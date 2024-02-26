<?php
namespace App\Http\Controllers\Integrations;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use DB;
use Redirect;
use Auth;
use Carbon\Carbon;

///////////////////////////////////////////////////////// NOTE VERY IMPORTANT ///////////////////////////////////////////////////////////
// don't forget to edit /radius/sms.php file for any changes in page
///////////////////////////////////////////////////////// NOTE VERY IMPORTANT ///////////////////////////////////////////////////////////
class SMS
{

    public function IsItUnicode($msg)
    { // 0=English / 1=Arabic
        $unicode = 0;
        $str = "ط¯ط¬ط­ط®ظ‡ط¹ط؛ظپظ‚ط«طµط¶ط·ظƒظ…ظ†طھط§ظ„ط¨ظٹط³ط´ط¸ط²ظˆط©ظ‰ظ„ط§ط±ط¤ط،ط¦ط¥ظ„ط¥ط£ظ„ط£ط¢ظ„ط¢";
        for ($i = 0; $i <= strlen($str); $i++) {
            $strResult = substr($str, $i, 1);
            for ($R = 0; $R <= strlen($msg); $R++) {
                $msgResult = substr($msg, $R, 1);
                if ($strResult == $msgResult && $strResult)
                    $unicode = 1;
            }
        }
        return $unicode;
    }

    // Fire and Forget HTTP Request (https://cwhite.me/blog/fire-and-forget-http-requests-in-php)
    // this function must be called as the last step in any sending message function
    public function sendingWithoutWaiting($url, $msg, $specialPort = null){
        
        $endpoint = $url;
        $postData = $msg;

        $endpointParts = parse_url($endpoint);
        $endpointParts['path'] = $endpointParts['path'] ?? '/';
        if(isset($specialPort)){$endpointParts['port'] = $specialPort;}
        else{$endpointParts['port'] = $endpointParts['port'] ?? $endpointParts['scheme'] === 'https' ? 443 : 80;}
        
        $contentLength = strlen($postData);

        $request = "POST {$endpointParts['path']} HTTP/1.1\r\n";
        $request .= "Host: {$endpointParts['host']}\r\n";
        $request .= "User-Agent: Loglia Laravel Client v2.2.0\r\n";
        $request .= "Authorization: Bearer api_key\r\n";
        $request .= "Content-Length: {$contentLength}\r\n";
        $request .= "Content-Type: application/json\r\n\r\n";
        $request .= $postData;

        $prefix = substr($endpoint, 0, 8) === 'https://' ? 'tls://' : '';

        $socket = fsockopen($prefix.$endpointParts['host'], $endpointParts['port']);
        fwrite($socket, $request);
        fclose($socket);
        $response = "sent without waiting";
        return $response;
        /////////////////////////////Fire and Forget HTTP Request //////////////////////////
    }

    public function Send($to, $message, $sender=null, $database=null)
    {
        $smsState = App\Settings::where('type', 'SMSProvider')->value('state');
        if($smsState == "1"){
            date_default_timezone_set("Africa/Cairo");
            $today = date("Y-m-d");
            $today_time = date("g:i a");
            $todayDateTime = $today." ".date("H:i:s");

            // check if send SMS from unknown customer ex.cron.php
            if(isset($database) and $database!=""){

                $SMSProvider = DB::table($database.".settings")->where('type', 'SMSProvider')->value('value');
                $SMSProvider_username = DB::table($database.".settings")->where('type', 'SMSProviderusername')->value('value');
                $SMSProvider_password = DB::table($database.".settings")->where('type', 'SMSProviderpassword')->value('value');
                if(isset($sender)){$SMSProvider_sendername = $sender;}
                else{$SMSProvider_sendername = DB::table($database.".settings")->where('type', 'SMSProvidersendername')->value('value');}
                
            }else{
                
                $SMSProvider = App\Settings::where('type', 'SMSProvider')->value('value');
                $SMSProvider_username = App\Settings::where('type', 'SMSProviderusername')->value('value');
                $SMSProvider_password = App\Settings::where('type', 'SMSProviderpassword')->value('value');
                if(isset($sender)){$SMSProvider_sendername = $sender;}
                else{$SMSProvider_sendername = App\Settings::where('type', 'SMSProvidersendername')->value('value');}
            }
            $unicode = $this->IsItUnicode($message);
            $messageWithoutEncoding = $message;
            $unicode_symbol = ($unicode == 1) ? 'ar' : 'en';
            $message = urlencode($message);

            $phoneWithoutCountryCode = $to;
            $phone_count = strlen($phoneWithoutCountryCode);
            //$phones = $phoneWithoutCountryCode['1'] . $phoneWithoutCountryCode['2'];

            if(isset($phoneWithoutCountryCode['0'])){

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

                        $api = "http://smsc.razytech.com/SmsC-GateWay-Bulk/sendsms?un=$SMSProvider_username&ps=$SMSProvider_password&org=$SMSProvider_sendername&op=$egyptionOperator&dist=$to&lang=$unicode_symbol&tx=$message";
                        $response = @file($api);
                        $find = "Accepted";
                        $string = $response[0];
                        if (strpos($string, $find) === false) {
                        } else {
                            $message_sent_successfully = "yes";
                        }
                    } else if($SMSProvider == 6){
                        
                        // check if this is Egyptian number we should remove country code (2)
                        // if this is non Egyptian number we should add (00) 
                        if($to[0] == "2"){
                            // Egyptian number remove country code (2)
                            $toCustomOrange = substr($to, 1); // $toWithoutCountryCode
                        }else{
                            // non Egyptian number we should add (00) 
                            $toCustomOrange = "00".$to;
                        }
                        
                        $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
                        $api = "https://wifi.orange.eg/hotspotsadmin/pages/SMSHandler.ashx?MobileNo=$toCustomOrange&code=$message&username=$SMSProvider_username&password=$SMSProvider_password";
                        $response = @file($api, FALSE, stream_context_create($arrContextOptions));
                        
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
                        // $api = "https://www.smsmisr.com/api/send/?username=$SMSProvider_username&password=$SMSProvider_password&language=$unicode_type&sender=$SMSProvider_sendername&mobile=$to&message=$message";
                        // $response = @file($api);
                        // // $response = $this->sendingWithoutWaiting($api, " ");
                        // $find = "1901";
                        // $string = $response[0];
                        // if (strpos($string, $find) === false) {
                        // } else {
                        //     $message_sent_successfully = "yes";
                        // }

                        // send SMS Message by POST method
                        // $data = ['Username' => $SMSProvider_username, 'password' => $SMSProvider_password, 'language' => $unicode_type, 'sender' => $SMSProvider_sendername, 'Mobile' => $to, 'message' => $message];
                        $data = ['username' => $SMSProvider_username, 'password' => $SMSProvider_password, 'language' => $unicode_type, 'sender' => $SMSProvider_sendername, 'mobile' => $to, 'message' => $message, 'environment' => '1'];
                        $msg = json_encode($data); // Encode data to JSON
                        // $url = 'https://smsmisr.com/api/v2/?';
                        $url = 'https://smsmisr.com/api/SMS/?';
                        $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                        $response = json_decode(@file_get_contents($url, FALSE, $context));
                        if($response->code == 1901){
                            //  return json_encode(array('state' => 1, 'message' =>'SMS sent successfully by SMS Misr')); 
                        }else{
                            //  return json_encode(array('state' => 1, 'message' =>'error in sening message by SMS Misr')); 
                        }

                    }else if ($SMSProvider == 10) { // SMS MISR Whitelable - sms2.microsystem.com.eg
                        
                        $unicode_type = ($unicode == 1) ? '2' : '1';
                        $api = "https://sms2.microsystem.com.eg/api/send/?username=$SMSProvider_username&password=$SMSProvider_password&language=$unicode_type&sender=$SMSProvider_sendername&mobile=$to&message=$message";
                        $response = @file($api);
                        $find = "1901";
                        $string = $response[0];
                        if (strpos($string, $find) === false) {
                        } else {
                            $message_sent_successfully = "yes";
                        }
                    }else if ($SMSProvider == 11) { // VictoryLink Whitelable
                        
                        // make sure there is a number and this number is egyptian no
                        if(isset($to) and $to[0] == 2){
                            
                            // export operator
                            $operatorCode = $to[1].$to[2].$to[3];
                            if($operatorCode == "010"){$operator = "Vodafone";}
                            if($operatorCode == "011"){$operator = "Etisalat";}
                            if($operatorCode == "012"){$operator = "Orange";}
                            if($operatorCode == "015"){$operator = "We";}
                        
                            // get customer id
                            $split = explode('/', url()->full());
                            $customerData = DB::table('customers')->where('url',$split[2])->first();

                            // create unique id
                            function GUID(){
                                if (function_exists('com_create_guid') === true)
                                {return trim(com_create_guid(), '{}');}
                                return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                            }
                            $uniqueMsgId = GUID();

                            // DB insert tracking log record to know if sms deleverd or not
                            DB::table("sms")->insert([['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '1','customer_id' => $customerData->id, 'type' => 'marketing', 'operator' => $operator, 'mobile' => $to, 'message'=> $messageWithoutEncoding, 'created_at'=> $todayDateTime, 'guid'=> $uniqueMsgId ]]);

                            // SMS Language
                            $unicode_type = ($unicode == 1) ? 'ar' : 'en';
                            // $unicode_type = 'ar';
                            // build request
                            $data = ['Username' => $SMSProvider_username, 'Password' => $SMSProvider_password, 'SMSLang' => $unicode_type, 'SMSSender' => $SMSProvider_sendername, 'SMSReceiver' => $to, 'SMSText' => $messageWithoutEncoding, 'SMSID'=> $uniqueMsgId, 'DLRURL'=> 'https://demo.microsystem.com.eg/api/smsStatusDLR' ];
                            $msg = json_encode($data); // Encode data to JSON
                            $url = 'https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMSWithDLR';
                            $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                            $response = json_decode(@file_get_contents($url, FALSE, $context));
                            if($response == 0){
                                $message_sent_successfully = "yes";
                                // DB::table("settings")->where('type','smsMisrCredit')->decrement('value', 1);
                            }else{
                                
                                // return json_encode(array('state' => 1, 'message' =>'error in sening message by SMS Misr')); 
                            }
                            
                        }

                    } else if($SMSProvider == 12){ // Orange Wi-Fi SMS verification through Damanhour railway AS ralay to avoid IP error
                        
                        // check if this is Egyptian number we should remove country code (2)
                        // if this is non Egyptian number we should add (00) 
                        $to = str_replace('+','', $to); // Replaces (+) if exist.
                        if($to[0] == "2"){
                            // Egyptian number remove country code (2)
                            $toCustomOrange = substr($to, 1); // $toWithoutCountryCode
                        }else{
                            // non Egyptian number we should add (00) 
                            $toCustomOrange = "00".$to;
                        }
                        
                        $api = "https://wifi.orange.eg/hotspotsadmin/pages/SMSHandler.ashx?MobileNo=$toCustomOrange&code=$message&username=$SMSProvider_username&password=$SMSProvider_password";
                        $data = ['orangeSMS' => $api];
                        $msg = json_encode($data); // Encode data to JSON
                        // $url = 'http://41.196.2.234:5050/sms.php';  // DAMANHOUR Direct
                        $url = 'http://orangesms.mymicrosystem.com:5050/sms.php'; // DAMANHOUR or MicrosystemEgyptSrv
                        $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                        $response = json_decode(@file_get_contents($url, FALSE, $context));
                        // $response = @file($api, FALSE, stream_context_create($arrContextOptions));
                        
                    }

                }
            }
        }
    }

    public function SendVerifySMS($to, $uID)
    {
        $avilable = DB::table("sms_verify")->where('state', '1')->where('credit', '>', '0')->get();
        if(isset($avilable)){
            $totalavilable = count($avilable);
            $rand = rand(0,$totalavilable-1);
			$selectedSmsVerifyID = $avilable[$rand]->id;
			
			// sending
			$response = exec('curl --data-urlencode "phone='.$to.'" --data-urlencode "api_key='.$avilable[$rand]->api_key.'" https://api.ringcaptcha.com/'.$avilable[$rand]->app_key.'/code/sms');
			$responseD = json_decode($response);
			if($responseD->status=="SUCCESS"){ 
				// update selectedSMSverifyID into user record in field 'token' to use it in verification phase
				App\Users::where('u_id', $uID)->update(['deviceToken' => $selectedSmsVerifyID]);
				// discount selectedSMSverify credit
				DB::table("sms_verify")->where('id', $selectedSmsVerifyID)->update(['credit' => $avilable[$rand]->credit-1]);
				return $response;
				return $responseD->retry_in; 
			}else{return "$response";}

        }else{
            // no enough credit
            // sending email
            return "0";
		}
    }
}
