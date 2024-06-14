<?php

namespace App\Services;

use Google\Client as Google_Client;

class FcmService
{
    protected $client;
    protected $projectId;
    protected $keyFilePath;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $rootPath = base_path();
        //$keyFilePath = $rootPath . '/nooritravels-e48f7-192f4d538ca7.json';
        $this->keyFilePath = $rootPath . '/nooritravels-e48f7-192f4d538ca7.json';
        $this->client = new Google_Client();

        if (!file_exists($this->keyFilePath)) {
            throw new \Exception("Service account file not found at {$this->keyFilePath}");
        }

        $this->client->setAuthConfig($this->keyFilePath);
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    }

    private function getAccessToken()
    {
        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithAssertion();
        }

        $accessToken = $this->client->getAccessToken();

        return $accessToken['access_token'];
    }

    public function sendNotification($tokens, $message, $data)
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $accessToken = $this->getAccessToken();

        $fields = [
            'message' => [
                'token' => $tokens,
                'notification' => [
                    'title' => $message['title'],
                    'body' => $message['body']
                ],
                'data' => $data,
                'android' => [
                    'priority' => 'high'
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer {$accessToken}",
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl Failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }
}
