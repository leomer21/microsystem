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
use Schema;
use Mail;
use Carbon\Carbon;

class WhatsappCron extends Controller
{
    public function whatsappCron(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
		// sending ... message per minute
		// $sendingRand = rand(1,3); // for yowsup
		$sendingRand = rand(10,30); // for chat-api.com
		// $sendingRand = 1;
		
		// return $fiveMinAgo = date('H:i:s', strtotime('5 minutes ago'));
		// $date1 = strtotime(Carbon::now()->subMinutes(5)->toDateTimeString());
		// $date2 = strtotime($todayDateTime);  
		// return $diff = abs($date2 - $date1); 

		require_once '../config.php';
		
		// // test new chatapi integration
		// return $whatsappClass->send( "", "201061030454" , "Hi Hi Hi From Cron", "3", "demo","","","","","");
		
		// General function to open socket with Whatsapp
		function whatsappConnect($ip,$port,$command){

			$f = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_set_option($f, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 500000));
			$s = socket_connect($f, $ip, $port);
			$len = strlen($command);
			$sendResult = socket_sendto($f, $command, $len, 0, $ip, $port);
			socket_close($f);
		}
		//////////////////////////////////////////////////////////
		///      check new users 4 global users database       ///
		//////////////////////////////////////////////////////////
		
		$allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            // get all usets
            foreach( DB::table($Customer->database.'.users')->where('u_id', '>', $Customer->last_user_id)->limit($sendingRand)->get() as $user ){

                // update last id in customers database
                DB::table("customers")->where( 'database', $Customer->database )->update(['last_user_id' => $user->u_id]);

                if( isset($user->u_phone) and strlen($user->u_phone) >= 8 and strpos($user->u_phone, ':') === false){
                    
                    // check if this mobile is already exists or not
                    $ifExists = DB::table("users_global")->where( 'mobile', 'like', '%'.$user->u_phone.'%')->first();
                    
                    // get last seen
                    $lastSeen = DB::table($Customer->database.'.radacct')->where('u_id', $user->u_id)->orderBy('radacctid','desc')->limit(1)->first();

                    // check country code
                    if( substr($user->u_phone, 0, 2)=="20" ){ $u_country = "Egypt"; }
                    elseif( substr($user->u_phone, 0, 3)=="966" ){ $u_country = "Saudi Arabia"; }
                    elseif( substr($user->u_phone, 0, 3)=="971" ){ $u_country = "United Arab Emirates"; }
                    elseif( substr($user->u_phone, 0, 3)=="965" ){ $u_country = "Kuwait"; }
                    elseif( substr($user->u_phone, 0, 3)=="905" ){ $u_country = "Canada"; }
                    elseif( substr($user->u_phone, 0, 2)=="41" ){ $u_country = "Switzerland"; }
                    elseif( substr($user->u_phone, 0, 3)=="491" ){ $u_country = "Germany"; }
                    elseif( substr($user->u_phone, 0, 3)=="316" ){ $u_country = "Netherlands"; }
                    elseif( substr($user->u_phone, 0, 2)=="44" ){ $u_country = "United Kingdom"; }
                    elseif( substr($user->u_phone, 0, 3)=="393" ){ $u_country = "Italy"; }
                    elseif( substr($user->u_phone, 0, 3)=="336" ){ $u_country = "France"; }
                    elseif( substr($user->u_phone, 0, 3)=="973" ){ $u_country = "Bahrain"; }
                    elseif( substr($user->u_phone, 0, 3)=="974" ){ $u_country = "Qatar"; }
                    elseif( substr($user->u_phone, 0, 3)=="964" ){ $u_country = "Iraq"; }
                    elseif( substr($user->u_phone, 0, 3)=="961" ){ $u_country = "Lebanon"; }
                    elseif( substr($user->u_phone, 0, 3)=="962" ){ $u_country = "Jordan"; }
                    elseif( substr($user->u_phone, 0, 3)=="220" ){ $u_country = "Gambia"; }
                    elseif( substr($user->u_phone, 0, 3)=="970" ){ $u_country = "Palestine"; }
                    elseif( substr($user->u_phone, 0, 3)=="972" ){ $u_country = "Israel"; }
                    else{ $u_country = $user->u_country; }

                    if(isset($lastSeen)){ 
                        $last_seen_at = $lastSeen->acctstoptime;
                        $last_seen_in_customer_id = $Customer->id;
                        $last_seen_in_branch_id = $lastSeen->branch_id;
                    }else{
                        $last_seen_at = "";
                        $last_seen_in_customer_id = "";
                        $last_seen_in_branch_id = "";
                    }
                    if(isset($ifExists)){
                        // user already exist so we will add customerID and branchID
                        $customer_id = $ifExists->customer_id.",".$Customer->id;
                        $local_user_id = $ifExists->local_user_id.",".$user->u_id;
                        $branch_id = $ifExists->branch_id.",".$user->branch_id;

                        // update user data
						DB::table("users_global")->where( 'id', $ifExists->id )->update(['customer_id' => $customer_id, 'local_user_id' => $local_user_id, 'branch_id' => $branch_id, 'last_seen_at' => $last_seen_at, 'last_seen_in_customer_id' => $last_seen_in_customer_id, 'last_seen_in_branch_id' => $last_seen_in_branch_id, 'updated_at' => $todayDateTime]);
                    }else{
						// New number so we will insert user data
						DB::table("users_global")->insert([['customer_id' => $Customer->id, 'local_user_id' => $user->u_id, 'branch_id' => $user->branch_id, 'whatsapp' => '0', 'country' => $u_country, 'name' => $user->u_name, 'gender' => $user->u_gender, 'mobile' => $user->u_phone, 'last_seen_at' => $last_seen_at, 'last_seen_in_customer_id' => $last_seen_in_customer_id, 'last_seen_in_branch_id' => $last_seen_in_branch_id, 'created_at' => $todayDateTime]]);
					}
					
					// Pushing user to simpleTouch POS
					$simpleTouchPosIntegration = DB::table("$Customer->database.settings")->where('type', 'simpleTouchPosIntegration')->first();
					if(isset($simpleTouchPosIntegration) and $simpleTouchPosIntegration->state == "1"){
						if($user->u_gender == "1"){$gender = "M";}elseif($user->u_gender=="0"){$gender="F";}else{$gender="N";}
						$data = ['BrandID' => $simpleTouchPosIntegration->value, 'FirstName' => $user->u_name, 'Mobile' => substr($user->u_phone, 1), 'Gender' => $gender, 'CustomerSourceID' => '8', 'Email' => $user->u_email];
						$msg = json_encode($data); // Encode data to JSON
						$url = "https://dobitesmobileapitest.azurewebsites.net/DobitesAPIs/Customer/Register";
						$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
						$response = json_decode(@file_get_contents($url, FALSE, $context));
						if(isset($response->MessageCode) and $response->MessageCode == "605"){ DB::table("$Customer->database.users")->where('u_id', $user->u_id)->update(['pos_id' => $response->Data->CustomerID]); }
						// print_r($response);
					}

					// Pushing user to POS rocket
					$PosRocketIntegration = DB::table("$Customer->database.settings")->where('type', 'PosRocketIntegration')->first();
					if(isset($PosRocketIntegration) and $PosRocketIntegration->state == "1"){
						// build variables and arrays
						if($user->u_gender == "1"){$gender = "MALE";}elseif($user->u_gender=="0"){$gender="FEMALE";}else{$gender="UNSPECIFIED";}
						$mobile = [['number' => $user->u_phone, 'is_primary' => true, 'is_verified' => true]]; 
						$data = ['first_name' => $user->u_name, 'gender' => $gender, 'country' => 'Eg', 'phone_numbers' => $mobile, 'dob' => $user->birthdate];
						$msg = json_encode($data);
						$url = "https://developer.posrocket.com/api/v1/directory/customers";
						$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\nAuthorization: Bearer $PosRocketIntegration->value",'content' => $msg)));
						$response = @file_get_contents($url, FALSE, $context);
						$response = json_decode($response);
						if(isset($response->data->id)){ DB::table("$Customer->database.users")->where('u_id', $user->u_id)->update(['pos_id' => $response->data->id]); }
					}

					if($Customer->whatsapp == "1"){
						// sending Whatsapp menu
						foreach( explode(",",$user->u_phone) as $to ){
							// delay time betweeb each message
							$delayTime = rand(10,20);
							sleep($delayTime);
							$whatsappClass->sendWhatsappMenu($Customer->database, $Customer->id, $to, $todayDateTime);
						}
					}
					unset($to);
                    unset($ifExists);
                }
            }
		}
		
		//////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////
		
		
		// $allCustomers=DB::table('customers')->where('state','1')->where('whatsapp','1')->where('database','sawqli')->groupBy('database')->get();
		$allCustomers=DB::table('customers')->where('state','1')->where('whatsapp','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
			
			////////////////////////////////////////////////////////////////////////////////////
			///////  YOWSUP  check if last update time of token more than one day     //////////
			////////////////////////////////////////////////////////////////////////////////////
			foreach( DB::table("whatsapp_token")->where('state','1')->where('integration_type','1')->where('updated_at', '<=', Carbon::now()->subHours(24)->toDateTimeString())->get() as $tokenNotUpdated ){
				DB::table("whatsapp_token")->where('id', $tokenNotUpdated->id)->update(['state' => '0']); 
			}
			
			////////////////////////////////////////////////////////////////////////////////////
			/////// YOWSUP  check if last update time of token more than two minutes     ///////
			////////////////////////////////////////////////////////////////////////////////////
			// return Carbon::now()->subMinutes(10)->toDateTimeString();
			foreach( DB::table("whatsapp_token")->where('state','1')->where('integration_type','1')->where('updated_at', '<=', Carbon::now()->subMinutes(2)->toDateTimeString())->get() as $tokenNotUpdated ){
				// check if server_mobile restarted 5 times without any response
				$last5MinStatus = DB::table("whatsapp_log")->where('type','restart_token_not_updated')->where('server_mobile',$tokenNotUpdated->server_mobile)->where('created_at', '>=', Carbon::now()->subMinutes(10)->toDateTimeString())->get();
				// DB::table("whatsapp_log")->insert([['customer_id' => '3', 'server_mobile' => $tokenNotUpdated->server_mobile, 'type' => 'restart_token_not_updated', 'note' => 'Token not updated', 'created_at' => $todayDateTime]]);
				// return $last5MinStatus;
				if( count($last5MinStatus) <= 3 ){
					// restart server_mobile number
					sleep(25);
					$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/server";
					$command = "restart,$tokenNotUpdated->server_mobile";
					$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$command")));
					$response = file_get_contents($url, FALSE, $context);

					$customerID = DB::table("whatsapp_token")->where('server_mobile', $tokenNotUpdated->server_mobile )->value('customer_id');
					DB::table("whatsapp_log")->insert([['customer_id' => $customerID, 'server_mobile' => $tokenNotUpdated->server_mobile, 'type' => 'restart_token_not_updated', 'value' => $command, 'note' => 'Token not updated', 'created_at' => $todayDateTime]]);
					sleep(5);
				}
				elseif( count($last5MinStatus) > 3 ){
					if( DB::table("whatsapp_log")->where('type','mail_sent_token_not_updated')->where('server_mobile',$tokenNotUpdated->server_mobile)->where('created_at', '>=', Carbon::now()->subMinutes(10)->toDateTimeString())->count() == 0 ){
							
						$content = "<center>الواتس آب خوروب يا عم احمد<br>$tokenNotUpdated->server_mobile</center>";
						$from = "support@microsystem.com.eg";
						$subject = "الحق يا عم احمد";
						$toArra = array('elmohamady@microsystem.com.eg', 'a.mansour@microsystem.com.eg', 'mr.ahmed@microsystem.com.eg');

						Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($toArra, $from, $subject) {
							$message->from($from);
							$message->to($toArra)->subject($subject);
						});
						DB::table("whatsapp_log")->insert([['server_mobile' => $tokenNotUpdated->server_mobile, 'type' => 'mail_sent_token_not_updated', 'note' => 'Mail sent', 'created_at' => $todayDateTime]]);
					}
					// try to register this number to check if blocked or not
					// @file("https://demo.microsystem.com.eg/api/whatsapp?type=register&customer_id=$tokenNotUpdated->customer_id&cc=$tokenNotUpdated->cc&server_mobile=$tokenNotUpdated->server_mobile&method=text&mcc=$tokenNotUpdated->mcc");

				}
				/*
				// check if this number restarted before with in 10 minutes
				elseif( count($last5MinStatus) == 9 ){
					// reset this number because restart is not working
					$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/server";
					$command = "reset,$tokenNotUpdated->server_mobile";
					$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$command")));
					$response = file_get_contents($url, FALSE, $context);
					
					$customerID = DB::table("whatsapp_token")->where('server_mobile', $tokenNotUpdated->server_mobile )->value('customer_id');
					DB::table("whatsapp_log")->insert([['customer_id' => $customerID, 'server_mobile' => $tokenNotUpdated->server_mobile, 'type' => 'reset_token_not_updated', 'value' => $command, 'note' => 'ServerMobile restarted 9 times within 10 min ', 'created_at' => $todayDateTime]]);
					sleep(5);
				}
				*/
			}
			
			////////////////////////////////////////////////////////////////////////////////////
			/////// check if message sent from system but not sent from whatsapp server  /////// 
			/////// also there is messages sent successfully from the same server_mobile ///////
			////////////////////////////////////////////////////////////////////////////////////
			
			foreach( DB::table("$Customer->database.whatsapp")->where('send_receive','0')->where('sent','0')->orderBy('id','asc')->limit($sendingRand)->get() as $allMessagesNotSent ){
				// delay time betweeb each message
				
				$delayTime = rand(10,20);
				// check if this message resent before again or not
				if( DB::table("whatsapp_log")->where('type','resendMessage')->where('value',"$Customer->database"."$allMessagesNotSent->id")->count() > 0 ){
					// msg resent before but not sent from WA server again, so we will block this message
					DB::table("$Customer->database.whatsapp")->where('id', $allMessagesNotSent->id)->update(['send_receive' => '2']); 
					DB::table("whatsapp_log")->insert([['customer_id' => $Customer->id, 'server_mobile' => $allMessagesNotSent->server_mobile, 'type' => 'resendMessageFailed', 'value' => "$Customer->database"."$allMessagesNotSent->id", 'note' => 'resend sent two times but WA didnt sent it', 'created_at' => $todayDateTime]]);
				}else{
					// resend this message again
					$serverMobileData = DB::table("whatsapp_token")->where('server_mobile', $allMessagesNotSent->server_mobile )->first();
					// if server mobile is deleted make message as failed
					if(!isset($serverMobileData)){ DB::table("$Customer->database.whatsapp")->where('server_mobile', $allMessagesNotSent->server_mobile)->where('send_receive','0')->where('sent','0')->update(['send_receive' => '2']); break;}
					$response = $whatsappClass->send( "", $allMessagesNotSent->client_mobile, $allMessagesNotSent->message, $serverMobileData->customer_id, $Customer->database,"","","","","",$allMessagesNotSent->id);
					if($response == "1"){
						// insert resend log
						DB::table("whatsapp_log")->insert([['customer_id' => $serverMobileData->customer_id, 'server_mobile' => $allMessagesNotSent->server_mobile, 'type' => 'resendMessage', 'value' => "$Customer->database"."$allMessagesNotSent->id", 'note' => 'resend this msg id because this msg sent before but WA server didnt send it', 'created_at' => $todayDateTime]]);
						sleep($delayTime);
					}
				}
			}
			
			
			////////////////////////////////////////////////////////////////////////////////////
			/////// 	  		check if there is a pending whatsapp campaign       	 ///////
			////////////////////////////////////////////////////////////////////////////////////
			foreach( DB::table("$Customer->database.whatsapp_campaign")->where('state','0')->limit($sendingRand)->get() as $whatsappSendingMsg ){
				// delay time betweeb each message
				$delayTime = rand(10,20);
				// check if there is more than 5 locked or blocked messages for the same user
				if( DB::table("$Customer->database.whatsapp_campaign")->where('user_id',$whatsappSendingMsg->user_id)->where('state','0')->count() > 5 ){
					// deactivate all user messages	
					DB::table("$Customer->database.whatsapp_campaign")->where('user_id', $whatsappSendingMsg->user_id)->where('state','0')->update(['state' => '2']); 
				}
				
				// check if this is campaign or standalone message
				if(isset($whatsappSendingMsg->campaign_id)){
					
					//get campaign info
					$campaignData = DB::table("$Customer->database.campaigns")->where('id',$whatsappSendingMsg->campaign_id)->first();
					if(isset($campaignData)){
						
						// if campaign is survey
						if( $campaignData->type == "survey" and $campaignData->whatsapp == "1" ){
							if($campaignData->survey_type == "poll" ){
								foreach ( DB::table("$Customer->database.survey")->where('campaign_id',$campaignData->id)->whereNull('u_id')->get() as $option)
								{
									$campaignData->question.="\n $option->options";
								}
							}
							
							// send whatsapp Message
							$userMobile = DB::table("$Customer->database.users")->where('u_id',$whatsappSendingMsg->user_id)->value('u_phone');
							if(isset($userMobile) and strlen($userMobile)>8 ){
								// deactivate this message from campaign	
								DB::table("$Customer->database.whatsapp_campaign")->where('id', $whatsappSendingMsg->id)->update(['state' => '1', 'sent_at' => $todayDateTime]); 
								// add reach to survey campaign
								DB::table("$Customer->database.campaign_statistics")->insert([['campaign_id' => $campaignData->id, 'type' => "reach", 'u_id' => $whatsappSendingMsg->user_id, 'created_at' => $todayDateTime]]);
								// sending
								foreach( explode(",",$userMobile) as $to ){
									$campaignData->question = urlencode($campaignData->question);
									$whatsappClass->send( "", $to , $campaignData->question, $Customer->id, $Customer->database,"","","","1",$campaignData->id);
									sleep($delayTime);
								}
							}else{
								// deactivate this message from campaign and mark it to failed
								DB::table("$Customer->database.whatsapp_campaign")->where('id', $whatsappSendingMsg->id)->update(['state' => '2', 'sent_at' => $todayDateTime]); 
							}
							unset($userMobile);
						}
					}	
				}

				// check if this stansalone message
				elseif(isset($whatsappSendingMsg->message_id)){
					// get message content
					$standaloneMsg = DB::table("$Customer->database.history")->where('operation','standaloneMessage')->where('id', $whatsappSendingMsg->message_id)->value('details');
					// get user mobile numner
					$userMobile = DB::table("$Customer->database.users")->where('u_id',$whatsappSendingMsg->user_id)->value('u_phone');
					// deactivate this message from campaign	
					DB::table("$Customer->database.whatsapp_campaign")->where('id', $whatsappSendingMsg->id)->update(['state' => '1', 'sent_at' => $todayDateTime]);
					if(isset($userMobile)){
						// send whatsapp Message
						foreach( explode(",",$userMobile) as $to ){
							$standaloneMsg = urlencode($standaloneMsg);
							return $whatsappClass->send( "", $to , $standaloneMsg, $Customer->id, $Customer->database,"","","","1");
							sleep($delayTime);
						}
					}
					unset($userMobile);
				}

			}
			
			//////////////////////////////////////////////////////////////////////////////////////////////////////////
			///////   check if there is a survey campaign or whatsapp first Bot will deliver after ... minuts  ///////
			//////////////////////////////////////////////////////////////////////////////////////////////////////////
            if($Customer->whatsapp=="1"){

                $surveyOrBotCampaigns = DB::table($Customer->database.".campaigns")->where(['whatsapp'=>'1', 'whatsapp_first_survey'=> '1'])->where('whatsapp_after', '!=', '0')->get();
                foreach ($surveyOrBotCampaigns as $surveyOrBotCampaign) {
                    
                    $splitTimeFrame = explode("/",$surveyOrBotCampaign->whatsapp_after);
                    if( isset($splitTimeFrame[1]) ){
						
                        if($splitTimeFrame[0] == "0"){$safeSide = "360"; $actualMinuts=$splitTimeFrame[0];}// to get all open sessions since 6 hours
                        else{
                            if(isset($splitTimeFrame[1]) and $splitTimeFrame[1] == "minuts"){
								$safeSide = $splitTimeFrame[0]*2;
								$actualMinuts = $splitTimeFrame[0];
                            }elseif(isset($splitTimeFrame[1]) and $splitTimeFrame[1] == "hours"){
								$safeSide = ($splitTimeFrame[0]*60)+10;
								$actualMinuts = $splitTimeFrame[0]*60;
                            }elseif(isset($splitTimeFrame[1]) and $splitTimeFrame[1] == "days"){
								$safeSide = (($splitTimeFrame[0]*60)*24)+10;
								$actualMinuts = ($splitTimeFrame[0]*60)*24;
                            }
						}
						
						// get online sessions opend from $past for $safeSide
                        $past = date('Y-m-d H:i:s', strtotime("-$safeSide minutes", strtotime($todayDateTime)));
						// $sqlQuery = "SELECT "."$Customer->database".".radacct.u_id, $Customer->database".".radacct.branch_id, $Customer->database".".radacct.group_id, $Customer->database".".radacct.network_id , ROUND(Sum(radacct.acctsessiontime)/60, 0) AS `totalMinuts` FROM "."$Customer->database".".radacct where "."$Customer->database".".radacct.acctstarttime>='$past' GROUP BY "."$Customer->database".".radacct.u_id ORDER BY "."$Customer->database".".radacct.radacctid DESC ;";
						$sqlQuery = "SELECT "."$Customer->database".".radacct.u_id, $Customer->database".".radacct.branch_id, $Customer->database".".radacct.group_id, $Customer->database".".radacct.network_id , ROUND(Sum(radacct.acctsessiontime)/60, 0) AS `totalMinuts` FROM "."$Customer->database".".radacct where "."$Customer->database".".radacct.acctupdatetime>='$past' GROUP BY "."$Customer->database".".radacct.u_id ORDER BY "."$Customer->database".".radacct.radacctid DESC ;";
						$sessions = DB::select(DB::raw($sqlQuery));
						
						if(isset($sessions)){
							foreach($sessions as $session){
								
								// check if user online since .... minuts
								if( $session->totalMinuts >= $actualMinuts ){
									
									// check if user take this offer before according to whatsapp_repeat_survey DAYS
									if( $surveyOrBotCampaign->whatsapp_repeat_survey == "" or $surveyOrBotCampaign->whatsapp_repeat_survey == "0" ){
										$checkIfTaken = DB::table("$Customer->database.whatsapp_campaign")->where(['user_id'=>$session->u_id ,'campaign_id'=>$surveyOrBotCampaign->id])->count();
									}else{
										$subDays = date('Y-m-d H:i:s', strtotime("-$surveyOrBotCampaign->whatsapp_repeat_survey days", strtotime($todayDateTime))); 
										$checkIfTaken = DB::table("$Customer->database.whatsapp_campaign")->where(['user_id'=>$session->u_id ,'campaign_id'=>$surveyOrBotCampaign->id])->whereBetween('created_at', [$subDays, $todayDateTime])->count();
									}

									// user didnt receive this survey yet
									if($checkIfTaken == 0){
										
										// check if user in target networks
										if (isset($surveyOrBotCampaign->network_id)) {
											$network_split = explode(',', $surveyOrBotCampaign->network_id);
											foreach ($network_split as $network_value) {
												if ($network_value == $session->network_id) {
													$found_network = 1;
												}
											}
										} else { $found_network = 1; }
										if(!isset($found_network)){$found_network=0;}
										
										// check if user in target groups
										if (isset($surveyOrBotCampaign->group_id)) {
											$group_split = explode(',', $surveyOrBotCampaign->group_id);
											foreach ($group_split as $group_value) {
												if ($group_value == $session->group_id) {
													$found_group = 1;
												}
											}
										} else { $found_group = 1; }
										if(!isset($found_group)){$found_group=0;}

										// check if user in target branches
										if (isset($surveyOrBotCampaign->branch_id)) {
											$branch_split = explode(',', $surveyOrBotCampaign->branch_id);
											foreach ($branch_split as $branch_value) {
												if ($branch_value == $session->branch_id) {
													$found_branch = 1;
												}
											}
										} else { $found_branch = 1; }
										if(!isset($found_branch)){$found_branch=0;}

										// check if user in targeted Network, Group, Network
										if( $found_network == "1" and $found_group == "1" and $found_branch == "1"){

											// check if this is survey and survey type poll options to collect answers with the question
											if($surveyOrBotCampaign->type == "survey" and $surveyOrBotCampaign->survey_type == "poll"){
												foreach ( DB::table("$Customer->database.survey")->where('campaign_id',$surveyOrBotCampaign->id)->whereNull('u_id')->get() as $option)
												{	
													$surveyOrBotCampaign->question.="\n $option->options";
												}
											}
											// get user data
											$userData = DB::table("$Customer->database.users")->where('u_id',$session->u_id)->first();
											// make sure mobile is correct to avoid any problem and ignore this user if mobile is not valid
											if( isset($userData->u_phone) and strlen($userData->u_phone) >= 8 and strpos($userData->u_phone, ':') === false){
												// check if we found any variables in questions
												// return $surveyOrBotCampaign->question;
												if(isset($surveyOrBotCampaign->question)){

													$currQuestion = $surveyOrBotCampaign->question;
													$currQuestion = @str_replace("@name","$userData->u_name",$currQuestion);
													$currQuestion = @str_replace("@email","$userData->u_email",$currQuestion);
													// check if there is offer code in menu
													if (strpos($currQuestion, '@offerCodes') !== false) { 
														$offerCodes = DB::table("$Customer->database.settings")->where('type', 'MainBotLoyaltyBendingOffersMsg')->value('value');
														foreach( DB::table("$Customer->database.campaign_statistics")->where('type', 'offer')->where('state', '0')->where('u_id', $userData->u_id)->get() as $offers ){
															if(strlen($offers->offer_code) == "8"){
																// this offer code to redeem loyalty program points, so we get there info from Table: 'loyalty_program'
																$offerDesc = DB::table("$Customer->database.loyalty_program")->where('id', $offers->campaign_id)->value('whatsapp');
															}else{
																// that's mean the digits is 6 and this is normal offer code related to normal campaign, so we get there info from Table: 'campaigns'
																$offerDesc = DB::table("$Customer->database.campaigns")->where('id', $offers->campaign_id)->value('offer_desc');
															}
															$offerCodes.="\n 🔘$offers->offer_code \n $offerDesc";
														}
														$offerCodes.="\n";
														$currQuestion = @str_replace("@offerCodes", $offerCodes,$currQuestion);
													}
													// get loyality points
													$loyaltyPoints = $whatsappClass->getCustomerLoyaltyPoints($Customer->database,$session->u_id,$todayDateTime);
													$currQuestion = @str_replace("@points","$loyaltyPoints",$currQuestion);
												}
												
												// send whatsapp Message
												foreach( explode(",",$userData->u_phone) as $to ){
													// delay time betweeb each message
													$delayTime = rand(10,20);
													// get globaa ID
													$globalID = DB::table('users_global')->where('mobile', 'like', '%'.$userData->u_phone.'%')->value('id');
													$currQuestion = @str_replace("@id","$globalID",$currQuestion);
													$currQuestion = @str_replace("@mobile","$to",$currQuestion);
													// encode all message
													$currQuestion = urlencode($currQuestion);
													// start sending
													$sendState = $whatsappClass->send( "", $to , $currQuestion, $Customer->id, $Customer->database,"","","","1",$surveyOrBotCampaign->id);
													sleep($delayTime);
												}
											}
											
											if(isset($sendState) and ($sendState == "in" or $sendState == "ok" or $sendState == "1") ){$sendState=1;}else{$sendState=0;}
											// insert this message into WhatsApp campaign table
											DB::table("$Customer->database.whatsapp_campaign")->insert([['state' => $sendState, 'user_id' => $session->u_id, 'campaign_id' => $surveyOrBotCampaign->id, 'created_at' => $todayDateTime]]);
											// add reach to survey campaign
											DB::table("$Customer->database.campaign_statistics")->insert([['campaign_id' => $surveyOrBotCampaign->id, 'type' => "reach", 'u_id' => $session->u_id, 'created_at' => $todayDateTime]]);
											unset($currQuestion);
											unset($userData);
										}
										unset($sendState);
										unset($found_network);
										unset($found_group);
										unset($found_branch);
									}
								}	
							}
						}
                    }
                }
            }
			////////////////////////////////////////////////
			///////   check if there is a birthdate  ///////
			////////////////////////////////////////////////
			
			if($Customer->whatsapp=="1"){

				// search for birthdate today
				foreach ( DB::table("$Customer->database.users")->whereRaw("DATE_FORMAT(birthdate, '%m-%d') = DATE_FORMAT(now(),'%m-%d')")->selectRaw('u_id, u_name, u_phone, birthdate')->get() as $todayBirthdate ){
					// delay time betweeb each message
					$delayTime = rand(10,20);
					
					// get offer details
					$offerCampaignData = DB::table("$Customer->database.campaigns")->where('type', 'birthdaysCelebrationOfferUnique')->first();
					// check if user recevied this message today or not yet
					if( DB::table("$Customer->database.history")->where('operation', 'birthdaysCelebrationOfferReminder')->where('u_id',$todayBirthdate->u_id)->where('add_date',$today)->count() == 0 ){ $limitationPass = 1; }else{ $limitationPass = 0; }

					if($limitationPass == 1){
						// get offer code, insert offer code into DB, insert history for tracking, replace variables, encoding msg, sending
						$offerCode = DB::table("$Customer->database.history")->where('operation','birthdaysCelebrationOffer')->where('u_id', $todayBirthdate->u_id)->orderBy('id', 'desc')->first();
						DB::table("$Customer->database.history")->insert([['operation' => 'birthdaysCelebrationOfferReminder', 'type1' => 'cron', 'type2' => 'auto', 'u_id' => $todayBirthdate->u_id, 'add_date' => $today, 'add_time' => $today_time ]]);
						$sendMsg = DB::table("$Customer->database.settings")->where('type', 'whatsappBirthdayMsg')->value('value');
						$sendMsg = @str_replace("@name","$todayBirthdate->u_name",$sendMsg);
						$sendMsg = @str_replace("@birthdate","$todayBirthdate->birthdate",$sendMsg);
						$sendMsg = @str_replace("@offer","$offerCode->details",$sendMsg);
						$sendMsg = urlencode($sendMsg);
						$sendState = $whatsappClass->send( "", $todayBirthdate->u_phone , $sendMsg, $Customer->id, $Customer->database,"","","","1");
						sleep($delayTime);	
					}
					unset($limitationPass);
				}

				// get whatsappBirthdaySendOfferBeforeNoDays
				$whatsappBirthdaySendOfferBeforeNoDays = DB::table("$Customer->database.settings")->where('type', 'whatsappBirthdaySendOfferBeforeNoDays')->value('value');
				// search for birth date after #no of days
				foreach( DB::table("$Customer->database.users")->whereRaw("DATE_FORMAT(birthdate, '%m-%d') = DATE_FORMAT(NOW() + INTERVAL $whatsappBirthdaySendOfferBeforeNoDays DAY, '%m-%d')")->selectRaw('u_id, u_name, u_phone, birthdate')->get() as $afterDaysBirthdate ){
					// get offer details
					$offerCampaignData = DB::table("$Customer->database.campaigns")->where('type', 'birthdaysCelebrationOfferUnique')->first();
					// check if there is limit or not
					if(isset($offerCampaignData->offer_limit) and $offerCampaignData->offer_limit!="0" and $offerCampaignData->offer_limit!="" and $offerCampaignData->offer_limit!=null){
						if( $offerCampaignData->offer_limit > DB::table("$Customer->database.history")->where('operation', 'birthdaysCelebrationOffer')->count() ){ $limitationPass = 1; }
					}else{ $limitationPass = 1; }
					// check if user take this offer or not yet
					if( DB::table("$Customer->database.history")->where('operation', 'birthdaysCelebrationOffer')->where('u_id',$afterDaysBirthdate->u_id)->where('add_date',$today)->count() == 0 ){ $limitationPass = 1; }else{ $limitationPass = 0; }

					if($limitationPass == 1){
						// generate offer code, insert offer code into DB, insert history for tracking, replace variables, encoding msg, sending
						$newOfferCode = rand(100000, 999999);
						DB::table("$Customer->database.campaign_statistics")->insert([['type' => 'offer', 'campaign_id' => $offerCampaignData->id, 'u_id' => $afterDaysBirthdate->u_id, 'state' => '0', 'offer_code' => $newOfferCode, 'created_at' => $todayDateTime]]);
						DB::table("$Customer->database.history")->insert([['operation' => 'birthdaysCelebrationOffer', 'type1' => 'cron', 'type2' => 'auto', 'u_id' => $afterDaysBirthdate->u_id, 'details' => $newOfferCode, 'add_date' => $today, 'add_time' => $today_time ]]);
						$sendMsg="$offerCampaignData->offer_desc";
						$sendMsg = @str_replace("@name","$afterDaysBirthdate->u_name",$sendMsg);
						$sendMsg = @str_replace("@offer","$newOfferCode",$sendMsg);
						$sendMsg = @str_replace("@birthdate","$afterDaysBirthdate->birthdate",$sendMsg);
						$sendMsg = urlencode($sendMsg);
						$sendState = $whatsappClass->send( "", $afterDaysBirthdate->u_phone , $sendMsg, $Customer->id, $Customer->database,"","","","1");
						sleep($delayTime);	
					}
					unset($limitationPass);
				}
			}
			
			
		}
		return "<center><strong>Done</strong></center>";
	}
}