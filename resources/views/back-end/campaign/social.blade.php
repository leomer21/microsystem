<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $campaign->offer_title }} &nbsp</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('/') }}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->
   
   <!-- Pic loading -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}landing/js/jquery.vegas.js"></script>
   <!-- Pic loading -->
   
 <!-- For Banner Slider JS -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/velocity/velocity.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/velocity/velocity.ui.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/buttons/spin.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/buttons/ladda.min.js"></script>

    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
    <script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/app.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}jquery.countdown.js"></script>

    <!-- /theme JS files -->

</head>

<?php
    // check facebook return OK or post not shared
    if(strpos($_SERVER['REQUEST_URI'], 'done') !== false) {
        // check of facebook return error
        if(strpos($_SERVER['REQUEST_URI'], 'error_code') !== false) {
        
        }else{
            // recived request from facebook like that "?#_=_" without "?error_code=" so we will redirect user to fonal page
            $newUrl = url('/campaign_offline?campaign=').$campaign->id.'&u_id='.$userid;
            echo '<meta http-equiv="refresh" content="0;url='.$newUrl.'finalpage.html">';
        }
    }

    // browser check of Chrome or not to deside: show link or pop up
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
        $chrome = 1;
    }else{
        $chrome = 0;
    }

?>


<body class="login-container">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href=""><img src="{{ asset('/') }}upload/{{ App\Settings::where('type','logo')->value('value') }}" alt=""></a>
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

            <!--<div class="panel panel-body">
                    <div class="text-center">
                        <div class="icon-object border-slate-300 text-slate-300"><i class="icon-quill4"></i></div>
                        <h5 class="content-group">Login to your account
                            <small class="display-block">Enter your credentials below</small>
                        </h5>
                    </div>
                    <center>
                        @if($campaign->social_network == 1)
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-labeled btn-xlg" id="share"><b><i
                                    class="icon-facebook"></i></b>
                        Share offer
                    </button>
                </div>
            @endif
            @if($campaign->social_network == 2)
                <div class="form-group">
                    <a class="btn btn-info btn-labeled btn-xlg entypo-twitter">
                        <b><i class="icon-twitter"></i></b>
                        Share offer
                    </a>
                </div>
            @endif
                    </center>
                </div>-->


                <!-- Footer -->
                <div class="footer text-muted text-center panel panel-body" style=" background-color: rgba(51, 51, 51, 0.8);  border: 0px solid transparent;">
                    <div class="text-center">
                        <!--<div class="icon-object border-slate-300 text-slate-300"><i class="icon-quill4"></i></div>-->
                        <h2 class="content-group" style="color: #ffffff">{{ $campaign->offer_title }}
                            <small class="display-block" style="color: #ffffff">{{ $campaign->offer_desc }}</small>
                        </h2>
                    </div>
                    <center>

                        @if($chrome == 1)
                            <!-- Enable pop up -->
                            @if($campaign->social_network == 1)
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-labeled btn-xlg" id="share"><b><i
                                                    class="icon-facebook"></i></b>
                                        Send this offer to {{$campaign->invite_friends}} of your special friends
                                    </button>
                                </div>
                            @endif
                            @if($campaign->social_network == 2)
                                <div class="form-group">
                                    <a class="btn btn-info btn-labeled btn-xlg entypo-twitter">
                                        <b><i class="icon-twitter"></i></b>
                                        Send this offer to {{$campaign->invite_friends}} of your special friends
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- Just Link -->
                            <div class="form-group">
                                <a type="button" class="btn btn-primary btn-labeled btn-xlg" href="https://www.facebook.com/dialog/share?client_id={{ App\Settings::where('type', 'facebook_client_id')->value('value') }}&link={{ url('specialoffer/'. $userid .'/'.$campaign->id .'/'.rand(121121,999999)) }}&href={{ url('specialoffer/'. $userid .'/'.$campaign->id .'/'.rand(121121,999999)) }}&redirect_uri={{ url('campaign_offline?done=1') }}&fallback_redirect_uri={{ url('campaign_offline?no=0') }}" ><b><i class="icon-facebook"></i></b>
                                    Send this offer to {{$campaign->invite_friends}} of your special friends
                                </a>
                            </div>
                        @endif
                            <h5 class="content-group connectaaaa" id="timer" style="color: #ffffff">Free Wireless Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                        <button type="button" class="btn btn-default btn-ladda btn-ladda-spinner connect"
                                data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                    class="ladda-label">Connect</span></button>
                    </center>
                </div>
                <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>

<script>
    // ---------- Header Slideshow ----------

    $(function () {
        $.vegas('slideshow', {
            backgrounds: [
                @foreach(App\Media::where('campaign_id', $campaign->id)->get() as $campaigns)
                {
                    src: '{{ asset('') }}upload/campaigns/{{ $campaigns->file }}', fade: 1000
                },
                @endforeach
            ]
        })
    });

</script>
<!-- /page container -->
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {


        $('.connect').delay({{ $campaign->delay * 1000 }}).show(0);
        $('.connectaaaa').delay({{ $campaign->delay  * 1000}}).hide(0);


        var sec = $('#timer span').text() || 0;
        var timer = setInterval(function() {
            $('#timer span').text(--sec);
            if (sec == 0) {
                $('#timer').fadeOut('fast');
                clearInterval(timer);
            }
        }, 1000);

        // Button with progress
        Ladda.bind('.btn-ladda-spinner', {
            dataSpinnerSize: 16,
            timeout: 2000,
            callback: function (instance) {
                window.location.href = "{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/account') }}";
            }
        });


    });

    ;
    window.fbAsyncInit = function () {
        FB.init({
            appId: '{{ App\Settings::where('type', 'facebook_client_id')->value('value') }}', status: true, cookie: true,
            xfbml: true
        });
    }

    document.getElementById('share').onclick = function () {
        FB.ui(
                {
                    @if($campaign->social_post_type == 1)
                    method: 'feed',
                    @elseif($campaign->social_post_type == 2)
                    method: 'share',
                    @elseif($campaign->social_post_type == 3)
                    method: 'send',
                    @endif
                    display: 'popup',
                    name: '{{ $campaign->offer_title }} ',

                    href: '{{ url('specialoffer/'. $userid .'/'.$campaign->id .'/'.rand(121121,999999)) }}',
                    link: '{{ url('specialoffer/'. $userid .'/'.$campaign->id .'/'.rand(121121,999999)) }}',

                    // stoped because facebook change post content to the content of page (LINK) (http://demo.microsystem.com.eg/specialoffer/124/8/234) 4.5.2019
                    <?php //$pic = App\Media::where('type', 'campaigns')->where('campaign_id', $campaign->id)->take(1)->get(); ?>
                    //     @if(isset($pic) && count($pic) == 1)
                    //         @foreach($pic as $campaigns)
                    //             picture: '{{ asset('') }}upload/campaigns/{{ $campaigns->file }}',
                    //         @endforeach
                    //     @else
                    //         picture: '',
                    //     @endif
                    // caption: '{{ App\Settings::where('type', 'app_name')->value('value') }}.',
                    // description: '{{ $campaign->offer_title }}',
                    // message: ''
                },
                // callback
                function (response) {
                    if (response && !response.error_message) {
                        window.location.href = "{{ url('/campaign_offline?campaign=')}}{{$campaign->id }}&u_id={{ $userid }}";
                    } else {
                        new PNotify({
                            title: 'Opss!',
                            text: 'Post has been cancelled, please post again.',
                            addclass: 'bg-danger'
                        });
                    }
                }
        );
    }

    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });
    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });
    $(function () {
        $('#maximage').maximage({
            cycleOptions: {
                fx: 'fade',
                speed: 1000, // Has to match the speed for CSS transitions in jQuery.maximage.css (lines 30 - 33)
                timeout: 5000,
                prev: '#arrow_left',
                next: '#arrow_right',
                pause: 0,
                before: function (last, current) {
                    if (!$.browser.msie) {
                        // Start HTML5 video when you arrive
                        if ($(current).find('video').length > 0) $(current).find('video')[0].play();
                    }
                },
                after: function (last, current) {
                    if (!$.browser.msie) {
                        // Pauses HTML5 video when you leave it
                        if ($(last).find('video').length > 0) $(last).find('video')[0].pause();
                    }
                }
            },
            onFirstImageLoaded: function () {
                jQuery('#cycle-loader').hide();
                jQuery('#maximage').fadeIn('fast');
            }
        });

        // Helper function to Fill and Center the HTML5 Video
        jQuery('video,object').maximage('maxcover');

    });


    // Show form
    var form = $(".steps-validation").show();

    // Select2 selects
    $('.select').select2();

</script>
</body>
</html>
