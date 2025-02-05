@extends('..back-end.layouts.master')
@section('title', 'Dashboard')
@section('content')
@section('js')
    <script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/ui/fullcalendar/fullcalendar.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>
    <script type="text/javascript" src="assets/js/charts/echarts/timeline_option.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>


@endsection
<!-- Page header -->
<?php 
    $dashboardType=App\Settings::where('type','dashboard_type')->where('state',Auth::user()->id)->first(); 
    if(count($dashboardType)==0){
        App\Settings::insert(['type' => 'dashboard_type', 'value' => 'internetManagement','state'=>Auth::user()->id]);
        $dashboardType=App\Settings::where('type','dashboard_type')->where('state',Auth::user()->id)->first(); 
    }
    if($dashboardType->value=="all" or !isset($dashboardType->value)){

        $system_counters=1;
        $online_rush_hour=1;
        $visitors_rush_hour=1;      
        $revenu_stream_statistics=1; 
    }

    if($dashboardType->value=="revenue_stream"){

        $revenu_stream_statistics=1; 
    } 
    
    // default to view the following statistics in all type
    $system_counters=1;
    $online_rush_hour=1;
    $visitors_rush_hour=1;
    $SystemEvents=1; 
    
?>
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                        </span><h4><i class="icon-paragraph-justify3 position-left"></i> <span class="text-semibold">Dashboard</span> -
                         @if(!isset($dashboardType))
                            All <i class="icon-home2"></i>
                         @elseif($dashboardType->value=="all")
                            All <i class="icon-home2"></i>
                         @elseif($dashboardType->value=="marketing")
                            Marketing <i class="icon-megaphone"></i>
                         @elseif($dashboardType->value=="internetManagement")
                            Internet Management <i class="icon-server"></i>
                         @elseif($dashboardType->value=="revenue_stream")
                            Revenue stream <i class="icon-coins"></i>
                         @elseif($dashboardType->value=="branchid")
                            Branch : {{App\Branches::where('id',$dashboardType->state)->value('name')}} <i class="icon-git-branch"></i>
                         @endif
                        </h4>
                    </a>
                    <div class="dropdown-menu dropdown-content width-350 center">
                        <ul class="media-list dropdown-content-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <a class="btn bg-teal-400 btn-block btn-float btn-float-lg" href="{{ url('dashboard_type?action=all') }}"><i class="icon-home2"></i> <span>All Statistics</span></a>
                                    @if(App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                                        <a class="btn bg-purple-300 btn-block btn-float btn-float-lg" href="{{ url('dashboard_type?action=marketing') }}"><i class="icon-megaphone"></i> <span>Marketing</span></a>
                                    @endif
                                </div>
                                
                                <div class="col-xs-6">
                                        <a class="btn bg-teal-800 btn-block btn-float btn-float-lg" href="{{ url('dashboard_type?action=internetManagement') }}"><i class="icon-server"></i> <span>Internet</span></a>
                                    @if(App\Settings::where('type', 'commercial_enable')->value('state') == 1)
                                        <a class="btn bg-warning-400 btn-block btn-float btn-float-lg" href="{{ url('dashboard_type?action=revenue_stream') }}"><i class="icon-coins"></i> <span>Revenue stream</span></a>
                                    @endif
                                    <?php /*<a class="btn bg-blue btn-block btn-float btn-float-lg" href="{{ url('dashboard_type/'.$branche_id) }}"><i class="icon-people"></i> <span>Users</span></a>*/ ?>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-xs-12">
                                    @foreach(App\Branches::where('state','1')->get() as $branch)
                                        <a class="btn bg-blue btn-block btn-float btn-float-lg" href="{{ url('dashboard_type?action=branch&id='.$branch->id) }}"><i class="icon-git-branch"></i> <span> {{ $branch->name }}</span></a>
                                    @endforeach
                                </div>
                            </div>
                        </ul>

                        <div class="dropdown-content-footer">
                        </div>
                    </div>
            <div class="heading-elements">
                <button type="button" class="btn btn-link daterange-ranges heading-btn text-semibold">
                    <i class="icon-calendar3 position-left"></i> <span></span> <b class="caret"></b>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- Dashboard content -->
    @if(Auth::user()->type == 1)
    
        <!-- system counters -->
        @if(isset($system_counters))
        <!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
        <!-- Progress counters -->
        <center>
        <div align="center" class="col-lg-12">

            <div class="col-md-2">
                <!-- users -->
                <div class="panel text-center">
                    <div class="panel-body">

                        <!-- Progress counter -->
                        <div class="content-group-sm svg-center position-relative" id="users-progress"></div>
                        <!-- /progress counter -->

                        <!-- Bars -->
                        <div id="users-progress-bars"></div>
                        <!-- /bars -->

                    </div>
                </div>
                <!-- /users -->
            </div>

            <div class="col-md-2">
                <!-- Productivity goal -->
                <div class="panel text-center">
                    <div class="panel-body">

                        <!-- Progress counter -->
                        <div class="content-group-sm svg-center position-relative" id="goal-progress"></div>
                        <!-- /progress counter -->

                        <!-- Bars -->
                        <div id="network_bar"></div>
                        <!-- /bars -->
                    </div>
                </div>
                <!-- /productivity goal -->
            </div>

            <div class="col-md-2">

                <!-- Available hours -->
                <div class="panel text-center">
                    <div class="panel-body">
                        <!-- Progress counter -->
                        <div class="content-group-sm svg-center position-relative" id="top-branches"></div>
                        <!-- /progress counter -->


                        <!-- Bars -->
                        <div id="top-branches-bars"></div>
                        <!-- /bars -->

                    </div>
                </div>
                <!-- /available hours -->

            </div>

            <div class="col-md-2">

                <!-- Available hours -->
                <div class="panel text-center">
                    <div class="panel-body">
                        <!-- Progress counter -->
                        <div class="content-group-sm svg-center position-relative" id="hours-available-progress"></div>
                        <!-- /progress counter -->

                        <!-- Bars -->
                        <div id="hours-available-bars"></div>
                        <!-- /bars -->

                    </div>
                </div>
                <!-- /available hours -->

            </div>


            <div class="col-md-2">

                <!-- Productivity goal -->
                <div class="panel text-center">
                    <div class="panel-body">

                        <!-- Progress counter -->
                        <div class="content-group-sm svg-center position-relative" id="online-progress"></div>
                        <!-- /progress counter -->

                        <!-- Bars -->
                        <div id="online-progress-bars"></div>
                        <!-- /bars -->

                    </div>
                </div>
                <!-- /productivity goal -->

            </div>

            <div class="col-md-2">
                <!-- Productivity goal -->
                <div class="panel text-center">
                    <div class="panel-body">

                        <!-- Progress counter -->
                        <div class="content-group-sm svg-center position-relative" id="packages-progress"></div>
                        <!-- /progress counter -->

                        <!-- Bars -->
                        <div id="packages-progress-bars"></div>
                        <!-- /bars -->

                    </div>
                </div>
                <!-- /productivity goal -->
            </div>
            
            <?php
            $getBranches = App\Branches::where('state','1')->get();
            $count = count($getBranches);
            //step 1:  get number between
            $upTo = intval($count / 6);
            $requiredNumber0 = $count - ($upTo * 6);
            $requiredNumber = ($upTo == 0) ? 1 : ($upTo * 6)+1;
            //step 2: convet to offset
            $offsetNumber = (12-($requiredNumber0*2))/2;
            foreach($getBranches as $key => $branch)
            {
                if($requiredNumber == ($key+1)){
                    ?>
                    <div align="center" class="col-md-2 col-md-offset-{{$offsetNumber}}">
                    <?php

                }else{
                    ?>
                    <div align="center" class="col-md-2 {{$requiredNumber}} {{$key}}">
                    <?php
                }
                ?>
                
                    <!-- Productivity goal -->
                    <div class="panel text-center">
                        <div class="panel-body">

                            <!-- Progress counter -->
                            <div class="content-group-sm svg-center position-relative" id="onlineUsersInBranches{{$branch->id}}"></div>
                            <!-- /progress counter -->

                            <!-- Bars -->
                            <div id="onlineUsersInBranches-bars{{$branch->id}}"></div>
                            <!-- /bars -->

                        </div>
                    </div>
                    <!-- /productivity goal -->
                </div>
                
                <?php
            }
            ?>
            
        </div>
        </center>
        <!-- /progress counters -->
        @endif
        <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!--Daily statistics-->
        @if(isset($revenu_stream_statistics) or isset($SystemEvents))
            @if(App\Settings::where('type', 'commercial_enable')->value('state') == 1 and isset($revenu_stream_statistics))
            <div class="col-lg-12">
                @include('..back-end.admin.activity')
            </div>
            @endif
            <div class="col-lg-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">System Events</h6>
                    </div>

                    <div class="panel-body">
                        <div class="schedule"></div>
                    </div>
                </div>
            </div>
        @endif
        <!--/Daily statistics-->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    @endif
</div>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    @if(Auth::user()->type == 1)
    <!-- daterange picker to enable admin to choose dates between -->
    <script>
        
         
        <?php
        $firstDayThisMonth = date("Y-m") . "-01";
        $lastDayThisMonth = date('m/t/Y', strtotime($firstDayThisMonth));
        ?>
        $('.daterange-ranges').daterangepicker(
            {
                startDate: '{{date('m/d/Y', strtotime($statisticsStartDate))}}',
                endDate: '{{date('m/d/Y', strtotime($statisticsEndDate))}}',
                minDate: '01/01/2016',
                maxDate: '{{$lastDayThisMonth}}',
                dateLimit: { days: 1095 },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                    'Whole period':['01/01/2016', moment()]
                },
                opens: 'left',
                applyClass: 'btn-small bg-slate-600 btn-block',
                cancelClass: 'btn-small btn-default btn-block',
                format: 'YYYY-MM-DD'
            },
            function(start, end) {
                $('.daterange-ranges span').html(start.format('MMMM D') + ' - ' + end.format('MMMM D'));
            }
        );

        $('.daterange-ranges').on('apply.daterangepicker', function(ev, picker) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var sartdate = picker.startDate.format('YYYY-MM-DD');
        var enddate = picker.endDate.format('YYYY-MM-DD');
        $.ajax({  
            'url':'statistics',
            'data':{_token: CSRF_TOKEN, sartdate:sartdate, enddate:enddate, admin:{{ Auth::user()->id }}},
            'type':'post',    
            success:function(data) {
                location.reload();
            },
            error:function(){
                swal("Cancelled", "Your imaginary file is safe :)", "error");

            }
        });
        });
        $('.daterange-ranges span').html( '{{ $statisticsType }}' );
    </script>
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <script>    
        // Geo chart
        // ------------------------------

        // Initialize chart
        google.load("visualization", "1", {packages:["geochart"]});
        google.setOnLoadCallback(drawRegionsMap);

        // Users Country
        function drawRegionsMap() {

            // Data
            var data = google.visualization.arrayToDataTable([
                ['Country', 'Users'],
                ['United States', {{ App\Users::where('u_country', 'USA')->count() }}],
                ['Egypt', {{ App\Users::where('u_country', 'Egypt')->count() }}]
            ]);


            // Options
            var options = {
                fontName: 'Roboto',
                height: 370,
                width: "100%",
                fontSize: 12,
                tooltip: {
                    textStyle: {
                        fontName: 'Roboto',
                        fontSize: 13
                    }
                }
            };


            // Draw chart
            var chart = new google.visualization.GeoChart($('#google-geo-region')[0]);
            chart.draw(data, options);
        }

    </script>
<!-- ---------------------------------------------------------------------------------------------- -->

<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->  
    <!-- system counters --> 
@if(isset($system_counters)) 
    <script>
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // users
        <?php
        $counterAllUsers=App\Users::count();if(!isset($counterAllUsers)){$counterAllUsers=0;}
        $limitUsers=App\Settings::where('type','limit_users')->value('value');if(!isset($limitUsers)){$limitUsers=0;}
        if(isset($counterAllUsers) and $counterAllUsers!=0 and isset($limitUsers) and $limitUsers!=0){$percentageLimitUsers=round(($counterAllUsers/$limitUsers)*100,1);if($percentageLimitUsers>100){$percentageLimitUsers=100;}}
        elseif(isset($limitUsers) and $limitUsers==0){$percentageLimitUsers="0";}
        else{$percentageLimitUsers=0;}
        ?>
        progressCounter('#users-progress', 38, 2, "#4caf50",{{$percentageLimitUsers}}, "icon-users text-green-400", 'Users', '@if(isset($percentageLimitUsers) and $percentageLimitUsers=="0")  {{$counterAllUsers}} of Unlimited @else {{$counterAllUsers}} of {{$limitUsers}} @endif ')
        // networks
        <?php
        $counterAllNetworks=App\Network::count();if(!isset($counterAllNetworks)){$counterAllNetworks=0;}
        $limitNetworks=App\Settings::where('type','limit_networks')->value('value');if(!isset($limitNetworks)){$limitNetworks=0;}
        if(isset($counterAllNetworks) and $counterAllNetworks!=0 and isset($limitNetworks) and $limitNetworks!=0){$percentageLimitNetworks=round(($counterAllNetworks/$limitNetworks)*100,1);if($percentageLimitNetworks>100){$percentageLimitNetworks=100;}}
        elseif(isset($limitNetworks) and $limitNetworks==0){$percentageLimitNetworks="0";}
        else{$percentageLimitNetworks=0;}
        ?>
        progressCounter('#goal-progress', 38, 2, "#5C6BC0", {{$percentageLimitNetworks}} , "icon-tree6 text-indigo-400", 'Networks', '@if(isset($percentageLimitNetworks) and $percentageLimitNetworks=="0")  {{$counterAllNetworks}} of Unlimited @else {{$counterAllNetworks}} of {{$limitNetworks}} @endif ')
        // branches
        <?php
        $counterAllBranches=App\Branches::count();if(!isset($counterAllBranches)){$counterAllBranches=0;}
        $limitBranches=App\Settings::where('type','limit_branches')->value('value');if(!isset($limitBranches)){$limitBranches=0;}
        if(isset($counterAllBranches) and $counterAllBranches!=0 and isset($limitBranches) and $limitBranches!=0){$percentageLimitBranches=round(($counterAllBranches/$limitBranches)*100,1); if($percentageLimitBranches>100){$percentageLimitBranches=100;}}
        elseif(isset($limitBranches) and $limitBranches==0){$percentageLimitBranches="0";}
        else{$percentageLimitBranches=0;}
        ?>
        progressCounter('#top-branches', 38, 2, "#00c6ff",{{$percentageLimitBranches}}, "icon-server text-indigo-400", 'Branches', '@if(isset($percentageLimitBranches) and $percentageLimitBranches=="0")  {{$counterAllBranches}} of Unlimited @else {{$counterAllBranches}} of {{$limitBranches}} @endif ')
    
        // Groups
        <?php
        $counterAllGroups=App\Groups::count();if(!isset($counterAllGroups)){$counterAllGroups=0;}
        $limitGroups=App\Settings::where('type','limit_groups')->value('value');if(!isset($limitGroups)){$limitGroups=0;}
        if(isset($counterAllGroups) and $counterAllGroups!=0 and isset($limitGroups) and $limitGroups!=0){$percentageLimitGroups=round(($counterAllGroups/$limitGroups)*100,1); if($percentageLimitGroups>100){$percentageLimitGroups=100;}}
        elseif(isset($limitGroups) and $limitGroups==0){$percentageLimitGroups="0";}
        else{$percentageLimitGroups=0;}
        ?>
        progressCounter('#hours-available-progress', 38, 2, "#F06292", {{ $percentageLimitGroups }}, "icon-user text-pink-400", 'Groups', '@if(isset($percentageLimitGroups) and $percentageLimitGroups=="0")  {{$counterAllGroups}} of Unlimited @else {{$counterAllGroups}} of {{$limitGroups}} @endif')

        // Cards
        <?php
        $counterAllCards=App\Cards::count();if(!isset($counterAllCards)){$counterAllCards=0;}
        $limitCards=App\Settings::where('type','limit_cards')->value('value');if(!isset($limitCards)){$limitCards=0;}
        if(isset($counterAllCards) and $counterAllCards!=0 and isset($limitCards) and $limitCards!=0){$percentageLimitCards=round(($counterAllCards/$limitCards)*100,1); if($percentageLimitCards>100){$percentageLimitCards=100;}}
        elseif(isset($limitCards) and $limitCards==0){$percentageLimitCards="0";}
        else{$percentageLimitCards=0;}
        ?>
        progressCounter('#online-progress', 38, 2, "#ff001e", {{ $percentageLimitCards }}, "icon-cash3 text-red-400", 'Cards', '@if(isset($percentageLimitCards) and $percentageLimitCards=="0")  {{$counterAllCards}} of Unlimited @else {{$counterAllCards}} of {{$limitCards}} @endif')

        // Packages
        <?php
        $counterAllPackages=App\Models\Packages::count();if(!isset($counterAllPackages)){$counterAllPackages=0;}
        $limitPackages=App\Settings::where('type','limit_packages')->value('value');if(!isset($limitPackages)){$limitPackages=0;}
        if(isset($counterAllPackages) and $counterAllPackages!=0 and isset($limitPackages) and $limitPackages!=0){$percentageLimitPackages=round(($counterAllPackages/$limitPackages)*100,1); if($percentageLimitPackages>100){$percentageLimitPackages=100;}}
        elseif(isset($limitPackages) and $limitPackages==0){$percentageLimitPackages="0";}
        else{$percentageLimitPackages=0;}
        ?>
        progressCounter('#packages-progress', 38, 2, "#4caf50", {{ $percentageLimitPackages }}, "icon-cash4 text-green-400", 'Packages', '@if(isset($percentageLimitPackages) and $percentageLimitPackages=="0")  {{$counterAllPackages}} of Unlimited @else {{$counterAllPackages}} of {{$limitPackages}} @endif')

        // Online users in branches
        <?php
        foreach(App\Branches::where('state','1')->get() as $branch)
        {
            $counterAllRegUsers=App\Users::where('branch_id',$branch->id)->count();if(!isset($counterAllRegUsers)){$counterAllRegUsers=0;}
            $onlineUsersNow=App\Models\RadacctActiveUsers::where('branch_id',$branch->id)->count();if(!isset($onlineUsersNow)){$onlineUsersNow=0;}
            if(isset($counterAllRegUsers) and $counterAllRegUsers!=0 and isset($onlineUsersNow) and $onlineUsersNow!=0){$percentageOnlineUsersInBranches=round(($onlineUsersNow/$counterAllRegUsers)*100,1); if($percentageOnlineUsersInBranches>100){$percentageOnlineUsersInBranches=100;}}
            elseif(isset($counterAllRegUsers) and $counterAllRegUsers==0){$percentageLimitBranches="0";}
            else{$percentageOnlineUsersInBranches=0;}
            ?>
            progressCounter('#onlineUsersInBranches{{$branch->id}}', 38, 2, "#<?php echo rand(111111,999999);?>",{{$percentageOnlineUsersInBranches}}, "icon-users text-indigo-400", '{{$branch->name}} branch', '{{$onlineUsersNow}} online of {{$counterAllRegUsers}} users')
            <?php  
        }
        ?>
        ///////////////////////////
        // Chart setup
        function progressCounter(element, radius, border, color, end, iconClass, textTitle, textAverage) {


                        // Basic setup
                        // ------------------------------

                        // Main variables
                        var d3Container = d3.select(element),
                            startPercent = 0,
                            iconSize = 32,
                            endPercent = end,
                            twoPi = Math.PI * 2,
                            formatPercent = d3.format('.0%'),
                            boxSize = radius * 2;

                        // Values count
                        //var count = Math.abs((endPercent - startPercent) / 0.01);
                        var count = endPercent;

                        // Values step
                        var step = endPercent < startPercent ? -0.01 : 0.01;



                        // Create chart
                        // ------------------------------

                        // Add SVG element
                        var container = d3Container.append('svg');

                        // Add SVG group
                        var svg = container
                            .attr('width', boxSize)
                            .attr('height', boxSize)
                            .append('g')
                                .attr('transform', 'translate(' + (boxSize / 2) + ',' + (boxSize / 2) + ')');



                        // Construct chart layout
                        // ------------------------------

                        // Arc
                        var arc = d3.svg.arc()
                            .startAngle(0)
                            .innerRadius(radius)
                            .outerRadius(radius - border);



                        //
                        // Append chart elements
                        //

                        // Paths
                        // ------------------------------

                        // Background path
                        svg.append('path')
                            .attr('class', 'd3-progress-background')
                            .attr('d', arc.endAngle(twoPi))
                            .style('fill', '#eee');

                        // Foreground path
                        var foreground = svg.append('path')
                            .attr('class', 'd3-progress-foreground')
                            .attr('filter', 'url(#blur)')
                            .style('fill', color)
                            .style('stroke', color);

                        // Front path
                        var front = svg.append('path')
                            .attr('class', 'd3-progress-front')
                            .style('fill', color)
                            .style('fill-opacity', 1);



                        // Text
                        // ------------------------------

                        // Percentage text value
                        var numberText = d3.select(element)
                            .append('h2')
                                .attr('class', 'mt-15 mb-5')

                        // Icon
                        d3.select(element)
                            .append("i")
                                .attr("class", iconClass + " counter-icon")
                                .attr('style', 'top: ' + ((boxSize - iconSize) / 2) + 'px');

                        // Title
                        d3.select(element)
                            .append('div')
                                .text(textTitle);

                        // Subtitle
                        d3.select(element)
                            .append('div')
                                .attr('class', 'text-size-small text-muted')
                                .text(textAverage);



                        // Animation
                        // ------------------------------

                        // Animate path
                        function updateProgress(progress) {
                            foreground.attr('d', arc.endAngle(twoPi * progress));
                            front.attr('d', arc.endAngle(twoPi * progress));
                            numberText.text(formatPercent(progress));
                        }

                        // Animate text
                        var progress = startPercent;
                        (function loops() {
                            updateProgress(progress);
                            if (count > 0) {
                                count--;
                                progress += step;
                                setTimeout(loops, 10);
                            }
                        })();
                    }


        // Initialize charts

        generateBarChart("#hours-available-bars", 24, 40, true, "elastic", 1200, 50, "#EC407A", "");
        //generateBarChart("#network_bar", {{ App\Network::count() }}, 40, true, "elastic", 1200, 50, "#5C6BC0", "");
        generateBarChart("#network_bar", 24, 40, true, "elastic", 1200, 50, "#5C6BC0", "");
        generateBarChart("#top-branches-bars", 24, 40, true, "elastic", 1200, 50, "#00c6ff", "");
        generateBarChart("#packages-progress-bars", 24, 40, true, "elastic", 1200, 50, "#4caf50", "");
        generateBarChart("#online-progress-bars", 24, 40, true, "elastic", 1200, 50, "#ff001e", "");
        generateBarChart("#users-progress-bars", 24, 40, true, "elastic", 1200, 50, "#4caf50", "");
        
        <?php
        foreach(App\Branches::where('state','1')->get() as $branch)
        {
            ?>
            generateBarChart("#onlineUsersInBranches-bars{{$branch->id}}", 24, 40, true, "elastic", 1200, 50, "#<?php echo rand(111111,999999);?>", "");
            <?php
        }
        ?>

        <!--     below design   -->
        // Chart setup
        function generateBarChart(element, barQty, height, animate, easing, duration, delay, color, tooltip) {


            // Basic setup
            // ------------------------------

            // Add data set
            var bardata = [];
            for (var i=0; i < barQty; i++) {
                bardata.push(Math.round(Math.random()*10) + 10)
            }

            // Main variables
            var d3Container = d3.select(element),
                width = d3Container.node().getBoundingClientRect().width;



            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.scale.ordinal()
                .rangeBands([0, width], 0.3)

            // Vertical
            var y = d3.scale.linear()
                .range([0, height]);



            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.range(0, bardata.length))

            // Vertical
            y.domain([0, d3.max(bardata)])



            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                .attr('width', width)
                .attr('height', height)
                .append('g');



            //
            // Append chart elements
            //

            // Bars
            var bars = svg.selectAll('rect')
                .data(bardata)
                .enter()
                .append('rect')
                    .attr('class', 'd3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    })
                    .style('fill', color);



            // Tooltip
            // ------------------------------

            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0]);

            // Show and hide
            if(tooltip == "hours" || tooltip == "goal" || tooltip == "members") {
                bars.call(tip)
                    .on('mouseover', tip.show)
                    .on('mouseout', tip.hide);
            }

            // Daily meetings tooltip content
            if(tooltip == "hours") {
                tip.html(function (d, i) {
                    return "<div class='text-center'>" +
                            "<h6 class='no-margin'>" + d + "</h6>" +
                            "<span class='text-size-small'>Visitor</span>" +
                            "<div class='text-size-small'>" + i + ":00" + "</div>" +
                        "</div>"
                });
            }
            <?php $branches = App\Branches::get(); ?>
            // Statements tooltip content
            @foreach($branches as $branche)
            if(tooltip == "goal") {

               tip.html(function (d, i) {
                    return "<div class='text-center'>" +
                            "<h6 class='no-margin'>" + {{ $branche->network_id }} + "</h6>" +
                            "<span class='text-size-small'>Branches</span>" +
                            "<div class='text-size-small'>" + {{ App\Users::where('network_id', '1')->count() }} + " Users" + "</div>" +
                        "</div>"

                });

            }
            @endforeach()

            // Online members tooltip content
            if(tooltip == "members") {
                tip.html(function (d, i) {
                    return "<div class='text-center'>" +
                            "<h6 class='no-margin'>" + d + "0" + "</h6>" +
                            "<span class='text-size-small'>members</span>" +
                            "<div class='text-size-small'>" + i + ":00" + "</div>" +
                        "</div>"
                });
            }



            // Bar loading animation
            // ------------------------------

            // Choose between animated or static
            if(animate) {
                withAnimation();
            } else {
                withoutAnimation();
            }

            // Animate on load
            function withAnimation() {
                bars
                    .attr('height', 0)
                    .attr('y', height)
                    .transition()
                        .attr('height', function(d) {
                            return y(d);
                        })
                        .attr('y', function(d) {
                            return height - y(d);
                        })
                        .delay(function(d, i) {
                            return i * delay;
                        })
                        .duration(duration)
                        .ease(easing);
            }

            // Load without animateion
            function withoutAnimation() {
                bars
                    .attr('height', function(d) {
                        return y(d);
                    })
                    .attr('y', function(d) {
                        return height - y(d);
                    })
            }



            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', barsResize);

            // Call function on sidebar width change
            $('.sidebar-control').on('click', barsResize);

            // Resize function
            //
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to
            // be updated on window resize
            function barsResize() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width);

                // Width of appended group
                svg.attr("width", width);

                // Horizontal range
                x.rangeBands([0, width], 0.3);


                // Chart elements
                // -------------------------

                // Bars
                svg.selectAll('.d3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    });
            }
        }


        // Initialize chart
        sparkline("#new-visitors", "line", 30, 35, "basis", 750, 2000, "#26A69A"); // Vists Rush hour 
        sparkline("#total-online", "line", 30, 35, "basis", 750, 2000, "#5C6BC0"); // Rush hour online 
        sparkline("#server-load", "area", 30, 50, "basis", 750, 2000, "rgba(255,255,255,0.5)");

        <!-- create text on below design in system counters -->

        // Chart setup
        function sparkline(element, chartType, qty, height, interpolation, duration, interval, color) {


            // Basic setup
            // ------------------------------

            // Define main variables
            /*var d3Container = d3.select(element),
                margin = {top: 0, right: 0, bottom: 0, left: 0},
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
                height = height - margin.top - margin.bottom;*/


            // Generate random data (for demo only)
            var data = [];
            for (var i=0; i < qty; i++) {
                data.push(Math.floor(Math.random() * qty) + 5)
            }



            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.scale.linear().range([0, width]);

            // Vertical
            var y = d3.scale.linear().range([height - 5, 5]);



            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain([1, qty - 3])

            // Vertical
            y.domain([0, qty])



            // Construct chart layout
            // ------------------------------

            // Line
            var line = d3.svg.line()
                .interpolate(interpolation)
                .x(function(d, i) { return x(i); })
                .y(function(d, i) { return y(d); });

            // Area
            var area = d3.svg.area()
                .interpolate(interpolation)
                .x(function(d,i) {
                    return x(i);
                })
                .y0(height)
                .y1(function(d) {
                    return y(d);
                });



            // Create SVG
            // ------------------------------

            // Container
            var container = d3Container.append('svg');

            // SVG element
            var svg = container
                .attr('width', width + margin.left + margin.right)
                .attr('height', height + margin.top + margin.bottom)
                .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");



            // Add mask for animation
            // ------------------------------

            // Add clip path
            var clip = svg.append("defs")
                .append("clipPath")
                .attr('id', function(d, i) { return "load-clip-" + element.substring(1) })

            // Add clip shape
            var clips = clip.append("rect")
                .attr('class', 'load-clip')
                .attr("width", 0)
                .attr("height", height);

            // Animate mask
            clips
                .transition()
                    .duration(1000)
                    .ease('linear')
                    .attr("width", width);



            //
            // Append chart elements
            //

            // Main path
            var path = svg.append("g")
                .attr("clip-path", function(d, i) { return "url(#load-clip-" + element.substring(1) + ")"})
                .append("path")
                    .datum(data)
                    .attr("transform", "translate(" + x(0) + ",0)");

            // Add path based on chart type
            if(chartType == "area") {
                path.attr("d", area).attr('class', 'd3-area').style("fill", color); // area
            }
            else {
                path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color); // line
            }

            // Animate path
            path
                .style('opacity', 0)
                .transition()
                    .duration(750)
                    .style('opacity', 1);



            // Set update interval. For demo only
            // ------------------------------

            setInterval(function() {

                // push a new data point onto the back
                data.push(Math.floor(Math.random() * qty) + 5);

                // pop the old data point off the front
                data.shift();

                update();

            }, interval);



            // Update random data. For demo only
            // ------------------------------

            function update() {

                // Redraw the path and slide it to the left
                path
                    .attr("transform", null)
                    .transition()
                        .duration(duration)
                        .ease("linear")
                        .attr("transform", "translate(" + x(0) + ",0)");

                // Update path type
                if(chartType == "area") {
                    path.attr("d", area).attr('class', 'd3-area').style("fill", color)
                }
                else {
                    path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color);
                }
            }



            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', resizeSparklines);

            // Call function on sidebar width change
            $('.sidebar-control').on('click', resizeSparklines);

            // Resize function
            //
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to
            // be updated on window resize
            function resizeSparklines() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width + margin.left + margin.right);

                // Width of appended group
                svg.attr("width", width + margin.left + margin.right);

                // Horizontal range
                x.range([0, width]);


                // Chart elements
                // -------------------------

                // Clip mask
                clips.attr("width", width);

                // Line
                svg.select(".d3-line").attr("d", line);

                // Area
                svg.select(".d3-area").attr("d", area);
            }
        }

    </script>
@endif
    <!-- /system counters -->
    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
    <script>
        // Reseller Javascript
        // Schedule
       // ------------------------------
       <?php $todayDateTime = Carbon\Carbon::now(); ?>
       <?php
       $currency=App\Settings::where('type','currency')->value('value');
       ?>
       @if(Auth::user()->type == 2)//Reseller
           // Add events
           <?php $reseller_events = App\History::where('reseller_id',Auth::user()->id)->get(); ?>
           var eventsColors = [
               @foreach($reseller_events as $events)
               {
                   @if($events->operation=="reseller_charge_package")
                       title: 'Charged Package {{$events->package_price}}{{$currency}} for user {{App\Users::where('u_id',$events->u_id)->value('u_name')}}.',
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

       @elseif(Auth::user()->type == 1)// Administrator

             var eventsColors = [
                // Reseller
                 <?php $reseller_events = App\History::whereNotNull('reseller_id')->get(); ?>
                 @foreach($reseller_events as $events)
                 {
                     @if($events->operation=="reseller_charge_package")
                         title: 'Reseller: {{App\Admins::where('id',$events->reseller_id)->value('name')}}, has charged Package of {{$events->package_price}}{{$currency}} for user {{App\Users::where('u_id',$events->u_id)->value('u_name')}}.',
                         start: '{{ $events->add_date }} {{ $events->add_time }}',
                         color: '#ffa800'
                         ,desc: 'At {{ $events->add_date }} {{ $events->add_time }}'
                     @endif

                     @if($events->operation=="reseller_cards_package")
                         <?php
                         $cardPackageDetails=$events->details;
                         $cardPackageDetailsExplode=explode(";",$cardPackageDetails);
                         $PackageNoOfCards=$cardPackageDetailsExplode[1]-$cardPackageDetailsExplode[0];
                         ?>
                         title: 'Add Card Package for reseller: {{App\Admins::where('id',$events->reseller_id)->value('name')}}, no of :{{$PackageNoOfCards}} cards, From serial:{{$cardPackageDetailsExplode[0]}} To serial:{{$cardPackageDetailsExplode[1]}}.',
                         start: '{{ $events->add_date }} {{ $events->add_time }}',
                         color: '#ff00f0'
                         ,desc: 'At {{ $events->add_date }} {{ $events->add_time }}'
                     @endif

                     @if($events->operation=="reseller_payment")
                         title: 'Make payment for Reseller: {{App\Admins::where('id',$events->reseller_id)->value('name')}}, Amount of {{$events->details}}{{$currency}}',
                         start: '{{ $events->add_date }} {{ $events->add_time }}',
                         color: '#ff0000'
                         ,desc: 'At {{ $events->add_date }} {{ $events->add_time }}'
                     @endif

                     @if($events->operation=="reseller_credit")
                         title: 'Add Credit for Reseller: {{App\Admins::where('id',$events->reseller_id)->value('name')}}, Amount of {{$events->details}}{{$currency}}',
                         start: '{{ $events->add_date }} {{ $events->add_time }}',
                         color: '#4bdb3c'
                         ,desc: 'At {{ $events->add_date }} {{ $events->add_time }}'
                     @endif

                 },
                 @endforeach

                 // user_charge_package
                 <?php $user_charge_package = App\History::where('operation','user_charge_package')->get(); ?>
                 @foreach($user_charge_package as $user_package)
                 {
                     title: '{{App\Users::where('u_id',$user_package->u_id)->value('u_name')}}, has charged Package of {{$user_package->package_price}}{{$currency}}.',
                     start: '{{ $user_package->add_date }} {{ $user_package->add_time }}',
                     color: '#0600ff'
                     ,desc: 'At {{ $user_package->add_date }} {{ $user_package->add_time }}'
                 },
                 @endforeach

                // Generate cards
                <?php $user_generate_cards = App\History::where('operation','Generate cards')->get(); ?>
                @foreach($user_generate_cards as $generate_cards)
                {
                    <?php  $generate_cards_splited=explode(';',$generate_cards->details); ?>
                    title: 'Generate {{$generate_cards_splited[2]}} cards, valued at {{$generate_cards_splited[3]}}{{$currency}}, from serial {{$generate_cards_splited[0]}} to serial {{$generate_cards_splited[1]}}.',
                    start: '{{ $generate_cards->add_date }} {{ $generate_cards->add_time }}',
                    color: '#00ddda'
                    ,desc: 'At {{ $generate_cards->add_date }} {{ $generate_cards->add_time }}'
                },
                @endforeach
                // Charged card ?
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_connection_type' ])->get() as $value)
                 {
                    title: 'Connection type change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#ede66a',
                    desc: 'At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_backup_connection_type' ])->get() as $value)
                 {
                    title: 'Backup connection type change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#edc56a',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_adsl_user' ])->get() as $value)
                 {
                    title: 'ADSL username change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#efaa0b',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_adsl_pass' ])->get() as $value)
                 {
                    title: 'ADSL password change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#ea700b',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_wireless_name' ])->get() as $value)
                 {
                    title: 'Wireless username change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#ef6732',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_wireless_pass' ])->get() as $value)
                 {
                    title: 'Wireless password change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#ef3232',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_private_wireless_state' ])->get() as $value)
                 {
                    title: 'Private wireless state change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#8e1c1c',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_private_wireless_name' ])->get() as $value)
                 {
                    title: 'Private wireless name change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#ef8d8d',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_private_wireless_pass' ])->get() as $value)
                 {
                    title: 'Private wireless password change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#baed8b',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_private_wireless_ip' ])->get() as $value)
                 {
                    title: 'Private wireless ip change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#91cc5b',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_hacking_protection' ])->get() as $value)
                 {
                    title: 'Hacing protection change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#509114',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_load_balance_state' ])->get() as $value)
                 {
                    title: 'load balance state change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#148a91',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_load_balance_type' ])->get() as $value)
                 {
                    title: 'load balance type change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#0f5c60',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_load_balance_equaled_state' ])->get() as $value)
                 {
                    title: 'load balance equaled state change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#058a91',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_load_balance_lines_count' ])->get() as $value)
                 {
                    title: 'load balance lines count change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#1572af',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_username' ])->get() as $value)
                 {
                    title: 'Username change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#299ae5',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_password' ])->get() as $value)
                 {
                    title: 'Password change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#0b4fc4',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_state' ])->get() as $value)
                 {
                    title: 'State change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#4d5bc1',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_adult_state' ])->get() as $value)
                 {
                    title: 'Adult state change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#9e40dd',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

                 },   
                @endforeach
                @foreach(App\History::where(['type1' => 'branches_changes', 'operation' => 'change_advanced_script_state' ])->get() as $value)
                 {
                    title: 'Advanced script state change',
                    start: '{{ $value->add_date }} {{ $value->add_time }}',
                    color: '#9e40dd',
                    desc: ' At {{ $value->add_date }} {{ $value->add_time }}'

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
    </script>
    <!-- ------------------------------------------------------------------------------------------------------------- -->
    @else
    @endif
    @section('css')

    @endsection
@endsection
