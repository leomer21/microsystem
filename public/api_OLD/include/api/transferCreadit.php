<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	$disUser= $obj['distUser'];
	$creadit= $obj['creadit'];
	
	
	require_once 'include/config.php';
			
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=transferCreadit";
				//$data='{"username":"ãÍãÏ","password":"ãÍãÏ","web":"http://m","lang":"ar"}';
				// send json
				$data_before = array("username" => "$login_user", "password" => "$login_password", "web" => "$login_web_site", "lang" => "$login_lang"
				, "distUser" => "$disUser", "creadit" => "$creadit");                                                                    
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

				$search_in_users="select * from `users` where `u_uname`='$disUser' or `u_mobile`='$disUser'";
        		$r_search_in_users=@mysql_query($search_in_users,$conn_user);
        		
        		////////////////////////////////  
				
        		if(@mysql_num_rows($r_search_in_users)>0){ 
                
                 $row_search_in_users=@mysql_fetch_array($r_search_in_users);

                $dis_id=$row_search_in_users['u_id']; 
                $dis_personal_code=$row_search_in_users['personal_code'];
        		$dis_u_name=$row_search_in_users['u_name'];
        		$dis_u_uname=$row_search_in_users['u_uname'];
        		$dis_u_mobile=$row_search_in_users['u_mobile'];
        		$dis_creadit=$row_search_in_users['creadit'];
        		
        		// start Calculation
        		if($creadit<=$current_user_creadit and $creadit>=1)// user not have creadit
        		{
        			// first step : update user new creadit after discount
        			
        			$current_user_creadit_after_discount=$current_user_creadit-$creadit;
        			$updateUserCiscount="update `users` set `creadit`='$current_user_creadit_after_discount' where `u_id`='$db_id'";
        			@mysql_query($updateUserCiscount,$conn_user);
        			
        			// first step again: get current creadit for distination user at moment
        			$getCurrentDisCreaditAtMoment="select `creadit` from `users` where `u_id`='$dis_id'";
        			$r_currentDisCreaditAtMoment=@mysql_query($getCurrentDisCreaditAtMoment,$conn_user);
        			$row_currentDisCreaditAtMoment=@mysql_fetch_array($r_currentDisCreaditAtMoment);
        			$currentDisCreaditAtMoment=$row_currentDisCreaditAtMoment['creadit'];
        			
        			// Second step : upgrade distination user creadit
        			$distinationUserCreaditAfterTransfer=$currentDisCreaditAtMoment+$creadit;
        			$updateDistinationUser="update `users` set `creadit`='$distinationUserCreaditAfterTransfer' where `u_id`='$dis_id'";
        			@mysql_query($updateDistinationUser,$conn_user);
        			
        			// third step : send Messages
        			if($login_lang=="ar"){$messageType=$transferCreadit_ar;//ÞÇã ÇáÚãíá NOD ÈÊÍæíá ãÈáÛ 5 Çáì ÍÓÇÈß
        			$msgMessage="$transferCreadit_statmentA_ar $db_u_name $transferCreadit_statmentB_ar $creadit $transferCreadit_statmentC_ar";
        			$msgMessage2="$transferCreadit_statmentD_ar $creadit $transferCreadit_statmentE_ar $dis_u_name ";
        			}else{
        			$msgMessage="$transferCreadit_statmentA_en $db_u_name $transferCreadit_statmentB_en $creadit $transferCreadit_statmentC_en";
        			$msgMessage2="$transferCreadit_statmentD_en $creadit $transferCreadit_statmentE_en $dis_u_name ";
        			 $messageType=$transferCreadit_en;}
        			
        			$insert_distination_user_data="insert into `orders` 
					(`type`,`read`,`order_details`,`order_answer`,`order_user_id`,`order_type`,`order_user_name`,`order_user_uname`,`order_send_date`,`order_send_time`) values 
					('$messageType','no','$messageType','$msgMessage',$dis_id,'user','$dis_u_name','$dis_u_uname','$today','$today_time')";
        			@mysql_query($insert_distination_user_data,$conn_user);
        			
        			$insert_current_user_data="insert into `orders` 
					(`type`,`read`,`order_details`,`order_answer`,`order_user_id`,`order_type`,`order_user_name`,`order_user_uname`,`order_send_date`,`order_send_time`) values 
					('$messageType','no','$messageType','$msgMessage2',$db_id,'user','$db_u_name','$db_u_uname','$today','$today_time')";
        			@mysql_query($insert_current_user_data,$conn_user);
        			
        			// Fourth step : insert history
        			$insert_into_history2="insert into history (add_date,add_month,add_time,type1,type2,operation,u_id,u_name,u_uname,details,notes,charge_salary) values 
					('$today','$month_table_name','$today_time','microcharge','user','transfer_creadit','$db_id','$db_u_name','$db_u_uname','$dis_id','$dis_u_name','$creadit')";
					@mysql_query($insert_into_history2,$conn_user);
        			
        			// Return Data
        			$status=1;if($login_lang=="ar"){$status_message=$successfullyTransferMessage_ar;}else{ $status_message=$successfullyTransferMessage_en;}
        			$json = array("status" => "1","statusMessage" => "$status_message"
        			,"distName" => "$dis_u_name","distUser" => "$dis_u_uname","distMobile" => "$dis_u_mobile"
        			,"creaditAfterTransfer" => "$current_user_creadit_after_discount");
        			echo json_encode($json);
        			
        		}else{
        			
        			$status=0;if($login_lang=="ar"){$status_message=$sooryYouDontHaveEnoughCreadit_ar;}else{ $status_message=$sooryYouDontHaveEnoughCreadit_en;}
					$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
					echo $data = json_encode($data_before);
        		}
        		
				
                }//if(@mysql_num_rows($result_search_answer)>0)
				else{
					$status=0;if($login_lang=="ar"){$status_message=$distinationUserNotFount_ar;}else{ $status_message=$distinationUserNotFount_en;}
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