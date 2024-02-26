<?php 

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	require_once 'include/config.php';

	 ///////////////////////////////////////////////////       
	        // Connect to system database
	 ///////////////////////////////////////////////////     
		
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=login";
				//$data='{"username":"test","password":"test","web":"http://www.microsystem-eg.com","lang":"ar"}';
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
		//@mysql_query("SET NAMES cp1256",$conn_user);
		//@mysql_query("set characer set cp1256",$conn_user);
		///////////////////////////////////////////////////        
		//			 Connect to user database 
		///////////////////////////////////////////////////
//		if (is_numeric($login_user)){
//			$strlenth=strlen($login_user);
//			if($strlenth=="11"){$login_user="2".$login_user;}
//		}
		// users
//		$get_user_data="select * from users where u_uname='$login_user' and u_password='$login_password'";
//		$r_get_user_data=@mysql_query($get_user_data,$conn_user);
		include_once 'include/sql/sql.php';
		// admins
		$get_admin_data="select * from admins where a_uname='$login_user' and a_password='$login_password'";
		$r_get_admin_data=@mysql_query($get_admin_data,$conn_user);
		// Resellers
		$select_dis="select * from distributors where uname='$login_user' and password='$login_password'";
        $result_select_dis = @mysql_query($select_dis,$conn_user);
		
		if(@mysql_num_rows($r_get_user_data)>0) // user
		{
			$row_get_user_data=@mysql_fetch_array($r_get_user_data);
			$db_id=$row_get_user_data['u_id'];
			$db_u_canuse=$row_get_user_data['u_canuse'];
			$db_u_name=$row_get_user_data['u_name'];
			$db_u_uname=$row_get_user_data['u_uname'];
			$db_u_password=$row_get_user_data['u_password'];
			$db_network_code=$row_get_user_data['network_code'];
			$db_hotspot_or_ppp=$row_get_user_data['hotspot_or_ppp'];
			// update 18.12.2014
			$db_personal_code=$row_get_user_data['personal_code'];
			$db_u_mobile=$row_get_user_data['u_mobile'];
			$db_u_mail=$row_get_user_data['u_mail'];
			
			// User Data for "MAIN PAGE"
			$db_total_points=$row_get_user_data['u_points']; if(!$db_total_points){$db_total_points=0;}
			$db_user_mode=$row_get_user_data['mode'];
			$user_day_limit=$row_get_user_data['user_day_limit'];
			$u_card_validate_date_user=$row_get_user_data['u_card_validate_date'];
            $u_card_date_of_charging_user=$row_get_user_data['u_card_date_of_charging'];
            $db_creadit=$row_get_user_data['creadit']; if(!$db_creadit){$db_creadit=0;}
            $monthly_speed_name=$row_get_user_data['monthly_speed_name'];
            $monthly2_speed_name=$row_get_user_data['monthly2_speed_name'];
            $period_speed_name=$row_get_user_data['period_speed_name'];
                
            
            // Network Data for "MAIN PAGE"
			$select_mikrotik_info="select `open_system_name`,`name`,`open_system` from network where code='$db_network_code'";
            $result_select_mikrotik_info=@mysql_query($select_mikrotik_info,$conn_user);	
            $row_result_select_mikrotik_info=@mysql_fetch_array($result_select_mikrotik_info);
            $open_system_name=$row_result_select_mikrotik_info['open_system_name'];
            $db_network_name=$row_result_select_mikrotik_info['name'];
            $db_open_system=$row_result_select_mikrotik_info['open_system'];
            
            // Messages Data for "MAIN PAGE"
            $search_answer="select * from orders where order_user_id=$db_id and order_type='user' and order_answer!='null' and `read`!='yes'";
            $result_search_answer=@mysql_query($search_answer,$conn_user);
            $number_of_messages=@mysql_num_rows($result_search_answer);
			
            // check found Packages Monthly
            $checkFoundPackagesMonthly="select `id` from `packages` where state='active' and type='monthly'";
            $r_checkFoundPackagesMonthly=@mysql_query($checkFoundPackagesMonthly,$conn_user);
            if(@mysql_num_rows($r_checkFoundPackagesMonthly)>0){$monthlyPackageState=1;}else{$monthlyPackageState=0;}
            
			// check found Packages Validity
            $checkFoundPackagesValidity="select `id` from `packages` where state='active' and type='monthly2'";
            $r_checkFoundPackagesValidity=@mysql_query($checkFoundPackagesValidity,$conn_user);
            if(@mysql_num_rows($r_checkFoundPackagesValidity)>0){$ValidityPackageState=1;}else{$ValidityPackageState=0;}
            
			// check found Packages Time
            $checkFoundPackagesTime="select `id` from `packages` where state='active' and type='period'";
            $r_checkFoundPackagesTime=@mysql_query($checkFoundPackagesTime,$conn_user);
            if(@mysql_num_rows($r_checkFoundPackagesTime)>0){$TimePackageState=1;}else{$TimePackageState=0;}
            
			// check found Packages Time
            $checkFoundPackagesSMS="select `id` from `packages` where state='active' and type='sms'";
            $r_checkFoundPackagesSMS=@mysql_query($checkFoundPackagesSMS,$conn_user);
            if(@mysql_num_rows($r_checkFoundPackagesSMS)>0){$SMSPackageState=1;}else{$SMSPackageState=0;}
            
			// check found Packages ExtraBandwidth
            $checkFoundPackagesExtraBandwidth="select `id` from `packages` where state='active' and type='bandwidth_card'";
            $r_checkFoundPackagesExtraBandwidth=@mysql_query($checkFoundPackagesExtraBandwidth,$conn_user);
            if(@mysql_num_rows($r_checkFoundPackagesExtraBandwidth)>0){$ExtraBandwidthPackageState=1;}else{$ExtraBandwidthPackageState=0;}
            
            // Data for "MAIN PAGE"
			if($db_user_mode=="monthly"){
				if($login_lang=="ar"){$current_mode=$monthly_mode_ar;}
				if($login_lang=="en"){$current_mode=$monthly_mode_en;}
				$expire_date=$user_day_limit;
				$speed_name=$monthly_speed_name;}
			
			if($db_user_mode=="monthly2"){
				if($login_lang=="ar"){$current_mode=$monthly2_mode_ar;}
				if($login_lang=="en"){$current_mode=$monthly2_mode_en;}
				$expire_date=$u_card_validate_date_user;
				$speed_name=$monthly2_speed_name;}
			
			if($db_user_mode=="period"){
				if($login_lang=="ar"){$current_mode=$time_mode_ar;}
				if($login_lang=="en"){$current_mode=$time_mode_en;}
				$expire_date="";
				$speed_name=$period_speed_name;}
			
			if($db_user_mode=="free"){
				if($login_lang=="ar"){$current_mode=$free_mode_ar;}
				if($login_lang=="en"){$current_mode=$free_mode_en;}
				$expire_date="";
				$speed_name="";}
				
			 // Avilable Days for "MAIN PAGE"
			 	//////////////////////////////////////////////////////////////////
				//////////////////////////   SubDate   ///////////////////////////
				//////////////////////////////////////////////////////////////////
				function daysDifference($endDate, $beginDate)
				{
				  //explode the date by "-" and storing to array
				   $date_parts1=explode("-", $beginDate);
				   $date_parts2=explode("-", $endDate);
				   //gregoriantojd() Converts a Gregorian date to Julian Day Count
				   $start_date=@gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
				   $end_date=@gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
				   return $end_date - $start_date;
				}
				//////////////////////////////////////////////////////////////////
				//////////////////////////   SubDate   ///////////////////////////
				//////////////////////////////////////////////////////////////////
                $nowdate_user = strtotime("$expire_date");
                $thendate_user = strtotime("$today");
                $datediff_user = ($nowdate_user - $thendate_user);                 // בØÑÍ ÊÇÑםÎ ÞÏםד דה ÊÇÑםÎ ÌÏםÏ
                $final_date_user=round($datediff_user/86400);
                if($final_date_user>0)
                {$finalDaysDifference=@daysDifference($expire_date,$today);}	
				else{
						if($expire_date!="0000-00-00"){
							if($login_lang=="ar"){ if($db_open_system=="yes"){$finalDaysDifference=$free_mode2_ar;}else{$finalDaysDifference=$finalDaysDifference_ar;}}
							if($login_lang=="en"){ if($db_open_system=="yes"){$finalDaysDifference=$free_mode2_en;}else{$finalDaysDifference=$finalDaysDifference_en;}}
						}else{
							$finalDaysDifference=0;
						}
					}
				/////////////////////////////////////////////////////////////////
				if($expire_date=="0000-00-00"){
						if($login_lang=="ar"){$expire_date=$first_login_ar;}
						if($login_lang=="en"){$expire_date=$first_login_en;}
					}
				
				
				
            		
				
			// for Login	
			if( ($db_u_uname==$login_user and $db_u_password==$login_password ) or ($db_u_mobile==$login_user and $db_u_password==$login_password) )
			{
			
				if($db_u_canuse=="1")
				{
				$status=1;if($login_lang=="ar"){$status_message=$login_successfully_ar;}else{ $status_message=$login_successfully_en;}
				$data_before = array("status" => "$status", "name" => "$db_u_name", "statusMessage" => "$status_message", "type" => "user"
				, "avilableDays" => "$finalDaysDifference", "mode" => "$current_mode", "expireDate" => "$expire_date"
				, "networkName" => "$db_network_name", "networkUrl" => "$login_web_site", "newMessages" => "$number_of_messages"
				, "creadit" => "$db_creadit", "smsCreadit" => "$db_total_points", "lastCharge" => "$u_card_date_of_charging_user"
				, "speed" => "$speed_name", "mobile" => "$db_u_mobile", "mail" => "$db_u_mail", "personalCode" => "$db_personal_code"
				, "mikrotikIP" => "$mikrotikIP", "mobileChargeCardState" => "$mobileChargeCard", "mobileChargeTransferState" => "$mobileChargeTransfer"
				, "monthlyPackageState" => "$monthlyPackageState", "ValidityPackageState" => "$ValidityPackageState"
				, "timePackageState" => "$TimePackageState", "SMSPackageState" => "$SMSPackageState", "extraBandwidthPackageState" => "$ExtraBandwidthPackageState"
				);                                                                    
				echo $data = json_encode($data_before);
				}else if($db_u_canuse=="0")
				{
				$status=2;if($login_lang=="ar"){$status_message=$wating_admin_confirm_ar;}else{ $status_message=$wating_admin_confirm_en;}
				$data_before = array("status" => "$status", "name" => "$db_u_name", "statusMessage" => "$status_message", "type" => "user");                                                                    
				echo $data = json_encode($data_before);
				}	
				else{
				$status=3;if($login_lang=="ar"){$status_message=$wating_sms_confirm_ar;}else{ $status_message=$wating_sms_confirm_en;}
				$data_before = array("status" => "$status", "name" => "$db_u_name", "statusMessage" => "$status_message", "type" => "user");                                                                    
				echo $data = json_encode($data_before);
				}
				
			}//if($db_u_uname==$login_user and $db_u_password==$login_password)
			else{// error hacker
				$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_password_ar;}else{ $status_message=$error_in_username_or_password_en;}
				$data_before = array("status" => "$status", "statusMessage" => "$status_message", "type" => "user");                                                                    
				echo $data = json_encode($data_before);}
				
		}//if(@mysql_num_rows($r_get_user_data)>0)
		
		/////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////
		
		 elseif (@mysql_num_rows($r_get_admin_data)>0)// admin
	 {
	 		$row_get_admin_data=@mysql_fetch_array($r_get_admin_data);
			$db_id=$row_get_admin_data['a_id'];
			$db_a_name=$row_get_admin_data['a_name'];
			$db_a_uname=$row_get_admin_data['a_uname'];
			$db_a_password=$row_get_admin_data['a_password'];
			
			if($db_a_uname==$login_user and $db_a_password==$login_password)
			{
				$status=1;if($login_lang=="ar"){$status_message=$login_successfully_ar;}else{ $status_message=$login_successfully_en;}
				$data_before = array("status" => "$status", "name" => "$db_a_name", "statusMessage" => "$status_message", "type" => "admin");
				
					$get_all_permission_pages="select * from permissions where a_uname='$db_a_uname'";
			        $r_get_all_permission_pages=@mysql_query($get_all_permission_pages);
			        while ($row_get_all_permission_pages = @mysql_fetch_array($r_get_all_permission_pages)) {
			        	
			        	$permiss__page=$row_get_all_permission_pages['pages'];
			        	if($permiss__page){$data_before['pages'][].=$permiss__page;}
			        	
				        $get_per___network=$row_get_all_permission_pages['networks'];
		        		if($get_per___network){$data_before['networks'][].=$get_per___network; }
			        	
			        }//while ($row_get_all_permission_pages = @mysql_fetch_array($r_get_all_permission_pages))
		                                                                            
				echo $data = json_encode($data_before);
				
			}//if($db_u_uname==$login_user and $db_u_password==$login_password)
			else{
				$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_password_ar;}else{ $status_message=$error_in_username_or_password_en;}
				$data_before = array("status" => "$status", "statusMessage" => "$status_message", "type" => "admin");                                                                    
				echo $data = json_encode($data_before);}
	 }
	 
	 	/////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////
	
	 elseif (@mysql_num_rows($result_select_dis)>0)// Res
	 {
	 			$raw_select_dist=@mysql_fetch_array($result_select_dis);
	        	$dis_name=$raw_select_dist['name'];
	        	$dis_uname=$raw_select_dist['uname'];
		        $dis_password=$raw_select_dist['password'];
		        $dis_id=$raw_select_dist['id'];
	        	$creadit_state=$raw_select_dist['creadit_state'];
	        	$dis_network_code=$raw_select_dist['network_code'];
	        	$area_groups_id=$raw_select_dist['area_groups_id'];
	        	
			if($dis_uname==$login_user and $dis_password==$login_password)
			{
				if($creadit_state!="active")// םÚהל דבוזÔ ÕבÇÍםÉ
	        	{ 
					$status=0;if($login_lang=="ar"){$status_message=$account_susbended_ar;}else{ $status_message=$account_susbended_en;}
					$data_before = array("status" => "$status", "statusMessage" => "$status_message", "type" => "reseller");                                                                    
					echo $data = json_encode($data_before);
	        	}else{
			
					$status=1;if($login_lang=="ar"){$status_message=$login_successfully_ar;}else{ $status_message=$login_successfully_en;}
					$data_before = array("status" => "$status", "name" => "$db_a_name", "statusMessage" => "$status_message", "type" => "reseller");
					
					///////////////////////////
			        if($dis_network_code=="all"){$data_before['networks'][]="All Networks";}
			        else{
			        	$get_network_name="select name from network where code='$dis_network_code'";
			        	$r_get_network_name=@mysql_query($get_network_name);
			        	$row_get_network_name=@mysql_fetch_array($r_get_network_name);
			        	$network_name=$row_get_network_name['name'];
			        	$data_before['networks'][]=$network_name;	
			        }// end else if($dis_network_code=="all")
			        ///////////////////////////
			        if($area_groups_id=="all"){$data_before['groups'][]="All Groups";}
			        else{
			        	$get_group_name="select name from area_groups where id='$area_groups_id'";
			        	$r_get_group_name=@mysql_query($get_group_name);
			        	$row_get_group_name=@mysql_fetch_array($r_get_group_name);
			        	$group_name=$row_get_group_name['name'];
			        	$data_before['groups'][]=$group_name;
			        }// End else if($area_groups_id=="all")
			        ///////////////////////////
			                                                                        
				echo $data = json_encode($data_before);
				
	        	}//if($creadit_state!="active")// םÚהל דבוזÔ ÕבÇÍםÉ
	        	
			}//if($db_u_uname==$login_user and $db_u_password==$login_password)
			else{
				$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_password_ar;}else{ $status_message=$error_in_username_or_password_en;}
				$data_before = array("status" => "$status", "statusMessage" => "$status_message", "type" => "reseller");                                                                    
				echo $data = json_encode($data_before);}
	 }
	 /////////////////////////////////////////////////////////////////////////////////////
	 /////////////////////////////////////////////////////////////////////////////////////
	 /////////////////////////////////////////////////////////////////////////////////////
	 /////////////////////////////////////////////////////////////////////////////////////
	 
	 else{
			$status=0;if($login_lang=="ar"){$status_message=$error_in_username_or_password_ar;}else{ $status_message=$error_in_username_or_password_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message", "type" => "");                                                                    
			echo $data = json_encode($data_before);
	 		unset($can_go);}
	 	
		}//if($can_go=="yes")
		     
	 
@mysql_close($conn_user);
	

?>