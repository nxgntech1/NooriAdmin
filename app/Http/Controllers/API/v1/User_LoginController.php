<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Driver;
use App\Models\UserApp;
use Illuminate\Http\Request;
use DB;
class User_LoginController extends Controller
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
  

  public function login(Request $request)
  {
    $date_heure = date('Y-m-d H:i:s');
    $id_user = "";
    $mdp = md5($request->get('mdp'));
    $telephone = $request->get('phone');
    $mdp = str_replace("'","\'",$mdp);
    $telephone = str_replace("'","\'",$telephone);
    $user_cat = $request->get('user_cat');

    if(!empty($request->get('mdp') && $request->get('phone')))
    {
    if($user_cat == 'customer')
    {
        $checkuser = UserApp::where('phone',$telephone)->first();

    }
    else if($user_cat == 'driver'){
      $checkuser = Driver::where('phone',$telephone)->first();
    }
    else{
        $response['success']= 'Failed';
        $response['error']='Not Found';
    }
    }
    else{
        $response['success']= 'Failed';
        $response['error']='some fields are missing';
    }
   return response()->json($response);
  }
}