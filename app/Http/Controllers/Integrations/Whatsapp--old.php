<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Hash;
use Session;
use Input;
use Validator;
use Auth;
use Redirect;
use DB;
use Carbon\Carbon;

class Whatsapp extends Controller
{

    public function whatsapp(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();

		include '../config.php';
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
		DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body]]);
		$body = json_decode($body);
		// return "stop here";
		
		// check if Facebook auth webhook
		if(isset($request->hub_mode) ){return $request->hub_challenge;}
		////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////// send message to Whatsapp  ////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////
		// stoped in 25/1/2020 because this function switched to WhatsApp integartion file in (send) function
		
		// General function to open socket with Whatsapp
		// after update this is not used 
		// function whatsappConnect($ip,$port,$command){

		// 	$f = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		// 	socket_set_option($f, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 500000));
		// 	$s = socket_connect($f, $ip, $port);
		// 	$len = strlen($command);
		// 	$sendResult = socket_sendto($f, $command, $len, 0, $ip, $port);
		// 	socket_close($f);
		// }
		
		// send MSG from outside server trough API
		if(isset($request->type) and $request->type == "send"){
			/*
				POST: https://demo.microsystem.com.eg/api/whatsapp?type=send
				Body:
					{ "username": "demo", "password": "1403636mra", "customer_id": "3", "load_balance": "1", "server_mobile": "201011539990", "client_mobile": "201061030454", "msg_type": "text", "message": "This is test message" , "urlencode": "0", "campaign_id": "0"}
					// customer_id: is Mandatory
				Response:
					// if whatsapp server received msg from us successfully
					1
					// if fail send restart to whatsapp server
					0

				//////////////////////////////
				sending types
					- message_sync_typing_send
					- message_typing_send
					- message_sync_send
					- message send 
			*/
			$body = @file_get_contents('php://input');
			$body = json_decode($body);
			
			// check on credentials and credit
			if(isset($request->username) and isset($request->password)){
				
				$isAuthorizedData = DB::table("customers")->where('id', $request->customer_id )->where('database', $request->username )->where('password', $request->password)->first();
				if(!isset($isAuthorizedData)){ return "1001,Invalid Username or Pssword or customerID"; }
				if($isAuthorizedData->state != "1"){ return "1002,Inactive Hotspot System"; }
				if($isAuthorizedData->whatsapp != "1"){ return "1003,WhatsApp service is disabled"; }
				if($isAuthorizedData->whatsapp_credit < "1"){ return "1004,Not enough WhatsApp credit"; }
				// if customer not choose specific server_mobile
				if( !isset($request->server_mobile) or $request->server_mobile == ""){
					$request->server_mobile = DB::table("whatsapp_token")->where('customer_id', $request->customer_id )->where('state', '1')->value('server_mobile');
				}

				$validateServerMobile = DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile)->first();
				if(isset($validateServerMobile)){
					if($validateServerMobile->state == 0){return "1005,WhatsApp number inactive";}
					if($validateServerMobile->state == 2){return "1006,WhatsApp number still in progress but not registerd";}
					if($validateServerMobile->state == 3){return "1007,WhatsApp number is blocked";}
				}else{ return "1008,WhatsApp number not registerd"; }
				$serverMobileToken = DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile )->value('token');
				// Discount Credit
				DB::table("customers")->where('database', $request->username )->where('password', $request->password)->update([ 'whatsapp_credit' => $isAuthorizedData->whatsapp_credit-1 ]);
			}else{
				return "1000,missing username or password";
			}

			// check if Hotspot system need to loadbalance between each WA number
			if( isset($request->load_balance) and $request->load_balance == "1" and DB::table("whatsapp_token")->where('customer_id', $request->customer_id )->where('state', '1' )->where('server_mobile', '!=', $request->server_mobile )->count() >= 2){
				$customerDB = $request->username.".whatsapp";
				$lastSentMsgServerMobile = DB::table($customerDB)->where('send_receive', '0')->orderBy('id','desc')->value('server_mobile');
				$nextNumberQuery = DB::table("whatsapp_token")->where('customer_id', $request->customer_id )->where('state', '1' )->where('server_mobile', '!=', $lastSentMsgServerMobile )->orderBy('id','desc')->first();
				$request->server_mobile = $nextNumberQuery->server_mobile;
			}else{
				// send to direct server_mobile
				// get customer ID to insert into there customer database
				$customerID = DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile )->value('customer_id');
				if($customerID == "0"){ // Hotspot system 
					$customerDB = "whatsapp";
				}else{
					$customerDB = $request->username.".whatsapp";
				}
			}
			// make sure all message sent from Radius page with emojis will send successfully
			if( isset($request->urlencode) and $request->urlencode == "1" ){
				$request->message = urldecode($request->message);
			}
			// to avoid any problem
			if( !isset($request->campaign_id) ){ $request->campaign_id = "";}
			//insert into there customer database
			$sentMsgID = DB::table($customerDB)->insertGetId([
				'send_receive' => '0'
				, 'campaign_id' => $request->campaign_id
				, 'server_mobile' => $request->server_mobile
				,'client_mobile'=> $request->client_mobile
				, 'message' => $request->message
				, 'created_at' => $todayDateTime]);
			// to mark sent message to know it when sent successfully from WA server
			$request->client_mobile = $sentMsgID."@".$request->client_mobile."_";
			
			// to makesure the supported emojis well send succesfully and recevice there sent notification succssfully
			$finalSendMessage = base64_encode($request->message);
			// sending
			$serverMobileToken = DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile )->value('token');
			$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/whatsapp";
			sleep(1);
			// $msg = $serverMobileToken.','.$request->server_mobile.','.'/message_sync_send "'.$request->client_mobile.'" "'.$finalSendMessage.'" ';
			$msg = $serverMobileToken.','.$request->server_mobile.','.'message_sync_typing_send,'.$request->client_mobile.' "'.$finalSendMessage.'" ';
			// $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
			// $response = @file_get_contents($url, FALSE, $context);
			$response = $whatsappClass->sendingWithoutWaiting($url, $msg, $whatsapp_Srv1_OPsocketPort);
			/*
			/////////////////////////////Fire and Forget HTTP Request //////////////////////////
			$endpoint = $url;
			$postData = $msg;

			$endpointParts = parse_url($endpoint);
			$endpointParts['path'] = $endpointParts['path'] ?? '/';
			// return $endpointParts['port'] = $endpointParts['port'] ?? $endpointParts['scheme'] === 'https' ? 443 : 80;
			$endpointParts['port'] = $whatsapp_Srv1_OPsocketPort;

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
			/////////////////////////////Fire and Forget HTTP Request //////////////////////////
			*/
			
			/* // not used because whatsapp cron is doing the same function (restart and resend again)
			if($response == 0){
				// sending restart for this number
				$url2="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/server";
				$msg2 = "restart,$request->server_mobile";
				$context2 = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg2")));
				$response2 = @file_get_contents($url2, FALSE, $context2);
				// resend message again
				for($i=0; $i<=60; $i++){
					$newServerMobileToken = DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile )->value('token');
					if( $newServerMobileToken != $serverMobileToken ){
						$msg = $newServerMobileToken.','.$sentMsgID.','.'/message_sync_typing_send '.$request->client_mobile.' "'.base64_encode($request->message).'" ';
						$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
						return $response = @file_get_contents($url, FALSE, $context);
						break;
					}
					sleep(0.5);
				}	
			}
			*/
			
			return $response;
		}
		
		////////////////////////////////////////////////////////////////////////////////////
		/////////////////// Received message from user through Whatsapp  /////////////////// Webhook
		////////////////////////////////////////////////////////////////////////////////////
			// if request from Yowsup or chat-API.com or mercury.chat or mikofi.com or official Facebook WhatsApp business account
		if( (isset($request->type) and $request->type == "received") or (isset($body->messages[0]->self)) or (isset($body->data->body) and $body->type == "message" and $body->data->fromMe != "1")  or (isset($body->data->data->messages[0]->message->conversation))  or ( isset($body->object) and $body->object == "whatsapp_business_account" and isset($body->entry[0]->changes[0]->value->messages[0]->text->body) ) ){
			/*
			POST:https://demo.microsystem.com.eg/api/whatsapp?type=received
			Body:
				{"client_mobile": "201010746667@s.whatsapp.net", "server_mobile": "201096622600@s.whatsapp.net", "msg_time": "06-11-2019 13:14:06", "message": "\u0628\u0628", "msg_id": "4595B16D39234CCB34F358722C6FD504", "isGroup": "201010746667@s.whatsapp.net", "msg_type": "text"}
			Response:
				1
			*/
			
			// if(isset($request->returnToDialogFlowChatAI)){return "Here";}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
			if(isset($request->type) and $request->type == "received"){
				// from Yowsup
				$clientMobile = (explode("@",$body->client_mobile));
				$serverMobile = (explode("@",$body->server_mobile));
				$serverMobileToken = DB::table("whatsapp_token")->where('server_mobile', $serverMobile[0] )->value('token');
				$msgTime = date("Y-m-d H:i:s", strtotime($body->msg_time));
				$receviedMessage = $body->message;

			}elseif(isset($body->instanceId)){
				// from Chat-API.com
				if(isset($body->messages[0]->self) and $body->messages[0]->self == "1"){return "sent MSG";};
				if(isset($body->ack)){return "ACK";}
				// get from chatapi
				// return $body->messages;
				$clientMobile = explode("@",$body->messages[0]->author);
				$serverMobile[0] = DB::table("whatsapp_token")->where('chatapi_instance_id', $body->instanceId )->value('server_mobile');
				$receviedMessage = $body->messages[0]->body;
				$msgTime = date('Y-m-d H:i:s', $body->messages[0]->time);
				$body->msg_type = "text";
				$body->msg_id = $body->messages[0]->id;

			}elseif(isset($body->data->body)){
				// from mercury.chat
				// return $body->data->body;
				$clientMobile = explode("@",$body->data->author);
				$serverMobile[0] = DB::table("whatsapp_token")->where('chatapi_instance_id', $body->data->instance_number )->value('server_mobile');
				$receviedMessage = $body->data->body;
				$msgTime = date('Y-m-d H:i:s', $body->data->time);
				$body->msg_type = "text";
				$body->msg_id = $body->data->message_id;

			}elseif(isset($body->data->data->messages[0]->message->conversation)){
				// from mikofi.com
				// return $body->data->messages[0]->message->conversation;
				$clientMobile = explode("@",$body->data->data->messages[0]->key->remoteJid);
				$serverMobile[0] = DB::table("whatsapp_token")->where('chatapi_instance_id', $body->instance_id )->value('server_mobile');
				$receviedMessage = $body->data->data->messages[0]->message->conversation;
				$msgTime = date('Y-m-d H:i:s', $body->data->data->messages[0]->message->messageContextInfo->deviceListMetadata->senderTimestamp);
				$body->msg_type = "text";
				$body->msg_id = $body->data->data->messages[0]->key->id;
			}elseif(isset($body->object) and $body->object == "whatsapp_business_account"){
				// from official Facebook WhatsApp business account
				// return $body->entry[0]->changes[0]->value->metadata->display_phone_number;
				$clientMobile[0] = $body->entry[0]->changes[0]->value->contacts[0]->wa_id;
				$serverMobile[0] = $body->entry[0]->changes[0]->value->metadata->display_phone_number;
				$receviedMessage = $body->entry[0]->changes[0]->value->messages[0]->text->body;
				$msgTime = $todayDateTime;
				$body->msg_type = "text";
				$body->msg_id = $body->entry[0]->id; // conversation ID
			}
			
			// replace arabic numbers
			$receviedMessage = str_replace("١","1",$receviedMessage);
			$receviedMessage = str_replace("٢","2",$receviedMessage);
			$receviedMessage = str_replace("٣","3",$receviedMessage);
			$receviedMessage = str_replace("٤","4",$receviedMessage);
			$receviedMessage = str_replace("٥","5",$receviedMessage);
			$receviedMessage = str_replace("٦","6",$receviedMessage);
			$receviedMessage = str_replace("٧","7",$receviedMessage);
			$receviedMessage = str_replace("٨","8",$receviedMessage);
			$receviedMessage = str_replace("٩","9",$receviedMessage);
			$receviedMessage = str_replace("٠","0",$receviedMessage);

			$customerID = DB::table("whatsapp_token")->where('server_mobile', $serverMobile[0] )->value('customer_id');
			if($customerID == "0"){ // Hotspot system 
				$customerDBonly = "microsystem";
				$customerDB = "whatsapp";
			}else{
				$customerDB = DB::table("customers")->where('id', $customerID )->value('database');
				$customerDBonly = $customerDB;
				$customerDB = $customerDB.".whatsapp";
			}
			// test sending by chatAPI
			// return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], "https://www.w3schools.com/tags/smiley.gif Grills Menu");
			// set variables
			$lastUserMessage = DB::table($customerDB)->where('send_receive','0')->where('sent','1')->where('client_mobile',$clientMobile[0])->where('server_mobile',$serverMobile[0])->orderBy('id', 'desc')->first();
			$userData = DB::table( $customerDBonly.".users" )->where('u_phone', 'like', '%'.$clientMobile[0].'%')->first();
			$landBot = DB::table("$customerDBonly.campaigns")->where('type','whatsappFirstBot')->where('whatsapp_first_survey','1')->where('whatsapp','1')->first();
			
			// received request from QR from unregistered customer
			if(!isset($lastUserMessage) or !isset($userData)){
				if( !isset($lastUserMessage) and isset($userData)){ // existed customer but send message to server mobile without recevine any previous message from this server mobile
					if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0],1);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0]);
				}elseif( (!isset($userData) and !isset($lastUserMessage)) or (!isset($userData) and isset($lastUserMessage) and $lastUserMessage->campaign_id!="88888888") ){// new customer
					$whatsappQRaskForName = DB::table("$customerDBonly.settings")->where('type', 'whatsappQRaskForName')->value('value');

					if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappQRaskForName, "88888888");}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappQRaskForName, "88888888"); 
				}
			}// system asked him for his name so we will register new user account then send whatsapp menu
			if(isset($lastUserMessage) and $lastUserMessage->campaign_id=="88888888"){
				// check if recevied any unrecognized symbols 
				if (strpos($receviedMessage, '=') !== false) {
					// ask him again for his name
					$whatsappQRaskForName = DB::table("$customerDBonly.settings")->where('type', 'whatsappQRaskForName')->value('value');
					
					if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappQRaskForName, "88888888");}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappQRaskForName, "88888888");
				}else{
					// create new user
					// check country code
					if( substr($clientMobile[0], 0, 2)=="20" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 1); $u_country = "Egypt"; }
					elseif( substr($clientMobile[0], 0, 3)=="966" ){ $mobileWithoutCountryCode = "0".substr($clientMobile[0], 3);  $u_country = "Saudi Arabia"; }
					elseif( substr($clientMobile[0], 0, 3)=="971" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "United Arab Emirates"; }
					elseif( substr($clientMobile[0], 0, 3)=="965" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Kuwait"; }
					elseif( substr($clientMobile[0], 0, 3)=="905" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Canada"; }
					elseif( substr($clientMobile[0], 0, 2)=="41" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 2);   $u_country = "Switzerland"; }
					elseif( substr($clientMobile[0], 0, 3)=="491" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Germany"; }
					elseif( substr($clientMobile[0], 0, 3)=="316" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Netherlands"; }
					elseif( substr($clientMobile[0], 0, 2)=="44" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 2);   $u_country = "United Kingdom"; }
					elseif( substr($clientMobile[0], 0, 3)=="393" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Italy"; }
					elseif( substr($clientMobile[0], 0, 3)=="336" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "France"; }
					elseif( substr($clientMobile[0], 0, 3)=="973" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Bahrain"; }
					elseif( substr($clientMobile[0], 0, 3)=="974" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Qatar"; }
					elseif( substr($clientMobile[0], 0, 3)=="964" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Iraq"; }
					elseif( substr($clientMobile[0], 0, 3)=="961" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Lebanon"; }
					elseif( substr($clientMobile[0], 0, 3)=="962" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Jordan"; }
					elseif( substr($clientMobile[0], 0, 3)=="220" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Gambia"; }
					elseif( substr($clientMobile[0], 0, 3)=="970" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Palestine"; }
					elseif( substr($clientMobile[0], 0, 3)=="972" ){ $mobileWithoutCountryCode = substr($clientMobile[0], 3);  $u_country = "Israel"; }
					else{ $mobileWithoutCountryCode = $clientMobile[0]; $u_country = "Unknown";}
					// create new user in database
					$newUserID = DB::table("$customerDBonly.users")->insertGetId([ 'u_email' => ' ', 'Registration_type' => '2', 'u_state' => '1', 'suspend' => '0', 'u_name' => $receviedMessage, 'u_uname' => $mobileWithoutCountryCode, 'u_password' => $clientMobile[0], 'u_phone' => $clientMobile[0], 'u_country' => $u_country, 'u_gender' => '2', 'branch_id' => DB::table($customerDBonly.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($customerDBonly.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($customerDBonly.".area_groups")->where('name','Default')->orWhere('name','default')->value('id'), 'created_at' => $todayDateTime]);
					// create fake session in radacct table to count this visit
					DB::table("$customerDBonly.radacct")->insert([[ 'acctsessionid' => rand(100000, 999999), 'acctuniqueid' => rand(100000, 999999), 'username' => $mobileWithoutCountryCode, 'acctstarttime' => $todayDateTime, 'acctstoptime' => $todayDateTime, 'acctsessiontime' => '60', 'acctauthentic' => '00:01:00', 'acctupdatetime' => $todayDateTime, 'u_id' => $newUserID, 'dates' => $today, 'branch_id' => DB::table($customerDBonly.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($customerDBonly.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($customerDBonly.".area_groups")->where('name','Default')->orWhere('name','default')->value('id') ]]);
					// send Whatsapp menu
					if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0],1);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0]);
				}
			}
			
			// if first 3 char is number
			$first3Char = mb_substr($receviedMessage, 0, 3);

			// check if recevied reply on specific message and filter it
			if (strpos($receviedMessage, 'context_info=[stanza_id=') !== false) {
				// make sure the reply to server mobile
				$receviedMessagePart1 = (explode("participant=",$receviedMessage));
				$receviedMessagePart2 = (explode("@s.whatsapp.net",$receviedMessagePart1[1]));
				if($receviedMessagePart2[0] == $serverMobile[0]){
					// reply to server mobile, so get specific campaign user reply to by message ID
					$getReplyMsgIDpart1 = explode("context_info=[stanza_id=",$receviedMessage);
					$getReplyMsgIDpart2 = explode(" participant",$getReplyMsgIDpart1[1]);
					$getReplyMsg = DB::table("whatsapp")->where('msg_id', $getReplyMsgIDpart2[0] )->first();
					$receviedMessage = "This is reply for: $getReplyMsg->message";

				}else{
					// reply to his text	
				}
				//$receviedMessage = $receviedMessagePart2[0];
			}
			// check if user reply to ReferralInvitee whatsappReferralInvitee
			// return $lastUserMessage->campaign_id;
			
			if(isset($landBot) and isset($lastUserMessage->pending_survey_id) and isset($lastUserMessage->campaign_id) and $lastUserMessage->campaign_id==$landBot->id ){
				// Make sure this request is whatsappReferralInvitee
				if( DB::table( $customerDBonly.".survey" )->where('id' ,$lastUserMessage->pending_survey_id)->value('whatsapp_referral_invitee') == 1 ){

					// check if this number if valid or not
					$inviterUserData = DB::table( $customerDBonly.".users" )->where('u_phone', $receviedMessage)->orWhere('u_uname', $receviedMessage)->first();
					// $userData = DB::table( $customerDBonly.".users" )->where('u_phone', 'like', '%'.$clientMobile[0].'%')->first();
					
					// make sure there is an existing inviter and user didnt invite self
					if(isset($inviterUserData) and $clientMobile[0] != $receviedMessage ){
						// user exist
						// check if user online or offline
						$currSession = DB::table($customerDBonly.".radacct")->where('u_id',$userData->u_id)->whereNull('acctstoptime')->first();
						if(isset($currSession)){
							// user online
							
							// check if there is limit or not
							if(isset($landBot->offer_limit) and $landBot->offer_limit!="0" and $landBot->offer_limit!="" and $landBot->offer_limit!=null){
								if( $landBot->offer_limit > DB::table("$customerDBonly.history")->where('type', 'whatsappReferralInvitationSuccess')->where('notes', $inviterUserData->u_id)->count() ){ $limitationPass = 1; }
							}else{ $limitationPass = 1; }

							if($limitationPass == 1){
								
								// ckeck if there is offer for inviter
								if( DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralinviterIsOffer')->value('state') == "1" ){
									$newOfferCode = rand(100000, 999999);
									DB::table("$customerDBonly.campaign_statistics")->insert([['type' => 'offer', 'campaign_id' => $landBot->id, 'u_id' => $inviterUserData->u_id, 'state' => '0', 'offer_code' => $newOfferCode, 'created_at' => $todayDateTime]]);
									$sendMsg="$landBot->offer_desc";
									$sendMsg.="\n 🎁 Offer Code: $newOfferCode";
									if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $inviterUserData->u_phone, $sendMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
									$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $inviterUserData->u_phone, $sendMsg);
								}
								// ckeck if there is offer for invitee
								if( DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralinviteeIsOffer')->value('state') == "1" ){
									$newOfferCode = rand(100000, 999999);
									DB::table("$customerDBonly.campaign_statistics")->insert([['type' => 'offer', 'campaign_id' => $landBot->id, 'u_id' => $userData->u_id, 'state' => '0', 'offer_code' => $newOfferCode, 'created_at' => $todayDateTime]]);
									$sendMsg ="$landBot->offer_desc";
									$sendMsg.="\n 🎁 Offer Code: $newOfferCode";
									if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $inviterUserData->u_phone, $sendMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
									$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $userData->u_phone, $sendMsg);
								}
								// ckeck if there is points for inviter
								if( DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralinviterIsPoints')->value('state') == "1" ){
									DB::table("$customerDBonly.loyalty_points")->insert([['state' => '1','type' => '1', 'a_id' => '0', 'u_id' => $inviterUserData->u_id, 'points' => DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralinviterIsPoints')->value('value'), 'notes' => "whatsappReferralinviter after invite $userData->u_id ", 'created_at' => $todayDateTime]]);
								}
								// ckeck if there is points for invitee
								if( DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralinviteeIsPoints')->value('state') == "1" ){
									DB::table("$customerDBonly.loyalty_points")->insert([['state' => '1','type' => '1', 'a_id' => '0', 'u_id' => $userData->u_id, 'points' => DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralinviteeIsPoints')->value('value'), 'notes' => "whatsappReferralinvitee after invite $inviterUserData->u_id ", 'created_at' => $todayDateTime]]);
								}

							}else{
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitationOfferLimitExceeded')->value('value') );}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitationOfferLimitExceeded')->value('value') );
							}

							DB::table("$customerDBonly.history")->insert([['operation' => 'whatsappReferralInvitationSuccess', 'details' => $currSession->branch_id, 'type2' => 'auto', 'u_id' => $userData->u_id, 'notes' => $inviterUserData->u_id, 'type1' => 'hotspot', 'add_date' => $today, 'add_time' => $today_time]]);
							$whatsappReferralInvitedAskInvitationSuccessMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitedAskInvitationSuccessMsg')->value('value');
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappReferralInvitedAskInvitationSuccessMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappReferralInvitedAskInvitationSuccessMsg);
						}else{
							// user offline
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitationOpenWiFi')->value('value'));}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitationOpenWiFi')->value('value'));
						}
					}else{ 
						// user not exist
						if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitationOpenWiFi')->value('value'));}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
						return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitedAskInvitationFailMsg')->value('value') );
					}
				}
			}
			////////////////////////////////////////////////////////////
			/////////////  check if admin send request  ////////////////
			////////////////////////////////////////////////////////////
			
			elseif ( (is_numeric($first3Char) and strlen($receviedMessage)>2 ) or ($lastUserMessage->campaign_id=="999999999") ){
				
				// $allCustomers=DB::table('customers')->where('state','1')->where('whatsapp','1')->groupBy('database')->get();
				$getCustomerID=DB::table('whatsapp_token')->where('server_mobile',$serverMobile[0])->where('state','1')->value('customer_id');
				$Customer=DB::table('customers')->where('id',$getCustomerID)->where('state','1')->groupBy('database')->first();
				// foreach( $allCustomers as $Customer )
				// {
					// Get Whatsapp Admins
					$adminData = DB::table( $Customer->database.".admins" )->where('mobile', 'like','%'.$clientMobile[0])->first();
					
					// if client_mobile is admin and server_mobile in the same customer DB
					if( (isset($adminData) and $Customer->database == $customerDBonly) or ($lastUserMessage->campaign_id=="999999999") ){
						// check permissions
						$adminPermissionsName = 'WAadmin';
						$redeemPointsPermissionsName = 'WAredeemPoints';
						$regPointsPermissionsName = 'WAregPoints';
						if ( isset($adminData->permissions) and strpos($adminData->permissions, $adminPermissionsName) !== false) { $adminPermission = 1; }else{ $adminPermission = 0; }
						if ( isset($adminData->permissions) and strpos($adminData->permissions, $redeemPointsPermissionsName) !== false) { $redeemPointsPermission = 1; }else{ $redeemPointsPermission = 0; }
						if ( isset($adminData->permissions) and strpos($adminData->permissions, $regPointsPermissionsName) !== false) { $regPointsPermission = 1; }else{ $regPointsPermission = 0; }
						if ( isset($adminData->id) ) { $adminID = $adminData->id;}else{ $adminID = 0; }

						if( ($adminPermission == 1 or $redeemPointsPermission == 1 or $regPointsPermission == 1) or ($lastUserMessage->campaign_id=="999999999") ){

							// some checks for to know the request
							$regPointSplit = @explode(" ",$receviedMessage);
							$refundSplit = @explode(" -",$receviedMessage);
							$offerCodeSplit = explode(" ",$receviedMessage);

							///////////////////////////////////////////////
							// check if admin need to redeem loyaloty program for a customer
							if(strlen($receviedMessage)==1 and isset($lastUserMessage) and strpos($lastUserMessage->pending_survey_id, 'redeemLoyaltyProgram')){
								// to avoid resening previus message again
								$adminRequest = 1;

								$options = @explode(",",$lastUserMessage->pending_survey_id);
								foreach($options as $option){
									
									$order = @explode(":",$option);
									$variable = @explode("-",$option);
									if($receviedMessage == $order[0]){

										// get loyalty program data
										$currLoyaltyProgram = DB::table("$customerDBonly.loyalty_program")->where('id',$variable[1])->first();
										// get customer Data
										$customerData = DB::table("$customerDBonly.users")->where('u_id',$variable[2])->first();
										// get camoaign data
										$campaignData = DB::table( $customerDBonly.".campaigns" )->where('id',$currLoyaltyProgram->campaign_id)->first();
										// make sure user already have enough credit to redeem
										$userLoyaltyPoints = $whatsappClass->getCustomerLoyaltyPoints($customerDBonly,$customerData->u_id,$todayDateTime);
										if($userLoyaltyPoints < $currLoyaltyProgram->points){
											if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $customerData->u_phone, "Not enough points.");}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
											$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $customerData->u_phone, "Not enough points.");
											// EXIT
											return "Done 0";
										}
										// I'mhere
										// insert redeem
										DB::table("$customerDBonly.loyalty_points")->insert([['state' => '1','type' => '2', 'a_id' => $adminID, 'u_id' => $variable[2], 'points' => $currLoyaltyProgram->points, 'created_at' => $todayDateTime]]);
										// send Admin notification
										$autoReplyMsg = "✅ $currLoyaltyProgram->points Points has been redeemed successfully to \n";
										$autoReplyMsg.=  $currLoyaltyProgram->whatsapp;
										"For: $customerData->u_name /n Mobile: $customerData->u_phone";
										// send Customer notification
										$whatsappUserReceivePointsMsg = "🎁 Congratulations you have redeemed $currLoyaltyProgram->points Points to \n";
										$whatsappUserReceivePointsMsg.=  $currLoyaltyProgram->whatsapp;
										// create offer code
										// in this case (create offer code to redeem loyalty program) we will create tall offer code 8 digits instead of 6 digits to be able to differentiate it, 
										// and insert loyalty program id insted of campaign id in field campaign_id
										$newOfferCode = rand(10000000, 99999999);
										DB::table("$customerDBonly.campaign_statistics")->insert([['type' => 'offer', 'campaign_id' => $currLoyaltyProgram->id, 'u_id' => $customerData->u_id, 'state' => '0', 'offer_code' => $newOfferCode, 'created_at' => $todayDateTime]]);
										$whatsappUserReceivePointsMsg.="\n 🎁 Offer Code: $newOfferCode";
										if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $customerData->u_phone, $whatsappUserReceivePointsMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
										$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $customerData->u_phone, $whatsappUserReceivePointsMsg);
										// if user is redeeming, avoid sending message to admin
										if($adminID == 0){return "Done";}
									
									}								
								}
							}
							///////////////////////////////////////////////
							// 	check if admin query about customer and not for redeemPoints
							elseif(strlen($receviedMessage)>=5 and DB::table( $Customer->database.".campaign_statistics" )->where( 'offer_code',$offerCodeSplit[0] )->where('type','offer')->count()==0 ){
								$adminRequest = 1;
								// check if request by mobile number or global ID
								if($receviedMessage[0] == "0" and strlen($receviedMessage)>7 ){
									// through mobile number
									$customerData = DB::table( $customerDBonly.".users" )->where('u_phone', 'like', '%'.$regPointSplit[0].'%')->first();
									if(isset($customerData)){
										// found user data
										$foundCustomerData = 1;
									}else{
										// not found data
										$autoReplyMsg = "❌ Not Found Mobile Number.";
									}
								}else{
									// through Global ID
									$globalIdData = DB::table('users_global')->where('id', $regPointSplit[0])->first();
									if(isset($globalIdData)){
										// return $Customer->id;
										// get customer ID then get local user ID
										$globalCustomerIDs = @explode(",",$globalIdData->customer_id);
										$counter = 0;
										foreach($globalCustomerIDs as $globalCustomerID){
											if($globalCustomerID == $Customer->id){
												$sequence = $counter;
												break;
											}
											$counter++;
										}
										// then get local user ID using $sequence
										if(isset($sequence)){
											$localIDs = @explode(",",$globalIdData->local_user_id);
											$counter2 = 0;
											foreach($localIDs as $localID){
												if($counter2 == $sequence){
													$globalID = $localID;
													break;
												}
												$counter2++;
											}
										}
										if(isset($globalID)){
											// found user ID
											$customerData = DB::table( $customerDBonly.".users" )->where('u_id', $globalID)->first();

											if(isset($customerData)){
												// found user data
												$foundCustomerData = 1;
											}else{
												// not found data
												$autoReplyMsg = "❌ this customer is not registered in your system.";
											}
										}else{
											// user ID already exist but not user ID
											$autoReplyMsg = "❌ user ID already exist but not found user ID.";
										}
									}else{
										// not found user ID
										$autoReplyMsg = "❌ Customer Not Found.";
									}
								}
								///////////////////////////////////////////////
								// check if request for regPoints OR refund
								if( (isset($regPointSplit[1]) and !isset($refundSplit[1])) or ( isset($regPointSplit[1]) and isset($refundSplit[1]) ) ){
									
									// regPoints
									if(isset($regPointSplit[1]) and !isset($refundSplit[1])){
										$request4RegPoints = 1;
										// calculate loyality points
										$amountToLoyaltyPoints = DB::table("$Customer->database.settings")->where('type', 'amountToLoyaltyPoints')->value('value');
										$earnedPoints = $regPointSplit[1] * $amountToLoyaltyPoints;
										// insert points
										DB::table("$customerDBonly.loyalty_points")->insert([['state' => '1','type' => '1', 'a_id' => $adminData->id, 'u_id' => $customerData->u_id, 'amount' => $regPointSplit[1], 'points' => $earnedPoints, 'created_at' => $todayDateTime]]);
										// send Whatsapp Message to Admin
										$autoReplyMsg = "✅ $earnedPoints Points has been added successfully.";
										$whatsappUserReceivePointsMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappUserReceivePointsMsg')->value('value');
										$whatsappUserReceivePointsMsg = @str_replace("@earned","$earnedPoints",$whatsappUserReceivePointsMsg);
									} 
									// refund
									elseif(isset($regPointSplit[1]) and isset($refundSplit[1])){
										// calculate loyality points 
										$amountToLoyaltyPointsRefund = DB::table("$Customer->database.settings")->where('type', 'amountToLoyaltyPoints')->value('value');
										$refundPoints = abs($regPointSplit[1]) * $amountToLoyaltyPointsRefund;
										DB::table("$customerDBonly.loyalty_points")->insert([['state' => '1','type' => '0', 'a_id' => $adminData->id, 'u_id' => $customerData->u_id, 'amount' => abs($regPointSplit[1]), 'points' => $refundPoints, 'created_at' => $todayDateTime]]);
										// send Whatsapp Message to Admin
										$autoReplyMsg = "✅ $refundPoints Points has been refunded successfully.";
										$whatsappUserReceivePointsMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappUserRefundPointsMsg')->value('value');
										$whatsappUserReceivePointsMsg = @str_replace("@refund","$refundPoints",$whatsappUserReceivePointsMsg);
									}

									// continue steps
									if($regPointsPermission == 1 or $adminPermission == 1){
										if(isset($foundCustomerData) and $foundCustomerData == 1){
									
											// get loyalty points
											$loyaltyPoints = $whatsappClass->getCustomerLoyaltyPoints($customerDBonly,$customerData->u_id,$todayDateTime);
											// convert points text message
											$whatsappUserReceivePointsMsg = @str_replace("@points","$loyaltyPoints",$whatsappUserReceivePointsMsg);

											if(isset($request4RegPoints)){
												// get all and avilable loyalty programs
												$allAndAvilableLoyaltyProgram = $whatsappClass->getAllAndAvilableLoyaltyProgram($customerDBonly, $customerData->u_id, $todayDateTime);
												$whatsappUserReceivePointsMsg = @str_replace("@all_loyalty_programs",$allAndAvilableLoyaltyProgram['all'],$whatsappUserReceivePointsMsg);
												if($allAndAvilableLoyaltyProgram['available']!=null){ $whatsappUserReceivePointsMsg = @str_replace("@available_loyalty_programs",$allAndAvilableLoyaltyProgram['available'],$whatsappUserReceivePointsMsg); }
												else{$whatsappUserReceivePointsMsg = @str_replace("@available_loyalty_programs","till now nothing😳!",$whatsappUserReceivePointsMsg);}
											}
											if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $customerData->u_phone, $whatsappUserReceivePointsMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
											$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $customerData->u_phone, $whatsappUserReceivePointsMsg);
											// avoid continue to user reply options
											$adminSentMessage = 1;
										}
									}
								}
								///////////////////////////////////////////////
								// Admin query for a customer info to redeemPoints 
								else{
									if($regPointsPermission == 1 or $redeemPointsPermission == 1){
										if(isset($foundCustomerData) and $foundCustomerData == 1){
											
											// to avoid inserting any record after this record to be able to detect options
											$waitAdminReply = 1;
											// build the reply
											$autoReplyMsg = $whatsappClass->getAllCustomerInfoToAdmin($customerDBonly, $customerData->u_id, $todayDateTime, '1' );
											$allAndAvilableLoyaltyProgram = $whatsappClass->getAllAndAvilableLoyaltyProgram($customerDBonly, $customerData->u_id, $todayDateTime);
											if(isset($allAndAvilableLoyaltyProgram['available']) and $allAndAvilableLoyaltyProgram['available']!=null){
												$autoReplyMsg.="〰〰〰〰〰〰〰\n Available Loyalty Program \n";
												$autoReplyMsg.= $allAndAvilableLoyaltyProgram['available'];	
											}
											// buildWaitingAdminResponseMenu
											$setWaitingAdminResponse = "999999999";
											$buildWaitingAdminResponseMenu = $allAndAvilableLoyaltyProgram['buildWaitingAdminResponseMenu'];
										}
									}
								}
							///////////////////////////////////////////////
							// Admin redeemOffer	
							}else{
								
								if($redeemPointsPermission == "1"){
									$adminRequest = 1;
									$offerData=DB::table( $Customer->database.".campaign_statistics" )->where( 'offer_code',$offerCodeSplit[0] )->where('type','offer')->first();
									if( isset($offerData) ){
										if( $offerData->state == "0" ){// offer is available
											
											// user data
											$userData = DB::table( $Customer->database.".users" )->where('u_id',$offerData->u_id)->first();
											// preset this variable to avoid any error
											if(!isset($autoReplyMsg)){$autoReplyMsg="";}

											if(strlen($offerCodeSplit[0]) == "8"){
												// this offer code to redeem loyalty program points, so we get there info from Table: 'loyalty_program'
												$loyaltyProgramData = DB::table( $Customer->database.".loyalty_program" )->where('id',$offerData->campaign_id)->first();
												$offerItems = DB::table( $Customer->database.".loyalty_program_items" )->where('loyalty_program_id',$loyaltyProgramData->id)->get();
												// to pass next step
												$remainingOffers="unlimited";
												// Loyalty Program state
												if($loyaltyProgramData->state == "1"){ $campaignState="🔵"; }else{ $campaignState="🔴"; }
												// check if this is FREE_ITEMS
												if($loyaltyProgramData->type == "1"){
													$loyaltyProgramType = "Type: FREE ITEMS \n";
													$counter = 0;
													foreach($offerItems as $item){
														if($counter>0){$autoReplyMsg.="\n";}
														$loyaltyProgramType.=' 🔘Item Name: "'.$item->item_name;
														$counter++;
													}
												}// check if this is DISCOUNT_PER_ITEM
												elseif($loyaltyProgramData->type == "2"){
													$loyaltyProgramType = "Type: DISCOUNT PER ITEM \n";
													if($loyaltyProgramData->discount_type == "1"){
														$loyaltyProgramType.= " 🔘Discount: $loyaltyProgramData->discount_value% \n";
													}else{
														$loyaltyProgramType.= " 🔘Discount: $loyaltyProgramData->discount_value ".DB::table( $Customer->database.".settings" )->where('type', 'currency')->value('value')." \n";
													}
													$counter = 0;
													foreach($offerItems as $item){
														if($counter>0){$autoReplyMsg.="\n";}
														$loyaltyProgramType.='For Item: "'.$item->item_name;
														$counter++;
													}
												}
												// check if this is BY_ONE_GET_MANY
												elseif($loyaltyProgramData->type == "3"){
													$loyaltyProgramType = "Type: BY ONE GET MANY \n";
													$loyaltyProgramType.= " 🔘If you buy: $loyaltyProgramData->depends_on_item_name \n";
													$counter = 0;
													foreach($offerItems as $item){
														if($counter>0){$autoReplyMsg.="\n And";}
														$loyaltyProgramType.=' 🔘Get Free: '.$item->item_name;
														$counter++;
													}
												}
												// check if this is DISCOUNT_PER_SALE
												elseif($loyaltyProgramData->type == "4"){
													$loyaltyProgramType = "Type: DISCOUNT PER SALE \n";
													if($loyaltyProgramData->discount_type == "1"){
														$loyaltyProgramType.= " 🔘Total discount: $loyaltyProgramData->discount_value%";
													}else{
														$loyaltyProgramType.= " 🔘Total discount: $loyaltyProgramData->discount_value ".DB::table( $Customer->database.".settings" )->where('type', 'currency')->value('value');
													}
												}
												// offer informations
												$autoReplyMsg = "✅ *Valid Offer (Loyalty Program)* \n 🔘For: $userData->u_name\n 🔘Mobile: *******".substr($userData->u_phone, -4)."\n 🔘Creation date: $offerData->created_at\n 🔘L.P state: $campaignState \n 🔘$loyaltyProgramType \n";

											}else{
												// that's mean the digits is 6 and this is normal offer code related to normal campaign, so we get there info from Table: 'campaigns'
												$campaignData = DB::table( $Customer->database.".campaigns" )->where('id',$offerData->campaign_id)->first();
												// check if there is a limit or not
												if($campaignData->offer_limit =="" or $campaignData->offer_limit=="0")
												{
													$remainingOffers="unlimited";
												}else{
													$redeemedOffers = DB::table($Customer->database.".campaign_statistics")->where( 'type', 'offer' )->where( 'campaign_id', $campaignData->id )->where( 'state', '1' )->count();
													$remainingOffers=$campaignData->offer_limit-$redeemedOffers;
												}
												// Campaign state
												if($campaignData->state == "1"){ $campaignState="🔵"; }else{ $campaignState="🔴"; }
												// offer informations
												$autoReplyMsg = "✅ *Valid Offer* \n 🔘For: $userData->u_name\n 🔘Mobile: *******".substr($userData->u_phone, -4)."\n 🔘Creation date: $offerData->created_at\n 🔘Campaign state: $campaignState \n 🔘Campaign name: $campaignData->campaign_name \n 🔘Offer Description: $campaignData->offer_desc \n 🔘Offer Terms: $campaignData->offer_terms \n 🔘Offer Message: $campaignData->offer_sms_message \n 🔘Remaining Offers: $remainingOffers \n";
											}
											
											// check if the is avilable offer before limit finish
											if( $remainingOffers == "unlimited" or $remainingOffers >=1 ){
												
												if( isset($offerCodeSplit[1]) and $offerCodeSplit[1] == "1" ){
													$autoReplyMsg.="〰〰〰〰〰〰〰〰〰〰\n👉 Offer has been redeemed successfully.";
													$autoReplyMsg2 = "Offer code $offerCodeSplit[0] has been redeemed successfully.";
													// admin need to approve this offer in 1 shot, so we will update offer data
													DB::table($Customer->database.".campaign_statistics")->where( 'id', $offerData->id )->update(['state' => '1', 'updated_at' => $todayDateTime, 'a_id' => $adminData->id]);
												}else{
													$autoReplyMsg.="〰〰〰〰〰〰〰〰〰〰\n👉 To Redeem Offer Press 1";
												}
											}else{
												// offer limit has been reached
												$autoReplyMsg = "⚠ Offer limit has been reached.";
											}
											
										}elseif( $offerData->state == "1" ){
											$autoReplyMsg = "⚠ Offer used before \n At: $offerData->updated_at\n Redeemed By: ".DB::table( $Customer->database.".users" )->where('u_id',$offerData->u_id)->value('u_name')."\n By Admin: ".DB::table( $Customer->database.".admins" )->where('id',$offerData->a_id)->value('name');
										}
									}else{
										$autoReplyMsg = "❌ Invalid offer code.";
									}
								}else{
									// $autoReplyMsg = "❌ You don't have Permission to redeem loyality Points.";
								}							
							}
							
							// sending any output message of previous functions
							if(isset($autoReplyMsg)){

								// SENDING Hot reply to this Admin
								
								// Avoid any error
								if(!isset($buildWaitingAdminResponseMenu)){$buildWaitingAdminResponseMenu=null;}
								if(!isset($setWaitingAdminResponse)){$setWaitingAdminResponse=0;}

								//insert into there customer database
								$sentMsgID = DB::table($customerDB)->insertGetId([
									'send_receive' => '0'
									, 'server_mobile' => $serverMobile[0]
									, 'client_mobile'=> $clientMobile[0]
									, 'message' => $autoReplyMsg
									, 'pending_survey_id' =>$buildWaitingAdminResponseMenu
									, 'campaign_id' =>$setWaitingAdminResponse
									, 'created_at' => $todayDateTime]);
								// to mark sent message to know it when sent successfully from WA server
								if(isset($request->returnToDialogFlowChatAI)){
									DB::table($customerDB)->insert([['type' => $body->msg_type
									, 'msg_id' => $body->msg_id
									, 'sent' => '1', 'delivered' => '1', 'read' => '1', 'send_receive' => '1'
									, 'server_mobile' => $serverMobile[0]
									, 'client_mobile'=> $clientMobile[0]
									, 'message' => $receviedMessage
									, 'msg_time' => $msgTime
									, 'created_at' => $todayDateTime]]);	
									return $whatsappClass->returnToDialogFlowChatAI($Customer->database, $todayDateTime, $serverMobile[0], $clientMobile[0], $autoReplyMsg, $setWaitingAdminResponse, $buildWaitingAdminResponseMenu, $sentMsgID);
								}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								$response = $whatsappClass->sendWhatsapp($Customer->database, $todayDateTime, $serverMobile[0], $clientMobile[0], $autoReplyMsg, $setWaitingAdminResponse, $buildWaitingAdminResponseMenu, $sentMsgID);
								// avoid continue to user reply options
								$adminSentMessage = 1;
								// print(" $msg ");
								// check if will send reply another message to user
								
								if(isset($autoReplyMsg2)){
									sleep(1);
									$userMobile = DB::table($Customer->database.".users")->where( 'u_id', $offerData->u_id )->value('u_phone');
									if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($Customer->database, $todayDateTime, $serverMobile[0], $userMobile, $autoReplyMsg2,null,null, $sentMsgID);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
									$response = $whatsappClass->sendWhatsapp($Customer->database, $todayDateTime, $serverMobile[0], $userMobile, $autoReplyMsg2,null,null, $sentMsgID);
									// avoid continue to user reply options
									$adminSentMessage = 1;
									// print("<br> $msg ");
								}
							}
						}
					}
					// break;
				//}
			}
			// avoid continue to user reply options
			if(isset($adminSentMessage)){
				DB::table($customerDB)->insert([['type' => $body->msg_type
				, 'msg_id' => $body->msg_id
				, 'sent' => '1', 'delivered' => '1', 'read' => '1', 'send_receive' => '1'
				, 'server_mobile' => $serverMobile[0]
				, 'client_mobile'=> $clientMobile[0]
				, 'message' => $receviedMessage
				, 'msg_time' => $msgTime
				, 'created_at' => $todayDateTime]]);	
				return "Admin Sent Message, avoid continue to user reply options.";
			}
			
			////////////////////////////////////////////////////////////
			/////////// check if user reply by press code  /////////////
			////////////////////////////////////////////////////////////
			if (is_numeric($first3Char) and strlen($receviedMessage)<=2 ){
				
				////////////////////////////
				/// Admin Redeeming Offer by pressing 1
				if (isset($lastUserMessage) and strpos($lastUserMessage->message, 'To Redeem Offer Press 1') !== false and $receviedMessage == "1") { 
						
					$adminData = DB::table( $customerDBonly.".admins" )->where('mobile',$clientMobile[0])->first();
					$getOfferCodeFromPreviousMsg = DB::table($customerDB)->where('send_receive','1')->where('sent','1')->where('client_mobile',$clientMobile[0])->where('server_mobile',$serverMobile[0])->orderBy('id', 'desc')->first();
					$offerData=DB::table( $customerDBonly.".campaign_statistics" )->where( 'offer_code',$getOfferCodeFromPreviousMsg->message )->where('type','offer')->first();
					$campaignData = DB::table( $customerDBonly.".campaigns" )->where('id',$offerData->campaign_id)->first();

					if( $offerData->state == "0" ){// offer is available
						
						if(strlen($getOfferCodeFromPreviousMsg->message) == "8"){
							// this offer code to redeem loyalty program points, so we get there info from Table: 'loyalty_program'
							$loyaltyProgramData = DB::table( $customerDBonly.".loyalty_program" )->where('id',$offerData->campaign_id)->first();
							// to pass next step
							$remainingOffers="unlimited";
						}else{
							// that's mean the digits is 6 and this is normal offer code related to normal campaign, so we get there info from Table: 'campaigns'
							// check if there is a limit or not
							if($campaignData->offer_limit =="" or $campaignData->offer_limit=="0")
							{
								$remainingOffers="unlimited";
							}else{
								$redeemedOffers = DB::table($customerDBonly.".campaign_statistics")->where( 'type', 'offer' )->where( 'campaign_id', $campaignData->id )->where( 'state', '1' )->count();
								$remainingOffers=$campaignData->offer_limit-$redeemedOffers;
								// if($campaignData->state == "1"){ $campaignData->state = "✅"; }else{ $campaignData->state = "🔴"; }
							}
						}
						// check if the is avilable offer before limit finish
						if( $remainingOffers == "unlimited" or $remainingOffers >=1 ){

							// user data
							$userData = DB::table( $customerDBonly.".users" )->where('u_id',$offerData->u_id)->first();
							// offer informations
							// $autoReplyMsg = "✅ *Valid Offer* \n 🔘For: $userData->u_name\n 🔘Mobile: *******".substr($userData->u_phone, -4)."\n 🔘Creation date: $offerData->created_at\n 🔘Campaign state: $campaignData->state \n 🔘Campaign name: $campaignData->campaign_name \n 🔘Offer Description: $campaignData->offer_desc \n 🔘Offer Terms: $campaignData->offer_terms \n 🔘Offer Message: $campaignData->offer_sms_message \n 🔘Remaining Offers: $remainingOffers \n";
							$autoReplyMsg = "👉 Offer code $getOfferCodeFromPreviousMsg->message has been redeemed successfully \n by: $adminData->name.";
							$autoReplyMsg2 = "✅ Offer code $getOfferCodeFromPreviousMsg->message has been redeemed successfully.";

							DB::table($customerDBonly.".campaign_statistics")->where( 'id', $offerData->id )->update(['state' => '1', 'updated_at' => $todayDateTime, 'a_id' => $adminData->id]);
							
						}else{
							// offer limit has been reached
							$autoReplyMsg = "⚠ offer limit has been reached \n at:$offerData->updated_at\n by:".DB::table( $customerDBonly.".admins" )->where('id',$offerData->a_id)->value('name');
						}
					}elseif( $offerData->state == "1" ){
						$autoReplyMsg = "⚠ offer used before \n At:$offerData->updated_at\n By:".DB::table( $customerDBonly.".admins" )->where('id',$offerData->a_id)->value('name');
					}
					
					// SENDING Hot reply to this Admin
					$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $autoReplyMsg);
					
					// check if will send reply another message to user
					if(isset($autoReplyMsg2)){
						sleep(1);
						$userMobile = DB::table($customerDBonly.".users")->where( 'u_id', $offerData->u_id )->value('u_phone');
						if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $userMobile, $autoReplyMsg2,null,null);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
						$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $userMobile, $autoReplyMsg2,null,null);
					}
					// avoid continue to user reply options
					return "Redeeming offer code has been done";
				}	
				///////////////////////////////////////////
				/// if user reply on survey or bot campaign
				if ( (isset($lastUserMessage) and $lastUserMessage->campaign_id!="0" and $lastUserMessage->campaign_id!="999999999") or (isset($landBot)) ){
					
					// get campaign data 
					$campaignData = DB::table("$customerDBonly.campaigns")->where('id',$lastUserMessage->campaign_id)->first();
					// get user ID
					$userData = DB::table( $customerDBonly.".users" )->where('u_phone', 'like', '%'.$clientMobile[0].'%')->first();
					$userID = $userData->u_id;

					// if survey
					if(isset($campaignData) and $campaignData->type == "survey"){
						
						// I disable check if ( received servey before ) function because it conflict with surveys have guest comment ON
						// user receive "⚠ You have already filled this survey before." after filling the first survey
						// because the system already insert a record in "survey" table teh userID and campaignID after choose "I'm not happy"
						/*
						// check if user take this offer before according to whatsapp_repeat_survey DAYS
						if( $campaignData->whatsapp_repeat_survey == "" or $campaignData->whatsapp_repeat_survey == "0" ){
							$checkIfTaken = DB::table("$customerDBonly.survey")->where(['u_id'=>$userID ,'campaign_id'=>$campaignData->id])->count();
						}else{
							$subDays = date('Y-m-d H:i:s', strtotime("-12 hours", strtotime($todayDateTime))); 
							$checkIfTaken = DB::table("$customerDBonly.survey")->where(['u_id'=>$userID ,'campaign_id'=>$campaignData->id])->whereBetween('created_at', [$subDays, $todayDateTime])->count();
						}
						*/
						$checkIfTaken = 0;

						// Rate survey
						// make sure user respond between 1 & 5
						if( $campaignData->survey_type == "rating" and ( $receviedMessage >=1 and $receviedMessage <=5 ) ) {
							// mark it as a correct respond from user to avoid resending message again to the same user
							$userReply = 1;
							// avoid any decimal numbers like 1.5
							$receviedMessage = $receviedMessage[0];
							// user didnt receive this survey yet
							if($checkIfTaken == 0){
								// insert rate into survey DB
								DB::table("$customerDBonly.survey")->insert([['campaign_id' => $campaignData->id, 'options' => $receviedMessage, 'u_id' => $userID, 'created_at' => $todayDateTime]]);
								DB::table("$customerDBonly.history")->insert([['operation' => 'campaigns_survey_rating', 'details' => $campaignData->id, 'type2' => $receviedMessage, 'u_id' => $userID, 'notes' => 'campaigns', 'type1' => 'hotspot', 'add_date' => $today, 'add_time' => $today_time]]);
								// check if system will reply on this message
								$optionIdOfSystemReply = DB::table("$customerDBonly.survey")->where('campaign_id',$campaignData->id)->where('options', $receviedMessage)->whereNull('u_id')->first();
							}
						}
						// POOL survey
						// make sure user respond between 1 & 5
						if( $campaignData->survey_type == "poll" and ( $receviedMessage >=1 and $receviedMessage <=50 ) ) {
							
							// user didnt receive this survey yet
							if($checkIfTaken == 0){
								// get pool option order number
								$surveyOrderNumber = 1;
								foreach( DB::table("$customerDBonly.survey")->where('campaign_id', $campaignData->id)->whereNull('u_id')->whereNotNull('options')->get() as $surveyOption ){
									if($receviedMessage == $surveyOrderNumber){
										$optionID = $surveyOption->id;
									}
									$surveyOrderNumber++;
								}
								if(isset($optionID)){
									// mark it as a correct respond from user to avoid resending message again to the same user
									$userReply = 1;
									// insert vote into survey DB
									DB::table("$customerDBonly.survey")->insert([['options' => $optionID, 'campaign_id' => $campaignData->id, 'u_id' => $userID, 'created_at' => $todayDateTime]]);
									DB::table("$customerDBonly.history")->insert([['operation' => 'campaigns_survey_poll', 'details' => $campaignData->id, 'type2' => $optionID, 'u_id' => $userID, 'notes' => 'campaigns', 'type1' => 'hotspot', 'add_date' => $today, 'add_time' => $today_time]]);
									// check if system will reply on this message
									$optionIdOfSystemReply = DB::table("$customerDBonly.survey")->where('id',$optionID)->first();
								}
							}
						}

						// if user filled this survey before
						if($checkIfTaken > 0){
							// SENDING
							$surveyAlreadyTakenBefore = "⚠ You have already filled this survey before.";
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $surveyAlreadyTakenBefore);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $surveyAlreadyTakenBefore);
						}
					}
					
					// check if user click to call_staff
					elseif(isset($landBot) and DB::table("$customerDBonly.survey")->where('campaign_id',$landBot->id)->where('id', $lastUserMessage->pending_survey_id)->value('call_staff') == "1" ){
						
						// mark it as a correct respond from user to avoid resending message again to the same user
						$userReply = 1;	
						
						// check if we can avoid WiFi login restriction
						$avoidWiFiWhenCallStaff = DB::table("$customerDBonly.settings")->where('type', 'avoidWiFiWhenCallStaff')->value('state');
						// check if user online or offline
						$currSession = DB::table($customerDBonly.".radacct")->where('u_id',$userID)->whereNull('acctstoptime')->first();						
						// deside which way to get branch ID (from online session, or just first active branch)
						if(isset($currSession)){ $branchIdFromOnlineSessionOfUserOrfirstBranchID = $currSession->branch_id; }
						if($avoidWiFiWhenCallStaff == "1"){ $branchIdFromOnlineSessionOfUserOrfirstBranchID = DB::table($customerDBonly.".branches")->where('state','1')->value('id'); }

						if(isset($currSession) or $avoidWiFiWhenCallStaff == "1"){
							// return "// online"; 
							
							// send Notification to Admin
							if($avoidWiFiWhenCallStaff == "1"){
								$callStaffReplyMsg = "📞 New Order \n";
								$callStaffReplyMsg.="🛎 Customer Answer: $receviedMessage 👇\n";
							}else{
								$callStaffReplyMsg = "🛎 Staff request for table no: $receviedMessage 👇\n";
							}
							
							$callStaffReplyMsg.= "🛎 in Branch: ".DB::table("$customerDBonly.branches")->where('id', $branchIdFromOnlineSessionOfUserOrfirstBranchID)->value('name')."\n";
							$callStaffReplyMsg.= $whatsappClass->getAllCustomerInfoToAdmin($customerDBonly, $userID, $todayDateTime, '0' );
							$allAndAvilableLoyaltyProgram = $whatsappClass->getAllAndAvilableLoyaltyProgram($customerDBonly, $userID, $todayDateTime);
							if(isset($allAndAvilableLoyaltyProgram['available']) and $allAndAvilableLoyaltyProgram['available']!=null){
								$callStaffReplyMsg.="〰〰〰〰〰〰〰\n Available Loyalty Program \n";
								$callStaffReplyMsg.= $allAndAvilableLoyaltyProgram['available'];	
							}

							// get all staff
							$staffCount = 0;
							foreach(DB::table( $customerDBonly.".admins" )->where('permissions', 'like', '%WAredeemPoints%')->get() as $staff){
								$staffCount++;
								// check if this waiter is online in this branch or not
								// check which admin is online or we will avoidWiFiWhenCallStaff
								if (DB::table($customerDBonly.".radacct")->where('u_id', DB::table( $customerDBonly.".users" )->where('u_phone', 'like', '%'.$staff->mobile.'%')->value('u_id') )->where('branch_id', $branchIdFromOnlineSessionOfUserOrfirstBranchID)->whereNull('acctstoptime')->count() > 0 or $avoidWiFiWhenCallStaff == "1"){
									// Admin is Online
									$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $staff->mobile, $callStaffReplyMsg);
								}
							}
							
							// send Notification to Customer
							// get call staff success message
							if($staffCount>0){
								$callStaffSuccessMsg = DB::table($customerDBonly.".survey")->where('id',$lastUserMessage->pending_survey_id)->value('call_staff_success_msg');
							}else{$callStaffSuccessMsg = "Sorry, service not available at moment \n Please try again later.";}
							// Sending
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $callStaffSuccessMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $callStaffSuccessMsg);
						}else{
							// return "// offline"; 
							// get call staff success message
							$loginToWifiMsg = DB::table($customerDBonly.".survey")->where('id',$lastUserMessage->pending_survey_id)->value('login_to_wifi_msg');
							// Sending
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $loginToWifiMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $loginToWifiMsg);
						}
					}
					
					// if there is any (whatsappBot) menu
					elseif(isset($campaignData) and $campaignData->type == "whatsappBot"){
						// if user need to go back
						if($receviedMessage == "0"){ 
							
							// going back step by step // running on my mobile only
							$secondLastUserMessage = DB::table($customerDB)->where('send_receive','0')->where('sent','1')->where('client_mobile',$clientMobile[0])->where('server_mobile',$serverMobile[0])->where('id', '<', $lastUserMessage->id)->where('campaign_id', $campaignData->back_campaign_id)->whereNull('pending_survey_id')->orderBy('id', 'desc')->first();
							if(isset($secondLastUserMessage)){
								if($secondLastUserMessage->campaign_id == $landBot->id){// this user return to menu
									if(isset($request->returnToDialogFlowChatAI)){ return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0],1);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
									return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0]);
								}else{// this user return to any other campaign
									if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $secondLastUserMessage->message,$secondLastUserMessage->campaign_id,null);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
									return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $secondLastUserMessage->message,$secondLastUserMessage->campaign_id,null); 
								}
							}else{ 
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], "🔙","",null);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], "🔙","",null); 
							}

							// Running on all but going back to main menu directly not step by step
							// if(isset($landBot->id)){
							// 	$secondLastUserMessage = DB::table($customerDB)->where('send_receive','0')->where('sent','1')->where('client_mobile',$clientMobile[0])->where('server_mobile',$serverMobile[0])->where('campaign_id', $landBot->id)->orderBy('id', 'desc')->first();
							// 	if(isset($secondLastUserMessage)){
							// 		return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $secondLastUserMessage->message,$secondLastUserMessage->campaign_id,null);
							// 	}else{
							// 		return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], "Back to Menu");	
							// 	}
							// }else{						
							// 	return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], "Back to Menu");
							// }
						}
						// check if user reply on any this campain options
						$whatsappBotMenu = 1;
						$optionIdOfSystemReply = DB::table("$customerDBonly.survey")->where('campaign_id',$lastUserMessage->campaign_id)->where('options', $receviedMessage)->whereNull('u_id')->first();
					}
					
					// if whatsappFirstBot
					elseif(isset($landBot)){
						// check if user reply on any this campaign options
						$optionIdOfSystemReply = DB::table("$customerDBonly.survey")->where('campaign_id',$landBot->id)->where('options', $receviedMessage)->whereNull('u_id')->first();
					}
					
					////////////////////////////////////////////////
					//// check if system will reply on this message AND make sure we didnt wait user input for whatsappPay
					if(isset($optionIdOfSystemReply) and  DB::table("$customerDBonly.survey")->where('id',$lastUserMessage->pending_survey_id)->value('whatsappPay') == 0){
						// mark it as a correct respond from user to avoid resending message again to the same user
						$userReply = 1;	
						
						// check if this whatsappReferralInvitee
						if($optionIdOfSystemReply->whatsapp_referral_invitee == "1"){
							// check if user invited before or not
							if(DB::table("$customerDBonly.history")->where('operation','whatsappReferralInvitationSuccess')->where('u_id',$userData->u_id)->count() > 0 ){
								// invited before
								$replyMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitedAskAfterInvitationMsg')->value('value');
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $replyMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								$sedingResult = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $replyMsg);
								
							}else{
								// not invited before
								$replyMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitedAskBeforeInvitationMsg')->value('value');
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $replyMsg, $landBot->id, $optionIdOfSystemReply->id);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								$sedingResult = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $replyMsg, $landBot->id, $optionIdOfSystemReply->id);
							}
						}

						elseif($optionIdOfSystemReply->whatsapp_referral_inviter == "1"){
							
							// get first message
							$replyMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInviterMsg')->value('value');
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $replyMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$sedingResult = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $replyMsg);
							// get forward message
							$whatsappReferralInvitationForwardMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappReferralInvitationForwardMsg')->value('value');
							$whatsappReferralInvitationForwardMsg = @str_replace("@name","$userData->u_name",$whatsappReferralInvitationForwardMsg);
							// remove country code
							if( $userData->u_phone[0] == "2" ){$userData->u_phone = substr($userData->u_phone, 1);}
							elseif( mb_substr($userData->u_phone, 0, 3) == "966"){$userData->u_phone = substr($userData->u_phone, 3);}
							$whatsappReferralInvitationForwardMsg = @str_replace("@mobile", $userData->u_phone, $whatsappReferralInvitationForwardMsg);
							// SENDING
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappReferralInvitationForwardMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappReferralInvitationForwardMsg);
						
						}
						elseif($optionIdOfSystemReply->is_reply == "1"){
							$systemWillReplyOnMessage = 1;
							// check if offer system is on and have offer limit is avilable
							if($optionIdOfSystemReply->is_offer == "1"){
								// check offer limit
								if($campaignData->offer_limit == "0" or $campaignData->offer_limit==""){
									$giveOffer = 1;
								}elseif(DB::table("$customerDBonly.campaign_statistics")->where('type', 'offer')->where('campaign_id', $campaignData->id)->count() < $campaignData->offer_limit){
									$giveOffer = 1;
								}else{
									$giveOffer = 0;
								}
								// insert offer code if offer limit is avilable
								if($giveOffer == "1"){
									$newOfferCode = rand(100000, 999999);
									DB::table("$customerDBonly.campaign_statistics")->insert([['type' => 'offer', 'campaign_id' => $campaignData->id, 'u_id' => $userID, 'state' => '0', 'offer_code' => $newOfferCode, 'created_at' => $todayDateTime]]);
									$optionIdOfSystemReply->reply_message.="\n $campaignData->offer_desc";
									$optionIdOfSystemReply->reply_message.="\n 🎁 Offer Code: $newOfferCode";
								}
							}

							// if there is a whatsappBot menu insert there id to go forward into deeb menu
							// DB::table("$customerDBonly.survey")->whereNotNull('next_campaign_id')->where('options', $receviedMessage)->whereNull('u_id')->count() == 1
							if(isset($optionIdOfSystemReply->next_campaign_id) ){ $whatsappBotMenu = 1; $nextCampaignID = $optionIdOfSystemReply->next_campaign_id;}
							else{ $nextCampaignID = null;}
							
							// // SENDING
							// //insert into there customer database
							$sentMsgID = DB::table($customerDB)->insertGetId([
								'send_receive' => '0'
								, 'server_mobile' => $serverMobile[0]
								, 'client_mobile'=> $clientMobile[0]
								, 'message' => $optionIdOfSystemReply->reply_message
								, 'campaign_id'=> $nextCampaignID
								, 'created_at' => $todayDateTime]);
							// to mark sent message to know it when sent successfully from WA server
							if(isset($request->returnToDialogFlowChatAI)){echo $response = $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $optionIdOfSystemReply->reply_message,null,null,$sentMsgID);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							else{$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $optionIdOfSystemReply->reply_message,null,null,$sentMsgID);}

							// check if user click to call_staff
							if($optionIdOfSystemReply->call_staff == "1"){
								$waitUserReply=1;
								DB::table($customerDB)->where( 'id', $sentMsgID )->update([ 'campaign_id' => $optionIdOfSystemReply->campaign_id, 'pending_survey_id' => $optionIdOfSystemReply->id]);
							}
								
						}
						
						// check if user click to view_loyalty_program
						if($optionIdOfSystemReply->view_loyalty_program == "1"){
							
							// get AskForLoyaltyProgram message
							$viewLoyaltyProgramMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappUserAskForLoyaltyProgram')->value('value');
							// convery loyality points
							$loyaltyPoints = $whatsappClass->getCustomerLoyaltyPoints($customerDBonly,$userID,$todayDateTime);
							$viewLoyaltyProgramMsg = @str_replace("@points","$loyaltyPoints",$viewLoyaltyProgramMsg);
							// get lowest loilty program points to know if customer have away to redeem or not
							$lowestLoyaltyProgramsPoints = DB::table("$customerDBonly.loyalty_program")->where('state', '1')->where('row_type', '1')->orderBy('points', 'asc')->value('points');
							// get customer loyalty programs
							$allAndAvilableLoyaltyProgram = $whatsappClass->getAllAndAvilableLoyaltyProgram($customerDBonly, $userID, $todayDateTime);
							// convert all_loyalty_programs text message
							$viewLoyaltyProgramMsg = @str_replace("@all_loyalty_programs",$allAndAvilableLoyaltyProgram['all'],$viewLoyaltyProgramMsg);
							// convert all_loyalty_programs text message
							if($allAndAvilableLoyaltyProgram['available']!=null){ $viewLoyaltyProgramMsg = @str_replace("@available_loyalty_programs",$allAndAvilableLoyaltyProgram['available'],$viewLoyaltyProgramMsg); }
							else{$viewLoyaltyProgramMsg = @str_replace("@available_loyalty_programs","till now nothing😳!",$viewLoyaltyProgramMsg);}
							// convert @id to get global ID
							$globalID = DB::table('users_global')->where('mobile', 'like', '%'.$clientMobile[0].'%')->value('id');
							$viewLoyaltyProgramMsg = @str_replace("@id","$globalID",$viewLoyaltyProgramMsg);							
							
							// before give user apility to redeem his points, we need to make sure the customer have at least 1 program to redeem it
							if($loyaltyPoints >= $lowestLoyaltyProgramsPoints){
								// make user able to redeem there loyalty program
								// to avoid inserting any record after this record to be able to detect options
								$waitAdminReply = 1;
								// avoid continue to user reply options
								$adminSentMessage = 1;
								// buildWaitingAdminResponseMenu
								$setWaitingAdminResponse = "999999999";
								$buildWaitingAdminResponseMenu = $allAndAvilableLoyaltyProgram['buildWaitingAdminResponseMenu'];	
							}else{
								$setWaitingAdminResponse = null;
								$buildWaitingAdminResponseMenu = null;	
							}
							
							//insert into there customer database
							$sentMsgID = DB::table($customerDB)->insertGetId([
								'send_receive' => '0'
								, 'server_mobile' => $serverMobile[0]
								, 'client_mobile'=> $clientMobile[0]
								, 'message' => $viewLoyaltyProgramMsg
								, 'pending_survey_id' =>$buildWaitingAdminResponseMenu
								, 'campaign_id' =>$setWaitingAdminResponse
								, 'created_at' => $todayDateTime]);
							// to mark sent message to know it when sent successfully from WA server
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $viewLoyaltyProgramMsg, $setWaitingAdminResponse, $buildWaitingAdminResponseMenu, $sentMsgID);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $viewLoyaltyProgramMsg, $setWaitingAdminResponse, $buildWaitingAdminResponseMenu, $sentMsgID);
																		
							// $response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $viewLoyaltyProgramMsg);
						}

						// check if user click for whatsapp pay
						if($optionIdOfSystemReply->whatsappPay == "1"){
							// to avoid inserting any record after the following record to be able to handel this option 
							$waitUserReply = 1;
							// get AskForLoyaltyProgram message
							$whatsappPayEnterAmountMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappPayEnterAmountMsg')->value('value');
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappPayEnterAmountMsg, $optionIdOfSystemReply->campaign_id, $optionIdOfSystemReply->id);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappPayEnterAmountMsg, $optionIdOfSystemReply->campaign_id, $optionIdOfSystemReply->id);
						}
						
						// check if user click to enter there Birthdate
						if($optionIdOfSystemReply->birthdaysCelebrationOffer == "1"){
							// to avoid inserting any record after the following record to be able to handel this option 
							$waitUserReply = 1;
							// check if birthdate is exist or not
							if($userData->birthdate == null){
								// birthdate empty
								$whatsappEnterBirthdateMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappEnterBirthdateMsg')->value('value');
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappEnterBirthdateMsg, $optionIdOfSystemReply->campaign_id, $optionIdOfSystemReply->id);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappEnterBirthdateMsg, $optionIdOfSystemReply->campaign_id, $optionIdOfSystemReply->id);
							}else{
								// birthdate already exist
								$whatsappBirthdateAlreadyEnterdMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappBirthdateAlreadyEnterdMsg')->value('value');
								$whatsappBirthdateAlreadyEnterdMsg = @str_replace("@name","$userData->u_name",$whatsappBirthdateAlreadyEnterdMsg);
								$whatsappBirthdateAlreadyEnterdMsg = @str_replace("@birthdate","$userData->birthdate",$whatsappBirthdateAlreadyEnterdMsg);
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappBirthdateAlreadyEnterdMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappBirthdateAlreadyEnterdMsg);
							}
						}

						// check if we are waiting user reply
						if($optionIdOfSystemReply->is_reply_after_user_reply == "1"){
							// print(" $sentMsgID ");
							// add flag on this record in "whatsapp" table 
							DB::table("$customerDBonly.whatsapp")->where( 'id', $sentMsgID )->update(['pending_survey_id' => $optionIdOfSystemReply->id, 'campaign_id' => $campaignData->id]);
						}else{
							
							// check if we will send_user_reply_to_admin_wa
							if($optionIdOfSystemReply->send_user_reply_to_admin_wa == "1"){
								// enable the following variable to auto run (check if we will send_user_reply_to_admin_wa) function
								$send_user_reply_to_admin_wa = 1;
							}
							// switched the 'next_campaign_id' from 'campaign' table to 'survey' table
							// // check if there is next survey campaign
							// if(isset($campaignData->next_campaign_id) and $campaignData->next_campaign_id !=""){
							// 	// get next campaign data
							// 	// return "there is next survey campaign $campaignData->next_campaign_id ";
							// 	$nextCampaignData = DB::table("$customerDBonly.campaigns")->where('id',$campaignData->next_campaign_id)->first();
							// }
							// check if there is next survey campaign
							if( isset($campaignData) and $campaignData->type=="survey" and isset($optionIdOfSystemReply->next_campaign_id) and $optionIdOfSystemReply->next_campaign_id !=""){
								// get next campaign data
								$nextCampaignData = DB::table("$customerDBonly.campaigns")->where('id',$optionIdOfSystemReply->next_campaign_id)->first();
							}
						}
					}
					
				}
				
			}
			
			////////////////////////////////////////////////////////////
			/////////// check if we are waiting user reply  ////////////
			////////////////////////////////////////////////////////////
			// to make sure system will not take any action till the next message
			
			if(!isset($systemWillReplyOnMessage)){
				// make sure the last message is reply
				if(isset($lastUserMessage) and isset($lastUserMessage->pending_survey_id) and isset($lastUserMessage->campaign_id) and $lastUserMessage->campaign_id!="999999999" ){
					
					$waitUserReply = 1;
					// get survey campaign data
					$surveyCampaignData = DB::table( $customerDBonly.".campaigns" )->where('id', $lastUserMessage->campaign_id)->first();
					// get survey option data
					$surveyOptionData = DB::table("$customerDBonly.survey")->where( 'id', $lastUserMessage->pending_survey_id )->first();
					// get user data
					if(!isset($userData)){$userData = DB::table( $customerDBonly.".users" )->where('u_phone', 'like', '%'.$clientMobile[0].'%')->first();}
					
					if(isset($surveyOptionData->whatsappPay) and $surveyOptionData->whatsappPay == "1" ){
						if(DB::table("$customerDBonly.history")->where(['operation'=>'whatsappPayRememberAmountTemp', 'u_id'=>$userData->u_id, 'add_date'=>$today ])->count() == "0")
						{
							// insert temp record to remember amount
							DB::table("$customerDBonly.history")->insert([['operation' => 'whatsappPayRememberAmountTemp', 'details' => '', 'type2' => $lastUserMessage->pending_survey_id, 'u_id' => $userData->u_id, 'notes' => $receviedMessage, 'type1' => 'hotspot', 'add_date' => $today, 'add_time' => $today_time]]);
							// send second message (enter table no)
							$whatsappPayEnterTableNoMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappPayEnterTableNoMsg')->value('value');
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappPayEnterTableNoMsg, $surveyOptionData->campaign_id, $surveyOptionData->id);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappPayEnterTableNoMsg, $surveyOptionData->campaign_id, $surveyOptionData->id);	
						}else{
							// get amount from temp record
							$amount = DB::table("$customerDBonly.history")->where(['operation'=>'whatsappPayRememberAmountTemp', 'u_id'=>$userData->u_id, 'add_date'=>$today ])->value('notes');
							// send inbrogress message
							// set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							if(!isset($request->returnToDialogFlowChatAI)){ $response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], "⏳ in progress..."); }
							// get payment codes
							$whatsappPayFawryState = DB::table("$customerDBonly.settings")->where('type', 'whatsappPayFawryState')->value('state');
							$whatsappPayVisaState = DB::table("$customerDBonly.settings")->where('type', 'whatsappPayVisaState')->value('state');
							$whatsappPayWalletState =  DB::table("$customerDBonly.settings")->where('type', 'whatsappPayWalletState')->value('state');
							$whatsappPayCurrency = DB::table("$customerDBonly.settings")->where('type', 'whatsAppPayCurrency')->value('value');
							if(!isset($whatsappPayCurrency) or $whatsappPayCurrency == ""){$whatsappPayCurrency = "EGP";}
							$paymentResponse = $whatsappClass->pay('enduser', $customerID, DB::table("$customerDBonly.settings")->where('type', 'app_name')->value('value'), $userData->u_id, $clientMobile[0], $userData->u_email, $amount, $whatsappPayCurrency, $whatsappPayFawryState, $whatsappPayVisaState, $whatsappPayWalletState, $receviedMessage);
							// remove temp record
							DB::table("$customerDBonly.history")->where(['operation'=>'whatsappPayRememberAmountTemp', 'u_id'=>$userData->u_id ])->delete(); 
							// make sure links and codes is recevied correctly
							if(isset($paymentResponse['fawry']) and isset($paymentResponse['visa']) and isset($paymentResponse['wallet'])){
								// make sure variables is not empty
								if( ($whatsappPayVisaState=="1" and $paymentResponse['visa']=="") or ($whatsappPayWalletState=="1" and $paymentResponse['wallet']=="") ){
									// send error message
									if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappPayErrorMsg')->value('value'));}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
									$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappPayErrorMsg')->value('value'));
								}else{
									// send final message (your code is)
									$whatsappPayFinishMsg = DB::table("$customerDBonly.settings")->where('type', 'whatsappPayFinishMsg')->value('value');
									$whatsappPayFinishMsg = @str_replace("@points","$loyaltyPoints",$whatsappPayFinishMsg);
									$whatsappPayFinishMsg = @str_replace("@fawry",$paymentResponse['fawry'],$whatsappPayFinishMsg);
									$whatsappPayFinishMsg = @str_replace("@visa",$paymentResponse['visa'],$whatsappPayFinishMsg);
									$whatsappPayFinishMsg = @str_replace("@wallet",$paymentResponse['wallet'],$whatsappPayFinishMsg);
									if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappPayFinishMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
									$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $whatsappPayFinishMsg);
								}
							}else{// send error message
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappPayErrorMsg')->value('value'));}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappPayErrorMsg')->value('value'));
							}
						}
					}
					elseif($surveyOptionData->birthdaysCelebrationOffer == "1" ){
						// check if values is true
						$enterdBirthdate = @explode(" ",$receviedMessage);
						if(isset($enterdBirthdate[0]) and isset($enterdBirthdate[1]) and isset($enterdBirthdate[2]) ){
							if($enterdBirthdate[0] >=1 and $enterdBirthdate[0] <= 31 and $enterdBirthdate[1] >=1 and $enterdBirthdate[1]<=12 and $enterdBirthdate[2]>=1930 and $enterdBirthdate[2]<=2020){
								//update birthdate into user record
								DB::table($customerDBonly.".users")->where( 'u_id', $userData->u_id )->update(['birthdate' => $enterdBirthdate[2]."-".$enterdBirthdate[1]."-".$enterdBirthdate[0]]);
								if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappBirthdateSuccessMsg')->value('value'));}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
								return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappBirthdateSuccessMsg')->value('value'));	
							}
						}
						// if we reached to this line thats means there is an error
						if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappBirthdateFailMsg')->value('value'), $surveyOptionData->campaign_id, $surveyOptionData->id);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
						return $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappBirthdateFailMsg')->value('value'), $surveyOptionData->campaign_id, $surveyOptionData->id);	
					}else{
						// get survey result from survey DB
						$surveyResult = DB::table("$customerDBonly.survey")->where('id', $lastUserMessage->pending_survey_id)->value('options');

						// insert user reply into History to view it in survey dashboard
						DB::table("$customerDBonly.history")->insert([['operation' => 'whatsapp_survey_user_reply', 'details' => $surveyCampaignData->id, 'type2' => $lastUserMessage->pending_survey_id, 'u_id' => $userData->u_id, 'notes' => $receviedMessage, 'type1' => 'hotspot', 'add_date' => $today, 'add_time' => $today_time]]);
							
						// check if we will reply after this user reply
						if($surveyOptionData->is_reply_after_user_reply == "1"){
							if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $surveyOptionData->reply_message_after_user_reply);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
							$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $surveyOptionData->reply_message_after_user_reply);
						}
					}
					// switched the 'next_campaign_id' from 'campaign' table to 'survey' table
					// // check if there is next survey campaign
					// if(isset($surveyCampaignData->next_campaign_id) and $surveyCampaignData->next_campaign_id !=""){
					// 	// get next campaign data
					// 	$nextCampaignData = DB::table("$customerDBonly.campaigns")->where('id',$surveyCampaignData->next_campaign_id)->first();
					// }
					// check if there is next survey campaign
					if( isset($surveyCampaignData) and $surveyCampaignData->type=="survey" and isset($surveyOptionData->next_campaign_id) and $surveyOptionData->next_campaign_id !=""){
						// get next campaign data
						$nextCampaignData = DB::table("$customerDBonly.campaigns")->where('id',$surveyOptionData->next_campaign_id)->first();
					}


				
				}
			}
			////////////////////////////////////////////////////////////
			////// check if we will send_user_reply_to_admin_wa  ///////
			////////////////////////////////////////////////////////////
			// check if we will send_user_reply_to_admin_wa after (check if we are waiting user reply) with his function variables
				// $surveyCampaignData
				// $surveyOptionData
				// $userData
			// or we will send_user_reply_to_admin_wa after (check if system will reply on this message)
			if( (isset($surveyOptionData) and $surveyOptionData->send_user_reply_to_admin_wa == "1") or (isset($send_user_reply_to_admin_wa) and $send_user_reply_to_admin_wa == 1)){
				
				// if we will send_user_reply_to_admin_wa message after (//// check if system will reply on this message) directly
				if(isset($send_user_reply_to_admin_wa) and $send_user_reply_to_admin_wa == 1){
					// set variables and replace variables with (//// check if system will reply on this message) function
					$surveyOptionData = $optionIdOfSystemReply;
					$surveyCampaignData = $campaignData;
					$userData = DB::table( $customerDBonly.".users" )->where('u_id', $userID)->first();
					$surveyResult = $receviedMessage;
				}

				// build WhatsApp message
				$autoReplyMsg = "📨 Survey Result\n";
				$autoReplyMsg.= "❓ Survey Question: $surveyCampaignData->question \n";
				if($surveyCampaignData->survey_type == "poll"){
					// get pool option order number
					$surveyOrderNumber = 1;
					$autoReplyMsg.= "👉 Customer Answer: $surveyResult \n";
					// $autoReplyMsg.= "🔘 Survey answer type: Poll\n";
				}else{ // rating
					$autoReplyMsg.= "👉 Customer Answer: $surveyResult \n";
					// $autoReplyMsg.= "🔘 Survey answer type: rating 1:5\n";
				}
				if(!isset($send_user_reply_to_admin_wa)){
					$autoReplyMsg.= "🔘 Customer Message: $receviedMessage \n";
				}
				$autoReplyMsg.= "🔘 Survey Campaign Name: $surveyCampaignData->campaign_name \n";
				
				// get all user informations
				$autoReplyMsg.="〰〰〰〰〰〰〰\n";
				$autoReplyMsg.= $whatsappClass->getAllCustomerInfoToAdmin($customerDBonly, $userData->u_id, $todayDateTime, '1' );
				$allAndAvilableLoyaltyProgram = $whatsappClass->getAllAndAvilableLoyaltyProgram($customerDBonly, $userData->u_id, $todayDateTime);
				if(isset($allAndAvilableLoyaltyProgram['available']) and $allAndAvilableLoyaltyProgram['available']!=null){
					$autoReplyMsg.="〰〰〰〰〰〰〰\n Available Loyalty Program \n";
					$autoReplyMsg.= $allAndAvilableLoyaltyProgram['available'];	
				}
				
				// get All Whatsapp admins
				$whatsappAdmins = DB::table( $customerDBonly.".admins" )->where('permissions', 'like','%WAadmin%')->get();
				foreach($whatsappAdmins as $admin){

					// SENDING Hot reply 
					if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $admin->mobile, $autoReplyMsg);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $admin->mobile, $autoReplyMsg);
					sleep(1);
					// print("Mob: $admin->mobile response: $response ");
				}
			}
			
			//////////////////////////////////////////////////////////////////////
			/////////// check if user send wrong response on survey   ////////////
			//////////////////////////////////////////////////////////////////////
			// Resend Message again
			
			if( (isset($adminRequest) and $adminRequest==1) or (isset($waitUserReply) and $waitUserReply==1) or (isset($userReply) and $userReply==1) ){
				// do nothing
			}else{
				// check if we are waiting any response for survey or any campaign question
				if(isset($lastUserMessage->campaign_id) and $lastUserMessage->campaign_id != 0){
					// mark it as wrong Response to avoid the insert reply into DB
					
					$wrongResponse = 1;
					// SENDING Hot reply to this Admin
					// avoid sending if there is an AI bit 
					// set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					if(isset($request->returnToDialogFlowChatAI)){
						return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0],1);
					}else{
						$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappUserWrongResponse')->value('value')." \n \n".$lastUserMessage->message,null,null, $lastUserMessage->id);
					}
					
					///////////////////////////////////////////////////////////////////////////
					/////////// check if user send wrong response without survey   ////////////
					/////////// 	    and there is a landBot activated           ////////////
					///////////////////////////////////////////////////////////////////////////
				}elseif( isset($landBot) ){
					// send landbot menu

					// mark it as wrong Response to avoid the insert reply into DB
					$whatsappBotMenu = 1;
					// send unknown symbol message
					if($receviedMessage == "0" or $receviedMessage == "00" or $receviedMessage == "000" or $receviedMessage == "0000" or $receviedMessage == "00000"){ }
					else {
						$landBotMenu = DB::table("$customerDBonly.settings")->where('type', 'whatsappUserWrongResponse')->value('value');
						if(isset($request->returnToDialogFlowChatAI)){
							return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0],1);
							// return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappUserWrongResponse')->value('value'));
						}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
						$whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], DB::table("$customerDBonly.settings")->where('type', 'whatsappUserWrongResponse')->value('value'));
					}
					
					if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0],1);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0]);
				}
			}


			////////////////////////////////////////////////////////////
			////////// Check if there is next survey campaign //////////
			////////////////////////////////////////////////////////////
			if(isset($nextCampaignData)){
				// if campaign type survey
				if($nextCampaignData->type == "survey" and $nextCampaignData->whatsapp == "1" ){
				
					if($nextCampaignData->survey_type == "poll" ){
						foreach ( DB::table("$customerDBonly.survey")->where('campaign_id',$nextCampaignData->id)->whereNull('u_id')->get() as $option)
						{
							$nextCampaignData->question.="\n $option->options";
						}
					}
					// send whatsapp Message
					if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->returnToDialogFlowChatAI($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $nextCampaignData->question, $nextCampaignData->id);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
					$response = $whatsappClass->sendWhatsapp($customerDBonly, $todayDateTime, $serverMobile[0], $clientMobile[0], $nextCampaignData->question, $nextCampaignData->id);
						
					// insert this message into WhatsApp campaign table
					$userData = DB::table( $customerDBonly.".users" )->where('u_phone', 'like', '%'.$clientMobile[0].'%')->first();

					DB::table("$customerDBonly.whatsapp_campaign")->insert([['state' => '1', 'user_id' => $userData->u_id, 'campaign_id' => $nextCampaignData->id, 'created_at' => $todayDateTime]]);
					// add reach to survey campaign
					DB::table("$customerDBonly.campaign_statistics")->insert([['campaign_id' => $nextCampaignData->id, 'type' => "reach", 'u_id' => $userData->u_id, 'created_at' => $todayDateTime]]);
				}
			}

			//insert into there customer database
			if( (isset($wrongResponse) and $wrongResponse==1) or (isset($waitUserReply) and $waitUserReply == 1) or (isset($waitAdminReply) and $waitAdminReply == 1) or (isset($whatsappBotMenu) and $whatsappBotMenu == 1) ){
				// do nothing to avoid the insert reply into DB, to continue survey campaign or campaign question
			}else{
				DB::table($customerDB)->insert([['type' => $body->msg_type
				, 'msg_id' => $body->msg_id
				, 'sent' => '1', 'delivered' => '1', 'read' => '1', 'send_receive' => '1'
				, 'server_mobile' => $serverMobile[0]
				, 'client_mobile'=> $clientMobile[0]
				, 'message' => $receviedMessage
				, 'msg_time' => $msgTime
				, 'created_at' => $todayDateTime]]);	
			}

			// if user send 0 message, we will push whatsapp menu
			if($receviedMessage == "0" or $receviedMessage == "00" or $receviedMessage == "000" or $receviedMessage == "0000" or $receviedMessage == "00000"){
				if(isset($request->returnToDialogFlowChatAI)){return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0],1);}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
			return $whatsappClass->sendWhatsappMenu($customerDBonly, $customerID, $clientMobile[0], $todayDateTime, $serverMobile[0]);
			}
			
			
		
			// // insert received message into DB
			// DB::table("whatsapp")->insert([['type' => $body->msg_type
			// , 'customer_id' => $body->msg_id
			// , 'msg_id' => $body->msg_id
			// , 'sent' => '1', 'delivered' => '1', 'read' => '1', 'send_receive' => '1'
			// , 'server_mobile' => $serverMobile[0]
			// , 'client_mobile'=> $clientMobile[0]
			// , 'message' => $receviedMessage
			// , 'msg_time' => $msgTime
			// , 'created_at' => $todayDateTime]]);
			if(!isset($request->returnToDialogFlowChatAI)){print("1");}  // set Whstsapp class to return message here instead of sending direct to user, to make chatAI able to delever message to Telegram or any other channel (FB, Twitter, Skype, etc...)
			
			
			/////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////// Auto reply ////////////////////////////////
			/////////////////////////////////////////////////////////////////////////////////
			/*
			if(isset($autoReplyMsg) or isset($autoReplyMsg2)){
				// do nothing
			}else{
				// check if recevied not supported reply
				if (strpos($receviedMessage, '[audio=') !== false) { $noReply = 1;}
				elseif (strpos($receviedMessage, '[sticker=') !== false) { $noReply = 1;}
				elseif (strpos($receviedMessage, '[image=') !== false) { $noReply = 1;}
				elseif (strpos($receviedMessage, '[location=') !== false) { $noReply = 1;}
				elseif (strpos($receviedMessage, '[') !== false) { $noReply = 1;}
				else{$noReply = 0;}
				
				if( $noReply != 1 ){

					// check if message sent last 2 sec
					$lastSentID = DB::table($customerDB)->where('send_receive','0')->where('server_mobile',$serverMobile[0])->orderBy('id','desc')->first();
					if(isset($lastSentID)){
						$date1 = strtotime($lastSentID->created_at);
						$date2 = strtotime($todayDateTime);  
						$diff = abs($date2 - $date1);  
						if($diff < 2){
							sleep(2);
						}
					}
					
					//insert into there customer database
					$sentMsgID = DB::table($customerDB)->insertGetId([
						'send_receive' => '0'
						, 'server_mobile' => $serverMobile[0]
						, 'client_mobile'=> $clientMobile[0]
						, 'message' => $receviedMessage
						, 'created_at' => $todayDateTime]);
					// to mark sent message to know it when sent successfully from WA server
					$clientMobile[0] = $sentMsgID."@".$clientMobile[0]."_";
					// - message_sync_typing_send
					// - message_typing_send
					// - message_sync_send
					// - message send 
					// Send message
					$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/whatsapp";
					$receviedMessage = base64_encode($receviedMessage);
					// $msg = $serverMobileToken.','.$serverMobile[0].','.'/message_sync_send "'.$clientMobile[0].'" "'.$receviedMessage.'" ';
					$msg = $serverMobileToken.','.$serverMobile[0].','.'message_send,'.$clientMobile[0].' "'.$receviedMessage.'" ';
					$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
					$response = @file_get_contents($url, FALSE, $context);
					$find="1";
					if(strpos($response, $find) !== false){ // Sent Successfully
						return "Whatsapp Auto reply sent";
					}else{// not Sent
						return "Whatsapp Auto reply fail";
					}
				}
			}
			*/
		}

		//////////////////////////////////////////////////////////////////////////////////////
		///  YOWSUP  Msg sent from our whatsapp server to Whatsapp sys and give us Message ID // Webhook
		//////////////////////////////////////////////////////////////////////////////////////

		// Whatsapp server reply for each sent message from us,
		// to make sure all message sent from us is sent also from Whatsapp server
		if(isset($request->type) and $request->type == "server_received_msg"){
			/*
			examble
			POST:https://demo.microsystem.com.eg/api/whatsapp?type=server_received_msg
			Body:
				{ "local_msg_id": "123", "client_mobile": "201061030454@s.whatsapp.net", "msg_type": "  text", "msg_id": " 1571166517-1", "msg_time": "15-10-2019 19:08:37", "message": "message_attributes=[conversation=b'\\xd8\\xb6']", "server_mobile": "201553514351@s.whatsapp.net"}
			response
				1
			*/
			$body = @file_get_contents('php://input');
			$body = json_decode($body);
			$clientMobile = (explode("@",$body->client_mobile));
			$serverMobile = (explode("@",$body->server_mobile));
			
			$customerID = DB::table("whatsapp_token")->where('server_mobile', $serverMobile[0] )->value('customer_id');
			if($customerID == "0"){ // Hotspot system 
				$customerDB = "whatsapp";
			}else{
				$customerDB = DB::table("customers")->where('id', $customerID )->value('database');
				$customerDB = $customerDB.".whatsapp";
			}
			
			// remove spaces
			$clientMobile[0] = str_replace(" ","",$clientMobile[0]);
			$sentMessageID = str_replace(" ","",$body->msg_id);
			$msgType = str_replace(" ","",$body->msg_type);
			$allMessage = $body->message;
			if($allMessage){ $splitMessage1 = (explode("[",$allMessage)); }
			if(isset($splitMessage1[1])){ $splitMessage2 = (explode("=",$splitMessage1[1])); }
			if(isset($splitMessage2[1])){ $finalMessage = str_replace("]","",$splitMessage2[1]); }
			else{ $finalMessage = 0; }

			// for python3 remove b' '
			$splitMessage3 = explode("b'",$finalMessage);
			if(isset($splitMessage3[1])){ 
				$finalMessage = substr_replace($splitMessage3[1], "", -1); 
			}
			// $finalMessage = base64_decode($finalMessage);

			// return $finalMessage;
			if( $finalMessage!="0" ){
				$updateState = DB::table($customerDB)->where( 'id', $body->local_msg_id )->update(['type' => $msgType, 'msg_id' => $sentMessageID, 'sent' => 1, 'updated_at' => $todayDateTime ]);
			}
			// // test
			// $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			// $body = @file_get_contents('php://input');
			// DB::table("whatsapp")->insert([['type' => $updateState, 'message' => $finalMessage]]);
			return "Done server_received_msg";
		}

		////////////////////////////////////////////////////////////////////////////////////
		////////////////   Receive ACK from Whatsapp "deleverd", "read"  /////////////////// Webhook
		////////////////////////////////////////////////////////////////////////////////////

		// Whatsapp server ack for each sent message,
		// by deleverd OR seen
		// if request from Yowsup or chat-API.com or sent from mercury.chat
		if( (isset($request->type) and $request->type == "ack") or (isset($body->ack[0]->status)) or (isset($body->data->body) and $body->type == "message" and $body->data->fromMe == "1") ){
			/*
			examble
			GET:https://demo.microsystem.com.eg/api/whatsapp?type=ack&msg_client_mobile=201010746667&msg_id=1571240101-1&msg_state=None@server_mobile=201145929570
				- msg_state=None (delivered)
				- msg_state=None (read)
			response
				1
			*/
			if( isset($body->ack[0]->status) ){
				// from ChatAPI
				$serverMobile = DB::table("whatsapp_token")->where('chatapi_instance_id', $body->instanceId )->value('server_mobile');
				$clientMobile = (explode("@",$body->ack[0]->chatId))[0];
				$msgID = $body->ack[0]->id;
				$messageState = $body->ack[0]->status;
				if($messageState == "viewed"){ $messageState = "read";}
				elseif($messageState == "delivered"){ $messageState = "None";}
				else{ return "We already setted message to sent.";}

			}elseif( isset($body->event) and $body->event == "messages.update" and isset($body->data[0]->update->status) ){
				// from mikofi.com
				$serverMobile = DB::table("whatsapp_token")->where('chatapi_instance_id', $body->instance_id )->value('server_mobile');
				$clientMobile = (explode("@",$body->data[0]->key->remoteJid))[0];
				$msgID = $body->data[0]->key->id;
				$messageState = $body->data[0]->update->status;
				if($messageState == "4"){ $messageState = "read";}
				elseif($messageState == "3"){ $messageState = "None";}
				else{ return "We already setted message to sent.";}
			
			}elseif(isset($body->data->message_id) and $body->type == "message" and $body->data->fromMe == "1"){
				// Msg sent from mercury.chat, so we will save message ID and finish this function b return "DONE"
				$clientMobile = (explode("@",$body->data->chatId))[0];
				$serverMobile = (explode("@",$body->data->author))[0];
				$customerID = DB::table("whatsapp_token")->where('server_mobile', $serverMobile )->value('customer_id');
				$customerDB = DB::table("customers")->where('id', $customerID )->value('database');
				// get last record inserted for this user
				$lastRecordID = DB::table($customerDB.".whatsapp")->where('msg_id', '0')->where('client_mobile', $clientMobile)->where('send_receive', '0')->orderBy('id', 'desc')->first();
				DB::table($customerDB.".whatsapp")->where('id', $lastRecordID->id)->update([ 'msg_id' => $body->data->message_id, 'updated_at' => $todayDateTime]);
				return "Msg ID has been assigned successfully.";
			}elseif( isset($body->data->instance_number) and $body->data->status_sent == 1 ){
				// deleverd or read from mercury.chat 	
				$clientMobile = (explode("@",$body->data->chatId))[0];
				$serverMobile = (explode("@",$body->data->author))[0];
				$msgID = $body->data->message_id;
				$messageState = "";
				if($body->data->status_viewed == 1){ $goRead=1; }
				if($body->data->status_delivered == 1){ $goDeleverd=1; }
			}else{
				// from Yowsup
				$serverMobile = (explode("@",$request->server_mobile))[0];
				if( !isset($serverMobile[0]) ){ $serverMobile[0] = $request->server_mobile; }
				$clientMobile = $request->msg_client_mobile;
				$msgID = $request->msg_id;
				$messageState = $request->msg_state;
			}
			
			$customerID = DB::table("whatsapp_token")->where('server_mobile', $serverMobile )->value('customer_id');
			if($customerID == "0"){ // Hotspot system 
				$customerDB = "whatsapp";
			}else{
				$customerDB = DB::table("customers")->where('id', $customerID )->value('database');
				$customerDB = $customerDB.".whatsapp";
			}

			// get this message id and mark all messages before this message as readed or deleverd
			$messageID = DB::table($customerDB)->where('msg_id', $msgID )->value('id');				
			
			if($messageState == "read" or isset($goRead)){
				// in case we received "read" state thats means all previously messages before this readed message 
				DB::table($customerDB)->where('id', '<=', $messageID)->where('read', '0')->where('client_mobile', $clientMobile)->where('send_receive', '0')->update([ 'read' => '1', 'updated_at' => $todayDateTime]);
			}
			if($messageState == "None" or isset($goDeleverd)){
				// in case we received "delivered" state thats means all previously messages before this message 
				DB::table($customerDB)->where('id', '<=', $messageID)->where('delivered', '0')->where('client_mobile', $clientMobile)->where('send_receive', '0')->update([ 'delivered' => '1', 'msg_time' => $todayDateTime, 'updated_at' => $todayDateTime]);
			}

			return "1";
		}

		////////////////////////////////////////////////////////////////////////////////////
		/////////////////  YOWSUP   Receive WhatsApp token for each server_mobile    /////// Webhook
		////////////////////////////////////////////////////////////////////////////////////

		// check if Whatsapp server sent token to know which token related to server mobile,
		// to make sure we send message from the correct server mobile
		if(isset($request->type) and $request->type == "token"){
			/*
			GET:https://demo.microsystem.com.eg/api/whatsapp?type=token&token=21060&server_mobile=201096622600@s.whatsapp.net
			*/
			// insert response
			$serverMobile = (explode("@",$request->server_mobile));
			DB::table("whatsapp_token")->where( 'server_mobile', $serverMobile[0] )->update([ 'token' => $request->token, 'updated_at' => $todayDateTime ]);
			// DB::table("whatsapp_token")->insert([['server_mobile' => $serverMobile[0], 'token' => $request->token]]);
		}

		///////////////////////////////////////////////////////////////////////////////////
		///////////////////   YOWSUP  Register new Whatsapp number    /////////////////////
		///////////////////////////////////////////////////////////////////////////////////
		if(isset($request->type) and $request->type == "register"){
			/*
			examble
			GET:https://demo.microsystem.com.eg/api/whatsapp?type=register&customer_id=3&server_mobile=201061030454&method=voice&cc=20&mcc=602
			- cc(contry_code): 20 
			- server_mobile: 201061030454
			- method:
				sms
				voice
			- mcc:  //(choose from contry list)
				602
			Response
				too_recent
				{"login":"201553514351","status":"fail","reason":"too_recent","retry_after":18212,"sms_wait":18212,"voice_wait":0}

				Wrong mobile nuber
				{"login":"20101074667","status":"fail","reason":"bad_param","param":"number"}

				sent but not whatsapp reason incorrect
				{"login":"201061030454","status":"fail","reason":"incorrect","sms_length":6,"voice_length":6,"sms_wait":0,"voice_wait":0}

				Verification code has been sent successfully
				{"login":"201553514351","notify_after":86400,"status":"sent","length":6,"method":"voice","retry_after":125,"sms_wait":18054,"voice_wait":125}

				Blocked number
				{"login":"201553514351","status":"fail","reason":"blocked","retry_after":2}

			*/
			// check if this server_number registerd before or not
			$checkIfNumberExist = DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile)->where('customer_id', $request->customer_id)->first();
			if( isset($checkIfNumberExist) ){
				// this number already registerd before and active
			}else{ // new number
				DB::table("whatsapp_token")->insert([['customer_id' => $request->customer_id, 'cc' => $request->cc, 'mcc' => $request->mcc, 'server_mobile' => $request->server_mobile, 'state' => '2']]);
			}
			
			$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/server";
			$msg = "reg,$request->cc,$request->server_mobile,$request->method,$request->mcc";
			$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
			DB::table("whatsapp_log")->insert([['customer_id' => $request->customer_id, 'server_mobile' => $request->server_mobile, 'type' => $request->type, 'value' => $msg, 'note' => '1st action to reg new whatsapp num', 'created_at' => $todayDateTime]]);
			$response = @file_get_contents($url, FALSE, $context);
			
			// check if number blocked or not
			$lastResponse = json_decode($response,true);
			if(isset($lastResponse['reason']) and $lastResponse['reason']=="blocked"){
				DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile)->update([ 'state' => '4']);
				DB::table("whatsapp_log")->insert([['customer_id' => $request->customer_id, 'server_mobile' => $request->server_mobile, 'type' => 'blocked', 'value' => "blocked,$request->server_mobile", 'note' => 'this mobile has been blocked from WA', 'created_at' => $todayDateTime]]);
			}
			return $response;
		}

		///////////////////////////////////////////////////////////////////////////////////
		///////////////////   YOWSUP  Enter Whatsapp registration code    /////////////////
		///////////////////////////////////////////////////////////////////////////////////
		if(isset($request->type) and $request->type == "registration_code"){
			/*
			examble
			GET:https://demo.microsystem.com.eg/api/whatsapp?type=registration_code&customer_id=3&server_mobile=201061030454&cc=20&code=123-456
			Body:
				// responce after enter code 
				reg code is invalid
				{"login":"201553514351","status":"fail","reason":"mismatch","retry_after":2}
				guessed_too_fast
				{"login": "201553514351","status": "fail","reason": "guessed_too_fast","retry_after": 41}
				Registration successfully, send start commant for this server_mobile
				{"status":"ok","login":"201553514351","type":"existing","edge_routing_info":"CAgIBQ==","chat_dns_domain":"fb","security_code_set":false}
				
			Response:
				// if code is valid system will send start number and we will receive
				start
			*/

			$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/server";
			$msg = "regCode,$request->cc,$request->server_mobile,$request->code";
			$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
			DB::table("whatsapp_log")->insert([['customer_id' => $request->customer_id, 'server_mobile' => $request->server_mobile, 'type' => $request->type, 'value' => $msg, 'note' => '2nd action to reg new whatsapp num', 'created_at' => $todayDateTime]]);
			$response = @file_get_contents($url, FALSE, $context);
			print $response;

			// for python3 remove b' '
			// $splitMessage3 = explode("b'",$finalMessage);
			// if(isset($splitMessage3[1])){ 
			// 	$finalMessage = substr_replace($splitMessage3[1], "", -1); 
			// }
			// insert response
			// DB::table("test")->insert([['value1' => 'Reg response', 'value2' => $response]]);
			$decodedJsonResponse = json_decode($response,true);
			if($decodedJsonResponse['status']){
				$responseStatus = $decodedJsonResponse['status'];
				if($responseStatus == "ok"){
					// update status in WA token
					DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile)->where('customer_id', $request->customer_id)->update([ 'state' => '1', 'updated_at' => $todayDateTime ]);
					// start this number in WA server
					sleep(1);
					$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/server";
					$msg = "start,$request->server_mobile";
					$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
					DB::table("whatsapp_log")->insert([['customer_id' => $request->customer_id, 'server_mobile' => $request->server_mobile, 'type' => $request->type, 'value' => $msg, 'note' => '2nd enter reg code', 'created_at' => $todayDateTime]]);
					$response2 = @file_get_contents($url, FALSE, $context);
				}
			}

			DB::table("whatsapp_token")->where('server_mobile', $request->server_mobile)->where('customer_id', $request->customer_id)->where('state', '2')->update([ 'token' => $request->token, 'updated_at' => $todayDateTime ]);
		}

		///////////////////////////////////////////////////////////////////////////////////
		///////////////////   YOWSUP  Whastsapp Config file backup    ///////////////////// Webhook
		///////////////////////////////////////////////////////////////////////////////////
		if(isset($request->type) and $request->type == "server_mobile_configuration"){
			/*
			examble
			POST:https://demo.microsystem.com.eg/api/whatsapp?type=server_mobile_configuration
			Body:
				{
					"_version_": 1,
					"cc": "20",
					"client_static_keypair": "eA7Y96xRIjkRUUTrCMWLL3UqYz50ngaDbK7ZsOqOnWxHysuq/XMq7KEJMq0jvZzLGB0YTU+16y0H03juiZRXDA==",
					"expid": "ssfg716hT8SZscp6FYfsLQ==",
					"fdid": "c1f30896-4b05-4c41-be18-524c1bcb8f94",
					"id": "uNUfXZr2At7ztPt7sLC4tRVw1S0=",
					"login": "201553514354",
					"mcc": "602",
					"mnc": "000",
					"phone": "201553514354",
					"sim_mcc": "000",
					"sim_mnc": "000"
				}
			Response:
				1
			*/
			$body = @file_get_contents('php://input');
			$body = json_decode($body);
			DB::table("whatsapp_token")->where( 'server_mobile', $body->login )->update([ 'config' => $request->config, 'updated_at' => $todayDateTime ]);
		}


	}
}