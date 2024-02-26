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
	
	//{"username":"ãÍãÏ","password":"ãÍãÏ","web":"http://m","lang":"ar","msgTo":"201061030454","msgSenderName":"Microsystem","msgText":"Ahmed ÈÓã Çááå ÇáÑÍãä ÇáÑÍíã"}
	
	require_once 'include/config.php';
	 ///////////////////////////////////////////////////       
	        // Connect to system database
	 ///////////////////////////////////////////////////     
	   
//		if($can_go=="yes"){
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
			$total_points=$row_get_user_data['u_points'];
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
		
					
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
					
					
     function IsItUnicode($msg){
      $unicode=0;
      $str = "ÏÌÍÎåÚÛİŞËÕÖØßãäÊÇáÈíÓÔÙÒæÉìáÇÑÄÁÆÅáÅÃáÃÂáÂ";
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
        
        
        //if($cost<=$total_points and $error_value=="null")
        if($error_value=="null")
        {
       		   $message_content=urlencode($text);
		       $sms_r_mobile_number=$to;
			   $sms_r_content_message=$message_content;
		       if($unicode==0){$sms_type=0;}// English
		       else{$sms_type=2;}// Arabic
		  
        // Sending SMS
  
						////////////////////////////////////////////////////////////
						if(strlen($to)=="12"){// This condition To Limit send SMS From Egypt Only and limit respects to 1 Mobile Number
                     	
							     	$select_number_of_points="select * from users where u_uname='$db_u_uname'";
							        $result_select_number_of_points=@mysql_query($select_number_of_points,$conn_user);
							        $number_of_points_in_array=@mysql_fetch_array($result_select_number_of_points);
							        $total_points=$number_of_points_in_array['u_points'];
							     	$final_points_after_send=$total_points-$cost;
							     	
									if(!$total_points or $total_points<0){$total_points=0;}
							     	if($final_points_after_send<0){$final_points_after_send=0;}
									
							        $status=1;if($login_lang=="ar"){$status_message="";}else{ $status_message="";}
									$data_before = array("status" => "$status", "statusMessage" => "$status_message"
									, "smsCost" => "$cost", "yourBalanceBeforeSending" => "$total_points"
									, "yourBalanceAfterSending" => "$final_points_after_send");                                                                    
									echo $data = json_encode($data_before);
		
                    				

                    	       
                    }//if($admin_uname_sms and $admin_pass_sms and $company_name_sms)
                    else{// íÈŞì ÇáÈÇÔÇ ÈíÈÚÊ áÇßÊÑ ãä ÑŞã ÇÉ ÈíÈÚÊ áÑŞã ÎÇÑÌ ãÕÑ
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

	
@mysql_close($conn_user);
	
                    
?>