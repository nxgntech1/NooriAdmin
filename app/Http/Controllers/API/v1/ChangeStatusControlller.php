<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class ChangeStatusControlller extends Controller
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

  public function changeStatus(Request $request)
  {

    $id_conducteur = $request->get('id_driver');
    $online = $request->get('online');
  if(!empty($id_conducteur) && !empty($online)){
    $updatedata =  DB::update('update tj_conducteur set online = ? where id = ?',[ $online,$id_conducteur]);

    if (!empty($updatedata)) {
      $get_user = Driver::where('id',$id_conducteur)->first();
      $row = $get_user->toArray();
      $row['id']=(string)$row['id'];

      $image_user = $row['photo_path'];
      $photo = '';
      $row['photo'] = $photo;
      $row['photo_nic'] = $photo;
      $row['photo_car_service_book'] = $photo;
      $row['photo_licence'] = $photo;
      $row['photo_road_worthy'] = $photo;
      if($image_user != ''){
          if(file_exists(public_path('assets/images/driver'.'/'.$image_user )))
          {
              $image_user = asset('assets/images/driver').'/'. $image_user;
          }
          else
          {
              $image_user = asset('assets/images/placeholder_image.jpg');

          }
          $row['photo_path'] = $image_user;
      }
      $image = $row['photo_nic_path'];

      if($image != ''){
          if(file_exists(public_path('assets/images/driver'.'/'.$image )))
          {
              $image = asset('assets/images/driver').'/'. $image;
          }
          else
          {
              $image = asset('assets/images/placeholder_image.jpg');

          }
          $row['photo_nic_path'] = $image;
      }
      $car = $row['photo_car_service_book_path'];
      if($car != ''){
        if(file_exists(public_path('assets/images/driver'.'/'.$car )))
        {
            $car = asset('assets/images/driver').'/'. $car;
        }
        else
        {
            $car = asset('assets/images/placeholder_image.jpg');

        }
        $row['photo_car_service_book_path'] = $car;
    }
    $licence = $row['photo_licence_path'];
    if($licence != ''){
      if(file_exists(public_path('assets/images/driver'.'/'.$licence )))
      {
          $licence = asset('assets/images/driver').'/'. $licence;
      }
      else
      {
          $licence = asset('assets/images/placeholder_image.jpg');

      }
      $row['photo_licence_path'] = $licence;
  }
  if($row['photo_road_worthy_path'] != ''){
      if(file_exists(public_path('assets/images/driver'.'/'.$row['photo_road_worthy_path'] )))
      {
          $road = asset('assets/images/driver').'/'. $row['photo_road_worthy_path'];
      }
      else
      {
          $road = asset('assets/images/placeholder_image.jpg');

      }
      $row['photo_road_worthy_path'] = $road;
  }
      $response['success']= 'success';
      $response['error']= null;
      $response['message'] = 'Status Changed Successfully';
      $response['data'] = $row;
        }
    else {

      $response['success']= 'Failed';
      $response['error']= 'Failed to change status';
    }
  }
  else {

    $response['success']= 'Failed';
    $response['error']= 'Some Fields are missing';
  }
    return response()->json($response);
  }





}
