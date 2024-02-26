<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/590925c57d186') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590925c57d186') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590925c57d186') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590925c57d186') }}/css/owl.carousel.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590925c57d186') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590925c57d186') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590925c57d186') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-logo-menu-btn-2" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/590925c57d186') }}/images/logo_white2.png" srcset="" alt="Your logo"></div>
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
                        <li><span>Home</span></li>
                        <li><span><a href="http://hotspot.microsystem.com.eg/wifi-marketing/" target="_blank" class="smooth">&nbsp;Marketing Module</a><br></span></li>
                        <li><span><a href="http://hotspot.microsystem.com.eg/internet_management" target="_blank" class="smooth">Internet Management Module</a></span></li>
                    </ul>
                    <span class="btn-group"><a class="btn btn-primary btn-lg smooth" href="http://hotspot.microsystem.com.eg/contact-us" target="_blank"><i class="icon-plus icon-position-left"></i><span>Buy now</span></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bg bg-2-color-dark"></div>
</nav>		<div id="wrap">
			<header id="header-form-slogan" class="pt-125 pb-150  dark bg-2-color-dark">
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
			</header> @include('...front-end.landing.custom_auth_js') <section id="gallery-carousel-single" class="pt-100 pb-100  dark text-center bg-2-color-dark">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-50">How it works</h2>
        			</div>
    			</div>
    			<div class="single-carousel gallery"><div class="item container">
            			<img src="{{ asset('/custom-landing/590925c57d186') }}/images/Microsystem_dashboards.png" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">I always thought that people used to pay much for quality. But these guys changed my opinion. The quality exceeds the price many times. I recommend it to everybody.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/590925c57d186') }}/images/Mockup3_800.png" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">The page is adapted to the most of the popular platform in segment. All you need to do is to choose your variant and start working.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/590925c57d186') }}/images/Mockup5_800.png" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">Your project looks great on any device. Content can be easily read and a user understands freely what you wanted to say him or her.
            			</p>
        			</div></div>
    			<div class="bg"></div>
			</section><section id="clients-5col" class="pt-50 pb-50 bg-2-color-light light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/590925c57d186') }}/images/client-1.png" srcset="{{ asset('/custom-landing/590925c57d186') }}/images/client-1@2x.png 2x" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/590925c57d186') }}/images/client-2.png" srcset="{{ asset('/custom-landing/590925c57d186') }}/images/client-2@2x.png 2x" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/590925c57d186') }}/images/client-3.png" srcset="{{ asset('/custom-landing/590925c57d186') }}/images/client-3@2x.png 2x" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/590925c57d186') }}/images/client-4.png" srcset="{{ asset('/custom-landing/590925c57d186') }}/images/client-4@2x.png 2x" alt="Client">
                    			</a>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/590925c57d186') }}/images/client-5.png" srcset="{{ asset('/custom-landing/590925c57d186') }}/images/client-5@2x.png 2x" alt="Client">
                    			</a>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="desc-text-btn-halfbg" class="bg-2-color-light pt-200 pb-md-200 light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-4 text-md-left">
                			<h2>New level</h2>
                			<p class="mb-50">Do you want to create a new project or to update the previous one? So, this template will match you ideally. Innovative solutions and simple mathematically calculated design make it actual for a long time.</p>
                			<a href="#" class="btn btn-default"><span>Read more</span><i class="icon-arrow-right icon-position-right"></i></a>
            			</div>
        			</div>
    			</div>
    			<div class="half-container-right"></div>
    			<div class="bg"></div>
			</section><section id="desc-halfbg-text-btn-2" class="pt-150 pb-md-150 bg-2-color-light light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-4 col-md-offset-8">
                			<h2 class="mb-50">Multiplatform</h2>
                			<p class="mb-50"> So, what is the secret of successful template design? First of all, it is its friendliness – both for the template’s owner and for his or her future targeted audience.</p>
                			<a href="#" class="btn btn-primary"><span>Try now</span><i class="icon-window icon-size-m icon-position-right"></i></a>
            			</div>
        			</div>
    			</div>
    			<div class="half-container-left mt-150"></div>
    			<div class="bg"></div>
			</section><section id="action-text-phone" class="pt-100 pb-100 bg-2-color-light light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-4">
                			<h2>We are waiting for your call</h2>
            			</div>
            			<div class="col-md-8 text-md-right">
                			<h1>+2(010)61030454</h1>
            			</div>
        			</div>
    			</div>
    			<div class="bg parallax-bg skrollable-before" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</section><footer id="footer-center-share-logo" class="bg-1-color-dark dark pt-100 pb-100">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-12 text-center">
                			<ul class="share-list mb-25">
                    			<li>
                        			<a class="goodshare" data-type="fb" href="http://test.microsystem.com.eg/builder/index.php?type=landing#"><i class="icon-facebook"></i><span>Share</span><span data-counter="fb">0</span></a>
                    			</li><li>
                        			<a class="goodshare" data-type="tw" href="http://test.microsystem.com.eg/builder/index.php?type=landing#"><i class="icon-twitter"></i><span>Tweet</span><span data-counter="tw"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="gp"><i class="icon-google-plus"></i><span>Share</span><span data-counter="gp">0</span></a>
                    			</li><li>
                        			<a class="goodshare" data-type="li" href="http://test.microsystem.com.eg/builder/index.php?type=landing#"><i class="icon-linkedin"></i><span>Share</span><span data-counter="li">0</span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="pt"><i class="icon-pinterest-p"></i><span>Share</span><span data-counter="pt">0</span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="vk"><i class="icon-vk"></i><span>Share</span><span data-counter="vk">0</span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="ok"><i class="icon-odnoklassniki"></i><span>Share</span><span data-counter="ok">0</span></a>
                    			</li>
                			</ul>
                			<span>© Microsystem. All rights reserved.</span>
                			<div class="mt-50">
                    			<img src="{{ asset('/custom-landing/590925c57d186') }}/images/logo_square_blue_small_100.png" srcset="" alt="Your logo">
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</footer>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/owl.carousel.min.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/jquery.smooth-scroll.min.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/590925c57d186') }}/js/index.js"></script>
	</body>
</html>