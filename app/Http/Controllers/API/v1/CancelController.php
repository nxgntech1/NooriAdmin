<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GcmController;
use App\Http\Controllers\PayfastAutoLoadController;
use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use DB;
class CancelController extends Controller
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

  public function Cancel(Request $request)
  {
         
 
    $response['msg']="Payment Canceled"; 
    if($request->get('requred_paramter')){
        $response['msg']="Insufficient parameters supplied.";
    }
   
    return response()->json($response);
  }
       
}