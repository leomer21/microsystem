<?php
	require_once '../config.php';
	$systemLoginURL = "http://my".$installation_url."/login";
	$systemLoginURLwithoutLogin = "my".$installation_url;
	$subdomain = url()->full();
	$split = explode('/', $subdomain);
	//echo $split[2];
	if($split[2] == "my.microsystem.com.eg"){
		echo '<meta http-equiv="refresh" content="0; url=http://my.microsystem.com.eg/login" />';
	}elseif( $split[2] == $systemLoginURLwithoutLogin){
		echo '<meta http-equiv="refresh" content="0; url='.$systemLoginURL.'" />';
	}
?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Error</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->


	<!-- Theme JS files -->
	<script type="text/javascript" src="{{ asset('/') }}assets/js/core/app.js"></script>
	<!-- /theme JS files -->

</head>

<body class="login-container  pace-done" style=""><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.html"><img src="" alt=""></a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			

			
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container" style="min-height:272px">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">
					
					<!-- Error title -->
					<div class="text-center content-group">
						<h1 class="error-title">405</h1>
						<h5>Oops, an error has occurred. Not allowed!</h5>
					</div>
					<!-- /error title -->


					<!-- Error content -->
					<div class="row">
						<div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
							<form action="#" class="main-search">
								

								<div class="row">
									<div class="col-sm-12">
										<a href="{{$systemLoginURL}}" class="btn btn-primary btn-block content-group"><i class="icon-circle-left2 position-left"></i> Go to dashboard</a>
									</div>

								</div>
							</form>
						</div>
					</div>
					<!-- /error wrapper -->


					<!-- Footer -->
					
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->



</body>
</html>
