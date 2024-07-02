<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->client = new Client($sid, $token);
    }

    public function sendSms($to, $message)
    {
        $from = config('services.twilio.from');
        $this->client->messages->create($to, [
            'from' => $from,
            'messagingServiceSid' => 'MGe6e472ffff7bb6fd31eebf1839afd875',
            'body' => $message
        ]);
    }
    public function sendWhatsappMessage($to,$message)
    {
        $from = config('services.twilio.whatsappnumber');
        $messagingServiceSid=env('TWILIO_OTP_WHATSAPP_MSG_ID');
        try {
            $response = $this->client->messages->create(
                "whatsapp:".$to, // to
                [
                    "from" => "whatsapp:".$from,
                    "body" => $message,
                    "messagingServiceSid" => env('TWILIO_OTP_WHATSAPP_MSG_ID')
                ]
            );
            return response()->json($response);
        } catch (\Twilio\Exceptions\RestException $e) {
            // Handle the error
            return response()->json(['status' => 'Error sending message', 'error' => $e->getMessage()], 400);
        }
        
        // $api_endpoint = env('WHATSAPP_API_ENDPOINT');
        // $api_token = env('WHATSAPP_API_TOKEN');

        // $data = [
        //     'phone' => $to,
        //     'body' => $message
        // ];

        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Authorization' => "Bearer $api_token",
        // ])->post($api_endpoint, $data);

        // if ($response->successful()) {
        //     return response()->json(['status' => 'Message sent successfully!']);
        // } else {
        //     return response()->json(['status' => 'Error sending message', 'error' => $response->body()], $response->status());
        // }
       
    }
}
