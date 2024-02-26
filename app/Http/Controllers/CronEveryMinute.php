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

class CronEveryMinute extends Controller
{
    public function cronEveryMinute(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
		$microsystemSMSserver = new App\Http\Controllers\ApiController();
		
		$allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
		// $allCustomers=DB::table('customers')->where('database','demo')->groupBy('database')->get(); // for test only
        foreach( $allCustomers as $customer )
        {
			/*
			///////////////////////////////////////////////////////////////////////////////////////////////
			///      					 User creation in Microsoft Dynamics						    ///
			///////////////////////////////////////////////////////////////////////////////////////////////
			foreach( DB::table($customer->database.'.users')->where('suspend', '0')->whereNull('pos_id')->get() as $newUsers ){

				if($newUsers->u_gender == "1"){$gender = "Male";}
				elseif($newUsers->u_gender == "0"){$gender = "Female";}
				else{$gender = " ";}
				// $url = "http://10.6.10.4:1247/DevU/WS/Fortex/Codeunit/Members";
				$url = "http://10.6.10.4:1247/DevU/WS/Fortex/Codeunit/Members";
				//setting the curl headers
				$headers = array(
					"Content-type: text/xml;charset=\"utf-8\"",
					"Accept: text/xml",
					"Cache-Control: no-cache",
					"Pragma: no-cache",
					"SOAPAction: \"urn:microsoft-dynamics-schemas/page/member\"",
					"Authorization: Basic TUlDUk9TT0ZUX1NSVl9OXEFETUlOSVNUUkFUT1I6RUFJWXhQck9ESEFZbFBFNm52RmhEb2d5dWU1aElIcEYwZWtHeUV6aCtkOD0="
				);

				$xmlRequest = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
					<Body>
						<CreateMemberAccount xmlns="urn:microsoft-dynamics-schemas/codeunit/Members">
							<name>'.$newUsers->u_name.'</name>
							<phone>'.$newUsers->u_phone.'</phone>
							<gender>'.$gender.'</gender>
						</CreateMemberAccount>
					</Body>
				</Envelope>';
				try{

					$ch = curl_init();

					//setting the curl options
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_POSTFIELDS,  $xmlRequest);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_VERBOSE, 0);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

					$data = curl_exec($ch);
					// Success msg: <Soap:Envelope xmlns:Soap="http://schemas.xmlsoap.org/soap/envelope/"><Soap:Body><CreateMemberAccount_Result xmlns="urn:microsoft-dynamics-schemas/codeunit/Members"><return_value/></CreateMemberAccount_Result></Soap:Body></Soap:Envelope>
					// Failed Msg: a:Microsoft.Dynamics.Nav.Types.Exceptions.NavCSideDuplicateKeyExceptionThe Member Account Upgrade Entry already exists. Identification fields and values: Account No.='010310102021',Date='10/10/21'The Member Account Upgrade Entry already exists. Identification fields and values: Account No.='010310102021',Date='10/10/21'
					//convert the XML result into array
					if($data === false){
						$error = curl_error($ch);
						echo "<br>Error in Microsoft Dynamics: ".$error."<br>";
						
					}else{
						// connection successfully
						if (strpos($data, 'CreateMemberAccount_Result') !== false) {
							echo "<br>User account creation successfully in Microsoft Dynamics - $newUsers->u_name - $newUsers->u_uname<br>";
							DB::table("$customer->database.users")->where('u_id',$newUsers->u_id)->update(['pos_id' => "Done MicrosoftDynamics $todayDateTime"]);
						}else{
							echo "<br>User account creation failed in Microsoft Dynamics - $newUsers->u_name - $newUsers->u_uname<br>";
							DB::table("$customer->database.users")->where('u_id',$newUsers->u_id)->update(['pos_id' => "Failed MicrosoftDynamics $todayDateTime"]);
						}
						// return simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
						// return $data = json_decode(json_encode(simplexml_load_string($data)), true);
					}

					curl_close($ch);

				}catch(Exception  $e){
					echo 'Error in Microsoft Dynamics: '.$e->getMessage();
				}

			}
			*/
            ///////////////////////////////////////////////////////////////////////////////////////////////
			///      close any active group_temporary_switch session reached to finishing duration      ///
			///////////////////////////////////////////////////////////////////////////////////////////////	
            foreach( DB::table($customer->database.'.group_temporary_switch')->where('finishing_at', '<', $todayDateTime)->where('state', '1')->where('approved', '1')->get() as $session ){

				// close session in `group_temporary_switch` table
				DB::table("$customer->database.group_temporary_switch")->where('id',$session->id)->update(['state' => '0']);
				// update previously_group_id into user DB
				DB::table("$customer->database.users")->where('u_id',$session->u_id)->update(['group_id' => $session->previously_group_id]); 
				// disconnect Mikrotik session to apply new group speed
				DB::table("$customer->database.radacct")->where('u_id',$session->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
				// send notification to user
				$userData = DB::table("$customer->database.users")->where('u_id',$session->u_id)->first();
                $notificationMsg = "Your speed-up request has been finished."; // ⏰
                $whatsappClass->send( "", $userData->u_phone , $notificationMsg, $customer->id, $customer->database);
				$microsystemSMSserver->sendMicrosystemSMS($customer->database, 'chatbot', $userData->u_phone, $notificationMsg);
			}
			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			///      close expired user requests not approved in group_temporary_switch after 1 day of request      ///
			///////////////////////////////////////////////////////////////////////////////////////////////////////////
            foreach( DB::table($customer->database.'.group_temporary_switch')->where('approved', null)->where('state', '1')->where('requested_by', '2')->whereRaw('created_at + interval 1 day <= ?', [$todayDateTime])->get() as $session ){
				// close session in `group_temporary_switch` table
				DB::table("$customer->database.group_temporary_switch")->where('id',$session->id)->update(['state' => '0']);
				// send notification to user
				$userData = DB::table("$customer->database.users")->where('u_id',$session->u_id)->first();
                $notificationMsg = "Your speed-up request has been expired."; //⏰ 
                $whatsappClass->send( "", $userData->u_phone , $notificationMsg, $customer->id, $customer->database);
				$microsystemSMSserver->sendMicrosystemSMS($customer->database, 'chatbot', $userData->u_phone, $notificationMsg);
			}
			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			///     close expired URL filter session reached to finishing duration in urlfilter_temporary_switch    ///
			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			foreach( DB::table($customer->database.'.urlfilter_temporary_switch')->whereNotNull('finishing_at')->where('finishing_at', '<', $todayDateTime)->where('state', '1')->get() as $urlFilterRequest ){
					
				//check if the URL is the group listed
				$targetURLs = [];
				if(strpos($urlFilterRequest->url, "facebook") !== false or strpos($urlFilterRequest->url, "Facebook") !== false){
					$targetURLs[]="facebook.com";
					$targetURLs[]="fb.com";
					$targetURLs[]="fbcdn.net";
				}elseif(strpos($urlFilterRequest->url, "youtube") !== false){
					$targetURLs[]="youtube.com";
					$targetURLs[]="googlevideo.com";  
				}elseif(strpos($urlFilterRequest->url, "video") !== false){
					$targetURLs[]="tiktokcdn.com";
					$targetURLs[]="video-hbe1-1.xx.fbcdn.net";
					$targetURLs[]="googlevideo";
					$targetURLs[]="youtube";
				}elseif(strpos($urlFilterRequest->url, "twitter") !== false){
					$targetURLs[]="twitter.com";
					$targetURLs[]="twimg.com";
				}elseif(strpos($urlFilterRequest->url, "instagram") !== false){
					$targetURLs[]="instagram.com";
					$targetURLs[]="cdninstagram.com";
				}elseif(strpos($urlFilterRequest->url, "tiktok") !== false){
					$targetURLs[]="tiktok";
					$targetURLs[]="tiktokcdn.com";
				}elseif(strpos($urlFilterRequest->url, "netflix") !== false){
					$targetURLs[]="netflix";
					$targetURLs[]="netflixcdn";
				}
				
				
				elseif(strpos($urlFilterRequest->url, "social") !== false){
					$targetURLs[]="instagram.com";
					$targetURLs[]="cdninstagram.com";
					$targetURLs[]="facebook.com";
					$targetURLs[]="fb.com";
					$targetURLs[]="fbcdn.net";
					$targetURLs[]="twitter.com";
					$targetURLs[]="twimg.com";
					$targetURLs[]="instagram.com";
					$targetURLs[]="cdninstagram.com";
					$targetURLs[]="tiktok";
					$targetURLs[]="tiktokcdn.com";
				}elseif(strpos($urlFilterRequest->url, "windows") !== false){
					//?????????????????????
				}elseif(strpos($urlFilterRequest->url, "updates") !== false){
					//?????????????????????
				}elseif(strpos($urlFilterRequest->url, "internet") !== false){
					//?????????????????????
				}elseif(strpos($urlFilterRequest->url, "sex") !== false or strpos($urlFilterRequest->url, "المواقع الجنسيه") !== false){
					$targetURLs[]="sex";
				}elseif(strpos($urlFilterRequest->url, "adult") !== false){
					$targetURLs[]="adult";
				}else{
					$urlFilterRequest->url = str_replace("http://","",$urlFilterRequest->url);
					$urlFilterRequest->url = str_replace("https://","",$urlFilterRequest->url);
					$targetURLs[] = preg_replace('/\s+/', '', $urlFilterRequest->url);
				}

				// check if apply for all network
				if($urlFilterRequest->apply_for == "network"){
					
					// get all groups
					foreach( DB::table($customer->database.'.area_groups')->where('is_active','1')->get() as $group ){
						
						// insert new urls, or delete if admin needs to unblock
						foreach($targetURLs as $url){
							if($urlFilterRequest->block_or_unblock!='block'){
								DB::table("$customer->database.url_filter")->insert(['group_id' => $group->id, 'url' => $url]);
							}else{
								DB::table("$customer->database.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
							}
						}

						// check if there is any remining url filter in `url_filter` table, to take a desition to switch off url filter if empty or not
						if( DB::table("$customer->database.url_filter")->where('group_id', $group->id)->count() > 0 ){
							// still there is many records, SO we will keeb `url_filter_state` is on
							// update group url filter fields and make script avilable for next Mikrotik pull
							DB::table("$customer->database.area_groups")->where('id',$group->id)->update(['url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
						}else{
							// no more records, so we will turn off `url_filter_state`
							// update group url filter fields and make script avilable for next Mikrotik pull
							DB::table("$customer->database.area_groups")->where('id',$group->id)->update(['url_filter_state' => '0', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
						}
													
						// update record in `urlfilter_temporary_switch` to switch of state
						DB::table("$customer->database.urlfilter_temporary_switch")->where(['id' => $urlFilterRequest->id])->update(['state' => '0']);
					}
					
				}else if($urlFilterRequest->apply_for == "group"){
					
					$group = DB::table($customer->database.'.area_groups')->where('id', $urlFilterRequest->group_id)->first();

					// insert new urls, or delete if admin needs to unblock
					foreach($targetURLs as $url){
						if($urlFilterRequest->block_or_unblock!='block'){
							DB::table("$customer->database.url_filter")->insert(['group_id' => $group->id, 'url' => $url]);
						}else{
							DB::table("$customer->database.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
						}
					}

					// check if there is any remining url filter in `url_filter` table, to take a desition to switch off url filter if empty or not
					if( DB::table("$customer->database.url_filter")->where('group_id', $group->id)->count() > 0 ){
						// still there is many records, SO we will keeb `url_filter_state` is on
						// update group url filter fields and make script avilable for next Mikrotik pull
						DB::table("$customer->database.area_groups")->where('id',$group->id)->update(['url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
					}else{
						// no more records, so we will turn off `url_filter_state`
						// update group url filter fields and make script avilable for next Mikrotik pull
						DB::table("$customer->database.area_groups")->where('id',$group->id)->update(['url_filter_state' => '0', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
					}
					
					// update record in `urlfilter_temporary_switch` to switch of state
					DB::table("$customer->database.urlfilter_temporary_switch")->where(['id' => $urlFilterRequest->id])->update(['state' => '0']);

				}else if($urlFilterRequest->apply_for == "user"){
					// check if target user exist or not
					$user = DB::table($customer->database.'.users')->where('u_id', $urlFilterRequest->user_id)->first();
					// get a group data for this user
					$group = DB::table($customer->database.'.area_groups')->where('id', $user->group_id)->first();
					
					// check if this user have a self rules or not 
					if($group->as_system == "1"){
						// hava a self rules
						if($urlFilterRequest->block_or_unblock!='block'){
							// blocking,

							// so we will add new url filter directly to this group
							// update group url filter fields and make script avilable for next Mikrotik pull
							DB::table("$customer->database.area_groups")->where('id',$group->id)->update(['url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
							// insert new urls
							foreach($targetURLs as $url){
								DB::table("$customer->database.url_filter")->insert(['group_id' => $group->id, 'url' => $url]);
							}
							// update record in `urlfilter_temporary_switch` to switch of state
							DB::table("$customer->database.urlfilter_temporary_switch")->where(['id' => $urlFilterRequest->id])->update(['state' => '0']);
							
						}else{
							// unblocking, 
							
							// check if there is the last unblocking record (to destroy the selfrules group ) or just remove the target URL only
							if( DB::table("$customer->database.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => 'user'])->orderBy('id', 'desc')->count() > 1 ){
								// there is many blocked sites, SO we will remove the target URL only
								// delete target URL urls only
								foreach($targetURLs as $url){
									DB::table("$customer->database.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
								}
								// update record in `urlfilter_temporary_switch` to switch of state
								DB::table("$customer->database.urlfilter_temporary_switch")->where(['id' => $urlFilterRequest->id])->update(['state' => '0']);
								// update group `url_filter_state` fields to force Mikrotik to restruture URLs filter in next Mikrotik pull 
								DB::table("$customer->database.area_groups")->where('id',$group->id)->update(['url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
							}else{
								// there is just 1 record to block it, SO we will proceed in (destroying the selfrules group)
								// We will lookup in `urlfilter_temporary_switch` if there is a previus group for this user
								// if no, we will clean up this self rules
								// if yes, we will destroy the self rules, then we will assign back to the previously group
								if( !isset($urlFilterRequest->previously_group_id) ){
									// no Previously url filter, so we will clean up this self rules
									// update group `url_filter_state` fields and make script avilable for next Mikrotik pull
									DB::table("$customer->database.area_groups")->where('id',$group->id)->update(['url_filter_state' => '0', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
									// update record in `urlfilter_temporary_switch` to switch of state
									DB::table("$customer->database.urlfilter_temporary_switch")->where(['id' => $urlFilterRequest->id])->update(['state' => '0']);
								}else{
									// yes, we will destroy the self rules, then we will assign back to the previously group
									// set user to the previously profile
									DB::table("$customer->database.users")->where('u_id', $user->u_id)->update(['group_id' => $urlFilterRequest->previously_group_id, 'Selfrules' => '0']);
									// deactivate this rules, and change group name to be 'u_uname' ex.(01061030454) because deleting the script from Mikrotik need this naming
									DB::table("$customer->database.area_groups")->where('id', $user->group_id)->update(['url_filter_state' => '0', 'url_filter_type'=> '1', 'change_url_filter' => '1', 'name' => $user->u_uname]); 
									// disconnect user to apply (new AddressList name) to match with the previus group name
									DB::table("$customer->database.radacct")->where('u_id',$user->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
									// update record in `urlfilter_temporary_switch` to switch of state
									DB::table("$customer->database.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => $urlFilterRequest->apply_for, 'url' => $urlFilterRequest->url])->update(['state' => '0']);
								}
							}
						}
					}else{
						if($urlFilterRequest->block_or_unblock!='block'){
							// BLOCKING have a normal group, so we will copy this group data, then create new self rules group with the same previous group criteria
							// create a new self rules group, from the latest group
							$newGroupId = DB::table("$customer->database.area_groups")->insertGetId(['name' => "$user->u_name Special Rules", 'is_active' => '1', 'as_system' => '1', 'radius_type' => $group->radius_type, 'url_redirect' => $group->url_redirect, 'url_redirect_Interval' => $group->url_redirect_Interval, 'session_time' => $group->session_time, 'port_limit' => $group->port_limit, 'idle_timeout' => $group->idle_timeout, 'quota_limit_upload' => $group->quota_limit_upload, 'quota_limit_download' => $group->quota_limit_download, 'quota_limit_total' => $group->quota_limit_total, 'speed_limit' => $group->speed_limit, 'renew' => $group->renew, 'if_downgrade_speed' => $group->if_downgrade_speed, 'end_speed' => $group->end_speed, 'network_id' => $group->network_id, 'auto_login' => $group->auto_login, 'auto_login_expiry' => $group->auto_login_expiry, 'limited_devices' => $group->limited_devices,  'created_at' => $created_at,  'notes' => $group->notes, 'url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
							// insert previously rules
							foreach(DB::table("$customer->database.url_filter")->where('group_id', $group->id)->get() as $url){
								DB::table("$customer->database.url_filter")->insert(['group_id' => $newGroupId, 'url' => $url->url]);
							}
							// insert new rules
							foreach($targetURLs as $url){
								DB::table("$customer->database.url_filter")->insert(['group_id' => $newGroupId, 'url' => $url]);
							}
							// set user to the new profile
							DB::table("$customer->database.users")->where('u_id', $user->u_id)->update(['group_id' => $newGroupId, 'Selfrules' => '1']);
							// disconnect user to apply selfrules (new AddressList name) to match with the blocking name
							DB::table("$customer->database.radacct")->where('u_id',$user->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
							// update record in `urlfilter_temporary_switch` to switch of state
							DB::table("$customer->database.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => $urlFilterRequest->apply_for, 'url' => $urlFilterRequest->url])->update(['state' => '0']);
						}
					}
				}

				// send notification to admin
				$adminData = DB::table("$customer->database.admins")->where('id',$urlFilterRequest->admin_id)->first();
				if($urlFilterRequest->apply_for == "user"){ $name = DB::table("$customer->database.users")->where('u_id',$urlFilterRequest->user_id)->value('u_name'); }
				elseif($urlFilterRequest->apply_for == "group"){ $name = DB::table("$customer->database.area_groups")->where('id',$urlFilterRequest->group_id)->value('name'); }
				else{$name="";}
                $notificationMsg = "Your order to $urlFilterRequest->block_or_unblock $urlFilterRequest->url for $urlFilterRequest->apply_for $name\n started at $urlFilterRequest->starting_at has been finished."; //⏰ 
                $whatsappClass->send( "", $adminData->mobile, $notificationMsg, $customer->id, $customer->database);
				$microsystemSMSserver->sendMicrosystemSMS($customer->database, 'chatbot', $adminData->mobile, $notificationMsg);
			}

			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			///               close expired internet mode sessions in internet_mode_temporary_switch                ///
			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			foreach( DB::table($customer->database.'.internet_mode_temporary_switch')->whereNotNull('finishing_at')->where('finishing_at', '<', $todayDateTime)->where('state', '1')->get() as $internetModeRequest ){
				// update branch `internet_mode`
				DB::table("$customer->database.branches")->where('id',$internetModeRequest->branch_id)->update(['internet_mode' => 'default', 'change_internet_mode'=>'1']); 
				// insert history for menu tracking
				DB::table("$customer->database.history")->insert(['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'internet_mode', 'details' => '1', 'a_id' => $internetModeRequest->admin_id, 'add_date' => $today, 'add_time' => $today_time] );
				// deactivate request
				DB::table("$customer->database.internet_mode_temporary_switch")->where('id',$internetModeRequest->id)->update(['state' => '0']); 
				// send notification to admin
				$adminData = DB::table("$customer->database.admins")->where('id',$internetModeRequest->admin_id)->first();
				$name = DB::table("$customer->database.branches")->where('id',$internetModeRequest->branch_id)->value('name');
				$notificationMsg = "Your order to set $internetModeRequest->internet_mode mode for branch $name\n started at $internetModeRequest->starting_at has been finished."; //⏰ 
                $whatsappClass->send( "", $adminData->mobile, $notificationMsg, $customer->id, $customer->database);
				$microsystemSMSserver->sendMicrosystemSMS($customer->database, 'chatbot', $adminData->mobile, $notificationMsg);
			}
			

		}
		
		//////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////
		return "<center><strong>Done</strong></center>";
	}
}