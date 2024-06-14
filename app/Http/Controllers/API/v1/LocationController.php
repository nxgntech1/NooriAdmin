<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\VehicleLocation;
use App\Models\VehicleRental;
use Illuminate\Http\Request;
use DB;
class LocationController extends Controller {

	public function __construct() {
		$this -> limit = 20;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function getData(Request $request) {
		$id_user_app = $request -> get('id_user_app');

		if (!empty($id_user_app)) {

			$sql = DB::table('tj_location_vehicule')
				->Join('tj_type_vehicule_rental','tj_type_vehicule_rental.id','=','tj_location_vehicule.id_vehicule_rental')
				->select('tj_location_vehicule.id', 'tj_location_vehicule.nb_jour', 'tj_location_vehicule.date_debut', 'tj_location_vehicule.date_fin', 'tj_location_vehicule.contact', 'tj_location_vehicule.id_vehicule_rental', 'tj_location_vehicule.statut', 'tj_type_vehicule_rental.prix', 'tj_type_vehicule_rental.no_of_passenger', 'tj_location_vehicule.creer', 'tj_location_vehicule.modifier', 'tj_type_vehicule_rental.image', 'tj_type_vehicule_rental.libelle as libTypeVehicule')
				->where('tj_location_vehicule.id_user_app', '=', $id_user_app)
				->orderBy('tj_location_vehicule.id', 'desc') -> get();

			// output data of each row
			if ($sql -> count() > 0) {
				$output = array();
				foreach ($sql as $row) {
					$row->id=(string)$row->id;
					if ($row -> image != '') {
						if (file_exists(public_path('assets/images/type_vehicle_rental' . '/' . $row -> image))) {
							$image = asset('assets/images/type_vehicle_rental') . '/' . $row -> image;
						} else {
							$image = asset('assets/images/placeholder_image.jpg');

						}
						$row -> image = $image;
					}
						$output[] = $row;
				}


				if (!empty($row)) {
					$response['success'] = 'success';
					$response['error'] = null;
					$response['message'] = 'Successfully';
					$response['data'] = $output;
				} else {
					$response['success'] = 'Failed';
					$response['error'] = 'Failed to fetch data';
				}
			} else {
				$response['success'] = 'Failed';
				$response['error'] = 'Not Found';
			}

		} else {
			$response['success'] = 'Failed';
			$response['error'] = 'Id Required';
		}
		return response() -> json($response);

	}

}
