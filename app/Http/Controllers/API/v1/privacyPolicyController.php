<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Commission;
use Illuminate\Http\Request;
use DB;
class privacyPolicyController extends Controller
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
    
    $sql = DB::table('tj_privacy_policy')
    ->get();

    foreach($sql as $row){
     
        if(!empty($sql)){
          $row->id=(string)$row->id; 
          $response['success']= 'success';
          $response['error']= null;
          $response['message']= 'successfully';
          $response['data']= $row;
        }else{
          $response['success']= 'Failed';
          $response['error']= 'Failed to fetch data';
          $response['message']= 'successfully';
        }
        return response()->json($response);

    }
        
  }
  
}