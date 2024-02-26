<?php 
$sys_dbhost = 'localhost';
		$conn_user = @mysql_connect("localhost", "", "", true);
		@mysql_select_db("microsys_3m_v5",$conn_user);
		mysql_set_charset('utf8');  
		//@mysql_query("SET NAMES cp1256",$conn_user);
		//@mysql_query("set characer set cp1256",$conn_user);
		///////////////////////////////////////////////////        
		//			 Connect to user database 
		///////////////////////////////////////////////////
//date_default_timezone_set("Africa/Cairo");
//	$today = date("Y-m-d");
//    $today_time = date("g:i a");
echo $today."<br>".$today_time;
		$get_user_data="select * from users_test";
		$r_get_user_data=@mysql_query($get_user_data);
		if(@mysql_num_rows($r_get_user_data)>0)
		{
			$row_get_user_data=@mysql_fetch_array($r_get_user_data);
			$db_id=$row_get_user_data['id'];
			$db_u_name=$row_get_user_data['u_name'];
			$db_u_uname=$row_get_user_data['u_uname'];
			$db_u_password=$row_get_user_data['u_password'];
			$db_creadit=$row_get_user_data['creadit'];
			$db_network_code=$row_get_user_data['network_code'];
			$db_hotspot_or_ppp=$row_get_user_data['hotspot_or_ppp'];
			
			//$db_u_name="arabic";
			$status=1;$status_message="successfully";
			//echo $final_name=utf8_encode($db_u_name);
			$data_before = array("status" => "$status", "name" => "$db_u_name", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);
		}//if(@mysql_num_rows($r_get_user_data)>0)
		else{
			$status=0;$status_message="login failed";
			$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
			echo $data = json_encode($data_before);
		}
?>