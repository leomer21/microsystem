<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5a92f7f21064f') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a92f7f21064f') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a92f7f21064f') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a92f7f21064f') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a92f7f21064f') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a92f7f21064f') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-logo-menu-btn-2" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/5a92f7f21064f') }}/images/logo.png" srcset="{{ asset('/custom-landing/5a92f7f21064f') }}/images/logo@2x.png 2x" alt="Your logo"></div>
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
                        <li><span><a href="#">Benefits</a></span></li>
                        <li><span><a href="#">Testimonials</a></span></li>
                        <li><span><a href="#">Prices</a></span></li>
                    </ul>
                    <span class="btn-group"><a class="btn btn-primary btn-lg"><i class="icon-plus icon-position-left"></i><span>Buy now</span></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bg bg-2-color-dark"></div>
</nav>		<div id="wrap">
			<header id="header-form-slogan" class="pt-125 pb-150 bg-2-color-dark dark">
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
                                			<a data-type="tw" id="twitter" href="http://villa307.microsystem.com.eg/builder/%7B%7B%20url('auth/twitter')%20%7D%7D"><i class="icon-twitter"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a data-type="gp" id="google" href="http://villa307.microsystem.com.eg/builder/%7B%7B%20url('auth/google')%20%7D%7D"><i class="icon-google-plus"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a data-type="li" id="linkedin" href="http://villa307.microsystem.com.eg/builder/%7B%7B%20url('auth/linkedin')%20%7D%7D"><i class="icon-linkedin"></i><span>Sign in</span></a>
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
			</header> @include('...front-end.landing.custom_auth_js') <header id="header-center-slogan-img-videobg" class="pt-200 bg-1-color-dark dark">
    			<div class="container">
        			<div class="row text-center">
            			<div class="col-md-12">
                			<div class="mb-50">
                    			<h1>Design &amp; Flexibility</h1>
                    			<p>The given template is armed with the number of settings, so you can easily adapt it according to you requirements.
                    			</p>
                			</div>
                			<a href="#" class="btn btn-primary"><i class="icon-window icon-size-m icon-position-left"></i><span><strong>Download for FREE</strong></span></a><a href="#" class="btn btn-default goto"><i class="icon-plus icon-size-m icon-position-left"></i> <span><strong>View more</strong></span></a>

                			<img src="{{ asset('/custom-landing/5a92f7f21064f') }}/images/browser-windows.png" srcset="{{ asset('/custom-landing/5a92f7f21064f') }}/images/browser-windows@2x.png 2x" class="screen mt-125" alt="">
            			</div>
        			</div>
    			</div>
    			<div class="bg bg-video parallax-bg" data-vide-bg="mp4: video/video_bg, ogv: video/video_bg, jpg: video/video_bg" data-vide-options="posterType: jpg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <footer id="footer-center-share-logo" class="bg-1-color-dark dark pt-100 pb-100">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-12 text-center">
                			<ul class="share-list mb-25">
                    			<li>
                        			<a href="#" class="goodshare" data-type="fb"><i class="icon-facebook"></i><span>Share</span><span data-counter="fb"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="tw"><i class="icon-twitter"></i><span>Tweet</span><span data-counter="tw"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="gp"><i class="icon-google-plus"></i><span>Share</span><span data-counter="gp"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="li"><i class="icon-linkedin"></i><span>Share</span><span data-counter="li"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="pt"><i class="icon-pinterest-p"></i><span>Share</span><span data-counter="pt"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="vk"><i class="icon-vk"></i><span>Share</span><span data-counter="vk"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="ok"><i class="icon-odnoklassniki"></i><span>Share</span><span data-counter="ok"></span></a>
                    			</li>
                			</ul>
                			<span>© Multifour.com. All rights reserved.</span>
                			<div class="mt-50">
                    			<img src="{{ asset('/custom-landing/5a92f7f21064f') }}/images/logo-mid.png" srcset="{{ asset('/custom-landing/5a92f7f21064f') }}/images/logo-mid@2x.png 2x" alt="Your logo">
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</footer>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5a92f7f21064f') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5a92f7f21064f') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5a92f7f21064f') }}/js/jquery.vide.min.js"></script>
		<script src="{{ asset('/custom-landing/5a92f7f21064f') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5a92f7f21064f') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5a92f7f21064f') }}/js/index.js"></script>
	</body>
</html>