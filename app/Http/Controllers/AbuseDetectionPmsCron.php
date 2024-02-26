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
use Mail;
use DateTime;

class AbuseDetectionPmsCron extends Controller
{

    
    public function abuseDetectionPmsCron(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $now = time(); 
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        $currentHour = date("H");
        $today_full24=$created_at;
        $lastWeek = date('Y-m-d', strtotime("-7 days", strtotime($today)));

        $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            

            /////////////////////////////////////////////////////////////////////////////////////////////////////
            ////     check spamming emplwees in hotels if there is an online user afrer 1 day of checkout    ////
            /////////////////////////////////////////////////////////////////////////////////////////////////////
            

            // check if there is active PMS integration
            $pms = DB::table($Customer->database.'.pms')->where('state', '1')->first();
            if(isset($pms)){
                // // create (Abusers staff) account if not exist
                // $userData4PMS = DB::table($Customer->database.".users")->where('u_name','Abusers staff')->first();
                // if(!isset($userData4PMS)){
                //     $abusersStaffId = DB::table("$Customer->database.users")->insertGetId([ 'u_email' => ' ', 'Registration_type' => '2', 'u_state' => '0', 'suspend' => '1', 'u_name' => 'Abusers staff', 'u_uname' => 'Abusers staff', 'u_password' => 'Abusers staff', 'u_phone' => '',  'u_mac' => '00:11:22:33:44:55', 'u_country' => '', 'u_gender' => '2', 'branch_id' => DB::table($Customer->database.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($Customer->database.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($Customer->database.".area_groups")->where('name','Default')->orWhere('name','default')->value('id'), 'created_at' => $created_at]);
                // }else{
                //     $abusersStaffId = $userData4PMS->u_id;
                // }

                // create (Abusers Group) Group if not exist
                $abusersGroup = DB::table($Customer->database.".area_groups")->where('name','Abusers')->first();
                if(!isset($abusersGroup)){
                    $abusersgroupId = DB::table("$Customer->database.area_groups")->insertGetId([ 'name' => 'Abusers', 'session_time' => '00:00:00', 'quota_limit_upload' => '0', 'quota_limit_download' => '0', 'quota_limit_total' => '0', 'speed_limit' => '32K/32K 128K/128K 22K/22K 60 8', 'renew' => '1', 'if_downgrade_speed' => '0', 'is_active' => '0', 'as_system' => '0', 'port_limit' => '1', 'notes' => 'Abusers staff', 'network_id' => '2', 'auto_login' => '0', 'limited_devices' => '1', 'created_at' => $created_at]);
                }else{
                    $abusersgroupId = $abusersGroup->id;
                }


                $searchController = new App\Http\Controllers\SearchController();
                $onlineUsers = DB::table($Customer->database.".radacct")->whereNull('acctstoptime')->get();
                // $onlineUsers = DB::table($Customer->database.".radacct_active_users")->get();
                // $onlineUsers = DB::table("demo".".radacct_active_users")->get(); // for test only
                foreach ($onlineUsers as $value) {
            
                    // get user pms_id and checkout date
                    $userData4PMS = DB::table($Customer->database.".users")->where('u_id',$value->u_id)->first();
                    if(isset($userData4PMS)){
                        // get checkout date
                        if (strpos($userData4PMS->notes, 'checkOut:') !== false) {
                            $userCheckoutDate = @explode(",",end(preg_split('/checkOut: /', $userData4PMS->notes)))[0];
                            if(isset($userCheckoutDate) and strlen($userCheckoutDate)>8 ){
                                // calculate remaining days till checkout
                                $datediff = strtotime($userCheckoutDate) - $now;
                                $remainingDaysCheckout = round($datediff / (60 * 60 * 24)); // no of remaining days till checkout
                                // make sure this user checked out yesterday (+0 days), since 2 days (+1 days), since 7 days (+6 days), 
                                if(isset($remainingDaysCheckout) and $remainingDaysCheckout < 0 and $today > date('Y-m-d', strtotime("+7 days", strtotime($userCheckoutDate))) ){
                                    // suspend this user because this mac address online after midnight of checkout date
                                    // echo "<br> Abuser Staff: ".$value->callingstationid.", U_ID:$value->u_id".", Guest Name:$userData4PMS->u_name".", Guest Room:$userData4PMS->u_uname".", Check-Out date:$userCheckoutDate".", Branch ID:$userData4PMS->branch_id.";
                                    
                                    // // insert this mac to abusersStaffAccount and notes (STOPPED BECAUSE WE CHANGE PLAN TO BE USER CREATION FOR EACH ABUSER )
                                    // $abusersStaffAccount = DB::table($Customer->database.".users")->where('u_id', $abusersStaffId)->first();
                                    // $note = "New abuse detection at:$created_at, using Mac:$value->callingstationid, founded in user:$userData4PMS->u_name, checkOut:$userCheckoutDate";
                                    // DB::table($Customer->database.".users")->where('u_id', $abusersStaffId)->update(['u_mac' => $abusersStaffAccount->u_mac.','.$value->callingstationid, 'notes' => $abusersStaffAccount->notes.'; '.$note]);
                                    
                                    // create new user with full details
                                    echo "<br> Staff Abuse Detection:  at:$created_at, Using Mac:".$value->callingstationid.", IP:".$value->framedipaddress.", Guest Name:$userData4PMS->u_name".", Guest Room:$userData4PMS->u_uname".", Check-Out date:$userCheckoutDate".", Branch ID:$userData4PMS->branch_id.";
                                    
                                    if( DB::table($Customer->database.".users")->where('u_name','Abuse detection '.$value->callingstationid)->count() == 0 ){
                                        $note = "Staff Abuse Detection:  at:$created_at, Using Mac:".$value->callingstationid.", IP:".$value->framedipaddress.", Guest Name:$userData4PMS->u_name".", Guest Room:$userData4PMS->u_uname".", Check-Out date:$userCheckoutDate".", Branch ID:$userData4PMS->branch_id.";
                                        $abusersStaffId = DB::table("$Customer->database.users")->insertGetId([ 'u_mac' => $value->callingstationid,'u_email' => ' ', 'Registration_type' => '2', 'u_state' => '0', 'suspend' => '0', 'u_name' => 'Abuse detection '.$value->callingstationid, 'u_uname' => $userData4PMS->u_name, 'u_password' => $created_at, 'u_phone' => '', 'u_country' => '', 'u_gender' => '2', 'branch_id' => $value->branch_id, 'network_id' => $value->network_id, 'group_id' => $abusersgroupId, 'created_at' => $created_at, 'notes' => $note]);

                                        // send disconnect signal
                                        DB::table($Customer->database.".radacct")->where('radacctid',$value->radacctid)->update(['realm'=>'1']);

                                        // send suspend signal
                                        $searchController->suspend($abusersStaffId, "false");
                                    }

                                    
                                   
                                }
                            }
                        }
                    }
                    unset($userData4PMS);

                } //foreach ($onlineUsers as $key => $value)
                

            }

			
      
        }
    echo "<center><h1>Abuse Detection Done</h1></center>";
	}
	
}