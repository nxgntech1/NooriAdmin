<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use DB;
class TerminalCourseController extends Controller
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
  

  public function terminerCourse(Request $request)
  {
   
        $id_requete = $request->get('id_ride');
        $id_conducteur = $request->get('id_conducteur');
        $image =$request->file('image');
       
        $cout =$request->get('cout');
        $distance = $request->get('distance');
        $duree = $request->get('duree');
        $date_heure = date('Y-m-d H:i:s');

        $image = base64_encode(file_get_contents($request->file('image')->getPathName()));
        $file = $request->file('image');
        $extenstion = $file->getClientOriginalExtension();
        $time = time().'.'.$extenstion;
        $filename = $time;
        $file->move(public_path('images/app_user/'), $filename);

        $updatedata = DB::update('update tj_requete set statut_course = ?,id_conducteur_accepter = ? ,modifier = ? where id = ?',['fence',$id_conducteur,$date_heure,$id_requete]);

        $sql =  DB::table('tj_requete')
        ->crossJoin('tj_user_app')
        ->select('tj_requete.id_user_app', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_user_app.email')
        ->where('tj_requete.id_user_app','=',DB::raw('tj_user_app.id'))
        ->where('tj_requete.id','=',$id_requete)
        ->get();
        foreach($sql as $row){
            $id_user_app = $row->id_user_app;
            $nom = $row->nom;
            $prenom = $row->prenom;
            $email = $row->email;
        }
        $selectdata =  DB::table('tj_recu')
        ->select('id')
        ->where('id_course','=',$id_requete)
        ->where('id_conducteur','=',$id_conducteur)
        ->where('id_user_app','=',$id_user_app)
        ->get();

         if (!empty($selectdata)) {
            $response['msg']['etat'] = 1;
            if($image){
            $insertdata = DB::insert("insert into tj_recu(image,image_name,id_course,id_conducteur,id_user_app,creer,modifier,montant,duree,distance)
            values('".$image."','".$filename."','".$id_requete."','$id_conducteur','".$id_user_app."','".$date_heure."','".$date_heure."','".$cout."','".$duree."','".$distance."')");
            }
        }
             // Get user info
            /** Start Notification **/
        $tmsg='';
        $terrormsg='';
        
        $title=str_replace("'","\'","Closing of the race");
        $msg=str_replace("'","\'","End of the race! We wish you an excellent result!");
        
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
        ->where('id','=',$id_conducteur)
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
            if($email != ""){
                $to = $email;
                $subject = "Payment receipt - Spark";
                $message = '
                <body style="margin:100px; background: #f8f8f8; ">
                    <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                        <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px; background: #fff;">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top; padding-bottom:30px;" align="center">
                                            <img src="http://projets.hevenbf.com/yellow%20taxi/webservices/images/logo_taxijaune.jpg" alt="logo Spark" style="border:none" width="15%">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="padding: 40px; background: #fff;">
                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <img src="'.TerminalCourseController::url().'/on_demand_taxi/on_demand_taxi_webservice/images/recu_trajet_course/'.$filename.'" alt="logo Taxi Jaune" style="border:none" width="100%">
                                            <h3>Payment receipt </h3>
                                            <p>Hello Mr./Mrs '.$prenom.' '.$nom.'</p>
                                            <b><u>Details of your payment receipt:</u></b><br>
                                            <p><b>Distance:</b> '.$distance.' M</p>
                                            <p><b>Duration:</b> '.$duree.'</p>
                                            <p><b>Cost:</b> '.$cout.' $</p>
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
            $headers .= 'From: Taxi Jaune' . "\r\n";
            mail($to,$subject,$message,$headers);
            }
        if ($updatedata > 0) {
            $response['msg']['etat'] = 1;
        } else {
            $response['msg']['etat'] = 2;
        }
   return response()->json($response);
  }
  public static function url(){
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   $site_url = preg_replace('/^www\./', '', parse_url($actual_link, PHP_URL_HOST));
   if((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')){
      return "https://".$site_url; 
   }else{
      return "http://".$site_url; 
   }
   
}

}