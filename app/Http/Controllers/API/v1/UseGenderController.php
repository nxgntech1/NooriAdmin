<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\Commission;
use Illuminate\Http\Request;
use DB;
class UseGenderController extends Controller
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

  public function getData(Request $request)
  {
    $id_user =  $request->get('id_user');

    $sql = DB::table('tj_user_app')
    ->select('*')
    ->where('id','=',$id_user)
    ->get();
    
    foreach($sql as $row){
      $row->id=(string)$row->id;
    
    $image = $row->photo_nic_path;
    $image_path = asset('assets/images/users').'/'.$image;
    $row->photo_nic_path_link = $image_path;
        if(!empty($row)){
          $response['success']= 'success';
          $response['error']= null;
          $response['message']= 'successfully';
          $response['data'] = $row;

        } else {
          $response['success']= 'Failed';
          $response['error']= 'Failed To Fetch Data';
        }
        return response()->json($response);

    }

  }

}
