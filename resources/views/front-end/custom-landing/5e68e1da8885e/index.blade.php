<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e68e1da8885e') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e68e1da8885e') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e68e1da8885e') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e68e1da8885e') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e68e1da8885e') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e68e1da8885e') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-logo-menu" class="navbar dark" style="">
    <div class="container">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><a href="http://www.elafgroup.com/" target="_self" class="smooth"><img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/elaf-group-logo.png" srcset="" alt="Your logo" style="transition: all 0s ease 0s; margin: 0px; display: inline-block; min-width: 15px;"></a></div>
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
                        <li><i class="icon-briefcase icon-position-left"></i><span><a href="http://www.elafgroup.com/elaf-hotels/about-us/" target="_blank" class="smooth">ABOUT THE HOTEL</a></span></li>
                        <li><i class="icon-camera2 icon-position-left"></i><span><a href="http://www.elafgroup.com/elaf-hotels/press-room-main/" target="_blank" class="smooth">PRESS ROOM</a></span></li>
                        
                        <li><i class="icon-bubble-question icon-position-left"></i><span><a href="http://www.elafgroup.com/elaf-hotels/contact-us/" target="_blank" class="smooth">CONTACT US</a></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-bg bg-1-color-dark"></div>
</nav>		<div id="wrap">
			<header id="header-slogan-text-btn" class="dark bg-2-color-dark pt-250 pb-250 sep-b">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-6">
                
            			</div>
            			<div class="col-md-6">
                
                
            			</div>

        			</div>
    			</div>
    			<div class="bg parallax-bg skrollable-after" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="gallery-list-3col" class="pt-125 pb-150 bg-1-color-light text-center light">
    			<div class="container">
        			<div class="title-group text-center">
            
        			</div>
        			<div class="row gallery">
            			<div class="col-md-4">
                			<a href="http://www.elafgroup.com/elaf-hotels-jeddah/hotels/elaf-jeddah/" class="gallery-box smooth" target="_self">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/ABOUT_THE_HOTEL.jpg" alt="screen" srcset="">
                			</a>
                			<a href="http://www.elafgroup.com/elaf-hotels-jeddah/hotels/elaf-jeddah/" class="gallery-box mfp-iframe smooth" target="_blank">
                    			<i class="icon icon-size-m icon-ion-ios-play-outline"></i>
                    			<span class="caption">Video item</span>
                    			<img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/HEALTH-LEISURE.png" alt="screen" srcset="">
                			</a>
            			</div>
            			<div class="col-md-4">
                			<a href="http://www.elafgroup.com/elaf-hotels-jeddah/hotels/elaf-jeddah/" target="_blank" class="gallery-box external smooth">
                    			<i class="icon icon-size-m icon-link"></i>
                    			<span class="caption">Item with external link</span>
                    			<img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/DINING.jpg" alt="screen" srcset="">
                			</a>
                			<a href="http://www.elafgroup.com/elaf-hotels-jeddah/hotels/elaf-jeddah/" class="gallery-box smooth" target="_blank">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/SERVICES_-_FACILITIES.jpg" alt="screen" srcset="">
                			</a>
            			</div>
            			<div class="col-md-4">
                			<a href="http://www.elafgroup.com/elaf-hotels-jeddah/hotels/elaf-jeddah/" class="gallery-box smooth" target="_self">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/MEETING-EVENTS.jpg" alt="screen" srcset="">
                			</a>
                			<a href="http://www.elafgroup.com/elaf-hotels-jeddah/hotels/elaf-jeddah/" class="gallery-box smooth" target="_self">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/WEDDINGS.jpg" alt="screen" srcset="">
                			</a>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><header id="header-form-slogan" class="dark pt-250 pb-250 bg-1-color-dark">
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
                                			<a data-type="li" id="linkedin" href="http://demo.microsystem.com.eg/builder/%7B%7B%20url('auth/linkedin')%20%7D%7D"><i class="icon-linkedin"></i><span>Sign in</span></a>
                            			</li>
                        			</ul></center>
                    			</div>
                			</div>
            			</div>
            			<div class="col-md-6 col-md-offset-1">
                
                
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <footer id="footer-logo-social" class="bg-2-color-dark dark pt-25 pb-25">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-6 col-md-push-6 text-md-right">
                			<ul class="social-list">
                    			<li>
                        			<a href="#"><i class="icon-twitter icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-facebook icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-linkedin icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-github-alt icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-pinterest-p icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-google-plus icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-dribbble icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-behance icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-instagram icon-size-m"></i></a>
                    			</li><li>
                        			<a href="#"><i class="icon-youtube icon-size-m"></i></a>
                    			</li>
                			</ul>
            			</div>
            			<div class="col-md-6 text-md-left col-md-pull-6">
                			<div class="float-box">
                   			<img src="{{ asset('/custom-landing/5e68e1da8885e') }}/images/logo.png" srcset="{{ asset('/custom-landing/5e68e1da8885e') }}/images/logo@2x.png 2x" alt="Your logo" class="float-left-md">
                    			<div class="float-left-md"><span>© Multifour.com.<br>All rights reserved.</span></div>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</footer>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/jquery.smooth-scroll.min.js"></script>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5e68e1da8885e') }}/js/index.js"></script>
	</body>
</html>