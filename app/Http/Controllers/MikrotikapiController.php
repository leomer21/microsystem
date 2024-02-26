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

class MikrotikapiController extends Controller
{
    public function mikrotikapi(Request $request){
        // return "Done";
        $body = @file_get_contents('php://input');
        $body=explode('finishContent',$body);
        if(isset($body[0])){$jsonRequest = json_decode(  str_replace(",]}","]}",$body[0])  , true);}
        // ]} finishContent 
        // ,]}
        // str_replace(",]}","]}",$body[0]);
        $mikrotik_id=$request['identify'];
        $reboot=$request['reboot'];
        $branchid=$request['branchid'];
        $identify=$request['identify'];
        $secret=$request['secret'];
        
        $serial=$request['serial'];
        $auto=$request['auto'];
        $cpu=$request['cpu'];
        $uptime=$request['uptime'];
        // to avoid old versions of Microsystem script
        if(isset(explode('/',$request['ram'])[1])){
            // get remining percentage of ram
            $reminingRam = (explode('/',$request['ram']))[1] - (explode('/',$request['ram']))[0];
            $ram = ( $reminingRam / (explode('/',$request['ram']))[1] )*100 ;
            $ram = round($ram,1);
        }else{
            $ram=round($request['ram']/1024/1024,1);
        }
        
        // $ram=$request['ram'];
        $boardname=$request['boardname'];
        $uniquePassword=$request['realm']; 

        $publicIP=$request['publicip']; if(!isset($publicIP)){$publicIP="";}
        $DNSname=$request['dnsname']; if(!isset($DNSname)){$DNSname="";}
        
        $detectedIP = $request['detectedIP'];
        $detectedMac = $request['detectedMac'];

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		
		require_once '../config.php';

        ////////////////////////////////////
        // Hotspot Hosts
        ////////////////////////////////////
        
        if( isset($jsonRequest['HotspotHost']) ){
            // This function used for sync. between mikrotik and radacct DB to remove locked sessions from each others
            // and remove all registerd mac into users DB from mikrotik hosts in case the state is (not Authorized)
            // and Update this record to DB to provide admin choise to re-enable this user  
            if( count(DB::table('customers')->where('database',$identify)->where('password',$uniquePassword)->first()) > 0 )
            {
                $smallScript = "";
                // // Remove hosts from DB after 5 Minuts
                // foreach( DB::table($mikrotik_id.".hosts")->get() as $host){
                //     $lastCheckSeconds=strtotime($host->created_at);
                //     $timeNowSeconds = strtotime(Carbon::now());
                //     $recordSince = $timeNowSeconds - $lastCheckSeconds;
                //     if($recordSince > 300){
                //         DB::statement( 'DELETE FROM '.$mikrotik_id.".hosts".' where `id`='.$host->id.';');
                //     }
                // }
                if(isset($request['mode']) and $request['mode']=="SendOnlyNotAuthHosts"){
                    // Remove all hosts from DB if this system for Hotel
                    DB::statement( 'TRUNCATE TABLE '.$mikrotik_id.".hosts".';'); // Disable this because we remove record by record

                    $allHosts = [];
                    foreach($jsonRequest['HotspotHost'] as $hotspotHost){
                        $onlineMacArray[]=$hotspotHost['mac'];
                        if(isset($hotspotHost['up'])){ $thisUptime = $hotspotHost['up']; }else{ $thisUptime = ""; }
                        if(isset($hotspotHost['name'])){ $thisDeviceName = $hotspotHost['name']; }else{ $thisDeviceName = ""; }
                        if(isset($hotspotHost['bypass'])){ $thisBypass = $hotspotHost['bypass']; }else{ $thisBypass = "false"; }

                        // store internet access only into DB 
                        if($hotspotHost['auth'] != "true"){
                            if(isset($userData)){ $userID = $userData->u_id; }else{ $userID = 0; }
                            if($hotspotHost['auth'] == "true"){ $internetAccess=1;}else{$internetAccess=0;}
                            $allHosts[] = ['u_id' => $userID ,'branch_id' => $branchid ,'mac' => $hotspotHost['mac'], 'internet_access' => $internetAccess, 'device_name' => $thisDeviceName, 'address' => $hotspotHost['ip'], 'bypassed' => $thisBypass, 'uptime' => $thisUptime, 'created_at' => $todayDateTime];
                        }
                        
                        unset($userID);
                        // unset($countOnlineSessions);
                        unset($userData);
                        unset($internetAccess);
                        unset($userRate);
                        unset($thisUptime);
                        unset($thisDeviceName);
                        unset($thisBypass);
                    
                    }
                    DB::table($mikrotik_id.".hosts")->insert($allHosts);
                }else{
                    foreach($jsonRequest['HotspotHost'] as $hotspotHost){
                        $onlineMacArray[]=$hotspotHost['mac'];
                        if(isset($hotspotHost['up'])){ $thisUptime = $hotspotHost['up']; }else{ $thisUptime = ""; }
                        if(isset($hotspotHost['name'])){ $thisDeviceName = $hotspotHost['name']; }else{ $thisDeviceName = ""; }
                        if(isset($hotspotHost['bypass'])){ $thisBypass = $hotspotHost['bypass']; }else{ $thisBypass = "false"; }
    
                        //if(isset($branchid) and $branchid!=""){ $countOnlineSessions = DB::table($mikrotik_id.".radacct")->where('callingstationid',$hotspotHost['mac'])->where('branch_id', $branchid)->whereNull('acctstoptime')->count(); } // we disable it because we enable multi hotspot branch in 12/1/2021
                        if(isset($branchid) and $branchid!=""){ $countOnlineSessions = DB::table($mikrotik_id.".radacct")->where('callingstationid',$hotspotHost['mac'])->whereNull('acctstoptime')->count(); }
                        else{ $countOnlineSessions = DB::table($mikrotik_id.".radacct")->where('callingstationid',$hotspotHost['mac'])->whereNull('acctstoptime')->count(); }
                        $userData = DB::table($mikrotik_id.".users")->where('u_mac','like', '%'.$hotspotHost['mac'].'%')->first();
                        
                        // Check if user online in mikrotik and session not online in "radacct" DB
                        if( $hotspotHost['auth'] == "true" and $countOnlineSessions == 0)
                        {
                           $smallScript.=':if ([ /ip hotspot host find mac-address='.'"'.$hotspotHost['mac'].'"'.' ] != '.'""'.' ) do={ /ip hotspot host remove [find mac-address='.'"'.$hotspotHost['mac'].'"'.' ]; }  '."  \n";
                           // get last disconnection reason 
                           if(isset($branchid) and $branchid!=""){ $lastOnlineSession = DB::table($mikrotik_id.".radacct")->where('callingstationid',$hotspotHost['mac'])->where('branch_id', $branchid)->orderBy('radacctid','DESC')->limit(1)->first(); }
                           else{ $lastOnlineSession = DB::table($mikrotik_id.".radacct")->where('callingstationid',$hotspotHost['mac'])->orderBy('radacctid','DESC')->limit(1)->first(); }
                           if(isset($lastOnlineSession->acctstoptime)){
                                $smallScript.=':log warning "'.$hotspotHost['mac'].' Offline DB, last login: '.$lastOnlineSession->acctstoptime.', '.$lastOnlineSession->acctterminatecause.'"'."  \n"; 
                           }else{
                                $smallScript.=':log warning "'.$hotspotHost['mac'].' Offline DB, Not FOUND lastOnlineSession.'.'"'."  \n"; 
                           }
                           
                        }
                        // Ckeck if user not online in Mikrotik and session online in DB
                        if( $hotspotHost['auth'] != "true" and $countOnlineSessions > 0 )
                        {
                            DB::table($mikrotik_id.".radacct")->where('callingstationid',$hotspotHost['mac'])->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now()]);
                            // $smallScript.=" removed ".$hotspotHost['mac']." \n"; // for test and debug
                        }
                        // Check if user not online (not Authorized) in Mikrotik and already exist in users database
                        if( $hotspotHost['auth'] != "true" and isset($userData))
                        {   // make sure the user is not active and not suspeded and not bypassed
                            if( $userData->u_state=="1" and $userData->suspend=="0" and $thisBypass == "false" ){
                                $smallScript.=':if ([ /ip hotspot host find mac-address='.'"'.$hotspotHost['mac'].'"'.' ] != '.'""'.' and [ /ip hotspot active find mac-address='.'"'.$hotspotHost['mac'].'"'.' ] = '.'""'.' ) do={ /ip hotspot host remove [find mac-address='.'"'.$hotspotHost['mac'].'"'.' ]; }  '."  \n"; 
                                $smallScript.=':log warning "'.$hotspotHost['mac'].' Refresh2Access."'."  \n"; 
                            }
                        }
    
                        // Update device name in "radacct" table
                        // return $hotspotHost['mac'];
                        DB::table($mikrotik_id.".radacct")->where('callingstationid',$hotspotHost['mac'])->where('branch_id',$branchid)->whereNull('acctstoptime')->update(['groupname' => $thisDeviceName, 'acctauthentic' => $thisUptime]);
                        // store internet access only into DB 
                        if($hotspotHost['auth'] != "true"){
                            if($hotspotHost['auth'] == "true"){ $internetAccess=1;}else{$internetAccess=0;}
                            $hostData = DB::table($mikrotik_id.".hosts")->where('mac',$hotspotHost['mac'])->first();
                            if(isset($hostData)){
                                // Update this record to DB to provide admin choise to re-enable this user       
                                DB::table($mikrotik_id.".hosts")->where('id',$hostData->id)->update(['uptime' => $thisUptime, 'address' => $hotspotHost['ip'], 'internet_access' => $internetAccess, 'device_name' => $thisDeviceName, 'bypassed' => $thisBypass]);
                            }else{
                                // insert this record to DB to provide admin choise to re-enable this user
                                if(isset($userData)){ $userID = $userData->u_id; }else{ $userID = 0; }
                                DB::table($mikrotik_id.".hosts")->insert([['u_id' => $userID ,'branch_id' => $branchid ,'mac' => $hotspotHost['mac'], 'internet_access' => $internetAccess, 'device_name' => $thisDeviceName, 'address' => $hotspotHost['ip'], 'bypassed' => $thisBypass, 'uptime' => $thisUptime, 'created_at' => $todayDateTime]]);
                            }
                        }
                        
                        unset($userID);
                        unset($countOnlineSessions);
                        unset($userData);
                        unset($internetAccess);
                        unset($userRate);
                        unset($thisUptime);
                        unset($thisDeviceName);
                        unset($thisBypass);
                       
                    }

                    // delete record not uploaded from mikrotik // DOMINA
                    $allHosts = DB::table($mikrotik_id.".hosts")->where('branch_id',$branchid)->get();
                    foreach($allHosts as $thisHost){
                        if (in_array($thisHost->mac, $onlineMacArray)){ // return "found";
                            // make sure this session not online NOW
                            // hosts table must be contains records didnt have internet access (Authoruized) from our AAA
                            if( DB::table($mikrotik_id.".radacct")->where('callingstationid',$thisHost->mac)->whereNull('acctstoptime')->count() > 0 ){
                                DB::table($mikrotik_id.".hosts")->where('mac',$thisHost->mac)->delete();
                            }
                        }else{ // return "not found";
                            DB::table($mikrotik_id.".hosts")->where('mac',$thisHost->mac)->delete();
                        }
                    }

                }
                if(isset($request['mode']) and $request['mode']=="SendOnlyNotAuthHosts"){
                    // avoid this function: check if session online in DB and not found in Mikrotik
                    // because Mikrotik Hosts script not sending all hosts because of huge concurrent sessions
                }else{
                    // check if session online in DB and not found in Mikrotik
                    if(isset($branchid) and $branchid!=""){ $allOnlineSessions = DB::table($mikrotik_id.".radacct")->where('branch_id', $branchid)->whereNull('acctstoptime')->get(); }
                    else{ $allOnlineSessions = DB::table($mikrotik_id.".radacct")->whereNull('acctstoptime')->get(); }
                    foreach($allOnlineSessions as $thisSession){
                        
                            if (in_array($thisSession->callingstationid, $onlineMacArray)){
                                // return "found";
                                // $smallScript.=" Found online in two systems ".$thisSession->callingstationid." \n"; // for test and debug
                            }else{
                                // return "not found";
                                DB::table($mikrotik_id.".radacct")->where('radacctid',$thisSession->radacctid)->update(['acctstoptime' => $todayDateTime, 'acctterminatecause' => 'In-DB-not-found-Hosts']);
                                // $smallScript.=" removed ".$thisSession->callingstationid." \n"; 
                            }
                    }
                }

                
                $smallScript.=":delay 2 \n";
                //$smallScript.="/file remove auto2.rsc \n";
                $encoded=htmlentities($smallScript, ENT_NOQUOTES);
                $encoded=str_replace("&lt;","<",$encoded);
                $encoded=str_replace("&gt;",">",$encoded);
                $encoded=str_replace("&amp;","&",$encoded);
                return $encoded;
            
            }else{return ':log error "Hosts not updated '.\Carbon\Carbon::now().'"';}
        }

        
        ////////////////////////////////////
        // Fetch QUEUES and Interface rate
        ////////////////////////////////////
        
        if( isset($jsonRequest['Queues']) ){
            
            // This function to know users speed
            if( count(DB::table('customers')->where('database',$identify)->where('password',$uniquePassword)->first()) > 0 )
            {
                foreach($jsonRequest['Queues'] as $queue){
                    $getMacStep1=explode('-',$queue['queueMacName']);
                    if(isset($getMacStep1[1])){
                        $finalMac=explode('>',$getMacStep1[1]);
                    }elseif(count(explode(':',$queue['queueMacName'])) == 6){
                        $finalMac[0] = $queue['queueMacName'];
                    }else{
                        // there is manual queue added by admin, so we will put it as it (if true no problem it will update, if false no problem it will do nothing)
                        $finalMac[0] = $queue['queueMacName'];
                    }
                    // detected as a HIGH LOAD 2.11.2019
                    // if( isset($finalMac[0]) and DB::table($mikrotik_id.".radacct")->where('callingstationid',$finalMac[0])->count() > 0 ){
                        DB::table($mikrotik_id.".radacct")->where('callingstationid',$finalMac[0])->whereNull('acctstoptime')->update(['framedprotocol' => $queue['queueRate']]);
                    // }
                    unset($getMacStep1);
                    unset($finalMac);
                }
            }
            // insert and update OUT interface speed rates for online users page
            // check if record added before
            if( DB::table($identify.".history")->where('operation','interface_out_rate')->where('branch_id',$branchid)->count() == 0){
                if(isset($branchid) and $branchid!=""){
                    // insert new record
                    $interfaceOutUploadRate = round($request['upload']/1024,0);
                    $interfaceOutDownloadRate = round($request['download']/1024,0);
                    DB::table($identify.".history")->insert([['add_date' => $today, 'add_time' => $today_time, 'type1' => 'MikrotikApiController', 'type2' => 'auto', 'operation' => 'interface_out_rate', 'details' => $request['upload'], 'notes' => $request['download'], 'branch_id'=> $branchid ]]);
                    DB::table($identify.".history")->insert([['add_date' => $today, 'add_time' => $today_time, 'type1' => 'MikrotikApiController', 'type2' => 'auto', 'operation' => 'interface_out_net_speed', 'details' => '1024', 'notes' => '16384', 'branch_id'=> $branchid ]]);
                }
                                
            }elseif( isset($branchid) and $branchid!="" ){
                if(isset($branchid) and $branchid!=""){
                    // update upload speed into 'details', download into 'notes'
                    $interfaceOutUploadRate = round($request['upload']/1024,0);
                    $interfaceOutDownloadRate = round($request['download']/1024,0);
                    DB::table($identify.".history")->where('operation','interface_out_rate')->where('branch_id',$branchid)->update(['details' => $interfaceOutUploadRate, 'notes' => $interfaceOutDownloadRate]);
                }
            }
        }
        
        ////////////////////////////////////
        // Fetch WiFi signals
        ////////////////////////////////////
        
        if( isset($jsonRequest['Wireless']) ){
            // This function to know users speed
            if( count(DB::table('customers')->where('database',$identify)->where('password',$uniquePassword)->first()) > 0 )
            {
                foreach($jsonRequest['Wireless'] as $wireless){
                    
                    // update in wifi_signal in hosts table
                    DB::table($mikrotik_id.".hosts")->where('mac',$wireless['mac'])->update(['wifi_signal' => $wireless['SNR']]);
                    // update in wifi_signal in radacct table
                    if( DB::table($mikrotik_id.".radacct")->where('callingstationid',$wireless['mac'])->count() > 0 ){
                        DB::table($mikrotik_id.".radacct")->where('callingstationid',$wireless['mac'])->whereNull('acctstoptime')->update(['servicetype' => $wireless['SNR']]);
                    }
                    unset($getMacStep1);
                    unset($finalMac);
                }
            }
        }
        
        ////////////////////////////////////
        // reboot function
        ////////////////////////////////////
        if(isset($reboot) and $reboot=="reboot" and isset($identify) and $identify!="" and isset($uniquePassword) and $uniquePassword!=""){
            //if(DB::table($identify.'.branches')->where('Radiussecret',$secret)->first())
            if(count(DB::table('customers')->where('database',$identify)->where('password',$uniquePassword)->first()) > 0 )
            {
                if(isset($branchid) and $branchid!=""){DB::table($identify.'.radacct')->whereNull('acctstoptime')->where('branch_id',$branchid)->update(['acctstoptime' => \Carbon\Carbon::now()]);}
                else{DB::table($identify.'.radacct')->whereNull('acctstoptime')->update(['acctstoptime' => \Carbon\Carbon::now()]);}
                return "Done ".\Carbon\Carbon::now();
            }else{return "false".\Carbon\Carbon::now();}
        }

        // check if mikrotik not have branch id and system have one active branch
        if( (!isset($branchid) or $branchid=="") and (isset($identify) and $identify!="" and $identify!="unconfigured" ) ) {
            
            $checkActiveBranches=DB::table($identify.'.branches')->where('state',"1")->first();
            if(count($checkActiveBranches) == 1)
            {
                // this customer have one active branch so will put self branch id
                $branchid=$checkActiveBranches->id;
            }
        }

        ////////////////////////////////////
        // Auto configration function
        ////////////////////////////////////
        
        if(isset($auto) and $auto=="auto"){
            if($identify=="unconfigured")//Mikrotik need config script
            {
                if(isset($serial)){
                    $checkDB=DB::table('serials')->where('serial',$serial)->first();
                    if(count($checkDB)>0)
                    {  
                        $customerData=DB::table('customers')->where('id',$checkDB->customer_id)->first();
                        if(count($customerData)>0){
                            $identify=$customerData->database;
                            $url=$customerData->url;
                            $branchData=DB::table($identify.'.branches')->where('serial',$serial)->first();
                            if(count($branchData)>0)
                            {
                                $branchID=$branchData->id;
                                $branchName=$branchData->name;
                                
                                $script='/radius add address='.$systemMasterIP.' secret=microsystem service=hotspot timeout=3s realm='.$customerData->password.' comment='.$url.'
                                /system identity set name='.$identify.'
                                /ip hotspot profile set [ find name=hsprof1 ] dns-name=internet.microsystem.com.eg login-by=cookie,http-pap,mac use-radius=yes radius-accounting=yes radius-interim-update=1m radius-location-id='.$branchID.' radius-location-name="'.$branchName.'"'." \n";
                                //$script.="/file set 'hotspot/login.html' contents='<meta http-equiv='."'".'refresh'."'".'content='."'".'0; url=https://'.$url."/?identify=-$identify-"."'".'></head></html>";
                                // $script.=":if ([:len [/file find name=hotspot/login.html ]] > 0) do={ /file set ".'"'."hotspot/login.html".'"'." contents=".'"'."<meta http-equiv='refresh' content='0; url=https://".$url."/?identify=-$(identity)-$(location-id)-$(server-name)-$(server-address)-$(mac)'>".'"}'." \n";
                                // $script.=":if ([:len [/file find name=flash/hotspot/login.html ]] > 0) do={ /file set ".'"'."flash/hotspot/login.html".'"'." contents=".'"'."<meta http-equiv='refresh' content='0; url=https://".$url."/?identify=$(identity)-$(location-id)-$(server-name)-$(server-address)-$(mac)'>".'"}'." \n";
                                    // new update to send 
                                    $script.=":if ([:len [/file find name=hotspot/login.html ]] > 0) do={ /tool fetch url=".'"'."http://microsystem-eg.com/hotspot/login.php?url=$url".'"'." mode=https dst-path=".'"'."hotspot/login.html".'"'.'}'." \n";
                                    $script.=":if ([:len [/file find name=flash/hotspot/login.html ]] > 0) do={ /tool fetch url=".'"'."http://microsystem-eg.com/hotspot/login.php?url=$url".'"'." mode=https dst-path=".'"'."flash/hotspot/login.html".'"'.'}'." \n";
                                $script.='/ip hotspot walled-garden
                                add dst-host='.$url.'
                                add dst-host=http://'.$url.'
                                add dst-host=http://www.'.$url.'
                                add dst-host=https://'.$url.'
                                add dst-host=https://www.'.$url.'
                                add dst-host=www.'.$url.'
                                add dst-host=*.microsystem.com.eg
                                #add dst-host=*.bam.nr-data.net
                                #add dst-host=*.js-agent.newrelic.com
                                #add dst-host=*.1e100.net
                                #add dst-host=*.akamaihd.net
                                #add dst-host=*.akamai.net
                                #add dst-host=*.edgecastcdn.net
                                #add dst-host=*.edgekey.net
                                #add dst-host=*.akamaiedge.net
                                add dst-host=*.licdn.net
                                #add dst-host=*.fbcdn.net
                                add dst-host=*.facebook.com
                                add dst-host=*.mymicrosystem.*
                                add dst-host=*.mikrotik.*
                                add dst-host=*.microsystemapp.*
                                add dst-host=*.microsystem.*
                                #add dst-host=*.whatsapp.* comment=WhatsAppVerificationCode
                                #add dst-host=*.google.* comment=FirebaseSMSlogin
                                #add dst-host=www.google.com* comment=FirebaseSMSlogin
                                add dst-host=*.cloudfront.net'." \n";
								
								// Add default DNS
								$script.=":if ([/ip dns static find comment=MicrosystemDomain ]=".'""'.") do={ /ip dns static add address=$systemMasterIP comment=MicrosystemDomain name=$url ttl=1s; } \n";
								
								// // Force DNS
								// $script.="/ip firewall nat add chain=dstnat action=dst-nat to-addresses=10.5.50.1 to-ports=53 protocol=tcp dst-port=53 comment=ForceDNS \n";
								// $script.="/ip firewall nat add chain=dstnat action=dst-nat to-addresses=10.5.50.1 to-ports=53 protocol=udp dst-port=53 comment=ForceDNS \n";
								
								// Modify Netwatch IP
								$script.=":if ([/tool netwatch find host=8.8.8.8 ] != ".'""'.") do={ /tool netwatch set [ find host=8.8.8.8 ] disabled=no host=$systemMasterIP ; } \n";

                                if( DB::table($identify.'.settings')->where('type','google_client_id')->value('state') == "1" )
                                {
                                  $script.="add dst-host=*.googleapis.com \n";  
                                  $script.="add dst-host=*.googleusercontent.com \n";  
                                  $script.="add dst-host=*.gstatic.com \n";  
                                  $script.="add dst-host=*.accounts.youtube.com \n";  
                                  $script.="add dst-host=*.apis.google.com \n";  
                                  $script.="add dst-host=*.accounts.google.com \n";  
                                  $script.="add dst-host=*.l.google.com \n";  
                                  $script.="add dst-host=accounts.google.com \n";  
                                  $script.="add dst-host=www.google.com \n";
                                }

                                if( DB::table($identify.'.settings')->where('type','facebook_client_id')->value('state') == "1" )
                                {
                                  $script.="add dst-host=*.facebook.com \n";  
                                  $script.="add dst-host=facebook.com \n";  
                                  $script.="add dst-host=*.fbcdn.net \n";  
                                }

                                if( DB::table($identify.'.settings')->where('type','twitter_client_id')->value('state') == "1" )
                                {
                                  $script.="add dst-host=*.twitter.com \n";  
                                  $script.="add dst-host=twitter.com \n";  
                                  $script.="add dst-host=*.twimg.com \n";  
                                }

                                if( DB::table($identify.'.settings')->where('type','linkedin_client_id')->value('state') == "1" )
                                {
                                  $script.="add dst-host=*linkedin.com* \n";  
                                }

                                // set SSID as network name if wireless enables
                                $networkName=DB::table($identify.'.networks')->value('name');
                                $script.=':if ([/interface find name=wlan1 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan1 ] band=2ghz-g/n frequency=auto disabled=no mode=ap-bridge ssid='.'"'.$networkName.'"'.'; }'."\n"; 
								$script.=':if ([/interface find name=wlan2 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan2 ] frequency=auto disabled=no mode=ap-bridge ssid='.'"'.$networkName.'"'.'; }'."\n"; 


                                // VPN connection
                                if(isset($customerData->password) and $customerData->password!=""){

                                    // add VPN connection
                                    $script.='/interface sstp-client add connect-to=vpnip.microsystem.com.eg:9090 disabled=no http-proxy=0.0.0.0:9090 name="cloud" profile=default-encryption verify-server-address-from-certificate=no password='.'"'.$customerData->password.'"'.' user='.'"'.$identify.'"'."; \n";
                                    //$script.='/interface l2tp-client add connect-to=52.233.172.210 disabled=no name=cloud password='.'"'.$customerData->password.'"'.' user='.'"'.$identify.'"'."; \n";

                                    // set password
                                    $script.=':if ( [/user find name=microsystem] !='.'""'.') do={ /user set [find name=microsystem] group=full password='.'"'.$customerData->password.'"'.' } else={ '."\n";
                                    $script.='/user add group=full name=microsystem password='.'"'.$customerData->password.'"'.' } '."\n";
                                }
                                // check if enable wireless
                                if($branchData->wireless_state==1)
                                {
                                    //enable wireless
                                    if(isset($branchData->wireless_name) and $branchData->wireless_name!=""){$ssid=$branchData->wireless_name;}else{$ssid="Microsystem";}
                                    $script.='/interface wireless set [ find default-name=wlan1 ] band=2ghz-g/n frequency=auto disabled=no mode=ap-bridge ssid='.'"'.$ssid.'"'."; \n";
									$script.='/interface wireless set [ find default-name=wlan2 ] frequency=auto disabled=no mode=ap-bridge ssid='.'"'.$ssid.'"'."; \n";

                                    // check if have password
                                    if(isset($branchData->wireless_pass) and $branchData->wireless_pass != "")
                                    {
                                        //return $branchData->wireless_pass;
                                        // add new security profile and assigned to wireless
                                        $script.=':if ([/interface wireless security-profiles find name=profile1 ] != '.'""'.') do={ /interface wireless security-profiles remove profile1}'." \n"; 
                                        $script.='/interface wireless security-profiles add name=profile1 authentication-types=wpa-psk,wpa2-psk eap-methods='.'""'.' management-protection=allowed mode=dynamic-keys supplicant-identity='.'""'.' wpa-pre-shared-key='.$branchData->wireless_pass.' wpa2-pre-shared-key='.$branchData->wireless_pass." \n";
                                        $script.="/interface wireless set [ find default-name=wlan1 ]  security-profile=profile1 \n";
										$script.="/interface wireless set [ find default-name=wlan2 ]  security-profile=profile1 \n";
                                    }
                                }

                                // enable URL filter
                                //$script.="/ip firewall nat add action=redirect chain=dstnat comment=DNS dst-port=53 protocol=tcp to-ports=53 \n";
                                //$script.="/ip firewall nat add action=redirect chain=dstnat dst-port=53 protocol=udp to-ports=53 \n"; 
 
                                // set keepalive-timeout to 10 min
                                $script.="/ip hotspot user profile set [ find default=yes ] keepalive-timeout=10m \n"; 
                                
                                // $encoded=htmlentities($script, ENT_NOQUOTES);
                                // $encoded=str_replace("&lt;","<",$encoded);
                                // $encoded=str_replace("&gt;",">",$encoded);

                                // return $encoded;
                                $completeUnconfiguredCycle=1;
                                ////return echo html_entity_decode($return, ENT_NOQUOTES); // Does not convert any quotes
                            }
                        }
                    }
                    
                }
                
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // if(isset($completeUnconfiguredCycle) or !isset($completeUnconfiguredCycle)){

            //////////////////////////////////////////////////////
            //         Mikrotik waiting any other event
            //////////////////////////////////////////////////////
			
			if( (isset($branchid) and $branchid!="") or ( isset($serial) and $serial!="" and $identify!="unconfigured") or ( isset($completeUnconfiguredCycle)) )
            {
                
                $finalBranchID=DB::table($identify.'.branches')->where('serial',$serial)->first();
                
                if(!isset($finalBranchID)){$finalBranchID=DB::table($identify.'.branches')->where('id',$branchid)->first();}

                // found branch record
                if(isset($finalBranchID)) 
                { 
                    // update mikrotik usage
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['last_check' => \Carbon\Carbon::now(),'cpu'=> $cpu, 'uptime'=> $uptime, 'ram'=>$ram, 'boardname'=>$boardname, 'ip'=>$publicIP, 'url'=>$DNSname ]);
                    
                    // create new script variable or continue on unconfigured script
                    if(isset($script)){$script.="\n";}else{$script="";}
                    
                    ///////////////////////////////
                    //         Start Check
                    ///////////////////////////////

                    //check advanced script
                    if($finalBranchID->change_advanced_script_state=="1" and $finalBranchID->advanced_script_state=="1")
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_advanced_script_state' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_advanced_script_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        $script.="$finalBranchID->advanced_script";
                        return $script;
                        
                    }

                    // check change hotspot state
                    if($finalBranchID->change_state==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_state' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->state == 1){ // Enable Hotspot and netwatch
                            $script.="/ip hotspot set [ find name=hotspot1 ] disabled=no; \n";
                            $script.=":if ([/tool netwatch find host=$systemMasterIP ] != ".'""'.") do={ /tool netwatch set [ find host=$systemMasterIP ] disabled=no ; } \n";
                        }
                        if($finalBranchID->state == 0){ // Disable Hotspot and netwatch
                            $script.="/ip hotspot set [ find name=hotspot1 ] disabled=yes; \n";
                            $script.=":if ([/tool netwatch find host=$systemMasterIP ] != ".'""'.") do={ /tool netwatch set [ find host=$systemMasterIP ] disabled=yes ; } \n";
                        }
                    }
                    
                    // check reset configration
                    if($finalBranchID->reset==1)
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['reset' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'reset', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        return "system reset";
                    }

                    // check reboot
                    if($finalBranchID->reboot==1)
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['reboot' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'reboot', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        return "system reboot";
                    }

                    //////////////////////////////////
                    //    Load Balanceing
                    //////////////////////////////////
                    if( $finalBranchID->load_balance_state==1 and ($finalBranchID->change_load_balance_state==1 or isset($completeUnconfiguredCycle)) )
                    {   
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_load_balance_state' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_load_balance_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        $landLines=DB::table($identify.'.load_balancing')->where('branch_id',$finalBranchID->id)->get();
                    
                        if(count($landLines)>=2){

                            ///////////////////////////////////////////////////////////
                            // check if load balanceing lines equal each others or not
                            ///////////////////////////////////////////////////////////

                            //check equal or not and get small line
                            // Output = $smalLineID
                            // Output = $smalLineSpeed
                            foreach($landLines as $line)
                            {
                                $currentLineSpeed=$line->speed;
                                if(!isset($smalLineSpeed)){$smalLineSpeed=$currentLineSpeed; $smalLineID=$line->id;}
                                foreach($landLines as $line2)
                                {
                                    if($currentLineSpeed!=$line2->speed){$notEqual=1;}
                                    if($currentLineSpeed<$smalLineSpeed){$smalLineSpeed=$currentLineSpeed; $smalLineID=$line->id;}
                                }
                            }

                            // get total and specific land lines count
                            // Output = $countLine[1]
                            // Output = $countTotalLines
                            if(isset($notEqual) and $notEqual==1){
                                $countTotalLines=0;
                                foreach($landLines as $line)
                                {
                                    $countLine[$line->id]=$line->speed/$smalLineSpeed;
                                    $countTotalLines+=$countLine[$line->id];
                                }
                            }
                            //return $countLine[3];
                            //////////////////////////////////////////////////////////////
                            // END check if load balanceing lines equal each others or not
                            //////////////////////////////////////////////////////////////

                            // ( preparation steps ) rename ether1
                            $script.="/interface ethernet set [find default-name=ether1] name=ether1; \n";

                            // ( preparation steps ) revoke bridge ports
                            for($i=1;$i<count($landLines);$i++)
                            {   
                                $portName="ether".($i+1);
                                $script.=":if ([/interface bridge port find interface=$portName ] != ".'""'." ) do={ /interface bridge port remove [find interface=$portName]; } \n";
                            }
 
                            // ( preparation steps ) remove dhcp-client
                            // $script.='
                            // :foreach i in=[/ip dhcp-client find] do={ '."\n".'
                            //     :local interface [/ip dhcp-client get $i interface ] ;'."\n".'
                            //     :if ( $interface='.'"'.'IN'.'"'.') do={ '."\n".'
                            //         /ip dhcp-client remove $i; '."\n".'
                            //     } '."\n".'
                            // } '."\n";
                            $script.='
                            :foreach i in=[/ip dhcp-client find] do={ '."\n".'
                                /ip dhcp-client remove $i; '."\n".'
                            } '."\n";
                         
                            // ( preparation steps ) remove hotspot server
                            // ( preparation steps ) remove DHCP server
                            // ( preparation steps ) Install hotspot server
                            // ( preparation steps ) remove hotspot profile2
                            // ( preparation steps ) modify hotspot server to profile1
                            
                            $counter=1;
                            $zeroCounter=0;
                            $linesNotEqualCounter=0;
                            foreach($landLines as $line)
                            {
                                $threeDigitsIPbefore=explode('.',$line->ip);
                                $threeDigitsIP=$threeDigitsIPbefore[0].".".$threeDigitsIPbefore[1].".".$threeDigitsIPbefore[2].".";
                                // ip address
                                $script.=":if ([/ip address find interface=ether$counter ] != ".'""'." ) do={ /ip address remove [find interface=ether$counter]; }  \n";
                                $script.="/ip address add address=".$line->ip."/24 network=".$threeDigitsIP."0 broadcast=".$threeDigitsIP."255 interface=ether".$counter." \n";
                                // ip firewall mangle
                                $script.=":if ([/ip firewall mangle find new-connection-mark=ether".$counter."_conn ] != ".'""'." ) do={ /ip firewall mangle remove [find new-connection-mark=ether".$counter."_conn]; } \n";
                                $script.="/ip firewall mangle add chain=input in-interface=ether".$counter." action=mark-connection new-connection-mark=ether".$counter."_conn \n";

                                $script.=":if ([/ip firewall mangle find new-routing-mark=to_ether".$counter." ] != ".'""'." ) do={ /ip firewall mangle remove [find new-routing-mark=to_ether".$counter."]; } \n";
                                $script.="/ip firewall mangle add chain=output hotspot=auth connection-mark=ether".$counter."_conn action=mark-routing new-routing-mark=to_ether".$counter." \n";

                                $script.=":if ([/ip firewall mangle find dst-address=".'"'.$threeDigitsIP."0/24".'"'." ] != ".'""'." ) do={ /ip firewall mangle remove [find dst-address=".'"'.$threeDigitsIP."0/24".'"'."]; } \n";
                                $script.="/ip firewall mangle add chain=prerouting dst-address=".$threeDigitsIP."0/24 action=accept in-interface=OUT \n";

                                $script.=":if ([/ip firewall mangle find in-interface=OUT new-connection-mark=ether".$counter."_conn ] != ".'""'." ) do={ /ip firewall mangle remove [find in-interface=OUT new-connection-mark=ether".$counter."_conn]; } \n";
                                    //check equal or not and get small line
                                    if(isset($notEqual) and $notEqual==1){
                                        for($i=0;$i<$countLine[$line->id];$i++)
                                        {
                                            $script.="/ip firewall mangle add chain=prerouting dst-address-type=!local in-interface=OUT per-connection-classifier=both-addresses-and-ports:".$countTotalLines."/".$linesNotEqualCounter." action=mark-connection new-connection-mark=ether".$counter."_conn passthrough=yes  \n";
                                            $linesNotEqualCounter++;
                                        }
                                    }else{
                                        $script.="/ip firewall mangle add chain=prerouting dst-address-type=!local in-interface=OUT per-connection-classifier=both-addresses-and-ports:".count($landLines)."/".$zeroCounter." action=mark-connection new-connection-mark=ether".$counter."_conn passthrough=yes  \n";
                                    }

                                $script.=":if ([/ip firewall mangle find in-interface=OUT new-routing-mark=to_ether".$counter." ] != ".'""'." ) do={ /ip firewall mangle remove [find in-interface=OUT new-routing-mark=to_ether".$counter."]; } \n";
                                $script.="/ip firewall mangle add chain=prerouting connection-mark=ether".$counter."_conn in-interface=OUT action=mark-routing new-routing-mark=to_ether".$counter." \n";
                                // ip route
                                $script.=":if ([/ip route find routing-mark=to_ether".$counter." ] != ".'""'." ) do={ /ip route remove [find routing-mark=to_ether".$counter."]; } \n";
                                $script.="/ip route add dst-address=0.0.0.0/0 gateway=".$line->gateway." routing-mark=to_ether".$counter." distance=$counter check-gateway=ping \n";

                                // stoped for conflict "twise remove" 12.2.2018
                                $script.=":if ([/ip route find distance=$counter routing-mark!=to_ether".$counter." gateway=".'"'.$line->gateway.'"'." ] != ".'""'." ) do={ /ip route remove [find distance=$counter routing-mark!=to_ether".$counter." gateway=".'"'.$line->gateway.'"'."]; } \n";
                                $script.="/ip route add dst-address=0.0.0.0/0 gateway=".$line->gateway." distance=$counter check-gateway=ping \n";
                                
                                // ip firewall nat
                                $script.=":if ([/ip firewall nat find out-interface=".'"'."ether".$counter.'"'." ] != ".'""'." ) do={ /ip firewall nat remove [find out-interface=".'"'."ether".$counter.'"'."]; } \n";
                                $script.="/ip firewall nat add chain=srcnat out-interface=ether".$counter." action=masquerade \n";

                                $counter++;
                                $zeroCounter++;
                                
                            }//foreach($landLines as $line)

                        }//if($linesCount>2)

                        if(!isset($completeUnconfiguredCycle)){
                            $encoded=htmlentities($script, ENT_NOQUOTES);
                            $encoded=str_replace("&lt;","<",$encoded);
                            $encoded=str_replace("&gt;",">",$encoded);
                            return $encoded;
                        }
                        
                    }//if($finalBranchID->load_balance_state==1 $finalBranchID->change_load_balance_state==1)
                
                    // disable load balanceing
                    if($finalBranchID->load_balance_state==0 and $finalBranchID->change_load_balance_state==1)
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_load_balance_state' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_load_balance_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        $landLines=DB::table($identify.'.load_balancing')->where('branch_id',$finalBranchID->id)->get();
                        //check equal or not and get small line
                        // Output = $smalLineID
                        // Output = $smalLineSpeed
                        foreach($landLines as $line)
                        {
                            $currentLineSpeed=$line->speed;
                            if(!isset($smalLineSpeed)){$smalLineSpeed=$currentLineSpeed; $smalLineID=$line->id;}
                            foreach($landLines as $line2)
                            {
                                if($currentLineSpeed!=$line2->speed){$notEqual=1;}
                                if($currentLineSpeed<$smalLineSpeed){$smalLineSpeed=$currentLineSpeed; $smalLineID=$line->id;}
                            }
                        }

                        // get total and specific land lines count
                        // Output = $countLine[1]
                        // Output = $countTotalLines
                        if(isset($notEqual) and $notEqual==1){
                            $countTotalLines=0;
                            foreach($landLines as $line)
                            {
                                $countLine[$line->id]=$line->speed/$smalLineSpeed;
                                $countTotalLines+=$countLine[$line->id];
                            }
                        }
                        
                        // ( preparation steps ) rename IN interface
                        $script.="/interface ethernet set [find default-name=ether1] name=IN; \n";

                        // ( preparation steps ) add removed bridge ports
                        for($i=2;$i<24;$i++)
                        {   
                            $script.=":if ([/interface bridge port find interface=ether".$i." ] = ".'""'." and [/interface ethernet find name=ether".$i." ] != ".'""'." ) do={ /interface bridge port add bridge=OUT interface=ether".$i."; } \n";
                        }

                        // Add dhcp-client
                        $script.='
                            :foreach i in=[/ip dhcp-client find] do={ '."\n".'
                                :local interface [/ip dhcp-client get $i interface ] ;'."\n".'
                                :if ( $interface='.'"'.'IN'.'"'.') do={ '."\n".'
                                    /ip dhcp-client remove $i; '."\n".'
                                } '."\n".'
                            } '."\n".'
                            /ip dhcp-client add default-route-distance=1 dhcp-options=hostname,clientid disabled=no interface=IN '."\n".'
                            '."\n";

                        $counter=1;
                        $zeroCounter=0;
                        foreach($landLines as $line)
                        {
                            $threeDigitsIPbefore=explode('.',$line->ip);
                            $threeDigitsIP=$threeDigitsIPbefore[0].".".$threeDigitsIPbefore[1].".".$threeDigitsIPbefore[2].".";
                            // ip address
                            $script.=":if ([/ip address find interface=ether$counter ] != ".'""'." ) do={ /ip address remove [find interface=ether$counter]; }  \n";
                            // ip firewall mangle
                            $script.=":if ([/ip firewall mangle find new-connection-mark=ether".$counter."_conn ] != ".'""'." ) do={ /ip firewall mangle remove [find new-connection-mark=ether".$counter."_conn]; } \n";
                            $script.=":if ([/ip firewall mangle find new-routing-mark=to_ether".$counter." ] != ".'""'." ) do={ /ip firewall mangle remove [find new-routing-mark=to_ether".$counter."]; } \n";
                            $script.=":if ([/ip firewall mangle find dst-address=".'"'.$threeDigitsIP."0/24".'"'." ] != ".'""'." ) do={ /ip firewall mangle remove [find dst-address=".'"'.$threeDigitsIP."0/24".'"'."]; } \n";
                            $script.=":if ([/ip firewall mangle find in-interface=OUT new-connection-mark=ether".$counter."_conn ] != ".'""'." ) do={ /ip firewall mangle remove [find in-interface=OUT new-connection-mark=ether".$counter."_conn]; } \n";
                            $script.=":if ([/ip firewall mangle find in-interface=OUT new-routing-mark=to_ether".$counter." ] != ".'""'." ) do={ /ip firewall mangle remove [find in-interface=OUT new-routing-mark=to_ether".$counter."]; } \n";
                            $script.=":if ([/ip route find routing-mark=to_ether".$counter." ] != ".'""'." ) do={ /ip route remove [find routing-mark=to_ether".$counter."]; } \n";
                            $script.=":if ([/ip route find distance=$counter gateway=".'"'.$line->gateway.'"'." ] != ".'""'." ) do={ /ip route remove [find distance=$counter gateway=".'"'.$line->gateway.'"'."]; } \n";
                            $script.=":if ([/ip firewall nat find out-interface=".'"'."ether".$counter.'"'." ] != ".'""'." ) do={ /ip firewall nat remove [find out-interface=".'"'."ether".$counter.'"'."]; } \n";
                            $counter++;
                            $zeroCounter++;
                            
                        }//foreach($landLines as $line)

                        $encoded=htmlentities($script, ENT_NOQUOTES);
                        $encoded=str_replace("&lt;","<",$encoded);
                        $encoded=str_replace("&gt;",">",$encoded);
                        return $encoded;

                    }//if($finalBranchID->load_balance_state==0 and $finalBranchID->change_load_balance_state==1)

                    ////////////////////////
                    // check wireless state
                    ////////////////////////

                    if($finalBranchID->change_wireless_state==1)
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_wireless_state' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_wireless_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        if($finalBranchID->wireless_state==1){ // enable wireless
							$script.=':if ([/interface wireless find default-name=wlan1 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan1 ] band=2ghz-g/n frequency=auto disabled=no mode=ap-bridge; }'." \n";
							$script.=':if ([/interface wireless find default-name=wlan2 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan2 ] frequency=auto disabled=no mode=ap-bridge; }'." \n";}
                        if($finalBranchID->wireless_state==0){ // disable wireless
							$script.=':if ([/interface wireless find default-name=wlan1 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan1 ] disabled=yes; }'."\n";
							$script.=':if ([/interface wireless find default-name=wlan2 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan2 ] disabled=yes; }'."\n";
						}
                    }

                    // check wireless Name SSID
                    if($finalBranchID->change_wireless_name==1)
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_wireless_name' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_wireless_name', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        $script.=':if ([/interface wireless find default-name=wlan1 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan1 ] ssid='.'"'.$finalBranchID->wireless_name.'"'.' } '."\n";
						$script.=':if ([/interface wireless find default-name=wlan2 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan2 ] ssid='.'"'.$finalBranchID->wireless_name.'"'.' } '."\n";
                    }

                    // check wireless Password
                    if($finalBranchID->change_wireless_pass==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_wireless_pass' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_wireless_pass', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        if(isset($finalBranchID->wireless_pass))
                        {
                            if($finalBranchID->wireless_pass == "")
                            {// password empty
                                $script.=':if ([/interface wireless find default-name=wlan1 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan1 ]  security-profile=default; }'."\n";
								$script.=':if ([/interface wireless find default-name=wlan2 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan2 ]  security-profile=default; }'."\n";
                            }else{// password not empty
                                // step 1 : Delete security profile if exist
                                $script.=':if ([/interface wireless security-profiles find name=profile1 ] != '.'""'.') do={ /interface wireless security-profiles remove profile1; }'."\n";
                                // step 2 : Add new security profile
                                $script.='/interface wireless security-profiles add name=profile1 authentication-types=wpa-psk,wpa2-psk eap-methods='.'""'.' management-protection=allowed mode=dynamic-keys supplicant-identity='.'""'.' wpa-pre-shared-key='.'"'.$finalBranchID->wireless_pass.'"'.' wpa2-pre-shared-key='.'"'.$finalBranchID->wireless_pass.'"'."; \n";
                                // step 3 : assign profile wireless
                                $script.=':if ([/interface wireless find default-name=wlan1 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan1 ]  security-profile=profile1; }'."\n";
								$script.=':if ([/interface wireless find default-name=wlan2 ] != '.'""'.') do={ /interface wireless set [ find default-name=wlan2 ]  security-profile=profile1; }'."\n";
                            }
                        }       
                    }

                    ////////////////////////////////
                    // check private wireless state
                    ////////////////////////////////

                    if($finalBranchID->change_private_wireless_state==1 or isset($completeUnconfiguredCycle))
                    {// enable private wireless
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_private_wireless_state' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_private_wireless_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        if($finalBranchID->private_wireless_state==1){

                            $threeDigitsIPbefore=explode('.',$finalBranchID->private_wireless_ip);
                            $threeDigitsIP=$threeDigitsIPbefore[0].".".$threeDigitsIPbefore[1].".".$threeDigitsIPbefore[2].".";
                            
                            // step 1 : Delete security profile if exist
                            $script.=':if ([/interface wireless security-profiles find name=private ] != '.'""'.') do={ /interface wireless security-profiles remove private; }'."\n";
                            // step 2 : Add new security profile
                            $script.='/interface wireless security-profiles add name=private authentication-types=wpa-psk,wpa2-psk eap-methods='.'""'.' management-protection=allowed mode=dynamic-keys supplicant-identity='.'""'.' wpa-pre-shared-key='.'"'.$finalBranchID->private_wireless_pass.'"'.' wpa2-pre-shared-key='.'"'.$finalBranchID->private_wireless_pass.'"'."; \n";

                            $script.=':if ([/interface wireless find name=private ] != '.'""'.') do={ '."\n";
                            # add wireless
                            $script.='/interface wireless set [find name=private] name=private disabled=no keepalive-frames=disabled master-interface=wlan1 mode=ap-bridge multicast-buffering=disabled security-profile=private ssid='.'"'.$finalBranchID->private_wireless_name.'"'.' wds-cost-range=0 wds-default-cost=0 wps-mode=disabled; '."\n";
							$script.='/interface wireless set [find name=private] name=private disabled=no keepalive-frames=disabled master-interface=wlan2 mode=ap-bridge multicast-buffering=disabled security-profile=private ssid='.'"'.$finalBranchID->private_wireless_name.'"'.' wds-cost-range=0 wds-default-cost=0 wps-mode=disabled; '."\n";
                            $script.='} else={ '."\n";
                            $script.='/interface wireless add disabled=no keepalive-frames=disabled master-interface=wlan1 mode=ap-bridge multicast-buffering=disabled name=private security-profile=private ssid='.'"'.$finalBranchID->private_wireless_name.'"'.' wds-cost-range=0 wds-default-cost=0 wps-mode=disabled;';
							$script.='/interface wireless add disabled=no keepalive-frames=disabled master-interface=wlan2 mode=ap-bridge multicast-buffering=disabled name=private security-profile=private ssid='.'"'.$finalBranchID->private_wireless_name.'"'.' wds-cost-range=0 wds-default-cost=0 wps-mode=disabled;';
                            # add IP
                            $script.='/ip address add interface=private address='.$threeDigitsIP."1".'/24 network='.$threeDigitsIP."0"."; \n";
                            # add POOL
                            $script.='/ip pool add name=private ranges='.$threeDigitsIP.'2-'.$threeDigitsIP.'254'."; \n";
                            # add DHCP
                            $script.='/ip dhcp-server add address-pool=private disabled=no interface=private name=private;'."; \n";
                            $script.='/ip dhcp-server network add address='.$threeDigitsIP.'0/24 dns-server=8.8.8.8,8.8.4.4 gateway='.$threeDigitsIP.'1;'." \n";
                            # add firewall
                            $script.='/ip firewall nat add action=masquerade chain=srcnat out-interface=private;'." \n";
                            $script.='}'." \n";
                            
                            }
                        // disable wireless if state 0    
                        if($finalBranchID->private_wireless_state==0){$script.=':if ([/interface wireless find name=private ] != '.'""'.') do={ /interface wireless set [ find name=private ] disabled=yes;} '."\n";}
                    }

                    // check private wireless Name SSID
                    if($finalBranchID->change_private_wireless_name==1 or isset($completeUnconfiguredCycle))
                    {
                        if(isset($finalBranchID->private_wireless_name) and $finalBranchID->private_wireless_name!="")
                        {
                            DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_private_wireless_name' => '0']);
                            DB::table($identify.'.history')->where(['operation'=>'change_private_wireless_name', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                            $script.=':if ([/interface wireless find name=private ] != '.'""'.') do={ /interface wireless set [ find name=private ] ssid='.'"'.$finalBranchID->private_wireless_name.'"'.'; } '."\n";
                        }
                    }

                    // check private wireless Password
                    if($finalBranchID->change_private_wireless_pass==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_private_wireless_pass' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_private_wireless_pass', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        if(isset($finalBranchID->private_wireless_pass))
                        {
                            if($finalBranchID->private_wireless_pass == "")
                            {// password empty
                                $script.=':if ([/interface wireless find name=private ] != '.'""'.') do={ /interface wireless set [ find name=private ] security-profile=default; }'."\n";    
                            }else{// password not empty
                                // step 1 : Delete security profile if exist
                                $script.=':if ([/interface wireless security-profiles find name=private ] != '.'""'.') do={ /interface wireless security-profiles remove private; }'."\n";
                                // step 2 : Add new profile
                                $script.='/interface wireless security-profiles add name=private authentication-types=wpa-psk,wpa2-psk eap-methods='.'""'.' management-protection=allowed mode=dynamic-keys supplicant-identity='.'""'.' wpa-pre-shared-key='.'"'.$finalBranchID->private_wireless_pass.'"'.' wpa2-pre-shared-key='.'"'.$finalBranchID->private_wireless_pass.'"'."; \n";
                                // step 3 : assign profile wireless
                                $script.=':if ([/interface wireless find name=private ] != '.'""'.') do={ /interface wireless set [ find name=private ] security-profile=private; }'."\n";
                            }
                        }        
                    }


                    ////////////////////////////////////////////
                    //check change Mikrotik username or password
                    ////////////////////////////////////////////
                    if($finalBranchID->change_username_or_password==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_username_or_password' => '0']);
                        //DB::table($identify.'.history')->where(['operation'=>'change_username_or_password', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        DB::table($identify.'.history')->where(['operation'=>'change_username', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        DB::table($identify.'.history')->where(['operation'=>'change_password', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                            if( isset($finalBranchID->username) and $finalBranchID->username!="" and isset($finalBranchID->password) and $finalBranchID->password!="" )
                            {
                                $script.='
                                :foreach i in=[/user find] do={ '."\n".'
                                    :local name [/user get $i name] ; '."\n".'
                                    :if ( $name != '.'"'.'microsystem'.'"'.') do={  /user remove $name } '."\n".'
                                }'."\n";
                                if($finalBranchID->username!="microsystem"){
                                    $script.=" /user add group=full name=$finalBranchID->username password=$finalBranchID->password \n";     
                                }
                            }
                    }

                    // check Auto login update
                    if($finalBranchID->change_auto_login==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_auto_login' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_auto_login', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->auto_login==1){
                            // Enable auto login
                            $script.=":if ([/ip hotspot profile find name=hsprof1 ] != ".'""'." ) do={ /ip hotspot profile set [ find name=hsprof1 ] login-by=cookie,http-pap,mac  } \n";
                            
                        }

                        if($finalBranchID->auto_login==0){
                            // Disable Auto login
                            $script.=":if ([/ip hotspot profile find name=hsprof1 ] != ".'""'." ) do={ /ip hotspot profile set [ find name=hsprof1 ] login-by=cookie,http-pap  } \n";  }
                    }
                    
                    // check adult protection
                    if($finalBranchID->change_adult_state==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_adult_state' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_adult_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->adult_state==1){$script.="/ip dns set allow-remote-requests=yes servers=208.67.222.123,208.67.220.123 \n";
                        $script.="/ip dns cache flush \n";}
                        if($finalBranchID->adult_state==0){$script.="/ip dns set allow-remote-requests=yes servers=8.8.8.8,4.2.2.2 \n";
                        $script.="/ip dns cache flush \n";}
                    }

                    // check hacking protection
                    if($finalBranchID->change_hacking_protection==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_hacking_protection' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_hacking_protection', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        if($finalBranchID->hacking_protection==1){
                            
                            $script.=':if ( [/interface bridge find name=OUT] != '.'""'.' ) do={ /interface bridge set [find name=OUT] arp=reply-only }'."\n";
                            $script.=':if ( [/ip address find network=10.11.12.13] != "" ) do={ /log info '.'"'.'Address already add before" } else={ '."\n";
                            $script.='/ip address add address=10.11.12.13 interface=OUT network=10.11.12.13 }'."\n";
                            $script.=':if ( [/ip dhcp-server find interface=OUT ] != '.'""'.') do={'."\n";
                            $script.='/ip dhcp-server set [find interface=OUT] add-arp=yes always-broadcast=yes bootp-support=dynamic disabled=no; }'."\n";
                            $script.=':if ( [/ip dhcp-server network find ] != '.'""'.') do={ /ip dhcp-server network set 0 gateway=10.11.12.13 netmask=32 }'."\n";
                            
                        }
                        if($finalBranchID->hacking_protection==0){
                            $script.=':if ( [/interface bridge find name=OUT] != '.'""'.' ) do={ /interface bridge set [find name=OUT] arp=enabled }'."\n";
                            $script.=':if ( [/ip address find network=10.11.12.13] != "" ) do={ /ip address remove [find network=10.11.12.13]; }'."\n";
                            
                            $script.=':if ( [/ip dhcp-server find interface=OUT ] != '.'""'.') do={'."\n";
                            $script.='/ip dhcp-server set [find interface=OUT] add-arp=no always-broadcast=no bootp-support=static disabled=no; }'."\n";
                            $script.=':if ( [/ip dhcp-server network find ] != '.'""'.') do={ /ip dhcp-server network set 0 gateway=10.5.50.1 netmask=0; }'."\n";

                            $script.="/ip dns set allow-remote-requests=yes servers=8.8.8.8,4.2.2.2 \n";
                            $script.="/ip dns cache flush \n";
                            }
                    }

                    // check Anti virus
                    if($finalBranchID->change_antivirus==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_antivirus' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'antivirus', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->antivirus==1){
                            $script.=":if ([/ip firewall filter find comment=Antivirus ] != ".'""'." ) do={ /ip firewall filter remove [find comment=Antivirus ]; } \n";
                            $script.="/ip firewall filter add action=add-src-to-address-list address-list=blockDDos address-list-timeout=1d chain=input comment=Antivirus connection-limit=32,32 protocol=tcp \n";
                            $script.="/ip firewall filter add action=tarpit chain=input comment=Antivirus connection-limit=10,32 protocol=tcp src-address-list=blockDDos \n";
                            $script.="/ip firewall filter move [/ip fire filter find comment=Antivirus] 0 \n";
                            $script.="/ip firewall filter move [/ip fire filter find comment=Antivirus] 0 \n";
                        }

                        if($finalBranchID->antivirus==0){
                            // Remove Antivirus
                            $script.=":if ([/ip firewall filter find comment=Antivirus ] != ".'""'." ) do={ /ip firewall filter remove [find comment=Antivirus ]; } \n";
                        }
                    }

                    // check Download
                    if($finalBranchID->change_block_downloading==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_block_downloading' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'block_downloading', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->block_downloading==1){
                            $script.=":if ([/ip firewall mangle find comment=blockDownload ] != ".'""'." ) do={ /ip firewall mangle remove [find comment=blockDownload ]; } \n";
                            $script.="/ip firewall mangle add action=mark-connection chain=prerouting connection-bytes=262146-4294967295 in-interface=IN new-connection-mark=download protocol=tcp src-port=21,80 comment=blockDownload \n";
                            $script.="/ip firewall mangle add action=mark-packet chain=prerouting connection-mark=download in-interface=IN new-packet-mark=download passthrough=no comment=blockDownload \n";
                            $script.=":if ([/queue type find name=shape ] != ".'""'." ) do={ :log error AlreadyExist  } else { /queue type add kind=pcq name=shape pcq-classifier=src-address pcq-dst-address6-mask=64 pcq-rate=128k pcq-src-address6-mask=64 } \n";
                            $script.=":if ([/queue tree find comment=blockDownload ] != ".'""'." ) do={ /queue tree remove [find comment=blockDownload ]; } \n";
                            $script.="/queue tree add max-limit=128k name=Download packet-mark=download parent=global queue=shape comment=blockDownload \n";
                        }

                        if($finalBranchID->block_downloading==0){
                            // Remove block Download
                            $script.=":if ([/ip firewall mangle find comment=blockDownload ] != ".'""'." ) do={ /ip firewall mangle remove [find comment=blockDownload ]; } \n";
                            //$script.=":if ([/queue type find kind=pcq ] != ".'""'." ) do={ /queue type remove [find kind=pcq ]; } \n"; // comment it because mikrotik refuse to delete this record
                            $script.=":if ([/queue tree find comment=blockDownload ] != ".'""'." ) do={ /queue tree remove [find comment=blockDownload ]; } \n";
                        }
                    }

                    // check torrent Download
                    if($finalBranchID->change_block_torrent_download==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_block_torrent_download' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'block_torrent_download', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->block_torrent_download==1){
                            $script.=":if ([/ip firewall layer7-protocol find comment=BlockTorrent ] != ".'""'." ) do={ /ip firewall layer7-protocol remove [find comment=BlockTorrent ]; } \n";
                            $script.='/ip firewall layer7-protocol add comment=BlockTorrent name=layer7-bittorrent-exp regexp='.'"'.'^(\\\\x13bittorrent protocol|azver\\\x01\$|get /scrape\\\\\?info_hash=get /announce\\\\\?info_hash=|get /client/bitcomet/|GET /data\\\\\\?fid=)|d1:ad2:id20:|\\\\x08'."'".'7P\\\\)[RP]"'." \n";
                            $script.=":if ([/ip firewall filter find comment=BlockTorrent ] != ".'""'." ) do={ /ip firewall filter remove [find comment=BlockTorrent ]; } \n";
                            $script.="/ip firewall filter add action=add-src-to-address-list address-list=Torrent-Conn address-list-timeout=2m chain=forward layer7-protocol=layer7-bittorrent-exp src-address=10.5.50.0/24 src-address-list=!allow-bit comment=BlockTorrent \n";
                            $script.="/ip firewall filter add action=add-src-to-address-list address-list=Torrent-Conn address-list-timeout=2m chain=forward p2p=all-p2p src-address=10.5.50.0/24 src-address-list=!allow-bit comment=BlockTorrent \n";
                            $script.="/ip firewall filter add action=drop chain=forward dst-port=!0-1024,3230,3253,1719,8291,5900,5800,3389,14147,5222,59905,1433,1434 protocol=tcp src-address-list=Torrent-Conn comment=BlockTorrent \n"; // we removed this ports bacause mikrotik dose't allow more than 15 port ",1718,1731,3601,5001,8080,7531,9090,3306,3389 "
                            $script.="/ip firewall filter add action=drop chain=forward dst-port=!0-1024,8291,5900,5800,3389,14147,5222,59905,1433,1434,3306,3389 protocol=udp src-address-list=Torrent-Conn comment=BlockTorrent \n";
                        }

                        if($finalBranchID->block_torrent_download==0){
                            // Remove block torrent Download
                            $script.=":if ([/ip firewall layer7-protocol find comment=BlockTorrent ] != ".'""'." ) do={ /ip firewall layer7-protocol remove [find comment=BlockTorrent ]; } \n";
                            $script.=":if ([/ip firewall filter find comment=BlockTorrent ] != ".'""'." ) do={ /ip firewall filter remove [find comment=BlockTorrent ]; } \n";
                        }
                    }

                    // check windows update
                    if($finalBranchID->change_block_windows_update==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_block_windows_update' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'block_windows_update', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->block_windows_update==1){
                            $script.=":if ([/ip firewall layer7-protocol find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall layer7-protocol remove [find comment=WindowsUpdate ]; } \n";
                            $script.="/ip firewall layer7-protocol add name=block-update-ms regexp=".'"'.".(stats|ntservicepack|update|download|windowsupdate|v4.windowsupdate).(microsoft|windowsupdate)".'"'." comment=WindowsUpdate \n";
                            $script.="/ip firewall layer7-protocol add name=block-update-msw regexp=".'"'.".(wustat|ws|v4.windowsupdate.microsoft|windowsupdate.microsoft).(nsatc|windows|microsoft)".'"'." comment=WindowsUpdate \n";
                            $script.=":if ([/ip firewall filter find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall filter remove [find comment=WindowsUpdate ]; } \n";
                            $script.="/ip firewall filter add action=drop chain=forward layer7-protocol=block-update-ms comment=WindowsUpdate \n";
                            $script.="/ip firewall filter add action=drop chain=forward layer7-protocol=block-update-msw comment=WindowsUpdate \n";
                            
                        }

                        if($finalBranchID->block_windows_update==0){
                            // Remove block windows update
                            $script.=":if ([/ip firewall layer7-protocol find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall layer7-protocol remove [find comment=WindowsUpdate ]; } \n";
                            $script.=":if ([/ip firewall filter find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall filter remove [find comment=WindowsUpdate ]; } \n";
                        }
                    }

                    // check internet Mode update
                    if( (isset($finalBranchID->change_internet_mode) and $finalBranchID->change_internet_mode==1) or (isset($finalBranchID->change_internet_mode) and isset($completeUnconfiguredCycle)) )
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_internet_mode' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'internet_mode', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->internet_mode=="default"){
                            // delete all scripts
                            $script.="[/queue tree remove [ find comment=HomeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=HomeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=HomeMode]] \n";

                            $script.="[/queue tree remove [ find comment=OfficeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=OfficeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=OfficeMode]] \n";

                            $script.="[/queue tree remove [ find comment=GamingMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=GamingMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=GamingMode]] \n";

                        }elseif($finalBranchID->internet_mode=="home"){
                            // delete all scripts
                            $script.="[/queue tree remove [ find comment=HomeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=HomeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=HomeMode]] \n";
                            $script.="[/queue tree remove [ find comment=OfficeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=OfficeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=OfficeMode]] \n";
                            $script.="[/queue tree remove [ find comment=GamingMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=GamingMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=GamingMode]] \n";
                            // adding HomeMode script
                            $script.="/ip firewall layer7-protocol add comment=HomeMode name=streaming regexp=videoplayback|video; \n";
                            $script.='/ip firewall layer7-protocol add comment=HomeMode name=video regexp="^.+(tiktokcdn.com|video-hbe1-1.xx.fbcdn.net|googlevideo|cdninstagram.com|youtube).*\$"'." \n";
                            $script.='/ip firewall layer7-protocol add comment=HomeMode name=videoplayback regexp="get /videoplayback[\\\\x09-\\\\x0d -~]* http/[01]\\\\.[019]"'." \n";
                            $script.='/ip firewall layer7-protocol add comment=HomeMode name=videoplayback1 regexp="(GET \\/videoplayback\\\?|GET \\/crossdomain\\.xml)" '." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=HomeMode layer7-protocol=streaming new-connection-mark=Streaming passthrough=no port=!8801-8802,3478,3479 protocol=udp'." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=HomeMode layer7-protocol=videoplayback new-connection-mark=Streaming passthrough=no'." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=HomeMode layer7-protocol=videoplayback1 new-connection-mark=Streaming passthrough=no'." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=HomeMode layer7-protocol=video new-connection-mark=Streaming passthrough=no '." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=postrouting comment=HomeMode connection-mark=Streaming new-packet-mark=Streaming passthrough=no'." \n";
                            $script.='/queue tree add comment=HomeMode name="Video&Audio Streaming" parent=global priority=1'." \n";
                            $script.='/queue tree add comment=HomeMode name=Video packet-mark=Streaming parent="Video&Audio Streaming" priority=1'." \n";                
                        }elseif($finalBranchID->internet_mode=="office"){
                            // delete all scripts
                            $script.="[/queue tree remove [ find comment=HomeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=HomeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=HomeMode]] \n";
                            $script.="[/queue tree remove [ find comment=OfficeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=OfficeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=OfficeMode]] \n";
                            $script.="[/queue tree remove [ find comment=GamingMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=GamingMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=GamingMode]] \n";
                            // adding OfficeMode script
                            $script.="/ip firewall layer7-protocol add comment=OfficeMode name=streaming regexp=videoplayback|video \n";
                            $script.='/ip firewall layer7-protocol add comment=OfficeMode name=video regexp="^.+(tiktokcdn.com|video-hbe1-1.xx.fbcdn.net|googlevideo|cdninstagram.com|youtube).*\$"'." \n";
                            $script.='/ip firewall layer7-protocol add comment=OfficeMode name=videoplayback regexp="get /videoplayback[\\\\x09-\\\\x0d -~]* http/[01]\\\\.[019]"'." \n";
                            $script.='/ip firewall layer7-protocol add comment=OfficeMode name=videoplayback1 regexp="(GET \\/videoplayback\\\?|GET \\/crossdomain\\.xml)" '." \n";
                            $script.='/ip firewall layer7-protocol add comment=OfficeMode name=zoom regexp="^.+(zoom).*\$" '." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=OfficeMode layer7-protocol=streaming new-connection-mark=Streaming passthrough=no port=!8801-8802,3478,3479 protocol=udp'." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=OfficeMode layer7-protocol=videoplayback new-connection-mark=Streaming passthrough=no'." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=OfficeMode layer7-protocol=videoplayback1 new-connection-mark=Streaming passthrough=no'." \n";
                            $script.='/ip firewall mangle add action=mark-connection chain=forward comment=OfficeMode layer7-protocol=video new-connection-mark=Streaming passthrough=no src-address=192.168.20.68'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=postrouting comment=OfficeMode connection-mark=Streaming new-packet-mark=Streaming passthrough=no'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode new-packet-mark="zoom down" passthrough=yes protocol=udp src-port=8801-8802,3478,3479'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode dst-port=8801,8802 new-packet-mark="zoom down tcp" passthrough=yes protocol=tcp'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode dst-port=8801-8802,3478,3479 new-packet-mark="zoom up " passthrough=yes protocol=udp'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode new-packet-mark="zoom up tcp" passthrough=yes protocol=tcp src-port=8801,8802'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode new-packet-mark="skype down" passthrough=yes protocol=udp src-port=3478,3479,3480,3481'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode dst-port=3478,3479,3480,3481 new-packet-mark="skype up" passthrough=yes protocol=udp'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode new-packet-mark="webex down" passthrough=yes protocol=udp src-port=9000'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode new-packet-mark="webex down tcp" passthrough=yes protocol=tcp src-port=5004'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode dst-port=9000 new-packet-mark="webex up" passthrough=yes protocol=udp'." \n";
                            $script.='/ip firewall mangle add action=mark-packet chain=forward comment=OfficeMode dst-port=5004 new-packet-mark="webex up tcp" passthrough=yes protocol=tcp'." \n";
                            $script.='/queue tree add comment=OfficeMode name=MeetingsApps parent=global priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="zoom down" packet-mark="zoom down" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="zoom down tcp" packet-mark="zoom down tcp" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="zoom up " packet-mark="zoom up " parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="zoom up tcp" packet-mark="zoom up tcp" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="skype down" packet-mark="skype down" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="skype up" packet-mark="skype up" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="webex down" packet-mark="webex down" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="webex down tcp" packet-mark="webex down tcp" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="webex up" packet-mark="webex up" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="webex up tcp" packet-mark="webex up tcp" parent=MeetingsApps priority=1'." \n";
                            $script.='/queue tree add comment=OfficeMode name="Video&Audio Streaming" parent=global'." \n";
                            $script.='/queue tree add comment=OfficeMode name=Video packet-mark=Streaming parent="Video&Audio Streaming"'." \n";
                        }elseif($finalBranchID->internet_mode=="gaming"){
                            // delete all scripts
                            $script.="[/queue tree remove [ find comment=HomeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=HomeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=HomeMode]] \n";
                            $script.="[/queue tree remove [ find comment=OfficeMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=OfficeMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=OfficeMode]] \n";
                            $script.="[/queue tree remove [ find comment=GamingMode] ] \n";
                            $script.="[/ip firewall mangle remove [ find comment=GamingMode]] \n";
                            $script.="[/ip firewall layer7-protocol remove [ find comment=GamingMode]] \n";
                            // adding GameMode script
                            
                        }
                    }

                    // recive API from Mikrotik
                    if(isset($detectedIP) or isset($detectedMac)){
                        
                        if(isset($detectedIP)){
                            $searchBypass=DB::table($identify.'.bypassed')->where('branch_id',$finalBranchID->id)->where('state','!=','1')->where('ip',$detectedIP)->value('id');
                        }
                        if(isset($detectedMac) and !isset($searchBypass)){
                            $searchBypass=DB::table($identify.'.bypassed')->where('branch_id',$finalBranchID->id)->where('state','!=','1')->where('mac',$detectedMac)->value('id');
                        }
                        
                        if(isset($searchBypass)){
                            DB::table($identify.'.bypassed')->where('id',$searchBypass)->update(['ip' => $detectedIP, 'mac' => $detectedMac, 'state' => '1', 'change_state' => '0', 'updated_at' => $todayDateTime]); 
                        }

                    }
                    // check change bypass Mac or IP or PORT
                    $bypass=DB::table($identify.'.bypassed')->where('branch_id',$finalBranchID->id)->where('change_state','!=','0')->get();
            
                    foreach($bypass as $record){
                        // change state
                        if($record->change_state == "1"){
                            // need change
                            //DB::table($identify.'.bypassed')->where('id',$record->id)->update(['change_state' => '0']); 
                            $script.=':global staticIP "'.$record->ip.'"; '."\n";
                            $script.=':global staticMac "'.$record->mac.'"; '."\n";
                            $script.=':global foundIPorMac ""; '."\n";
                            $script.=':global detectedIP ""; '."\n";
                            $script.=':global detectedMac ""; '."\n";
                            $script.=':local branchid ""; '."\n";
                            $script.=':if ([/ip hotspot profile find name=hsprof1 ] != "" and [/radius find address='.$systemMasterIP.' ] != "") do={'."\n";
                            $script.='    :set $branchid ([/ip hotspot profile get [ find name=hsprof1 ] radius-location-id ]);'."\n";
                            $script.='} else={'."\n";
                            $script.='    :set $branchid "";'."\n";
                            $script.='}'."\n";
                            $script.=':global identify [/system identity get name]; '."\n";
                            $script.=':foreach i in=[/ ip hotspot host find] do={ '."\n";
                            $script.=':local dynamicIP [/ ip hotspot host get $i address ] ; '."\n";
                            $script.=':local dynamicMac [/ ip hotspot host get $i mac-address ] ; '."\n";
                            $script.=':if ( $staticIP=$dynamicIP or $staticMac=$dynamicMac ) do={ '."\n";
                            $script.=':global foundIPorMac "found"; '."\n";
                            $script.=':global detectedIP $dynamicIP;  '."\n";
                            $script.=':global detectedMac $dynamicMac;  '."\n";
                            $script.=':global detectionSuccessfully 1; '."\n";
                            $script.=':foreach i2 in=[/ ip hotspot ip-binding find] do={ '."\n";
                            $script.=':local searchMacToDelete [/ ip hotspot ip-binding get $i2 mac-address ] ; '."\n";
                            $script.=':if ( $detectedMac=$searchMacToDelete  ) do={ '."\n";
                            $script.='/ip hotspot ip-binding remove $i2; '."\n";
                            $script.='} } '."\n";
                            $script.='/ip hotspot ip-binding add comment=static mac-address=$detectedMac type=bypassed; '."\n";
                            $script.=':foreach i3 in=[/ ip dhcp-server lease find] do={ '."\n";
                            $script.=':local searchMacToDelete2 [/ ip dhcp-server lease get $i3 mac-address ] ; '."\n";
                            $script.=':if ( $detectedMac=$searchMacToDelete2  ) do={ '."\n";
                            $script.='/ip dhcp-server lease remove $i3; '."\n";
                            $script.='} } '."\n";
                            $script.='/ip dhcp-server lease add address=$detectedIP mac-address=$detectedMac server=dhcp1 comment="static"; '."\n";
                            $script.='/tool fetch url="http://'.$serverDomain.'/mikrotikapi\?identify=$identify&auto=auto&branchid=$branchid&detectedIP=$detectedIP&detectedMac=$detectedMac" mode=http '."\n";
                            // $script.=':global rerererere "https://s1.microsystem.com.eg/mikrotikapi\?identify=$identify&auto=auto&detectedIP=$detectedIP&detectedMac=$detectedMac"; '."\n";
                            $script.='} } '."\n";
                            $script.=':log error "bypassing has been done."; '."\n";
                            // Waiting to create API
                            if(isset($record->port))
                            {
                                if($record->port != "")
                                {
                                    $script.=':global foundDHCPclient ""; '."\n";
                                    $script.=':global dynamicIP ""; '."\n";
                                    $script.=':global port "'.$record->port.'"; '."\n";
                                    $script.=':if ( $foundIPorMac ="found" ) do= { '."\n";
                                    $script.=':foreach i in=[/ ip dhcp-client find] do={ '."\n";
                                    $script.=':local checkDisable [/ip dhcp-client get $i disabled ] ; '."\n";
                                    $script.=':if ( $checkDisable = false ) do= { '."\n";
                                    $script.=':global foundDHCPclient found; '."\n";
                                    $script.=':global dynamicIP [/ ip dhcp-client get $i address ] ; '."\n";
                                    $script.='} } '."\n";
                                    $script.=':if ( $foundDHCPclient != found ) do= { '."\n";
                                    $script.=':local checkIfExistInDHCP [ /ip address find interface=ether1 ];  '."\n";
                                    $script.=':if ( [ :len $checkIfExistInDHCP] > 0 ) do= { '."\n";
                                    $script.=':global foundDHCPclient found; '."\n";
                                    $script.=':global dynamicIP [/ ip address get [ /ip address find interface=ether1 ] address ] ; '."\n";
                                    $script.='} } '."\n";
                                    $script.=':if ( $foundDHCPclient ="found" ) do= { '."\n";
                                    $script.=':for j from=( [:len $dynamicIP] - 1) to=0 do={ '."\n";
                                    $script.=':if ( [:pick $dynamicIP $j] = "/") do={ '."\n";
                                    $script.=':global dynamicIP [:pick $dynamicIP 0 $j] ; '."\n";
                                    $script.='} } '."\n";
                                    $script.=':if ([/ip firewall nat find to-addresses=$detectedIP comment="static" ] != "" ) do={ /ip firewall nat remove [find to-addresses=$detectedIP comment="static" ]; } '."\n";
                                    $script.='/ip firewall nat add action=dst-nat chain=dstnat dst-address=$dynamicIP dst-port=$port protocol=tcp to-addresses=$detectedIP to-ports=$port comment="static" '."\n";
                                    $script.='} } '."\n";
                                    // $script.=":if ([/ip firewall filter find comment=BlockTorrent ] != ".'""'." ) do={ /ip firewall filter set [find comment=BlockTorrent] dst-port=!0-1024,3230,3253,1719,8291,5900,5800,3389,14147,5222,59905,1433,1434,3306,3389,1718,1731,3601,5001,8080,7531,9090,$record->port ; } \n"; we comment it bacause  mikrotik dose't allow more than 15 port 
                                    $script.=':log error "Nating IP Done"; '."\n";

                                }
                            }
                            

                        }elseif($record->change_state == "2"){
                            // need delete
                            DB::table($identify.'.bypassed')->where('id',$record->id)->delete();
                            $script.=":global staticIP ".'"'."$record->ip".'"'."; \n";
                            $script.=":global staticMac ".'"'."$record->mac".'"'."; \n";
                            $script.=":foreach i2 in=[/ ip hotspot ip-binding find] do={ \n";
                            $script.=':local searchMacToDelete [/ ip hotspot ip-binding get $i2 mac-address ] ;'." \n";
                            $script.=':if ( $staticMac=$searchMacToDelete  ) do={'." \n";
                            $script.='/ip hotspot ip-binding remove $i2; '."\n";
                            $script.="} } \n";
                            $script.=":foreach i3 in=[/ ip dhcp-server lease find] do={ \n";
                            $script.=':local searchMacToDelete2 [/ ip dhcp-server lease get $i3 mac-address ] ; '."\n";
                            $script.=':if ( $staticMac=$searchMacToDelete2  ) do={ '."\n";
                            $script.='/ip dhcp-server lease remove $i3; '."\n";
                            $script.='} } '."\n";
                            $script.=':if ([/ip firewall nat find to-addresses=$staticIP comment="static" ] != "" ) do={ /ip firewall nat remove [find to-addresses=$staticIP comment="static" ]; }  '."\n";
                            $script.=':log error "$staticIP has been deleted."; '."\n";
                        }

                       
                    }

                    // check for suspend or unsuspend users 
                    $suspendUnsuspendUsers=DB::table($identify.'.history')->where('branch_id',$finalBranchID->id)->where('type1','suspend_unsuspend_user')->where('details','1')->get();
                    foreach($suspendUnsuspendUsers as $record){
                        // Mark as completed
                        DB::table($identify.'.history')->where(['id'=>$record->id])->update(['details' => "0" ]);
                        // check state
                        $separatedMac=explode(',',$record->notes);
                        foreach($separatedMac as $row)
                        {
                            // Remove Spaces
                            $row = str_replace(' ', '', $row);
                            // Build Mikrotik code
                            if($record->operation == "suspend_user"){
                                $script.=':do { /ip hotspot ip-binding add comment=suspended mac-address='.$row.' type=blocked; } on-error={ :log error "This mac already registerd before"; }'."\n";
                            }elseif($record->operation == "unsuspend_user"){
                                $script.=':if ([ /ip hotspot ip-binding find mac-address="'.$row.'" ] != "" ) do={ /ip hotspot ip-binding remove [find mac-address="'.$row.'" ]; }'."\n";
                            }
                        }
                    }

                    if($finalBranchID->change_block_windows_update==1 or isset($completeUnconfiguredCycle))
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_block_windows_update' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'block_windows_update', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);

                        if($finalBranchID->block_windows_update==1){
                            $script.=":if ([/ip firewall layer7-protocol find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall layer7-protocol remove [find comment=WindowsUpdate ]; } \n";
                            $script.="/ip firewall layer7-protocol add name=block-update-ms regexp=".'"'.".(stats|ntservicepack|update|download|windowsupdate|v4.windowsupdate).(microsoft|windowsupdate)".'"'." comment=WindowsUpdate \n";
                            $script.="/ip firewall layer7-protocol add name=block-update-msw regexp=".'"'.".(wustat|ws|v4.windowsupdate.microsoft|windowsupdate.microsoft).(nsatc|windows|microsoft)".'"'." comment=WindowsUpdate \n";
                            $script.=":if ([/ip firewall filter find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall filter remove [find comment=WindowsUpdate ]; } \n";
                            $script.="/ip firewall filter add action=drop chain=forward layer7-protocol=block-update-ms comment=WindowsUpdate \n";
                            $script.="/ip firewall filter add action=drop chain=forward layer7-protocol=block-update-msw comment=WindowsUpdate \n";
                            
                        }

                        if($finalBranchID->block_windows_update==0){
                            // Remove block windows update
                            $script.=":if ([/ip firewall layer7-protocol find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall layer7-protocol remove [find comment=WindowsUpdate ]; } \n";
                            $script.=":if ([/ip firewall filter find comment=WindowsUpdate ] != ".'""'." ) do={ /ip firewall filter remove [find comment=WindowsUpdate ]; } \n";
                        }
                    }
                    ////////////////////////////
                    //    Connection type
                    ////////////////////////////

                    //check connection type ADSL
                    if( ($finalBranchID->change_connection_type==1 or isset($completeUnconfiguredCycle) ) and $finalBranchID->connection_type=="1")
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_connection_type' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        $script.='
                        :foreach i in=[/ip dhcp-client find] do={ '."\n".'
                            :local interface [/ip dhcp-client get $i interface ] ;'."\n".'
                            :if ( $interface='.'"'.'IN'.'"'.') do={ '."\n".'
                                /ip dhcp-client remove $i; '."\n".'
                            } '."\n".'
                            } '."\n".'
                        /ip dhcp-client add default-route-distance=1 dhcp-options=hostname,clientid disabled=no interface=IN '."\n".'
                        '."\n";     
                    }

                    //check connection type PPPOE
                    if(($finalBranchID->change_connection_type==1 or $finalBranchID->change_adsl_user==1 or $finalBranchID->change_adsl_pass==1 or isset($completeUnconfiguredCycle)) and $finalBranchID->connection_type=="2")
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_connection_type' => '0']);
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_adsl_user' => '0']);
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_adsl_pass' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        DB::table($identify.'.history')->where(['operation'=>'change_adsl_user', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        DB::table($identify.'.history')->where(['operation'=>'change_adsl_pass', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        $script.='
                        :if ([/interface pppoe-client find name=pppoe-out1 ] != '.'""'.') do={ /interface pppoe-client remove pppoe-out1; } '."\n".'
                        /interface pppoe-client add add-default-route=yes disabled=no interface=IN name=pppoe-out1 user='.$finalBranchID->adsl_user.' password='.$finalBranchID->adsl_pass.' '."\n".'
                        '."\n";     
                    }


                    //check connection type Vodafone USB Modem
                    if(($finalBranchID->change_connection_type==1 or isset($completeUnconfiguredCycle)) and $finalBranchID->connection_type=="3")
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_connection_type' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        /*
                        #delete all disabled records in PPP for USB modems auto configuration
                        :foreach d in=[/interface ppp-client find] do={
                        :local state [/interface ppp-client get $d disabled ] ;
                        :local name [/interface ppp-client get $d name ] ;
                        if ( $state=true) do={ 
                        /interface ppp-client remove $name; 
                        }
                        }

                        #insert new PPP record with olny active port
                        :foreach i in=[/port find] do={
                        :local state [/port get $i i ] ;
                        :local name [/port get $i name ] ;

                        if ($state = false) do={
                        #delete vodafone record if exist
                        if ([/interface ppp-client find name=vodafone ] != "") do={ /interface ppp-client remove vodafone; }
                        #insert new record
                        /interface ppp-client add apn=internet.vodafone.net dial-on-demand=no disabled=no name=vodafone port=$name;
                        }
                        }
                        */
                            $script.='
                                    :foreach d in=[/interface ppp-client find] do={ '."\n".'
                                        :local state [/interface ppp-client get $d disabled ] ;'."\n".'
                                        :local name [/interface ppp-client get $d name ] ;'."\n".'
                                        :if ( $state=true) do={ '."\n".'
                                            /interface ppp-client remove $name; '."\n".'
                                        }'."\n".'
                                    }'."\n".'
                                    :foreach i in=[/port find] do={'."\n".'
                                        :local state [/port get $i i ] ;'."\n".'
                                        :local name [/port get $i name ] ;'."\n".'
                                        :if ($state = false) do={'."\n".'
                                            :if ([/interface ppp-client find name=vodafone ] != '.'""'.') do={ /interface ppp-client remove vodafone; }'."\n".'
                                            :if ([/interface ppp-client find name=etisalat ] != '.'""'.') do={ /interface ppp-client remove etisalat; }'."\n".'
                                            :if ([/interface ppp-client find name=orange ] != '.'""'.') do={ /interface ppp-client remove orange; }'."\n".'
                                            :if ([/interface ppp-client find name=autodetected ] != '.'""'.') do={ /interface ppp-client remove autodetected; }'."\n".'
                                            /interface ppp-client add apn=internet.vodafone.net dial-on-demand=no disabled=no name=vodafone port=$name;'."\n".'
                                        }'."\n".'
                                    }'."\n";

                    }

                    //check connection type Etisalat USB Modem
                    if(($finalBranchID->change_connection_type==1 or isset($completeUnconfiguredCycle)) and $finalBranchID->connection_type=="4")
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_connection_type' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        //# this modem is : zte mf190s
                        //#/port set 0 baud-rate=auto name=usb1;
                            
                            $script.='
                                    :foreach d in=[/interface ppp-client find] do={ '."\n".'
                                        :local state [/interface ppp-client get $d disabled ] ;'."\n".'
                                        :local name [/interface ppp-client get $d name ] ;'."\n".'
                                        :if ( $state=true) do={ '."\n".'
                                            /interface ppp-client remove $name; '."\n".'
                                        }'."\n".'
                                    }'."\n".'
                                    :foreach i in=[/port find] do={'."\n".'
                                        :local state [/port get $i i ] ;'."\n".'
                                        :local name [/port get $i name ] ;'."\n".'
                                        :if ($state = false) do={'."\n".'
                                            :if ([/interface ppp-client find name=vodafone ] != '.'""'.') do={ /interface ppp-client remove vodafone; }'."\n".'
                                            :if ([/interface ppp-client find name=etisalat ] != '.'""'.') do={ /interface ppp-client remove etisalat; }'."\n".'
                                            :if ([/interface ppp-client find name=orange ] != '.'""'.') do={ /interface ppp-client remove orange; }'."\n".'
                                            :if ([/interface ppp-client find name=autodetected ] != '.'""'.') do={ /interface ppp-client remove autodetected; }'."\n".'
                                            :if ([/system resource usb find device-id=0x0117 ] != '.'""'.') do={'."\n".'
                                                /interface ppp-client add apn=internet.etisalat data-channel=2 disabled=no info-channel=1 name=etisalat dial-on-demand=no port=$name;'."\n".'
                                            } else={'."\n".'
                                                :if ([/interface ppp-client find name=etisalat ] != "") do={ /interface ppp-client remove etisalat; }'."\n".'
                                                /interface ppp-client add apn=internet.etisalat disabled=no name=etisalat dial-on-demand=no port=$name;'."\n".'
                                            }'."\n".'
                                        }'."\n".'
                                    }'."\n";
                            
                            
                    }

                    //check connection type Orange USB Modem
                    if(($finalBranchID->change_connection_type==1 or isset($completeUnconfiguredCycle)) and $finalBranchID->connection_type=="5")
                    {
                        DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_connection_type' => '0']);
                        DB::table($identify.'.history')->where(['operation'=>'change_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        
                            $script.='
                                    :foreach d in=[/interface ppp-client find] do={ '."\n".'
                                        :local state [/interface ppp-client get $d disabled ] ;'."\n".'
                                        :local name [/interface ppp-client get $d name ] ;'."\n".'
                                        :if ( $state=true) do={ '."\n".'
                                            /interface ppp-client remove $name; '."\n".'
                                        }'."\n".'
                                    }'."\n".'
                                    :foreach i in=[/port find] do={'."\n".'
                                        :local state [/port get $i i ] ;'."\n".'
                                        :local name [/port get $i name ] ;'."\n".'
                                        :if ($state = false) do={'."\n".'
                                            :if ([/interface ppp-client find name=vodafone ] != '.'""'.') do={ /interface ppp-client remove vodafone; }'."\n".'
                                            :if ([/interface ppp-client find name=etisalat ] != '.'""'.') do={ /interface ppp-client remove etisalat; }'."\n".'
                                            :if ([/interface ppp-client find name=orange ] != '.'""'.') do={ /interface ppp-client remove orange; }'."\n".'
                                            :if ([/interface ppp-client find name=autodetected ] != '.'""'.') do={ /interface ppp-client remove autodetected; }'."\n".'
                                            /interface ppp-client add apn=internet dial-on-demand=no disabled=no name=orange port=$name;'."\n".'
                                        }'."\n".'
                                    }'."\n";

                    }

                
                //////////////////////////////////
                //    Backup connection type
                //////////////////////////////////
                                            
                //check backup connection type ADSL
                if($finalBranchID->backup_connection_state=="1" and $finalBranchID->backup_connection_type=="1" and ($finalBranchID->change_backup_connection_type=="1" or $finalBranchID->change_backup_connection_state=="1" or isset($completeUnconfiguredCycle)) )
                {
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_state' => '0']);
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_type' => '0']);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    $script.='
                    :foreach i in=[/ip dhcp-client find] do={ '."\n".'
                        :local interface [/ip dhcp-client get $i interface ] ;'."\n".'
                        :if ( $interface='.'"'.'IN'.'"'.') do={ '."\n".'
                            /ip dhcp-client remove $i; '."\n".'
                        } '."\n".'
                        } '."\n".'
                    /ip dhcp-client add default-route-distance=1 dhcp-options=hostname,clientid disabled=no interface=IN '."\n".'
                    '."\n";     
                }

                //check backup connection type Vodafone USB Modem
                if($finalBranchID->backup_connection_state=="1" and $finalBranchID->backup_connection_type=="2" and ($finalBranchID->change_backup_connection_type=="1" or $finalBranchID->change_backup_connection_state=="1" or isset($completeUnconfiguredCycle)) )
                {
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_state' => '0']);
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_type' => '0']);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                        $script.='
                                :foreach d in=[/interface ppp-client find] do={ '."\n".'
                                    :local state [/interface ppp-client get $d disabled ] ;'."\n".'
                                    :local name [/interface ppp-client get $d name ] ;'."\n".'
                                    :if ( $state=true) do={ '."\n".'
                                        /interface ppp-client remove $name; '."\n".'
                                    }'."\n".'
                                }'."\n".'
                                :foreach i in=[/port find] do={'."\n".'
                                    :local state [/port get $i i ] ;'."\n".'
                                    :local name [/port get $i name ] ;'."\n".'
                                    :if ($state = false) do={'."\n".'
                                        :if ([/interface ppp-client find name=vodafone ] != '.'""'.') do={ /interface ppp-client remove vodafone; }'."\n".'
                                        :if ([/interface ppp-client find name=etisalat ] != '.'""'.') do={ /interface ppp-client remove etisalat; }'."\n".'
                                        :if ([/interface ppp-client find name=orange ] != '.'""'.') do={ /interface ppp-client remove orange; }'."\n".'
                                        /interface ppp-client add apn=internet.vodafone.net dial-on-demand=no disabled=no name=vodafone port=$name default-route-distance=2;'."\n".'
                                    }'."\n".'
                                }'."\n";

                }


                //check backup connection type Etisalat USB Modem
                if($finalBranchID->backup_connection_state=="1" and $finalBranchID->backup_connection_type=="3" and ($finalBranchID->change_backup_connection_type=="1" or $finalBranchID->change_backup_connection_state=="1" or isset($completeUnconfiguredCycle)) )
                {
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_state' => '0']);
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_type' => '0']);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    //# this modem is : zte mf190s
                    //#/port set 0 baud-rate=auto name=usb1;
                        
                        $script.='
                                :foreach d in=[/interface ppp-client find] do={ '."\n".'
                                    :local state [/interface ppp-client get $d disabled ] ;'."\n".'
                                    :local name [/interface ppp-client get $d name ] ;'."\n".'
                                    :if ( $state=true) do={ '."\n".'
                                        /interface ppp-client remove $name; '."\n".'
                                    }'."\n".'
                                }'."\n".'
                                :foreach i in=[/port find] do={'."\n".'
                                    :local state [/port get $i i ] ;'."\n".'
                                    :local name [/port get $i name ] ;'."\n".'
                                    :if ($state = false) do={'."\n".'
                                        :if ([/interface ppp-client find name=vodafone ] != '.'""'.') do={ /interface ppp-client remove vodafone; }'."\n".'
                                        :if ([/interface ppp-client find name=etisalat ] != '.'""'.') do={ /interface ppp-client remove etisalat; }'."\n".'
                                        :if ([/interface ppp-client find name=orange ] != '.'""'.') do={ /interface ppp-client remove orange; }'."\n".'
                                        :if ([/interface ppp-client find name=autodetected ] != '.'""'.') do={ /interface ppp-client remove autodetected; }'."\n".'
                                        :if ([/system resource usb find device-id=0x0117 ] != '.'""'.') do={'."\n".'
                                            /interface ppp-client add apn=internet.etisalat data-channel=2 disabled=no info-channel=1 name=etisalat dial-on-demand=no port=$name default-route-distance=1;'."\n".'
                                        } else={'."\n".'
                                            :if ([/interface ppp-client find name=etisalat ] != "") do={ /interface ppp-client remove etisalat; }'."\n".'
                                            /interface ppp-client add apn=internet.etisalat disabled=no name=etisalat dial-on-demand=no port=$name default-route-distance=2;'."\n".'
                                        }'."\n".'
                                    }'."\n".'
                                }'."\n";
                        
                        
                }


                //check backup connection type Orange USB Modem
                if($finalBranchID->backup_connection_state=="1" and $finalBranchID->backup_connection_type=="4" and ($finalBranchID->change_backup_connection_type=="1" or $finalBranchID->change_backup_connection_state=="1" or isset($completeUnconfiguredCycle)) )
                {
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_state' => '0']);
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_type' => '0']);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    
                        $script.='
                            :foreach d in=[/interface ppp-client find] do={ '."\n".'
                                :local state [/interface ppp-client get $d disabled ] ;'."\n".'
                                :local name [/interface ppp-client get $d name ] ;'."\n".'
                                :if ( $state=true) do={ '."\n".'
                                    /interface ppp-client remove $name; '."\n".'
                                }'."\n".'
                            }'."\n".'
                            :foreach i in=[/port find] do={'."\n".'
                                :local state [/port get $i i ] ;'."\n".'
                                :local name [/port get $i name ] ;'."\n".'
                                :if ($state = false) do={'."\n".'
                                    :if ([/interface ppp-client find name=vodafone ] != '.'""'.') do={ /interface ppp-client remove vodafone; }'."\n".'
                                    :if ([/interface ppp-client find name=etisalat ] != '.'""'.') do={ /interface ppp-client remove etisalat; }'."\n".'
                                    :if ([/interface ppp-client find name=orange ] != '.'""'.') do={ /interface ppp-client remove orange; }'."\n".'
                                    :if ([/interface ppp-client find name=autodetected ] != '.'""'.') do={ /interface ppp-client remove autodetected; }'."\n".'
                                    /interface ppp-client add apn=internet dial-on-demand=no disabled=no name=orange port=$name default-route-distance=3;'."\n".'
                                }'."\n".'
                            }'."\n";

                }

                //remove backup connection
                if($finalBranchID->backup_connection_state=="0" and $finalBranchID->change_backup_connection_state=="1")
                {
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_state' => '0']);
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_backup_connection_type' => '0']);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    DB::table($identify.'.history')->where(['operation'=>'change_backup_connection_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    $script.='
                            :foreach d in=[/interface ppp-client find] do={ '."\n".'
                                :local state [/interface ppp-client get $d disabled ] ;'."\n".'
                                :local name [/interface ppp-client get $d name ] ;'."\n".'
                                :if ( $state=true) do={ '."\n".'
                                    /interface ppp-client remove $name; '."\n".'
                                }'."\n".'
                            }'."\n".'
                            :if ([/interface ppp-client find name=vodafone ] != '.'""'.') do={ /interface ppp-client remove vodafone; }'."\n".'
                            :if ([/interface ppp-client find name=etisalat ] != '.'""'.') do={ /interface ppp-client remove etisalat; }'."\n".'
                            :if ([/interface ppp-client find name=orange ] != '.'""'.') do={ /interface ppp-client remove orange; }'."\n".'
                            :if ([/interface ppp-client find name=autodetected ] != '.'""'.') do={ /interface ppp-client remove autodetected; }'."\n"."\n";
                }
                /*
                ////////////////////////////////
                //         URL filter V1
                ////////////////////////////////
    
                //if(isset($completeUnconfiguredCycle)){$urlFilterValue="url_filter";}else{$urlFilterValue="change_url_filter";}

                foreach(DB::table($identify.'.area_groups')->where('change_url_filter','1')->where('is_active','1')->get() as $group)
                {
                    DB::table($identify.'.area_groups')->where('id',$group->id)->update(['change_url_filter' => '0']);
                    
                    if($group->url_filter_type=="1"){ 
                        // block enterd
                        $dst_address_list="dst-address-list=";
                    }elseif($group->url_filter_type=="2")
                    {
                        // block all expect enterd !
                        $dst_address_list="dst-address-list=!";
                    }

                    $allURL=DB::table($identify.'.url_filter')->where('group_id',$group->id)->get();

                    if($group->as_system == "1"){
                        $groupName=DB::table($identify.'.users')->where('group_id',$group->id)->value('u_uname');
                    }else{
                        $groupName=$group->name;
                    }
 
                    // Enable url filter 
                    if($group->url_filter_state == "1")
                    {
                        if(isset($allURL) and count($allURL)>0)
                        {
                            //ip firewall filter
                            $script.=":if ([/ip firewall filter find src-address-list=".'"'.$groupName.'"'." ] != ".'""'.") do={ /ip firewall filter remove [find src-address-list=".'"'.$groupName.'"'."]; } \n";
                            $script.="/ip firewall filter add action=drop chain=forward ".$dst_address_list.'"'."block".$groupName.'"'." src-address-list=".'"'.$groupName.'"'."; \n";

                            //system script
                            $script.=":if ([/system script find name=".'"'."block".$groupName.'"'." ] != ".'""'.") do={ /system script remove [find name=".'"'."block".$groupName.'"'."]; } \n";
                            $script.='/system script add name="block'.$groupName.'" owner=microsystem policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=" ';
                            $script.=' :local restrictedName block'.$groupName;
                            $script.=' :foreach i in=[/ip dns cache all find ] do={';
                            $script.=' :local cacheName [/ip dns cache all get $i name];';
                            $script.=' :if (';
                                    // insert all websites in if condition
                                    $counter=0;
                                    foreach($allURL as $url)
                                    {
                                        if($counter==0){$script.='$cacheName ~\"'.$url->url.'.*\$\"';}
                                        else{$script.=' || $cacheName ~\"'.$url->url.'.*\$\"';}
                                        $counter++;
                                    }
                                    
                            $script.=') do={';
                            $script.=' :local tmpAddress [/ip dns cache all get $i data];';
                            $script.=' :if ([/ip firewall address-list find address=$tmpAddress ] != \"\" ) do={';
                            $script.=' /log info already-added-before;';
                            $script.=' } else={';
                            $script.=' /ip firewall address-list add address=$tmpAddress list=$restrictedName comment=$cacheName; ';
                            $script.=' } } } " '."\n";
                                    
                            //system scheduler
                            $script.=":if ([/system scheduler find name=".'"'."block".$groupName.'"'." ] != ".'""'.") do={ /system scheduler remove [find name=".'"'."block".$groupName.'"'."]; } \n";
                            $script.="/system scheduler add disabled=no interval=1m name=block".$groupName." on-event=block".$groupName." policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive start-time=startup";
                        }
                    }elseif($group->url_filter_state == "0")
                    {// Disable url filter 
                        // remove firewall filter
                        $script.=":if ([/ip firewall filter find src-address-list=".'"'.$groupName.'"'." ] != ".'""'.") do={ /ip firewall filter remove [find src-address-list=".'"'.$groupName.'"'."]; } \n";
                        // remove script
                        $script.=":if ([/system script find name=".'"'."block".$groupName.'"'." ] != ".'""'.") do={ /system script remove [find name=".'"'."block".$groupName.'"'."]; } \n";
                        // remove scheduler
                        $script.=":if ([/system scheduler find name=".'"'."block".$groupName.'"'." ] != ".'""'.") do={ /system scheduler remove [find name=".'"'."block".$groupName.'"'."]; } \n";
                    }

                }//foreach(DB::table($identify.'.area_groups')->where('change_url_filter','1')->where('is_active','1')->get() as $group)
                */

                ////////////////////////////////
                //         URL filter V2
                ////////////////////////////////
    
                //if(isset($completeUnconfiguredCycle)){$urlFilterValue="url_filter";}else{$urlFilterValue="change_url_filter";}

                foreach(DB::table($identify.'.area_groups')->where('change_url_filter','1')->where('is_active','1')->get() as $group)
                {
                    DB::table($identify.'.area_groups')->where('id',$group->id)->update(['change_url_filter' => '0']);
                    
                    if($group->url_filter_type=="1"){ 
                        // block enterd
                        $layer7_protocol="layer7-protocol=";
                    }elseif($group->url_filter_type=="2")
                    {
                        // block all expect enterd !
                        $layer7_protocol="layer7-protocol=!";
                    }

                    $allURL=DB::table($identify.'.url_filter')->where('group_id',$group->id)->get();
                    
                    // Enable url filter 
                    if($group->url_filter_state == "1")
                    {
                        // get group name
                        if($group->as_system == "1"){
                            $groupName=DB::table($identify.'.users')->where('group_id',$group->id)->value('u_uname');
                        }else{
                            $groupName=$group->name;
                        }
                        
                        if(isset($allURL) and count($allURL)>0)
                        {
                              
                            //IP firewall layer7
                            $script.=":if ([/ip firewall layer7-protocol find name=".'"'."block".$groupName.'"'." ] != ".'""'.") do={ /ip firewall layer7-protocol remove [find name=".'"'."block".$groupName.'"'."]; } \n";
                            $script.='/ip firewall layer7-protocol add name="block'.$groupName.'"';
                            $script.=' regexp="^.+(';
                                    // insert all websites in if condition
                                    $counter=0;
                                    foreach($allURL as $url)
                                    {
                                        if($counter==0){$script.=$url->url;}
                                        else{$script.='|'.$url->url;}
                                        // check if 'sex' in urls
                                        if(strpos($url->url, "sex") !== false or strpos($url->url, "adult") !== false){$sexBlock=1;}
                                        $counter++;
                                    }
                                    
                            $script.=').*\$"'." \n";
                                    
                            //ip firewall filter
                            $script.=":if ([/ip firewall filter find src-address-list=".'"'.$groupName.'"'." ] != ".'""'.") do={ /ip firewall filter remove [find src-address-list=".'"'.$groupName.'"'."]; } \n";
                            $script.="/ip firewall filter add action=drop protocol=tcp chain=forward ".$layer7_protocol.'"'."block".$groupName.'"'." src-address-list=".'"'.$groupName.'"'." comment=".'"'."block".$groupName.'"'."; \n";
                            // move this record to be the first record
                            $script.="/ip firewall filter move [/ip firewall filter find comment=".'"'."block".$groupName.'"'."] [:pick [find] 0]; \n";
                            
                            // check if we will apply blocking 'sex' rules through DNS
                            if(isset($sexBlock)){
                                // double check the default settings of the DNS is working fine
                                $script.=":if ([/ip firewall nat find comment=ForceDNS ] != ".'""'.") do={ /ip firewall nat remove [find comment=ForceDNS]; } \n";
                                $script.="/ip dns set allow-remote-requests=yes servers=208.67.222.123,208.67.220.123 \n";
                                $script.="/ip dhcp-server network set [find comment=".'"'."hotspot network".'"'."] dns-server=8.8.8.8,8.8.4.4;"." \n";
                                $script.="/ip dns cache flush \n";
                                // set force DNS record
                                $script.="/ip firewall nat add action=dst-nat chain=dstnat comment=".'"'."ForceAdultDNS".$groupName.'"'." dst-port=53 protocol=udp src-address-list=".'"'.$groupName.'"'." to-addresses=[/ip dhcp-server network get [find comment=".'"'."hotspot network".'"'."] gateway ] to-ports=53 \n";
                                // move this record to be the first record
                                $script.="/ip firewall nat move [/ip firewall nat find comment=".'"'."ForceAdultDNS".$groupName.'"'."] 1; \n";
                            }
                        }
                    }elseif($group->url_filter_state == "0")
                    {// Disable url filter 
                        
                        // get group name
                        if($group->as_system == "1"){
                            $groupName=$group->name; // changed when applying blocking from chatbot
                        }else{
                            $groupName=$group->name;
                        }

                        // check if we will apply unblocking 'sex' rules through DNS
                        foreach($allURL as $url){ if(strpos($url->url, "sex") !== false or strpos($url->url, "adult") !== false){$sexBlock=1;} }
                        if(!isset($sexBlock)){// remove force DNS record
                            $script.=":if ([/ip firewall nat find comment=".'"'."ForceAdultDNS".$groupName.'"'." ] != ".'""'.") do={ /ip firewall nat remove [find comment=".'"'."ForceAdultDNS".$groupName.'"'."]; } \n";
                        }

                        // remove firewall filter
                        $script.=":if ([/ip firewall filter find src-address-list=".'"'.$groupName.'"'." ] != ".'""'.") do={ /ip firewall filter remove [find src-address-list=".'"'.$groupName.'"'."]; } \n";
                        // remove layer7
                        $script.=":if ([/ip firewall layer7-protocol find name=".'"'."block".$groupName.'"'." ] != ".'""'.") do={ /ip firewall layer7-protocol remove [find name=".'"'."block".$groupName.'"'."]; } \n";
                        
                    }

                }//foreach(DB::table($identify.'.area_groups')->where('change_url_filter','1')->where('is_active','1')->get() as $group)


                ////////////////////////////////
                //     log Visited websites
                ////////////////////////////////
                //check backup connection type Orange USB Modem
                if( $finalBranchID->change_users_log_history_state=="1" or $finalBranchID->change_users_log_history_type=="1" or isset($completeUnconfiguredCycle) )
                {
                    DB::table($identify.'.branches')->where('id',$finalBranchID->id)->update(['change_users_log_history_state' => '0', 'change_users_log_history_type' => '0']);
                    DB::table($identify.'.history')->where(['operation'=>'change_users_log_history_state', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    DB::table($identify.'.history')->where(['operation'=>'change_users_log_history_type', 'details'=>'1'])->update(['notes' => NULL, 'details' => "0" ]);
                    
                    if($finalBranchID->users_log_history_state==0){
                        // we will remove log
                        $script.='/system logging action set 3 bsd-syslog=yes remote=0.0.0.0 syslog-facility=user;'."\n";
                        $script.=":if ([/ip firewall nat find comment=VWS ] != ".'""'.") do={ /ip firewall nat remove [find comment=VWS] } \n";
                    }else{
                        // configure websites log
                        //1=websites, 2=detailed IP of outgoing requests, 3=detailed IP of inbound requests, 4=detailed IP of inbound and outgoing requests, 5=All of the above
                        $script.='/system logging action set 3 bsd-syslog=yes remote='.$systemMasterIP.' syslog-facility=user;'."\n";
                        $script.=":if ([/ip firewall nat find comment=VWS ] != ".'""'.") do={ /ip firewall nat remove [find comment=VWS] } \n";
                        $script.=":if ([/system logging find action=remote ] != ".'""'.") do={ /system logging remove [ find action=remote ] } \n";

                        if($finalBranchID->users_log_history_type==2 or $finalBranchID->users_log_history_type==4 or $finalBranchID->users_log_history_type==5)
                        {   // outgoing IP                              
                            $script.="/ip firewall nat add action=log chain=dstnat comment=VWS log-prefix=IP \n";
                        }

                        if($finalBranchID->users_log_history_type==3 or $finalBranchID->users_log_history_type==4 or $finalBranchID->users_log_history_type==5)
                        {   // inbound IP
                            $script.="/ip firewall nat add action=log chain=srcnat comment=VWS log-prefix=IP \n";
                        }

                        if($finalBranchID->users_log_history_type==1 or $finalBranchID->users_log_history_type==5)
                        {   // Websites
                            $script.="/ip proxy set enabled=yes \n";
                            $script.=":if ([/ip firewall nat find to-ports=".'"'."8080".'"'." ] != ".'""'.") do={ /ip firewall nat remove [find to-ports=".'"'."8080".'"'."] } \n";
                            $script.="/ip firewall nat add action=redirect chain=dstnat dst-port=80 protocol=tcp to-ports=8080 \n";
                            $script.="/ip firewall nat add action=redirect chain=dstnat dst-port=443 protocol=tcp to-ports=8080 \n";
                            $script.="/system logging add action=remote topics=account,!debug \n";
                            $script.="/system logging add action=remote topics=web-proxy,!debug \n";
                        }

                        if($finalBranchID->users_log_history_type==2 or $finalBranchID->users_log_history_type==3 or $finalBranchID->users_log_history_type==4 or $finalBranchID->users_log_history_type==5)
                        {   // config logging
                            $script.="/system logging set 0 topics=info,!firewall; \n";
                            $script.="/system logging add action=remote topics=firewall; \n";

                        }
                        
                    }

                }

                ////////////////////////////////
                //   Disconnect online users
                ////////////////////////////////
                foreach(DB::table($identify.'.radacct')->where('realm','1')->get() as $record){
                    //$script.="/ip hotspot active remove [find user=".'"'.$record->username.'"'."]   \n"; 
                    $script.=':if ([ /ip hotspot host find mac-address='.'"'."$record->callingstationid".'"'.' ] != '.'""'.' ) do={ /ip hotspot host remove [find mac-address='.'"'."$record->callingstationid".'"'.' ]; }  '."  \n";
                    $script.=':log warning "'.$record->callingstationid.' Cron Realm, last login: '.$record->acctstoptime.', '.$record->acctterminatecause.'"'."  \n"; 
                    DB::table($identify.'.radacct')->where('radacctid',$record->radacctid)->update(['realm' => '0']);    
                }

                ////////////////////////////////
                //   Remove Speed Limit
                ////////////////////////////////
                foreach(DB::table($identify.'.radacct')->where('realm','2')->get() as $record){
                    $script.=':if ([ /queue simple find target='.'"'."$record->framedipaddress/32".'"'.' ] != '.'""'.' ) do={ /queue simple remove [find target='.'"'."$record->framedipaddress/32".'"'.' ]; }  '."  \n"; 
                    DB::table($identify.'.radacct')->where('radacctid',$record->radacctid)->update(['realm' => '2done']);    
                }

                /////////////////////////////////////////////////////////////////////
                //   Refresh online session in Mikrotik to send access request again (in case of hotel "hosts" config if"SendOnlyNotAuthHosts")
                /////////////////////////////////////////////////////////////////////
                // get all refresh to access requests
                $refresh2Access=DB::table($identify.'.history')->where('branch_id',$finalBranchID->id)->where('operation','refresh2Access')->where('details','1')->get();
                foreach($refresh2Access as $record){
                    // Mark as completed
                    DB::table($identify.'.history')->where(['id'=>$record->id])->update(['details' => "0" ]);
                    // check state
                    $separatedMac=explode(',',$record->notes);
                    foreach($separatedMac as $row)
                    {
                        // Remove Spaces
                        $row = str_replace(' ', '', $row);
                        // Build Mikrotik code
                        $script.=':if ([ /ip hotspot host find mac-address='.'"'.$row.'"'.' ] != '.'""'.' and [ /ip hotspot active find mac-address='.'"'.$row.'"'.' ] = '.'""'.' ) do={ /ip hotspot host remove [find mac-address='.'"'.$row.'"'.' ]; }  '."  \n"; 
		                $script.=':log warning "'.$row.' Refresh2Access."'."  \n"; 
                    }
                }


                $script.=":delay 2 \n";
                $script.="/file remove auto.rsc \n";
                $encoded=htmlentities($script, ENT_NOQUOTES);
                $encoded=str_replace("&lt;","<",$encoded);
                $encoded=str_replace("&gt;",">",$encoded);
                $encoded=str_replace("&amp;","&",$encoded);
                return $encoded;
            }




                //return "cpu=$cpu uptime=$uptime ram=$ram boardname=$boardname";
            }
                
            //  }
        }
        
        
        // if found $reboot="reboot" that means update all radacct opend sessions to stopper with the time now
        // and if not recive branch id we will update all radacct sessions



    }
}