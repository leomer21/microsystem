<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	$type=$obj['type'];// all - active - inactive
	$userID=$obj['userID'];
	$network_code=$obj['networkCode'];
	$u_uname=$obj['uname'];
	$u_area=$obj['groupID'];
	
	
	require_once 'include/config.php';
	 ///////////////////////////////////////////////////       
	        // Connect to system database
	 ///////////////////////////////////////////////////     
	 
		if($sys_local_or_web=="local")
		{
			// do nothing
		}else {
			$can_go="yes";
		}
		
		if($can_go=="yes"){
		$sys_dbhost = 'localhost';
		$conn_user = @mysql_connect($sys_dbhost, $sys_db_user, $sys_db_pass, true);
		@mysql_select_db($sys_db_name,$conn_user);
		mysql_set_charset('utf8');  
		///////////////////////////////////////////////////        
		//			 Connect to admin database 
		///////////////////////////////////////////////////

		// for encrypt
		$login_user=@mysql_real_escape_string($login_user);
		$login_password=@mysql_real_escape_string($login_password);
		$get_user_data="select * from `admins` where ( `a_uname`='$login_user' and `a_password`='$login_password' )";
		$r_get_user_data=@mysql_query($get_user_data,$conn_user);
		
		//include_once 'include/sql/sql.php';
		if(@mysql_num_rows($r_get_user_data)>0)
		{
			$row_get_user_data=@mysql_fetch_array($r_get_user_data);
			$db_id=$row_get_user_data['a_id'];
			$db_a_name=$row_get_user_data['a_name'];
			$db_a_uname=$row_get_user_data['a_uname'];
			$db_a_password=$row_get_user_data['a_password'];
			$db_a_mail=$row_get_user_data['a_email'];
			$db_a_mobile=$row_get_user_data['a_mobile'];
			
			if($db_a_uname==$login_user)
			{	
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////

				if($type or $userID or $network_code or $u_uname or $u_area)
				{
					if($type){
						if($type=="all"){$selectSQL="select * from `users`"; $canSearch="yes";}
						if($type=="active"){$selectSQL="select * from `users` where `u_state`='active'"; $canSearch="yes";}
						if($type=="inactive"){$selectSQL="select * from `users` where `u_state`='inactive'"; $canSearch="yes";}
					}elseif ($userID){$selectSQL="select * from `users` where `u_id`='$userID'"; $canSearch="yes";}
					elseif ($network_code){$selectSQL="select * from `users` where `network_code`='$network_code'"; $canSearch="yes";}
					elseif ($u_uname){$selectSQL="select * from `users` where `u_uname`='$u_uname'"; $canSearch="yes";}
					elseif ($u_area){$selectSQL="select * from `users` where `u_area`='$u_area'"; $canSearch="yes";}
					else {// not found any check data
						$status=0;if($login_lang=="ar"){$status_message=$notFoundGetAttributes_ar;}else{ $status_message=$notFoundGetAttributes_en;}
						$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);}
						
					if($canSearch=="yes")
					{
						$r_selectSQL=@mysql_query($selectSQL);
						if(@mysql_num_rows($r_selectSQL)>0)
						{
							$num_of_results=@mysql_num_rows($r_selectSQL);
							$json = array("status" => "1","statusMessage" => "success", "numberOfResults" => "$num_of_results");
							while ($row_selectSQL=@mysql_fetch_array($r_selectSQL)) {
								
								$getted_u_id=$row_selectSQL['u_id'];
								$row_bot_data['userID']=$getted_u_id;
								$getted_personal_code=$row_selectSQL['personal_code'];
								$row_bot_data['personalCode']=$getted_personal_code;
								$getted_suspend=$row_selectSQL['suspend'];
								$row_bot_data['suspend']=$getted_suspend;
								$getted_user_language=$row_selectSQL['user_language'];
								$row_bot_data['language']=$getted_user_language;
								$getted_creadit=$row_selectSQL['creadit'];
								$row_bot_data['creadit']=$getted_creadit;
								$getted_network_name=$row_selectSQL['network_name'];
								$row_bot_data['networkName']=$getted_network_name;
								$getted_network_code=$row_selectSQL['network_code'];
								$row_bot_data['networkCode']=$getted_network_code;
								$getted_hotspot_or_ppp=$row_selectSQL['hotspot_or_ppp'];
								$row_bot_data['hotspotOrPPP']=$getted_hotspot_or_ppp;
								$getted_u_name=$row_selectSQL['u_name'];
								$row_bot_data['name']=$getted_u_name;
								$getted_u_uname=$row_selectSQL['u_uname'];
								$row_bot_data['userNname']=$getted_u_uname;
								$getted_u_password=$row_selectSQL['u_password'];
								$row_bot_data['password']=$getted_u_password;
								$getted_u_phone=$row_selectSQL['u_phone'];
								$row_bot_data['phone']=$getted_u_phone;
								$getted_u_address=$row_selectSQL['u_address'];
								$row_bot_data['address']=$getted_u_address;
								$getted_u_mobile=$row_selectSQL['u_mobile'];
								$row_bot_data['mobile']=$getted_u_mobile;
								$getted_u_area=$row_selectSQL['u_area'];
								$row_bot_data['groupID']=$getted_u_area;
								$getted_u_mail=$row_selectSQL['u_mail'];
								$row_bot_data['mail']=$getted_u_mail;
								$getted_u_start_date=$row_selectSQL['u_start_date'];
								$row_bot_data['startDate']=$getted_u_start_date;
								$getted_u_birth_date=$row_selectSQL['u_birth_date'];
								$row_bot_data['birthDate']=$getted_u_birth_date;
								$getted_u_state=$row_selectSQL['u_state'];
								$row_bot_data['state']=$getted_u_state;
								$getted_u_macaddress=$row_selectSQL['u_macaddress'];
								$row_bot_data['macaddress']=$getted_u_macaddress;
								$getted_u_ip=$row_selectSQL['u_ip'];
								$row_bot_data['ip']=$getted_u_ip;
								$getted_u_gender=$row_selectSQL['u_gender'];
								$row_bot_data['gender']=$getted_u_gender;
								$getted_u_card_validate_date=$row_selectSQL['u_card_validate_date'];
								$row_bot_data['expireDateMonthlyPackage']=$getted_u_card_validate_date;
								$getted_u_card_date_of_charging=$row_selectSQL['u_card_date_of_charging'];
								$row_bot_data['lastChargeDate']=$getted_u_card_date_of_charging;
								$getted_u_points=$row_selectSQL['u_points'];
								$row_bot_data['smsCredit']=$getted_u_points;
								$getted_user_day_limit=$row_selectSQL['user_day_limit'];
								$row_bot_data['expireDateValidityPackage']=$getted_user_day_limit;
								$getted_mode=$row_selectSQL['mode'];
								$row_bot_data['mode']=$getted_mode;
								
								$getted_note=$row_selectSQL['note'];
								$row_bot_data['note']=$getted_note;
								$getted_self_rules_radius=$row_selectSQL['self_rules_radius'];
								$row_bot_data['selfRulesRadius']=$getted_self_rules_radius;
								$getted_radius_type=$row_selectSQL['radius_type'];
								$row_bot_data['radiusType']=$getted_radius_type;
								$getted_url_redirect=$row_selectSQL['url_redirect'];
								$row_bot_data['urlRedirect']=$getted_url_redirect;
								$getted_url_redirect_Interval=$row_selectSQL['url_redirect_Interval'];
								$row_bot_data['urlRedirectInterval']=$getted_url_redirect_Interval;
								$getted_session_time=$row_selectSQL['session_time'];
								$row_bot_data['sessionTime']=$getted_session_time;
								$getted_port_limit=$row_selectSQL['port_limit'];
								$row_bot_data['portLimit']=$getted_port_limit;
								$getted_idle_timeout=$row_selectSQL['idle_timeout'];
								$row_bot_data['idleTimeout']=$getted_idle_timeout;
								$getted_quota_limit_upload=$row_selectSQL['quota_limit_upload'];
								$row_bot_data['quotaLimitUpload']=$getted_quota_limit_upload;
								$getted_quota_limit_download=$row_selectSQL['quota_limit_download'];
								$row_bot_data['quotaLimitDownload']=$getted_quota_limit_download;
								$getted_quota_limit_total=$row_selectSQL['quota_limit_total'];
								$row_bot_data['quotaLimitTotal']=$getted_quota_limit_total;
								$getted_speed_limit=$row_selectSQL['speed_limit'];
								$row_bot_data['speedLimit']=$getted_speed_limit;
								$getted_renew=$row_selectSQL['renew'];
								$row_bot_data['renewPerDay']=$getted_renew;
								$getted_if_downgrade_speed=$row_selectSQL['if_downgrade_speed'];
								$row_bot_data['ifDowngradeSpeed']=$getted_if_downgrade_speed;
								$getted_end_speed=$row_selectSQL['end_speed'];
								$row_bot_data['gettedEndSpeed']=$getted_end_speed;
								
								
								$json['users'][]=$row_bot_data;
								
							}//while ($row_selectSQL=@mysql_fetch_array($r_selectSQL))
							
							echo json_encode($json);
							
						}else{
						$status=0;if($login_lang=="ar"){$status_message=$notFoundResult_ar;}else{ $status_message=$notFoundResult_en;}
						$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);}
						
					}//if($canSearch=="yes")
						
							
							
							
					
						
				}//if($u_name and $u_uname and $u_password)
				else{
				$status=0;if($login_lang=="ar"){$status_message=$errorRequiredData_ar;}else{ $status_message=$errorRequiredData_en;}
				$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
				echo $data = json_encode($data_before);}
				
				
			
				
				
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
				
			}//if($db_a_uname==$login_user)
			else{
			$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_password_ar;}else{ $status_message=$error_in_username_or_password_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);}
			
		}//if(@mysql_num_rows($r_get_user_data)>0)
		else{
			$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_password_ar;}else{ $status_message=$error_in_username_or_password_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);}

		unset($can_go);
		}//if($can_go=="yes")
		     
	  
	
@mysql_close($conn_user);
	
                    
?>