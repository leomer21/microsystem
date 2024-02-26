<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	$uID=$obj['userID'];
	
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

				if($uID)
				{
						$select_user="select `u_uname`,`u_id` from `users` where u_id='$uID'";
						$result_select_user=@mysql_query($select_user);
						$row_select_user=@mysql_fetch_array($result_select_user);
						$gettedUserID=$row_select_user['u_id'];
						$gettedUserName=$row_select_user['u_uname'];
						
						
			        	if($gettedUserID)
				        	{
				        	
							$delete_user_id_data="delete from `users` where u_id=$uID";
							@mysql_query($delete_user_id_data,$conn_user);
							
							$delete_from_order_change_limit="delete from `order_change_limit` where user_id='$uID'";
				        	@mysql_query($delete_from_order_change_limit,$conn_user);
				        	$delete_from_order_change_speed="delete from `order_change_speed` where user_id='$uID'";
				        	@mysql_query($delete_from_order_change_speed,$conn_user);
				        	$delete_from_mac="delete from `mac` where `u_id`='$gettedUserName'";
				        	@mysql_query($delete_from_mac,$conn_user);
				        	$delete_from_orders="delete from `orders` where `order_user_id`='$uID'";
				        	@mysql_query($delete_from_orders,$conn_user);
				        	// if radius
				        	$delete_from_radusergroup="delete from `radusergroup` where `u_uname`='$gettedUserName'";
				        	@mysql_query($delete_from_radusergroup,$conn_user);
				        	$delete_from_radreply="delete from `radreply` where `username`='$gettedUserName'";
				        	@mysql_query($delete_from_radreply,$conn_user);
				        	$delete_from_radgroupcheck="delete from `radgroupcheck` where `groupname`='$gettedUserName'";
				        	@mysql_query($delete_from_radgroupcheck,$conn_user);
				        	$delete_from_radcheck="delete from `radcheck` where `username`='$gettedUserName'";
				        	@mysql_query($delete_from_radcheck,$conn_user);
						
							$status=1;if($login_lang=="ar"){$status_message=$deleteUserSuccessfully_ar;}else{ $status_message=$deleteUserSuccessfully_en;}
							$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
							echo $data = json_encode($data_before);
				        }else{
			        		$status=0;if($login_lang=="ar"){$status_message=$deleteErrorID_ar;}else{ $status_message=$deleteErrorID_en;}
							$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
							echo $data = json_encode($data_before);
			        	}
				        	
				}//if($uID)
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