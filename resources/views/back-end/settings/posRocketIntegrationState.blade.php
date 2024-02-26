<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ App\Settings::where('type', 'app_name')->value('value') }} - POS rocket Integration</title>
    <link rel="icon" href="upload/photo/faviconlogosmall.ico">

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

<body class="login-container">

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
            <a class="navbar-brand" href=""><img src="{{ asset('/') }}upload/{{ App\Settings::where('type','logo')->value('value') }}"></a>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<!-- <div class="page-content"> -->

			<!-- Main content -->
			<!-- <div class="content-wrapper"> -->

				<!-- Content area -->
				<!-- <div class="content"> -->

					<!-- Error title -->
                    <br>
					<div class="text-center content-group">
                            <img src="{{ asset('/') }}assets/images/POSrocket.png">
                            <h5 class="error-title offline-title">
                            Integration <br> Successfully
                            </h5>
					</div>
					
                    
                    <!-- <div class="pace-activity"></div></div> -->

                    <!-- Page header -->
                    <!-- <div class="page-header page-header-default"></div> -->
                    <!-- /page header -->

                    <div style="width: 50%; padding: 10px;text-align: center;margin: auto;" class="form-group has-feedback has-feedback-left">
                        <b class="form-control"> Access Token: {{$accessToken}} </b>
                    </div>
                    <div style="width: 50%; padding: 10px;text-align: center;margin: auto;" class="form-group has-feedback has-feedback-left">
                        <b class="form-control"> Refresh Token: {{$refreshToken}} </b>
                    </div>
                    <div style="width: 50%; padding: 10px;text-align: center;margin: auto;" class="form-group has-feedback has-feedback-left">
                        <b class="form-control"> Business ID: {{$businessID}} </b>
                    </div>
                    

                    <div class="modal-footer" style="width: 60%;padding: 10px;text-align: center;margin: auto;">
                        <!-- <button type="button" class="btn btn-link" data-dismiss="modal">Close</button> -->
                        
                            <!-- Redirected outside system with valid system URL -->
                            <form method="GET" action="{{ asset('/') }}settings">
                                <br>
                                <button type="submit" id="submit" data-loading-text="Loading..." class="btn btn-primary btn-block btn-rounded submit"> Back to settings <i class="icon-arrow-right14 position-right"></i> </button>
                                
                                <script>
                                    $("#submit").click(function() {
                                        var $btn = $(this);
                                        var amount = document.getElementById("amount");
                                        // if (amount == null) { 
                                            $btn.button('loading'); 
                                            setTimeout(function () {
                                            $btn.button('reset');
                                        }, 5000);
                                        // }
                                    });
                                </script>
                            </form>
                        
                    </div>
                    <br><br>
            




					<!-- Footer -->
					<!-- <div class="footer text-muted text-center">
						<center>   &copy; 2018 <a target="_blank" href="http://wifi-solutions.microsystem.com.eg/">Smart WiFi</a> by <a href="http://microsystem.com.eg" target="_blank">Microsystem.</a> </center>
					</div> -->
					<!-- /footer -->

				<!-- </div> -->
				<!-- /content area -->

			<!-- </div> -->
			<!-- /main content -->

		<!-- </div> -->
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>