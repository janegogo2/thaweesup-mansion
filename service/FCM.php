<?php


class FCM {
    public function send_notification($token, $payload_notification, $payload_data) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $KEY = 'AAAAal2BKJ4:APA91bEV84olk0mZwOJIAIFE1WoeJaS5oSTD38Ty-IkZDsV2-bHDqDEGaeKUcbyKVth7FPplgcktktYziO4e51_qHc2JAjrec9Lb6inebRDGvMM0x-BNsrVQO3jzvkWGKkJchtqLl178';
        $fields = array(
            'registration_ids' => $token,
            'priority' => 'high',
            'notification' => $payload_notification,
            //'data' => $payload_data
        );
        $headers = array(
            'Authorization: key='.$KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporary
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
         //   Log::error('Curl failed: ' . curl_error($ch));
            //die('Curl failed: ' . curl_error($ch));
            return $result;
        }
        // Close connection
        curl_close($ch);
        return $result;
    }
}