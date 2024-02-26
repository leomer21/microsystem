<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	$pin = $obj['pin'];
	
	require_once 'include/config.php';
	 ///////////////////////////////////////////////////       
	        // Connect to system database
	 ///////////////////////////////////////////////////     
	   
		
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=confirmPIN";
				//$data='{"username":"ãÍãÏ","password":"ãÍãÏ","web":"http://m","lang":"ar","name":"UpdatedName","updatePass":"Updatedpassword","landLine":"Updatedlandline","mobile":"Updatedmobilenumber","address":"Updatedaddress","mail":"Updatedmail","birthDate":"Updatedbirthdate","gender":"Updatedgender","personalCode":"Updatedpersonalcode"}';
				// send json
				$data_before = array("username" => "$login_user", "password" => "$login_password", "web" => "$login_web_site", "lang" => "$login_lang"
				, "pin" => "$pin");                                                                    
				$data = json_encode($data_before);    
				$options = array(
						'http' => array(
								'header'  => "Content-type: application/json; charset=utf-8\r\n",
								'method'  => 'POST',
								'content' => "$data",
						),
				);
				$context=@stream_context_create($options);
				echo $result=file_get_contents($url,false,$context);
								
			}
		}else {
			$can_go="yes";
		}
		
		if($can_go=="yes"){
		$sys_dbhost = 'localhost';
		$conn_user = @mysql_connect($sys_dbhost, $sys_db_user, $sys_db_pass, true);
		@mysql_select_db($sys_db_name,$conn_user);
		mysql_set_charset('utf8');  
		///////////////////////////////////////////////////        
		//			 Connect to user database 
		///////////////////////////////////////////////////

		include_once 'include/sql/sql.php';
		if(@mysql_num_rows($r_get_user_data)>0)
		{
			$row_get_user_data=@mysql_fetch_array($r_get_user_data);
			$db_id=$row_get_user_data['u_id'];
			$db_u_name=$row_get_user_data['u_name'];
			$db_u_uname=$row_get_user_data['u_uname'];
			$db_u_password=$row_get_user_data['u_password'];
			$db_u_mail=$row_get_user_data['u_mail'];
			$current_user_creadit=$row_get_user_data['creadit'];
			$suspend=$row_get_user_data['suspend'];
			$network_code=$row_get_user_data['network_code'];
    		$hotspot_or_ppp=$row_get_user_data['hotspot_or_ppp'];
    		$u_canuse=$row_get_user_data['u_canuse'];
    		$currentPIN=$row_get_user_data['personal_code'];
    		
			
			$u_mobile=$row_get_user_data['u_mobile'];
			
			if( ($db_u_uname==$login_user) or ($u_mobile==$login_user) and $db_u_password==$login_password and $suspend!="suspend" and $u_canuse==1)
			{	
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////

				//$pin=@mysql_real_escape_string($pin); // SQL enjection

					if($pin==$currentPIN)
					{
						$status=1;if($login_lang=="ar"){$status_message="";}else{ $status_message="";}
						$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);
					}else{
						$status=0;if($login_lang=="ar"){$status_message=$confirmPINerror_ar;}else{ $status_message=$confirmPINerror_en;}
						$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);
					}
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Confirm PIN ////////////////////////////////////////////////////////////////////
				
			}//if($db_u_uname==$login_user and $db_u_password==$login_password)
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