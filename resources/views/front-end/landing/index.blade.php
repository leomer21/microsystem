<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('/') }}upload/photo/faviconlogosmall.ico" type="image/x-icon" />

    <title>{{ App\Settings::where('type', 'app_name')->value('value') }} - Wi-Fi / Internet Landing page</title>
    <?php 
        // get PMS integration state
        if(App\Settings::where('type', 'pms_integration')->value('state') == "1"){ $pmsIntegrationState = 1;}else{$pmsIntegrationState = 0;}
        // check if the PMS premium and complementry login state is on or off
        if( isset($_GET['identify']) and isset(explode('-',$_GET['identify'])[2]) and $pmsIntegrationState == 1){
            if( App\Settings::where('type', 'pms_integration')->value('state') == "1"){
                
                $branchData = App\Branches::where('id', explode('-',$_GET['identify'])[2] )->first();
                if(isset($branchData)){
                    if($branchData->pms_premium_login_state != "1"){$pmsPremiumLoginDisabled = 1;}
                    if($branchData->pms_complementary_login_state != "1"){$pmsComplementaryLoginDisabled = 1;}
                }
            }
        }

        // check if there is only one branch and complementry is disabled 
        if(App\Branches::where('id', '1')->where('pms_complementary_login_state', '0')->count() == 1){
            $pmsComplementaryLoginDisabled = 1;
        }
        
    ?>
    <!-- Global stylesheets -->
    <link href="{{ asset('/') }}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/colors.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/extras/animate.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}landing/css/jquery.vegas.css" rel="stylesheet"><!-- Banner BG -->

    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/select2.min.js"></script>

    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/app.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/pages/login.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/pages/form_bootstrap_select.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/validation/validate.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}landing/js/jquery.flexslider.js"></script><!-- Flexslider JS -->
    <script type="text/javascript" src="{{ asset('/') }}landing/js/jquery.vegas.js"></script><!-- For Banner Slider JS -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/validation/validate.min.js"></script>
    @if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1)
        <!--<-script type="text/javascript" src="{{ asset('/') }}assets/js/sdk.js"></script> -->
        <!-- Turned of in 1.1.2017 after remove javascript from accountkit integration  -->
        <!-- <script src="https://sdk.accountkit.com/en_US/sdk.js"></script> -->
    @endif


    <!-- /theme JS files -->
</head>
<body class="login-container">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href=""><img src="{{ asset('/') }}upload/{{ App\Settings::where('type','logo')->value('value') }}"></a>
    </div>
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">
               @include('..front-end.landing.auth') 
                <!-- Footer -->
                <div class="footer text-muted text-center panel panel-body" style=" background-color: rgba(51, 51, 51, 0.8);  border: 0px solid transparent;">
                    <!--<div class="icon-object border-slate-300 text-slate-300" data-toggle="modal" data-target="#modal_form_vertical"><i class="icon-users"></i></div>-->
                    <!--<h2 class="content-group" data-toggle="modal" data-target="#modal_form_vertical" style="color: #ffffff">Login by the following options
                        <small class="display-block" style="color: #ffffff"></small>
                    </h2>-->
                    <p><button type="button" class="btn btn-danger btn-rounded btn-labeled btn-xlg" data-toggle="modal" data-target="#modal_form_vertical"><b><i class="icon-connection"></i></b> CLICK HERE FOR Wi-Fi</button></p>
                    <div class="row">

                        @if(App\Settings::where('type', 'google_client_id')->value('state') == 1) <a class="btn btn-danger btn-labeled" href="{{ url('auth/google') }}"><b><i class="icon-google"></i></b> Sign in with Google</a> @else @endif
                        @if(App\Settings::where('type', 'twitter_client_id')->value('state') == 1)<a class="btn btn-info btn-labeled" href="{{ url('auth/twitter') }}"><b><i class="icon-twitter"></i></b> Sign in with Twitter</a> @else @endif
                        <p></p>
                        @if(App\Settings::where('type', 'facebook_client_id')->value('state') == 1)<a class="btn btn-primary btn-labeled" href="{{ url('auth/facebook') }}"><b><i class="icon-facebook"></i></b> Sign in with Facebook</a> @else @endif
                        @if(App\Settings::where('type', 'linkedin_client_id')->value('state') == 1)<a class="btn bg-blue-800 btn-labeled" href="{{ url('auth/linkedin') }}"><b><i class="icon-linkedin"></i></b> Sign in with Linkedin</a> @else @endif
                    </div>
                </div>
                <!-- /footer -->
                @include('..back-end.footer')


            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->
<script>
@if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1)
	$('#form').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) { 
	    e.preventDefault();
	    return false;
	  }
	});
    // initialize Account Kit with CSRF protection
    AccountKit_OnInteractive = function() {
        AccountKit.init({
            appId: '{{ App\Settings::where('type', 'Accountkitappid')->value('value') }}',
            state: '{{ csrf_token() }}',
            version: 'v1.1',
            debug:true
        });
    };

    function loginCallback(response) {
        console.log(response);

        if (response.status === "PARTIALLY_AUTHENTICATED") {
            document.getElementById('code').value = response.code;
            {{ csrf_token() }} = response.state;
            document.getElementById('form').submit();
        }

        else if (response.status === "NOT_AUTHENTICATED") {
            // handle authentication failure
            alert('Registration has been canceled.');
        }
        else if (response.status === "BAD_PARAMS") {
            // handle bad parameters
            alert('Please enter correct mobile number.');
        }
    }

    function smsLogin() {
        var countryCode = document.getElementById('country').value;
        var phoneNumber = document.getElementById('phone').value;
        //console.log("+"+countryCode,phoneNumber)
        AccountKit.login(
                'PHONE',
                {countryCode: "+"+countryCode, phoneNumber: phoneNumber},
                loginCallback
        );
    }
@endif
</script>
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
            updateSignInButtonUI();
            // updateSignInFormUI();
            updateSignOutButtonUI();
            updateSignedInUserStatusUI();
            updateVerificationCodeFormUI();
            });

            // Event bindings.
            document.getElementById('sign-out-button').addEventListener('click', onSignOutClick);
            // document.getElementById('phone').addEventListener('keyup', updateSignInButtonUI);
            // document.getElementById('phone').addEventListener('change', updateSignInButtonUI);
            // document.getElementById('verification-code').addEventListener('keyup', updateVerifyCodeButtonUI);
            // document.getElementById('verification-code').addEventListener('change', updateVerifyCodeButtonUI);
            document.getElementById('verification-code-form').addEventListener('submit', onVerifyCodeSubmit);
            document.getElementById('cancel-verify-code-button').addEventListener('click', cancelVerification);

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
        * Function called when clicking the Login/Logout button.
        */
        function onSignInSubmit() {
            if (isPhoneNumberValid()) {
            window.signingIn = true;
            updateSignInButtonUI();
            var phoneNumber = getPhoneNumberFromUserInput();
            var appVerifier = window.recaptchaVerifier;
            firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                .then(function (confirmationResult) {
                    // SMS sent. Prompt user to type the code from the message, then sign the
                    // user in with confirmationResult.confirm(code).
                    window.confirmationResult = confirmationResult;
                    window.signingIn = false;
                    // updateSignInButtonUI();
                    updateVerificationCodeFormUI();
                    // updateVerifyCodeButtonUI();
                    // updateSignInFormUI();

                    // get variables
                    if (document.getElementById('reg-gender')) { var gender = document.getElementById('reg-gender').value;}else{var gender = "2";}
                    if (document.getElementById('reg-name')) { var name = document.getElementById('reg-name').value; }else{var name = "";}
                    if (document.getElementById('email')) { var email = document.getElementById('email').value; }else {var email = "  ";}
                    var countryCode = document.getElementById('countrycode').value;
                    var mobile = document.getElementById('phone').value;
                    var fullURL = gender+'/'+name+'/'+email+'/'+countryCode+'/'+mobile+'/'+'';
                    // alert(user.phoneNumber);
                    // updateVerificationCodeFormUI();
                    $.ajax({
                        url:'{{url()->full()}}/firebaseStep1/'+fullURL
                    });

                }).catch(function (error) {
                    // Error; SMS not sent
                    console.error('Error during signInWithPhoneNumber', error);
                    // window.alert('Error during signInWithPhoneNumber:\n\n'
                    //     + error.code + '\n\n' + error.message);
                    window.alert('\n'+ error.message);
                    window.signingIn = false;
                    // updateSignInFormUI();
                    updateSignInButtonUI();
                });
            }
        }

        /**
        * Function called when clicking the "Verify Code" button.
        */
        // var response = $.ajax({  
        //         url:'http://alemaratiya.mymicrosystem.com/firebaseVerifyCode/1/Ahmed/a@b.c/2/01061030454/1111',
        //         success:function(data)
        //         {
        //             alert(data);
        //             return(data);
        //         }
        //     });
        // console.log(response);
        // alert(response.responseText);

        function onVerifyCodeSubmit(e) {

            // verify code from firebase
            e.preventDefault();
            if (!!getCodeFromUserInput()) {
                window.verifyingCode = true;
                // updateVerifyCodeButtonUI();
                var code = getCodeFromUserInput();
                confirmationResult.confirm(code).then(function (result) {
                    // User signed in successfully.
                    var user = result.user;
                    window.verifyingCode = false;
                    window.confirmationResult = null;
                    // window.alert('firebase code OK');
                    $.ajax({
                        url:'{{url()->full()}}/firebaseCreateToken/'+user.phoneNumber,
                        success:function(data)
                        {
                            window.location.replace('{{url()->full()}}/firebaseLoginSuccess/'+data);
                        }
                    });
                }).catch(function (error) {
                     // check if the code is whatsapp code
                    if (document.getElementById('reg-gender')) { var gender = document.getElementById('reg-gender').value;}else{var gender = "2";}
                    if (document.getElementById('reg-name')) { var name = document.getElementById('reg-name').value; }else{var name = "";}
                    if (document.getElementById('email')) { var email = document.getElementById('email').value; }else {var email = "  ";}
                    if (document.getElementById('verification-code')) { var code = document.getElementById('verification-code').value; }else {var code = " ";}
                    
                    var countryCode = document.getElementById('countrycode').value;
                    var mobile = document.getElementById('phone').value;
                    var fullURL = gender+'/'+name+'/'+email+'/'+countryCode+'/'+mobile+'/'+code;
                    $.ajax({
                        url:'{{url()->full()}}/firebaseVerifyCode/'+fullURL,
                        success:function(data)
                        {
                            if(data != 0){
                                window.location.replace('{{url()->full()}}/firebaseLoginSuccess/'+data);
                            } else {
                                window.alert('Wrong Verification Code.');
                            }
                            return(data);
                        }
                    });
                    // User couldn't sign in (bad verification code?)
                    // console.error('Error while checking the verification code', error);
                    // window.alert('\n'+error.message);
                    // window.verifyingCode = false;
                    // updateSignInButtonUI();
                    // updateVerifyCodeButtonUI();
                });
            }

           
        }

        // function verifyFromFirebase(e) {
        //     e.preventDefault();
        //     if (!!getCodeFromUserInput()) {
        //         window.verifyingCode = true;
        //         // updateVerifyCodeButtonUI();
        //         var code = getCodeFromUserInput();
        //         confirmationResult.confirm(code).then(function (result) {
        //             // User signed in successfully.
        //             var user = result.user;
        //             window.verifyingCode = false;
        //             window.confirmationResult = null;
        //         }).catch(function (error) {
        //             // User couldn't sign in (bad verification code?)
        //             console.error('Error while checking the verification code', error);
        //             // window.alert('Error while checking the verification code:\n\n'
        //             //     + error.code + '\n\n' + error.message);
        //             window.alert('\n'+error.message);
        //             window.verifyingCode = false;
        //             updateSignInButtonUI();
        //             // updateVerifyCodeButtonUI();
        //         });
        //     }
        // }
        /**
        * Cancels the verification code input.
        */
        function cancelVerification(e) {
            e.preventDefault();
            window.confirmationResult = null;
            updateVerificationCodeFormUI();
            // updateSignInFormUI();
        }

        /**
        * Signs out the user when the sign-out button is clicked.
        */
        function onSignOutClick() {
            firebase.auth().signOut();
        }

        /**
        * Reads the verification code from the user input.
        */
        function getCodeFromUserInput() {
            return document.getElementById('verification-code').value;
        }

        /**
        * Reads the phone number from the user input.
        */
        function getPhoneNumberFromUserInput() {
            var a=document.getElementById('countrycode').value; 
            var b=document.getElementById('phone').value;
            return '+'+a+b;
        }

        /**
        * Returns true if the phone number is valid.
        */
        function isPhoneNumberValid() {
            var pattern = /^\+[0-9\s\-\(\)]+$/;
            var phoneNumber = getPhoneNumberFromUserInput();
            return phoneNumber.search(pattern) !== -1;
        }

        /**
        * Re-initializes the ReCaptacha widget.
        */
        function resetReCaptcha() {
            if (typeof grecaptcha !== 'undefined'
                && typeof window.recaptchaWidgetId !== 'undefined') {
            grecaptcha.reset(window.recaptchaWidgetId);
            }
        }

        /**
        * Updates the Sign-in button state depending on ReCAptcha and form values state.
        */
        function updateSignInButtonUI() {
            document.getElementById('sign-in-button').disabled =
                !isPhoneNumberValid()
                || !!window.signingIn;
        }

        /**
        * Updates the Verify-code button state depending on form values state.
        */
        // function updateVerifyCodeButtonUI() {
        //     document.getElementById('verify-code-button').disabled =
        //         !!window.verifyingCode
        //         || !getCodeFromUserInput();
        // }

        /**
        * Updates the state of the Sign-in form.
        */
        // function updateSignInFormUI() {
        //     if (firebase.auth().currentUser || window.confirmationResult) {
        //     document.getElementById('signupFormToViewOrHidden').style.display = 'none';
        //     } else {
        //     resetReCaptcha();
        //     document.getElementById('signupFormToViewOrHidden').style.display = 'block';
        //     }
        // }

        /**
        * Updates the state of the Verify code form.
        */
        function updateVerificationCodeFormUI() {
            if (!firebase.auth().currentUser && window.confirmationResult) {
            document.getElementById('verification-code-form').style.display = 'block';
            document.getElementById('sign-in-button').style.display = 'none';
            document.getElementById('signupFormToViewOrHidden').style.display = 'none';
            } else {
            document.getElementById('verification-code-form').style.display = 'none';
            document.getElementById('sign-in-button').style.display = 'block';
            document.getElementById('signupFormToViewOrHidden').style.display = 'block';
            }
        }

        /**
        * Updates the state of the Sign out button.
        */
        function updateSignOutButtonUI() {
            if (firebase.auth().currentUser) {
            document.getElementById('sign-out-button').style.display = 'block';
            } else {
            document.getElementById('sign-out-button').style.display = 'none';
            }
        }

        /**
        * Updates the Signed in user status panel.
        */
        function updateSignedInUserStatusUI() {
            var user = firebase.auth().currentUser;
            if (user) {
            // get variables
            // if (document.getElementById('reg-gender')) { var gender = document.getElementById('reg-gender').value;}else{var gender = "2";}
            // if (document.getElementById('reg-name')) { var name = document.getElementById('reg-name').value; }else{var name = "";}
            // if (document.getElementById('email')) { var email = document.getElementById('email').value; }else {var email = "  ";}
            // var countryCode = document.getElementById('countrycode').value;
            // var mobile = document.getElementById('phone').value;
            // var finalMobileFull = user.phoneNumber;
            // var fullURL = gender+'/'+name+'/'+email+'/'+countryCode+'/'+mobile+'/'+finalMobileFull;
            // alert(user.phoneNumber);
            // updateVerificationCodeFormUI();
            // $.ajax({
            //     url:'{{url()->full()}}/firebaseStep2/'+fullURL
            // });
            
            // $.ajax({
            //     url:'{{url()->full()}}/firebaseCreateToken/'+user.phoneNumber,
            //     success:function(data)
            //     {
            //         window.location.replace('{{url()->full()}}/firebaseLoginSuccess/'+data);
            //     }
            // });
            // document.getElementById('sign-in-status').textContent = 'Signed in';
            // document.getElementById('account-details').textContent = JSON.stringify(user, null, '  ');
            } else {
            // document.getElementById('sign-in-status').textContent = 'Signed out';
            // document.getElementById('account-details').textContent = 'null';
            }
        }
    </script>
@endif
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    $(window).load(function () {
        $("#loader").fadeOut();
        $("#mask").delay(1000).fadeOut("slow");
    });

    //Adding fixed position to header
    $(document).scroll(function () {
        if ($(document).scrollTop() >= 10000) {
            $('.navbar').addClass('navbar-fixed-top');
            $('html').addClass('has-fixed-nav');
        } else {
            $('.navbar').removeClass('navbar-fixed-top');
            $('html').removeClass('has-fixed-nav');
        }
    });

    // ---------- Header Slideshow ----------

    $(function () {
        $.vegas('slideshow', {
            backgrounds: [
                // check if there is a specific landing page for this branch
                @if(isset($_GET['identify']) and isset(explode('-',$_GET['identify'])[2]) and App\Media::where('type', 'custom-landing')->where('branch_id', explode('-',$_GET['identify'])[2])->count() > 0 )
                    @foreach(App\Media::where('type', 'custom-landing')->where('branch_id', explode('-',$_GET['identify'])[2])->get() as $img)
                    {
                        src: '{{ asset('/upload/media/'.$img->file) }}', fade: 1000
                    },
                    @endforeach  
                @else
                    @foreach(App\Media::where('template', 'default')->get() as $img)
                    {
                        src: '{{ asset('/upload/media/'.$img->file) }}', fade: 1000
                    },
                    @endforeach
                @endif
            ]
        })
    });


    // ---------- Flexslider Script ----------
    $('.flexslider').flexslider({
        animation: "fade",
        start: function (slider) {
            $('body').removeClass('loading');
        }
    });

    // Checkboxes
    $(".styled").uniform({
        radioClass: 'choice'
    });
    $('.select-fixed-single').select2({
        minimumResultsForSearch: Infinity,

    });
    $( ".signup" ).change(function() {
   	
        /*if ( $("#usernamevalid").val() == 1 && $("#phonevalid").val() == 1) {
            $("#signup").prop("disabled",false);
        }else{
            $("#signup").prop("disabled",true);
        }*/
    });
    $("#username").change(function(){
        var username = $("#username").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:"post",
            url:"{{ url('validation/username') }}",
            data:{_token: CSRF_TOKEN, username:username},
            success:function(data){
                if(data > 0){
                    $('.basic-error-username').show();
                    $('#usernamevalid').val("0");
                    $('.basic-error-username').text("Sorry! Username already exist.");
                }
                else{
                    $('.basic-error-username').hide();
                    $('#usernamevalid').val("1");
                    $('.basic-error-username').removeClass('validation-error-label validation-valid-label');
                }

            }
        });
    });
    $("#email").change(function(){
        var email = $("#email").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:"post",
            url:"{{ url('validation/email') }}",
            data:{_token: CSRF_TOKEN, email:email},
            success:function(data){
                if(data > 0){
                    $('.basic-error-email').show();
                    $('.basic-error-email').text("Sorry! Email already exist.");
                }
                else{
                    $('.basic-error-email').hide();
                    $('.basic-error-email').removeClass('validation-error-label validation-valid-label');
                }
            }
        });
    });
    $("#phone").change(function(){
        var mobile = $("#phone").val();
        var countrycode = $("#countrycode").val();
        var phone=countrycode.concat(mobile);
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if (countrycode == 2 && mobile.length < 11) {
                    $('.basic-error-phone').show();
                    $('.basic-error-phone').text("Please enter full mobile number");
        }else{
            $.ajax({
                type:"post",
                url:"{{ url('validation/phone') }}",
                data:{_token: CSRF_TOKEN, phone:phone},
                success:function(data){

                                    
                    if(data > 0){
                        $('.basic-error-phone').show();
                        $('#phonevalid').val("0");
                        $('.basic-error-phone').text("Sorry! Mobile already exist.");
                    }
                    else{
                        $('.basic-error-phone').hide();
                        $('#phonevalid').val("1");
                        $('.basic-error-phone').removeClass('validation-error-label validation-valid-label');
                    }
                }
            });
        }
    });
    @if(isset($errorMessage))
		$(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif

    @if(isset($smsConfirm) && $smsConfirm == 1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif

    @if(isset($successfullRegistration) && $successfullRegistration == 1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif
    @if(isset($contact_system_administrator))
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif
    @if(isset($loginAfterWaitingAdminConfirm))
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif

    @if(isset($confirm_error) && $confirm_error == 1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
    });
    @endif

    @if(isset($phone_error) &&  $phone_error == 1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif
    @if(isset($user_exist) &&  $user_exist == 1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        $('#tab2').modal('show');
        //$('#tab1').modal('hide');
    });
    @endif
    @if(isset($send_code) &&  $send_code == 1)
          $(window).load(function(){
        $('#modal_form_vertical').modal('show');
    });
    @endif
    @if(isset($forget) &&  $forget == 1)
         $(window).load(function(){
        $('#modal_form_vertical').modal('show');
    });
    @endif
    @if(isset($reset) &&  $reset == 1)
         $(window).load(function(){
        $('#modal_form_vertical').modal('show');
    });
    @endif
    @if(isset($mailsend) &&  $mailsend == 0)
         $(window).load(function(){
        $('#modal_form_vertical').modal('show');
    });
    @endif

    @if(isset($mailsend) &&  $mailsend == 1)
         $(window).load(function(){
        $('#modal_form_vertical').modal('show');
    });
    @endif
    
    @if(isset($mobile_exist) &&  $mobile_exist == 1)
        $(window).load(function(){
            $('#modal_form_vertical').modal('show');
        });
    @endif

    @if(isset($email_exist) &&  $email_exist == 1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif

    @if(isset($signup_step2) &&  $signup_step2 == 1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif

    @if(App\Settings::where('type','alwaysOpenPasswordLoginInUserCP')->value('state')==1 or Session::has('AccountkitFullMobile'))
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
        });
    @endif

</script>
</body>
</html>

