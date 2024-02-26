<?php 

                    				//$xml=simplexml_load_file("xml.xml");      	
									//foreach($xml->result as $child) {
									//  echo $child->status;
									//  echo $child->messageid;
									//  echo $child->destination;
									//}
									//echo $xml->result->status;


if($pageName4SMS=="admin_send_sms.php")
{
								if($current_sms_provider_name=="resalty")
                    			{
                    				if($sms_type=="2")//Arabic
                    				{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$current_sms_provider_user&password=$current_sms_provider_password&to=$to&sender=$current_sms_sender_name&encoding=utf-8&msg=$message_content";}
                    				if($sms_type=="0")//ENGLISH
                    				{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$current_sms_provider_user&password=$current_sms_provider_password&to=$to&sender=$current_sms_sender_name&msg=$message_content";}
					                $result1=@file($url1);
	                    			$find="MessageID";
									$string=$result1[0];
									if(strpos($string, $find) ===false){ // not Sent يعنى الرسالة متبعتتشي يعنى مش هتعمل حاجة...
									}else{// Send Successfully
										$message_sent_successfully="yes";}
	                    			}//if($currend_sms_provider_name=="resalty")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			if($current_sms_provider_name=="masrawy")
                    			{
                    			// OLD system echo $url1="http://www.masrawy.com/SMSService/send.aspx?userName=$current_sms_provider_user&password=$current_sms_provider_password&mobile=$to&message=$message_content&Sender=$current_sms_sender_name";
                    			$url1="http://sms.masrawy.com/send.aspx?userName=$current_sms_provider_user&password=$current_sms_provider_password&mobile=$to&message=$message_content&Sender=$current_sms_sender_name";
                    			
                    			$result1=@file($url1);
                    			$find="SentSuccessfully";
								$string=$result1[0];
								if(strpos($string, $find) ===false){ // not Sent يعنى الرسالة متبعتتشي يعنى مش هتعمل حاجة...
								}else{// Send Successfully
									$message_sent_successfully="yes";}
                    			
                    			}//if($currend_sms_provider_name=="masrawy")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			if($current_sms_provider_name=="valuedsms")
                    			{
                    				if($sms_type=="2")//Arabic
                    				{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=2&username=$current_sms_provider_user&password=$current_sms_provider_password&destination=$to&message=$message_content&sender=$current_sms_sender_name";}
                    				if($sms_type=="0")//ENGLISH
                    				{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=0&username=$current_sms_provider_user&password=$current_sms_provider_password&destination=$to&message=$message_content&sender=$current_sms_sender_name";}
                    				$result1=@file($url1);
                    				$find="true";
									$string=$result1[0];
									if(strpos($string, $find) ===false){ // not Sent يعنى الرسالة متبعتتشي يعنى مش هتعمل حاجة...
									}else{// Send Successfully
										$message_sent_successfully="yes";}
                    			}//if($currend_sms_provider_name=="valuedsms")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			if($current_sms_provider_name=="infobip")
                    			{
                    				$senderID=$current_sms_sender_name;
                    				if($sms_type=="2")//Arabic
                    				{$url1="http://api.infobip.com/api/v3/sendsms/plain?user=$current_sms_provider_user&password=$current_sms_provider_password&sender=$senderID&GSM=$to&datacoding=8&SMSText=$message_content";}
                    				if($sms_type=="0")//ENGLISH
                    				{$url1="http://api.infobip.com/api/v3/sendsms/plain?user=$current_sms_provider_user&password=$current_sms_provider_password&sender=$senderID&GSM=$to&SMSText=$message_content";}
                    				$result1=@file($url1);
                    				$find="<result><status>0";
                    				$string=$result1[2];// not remove
									
	                    			if(strpos($string, $find) ===false){ // not Sent يعنى الرسالة متبعتتشي يعنى مش هتعمل حاجة...
									}else{// Send Successfully
									$message_sent_successfully="yes";}
									
                    			}//if($currend_sms_provider_name=="valuedsms")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
	
                    			
}//if($pageName4SMS=="admin_send_sms.php")

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// location : /user/sms.php

if($pageName4SMS=="sms.php")
{
	
    	if($current_sms_provider_name=="resalty")
        {
    		
    		if($sms_type=="2")// AR
    		{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$current_sms_provider_user&password=$current_sms_provider_password&to=$sms_r_mobile_number&sender=$current_sms_sender_name&encoding=utf-8&msg=$sms_r_content_message";}
    		if($sms_type=="0")// EN
    		{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$current_sms_provider_user&password=$current_sms_provider_password&to=$sms_r_mobile_number&sender=$current_sms_sender_name&msg=$sms_r_content_message";}
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
            $url1="http://www.masrawy.com/SMSService/send.aspx?userName=$current_sms_provider_user&password=$current_sms_provider_password&mobile=$sms_r_mobile_number&message=$sms_r_content_message&Sender=$current_sms_sender_name";
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
    		if($sms_type=="2")// AR
    		{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=2&username=$current_sms_provider_user&password=$current_sms_provider_password&destination=$sms_r_mobile_number&message=$sms_r_content_message&sender=$current_sms_sender_name";}
    		if($sms_type=="0")// EN
    		{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=0&username=$current_sms_provider_user&password=$current_sms_provider_password&destination=$sms_r_mobile_number&message=$sms_r_content_message&sender=$current_sms_sender_name";}
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
        if($current_sms_provider_name=="infobip")
        {
    		$senderID=$current_sms_sender_name;
    		if(!$senderID){$senderID=$current_sms_sender_name;}
    		if($sms_type=="2")//Arabic
    		{$url1="http://api.infobip.com/api/v3/sendsms/plain?user=$current_sms_provider_user&password=$current_sms_provider_password&sender=$senderID&GSM=$sms_r_mobile_number&datacoding=8&SMSText=$sms_r_content_message";}
    		if($sms_type=="0")//ENGLISH
    		{$url1="http://api.infobip.com/api/v3/sendsms/plain?user=$current_sms_provider_user&password=$current_sms_provider_password&sender=$senderID&GSM=$sms_r_mobile_number&SMSText=$sms_r_content_message";}
    		$result1=@file($url1);
    		$find="<result><status>0";
    		$string=$result1[2];// not remove
    		
	        if(strpos($string, $find) ===false){ // not Sent يعنى الرسالة متبعتتشي يعنى مش هتعمل حاجة...
    		}else{// Send Successfully
    		$message_sent_successfully="yes";}
    		
        }//if($currend_sms_provider_name=="valuedsms")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
	
	
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// location: /user/charging_radius/chargePackageSMS.php
if($pageName4SMS=="chargePackageSMS.php"){
	
		if(strlen($current_user_mobile)==11)// for Egyption numbers only add contry code in first of mobile number
		{$current_user_mobile="2".$current_user_mobile;}
		
    	if($current_sms_provider_name=="resalty")
        {
    		if($sms_state_user_lang=="ar")// AR
    		{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$admin_uname_sms&password=$admin_pass_sms&to=$current_user_mobile&sender=$company_name_sms&encoding=utf-8&msg=$content_message";}
    		if($sms_state_user_lang=="en")// EN
    		{$url1 ="http://www.resalty.net/api/sendSMS.php?userid=$admin_uname_sms&password=$admin_pass_sms&to=$current_user_mobile&sender=$company_name_sms&msg=$content_message";}
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
            $url1="http://www.masrawy.com/SMSService/send.aspx?userName=$admin_uname_sms&password=$admin_pass_sms&mobile=$current_user_mobile&message=$content_message&Sender=$company_name_sms";
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
    		if($sms_state_user_lang=="ar")// AR
    		{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=2&username=$admin_uname_sms&password=$admin_pass_sms&destination=$current_user_mobile&message=$content_message&sender=$company_name_sms";}
    		if($sms_state_user_lang=="en")// EN
    		{$url1="http://api.valuedsms.com/?command=send_sms&sms_type=0&username=$admin_uname_sms&password=$admin_pass_sms&destination=$current_user_mobile&message=$content_message&sender=$company_name_sms";}
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
        if($current_sms_provider_name=="infobip")
        {
    		$senderID=$current_sms_sender_name;
    		if(!$senderID){$senderID=$company_name_sms;}
    		if($sms_type=="2")//Arabic
    		{$url1="http://api.infobip.com/api/v3/sendsms/plain?user=$admin_uname_sms&password=$admin_pass_sms&sender=$senderID&GSM=$current_user_mobile&datacoding=8&SMSText=$content_message";}
    		if($sms_type=="0")//ENGLISH
    		{$url1="http://api.infobip.com/api/v3/sendsms/plain?user=$admin_uname_sms&password=$admin_pass_sms&sender=$senderID&GSM=$current_user_mobile&SMSText=$content_message";}
    		$result1=@file($url1);
    		$find="<result><status>0";
    		$string=$result1[2];// not remove
    		
	        if(strpos($string, $find) ===false){ // not Sent يعنى الرسالة متبعتتشي يعنى مش هتعمل حاجة...
    		}else{// Send Successfully
    		$message_sent_successfully="yes";}
    		
        }//if($currend_sms_provider_name=="valuedsms")
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
                    			/////////////////////////////////////////////
	
	
}//if($pageName4SMS=="monthlyPackage.php")
?>