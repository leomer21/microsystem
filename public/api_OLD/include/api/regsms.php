<?php 


	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	$login_regsms = $obj['regSms'];

	require_once 'include/config.php';
			
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=regSms";
				//$data='{"user":"test","password":"test","web":"http://www.microsystem-eg.com","lang":"ar"}';
				// send json
				$data_before = array("username" => "$login_user", "password" => "$login_password", "web" => "$login_web_site", "lang" => "$login_lang", "regSms" => "$login_regsms");                                                                    
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
		}else {$can_go="yes";}
		
		if($can_go=="yes"){
		$sys_dbhost = 'localhost';
		$conn_user = @mysql_connect($sys_dbhost, $sys_db_user, $sys_db_pass, true);
		@mysql_select_db($sys_db_name,$conn_user);
		@mysql_set_charset('utf8');
		
		///////////////////////////////////////////////////        
		//			 Connect to user database 
		///////////////////////////////////////////////////
		include_once 'include/sql/sql.php';
		if(@mysql_num_rows($r_get_user_data)>0)
		{
			$row_get_user_data=@mysql_fetch_array($r_get_user_data);
			$db_id=$row_get_user_data['u_id'];
			$db_u_canuse=$row_get_user_data['u_canuse'];
			$db_u_name=$row_get_user_data['u_name'];
			$db_u_uname=$row_get_user_data['u_uname'];
			$db_u_password=$row_get_user_data['u_password'];
			$db_creadit=$row_get_user_data['creadit'];
			$db_network_code=$row_get_user_data['network_code'];
			$db_hotspot_or_ppp=$row_get_user_data['hotspot_or_ppp'];
			
			if($db_u_canuse==$login_regsms)
			{
				$conn_user_4_mikrotik = @mysql_connect($sys_dbhost, $sys_db_user, $sys_db_pass, true);
				@mysql_select_db($sys_db_name,$conn_user_4_mikrotik);
				@mysql_query("SET NAMES cp1256",$conn_user_4_mikrotik);
				@mysql_query("set characer set cp1256",$conn_user_4_mikrotik);
				
				///////////////////////////////////////////////////        
				//			 Connect to user database 
				///////////////////////////////////////////////////
				$get_user_data_4_mikrotik="select u_name,u_uname,u_password from users where u_id='$db_id'";
				$r_get_user_data_4_mikrotik=@mysql_query($get_user_data_4_mikrotik,$conn_user_4_mikrotik)or die(mysql_error());
					$row_get_user_data_4_mikrotik=@mysql_fetch_array($r_get_user_data_4_mikrotik);
					$db_u_name_4_mikrotik=$row_get_user_data_4_mikrotik['u_name'];
					$db_u_uname_4_mikrotik=$row_get_user_data_4_mikrotik['u_uname'];
					$db_u_password_4_mikrotik=$row_get_user_data_4_mikrotik['u_password'];
					
				            			// Get Mikrotik Info ///////////////////////////////////////////////
        						$select_mikrotik_info="select * from network where code='$db_network_code'";
                				$result_select_mikrotik_info=@mysql_query($select_mikrotik_info,$conn_user);	
                				while($row_result_select_mikrotik_info=@mysql_fetch_array($result_select_mikrotik_info))
                    			{
                    			 $network_name=$row_result_select_mikrotik_info['name'];	
                                 $ip_lan=$row_result_select_mikrotik_info['lan_ip'];
                    			 $ip_wan=$row_result_select_mikrotik_info['wan_ip'];
                    			 $mikrotik_user=$row_result_select_mikrotik_info['mikrotik_user'];
                    			 $mikrotik_pass=$row_result_select_mikrotik_info['mikrotik_pass'];
                    			
                    			 // Get Mikrotik user control Mode
                    			 $wan_or_lan=$row_result_select_mikrotik_info['wan_or_lan'];
                    			 
                    			  // start new 13.2.2014
                    			 $stop_user_type=$row_result_select_mikrotik_info['stop_user_type'];
                    			 $stop_user_profile_id=$row_result_select_mikrotik_info['stop_user_profile_id'];
                    			 $stop_user_profile_code=$row_result_select_mikrotik_info['stop_user_profile_code'];
                    			 // End new 13.2.2014
                    			 // V8 15.11.2015
							     $connection_type=$row_result_select_mikrotik_info['connection_type'];
							    
                    			}
                    			if($wan_or_lan=="wan")
	                    		{$connection_ip=$ip_wan;}
								if($wan_or_lan=="lan")
	                    		{$connection_ip=$ip_lan;}
	                    		/////////////////////////////////////////////////////////////////////
	                    		
	                    		//get limit_uptime_for_user_when_register
	                $select_limit_uptime_for_user_when_register="select * from informations where type='limit_uptime_for_user_when_register'";
	                $result_select_limit_uptime_for_user_when_register=@mysql_query($select_limit_uptime_for_user_when_register);
	                $row_result_select_limit_uptime_for_user_when_register=@mysql_fetch_array($result_select_limit_uptime_for_user_when_register);
	                $limit_uptime_for_user_when_register=$row_result_select_limit_uptime_for_user_when_register['control'];  		
                	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
                    	if($connection_type!="radius")
                    	{
					
							require('include/mikrotik/routeros_api.class.php');
						    $API = new routeros_api();
							$API->debug = false;
							//$API->connect($ip_wan, $mikrotik_user, $mikrotik_pass);
							if($API->connect($connection_ip, $mikrotik_user, $mikrotik_pass))
							{
							// for Add New USer			
								if($db_hotspot_or_ppp=="hotspot")
                    			{
								// for Add New USer			
									$API->comm("/ip/hotspot/user/add", array(
						          "name"     => "$db_u_uname_4_mikrotik",
						          "password" => "$db_u_password_4_mikrotik",
								  "profile" => "MID",
								  "limit-uptime" => "$limit_uptime_for_user_when_register",	
						          "comment"  => "$db_u_name_4_mikrotik",));
                    			}//if($hotspot_or_ppp=="hotspot")
                    			
                    			if($db_hotspot_or_ppp=="ppp")
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
									$API->write('/ppp/secret/set', false);
									$API->write('=.id='.$db_u_uname_4_mikrotik, false);
									$API->write('=disabled=yes');
									$API->read();}
									}//if($hotspot_or_ppp=="ppp")
									
                    	// step 2
                		$ubdate_user_data="update users set u_canuse='1' where `u_uname`='$db_u_uname'";
                		$result_ubdate_user_data=@mysql_query($ubdate_user_data,$conn_user);
			                
						$status=1;if($login_lang=="ar"){$status_message=$registration_successfully_ar;}else{ $status_message=$registration_successfully_en;}
						$data_before = array("status" => "$status", "name" => "$db_u_name", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);
						
							}//if($API->connect($connection_ip, $mikrotik_user, $mikrotik_pass))
							else{
								$status=0;
								if($login_lang=="ar"){ $status_message=$try_again_later_ar;}
								else { $status_message=$try_again_later_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
								echo $data = json_encode($data_before);
		        			}
							
                    	}//if($connection_type!="radius")
                    	elseif ($connection_type=="radius")
                    	{
	                		$ubdate_user_data="update users set u_canuse='1' where `u_uname`='$db_u_uname'";
	                		$result_ubdate_user_data=@mysql_query($ubdate_user_data,$conn_user);
				                
							$status=1;if($login_lang=="ar"){$status_message=$registration_successfully_ar;}else{ $status_message=$registration_successfully_en;}
							$data_before = array("status" => "$status", "name" => "$db_u_name", "statusMessage" => "$status_message");                                                                    
							echo $data = json_encode($data_before);
							
                    	}//elseif ($connection_type=="radius")
                    	
                    	////////////////////////////////////////////////////////////////////////////////////////
			}else
			{
			$status=2;if($login_lang=="ar"){$status_message=$wrong_code_ar;}else{ $status_message=$wrong_code_en;}
			$data_before = array("status" => "$status", "name" => "$db_u_name", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);
			}	
			
		}//if(@mysql_num_rows($r_get_user_data)>0)
		else{
			$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_password_ar;}else{ $status_message=$error_in_username_or_password_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);}

		unset($can_go);
		}//if($can_go=="yes")
		     
	 
	
@mysql_close($conn_user);


?>