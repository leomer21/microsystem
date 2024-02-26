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
use App\Models\URLFilter;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index($id)
    {
        $user = Users::find($id);
        return view('search')->with('Update', $user);
    }
    public function add_user(Request $request)
    {
        $actions = new App\Users();
        $actions->u_name = $request['fullname'];
        $actions->u_uname = strtolower($request['username']);
        //$actions->u_password = Hash::make($request['password']);
        $actions->u_password = $request['password'];
        $actions->u_gender = $request['gender'];
        $actions->u_lang = $request['lang'];
        $actions->u_address = $request['address'];
        $actions->u_email = $request['email'];
        $actions->u_phone = $request['phone'];
        $actions->u_mac = $request['mac'];
        $actions->branch_id = $request['branch_id'];
        //$actions->u_state = $request['state'];
        $actions->u_country = $request['countrie'];

        if(!$request['Suspend']){$suspendState=0;}else{$suspendState=1;}
        $actions->suspend = $suspendState;

        if(!$request['u_state']){$userStatedState=1;}else{$userStatedState=0;}
        $actions->u_state = $userStatedState;

        //$actions->Registration_type = $request['Registration'];
        $actions->Registration_type = "2";

             if($request['selfrulesState']==1){$selfrulesState='1';}else{$selfrulesState='0';}
             if($selfrulesState=="1"){

                $actions->Selfrules = $request['selfrulesState'];
                $action = new App\Groups();
                $action->as_system = 1;
                $action->is_active = 1;
                $action->network_id = $request['networkname'];
                $action->radius_type = $request['r_type'];
                $action->url_redirect = $request['url_redirect'];
                $action->url_redirect_Interval = $request['url_redirect_Interval'];
                $action->idle_timeout = $request['idle_timeout'];
                $action->session_time = $request['session_time'];
                //$action->auto_login_expiry = $request['auto-login-expiry'];
                $action->port_limit = $request['u_multi_session'];
                $action->renew = "1";

                //if(!isset($request['auto_login'])){$action->auto_login=0;}else{$action->auto_login = $request['auto_login'];}
                $action->limited_devices = $request['limited_devices'];

                $quota_limit_upload = $request['quota_limit_upload'] * 1024 * 1024;

                $action->quota_limit_upload = $quota_limit_upload;
                $quota_limit_download = $request['quota_limit_download'] * 1024 * 1024;

                $action->quota_limit_download = $quota_limit_download;
                $quota_limit_total = $request['quota_limit_total'] * 1024 * 1024;

                $action->quota_limit_total = $quota_limit_total;

                if($request['equationchecks'] == "on") {
                    $action->speed_limit = $request['equationstart'];
                }else{
                    if($request['speed_limit1'] != null && $request['speed_limit2'] != null) {
                        $speed_limit1 = $request['speed_limit1'];
                        $speed_limit2 = $request['stype1'];

                        $speed_limit01 = $request['speed_limit2'];
                        $speed_limit02 = $request['stype2'];
                        $speed_limit = $speed_limit1 . $speed_limit2 . '/' . $speed_limit01 . $speed_limit02;

                        $action->speed_limit = $speed_limit;
                    }
                }

                 if(isset($action->end_speed) && $action->end_speed != "0K/0K"){$action->if_downgrade_speed = 0; }
                 else{$action->if_downgrade_speed = $request['values'];}
                 if($request['equationcheckss'] == "on") {
                     $action->end_speed = $request['equationend'];
                 }else {
                     if ($request['values'] == 1) {
                         if ($request['end_speed1'] != null && $request['end_speed2'] != null) {
                             $end_speed1 = $request['end_speed1'];
                             $end_speed2 = $request['etype1'];
                             $end_speed01 = $request['end_speed2'];
                             $end_speed02 = $request['etype2'];
                             $end_speed = $end_speed1 . $end_speed2 . '/' . $end_speed01 . $end_speed02;

                             $action->end_speed = $end_speed;
                         }
                     }
                 }
                $action->url_filter_state = $request['website-state'] == 'on' ? '1' : '0';
                $action->url_filter_type =  $request['website-type'];
                $action->save();
                $latestgroup = App\Groups::orderBy('id', 'desc')->first();
                
                if(count($request['websitename'])>0 and $action->url_filter_state==1)
                {
                    $recordsCount = count($request['websitename']);
                    App\Groups::where('id', '=', $latestgroup->id)->update(['change_url_filter'=>'1']);
                    
                    for($i = 0; $i < $recordsCount; $i++)
                    { 
                    URLFilter::insert(['group_id' => $latestgroup->id, 'url' => $request['websitename'][$i]]); 
                    }
                }
                

        }else{
            $actions->Selfrules = $selfrulesState;
            $actions->network_id = $request['networkname'];
            $actions->group_id = $request['groupnames'];

        }

        if($selfrulesState=="1"){
            $actions->group_id = $latestgroup->id;
            $actions->network_id = $request['networkname'];
        }
        $actions->notes = $request['notes'];
        
        $actions->save();

        return redirect()->route('search');
 
    }

    public function edit_user(Request $request, $id){

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
        
        $user = App\Users::find($id);
        $user->updated_at = $todayDateTime;
        $user->u_name = Input::get('name');
        $user->u_uname = Input::get('username');
        $user->u_address = Input::get('address');
        //$user->u_password = Hash::make(Input::get('password'));
        $user->u_password = Input::get('password');
        $user->u_gender = Input::get('gender');
        $user->u_lang = Input::get('lang');
        $user->u_country = Input::get('countrie');
        $user->notes = Input::get('notesnotes');
        //$user->Registration_type = Input::get('Registration');
        //$user->u_state = Input::get('state') == 'on' ? '1' : '0';
        //$user->suspend = Input::get('Suspend');
        if(!Input::get('Suspend')){$suspendState=0;}else{$suspendState=1;}
        $user->suspend = $suspendState;
        $user->u_email = Input::get('email');
        $user->u_phone = Input::get('phone');
        $user->u_mac = Input::get('mac');
        $user->branch_id = Input::get('branch_id');

        // update package expiry date
        if(Input::get('expiry')){
            if($user->monthly_package_expiry != null){ $user->monthly_package_expiry = Input::get('expiry'); }
            elseif($user->validity_package_expiry != null){ $user->validity_package_expiry = Input::get('expiry'); }
            elseif($user->time_package_expiry != null){ $user->time_package_expiry = Input::get('expiry'); }
            elseif($user->bandwidth_package_expiry != null){ $user->bandwidth_package_expiry = Input::get('expiry'); }
        }  
        
        if(Input::get('selfrules')==1) {$selfrulesState='1';}else{$selfrulesState='0';}

        // get all active sessions to mark all active sessions to stop
        foreach(App\Radacct::where('u_id',$id)->whereNull('acctstoptime')->get() as $session)
        {   // mark all active sessions to stop
            App\Radacct::where('acctuniqueid',$session->acctuniqueid)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
        }
                
        if($selfrulesState=="1"){
            
                //$action->Selfrules = $selfrulesState;
                $action = new App\Groups();
                $action->as_system = 1;
                $action->is_active = 1;
                $action->network_id = $user->network_id;
                $action->radius_type = $request['r_type'];
                $action->url_redirect = $request['url_redirect'];
                $action->url_redirect_Interval = $request['url_redirect_Interval'];
                $action->idle_timeout = $request['idle_timeout'];
                $action->session_time = $request['session_time'];
                //$action->auto_login_expiry = $request['auto-login-expiry'];
                $action->port_limit = $request['u_multi_session'];
                $action->renew = "1";
                // 18/7/2019 remove open error from online users.
                if(!is_numeric($request['quota_limit_upload'])){$request['quota_limit_upload']=(int)$request['quota_limit_upload'];}
                if(!is_numeric($request['quota_limit_download'])){$request['quota_limit_download']=(int)$request['quota_limit_download'];;}
                if(!is_numeric($request['quota_limit_total'])){$request['quota_limit_total']=(int)$request['quota_limit_total'];}
                $action->name = "$user->u_name Special Rules";

                //if(Input::get('auto_login')){$action->auto_login = Input::get('auto_login');}else{$action->auto_login=0;}
                $action->limited_devices = Input::get('limited_devices');

                $quota_limit_upload = $request['quota_limit_upload'] * 1024 * 1024;
                if(isset($quota_limit_upload) and $quota_limit_upload == 0){ $action->quota_limit_upload = ""; }
                else{ $action->quota_limit_upload = $quota_limit_upload; }
                
                $quota_limit_download = $request['quota_limit_download'] * 1024 * 1024;
                if(isset($quota_limit_download) and $quota_limit_download == 0){ $action->quota_limit_download = ""; }
                else{ $action->quota_limit_download = $quota_limit_download; }

                $quota_limit_total = $request['quota_limit_total'] * 1024 * 1024;
                if(isset($quota_limit_total) and $quota_limit_total == 0){ $action->quota_limit_total = ""; }
                else{ $action->quota_limit_total = $quota_limit_total; }

                if(Input::get('ifDownGradeSpeed') == 1){ $ifDownGradeSpeed = 1; }else{ $ifDownGradeSpeed = 0;}

                //disable end speed if end speed equal null or empty
                $end_speed01_test =  Input::get('end_speed1');
                $end_speed02_test =  Input::get('end_speed2');
                $equation_end_test = Input::get('equationend');
                $equation_check_end_speed = Input::get('equationCheckOfEndSpeed');
                if($ifDownGradeSpeed == 1 and (!isset($end_speed01_test) or Input::get('end_speed1') == 0)){$ifDownGradeSpeed = 0; }
                if($ifDownGradeSpeed == 1 and (!isset($end_speed02_test) or Input::get('end_speed2') == 0)){$ifDownGradeSpeed = 0; }
                if($equation_check_end_speed == "on" and ( !isset($equation_end_test) or $equation_end_test == "")) {$ifDownGradeSpeed = 0; }
                if(Input::get('ifDownGradeSpeed') == 1 and $equation_check_end_speed == "on" and isset($equation_end_test)) {$ifDownGradeSpeed = 1; }

                $action->if_downgrade_speed=$ifDownGradeSpeed;

                // check start speed
                if(Input::get('equationCheckOfStartSpeed') == "on") {
                    $action->speed_limit =  Input::get('equationstart');
                }else{
                    if($request['speed_limit1'] != null && $request['speed_limit2'] != null) {
                        $speed_limit1 = $request['speed_limit1'];
                        $speed_limit2 = $request['stype1'];

                        $speed_limit01 = $request['speed_limit2'];
                        $speed_limit02 = $request['stype2'];
                        $speed_limit = $speed_limit1 . $speed_limit2 . '/' . $speed_limit01 . $speed_limit02;

                        $action->speed_limit = $speed_limit;
                    }
                }
               
                // check end speed
                if(Input::get('equationCheckOfEndSpeed') == "on") {
                    $action->end_speed =  Input::get('equationend');
                }else{
                    if($action->if_downgrade_speed == 1){
                        if($request['end_speed1'] != null && $request['end_speed2'] != null) {
                            $end_speed1 = $request['end_speed1'];
                            $end_speed2 = $request['etype1'];
                            $end_speed01 = $request['end_speed2'];
                            $end_speed02 = $request['etype2'];
                            $end_speed = $end_speed1 . $end_speed2 . '/' . $end_speed01 . $end_speed02;

                            $action->end_speed = $end_speed;
                        }
                    }
                }

                // Delete last group
                $gettedGroupIDForDelete = App\Users::find($id)->group_id;
                $as_system = Input::get('as_system');
                if($as_system==1){//delete group data ant insert data again 
                    $delete = App\Groups::where('id',$gettedGroupIDForDelete)->first();
                    $delete->delete();
                }
                // insert new group data after delete last group beta3 eluser
                $action->save();
                $newGroupId = App\Groups::orderBy('id', 'desc')->first();
                ///////////////////////////////
                $url_filter_state = Input::get('website-state') == 'on' ? '1' : '0';
                $group = App\Groups::find($newGroupId->id);
                if(count(Input::get('websitename'))>0 and $url_filter_state==1)
                {
                    $group->url_filter_state = $url_filter_state;
                    $group->url_filter_type = Input::get('website-type');

                    $recordsCount = count(Input::get('websitename'));
                    URLFilter::where('group_id', $group->id)->delete(); 
                    App\Groups::where('id', '=', $group->id)->update(['change_url_filter'=>'1']);
                    
                    for($i = 0; $i < $recordsCount; $i++)
                    { 
                        URLFilter::insert(['group_id' => $group->id, 'url' => Input::get('websitename')[$i]]); 
                    }
                }
                if($url_filter_state==0){
                    $group->url_filter_state=0;
                    App\Groups::where('id', '=', $group->id)->update(['change_url_filter'=>'1']);
                }
                $group->update();
                
                //  $id = App\Groups::orderBy('id', 'desc')->first();
            /*$Groups = new App\Groups();
            $Groups->as_system = 1;
            $Groups->network_id = Input::get('networkname');
            $Groups->radius_type = Input::get('r_type');
            $Groups->url_redirect = Input::get('url_redirect');
            $Groups->url_redirect_Interval = Input::get('url_redirect_Interval');
            $Groups->idle_timeout = Input::get('idle_timeout');
            $Groups->session_time = Input::get('session_time');
            $Groups->port_limit = Input::get('u_multi_session');
            $quota_limit_upload = Input::get('quota_limit_upload') * 1024 * 1024;
            $Groups->quota_limit_upload = $quota_limit_upload;
            $quota_limit_download = Input::get('quota_limit_download') * 1024 * 1024;
            $Groups->quota_limit_download = $quota_limit_download;
            $quota_limit_total = Input::get('quota_limit_total') * 1024 * 1024;
            $Groups->quota_limit_total = $quota_limit_total;

            if(Input::get('speed_limit1') != null && Input::get('speed_limit2') != null) {
                $speed_limit1_edit = Input::get('speed_limit2');
                $speed_limit2_edit = Input::get('stype2');
                $speed_limit01_edit = Input::get('speed_limit1');
                $speed_limit02_edit = Input::get('stype1');
                $speed_limit_edit = $speed_limit1_edit . $speed_limit2_edit . '/' . $speed_limit01_edit . $speed_limit02_edit;

                $Groups->speed_limit = $speed_limit_edit;
            }

            if (Input::get('values') == 1) {
                if(Input::get('end_speed1') != null && Input::get('end_speed2') != null) {
                    $end_speed1_edit = Input::get('end_speed1');
                    $end_speed2_edit = Input::get('etype1');
                    $end_speed01_edit = Input::get('end_speed2');
                    $end_speed02_edit = Input::get('etype2');
                    $end_speed_edit = $end_speed1_edit . $end_speed2_edit . '/' . $end_speed01_edit . $end_speed02_edit;

                    $Groups->end_speed = $end_speed_edit;
                }
            }
            $Groups->if_downgrade_speed = Input::get('values');*/
            //$Groups->save();

        }else{
            $user->Selfrules = $selfrulesState;
            //$user->network_id = Input::get('networkname');
            $user->group_id = Input::get('groupname');

        }
        
        //$id = App\Groups::orderBy('id', 'desc')->first();
        //$user->group_id = $id;
        if($selfrulesState=="1"){
            $user->selfrules = "1";
            $user->group_id = $newGroupId->id;
        }
        $user->save();

        return redirect()->back();
    }

    
}