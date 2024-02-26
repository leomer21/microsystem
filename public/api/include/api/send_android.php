<?php 
$google_api_key;// get from web db
$debug=0;// 0 disabled || 1 Enabled

include 'gcm/function.php';
	
	 	$gcmRegID    = $_GET["regId"]; // GCM Registration ID got from device
	    $pushMessage = $_GET["message"];
	         
        $registatoin_ids = array($gcmRegID);
        $message = array("price" => $pushMessage);
     
        $result = send_push_notification($registatoin_ids, $message);
     
        echo $result;
    
?>