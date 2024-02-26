@extends('..back-end.layouts.master')
@section('title', 'Profile')
@section('content')
<?php $todayDateTime = Carbon\Carbon::now(); ?>
<!-- Main content -->
<div class="content-wrapper">

    <!-- Page header -->
    <div class="page-header">

        <!-- Header content -->
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ Auth::user()->name }}</span> - Profile</h4>
            </div>

            <!--<div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
                    <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
                    <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
                </div>
            </div>-->
        </div>
        <!-- /header content -->


        <!-- Toolbar -->
        <div class="navbar navbar-default navbar-xs">
            <ul class="nav navbar-nav visible-xs-block">
                <li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
            </ul>

            <div class="navbar-collapse collapse" id="navbar-filter">
                <ul class="nav navbar-nav element-active-slate-400">
                    <li class="active"><a href="#activity" data-toggle="tab"><i class="icon-menu7 position-left"></i> Activity</a></li>
                    <li><a href="#schedule" data-toggle="tab"><i class="icon-calendar3 position-left"></i> Schedule <span class="badge badge-success badge-inline position-right">@if(Auth::user()->type == 1) {{ App\History::where('a_id',Auth::user()->id)->count() }} @else {{ App\History::where('reseller_id',Auth::user()->id)->where('add_date',$todayDateTime->toDateString())->count() }} @endif </span></a></li>
                    <li><a href="#settings" data-toggle="tab"><i class="icon-cog3 position-left"></i> Settings</a></li>
                </ul>

               <!-- <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li><a href="#"><i class="icon-stack-text position-left"></i> Notes</a></li>
                        <li><a href="#"><i class="icon-collaboration position-left"></i> Friends</a></li>
                        <li><a href="#"><i class="icon-images3 position-left"></i> Photos</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-gear"></i> <span class="visible-xs-inline-block position-right"> Options</span> <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#"><i class="icon-image2"></i> Update cover</a></li>
                                <li><a href="#"><i class="icon-clippy"></i> Update info</a></li>
                                <li><a href="#"><i class="icon-make-group"></i> Manage sections</a></li>
                                <li class="divider"></li>
                                <li><a href="#"><i class="icon-three-bars"></i> Activity log</a></li>
                                <li><a href="#"><i class="icon-cog5"></i> Profile settings</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>-->
            </div>
        </div>
        <!-- /toolbar -->

    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content">

        <!-- User profile -->
        <div class="row">
            <div class="col-lg-9">
                <div class="tabbable">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="activity">

                            <!-- Timeline -->
                            <div class="timeline timeline-left content-group">
                                <div class="timeline-container">

                                    <!-- Sales stats -->
                                    <div class="timeline-row">
                                        <div class="timeline-icon">
                                            @if(isset(Auth::user()->photo))
                                            <a href="#"><img src="upload/photo/{{Auth::user()->photo}}" title=""></a>
                                            @else
                                            <a href="#"><img src="assets/images/profile.png" title=""></a>
                                            @endif

                                        </div>

                                        <div class="panel panel-flat timeline-content">
                                            <div class="panel-heading">
                                                <h6 class="panel-title">Daily statistics</h6>
                                                <div class="heading-elements">
                                                    <!--<span class="heading-text"><i class="icon-history position-left text-success"></i> Updated 3 hours ago</span>-->

                                                    <ul class="icons-list">

                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="panel-body">
                                                <div class="chart-container">
                                                    <div class="chart has-fixed-height" id="sales" style="height: 1200px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /sales stats -->


                                </div>
                            </div>
                            <!-- /timeline -->

                        </div>

                        <!----------------------------------------- Calendar ------------------------------------------>
                        <div class="tab-pane fade" id="schedule">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">My schedule</h6>
                                </div>

                                <div class="panel-body">
                                    <div class="schedule"></div>
                                </div>
                            </div>
                        </div>
                        <!---------------------------------------- /Calendar ------------------------------------------>

                        <!--------------------------------------- Profile info ----------------------------------------->

                        <div class="tab-pane fade" id="settings">

                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Profile information</h6>
                                </div>

                                <div class="panel-body">
                                    {{ Form::open(array('url' => 'editprofile', 'files' => true, 'method' => 'post', 'id' => 'editporfile')) }}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Username</label>
                                                    <input name="name" type="text" value="{{ Auth::user()->name }}" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Address</label>
                                                    <input name="address" type="text" value="{{ Auth::user()->address }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Email</label>
                                                    <input type="text" readonly="readonly" value="{{ Auth::user()->email }}" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Country</label>
                                                    <select class="select" name="country">
                                                        @foreach($country as $countrys)
                                                        <option value="{{ $countrys }}">{{ $countrys }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Phone</label>
                                                    <input name="phone" type="text" value="{{ Auth::user()->phone }}" class="form-control">

                                                </div>

                                                <div class="col-md-6">
                                                    <label>Upload profile image</label>
                                                    <input name="file" type="file" class="file-styled">
                                                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary" onclick="document.forms['editporfile'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                            <!-- /profile info -->


                            <!-- Account settings -->
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Account settings</h6>
                                </div>

                                <div class="panel-body">
                                    <form action="{{ url('changepassword') }}" method="POST" id="changepassword">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Username</label>
                                                    <input type="text" value="{{ Auth::user()->name }}" readonly="readonly" class="form-control">
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Current password</label>
                                                    <input type="password" value="{{ Auth::user()->password }}" readonly="readonly" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>New password</label>
                                                    <input type="password" placeholder="Enter new password" class="form-control" name="password">
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Retype new password</label>
                                                    <input type="password" placeholder="Repeat new password" class="form-control" name="confirmpassword">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary" onclick="document.forms['changepassword'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /account settings -->

                        </div>
                        <!-------------------------------------- /Profile info ---------------------------------------->
                    </div>
                </div>
            </div>

            <div class="col-lg-3">

                <!-- User thumbnail -->
                <div class="thumbnail">
                    <div class="thumb thumb-rounded thumb-slide">
                        @if(isset(Auth::user()->photo))
                        <a href="#"><img src="upload/photo/{{Auth::user()->photo}}" title=""></a>
                        @else
                        <a href="#"><img src="assets/images/profile.png" title=""></a>
                        @endif
                    </div>

                    <div class="caption text-center">
                        <h6 class="text-semibold no-margin">{{ Auth::user()->name }}</h6>
                        <h5 class="text-semibold no-margin"> Credit : {{Auth::user()->credit}} </h5>
                        <small class="display-block">@if(Auth::user()->type == 1) Administrator @else Reseller @endif</small>
                    </div>
                </div>
                <!-- /user thumbnail -->


            </div>
        </div>
        <!-- /user profile -->


        @include('..back-end.footer')

    </div>
    <!-- /content area -->

</div>
<!-- /main content -->
<script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>
<script>
       // Form components
       // ------------------------------

       // Select2 selects
       $('.select').select2({
           minimumResultsForSearch: Infinity
       });


       // Styled file input
       $(".file-styled").uniform({
           wrapperClass: 'bg-warning',
           fileButtonHtml: '<i class="icon-googleplus5"></i>'
       });


       // Styled checkboxes, radios
       $(".styled").uniform({
           radioClass: 'choice'
       });
       $(function() {
            // Charts
            // ------------------------------

            // Set paths
            require.config({
                paths: {
                    echarts: 'assets/js/plugins/visualization/echarts'
                }
            });


            // Configuration
            require(
                [
                    'echarts',
                    'echarts/theme/limitless',
                    'echarts/chart/line',   // load-on-demand, don't forget the Magic switch type.
                    'echarts/chart/bar'
                ],
                // Charts setup
                function (ec, limitless) {

                        <!-------------------------------------- Apply options ----------------------------------------->

                        var sales = ec.init(document.getElementById('sales'), limitless);

                        <!----------------------------------- Sales chart Data ----------------------------------------->
                        //
                        // Sales chart options
                        //

                        <?php
                        $noOfDays=cal_days_in_month(CAL_GREGORIAN,date("m"),date("Y"));
                        $firstDayCurrentMonth=date("Y-m")."-01";
                        $lastDayCurrentMonth=date('Y-m-t', strtotime($firstDayCurrentMonth));

                        ?>
                        sales_options = {

                            // Setup grid
                            grid: {
                                x: 45,
                                x2: 15,
                                y: 35,
                                y2: 25
                            },

                            // Add tooltip
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'shadow'
                                }
                            },

                            // Add legend
                            legend: {
                                data:['Charged Packages', 'Make Payment', 'Add Credit']
                            },

                            // Enable drag recalculate
                            calculable: true,

                            // Horizontal axis
                            xAxis: [{
                                type: 'value'
                            }],

                            // Vertical axis
                            yAxis: [{
                                type: 'category',
                                axisTick: {
                                    show: false
                                },
                                data: [
                                <?php
                                $counter=1;
                                for($i=$noOfDays;$i>=1;$i--)
                                {
                                    if($counter!=1){echo ",";}
                                    echo "'".$i.date("-m")."'";
                                    $counter++;
                                }
                                ?>
                                ]
                            }],

                            // Add series
                            series: [
                                {
                                    name: 'Charged Packages',
                                    type: 'bar',
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: true,
                                                position: 'inside'
                                            }
                                        }
                                    },
                                    data: [
                                    <?php
                                    $counter=1;
                                    for($i=$noOfDays;$i>=1;$i--)
                                    {
                                        if($counter!=1){echo ",";}$counter++;
                                        $dayValue=App\History::where('operation','reseller_charge_package')->where('reseller_id',Auth::user()->id)->where('add_date',date("Y-m-").$i)->sum('package_price');
                                        if(isset($dayValue)){echo $dayValue; unset($dayValue);}
                                        else{echo "0";}
                                    }
                                    ?>
                                    ]
                                },
                                {
                                    name: 'Add Credit',
                                    type: 'bar',
                                    stack: 'Total',
                                    barWidth: 5,
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: true
                                            }
                                        }
                                    },
                                    data: [
                                    <?php
                                    $counter=1;
                                    for($i=$noOfDays;$i>=1;$i--)
                                    {
                                        if($counter!=1){echo ",";}$counter++;
                                        $dayValue=App\History::where('operation','reseller_credit')->where('reseller_id',Auth::user()->id)->where('add_date',date("Y-m-").$i)->sum('details');
                                        if(isset($dayValue)){echo $dayValue; unset($dayValue);}
                                        else{echo "0";}
                                    }
                                    ?>
                                    ]
                                },
                                {
                                    name: 'Make Payment',
                                    type: 'bar',
                                    stack: 'Total',
                                    itemStyle: {
                                        normal: {
                                            label: {
                                                show: true,
                                                position: 'left'
                                            }
                                        }
                                    },
                                    data: [
                                        <?php
                                        $counter=1;
                                        for($i=$noOfDays;$i>=1;$i--)
                                        {
                                            if($counter!=1){echo ",";}$counter++;
                                            $dayValue=App\History::where('operation','reseller_payment')->where('reseller_id',Auth::user()->id)->where('add_date',date("Y-m-").$i)->sum('details');;
                                            if(isset($dayValue)){echo "-".$dayValue; unset($dayValue);}
                                            else{echo "0";}
                                        }
                                        ?>
                                    ]
                                }
                            ]
                        };
                        //
                        // Plans chart options
                        //

                        // Text label options
                        var labelRight = {normal: {color: '#FF7043', label: {position: 'right'}}};

                        <!-------------------------------------- Apply options ----------------------------------------->

                        //
                        // Apply options
                        //
                        sales.setOption(sales_options);

                        <!-------------------------------------- Resize chart ----------------------------------------->

                        //
                        // Resize chart
                        //

                        window.onresize = function () {
                            setTimeout(function (){
                                sales.resize();
                            }, 200);
                        }

                        <!------------------------------------ Resize in tabs ----------------------------------------->

                        // Resize in tabs
                        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                            sales.resize();
                        });

                        <!------------------------------------ Schedule Data ----------------------------------------->
                        // Schedule
                        //
                        <?php
                        $currency=App\Settings::where('type','currency')->value('value');
                        ?>
                        @if(Auth::user()->type == 2)
                            // Add events
                            <?php $reseller_events = App\History::where('reseller_id',Auth::user()->id)->get(); ?>
                            var eventsColors = [
                                @foreach($reseller_events as $events)
                                {
                                    @if($events->operation=="reseller_charge_package")
                                        title: 'Charge Package {{$events->package_price}}{{$currency}} for user {{App\Users::where('u_id',$events->u_id)->value('u_name')}}.',
                                        start: '{{ $events->add_date }} {{ $events->add_time }}',
                                        color: '#ffa800'
                                        ,desc: ''
                                    @endif

                                    @if($events->operation=="reseller_cards_package")
                                        <?php
                                        $cardPackageDetails=$events->details;
                                        $cardPackageDetailsExplode=explode(";",$cardPackageDetails);
                                        $PackageNoOfCards=$cardPackageDetailsExplode[1]-$cardPackageDetailsExplode[0];
                                        ?>
                                        title: 'Add Card Package :{{$PackageNoOfCards}} cards, From serial:{{$cardPackageDetailsExplode[0]}} To serial:{{$cardPackageDetailsExplode[1]}}.',
                                        start: '{{ $events->add_date }} {{ $events->add_time }}',
                                        color: '#ff00f0'
                                        ,desc: ''
                                    @endif

                                    @if($events->operation=="reseller_payment")
                                        title: 'Make a payment {{$events->details}}{{$currency}}',
                                        start: '{{ $events->add_date }} {{ $events->add_time }}',
                                        color: '#ff0000'
                                        ,desc: ''
                                    @endif

                                    @if($events->operation=="reseller_credit")
                                        title: 'Add Credit {{$events->details}}{{$currency}}',
                                        start: '{{ $events->add_date }} {{ $events->add_time }}',
                                        color: '#4bdb3c'
                                        ,desc: ''
                                    @endif

                                },
                                @endforeach
                            ];
                        @else
                            // Add events
                            <?php $admin_events = App\History::where('a_id',Auth::user()->id)->get(); ?>
                            var eventsColors = [
                                @foreach($admin_events as $events)
                                {
                                    title: 'Long Event',
                                    start: '{{ $events->add_date }}',
                                    color: '#26A69A'
                                },
                                @endforeach
                            ];
                        @endif

                        // Initialize calendar with options
                        $('.schedule').fullCalendar({
                            header: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'month,agendaWeek,agendaDay'
                            },
                            defaultDate: '{{ $todayDateTime->toDateString() }}',
                            editable: false,
                            events: eventsColors,
                            eventClick:  function(event, jsEvent, view) {
                                new PNotify({
                                    title: event.title,
                                    text: event.desc,
                                    icon: 'icon-cash4'
                                });
                            }
                        });


                        // Render in hidden elements
                        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                            $('.schedule').fullCalendar('render');
                        });

                        <!----------------------------------- /Schedule Data ----------------------------------------->
                }
            );
       });
</script>
@endsection