<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Network;
use Illuminate\Http\Request;
use App\Branches;
use App\Models\LoadBalancing;
use Input;
use DB;
use Auth;
use App;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class BranchesController extends Controller
{

    public function index()
    {
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['branches'] == 1){
            return view('back-end.branches.index',array(
                'branches'=> Branches::all(),
                'networks' => Network::all()
            ));
        }else{
            return view('errors.404');
        }
    }

    // get auto refresh data in branch page
    public function Json(){
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['branches']) {

           
            // $firstDayMonth=date("Y-m")."-01";
            // $lastDayMonth=date('Y-m-t', strtotime($firstDayMonth));

            $data = App\Models\BranchNetwork::get();
            foreach ($data as $key => $value) {

                 // get the first day of renwing day to get monthly usage in GB
                $subdomain = url()->full();
                $split = explode('/', $subdomain);
                $customerData =  DB::table('customers')->where('url',$split[2])->first();
                $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
                $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($customerData->database, $value->id);
                $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];
                
                $value->count_online = App\Models\RadacctActiveUsers::where('branch_id', $value->id)->count();
                $value->count_users = App\Users::where('branch_id', $value->id)->count();
                
                // get Monthly Usage
                $monthlyUsageUpload = App\Radacct::where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctinputoctets');
                $monthlyUsageDownload = App\Radacct::where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctoutputoctets');
                $monthlyTotalUsage = round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1)." GB";
                $value->monthly_usage = $monthlyTotalUsage;
                
                // Get Total Usage
                $usageUpload = App\Radacct::where('branch_id',$value->id)->sum('acctinputoctets');
                $usageDownload = App\Radacct::where('branch_id',$value->id)->sum('acctoutputoctets');
                $totalUsage = round(($usageUpload+$usageDownload)/1024/1024/1024,2)." GB";
                $value->total_usage = $totalUsage;

                $radiusType = App\Branches::where('id', $value->id)->value('radius_type');
                if( $radiusType == "aruba" ){ $foundDDWRT=1; }
                if( $radiusType == "ddwrt" ){ $foundDDWRT=1; }
                if(!isset($foundDDWRT)){$foundDDWRT=0;}
                //return $value->last_check .'aaaaaaaaaaaaaaaaaaaaaa'. Carbon::now();
                if($foundDDWRT==1){
                    // get value from last update in "radacct" table
                    $lastCheckSeconds = strtotime( App\Radacct::where('branch_id',$value->id)->orderBy('radacctid', 'desc')->value('acctupdatetime') );
                }else{
                    // get value from branch table
                    $lastCheckSeconds=strtotime($value->last_check);
                }
                
                //return Carbon::now();
                $timeNowSeconds = strtotime(Carbon::now());
                
                //$lastCheckSeconds = strtotime("2017-10-11 10:00:00");
                //$timeNowSeconds = strtotime("2017-10-11 10:02:01");
                $value->delayTime = $timeNowSeconds - $lastCheckSeconds;

                if($foundDDWRT==1){
                    // send dash "-"
                    $value->cpu = "<center><h>-</h></center>";
                    $value->uptime = "<center><h>-</h></center>";
                    $value->ram = "<center><h>-</h></center>";
                }else{
                    // send real data
                    $value->cpu = $value->cpu."%";
                }

                $value->foundDDWRT = $foundDDWRT;

                if($value->last_check){
                    $value->last_check_date = explode(' ', $value->last_check)[0];
                    $value->last_check_time = explode(' ', $value->last_check)[1];
                }
            }
            return array('aaData' => $data);
        }else{
            return view('errors.404');
        }
    }

    public function Add(Request $request){
        

        //Backup connection
        $backup_connection_state =  $request['backup-connection-state'] == 'on' ? '1' : '0';
        if($backup_connection_state == 1){
            $backup_connection_type = $request['backup-connection-type'];
        }else{
            $backup_connection_type = null;
        }

        //Wireless
        $wireless_state = $request['wireless-state'] == 'on' ? '1' : '0';

        //Private wireless    
        $private_wireless_state = $request['private-wireless-state'] == 'on' ? '1' : '0';

        if($private_wireless_state == 1){
            $private_wireless_name = $request['private-wireless-username'];
            $private_wireless_pass = $request['private-wireless-password'];
            $private_wireless_ip = $request['private-wireless-ip'];
        }else{
            $private_wireless_name = null;
            $private_wireless_pass = null;
            $private_wireless_ip = null;
        }


        //Security    
        $hacking_protection = $request['security-state'] == 'on' ? '1' : '0';
        $adult_state = $request['adult-state'] == 'on' ? '1' : '0';

        //Advanced script
        $advanced_script_state = $request['advanced-script-state'] == 'on' ? '1' : '0';
        if($advanced_script_state == 1){
            $advanced_script = $request['advanced-script'];
        }else{
            $advanced_script = null;
        }
        $log_history_state = $request['log-history-state'] == 'on' ? '1' : '0';

        // insert serial into Microsystem DB
        $serial=$request['serial'];
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $systemIdentify=$split[2];
        $getTenantID = DB::table('microsystem.customers')->where('url', $systemIdentify)->value('id');
        // delete any seial
        DB::table('microsystem.serials')->where('serial', $serial)->delete();
        // insert new serial with tenantID
        DB::table('microsystem.serials')->insert(['serial' => $serial, 'customer_id' => $getTenantID]);

        $radiusType=$request['r_type'];

        $auto_login=$request['auto_login'] == 'on' ? '1' : '0';
        $auto_login_expiry=$request['auto-login-expiry'];
        $antivirus=$request['antivirus'] == 'on' ? '1' : '0';
        $block_windows_update=$request['block_windows_update'] == 'on' ? '1' : '0';
        $block_torrent_download = $request['torr'] == 'on' ? '1' : '0';
        $block_downloading=$request['block_downloading'] == 'on' ? '1' : '0';

        // insert all branch data
        $branch = Branches::insertGetId(['auto_login' => $auto_login, 'auto_login_expiry' => $auto_login_expiry, 'antivirus' => $antivirus, 'block_windows_update' => $block_windows_update, 'block_torrent_download' => $block_torrent_download, 'block_downloading' => $block_downloading, 'name' => $request['name'], 'network_id' => $request['networkname'], 'state' => $request['state'], 'address' => $request['address'], 'phone' => $request['phone'], 'notes' => $request['notes'], 'username' => $request['username'], 'password' => $request['password'], 'Radiussecret' => $request['Radiussecret'], 'ip' => $request['ip'], 'APIport' => $request['APIport'], 'Radiusport' => $request['Radiusport'], 'start_quota' => $request['start_quota'], 'monthly_quota' => $request['monthly_quota'], 'device_mac' => $request['device_mac'], 'serial' => $request['serial'], 'connection_type' => $request['connection-type'], 'adsl_user' => $request['adsl-username'], 'adsl_pass' => $request['adsl-password'], 'backup_connection_type' => $backup_connection_type, 'backup_connection_state' => $backup_connection_state, 'wireless_state' => $wireless_state, 'wireless_name' => $request['wireless-username'], 'wireless_pass' => $request['wireless-password'], 'private_wireless_state' => $private_wireless_state, 'private_wireless_name' => $private_wireless_name, 'private_wireless_pass' => $private_wireless_pass, 'private_wireless_ip' => $private_wireless_ip, 'hacking_protection' => $hacking_protection, 'adult_state' => $adult_state, 'advanced_script_state' => $advanced_script_state, 'advanced_script' => $advanced_script, 'users_log_history_type' => $request['log-history-type'], 'users_log_history_state' => $log_history_state, 'radius_type' => $radiusType]);

        // internetMode
        if($request['internet-mode']!="default"){
            Branches::where('id', '=', $branch)->update(['internet_mode' => $request['internet-mode'], 'change_internet_mode' => '1']);
        }

        // check loadbalanceing state
        if($request['connection-type'] == "6"){
            Branches::where('id', '=', $branch)->update(['load_balance_state' => '1', 'change_load_balance_state' => '1']);
        }
        
        $recordsCount = @count($request['load-ip']);
        for($i = 0; $i < $recordsCount; $i++)
         { 
            if(isset($request['load-username'][$i])){$load_username=$request['load-username'][$i];}else{$load_username="";}
            if(isset($request['load-password'][$i])){$load_password=$request['load-password'][$i];}else{$load_password="";}

           LoadBalancing::insert(['branch_id' => $branch, 'ip' => $request['load-ip'][$i], 'gateway' => $request['load-gateway'][$i], 'speed' => $request['load-speed'][$i], 'type' => $request['load-type'][$i], 'user' => $load_username, 'pass' => $load_password]); 
        }

        $bypassRecordsCount = @count($request['bypass-ip']);
        for($i = 0; $i < $bypassRecordsCount; $i++)
         { 
           // insert new record
           App\Models\Bypassed::insert(['branch_id' => $branch, 'ip' => $request['bypass-ip'][$i], 'mac' => $request['bypass-mac'][$i], 'port' => $request['bypass-port'][$i], 'change_state' => '1', 'state' => '0', 'created_at' => Carbon::now() ]); 
        }

        return redirect()->route('branches');
    }

    public function delete_branch($id){

        $delete = Branches::where('id',$id)->first();
        $delete->delete();
        return redirect()->route('branches');
    }
    // change branch state direct button
    public function state($id,$value){
        
        $dt = Carbon::now();
        $value = ($value == 'true')? 1 : 0;        
        if(Branches::where('id', $id)->value('state') != $value){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );

            Branches::where('id', '=', $id)->update(['state' => $value, 'change_state' => '1']);
        } 
    }
    public function branchid($id){
        return view('back-end.branches.edit',array(
            'branch' => Branches::find($id),
            'networks' => Network::all(),
            'load' => LoadBalancing::where('branch_id', $id)->get(),
            'bypass' => App\Models\Bypassed::where('branch_id', $id)->where('change_state','!=','2')->get()
        ));
    }


    public function Edit($id){
        
        $dt = Carbon::now();
        $branch = Branches::find($id);

        //General
        $branch->name = Input::get('name');
        $branch->network_id = Input::get('networkname');
        //$branch->state = Input::get('state');
        $branch->address = Input::get('address');
        $branch->phone = Input::get('phone');
        $branch->notes = Input::get('notes');
        $branch->radius_type = Input::get('r_type');
        // autoLoginByMacFromWeb
        App\Settings::where('type', 'autoLoginByMacFromWeb')->where('value', $branch->id)->update(['state' => Input::get('autoLoginByMacFromWeb')]);
        
        //Basic settings
        if($branch->username != Input::get('username')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_username', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_username_or_password = 1;
            $branch->username = Input::get('username'); 
        }
        if($branch->password != Input::get('password')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_password', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_username_or_password = 1;
            $branch->password = Input::get('password'); 
        }
        $branch->Radiussecret = Input::get('Radiussecret');
        $branch->ip = Input::get('ip');
        $branch->APIport = Input::get('APIport');
        $branch->Radiusport = Input::get('Radiusport');
        $branch->start_quota = Input::get('start_quota');
        $branch->monthly_quota = Input::get('monthly_quota');
        $branch->device_mac = Input::get('device_mac');
        $branch->serial = Input::get('serial');

        // insert serial into Microsystem DB
        $serial=Input::get('serial');
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $systemIdentify=$split[2];  
        $getTenantID = DB::table('microsystem.customers')->where('url', $systemIdentify)->value('id');
        // delete any seial
        DB::table('microsystem.serials')->where('serial', $serial)->delete();
        // insert new serial with tenantID
        DB::table('microsystem.serials')->insert(['serial' => $serial, 'customer_id' => $getTenantID]);

        // PMS state
        if(App\Settings::where('type', 'pms_integration')->value('state') == "1" ){
            $branch->pms_premium_login_state = Input::get('pms_premium_login_state') == 'on' ? '1' : '0';
            $branch->pms_complementary_login_state = Input::get('pms_complementary_login_state') == 'on' ? '1' : '0';
        }

        // Location Based Group Switching
        $branch->temporary_group_switching_state = Input::get('temporary_group_switching_state') == 'on' ? '1' : '0';
        $branch->temporary_group_switching_group_id = Input::get('temporary_group_switching_group_id');
        $branch->temporary_group_switching_exception_groups = @implode(",",Input::get('temporary_group_switching_exception_groups'));
        
        //Connection
        if($branch->connection_type != Input::get('connection-type')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_connection_type', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_connection_type = 1;
            $branch->connection_type = Input::get('connection-type'); 
            // check loadbalanceing state
            if($branch->connection_type == "6"){
                $branch->load_balance_state="1";
                $branch->change_load_balance_state="1";
            }elseif($branch->connection_type != "6"){
                $branch->load_balance_state="0";
                $branch->change_load_balance_state="1";
            }
        }

        if($branch->adsl_user != Input::get('adsl-username')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_adsl_user', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_adsl_user = 1;
            $branch->adsl_user = Input::get('adsl-username');            
        }
        if($branch->adsl_pass != Input::get('adsl-password')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_adsl_pass', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_adsl_pass = 1;
            $branch->adsl_pass = Input::get('adsl-password');            
        }


        //Backup connection state
        $backup_connection_state =  Input::get('backup-connection-state') == 'on' ? '1' : '0';
        if($branch->backup_connection_state != $backup_connection_state){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_backup_connection_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->backup_connection_state = $backup_connection_state;
            $branch->change_backup_connection_state = 1;
            
        }  

        //Backup connection type
        if( $branch->backup_connection_type!=Input::get('backup-connection-type')){

            if($backup_connection_state == 1){
                App\History::insert(
                    ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_backup_connection_type', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                $branch->backup_connection_type = Input::get('backup-connection-type');
                $branch->change_backup_connection_type = 1;
            }
        }   
          
        
        //Wireless
        $wireless_state = Input::get('wireless-state') == 'on' ? '1' : '0';
        if($branch->wireless_state != $wireless_state){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_wireless_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_wireless_state = 1;
            $branch->wireless_state = $wireless_state;          
        }
        if($branch->wireless_name != Input::get('wireless-username')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_wireless_name', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_wireless_name = 1;
            $branch->wireless_name = Input::get('wireless-username');       
        }
        if($branch->wireless_pass != Input::get('wireless-password')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_wireless_pass', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_wireless_pass = 1;
            $branch->wireless_pass = Input::get('wireless-password');      
        }
        
        
        //Private wireless    
        $private_wireless_state = Input::get('private-wireless-state') == 'on' ? '1' : '0';
        if($branch->private_wireless_state != $private_wireless_state){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_private_wireless_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_private_wireless_state = 1;
            $branch->private_wireless_state = $private_wireless_state;          
        }

        if($private_wireless_state == 1){
            if($branch->private_wireless_name != Input::get('private-wireless-username')){
                App\History::insert(
                    ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_private_wireless_name', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                $branch->change_private_wireless_name  = 1;
                $branch->private_wireless_name = Input::get('private-wireless-username');      
            }

            if($branch->private_wireless_pass != Input::get('private-wireless-password')){
                App\History::insert(
                    ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_private_wireless_pass', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                $branch->change_private_wireless_pass  = 1;
                $branch->private_wireless_pass = Input::get('private-wireless-password');     
            }

            if($branch->private_wireless_ip != Input::get('private-wireless-ip')){
                App\History::insert(
                    ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_private_wireless_ip', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                $branch->change_private_wireless_ip  = 1;
                $branch->private_wireless_ip = Input::get('private-wireless-ip');     
            }
        }


        //Security
        $hacking_protection = Input::get('security-state') == 'on' ? '1' : '0';
        if($branch->hacking_protection != $hacking_protection){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_hacking_protection', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_hacking_protection = 1;
            $branch->hacking_protection = $hacking_protection;         
        }   
        

        $adult_state = Input::get('adult-state') == 'on' ? '1' : '0';
        if($branch->adult_state != $adult_state){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_adult_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_adult_state = 1;
            $branch->adult_state = $adult_state;         
        }

        //Advanced script
        $advanced_script_state = Input::get('advanced-script-state') == 'on' ? '1' : '0';
        if( ($branch->advanced_script_state != $advanced_script_state) or ($advanced_script_state==1 and Input::get('edit-advanced-script')!="" and Input::get('edit-advanced-script')!=$branch->advanced_script) ){
            // make sure advanced script is not empty and have a change
            if(Input::get('edit-advanced-script')!="" and Input::get('edit-advanced-script')!=$branch->advanced_script){
                $branch->advanced_script = Input::get('edit-advanced-script');
            }
            App\History::insert(
            ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_advanced_script_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
        
            $branch->change_advanced_script_state = 1;
            $branch->advanced_script_state = $advanced_script_state;  
            

        }

        // if($advanced_script_state == 1){
        //     /*if($branch->advanced_script != Input::get('advanced-script')){
        //         App\History::insert(
        //             ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_advanced_script_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
        //         );
        //         $branch->change_advanced_script_state = 1;*/
        //         $branch->advanced_script = Input::get('edit-advanced-script');            
        //     //}
        // }

        //Log visited sites
        $log_history_state = Input::get('log-history-state') == 'on' ? '1' : '0';
        if($branch->users_log_history_state != $log_history_state){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_users_log_history_state', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_users_log_history_state = 1;
            $branch->users_log_history_state = $log_history_state;            
        }

        if($branch->users_log_history_type != Input::get('log-history-type')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_users_log_history_type', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_users_log_history_type  = 1;
            $branch->users_log_history_type = Input::get('log-history-type');     
        }
        
        //check if found changes
        $recordsCount = @count(Input::get('load-ip'));

        for($i = 0; $i < $recordsCount; $i++)
        { 
            $currCheckCount = LoadBalancing::where('ip', input::get('load-ip')[$i])->where('gateway', Input::get('load-gateway')[$i])->where('speed', Input::get('load-speed')[$i])->count(); 
            if($currCheckCount == 0){
                //return "found change";
                $changeFound=1;
                break;
            }
        }

        //check if count not equal each other
        if( LoadBalancing::where('branch_id', $branch->id)->count() != $recordsCount){$changeFound=1;};

        // delete last landlines and insert new one
        if(isset($changeFound) and $changeFound==1){
            LoadBalancing::where('branch_id', $branch->id)->delete(); 
            for($i = 0; $i < $recordsCount; $i++)
            { 
                if(isset(Input::get('load-username')[$i])){$load_username = Input::get('load-username')[$i]; }else{ $load_username = "";}
                if(isset(Input::get('load-password')[$i])){$load_password = Input::get('load-password')[$i]; }else{ $load_password = "";}

                LoadBalancing::insert(['branch_id' => $branch->id, 'ip' => Input::get('load-ip')[$i], 'gateway' => Input::get('load-gateway')[$i], 'speed' => Input::get('load-speed')[$i], 'type' => Input::get('load-type')[$i], 'user' => $load_username, 'pass' => $load_password]); 
            }
            Branches::where('id', '=', $branch->id)->update(['load_balance_state' => '1', 'change_load_balance_state' => '1']);
        }

        // Auto Login
        $branch->auto_login_expiry = Input::get('auto-login-expiry');
        if($branch->auto_login != Input::get('auto_login')){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'change_auto_login', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_auto_login  = 1;
            $branch->auto_login = Input::get('auto_login');   
        }

        // block_windows_update
        $block_windows_update = Input::get('block_windows_update') == 'on' ? '1' : '0';
        if($branch->block_windows_update != $block_windows_update){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'block_windows_update', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_block_windows_update = 1;
            $branch->block_windows_update = $block_windows_update;         
        }

        // block_torrent_download
        $block_torrent_download = Input::get('block_torrent_download') == 'on' ? '1' : '0';
        if($branch->block_torrent_download != $block_torrent_download){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'block_torrent_download', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_block_torrent_download = 1;
            $branch->block_torrent_download = $block_torrent_download;         
        }

        // block_downloading
        $block_downloading = Input::get('block_downloading') == 'on' ? '1' : '0';
        if($branch->block_downloading != $block_downloading){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'block_downloading', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_block_downloading = 1;
            $branch->block_downloading = $block_downloading;         
        }

        // antivirus
        $antivirus = Input::get('antivirus') == 'on' ? '1' : '0';
        if($branch->antivirus != $antivirus){
            App\History::insert(
                ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'antivirus', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            $branch->change_antivirus = 1;
            $branch->antivirus = $antivirus;
        }

        //check if found changes in bypassed
        $bypassRecordsCount = @count(Input::get('bypass-ip'));

        for($i = 0; $i < $bypassRecordsCount; $i++)
        { 
            if(isset(input::get('bypass-id')[$i])){
                $currCheckIDonly = App\Models\Bypassed::where('id', input::get('bypass-id')[$i])->count(); 
            }else{$currCheckIDonly = 0;}
            
            if( $currCheckIDonly >0 ){
                $currCheckAll = App\Models\Bypassed::where('id', input::get('bypass-id')[$i])->where('ip', input::get('bypass-ip')[$i])->where('mac', Input::get('bypass-mac')[$i])->where('port', Input::get('bypass-port')[$i])->value('id'); 
                if(!isset($currCheckAll)){
                    // changeFound
                    App\Models\Bypassed::where('id', input::get('bypass-id')[$i])->update(['ip' => input::get('bypass-ip')[$i], 'mac' => Input::get('bypass-mac')[$i], 'port' => Input::get('bypass-port')[$i], 'change_state' => '1', 'updated_at' => Carbon::now()]);
                }
            }elseif( $currCheckIDonly==0 ){
                // insert new record
                App\Models\Bypassed::insert(['branch_id' => $branch->id, 'ip' => Input::get('bypass-ip')[$i], 'mac' => Input::get('bypass-mac')[$i], 'port' => Input::get('bypass-port')[$i], 'change_state' => '1', 'state' => '0', 'created_at' => Carbon::now() ]); 
            }
        }

        // internetMode
        if(isset($branch->internet_mode)){
            if($branch->internet_mode != Input::get('internet-mode')){
                App\History::insert(
                    ['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'internet_mode', 'details' => '1', 'a_id' => Auth::user()->id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                $branch->change_internet_mode = 1;
                $branch->internet_mode = Input::get('internet-mode');         
            }
        }

        $branch->update();
        return redirect()->route('branches');
    }


    public function reboot($id, Request $request){
        $dt = Carbon::now();

        App\Branches::where('id', $id)->update([
            'reboot' => '1'
        ]);

        App\History::insert(
            ['type1' => 'mikrotik_reboot', 'type2' => 'Admin', 'operation' => 'mikrotik_reboot', 'details' => '1', 'a_id' => Auth::user()->id,'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
        );

        $request->session()->push('reboot_notify', App\Branches::where('id', $id)->value('name'));
        return redirect()->route('branches');

    }

    public function reset($id, Request $request){
        $dt = Carbon::now();

        App\Branches::where('id', $id)->update([
            'reset' => '1'
        ]);

        App\History::insert(
            ['type1' => 'mikrotik_reset', 'type2' => 'Admin', 'operation' => 'mikrotik_reset', 'details' => '1', 'a_id' => Auth::user()->id,'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
        );

        $request->session()->push('reset_notify', App\Branches::where('id', $id)->value('name'));
        return redirect()->route('branches');
    
    }
    public function load_balancing_delete($id, $branchid){
        LoadBalancing::where(['id' => $id, 'branch_id' => $branchid])->delete();  
        Branches::where('id', '=', $branchid)->update(['change_load_balance_state' => '1']);      
    }
    
    public function bypass_delete($id, $branchid){
        App\Models\Bypassed::where('id', $id)->update(['change_state' => '2']);
        //App\Models\Bypassed::where(['id' => $id, 'branch_id' => $branchid])->delete();
    }


}