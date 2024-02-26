<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ App\Settings::where('type', 'app_name')->value('value') }} - Direct Charge</title>
    <link rel="icon" href="upload/photo/faviconlogosmall.ico">

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->


	<!-- Theme JS files -->
	<script type="text/javascript" src="assets/js/core/app.js"></script>
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
					<div class="text-center content-group">
                            <h5 class="error-title offline-title">
                                    <!-- {{$customerData->url}} -->
                                    {{App\Settings::where('type','directChargePageTitle')->value('value')}}
                            </h5>
					</div>
					
                    
                    <!-- <div class="pace-activity"></div></div> -->

                    <!-- Page header -->
                    <!-- <div class="page-header page-header-default"></div> -->
                    <!-- /page header -->

            

                    <div class="modal-footer" style="width: 60%;padding: 10px;text-align: center;margin: auto;">
                        <!-- <button type="button" class="btn btn-link" data-dismiss="modal">Close</button> -->
                        
                            <!-- Redirected outside system with valid system URL -->
                            <form method="GET" action="/directChargeValues">

                                <!-- If Admin need to get customer name for each transaction -->
                                @if( App\Settings::where('type','directChargeRequireCustomerName')->value('state') == 1 )
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input name="name" type="text" class="form-control" placeholder="Full Name" required="required" aria-required="true">
                                        <div class="form-control-feedback">
                                            <i class="icon-user-check text-muted"></i>
                                        </div>
                                    </div>
                                @else
                                    <!-- If Admin need to set static customer name for each transaction -->
                                    <input id="name" name="name" type="hidden" value="{{App\Settings::where('type','directChargeRequireCustomerName')->value('value')}}">
                                @endif

                                <!-- If Admin need to get customer name for each transaction -->
                                @if( App\Settings::where('type','directChargeRequireCustomerMobile')->value('state') == 1 )
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input id="mobile" name="mobile" type="number" class="form-control" placeholder="Mobile number" required="required" aria-required="true" maxlength="11" onkeypress="return isNumber(event)">
                                        <div class="form-control-feedback">
                                            <i class="icon-mobile text-muted"></i>
                                        </div>
                                    </div>
                                @else
                                    <!-- If Admin need to set static customer name for each transaction -->
                                    <input name="name" type="hidden" value="{{App\Settings::where('type','directChargeRequireCustomerMobile')->value('value')}}">
                                @endif
                                
                                <!-- If Admin need to get customer email for each transaction -->
                                @if( App\Settings::where('type','directChargeRequireCustomerEmail')->value('state') == 1 )
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" required="required" aria-required="true" onkeypress="return isNumber(event)">
                                        <div class="form-control-feedback">
                                            <i class="icon-mail5 text-muted"></i>
                                        </div>
                                    </div>
                                @else
                                    <!-- If Admin need to set static customer name for each transaction -->
                                    <input name="name" type="hidden" value="{{App\Settings::where('type','directChargeRequireCustomerEmail')->value('value')}}">
                                @endif
                                
                                <div class="form-group has-feedback has-feedback-left">
                                    <?php $currency = App\Settings::where('type','directChargeCurrency')->value('value');
                                    if($currency=="USD->EGP"){ $currency = "USD"; }
                                    ?>
                                    <input id="amount" name="amount" type="number" class="form-control" placeholder="Amount {{$currency}}" required="required" aria-required="true" maxlength="11" onkeypress="return isNumber(event)">
                                    <div class="form-control-feedback">
                                        <i class="icon-cash3 text-muted"></i>
                                    </div>
                                </div>

                                <br>
                                <button type="submit" id="submit" data-loading-text="Loading..." class="btn btn-primary btn-block btn-rounded submit" > Pay NOW  <i class="icon-arrow-right14 position-right"></i> </button>
                                
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