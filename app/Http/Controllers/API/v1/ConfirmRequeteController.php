<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\GcmController;
use DB;
class ConfirmRequeteController extends Controller
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
    $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

    $id_requete = $request->get('id_ride');
    $id_user = $request->get('id_user');
    $driver_name = $request->get('driver_name');
    $from_id=$request->get('from_id');

    $lat_conducteur = $request->get('lat_conducteur');
    $lng_conducteur = $request->get('lng_conducteur');
    $lat_client = $request->get('lat_client');
    $lng_client = $request->get('lng_client');

    $lat_conducteur=str_replace("."," ",$lat_conducteur);
    $lng_conducteur=str_replace("."," ",$lng_conducteur);
    $lat_client=str_replace("."," ",$lat_client);
    $lng_client=str_replace("."," ",$lng_client);
    if(!empty($id_requete) && !empty($id_user) && !empty($driver_name) && !empty($from_id)){
    $updatedata =  DB::update('update tj_requete set statut = ? where id = ? AND statut = ?',['confirmed',$id_requete,'new']);

    if (!empty($updatedata)) {
        $otp = random_int(100000, 999999);


        $user =  Requests::where('id',$id_requete)->first();
        if($user){
            $user->otp = $otp;
            $user->otp_created = now();
        }
        $user->save();
        $sql = Requests::where('id',$id_requete)->first();
        $row = $sql->toArray();
        $row['id']=(string)$row['id'];
        $row['creer'] = date("d", strtotime($row['creer'])) . " " . $months[date("F", strtotime($row['creer']))] . ", " . date("Y", strtotime($row['creer']));
        $row['date_retour'] = date("d", strtotime($row['date_retour'])) . " " . $months[date("F", strtotime($row['date_retour']))] . ", " . date("Y", strtotime($row['date_retour']));

            if($row['trajet'] != ''){
            if(file_exists(public_path('images/recu_trajet_course'.'/'.$row['trajet'] )))
            {
                $image_user = asset('images/recu_trajet_course').'/'. $row['trajet'];
            }
            else
            {
                $image_user =asset('assets/images/placeholder_image.jpg');

            }
            $row['trajet'] = $image_user;
        }
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
        $sound = $lat_conducteur."_".$lng_conducteur."_".$lat_client."_".$lng_client;
        $message=array("body"=>$msg_,"title"=>$title,"sound"=>$sound,"tag"=>"rideconfirmed");

        $query = DB::table('tj_user_app')
        ->select('fcm_id')
        ->where('fcm_id','<>','')
        ->where('id','=',$id_user)
        ->get();

        $tokens = array();
        if ($query->count() > 0) {
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
            values('".$title."','".$msg."','yes','".$date_heure."','".$date_heure."','".$to_id."','".$from_id."','rideconfirmed')");
            $sql_notification = Notification::orderby('id','desc')->first();
            $data = $sql_notification->toArray();
                $row['titre'] = $data['titre'];
                $row['message'] = $data['message'];
                $row['statut_notification'] = $data['statut'];
                $row['to_id'] = $data['to_id'];
                $row['from_id'] = $data['from_id'];
                $row['type'] = $data['type'];
        }
        $row['tax'] = json_decode($row['tax'], true);
        $row['stops'] = json_decode($row['stops'], true);
        $row['user_info'] = json_decode($row['user_info'], true);

        $response['success'] = 'success';
        $response['error'] = null;
        $response['message'] = 'status successfully updated';
        $response['data'] = $row;

        }
    else {
        $response['success'] = 'Failed';
        $response['error'] = 'Failed to update data';

    }
}
else {
    $response['success'] = 'Failed';
    $response['error'] = 'some field are missing';

}
    return response()->json($response);
  }





}
