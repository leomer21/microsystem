<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5cbefaad47f46') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5cbefaad47f46') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5cbefaad47f46') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5cbefaad47f46') }}/css/owl.carousel.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5cbefaad47f46') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5cbefaad47f46') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5cbefaad47f46') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-menu-text" class="navbar navbar-fixed-top light">
        <div class="container">
            <div class="row no-pad">
                <div class="hidden-lg">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="col-md-7 text-md-left">
                        <ul class="nav">
                            <li><span><a href="#">Works</a></span></li>
                            <li><span><a href="#">Services</a></span></li>
                            <li><span><a href="#">How it works</a></span></li>
                            <li><span><a href="#">Team</a></span></li>
                        </ul>
                    </div>
                    <div class="col-md-5 text-md-right">
                        <div class="nav-text"><i class="icon-phone-wave icon-position-left"></i><span><strong>+1(890)1321312</strong></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-bg bg-1-color-light"></div>
    </nav>		<div id="wrap">
			<header id="header-halfbg-slogan" class="bg-1-color-light pt-150 pb-md-150 light">
    			<div class="container">
        			<div class="row no-pad">
            			<div class="col-md-6 col-md-offset-6">
                			<img src="{{ asset('/custom-landing/5cbefaad47f46') }}/images/logo-big.png" srcset="{{ asset('/custom-landing/5cbefaad47f46') }}/images/logo-big@2x.png 2x" class="mb-50" alt="Gumapp">
                			<h1 class="mb-50"><b>kriss offers</b></h1>

                			<p class="mb-50">So, what is the secret of successful template design? First of all, it is its friendliness – both for the template’s owner and for his future targeted audience. UX and UI are not just empty phrases for us.</p>

                			<div class="row">
                    			<div class="col-md-4">
                        			<div class="content-box text-md-left">
                            			<i class="content-icon icon-color icon-first-aid icon-size-l"></i>
                            			<h4>Free consultation</h4>
                        			</div>
                    			</div>
                    			<div class="col-md-4">
                        			<div class="content-box text-md-left">
                            			<i class="content-icon icon-color icon-receipt icon-size-l"></i>
                            			<h4>Family receipt</h4>
                        			</div>
                    			</div>
                    			<div class="col-md-4">
                        			<div class="content-box text-md-left">
                            			<i class="content-icon icon-color icon-microscope icon-size-l"></i>
                            			<h4>Own laboratory</h4>
                        			</div>
                    			</div>
                			</div>

            			</div>
        			</div>
    			</div>
    			<div class="half-container-left mt-75"></div>
    			<div class="bg"></div>
			</header> @include('...front-end.landing.custom_auth_js') <header id="header-form-slogan" class="pt-125 pb-150 dark bg-2-color-dark">
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
                                			<a data-type="li" id="linkedin" href="http://kriss.mymicrosystem.com/builder/%7B%7B%20url('auth/linkedin')%20%7D%7D"><i class="icon-linkedin"></i><span>Sign in</span></a>
                            			</li>
                        			</ul></center>
                    			</div>
                			</div>
            			</div>
            			<div class="col-md-6 col-md-offset-1">
                			<h1><strong>Sign up</strong><br></h1>
                			<p>Emotions that causes your project in visitor are no less important ticket to success. Modern solutions, interesting elements, unique approach to details make this template recognizable and interesting.
                			</p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="gallery-carousel-single" class="pt-100 pb-100 bg-1-color-dark dark text-center">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-50">How it works</h2>
        			</div>
    			</div>
    			<div class="single-carousel gallery"><div class="item container">
            			<img src="{{ asset('/custom-landing/5cbefaad47f46') }}/images/browser-window-1.png" srcset="{{ asset('/custom-landing/5cbefaad47f46') }}/images/browser-window-1@2x.png 2x" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">I always thought that people used to pay much for quality. But these guys changed my opinion. The quality exceeds the price many times. I recommend it to everybody.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/5cbefaad47f46') }}/images/browser-window-2.png" srcset="{{ asset('/custom-landing/5cbefaad47f46') }}/images/browser-window-2@2x.png 2x" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">The page is adapted to the most of the popular platform in segment. All you need to do is to choose your variant and start working.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/5cbefaad47f46') }}/images/0512EgyptCairo_al_halili1168.JPG" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">Your project looks great on any device. Content can be easily read and a user understands freely what you wanted to say him or her.
            			</p>
        			</div></div>
    			<div class="bg"></div>
			</section>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/owl.carousel.min.js"></script>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5cbefaad47f46') }}/js/index.js"></script>
	</body>
</html>