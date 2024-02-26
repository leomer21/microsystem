<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Microsystem WiFi payment status</title>
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

<?php 
// for test: http://payment.microsystem.com.eg/login?owner=282&refunded_amount_cents=0&captured_amount=0&is_void=false&created_at=2018-04-17T15%3A09%3A14.226798&order=274206&profile_id=263&is_capture=false&data.message=Approved&is_voided=false&success=true&has_parent_transaction=false&is_refunded=false&hmac=0e2d04023f72770c594c71425fdaf1f7fa157d3a48fe9c11995054cb32620bccc72df15d8d19bfd8d5a5ae7e29b852bb7d77f1a3529493902493dd222e0830ae&integration_id=286&currency=EGP&error_occured=false&source_data.pan=8769&id=205535&source_data.sub_type=Visa&is_3d_secure=true&is_refund=false&amount_cents=1484000&source_data.type=card&pending=false&api_source=IFRAME&is_standalone_payment=true&is_auth=false
if(Session::get('Identify')){
    print_r( Session::get('Identify'));
}

// NOTE: redirection operation from app\Http\Controllers\Auth\AuthController.php
if(isset($_GET['is_3d_secure'])){ $is_3d_secure = $_GET['is_3d_secure'];}else{ $is_3d_secure = "false";}
if(isset($_REQUEST['success'])){
    
    $id = $_GET['id']; // 76
    $pending = $_GET['pending']; // False
    $amount_cents = $_GET['amount_cents']; // 100 
    $success = $_GET['success']; // true
    $order = $_GET['order']; // 89
    $created_at = $_GET['created_at']; // 2016-12-25T12%3A50%3A16.240255Z
    $currency = $_GET['currency']; // EGP
    $error_occured = $_GET['error_occured']; // False

    if(isset($_GET['source_data_type'])) {$source_data_type = $_GET['source_data_type'];} // False
    // else{echo "not found";}



    $customerID = DB::table('payment_response')->where('obj_id',$id)->value('customer_id');
    if(isset($customerID))
    {
        $customerData = DB::table('customers')->where('id',$customerID)->first();
        if(isset($customerData)){
            $customerURL = "http://".$customerData->url."/settings";
        }
    }
        
    
}

// if (isset($_REQUEST['pending'])) {echo $_REQUEST['pending'];}
// echo "<br>";
// if(isset($_GET['amount_cents'])) {echo $_GET['amount_cents'];}
?>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Error title -->
					<div class="text-center content-group">
						@if(isset($source_data_type) and $source_data_type == "cash")
                            
                            @if(isset($_GET['pending']) and $_GET['pending'] == "true")
                            <h1 class="error-title offline-title">
                                    Success
                            </h1>
                            <h5>The courier service representative will contact you through your registered mobile number in settings page to schedule the cash collection.</h5> 
                            @else
                            <h1 class="error-title offline-title">
                                    Failed!
                            </h1>
                            <h5>Sorry, transaction failed.</h5> 
                            @endif

                        @else
                            
                            @if(isset($success) and $success == "true")
                            <h1 class="error-title offline-title">
                                    Success
                            </h1>
                            <h5>Your transaction has been successfully.</h5> 
                            @else
                            <h1 class="error-title offline-title">
                                    Failed!
                            </h1>
                            <h5>Sorry, transaction failed.</h5> 
                            @endif

                        @endif                        
					</div>
					
                    
                    <div class="pace-activity"></div></div>

                    <!-- Page header -->
                    <div class="page-header page-header-default"></div>
                    <!-- /page header -->


                    <!-- Content area -->
                    <div class="content">

                        <!-- Basic table -->
                        <div class="panel panel-flat" style="max-width:70%; margin-left: 15%; margin-right: 15%;">
                
                            <div class="table-responsive">
                                <table class="table">
                                    <!-- <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Username</th>
                                        </tr>
                                    </thead> -->
                                    <tbody>
                                        <tr>
                                            @if(isset($order)) <td>Order ID</td>  <td> {{$order}} </td> @endif
                                        </tr>
                                        <tr>
                                            @if(isset($amount_cents)) <td>Order Amount</td>  <td> {{$amount_cents/100}} {{$currency}} </td> @endif
                                        </tr>
                                        <!-- <tr>
                                            @if(isset($created_at)) <td>Order Date</td> <td> {{$created_at}} </td> @endif
                                        </tr> -->
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /basic table -->        

                    </div>
                    <!-- /content area -->

            
    


                    <div class="modal-footer" style="max-width:70%; margin-left: 15%; margin-right: 15%;">
                        <!-- <button type="button" class="btn btn-link" data-dismiss="modal">Close</button> -->
                        @if($is_3d_secure != "true")
                            <!-- inside frame -->
                            <button type="submit" class="btn btn-primary btn-block btn-rounded" id="Success_message"> Done, Just close this screen and refresh setting page. </button>
                        @elseif( isset($customerURL) and $is_3d_secure == "true")
                            <!-- Redirected outside system with valid system URL -->
                            <form action="{{$customerURL}}">
                                <button type="submit" class="btn btn-primary btn-block btn-rounded" id="Success_message"> Done  <i class="icon-arrow-right14 position-right"></i> </button>
                            </form>
                        @else
                            <!-- Redirected outside system without valid system URL -->
                            <button type="button" class="btn btn-primary btn-block btn-rounded" id="Success_message"> Just close this screen then open your admin panel again. </button> 
                        @endif
                        
                    </div>
                    <br><br>
            




					<!-- Footer -->
					<!-- <div class="footer text-muted text-center">
						<center>   &copy; 2018 <a target="_blank" href="http://wifi-solutions.microsystem.com.eg/">Smart WiFi</a> by <a href="http://microsystem.com.eg" target="_blank">Microsystem.</a> </center>
					</div> -->
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