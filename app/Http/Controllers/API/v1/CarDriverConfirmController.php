<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Requests;
use Illuminate\Http\Request;
use DB;
class CarDriverConfirmController extends Controller
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

  public function confirm(Request $request)
  {
    $id_requete = $request->get('id_ride');
    $id_driver = $request->get('id_user');
    $use_name = $request->get('user_name');
    $from_id=$request->get('from_id');

    $car_driver_confirmed = $request->get('car_driver_confirmed');

    $lat_conducteur = $request->get('lat_conducteur');
    $lng_conducteur = $request->get('lng_conducteur');
    $lat_client = $request->get('lat_client');
    $lng_client = $request->get('lng_client');

    $lat_conducteur=str_replace("."," ",$lat_conducteur);
    $lng_conducteur=str_replace("."," ",$lng_conducteur);
    $lat_client=str_replace("."," ",$lat_client);
    $lng_client=str_replace("."," ",$lng_client);
    $updatedata =  DB::update('update tj_requete set car_driver_confirmed = ? where id = ?',[ $car_driver_confirmed,$id_requete]);

    if (!empty($updatedata)) {
        $sql = Requests::where('id',$id_requete)->first();
        $row = $sql->toArray();
        $row['id']=(string)$row['id'];
        $row['tax'] = json_decode($row['tax'],true);
        $row['stops'] = json_decode($row['stops'], true);
        $row['user_info'] = json_decode($row['user_info'], true);


        $tmsg='';
        $terrormsg='';

        if($car_driver_confirmed){
            $title=str_replace("'","\'","Confirmation of your information");
            $msg=str_replace("'","\'",$use_name." confirmed your information.  Now you can start ride.");
        }else{
            $title=str_replace("'","\'","Confirmation of your information");
            $msg=str_replace("'","\'",$use_name." decline your information.");
        }

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
        $data = $row;
        if (count($tokens) > 0) {
            GcmController::send_notification($tokens, $message, $data);
                $date_heure = date('Y-m-d H:i:s');
                $to_id=$request->get('id_user');
                $insertdata = DB::insert("insert into tj_notification(titre,message,statut,creer,modifier,to_id,from_id,type)
                values('".$title."','".$msg."','yes','".$date_heure."','".$date_heure."','".$to_id."','".$from_id."','userconfirmed')");

        }
        $response['success'] = 'success';
        $response['error'] = null;
        $response['message'] = 'status update successfully';
        $response['data'] = $row;
    } else {
        $response['success'] = 'failed';
        $response['error'] = 'failed to update';
    }
    return response()->json($response);
  }





}
