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
				$url = $local_url."?op=receiveMessagesNew";
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

				$search_answer="select * from orders where order_user_id=$db_id and `order_type`='user' and `order_answer`!='null' and `read`<>'yes' order by `order_id` DESC";
                $result_search_answer=@mysql_query($search_answer,$conn_user);
                
                if(@mysql_num_rows($result_search_answer)>0) 
                {
                	$json = array("status" => "1","statusMessage" => "success");
                //$json= array("counterAllMessages" => "$counterAllMessages","counterReadedMessages" => "$counterReadedMessages","counterNewMessages" => "$counterNewMessages");
                $counterAllMessages=0;
                $counterReadedMessages=0;
                $counterNewMessages=0;
				while ($row_search_answer=@mysql_fetch_array($result_search_answer)) {
					
				  $message_id=$row_search_answer['order_id'];
                  $order_details=$row_search_answer['order_details'];
                  $order_answer=$row_search_answer['order_answer'];
                  $order_send_date=$row_search_answer['order_send_date'];
                  $order_send_time=$row_search_answer['order_send_time'];
                  $order_read=$row_search_answer['read'];
                  
                  if($order_read!="yes"){$counterNewMessages++;}
				  if($order_read=="yes"){$counterReadedMessages++;}
				  $counterAllMessages++;
				  
				  $row_bot_messages['messageId']= $message_id;
				  $row_bot_messages['messageSubject']= $order_details;
				  $row_bot_messages['messageContent']= $order_answer;
				  $row_bot_messages['messageDate']= $order_send_date;
				  $row_bot_messages['messageTime']= $order_send_time;
				  $row_bot_messages['read']= $order_read;
				  
				  $json['messages'][]=$row_bot_messages;
					
				}//while ($row_search_answer=@mysql_fetch_array($result_search_answer)) 
				//$json= array("counterAllMessages" => "$counterAllMessages","counterReadedMessages" => "$counterReadedMessages","counterNewMessages" => "$counterNewMessages");
				
//				$json['counterAllMessages']="$counterAllMessages";
//				$json['counterReadedMessages']="$counterReadedMessages";
//				$json['counterNewMessages']="$counterNewMessages";

				echo json_encode($json);
				
                }//if(@mysql_num_rows($result_search_answer)>0)
				else{
					$status=0;if($login_lang=="ar"){$status_message=$receiveMessagesError_ar;}else{ $status_message=$receiveMessagesError_en;}
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