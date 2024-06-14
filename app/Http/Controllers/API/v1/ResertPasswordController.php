<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use DB;
class ResertPasswordController extends Controller
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


  public function resertPassword(Request $request)
  {
    $email = $request->get('email');
    $otp = $request->get('otp');
    $newPassword = $request->get('new_password');
    $new_password = str_replace("'","\'",$newPassword);
    $new_password = md5($new_password);
    $conformPassword = $request->get('confirm_password');
    $conform_password = str_replace("'","\'",$conformPassword);
    $conform_password = md5($conform_password);
    $date_heure = date('Y-m-d H:i:s');
    $time = strtotime($date_heure);
    $time = $time - (30 * 60);
    $date = date("Y-m-d H:i:s", $time);
    $user_cat = $request->get('user_cat');


    if($newPassword == $conformPassword){
        if($user_cat == 'user_app'){
        $sql = DB::table('tj_user_app')
        ->select('id','reset_password_otp','reset_password_otp_modifier')
        ->where('email','=',$email)
        ->get();
        if($sql->count() > 0){

        foreach($sql as $row)
        {
            $id = $row->id;

        }

        if($id > 0){
                $user_id = $row->id;
                $saved_otp = $row->reset_password_otp;
                $otp_saved_at = $row->reset_password_otp_modifier;

                if($otp_saved_at > $date) {
                    if($saved_otp == $otp){
                        $updatedata = DB::update('update tj_user_app set mdp = ? , modifier = ? where id = ?',[$new_password,$date_heure,$user_id]);

                        if (!empty($updatedata)) {
                            $sql = UserApp::where('id',$user_id)->first();
                            $row = $sql->toArray();
                            $row['id']=(string)$row['id'];
                            if($row['photo_path'] != ''){
                                if(file_exists(public_path('assets/images/users'.'/'.$row['photo_path'] )))
                                {
                                    $image_user = asset('assets/images/users').'/'. $row['photo_path'];
                                }
                                else
                                {
                                    $image_user =asset('assets/images/placeholder_image.jpg');

                                }
                                $row['photo_path'] = $image_user;
                            }
                                if($row['photo_nic_path'] != ''){
                                if(file_exists(public_path('assets/images/users'.'/'.$row['photo_nic_path'] )))
                                {
                                    $image = asset('assets/images/users').'/'. $row['photo_nic_path'];
                                }
                                else
                                {
                                    $image =asset('assets/images/placeholder_image.jpg');

                                }
                                $row['photo_nic_path'] = $image;
                            }
                                $row['photo'] = '';
                                $row['photo_nic'] = '';

                            $response['success'] = 'success';
                            $response['error'] = null;
                            $response['message'] = "Password Saved Successfully";
                            $response['data'] = $row;

                        } else {
                            $response['success'] = 'Failed';
                            $response['error'] = 'Failed';
                        }
                    }else{
                        $response['success'] = 'Failed';
                        $response['error'] = "OTP Does not Match";
                    }
                }else{
                    $response['success'] = 'Failed';
                    $response['error']="OTP Is not valid";

                }
            }else{
                $response['success'] = 'Failed';
                $response['error']="Not found";

            }
        }else{
            $response['success'] = 'Failed';
            $response['error']="Email is Not Exist";

        }
        }elseif($user_cat == 'driver'){
            $sql = DB::table('tj_conducteur')
            ->select('id','reset_password_otp','reset_password_otp_modifier')
            ->where('email','=',$email)
            ->get();
            if($sql->count() > 0){
            foreach($sql as $row){
                $id = $row->id;
            }
            if($id > 0){

                    $user_id = $row->id;
                    $saved_otp = $row->reset_password_otp;
                    $otp_saved_at = $row->reset_password_otp_modifier;

                    if($otp_saved_at > $date) {

                    if($saved_otp == $otp){

                        $updatedata = DB::update('update tj_conducteur set mdp = ? , modifier = ? where id = ?',[$new_password,$date_heure,$user_id]);

                        if (!empty($updatedata)) {
                            $sql = Driver::where('id',$user_id)->first();
                            $row = $sql->toArray();
                            $row['id']=(string)$row['id'];
                            $row['photo'] = '';
                            if($row['photo_path'] != ''){
                                if(file_exists(public_path('assets/images/driver'.'/'.$row['photo_path'] )))
                                {
                                    $image_user = asset('assets/images/driver').'/'. $row['photo_path'];
                                }
                                else
                                {
                                    $image_user =asset('assets/images/placeholder_image.jpg');

                                }
                                $row['photo_path'] = $image_user;
                            }
                            $row['photo_licence'] = '';
                            $row['photo_nic'] = '';
                            $row['photo_car_service_book'] = '';
                            $row['photo_road_worthy'] = '';
                             if($row['photo_nic_path'] != ''){
                                if(file_exists(public_path('assets/images/driver'.'/'.$row['photo_nic_path'] )))
                                {
                                    $image = asset('assets/images/driver').'/'. $row['photo_nic_path'];
                                }
                                else
                                {
                                    $image =asset('assets/images/placeholder_image.jpg');

                                }
                                $row['photo_nic_path'] = $image;
                            }

                            if($row['photo_licence_path'] != ''){
                                if(file_exists(public_path('assets/images/driver'.'/'.$row['photo_licence_path'] )))
                                {
                                    $image_licence = asset('assets/images/driver').'/'. $row['photo_licence_path'];
                                }
                                else
                                {
                                    $image_licence =asset('assets/images/placeholder_image.jpg');

                                }
                                $row['photo_licence_path'] = $image_licence;
                            }
                            if($row['photo_car_service_book_path'] != ''){
                                if(file_exists(public_path('assets/images/driver'.'/'.$row['photo_car_service_book_path'] )))
                                {
                                    $image_car = asset('assets/images/driver').'/'. $row['photo_car_service_book_path'];
                                }
                                else
                                {
                                    $image_car =asset('assets/images/placeholder_image.jpg');

                                }
                                $row['photo_car_service_book_path'] = $image_car;
                            }

                            if($row['photo_road_worthy_path'] != ''){
                                if(file_exists(public_path('assets/images/driver'.'/'.$row['photo_road_worthy_path'] )))
                                {
                                    $image_road = asset('assets/images/driver').'/'. $row['photo_road_worthy_path'];
                                }
                                else
                                {
                                    $image_road =asset('assets/images/placeholder_image.jpg');

                                }
                                $row['photo_road_worthy_path'] = $image_road;
                            }

                            $response['success'] = 'success';
                            $response['error'] = null;
                            $response['message'] = "Password Saved Successfully";
                            $response['data'] = $row;
                        } else {
                            $response['success'] = 'Failed';
                            $response['error'] = 'Failed';
                        }
                    }else{
                        $response['success'] = 'Failed';
                        $response['error'] = "OTP Does not Match";
                    }
                }else{
                    $response['success'] = 'Failed';
                    $response['error']="OTP Is not valid";
                }

            }else{
                $response['success'] = 'Failed';
                $response['error']="Email is not Exist";
            }
        }else{
            $response['success'] = 'Failed';
            $response['error']="Email is not Exist";
        }
        }
    else{
        $response['success'] = 'Failed';
        $response['error']="Not Found";
    }
    }else{
        $response['success'] = 'Failed';
        $response['error']="Confirm password does not match with Password";
    }

   return response()->json($response);
  }
}
