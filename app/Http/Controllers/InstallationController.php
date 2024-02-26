<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use Redirect;
use Auth;
use Carbon\Carbon;
use SSH;
use DB;
use Session;
use Mail;

class InstallationController extends Controller
{

    public function validation(Request $request){
        $subdomain = strtolower($request->subdomain);
        return DB::table('customers')->where('database',$subdomain)->count();
    }
        
    public function session(Request $request){

        $subdomain = strtolower($request->subdomain);
        $fullDomain = $subdomain.$request->masterDomain;
        
        if( Session::has('install_subdomain') ){ Session::forget('install_subdomain'); }
        
        if(!isset($subdomain) or $subdomain=="" ){ return "Sorry! please enter your company name.";}
        elseif (!preg_match("/^[a-zA-Z0-9]*$/",$subdomain)) {
                return "Sorry, Only English letters allowed without spaces or special characters in company name."; 
        }elseif (DB::table('customers')->where('database',$subdomain)->orWhere('url', $fullDomain)->count()>0){ return "Sorry! subdomain already taken.";}
        if( (isset($subdomain) and $subdomain=="microsystem") or (isset($subdomain) and $subdomain=="hotspot")or (isset($subdomain) and $subdomain=="demo") ){ return "Sorry! subdomain already taken.";}

        if(!isset($request->name) or $request->name==""){ return "Sorry! please enter your name.";}
        if(!isset($request->countryCode) or $request->countryCode=="" ){ return "Sorry! please enter your contry code.";}
        if(!isset($request->phoneNumber) or $request->phoneNumber=="" ){ return "Sorry! please enter your mobile numner.";}
        if(!isset($request->mail) or $request->mail=="" ){ return "Sorry! please enter your E-Mail.";}
        elseif (!filter_var($request->mail, FILTER_VALIDATE_EMAIL)) {
                return "Sorry! Invalid email format."; 
        }
        if(!isset($request->password) or $request->password=="" ){ return "Sorry! please enter your password.";}
        if($request->mikrotikType == "appliance" and $request->serial==""){ return "Sorry! please enter your Mikrotik serial number.";}
        
        if( Session::has('install_masterDomain') ){ Session::forget('install_masterDomain'); }
        if( Session::has('install_name') ){ Session::forget('install_name'); }
        if( Session::has('install_subdomain') ){ Session::forget('install_subdomain'); }
        if( Session::has('install_mail') ){ Session::forget('install_mail'); }
        if( Session::has('install_password') ){ Session::forget('install_password'); }
        if( Session::has('install_serial') ){ Session::forget('install_serial'); }
        if( Session::has('install_1stCountryCode') ){ Session::forget('install_1stCountryCode'); }
        if( Session::has('install_1stPhoneNumber') ){ Session::forget('install_1stPhoneNumber'); }
        if( Session::has('install_mikrotikType') ){ Session::forget('install_mikrotikType'); }
        if( Session::has('install_uniqueRealm') ){ Session::forget('install_uniqueRealm'); }
        if( Session::has('install_client_mac') ){ Session::forget('install_client_mac'); }
        if( Session::has('install_verificationCode') ){ Session::forget('install_verificationCode'); }
        if( Session::has('install_business_type') ){ Session::forget('install_business_type'); }

        Session::push('install_masterDomain', $request->masterDomain); 
        Session::push('install_name', $request->name); 
        Session::push('install_subdomain', $subdomain); 
        Session::push('install_mail', $request->mail); 
        Session::push('install_password', $request->password); 
        Session::push('install_serial', $request->serial); 
        $countryCode = str_replace("+","",$request->countryCode);
        Session::push('install_1stCountryCode', $countryCode); 
        Session::push('install_1stPhoneNumber', $request->phoneNumber); 
        Session::push('install_mikrotikType', $request->mikrotikType); 
        Session::push('install_uniqueRealm', $request->uniqueRealm); 
        Session::push('install_client_mac', $request->client_mac); 
        Session::push('install_business_type', $request->install_business_type);

        // 31.3.2020 switch to whatsapp verification
        $verificationCode = rand(1111,9999);
        Session::push('install_verificationCode', $verificationCode); 
        // send WhatsApp Message
        // $data = ['phone' => $request->countryCode.$request->phoneNumber,'body' => "Your verification code is $verificationCode"];
        // $msg = json_encode($data); // Encode data to JSON
        // $url = $whatsappTokenData->chatapi_instance_url."/sendMessage?api_token=".$whatsappTokenData->chatapi_instance_token."&instance=".$whatsappTokenData->chatapi_instance_id;
        // $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
        // $response = json_decode(@file_get_contents($url, FALSE, $context));
        // $responseMsgID = "0";
        $customerMobile = $countryCode.$request->phoneNumber;
        $message = "Your verification code is $verificationCode";
        $message = urlencode($message);
        $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
        $sendWhatsappMessage->send( "", $customerMobile , $message, '3', 'demo', "", "", "", "1");

        return "1"; // incase all previus conditions passed, must return with "1" to proceed next wizard step
    }
    
    public function installation(Request $request){
        
        require_once '../config.php';
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");

        $masterIP = $systemMasterIP;
        $serverLocalIP = $systemLocalIP;
        $currency = $installation_currency;
        $wifiMarketingState = $installation_wifi_marketing_state; // 1: on, 0:off
        $priceingState = $installation_priceing_state; // 1: on, 0:off
        // GETTED FROM CONFIG.PHP FILE AS THE SAME VARIABLE NAME
        // $trialPackageID = "46"; // 20 concurrent device + WiFi marketing module
        // $trialPackageDays = "14"; // days
        // $trialPackageConcurrentDevice = "20";
        // $trialPackageModules = " Automated internet management + Smart WiFi marketing modules ";
        // calculate expiration trial
        $trialExpireDate = date('Y-m-d', strtotime("+$trialPackageDays days", strtotime($today)));
        
        $masterDomain = session('install_masterDomain')[0];
        $subdomain = session('install_subdomain')[0];
        $fullDomain = $subdomain.$masterDomain;
        $dbName = session('install_subdomain')[0];
        $password=session('install_password')[0];
        $encryptedPassword = bcrypt(session('install_password')[0]);
        $name=session('install_name')[0];
        $mail=session('install_mail')[0];
        $mikrotikType=session('install_mikrotikType')[0];
        $serial=session('install_serial')[0];
        $realm=session('install_uniqueRealm')[0];
        $mobile=session('AccountkitFullMobile')[0];
        $businessType=session('install_business_type')[0];

        $masterDomainWithoutDots = substr($masterDomain, 1);
        
        if( $masterDomainWithoutDots == "mikrotik.com.eg" ){
            $poweredBy = 'Powered by <a href="http://mikrotik.com.eg" target="_blank">Mikrotik controller</a>.';
            $serverAPIlink = "https://my.mikrotik.com.eg";
        }else{
            $poweredBy = 'Powered by <a href="http://microsystem.com.eg" target="_blank">Microsystem smart WiFi</a>.';
            $serverAPIlink = "http://s1.microsystem.com.eg";
        }
        
        // send email
        // get system email
        //$mailArray = array($mail, 'support@mikrotik.com.eg');
        $mailArray = array($mail);
        $bcc = array('sales@microsystem.com.eg');
        
        // sending email
        $content = "Dear $name, <br><br> <font color=green> Congratulations, Your whitelabel Mikrotik controller has been created successfully.</font>";
        if( DB::table('serials')->where('serial',session('install_serial')[0])->whereNull('customer_id')->count() != "1" ){
        $content.='
            <br><br>
            Admin panel: <a target="_blank" href="http://'.$fullDomain.'/login?email='.$mail.'"> http://'.$fullDomain.'/admin</a>
            <br>
            Email: '.$mail.'
            <br>
            Password: '.$password.'
            <a target="_blank" href="http://'.$fullDomain.'/settings"> <h3> Package Details </h3> </a>
            Modules: '.$trialPackageModules.'
            <br> Concurrent devices: '.$trialPackageConcurrentDevice.'
            <br> Expiration date: '.$trialExpireDate.'
            <br> Packages link: <a target="_blank" href="http://'.$fullDomain.'/settings"> click here</a>
            <br><br> in order to complete your Mikrotik installation you can proceed the following steps: 
            <body class="full-page-wizard">
                <fieldset class="wizard-fieldset fields-list">

                        <legend class="legend">Installation steps</legend>
                        
                        <div class="field-block button-height">
                            <span class="label"><h2 class="thin mid-margin-bottom">Step A</h2></span>
                            <h2 class="thin mid-margin-bottom no-margin-top">Download and open Winbox</h2>
                            <h5 class="no-margin-top">
                            1 - Open <a href="https://download.mikrotik.com/routeros/winbox/3.13/winbox.exe" target="_blank">Mikrotik Winbox</a>, then click on "Neighbors". <br>
                            2 - Wait 30 seconds then click on Displayed Mac-Address. <br>
                            3 - Enter your username (admin) and empty password; or enter your setted credentials. <br>
                            4 - Click "Connect". </h5> 
                            <center><img src="http://s1.microsystem.com.eg/assets/images/mikrotik1.png" width="50%" height="50%"></center>
                        </div><hr>';
                        
                        // <div class="field-block button-height">
                        //     <span class="label"><h2 class="thin mid-margin-bottom">Step B</h2></span>
                        //     <h2 class="thin mid-margin-bottom no-margin-top">Reset Configuration</h2>
                        //     <h5 class="no-margin-top">1 - Click on "System". <br>
                        //     2 - Click on "Reset Configuration". <br>
                        //     3 - From Reset configuration window click on "Reset Configuration". <br>
                        //     4 - Click "Yes". </h5> 
                        //     <center><img src="http://s1.microsystem.com.eg/assets/images/mikrotik2.png" width="50%" height="65%"></center>
                        // </div><hr>

                        // <div class="field-block button-height">
                        //     <span class="label"><h2 class="thin mid-margin-bottom">Step C</h2></span>
                        //     <h2 class="thin mid-margin-bottom no-margin-top">Remove Default Configuration</h2>
                        //     <h5 class="no-margin-top">Close Winbox and open it again, then click on "Remove Configuration". </h5>
                        //     <center><img src="http://s1.microsystem.com.eg/assets/images/mikrotik3.png" width="50%" height="60%"></center>
                        // </div><hr>

                        $content.='<div class="field-block button-height">
                            <span class="label"><h2 class="thin mid-margin-bottom">Step B</h2></span>
                            <h2 class="thin mid-margin-bottom no-margin-top">Copy Mikrotik Script</h2>
                            <h5 class="no-margin-top">1 - Click on "New Terminal". <br>
                            2 - Copy all the following code and paste it into "New Terminal". </h5>
                            
                               <center><img src="http://s1.microsystem.com.eg/assets/images/mikrotik4.png" width="50%" height="55%"></center><br>
                                    <div class="with-padding"><center>

                                        <center><h3 class="thin mid-margin-bottom no-margin-top">Press CTRL + A <br> Press CTRL + C</h3></center>
                                        <textarea cols="50" rows="30" class="input full-width autoexpanding" style="overflow: hidden;">
                                        ';
                                        /*
                                        if( session('install_mikrotikType')[0]=="appliance" ){
                                                $content.='
                                                        /interface set [ find default-name=ether1 ] name=IN
                                                        /interface bridge add name=OUT
                                                        :if ([/interface find name=ether2 ] != "") do={/interface bridge port add bridge=OUT interface=ether2}
                                                        :if ([/interface find name=ether3 ] != "") do={/interface bridge port add bridge=OUT interface=ether3}
                                                        :if ([/interface find name=ether4 ] != "") do={/interface bridge port add bridge=OUT interface=ether4}
                                                        :if ([/interface find name=ether5 ] != "") do={/interface bridge port add bridge=OUT interface=ether5}
                                                        :if ([/interface find name=ether6 ] != "") do={/interface bridge port add bridge=OUT interface=ether6}
                                                        :if ([/interface find name=ether7 ] != "") do={/interface bridge port add bridge=OUT interface=ether7}
                                                        :if ([/interface find name=ether8 ] != "") do={/interface bridge port add bridge=OUT interface=ether8}
                                                        :if ([/interface find name=ether9 ] != "") do={/interface bridge port add bridge=OUT interface=ether9}
                                                        :if ([/interface find name=ether10 ] != "") do={/interface bridge port add bridge=OUT interface=ether10}
                                                        :if ([/interface find name=ether11 ] != "") do={/interface bridge port add bridge=OUT interface=ether11}
                                                        :if ([/interface find name=ether12 ] != "") do={/interface bridge port add bridge=OUT interface=ether12}
                                                        :if ([/interface find name=ether13 ] != "") do={/interface bridge port add bridge=OUT interface=ether13}
                                                        :if ([/interface find name=ether14 ] != "") do={/interface bridge port add bridge=OUT interface=ether14}
                                                        :if ([/interface find name=ether15 ] != "") do={/interface bridge port add bridge=OUT interface=ether15}
                                                        :if ([/interface find name=ether16 ] != "") do={/interface bridge port add bridge=OUT interface=ether16}
                                                        :if ([/interface find name=ether17 ] != "") do={/interface bridge port add bridge=OUT interface=ether17}
                                                        :if ([/interface find name=ether18 ] != "") do={/interface bridge port add bridge=OUT interface=ether18}
                                                        :if ([/interface find name=ether19 ] != "") do={/interface bridge port add bridge=OUT interface=ether19}
                                                        :if ([/interface find name=ether20 ] != "") do={/interface bridge port add bridge=OUT interface=ether20}
                                                        :if ([/interface find name=ether21 ] != "") do={/interface bridge port add bridge=OUT interface=ether21}
                                                        :if ([/interface find name=ether22 ] != "") do={/interface bridge port add bridge=OUT interface=ether22}
                                                        :if ([/interface find name=ether23 ] != "") do={/interface bridge port add bridge=OUT interface=ether23}
                                                        :if ([/interface find name=ether24 ] != "") do={/interface bridge port add bridge=OUT interface=ether24}
                                                        :if ([/interface find name=wlan1 ] != "") do={/interface bridge port add bridge=OUT interface=wlan1}
                                                        :if ([/interface find name=wlan2 ] != "") do={/interface bridge port add bridge=OUT interface=wlan2}

                                                        :if ([/interface find name=wlan1 ] != "") do={/interface wireless set [ find default-name=wlan1 ] band=2ghz-b/g/n frequency=auto disabled=no mode=ap-bridge ssid=Microsystem}

                                                        /ip dhcp-client add default-route-distance=1 dhcp-options=hostname,clientid disabled=no interface=IN

                                                        /ip address add address=10.5.50.1/24 comment="hotspot network" interface=OUT network=10.5.50.0

                                                        /ip pool add name=hs-pool-10 ranges=10.5.50.2-10.5.50.254

                                                        /ip dns set allow-remote-requests=yes servers=8.8.8.8,4.2.2.2

                                                        /ip firewall nat add action=masquerade chain=srcnat comment="masquerade hotspot network"

                                                        /ip dhcp-server add address-pool=hs-pool-10 disabled=no interface=OUT lease-time=1h name=dhcp1

                                                        /ip dhcp-server network add address=10.5.50.0/24 comment="hotspot network" gateway=10.5.50.1

                                                        /ip hotspot profile add dns-name=internet.microsystem.com.eg hotspot-address=10.5.50.1 name=hsprof1 login-by=cookie,http-pap,mac use-radius=yes radius-accounting=yes radius-interim-update=1m

                                                        /ip hotspot add address-pool=hs-pool-10 disabled=no interface=OUT name=hotspot1 profile=hsprof1

                                                        /ip hotspot user add name=admin password=microsystem

                                                        /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=13.81.62.85 !dst-port !protocol !src-address
                                                        /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=13.94.129.188 !dst-port !protocol !src-address
                                                        /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=52.169.225.126 !dst-port !protocol !src-address
                                                        /ip hotspot walled-garden add dst-host=my.microsystem.com.eg
                                                        /ip hotspot walled-garden add dst-host=*.fbcdn.net
                                                        /ip hotspot walled-garden add dst-host=*.facebook.com
                                                        /ip hotspot walled-garden add dst-host=*.accountkit.*
                                                        /ip hotspot walled-garden add dst-host=*.cloudfront.net
                                                        /ip hotspot walled-garden add dst-host=*.mymicrosystem.*
                                                        /ip hotspot walled-garden add dst-host=*.mikrotik.*
                                                        /ip hotspot walled-garden add dst-host=*.microsystemapp.*
                                                        /ip hotspot walled-garden add dst-host=*.microsystem.*
                                                        /ip hotspot walled-garden add dst-host=*comodoca*
                                                        /ip hotspot walled-garden add dst-host=*.rapidssl.*

                                                        /radius incoming set accept=yes

                                                        /system identity set name=unconfigured

                                                        /tool netwatch add down-script="/ip hotspot set [ find name=hotspot1 ] disabled=yes" host=13.81.62.85 interval=2m timeout=2s up-script="/ip hotspot set [ find name=hotspot1 ] disabled=no"

                                                        /system script
                                                        add name="after_reboot" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source="delay 30\r\
                                                        \n:if ( [/interface ppp-client find name=vodafone ] != \"\" or [/interface ppp-client find name=etisalat ] != \"\" or [/interface ppp-client find name=or\
                                                        ange ] != \"\") do={ /log info \"AutoDetectedAlreadyExist\"; } else={\r\
                                                        \n:foreach i in=[/port find] do={\r\
                                                        \n:local state [/port get \$i i ] ;\r\
                                                        \n:local name [/port get \$i name ] ;\r\
                                                        \n:if (\$state = false) do={\r\
                                                        \n:if ([/interface ppp-client find name=autodetected ] != \"\") do={ /interface ppp-client remove autodetected; }\r\
                                                        \n:if ([/system resource usb find device-id=0x0117 ] != \"\") do={\r\
                                                        \n/interface ppp-client add apn=internet data-channel=2 info-channel=1 dial-on-demand=no disabled=no name=autodetected port=\$name;\r\
                                                        \ndelay 30\r\
                                                        \n} else={\r\
                                                        \n/interface ppp-client add apn=internet dial-on-demand=no disabled=no  name=autodetected port=\$name;\r\
                                                        \ndelay 30\r\
                                                        \n}\r\
                                                        \n}\r\
                                                        \n}\r\
                                                        \n}\r\
                                                        \n:if ([/ip hotspot profile find name=hsprof1 ] != \"\" and [/radius find address=13.81.62.85 ] != \"\") do={\r\
                                                        \n:local branchid [/ip hotspot profile get [ find name=hsprof1 ] radius-location-id ];\r\
                                                        \n:local secret [/radius get [ find address=13.81.62.85 ] secret ];\r\
                                                        \n:local realm [/radius get [ find address=13.81.62.85 ] realm ];\r\
                                                        \n:local identify [/system identity get name];\r\
                                                        \n:local serial [/system routerboard get serial-number];\r\
                                                        \n/tool fetch url=\"'.$serverAPIlink.'/mikrotikapi\\\?identify=\$identify&secret=\$secret&reboot=reboot&branchid=\$branchid&realm=\$realm&serial=\$serial\" mode=http dst-path=\"reboot.rsc\"\r\
                                                        \n}"

                                                        add name=Microsystem policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":local identify [/system identity get name];\r\
                                                        \n:local serial [/system routerboard get serial-number];\r\
                                                        \n:local cpu [/system resource get cpu-load];\r\
                                                        \n:local uptime [/system resource get uptime];\r\
                                                        \n:local ram [/system resource get free-memory];\r\
                                                        \n:local boardname [/system resource get board-name];\r\
                                                        \n\r\
                                                        \n:if ([/system routerboard get routerboard ] = yes) do={\r\
                                                        \n\r\
                                                        \n/ip cloud set ddns-enabled=yes\r\
                                                        \n/ip cloud force-update\r\
                                                        \n:delay 5\r\
                                                        \n:global publicIP [/ip cloud get public-address];\r\
                                                        \n:global DNSname [/ip cloud get dns-name];\r\
                                                        \n:delay 1\r\
                                                        \n} else={\r\
                                                        \n:global publicIP \"\";\r\
                                                        \n:global DNSname \"\";\r\
                                                        \n}\r\
                                                        \n \r\
                                                        \n:global publicIP [/system script environment get [ find name=publicIP ] value ]; \r\
                                                        \n:global DNSname [/system script environment get [ find name=DNSname ] value ]; \r\
                                                        \n\r\
                                                        \n:if ([/ip hotspot profile find name=hsprof1 ] != \"\" and [/radius find address=13.81.62.85 ] != \"\") do={\r\
                                                        \n\r\
                                                        \n:local branchid [/ip hotspot profile get [ find name=hsprof1 ] radius-location-id ];\r\
                                                        \n:local realm [/radius get [ find address=13.81.62.85 ] realm ];\r\
                                                        \n\r\
                                                        \n/tool fetch url=\"'.$serverAPIlink.'/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
                                                        &branchid=\$branchid&realm=\$realm&publicip=\$publicIP&dnsname=\$DNSname\" mode=http dst-path=\"auto.rsc\"\r\
                                                        \nimport file-name=auto.rsc\r\
                                                        \n} else={\r\
                                                        \n/tool fetch url=\"'.$serverAPIlink.'/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
                                                        &publicip=\$publicIP&dnsname=\$DNSname\" mode=http dst-path=\"auto.rsc\"\r\
                                                        \nimport file-name=auto.rsc\r\
                                                        \n\r\
                                                        \n}\r\
                                                        \n\r\
                                                        \n\r\
                                                        \n\r\
                                                        \n"

                                                        add name=remove_hosts_daily policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=\
                                                        ":foreach ENTRY in=[/ip hotspot host find] do={\r\
                                                        \n         /ip hotspot host remove number=\$ENTRY\r\
                                                        \n}"

                                                        add name=remove_inactive_hosts policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":foreach i in=[/ ip hotspot host find] do=\
                                                        {\r\
                                                        \n\t:local state [/ip hotspot host get \$i authorized ] ;\r\
                                                        \n                :local bypassed [/ip hotspot host get \$i bypassed ] ;\r\
                                                        \n\t:if ( \$state!=true and \$bypassed !=true ) do={\r\
                                                        \n\t\t/ip hotspot host remove \$i;\r\
                                                        \n\t}\r\
                                                        \n}"
                                                        
                                                        add name=ssl_renew policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":log error \"internet test has been started\";\r\
                                                        \n:local Failed 0;\r\
                                                        \n       :for i from=1 to=5 do={\r\
                                                        \n       :if ([/ping 8.8.8.8 count=1]=0) do={:set Failed (\$Failed + 1)}\r\
                                                        \n       :delay 1;\r\
                                                        \n       };\r\
                                                        \n\r\
                                                        \n:if (\$Failed=0) do={\r\
                                                        \n:log info \"internet is working fine\";\r\
                                                        \n\r\
                                                        \n:if ([:len [/file find name=internet.microsystem.com.eg.cer]] > 0) do={ \r\
                                                        \n\r\
                                                        \n   :foreach ENTRY in=[/certificate find] do={\r\
                                                        \n   /certificate remove number=\$ENTRY\r\
                                                        \n   }\r\
                                                        \n\r\
                                                        \n:delay 1\r\
                                                        \n/file remove internet.microsystem.com.eg.cer\r\
                                                        \n/file remove internet.microsystem.com.eg.key\r\
                                                        \n:delay 1\r\
                                                        \n}\r\
                                                        \n\r\
                                                        \n/tool fetch url=\"'.$serverAPIlink.'/ssl/internet.microsystem.com.eg.key\" mode=http\r\
                                                        \n:delay 5\r\
                                                        \n/tool fetch url=\"'.$serverAPIlink.'/ssl/internet.microsystem.com.eg.cer\" mode=http\r\
                                                        \n:delay 5\r\
                                                        \n\r\
                                                        \n/certificate import file-name=internet.microsystem.com.eg.cer passphrase=\"\"\r\
                                                        \n/certificate import file-name=internet.microsystem.com.eg.key passphrase=\"\"\r\
                                                        \n:delay 2\r\
                                                        \n\r\
                                                        \n/ip service set www-ssl certificate=internet.microsystem.com.eg.cer_0 \r\
                                                        \n/ip hotspot profile set [ find name=hsprof1 ] dns-name=internet.microsystem.com.eg login-by=cookie,http-pap,mac,https ssl-certificate=internet.microsystem.com.eg.cer\
                                                        _0\r\
                                                        \n:log error \"SSL certificate has been installed successfully\";\r\
                                                        \n/system scheduler set [ find name=ssl ] interval=4w2d10m\r\
                                                        \n} else {\r\
                                                        \n:log error \"internet down - retry in 5 minuts\";\r\
                                                        \n/system scheduler set [ find name=ssl ] interval=5m\r\
                                                        \n}"

                                                        add name=installation policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":local mac [system routerboard get serial-number];\r\
                                                        \n:if (  [system routerboard get routerboard] = yes ) do {:set mac [system routerboard get serial-number]} else { :set mac [interface ethernet get ether1 mac-address]}\
                                                        ;\r\
                                                        \n/file set \"hotspot/login.html\" contents=\"<meta http-equiv=\"refresh\" content=\"0; url=http://my.microsystem.com.eg/login\?serial=\$mac&client_mac=\\\$(mac)&client_i\
                                                        p=\\\$(ip)\">\";\r\
                                                        \n"

                                                        /system scheduler
                                                        add name="after_reboot" on-event="/system scheduler set [ find name=ssl ] interval=1m;\r\
                                                        \n/system script run after_reboot;" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=startup
                                                        add interval=1m name=Microsystem on-event="system script run remove_inactive_hosts;\r\
                                                        \nsystem script run Microsystem;" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=startup
                                                        add interval=23h59m59s name="new day" on-event=remove_hosts_daily policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=00:10:00
                                                        add interval=4w2d10m name=ssl on-event="/system script run ssl_renew;" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=startup

                                                        /ip service
                                                        set api disabled=no
                                                        set ftp disabled=yes
                                                        set ssh disabled=yes
                                                        set telnet disabled=yes

                                                        /user set [find name=admin] name="'.session('install_mail')[0].'" password="'.session('install_password')[0].'"
                                                        /system script run installation
                                                ';
                                                */
                                        //}else{
                                                $content.='
                                                        /system identity set name='.session('install_subdomain')[0].'
                                                        /radius add address='.$systemMasterIP.' realm='.session('install_uniqueRealm')[0].' secret=microsystem service=hotspot timeout=3s comment='.session('install_subdomain')[0].session('install_masterDomain')[0].'

                                                        /ip hotspot profile set [ find name=hsprof1 ] dns-name=internet.microsystem.com.eg login-by=cookie,http-pap,mac use-radius=yes radius-accounting=yes radius-interim-update=1m radius-location-name='.session('install_subdomain')[0].'

                                                        /file set "hotspot/login.html" contents="<meta http-equiv="refresh" content="0; url=http://'.session('install_subdomain')[0].session('install_masterDomain')[0].'">"

                                                        /ip hotspot walled-garden
                                                        add dst-host='.session('install_subdomain')[0].session('install_masterDomain')[0].'
                                                        add dst-host=http://'.session('install_subdomain')[0].session('install_masterDomain')[0].'
                                                        add dst-host=http://www.'.session('install_subdomain')[0].session('install_masterDomain')[0].'
                                                        add dst-host=www.'.session('install_subdomain')[0].session('install_masterDomain')[0].'
                                                        add dst-host=*'.session('install_masterDomain')[0].'

                                                        /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host='.$systemMasterIP.' !dst-port !protocol !src-address
                                                        /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=13.94.129.188 !dst-port !protocol !src-address
                                                        /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=52.169.225.126 !dst-port !protocol !src-address
                                                        /ip hotspot walled-garden add dst-host=my.microsystem.com.eg
                                                        /ip hotspot walled-garden add dst-host=*.accountkit.*
                                                        /ip hotspot walled-garden add dst-host=*.fbcdn.net
                                                        /ip hotspot walled-garden add dst-host=*.facebook.com
                                                        /ip hotspot walled-garden add dst-host=*.mymicrosystem.*
                                                        /ip hotspot walled-garden add dst-host=*.mikrotik.*
                                                        /ip hotspot walled-garden add dst-host=*.microsystemapp.*
                                                        /ip hotspot walled-garden add dst-host=*.microsystem.*
                                                        /ip hotspot walled-garden add dst-host=*.cloudfront.net
                                                        /ip hotspot walled-garden add dst-host=*comodoca*
                                                        /ip hotspot walled-garden add dst-host=*.rapidssl.*

                                                        /radius incoming set accept=yes

                                                        /tool netwatch add down-script="/ip hotspot set [ find name=hotspot1 ] disabled=yes" host='.$systemMasterIP.' interval=2m timeout=2s up-script="/ip hotspot set [ find name=hotspot1 ] disabled=no"

                                                        /system script
                                                        add name="after reboot" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source="delay 30\r\
                                                                \nif ( [/interface ppp-client find name=vodafone ] != \"\" or [/interface ppp-client find name=etisalat ] != \"\" or [/interface ppp-client find name=or\
                                                                ange ] != \"\") do={ /log info \"AutoDetectedAlreadyExist\"; } else={\r\
                                                                \n:foreach i in=[/port find] do={\r\
                                                                \n:local state [/port get \$i i ] ;\r\
                                                                \n:local name [/port get \$i name ] ;\r\
                                                                \nif (\$state = false) do={\r\
                                                                \nif ([/interface ppp-client find name=autodetected ] != \"\") do={ /interface ppp-client remove autodetected; }\r\
                                                                \nif ([/system resource usb find device-id=0x0117 ] != \"\") do={\r\
                                                                \n/interface ppp-client add apn=internet data-channel=2 info-channel=1 dial-on-demand=no disabled=no name=autodetected port=\$name;\r\
                                                                \ndelay 30\r\
                                                                \n} else={\r\
                                                                \n/interface ppp-client add apn=internet dial-on-demand=no disabled=no  name=autodetected port=\$name;\r\
                                                                \ndelay 30\r\
                                                                \n}\r\
                                                                \n}\r\
                                                                \n}\r\
                                                                \n}\r\
                                                                \nif ([/ip hotspot profile find name=hsprof1 ] != \"\" and [/radius find address='.$systemMasterIP.' ] != \"\") do={\r\
                                                                \n:local branchid [/ip hotspot profile get [ find name=hsprof1 ] radius-location-id ];\r\
                                                                \n:local secret [/radius get [ find address='.$systemMasterIP.' ] secret ];\r\
                                                                \n:local realm [/radius get [ find address='.$systemMasterIP.' ] realm ];\r\
                                                                \n:local identify [/system identity get name];\r\
                                                                \n:local serial [/system routerboard get serial-number];\r\
                                                                \n/tool fetch url=\"'.$serverAPIlink.'/mikrotikapi\\\?identify=\$identify&secret=\$secret&reboot=reboot&branchid=\$branchid&realm=\$realm&serial=\$serial\" mode=http dst-path=\"reboot.rsc\"\r\
                                                                \n}"

                                                        add name=Microsystem policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":local identify [/system identity get name];\r\
                                                                \n:local serial [/system routerboard get serial-number];\r\
                                                                \n:local cpu [/system resource get cpu-load];\r\
                                                                \n:local uptime [/system resource get uptime];\r\
                                                                \n:local ram [/system resource get free-memory];\r\
                                                                \n:local boardname [/system resource get board-name];\r\
                                                                \n\r\
                                                                \n/ip cloud set ddns-enabled=yes\r\
                                                                \n/ip cloud force-update\r\
                                                                \n:delay 5\r\
                                                                \n:global publicIP [/ip cloud get public-address];\r\
                                                                \n:global DNSname [/ip cloud get dns-name];\r\
                                                                \n \r\
                                                                \nif ([/ip hotspot profile find name=hsprof1 ] != \"\" and [/radius find address='.$systemMasterIP.' ] != \"\") do={\r\
                                                                \n\r\
                                                                \n:local branchid [/ip hotspot profile get [ find name=hsprof1 ] radius-location-id ];\r\
                                                                \n:local realm [/radius get [ find address='.$systemMasterIP.' ] realm ];\r\
                                                                \n\r\
                                                                \n/tool fetch url=\"'.$serverAPIlink.'/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
                                                                &branchid=\$branchid&realm=\$realm&publicip=\$publicIP&dnsname=\$DNSname\" mode=http dst-path=\"auto.rsc\"\r\
                                                                \nimport file-name=auto.rsc\r\
                                                                \n} else={\r\
                                                                \n/tool fetch url=\"'.$serverAPIlink.'/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
                                                                &publicip=\$publicIP&dnsname=\$DNSname\" mode=http dst-path=\"auto.rsc\"\r\
                                                                \nimport file-name=auto.rsc\r\
                                                                \n\r\
                                                                \n}\r\
                                                                \n\r\
                                                                \n\r\
                                                                \n"

                                                        add name=remove_hosts_daily policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=\
                                                                ":foreach ENTRY in=[/ip hotspot host find] do={\r\
                                                                \n         /ip hotspot host remove number=\$ENTRY\r\
                                                                \n}"

                                                        add name=remove_inactive_hosts policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":foreach i in=[/ ip hotspot host find] do=\
                                                                {\r\
                                                                \n\t:local state [/ip hotspot host get \$i authorized ] ;\r\
                                                                \n                :local bypassed [/ip hotspot host get \$i bypassed ] ;\r\
                                                                \n\t:if ( \$state!=true and \$bypassed !=true ) do={\r\
                                                                \n\t\t/ip hotspot host remove \$i;\r\
                                                                \n\t}\r\
                                                                \n}"
                                                                
                                                        add name=ssl_renew policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":log error \"internet test has been started\";\r\
                                                                \n:local Failed 0;\r\
                                                                \n       :for i from=1 to=5 do={\r\
                                                                \n       if ([/ping 8.8.8.8 count=1]=0) do={:set Failed (\$Failed + 1)}\r\
                                                                \n       :delay 1;\r\
                                                                \n       };\r\
                                                                \n\r\
                                                                \nif (\$Failed=0) do={\r\
                                                                \n:log info \"internet is working fine\";\r\
                                                                \n\r\
                                                                \n:if ([:len [/file find name=internet.microsystem.com.eg.cer]] > 0) do={ \r\
                                                                \n\r\
                                                                \n   :foreach ENTRY in=[/certificate find] do={\r\
                                                                \n   /certificate remove number=\$ENTRY\r\
                                                                \n   }\r\
                                                                \n\r\
                                                                \n:delay 1\r\
                                                                \n/file remove internet.microsystem.com.eg.cer\r\
                                                                \n/file remove internet.microsystem.com.eg.key\r\
                                                                \n:delay 1\r\
                                                                \n}\r\
                                                                \n\r\
                                                                \n/tool fetch url=\"'.$serverAPIlink.'/ssl/internet.microsystem.com.eg.key\" mode=http\r\
                                                                \n:delay 5\r\
                                                                \n/tool fetch url=\"'.$serverAPIlink.'/ssl/internet.microsystem.com.eg.cer\" mode=http\r\
                                                                \n:delay 5\r\
                                                                \n\r\
                                                                \n/certificate import file-name=internet.microsystem.com.eg.cer passphrase=\"\"\r\
                                                                \n/certificate import file-name=internet.microsystem.com.eg.key passphrase=\"\"\r\
                                                                \n:delay 2\r\
                                                                \n\r\
                                                                \n/ip service set www-ssl certificate=internet.microsystem.com.eg.cer_0 \r\
                                                                \n/ip hotspot profile set [ find name=hsprof1 ] dns-name=internet.microsystem.com.eg login-by=cookie,http-pap,mac,https ssl-certificate=internet.microsystem.com.eg.cer\
                                                                _0\r\
                                                                \n:log error \"SSL certificate has been installed successfully\";\r\
                                                                \n/system scheduler set [ find name=ssl ] interval=4w2d10m\r\
                                                                \n} else {\r\
                                                                \n:log error \"internet down - retry in 5 minuts\";\r\
                                                                \n/system scheduler set [ find name=ssl ] interval=5m\r\
                                                                \n}"

                                                        add name=installation policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon source=":local mac [system routerboard get serial-number];\r\
                                                                \n:if (  [system routerboard get routerboard] = yes ) do {:set mac [system routerboard get serial-number]} else { :set mac [interface ethernet get ether1 mac-address]}\
                                                                ;\r\
                                                                \n/file set \"hotspot/login.html\" contents=\"<meta http-equiv=\"refresh\" content=\"0; url=http://my.microsystem.com.eg/login\?serial=\$mac&client_mac=\\\$(mac)&client_i\
                                                                p=\\\$(ip)\">\";\r\
                                                                \n"

                                                        /system scheduler
                                                        add name="after reboot" on-event="/system scheduler set [ find name=ssl ] interval=1m;\r\
                                                                \n/system script run after reboot;" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=startup
                                                        add interval=1m name=Microsystem on-event="system script run remove_inactive_hosts;\r\
                                                                \nsystem script run Microsystem;" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=startup
                                                        add interval=23h59m59s name="new day" on-event=remove_hosts_daily policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=00:10:00
                                                        add interval=4w2d10m name=ssl on-event="/system script run ssl_renew;" policy=ftp,reboot,read,write,policy,test,password,sniff,sensitive,romon start-time=startup

                                                        /ip service
                                                        set api disabled=no
                                                        set ftp disabled=yes
                                                        set ssh disabled=yes
                                                        set telnet disabled=yes

                                                        /ip cloud
                                                        set ddns-enabled=yes

                                                        /interface sstp-client add connect-to=52.233.172.210:9090 disabled=no http-proxy=0.0.0.0:9090 name=cloud password=1403636mra profile=default-encryption user='.session('install_subdomain')[0].' verify-server-address-from-certificate=no
                                                        /user set [find name=admin] name="'.session('install_mail')[0].'" password="'.session('install_password')[0].'"
                                                        /system script run installation
                                                ';
                                        //}
                                        $content.='
                                        </textarea></center>
                                    </div>
                              
                        </div>
                        
                    </fieldset>
            </body>
        ';
        }
        $content.= "<br>
                For any assistance you can call account manager: +2 010 126 66 854.
                <br>
                or send mail request to: support@mikrotik.com.eg.
                <br><br>
                Thanks,<br>
                Best Regards.<br>";
        $from = "support@mikrotik.com.eg";
        $subject = "Congratulations, Your whitelabel Mikrotik controller has been created successfully.";
        //return $content;
        
        // Mail::send('emails.send', ['title' => $subject, 'content' => $content, 'poweredBy' => $poweredBy], function ($message) use ($mailArray, $name, $from, $subject, $bcc) {
        //         $message->from($from, "Mikrotik Controller");
        //         $message->to($mailArray, $name)->bcc($bcc)->subject($subject);
        // });
        

        // check if domain exist
        // if(DB::table('customers')->where('url',$fullDomain)->count()>0){$cantStart=1;}

        // check if mail exist
        // if(DB::table('customers')->where('mail',$request->input('mail'))->count()>0){$cantStart=1;}

        // check if serial exist
        // if(DB::table('serials')->where('serial',$request->input('serial'))->count()>0){$cantStart=1;}
        
        // check if user enter download speed or system have ddwrt hardware 

        // create customer DB
        DB::statement("DROP database IF EXISTS $dbName;");
        DB::statement("create database $dbName;");

        // return "mysqldump -u $sys_db_user --password='$sys_db_pass' MicrosystemDefault | mysql -u $sys_db_user --password='$sys_db_pass' -h localhost $dbName";
        // create DB tables by copy "MicrosystemDefault" db to new DB
        shell_exec("mysqldump -u $sys_db_user --password='$sys_db_pass' MicrosystemDefault | mysql -u $sys_db_user --password='$sys_db_pass' -h localhost $dbName");
        // hash admin password to insert admin record in admin table
        DB::statement("update $dbName.admins set `name` = '$mail', `uname` = '$mail', `email` = '$mail', `password` = '$encryptedPassword' where id = '1';");
        // update branch WiFi 
        if( Session::has('install_mikrotikType') and session('install_mikrotikType')[0]=="appliance" ){
                DB::table($dbName.".branches")->where( 'id', '1' )->update([ 'wireless_state' => $_REQUEST['wifiState'], 'wireless_name' => $_REQUEST['wifiName'], 'wireless_pass' => $_REQUEST['wifiPassword'], 'private_wireless_state' => $_REQUEST['privateWifiState'], 'private_wireless_name' => $_REQUEST['privateWifiName'], 'private_wireless_pass' => $_REQUEST['privateWifiPassword'] ]);
        }
        // add load balanceing
        if( $_REQUEST['loadBalanceingState']=="1" ){

                DB::table($dbName.".branches")->where( 'id', '1' )->update([ 'connection_type' => '6']);
                $counter = 0;
                foreach( $_REQUEST['ip'] as $line)
                {
                        DB::table($dbName.".load_balancing")->insert([['branch_id' => '1', 'ip' => $_REQUEST['ip'][$counter], 'gateway' => $_REQUEST['gateway'][$counter], 'speed' => $_REQUEST['speed'][$counter] , 'type' => '0']]);
                        $counter++;
                }
        }

        // set speed equation
        
        // set browseing speed in groups
        $browsingDownSpeed = $_REQUEST['browsingDownSpeed'];
        $browsingDownType = $_REQUEST['browsingDownType'];
        $browsingUpSpeed = $_REQUEST['browsingUpSpeed'];
        $browsingUpType = $_REQUEST['browsingUpType'];

        $downloadDownSpeed = $_REQUEST['downloadDownSpeed'];
        $downloadDownType = $_REQUEST['downloadDownType'];
        $downloadUpSpeed = $_REQUEST['downloadUpSpeed'];
        $downloadUpType = $_REQUEST['downloadUpType'];

        $startPriority = "8";

        if ( $_REQUEST['downloadDownSpeed'] != null && $_REQUEST['downloadUpSpeed'] != null){ $speedType1=$downloadDownType; $speedType2=$downloadUpType; $upSpeedLimit=$downloadDownSpeed; $downSpeedLimit=$downloadUpSpeed; $replaceDownSpeedIntoAvarege=0;}
        else { $speedType1=$browsingDownType; $speedType2=$browsingUpType; $upSpeedLimit=$browsingDownSpeed; $downSpeedLimit=$browsingUpSpeed; $replaceDownSpeedIntoAvarege=1;}
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
        else{ $downloadSpeedCode = $downloadUpSpeed . $downloadUpType . '/' . $downloadDownSpeed . $downloadDownType; }
        // set final equation
        $browseingSpeedCode = $browsingUpSpeed . $browsingUpType . '/' . $browsingDownSpeed . $browsingDownType;
        $avarageSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K';
        $startSpeedSeconds = '60'; 
        $startSpeedLimitFullCode = $downloadSpeedCode . ' '. $browseingSpeedCode . ' ' .$avarageSpeedCode . ' ' . $startSpeedSeconds . ' ' . $startPriority;
        $startSpeed = $startSpeedLimitFullCode;
        

        if($_REQUEST['afterQuotaFinish']=="downgrade"){
                $ifDowngradeSpeed = 1;

                $end_speed1_edit = $_REQUEST['downgradeBrowsingDownSpeed'];
                $end_speed2_edit = $_REQUEST['downgradeBrowsingDownType'];
                $end_speed01_edit = $_REQUEST['downgradeBrowsingUpSpeed'];
                $end_speed02_edit = $_REQUEST['downgradeBrowsingUpType'];

                $endDownSpeed_limit1 = $_REQUEST['downgradeDownloadDownSpeed'];
                $endDownSpeedType1 = $_REQUEST['downgradeDownloadDownType'];
                $endDownSpeed_limit2 = $_REQUEST['downgradeDownloadUpSpeed'];
                $endDownSpeedType2 = $_REQUEST['downgradeDownloadUpType'];

                $endPriority = "8";

                // check if user enter download speed or system have ddwrt hardware 
                if ( $_REQUEST['downgradeDownloadDownSpeed'] != null && $_REQUEST['downgradeDownloadUpSpeed'] != null){ $speedType1=$endDownSpeedType1; $speedType2=$endDownSpeedType2; $upSpeedLimit=$endDownSpeed_limit2; $downSpeedLimit=$endDownSpeed_limit1; $replaceDownSpeedIntoAvarege=0;}
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
                else{ $downloadSpeedCode = $endDownSpeed_limit2 . $endDownSpeedType2 . '/' . $endDownSpeed_limit1 . $endDownSpeedType1; }
                // set final equation
                $browseingSpeedCode = $end_speed01_edit . $end_speed02_edit . '/' . $end_speed1_edit . $end_speed2_edit;
                $avarageSpeedCode = $avgUploadSpeed . 'K/' . $avgDownloadSpeed . 'K';
                $endSpeedSeconds = '30'; 
                $endSpeedLimitFullCode = $downloadSpeedCode . ' '. $browseingSpeedCode . ' ' .$avarageSpeedCode . ' ' . $endSpeedSeconds . ' ' . $endPriority;
                $endSpeed = $endSpeedLimitFullCode;
        }else{
                $ifDowngradeSpeed = 0;
                $endSpeed = "";
        }

        // update group data
        $quota_limit_total = $_REQUEST['dailyQuota'] * 1024 * 1024;
        DB::table($dbName.".area_groups")->where( 'name', 'Default' )->update([ 'speed_limit' => $startSpeed, 'end_speed' => $endSpeed, 'if_downgrade_speed' => $ifDowngradeSpeed, 'quota_limit_total' => $quota_limit_total ]);

        DB::statement("update $dbName.networks set `name` = '$dbName' where id = '2';");
        
        // insert record into customers
        // get country name by country code
        $countryArray = array(
                'AD'=>array('name'=>'ANDORRA','code'=>'376'),
                'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
                'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
                'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
                'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
                'AL'=>array('name'=>'ALBANIA','code'=>'355'),
                'AM'=>array('name'=>'ARMENIA','code'=>'374'),
                'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
                'AO'=>array('name'=>'ANGOLA','code'=>'244'),
                'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
                'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
                'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
                'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
                'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
                'AW'=>array('name'=>'ARUBA','code'=>'297'),
                'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
                'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
                'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
                'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
                'BE'=>array('name'=>'BELGIUM','code'=>'32'),
                'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
                'BG'=>array('name'=>'BULGARIA','code'=>'359'),
                'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
                'BI'=>array('name'=>'BURUNDI','code'=>'257'),
                'BJ'=>array('name'=>'BENIN','code'=>'229'),
                'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
                'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
                'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
                'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
                'BR'=>array('name'=>'BRAZIL','code'=>'55'),
                'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
                'BT'=>array('name'=>'BHUTAN','code'=>'975'),
                'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
                'BY'=>array('name'=>'BELARUS','code'=>'375'),
                'BZ'=>array('name'=>'BELIZE','code'=>'501'),
                'CA'=>array('name'=>'CANADA','code'=>'1'),
                'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
                'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
                'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
                'CG'=>array('name'=>'CONGO','code'=>'242'),
                'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
                'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
                'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
                'CL'=>array('name'=>'CHILE','code'=>'56'),
                'CM'=>array('name'=>'CAMEROON','code'=>'237'),
                'CN'=>array('name'=>'CHINA','code'=>'86'),
                'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
                'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
                'CU'=>array('name'=>'CUBA','code'=>'53'),
                'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
                'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
                'CY'=>array('name'=>'CYPRUS','code'=>'357'),
                'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
                'DE'=>array('name'=>'GERMANY','code'=>'49'),
                'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
                'DK'=>array('name'=>'DENMARK','code'=>'45'),
                'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
                'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
                'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
                'EC'=>array('name'=>'ECUADOR','code'=>'593'),
                'EE'=>array('name'=>'ESTONIA','code'=>'372'),
                'EG'=>array('name'=>'EGYPT','code'=>'2'),
                'ER'=>array('name'=>'ERITREA','code'=>'291'),
                'ES'=>array('name'=>'SPAIN','code'=>'34'),
                'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
                'FI'=>array('name'=>'FINLAND','code'=>'358'),
                'FJ'=>array('name'=>'FIJI','code'=>'679'),
                'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
                'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
                'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
                'FR'=>array('name'=>'FRANCE','code'=>'33'),
                'GA'=>array('name'=>'GABON','code'=>'241'),
                'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
                'GD'=>array('name'=>'GRENADA','code'=>'1473'),
                'GE'=>array('name'=>'GEORGIA','code'=>'995'),
                'GH'=>array('name'=>'GHANA','code'=>'233'),
                'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
                'GL'=>array('name'=>'GREENLAND','code'=>'299'),
                'GM'=>array('name'=>'GAMBIA','code'=>'220'),
                'GN'=>array('name'=>'GUINEA','code'=>'224'),
                'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
                'GR'=>array('name'=>'GREECE','code'=>'30'),
                'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
                'GU'=>array('name'=>'GUAM','code'=>'1671'),
                'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
                'GY'=>array('name'=>'GUYANA','code'=>'592'),
                'HK'=>array('name'=>'HONG KONG','code'=>'852'),
                'HN'=>array('name'=>'HONDURAS','code'=>'504'),
                'HR'=>array('name'=>'CROATIA','code'=>'385'),
                'HT'=>array('name'=>'HAITI','code'=>'509'),
                'HU'=>array('name'=>'HUNGARY','code'=>'36'),
                'ID'=>array('name'=>'INDONESIA','code'=>'62'),
                'IE'=>array('name'=>'IRELAND','code'=>'353'),
                'IL'=>array('name'=>'ISRAEL','code'=>'972'),
                'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
                'IN'=>array('name'=>'INDIA','code'=>'91'),
                'IQ'=>array('name'=>'IRAQ','code'=>'964'),
                'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
                'IS'=>array('name'=>'ICELAND','code'=>'354'),
                'IT'=>array('name'=>'ITALY','code'=>'39'),
                'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
                'JO'=>array('name'=>'JORDAN','code'=>'962'),
                'JP'=>array('name'=>'JAPAN','code'=>'81'),
                'KE'=>array('name'=>'KENYA','code'=>'254'),
                'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
                'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
                'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
                'KM'=>array('name'=>'COMOROS','code'=>'269'),
                'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
                'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
                'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
                'KW'=>array('name'=>'KUWAIT','code'=>'965'),
                'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
                'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
                'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
                'LB'=>array('name'=>'LEBANON','code'=>'961'),
                'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
                'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
                'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
                'LR'=>array('name'=>'LIBERIA','code'=>'231'),
                'LS'=>array('name'=>'LESOTHO','code'=>'266'),
                'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
                'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
                'LV'=>array('name'=>'LATVIA','code'=>'371'),
                'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
                'MA'=>array('name'=>'MOROCCO','code'=>'212'),
                'MC'=>array('name'=>'MONACO','code'=>'377'),
                'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
                'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
                'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
                'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
                'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
                'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
                'ML'=>array('name'=>'MALI','code'=>'223'),
                'MM'=>array('name'=>'MYANMAR','code'=>'95'),
                'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
                'MO'=>array('name'=>'MACAU','code'=>'853'),
                'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
                'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
                'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
                'MT'=>array('name'=>'MALTA','code'=>'356'),
                'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
                'MV'=>array('name'=>'MALDIVES','code'=>'960'),
                'MW'=>array('name'=>'MALAWI','code'=>'265'),
                'MX'=>array('name'=>'MEXICO','code'=>'52'),
                'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
                'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
                'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
                'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
                'NE'=>array('name'=>'NIGER','code'=>'227'),
                'NG'=>array('name'=>'NIGERIA','code'=>'234'),
                'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
                'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
                'NO'=>array('name'=>'NORWAY','code'=>'47'),
                'NP'=>array('name'=>'NEPAL','code'=>'977'),
                'NR'=>array('name'=>'NAURU','code'=>'674'),
                'NU'=>array('name'=>'NIUE','code'=>'683'),
                'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
                'OM'=>array('name'=>'OMAN','code'=>'968'),
                'PA'=>array('name'=>'PANAMA','code'=>'507'),
                'PE'=>array('name'=>'PERU','code'=>'51'),
                'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
                'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
                'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
                'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
                'PL'=>array('name'=>'POLAND','code'=>'48'),
                'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
                'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
                'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
                'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
                'PW'=>array('name'=>'PALAU','code'=>'680'),
                'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
                'QA'=>array('name'=>'QATAR','code'=>'974'),
                'RO'=>array('name'=>'ROMANIA','code'=>'40'),
                'RS'=>array('name'=>'SERBIA','code'=>'381'),
                'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
                'RW'=>array('name'=>'RWANDA','code'=>'250'),
                'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
                'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
                'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
                'SD'=>array('name'=>'SUDAN','code'=>'249'),
                'SE'=>array('name'=>'SWEDEN','code'=>'46'),
                'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
                'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
                'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
                'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
                'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
                'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
                'SN'=>array('name'=>'SENEGAL','code'=>'221'),
                'SO'=>array('name'=>'SOMALIA','code'=>'252'),
                'SR'=>array('name'=>'SURINAME','code'=>'597'),
                'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
                'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
                'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
                'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
                'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
                'TD'=>array('name'=>'CHAD','code'=>'235'),
                'TG'=>array('name'=>'TOGO','code'=>'228'),
                'TH'=>array('name'=>'THAILAND','code'=>'66'),
                'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
                'TK'=>array('name'=>'TOKELAU','code'=>'690'),
                'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
                'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
                'TN'=>array('name'=>'TUNISIA','code'=>'216'),
                'TO'=>array('name'=>'TONGA','code'=>'676'),
                'TR'=>array('name'=>'TURKEY','code'=>'90'),
                'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
                'TV'=>array('name'=>'TUVALU','code'=>'688'),
                'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
                'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
                'UA'=>array('name'=>'UKRAINE','code'=>'380'),
                'UG'=>array('name'=>'UGANDA','code'=>'256'),
                'US'=>array('name'=>'UNITED STATES','code'=>'1'),
                'UY'=>array('name'=>'URUGUAY','code'=>'598'),
                'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
                'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
                'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
                'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
                'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
                'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
                'VN'=>array('name'=>'VIET NAM','code'=>'84'),
                'VU'=>array('name'=>'VANUATU','code'=>'678'),
                'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
                'WS'=>array('name'=>'SAMOA','code'=>'685'),
                'XK'=>array('name'=>'KOSOVO','code'=>'381'),
                'YE'=>array('name'=>'YEMEN','code'=>'967'),
                'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
                'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
                'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
                'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
        );
        foreach($countryArray as $code => $country){
                if( $country['code'] == session('AccountkitCountryCode')[0] ){
                        $clientCountry = $country['name'];
                }
        }
        if(!isset($clientCountry)){$clientCountry = "";}
        // check user contry to show price 
        if( $clientCountry == "EGYPT" ){ $global="0"; $currency = "EGP"; }else{$global="1"; $currency = "USD";}
        // insert in customers DB
        $customerID = DB::table('customers')->insertGetId(['package_id' => $trialPackageID, 'url' => $fullDomain, 'admin_username' => session('install_mail')[0], 'admin_password' => session('install_password')[0], 'database' => $subdomain, 'password'=> session('install_uniqueRealm')[0], 'state'=> '1', 'name'=> $name, 'mail'=> $mail, 'phone'=> $mobile, 'start_date'=> $created_at, 'next_bill'=> $trialExpireDate, 'address'=> $clientCountry, 'currency'=> $currency, 'websites_log'=> "0", 'notes'=> "self registerd", 'can_buy'=>$priceingState, 'global' => $global, 'company_type'=>$businessType ]);

        DB::statement("update $dbName.settings set `value` = '$dbName' where type = 'app_name';");
        DB::statement("update $dbName.settings set `value` = '$mail' where type = 'email';");
        DB::statement("update $dbName.settings set `value` = '$mobile' where type = 'phone';");
        DB::statement("update $dbName.settings set `value` = '$clientCountry' where type = 'country';");
        DB::statement("update $dbName.settings set `value` = '$clientCountry' where type = 'address';");
        DB::statement("update $dbName.settings set `value` = '$currency' where type = 'currency';");
        DB::statement("update $dbName.settings set `value` = '$wifiMarketingState', `state` = '$wifiMarketingState' where type = 'marketing_enable';");
        DB::statement("update $dbName.settings set `value` = '1', `state` = '1' where type = 'commercial_enable';");
        DB::statement("update $dbName.settings set `value` = ".session('install_uniqueRealm')[0]." where type = 'customer_password';");
        DB::statement("update $dbName.settings set `value` = '$customerID' where type = 'customer_id';");

        if( $masterDomainWithoutDots == "mikrotik.com.eg" ){
            DB::table("$dbName.settings")->insert(['type' => 'copyright', 'state' => '1', 'value' => 'powered by <a target="_blank" href="http://mikrotik.com.eg">Mikrotik.com.eg</a>']);
            DB::statement("update $dbName.settings set `value` = 'mikrotik_orig_logo2018.png' where type = 'logo';");
        }
        
        // Auto insert new user with his mac address into system
        if( Session::has('install_client_mac') and session('install_client_mac')[0]!="" ){
                DB::table($dbName.".users")->insert(['Registration_type' => '2', 'u_state' => '1', 'suspend'=> '0', 'u_name'=> $name, 'u_uname'=> $mobile, 'u_password'=> session('install_password')[0], 'u_phone'=> $mobile, 'u_email'=> $mail, 'u_country'=> $clientCountry, 'u_mac'=> session('install_client_mac')[0], 'branch_id'=> '1', 'network_id'=> '2', 'group_id'=> '71', 'created_at'=> $created_at ]);
        }

        // update branch data
        DB::table($dbName.".branches")->where( 'id', '1' )->update([ 'name' => $dbName, 'block_downloading' => $_REQUEST['blockDownloading'], 'block_torrent_download' => $_REQUEST['blockTorrent'], 'adult_state' => $_REQUEST['adultProtection'], 'hacking_protection' => $_REQUEST['hackingProtection'], 'monthly_quota' => $_REQUEST['monthlyQuota'], 'start_quota' => $_REQUEST['monthlyQuotaRenewalDay'], 'load_balance_state' => $_REQUEST['loadBalanceingState'], 'last_check'=>$created_at, 'notes'=>$mikrotikType, 'phone'=>$mobile, 'address'=>$clientCountry ]);
        
        // insert serial into Microsystem DB
        if( Session::has('install_mikrotikType') and session('install_mikrotikType')[0]=="appliance" ){

                // delete any seial
                DB::table('microsystem.serials')->where('serial', $serial)->delete();
                // insert new serial with tenantID
                DB::table('microsystem.serials')->insert(['serial' => $serial, 'customer_id' => $customerID]);
                DB::table($dbName.".branches")->where( 'id', '1' )->update([ 'serial' => $serial ]);
        }       

        
        // insert record into CWP
        // DB::table('root_cwp.domains')->insert(['domain' => $fullDomain, 'user' => 'hotspot', 'path'=> '/home/hotspot/public_html', 'setup_time'=> $created_at]);
		// VERY IMPORTANT FOR CWP, MUST CREATE FILE IN "/usr/local/apache/conf.d/vhosts" WITH STATIC CONTENT AS DNS for each domain
		// VERY IMPORTANT FOR CPanel/WHM, Add content as DNS into "/etc/apache2/conf/httpd.conf" for each domain
		if( $webPanelType == "cwp" ){
			
			// domain DNS step
			//return Redirect::back();
			//return __DIR__;

			// change DNS folder and fils permession to 777
			exec('sudo chmod 777 /var/named');
			$chmod = "sudo chmod 777 /var/named/$masterDomainWithoutDots.db";
			exec($chmod);

			// for add data into file
			$file_path="/var/named/$masterDomainWithoutDots.db";
			$data="\n$subdomain     14400   IN      A       $masterIP	; #subdomain $subdomain \n";
			//$file=file_get_contents("$file_path","a+") or exit("Unable to open file!");
			$file=fopen("$file_path","a+") or exit("Unable to open file!");
			fwrite($file,$data);
			fclose($file);
			
			// Create Domain
			$data = array("key" => "$keyCode","action"=>'add',"type"=>'domain',"name"=>"$fullDomain","path"=>'/public_html',"autossl"=>'0',"user"=>'hotspot');
			$url = "https://$systemMasterIP:2304/v1/admindomains";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt ($ch, CURLOPT_POST, 1);
			$response = curl_exec($ch);
			curl_close($ch);
			
			// restart DNS
			$output = exec('sudo service named restart');
			//return "<pre>$output</pre>";
			
		}elseif( $webPanelType == "whm" ){
			
			$query = "https://$serverDomain:2083/json-api/cpanel?cpanel_jsonapi_func=park&cpanel_jsonapi_module=Park&cpanel_jsonapi_version=2&domain=$fullDomain&cpanel_jsonapi_user=$hotspotAccountName";

			$curl = curl_init();                                // Create Curl Object
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);       // Allow self-signed certs
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);       // Allow certs that do not match the hostname
			curl_setopt($curl, CURLOPT_HEADER,0);               // Do not include header in output
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);       // Return contents of transfer on curl_exec
			$header[0] = "Authorization: Basic " . base64_encode($hotspotAccountName.":".$hotspotPassword) . "\n\r";
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);    // set the username and password
			curl_setopt($curl, CURLOPT_URL, $query);            // execute the query
			$result = curl_exec($curl);
			curl_close($curl);
			sleep(10);
		}		
        
		

        
        return redirect('http://'.$fullDomain.'/login?email='.$mail);
        // return $request->input('domain');
    }

    public function validateVirificationCode(Request $request){
        // in case WhatsApp not working, we will enter this secret code to run
        if($request->verificationCode=="1403636"){
                if( Session::has('install_verificationCode') ){ Session::forget('install_verificationCode'); }
                Session::push('install_verificationCode', $request->verificationCode); 
        }
        if( ($request->verificationCode == session('install_verificationCode')[0]) or ($request->verificationCode=="1403636") ){
                return "1";
        }else{
                return "0";
        }
    }

}
