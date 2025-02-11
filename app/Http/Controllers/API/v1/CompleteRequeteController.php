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
use App\Models\UserApp;
use DB;
use App\Models\Currency;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;
use Exception as exption;

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

                $this->SendStarttripAppNotification($id_requete);

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

    public function SendStarttripAppNotification($ride_id)
    {

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.destination_name', 
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time',
            'tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur',
            'tj_conducteur.prenom as driverfirstname','tj_conducteur.nom as driverlastnae','tj_user_app.fcm_id')
            ->where('tj_requete.id', '=', $ride_id)
            ->get();

            foreach ($sql as $row) {
   
                $drivername = $row->driverfirstname .' '. $row->driverlastnae;
                $pickup_Location = $row->depart_name;
                $drop_Location = $row->destination_name;
                $pickupdate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date)); 
                $pickuptime = date("h:i A", strtotime($row->ride_required_on_time));
                $tokens = $row->fcm_id;
            }

            $tmsg = '';
            $terrormsg = '';

            $title = "Trip Started";
            
            $msg = str_replace("{DriverName}", $drivername, "Your trip has started driver {DriverName} will reach you soon. Please provide the OTP to start the ride.");
            $msg = str_replace("'", "\'", $msg);
        
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            $data = [
                'ride_id' => $ride_id
            ];

            $message1 = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'driverstarted'
            ];

            $notifications= new NotificationsController();
            $response['Response'] = $notifications->sendNotification($tokens, $message1,$data);

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

                $this->SendDriverArrivedAppNotification($id_requete);

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

    public function SendDriverArrivedAppNotification($ride_id)
    {

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        ->join('tj_vehicule','tj_requete.vehicle_Id','=','tj_vehicule.id')
        ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
        ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
        ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.destination_name', 
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time',
            'tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur',
            'tj_conducteur.prenom as driverfirstname','tj_conducteur.nom as driverlastnae',
            'car_model.name as carmodel','brands.name as brandname','tj_vehicule.numberplate','tj_conducteur.phone as driverphone','tj_user_app.fcm_id')
            ->where('tj_requete.id', '=', $ride_id)
            ->get();

            foreach ($sql as $row) {
   
                $drivername = $row->driverfirstname .' '. $row->driverlastnae;
                $carmodelandbrand = $row->brandname .' - '. $row->carmodel;
                $numberplate = $row->numberplate;
                $driverphone = $row->driverphone;
                $pickup_Location = $row->depart_name;
                $drop_Location = $row->destination_name;
                $pickupdate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date)); 
                $pickuptime = date("h:i A", strtotime($row->ride_required_on_time));
                $tokens = $row->fcm_id;
            }

            $tmsg = '';
            $terrormsg = '';



            $title = "Driver Arrived";
            
            $msg = str_replace("{DriverName}", $drivername, "Your driver {DriverName} has arrived at the pickup location with {carmodel} Reg. no {carnumber}. He can be reachable on {DriverPhone}.");
            $msg = str_replace("{carmodel}", $carmodelandbrand, $msg);
            $msg = str_replace("{carnumber}", $numberplate, $msg);
            $msg = str_replace("{DriverPhone}", $driverphone, $msg);
            $msg = str_replace("'", "\'", $msg);
        
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            $data = [
                'ride_id' => $ride_id
            ];

            $message1 = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'driverarrived'
            ];

            $notifications= new NotificationsController();
            $response['Response'] = $notifications->sendNotification($tokens, $message1,$data);

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

            $updatedata =  DB::update('update tj_requete set statut = ? where id = ? and otp= ?',['On Ride', $id_requete,$otp]);
        
            if (!empty($updatedata)) {
                $query = DB::insert("insert into ride_status_change_log(ride_id,status,driver_id, latitude,longitude,created_on)
                values('".$id_requete."','On Ride','".$id_user."','".$driver_lat."','".$driver_lon."','".$date_heure."')");
            }
            
            if (!empty($updatedata)) {

                $this->SendRideStartedAppNotification($id_requete);

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
    public function SendRideStartedAppNotification($ride_id)
    {

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        ->join('tj_vehicule','tj_requete.vehicle_Id','=','tj_vehicule.id')
        ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
        ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
        ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.destination_name', 
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time',
            'tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur',
            'tj_conducteur.prenom as driverfirstname','tj_conducteur.nom as driverlastnae',
            'car_model.name as carmodel','brands.name as brandname','tj_vehicule.numberplate','tj_conducteur.phone as driverphone','tj_user_app.fcm_id')
            ->where('tj_requete.id', '=', $ride_id)
            ->get();

            foreach ($sql as $row) {
   
                $tokens = $row->fcm_id;
            }

            $currentDateTimeInIndia = Carbon::now('Asia/Kolkata');
            $title = "Ride Started";
            
            $msg = str_replace("{Ridetime}", $currentDateTimeInIndia->format('d-m-Y h:m A'), "Your ride has started at {Ridetime}.");
            
            $msg = str_replace("'", "\'", $msg);
        
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            $data = [
                'ride_id' => $ride_id
            ];

            $message1 = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'ridestarted'
            ];

            $notifications= new NotificationsController();
            $response['Response'] = $notifications->sendNotification($tokens, $message1,$data);

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

        $currency = Currency::where('statut', 'yes')->first();

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set statut = ?,odometer_end_reading = ? where id = ?',['Completed', $odometer_end_reading, $id_requete]);
        
            if (!empty($updatedata)) {
                $query = DB::insert("insert into ride_status_change_log(ride_id,status,driver_id, latitude,longitude,created_on)
                values('".$id_requete."','Completed','".$id_user."','".$driver_lat."','".$driver_lon."','".$date_heure."')");
            }

            $sqlride = DB::table('tj_requete')
                    ->select('tj_requete.id_payment_method', DB::raw('cast(tj_requete.montant as decimal(18,2)) as montant'))
                    ->where('tj_requete.id', '=', $id_requete)
                    ->get();

            foreach ($sqlride as $row) {
                $payment_MethodId = $row->id_payment_method;

                $addonamt = DB::table('tj_transaction')
                    ->select('tj_transaction.ride_id', 'tj_transaction.is_addon','tj_transaction.payment_method','tj_transaction.payment_status',DB::raw('sum(cast(IFNULL(tj_transaction.amount, 0) as decimal(18,2))) as addonamount'))
                    ->where('tj_transaction.ride_id', '=', $id_requete)
                    ->where('tj_transaction.payment_method','=',5)
                    ->where('tj_transaction.payment_status','=','yes')
                    ->where('tj_transaction.is_addon','=','yes')
                    ->groupBy('tj_transaction.ride_id', 'tj_transaction.is_addon','tj_transaction.payment_method','tj_transaction.payment_status')
                    ->get();
                    $addonTotal =0;
                if(!empty($addonamt))
                {
                    foreach($addonamt as $addon)
                    {
                        $addonTotal = $addon->addonamount;
                    }
                }

                $amount = $row->montant + $addonTotal;
            }
            
            if (!empty($updatedata)) {

                $this->SendEmail("customer",$id_requete);
                $this->SendEmail("admin",$id_requete);
                $this->SendRideCompletedAppNotification($id_requete);

                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';

                if ($addonTotal > 0 || $payment_MethodId=="5"){
                    $response['collect_Cash'] = '1';
                    $response['finalAmount'] = $currency->symbole . "" . number_format($amount,$currency->decimal_digit); 


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
    public function SendRideCompletedAppNotification($ride_id)
    {

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('tj_conducteur', 'tj_requete.id_conducteur', '=', 'tj_conducteur.id')
        ->join('tj_vehicule','tj_requete.vehicle_Id','=','tj_vehicule.id')
        ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
        ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
        ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.destination_name', 
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time',
            'tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur',
            'tj_conducteur.prenom as driverfirstname','tj_conducteur.nom as driverlastnae',
            'car_model.name as carmodel','brands.name as brandname','tj_vehicule.numberplate','tj_conducteur.phone as driverphone','tj_user_app.fcm_id')
            ->where('tj_requete.id', '=', $ride_id)
            ->get();

            foreach ($sql as $row) {
   
                $tokens = $row->fcm_id;
            }

            $title = "Ride Completed";
            
            $msg = "Your ride is completed. Thank you for riding with us!";
            
            $msg = str_replace("'", "\'", $msg);
        
            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }

            $data = [
                'ride_id' => $ride_id
            ];

            $message1 = [
                'title' => $title,
                'body' => $msg_,
                'sound'=> 'mySound',
                'tag' => 'ridecompleted'
            ];

            $notifications= new NotificationsController();
            $response['Response'] = $notifications->sendNotification($tokens, $message1,$data);

            return response()->json($response);
    }


    public function RideCashPaidRequest(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user_app');
        $date_heure=date('Y-m-d H:i:s');
        
        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::table('tj_requete')
            ->where('id', $id_requete)
            ->update(['statut_paiement' => 'yes']);
        
            $sqlride = DB::table('tj_requete')
                    ->select('tj_requete.montant')
                    ->where('tj_requete.id', '=', $id_requete)
                    ->get();

            foreach ($sqlride as $row) {
                $amount = $row->montant;
            }
            
             if (DB::table('tj_transaction')->where('ride_id', $id_requete)->where('amount', $amount)->where('id_user_app', $id_user)->doesntExist()) {
                
                $query = DB::table('tj_transaction')->insert([
                    'ride_id' => $id_requete,
                    'amount' => $amount,
                    'id_user_app' => $id_user,
                    'payment_method' => '5',
                    'payment_status' => 'yes',
                    'creer' => $date_heure,
                    'modifier' => $date_heure,
                ]);
            
            }

            //if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
               $response['data'] = '1';
            // }
            // else {
            //     $response['success'] = 'Failed';
            //     $response['error'] = 'Failed to update data'.$updatedata.'ride_id:'.$id_requete.'Userid:'.$id_user;
            // }
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

    public function SendEmail($usertype, $id)
    {
        $currency = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();
        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');
            $sql = DB::table('tj_requete')
            ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
            ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
            ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
            ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
            ->Join('bookingtypes', 'tj_requete.booking_type_id','=','bookingtypes.id')
            ->join('tj_vehicule','tj_requete.vehicle_Id','=','tj_vehicule.id')
            ->join('tj_conducteur','tj_requete.id_conducteur','=','tj_conducteur.id')
            ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
                'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
                'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
                'tj_requete.statut', 'tj_requete.id_conducteur',
                'tj_requete.creer', 'tj_requete.tax_amount','tj_requete.discount',
                'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete.otp','tj_user_app.email as customeremail','tj_user_app.phone as customerphone',
                'tj_requete.distance', 'tj_user_app.phone','tj_requete.date_retour', 'tj_requete.heure_retour',
                'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
                'tj_requete.car_Price','tj_requete.sub_total',
                'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time','tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
                'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
                'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image','tj_requete.id_payment_method as paymentmethodid', 'bookingtypes.bookingtype as bookingtype',
                'tj_vehicule.numberplate','tj_conducteur.prenom as driverfirstname','tj_conducteur.nom as driverlastname','tj_conducteur.phone as drivernumber','tj_requete.odometer_start_reading','tj_requete.odometer_end_reading')
                ->where('tj_requete.id', '=', $id)
                ->get();
            foreach ($sql as $row) {
                $customer_name = $row->prenom.' '.$row->nom;
                $customerphone = $row->customerphone;
                $customeremail = $row->customeremail;
                $carmodelandbrand = $row->brandname .' / '. $row->carmodel;
                $pickup_Location = $row->depart_name;
                $drop_Location = $row->destination_name;
                $booking_date = date("d", strtotime($row->creer)) . " " . $months[date("F", strtotime($row->creer))] . ", " . date("Y", strtotime($row->creer));
                $booking_time = date("h:i A", strtotime($row->creer)); 
                $payment_method = $row->payment;
                $bookingtype = $row->bookingtype;
                $pickupdate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date)); 
                $pickuptime = date("h:i A", strtotime($row->ride_required_on_time));
                $brandname = $row->brandname;
                $numberplate = $row->numberplate;
                $drivername = $row->driverfirstname.' '.$row->driverlastname;
                $driverphone = $row->drivernumber;
                $starting_reading = $row->odometer_start_reading;
                $ending_reading = $row->odometer_end_reading;

                $car_Price = $row->car_Price;
                if(!empty($car_Price))
                    $car_Price = $currency->symbole . "" . number_format($row->car_Price,$currency->decimal_digit);

                $coupon_discount = $row->discount;
                if(!empty($coupon_discount))
                    $coupon_discount = $currency->symbole . "" . number_format($coupon_discount,$currency->decimal_digit);
                else
                    $coupon_discount = $currency->symbole . "" . number_format($coupon_discount,$currency->decimal_digit); 

                $tax_amount = $row->tax_amount;
                if(!empty($tax_amount))
                    $tax_amount = $currency->symbole . "" . number_format($tax_amount,$currency->decimal_digit);

                $sub_total = $row->sub_total;
                if(!empty($sub_total))
                    $sub_total = $currency->symbole . "" . number_format($sub_total,$currency->decimal_digit);

                $final_amount = $row->montant;
                if(!empty($final_amount))
                    $final_amount = $currency->symbole . "" . number_format($final_amount,$currency->decimal_digit);

             }
             if($usertype=="customer")
             {
                
                $emailsubject = '';
                $emailmessage = '';

                $emailsubject = "Your ride is completed";
                $emailmessage = file_get_contents(resource_path('views/emailtemplates/to_customer_ride_complete.html'));
                

                
                $emailmessage = str_replace("{CustomerName}", $customer_name, $emailmessage);
                $emailmessage = str_replace("{PickupLocation}", $pickup_Location, $emailmessage);
                $emailmessage = str_replace("{DropoffLocation}", $drop_Location, $emailmessage);
                $emailmessage = str_replace("{carmodel}", $carmodelandbrand, $emailmessage);
                $emailmessage = str_replace("{DriverName}", $drivername, $emailmessage);
                $emailmessage = str_replace("{BookingType}", $bookingtype, $emailmessage);
                $emailmessage = str_replace("{BrandName}", $brandname, $emailmessage);
                $emailmessage = str_replace("{PickupDate}", $pickupdate, $emailmessage);
                $emailmessage = str_replace("{PickupTime}", $pickuptime, $emailmessage);
                $emailmessage = str_replace("{TripCharge}", $car_Price, $emailmessage);
                $emailmessage = str_replace("{coupondiscount}", $coupon_discount, $emailmessage);
                $emailmessage = str_replace("{TripTax}", $tax_amount, $emailmessage);
                $emailmessage = str_replace("{PaymentMethod}", $payment_method, $emailmessage);
                $emailmessage = str_replace("{TotalAmount}", $final_amount, $emailmessage);
                $emailmessage = str_replace("{CarNumber}", $numberplate, $emailmessage);
                

                $admintoemail=env('ADMIN_EMAILID','info@nooritravels.com');
                $notifications= new NotificationsController();
                $response['CustomerEmailResponse'] = $notifications->sendEmail($customeremail, $emailsubject,$emailmessage);
             }
             else if($usertype=="admin")
             {
                $AdminUrl= env('ADMIN_BASEURL','https://nadmin.nxgnapp.com/').'ride/show/'.$id;
                $emailsubject = '';
                $emailmessage = '';

                $emailsubject = "Ride completed";
                //$emailmessage = file_get_contents(resource_path('views/emailtemplates/to_admin_ride_complete.html'));
                $emailmessage = file_get_contents(resource_path('views/emailtemplates/complete.html'));
                $emailmessage = str_replace("{CustomerName}", $customer_name, $emailmessage);
                $emailmessage = str_replace("{CustomerNumber}", $customerphone, $emailmessage);
                $emailmessage = str_replace("{PickupDate}", $pickupdate, $emailmessage);
                $emailmessage = str_replace("{PickupTime}", $pickuptime, $emailmessage);
                $emailmessage = str_replace("{BookingType}", $bookingtype, $emailmessage);
                $emailmessage = str_replace("{BookingID}", $id, $emailmessage);
                $emailmessage = str_replace("{StartingReading}", $starting_reading, $emailmessage);
                $emailmessage = str_replace("{EndingReading}", $ending_reading, $emailmessage);
                $emailmessage = str_replace("{TotalAmount}", $final_amount, $emailmessage);
                
                
                // $emailmessage = str_replace("{PickupLocation}", $pickup_Location, $emailmessage);
                // $emailmessage = str_replace("{DropoffLocation}", $drop_Location, $emailmessage);
                // $emailmessage = str_replace("{AdminUrl}", $AdminUrl, $emailmessage);
                
                // $emailmessage = str_replace("{CarNumber}", $numberplate, $emailmessage);
                
                // $emailmessage = str_replace("{PaymentMethod}", $payment_method, $emailmessage);
                // $emailmessage = str_replace("{DriverName}", $drivername, $emailmessage);
                // $emailmessage = str_replace("{DriverPhone}", $driverphone, $emailmessage);
                // $emailmessage = str_replace("{BrandName}", $brandname, $emailmessage);
                // $emailmessage = str_replace("{carmodel}", $carmodelandbrand, $emailmessage);
                

                $admintoemail= env('ADMIN_EMAILID','info@nooritravels.com');
                $notifications= new NotificationsController();
                $response['CustomerEmailResponse'] = $notifications->sendEmail($admintoemail, $emailsubject,$emailmessage);
             }
            // return "success";
    }

    public function arrivalODORequest(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user');
        $odometer_arrival_reading=$request->get('odometer_arrival_reading');

        if(!empty($id_requete) && !empty($id_user)){

            $updatedata =  DB::update('update tj_requete set odometer_arrival_reading = ? where id = ?',[ $odometer_arrival_reading, $id_requete]);
        
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






}
