<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Hash;
use Session;
use Input;
use Validator;
use Auth;
use Redirect;
use DB;
use Schema;
use Mail;
use Carbon\Carbon;

class ReplicationController extends Controller
{
    public function replication(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		$ApiController = new App\Http\Controllers\ApiController();
		include '../config.php';
 
		$thisMinuteStart=$today." ".date("H:i:00");
		$thisMinuteEnd=$today." ".date("H:i:59");
		DB::table("orange.settings")->where('type', 'cron')->update([ 'value' => $todayDateTime]);
		
		///////////////////////////////////////////////////////////////////////////////////////////////
		///      				     Master Send to Slave 			   			///
		///////////////////////////////////////////////////////////////////////////////////////////////	
		// foreach( DB::table('orange.radacct')->Where('sync',0)->get() as $sessionData ){

		// }`
			$radacct = DB::table('demo.radacct')->limit(1000)->get();
			$endpoint = "http://microsystem-eg.com/hotspot/api.php";
			$restructureData = json_encode($radacct);
        	$restructureData = str_replace('"', "**", $restructureData);
			$postData = '{
				"table":"radacct",
				"data":"'.$restructureData.'"
			}';
			
			$context2 = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => $postData)));
			$response2 = file_get_contents($endpoint, FALSE, $context2);
			return $response2;
			
			
			/*
			$endpointParts = parse_url($endpoint);
			// $endpointParts['path'] = $endpointParts['path'] ?? '/';
			if(isset($specialPort)){$endpointParts['port'] = $specialPort;}
			// else{$endpointParts['port'] = $endpointParts['port'] ?? $endpointParts['scheme'] === 'https' ? 443 : 80;}
			$endpointParts['port'] = 80;
			
			$contentLength = strlen($postData);

			$request = "POST {$endpointParts['path']} HTTP/1.1\r\n";
			$request .= "Host: {$endpointParts['host']}\r\n";
			$request .= "User-Agent: Loglia Laravel Client v2.2.0\r\n";
			$request .= "Authorization: Bearer api_key\r\n";
			$request .= "Content-Length: {$contentLength}\r\n";
			$request .= "Content-Type: application/json\r\n\r\n";
			$request .= $postData;

			$prefix = substr($endpoint, 0, 8) === 'https://' ? 'tls://' : '';
			$socket = fsockopen($prefix.$endpointParts['host'], $endpointParts['port']);
			fwrite($socket, $request);
			fclose($socket);
			$response = "sent without waiting";
			return $response;
			*/
			/////////////////////////////Fire and Forget HTTP Request //////////////////////////
			
			// not matured session 
			// DB::table("orange.radacct")->where('radacctid', $failedSession->radacctid)->update([ 'acctstoptime' => $todayDateTime, 'internet_state' => 0, 'portal_stop_report' => 1, 'connectinfo_stop' => 'Auto kill not updated session from BNG',  'acctterminatecause' => 'Auto kill not updated session from BNG' ]);
		


		
		return "<center><strong>Done</strong></center>";
	}
	

}