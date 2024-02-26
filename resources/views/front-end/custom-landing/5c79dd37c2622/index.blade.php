<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5c79dd37c2622') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5c79dd37c2622') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5c79dd37c2622') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5c79dd37c2622') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5c79dd37c2622') }}/css/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5c79dd37c2622') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5c79dd37c2622') }}/css/index.css" />
	</head>
    <body class="light-page">
<nav id="nav-fluid-logo-menu-btn-2" class="navbar navbar-fixed-top dark">
    <div class="container-fluid">
        <div class="row no-pad">
            <div class="col-md-2 text-left">
                <div class="row no-pad">
                    <div class="col-xs-6 col-md-12">
                        <div class="navbar-brand"><img src="{{ asset('/custom-landing/5c79dd37c2622') }}/images/logo.png" srcset="{{ asset('/custom-landing/5c79dd37c2622') }}/images/logo@2x.png 2x" alt="Your logo"></div>
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
                        <li><span><a href="#">Benefits</a></span></li>
                        <li><span><a href="#">Testimonials</a></span></li>
                        <li><span><a href="#">Prices</a></span></li>
                    </ul>
                    <span class="btn-group"><a class="btn btn-primary btn-lg"><i class="icon-plus icon-position-left"></i><span>Buy now</span></a></span>
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
                			<h1><strong>Sign up</strong><br>&amp; Sign in</h1>
                			<p>Emotions that causes your project in visitor are no less important ticket to success. Modern solutions, interesting elements, unique approach to details make this template recognizable and interesting.
                			</p>
            			</div>
        			</div>
    			</div>
    			<!-- End: Logo and Countdown Area -->
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <header id="header-center-slogan-img" class="pt-150 pb-0 dark bg-3-color-dark">
    			<div class="container">
        			<div class="row  text-center">
            			<div class="col-md-12">
                			<h1><strong>GUM</strong></h1>
                			<h2 class="mb-75">Pure design, awesome features &amp; absolute flexibility</h2>
                			<a href="#" class="btn btn-default"><i class="icon-apple2 icon-size-m icon-position-left"></i><span>Download on the <strong>App Store</strong></span></a>
                			<img src="{{ asset('/custom-landing/5c79dd37c2622') }}/images/phone-top.png" srcset="{{ asset('/custom-landing/5c79dd37c2622') }}/images/phone-top@2x.png 2x" alt="" class="screen mt-125">
            			</div>
        			</div>
    			</div>
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') <section id="team-2col-2row" class="pt-125 pb-125 bg-1-color-light light">
    			<div class="container">
        			<div class="title-group">
            			<h2>Our specialists</h2>
            			<h4 class="mb-75">In our work we try to use only the most modern, convenient and interesting solutions.</h4>
        			</div>
        			<div class="row">
            			<div class="col-md-6">
                			<div class="team-box border-box">
                    			<div class="row">
                        			<div class="col-md-6">
                            			<div class="team-contact mb-25">
                                			<ul class="social-list">
                                    			<li><a href="#"><i class="icon-twitter icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-facebook icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-linkedin icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-google-plus icon-size-m"></i></a></li>
                                			</ul>
                                			<img class="screen" src="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-1.jpg" srcset="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-1@2x.jpg 2x" alt="team member">
                            			</div>
                            			<h3>Michael Smith</h3>
                            			<h4>Head doctor</h4>
                        			</div>
                        			<div class="col-md-6">
                            			<p class="mb-25">So, what is the secret of successful template design? First of all, it is its friendliness – both for the template’s owner and for his future targeted audience. UX and UI are not just empty phrases for us.</p>
                        			</div>
                    			</div>
                			</div>

                			<div class="team-box border-box">
                    			<div class="row">
                        			<div class="col-md-6">
                            			<div class="team-contact mb-25">
                                			<ul class="social-list">
                                    			<li><a href="#"><i class="icon-twitter icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-facebook icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-linkedin icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-google-plus icon-size-m"></i></a></li>
                                			</ul>
                                			<img class="screen" src="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-3.jpg" srcset="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-3@2x.jpg 2x" alt="team member">
                            			</div>
                            			<h3>Laura O'Neil</h3>
                            			<h4>Therapist</h4>
                        			</div>
                        			<div class="col-md-6">
                            			<p>Emotions that causes your project in visitor are no less important ticket to success. Modern solutions, interesting elements, unique approach to details make this template recognizable and interesting.</p>
                        			</div>
                    			</div>
                			</div>
            			</div>

            			<div class="col-md-6">
                			<div class="team-box border-box">
                    			<div class="row">
                        			<div class="col-md-6">
                            			<div class="team-contact mb-25">
                                			<ul class="social-list">
                                    			<li><a href="#"><i class="icon-twitter icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-facebook icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-linkedin icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-google-plus icon-size-m"></i></a></li>
                                			</ul>
                                			<img class="screen" src="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-2.jpg" srcset="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-2@2x.jpg 2x" alt="team member">
                            			</div>
                            			<h3>Katy Davis</h3>
                            			<h4>Dentist</h4>
                        			</div>
                        			<div class="col-md-6">
                            			<p>You project will not look like a template bought in a store and adapted within couple of hours. Oh, no! This is not your case. You obtain qualitative, fascinating and juicy final product that is modern and actual. </p>
                        			</div>
                    			</div>
                			</div>
                			<div class="team-box border-box">
                    			<div class="row">
                        			<div class="col-md-6">
                            			<div class="team-contact mb-25">
                                			<ul class="social-list">
                                    			<li><a href="#"><i class="icon-twitter icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-facebook icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-linkedin icon-size-m"></i></a></li>
                                    			<li><a href="#"><i class="icon-google-plus icon-size-m"></i></a></li>
                                			</ul>
                                			<img class="screen" src="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-4.jpg" srcset="{{ asset('/custom-landing/5c79dd37c2622') }}/images/team-4@2x.jpg 2x" alt="team member">
                            			</div>
                            			<h3>Sam</h3>
                            			<h4>Assistant</h4>
                        			</div>
                        			<div class="col-md-6">
                            			<p>Friendliness and emotions – these are the main principles of our design. Qualitative and flexible code lays in base of this great product.</p>
                        			</div>
                    			</div>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="desc-accordion" class="bg-2-color-light pt-125 pb-125 light">
    			<div class="container">
        			<h2 class="mb-50">FAQ</h2>
        			<div class="row">
            			<div class="col-md-12" id="accordion-faq-1" role="tablist" aria-multiselectable="true">
                			<div class="panel">
                    			<a class="panel-heading" data-toggle="collapse" role="button" data-parent="#accordion-faq-1" href="#collapse-faq-1"><h4>Where can I get more information and help?</h4></a>
                    			<div id="collapse-faq-1" class="panel-collapse collapse in">
                        			<div class="panel-body">
                            			<p>Emotions that causes your project in visitor are no less important ticket to success. Modern solutions, interesting elements, unique approach to details make this template recognizable and interesting. You project will not look like a template bought in a store and adapted within couple of hours. Oh, no! This is not your case. You obtain qualitative, fascinating and juicy final product that is modern and actual. Friendliness and emotions – these are the main principles of our design. Qualitative and flexible code lays in base of this great product.</p>
                        			</div>
                    			</div>
                			</div>
                			<div class="panel">
                    			<a class="panel-heading collapsed" data-toggle="collapse" role="button" data-parent="#accordion-faq-1" href="#collapse-faq-2"><h4>Where can I purchase this template?</h4></a>
                    			<div id="collapse-faq-2" class="panel-collapse collapse">
                        			<div class="panel-body">
                            			<p>So, what is the secret of successful template design? First of all, it is its friendliness – both for the template’s owner and for his or her future targeted audience. UX and UI are not just empty phrases for us. It is very important for us that the user could understand correctly the message your project’s trying to say to him or her. But, correct giving of the information is just a half of success.</p>
                        			</div>
                    			</div>
                			</div>
                			<div class="panel">
                    			<a class="panel-heading collapsed" data-toggle="collapse" role="button" data-parent="#accordion-faq-1" href="#collapse-faq-3"><h4>What are the system requirements?</h4></a>
                    			<div id="collapse-faq-3" class="panel-collapse collapse">
                        			<div class="panel-body">
                            			<p>Of course, this text is just a little thing, one of those smalls that create this product in whole. They are – each of them – are responsible for the fact whether this product would be great or ordinary. It is possible to raise the whole product up only through raising smalls on a new level. So, this is what we are busy now. We spend our time and attention exactly to these things.</p>
                        			</div>
                    			</div>
                			</div>
                			<div class="panel">
                    			<a class="panel-heading collapsed" data-toggle="collapse" role="button" data-parent="#accordion-faq-1" href="#collapse-faq-4"><h4>Do I need to sign in to use an app?</h4></a>
                    			<div id="collapse-faq-4" class="panel-collapse collapse">
                        			<div class="panel-body">
                            			<p>You may take this text as a copied and pasted one, but it is not so. Appearances are deceitful. For real, this text was created by our copywriters, so you could see in details how would the final product look like.</p>
                        			</div>
                    			</div>
                			</div>
                			<div class="panel">
                    			<a class="panel-heading collapsed" data-toggle="collapse" role="button" data-parent="#accordion-faq-1" href="#collapse-faq-5"><h4>Are template updates free?</h4></a>
                    			<div id="collapse-faq-5" class="panel-collapse collapse">
                        			<div class="panel-body">
                            			<p>We could paste here the part of some article or couple of paragraphs from interesting book (that you have read for sure and may be even liked) or text in unfamiliar language ever. But we did not. We spent time on writing our own text, the text that you are reading now just like users will read your content in nearest future.</p>
                        			</div>
                    			</div>
                			</div>
                			<div class="panel">
                    			<a class="panel-heading collapsed" data-toggle="collapse" role="button" data-parent="#accordion-faq-1" href="#collapse-faq-6"><h4>Where can I get technical support for a template?</h4></a>
                    			<div id="collapse-faq-6" class="panel-collapse collapse">
                        			<div class="panel-body">
                            			<p>Of course, this text is just a little thing, one of those smalls that create this product in whole. They are – each of them – are responsible for the fact whether this product would be great or ordinary. It is possible to raise the whole product up only through raising smalls on a new level. So, this is what we are busy now. We spend our time and attention exactly to these things.</p>
                        			</div>
                    			</div>
                			</div>
            			</div>
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
			</section><section id="video-icon-text" class="pt-200 pb-200 bg-1-color-dark dark">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-6 text-center">
                			<a href="https://vimeo.com/123395658" class="single-iframe-popup"><i class="icon-ion-ios-play-outline icon-size-xl icon-color"></i></a>
            			</div>
            			<div class="col-md-6">
                			<h2 class="mb-50">Innovations</h2>
                			<p>In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. Our elements have no excessive gloss, but they are always actual. </p>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="video-text" class="pt-150 pb-150 bg-1-color-light light">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-5">
                			<h2 class="mb-50">Innovations</h2>
                			<p>In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. Our elements have no excessive gloss, but they are always actual. </p>
            			</div>
            			<div class="col-md-6 col-md-offset-1">
                			<div class="video-iframe embed-responsive embed-responsive-16by9">
                    			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/XB2g7-HgE_g?rel=0&amp;controls=0&amp;showinfo=0" allowfullscreen=""></iframe>
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><section id="video-text-btn" class="pt-150 pb-150 bg-2-color-dark dark">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-6">
                			<div class="video-iframe embed-responsive embed-responsive-16by9">
                    			<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/166929892?title=0&amp;byline=0&amp;portrait=0" allowfullscreen=""></iframe>
                			</div>
            			</div>
            			<div class="col-md-5 col-md-offset-1">
                			<h2 class="mb-50">Innovations</h2>
                			<p class="mb-50">In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. </p>
                			<a href="#" class="btn btn-default"><span>Try now</span><i class="icon-window icon-size-m icon-position-right"></i></a>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section><footer id="footer-center-share-logo" class="bg-1-color-dark dark pt-100 pb-100">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-12 text-center">
                			<ul class="share-list mb-25">
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
                			<span>© Multifour.com. All rights reserved.</span>
                			<div class="mt-50">
                    			<img src="{{ asset('/custom-landing/5c79dd37c2622') }}/images/logo-mid.png" srcset="{{ asset('/custom-landing/5c79dd37c2622') }}/images/logo-mid@2x.png 2x" alt="Your logo">
                			</div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</footer>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5c79dd37c2622') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5c79dd37c2622') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5c79dd37c2622') }}/js/jquery.magnific-popup.min.js"></script>
		<script src="{{ asset('/custom-landing/5c79dd37c2622') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5c79dd37c2622') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5c79dd37c2622') }}/js/index.js"></script>
	</body>
</html>