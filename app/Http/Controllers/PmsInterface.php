<?php
namespace App\Http\Controllers;
// Taba
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

class PmsInterface extends Controller
{

	// // if we received Direct API from the PMS (NOT Applicable)
	// public function checkin(Request $request){
	// 	date_default_timezone_set("Africa/Cairo");
    //     $today = date("Y-m-d");
    //     $today_time = date("g:i a");
	// 	$todayDateTime = $today." ".date("H:i:s");
    //     if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 && isset($request->pmsId)){
			
	// 		$pms = DB::table($request->system.'.pms')->where('id', $request->pmsId)->first();
	// 		if(isset($pms)){

	// 			// check if this reservation exist in users table 
	// 			$userData = DB::table($request->system.".users")->where('pms_guest_id', $request->guestId)->first();
	// 			if(isset($userData)){ // user already registerd before
					
	// 				// check if user already active or not
	// 				if($userData->suspend != "0"){ 

	// 					// activate user again
	// 					DB::table($request->system.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'suspend' => '0' ]); 
	// 				}

	// 				// check if this user change theit room no or not
	// 				if($userData->pms_room_no != $request->roomNo){
						
	// 					// update master room no field 
	// 					DB::table($request->system.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'pms_room_no' => $request->roomNo ]);

	// 					// check if room no in login_username
	// 					if (strpos($pms->login_username, 'room_no') !== false) {
	// 						DB::table($request->system.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'u_uname' => $request->roomNo ]);
	// 					}

	// 					// check if room no in login_password
	// 					if (strpos($pms->login_password, 'room_no') !== false) {
	// 						DB::table($request->system.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'u_password' => $request->roomNo ]);
	// 					}
						
	// 				}
					
	// 			}else{ 
					
	// 				if(isset($request->guestTitle)){$title=$request->guestTitle;}else{$title="";}
	// 				if(isset($request->guestFirstName)){$firstname=$request->guestFirstName;}else{ $firstname=""; }
	// 				if(isset($request->guestLastName)){$lastname=$request->guestLastName;}else{ $lastname=""; }
	// 				if(isset($request->guestMobile) and $request->guestMobile!="-" ){ $mobile=$request->guestMobile; }else{ $mobile=null; }
	// 				if(isset($request->guestEmail) and $request->guestEmail!="-" ){ $email=$request->guestEmail; }else{ $email=" "; }
	// 				if(isset($request->guestBirthDate) and $request->guestBirthDate!="-" ){ $birthDate=$request->guestBirthDate; }else{ $birthDate=null; }
	// 				$fullName = $title." ".$firstname." ".$lastname;

	// 				// set login_username
	// 				if($pms->login_username=="room_no"){$username = (int)$request->roomNo;}
	// 				elseif($pms->login_username=="first_name"){$username = $request->guestFirstName;}
	// 				elseif($pms->login_username=="last_name"){$username = $request->guestLastName;}
	// 				elseif($pms->login_username=="mobile"){$username = $request->guestMobile;}
	// 				elseif($pms->login_username=="email"){$username = $request->guestEmail;}
	// 				elseif($pms->login_username=="birth_date"){$username = $request->guestBirthDate;}
					
	// 				// set login_password
	// 				if($pms->login_password=="room_no"){$password = (int)$request->roomNo;}
	// 				elseif($pms->login_password=="first_name"){$password = $request->guestFirstName;}
	// 				elseif($pms->login_password=="last_name"){$password = $request->guestLastName;}
	// 				elseif($pms->login_password=="mobile"){$password = $request->guestMobile;}
	// 				elseif($pms->login_password=="email"){$password = $request->guestEmail;}
	// 				elseif($pms->login_password=="birth_date"){$password = $request->guestBirthDate;}

	// 				// set gender
	// 				if($request->guestGender=="Male"){$gender = 1;}
	// 				elseif($request->guestGender=="Female"){$gender = 0;}
	// 				else{$gender = 2;}
					
	// 				$newUserID = DB::table("$request->system.users")->insertGetId([ 
	// 					'pms_id' => $pms->id,
	// 					'pms_guest_id' => $request->guestId, 
	// 					'pms_room_no' => $request->roomNo, 
	// 					'pms_reservation_id' => $request->reservationId, 
	// 					'u_email' => $email, 
	// 					'Registration_type' => '2', 
	// 					'u_state' => '1', 
	// 					'suspend' => '0', 
	// 					'u_name' => $fullName, 
	// 					'u_uname' => $username, 
	// 					'u_password' => $password, 
	// 					'u_phone' => $mobile, 
	// 					'birthdate' => $birthDate,
	// 					'u_country' => $request->guestCountry, 
	// 					'u_lang'=> $request->guestLanguage,
	// 					'u_gender' => $gender, 
	// 					// 'branch_id' => DB::table($request->system.".branches")->where('state','1')->value('id'), 
	// 					'branch_id' => $pms->id, // TABA
	// 					'network_id' => DB::table($request->system.".networks")->where('state','1')->value('id'), 
	// 					'group_id' => $pms->internet_group, 
	// 					'notes' => 'Account Type: '.$request->type.", Nationality: ".$request->guestNationality.", checkIn: ".$request->beginDate.", checkOut: ".$request->endDate, 
	// 					'created_at' => $todayDateTime]);
	// 			}

	// 			return json_encode(array('state' => 1, 'message' => 'Checking-in successfully.'));
	// 		}else{
	// 			return json_encode(array('state' => 0, 'message' => 'Invalid PMS ID.'));
	// 		}
	// 	}else{
    //         return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
    //     }
	// }

	// called from the PMS interface class -> `rowData` function after PMS interface send (GI) request to the NodeJS file and this file called the "rowData" faunction
	public function _checkin($system, $pmsId, $roomNo, $reservationId, $gTitle, $gFname, $gName, $gEmail, $gMobile, $gLanguage, $gBirthday, $gBirthday_ROW, $gNationality, $gGender, $classOfService, $arrivalDate, $departureDate, $guestGroupNumber, $guestUniqueIdentifier, $roomType, $shareMultipleRooms, $guestVipCategory, $confirmationNo){
		
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$today_time24 = date("H:i:s");
		$todayDateTime = $today." ".date("H:i:s");
        	
		$pms = DB::table($system.'.pms')->where('id', $pmsId)->first();
		if(isset($pms)){
		
			// make sure there is a UniqueIdentifier to avoid changing alot of users with pms_guest_id 0
			if(isset($guestUniqueIdentifier) and $guestUniqueIdentifier!="0" and $guestUniqueIdentifier !="" and $guestUniqueIdentifier !=" "){
				// $userData = DB::table($system.".users")->where('pms_reservation_id', $reservationId)->where('suspend', '0')->where('pms_room_no', $roomNo)->first(); // in this case we can't detect the returned guest
				$userData = DB::table($system.".users")->where('pms_guest_id', $guestUniqueIdentifier)->first(); 
				if(isset($userData)){ // user already registerd before

					// in case the PMS interface make database resync
					if($userData->pms_reservation_id == $reservationId){
						return json_encode(array('state' => 0, 'message' => "Checking-in is already registerd before for room ($roomNo), reservation id($reservationId)."));
					}

					// in this case we detect the returned guest but we will check if the reception check-in the same guest profile to two rooms, 
					if($userData->pms_room_no !="0"){
						// thats mean the guest profile aleady registerd in another checked-in profile
						// thats mean the reception checking-in the same guest profile to two rooms, 
						// and thats mean the first room will changed to the last room ,
						// so we will unset the user data to be able to register new room account
						print_r(json_encode(array('state' => 2, 'message' => "Guest profile aleady registerd in another checked-in profile ($guestUniqueIdentifier), thats mean the reception checking-in the same guest profile to two rooms($userData->pms_room_no, $roomNo), so will allow to register new room account for new room number ($roomNo)")));
						unset($userData);
					}
				}
			}else{
				return json_encode(array('state' => 0, 'message' => 'Checking-in failed because there is no guestUniqueIdentifier G+ '.$guestUniqueIdentifier.'.'));
			}
				
			if(isset($gTitle) and $gTitle!="" ){$title=$gTitle;}else{$title="";}
			if(isset($gFname) and $gFname!="" ){$firstname=$gFname;}else{ $firstname=""; }
			if(isset($gName) and $gName!="" ){$lastname=$gName;}else{ $lastname=""; }
			if(isset($gMobile) and $gMobile!="" ){ $mobile=$gMobile; }else{ $mobile=null; }
			if(isset($gEmail) and $gEmail!="" ){ $email=$gEmail; }else{ $email=" "; }
			if(isset($gBirthday) and $gBirthday!="" ){ $birthDate=$gBirthday; }else{ $birthDate=null; }
			$fullName = $title." ".$firstname." ".$lastname;

			// REFORMAT MOBILE: remove spaces, and dashes from mobile numbers, add (+)symbol if not exist, replace first two 00 to +
			if( $mobile!=null && $mobile!="" && strlen($mobile)>6 ){
				$mobile = str_replace("-", "", $mobile);  // remove dashes
				$mobile = str_replace("/", "", $mobile);  // remove shaches
				$mobile = str_replace("+", "", $mobile);  // remove plus symbole
				if(isset($mobile[0]) && isset($mobile[1]) && $mobile[0]=="0" && $mobile[1]=="0"){$mobile=substr($mobile, 2); } // remove first 00 
				// if(isset($mobile[0]) && $mobile[0]!="+"){$mobile = '+'.$mobile;} // add (+)symbol if not exist 
				if(isset($mobile[0]) && $mobile[0]=="+"){$mobile = '+'.$mobile;} // remove (+)symbol if exist to be able to send whatsapp and SMS
			}

			// Match first two charters of Country to the country name
			if($gNationality != ""){ $gNationality = Locale::getDisplayRegion('-' . $gNationality); }


			// set login_username
			if($pms->login_username=="room_no"){$username = (int)$roomNo;}
			elseif($pms->login_username=="first_name"){$username = $gFname;}
			elseif($pms->login_username=="last_name"){$username = $gName;}
			elseif($pms->login_username=="mobile"){$username = $gMobile;}
			elseif($pms->login_username=="email"){$username = $gEmail;}
			elseif($pms->login_username=="birth_date"){$username = $gBirthday_ROW;}
			elseif($pms->login_username=="reservation_no"){$username = $reservationId;}
			elseif($pms->login_username=="confirmation_no"){$username = $confirmationNo;}
			elseif($pms->login_username=="check_in_date"){$username = date("dmY", strtotime($arrivalDate));}
			elseif($pms->login_username=="check_out_date"){$username = date("dmY", strtotime($departureDate));}

			// set login_password
			if($pms->login_password=="room_no"){$password = (int)$roomNo;}
			elseif($pms->login_password=="first_name"){$password = $gFname;}
			elseif($pms->login_password=="last_name"){$password = $gName;}
			elseif($pms->login_password=="mobile"){$password = $gMobile;}
			elseif($pms->login_password=="email"){$password = $gEmail;}
			elseif($pms->login_password=="birth_date"){$password = $gBirthday_ROW;}
			elseif($pms->login_password=="reservation_no"){$password = $reservationId;}
			elseif($pms->login_password=="confirmation_no"){$password = $confirmationNo;}
			elseif($pms->login_password=="check_in_date"){$password = date("dmY", strtotime($arrivalDate));}
			elseif($pms->login_password=="check_out_date"){$password = date("dmY", strtotime($departureDate));}

			// if there is no Birthday or the password is empty for any reason we will replace password to the last name (small letters)
			if($password==""){$password = strtolower($gName);}

			// check if there is no gender identification to detect it from title
			if($gGender=="" && $gTitle!=""){
				if($gTitle=="Ms" or $gTitle=="Ms" or $gTitle=="Ms" or $gTitle=="F" ){$gGender = "Female";} // female
				elseif($gTitle=="Mr." or $gTitle=="M" ){$gGender = "Male";} // male
				else{$gGender = "Unknown";} // unknown
			}

			// set gender
			if($gGender=="Male" or $gGender=="M"){$gender = 1;}
			elseif($gGender=="Female" or $gGender=="F"){$gender = 0;}
			else{$gender = 2;}

			// set group ID
			if(isset($roomType) and $roomType!=""){
				$groupNameAsRoomType = DB::table($system.".area_groups")->where('name',$roomType)->first();
				if(isset($groupNameAsRoomType)){
					$finalGroupID = $groupNameAsRoomType->id;
				}
			}
			if(!isset($finalGroupID)){$finalGroupID = $pms->internet_group;}
	
			// check if this user new or returned customer
			if(isset($userData)){
				$finalUserID = $userData->u_id; 
				// this is a returned customer
				// prepare variables
				if( $email == " " or $email == $userData->u_email ){ $newEmail = $userData->u_email; }else{ $newEmail = $userData->u_email.','.$email; }
				if( $mobile == null or $mobile == $userData->u_phone ){ $newMobile = $userData->u_phone; }else{ $newMobile = $userData->u_phone.','.$mobile; }
				$newUpdates = 'New Room No: '.$roomNo.', New Class Of Service:'.$classOfService.", New checkIn: ".$arrivalDate.", New checkOut: ".$departureDate.", New Guest Group number: ".$guestGroupNumber.", New Room Type: ".$roomType.", New Guest Share Multiple Rooms: ".$shareMultipleRooms.", New Guest VIP category: ".$guestVipCategory.", New Guest birthday: ".$gBirthday.", New Reservation Number: ".$reservationId.", New Confirmation Number: ".$confirmationNo;
				$newNotes = $userData->notes. '; RETURNED GUEST '.$newUpdates;
				// update new fields with room number and reservation ID, etc...
				DB::table($system.".users")->where('pms_guest_id', $guestUniqueIdentifier)->limit(1)->update([ 
					'pms_id' => $pms->id,
					'pms_room_no' => $roomNo, 
					'pms_reservation_id' => $reservationId, 
					'u_email' => $newEmail, 
					'Registration_type' => '2', 
					'u_state' => '1', 
					'suspend' => '0', 
					'u_name' => $fullName, 
					'u_uname' => $username, 
					'u_password' => $password, 
					'u_phone' => $newMobile, 
					'birthdate' => $birthDate,
					'u_country' => $gNationality, 
					'u_lang'=> $gLanguage,
					'u_gender' => $gender, 
					// 'branch_id' => DB::table($system.".branches")->where('state','1')->value('id'), 
					'branch_id' => $pms->id, // TABA
					'network_id' => DB::table($system.".networks")->where('state','1')->value('id'), 
					'group_id' => $finalGroupID,
					'notes' => $newNotes,
					'updated_at' => $todayDateTime
				]); 

				// send unsuspend signal to remove any suspended mac from router (just in case)
				$searchController = new App\Http\Controllers\SearchController();
				$searchController->suspend($finalUserID, "true");

				// return JSON
				print_r( json_encode(array('state' => 0, 'message' => "Returned Guest ($guestUniqueIdentifier) Checked-in Successfully at($roomNo).")) );
			}else{
				// create new account
				$newUserID = DB::table("$system.users")->insertGetId([
					'pms_id' => $pms->id,
					'pms_guest_id' => $guestUniqueIdentifier,
					'pms_room_no' => $roomNo, 
					'pms_reservation_id' => $reservationId, 
					'u_email' => $email, 
					'Registration_type' => '2', 
					'u_state' => '1', 
					'suspend' => '0', 
					'u_name' => $fullName, 
					'u_uname' => $username, 
					'u_password' => $password, 
					'u_phone' => $mobile, 
					'birthdate' => $birthDate,
					'u_country' => $gNationality, 
					'u_lang'=> $gLanguage,
					'u_gender' => $gender, 
					// 'branch_id' => DB::table($system.".branches")->where('state','1')->value('id'), 
					'branch_id' => $pms->id, // TABA
					'network_id' => DB::table($system.".networks")->where('state','1')->value('id'), 
					'group_id' => $finalGroupID,
					'notes' => 'Room No: '.$roomNo.', Class Of Service:'.$classOfService.", Nationality: ".$gNationality.", checkIn: ".$arrivalDate.", checkOut: ".$departureDate.", Guest Group number: ".$guestGroupNumber.", Room Type: ".$roomType.", Guest Share Multiple Rooms: ".$shareMultipleRooms.", Guest VIP category: ".$guestVipCategory.", Guest birthday: ".$gBirthday.", Reservation Number: ".$reservationId.", Confirmation Number: ".$confirmationNo,
					'created_at' => $todayDateTime]);
					$finalUserID = $newUserID;
				print_r( json_encode(array('state' => 1, 'message' => $roomNo.' Checking-in successfully.')) );
			}
			
			// send WhatsApp bot menu
			$cronScheduleHotelGuestNotificationsStep1 = app()->make('App\Http\Controllers\CronScheduleHotelGuestNotificationsStep1');
			if( $cronScheduleHotelGuestNotificationsStep1->validateMobiles($mobile) >= '1' and DB::table("$system.settings")->where('type', 'sendWhatsAppBotMenuAfterCheckIn')->value('state') == '1' ){ 
				$customerData = DB::table('customers')->where('database',$system)->first();
				$whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
				$whatsappClass->sendWhatsappMenu($customerData->database, $customerData->id, $mobile, $todayDateTime);	
			}

			// insert tags
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_checkin_count', 'value'=> DB::table("$system.user_tags")->where('tag', 'pms_checkin_date')->where('pms_profile_id', $guestUniqueIdentifier)->count()+1 ]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_room_number', 'value'=> $roomNo]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_class_of_service', 'value' => $classOfService]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_nationality', 'value' => $gNationality]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_checkin_date', 'value' => $arrivalDate.' '.$today_time24]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_checkout_date', 'value' => $departureDate]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_group_number', 'value' => $guestGroupNumber]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_room_type', 'value' => $roomType]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_share_multiple_rooms', 'value' => $shareMultipleRooms]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_vip_category', 'value' => $guestVipCategory]);
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_title', 'value' => $title]);
			// calculate no of stays
			$now = time(); // or your date as well
			$datediff = strtotime($departureDate) - strtotime($arrivalDate);
			$stayNights = round($datediff / (60 * 60 * 24)); // no of stay nights
			DB::table("$system.user_tags")->insert(['u_id' => $finalUserID, 'pms_profile_id' => $guestUniqueIdentifier, 'tag' => 'pms_stay_nights', 'value' => $stayNights]);

		}else{
			return json_encode(array('state' => 0, 'message' => "Invalid PMS ID ($pmsId)"));
		}
		
	}

	// // if we received Direct API from the PMS (NOT Applicable)
	// public function checkout(Request $request){
	// 	date_default_timezone_set("Africa/Cairo");
    //     $today = date("Y-m-d");
    //     $today_time = date("g:i a");
	// 	$todayDateTime = $today." ".date("H:i:s");
    //     if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 && isset($request->pmsId)){
			
	// 		$pms = DB::table($request->system.'.pms')->where('id', $request->pmsId)->first();
	// 		if(isset($pms)){

	// 			// check if this reservation exist in users table 
	// 			$userData = DB::table($request->system.".users")->where('pms_reservation_id', $request->reservationId)->orWhere('pms_room_no', $request->roomNo)->first();
	// 			if(isset($userData)){ // user already registerd before
					
	// 				// diactivate all reservations or room number users
	// 				DB::table($request->system.".users")->where( 'pms_reservation_id', $request->reservationId )->orWhere('pms_room_no', $request->roomNo)->update([ 'suspend' => '1' ]); 
	// 				return json_encode(array('state' => 1, 'message' => 'Checking-out successfully.'));

	// 			}else{
	// 				return json_encode(array('state' => 0, 'message' => 'Reservation ID and Room number not found.'));
	// 			}

	// 		}else{
	// 			return json_encode(array('state' => 0, 'message' => 'Invalid PMS ID.'));
	// 		}
	// 	}else{
    //         return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
    //     }
	// }

	// called from the PMS interface class -> `rowData` function after PMS interface send (GI) request to the NodeJS file and this file called the "rowData" faunction
	public function _checkout($system, $pmsId, $roomNo){
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        	
		$pms = DB::table($system.'.pms')->where('id', $pmsId)->first();
		if(isset($pms)){

			// check if this reservation exist in users table 
			$userData = DB::table($system.".users")->where('pms_room_no', $roomNo)->where('pms_id', $pmsId)->first();
			if(isset($userData) and isset($roomNo) and $roomNo!="0" and $roomNo!="" and $roomNo!=" "){ // user already registerd before
				
				// diactivate all reservations or room number users
				// DB::table($system.".users")->where('pms_room_no', $roomNo)->update([ 'suspend' => '1' ]); 

				// // check if there is a registerd mobile number to replace username and password to them (TABA)
				// if(isset($userData) and strlen($userData->u_phone) > 8){
				// 	$finalUserAndPassword = str_replace("+","",$userData->u_phone);
				// }else{
				// 	$finalUserAndPassword = rand(11111,99999);
				// }
				
				// $finalUserName = "Checked Out".$roomNo;
				// $finalPassword = $todayDateTime;
				$finalUserName = $userData->u_name.", Checked Out".$roomNo." at:".$todayDateTime;
				
				// update checkout group_id, remove username and password and room number and reservation_id
				// DB::table($system.".users")->where('pms_room_no', $roomNo)->where('pms_id', $pmsId)->update([ 'group_id' => $pms->checkout_group, 'pms_id' => '0',  'pms_room_no' => '0',  'pms_reservation_id' => '0', 'u_uname' => $finalUserName, 'u_password' => $finalPassword, 'updated_at' => $todayDateTime ]);
				DB::table($system.".users")->where('pms_room_no', $roomNo)->where('pms_id', $pmsId)->update([ 'group_id' => $pms->checkout_group, 'pms_id' => '0',  'pms_room_no' => '0',  'pms_reservation_id' => '0', 'u_name' => $finalUserName, 'updated_at' => $todayDateTime ]);
				
				// update check out tag to the current checkout time and re-calculate stay nights
				$now = time(); // or your date as well
				$lastCheckInDate = strtotime( DB::table("$system.user_tags")->where('tag', 'pms_checkin_date')->where('pms_profile_id', $userData->pms_guest_id)->orderBy('id', 'desc')->value('value') );
				$datediff = $now - $lastCheckInDate;
				$stayNights = round($datediff / (60 * 60 * 24)); // no of stay nights
				DB::table("$system.user_tags")->where('tag', 'pms_checkout_date')->where('pms_profile_id', $userData->pms_guest_id)->orderBy('id', 'desc')->update([ 'value' => $todayDateTime ]);
				DB::table("$system.user_tags")->where('tag', 'pms_stay_nights')->where('pms_profile_id', $userData->pms_guest_id)->orderBy('id', 'desc')->update([ 'value' => $stayNights ]);
				
				// check for check-out AL letter
				if(DB::table("$system.campaigns")->where('type', 'guestCheckout')->value( 'state') == "1"){
					// check for check-out Email
					// $cronScheduleHotelGuestNotificationsStep1 = new App\Http\Controllers\CronScheduleHotelGuestNotificationsStep1();
					$cronScheduleHotelGuestNotificationsStep1 = app()->make('App\Http\Controllers\CronScheduleHotelGuestNotificationsStep1');
					$guestCheckoutEmail = DB::table("$system.settings")->where('type', 'guestCheckoutEmail')->first();
					if(isset( $guestCheckoutEmail ) and $guestCheckoutEmail->state == "1"){
						if(isset($userData->u_email) and $cronScheduleHotelGuestNotificationsStep1->validateEmails($userData->u_email) >= '1' ){ DB::table("$system.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $system, 'u_id' => $userData->u_id, 'email' => $userData->u_email, 'chatgpt_content' => $guestCheckoutEmail->value, 'by' => 'guestCheckoutEmailPmsInterface', 'reason'=>"Guest $userData->u_name `$userData->u_id` checked-out $today.", 'created_at'=>$todayDateTime]]);  }
					}
		
					// check for check-out Whatsapp
					$guestCheckoutWhatsapp = DB::table("$system.settings")->where('type', 'guestCheckoutWhatsapp')->first();
					if(isset( $guestCheckoutWhatsapp ) and $guestCheckoutWhatsapp->state == "1"){
						if(isset($userData->u_phone) and $cronScheduleHotelGuestNotificationsStep1->validateMobiles($userData->u_phone) >= '1' ){ DB::table("$system.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $system, 'u_id' => $userData->u_id, 'mobile' => $userData->u_phone, 'chatgpt_content' => $guestCheckoutWhatsapp->value, 'by' => 'guestCheckoutWhatsappPmsInterface', 'reason'=>"Guest $userData->u_name `$userData->u_id` checked-out $today.", 'created_at'=>$todayDateTime]]); }
					}
		
					// check for check-out SMS
					$guestCheckoutSMS = DB::table("$system.settings")->where('type', 'guestCheckoutSMS')->first();
					if(isset( $guestCheckoutSMS ) and $guestCheckoutSMS->state == "1"){
						if(isset($userData->u_phone) and $cronScheduleHotelGuestNotificationsStep1->validateMobiles($userData->u_phone) >= '1' ){ DB::table("$system.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $system, 'u_id' => $userData->u_id, 'mobile' => $userData->u_phone, 'chatgpt_content' => $guestCheckoutSMS->value, 'by' => 'guestCheckoutSMSPmsInterface', 'reason'=>"Guest $userData->u_name `$userData->u_id` checked-out $today.", 'created_at'=>$todayDateTime]]); }
					}
				}
				// sending checkout notification
				// DB::table($system.".users")->where('pms_room_no', $roomNo)->delete(); 
				return json_encode(array('state' => 1, 'message' => "Checking-out successfully for room ($roomNo)."));

			}else{
				return json_encode(array('state' => 0, 'message' => "Room number ($roomNo) not found to Check-Out."));
			}

		}else{
			return json_encode(array('state' => 0, 'message' => "Invalid PMS ID ($pmsId)."));
		}
	}

	// // if we received Direct API from the PMS (NOT Applicable)
	// public function changeRoom(Request $request){
	// 	date_default_timezone_set("Africa/Cairo");
    //     $today = date("Y-m-d");
    //     $today_time = date("g:i a");
	// 	$todayDateTime = $today." ".date("H:i:s");
    //     if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 && isset($request->pmsId)){
			
	// 		$pms = DB::table($request->system.'.pms')->where('id', $request->pmsId)->first();
	// 		if(isset($pms)){

	// 			// check if this reservation exist in users table 
	// 			$userData = DB::table($request->system.".users")->where('pms_reservation_id', $request->reservationId)->orWhere('pms_room_no', $request->oldRoomNo)->first();
	// 			if(isset($userData)){ // user already registerd before
					
	// 				// check if room no in login_username
	// 				if (strpos($pms->login_username, 'room_no') !== false) {
	// 					DB::table($request->system.".users")->where( 'pms_reservation_id', $request->reservationId )->orWhere('pms_room_no', $request->oldRoomNo)->update([ 'u_uname' => $request->newRoomNo ]);
	// 				}
					
	// 				// check if room no in login_password
	// 				if (strpos($pms->login_password, 'room_no') !== false) {
	// 					DB::table($request->system.".users")->where( 'pms_reservation_id', $request->reservationId )->orWhere('pms_room_no', $request->oldRoomNo)->update([ 'u_password' => $request->newRoomNo ]);
	// 				}

	// 				// diactivate all reservations or room number users
	// 				DB::table($request->system.".users")->where( 'pms_reservation_id', $request->reservationId )->orWhere('pms_room_no', $request->oldRoomNo)->update([ 'pms_room_no' => $request->newRoomNo ]); 


	// 				return json_encode(array('state' => 1, 'message' => 'Room number has been changed successfully.'));

	// 			}else{
	// 				return json_encode(array('state' => 0, 'message' => 'Reservation ID and Room number not found.'));
	// 			}

	// 		}else{
	// 			return json_encode(array('state' => 0, 'message' => 'Invalid PMS ID.'));
	// 		}
	// 	}else{
    //         return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
    //     }
	// }

	// public function _changeRoom($system, $pmsId, $roomNo, $reservationId, $gName, $gLanguage, $guestGroupNumber, $arrivalDate, $departureDate, $classOfService, $shareMultipleRooms, $oldRoomNo, $gTitle, $guestUniqueIdentifier, $roomType, $guestVipCategory){
	public function _changeRoom($system, $pmsId, $roomNo, $reservationId, $gTitle, $gFname, $gName, $gEmail, $gMobile, $gLanguage, $gBirthday, $gBirthday_ROW, $gNationality, $gGender, $classOfService, $arrivalDate, $departureDate, $guestGroupNumber, $guestUniqueIdentifier, $roomType, $shareMultipleRooms, $guestVipCategory, $confirmationNo, $oldRoomNo){
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        
			$pms = DB::table($system.'.pms')->where('id', $pmsId)->first();
			if(isset($pms) and isset($reservationId) and $reservationId!="0" and $reservationId!="" and $reservationId!=" "){

				// check if this reservation exist in users table 
				$userData = DB::table($system.".users")->where('pms_reservation_id', $reservationId)->where('pms_id', $pmsId)->first();
				if(isset($userData)){ // user already registerd before
					
					// check if room no in login_username
					if (strpos($pms->login_username, 'room_no') !== false) {
						DB::table($system.".users")->where( 'pms_reservation_id', $reservationId )->where('pms_id', $pmsId)->limit(1)->update([ 'u_uname' => $roomNo ]);
					}
					
					// check if room no in login_password
					if (strpos($pms->login_password, 'room_no') !== false) {
						DB::table($system.".users")->where( 'pms_reservation_id', $reservationId )->where('pms_id', $pmsId)->limit(1)->update([ 'u_password' => $roomNo ]);
					}

					// set group ID according to guest type
					if(isset($roomType) and $roomType!=""){
						$groupNameAsRoomType = DB::table($system.".area_groups")->where('name',$roomType)->first();
						if(isset($groupNameAsRoomType)){
							$finalGroupID = $groupNameAsRoomType->id;
						}
					}
					if(!isset($finalGroupID)){$finalGroupID = $userData->group_id;}

					// insert new updates
					$newUpdates = 'New Room No: '.$roomNo.', New Class Of Service:'.$classOfService.", New checkIn: ".$arrivalDate.", New checkOut: ".$departureDate.", New Guest Group number: ".$guestGroupNumber.", New Room Type: ".$roomType.", New Guest Share Multiple Rooms: ".$shareMultipleRooms.", New Guest VIP category: ".$guestVipCategory.", New Guest birthday: ".$gBirthday.", New Reservation Number: ".$reservationId.", New Confirmation Number: ".$confirmationNo;
					$newNotes = $userData->notes. '; Room or profile has been Modified, old room number '.$userData->pms_room_no.', new room number '.$roomNo.', '.$newUpdates;

					// // change room number field
					// DB::table($system.".users")->where( 'pms_reservation_id', $reservationId )->update([ 'pms_room_no' => $roomNo, 'notes'=> $newNotes, 'group_id'=> $finalGroupID ]); 
					
					// prepare variables
					if(isset($gTitle) and $gTitle!="" ){$title=$gTitle;}else{$title="";}
					if(isset($gFname) and $gFname!="" ){$firstname=$gFname;}else{ $firstname=""; }
					if(isset($gName) and $gName!="" ){$lastname=$gName;}else{ $lastname=""; }
					if(isset($gMobile) and $gMobile!="" ){ $mobile=$gMobile; }else{ $mobile=null; }
					if(isset($gEmail) and $gEmail!="" ){ $email=$gEmail; }else{ $email=" "; }
					if(isset($gBirthday) and $gBirthday!="" ){ $birthDate=$gBirthday; }else{ $birthDate=null; }
					$fullName = $title." ".$firstname." ".$lastname;
					// set login_username
					if($pms->login_username=="room_no"){$username = (int)$roomNo;}
					elseif($pms->login_username=="first_name"){$username = $gFname;}
					elseif($pms->login_username=="last_name"){$username = $gName;}
					elseif($pms->login_username=="mobile"){$username = $gMobile;}
					elseif($pms->login_username=="email"){$username = $gEmail;}
					elseif($pms->login_username=="birth_date"){$username = $gBirthday_ROW;}
					elseif($pms->login_username=="reservation_no"){$username = $reservationId;}
					elseif($pms->login_username=="confirmation_no"){$username = $confirmationNo;}
					elseif($pms->login_username=="check_in_date"){$username = date("dmY", strtotime($arrivalDate));}
					elseif($pms->login_username=="check_out_date"){$username = date("dmY", strtotime($departureDate));}

					// set login_password
					if($pms->login_password=="room_no"){$password = (int)$roomNo;}
					elseif($pms->login_password=="first_name"){$password = $gFname;}
					elseif($pms->login_password=="last_name"){$password = $gName;}
					elseif($pms->login_password=="mobile"){$password = $gMobile;}
					elseif($pms->login_password=="email"){$password = $gEmail;}
					elseif($pms->login_password=="birth_date"){$password = $gBirthday_ROW;}
					elseif($pms->login_password=="reservation_no"){$password = $reservationId;}
					elseif($pms->login_password=="confirmation_no"){$password = $confirmationNo;}
					elseif($pms->login_password=="check_in_date"){$password = date("dmY", strtotime($arrivalDate));}
					elseif($pms->login_password=="check_out_date"){$password = date("dmY", strtotime($departureDate));}
					
					// REFORMAT MOBILE: remove spaces, and dashes from mobile numbers, add (+)symbol if not exist, replace first two 00 to +
					if( $mobile!=null && $mobile!="" && strlen($mobile)>6 ){
						$mobile = str_replace("-", "", $mobile);  // remove dashes
						$mobile = str_replace("/", "", $mobile);  // remove shaches
						$mobile = str_replace("+", "", $mobile);  // remove plus symbole
						if(isset($mobile[0]) && isset($mobile[1]) && $mobile[0]=="0" && $mobile[1]=="0"){$mobile=substr($mobile, 2); } // remove first 00 
						// if(isset($mobile[0]) && $mobile[0]!="+"){$mobile = '+'.$mobile;} // add (+)symbol if not exist 
						if(isset($mobile[0]) && $mobile[0]=="+"){$mobile = '+'.$mobile;} // remove (+)symbol if exist to be able to send whatsapp and SMS
					}

					// Match first two charters of Country to the country name
					if($gNationality != ""){ $gNationality = Locale::getDisplayRegion('-' . $gNationality); }
					
					// if there is no Birthday or the password is empty for any reason we will replace password to the last name (small letters)
					if($password==""){$password = strtolower($gName);}

					// check if there is no gender identification to detect it from title
					if($gGender=="" && $gTitle!=""){
						if($gTitle=="Ms" or $gTitle=="Ms" or $gTitle=="Ms" or $gTitle=="F" ){$gGender = "Female";} // female
						elseif($gTitle=="Mr." or  $gTitle=="M"){$gGender = "Male";} // male
						else{$gGender = "Unknown";} // unknown
					}
					// set gender
					if($gGender=="Male" or $gGender=="M"){$gender = 1;}
					elseif($gGender=="Female" or $gGender=="F"){$gender = 0;}
					else{$gender = 2;}
					// set group ID
					if(isset($roomType) and $roomType!=""){
						$groupNameAsRoomType = DB::table($system.".area_groups")->where('name',$roomType)->first();
						if(isset($groupNameAsRoomType)){
							$finalGroupID = $groupNameAsRoomType->id;
						}
					}
					if(!isset($finalGroupID)){$finalGroupID = $pms->internet_group;}
					
					$emails =  $userData->u_email;
					$emailArray = explode(",", trim($emails));

					if($emailArray[0] == ""){
						if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$emailArray[0] = $email;
						}
						//$emailArray[] = "m@m";
					}else if (in_array($email, $emailArray)) {

					}else{
						if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$emailArray[] = $email;
						}
					}
					$newEmail = implode(",",$emailArray);

					$mobiles =  $userData->u_phone;
					$moliesArray = explode(",", trim($mobiles));

					if($moliesArray[0] == ""){
							$moliesArray[0] = $mobile;
						//$emailArray[] = "m@m";
					}else if (in_array($mobile, $moliesArray)) {

					}else{
							$moliesArray[] = $mobile;
					}
					$newMobile = implode(",",$moliesArray);
					//return $newEmail;
					//if( $email == " " or $email == $userData->u_email ){ $newEmail = $userData->u_email; }else{ $newEmail = $userData->u_email.','.$email; }
					//if( $mobile == "" or $mobile == $userData->u_phone ){ $newMobile = $userData->u_phone; }else{ $newMobile = $userData->u_phone.','.$mobile; }
					
					// update new fields with room number and reservation ID, etc...
					DB::table($system.".users")->where( 'pms_reservation_id', $reservationId )->where('pms_id', $pmsId)->update([ 
						'pms_room_no' => $roomNo, 
						// 'pms_reservation_id' => $reservationId, 
						'pms_guest_id' => $guestUniqueIdentifier,
						'u_email' => $newEmail, 
						'u_phone' => $newMobile, 
						// 'Registration_type' => '2', 
						// 'u_state' => '1', 
						// 'suspend' => '0', 
						'u_name' => $fullName, 
						'u_uname' => $username, 
						'u_password' => $password, 
						'birthdate' => $birthDate,
						'u_country' => $gNationality, 
						'u_lang'=> $gLanguage,
						'u_gender' => $gender, 
						// 'branch_id' => DB::table($system.".branches")->where('state','1')->value('id'), 
						'branch_id' => $pms->id, // TABA
						'network_id' => DB::table($system.".networks")->where('state','1')->value('id'), 
						'group_id' => $finalGroupID,
						'notes' => $newNotes,
						'updated_at' => $todayDateTime
					]); 

					return json_encode(array('state' => 1, 'message' => "Room ($roomNo) or profile ($guestUniqueIdentifier) has been modified successfully with reservation id ($reservationId)."));

				}else{
					// Reservation ID not found to be able to change room, so we will coverting from GC To GI
					print_r( json_encode(array('state' => 0, 'message' => "Reservation ID ($reservationId) not found to be able to change room ($roomNo), so we will coverting from GC To GI.")) );
					print_r( $this->_checkin($system, $pmsId, $roomNo, $reservationId, $gTitle, $gFname, $gName, $gEmail, $gMobile, $gLanguage, $gBirthday, $gBirthday_ROW, $gNationality, $gGender, $classOfService, $arrivalDate, $departureDate, $guestGroupNumber, $guestUniqueIdentifier, $roomType, $shareMultipleRooms, $guestVipCategory, $confirmationNo) );
					return json_encode(array('state' => 1, 'message' => 'Coverted from GC To GI successfully.'));
				}

			}else{
				return json_encode(array('state' => 0, 'message' => "Invalid PMS ID ($pmsId) or Invalid reservation id ($reservationId)."));
			}
	}


	public function pullInvoices(Request $request){
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 && isset($request->pmsId)){
			
			$pms = DB::table($request->system.'.pms')->where('id', $request->pmsId)->first();
			// update last check 
			// DB::table($request->system.".pms")->where( 'id', $pms->id )->update([ 'last_check' => $todayDateTime ]); 
			if(isset($pms)){

				$invoices = DB::table("$request->system.pms_invoices")->where('pms_id', $pms->id)->where('delivered', '0')->limit(1)->get();
				if(isset($invoices) and count($invoices) > 0){
					
					foreach( $invoices as $key => $value ){
						// $value->room_no = DB::table($request->system.'.users')->where('u_id', $value->user_id)->value('pms_room_no');
						// invoice inserted successfully, so we will update delevery date of the invoice
						// DB::table($request->system.".pms_invoices")->where( 'id', $value->id )->update([ 'delivered' => '1', 'delivered_at' => $todayDateTime ]); 

						$value->price = $value->price*100;
					}
					DB::table($request->system.".pms_invoices")->where( 'id', $invoices[0]->id )->update([ 'delivered' => '1', 'delivered_at' => $todayDateTime ]); 

					return json_encode(array('state' => 1, 'message' => 'Invoices sent successfully.', 'data' => $invoices));
				}else{
					return json_encode(array('state' => 0, 'message' => 'There is no new invoices.'));
				}	

			}else{
				return json_encode(array('state' => 0, 'message' => 'Invalid PMS ID.'));
			}
		}else{
            return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
        }
	}

	/*
    public function pull(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		
		// $operaGetReservationsQuery = "select trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID  and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA  from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END  and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";
		// $operaGetReservationsQuery = "select trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no ,'-') TEL,nvl(a.email,'-') EMAIL from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID  and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no,'-') TEL,nvl(a.email,'-') EMAIL  from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END  and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";
		$operaGetReservationsQuery  ="select trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_ANME,upper(A.FIRST) FIRST_NANME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no ,'-') TEL,nvl(a.email,'-') EMAIL from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID  and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,c.RESV_NAME_ID,a.name_id,upper(A.NAME) LAST_ANME,upper(A.FIRST) FIRST_NANME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA,nvl(to_char(a.birth_date,'dd/mm/yyy'),'-') birth_date,nvl(a.phone_no,'-') TEL,nvl(a.email,'-') EMAIL  from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END  and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";
		$suite8GetReservationsQuery ="select YS.YRMS_SHORTDESC ROOM_NO, case when YS.YRES_XCMS_ID=XC.XCMS_ID then 'Main' else 'Accompany' end as type, XC.XCMS_ID NAME_ID, lower(nvl(XC.XCMS_NAME1,'-')) LAST_NAME,lower(nvl(XC.XCMS_NAME3,'-')) FIRST_NAME, to_char(ys.YRES_EXPARRTIME,'dd/mm/yyyy') BEGIN_DATE,to_char(ys.YRES_EXPDEPTIME,'dd/mm/yyyy') END_DATE,(SELECT nvl(XCID.XCID_ADDRGREET,'-') FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) TITLE,(SELECT case when XCID.XCID_SEX=1 then 'M' when XCID.XCID_SEX=2 then 'F' else '-' end   FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) GENDER,(select nvl(X.NATIONALITY,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID )  NATIONALITY,(select nvl(X.XCOU_LONGDESC,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID )  COUNTRY,(SELECT nvl(WLAN.WLAN_LONGDESC,'-') FROM WLAN WHERE WLAN.WLAN_ID = XC.XCMS_WLAN_ID ) LANGUAGE,nvl(YS.TRAVELAGENT_NAME||YS.COMPANY_NAME,'-') TA,nvl(to_char((SELECT XCID.XCID_BIRTHTIME FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID),'dd/mm/yyyy'),'-') birth_date,nvl((select LOWER(N.XCOM_VALUE) from XCOM n,XCMT t where N.XCOM_XCMT_ID=T.XCMT_ID and T.XCMT_TYPE=4 and N.XCOM_PRIMARY=1 and N.XCOM_XCMS_ID=XC.XCMS_ID),'-') TEL,nvl((select LOWER(N.XCOM_VALUE) from XCOM n,XCMT t where N.XCOM_XCMT_ID=T.XCMT_ID and T.XCMT_TYPE=1 and N.XCOM_PRIMARY=1 and N.XCOM_XCMS_ID=XC.XCMS_ID),'-') EMAIL from xcms xc,YRPL yr ,V8_REP_YRES_INFOS YS where  YS.INHOUSE=1 and  YS.YRES_RESSTATUS=1 and YS.YRES_NOAVAILREASON=0 and xc.XCMS_ID=YR.YRPL_XCMS_ID and YR.YRPL_YRES_ID=YS.YRES_ID";
		$protel8GetReservationsQuery ="select (select zim.ziname  from zimmer zim where bu.zimmernr =zim.zinr) ROOM_NO, 'Main' type, bu.kundennr name_id, upper((select case when kun.name1='' then '-' else kun.name1 end from kunden kun where kun.kdnr=bu.kundennr )) LAST_NAME, upper((select case when kun.vorname='' then '-' else kun.vorname end from kunden kun where kun.kdnr=bu.kundennr )) FIRST_NAME, CONVERT(VARCHAR(10), bu.globdvon, 103) BEGIN_DATE, CONVERT(VARCHAR(10), bu.globdbis, 103) END_DATE, (select case when kun.titel ='' then '-' else kun.titel end from kunden kun where kun.kdnr=bu.kundennr ) TITLE, (select CASE WHEN kun.gender = 2 THEN 'Male' WHEN kun.gender = 1 THEN 'Female' ELSE '-' END  from kunden kun where kun.kdnr=bu.kundennr ) GENDER, isnull((select max(nat.land) from kunden kun,natcode nat where kun.kdnr=bu.kundennr and nat.codenr=kun.nat ),'-') NATIONALITY, isnull((select max(nat.land) from kunden kun,natcode nat where kun.kdnr=bu.kundennr and nat.codenr=kun.landkz ),'-') COUNTRY, isnull((select max(sp.name) from kunden kun,sprache sp where kun.kdnr=bu.kundennr and kun.sprache=sp.nr),'-') LANGUAGE from buch bu where bu.BUCHSTATUS=1";

		$operaInsertInvoiceQuery = "select trim(' ' from C.ROOM_NO) ROOM_NO,'Main' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.RESV_NAME_ID= C.RESV_NAME_ID  and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END and c.ROOM_CATEGORY>0 union all select trim(' ' from C.ROOM_NO) ROOM_NO,'Accompany' type,a.name_id,upper(A.NAME) LAST_NAME,upper(A.FIRST) FIRST_NAME,to_char(B.BEGIN_DATE,'dd/mm/yyyy') BEGIN_DATE,to_char(B.END_DATE,'dd/mm/yyyy') END_DATE,nvl(A.TITLE,'-') TITLE,nvl(A.GENDER,'-') GENDER,nvl(A.NATIONALITY_DESC,'-') NATIONALITY,nvl(A.COUNTRY_DESC,'-') COUNTRY,nvl(A.LANGUAGE_DESC,'-') LANGUAGE,nvl(nvl(C.TRAVEL_AGENT_NAME,C.COMPANY_NAME),'-') TA  from NAME_VIEW a, RESERVATION_NAME b ,rep_reservation_all_view c where a.name_id=b.name_id and C.RESV_STATUS in ('CHECKED IN','DUE IN','DUE OUT') and B.PARENT_RESV_NAME_ID= C.RESV_NAME_ID and B.TRUNC_BEGIN_DATE =C.TRUNC_BEGIN and B.TRUNC_END_DATE=C.TRUNC_END  and c.ROOM_CATEGORY>0 order by ROOM_NO,name_id";
		$suite8InsertInvoiceQuery ="select YS.YRMS_SHORTDESC ROOM_NO, case when YS.YRES_XCMS_ID=XC.XCMS_ID then 'Main' else 'Accompany' end as type, XC.XCMS_ID NAME_ID, lower(nvl(XC.XCMS_NAME1,'-')) LAST_NAME,lower(nvl(XC.XCMS_NAME3,'-')) FIRST_NAME, to_char(ys.YRES_EXPARRTIME,'dd/mm/yyyy') BEGIN_DATE,to_char(ys.YRES_EXPDEPTIME,'dd/mm/yyyy') END_DATE,(SELECT nvl(XCID.XCID_ADDRGREET,'-') FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) TITLE,(SELECT case when XCID.XCID_SEX=1 then 'M' when XCID.XCID_SEX=2 then 'F' else '-' end   FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID ) GENDER,(select nvl(X.NATIONALITY,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID )  NATIONALITY,(select nvl(X.XCOU_LONGDESC,'-') from V8_REP_XCMS_INFOS x where XC.XCMS_ID=X.XCMS_ID )  COUNTRY,(SELECT nvl(WLAN.WLAN_LONGDESC,'-') FROM WLAN WHERE WLAN.WLAN_ID = XC.XCMS_WLAN_ID ) LANGUAGE,nvl(YS.TRAVELAGENT_NAME||YS.COMPANY_NAME,'-') TA,nvl(to_char((SELECT XCID.XCID_BIRTHTIME FROM XCID WHERE XCID.XCID_ID = XC.XCMS_ID),'dd/mm/yyyy'),'-') birth_date,nvl((select LOWER(N.XCOM_VALUE) from XCOM n,XCMT t where N.XCOM_XCMT_ID=T.XCMT_ID and T.XCMT_TYPE=4 and N.XCOM_PRIMARY=1 and N.XCOM_XCMS_ID=XC.XCMS_ID),'-') TEL,nvl((select LOWER(N.XCOM_VALUE) from XCOM n,XCMT t where N.XCOM_XCMT_ID=T.XCMT_ID and T.XCMT_TYPE=1 and N.XCOM_PRIMARY=1 and N.XCOM_XCMS_ID=XC.XCMS_ID),'-') EMAIL from xcms xc,YRPL yr ,V8_REP_YRES_INFOS YS where  YS.INHOUSE=1 and  YS.YRES_RESSTATUS=1 and YS.YRES_NOAVAILREASON=0 and xc.XCMS_ID=YR.YRPL_XCMS_ID and YR.YRPL_YRES_ID=YS.YRES_ID";
		$protel8InsertInvoiceQuery ="select (select zim.ziname  from zimmer zim where bu.zimmernr =zim.zinr) ROOM_NO, 'Main' type, bu.kundennr name_id, upper((select case when kun.name1='' then '-' else kun.name1 end from kunden kun where kun.kdnr=bu.kundennr )) LAST_NAME, upper((select case when kun.vorname='' then '-' else kun.vorname end from kunden kun where kun.kdnr=bu.kundennr )) FIRST_NAME, CONVERT(VARCHAR(10), bu.globdvon, 103) BEGIN_DATE, CONVERT(VARCHAR(10), bu.globdbis, 103) END_DATE, (select case when kun.titel ='' then '-' else kun.titel end from kunden kun where kun.kdnr=bu.kundennr ) TITLE, (select CASE WHEN kun.gender = 2 THEN 'Male' WHEN kun.gender = 1 THEN 'Female' ELSE '-' END  from kunden kun where kun.kdnr=bu.kundennr ) GENDER, isnull((select max(nat.land) from kunden kun,natcode nat where kun.kdnr=bu.kundennr and nat.codenr=kun.nat ),'-') NATIONALITY, isnull((select max(nat.land) from kunden kun,natcode nat where kun.kdnr=bu.kundennr and nat.codenr=kun.landkz ),'-') COUNTRY, isnull((select max(sp.name) from kunden kun,sprache sp where kun.kdnr=bu.kundennr and kun.sprache=sp.nr),'-') LANGUAGE from buch bu where bu.BUCHSTATUS=1";

		require_once '../config.php';

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
		
		
		$allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            // check if PMS integration is on
			if(DB::table($request->guestId.'.settings')->where('type', 'pms_integration')->value('state') == 1){
				
				// check if there is active PMS integration
				foreach(DB::table($request->guestId.'.pms')->where('state', '1')->get() as $pms){

					// check if it is database direct connection or through interface
					if($pms->connection_type == 'database'){
						
						// connect to oracle database
						$db = "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = $pms->db_ip)(PORT = $pms->db_port))(CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME = $pms->db_name)))";
						if($connection = @OCILogon($pms->db_username, $pms->db_password, $db))
						{
							// update last check 
							// DB::table($request->guestId.".pms")->where( 'id', $pms->id )->update([ 'last_check' => $todayDateTime ]); 
							// execute query to get reservations
							if($pms->type == 'opera'){
								$stid = @oci_parse($connection, $operaGetReservationsQuery);
							}elseif($pms->type == 'suite8'){
								$stid = @oci_parse($connection, $suite8GetReservationsQuery);
							}elseif($pms->type == 'protel'){
								$stid = @oci_parse($connection, $protelGetReservationsQuery);
							}
							@oci_execute($stid);
							if(isset($stid)){
								$guestsId=array();
								// delete latest records
								DB::statement("TRUNCATE TABLE $request->guestId.pms_reservations;");
								
								while ($row = @oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){

									// build guests array to compare between current users to detect checkout reservations
									array_push($guestsId, $request->guestId);
									

									//insert new reach
									DB::table($request->guestId.".pms_reservations")->insert([
										'pms_id' => $pms->id
										, 'room_no' => $request->roomNo
										, 'begin_date' => date("Y-m-d", strtotime( str_replace('/', '-', $row['BEGIN_DATE']) ) )
										, 'end_date' => date("Y-m-d", strtotime( str_replace('/', '-',$row['END_DATE']) ) )
										, 'type' => $request->type
										, 'guest_id' => $request->guestId
										, 'title' => $request->guestTitle
										, 'first_name' => $request->guestFirstName
										, 'last_name' => $request->guestLastName
										, 'gender' => $request->guestGender
										, 'nationality' => $request->guestNationality
										, 'country' => $request->guestCountry
										, 'mobile' => $request->guestMobile
										, 'email' => $request->guestEmail
										, 'birth_date' => $request->guestBirthDate
										, 'language' => $request->guestLanguage
										, 'ta' => $row['TA']
										, 'created_at' => $todayDateTime]);

									// check if this reservation exist in users table 
									$userData = DB::table($request->guestId.".users")->where('pms_guest_id', $request->guestId)->first();
									if(isset($userData)){ // user already registerd before
										
										// check if user already active or not
										if($userData->suspend != "0"){ 

											// activate user again
											DB::table($request->guestId.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'suspend' => '0' ]); 
										}

										// check if this user change theit room no or not
										if($userData->pms_room_no != $request->roomNo){
											
											// update master room no field 
											DB::table($request->guestId.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'pms_room_no' => $request->roomNo ]);

											// check if room no in login_username
											if (strpos($pms->login_username, 'room_no') !== false) {
												DB::table($request->guestId.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'u_uname' => $request->roomNo ]);
											}

											// check if room no in login_password
											if (strpos($pms->login_password, 'room_no') !== false) {
												DB::table($request->guestId.".users")->where( 'pms_guest_id', $request->guestId )->update([ 'u_password' => $request->roomNo ]);
											}
											
										}
										
									}else{ 
										
										if(isset($request->guestTitle)){$title=$request->guestTitle;}else{$title="";}
										if(isset($request->guestFirstName)){$firstname=$request->guestFirstName;}else{ $firstname=""; }
										if(isset($request->guestLastName)){$lastname=$request->guestLastName;}else{ $lastname=""; }
										if(isset($request->guestMobile) and $request->guestMobile!="-" ){ $mobile=$request->guestMobile; }else{ $mobile=null; }
										if(isset($request->guestEmail) and $request->guestEmail!="-" ){ $email=$request->guestEmail; }else{ $email=" "; }
										if(isset($request->guestBirthDate) and $request->guestBirthDate!="-" ){ $birthDate=$request->guestBirthDate; }else{ $birthDate=null; }
										$fullName = $title." ".$firstname." ".$lastname;

										// set login_username
										if($pms->login_username=="room_no"){$username = (int)$request->roomNo;}
										elseif($pms->login_username=="first_name"){$username = $request->guestFirstName;}
										elseif($pms->login_username=="last_name"){$username = $request->guestLastName;}
										elseif($pms->login_username=="mobile"){$username = $request->guestMobile;}
										elseif($pms->login_username=="email"){$username = $request->guestEmail;}
										elseif($pms->login_username=="birth_date"){$username = $request->guestBirthDate;}
										
										// set login_password
										if($pms->login_password=="room_no"){$password = (int)$request->roomNo;}
										elseif($pms->login_password=="first_name"){$password = $request->guestFirstName;}
										elseif($pms->login_password=="last_name"){$password = $request->guestLastName;}
										elseif($pms->login_password=="mobile"){$password = $request->guestMobile;}
										elseif($pms->login_password=="email"){$password = $request->guestEmail;}
										elseif($pms->login_password=="birth_date"){$password = $request->guestBirthDate;}

										// set gender
										if($request->guestGender=="Male"){$gender = 1;}
										elseif($request->guestGender=="Female"){$gender = 0;}
										else{$gender = 2;}
										
										// avoid nulled fields
										// if(!isset($request->guestTitle)){$request->guestTitle="";}
										// if(!isset($request->guestFirstName)){$request->guestFirstName="";}
										// if(!isset($row['LAST_NANME'])){$row['LAST_NANME']="";}
										// if(!isset($request->guestNationality)){$request->guestNationality="";}
										// if(!isset($request->guestCountry)){$request->guestCountry="";}
										// if(!isset($request->guestLanguage)){$request->guestLanguage="";}
										// if(!isset($row['TA'])){$row['TA']="";}
										
										
										

										$newUserID = DB::table("$request->guestId.users")->insertGetId([ 
											'pms_id' => $pms->id,
											'pms_guest_id' => $request->guestId, 
											'pms_room_no' => $request->roomNo, 
											'u_email' => $email, 
											'Registration_type' => '2', 
											'u_state' => '1', 
											'suspend' => '0', 
											'u_name' => $fullName, 
											'u_uname' => $username, 
											'u_password' => $password, 
											'u_phone' => $mobile, 
											'birthdate' => $birthDate,
											'u_country' => $request->guestCountry, 
											'u_lang'=> $request->guestLanguage,
											'u_gender' => $gender, 
											// 'branch_id' => DB::table($request->guestId.".branches")->where('state','1')->value('id'), 
											'branch_id' => $pms->id, // TABA
											'network_id' => DB::table($request->guestId.".networks")->where('state','1')->value('id'), 
											'group_id' => $pms->internet_group, 
											'notes' => 'Account Type: '.$request->type.", Nationality: ".$request->guestNationality.", Travel Agent: ".$row['TA'], 
											'created_at' => $todayDateTime]);
									}

									unset($userData);
									unset($username);
									unset($password);
									unset($gender);
									unset($fullName);
									unset($title);
									unset($username);
									unset($password);
									unset($fullName);
									unset($mobile);
									unset($email);
									unset($birthDate);
									
									// echo "ROOM_NO: ".$request->roomNo ."<br>";
									// echo "TYPE: ".$request->type ."<br>";
									// echo "NAME_ID: ".$request->guestId ."<br>";
									// echo "LAST_NAME: ".$request->guestLastName ."<br>";
									// echo "FIRST_NAME: ".$request->guestFirstName ."<br>";
									// echo "BEGIN_DATE: ".$row['BEGIN_DATE'] ."<br>";
									// echo "END_DATE: ".$row['END_DATE'] ."<br>";
									// echo "TITLE: ".$request->guestTitle ."<br>";
									// echo "GENDER: ".$request->guestGender ."<br>";
									// echo "NATIONALITY: ".$request->guestNationality ."<br>";
									// echo "COUNTRY: ".$request->guestCountry ."<br>";
									// echo "LANGUAGE: ".$request->guestLanguage ."<br>";
									// echo "TA: ".$row['TA'] ."<br>";
					
									// echo "<br>";
									// foreach ($row as $item) {
									// 	echo $item ."<br>";
									// }
								}
								
							}


							//////////////////////////////////////////////////////////////////////////////////////////////////
							////////////////////// check if there is bending invoice to send it to the PMS ///////////////////
							//////////////////////////////////////////////////////////////////////////////////////////////////

							foreach( DB::table("$request->guestId.pms_invoices")->where('pms_id', $pms->id)->where('delivered', '0')->get() as $bendingInvoice ){
								// insert new invoice record
								// execute query to get reservations
								if($pms->type == 'opera'){
									$invoice = @oci_parse($connection, $operaInsertInvoiceQuery);
								}elseif($pms->type == 'suite8'){
									$invoice = @oci_parse($connection, $suite8InsertInvoiceQuery);
								}elseif($pms->type == 'protel'){
									$invoice = @oci_parse($connection, $protelInsertInvoiceQuery);
								}
								@oci_execute($invoice);
								if(isset($invoice)){
									// invoice inserted successfully, so we will update delevery date of the invoice
									DB::table($request->guestId.".pms_invoices")->where( 'id', $bendingInvoice->id )->update([ 'delivered' => '1', 'delivered_at' => $todayDateTime ]); 
								}

							}

							@OCILogoff($connection);
						}else{
							echo "Connection Failed";
						}

					}
					
				}
			
				////////////////////////////////////////////////////////////////////////////////////////////////////
				/////////////////////////// check if there is reservations checking out ////////////////////////////
				////////////////////////////////////////////////////////////////////////////////////////////////////

				// compare active users in table users VS pms_reservations table
				if(isset($guestsId) and count($guestsId)>0){

					foreach( DB::table("$request->guestId.users")->where('suspend', '0')->where('pms_id', '!=', '0')->get() as $activeUser ){

						if( array_search($activeUser->pms_guest_id ,$guestsId) == false ){
							// we will diactivate this user
							DB::table($request->guestId.".users")->where( 'u_id', $activeUser->u_id )->update([ 'suspend' => '1' ]); 
						}
					}
				}
			
			}

		}

		return "Done";
    }
	*/
	
	// recevied from nodeJS API
	public function rowData(Request $request){
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
		// update last check 
		DB::table($request->system.".pms")->where( 'id', $request->pmsId )->update([ 'last_check' => $todayDateTime ]); 

        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 && isset($request->pmsId)){
			
			$pms = DB::table($request->system.'.pms')->where('id', $request->pmsId)->first();
			if(isset($pms)){
				
				// $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				// $body = @file_get_contents('php://input');
				// DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => $request->row_data ]]);

				if(isset($request->row_data) and $request->row_data != ""){

					foreach(explode('',$request->row_data) as $record){

						$newRecord = explode('|',$record);
						// return explode('RN',$newRecord[1])[1];
						// check if this is the response of post invoice
						if (strpos($newRecord[0], 'PA') !== false) {
							if( isset( explode('RN',$newRecord[1])[1]) ){
								$extractedRoomNo = trim(explode('RN',$newRecord[1])[1], '');
							}else{
								$extractedRoomNo = explode('|',explode('RN',$record)[1])[0];
							}
							DB::table($request->system.".pms_invoices")->where('pms_id', $pms->id)->where('delivered', '0')->where('room_no', $extractedRoomNo )->limit(1)->update([ 'delivered' => '1', 'delivered_at'=> $todayDateTime ]);
						}elseif(strpos($newRecord[0], 'GI') !== false) { // check if this is check in
							// step 1: prepare empty values
							$roomNo=""; $reservationId=""; $gTitle=""; $gFname=""; $gName=""; $gEmail=""; $gMobile=""; $gLanguage=""; $gBirthday=""; $gBirthday_ROW=""; $gNationality="";  $classOfService=""; $arrivalDate=""; $departureDate=""; $classOfService=""; $gGender=""; $guestGroupNumber = ""; $guestUniqueIdentifier= ""; $roomType= ""; $shareMultipleRooms= ""; $guestVipCategory= ""; $confirmationNo = "";
							// step 2: fill values into variables
							foreach( $newRecord as $part ){
								
								if (substr($part, 0, 2) == 'RN' and $roomNo=="") { $roomNo = trim(explode('RN',$part)[1],''); } // Room Number
								if (substr($part, 0, 2) == 'A0' and $gBirthday=="") { $gBirthday = trim(explode('A0',$part)[1],''); if(isset($gBirthday) and $gBirthday!="") {
									// we can receive rows with diff formats (01-Jun-1989 OR 05122020 )
									$gBirthday_ROW = $gBirthday; // incase we will insert it as password
									if(@date_format(date_create($gBirthday),"Y-m-d")){
										$gBirthday = date_format(date_create($gBirthday),"Y-m-d");
									}elseif(strlen($gBirthday) == "8"){
										$gBirthday = $gBirthday[4].$gBirthday[5].$gBirthday[6].$gBirthday[7]."-".$gBirthday[2].$gBirthday[3]."-".$gBirthday[0].$gBirthday[1];
									}else{
										$gBirthday = $gBirthday_ROW;
									}

									// convert format YYYY-MM-DD to DDMMYYYY to set the birthdate as password
									if(@date_format(date_create($gBirthday),"dmY")){
										$gBirthday_ROW = date_format(date_create($gBirthday),"dmY");
									}
								} // guest Birthday
								}
								if (substr($part, 0, 2) == 'A1' and $gEmail=="" ) { $gEmail = trim(explode('A1',$part)[1],''); } // guest Email 
								if (substr($part, 0, 2) == 'A2' and $gMobile=="" ) { $gMobile = trim(explode('A2',$part)[1],''); } // guest Mobile 
								if (substr($part, 0, 2) == 'A3' and $gNationality=="" ) { $gNationality = trim(explode('A3',$part)[1],''); } // guest Nationality 
								if (substr($part, 0, 2) == 'GT' and $gTitle=="") { $gTitle = trim(explode('GT',$part)[1],''); } // guest Title 
								if (substr($part, 0, 2) == 'A4' and $gGender=="" ) { $gGender = trim(explode('A4',$part)[1],''); } // guest Gender
								if (substr($part, 0, 2) == 'GF' and $gFname=="") { $gFname = substr($part, 2); } // guest first name 
								if (substr($part, 0, 2) == 'GN' and $gName=="") { $gName = substr($part, 2); } // guest name as last name 
								if (substr($part, 0, 2) == 'GL' and $gLanguage=="") { $gLanguage = trim(explode('GL',$part)[1],''); } // guest Language 
								if (substr($part, 0, 2) == 'G#' and $reservationId=="") { $reservationId = trim(explode('G#',$part)[1],''); } // reservation ID 
								if (substr($part, 0, 2) == 'GA' and $arrivalDate=="" ) { $arrivalDate = trim(explode('GA',$part)[1],''); $arrivalDate = date_format(date_create($arrivalDate[0].$arrivalDate[1].'-'.$arrivalDate[2].$arrivalDate[3].'-'.$arrivalDate[4].$arrivalDate[5]),"Y-m-d"); } // Guest Arrival Date 
								if (substr($part, 0, 2) == 'GD' and $departureDate=="") { $departureDate = trim(explode('GD',$part)[1],'');  $departureDate = date_format(date_create($departureDate[0].$departureDate[1].'-'.$departureDate[2].$departureDate[3].'-'.$departureDate[4].$departureDate[5]),"Y-m-d");} // Guest Departure Date 
								if (substr($part, 0, 2) == 'CS' and $classOfService=="") { $classOfService = trim(explode('CS',$part)[1],''); } // Class of Service
								if (substr($part, 0, 2) == 'GG' and $guestGroupNumber =="") { $guestGroupNumber = trim(explode('GG',$part)[1],''); } // Guest Group number
								
								if (substr($part, 0, 2) == 'G+' and $guestUniqueIdentifier=="" ) { $guestUniqueIdentifier = trim(explode('G+',$part)[1],''); } // guest Unique identifier  (Porfile ID in Opera)
								if (substr($part, 0, 2) == 'A8' and $guestUniqueIdentifier=="" ) { $guestUniqueIdentifier = trim(explode('A8',$part)[1],''); } // guest Unique identifier  (Porfile ID in Opera)

								if (substr($part, 0, 2) == 'A5' and $confirmationNo=="" ) { $confirmationNo = trim(explode('A5',$part)[1],''); } // confirmation 
								if (substr($part, 0, 2) == 'A6' and $roomType=="" ) { $roomType = trim(explode('A6',$part)[1],''); } // Room type ex. AMB, FAMILY, BRES, CLUB, PRINCE (will assign to a group with the same name) 
								if (substr($part, 0, 2) == 'GS' and $shareMultipleRooms=="" ) { $shareMultipleRooms = trim(explode('GS',$part)[1],''); } // Guest Share Multiple Rooms (Y|N) Yes OR No ex.two rooms conneted 
								if (substr($part, 0, 2) == 'GV' and $guestVipCategory=="" ) { $guestVipCategory = trim(explode('GV',$part)[1],''); } // Guest VIP category 1:13 => examples no of visits, Royal suite 

							}
							print_r( $this->_checkin($request->system, $pms->id, $roomNo, $reservationId, $gTitle, $gFname, $gName, $gEmail, $gMobile, $gLanguage, $gBirthday, $gBirthday_ROW, $gNationality, $gGender, $classOfService, $arrivalDate, $departureDate, $guestGroupNumber, $guestUniqueIdentifier, $roomType, $shareMultipleRooms, $guestVipCategory, $confirmationNo) );
							
						}elseif(strpos($newRecord[0], 'GO') !== false) { // check if this is check out

							print_r( $this->_checkout($request->system, $request->pmsId, explode('RN',$newRecord[1])[1]) );

						}elseif(strpos($newRecord[0], 'GC') !== false) { // check if this is change room

							// step 1: prepare empty values
							$roomNo=""; $reservationId=""; $gTitle=""; $gFname=""; $gName=""; $gEmail=""; $gMobile=""; $gLanguage=""; $gBirthday=""; $gBirthday_ROW=""; $gNationality=""; $arrivalDate=""; $departureDate=""; $classOfService=""; $gGender=""; $guestGroupNumber = ""; $guestUniqueIdentifier= ""; $roomType= ""; $shareMultipleRooms= ""; $guestVipCategory= ""; $confirmationNo = ""; $oldRoomNo = "";
							
							// step 2: fill values into variables
							foreach( $newRecord as $part){
								
								if (substr($part, 0, 2) == 'RN' and $roomNo=="" ) { $roomNo = trim(explode('RN',$part)[1],''); } // Room No
								if (substr($part, 0, 2) == 'RO' and $oldRoomNo=="" ) { $oldRoomNo = trim(explode('RO',$part)[1],''); } // Old Room Number
								if (substr($part, 0, 2) == 'A0' and $gBirthday=="") { $gBirthday = trim(explode('A0',$part)[1],''); if(isset($gBirthday) and $gBirthday!="") {
									// we can receive rows with diff formats (01-Jun-1989 OR 05122020 )
									$gBirthday_ROW = $gBirthday; // incase we will insert it as password
									if(@date_format(date_create($gBirthday),"Y-m-d")){
										$gBirthday = date_format(date_create($gBirthday),"Y-m-d");
									}elseif(strlen($gBirthday) == "8"){
										$gBirthday = $gBirthday[4].$gBirthday[5].$gBirthday[6].$gBirthday[7]."-".$gBirthday[2].$gBirthday[3]."-".$gBirthday[0].$gBirthday[1];
									}else{
										$gBirthday = $gBirthday_ROW;
									}

									// convert format YYYY-MM-DD to DDMMYYYY to set the birthdate as password (TABA)
									if(@date_format(date_create($gBirthday),"dmY")){
										$gBirthday_ROW = date_format(date_create($gBirthday),"dmY");
									}

								} // guest Birthday
								}
								if (substr($part, 0, 2) == 'A1' and $gEmail=="" ) { $gEmail = trim(explode('A1',$part)[1],''); } // guest Email 
								if (substr($part, 0, 2) == 'A2' and $gMobile=="" ) { $gMobile = trim(explode('A2',$part)[1],''); } // guest Mobile 
								if (substr($part, 0, 2) == 'A3' and $gNationality=="" ) { $gNationality = trim(explode('A3',$part)[1],''); } // guest Nationality 
								if (substr($part, 0, 2) == 'GT' and $gTitle=="") { $gTitle = trim(explode('GT',$part)[1],''); } // guest Title 
								if (substr($part, 0, 2) == 'A4' and $gGender=="" ) { $gGender = trim(explode('A4',$part)[1],'');} // guest Gender
								if (substr($part, 0, 2) == 'GF' and $gFname=="") { $gFname = explode('GF',$part)[1]; } // guest first name 
								if (substr($part, 0, 2) == 'GN' and $gName=="") { $gName = explode('GN',$part)[1]; } // guest name as last name 
								if (substr($part, 0, 2) == 'GL' and $gLanguage=="") { $gLanguage = trim(explode('GL',$part)[1],''); } // guest Language 
								if (substr($part, 0, 2) == 'G#' and $reservationId=="") { $reservationId = trim(explode('G#',$part)[1],''); } // reservation ID 
								if (substr($part, 0, 2) == 'GA' and $arrivalDate=="" ) { $arrivalDate = trim(explode('GA',$part)[1],''); $arrivalDate = date_format(date_create($arrivalDate[0].$arrivalDate[1].'-'.$arrivalDate[2].$arrivalDate[3].'-'.$arrivalDate[4].$arrivalDate[5]),"Y-m-d"); } // Guest Arrival Date 
								if (substr($part, 0, 2) == 'GD' and $departureDate=="") { $departureDate = trim(explode('GD',$part)[1],'');  $departureDate = date_format(date_create($departureDate[0].$departureDate[1].'-'.$departureDate[2].$departureDate[3].'-'.$departureDate[4].$departureDate[5]),"Y-m-d");} // Guest Departure Date 
								if (substr($part, 0, 2) == 'CS' and $classOfService=="") { $classOfService = trim(explode('CS',$part)[1],''); } // Class of Service
								if (substr($part, 0, 2) == 'GG' and $guestGroupNumber =="") { $guestGroupNumber = trim(explode('GG',$part)[1],''); } // Guest Group number
								if (substr($part, 0, 2) == 'G+' and $guestUniqueIdentifier=="" ) { $guestUniqueIdentifier = trim(explode('G+',$part)[1],''); } // guest Unique identifier  (Porfile ID in Opera)
								if (substr($part, 0, 2) == 'A8' and $guestUniqueIdentifier=="" ) { $guestUniqueIdentifier = trim(explode('A8',$part)[1],''); } // guest Unique identifier  (Porfile ID in Opera)
								if (substr($part, 0, 2) == 'A5' and $confirmationNo=="" ) { $confirmationNo = trim(explode('A5',$part)[1],''); } // confirmation 
								if (substr($part, 0, 2) == 'A6' and $roomType=="" ) { $roomType = trim(explode('A6',$part)[1],''); } // Room type ex. AMB, FAMILY, BRES, CLUB, PRINCE (will assign to a group with the same name) 
								if (substr($part, 0, 2) == 'GS' and $shareMultipleRooms=="" ) { $shareMultipleRooms = trim(explode('GS',$part)[1],''); } // Guest Share Multiple Rooms (Y|N) Yes OR No ex.two rooms conneted 
								if (substr($part, 0, 2) == 'GV' and $guestVipCategory=="" ) { $guestVipCategory = trim(explode('GV',$part)[1],''); } // Guest VIP category 1:13 => examples no of visits, Royal suite 
								if (substr($part, 0, 2) == 'RO' and $oldRoomNo=="" ) { $oldRoomNo = trim(explode('RO',$part)[1],''); } // Old Room Number
								
							}							
							// print_r( $this->_changeRoom($request->system, $pms->id, $roomNo, $reservationId, $gName, $gLanguage, $guestGroupNumber, $arrivalDate, $departureDate, $classOfService, $shareMultipleRooms, $oldRoomNo, $gTitle, $guestUniqueIdentifier, $roomType, $guestVipCategory) );
							print_r( $this->_changeRoom($request->system, $pms->id, $roomNo, $reservationId, $gTitle, $gFname, $gName, $gEmail, $gMobile, $gLanguage, $gBirthday, $gBirthday_ROW, $gNationality, $gGender, $classOfService, $arrivalDate, $departureDate, $guestGroupNumber, $guestUniqueIdentifier, $roomType, $shareMultipleRooms, $guestVipCategory, $confirmationNo, $oldRoomNo) );
						}
					}
				}
				return json_encode(array('state' => 1, 'message' => 'recevied.', 'pms_id' => $request->pmsId));

			}else{
				return json_encode(array('state' => 0, 'message' => 'Invalid PMS ID.'));
			}
		}else{
            return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
        }
	}

	// restart PMS after clicking restart from setting page
	public function restartPmsIntrface(Request $request){

		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
		$pms = App\Models\Pms::where('id', $request->id)->first();
		// return $pms->name;
		// // testing
		// echo "testing"; 
		// return shell_exec('sudo service named start');
		// stop pms
		// $output = shell_exec('sudo /usr/local/bin/pm2 stop '.$pms->name);
		$output = shell_exec('sudo pm2 stop '.$pms->name);
		sleep(12);
		// $output = shell_exec('sudo /usr/local/bin/pm2 start '.$pms->name);
		$output = shell_exec('sudo pm2 start '.$pms->name);
		return $output;

	}
	
}