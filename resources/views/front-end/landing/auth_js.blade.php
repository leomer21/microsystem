<script>
    $('.select-fixed-single').select2({
        minimumResultsForSearch: Infinity,
    });
        // ---------- Header Slideshow ----------

    $(function () {
        $.vegas('slideshow', {
            backgrounds: [
                @foreach(App\Media::where('state','1')->where('template', 'template1')->get() as $img)
                {
                    src: '{{ asset('/upload/media/'.$img->file) }}', fade: 1000
                },
                @endforeach
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



    // Checkboxes
    $(".styled").uniform({
        radioClass: 'choice'
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


    @if(App\Settings::where('type','alwaysOpenPasswordLoginInUserCP')->value('state')==1)
        $(window).load(function(){
        $('#modal_form_vertical').modal('show');
    });
    @endif
</script>