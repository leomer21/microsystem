<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/590a10b0e807c') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590a10b0e807c') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590a10b0e807c') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590a10b0e807c') }}/css/owl.carousel.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590a10b0e807c') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590a10b0e807c') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/590a10b0e807c') }}/css/index.css" />
	</head>
    <body class="light-page">
		<div id="wrap">
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
                			<h1><strong>Sign up</strong><br>&amp; Sign in</h1>
                			<p>Welcome to Mac-Fi<br></p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg skrollable-after" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="price-2col" class="pt-150 pb-150 bg-1-color-light light">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-3">
                			<h2 class="mb-50">Prices</h2>
                			<p class="mb-25">You are very important to us, all information received will always remain confidential. Emotions that causes your project in visitor are no less important ticket to success.</p>
                			<a href="#" class="btn btn-default"><i class="icon-medal-empty icon-position-left"></i><span>Get special offer!</span></a>
            			</div>
            			<div class="col-md-4 col-md-offset-1">
                			<div class="price-box border-box">
                    			<div class="stamp">
                        			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/stamp-free.png" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/stamp-free@2x.png 2x" alt="for free">
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
                    			<a href="#" class="btn btn-info btn-block"><span>Try now</span></a>
                			</div>
            			</div>
            			<div class="col-md-4">
                			<div class="price-box border-box">
                    			<div class="stamp">
                        			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/stamp-king.png" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/stamp-king@2x.png 2x" alt="King size">
                    			</div>
                    			<h3 class="price-title">Professional</h3>
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
                    			<a href="#" class="btn btn-warning btn-block"><span>Buy now</span></a>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="gallery-carousel-single" class="pt-100 pb-100 bg-1-color-dark dark text-center">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2 class="mb-50">How it works</h2>
        			</div>
    			</div>
    			<div class="single-carousel gallery"><div class="item container">
            			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/browser-window-1.png" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/browser-window-1@2x.png 2x" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">I always thought that people used to pay much for quality. But these guys changed my opinion. The quality exceeds the price many times. I recommend it to everybody.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/browser-window-2.png" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/browser-window-2@2x.png 2x" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">The page is adapted to the most of the popular platform in segment. All you need to do is to choose your variant and start working.
            			</p>
        			</div><div class="item container">
            			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/16807738_10155041732575127_3342381423705122576_n.jpg" srcset="" alt="" class="screen">
            			<p class="mt-50 compressed-box-50">Your project looks great on any device. Content can be easily read and a user understands freely what you wanted to say him or her.
            			</p>
        			</div></div>
    			<div class="bg"></div>
			</section><section id="testimonials-carousel-single-2" class="pt-100 bg-2-color-light light">
    			<div class="container">
        			<div class="title-group text-center">
            			<h2>Stories</h2>
        			</div>
    			</div>
    			<div class="testimonials single-carousel-autoheight text-center slide-img-carousel"><div class="item container">
            			<blockquote>
                			<span>I always thought that people used to pay much for quality. But these guys changed my opinion. The quality exceeds the price many times. I recommend it to everybody.</span>
                			<span class="quote-desc"><small>Tom Clancy</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-1.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-1@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div><div class="item container">
            			<blockquote>
                			<span>Such simple and flexible settings make this product a universal solution for the most of the interested customers. Try it, I am sure for 100% that you will be satisfied.</span>
                			<span class="quote-desc"><small>Sarah Fox</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-2.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-2@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div><div class="item container">
            			<blockquote>
                			<span>I am deeply experienced in this sphere (more than 14 years) and I know how should high-quality products look like. Here it is. It’s excellent.</span>
                			<span class="quote-desc"><small>Tod Valdi</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-3.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-3@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div><div class="item container">
            			<blockquote>
                			<span>My colleague recommended them to me. I hesitated for a long time, but than I tried and understood what I had paid off for.</span>
                			<span class="quote-desc"><small>Alex, age 45</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-4.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-4@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div><div class="item container">
            			<blockquote>
                			<span>It’s just five stars. I will certainly come back and bring my friends with me.</span>
                			<span class="quote-desc"><small>Tina85</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-8.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-8@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div><div class="item container">
            			<blockquote>
                			<span>I don’t like to write reviews. But this time I can’t help from giving proper respect to developers. This is really nice work.</span>
                			<span class="quote-desc"><small>Customer, age 32</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-6.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-6@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div><div class="item container">
            			<blockquote>
                			<span>I can’t find alternatives to this template in the market for such a price. Of course, if you seek deeply you will find the analogue, but believe my experience – the price will be much higher.</span>
                			<span class="quote-desc"><small>Sarah, designer</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-5.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-5@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div><div class="item container">
            			<blockquote>
                			<span>This is a great product. It reveals an individual approach to each customer. I liked the level of the provided service.</span>
                			<span class="quote-desc"><small>Agency Director</small></span>
            			</blockquote>
            			<div class="slide-img-block">
                			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-7.jpg" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/photo-7@2x.jpg 2x" alt="" class="img-circle">
            			</div>
        			</div></div>
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
			</section><footer id="footer-center-social-logo" class="bg-2-color-dark dark pt-100 pb-100">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-12 text-center">
                			<ul class="social-list">
                    			<li>
                        			<a href="#"><i class="icon-twitter icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-facebook icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-linkedin icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-github-alt icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-pinterest-p icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-google-plus icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-dribbble icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-behance icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-instagram icon-size-m"></i></a>
                    			</li>
                    			<li>
                        			<a href="#"><i class="icon-youtube icon-size-m"></i></a>
                    			</li>
                			</ul>
                			<span>© Multifour.com. All rights reserved.</span>
                			<div class="mt-50">
                    			<img src="{{ asset('/custom-landing/590a10b0e807c') }}/images/logo-mid.png" srcset="{{ asset('/custom-landing/590a10b0e807c') }}/images/logo-mid@2x.png 2x" alt="Your logo">
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</footer>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/owl.carousel.min.js"></script>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/590a10b0e807c') }}/js/index.js"></script>
	</body>
</html>