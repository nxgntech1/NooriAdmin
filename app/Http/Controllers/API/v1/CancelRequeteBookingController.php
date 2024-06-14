<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use DB;
class CancelRequeteBookingController extends Controller
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

  public function cancel(Request $request)
  {
    $id_requete = $request->get('id_ride');
    $id_driver = $request->get('id_driver');
    $user_name = $request->get('user_name');

    $updatedata =  DB::update('update tj_requete_book set statut = ? where id = ?',['canceled',$id_requete]);

    if (!empty($updatedata)) {
        $response['msg']['etat'] = 1;
        
        $tmsg='';
        $terrormsg='';
        
        $title=str_replace("'","\'","Cancellation of a ride");
        $msg=str_replace("'","\'",$user_name." canceled his ride");
        
        $tab[] = array();
        $tab = explode("\\",$msg);
        $msg_ = "";
        for($i=0; $i<count($tab); $i++){
            $msg_ = $msg_."".$tab[$i];
        }

      
        $message=array("body"=>$msg_,"title"=>$title,"sound"=>"mySound","tag"=>"ridecanceledrider");

        $query = DB::table('tj_conducteur')
        ->select('fcm_id')
        ->where('fcm_id','!=','')
        ->where('id','=',$id_driver)
        ->get();
      
        $tokens = array();
        if (!empty($query)) {
            foreach ($query as $user) {
                if (!empty($user->fcm_id)) {
                    $tokens[] = $user->fcm_id;
                }
            }
        }
        $temp = array();
        if (count($tokens) > 0) {
            GcmController::send_notification($tokens, $message, $temp);
        }
    } else {
        $response['msg']['etat'] = 2;
    }
    return response()->json($response);
  }
       
}