<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\UserApp;
use App\Models\Driver;
use App\Models\Vehicle;

use App\Models\Requests;
use App\Models\FavoriteRide;
use App\Models\VehicleLocation;
use App\Models\Message;
use App\Models\Note;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use DB;
class DeleteUserController extends Controller
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

  public function deleteuser(Request $request)
  {
    $id = $request->get('user_id');
    $user_cat = $request->get('user_cat');

    if(!empty($id)){

        if ($user_cat == 'customer') {

        $requests=Requests::where('id_user_app',$id);
        $requests->delete();

          $ride=FavoriteRide::where('id_user_app',$id);
          $ride->delete();

          $VehicleLocation=VehicleLocation::where('id_user_app',$id);
          $VehicleLocation->delete();

          $Message=Message::where('id_user_app',$id);
          $Message->delete();

          $Note=Note::where('id_user_app',$id);
          $Note->delete();

            $user=UserApp::find($id);
            if($user){
              $destination = public_path('assets/images/users/' . $user->photo_path);
              if (File::exists($destination)) {
                File::delete($destination);
              }

              if ($user->delete()) {
                $response['success']= 'success';
                $response['error']= null;
                $response['message']= 'User Deleted Successfully';
              } else {
                $response['success']= 'Failed';
                $response['error']='Failed To Delete User';
              }

          } else {
            $response['success']= 'Failed';
            $response['error']='Not Found';
        }

      } elseif ($user_cat == 'driver') {

        $requests=Requests::where('id_conducteur',$id);
        $requests->delete();

        $vehicle = Vehicle::where('id_conducteur',$id);
        $vehicle->delete();

        $Message=Message::where('id_conducteur',$id);
        $Message->delete();

        $Note=Note::where('id_conducteur',$id);
        $Note->delete();

        $user=Driver::find($id);

        if($user){
            $destination = public_path('assets/images/driver/' . $user->photo_path);
            if (File::exists($destination)) {
              File::delete($destination);
            }

            if ($user->delete()) {

              $response['success']= 'success';
              $response['error']= null;
              $response['message']= 'User Deleted Successfully';

          } else {

              $response['success']= 'Failed';
              $response['error']='Failed To Delete User';

          }

        } else {

          $response['success']= 'Failed';
          $response['error']='Not Found';

      }
    } else{
      
      $response['success']= 'Failed';
      $response['error']='Some fields are missing';
    }

  } else{
      $response['success']= 'Failed';
      $response['error']='Id Required';
  }
    return response()->json($response);

  }

}
