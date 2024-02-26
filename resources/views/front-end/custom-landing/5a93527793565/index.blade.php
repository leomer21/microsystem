<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5a93527793565') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a93527793565') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a93527793565') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a93527793565') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a93527793565') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a93527793565') }}/css/index.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5a93527793565') }}/css/preloader.css" />
	</head>
    <body class="light-page">
	<div id="preloader"><div class="circles"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
<nav id="nav-fluid-canvas" class="navbar navbar-fixed-top dark">
        <div class="container-fluid">
            <div class="row no-pad">
                <div class="col-xs-6 text-left">
                    <a class="navbar-brand goto" href=""><img src="{{ asset('/custom-landing/5a93527793565') }}/images/logo.png" srcset="{{ asset('/custom-landing/5a93527793565') }}/images/logo@2x.png 2x" height="26" alt="Your logo"></a>
                </div>
                <div class="col-xs-6 text-right">
                    <button type="button" class="off-canvas-toggle">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="nav-bg bg-1-color-dark"></div>
    </nav><aside id="navbar" class="navbar-off-canvas dark text-left bg-1-color-dark">
            <a href="#" class="logo"><img src="{{ asset('/custom-landing/5a93527793565') }}/images/logo.png" srcset="{{ asset('/custom-landing/5a93527793565') }}/images/logo@2x.png 2x" alt="Your logo" class="screen"></a>
            <h2>GUM</h2>
        <figure>
            <ul class="nav">
                <li><i class="icon-speed-fast icon-position-left"></i><span><a href="#">About</a></span></li>
                <li><i class="icon-cog icon-position-left"></i><span><a href="#">How it works</a></span></li>
                <li><i class="icon-briefcase icon-position-left"></i><span><a href="#">Benefits</a></span></li>
                <li><i class="icon-picture icon-position-left"></i><span><a href="#">Screenshots</a></span></li>
                <li><i class="icon-bubble icon-position-left"></i><span><a href="#">Stories</a></span></li>
            </ul>
            <a href="#" class="btn-default btn-sm btn"><i class="icon-plus icon-position-left"></i><span>Buy now!</span></a>
        </figure>
        <figure>
            <p>So, what is the secret of successful template design? First of all, it is its friendliness – both for the template’s owner and for his or her future targeted audience.</p>
        </figure>
        <figure>
            <ul class="share-list">
                <li>
                    <a href="#" class="goodshare" data-type="fb"><i class="icon-facebook"></i><span>Share</span><span data-counter="fb">0</span></a>
                </li><li>
                    <a href="#" class="goodshare" data-type="tw"><i class="icon-twitter"></i><span>Tweet</span><span data-counter="tw"></span></a>
                </li><li>
                    <a href="#" class="goodshare" data-type="gp"><i class="icon-google-plus"></i><span>Share</span><span data-counter="gp">0</span></a>
                </li><li>
                    <a href="#" class="goodshare" data-type="li"><i class="icon-linkedin"></i><span>Share</span><span data-counter="li">0</span></a>
                </li><li>
                    <a href="#" class="goodshare" data-type="pt"><i class="icon-pinterest-p"></i><span>Share</span><span data-counter="pt">0</span></a>
                </li><li>
                    <a href="#" class="goodshare" data-type="vk"><i class="icon-vk"></i><span>Share</span><span data-counter="vk">0</span></a>
                </li><li>
                    <a href="#" class="goodshare" data-type="ok"><i class="icon-odnoklassniki"></i><span>Share</span><span data-counter="ok">0</span></a>
                </li>
            </ul>
        </figure>
        <figure>
            <ul class="social-list">
                <li>
                    <a href=""><i class="icon-twitter icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-facebook icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-linkedin icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-github-alt icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-pinterest-p icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-google-plus icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-vk icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-reddit-alien icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-skype icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-odnoklassniki icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-dribbble icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-behance icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-instagram icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-youtube icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-html5 icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-css3 icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-foursquare icon-size-m"></i></a>
                </li>
                <li>
                    <a href=""><i class="icon-dropbox icon-size-m"></i></a>
                </li>
            </ul>
        </figure>
    </aside><div class="off-canvas-overlay bg-color-menu bg-1-color-dark"></div>		<div id="wrap">
			<header id="header-form-slogan--0" class="dark bg-2-color-dark pt-250 pb-250">
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
			</header> @include('...front-end.landing.custom_auth_js') <section id="contact-form-map" class="pt-150 pb-150 bg-3-color-light light">
    			<div class="container">
        			<div class="row flex-md-vmiddle">
            			<div class="col-md-5">
                			<h2 class="mb-50">Make an appointment</h2>
                			<form action="./scripts/request.php" class="contact_form" novalidate="novalidate" id="contact-form-map-form">
                    			<div class="form-group">
                        			<input type="text" class="form-control contact_name" placeholder="Full name" name="name">
                    			</div>
                    			<div class="form-group">
                        			<input type="email" class="form-control contact_email" placeholder="Email Address" name="email">
                    			</div>
                    			<div class="form-group">
                        			<textarea class="form-control contact_message" rows="6" placeholder="Your message or question" name="message"></textarea>
                    			</div>
                    			<button type="submit" data-loading-text="•••" data-complete-text="Completed!" data-reset-text="Try again later..." class="btn btn-block btn-primary contact_submit"><span>Send</span></button>
                			</form>
                			<small class="desc-text">You are very important to us, all information received will always remain confidential.</small>
            			</div>
            			<div class="col-md-6 col-md-offset-1">
                			<div id="contact-form-map-map" class="embed-responsive embed-responsive-4by3 g-map"></div>
            			</div>
        			</div>
    			</div>
    			<div class="bg"></div>
			</section>
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5a93527793565') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5a93527793565') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5a93527793565') }}/js/jquery.validate.min.js"></script>
		<script src="{{ asset('/custom-landing/5a93527793565') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5a93527793565') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5a93527793565') }}/js/index.js"></script>
	</body>
</html>