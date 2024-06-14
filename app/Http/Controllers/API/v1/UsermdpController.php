<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class UsermdpController extends Controller
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


  public function UpdateUsermdp(Request $request)
  {

        $user_cat = $request->get('user_cat');
        $anc_mdp = $request->get('anc_mdp');
        $anc_mdp = str_replace("'","\'",$anc_mdp);
        $anc_mdp = md5($anc_mdp);
        $new_mdp = $request->get('new_mdp');
        $new_mdp = str_replace("'","\'",$new_mdp);
        $new_mdp = md5($new_mdp);
        $date_heure = date('Y-m-d H:i:s');

        if($user_cat == "user_app"){
            $id_user = $request->get('id_user');
            $get_user = UserApp::where('id',$id_user)->first();
            if(!$get_user)
            {
                $response['success']= 'Failed';
                $response['error']= 'User Not Found';
            }else{
            $oldpass = $get_user->toArray();

            if ($oldpass['mdp'] == $anc_mdp) {
                $updatedata =  DB::update('update tj_user_app set mdp = ?,modifier = ? where id = ?',[$new_mdp,$date_heure,$id_user]);

                $sql = DB::table('tj_user_app')
                ->select('*')
                ->where('id','=',$id_user)
                ->get();
                foreach ($sql as $row)
                if (!empty($row)) {
                    $row->id=(string)$row->id;
                    $response['success']= 'success';
                    $response['error']= null;
                    $response['data']= $row;
                } else {
                    $response['success']= 'Failed';
                    $response['error']= 'Failed to Update Password';
                }
            }else{
                $response['success']= 'Failed';
                $response['error']= 'Incorrect Password';
            }
        }
        }else{
            $id_driver = $request->get('id_driver');
            $get_user = Driver::where('id',$id_driver)->first();
            if(!$get_user)
            {
                $response['success']= 'Failed';
                $response['error']= 'Driver Not Found';
            }else{
                $oldpass = $get_user->toArray();

            if ($oldpass['mdp'] == $anc_mdp) {
                $updatedata =  DB::update('update tj_conducteur set mdp = ?,modifier = ? where id = ?',[$new_mdp,$date_heure,$id_driver]);

                $sql = DB::table('tj_conducteur')
                ->select('*')
                ->where('id','=',$id_driver)
                ->get();
                foreach ($sql as $row)
                if (!empty($row)) {
                    $row->id=(string)$row->id;
                    $response['success']= 'success';
                    $response['error']= null;
                    $response['data']= $row;
                } else {
                    $response['success']= 'Failed';
                    $response['error']= 'Failed to Update Password';
                }
            }else{
                $response['success']= 'Failed';
                $response['error']= 'Password Incorrect';
            }
        }
    }

    return response()->json($response);
  }

}
