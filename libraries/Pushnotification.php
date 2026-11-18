<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pushnotification {

    public $CI;

    public $API_ACCESS_KEY = "";

    public $fcmUrl = "https://fcm.googleapis.com/fcm/send";

    public function __construct() {
        $this->CI = &get_instance();
        $this->API_ACCESS_KEY = getenv('FCM_SERVER_KEY') ? getenv('FCM_SERVER_KEY') : "";
    }

   /* public function send($tokens, $msg, $action = "") {
        $notificationData = [
            'title' => $msg['title'],
            'body' => $msg['body'],
            'action' => $action,
            'sound' => 'mySound',
        ];

        $fcmNotification = [
            'to' => $tokens, //single token
            'collapseKey' => "{$tokens}",
            'data' => $notificationData,
        ];
        $headers = [
            'Authorization: key=' . $this->API_ACCESS_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        return true;
    }*/

    public function send($tokens, $msg, $action =""){
        $url = 'https://fcm.googleapis.com/fcm/send';

        /*api_key loaded from environment or class property*/    
        $api_key = !empty($this->API_ACCESS_KEY) ? $this->API_ACCESS_KEY : (getenv('FCM_SERVER_KEY') ? getenv('FCM_SERVER_KEY') : "");

         $data = array(
                        "body" => $msg['body'],
                        "title" => $msg['title'],
                        // "click_action" => "default",
                        'action' => $action,/*                        
                        "Origem" => $origem,
                        "EComID" => $fields['EComID'],
                        "EComLogID" => $fields['EComLogID'],
                        "EComDesc" => $fields['EComDesc'], 
                        "EComDTH" => $fields['EComDTH'], "d/m/Y H:i:s",
                        "EComAss" => $fields['EComAss'],
                        "EcomAnx" => ((empty($fields['EcomAnx']))?'':$fields['EcomAnx']),
                        "EComEmpID" => $fields['EComEmpID'],
                        "LogNome" => $fields['LogNome'],                                            
                        "LogAvatar" => $fields['LogAvatar'] */
                    ); 


        $fields = array (
            'registration_ids' => array (
                    $tokens
            ),
            'data' => $data /*array (
                    "message" => $data
            )*/
        );

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }


    public function push_notification_android($message, $device_id){

    //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key loaded from environment or class property*/    
    $api_key = !empty($this->API_ACCESS_KEY) ? $this->API_ACCESS_KEY : (getenv('FCM_SERVER_KEY') ? getenv('FCM_SERVER_KEY') : "");

    

    

     $data = array(
                    "body" => $message,
                    "title" => "eratitle1",
                    'action' => "",
                    // "click_action" => "default",
                    /*                        
                    "Origem" => $origem,
                    "EComID" => $fields['EComID'],
                    "EComLogID" => $fields['EComLogID'],
                    "EComDesc" => $fields['EComDesc'], 
                    "EComDTH" => $fields['EComDTH'], "d/m/Y H:i:s",
                    "EComAss" => $fields['EComAss'],
                    "EcomAnx" => ((empty($fields['EcomAnx']))?'':$fields['EcomAnx']),
                    "EComEmpID" => $fields['EComEmpID'],
                    "LogNome" => $fields['LogNome'],                                            
                    "LogAvatar" => $fields['LogAvatar'] */
                ); 


    $fields = array (
        'registration_ids' => array (
                $device_id
        ),
        'data' => $data /*array (
                "message" => $data
        )*/
    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );
                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

}
