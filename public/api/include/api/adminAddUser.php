<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
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

				if($u_name and $u_uname and $u_password)
				{
						
						$checkIfUserExist="select `u_id` from `users` where `u_uname`='$u_uname'";
						$r_checkIfUserExist=@mysql_query($checkIfUserExist);
						if(@mysql_num_rows($r_checkIfUserExist)<=0)
							{
								///// Calculate MB 
								if($quota_limit_upload){$quota_limit_upload=$quota_limit_upload*1024*1024;}
								if($quota_limit_download){$quota_limit_download=$quota_limit_download*1024*1024;}
								if($quota_limit_total){$quota_limit_total=$quota_limit_total*1024*1024;}
								//////////////////	
							$insert_new_user_data="insert into `users` (network_name,network_code,hotspot_or_ppp,u_canuse,u_name,u_uname,u_password,u_phone,u_mobile,u_area,u_address,
							u_mail,u_gender,u_birth_date,u_start_date,u_state,u_macaddress,u_ip
							,self_rules_radius,radius_type,url_redirect,url_redirect_Interval,session_time,port_limit,idle_timeout,quota_limit_upload
							,quota_limit_download,quota_limit_total,speed_limit,renew,if_downgrade_speed,end_speed) values 
							('$network_name',
							'$network_code',
							'$hotspot_or_ppp',
							'1',
							'$u_name',
							'$u_uname',
							'$u_password',
							'$u_phone',
							'$u_mobile',
							'$u_area',
							'$u_address',
							'$u_mail',
							'$u_gender',
							'$u_birth_date',
							'$u_start_date',
							'$u_state',
							'$u_macaddress',
							'$u_ip'
							,'$self_rules_radius'
							,'$radius_type'
							,'$url_redirect'
							,'$url_redirect_Interval'
							,'$session_time'
							,'$port_limit'
							,'$idle_timeout'
							,'$quota_limit_upload'
							,'$quota_limit_download'
							,'$quota_limit_total'
							,'$speed_limit'
							,'1'
							,'$if_downgrade_speed'
							,'$end_speed'
							)";
							if(@mysql_query($insert_new_user_data,$conn_user))
							{
								$last_id=@mysql_insert_id();
								$status=1;if($login_lang=="ar"){$status_message=$userAddSccessfully_ar;}else{ $status_message=$userAddSccessfully_en;}
								$data_before = array("status" => "$status", "userID" => "$last_id", "statusMessage" => "$status_message");                                                                    
								echo $data = json_encode($data_before);
							}else{
								$status=0;if($login_lang=="ar"){$status_message=$errorInInsert_ar;}else{ $status_message=$errorInInsert_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
								echo $data = json_encode($data_before);}
								
					}else{
					$status=0;if($login_lang=="ar"){$status_message=$userExist_ar;}else{ $status_message=$userExist_en;}
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