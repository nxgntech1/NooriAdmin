<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use DB;
class NotifyController extends Controller
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
  

  public function UpdateNotify(Request $request)
  {
   
   
        $id_requete = $request->get('custom_str1');
        $id_user = $request->get('custom_str2');
        $tip_amount = ($request->get('custom_str3'))?$request->get('custom_str3'):0;
        $from_id =$request->get('custom_str4');
        $customer_name =$request->get('name_first').' '.$request->get('name_last');

        $data = DB::table('tj_requete')
        ->select('id')
        ->where('statut_paiement','=','')
        ->where('id','=',$id_requete)
        ->get();

        if($data && $request->get('payment_status')=="COMPLETE"){
            
        $updatedata = DB::update('update tj_requete set statut_paiement = ?,tip_amount = ?  where id = ?',['yes',$id_requete,$tip_amount]);
       
        if ($updatedata>0) {
            $response['msg']['etat'] = 1;
            
            $tmsg='';
            $terrormsg='';
            
            $title=str_replace("'","\'","Payment of the race");
            if($tip_amount > 0){
                $msg=str_replace("'","\'","Your customer has just paid for his ride. Also you have received R".$tip_amount);
            }else{
                $msg=str_replace("'","\'",$customer_name." paid R".$amount." successfully.");
            }
           
            $tab[] = array();
            $tab = explode("\\",$msg);
            $msg_ = "";
            for($i=0; $i<count($tab); $i++){
                $msg_ = $msg_."".$tab[$i];
            }
         
            $message=array("body"=>$msg_,"title"=>$title,"sound"=>"mySound","tag"=>"paymentcompleted");
       
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
                $date_heure = date('Y-m-d H:i:s');
                $from_id=$request->get('custom_str4');
                $to_id=$request->get('id_driver');

                $query = DB::insert("insert into tj_vehicule(titre,message,statut,creer,to_id,from_id,type)
                values('".$title."','".$msg."','yes','".$date_heure."','".$to_id."','".$from_id."','paymentcompleted')");

            }
           
            
             // Get user info
            $query = DB::table('tj_requete')
            ->crossJoin('tj_user_app')
            ->select('tj_user_app.fcm_id', 'tj_user_app.id', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_user_app.email')
            ->where('tj_requete.id_user_app','=',DB::raw('tj_user_app.id'))
            ->where('tj_requete.id','=',$id_requete)
            ->get();
            
            // Get Ride Info
            $query_ride =  DB::table('tj_requete')
            ->select('distance', 'duree', 'montant', 'creer', 'trajet')
            ->where('id','=',$id_requete)
            ->get();
            foreach($query_ride as $ride){
                $distance = $ride->distance;
                $duree = $ride->duree;
                $cout = $ride->montant;
                $date_heure = $ride->creer;
                $img_name = $ride->trajet;
            }

            $tokens = array();
            $nom = "";
            $prenom = "";
            $email = "";
            if (!empty($query)) {
                foreach($query as $user){
              
                    if (!empty($user->fcm_id)) {
                        $tokens[] = $user->fcm_id;
                        $nom = $user->nom;
                        $prenom = $user->prenom;
                        $email = $user->email;
                    }
                }
            }
        
            if($email != ""){
                $to = $email;
                $subject = "Payment receipt - Spark";
                $message = '
                    <body style="margin:100px; background: #ffc600; ">
                        <div width="100%" style="background: #ffc600; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                            <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px; background: #fff;">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                    <tbody>
                                        <tr>
                                            <td style="vertical-align: top; padding-bottom:30px;" align="center">
                                                <img src="'.NotifyController::url().'/on_demand_taxi/on_demand_taxi_webservice/images/logo_taxijaune.jpg" alt="Spark" style="border:none" width="15%">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="padding: 40px; background: #fff;">
                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <img src="http://projets.hevenbf.com/on_demand_taxi/on_demand_taxi_webservice/images/recu_trajet_course/'.$img_name.'" alt="Spark" style="border:none" width="100%">
                                                <h3>Payment receipt </h3>
                                                <p>Hello Mr./Mrs '.$prenom.' '.$nom.'</p>
                                                <b><u>Details of your payment receipt:</u></b><br>
                                                <p><b>Distance:</b> '.$distance.' M</p>
                                                <p><b>Duration:</b> '.$duree.'</p>
                                                <p><b>Cost:</b> R '.$cout.'</p>
                                                <p><b>Date:</b> '.$date_heure.'</p>
                                                <br/>
                                                <p>Good continuation and see you soon !</p>
                                                <p>Regards Spark</p>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </body>
                ';
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\b";
                $headers .= 'From: Spark' . "\r\n";
                mail($to,$subject,$message,$headers);
            }
        }else {
            $response['msg']['etat'] = 2;
        }
  

     }
        else{
            $response['msg']="Not Found";
        }
      return response()->json($response);
  }
  public static function url(){
    $actual_link = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $site_url = preg_replace('/^www\./', '', parse_url($actual_link, PHP_URL_HOST));
   if(($_SERVER['HTTPS'] && $_SERVER['HTTPS'] === 'on')){
      return "https://".$site_url; 
   }else{
      return "http://".$site_url; 
   }
   
}
}