<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Microsystem Smart Wi-Fi</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5de3992836abe') }}/js/fonts.js"></script>
		<!-- Global stylesheets -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		<link href="{{asset('/')}}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
		<link href="{{asset('/')}}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="{{asset('/')}}assets/css/core.css" rel="stylesheet" type="text/css">
		<link href="{{asset('/')}}assets/css/components.css" rel="stylesheet" type="text/css">
		<link href="{{asset('/')}}assets/css/colors.css" rel="stylesheet" type="text/css">
		<!-- /global stylesheets -->
		
		<!-- Core JS files -->
		<script type="text/javascript" src="{{asset('/')}}assets/js/plugins/loaders/pace.min.js"></script>
		<script type="text/javascript" src="{{asset('/')}}assets/js/core/libraries/jquery.min.js"></script>
		<script type="text/javascript" src="{{asset('/')}}assets/js/core/libraries/bootstrap.min.js"></script>
		<script type="text/javascript" src="{{asset('/')}}assets/js/plugins/loaders/blockui.min.js"></script>
		<!-- /core JS files -->
		
		<!-- Theme JS files -->
		<script type="text/javascript" src="{{asset('/')}}assets/js/core/app.js"></script>
		<script type="text/javascript" src="{{asset('/')}}assets/js/plugins/forms/selects/select2.min.js"></script>
		<script type="text/javascript" src="{{asset('/')}}assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
		
		<script type="text/javascript" src="{{asset('/')}}assets/js/plugins/forms/validation/validate.min.js"></script>
		<script type="text/javascript" src="http://sdk.accountkit.com/en_US/sdk.js"></script>
		<meta id="csrf" content="{{ csrf_token() }}" />
		<!-- /theme JS files -->
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5de3992836abe') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5de3992836abe') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5de3992836abe') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5de3992836abe') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5de3992836abe') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-logo-menu" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/5de3992836abe') }}/images/logo.png" srcset="{{ asset('/custom-landing/5de3992836abe') }}/images/logo@2x.png 2x" alt="Your logo"></div>
                    </div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-10 text-md-right">
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav">
                        <li><i class="icon-briefcase icon-position-left"></i><span><a href="#">Services</a></span></li>
                        <li><i class="icon-camera2 icon-position-left"></i><span><a href="#">Gallery</a></span></li>
                        <li><i class="icon-compass2 icon-position-left"></i><span><a href="#">Location</a></span></li>
                        <li><i class="icon-bubble-question icon-position-left"></i><span><a href="#">FAQ</a></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bg bg-1-color-dark"></div>
</nav>		<div id="wrap">
			<header id="header-center-slogan-img-videobg" class="pt-200 bg-1-color-dark dark">
    			<div class="container">
        			<div class="row text-center">
            			<div class="col-md-12">
                			<div class="mb-50">
                    			<h1 spellcheck="false"><span style="font-size: 16px;">Microsystem Hotspot</span></h1>
                    			<p>Are you ready for next generation of marketing?&nbsp; &nbsp;</p>
                			</div>
                

                			<img src="{{ asset('/custom-landing/5de3992836abe') }}/images/Microsystem_dashboards.png" srcset="" class="screen mt-125" alt="">
            			</div>
        			</div>
    			</div>
    			<div class="bg bg-video parallax-bg" data-vide-bg="mp4: video/video_bg, ogv: video/video_bg, jpg: video/video_bg" data-vide-options="posterType: jpg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <header id="header-form-slogan" class="pt-125 pb-150 bg-2-color-dark dark">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-5">
                			<div class="form-container bg-1-color-light light"> @include('...front-end.landing.custom_auth') 
                    			<div id="socialMediaLogin">
                        			<center><small class="desc-text">Sign in by the following options.</small></center>
                        			<center><ul class="share-list">
                            			<li>
                                			<a href="{{ url('auth/facebook') }}" data-type="fb" id="facebook"><i class="icon-facebook"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a href="{{ url('auth/twitter') }}" data-type="tw" id="twitter"><i class="icon-twitter"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a href="{{ url('auth/google') }}" data-type="gp" id="google"><i class="icon-google-plus"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a href="{{ url('auth/linkedin') }}" data-type="li" id="linkedin"><i class="icon-linkedin"></i><span>Sign in</span></a>
                            			</li>
                        			</ul></center>
                    			</div>
                			</div>
            			</div>
            			<div class="col-md-6 col-md-offset-1">
                			<h1><strong>Sign up</strong><br>&amp; Sign in</h1>
                			<p>Emotions that causes your project in visitor are no less important ticket to success. Modern solutions, interesting elements, unique approach to details make this template recognizable and interesting.
                			</p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg skrollable-before" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="benefit-2col-img" class="pt-125 pb-125 bg-1-color-light light">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-7 text-md-left">
                
                
                			<div class="row mt-50">
                    			<div class="col-md-6">
                        			<div class="content-box">
                            			<i class="content-icon icon-position-left icon-size-m icon-map-marker-user icon-color"></i>
                            			<h3></h3><h3>Targeted Campaigns</h3><div><br></div>
                            			<p></p><div class="col span_3 centered-text one-fourths clear-both" data-animation="" data-delay="0" style="vertical-align: baseline; font-size: 12px; outline: 0px; padding: 0px; margin: 0px 22px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px; text-align: center; color: rgb(103, 103, 103); background-color: rgb(248, 248, 248);"><div class="col span_12" data-animation="" data-delay="0" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px 5.15625px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px;"><div class="wpb_text_column wpb_content_element " style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><div class="wpb_wrapper" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><p>filter users by number of returning visits, last visits and more to attract your potential customers.</p></div></div></div></div><p></p>
                        			</div>
                        			<div class="content-box">
                            			<i class="content-icon icon-size-m icon-position-left icon-bullhorn icon-color"></i>
                            			<h3></h3><h3>Advertising</h3><div><br></div>
                            			<p></p><div class="col span_3 centered-text one-fourths clear-both" data-animation="" data-delay="0" style="vertical-align: baseline; font-size: 12px; outline: 0px; padding: 0px; margin: 0px 22px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px; text-align: center; color: rgb(103, 103, 103); background-color: rgb(248, 248, 248);"><div class="col span_12" data-animation="" data-delay="0" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px 5.15625px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px;"><div class="wpb_text_column wpb_content_element " style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><div class="wpb_wrapper" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><p>Use banners and splash pages to promote offers and mobile app downloads.</p></div></div></div></div><p></p>
                        			</div>
                    			</div>
                    			<div class="col-md-6">
                        			<div class="content-box">
                            			<i class="content-icon icon-position-left icon-size-m icon-laptop-phone icon-color"></i>
                            			<h3></h3><h3>Returning visitors</h3><div><br></div>
                            			<p></p><div class="col span_3 centered-text one-fourths clear-both" data-animation="" data-delay="0" style="vertical-align: baseline; font-size: 12px; outline: 0px; padding: 0px; margin: 0px 22px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px; text-align: center; color: rgb(103, 103, 103); background-color: rgb(248, 248, 248);"><div class="col span_12" data-animation="" data-delay="0" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px 5.15625px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px;"><div class="wpb_text_column wpb_content_element " style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><div class="wpb_wrapper" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><div class="col span_4 has-animation animated-in" data-animation="fade-in" data-delay="200" style="vertical-align: baseline; outline: 0px; padding: 0px; margin: 0px 22px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 352px; opacity: 1; text-align: start;"><div class="col span_10 col_last" data-animation="" data-delay="0" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px; position: relative; z-index: 10; float: left; width: 292.156px;"><div class="wpb_text_column wpb_content_element " style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><div class="wpb_wrapper" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><ul style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; margin-left: 30px; border: 0px;"><li style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px; list-style: outside disc;"><div class="col span_3 centered-text one-fourths clear-both" data-animation="" data-delay="0" style="vertical-align: baseline; outline: 0px; padding: 0px; margin: 0px 22px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px; text-align: center;"><div class="col span_12" data-animation="" data-delay="0" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px 5.15625px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 258.5px;"><div class="wpb_text_column wpb_content_element " style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><div class="wpb_wrapper" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><p>Identify weekly and monthly returning visitors for more focus on your marketing research.</p></div></div></div></div></li></ul></div></div></div></div></div></div></div></div><p></p>
                        			</div>
                        			<div class="content-box">
                            			<i class="content-icon icon-size-m icon-position-left icon-wifi icon-color"></i>
                            			<h3></h3><h3>Internet Bandwidth Management</h3><div><br></div><ul style="vertical-align: baseline; font-size: 12px; outline: 0px; margin-left: 30px; border: 0px; color: rgb(103, 103, 103); background-color: rgb(248, 248, 248);"></ul>
                            			<p></p><div class="col span_4 has-animation animated-in" data-animation="fade-in" data-delay="200" style="vertical-align: baseline; font-size: 12px; outline: 0px; padding: 0px; margin: 0px 22px 0px 0px; border: 0px; position: relative; z-index: 10; float: left; width: 352px; opacity: 1; color: rgb(103, 103, 103); background-color: rgb(248, 248, 248);"><div class="col span_10 col_last" data-animation="" data-delay="0" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px; position: relative; z-index: 10; float: left; width: 292.156px;"><div class="wpb_text_column wpb_content_element " style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><div class="wpb_wrapper" style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px;"><ul style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; margin-left: 30px; border: 0px;"><li style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px; list-style: outside disc;">Bandwidth, Speed, time, daily quota management per user.</li><li style="vertical-align: baseline; font-family: inherit; font-weight: inherit; font-style: inherit; outline: 0px; padding: 0px; margin: 0px; border: 0px; list-style: outside disc;">Downgrade speed or disable internet after quota finished.</li></ul></div></div></div></div><p></p>
                        			</div>
                    			</div>
                			</div>
            			</div>
            			<div class="col-md-4 col-md-offset-1 text-md-right">
                			<img src="{{ asset('/custom-landing/5de3992836abe') }}/images/Facbook_lead_generation.png" srcset="" alt="phone" class="screen">
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="clients-5col" class="pt-50 pb-50 bg-2-color-light light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5de3992836abe') }}/images/download_(1).png" srcset="" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5de3992836abe') }}/images/22.jpg" srcset="" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5de3992836abe') }}/images/cairo_(1).png" srcset="" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5de3992836abe') }}/images/prolink_5900072014-04-03-10-00-19.jpg" srcset="" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5de3992836abe') }}/images/image_400x400.jpg" srcset="" alt="Client">
                    			</a>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5de3992836abe') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5de3992836abe') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5de3992836abe') }}/js/jquery.vide.min.js"></script>
		<script src="{{ asset('/custom-landing/5de3992836abe') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5de3992836abe') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5de3992836abe') }}/js/index.js"></script>
	</body>
</html>