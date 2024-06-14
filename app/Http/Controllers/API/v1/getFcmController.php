<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class getFcmController extends Controller
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
    $id_user = $request->get('id_user');
    $cat_user = $request->get('cat_user');
      
    if($cat_user == "user_app"){

        $sql = DB::table('tj_user_app')
        ->select('fcm_id')
        ->where('id','=',$id_user)
        ->get();
        foreach($sql as $row){
        }
        $token =  $row->fcm_id;
        if(!empty($row)){
          $response['success'] = 'success';
          $response['error'] = null;
          $response['message'] = 'successfully';
          $response['data'] = $row;

        }else{
          $response['success'] = 'Failed';
          $response['error'] = 'Failed';
    
        }

    }elseif($cat_user == "driver"){
        $sql = DB::table('tj_conducteur')
        ->select('fcm_id')
        ->where('id','=',$id_user)
        ->get(); 
        foreach($sql as $row){
        }
        $token =  $row->fcm_id;
        if(!empty($row)){
          $response['success'] = 'success';
          $response['error'] = null;
          $response['message'] = 'successfully';
          $response['data'] = $row;

        }else
        {
          $response['success'] = 'Failed';
          $response['error'] = 'Failed';
    
        }
    }
    else{
      $response['success'] = 'Failed';
      $response['error'] = 'Failed';

    }

    return response()->json($response);
  }

}