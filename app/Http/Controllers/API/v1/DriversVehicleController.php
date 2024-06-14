<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\Commission;
use App\Models\Vehicle;
use App\Models\Driver;

use Illuminate\Http\Request;
use DB;
class DriversVehicleController extends Controller
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
    $id_driver =  $request->get('id_driver');

    $sql = Vehicle::where('id_conducteur',$id_driver)->where('tj_vehicule.statut','=','yes')->first();
    $row = $sql;

    if(!empty($sql)){
      
      $vehicle_type_id=$sql->id_type_vehicule;
      
      $delivery_charge = DB::table('delivery_charges')->select('minimum_delivery_charges_within_km', 'minimum_delivery_charges', 'delivery_charges_per_km')
        ->where('id_vehicle_type', '=', $vehicle_type_id)->first();
      if (!empty($delivery_charge)) {
          $row->minimum_delivery_charges_within_km = $delivery_charge->minimum_delivery_charges_within_km;
          $row->minimum_delivery_charges = $delivery_charge->minimum_delivery_charges;
          $row->delivery_charges_per_km = $delivery_charge->delivery_charges_per_km;
      }else{
          $row->minimum_delivery_charges_within_km = '';
          $row->minimum_delivery_charges = '';
          $row->delivery_charges_per_km = '';
      }

      $driver = Driver::find($id_driver);
      $row->zone_id = $driver->zone_id ? explode(',',$driver->zone_id) : [];

    }

    if(!empty($row)){
      $response['success']= 'success';
      $response['error']= null;
      $response['message']= 'Successfully';
      $response['data'] = $row;
    }else{
      $response['success']= 'Failed';
      $response['error']= 'No Data Found';
      $response['message']= null;
    }

    return response()->json($response);  
}
  
}