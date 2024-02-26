<?php

  
    $SMSProvider_value = @mysqli_query($conn,"select `value` from `settings` where `type`='SMSProvider'");
    $SMSProvider_row=@mysqli_fetch_array($SMSProvider_value);
    $SMSProvider=$SMSProvider_row['value'];

    $SMSProvider_username_value = @mysqli_query($conn,"select `value` from `settings` where `type`='SMSProviderusername'");
    $SMSProvider_username_row=@mysqli_fetch_array($SMSProvider_username_value);
    $SMSProvider_username=$SMSProvider_username_row['value'];
 
    $SMSProvider_password_value = @mysqli_query($conn,"select `value` from `settings` where `type`='SMSProviderpassword'");
    $SMSProvider_password_row = @mysqli_fetch_array($SMSProvider_password_value);
    $SMSProvider_password = $SMSProvider_password_row['value'];
    
    $SMSProvider_sendername_value = @mysqli_query($conn,"select `value` from `settings` where `type`='SMSProvidersendername'");
    $SMSProvider_sendername_row=@mysqli_fetch_array($SMSProvider_sendername_value);
    $SMSProvider_sendername=$SMSProvider_sendername_row['value'];

    // send whatsapp with SMS 11/9/2019
    $whatsappState_value = @mysqli_query($conn,"select `state` from `settings` where `type`='whatsappProvider'");
    $whatsappState_row=@mysqli_fetch_array($whatsappState_value);
    $whatsappState=$whatsappState_row['state'];
    if(isset($whatsappState) and $whatsappState == 1){
        
        $url="https://demo.microsystem.com.eg/api/whatsapp?type=send";
        $customerID_value = @mysqli_query($conn,"select `value` from `settings` where `type`='customer_id'");
        $customerID_row=@mysqli_fetch_array($customerID_value);
        $customerID=$customerID_row['value'];

        $customerPassword_value = @mysqli_query($conn,"select `value` from `settings` where `type`='customer_password'");
        $customerPassword_row=@mysqli_fetch_array($customerPassword_value);
        $customerPassword=$customerPassword_row['value'];
        $WA_message = urlencode($message);
        $msg = '{ "username": "'.$systemID.'", "password": "'.$customerPassword.'", "customer_id": "'.$customerID.'", "load_balance": "", "server_mobile": "", "client_mobile": "'.$to.'", "msg_type": "", "message": "'.$WA_message.'", "urlencode": "1"}';
        $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
        $response = @file_get_contents($url, FALSE, $context);
    }

    // check incode
    function IsItUnicode($msg){
      $unicode=0;
      $str = "دجحخهعغفقثصضطكمنتالبيسشظزوةىلارؤءئإلإألأآلآ";
      for($i=0;$i<=strlen($str);$i++)
          {
          $strResult= substr($str,$i,1);
          for($R=0;$R<=strlen($msg);$R++)
              {
               $msgResult= substr($msg,$R,1);
                if($strResult==$msgResult && $strResult)
                   $unicode=1;
               }
         }
    
    return $unicode;
    }
    //$unicode_symbol = ($unicode == 1) ? 'ar' : 'en';
    $unicode = IsItUnicode($message);
    if($unicode=="1"){$unicode_symbol="ar";}else{$unicode_symbol="en";}

    $message_without_encode=$message;
    //$utf8_encoded=utf8_encode($message);
    $message = urlencode($message);

    $phoneWithoutCountryCode = $to;
    $phone_count = strlen($phoneWithoutCountryCode);
    //$phones = $phoneWithoutCountryCode['1'] . $phoneWithoutCountryCode['2'];


    if ($phoneWithoutCountryCode['0'] != 2) {
        $cansend = 1;
    }
    if ($phoneWithoutCountryCode['0'] == 2 and $phone_count == "12" and (strpos($to, '010') !== false || strpos($to, '011') !== false || strpos($to, '012') !== false)) {
        $cansend = 1;
    }
    if (isset($cansend)) {
        if ($SMSProvider == 1) {
            $api = "http://www.resalty.net/api/sendSMS.php?userid=$SMSProvider_username&password=$SMSProvider_password&to=$to&sender=$SMSProvider_sendername&msg=$message";
            $response = @file($api);
            $find = "MessageID";
            $string = $response[0];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 2) {
            $api = "http://sms.masrawy.com/send.aspx?userName=$SMSProvider_username&password=$SMSProvider_password&mobile=$to&message=$message&Sender=$SMSProvider_sendername";
            $response = @file($api);
            $find = "SentSuccessfully";
            $string = $response[0];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 3) {
            $api = "http://api.valuedsms.com/?command=send_sms&sms_type=0&username=$SMSProvider_username&password=$SMSProvider_password&destination=$to&message=$message&sender=$SMSProvider_sendername";
            $response = @file($api);
            $find = "true";
            $string = $response[0];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 4) {
            $api = "http://api.infobip.com/api/v3/sendsms/plain?user=$SMSProvider_username&password=$SMSProvider_password&sender=$SMSProvider_sendername&GSM=$to&datacoding=8&SMSText=$message";
            $response = @file($api);
            $find = "<result><status>0";
            $string = $response[2];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 5) {

            // //remove contry code "2"
            // if($phoneWithoutCountryCode['0'] == 2){
            //     $to=substr($to, 1);
            // }

            // get operatior number
            if (substr($to, 0, 3) === "012") {
                $egyptionOperator = "1";
            }// 1 Mobinil
            elseif (substr($to, 0, 3) === "010") {
                $egyptionOperator = "2";
            }// 2 Vodafone
            elseif (substr($to, 0, 3) === "011") {
                $egyptionOperator = "3";
            }// 3 Etisalat
            else {
                $egyptionOperator = "";
            }

            //start sending
            $api = "http://smsc.razytech.com/SmsC-GateWay-Bulk/sendsms?un=$SMSProvider_username&ps=$SMSProvider_password&org=$SMSProvider_sendername&op=$egyptionOperator&dist=$to&lang=$unicode_symbol&tx=$message";
            $response = @file($api);
            $find = "Accepted";
            $string = $response[0];
            // //test
            // $test=$curr_campaign['id'];
            // @mysqli_query($conn,"insert into `campaign_statistics` (`campaign_id`,`u_id`,`type`,`created_at`) values ('66666666666','666666','$api','$created_at') ");

            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if($SMSProvider == 6){
            //remove 2 from mobile number
            //$toSplited=explode("2",$to,1);
            $toWithoutCountryCode = substr($to, 1);
            $api = "https://wifi.orange.eg/hotspotsadmin/pages/SMSHandler.ashx?MobileNo=$toWithoutCountryCode&code=$message&username=$SMSProvider_username&password=$SMSProvider_password";
            $response = @file($api);
            /*$find = "[]";
            $string = $response[1];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
            */
        } else if ($SMSProvider == 7) {
            $api = "http://www.mobily.ws/api/msgSend.php?mobile=$SMSProvider_username&password=$SMSProvider_password&numbers=$to&sender=$SMSProvider_sendername&msg=$message&timeSend=0&dateSend=0&applicationType=68&lang=UTF-8";
            $response = @file($api);
            $find = "1";
            $string = $response[0];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 8) { // UAE smart SMS
            $unicode_type = ($unicode == 1) ? 'unicode' : 'text';
            $api = "http://smartsmsgateway.com/api/api_http.php?username=$SMSProvider_username&password=$SMSProvider_password&senderid=$SMSProvider_sendername&to=$to&text=$message&type=unicode";
            $response = @file($api);
            $find = "OK";
            $string = $response[0];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 9) { // SMS MISR - sms.com.eg
            $unicode_type = ($unicode == 1) ? '2' : '1';
            $api = "https://www.smsmisr.com/api/send/?username=$SMSProvider_username&password=$SMSProvider_password&language=$unicode_type&sender=$SMSProvider_sendername&mobile=$to&message=$message";
            $response = @file($api);
            $find = "1901";
            $string = $response[0];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 10) { // SMS MISR Whitelable - sms2.microsystem.com.eg
            $unicode_type = ($unicode == 1) ? '2' : '1';
            $api = "https://sms2.microsystem.com.eg/api/send/?username=$SMSProvider_username&password=$SMSProvider_password&language=$unicode_type&sender=$SMSProvider_sendername&mobile=$to&message=$message";
            $response = @file($api);
            $find = "1901";
            $string = $response[0];
            if (strpos($string, $find) === false) {
            } else {
                $message_sent_successfully = "yes";
            }
        } else if ($SMSProvider == 11) { // VictoryLink Whitelable
                
            // make sure there is a number and this number is egyptian no
            if(isset($to) and $to[0] == 2){
                
                // export operator
                $operatorCode = $to[1].$to[2].$to[3];
                if($operatorCode == "010"){$operator = "Vodafone";}
                if($operatorCode == "011"){$operator = "Etisalat";}
                if($operatorCode == "012"){$operator = "Orange";}
                if($operatorCode == "015"){$operator = "We";}
    
                // create unique id
                function GUID(){
                    if (function_exists('com_create_guid') === true)
                    {return trim(com_create_guid(), '{}');}
                    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                }
                $uniqueMsgId = GUID();

                // SMS Language
                $unicode_type = ($unicode == 1) ? 'ar' : 'en';
                $message = urldecode($message);
                
                // build request
                $data = ['Username' => $SMSProvider_username, 'Password' => $SMSProvider_password, 'SMSLang' => $unicode_type, 'SMSSender' => $SMSProvider_sendername, 'SMSReceiver' => $to, 'SMSText' => $message_without_encode, 'SMSID'=> $uniqueMsgId ];
                $msg = json_encode($data); // Encode data to JSON
                $url = 'https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMS';
                $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                $response = json_decode(@file_get_contents($url, FALSE, $context));
                if($response == 0){
                    $message_sent_successfully = "yes";
                    // DB::table("settings")->where('type','smsMisrCredit')->decrement('value', 1);
                }else{
                    // return json_encode(array('state' => 1, 'message' =>'error in sening message by SMS Misr')); 
                }
                
            }

        }
    }


    

?>