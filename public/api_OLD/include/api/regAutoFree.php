<?php
/**
 * Created by Pharaoh on 6/8/2016.
 */
 
	$body = @file_get_contents('php://input');
	
	//$body = '{"u_name": "Pharaoh","u_mobile":"01090477724"}';
	
	$obj = json_decode($body, true);
	
	$reg_name = $obj['u_name'];
	$reg_mobile = $obj['u_mobile'];
	$reg_email = $obj['u_mail'];
	$reg_mac = $obj['u_macaddress'];
	$reg_mac=strtoupper("$reg_mac");// convert small letter to Capital letter to solve matching error
	$reg_country = $obj['u_country'];
	$reg_phone_type = $obj['u_phonetype'];
	$reg_lang = $obj['user_language'];
	$reg_start_date = date("yyyy-MM-dd");
	$reg_ip = $obj['u_ip'];
	//$reg_sim = $obj['u_sim'];
	
	require_once 'include/config.php';
	require_once 'include/opendb.php';

		$get_all_network_active='select u_id from users where u_macaddres="$reg_mac"';
        $result_get_all_network_active=@mysql_query($get_all_network_active);
        if(@mysql_num_rows($result_get_all_network_active)>0) 
        	{
        		 $json = array("status" => "2","statusMessage" => "mac already registerd");
        	     echo json_encode($json);
        	} 
        	else { // add new user
			if($get_all_network_active == $reg_mac){
				$login ="";
			}
			if($reg_country == "مصر" || $reg_country == "eg"){
				$reg_country = "Egypt";
			}

			$checkIfUserExist="select `u_id` from `users` where `u_macaddress`='$reg_mac'";
			$r_checkIfUserExist=@mysql_query($checkIfUserExist);
			if(@mysql_num_rows($r_checkIfUserExist)>0)
			{
				$status=2;//user already registerd with the same mac
				if($login_lang=="ar"){ $status_message=$user_aleady_exist_ar;}
				else { $status_message=$user_aleady_exist_en;}
				$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
				echo $data = json_encode($data_before);
			}else{
				$insert_data_of_direct_user="insert into users (`network_code`,`u_canuse`,`u_state`,`u_name`,`u_mobile`,`u_mail`,`u_macaddress`,`u_country`,`u_phonetype`,`user_language`,`u_start_date`,`u_ip`) values 
				('network','1','active','$reg_name','$reg_mobile','$reg_email','$reg_mac','$reg_country','$reg_phone_type','$reg_lang','$reg_start_date','$reg_ip')";
				@mysql_query($insert_data_of_direct_user,$conn_user)or die(@mysql_error());
				
				$status=1;
				if($login_lang=="ar"){ $status_message=$registration_successfully_ar;}
				else { $status_message=$registration_successfully_en;}
				$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
				echo $data = json_encode($data_before);
				}
        		}    
		@mysql_close($conn_user);
?>