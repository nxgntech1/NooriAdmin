<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\RequestBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;

class SetRequeteBookController extends Controller
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
    $user_id = $request->get('user_id');
    $lat1 = $request->get('lat1');
    $lng1 = $request->get('lng1');
    $lat2 = $request->get('lat2');
    $lng2 = $request->get('lng2');
    $cout = $request->get('cout');
    $duree = $request->get('duree');
    $distance =$request->get('distance');

    $id_conducteur = $request->get('id_conducteur');
    $id_payment = $request->get('id_payment');
    $depart_name = $request->get('depart_name');
    $destination_name = $request->get('destination_name');
    $image = $request->file('image');
    $nb_day = $request->get('nb_day');
    $heure_depart = $request->get('heure_depart');
    $date_book = $request->get('date_book');
    $cu = $request->get('price');
    $place =$request->get('place');
    $place = str_replace("'","\'",$place);
    $number_poeple = $request->get('number_poeple');
    $number_poeple = str_replace("'","\'",$number_poeple);
    $statut_round = $request->get('statut_round');
    $heure_retour = $request->get('heure_retour');
    $date_heure = date('Y-m-d H:i:s');


     if(!empty($image)){
       $file = $request->file('image');
       $extenstion = $file->getClientOriginalExtension();
       $time = time().'.'.$extenstion;
       $filename = 'requete_images_'.$time;
       $file->move(public_path('images/recu_trajet_course/'), $filename);
     }
     else{
        $filename = '';
     }
        $tmsg='';
        $terrormsg='';

        $title=str_replace("'","\'","New ride");
        $msg=str_replace("'","\'","You have just received a request of booking from a client");

        $tab[] = array();
        $tab = explode("\\",$msg);
        $msg_ = "";
        for($i=0; $i<count($tab); $i++){
            $msg_ = $msg_."".$tab[$i];
        }


        $message=array("body"=>$msg_,"title"=>$title,"sound"=>"mySound","tag"=>"ridenewrider");

        $query = DB::table('tj_conducteur')
        ->select('fcm_id')
        ->where('fcm_id','<>','')
        ->where('id','=',DB::raw($id_conducteur))
        ->get();

        $tokens = array();
        if (!empty($query)) {
            foreach ($query as $user)
            {
                if (!empty($user->fcm_id)) {
                    $tokens[] = $user->fcm_id;
                }
            }
        }

        $temp = array();
         if (count($tokens) > 0) {
             GcmController::send_notification($tokens, $message, $temp);
         }

        $date_heure = date('Y-m-d H:i:s');
        $insertdata = DB::insert("insert into tj_requete_book(statut_round,heure_retour,number_poeple,place,id_payment_method,cu,trajet,depart_name,destination_name,id_conducteur,id_user_app,latitude_depart,longitude_depart,latitude_arrivee,longitude_arrivee,statut,creer,distance,montant,duree,date_book,nb_day,heure_depart,statut_paiement,modifier)
        values('".$statut_round."','".$heure_retour."','".$number_poeple."','".$place."','".$id_payment."','".$cu."','".$filename."','".$depart_name."','".$destination_name."','".$id_conducteur."','".$user_id."','".$lat1."','".$lng1."','".$lat2."','".$lng2."','new','".$date_heure."','".$distance."','".$cout."','".$duree."','".$date_book."','".$nb_day."','".$heure_depart."','','".$date_heure."')");
        $id=DB::getPdo()->lastInsertId();


        if ($id > 0) {
        $get_user = RequestBooks::where('id',$id)->first();
        $row = $get_user->toArray();
        $row['id']=(string)$row['id'];
        if($row['trajet'] != '') {
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

        $response['success']= 'success';
        $response['error']= null;
        $response['message'] = 'Successfully created';
        $response['data'] = $row;
        }else{
          $response['success']= 'Failed';
          $response['error']= null;
        }
        return response()->json($response);
    }




}
