<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;

use Google\Client as Google_Client;


require 'C:\Websites\NooriTravels\cabme-admin-panel\vendor\autoload.php';

use Config;

class GcmController extends Controller
{
	 /**
     * Sending Push Notification
     */
    public static function send_notification($tokens,$message,$data) {
        // Set POST variables
        $url='https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' 	=> $tokens,
            'notification' => $message,
            'data'=>$data,
            'content_available' => true,
            'priority' => 'high',
        );
        
        $headers = array(
            'Authorization:key='.Config::get('constant.apikey.GOOGLE_API_KEY'),
            'Content-Type:application/json'
        );
		
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $url );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

        $result = curl_exec($ch );

        if($result===FALSE){
            die('Curl Failed:'.curl_error($ch));
        }
        curl_close( $ch );

        return $result;
    }

    public static function getAccessToken() {
        //$keyFilePath = 'C:\\Websites\\NooriTravels\\cabme-admin-panel\\nooritravels-e48f7-aa691c6b2ca1.json'; // Update this path
        //$keyFilePath = storage_path('nooritravels-e48f7-aa691c6b2ca1.json');

        $rootPath = base_path();
        $keyFilePath = $rootPath . '/nooritravels-e48f7-192f4d538ca7.json';

    
        $client = new Google_Client();
        $client->setAuthConfig($keyFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    
        // if ($client->isAccessTokenExpired()) {
             $client->fetchAccessTokenWithAssertion();
        // }
    
        $accessToken = $client->getAccessToken();
    
        return $accessToken['access_token'];
    }

    public static function sendNotification($tokens, $message, $data) {
        $url = 'https://fcm.googleapis.com/v1/projects/nooritravels-e48f7/messages:send';
        
        // Get the OAuth 2.0 token using the service account
        $accessToken = GcmController::getAccessToken();  // Implement this function to get the access token
        
        $fields = array(
            'message' => array(
                'token' => $tokens,  // If using multiple tokens, you need to send them one by one or use 'topic'
                'notification' => $message,
                'data' => $data,
                'android' => array(
                    'priority' => 'high'
                )
            )
        );
    
        $headers = array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        );
        
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