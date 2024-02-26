<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	$cardType = $obj['CardType'];
	$CardPrice = $obj['CardPrice'];
	$chargeType = $obj['ChargeType'];
	
	
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
				$url = $local_url."?op=mobileCards1";
				//$data='{"username":"„Õ„œ","password":"„Õ„œ","web":"http://m","lang":"ar","CardType":"Vodafone","CardPrice":"0.50"}';
				// send json 																/Mobinil/Etisalat/Marhaba		/1.00/1.50/10
				$data_before = array("username" => "$login_user", "password" => "$login_password", "web" => "$login_web_site", "lang" => "$login_lang"
				, "CardType" => "$cardType", "CardPrice" => "$CardPrice", "ChargeType" => "$chargeType");                                                                    
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
	if($first_data=="customer_url"){$customer_url=$row_get_customer_data['control'];}
	if($first_data=="mobile_cards_payment_type"){$customer_mobile_cards_payment_type=$row_get_customer_data['control'];}}
	
if($customer_mobile_cards_payment_type=="from_user")
{
	$payment_type="from_user";
}else{ // from_company
	
				$check_for_customers_mob_data="select * from customers_mob_data where customer_id='$current_get_customer_id'";
		 		$r_check_for_customers_mob_data=@mysql_query($check_for_customers_mob_data,$conn_sys2);
		 		if(@mysql_num_rows($r_check_for_customers_mob_data)>0)// Ì⁄‰Ï „ ÷«› ﬁ»· ﬂœ… Ì»ﬁÏ Â⁄„· «»œÌ 
		 		{
		 			$row_last_customer_creadit=@mysql_fetch_array($r_check_for_customers_mob_data);
		 			$last_creadit=$row_last_customer_creadit['creadit'];
		 			$payment_type=$row_last_customer_creadit['payment_type'];
		 		}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// S T E P  2  /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
if($cardType=="Vodafone" and $CardPrice=="1"){$action_type="vod1";}
if($cardType=="Vodafone" and $CardPrice=="2"){$action_type="vod2";}
if($cardType=="Vodafone" and $CardPrice=="3"){$action_type="vod3";}
if($cardType=="Vodafone" and $CardPrice=="4"){$action_type="vod4";}
if($cardType=="Vodafone" and $CardPrice=="5"){$action_type="vod5";}
if($cardType=="Vodafone" and $CardPrice=="10"){$action_type="vod10";}
if($cardType=="Vodafone" and $CardPrice=="15"){$action_type="vod15";}
if($cardType=="Vodafone" and $CardPrice=="25"){$action_type="vod25";}
if($cardType=="Vodafone" and $CardPrice=="50"){$action_type="vod50";}
if($cardType=="Vodafone" and $CardPrice=="100"){$action_type="vod100";}

if($cardType=="Etisalat" and $CardPrice=="0.50"){$action_type="etis0_50";}
if($cardType=="Etisalat" and $CardPrice=="1"){$action_type="etis1";}
if($cardType=="Etisalat" and $CardPrice=="1.50"){$action_type="etis1_50";}
if($cardType=="Etisalat" and $CardPrice=="3"){$action_type="etis3";}
if($cardType=="Etisalat" and $CardPrice=="5"){$action_type="etis5";}
if($cardType=="Etisalat" and $CardPrice=="10"){$action_type="etis10";}
if($cardType=="Etisalat" and $CardPrice=="15"){$action_type="etis15";}
if($cardType=="Etisalat" and $CardPrice=="25"){$action_type="etis25";}
if($cardType=="Etisalat" and $CardPrice=="50"){$action_type="etis50";}
if($cardType=="Etisalat" and $CardPrice=="100"){$action_type="etis100";}

if($cardType=="Mobinil" and $CardPrice=="5"){$action_type="mob5";}
if($cardType=="Mobinil" and $CardPrice=="10"){$action_type="mob10";}
if($cardType=="Mobinil" and $CardPrice=="15"){$action_type="mob15";}
if($cardType=="Mobinil" and $CardPrice=="25"){$action_type="mob25";}
if($cardType=="Mobinil" and $CardPrice=="50"){$action_type="mob50";}
if($cardType=="Mobinil" and $CardPrice=="100"){$action_type="mob100";}

if($cardType=="Marhaba" and $CardPrice=="5"){$action_type="marhaba5";}
if($cardType=="Marhaba" and $CardPrice=="10"){$action_type="marhaba10";}
if($cardType=="Marhaba" and $CardPrice=="20"){$action_type="marhaba20";}
if($cardType=="Marhaba" and $CardPrice=="30"){$action_type="marhaba30";}
if($cardType=="Marhaba" and $CardPrice=="50"){$action_type="marhaba50";}
			

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($payment_type=="special_cards")// Ì⁄‰Ï ‘«—Ì ﬂ—Ê  ›ﬁÿ „‘ —’Ìœ ‘«„·
{

$check_for_creadit_card="select * from customers_mob_data where customer_id='$current_get_customer_id'";
$r_check_for_creadit_card=@mysql_query($check_for_creadit_card,$conn_sys2)or die(mysql_error());
$row_check_for_creadit_card=@mysql_fetch_array($r_check_for_creadit_card);
$no_of_valid_cards=$row_check_for_creadit_card[$action_type];

//echo "<br>cost:".$CardPrice;

if($no_of_valid_cards>0 and $CardPrice<=$last_creadit)// Ì»ﬁÏ «·—«Ã· «·„Õ —„ ’«Õ» «·‘»ﬂ… ⁄‰œ… —’Ìœ ﬂ—Ê  Êﬂ„«‰ —’Ìœ ›·Ê”
{
$first_2_char = str_split($action_type, 2);
$card__type__=$first_2_char[0];
if($card__type__=="vo"){$final_card_type="Vodafone";}
if($card__type__=="mo"){$final_card_type="Mobinil";}
if($card__type__=="et"){$final_card_type="Etisalat";}
if($card__type__=="ma"){$final_card_type="Marhaba";}
    $get_selected_card="select * from mob_cards where card_type='$final_card_type' and amount='$CardPrice' and state='active' order by id ASC";
	$r_get_selected_card=@mysql_query($get_selected_card,$conn_sys2);
	if(@mysql_num_rows($r_get_selected_card)>0)// Ì»ﬁÏ ›Ï ﬂ«—  Ì«·« «»”ÿ
	{ 
		$row_get_selected_card=@mysql_fetch_array($r_get_selected_card);
		// Card Data
		$selected_card_id=$row_get_selected_card['id'];
		$selected_card_cost=$row_get_selected_card['cost'];
		$selected_card_amount=$row_get_selected_card['amount'];
		
		//get_network_cost
		$searched_card_type="fee_"."$action_type";
		$network_cost=$row_check_for_creadit_card["$searched_card_type"];
		////////////////////////////////////////////////
		$total_cost=$network_cost+$selected_card_cost;
		$total_final_salary=$total_cost+$selected_card_amount;
		////////////////////////////////////////////////

		$status=1;if($login_lang=="ar"){$status_message="successfully";}else{ $status_message="successfully";}
		$data_before = array("status" => "$status", "statusMessage" => "$status_message"
		, "cardFee" => "$selected_card_cost", "cardAmount" => "$selected_card_amount", "totalCost" => "$total_final_salary");                                                                    
		echo $data = json_encode($data_before);
			
	}else{// Ì»ﬁÏ „›Ì‘ ﬂ«—  ›Ï «·ﬁ«⁄œ… ⁄‰œÏ Ê’«Õ» «·‘»ﬂ… ⁄‰œÊ —’Ìœ
		
		$status=0;if($login_lang=="ar"){$status_message=$error_mobile_not_found_card_ar;}else{ $status_message=$error_mobile_not_found_card_en;}
		$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
		echo $data = json_encode($data_before);
		
		$insert_history_in_company="insert into history (type,value1,value2,value3,value4,value5,value6) values('not_found_mobile_card','$final_card_type','$CardPrice','$current_get_customer_id','$uname','$today_date','$today_time') ";
		@mysql_query($insert_history_in_company,$conn_sys2)or die(mysql_error());}
	
	
}else{
		$status=0;if($login_lang=="ar"){$status_message=$error_mobile_not_found_card_ar;}else{ $status_message=$error_mobile_not_found_card_en;}
		$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
		echo $data = json_encode($data_before);}
		
}//if($payment_type=="special_cards")// Ì⁄‰Ï ‘«—Ì ﬂ—Ê  ›ﬁÿ „‘ —’Ìœ ‘«„·

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

elseif ($payment_type=="from_user")// Ì»ﬁÏ «·ﬂ—Ê  ›Ï ﬁ«⁄œ… »Ì«‰«  «·⁄„Ì·
{
	
	
$first_2_char = str_split($action_type, 2);
$card__type__=$first_2_char[0];
if($card__type__=="vo"){$final_card_type="Vodafone";}
if($card__type__=="mo"){$final_card_type="Mobinil";}
if($card__type__=="et"){$final_card_type="Etisalat";}
if($card__type__=="ma"){$final_card_type="Marhaba";}
    $get_selected_card="select * from mob_cards where card_type='$final_card_type' and amount='$CardPrice' and state='active' order by id ASC";
	$r_get_selected_card=@mysql_query($get_selected_card,$conn_user);
	if(@mysql_num_rows($r_get_selected_card)>0)// Ì»ﬁÏ ›Ï ﬂ«—  Ì«·« «»”ÿ
	{
		$row_get_selected_card=@mysql_fetch_array($r_get_selected_card);
		// Card Data
		$selected_card_id=$row_get_selected_card['id'];
		$selected_card_cost=$row_get_selected_card['cost'];
		$selected_card_amount=$row_get_selected_card['amount'];
		
		////////////////////////////////////////////////
		$total_final_salary=$selected_card_cost+$selected_card_amount;
		////////////////////////////////////////////////
		
		$status=1;if($login_lang=="ar"){$status_message="successfully";}else{ $status_message="successfully";}
		$data_before = array("status" => "$status", "statusMessage" => "$status_message"
		, "cardFee" => "$selected_card_cost", "cardAmount" => "$selected_card_amount", "totalCost" => "$total_final_salary");

		
	}else{// Ì»ﬁÏ „›Ì‘ ﬂ«—  ›Ï «·ﬁ«⁄œ… ⁄‰œÏ Ê’«Õ» «·‘»ﬂ… ⁄‰œÊ —’Ìœ
		$status=0;if($login_lang=="ar"){$status_message=$error_mobile_not_found_card_ar;}else{ $status_message=$error_mobile_not_found_card_en;}
		$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
		echo $data = json_encode($data_before);
		
		$insert_history_in_company="insert into history (type,value1,value2,value3,value4,value5,value6) values('not_found_mobile_card','$final_card_type','$CardPrice','$current_get_customer_id','$uname','$today_date','$today_time') ";
		@mysql_query($insert_history_in_company,$conn_sys2)or die(mysql_error());}
	
	
	
}//elseif ($payment_type=="from_user")// Ì»ﬁÏ «·ﬂ—Ê  ›Ï ﬁ«⁄œ… »Ì«‰«  «·⁄„Ì·
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

elseif($payment_type=="all"){// Ì⁄‰Ï «·—«Ã· ‘«—Ï —’Ìœ ‘«„· ﬂ· «·ﬂ—Ê 
	
	
}// End else Ì⁄‰Ï «·—«Ã· ‘«—Ï —’Ìœ ‘«„· ﬂ· «·ﬂ—Ê 
else{
		$status=0;if($login_lang=="ar"){$status_message=$error_mobile_system_not_supported_ar;}else{ $status_message=$error_mobile_system_not_supported_en;}
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
@mysql_close($conn_sys2);
	
                    
?>