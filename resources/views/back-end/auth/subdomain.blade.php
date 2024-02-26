        <?php
        if( isset($_GET['serial']) )
        {
            $customerID = DB::table('serials')->where('serial',$_GET['serial'])->value('customer_id');
            if(isset($customerID)){
                $customerData = DB::table('customers')->where('id',$customerID)->first();
                echo "<meta http-equiv='refresh' content='0; url=http://$customerData->url/login?email=$customerData->admin_username' />";
            }
            
        }

        require_once '../config.php';
        $subdomain = url()->full();
        $hostedDomain = explode('/', $subdomain);
        
        $hostedDomain = explode('/', url()->full()); 
        //$hostedDomainWithoutDots = explode('.', $hostedDomain[2]);
        
        if( isset($_GET['status']) && !Session::has('AccountkitFullMobile') ){
            if($_GET['status']=="PARTIALLY_AUTHENTICATED"){

                // switch from AccountKit to Whatsapp Verification code
                /*
                $facebook_app_id = $accountKitAppID4installation;
                $app_secret = $accountKitAppSecret4installation;
                $authorization_code = $_GET['code'];
                $firstURLcontent = @file_get_contents("https://graph.accountkit.com/v1.2/access_token?grant_type=authorization_code&code=$authorization_code&access_token=AA|$facebook_app_id|$app_secret");
                $stepA = json_decode($firstURLcontent);
                if(isset($stepA->access_token)){

                    $gettedAccessToken = $stepA->access_token;
                    $stepB = json_decode(@file_get_contents("https://graph.accountkit.com/v1.2/me/?access_token=$gettedAccessToken"));
                    //print_r($stepB);
                    $finalMobile = $stepB->phone->country_prefix.$stepB->phone->national_number;
                    $mobileWithoutCountryCode = $stepB->phone->national_number;
                    if($stepB->phone->country_prefix == "20"){ $mobileWithoutCountryCode = "0".$mobileWithoutCountryCode; }
                    Session::push('AccountkitFullMobile', $finalMobile); 
                    Session::push('AccountkitCountryCode', $stepB->phone->country_prefix); 
                    Session::push('mobileWithoutCountryCode', $mobileWithoutCountryCode);
                }
                */
                if( $_GET['verificationCode'] == session('install_verificationCode')[0] ){
                    // valid code, so we will simulate as accountkit is working
                    $finalMobile = session('install_1stCountryCode')[0].session('install_1stPhoneNumber')[0];
                    Session::push('AccountkitFullMobile', $finalMobile); 
                    Session::push('AccountkitCountryCode', session('install_1stCountryCode')[0]); 
                    Session::push('mobileWithoutCountryCode', session('install_1stPhoneNumber')[0]);
                }
            }
        }
        ?>
        @if( Session::has('AccountkitFullMobile') and Session::has('install_subdomain') and DB::table('customers')->where('database',session('install_subdomain')[0])->count() == 0 )
        
        
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

                <meta name="csrf-token" content="{{ csrf_token() }}" />
                
                <!-- http://davidbcalhoun.com/2010/viewport-metatag -->
                <meta name="HandheldFriendly" content="True">
                <meta name="MobileOptimized" content="320">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

                <!-- For all browsers -->
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/reset3860.css?v=1">
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/style3860.css?v=1">
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/colors3860.css?v=1">
                <link rel="stylesheet" media="print" href="{{ asset('/') }}assets/wizard/css/print3860.css?v=1">
                <!-- For progressively larger displays -->
                <link rel="stylesheet" media="only all and (min-width: 480px)" href="{{ asset('/') }}assets/wizard/css/4803860.css?v=1">
                <link rel="stylesheet" media="only all and (min-width: 768px)" href="{{ asset('/') }}assets/wizard/css/7683860.css?v=1">
                <link rel="stylesheet" media="only all and (min-width: 992px)" href="{{ asset('/') }}assets/wizard/css/9923860.css?v=1">
                <link rel="stylesheet" media="only all and (min-width: 1200px)" href="{{ asset('/') }}assets/wizard/css/12003860.css?v=1">
                <!-- For Retina displays -->
                <link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="{{ asset('/') }}assets/wizard/css/2x3860.css?v=1">

                <!-- Webfonts <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>-->
                <!-- <style type="text/css">
                    @font-face {
                    font-family: 'Open Sans';
                    font-style: normal;
                    font-weight: 300;
                    src: local('Open Sans Light'), local('OpenSans-Light'), url({{ asset('/') }}assets/wizard/pic/DXI1ORHCpsQm3Vp6mXoaTXhCUOGz7vYGh680lGh-uXM.woff) format('woff');
                    }
                </style> -->
                
            
                <!-- Additional styles -->
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/styles/form3860.css?v=1">
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/styles/modal3860.css?v=1">
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/styles/progress-slider3860.css?v=1">
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/styles/switches3860.css?v=1">
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/css/styles/table3860.css?v=1">

                <!-- jQuery Form Validation -->
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/js/libs/formValidator/developr.validationEngine3860.css?v=1">

                <!-- Google code prettifier -->
                <script src="{{ asset('/') }}assets/wizard/js/libs/google-code-prettify/prettify3860.js?v=1"></script>
                <link rel="stylesheet" href="{{ asset('/') }}assets/wizard/js/libs/google-code-prettify/sunburst3860.css?v=1">

                <!-- JavaScript at bottom except for Modernizr -->
                <script src="{{ asset('/') }}assets/wizard/js/libs/modernizr.custom.js"></script>

                <!-- For Modern Browsers -->
                <link rel="shortcut icon" href="{{ asset('/') }}assets/wizard/img/favicons/favicon.png">
                <!-- For everything else -->
                <link rel="shortcut icon" href="{{ asset('/') }}assets/wizard/img/favicons/favicon.ico">
                <!-- For retina screens -->
                <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('/') }}assets/wizard/img/favicons/apple-touch-icon-retina.png">
                <!-- For iPad 1-->
                <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('/') }}assets/wizard/img/favicons/apple-touch-icon-ipad.png">
                <!-- For iPhone 3G, iPod Touch and Android -->
                <link rel="apple-touch-icon-precomposed" href="{{ asset('/') }}assets/wizard/img/favicons/apple-touch-icon.png">

                <!-- iOS web-app metas -->
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black">
                
                <!-- Microsoft clear type rendering -->
                <!-- <meta http-equiv="cleartype" content="on"> -->

                <!-- <meta name="msapplication-tooltip" content="Cross-platform admin template.">
                <meta name="msapplication-starturl" content="http://www.display-inline.fr/demo/developr"> -->

                <!-- Style -->
                <?php
                    $bg = array("/assets/admin_login_bg/login-1-high-prog.jpg", "/assets/admin_login_bg/login-2-high-prog.jpg", "/assets/admin_login_bg/login-3-high-prog.jpg", "/assets/admin_login_bg/login-4-high-prog.jpg", "/assets/admin_login_bg/login-5-high-prog.jpg", "/assets/admin_login_bg/login-6-high-prog.jpg", "/assets/admin_login_bg/login-8-high-prog.jpg", "/assets/admin_login_bg/login-9-high-prog.jpg"); // array of filenames
                    $i = rand(0, count($bg)-1);
                    $Images = "$bg[$i]";
                ?>
                <style>
                    body{
                        background:url(<?php echo $Images; ?>);
                        background-repeat: no-repeat;
                        background-position: center center;
                        background-size: cover;
                        background-attachment: fixed;
                    }
                </style>

            </head>

            <body class="full-page-wizard">

                <!-- Scripts -->
                <script src="{{ asset('/') }}assets/wizard/js/libs/jquery-1.8.2.min.js"></script>
                <script src="{{ asset('/') }}assets/wizard/js/setup.js"></script>

                <!-- Template functions -->
                <script src="{{ asset('/') }}assets/wizard/js/developr.input.js"></script>
                <script src="{{ asset('/') }}assets/wizard/js/developr.tabs.js"></script>	
                <script src="{{ asset('/') }}assets/wizard/js/developr.tooltip.js"></script>
                <script src="{{ asset('/') }}assets/wizard/js/developr.wizard.js"></script>

                <!-- jQuery Form Validation -->
                <script src="{{ asset('/') }}assets/wizard/js/libs/formValidator/jquery.validationEngine3860.js?v=1"></script>
                <script src="{{ asset('/') }}assets/wizard/js/libs/formValidator/languages/jquery.validationEngine-en3860.js?v=1"></script>

                <script>
                   
                    function showWifi() {
                        var x = document.getElementById("showWiFiDIV");
                        var x2 = document.getElementById("showWiFiDIV2");
                        if (x.style.display === "none") {
                            x.style.display = "block";
                            x2.style.display = "block";
                        } else {
                            x.style.display = "none";
                            x2.style.display = "none";
                        }
                    }

                    function showPrivateWifi() {
                        var x = document.getElementById("showPrivateWiFiDIV");
                        var x2 = document.getElementById("showPrivateWiFiDIV2");
                        if (x.style.display === "none") {
                            x.style.display = "block";
                            x2.style.display = "block";
                        } else {
                            x.style.display = "none";
                            x2.style.display = "none";
                        }
                    }

                    function showloadBalanceing() {
                        var x = document.getElementById("dynamicLoadDIV");
                        if (x.style.display === "none") {
                            x.style.display = "block";
                        } else {
                            x.style.display = "none";
                        }
                    }

                    $(document).on('click', '#removeLine', function () {
                        $(this).parent().remove();
                    });

                    function addNewLine() {
                        var code = '<div class="field-block button-height">\n';                                     
                            code += '<span class="label"></span> \n';
                                code += '<select name="speed[]" class="select"> \n';
                                    code += '<option value="1">Speed 1M</option> \n';
                                    code += '<option value="2">Speed 2M</option> \n';
                                    code += '<option value="4">Speed 4M</option> \n';
                                    code += '<option selected value="8">Speed 8M</option> \n';
                                    code += '<option value="16">Speed 16M</option> \n';
                                    code += '<option value="32">Speed 32M</option> \n';
                                    code += '<option value="64">Speed 64M</option> \n';
                                code += '</select> \n';
                                code += '<input type="text" name="ip[]" value="" class="input" placeholder="IP Address" size="12">  \n';
                                code += '<input type="text" name="gateway[]" value="" class="input" placeholder="Gateway" size="11">  \n';
                                code += '<button type="button"  id="removeLine" class="button glossy"><span class="red">Remove</span></button> \n';
                            code += '</div>\n';
                        $('#dynamicLoadDIV').append(code);
                    }

                    function dailyQuotaFunction() {
                        
                        var x = document.getElementById("dailyQuotaDIV");
                        var x1 = document.getElementById("dailyQuotaDIV1");
                        var x2 = document.getElementById("dailyQuotaDIV2");
                        var x3 = document.getElementById("dailyQuotaDIV3");

                        if (x.style.display === "none") {
                            x.style.display = "block";
                            x1.style.display = "block";
                            x2.style.display = "block";
                            x3.style.display = "block";
                        } else {
                            x.style.display = "none";
                            x1.style.display = "none";
                            x2.style.display = "none";
                            x3.style.display = "none";
                        }
                        
                    }

                    function downgradeSpeed() {
                        
                        var x2 = document.getElementById("dailyQuotaDIV2");
                        var x3 = document.getElementById("dailyQuotaDIV3");

                        if (x2.style.display === "none") {
                            x2.style.display = "block";
                            x3.style.display = "block";
                        } else {
                            x2.style.display = "none";
                            x3.style.display = "none";
                        }
                        
                    }
                    

                </script>
 
                 <form method="get" id="wizardForm" action="http://{{ $hostedDomain[2] }}/install/installation" class="block wizard "> 
                 <!-- <form class="block wizard ">  -->

                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />

                    <h3 class="block-title">Mikrotik Controller Wizard</h3>

                    <!-- Welcome fieldset -->

                    <fieldset class="wizard-fieldset fields-list">

                            <legend class="legend">Smart wizard!</legend>

                            <div class="field-block button-height">
                                <h4>Hello {{ session('install_name')[0] }}!</h4>
                                <p>We help you to configure your device, network and system through easy steps automatically.</p>
                            </div>
                            
                    </fieldset>

                    <!-- Welcome fieldset -->

                    <!-- WiFi -->
                    @if( Session::has('install_mikrotikType') and session('install_mikrotikType')[0]=="appliance")
                        <fieldset class="wizard-fieldset fields-list">

                            <legend class="legend">WiFi</legend>

                            <div class="field-block">
                                <h4>Hello {{ session('install_name')[0] }}!</h4>
                                <p>Please fill this form to complete your registration:</p>
                            </div>
                            
                            <div class="field-block button-height">
                                <label for="wifiState" class="label"><b>WiFi state </b><span class="red">&nbsp*</span></label>
                                <select class="select" onchange="showWifi()" name='wifiState'>
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                </select>
                            </div>


                                <div class="field-block button-height" id="showWiFiDIV">
                                    <label for="wifiName" class="label"><b>WiFi name</b><span class="red">&nbsp*</span></label>
                                    <input type="text" name="wifiName" id="wifiName" @if (session('install_subdomain')) value="{{session('install_subdomain')[0]}}" @endif class="input validate[required]">
                                </div>

                                <div class="field-block button-height" id="showWiFiDIV2">
                                    <label for="wifiPassword" class="label"><b>WiFi Password</b></label>
                                    <input type="text" name="wifiPassword" id="wifiPassword" value="" class="input" minlength="8">
                                    <small class="input-info red">Minimum length 8 characters ( or leave empty for open WiFi ).</small>
                                </div>
                            

                            <div class="field-block button-height">
                                <label for="privateWifiState" class="label"><b>Private WiFi state </b></label>
                                <select class="select" onchange="showPrivateWifi()" name='privateWifiState'>
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                </select>
                                <small class="input-info">for smart WiFi devices (TV, Printer, etc...) without access control or landing page.</small>
                            </div>

                                <div class="field-block button-height" id="showPrivateWiFiDIV" style="display: none;">
                                    <label for="privateWifiName" class="label"><b>Private WiFi name</b></label>
                                    <input type="text" name="privateWifiName" id="privateWifiName" @if (session('install_subdomain')) value="{{session('install_subdomain')[0]}} Private" @endif class="input validate[required]">
                                </div>

                                <div class="field-block button-height" id="showPrivateWiFiDIV2" style="display: none;">
                                    <label for="privateWifiPassword" class="label"><b>Private WiFi Password</b></label>
                                    <input type="text" name="privateWifiPassword" id="privateWifiPassword" value="" class="input" minlength="8">
                                    <small class="input-info red">Minimum length 8 characters ( or leave empty for open WiFi ).</small>
                                </div>
                            
                            </div>

                        </fieldset>
                    @endif
                    <!-- Internet -->
                    <fieldset class="wizard-fieldset fields-list">

                        <legend class="legend">Internet</legend>

                        <div class="field-block button-height">
                            <label for="loadBalanceingState" class="label"><b>Load balancing</b></label>
                            <select class="select" onchange="showloadBalanceing()" name='loadBalanceingState'>
                                <option value="0">OFF</option>
                                <option value="1">ON</option>
                            </select>
                            <small class="input-info">If you have multible internet source or landlines.</small>
                        </div>

                        <div id="dynamicLoadDIV" style="display: none;"><br>

                            <div class="field-block button-height">
                                <span class="label"><button type="button" onclick="addNewLine()" id="edit-add_load" class="button glossy"><span class="blue">Add New Line</span></button> </span>
                                <select name="speed[]" class="select">
                                    <option value="1">Speed 1M</option>
                                    <option value="2">Speed 2M</option>
                                    <option value="4">Speed 4M</option>
                                    <option selected value="8">Speed 8M</option>
                                    <option value="16">Speed 16M</option>
                                    <option value="32">Speed 32M</option>
                                    <option value="64">Speed 64M</option>
                                </select>
                                <input type="text" name="ip[]" value="192.168.1.10" class="input" placeholder="IP Address" size="12"> 
                                <input type="text" name="gateway[]" value="192.168.1.1" class="input" placeholder="Gateway" size="11">    
                            </div>

                        </div><br>

                        <div class="field-block button-height">
                            <label for="blockDownloading" class="label"><b>Block Downloading</b></label>
                            <select class="select" name='blockDownloading'>
                                <option value="0">OFF</option>
                                <option value="1">ON</option>
                            </select>
                        </div>
                        
                        <div class="field-block button-height">
                            <label for="blockTorrent" class="label"><b>Block torrent download </b></label>
                            <select class="select" name='blockTorrent'>
                                <option value="0">OFF</option>
                                <option value="1">ON</option>
                            </select>
                        </div>

                        <div class="field-block button-height">
                            <label for="adultProtection" class="label"><b>Adult Protection </b></label>
                            <select class="select" name='adultProtection'>
                                <option value="0">OFF</option>
                                <option value="1">ON</option>
                            </select>
                        </div>
                        
                        <div class="field-block button-height">
                            <label for="hackingProtection" class="label"><b>Hacking and NetCut Protection </b></label>
                            <select class="select" name='hackingProtection'>
                                <option value="0">OFF</option>
                                <option value="1">ON</option>
                            </select>
                            <small class="input-info">Hacking protection disable sharing between computers and printers.</small>
                        </div>

                        <div class="field-block button-height">
                            <label for="monthlyQuota" class="label"><b>Monthly Quota</b><span class="red">&nbsp GB</span></label>
                            <input type="text" name="monthlyQuota" id="monthlyQuota" value="500" size="4" class="input validate[custom[onlyLetterNumber]]">
                            <small class="input-info">Enter your total landlines quota or leave empty for unlimited quota.</small>
                        </div>

                        <div class="field-block button-height">
                            <label for="monthlyQuotaRenewalDay" class="label"><b>Quota renewal day</b></label>
                            <input type="text" name="monthlyQuotaRenewalDay" id="monthlyQuotaRenewalDay" value="1" size="2" class="input validate[custom[onlyLetterNumber]]">
                            <!-- <small class="input-info">Enter your landlines quota or leave empty for unlimited quota.</small> -->
                        </div>
                        
                    </fieldset>
                    <!-- Speed -->
                    <fieldset class="wizard-fieldset fields-list">

                        <legend class="legend">Speed</legend>

                        <div class="field-block button-height">
                            <span class="label"><span class="green">Browsing Mode</span> </span>
                            <span class="green"><strong>Download speed:</strong></span> 
                            <input type="text" name="browsingDownSpeed" value="8" class="input" placeholder="Download speed" size="2"> 
                            <select name="browsingDownType" class="select">
                                <option value="M">MB</option>
                                <option value="K">KB</option>
                            </select>
                            <span class="green"><strong>Upload speed:</strong></span>
                            <input type="text" name="browsingUpSpeed" value="2" class="input" placeholder="IP Address" size="2"> 
                            <select name="browsingUpType" class="select">
                                <option value="M">MB</option>
                                <option value="K">KB</option>
                            </select>
                        </div>

                        <div class="field-block button-height">
                            <span class="label"><span class="green">Download Mode</span> </span>
                            <span class="green"><strong>Download speed:</strong></span> 
                            <input type="text" name="downloadDownSpeed" value="2" class="input" placeholder="Download speed" size="2"> 
                            <select name="downloadDownType" class="select">
                                <option value="M">MB</option>
                                <option value="K">KB</option>
                            </select>
                            <span class="green"><strong>Upload speed:</strong></span>
                            <input type="text" name="downloadUpSpeed" value="1" class="input" placeholder="IP Address" size="2"> 
                            <select name="downloadUpType" class="select">
                                <option value="M">MB</option>
                                <option value="K">KB</option>
                            </select>
                        </div>

                        <div class="field-block button-height">
                            <span class="label"><span class="blue">Apply Daily Quota?</span> </span>
                            <select onchange="dailyQuotaFunction()" name="dailyQuotaState" class="select">
                                <option value="on">ON</option>
                                <option value="off">OFF</option>
                            </select>
                            <small class="input-info">set daily quota for each user.</small>
                        </div>

                        <div class="field-block button-height" id="dailyQuotaDIV">
                            <label for="dailyQuota" class="label"><b>Daily Quota</b><span class="red">&nbsp MB</span></label>
                            <input type="text" name="dailyQuota" id="dailyQuotaValue" size="5" value="1024" class="input validate[custom[onlyLetterNumber]]">
                        </div>

                        <div class="field-block button-height" id="dailyQuotaDIV1">
                            <span class="label"><span class="blue">After Quota Finish</span> </span>
                            <select onchange="downgradeSpeed()" name="afterQuotaFinish" class="select">
                                <option value="downgrade">Downgrade speed</option>
                                <option value="stop">Stop internet till next day</option>
                            </select>
                        </div>
                        
                        <div class="field-block button-height" id="dailyQuotaDIV2">
                            <span class="label"><span class="red">Browsing Mode</span> </span>
                            <span class="red"><strong>Download speed:</strong></span> 
                            <input type="text" name="downgradeBrowsingDownSpeed" value="8" class="input" placeholder="Download speed" size="2"> 
                            <select name="downgradeBrowsingDownType" class="select">
                                <option value="M">MB</option>
                                <option value="K">KB</option>
                            </select>
                            <span class="red"><strong>Upload speed:</strong></span>
                            <input type="text" name="downgradeBrowsingUpSpeed" value="2" class="input" placeholder="IP Address" size="2"> 
                            <select name="downgradeBrowsingUpType" class="select">
                                <option value="M">MB</option>
                                <option value="K">KB</option>
                            </select>
                        </div>

                        <div class="field-block button-height" id="dailyQuotaDIV3">
                            <span class="label"><span class="red">Download Mode</span> </span>
                            <span class="red"><strong>Download speed:</strong></span> 
                            <input type="text" name="downgradeDownloadDownSpeed" value="512" class="input" placeholder="Download speed" size="2"> 
                            <select name="downgradeDownloadDownType" class="select">
                                <option value="K">KB</option>
                                <option value="M">MB</option>
                            </select>
                            <span class="red"><strong>Upload speed:</strong></span>
                            <input type="text" name="downgradeDownloadUpSpeed" value="128" class="input" placeholder="IP Address" size="2"> 
                            <select name="downgradeDownloadUpType" class="select">
                                <option value="K">KB</option>
                                <option value="M">MB</option>
                            </select>
                        </div>
                        @if( ( session('install_mikrotikType')[0]=="appliance" and DB::table('serials')->where('serial',session('install_serial')[0])->whereNull('customer_id')->count() == "1") or (session('install_client_mac')[0]!="") ) 
                        <div class="field-block button-height wizard-controls align-right">
                            <button onclick="loadingIcon()" id="loadingButton" type="submit" class="button glossy mid-margin-right">
                                <span class="button-icon"><span class="icon-tick"></span></span>
                                Finish
                            </button>
                        </div>
 
                        <script>
                            function loadingIcon() {
                                $('#loadingButton').attr("disabled", "disabled");
                                $('#wizardForm').submit();
                            }
                        </script>
                        @endif

                    </fieldset>
                    
                    <!-- Mikrotik Script -->
                    <!-- remove script from preconfigured QR device -->
                    <!-- if Mikrotik type is routerboard and serial exist into DB before or user have mac address  --> 
                    <?php /* @if( (session('install_mikrotikType')[0]=="appliance" and DB::table('serials')->where('serial',session('install_serial')[0])->whereNull('customer_id')->count() == "1" and session('install_client_mac')[0]=="") or (session('install_mikrotikType')[0]=="pc") )  */?>
                    <?php // Enter if device type appliance and serial not inside DB or assigned to other customer AND CONFIRM THE USER NOT OPEN AUTO INSTALLATION
                          // Enter if device type PC   ?>
                    @if( (session('install_mikrotikType')[0]=="appliance" and DB::table('serials')->where('serial',session('install_serial')[0])->whereNull('customer_id')->count() == "0" and session('install_client_mac')[0]=="") or (session('install_mikrotikType')[0]=="pc") )  

                    <fieldset class="wizard-fieldset fields-list">

                        <legend class="legend">Script Setup</legend>

                        <div class="field-block button-height">
                            <!-- <span class="label"><span class="green">Script</span> </span> -->
                            <!-- <textarea name="browsingDownSpeed" class="input" rows="4" cols="50"> 
                            </textarea> -->
                        </div>  
                        
                        <div class="field-block button-height">
                            <span class="label"><h2 class="thin mid-margin-bottom">Step A</h2></span>
                            <h2 class="thin mid-margin-bottom no-margin-top">Download and open Winbox</h2>
                            <h5 class="no-margin-top">
                            1 - Open <a href='https://download.mikrotik.com/routeros/winbox/3.13/winbox.exe' target='_blank'>Mikrotik Winbox</a>, then click on "Neighbors". <br>
                            2 - Wait 30 seconds then click on Displayed Mac-Address. <br>
                            3 - Enter your username (admin) and empty password; or enter your setted credentials. <br>
                            4 - Click "Connect". </h5> 
                            <img src="{{ asset('/') }}assets/images/mikrotik1.png" width="100%" height="50%">
                        </div>

                        <!-- <div class="field-block button-height">
                            <span class="label"><h2 class="thin mid-margin-bottom">Step B</h2></span>
                            <h2 class="thin mid-margin-bottom no-margin-top">Reset Configuration</h2>
                            <h5 class="no-margin-top">1 - Click on "System". <br>
                            2 - Click on "Reset Configuration". <br>
                            3 - From Reset configuration window click on "Reset Configuration". <br>
                            4 - Click "Yes". </h5> 
                            <img src="{{ asset('/') }}assets/images/mikrotik2.png" width="100%" height="65%">
                        </div>

                        <div class="field-block button-height">
                            <span class="label"><h2 class="thin mid-margin-bottom">Step C</h2></span>
                            <h2 class="thin mid-margin-bottom no-margin-top">Remove Default Configuration</h2>
                            <h5 class="no-margin-top">Close Winbox and open it again, then click on "Remove Configuration". </h5>
                            <img src="{{ asset('/') }}assets/images/mikrotik3.png" width="100%" height="60%">
                        </div> -->

                        <div class="field-block button-height">
                            <!-- <span class="label"><h2 class="thin mid-margin-bottom">Step D</h2></span> -->
                            <span class="label"><h2 class="thin mid-margin-bottom">Step B</h2></span>
                            <h2 class="thin mid-margin-bottom no-margin-top">Copy Mikrotik Script</h2>
                            <h5 class="no-margin-top">1 - Click on "New Terminal". <br>
                            2 - Copy all the following code and paste it into "New Terminal". </h5>
                            <?php /*
                            @if( Session::has('install_mikrotikType') and session('install_mikrotikType')[0]=="appliance" )
                                <!-- <div class="field-block button-height"> -->
                                    <div class="large-box-shadow white-gradient with-border">

                                    <div class="button-height with-mid-padding silver-gradient no-margin-top"></div>
                                    <div class="with-padding">
                                        <textarea class="input full-width autoexpanding" style="overflow: hidden; resize: none; height: 204px;"> 
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
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/mikrotikapi\\\?identify=\$identify&secret=\$secret&reboot=reboot&branchid=\$branchid&realm=\$realm&serial=\$serial\" mode=http dst-path=\"reboot.rsc\"\r\
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
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
                                                &branchid=\$branchid&realm=\$realm&publicip=\$publicIP&dnsname=\$DNSname\" mode=http dst-path=\"auto.rsc\"\r\
                                                \nimport file-name=auto.rsc\r\
                                                \n} else={\r\
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
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
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/ssl/internet.microsystem.com.eg.key\" mode=http\r\
                                                \n:delay 5\r\
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/ssl/internet.microsystem.com.eg.cer\" mode=http\r\
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

                                            /user set [find name=admin] name="{{session('install_mail')[0]}}" password="{{session('install_password')[0]}}"
                                        </textarea> 
                                    </div>
                                <!-- </div> -->
                            @else
                            */ ?>
                                <!-- <div class="field-block button-height">     -->
                                <div class="large-box-shadow white-gradient with-border">

                                    <div class="button-height with-mid-padding silver-gradient no-margin-top"></div>
                                    <div class="with-padding">
                                        <textarea class="input full-width autoexpanding" style="overflow: hidden; resize: none; height: 204px;"> 
                                            /system identity set name={{session('install_subdomain')[0]}}
                                            /radius add address=13.81.62.85 realm={{session('install_uniqueRealm')[0]}} secret=microsystem service=hotspot timeout=3s comment={{session('install_subdomain')[0]}}{{session('install_masterDomain')[0]}}

                                            /ip hotspot profile set [ find name=hsprof1 ] dns-name=internet.microsystem.com.eg login-by=cookie,http-pap,mac use-radius=yes radius-accounting=yes radius-interim-update=1m radius-location-name={{session('install_subdomain')[0]}}

                                            /file set "hotspot/login.html" contents="<meta http-equiv='refresh' content='0; url=http://{{session('install_subdomain')[0]}}{{session('install_masterDomain')[0]}}'>"

                                            /ip hotspot walled-garden
                                            add dst-host={{session('install_subdomain')[0]}}{{session('install_masterDomain')[0]}}
                                            add dst-host=http://{{session('install_subdomain')[0]}}{{session('install_masterDomain')[0]}}
                                            add dst-host=http://www.{{session('install_subdomain')[0]}}{{session('install_masterDomain')[0]}}
                                            add dst-host=www.{{session('install_subdomain')[0]}}{{session('install_masterDomain')[0]}}
                                            add dst-host=*{{session('install_masterDomain')[0]}}
                                            add dst-host=*.fbcdn.net
                                            add dst-host=*.facebook.com
                                            add dst-host=*.accountkit.*
                                            add dst-host=*.cloudfront.net
                                            add dst-host=*.mymicrosystem.*
                                            add dst-host=*.mikrotik.*
                                            add dst-host=*.microsystemapp.*
                                            add dst-host=*.microsystem.*
                                            add dst-host=*comodoca*
                                            add dst-host=*.rapidssl.*

                                            /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=13.81.62.85 !dst-port !protocol !src-address
                                            /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=13.94.129.188 !dst-port !protocol !src-address
                                            /ip hotspot walled-garden ip add action=accept disabled=no !dst-address dst-host=52.169.225.126 !dst-port !protocol !src-address

                                            /radius incoming set accept=yes

                                            /tool netwatch add down-script="/ip hotspot set [ find name=hotspot1 ] disabled=yes" host=13.81.62.85 interval=2m timeout=2s up-script="/ip hotspot set [ find name=hotspot1 ] disabled=no"

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
                                                \nif ([/ip hotspot profile find name=hsprof1 ] != \"\" and [/radius find address=13.81.62.85 ] != \"\") do={\r\
                                                \n:local branchid [/ip hotspot profile get [ find name=hsprof1 ] radius-location-id ];\r\
                                                \n:local secret [/radius get [ find address=13.81.62.85 ] secret ];\r\
                                                \n:local realm [/radius get [ find address=13.81.62.85 ] realm ];\r\
                                                \n:local identify [/system identity get name];\r\
                                                \n:local serial [/system routerboard get serial-number];\r\
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/mikrotikapi\\\?identify=\$identify&secret=\$secret&reboot=reboot&branchid=\$branchid&realm=\$realm&serial=\$serial\" mode=http dst-path=\"reboot.rsc\"\r\
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
                                                \nif ([/ip hotspot profile find name=hsprof1 ] != \"\" and [/radius find address=13.81.62.85 ] != \"\") do={\r\
                                                \n\r\
                                                \n:local branchid [/ip hotspot profile get [ find name=hsprof1 ] radius-location-id ];\r\
                                                \n:local realm [/radius get [ find address=13.81.62.85 ] realm ];\r\
                                                \n\r\
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
                                                &branchid=\$branchid&realm=\$realm&publicip=\$publicIP&dnsname=\$DNSname\" mode=http dst-path=\"auto.rsc\"\r\
                                                \nimport file-name=auto.rsc\r\
                                                \n} else={\r\
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/mikrotikapi\\\?identify=\$identify&auto=auto&serial=\$serial&cpu=\$cpu&uptime=\$uptime&ram=\$ram&boardname=\$boardname\
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
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/ssl/internet.microsystem.com.eg.key\" mode=http\r\
                                                \n:delay 5\r\
                                                \n/tool fetch url=\"http://s1.microsystem.com.eg/ssl/internet.microsystem.com.eg.cer\" mode=http\r\
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

                                            /interface sstp-client add connect-to=52.233.172.210:9090 disabled=no http-proxy=0.0.0.0:9090 name=cloud password=1403636mra profile=default-encryption user={{session('install_subdomain')[0]}} verify-server-address-from-certificate=no
                                            /user set [find name=admin] name="{{session('install_mail')[0]}}" password="{{session('install_password')[0]}}"
                                        </textarea>
                                    </div>
                                <!-- </div> -->

                                <div class="custom-vscrollbar" style="display: block; top: 6px; left: 715px; height: 478px; width: 8px; opacity: 0;"><div style="top: 0px; height: 320px;"></div></div></div>
                            <?php     
                            //@endif 
                            ?>
                            <br>
                            <img src="{{ asset('/') }}assets/images/mikrotik4.png" width="100%" height="55%">
                        </div>
                        
                        <div class="field-block button-height wizard-controls align-right">

                             <button onclick="loadingIcon()" id="loadingButton" type="submit" class="button glossy mid-margin-right">
                                <span class="button-icon"><span class="icon-tick"></span></span>
                                Finish
                            </button> 

                        </div>

                        <script>
                            function loadingIcon() {
                                $('#loadingButton').attr("disabled", "disabled");
                                $('#wizardForm').submit();
                            }
                        </script>

                    </fieldset>
                    @endif
                </form>


            </body>



        @else

            @extends('...back-end.layouts.app')
            @section('title', 'Mikrotik controller') 
            @section('content') 
            <style>
            .panel-transparent {
                    background: rgba(255,255,255, 0.5)!important;
            }
            </style> 
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            
            <div class="tabbable panel login-form width-400 panel-transparent">
                <ul class="nav nav-tabs nav-justified">
                    <li @if (!session('install_subdomain') and !isset($_GET['serial']) and !isset($_GET['registration']) ) class="active" @endif ><a href="#basic-tab1" data-toggle="tab"><h6>Sign in</h6></a></li>
                    <li @if (session('install_subdomain') or isset($_GET['serial']) or isset($_GET['registration']) ) class="active" @endif ><a href="#basic-tab2" data-toggle="tab"><h6>Whitelabel registration</h6></a></li>
                </ul>
 
                <div class="tab-content panel-body">
                    <div class="tab-pane fade in @if ( !session('install_subdomain') and !isset($_GET['serial']) and !isset($_GET['registration']) ) active @endif" id="basic-tab1">
                        <form method="POST" action="{{ url('subdomain') }}" >
                            <?php $uniqueRealm = rand(1000000000,9999999999);?>
                            <input type="hidden" id="uniqueRealm" name="uniqueRealm" value="{{$uniqueRealm}}" />
                            {{ csrf_field() }}
                            <div class="text-center">
                                <div class="icon-object border-slate-600 text-slate-600"><i class="icon-reading"></i></div>
                                <h5 class="content-group">Enter your subdomain at <small class="display-block" style="color: #ffffff">Microsystem Hotspot</small></h5>
                            </div>
                  
                            <div class="input-group has-feedback-left">
                                <input type="text" name="domain" class="form-control" placeholder="Company Name">
                                    <span class="input-group-addon">{{$installation_url}}</span>
                                    <input type='hidden' id="masterDomain" name='masterDomain' value="{{$installation_url}}">
                                <div class="form-control-feedback">
                                    <i class=" icon-earth"></i>
                                </div>
                            </div>
                            <br>

                            @if (isset($error) && $error == 1)
                                <div class="alert alert-warning alert-styled-left alert-arrow-left alert-bordered">
                                    <span class="text-semibold">Sorry!</span> Your Domain name is not valid.
                                </div>
                            @endif

                            <div class="form-group">
                                <button type="submit" class="btn bg-blue btn-block">Open <i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>

                    </div>
   
                    <div class="tab-pane fade in @if (session('install_subdomain') or isset($_GET['serial']) or isset($_GET['registration']) ) active @endif validation" id="basic-tab2">
                        <form autocomplete="off" method="POST" action="{{ url('installation') }}" id="firstRegistrationForm">
                            {{ csrf_field() }}
                            <div class="text-center">
                                <!-- <div class="icon-object border-success text-success"><i class="icon-plus3"></i></div> -->
                                <!-- <img src="http://mikrotik.com.eg/wp-content/uploads/2018/06/logo_new800-Custom-2-980x280-1-300x86.png"></img> -->
                                <img src="{{ asset('/') }}assets/images/mikrotik_logo.png" width="100%" height="50%">
                                <h5 class="content-group">Create your own Mikrotik controller <small class="display-block" style="color: #ffffff">All fields are required</small></h5>
                            </div>

                            <div class="input-group has-feedback-left">
                                <input required type="text" id="subdomain" name="domain" @if (session('install_subdomain')) value="{{ session('install_subdomain')[0] }}" @endif class="form-control required" placeholder="Company Name">
                                    <span class="input-group-addon">{{$installation_url}}</span>
                                    <input type='hidden' id="masterDomain" name='masterDomain' value="{{$installation_url}}">
                                <div class="form-control-feedback">
                                    <i class=" icon-earth"></i>
                                </div>
                            </div>

                            <br>

                            <div class="form-group has-feedback ">
                                <select class="form-control select-fixed-single" name='install_business_type'>
                                    <option @if (session('install_business_type') and session('install_business_type') == "airport") selected @endif value="airport">Airport</option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "hotel") selected @endif value="hotel">Hotel</option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "cofee") selected @endif value="cofee">Cofee </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "clup") selected @endif value="clup">Clup </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "Mall") selected @endif value="clup">Mall </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "resturant") selected @endif value="resturant">Resturant </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "working_space") selected @endif value="working_space">Co-Working Space </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "office") selected @endif value="office">Office </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "school") selected @endif value="school">School </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "university") selected @endif value="university">University </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "marketing_agency") selected @endif value="marketing_agency">Marketing Agency </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "reseller") selected @endif value="reseller">Reseller </option>
                                    <option @if (session('install_business_type') and session('install_business_type') == "other") selected @endif value="other">Other </option>
                                </select>
                            </div>
                            
                            <!-- <br> -->
                            <input type="hidden" id="subdomainValid" value="0">
                            <!-- <label class="basic-error-subdomain validation-error-label" for="basic" style="display: none;"></label> -->
                            
                            <div class="basic-error-subdomain alert alert-danger alert-styled-left alert-arrow-left alert-bordered" style="display: none;"></div>
                            
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" id="name" @if (session('install_name')) value="{{ session('install_name')[0] }}" @endif name="name" class="form-control required" placeholder="Your Name">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <select id="country_code" class="form-control select-fixed-single" name="country_code">                             
                                    <option @if (session('install_1stCountryCode')[0]=="+2") selected @endif value="+2">Egypt +2</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+966") selected @endif value="+966">Saudi Arabia +966</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+971") selected @endif value="971">United Arab Emirates +971</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+974") selected @endif value="+974">Qatar +974</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+964") selected @endif value="+964">Iraq +964</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+965") selected @endif value="+965">Kuwait +965</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+961") selected @endif value="+961">Lebanon +961</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+962") selected @endif value="+962">Jordan +962</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+220") selected @endif value="+220">Gambia +220</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+44") selected @endif value="+44">UK +44</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+1") selected @endif value="+1">USA +1</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+213") selected @endif value="+213">Algeria +213</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+376") selected @endif value="+376">Andorra +376</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+244") selected @endif value="+244">Angola +244</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+1264") selected @endif value="+1264">Anguilla +1264</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+1268") selected @endif value="+1268">Antigua &amp; Barbuda +1268</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+599") selected @endif value="+599">Antilles Dutch +599</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+54") selected @endif value="+54">Argentina +54</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+374") selected @endif value="+374">Armenia +374</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+297") selected @endif value="+297">Aruba +297</option>
                                    <option value="+247">Ascension Island +247</option>
                                    <option value="+61">Australia +61</option>
                                    <option value="+43">Austria +43</option>
                                    <option value="+994">Azerbaijan +994</option>
                                    <option value="+1242">Bahamas +1242</option>
                                    <option value="+973">Bahrain +973</option>
                                    <option value="+880">Bangladesh +880</option>
                                    <option value="+1246">Barbados +1246</option>
                                    <option value="+375">Belarus +375</option>
                                    <option value="+32">Belgium +32</option>
                                    <option value="+501">Belize +501</option>
                                    <option value="+229">Benin +229</option>
                                    <option value="+1441">Bermuda +1441</option>
                                    <option value="+975">Bhutan +975</option>
                                    <option value="+591">Bolivia +591</option>
                                    <option value="+387">Bosnia Herzegovina +387</option>
                                    <option value="+267">Botswana +267</option>
                                    <option value="+55">Brazil +55</option>
                                    <option value="+673">Brunei +673</option>
                                    <option value="+359">Bulgaria +359</option>
                                    <option value="+226">Burkina Faso +226</option>
                                    <option value="+257">Burundi +257</option>
                                    <option value="+855">Cambodia +855</option>
                                    <option value="+237">Cameroon +237</option>
                                    <option value="+1">Canada +1</option>
                                    <option value="+238">Cape Verde Islands +238</option>
                                    <option value="+1345">Cayman Islands +1345</option>
                                    <option value="+236">Central African Republic +236</option>
                                    <option value="+56">Chile +56</option>
                                    <option value="+86">China +86</option>
                                    <option value="+57">Colombia +57</option>
                                    <option value="+269">Comoros +269</option>
                                    <option value="+242">Congo +242</option>
                                    <option value="+682">Cook Islands +682</option>
                                    <option value="+506">Costa Rica +506</option>
                                    <option value="+385">Croatia +385</option>
                                    <option value="+53">Cuba +53</option>
                                    <option value="+90392">Cyprus North +90392</option>
                                    <option value="+357">Cyprus South +357</option>
                                    <option value="+42">Czech Republic +42</option>
                                    <option value="+45">Denmark +45</option>
                                    <option value="+2463">Diego Garcia +2463</option>
                                    <option value="+253">Djibouti +253</option>
                                    <option value="+1809">Dominica +1809</option>
                                    <option value="+1809">Dominican Republic +1809</option>
                                    <option value="+593">Ecuador +593</option>
                                    <option value="+353">Eire +353</option>
                                    <option value="+503">El Salvador +503</option>
                                    <option value="+240">Equatorial Guinea +240</option>
                                    <option value="+291">Eritrea +291</option>
                                    <option value="+372">Estonia +372</option>
                                    <option value="+251">Ethiopia +251</option>
                                    <option value="+500">Falkland Islands +500</option>
                                    <option value="+298">Faroe Islands +298</option>
                                    <option value="+679">Fiji +679</option>
                                    <option value="+358">Finland +358</option>
                                    <option value="+33">France +33</option>
                                    <option value="+594">French Guiana +594</option>
                                    <option value="+689">French Polynesia +689</option>
                                    <option value="+241">Gabon +241</option>
                                    <option value="+7880">Georgia +7880</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+49") selected @endif value="+49">Germany +49</option>
                                    <option value="+233">Ghana +233</option>
                                    <option value="+350">Gibraltar +350</option>
                                    <option value="+30">Greece +30</option>
                                    <option value="+299">Greenland +299</option>
                                    <option value="+1473">Grenada +1473</option>
                                    <option value="+590">Guadeloupe +590</option>
                                    <option value="+671">Guam +671</option>
                                    <option value="+502">Guatemala +502</option>
                                    <option value="+224">Guinea +224</option>
                                    <option value="+245">Guinea - Bissau +245</option>
                                    <option value="+592">Guyana +592</option>
                                    <option value="+509">Haiti +509</option>
                                    <option value="+504">Honduras +504</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+852") selected @endif value="+852">Hong Kong +852</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+36") selected @endif value="+36">Hungary +36</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+354") selected @endif value="+354">Iceland +354</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+91") selected @endif value="+91">India +91</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+62") selected @endif value="+62">Indonesia +62</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+98") selected @endif value="+98">Iran +98</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+964") selected @endif value="+964">Iraq +964</option>
                                    <option value="+972">Israel +972</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+39") selected @endif value="+39">Italy +39</option>
                                    <option value="+225">Ivory Coast +225</option>
                                    <option value="+1876">Jamaica +1876</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+81") selected @endif value="+81">Japan +81</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+962") selected @endif value="+962">Jordan +962</option>
                                    <option value="+7">Kazakhstan +7</option>
                                    <option value="+254">Kenya +254</option>
                                    <option value="+686">Kiribati +686</option>
                                    <option value="+850">Korea North +850</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+82") selected @endif value="+82">Korea South +82</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+965") selected @endif value="+965">Kuwait +965</option>
                                    <option value="+996">Kyrgyzstan +996</option>
                                    <option value="+856">Laos +856</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+371") selected @endif value="+371">Latvia +371</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+961") selected @endif value="+961">Lebanon +961</option>
                                    <option value="+266">Lesotho +266</option>
                                    <option value="+231">Liberia +231</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+218") selected @endif value="+218">Libya +218</option>
                                    <option value="+417">Liechtenstein +417</option>
                                    <option value="+370">Lithuania +370</option>
                                    <option value="+352">Luxembourg +352</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+853") selected @endif value="+853">Macao +853</option>
                                    <option value="+389">Macedonia +389</option>
                                    <option value="+261">Madagascar +261</option>
                                    <option value="+265">Malawi +265</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+60") selected @endif value="+60">Malaysia +60</option>
                                    <option value="+960">Maldives +960</option>
                                    <option value="+223">Mali +223</option>
                                    <option value="+356">Malta +356</option>
                                    <option value="+692">Marshall Islands +692</option>
                                    <option value="+596">Martinique +596</option>
                                    <option value="+222">Mauritania +222</option>
                                    <option value="+269">Mayotte +269</option>
                                    <option value="+52">Mexico +52</option>
                                    <option value="+691">Micronesia +691</option>
                                    <option value="+373">Moldova +373</option>
                                    <option value="+377">Monaco +377</option>
                                    <option value="+976">Mongolia +976</option>
                                    <option value="+1664">Montserrat +1664</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+212") selected @endif value="+212">Morocco +212</option>
                                    <option value="+258">Mozambique +258</option>
                                    <option value="+95">Myanmar +95</option>
                                    <option value="+264">Namibia +264</option>
                                    <option value="+674">Nauru +674</option>
                                    <option value="+977">Nepal +977</option>
                                    <option value="+31">Netherlands +31</option>
                                    <option value="+687">New Caledonia +687</option>
                                    <option value="+64">New Zealand +64</option>
                                    <option value="+505">Nicaragua +505</option>
                                    <option value="+227">Niger +227</option>
                                    <option value="+234">Nigeria +234</option>
                                    <option value="+683">Niue +683</option>
                                    <option value="+672">Norfolk Islands +672</option>
                                    <option value="+670">Northern Marianas +670</option>
                                    <option value="+47">Norway +47</option>
                                    <option value="+968">Oman +968</option>
                                    <option value="+680">Palau +680</option>
                                    <option value="+507">Panama +507</option>
                                    <option value="+675">Papua New Guinea +675</option>
                                    <option value="+595">Paraguay +595</option>
                                    <option value="+51">Peru +51</option>
                                    <option value="+63">Philippines +63</option>
                                    <option value="+48">Poland +48</option>
                                    <option value="+351">Portugal +351</option>
                                    <option value="+1787">Puerto Rico +1787</option>
                                    <option value="+974">Qatar +974</option>
                                    <option value="+262">Reunion +262</option>
                                    <option value="+40">Romania +40</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+7") selected @endif value="+7">Russia +7</option>
                                    <option value="+250">Rwanda +250</option>
                                    <option value="+378">San Marino +378</option>
                                    <option value="+239">Sao Tome &amp; Principe +239</option>
                                    <option value="+966">Saudi Arabia +966</option>
                                    <option value="+221">Senegal +221</option>
                                    <option value="+381">Serbia +381</option>
                                    <option value="+248">Seychelles +248</option>
                                    <option value="+232">Sierra Leone +232</option>
                                    <option value="+65">Singapore +65</option>
                                    <option value="+421">Slovak Republic +421</option>
                                    <option value="+386">Slovenia +386</option>
                                    <option value="+677">Solomon Islands +677</option>
                                    <option value="+252">Somalia +252</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+27") selected @endif value="+27">South Africa +27</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+34") selected @endif value="+34">Spain +34</option>
                                    <option value="+94">Sri Lanka +94</option>
                                    <option value="+290">St. Helena +290</option>
                                    <option value="+1869">St. Kitts +1869</option>
                                    <option value="+1758">St. Lucia +1758</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+249") selected @endif value="+249">Sudan +249</option>
                                    <option value="+597">Suriname +597</option>
                                    <option value="+268">Swaziland +268</option>
                                    <option value="+46">Sweden +46</option>
                                    <option value="+41">Switzerland +41</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+963") selected @endif value="+963">Syria +963</option>
                                    <option value="+886">Taiwan +886</option>
                                    <option value="+7">Tajikstan +7</option>
                                    <option value="+66">Thailand +66</option>
                                    <option value="+228">Togo +228</option>
                                    <option value="+676">Tonga +676</option>
                                    <option value="+1868">Trinidad &amp; Tobag</option>
                                    <option value="+216">Tunisia +216</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+90") selected @endif value="+90">Turkey +90</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+7") selected @endif value="+7">Turkmenistan +7</option>
                                    <option value="+993">Turkmenistan +993</option>
                                    <option value="+1649">Turks &amp; Caicos Islands +1649</option>
                                    <option value="+688">Tuvalu +688</option>
                                    <option value="+256">Uganda +256</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+44") selected @endif value="+44">UK +44</option>
                                    <option value="+380">Ukraine +380</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+971") selected @endif value="+971">United Arab Emirates +971</option>
                                    <option value="+598">Uruguay +598</option>
                                    <option @if (session('install_1stCountryCode')[0]=="+1") selected @endif value="+1">USA +1</option>
                                    <option value="+7">Uzbekistan +7</option>
                                    <option value="+678">Vanuatu +678</option>
                                    <option value="+379">Vatican City +379</option>
                                    <option value="+58">Venezuela +58</option>
                                    <option value="+84">Vietnam +84</option>
                                    <option value="+1284">Virgin Islands - British +1284</option>
                                    <option value="+1340">Virgin Islands - US +1340</option>
                                    <option value="+681">Wallis &amp; Futuna +681</option>
                                    <option value="+969">Yemen North +969</option>
                                    <option value="+967">Yemen South +967</option>
                                    <option value="+381">Yugoslavia +381</option>
                                    <option value="+243">Zaire +243</option>
                                    <option value="+260">Zambia +260</option>
                                    <option value="+263">Zimbabwe +263</option>
                                </select>
                                <div class="form-control-feedback">
                                    <!-- <i class=" icon-earth"></i> -->
                                </div>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input  id="mobile" @if (session('install_1stPhoneNumber')) value="{{ session('install_1stPhoneNumber')[0] }}" @endif type="text" name="mobile" class="form-control required" placeholder="Your mobile" autocomplete="off">
                                <div class="form-control-feedback">
                                    <i class=" icon-mobile"></i>
                                </div>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" @if (session('install_mail')) value="{{ session('install_mail')[0] }}" @endif id="mail" name="mail" class="form-control required" placeholder="Your business email" autocomplete="off">
                                <div class="form-control-feedback">
                                    <i class="icon-mention text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input type="password" @if (session('install_password')) value="{{ session('install_password')[0] }}" @endif id="password" name="password" class="form-control required" placeholder="Create password">
                                <div class="form-control-feedback">
                                    <i class="icon-user-lock text-muted"></i>
                                </div>
                            </div>
                            @if(isset($_GET['serial']))
                                <input type="hidden" value="{{ $_GET['serial'] }}" id="serial" name="serial">
                                <!-- user enter QR -->
                                <input type="hidden" value="appliance" id='mikrotikType' name="mikrotikType">
                                <input type="hidden" value="{{ $_GET['client_mac'] }}" id='client_mac' name="client_mac">
                            @else

                                <div class="form-group has-feedback ">
                                    <select class="form-control select-fixed-single" onchange="showSerial()" id='mikrotikType' name='mikrotikType'>
                                        <option value="pc">Mikrotik on PC / VM </option>
                                        <option value="appliance">Mikrotik on Appliance / Routerboard</option>
                                    </select>
                                    <!-- <div class="form-control-feedback">
                                         <i class="icon-barcode2 text-muted"></i> 
                                    </div> -->
                                </div>
                                
                                <div id="mikrotikSerialDIV" style="display: none;">

                                    <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                                        Open Winbox then goto System->Routerboard and copy your serial number. 
                                    </div>

                                    <div class="form-group has-feedback has-feedback-left">
                                        <input type="text" @if (session('install_serial')) value="{{ session('install_serial')[0] }}" @endif id="serial" name="serial" class="form-control" placeholder="Mikrotik serial number">
                                        <div class="form-control-feedback">
                                            <i class="icon-barcode2 text-muted"></i>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        
                            <input type="hidden" id="generalValid" value="0">
                            <div class="basic-error-general alert alert-danger alert-styled-left alert-arrow-left alert-bordered" style="display: none;"></div>
                        </form>
                        
                        <button type="submit" onclick="smsLogin()" id="submitButton" class="btn bg-indigo-400 btn-block">Next <i class="icon-circle-right2 position-right"></i></button>

                        <div id="verificationCode">
                            <center><h3 style="color: white">Please check your WhatsApp to get Verification Code.</h3></center>
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="number" name="verificationCodeText" id="verificationCodeText" class="form-control required" placeholder="Verification Code">
                                <div class="form-control-feedback">
                                    <i class="icon-user-lock text-muted"></i>
                                </div>
                                <br>
                                <button type="submit" onclick="verifyCode()" id="" class="btn bg-indigo-400 btn-block">Next <i class="icon-circle-right2 position-right"></i></button>
                            </div>
                            <div class="basic-error-subdomain2 alert alert-danger alert-styled-left alert-arrow-left alert-bordered" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <script>

                $(window).load(function () {
                    $('#verificationCode').hide();
                });
                
                function showSerial() {
                    var x = document.getElementById("mikrotikSerialDIV");
                    if (x.style.display === "none") {
                        x.style.display = "block";
                    } else {
                        x.style.display = "none";
                    }
                }
                // Facebook accountkit SMS
                function smsLogin() {
                    
                    <?php 
                    $subdomain = url()->full();
                    $hostedDomain = explode('/', $subdomain);
                    require_once '../config.php';
                    ?>
                    var accountKitAppID = "{{ $accountKitAppID4installation }}";
                    var redirectURL = "http://{{ $hostedDomain[2] }}/login";
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    
                    var masterDomain = $("#masterDomain").val();
                    var name = $("#name").val();
                    var subdomain = $("#subdomain").val();
                    var countryCode = encodeURIComponent( $("#country_code").val() );
                    var phoneNumber = $("#mobile").val();
                    var mail = $("#mail").val();
                    var password = $("#password").val();
                    var mikrotikType = $("#mikrotikType").val();
                    var serial = $("#serial").val();
                    var client_mac = $("#client_mac").val();
                    
                    var uniqueRealm = $("#uniqueRealm").val();
                    // save variables in session
                    $.ajax({
                        type:"post",
                        url:"{{ url('install/session') }}",
                        data:{_token: CSRF_TOKEN, name:name, subdomain:subdomain, mail:mail, password:password, serial:serial, countryCode:$("#country_code").val(), phoneNumber:phoneNumber, mikrotikType:mikrotikType, masterDomain:masterDomain, uniqueRealm:uniqueRealm, client_mac:client_mac},
                        success:function(data){              
                            if(data == 1){
                                $('#submitButton').hide();
                                $('#firstRegistrationForm').hide();
                                $('#verificationCode').show();
                                // var accountKitURL = "https://www.accountkit.com/v1.0/basic/dialog/sms_login/?";
                                // var and = "&";
                                // window.location.replace(accountKitURL+"country_code="+countryCode+and+"phone_number="+phoneNumber+and+"app_id="+accountKitAppID+and+"redirect="+redirectURL+and+"state="+CSRF_TOKEN+and+"fbAppEventsEnabled=true");
                            }
                            else{
                                $('.basic-error-general').show();
                                $('#generalValid').val("0");
                                $('.basic-error-general').text(data);
                            }
                        }
                    });
                    
                }

                function verifyCode() {
                    var verificationCode = $("#verificationCodeText").val();
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type:"post",
                        url:"{{ url('install/validateVirificationCode') }}",
                        data:{_token: CSRF_TOKEN, verificationCode:verificationCode},
                        success:function(data){              
                            if(data == 1){
                                var successLink = "{{url('/login?status=PARTIALLY_AUTHENTICATED')}}"+"&verificationCode="+verificationCode;
                                window.location.replace(successLink);
                            }else{
                                $('.basic-error-subdomain2').show();
                                $('.basic-error-subdomain2').text("Sorry! Invalid Verification Code.");
                            }
                        }
                    });
                }
                // validation on company name | (subdomain) | (dbname)
                $("#subdomain").change(function(){
                    var subdomain = $("#subdomain").val();
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                
                        $.ajax({
                            type:"post",
                            url:"{{ url('install/validation') }}",
                            data:{_token: CSRF_TOKEN, subdomain:subdomain},
                            success:function(data){

                                                
                                if(data > 0){
                                    $('.basic-error-subdomain').show();
                                    $('#subdomainValid').val("0");
                                    $('.basic-error-subdomain').text("Sorry! subdomain already taken.");
                                }
                                else{
                                    $('.basic-error-subdomain').hide();
                                    $('#subdomainValid').val("1");
                                    $('.basic-error-subdomain').removeClass('validation-error-label validation-valid-label');
                                }
                            }
                        });
                    
                });


            </script>
            @endsection
            
        @endif
 



