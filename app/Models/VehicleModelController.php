<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use DB;
class VehicleModelController extends Controller
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
  
  public function updateVehicleModel(Request $request)
  {
    $id_user = $request->get('id_conducteur');
    $model = $request->get('model');
    $model = str_replace("'","\'",$model);
    $date_heure = date('Y-m-d H:i:s');
    if(!empty($id_user) && !empty($model)){
    $updatedata = DB::update('update tj_vehicule set model = ?, modifier = ? where id_conducteur = ?',[$model,$date_heure,$id_user]);

    if (!empty($updatedata)) {
        $sql = Vehicle::where('id_conducteur',$id_user)->first();
        $row = $sql->toArray();
        $response['success'] = 'success';
        $response['error'] = null;
        $response['message'] = 'status successfully updated';
        $response['data'] = $row;
    } else {
        $response['success'] = 'Failed';
        $response['error'] = 'failed to update';
    }
} else{
    $response['success'] = 'Failed';
    $response['error'] = 'some field are missing';
}
        return response()->json($response);

    }
        

  
}