<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $campaign->ad_name }}</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('/') }}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/colors.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('/preview') }}/css/maximage.css" type="text/css" media="screen"  charset="utf-8"/>
    <link rel="stylesheet" href="https://rawgit.com/kremalicious/appstorebadges/master/dist/appstorebadges.min.css">

    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/velocity/velocity.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/velocity/velocity.ui.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/buttons/spin.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/buttons/ladda.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script src="{{ asset('/preview') }}/js/jquery.maximage.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('/preview') }}/js/jquery.fullscreen.js" type="text/javascript" charset="utf-8"></script>


    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
    <script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/app.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}jquery.countdown.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/pages/form_bootstrap_select.js"></script>

    <!-- /theme JS files -->

</head>

<style>
    @if($campaign->type == "video")
    iframe {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: 0;
        background: url('/assets/images/polina.jpg') no-repeat;
        background-size: cover;
    }

    /*****************************/

    .overlay {
        width: 400px;
        height: 400px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        background: rgba(0,0,0,0.3);
        display: block;
        position: absolute;
        top: 10%;
        left: 30%;
    }

    .overlay h1 {
        text-align: center;
        padding-top: 100px;
        color: #fff;
        font-family: inherit;
    }

    .overlay p{
        text-align: center;
        width: 50%;
        margin: 0 auto;
        color: #fff;
        font-family: inherit;
        margin-bottom: 20px;
        transform-origin: top 0 left 0;
    }

    .overlay a {
        color: #fff;
    }

    @-webkit-keyframes shake {
        0% { -webkit-transform: translate(2px, 1px) rotate(0deg); }
        10% { -webkit-transform: translate(-1px, -2px) rotate(-1deg); }
        20% { -webkit-transform: translate(-3px, 0px) rotate(1deg); }
        30% { -webkit-transform: translate(0px, 2px) rotate(0deg); }
        40% { -webkit-transform: translate(1px, -1px) rotate(1deg); }
        50% { -webkit-transform: translate(-1px, 2px) rotate(-1deg); }
        60% { -webkit-transform: translate(-3px, 1px) rotate(0deg); }
        70% { -webkit-transform: translate(2px, 1px) rotate(-1deg); }
        80% { -webkit-transform: translate(-1px, -1px) rotate(1deg); }
        90% { -webkit-transform: translate(2px, 2px) rotate(0deg); }
        100% { -webkit-transform: translate(1px, -2px) rotate(-1deg); }
    }


    .overlay:hover,
    .overlay:focus{
        -webkit-animation: shake 0.8s linear infinite;
        animation: shake 0.2s linear infinite;

    }
    @endif
</style>
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
                @if($campaign->type == "video")


                @else
                <div id="maximage">
                    @foreach(App\Media::where('type', 'campaigns')->where('campaign_id', $campaign->id)->get() as $campaigns)
                        <div>
                            <img src="{{ asset('') }}/upload/campaigns/{{ $campaigns->file }}" alt=""/>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Footer -->
                @if($campaign->type == "video")
                    <?php $transparentLevel = 0.2; ?>
                @else
                    <?php $transparentLevel = 0.8; ?>
                @endif

                <div class="footer text-muted text-center panel panel-body" style=" background-color: rgba(51, 51, 51, {{$transparentLevel}});  border: 0px solid transparent;">
                    @if($campaign->type == "website") <!-- Connect & visit -->
                        <div class="text-center">
                            <!--<div class="icon-object border-slate-300 text-slate-300"><i class="icon-quill4"></i></div>-->
                            <h2 class="content-group" style="color: #ffffff">{{ $campaign->text }}</h2>
                        </div>
                        <center>
                            @if(isset($userid))
                                <div class="form-group">
                                    <a class="btn btn-primary btn-labeled btn-xlg" href="{{ url('campaign_click/'.$campaign->id.'/'.$userid) }}"><b><i
                                                    class="icon-link"></i></b>
                                        Connect & visit
                                    </a>
                                </div>
                                <div class="form-group">
                                    <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                    <a href="{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/account') }}" class="btn btn-default btn-ladda btn-ladda-spinner connect"
                                            data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                class="ladda-label">Connect</span></a>
                                </div>
                            @else
                                <div class="form-group">
                                    <a class="btn btn-primary btn-labeled btn-xlg" href="#"><b><i
                                                    class="icon-link"></i></b>
                                        Connect & visit
                                    </a>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                        <a href="#" class="btn btn-default btn-ladda btn-ladda-spinner connect" data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                class="ladda-label">Connect</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </center>
                    @elseif($campaign->type == "apps")<!--Connect & install-->

                        <div class="text-center">
                            <!--<div class="icon-object border-slate-300 text-slate-300"><i class="icon-quill4"></i></div>-->
                            <h2 class="content-group" style="color: #ffffff">{{ $campaign->text }}</h2>
                        </div>
                        <center>
                            @if(isset($userid))
                                <h2 class="content-group" style="color: #ffffff">Download app to connect</h2>

                                <a class="badge" href="{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/ios') }}">
                                    <svg class="badge__icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.5640259,13.8623047
                                        c-0.4133301,0.9155273-0.6115723,1.3251343-1.1437988,2.1346436c-0.7424927,1.1303711-1.7894897,2.5380249-3.086853,2.5500488
                                        c-1.1524048,0.0109253-1.4483032-0.749939-3.0129395-0.741333c-1.5640259,0.008606-1.8909302,0.755127-3.0438843,0.7442017
                                        c-1.296814-0.0120239-2.2891235-1.2833252-3.0321655-2.4136963c-2.0770874-3.1607666-2.2941895-6.8709106-1.0131836-8.8428955
                                        c0.9106445-1.4013062,2.3466187-2.2217407,3.6970215-2.2217407c1.375,0,2.239502,0.7539673,3.3761597,0.7539673
                                        c1.1028442,0,1.7749023-0.755127,3.3641357-0.755127c1.201416,0,2.4744263,0.6542969,3.3816528,1.7846069
                                        C14.0778809,8.4837646,14.5608521,12.7279663,17.5640259,13.8623047z M12.4625244,3.8076782
                                        c0.5775146-0.741333,1.0163574-1.7880859,0.8571167-2.857666c-0.9436035,0.0653076-2.0470581,0.6651611-2.6912842,1.4477539	C10.0437012,3.107605,9.56073,4.1605835,9.7486572,5.1849365C10.7787476,5.2164917,11.8443604,4.6011963,12.4625244,3.8076782z"></path>
                                    </svg>
                                    <span class="badge__text">Download on the</span>
                                    <span class="badge__storename">App Store</span>
                                </a>

                                <a class="badge" href="{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/android') }}">
                                    <svg class="badge__icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" viewBox="0 0 20 20">
                                        <path d="M4.942627,18.0508423l7.6660156-4.3273926l-1.6452026-1.8234253L4.942627,18.0508423z M2.1422119,2.1231079
                                        C2.0543823,2.281311,2,2.4631958,2,2.664917v15.1259766c0,0.2799683,0.1046143,0.5202026,0.2631226,0.710144l7.6265259-7.7912598
                                        L2.1422119,2.1231079z M17.4795532,9.4819336l-2.6724854-1.508606l-2.72229,2.7811279l1.9516602,2.1630249l3.4431152-1.9436035
                                        C17.7927856,10.8155518,17.9656372,10.5287476,18,10.2279053C17.9656372,9.927063,17.7927856,9.6402588,17.4795532,9.4819336z
                                        M13.3649292,7.1592407L4.1452026,1.954834l6.8656616,7.609314L13.3649292,7.1592407z"></path>
                                    </svg>
                                    <span class="badge__text">Get it on</span>
                                    <span class="badge__storename">Google Play</span>
                                </a>

                                <div class="form-group">
                                    <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                    <a href="{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/account') }}" class="btn btn-default btn-ladda btn-ladda-spinner connect"
                                       data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                class="ladda-label">Connect</span></a>
                                </div>
                            @else
                                <div class="form-group">
                                    <a class="btn btn-primary btn-labeled btn-xlg" href="#"><b><i
                                                    class="icon-link"></i></b>
                                        Connect & install
                                    </a>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                        <a href="#" class="btn btn-default btn-ladda btn-ladda-spinner connect" data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                    class="ladda-label">Connect</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </center>
                    @elseif($campaign->type == "survey")
                            <div class="text-center">
                                <!--<div class="icon-object border-slate-300 text-slate-300"><i class="icon-quill4"></i></div>-->
                                <h2 class="content-group" style="color: #ffffff">{{ $campaign->question }}</h2>
                            </div>
                            <center>
                                @if(isset($userid))
                                    @if($campaign->survey_type == "rating")
                                        <style>
                                            @import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

                                            /****** Style Star Rating Widget *****/

                                            .rating {
                                                border: none;
                                                float: left;
                                            }

                                            .rating > input { display: none; }
                                            .rating > label:before {
                                                margin: 5px;
                                                font-size: 3.25em;
                                                font-family: FontAwesome;
                                                display: inline-block;
                                                content: "\f005";
                                            }

                                            .rating > .half:before {
                                                content: "\f089";
                                                position: absolute;
                                            }

                                            .rating > label {
                                                color: #ddd;
                                                float: right;
                                            }

                                            /***** CSS Magic to Highlight Stars on Hover *****/

                                            .rating > input:checked ~ label, /* show gold star when clicked */
                                            .rating:not(:checked) > label:hover, /* hover current star */
                                            .rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

                                            .rating > input:checked + label:hover, /* hover current star when changing rating */
                                            .rating > input:checked ~ label:hover,
                                            .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
                                            .rating > input:checked ~ label:hover ~ label { color: #FFED85;  }
                                        </style>
                                        <div class="row">
                                            <form action="{{ url('survey_vote') }}" method="post" id="vote">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="userid" value="{{ $userid }}">
                                                <input type="hidden" name="campaign" value="{{ $campaign->id }}">
                                                <input type="hidden" name="type" value="rating">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-6">
                                                    <fieldset class="rating">
                                                        <input type="radio" id="star5" name="rating" value="5" onclick="document.forms['vote'].submit(); return false;" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>
                                                        <input type="radio" id="star4half" name="rating" value="4.5" onclick="document.forms['vote'].submit(); return false;" /><label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
                                                        <input type="radio" id="star4" name="rating" value="4" onclick="document.forms['vote'].submit(); return false;" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                                        <input type="radio" id="star3half" name="rating" value="3.5" onclick="document.forms['vote'].submit(); return false;" /><label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                                                        <input type="radio" id="star3" name="rating" value="3" onclick="document.forms['vote'].submit(); return false;" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
                                                        <input type="radio" id="star2half" name="rating" value="2.5" onclick="document.forms['vote'].submit(); return false;" /><label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                                                        <input type="radio" id="star2" name="rating" value="2" onclick="document.forms['vote'].submit(); return false;" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                                        <input type="radio" id="star1half" name="rating" value="1.5" onclick="document.forms['vote'].submit(); return false;" /><label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                                                        <input type="radio" id="star1" name="rating" value="1" onclick="document.forms['vote'].submit(); return false;" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                                                        <input type="radio" id="starhalf" name="rating" value="0.5" onclick="document.forms['vote'].submit(); return false;" /><label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                                                    </fieldset>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                    <div class="row">
                                        <form action="{{ url('survey_vote') }}" method="post" id="vote">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="userid" value="{{ $userid }}">
                                            <input type="hidden" name="campaign" value="{{ $campaign->id }}">
                                            <input type="hidden" name="type" value="poll">
                                            <div class="form-group col-lg-3"></div>
                                            <div class="form-group col-lg-6">
                                                @foreach(App\Models\Survey::where('campaign_id', $campaign->id)->whereNull('u_id')->get() as $survey)
                                                    <div class="radio">
                                                        <label style="color: #ffffff">
                                                            <input type="radio" name="option" class="control-primary"
                                                                   value="{{ $survey->id }}">
                                                            {{  $survey->options }}
                                                            <span class="text-muted">({{  App\Models\Survey::where(['options' => $survey->id, 'campaign_id' => $campaign->id])->count() }}
                                                                )</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <a href="#"
                                                   class="btn bg-blue btn-block"
                                                   onclick="document.forms['vote'].submit(); return false;">Vote</a>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                @else
                                    @if($campaign->survey_type == "rating")

                                    @else
                                    <div class="row">
                                        <div class="form-group col-lg-3"></div>
                                        <div class="form-group col-lg-6">
                                            @foreach(App\Models\Survey::where('campaign_id', $campaign->id)->whereNull('u_id')->get() as $survey)
                                                <div class="radio">
                                                    <label style="color: #ffffff">
                                                        <input type="radio" name="option" class="control-primary"
                                                               value="{{ $survey->id }}">
                                                        {{  $survey->options }}
                                                        <span class="text-muted">({{  App\Models\Survey::where(['options' => $survey->id, 'campaign_id' => $campaign->id])->count() }}
                                                            )</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                            <a href="#" class="btn bg-blue btn-block"
                                               onclick="document.forms['vote'].submit(); return false;">Vote</a>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                            </center>
                    @elseif($campaign->type == "social") <!-- Connect & check in -->
                            <div class="text-center">
                                <!--<div class="icon-object border-slate-300 text-slate-300"><i class="icon-quill4"></i></div>-->
                                <h2 class="content-group" style="color: #ffffff">{{ $campaign->text }}</h2>
                            </div>
                            <center>
                                @if(isset($userid))
                                    <div class="form-group">
                                        <a class="btn btn-primary btn-labeled btn-xlg" href="{{ url('campaign_click/'.$campaign->id.'/'.$userid) }}"><b><i
                                                        class="icon-link"></i></b>
                                            Connect & check in
                                        </a>
                                    </div>
                                    <div class="form-group">
                                        <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                        <a href="{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/account') }}" class="btn btn-default btn-ladda btn-ladda-spinner connect"
                                           data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                    class="ladda-label">Connect</span></a>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <a class="btn btn-primary btn-labeled btn-xlg" href="#"><b><i
                                                        class="icon-link"></i></b>
                                            Connect & check in
                                        </a>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                            <a href="#" class="btn btn-default btn-ladda btn-ladda-spinner connect" data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                        class="ladda-label">Connect</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </center>
                    @elseif($campaign->type == "offer") <!-- Connect & get the offer -->
                        <!-- Success modal -->
                        <div id="offer" class="modal fade">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h6 class="modal-title">Get the offer</h6>
                                    </div>
 
                                    <div class="modal-body">
                                        <div class="row">
                                            <form action="{{ url('get_offer') }}" method="post" id="get-offer">
                                                {{ csrf_field() }}
                                                @if($campaign->offer_sendsms == 1)
                                                    <div class="row">
                                                        <h6 class="text-semibold">Please enter your mobile number to recive your offer code by SMS</h6>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-3">
                                                            <div class="form-group has-feedback">
                                                                <select class="bootstrap-select" data-width="100%"  required name="countrycode">
                                                                    <?php $systemCountry=App\Settings::where('type', 'country')->value('value'); ?>
                                                                    <option @if($systemCountry=="Saudi Arabia") selected @endif value="966">Saudi Arabia +966
                                                                    </option>
                                                                    <option @if($systemCountry=="United Arab Emirates") selected @endif value="971">United Arab Emirates +971
                                                                    </option>
                                                                    <option @if($systemCountry=="Qatar") selected @endif value="974">Qatar +974
                                                                    </option>
                                                                    <option @if($systemCountry=="Iraq") selected @endif value="964">Iraq +964
                                                                    </option>
                                                                    <option @if($systemCountry=="Kuwait") selected @endif value="965">Kuwait +965
                                                                    </option>
                                                                    <option @if($systemCountry=="Lebanon") selected @endif value="961">Lebanon +961
                                                                    </option>
                                                                    <option @if($systemCountry=="Jordan") selected @endif value="962">Jordan +962
                                                                    </option>
                                                                    <option @if($systemCountry=="Egypt") selected @endif value="2">Egypt
                                                                    </option>
                                                                    <option @if($systemCountry=="Gambia") selected @endif value="220">Gambia +220
                                                                    </option>
                                                                    <option value="44">UK +44
                                                                    </option>
                                                                    <option value="1">USA +1
                                                                    </option>
                                                                    <option value="213">Algeria +213
                                                                    </option>
                                                                    <option value="376">Andorra +376
                                                                    </option>
                                                                    <option value="244">Angola +244
                                                                    </option>
                                                                    <option value="1264">Anguilla +1264
                                                                    </option>
                                                                    <option value="1268">Antigua &amp; Barbuda +1268
                                                                    </option>
                                                                    <option value="599">Antilles Dutch +599
                                                                    </option>
                                                                    <option value="54">Argentina +54
                                                                    </option>
                                                                    <option value="374">Armenia +374
                                                                    </option>
                                                                    <option value="297">Aruba +297
                                                                    </option>
                                                                    <option value="247">Ascension Island +247
                                                                    </option>
                                                                    <option value="61">Australia +61
                                                                    </option>
                                                                    <option value="43">Austria +43
                                                                    </option>
                                                                    <option value="994">Azerbaijan +994
                                                                    </option>
                                                                    <option value="1242">Bahamas +1242
                                                                    </option>
                                                                    <option value="973">Bahrain +973
                                                                    </option>
                                                                    <option value="880">Bangladesh +880
                                                                    </option>
                                                                    <option value="1246">Barbados +1246
                                                                    </option>
                                                                    <option value="375">Belarus +375
                                                                    </option>
                                                                    <option value="32">Belgium +32
                                                                    </option>
                                                                    <option value="501">Belize +501
                                                                    </option>
                                                                    <option value="229">Benin +229
                                                                    </option>
                                                                    <option value="1441">Bermuda +1441
                                                                    </option>
                                                                    <option value="975">Bhutan +975
                                                                    </option>
                                                                    <option value="591">Bolivia +591
                                                                    </option>
                                                                    <option value="387">Bosnia Herzegovina +387
                                                                    </option>
                                                                    <option value="267">Botswana +267
                                                                    </option>
                                                                    <option value="55">Brazil +55
                                                                    </option>
                                                                    <option value="673">Brunei +673
                                                                    </option>
                                                                    <option value="359">Bulgaria +359
                                                                    </option>
                                                                    <option value="226">Burkina Faso +226
                                                                    </option>
                                                                    <option value="257">Burundi +257
                                                                    </option>
                                                                    <option value="855">Cambodia +855
                                                                    </option>
                                                                    <option value="237">Cameroon +237
                                                                    </option>
                                                                    <option value="1">Canada +1
                                                                    </option>
                                                                    <option value="238">Cape Verde Islands +238
                                                                    </option>
                                                                    <option value="1345">Cayman Islands +1345
                                                                    </option>
                                                                    <option value="236">Central African Republic +236
                                                                    </option>
                                                                    <option value="56">Chile +56
                                                                    </option>
                                                                    <option value="86">China +86
                                                                    </option>
                                                                    <option value="57">Colombia +57
                                                                    </option>
                                                                    <option value="269">Comoros +269
                                                                    </option>
                                                                    <option value="242">Congo +242
                                                                    </option>
                                                                    <option value="682">Cook Islands +682
                                                                    </option>
                                                                    <option value="506">Costa Rica +506
                                                                    </option>
                                                                    <option value="385">Croatia +385
                                                                    </option>
                                                                    <option value="53">Cuba +53
                                                                    </option>
                                                                    <option value="90392">Cyprus North +90392
                                                                    </option>
                                                                    <option value="357">Cyprus South +357
                                                                    </option>
                                                                    <option value="42">Czech Republic +42
                                                                    </option>
                                                                    <option value="45">Denmark +45
                                                                    </option>
                                                                    <option value="2463">Diego Garcia +2463
                                                                    </option>
                                                                    <option value="253">Djibouti +253
                                                                    </option>
                                                                    <option value="1809">Dominica +1809
                                                                    </option>
                                                                    <option value="1809">Dominican Republic +1809
                                                                    </option>
                                                                    <option value="593">Ecuador +593
                                                                    </option>
                                                                    <option value="2">Egypt
                                                                    </option>
                                                                    <option value="353">Eire +353
                                                                    </option>
                                                                    <option value="503">El Salvador +503
                                                                    </option>
                                                                    <option value="240">Equatorial Guinea +240
                                                                    </option>
                                                                    <option value="291">Eritrea +291
                                                                    </option>
                                                                    <option value="372">Estonia +372
                                                                    </option>
                                                                    <option value="251">Ethiopia +251
                                                                    </option>
                                                                    <option value="500">Falkland Islands +500
                                                                    </option>
                                                                    <option value="298">Faroe Islands +298
                                                                    </option>
                                                                    <option value="679">Fiji +679
                                                                    </option>
                                                                    <option value="358">Finland +358
                                                                    </option>
                                                                    <option value="33">France +33
                                                                    </option>
                                                                    <option value="594">French Guiana +594
                                                                    </option>
                                                                    <option value="689">French Polynesia +689
                                                                    </option>
                                                                    <option value="241">Gabon +241
                                                                    </option>
                                                                    <option value="220">Gambia +220
                                                                    </option>
                                                                    <option value="7880">Georgia +7880
                                                                    </option>
                                                                    <option value="49">Germany +49
                                                                    </option>
                                                                    <option value="233">Ghana +233
                                                                    </option>
                                                                    <option value="350">Gibraltar +350
                                                                    </option>
                                                                    <option value="30">Greece +30
                                                                    </option>
                                                                    <option value="299">Greenland +299
                                                                    </option>
                                                                    <option value="1473">Grenada +1473
                                                                    </option>
                                                                    <option value="590">Guadeloupe +590
                                                                    </option>
                                                                    <option value="671">Guam +671
                                                                    </option>
                                                                    <option value="502">Guatemala +502
                                                                    </option>
                                                                    <option value="224">Guinea +224
                                                                    </option>
                                                                    <option value="245">Guinea - Bissau +245
                                                                    </option>
                                                                    <option value="592">Guyana +592
                                                                    </option>
                                                                    <option value="509">Haiti +509
                                                                    </option>
                                                                    <option value="504">Honduras +504
                                                                    </option>
                                                                    <option value="852">Hong Kong +852
                                                                    </option>
                                                                    <option value="36">Hungary +36
                                                                    </option>
                                                                    <option value="354">Iceland +354
                                                                    </option>
                                                                    <option value="91">India +91
                                                                    </option>
                                                                    <option value="62">Indonesia +62
                                                                    </option>
                                                                    <option value="98">Iran +98
                                                                    </option>
                                                                    <option value="964">Iraq +964
                                                                    </option>
                                                                    <option value="972">Israel +972
                                                                    </option>
                                                                    <option value="39">Italy +39
                                                                    </option>
                                                                    <option value="225">Ivory Coast +225
                                                                    </option>
                                                                    <option value="1876">Jamaica +1876
                                                                    </option>
                                                                    <option value="81">Japan +81
                                                                    </option>
                                                                    <option value="962">Jordan +962
                                                                    </option>
                                                                    <option value="7">Kazakhstan +7
                                                                    </option>
                                                                    <option value="254">Kenya +254
                                                                    </option>
                                                                    <option value="686">Kiribati +686
                                                                    </option>
                                                                    <option value="850">Korea North +850
                                                                    </option>
                                                                    <option value="82">Korea South +82
                                                                    </option>
                                                                    <option value="965">Kuwait +965
                                                                    </option>
                                                                    <option value="996">Kyrgyzstan +996
                                                                    </option>
                                                                    <option value="856">Laos +856
                                                                    </option>
                                                                    <option value="371">Latvia +371
                                                                    </option>
                                                                    <option value="961">Lebanon +961
                                                                    </option>
                                                                    <option value="266">Lesotho +266
                                                                    </option>
                                                                    <option value="231">Liberia +231
                                                                    </option>
                                                                    <option value="218">Libya +218
                                                                    </option>
                                                                    <option value="417">Liechtenstein +417
                                                                    </option>
                                                                    <option value="370">Lithuania +370
                                                                    </option>
                                                                    <option value="352">Luxembourg +352
                                                                    </option>
                                                                    <option value="853">Macao +853
                                                                    </option>
                                                                    <option value="389">Macedonia +389
                                                                    </option>
                                                                    <option value="261">Madagascar +261
                                                                    </option>
                                                                    <option value="265">Malawi +265
                                                                    </option>
                                                                    <option value="60">Malaysia +60
                                                                    </option>
                                                                    <option value="960">Maldives +960
                                                                    </option>
                                                                    <option value="223">Mali +223
                                                                    </option>
                                                                    <option value="356">Malta +356
                                                                    </option>
                                                                    <option value="692">Marshall Islands +692
                                                                    </option>
                                                                    <option value="596">Martinique +596
                                                                    </option>
                                                                    <option value="222">Mauritania +222
                                                                    </option>
                                                                    <option value="269">Mayotte +269
                                                                    </option>
                                                                    <option value="52">Mexico +52
                                                                    </option>
                                                                    <option value="691">Micronesia +691
                                                                    </option>
                                                                    <option value="373">Moldova +373
                                                                    </option>
                                                                    <option value="377">Monaco +377
                                                                    </option>
                                                                    <option value="976">Mongolia +976
                                                                    </option>
                                                                    <option value="1664">Montserrat +1664
                                                                    </option>
                                                                    <option value="212">Morocco +212
                                                                    </option>
                                                                    <option value="258">Mozambique +258
                                                                    </option>
                                                                    <option value="95">Myanmar +95
                                                                    </option>
                                                                    <option value="264">Namibia +264
                                                                    </option>
                                                                    <option value="674">Nauru +674
                                                                    </option>
                                                                    <option value="977">Nepal +977
                                                                    </option>
                                                                    <option value="31">Netherlands +31
                                                                    </option>
                                                                    <option value="687">New Caledonia +687
                                                                    </option>
                                                                    <option value="64">New Zealand +64
                                                                    </option>
                                                                    <option value="505">Nicaragua +505
                                                                    </option>
                                                                    <option value="227">Niger +227
                                                                    </option>
                                                                    <option value="234">Nigeria +234
                                                                    </option>
                                                                    <option value="683">Niue +683
                                                                    </option>
                                                                    <option value="672">Norfolk Islands +672
                                                                    </option>
                                                                    <option value="670">Northern Marianas +670
                                                                    </option>
                                                                    <option value="47">Norway +47
                                                                    </option>
                                                                    <option value="968">Oman +968
                                                                    </option>
                                                                    <option value="680">Palau +680
                                                                    </option>
                                                                    <option value="507">Panama +507
                                                                    </option>
                                                                    <option value="675">Papua New Guinea +675
                                                                    </option>
                                                                    <option value="595">Paraguay +595
                                                                    </option>
                                                                    <option value="51">Peru +51
                                                                    </option>
                                                                    <option value="63">Philippines +63
                                                                    </option>
                                                                    <option value="48">Poland +48
                                                                    </option>
                                                                    <option value="351">Portugal +351
                                                                    </option>
                                                                    <option value="1787">Puerto Rico +1787
                                                                    </option>
                                                                    <option value="974">Qatar +974
                                                                    </option>
                                                                    <option value="262">Reunion +262
                                                                    </option>
                                                                    <option value="40">Romania +40
                                                                    </option>
                                                                    <option value="7">Russia +7
                                                                    </option>
                                                                    <option value="250">Rwanda +250
                                                                    </option>
                                                                    <option value="378">San Marino +378
                                                                    </option>
                                                                    <option value="239">Sao Tome &amp; Principe +239
                                                                    </option>
                                                                    <option value="966">Saudi Arabia +966
                                                                    </option>
                                                                    <option value="221">Senegal +221
                                                                    </option>
                                                                    <option value="381">Serbia +381
                                                                    </option>
                                                                    <option value="248">Seychelles +248
                                                                    </option>
                                                                    <option value="232">Sierra Leone +232
                                                                    </option>
                                                                    <option value="65">Singapore +65
                                                                    </option>
                                                                    <option value="421">Slovak Republic +421
                                                                    </option>
                                                                    <option value="386">Slovenia +386
                                                                    </option>
                                                                    <option value="677">Solomon Islands +677
                                                                    </option>
                                                                    <option value="252">Somalia +252
                                                                    </option>
                                                                    <option value="27">South Africa +27
                                                                    </option>
                                                                    <option value="34">Spain +34
                                                                    </option>
                                                                    <option value="94">Sri Lanka +94
                                                                    </option>
                                                                    <option value="290">St. Helena +290
                                                                    </option>
                                                                    <option value="1869">St. Kitts +1869
                                                                    </option>
                                                                    <option value="1758">St. Lucia +1758
                                                                    </option>
                                                                    <option value="249">Sudan +249
                                                                    </option>
                                                                    <option value="597">Suriname +597
                                                                    </option>
                                                                    <option value="268">Swaziland +268
                                                                    </option>
                                                                    <option value="46">Sweden +46
                                                                    </option>
                                                                    <option value="41">Switzerland +41
                                                                    </option>
                                                                    <option value="963">Syria +963
                                                                    </option>
                                                                    <option value="886">Taiwan +886
                                                                    </option>
                                                                    <option value="7">Tajikstan +7
                                                                    </option>
                                                                    <option value="66">Thailand +66
                                                                    </option>
                                                                    <option value="228">Togo +228
                                                                    </option>
                                                                    <option value="676">Tonga +676
                                                                    </option>
                                                                    <option value="1868">Trinidad &amp; Tobago +1868
                                                                    </option>
                                                                    <option value="216">Tunisia +216
                                                                    </option>
                                                                    <option value="90">Turkey +90
                                                                    </option>
                                                                    <option value="7">Turkmenistan +7
                                                                    </option>
                                                                    <option value="993">Turkmenistan +993
                                                                    </option>
                                                                    <option value="1649">Turks &amp; Caicos Islands +1649
                                                                    </option>
                                                                    <option value="688">Tuvalu +688
                                                                    </option>
                                                                    <option value="256">Uganda +256
                                                                    </option>
                                                                    <option value="44">UK +44
                                                                    </option>
                                                                    <option value="380">Ukraine +380
                                                                    </option>
                                                                    <option value="971">United Arab Emirates +971
                                                                    </option>
                                                                    <option value="598">Uruguay +598
                                                                    </option>
                                                                    <option value="1">USA +1
                                                                    </option>
                                                                    <option value="7">Uzbekistan +7
                                                                    </option>
                                                                    <option value="678">Vanuatu +678
                                                                    </option>
                                                                    <option value="379">Vatican City +379
                                                                    </option>
                                                                    <option value="58">Venezuela +58
                                                                    </option>
                                                                    <option value="84">Vietnam +84
                                                                    </option>
                                                                    <option value="84">Virgin Islands - British +1284
                                                                    </option>
                                                                    <option value="84">Virgin Islands - US +1340
                                                                    </option>
                                                                    <option value="681">Wallis &amp; Futuna +681
                                                                    </option>
                                                                    <option value="969">Yemen North +969
                                                                    </option>
                                                                    <option value="967">Yemen South +967
                                                                    </option>
                                                                    <option value="381">Yugoslavia +381
                                                                    </option>
                                                                    <option value="243">Zaire +243
                                                                    </option>
                                                                    <option value="260">Zambia +260
                                                                    </option>
                                                                    <option value="263">Zimbabwe +263
                                                                    </option>
                                                                </select>
                                                                </select>
                                                                <div class="form-control-feedback">
                                                                    <i class="icon-earth text-muted"></i>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group has-feedback has-feedback-left">
                                                                @if(isset($userid))
                                                                <?php
                                                                $userMobile=App\Users::where('u_id',$userid)->value('u_phone');
                                                                if(isset($userMobile))
                                                                $toWithoutCountryCode=substr($userMobile, 1);
                                                                ?>
                                                                <input name="phone" type="text" @if(isset($toWithoutCountryCode)) value="{{$toWithoutCountryCode}}" @endif class="form-control"
                                                                placeholder="Enter your mobile number" required>

                                                                <input type="hidden" name="userid" value="{{ $userid }}">
                                                                <input type="hidden" name="campaignid" value="{{ $campaign->id }}">
                                                                @endif
                                                                 
                                                                <div class="form-control-feedback">
                                                                    <i class="icon-mobile text-muted"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($campaign->offer_sendmail == 1)
                                                    <center><h6 class="text-semibold">Please enter your email to recive your offer code</h6>
                                                    </center>
                                                    <div class="row">
                                                        <div class="col-md-3">

                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group has-feedback has-feedback-left">
                                                                @if(isset($userid))
                                                                <input name="email" type="email" @if($mail=App\Users::where('u_id',$userid)->value('u_email')) value="{{$mail}}" @endif class="form-control"
                                                                       placeholder="Your email" required>
                                                                <input type="hidden" name="userid" value="{{ $userid}}">
                                                                <input type="hidden" name="campaignid" value="{{ $campaign->id}}">
                                                                @endif
                                                                
                                                                <div class="form-control-feedback">
                                                                    <i class="icon-mail5 text-muted"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="modal-footer">
                                                    <button type="button" id="loadingButton" class="btn btn-success" onclick="loadingIcon();">Confirm</button>
                                                </div>
                                                <script>
                                                    function loadingIcon() {
                                                        $('#loadingButton').attr("disabled", "disabled");
                                                        $('#get-offer').submit();
                                                    }
                                                </script>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /success modal -->
                        <div class="text-center">
                            <!--<div class="icon-object border-slate-300 text-slate-300"><i class="icon-quill4"></i></div>-->
                            <h2 class="content-group" style="color: #ffffff">{{ $campaign->offer_title }}
                                <small class="display-block" style="color: #ffffff">{{ $campaign->offer_desc }}</small>
                            </h2>
                        </div>
                        <center>
                            @if(isset($userid))
                                <div class="form-group">
                                    <button class="btn btn-primary btn-labeled btn-xlg" data-toggle="modal" data-target="#offer"><b><i
                                                    class="icon-link"></i></b>
                                        Get my offer code NOW!
                                    </button>
                                </div>
                                <div class="form-group">
                                    <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                    <a href="{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/account') }}" class="btn btn-default btn-ladda btn-ladda-spinner connect"
                                       data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                class="ladda-label">Connect</span></a>
                                </div>
                            @else
                                <div class="form-group">
                                    <button class="btn btn-primary btn-labeled btn-xlg" data-toggle="modal" data-target="#offer"><b><i
                                                    class="icon-link"></i></b>
                                        Get my offer code NOW!
                                    </button>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                        <a href="#" class="btn btn-default btn-ladda btn-ladda-spinner connect" data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                    class="ladda-label">Connect</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </center>
                    @elseif($campaign->type == "video") <!-- Connect and visit -->
                            
                            <center>

                                <div style="z-index: 1; width: 100%; height: 100%">
                                    <!-- <iframe frameborder="0" height="100%" width="100%" src="https://www.youtube.com/embed/{{$campaign->video_url}}?autoplay=1&controls=0&showinfo=0&autohide=1&loop=10&rel=0"> -->
                                    <iframe frameborder="0" height="100%" width="100%" allow='autoplay' src="https://www.youtube.com/embed/{{$campaign->video_url}}?autoplay=1&controls=0&showinfo=0&autohide=1&loop=10&rel=0">

                                    </iframe>

                                    @if(isset($userid))
                                        <div class="form-group">
                                            <h2 style="color: #ffffff; -webkit-text-stroke: 0px blue;">{{ $campaign->description }}</h2>
                                            <a class="btn btn-primary btn-labeled btn-xlg" href="{{ url('campaign_click/'.$campaign->id.'/'.$userid) }}"><b><i
                                                            class="icon-link"></i></b>
                                                Connect and visit
                                            </a>
                                        </div>
                                        <div class="form-group">
                                            <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                            <a href="{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/account') }}" class="btn btn-default btn-ladda btn-ladda-spinner connect"
                                               data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                        class="ladda-label">Connect</span></a>
                                        </div>
                                    @else 
                                        <div class="form-group">
                                            <h2 style="color: #ffffff; -webkit-text-stroke: 0px blue;">{{ $campaign->description }}</h2>
                                            <button class="btn btn-primary btn-labeled btn-xlg" data-toggle="modal" data-target="#offer"><b><i
                                                            class="icon-link"></i></b>
                                                Connect and visit
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <h5 class="content-group connect_delay_text" id="timer" style="color: #ffffff">Free Internet <span>{{ $campaign->delay }}</span> Seconds</h5>
                                                <a href="#" class="btn btn-default btn-ladda btn-ladda-spinner connect" data-spinner-color="#333" data-style="slide-up" style="display: none;"><span
                                                            class="ladda-label">Connect</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </center>
                    @endif
                </div>
                <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>

<!-- /page container -->
<script type="text/javascript" charset="utf-8">

   @if(isset($share))
    $(window).load(function(){
        $('#offer').modal('show');
    });
   @endif

    $(document).ready(function () {


        

        @if(isset($campaign->open_profile) and $campaign->open_profile==1)

            $('.connect').delay({{ $campaign->delay * 1000 }}).show(0);
            $('.connect_delay_text').delay({{ $campaign->delay  * 1000}}).hide(0);

            var sec = $('#timer span').text() || 0;
            var timer = setInterval(function() {
                $('#timer span').text(--sec);
                if (sec == 0) {
                    $('#timer').fadeOut('fast');
                    clearInterval(timer);
                }
            }, 1000);
        @else

        $('.connect_delay_text').hide(0);
        
        @endif


        // Button with progress
        Ladda.bind('.btn-ladda-spinner', {
            dataSpinnerSize: 16,
            timeout: 2000,
            callback: function (instance) {
                @if(isset($userid))
                window.location.href = "{{ url('campaign_click/'.$campaign->id.'/'.$userid.'/account') }}";
                @endif
            }
        });
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

        var vid = document.getElementById("bgvid");
        var pauseButton = document.querySelector("#polina button");

        function vidFade() {
            vid.classList.add("stopfade");
        }

        vid.addEventListener('ended', function () {
            // only functional if "loop" is removed
            vid.pause();
            // to capture IE10
            vidFade();
        });
        pauseButton.addEventListener("click", function () {
            vid.classList.toggle("stopfade");
            if (vid.paused) {
                vid.play();
                pauseButton.innerHTML = '<b><i class="icon-pause2"></i></b>Pause';
            } else {
                vid.pause();
                pauseButton.innerHTML = '<b><i class="icon-play4"></i></b>Start';
            }
        })


    });

    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });
    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });
    // Show form
    var form = $(".steps-validation").show();

    // Select2 selects
    $('.select').select2();
</script>
</body>
</html>
