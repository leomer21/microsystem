<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/58b755bc3f868') }}/js/fonts.js"></script>
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
		<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
		<meta id="csrf" content="{{ csrf_token() }}" />
		<!-- /theme JS files -->
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/58b755bc3f868') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58b755bc3f868') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58b755bc3f868') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58b755bc3f868') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/58b755bc3f868') }}/css/index.css" />
	</head>
    <body class="light-page">
		<div id="wrap">
			<header id="header-form-slogan" class="pt-125 pb-150 bg-2-color-dark dark">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-5">
                			<div class="form-container bg-1-color-light light"> @include('...front-end.landing.custom_auth') 
                        			<!--<ul class="nav nav-tabs">
                            			<li class="active"><a href="#basic-tab1" data-toggle="tab">Sign in</a></li>
                            			<li><a href="#basic-tab2" data-toggle="tab">Sign up</a></li>
                        			</ul>
                        			<div class="tab-content">
                            			<div class="tab-pane active" id="basic-tab1">
                                			<form action="" class="signin_form">
                                    			<div class="form-group">
                                        			<input type="text" class="form-control signin_name" placeholder="Username" name="name">
                                    			</div>
                                    			<div class="form-group">
                                        			<input type="password" class="form-control signin_password" placeholder="Password" name="password">
                                    			</div>
                                    			<button type="submit" data-loading-text="&bull;&bull;&bull;" data-complete-text="Completed!" data-reset-text="Try again later..." class="btn btn-block btn-primary signin_submit"><span>Sign in</span></button>
                                			</form>
                            			</div>

                            			<div class="tab-pane" id="basic-tab2">
                                			<form action="" class="signin_form">
                                    			<div class="form-group">
                                        			<input type="text" class="form-control signin_name" placeholder="Username" name="name">
                                    			</div>
                                    			<div class="form-group">
                                        			<input type="password" class="form-control signin_password" placeholder="Password" name="password">
                                    			</div>
                                    			<button type="submit" data-loading-text="&bull;&bull;&bull;" data-complete-text="Completed!" data-reset-text="Try again later..." class="btn btn-block btn-primary signin_submit"><span>Sign up</span></button>
                                			</form>
                            			</div>
                        			</div>-->
                        
                    			<small class="desc-text">Sign in by the following options.</small>
                    			<br>
                    			<div class="">
                        			<ul class="share-list">
                            			<li>
                                			<a href="{{url('auth/facebook')}}" data-type="fb" id="facebook"><i class="icon-facebook"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a href="{{url('auth/twitter')}}" data-type="tw" id="twitter"><i class="icon-twitter"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a href="{{url('auth/google')}}" data-type="gp" id="google"><i class="icon-google-plus"></i><span>Sign in</span></a>
                            			</li><li>
                                			<a href="{{url('auth/linkedin')}}" data-type="li" id="linkedin"><i class="icon-linkedin"></i><span>Sign in</span></a>
                            			</li>
                        			</ul>
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
			</header> @include('...front-end.landing.custom_auth_js') <section id="desc-halfbg-text" class="bg-2-color-light pt-200 pb-md-200 light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-4 col-md-offset-8 text-md-left">
                			<h2 class="mb-50">New level</h2>
                			<p>Do you want to create a new project or to update the previous one? So, this template will match you ideally. Innovative solutions and simple mathematically calculated design make it actual for a long time.</p>
            			</div>
        			</div>
    			</div>
    			<div class="half-container-left"></div>
    			<div class="bg"></div>
			</section>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/58b755bc3f868') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/58b755bc3f868') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/58b755bc3f868') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/58b755bc3f868') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/58b755bc3f868') }}/js/index.js"></script>
	</body>
</html>