<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5a6799dab2ad8') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a6799dab2ad8') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a6799dab2ad8') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a6799dab2ad8') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a6799dab2ad8') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a6799dab2ad8') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-menu-social" class="navbar navbar-fixed-top light">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="hidden-lg">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <div class="col-md-6 text-md-left">
                    <ul class="nav">
                        <li><span><a href="#">Works</a></span></li>
                        <li><span><a href="#">Services</a></span></li>
                        <li><span><a href="#">How it works</a></span></li>
                        <li><span><a href="#">Team</a></span></li>
                    </ul>
                </div>
                <div class="col-md-6 text-md-right">
                    <ul class="social-list">
                        <li>
                            <a href="" target="_blank"><i class="icon-twitter icon-size-m"></i></a>
                        </li>
                        <li>
                            <a href="" target="_blank"><i class="icon-facebook icon-size-m"></i></a>
                        </li>
                        <li>
                            <a href="" target="_blank"><i class="icon-dribbble icon-size-m"></i></a>
                        </li>
                        <li>
                            <a href="" target="_blank"><i class="icon-behance icon-size-m"></i></a>
                        </li>
                        <li>
                            <a href="" target="_blank"><i class="icon-envelope-o icon-size-m"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bg bg-1-color-light"></div>
</nav>		<div id="wrap">
			<header id="header-center-logo-slogan" class="pt-150 pb-150 light bg-1-color-light">
    			<div class="container">
        			<div class="row no-pad text-center">
            			<div class="col-md-12">
                			<img src="{{ asset('/custom-landing/5a6799dab2ad8') }}/images/systemIcon.png" srcset="{{ asset('/custom-landing/5a6799dab2ad8') }}/images/SystemIconRetina.png 2x" class="mb-100" alt="GeekZone">
                			<h1 class="mb-50"><strong><em><mark>Welcome To Geek Zone</mark></em></strong></h1><h1 class="mb-50"><span class="text-uppercase">Meet ,Share, <strong>INNOVATE</strong>&nbsp;</span></h1><h1 class="mb-50"><span class="text-uppercase"><strong>what you will build today?!</strong></span></h1>

                

                			<a class="btn btn-link goto" href="http://geek-zone.microsystem.com.eg/builder/index.php#"><i class="icon-arrow-down icon-size-m icon-position-left"></i><span><strong>Sign Up Now!</strong></span></a>
            			</div>
        			</div>
    			</div>
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
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
                                			<a href="{{ url('auth/linkedin') }}" data-type="li" id="linkedin"><i class="icon-linkedin"></i><span>Sign in</span></a>
                            			</li>
                        			</ul></center>
                    			</div>
                			</div>
            			</div>
            			<div class="col-md-6 col-md-offset-1">
                			<h1><strong>Sign up</strong><br>To get your internet</h1>
                			<p><strong><span class="text-uppercase">Signup and Connect Now!</span></strong></p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') 
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5a6799dab2ad8') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5a6799dab2ad8') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5a6799dab2ad8') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5a6799dab2ad8') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5a6799dab2ad8') }}/js/index.js"></script>
	</body>
</html>