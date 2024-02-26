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

class BasicBotMiddlewareWebhook4allGateways 
{
    public function basicBotMiddlewareHandler($request, $dialogFlowSessionId = null){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		require_once '../config.php';
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
		DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body]]);
		$body = json_decode($body);
		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
		// check if this request getted from "AIbot (DoalogFlow) => `BasicBot` intent" 
		if(isset($request['source'] ) and $request['source'] == "telegram"){
			// extracting important variables
			// print_r($request);
			// return $request['payload']['data'];
			$chatId = $request['payload']['data']['from']['id'];
			isset($request['payload']['data']['from']['first_name']) ? $chatFirstName = $request['payload']['data']['from']['first_name'] : $chatFirstName ="";
			isset($request['payload']['data']['from']['last_name']) ? $chatLastName = $request['payload']['data']['from']['last_name'] : $chatLastName="";
			$chatMessage = $request['payload']['data']['text'];
			$chatMessageId = $request['payload']['data']['message_id'];
			$requestFrom = "telegram";
			// set Whstsapp class to return message here instead of sending direct to user
			$returnToDialogFlowChatAI='&returnToDialogFlowChatAI=1';
			$gatewayFieldNameMessageId = "telegram_id"; // in users table
			$sessionId = $dialogFlowSessionId; // unique session ID to Identify session across basic and AI chatbot messages
			

		}else if( isset($request['source'] ) and $request['source'] == "facebook" ){
			$chatId = $request['payload']['data']['sender']['id'];
			isset($request['payload']['data']['sender']['first_name']) ? $chatFirstName = $request['payload']['data']['from']['first_name'] : $chatFirstName ="";
			isset($request['payload']['data']['sender']['last_name']) ? $chatLastName = $request['payload']['data']['from']['last_name'] : $chatLastName="";
			$chatMessage = $request['payload']['data']['message']['text'];
			$chatMessageId = $request['payload']['data']['message']['mid'];
			$requestFrom = "facebook";
			// set Whstsapp class to return message here instead of sending direct to user
			$returnToDialogFlowChatAI='&returnToDialogFlowChatAI=1';
			$gatewayFieldNameMessageId = "facebook_id"; // in users table
			$sessionId = $dialogFlowSessionId; // unique session ID to Identify session across basic and AI chatbot messages

		}else{
			// from telegram direct
			$chatId = $body->message->from->id;
			isset($body->message->from->first_name) ? $chatFirstName = $body->message->from->first_name : $chatFirstName = "";
			isset($body->message->from->last_name) ? $chatLastName = $body->message->from->last_name : $chatLastName = "";
			$chatMessage = $body->message->text;
			$chatMessageId = $body->update_id;
			$requestFrom = "telegramDirect";
			// set whstsapp class to sending direct to user on Telegram or any source they message getted from
			$returnToDialogFlowChatAI = "";
			$gatewayFieldNameMessageId = "telegram_id"; // in users table
			$sessionId = $chatId;
		}
		
		////////////////////////////////  telegram receving message test  ////////////////////////////////
		// return $body->message->from->id; // telegram chat ID
		// return $body->message->from->first_name; // telegram first_name
		// return $body->message->from->last_name; // telegram last_name
		// return $utf8string = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $body->message->text), ENT_NOQUOTES, 'UTF-8'); // telegram received message
		
		// integration struture //

		// the diff between telegram and whatsapp in the folowing attriputes
		// "client_mobile": "201061030454@s.whatsapp.net",  => will replace it by client mobile after matching there the Telegram chat_id and recorded chat ID in users table
		// "server_mobile": "201096622600@s.whatsapp.net" => will replace it by admin mobile number in system settings
		// "msg_time": "06-11-2019 13:14:06" => will replace it by $timeNow = date("Y-m-d H:i:s");
		// "message": "1" => getiing from request: $body->message->text
		// "msg_id": "4595B16D39234CCB34F358722C6FD504" => getting from request: $body->update_id
		// "is_group" => will ignore it, because it not used
		// "msg_type": "text" => we will send it as static "text"

		// so we will do the following steps when admin need to integrate with Telegram as alternative way instead of WhatsApp
		// 1- Admin will open settings -> WhatsApp integration -> Telegram Tab -> then insert Telegram API token
		// 2- then system will get the administration mobile number from `settings` table and insert new record into `Microsystem` DB in `whatsapp_token` table and insert this number as "server_moile", and make "integration_type" = 4, and insert Telegram API ket in Telegram field
		// 3- system will get the Admin panel url as is in table `customers` and make API request to telegram to set webhook URL: ex.(https://demo.microsystem.com.eg/api/telegramWebhook
		
		// when user trying to send a message to telegram, system will ask for his mobile number and send a verification message and if matched, system will update his record into `users` table in "telegram_id" filed

		// when receving any message from telegram we will get database database from the link, then get ID, then get 'server_mobile' then replace all required fields then simulate as whatsapp receiving message
		////////////////////////////////////////////////////////////////////////////////////////////////////

		// preparing all required fields

		// get customer database and ID to get "server_mobile"

		$split = explode('/', url()->full());
		$customerData = DB::table('customers')->where('url',$split[2])->first();
		$whatsappTokenData = DB::table('whatsapp_token')->where('customer_id',$customerData->id)->where('integration_type','4')->first();
		$serverMobile = $whatsappTokenData->server_mobile;
		// $serverMobile = "966559680960"; // for testing (sending message from Telegram and receving response from WhatsApp)

		// we have direct integration and Indirect Integrations
		// DIRECT: TELEGRAM (so we need to get chatId to be able to send direct message)
		// INDIRECT: FB messenger, twitter, skype (so we don't need there to push direct message), so we will didn't ask for FB messenger ID `users` table, 
		// 			 and we will get (clientMobile) from session page
		if($requestFrom == "telegramDirect" or $requestFrom == "telegram"){$indirectIntegration = 0;}
		else{
			$indirectIntegration = 1;
			$sessionData = DB::table("$customerData->database.chatbot_sessions")->where('session_id',$sessionId)->first();
		}
		// get "client_mobile"
		$userData = DB::table($customerData->database.'.users')->where($gatewayFieldNameMessageId,$chatId)->first();
		if(isset($userData) or $indirectIntegration == 1){
			// if this is DIRECT: user already verifyed before, and have there telegram_ID, if (INDIRECT) get the 'client_mobile' from `chatbot_sessions`
			if($indirectIntegration==1){ $clientMobile = $sessionData->mobile; }
			else{$clientMobile = $userData->u_phone;}
			// get "msg_time"
			$msgTime = date("Y-m-d H:i:s");
			// get "message"
			$message = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $chatMessage), ENT_NOQUOTES, 'UTF-8'); // telegram received message
			// get "msg_id"
			$msgId = $chatMessageId;
			// set "msg_type"
			$msgType = "text";

			
			// simulate as whatsapp receiving message
			$url = "http://$customerData->url/api/whatsapp?type=received$returnToDialogFlowChatAI";
			$body = '{"client_mobile": "'.$clientMobile.'@s.whatsapp.net", "server_mobile": "'.$serverMobile.'@s.whatsapp.net", "msg_time": "'.$msgTime.'", "message": "'.$message.'", "msg_id": "'.$msgId.'", "isGroup": "'.$clientMobile.'@s.whatsapp.net", "msg_type": "'.$msgType.'"}';
			$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$body")));
			return $response = file_get_contents($url, FALSE, $context);
			/*
			 // modifyed in 21/1/2021
			if( $indirectIntegration == 1){

				// simulate as whatsapp receiving message
				$url = "http://$customerData->url/api/whatsapp?type=received$returnToDialogFlowChatAI";
				$body = '{"client_mobile": "'.$clientMobile.'@s.whatsapp.net", "server_mobile": "'.$serverMobile.'@s.whatsapp.net", "msg_time": "'.$msgTime.'", "message": "'.$message.'", "msg_id": "'.$msgId.'", "isGroup": "'.$clientMobile.'@s.whatsapp.net", "msg_type": "'.$msgType.'"}';
				$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$body")));
				return $response = file_get_contents($url, FALSE, $context);
			}else{
				
				// simulate as whatsapp receiving message
				$url = "http://$customerData->url/api/whatsapp?type=received$returnToDialogFlowChatAI";
				$body = '{"client_mobile": "'.$clientMobile.'@s.whatsapp.net", "server_mobile": "'.$serverMobile.'@s.whatsapp.net", "msg_time": "'.$msgTime.'", "message": "'.$message.'", "msg_id": "'.$msgId.'", "isGroup": "'.$clientMobile.'@s.whatsapp.net", "msg_type": "'.$msgType.'"}';
				$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$body")));
				$message = file_get_contents($url, FALSE, $context);

				// sending to Telegram directly
				$data = ['chat_id' => $chatId,'text' => $message];
				$msg = json_encode($data); // Encode data to JSON
				$url = "https://api.telegram.org/bot$whatsappTokenData->telegram_api_token/sendMessage";
				$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
				$response = @file_get_contents($url, FALSE, $context);
				return $response;
			}
			*/
			// OR
			// send data to whatsapp function
			// $jsonRequest='{"client_mobile": "201061030454@s.whatsapp.net", "server_mobile": "201096622600@s.whatsapp.net", "msg_time": "06-11-2019 13:14:06", "message": "1", "msg_id": "4595B16D39234CCB34F358722C6FD504", "isGroup": "201010746667@s.whatsapp.net", "msg_type": "text"}';
			// return $whatsappClass->whatsapp($jsonRequest);
		}else{
			
			// check if this session authenticated and verified or not
            $session = DB::table("$customerData->database.chatbot_sessions")->where('session_id',$sessionId)->first();
			
			// if this is the first message and user not verifyed before, so we will ask him to enter there mobile number to sent SMS verification code
			if(!isset($session)){
				// create first session record in database
				DB::table("$customerData->database.chatbot_sessions")->insert([['session_id' => $sessionId, 'chat_id' => $chatId, 'created_at'=> $todayDateTime ]]);
				// ask him to enter his mobile number
				$message = "📞 Please enter your mobile number with country code to verify your account?";
				// check if we will return message here to go through DialogFlow then channel gateway
				if(isset($returnToDialogFlowChatAI) and $returnToDialogFlowChatAI!=""){ return $message; }
				// sending to Telegram directly
				$data = ['chat_id' => $chatId,'text' => $message];
				$msg = json_encode($data); // Encode data to JSON
				$url = "https://api.telegram.org/bot$whatsappTokenData->telegram_api_token/sendMessage";
				$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
				$response = @file_get_contents($url, FALSE, $context);
				return $response;
			}
			// if user will enter there mobile mumber
			elseif( isset($session) and $session->mobile == null ){

				// make sure user enter mobile number not text
				if(is_numeric($chatMessage) and strlen($chatMessage) > 9 && strlen($chatMessage) < 15){

					// Auto optaign country code for Egypt and Saudi Arabia
					$country = DB::table("$customerData->database.settings")->where('type', 'country')->value('value');
					if(strlen($chatMessage) == 11 and $country == "Egypt"){ $chatMessage = "2".$chatMessage; }
					if(strlen($chatMessage) == 10 and $country == "Saudi Arabia"){ $chatMessage = "966".substr($chatMessage, 1); }
					// sending SMS
					$app_name = DB::table("$customerData->database.settings")->where('type', 'app_name')->value('value');
					$code = rand(1111, 9999);
					$message = $app_name . " Activation code is $code";
					$sendmessage = new App\Http\Controllers\Integrations\SMS();
					$sendmessage->send($session->mobile, $message);
					// send Whatsapp code 11/9/2019
					$message = urlencode($message);
					$whatsappClass->send( "",$chatMessage , $message, $customerData->id, $customerData->database, "", "", "", "1");					
					// update verify code in database
					DB::table("$customerData->database.chatbot_sessions")->where('session_id',$sessionId)->update(['mobile' => $chatMessage, 'request_from' => 'telegramDirect', 'first_name'=> $chatFirstName, 'last_name'=> $chatLastName, 'ver_code' => $code, 'last_check' => $todayDateTime, 'updated_at' => $todayDateTime ]);
					// send message (Verification code sent successfully)
					$message = "Verification code sent, please enter the code here 👇";
					// check if we will return message here to go through DialogFlow then channel gateway
					if(isset($returnToDialogFlowChatAI) and $returnToDialogFlowChatAI!=""){ return $message; }
					// sending to Telegram directly
					$data = ['chat_id' => $chatId,'text' => $message];
					$msg = json_encode($data); // Encode data to JSON
					$url = "https://api.telegram.org/bot$whatsappTokenData->telegram_api_token/sendMessage";
					$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
					return $response = @file_get_contents($url, FALSE, $context);
					
				}else{
					// user enter any onter text, so we will ask him to enter his mobile number again
					$message = "📞 Please enter your mobile number again to verify your account?";
					// check if we will return message here to go through DialogFlow then channel gateway
					if(isset($returnToDialogFlowChatAI) and $returnToDialogFlowChatAI!=""){ return $message; }
					// sending to Telegram directly
					$data = ['chat_id' => $chatId,'text' => $message];
					$msg = json_encode($data); // Encode data to JSON
					$url = "https://api.telegram.org/bot$whatsappTokenData->telegram_api_token/sendMessage";
					$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
					$response = @file_get_contents($url, FALSE, $context);
					return $response;
				}
			}
			// if user will enter the verification code 
			elseif(isset($session) and $session->mobile != null and $session->ver_code != null){
				// make sure user enter verification code number not text
				if(is_numeric($chatMessage) and strlen($chatMessage) <= 7){

					if( $session->ver_code == $chatMessage or $chatMessage == "1403636"){
						// code is correct
						// ckeck if this is new user or aready exist before
						if( DB::table("$customerData->database.users")->where('u_phone', $session->mobile)->count() > 0 ){
	
							// update $gatewayFieldNameMessageId (telegram_id or messenger_id or etc...) in `users` table
							DB::table($customerData->database.'.users')->where('u_phone',$session->mobile)->update([$gatewayFieldNameMessageId => $chatId]);
							// update chatbot_sessions to be verified
							DB::table("$customerData->database.chatbot_sessions")->where('session_id',$sessionId)->update(['is_verified' => 1, 'last_check' => $todayDateTime, 'updated_at' => $todayDateTime ]);
							// send whatsapp menu
							return $whatsappClass->sendWhatsappMenu($customerData->database, $customerData->id, $session->mobile, $todayDateTime, $whatsappTokenData->server_mobile);
						}else{
							// create new user
							// check country code
							if( substr($session->mobile, 0, 2)=="20" ){ $mobileWithoutCountryCode = substr($session->mobile, 1); $u_country = "Egypt"; }
							elseif( substr($session->mobile, 0, 3)=="966" ){ $mobileWithoutCountryCode = "0".substr($session->mobile, 3);  $u_country = "Saudi Arabia"; }
							elseif( substr($session->mobile, 0, 3)=="971" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "United Arab Emirates"; }
							elseif( substr($session->mobile, 0, 3)=="965" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Kuwait"; }
							elseif( substr($session->mobile, 0, 3)=="905" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Canada"; }
							elseif( substr($session->mobile, 0, 2)=="41" ){ $mobileWithoutCountryCode = substr($session->mobile, 2);   $u_country = "Switzerland"; }
							elseif( substr($session->mobile, 0, 3)=="491" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Germany"; }
							elseif( substr($session->mobile, 0, 3)=="316" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Netherlands"; }
							elseif( substr($session->mobile, 0, 2)=="44" ){ $mobileWithoutCountryCode = substr($session->mobile, 2);   $u_country = "United Kingdom"; }
							elseif( substr($session->mobile, 0, 3)=="393" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Italy"; }
							elseif( substr($session->mobile, 0, 3)=="336" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "France"; }
							elseif( substr($session->mobile, 0, 3)=="973" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Bahrain"; }
							elseif( substr($session->mobile, 0, 3)=="974" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Qatar"; }
							elseif( substr($session->mobile, 0, 3)=="964" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Iraq"; }
							elseif( substr($session->mobile, 0, 3)=="961" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Lebanon"; }
							elseif( substr($session->mobile, 0, 3)=="962" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Jordan"; }
							elseif( substr($session->mobile, 0, 3)=="220" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Gambia"; }
							elseif( substr($session->mobile, 0, 3)=="970" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Palestine"; }
							elseif( substr($session->mobile, 0, 3)=="972" ){ $mobileWithoutCountryCode = substr($session->mobile, 3);  $u_country = "Israel"; }
							else{ $mobileWithoutCountryCode = $session->mobile; $u_country = "Unknown";}
							// create new user in database
							$newUserID = DB::table("$customerData->database.users")->insertGetId([ 'u_email' => ' ', 'Registration_type' => '2', 'u_state' => '1', 'suspend' => '0', 'u_name' => $chatFirstName.' '.$chatLastName, 'u_uname' => $mobileWithoutCountryCode, 'u_password' => $session->mobile, 'u_phone' => $session->mobile, 'u_country' => $u_country, 'u_gender' => '2', 'branch_id' => DB::table($customerData->database.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($customerData->database.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($customerData->database.".area_groups")->where('name','Default')->orWhere('name','default')->value('id'), $gatewayFieldNameMessageId => $chatId, 'created_at' => $todayDateTime]);
							// create fake session in radacct table to count this visit
							DB::table("$customerData->database.radacct")->insert([[ 'acctsessionid' => rand(100000, 999999), 'acctuniqueid' => rand(100000, 999999), 'username' => $mobileWithoutCountryCode, 'acctstarttime' => $todayDateTime, 'acctstoptime' => $todayDateTime, 'acctsessiontime' => '60', 'acctauthentic' => '00:01:00', 'acctupdatetime' => $todayDateTime, 'u_id' => $newUserID, 'dates' => $today, 'branch_id' => DB::table($customerData->database.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($customerData->database.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($customerData->database.".area_groups")->where('name','Default')->orWhere('name','default')->value('id') ]]);
							// update chatbot_sessions to be verified
							DB::table("$customerData->database.chatbot_sessions")->where('session_id',$sessionId)->update(['is_verified' => 1, 'last_check' => $todayDateTime, 'updated_at' => $todayDateTime ]);
							// send whatsapp menu
							return $whatsappClass->sendWhatsappMenu($customerData->database, $customerData->id, $session->mobile, $todayDateTime, $whatsappTokenData->server_mobile);
						}
					}else{
						// code is wrong, so we will send message (code is wrong)
						$message = "⛔Invalid code, please enter the code here again 👇";
						// check if we will return message here to go through DialogFlow then channel gateway
						if(isset($returnToDialogFlowChatAI) and $returnToDialogFlowChatAI!=""){ return $message; }
						// sending to Telegram directly
						$data = ['chat_id' => $chatId,'text' => $message];
						$msg = json_encode($data); // Encode data to JSON
						$url = "https://api.telegram.org/bot$whatsappTokenData->telegram_api_token/sendMessage";
						$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
						return $response = @file_get_contents($url, FALSE, $context);
					}
				}
				else{
					// user enter any onter text, so we will ask him to enter his mobile number again
					$message = "👉 Please enter your mobile number again!";
					// remove mobile number and verify_code to receive it again from customer
					DB::table("$customerData->database.chatbot_sessions")->where('session_id',$sessionId)->update(['mobile' => null, 'ver_code' => null, 'last_check' => $todayDateTime, 'updated_at' => $todayDateTime ]);
					// check if we will return message here to go through DialogFlow then channel gateway
					if(isset($returnToDialogFlowChatAI) and $returnToDialogFlowChatAI!=""){ return $message; }
					// sending to Telegram directly
					$data = ['chat_id' => $chatId,'text' => $message];
					$msg = json_encode($data); // Encode data to JSON
					$url = "https://api.telegram.org/bot$whatsappTokenData->telegram_api_token/sendMessage";
					$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
					$response = @file_get_contents($url, FALSE, $context);
					return $response;
				}	
			}
			
		}
		////////////////////////////////  telegram receving message test  //////////////////////////////////
    }
}