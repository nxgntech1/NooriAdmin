<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class UserAddressController extends Controller
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

  public function UpdateUserAddress(Request $request)
  {

        $id_user = $request->get('id_user');
        $address = $request->get('address');
        $address = str_replace("'","\'",$address);
        $user_cat = $request->get('user_cat');
        $date_heure = date('Y-m-d H:i:s');
        $photo = '';

        if($user_cat == "user_app"){


            $updatedata =  DB::update('update tj_user_app set address = ?,modifier = ? where id = ?',[$address,$date_heure,$id_user]);

            if ($updatedata > 0) {
                $get_user = UserApp::where('id',$id_user)->first();
                $row = $get_user->toArray();
                $row['id'] =(string)$row['id'];
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
                    $row['photo'] = $photo;
                    $row['photo_nic'] = $photo;



                $response['success']= 'success';
                $response['error']= null;
                $response['message']= 'Address successfully updated';
                $response['data'] = $row;

            } else {
                $response['success']= 'Failed';
                $response['error']= 'Failed to update Address';
            }


        }elseif($user_cat == "driver"){

            $updatedata =  DB::update('update tj_conducteur set address = ?,modifier = ? where id = ?',[$address,$date_heure,$id_user]);

            if ($updatedata > 0) {
                $get_user = Driver::where('id',$id_user)->first();
                $row = $get_user->toArray();
                $row['id'] =(string)$row['id'];
                if(file_exists(public_path('assets/images/driver/'.$row['photo_path'] ))){
                    $image_user='assets/images/driver/'.$row['photo_path'];
                    }else{
                     $image_user ='assets/images/placeholder_image.jpg';
                     }

                    $row['photo'] = '';
                    $row['photo_path'] = asset($image_user);
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

                $response['success']= 'success';
                $response['error']= null;
                $response['message']= 'Address successfully updated';
                $response['data'] = $row;
            } else {
                $response['success']= 'Failed';
                $response['error']= 'Failed to update Address';
            }

        }
        else{
            $response['success']= 'Failed';
            $response['error']= 'Not Found';
        }

        return response()->json($response);
  }

}
