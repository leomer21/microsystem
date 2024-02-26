<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/605b0f1164833') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/605b0f1164833') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/605b0f1164833') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/605b0f1164833') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/605b0f1164833') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/605b0f1164833') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/605b0f1164833') }}/css/index.css" />
	</head>
    <body class="light-page">
		<div id="wrap">
			<section id="video-center-icon" class="pt-125 pb-150 dark bg-3-color-dark">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-50">Smart Space</h2>
        			</div>
        			<div class="row">
            			<div class="col-md-12 text-center">
                			<a href="https://www.youtube.com/watch?v=zJ85ko9TjPQ" class="single-iframe-popup" target="_self">
                    			<i class="icon-ion-ios-play-outline icon-size-xl icon-color"></i>
                			</a>
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
		<script src="{{ asset('/custom-landing/605b0f1164833') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/605b0f1164833') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/605b0f1164833') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/605b0f1164833') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/605b0f1164833') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/605b0f1164833') }}/js/index.js"></script>
	</body>
</html>