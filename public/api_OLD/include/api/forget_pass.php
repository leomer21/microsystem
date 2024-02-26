<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_mail = $obj['mail'];
	$login_lang = $obj['lang'];
	
	require_once 'include/config.php';
		
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=forgetPass";
				//$data='{"user":"test","password":"test","web":"http://www.microsystem-eg.com","lang":"ar"}';
				// send json
				$data_before = array("username" => "$login_user", "mail" => "$login_mail", "web" => "$login_web_site", "lang" => "$login_lang");                                                                    
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
		//@mysql_query("SET NAMES cp1256",$conn_user);
		//@mysql_query("set characer set cp1256",$conn_user);
		///////////////////////////////////////////////////        
		//			 Connect to user database 
		///////////////////////////////////////////////////

		include_once 'include/sql/sql.php';
		
		if(@mysql_num_rows($r_get_user_data)>0)
		{
			$row_get_user_data=@mysql_fetch_array($r_get_user_data);
			$db_id=$row_get_user_data['id'];
			$db_u_name=$row_get_user_data['u_name'];
			$db_u_uname=$row_get_user_data['u_uname'];
			$db_u_password=$row_get_user_data['u_password'];
			$db_u_mail=$row_get_user_data['u_mail'];
			
			
					$user_mail_for_send=$db_u_mail; 
					$user_uname_for_send=$db_u_uname;
				
					$message_subject="Password Recovery for : $db_u_uname";
                    $message_header="Dear : ".$db_u_name;
                    $message_body_title="Request Time : $today $today_time";
                    $message_body_body="<center>Your Password <br> $db_u_password </center>";
			include 'include/mail/contact.php';
		}//if(@mysql_num_rows($r_get_user_data)>0)
		else{
			$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_mail_ar;}else{ $status_message=$error_in_username_or_mail_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);}

		unset($can_go);
		}//if($can_go=="yes")
		     
	
@mysql_close($conn_user);
	
                    
?>