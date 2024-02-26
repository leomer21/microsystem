<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use DB;
use Auth;
use App\Models\URLFilter;
use Carbon\Carbon;

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
        $speed_limit_counts = @count($speed_limit);
        $end_speed_counts = @count($end_speed);
        $equationStartSpeed="";
        $equationEndSpeed="";

        if(isset($groups->speed_limit) && $speed_limit_counts > "2"){
            $equationStartSpeed="1";
            $groups->speed_limit;
        }else{
            //$groups->speed_limit = $this->conve($groups->speed_limit);
        }

        if(isset($groups->end_speed) && $end_speed_counts > "2"){
            $equationEndSpeed="1";
            $groups->end_speed;
        }else{
            //$groups->end_speed = $this->conve($groups->end_speed);
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

            // check if any branch have ddwrt
            foreach(App\Branches::get() as $branch)
            {   
                if( $branch->radius_type == "ddwrt" ){ $foundDDWRT=1; }
                if( $branch->radius_type == "aruba" ){ $foundDDWRT=1; }
            }
            if(!isset($foundDDWRT)){$foundDDWRT=0;}
            
            $data = App\Models\GroupsNetwork::where('as_system', '0')->get();
            foreach ($data as $key => $value) {
                // $value->count_online = App\Models\RadacctActiveUsers::where('group_id', $value->id)->count(); // execution time more that 1 min
                $value->count_online = App\Radacct::where('group_id', $value->id)->whereNull('acctstoptime')->count();
                $value->count_users = App\Users::where('group_id', $value->id)->count();
                // get Monthly Usage
                // $monthlyUsageUpload=App\Radacct::where('group_id',$value->id)->whereBetween('dates',[$firstDayMonth, $lastDayMonth])->sum('acctinputoctets');
                // $monthlyUsageDownload=App\Radacct::where('group_id',$value->id)->whereBetween('dates',[$firstDayMonth, $lastDayMonth])->sum('acctoutputoctets');
                $monthlyUsageUpload=$value->this_month_acctinputoctets;
                $monthlyUsageDownload=$value->this_month_acctoutputoctets;
                $monthlyTotalUsage=round(($monthlyUsageUpload+$monthlyUsageDownload)/1024/1024/1024,1);
                $value->monthly_usage=$monthlyTotalUsage;
                // // Get Total Usage
                // $usageUpload=App\Radacct::where('group_id',$value->id)->sum('acctinputoctets');
                // $usageDownload=App\Radacct::where('group_id',$value->id)->sum('acctoutputoctets');
                // $usageUpload=0;
                // $usageDownload=0;
                // $totalUsage=round(($usageUpload+$usageDownload)/1024/1024/1024,2)."GB";
                // $value->total_usage=$totalUsage;

                // Get Browseing and download speed
                if(isset($value->speed_limit))
                {   
                   $firstExplode=explode(" ",$value->speed_limit); 
                   if(isset($firstExplode[1])){
                    
                    //XXXXXXXXXXXXX Browseing speed XXXXXXXXXXXXXXXXXX
                    $secondExplode=explode(" ",$firstExplode[1]);

                    $equFirstEX=explode("/",$secondExplode[0]);
                    //upload
                    $equUPcheck=explode("K",$equFirstEX[0]);
                    if(isset($equUPcheck[1])){
                        //so this is upload speed in KB
                        $finalEndBrowseModeUploadType="K";
                        $finalEndBrowseModeUploadSpeed=$equUPcheck[0];
                    }else{
                        //so this is upload speed in MB
                        $finalEndBrowseModeUploadType="M";
                        $equStepMegaByteUpload=explode("M",$equFirstEX[0]);
                        $finalEndBrowseModeUploadSpeed=$equStepMegaByteUpload[0];
                    }

                    // download
                    $equDowncheck=explode("K",$equFirstEX[1]);
                    if(isset($equDowncheck[1])){
                        //so this is Download speed in KB
                        $finalEndBrowseModeDownloadType="K";
                        $finalEndBrowseModeDownloadSpeed=$equDowncheck[0];
                    }else{
                        //so this is Download speed in MB
                        $finalEndBrowseModeDownloadType="M";
                        $equStepMegaByteDownload=explode("M",$equFirstEX[1]);
                        $finalEndBrowseModeDownloadSpeed=$equStepMegaByteDownload[0];
                    }

                    //XXXXXXXXXXXXX Download speed XXXXXXXXXXXXXXXXXX
                    $thirdExplode=explode(" ",$firstExplode[0]);

                    $downModeEquFirstEX=explode("/",$thirdExplode[0]);
                    //upload
                    $downModeEquUPcheck=explode("K",$downModeEquFirstEX[0]);
                    if(isset($downModeEquUPcheck[1])){
                        //so this is upload speed in KB
                        $finalEndDownModeUploadType="K";
                        $finalEndDownModeUploadSpeed=$downModeEquUPcheck[0];
                    }else{
                        //so this is upload speed in MB
                        $finalEndDownModeUploadType="M";
                        $equStepMegaByteUpload=explode("M",$downModeEquFirstEX[0]);
                        $finalEndDownModeUploadSpeed=$equStepMegaByteUpload[0];
                    }

                    // download
                    $downModeEquDowncheck=explode("K",$downModeEquFirstEX[1]);
                    if(isset($downModeEquDowncheck[1])){
                        //so this is Download speed in KB
                        $finalEndDownModeDownloadType="K";
                        $finalEndDownModeDownloadSpeed=$downModeEquDowncheck[0];
                    }else{
                        //so this is Download speed in MB
                        $finalEndDownModeDownloadType="M";
                        $equStepMegaByteDownload=explode("M",$downModeEquFirstEX[1]);
                        $finalEndDownModeDownloadSpeed=$equStepMegaByteDownload[0];
                    }
                    if($foundDDWRT==1){
                        $value->designed_speed = '<span class="label bg-success heading-text"><i class="icon-cloud-upload"></i> '.$finalEndBrowseModeUploadSpeed.''.$finalEndBrowseModeUploadType.' <i class="icon-cloud-download"></i> '.$finalEndBrowseModeDownloadSpeed.''.$finalEndBrowseModeDownloadType.'</span>';
                    }else{
                        $value->browseing_speed = 'Browsing:<i class="icon-cloud-upload"></i> '.$finalEndBrowseModeUploadSpeed.''.$finalEndBrowseModeUploadType.' <i class="icon-cloud-download"></i> '.$finalEndBrowseModeDownloadSpeed.''.$finalEndBrowseModeDownloadType;
                        $value->download_speed = 'Download:<i class="icon-cloud-upload"></i> '.$finalEndDownModeUploadSpeed.''.$finalEndDownModeUploadType.' <i class="icon-cloud-download"></i> '.$finalEndDownModeDownloadSpeed.''.$finalEndDownModeDownloadType;
                        $value->designed_speed = '<span class="label bg-success heading-text">'.$value->browseing_speed.'</span> <br> <span class="label bg-danger heading-text">'.$value->download_speed.'</span>';
                    }
                   } 
                }
                // //////////////////////// End Speed //////////////////////////
                if(isset($value->end_speed))
                {   
                   $endFirstExplode=explode(" ",$value->end_speed); 
                   if(isset($endFirstExplode[1])){
                    
                    //XXXXXXXXXXXXX Browseing speed XXXXXXXXXXXXXXXXXX
                    $endSecondExplode=explode(" ",$endFirstExplode[1]);

                    $endEquFirstEX=explode("/",$endSecondExplode[0]);
                    //upload
                    $endEquUPcheck=explode("K",$endEquFirstEX[0]);
                    if(isset($endEquUPcheck[1])){
                        //so this is upload speed in KB
                        $endFinalEndBrowseModeUploadType="K";
                        $endFinalEndBrowseModeUploadSpeed=$endEquUPcheck[0];
                    }else{
                        //so this is upload speed in MB
                        $endFinalEndBrowseModeUploadType="M";
                        $equStepMegaByteUpload=explode("M",$endEquFirstEX[0]);
                        $endFinalEndBrowseModeUploadSpeed=$equStepMegaByteUpload[0];
                    }

                    // download
                    $equDowncheck=explode("K",$endEquFirstEX[1]);
                    if(isset($equDowncheck[1])){
                        //so this is Download speed in KB
                        $endFinalEndBrowseModeDownloadType="K";
                        $endFinalEndBrowseModeDownloadSpeed=$equDowncheck[0];
                    }else{
                        //so this is Download speed in MB
                        $endFinalEndBrowseModeDownloadType="M";
                        $equStepMegaByteDownload=explode("M",$endEquFirstEX[1]);
                        $endFinalEndBrowseModeDownloadSpeed=$equStepMegaByteDownload[0];
                    }

                    //XXXXXXXXXXXXX Download speed XXXXXXXXXXXXXXXXXX
                    $thirdExplode=explode(" ",$endFirstExplode[0]);

                    $downModeendEquFirstEX=explode("/",$thirdExplode[0]);
                    //upload
                    $downModeendEquUPcheck=explode("K",$downModeendEquFirstEX[0]);
                    if(isset($downModeendEquUPcheck[1])){
                        //so this is upload speed in KB
                        $endFinalEndDownModeUploadType="K";
                        $endFinalEndDownModeUploadSpeed=$downModeendEquUPcheck[0];
                    }else{
                        //so this is upload speed in MB
                        $endFinalEndDownModeUploadType="M";
                        $equStepMegaByteUpload=explode("M",$downModeendEquFirstEX[0]);
                        $endFinalEndDownModeUploadSpeed=$equStepMegaByteUpload[0];
                    }

                    // download
                    $downModeEquDowncheck=explode("K",$downModeendEquFirstEX[1]);
                    if(isset($downModeEquDowncheck[1])){
                        //so this is Download speed in KB
                        $finalEndDownModeDownloadType="K";
                        $finalEndDownModeDownloadSpeed=$downModeEquDowncheck[0];
                    }else{
                        //so this is Download speed in MB
                        $finalEndDownModeDownloadType="M";
                        $equStepMegaByteDownload=explode("M",$downModeendEquFirstEX[1]);
                        $finalEndDownModeDownloadSpeed=$equStepMegaByteDownload[0];
                    }
                    if($foundDDWRT==1){
                        $value->designed_end_speed = '<span class="label bg-success heading-text"><i class="icon-cloud-upload"></i> '.$endFinalEndBrowseModeUploadSpeed.''.$endFinalEndBrowseModeUploadType.' <i class="icon-cloud-download"></i> '.$endFinalEndBrowseModeDownloadSpeed.''.$endFinalEndBrowseModeDownloadType.'</span>';
                    }else{
                        $value->browseing_end_speed = 'Browsing:<i class="icon-cloud-upload"></i> '.$endFinalEndBrowseModeUploadSpeed.''.$endFinalEndBrowseModeUploadType.' <i class="icon-cloud-download"></i> '.$endFinalEndBrowseModeDownloadSpeed.''.$endFinalEndBrowseModeDownloadType;
                        $value->download_end_speed = 'Download:<i class="icon-cloud-upload"></i> '.$endFinalEndDownModeUploadSpeed.''.$endFinalEndDownModeUploadType.' <i class="icon-cloud-download"></i> '.$finalEndDownModeDownloadSpeed.''.$finalEndDownModeDownloadType;
                        $value->designed_end_speed = '<span class="label bg-success heading-text">'.$value->browseing_end_speed.'</span> <br> <span class="label bg-danger heading-text">'.$value->download_end_speed.'</span>';
                    }
                   } 
                }
                
            }
            return array('aaData' => $data);
        }else{
            return view('errors.404');
        }
    }


    public function Delete($id){

        $delete = App\Groups::where('id',$id)->first();
        $delete->delete();
		
		// assign all users to default group
		if(App\Groups::where('name','Default')){
			$newGroup = App\Groups::where('name','Default')->first();
		}elseif(App\Groups::where('name','default')){
			$newGroup = App\Groups::where('name','default')->first();
		}elseif(App\Groups::where('is_active','1')){
			$newGroup = App\Groups::where('is_active','1')->orderBy('id','asc')->first();
		}else{
			$newGroup = "notfound";
		}
		
		if($newGroup != "notfound"){
			App\Users::where('group_id',$id)->update(['group_id'=>$newGroup->id]);
		}
		
        return redirect()->route('group');
    }
    public function state($id,$value){
        
        $value = ($value == 'true')? 1 : 0;
        App\Groups::where('id', '=', $id)->update(['is_active'=>$value]);

        // get all users registers in this group
        foreach(App\Users::where('group_id',$id)->get() as $assignedUser)
        {   // get all active sessions
            foreach(App\Radacct::where('u_id',$assignedUser->u_id)->whereNull('acctstoptime')->get() as $session)
            {   // mark all active sessions to stop
                App\Radacct::where('acctuniqueid',$session->acctuniqueid)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
            }
        }
    }



    public function add(Request $request){

        $group = new App\Groups();
        $group->name = ucfirst(strtolower($request['name']));
        $group->is_active = $request['state'];
        $group->network_id = $request['network'];
        //$group->radius_type = $request['r_type'];

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
        if(!is_numeric($request['quota_limit_upload'])){$request['quota_limit_upload']=0;}
        $quota_limit_upload = $request['quota_limit_upload'] * 1024 * 1024;

        $group->quota_limit_upload = $quota_limit_upload;
        if(!is_numeric($request['quota_limit_download'])){$request['quota_limit_download']=0;}
        $quota_limit_download = $request['quota_limit_download'] * 1024 * 1024;

        $group->quota_limit_download = $quota_limit_download;
        if(!is_numeric($request['quota_limit_total'])){$request['quota_limit_total']=0;}
        $quota_limit_total = $request['quota_limit_total'] * 1024 * 1024;

        $group->quota_limit_total = $quota_limit_total;

        if($request['equationchecks'] == "on") {
            $group->speed_limit = $request['equationstart'];
        }else{

            if (Input::get('downSpeed_limit1') != null && Input::get('downSpeed_limit2') != null)
            {
                $speed_limit1_edit = Input::get('speed_limit1');
                $speed_limit2_edit = Input::get('stype1');
                $speed_limit01_edit = Input::get('speed_limit2');
                $speed_limit02_edit = Input::get('stype2');

                $downSpeed_limit1 = Input::get('downSpeed_limit1');
                $downSpeedType1 = Input::get('downSpeedType1');
                $downSpeed_limit2 = Input::get('downSpeed_limit2');
                $downSpeedType2 = Input::get('downSpeedType2');

                $startPriority = Input::get('startPriority');

                // check if user enter download speed or system have ddwrt hardware 
                if (Input::get('downSpeed_limit1') != null && Input::get('downSpeed_limit2') != null){ $speedType1=$downSpeedType1; $speedType2=$downSpeedType2; $upSpeedLimit=$downSpeed_limit1; $downSpeedLimit=$downSpeed_limit2; $replaceDownSpeedIntoAvarege=0;}
                else { $speedType1=$speed_limit2_edit; $speedType2=$speed_limit02_edit; $upSpeedLimit=$speed_limit1_edit; $downSpeedLimit=$speed_limit01_edit; $replaceDownSpeedIntoAvarege=1;}
                // convert MB into KB
                if($speedType1=="M"){ $downModeUploadSpeedConvToKB = $upSpeedLimit*1024; }else{ $downModeUploadSpeedConvToKB = $upSpeedLimit;}
                if($speedType2=="M"){ $downModeDownSpeedConvToKB = $downSpeedLimit*1024; }else{ $downModeDownSpeedConvToKB = $downSpeedLimit;}
                // set upload average in KB
                if($downModeUploadSpeedConvToKB > 0 and $downModeUploadSpeedConvToKB < 32){$avgUploadSpeed=$downModeUploadSpeedConvToKB-5;}
                elseif($downModeUploadSpeedConvToKB >= 32 and $downModeUploadSpeedConvToKB <= 64){$avgUploadSpeed=$downModeUploadSpeedConvToKB-10;}
                elseif($downModeUploadSpeedConvToKB > 64 and $downModeUploadSpeedConvToKB <= 128){$avgUploadSpeed=$downModeUploadSpeedConvToKB-25;}
                elseif($downModeUploadSpeedConvToKB > 128 ){$avgUploadSpeed=$downModeUploadSpeedConvToKB-50;}
                // set download average in KB
                if($downModeDownSpeedConvToKB > 0 and $downModeDownSpeedConvToKB < 32){$avgDownloadSpeed=$downModeDownSpeedConvToKB-5;}
                elseif($downModeDownSpeedConvToKB >= 32 and $downModeDownSpeedConvToKB <= 64){$avgDownloadSpeed=$downModeDownSpeedConvToKB-10;}
                elseif($downModeDownSpeedConvToKB > 64 and $downModeDownSpeedConvToKB <= 128){$avgDownloadSpeed=$downModeDownSpeedConvToKB-25;}
                elseif($downModeDownSpeedConvToKB > 128 and $downModeDownSpeedConvToKB <= 256){$avgDownloadSpeed=$downModeDownSpeedConvToKB-50;}
                elseif($downModeDownSpeedConvToKB > 256 and $downModeDownSpeedConvToKB <= 512){$avgDownloadSpeed=$downModeDownSpeedConvToKB-70;}
                elseif($downModeDownSpeedConvToKB > 512 ){$avgDownloadSpeed=$downModeDownSpeedConvToKB-100;}
                // replace download speed into average in case system have ddwrt
                if( $replaceDownSpeedIntoAvarege ==1 ){ $downloadSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K'; }
                else{ $downloadSpeedCode = $downSpeed_limit1 . $downSpeedType1 . '/' . $downSpeed_limit2 . $downSpeedType2; }
                // set final equation
                $browseingSpeedCode = $speed_limit1_edit . $speed_limit2_edit . '/' . $speed_limit01_edit . $speed_limit02_edit;
                $avarageSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K';
                $startSpeedSeconds = '60'; 
                $startSpeedLimitFullCode = $downloadSpeedCode . ' '. $browseingSpeedCode . ' ' .$avarageSpeedCode . ' ' . $startSpeedSeconds . ' ' . $startPriority;
                $group->speed_limit = $startSpeedLimitFullCode;
            }
        }
        if(isset($group->end_speed) && $group->end_speed != "0K/0K"){$group->if_downgrade_speed = 0; }
        else{$group->if_downgrade_speed = $request['values'];}
        if($request['equationcheckss'] == "on") {
            $group->end_speed = $request['equationend'];
        }else{
            if ($request['values'] == 1) {
                
                if (Input::get('end_speed1') != null && Input::get('end_speed2') != null) {
                    $end_speed1_edit = Input::get('end_speed1');
                    $end_speed2_edit = Input::get('etype1');
                    $end_speed01_edit = Input::get('end_speed2');
                    $end_speed02_edit = Input::get('etype2');

                    $endDownSpeed_limit1 = Input::get('endDownSpeed_limit1');
                    $endDownSpeedType1 = Input::get('endDownSpeedType1');
                    $endDownSpeed_limit2 = Input::get('endDownSpeed_limit2');
                    $endDownSpeedType2 = Input::get('endDownSpeedType2');

                    $endPriority = Input::get('endPriority');

                    // check if user enter download speed or system have ddwrt hardware 
                    if (Input::get('endDownSpeed_limit1') != null && Input::get('endDownSpeed_limit2') != null){ $speedType1=$endDownSpeedType1; $speedType2=$endDownSpeedType2; $upSpeedLimit=$endDownSpeed_limit1; $downSpeedLimit=$endDownSpeed_limit2; $replaceDownSpeedIntoAvarege=0;}
                    else { $speedType1=$end_speed2_edit; $speedType2=$end_speed02_edit; $upSpeedLimit=$end_speed1_edit; $downSpeedLimit=$end_speed01_edit; $replaceDownSpeedIntoAvarege=1;}
                    // convert MB into KB
                    if($speedType1=="M"){ $downModeUploadSpeedConvToKB = $upSpeedLimit*1024; }else{ $downModeUploadSpeedConvToKB = $upSpeedLimit;}
                    if($speedType2=="M"){ $downModeDownSpeedConvToKB = $downSpeedLimit*1024; }else{ $downModeDownSpeedConvToKB = $downSpeedLimit;}
                    // set upload average in KB
                    if($downModeUploadSpeedConvToKB > 0 and $downModeUploadSpeedConvToKB < 32){$avgUploadSpeed=$downModeUploadSpeedConvToKB-5;}
                    elseif($downModeUploadSpeedConvToKB >= 32 and $downModeUploadSpeedConvToKB <= 64){$avgUploadSpeed=$downModeUploadSpeedConvToKB-10;}
                    elseif($downModeUploadSpeedConvToKB > 64 and $downModeUploadSpeedConvToKB <= 128){$avgUploadSpeed=$downModeUploadSpeedConvToKB-25;}
                    elseif($downModeUploadSpeedConvToKB > 128 ){$avgUploadSpeed=$downModeUploadSpeedConvToKB-50;}
                    // set download average in KB
                    if($downModeDownSpeedConvToKB > 0 and $downModeDownSpeedConvToKB < 32){$avgDownloadSpeed=$downModeDownSpeedConvToKB-5;}
                    elseif($downModeDownSpeedConvToKB >= 32 and $downModeDownSpeedConvToKB <= 64){$avgDownloadSpeed=$downModeDownSpeedConvToKB-10;}
                    elseif($downModeDownSpeedConvToKB > 64 and $downModeDownSpeedConvToKB <= 128){$avgDownloadSpeed=$downModeDownSpeedConvToKB-25;}
                    elseif($downModeDownSpeedConvToKB > 128 and $downModeDownSpeedConvToKB <= 256){$avgDownloadSpeed=$downModeDownSpeedConvToKB-50;}
                    elseif($downModeDownSpeedConvToKB > 256 and $downModeDownSpeedConvToKB <= 512){$avgDownloadSpeed=$downModeDownSpeedConvToKB-70;}
                    elseif($downModeDownSpeedConvToKB > 512 ){$avgDownloadSpeed=$downModeDownSpeedConvToKB-100;}
                    // replace download speed into average in case system have ddwrt
                    if( $replaceDownSpeedIntoAvarege ==1 ){ $downloadSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K'; }
                    else{ $downloadSpeedCode = $endDownSpeed_limit1 . $endDownSpeedType1 . '/' . $endDownSpeed_limit2 . $endDownSpeedType2; }
                    // set final equation
                    $browseingSpeedCode = $end_speed1_edit . $end_speed2_edit . '/' . $end_speed01_edit . $end_speed02_edit;
                    $avarageSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K';
                    $endSpeedSeconds = '30'; 
                    $endSpeedLimitFullCode = $downloadSpeedCode . ' '. $browseingSpeedCode . ' ' .$avarageSpeedCode . ' ' . $endSpeedSeconds . ' ' . $endPriority;
                    $group->end_speed = $endSpeedLimitFullCode;

                } else {
                    $group->end_speed = "";
                    $group->if_downgrade_speed = 0;
                }
  
            }
        }
        $group->url_filter_state = $request['website-state'] == 'on' ? '1' : '0';
        $group->url_filter_type =  $request['website-type'];
        $group->renew = 1;
        $group->notes = $request['notes'];
        $group->expire_users_after_days = $request['expire_users_after_days'];
        $group->save();
        
        $latestgroup = App\Groups::orderBy('id', 'desc')->first();

        if(@count($request['websitename'])>0 and $group->url_filter_state==1)
        {
            $recordsCount = @count($request['websitename']);
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
        // get all users registers in this group
        foreach(App\Users::where('group_id',$id)->get() as $assignedUser)
        {   // get all active sessions
            foreach(App\Radacct::where('u_id',$assignedUser->u_id)->whereNull('acctstoptime')->get() as $session)
            {   // mark all active sessions to stop
                App\Radacct::where('acctuniqueid',$session->acctuniqueid)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
            }
        }

        $group = App\Groups::find($id);

        $group->name = Input::get('name');
        $group->is_active = Input::get('state');
        $group->network_id = Input::get('network');
        //$group->radius_type = Input::get('r_type');
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
            if (Input::get('speed_limit1') != null && Input::get('speed_limit1') != 0 && Input::get('speed_limit2') != null && Input::get('speed_limit2') != 0) {
                $speed_limit1_edit = Input::get('speed_limit1');
                $speed_limit2_edit = Input::get('stype1');
                $speed_limit01_edit = Input::get('speed_limit2');
                $speed_limit02_edit = Input::get('stype2');

                $downSpeed_limit1 = Input::get('downSpeed_limit1');
                $downSpeedType1 = Input::get('downSpeedType1');
                $downSpeed_limit2 = Input::get('downSpeed_limit2');
                $downSpeedType2 = Input::get('downSpeedType2');

                $startPriority = Input::get('startPriority');

                // (Start speed before consuming limitations) check if user enter download speed more than or equal browsing speed 
                if (Input::get('speed_limit1') != null && Input::get('speed_limit2') != null && Input::get('downSpeed_limit1') != null && Input::get('downSpeed_limit2') != null){
                    // convert all to KB
                    if(Input::get('stype1') != "K"){ $browseingModeUpSpeed = Input::get('speed_limit1') * 1024; }else{ $browseingModeUpSpeed = Input::get('speed_limit1'); }
                    if(Input::get('stype2') != "K"){ $browseingModeDownSpeed = Input::get('speed_limit2') * 1024; }else{ $browseingModeDownSpeed = Input::get('speed_limit2'); }
                    if(Input::get('downSpeedType1') != "K"){ $DownloadModeUpSpeed = Input::get('downSpeed_limit1') * 1024; }else{ $DownloadModeUpSpeed = Input::get('downSpeed_limit1'); }
                    if(Input::get('downSpeedType2') != "K"){ $DownloadModeDownSpeed = Input::get('downSpeed_limit2') * 1024; }else{ $DownloadModeDownSpeed = Input::get('downSpeed_limit2'); }
                    // check if download speed more than or equal browsing speed and discount it 16 kb  
                    // And covert browseing speeds to KB to avoid any error in Mikrotik
                    if( $DownloadModeUpSpeed >= $browseingModeUpSpeed ){ $downSpeed_limit1 = $browseingModeUpSpeed - 16; $downSpeedType1 = "K"; $speed_limit1_edit = $browseingModeUpSpeed; $speed_limit2_edit = "K";}
                    if( $DownloadModeDownSpeed >= $browseingModeDownSpeed ){ $downSpeed_limit2 = $browseingModeDownSpeed - 16; $downSpeedType2 = "K"; $speed_limit01_edit = $browseingModeDownSpeed; $speed_limit02_edit = "K";}
                    
                    // in case every speeds is corrent but the download mode type is KB covert browseing speeds to KB to avoid any error in Mikrotik
                    if( $downSpeedType1 == "K" ){ $speed_limit1_edit = $browseingModeUpSpeed; $speed_limit2_edit = "K"; }
                    if( $downSpeedType2 == "K" ){ $speed_limit01_edit = $browseingModeDownSpeed; $speed_limit02_edit = "K"; }
                }
                
                // check if user enter download speed or system have ddwrt hardware 
                if (Input::get('downSpeed_limit1') != null && Input::get('downSpeed_limit2') != null){ $speedType1=$downSpeedType1; $speedType2=$downSpeedType2; $upSpeedLimit=$downSpeed_limit1; $downSpeedLimit=$downSpeed_limit2; $replaceDownSpeedIntoAvarege=0;}
                else { $speedType1=$speed_limit2_edit; $speedType2=$speed_limit02_edit; $upSpeedLimit=$speed_limit1_edit; $downSpeedLimit=$speed_limit01_edit; $replaceDownSpeedIntoAvarege=1;}
                // convert MB into KB
                if($speedType1=="M"){ $downModeUploadSpeedConvToKB = $upSpeedLimit*1024; }else{ $downModeUploadSpeedConvToKB = $upSpeedLimit;}
                if($speedType2=="M"){ $downModeDownSpeedConvToKB = $downSpeedLimit*1024; }else{ $downModeDownSpeedConvToKB = $downSpeedLimit;}
                // set upload average in KB
                if($downModeUploadSpeedConvToKB > 0 and $downModeUploadSpeedConvToKB < 32){$avgUploadSpeed=$downModeUploadSpeedConvToKB-5;}
                elseif($downModeUploadSpeedConvToKB >= 32 and $downModeUploadSpeedConvToKB <= 64){$avgUploadSpeed=$downModeUploadSpeedConvToKB-10;}
                elseif($downModeUploadSpeedConvToKB > 64 and $downModeUploadSpeedConvToKB <= 128){$avgUploadSpeed=$downModeUploadSpeedConvToKB-25;}
                elseif($downModeUploadSpeedConvToKB > 128 ){$avgUploadSpeed=$downModeUploadSpeedConvToKB-50;}
                // set download average in KB
                if($downModeDownSpeedConvToKB > 0 and $downModeDownSpeedConvToKB < 32){$avgDownloadSpeed=$downModeDownSpeedConvToKB-5;}
                elseif($downModeDownSpeedConvToKB >= 32 and $downModeDownSpeedConvToKB <= 64){$avgDownloadSpeed=$downModeDownSpeedConvToKB-10;}
                elseif($downModeDownSpeedConvToKB > 64 and $downModeDownSpeedConvToKB <= 128){$avgDownloadSpeed=$downModeDownSpeedConvToKB-25;}
                elseif($downModeDownSpeedConvToKB > 128 and $downModeDownSpeedConvToKB <= 256){$avgDownloadSpeed=$downModeDownSpeedConvToKB-50;}
                elseif($downModeDownSpeedConvToKB > 256 and $downModeDownSpeedConvToKB <= 512){$avgDownloadSpeed=$downModeDownSpeedConvToKB-70;}
                elseif($downModeDownSpeedConvToKB > 512 ){$avgDownloadSpeed=$downModeDownSpeedConvToKB-100;}
                // replace download speed into average in case system have ddwrt
                if( $replaceDownSpeedIntoAvarege ==1 ){ $downloadSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K'; }
                else{ $downloadSpeedCode = $downSpeed_limit1 . $downSpeedType1 . '/' . $downSpeed_limit2 . $downSpeedType2; }
                // set final equation
                $browseingSpeedCode = $speed_limit1_edit . $speed_limit2_edit . '/' . $speed_limit01_edit . $speed_limit02_edit;
                $avarageSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K';
                $startSpeedSeconds = '60'; 
                $startSpeedLimitFullCode = $downloadSpeedCode . ' '. $browseingSpeedCode . ' ' .$avarageSpeedCode . ' ' . $startSpeedSeconds . ' ' . $startPriority;
                $group->speed_limit = $startSpeedLimitFullCode;
            } else {
                $group->speed_limit = "";
            }
        }
        if(Input::get('equationcheckss2') == "on") {
            $group->end_speed =  Input::get('equationend');
        }else{
            if (Input::get('end_speed1') != null && Input::get('end_speed1') != 0 && Input::get('end_speed2') != null && Input::get('end_speed2') != 0) {
                $end_speed1_edit = Input::get('end_speed1');
                $end_speed2_edit = Input::get('etype1');
                $end_speed01_edit = Input::get('end_speed2');
                $end_speed02_edit = Input::get('etype2');

                $endDownSpeed_limit1 = Input::get('endDownSpeed_limit1');
                $endDownSpeedType1 = Input::get('endDownSpeedType1');
                $endDownSpeed_limit2 = Input::get('endDownSpeed_limit2');
                $endDownSpeedType2 = Input::get('endDownSpeedType2');

                $endPriority = Input::get('endPriority');

                // (End speed after consuming limitations.) check if user enter download speed more than or equal browsing speed 
                if (Input::get('end_speed1') != null && Input::get('end_speed2') != null && Input::get('endDownSpeed_limit1') != null && Input::get('endDownSpeed_limit2') != null){
                    // convert all to KB
                    if(Input::get('etype1') != "K"){ $browseingModeUpSpeed = Input::get('end_speed1') * 1024; }else{ $browseingModeUpSpeed = Input::get('end_speed1'); }
                    if(Input::get('etype2') != "K"){ $browseingModeDownSpeed = Input::get('end_speed2') * 1024; }else{ $browseingModeDownSpeed = Input::get('end_speed2'); }
                    if(Input::get('endDownSpeedType1') != "K"){ $DownloadModeUpSpeed = Input::get('endDownSpeed_limit1') * 1024; }else{ $DownloadModeUpSpeed = Input::get('endDownSpeed_limit1'); }
                    if(Input::get('endDownSpeedType2') != "K"){ $DownloadModeDownSpeed = Input::get('endDownSpeed_limit2') * 1024; }else{ $DownloadModeDownSpeed = Input::get('endDownSpeed_limit2'); }
                    // check if download speed more than or equal browsing speed and discount it 16 kb
                    // And covert browseing speeds to KB to avoid any error in Mikrotik
                    if( $DownloadModeUpSpeed >= $browseingModeUpSpeed ){ $endDownSpeed_limit1 = $browseingModeUpSpeed - 16; $endDownSpeedType1 = "K"; $end_speed1_edit = $browseingModeUpSpeed; $end_speed2_edit = "K";}
                    if( $DownloadModeDownSpeed >= $browseingModeDownSpeed ){ $endDownSpeed_limit2 = $browseingModeDownSpeed - 16; $endDownSpeedType2 = "K"; $end_speed01_edit = $browseingModeDownSpeed; $end_speed02_edit = "K";}

                    // in case every speeds is corrent but the download mode type is KB covert browseing speeds to KB to avoid any error in Mikrotik
                    if( $endDownSpeedType1 == "K" ){ $end_speed1_edit = $browseingModeUpSpeed; $end_speed2_edit = "K"; }
                    if( $endDownSpeedType2 == "K" ){ $end_speed01_edit = $browseingModeDownSpeed; $end_speed02_edit = "K"; }
                }

                // check if user enter download speed or system have ddwrt hardware 
                if (Input::get('endDownSpeed_limit1') != null && Input::get('endDownSpeed_limit2') != null){ $speedType1=$endDownSpeedType1; $speedType2=$endDownSpeedType2; $upSpeedLimit=$endDownSpeed_limit1; $downSpeedLimit=$endDownSpeed_limit2; $replaceDownSpeedIntoAvarege=0;}
                else { $speedType1=$end_speed2_edit; $speedType2=$end_speed02_edit; $upSpeedLimit=$end_speed1_edit; $downSpeedLimit=$end_speed01_edit; $replaceDownSpeedIntoAvarege=1;}
                // convert MB into KB
                if($speedType1=="M"){ $downModeUploadSpeedConvToKB = $upSpeedLimit*1024; }else{ $downModeUploadSpeedConvToKB = $upSpeedLimit;}
                if($speedType2=="M"){ $downModeDownSpeedConvToKB = $downSpeedLimit*1024; }else{ $downModeDownSpeedConvToKB = $downSpeedLimit;}
                // set upload average in KB
                if($downModeUploadSpeedConvToKB > 0 and $downModeUploadSpeedConvToKB < 32){$avgUploadSpeed=$downModeUploadSpeedConvToKB-5;}
                elseif($downModeUploadSpeedConvToKB >= 32 and $downModeUploadSpeedConvToKB <= 64){$avgUploadSpeed=$downModeUploadSpeedConvToKB-10;}
                elseif($downModeUploadSpeedConvToKB > 64 and $downModeUploadSpeedConvToKB <= 128){$avgUploadSpeed=$downModeUploadSpeedConvToKB-25;}
                elseif($downModeUploadSpeedConvToKB > 128 ){$avgUploadSpeed=$downModeUploadSpeedConvToKB-50;}
                // set download average in KB
                if($downModeDownSpeedConvToKB > 0 and $downModeDownSpeedConvToKB < 32){$avgDownloadSpeed=$downModeDownSpeedConvToKB-5;}
                elseif($downModeDownSpeedConvToKB >= 32 and $downModeDownSpeedConvToKB <= 64){$avgDownloadSpeed=$downModeDownSpeedConvToKB-10;}
                elseif($downModeDownSpeedConvToKB > 64 and $downModeDownSpeedConvToKB <= 128){$avgDownloadSpeed=$downModeDownSpeedConvToKB-25;}
                elseif($downModeDownSpeedConvToKB > 128 and $downModeDownSpeedConvToKB <= 256){$avgDownloadSpeed=$downModeDownSpeedConvToKB-50;}
                elseif($downModeDownSpeedConvToKB > 256 and $downModeDownSpeedConvToKB <= 512){$avgDownloadSpeed=$downModeDownSpeedConvToKB-70;}
                elseif($downModeDownSpeedConvToKB > 512 ){$avgDownloadSpeed=$downModeDownSpeedConvToKB-100;}
                // replace download speed into average in case system have ddwrt
                if( $replaceDownSpeedIntoAvarege ==1 ){ $downloadSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K'; }
                else{ $downloadSpeedCode = $endDownSpeed_limit1 . $endDownSpeedType1 . '/' . $endDownSpeed_limit2 . $endDownSpeedType2; }
                // set final equation
                $browseingSpeedCode = $end_speed1_edit . $end_speed2_edit . '/' . $end_speed01_edit . $end_speed02_edit;
                $avarageSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K';
                $endSpeedSeconds = '30'; 
                $endSpeedLimitFullCode = $downloadSpeedCode . ' '. $browseingSpeedCode . ' ' .$avarageSpeedCode . ' ' . $endSpeedSeconds . ' ' . $endPriority;
                $group->end_speed = $endSpeedLimitFullCode;

            } else {
                $group->end_speed = "";
            }
        }
        $url_filter_state = Input::get('website-state') == 'on' ? '1' : '0';
        
        if(@count(Input::get('websitename'))>0 and $url_filter_state==1)
        {
            $group->url_filter_state = $url_filter_state;
            $group->url_filter_type = Input::get('website-type');

            $recordsCount = @count(Input::get('websitename'));
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

        // $recordsCount = @count(Input::get('websitename'));
        
        // URLFilter::where('group_id', $group->id)->delete(); 

        // for($i = 0; $i < $recordsCount; $i++)
        // { 
        //    URLFilter::insert(['group_id' => $group->id, 'url' => Input::get('websitename')[$i]]); 
        // }

        $group->renew = 1;
        $group->notes = Input::get('notes');
        $group->expire_users_after_days = Input::get('expire_users_after_days');
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
