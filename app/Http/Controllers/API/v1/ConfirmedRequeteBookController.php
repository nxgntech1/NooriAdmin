<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\GcmController;
use DB;
class ConfirmedRequeteBookController extends Controller
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
  public function confirmRequest(Request $request)
  {
    
    $id_requete = $request->get('id_ride');
    $id_user = $request->get('id_user');
    $driver_name = $request->get('driver_name');

    $updatedata =  DB::update('update tj_requete_book set statut = ? where id = ? AND statut = ?',['confirmed',$id_requete,'new']);

    if (!empty($updatedata)) {
        $response['msg']['etat'] = 1;
            
        $tmsg='';
        $terrormsg='';
        
        $title=str_replace("'","\'","Confirmation of your ride");
        $msg=str_replace("'","\'",$driver_name." is Confirmed your ride.");
        
        $tab[] = array();
        $tab = explode("\\",$msg);
        $msg_ = "";
        for($i=0; $i<count($tab); $i++){
            $msg_ = $msg_."".$tab[$i];
        }
        $message=array("body"=>$msg_,"title"=>$title,"sound"=>"mySound","tag"=>"rideconfirmed");

        $query = DB::table('tj_conducteur')
        ->select('fcm_id')
        ->where('fcm_id','!=','')
        ->where('id','=',$id_user)
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
      
        }
    else {
        $response['msg']['etat'] = 2;
    }
    return response()->json($response);
  }
       
    
   
  

}