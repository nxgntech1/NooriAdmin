<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\API\v1\NotificationListController;
use DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//require 'C:\Personal\NxGn\Projects\NoorieTravels\NooriAdminPortal\vendor\autoload.php';
require 'C:\Websites\NooriTravels\cabme-admin-panel\vendor\autoload.php';


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
        $odometer_start_reading=$request->get('odometer_start_reading');

        $date_heure=date('Y-m-d H:i:s');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut = ?,distance_to_pickup = ?,odometer_start_reading = ? where id = ?',['Start Trip', $distance_to_pickup, $odometer_start_reading, $id_requete]);
        
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
        $date_heure=date('Y-m-d H:i:s');

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
        $date_heure=date('Y-m-d H:i:s');

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
        $odometer_end_reading=$request->get('odometer_end_reading');
        $date_heure=date('Y-m-d H:i:s');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut = ?,odometer_end_reading = ? where id = ?',['Completed', $odometer_end_reading, $id_requete]);
        
            if (!empty($updatedata)) {
                $query = DB::insert("insert into ride_status_change_log(ride_id,status,driver_id, latitude,longitude,created_on)
                values('".$id_requete."','Completed','".$id_user."','".$driver_lat."','".$driver_lon."','".$date_heure."')");
            }

            $sqlride = DB::table('tj_requete')
                    ->select('tj_requete.id_payment_method', 'tj_requete.montant')
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
        $date_heure=date('Y-m-d H:i:s');

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


    public function TestAppNotification(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user');
        $driver_lat = $request->get('driver_lat');
        $driver_lon=$request->get('driver_lon');
        $date_heure=date('Y-m-d H:i:s');

        //if(!empty($id_requete) && !empty($id_user)){

            // Sending Notifications 
            //if (count($tokens) > 0) {

            $tmsg = '';
            $terrormsg = '';

            $title = str_replace("'", "\'", "New ride");
            $msg = str_replace("'", "\'", "You have just received a request from a client");
         
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            // if ($id > 0) {
            //     $get_user = Requests::where('id', '140')->first();
            //     $rowData = $get_user->toArray();
            // // }

            $tokens = 'cC9i9w3_S9-MdNPhCAUoFf:APA91bHE3_RsMUm5Y_UXN9dvcC7ALUgk7DfsTj5mEGAyLIDaHsZZtTKm_LibaAyTKRvMWhzfq_Q9f28jB8vPLpJOa62saag7Gd4wE4dm_GZrVca3XFPyNFS7AAJI8Lvgi4KRQNf7GgiD';
           // $data = $rowData;

          
            $message1 = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'ridenewrider'
            ];

            //$data = $request->input('data');

            // $notifications= new NotificationsController();
            // $notifcationres = $notifications->sendNotification($tokens, $message1,null);
            // $SMS_Notifiaction = $notifications->sendSMS('919885084010','OTP is for 3456 TeamPlay app. Do not share the OTP with anyone for security reasons');
            

            // $app_name = 'Noori';
            // $contact_us_email = 'noori@nxgnemail.com';
            // $headers = "MIME-Version: 1.0" . "\r\n";
            // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            // $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
            // $headers .= 'Return-Path: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";

            //$mailresponse = mail('kanna.ganasala@nxgntech.com', 'Test Mail from Noori', 'Test message body from Noori', $headers);
             
            $notifications= new NotificationsController();
            $notifications->sendEmail('kannababu.g@gmail.com','test message','Test message body from Noori');
            // $mail = new PHPMailer(true);

            // try {
            //     // Server settings
            //     $mail->isSMTP();
            //     $mail->Host       = 'smtp.mailgun.org';
            //     $mail->SMTPAuth   = true;
            //     $mail->Username   = 'postmaster@nxgnemail.com';
            //     $mail->Password   = 'nxgnsmtp';
            //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //     $mail->Port       = 587;
    
            //     // Recipients
            //     $mail->setFrom('noori@nxgnemail.com', 'Mailer');
            //     $mail->addAddress('kanna.ganasala@nxgntech.com', 'Joe User');
    
            //     // Content
            //     $mail->isHTML(true);
            //     $mail->Subject = 'Here is the subject';
            //     $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            //     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
            //     $mail->send();
            //     echo 'Message has been sent';
            // } catch (Exception $e) {
            //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            // }

            //if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
               // $response['Notification_data'] = $notifcationres;
              //  $response['mailResponse'] = $mailresponse;
            // }
            // else {
            //     $response['success'] = 'Failed';
            //     $response['error'] = 'Failed to update data';
            // }
        //}

        return response()->json($response);
    }








}
