<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/58e4fac72a9d1') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58e4fac72a9d1') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58e4fac72a9d1') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58e4fac72a9d1') }}/css/owl.carousel.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58e4fac72a9d1') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58e4fac72a9d1') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58e4fac72a9d1') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-logo-menu" class="navbar navbar-fixed-top dark">
    <div class="container">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/58e4fac72a9d1') }}/images/logo_square_blue_small.png" srcset="" alt="Your logo"></div>
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
                        <li><i class="icon-briefcase icon-position-left"></i><span><a href="#gallery-carousel-single" target="_self" class="smooth">H</a>ome</span></li><li><i class="icon-briefcase icon-position-left"></i><span><a href="test.html#action-text-btn-img" target="_blank" class="smooth">test</a></span></li>
                        <li><i class="icon-camera2 icon-position-left"></i><span><a href="https://www.youtube.com/watch?v=-4p6cfIk_9M" target="_self" class="single-iframe-popup">V</a>ideo</span></li>
                        <li><i class="icon-compass2 icon-position-left"></i><span><a href="test.html#nav-fluid-canvas" target="_self" class="smooth">more<br></a></span></li>
                        <li><i class="icon-bubble-question icon-position-left"></i><span>Contact US<br></span></li><li><i class="icon-bubble-question icon-position-left"></i><span><a href="http://hotspot.microsystem.com.eg" target="_blank" class="smooth">Get Started<br></a></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bg bg-1-color-dark"></div>
</nav>		<div id="wrap">
			<header id="header-form-slogan" class="pt-125 pb-150  dark bg-2-color-dark">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-5">
                			<div class="form-container bg-1-color-light light"> @include('...front-end.landing.custom_auth') 
                    			<small class="desc-text">Sign in by the following options.</small>
                    			<br>
                    			<div class="">
                        			<center><ul class="share-list">
                            			<li>
                                			<a data-type="fb" id="facebook" href="{{ url('auth/facebook') }}"><i class="icon-facebook"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a data-type="tw" id="twitter" href="{{ url('auth/twitter') }}"><i class="icon-twitter"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a data-type="gp" id="google" href="{{ url('auth/google') }}"><i class="icon-google-plus"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a data-type="li" id="linkedin" href="{{ url('auth/linkedin') }}"><i class="icon-linkedin"></i><span>Sign in</span></a>
                            			</li>
                        			</ul></center>
                    			</div>
                			</div>
            			</div>
            			<div class="col-md-6 col-md-offset-1">
                			<h1><strong>Sign up</strong><br>in Microsytem<br>NOW</h1>
                			<p>Emotions that causes your project in visitor are no less important ticket to success. Modern solutions, interesting elements, unique approach to details make this template recognizable and interesting.
                			</p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="gallery-carousel-single" class="pt-100 pb-100  dark text-center bg-1-color-dark">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-50">How it works</h2>
        			</div>
    			</div>
    			<div class="single-carousel gallery"><div class="item container">
            			<img src="{{ asset('/custom-landing/58e4fac72a9d1') }}/images/bg-11.jpg" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">I always thought that people used to pay much for quality. But these guys changed my opinion. The quality exceeds the price many times. I recommend it to everybody.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/58e4fac72a9d1') }}/images/browser-window-1.png" srcset="{{ asset('/custom-landing/58e4fac72a9d1') }}/images/browser-window-1@2x.png 2x" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">I always thought that people used to pay much for quality. But these guys changed my opinion. The quality exceeds the price many times. I recommend it to everybody.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/58e4fac72a9d1') }}/images/iti_pic2.JPG" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">The page is adapted to the most of the popular platform in segment. All you need to do is to choose your variant and start working.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/58e4fac72a9d1') }}/images/bg-12.jpg" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">Your project looks great on any device. Content can be easily read and a user understands freely what you wanted to say him or her.
            			</p>
        			</div></div>
    			<div class="bg"></div>
			</section>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/owl.carousel.min.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/jquery.smooth-scroll.min.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/58e4fac72a9d1') }}/js/index.js"></script>
	</body>
</html>