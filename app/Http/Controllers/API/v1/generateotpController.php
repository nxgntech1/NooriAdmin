<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\Commission;
use App\Models\UserApp;
use App\Models\Requests;
use App\Models\Settings;
use Illuminate\Http\Request;
use DB;
class generateotpController extends Controller
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

  public function OTP(Request $request)
  {

        $id_user_app =  $request->get('id_user_app');

        $ride_id = $request->get('ride_id');
        $otp = random_int(100000, 999999);

        $otp_setting=Settings::select('show_ride_otp')->first();

        $user =  Requests::where('id',$ride_id)->where('id_user_app',$id_user_app)->first();

        if(!empty($user) && $user->ride_type!='dispatcher'  ){
          if($otp_setting->show_ride_otp=="yes"){
            $user->otp = $otp;
            $user->otp_created = now();
            $user->save();
          }
        }

        $row = Requests::select('otp')->where('id',$ride_id)->first();

        $response = array();
        if($row){
            $response['success']= 'success';
            $response['error']= null;
            $response['message']='Successfully';
            $response['data'] = $row;
        }else{
            $response['success']= 'Failed';
            $response['error']= 'Failed to fetch data';
            $response['message']='Failed to fetch data';
        }

        return response()->json($response);

  }
}
