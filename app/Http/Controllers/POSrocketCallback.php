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

class POSrocketCallback extends Controller
{
    public function POSrocket(Request $request){
		
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
		require_once '../config.php';

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $body = @file_get_contents('php://input');
		$bodyJson = json_decode($body);

		// // refresh token
		// $data = array(
		// 	'refresh_token' => 'crgZHsFndvc4O1ORe1WYtf7nL6aYTK',
		// 	'client_id' => $posRocketClientID,
		// 	'client_secret' => $posRocketClientSecret,
		// 	'grant_type' => 'refresh_token'
		// );
		// # Create a connection
		// $url = 'https://developer.posrocket.com/oauth/token/';
		// $ch = curl_init($url);
		// # Form data string
		// $postString = http_build_query($data, '', '&');
		// # Setting our options
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// # Get the response
		// $response = curl_exec($ch);
		// curl_close($ch);
		// $tokenResponse = json_decode($response);
		// print_r($tokenResponse);
		// return $tokenResponse->access_token;

        function getallheaders()
        {
            $headers = [];
            foreach ($_SERVER as $name => $value)
            {
                if (substr($name, 0, 5) == 'HTTP_')
                {
                    $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$name] = $value;
                } else if ($name == "Authorization") {
                    $headers["Content-Type"] = $value;
                } else if ($name == "CONTENT_LENGTH") {
                    $headers["Content-Length"] = $value;
                }
            }
            return $headers;
        }
        
        $headers = getallheaders();
        // return $headers->headers;
        $headerToText = "";
        foreach ($headers as $header => $value) {
            //echo "$header: $value <br />\n";
            $headerToText.= "$header: $value <br />\n";
        }
        
		DB::table("test")->insert([['value1' => $actual_link, 'value2' => "$headerToText", 'value3' => $body]]);
        //return "Finished";
		
		// // testing //
		// // get business_id
		// $postData = "";
		// $accessToken = "FYTVT2W2A5xPU0MUwzGtwJstqeNg9k";
		// $context = stream_context_create(array(
		// 	'http' => array(
		// 		'method' => 'GET',
		// 		'header' => "Authorization: Bearer $accessToken\r\n",
		// 		'content' => $postData
		// 	)
		// ));
		// $response = file_get_contents('https://developer.posrocket.com/api/v1/me', FALSE, $context);
		// $businessResponse = json_decode($response);
		// $businessID = $businessResponse->id;
		// return "<center><h1> Business ID: $businessID <br>Token: $accessToken </h1></center>";
		// // testing //

		// Authentication
		if(isset($_GET['code'])){
			
			$grantCode = $request['code']; // t0zZYp4xCvKwyVwy0gK88awzERrVNn
			//$postData = "code=$grantCode&redirect_uri=http://demo.microsystem.com.eg/api/testController2&client_id=$grantCode&client_secret=7j4lLdhrb8AvcT7XmaZ1tPqSzjhLErm2FOYiWZciGPxf3HFQAWD701hRlUsVamJ4YTi9MDUUKYM5r2Ye2kvE0cC8rjnLvApFetbaC7MxkHIBWCaJhfSH4VnzORaMCyo5&grant_type=authorization_code";
			$data = array(
				'code' => $grantCode,
				'redirect_uri' => asset('/').'api/POSrocketCallback',
				'client_id' => $posRocketClientID,
				'client_secret' => $posRocketClientSecret,
				'grant_type' => 'authorization_code'
			);
			# Create a connection
			$url = 'https://developer.posrocket.com/oauth/token/';
			$ch = curl_init($url);
			# Form data string
			$postString = http_build_query($data, '', '&');
			# Setting our options
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Get the response
			$response = curl_exec($ch);
			curl_close($ch);
			$tokenResponse = json_decode($response);
			
			// we must save it into customer DB in table Options
			$subdomain = url()->full();
			$split = explode('/', $subdomain);
			$customerData =  DB::table('customers')->where('url',$split[2])->first();
			if(isset($tokenResponse->access_token)){
				$accessToken = $tokenResponse->access_token; 
				$refreshToken = $tokenResponse->refresh_token;
				DB::table($customerData->database.".settings")->where('type', 'PosRocketIntegration' )->update([ 'value' => $accessToken ]);
			}
			else{$accessToken = "Unknown"; $refreshToken = "Unknown";}
			
			// return $accessToken;

			// get business_id
			$postData = "";
			$context = stream_context_create(array(
				'http' => array(
					'method' => 'GET',
					'header' => "Authorization: Bearer $accessToken\r\n",
					'content' => $postData
				)
			));
			$response = @file_get_contents('https://developer.posrocket.com/api/v1/me', FALSE, $context);
			$businessResponse = json_decode($response);
			if(isset($businessResponse->id)){$businessID = $businessResponse->id;}
			else{$businessID = "Unknown";}
			
			return view('back-end.settings.posRocketIntegrationState', ['businessID' => $businessID, 'accessToken' => $accessToken, 'refreshToken'=>$refreshToken]);
		}	
		
/*
		$allCustomers=DB::table('customers')->where('state','1')->whereNotNull('pos_rocket_id')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
			// get POSrocketToken
			$POSrocketToken = DB::table($Customer->database.".settings")->where('type','PosRocketIntegration')->value('value');

			// Get All branches from POSrocket and update there POS_ID according to name on POS database and our 'branches' table
			$context = stream_context_create(array( 'http' => array( 'method' => 'GET', 'header' => "Authorization: Bearer $POSrocketToken\r\n", 'content' => '') ));
			$response = file_get_contents('https://developer.posrocket.com/api/v1/locations', FALSE, $context);
			$locations = json_decode($response);
			foreach( $locations->data as $location ){
				// update branchID in customer branch DB
				if(DB::table($Customer->database.".branches")->where('name', $location->name)->value('name') == $location->name){
					DB::table($Customer->database.".branches")->where('name', $location->name )->update([ 'pos_id' => $location->id ]);
				}
				// insert this branch into system db
				if( DB::table("pos_locations")->where('location_id', $location->id)->count() == 0 ){
					DB::table("pos_locations")->insert([['customer_id' => $Customer->id, 'business_id' => $Customer->pos_rocket_id, 'location_id' => $location->id, 'location_name' => $location->name, 'created_at' => $todayDateTime]]);
				}
			}
	
			// Get All Items
			$context = stream_context_create(array( 'http' => array( 'method' => 'GET', 'header' => "Authorization: Bearer $POSrocketToken\r\n", 'content' => '') ));
			$response = file_get_contents('https://developer.posrocket.com/api/v1/catalog/items', FALSE, $context);
			$itsms = json_decode($response);
			DB::statement( "TRUNCATE TABLE $Customer->database".".pos_items" );
			foreach( $itsms->data as $item ){
				foreach( $item->variations as $variation ){
					DB::table($Customer->database.".pos_items")->insert([['pos_id' => $variation->id, 'name' => $variation->name, 'category' => $item->category->name, 'price' => round($variation->pricing[0]->price/1000,1), 'created_at' => $todayDateTime]]);
				}
			}

			// get all employees and store there employee_id
			$context = stream_context_create(array( 'http' => array( 'method' => 'GET', 'header' => "Authorization: Bearer $POSrocketToken\r\n", 'content' => '') ));
			$response = file_get_contents('https://developer.posrocket.com/api/v1/me/employees', FALSE, $context);
			$employees = json_decode($response);
			foreach( $employees->data as $employee ){
				if( DB::table("$Customer->database.admins")->where('email', $employee->email)->count() > 0 ){
					DB::table("$Customer->database.admins")->where('email', $employee->email )->update([ 'pos_id' => $employee->id, 'updated_at' => $todayDateTime ]);
				}
			}
			
			// get all users from POSrocket
			$context = stream_context_create(array( 'http' => array( 'method' => 'GET', 'header' => "Authorization: Bearer $POSrocketToken\r\n", 'content' => '') ));
			$response = file_get_contents('https://developer.posrocket.com/api/v1/directory/customers', FALSE, $context);
			$users = json_decode($response);
			
			// check if there is next page
			if(isset($users->next)){
				// gat all next links and merge it in one array, according to the total number of POSrocket users count
				for($i=0; $i<=$users->count; $i++){
					if($i==0){
						$i= $users->count-count($users->data);
						$newUsers = json_decode(file_get_contents($users->next, FALSE, $context));
					}else{
						if(isset($newUsers->next)){	
							$newUsers = json_decode(file_get_contents($newUsers->next, FALSE, $context));
							$i= $i-count($newUsers->data);
						}else{// finished, not found any next link
							break;
						}
					}
					$users->data = array_merge($users->data, $newUsers->data);
				}
			}

			foreach( $users->data as $user ){
				
				if( DB::table("$Customer->database.users")->where('pos_id', $user->id)->orWhere('u_phone', 'like', '%'.$user->phone_numbers[0]->number.'%')->count() == 0 ){
					
					$mobileWithoutCountryCode = "";
					$mobileWithCountryCode = "";
					$counter = 0;
					$from = DB::table("whatsapp_token")->where('customer_id', $Customer->id )->where('state', '1')->value('server_mobile');
					foreach($user->phone_numbers as $mobile){
						if($counter != 0){ $mobileWithoutCountryCode.=","; $mobileWithCountryCode.=","; }
						if( $mobile->number[0] != "0" and strlen($mobile->number) == 10){ $mobileWithoutCountryCode.="0".$mobile->number; $mobileWithCountryCode.="20".$mobile->number; }
						elseif($mobile->number[0] == "2" and strlen($mobile->number) == 12){ $mobileWithoutCountryCode.=substr($mobile->number,1); $mobileWithCountryCode.=$mobile->number; }
						else{ $mobileWithoutCountryCode.=$mobile->number; $mobileWithCountryCode.=$mobile->number; }
						// get country name
						if( substr($mobileWithCountryCode, 0, 2)=="20" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 1); $u_country = "Egypt"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="966" ){ $mobileWithoutCountryCode = "0".substr($mobileWithCountryCode, 3);  $u_country = "Saudi Arabia"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="971" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "United Arab Emirates"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="965" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Kuwait"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="905" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Canada"; }
						elseif( substr($mobileWithCountryCode, 0, 2)=="41" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 2);   $u_country = "Switzerland"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="491" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Germany"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="316" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Netherlands"; }
						elseif( substr($mobileWithCountryCode, 0, 2)=="44" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 2);   $u_country = "United Kingdom"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="393" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Italy"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="336" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "France"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="973" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Bahrain"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="974" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Qatar"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="964" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Iraq"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="961" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Lebanon"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="962" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Jordan"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="220" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Gambia"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="970" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Palestine"; }
						elseif( substr($mobileWithCountryCode, 0, 3)=="972" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Israel"; }
						else{ $mobileWithoutCountryCode = $mobile; $u_country = "Unknown";}
						// send Whatsapp menu
						$whatsappClass->sendWhatsappMenu($Customer->database, $Customer->id, $mobile, $todayDateTime, $from);
						$counter++;
					}
					if( DB::table("$Customer->database.users")->where('u_phone',$mobileWithCountryCode)->count() == 0 ){
						// set email
						if( isset($user->email)){$email = $user->email;}else{$email = ' ';}
						// set gender
						if($user->gender == "MALE"){$gender = 1;} elseif($user->gender == "FEMALE"){$gender = 0;} else{$gender=2;}
						// create new user in database
						$newUserID = DB::table("$Customer->database.users")->insertGetId([ 'pos_id' => $user->id, 'u_email' => $email, 'Registration_type' => '2', 'u_state' => '1', 'suspend' => '0', 'birthdate'=> $user->dob,'u_name' => $user->first_name." ".$user->last_name, 'u_uname' => $mobileWithoutCountryCode, 'u_password' => $mobileWithCountryCode, 'u_phone' => $mobileWithCountryCode, 'u_country' => $u_country, 'u_gender' => $gender, 'branch_id' => DB::table($Customer->database.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($Customer->database.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($Customer->database.".area_groups")->where('name','Default')->orWhere('name','default')->value('id'), 'created_at' => $todayDateTime]);
						// create fake session in radacct table to count this visit
						DB::table("$Customer->database.radacct")->insert([[ 'acctsessionid' => rand(100000, 999999), 'acctuniqueid' => rand(100000, 999999), 'username' => $mobileWithoutCountryCode, 'acctstarttime' => $todayDateTime, 'acctstoptime' => $todayDateTime, 'acctsessiontime' => '60', 'acctauthentic' => '00:01:00', 'acctupdatetime' => $todayDateTime, 'u_id' => $newUserID, 'dates' => $today, 'branch_id' => DB::table($Customer->database.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($Customer->database.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($Customer->database.".area_groups")->where('name','Default')->orWhere('name','default')->value('id') ]]);
					}
					
				}
			}

			// send all users not registerd in POSrocket
			$usersNotRegisterd = DB::table("$Customer->database.users")->whereNull('pos_id')->get();
			foreach($usersNotRegisterd as $user){
				// build variables and arrays
				if($user->u_gender == "1"){$gender = "MALE";}elseif($user->u_gender=="0"){$gender="FEMALE";}else{$gender="UNSPECIFIED";}
				$mobile = [['number' => $user->u_phone, 'is_primary' => true, 'is_verified' => true]]; 
				$data = ['first_name' => $user->u_name, 'gender' => $gender, 'country' => 'Eg', 'phone_numbers' => $mobile, 'dob' => $user->birthdate];
				$msg = json_encode($data);
				$url = "https://developer.posrocket.com/api/v1/directory/customers";
				$context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\nAuthorization: Bearer $POSrocketToken",'content' => $msg)));
				$response = @file_get_contents($url, FALSE, $context);
				$response = json_decode($response);
				if(isset($response->data->id)){ DB::table("$Customer->database.users")->where('u_id', $user->u_id)->update(['pos_id' => $response->data->id]); }
			}
		}
*/
		// receive new customer creation
		if( isset($bodyJson->event) and $bodyJson->event=="customer.create"){
			// get system details
			$customerData = DB::table("customers")->where('pos_rocket_id', $bodyJson->business_id)->first();
			if(isset($customerData)){
				// return $bodyJson->body->first_name;
				$mobileWithoutCountryCode = "";
				$mobileWithCountryCode = "";
				$counter = 0;
				foreach($bodyJson->body->phone_numbers as $mobile){
					if($counter != 0){ $mobileWithoutCountryCode.=","; $mobileWithCountryCode.=","; }
					if( $mobile->number[0] != "0" and strlen($mobile->number) == 10){ $mobileWithoutCountryCode.="0".$mobile->number; $mobileWithCountryCode.="20".$mobile->number; }
					elseif($mobile->number[0] == "2" and strlen($mobile->number) == 12){ $mobileWithoutCountryCode.=substr($mobile->number,1); $mobileWithCountryCode.=$mobile->number; }
					else{ $mobileWithoutCountryCode.=$mobile->number; $mobileWithCountryCode.=$mobile->number; }
					// get country name
					if( substr($mobileWithCountryCode, 0, 2)=="20" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 1); $u_country = "Egypt"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="966" ){ $mobileWithoutCountryCode = "0".substr($mobileWithCountryCode, 3);  $u_country = "Saudi Arabia"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="971" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "United Arab Emirates"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="965" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Kuwait"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="905" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Canada"; }
					elseif( substr($mobileWithCountryCode, 0, 2)=="41" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 2);   $u_country = "Switzerland"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="491" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Germany"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="316" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Netherlands"; }
					elseif( substr($mobileWithCountryCode, 0, 2)=="44" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 2);   $u_country = "United Kingdom"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="393" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Italy"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="336" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "France"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="973" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Bahrain"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="974" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Qatar"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="964" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Iraq"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="961" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Lebanon"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="962" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Jordan"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="220" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Gambia"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="970" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Palestine"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="972" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Israel"; }
					else{ $mobileWithoutCountryCode = $mobile; $u_country = "Unknown";}
					$counter++;
				}
				if( DB::table("$customerData->database.users")->where('u_phone',$mobileWithCountryCode)->count() == 0 ){
					// set email
					if( isset($bodyJson->body->email)){$email = $bodyJson->body->email;}else{$email = ' ';}
					// set gender
					if($bodyJson->body->gender == "MALE"){$gender = 1;} elseif($bodyJson->body->gender == "FEMALE"){$gender = 0;} else{$gender=2;}
					// create new user in database
					$newUserID = DB::table("$customerData->database.users")->insertGetId([ 'pos_id' => $bodyJson->body->id, 'u_email' => $email, 'Registration_type' => '2', 'u_state' => '1', 'suspend' => '0', 'birthdate'=> $bodyJson->body->dob,'u_name' => $bodyJson->body->first_name." ".$bodyJson->body->last_name, 'u_uname' => $mobileWithoutCountryCode, 'u_password' => $mobileWithCountryCode, 'u_phone' => $mobileWithCountryCode, 'u_country' => $u_country, 'u_gender' => $gender, 'branch_id' => DB::table($customerData->database.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($customerData->database.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($customerData->database.".area_groups")->where('name','Default')->orWhere('name','default')->value('id'), 'created_at' => $todayDateTime]);
					// create fake session in radacct table to count this visit
					DB::table("$customerData->database.radacct")->insert([[ 'acctsessionid' => rand(100000, 999999), 'acctuniqueid' => rand(100000, 999999), 'username' => $mobileWithoutCountryCode, 'acctstarttime' => $todayDateTime, 'acctstoptime' => $todayDateTime, 'acctsessiontime' => '60', 'acctauthentic' => '00:01:00', 'acctupdatetime' => $todayDateTime, 'u_id' => $newUserID, 'dates' => $today, 'branch_id' => DB::table($customerData->database.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($customerData->database.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($customerData->database.".area_groups")->where('name','Default')->orWhere('name','default')->value('id') ]]);
					// send Whatsapp menu
					$from = DB::table("whatsapp_token")->where('customer_id', $customerData->id )->where('state', '1')->value('server_mobile');
					$whatsappClass->sendWhatsappMenu($customerData->database, $customerData->id, $mobile, $todayDateTime, $from);
				}
			}
		}

		// receive update customer
		if( isset($bodyJson->event) and $bodyJson->event=="customer.update"){
			// get system details
			$customerData = DB::table("customers")->where('pos_rocket_id', $bodyJson->business_id)->first();
			if(isset($customerData)){
				// return $bodyJson->body->first_name;
				$mobileWithoutCountryCode = "";
				$mobileWithCountryCode = "";
				$counter = 0;
				foreach($bodyJson->body->phone_numbers as $mobile){
					if($counter != 0){ $mobileWithoutCountryCode.=","; $mobileWithCountryCode.=","; }
					if( $mobile->number[0] != "0" and strlen($mobile->number) == 10){ $mobileWithoutCountryCode.="0".$mobile->number; $mobileWithCountryCode.="20".$mobile->number; }
					elseif($mobile->number[0] == "2" and strlen($mobile->number) == 12){ $mobileWithoutCountryCode.=substr($mobile->number,1); $mobileWithCountryCode.=$mobile->number; }
					else{ $mobileWithoutCountryCode.=$mobile->number; $mobileWithCountryCode.=$mobile->number; }
					// get country name
					if( substr($mobileWithCountryCode, 0, 2)=="20" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 1); $u_country = "Egypt"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="966" ){ $mobileWithoutCountryCode = "0".substr($mobileWithCountryCode, 3);  $u_country = "Saudi Arabia"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="971" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "United Arab Emirates"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="965" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Kuwait"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="905" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Canada"; }
					elseif( substr($mobileWithCountryCode, 0, 2)=="41" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 2);   $u_country = "Switzerland"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="491" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Germany"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="316" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Netherlands"; }
					elseif( substr($mobileWithCountryCode, 0, 2)=="44" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 2);   $u_country = "United Kingdom"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="393" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Italy"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="336" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "France"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="973" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Bahrain"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="974" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Qatar"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="964" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Iraq"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="961" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Lebanon"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="962" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Jordan"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="220" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Gambia"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="970" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Palestine"; }
					elseif( substr($mobileWithCountryCode, 0, 3)=="972" ){ $mobileWithoutCountryCode = substr($mobileWithCountryCode, 3);  $u_country = "Israel"; }
					else{ $mobileWithoutCountryCode = $mobile; $u_country = "Unknown";}
					$counter++;
				}
				
				// set email
				if( isset($bodyJson->body->email)){$email = $bodyJson->body->email;}else{$email = ' ';}
				// set gender
				if($bodyJson->body->gender == "MALE"){$gender = 1;} elseif($bodyJson->body->gender == "FEMALE"){$gender = 0;} else{$gender=2;}
				// update user in database
				DB::table("$customerData->database.users")->where('pos_id', $bodyJson->body->id )->update([ 'u_email' => $email, 'birthdate' => $bodyJson->body->dob, 'u_name' => $bodyJson->body->first_name." ".$bodyJson->body->last_name, 'u_uname' => $mobileWithoutCountryCode, 'u_password' => $mobileWithCountryCode, 'u_phone' => $mobileWithCountryCode, 'u_country' => $u_country, 'u_gender' => $gender ]);
			}
		}
		
		// receive checkout with assigned customer
		if( isset($bodyJson->event) and $bodyJson->event=="sale.create" and isset($bodyJson->body->customer->id)){
			// get system details
			$customerData = DB::table("customers")->where('pos_rocket_id', $bodyJson->business_id)->first();
			if(isset($customerData)){
				// get user data
				$userData = DB::table("$customerData->database.users")->where('pos_id', $bodyJson->body->customer->id)->orWhere('u_phone', 'like', '%'.$bodyJson->body->customer->phone_number->number.'%')->first();
				if(isset($userData)){
					$amount = round($bodyJson->body->total_collected_money->amount/1000,1);
					if($amount>=1){
						// calculate loyalty points
						$amountToLoyaltyPoints = DB::table("$customerData->database.settings")->where('type', 'amountToLoyaltyPoints')->value('value');
						$earnedPoints = $amount * $amountToLoyaltyPoints;
						// get admin data
						$adminData = DB::table("$customerData->database.admins")->where('email', $bodyJson->body->creator->email)->first();
						if(isset($adminData)){$adminID = $adminData->id;}else{$adminID = "0";}
						// insert points
						$newPointsID = DB::table("$customerData->database.loyalty_points")->insertGetId(['state' => '1','type' => '1', 'a_id' => $adminID, 'u_id' => $userData->u_id, 'amount' => $amount, 'points' => $earnedPoints, 'created_at' => $todayDateTime]);
						// get whatsappUserReceivePointsMsg
						$whatsappUserReceivePointsMsg = DB::table("$customerData->database.settings")->where('type', 'whatsappUserReceivePointsMsg')->value('value');
						$whatsappUserReceivePointsMsg = @str_replace("@earned","$earnedPoints",$whatsappUserReceivePointsMsg);
						// get loyality points
						$loyaltyPoints = $whatsappClass->getCustomerLoyaltyPoints($customerData->database,$userData->u_id,$todayDateTime);
						// convert points text message
						$whatsappUserReceivePointsMsg = @str_replace("@points","$loyaltyPoints",$whatsappUserReceivePointsMsg);
						// get all and avilable loyalty programs
						$allAndAvilableLoyaltyProgram = $whatsappClass->getAllAndAvilableLoyaltyProgram($customerData->database, $userData->u_id, $todayDateTime);
						$whatsappUserReceivePointsMsg = @str_replace("@all_loyalty_programs",$allAndAvilableLoyaltyProgram['all'],$whatsappUserReceivePointsMsg);
						if($allAndAvilableLoyaltyProgram['available']!=null){ $whatsappUserReceivePointsMsg = @str_replace("@available_loyalty_programs",$allAndAvilableLoyaltyProgram['available'],$whatsappUserReceivePointsMsg); }
						else{$whatsappUserReceivePointsMsg = @str_replace("@available_loyalty_programs","till now nothing😳!",$whatsappUserReceivePointsMsg);}
						// send Whatsapp Message to user
						$whatsappClass->Send(null, $userData->u_phone, $whatsappUserReceivePointsMsg, $customerData->id, $customerData->database);
						// insert buy items in 'history' table
						foreach($bodyJson->body->itemization as $item){
							DB::table("$customerData->database.history")->insert([['operation' => 'customerCheckoutItem', 'details' => $item->variation->id, 'type2' => $newPointsID, 'u_id' => $userData->u_id, 'a_id' => $adminID, 'notes' => $item->variation->name, 'type1' => 'hotspot', 'add_date' => $today, 'add_time' => $today_time]]);
						}
					}
				}
			}
		}

		// receive refund with assigned customer
		if( isset($bodyJson->event) and $bodyJson->event=="refund.create" and isset($bodyJson->body->customer->id)){
			// get system details
			$customerData = DB::table("customers")->where('pos_rocket_id', $bodyJson->business_id)->first();
			if(isset($customerData)){
				// get user data
				$userData = DB::table("$customerData->database.users")->where('pos_id', $bodyJson->body->customer->id)->orWhere('u_phone', 'like', '%'.$bodyJson->body->customer->phone_number->number.'%')->first();
				if(isset($userData)){
					$amount = round($bodyJson->body->tender->total_money->amount/1000,1);
					// calculate loyalty points
					$amountToLoyaltyPointsRefund = DB::table("$customerData->database.settings")->where('type', 'amountToLoyaltyPoints')->value('value');
					$refundPoints = $amount * $amountToLoyaltyPointsRefund;
					// get admin data
					$adminData = DB::table("$customerData->database.admins")->where('email', $bodyJson->body->creator->email)->first();
					if(isset($adminData)){$adminID = $adminData->id;}else{$adminID = "0";}
					// insert refund points
					DB::table("$customerData->database.loyalty_points")->insert([['state' => '1','type' => '0', 'a_id' => $adminID, 'u_id' => $userData->u_id, 'amount' => $amount, 'points' => $refundPoints, 'created_at' => $todayDateTime]]);
					// get whatsappUserRefundPointsMsg
					$whatsappUserReceivePointsMsg = DB::table("$customerData->database.settings")->where('type', 'whatsappUserRefundPointsMsg')->value('value');
					$whatsappUserReceivePointsMsg = @str_replace("@refund","$refundPoints",$whatsappUserReceivePointsMsg);
					// get loyality points
					$loyaltyPoints = $whatsappClass->getCustomerLoyaltyPoints($customerData->database,$userData->u_id,$todayDateTime);
					// convert points text message
					$whatsappUserReceivePointsMsg = @str_replace("@points","$loyaltyPoints",$whatsappUserReceivePointsMsg);
					// send Whatsapp Message to user
					$whatsappClass->Send(null, $userData->u_phone, $whatsappUserReceivePointsMsg, $customerData->id, $customerData->database);
				}
			}
		}
		
		// receive loaylty code qyery
		if( isset($request->type) and $request->type == "LOYALTY_VERIFICATION"){
			/*
			// return "here";
			// $freeItems = [['variation_id' => 'fcdfb8bd-124f-4cd4-8af5-6892ce1ea43d', 'variation_id' => 'eacd87c8-0fa1-4975-b41f-99da39a7805d']];
			// $data = array(
			// 	'variation_id' => 'eacd87c8-0fa1-4975-b41f-99da39a7805d',
			// 	'client_id' => 'KwqRWl7RBDuCOoP4YIddrYDffSMDZaHBEYdD1gYA',
			// 	'client_secret' => 'vJjosuogglqOUop1QDi2vHwkuYNHJP7LQ6WObsCeqQOetNcIyu4U0XgtppJGTSZkSIBBREO0BHKXvh34GioXpe2sVMycYnMjyKpWyAl9irid2gfbC2sSmjgcbmfPHbQf',
			// 	'grant_type' => 'authorization_code',
			// );
			// $a = array('variation_id' => 'fcdfb8bd-124f-4cd4-8af5-6892ce1ea43d');
			
			// working fine FREE_ITEMS
			$FREE_ITEMS = '
				{ 
					"action": "FREE_ITEMS", 
					"item": [ 
						{
						"variation_id": "eacd87c8-0fa1-4975-b41f-99da39a7805d"
						},
						{
						"variation_id": "fcdfb8bd-124f-4cd4-8af5-6892ce1ea43d" 
						}
					]
				} 
			';
				// -- DISCOUNT_PER_ITEM
			$DISCOUNT_PER_ITEM = '{"action": "DISCOUNT_PER_ITEM","discount": { "name": "integration", "type": "PERCENTAGE", "rate": 0.1 }, "item": [ { "variation_id": "eacd87c8-0fa1-4975-b41f-99da39a7805d" } ] }';
				// depends on lettle burger **
			$BY_ONE_GET_MANY = '
				{ 
					"action": "BY_ONE_GET_MANY", 
					"item": [ 
					{ 
					"variation_id": "eacd87c8-0fa1-4975-b41f-99da39a7805d" 
					}, 
					{ 
					"variation_id": "fcdfb8bd-124f-4cd4-8af5-6892ce1ea43d" 
					} 
					], 
					"depends_on": "089de461-c6f5-4f59-a9bc-66dcf814cd84" 
				} 
			';
				// 1% = 0.01 | 1.5% = 0.015 | 10% = 0.1 | 55% = 0.55 | 100% = 10
			$DISCOUNT_PER_SALE = '{"action": "DISCOUNT_PER_SALE","discount": {"name": "integration","type": "PERCENTAGE","rate": 0.55}}';
			$DISCOUNT_PER_SALE = '{ "action": "DISCOUNT_PER_ITEM", "discount": { "name": "integration", "type": "FIXED", "amount": 1000, "after_tax": true }, "item": [ { "variation_id": "cd9f7bf1-cb9f-404f-97f2-cf73384dff8e" } ] }';
			
			return $DISCOUNT_PER_SALE;
			return $msg = json_encode($data);
			*/
			/////////////////////////////////////////////
			$checkByLocationID = DB::table("pos_locations")->where('location_id', $request->location_id)->first();
			if(isset($checkByLocationID)){
				
				$customerData = DB::table("customers")->where('id', $checkByLocationID->customer_id)->first();
				
				$offerData=DB::table( $customerData->database.".campaign_statistics" )->where( 'offer_code',$request->code )->where('type','offer')->first();
				if( isset($offerData) ){
					if( $offerData->state == "0" ){// offer is available
						if(strlen($request->code) == "8"){
							// this offer code to redeem loyalty program points, so we get there info from Table: 'loyalty_program'
							$offerDesc = DB::table("$customerData->database.loyalty_program")->where('id', $offerData->campaign_id)->first();
							// to bass next step
							$remainingOffers="unlimited";
						}else{
							// that's mean the digits is 6 and this is normal offer code related to normal campaign, so we get there info from Table: 'campaigns'
							$campaignData = DB::table( $customerData->database.".campaigns" )->where('id',$offerData->campaign_id)->first();
							$offerDesc = DB::table( $customerData->database.".loyalty_program" )->where('row_type','2')->where('state','1')->where('campaign_id',$offerData->campaign_id)->first();
							// check if there is a limit or not
							if( $campaignData->offer_limit =="" or $campaignData->offer_limit=="0")
							{
								$remainingOffers="unlimited";
							}else{
								$redeemedOffers = DB::table($customerData->database.".campaign_statistics")->where( 'type', 'offer' )->where( 'campaign_id', $campaignData->id )->where( 'state', '1' )->count();
								$remainingOffers=$campaignData->offer_limit-$redeemedOffers;
							}
						}
									
						if( isset($offerDesc) ){
							$offerItems = DB::table( $customerData->database.".loyalty_program_items" )->where('loyalty_program_id',$offerDesc->id)->get();

							// check if the is avilable offer before limit finish
							if( $remainingOffers == "unlimited" or $remainingOffers >=1 ){

								// user data
								$userData = DB::table( $customerData->database.".users" )->where('u_id',$offerData->u_id)->first();
								// check if this is FREE_ITEMS
								if($offerDesc->type == "1"){
									
									$autoReplyMsg = '{"action": "FREE_ITEMS", "item": [ ';
									$counter = 0;
									foreach($offerItems as $item){
										if($counter>0){$autoReplyMsg.=',';}
										$autoReplyMsg.='{"variation_id": "'.$item->item_id.'"}';
										$counter++;
									}
									$autoReplyMsg.=']} ';
								}// check if this is DISCOUNT_PER_ITEM
								elseif($offerDesc->type == "2"){

									if($offerDesc->discount_type == "1"){
										$discountType = "PERCENTAGE";
										$rate = $offerDesc->discount_value/100;
										$afterTax = ""; $rateOrAmount = "rate";
									}else{
										$discountType = "FIXED";
										$rate = $offerDesc->discount_value*1000;
										$afterTax = ', "after_tax": true'; $rateOrAmount = "amount";
									}
									$autoReplyMsg = '{"action": "DISCOUNT_PER_ITEM","discount": { "name": "integration", "type": "'.$discountType.'", "'.$rateOrAmount.'": '.$rate.' '.$afterTax.'}, "item": [ ';
									$counter = 0;
									foreach($offerItems as $item){
										if($counter>0){$autoReplyMsg.=',';}
										$autoReplyMsg.='{"variation_id": "'.$item->item_id.'"}';
										$counter++;
									}
									$autoReplyMsg.= '] }';
								}
								// check if this is BY_ONE_GET_MANY
								elseif($offerDesc->type == "3"){
									$autoReplyMsg = '{ "action": "BY_ONE_GET_MANY", "item": [ ';
									$counter = 0;
									foreach($offerItems as $item){
										if($counter>0){$autoReplyMsg.=',';}
										$autoReplyMsg.='{"variation_id": "'.$item->item_id.'"}';
										$counter++;
									}
									$autoReplyMsg.='], "depends_on": "'.$offerDesc->depends_on_item_id.'" } ';
								}
								// check if this is DISCOUNT_PER_SALE
								elseif($offerDesc->type == "4"){

									if($offerDesc->discount_type == "1"){
										$discountType = "PERCENTAGE";
										$rate = $offerDesc->discount_value/100;
										$afterTax = ""; $rateOrAmount = "rate";
									}else{
										$discountType = "FIXED";
										$rate = $offerDesc->discount_value*1000;
										$afterTax = ', "after_tax": true'; $rateOrAmount = "amount";
									}
									$autoReplyMsg = '{"action": "DISCOUNT_PER_SALE","discount": { "name": "integration", "type": "'.$discountType.'", "'.$rateOrAmount.'": '.$rate.' '.$afterTax.'}}';
								}
								
							}else{
								// offer limit has been reached
								$autoReplyMsg = "⚠ Offer limit has been reached.";
							}
						}else{
							$autoReplyMsg = "Not found offer desc in `loyalty_program` table.";
						}
						
					}elseif( $offerData->state == "1" ){
						$autoReplyMsg = "⚠ Offer used before \n At: $offerData->updated_at\n Redeemed By: ".DB::table( $customerData->database.".users" )->where('u_id',$offerData->u_id)->value('u_name')."\n By Admin: ".DB::table( $customerData->database.".admins" )->where('id',$offerData->a_id)->value('name');
					}
				}else{
					$autoReplyMsg = "Invalid offer code.";
				}
			}else{
				$autoReplyMsg = "not found location ID in `pos_locations` table.";
			}

			return $autoReplyMsg;
			//////////////////////////////////////////////
		}

		// receive loaylty code REDEEM
		if( isset($request->type) and $request->type == "LOYALTY_REDEEM"){
			
			$checkByLocationID = DB::table("pos_locations")->where('location_id', $request->location_id)->first();
			if(isset($checkByLocationID)){
				
				$customerData = DB::table("customers")->where('id', $checkByLocationID->customer_id)->first();
				$offerData=DB::table( $customerData->database.".campaign_statistics" )->where( 'offer_code',$request->code )->where('type','offer')->first();
				
				if(isset($offerData) and $offerData->state == "0" ){// offer is available
					
					if(strlen($request->code) == "8"){
						// this offer code to redeem loyalty program points, so we get there info from Table: 'loyalty_program'
						// to bass next step
						$remainingOffers="unlimited";
					}else{
						// that's mean the digits is 6 and this is normal offer code related to normal campaign, so we get there info from Table: 'campaigns'
						// get campaign data
						$campaignData = DB::table( $customerData->database.".campaigns" )->where('id',$offerData->campaign_id)->first();
						// check if there is a limit or not
						if($campaignData->offer_limit =="" or $campaignData->offer_limit=="0")
						{
							$remainingOffers="unlimited";
						}else{
							$redeemedOffers = DB::table($customerData->database.".campaign_statistics")->where( 'type', 'offer' )->where( 'campaign_id', $campaignData->id )->where( 'state', '1' )->count();
							$remainingOffers=$campaignData->offer_limit-$redeemedOffers;
						}
					}
					
					// check if the is avilable offer before limit finish
					if( $remainingOffers == "unlimited" or $remainingOffers >=1 ){

						// get admin data
						$adminData = DB::table("$customerData->database.admins")->where('pos_id', $request->employee_id)->first();
						if(isset($adminData)){$adminID = $adminData->id;}else{$adminID = "0";}
						// user data
						$userData = DB::table( $customerData->database.".users" )->where('u_id',$offerData->u_id)->first();
						// disable offer code
						DB::table($customerData->database.".campaign_statistics")->where( 'id', $offerData->id )->update(['state' => '1', 'updated_at' => $todayDateTime, 'a_id' => $adminID]);
						$autoReplyMsg = ['status' => 'REDEEMED'];
						$autoReplyMsg = json_encode($autoReplyMsg);
						// send Whatsapp Message to user
						$whatsappClass->Send(null, $userData->u_phone, "🎁 Offer code $request->code has been redeemed successfully.", $customerData->id, $customerData->database);
						
					}else{
						// offer limit has been reached
						$autoReplyMsg = "⚠ offer limit has been reached \n at:$offerData->updated_at\n by:".DB::table( $customerData->database.".admins" )->where('id',$offerData->a_id)->value('name');
					}
				}elseif( $offerData->state == "1" ){
					$autoReplyMsg = "⚠ offer used before \n At:$offerData->updated_at\n By:".DB::table( $customerData->database.".admins" )->where('id',$offerData->a_id)->value('name');
				}
			}else{
				$autoReplyMsg = "not found location ID in `pos_locations` table.";
			}
			
			return $autoReplyMsg;
		}
		
		
	}
}