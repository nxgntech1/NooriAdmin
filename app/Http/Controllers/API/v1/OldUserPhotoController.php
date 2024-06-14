<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;
use Image;

class OldUserPhotoController extends Controller
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
  public function UpdateUserPhoto(Request $request)
  {
    $image = $request->file('image');
    $user_cat = $request->get('user_cat');
    $date_heure = date('Y-m-d H:i:s');


    if($user_cat == "user_app"){
        $id_user = $request->get('id_user');
        $get_user = UserApp::where('id',$id_user)->first();
        if(!$get_user)
        {
            $response['success']= 'Failed';
            $response['error']= 'User Not Found';
        }else{
        if(empty($image))
        {
            $response['success']= 'Failed';
            $response['error']= 'Image Not Found';
        } else
        {
          $destination = public_path('assets/images/users/' . $get_user->photo_path);

          if (File::exists($destination)) {
              File::delete($destination);
          }
            $image = '';
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $time = time().'.'.$extenstion;
            $filename = 'User_photo'.$time;
            $path = public_path('assets/images/users/') . $filename;
            Image::make($file->getRealPath())->resize(150, 150)->save($path);
        
            $updatedata = DB::update('update tj_user_app set photo = ?,photo_path = ?,modifier = ? where id = ?',[$image,$filename,$date_heure,$id_user]);

        }
        $sql = DB::table('tj_user_app')
        ->select('*')
        ->where('id','=',$id_user)
        ->orderby('modifier','Desc')
        ->get();


        foreach($sql as $row)
        if(!empty($row)){

            $image = $row->photo_path;
            $row->photo = $image;
            $image_nic = $row->photo_nic_path;
            $image_path = asset('assets/images/users').'/'.$image;
            
            $row->photo_path = $image_path;

            if($image_nic != ''){
                if(file_exists( public_path().'assets/images/users'.'/'.$image_nic ))
                {
                    $image_user = asset('assets/images/users').'/'. $image_nic;
                }
                else
                {
                    $image_user = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_nic_path = $image_user;
            }

            $response['success']= 'Success';
            $response['error']= null;
            $response['data'] = $row;
        } else {
            $response['success']= 'Failed';
            $response['error']= 'Update Image Failed';
        }
    }
}elseif($user_cat == "driver"){
            $id_driver = $request->get('id_driver');

            $get_user = Driver::where('id',$id_driver)->first();

            if(!$get_user)
            {
                $response['success']= 'Failed';
                $response['error']= 'Driver Not Found';
            }else{
            if(empty($image))
            {
                $response['success']= 'Failed';
                $response['error']= 'Image Not Found';
            } else{
              $destination = public_path('assets/images/driver/' . $get_user->photo_path);

              if (File::exists($destination)) {
                  File::delete($destination);
              }
                $image = '';
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $time = time().'.'.$extenstion;
                $filename = 'Driver_photo'.$time;
                $path = public_path('assets/images/driver/') . $filename;
                Image::make($file->getRealPath())->resize(150, 150)->save($path);

                //$file->move(public_path('assets/images/driver'), $filename);

                $updatedata = DB::update('update tj_conducteur set photo = ?,photo_path = ?,modifier = ? where id = ?',[$image,$filename,$date_heure,$id_driver]);

            }
            $sql = DB::table('tj_conducteur')
            ->select('*')
            ->where('id','=',$id_driver)
            ->get();

            foreach($sql as $row)

            if(!empty($row)){
                $image = $row->photo_path;
                $row->photo = '';
                $image_nic = $row->photo_nic_path;
                $row->photo_nic = '';
                $row->photo_licence = '';
                $row->photo_car_service_book = '';
                $row->photo_road_worthy = '';
                $image_path = asset('assets/images/driver').'/'.$image;
                $row->photo_path = $image_path;

                if($image_nic != ''){
                    if(file_exists(public_path('assets/images/driver'.$image_nic )))
                    {
                         $image_user = asset('assets/images/driver'). $image_nic;
                    }
                    else
                    {
                       $image_user = asset('assets/images/placeholder_image.jpg');

                    }
                    $row->photo_nic_path = $image_user;
                }

                $response['success']= 'success';
                $response['error']= null;
                $response['message'] = 'Successfully updated image';
                $response['data'] = $row;
            } else {
                $response['success']= 'Failed';
                $response['error']= 'Update Image Failed';
            }
        }
    }else{
        $response['success']= 'Failed';
        $response['error']= 'Not Found';
    }
    return response()->json($response);
  }

}
