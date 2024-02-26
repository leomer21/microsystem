<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	$UpdatePass = $obj['updatePass'];
	
	$updateName = $obj['name'];
	$updateLandLine = $obj['landLine'];
	$updateMobile = $obj['mobile'];
	$updateAddress = $obj['address'];
	$updateMail = $obj['mail'];
	$updateBirthDate = $obj['birthDate'];
	$updateGender = $obj['gender'];
	$updatePersonalCode = $obj['personalCode'];
	
	require_once 'include/config.php';
		
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=updateUserProfile";
				//$data='{"username":"ãÍãÏ","password":"ãÍãÏ","web":"http://m","lang":"ar","name":"UpdatedName","updatePass":"Updatedpassword","landLine":"Updatedlandline","mobile":"Updatedmobilenumber","address":"Updatedaddress","mail":"Updatedmail","birthDate":"Updatedbirthdate","gender":"Updatedgender","personalCode":"Updatedpersonalcode"}';
				// send json
				$data_before = array("username" => "$login_user", "password" => "$login_password", "web" => "$login_web_site", "lang" => "$login_lang"
				, "name" => "$updateName", "updatePass" => "$UpdatePass", "landLine" => "$updateLandLine", "mobile" => "$updateMobile"
				, "address" => "$updateAddress", "mail" => "$updateMail", "birthDate" => "$updateBirthDate", "gender" => "$updateGender"
				, "personalCode" => "$updatePersonalCode");                                                                    
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

				$qumaCheckFirst=0;
				
			
				if($UpdatePass){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$UpdatePass_sql=$quma1."u_password='$UpdatePass'";
					$qumaCheckFirst++;
				}
				if($updateName){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$UpdateName_sql=$quma1."u_name='$updateName'";
					$qumaCheckFirst++;
				}
				if($updateLandLine){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$updateLandLine_sql=$quma1."u_phone='$updateLandLine'";
					$qumaCheckFirst++;
				}
				if($updateMobile){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$updateMobile_sql=$quma1."u_mobile='$updateMobile'";
					$qumaCheckFirst++;
				}
				if($updateAddress){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$updateAddress_sql=$quma1."u_address='$updateAddress'";
					$qumaCheckFirst++;
				}
			if($updateMail){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$updateMail_sql=$quma1."u_mail='$updateMail'";
					$qumaCheckFirst++;
				}
			if($updateBirthDate){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$updateBirthDate_sql=$quma1."u_birth_date='$updateBirthDate'";
					$qumaCheckFirst++;
				}
			if($updateGender){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$updateGender_sql=$quma1."u_gender='$updateGender'";
					$qumaCheckFirst++;
				}
			if($updatePersonalCode){
					if($qumaCheckFirst==0){$quma1="";}
					if($qumaCheckFirst>0){$quma1=",";}
					$updatePersonalCode_sql=$quma1."personal_code='$updatePersonalCode'";
					$qumaCheckFirst++;
				}
				
				$updateUserData="update users set $UpdatePass_sql $UpdateName_sql $updateLandLine_sql $updateMobile_sql 
				$updateAddress_sql $updateMail_sql $updateBirthDate_sql $updateGender_sql $updatePersonalCode_sql where u_id='$db_id'";
				if(@mysql_query($updateUserData))
				{
					$status=1;if($login_lang=="ar"){$status_message=$updateUserProfileSuccessfully_ar;}else{ $status_message=$updateUserProfileSuccessfully_en;}
					$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
					echo $data = json_encode($data_before);
					
				}else{
					$status=0;if($login_lang=="ar"){$status_message=$charge_card_try_again_later_ar;}else{ $status_message=$charge_card_try_again_later_en;}
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