<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/59676be1c732b') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/59676be1c732b') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/59676be1c732b') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/59676be1c732b') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/59676be1c732b') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/59676be1c732b') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-logo-menu-btn" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/59676be1c732b') }}/images/logo.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/logo@2x.png 2x" alt="Your logo"></div>
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
                        <li><span><a href="#">About</a></span></li>
                        <li><span><a href="#">Screenshots</a></span></li>
                        <li><span><a href="#">Stories</a></span></li>
                    </ul>
                    <a href="" class="btn-default btn btn-sm"><i class="icon-plus icon-position-left"></i><span>Download</span></a>
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
                			<h1><b>Welcome To Etisalat</b></h1>
                			<p>Emotions that causes your project in visitor are no less important ticket to success. Modern solutions, interesting elements, unique approach to details make this template recognizable and interesting.
                			</p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="clients-5col-2row" class="pt-125 pb-150 bg-1-color-dark dark">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-75">Partners</h2>
        			</div>
        			<div class="row">
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-1-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-1-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>General partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-6-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-6-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Limited partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-2-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-2-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Media partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-7-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-7-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>General partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-3-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-3-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Limited partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-8-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-8-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Global partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-4-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-4-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Honorary partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-9-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-9-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Media partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-5-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-5-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Global partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/59676be1c732b') }}/images/client-10-dark.png" srcset="{{ asset('/custom-landing/59676be1c732b') }}/images/client-10-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>General partner</p>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="desc-halfbg-text" class="bg-2-color-light pt-200 pb-md-200 light">
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
			</section>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/59676be1c732b') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/59676be1c732b') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/59676be1c732b') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/59676be1c732b') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/59676be1c732b') }}/js/index.js"></script>
	</body>
</html>