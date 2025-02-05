<?php
    if( isset($result[0]['id']) and App\Users::where('u_id',$result[0]['id'])->count() > 0 ){
    $pmsIntegrationState = App\Settings::where('type', 'pms_integration')->value('state');
       
?>
<!doctype html>
<html class="no-js" lang="">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $result[0]['name'] }} - Internet Profile</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->
    <!-- <link rel="icon" type="image/png" href="favicon.png"> -->

    <link rel="stylesheet" href="user/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="user/css/vendor/animate.min.css">
    <link rel="stylesheet" href="user/css/vendor/owl.carousel.css">
    <link rel="stylesheet" href="user/css/vendor/owl.transitions.css">
    <link rel="stylesheet" href="user/css/style.css">
     <!-- sendpulse.com push notification -->
    <!-- <script charset="UTF-8" src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/fb1ad52bf6e57bebdb72ad07126b74ea_0.js" async></script> -->

    <script src="user/js/vendor/modernizr.js"></script>
  </head>
  <script>
      var loginStatus = {!! Auth::check() ? "'logged'" : "'not'" !!};
      var siteUrl = '{{ url("/") }}';
      var token = '{{ csrf_token() }}';
  </script>

<?php
    // check if admin getiing CardSerialInSignupTab, thats mean we will auto charge card, the the card will auto charge package
    if(Session::has('cardSerial')){
        $autoChargeURL=url('charge').'?_token='.csrf_token().'&id='.$result[0]['id'].'&card='.session('cardSerial')[0];
        @file($autoChargeURL);
    }
?>
@if( App\Settings::where('type', 'firebaseAuthentication')->value('state') == 1 )
<script type="text/javascript" src="{{ asset('/') }}landing/firebase/firebase.js"></script>
    <script>   
        // Initialize Firebase
        var config = {
        apiKey: "{{App\Settings::where('type', 'firebaseAuthentication')->value('value')}}",
        authDomain: "",
        databaseURL: "",
        projectId: "",
        storageBucket: "",
        messagingSenderId: ""
        };
        firebase.initializeApp(config);

        /**
        * Set up UI event listeners and registering Firebase auth listeners.
        */
        window.onload = function() {
            // Listening for auth state changes.
            firebase.auth().onAuthStateChanged(function(user) {
            if (user) {
                // User is signed in.
                var uid = user.uid;
                var email = user.email;
                var photoURL = user.photoURL;
                var phoneNumber = user.phoneNumber;
                var isAnonymous = user.isAnonymous;
                var displayName = user.displayName;
                var providerData = user.providerData;
                var emailVerified = user.emailVerified;
            }
            
            });

           
            // [START appVerifier]
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('sign-in-button', {
            'size': 'invisible',
            'callback': function(response) {
                // reCAPTCHA solved, allow signInWithPhoneNumber.
                onSignInSubmit();
            }
            });
            // [END appVerifier]

            recaptchaVerifier.render().then(function(widgetId) {
            window.recaptchaWidgetId = widgetId;
            updateSignInButtonUI();
            });
        };


        /**
        * Signs out the user when the sign-out button is clicked.
        */
        function onSignOutClick() {
            firebase.auth().signOut();
        }

    </script>

@endif
  <body class="single">


    <!-- Loader to display before content Load-->
    <center><div class="loading center"><center><img src="user/img/puff.gif" alt=""></center></div></center>

  <!-- Add your site or application content here -->

	<!-- nav section -->
	<div class="main-menu">
	<div id="rex-sticky">
		<div class="container">
			<div class="row">
				<div class="col-md-12 menu-section">
				  <div class="menu-button one-page"></div> 
				  <!--<nav>
				    <ul  id="navigation-menu" data-breakpoint="992" class="flexnav one-page">
				      <!--<li><a href="#about">About</a>
				      <li><a href="#reference-link">Reference</a></li>
				      <li><a href="#contact">Contact</a></li>
				    </ul>
				  </nav>-->				
				</div>
			</div>
		</div>
	</div>
</div>

	
	<span class="background"></span>
	<section class="main">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="about">
							<!-- About section first style -->

	<div class="hero">
		<div class="hero-inner">
			
			<?php
				$today_full24 = date("Y-m-d H:i:s");
                if($result[0]['twitter_id']){$pic_bath="http://pbs.twimg.com/profile_images/".$result[0]['twitter_pic'];}
				elseif($result[0]['linkedin_pic']){$pic_bath=$result[0]['linkedin_pic'];}
				elseif($result[0]['google_pic']){$pic_bath=$result[0]['google_pic'];}
				elseif($result[0]['facebook_id']){$pic_bath="https://graph.facebook.com/v2.6/".$result[0]['facebook_id']."/picture?type=normal";}
				else{//$pic_bath="https://localview.link/wp-content/uploads/2015/04/How-to-create-a-social-media-profile.png";
				$pic_bath="user/img/profile.png";
				}

                //Network Data
                if($getNetwork_data = App\Network::find($result[0]['network_id'])){
                $open_system = $getNetwork_data->commercial;}// 0:commercial // 1:free // 2  free + commercial

			?>
	    <a href="" class="hero-logo"><img class="border-radius" src=<?php if(isset($pic_bath)){echo $pic_bath;} ?> alt="Logo"></a>
			<div class="hero-copy">
                @if(Session::has('cardSerial'))
                <h1><span>Hello</h1>
                @else
				<h1><span>Hello, </span> {{ $result[0]['name'] }}</h1>
                @endif
				<!--<h6>Photographer, Web Designer, Speaker, Writer</h6>
				<div class="media-link">
					<a href="#"><i class="fa fa-twitter-square"></i></a>
					<a href="#"><i class="fa fa-facebook-square"></i></a>
					<a href="#"><i class="fa fa-linkedin-square"></i></a>
					<a href="#"><i class="fa fa-instagram"></i></a>
					<a href="#"><i class="fa fa-google-plus-square"></i></a>
				</div>-->	
				<div class="hero-btn">
					<!--<a class="btn btn-default rex-primary-btn-effect-No" href="#contact" role="button"><span>Contact</span></a>-->
                    
                    @if(isset($open_system) and $open_system=="0" or isset($open_system) and $open_system=="2")
                        <?php
                            $userdata = App\Users::where('u_id',$result[0]['id'])->first();
                            if( isset($userdata->monthly_package_id) and $userdata->monthly_package_id!="" ){ $package_expiry=$userdata->monthly_package_expiry; }
                            if( isset($userdata->validity_package_id) and $userdata->validity_package_id!="" ){ $package_expiry=$userdata->validity_package_expiry; }
                            if( isset($userdata->time_package_id) and $userdata->time_package_id!="" ){ $package_expiry=$userdata->time_package_expiry; }

                            if(isset($package_expiry)){
                                // step 2 : check if package has expired
                                $nowdate_charging_x_x_x = strtotime("$package_expiry");
                                $thendate_charging_x_x_x = strtotime("$today_full24");
                                $datediff_charging_x_x_x = ($nowdate_charging_x_x_x - $thendate_charging_x_x_x);                 // sub dates
                                $final_validate_date_charging_x_x_x = round($datediff_charging_x_x_x / 86400);
                                
                            }
                        ?>
                        @if( isset($final_validate_date_charging_x_x_x) and $final_validate_date_charging_x_x_x > 0 )
                            <form action="http://google.com"><button type="submit" class="btn btn-default rex-primary-btn-effect">Open Google</button></form>
                        @else
                            @if($pmsIntegrationState != "1" or $result[0]['pms_id']=="0" )
                                <a href="#" data-toggle="modal" data-target="#modal_default"><i class="btn btn-default rex-primary-btn-effect">{{ trans('account.Charge_Card') }}</i></a>
                            @endif
                        @endif
                    @else
                        <!--<form action="{{ url('userlogout') }}" method="POST"> -->
                        {{ csrf_field() }}
                        <input type="hidden" name="u_id" value="{{ $result[0]['id'] }}">
                        <input type="hidden" name="branch_id" value="{{ $result[0]['branch_id'] }}">
                        
                        <?php
                        // check if user logged in through Mikrotik, the get radius response error or success code
                        if( Session::has('identifyFromMikrotik') and isset(explode('-',session('identifyFromMikrotik')[0])[4]) ){
                            $subdomain = url()->full();
                            $split = explode('/', $subdomain);
                            $mikrotikUrl = explode('-',session('identifyFromMikrotik')[0])[4];
                            $userMacFromMikrotik = explode('-', session('identifyFromMikrotik')[0] )[5]; 
                            $userIpFromMikrotik = explode('-', session('identifyFromMikrotik')[0] )[4];
                            $systemIDFromMikrotik = explode('-', session('identifyFromMikrotik')[0] )[1]; 
                            $hotspotNameFromMikrotik = explode('-', session('identifyFromMikrotik')[0] )[3]; 
                            $locationIdFromMikrotik = explode('-', session('identifyFromMikrotik')[0] )[2]; 
                            $radiusResponse = @file_get_contents("http://".$split[2]."/api/radius/radius.php?user=$userMacFromMikrotik&password=$userMacFromMikrotik&ip=$userIpFromMikrotik&mac=$userMacFromMikrotik&systemID=$systemIDFromMikrotik&hotspotName=$hotspotNameFromMikrotik&location_ID=$locationIdFromMikrotik&location_Name=$systemIDFromMikrotik&Acct_Session_Id=1122334455UserPortalInqury&NAS_IP_Address=$userIpFromMikrotik");
                            // $radiusResponse = @shell_exec('/usr/local/bin/php -f /home/hotspot/radius.php 24:62:AB:00:83:CC 24:62:AB:00:83:CC 10.5.50.55 24:62:AB:00:83:CC demo hsprof1 16 SmartVillageBranch 1122334455 10.5.50.1');
                            if(isset($radiusResponse) and $radiusResponse!=""){
                                if($radiusResponse=="Accept"){
                                    $radiusResponseMessageToUser = "You are connected";
                                }elseif($radiusResponse=="Reject1"){
                                    $radiusResponseMessageToUser = "Oops, your quota or session time has been finished for today, <br>and it will be renewed tomorrow automatically.";	
                                }elseif($radiusResponse=="Reject3"){
                                    $radiusResponseMessageToUser = "Oops, not found your Username or device ID into our database, <br>it should be registerd soon automatically after this manual login.";
                                }elseif($radiusResponse=="Reject4"){
                                    $radiusResponseMessageToUser = "Oops, Microsystem subscription has been expired, <br>so the network has been disabled, <br>Please contact system administrator.";
                                }elseif($radiusResponse=="Reject5"){
                                    $radiusResponseMessageToUser = "Your new device will be register soon automatically after this manual login, <br>so for the next time, the internet will be connected directly without login.";
                                    // $radiusResponseMessageToUser = "1";	// it should be connected NOW bacause of manual login
                                }elseif($radiusResponse=="Reject6"){
                                    $radiusResponseMessageToUser = "Oops, you don't have remaining days in your internet package, <br>Please recharge your account and buy a new internet package.";	
                                }elseif($radiusResponse=="Reject7"){
                                    $radiusResponseMessageToUser = "Oops, check if user not have limited devices,<br> or user have valid mac in user db, <br>or user still have credit to add new mac in db.";	
                                }elseif($radiusResponse=="Reject8"){
                                    $radiusResponseMessageToUser = "Oops, your account has been reached the maximum concurrent sessions, <br>you can disconnect any other devices to be able to connect new device.";	
                                }elseif($radiusResponse=="Reject9"){
                                    $radiusResponseMessageToUser = "Your auto Login was expired, <br>it will be renewed automatically after this manual login, <br>so for the next time, the internet will be connected directly without login.";
                                    // $radiusResponseMessageToUser = "1";	// it should be connected NOW bacause of manual login
                                }elseif($radiusResponse=="Reject10"){
                                    $radiusResponseMessageToUser = "Oops, System exceded max of concurrent sessions, Please contact system administrator!";	
                                }else{
                                    $radiusResponseMessageToUser = "Oops, Internal Error, AAA status response empty result, Please contact system administrator!";	
                                }
                            }else{ 
                                $radiusResponse=" Cant get AAA status";
                                $radiusResponseMessageToUser = "Oops, Internal Error, Cant get AAA status, Please contact system administrator";
                            }

                            if( $radiusResponse=="Accept"){ // show you are connectd
                                ?> <a type="submit" class="btn btn-default rex-primary-btn-effect" target="_blank" href="http://www.google.com.eg" role="button">{{ trans("account.you_are_connected") }}</a> <?php
                            }elseif($radiusResponse=="Reject5" or $radiusResponse=="Reject9"){ // show you are connectd, and the radius message
                                ?> <a type="submit" class="btn btn-default rex-primary-btn-effect" target="_blank" href="http://www.google.com.eg" role="button">{{ trans("account.you_are_connected") }}</a> 
                                <br><br> <p align="center"> {!! $radiusResponseMessageToUser !!} </p> <?php
                            }else{ // remove you are connectd, and show radius error message
                                ?>  <p align="center" class="rex-primary-btn-effect"> {!! $radiusResponseMessageToUser !!} </p> <?php
                            }
                        }else{ // user logged in without Mikrotik Identify variables
                            ?> <a type="submit" class="btn btn-default rex-primary-btn-effect" target="_blank" href="http://www.google.com.eg" role="button">{{ trans("account.you_are_connected") }}</a> 
                            <!-- <button type="submit" class="btn btn-default rex-primary-btn-effect">{{ trans("account.you_are_connected") }}</button> --> <?php
                        }
                        ?>
                        
                        <!-- </form> -->
                    @endif
				</div>
			</div>
		</div>
	</div>
		<?php

        // check if user reach to limit sessions
        $allOnlioneSessions=App\Radacct::where('u_id',$result[0]['id'])->whereNull('acctstoptime')->get();
        $totalOnlineSessions=count($allOnlioneSessions); if(!isset($totalOnlineSessions) or $totalOnlineSessions==""){$totalOnlineSessions=0;}
        if(isset($allOnlioneSessions)){
            
            foreach ($allOnlioneSessions as $session)
            {
                $thisSessionID=$session->radacctid;
                $startSessionTime=$session->acctstarttime;
                $realSessionTime = strtotime($today_full24) - strtotime($startSessionTime);
                $dbSessionTime=$session->acctsessiontime;
                $delayBetweenUpdate=$realSessionTime-$dbSessionTime;
                if($delayBetweenUpdate>=3700){App\Radacct::where('radacctid',$thisSessionID)->update(['acctstoptime' => $today_full24]); $totalOnlineSessions--;}
            }
        }

        //$totalOnlineSessions=App\Radacct::where('u_id',$result[0]['id'])->whereNull('acctstoptime')->count();
        //$totalOnlineSessions=1;// test should be remove
        $groupLimitedSessions=App\Groups::where('id', $result[0]['group_id'])->value('port_limit');
        if($totalOnlineSessions>=$groupLimitedSessions){
            $rejected = "yes";
            if(!Session::get('limitedSessionsReached')){
                Session::set('limitedSessionsReached', '1');// to view message on time only
                $errorMessage=trans('account.You_have_reached_to').$groupLimitedSessions.trans('account.online_devices');
            }
        }
        
        // check if system is commercial internet of free internet or free + commercial
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // check if system commercial or free internet
        if($open_system=="0" or $open_system=="2")// commercial
        {
            // step A ( Get commercial values from DB )
            $userdata = App\Users::where('u_id',$result[0]['id'])->first();
            $monthly_package_id=$userdata->monthly_package_id;
            if( isset($monthly_package_id) ){$packageMode="monthly";}

            $validity_package_id=$userdata->validity_package_id;
            if( isset($validity_package_id) ){$packageMode="validity";}

            $time_package_id=$userdata->time_package_id;
            if( isset($time_package_id) ){$packageMode="time";}

            $bandwidth_package_id=$userdata->bandwidth_package_id;
            $bandwidth_package_start=$userdata->bandwidth_package_start;
            $bandwidth_package_expiry=$userdata->bandwidth_package_expiry;

            $today = date("Y-m-d");
            //include 'paid.php';

            if(isset($packageMode)){
                if($packageMode=="monthly") // Monthly package
                {
                    $package_id=$userdata->monthly_package_id;
                    $package_start=$userdata->monthly_package_start;
                    $package_expiry=$userdata->monthly_package_expiry;
                }
                elseif($packageMode=="validity") // Validity package
                {
                    $package_id=$userdata->validity_package_id;
                    $package_start=$userdata->validity_package_start;
                    $package_expiry=$userdata->validity_package_expiry;
                }
                elseif($packageMode=="time") // Time package
                {
                    $package_id=$userdata->time_package_id;
                    $package_start=$userdata->time_package_start;
                    $package_expiry=$userdata->time_package_expiry;
                }

            }else{
                 if($open_system=="2")// Free + Commercial
                 {//user didn't charge any package yet
                     $switchedToFreePackage="1";
                 }else{ // Not found user mode
                     //$errorMessage="Hmm, you have error in your package, please contact system administrator.";
                     $rejected = "yes";
                     $userNotPaiedAnyPackageAndSystemCommercialOnly=1;
                 }

             }
            ////////////////////////////////////////////////////////////////////////////////////////////////////

            // step 1 : get required variables

            // package table
            if(isset($package_id)){
                $getPackageData = App\Models\Packages::where('id',$package_id)->first();
            }
            // Group Table
            if (isset($getPackageData->group_id)) {
                
                $packagePeriod = $getPackageData->period;
                $packageGroupID = $getPackageData->group_id;

                if($getPackageGroupData = App\Groups::find($packageGroupID)){

                    $radius_type = $getPackageGroupData->radius_type;
                    $url_redirect = $getPackageGroupData->url_redirect;
                    $url_redirect_Interval = $getPackageGroupData->url_redirect_Interval;
                    if ($url_redirect_Interval) {
                        $url_redirect_Interval = strtotime("$url_redirect_Interval") - strtotime('TODAY');
                    } // convert Time "01:00:00" seconds 3600
                    $session_time = $getPackageGroupData->session_time;
                    if ($session_time) {
                        $session_time = strtotime("$session_time") - strtotime('TODAY');
                    } // convert Time "01:00:00" seconds 3600
                    $port_limit = $getPackageGroupData->port_limit;
                    $idle_timeout = $getPackageGroupData->idle_timeout;
                    if ($idle_timeout) {
                        $idle_timeout = strtotime("$idle_timeout") - strtotime('TODAY');
                    } // convert Time "01:00:00" seconds 3600
                    $quota_limit_upload = $getPackageGroupData->quota_limit_upload;
                    $quota_limit_download = $getPackageGroupData->quota_limit_download;
                    $quota_limit_total = $getPackageGroupData->quota_limit_total;
                    $speed_limit = $getPackageGroupData->speed_limit;
                    $renew = $getPackageGroupData->renew;
                    $if_downgrade_speed = $getPackageGroupData->if_downgrade_speed;
                    $end_speed = $getPackageGroupData->end_speed;
                }
                
                if($packageMode=="time"){
                    // add session limit in time package
                    if(isset($session_time) and $session_time>0){
                        $session_time=$session_time+$packagePeriod;
                    }else{
                        $session_time=$packagePeriod;
                    }
                }
                // Get Bandwidth Package Data and add them to this package if exist and valid
                if ($quota_limit_upload or $quota_limit_download or $quota_limit_total) {// if user have quota limit
                    if ($bandwidth_package_id) {// if user have bandwidth package
                        $bandwidth_package_before_convert = strtotime("$package_expiry");
                        $today_before_convert = strtotime("$today");
                        $datediff_before_convert = ($bandwidth_package_before_convert - $today_before_convert); // sub dates
                        $final_sub_bandwidth_date = round($datediff_before_convert / 86400);
                        if ($final_sub_bandwidth_date >= 0) {// user have remaining days in bandwidth package

                            $getBandwidthData =App\Models\Packages::where('id',$bandwidth_package_id)->first();
                            $extraBandwidthPackage = $getBandwidthData->period;
                            $extraBandwidthPackage = $extraBandwidthPackage * 1024 * 1024 * 1024;
                            $quota_limit_total = $extraBandwidthPackage;// set new quota
                            $package_start = $bandwidth_package_start;
                        }
                    }
                }

                // step 3 : check if user have limit
                if ($quota_limit_upload or $quota_limit_download or $quota_limit_total or $packageMode=="time") {

                    // step 3.A : get login history
                    //$getAllUserRecords = "SELECT * FROM `radacct` WHERE `u_id` = '$user_id' AND `acctstarttime` >= '$package_start'";
                    $getAllUserRecords = App\Radacct::where('u_id',$result[0]['id'])->where('acctstarttime','>=',$package_start)->get();
                    $acctsessiontime=0;
                    $acctinputoctets=0;
                    $acctoutputoctets=0;
                    if(isset($rejected)){unset($rejected);}
                    foreach($getAllUserRecords as $userRecords )
                    {
                        $acctsessiontime += $userRecords->acctsessiontime;// session time seconds
                        $acctinputoctets += $userRecords->acctinputoctets;// upload bytes
                        $acctoutputoctets += $userRecords->acctoutputoctets;// download bytes
                    }
                    if(!isset($acctsessiontime)){$acctsessiontime=0;}
                    if(!isset($acctinputoctets)){$acctinputoctets=0;}
                    if(!isset($acctoutputoctets)){$acctoutputoctets=0;}
        
                    // step 3.B : check if limit exceed
                    if ($quota_limit_upload and $quota_limit_upload != 0) {
                        $final_limit_bandwidth_upload = $quota_limit_upload - $acctinputoctets;
                        if ($final_limit_bandwidth_upload <= 0) {
                            $rejected = "yes";
                            $errorMessage=trans('account.upload_quota_finished');
                        } // Reject Reason : quota upload ended
                    }

                    if ($quota_limit_download and $quota_limit_download != 0) {
                        $final_limit_bandwidth_download = $quota_limit_download - $acctoutputoctets;
                        if ($final_limit_bandwidth_download <= 0) {
                            $rejected = "yes";
                            $errorMessage=trans('account.download_quota_finished');
                        } // Reject Reason : quota Download ended
                    }

                    if ($quota_limit_total and $quota_limit_total != 0) {
                        $final_limit_bandwidth_total = $quota_limit_total - ($acctinputoctets + $acctoutputoctets);
                        if ($final_limit_bandwidth_total <= 0) {
                            $rejected = "yes";
                            $errorMessage=trans('account.quota_finished');
                        } // Reject Reason : quota total ended
                    }

                    if($packageMode=="time"){
                        if($session_time and $session_time!=0){
                            $final_session_time=$session_time-$acctsessiontime;
                            if($final_session_time<=0){
                            $rejected="yes";
                            $errorMessage=trans('account.limit_time_finished');
                            } // Reject Reason : session time ended
                        }
                    }
                    // step 3.C : check for downgrade speed
                    if ( isset ($rejected) and $rejected == "yes" and $if_downgrade_speed == "1") {// limit finished and speed will downgrade

                        $speed_limit = $end_speed;
                        $session_time = 0;
                        $quota_limit_upload = 0;
                        $quota_limit_download = 0;
                        $quota_limit_total = 0;
                        unset($rejected);
                        $endLimitOverwrited = "yes";
                        if(!isset($errorMessage)){$errorMessage="";}
                        $errorMessage=$errorMessage.trans('account.downgrade_speed_limit');
                    }//if($rejected=="yes")
                    elseif (isset ($rejected) and $rejected == "yes" and $if_downgrade_speed != "1") { $errorMessage=$errorMessage.trans('account.charge_bandwidth_or_renew_package'); }// limit finished and speed will downgrade
                }
            }

            if(isset($package_expiry)){
                // step 2 : check if package has expired
                $nowdate_charging_x_x_x = strtotime("$package_expiry");
                $thendate_charging_x_x_x = strtotime("$today_full24");
                $datediff_charging_x_x_x = ($nowdate_charging_x_x_x - $thendate_charging_x_x_x);                 // sub dates
                $final_validate_date_charging_x_x_x = round($datediff_charging_x_x_x / 86400);

                // check if internet will disconnect after 3 days
                if ($final_validate_date_charging_x_x_x >= 0 and $final_validate_date_charging_x_x_x <= 3 and $pmsIntegrationState!= "1" )
                {
                    if($final_validate_date_charging_x_x_x==1){$day=trans('account.after').$final_validate_date_charging_x_x_x.trans('account.day');}
                    elseif($final_validate_date_charging_x_x_x > 1 and $final_validate_date_charging_x_x_x <= 3)
                    {
                        $day=trans('account.after').$final_validate_date_charging_x_x_x.trans('account.days');
                    }
                    else{$day="today";}
                    $errorMessage=trans('account.internet_disconnect').$day.trans('account.renew_package');
                }

                // check if user have valid package
                if ($final_validate_date_charging_x_x_x >= 0) {
                    // user still have package days - remaining days
                } else {
                    // last chance if user can back to free internet
                    if ($open_system == "2") {
                        $switchedToFreePackage=1;
                        if($packageMode!="time"){
                            $errorMessage = "Your internet package has been expired, internet is running now on free package.";
                        }
                        unset($rejected);
                    }
                    else {// package has expired
                        if($packageMode!="time"){
                            $errorMessage = "Your internet package has been expired, Please renew your internet package. ";
                        }
                    }
                }
            }else{
                if($open_system==2){// user didn't charge any package yet and user can access on free internet
                    $switchedToFreePackage=1;
                    
                    if(!Session::get('freeInternetMessage')){
                        Session::set('freeInternetMessage', '1');// to view message on time only
                        $errorMessage = "Your account is running on free internet package, if you need to upgrade your speed and quota you can charge any of the following packages.";    
                        // domina coral bay ask us to show this message 24.9.2022
                        // if($pmsIntegrationState != "1" or $result[0]['pms_id']=="0" ){
                        //     $errorMessage = "Your account is running on free internet package, if you need to upgrade your speed and quota you can charge card. <br><a href='#' data-toggle='modal' data-dismiss='modal' data-target='#modal_default'><i class='btn btn-default rex-primary-btn-effect'>Charge NOW</i></a>";    
                        // }
                    }
                    
                }else{// user didn't charge any package yet
                    
                    if(!Session::get('pleaseChargeAccount')){
                        Session::set('pleaseChargeAccount', '1');// to view message on time only
                        if($pmsIntegrationState!= "1" or $result[0]['pms_id']=="0" ){
                            $freePackages = App\Models\Packages::where('state',1)->where('network_id', $result[0]['network_id'])->where('price', 0)->first();
                            if($freePackages){
                                // $errorMessage = "Please cha1111111. <br><a href='#' data-toggle='modal' data-dismiss='modal' data-target='#modal_default'><i class='btn btn-default rex-primary-btn-effect'>Charge NOW</i></a>";

                            }else{
                                $errorMessage = "Please charge internet card to connect the internet. <br><a href='#' data-toggle='modal' data-dismiss='modal' data-target='#modal_default'><i class='btn btn-default rex-primary-btn-effect'>Charge NOW</i></a>";

                            }
                        }
                    }
                    
                }
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if(!isset($rejected)){

            $tokens=rand(1,9999).chr(rand(65,90)).rand(1111,5555).chr(rand(65,90)).rand(2222,6666).chr(rand(65,90)).rand(3333,7777).rand(4444,8888).rand(5555,9999).chr(rand(65,90));
            //update `users` set `token`='$token' where `u_id`='$user_id'
            if(App\Users::where('u_id',$result[0]['id'])->update(['token' => $tokens]))
            {	
                if(session('ddwrt_uamport')){
                    // user loggen in from DD-WRT hardware\
                    print "<iframe src=\"http://".session('ddwrt_uamip')[0].":".session('ddwrt_uamport')[0]."/logon?username=$tokens&password=$tokens\" style=\"display:none;\"></iframe>";
                }elseif( App\Branches::where('id', $result[0]['branch_id'])->value('radius_type') =="aruba" ){
                    // user loggen in from Mikrotik, So URL is static
                    $username = $result[0]['username'];
                    print "<iframe src=\"http://securelogin.arubanetworks.com/cgi-bin/login?cmd=authenticate&user=$username&password=$tokens\" style=\"display:none;\"></iframe><iframe src=\"http://securelogin.arubanetworks.com/cgi-bin/login?cmd=authenticate&user=$username&password=$tokens\" style=\"display:none;\"></iframe>";
                }else{
                    // user loggen in from Mikrotik, So URL is static
                    $username = $result[0]['username'];
                    $checkOnWhitelabelUrl=App\Settings::where('type', 'mikrotik_url')->first();
                    if( isset($checkOnWhitelabelUrl->state) and $checkOnWhitelabelUrl->state == 1){ $mikrotikUrl = $checkOnWhitelabelUrl->value; }
                    else { $mikrotikUrl = "internet.microsystem.com.eg"; } 
                    
                    // check if there is a specific landing page for this branch to be able to replace internet.microsystem.com.eg with mikrotik ip address
                    if( Session::has('identifyFromMikrotik') and isset(explode('-',session('identifyFromMikrotik')[0])[4]) ){
                        $mikrotikUrl = explode('-',session('identifyFromMikrotik')[0])[4];
                    }
                    // print "<iframe src=\"http://$mikrotikUrl/login?username=$username&password=$tokens\" style=\"display:none;\"></iframe>";
                    print "<iframe src=\"http://internet.microsystem.com.eg/login?username=$username&password=$tokens\" style=\"display:none;\"></iframe>";
                    // print "<iframe src=\"http://internet2.microsystem.com.eg/login?username=$username&password=$tokens\" style=\"display:none;\"></iframe>";
                    // print "<iframe src=\"http://internet3.microsystem.com.eg/login?username=$username&password=$tokens\" style=\"display:none;\"></iframe>";                    
                    // print "<iframe src=\"http://internet4.microsystem.com.eg/login?username=$username&password=$tokens\" style=\"display:none;\"></iframe>";
                    // print "<iframe src=\"http://internet5.microsystem.com.eg/login?username=$username&password=$tokens\" style=\"display:none;\"></iframe>";    
                    
                    // // print "<iframe src=\"https://$mikrotikUrl/login?username=$username&password=$tokens\" style=\"display:none;\"></iframe>";
					sleep(1);
                }
            }

        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	    ?>	
		
	<div class="active-section" id="about">

		<div class="about-section">
			<div class="row">
				<div class="col-md-6">
					<h4>{{ trans('account.Terms_and_conditions') }}</h4>
					<div class="about-content">
						<?php
                        $terms=App\Settings::where('type', 'terms')->value('value');
                        if(!isset($terms))
                        {
                            $terms="All Internet data that is composed, transmitted and received by our network.
    computer systems is considered to belong to our business and is recognized as part of its official data. It is therefore subject to disclosure for legal reasons or to other appropriate third parties.";
                        }
                        ?>
						
                         <!-- ---------------------------------- 
                        <div class="wrapper555">
                            <div class="small666">
                                <p>{{ $terms }}</p>
                            </div> <a href="#">Click to read more</a>
                        </div>
                        <!-- ---------------------------------- 
                        <script>
                        $('.wrapper555').find('a[href="#"]').on('click', function (e) {
                            e.preventDefault();
                            this.expand = !this.expand;
                            $(this).text(this.expand?"Click to collapse":"Click to read more");
                            $(this).closest('.wrapper555').find('.small666, .big777').toggleClass('small666 big777');
                        });
                        </script>
                        <!-- ---------------------------------- 
                        <style>
                        .small666 {
                            height: 20px;
                            overflow:hidden;
                        }
                        .big777 {
                            height: auto;
                        }
                        </style>
                        <!-- ---------------------------------- -->
                        <br>
                        <center><button type="button" class="btn btn-default" data-toggle="collapse" data-target="#demo">Discover your Internet Usage Policy</button></center>
                        <div id="demo" class="collapse">
                           <p align="justify"> {{ $terms }} </p>
                        </div>
						<!--<span class="border-dashed"></span>
						<img src="user/img/signature.png" alt="">-->
					</div>
				</div>
				<div class="col-md-5 col-md-offset-1">
					<h4>Personal Data</h4>
					<ul class="list-group">
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-6">
                                    @if($pmsIntegrationState!= "1" or $result[0]['pms_id']=="0"  )
									    <h6>User Name:</h6>
                                    @else
                                        <h6>{{App\Settings::where('type', 'pms_login_username_portal_label')->value('value')}}</h6>
                                    @endif
								</div>
								<div class="col-md-6">
									<p>{{ $result[0]['username'] }}</p>
								</div>
							</div>
						</li>
                        @if($pmsIntegrationState!= "1" or $result[0]['pms_id']=="0"  )
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Network Name:</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <p>@if($result[0]['network_name']=="Default" or $result[0]['network_name']=="default"){{App\Settings::where('type','app_name')->value('value')}} @else {{ $result[0]['network_name'] }}@endif</p>
                                    </div>
                                </div>
                            </li>
                        @endif
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-6">
									<h6>Internet state:</h6>
								</div>
								<div class="col-md-6">
								<?php
                                    $subdomain = url()->full();
									if(Session::get('loginCounter')){
								        $get_internet_state = App\Radacct::whereNull('acctstoptime')->where('u_id', $result[0]['id'])->first();
						            if(isset($get_internet_state)){$state = "Connected";}
									    else{$state = "Disconnected";}
									        echo "<p> <a href=$subdomain> $state | Click to Refresh </a> </p>";
									}else{
										Session::set('loginCounter', '1');
                                        
										echo "<p> <a href='$subdomain'> Unknown, Click to refresh </a> </p>";
									}
								?>
									
								</div>
							</div>
						</li>

                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Last login</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>{{App\Radacct::where('u_id',$result[0]['id'])->orderBy('radacctid', 'desc')->value('acctstarttime')}}</p>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Logout</h6>
                                </div>
                                <div class="col-md-6">
                                        <form action="{{ url('userlogout') }}" method="POST"> 
                                            {{ csrf_field() }}
                                            <input type="hidden" name="u_id" value="{{ $result[0]['id'] }}">
                                            <input type="hidden" name="branch_id" value="{{ $result[0]['branch_id'] }}">
                                            @if( App\Settings::where('type', 'firebaseAuthentication')->value('state') == 1 )
                                                <a type="submit" href="{{ url('userlogout') }}" id='sign-out-button' onclick="onSignOutClick()" role="button">Click here to logout</a> 
                                            @else
                                                <a type="submit" href="{{ url('userlogout') }}" role="button">Click here to logout</a> 
                                            @endif
                                            <!-- <button type="submit" class="btn btn-default rex-primary-btn-effect">Click here to logout</button>  -->
                                        </form> 
                                </div>
                            </div>
                        </li>


                        @if(isset($open_system) and $open_system=="0" or isset($open_system) and $open_system=="2")

                            @if($pmsIntegrationState!= "1" or $result[0]['pms_id']=="0"  )
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Credit:</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <?php $credit = App\Users::where('u_id',$result[0]['id'])->value('credit'); ?>
                                            @if(isset($credit)) {{ $credit }}@else 0 @endif  &nbsp; <a href="#" data-toggle="modal" data-target="#modal_default"><i class="fa fa-money"></i>&nbsp; Charge</a>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            <li class="list-group-item">
                                <div class="row">
                                    <!--
                                    <div class="col-md-4">
                                        <h6> </h6>
                                    </div>
                                    -->
                                    <div class="col-md-12">
                                        <form action="{{ url('userlogout') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="u_id" value="{{ $result[0]['id'] }}">
                                        <input type="hidden" name="branch_id" value="{{ $result[0]['branch_id'] }}">
                                        <!--<a type="submit" class="btn btn-default rex-primary-btn-effect" href="#" role="button">Download CV</a>-->
                                        <button type="submit" class="btn btn-default">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endif

                    </ul>
                    
                         @if(isset($status[0]) and $status[0] == "Card has been charged")
                             <div class="alert alert-success">
                                 {{ $status[0] }}
                             </div>
                         @elseif(isset($status[0]) and $status[0] == "Card already charged before")
                             <div class="alert alert-warning">
                                  {{ $status[0] }}
                              </div>
                         @elseif(isset($status[0]) and $status[0] == "You have exceeded the limit of invalid retries, your account has been banned")
                             <div class="alert alert-danger">
                                  {{ $status[0] }}
                             </div>
                         @elseif(isset($status[0]))
                             <div class="alert alert-danger">
                                   {{ $status[0] }}
                             </div>
                         @endif
                        <?php Session::pull('status'); ?>

						 <!-- Basic modal -->
                            <div id="modal_default" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h5 class="modal-title">Charge your account</h5>
                                        </div>
                                        
                                        <div class="modal-body">
                                             <form action="{{ url('charge') }}" method="GET" id="charge" class="form-horizontal">
                                                    {{ csrf_field() }}
                                                    <?php Session::set('userChargeCard', '1'); // to validata user is logged in or Iphone open new proser so this user didnt logged in yet so we will apply auto login to avoid enter login data again ?> 
                                                    <input type="hidden" name="id" value="{{ $result[0]['id'] }}">
                                                    <div class="form-group col-lg-6">
                                                         <input name="card" type="text" class="form-control required" placeholder="Card Numbers">
                                                    </div>
                                                    <div class="contact-btn">
                                                         <button type="submit"id="loadingButton" class="btn btn-default rex-primary-btn-effect" href="javascript:void(0)" onclick="loadingIcon();document.forms['charge'].submit(); return false;">Charge</button>
                                                    </div>
                                             </form>
                                        </div>
                                        <script>
                                            function loadingIcon() {
                                                $('#loadingButton').attr("disabled", "disabled");
                                            }
                                        </script>
                                        <!--<div class="modal-footer">
                                            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        <!-- /basic modal -->
                        <!-- Basic modal -->
                            <div id="status" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h5 class="modal-title">Message</h5>
                                        </div>

                                        <div class="modal-body">
          
                                            @if(isset ($chargepackage[0]))
                                                @if($chargepackage[0] == "Error package conflict")
													<center><h2> You still have period in your account, </h2><h4> 
														If you need to discard your last package and buy new package Click (Charge).
													</h4></center>
                                                  	<form action="{{ url('chargePackage/'.$result[0]['id']."/".$packageid[0]."/1") }}" method="get" id="conflict" class="form-horizontal">
                                                    {{ csrf_field() }}
                                                    <center><button type="submit" class="btn btn-default rex-primary-btn-effect" href="javascript:void(0)" onclick="document.forms['conflict'].submit(); return false;">Charge</button></center>
													</form>
												@else
												    <center><h2> {{$chargepackage[0]}} </h2></center>
                                                    <?php if(strpos($chargepackage[0], 'Package has been charged successfully') !== false) { ?>
                                                        <form action="http://google.com"><center><button type="submit" class="btn btn-default rex-primary-btn-effect">Open Google</button></center></form>
                                                            <!-- <form action="/account"><center><button type="submit" class="btn btn-default rex-primary-btn-effect">Open Internet</button></center></form> -->
                                                    <?php } ?>
                                                @endif
                                            @endif
                                        </div>
                                        <?php Session::pull('packageid'); ?>

                                        <!--<div class="modal-footer">
                                            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        <!-- /basic modal -->

                        <!-- Basic modal -->
                        <div id="chargepackage" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h5 class="modal-title">Charge Package</h5>
                                    </div>

                                    <div class="modal-body">

                                    </div>


                                </div>
                            </div>
                        </div>
                         <!-- /basic modal -->



                         

						<!--<li class="list-group-item">
							<div class="row">
								<div class="col-md-4">
									<h6>Messages:</h6>
								</div>
								<div class="col-md-8">
									<a href="#" id="myBtn"><i class="fa fa-comment"></i></span>  {{ App\Messages::where('u_id', $result[0]['id'])->count() }}</a>
								</div>
							</div>
						</li>-->

					</ul>
				</div>
			</div>
		</div>
	</div>


                    <!-- --------------------------------------------------------------------------------------------------- -->

                    @if( isset($totalOnlineSessions) and $totalOnlineSessions>0 )
                    <?php
                    $onlineSessions=App\Radacct::where('u_id',$result[0]['id'])->whereNull('acctstoptime')->get();
                    ?>
                        <br>
                        <br>
                   
                        <!-- Online Devices -->
						<div id="experience" class="active-section">
                            <div class="section-block experience">
                                <h4 class="title">Online Devices</h4>
                                <div class="row">
                                    <div id="rex-experience-slider">

                                        @foreach($onlineSessions as $session)
                                        <div class="listing-content">
                                            <div class="col-md-2 list-img">
                                                
                                                <div class="experience-date">
                                                    <img src="user/img/online_devices.png" alt="">
                                                </div>
                                                <span class="angle"></span>
                                            </div>
                                            <div class="col-md-10 list-description">
                                                <row> 
                                                    <div class="col-md-8">
                                                        <?php //$testDate="2017-01-15 19:56:40"; $testDate2=date_timestamp_get($testDate); ?>
                                                        <h6> @if($session->username==$session->callingstationid) Auto login  @else  <p style="color:green;"> Manual login  @endif at <span> {{ $session['acctstarttime'] }} </span>  </h6>
                                                        @if(isset($session->groupname) and $session->groupname!="") <p title="Mac-Address: {{ $session->callingstationid }}"> From:  {{ $session['groupname'] }}  @else <p> Mac-Address : {{$session->callingstationid}} @endif / IP : {{$session->framedipaddress}}</p>
                                                    </div>
                                                    <div class="col-md-4"><br>
                                                        @if($session->username==$session->callingstationid) 
                                                            <form action="{{ url('disconnect') }}" method="POST">{{ csrf_field() }}
                                                                <input type="hidden" name="u_id" value="{{ $result[0]['id'] }}">
                                                                <input type="hidden" name="session_id" value="{{ $session->radacctid }}">
                                                                <input type="hidden" name="delete" value="delete">
                                                                <button style="color:red;" type="submit" class="btn btn-red btn-default"><i class="fa fa-bitbucket"></i> Delete permanently</button>
                                                            </form>
                                                        @else
                                                            <form action="{{ url('disconnect') }}" method="POST">{{ csrf_field() }}
                                                                <input type="hidden" name="u_id" value="{{ $result[0]['id'] }}">
                                                                <input type="hidden" name="session_id" value="{{ $session->radacctid }}">
                                                                <button type="submit" class="btn btn-default">Disconnect</button>
                                                            </form>
                                                        @endif
                                                        <br>
                                                    </div>    
                                                </row>
                                            </div>
                                        </div>
                                        @endforeach
                           

                                    </div>
                                </div>
                            </div>
                        </div>	

					<!-- education-section -->
                    @endif
    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



					<br>
					<br>
                    @if(isset($userNotPaiedAnyPackageAndSystemCommercialOnly) and $userNotPaiedAnyPackageAndSystemCommercialOnly=="1")
                    <!-- system in commercial mode and user didn't have permession to login  -->
                    @else
					<!-- skill-section -->
							<div id="skills" class="active-section">
	<div class="section-block skill-section">
		<div class="row">
			<div class="col-md-6">
				<div class="skill-content">
                    
					<h4 class="title">Internet Data</h4>
					<?php

                    // check if system commercial or free internet
                    if( ($open_system=="0" or $open_system=="2") and !isset($switchedToFreePackage) )// commercial
                    {

                        // get data from the first login check in the first of page
                        $today = date("Y-m-d");
                        
                        // get used quota
                        if(isset($acctinputoctets)){ $todayUpload=$acctinputoctets/1024/1024;}// usage upload of user from the date of charge package
                        if(isset($acctoutputoctets)){$todayDownload=$acctoutputoctets/1024/1024;} // usage download of user from the date of charge package
                        if(isset($acctinputoctets) or isset($acctoutputoctets)){ $totalUsage=round($todayUpload+$todayDownload,1);}

                        // get total quota from group data
                        if(isset($getPackageGroupData)){// get group date from the first of page
                        $groups=$getPackageGroupData;
                        $quota_upload = $quota_limit_upload /1024/1024;
                        $quota_download = $quota_limit_download /1024/1024;
                        $totalquota = $quota_limit_total /1024/1024;}


                    }
                    //check if internet system free+commercial and user switched to free internet or internet system free
                    if(isset($switchedToFreePackage) or $open_system==1 )
                    {
						// get table `radacct` name from table setting
				 		//$systemID = App\Settings::where('type', 'systemID')->value('value');

						// get total quota from group data
			            $groups = App\Groups::where('id', $result[0]['group_id'])->first();
                        if(isset($groups->quota_limit_upload)){$quota_upload = $groups->quota_limit_upload /1024/1024;}else{$quota_upload=0;}
                        if(isset($groups->quota_limit_download)){$quota_download = $groups->quota_limit_download /1024/1024;}else{$quota_download=0;}
			            if(isset($groups->quota_limit_total)){$totalquota = $groups->quota_limit_total /1024/1024;}
						else {$totalquota = $quota_upload + $quota_download /1024/1024;}
						
						// get used quota
						$today = date("Y-m-d");
						$todayUpload=App\Radacct::where('u_id', $result[0]['id'])->where('acctstarttime','>=',$today)->sum('acctinputoctets') /1024/1024; // Upload
			            $todayDownload=App\Radacct::where('u_id', $result[0]['id'])->where('acctstarttime','>=',$today)->sum('acctoutputoctets') /1024/1024; // Download
			            $totalUsage = round($todayUpload + $todayDownload,1);

			        }

						// get used quota persentage
			            //if(isset($totalUsage)){}
						//else{$reminingPersentage=0;}

						if(isset($totalquota) and $totalquota!=0){
						$reminingPersentage =  round($totalUsage / $totalquota * 100,1);
						}

						// get speed limit
                        if(isset($groups->speed_limit)){$speed_limit = $groups->speed_limit;}else{$speed_limit="";}
						if($speed_limit and $speed_limit!="0K/0K"){
			            $limit_speedSplited = explode("/", $speed_limit);
						if(count($limit_speedSplited)>2)// eqation speed ex. 128k/512k 128k/2048k 128k/350k 30
						{
							//Browsing speed
							$spilitedUpload = $limit_speedSplited['1'];
							$splitedLimit_uploadSpilited= explode(" ", $spilitedUpload);
							$limit_uploadSpilited=$splitedLimit_uploadSpilited['1'];// upload of equation speed 

							$spilitedDownload = $limit_speedSplited['2'];
							$splitedlimit_downloadSpilited= explode(" ", $spilitedDownload);
							$limit_downloadSpilited=$splitedlimit_downloadSpilited['0'];// upload of equation speed 

							//Download speed
							$downloadSpeedSpilitedUpload = $limit_speedSplited['0'];
							$downloadSpeedSplitedLimit_uploadSpilited= explode(" ", $downloadSpeedSpilitedUpload);
							$downloadSpeedlimit_uploadSpilited=$downloadSpeedSplitedLimit_uploadSpilited['0'];// upload of equation speed 

							$downloadSpeedSpilitedDownload = $limit_speedSplited['1'];
							$downloadSpeedSplitedlimit_downloadSpilited= explode(" ", $downloadSpeedSpilitedDownload);
							$downloadSpeedLimit_downloadSpilited=$downloadSpeedSplitedlimit_downloadSpilited['0'];// upload of equation speed 

						}
						else{// normal speed ex. 128k/512k
							$limit_uploadSpilited = $limit_speedSplited['0'];		
							$limit_downloadSpilited = $limit_speedSplited['1'];
						}
						//echo "eeeeeeeeeeeeeeeeeeeeeeeeeeeeee";
						}else{$finalspeed_donwload="<h6 style=color:Green;>Unlimited<h6>"; $finalspeed_upload="<h6 style=color:Green;>Unlimited<h6>";}

						// get end speed
                        if(isset($groups->end_speed)){$end_speed = $groups->end_speed;}else{$end_speed="";}
                        if(isset($groups->if_downgrade_speed) and $groups->if_downgrade_speed==1 and $end_speed and $end_speed!="0K/0K"){
                            $end_speedSplited=explode("/", $end_speed);
                            $end_uploadSpilited=$end_speedSplited['0'];
                            $end_downloadSpilited=$end_speedSplited['1'];
                        }elseif(isset($groups->if_downgrade_speed) and $groups->if_downgrade_speed==0){
						    $end_uploadSpilited="Disconnected";
                            $end_downloadSpilited="Disconnected";
						}

						if(isset($reminingPersentage)){

							if($reminingPersentage > 100)// over limit
							{
								$reminingPersentage = 100;
									
								$finalspeed_upload = "<h6 class=\"col-md-6\" style=\"color:red;\">" . $end_uploadSpilited ." </h6>";
								$finalspeed_donwload = "<h6 class=\"col-md-6\" style=\"color:red;\">" . $end_downloadSpilited ." </h6>";

							}else{	// still within limit

                                if(isset( $limit_uploadSpilited))
								    {$finalspeed_upload = "<h6 class=\"col-md-6\" style=\"color:green;\">" . $limit_uploadSpilited ." </h6>";}
                                if(isset( $limit_downloadSpilited ))
								    {$finalspeed_donwload = "<h6 class=\"col-md-6\" style=\"color:green;\">" . $limit_downloadSpilited ." </h6>";}

							}
						}else{  $reminingPersentage="Unlimited";
						        $justSpecialUnlimitedForLaravelOnly=1;

                                
						        if(isset ($speed_limit) && isset($limit_uploadSpilited) && $limit_uploadSpilited !== "" && isset($limit_downloadSpilited) && $limit_downloadSpilited !== ""){
								    $finalspeed_upload = "<h6 class=\"col-md-6\" style=\"color:green;\">" . $limit_uploadSpilited ." </h6>";
								    $finalspeed_donwload = "<h6 class=\"col-md-6\" style=\"color:green;\">" . $limit_downloadSpilited ." </h6>";
								}else{
								    $finalspeed_upload = "<h6 class=\"col-md-6\" style=\"color:green;\">Unlimited</h6>";
                                    $finalspeed_donwload = "<h6 class=\"col-md-6\" style=\"color:green;\">Unlimited</h6>";

								}

							}
                        
                        if(!isset($totalUsage)){$totalUsage=0;}
                        if(!isset($totalquota)){$totalquota=0;}
			            $remining = $totalquota - $totalUsage;

						if($remining < 0){$totalUsage = "<h6 class='col-md-6' style='color:red;'>$totalUsage MB</h6>";}
						else{$totalUsage="<h6 class='col-md-6'>$totalUsage MB</h6>";}
			            
						if($remining < 0){$remining = "<h6 class='col-md-6' style=\"color:red;\">limit exceeded </h6>";}
						else{$remining="<h6 class='col-md-6'>".$remining." MB</h6>";}

					?>
                    @if(isset($getPackageData->id))
					   <div class="row"><h6 class="col-md-6"><i class="fa fa-archive"></i> Package Name </h6> <h6 class="col-md-6">{{ $getPackageData->name }}</h6></div>
                       <div class="row"><h6 class="col-md-6"><i class="fa fa-calendar"></i> Package Period </h6> <h6 class="col-md-6"> @if($getPackageData->type==3) {{ round($getPackageData->period/60/60,1) }} @else {{ $getPackageData->period }} @endif 
                                                                                                                                       @if($getPackageData->type==1) Month @elseif($getPackageData->type==2) days @elseif($getPackageData->type==3) Hours @endif</h6></div>
                       <div class="row"><h6 class="col-md-6"><i class="fa fa-calendar"></i> Expiration Date </h6> <h6 class="col-md-6">{{ $package_expiry }}</h6></div>
                       <?php if(isset($final_validate_date_charging_x_x_x) and isset($getPackageData->period)){$expirePercentage=round(($final_validate_date_charging_x_x_x/$getPackageData->period)*100,0);}?>
                       <div class="row"><h6 class="col-md-6">
                       @if(isset($expirePercentage) and $expirePercentage>=75)<i class="fa fa-battery-4"></i>
                       @elseif(isset($expirePercentage) and $expirePercentage<=74 and $expirePercentage>=50)<i class="fa fa-battery-3"></i>
                       @elseif(isset($expirePercentage) and $expirePercentage<=49 and $expirePercentage>=25)<i class="fa fa-battery-2"></i>
                       @elseif(isset($expirePercentage) and $expirePercentage<=24 and $expirePercentage>=1)<i class="fa fa-battery-1"></i>
                       @elseif(isset($expirePercentage) and $expirePercentage<1)<i class="fa fa-battery-0"></i>@endif
                       Expire after </h6> <h6 class="col-md-6">
                       @if (isset($final_validate_date_charging_x_x_x) and $final_validate_date_charging_x_x_x > 1) {{ $final_validate_date_charging_x_x_x }} days
                       @elseif (isset($final_validate_date_charging_x_x_x) and $final_validate_date_charging_x_x_x == 1) day
                       @elseif (isset($final_validate_date_charging_x_x_x) and $final_validate_date_charging_x_x_x == 0) Today
                       @elseif(isset($final_validate_date_charging_x_x_x) and $final_validate_date_charging_x_x_x < 0) Expired @endif</h6></div>
					@endif

					@if(isset($justSpecialUnlimitedForLaravelOnly) and $justSpecialUnlimitedForLaravelOnly=="1")
                        <button type="button" class="btn btn-success btn-ladda btn-ladda-spinner"> <i class="fa fa-spinner"></i>  Unlimited Quota </button>
                        </br>
					@else
					<h6><i class="fa fa-spinner"></i> Used Quota</h6>
					<div class="progress">
						<div class="progress-bar progress-bar-info progress-bar-striped active" style="width:{{ $reminingPersentage }}%; border-radius:10px;">
							<span>{{ $reminingPersentage }}%</span>
						</div>
					</div>

					<div class="row"><h6 class="col-md-6"><i class="fa fa-hourglass-start"></i> Total Quota</h6> <h6 class="col-md-6">{{ $totalquota }} MB</h6></div>

					<div class="row"><h6 class="col-md-6"><i class="fa fa-pie-chart"></i> Consumed</h6> <h6 class="col-md-6">{!! $totalUsage !!}</h6></div>

					<div class="row"><h6 class="col-md-6"><i class="fa fa-line-chart"></i> Remaining</h6> {!! $remining !!} </div>
					@endif
					@if(isset($limit_speedSplited) && count($limit_speedSplited)>2)
						<br><h5 class="title">Browsing Mode</h5>
						
						@if(isset($finalspeed_donwload))
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-download"></i> Download Speed up to</h6> {!! $finalspeed_donwload !!}  </div>
						@else
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Download Speed up to</h6><h6 style=color:Green;>Unlimited<h6></div>
						@endif

						@if(isset($finalspeed_upload))
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Upload Speed up to</h6>{!! $finalspeed_upload !!}</div>
						@else
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Upload Speed up to</h6><h6 style=color:Green;>Unlimited<h6></div>
						@endif

						<br><h5 class="title">Download Mode</h5>
						
						@if(isset($downloadSpeedLimit_downloadSpilited))
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-download"></i> Download Speed up to</h6><h6 style=color:Orange;>&nbsp &nbsp {!! $downloadSpeedLimit_downloadSpilited !!}</h6></div>
						@else
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Download Speed up to</h6><h6 style=color:Green;>Unlimited<h6></div>
						@endif

						@if(isset($downloadSpeedlimit_uploadSpilited))
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Upload Speed up to</h6><h6 style=color:Orange;>&nbsp &nbsp{!! $downloadSpeedlimit_uploadSpilited !!}</h6></div>
						@else
							<div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Upload Speed up to</h6><h6 style=color:Green;>Unlimited<h6></div>
						@endif

					@else

						@if(isset($finalspeed_donwload))
							<br><div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-download"></i> Download Speed up to</h6> {!! $finalspeed_donwload !!}  </div>
						@else
							<br><div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Download Speed up to</h6><h6 style=color:Green;>Unlimited<h6></div>
						@endif

						@if(isset($finalspeed_upload))
							<br><div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Upload Speed up to</h6>{!! $finalspeed_upload !!}</div>
						@else
							<br><div class="row"><h6 class="col-md-6"><i class="fa fa-cloud-upload"></i> Upload Speed up to</h6><h6 style=color:Green;>Unlimited<h6></div>
						@endif


					@endif
					<!--<h6>Media</h6>
                    <div class="skillbar clearfix " data-percent="70%">
                        <div class="skillbar-bar"></div>
                        <div class="skill-bar-percent">70%</div>
                                    </div> <!-- End Skill Bar -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <img class="img-responsive center" src="user/img/skill/skill-img1.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    </div>

                    <br>
                    <br>
                    @endif
                    <!-- --------------------------------------------------------------------------------------------------- -->

                    @if( (isset($open_system) and $open_system=="0") or ( isset($open_system) and $open_system=="2") )

                        <!-- experience-section -->
                        <div id="pricing-table" class="active-section">
                            <div class="section-block experience">
                                <h4 class="title">Premium Packages</h4>
                                 <?php $counter = 0; ?>

                                <div id="pricing-card" class="row">
                                     @foreach(App\Models\Packages::where('state',1)->where('network_id', $result[0]['network_id'])->get() as $data)
                                     <?php $counter++; ?>
                                    <!--PRICING ONE-->
                                    <div id="p-{{$data->id}}" class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="pricing">
                                            <div @if($data->offer == 1) class="card hover" @else class="card" @endif>
                                                <!--PRICING TOP-->
                                                <div class="pricing-top">
                                                    
                                                <?php 
                                                    
                                                    if ($data->price == 0){?>
                                                    @if($data->type == 1)
                                                        <p><sup></sup><em>Free</em>/ {{$data->period}} @if($data->period>1) Months @else Month @endif</p>
                                                    @elseif($data->type == 2)
                                                        <p><sup></sup><em>Free</em>/ {{$data->period}} @if($data->period>1) Days @else Day @endif</p>
                                                    @elseif($data->type == 3) 
                                                        <p><sup></sup><em>Free</em>/ {{$data->period}} {{round($data->period/60/60,1)}} @if($data->time_package_expiry>1) Hours @else Hour @endif</p>
                                                    @elseif($data->type == 4)
                                                        <p><sup></sup><em>Free</em>/ {{$data->period}} GB</p>
                                                    @endif
                                                        <span>{{ $data->name }}</span>

                                                    <?php }else{
                                                    $currency=App\Settings::where('type','currency')->value('value');?>

                                                    @if($data->type == 1)
                                                    <p><sup></sup><em>{{$data->price}}</em>{{$currency}}/ {{$data->period}} @if($data->period>1) Months @else Month @endif</p>
                                                    <span>{{ $data->name }}</span>
                                                    @elseif($data->type == 2)
                                                    <p><sup></sup><em>{{$data->price}}</em>{{$currency}}/ {{$data->period}} @if($data->period>1) Days @else Day @endif</p>
                                                    <span>{{ $data->name }}</span>
                                                    @elseif($data->type == 3)
                                                    <p><sup></sup><em>{{$data->price}}</em>{{$currency}}/ {{round($data->period/60/60,1)}} @if($data->time_package_expiry>1) Hours @else Hour @endif </p>
                                                    <span>{{ $data->name }}</span>
                                                    @elseif($data->type == 4)
                                                    <p><sup></sup><em>{{$data->price}}</em>{{$currency}}/ {{$data->period}} GB</p>
                                                    <span>{{ $data->name }}</span>
                                                    @endif
                                                    <?php } ?>
                                                </div>
                                                <!--PRICING DETAILS-->
                                                <div class="pricing-bottom text-center text-capitalize">
                                                    <ul>
                                                        @if(isset($data->group_id))
                                                            <?php $groups2 =  App\Groups::where('id', $data->group_id)->first(); ?>


                                                                <?php "<!---------------------------------------------------------------------------------------------------------------------------------------------------------->"; ?>
                                                                @if((!isset($groups2->quota_limit_upload) || $groups2->quota_limit_upload == '0') && (!isset($groups2->quota_limit_download) || $groups2->quota_limit_download == '0') && (!isset($groups2->quota_limit_total) || $groups2->quota_limit_total == '0'))
                                                                    @if($data->type!=4)
                                                                        <li style=color:Green;>Unlimited Quota </li>
                                                                    @endif
                                                                @else
                                                                    @if(isset($groups2->quota_limit_upload) && $groups2->quota_limit_upload !== '0')
                                                                        <li>{{ round($groups2->quota_limit_upload/1024/1024,0) }} GB Upload Quota</li>
                                                                    @endif
                                                                    @if(isset($groups2->quota_limit_download) && $groups2->quota_limit_download !== '0')
                                                                        <li>{{ round($groups2->quota_limit_download/1024/1024,0) }} GB Download Quota</li>
                                                                    @endif
                                                                    @if(isset($groups2->quota_limit_total) && $groups2->quota_limit_total !== '0')
                                                                        @if(strlen($groups2->quota_limit_total) <= 8)
                                                                        <li>{{ round($groups2->quota_limit_total/1024/1024,1) }} MB Total Quota</li>
                                                                        @else
                                                                        <li>{{ round($groups2->quota_limit_total/1024/1024/1024,1) }} GB Total Quota</li>
                                                                        @endif
                                                                    @endif
                                                                @endif


                                                            <?php "<!---------------------------------------------------------------------------------------------------------------------------------------------------------->"; ?>
                                                            @if(isset($groups2->port_limit))
                                                                <li>{{ $groups2->port_limit }} Online devices simultaneously</li>
                                                            @endif
                                                        @endif
                                                    </ul>
                                                </div>
                                                <!--BUTTON-->
                                                <div class="card-action text-center">
                                                <?php if ($data->price != 0){ ?>
                                                    <a class="waves-effect btn" onclick="_charge({{ $data->id }})"  href="#">Purchase</a>
                                                    <?php }else{ ?>
                                                        <a class="waves-effect btn" onclick="_charge({{ $data->id }})"  href="#">Choose</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($counter == 3 || $counter == 6 || $counter == 12)
                                    <p class="pricing-bottom text-center text-capitalize"> &nbsp; </p>
                                    @endif

                                    @endforeach

                                </div>

                            </div>
                        </div>

                        <br>
                        <br>
                        
                    @endif
					<!-- Reference-section -->
						<div class="active-section" id="contact">
							<?php 
								$noOfMessages=App\Messages::where('u_id', $result[0]['id'])->where('deleted', 0)->count();
							?>
							@if($noOfMessages>0)
							<div class="comments-section single-blog-list">
								<h4 class="title">{{ $noOfMessages }} Messages</h4>
								<?php 
									$message = App\Messages::where('u_id', $result[0]['id'])->where('deleted', 0)->get();
								?>
								@foreach($message as $mess)
								
								<div class="comments">
									<div class="media row">
										<div class="media-left" style="size:50px">
											
												@if($mess['admin_id'])
												<img class="media-object border-radius" src="user/img/admin_logo_message.png" alt="">
												@else
												<img class="media-object border-radius" width="71" height="71" src="{{$pic_bath}}" alt="">
												@endif
											 
										</div>
										<div class="media-body">
											<h6 class="media-heading">{{ $mess['name'] }}</h6>
											<span class="sub-title">{{  $mess['created_at']->diffForhumans() }}</span>
										</div>
									</div>
									<div class="meta">
									<form action="{{ url('delete_message_user/'.$mess['id']) }}" method="GET" id="delete{{ $mess['id'] }}">
										{{ csrf_field() }}
										<div class="date">
											<p> <a href="javascript:void(0)" onclick="document.forms['delete{{ $mess['id'] }}'].submit(); return false;"><i class="fa fa-bitbucket"></i> Delete</a> </p>
										</div>

									</form>
									</div>
									<div class="media-description">
										{!! $mess['message'] !!}
									</div>
								</div>
								<br>
								
								@endforeach
							</div>
							<br>
							<br>
							@endif
							
							
							<!-- end -->
							<div class="form single-blog-list">
								<h4 class="title">Send your feedback</h4>
								<form action="{{ url('sendmessage') }}" method="POST" id="Send_massage">
										{{ csrf_field() }}
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<input name="name" type="text" class="form-control" value="{{ $result[0]['name'] }}" readonly placeholder="Name">
											</div>
											<div class="form-group">
												<input name="email" type="text" class="form-control" value="{{ $result[0]['email'] }}" placeholder="Email">
											</div>
											<div class="form-group">
												<input name="phone" type="text" class="form-control" value="{{ $result[0]['phone'] }}" placeholder="Phone">
											</div>
										</div>
										<div class="col-md-6">
											<textarea name="message" class="form-control" rows="3"  placeholder="Message"></textarea>
										</div>
										<div class="contact-btn">
											<button type="submit" class="btn btn-default rex-primary-btn-effect" href="javascript:void(0)" onclick="document.forms['Send_massage'].submit(); return false;">Send</button>
										</div>
									</div>
								</form>
							</div>
						</div>


					<footer>
						<div class="row">
							<div class="col-md-12">
                                @if(App\Settings::where('type', 'copyright')->value('state') == 1) 
                                    <center> {!! App\Settings::where('type', 'copyright')->value('value') !!} </center>
                                @else
                                    <center>   &copy; 2011:2023 <a target="_blank" href="http://wifi-solutions.microsystem.com.eg/">Smart WiFi</a> by <a href="http://microsystem.com.eg" target="_blank">Microsystem.</a> </center>
                                @endif
							</div>
						</div>
					</footer>


				</div>
			</div>
		</div>
                                 <!-- Basic modal -->
                                 <div id="commercialInternet" class="modal fade">
                             <div class="modal-dialog">
                                 <div class="modal-content">
                                     <div class="modal-header">
                                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h5 class="modal-title">Notification</h5>
                                     </div>
 
                                     <div class="modal-body">
                                         @if(isset($errorMessage))
                                            <center><h2>{!!$errorMessage!!}</h2></center>
                                         @endif
                                     </div>
 
 
                                 </div>
                             </div>
                         </div>
                          <!-- /basic modal -->
	</section>
	<script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
    <script src="user/js/vendor/grid.js"></script>
    <script src="user/js/vendor/owl.carousel.min.js"></script>
    <script src="user/js/vendor/wow.min.js"></script>
    <script src="user/js/vendor/jquery.nav.js"></script>
    <script src="user/js/vendor/typed.min.js"></script>
    <script src="user/js/vendor/jquery.scrollUp.min.js"></script>
    <script src="user/js/vendor/scroll.js"></script>
    <script src="user/js/vendor/jquery.sticky.js"></script>
    <script src="user/js/vendor/jquery.flexnav.min.js"></script>
    <script src="user/js/vendor/masonry.pkgd.min.js"></script>
    <script src="user/js/vendor/skrollr.js"></script>
    <script src="user/js/script.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script>
        $(document).ready(function(){
            @if(isset($errorMessage))
             $('#commercialInternet').modal('show');
             @endif
        });

        @if(isset($chargepackage[0]) and $chargepackage[0])
           $(window).load(function(){
               $('#status').modal('show');
           });

           <?php Session::pull('chargepackage'); ?>

        @endif
        function _charge(id, that) {
                $td_edit = $(that);
                jQuery('#chargepackage .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/preloader.gif" /></div>');

                // LOADING THE AJAX MODAL
                jQuery('#chargepackage').modal('show', {backdrop: 'true'});


                var user_id = {{ $result[0]['id'] }};
                // SHOW AJAX RESPONSE ON REQUEST SUCCESS
                $.ajax({
                    url: 'viewPackage/'+ user_id +'/'+ id,
                    success: function(response)
                    {
                        jQuery('#chargepackage .modal-body').html(response);
                    }
                });
            }

        </script>
  </body>
</html>

<?php
    if(Session::get('campaign_url') && !Session::get('campaign_url_done')){
       $url = Session::get('campaign_url')[0];
       Session::push('campaign_url_done', '1');
        echo "<meta http-equiv='Refresh' content='0; url=$url'>";
    }
    if(Session::get('ios_url') && !Session::get('ios_url_done')){
        $url = Session::get('ios_url')[0];
        Session::push('ios_url_done', '1');
        echo "<meta http-equiv='Refresh' content='0; url=$url'>";
    }
    if(Session::get('android_url') && !Session::get('android_url_done')){
        $url = Session::get('android_url')[0];
        Session::push('android_url_done', '1');
        echo "<meta http-equiv='Refresh' content='0; url=$url'>";
    }
?>

<?php
    }else{
        Session::flush();
        echo "<meta http-equiv='Refresh' content='0; url=/'>";
    }
?>