<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Commission;
use Illuminate\Http\Request;
use DB;
class termsofConditionController extends Controller
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

    $sql = DB::table('tj_terms_and_conditions')
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
          $response['message']= null;
        }
        return response()->json($response);

    }

  }

}
