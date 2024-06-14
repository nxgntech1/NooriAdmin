<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class UserNicController extends Controller
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

  public function updateUserNic(Request $request)
  {


        $user_cat =  $request->get('user_cat');
        $image_name = $request->get('image_name');
        $image= $request->file('image');
        $date_heure = date('Y-m-d H:i:s');
        $path = 'assets/images/users';
        if($user_cat == "user_app"){

        	$id_user = $request->get('id_user');
        	if(empty($image)){
            	$response['success']= 'Failed';
            	$response['error']= 'Image Not Found';
        	} else{
		        $image = '';
		        $file = $request->file('image');
		        $extenstion = $file->getClientOriginalExtension();
		        $time = time().'.'.$extenstion;
		        $filename = 'User_nic'.$time;
		        $file->move(public_path('assets/images/users'), $filename);
		        $updatedata = DB::update('update tj_user_app set photo_nic = ?,photo_nic_path = ?,modifier = ? where id = ?',[$image,$filename,$date_heure,$id_user]);
		        if(!empty($updatedata)){
		            $updatestatus = DB::update('update tj_user_app set statut_nic = ? where id = ?',['uploaded',$id_user]);

		            $get_user = UserApp::
		            select('*')
		            ->where('id',$id_user)
		            ->orderby('modifier', 'desc')
		            ->get();
		            foreach($get_user as $row){
		                $image = $row->photo_nic_path;
		                $image_user = $row->photo_path;
		                $image_path = asset('assets/images/users').'/'.$image;
		                $row->photo_path = $image_path;
		                $row->photo_nic_path = $image_path;
		                if($image_user != ''){
		                    if(file_exists(public_path('assets/images/users'.'/'.$image_user )))
		                    {
		                        $image_user = asset('assets/images/users').'/'. $image_user;
		                    }
		                    else
		                    {
		                        $image_user = asset('assets/images/placeholder_image.jpg');

		                    }
		                    $row->photo_nic_path = $image_user;
		                }

		                $response['success']= 'success';
		                $response['error']= null;
		                $response['message']= 'Photo Nic successfully updated';
		                $response['data'] = $row;
		            }
		    } else{
		        $response['success']= 'Failed';
		        $response['error']= 'Image Not Updated';
		    }
			}

        }elseif($user_cat == "driver"){

            $id_user = $request->get('id_user');
            if(empty($image)){
                $response['success']= 'Failed';
                $response['error']= 'Image Not Found';
            }else{

	            $image ='';
	            $file = $request->file('image');
	            $extenstion = $file->getClientOriginalExtension();
	            $time = time().'.'.$extenstion;
	            $filename = 'driver_nic'.$time;
	            $file->move(public_path('assets/images/driver'), $filename);

	            $updatedata = DB::update('update tj_conducteur set photo_nic = ?,photo_nic_path = ?,modifier = ? where id = ?',[$image,$filename,$date_heure,$id_user]);

	            if($updatedata > 0){

	               if(!empty($image))

	                $updatestatus = DB::update('update tj_conducteur set statut_nic = ? where id = ?',['uploaded',$id_user]);

	                $get_user = Driver::
	                select('*')
	                ->where('id',$id_user)
	                ->orderby('modifier', 'desc')
	                ->get();
	                foreach($get_user as $row){
	                    if( $row->photo_path != ''){
	                    if(file_exists(public_path('assets/images/driver/'.$row->photo_path ))){
	                        $image_user='assets/images/driver/'.$row->photo_path;
	                        }else{
	                         $image_user ='assets/images/placeholder_image.jpg';
	                         }
	                         $row->photo_path = asset($image_user);
	                        }

	                        $row->photo = '';

	                        $row->photo_licence = '';
	                        $row->photo_nic = '';
	                        $row->photo_car_service_book = '';
	                        $row->photo_road_worthy = '';
	                         if($row->photo_nic_path != ''){
	                            if(file_exists(public_path('assets/images/driver'.'/'.$row->photo_nic_path )))
	                            {
	                                $image = asset('assets/images/driver').'/'. $row->photo_nic_path;
	                            }
	                            else
	                            {
	                                $image =asset('assets/images/placeholder_image.jpg');

	                            }
	                            $row->photo_nic_path = $image;
	                        }

	                        if( $row->photo_licence_path != ''){
	                            if(file_exists(public_path('assets/images/driver'.'/'. $row->photo_licence_path )))
	                            {
	                                $image_licence = asset('assets/images/driver').'/'.  $row->photo_licence_path;
	                            }
	                            else
	                            {
	                                $image_licence =asset('assets/images/placeholder_image.jpg');

	                            }
	                            $row->photo_licence_path = $image_licence;
	                        }
	                        if($row->photo_car_service_book_path != ''){
	                            if(file_exists(public_path('assets/images/driver'.'/'.$row->photo_car_service_book_path )))
	                            {
	                                $image_car = asset('assets/images/driver').'/'.$row->photo_car_service_book_path;
	                            }
	                            else
	                            {
	                                $image_car =asset('assets/images/placeholder_image.jpg');

	                            }
	                            $row->photo_car_service_book_path = $image_car;
	                        }

	                        if( $row->photo_road_worthy_path != ''){
	                            if(file_exists(public_path('assets/images/driver'.'/'. $row->photo_road_worthy_path )))
	                            {
	                                $image_road = asset('assets/images/driver').'/'.  $row->photo_road_worthy_path;
	                            }
	                            else
	                            {
	                                $image_road =asset('assets/images/placeholder_image.jpg');

	                            }
	                            $row->photo_road_worthy_path = $image_road;
	                        }
	                $response['success']= 'success';
	                $response['error']= null;
	                $response['message']= 'status nic successfully updated';
	                $response['data'] = $row;
			 }
        }else{
        	$response['success']= 'Failed';
        	$response['error']= 'Image Not Updated';
        }
		}
    }else{
        $response['success']= 'Failed';
        $response['error']= 'Not Found';
    }

    return response()->json($response);
  }

}
