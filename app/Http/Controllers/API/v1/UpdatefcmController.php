<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;

class UpdatefcmController extends Controller
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


  public function updatefcm(Request $request)
  {

        $user_id = $request->get('user_id');
        $fcm_id=$request->get('fcm_id');
        $device_id=$request->get('device_id');
        $user_cat = $request->get('user_cat');
        $date_heure = date('Y-m-d H:i:s');
        if($user_cat == "user_app"){

            $update_query = DB::update('update tj_user_app set fcm_id = ?,device_id = ?,modifier = ? where id = ?',[$fcm_id,$device_id,$date_heure,$user_id]);

            if($update_query){
                $sql = UserApp::where('id',$user_id)->first();
                $row = $sql->toArray();
                $row['id'] =(string)$row['id'];
                $row['photo'] = '';
                $row['photo_nic'] = '';
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
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successful';
                $response['data'] = $row;
            }else{
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update';
            }
        }elseif($user_cat == 'driver'){
            $update_query = DB::update('update tj_conducteur set fcm_id = ?,device_id = ?,modifier = ? where id = ?',[$fcm_id,$device_id,$date_heure,$user_id]);

            if($update_query){
                $sql = Driver::where('id',$user_id)->first();
                $row = $sql->toArray();
                $row['id'] =(string)$row['id'];
                $row['photo'] = '';
                $row['photo_licence'] = '';
                $row['photo_nic'] = '';
                $row['photo_car_service_book'] = '';
                $row['photo_road_worthy'] = '';
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
                $response['message'] = 'successful';
                $response['data'] = $row;
            }else{
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update';
            }
        }else{
            $response['success'] = 'Failed';
            $response['error'] = 'Not Found';
        }

   return response()->json($response);
  }
}
