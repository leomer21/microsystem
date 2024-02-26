<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f3eb90fc50e9') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f3eb90fc50e9') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f3eb90fc50e9') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f3eb90fc50e9') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f3eb90fc50e9') }}/css/index.css" />
	</head>
    <body class="light-page">
		<div id="wrap">
			<header id="header-img-slogan-videobg" class="pt-125 pb-125 dark bg-2-color-light">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-7">
                			<a href="https://design.tutsplus.com/ar/tutorials/how-to-make-a-restaurant-menu-template-in-indesign--cms-32452" target="_self" class="smooth"><img class="screen" src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/images/1-marketing-3a.png" srcset="{{ asset('/custom-landing/5f3eb90fc50e9') }}/images/1-marketing-3a.png 2x" alt="app screen" style="transition: all 0s ease 0s; margin: 0px; display: inline-block; min-width: 15px;"></a>
            			</div>
            			<div class="col-md-5">
                			<img class="mb-50" src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/images/Microsystem_White.png" srcset="" alt="Your logo">
                			<h1 spellcheck="true"><b>شوف المنيو واستمتع بالاختيارك&nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; شكرا على ثقتك بينا&nbsp; &nbsp; &nbsp;</b></h1>
                
                
                			<a class="btn btn-success btn-lg" target="_blank" href="https://design.tutsplus.com/ar/tutorials/how-to-make-a-restaurant-menu-template-in-indesign--cms-32452"><span><strong><span class="text-uppercase">شوف المنيو&nbsp;</span></strong></span><i class="icon-plus-circle icon-position-right icon-size-m"></i></a>
            			</div>
        			</div>
    			</div>
    			<div class="bg bg-video parallax-bg" data-vide-bg="mp4: video/video_bg, ogv: video/video_bg, jpg: video/video_bg" data-vide-options="posterType: jpg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <header id="header-form-slogan" class="pt-125 pb-150 bg-2-color-dark dark">
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
    			<div class="bg parallax-bg skrollable-before" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') 
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/jquery.vide.min.js"></script>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/jquery.smooth-scroll.min.js"></script>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5f3eb90fc50e9') }}/js/index.js"></script>
	</body>
</html>