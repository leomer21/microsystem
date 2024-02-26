<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ App\Settings::where('type', 'app_name')->value('value') }} - Group Temporary Switch</title>
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

    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/uploaders/fileinput2.min.js"></script>
    <script>
     $('.selectt').select2({
        minimumResultsForSearch: Infinity
    });
    </script>
    

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
                                    Smart Speed-Up
                            </h5>
					</div>
                    
                    <!-- <div class="pace-activity"></div></div> -->

                    <!-- Page header -->
                    <div class="page-header page-header-default"></div>
                    <!-- /page header -->

                    <div class="modal-footer" style="width: 60%;padding: 10px;text-align: center;margin: auto;">
                        <!-- <button type="button" class="btn btn-link" data-dismiss="modal">Close</button> -->
                        
                                @if( isset($request->groupTemporarySwitchToken))
                                    @if(isset($sessionRequest) && isset($allUserInfo))
                                        <!-- session in already exist -->
                                        
                                        @if($sessionRequest->state == "1")

                                            <!-- Redirected outside system with valid system URL -->
                                            <form method="get" action="/groupTemporarySwitchSubmit">

                                                <!-- seaaion is acvive -->
                                                <h5 style="text-align: left;">
                                                    {!! nl2br(e($allUserInfo)) !!}
                                                </h5>
                                                <h4>
                                                    <?php if($sessionRequest->duration_by_minutes=="0"){$durationByMinutes = "more than 6 hours";}
                                                    else{$durationByMinutes = $sessionRequest->duration_by_minutes." Minutes";} ?>
                                                    <!-- User request at {{$sessionRequest->created_at}} to speed-up for {{$durationByMinutes}}. -->
                                                    User request at {{$sessionRequest->created_at}} to speed-up for {{intdiv($sessionRequest->duration_by_minutes, 60).'H:'. ($sessionRequest->duration_by_minutes % 60).'Minutes'}}
                                                </h4>
                                                <input type="hidden" name="state" value="approved">
                                                <input type="hidden" name="token" value={{$request->groupTemporarySwitchToken}}>
                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3" data-popup="tooltip" title="Allowed time untill return to his previously group ex.(type `60` and select `Minutes`)" data-placement="right">Allow for</label>
                                                    <div class="col-lg-2">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                        <input name="duration_value" type="number" value='{{$sessionRequest->duration_by_minutes}}' class="form-control" placeholder="60" required="required" aria-required="true" min="1">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-screen3"></i>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <select class="select" name="duration_type">
                                                                <option value="minutes">Minutes</option>
                                                                <option value="hour">Hours</option>
                                                                <option value="day">Days</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <label class="control-label col-lg-1" data-popup="tooltip" title="Which group you want to shift him to speed-up wifi for this user?" data-placement="right">On group</label>
                                                    <div class="col-lg-3">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <select class="select" name="new_group_id">
                                                                @foreach(App\Groups::where('is_active','1')->where('as_system','0')->where('id', '!=',$sessionRequest->previously_group_id)->get() as $group)
                                                                    <option value={{$group->id}}>{{$group->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div> 

                                                <br>
                                                <button type="submit" id="submit" data-loading-text="Loading..." class="btn btn-primary btn-block btn-rounded submit" > Approve  <i class="icon-arrow-right14 position-right"></i> </button>
                                            </form>
                                            <br>
                                            <form method="get" action="/groupTemporarySwitchSubmit">
                                                <input type="hidden" name="state" value="declined">
                                                <input type="hidden" name="token" value={{$request->groupTemporarySwitchToken}}>
                                                <button type="submit" id="submit" data-loading-text="Loading..." class="btn btn-primary btn-block btn-rounded submit btn-danger" > Decline <i class="icon-stop position-right"></i> </button>
                                            </form>
                                        @else
                                            <!-- session is deactivated -->
                                            <div class="form-group has-feedback has-feedback-left"> <h3> Request has been completed. </h3> </div>
                                        @endif
                                    @endif
                                @endif
                                
                                @if(isset($submit_state))
                                    @if($submit_state == "1")
                                        <h1> Request Approved successfully.</h1>
                                    @elseif($submit_state == "0")
                                        <h1> Request has been rejected.</h1>
                                    @else
                                        <h1> Request has been completed.</h1>    
                                    @endif
                                @endif
                    
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