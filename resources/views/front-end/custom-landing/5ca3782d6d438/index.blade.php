<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5ca3782d6d438') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5ca3782d6d438') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5ca3782d6d438') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5ca3782d6d438') }}/css/owl.carousel.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5ca3782d6d438') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5ca3782d6d438') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5ca3782d6d438') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-logo-menu" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/5ca3782d6d438') }}/images/logo.png" srcset="{{ asset('/custom-landing/5ca3782d6d438') }}/images/logo@2x.png 2x" alt="Your logo"></div>
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
			<section id="gallery-carousel" class="pt-125 bg-1-color-light text-center light">
    			<div class="container no-side-pad">
        			<div class="title-group text-center">
            			<h2>Screens</h2>
        			</div>
        			<div class="row">
            			<div class="col-md-12">
                			<div class="fullwidth-carousel gallery"><a href="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-1.jpg" class="item gallery-box">
                        			<i class="icon icon-size-m icon-plus"></i>
                        			<span class="caption">Gallery item with zoom option</span>
                        			<img src="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-2.png" srcset="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-1@2x.png 2x" alt="screenz">
                    			</a><a href="https://vimeo.com/123395658" class="item gallery-box mfp-iframe">
                        			<i class="icon icon-size-m icon-ion-ios-play-outline"></i>
                        			<span class="caption">Video item</span>
                        			<img src="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-2.png" srcset="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-2@2x.png 2x" alt="screen">
                    			</a><a href="https://www.google.com.ua" target="_blank" class="item gallery-box external">
                        			<i class="icon icon-size-m icon-link"></i>
                        			<span class="caption">Item with external link</span>
                        			<img src="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-3.png" srcset="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-3@2x.png 2x" alt="screen">
                    			</a><a href="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-4.jpg" class="item gallery-box">
                        			<i class="icon icon-size-m icon-plus"></i>
                        			<span class="caption">Gallery item with zoom option</span>
                        			<img src="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-4.png" srcset="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-4@2x.png 2x" alt="screen">
                    			</a><a href="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-5.jpg" class="item gallery-box">
                        			<i class="icon icon-size-m icon-plus"></i>
                        			<span class="caption">Gallery item with zoom option</span>
                        			<img src="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-5.png" srcset="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-5@2x.png 2x" alt="screen">
                    			</a><a href="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-6.jpg" class="item gallery-box">
                        			<i class="icon icon-size-m icon-plus"></i>
                        			<span class="caption">Gallery item with zoom option</span>
                        			<img src="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-6.png" srcset="{{ asset('/custom-landing/5ca3782d6d438') }}/images/screen-phone-6@2x.png 2x" alt="screen">
                    			</a></div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><header id="header-form-slogan" class="pt-125 pb-150 bg-2-color-dark dark">
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
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') 
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/owl.carousel.min.js"></script>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5ca3782d6d438') }}/js/index.js"></script>
	</body>
</html>