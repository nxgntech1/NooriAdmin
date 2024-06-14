<?php

namespace App\Services;

use Twilio\Rest\Client;

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
}
