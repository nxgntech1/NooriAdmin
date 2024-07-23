<?php

namespace App\Services;

use GuzzleHttp\Client;

class SMSCountryService
{
    protected $url;
    protected $senderid;
    protected $client;

    public function __construct()
    {
        $this->senderid = env('SMS_COUNTRY_SENDER');
        $this->url = env('SMS_COUNTRY_API_URL');
        $this->client = new Client();
    }

    public function sendSMS($number, $message)
    {
        try{
            $this->url = $this->url.$number.'&message='.$message.'&sid='.$this->senderid;

            $response = $this->client->get($this->url);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}