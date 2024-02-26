<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f491d679d0c3') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f491d679d0c3') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f491d679d0c3') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f491d679d0c3') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f491d679d0c3') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5f491d679d0c3') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-logo-menu-btn" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/logo.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/logo@2x.png 2x" alt="Your logo"></div>
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
			<header id="header-center-slogan-img-videobg" class="pt-200 bg-1-color-dark dark">
    			<div class="container">
        			<div class="row text-center">
            			<div class="col-md-12">
                			<div class="mb-50">
                    			<h1>Design &amp; Flexibility</h1>
                    			<p>The given template is armed with the number of settings, so you can easily adapt it according to you requirements.
                    			</p>
                			</div>
                			<a href="#" class="btn btn-primary"><i class="icon-window icon-size-m icon-position-left"></i><span><strong>Download for FREE</strong></span></a><a href="#" class="btn btn-default goto"><i class="icon-plus icon-size-m icon-position-left"></i> <span><strong>View more</strong></span></a>

                			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/browser-windows.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/browser-windows@2x.png 2x" class="screen mt-125" alt="">
            			</div>
        			</div>
    			</div>
    			<div class="bg bg-video parallax-bg" data-vide-bg="mp4: video/video_bg, ogv: video/video_bg, jpg: video/video_bg" data-vide-options="posterType: jpg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="clients-5col-2row" class="pt-125 pb-150 bg-1-color-dark dark">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-75">Partners</h2>
        			</div>
        			<div class="row">
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-1-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-1-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>General partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-6-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-6-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Limited partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-2-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-2-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Media partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-7-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-7-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>General partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-3-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-3-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Limited partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-8-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-8-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Global partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-4-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-4-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Honorary partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-9-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-9-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Media partner</p>
                			</div>
            			</div>
            			<div class="col-md-20 col-sm-4 text-center">
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-5-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-5-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>Global partner</p>
                			</div>
                			<div class="content-box no-space">
                    			<a href="#">
                        			<img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-10-dark.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-10-dark@2x.png 2x" alt="Client">
                    			</a>
                    			<p>General partner</p>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="desc-halfbg-text-btn" class="bg-1-color-dark dark pt-200 pb-md-200">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-4 col-md-offset-8 text-md-left">
                			<h2>Multiplatform</h2>
                			<p class="mb-50">The page is adapted to the most of the popular platform in segment. All you need to do is to choose your variant and start working.</p>
                			<a href="#" class="btn btn-default"><span>Read more</span><i class="icon-arrow-right icon-position-right"></i></a>
            			</div>
        			</div>
    			</div>
    			<div class="half-container-left"></div>
    			<div class="bg"></div>
			</section><section id="gallery-list-3col" class="pt-125 pb-150 bg-1-color-light text-center light">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-75">Gallery</h2>
        			</div>
        			<div class="row gallery">
            			<div class="col-md-4">
                			<a href="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-7.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-7.jpg" alt="screen">
                			</a>
                			<a href="https://vimeo.com/123395658" class="gallery-box mfp-iframe">
                    			<i class="icon icon-size-m icon-ion-ios-play-outline"></i>
                    			<span class="caption">Video item</span>
                    			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-8.jpg" alt="screen">
                			</a>
            			</div>
            			<div class="col-md-4">
                			<a href="https://www.google.com.ua" target="_blank" class="gallery-box external">
                    			<i class="icon icon-size-m icon-link"></i>
                    			<span class="caption">Item with external link</span>
                    			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-9.jpg" alt="screen">
                			</a>
                			<a href="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-10.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-10.jpg" alt="screen">
                			</a>
            			</div>
            			<div class="col-md-4">
                			<a href="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-11.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-11.jpg" alt="screen">
                			</a>
                			<a href="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-12.jpg" class="gallery-box">
                    			<i class="icon icon-size-m icon-plus"></i>
                    			<span class="caption">Gallery item with zoom option</span>
                    			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/screen-12.jpg" alt="screen">
                			</a>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="video-center-text" class="pt-125 pb-125 bg-2-color-light light">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-75">How it works</h2>
        			</div>
        			<div class="row">
            			<div class="col-md-10 col-md-offset-1 text-center">
                			<div class="video-iframe embed-responsive embed-responsive-16by9 mb-75">
                    			<iframe src="https://player.vimeo.com/video/149850024?title=0&amp;byline=0&amp;portrait=0&amp;badge=0" allowfullscreen=""></iframe>
                			</div>
                			<p class="compressed-box-50">In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. Our elements have no excessive gloss, but they are always actual. </p>
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
                        			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-free.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-free@2x.png 2x" alt="for free">
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
                        			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-beginner.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-beginner@2x.png 2x" alt="best for beginners">
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
                        			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-best.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-best@2x.png 2x" alt="king size">
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
                        			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-king.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/stamp-king@2x.png 2x" alt="king size">
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
            			<div class="col-md-2"><img class="screen" src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-1.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/client-1@2x.png 2x" alt="logo"></div>
            			<div class="col-md-10"><small>If an eligible item that you’ve bought online doesn’t arrive, or doesn’t match the seller’s description, PayPal's Buyer Protection may reimburse you for the full amount of the item plus postage. Buyer Protection can cover your eligible online purchases, on eBay or on any other website, when you use PayPal. Conditions apply.
			</small></div>
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
    			<div class="bg parallax-bg skrollable-before" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <footer id="footer-logo-share-subscribe" class="bg-1-color-dark dark pt-100 pb-100">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-7 col-md-push-5">
                			<div class="row">
                    			<div class="col-sm-3">
                        			<h4>Learn more</h4>
                        			<ul class="text-list">
                            			<li>
                                			<a href="#">Tour</a>
                            			</li>
                            			<li>
                                			<a href="#">Customers</a>
                            			</li>
                            			<li>
                                			<a href="#">Pricing and Plans</a>
                            			</li>
                            			<li>
                                			<a href="#">New Features</a>
                            			</li>
                            			<li>
                                			<a href="#">Education</a>
                            			</li>

                        			</ul>
                    			</div>
                    			<div class="col-sm-3">
                        			<h4>Extras</h4>
                        			<ul class="text-list">
                            			<li>
                                			<a href="#">Marketplace</a>
                            			</li>
                            			<li>
                                			<a href="#">Design</a>
                            			</li>
                            			<li>
                                			<a href="#">Team Sync</a>
                            			</li>
                            			<li>
                                			<a href="#">Disruptors</a>
                            			</li>
                            			<li>
                                			<a href="#">Free T-Shirt</a>
                            			</li>

                        			</ul>
                    			</div>
                    			<div class="col-sm-6">
                        			<form action="scripts/request.php" method="post" class="subscribe_form" novalidate="novalidate" id="footer-logo-share-subscribe-form">
                            			<div class="input-group">
                                			<input class="form-control subscribe_email" type="email" name="email" placeholder="Enter your email">
                                			<div class="input-group-btn">
                                    			<button type="submit" class="btn-link btn subscribe_submit" data-loading-text="•••"><i class="icon icon-arrow-right"></i></button>
                                			</div>
                            			</div>
                        			</form>
                        			<small class="desc-text"><span>Follow us in social networks. You can also subscribe for our news. We are going to provide you with actual and important for you information without spam or fluff.</span></small>
                    			</div>

                			</div>
            			</div>
            			<div class="col-md-5 col-md-pull-7 text-md-left">
                			<div class="float-box mb-50">
                   			<img src="{{ asset('/custom-landing/5f491d679d0c3') }}/images/logo.png" srcset="{{ asset('/custom-landing/5f491d679d0c3') }}/images/logo@2x.png 2x" alt="Your logo" class="float-left-md">
                    			<div class="float-left-md"><span>© Multifour.com.<br>All rights reserved.</span></div>
                			</div>
                			<ul class="share-list">
                    			<li>
                        			<a href="#" class="goodshare" data-type="fb"><i class="icon-facebook"></i><span>Share</span><span data-counter="fb"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="tw"><i class="icon-twitter"></i><span>Tweet</span><span data-counter="tw"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="gp"><i class="icon-google-plus"></i><span>Share</span><span data-counter="gp"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="li"><i class="icon-linkedin"></i><span>Share</span><span data-counter="li"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="pt"><i class="icon-pinterest-p"></i><span>Share</span><span data-counter="pt"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="vk"><i class="icon-vk"></i><span>Share</span><span data-counter="vk"></span></a>
                    			</li><li>
                        			<a href="#" class="goodshare" data-type="ok"><i class="icon-odnoklassniki"></i><span>Share</span><span data-counter="ok"></span></a>
                    			</li>
                			</ul>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</footer>
		</div>
		<footer></footer>
		<div class="modal-container">
			<div class="modal fade modal-confirm flex-center dark" id="footer-logo-share-subscribe-success" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
			<div class="modal-content text-center">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span></button></div>
			<div class="modal-body">
			<i class="content-icon icon icon-checkmark-circle icon-size-xl icon-color mb-50"></i>
			<h3 class="mb-25 mailchimp-data-message">Your message was sent successfully!</h3>
			<p class="mb-50">In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. Our elements have no excessive gloss, but they are always actual.</p>
			<a href="#" class="btn btn-default">Download</a></div>
			<div class="bg bg-type-cover"></div></div></div></div>
			<div class="modal fade modal-confirm flex-center dark" id="footer-logo-share-subscribe-error" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
			<div class="modal-content text-center">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span></button></div>
			<div class="modal-body">
			<i class="content-icon icon icon-warning icon-size-xl icon-color mb-50"></i>
			<h3 class="mb-25">Oops! Something went wrong!</h3>
			<p class="mb-50">In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. Our elements have no excessive gloss, but they are always actual.</p>
			<a href="#" class="btn btn-danger">Ask support</a></div>
			<div class="bg bg-type-cover"></div></div></div></div>
		</div>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/jquery.vide.min.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/jquery.validate.min.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5f491d679d0c3') }}/js/index.js"></script>
	</body>
</html>