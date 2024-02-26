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
class WhatsApp
{
    // Fire and Forget HTTP Request (https://cwhite.me/blog/fire-and-forget-http-requests-in-php)
    // this function must be called as the last step in any sending message function
    public function sendingWithoutWaiting($url, $msg, $specialPort = null){
        
        $endpoint = $url;
        $postData = $msg;

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $body = @file_get_contents('php://input');
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '2 - sendingWithoutWaiting' ]]);

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
        $context = stream_context_create([
            'ssl' => [
                'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        // $socket = fsockopen($prefix.$endpointParts['host'], $endpointParts['port']);
        $socket = stream_socket_client($prefix . $endpointParts['host'] . ':' . $endpointParts['port'], $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) { die("Connection failed: $errstr ($errno)"); }
        fwrite($socket, $request);
        fclose($socket);
        $response = "sent without waiting";
        return $response;
        /////////////////////////////Fire and Forget HTTP Request //////////////////////////
    }

    // send whatsapp message (without assigned server mobile) without waiting
    public function sendWhatsappWithoutSourceWithoutWaiting($from=null, $to, $message, $customerID, $database=null, $loadBalance=null, $senderName=null, $msg_type=null, $urlEncode=null, $campaignID=null, $resendID=null){
        // make sure there is an WhatsApp integration
        if(DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1')->count() > 0 ){

            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $body = @file_get_contents('php://input');
            DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '1 - sendWhatsappWithoutSourceWithoutWaiting' ]]);

            // prepare function sending without waiting 
            $data = ['from' => $from, 'to' => $to, 'message' => $message, 'customerID' => $customerID, 'database' => $database, 'loadBalance' => $loadBalance, 'senderName' => $senderName, 'msg_type' => $msg_type, 'urlEncode' => $urlEncode, 'campaignID' => $campaignID, 'resendID' => $resendID];
            $msg = json_encode($data); // Encode data to JSON
            $url = "http://{$_SERVER['HTTP_HOST']}/api/sendWhatsappWithoutSourceWithoutWaiting";
            return $response = $this->sendingWithoutWaiting($url, $msg); // not working with https
        }
    }

    // send whatsapp message (with assigned server mobile) without waiting
    public function sendWhatsappWithoutWaiting($customerDB, $todayDateTime, $from, $to, $message, $campaign_id=null, $pending_survey_id=null, $sentMsgID=null){
        
        // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // $body = @file_get_contents('php://input');
        // DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '1 - sendWhatsappWithoutWaiting' ]]);

        // prepare function sending without waiting 
        $data = ['customerDB' => $customerDB, 'todayDateTime' => $todayDateTime, 'from' => $from, 'to' => $to, 'message' => $message, 'campaign_id' => $campaign_id, 'pending_survey_id' => $pending_survey_id, 'sentMsgID' => $sentMsgID];
        $msg = json_encode($data); // Encode data to JSON
        $url = "http://{$_SERVER['HTTP_HOST']}/api/sendWhatsappWithoutWaiting";
        return $response = $this->sendingWithoutWaiting($url, $msg); // not working with https
    }

    // can send without assigned server mobile
    public function Send($from=null, $to, $message, $customerID, $database=null, $loadBalance=null, $senderName=null, $msg_type=null, $urlEncode=null, $campaignID=null, $resendID=null)
    {
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");  
        include '../config.php';
        // check if send SMS from unknown customer ex. cron.php
        if(isset($database) and $database!=""){

            $whatsappInfo = DB::table($database.".settings")->where('type', 'whatsappProvider')->first();
            if(!isset($whatsappInfo)){return "";}
            $whatsappProvider = $whatsappInfo->value;
            $whatsappState = $whatsappInfo->state;
            $whatsappProvider_username = DB::table($database.".settings")->where('type', 'whatsappProviderUsername')->value('value');
            $whatsappProvider_password = DB::table($database.".settings")->where('type', 'whatsappProviderPassword')->value('value');
            if(isset($senderName)){$whatsappProvider_sendername = $senderName;}
            else{$whatsappProvider_sendername = DB::table($database.".settings")->where('type', 'whatsappSenderName')->value('value');}
            
        }else{
            
            $whatsappInfo = App\Settings::where('type', 'whatsappProvider')->first();
            $whatsappProvider = $whatsappInfo->value;
            $whatsappState = $whatsappInfo->state;
            $whatsappProvider_username = App\Settings::where('type', 'whatsappProviderUsername')->value('value');
            $whatsappProvider_password = App\Settings::where('type', 'whatsappProviderPassword')->value('value');
            if(isset($senderName)){$whatsappProvider_sendername = $senderName;}
            else{$whatsappProvider_sendername = DB::table($database.".settings")->where('type', 'whatsappSenderName')->value('value');}
        }

        if(!isset($msgType)){ $msgType = "text"; }
        
        if ( $whatsappState == 1 and isset($to)) {

            // check on credentials and credit
			if(isset($whatsappProvider_username) and isset($whatsappProvider_password)){
				
				$isAuthorizedData = DB::table("customers")->where('id', $customerID )->where('database', $whatsappProvider_username )->where('password', $whatsappProvider_password)->first();
				if(!isset($isAuthorizedData)){ return "1001,Invalid Username or Pssword or customerID"; }
				if($isAuthorizedData->state != "1"){ return "1002,Inactive Hotspot System"; }
				if($isAuthorizedData->whatsapp != "1"){ return "1003,WhatsApp service is disabled"; }
				if($isAuthorizedData->whatsapp_credit < "1"){ return "1004,Not enough WhatsApp credit"; }
				// if customer not choose specific server_mobile
				if( !isset($from) or $from == ""){
                    $from = DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1')->where('integration_type', '!=','4')->value('server_mobile');
                }
                // $validateServerMobile = DB::table("whatsapp_token")->where('server_mobile', $from)->orderBy('integration_type','asc')->first();
                $validateServerMobile = DB::table("whatsapp_token")->where('server_mobile', $from)->first();

				if(isset($validateServerMobile)){
					if($validateServerMobile->state == 0){return "1005,WhatsApp number inactive";}
					if($validateServerMobile->state == 2){return "1006,WhatsApp number still in progress but not registerd";}
					if($validateServerMobile->state == 3){return "1007,WhatsApp number is blocked";}
				}else{ return "1008,WhatsApp number not registerd"; }
				$serverMobileToken = $validateServerMobile->token;
				// Discount Credit
				DB::table("customers")->where('database', $whatsappProvider_username )->where('password', $whatsappProvider_password)->update([ 'whatsapp_credit' => $isAuthorizedData->whatsapp_credit-1 ]);
			}else{
				return "1000,missing username or password";
			}

			// check if Hotspot system need to loadbalance between each WA number
            /*
            stopped bacause we can't load balance between two lines, just 3 only, also we need can't load balance if message didnt contain `code` (MADE 4 4shopping) 30.5.5022
			if( isset($loadBalance) and $loadBalance == "1" and DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1' )->where('server_mobile', '!=', $from )->count() >= 2){
				$customerDB = $whatsappProvider_username.".whatsapp";
				$lastSentMsgServerMobile = DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->where('message', 'like', '%code%')->first(); if(!isset($lastSentMsgServerMobile)){ DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->where('message', 'like', '%Wi-Fi%')->first(); } if(!isset($lastSentMsgServerMobile)){ DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->where('message', 'like', '%Microsystem%')->first(); }
                $lastServerMobileSender = DB::table("whatsapp_token")->where('server_mobile', $lastSentMsgServerMobile->server_mobile )->first();
                // echo "lastServerMobileSender->id $lastServerMobileSender->id <br>"; // debug only
				$nextNumberQuery = DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1' )->where('id', '>', $lastServerMobileSender->id )->orderBy('id','asc')->first();
                if(!isset($nextNumberQuery)){ $nextNumberQuery = DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1' )->orderBy('id','asc')->first(); }
				$from = $nextNumberQuery->server_mobile;
			}else{
				// send to direct server_mobile
				if($validateServerMobile->customer_id == "0"){ // Hotspot system 
					$customerDB = "whatsapp";
				}else{
					$customerDB = $whatsappProvider_username.".whatsapp";
				}
			}
            */
            if( isset($loadBalance) and $loadBalance == "1" and DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1' )->count() >= 2){
				$customerDB = $whatsappProvider_username.".whatsapp";
				$lastSentMsgServerMobile = DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->where('message', 'like', '%code%')->first(); if(!isset($lastSentMsgServerMobile)){ $lastSentMsgServerMobile = DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->where('message', 'like', '%Wi-Fi%')->first(); } if(!isset($lastSentMsgServerMobile)){ $lastSentMsgServerMobile = DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->where('message', 'like', '%Microsystem%')->first(); }        if(!isset($lastSentMsgServerMobile)){ $lastSentMsgServerMobile = DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->first(); }
                $lastServerMobileSender = DB::table("whatsapp_token")->where('server_mobile', $lastSentMsgServerMobile->server_mobile )->first();
                // return "lastServerMobileSender->id $lastServerMobileSender->id <br>"; // debug only
				$nextNumberQuery = DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1' )->where('id', '>', $lastServerMobileSender->id )->orderBy('id','asc')->first();
                if(!isset($nextNumberQuery)){ $nextNumberQuery = DB::table("whatsapp_token")->where('customer_id', $customerID )->where('state', '1' )->orderBy('id','asc')->first(); }
				$from = $nextNumberQuery->server_mobile;
			}else{
				// send to direct server_mobile
				if($validateServerMobile->customer_id == "0"){ // Hotspot system 
					$customerDB = "whatsapp";
				}else{
					$customerDB = $whatsappProvider_username.".whatsapp";
				}
			}
			// make sure all message sent from Radius page with emojis will send successfully
			if( isset($urlEncode) and $urlEncode == "1" ){
				$message = urldecode($message);
			}
			// to avoid any problem
            if( !isset($campaignID) ){ $campaignID = "";}
            
            // make sure this is not resending MSG
            if(isset($resendID)){
                $sentMsgID = $resendID;
            }else{
                //insert into there customer database
                $sentMsgID = DB::table($customerDB)->insertGetId([
                    'send_receive' => '0'
                    , 'campaign_id' => $campaignID
                    , 'server_mobile' => $from
                    , 'client_mobile'=> $to
                    , 'message' => $message
                    , 'created_at' => $todayDateTime]);
            }

            if($validateServerMobile->integration_type == "1"){
                // Yowsup
                // to mark sent message to know it when sent successfully from WA server
                $to = $sentMsgID."@".$to."_";
                // to makesure the supported emojis well send succesfully and recevice there sent notification succssfully
                $finalSendMessage = base64_encode($message);
                // sending
                $serverMobileToken = DB::table("whatsapp_token")->where('server_mobile', $from )->value('token');
                $url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/whatsapp";
                // $msg = $serverMobileToken.','.$from.','.'/message_sync_send "'.$to.'" "'.$finalSendMessage.'" ';
                // sleep(1);
                $msg = $serverMobileToken.','.$from.','.'message_sync_typing_send,'.$to.' "'.$finalSendMessage.'" ';
                // $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                // $response = @file_get_contents($url, FALSE, $context);
                $response = $this->sendingWithoutWaiting($url, $msg, $whatsapp_Srv1_OPsocketPort);
                
                if( $response == "in" or $response == "ok" or $response == "1" or $response == "sent without waiting"){ return "1"; }else{ return "0"; }
            }elseif($validateServerMobile->integration_type == "2"){
                // chat API
                $data = ['phone' => $to,'body' => $message];
                $msg = json_encode($data); // Encode data to JSON
                $url = $validateServerMobile->chatapi_instance_url."/sendMessage?token=".$validateServerMobile->chatapi_instance_token;
                $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                $response = @file_get_contents($url, FALSE, $context);
                $response = json_decode($response);
                if(!isset($response->id)){$response->id = "ChatAPI_NotFoundID";}
                DB::table($customerDB)->where('id',$sentMsgID)->update(['msg_id' => $response->id, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                // sent response 1|0
                if(isset($response->sent) and $response->sent == "true"){ return "1";}else{ return "0";}
            
            }elseif($validateServerMobile->integration_type == "3"){
                // from mercury.chat
                $data = ['phone' => $to,'body' => $message];
                $msg = json_encode($data); // Encode data to JSON
                $url = $validateServerMobile->chatapi_instance_url."/sendMessage?api_token=".$validateServerMobile->chatapi_instance_token."&instance=".$validateServerMobile->chatapi_instance_id;
                $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                $response = @file_get_contents($url, FALSE, $context);
                $response = json_decode($response);
                $responseMsgID = "0";
                DB::table($customerDB)->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                if(isset($response->data->status_queued) and $response->data->status_queued == 1){ return "1";}else{ return "0";}

            }elseif($validateServerMobile->integration_type == "4"){
                // Telegram
                // get Telegram ID from `users` table
                $telegramID = DB::table( $database.".users" )->where('u_phone', $to)->value('telegram_id');
                $data = ['chat_id' => $telegramID,'text' => $message];
                $msg = json_encode($data); // Encode data to JSON
                $url = "https://api.telegram.org/bot$validateServerMobile->telegram_api_token/sendMessage";
                DB::table($database.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => rand(11111,99999), 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                // return $response = $this->sendingWithoutWaiting($url, $msg); // not working with https

                $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                $response = @file_get_contents($url, FALSE, $context);
                $response = json_decode($response);
                // sent response 1|0
                if(isset($response->ok) and $response->ok == "true"){ return "1";}else{ return "0";}

            }elseif($validateServerMobile->integration_type == "5"){
                // Mikofi
                // echo "$from"; // debug only

                // OLD version
                /*
                $validateServerMobile = DB::table("whatsapp_token")->where('server_mobile', $from )->first();
                // After New Update 31.07.2023 to be POST Request
                $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
                $url = "https://www.mikofi.com/api/send.php?access_token=".$validateServerMobile->chatapi_instance_token."&instance_id=".$validateServerMobile->chatapi_instance_id."&type=text&number=".$to."&message=".urlencode($message);
                $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
                $response = json_decode($response);
                if(isset($response->data->key->id)){$responseMsgID = $response->data->key->id;}else{$responseMsgID = "Mikofi_NotFoundID";}
                DB::table($customerDB)->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                // sent response 1|0
                if(isset($response->status) and $response->status == "success"){ return "1";}else{ return "0";}
                */

                // New Version
                $validateServerMobile = DB::table("whatsapp_token")->where('server_mobile', $from )->first();
                // After New Update 31.07.2023 to be POST Request
                $msg = json_encode(['number' => $to, 'message' => $message, 'type' => 'text', 'access_token' => $validateServerMobile->chatapi_instance_token, 'instance_id' => $validateServerMobile->chatapi_instance_id]); // Encode data to JSON
                $arrContextOptions=array('http' => array('method' => 'POST', 'header' => "Content-Type: application/json\r\n", 'content' => "$msg"),"ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
                $url = "https://mikofi.com/api/send";
                $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
                $response = json_decode($response);
                if(isset($response->data->key->id)){$responseMsgID = $response->data->key->id;}else{$responseMsgID = "Mikofi_NotFoundID";}
                DB::table($customerDB)->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                // sent response 1|0
                if(isset($response->status) and $response->status == "success"){ return "1";}else{ return "0";}
            
            }elseif($validateServerMobile->integration_type == "6"){
                // official Facebook WhatsApp business account
                $messageInArray = ['body' => $message];
                $data = ['messaging_product' => 'whatsapp', 'recipient_type' => 'individual', 'to' => $to, 'type' => 'text', 'text' => $messageInArray];
                $msg = json_encode($data); // Encode data to JSON
                $arrContextOptions=array('http' => array('method' => 'POST', 'header' => "Authorization: Bearer $validateServerMobile->chatapi_instance_token\r\nContent-Type: application/json\r\n", 'content' => "$msg")); 
                $url = "https://graph.facebook.com/v14.0/".$validateServerMobile->chatapi_instance_id."/messages";
                $response = file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
                $response = json_decode($response);
                if(isset($response->messages[0]->id)){$responseMsgID = $response->messages[0]->id;}else{$responseMsgID = "official_NotFoundID";}
                DB::table($customerDB)->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                // sent response 1|0
                if(isset($response->messaging_product) and $response->messaging_product == "whatsapp"){ return "1";}else{ return "0";}
            }

        }


    }
    // most used in whatsapp master page
    public function sendWhatsapp($customerDB, $todayDateTime, $from, $to, $message, $campaign_id=null, $pending_survey_id=null, $sentMsgID=null){
        if( isset($to) and strlen($to)>8 ){
            include '../config.php';
            // SENDING
            $url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/whatsapp";
            // Get Token
            $whatsappTokenData = DB::table("whatsapp_token")->where('server_mobile', $from )->where('state','1')->first();
            if(isset($whatsappTokenData)){
             
                $serverMobileToken = $whatsappTokenData->token;

                //insert into there customer database
                if($sentMsgID==null or !isset($sentMsgID)){
                    // return "is null";
                    $sentMsgID = DB::table($customerDB.".whatsapp")->insertGetId([
                        'send_receive' => '0'
                        , 'server_mobile' => $from
                        , 'client_mobile'=> $to
                        , 'campaign_id' =>$campaign_id
                        , 'pending_survey_id' =>$pending_survey_id
                        , 'message' => $message
                        , 'created_at' => $todayDateTime]);
                }else{
                    // return "not null";
                }

                // check if this mobile integrated with Yowsup or chatAPI
                if($whatsappTokenData->integration_type == "1"){
                    // Yowsup
                    // to mark sent message to know it when sent successfully from WA server
                    sleep(1);
                    $msg = $serverMobileToken.','.$from.','.'message_sync_typing_send,'.$sentMsgID."@".$to."_".' "'.base64_encode($message).'" ';
                    // $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                    // return $response = @file_get_contents($url, FALSE, $context);
                    return $response = $this->sendingWithoutWaiting($url, $msg, $whatsapp_Srv1_OPsocketPort);
                }elseif( $whatsappTokenData->integration_type == "2" ){
                    // chat API
                    $data = ['phone' => $to,'body' => $message];
                    $msg = json_encode($data); // Encode data to JSON
                    $url = $whatsappTokenData->chatapi_instance_url."/sendMessage?token=".$whatsappTokenData->chatapi_instance_token;
                    $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                    $response = json_decode(@file_get_contents($url, FALSE, $context));
                    if(isset($response->id)){ $responseMsgID = $response->id; $return=1; }else{ $responseMsgID = "0"; $return=0;}
                    DB::table($customerDB.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                    return $return;
                    /* 
                    Alternative way to send images but it is too slow
                    // chat API
                    // check if message contaings link to send it as media
                    if( strpos($message, 'http') !== false ){ // this is file
                        // get the link and remove it from message 
                        foreach( explode(" ",$message) as $part){
                            if( strpos($part, 'http') !== false ){
                                $part = (strpos($part, '\r'))[0];
                                $message = @str_replace($part,"",$message); 
                                $link = $part; break; 
                            }
                        }
                        $data = ['phone' => $to, 'body' => $link, 'filename' => "Micro", 'caption' => $message];
                        $url = $whatsappTokenData->chatapi_instance_url."/sendFile?token=".$whatsappTokenData->chatapi_instance_token;
                    }else{ // this is text
                        $data = ['phone' => $to,'body' => $message];
                        $url = $whatsappTokenData->chatapi_instance_url."/sendMessage?token=".$whatsappTokenData->chatapi_instance_token;
                    }
                    $msg = json_encode($data); // Encode data to JSON
                    $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                    $response = json_decode(@file_get_contents($url, FALSE, $context));
                    if(isset($response->id)){ $responseMsgID = $response->id; $return=1; }else{ $responseMsgID = "0"; $return=0;}
                    DB::table($customerDB.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                    return $return;
                    */
                }elseif( $whatsappTokenData->integration_type == "3" ){
                    // from mercury.chat
                    $data = ['phone' => $to,'body' => $message];
                    $msg = json_encode($data); // Encode data to JSON
                    $url = $whatsappTokenData->chatapi_instance_url."/sendMessage?api_token=".$whatsappTokenData->chatapi_instance_token."&instance=".$whatsappTokenData->chatapi_instance_id;
                    $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                    $response = json_decode(@file_get_contents($url, FALSE, $context));
                    $responseMsgID = "0";
                    DB::table($customerDB.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                    if(isset($response->data->status_queued) and $response->data->status_queued == 1){ return "1";}else{ return "0";}
                }elseif($whatsappTokenData->integration_type == "4"){
                    // Telegram
                    // get Telegram ID
                    $telegramID = DB::table( $customerDB.".users" )->where('u_phone', $to)->value('telegram_id');
    
                    $data = ['chat_id' => $telegramID,'text' => $message];
                    $msg = json_encode($data); // Encode data to JSON
                    $url = "https://api.telegram.org/bot$whatsappTokenData->telegram_api_token/sendMessage";
                    DB::table($customerDB.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => rand(111111111,999999999), 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                    // return $response = $this->sendingWithoutWaiting($url, $msg); // not working with SSL
                    
                    $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
                    $response = @file_get_contents($url, FALSE, $context);
                    $response = json_decode($response);
                    // sent response 1|0
                    if(isset($response->ok) and $response->ok == "true"){ return "1";}else{ return "0";}
                
                }elseif( $whatsappTokenData->integration_type == "5" ){
                    // Mikofi
                    // OLD version
                    // $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
                    // $url = "https://www.mikofi.com/api/send.php?access_token=".$whatsappTokenData->chatapi_instance_token."&instance_id=".$whatsappTokenData->chatapi_instance_id."&type=text&number=".$to."&message=".urlencode($message);
                    
                    // New Version After New Update 31.07.2023 to be POST Request
                    $msg = json_encode(['number' => $to, 'message' => $message, 'type' => 'text', 'access_token' => $whatsappTokenData->chatapi_instance_token, 'instance_id' => $whatsappTokenData->chatapi_instance_id]); // Encode data to JSON
                    $arrContextOptions=array('http' => array('method' => 'POST', 'header' => "Content-Type: application/json\r\n", 'content' => "$msg"),"ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
                    $url = "https://mikofi.com/api/send";
                    $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
                    $response = json_decode($response);
                    if(isset($response->data->key->id)){$responseMsgID = $response->data->key->id;}else{$responseMsgID = "Mikofi_NotFoundID";}
                    DB::table($customerDB.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                    // sent response 1|0
                    if(isset($response->status) and $response->status == "success"){ return "1";}else{ return "0";}
                    
                }elseif( $whatsappTokenData->integration_type == "6" ){
                    // official Facebook WhatsApp business account
                    $messageInArray = ['body' => $message];
                    $data = ['messaging_product' => 'whatsapp', 'recipient_type' => 'individual', 'to' => $to, 'type' => 'text', 'text' => $messageInArray];
                    $msg = json_encode($data); // Encode data to JSON
                    $arrContextOptions=array('http' => array('method' => 'POST', 'header' => "Authorization: Bearer $whatsappTokenData->chatapi_instance_token\r\nContent-Type: application/json\r\n", 'content' => "$msg")); 
                    $url = "https://graph.facebook.com/v14.0/".$whatsappTokenData->chatapi_instance_id."/messages";
                    $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
                    $response = json_decode($response);
                    if(isset($response->messages[0]->id)){$responseMsgID = $response->messages[0]->id;}else{$responseMsgID = "official_NotFoundID";}
                    DB::table($customerDB.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => $responseMsgID, 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
                    // sent response 1|0
                    if(isset($response->messaging_product) and $response->messaging_product == "whatsapp"){ return "1";}else{ return "0";}
                }
            }
        }
    }
    
    // Used in whatsapp master page to set Whstsapp class to return message instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
    public function returnToDialogFlowChatAI($customerDB, $todayDateTime, $from, $to, $message, $campaign_id=null, $pending_survey_id=null, $sentMsgID=null){
        if( isset($to) and strlen($to)>8 ){
            
            //insert into there customer database
            if($sentMsgID==null or !isset($sentMsgID)){
                // return "is null";
                $sentMsgID = DB::table($customerDB.".whatsapp")->insertGetId([
                    'send_receive' => '0'
                    , 'msg_id' => rand(111111111,999999999)
                    , 'type' => 'text'
                    , 'sent' => '1'
                    , 'server_mobile' => $from
                    , 'client_mobile'=> $to
                    , 'campaign_id' =>$campaign_id
                    , 'pending_survey_id' =>$pending_survey_id
                    , 'message' => $message
                    , 'created_at' => $todayDateTime]);
            }else{
                DB::table($customerDB.".whatsapp")->where('id',$sentMsgID)->update(['msg_id' => rand(111111111,999999999), 'type' => 'text', 'send_receive' => '0', 'sent' => '1']);
            }

            return $message."\n";

        }
    }

    public function getAllCustomerInfoArray($customerDB,$userID,$todayDateTime,$adminPermission){

        $customerData = DB::table( $customerDB.".users" )->where('u_id', $userID)->first();
        // check if admin need to avoid WiFi login restriction (to uncover the customer mobile and email)
        $avoidWiFiWhenCallStaff = DB::table("$customerDB.settings")->where('type', 'avoidWiFiWhenCallStaff')->value('state');

        $customerInfo = array();
        $customerInfo['id'] = $customerData->u_id;
        $customerInfo['global_id']=DB::table('users_global')->where('mobile', 'like', '%'.$customerData->u_phone.'%')->value('id');
        $customerInfo['state']=$customerData->u_state;
        $customerInfo['suspend']=$customerData->suspend;
		$customerInfo['name'] = $customerData->u_name;
		if($customerData->u_gender == "0"){$customerInfo['gender'] = "Female";}
		elseif($customerData->u_gender == "1"){$customerInfo['gender'] = "‍‍‍Male";}
		else{$customerInfo['gender'] = "Unknown";}
        if(isset($customerData->u_uname)){$customerInfo['mobile'] = $customerData->u_uname;}
        else{$customerInfo['mobile'] = $customerData->u_phone;}
        $customerInfo['email'] = $customerData->u_email;
		$customerInfo['country'] = $customerData->u_country;
		$customerInfo['group'] = DB::table("$customerDB.area_groups")->where('id', $customerData->group_id)->value('name');
		$customerInfo['branch'] = DB::table("$customerDB.branches")->where('id', $customerData->branch_id)->value('name');
		$customerInfo['reg_date'] = $customerData->created_at;
		$allUsersSessions=DB::table("$customerDB.users_radacct")->select(DB::raw('* ,count(u_id) as visits'))->where('u_id',$customerData->u_id)->first();
		if(isset($allUsersSessions->visits)){$customerInfo['visits'] = $allUsersSessions->visits;}
		else{$customerInfo['visits'] = 0;}
		$lastVisit = DB::table("$customerDB.radacct")->where('u_id', $customerData->u_id)->orderBy('radacctid','desc')->first();
		if(isset($lastVisit->acctstarttime)){$customerInfo['last_visit'] = $lastVisit->acctstarttime;}
        else{$customerInfo['last_visit'] = "";}

        // Info related to hotels
        $customerInfo['username'] = $customerData->u_uname;
        $customerInfo['password'] = $customerData->u_password;
        $customerInfo['mac'] = $customerData->u_mac;
        $customerInfo['checkin'] = $userCheckIn = @explode(",",end(preg_split('/checkIn: /', $customerData->notes)))[0];
        $customerInfo['checkout'] = $userCheckOut = @explode(",",end(preg_split('/checkOut: /', $customerData->notes)))[0];
        if (strpos($customerData->notes, 'checkOut:') !== false and strpos($customerData->notes, 'checkIn:') !== false) {
            $checkin = strtotime($userCheckIn);
            $checkout = strtotime($userCheckOut);
            $datediff = $checkout - $checkin;
            $customerInfo['nights'] = round($datediff / (60 * 60 * 24));
        }else{$customerInfo['nights'] = "0";}
        if (strpos($customerData->notes, 'Guest birthday:') !== false ) {
            $dateOfBirth = @explode(",",end(preg_split('/Guest birthday:/', $customerData->notes)))[0];
            $customerInfo['age'] = $dateOfBirth;
            if($dateOfBirth!= "" and $dateOfBirth!=" -" and $dateOfBirth!="-"){
                $diff = date_diff(date_create($dateOfBirth), date_create(date("Y-m-d")));
                $customerInfo['age'].= ' Age is '.$diff->format('%y');
            }
        }else{$customerInfo['age']="";}

        $onlineSessions = DB::table("$customerDB.radacct")->where('u_id', $customerData->u_id)->whereNull('acctstoptime')->get();
        if( count($onlineSessions) > 0){
            $customerInfo['online'] = 1;
            $customerInfo['onlineSessions'] = $onlineSessions;
        }else{
            $customerInfo['online'] = 0;
        }
		$loyaltyPoints = $this->getCustomerLoyaltyPoints($customerDB, $userID, $todayDateTime);

        // get the first day of renwing day to get monthly usage in GB
        $gettingFirstAndLastDayInQuotaPeriod = $this->getFirstAndLastDayInQuotaPeriod ($customerDB, $customerData->branch_id);
        $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
        $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];    

        // Get Monthly Usage
        $today=date("Y-m-d");
        $monthlyUsageTotal=DB::table("$customerDB.radacct")->where('u_id',$customerData->u_id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum(DB::raw('acctinputoctets + acctoutputoctets'));
        $monthlyTotalUsage=round(($monthlyUsageTotal)/1024/1024/1024,2);
        $todayUsageTotal_bytes=DB::table("$customerDB.radacct")->where('u_id',$customerData->u_id)->where('dates',$today)->sum(DB::raw('acctinputoctets + acctoutputoctets'));
        $todayTotalUsage=round(($todayUsageTotal_bytes)/1024/1024/1024,2);
        $customerInfo['today_usage'] = "$todayTotalUsage GB";
        $todayUptime=DB::table("$customerDB.radacct")->where('u_id',$customerData->u_id)->where('dates',$today)->sum(DB::raw('acctsessiontime'));
        if( $todayUptime > 86400) { $todayUptimeUsage=gmdate("d",$todayUptime)-1; $todayUptimeUsage.='d '.gmdate("H:i:s", $todayUptime); }else { $todayUptimeUsage=gmdate("H:i:s",$todayUptime); }
        $customerInfo['today_uptime'] = $todayUptimeUsage;
        $customerInfo['monthly_usage'] = "$monthlyTotalUsage GB";
        $MonthlyUptime=DB::table("$customerDB.radacct")->where('u_id',$customerData->u_id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum(DB::raw('acctsessiontime'));
        if( $MonthlyUptime > 86400) { $MonthlyUptimeUsage=gmdate("d",$MonthlyUptime)-1; $MonthlyUptimeUsage.='d '.gmdate("H:i:s", $MonthlyUptime); }else { $MonthlyUptimeUsage=gmdate("H:i:s",$MonthlyUptime); }
        $customerInfo['monthly_uptime'] = $MonthlyUptimeUsage;
        // get remaining quota
        $lastRadacctRecord=DB::table("$customerDB.radacct")->where('u_id',$customerData->u_id)->orderBy('radacctid','desc')->first();
        if(isset($lastRadacctRecord->total_quota) and $lastRadacctRecord->total_quota!="" and $lastRadacctRecord->total_quota!="0" and $lastRadacctRecord->realm!='2done' and $lastRadacctRecord->realm!='2'){// there is a quota
            $customerInfo['remaining_quota'] = round(($lastRadacctRecord->total_quota-$todayUsageTotal_bytes)/1024/1024,1)."MB/".round($lastRadacctRecord->total_quota/1024/1024,1).'MB';
        }else{ // nt found quota
            $customerInfo['remaining_quota'] = "unlimited";
        }
        // get current speed
        $getCurrentSpeed = DB::table("$customerDB.radacct_active_users")->where('u_id',$customerData->u_id)->first();
        if(isset($getCurrentSpeed)){ // user is online
            if($getCurrentSpeed->speed_rate == "0bps/0bps" or $getCurrentSpeed->speed_rate == ""){$currentSpeed="0/0";}
            else{$currentSpeed = $getCurrentSpeed->speed_rate;}
        }else{// user not online
            $currentSpeed=0;
        }
        $customerInfo['current_speed'] = $currentSpeed;
        
        $currency = DB::table("$customerDB.settings")->where('type', 'currency')->value('value');
        $customerInfo['premiumInternetBills'] = round(DB::table( $customerDB.".pms_invoices" )->where('user_id', $userID)->sum('price'),1).$currency;
		$customerInfo['loyalty_points'] = $loyaltyPoints;
		$customerInfo['amount_of_bills'] = round(DB::table("$customerDB.loyalty_points")->where('u_id', $customerData->u_id)->where('type', '1')->sum('amount'),1).$currency;
		$customerInfo['count_of_bills'] = DB::table("$customerDB.loyalty_points")->where('u_id', $customerData->u_id)->where('type', '1')->count();
        $customerInfo['amount_of_refunds'] = round(DB::table("$customerDB.loyalty_points")->where('u_id', $customerData->u_id)->where('type', '0')->sum('amount'),1).$currency;
		$customerInfo['count_of_refunds'] = DB::table("$customerDB.loyalty_points")->where('u_id', $customerData->u_id)->where('type', '0')->count();
        $customerInfo['count_of_loyalty_redeems'] = DB::table("$customerDB.loyalty_points")->where('u_id', $customerData->u_id)->where('type', '2')->count();
        
		return $customerInfo;
    }
    
    public function getAllCustomerInfoToAdmin($customerDB,$userID,$todayDateTime,$adminPermission){

        $allCustomerData = $this->getAllCustomerInfoArray($customerDB, $userID, $todayDateTime, $adminPermission);

        $customerData = DB::table( $customerDB.".users" )->where('u_id', $userID)->first();
        // check if admin need to avoid WiFi login restriction (to uncover the customer mobile and email)
        $avoidWiFiWhenCallStaff = DB::table("$customerDB.settings")->where('type', 'avoidWiFiWhenCallStaff')->value('state');

		$returnReplyMsg="👇 Customer Info 👇\n";
		$returnReplyMsg.= "🔘 ID: ".$allCustomerData['global_id']." \n";
		$returnReplyMsg.= "👤 Name: ".$allCustomerData['name']." \n";
		// if($allCustomerData['gender'] == "Female"){$returnReplyMsg.= "🎭 Gender: 👩‍🦰 Female \n";}
		// elseif($allCustomerData['gender'] == "Male"){$returnReplyMsg.= "🎭 Gender: ‍‍‍👨‍🦰 Male \n";}
        // else{$returnReplyMsg.= "🎭 Gender: ‍‍‍Unknown \n";}
        $returnReplyMsg.= "🎭 Gender: ".$allCustomerData['gender']." \n";
		if($adminPermission == 1 or $avoidWiFiWhenCallStaff == 1){
			if(isset($customerData->u_uname)){$returnReplyMsg.= "📞 Mobile: $customerData->u_uname \n";}
            else{$returnReplyMsg.= "📞 Mobile: ".$allCustomerData['mobile']." \n";}
            $returnReplyMsg.= "💬 WhatsApp: https://wa.me/".$customerData->u_phone." \n";
			$returnReplyMsg.= "✉️ E-Mail: ".$allCustomerData['email']."\n";
		}else{
			$returnReplyMsg.= "📞 Mobile: ********".substr($allCustomerData['mobile'],-3)." \n";
			$returnReplyMsg.= "✉️ E-Mail: ".substr($allCustomerData['email'],0,4)."******.***\n";
		}
		$returnReplyMsg.= "🏁 Country: ".$allCustomerData['country']."\n";
		$returnReplyMsg.= "🚀 Group: ".$allCustomerData['group']."\n";
		$returnReplyMsg.= "🏢 Reg branch: ".$allCustomerData['branch']."\n";
		$returnReplyMsg.= "🎯 Reg date: ".$allCustomerData['reg_date']."\n";
		$returnReplyMsg.= "👣 Visits: ".$allCustomerData['visits']." \n";
        $returnReplyMsg.= "👁️ Last Visit: ".$allCustomerData['last_visit']."\n";
        $onlineState = $allCustomerData['online'] == 1? 'Yes' : 'No';
        if( $allCustomerData['online'] == "1"){
           $returnReplyMsg.= "👁️ Online NOW: ".$onlineState."\n";
           $returnReplyMsg.= "👁️ Remaining Quota: ".$allCustomerData['remaining_quota']."\n";
           $returnReplyMsg.= "👁️ Current Speed: ".$allCustomerData['current_speed']."\n";
        }
		
        // Internet Usage
        $returnReplyMsg.= "⏳ Today Usage: ".$allCustomerData['today_usage']."\n";
        $returnReplyMsg.= "⏳ Monthly Usage: ".$allCustomerData['monthly_usage']."\n";
        $returnReplyMsg.= "⏰ Today Uptime: ".$allCustomerData['today_uptime']."\n";
        $returnReplyMsg.= "⏰ Monthly Uptime: ".$allCustomerData['monthly_uptime']."\n";

        // Loyalty 
        $returnReplyMsg.= "💵 Amount of bills: ".$allCustomerData['amount_of_bills']."\n";
        $returnReplyMsg.= "💵 Count of bills: ".$allCustomerData['count_of_bills']."\n";
        $returnReplyMsg.= "💸 Amount of refunds: ".$allCustomerData['amount_of_refunds']."\n";
        $returnReplyMsg.= "💸 Count of refunds: ".$allCustomerData['count_of_refunds']."\n";
        $returnReplyMsg.= "🏆 Loyalty Points: ".$allCustomerData['loyalty_points']." \n";
        $returnReplyMsg.= "🔄 Count of loyalty redeems: ".$allCustomerData['count_of_loyalty_redeems']."\n";
		return $returnReplyMsg;
	}

    public function getAllHotelGuestInfoToAdmin($customerDB,$userID,$todayDateTime,$adminPermission){

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");

        $allCustomerData = $this->getAllCustomerInfoArray($customerDB, $userID, $todayDateTime, $adminPermission);

        $customerData = DB::table( $customerDB.".users" )->where('u_id', $userID)->first();
        // check if admin need to avoid WiFi login restriction (to uncover the customer mobile and email)
        $avoidWiFiWhenCallStaff = DB::table("$customerDB.settings")->where('type', 'avoidWiFiWhenCallStaff')->value('state');

		$returnReplyMsg="👇 Guest Info 👇\n";
		// $returnReplyMsg.= "🔘 ID: ".$allCustomerData['global_id']." \n";
		$returnReplyMsg.= "👤 Name: ".$allCustomerData['name']." \n";
        $returnReplyMsg.= "🚪 Username: ".$allCustomerData['username']." \n";
        $returnReplyMsg.= "🗝️ Password: ".$allCustomerData['password']." \n";
        $returnReplyMsg.= "🚀 Group: ".$allCustomerData['group']."\n";
		$returnReplyMsg.= "🏢 Hotel: ".$allCustomerData['branch']."\n";
        $returnReplyMsg.= "🏁 Check-In: ".$allCustomerData['checkin']."\n";
		$returnReplyMsg.= "🛫 Check-Out: ".$allCustomerData['checkout']."\n";
        $returnReplyMsg.= "🛌 Nights: ".$allCustomerData['nights']."\n";
        $returnReplyMsg.= "🎂 Age: ".$allCustomerData['age']."\n";
		// if($allCustomerData['gender'] == "Female"){$returnReplyMsg.= "🎭 Gender: 👩‍🦰 Female \n";}
		// elseif($allCustomerData['gender'] == "Male"){$returnReplyMsg.= "🎭 Gender: ‍‍‍👨‍🦰 Male \n";}
        // else{$returnReplyMsg.= "🎭 Gender: ‍‍‍Unknown \n";}
        $returnReplyMsg.= "🎭 Gender: ".$allCustomerData['gender']." \n";
		if($adminPermission == 1 or $avoidWiFiWhenCallStaff == 1){
			if(isset($customerData->u_uname)){$returnReplyMsg.= "📞 Mobile: $customerData->u_uname \n";}
            else{$returnReplyMsg.= "📞 Mobile: ".$allCustomerData['mobile']." \n";}
            $returnReplyMsg.= "💬 WhatsApp: https://wa.me/".$customerData->u_phone." \n";
			$returnReplyMsg.= "✉️ E-Mail: ".$allCustomerData['email']."\n";
		}else{
			$returnReplyMsg.= "📞 Mobile: ********".substr($allCustomerData['mobile'],-3)." \n";
			$returnReplyMsg.= "✉️ E-Mail: ".substr($allCustomerData['email'],0,4)."******.***\n";
		}
		$returnReplyMsg.= "🏁 Country: ".$allCustomerData['country']."\n";
		$returnReplyMsg.= "🎯 Reg date: ".$allCustomerData['reg_date']."\n";
		$returnReplyMsg.= "👣 Visits: ".$allCustomerData['visits']." \n";
        $returnReplyMsg.= "👁️ Last Visit: ".$allCustomerData['last_visit']."\n";
        
        // $returnReplyMsg.="👇 Internet Info 👇\n";
        $returnReplyMsg.= "📱 Mac: ".$allCustomerData['mac']."\n";
        $onlineState = $allCustomerData['online'] == 1? 'Yes' : 'No';
        if( $allCustomerData['online'] == "1"){
           $returnReplyMsg.= "👁️ Online NOW: ".$onlineState."\n";
           $returnReplyMsg.= "👁️ Online devices: ".count($allCustomerData['onlineSessions'])."\n";
           $sessionCounter = 0;
           foreach( $allCustomerData['onlineSessions'] as $session){
                $sessionCounter++;
                $sessionConsumption = @round(($session->acctinputoctets + $session->acctoutputoctets)/1024/1024,1)." MB";
                $sessionUptime = gmdate('H:i:s', Carbon::parse($todayDateTime)->diffInSeconds(Carbon::parse($session->acctstarttime))); // Output: 00:30:00
                $returnReplyMsg.= "📁 Session $sessionCounter started at: ".$session->acctstarttime.", Mac: ".$session->callingstationid.", IP: ".$session->framedipaddress.", Uptime: ".$sessionUptime.", Usage: ".$sessionConsumption.""."\n";
           }
           $returnReplyMsg.= "👁️ Remaining Quota: ".$allCustomerData['remaining_quota']."\n";
           $returnReplyMsg.= "👁️ Current Speed: ".$allCustomerData['current_speed']."\n";
        }else{
            $returnReplyMsg.= "👁️ Online NOW: ".$onlineState."\n";
        }
		
        // Internet Usage
        $returnReplyMsg.= "⏳ Today Usage: ".$allCustomerData['today_usage']."\n";
        $returnReplyMsg.= "⏳ Monthly Usage: ".$allCustomerData['monthly_usage']."\n";
        $returnReplyMsg.= "⏰ Today Uptime: ".$allCustomerData['today_uptime']."\n";
        $returnReplyMsg.= "⏰ Monthly Uptime: ".$allCustomerData['monthly_uptime']."\n";

        // $returnReplyMsg.="👇 Billing Info 👇\n";
        $returnReplyMsg.= "💵 Premium internet bills: ".$allCustomerData['premiumInternetBills']."\n";
        $returnReplyMsg.= "💵 Amount of other bills: ".$allCustomerData['amount_of_bills']."\n";
        $returnReplyMsg.= "💵 Count of other bills: ".$allCustomerData['count_of_bills']."\n";
        $returnReplyMsg.= "💸 Amount of refunds: ".$allCustomerData['amount_of_refunds']."\n";
        $returnReplyMsg.= "💸 Count of refunds: ".$allCustomerData['count_of_refunds']."\n";
        // Loyalty 
        // $returnReplyMsg.="👇 Loyalty Info 👇\n";
        $returnReplyMsg.= "🏆 Loyalty Points: ".$allCustomerData['loyalty_points']." \n";
        $returnReplyMsg.= "🔄 Count of loyalty redeems: ".$allCustomerData['count_of_loyalty_redeems']."\n";
		return $returnReplyMsg;
	}

	public function getCustomerLoyaltyPoints($customerDB,$userID,$todayDateTime){
		// get loyalty points
		$pointsExpireAfterDays = DB::table("$customerDB.settings")->where('type', 'loyaltyPointsExpireAfterDays')->value('value');
		if(isset($pointsExpireAfterDays) and $pointsExpireAfterDays!="0"){
			// get all user points and add days to it
			$loyaltyPoints = 0;
			foreach(DB::table("$customerDB.loyalty_points")->where('state', '1')->where('type', '1')->where('u_id', $userID)->get() as $point){
				$addExpirationDays = date('Y-m-d H:i:s', strtotime("+$pointsExpireAfterDays days", strtotime($point->created_at)));
				if($addExpirationDays >= $todayDateTime){ $loyaltyPoints = $loyaltyPoints + $point->points;}
			}
		}else{
			// get all user points
			$loyaltyPoints = 0;
			foreach(DB::table("$customerDB.loyalty_points")->where('state', '1')->where('type', '1')->where('u_id', $userID)->get() as $point){
			$loyaltyPoints = $loyaltyPoints + $point->points;
			}
		}
		// sub refund and redeemed points
		$totalRefund = DB::table("$customerDB.loyalty_points")->where('u_id', $userID)->where('state', '1')->where('type', '0')->sum('points');
		$loyaltyPoints = $loyaltyPoints - $totalRefund;
		$totalRedeem = DB::table("$customerDB.loyalty_points")->where('u_id', $userID)->where('state', '1')->where('type', '2')->sum('points');
		$loyaltyPoints = $loyaltyPoints - $totalRedeem;
		// deactivate all points to start from zero
		if($loyaltyPoints<0){ $loyaltyPoints=0; DB::table($customerDB.".loyalty_points")->where( 'u_id', $userID )->where('state', '1')->update(['state' => '0', 'updated_at' => $todayDateTime]); }
		
		return $loyaltyPoints;
	}
	
	// response "all", "available", "buildWaitingAdminResponseMenu"
	public function getAllAndAvilableLoyaltyProgram($customerDB, $userID, $todayDateTime){
		
		// get avilable loyalty program
		$counter4LoyaltyProgram = 1;
		$availableLoyaltyPrograms = null;
		$allLoyaltyPrograms = null;
		$buildWaitingAdminResponseMenu = null;
		$userLoyaltyPoints = $this->getCustomerLoyaltyPoints($customerDB, $userID, $todayDateTime);

		foreach(DB::table( $customerDB.".loyalty_program" )->where( 'state','1')->where( 'row_type','1')->get() as $loyaltyProgram){
			
			// buildWaitingAdminResponseMenu
			if($counter4LoyaltyProgram == 1){ $comma = "";}else{$comma = ",";}
			$buildWaitingAdminResponseMenu.=$comma.$counter4LoyaltyProgram.":"."redeemLoyaltyProgram-$loyaltyProgram->id-$userID";
			// set All loyality programs
            $allLoyaltyPrograms.= "🎁No.$counter4LoyaltyProgram: $loyaltyProgram->whatsapp \n🔘Points: $loyaltyProgram->points \n";
            // set available loyalty program
			if($userLoyaltyPoints >= $loyaltyProgram->points){ $availableLoyaltyPrograms.="🎁No.$counter4LoyaltyProgram: $loyaltyProgram->whatsapp \n🔘Points: $loyaltyProgram->points \n"; }
			
			// check if we will get loyalty Program Items
			if($loyaltyProgram->type == "1" or $loyaltyProgram->type == "2" or $loyaltyProgram->type == "3"){
				
				if($loyaltyProgram->type == "3"){ 
                    // set All loyality programs
                    $allLoyaltyPrograms.= "🔘Depends On: $loyaltyProgram->depends_on_item_name \n"; 
                    // set available loyalty program
					if($userLoyaltyPoints >= $loyaltyProgram->points){$availableLoyaltyPrograms.= "🔘Depends On: $loyaltyProgram->depends_on_item_name \n"; }
				}
				// not used for now because we mention it in reply message
				// foreach(DB::table( $customerDB.".loyalty_program_items" )->where( 'loyalty_program_id', $loyaltyProgram->id)->get() as $loyaltyProgramItem){
				// 	$allLoyaltyPrograms.= "🔘Item: $loyaltyProgramItem->item_name \n";
				// 	$rowOfLoyaltyProgram.= "🔘Item: $loyaltyProgramItem->item_name \n";
				// }
			}
			$counter4LoyaltyProgram++;
		}
		
		$return['all']=$allLoyaltyPrograms;
		$return['available']=$availableLoyaltyPrograms;
		$return['buildWaitingAdminResponseMenu']=$buildWaitingAdminResponseMenu;
		return $return;
	}
	
	public function pay($microsystemORenduser, $systemID, $systemName, $customerID=null, $customerMobile, $customerEmail, $amount, $currency, $fawry=null, $visa=null, $wallet=null, $orderNotes=null)
    {   
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        $currentHour = date("H");
        $systemData =  DB::table('customers')->where('id',$systemID)->where('state','1')->first();

        // check if we need to convert USD amount to EGP
        if($currency == "USD->EGP"){
            // set currency for next actions
            $currency="EGP"; 
            // connect to openexchangerates.org to get latest exchenge rate
            $openexchangeratesURL = 'https://openexchangerates.org/api/latest.json?app_id='.DB::table("$systemData->database.settings")->where('type', 'openexchangerates.org_app_id')->value('value');
            $rawdataOfAllRates = file_get_contents($openexchangeratesURL, false);
            $rawdataOfAllRates = json_decode($rawdataOfAllRates);
            // convert to USD to EGP rate
            $amount = round($amount * $rawdataOfAllRates->rates->EGP,2);
        }

        // Credentials setup
        if($microsystemORenduser=="enduser"){
            $customerData = DB::table( $systemData->database.".users" )->where('u_id', $customerID)->first();
            $customerName = $customerData->u_name;
            $fawryState = DB::table("$systemData->database.settings")->where('type', 'fawryState')->value('state');
            $visaState = DB::table("$systemData->database.settings")->where('type', 'visaState')->value('state');
            $walletState = DB::table("$systemData->database.settings")->where('type', 'walletState')->value('state');
            // for WeAccept Visa and Wallet 
            if($currency == "USD"){
                $merchantOrderIDStartFrom = "900000";
            }else{
                $merchantOrderIDStartFrom = "9000";
            }
        }elseif($microsystemORenduser == "directCharge"){
            // require_once '../config.php';
            $customerName = $systemName;
            $fawryState = "0";
            $visaState = "1";
            $walletState = "0";
            
            // for WeAccept Visa and Wallet 
            if($currency == "USD"){
                $merchantOrderIDStartFrom = "900000";
            }else{
                $merchantOrderIDStartFrom = "9000";
            }
        }
        else{
            require_once '../config.php';
            $customerName = $systemName;
            $fawryState = "1";
            $visaState = "1";
            $walletState = "1";
            $fawryPaymentExpiryDays = "7";
            $weacceptUsername = $username;
            $weacceptPassword = $password;
            $weacceptIframeID = $iframe_id;
            // for WeAccept Visa and Wallet 
            if($currency == "USD"){
                $merchantOrderIDStartFrom = "100000";
            }else{
                $merchantOrderIDStartFrom = "1000";
            }
        }
        
        
        if( isset($systemData) ){

            if($fawry == "1" and $fawryState!="0"){
                
                ///// Fawry Direct Integration Server2Server /////
                if($microsystemORenduser=="enduser"){
                    if($fawryState == "1"){// live
                        $fawryIntegrationUrl = "https://www.atfawry.com/ECommerceWeb/Fawry/payments/charge";
                        $fawryMerchantCode = DB::table("$systemData->database.settings")->where('type', 'fawryMerchantCodeLive')->value('value'); // LIVE
                        $fawrySecurityKey = DB::table("$systemData->database.settings")->where('type', 'fawrySecurityKeyLive')->value('value'); // LIVE
                        $fawryPaymentExpiryDays = DB::table("$systemData->database.settings")->where('type', 'fawryPaymentExpiryDays')->value('value');
                    }else{ // test
                        $fawryIntegrationUrl = "https://atfawry.fawrystaging.com//ECommerceWeb/Fawry/payments/charge";
                        $fawryMerchantCode = DB::table("$systemData->database.settings")->where('type', 'fawryMerchantCodeTest')->value('value'); // test
                        $fawrySecurityKey = DB::table("$systemData->database.settings")->where('type', 'fawrySecurityKeyTest')->value('value'); // test
                        $fawryPaymentExpiryDays = DB::table("$systemData->database.settings")->where('type', 'fawryPaymentExpiryDays')->value('value');
                    }
                }

                // 1 - insert payment record to get unique code of merchantOrderID
                if($microsystemORenduser=="enduser"){
                    $merchantOrderID = DB::table('end_users_payment')->insertGetId(array('customer_id' => $systemData->id, 'local_user_id' => $customerID, 'amount' => $amount, 'payment_method' => 'fawry', 'order_notes' => $orderNotes, 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }else{
                    $merchantOrderID = DB::table('payment')->insertGetId(array('customer_id' => $systemData->id, 'amount' => $amount, 'payment_method' => 'fawry', 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }
                // 2 - sending fawry Json request
                $merchantRefNum = $merchantOrderID; // must be unique
                $customerProfileId = $systemData->id; // must be unique
                $finalAmount = $amount.'.00'; // must be dicimal ex(20.00)
                $hashVar = $fawryMerchantCode.$merchantRefNum.$customerProfileId."PAYATFAWRY".$finalAmount.$fawrySecurityKey;
                $finalHashCode = hash('sha256', $hashVar);
                $data = '
                {
                    "merchantCode":"'.$fawryMerchantCode.'",
                    "merchantRefNum":"'.$merchantRefNum.'",
                    "customerProfileId":"'.$customerProfileId.'",
                    "customerMobile":"'.$customerMobile.'",
                    "customerEmail":"'.$customerEmail.'",
                    "paymentMethod":"PAYATFAWRY",
                    "amount":'.$finalAmount.',
                    "currencyCode":"'.$currency.'",
                    "description":"'.$systemName.'",
                    "paymentExpiry":'.strtotime("+$fawryPaymentExpiryDays day").'077,
                    "chargeItems":[
                       {
                          "itemId":"897fa8e81be26df25db592e81c31c",
                          "description":"'.$customerName.'",
                          "price":'.$finalAmount.',
                          "quantity":1
                       }
                    ],
                    "signature":"'.$finalHashCode.'"
                }
                ';
        
                // SENDING 
                $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$data")));
                $response = @file_get_contents($fawryIntegrationUrl, FALSE, $context);
        
                // check if sending done succesfully
                if(isset($response)){
                    $responseJson = json_decode($response);
                    if(isset($responseJson)){
                        if($responseJson->statusCode == "200"){
                            $referenceNumber = $responseJson->referenceNumber;
                            if(isset($referenceNumber) and $microsystemORenduser=="enduser"){ DB::table('end_users_payment')->where('id',$merchantOrderID)->update(['fawry_code' => $referenceNumber]);}
                        }
                    }
                }
                
            }
            
            if( $visa == "1" and $visaState!="0" ){

                //// WeAccept  /////
                if($microsystemORenduser=="enduser" or $microsystemORenduser == "directCharge"){
                    $weacceptUsername = DB::table("$systemData->database.settings")->where('type', 'weacceptUsername')->value('value');
                    $weacceptPassword = DB::table("$systemData->database.settings")->where('type', 'weacceptPassword')->value('value');
                    $weacceptIframeID = DB::table("$systemData->database.settings")->where('type', 'weacceptIframeID')->value('value');
                    
                    // get integration ID
                    if($visaState == "1"){// live
                        if($currency=="USD"){
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardUSDlive')->value('value'); // LIVE USD
                        }else{ // EGP
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardEGPlive')->value('value'); // LIVE EGP
                        }
                    }else{ // test
                        if($currency=="USD"){
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardUSDtest')->value('value'); // test USD    
                        }else{
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardEGPtest')->value('value'); // test EGP
                        }
                    }
                }else{
                    if($currency=="USD"){
                        $weacceptIntegrationID = $integration_id4cardUSD;
                    }
                    else{ // EGP
                        $weacceptIntegrationID = $integration_id4cardEGP;
                    }
                }
                $finalAmount = $amount * 100; // to convert amount into cents
                
                // 1 - insert payment record to get unique code of merchantOrderID
                if($microsystemORenduser=="enduser"){
                    $merchantOrderID = DB::table('end_users_payment')->insertGetId(array('customer_id' => $systemData->id, 'local_user_id' => $customerID, 'amount' => $amount, 'payment_method' => 'card', 'mobile' => $customerMobile, 'order_notes' => $orderNotes, 'created_at' => $created_at ));
                }
                elseif($microsystemORenduser == "directCharge"){
                    $merchantOrderID = DB::table('end_users_payment')->insertGetId(array('customer_id' => $systemData->id, 'local_user_id' => '0', 'amount' => $amount, 'payment_method' => 'card', 'mobile' => $customerMobile, 'order_notes' => $orderNotes, 'created_at' => $created_at )); 
                }
                else{
                    $merchantOrderID = DB::table('payment')->insertGetId(array('customer_id' => $systemData->id, 'amount' => $amount, 'payment_method' => 'card', 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }
                $merchantOrderIDfinal = $merchantOrderID+$merchantOrderIDStartFrom;
                
                // validation
                $firstName = $customerName;
                if(!isset( $firstName ) or $firstName == ""){ $firstName = "Microsystem"; }
                $lastName = $systemName; 
                if(!isset( $lastName ) or $lastName == ""){ $lastName = " "; }
                $email = $customerEmail;
                if(!isset( $email ) or $email == "" or $email == " " or strpos($email, '@') === false){ $email = DB::table("$systemData->database.settings")->where('type','email')->value('value'); }
                $street = DB::table("$systemData->database.settings")->where('type', 'address')->value('value');
                if(!isset( $street ) or $street == ""){ $street = "Master st"; }
                $phone_number = $customerMobile;
                if(!isset( $phone_number ) or $phone_number == ""){ $phone_number = "201145929570"; }
                $country = DB::table("$systemData->database.settings")->where('type', 'country')->value('value');
                if(!isset( $country ) or $country == ""){ $country = "Egypt"; }


                $userData = array(
                    "apartment"=> "0", 
                    "email"=> $email, 
                    "first_name"=> $firstName,
                    "last_name"=> $lastName,
                    "floor"=> "0",  
                    "street"=> $street, 
                    "building"=> "0", 
                    "phone_number"=> $phone_number, 
                    "postal_code"=> "0", 
                    "city"=> "Cairo", 
                    "country"=> $country,  
                    "state"=> "Cairo",
                    "shipping_method"=> "PKG"
                );

                
                // step 1
                // The data to send to the API
                $postData = array(
                    'username' => $weacceptUsername,
                    'password' => $weacceptPassword,
                    'expiration' => '36000'
                );

                // Create the context for the request
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData)
                    )
                ));

                // Send the request
                $response = file_get_contents('https://accept.paymobsolutions.com/api/auth/tokens', FALSE, $context);

                // Check for errors
                if($response === FALSE){
                    die('Error');
                }

                // Decode the response
                $responseData = json_decode($response, TRUE);

                // Print the date from the response
                $tokenFromStep1 = $responseData['token'];
                $merchantIDFromStep1 = $responseData['profile']['id'];

                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                ////////////                                    step 2                                   //////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////

                // The data to send to the API
                $postData2 = array(
                    "delivery_needed"=> "false",
                    "merchant_id"=> "$merchantIDFromStep1",
                    "amount_cents"=> "$finalAmount",
                    "currency"=> "$currency",
                    "merchant_order_id"=> "$merchantOrderIDfinal"
                );
                $postData2['items'] = array(
                );
                $postData2['shipping_data'] = $userData;
                
                // Create the context for the request
                $context2 = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData2)
                    )
                ));

                // Send the request
                $response2 = file_get_contents("https://accept.paymobsolutions.com/api/ecommerce/orders?token=$tokenFromStep1", FALSE, $context2);

                // Check for errors
                if($response2 === FALSE){
                    die('Error');
                }

                // Decode the response2
                $response2Data = json_decode($response2, TRUE);

                // Print the date from the response2
                $orderIDFromStep2 = $response2Data['id'];
                //if(isset($response2Data['url'])) {$orderUrl = $response2Data['url'];} // existed only in case Type=card not fount in Type=wallet
                
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                ////////////                                    step 3                                   //////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                
                // The data to send to the API
                $postData3 = array(
                    "amount_cents"=> "$finalAmount",
                    "expiration"=> "36000",
                    "order_id"=> "$orderIDFromStep2",
                    "currency"=> "$currency", 
                    "integration_id"=> "$weacceptIntegrationID"
                );
                $postData3['billing_data'] = $userData;
                
                // Create the context for the request
                $context3 = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData3)
                    )
                ));

                // Send the request
                $response3 = file_get_contents("https://accept.paymobsolutions.com/api/acceptance/payment_keys?token=$tokenFromStep1", FALSE, $context3);

                // Check for errors
                if($response3 === FALSE){
                    die('Error');
                }

                // Decode the response2
                $response3Data = json_decode($response3, TRUE);

                // Print the date from the response2
                $finalTokenFromStep3 = $response3Data['token'];

                if($visa=="1")
                {
                    $iframe = "https://accept.paymobsolutions.com/api/acceptance/iframes/$weacceptIframeID?payment_token=$finalTokenFromStep3";
                    if(isset($iframe)){ 
                        $iframe=$this->linkShortener($systemData->url,$iframe);
                        if($microsystemORenduser=="enduser"){DB::table('end_users_payment')->where('id',$merchantOrderID)->update(['visa_link' => $iframe]);}
                    }
                }

            }
            
            if( $wallet == "1" and $walletState!="0" ){

                //// WeAccept  /////
                if($microsystemORenduser=="enduser"){
                    $weacceptUsername = DB::table("$systemData->database.settings")->where('type', 'weacceptUsername')->value('value');
                    $weacceptPassword = DB::table("$systemData->database.settings")->where('type', 'weacceptPassword')->value('value');
                    $weacceptIframeID = DB::table("$systemData->database.settings")->where('type', 'weacceptIframeID')->value('value');

                    // get integration ID
                    if($visaState == "1"){// live
                        $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptWalletLive')->value('value'); // LIVE EGP
                    }else{ // test
                        $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptWalletTest')->value('value'); // test EGP
                    }
                }else{
                    $weacceptIntegrationID = $integration_id4wallet;
                }

                $finalAmount = $amount * 100; // to convert amount into cents
                
                // 1 - insert payment record to get unique code of merchantOrderID
                if($microsystemORenduser=="enduser"){
                    $merchantOrderID = DB::table('end_users_payment')->insertGetId(array('customer_id' => $systemData->id, 'local_user_id' => $customerID, 'amount' => $amount, 'payment_method' => 'wallet', 'mobile' => $customerMobile, 'order_notes' => $orderNotes, 'created_at' => $created_at ));
                }else{
                    $merchantOrderID = DB::table('payment')->insertGetId(array('customer_id' => $systemData->id, 'amount' => $amount, 'payment_method' => 'wallet', 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }
                $merchantOrderIDfinal = $merchantOrderID+$merchantOrderIDStartFrom;
                
                // validation
                $firstName = $customerName;
                if(!isset( $firstName ) or $firstName == ""){ $firstName = "Microsystem"; }
                $lastName = $systemName; 
                if(!isset( $lastName ) or $lastName == ""){ $lastName = " "; }
                $email = $customerEmail;
                if(!isset( $email ) or $email == "" or $email == " " or strpos($email, '@') === false){ $email = "support@microsystem.com.eg"; }
                $street = DB::table("$systemData->database.settings")->where('type', 'address')->value('value');
                if(!isset( $street ) or $street == ""){ $street = "Master st"; }
                $phone_number = $customerMobile;
                if(!isset( $phone_number ) or $phone_number == ""){ $phone_number = "201145929570"; }
                $country = DB::table("$systemData->database.settings")->where('type', 'country')->value('value');
                if(!isset( $country ) or $country == ""){ $country = "Egypt"; }


                $userData = array(
                    "apartment"=> "0", 
                    "email"=> $email, 
                    "first_name"=> $firstName,
                    "last_name"=> $lastName,
                    "floor"=> "0",  
                    "street"=> $street, 
                    "building"=> "0", 
                    "phone_number"=> $phone_number, 
                    "postal_code"=> "0", 
                    "city"=> "Cairo", 
                    "country"=> $country,  
                    "state"=> "Cairo",
                    "shipping_method"=> "PKG"
                );

                
                // step 1
                // The data to send to the API
                $postData = array(
                    'username' => $weacceptUsername,
                    'password' => $weacceptPassword,
                    'expiration' => '36000'
                );

                // Create the context for the request
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData)
                    )
                ));

                // Send the request
                $response = file_get_contents('https://accept.paymobsolutions.com/api/auth/tokens', FALSE, $context);

                // Check for errors
                if($response === FALSE){
                    die('Error');
                }

                // Decode the response
                $responseData = json_decode($response, TRUE);

                // Print the date from the response
                $tokenFromStep1 = $responseData['token'];
                $merchantIDFromStep1 = $responseData['profile']['id'];

                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                ////////////                                    step 2                                   //////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////

                // The data to send to the API
                $postData2 = array(
                    "delivery_needed"=> "false",
                    "merchant_id"=> "$merchantIDFromStep1",
                    "amount_cents"=> "$finalAmount",
                    "currency"=> "$currency",
                    "merchant_order_id"=> "$merchantOrderIDfinal"
                );
                $postData2['items'] = array(
                );
                $postData2['shipping_data'] = $userData;
                
                // Create the context for the request
                $context2 = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData2)
                    )
                ));

                // Send the request
                $response2 = file_get_contents("https://accept.paymobsolutions.com/api/ecommerce/orders?token=$tokenFromStep1", FALSE, $context2);

                // Check for errors
                if($response2 === FALSE){
                    die('Error');
                }

                // Decode the response2
                $response2Data = json_decode($response2, TRUE);

                // Print the date from the response2
                $orderIDFromStep2 = $response2Data['id'];
                //if(isset($response2Data['url'])) {$orderUrl = $response2Data['url'];} // existed only in case Type=card not fount in Type=wallet
                
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                ////////////                                    step 3                                   //////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                
                // The data to send to the API
                $postData3 = array(
                    "amount_cents"=> "$finalAmount",
                    "expiration"=> "36000",
                    "order_id"=> "$orderIDFromStep2",
                    "currency"=> "$currency", 
                    "integration_id"=> "$weacceptIntegrationID"
                );
                $postData3['billing_data'] = $userData;
                
                // Create the context for the request
                $context3 = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData3)
                    )
                ));

                // Send the request
                $response3 = file_get_contents("https://accept.paymobsolutions.com/api/acceptance/payment_keys?token=$tokenFromStep1", FALSE, $context3);

                // Check for errors
                if($response3 === FALSE){
                    die('Error');
                }

                // Decode the response2
                $response3Data = json_decode($response3, TRUE);

                // Print the date from the response2
                $finalTokenFromStep3 = $response3Data['token'];

                if($wallet=="1")
                {
                    ////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////                   step 4 in case type = wallet                       //////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////
                    
                    // The data to send to the API
                    $postData4 = array(
                        "payment_token"=> "$finalTokenFromStep3"
                    );

                    $postData4['source'] = array(
                        "identifier"=> "$customerMobile",
                        "subtype"=> "WALLET"
                    );
                    
                    $postData4['billing'] = $userData;
                    
                    // Create the context for the request
                    $context4 = stream_context_create(array(
                        'http' => array(
                            'method' => 'POST',
                            'header' => "Content-Type: application/json\r\n",
                            'content' => json_encode($postData4)
                        )
                    ));

                    // Send the request
                    $response4 = file_get_contents("https://accept.paymobsolutions.com/api/acceptance/payments/pay", FALSE, $context4);

                    // Check for errors
                    if($response4 === FALSE){
                        die('Error');
                    }

                    // Decode the response2
                    $response4Data = json_decode($response4, TRUE);

                    // Print the date from the response2
                    $redirectURL = $response4Data['redirect_url'];
                    if(isset($redirectURL)){ 
                        $redirectURL=$this->linkShortener($systemData->url,$redirectURL);
                        if($microsystemORenduser=="enduser"){DB::table('end_users_payment')->where('id',$merchantOrderID)->update(['wallet_link' => $redirectURL]);}
                    }
                    
                }  
            }

            if(isset($referenceNumber)){$fawryReturn=$referenceNumber;}else{$fawryReturn="";}
            if(isset($iframe)){$visaReturn=$iframe;}else{$visaReturn="";}
            if(isset($redirectURL)){$walletReturn=$redirectURL;}else{$walletReturn="";}
            $return['fawry']=$fawryReturn;
            $return['visa']=$visaReturn;
            $return['wallet']=$walletReturn;
            return $return;
        }else{return "System subscription has been ended.";}   
    }

    public function linkShortener($baseUrl=null,$tallUrl=null)
    {
        // for External API
        // URL: https://demo.microsystem.com.eg/api/linkShortener
        // Method: POST
        // Body: {"baseUrl":"demo.microsystem.com.eg","tallUrl":"https://accept.paymobsolutions.com/api/acceptance/iframes/2155"}
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        if(!isset($baseUrl) and !isset($tallUrl)){
            $body = @file_get_contents('php://input');
            $request = json_decode($body, true);
            $baseUrl = $request['baseUrl'];
            $tallUrl = $request['tallUrl'];
        }

        $shortID = DB::table('link_shortener')->insertGetId(array('base_url' => $baseUrl, 'tall_url' => $tallUrl, 'created_at' => $created_at ));
        $finalUrl = "https://".$baseUrl."/api/url/".$shortID;
        DB::table('link_shortener')->where('id',$shortID)->update(['final_url' => $finalUrl]);
        return $finalUrl;
    }

    public function linkShortenerFetch($urlId, $branchID=null, $tableID=null)
    {
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        $urlData = DB::table('link_shortener')->where('id',$urlId)->first();
        function getUserIP()
        {
            // Get real visitor IP behind CloudFlare network
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                    $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];
            if(filter_var($client, FILTER_VALIDATE_IP)){$ip = $client;}
            elseif(filter_var($forward, FILTER_VALIDATE_IP)){$ip = $forward;}
            else{$ip = $remote;}
            return $ip;
        }
        $user_ip = getUserIP();
        // stopped because we cand find any kind of information to identify who is the customer :(
        // if user scan QR so insert temp history with branch and table ID
        // if(isset($branchID) and isset($tableID)){
            // DB::table("$customerDB.history")->insert([['operation' => 'whatsappScanQR', 'details' => $branchID, 'type2' => 'auto', 'u_id' => $userData->u_id, 'notes' => $tableID, 'type1' => 'hotspot', 'add_date' => $today, 'add_time' => $today_time]]);
        // }

        DB::table('link_shortener')->where('id',$urlId)->update(['visits' => DB::raw('visits + 1'), 'last_visit_ip' => $user_ip, 'last_visit'=>$created_at]);
        return Redirect::to($urlData->tall_url);
    }

    public function sendWhatsappMenu($customerDB, $customerID, $to, $todayDateTime, $from=null, $returnToDialogFlowChatAI=null){

        $whatsappFirstBot = DB::table($customerDB.".campaigns")->where(['whatsapp'=>'1', 'type'=> 'whatsappFirstBot'])->where('whatsapp_after', '!=', '0')->first();
        $user = DB::table( $customerDB.".users" )->where('u_phone', 'like', '%'.$to.'%')->first();
        $globalID = DB::table('users_global')->where('mobile', 'like', '%'.$user->u_phone.'%')->value('id');
        if(isset($whatsappFirstBot) and isset($user)){

            $whatsappMenu = $whatsappFirstBot->question;
            $whatsappMenu = @str_replace("@name","$user->u_name",$whatsappMenu);
            $whatsappMenu = @str_replace("@email","$user->u_email",$whatsappMenu);
            // check if there is offer code in menu
            if (strpos($whatsappMenu, '@offerCodes') !== false) { 
                $offerCodes = DB::table("$customerDB.settings")->where('type', 'MainBotLoyaltyBendingOffersMsg')->value('value');
                foreach( DB::table("$customerDB.campaign_statistics")->where('type', 'offer')->where('state', '0')->where('u_id', $user->u_id)->get() as $offers ){
                    if(strlen($offers->offer_code) == "8"){
                        // this offer code to redeem loyalty program points, so we get there info from Table: 'loyalty_program'
                        $offerDesc = DB::table("$customerDB.loyalty_program")->where('id', $offers->campaign_id)->value('whatsapp');
                    }else{
                        // that's mean the digits is 6 and this is normal offer code related to normal campaign, so we get there info from Table: 'campaigns'
                        $offerDesc = DB::table("$customerDB.campaigns")->where('id', $offers->campaign_id)->value('offer_desc');
                    }
                    $offerCodes.="\n 🔘$offers->offer_code \n $offerDesc";
                }
                $offerCodes.="\n";
                $whatsappMenu = @str_replace("@offerCodes", $offerCodes,$whatsappMenu);
            }
            // get loyality points
            $loyaltyPoints = $this->getCustomerLoyaltyPoints($customerDB,$user->u_id,$todayDateTime);
            $whatsappMenu = @str_replace("@points","$loyaltyPoints",$whatsappMenu);
        
            // send whatsapp Message    
            $whatsappMenu = @str_replace("@id","$globalID",$whatsappMenu);
            $whatsappMenu = @str_replace("@mobile","$to",$whatsappMenu);
            // encode all message
            $whatsappMenuEncoded = urlencode($whatsappMenu);
            // start sending
            if(!$returnToDialogFlowChatAI == "1"){
                $sendState = $this->send( $from, $to , $whatsappMenuEncoded, $customerID, $customerDB,"","","","1",$whatsappFirstBot->id);
                if($sendState == "in" or $sendState == "ok" or $sendState == "1"){$sendState = 1;}else{$sendState = 0;}
            }else{
                $sendState = 1;
                //insert into there customer database
                $sentMsgID = DB::table($customerDB.".whatsapp")->insertGetId([
                    'send_receive' => '0'
                    , 'sent' => '1'
                    , 'type' => 'text'
                    , 'msg_id' => rand(111111111,999999999)
                    , 'campaign_id' => $whatsappFirstBot->id
                    , 'server_mobile' => $from
                    , 'client_mobile'=> $to
                    , 'message' => $whatsappMenu
                    , 'created_at' => $todayDateTime]);
            }
            
            
            // insert this message into WhatsApp campaign table
            DB::table("$customerDB.whatsapp_campaign")->insert([['state' => $sendState, 'user_id' => $user->u_id, 'campaign_id' => $whatsappFirstBot->id, 'created_at' => $todayDateTime]]);
            // add reach to survey campaign
            DB::table("$customerDB.campaign_statistics")->insert([['campaign_id' => $whatsappFirstBot->id, 'type' => "reach", 'u_id' => $user->u_id, 'created_at' => $todayDateTime]]);

            return $whatsappMenu;
        }
    }

    public function getFirstAndLastDayInQuotaPeriod ($database, $branchId){
        
        date_default_timezone_set("Africa/Cairo");
        $branchData = DB::table("$database.branches")->where('id',$branchId)->first();
        
        if(!isset($branchData)){
            // user didnt have a branch so we will return default values to avoid any crashing
            $firstDayMonth=date("Y-m")."-01";
            $lastDayMonth=date('Y-m-t', strtotime($firstDayMonth));
            return array('firstDayOfQuotaPeriod'=>$firstDayMonth, 'lastDayOfQuotaPeriod'=>$lastDayMonth);
        }
        // get the first day of renwing day to get monthly usage in GB
        if( $branchData->start_quota < 10 ){ $branchData->start_quota = "0".$branchData->start_quota;}
        // check if the expecting day grater than today so we will get quota period from last month, else (this month)
        $expectingRenewalDate=date("Y-m")."-$branchData->start_quota";
        if($expectingRenewalDate > date("Y-m-d")){
            // echo "yes<br>";
            $firstDayOfQuotaPeriod = date("Y-m-d",strtotime(date("Y-m-d", strtotime($expectingRenewalDate)) . " -1 months"));
            $lastDayOfQuotaPeriod = date("Y-m-d",strtotime(date("Y-m-d", strtotime($firstDayOfQuotaPeriod)) . " +1 months"));
            $lastDayOfQuotaPeriod = date("Y-m-d",strtotime(date("Y-m-d", strtotime($lastDayOfQuotaPeriod)) . " -1 days"));
        }else{
            // echo "no<br>";
            $firstDayOfQuotaPeriod = $expectingRenewalDate;
            $lastDayOfQuotaPeriod = date("Y-m-d",strtotime(date("Y-m-d", strtotime($expectingRenewalDate)) . " +1 months"));
            $lastDayOfQuotaPeriod = date("Y-m-d",strtotime(date("Y-m-d", strtotime($lastDayOfQuotaPeriod)) . " -1 days"));
        }
        return array('firstDayOfQuotaPeriod'=>$firstDayOfQuotaPeriod, 'lastDayOfQuotaPeriod'=>$lastDayOfQuotaPeriod);
    }
    
    // public function waAdmins($from=null, $to, $message, $customerID, $database=null, $loadBalance=null, $senderName=null, $msg_type=null)
    // {
    //     // check if send SMS from unknown customer ex. cron.php
    //     if(isset($database) and $database!=""){

    //         $whatsappInfo = DB::table($database.".settings")->where('type', 'whatsappProvider')->first();
    //         $whatsappProvider = $whatsappInfo->value;
    //         $whatsappState = $whatsappInfo->state;
    //         $whatsappProvider_username = DB::table($database.".settings")->where('type', 'whatsappProviderUsername')->value('value');
    //         $whatsappProvider_password = DB::table($database.".settings")->where('type', 'whatsappProviderPassword')->value('value');
    //         if(isset($senderName)){$whatsappProvider_sendername = $senderName;}
    //         else{$whatsappProvider_sendername = DB::table($database.".settings")->where('type', 'whatsappSenderName')->value('value');}
            
    //     }else{

    //     }
    //}
}
