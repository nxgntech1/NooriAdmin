<?php

namespace App\Services;

use GuzzleHttp\Client;

class TextLocalService
{
    protected $apiKey;
    protected $sender;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env('TEXTLOCAL_API_KEY');
        $this->sender = env('TEXTLOCAL_SENDER');
        $this->client = new Client();
    }

    public function sendSMS($numbers, $message)
    {
        $url = 'https://api.textlocal.in/send/';
        $data = [
            'apikey' => $this->apiKey,
            'numbers' => implode(',', (array) $numbers),
            'message' => urlencode($message),
            'sender' => $this->sender,
        ];

        $response = $this->client->post($url, [
            'form_params' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
