<?php
namespace App\Http\Controllers;
use SSH;
use DB;
use Illuminate\Http\Request;
use App;
use Mail;

class ApiController
{
    public function Index(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		
		require_once '../config.php';
        // $output = shell_exec('sudo /sbin/service named restart');
        // sleep(10);
        
        // $output = exec('sleep 5s');
        //$output = exec('sleep 5s; sudo service httpd restart; sleep 5s');
        // $output = shell_exec('ls -l & sudo sh /scripts/restart_httpd > /dev/null 2>&1 & echo $!');
        // return "<pre>$output</pre>";

        $content = $request->all();
        // return $content;
        // $fp = stream_socket_client("http://www.microsystem.com.eg", $errno, $errstr, 33);
        // if (!$fp) {
        //     echo "$errstr ($errno)<br />\n";
        // } else {
        //     fwrite($fp, "GET / HTTP/1.0\r\nHost: www.example.com\r\nAccept: */*\r\n\r\n");
        //     while (!feof($fp)) {
        //         echo fgets($fp, 1024);
        //     }
        //     fclose($fp);
        // }

        
        // try {
        //     $fp = fsockopen("www.microsystem.com.eg", 443, $errno, $errstr, 0.01);
        //     if (!$fp) {
        //         echo "$errstr ($errno)<br />\n";
        //     } else {
        //         echo "No error<br />\n";
        //     }
        // } catch (Exception $e) {
        //     echo 'Connection time out';
        // }

        // Send($from=null, $to, $message, $msg_type=null, $senderName=null, $customerID=null, $database=null)
        $split = explode('/', url()->full());
        $customerData = DB::table('customers')->where('url',$split[2])->first();
        

        $from = "201096622600";
        $to = "201061030454";
        $message = "internal sys test ❤ ☺ ☹ ☠ ✊☝✌✋✍☘⭐✨⚡☄⚽✈⛴⛲⏲☎⌚⚙⛓⏰❣☮✝☪♨⭕❌⛔✅❎❇✳1⃣ 2⃣ ▶⏬↘➖➕➗✖⚫♥♦";
        // $msg_type = "text";
        // $senderName = "Microsystem";
        $customerID = "3";
        $database = "demo";
        // $loadBalance="0";
        // $msgType="";

        $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
        // return $sendWhatsappMessage->send( $from, $to , $message, $msg_type, $senderName, $customerID, $database, $loadBalance, $msgType);
        return $sendWhatsappMessage->send( "", $to , $message, $customerID, $database);
        
        // $timeout = ini_get("default_socket_timeout");
        // return $timeout;

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $body = @file_get_contents('php://input');
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => "$todayDateTime", 'value3' => $body]]);
    }

    // retrive moved to AdminApiController
    public function sendMicrosystemSMS($customer_id, $type, $to, $message){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		is_numeric($customer_id) ? : $customer_id = DB::table("customers")->where('database',$customer_id)->value('id');
        if($type == "verification"){$type = 0;}
        elseif($type == "text"){$type = 1;}
        elseif($type == "chatbot"){$type = 2;}

        if(isset($to) and isset($to[0]) and $to[0] == 2){
            // export operator
            $operatorCode = $to[1].$to[2].$to[3];
            if($operatorCode == "010"){$operator = "Vodafone";}
            if($operatorCode == "011"){$operator = "Etisalat";}
            if($operatorCode == "012"){$operator = "Orange";}
            if($operatorCode == "015"){$operator = "We";}

            /*
            // check credit
            $Mikrotik1_vodafoneCredit = DB::table("settings")->where('type','Mikrotik1_vodafoneCredit')->value('value');
            $Mikrotik1_etisalatCredit = DB::table("settings")->where('type','Mikrotik1_etisalatCredit')->value('value');
            $Mikrotik1_orangeCredit = DB::table("settings")->where('type','Mikrotik1_orangeCredit')->value('value');
            $Mikrotik1_weCredit = DB::table("settings")->where('type','Mikrotik1_weCredit')->value('value');
            $smsMisrCredit = DB::table("settings")->where('type','smsMisrCredit')->value('value');

            if($Mikrotik1_vodafoneCredit < 100 or $Mikrotik1_etisalatCredit <100 or $Mikrotik1_orangeCredit <100 or $Mikrotik1_weCredit <100 or $smsMisrCredit <100){
                // sending email to notify credit
                // $content = "Dear Microsystem support team, <br> <font color=red> Low Credit at Microsystem SMS server,</font> Plasee add credit more than 100 point <br>
                // <strong> Vodafone Credit: $Mikrotik1_vodafoneCredit </strong> <br>=> <a targe='_blank' href='https://web.vodafone.com.eg/auth'> Recharge Vodafone.</a> username: 01011539990| Password: 1403636_Mra  <br>
                // <strong> Etisalat Credit: $Mikrotik1_etisalatCredit </strong> <br>=> <a targe='_blank' href='https://www.etisalat.eg/LoginApp/'> Recharge Etisalat.</a> username: support@microsystem.com.eg | Password: 1403636Mra  <br>
                // <strong> Orange Credit: $Mikrotik1_orangeCredit </strong> <br>=> <a targe='_blank' href='https://www.orange.eg/en/myaccount/login'> Recharge Orange.</a> username: 01277418871 | Password: 1403636mra   <br>
                // <strong> WE Credit: $Mikrotik1_weCredit </strong> <br>=> <a targe='_blank' href='https://my.te.eg/'> Recharge WE.</a> username: 01556300735 | Password: 1403636mra   <br>
                // <strong> SMS Misr Credit: $smsMisrCredit </strong> <br>=> <a targe='_blank' href='https://smsmisr.com/user'> Recharge SMS Misr.</a> username: a.mansour@microsystem.com.eg | Password: 1403636mra   <br>
                // <br>
                // Thanks,<br>
                // Best Regards.<br>";
                // $from = "support@microsystem.com.eg";
                // $subject = "Low Credit at Microsystem SMS server";
                // $customerEmailArray = array('mr.ahmed@microsystem.com.eg', 'a.mansour@microsystem.com.eg');
                // $customerName = "Microsystem SMS server";
                // Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                //     $message->from($from, $customerName);
                //     $message->to($customerEmailArray, $customerName)->subject($subject);
                // });
            }
            */
            //////////////////////////// defence mechanism ///////////////////////////
            //////////// Check if user receive message in less than 5 min ////////////
            //////////////////////////////////////////////////////////////////////////
            $check5Min = DB::table('sms')->where('mobile', $to)->where('state', '1')->whereRaw('created_at + interval 5 minute >= ?', [$todayDateTime])->first();
            if(isset($check5Min)){
                // not sending message because user already sent in less than 5 min
                DB::table("sms")->insert([['state' => '5','customer_id' => $customer_id, 'type' => $type == "verification" ? 0 : 1, 'operator' => $operator, 'mobile' => $to, 'message'=> $message, 'created_at'=> $todayDateTime, 'guid'=> 'notSentUserRequestSince5Min' ]]);
            }elseif($customer_id == "999"){
                // not sending message because Customer (system) is blocked
                DB::table("sms")->insert([['state' => '10','customer_id' => $customer_id, 'type' => $type == "verification" ? 0 : 1, 'operator' => $operator, 'mobile' => $to, 'message'=> $message, 'created_at'=> $todayDateTime, 'guid'=> 'notSentCustomer(system)isBlocked' ]]);
            }else{

                // check if last mikrotik check more than 10 seconds we will send message by SMSmisr
                $mikrotikSmsLastCheck = DB::table('settings')->where('type','mikrotikSmsLastCheck')->value('updated_at');
                $last10Seconds = date('Y-m-d H:i:s', strtotime($todayDateTime . ' -10 seconds'));
                if($mikrotikSmsLastCheck >= $last10Seconds){
                    // Mikrotik SMS server is online, send message by Mikrotik SMS
                    DB::table("sms")->insert([['customer_id' => $customer_id, 'type' => $type == "verification" ? 0 : 1, 'operator' => $operator, 'mobile' => $to, 'message'=> $message, 'created_at'=> $todayDateTime ]]);
                }else{
                    // Mikroitk SMS server is offline, Sending by SMS Misr
                    // $content = "Dear Microsystem support team, <br> <font color=red> Mikroitk SMS server is offline since $mikrotikSmsLastCheck, Sending by SMS Misr <br> <br> Thanks,<br> Best Regards.<br>";
                    // $from = "support@microsystem.com.eg";
                    // $subject = "Mikroitk SMS server OFFLINE";
                    // $customerEmailArray = array('mr.ahmed@microsystem.com.eg', 'a.mansour@microsystem.com.eg');
                    // $customerName = "Microsystem SMS server";
                    // Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                    //     $message->from($from, $customerName);
                    //     $message->to($customerEmailArray, $customerName)->subject($subject);
                    // });
                    // Mikroitk SMS server is offline, Sending by SMS Misr
                    
                    
                    /*
                    // send SMS Message using SMSmisr or Victory link
                    // $data = ['Username' => $SMSProvider_username, 'password' => $SMSProvider_password, 'language' => '1', 'sender' => $SMSProvider_sendername, 'Mobile' => $to, 'message' => $message];
                    
                    $SMSProvider_username = DB::table("settings")->where('type', 'SMSProviderusername')->value('value');
                    $SMSProvider_password = DB::table("settings")->where('type', 'SMSProviderpassword')->value('value');
                    $SMSProvider_sendername = DB::table("settings")->where('type', 'SMSProvidersendername')->value('value');

                    function GUID(){
                        if (function_exists('com_create_guid') === true)
                        {return trim(com_create_guid(), '{}');}
                        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                    }
                    $uniqueMsgId = GUID();
                    $data = ['Username' => 'MicroSysAPI', 'Password' => 'Tn(6-d_[7u', 'SMSLang' => 'e', 'SMSSender' => 'Microsystem', 'SMSReceiver' => $to, 'SMSText' => $message, 'SMSID'=> $uniqueMsgId, 'DLRURL'=> 'https://demo.microsystem.com.eg/api/smsStatusDLR' ];
                    $msg = json_encode($data); // Encode data to JSON
                    // $url = 'https://smsmisr.com/api/v2/?';
                    // $url = 'https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMS';
                    $url = 'https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMSWithDLR';
                    $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                    $response = json_decode(@file_get_contents($url, FALSE, $context));
                    
                    // if($response->code == 1901){
                    if($response == 0){
                        // return json_encode(array('state' => 1, 'message' =>'SMS sent successfully by SMS Misr')); 
                        DB::table("settings")->where('type','smsMisrCredit')->decrement('value', 1);
                    }else{
                        // return json_encode(array('state' => 1, 'message' =>'error in sening message by SMS Misr')); 
                    }
                    // DB update
                    // DB::table("sms")->insert([['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '1','customer_id' => $customer_id, 'type' => $type == "verification" ? 0 : 1, 'operator' => $operator, 'mobile' => $to, 'message'=> $message, 'created_at'=> $todayDateTime ]]);
                    DB::table("sms")->insert([['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '1','customer_id' => $customer_id, 'type' => $type == "verification" ? 0 : 1, 'operator' => $operator, 'mobile' => $to, 'message'=> $message, 'created_at'=> $todayDateTime, 'guid'=> $uniqueMsgId ]]);
                    */

                    // send SMS by Orange proxy 31.3.2023
                    $SMSProvider_username = "Wifi";
                    $SMSProvider_password = "$600@WydMh";
                    // if this is non Egyptian number we should add (00) 
                    $to = str_replace('+','', $to); // Replaces (+) if exist.
                    if($to[0] == "2"){
                        // Egyptian number remove country code (2)
                        $toCustomOrange = substr($to, 1); // $toWithoutCountryCode
                    }else{
                        // non Egyptian number we should add (00) 
                        $toCustomOrange = "00".$to;
                    }
                    $message = urlencode($message);
                    $api = "https://wifi.orange.eg/hotspotsadmin/pages/SMSHandler.ashx?MobileNo=$toCustomOrange&code=$message&username=$SMSProvider_username&password=$SMSProvider_password";
                    $data = ['orangeSMS' => $api];
                    $msg = json_encode($data); // Encode data to JSON
                    // $url = 'http://41.196.2.234:5050/sms.php';  // DAMANHOUR Direct
                    $url = 'http://orangesms.mymicrosystem.com:5050/sms.php'; // DAMANHOUR or MicrosystemEgyptSrv
                    $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                    $response = json_decode(@file_get_contents($url, FALSE, $context));
                    // $response = @file($api, FALSE, stream_context_create($arrContextOptions));
                    DB::table("sms")->insert([['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '1','customer_id' => $customer_id, 'type' => $type == "verification" ? 0 : 1, 'operator' => $operator, 'mobile' => $to, 'message'=> $message, 'created_at'=> $todayDateTime, 'guid'=> 'SentByOrange' ]]);
                }
            }
        }

        return json_encode(array('state' => 1, 'message' =>'SMS sent successfully by Mikrotik or SMSmisr or Victorylink or Orange.')); 

        // $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
        // // return $sendWhatsappMessage->send( $from, $to , $message, $msg_type, $senderName, $customerID, $database, $loadBalance, $msgType);
        // return $sendWhatsappMessage->send( "", $to , $message, $customerID, $database);
       
    }

   

}