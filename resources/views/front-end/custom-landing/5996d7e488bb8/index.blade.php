<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>index</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
		<script src="{{ asset('/custom-landing/5996d7e488bb8') }}/js/fonts.js"></script>
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
		
		<link rel="stylesheet" href="{{ asset('/custom-landing/5996d7e488bb8') }}/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5996d7e488bb8') }}/css/icons.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5996d7e488bb8') }}/css/style.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5996d7e488bb8') }}/css/custom.css" />
		<link rel="stylesheet" href="{{ asset('/custom-landing/5996d7e488bb8') }}/css/index.css" />
	</head>
    <body class="light-page">
		<div id="wrap">
			<header id="header-slogan-text-btn" class="pt-250 pb-200 dark bg-2-color-dark">
    			<div class="container">
        			<div class="row">
            			<div class="col-md-6">
                
            			</div>
            			<div class="col-md-6">
                
                
            			</div>

        			</div>
    			</div>
    			<div class="bg parallax-bg" data-top-bottom="transform:translate3d(0px, 25%, 0px)" data-bottom-top="transform:translate3d(0px, -25%, 0px)"></div>
			</header> @include('...front-end.landing.custom_auth_js') 
		</div>
		<footer></footer>
		<div class="modal-container"></div>
		<script src="{{ asset('/custom-landing/5996d7e488bb8') }}/js/jquery-2.1.4.min.js"></script>
		<script src="{{ asset('/custom-landing/5996d7e488bb8') }}/js/bootstrap.min.js"></script>
		<script src="{{ asset('/custom-landing/5996d7e488bb8') }}/js/skrollr.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8"></script>
		<script src="https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js"></script>
		<script src="{{ asset('/custom-landing/5996d7e488bb8') }}/js/custom.js"></script>
		<script src="{{ asset('/custom-landing/5996d7e488bb8') }}/js/index.js"></script>
	</body>
</html>