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

class MicrosystemCron extends Controller
{
    public function MicrosystemCron(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        $currentHour = date("H");
        $today_full24=$created_at;
        $todayFirstMinute = date("Y-m-d")." 00:00:00";
        $todayLastMinute = date("Y-m-d")." 23:59:59";
        
        $totalUsers = 0;
        $concurrentUsers = 0;
        $newUsers = 0;
        $maxConcurrent = 0;
        $totalDevices = 0;
        $newDevices = 0;
        $totalVisitors = 0;
        $totalOnlineSessions = 0;
        $totalOnlineUsers = 0;
        $totalNewUsers = 0;
        $concurrentNow = 0;
        DB::statement( 'TRUNCATE TABLE end_users');
        DB::statement( 'UPDATE mac_vendor_list SET used=0' );
        
        // return $thisTenentDevice = DB::table("tolip.visitors")->whereBetween('created_at', [$todayFirstMinute , $todayLastMinute] )->count();
        // for test only
        // $allCustomers=DB::table('customers')->where('database','alemaratiya')->groupBy('database')->get();
        $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        // $allCustomers=DB::table('customers')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            
            DB::statement( 'TRUNCATE TABLE '.$Customer->database.'.radpostauth;');
            DB::statement( 'DELETE FROM '.$Customer->database.'.radacct WHERE `acctsessiontime` <= 30;');
            DB::statement( 'OPTIMIZE TABLE '.$Customer->database.'.radacct;');

            // check if SMS verification in ON
            $accountkitState = DB::table( $Customer->database.".settings" )->where( 'type','Accountkitappid' )->value('state');
            $SMSstate = DB::table( $Customer->database.".networks" )->where( 'state','1' )->value('r_type');
            if( (isset($accountkitState) and $accountkitState==1) or (isset($SMSstate) and $SMSstate==2) ){ $verified = 1; }
            else{ $verified = 0; }

            // get network concurrent users
            $networkConcurrentUsers = DB::table($Customer->database.".history")->where('operation', 'concurrent')->where('add_date', $today)->whereNotNull('network_id')->value('details');
            if(isset($networkConcurrentUsers)){$maxConcurrent+=$networkConcurrentUsers;}

            // get total visits today
            $thisTenentDevice = DB::table( $Customer->database.".visitors")->whereBetween('created_at', [$todayFirstMinute , $todayLastMinute] )->count();
            // DB::table("history")->insert(['type' => "$Customer->database-visitors", 'value' => $thisTenentDevice, 'date' => $today, 'created_at' => $created_at]);
            $totalVisitors+=$thisTenentDevice;
            unset($thisTenentDevice);

            // get online users at moment
            $thisTenentOnlineUsers = DB::table( $Customer->database.".radacct")->whereNull('acctstoptime')->groupBy('u_id')->count();
            // DB::table("history")->insert(['type' => "$Customer->database-onlineUsers", 'value' => $thisTenentOnlineUsers, 'date' => $today, 'created_at' => $created_at]);
            $totalOnlineUsers+=$thisTenentOnlineUsers;
            unset($thisTenentOnlineUsers);

            // get new users today
            $thisTenentNewUsers = DB::table( $Customer->database.".users")->whereBetween('created_at', [$todayFirstMinute , $todayLastMinute] )->count();
            // DB::table("history")->insert(['type' => "$Customer->database-newUsers", 'value' => $thisTenentNewUsers, 'date' => $today, 'created_at' => $created_at]);
            $totalNewUsers+=$thisTenentNewUsers;
            unset($thisTenentNewUsers);

            // get all users count
            $totalUsers+=DB::table( $Customer->database.".users")->count();

            // get all devices count
            $num_of_mac = DB::select("SELECT SUM(CHAR_LENGTH(u_mac) - CHAR_LENGTH(REPLACE(u_mac, ',', '')) + 1) AS num_of_mac FROM $Customer->database.users");
            if(isset($num_of_mac)){$array = json_decode(json_encode($num_of_mac), True);}
            if(isset($array[0]['num_of_mac'])){ $totalDevices+= $array[0]['num_of_mac']; }

            // get concurrent devices NOW
            $concurrentNow+=DB::table($Customer->database.".radacct")->whereNull('acctstoptime')->count();
            
            /////////////////////////////////////
            ///            Add users          ///
            /////////////////////////////////////
            /*
            foreach( DB::table( $Customer->database.".users" )->get() as $endUser ){
                $totalUsers++;
                // check online state
                if( DB::table($Customer->database.".radacct")->where('u_id', $endUser->u_id)->whereNull('acctstoptime')->count() > 0){
                    $userOnlineState = 1;
                    $concurrentUsers++;
                }else{
                    $userOnlineState = 0;
                }
                // check registration date
                $created_at_splited = explode(' ', $endUser->created_at);
                if(isset($created_at_splited[0]) and $created_at_splited[0] == $today){  $newUsers++; }

                // get no of devices
                if(isset($endUser->u_mac) and $endUser->u_mac!="" and $endUser->u_mac!=" " ){
                    // $totalDevices += count(explode(',', $endUser->u_mac));
                    // get mac vendor
                    // 1st step remove :
                    foreach( explode(',', $endUser->u_mac) as $thisMac ){
                        
                        $mac1Explode1 = explode(":", $thisMac);
                        if(isset($mac1Explode1[2])){
                            $totalDevices++;
                            /*
                            // get Mac Vendor
                            $macVendorFormat = strtoupper($mac1Explode1[0].$mac1Explode1[1].$mac1Explode1[2]);
                            if( DB::table('mac_vendor_list')->where( 'mac_prefix',$macVendorFormat )->count() > 0 ){
                                DB::table('mac_vendor_list')->where( 'mac_prefix',$macVendorFormat )->increment('used');
                                // DB::table('mac_vendor_list')->where( 'mac_prefix',$macVendorFormat )->update([ 'u_id' => $endUser->u_id, 'sys_db' => $Customer->database ]);
                            }else{
                                // unknown vendor
                                DB::table("mac_vendor_list")->insert(['mac_prefix' => $macVendorFormat, 'name' => 'unknown', 'used' => '1', 'u_id' => $endUser->u_id, 'sys_db' => $Customer->database ]);
                            }
                            unset($macVendorFormat);
                            */ /*
                        }
                        unset($mac1Explode1);
                        
                    }
                }
                
                // insert user data
                // DB::table("end_users")->insert(['u_id' => $endUser->u_id, 'system_id' => $Customer->id, 'system_db' => $Customer->database, 'branch_name' => DB::table( $Customer->database.".branches" )->where( 'id',$endUser->branch_id )->value('name') , 'online' => $userOnlineState, 'name' => $endUser->u_name, 'gender' => $endUser->u_gender, 'mobile' => $endUser->u_phone, 'country' => $endUser->u_country, 'mail' => $endUser->u_email, 'mac' => $endUser->u_mac, 'user_created_at' => $endUser->created_at, 'verified' => $verified]);
                
                unset($userOnlineState);
                unset($created_at_splited);
                
            }
            */
            /////////////////////////////////////
            ///            Add users          ///
            /////////////////////////////////////
            unset($networkConcurrentUsers);
           
        }

        // Get RAM load %
        function get_server_memory_usage(){

            $free = shell_exec('free');
            $free = (string)trim($free);
            $free_arr = explode("\n", $free);
            $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem);
            $mem = array_merge($mem);
            $memory_usage = round($mem[2]/$mem[1]*100,1);
            // to convert from free % to used %
            $memory_usage = abs($memory_usage - 100);
        
            return $memory_usage;
        }
        // get Processor load
        function get_server_cpu_usage(){
            $load = sys_getloadavg();
            return $load[0];
        }

        // get new devices
        $lastDevicesRecord = DB::table("history")->where( 'type','totalDevices' )->orderBy('id','desc')->limit(1)->value('value');
        $newDevices=$totalDevices-$lastDevicesRecord;

        // insert Microsystem history data
        // DB::table("history")->insert(['type' => 'newUsersToday', 'value' => $newUsers, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'totalDevices', 'value' => $totalDevices, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'newDevicesToday', 'value' => $newDevices, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'onlineUsers', 'value' => $totalOnlineUsers, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'concurrentDevicesNOW', 'value' => $concurrentNow, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'maxConcurrentToday', 'value' => $maxConcurrent, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'visitors', 'value' => $totalVisitors, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'newUsers', 'value' => $totalNewUsers, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'totalUsers', 'value' => $totalUsers, 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'RAM%', 'value' => get_server_memory_usage(), 'date' => $today, 'created_at' => $created_at]);
        DB::table("history")->insert(['type' => 'CPU', 'value' => get_server_cpu_usage(), 'date' => $today, 'created_at' => $created_at]);
        return "Done";
    }
}