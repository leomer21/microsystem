<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use DB;
use Response;
use Redirect;
use Session;
use Log;
use Input;
use Image;
use Carbon\Carbon;
use App\Models\Visitors;
use Identify;
use Auth;
use Mail;

class LandingController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->visitors = new Visitors;
        $this->visitorInfo();
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        ///////////////////////////////////////////////////////////////////////////////////////////////
        ///      					  Check on primise license subscription      					///
        ///////////////////////////////////////////////////////////////////////////////////////////////
        /*
        // Just enable it when system installed on-premises
        // and make sure there is a record in `settings` table called 'lastCheck'
        date_default_timezone_set("Africa/Cairo");
        $todayDateTime = date("Y-m-d H:i:s");
        $last14days = date('Y-m-d H:i:s', strtotime($todayDateTime . ' -14 days'));
        if(App\Settings::where('type', 'lastCheck')->value('value') >= $last14days){
        }else{ App\Network::where('state',1)->update(['state' => '0']); }
        */
        ///////////////////////////////////////////////////////////////////////////////////////////////

        //Custom landing page & default landing page
        if (isset($template) && $template == 'default') {
            // check if facebook accountkit enabled so will redirect user to iframe to ignore refresh errors
            if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1){
                $subdomain = url()->full();
                $split = explode('/', $subdomain);

                // NOTE VERY IMPORTANT: WE disabled iframe because after we add multible domain in the same account in https://www.accountkit.com/ his system dose't support Iframe because it set 'X-Frame-Options' to 'deny' (26.4.2018)
                return view('front-end.landing.index', ['Identify' => session('Identify')]);
                // the reason to make Iframe idea because the user after reciving sms from accountkit then user clicked on login notification, mobile resubmit accountkit page so account kit give error and user can't back again "bad UX"
                // return redirect()->away("http://$split[2]/iframe");
            }else{
                // share URL attriputed from Mikrotik or any to forward this values to Home page after login to be able to replace internet.microsystem.com.eg with mikrotik ip address
                if(isset($_REQUEST['identify'])){Session::forget('identifyFromMikrotik'); Session::push('identifyFromMikrotik', $_REQUEST['identify']);}
                
                // share Mikrotik branch id
                if(isset($_REQUEST['identify']) and isset(explode('-', $_REQUEST['identify'])[2]) ){Session::push('mikrotikLocationID',  explode('-', $_REQUEST['identify'])[2] );}
                // Auto login by Mac from web
                if(isset($_REQUEST['identify']) and isset(explode('-', $_REQUEST['identify'])[5]) and isset(explode( ':', explode('-', $_REQUEST['identify'])[5] )[5]) and !Session::has('autoLoginByMacFromWeb') ){
                    
                    // check if settings record is created or not
                    $autoLoginByMacFromWebState = App\Settings::where('type', 'autoLoginByMacFromWeb')->where('value', explode('-', $_REQUEST['identify'])[2])->value('state');
                    if(isset($autoLoginByMacFromWebState)){
                        if($autoLoginByMacFromWebState == "1"){
                            $userMacFromMikrotik = explode('-', $_REQUEST['identify'])[5]; 
                            Session::push('autoLoginByMacFromWeb', $userMacFromMikrotik);
                            $getUserAndPassword=App\Users::where('u_mac', 'like', '%'.$userMacFromMikrotik.'%')->first();
                            if(isset($getUserAndPassword)){
                                $loginData = array('username' => $getUserAndPassword->u_uname, 'password' => $getUserAndPassword->u_password);
                                return $this->loginAuto($loginData);
                            }
                        }
                    }else{
                        // insert new autoLoginByMacFromWeb record
                        App\Settings::insert( ['type' => 'autoLoginByMacFromWeb', 'value' => explode('-', $_REQUEST['identify'])[2], 'state' => '0'] );
                    }

                }
                
                // if( Session::has('Identify') ){
                    
                // }

                if(Session::has('login')){
                    $result = Session::get('login');
                    return view('front-end.landing.home', ['result' => $result, 'status' => session()->get('status'), 'chargepackage' => session()->get('chargepackage'), 'packageid' => session()->get('packageid')]);
                }

                return view('front-end.landing.index', ['Identify' => session('Identify')]);
            }
            
        }else{
            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                // check if facebook accountkit enabled so will redirect user to iframe to ignore refresh errors
                // if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1){
                    // $subdomain = url()->full();
                    // $split = explode('/', $subdomain);
                    // return redirect()->away("http://$split[2]/iframe");
                // }else{
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'Identify' => session('Identify')]);
                // }
            }else{
                return view("errors.404");
            }
        }
    }
    // step 1 for facebook accountkit to redirect user to iframe to ignore refresh errors
    // the reason to make Iframe idea because the user after reciving sms from accountkit then user clicked on login notification, mobile resubmit accountkit page so account kit give error and user can't back again "bad UX"
    public function iframe()
    { 

        // $this->visitors = new Visitors;
        // $this->visitorInfo();
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        //Custom landing page & default landing page
        if (isset($template) && $template == 'default') {
            return view('front-end.landing.iframe', ['Identify' => session('Identify')]);
        }else{
            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                return view("front-end.custom-landing.$landing.iframe", ['id' => $landing, 'Identify' => session('Identify')]);
            }else{
                return view("errors.404");
            }
        }

    }
    // step 2 for facebook accountkit to redirect user to iframe to ignore refresh errors
    public function indexIframe()
    {
        
        // $this->visitors = new Visitors;
        // $this->visitorInfo();
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        if( isset($_GET['status']) && !Session::has('AccountkitFullMobile') ){

            if($_GET['status']=="PARTIALLY_AUTHENTICATED"){

                $facebook_app_id = App\Settings::where('type', 'Accountkitappid')->value('value');
                $app_secret = App\Settings::where('type', 'Accountkitappsecret')->value('value');
                $authorization_code = $_GET['code'];
                $stepA = json_decode(@file_get_contents("https://graph.accountkit.com/v1.2/access_token?grant_type=authorization_code&code=$authorization_code&access_token=AA|$facebook_app_id|$app_secret"));
                $gettedAccessToken = $stepA->access_token;

                $stepB = json_decode(@file_get_contents("https://graph.accountkit.com/v1.2/me/?access_token=$gettedAccessToken"));
                //print_r($stepB);
                $finalMobile = $stepB->phone->country_prefix.$stepB->phone->national_number;
                $mobileWithoutCountryCode = $stepB->phone->national_number;
                if($stepB->phone->country_prefix == "20"){ $mobileWithoutCountryCode = "0".$mobileWithoutCountryCode; }
                Session::push('AccountkitFullMobile', $finalMobile); 
                Session::push('mobileWithoutCountryCode', $mobileWithoutCountryCode); 
                                                            
            }
        }                                            
        
        // If number not in database                                       
        if(isset($finalMobile) and App\Users::where('u_phone', $finalMobile)->count() == 0){
            
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {
                return view('front-end.landing.index', ['signup_step2' => '1']);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'signup_step2' => '1']);
                }
            }
            // if merge accounts is enabled and getted mobile number from facebook account kit
         // mobile exist in database so we will login   
        }elseif (isset($finalMobile) and App\Users::where('u_phone', $finalMobile)->count() > 0){
            
            $getUserAndPassword=App\Users::where('u_phone', $finalMobile)->first();
            $loginData = array('username' => $getUserAndPassword->u_uname, 'password' => $getUserAndPassword->u_password);
            return $this->loginAuto($loginData);
            // user refresh page
        }else{
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {
                return view('front-end.landing.index', ['Identify' => session('Identify')]);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'Identify' => session('Identify')]);
                }else{
                    return view("errors.404");
                }
            }
        }

       

    }

    public function lang($lang = '')
    {
            $langs = ['ar','en'];
            if(in_array($lang,$langs)){
            Session::forget('lang');
            Session::push('lang', $lang); 
            return Redirect::back();
            }
    }

    // third and final step in login journey 
    public function account()
    {
        // if (session('login')) { 
        if(Session::has('login')){
            // $result = session('login');
            $result = Session::get('login');
            return view('front-end.landing.home', ['result' => $result, 'status' => session()->get('status'), 'chargepackage' => session()->get('chargepackage'), 'packageid' => session()->get('packageid')]);
        } else {
            return view('front-end.landing.index', ['errorMessage' => "Oops, missing session data 002, please login again."]); // trying to identify why screen refresh in login page 7/6/2022
            return Redirect::to('/');
        }
    }

    public function landing()
    {
        $permissions = app('App\Http\Controllers\DashboardController')->permissions();
        if ($permissions['landingpage'] == 1) {
            $landing_data = App\Media::where('type', Null)->get();
            return view('back-end.landing.index', ['data' => $landing_data]);
        } else {
            return view('errors.404');
        }
    }

    // first step in user login journey
    public function login(Request $request)
    {
        date_default_timezone_set("Africa/Cairo");
        $ChatGPT = new App\Http\Controllers\Integrations\ChatGPT();
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');
        if ($username = strtolower($request->input('username'))) {

            // check if password not required in login process
            if(App\Settings::where('type', 'getPassword')->value('state')==1){ $password = $request->input('password'); }
            else{ $password = $request->input('username'); }
            

            $userdata = App\Users::where('u_uname', $username)->where('u_password', $password)->first();
            if (isset($userdata)) {
                $emailVerificationSwitchRoomType = App\Settings::where('type', 'emailVerificationSwitchRoomTypeForLogin')->value('state');
                $email = $request->input('emailForPms');
                if (isset($emailVerificationSwitchRoomType) && $emailVerificationSwitchRoomType == '1') {
                    $emailVerificationSwitchToGroupId = App\Settings::where('type', 'emailVerificationSwitchToGroupIdForLogin')->value('value');
                    $notes = $userdata->notes;
                    if (strpos($notes, 'Room Type:') !== false) {
                    $userRoomType = @explode(",",end(preg_split('/Room Type: /', $userData4PMS->notes)))[0];
                    if(isset($userRoomType) ){
                        $groupId  = App\Groups::where('name', $userRoomType)->value('id');
                        if(isset($userRoomType) ){
                        $emailVerificationSwitchToGroupId = $groupId;
                        }
                    }
                    }
                    if($emailVerificationSwitchToGroupId != $userdata->group_id){
                        $ChatGPT->sendEmailVerifyUsingChatGptWithoutWaiting($userdata->u_id,'login',$request->input('emailForPms'),$userdata->u_name,$userdata->u_country);

                        // if (!filter_var($request->input('emailForPms'), FILTER_VALIDATE_EMAIL)) {
                        // }
                        // else{
                        //     $email = explode(",", $userdata->u_email);
                        //     $ChatGPT->sendEmailVerifyUsingChatGptWithoutWaiting($userdata->u_id,'login',$request->input('emailForPms'),$userdata->u_name,$userdata->u_country);

                        // }

                    }
                }
                
        $user_id = $userdata->u_id;
                $agilecrm_id = $userdata->agilecrm_id;
                $branch_id = $userdata->branch_id;
                $group_id = $userdata->group_id;
                $network_id = $userdata->network_id;
                $u_state = $userdata->u_state;
                $u_name = $userdata->u_name;
                $facebook_id = $userdata->facebook_id;
                $twitter_id = $userdata->twitter_id;
                $twitter_pic = $userdata->twitter_pic;
                $google_id = $userdata->google_id;
                $linkedin_id = $userdata->linkedin_id;
                $selfrules = $userdata->Selfrules;
                $registration_type = $userdata->Registration_type;
                $suspend = $userdata->suspend;
                $u_email = $userdata->u_email;
                $u_phone = $userdata->u_phone;
                $linkedin_pic = $userdata->linkedin_pic;
                $google_pic = $userdata->google_pic;

                if ($userdata->u_gender == 1) {
                    $gender = "Male";
                } elseif ($userdata->u_gender == 0) {
                    $gender = "Female";
                } else {
                    $gender = "Unknown";
                }

                //case 5; //check registration type ( Waiting admin confirm, waiting sms confirm or accepted )
                if ($registration_type == "2") {
                    $canLogin = 1;
                } elseif ($registration_type == "0") {
                    $errorMessage = "Hmm, your account waiting SMS confirmation please fill it.";
                    $smsConfirm = "1";
                    $u_id = $user_id;

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage, 'smsConfirm' => $smsConfirm, 'u_id' => $u_id]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage, 'smsConfirm' => $smsConfirm, 'u_id' => $u_id]);
                        }
                    }
                } elseif ($registration_type == "1") {
                    $errorMessage = "Hmm, your account still waiting confirmation, Please contact system administrator. ".App\Settings::where('type','phone')->value('value');
                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                } else {
                    $errorMessage = "Error, your account still waiting confirmation, Please contact system administrator.";
                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }

                //Network Data
                if ($getNetwork_data = App\Network::find($network_id)) {
                    $network_name = $getNetwork_data->name;
                    $network_state = $getNetwork_data->state;
                    $open_system = $getNetwork_data->commercial;
                }// 0:commercial // 1:free // 2  free + commercial
                else {
                    $errorMessage = "Error, your network is not set, please contact system administrator.";
                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }

                //Branch Data
                if ($getBranch_data = App\Branches::find($branch_id)) {
                    $branch_state = $getBranch_data->state;
                    $url = $getBranch_data->url;
                } else {
                    $errorMessage = "Error, your branch is not set, Please contact system administrator.";
                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }

                // case 1 // check Network state
                if ($network_state == 1) {
                    $canLogin = 1;
                } else {
                    $errorMessage = "Hmm, your network is inactive, please contact system administrator.";
                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }

                // case 3 // check Branch state
                if ($branch_state == 1) {
                    $canLogin = 1;
                } else {
                    $errorMessage = "Hmm, your branch is inactive, please contact system administrator.";

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }                
                }

                //case 4: //check user state
                if ($u_state == "1") {
                    $canLogin = 1;
                } else {
                    $errorMessage = "Hmm, your account still inactive, please contact system administrator.";

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }                
                }

                // case 6 //check account suspension
                if ($suspend == 0) {
                    $canLogin = 1;
                } else {
                    $errorMessage = "Hmm, your account has been suspended, please contact system administrator.";

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }


                if ($open_system != 1) {// Commercial or free+commercial
                    $canLogin = 1;
                } else {// Free Internet
                    //Group Data
                    if ($getGroup_data = App\Groups::find($group_id)) {
                        $group_is_active = $getGroup_data->is_active;
                        $group_radius_type = $getGroup_data->radius_type;
                        $group_renew = $getGroup_data->renew;
                        $group_url_redirect = $getGroup_data->url_redirect;
                        $group_url_redirect_Interval = $getGroup_data->url_redirect_Interval;
                        $group_session_time = $getGroup_data->session_time;
                        $group_port_limit = $getGroup_data->port_limit;
                        $group_idle_timeout = $getGroup_data->idle_timeout;
                        $group_quota_limit_upload = $getGroup_data->quota_limit_upload;
                        $group_quota_limit_download = $getGroup_data->quota_limit_download;
                        $group_quota_limit_total = $getGroup_data->quota_limit_total;
                        $group_speed_limit = $getGroup_data->speed_limit;
                        $group_if_downgrade_speed = $getGroup_data->if_downgrade_speed;
                        $group_end_speed = $getGroup_data->end_speed;
                    } else {//group id didnt set yet ( account didnt approved yet or group ip deleted)
                        $errorMessage = "Hmm, your account still waiting confirmation, Please contact system administrator. ".App\Settings::where('type','phone')->value('value');

                        //Custom landing page & default landing page
                        if (isset($template) && $template == 'default') {
                            return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                        }else{
                            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                            }
                        }
                    }

                    //get system ID
                    //$systemID=App\Settings::where('type', 'systemID')->value('value');

                    // Delete all related user records in `radreply` to clr any previous data
                    $database = app('App\Http\Controllers\Controller')->configuration();
                    DB::table($database . '.radreply')->where('username', '=', $username)->delete();
                    DB::table($database . '.radgroupcheck')->where('groupname', '=', $username)->delete();
                    DB::table($database . '.radusergroup')->where('username', '=', $username)->delete();
                    DB::table($database . '.radcheck')->where('username', '=', $username)->delete();

                    // Get Today info
                    $today = date("Y-m-d");
                    $today_time = date("g:i a");


                    // case 2 // check Group state
                    if ($group_is_active == 1) {
                        $canLogin = 1;
                    } else {
                        $errorMessage = "Hmm, your group is inactive, please contact system administrator.";

                        //Custom landing page & default landing page
                        if (isset($template) && $template == 'default') {
                            return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                        }else{
                            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                            }
                        }
                    }


                    //get today user usage ( upload + download + session time )
                    $TodayUpload = App\Radacct::where('u_id', $user_id)->where('acctstarttime', '>=', $today)->sum('acctinputoctets') + 0;
                    $TodayDownload = App\Radacct::where('u_id', $user_id)->where('acctstarttime', '>=', $today)->sum('acctoutputoctets') + 0;
                    $TodaySessionTime = App\Radacct::where('u_id', $user_id)->where('acctstarttime', '>=', $today)->sum('acctsessiontime') + 0;

                    // case 7 // check session time
                    if (isset($group_session_time) and $group_session_time != 0 and isset($TodaySessionTime) and $TodaySessionTime > 0 and $group_if_downgrade_speed != "1") {
                        // Split the time string into hours, minutes, and seconds
                        list($hours, $minutes, $seconds) = explode(":", $group_session_time);

                        // Convert the hours, minutes, and seconds into seconds
                        $group_session_time = $hours * 3600 + $minutes * 60 + $seconds;
                        
                        $group_session_time = $group_session_time - $TodaySessionTime;
                        if ($group_session_time <= 0) {
                            $errorMessage = "Hmm, your session time has been finished, upgrade your package or contact system administrator.";
                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                                }
                            }
                        }// Reject Reason: session time ended
                        else {
                            $canLogin = 1;
                        }//because user still have more time
                    } else {
                        $canLogin = 1;
                    }//because user didn't have session limit

                    // case 8 // check quota upload
                    if (isset($group_quota_limit_upload) and $group_quota_limit_upload != 0 and $group_if_downgrade_speed != "1") {
                        $group_quota_limit_upload = $group_quota_limit_upload - $TodayUpload;
                        if ($group_quota_limit_upload <= 0) {
                            $errorMessage = "Hmm, your upload quota has been finished, upgrade your package or contact system administrator.";

                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                                }
                            }
                        } // Reject Reason : quota upload ended
                        else {
                            $canLogin = 1;
                        }//because user still have more upload quota
                    } else {
                        $canLogin = 1;
                    }//because user still have more upload quota

                    // case 9 // check quota Download
                    if (isset($group_quota_limit_download) and $group_quota_limit_download != 0 and $group_if_downgrade_speed != "1") {
                        $group_quota_limit_download = $group_quota_limit_download - $TodayDownload;
                        if ($group_quota_limit_download <= 0) {
                            $errorMessage = "Hmm, your Download quota has been finished, upgrade your package or contact system administrator.";

                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                                }
                            }
                        } // Reject Reason : quota Download ended
                        else {
                            $canLogin = 1;
                        }//because user still have more Download quota
                    } else {
                        $canLogin = 1;
                    }//because user still have more Download quota

                    // case 10 // check Total quota Download+Upload
                    if (isset($group_quota_limit_total) and $group_quota_limit_total != 0 and $group_if_downgrade_speed != "1") {
                        $group_quota_limit_total = $group_quota_limit_total - ($TodayDownload + $TodayUpload);
                        if ($group_quota_limit_total <= 0) {
                            $errorMessage = "Hmm, your quota has been finished, upgrade your package or contact system administrator.";

                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                                }
                            }
                        } // Reject Reason : quota Total ended
                        else {
                            $canLogin = 1;
                        }//because user still have more quota
                    } else {
                        $canLogin = 1;
                    }//because user still have more quota
                }


                //successfully login
                if ($canLogin == 1 and !isset($errorMessage)) // successfully login
                {
                    //token update
                    $token = rand(1, 9999) . chr(rand(65, 90)) . rand(1111, 5555) . chr(rand(65, 90)) . rand(2222, 6666) . chr(rand(65, 90)) . rand(3333, 7777) . rand(4444, 8888) . rand(5555, 9999) . chr(rand(65, 90));
                    App\Users::where('u_id', $user_id)->update(['token' => $token]);

                    // check if the PMS integration is on and save mobile numner is on
                    if(App\Settings::where('type', 'pms_integration')->value('state')==1 && App\Settings::where('type', 'pms_save_mobile_from_login_page')->value('state')==1){
                        if($request->input('MobileNumberForPms')!=""){
                        App\Users::where('u_id', $user_id)->update(['u_phone' => $request->input('countryCodeForPms').$request->input('MobileNumberForPms')]);  
                        }
                    }

                    // check if the PMS integration is on and save email is on
                    if(App\Settings::where('type', 'pms_integration')->value('state')==1 && App\Settings::where('type', 'pms_save_email_from_login_page')->value('state')==1){
                        if( $request->input('emailForPms')!=""){
                            $emails = App\Users::where('u_id', $user_id)->first();
                            App\Users::where('u_id', $user_id)->update(['u_email' => $ChatGPT->addEmailIfNotExist($request->input('emailForPms'),$emails->u_email)]);  
                        }
                    }

                    // // Add Agile CRM score
                    // include("agilecrm.php");
                    // $contact_json = array(
                    //     "id" => $agilecrm_id, //It is mandatory field. Id of contact
                    //     "lead_score" => "1"
                    // );
                    // $contact_json = json_encode($contact_json);
                    // curl_wrap("contacts/edit/lead-score", $contact_json, "PUT", "application/json");

                    // check if this ticket/user will expire after * days, by checking group expiration days, by checking if this is the first login by checking last_login_manual field
                    if($userdata->last_login_manual == null){
                        // so this is the first time login for this user, so we will check if the sstem in free internet mode, then check if the group contains expire_users_after_days value
                        if ($open_system == 1) {// Free Internet
                            // check if the group contains expire_users_after_days value
                            if($getGroup_data->expire_users_after_days > 0){
                                // adding this days to the today then update `time_package_expiry` field in `users` table, to be able to delete user/ticket after txpiration
                                $currentDateTime = Carbon::now();
                                $newDateTime = $currentDateTime->addDays($getGroup_data->expire_users_after_days);
                                App\Users::where('u_id', $user_id)->update(['time_package_expiry' => $newDateTime]); 
                            }
                        }       
                    }

                    //redirect and start session
                    $login_url = "<iframe src=\"$url/login?username=$username&password=$token\" style=\"display:none;\"></iframe>";
                    $result = array('id' => $user_id, 'branch_id' => $branch_id, 'url' => $login_url, 'facebook_id' => $facebook_id, 'twitter_id' => $twitter_id, 'twitter_pic' => $twitter_pic, 'linkedin_id' => $linkedin_id, 'username' => $username, 'name' => $u_name, 'gender' => $gender, 'network_name' => $network_name, 'group_id' => $group_id, 'network_id' => $network_id, 'email' => $u_email, 'phone' => $u_phone, 'linkedin_pic' => $linkedin_pic, 'google_pic' => $google_pic, 'pms_id' => $userdata->pms_id);
                    // $request->session()->push('login', $result); 
                    Session::push('login', $result);
                }

            } else {
                //return redirect()->route('/')->with('message', 'Invalid username/password combination');
                $errorMessage = "Hmm, Wrong username or password, Please try again.";
                //$request->session()->push('message', 'Invalid username/password combination!');
                //$message = session('message');
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
                //return redirect()->to('/')->with(['message',$message]);
            }

            //\Carbon\Carbon::now();
            //return $user_id;
            return redirect()->route('campaign_offline');

            //return view('front-end.landing.home',array('id' => $user_id , 'branch_id' => $branch_id , 'url' => $login_url));
        } else {//user click login button after registration ( on state : waiting admin confirm )
            $loginAfterWaitingAdminConfirm = "1";
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {
                return view('front-end.landing.index', ['loginAfterWaitingAdminConfirm' => $loginAfterWaitingAdminConfirm]);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'loginAfterWaitingAdminConfirm' => $loginAfterWaitingAdminConfirm]);
                }
            }
            
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function loginAuto($userdata)
    {
        date_default_timezone_set("Africa/Cairo");
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');
        //return $userdata;
        $username = strtolower($userdata['username']);

        // check if password not required in login process
        if(App\Settings::where('type', 'getPassword')->value('state')==1){ $password = $userdata['password']; }
        else{ $password = $userdata['password']; }

        
        if (isset($userdata['social'])) {
            $social = $userdata['social'];
        }
        // $password = "1119684871403588";
        // $username = "personal3m@yahoo.com";
        // $social="facebook_id";
        if (isset($social)) {
            $password_fild = $social;
        } else {
            $password_fild = "u_password";
        }
		
        $userdata = App\Users::where('u_uname', $username)->where($password_fild, $password)->first();

        //$userdata = App\Users::where('u_uname',$username)->where('u_password' , $password)->first();
        if (isset($userdata)) {

            //Users Data
            $user_id = $userdata->u_id;
            $agilecrm_id = $userdata->agilecrm_id;
            $branch_id = $userdata->branch_id;
            $group_id = $userdata->group_id;
            $network_id = $userdata->network_id;
            $u_state = $userdata->u_state;
            $u_name = $userdata->u_name;
            $facebook_id = $userdata->facebook_id;
            $twitter_id = $userdata->twitter_id;
            $twitter_pic = $userdata->twitter_pic;
            $google_id = $userdata->google_id;
            $linkedin_id = $userdata->linkedin_id;
            $u_email = $userdata->u_email;
            $u_phone = $userdata->u_phone;
            $linkedin_pic = $userdata->linkedin_pic;
            $google_pic = $userdata->google_pic;

            $selfrules = $userdata->Selfrules;
            $registration_type = $userdata->Registration_type;
            $suspend = $userdata->suspend;
            if ($userdata->u_gender == 1) {
                $gender = "Male";
            } elseif ($userdata->u_gender == 0) {
                $gender = "Female";
            } else {
                $gender = "Unknown";
            }
			
            //case 5; //check registration type ( Waiting admin confirm, waiting sms confirm or accepted )
            if ($registration_type == "2") {
                $canLogin = 1;
            } elseif ($registration_type == "0") {
                $errorMessage = "Hmm, your account waiting SMS confirmation please fill it.";
                $smsConfirm = "1";
                $u_id = $user_id;

                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage, 'smsConfirm' => $smsConfirm, 'u_id' => $u_id]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage, 'smsConfirm' => $smsConfirm, 'u_id' => $u_id]);
                    }
                }

                

            } elseif ($registration_type == "1") {
                $errorMessage = "Hmm, your account still waiting confirmation, Please contact system administrator. ".App\Settings::where('type','phone')->value('value');

                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            } else {
                $errorMessage = "Error, your account still waiting confirmation, Please contact system administrator.";

                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }
			
            //Network Data
            if ($getNetwork_data = App\Network::find($network_id)) {
                $network_name = $getNetwork_data->name;
                $network_state = $getNetwork_data->state;
                $open_system = $getNetwork_data->commercial;
            }// 0:commercial // 1:free // 2  free + commercial
            else {
                $errorMessage = "Error, your network is not set, please contact system administrator.";

                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }

            //Branch Data
			//return $branch_id;
			if(App\Branches::find($branch_id)){
				$getBranch_data=App\Branches::find($branch_id);
				$branch_state = $getBranch_data->state;
                $url = $getBranch_data->url;
			}elseif(App\Branches::where('state','1')){
				$getBranch_data=App\Branches::where('state','1')->orderBy('id','asc')->first();
				$branch_state = $getBranch_data->state;
                $url = $getBranch_data->url;
			}else{
				
                $errorMessage = "Error, your branch is not set, Please contact system administrator.";
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }

            // case 1 // check Network state
            if ($network_state == 1) {
                $canLogin = 1;
            } else {
                $errorMessage = "Hmm, your network is inactive, please contact system administrator.";

                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }

            // case 3 // check Branch state
            if ($branch_state == 1) {
                $canLogin = 1;
            } else {
                $errorMessage = "Hmm, your branch is inactive, please contact system administrator.";
   
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }

            //case 4: //check user state
            if ($u_state == "1") {
                $canLogin = 1;
            } else {
                $errorMessage = "Hmm, your account still inactive, please contact system administrator.";
                
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }


            // case 6 //check account suspension
            if ($suspend == 0) {
                $canLogin = 1;
            } else {
                $errorMessage = "Hmm, your account has been suspended, please contact system administrator.";

                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }

            if ($open_system != 1) {// Commercial or free+commercial
                $canLogin = 1;
            } else {// Free Internet
                //Group Data
                if ($getGroup_data = App\Groups::find($group_id)) {
                    $group_is_active = $getGroup_data->is_active;
                    $group_radius_type = $getGroup_data->radius_type;
                    $group_renew = $getGroup_data->renew;
                    $group_url_redirect = $getGroup_data->url_redirect;
                    $group_url_redirect_Interval = $getGroup_data->url_redirect_Interval;
                    $group_session_time = $getGroup_data->session_time;
                    $group_port_limit = $getGroup_data->port_limit;
                    $group_idle_timeout = $getGroup_data->idle_timeout;
                    $group_quota_limit_upload = $getGroup_data->quota_limit_upload;
                    $group_quota_limit_download = $getGroup_data->quota_limit_download;
                    $group_quota_limit_total = $getGroup_data->quota_limit_total;
                    $group_speed_limit = $getGroup_data->speed_limit;
                    $group_if_downgrade_speed = $getGroup_data->if_downgrade_speed;
                    $group_end_speed = $getGroup_data->end_speed;
                } else {//group id didnt set yet ( account didnt approved yet or group ip deleted)
                    $errorMessage = "Hmm, your account still waiting confirmation, Please contact system administrator. ".App\Settings::where('type','phone')->value('value');

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }

                //get system ID
                //$systemID=App\Settings::where('type', 'systemID')->value('value');

                // Delete all related user records in `radreply` to clr any previous data
                $database = app('App\Http\Controllers\Controller')->configuration();
                DB::table($database . '.radreply')->where('username', '=', $username)->delete();
                DB::table($database . '.radgroupcheck')->where('groupname', '=', $username)->delete();
                DB::table($database . '.radusergroup')->where('username', '=', $username)->delete();
                DB::table($database . '.radcheck')->where('username', '=', $username)->delete();

                // Get Today info
                $today = date("Y-m-d");
                $today_time = date("g:i a");


                // case 2 // check Group state
                if ($group_is_active == 1) {
                    $canLogin = 1;
                } else {
                    $errorMessage = "Hmm, your group is inactive, please contact system administrator.";

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }


                //get today user usage ( upload + download + session time )
                $TodayUpload = App\Radacct::where('u_id', $user_id)->where('acctstarttime', '>=', $today)->sum('acctinputoctets') + 0;
                $TodayDownload = App\Radacct::where('u_id', $user_id)->where('acctstarttime', '>=', $today)->sum('acctoutputoctets') + 0;
                $TodaySessionTime = App\Radacct::where('u_id', $user_id)->where('acctstarttime', '>=', $today)->sum('acctsessiontime') + 0;

                // case 7 // check session time
                if (isset($group_session_time) and $group_session_time != 0 and isset($TodaySessionTime) and $TodaySessionTime > 0 and $group_if_downgrade_speed != "1") {
                    $group_session_time = $group_session_time - $TodaySessionTime;
                    if ($group_session_time <= 0) {
                        $errorMessage = "Hmm, your session time has been finished, upgrade your package or contact system administrator.";
                        //Custom landing page & default landing page
                        if (isset($template) && $template == 'default') {
                            return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                        }else{
                            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                            }
                        }
                    }// Reject Reason: session time ended
                    else {
                        $canLogin = 1;
                    }//because user still have more time
                } else {
                    $canLogin = 1;
                }//because user didn't have session limit

                // case 8 // check quota upload
                if (isset($group_quota_limit_upload) and $group_quota_limit_upload != 0 and $group_if_downgrade_speed != "1") {
                    $group_quota_limit_upload = $group_quota_limit_upload - $TodayUpload;
                    if ($group_quota_limit_upload <= 0) {
                        $errorMessage = "Hmm, your upload quota has been finished, upgrade your package or contact system administrator.";

                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                                }
                            }
                    } // Reject Reason : quota upload ended
                    else {
                        $canLogin = 1;
                    }//because user still have more upload quota
                } else {
                    $canLogin = 1;
                }//because user still have more upload quota

                // case 9 // check quota Download
                if (isset($group_quota_limit_download) and $group_quota_limit_download != 0 and $group_if_downgrade_speed != "1") {
                    $group_quota_limit_download = $group_quota_limit_download - $TodayDownload;
                    if ($group_quota_limit_download <= 0) {
                        $errorMessage = "Hmm, your Download quota has been finished, upgrade your package or contact system administrator.";

                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                                }
                            }
                    } // Reject Reason : quota Download ended
                    else {
                        $canLogin = 1;
                    }//because user still have more Download quota
                } else {
                    $canLogin = 1;
                }//because user still have more Download quota

                // case 10 // check Total quota Download+Upload
                if (isset($group_quota_limit_total) and $group_quota_limit_total != 0 and $group_if_downgrade_speed != "1") {
                    $group_quota_limit_total = $group_quota_limit_total - ($TodayDownload + $TodayUpload);
                    if ($group_quota_limit_total <= 0) {
                        $errorMessage = "Hmm, your quota has been finished, upgrade your package or contact system administrator.";

                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                                }
                            }
                    } // Reject Reason : quota Total ended
                    else {
                        $canLogin = 1;
                    }//because user still have more quota
                } else {
                    $canLogin = 1;
                }//because user still have more quota
            }


            //successfully login
            if ($canLogin == 1 and !isset($errorMessage)) // successfully login
            {
                //token update
                $token = rand(1, 9999) . chr(rand(65, 90)) . rand(1111, 5555) . chr(rand(65, 90)) . rand(2222, 6666) . chr(rand(65, 90)) . rand(3333, 7777) . rand(4444, 8888) . rand(5555, 9999) . chr(rand(65, 90));
                App\Users::where('u_id', $user_id)->update(['token' => $token]);

                // // Add Agile CRM score
                // include("agilecrm.php");
                // $contact_json = array(
                //     "id" => $agilecrm_id, //It is mandatory field. Id of contact
                //     "lead_score" => "1"
                // );
                // $contact_json = json_encode($contact_json);
                // curl_wrap("contacts/edit/lead-score", $contact_json, "PUT", "application/json");

                // check if this ticket/user will expire after * days, by checking group expiration days, by checking if this is the first login by checking last_login_manual field
                if($userdata->last_login_manual == null){
                    // so this is the first time login for this user, so we will check if the sstem in free internet mode, then check if the group contains expire_users_after_days value
                    if ($open_system == 1) {// Free Internet
                        // check if the group contains expire_users_after_days value
                        if($getGroup_data->expire_users_after_days > 0){
                            // adding this days to the today then update `time_package_expiry` field in `users` table, to be able to delete user/ticket after txpiration
                            $currentDateTime = Carbon::now();
                            $newDateTime = $currentDateTime->addDays($getGroup_data->expire_users_after_days);
                            App\Users::where('u_id', $user_id)->update(['time_package_expiry' => $newDateTime]); 
                        }
                    }       
                }
                
                //redirect and start session 
                $login_url = "<iframe src=\"$url/login?username=$username&password=$token\" style=\"display:none;\"></iframe>";
                $result = array('id' => $user_id, 'branch_id' => $branch_id, 'url' => $login_url, 'facebook_id' => $facebook_id, 'twitter_id' => $twitter_id, 'twitter_pic' => $twitter_pic, 'linkedin_id' => $linkedin_id, 'username' => $username, 'name' => $u_name, 'gender' => $gender, 'network_name' => $network_name, 'group_id' => $group_id, 'network_id' => $network_id, 'email' => $u_email, 'phone' => $u_phone, 'linkedin_pic' => $linkedin_pic, 'google_pic' => $google_pic, 'pms_id' => $userdata->pms_id);
                //$request->session()->push('login', $result);
                Session::push('login', $result);
            }


        } else {
            //return redirect()->route('/')->with('message', 'Invalid username/password combination');
            $errorMessage = "Hmm, Wrong username or password, Please try again.";
            //$request->session()->push('message', 'Invalid username/password combination!');
            //$message = session('message');

                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            //return redirect()->to('/')->with(['message',$message]);
        }
        return redirect()->route('campaign_offline');

        //return view('front-end.landing.home',array('id' => $user_id , 'branch_id' => $branch_id , 'url' => $login_url));
    }

    public function disconnect(Request $request)
    { // user clicked disconnect online session from home page

        $userID = $request->input('u_id');
        $sessionID = $request->input('session_id');

        //User Data
        $getUserData = App\Radacct::where('u_id', $userID)->where('radacctid', $sessionID)->first();
        if (isset($getUserData)) {
            $radacct_id = $getUserData->radacctid;
            $geted_User_Name = $getUserData->username;
            $geted_Framed_IP_Address = $getUserData->framedipaddress;
            $geted_nasipaddress = $getUserData->nasipaddress;
            $geted_branchID = $getUserData->branch_id;
            $geted_mac = $getUserData->callingstationid;

            //Branch Data
            $getbranchdata = App\Branches::where('id', $geted_branchID)->first();
            $geted_secret = $getbranchdata->Radiussecret;
            $coaport = $getbranchdata->Radiusport;
            $systemIP = $getbranchdata->ip;

            App\Radacct::where('radacctid',$radacct_id)->update(['realm'=>'1']);

            // disconnect user
            // $beExecuted = 'echo User-Name=' . $geted_User_Name . ',Framed-IP-Address=' . $geted_Framed_IP_Address . ' | radclient -x ' . $systemIP . ':' . $coaport . ' disconnect ' . $geted_secret . '  2>&1 > /dev/null 2>/dev/null &';
            // exec($beExecuted);

            // if user clicked delete
            $delete = $request->input('delete');
            if (isset($delete) and $delete == "delete") {
                $getAllMac = App\Users::where('u_id', $userID)->value('u_mac');
                $AllMac = explode(',', $getAllMac);
                if (isset($AllMac[0]) and $AllMac[0] != "" and !isset($AllMac[1])) {// just one mac so we will remove it
                    App\Users::where('u_id', $userID)->update(['u_mac' => ""]);
                } else {
                    // user have many mac address
                    $addNewMac = "";
                    $countMacForUsers = count($AllMac);
                    for ($i = 0; $i < $countMacForUsers; $i++) {
                        if ($addNewMac and $addNewMac != "" and $AllMac[$i] != $geted_mac) {
                            $addNewMac .= ",";
                        }// just add comma for separation
                        if ($AllMac[$i] == $geted_mac) {
                        }// skip first mac
                        else {
                            $addNewMac .= $AllMac[$i];
                        }
                    }
                    App\Users::where('u_id', $userID)->update(['u_mac' => $addNewMac]);
                }
            }
            return redirect('/account');
        }
    }

    public function logout(Request $request)
    {
        if (session('login')) {
            $id = $request->input('u_id');
            //User Data
            $getUserData = App\Radacct::whereNull('acctstoptime')->where('u_id', $id)->orderBy('radacctid', 'desc')->first();
            if (isset($getUserData)) {
                $radacct_id = $getUserData->radacctid;
                $geted_User_Name = $getUserData->username;
                $geted_Framed_IP_Address = $getUserData->framedipaddress;
                $geted_nasipaddress = $getUserData->nasipaddress;
                $geted_branchID = $getUserData->branch_id;

                //Branch Data
                $getbranchdata = App\Branches::where('id', $geted_branchID)->first();

                $geted_secret = $getbranchdata->Radiussecret;
                $coaport = $getbranchdata->Radiusport;
                App\Radacct::where('radacctid',$radacct_id)->update(['realm'=>'1']); 
                // disconnect user
                // $beExecuted = 'echo User-Name=' . $geted_User_Name . ',Framed-IP-Address=' . $geted_Framed_IP_Address . ' | radclient -x ' . $geted_nasipaddress . ':' . $coaport . ' disconnect ' . $geted_secret . '  2>&1 > /dev/null 2>/dev/null &';
                // exec($beExecuted);
                Session::flush();
                return redirect('/');
            }
            if (session('Identify')) {
                $identify = session('Identify');
            }
            Session::flush();
            if (isset($identify)) {
                Session::push('Identify', $identify[0]);
            }
            return redirect('/');
        } else {
            return redirect('/');
        }
    }
    
    // user send message
    public function contact(Request $request)
    {
        if(!isset($request['message']) or $request['message']=="" or $request['message']==" "){
            return redirect('http://google.com');
        }else{
            $result = session('login');
            $contact = new App\Messages();
            $contact->u_id = $result[0]['id'];
            $contact->name = $request['name'];
            $contact->email = $request['email'];
            $contact->phone = $request['phone'];
            $contact->message = $request['message'];
            $contact->state = 0;
            $contact->save();

            return redirect()->route('account');
        }
    }


    public function signup(Request $request)
    {
        $ChatGPT = new App\Http\Controllers\Integrations\ChatGPT();
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');
        // Getting all post data
        $signup = new App\Users();
        
        // check if admin getiing CardSerialInSignupTab, thats mean we will genetare random mobile number and name, then push serial number in session to pass this card to the home page for auth charge function
        if(App\Settings::where('type', 'getCardSerialInSignupTab')->value('state') ==1){
            if(isset($request['cardSerial'])){
                // check if cardserial is already exist or not
                if(App\Cards::where('number', $request['cardSerial'])->count()>0){     
                    $request['phone'] = $request['cardSerial'];
                    Session::push('cardSerial', $request['cardSerial']);
                }else{
                    // card not exist
                    $errorMessage = "Oops, Wrong card serial.";
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                        }
                    }
                }
            }else{
                $randon = rand(111111,999999);
                $request['phone'] = $randon;
                Session::push('cardSerial', $randon);
            }
            
        }else{       
            // avoid error if user opend landing page and clicked registration then refresh the page again
            if( !isset($request['name']) && !isset($request['phone']) && !isset($request['email']) && !isset($request['gender']) ){
                $errorMessage = "You refreshed the registration page, Please fill in the following information again.";
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['errorMessage' => $errorMessage]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'errorMessage' => $errorMessage]);
                    }
                }
            }
        }
        
        if(App\Settings::where('type', 'getNetwork')->value('state')==1){ $signup->network_id = $request['network']; } 
        else{ $signup->network_id = App\Network::where('state',1)->value('id'); }
        if(!isset($signup->network_id)){$signup->network_id = App\Network::value('id');}

        if(App\Settings::where('type', 'getName')->value('state')==1){ $signup->u_name = $request['name']; }
        else{ $signup->u_name = $request['phone']; }

        if(App\Settings::where('type', 'getNetwork')->value('state')==1 or App\Settings::where('type', 'getUserName')->value('state') !=1){ $signup->u_uname = strtolower($request['username']); }
        else{
            // Check if user register with accountkit   
            if(Session::has('mobileWithoutCountryCode')) {$signup->u_uname = session('mobileWithoutCountryCode')[0];}
            else{$signup->u_uname = $request['phone']; }

            if(!isset($signup->u_uname) ){
                if(App\Settings::where('type', 'getEmail')->value('state')==1){
                    $signup->u_uname = $signup->u_uname = $request['email']; 
                }else{
                    $signup->u_uname = "N/A:".rand(11111,99999); 
                }
            }

        }
        
        if(App\Settings::where('type', 'getEmail')->value('state')==1){ $signup->u_email = $request['email']; }
        else{$signup->u_email = "";}
        
        if(App\Settings::where('type', 'getGender')->value('state')==1){ $signup->u_gender = $request['gender']; }
        else{$signup->u_gender = "2";}

        if(App\Settings::where('type', 'getPassword')->value('state')==1 and App\Settings::where('type', 'pms_integration')->value('state') != "1"){ $signup->u_password = $request['password']; }
        elseif(App\Settings::where('type', 'pms_integration')->value('state') == "1"){
            $signup->u_password = rand(1111111111,9999999999);
        }else{ $signup->u_password = $request['phone']; }
        
        if(isset($request['countrycode'])){
                $signup->u_phone = $request['countrycode'] . $request['phone'];
        }else{
            $signup->u_phone = "N/A";
        }

        //get country name 
        if($request['countrycode']=="2" or $request['countrycode']=="20" or $request['countrycode']=="+2" or $request['countrycode']=="+20"){$userCountry="Egypt";}
        elseif($request['countrycode']=="966"){$userCountry="Saudi Arabia";}
        elseif($request['countrycode']=="971"){$userCountry="United Arab Emirates";}
        elseif($request['countrycode']=="974"){$userCountry="Qatar";}
        elseif($request['countrycode']=="964"){$userCountry="Iraq";}
        elseif($request['countrycode']=="965"){$userCountry="Kuwait";}
        elseif($request['countrycode']=="961"){$userCountry="Lebanon";}
        elseif($request['countrycode']=="962"){$userCountry="Jordan";}
        elseif($request['countrycode']=="220"){$userCountry="Gambia";}
        else{$userCountry="Unknown";}
        $signup->u_country = $userCountry;
		
        //check if user exist
        $checkIfUserExist = App\Users::where('u_uname', $signup->u_uname)->first();
        if (isset($checkIfUserExist) and isset($signup->network_id) and App\Settings::where('type', 'mergeAccounts')->value('state')!=1) {
            // username exist and required
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {// return "test1";
                return view('front-end.landing.index', ['user_exist' => '1']);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {// return "test2";
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'user_exist' => '1']);
                }
            }

        }elseif(App\Users::where('u_phone', $request['countrycode'].$request['phone'])->count() > 0 and App\Settings::where('type', 'mergeAccounts')->value('state')!=1){
            // Mobile exist and merge account is disabled
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {// return "test3";
                return view('front-end.landing.index', ['mobile_exist' => '1']);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {// return "test4";
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'mobile_exist' => '1']);
                }
            }
        
        }elseif(App\Users::where('u_email', $request['email'])->count() > 0 and App\Settings::where('type', 'getEmail')->value('state')==1 and App\Settings::where('type', 'mergeAccounts')->value('state')!=1){
            // Email exist and required
            // Custom landing page & default landing page
                // then check if mergeAccounts disabled show error else disable error 
            
            if (isset($template) && $template == 'default') {// return "test5";
                return view('front-end.landing.index', ['email_exist' => '1']);
            }else{ 
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {// return "test6";
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'email_exist' => '1']);
                }
            }
            
        }else {

            if( (App\Settings::where('type', 'mergeAccounts')->value('state')==1 and App\Users::where('u_phone', $request['countrycode'].$request['phone'])->count()>0 and App\Settings::where('type', 'getMobileInSignupTab')->value('state')==1 ) or ( App\Settings::where('type', 'mergeAccounts')->value('state')==1 and App\Users::where('u_email', $request['email'])->count() > 0) )
            {
                // Mobile exist and merge account is enabled or email 
                // we will not create new account
                // and we will apply SMS confirmation
                // get user ID
                if(App\Settings::where('type', 'getMobileInSignupTab')->value('state')==1 and App\Users::where('u_phone', $request['countrycode'].$request['phone'])->count() > 0){
                    $mergedUserID=App\Users::where('u_phone', $request['countrycode'].$request['phone'])->value('u_id');
                }elseif(App\Settings::where('type', 'getEmail')->value('state')==1 and App\Users::where('u_email', $request['email'])->count() > 0){
                    $mergedUserID=App\Users::where('u_email', $request['email'])->value('u_id');
                }
                
                // check if mobile not exist before, search for email exist
                // if(count($mergedUserID)==0){$mergedUserID=App\Users::where('u_email', $request['email'])->value('u_id');}
                $dt = Carbon::now();
            
                // check if registration is done with account kit veryfication and SMS verification is turned on
                if(App\Settings::where('type', 'Accountkitappid')->value('state') != 1 and App\Network::where('id',$signup->network_id)->value('r_type') == 2){
                    
                    // sending SMS
                    $app_name = App\Settings::where('type', 'app_name')->value('value');
                    $code = rand(1111, 9999);
                    $smsVerificationTemplate = App\Settings::where('type', 'smsVerificationTemplate')->first();
                    if( isset($smsVerificationTemplate->value) and  $smsVerificationTemplate->value !="" ){
                        $message = str_replace('@CODE', $code, $smsVerificationTemplate->value);
                    }else{
                        // $message = $app_name . " Activation code is $code";  
                        $message = "Microsystem Smart Wi-Fi code is $code";  // New for VictoryLink OTP SMS according to NTRA rules
                        // $message = $app_name . " $code"; // to avoid operator filter
                    }
                    
                    $sendmessage = new App\Http\Controllers\Integrations\SMS();
                    $sendmessage->send($request['countrycode'] . $request['phone'], $message);
                    
                    // send whatsapp with SMS 11/9/2019
                    $split = explode('/', url()->full());
                    $customerData = DB::table('customers')->where('url',$split[2])->first();
                    
                    // sending WhatsApp SMS verification 
                    if($customerData->microsystem_whatsapp_verfy_free=="1"){
                        // sending WhatsApp SMS verification by Microsystem WhatsApp server 11/3/2022
                        $customerID = "3";
                        $customerDatabase = "demo";
                    }else{
                        // sending WhatsApp SMS verification by customer Whatsapp integration
                        // $customerID = App\Settings::where('type', 'customer_id')->value('value');
                        $customerID = $customerData->id;
                        $customerDatabase = $customerData->database;
                    }
                    $messageEncoded = urlencode($message);
                    $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                    $sendWhatsappMessage->send( "", $request['countrycode'].$request['phone'] , $messageEncoded, $customerID, $customerDatabase, "1", "", "", "1");
                    
                    // sending SMS verification by Microsystem SMS server 2/4/2021
                    if($customerData->microsystem_sms_verfy_free=="1"){
                        $microsystemSMSserver = new App\Http\Controllers\ApiController();
                        $microsystemSMSserver->sendMicrosystemSMS($customerData->id, 'verification', $request['countrycode'].$request['phone'], $message);
                        // @file("https://demo.microsystem.com.eg/api/sendMicrosystemSMS?customer_id=$customerData->id&type=verification&to=".request['countrycode'].$request['phone']."&message=$message");
                    }
                    // update verification code into user record
                    App\Users::where('u_id', $mergedUserID)->update(['sms_code' => $code]);

                    //insert SMS sending notification in History
                    App\History::insert(
                        ['type1' => 'hotspot', 'type2' => 'Auto', 'operation' => 'send_sms_confirmation', 'notes' => $_SERVER['REMOTE_ADDR'], 'details' => $code, 'u_id' => $mergedUserID, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                    );

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {// return "test7";
                        return view('front-end.landing.index', ['smsConfirm' => '1', 'u_id' => $mergedUserID]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {// return "test8";
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'smsConfirm' => '1', 'u_id' => $mergedUserID]);
                        }
                    }
                    
                }else{
                        // Account kit is enabled 
                        //Auto login
                        //$getUserAndPassword=App\Users::where('u_phone', $request['countrycode'].$request['phone'])->first();
                        $getUserAndPassword=App\Users::where('u_id', $mergedUserID)->first();
                        $loginData = array('username' => $getUserAndPassword->u_uname, 'password' => $getUserAndPassword->u_password);// return "test9";
                        return $this->loginAuto($loginData);

                }                
                
            }
            
                // New User
                // get network registration state
                $networkData = App\Network::find($signup->network_id);
                $networkType = $networkData->r_type;
                //return "111 ".$networkData;
                //Direct Registration or SMS with account Kit
                if ( $networkType == "0" or ( App\Settings::where('type', 'Accountkitappid')->value('state') == 1 and $networkType == "2" ) ) {
                    
                    // get default or first branch or Mikrotik location in the session
                    if(session('mikrotikLocationID')){
                        $finalBranchID = session('mikrotikLocationID')[0];
                    }else{
                        $getDefaultBranchID = App\Branches::where('name', 'default')->orWhere('name', 'Default')->value('id');
                        if (isset($getDefaultBranchID)) {
                            $finalBranchID = $getDefaultBranchID;
                        } else {
                            $finalBranchID = App\Branches::first()->value('id');
                        }
                    }
                    // return $finalBranchID;

                    //get default or first group
                    $getDefaultGroupsID = App\Groups::where('name', 'default')->value('id');
                    if (isset($getDefaultGroupsID)) {
                        $finalGroupID = $getDefaultGroupsID;
                    } else {
                        $finalGroupID = App\Groups::first()->value('id');
                    }

                    //set default values
                    $signup->group_id = $finalGroupID;
                    $signup->branch_id = $finalBranchID;
                    $signup->u_state = 1;
                    $signup->suspend = 0;
                    $signup->Registration_type = 2;//activated
                    
                    $signup->save();


                    $userdata = App\Users::where('u_uname', $signup->u_uname)->where('u_password', $signup->u_password)->first();
                    $u_country = ($userdata->u_country == "Unknown")? "United States" : $userdata->u_country;
                    $user_id = $userdata->u_id;
                    //$this->activate( $user_id,'signup',$userdata->u_email,$userdata->u_name,$u_country);
                    $ChatGPT->sendEmailVerifyUsingChatGptWithoutWaiting($user_id,'signup',$request->input('email'),$userdata->u_name,$u_country);
                    $loginData = array('username' => $signup->u_uname, 'password' => $signup->u_password);// return "test10";
                    return $this->loginAuto($loginData);

                } //Waiting Admin Confirm
                elseif ($networkType == "1") {
                    
                    // insert session mac address into user account (to be able to start internet dirclty after admin confirmation)
                    if(Session::has('identifyFromMikrotik')) {
                        // check if there is mac address into session
                        if(isset( explode('-', session('identifyFromMikrotik')[0] )[5] )){
                            $userMacFromMikrotik = explode('-', session('identifyFromMikrotik')[0] )[5]; 
                            $signup->u_mac = $userMacFromMikrotik;
                        }
                        
                    }
                    $signup->Registration_type = 1;//Waiting Admin Confirm

                    $signup->save();

                    $userdata = App\Users::where('u_uname', $signup->u_uname)->where('u_password', $signup->u_password)->first();
                    $u_country = ($userdata->u_country == "Unknown")? "United States" : $userdata->u_country;
                    $user_id = $userdata->u_id;
                    // $this->activate( $user_id,'signup',$userdata->u_email,$userdata->u_name,$u_country);
                    $ChatGPT->sendEmailVerifyUsingChatGptWithoutWaiting($user_id,'signup',$request->input('email'),$userdata->u_name,$u_country);

                } //Waiting SMS confirm
                elseif ($networkType == "2" and App\Settings::where('type', 'Accountkitappid')->value('state') != 1) {
                    // accountkit is disabled
                    $dt = Carbon::now();
                    
                    if(!isset($request->accountkit) && $request->accountkit !== 1){ 

                        // sending SMS
                        $app_name = App\Settings::where('type', 'app_name')->value('value');
                        $code = rand(1111, 9999);
                        $smsVerificationTemplate = App\Settings::where('type', 'smsVerificationTemplate')->first();
                        if( isset($smsVerificationTemplate->value) and  $smsVerificationTemplate->value !="" ){
                            $message = str_replace('@CODE', $code, $smsVerificationTemplate->value);
                        }else{
                            // $message = $app_name . " Activation code is $code";  
                            $message = "Microsystem Smart Wi-Fi code is $code";  // New for VictoryLink OTP SMS according to NTRA rules
                            // $message = $app_name . " $code"; // to avoid operator filter
                        }
                        $sendmessage = new App\Http\Controllers\Integrations\SMS();
                        $sendmessage->send($request['countrycode'] . $request['phone'], $message);

                        // send whatsapp with SMS 11/9/2019
                        $split = explode('/', url()->full());
                        $customerData = DB::table('customers')->where('url',$split[2])->first();

                        // sending WhatsApp SMS verification 
                        if($customerData->microsystem_whatsapp_verfy_free=="1"){
                            // sending WhatsApp SMS verification by Microsystem WhatsApp server 11/3/2022
                            $customerID = "3";
                            $customerDatabase = "demo";
                        }else{
                            // sending WhatsApp SMS verification by customer Whatsapp integration
                            $customerID = App\Settings::where('type', 'customer_id')->value('value');
                            $customerDatabase = $customerData->database;
                        }
                        $messageEncoded = urlencode($message);
                        $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                        $sendWhatsappMessage->send( "", $request['countrycode'].$request['phone'] , $messageEncoded, $customerID, $customerDatabase, "1", "", "", "1");
                        
                        // sending SMS verification by Microsystem SMS server 2/4/2021
                        $customerData = DB::table('customers')->where('url',$split[2])->first();
                        if($customerData->microsystem_sms_verfy_free=="1"){
                            $microsystemSMSserver = new App\Http\Controllers\ApiController();
                            $microsystemSMSserver->sendMicrosystemSMS($customerData->id, 'verification', $request['countrycode'].$request['phone'], $message);
                            // @file("https://demo.microsystem.com.eg/api/sendMicrosystemSMS?customer_id=$customerData->id&type=verification&to=".request['countrycode'].$request['phone']."&message=$message");
                        }
                    }
                    
                    // get default or first branch or Mikrotik location in the session
                    if(session('mikrotikLocationID')){
                        $finalBranchID = session('mikrotikLocationID')[0];
                    }else{
                        $getDefaultBranchID = App\Branches::where('name', 'default')->orWhere('name', 'Default')->value('id');
                        if (isset($getDefaultBranchID)) {
                            $finalBranchID = $getDefaultBranchID;
                        } else {
                            $finalBranchID = App\Branches::first()->value('id');
                        }
                    }
                    
                    //get default or first group
                    $getDefaultGroupsID = App\Groups::where('name', 'default')->value('id');
                    if (isset($getDefaultGroupsID)) {
                        $finalGroupID = $getDefaultGroupsID;
                    } else {
                        $finalGroupID = App\Groups::first()->value('id');
                    }

                    //set default values
                    $signup->group_id = $finalGroupID;
                    $signup->branch_id = $finalBranchID;
                    if(!isset($request->accountkit) && $request->accountkit !== 1){ 
                        $signup->sms_code = $code;
                    }
                    $signup->Registration_type = 0;//Waiting SMS confirm

                    // insert session mac address into user account (to be able to start internet dirclty after admin confirmation)
                    if(Session::has('identifyFromMikrotik')) {
                        // check if there is mac address into session
                        if(isset( explode('-', session('identifyFromMikrotik')[0] )[5] )){
                            $userMacFromMikrotik = explode('-', session('identifyFromMikrotik')[0] )[5]; 
                            $signup->u_mac = $userMacFromMikrotik;
                        }
                        
                    }

                    $
                    $signup->save();

                    $userdata = App\Users::where('u_uname', $signup->u_uname)->where('u_password', $signup->u_password)->first();
                    $u_country = ($userdata->u_country == "Unknown")? "United States" : $userdata->u_country;
                    $user_id = $userdata->u_id;
                    // $this->activate( $user_id,'signup',$userdata->u_email,$userdata->u_name,$u_country);
                    $ChatGPT->sendEmailVerifyUsingChatGptWithoutWaiting($user_id,'signup',$request->input('email'),$userdata->u_name,$u_country);

                    if(!isset($request->accountkit) && $request->accountkit !== 1){ 
                        //insert SMS sending notification in History
                        App\History::insert(
                            ['type1' => 'hotspot', 'type2' => 'Auto', 'operation' => 'send_sms_confirmation', 'notes' => $_SERVER['REMOTE_ADDR'], 'details' => $code, 'u_id' => $signup->u_id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                        );
                    
                        //Custom landing page & default landing page
                        if (isset($template) && $template == 'default') {// return "test11";
                            return view('front-end.landing.index', ['smsConfirm' => '1', 'u_id' => $signup->u_id]);
                        }else{
                            if (file_exists(public_path() . '/custom-landing/' . $landing)) {// return "test12";
                                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'smsConfirm' => '1', 'u_id' => $signup->u_id]);
                            }
                        }
                    }
                } else {// value not saved in network table
                
                    $signup->Registration_type = 1;//Waiting Admin Confirm

                    $signup->save();

                    $userdata = App\Users::where('u_uname', $signup->u_uname)->where('u_password', $signup->u_password)->first();
                    $u_country = ($userdata->u_country == "Unknown")? "United States" : $userdata->u_country;
                    $user_id = $userdata->u_id;
                    // $this->activate( $user_id,'signup',$userdata->u_email,$userdata->u_name,$u_country);

                    $ChatGPT->sendEmailVerifyUsingChatGptWithoutWaiting($user_id,'signup',$request->input('email'),$userdata->u_name,$u_country);
                }
                
            

            if ($template == 'template1') {// return "test13";
                return view('front-end.landing.index', array('successfullRegistration' => '1'));
            }
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {// return "test14";
                return view('front-end.landing.index', ['successfullRegistration' => '1']);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {// return "test15";
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'successfullRegistration' => '1']);
                }
            }
        }
    }

    public function deletebyuser($id)
    {
        $update = App\Messages::find($id);
        $update->deleted = 1;
        $update->update();
        return Redirect::back();
    }

  
    public function activate($user_id,$type,$email,$name,$country){
        $withValue = App\Settings::where('type', 'with_'.$type)->value('value');

        if (isset($withValue) && $withValue == '1') {
            $groupValue = App\Settings::where('type', 'group_'.$type)->value('value');
    
          $url =  "https://{$_SERVER['HTTP_HOST']}/activate";

          $token = str_random(60);

          $activationLink =  $url . "?code=" . $token;
          // Email subject and message

          //$message1 = "Hello,\n\nPlease click on the following link to activate your account:\n\n" . $activationLink;
          $data = $this->gptTurbo($name, $activationLink,$country);
          $obj = json_decode($data);
  
  // Access the "content" value
  $message1 = $obj->choices[0]->message->content;

          // Send email using PHP's built-in mail function
          $headers = "From: <".$email.">\r\n";
          $headers .= "Reply-To:  <noreply@microsystem.com.eg>\r\n";
          $headers .= "Content-type: text/plain; charset=UTF-8\r\n";


          App\Users::where('u_id', $user_id)->update(['token' => $token]);

        //   $result = mail($email, $subject, $message, $headers);
        Mail::send('emails.activation', ['content' => $message1], function ($message) use ($email) {
            $message->from('noreply@microsystem.com.eg', App\Settings::where('type', 'app_name')->value('value'));
            $message->to($email)->subject("Activate your account");
        });
        //   Mail::send('emails.activation',$message1,function($message) use($email)
        //   {
        //       $message->to($email)->subject('Activate your account');
        //   });

        }
    }

    public function gptTurbo($name,$link,$country){
      $curl = curl_init();
      $businessName = App\Settings::where('type', 'app_name')->value('value');
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "model": "gpt-3.5-turbo",
          "messages": [{"role": "assistant",
               "content": "Write an email without a subject or any variables for ('.$name.') for email verification in Wi-Fi service at ('.$businessName.') using the following link: ('.$link.'), based on the native language of their country ('.$country.') , and the reason for verification is to enable the internet after the 3 hours demo during your stay in the hotel, also raise the internet speed and quota and maximize the internet prioritization. "}]
          }',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer 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',
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      
      return $response;
    }
  
    public function activation(Request $request){

    }
    
    public function smsconfirm()
    {
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');
        
        $u_id = Input::get('user_id');
        $code = Input::get('sms_code');
        if (isset($code) and $code!="") {
            $user_check = App\Users::where('sms_code', $code)->first();
            if ($user_check) {
                $confirm = App\Users::find($user_check->u_id);
                $confirm->sms_code = "0";
                $confirm->Registration_type = 2;
                $confirm->update();
                $loginData = array('username' => $user_check->u_uname, 'password' => $user_check->u_password);
                return $this->loginAuto($loginData);
            } else {
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['confirm_error' => '1', 'u_id' => $u_id]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'confirm_error' => '1', 'u_id' => $u_id]);
                    }
                }
            }
        } else {
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {
                return view('front-end.landing.index', ['confirm_error' => '1', 'u_id' => $u_id]);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'confirm_error' => '1', 'u_id' => $u_id]);
                }
            }

        }

    }

    public function showcode($u_id)
    {

        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');


        //Custom landing page & default landing page
        if (isset($template) && $template == 'default') {
            return view('front-end.landing.index', ['send_code' => '1', 'u_id' => $u_id]);
        }else{
            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'send_code' => '1', 'u_id' => $u_id]);
            }
        }
    }

    public function sendcode() // in case user require new confirmation code
    {
        
        $dt = Carbon::now();
        $user_id = Input::get('user_id');
        $phone = Input::get('countrycode') . Input::get('phone_code');
        $phoneWithoutCountryCode = Input::get('phone_code');
        $today = date("Y-m-d");

        //check country code
        if(Input::get('countrycode')=="2"){$countryCode="Egypt";}else{$countryCode="Not Egypt";}
        //checkIfUserExist // we stoped it to provide resend code for registerd and unregisterd users
        // $checkIfUserExist = App\Users::where('u_id', $user_id)->where('Registration_type', '0')->first();
        // make sure this account didnt try more than 3 times in 5 min 
        
        $fiveMinAgo = date('H:i:s', strtotime('5 minutes ago'));
        $check_sms_confirm_count = App\History::where('u_id', $user_id)->where('operation', 'send_sms_confirmation')->where('add_date', $today)->where('add_time', '>=', $fiveMinAgo)->count();
        //get template running
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');
        
        // check if confirmation code limit exceeded
        // if ($check_sms_confirm_count <= 3 and isset($checkIfUserExist)) { 
        if ($check_sms_confirm_count <= 3) { 

            // check if valid mobile number
            if (is_numeric($phoneWithoutCountryCode) and ( (Input::get('countrycode')=="2" and strlen($phoneWithoutCountryCode) >= 11) or $countryCode!="Egypt" ) ) {

                // sending SMS
                $app_name = App\Settings::where('type', 'app_name')->value('value');
                $code = rand(1111, 9999);
                $smsVerificationTemplate = App\Settings::where('type', 'smsVerificationTemplate')->first();
                if( isset($smsVerificationTemplate->value) and  $smsVerificationTemplate->value !="" ){
                    $message = str_replace('@CODE', $code, $smsVerificationTemplate->value);
                }else{
                    // $message = $app_name . " Activation code is $code";  
                    $message = "Microsystem Smart Wi-Fi code is $code";  // New for VictoryLink OTP SMS according to NTRA rules
                    // $message = $app_name . " $code"; // to avoid operator filter
                }
                
                $phone_count = strlen($phoneWithoutCountryCode);
                $phones = $phoneWithoutCountryCode['0'] . $phoneWithoutCountryCode['1'];

                if ($phones == "01" or $countryCode!="Egypt") {
                    if (strpos($phone, '010') !== false || strpos($phone, '011') !== false || strpos($phone, '012') !== false || $countryCode!="Egypt") {

                        $user_check = App\Users::where('u_id', $user_id)->update(
                            ['sms_code' => $code, 'u_phone' => $phone]
                        );

                        if ($phone_count <= "11" or $countryCode!="Egypt") {
                            $sendmessage = new App\Http\Controllers\Integrations\SMS();
                            $sendmessage->send($phone, $message);

                            // send whatsapp with SMS 11/9/2019
                            $split = explode('/', url()->full());
                            $customerData = DB::table('customers')->where('url',$split[2])->first();

                            // sending WhatsApp SMS verification 
                            if($customerData->microsystem_whatsapp_verfy_free=="1"){
                                // sending WhatsApp SMS verification by Microsystem WhatsApp server 11/3/2022
                                $customerID = "3";
                                $customerDatabase = "demo";
                            }else{
                                // sending WhatsApp SMS verification by customer Whatsapp integration
                                $customerID = App\Settings::where('type', 'customer_id')->value('value');
                                $customerDatabase = $customerData->database;
                            }
                            $messageEncoded = urlencode($message);
                            $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                            $sendWhatsappMessage->send( "", $phone , $messageEncoded, $customerID, $customerDatabase, "1", "", "", "1");
                            
                            // sending SMS verification by Microsystem SMS server 2/4/2021
                            $customerData = DB::table('customers')->where('url',$split[2])->first();
                            if($customerData->microsystem_sms_verfy_free=="1"){
                                $microsystemSMSserver = new App\Http\Controllers\ApiController();
                                $microsystemSMSserver->sendMicrosystemSMS($customerData->id, 'verification', $phone, $message);
                                // @file("https://demo.microsystem.com.eg/api/sendMicrosystemSMS?customer_id=$customerData->id&type=verification&to=$phone&message=$message");
                            }
                            
                            //insert SMS sending notification in History
                            App\History::insert(
                                ['type1' => 'hotspot', 'type2' => 'Auto', 'operation' => 'send_sms_confirmation', 'notes' => $_SERVER['REMOTE_ADDR'], 'details' => $code, 'u_id' => $user_id, 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                            );
                            //Custom landing page & default landing page
                            if (isset($template) && $template == 'default') {
                                return view('front-end.landing.index', ['smsConfirm' => '1', 'u_id' => $user_id]);
                            }else{
                                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'smsConfirm' => '1', 'u_id' => $user_id]);
                                }
                            }
                        }
                        //Custom landing page & default landing page
                        if (isset($template) && $template == 'default') {
                            return view('front-end.landing.index', ['send_code' => '1', 'u_id' => $user_id]);
                        }else{
                            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'send_code' => '1', 'u_id' => $user_id]);
                            }
                        }
                    }
                } else {
                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['send_code' => '1', 'u_id' => $user_id]);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'send_code' => '1', 'u_id' => $user_id]);
                        }
                    }
                }

            } else {//not valid mobile number
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['send_code' => '1', 'u_id' => $user_id]);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'send_code' => '1', 'u_id' => $user_id]);
                    }
                }
            }

        } else {//confirmation code limit exceeded

            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {
                return view('front-end.landing.index', ['contact_system_administrator' => '1', 'u_id' => $user_id]);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'contact_system_administrator' => '1', 'u_id' => $user_id]);
                }
            }
        }

    }
    
    // when user enter there phone number to get verification code
    public function firebaseStep1($gender,$name,$email,$countryCode,$mobile){
        
        $userData = App\Users::where('u_phone', 'like', '%'.$countryCode.$mobile.'%')->first();

        // sending SMS
        $app_name = App\Settings::where('type', 'app_name')->value('value');
        $code = rand(1111, 9999);
        $smsVerificationTemplate = App\Settings::where('type', 'smsVerificationTemplate')->first();
        if( isset($smsVerificationTemplate->value) and  $smsVerificationTemplate->value !="" ){
            $message = str_replace('@CODE', $code, $smsVerificationTemplate->value);
        }else{
            // $message = $app_name . " Activation code is $code";  
            $message = "Microsystem Smart Wi-Fi code is $code";  // New for VictoryLink OTP SMS according to NTRA rules
            // $message = $app_name . " $code"; // to avoid operator filter
        }
        
        $fullMobile = $countryCode.$mobile;
        $todayDateTime = date("Y-m-d")." ".date("H:i:s");

        // check if user already exist or new
        if( isset($userData) ){
            // update verification code into user record
            App\Users::where('u_id', $userData->u_id)->update(['sms_code' => $code]);
            $newUserID = $userData->u_id;
        }else{
            // check country code
            if( substr($fullMobile, 0, 2)=="20" ){ $u_country = "Egypt"; }
            elseif( substr($fullMobile, 0, 3)=="966" ){ $u_country = "Saudi Arabia"; }
            elseif( substr($fullMobile, 0, 3)=="971" ){  $u_country = "United Arab Emirates"; }
            elseif( substr($fullMobile, 0, 3)=="965" ){  $u_country = "Kuwait"; }
            elseif( substr($fullMobile, 0, 3)=="905" ){  $u_country = "Canada"; }
            elseif( substr($fullMobile, 0, 2)=="41" ){   $u_country = "Switzerland"; }
            elseif( substr($fullMobile, 0, 3)=="491" ){  $u_country = "Germany"; }
            elseif( substr($fullMobile, 0, 3)=="316" ){  $u_country = "Netherlands"; }
            elseif( substr($fullMobile, 0, 2)=="44" ){   $u_country = "United Kingdom"; }
            elseif( substr($fullMobile, 0, 3)=="393" ){  $u_country = "Italy"; }
            elseif( substr($fullMobile, 0, 3)=="336" ){  $u_country = "France"; }
            elseif( substr($fullMobile, 0, 3)=="973" ){  $u_country = "Bahrain"; }
            elseif( substr($fullMobile, 0, 3)=="974" ){  $u_country = "Qatar"; }
            elseif( substr($fullMobile, 0, 3)=="964" ){  $u_country = "Iraq"; }
            elseif( substr($fullMobile, 0, 3)=="961" ){  $u_country = "Lebanon"; }
            elseif( substr($fullMobile, 0, 3)=="962" ){  $u_country = "Jordan"; }
            elseif( substr($fullMobile, 0, 3)=="220" ){  $u_country = "Gambia"; }
            elseif( substr($fullMobile, 0, 3)=="970" ){  $u_country = "Palestine"; }
            elseif( substr($fullMobile, 0, 3)=="972" ){  $u_country = "Israel"; }
            else{ $u_country = "Unknown";}

            // set Registration Type
            $networkData = App\Network::where('state','1')->first();
                            
            if ( $networkData->r_type == "0" or ( App\Settings::where('type', 'firebaseAuthentication')->value('state') == 1 and $networkData->r_type == "2" ) ) {
                $Registration_type = 2;//activated
            }
            elseif ($networkData->r_type == "1") {
                $Registration_type = 1;//Waiting Admin Confirm
            }else{
                $Registration_type = 0;
            }
            // create new user in database
            $newUserID =  App\Users::insertGetId(['sms_code' => $code, 'u_email' => $email, 'Registration_type' => $Registration_type, 'u_state' => '1', 'suspend' => '0', 'u_name' => $name, 'u_uname' => $mobile, 'u_password' => $countryCode.$mobile, 'u_phone' => $countryCode.$mobile, 'u_country' => $u_country, 'u_gender' => $gender, 'branch_id' => App\Branches::where('state','1')->value('id'), 'network_id' => App\Network::where('state','1')->value('id'), 'group_id' => App\Groups::where('name','Default')->orWhere('name','default')->value('id'), 'created_at' => $todayDateTime]);
        }
        
        // send whatsapp with SMS 11/9/2019
        $split = explode('/', url()->full());
        $customerID = App\Settings::where('type', 'customer_id')->value('value');
        // $customerData = DB::table('customers')->where('url',$split[2])->first();
        $customerDatabase = DB::table('customers')->where('id',$customerID)->value('database');
        $message = urlencode($message);
        $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
        $sendWhatsappMessage->send( "", $fullMobile , $message, $customerID, $customerDatabase, "1", "", "", "1");

        //insert SMS sending notification in History
        App\History::insert(['type1' => 'hotspot', 'type2' => 'Auto', 'operation' => 'send_sms_confirmation', 'notes' => $_SERVER['REMOTE_ADDR'], 'details' => $code, 'u_id' => $newUserID, 'add_date' => date("Y-m-d"), 'add_time' => date("H:i:s")]);
        return "1";
    }

    // when user enter verification code
    public function firebaseVerifyCode($gender,$name,$email,$countryCode,$mobile,$code){
        if( App\Users::where('u_phone', 'like', '%'.$countryCode.$mobile.'%')->where('sms_code',$code)->count() > 0){
            //token update
            $token = rand(1, 9999) . chr(rand(65, 90)) . rand(1111, 5555) . chr(rand(65, 90)) . rand(2222, 6666) . chr(rand(65, 90)) . rand(3333, 7777) . rand(4444, 8888) . rand(5555, 9999) . chr(rand(65, 90));
            App\Users::where('u_phone', 'like', '%'.$countryCode.$mobile.'%')->where('sms_code',$code)->update(['token' => $token]);
            return $token;
        }else{
            return "0";
        }
    }

    // before last step creating token
    public function firebaseCreateToken($mobile){
        //token update
        $mobile = str_replace("+","",$mobile);
        $token = rand(1, 9999) . chr(rand(65, 90)) . rand(1111, 5555) . chr(rand(65, 90)) . rand(2222, 6666) . chr(rand(65, 90)) . rand(3333, 7777) . rand(4444, 8888) . rand(5555, 9999) . chr(rand(65, 90));
        App\Users::where('u_phone', 'like', '%'.$mobile.'%')->update(['token' => $token]);
        return $token;
    }
    // when login successfully
    public function firebaseLoginSuccess($token){
    
        $confirm = App\Users::where('token', $token)->first();
        if(isset($confirm)){
            $confirm->sms_code = "0";
            // $confirm->Registration_type = 2;
            // $confirm->u_state = 1;
            $confirm->update();
            $loginData = array('username' => $confirm->u_uname, 'password' => $confirm->u_password);
            return $this->loginAuto($loginData);    
        }else{
            return Redirect::back();
        }
    }
    // show all landing page tables includes(default landing page, branch landings, website builders)
    public function get_landing(){
        $data = App\History::where([ 'type2' => 'admin', 'operation' => 'custom_landing_page', 'notes' => 'landing'])->get();
        foreach($data as $value){
            //$value['default']=['name'=>'test','hamada'=>'fe3on'];
            $value->unique_id = explode('/', $value->details)[3];
            if(App\Settings::where('type', 'landing')->value('value') == $value->unique_id){
                $state = 1;
            }else{
                $state = 0;
            }
            $value->state = $state;
            $value->name = 'Custom';
        }
        if(App\Settings::where('type', 'template')->value('value') == "default"){
            $default = 1;
        }else{
            $default = 0;
        }
        $dt = Carbon::now();
        $arrayCounter=count($data);
        // add branch landing pages
        $subdomain = url()->full();
        $domain = explode('/', $subdomain)[2];
        $database = DB::table('customers')->where('url',$domain)->value('database');
        foreach(App\Media::where('template', 'branch_landing')->whereNotNull('branch_id')->groupBy('branch_id')->get() as $branchLanding){
            $data[$arrayCounter++]=['name' => 'branch_landing', 'state' => $default, 'database' => $database, 'branch_id' => $branchLanding->branch_id, 'branch_name' => App\Branches::where('id', $branchLanding->branch_id)->value('name'), 'add_date'=> explode(' ', $branchLanding->created_at)[0], 'add_time'=> explode(' ', $branchLanding->created_at)[1], 'unique_id' => 'branch_landing'];
        }
        //add default landing page
        $data[$arrayCounter++]=['name' => 'default', 'state' => $default,'add_date'=> $dt->toDateString(), 'add_time'=> $dt->toTimeString(), 'unique_id' => 'default'];
        return array('aaData' => $data);
    }

    public function landing_state($unique_id,$value){

        $value = ($value == 'true') ? 1 : 0;
        if($value == 1){
            App\Settings::where('type', 'landing')->update(['value' => $unique_id]);
        }else{
            App\Settings::where('type', 'landing')->update(['value' => Null]);

        }

        if($value == 1 && $unique_id == "default"){
            App\Settings::where('type', 'template')->update(['value' => $unique_id]);
        }else{
            App\Settings::where('type', 'template')->update(['value' => Null]); 

        }
    }

    public function perview_landing($unique_id){

        if (file_exists(public_path() . '/custom-landing/' . $unique_id)) {
            return view("front-end.custom-landing.$unique_id.index", ['id' => $unique_id]);
        }else{
            return view('front-end.landing.index');
        }
    }
    // add media for default and edit media for branch landing page
    public function add_media(Request $request)
    {
        
        //Upload Background Pic
        if ($request->hasFile('file')) {

            if($request->template == "default"){
                App\Media::where('template' , $request->template)->delete();
                $branch_id = null;
            }elseif($request->template == "branch_landing"){
                App\Media::where('template' , $request->template)->where('branch_id' , $request->branch_id)->delete();
                $branch_id = $request->branch_id;
            }

            $file = Input::file('file');
            foreach ($file as $value) {
                // set name of files
                $subdomain = url()->full();
                $domain = explode('/', $subdomain)[2];
                $database = DB::table('customers')->where('url',$domain)->value('database');
                $name = $database . "-" . date('Y-m-d-H:i:s') . "-" . rand(111111111,999999999);
                $value->move(public_path() . '/upload/media/', $name);
                
                //insert
                App\Media::insert([
                    'file' =>  $name, 'template' => $request->template, 'type' => 'custom-landing', 'branch_id' => $branch_id
                ]);
            }
        }
        
        return redirect()->route('landings');
    }
    // add media for a specific landing page for each branch seperatly
    public function add_branch_landing(Request $request)
    {
        if(isset($request->branch_id) and $request->branch_id!=""){
            App\Media::where('template' ,'branch_landing')->where('branch_id' ,$request->branch_id)->delete();

            //Upload Background Pic
            if ($request->hasFile('file')) {
                $file = Input::file('file');
                foreach ($file as $value) {
                    // set name of files
                    $subdomain = url()->full();
                    $domain = explode('/', $subdomain)[2];
                    $database = DB::table('customers')->where('url',$domain)->value('database');
                    $name = $database . "-" . date('Y-m-d-H:i:s') . "-" . rand(111111111,999999999);
                    $value->move(public_path() . '/upload/media/', $name);
                    //insert
                    App\Media::insert([ 'file' =>  $name, 'template' => 'branch_landing', 'type' => 'custom-landing', 'branch_id' => $request->branch_id ]);
                }
            }
        }
        return redirect()->route('landings');
    }
    
    public function landing_info($name, $branch_id=null)
    {
        if($name == 'default'){ 
            $data = App\Media::where('template' , $name)->get(); 
            return view('back-end.landing.edit', ['template' => $name, 'data' => $data]);
        }
        elseif($name == 'branch_landing'){ 
            $data = App\Media::where('template' , $name)->where('branch_id' , $branch_id)->get(); 
            $branchName = App\Branches::where('id',$branch_id)->value('name');
            return view('back-end.landing.edit', ['template' => $name, 'branch_id' => $branch_id, 'branch_name' => $branchName, 'data' => $data]);
        }
            
    }

    public function landing_delete($id)
    {
        
        $file_id = explode('/', App\History::where('id', $id)->value('details'))[3];
        
        $public = public_path().'/custom-landing/'.$file_id;
        $public_remove = "rm -fr $public";
        exec("$public_remove");

        $resource = resource_path().'/views/front-end/custom-landing/'.$file_id;
        $resource_remove = "rm -fr $resource";
        exec("$resource_remove");

        App\History::where('id', $id)->delete();
        return redirect()->route('landings');
    }

    public function branch_landing_delete($branch_id)
    {
        App\Media::where('template' , 'branch_landing')->where('branch_id' , $branch_id)->delete();
        return redirect()->route('landings');
    }

    public function charge(Request $request)
    {
        
            // $userData = App\Users::where('u_id', $request->id)->first();
            // return "sssssssssssssss<br>ssss<br>ssss<br> $userData->u_name ssss<br>ssss<br>ssss<br>ssss $request->id".Input::get('card');
            // $user = App\Users::where('u_id', $user->u_id)->first();

            // $loginData = array('username' => $user->u_uname, 'password' => $user->u_password);
            // return app('App\Http\Controllers\LandingController')->loginAuto($loginData);

        $dt = Carbon::now();
        // Get expiration date
        $card_validate_date_charging_before_convert = strtotime(date($dt->toDateString(), strtotime($dt->toDateString())) . " - 1 month");
        $card_validate_date_charging = date('Y-m-d', $card_validate_date_charging_before_convert);

        $invalid_retries = App\History::where('u_id', Input::get('id'))->where('operation', 'Wrong charged card')->where('add_date', '<=', $dt->toDateString())->where('add_date', '>=', $card_validate_date_charging)->where('notes', '1')->count();
        if ($invalid_retries <= 20) {
            $check = App\Cards::where('number', Input::get('card'))->first();
            if (isset($check)) {//Card Exist
                $card_state = $check->state;
                $card_price = $check->price;
                $card_id = $check->id;
                if ($card_state == 1) {//Card active
                    App\Cards::where('number', Input::get('card'))
                        ->update(['date_of_charging' => $dt->toDateString(), 'state' => 0, 'u_id' => Input::get('id')]);
                    $userData = App\Users::where('u_id', Input::get('id'))->first();
                    $Last_user_credit = $userData->credit;
                    $userBranchID = $userData->branch_id;
                    $userGroupID = $userData->group_id;
                    $userNetworkID = $userData->network_id;
                    App\Users::where('u_id', Input::get('id'))
                        ->update(['credit' => $Last_user_credit + $card_price]);

                    $request->session()->push('status', 'Card has been charged');
                    $cards = App\Cards::where('number', Input::get('card'))->value('id');
                    if (isset($cards)) {
                        App\History::insert(
                            ['type1' => 'hotspot', 'type2' => 'user', 'operation' => 'Charged card', 'details' => $cards . ';' . Input::get('card'), 'u_id' => Input::get('id'), 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString(), 'package_price' => $card_price, 'branch_id' => $userBranchID, 'group_id' => $userGroupID, 'network_id' => $userNetworkID]
                        );
                    }
                    // // check if system have one package for auto charge
                    // if(App\Models\Packages::where('state', '1')->count() == "1"){
                    //     $packageDate = App\Models\Packages::where('state', '1')->first();
                    //     // check if user creait equal package cost
                    //     $totalUserCredit = $Last_user_credit + $card_price;
                    //     if($packageDate->price <= $totalUserCredit){
                    //         // charge package directly
                    //         return $this->Chargepackage(Input::get('id'),$packageDate->id,'1');
                    //     }
                    // }
                    
                    // check if user creait equal package cost for auto charge
                    $totalUserCredit = $Last_user_credit + $card_price;
                    $packageDate = App\Models\Packages::where('state', '1')->where('price', $totalUserCredit)->first();
                    if(isset($packageDate)){    
                        // charge package directly
                        return $this->Chargepackage(Input::get('id'),$packageDate->id,'1');
                    }
                    
                    // charge has been successfully
                    if(session('userChargeCard')){ // user already logged in (normal case)
                        return redirect()->route('account'); 
                    }else{ // user use Iphone so this mobile opend new prser witout login; so we will apply auto loggin to avoid enter login data again
                        $user = App\Users::where('u_id', $request->id)->first();
                        $loginData = array('username' => $user->u_uname, 'password' => $user->u_password);
                        return $this->loginAuto($loginData);
                    }
                    
                    

                } else {
                    $request->session()->push('status', 'Card already charged before');
                    return redirect()->route('account');
                }
            } else {//Card Not found
                App\History::insert(
                    ['type1' => 'hotspot', 'type2' => 'user', 'operation' => 'Wrong charged card', 'notes' => '1', 'details' => 'Wrong card number by ip ' . $_SERVER['REMOTE_ADDR'], 'u_id' => Input::get('id'), 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                $request->session()->push('status', 'Your invalid retries ' . $invalid_retries . ' of 20');
                return redirect()->route('account');
            }
        } else {
            $request->session()->push('status', 'You have exceeded the limit of invalid retries, your account has been suspended.');
            App\Users::where('u_id', Input::get('id'))
                ->update(['suspend' => '1']);

            App\History::where('u_id', Input::get('id'))->where('operation', 'Wrong charged card')
                ->update(['notes' => '0']);
            Session::flush();
            return redirect('/');

        }
    }

    public function ViewPackage(Request $request, $user_id, $package_id)
    {
        return view('front-end.landing.charge', ['charge' => $user_id . '/' . $package_id, 'package_id' => $package_id]);
    }

    public function Chargepackage($u_id, $package_id, $confirm = null, $reseller = null)
    {

        if (session('chargepackage')) {
            Session::pull('chargepackage');
        }

        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        // $homePath = base_path();
        // $path = $homePath . "/public/api/include/buyPackage/chargeRadius.php $u_id $package_id $confirm $reseller";
        // $value = exec("/usr/local/bin/php -f $path");
        $value = @file_get_contents("http://".$split[2]."/api/include/buyPackage/chargeRadius.php?u_id=$u_id&package_id=$package_id&confirm=$confirm&reseller=$reseller");
        $user = App\Users::where('u_id', $u_id)->first();
        if ($package_id == $user->monthly_package_id) {
            $start_date = $user->monthly_package_start;
            $expiry_date = $user->monthly_package_expiry;
        } elseif ($package_id == $user->validity_package_id) {
            $start_date = $user->validity_package_start;
            $expiry_date = $user->validity_package_expiry;
        } elseif ($package_id == $user->time_package_id) {
            $start_date = $user->time_package_start;
            $expiry_date = $user->time_package_expiry;
        } elseif ($package_id == $user->bandwidth_package_id) {
            $start_date = $user->bandwidth_package_start;
            $expiry_date = $user->bandwidth_package_expiry;
        }
        $admin = App\Admins::where('id', $reseller)->value('credit');
        if (isset($reseller)) {
            $remaining_credit = $admin;
        } else {
            $remaining_credit = $user->credit;
        }


        if ($value == 0) {
            $message = "Hmmm, you can't charge this package now.";
        } elseif ($value == 1) {
            if (isset($reseller)) {
                $message = "Package has been charged successfully your reseller remaining credit " . $remaining_credit . " expiration date is " . $expiry_date . "";
            } else {
                // get package price
                $package = App\Models\Packages::where('id', $package_id)->first();
                if($user->pms_id=="0"){
                    if($package->price == 0){$message = "The complimentary package has been applied successfully.";}
                    else{$message = "Package has been charged successfully your remaining credit " . $remaining_credit . " expiration date is " . $expiry_date . "";}
                }else{
                    if($package->price == 0){$message = "The complimentary package has been applied successfully.";}
                    else{$message = "Package invoice has been added successfully to your invoice at the Hotel, your expiration date is " . $expiry_date . "";}
                }
            }
        } elseif ($value == 2) {
            $message = "Sorry, you don't have enough credit. Please charge your account and try again.";
        } elseif ($value == 3) {
            $message = "Another error";
        } elseif ($value == 4) {
            $message = "Charge from web";
        } elseif ($value == 5) {
            $message = "Error package conflict";
        } elseif ($value == 6) {
            $message = "Can't charge bandwidth package without charge any package contains quota limit";
        }
        Session::push('chargepackage', $message);
        Session::push('packageid', $package_id);
        Session::push('userid', $u_id);
        // $request->session()->push('chargepackage', $message);
        // $request->session()->push('packageid', $package_id);
        // $request->session()->push('userid', $u_id);
        if (isset($reseller)) {
            return redirect()->route('search', 'message=' . $message);
        } else {
            // charge has been successfully
            if(session('userChargeCard')){ // user already logged in (normal case)
                return redirect()->route('account'); 
            }else{ // user use Iphone so this mobile opend new prser witout login; so we will apply auto loggin to avoid enter login data again
                $user = App\Users::where('u_id', $u_id)->first();
                $loginData = array('username' => $user->u_uname, 'password' => $user->u_password);
                return $this->loginAuto($loginData);
            }
        }
    }

    /**
     *
     * Get Visitor  Info and insert to the database
     *
     */
    public function visitorInfo()
    {
        // Get User Agent
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = $this->getClientIP();
        $browser_name = Identify::browser()->getName();
        $os = Identify::os()->getName();
        $device_name = Identify::device()->getName();
        $lang = Identify::lang()->getLanguage();

        // Check if the user is logged in
        if (Auth::check()) :
            // Check if the user has a client role
            if (Auth::user()) :
                $entry = $this->visitors->whereDay('created_at', '=', date('d'))->where('ip', $ip)->where('type', 'user')->get();
                if (count($entry) < 1) :
                    // Add the visitor info
                    /*$this->visitors->create([
                        'agent' => $agent,
                        'ip' => $ip,
                        'type' => 'user',
                        'browser' => $browser_name,
                        'os' => $os,
                        'devicename' => $device_name,
                        'lang' => $lang,
                    ]);*/
                endif;
                // Add the visitor info
                Visitors::insert([
                    'agent' => $agent,
                    'ip' => $ip,
                    'type' => 'user',
                    'browser' => $browser_name,
                    'os' => $os,
                    'devicename' => $device_name,
                    'lang' => $lang,
                    'created_at' => Carbon::Now(),
                    'updated_at' => Carbon::Now(),
                ]);
                /*$this->visitors->create([
                    'agent' => $agent,
                    'ip' => $ip,
                    'type' => 'admin',
                    'browser' => $browser_name,
                    'os' => $os,
                    'devicename' => $device_name,
                    'lang' => $lang,
                ]);*/
            endif;
        else:
            // If visitor is a guest
            $entry = $this->visitors->whereDay('created_at', '=', date('d'))->where('ip', $ip)->where('type', 'guest')->get();
            if (count($entry) < 1) :
                // Add the visitor info
                /*$this->visitors->create([
                    'agent' => $agent,
                    'ip' => $ip,
                    'type' => 'guest',
                    'browser' => $browser_name,
                    'os' => $os,
                    'devicename' => $device_name,
                    'lang' => $lang,
                ]);*/
            endif;
            Visitors::insert([
                'agent' => $agent,
                'ip' => $ip,
                'type' => 'guest',
                'browser' => $browser_name,
                'os' => $os,
                'devicename' => $device_name,
                'lang' => $lang,
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ]);

            // Add the visitor info
            /*$this->visitors->create([
                'agent' => $agent,
                'ip' => $ip,
                'type' => 'guest',
                'browser' => $browser_name,
                'os' => $os,
                'devicename' => $device_name,
                'lang' => $lang,
            ]);*/
        endif;
    }

    /**
     *
     * Get Client IP Address
     * @return  integer
     *
     */
    public function getClientIP()
    {
        $ip = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ip = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ip = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ip = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ip = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ip = getenv('REMOTE_ADDR');
        else
            $ip = 'UNKNOWN';

        return $ip;
    }

    public function forget_modal()
    {
        //get template running
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        //Custom landing page & default landing page
        if (isset($template) && $template == 'default') {
            return view('front-end.landing.index', ['forget' => '1']);
        }else{
            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'forget' => '1']);
            }
        }
    }

    public function forget()
    {

        $user = App\Users::where('u_email', Input::get('email'))->first();

        $request_mail = Input::get('email');

        if (isset($user) && isset($request_mail) && $request_mail !== "" && $request_mail !== " " && $request_mail !== "  ") {
            $state = "1";
            //token update
            $token = rand(1, 9999) . chr(rand(65, 90)) . rand(1111, 5555) . chr(rand(65, 90)) . rand(2222, 6666) . chr(rand(65, 90)) . rand(3333, 7777) . rand(4444, 8888) . rand(5555, 9999) . chr(rand(65, 90));
            App\Users::where('u_id', $user->u_id)->update(['token' => $token]);

            if (App\Settings::where('type', 'email')->value('value')) {

                $from = App\Settings::where('type', 'email')->value('value');
            } else {
                $from = Input::get('email');
            }
            $subject = 'Forget Password request';

            Mail::send('emails.forget', ['title' => $subject, 'token' => $token, 'username' => $user->u_name], function ($message) use ($user, $from, $subject) {
                $message->from($from, App\Settings::where('type', 'app_name')->value('value'));
                $message->to($user->u_email, $user->u_name)->subject($subject);
            });
        } else {
            $state = "0";
        }


        //get template running
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        //Custom landing page & default landing page
        if (isset($template) && $template == 'default') {
            return view('front-end.landing.index', ['reset' => '1', 'mailsend' => $state]);
        }else{
            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'reset' => '1', 'mailsend' => $state]);
            }
        }

    }

    public function reset_modal($token)
    {

        //get template running
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        //Custom landing page & default landing page
        if (isset($template) && $template == 'default') {
            return view('front-end.landing.index', ['reset' => '1', 'token' => $token]);
        }else{
            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'reset' => '1', 'token' => $token]);
            }
        }
    }

    public function reset()
    {
        $user = App\Users::where('token', Input::get('token'))->first();
        if ($user) {
            App\Users::where('u_id', $user->u_id)->update(['token' => null, 'u_password' => Input::get('password')]);
            $user = App\Users::where('u_id', $user->u_id)->first();

            $loginData = array('username' => $user->u_uname, 'password' => $user->u_password);
            return app('App\Http\Controllers\LandingController')->loginAuto($loginData);
        }


        //get template running
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        //Custom landing page & default landing page
        if (isset($template) && $template == 'default') {
            return view('front-end.landing.index');
        }else{
            if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                return view("front-end.custom-landing.$landing.index", ['id' => $landing]);
            }
        }
    }
    
    public function social_signup_kit(Request $request){
        //get template running
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        if (!session('Accountkit')){// check if user refresh page and resubmit request again

            $sendmessage = new App\Http\Controllers\Integrations\Accountkit();
            $response = $sendmessage->Send($request->get('code'));
            if(isset($response)){

                if(App\Users::where('u_phone', $response)->count() > 0){
                    // mobile number exist

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['social_mobile_exist' => '1']);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'social_mobile_exist' => '1']);
                        }
                    }
                    
                }else{ // valid and new mobile number
                    
                    App\Users::where('u_id', $request->userid)->update([
                        'u_phone' =>  $response, 'Registration_type' => 2 
                    ]);
                    $userdata = App\Users::where('u_id', $request->userid)->first();

                    if(isset($userdata->facebook_id)){
                        $current_provider_filed="facebook_id";
                        $social_id=$userdata->facebook_id;
                    }elseif(isset($userdata->twitter_id)){
                        $current_provider_filed = "twitter_id";
                        $social_id = $userdata->twitter_id;
                    }elseif(isset($userdata->google_id)){
                        $current_provider_filed = "google_id";
                        $social_id = $userdata->google_id;
                    }elseif(isset($userdata->linkedin_id)){
                        $current_provider_filed = "linkedin_id";
                        $social_id = $userdata->linkedin_id;
                    }

                    $login = array('username'=> $userdata->u_uname ,'social'=> $current_provider_filed , 'password' => $social_id);
                    return app('App\Http\Controllers\LandingController')->loginAuto($login);

                }
            }
        }else{
            //Custom landing page & default landing page
            if (isset($template) && $template == 'default') {
                return view('front-end.landing.index', ['signup_step2' => '1']);
            }else{
                if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                    return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'signup_step2' => '1']);
                }
            }
        }

    }
    public function signup_kit(Request $request){
        
        //get template running
        $template = App\Settings::where('type', 'template')->value('value');
        $landing = App\Settings::where('type', 'landing')->value('value');

        if (!session('Accountkit') and $request->get('code')){// check if user refresh page and resubmit request again
            
            $sendmessage = new App\Http\Controllers\Integrations\Accountkit();
            $response = $sendmessage->Send($request->get('code'));
            if(isset($response)){
                
                Session::push('Accountkit', $response);

                if(App\Users::where('u_phone', $response)->count() > 0 and App\Settings::where('type', 'mergeAccounts')->value('state')!=1){
                    // mobile number exist and merge accounts is disable

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['mobile_exist' => '1']);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'mobile_exist' => '1']);
                        }
                    }

                }elseif(App\Users::where('u_phone', $response)->count() == 0){
                    // new mobile number

                    //Custom landing page & default landing page
                    if (isset($template) && $template == 'default') {
                        return view('front-end.landing.index', ['signup_step2' => '1']);
                    }else{
                        if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                            return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'signup_step2' => '1']);
                        }
                    }

                }elseif(App\Users::where('u_phone', $response)->count() > 0 and App\Settings::where('type', 'mergeAccounts')->value('state')==1){
                    // mobile number exist and merga accounts is enabled
                    
                    //Auto login
                    $mergedUserID=App\Users::where('u_phone', $response)->value('u_id');
                    $getUserAndPassword=App\Users::where('u_id', $mergedUserID)->first();
                    $loginData = array('username' => $getUserAndPassword->u_uname, 'password' => $getUserAndPassword->u_password);
                    return $this->loginAuto($loginData);

                }
            }else{// redirect to landing page again
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index');
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing]);
                    }
                }
            }
        }else{
            if(App\Settings::where('type', 'mergeAccounts')->value('state')!=1){
                
                //Custom landing page & default landing page
                if (isset($template) && $template == 'default') {
                    return view('front-end.landing.index', ['signup_step2' => '1']);
                }else{
                    if (file_exists(public_path() . '/custom-landing/' . $landing)) {
                        return view("front-end.custom-landing.$landing.index", ['id' => $landing, 'signup_step2' => '1']);
                    }
                }
            }else{
                // user merged
                $mergedUserID=App\Users::where('u_phone', session('Accountkit')[0])->value('u_id');
                $getUserAndPassword=App\Users::where('u_id', $mergedUserID)->first();
                $loginData = array('username' => $getUserAndPassword->u_uname, 'password' => $getUserAndPassword->u_password);
                return $this->loginAuto($loginData);
            }
        }

    }


    public function validation($type, Request $request){
        if($type == "username") {
            return App\Users::where('u_name', $request->username)->count();
        }
        if($type == "email") {
            if(App\Settings::where('type', 'mergeAccounts')->value('state')==1)
            {   // to disable error message of dublication mobile number
                return "0";
            }else{
                return App\Users::where('u_email', $request->email)->count();
            }
        }
        if($type == "phone") {
            if(App\Settings::where('type', 'mergeAccounts')->value('state')==1)
            {   // to disable error message of dublication mobile number
                return "0";
            }else{
                return App\Users::where('u_phone', $request->phone)->count();
            }
            
        }
    }


}