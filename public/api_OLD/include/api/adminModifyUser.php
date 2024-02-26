<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	$userID=$obj['userID'];
	$network_name=$obj['networkName'];
	$network_code=$obj['networkCode'];
	$hotspot_or_ppp=$obj['hotspotOrPPP'];//hotspot
	$u_name=$obj['name'];
	$u_uname=$obj['uname'];
	$u_password=$obj['uPassword'];
	$u_phone=$obj['phone'];
	$u_mobile=$obj['mobile'];
	$u_area=$obj['groupID'];
	$u_address=$obj['address'];
	$u_mail=$obj['mail'];
	$u_gender=$obj['gender'];
	$u_birth_date=$obj['birthDate'];
	$u_start_date=$obj['startDate'];
	$u_state=$obj['state'];// active
	$u_ip=$obj['ip'];
	$u_macaddress=$obj['macaddress'];
	$self_rules_radius=$obj['selfRulesRadius'];
	$radius_type=$obj['radiusType'];//mikrotik
	$url_redirect=$obj['urlRedirect'];
	$url_redirect_Interval=$obj['urlRedirectInterval'];
	$session_time=$obj['sessionTime'];
	$port_limit=$obj['portLimit'];
	$idle_timeout=$obj['idleTimeout'];
	$quota_limit_upload=$obj['quotaLimitUpload'];
	$quota_limit_download=$obj['quotaLimitDownload'];
	$quota_limit_total=$obj['quotaLimitTotal'];
	$speed_limit=$obj['speedLimit'];
	$if_downgrade_speed=$obj['ifDowngradeSpeed'];
	$end_speed=$obj['endSpeed'];
	
	
	
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

				if($userID)
				{
					
					$checkIfUserExist="select `u_id` from `users` where `u_id`='$userID'";
					$r_checkIfUserExist=@mysql_query($checkIfUserExist);
					if(@mysql_num_rows($r_checkIfUserExist)>0)
						{
							if($network_name){
									@mysql_query("update `users` set `network_name`='$network_name' where `u_id`='$userID'");
								}
								
							if($network_code){
									@mysql_query("update `users` set `network_code`='$network_code' where `u_id`='$userID'");
								}
							if($hotspot_or_ppp){
									@mysql_query("update `users` set `hotspot_or_ppp`='$hotspot_or_ppp' where `u_id`='$userID'");
								}
							if($u_name){
									@mysql_query("update `users` set `u_name`='$u_name' where `u_id`='$userID'");
								}
							if($u_uname){
									@mysql_query("update `users` set `u_uname`='$u_uname' where `u_id`='$userID'");
								}
							if($u_password){
									@mysql_query("update `users` set `u_password`='$u_password' where `u_id`='$userID'");
								}
							if($u_phone){
									@mysql_query("update `users` set `u_phone`='$u_phone' where `u_id`='$userID'");
								}
							if($u_mobile){
									@mysql_query("update `users` set `u_mobile`='$u_mobile' where `u_id`='$userID'");
								}
							if($u_area){
									@mysql_query("update `users` set `u_area`='$u_area' where `u_id`='$userID'");
								}
							if($u_address){
									@mysql_query("update `users` set `u_address`='$u_address' where `u_id`='$userID'");
								}
							if($u_mail){
									@mysql_query("update `users` set `u_mail`='$u_mail' where `u_id`='$userID'");
								}
							if($u_gender){
									@mysql_query("update `users` set `$u_gender`='$u_gender' where `u_id`='$userID'");
								}
							if($u_birth_date){
									@mysql_query("update `users` set `$u_birth_date`='$u_birth_date' where `u_id`='$userID'");
								}
							if($u_start_date){
									@mysql_query("update `users` set `$u_start_date`='$u_start_date' where `u_id`='$userID'");
								}
							if($u_state){
									@mysql_query("update `users` set `$u_state`='$u_state' where `u_id`='$userID'");
								}
							if($u_ip){
									@mysql_query("update `users` set `$u_ip`='$u_ip' where `u_id`='$userID'");
								}
							if($u_macaddress){
									@mysql_query("update `users` set `$u_macaddress`='$u_macaddress' where `u_id`='$userID'");
								}
							if($self_rules_radius){
									@mysql_query("update `users` set `$self_rules_radius`='$self_rules_radius' where `u_id`='$userID'");
								}
							if($radius_type){
									@mysql_query("update `users` set `$radius_type`='$radius_type' where `u_id`='$userID'");
								}
							if($url_redirect){
									@mysql_query("update `users` set `$url_redirect`='$url_redirect' where `u_id`='$userID'");
								}
							if($url_redirect_Interval){
									@mysql_query("update `users` set `$url_redirect_Interval`='$url_redirect_Interval' where `u_id`='$userID'");
								}
							if($session_time){
									@mysql_query("update `users` set `$session_time`='$session_time' where `u_id`='$userID'");
								}
							if($port_limit){
									@mysql_query("update `users` set `$port_limit`='$port_limit' where `u_id`='$userID'");
								}
							if($idle_timeout){
									@mysql_query("update `users` set `$idle_timeout`='$idle_timeout' where `u_id`='$userID'");
								}
								///// Calculate MB 
							if($quota_limit_upload){
									$quota_limit_upload=$quota_limit_upload*1024*1024;
									@mysql_query("update `users` set `$quota_limit_upload`='$quota_limit_upload' where `u_id`='$userID'");
								}
							if($quota_limit_download){
									$quota_limit_download=$quota_limit_download*1024*1024;
									@mysql_query("update `users` set `$quota_limit_download`='$quota_limit_download' where `u_id`='$userID'");
								}
							if($quota_limit_total){
									$quota_limit_total=$quota_limit_total*1024*1024;
									@mysql_query("update `users` set `$quota_limit_total`='$quota_limit_total' where `u_id`='$userID'");
								}
							if($speed_limit){
									@mysql_query("update `users` set `$speed_limit`='$speed_limit' where `u_id`='$userID'");
								}
							if($if_downgrade_speed){
									@mysql_query("update `users` set `$if_downgrade_speed`='$if_downgrade_speed' where `u_id`='$userID'");
								}
							if($end_speed){
									@mysql_query("update `users` set `$end_speed`='$end_speed' where `u_id`='$userID'");
								}
							
							$last_id=@mysql_insert_id();
							$status=1;if($login_lang=="ar"){$status_message=$updateSuccessfully_ar;}else{ $status_message=$updateSuccessfully_en;}
							$data_before = array("status" => "$status", "statusMessage" => "$status_message");
							echo $data = json_encode($data_before);
							
									
					}else{
						$status=0;if($login_lang=="ar"){$status_message=$deleteErrorID_ar;}else{ $status_message=$deleteErrorID_en;}
						$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);}
						
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