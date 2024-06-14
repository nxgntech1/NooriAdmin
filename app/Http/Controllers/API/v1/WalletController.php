<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use Illuminate\Http\Request;
use DB;

class WalletController extends Controller
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
    $cat_user = $request->get('user_cat');
    if(!empty($id_user) && !empty($cat_user)){
    if($cat_user == "user_app"){
        $sql = DB::table('tj_user_app')
        ->select('amount')
        ->where('id','=',$id_user)
        ->get();
    
    }elseif($cat_user == "driver"){
        $sql = DB::table('tj_conducteur')
        ->select('amount')
        ->where('id','=',$id_user)
        ->get();
    }
    else{
        $response['success']= 'Failed';
        $response['error']= 'Not Found';
    }
    $amount = "0";
    // output data of each row
    foreach($sql as $row)
    {
        $amount = $row->amount;
    }
       
        if(!empty($row)){
            $response['success']= 'success';
            $response['error']= null;
            $response['message'] = 'Successfully';
            $response['data'] = $row;

        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to Fetch data';
        }
    }else{
        $response['success']= 'Failed';
        $response['error']= 'some field are missing';
    }
        return response()->json($response);

    }
        

  
}