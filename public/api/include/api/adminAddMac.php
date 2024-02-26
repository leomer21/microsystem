<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	$userID=$obj['userID'];
	$mac=$obj['mac'];
	$type=$obj['type'];
	
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

				if($userID and $mac and $type)
				{
						$checkIfUserExist="select `u_id`,`u_name`,`u_uname`,`network_code` from `users` where u_id='$userID'";
						$r_checkIfUserExist=@mysql_query($checkIfUserExist);
						if(@mysql_num_rows($r_checkIfUserExist)>0)
							{
								$row_select_user=@mysql_fetch_array($r_checkIfUserExist);
								$gettedName=$row_select_user['u_name'];
								$gettedUserName=$row_select_user['u_uname'];
								$gettednetworkCode=$row_select_user['network_code'];
								
								$checkIfMacExist="select * from `mac` where `mac`='$mac'";
								$r_checkIfMacExist=@mysql_query($checkIfMacExist);
								if(@mysql_num_rows($r_checkIfMacExist)==0){
								
									$insert_mac="insert into mac (u_id,u_name,u_uname,mac,type,add_date,network_code,state) values 
									('$userID','$gettedName','$gettedUserName','$mac','$type','$today','$gettednetworkCode','active')";
								
									if(@mysql_query($insert_mac,$conn_user))
									{
										$last_id=@mysql_insert_id();
										$status=1;if($login_lang=="ar"){$status_message=$macAddSucc_ar;}else{ $status_message=$macAddSucc_en;}
										$data_before = array("status" => "$status", "macID" => "$last_id", "statusMessage" => "$status_message");                                                                    
										echo $data = json_encode($data_before);
									}else{
										$status=0;if($login_lang=="ar"){$status_message=$errorInInsert_ar;}else{ $status_message=$errorInInsert_en;}
										$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
										echo $data = json_encode($data_before);}
										
								}//if(@mysql_num_rows($r_checkIfMacExist)>0)
								else{
								$status=0;if($login_lang=="ar"){$status_message=$macAlreadyExist_ar;}else{ $status_message=$macAlreadyExist_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
								echo $data = json_encode($data_before);}
								
					}else{
					$status=0;if($login_lang=="ar"){$status_message=$deleteErrorID_ar;}else{ $status_message=$deleteErrorID_en;}
					$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
					echo $data = json_encode($data_before);}
						
				}//if($userID and $mac and $type)
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