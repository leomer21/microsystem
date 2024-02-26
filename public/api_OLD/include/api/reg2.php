<?php 


	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$reg_web_site = $obj['web'];
	$reg_name = $obj['name'];
	$reg_user = $obj['username'];
	$reg_password = $obj['password'];
	$reg_mobile = $obj['mobile'];
	$login_lang = $obj['lang'];
	$login_mail = $obj['mail'];
	$reg_network_code = $obj['network_code'];
	
	require_once 'include/config.php';
	$conn_sys = @mysql_connect($dbhost, $dbuser, $dbpass);
	@mysql_select_db($dbname,$conn_sys);
	///////////////////////////////////////////////////       
	        // Connect to system database
	///////////////////////////////////////////////////     

	 	if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=reg2";
				//$data='{"user":"test","password":"test","web":"http://www.microsystem-eg.com","lang":"ar"}';
				// send json
				$data_before = array("name" => "$reg_name", "username" => "$reg_user", "password" => "$reg_password", "web" => "$reg_web_site", "lang" => "$login_lang", "mobile" => "$reg_mobile","networ_code" => "$reg_network_code"  );                                                                    
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
		@mysql_set_charset('utf8');  
		///////////////////////////////////////////////////        
		//			 Connect to user database 
		///////////////////////////////////////////////////
				$get_max_users="select * from informations where type='max_users'";
    			$r_get_max_users=@mysql_query($get_max_users);
    			$row_get_max_users=@mysql_fetch_array($r_get_max_users);
    			$max_users=$row_get_max_users['control'];
    			
    			$get_users_numbers="select id from users";
    			$r_get_users_numbers=@mysql_query($get_users_numbers);
    			$num_off_all_users=@mysql_num_rows($r_get_users_numbers);
    			
    			if($max_users==0 or $num_off_all_users<=$max_users) {//
    	$reg_web_site = $obj['web'];
	
    	$network=$reg_network_code;
        $u_name=$reg_name;
        $u_uname=$reg_user;
        $u_password=$reg_password;
        $u_mobile=$reg_mobile;
        $user_language=$login_lang;
        $u_start_date=$today;
        $u_state="";
    
        $select_mikrotik_info="select * from network where code='$network'";
                				$result_select_mikrotik_info=@mysql_query($select_mikrotik_info,$conn_user);	
                				$row_result_select_mikrotik_info=@mysql_fetch_array($result_select_mikrotik_info);
                    			
                    			 $network_name=$row_result_select_mikrotik_info['name'];	
                                 $ip_lan=$row_result_select_mikrotik_info['lan_ip'];
                    			 $ip_wan=$row_result_select_mikrotik_info['wan_ip'];
                    			 $mikrotik_user=$row_result_select_mikrotik_info['mikrotik_user'];
                    			 $mikrotik_pass=$row_result_select_mikrotik_info['mikrotik_pass'];
                    			 // Prodband or hotspot
                    			 $hotspot_or_ppp=$row_result_select_mikrotik_info['hotspot_or_ppp'];
                    			 // Get Mikrotik user control Mode
                    			 $wan_or_lan=$row_result_select_mikrotik_info['wan_or_lan'];
                    			 
                    			 $register_type=$row_result_select_mikrotik_info['register_type'];
                    			 $trial_period=$row_result_select_mikrotik_info['trial_period'];
                    			 $open_system=$row_result_select_mikrotik_info['open_system'];
                    			 $system_control_status=$row_result_select_mikrotik_info['system_control_status'];
                    			 $register_state=$row_result_select_mikrotik_info['register_state'];
                    			 
                    			 // start new 13.2.2014
                    			 $stop_user_type=$row_result_select_mikrotik_info['stop_user_type'];
                    			 $stop_user_profile_id=$row_result_select_mikrotik_info['stop_user_profile_id'];
                    			 $stop_user_profile_code=$row_result_select_mikrotik_info['stop_user_profile_code'];
                    			 // End new 13.2.2014
                    			 $wan_or_lan=$row_result_select_mikrotik_info['wan_or_lan'];
	                			
                    			  // V8 15.11.2015
							     $connection_type=$row_result_select_mikrotik_info['connection_type'];
							    
                    			if($wan_or_lan=="wan")
	                    		{$connection_type=$ip_wan;}
								if($wan_or_lan=="lan")
	                    		{$connection_type=$ip_lan;}
	                    		/////////////////////////////////////////////////////////////////////

                 	if($open_system=="yes"){$mode="open"; $limit_uptime_for_user_when_register="00:00:00";}
	                else{$mode="";$limit_uptime_for_user_when_register=$trial_period;}  		
                	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	                		
	                $select="select * from users where u_uname='$u_uname' or u_mobile='$u_mobile'";
	                $result = @mysql_query($select,$conn_user)or die(@mysql_error());
	                
	                if(@mysql_num_rows($result)>0)                             // Check on user Name or Bassword
	                {
	                $status=0;
					if($login_lang=="ar"){ $status_message=$user_aleady_exist_ar;}
					else { $status_message=$user_aleady_exist_en;}
					
					$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
					echo $data = json_encode($data_before);
					}
	                
                    else{           // if all correct

                            //////////////////////////////////////////////////////////////////////////////////////////////////////////////                    	
							if($register_type=="admin")
							{
		                        $query="insert into users (`u_mail`,`mode`,`free_user`,`network_name`,`network_code`,`hotspot_or_ppp`,`u_canuse`,`u_name`,`u_uname`,`u_password`,`u_mobile`,`u_start_date`,`u_state`) values
		                        ('$login_mail','$mode','$mode','$network_name','$network','$hotspot_or_ppp','0','$u_name','$u_uname','$u_password','$u_mobile','$u_start_date','$u_state')";
		                        @mysql_query($query,$conn_user);
		                        
		                        $status=1;
								if($login_lang=="ar"){ $status_message=$register_success_wating_admin_confirm_ar;}
								else { $status_message=$register_success_wating_admin_confirm_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");
								echo $data = json_encode($data_before);                                                                    
							}
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////
							
							if($register_type=="sms")
							{
								$register_number=rand(00000, 99999);
							$query_sms="insert into users (`u_mail`,`mode`,`free_user`,`network_name`,`network_code`,`hotspot_or_ppp`,`u_canuse`,`u_name`,`u_uname`,`u_password`,`u_mobile`,`u_start_date`,`u_state`) values
								('$login_mail','$mode','$mode','$network_name','$network','$hotspot_or_ppp','$register_number','$u_name','$u_uname','$u_password','$u_mobile','$u_start_date','$u_state')";
								$last_insert_id=@mysql_insert_id();
		                        @mysql_query($query_sms,$conn_user)or die(@mysql_error());
		                        
		                        $status=3;
								if($login_lang=="ar"){ $status_message=$register_success_wating_sms_confirm_ar;}
								else { $status_message=$register_success_wating_sms_confirm_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");
		                       
				// get sms data
                $select_admin_data_for_sms="select * from informations where type='sms'";
                $query_select_admin_data_for_sms=@mysql_query($select_admin_data_for_sms,$conn_user);
                while($row_admin_data_for_sms= @mysql_fetch_array($query_select_admin_data_for_sms))
                    {
                    $admin_uname_sms=$row_admin_data_for_sms['admin_uname_sms'];
                    $admin_pass_sms=$row_admin_data_for_sms['admin_pass_sms'];
                    $company_name_sms=$row_admin_data_for_sms['company_name_sms'];
                    $sms_state_user_lang=$row_admin_data_for_sms['sms_state_user_lang'];
                    $sms_state_user_special=$row_admin_data_for_sms['sms_state_user_special'];
                    }
                
                      
                    $get_customer_data="select * from informations";
					$r_get_customer_data=@mysql_query($get_customer_data,$conn_user);
					while ($row_get_customer_data = @mysql_fetch_array($r_get_customer_data)) {
						$first_data=$row_get_customer_data['type'];
						if($first_data=="customer_id"){$current_get_customer_id=$row_get_customer_data['control'];}
						if($first_data=="sms_provider"){$sms_provider=$row_get_customer_data['control'];}
						if($first_data=="customer_url"){$customer_url=$row_get_customer_data['control'];}}
					
    	            ////////////////////////////////////////////////////////////
						if($admin_uname_sms and $admin_pass_sms and $company_name_sms){
							
						if($wan_or_lan=="wan"){
	    					$get_currend_sms_creadit="select * from sms where u_id='$current_get_customer_id' and user='$admin_uname_sms' and password='$admin_pass_sms'";
	                    	$r_get_currend_sms_creadit=@mysql_query($get_currend_sms_creadit,$conn_sys);
	                    	$row_get_currend_sms_creadit=@mysql_fetch_array($r_get_currend_sms_creadit);
	                    	$current_sms_creadit=$row_get_currend_sms_creadit['palance'];
	                    	$current_sms_provider_user=$row_get_currend_sms_creadit['provider_user'];
	                    	$current_sms_provider_password=$row_get_currend_sms_creadit['provider_password'];
	                    	$current_sms_provider_name=$row_get_currend_sms_creadit['provider_name'];
	                    	$current_sms_sender_name=$row_get_currend_sms_creadit['sender_name'];
	                    	
	                    	if(!$current_sms_creadit){
	                    			$status=0;
									if($login_lang=="ar"){ $status_message=$contact_admin_to_confirm_yor_account_ar;}
									else { $status_message=$contact_admin_to_confirm_yor_account_en;}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message");  
									}
						}//if($wan_or_lan=="wan")
						
								if($current_sms_creadit>=1 or $wan_or_lan=="lan"){
                    		
								if($wan_or_lan=="lan"){
                    			$current_sms_provider_user=$admin_uname_sms;
                    			$current_sms_provider_password=$admin_pass_sms;
                    			$current_sms_sender_name=$company_name_sms;
                    			$current_sms_provider_name=$sms_provider;
                    			}
                    			//echo "<meta HTTP-EQUIV='REFRESH' content='0; url=http://api.valuedsms.com/?command=send_sms&sms_type=0&username=$admin_uname_sms&password=$admin_pass_sms&destination=$u_mobile&message=Dear:$user_uname your Activation Code : $register_number &sender=$company_name_sms'>";
                    			$sms_r_mobile_number=$u_mobile;
								$sms_r_content_message="Dear : $u_name Welcome to $current_sms_sender_name Network, your Activation Code is: $register_number ";
								
                    			if($current_sms_provider_name=="resalty")
                    			{
                    				$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$current_sms_provider_user&password=$current_sms_provider_password&to=$sms_r_mobile_number&sender=$current_sms_sender_name&msg=$sms_r_content_message";
					                $result1=@file($url1);
	                    			$find="MessageID";
									$string=$result1[0];
									if(strpos($string, $find) ===false){ // not Sent
									}else{// Send Successfully
										$message_sent_successfully="yes";}
	                    		}//if($currend_sms_provider_name=="resalty")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			if($current_sms_provider_name=="masrawy")
                    			{
                    			$url1="http://sms.masrawy.com/send.aspx?userName=$current_sms_provider_user&password=$current_sms_provider_password&mobile=$sms_r_mobile_number&message=$sms_r_content_message&Sender=$current_sms_sender_name";	
                    			$result1=@file($url1);
                    			$find="SentSuccessfully";
								$string=$result1[0];
								if(strpos($string, $find) ===false){ // not Sent
								}else{// Send Successfully
									$message_sent_successfully="yes";}
								}//if($currend_sms_provider_name=="masrawy")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			if($current_sms_provider_name=="valuedsms")
                    			{
                    				$sms_after_encoding=urlencode($sms_r_content_message);
                    				$url1="http://api.valuedsms.com/?command=send_sms&sms_type=0&username=$admin_uname_sms&password=$admin_pass_sms&destination=$sms_r_mobile_number&message=$sms_after_encoding&sender=$current_sms_sender_name";
                    				$result1=@file($url1);
                    				$find="true";
									$string=$result1[0];
									if(strpos($string, $find) ===false){ // not Sent
									}else{// Send Successfully
										$message_sent_successfully="yes";}
                    			}//if($currend_sms_provider_name=="valuedsms")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			
                    			if($message_sent_successfully=="yes"){
                    				if($wan_or_lan=="wan"){
                    				// step 1 
                    				$current_sms_creadit_after_discount=$current_sms_creadit-1;
                    				$discount_creadit_from_user="update sms set palance='$current_sms_creadit_after_discount' where u_id='$current_get_customer_id'";
                    				@mysql_query($discount_creadit_from_user,$conn_sys);
                    				}
                    				unset($message_sent_successfully);
                    				// step 2 insert history
                    				$insert_into_history2="insert into history (add_date,add_month,add_time,type1,type2,operation,details,u_id,u_name,u_uname) values 
									('$today','$month_table_name','$today_time','microcharge','auto','sms_register_confirm','$register_number','$last_insert_id','$u_name','$u_uname')";
									@mysql_query($insert_into_history2,$conn_user);
                    			}

                    	        }// End else if($current_sms_creadit>=1)
								else{// 
								$status=0;
								if($login_lang=="ar"){ $status_message=$contact_admin_to_confirm_yor_account_ar;}
								else { $status_message=$contact_admin_to_confirm_yor_account_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");  }
                    }//if($admin_uname_sms and $admin_pass_sms and $company_name_sms){
                    else{
                    			$status=0;
								if($login_lang=="ar"){ $status_message=$contact_admin_to_confirm_yor_account_ar;}
								else { $status_message=$contact_admin_to_confirm_yor_account_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");}

								echo $data = json_encode($data_before);
								
							}//if($register_type=="sms")
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////
							if($register_type=="direct")
							{
							   if($connection_type!="radius")
								{
							    require('include/mikrotik/routeros_api.class.php');
							    $API = new routeros_api();
								$API->debug = false;
								if($API->connect($connection_type, $mikrotik_user, $mikrotik_pass)) 
								{
									$insert_data_of_direct_user="insert into users (`u_mail`,`mode`,`free_user`,`u_canuse`,`u_name`,`u_uname`,`u_password`,`u_mobile`,`u_start_date`,`u_state`,`network_name`,`network_code`,`hotspot_or_ppp`) 
	    							values('$login_mail','$mode','$mode','1','$u_name','$u_uname','$u_password','$u_mobile','$u_start_date','active','$network_name','$network','$hotspot_or_ppp')";
			                        @mysql_query($insert_data_of_direct_user,$conn_user)or die(@mysql_error());
			                        $last_insert_id=@mysql_insert_id();
			                        
								    $status=1;
									if($login_lang=="ar"){ $status_message=$registration_successfully_ar;}
									else { $status_message=$registration_successfully_en;}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
									echo $data = json_encode($data_before);
									
									$conn_user_4_mikrotik = @mysql_connect($sys_dbhost, $sys_db_user, $sys_db_pass, true);
									@mysql_select_db($sys_db_name,$conn_user_4_mikrotik);
									@mysql_query("SET NAMES cp1256",$conn_user_4_mikrotik);
									@mysql_query("set characer set cp1256",$conn_user_4_mikrotik);
									
									///////////////////////////////////////////////////        
									//			 Connect to user database 
									///////////////////////////////////////////////////
									$get_user_data_4_mikrotik="select u_name,u_uname,u_password from users where u_id='$last_insert_id'";
									$r_get_user_data_4_mikrotik=@mysql_query($get_user_data_4_mikrotik,$conn_user_4_mikrotik)or die(@mysql_error());
										$row_get_user_data_4_mikrotik=@mysql_fetch_array($r_get_user_data_4_mikrotik);
										$db_u_name_4_mikrotik=$row_get_user_data_4_mikrotik['u_name'];
										$db_u_uname_4_mikrotik=$row_get_user_data_4_mikrotik['u_uname'];
										$db_u_password_4_mikrotik=$row_get_user_data_4_mikrotik['u_password'];
									
									if($hotspot_or_ppp=="hotspot")
	                    			{
									// for Add New USer
									  $API->comm("/ip/hotspot/user/add", array(
							          "name"     => "$db_u_uname_4_mikrotik",
							          "password" => "$db_u_password_4_mikrotik",
									  "profile" => "MID",
									  "limit-uptime" => "$limit_uptime_for_user_when_register",	
							          "comment"  => "$db_u_name_4_mikrotik",));
	                    			}//if($hotspot_or_ppp=="hotspot")
	                    			
	                    			if($hotspot_or_ppp=="ppp")
	                    			{
									
									// for Add New USer			
										$API->comm("/ppp/secret/add", array(
								          "name"     => "$db_u_uname_4_mikrotik",
								          "password" => "$db_u_password_4_mikrotik",
								          "profile" => "MID",
								          "comment"  => "$db_u_name_4_mikrotik",
								          "service"  => "pppoe",
										));
	                    				if($stop_user_type=="end_profile")
										{$API->write('/ppp/secret/set', false);
										$API->write('=.id='.$db_u_uname_4_mikrotik, false);
										$API->write('=profile='.$stop_user_profile_code);
										$API->read();}
										else{
										// 
										$API->write('/ppp/secret/set', false);
										$API->write('=.id='.$db_u_uname_4_mikrotik, false);
										$API->write('=disabled=yes');
										$API->read();}
										
										}//if($hotspot_or_ppp=="ppp")

                    					// step 1
	
    							
		        					
								}//if($API->connect($connection_type, $mikrotik_user, $mikrotik_pass)) 
								else{	
								$status=0;
								if($login_lang=="ar"){ $status_message=$try_again_later_ar;}
								else { $status_message=$try_again_later_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
								echo $data = json_encode($data_before);
		        				
								}
								
							  }//if($connection_type!="radius")
							  elseif ($connection_type=="radius"){
							  	
							  		$insert_data_of_direct_user="insert into users (`u_mail`,`mode`,`free_user`,`u_canuse`,`u_name`,`u_uname`,`u_password`,`u_mobile`,`u_start_date`,`u_state`,`network_name`,`network_code`,`hotspot_or_ppp`) 
	    							values('$login_mail','$mode','$mode','1','$u_name','$u_uname','$u_password','$u_mobile','$u_start_date','active','$network_name','$network','$hotspot_or_ppp')";
			                        @mysql_query($insert_data_of_direct_user,$conn_user)or die(@mysql_error());
			                        $last_insert_id=@mysql_insert_id();
			                        
								    $status=1;
									if($login_lang=="ar"){ $status_message=$registration_successfully_ar;}
									else { $status_message=$registration_successfully_en;}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
									echo $data = json_encode($data_before);
									
							  }//elseif ($connection_type=="radius")
							  
							}//if($register_type=="direct")
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////
                         }         // if all correct
       
            
}//if($max_users<=$num_off_all_users){ //
else{
								$status=0;
								if($login_lang=="ar"){ $status_message=$try_again_later_ar;}
								else { $status_message=$try_again_later_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
								echo $data = json_encode($data_before);}


	unset($can_go);	     
	 }
	 else{
			$status=0;if($login_lang=="ar"){$status_message=$web_site_error_ar;}else{ $status_message=$web_site_error_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);
	 }
	 		
		@mysql_close($conn_user);
		@mysql_close($conn_sys);

	 
?>