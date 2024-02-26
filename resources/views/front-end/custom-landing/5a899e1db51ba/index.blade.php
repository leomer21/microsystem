<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Maspero WiFi</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5a899e1db51ba') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a899e1db51ba') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a899e1db51ba') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a899e1db51ba') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a899e1db51ba') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a899e1db51ba') }}/css/index.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a899e1db51ba') }}/css/preloader.css" />
	</head>
    <body class="light-page">
	<div id="preloader"><div class="circles"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
<nav id="nav-fluid-logo-menu-btn-2" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/5a899e1db51ba') }}/images/Microsystem_50_50_new.png" srcset="" alt="Your logo"></div>
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
                        
                        
                        
                    </ul>
                    <span class="btn-group"><a class="btn btn-primary btn-lg smooth" target="_blank" href="http://wifi-solutions.microsystem.com.eg/"><i class="icon-link icon-position-left"></i><span>Powered by Microsystem WiFi</span></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bg bg-2-color-dark"></div>
</nav>		<div id="wrap">
			<header id="header-form-slogan" class="pt-125 pb-150 dark bg-2-color-dark">
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
                			<h1><strong>Login to</strong><br>Wi-Fi</h1>
                			<p>Convert WiFi cost into revenue through your public WiFi</p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <footer id="footer-center-social-logo" class="bg-2-color-dark dark pt-25 pb-25">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-12 text-center">
                			<ul class="social-list">
                    
                    			<li>
                        			<a href="https://www.facebook.com/microsystemegypt/" target="_blank" class="smooth"><i class="icon-facebook icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="https://www.linkedin.com/company/microsystemegypt/" target="_blank" class="smooth"><i class="icon-linkedin icon-size-m"></i></a>
                    			</li>
                    
                    
                    
                    			<li>
                        			<a href="http://wifi-solutions.microsystem.com.eg" target="_blank" class="smooth"><i class="icon-dribbble icon-size-m"></i></a>
                    			</li>
                    
                    
                    			<li>
                        			<a href="https://www.youtube.com/microsystemegypt" target="_blank" class="smooth"><i class="icon-youtube icon-size-m"></i></a>
                    			</li>
                			</ul>
                			<span>© <a href="http://wifi-solutions.microsystem.com.eg" target="_blank" class="smooth">Microsystem</a>&nbsp;<strong>01145929570</strong></span>
                			<div class="mt-50">
                    			<img src="{{ asset('/custom-landing/5a899e1db51ba') }}/images/Microsystem320_132small.png" srcset="" alt="Your logo">
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</footer>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5a899e1db51ba') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5a899e1db51ba') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5a899e1db51ba') }}/js/jquery.smooth-scroll.min.js"></script>
		<script src="{{ asset('/custom-landing/5a899e1db51ba') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5a899e1db51ba') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5a899e1db51ba') }}/js/index.js"></script>
	</body>
</html>