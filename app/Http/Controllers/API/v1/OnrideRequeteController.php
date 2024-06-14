<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Vehicle;
use App\Models\Requests;
use App\Models\Notification;
use App\Models\Driver;
use Illuminate\Http\Request;
use DB;
class OnrideRequeteController extends Controller
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


  public function register(Request $request)
  {
    $id_requete = $request->get('id_ride');
    $id_user = $request->get('id_user');
    $use_name = $request->get('use_name');
    $from_id = $request->get('from_id');
    $date_heure = date('Y-m-d H:i:s');

    $updatedata = DB::update('update tj_requete set statut = ? where id = ?',['on ride',$id_requete]);

       if(!empty($updatedata))
       {

        $sql = Requests::where('id',$id_requete)->first();
        $row = $sql->toArray();

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
            $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');
            $row['creer'] = date("d", strtotime($row['creer'])) . " " . $months[date("F", strtotime($row['creer']))] . ", " . date("Y", strtotime($row['creer']));
            $row['date_retour'] = date("d", strtotime($row['date_retour'])) . " " . $months[date("F", strtotime($row['date_retour']))] . ", " . date("Y", strtotime($row['date_retour']));

            $driver=DB::table('tj_conducteur')->where('id', $row['id_conducteur'])->first();
            $row['prenomConducteur'] = $driver->prenom;
            $row['nomConducteur'] = $driver->nom;
            $row['photo_path'] = $driver->photo_path;
            if ($row['photo_path'] != '') {
                if (file_exists(public_path('assets/images/driver' . '/' . $row['photo_path']))) {
                    $image_user = asset('assets/images/driver') . '/' . $row['photo_path'];
                }else{
                    $image_user = asset('assets/images/placeholder_image.jpg');
                }
            }
            else {
                    $image_user = asset('assets/images/placeholder_image.jpg');
                }
            
            $row['photo_path'] = $image_user;

            $sql_nb_avis = DB::table('tj_note')
                ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
                ->where('id_conducteur', '=', $row['id_conducteur'])
                ->get();

            if (!empty($sql_nb_avis)) {
                foreach ($sql_nb_avis as $row_nb_avis)
                    $somme = $row_nb_avis->somme;
                $nb_avis = $row_nb_avis->nb_avis;
                if ($nb_avis != "0")
                    $moyenne = $somme / $nb_avis;
                else
                    $moyenne = 0;
            } else {
                $somme = "0";
                $nb_avis = "0";
                $moyenne = 0;
            }
        $row['moyenne']=$moyenne;
        $tmsg='';
        $terrormsg='';

        $title=str_replace("'","\'","Beginning of your ride");
        $msg=str_replace("'","\'",$use_name." is started your ride.");

        $tab[] = array();
        $tab = explode("\\",$msg);
        $msg_ = "";
        for($i=0; $i<count($tab); $i++){
            $msg_ = $msg_."".$tab[$i];
        }
        $message=array("body"=>$msg_,"title"=>$title,"sound"=>"mySound","tag"=>"rideonride");

        $query = DB::table('tj_user_app')
        ->select('fcm_id')
        ->where('fcm_id','<>','')
        ->where('id','=',$id_user)
        ->get();

        $tokens = array();
        if ($query->count() > 0) {
            foreach ($query as $user){
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
            $from_id=$request->get('from_id');
            $to_id=$request->get('id_user');
            $insertdata = DB::insert("insert into tj_notification(titre,message,statut,creer,modifier,to_id,from_id,type)
            values('".$title."','".$msg."','yes','$date_heure','$date_heure','".$to_id."','".$from_id."','rideonride')");

            $sql_notification = Notification::where('to_id',$to_id)->first();
            $data = $sql_notification->toArray();
                $row['titre'] = $data['titre'];
                $row['message'] = $data['message'];
                $row['statut_notification'] = $data['statut'];
                $row['to_id'] = $data['to_id'];
                $row['from_id'] = $data['from_id'];
                $row['type'] = $data['type'];


        }
        $row['id'] = (string) $row['id'];
        $row['stops'] = json_decode($row['stops'], true);
        $row['tax'] = json_decode($row['tax'], true);
        $row['user_info'] = json_decode($row['user_info'], true);

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
