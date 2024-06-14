<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Drivers;
use App\Models\vehicleImages;
use Illuminate\Http\Request;
use DB;
class CarImagesDriversNameController extends Controller
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
  
  public function getData(Request $request)
  {
   
    $id_user =$request->get('id_driver');
    $sql = DB::table('tj_vehicule')
    ->crossJoin('tj_conducteur')
    ->select('tj_vehicule.numberplate', 'tj_conducteur.nom', 'tj_conducteur.prenom', 'tj_conducteur.photo', 'tj_conducteur.photo_path')
    ->where('tj_vehicule.id_conducteur','=',$id_user)
    ->where('tj_conducteur.id','=',$id_user)
    ->get();
    
    // output data of each row
    foreach($sql as $row)
    {
        $nom = $row->nom;
		$prenom = $row->prenom;
		$driver_name = $nom.' '.$prenom;
		$car_numberplate = $row->numberplate;
		$photo =  $row->photo;
		$photo_path =  $row->photo_path;
    }

    $sql = DB::table('tj_vehicle_images')
    ->select('image', 'image_path')
    ->where('id_driver','=',$id_user)
    ->get();
    $images = array();
    foreach($sql as $row)
    {
        array_push($images,$row);
    }
    $images = json_encode($images, JSON_FORCE_OBJECT);

    $response['driver_name'] = $driver_name;
    $response['car_numberplate'] = $car_numberplate;
    $response['driver_photo'] = $photo;
    $response['driver_photo_path'] = $photo_path;
    $response['images'] = $images;
    if(!empty($sql)){
        $response['msg']['etat'] = 1;
    }else{
        $response['msg']['etat'] = 2;
    }
        return response()->json($response);

    }
        

  
}