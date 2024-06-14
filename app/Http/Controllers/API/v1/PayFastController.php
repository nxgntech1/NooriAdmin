<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use DB;
class PayFastController extends Controller
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
  

  public function payFast(Request $request)
  {
    $id_requete = ($request->get('id_ride'))?$request->get('id_ride'):'';
    $id_user = ($request->get('id_driver'))?$request->get('id_driver'):'';
    $amount = ($request->get('amount'))?$request->get('amount'):'';
    $tip_amount = ($request->get('tip_amount'))?$request->get('tip_amount'):'';
    if($tip_amount){
        $amount=$tip_amount+$amount;
    }
    $name_first = ($request->get('name_first'))?$request->get('name_first'):'';
    $name_last = ($request->get('name_last'))?$request->get('name_last'):'';
    $from_id = ($request->get('from_id'))?$request->get('from_id'):'';
    $email_address = ($request->get('email_address'))?$request->get('email_address'):'';
    if($id_requete && $id_user && $email_address && $amount){
        $sql = DB::table('tj_requete')
        ->select('id')
        ->where('id','=',$id_requete)
        ->get();
        if (!empty($sql)) {
           
           
        }
    }
        
       
  }
}