<script>
     $( document ).ready(function() {
      // Handler for .ready() called.
        @if(App\Settings::where('type', 'facebook_client_id')->value('state') != 1 and App\Settings::where('type', 'twitter_client_id')->value('state') != 1 and App\Settings::where('type', 'google_client_id')->value('state') != 1 and App\Settings::where('type', 'linkedin_client_id')->value('state') != 1)
            $('#socialMediaLogin').remove();
        @endif
        @if(App\Settings::where('type', 'facebook_client_id')->value('state') != 1)
            $('#facebook').hide();
        @endif
        @if(App\Settings::where('type', 'twitter_client_id')->value('state') != 1)
            $('#twitter').hide();
        @endif
        @if(App\Settings::where('type', 'google_client_id')->value('state') != 1)
            $('#google').hide();
        @endif
        @if(App\Settings::where('type', 'linkedin_client_id')->value('state') != 1)
            $('#linkedin').hide();
        @endif 
    });   
    $('.select-fixed-single').select2({
        minimumResultsForSearch: Infinity,
        width: 250
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
            document.getElementById('csrf').value = response.state;
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
</script>