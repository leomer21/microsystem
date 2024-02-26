<?php

	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);

	$login_web_site = $obj['web'];
	$login_user = $obj['username'];
	$login_password = $obj['password'];
	$login_lang = $obj['lang'];
	
	require_once 'include/config.php';
			
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=getPackagesInternetMonthly";
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
		}else {$can_go="yes";}
		
		if($can_go=="yes"){
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

					if($hotspot_or_ppp=="ppp")
	        		{$get_packages="select `id`,`network_code`,`name`,`type`,`price`,`period`,`speed_profile_id`
	        		,`limit_profile_id`,`offer`,`offer_start_date`,`offer_start_time`,`offer_end_date`,`offer_end_time` from packages where type='monthly2' and state='active' and network_code='$network_code' and hotspot_or_ppp='$hotspot_or_ppp' and limit_profile_id is null  ORDER BY id ASC";}
	        		else{$get_packages="select `id`,`network_code`,`name`,`type`,`price`,`period`,`speed_profile_id`
	        		,`limit_profile_id`,`offer`,`offer_start_date`,`offer_start_time`,`offer_end_date`,`offer_end_time` from `packages` where  type='monthly2' and `state`='active' and `network_code`='$network_code' and hotspot_or_ppp='$hotspot_or_ppp'  ORDER BY id ASC";}

	        		$result_get_all_network_active=@mysql_query($get_packages);
			        if(@mysql_num_rows($result_get_all_network_active)>0) 
			        	{
			        		 $json = array("status" => "1","statusMessage" => "success");
			        	 	
			        		  while ($row_get_all_network_active=@mysql_fetch_assoc($result_get_all_network_active)) {
			        		  	
			        		$limit_profile_id=$row_get_all_network_active['limit_profile_id'];
			        		$speed_profile_id=$row_get_all_network_active['speed_profile_id'];
			        		
			        		///// speed_profile_id 
                            $select_speed_profile_id="select * from speed where id='$speed_profile_id'";
                            $result_select_speed_profile_id=@mysql_query($select_speed_profile_id);
                            $row_result_select_speed_profile_id=@mysql_fetch_array($result_select_speed_profile_id);
                            $speed_name=$row_result_select_speed_profile_id['speed_name'];
                            
                            $row_get_all_network_active['speed'].= $speed_name;
                            
                            if($limit_profile_id){
	                			///////////// limit profile data
	                			
	                            $select_limit_profile_data_for_view="SELECT * FROM `limit` WHERE `id`='$limit_profile_id'";
	                            $result_select_limit_profile_data_for_view=@mysql_query($select_limit_profile_data_for_view);
	                            $row_result_select_limit_profile_data_for_view=@mysql_fetch_array($result_select_limit_profile_data_for_view);
	                            
	                            $limit_name=$row_result_select_limit_profile_data_for_view['limit_name'];
	                            $start_speed_id=$row_result_select_limit_profile_data_for_view['start_speed_id'];
	                            $end_speed_id=$row_result_select_limit_profile_data_for_view['end_speed_id'];
	                            $bandwidth_upload_for_limit=$row_result_select_limit_profile_data_for_view['bandwidth_upload'];
	                            $bandwidth_download_for_limit=$row_result_select_limit_profile_data_for_view['bandwidth_download'];
	                            $bandwidth_total_for_limit=$row_result_select_limit_profile_data_for_view['bandwidth_total'];
	                            $stop=$row_result_select_limit_profile_data_for_view['stop'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				                            
																if($bandwidth_upload_for_limit>=0 and $bandwidth_upload_for_limit<1024)
																{$bandwidth_upload_for_limit=$bandwidth_upload_for_limit." Bytes";}
													   			if($bandwidth_upload_for_limit>=1024 and $bandwidth_upload_for_limit<1048576)
																{$bandwidth_upload_for_limit=round($bandwidth_upload_for_limit/1024,1)." KB";}
													   			if($bandwidth_upload_for_limit>=1048576 and $bandwidth_upload_for_limit<1073741824)
																{$bandwidth_upload_for_limit=round($bandwidth_upload_for_limit/1048576,1)." MB";}	
																if($bandwidth_upload_for_limit>=1073741824)
																{$bandwidth_upload_for_limit=round($bandwidth_upload_for_limit/1073741824,1)." GB";}
																if($bandwidth_upload_for_limit<=0){$bandwidth_upload_for_limit="";}
																
         														if($bandwidth_download_for_limit>=0 and $bandwidth_download_for_limit<1024)
																{$bandwidth_download_for_limit=$bandwidth_download_for_limit." Bytes";}
													   			if($bandwidth_download_for_limit>=1024 and $bandwidth_download_for_limit<1048576)
																{$bandwidth_download_for_limit=round($bandwidth_download_for_limit/1024,1)." KB";}
													   			if($bandwidth_download_for_limit>=1048576 and $bandwidth_download_for_limit<1073741824)
																{$bandwidth_download_for_limit=round($bandwidth_download_for_limit/1048576,1)." MB";}	
																if($bandwidth_download_for_limit>=1073741824)
																{$bandwidth_download_for_limit=round($bandwidth_download_for_limit/1073741824,1)." GB";}
																if($bandwidth_download_for_limit<=0){$bandwidth_download_for_limit="";}
																
         														if($bandwidth_total_for_limit>=0 and $bandwidth_total_for_limit<1024)
																{$bandwidth_total_for_limit=$bandwidth_total_for_limit." Bytes";}
													   			if($bandwidth_total_for_limit>=1024 and $bandwidth_total_for_limit<1048576)
																{$bandwidth_total_for_limit=round($bandwidth_total_for_limit/1024,1)." KB";}
													   			if($bandwidth_total_for_limit>=1048576 and $bandwidth_total_for_limit<1073741824)
																{$bandwidth_total_for_limit=round($bandwidth_total_for_limit/1048576,1)." MB";}	
																if($bandwidth_total_for_limit>=1073741824)
																{$bandwidth_total_for_limit=round($bandwidth_total_for_limit/1073741824,1)." GB";}
																if($bandwidth_total_for_limit<=0){$bandwidth_total_for_limit="";}
																
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	                            
		                            ///// start speed_profile_id 
		                            $select_speed_profile_id="select * from speed where id='$start_speed_id'";
		                            $result_select_speed_profile_id=@mysql_query($select_speed_profile_id);
		                            $row_result_select_speed_profile_id=@mysql_fetch_array($result_select_speed_profile_id);
		                            $start_speed_name=$row_result_select_speed_profile_id['speed_name'];
	                            
	                            	///// end speed_profile_id 
		                            $select_speed_profile_id="select * from speed where id='$end_speed_id'";
		                            $result_select_speed_profile_id=@mysql_query($select_speed_profile_id);
		                            $row_result_select_speed_profile_id=@mysql_fetch_array($result_select_speed_profile_id);
		                            $end_speed_name=$row_result_select_speed_profile_id['speed_name'];
		                            
	                            	if($stop=="on"){if($login_lang=="ar"){ $stopMessage=$shutdown_internet_after_limit_exceed_ar.$end_speed_name;}else {$stopMessage=$shutdown_internet_after_limit_exceed_en.$end_speed_name;}}
	                            	else {if($login_lang=="ar"){ $stopMessage=$end_speed_after_limit_exceed_ar.$end_speed_name;}else {$stopMessage=$end_speed_after_limit_exceed_en.$end_speed_name;};}
	                            
		                            $row_get_all_network_active['limit_name'].= $limit_name;
		                            $row_get_all_network_active['limit_start_speed'].= $start_speed_name;
		                            $row_get_all_network_active['limit_end_speed'].= $end_speed_name;
		                            $row_get_all_network_active['limit_bandwidth_upload'].= $bandwidth_upload_for_limit;
		                            $row_get_all_network_active['limit_bandwidth_download'].= $bandwidth_download_for_limit;
		                            $row_get_all_network_active['limit_bandwidth_total'].= $bandwidth_total_for_limit;
		                            $row_get_all_network_active['limit_stop'].= $stop;
		                            $row_get_all_network_active['limit_stop_message'].= $stopMessage;
		                            
                            }//if($limit_profile_id)            
        		
                            // update 19-12-2014
                            unset($row_get_all_network_active['limit_profile_id']);
                            unset($row_get_all_network_active['speed_profile_id']);
                            unset($row_get_all_network_active['network_code']);
			        		  if($row_get_all_network_active['type']=="monthly"){if($login_lang=="ar"){$row_get_all_network_active['type']=$backageNameMonthly_ar;} if($login_lang=="en"){$row_get_all_network_active['type']=$backageNameMonthly_en;}}
			        		  if($row_get_all_network_active['type']=="monthly2"){if($login_lang=="ar"){$row_get_all_network_active['type']=$backageNameMonthly2_ar;} if($login_lang=="en"){$row_get_all_network_active['type']=$backageNameMonthly2_en;}}
			        		  if($row_get_all_network_active['type']=="period"){if($login_lang=="ar"){$row_get_all_network_active['type']=$backageNamePeriod_ar;} if($login_lang=="en"){$row_get_all_network_active['type']=$backageNamePeriod_en;}}
			        		  if($row_get_all_network_active['type']=="bandwidth_card"){if($login_lang=="ar"){$row_get_all_network_active['type']=$backageNameBandwidth_ar;} if($login_lang=="en"){$row_get_all_network_active['type']=$backageNameBandwidth_en;}}
			        		  if($row_get_all_network_active['type']=="sms"){if($login_lang=="ar"){$row_get_all_network_active['type']=$backageNameSMS_ar;} if($login_lang=="en"){$row_get_all_network_active['type']=$backageNameSMS_en;}}
			        		  
			        		  if($row_get_all_network_active['type']=="offer"){if($login_lang=="ar"){$row_get_all_network_active['type']=$backageNameOffer_ar;} if($login_lang=="en"){$row_get_all_network_active['type']=$backageNameOffer_en;}}
			        		  ////////////////////////////////////////////////////// Offer Checker //////////////////////////////////////////////////////  	
			        		////////////////////////////////////////////////////// Offer Checker //////////////////////////////////////////////////////
			        		////////////////////////////////////////////////////// Offer Checker //////////////////////////////////////////////////////
			        		$currentOffer_status=$row_get_all_network_active['offer'];
                            $currentOffer_end_date=$row_get_all_network_active['offer_end_date'];
                            $currentOffer_end_time=$row_get_all_network_active['offer_end_time'];
                            if($currentOffer_status=="yes"){
			        		  	//////////////////////////////////////////////////////////////////////////////////////
								$nowdate_charging_for_reset_counter_QAQ = strtotime("$currentOffer_end_date");
								$thendate_charging_for_reset_counter_QAQ = strtotime("$today");
								$datediff_charging_for_reset_counter_QAQ = ($nowdate_charging_for_reset_counter_QAQ - $thendate_charging_for_reset_counter_QAQ);                 // áØÑÍ ÊÇÑíÎ ÞÏíã ãä ÊÇÑíÎ ÌÏíÏ
								$final__for_reset_counter_QAQ=round($datediff_charging_for_reset_counter_QAQ/86400);
								//////////////////////////////////////////////////////////////////////////////////////
								if($final__for_reset_counter_QAQ>=0)// íÚäì áÓå ÇáÚÑÖ ãäÊåÇÔ
								{ 
								    function date_getFullTimeDifference( $start, $end )
									{
									$uts['start']      =    strtotime( $start );
									        $uts['end']        =    strtotime( $end );
									        if( $uts['start']!==-1 && $uts['end']!==-1 )
									        {
									            if( $uts['end'] >= $uts['start'] )
									            {
									                $diff    =    $uts['end'] - $uts['start'];
									                if( $years=intval((floor($diff/31104000))) )
									                    $diff = $diff % 31104000;
									                if( $months=intval((floor($diff/2592000))) )
									                    $diff = $diff % 2592000;
									                if( $days=intval((floor($diff/86400))) )
									                    $diff = $diff % 86400;
									                if( $hours=intval((floor($diff/3600))) )
									                    $diff = $diff % 3600;
									                if( $minutes=intval((floor($diff/60))) )
									                    $diff = $diff % 60;
									                $diff    =    intval( $diff );
									                return( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
									            }}}
									$currentTime= date("H:i:s");
									$timeDiff=date_getFullTimeDifference( $currentTime, $currentOffer_end_time );
									if($timeDiff){$canDoIt="yes";}else{}// íÈÞì ÇáæÞÊ ÎáÕ
									
								}// Offer Valid
								
                            }//if($currentOffer_status=="yes")
                            else{$canDoIt="yes";}// Not Offer
							////////////////////////////////////////////////////// Offer Checker //////////////////////////////////////////////////////  	
			        		////////////////////////////////////////////////////// Offer Checker //////////////////////////////////////////////////////
			        		////////////////////////////////////////////////////// Offer Checker //////////////////////////////////////////////////////
			        			
								if($canDoIt=="yes")
								{
                            $json['packages'][]=$row_get_all_network_active;
                            unset($canDoIt);
								}
							  }
				        	  
			        	 	echo json_encode($json);
			        	 	
			       	    }//if(@mysql_num_rows($result_get_all_network_active)>0) 
			        	else {
			        		$status=0;
							if($login_lang=="ar"){ $status_message=$not_found_networks_ar;}else {$status_message=$not_found_networks_en;}
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

		unset($can_go);
		}//if($can_go=="yes")
		     
	 
	
@mysql_close($conn_user);

                    
?>