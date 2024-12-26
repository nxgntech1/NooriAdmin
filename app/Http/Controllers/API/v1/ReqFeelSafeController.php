<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\AutoLoadController;
use App\Models\Requests;
use Illuminate\Http\Request;
use DB;
use Twilio\Rest\Client;

class ReqFeelSafeController extends Controller
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
  public function UpdateReq(Request $request)
  {


    $user_name = $request->get('user_name');
    $user_cat = $request->get('user_cat');
    $trip_id =$request->get('trip_id');
    $date_heure = date('Y-m-d H:i:s');
    $feel_safe = $request->get('feel_safe');
    $lat =$request->get('lat');
    $lng =$request->get('lng');

    if($user_cat == "driver"){

        $updatedata = DB::update('update tj_requete set feel_safe_driver = ?,modifier = ? where id = ?',[$feel_safe,$date_heure,$trip_id]);

        if ($updatedata > 0) {

            $sql = Requests::where('id',$trip_id)->first();
            $row = $sql->toArray();

            if($row > 0) {
                $row['id']=(string)$row['id'];
                $feel_safe_status = $row['feel_safe_driver'];


            }
          if($feel_safe_status == 1)
          {

        $body = "Hey I am ".$user_cat." ".$user_name.". My Trip id is ".$trip_id.". I Reached my destination safely.";
        $sid = env('TWILIO_SID_NEW');
        $token = env('TWILIO_TOKEN_NEW');
          }
          elseif($feel_safe_status == 0){
            $status = 'driver feel not safe';
            $insertdata = DB::insert("insert into tj_sos(ride_id,latitude,longitude,creer,status)
          values ('" . $trip_id . "','" . $lat . "','" . $lng . "','" . $date_heure . "','".$status."') ");
            $body = "Hey I am ".$user_cat." ".$user_name.".  My Trip id is ".$trip_id.". I am not Feeling safe! Please Help me. My Trip id is ".$trip_id;
            $sid = env('TWILIO_SID_NEW');
            $token = env('TWILIO_TOKEN_NEW');
          }

          $response['success'] = 'success';
          $response['error'] = null;
          $response['message'] = 'successful';
          $response['data'] = $row;
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed';
        }
    }elseif($user_cat == 'user_app'){
        $updatedata = DB::update('update tj_requete set feel_safe = ?,modifier = ? where id = ?',[$feel_safe,$date_heure,$trip_id]);

            if ($updatedata > 0) {
                $sql = Requests::where('id',$trip_id)->first();
                $row = $sql->toArray();
                if($row > 0) {
                    $row['id']=(string)$row['id'];
                    $feel_safe_status = $row['feel_safe_driver'];

                }
              if($feel_safe_status == 1)
              {
                $body = "Hey I am ".$user_cat." ".$user_name.". My Trip id is ".$trip_id.". I Reached my destination safely.";
                $sid = env('TWILIO_SID_NEW');
                $token = env('TWILIO_TOKEN_NEW');
              }
              elseif($feel_safe_status == 0){
                $status = 'user feel not safe';
                $insertdata = DB::insert("insert into tj_sos(ride_id,latitude,longitude,creer,status)
              values ('" . $trip_id . "','" . $lat . "','" . $lng . "','" . $date_heure . "','".$status."') ");
                 $body = "Hey I am ".$user_cat." ".$user_name.". My Trip id is ".$trip_id. ". I am not Feeling safe! Please Help me. My Trip id is ";
                 $sid = env('TWILIO_SID_NEW');
                 $token = env('TWILIO_TOKEN_NEW');
              }
          
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successful';
                $response['data'] = $row;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed';
            }


    } else {
        $response['success'] = 'Failed';
        $response['error'] = 'Not Found';
    }

    return response()->json($response);
  }

}
