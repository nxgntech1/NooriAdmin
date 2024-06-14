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




class SmsController extends Controller
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


    public function __construct(TextLocalService $textLocalService,FcmService $fcmService)
    {
        $this->textLocalService = $textLocalService;
        $this->fcmService = $fcmService;
    }

    public function sendTextLocalSMS(Request $request)
    {
        $request->validate([
            'numbers' => 'required|array',
            'message' => 'required|string',
        ]);

        $response = $this->textLocalService->sendSMS($request->numbers, $request->message);

        return response()->json($response);
    }


    public function sendFcmNotification(Request $request)
    {
        
        // Sending Notifications 
            //if (count($tokens) > 0) {

            $tmsg = '';
            $terrormsg = '';

            $title = str_replace("'", "\'", "New ride");
            $msg = str_replace("'", "\'", "You have just received a request from a client");
            $msg1 = "You have just received a request from a client - Kanna";

            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }
            $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridenewrider");



            // if ($id > 0) {
                $get_user = Requests::where('id', '140')->first();
                $rowData = $get_user->toArray();
            // }

            //$tokens = array();
            $tokens = 'fYBKgdfNRfuC8FeGfuogXb:APA91bE-gG4NqKM2jt5RWAPZQwpzWnae8WijG0RTU4xncEnipZ8t3gGv6pxrHk7MVbPVxN6L6xlJ3ahELIZhP5pynxmzKOy3wFAH4nCTgWbNH1dLLI-ZblGTCP15ZzkCwXP04XOpRCoW';
            $data = $rowData;

            //$tokens = $request->input('tokens');
            $message1 = [
                'title' => 'Test message from API',
                'body' => 'You got a notification from backend'
            ];
            //$data = $request->input('data');

            $response['notifiaction_data'] = $this->fcmService->sendNotification($tokens, $message1,null);
            
            return response()->json($response);

        //}
        // End Sending Notifications 
    }


    
    
}
