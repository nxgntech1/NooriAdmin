<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\GcmController;
use DB;
class CompleteRequeteController extends Controller
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

    public function startTripRequest(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user');
        $driver_lat = $request->get('driver_lat');
        $driver_lon=$request->get('driver_lon');
        $distance_to_pickup=$request->get('distance_to_pickup');
        $date_heure=date('Y-m-d 00:00:00');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut = ?,distance_to_pickup = ? where id = ?',['Start Trip', $distance_to_pickup, $id_requete]);
        
            if (!empty($updatedata)) {
                $query = DB::insert("insert into ride_status_change_log(ride_id,status,driver_id, latitude,longitude,created_on)
                values('".$id_requete."','Start Trip','".$id_user."','".$driver_lat."','".$driver_lon."','".$date_heure."')");
            }
            
            if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
                $response['data'] = '1';
            }
            else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update data';
            }
        }

        return response()->json($response);
    }


    public function arrivedRequest(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user');
        $driver_lat = $request->get('driver_lat');
        $driver_lon=$request->get('driver_lon');
        $date_heure=date('Y-m-d 00:00:00');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut = ? where id = ?',['Arrived', $id_requete]);
        
            if (!empty($updatedata)) {
                $query = DB::insert("insert into ride_status_change_log(ride_id,status,driver_id, latitude,longitude,created_on)
                values('".$id_requete."','Arrived','".$id_user."','".$driver_lat."','".$driver_lon."','".$date_heure."')");
            }
            
            if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
                $response['data'] = '1';
            }
            else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update data';
            }
        }

        return response()->json($response);
    }

    public function onRideRequest(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user');
        $driver_lat = $request->get('driver_lat');
        $driver_lon=$request->get('driver_lon');
        $otp=$request->get('otp');
        $date_heure=date('Y-m-d 00:00:00');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut = ? where id = ?',['On Ride', $id_requete]);
        
            if (!empty($updatedata)) {
                $query = DB::insert("insert into ride_status_change_log(ride_id,status,driver_id, latitude,longitude,created_on)
                values('".$id_requete."','On Ride','".$id_user."','".$driver_lat."','".$driver_lon."','".$date_heure."')");
            }
            
            if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
                $response['data'] = '1';
            }
            else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update data';
            }
        }

        return response()->json($response);
    }


    public function RideCompleteRequest(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user');
        $driver_lat = $request->get('driver_lat');
        $driver_lon=$request->get('driver_lon');
        $date_heure=date('Y-m-d 00:00:00');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut = ? where id = ?',['Completed', $id_requete]);
        
            if (!empty($updatedata)) {
                $query = DB::insert("insert into ride_status_change_log(ride_id,status,driver_id, latitude,longitude,created_on)
                values('".$id_requete."','Completed','".$id_user."','".$driver_lat."','".$driver_lon."','".$date_heure."')");
            }

            $sqlride = DB::table('tj_requete')
                    ->select('tj_requete.id_payment_method,tj_requete.montant')
                    ->where('tj_requete.id', '=', $id_requete)
                    ->get();

            foreach ($sqlride as $row) {
                $payment_MethodId = $row->id_payment_method;
                $amount = $row->montant;
            }
            
            if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';

                if ($payment_MethodId = '5'){
                    $response['collect_Cash'] = '1';
                    $response['finalAmount'] = $amount;


                }else{
                    $response['collect_Cash'] = '0';
                    $response['finalAmount'] = '0';
                }   

                $response['data'] = '1';
            }
            else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update data';
            }
        }

        return response()->json($response);
    }


    public function RideCashPaidRequest(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user_app');
        $date_heure=date('Y-m-d 00:00:00');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut_paiement = ? where id = ?',['yes', $id_requete]);
        
            $sqlride = DB::table('tj_requete')
                    ->select('tj_requete.montant')
                    ->where('tj_requete.id', '=', $id_requete)
                    ->get();

            foreach ($sqlride as $row) {
                $amount = $row->montant;
            }

            if (!empty($updatedata)) {
                $query = DB::insert("insert into tj_transaction(ride_id,amount,id_user_app, payment_method,payment_status,creer,modifier)
                values('".$id_requete."','". $amount ."','".$id_user."','5','yes','".$date_heure."','".$date_heure."')");
            }

            if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
               $response['data'] = '1';
            }
            else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update data';
            }
        }

        return response()->json($response);
    }




  public function completeRequest(Request $request)
  {
    $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

    $id_requete = $request->get('id_ride');
    $id_user = $request->get('id_user');
    $driver_name = $request->get('driver_name');
    $from_id=$request->get('from_id');
    $date_heure=date('Y-m-d 00:00:00');
    if(!empty($id_requete) && !empty($driver_name) && !empty($id_user) && !empty($from_id)){

    $updatedata =  DB::update('update tj_requete set statut = ? where id = ?',['completed',$id_requete]);

    if (!empty($updatedata)) {
        $referral=Referral::where('user_id','=',$id_user)->where('code_used','=','false')->first();
        if(!empty($referral)){
          if($referral->referral_by_id!=null){
              $referBy=$referral->referral_by_id;
              $setting=Settings::first();
              $refAmount=$setting->referral_amount;
              $amount=0;
              $sql = DB::table('tj_user_app')
              ->select('amount')
              ->where('id','=',$referBy)
              ->first();
              //echo $sql->amount;
              if($sql->amount!=null){
                $amount=$sql->amount;
              }

              $newAmount=$amount+$refAmount;
              $updateDataUser=DB::table('tj_user_app')
              ->where('id', $referBy)
              ->update(['amount' => $newAmount,'modifier'=>$date_heure]);
              
              $paymethod='Referral';

              $query = DB::insert("insert into tj_transaction(amount,deduction_type,payment_method,id_user_app, creer,modifier)
              values('".$refAmount."',1,'".$paymethod."','".$referBy."','".$date_heure."','".$date_heure."')");

              $updateDataRef = DB::update('update referral set code_used = ? where user_id = ?',['true',$id_user]);


          }
        }
        $sql = Requests::where('id',$id_requete)->first();
        $row = $sql->toArray();
        $row['id'] = (string) $row['id'];

        $row['creer'] = date("d", strtotime($row['creer'])) . " " . $months[date("F", strtotime($row['creer']))] . ", " . date("Y", strtotime($row['creer']));
        $row['date_retour'] = date("d", strtotime($row['date_retour'])) . " " . $months[date("F", strtotime($row['date_retour']))] . ", " . date("Y", strtotime($row['date_retour']));


        if($row['ride_type'] == 'dispatcher'){
            DB::update('update tj_requete set statut_paiement= ? where id = ?',['yes',$id_requete]);
        }
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

            $driver=DB::table('tj_conducteur')->where('id', $row['id_conducteur'])->first();
            $row['prenomConducteur'] = $driver->prenom;
            $row['nomConducteur'] = $driver->nom;
            $row['photo_path'] = $driver->photo_path;
                if ($row['photo_path'] != '') {
                    if (file_exists(public_path('assets/images/driver' . '/' . $row['photo_path']))) {
                        $image_user = asset('assets/images/driver') . '/' . $row['photo_path'];
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');

                    }
                }else{
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

        $title=str_replace("'","\'","End of your ride");
        $msg=str_replace("'","\'",$driver_name." is completed your ride.");

        $tab[] = array();
        $tab = explode("\\",$msg);
        $msg_ = "";
        for($i=0; $i<count($tab); $i++){
            $msg_ = $msg_."".$tab[$i];
        }
        $message=array("body"=>$msg_,"title"=>$title,"sound"=>"mySound","tag"=>"ridecompleted");

        $query = DB::table('tj_user_app')
        ->select('fcm_id','nom','prenom','email')
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
            GcmController::send_notification($tokens, $message, $row);
            $date_heure = date('Y-m-d H:i:s');
            $to_id=$request->get('id_user');
            $insertdata = DB::insert("insert into tj_notification(titre,message,statut,creer,modifier,to_id,from_id,type)
            values('".$title."','".$msg."','yes','".$date_heure."','".$date_heure."','".$to_id."','".$from_id."','ridecompleted')");
            $sql_notification = Notification::orderby('modifier','desc')->first();
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
    $response['error'] = 'some fields are missing';
}
    return response()->json($response);
  }





}
