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
use Carbon\Carbon;

class UnifiController extends Controller
{

    public function unifi(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		// $unifiClass = new App\Http\Controllers\Integrations\Unifi();

		include '../config.php';
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
		// DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body]]);
		$body = json_decode($body);
		// return "stop here";
		
		// start testing 

		/**
		 * Controller configuration
		 * ===============================
		 * Copy this file to your working directory, rename it to config.php and update the section below with your UniFi
		 * controller details and credentials
		 */
		$controlleruser     = 'admin'; // the user name for access to the UniFi Controller
		$controllerpassword = '1403636mra'; // the password for access to the UniFi Controller
		$controllerurl      = 'https://unifi.microsystem.com.eg:8443'; // full url to the UniFi Controller, eg. 'https://22.22.11.11:8443', for UniFi OS-based
								// controllers a port suffix isn't required, no trailing slashes should be added
		$controllerversion  = '6.0.43'; // the version of the Controller software, e.g. '4.6.6' (must be at least 4.0.0)

		/**
		 * set to true (without quotes) to enable debug output to the browser and the PHP error log
		 */
		$debug = false;
		/**
		 * the short name of the site which you wish to query
		 */
		$site_id = 'rfdelxbt';//7stars: 3pyre32p
		// require_once('vendor/autoload.php');
		
		/**
		 * initialize the UniFi API connection class and log in to the controller and pull the requested data
		 */
		$unifi_connection = new App\Http\Controllers\Integrations\Unifi($controlleruser, $controllerpassword, $controllerurl, $site_id, $controllerversion);
		$set_debug_mode   = $unifi_connection->set_debug($debug);
		$loginresults     = $unifi_connection->login();
		
		// $ubnController->authorize_guest($id,$minutes, $upload, $download, $quotaMB);
		$userLoginState = $unifi_connection->authorize_guest('84:41:67:C1:63:95','0', '1024', '15360', '15'); //48:51:B7:DE:BC:64
		print_r($userLoginState);
		return "user loggin state";
		$clients_array    = $unifi_connection->list_clients();
		// return $clients_array;
		/*
		/////////////////////////////////////
		// Access Points data
		$accessPoints = array();
		foreach( $clients_array as $ap ){
			$accessPoints[]=array('id' => $ap->_id, 'ip' => $ap->ip, 'model' => $ap->model, 'name' => $ap->name, 'mac' => $ap->mac, 'state' => $ap->state, 'last_seen' => date('Y-m-d H:i:s', $ap->last_seen), 'uptime' => sprintf('%02d:%02d:%02d', floor($ap->uptime / 3600), floor($ap->uptime / 60 % 60), floor($ap->uptime % 60)), 'download_gb' => round($ap->tx_bytes/1024/1024/1024,1), 'upload_gb' => round($ap->rx_bytes/1024/1024/1024,1), 'total_gb' => round($ap->bytes/1024/1024/1024,1));
			// return "name: ".$ap->name." \n Mac: ".$ap->mac;
		}
		return $accessPoints;
		*/
		//////////////////////////////////////
		// Clients Data
		$clients = array();
		foreach( $clients_array as $client ){
			isset($client->hostname) ? $name = $client->hostname : $name = $client->mac;
			isset($client->ip) ? $ip = $client->ip : $ip = "";
			$client->_uptime_by_uap != $client->uptime ? $moving = 'moving' : $moving = 0;
			$clients[]=array('id' => $client->_id, 'ip' => $ip, 'name' => $name, 'mac' => $client->mac, 'assoc_time' => date('Y-m-d H:i:s', $client->assoc_time), 'latest_assoc_time' => date('Y-m-d H:i:s', $client->latest_assoc_time), 'first_seen' => date('Y-m-d H:i:s', $client->first_seen), 'last_seen' => date('Y-m-d H:i:s', $client->last_seen), 'signal' => $client->signal, 'uptime_by_uap' => sprintf('%02d:%02d:%02d', floor($client->_uptime_by_uap / 3600), floor($client->_uptime_by_uap / 60 % 60), floor($client->_uptime_by_uap % 60)), 'ap_mac' => $client->ap_mac , 'essid' => $client->essid, 'satisfaction' => $client->satisfaction ,'total_uptime' => sprintf('%02d:%02d:%02d', floor($client->uptime / 3600), floor($client->uptime / 60 % 60), floor($client->uptime % 60)), 'download_gb' => round($client->tx_bytes/1024/1024/1024,1), 'upload_gb' => round($client->rx_bytes/1024/1024/1024,1), 'total_gb' => round(($client->tx_bytes+$client->rx_bytes)/1024/1024/1024,1), 'move' => $moving);
			// return "name: ".$client->name." \n Mac: ".$client->mac;
		}
		return $clients;
		/////////////////////////////////////

		/**
		 * output the results in JSON format
		 */
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($clients_array);

		////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////


	}
}