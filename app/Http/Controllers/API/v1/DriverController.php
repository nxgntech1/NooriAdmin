<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Settings;
use App\Models\Zone;
use Illuminate\Http\Request;
use DB;
class DriverController extends Controller {

	public function __construct() {
		$this -> limit = 20;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$users = Driver::all();
		$users = Driver::paginate($this -> limit);
		return response() -> json($users);
	}

	public function getData(Request $request) {

		$lat1 = $request -> get('lat1');
		$lng1 = $request -> get('lng1');
		$setting=Settings::first();
		$amount=$setting->minimum_deposit_amount;

		$sql = DB::table('tj_type_vehicule')
			 -> crossJoin('tj_vehicule')
			 -> crossJoin('tj_conducteur')
			 -> crossJoin('zones')
			 -> select('tj_conducteur.id', 'tj_conducteur.nom', 'tj_conducteur.prenom',
			 'tj_conducteur.phone', 'tj_conducteur.email', 'tj_conducteur.online',
			 'tj_conducteur.photo_path as photo', 'tj_conducteur.latitude',
			  'tj_conducteur.longitude', 'tj_vehicule.id as idVehicule',
				'tj_vehicule.brand', 'tj_vehicule.model', 'tj_vehicule.color',
				 'tj_vehicule.numberplate', 'tj_vehicule.passenger',
				 'tj_type_vehicule.libelle as typeVehicule','tj_conducteur.zone_id','zones.id as zone_id')
				-> where('tj_vehicule.id_type_vehicule', '=', DB::raw('tj_type_vehicule.id'))
			 	-> where('tj_vehicule.id_conducteur', '=', DB::raw('tj_conducteur.id'))
				->whereRaw("find_in_set(zones.id,tj_conducteur.zone_id)")
			  -> where('tj_vehicule.statut', '=', 'yes')
				-> where('tj_conducteur.statut', '=', 'yes')
				-> where('tj_conducteur.is_verified', '=', '1')
				-> where('tj_conducteur.online', '!=', 'no')
				-> where('tj_conducteur.latitude', '!=', '')
				-> where('tj_conducteur.longitude', '!=', '')
				-> where('tj_conducteur.amount', '>=', $amount)
				-> where('zones.status', '=', 'yes');

		if ($request -> get('type_vehicle')) {
			$id_cat_taxi = $request -> get('type_vehicle');
			$sql->where('tj_type_vehicule.id', '=', $id_cat_taxi);
		}
		
		$results = $sql->get();

		if ($results -> count() > 0) {

			$output = array();
			foreach ($results as $row) {

				if($row->zone_id){
					$zone= Zone::find($row->zone_id);
					$zone_area_json = $zone->area->toJson();
					$zone_area_array = json_decode($zone_area_json, true);
					$zone_area_latlong = $zone_area_array['coordinates'][0];
					$vertices_x = $vertices_y = [];
					foreach($zone_area_array['coordinates'] as $key => $data){
						foreach($data as $k=>$v){
							$vertices_x[] = $v[1];
							$vertices_y[] = $v[0];
						}
					}
					$points_polygon = count($vertices_x)-1; 
					if($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $lat1, $lng1)){
						$row->in_zone = 'yes';
					}else{
						$row->in_zone = 'no';
					}
				}
				
				if($row->in_zone == "yes"){

					$row->id=(string)$row->id;
					$row->idVehicule=(string)$row->idVehicule;
					if ($row -> latitude != '' && $row -> longitude != ''){
						$row -> distance = DriverController::distance($row -> latitude, $row -> longitude, $lat1, $lng1, 'K');
					}

					$id_conducteur = $row -> id;
					$sql_nb_avis = DB::table('tj_note') -> select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme")) -> where('id_conducteur', '=', $id_conducteur) -> get();

					if (!empty($sql_nb_avis)) {
						foreach ($sql_nb_avis as $row_nb_avis) {
							$somme = $row_nb_avis -> somme;
							$nb_avis = $row_nb_avis -> nb_avis;
							if ($nb_avis != 0) {
								$moyenne = $somme / $nb_avis;
							} else {
								$moyenne = 0;
							}
						}
					} else {
						$somme = 0;
						$nb_avis = 0;
						$moyenne = 0;
					}
					$row -> moyenne = $moyenne;

					$sql_total = DB::table('tj_requete') -> select(DB::raw("COUNT(id) as total_completed_ride")) ->where('id_conducteur', '=', $id_conducteur) -> where('statut', '=', 'completed') -> get();
					foreach ($sql_total as $row_total) {
						$row -> total_completed_ride = $row_total -> total_completed_ride;
					}

					if ($row -> photo != '') {
						if (file_exists(public_path('assets/images/driver' . '/' . $row -> photo))) {
							$image_user = asset('assets/images/driver') . '/' . $row -> photo;
						} else {
							$image_user = asset('assets/images/placeholder_image.jpg');
						}
						$row -> photo = $image_user;
					}

					$row -> distance = (string) $row -> distance;
					$row -> moyenne = (string) $row -> moyenne;
					$row -> total_completed_ride = (string) $row -> total_completed_ride;
					$row -> zone_id = $row -> zone_id ? explode(',',$row -> zone_id) : [];

					$output[] = $row;

				}
			}

			if(count($output) > 0){
				
				function cmp($a, $b) {
					if ($a -> distance == $b -> distance)
						return 0;
					return ($a -> distance < $b -> distance) ? -1 : 1;
				}
	
				usort($output, 'App\Http\Controllers\API\v1\cmp');
	
				$response['success'] = 'Success';
				$response['error'] = null;
				$response['message'] = 'Successfully fetched data';
				$response['data'] = $output;

			}else{

				$response['success'] = 'Failed';
				$response['error'] = 'Not Found';	
			}
			
		} else {
			$response['success'] = 'Failed';
			$response['error'] = 'Not Found';
		}

		return response() -> json($response);

	}

	public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
		$i = $j = $c = $point = 0;
		for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
			$point = $i;
			if( $point == $points_polygon )
				$point = 0;
			if ( (($vertices_y[$point]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point]) ) )
				$c = !$c;
		}
		return $c;
	}

	public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		if (($lat1 == $lat2) && ($lon1 == $lon2)) {
			return 0;
		} else {
			$theta = $lon1 - $lon2;

			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K") {
				return ($miles * 1.609344);
			} else if ($unit == "N") {
				return ($miles * 0.8684);
			} else {
				return $miles;
			}
		}
	}

}
