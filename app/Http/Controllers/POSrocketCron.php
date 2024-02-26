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

class POSrocketCron extends Controller
{
    public function posRocketCron(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
		$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
		
		require_once '../config.php';
		

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

	
		
	}
}