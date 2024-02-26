<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	$chargeCode = $obj['chargeCode'];
	
	require_once 'include/config.php';
	 ///////////////////////////////////////////////////       
	        // Connect to system database
	 ///////////////////////////////////////////////////     
		
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=charge";
				//$data='{"username":"ãÍãÏ","password":"ãÍãÏ","web":"http://m","lang":"ar","chargeCode":"8463464"}';
				// send json
				$data_before = array("username" => "$login_user", "password" => "$login_password", "chargeCode" => "$chargeCode", "web" => "$login_web_site", "lang" => "$login_lang");                                                                    
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

				
				$charge_card_no=$chargeCode;
				//////////////////////////////////////////////////////////////////
				//////////////////////////   subDate   ///////////////////////////
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
				//////////////////////////   subDate   ///////////////////////////
				//////////////////////////////////////////////////////////////////
$check_for_card="select * from cards where card_no like '$charge_card_no' and card_canuse='1'";
$result_check_for_card=@mysql_query($check_for_card,$conn_user);
while ($row_check_for_card = @mysql_fetch_array($result_check_for_card)) {
	$card_no_from_db=$row_check_for_card['card_no'];
	$card_price=$row_check_for_card['card_price'];
	$card_id=$row_check_for_card['card_id'];
	$start_pay_date=$row_check_for_card['start_pay_date'];
	$end_pay_date=$row_check_for_card['end_pay_date'];
	
	
						    if(@mysql_num_rows($result_check_for_card)>0){$true_card="yes";}
						    /////////////////////
						    if($start_pay_date or $end_pay_date)
						    {
						    	if($start_pay_date){$ba2y_elwa2t_start=@daysDifference($start_pay_date,$today);
						    	if($ba2y_elwa2t_start>0){$true_card="no_start";}}
						    	if($end_pay_date){ $ba2y_elwa2t_end=@daysDifference($end_pay_date,$today);
						    	if($ba2y_elwa2t_end<0){$true_card="no_end";}}
						    	
						    }//if($start_pay_date or $end_pay_date)
						    /////////////////////
	
}//while ($row_check_for_card = @mysql_fetch_array($result_check_for_card)) 

if($true_card=="yes")
{
	// step 1
	$disable_card="update cards set card_canuse='0', card_date_of_charging='$today', card_time_of_charging='$today_time', u_id='$db_id' where card_id='$card_id'";
	if(@mysql_query($disable_card,$conn_user))
	{
	// step 2
	$current_user_creadit;
	if(!$current_user_creadit){$current_user_creadit=0;}
	
	$final_add_creadit=$current_user_creadit+$card_price;
	$update_user_creadit="update users set creadit='$final_add_creadit' where u_id='$db_id'";
	@mysql_query($update_user_creadit,$conn_user);
	
	$insert_into_history2="insert into history (add_date,add_month,add_time,type1,type2,operation,u_id,u_name,u_uname,charge_salary,details,notes) values 
	('$today','$month_table_name','$today_time','microcharge','user','charge_card','$db_id','$db_u_name','$db_u_uname','$card_price','$card_id','$charge_card_no')";
	@mysql_query($insert_into_history2,$conn_user);
	
	// Done Successfully
	$status=1;if($login_lang=="ar"){$status_message=$charge_card_successfully_ar;}else{ $status_message=$charge_card_successfully_en;}
	$data_before = array("status" => "$status", "statusMessage" => "$status_message", "cardPrice" => "$card_price"
	, "beforeUserCredit" => "$current_user_creadit", "afterUserCredit" => "$final_add_creadit");                                                                    
	echo $data = json_encode($data_before);
			
	}//if(@mysql_query($disable_card))
	else{
		
		$status=0;if($login_lang=="ar"){$status_message=$charge_card_try_again_later_ar;}else{ $status_message=$charge_card_try_again_later_en;}
		$data_before = array("status" => "$status", "statusMessage" => "$status_message",);                                                                    
		echo $data = json_encode($data_before);
		
	}
	unset($true_card);
}//if(@mysql_num_rows($result_check_for_card)>0)
else {
	$status=0;if($login_lang=="ar"){$status_message=$charge_card_wrong_ar;}else{ $status_message=$charge_card_wrong_en;}
	$data_before = array("status" => "$status", "statusMessage" => "$status_message",);                                                                    
	echo $data = json_encode($data_before);


if(!$suspend){$suspend=0;}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($suspend<20)
{
	$suspend++;
	$insert_error_no="update users set suspend='$suspend' where u_id='$db_id'";
	@mysql_query($insert_error_no,$conn_user);
	
}//if($suspend<20)
else{
	
	// Step 1
	$suspent_user="update users set suspend='suspend' where u_id='$db_id'";
	@mysql_query($suspent_user,$conn_user);
    		
    $insert_into_history2="insert into history (add_date,add_month,add_time,type1,type2,operation,u_id,u_name,u_uname) values 
	('$today','$month_table_name','$today_time','all','auto','auto_suspend','$db_id','$db_u_name','$db_u_uname')";
	@mysql_query($insert_into_history2,$conn_user);
   
    // Step 2
// Get Mikrotik Info ///////////////////////////////////////////////
$select_mikrotik_info="select * from network where code='$network_code'";
$result_select_mikrotik_info=@mysql_query($select_mikrotik_info,$conn_user);	
while($row_result_select_mikrotik_info=mysql_fetch_array($result_select_mikrotik_info))
{
$network_name=$row_result_select_mikrotik_info['name'];	
$ip_lan=$row_result_select_mikrotik_info['lan_ip'];
$ip_wan=$row_result_select_mikrotik_info['wan_ip'];
$mikrotik_user=$row_result_select_mikrotik_info['mikrotik_user'];
$mikrotik_pass=$row_result_select_mikrotik_info['mikrotik_pass'];
// Prodband or hotspot
$hotspot_or_ppp=$row_result_select_mikrotik_info['hotspot_or_ppp'];
// Get Mikrotik user control Mode
$wan_or_lan=$row_result_select_mikrotik_info['wan_or_lan'];
}
if($wan_or_lan=="wan")
{$connection_type=$ip_wan;}
if($wan_or_lan=="lan")
{$connection_type=$ip_lan;}
$uname_charging=$db_u_uname;

	require('include/mikrotik/routeros_api.class.php');
	$API = new routeros_api();
	$API->debug = false;
	if($API->connect($connection_type, $mikrotik_user, $mikrotik_pass)) // áæ ÇáßæäßÔä äÌÍ
	{
		if($hotspot_or_ppp=="hotspot")
           {
           	$API->write('/ip/hotspot/user/set', false);
			$API->write('=.id='.$uname_charging, false);	
			$API->write('=disabled=yes');
			$API->read();
			////////////////////////////////////////////
			$ARRAY_ac = $API->comm("/ip/hotspot/active/print");
			$regtable_ac = $ARRAY_ac[$i];
			for ($i=0; $i<200; $i++)
			{
			$regtable_ac = $ARRAY_ac[$i];
			if($regtable_ac['.id'])
			{
				if($regtable_ac['user']==$uname_charging)
				{				
				$el_id=$regtable_ac['.id'];
				break;
				}//if($regtable['user']==$uname_charging)
			}//if($regtable['user'])
			else{break;}
			}//for ($i=0; $i<5000; $i++)
			$API->write('/ip/hotspot/active/remove',false);
      		$API->write('=.id=' .$el_id);
			$API->read();
           }//if($hotspot_or_ppp=="hotspot")
           
		if($hotspot_or_ppp=="ppp")
           {
           	$API->write('/ppp/secret/set', false);
			$API->write('=.id='.$uname_charging, false);	
			$API->write('=disabled=yes');
			$API->read();
			$API->write('/ppp/active/remove', false);
			$API->write('=.id='."$uname_charging");
			$API->read();
           }//if($hotspot_or_ppp=="ppp")
           
	}//if($API->connect($connection_type, $mikrotik_user, $mikrotik_pass)) // áæ ÇáßæäßÔä äÌÍ
	
	
	$status=0;if($login_lang=="ar"){$status_message=$charge_card_suspend_ar;}else{ $status_message=$charge_card_suspend_en;}
	$data_before = array("status" => "$status", "statusMessage" => "$status_message",);                                                                    
	echo $data = json_encode($data_before);
	
}// End Else if($suspend<20)

}// End else if(@mysql_num_rows($result_check_for_card)>0)
				
				
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