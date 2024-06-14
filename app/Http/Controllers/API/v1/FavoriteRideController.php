<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use DB;
class FavoriteRideController extends Controller
{

   public function __construct()
   {
      $this->limit=20;   
   }
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */


  public function register(Request $request)
  {
         
        $user_id = $request->get('id_user_app');
        $lat1 = $request->get('lat1');
        $lng1 = $request->get('lng1');
        $lat2 = $request->get('lat2');
        $lng2 = $request->get('lng2');
        $distance = $request->get('distance');
        $distance_unit = $request->get('distance_unit');
        $depart_name = $request->get('depart_name');
        $destination_name = $request->get('destination_name');
        $fav_name = $request->get('fav_name');
        $date_heure = date('Y-m-d H:i:s');
     
        $reqchkonride = DB::table('tj_favorite_ride')
        ->select('id')
        ->where('id_user_app','=',$user_id)
        ->where('latitude_depart','=',$lat1)
        ->where('longitude_depart','=',$lng1)
        ->where('latitude_arrivee','=',$lat2)
        ->where('longitude_arrivee','=',$lng2)
        ->where('libelle','=',$fav_name)
        ->count();
        if ($reqchkonride > 0) {
            $response['success']= 'Failed';
            $response['error']= 'Favorite Already Created';
        }else{
            $query = DB::insert("insert into tj_favorite_ride(libelle,depart_name,destination_name,id_user_app,latitude_depart,longitude_depart,latitude_arrivee,longitude_arrivee,statut,creer,modifier,distance,distance_unit)
            values('".$fav_name."','".$depart_name."','".$destination_name."','".$user_id."','".$lat1."','".$lng1."','".$lat2."','".$lng2."','yes','".$date_heure."','".$date_heure."','".$distance."','".$distance_unit."')");
           
            if($query > 0){
                $response['success']= 'Success';
                $response['error']= null;
            }else{
                $response['success']= 'Failed';
                $response['error']= 'Failed To Create';
            }
        }
    return response()->json($response);
  }

}