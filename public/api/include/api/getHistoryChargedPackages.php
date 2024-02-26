<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	
	require_once 'include/config.php';
			
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=getHistoryChargedCards";
				//$data='{"username":"ãÍãÏ","password":"ãÍãÏ","web":"http://m","lang":"ar"}';
				// send json
				$data_before = array("username" => "$login_user", "password" => "$login_password", "web" => "$login_web_site", "lang" => "$login_lang");                                                                    
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
    		$u_mobile=$row_get_user_data['u_mobile'];
			
			if( ($db_u_uname==$login_user) or ($u_mobile==$login_user) and $db_u_password==$login_password and $suspend!="suspend" and $u_canuse==1)
			{	
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////

				$search_in_history="select * from `history` where u_id='$db_id' and operation='charge' and details is null order by id DESC";
        		$r_search_in_history=@mysql_query($search_in_history,$conn_user);
        		if(@mysql_num_rows($r_search_in_history)>0){
        			$num_of_packages=@mysql_num_rows($r_search_in_history);
        			$counter=0;
        			
        			$json = array("status" => "1","statusMessage" => "success", "numberOfPackages" => "$num_of_packages");
                
        		while ($row_search_in_history = @mysql_fetch_array($r_search_in_history)) {
        			$counter++;
        		$charge_package_name=$row_search_in_history['charge_package_name'];
        		$charge_salary=$row_search_in_history['charge_salary'];
        		$charge_period=$row_search_in_history['charge_period'];
        		$charge_type=$row_search_in_history['charge_type'];
        		$add_date=$row_search_in_history['add_date'];
        		$a_uname=$row_search_in_history['a_uname'];
        		$add_time=$row_search_in_history['add_time'];
        		$a_uname=$row_search_in_history['a_uname'];
        		$add_date_and_time=$add_date." ".$add_time;
        		if($charge_type=="monthly2"){ if($login_lang=="ar"){$charge_type=$lang_c_17_ar;}else{ $charge_type=$lang_c_17_en;}}
        		if($charge_type=="monthly"){ if($login_lang=="ar"){$charge_type=$lang_c_18_ar;}else{ $charge_type=$lang_c_18_en;}}
        		if($charge_type=="period"){ if($login_lang=="ar"){$charge_type=$lang_c_19_ar;}else{ $charge_type=$lang_c_19_en;}}
        		if($charge_type=="sms"){ if($login_lang=="ar"){$charge_type=$lang_c_20_ar;}else{ $charge_type=$lang_c_20_en;}}
        		if($charge_type=="bandwidth_card"){ if($login_lang=="ar"){$charge_type=$lang_c_21_ar;}else{ $charge_type=$lang_c_21_en;}}
        		
        		if(!$a_uname){if($login_lang=="ar"){$chargedBy=$lang_c_22_ar;}else{$chargedBy=$lang_c_22_en;}}
        		else{$chargedBy=$a_uname;}
        		
        		  $row_bot_data['chargedBy']= $chargedBy;
				  $row_bot_data['ChargeDateTime']= $add_date_and_time;
				  $row_bot_data['PackageType']= $charge_type;
				  $row_bot_data['PackageName']= $charge_package_name;
				  $row_bot_data['PackageSalary']= $charge_salary;
				  $row_bot_data['period']= $charge_period;
				  
				  $json['chargedPackages'][]=$row_bot_data;
        		
        		}//while ($row_search_in_history = @mysql_fetch_array($r_search_in_history)) 
        		
                echo json_encode($json); 
        		}//if(@mysql_num_rows($r_search_in_history)>0)
				else{
					$status=0;if($login_lang=="ar"){$status_message=$getHistoryChargedPackagesNotFound_ar;}else{ $status_message=$getHistoryChargedPackagesNotFound_en;}
					$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
					echo $data = json_encode($data_before);
				}
				
				
				
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// Charge Card ////////////////////////////////////////////////////////////////////
				
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