<?php


namespace App\Http\Controllers\api\v1;

use App\Services\FcmService;
use App\Services\TextLocalService;
use App\Http\Controllers\Controller;
use App\Services\TwilioService;

use App\Models\Requests;
use DB;
use PDO;
use Illuminate\Http\Request;
use App\Services;

use Google\Client as Google_Client;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//require 'C:\Personal\NxGn\Projects\NoorieTravels\NooriAdminPortal\vendor\autoload.php';
require 'C:\Websites\NooriTravels\cabme-admin-panel\vendor\autoload.php';


class NotificationsController extends Controller
{
    protected $twilio;
    protected $fcmService;
    protected $textLocalService;

    // ------------ Twilio SMS 
    // public function __construct(TwilioService $twilio)
    // {
    //     $this->twilio = $twilio;
    // }

    // public function sendSms(Request $request)
    // {
    //     $to = $request->input('to');
    //     $message = $request->input('message');

    //     $this->twilio->sendSms($to, $message);

    //     return response()->json(['status' => 'SMS sent successfully']);
    // }
    // --------------------END Twilio SMS -----------------------------


    public function __construct()
    {
        $this->textLocalService = new TextLocalService();
        $this->fcmService = new FcmService();
        $this->twilio = new TwilioService();
    }

    public function sendSMS($number,$message)
    {
        // $request->validate([
        //     'numbers' => 'required|array',
        //     'message' => 'required|string',
        // ]);

        $response = $this->textLocalService->sendSMS($number, $message);

        return response()->json($response);
    }


    public function sendNotification($tokens,$message,$data)
    {
            $response['notifiaction_data'] = $this->fcmService->sendNotification($tokens, $message,$data);
            return response()->json($response);

    }


    public function sendEmail($to, $emailsubject, $emailmessage)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.mailgun.org';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'postmaster@nxgnemail.com';
            $mail->Password   = 'nxgnsmtp';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet = 'UTF-8';

            // Recipients
            $mail->setFrom('noori@nxgnemail.com', 'Noori');
            //$mail->addAddress('kanna.ganasala@nxgntech.com', 'Joe User');
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $emailsubject;
            $mail->Body    = $emailmessage;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $response['emailResonse'] =  'Message has been sent';
        } catch (Exception $e) {
            $response['emailResonse']= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return response()->json($response);
    }

    public function sendWhatsappMessage($to,$message)
    {
        $response['data'] = $this->twilio->sendWhatsappMessage($to,$message);
        return response()->json($response);
    }
    
    
}
