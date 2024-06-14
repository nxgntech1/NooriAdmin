<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use DB;
class canceledLocationController extends Controller
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

  public function delete(Request $request)
  {
    $id = $request->get('id');
    $deleteData = DB::table('tj_location_vehicule')->where('id', $id)->delete();
    if ($deleteData>0) {
      $response['success']= 'Success';
      $response['error']= null;
      $response['message']= 'Location Deleted Success';

    } else {
      $response['success']= 'Failed';
      $response['error']= 'Failed to delete location';
    }
    return response()->json($response);
  }
       

}