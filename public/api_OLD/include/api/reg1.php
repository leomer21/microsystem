<?php 

	
	$body = @file_get_contents('php://input');
	$obj = json_decode($body, true);
	$reg1_web_site = $obj['web'];
	$login_lang = $obj['lang'];
	
	require_once 'include/config.php';
		
		if($sys_local_or_web=="local")
		{
			if($me_local_or_web=="local"){$can_go="yes";}
			else{
				$url = $local_url."?op=reg1";
				//$data='{"web":"http://www.microsystem-eg.com","lang":"ar"}';
				// send json
				$data_before = array("web" => "$reg1_web_site", "lang" => "$login_lang");                                                                    
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
		$get_all_network_active="select `name`,`code` from network where state='active'";
        $result_get_all_network_active=@mysql_query($get_all_network_active);
        if(@mysql_num_rows($result_get_all_network_active)>0) 
        	{
        		 $json = array("status" => "1","statusMessage" => "success");
        	 	
        		  while ($row_get_all_network_active=@mysql_fetch_assoc($result_get_all_network_active)) {
	        	    $json['network'][]=$row_get_all_network_active;
	        	  	$show_network_name=$row_get_all_network_active['name'];
	        	    $show_network_code=$row_get_all_network_active['code'];
				    //$json []= array ('name' => $show_network_name,'code' => $show_network_code);
				  }
	        	  
        	 //echo json_encode($json);
        	 
        	
        	 	echo json_encode($json);
        	 	
        	 	
//        	 	$status=1;
//				$data_before = array("status" => "$status");                                                                    
//				echo $data = json_encode($data_before);
        	}//if(@mysql_num_rows($result_get_all_network_active)>0) 
        	else {
        		$status=0;
				if($login_lang=="ar"){ $status_message=$not_found_networks_ar;}
				else { $status_message=$not_found_networks_en;}
				
				$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
				echo $data = json_encode($data_before);
        		}
     unset($can_go);    
	 }
	
	
	
	
@mysql_close($conn_user);

?>