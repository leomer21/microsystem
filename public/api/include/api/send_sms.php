<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	$msgSenderName = $obj['msgSenderName'];
	//$msgLang = $obj['msgLang'];
	$msgText = $obj['msgText'];
	$msgTo = $obj['msgTo'];
	
	//{"username":"„Õ„œ","password":"„Õ„œ","web":"http://m","lang":"ar","msgTo":"201061030454","msgSenderName":"Microsystem","msgText":"Ahmed »”„ «··Â «·—Õ„‰ «·—ÕÌ„"}
	
	require_once 'include/config.php';
	$conn_sys = @mysql_connect($dbhost, $dbuser, $dbpass);
	@mysql_select_db($dbname,$conn_sys);
	
	// to connect to micro_micro DataBase to achive to sms table
	$conn_sys_sms = @mysql_connect($dbhost, $dbuser, $dbpass, true);
	@mysql_select_db($dbnameMicrosystem,$conn_sys_sms);
	
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
		
//		if($sys_local_or_web=="local")
//		{
//			if($me_local_or_web=="local"){$can_go="yes";}
//			else{
//				$url = $local_url."?op=sendSMS";
//				//$data='{"username":"„Õ„œ","password":"„Õ„œ","web":"http://m","lang":"ar","chargeCode":"8463464"}';
//				// send json
//				$data_before = array("username" => "$login_user", "password" => "$login_password", "web" => "$login_web_site", "lang" => "$login_lang"
//				, "msgSenderName" => "$msgSenderName", "msgLang" => "$msgLang", "msgText" => "$msgText", "msgTo" => "$msgTo");                                                                    
//				$data = json_encode($data_before);    
//				$options = array(
//						'http' => array(
//								'header'  => "Content-type: application/json; charset=utf-8\r\n",
//								'method'  => 'POST',
//								'content' => "$data",
//						),
//				);
//				$context=@stream_context_create($options);
//				echo $result=file_get_contents($url,false,$context);
//								
//			}
//		}else {
//			$can_go="yes";
//		}
//		
//		if($can_go=="yes"){
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
			$db_u_name=$row_get_user_data['u_name'];
			$db_u_uname=$row_get_user_data['u_uname'];
			$db_u_password=$row_get_user_data['u_password'];
			$db_u_mail=$row_get_user_data['u_mail'];
			$current_user_creadit=$row_get_user_data['creadit'];
			$suspend=$row_get_user_data['suspend'];
			$total_points=$row_get_user_data['u_points'];
			$u_canuse=$row_get_user_data['u_canuse'];
			
			$u_mobile=$row_get_user_data['u_mobile'];
			$network_code=$row_get_user_data['u_mobile'];
			
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
		
        $select_mikrotik_info="select * from network where code='$network_code'";
        $result_select_mikrotik_info=@mysql_query($select_mikrotik_info,$conn_user);	
        $row_result_select_mikrotik_info=@mysql_fetch_array($result_select_mikrotik_info);
        $wan_or_lan=$row_result_select_mikrotik_info['wan_or_lan'];
	                			
				
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
					
					
     function IsItUnicode($msg){
      $unicode=0;
      $str = "œÃÕŒÂ⁄€›ﬁÀ’÷ÿﬂ„‰ «·»Ì”‘Ÿ“Ê…Ï·«—ƒ¡∆≈·≈√·√¬·¬";
      for($i=0;$i<=strlen($str);$i++)
          {
          $strResult= substr($str,$i,1);
          for($R=0;$R<=strlen($msg);$R++)
              {
               $msgResult= substr($msg,$R,1);
                if($strResult==$msgResult && $strResult)
                   $unicode=1;
               }
         }
    
    return $unicode;
    }
   
        $error_value="null";
        $text=$msgText;
        $text3=$msgText;
        $to=$msgTo;
        
        $unicode = IsItUnicode($text);
        $cost = 1 ;
        $message_____=utf8_decode($text3);
            if ($unicode == 0){
       if (strlen($message_____) >= 161 && strlen($message_____) <= 306) $cost=2;
       if (strlen($message_____) >= 307 && strlen($message_____) <= 459) $cost=3;
       if (strlen($message_____) >= 460 && strlen($message_____) <= 612) $cost=4;
       if (strlen($message_____) >= 613 && strlen($message_____) <= 765) $cost=5;
       if (strlen($message_____) >= 766 && strlen($message_____) <= 918) $cost=6;
       if (strlen($message_____) >= 919 )
       {
       		$status=0;if($login_lang=="ar"){$status_message=$send_sms_no_more_en_6_msg_ar;}else{ $status_message=$send_sms_no_more_en_6_msg_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);
			
       		$error_value="error";}
       
       }
       else{
       
       if (strlen($message_____) >= 71   && strlen($message_____) <= 134) $cost=2;
       if (strlen($message_____) >= 135  && strlen($message_____) <= 201) $cost=3;
       if (strlen($message_____) >= 202  && strlen($message_____) <= 268) $cost=4;

       if (strlen($message_____) > 268)
       {
       		$status=0;if($login_lang=="ar"){$status_message=$send_sms_no_more_ar_6_msg_ar;}else{ $status_message=$send_sms_no_more_ar_6_msg_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);
			$error_value="error";}
			
		} // End if Unicode UNKNOWN
        
        
        if($cost<=$total_points and $error_value=="null")
        {
       		   $message_content=urlencode($text);
		       $sms_r_mobile_number=$to;
			   $sms_r_content_message=$message_content;
		       if($unicode==0){$sms_type=0;}// English
		       else{$sms_type=2;}// Arabic
		  
        // Sending SMS
      
				// get sms data
                $select_admin_data_for_sms="select * from informations where type='sms'";
                $query_select_admin_data_for_sms=@mysql_query($select_admin_data_for_sms,$conn_user);
                $row_admin_data_for_sms= @mysql_fetch_array($query_select_admin_data_for_sms);
                    $admin_uname_sms=$row_admin_data_for_sms['admin_uname_sms'];
                    $admin_pass_sms=$row_admin_data_for_sms['admin_pass_sms'];
                    $company_name_sms=$row_admin_data_for_sms['company_name_sms'];
                    $sms_state_user_lang=$row_admin_data_for_sms['sms_state_user_lang'];
                    $sms_state_user_special=$row_admin_data_for_sms['sms_state_user_special'];
                 
		                $get_customer_data="select * from informations";
						$r_get_customer_data=@mysql_query($get_customer_data,$conn_user);
						
						while ($row_get_customer_data = @mysql_fetch_array($r_get_customer_data)){
						$first_data=$row_get_customer_data['type'];
						if($first_data=="customer_id"){$current_get_customer_id=$row_get_customer_data['control'];}
						if($first_data=="sms_provider"){$sms_provider=$row_get_customer_data['control'];}
						if($first_data=="customer_url"){$customer_url=$row_get_customer_data['control'];}}
    				
            			////////////////////////////////////////////////////////////
						if($admin_uname_sms and $admin_pass_sms and $company_name_sms){
						if($wan_or_lan=="wan"){
	    					$get_currend_sms_creadit="SELECT * FROM `sms` where `u_id`='$current_get_customer_id' and `user`='$admin_uname_sms' and `password`='$admin_pass_sms'";
	                    	$r_get_currend_sms_creadit=@mysql_query($get_currend_sms_creadit,$conn_sys_sms);
	                    	$row_get_currend_sms_creadit=@mysql_fetch_array($r_get_currend_sms_creadit);
	                    	$current_sms_creadit=$row_get_currend_sms_creadit['palance'];
	                    	$current_sms_provider_user=$row_get_currend_sms_creadit['provider_user'];
	                    	$current_sms_provider_password=$row_get_currend_sms_creadit['provider_password'];
	                    	$current_sms_provider_name=$row_get_currend_sms_creadit['provider_name'];
	                    	$current_sms_sender_name=$row_get_currend_sms_creadit['sender_name'];
	                    	
	                    	$current_sms_sender_name=$msgSenderName;// NEW 31.5.2014
	                    	
	                    	if(!$current_sms_creadit){
	                    		$status=0;if($login_lang=="ar"){$status_message=$contactNetworkAdministrator_ar;}else{ $status_message=$contactNetworkAdministrator_en;}
								$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
								echo $data = json_encode($data_before);
	                    	}
						}//if($wan_or_lan=="wan")
                    		if($current_sms_creadit>=$cost or $wan_or_lan=="lan"){
                    			
                    			if($wan_or_lan=="lan"){
                    			$current_sms_provider_user=$admin_uname_sms;
                    			$current_sms_provider_password=$admin_pass_sms;
                    			$current_sms_sender_name=$company_name_sms;
                    			$current_sms_provider_name=$sms_provider;
                    			}
                    			
                    			if($current_sms_provider_name=="resalty")
                    			{
                    				
                    				if($sms_type=="2")// AR
                    				{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$current_sms_provider_user&password=$current_sms_provider_password&to=$sms_r_mobile_number&sender=$current_sms_sender_name&encoding=utf-8&msg=$sms_r_content_message";}
                    				if($sms_type=="0")// EN
                    				{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$current_sms_provider_user&password=$current_sms_provider_password&to=$sms_r_mobile_number&sender=$current_sms_sender_name&msg=$sms_r_content_message";}
					                $result1=@file($url1);
	                    			$find="MessageID";
									$string=$result1[0];
									if(strpos($string, $find) ===false){ // not Sent Ì⁄‰Ï «·—”«·… „ »⁄  ‘Ì Ì⁄‰Ï „‘ Â ⁄„· Õ«Ã…...
									}else{// Send Successfully
										$message_sent_successfully="yes";}
	                    		}//if($currend_sms_provider_name=="resalty")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			if($current_sms_provider_name=="masrawy")
                    			{
                    			// OLD system $url1="http://www.masrawy.com/SMSService/send.aspx?userName=$current_sms_provider_user&password=$current_sms_provider_password&mobile=$sms_r_mobile_number&message=$sms_r_content_message&Sender=$current_sms_sender_name";
                    			$url1="http://sms.masrawy.com/send.aspx?userName=$current_sms_provider_user&password=$current_sms_provider_password&mobile=$sms_r_mobile_number&message=$sms_r_content_message&Sender=$current_sms_sender_name";
                    			$result1=@file($url1);
                    			$find="SentSuccessfully";
								$string=$result1[0];
								if(strpos($string, $find) ===false){ // not Sent Ì⁄‰Ï «·—”«·… „ »⁄  ‘Ì Ì⁄‰Ï „‘ Â ⁄„· Õ«Ã…...
								}else{// Send Successfully
									$message_sent_successfully="yes";}
								}//if($currend_sms_provider_name=="masrawy")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			if($current_sms_provider_name=="valuedsms")
                    			{
                    				
                    				if($sms_type=="2")// AR
                    				{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=2&username=$current_sms_provider_user&password=$current_sms_provider_password&destination=$sms_r_mobile_number&message=$sms_r_content_message&sender=$current_sms_sender_name";}
                    				if($sms_type=="0")// EN
                    				{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=0&username=$current_sms_provider_user&password=$current_sms_provider_password&destination=$sms_r_mobile_number&message=$sms_r_content_message&sender=$current_sms_sender_name";}
                    				$result1=@file($url1);
                    				$find="true";
									$string=$result1[0];
									if(strpos($string, $find) ===false){ // not Sent Ì⁄‰Ï «·—”«·… „ »⁄  ‘Ì Ì⁄‰Ï „‘ Â ⁄„· Õ«Ã…...
									}else{// Send Successfully
										$message_sent_successfully="yes";}
                    			}//if($currend_sms_provider_name=="valuedsms")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			
                    			if($message_sent_successfully=="yes"){
                    				if($wan_or_lan=="wan"){
                    				// step 1 Â‰Œ’„ «·—’Ìœ „‰ Õ”«» «·⁄„Ì·
                    				$current_sms_creadit_after_discount=$current_sms_creadit-$cost;
                    				$discount_creadit_from_user="update sms set palance='$current_sms_creadit_after_discount' where u_id='$current_get_customer_id'";
                    				@mysql_query($discount_creadit_from_user,$conn_sys_sms);
                    				}
                    				// step 2 insert history
                    				$insert_into_history2="insert into history (add_date,add_month,add_time,type1,type2,operation,details,notes,u_id,u_name,u_uname) values 
									('$today','$month_table_name','$today_time','microcharge','user','sms_user_sent','$cost','$sms_r_mobile_number:$text','$db_id','$db_u_name','$db_u_uname')";
									@mysql_query($insert_into_history2,$conn_user);
									// Ubdate New End user Palance
							     	$select_number_of_points="select * from users where u_uname='$db_u_uname'";
							        $result_select_number_of_points=@mysql_query($select_number_of_points,$conn_user);
							        $number_of_points_in_array=@mysql_fetch_array($result_select_number_of_points);
							        $total_points=$number_of_points_in_array['u_points'];
							     	$final_points_after_send=$total_points-$cost;
									$ubdate_new_palance="update users set u_points='$final_points_after_send' where u_id='$db_id'";
							        @mysql_query($ubdate_new_palance,$conn_user);
							        
							        $status=1;if($login_lang=="ar"){$status_message=$send_sms_successfully_ar;}else{ $status_message=$send_sms_successfully_en;}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message"
									, "smsCost" => "$cost", "yourBalance" => "$final_points_after_send");                                                                    
									echo $data = json_encode($data_before);
		
                    				unset($message_sent_successfully);}else{
                    				$status=0;if($login_lang=="ar"){$status_message=$sms_error_in_sending_ar;}else{ $status_message=$sms_error_in_sending_en;}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
									echo $data = json_encode($data_before);   // ÕœÀ  „‘ﬂ·… ›Ï «·«—”«·	
                    				}

                    	        }// End else if($current_sms_creadit>=1)
                    	        else{// Ì⁄‰Ï „›Ì‘ —’Ìœ ⁄‰œ ’«Õ» «·‘»ﬂ… ›Ï ”Ì” „ «·‘—ﬂ…
                    	        	$status=0;if($login_lang=="ar"){$status_message=$contactNetworkAdministrator_2_ar;}else{ $status_message=$contactNetworkAdministrator_2_en;}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
									echo $data = json_encode($data_before);}
								
                    }//if($admin_uname_sms and $admin_pass_sms and $company_name_sms)
                    else{// Ì⁄‰Ï „›Ì‘ «ﬂÊ‰  ⁄‰œ ’«Õ» «·‘»ﬂ… ›Ï ”Ì” „ «·‘—ﬂ…
                    				$status=0;if($login_lang=="ar"){$status_message=$contactNetworkAdministrator_3_ar;}else{ $status_message=$contactNetworkAdministrator_3_en;}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
									echo $data = json_encode($data_before);}
                    	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                    	
} // End Success Send Message
else{
			$status=0;if($login_lang=="ar"){$status_message=$sms_not_enough_creadit_ar;}else{ $status_message=$sms_not_enough_creadit_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);}
    
    
				
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

//		unset($can_go);
//		}//if($can_go=="yes")
//		     
	 }
	 else{
			$status=0;if($login_lang=="ar"){$status_message=$web_site_error_ar;}else{ $status_message=$web_site_error_en;}
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);}
	 
	
@mysql_close($conn_user);
@mysql_close($conn_sys);
@mysql_close($conn_sys_sms);
	
                    
?>