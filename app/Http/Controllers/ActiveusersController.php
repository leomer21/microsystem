<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use DB;
use Redirect;
use Auth;
use Carbon\Carbon;

class ActiveusersController extends Controller
{

    public function Index(){
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['onlineusers'] == "1"){
            return view('back-end.user.activeusers');
        }else{
            return view('errors.404');
        }

    }

    public function controlSpeedLimit($id,$state,$acctuniqueid){
        
        $state = ($state == 'true')? 0 : 1;
        
        if($state == 1){
            // removeSpeedLimit
            App\Radacct::where('acctuniqueid',$acctuniqueid)->update(['realm'=>'2']); 
        }elseif($state == 0){
            // remove user from hosts to apply speed again
            App\Radacct::where('acctuniqueid',$acctuniqueid)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']);  
        }
    }
    
    public function Data(){
        $currentDate = date('Y-m-d');
        //$currentDate = "2016-08-01";
        $data = App\Models\RadacctActiveUsers::all();

        // check if any branch have ddwrt
        foreach(App\Branches::get() as $branch)
        {   
            if( $branch->radius_type == "ddwrt" ){ $foundDDWRT=1; }
        }
        if(!isset($foundDDWRT)){$foundDDWRT=0;}
		
        foreach ($data as $key => $value) {
            
            // $value->TodayUpload=App\Radacct::where('u_id',$value->u_id)->where('dates',$currentDate)->sum('acctinputoctets')+0;
            // $value->TodayDownload=App\Radacct::where('u_id',$value->u_id)->where('dates',$currentDate)->sum('acctoutputoctets')+0;
            $value->TodayUpload=$value->TodayUpload+0;
            $value->TodayDownload=$value->TodayDownload+0;
            //$value->count_users = App\Users::where('group_id',$value->id)->count();\
            
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
                        $endFinalEndDownModeDownloadType="K";
                        $endFinalEndDownModeDownloadSpeed=$downModeEquDowncheck[0];
                    }else{
                        //so this is Download speed in MB
                        $endFinalEndDownModeDownloadType="M";
                        $equStepMegaByteDownload=explode("M",$downModeendEquFirstEX[1]);
                        $endFinalEndDownModeDownloadSpeed=$equStepMegaByteDownload[0];
                    }
                    if($foundDDWRT==1){
                        $value->designed_end_speed = '<span class="label bg-success heading-text"><i class="icon-cloud-upload"></i> '.$endFinalEndBrowseModeUploadSpeed.''.$endFinalEndBrowseModeUploadType.' <i class="icon-cloud-download"></i> '.$endFinalEndBrowseModeDownloadSpeed.''.$endFinalEndBrowseModeDownloadType.'</span>';
                    }else{
                        $value->browseing_end_speed = 'Browsing:<i class="icon-cloud-upload"></i> '.$endFinalEndBrowseModeUploadSpeed.''.$endFinalEndBrowseModeUploadType.' <i class="icon-cloud-download"></i> '.$endFinalEndBrowseModeDownloadSpeed.''.$endFinalEndBrowseModeDownloadType;
                        $value->download_end_speed = 'Download:<i class="icon-cloud-upload"></i> '.$endFinalEndDownModeUploadSpeed.''.$endFinalEndDownModeUploadType.' <i class="icon-cloud-download"></i> '.$endFinalEndDownModeDownloadSpeed.''.$endFinalEndDownModeDownloadType;
                        $value->designed_end_speed = '<span class="label bg-success heading-text">'.$value->browseing_end_speed.'</span> <br> <span class="label bg-danger heading-text">'.$value->download_end_speed.'</span>';
                    }
                } 
            }
            
            //////////////////////////////////////////////////////////////////////////////////////
            // get Group speed to compare with current speed to build progress par 
            // this function return $finalGroupUploadSpeed, $finalGroupDownloadSpeed 
            $usedQuota = round((($value->TodayUpload + $value->TodayDownload)/1024)/1024,1);
            $totalQuata = round($value->total_quota/1024/1024,1);
			if( $usedQuota> $totalQuata ){
                // quota finished switch to end speed
                 // upload
                if( isset($endFinalEndDownModeUploadType) and $endFinalEndDownModeUploadType == "M"){
                    $finalGroupUploadSpeed = $endFinalEndDownModeUploadSpeed;
                }elseif( isset($endFinalEndDownModeUploadType) and $endFinalEndDownModeUploadType == "K" ){
                    $finalGroupUploadSpeed = round( $endFinalEndDownModeUploadSpeed / 1024,1 );
                }else{ $finalGroupUploadSpeed=0; }
                // download 
                if( isset($endFinalEndDownModeDownloadType) and $endFinalEndDownModeDownloadType == "M"){
                    $finalGroupDownloadSpeed = $endFinalEndDownModeDownloadSpeed;
                }elseif( isset($endFinalEndDownModeDownloadType) and $endFinalEndDownModeDownloadType == "K" ){
                    $finalGroupDownloadSpeed = round( $endFinalEndDownModeDownloadSpeed / 1024,1 );
                }else{ $finalGroupDownloadSpeed=0; }
            }else{
                // quota not finished use start speed
                // upload
                if( isset($finalEndDownModeUploadType) and $finalEndDownModeUploadType == "M"){
                    $finalGroupUploadSpeed = $finalEndDownModeUploadSpeed;
                }elseif( isset($finalEndDownModeUploadType) and $finalEndDownModeUploadType == "K" ){
                    $finalGroupUploadSpeed = round( $finalEndDownModeUploadSpeed / 1024,1 );
                }else{ $finalGroupUploadSpeed=0; }
                // download 
                if( isset($finalEndDownModeDownloadType) and $finalEndDownModeDownloadType == "M"){
                    $finalGroupDownloadSpeed =  $finalEndDownModeDownloadSpeed;
                }elseif( isset($finalEndDownModeDownloadType) and $finalEndDownModeDownloadType == "K" ){
                    $finalGroupDownloadSpeed = round( $finalEndDownModeDownloadSpeed / 1024,1 );  // here
                }else{ $finalGroupDownloadSpeed=0; }  
            }
            
            //////////////////////////////////////////////////////////////////////////////////////
            // get current upload speed
            // this function return $currentUploadSpeed, $currentDownloadSpeed
                // 1st step: get left side of equation
                $firstExplode = explode("/",$value->speed_rate); 
                if(isset($firstExplode[0])){
                    $uploadSpeedBeforeConvert1 = $firstExplode[0];
                    // 2nd step get Mbps or kbps or bps
                    $uploadSpeedBeforeConvertMbps = explode("Mbps",$uploadSpeedBeforeConvert1);
                    $uploadSpeedBeforeConvertKbps = explode("kbps",$uploadSpeedBeforeConvert1);
                    $uploadSpeedBeforeConvertBps = explode("bps",$uploadSpeedBeforeConvert1);

                    if( isset($uploadSpeedBeforeConvertMbps[1]) ){
                        // don't convert
                        $currentUploadSpeed = $uploadSpeedBeforeConvertMbps[0];
                    }elseif( isset($uploadSpeedBeforeConvertKbps[1]) ){
                        // convert from KB to MB
                        $currentUploadSpeed = round($uploadSpeedBeforeConvertKbps[0] / 1024, 1);
                    }elseif( isset($uploadSpeedBeforeConvertBps[1]) ){
                        // less than 1 kb so will return 0
                        $currentUploadSpeed = 0;
                    }else{
                        // maybe GB or an error
                        $currentUploadSpeed = 0;
                    }
                }else{// not found speed rate
                    $currentUploadSpeed = 0;
                }
            // get current download speed 
                // 1st step: get left side of equation
                $secondExplode = explode("/",$value->speed_rate); 
                if(isset($firstExplode[1])){
                    $downloadSpeedBeforeConvert1 = $secondExplode[1];
                    // 2nd step get Mbps or kbps or bps

                    $downloadSpeedBeforeConvertMbps = explode("Mbps",$downloadSpeedBeforeConvert1);
                    $downloadSpeedBeforeConvertKbps = explode("kbps",$downloadSpeedBeforeConvert1);
                    $downloadSpeedBeforeConvertBps = explode("bps",$downloadSpeedBeforeConvert1);
                    if( isset($downloadSpeedBeforeConvertMbps[1]) ){
                        // don't convert
                        $currentDownloadSpeed = $downloadSpeedBeforeConvertMbps[0];
                    }elseif( isset($downloadSpeedBeforeConvertKbps[1]) ){
                        // convert from KB to MB
                        $currentDownloadSpeed = round($downloadSpeedBeforeConvertKbps[0] / 1024,1);                  
                    }elseif( isset($downloadSpeedBeforeConvertBps[1]) ){
                        // less than 1 kb so will return 0
                        $currentDownloadSpeed = 0; 
                    }else{
                        // maybe GB or an error
                        $currentDownloadSpeed = 0;
                    }
                }else{// not found speed rate
                    $currentDownloadSpeed = 0;
                }
            ///////////////////////////////////////////////////////////////////////////////////////
            // NOW we have $currentUploadSpeed, $currentDownloadSpeed
            // And $finalGroupUploadSpeed, $finalGroupDownloadSpeed 
            // So we will calculate all Persentages
            if(isset($currentDownloadSpeed) and $currentDownloadSpeed != 0 and isset($finalGroupDownloadSpeed) and $finalGroupDownloadSpeed !=0){
                $value->downloadPersentage = round( $currentDownloadSpeed / $finalGroupDownloadSpeed * 100);
            }else{$value->downloadPersentage = 0;}
            if(isset($currentUploadSpeed) and $currentUploadSpeed != 0 and isset($finalGroupUploadSpeed) and $finalGroupUploadSpeed !=0){
                $value->uploadPersentage = round( $currentUploadSpeed / $finalGroupUploadSpeed * 100);
            }else{$value->uploadPersentage = 0;}
			
			// check if group have speed limit or not
			if( isset($finalGroupDownloadSpeed) and $finalGroupDownloadSpeed !=0 and isset($finalGroupUploadSpeed) and $finalGroupUploadSpeed !=0 and $value->speed_limit!="0K/0K" and $value->speed_limit!="" and isset($value->speed_limit) ){
				$value->foundSpeedLimitInGroup=1; 
			}else{$value->foundSpeedLimitInGroup=0;}
            // insert into json return
            $value->currentDownloadSpeed = $currentDownloadSpeed;
            $value->finalGroupDownloadSpeed = $finalGroupDownloadSpeed;
            $value->currentUploadSpeed = $currentUploadSpeed;
            $value->finalGroupUploadSpeed = $finalGroupUploadSpeed;
            // unset 
            unset($firstExplode);
            unset($uploadSpeedBeforeConvert1);
            unset($uploadSpeedBeforeConvert2);
            unset($currentUploadSpeed);
            unset($secondExplode);
            unset($downloadSpeedBeforeConvert1);
            unset($downloadSpeedBeforeConvert2);
            unset($currentDownloadSpeed);
            unset($usedQuota);
            unset($totalQuata);
            unset($finalGroupUploadSpeed);
            unset($finalGroupDownloadSpeed);

            unset($downloadSpeedBeforeConvertMbps);
            unset($downloadSpeedBeforeConvertKbps);
            unset($downloadSpeedBeforeConvertBps);
            unset($uploadSpeedBeforeConvertMbps);
            unset($uploadSpeedBeforeConvertKbps);
            unset($uploadSpeedBeforeConvertBps);
            
        }

        $marketingEnable = App\Settings::where('type', 'marketing_enable')->value('value');

        //////////////////////////////
        // rebuild original foreach //
        //////////////////////////////
        $foreachCounter = 0;
        foreach($data as $record){
            
            // get device name
            if(isset($record->devicename) and $record->devicename!=""){ $deviceName = $record->devicename; }else{ $deviceName = "";}
            if(isset($record->uptime) and $record->uptime!=""){ $uptime = $record->uptime; }else{ $uptime = "";}
            // get wifi signal icon
            if(isset($record->wifi_signal) and $record->wifi_signal >= 40){ $wifiSignal = "<img title='Excellent WiFi signal: $record->wifi_signal dBm' width='' src='assets/images/excellent_wifi.png'> "; }
            elseif(isset($record->wifi_signal) and $record->wifi_signal >= 25 and $record->wifi_signal <= 39){ $wifiSignal = "<img title='Good WiFi signal: $record->wifi_signal dBm' width='' src='assets/images/good_wifi.png'> "; }
            elseif(isset($record->wifi_signal) and $record->wifi_signal >= 15 and $record->wifi_signal <= 24){ $wifiSignal = "<img title='Fair WiFi signal: $record->wifi_signal dBm' width='' src='assets/images/fair_wifi.png'> "; }
            elseif(isset($record->wifi_signal) and $record->wifi_signal >= 1 and $record->wifi_signal <= 14){ $wifiSignal = "<img title='Weak WiFi signal: $record->wifi_signal dBm' width='' src='assets/images/weak_wifi.png'> "; }
            else{ $wifiSignal = "";}
            
            // split mobile number
            $userMobile = $record->u_phone;
            if( $marketingEnable != 1 ){
                $userMobile = substr($userMobile, 0, -8)."XXXX".substr($userMobile, -4);
            }
            
            $data2[$foreachCounter] = array('u_id'=>$record->u_id,
            'u_name'=>$record->u_name,
            'u_phone'=>$userMobile,
            'pms_room_no'=>$record->pms_room_no,
            'groupname'=>$record->groupname,
            'radacctid'=>$record->radacctid,
            'acctsessionid'=>$record->acctsessionid,
            'acctuniqueid'=>$record->acctuniqueid,
            'username'=>$record->username,
            'acctstarttime'=>$uptime,
            'acctstoptime'=>$record->acctstoptime,
            'acctsessiontime'=>$record->acctsessiontime,
            'acctinputoctets'=>$record->acctinputoctets,
            'acctoutputoctets'=>$record->acctoutputoctets,
            'callingstationid'=>$record->callingstationid,
            'framedipaddress'=>$record->framedipaddress,
            'branch_id'=>$record->branch_id,
            'group_id'=>$record->group_id,
            'network_id'=>$record->network_id,
            'total_quota'=>$record->total_quota,
            'speed_limit'=>$record->speed_limit,
            'end_speed'=>$record->end_speed,
            'branch_name'=>$record->branch_name,
            'realm'=>$record->realm,
            'TodayUpload'=>$record->TodayUpload,
            'TodayDownload'=>$record->TodayDownload,
            'browseing_speed'=>$record->browseing_speed,
            'designed_speed'=>$record->designed_speed,
            'designed_end_speed'=>$record->designed_end_speed,
            'download_speed'=> $record->download_speed,
            'download_end_speed'=>$record->download_end_speed,
            'browseing_end_speed'=> $record->browseing_end_speed,
            'deviceName'=> $deviceName,
            'suspend'=>$record->suspend,
            'currentDownloadSpeed'=>$record->designed_speed,
            'wifi_signal'=>$wifiSignal,
            'currentDownloadSpeed'=>$record->currentDownloadSpeed,
            'finalGroupDownloadSpeed'=>$record->finalGroupDownloadSpeed,
            'currentUploadSpeed'=>$record->currentUploadSpeed,
            'finalGroupUploadSpeed'=>$record->finalGroupUploadSpeed,
            'uploadPersentage'=>$record->uploadPersentage,
            'downloadPersentage'=>$record->downloadPersentage,
			'foundSpeedLimitInGroup'=>$record->foundSpeedLimitInGroup
			);
			
            $foreachCounter++;
            unset($wifiSignal);
            unset($userMobile);

        }

        /////////////////////////
        // Merge Hosts foreach //
        /////////////////////////
        foreach( App\Models\Hosts::where(['internet_access' => '0'])->get() as $hostData){

            // get wifi signal icon
            if(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 40){ $wifiSignal = "<img title='Excellent WiFi signal: $hostData->wifi_signal dBm' width='' src='assets/images/excellent_wifi.png'> "; }
            elseif(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 25 and $hostData->wifi_signal <= 39){ $wifiSignal = "<img title='Good WiFi signal: $hostData->wifi_signal dBm' width='' src='assets/images/good_wifi.png'> "; }
            elseif(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 15 and $hostData->wifi_signal <= 24){ $wifiSignal = "<img title='Fair WiFi signal: $hostData->wifi_signal dBm' width='' src='assets/images/fair_wifi.png'> "; }
            elseif(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 1 and $hostData->wifi_signal <= 14){ $wifiSignal = "<img title='Weak WiFi signal: $hostData->wifi_signal dBm' width='' src='assets/images/weak_wifi.png'>  "; }
            else{ $wifiSignal = "";}

            if(isset($hostData->u_id) and $hostData->u_id != 0){
                // user exist
                $userData = App\Users::where('u_id',$hostData->u_id)->first();
                if($hostData->bypassed == "true"){$tag = '<span class="label btn-success btn-ladda btn-ladda-spinner">Bypassed</span>';}
                elseif( $userData->suspend == "1"){ $tag = '<span class="label bg-danger heading-text">Suspended</span>'; }
                else{ $tag = '<span class="label bg-danger heading-text">Pending</span>'; }
                $hostUserName = $tag.' '.$userData->u_name;
                // if(isset($hostData->device_name) and $hostData->device_name!=""){$hostUser = $hostData->device_name;}
                // else{$hostUser = $hostData->mac;}
                $deviceName = $hostData->device_name;
                $hostUser = $hostData->mac;
                $hostPhone = $userData->u_phone;
                $hostNetworkID = $userData->network_id;
                if(isset($hostData->branch_id)){ $hostBranchID = $hostData->branch_id; }else{ $hostBranchID = $userData->branch_id; }
                $hostGroupID = $userData->group_id;
                $suspend = $userData->suspend;
                $hostGroupName = App\Groups::where('id',$userData->group_id)->value('name');
                $hostBranchName = App\Branches::where('id',$hostBranchID)->value('name');
            }else{
                // user not found in DB
                if($hostData->bypassed == "true"){$tag = '<span class="label btn-success btn-ladda btn-ladda-spinner">Bypassed</span>';}
                else{ $tag = '<span class="label bg-danger heading-text">Not registered</span>'; }
                $hostData->u_id = 0;
                $hostUserName = $tag;
                $deviceName = $hostData->device_name;
                $hostUser = $hostData->mac;
                $hostPhone = "";
                $hostNetworkID = "";
                if(isset($hostData->branch_id)){ $hostBranchID = $hostData->branch_id;}else{ $hostBranchID = ""; }
                $hostGroupID = "";
                $hostGroupName = "";
                if(isset($hostData->branch_id)){ $hostBranchName = App\Branches::where('id',$hostData->branch_id)->value('name'); }else{ $hostBranchName = ""; }
                $suspend="";
            }
            $data2[$foreachCounter] = array('u_id'=>$hostData->u_id,
                'u_name'=>$hostUserName,
                'u_phone'=>$hostPhone,
                'groupname'=>$hostGroupName,
                'deviceName'=>$deviceName,
                'branch_name'=>$hostBranchName,
                'suspend'=>$suspend,
                'radacctid'=>'',
                'acctsessionid'=>'',
                'acctuniqueid'=>'',
                'username'=>$hostUser,
                'acctstarttime'=>$hostData->uptime,
                'acctstoptime'=>'',
                'acctsessiontime'=>'',
                'acctinputoctets'=>'',
                'acctoutputoctets'=>'',
                'callingstationid'=>'',
                'framedipaddress'=>$hostData->address,
                'branch_id'=>$hostBranchID,
                'group_id'=>$hostGroupID,
                'network_id'=>$hostNetworkID,
                'total_quota'=>'',
                'speed_limit'=>'',
                'end_speed'=>'',
                'realm'=>'',
                'TodayUpload'=>'',
                'TodayDownload'=>'',
                'browseing_speed'=>'',
                'designed_speed'=>'',
                'designed_end_speed'=>'',
                'download_speed'=> '',
                'download_end_speed'=>'',
                'browseing_end_speed'=> '',
                'wifi_signal'=>$wifiSignal,
                'currentDownloadSpeed'=>'',
                'finalGroupDownloadSpeed'=>'',
                'currentUploadSpeed'=>'',
                'finalGroupUploadSpeed'=>'',
                'uploadPersentage'=>'',
                'downloadPersentage'=>'');
            $foreachCounter++;
            unset($userData);
            unset($hostUserName);
            unset($tag);
            unset($hostUser);
            unset($hostPhone);
            unset($hostNetworkID);
            unset($hostBranchID);
            unset($hostGroupID);
            unset($hostGroupName);
            unset($hostBranchName);
            unset($suspend);
            unset($wifiSignal);
            
        }

      if(!isset($data2)){$data2=array();}// fix datatable no data error

        return array('aaData'=>$data2);
    }

    public function Disconnect($id){

        $getUserData = App\Radacct::where('acctuniqueid',$id)->first();
        if(isset($getUserData)){
            $radacct_id = $getUserData->radacctid;
            $geted_User_Name = $getUserData->username;
            $geted_Framed_IP_Address = $getUserData->framedipaddress;
            $geted_nasipaddress = $getUserData->nasipaddress;
            $geted_branch_id = $getUserData->branch_id;

            //Branch Data
            $getbranchdata = App\Branches::where('id',$geted_branch_id)->first();

            $geted_secret = $getbranchdata->Radiussecret;
            $coaport = $getbranchdata->Radiusport;
            $ip = $getbranchdata->ip;
            $radiusType = $getbranchdata->radius_type;
    
            if($radiusType == "mikrotik"){
                App\Radacct::where('acctuniqueid',$id)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
            }else{
                // disconnect user from shell if branch type Aruba or DDWRT
                //$beExecuted='echo User-Name='.$geted_User_Name.',Framed-IP-Address='.$geted_Framed_IP_Address.' | radclient -x '.$geted_nasipaddress.':'.$coaport.' disconnect '.$geted_secret.'  2>&1 ';
                $beExecuted='echo User-Name='.$geted_User_Name.',Framed-IP-Address='.$geted_Framed_IP_Address.' | radclient -x '.$ip.':'.$coaport.' disconnect '.$geted_secret.'  2>&1 ';
                exec($beExecuted, $output);
            }
            
        }

        return Redirect::back();
        
        //return view('back-end.user.activeusers');
    }

    public function Disconnectandsuspend($id){

        $getUserData = App\Radacct::where('acctuniqueid',$id)->first();
        if(isset($getUserData)){
            $radacct_id = $getUserData->radacctid;
            $geted_User_Name = $getUserData->username;
            $geted_Framed_IP_Address = $getUserData->framedipaddress;
            $geted_nasipaddress = $getUserData->nasipaddress;
            $geted_branch_id = $getUserData->branch_id;
            $geted_u_id = $getUserData->u_id;

            // suspend user in db
            App\Users::where('u_id',$geted_u_id)->update(['suspend' => 1]);
            //Branch Data
            $getbranchdata = App\Branches::where('id',$geted_branch_id)->first();
            $geted_secret = $getbranchdata->Radiussecret;
            $coaport = $getbranchdata->Radiusport;
            $ip = $getbranchdata->ip;
            $radiusType = $getbranchdata->radius_type;
            
            // Insert log in history table to block user in Mikrotik in each branch
            $state = "suspend_user";
            $allUserMac = App\Users::where('u_id', $geted_u_id)->value('u_mac');
            $date = date('Y-m-d', strtotime(Carbon::now()));
            $time = date('H:i:s', strtotime(Carbon::now()));
            foreach(App\Branches::where('state', '1')->get() as $branch){
            
                $insert = new History();
                $insert->add_date = $date;
                $insert->add_time = $time;
                $insert->type1 = "suspend_unsuspend_user";
                $insert->type2 = "admin";
                $insert->operation = "$state";
                $insert->details = 1;
                $insert->notes = $allUserMac;
                $insert->a_id = Auth::user()->id;
                $insert->u_id = $geted_u_id;
                $insert->branch_id = $branch->id;
                $insert->save();
            }

            if($radiusType == "mikrotik"){
                App\Radacct::where('acctuniqueid',$id)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
            }else{
                // disconnect user
                $beExecuted='echo User-Name='.$geted_User_Name.',Framed-IP-Address='.$geted_Framed_IP_Address.' | radclient -x '.$ip.':'.$coaport.' disconnect '.$geted_secret.'  2>&1 ';
                exec($beExecuted, $output);
            }
        }

        return Redirect::back();
        
        //return view('back-end.user.activeusers');
    }

    // admin click unsuspend from online users page
    public function unsuspend($id){

        App\Users::where('u_id',$id)->update(['suspend' => '0']); 
        App\Models\Hosts::where('u_id',$id)->delete();
        return Redirect::back();
        
    }
    // admin click removeBypass from online users page
    public function removeBypass($mac){

        App\Models\Bypassed::where('mac', $mac)->update(['change_state' => '2']);
        App\Models\Hosts::where('mac',$mac)->delete();
        return Redirect::back();
        
    }

    // Clicked addUnregisteredUser from online users page to view model with 3 option to add new user or add to exist or bypass
    public function addUnregisteredUsers($mac){
        if(isset($mac)){
            return view('back-end.user.addUnregisteredUsers',array(
                'mac' => $mac,
                'networks' => App\Network::where('state','1')->get(),
                'branches' => App\Branches::where('state','1')->get(),
                'groups' => App\Groups::where(['is_active'=>'1','as_system'=>'0'])->get()
            ));
        }
    }

    // user has chosed one of 3 options (assign to existed user, add new user, bypass device)
    public function addNewUnregisteredUser(Request $request){
        
        App\Models\Hosts::where('mac',$request->mac)->delete(); 

        // check if assign device to user
        if($request->type == "assign"){

            date_default_timezone_set("Africa/Cairo");
            $today = date("Y-m-d");
            $today_time = date("g:i a");
            $user = App\Users::find($request->u_id);
            $created_at = $today." ".date("H:i:s");
            
            // check if there is mac before (to concatinate it with the new one)
            if(isset($user->u_mac) and $user->u_mac!="" and $user->u_mac!=" "){ $newMac = $user->u_mac.",".$request->mac; }
            else{$newMac = $request->mac;}
            // update user mac
            App\Users::where( 'u_id',$user->u_id )->update([ 'u_mac' => $newMac, 'updated_at' => $created_at ]);
            // insert "refresh2Access" record into `history` table to remove user from hosts to access
            foreach(App\Branches::where('state', '1')->get() as $branch){
                App\History::insert([['add_date' => $today, 'add_time' => $today_time, 'type1' => 'mikrotikapi', 'type2' => 'admin', 'operation' => 'refresh2Access', 'details' => 1, 'notes' => $newMac, 'a_id' => '402', 'u_id' => $user->u_id, 'branch_id' => $branch->id]]);
            }

            return redirect()->back();
        } 

        // check if new user
        if($request->type == "new"){

            // if( $request->phone=="null"){$request->phone='';}
            // if( $request->email=="null"){$request->email='';}

            $actions = new App\Users();
            $actions->u_name = $request->name;
            $actions->u_uname = strtolower($request->username);
            $actions->u_password = $request->password;
            $actions->u_mac = $request->mac;
            $actions->u_phone = $request->phone;
            $actions->u_email = $request->email;
            $actions->u_country = App\Settings::where('type', 'country')->value('value');
            $actions->network_id = App\Network::where('state', '1')->value('id');
            $actions->branch_id = $request->branch;
            $actions->group_id = $request->group;

            $actions->Registration_type = '2';
            $actions->u_state = '1';
            $actions->suspend = '0';
            $actions->u_gender = $request->gender;
            $actions->created_at = date('Y-m-d');

            $actions->save();
            return redirect()->back();
        }

        // check if bypass device
        if($request->type == "bypass"){

            App\Models\Bypassed::insert(['branch_id' => $request->branch, 'mac' => $request->mac, 'port' => $request->port, 'change_state' => '1', 'state' => '0', 'created_at' => Carbon::now() ]); 
            return redirect()->back();
        }
    }

    function updateTotalDownloadSpeed(Request $request) {
        $request->speed = round($request->speed * 1024,1);
        $netDownSpeed = App\History::where('operation','interface_out_net_speed')->where('branch_id',$request->branch_id)->update(['notes' => $request->speed]);
    }

    function updateTotalUploadSpeed(Request $request) {
        $request->speed = round($request->speed * 1024,1);
        $netDownSpeed = App\History::where('operation','interface_out_net_speed')->where('branch_id',$request->branch_id)->update(['details' => $request->speed]);
    }
}
