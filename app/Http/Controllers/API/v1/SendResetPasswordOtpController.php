<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\UserApp;
use App\Models\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use DB;
class SendResetPasswordOtpController extends Controller
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


  public function resetPasswordOtp(Request $request)
  {
    $email = $request->get('email');
    $user_cat = $request->get('user_cat');
    $otp = mt_rand(1000,9999);
    $date_heure = date('Y-m-d H:i:s');


    if(!empty($email)){
        if($user_cat == 'user_app'){
            $sql = DB::table('tj_user_app')
            ->select('id','nom','prenom')
            ->where('email','=',$email)
            ->get();
            if($sql->count()>0){
            foreach($sql as $row)
            {
                  $row->id=(string)$row->id;
            }
                if($row->id >0)
                {
                $user_id = $row->id;
                $user_name = $row->nom." ".$row->prenom;

                $updatedata = DB::update('update tj_user_app set reset_password_otp = ? , reset_password_otp_modifier = ? where email = ?',[$otp,$date_heure,$email]);
             }

    }else{
        $response['success'] = 'Failed';
        $response['error'] = 'Email is not Exist';
    }
}
        elseif($user_cat == 'driver'){

            $sql = DB::table('tj_conducteur')
            ->select('id','nom','prenom')
            ->where('email','=',$email)
            ->get();
            if($sql->count()>0){
            foreach($sql as $row){
              $row->id=(string)$row->id;
            }
            if($row->id >0)
            {

                $user_id = $row->id;
                $user_name = $row->nom." ".$row->prenom;

                $updatedata = DB::update('update tj_conducteur set reset_password_otp = ? , reset_password_otp_modifier = ? where email = ?',[$otp,$date_heure,$email]);


            }  else{
                $response['success'] = 'Failed';
                $response['error'] = 'Email is not Exist';
            }


        }  else{
            $response['success'] = 'Failed';
            $response['error'] = 'Email is not Exist';

        }
    }else{
        $response['success'] = 'Failed';
        $response['error'] = 'Not Found';

    }
        if(!empty($user_id)){
                $emailsubject = '';
                $emailmessage = '';
                $emailtemplate = DB::table('email_template')->select('*')->where('type', 'reset_password')->first();
                if (!empty($emailtemplate)) {
                    $emailsubject = $emailtemplate->subject;
                    $emailmessage = $emailtemplate->message;
                    $send_to_admin = $emailtemplate->send_to_admin;
                }
                $contact_us_email = DB::table('tj_settings')->select('contact_us_email')->value('contact_us_email');
                $contact_us_email = $contact_us_email ? $contact_us_email : 'none@none.com';


                $app_name = env('APP_NAME', 'Cabme');
                if ($send_to_admin == "true") {
                    $to = $email . "," . $contact_us_email;
                } else {
                    $to = $email;
                }

                $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
                $emailmessage = str_replace("{UserName}", $row->prenom . " " . $row->nom, $emailmessage);
                $emailmessage = str_replace("{OTP}", $otp , $emailmessage);

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: ' . $app_name . '<' . $contact_us_email . '>' . "\r\n";
                
                mail($to, $emailsubject, $emailmessage, $headers);

            // Always set content-type when sending HTML email

        $response['success'] = 'success';
        $response['error']= null;
        $response['message']='successfully';
        $response['data']=$row;
    }
}else{
    $response['success'] = 'Failed';
    $response['error'] = 'Email required';

}
   return response()->json($response);
  }
}
