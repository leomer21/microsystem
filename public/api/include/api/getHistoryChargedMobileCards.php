<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	
	require_once 'include/config.php';
	$conn_sys = @mysql_connect($dbhost, $dbuser, $dbpass);
	@mysql_select_db($dbname,$conn_sys);
	
	$conn_sys2 = @mysql_connect($dbhost, $dbuser, $dbpass, true);
	@mysql_select_db($dbnameMicrosystem,$conn_sys2);

	//@mysql_query("SET NAMES cp1256",$conn_sys);
	//@mysql_query("set characer set cp1256,$conn_sys");
	 ///////////////////////////////////////////////////       
	        // Connect to system database
	 ///////////////////////////////////////////////////     
	   
	$get_user_db_name_and_pass="select * from web where sys_web='$login_web_site' and state='running'";
	$result_get_user_db_name_and_pass=@mysql_query($get_user_db_name_and_pass,$conn_sys);
	if(@mysql_num_rows($result_get_user_db_name_and_pass)>0)
	 { 
		$row_get_user_db_name_and_pass=@mysql_fetch_array($result_get_user_db_name_and_pass);
		$sys_name=$row_get_user_db_name_and_pass['sys_name'];
		$sys_admin_name=$row_get_user_db_name_and_pass['sys_admin_name'];
		$sys_db_name=$row_get_user_db_name_and_pass['sys_db_name'];
		$sys_db_user=$row_get_user_db_name_and_pass['sys_db_user'];
		$sys_db_pass=$row_get_user_db_name_and_pass['sys_db_pass'];
		$sys_local_or_web=$row_get_user_db_name_and_pass['sys_local_or_web'];
		$me_local_or_web=$row_get_user_db_name_and_pass['me_local_or_web'];
		$local_url=$row_get_user_db_name_and_pass['local_url'];
		
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=chargedMobileCards";
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



$get_customer_data="select * from informations";
$r_get_customer_data=@mysql_query($get_customer_data,$conn_user);
while ($row_get_customer_data = @mysql_fetch_array($r_get_customer_data)) {
	$first_data=$row_get_customer_data['type'];
	if($first_data=="customer_id"){$current_get_customer_id=$row_get_customer_data['control'];}
	if($first_data=="c_row_id"){$c_row_id=$row_get_customer_data['control'];}
	if($first_data=="customer_user"){$customer_user=$row_get_customer_data['control'];}
	if($first_data=="customer_name"){$customer_name=$row_get_customer_data['control'];}
	if($first_data=="customer_url"){$customer_url=$row_get_customer_data['control'];}}
	

				
				$get_mobile_cards_charged="select * from mob_cards where network_admin_id='$current_get_customer_id' and user_charge_id='$db_id' and state='inactive' order by `id` desc";
				$r_get_mobile_cards_charged=@mysql_query($get_mobile_cards_charged,$conn_sys2);
				if(@mysql_num_rows($r_get_mobile_cards_charged)>0){
        			$num_of_mobile_charged_cards=@mysql_num_rows($r_get_mobile_cards_charged);
        			$counter=0;
        			
        			$json = array("status" => "1","statusMessage" => "success", "numberOfChargedMobileCards" => "$num_of_mobile_charged_cards");
                
        			while ($row_get_mobile_cards_charged =@mysql_fetch_array($r_get_mobile_cards_charged)) {
		
		$history___card_id=$row_get_mobile_cards_charged['id'];
		$history___card_no=$row_get_mobile_cards_charged['card_no'];
		$history___card_serial=$row_get_mobile_cards_charged['card_serial'];
		$history___card_type=$row_get_mobile_cards_charged['card_type'];
		$history___card_charge_salary=$row_get_mobile_cards_charged['charge_salary'];
		$history___card_charge_cost=$row_get_mobile_cards_charged['charge_cost'];
		$history___card_expire_date=$row_get_mobile_cards_charged['expire_date'];
		$history___card_charge_date=$row_get_mobile_cards_charged['charge_date'];
		$history___card_charge_time=$row_get_mobile_cards_charged['charge_time'];
		$history___card_user_ip=$row_get_mobile_cards_charged['user_ip'];
		
				
        		  $row_bot_data['CardNo']= $history___card_no;
				  $row_bot_data['CardSerial']= $history___card_serial;
				  $row_bot_data['CardType']= $history___card_type;
				  $row_bot_data['CardSalary']= $history___card_charge_salary;
				  $row_bot_data['CardCost']= $history___card_charge_cost;
				  $row_bot_data['CardChargeDate']= $history___card_charge_date." At ".$history___card_charge_time;
				  $row_bot_data['CardChargeIP']= $history___card_user_ip;
				  
				  
				  $json['chargedMobileCards'][]=$row_bot_data;
        		
        		}//while ($row_search_in_history = @mysql_fetch_array($r_search_in_history)) 
        		
                echo json_encode($json); 
        		}//if(@mysql_num_rows($r_search_in_history)>0)
				else{
					$status=0;if($login_lang=="ar"){$status_message=$getHistoryChargedMobileNotFound_ar;}else{ $status_message=$getHistoryChargedMobileNotFound_en;}
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
		     
	 }
	 else{
			$status=0;if($login_lang=="ar"){$status_message=$web_site_error_ar;}else{ $status_message=$web_site_error_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);}
	 
	
@mysql_close($conn_user);
@mysql_close($conn_sys);
	
                    
?>