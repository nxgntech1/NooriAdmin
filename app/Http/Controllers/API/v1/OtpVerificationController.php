<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Commission;
use App\Models\Requests;
use App\Models\ParcelOrder;

use Illuminate\Http\Request;
use DB;
class OtpVerificationController extends Controller
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
  
  public function VerifyOTP(Request $request)
  {
    $id_user_app =  $request->get('id_user_app');
    $ride_id = $request->get('ride_id');
    $otp =  $request->get('otp');
    $ride_type = $request->get('ride_type');
    if(!empty($otp)){
     if(!empty($ride_type) || $ride_type=='parcel'){
        $otpsql = ParcelOrder::where('id', $ride_id)->where('id_user_app', $id_user_app)->get();
      }else{
        $otpsql = Requests::where('id', $ride_id)->where('id_user_app', $id_user_app)->get();
      }
        foreach ($otpsql as $row) {
          if (!empty($row->otp)) {
            if ($row->otp == $otp) {
              $response['success'] = 'success';
              $response['error'] = null;
              $response['message'] = 'Successfully Verified OTP';
            } else {
              $response['success'] = 'Failed';
              $response['error'] = 'OTP is Incorrect';
              $response['message'] = 'OTP is Incorrect';

            }
          }
        }
      }
        return response()->json($response);

    }
        
  }
  