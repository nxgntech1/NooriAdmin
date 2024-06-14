<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\UserApp;
use App\Models\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use DB;
class OnrideRequeteBookController extends Controller
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
    $creer = date('Y-m-d H:i:s');



       if(!empty($id_requete))
       {
        $sql =DB::table('tj_requete_book')
        ->select('*')
        ->where('id','=',$id_requete)
        ->get();
        $id_user_app = "";
        $depart_name = "";
        $destination_name = "";
        $latitude_depart = "";
        $longitude_depart = "";
        $latitude_arrivee = "";
        $longitude_arrivee = "";
        $distance = "";
        $duree = "";
        $montant = "";
        $trajet = "";
        $statut = "";
        $statut_paiement = "";
        $id_conducteur = "";
        // $creer = "";
        $modifier = "";
        $date_book = "";
        $nb_day = "";
        $heure_depart = "";
        $cu = "";
        $id_payment_method="";


        foreach($sql as $row){
            $id_user_app = $row->id_user_app;
            $depart_name = $row->depart_name;
            $destination_name = $row->destination_name;
            $latitude_depart = $row->latitude_depart;
            $longitude_depart = $row->longitude_depart;
            $latitude_arrivee = $row->latitude_arrivee;
            $longitude_arrivee = $row->longitude_arrivee;
            $distance = $row->distance;
            $duree = $row->duree;
            $montant = $row->montant;
            $trajet = $row->trajet;
            $statut = $row->statut;
            $statut_paiement = $row->statut_paiement;
            $id_conducteur = $row->id_conducteur;
            $id_payment_method = $row->id_payment_method;
            // $creer = $row['creer'];
            $modifier = $row->modifier;
            $date_book = $row->date_book;
            $nb_day = $row->nb_day;
            $heure_depart = $row->heure_depart;
            $cu = $row->cu;
        }
        $reqchkonride = DB::table('tj_requete')
        ->select('id')
        ->where('trajet','=',$trajet)
        ->where('depart_name','=',$depart_name)
        ->where('destination_name','=',$destination_name)
        ->where('id_conducteur','=',$id_conducteur)
        ->where('id_user_app','=',$id_user_app)
        ->where('latitude_depart','=',$latitude_depart)
        ->where('longitude_depart','=',$longitude_depart)
        ->where('latitude_arrivee','=',$latitude_arrivee)
        ->where('longitude_arrivee','=',$longitude_arrivee)
        ->where('distance','=',$distance)
        ->where('creer','=',$creer)
        ->where('montant','=',$cu)
        ->where('duree','=',$duree)
        ->where('id_payment_method','=',$id_payment_method)
        ->get();
        foreach ($reqchkonride as $row)
        {
            $row['id']=(string)$row['id'];
        }
        if($sql->count() > 0){

            $tmsg='';
            $terrormsg='';

            $title=str_replace("'","\'","Beginning of your ride");
            $msg=str_replace("'","\'","Your ride started, do not forget to put the seat belt");

            $tab[] = array();
            $tab = explode("\\",$msg);
            $msg_ = "";
            for($i=0; $i<count($tab); $i++){
                $msg_ = $msg_."".$tab[$i];
            }


            $message=array("body"=>$msg_,"title"=>$title,"sound"=>"mySound","tag"=>"rideonride");

            $query = DB::table('tj_user_app')
            ->select('fcm_id')
            ->where('fcm_id','!=','')
            ->where('id','=',$id_user)
            ->get();

            $tokens = array();
            if (!empty($query)) {
                foreach ($query as $user){
                    if (!empty($user->fcm_id)) {
                        $tokens[] = $user->fcm_id;
                    }
                }
            }
            $temp = array();
            if (count($tokens) > 0) {
                GcmController::send_notification($tokens, $message, $temp);
            }

            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'successfully';
            $response['data'] = $row;

        }
        else{
            if (!empty($id_conducteur)) {
                $query = DB::insert("insert into tj_requete(id_payment_method,trajet,depart_name,destination_name,id_conducteur,id_user_app,latitude_depart,longitude_depart,latitude_arrivee,longitude_arrivee,statut,creer,distance,montant,duree)
            values('" . $id_payment_method . "','" . $trajet . "','" . $depart_name . "','$destination_name','$id_conducteur','" . $id_user_app . "','" . $latitude_depart . "','" . $longitude_depart . "','" . $latitude_arrivee . "','" . $longitude_arrivee . "','on ride','" . $creer . "','" . $distance . "','" . $cu . "','" . $duree . "')");

                $sql_requete = Requests::where('creer', $creer)->first();
                $data = $sql_requete->toArray();

                if ($query > 0) {
                    $response['success'] = 'success';
                    $response['error'] = null;
                    $response['message'] = 'data added successfully';
                    $response['data'] = $data;
                    $tmsg = '';
                    $terrormsg = '';

                    $title = str_replace("'", "\'", "Beginning of your ride");
                    $msg = str_replace("'", "\'", "Your ride started, do not forget to put the seat belt");

                    $tab[] = array();
                    $tab = explode("\\", $msg);
                    $msg_ = "";
                    for ($i = 0; $i < count($tab); $i++) {
                        $msg_ = $msg_ . "" . $tab[$i];
                    }


                    $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "rideonride");

                    $query = DB::table('tj_user_app')
                        ->select('fcm_id')
                        ->where('fcm_id', '!=', '')
                        ->where('id', '=', $id_user)
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
                    $response['success'] = 'Failed';
                    $response['error'] = 'Failed to add data';
                }
            }else{
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to add data';
            }
        }
    }else {
        $response['success'] = 'Failed';
        $response['error'] = 'Not Found';
    }

   return response()->json($response);
  }
}
