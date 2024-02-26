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
use Locale;
use Mail;

class TestController extends Controller
{
    public function testController(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		//$datetime = new DateTime('now', new DateTimeZone('Africa/Cairo'));
		//$datetime->setTimezone(new DateTimeZone('GMT+3'));

		require_once '../config.php';


		$customerEmailArray = array('a.mansour@microsystem.com.eg');
		
		// sending email
		$content = "Dear testers<br><br>
		Thanks,<br>
		Best Regards.<br>";
		$from = "support@microsystem.com.eg";
		$subject = "Microsystem | Test";
		$customerName = "Microsystem TEST";

		Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
			$message->from($from, $customerName);
			$message->to($customerEmailArray, $customerName)->subject($subject);
		});
		
		//////////////////////////////////////////////////////////////////////////
		return $todayDateTime = $today." ".date("H:i:s");
		$number = 8533;
		$string = strval($number);
		$encoded = base64_encode(base64_encode($number));

		return $encoded; // Output: NTU=

		$decoded = base64_decode(base64_decode($encoded));
		return $decoded;
		// create whatsapp server

		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
		return $whatsappClass->sendWhatsappMenu('domina', '3', '201061030454', $todayDateTime);
		////////
		$standaloneMsg = "test test";
		$standaloneMsg = urlencode($standaloneMsg);
		
		return $whatsappClass->send( "", "201061030454" , $standaloneMsg, "3", "domina","","","","1");

		//PHP intl country code 2 chars to country name 
		
		// return var_dump(Locale::getDisplayRegion('-SA'));
		return $locale_cc = Locale::getDisplayRegion('-' . "SA");
		
		// offecial whatsapp test session messsage
		$message = "hi";
		$to = "201061030454";
		$messageInArray = ['body' => urlencode($message)];
		$data = ['messaging_product' => 'whatsapp', 'recipient_type' => 'individual', 'to' => $to, 'type' => 'text', 'text' => $messageInArray];
		$msg = json_encode($data); // Encode data to JSON
		$msg = '{
			"messaging_product": "whatsapp",
			"recipient_type": "individual",
			"to": "201061030454",
			"type": "text",
			"text": { 
				"body": "hello, world!"
			}
		}';
		$arrContextOptions=array('http' => array( 'method' => 'POST', 'header' => "Authorization: Bearer EAAOvXEZCD1u0BANgPZCjFVQkoMBuK1m0NpZB0r85VCxGQRr1GGxU2efbwAGYYsNUKaG7C76umlODgiAyPNj5mVQ6agvmbz9xtYhp7RGZCiug9mIRzZBZBZAGhm6kMSZCzkWXWWIQc6u9tCgUW3spmn5ZCqizlWHR22oCKTX3OpZBedJ55NuG9tIUADLkiFHOrKM7kY1RVNZCFMdHkLdtEqbekXZCTzMC3UUx3q8ZD\r\nContent-Type: application/json\r\n", 'content' => "$msg")); 
		$url = "https://graph.facebook.com/v14.0/105380682223464/messages";
		$response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
		return $response = json_decode($response);
		////////////////////////////////////////////////////
		
		// online 
		// return $TodayDownload=App\Radacct::where('u_id','869')->where('dates',$today)->sum('acctoutputoctets')+0;
		// return $TodayDownload=App\Models\RadacctTodayConsumptionActiveUsers::where('u_id','869')->value('TodayDownload')+0;
		
		// $TodayUpload=App\Models\RadacctActiveUsers::where('u_id','869')->value('TodayUpload')+0;
		// $TodayDownload=App\Models\RadacctActiveUsers::where('u_id','869')->value('TodayDownload')+0;

		$TodayUpload=App\Radacct::where('u_id','869')->where('dates',$today)->sum('acctinputoctets')+0;
		$TodayDownload=App\Radacct::where('u_id','869')->where('dates',$today)->sum('acctoutputoctets')+0;

		return $usedQuota = round((($TodayUpload + $TodayDownload)/1024)/1024,1);
		
		///////////////////////////////////////////////////////
		// PMS integration 
		$now = time(); // or your date as well
		$your_date = strtotime("2022-06-09");
		$datediff = $now - $your_date;

		return round($datediff / (60 * 60 * 24))-1; // no of stay nights
		
		///////////////////
		$myStr = "G#722725";
		// singlebyte strings
		$result = substr($myStr, 0, 2);
		// multibyte strings
		return $result = mb_substr($myStr, 0, 2);
		//////////////////
		$gBirthday = "15111989";
		$gBirthday_ROW = $gBirthday;
		if(@date_format(date_create($gBirthday),"Y-m-d")){
			$gBirthday = date_format(date_create($gBirthday),"Y-m-d");
		}elseif(strlen($gBirthday) == "8"){
			$gBirthday = $gBirthday[4].$gBirthday[5].$gBirthday[6].$gBirthday[7]."-".$gBirthday[2].$gBirthday[3]."-".$gBirthday[0].$gBirthday[1];
		}else{
			$gBirthday = $gBirthday_ROW;
		}
		return $gBirthday;

		$date=date_create("05032013");
		return date_format($date,"Y/m/d");
		/////////////////////////////////////////////////////////////
		// user profile performance

		// get just only online users after visit frequency filter
		$database = "demo";
		return $data = DB::table($database.'.users_radacct')
				// ->select(DB::raw('count(u_id) as counts, `u_id`, `u_name`, `u_uname`, `u_email`, `u_phone`, `suspend`, `Selfrules`, `created_at`'))->whereBetween('dates',['2022-05-01','2022-05-30'])
				
				// ->having('counts', '>=', 2)
				// ->select(DB::raw('count(u_id) as counts, `u_id`, `u_name`, `u_uname`, `u_email`, `u_phone`, `suspend`, `Selfrules`, `created_at`'))
				// ->orderBy('counts', 'DESC')
				->where(DB::raw(' EXISTS ( SELECT radacctid FROM '.$database.'.radacct WHERE radacct.u_id = users_radacct.u_id AND radacct.acctstoptime IS NULL ) group by users_radacct.u_id;'))
				// ->groupBy('users_radacct.u_id') // moved to the end of IF CONDITIONS
               	->get();

		// get just only online users 
		$database = "demo";
		return $data = DB::table($database.'.users')
				->select('u_id', 'u_name', 'u_uname', 'u_email', 'u_phone', 'suspend', 'Selfrules', 'created_at')
				->where(DB::raw(' EXISTS ( SELECT radacctid FROM '.$database.'.radacct WHERE radacct.u_id = users.u_id AND radacct.acctstoptime IS NULL );'))
               	->get();

		$database =  app('App\Http\Controllers\Controller')->configuration();
		return $data = App\Models\UsersJoinRadacctSearch::
				// ->select('u_id', 'u_name', 'u_uname', 'u_email', 'u_phone', 'suspend', 'Selfrules', 'created_at')
				with('radacct.acctstoptime')
                ->whereNull('radacct.acctstoptime')->get();

		return $data = DB::table($database.'.users')
				->select('users.u_id', 'users.u_name', 'users.u_uname', 'users.u_email', 'users.u_phone', 'users.suspend', 'users.Selfrules', 'users.created_at')
				->join($database.'.radacct', $database.'.radacct.u_id', '=', $database.'.users.u_id')
                ->whereNull($database.'.radacct.acctstoptime')->get();

		return $visits = App\Models\UsersRadacct::where('u_id', '28844')->select(DB::raw('count(u_id) as visits'))->value('visits');

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// users page performance
		$database = "demo";
		$data = DB::table($database.'.users_radacct');
		$data->select(DB::raw('* ,count(u_id) as counts'))
                ->groupBy('u_id')
                // ->having('counts', '>=', '20')
				// ->orderBy(DB::raw("`acctinputoctets` + `acctoutputoctets`"), 'desc');
				// ->orderByRaw('SUM(acctinputoctets+acctoutputoctets) DESC');
				->orderBy('counts', 'DESC');
		return $data = $data->limit(10)->get();
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// testing dashboard performance

		// Rush hour
		$statisticsStartDate = "2022-05-19 00:00:00";
		$statisticsEndDate = "2022-05-19 23:59:59";
		return $rushHour = App\Radacct::whereBetween('acctstarttime',[ $statisticsStartDate,$statisticsEndDate])->select(DB::raw(' hour( acctstarttime ) as hour, DATE_FORMAT(`acctstarttime`,"%Y-%m-%d %H:00:00") as date, count(*) as value '))->groupBy(DB::raw('hour(acctstarttime)'))->get();
		// App\Radacct::whereBetween('acctstarttime',[ $statisticsStartDate,$statisticsEndDate])->select(DB::raw(' hour( acctstarttime ) as hour, count(*) as sessions_per_hour '))->groupBy(DB::raw('hour(acctstarttime)'))->get();
		
		////////////////////////////////////////
		// online users in branch
		// $allDayData=App\Models\RadacctBranchDays::where('branch_id',$branch->id)->where('month', $currMonth->month)->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
		$allDayData=App\Models\RadacctBranchUsers::where('branch_id',"1")->where('month', '2022-04')->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
		if(isset($finalValue)){unset($finalValue);}
		foreach($allDayData as $record){
			$finalValue[$record->day]=$record->online_days;
		}

		for($i=1;$i<=31;$i++)
		{
			echo "'";
			if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
			
			if(isset($finalValue[$number])){
				echo $finalValue[$number];
			}else{echo "0";}
			echo "'";
			if($i!="31"){echo ",";}
		}
		return "<br>DONE";
		/////////////////////////////////////////
		// daily new registration
		// $firstDayThisMonth=$currMonth->month."-01 00:00:00";
		$firstDayThisMonth="2022-05-01 00:00:00";
		$lastDayThisMonth=date('Y-m-t', strtotime($firstDayThisMonth))." 23:59:59";
		$allDayData=App\Users::whereBetween('created_at', [$firstDayThisMonth, $lastDayThisMonth] )->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as new_users_per_day'))->groupBy('date')->get();
		
		if(isset($finalValue)){unset($finalValue);}
		foreach($allDayData as $record){
			$dateExplode = explode("-", $record->date);
			$finalValue[ $dateExplode[2] ] = $record->new_users_per_day;
		}

		for($i=1;$i<=31;$i++)
		{
			echo "'";
			if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
			
			if(isset($finalValue[$number])){
				echo $finalValue[$number];
			}else{echo "0";}
			echo "'";
			if($i!="31"){echo ",";}
		}

		return "<br>DONE";
		/////////////////////// concurrent devices
		/*
		// return $allDayData=App\History::where('network_id',$network->id)->where('operation', 'concurrent')->whereBetween('add_date', [$firstDayThisMonth, $lastDayThisMonth] )->get();
		return $allDayData=App\History::where('network_id','2')->where('operation', 'concurrent')->whereBetween('add_date', ['2022-01-01', '2022-01-31'] )->get();

		if(isset($finalValue)){unset($finalValue);}
		foreach($allDayData as $record){
			$dateExplode = explode("-", $record->add_date);
			$finalValue[ $dateExplode[2] ] = $record->details;
		}

		for($i=1;$i<=31;$i++)
		{
			echo "'";
			if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
			
			if(isset($finalValue[$number])){
				echo $finalValue[$number];
			}else{echo "0";}
			echo "'";
			if($i!="31"){echo ",";}
		}
		return "<br>DONE";
		*/
		/////////////////////// online users
		$allDayData=App\Models\RadacctNetworkUsers::where('network_id','2')->where('month', '2021-01')->select(DB::raw(' `day`, COUNT(`day`) as online_days'))->groupBy('day')->get();
		if(isset($finalValue)){unset($finalValue);}
		foreach($allDayData as $record){
			$finalValue[$record->day]=$record->online_days;
		}

		for($i=1;$i<=31;$i++)
		{
			echo "'";
			if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
			
			if(isset($finalValue[$number])){
				echo $finalValue[$number];
			}else{echo "0";}
			echo "'";
			if($i!="31"){echo ",";}
		}
		
		return "<br>DONE";

		if (in_array("Glenn", $people))
		{
			echo "Match found";
		}
		else
		{
			echo "Match not found";
		}
		return $allDayData[0];
		return $allDayData->day['02'];

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		return "start";
		// testing orangeWi-Fi verfivation SMS through damanhour railway
		// $api = "https://wifi.orange.eg/hotspotsadmin/pages/SMSHandler.ashx?MobileNo=01277418871&code=TestingCode123&username=Wifi&password=$600@WydMh";
		// $data = ['orangeSMS' => $api];
		// $msg = json_encode($data); // Encode data to JSON
		// $url = 'http://41.196.2.234:5050/sms.php';
		// $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
		// $response = @file_get_contents($url, FALSE, $context);

		$api = "http://41.196.2.234:5050/sms.php";
		$response = file($api, FALSE);

		print_r($response);
		return "END";

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		// testing send IP and MAC to Radiusd.php to get error response
		$subdomain = url()->full();
        $split = explode('/', $subdomain);
		$user = "24:62:AB:00:83:CC"; //  %{User-Name}
		$password = "24:62:AB:00:83:CC"; //  %{User-Password}
		$ip = '10.5.60.111'; //  %{Client-IP-Address}
		$mac = "24:62:AB:00:83:CC"; //  %{Calling-Station-Id}
		$systemID='demo'; // %{NAS-Identifier} getted from (system->Identify) #NOTE VERY IMPORTANT IF WE HOST IN ETISALAT IN ONE MIKROTIK REBLASE $systemID TO $hotspotName AND CREATE MICROSYSTEM SCRIPT FOR EVERY CUSTOMER AND DISABLE BRANCH FEATURES FROM GUI AND MAKE AUTO LOGIN CONTROL FROM THIS PAGE
		$hotspotName='hsprof1'; //%{Called-Station-Id} getted from  (ip->hotspot->server profile->"profile name ex.hsprof1")
		$location_ID='16'; //%{WISPr-Location-ID} getted from  (ip->hotspot->server profile->"open Profile then GOTO RADIUS Tab")
		$location_Name='smartVillage'; //%{WISPr-Location-Name} getted from  (ip->hotspot->server profile->"open Profile then GOTO RADIUS Tab")
		$Acct_Session_Id='1122334455'; // %{Acct-Session-Id}
		$NAS_IP_Address='10.5.60.1';     //NAS-IP-Address
		$radiusResponse = @file_get_contents("http://".$split[2]."/api/radius/radius.php?user=$user&password=$password&ip=$ip&mac=$mac&systemID=$systemID&hotspotName=$hotspotName&location_ID=$location_ID&location_Name=$location_Name&Acct_Session_Id=$Acct_Session_Id&NAS_IP_Address=$NAS_IP_Address");
		// $radiusResponse = @shell_exec('/usr/local/bin/php -f /home/hotspot/radius.php 24:62:AB:00:83:CC 24:62:AB:00:83:CC 10.5.50.55 24:62:AB:00:83:CC demo hsprof1 16 SmartVillageBranch 1122334455 10.5.50.1');
		// return $radiusResponse;
		if(isset($radiusResponse) and $radiusResponse!=""){
			if($radiusResponse=="Accept"){
				$radiusResponseMessageToUser = "You are connected";
			}elseif($radiusResponse=="Reject1"){
				$radiusResponseMessageToUser = "Oops, your quota or session time has been finished for today, and it will be renewed tomorrow automatically.";	
			}elseif($radiusResponse=="Reject3"){
				$radiusResponseMessageToUser = "Oops, not found your Username or device ID into our database, it should be registerd soon automatically after this manual login.";
			}elseif($radiusResponse=="Reject4"){
				$radiusResponseMessageToUser = "Oops, Microsystem subscription has been expired, so the network has been disabled, Please contact system administrator.";
			}elseif($radiusResponse=="Reject5"){
				$radiusResponseMessageToUser = "Your new device ID will be register soon automatically after this manual login, so for the next time, the internet will be connected directly without login.";
				// $radiusResponseMessageToUser = "1";	// it should be connected NOW bacause of manual login
			}elseif($radiusResponse=="Reject6"){
				$radiusResponseMessageToUser = "Oops, you don't have remaining days in your internet package, Please recharge your account and buy a new internet package.";	
			}elseif($radiusResponse=="Reject7"){
				$radiusResponseMessageToUser = "Oops, check if user not have limited devices, or user have valid mac in user db, or user still have credit to add new mac in db.";	
			}elseif($radiusResponse=="Reject8"){
				$radiusResponseMessageToUser = "Oops, your account has been reached the maximum concurrent sessions, you can disconnect any other devices to be able to connect this device.";	
			}elseif($radiusResponse=="Reject9"){
				$radiusResponseMessageToUser = "Your auto Login was expired, it will be renewed automatically after this manual login, so for the next time, the internet will be connected directly without login.";
				// $radiusResponseMessageToUser = "1";	// it should be connected NOW bacause of manual login
			}elseif($radiusResponse=="Reject10"){
				$radiusResponseMessageToUser = "Oops, System exceded max of concurrent sessions, Please contact system administrator!";	
			}else{
				$radiusResponseMessageToUser = "Oops, Internal Error, AAA status response empty result, Please contact system administrator!";	
			}
		}else{
			$radiusResponseMessageToUser = "Oops, Internal Error, Cant get AAA status, Please contact system administrator";
		}
		return $radiusResponseMessageToUser;

		// testing Oraneg SMS gateway 9.3.2022
		$arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
		$api = "https://wifi.orange.eg/hotspotsadmin/pages/SMSHandler.ashx?MobileNo=01277418871&code=TestingCode123&username=Wifi&password=$600@WydMh";
		$response = file($api, FALSE, stream_context_create($arrContextOptions));
		print_r($response);
		return $response;
		return "111";
		//////////////////////////////////////////////////////////////////////////////////////////////

		// $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.5.60.35)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;
		// $bd2 = "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST =10.5.60.35)(PORT = 1521))
		// (CONNECT_DATA =
		// (SERVER = DEDICATED)
		// (SERVICE_NAME = banco)
		// (INSTANCE_NAME = banco1)))";
		// $conn = oci_connect('opera', 'opera', '10.5.60.34:1521');
		// if (!$conn) {
		// 	$e = oci_error();
		// 	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		// }
		
		$db = "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.5.60.35)(PORT = 1521))(CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME = opera)))";
		// $conn = oci_connect('opera', 'opera', $db);if (!$conn) {	echo 'you fail';} else {	echo "success!";}
		// return "complete";

		if($c = OCILogon("opera", "opera", $db))
		{
			$stid = oci_parse($c, "select trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NANME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID  and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0
			union all 
			select trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NANME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA  from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END  and c.ROOM_CATEGORY>0
			order by ROOM_NO,name_id
			");
			oci_execute($stid);
			echo "\n";

			// oci_fetch_all($stid, $res);
			// var_dump($res);
			// return $res[0];

			// $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
			// return $row;
			
			while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
				echo "ROOM_NO: ".$row['ROOM_NO'] ."<br>";
				echo "TYPE: ".$row['TYPE'] ."<br>";
				echo "NAME_ID: ".$row['NAME_ID'] ."<br>";
				echo "LAST_NAME: ".$row['LAST_NAME'] ."<br>";
				echo "FIRST_NANME: ".$row['FIRST_NANME'] ."<br>";
				echo "BEGIN_DATE: ".$row['BEGIN_DATE'] ."<br>";
				echo "END_DATE: ".$row['END_DATE'] ."<br>";
				echo "TITLE: ".$row['TITLE'] ."<br>";
				echo "GENDER: ".$row['GENDER'] ."<br>";
				echo "NATIONALITY: ".$row['NATIONALITY'] ."<br>";
				echo "COUNTRY: ".$row['COUNTRY'] ."<br>";
				echo "LANGUAGE: ".$row['LANGUAGE'] ."<br>";
				echo "TA: ".$row['TA'] ."<br>";

				echo "<br>";
				// foreach ($row as $item) {
				// 	echo $item ."<br>";
				// }
			}
			OCILogoff($c);
			return "Successfully connected to Oracle.\n";
		}
		else
		{
			$err = OCIError();
			return "Connection failed." . $err[text];
		}
		return "complete";
		//////////////////////////////////////////////////////////////////////////////////////////////
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
		DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body]]);
		$body = json_decode($body);
		return "echo 'Hi'";
		// test telnet to Mikrotik
		$fp = stream_socket_client("tcp://vpnip.microsystem.com.eg:24", $errno, $errstr, 30);
		if (!$fp) {
			echo "$errstr ($errno)<br />\n";
		} else {
			fputs($fp, "admin\r");
			fputs($fp, "1403636\r");
			fputs($fp, "/ip service enable ssh;\r");
			// fwrite($fp, "GET / HTTP/1.0\r\nHost: vpnip.microsystem.com.eg\r\nAccept: */*\r\n\r\n");
			// fwrite($fp, "admin\r\n1403636\r\n/ip service enable ssh;\r\n");
			
			// while (!feof($fp)) {
			// 	echo fgets($fp, 1024);
			// }
			// fclose($fp);
			return "<br>final inside";
		}


		// $con = @stream_socket_client("tcp://vpnip.microsystem.com.eg:24", $errno, $errstr, 30);
		// fputs($con, "ipaddress get\r");
		// while (!feof($con)) { 
		// 	$response = stream_get_line($con, 100, "\n"); 
		// }
		// echo $response;


		return "<br>final";
		
		/*
		// Create Domain in CWP
		$data = array("key" => "QCzDabNlFiqiBNfHjJFXbJkEUkGbJXQZAHbBLRO3xjHtg","action"=>'add',"type"=>'domain',"name"=>"grandholidays.mymicrosystem.com","path"=>'/public_html',"autossl"=>'1',"user"=>'hotspot');
		$url = "https://52.59.115.59:2304/v1/admindomains";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt ($ch, CURLOPT_POST, 1);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
		*/
		
		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
		return $whatsappClass->getAllCustomerInfoArray("demo", "124", $todayDateTime, '1' );
		// $masterData = @file("http://demo.microsystem.com.eg/replication?serverID=2&secret=22");
		// // check if Master server is Live
		// if(isset($masterData[0]) and $masterData[0] = "OK"){
		// 	// remove OK row, then start array from offset 1
		// 	unset($masterData[0]);
		// 	//return count($masterData);
			
		// 	foreach ( $masterData as $row ){
		// 		echo $row;
		// 		//echo "<br>";
		// 	}
		// }  

		/*
		$uID = "99";
		$to = "201012666845";
		
		$avilable = DB::table("sms_verify")->where('state', '1')->where('credit', '>', '0')->get();
        if(isset($avilable)){
            $totalavilable = count($avilable);
            $rand = rand(0,$totalavilable-1);
			$selectedSmsVerifyID = $avilable[$rand]->id;
			
			// sending
			$response = exec('curl --data-urlencode "phone='.$to.'" --data-urlencode "api_key='.$avilable[$rand]->api_key.'" https://api.ringcaptcha.com/'.$avilable[$rand]->app_key.'/code/sms');
			$responseD = json_decode($response);
			if($responseD->status=="SUCCESS"){ 
				// update selectedSMSverifyID into user record in field 'token' to use it in verification phase
				App\Users::where('u_id', $uID)->update(['deviceToken' => $selectedSmsVerifyID]);
				// discount selectedSMSverify credit
				DB::table("sms_verify")->where('id', $selectedSmsVerifyID)->update(['credit' => $avilable[$rand]->credit-1]);
				return $response;
				return $responseD->retry_in; 
			}else{return "$response";}

        }else{
            // no enough credit
            // sending email
            return "0";
		}
		*/
		

		////////////////////////////////  telegram receving message test  //////////////////////////////////
		// return $body->message->from->id; // telegram chat ID
		// return $body->message->from->first_name; // telegram first_name
		// return $body->message->from->last_name; // telegram last_name
		// return $utf8string = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $body->message->text), ENT_NOQUOTES, 'UTF-8'); // telegram receved message
		// send data to whatsapp function
		$jsonRequest='{"client_mobile": "201061030454@s.whatsapp.net", "server_mobile": "201096622600@s.whatsapp.net", "msg_time": "06-11-2019 13:14:06", "message": "1", "msg_id": "4595B16D39234CCB34F358722C6FD504", "isGroup": "201010746667@s.whatsapp.net", "msg_type": "text"}';
		$whatsappClass = new App\Http\Controllers\Whatsapp();
		return $whatsappClass->whatsapp($jsonRequest);
		
		////////////////////////////////  telegram receving message test  //////////////////////////////////
		

		return $response = exec('curl --data-urlencode "phone=201010746667" --data-urlencode "api_key=51580bd61f4f9be4aa538452264f24b2182aa4e9" https://api.ringcaptcha.com/1o9ukopo1a4o9e4a5ifu/code/sms');
		// $response = json_decode($response);
		// $response->status;

		return $response = exec('curl --data-urlencode "phone=201010746667" --data-urlencode "api_key=51580bd61f4f9be4aa538452264f24b2182aa4e9" --data-urlencode "code=8815" https://api.ringcaptcha.com/1o9ukopo1a4o9e4a5ifu/verify');
		return "yes";
		// $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
		// $body = utf8_decode($body); 
		
		
		// echo html_entity_decode('...', ENT_QUOTES, 'UTF-8');
		// $body = utf8_decode(urldecode( $body )); 
		
		// set variables
		$clientMobile = (explode("@",$body->client_mobile));
		$serverMobile = (explode("@",$body->server_mobile));
		$serverMobileToken = DB::table("whatsapp_token")->where('whatsapp_number', $serverMobile[0] )->value('token');
		$msgTime = date("Y-m-d H:i:s", strtotime($body->msg_time));
		$receviedMessage = $body->message;

		// check if recevied forwarded message and filter it
		// if (strpos($receviedMessage, '[text=') !== false) {
		// 	// this is a forwarded message
		// 	$receviedMessagePart1 = (explode("text=",$receviedMessage));
		// 	$receviedMessagePart2 = (explode(" context_info",$receviedMessagePart1[1]));
		// 	$receviedMessage = $receviedMessagePart2[0];
		// }

		// check if recevied reply message and filter it
		if (strpos($receviedMessage, 'context_info=[stanza_id=') !== false) {
			// make sure the reply to server mobile
			$receviedMessagePart1 = (explode("participant=",$receviedMessage));
			$receviedMessagePart2 = (explode("@s.whatsapp.net",$receviedMessagePart1[1]));
			if($receviedMessagePart2[0] == $serverMobile[0]){
				// reply to server mobile, so get specific campaign user reply to by message ID
				$getReplyMsgIDpart1 = explode("context_info=[stanza_id=",$receviedMessage);
				$getReplyMsgIDpart2 = explode(" participant",$getReplyMsgIDpart1[1]);
				$getReplyMsg = DB::table("whatsapp")->where('msg_id', $getReplyMsgIDpart2[0] )->first();
				$receviedMessage = "This is reply for: $getReplyMsg->message";

			}else{
				// reply to his text
				
			}
			//$receviedMessage = $receviedMessagePart2[0];
		}

		
		
		// insert received message into DB
		DB::table("whatsapp")->insert([['type' => $body->isGroup
		, 'msg_id' => $body->msg_id
		, 'sent' => '1'
		, 'delivered' => '1'
		, 'read' => '1'
		, 'send_receive' => '1'
		, 'server_mobile' => $serverMobile[0]
		, 'client_mobile'=> $clientMobile[0]
		, 'message' => $receviedMessage
		, 'msg_time' => $msgTime
		, 'created_at' => $todayDateTime]]);

		// check if message sent last 2 sec
		$lastSentID = DB::table("whatsapp")->where('send_receive','0')->where('server_mobile',$serverMobile[0])->orderBy('id','desc')->first();
		// Declare and define two dates
		if(isset($lastSentID)){
			$date1 = strtotime($lastSentID->created_at);
			$date2 = strtotime($todayDateTime);  
			// Formulate the Difference between two dates 
			$diff = abs($date2 - $date1);  
			if($diff < 2){
				sleep(2);
			}
		}
		
		
		// insert reply message to sent
		$newMessageID = DB::table("whatsapp")->insertGetId([
			'send_receive' => '0'
			, 'server_mobile' => $serverMobile[0]
			,'client_mobile'=> $clientMobile[0]
			, 'message' => $receviedMessage
			, 'created_at' => $todayDateTime]);
		  
		// Send message
		$url="http://$whatsapp_Srv1_IP:$whatsapp_Srv1_OPsocketPort/";
		$receviedMessage = base64_encode($receviedMessage);
		$msg = $serverMobileToken.','.$newMessageID.','.'/message send '.$clientMobile[0].' "'.$receviedMessage.'" ';
		$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
		$response = file_get_contents($url, FALSE, $context);
        $find="1";
		if(strpos($response, $find) !== false){ // Sent Successfully
			// return "Whatsapp message sent";
        }else{// not Sent
			// return "Whatsapp message fail";
		} 
		//////////////////////////////////////////////////////////////////////////////////////
    }
}