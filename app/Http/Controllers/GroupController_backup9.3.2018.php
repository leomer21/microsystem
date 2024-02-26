<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use DB;
use Auth;
use App\Models\URLFilter;

class GroupController extends Controller
{
    protected $Groups;
    public function index()
    {
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['groups'] == 1){
            return view('back-end.group.group',array('groups' =>App\Groups::all('name', 'id'), 'networks' => App\Network::all()));
        }else{
            return view('errors.404');
        }

    }
    public function Viewedit($id)
    {
        $groups = App\Groups::find($id);

        $speed_limit = explode("/" , $groups->speed_limit);
        $end_speed = explode("/" , $groups->end_speed);
        $speed_limit_counts = count($speed_limit);
        $end_speed_counts = count($end_speed);
        $equationStartSpeed="";
        $equationEndSpeed="";

        if(isset($groups->speed_limit) && $speed_limit_counts > "2"){
            $equationStartSpeed="1";
            $groups->speed_limit;
        }else{
            $groups->speed_limit = $this->conve($groups->speed_limit);
        }

        if(isset($groups->end_speed) && $end_speed_counts > "2"){
            $equationEndSpeed="1";
            $groups->end_speed;
        }else{
            $groups->end_speed = $this->conve($groups->end_speed);
        }

        return view('back-end.group.edit',array(
            'group' => $groups,
            'equationStartSpeed' => $equationStartSpeed,
            'equationEndSpeed' => $equationEndSpeed,
            'networks' => App\Network::all(),
            'url' => URLFilter::where('group_id', $id)->get()
        ));
    }

    public function Jasondata()
    {
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['groups']) {

            $firstDayMonth=date("Y-m")."-01";
            $lastDayMonth=date('Y-m-t', strtotime($firstDayMonth));

            $data = App\Models\GroupsNetwork::where('as_system', '0')->get();
            foreach ($data as $key => $value) {
                $value->count_online = App\Models\RadacctActiveUsers::where('group_id', $value->id)->count();
                $value->count_users = App\Users::where('group_id', $value->id)->count();
                // get Monthly Usage
                $monthlyUsageUpload=App\Radacct::where('group_id',$value->id)->whereBetween('dates',[$firstDayMonth, $lastDayMonth])->sum('acctinputoctets');
                $monthlyUsageDownload=App\Radacct::where('group_id',$value->id)->whereBetween('dates',[$firstDayMonth, $lastDayMonth])->sum('acctoutputoctets');
                $monthlyTotalUsage=round(($monthlyUsageUpload+$monthlyUsageDownload)/1024/1024/1024,1)." GB";
                $value->monthly_usage=$monthlyTotalUsage;
                // Get Total Usage
                $usageUpload=App\Radacct::where('group_id',$value->id)->sum('acctinputoctets');
                $usageDownload=App\Radacct::where('group_id',$value->id)->sum('acctoutputoctets');
                $totalUsage=round(($usageUpload+$usageDownload)/1024/1024/1024,2)." GB";
                $value->total_usage=$totalUsage;
            }
            return array('aaData' => $data);
        }else{
            return view('errors.404');
        }
    }


    public function Delete($id){

        $delete = App\Groups::where('id',$id)->first();
        $delete->delete();

        return redirect()->route('group');
    }
    public function state($id,$value){
        $value = ($value == 'true')? 1 : 0;
        App\Groups::where('id', '=', $id)->update(['is_active'=>$value]);
    }



    public function add(Request $request){

        $group = new App\Groups();
        $group->name = ucfirst(strtolower($request['name']));
        $group->is_active = $request['state'];
        $group->network_id = $request['network'];
        $group->radius_type = $request['r_type'];

        if(!isset($request['auto_login'])){$group->auto_login=0;}else{$group->auto_login = $request['auto_login'];}
        $group->limited_devices = $request['limited_devices'];

            if(!isset($request['url_redirect']) or $request['url_redirect']=="") {
                $group->url_redirect = "";
            } else {
                $group->url_redirect = "http://" . $request['url_redirect'];
            }
        
        $group->url_redirect_Interval = $request['url_redirect_Interval'];
        $group->idle_timeout = $request['idle_timeout'];
        $group->session_time = $request['session_time'];
        $group->port_limit = $request['u_multi_session'];
        $group->auto_login_expiry = $request['auto-login-expiry'];
        $quota_limit_upload = $request['quota_limit_upload'] * 1024 * 1024;

        $group->quota_limit_upload = $quota_limit_upload;
        $quota_limit_download = $request['quota_limit_download'] * 1024 * 1024;

        $group->quota_limit_download = $quota_limit_download;
        $quota_limit_total = $request['quota_limit_total'] * 1024 * 1024;

        $group->quota_limit_total = $quota_limit_total;

        if($request['equationchecks'] == "on") {
            $group->speed_limit = $request['equationstart'];
        }else{
            if ($request['speed_limit1'] != null && $request['speed_limit2'] != null) {
                $speed_limit1 = $request['speed_limit1'];
                $speed_limit2 = $request['stype1'];

                $speed_limit01 = $request['speed_limit2'];
                $speed_limit02 = $request['stype2'];
                $speed_limit = $speed_limit1 . $speed_limit2 . '/' . $speed_limit01 . $speed_limit02;
                $group->speed_limit = $speed_limit;
            }
        }
        if(isset($group->end_speed) && $group->end_speed != "0K/0K"){$group->if_downgrade_speed = 0; }
        else{$group->if_downgrade_speed = $request['values'];}
        if($request['equationcheckss'] == "on") {
            $group->end_speed = $request['equationend'];
        }else{
            if ($request['values'] == 1) {
                if ($request['end_speed1'] != null && $request['end_speed2'] != null) {
                    $end_speed1 = $request['end_speed1'];
                    $end_speed2 = $request['etype1'];
                    $end_speed01 = $request['end_speed2'];
                    $end_speed02 = $request['etype2'];
                    $end_speed = $end_speed1 . $end_speed2 . '/' . $end_speed01 . $end_speed02;

                    $group->end_speed = $end_speed;
                }
            }
        }
        $group->url_filter_state = $request['website-state'] == 'on' ? '1' : '0';
        $group->url_filter_type =  $request['website-type'];
        $group->renew = 1;
        $group->notes = $request['notes'];
        $group->save();
        
        $latestgroup = App\Groups::orderBy('id', 'desc')->first();

        if(count($request['websitename'])>0 and $group->url_filter_state==1)
        {
            $recordsCount = count($request['websitename']);
            App\Groups::where('id', '=', $latestgroup->id)->update(['change_url_filter'=>'1']);
            
            for($i = 0; $i < $recordsCount; $i++)
            { 
            URLFilter::insert(['group_id' => $latestgroup->id, 'url' => $request['websitename'][$i]]); 
            }
        }

        return redirect()->route('group');

    }

    public function update($id)
    {
        $group = App\Groups::find($id);

        $group->name = Input::get('name');
        $group->is_active = Input::get('state');
        $group->network_id = Input::get('network');
        $group->radius_type = Input::get('r_type');
        $group->url_redirect = Input::get('url_redirect');
        
        if(Input::get('auto_login')){$group->auto_login = Input::get('auto_login');}else{$group->auto_login=0;}
        $group->limited_devices = Input::get('limited_devices');
        
        if(Input::get('url_redirect')) {
            if (Input::get('url_redirect') == "http://" or Input::get('url_redirect') == "https://") {
                $group->url_redirect = "";
            } 
            else if (strpos(Input::get('url_redirect'), 'http://') !== false or strpos(Input::get('url_redirect'), 'https://') !== false) {
                $group->url_redirect = Input::get('url_redirect');
            }else {
                $group->url_redirect = "http://" . Input::get('url_redirect');
            }
        }
        $group->url_redirect_Interval = Input::get('url_redirect_Interval');
        $group->idle_timeout = Input::get('idle_timeout');
        $group->session_time = Input::get('session_time');
        $group->port_limit = Input::get('u_multi_session');
        $group->auto_login_expiry = Input::get('auto-login-expiry');
        $quota_limit_upload = Input::get('quota_limit_upload') * 1024 * 1024;
        $group->quota_limit_upload = $quota_limit_upload;
        $quota_limit_download = Input::get('quota_limit_download') * 1024 * 1024;
        $group->quota_limit_download = $quota_limit_download;
        $quota_limit_total = Input::get('quota_limit_total') * 1024 * 1024;
        $group->quota_limit_total = $quota_limit_total;
        if(Input::get('ifDownGradeSpeed') == 1){ $ifDownGradeSpeed = 1; }else{ $ifDownGradeSpeed = 0;}


        //disable end speed if end speed equal null or empty
        $end_speed01_test =  Input::get('end_speed1');
        $end_speed02_test =  Input::get('end_speed2');
        $equation_end_test = Input::get('equationend');
        $equation_check_end_test = Input::get('equationcheckss2');
        if($ifDownGradeSpeed == 1 and (!isset($end_speed01_test) or Input::get('end_speed1') == 0)){$ifDownGradeSpeed = 0; }
        if($ifDownGradeSpeed == 1 and (!isset($end_speed02_test) or Input::get('end_speed2') == 0)){$ifDownGradeSpeed = 0; }
        if($equation_check_end_test == "on" and ( !isset($equation_end_test) or $equation_end_test == "")) {$ifDownGradeSpeed = 0; }
        if(Input::get('ifDownGradeSpeed') == 1 and $equation_check_end_test == "on" and isset($equation_end_test)) {$ifDownGradeSpeed = 1; }

        $group->if_downgrade_speed=$ifDownGradeSpeed;

        if(Input::get('equationchecks2') == "on") {
            $group->speed_limit =  Input::get('equationstart');
        }else{
            if (Input::get('speed_limit1') != null && Input::get('speed_limit2') != null) {
                $speed_limit1_edit = Input::get('speed_limit1');
                $speed_limit2_edit = Input::get('stype1');
                $speed_limit01_edit = Input::get('speed_limit2');
                $speed_limit02_edit = Input::get('stype2');
                $speed_limit_edit = $speed_limit1_edit . $speed_limit2_edit . '/' . $speed_limit01_edit . $speed_limit02_edit;
                $group->speed_limit = $speed_limit_edit;
            } else {
                $group->speed_limit = "";
            }
        }
        if(Input::get('equationcheckss2') == "on") {
            $group->end_speed =  Input::get('equationend');
        }else{
            if (Input::get('end_speed1') != null && Input::get('end_speed2') != null) {
                $end_speed1_edit = Input::get('end_speed1');
                $end_speed2_edit = Input::get('etype1');
                $end_speed01_edit = Input::get('end_speed2');
                $end_speed02_edit = Input::get('etype2');
                $end_speed_edit = $end_speed1_edit . $end_speed2_edit . '/' . $end_speed01_edit . $end_speed02_edit;

                $group->end_speed = $end_speed_edit;
            } else {
                $group->end_speed = "";
            }
        }
        $url_filter_state = Input::get('website-state') == 'on' ? '1' : '0';
        
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
        
        if($url_filter_state==0 and $group->url_filter_state==1){
            $group->url_filter_state=0;
            App\Groups::where('id', '=', $group->id)->update(['change_url_filter'=>'1']);
        }

        // $recordsCount = count(Input::get('websitename'));
        
        // URLFilter::where('group_id', $group->id)->delete(); 

        // for($i = 0; $i < $recordsCount; $i++)
        // { 
        //    URLFilter::insert(['group_id' => $group->id, 'url' => Input::get('websitename')[$i]]); 
        // }

        $group->renew = 1;
        $group->notes = Input::get('notes');
        $group->update();

        return redirect()->route('group');
    }
    private function conve($string){
        $arr = [];
        $strings = (explode("/",$string));
        foreach($strings as $str){
            $arr[] = preg_replace("/[^0-9]/", '',$str);
            $arr[] = substr($str, -1);
        }
        if(!isset($arr[3])){
            $arr[0] = 0;
            $arr[1] = 'K';
            $arr[2] = 0;
            $arr[3] = 'K';
        }
        return $arr;
    }

    public function website_delete($id, $groupid){
        URLFilter::where(['id' => $id, 'group_id' => $groupid])->delete(); 
        App\Groups::where('id', '=', $groupid)->update(['change_url_filter'=>'1']);      
    }
}
