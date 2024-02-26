<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5e15b87070558') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e15b87070558') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e15b87070558') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e15b87070558') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e15b87070558') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e15b87070558') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5e15b87070558') }}/css/index.css" />
	</head>
    <body class="light-page">
		<div id="wrap">
			<header id="header-center-slogan-img" class="pt-150 pb-0 dark bg-3-color-dark">
    			<div class="container">
        			<div class="row  text-center">
            			<div class="col-md-12">
                			<h1><br></h1>
                			<h2 class="mb-75">Pure design, awesome features &amp; absolute flexibility</h2>
                			<a href="#" class="btn btn-default"><i class="icon-apple2 icon-size-m icon-position-left"></i><span>Download on the <strong>App Store</strong></span></a>
                			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/phone-top.png" srcset="{{ asset('/custom-landing/5e15b87070558') }}/images/phone-top@2x.png 2x" alt="" class="screen mt-125">
            			</div>
        			</div>
    			</div>
    			<div class="bg parallax-bg skrollable-after" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="gallery-list-3col" class="pt-125 pb-150 bg-1-color-light text-center light">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-75">Gallery</h2>
        			</div>
        			<div class="row gallery">
            			<div class="col-md-4">
                			<a href="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-7.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-7.jpg" alt="screen">
                			</a>
                			<a href="https://vimeo.com/123395658" class="gallery-box mfp-iframe">
                    			<i class="icon icon-size-m icon-ion-ios-play-outline"></i>
                    			<span class="caption">Video item</span>
                    			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-8.jpg" alt="screen">
                			</a>
            			</div>
            			<div class="col-md-4">
                			<a href="https://www.google.com.ua" target="_blank" class="gallery-box external">
                    			<i class="icon icon-size-m icon-link"></i>
                    			<span class="caption">Item with external link</span>
                    			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-9.jpg" alt="screen">
                			</a>
                			<a href="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-10.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-10.jpg" alt="screen">
                			</a>
            			</div>
            			<div class="col-md-4">
                			<a href="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-11.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-11.jpg" alt="screen">
                			</a>
                			<a href="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-12.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/screen-12.jpg" alt="screen">
                			</a>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="price-4col" class="pt-125 pb-125 bg-1-color-light light">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2>Prices</h2>
            			<h4 class="mb-75">You are very important to us, all information received will always remain confidential.</h4>
        			</div>
        			<div class="row">
            			<div class="col-md-3 col-sm-6">
                			<div class="price-box border-box">
                   			<div class="stamp">
                        			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-free.png" srcset="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-free@2x.png 2x" alt="for free">
                    			</div>
                    			<h3 class="price-title">Trial</h3>
                    			<hr>
                    			<div class="content">
                        			<ul class="text-icon-list">
                            			<li><i class="icon-laptop-phone"></i><span>Fully responsive</span></li>
                            			<li><i class="icon-bucket"></i><span>Clean design</span></li>
                            			<li><i class="icon-equalizer"></i><span><del>Great flexibility</del></span></li>
                            			<li><i class="icon-book2"></i><span><del>Documentation</del></span></li>
                            			<li><i class="icon-shield-check"></i><span><del>6 month support</del></span></li>
                        			</ul>
                    			</div>
                    			<div class="price">
                        			<h3><strong>FREE</strong></h3>
                        			<small class="desc-text">for just<br>1 month</small>
                    			</div>
                    			<a href="#" class="btn btn-primary btn-block"><span>Try now</span></a>
                			</div>
            			</div>
            			<div class="col-md-3 col-sm-6">
                			<div class="price-box border-box">
                    			<div class="stamp">
                        			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-beginner.png" srcset="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-beginner@2x.png 2x" alt="best for beginners">
                    			</div>
                    			<h3 class="price-title">Beginner</h3>
                    			<hr>
                    			<div class="content">
                        			<ul class="text-icon-list">
                            			<li><i class="icon-laptop-phone"></i><span>Fully responsive</span></li>
                            			<li><i class="icon-bucket"></i><span>Clean design</span></li>
                            			<li><i class="icon-equalizer"></i><span>Great flexibility</span></li>
                            			<li><i class="icon-book2"></i><span><del>Documentation</del></span></li>
                            			<li><i class="icon-shield-check"></i><span><del>6 month support</del></span></li>
                        			</ul>
                    			</div>
                    			<div class="price">
                        			<h3><strong>$0.99</strong></h3>
                        			<small class="desc-text">per <br>month</small>
                    			</div>
                    			<a href="#" class="btn btn-primary btn-block"><span>Buy now</span></a>
                			</div>
            			</div>
            			<div class="col-md-3 col-sm-6">
                			<div class="price-box border-box">
                   			<div class="stamp">
                        			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-best.png" srcset="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-best@2x.png 2x" alt="king size">
                    			</div>
                    			<h3 class="price-title">Gold</h3>
                    			<hr>
                    			<div class="content">
                        			<ul class="text-icon-list">
                            			<li><i class="icon-laptop-phone"></i><span>Fully responsive</span></li>
                            			<li><i class="icon-bucket"></i><span>Clean design</span></li>
                            			<li><i class="icon-equalizer"></i><span>Great flexibility</span></li>
                            			<li><i class="icon-book2"></i><span>Documentation</span></li>
                            			<li><i class="icon-shield-check"></i><span><del>6 month support</del></span></li>
                        			</ul>
                    			</div>
                    			<div class="price">
                        			<h3><strong>$9.99</strong></h3>
                        			<small class="desc-text">per <br>month</small>
                    			</div>
                    			<a href="#" class="btn btn-primary btn-block"><span>Buy now</span></a>
                			</div>
            			</div>
            			<div class="col-md-3 col-sm-6">
                			<div class="price-box border-box">
                   			<div class="stamp">
                        			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-king.png" srcset="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-king@2x.png 2x" alt="king size">
                    			</div>
                    			<h3 class="price-title">Platinum</h3>
                    			<hr>
                    			<div class="content">
                        			<ul class="text-icon-list">
                            			<li><i class="icon-laptop-phone"></i><span>Fully responsive</span></li>
                            			<li><i class="icon-bucket"></i><span>Clean design</span></li>
                            			<li><i class="icon-equalizer"></i><span>Great flexibility</span></li>
                            			<li><i class="icon-book2"></i><span>Documentation</span></li>
                            			<li><i class="icon-shield-check"></i><span>6 month support</span></li>
                        			</ul>
                    			</div>
                    			<div class="price">
                        			<h3><strong>$16.99</strong></h3>
                        			<small class="desc-text">per <br>month</small>
                    			</div>
                    			<a href="#" class="btn btn-primary btn-block"><span>Buy now</span></a>
                			</div>
            			</div>
        			</div>

        			<div class="row mt-50">
            			<div class="col-md-2"><img class="screen" src="{{ asset('/custom-landing/5e15b87070558') }}/images/client-1.png" srcset="{{ asset('/custom-landing/5e15b87070558') }}/images/client-1@2x.png 2x" alt="logo"></div>
            			<div class="col-md-10"><small>If an eligible item that you’ve bought online doesn’t arrive, or doesn’t match the seller’s description, PayPal's Buyer Protection may reimburse you for the full amount of the item plus postage. Buyer Protection can cover your eligible online purchases, on eBay or on any other website, when you use PayPal. Conditions apply.
			</small></div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="video-center-icon" class="pt-125 pb-150 dark bg-3-color-dark">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-50">How it works</h2>
        			</div>
        			<div class="row">
            			<div class="col-md-12 text-center">
                			<a href="https://vimeo.com/123395658" class="single-iframe-popup">
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
			</header> @include('...front-end.landing.custom_auth_js') <section id="price-single" class="pt-150 pb-150 bg-3-color-light text-center light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-4 col-md-offset-4">
                			<div class="price-box border-box bg-1-color-light light">
                    			<div class="stamp">
                        			<img src="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-king.png" srcset="{{ asset('/custom-landing/5e15b87070558') }}/images/stamp-king@2x.png 2x" alt="king size">
                    			</div>
                    			<h3 class="price-title">Pre-Order</h3>
                    			<hr>
                    			<div class="content">
                        			<ul class="text-icon-list">
                            			<li><i class="icon-laptop-phone"></i><span>Fully responsive</span></li>
                            			<li><i class="icon-bucket"></i><span>Clean design</span></li>
                            			<li><i class="icon-equalizer"></i><span>Great flexibility</span></li>
                            			<li><i class="icon-book2"></i><span>Documentation</span></li>
                            			<li><i class="icon-shield-check"></i><span>6 month support</span></li>
                        			</ul>
                    			</div>
                    			<div class="price">
                        			<h3><strong>$9.99</strong></h3>
                        			<small class="desc-text">per <br>month</small>
                    			</div>
                    			<a href="#" class="btn btn-primary btn-block"><span>Order now</span></a>
                			</div>
                			<small>You are very important to us, all information received will always remain confidential. Emotions that causes your project in visitor are no less important ticket to success.</small>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5e15b87070558') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5e15b87070558') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5e15b87070558') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/5e15b87070558') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5e15b87070558') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5e15b87070558') }}/js/index.js"></script>
	</body>
</html>