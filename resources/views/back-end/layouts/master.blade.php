<!DOCTYPE html>
<html lang="en">
<head>
	<?php date_default_timezone_set("Africa/Cairo"); ?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="upload/photo/faviconlogosmall.ico">
	<title>@yield('title')</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/colors.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('/') }}assets/css/timepicki.css" rel="stylesheet" type="text/css">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	@yield('css')
			<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/velocity/velocity.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/velocity/velocity.ui.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/buttons/spin.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/buttons/ladda.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/editors/summernote/summernote.min.js"></script>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/jquery_ui/widgets.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="{{ asset('/') }}assets/js/core/app.js"></script>

	<script type="text/javascript" src="{{ asset('/') }}assets/js/pages/layout_fixed_custom.js"></script>
	@yield('js')
	<!-- /theme JS files -->
</head>
<style>/*
        .dropdown-menu {z-index: 2122; overflow: visible; position: relative;}*/
</style>
<script>
    var loginStatus = {!! Auth::check() ? "'logged'" : "'not'" !!};
    var siteUrl = '{{ url("/") }}';
    var token = '{{ csrf_token() }}';
</script>
<body class="navbar-top  pace-done sidebar-xs">
	<!-- Main navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-header">
			@if(App\Settings::where('type', 'copyright')->value('state') == 1)
				<a class="navbar-brand" href="{{ url('/admin') }}"><img src="{{ asset('/') }}upload/{{ App\Settings::where('type','logo')->value('value') }}" alt=""></a>
			@else
				<a class="navbar-brand" href="{{ url('/admin') }}"><img src="{{ asset('/') }}assets/images/logo_light.png" alt=""></a>
			@endif
			

			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
				<!-- <li><a class="sidebar-mobile-detached-toggle"><i class="icon-grid7"></i></a></li> -->
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>

			    <!--<p class="navbar-text"><span class="label bg-success">Online</span></p>-->

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" onclick="notifications_opend()" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-bell2"></i>
						<span class="visible-xs-inline-block position-right">Notifications</span>
						<?php
						$notificationsCount=App\History::where(['type1' => 'branches_changes'])->WhereNull('notes')->count();
						?>
						@if(isset($notificationsCount) and $notificationsCount>0)<span class="badge bg-warning-400">{{$notificationsCount}}</span>@endif
					</a>
					
					<div class="dropdown-menu dropdown-content">
						<div class="dropdown-content-heading">
							Notifications
							<ul class="icons-list">
								<li><a href="#" onclick="notifications()"><i class="icon-sync"></i></a></li>
							</ul>
						</div>

						<ul class="media-list dropdown-content-body width-350">
							<div class="updates"></div>
						</ul>

						<div class="dropdown-content-footer">
							<a href="#" data-popup="tooltip" title=""><i class="icon-menu display-block"></i></a>
						</div>
					</div>
				</li>
				<!--<li class="dropdown language-switch">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ asset('/') }}assets/images/flags/gb.png" class="position-left" alt="">
						English
						<span class="caret"></span>
					</a>

					<ul class="dropdown-menu">
						<li><a class="deutsch"><img src="{{ asset('/') }}assets/images/flags/de.png" alt=""> Deutsch</a></li>
						<li><a class="ukrainian"><img src="{{ asset('/') }}assets/images/flags/ua.png" alt=""> ??????????</a></li>
						<li><a class="english"><img src="{{ asset('/') }}assets/images/flags/gb.png" alt=""> English</a></li>
						<li><a class="espana"><img src="{{ asset('/') }}assets/images/flags/es.png" alt=""> Espa?a</a></li>
						<li><a class="russian"><img src="{{ asset('/') }}assets/images/flags/ru.png" alt=""> ???????</a></li>
					</ul>
				</li>-->
				<li class="dropdown">
					<?php 
						date_default_timezone_set("Africa/Cairo");
						$Last7Days = date('Y-m-d', strtotime(date(date('Y-m-d'), strtotime(date('Y-m-d'))) . " -2 day"));
						// $users_wating_confirm = App\Users::where('Registration_type', 1)->orWhere('Registration_type', 0)->orderBy('u_id', 'desc')->get();
						$users_count = App\Users::where('Registration_type', '!=', 2)->whereDate('created_at', '>', $Last7Days)->count();
						?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-users"></i>
						<span class="visible-xs-inline-block position-right">New registration</span>
						@if($users_count == 0)
							<?php $users_wating_confirm = array(); ?>
						@else
							<span class="badge bg-warning-400">{{ $users_count }}</span>
							<?php $users_wating_confirm = App\Users::where('Registration_type', '!=', 2)->whereDate('created_at', '>', $Last7Days)->orderBy('u_id', 'desc')->get(); ?>
						@endif
					</a>
					
					<div class="dropdown-menu dropdown-content">
						<div class="dropdown-content-heading">
							Users awaiting registration approval (since Yesterday)
							<!--<ul class="icons-list">
								<li><a href="#"><i class="icon-sync"></i></a></li>
							</ul>-->
						</div>

						<ul class="media-list dropdown-content-body width-450">
							@foreach($users_wating_confirm as $users)
							<li class="media">
								<!--<div class="media-left">
									<a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></a>
								</div>-->
								<div class="media-body">
									<a href="#" @if(isset($users['sms_code'])) title="SMS Verification Code: {{$users['sms_code']}}" @endif >{{ $users['u_name'] }}</a> {{ $users['u_phone'] }} <a class="media-annotation" @if(isset($users['sms_code'])) title="SMS Verification Code: {{$users['sms_code']}}" @endif > since {{ $users['created_at']->diffForHumans() }} @if(isset($users['sms_code']))<span class="status-mark border-orange position-left"></span>SMS {{$users['sms_code']}} </a>@endif
									<br>
									<?php 
										$branches = App\Branches::where('state',1)->get();
										//print_r($networks);
									?>
									<div class="row"> 
									<form action="{{ url('confirmusers/'.$users['u_id']) }}" method="POST" id="confirm-{{$users['u_id']}}">
                                    {{ csrf_field() }}
										<div class="col-md-5"> 
											<select  data-placeholder="Select branch" class="select-fixed" name="branches">
												@foreach ($branches as $branche)
                                                    <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                           		@endforeach
                                           	</select>
										</div>
										<?php 
											$groups = App\Groups::where('as_system' , 0)->where('is_active' , 1)->get();
										?>
										<div class="col-md-4"> 
											<select  data-placeholder="Select group" class="select-fixed" name="groups">
												@foreach ($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                           		@endforeach
                                           	</select>
										</div>
										<div class="col-md-3">
											<ul class="icons-list">
												<li></li> 
                                                <li><i onclick="document.forms['delete-{{$users['u_id']}}'].submit(); return false;"> </i> <a href="#" onclick="document.forms['delete-{{$users['u_id']}}'].submit(); return false;"><i class="icon-cross2 btn-sm"></i></a></li>
                                                
                                                <li><i onclick="document.forms['confirm-{{$users['u_id']}}'].submit(); return false;"> </i> <a href="#" onclick="document.forms['confirm-{{$users['u_id']}}'].submit(); return false;"><i class="icon-checkmark3 btn-sm"></i></a></li>
                                            </ul>
										</div>
									</form>
									<form action="{{ url('deleteusers/'.$users['u_id']) }}" method="POST" id="delete-{{$users['u_id']}}">
										{{ csrf_field() }}
									</form>
									</div>
									<!--<br>
									<center><button class="btn btn-primary">
										<i class="icon-cog3 position-left"></i> Confirm
									</button></center>-->
								</div>
							</li>
							@endforeach
						</ul>
						<div class="dropdown-content-footer">
							<!--<a href="#" data-popup="tooltip" title="All activity">--><i class="icon-menu display-block"></i><!--</a>-->
						</div>
					</div>
				</li>
				
				<li class="dropdown">
					<?php 
						$messages = App\Messages::where('state', 0)->orderBy('id','desc')->limit(10)->get();
						$message = App\Messages::where('state', 0)->first();

						$message_count = App\Messages::where('state', 0)->count();
						?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-bubbles4"></i>
						<span class="visible-xs-inline-block position-right">Messages</span>
						@if($message_count == 0)
						@else
						<span class="badge bg-warning-400">{{ $message_count }}</span>
						@endif
					</a>
					
					<div class="dropdown-menu dropdown-content width-350">
						<div class="dropdown-content-heading">
							last 10 Users Messages
							<!--<ul class="icons-list">
								<li><i class="icon-compose"></i></li>
							</ul>-->
						</div>

						<ul class="media-list dropdown-content-body">
							@foreach($messages as $message)
							<?php 
								$count = App\Messages::where('parent_id', $message['id'])->count() +1;
							?>
							<li class="media">
								<div class="media-left">
									<img src="{{ asset('/') }}assets/images/profile.png" class="img-circle img-sm" alt="">
									<span class="badge bg-danger-400 media-badge">{{ $count }}</span>
								</div>

								<div class="media-body">
									<a href="#" onclick="_open({{ $message['u_id'] }})" class="media-heading">
										<span class="text-semibold">{{ $message['name'] }}</span>
										<span class="media-annotation pull-right">{{ $message['created_at']->diffForHumans() }}</span>
									</a>

									<span class="text-muted">{{ $message['message'] }}...</span>
								</div>
							</li>
							@endforeach
						</ul>

						<div class="dropdown-content-footer">
							<!--<a href="#" data-popup="tooltip" title="All messages">--><i class="icon-menu display-block"></i><!--</a>-->
						</div>
					</div>
				</li>

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle"  data-toggle="dropdown">
                        @if(isset(Auth::user()->photo))
                        	<img src="upload/photo/{{Auth::user()->photo}}" alt="">
                        @else
                        	<img src="{{ asset('/') }}assets/images/profile.png" alt="">
                        @endif
						<span>{{ Auth::user()->name }}</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{{ url('myprofile') }}"><i class="icon-profile"></i> My profile</a></li>
						<!--<li><a href="#"><i class="icon-coins"></i> My balance</a></li>
						<li><a href="#"><span class="badge bg-teal-400 pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
						<li class="divider"></li>-->
						<!-- <li><a href="{{ url('/settings') }}?q=1"><i class="icon-cog5"></i> Account settings</a></li> -->
						<li><a href="{{ url('/logout') }}"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<div class="sidebar sidebar-main">
				<div class="sidebar-content">

					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="{{ url('/admin') }}" class="media-left">
                                @if(isset(Auth::user()->photo))
                                    <img src="upload/photo/{{Auth::user()->photo}}" class="img-circle img-sm" title="">
                                    @else
                                    <img src="{{ asset('/') }}assets/images/profile.png" class="img-circle img-sm" alt="">
                                @endif
								</a>
								<div class="media-body">
									<span class="media-heading text-semibold">{{ Auth::user()->name }}</span>
									<!-- <div class="text-size-mini text-muted">
										<i class="icon-pin text-size-small"></i> &nbsp;{{ Auth::user()->address }}
									</div> -->
								</div>

								<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="{{ url('/settings') }}"><i class="icon-cog3"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->


					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul id="menu" class="navigation navigation-main navigation-accordion">

								<!-- Main -->
                                @include('back-end.layouts.menu')

							</ul>
						</div>
					</div>
					<!-- /main navigation -->
				</div>
			</div>
			<!-- /main sidebar -->

			@section('sidebar')

			@show
			<!-- Basic modal -->
				<div id="modal_default" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h5 class="modal-title"></h5>
							</div>
							<div class="modal-body">
			                    
							</div>

							<div class="modal-footer">
			                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			                    <button type="button" class="btn btn-primary" onclick="document.forms['send'].submit(); return false;">Send</button>
			                </div>
						</div>
					</div>
				</div>
			<!-- /basic modal -->

            @yield('content')
		</div>
		<!-- /page content -->
	</div>
	<!-- /page container -->
		<script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/notifications/pnotify.min.js"></script>
	<script>
		<?php
			$split = explode('status', url()->full()); ?>
			@if(isset($split[1]) && $split[1] == "=0")		
				new PNotify({
		            title: 'Opps!',
		            text: 'Please try again you have failed.',
		            addclass: 'bg-danger'
		        });
			@endif
		
		    @if(session('reboot_notify') && !session('reboot_notify_done'))
		       {{ Session::push('reboot_notify_done', '1') }}

		       new PNotify({
		            title: '{{ session('reboot_notify')[0] }}',
		            text: 'Mikrotik Has been rebooted',
		            addclass: 'bg-success'
		        });
		    @endif
		    
		    @if(session('reset_notify') && !session('reset_notify_done'))
		       {{ Session::push('reset_notify_done', '1') }}

		       new PNotify({
		            title: '{{ session('reset_notify')[0] }}',
		            text: 'Mikrotik Has been reseted',
		            addclass: 'bg-success'
		        });
		    @endif
			

			function notifications_opend(){
		    	$.ajax({
		        	url:'notifications_opend', type: 'get', data: {_token: $('meta[name="csrf-token"]').attr('content')}, cache: true,
		            success: function(value) {
		                $('.updates').prepend(value);
		            }
		        });
	        }

		    function notifications(){
	    		$('.green').remove();
		    	$.ajax({
		        	url:'notifications', type: 'get', data: {_token: $('meta[name="csrf-token"]').attr('content')}, cache: true,
		            success: function(value) {
		                $('.updates').prepend(value);
		            }
		        });
	        }		
		    $(document).ready(function () {
			    var interval = 120000;   //number of mili seconds between each call
			    var refresh = function() {
			        $('.green').remove();

			        $.ajax({
			        	url:'notifications', type: 'get', data: {_token: $('meta[name="csrf-token"]').attr('content')}, cache: true,
			            success: function(value) {

			                $('.updates').prepend(value);
			                setTimeout(function() {
			                    refresh();
			                }, interval);
			            }
			        });
			    };
			    refresh();
		    });    
        $('.select-fixed').select2({
		    minimumResultsForSearch: Infinity,
		    width: 150
		});
		function _open(id, that) {
	        $td_edit = $(that);
	        jQuery('#modal_default .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/preloader.gif" /></div>');

	        // LOADING THE AJAX MODAL
	        jQuery('#modal_default').modal('show', {backdrop: 'true'});

	        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
	        $.ajax({
	            url: 'message/' + id,
	            success: function(response)
	            {
	                jQuery('#modal_default .modal-body').html(response);
	            }
	        });
	    }
        // Buttons with progress/spinner
	    // ------------------------------

	    // Initialize on button click
	    $('.btn-loading').click(function () {
	        var btn = $(this);
	        btn.button('loading')
	        setTimeout(function () {
	            btn.button('reset')
	        }, 3000)
	    });

	    // Button with spinner
	    Ladda.bind('.btn-ladda-spinner', {
	        dataSpinnerSize: 16,
	        timeout: 2000
	    });
	    
	    // Button with progress
	    Ladda.bind('.btn-ladda-progress', {
	        callback: function(instance) {
	            var progress = 0;
	            var interval = setInterval(function() {
	                progress = Math.min(progress + Math.random() * 0.1, 1);
	                instance.setProgress(progress);

	                if( progress === 1 ) {
	                    instance.stop();
	                    clearInterval(interval);
	                }
	            }, 200);
	        }
	    });

	    // Basic editors
	    // ------------------------------

	    // Default initialization
	    $('.summernote').summernote();


	    // Control editor height
	    $('.summernote-height').summernote({
	        height: 200
	    });


	    // Air mode
	    $('.summernote-airmode').summernote({
	        airMode: true
	    });



	    // Click to edit
	    // ------------------------------

	    // Edit
	    $('#edit').on('click', function() {
	        $('.click2edit').summernote({focus: true});
	    })

	    // Save
	    $('#save').on('click', function() {
	        var aHTML = $('.click2edit').code(); //save HTML If you need(aHTML: array).
	        $('.click2edit').destroy();
	    })



	    // Related form components
	    // ------------------------------

	    // Styled checkboxes/radios
	    $(".link-dialog input[type=checkbox], .note-modal-form input[type=radio]").uniform({
	        radioClass: 'choice'
	    });


	    // Styled file input
	    $(".note-image-input").uniform({
	        fileButtonClass: 'action btn bg-warning-400'
	    });
	</script>
</body>
</html>