@if(isset($months['notFoundAnyData']) && $months['notFoundAnyData']==1)
<div class="alert alert-warning alert-styled-right">

    <span class="text-semibold">Sorry!</span> User didn't logged in yet.
</div>

@else

<!-- Content area -->
<div class="content">
    <!-- Timeline -->
    <div class="timeline timeline-left">
        <div class="">
            <!-- Date stamp -->
            @if(isset($user_data->u_name))
                <div class="timeline-date text-muted">
                        {{ $user_data->u_name }}
                </div>
                <div class="timeline-date">
                    <strong>Mobile: </strong> {{$user_data->u_phone}}
                    &nbsp | &nbsp <strong>Total Visits:</strong> @if($visits == 1 or $visits == 0) {{$visits}} day @else {{$visits}} days @endif
                    &nbsp | &nbsp <strong>Total Consumption:</strong> @if($totalQuotaConsumptionAllMonths > 1073741824) {{ round($totalQuotaConsumptionAllMonths/1024/1024/1024,1) }} GB @else {{ round($totalQuotaConsumptionAllMonths/1024/1024,1) }} MB @endif
                    &nbsp | &nbsp <strong>Mac:</strong> {{$user_data->u_mac}}
                </div>
            @endif
            <!-- /date stamp -->

            <!-- Invoices -->
            <div class="timeline-row">
                <div class="timeline-icon">
                    <div class="bg-primary-400">
                        <i class="icon-pulse2"></i>
                    </div>
                </div>

                <div class="panel panel-flat timeline-content">
                    <div class="panel-heading">
                        <h6 class="panel-title text-size-small">Login history</h6>
                    </div> 
                    <div class="panel-body">

                        @foreach($months as $currentMonth)
                            <?php
                                $firstDayInThisMonth = $currentMonth['monthname']."-01";
                                $lastDayInThisMonth= date("Y-m-t", strtotime($firstDayInThisMonth));

                                /*$totalMonthUpload= App\Models\UsersRadacct::where('u_id', $u_id)->whereBetween('dates',[$firstDayInThisMonth, $lastDayInThisMonth])->sum('acctinputoctets');
                                $totalMonthDownload= App\Models\UsersRadacct::where('u_id', $u_id)->whereBetween('dates',[$firstDayInThisMonth, $lastDayInThisMonth])->sum('acctoutputoctets');
                                $totalMonthTotal = $totalMonthUpload + $totalMonthDownload;
                                $acctsessiontime= App\Models\UsersRadacct::where('u_id', $u_id)->whereBetween('dates',[$firstDayInThisMonth, $lastDayInThisMonth])->sum('acctsessiontime');
                                $counterDays = App\Models\UsersRadacct::where('u_id', $u_id)->whereBetween('dates',[$firstDayInThisMonth, $lastDayInThisMonth])->count();*/
                            ?>
                            <div class="panel-group panel-group-control panel-group-control-right content-group-lg col-lg-12" id="accordion-control-right">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">
                                            <a  data-toggle="collapse" class="collapsed " data-parent="#accordion-control-right" href="#accordion-control-right-group-{{ $currentMonth['monthname'] }}" aria-expanded="true" class=""><i class="icon-calendar52 position-left"></i> {{ $currentMonth['monthname'] }} <i class="icon-alarm position-left"></i>Total time @if( $currentMonth['sessions'] > 86400) {{ gmdate("d",$currentMonth['sessions'])-1 }}d {{ gmdate("H:i:s", $currentMonth['sessions']) }} @else {{ gmdate("H:i:s",$currentMonth['sessions']) }} @endif  <i class="icon-alarm-check position-left"></i> Total days {{ $currentMonth['countDays'] }} <i class="icon-cloud position-left"></i>  Total Usage @if($currentMonth['total'] > 1073741824) {{ round($currentMonth['total']/1024/1024/1024,1) }} GB @else {{ round($currentMonth['total']/1024/1024,1) }} MB @endif</a>

                                        </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i> <span class="caret"></span></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a href="{{ url('export_month/'.$u_id.'-'.$currentMonth['monthname'])  }}"><i class="icon-file-excel"></i> Export report</a></li>
                                                        <li><a href="{{ url('export_month_log/'.$u_id.'-'.$currentMonth['monthname'])  }}"><i class="icon-list-unordered"></i> Export visited websites</a></li>
                                                        <li><a href="#" onclick="_destination_month_logs({{ $u_id }}, '{{ $currentMonth['monthname'] }}',this)"><i class="icon-pie5"></i> Browse visited websites</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="accordion-control-right-group-{{ $currentMonth['monthname'] }}" class="panel-collapse collapse" aria-expanded="true">
                                        <div class="panel-body">
                                            @foreach(App\Models\UsersRadacct::where('u_id', $u_id)->where('month', $currentMonth['monthname'])->orderBy('radacctid', 'desc')->get() as $d) <!-- Days -->
                                                <div class="panel-group panel-group-control panel-group-control-right content-group-lg">
                                                <?php
                                                        //$currDay=explode("-",$d->dates)[0]."-".explode("-",$d->dates)[1];
                                                        $totalDayUpload= App\Models\UsersRadacct::where('u_id', $u_id)->where('dates',$d->dates)->sum('acctinputoctets');
                                                        $totalDayDownload= App\Models\UsersRadacct::where('u_id', $u_id)->where('dates',$d->dates)->sum('acctoutputoctets');
                                                        $totalDayUsage = $totalDayUpload + $totalDayDownload;
                                                    ?>
                                                
                                                    <div class="panel panel-white">
                                                        <div class="panel-heading"> 
                                                            <h6 class="panel-title">
                                                                <a data-toggle="collapse" class="collapsed" data-parent="#collapsible-control-right" href="#collapsible-control-right-{{ $d->dates }}"><i class="icon-sun3 position-left"></i> Date {{ $d->dates }} <i class="icon-alarm-check position-left"></i> Total time @if($d->acctsessiontime > 3600) {{ gmdate("H:i:s",$d->acctsessiontime)  }} @else {{ gmdate("H:i:s",$d->acctsessiontime) }} @endif<i class="icon-list-unordered position-left"></i> Sessions {{ $d->countseccions }} <i class="icon-cloud position-left"></i> Usage @if($totalDayUsage > 1073741824) {{ round($totalDayUsage/1024/1024/1024,1) }} GB @else {{ round($totalDayUsage/1024/1024,1) }} MB @endif </a>
                                                            </h6>
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li class="dropdown">
                                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i> <span class="caret"></span></a>
                                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                                            <li><a href="{{ url('export_day/'.$u_id.'-'.$d->dates)  }}"><i class="icon-file-excel"></i> Export report</a></li>
                                                                            <li><a href="{{ url('export_day_log/'.$u_id.'-'.$d->dates)  }}"><i class="icon-list-unordered"></i> Export visited websites</a></li>
                                                                            <li><a href="#" onclick="_destination_day_logs({{ $u_id }}, '{{ $d->dates }}',this)"><i class="icon-pie5"></i> Browse visited websites</a></li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div id="collapsible-control-right-{{ $d->dates }}" class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                          
                                                                <table class="table">
                                                                    <tbody>
                                                                            <tr class="active border-double">
                                                                            <td colspan="1"></td>
                                                                            <td colspan="1"><span class="text-danger"><i class="icon-cloud-download position-left"></i>{{ round($d->acctoutputoctets /1024/1024 , 1)  }} MB</td>
                                                                            <td colspan="1"><span class="text-success-600"><i class="icon-cloud-upload position-left"></i> {{ round($d->acctinputoctets /1024/1024 ,1) }} MB</td>
                                                                            <td colspan="1"><span class="text-muted"><i class="icon-cloud position-left"></i> {{ round(($d->acctoutputoctets + $d->acctinputoctets) /1024/1024 ,1) }} MB</td>

                                                                        </tr>
                                                                        @foreach(App\Radacct::where('u_id', $u_id)->where('dates', $d->dates)->orderBy('dates','desc')->get() as $r)<!-- sessions -->
                                                                            
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="media-left media-middle">
                                                                                            <a class="icon-enter"></a>
                                                                                        </div>
                                                                                        <div class="media-left">
                                                                                            <div class=""><a href="#" class="text-default text-semibold">Session time {{ gmdate("H:i:s",$r->acctsessiontime) }}</a></div>
                                                                                                <div class="text-muted text-size-small">
                                                                                                    <span class="status-mark border-success-600 position-left"></span>
                                                                                                    {{ \Carbon\Carbon::parse($r->acctstarttime)->format("h:i:s A") }}
                                                                                                    <span class="status-mark border-danger position-left"></span>
                                                                                                    @if(isset($r->acctstoptime))  {{ \Carbon\Carbon::parse($r->acctstoptime)->format("h:i:s A") }} @else Still online @endif
                                                                                                </div>
                                                                                                @if(isset($r->groupname) and $r->groupname!="")
                                                                                                <div class=""><span class="text-orange" title="{{ $r->callingstationid }}">From: {{ $r->groupname }}<span></div>
                                                                                                <div class=""><span class="text-muted text-semibold"> Branch: {{ App\Branches::where('id', $r->branch_id)->value('name') }}<span></div>
                                                                                                @else   
                                                                                                    <div class=""><span class="text-orange">From: {{ $r->callingstationid }}<span></div>
                                                                                                @endif
                                                                                        </div>
                                                                                    </td>

                                                                                    <td><span class="text-danger"><i class="icon-cloud-download position-left"></i> {{ round($r->acctoutputoctets /1024/1024 , 1)  }} MB</span></td>
                                                                                    <td><span class="text-success-600"><i class="icon-cloud-upload position-left"></i> {{ round($r->acctinputoctets /1024/1024 ,1) }} MB</span></td>
                                                                                    <td><span class="text-muted"><i class="icon-cloud position-left"></i> {{ round(($r->acctoutputoctets + $r->acctinputoctets) /1024/1024 ,1) }} MB</span></td>
                                                                                </tr>
                                                                           
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group{{ $currentMonth['monthname'] }}" aria-expanded="false">Additional Entries</a>
                                        </h6>
                                    </div>
                                    <div id="accordion-control-right-group{{ $currentMonth['monthname'] }}" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">

                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <!-- /invoices -->
            @if($network_commercial != 1)
                <!-- Schedule -->
                <div class="timeline-row">
                    <div class="timeline-icon">
                        <div class="bg-info-800">
                            <i class="icon-cash3"></i>
                        </div>
                    </div>

                    <div class="panel panel-flat timeline-content">
                        <div class="panel-heading">
                            <h6 class="panel-title text-semibold">Charge Package</h6>
                        </div>

                        <div class="panel-body">
                             <div class="schedule"></div>
                        </div>
                    </div>
                </div>
                <!-- /schedule -->
            @endif
        </div>
    </div>
    <!-- Primary modal -->
    <div id="modal_destination" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Destinations</h6>
                </div>

                <div class="modal-body" style="min-height:800px; overflow:auto;">

                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- /primary modal -->
    <!-- /timeline -->
	<div class="modal fade" id="modal_ajax">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body" style="min-height:500px; overflow:auto;"></div>
			</div>
		</div>
	</div>


</div>
<script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/fullcalendar/fullcalendar.min.js"></script>
<script>
    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });
    // Danger
    $(".control-danger").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });

    // Success
    $(".control-success").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-success-600 text-success-800'
    });

    // Warning
    $(".control-warning").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-warning-600 text-warning-800'
    });

    // Info
    $(".control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });
    // Schedule
    // ------------------------------
    <?php $dt = Carbon\Carbon::now(); ?>
    // Add events
    @if($network_commercial != 1)
        var events = [
            @foreach($cards as $card)
            {
                <?php
                    $split = explode(';', $card->details);
                    $id = $split[0];
                    $card_data = App\Cards::where('id', $id)->get();
                    foreach($card_data as $card_data){
                    $id = $card_data->id;
                    $price = $card_data->price;
                    $number = $card_data->number;
                    }?>

                title: 'Charge Card',
                start: '{{ $card->add_date }}',
                color: '#EF5350',
                desc: '<strong>Serial</strong> {{$id}},<br> <strong>Number</strong> {{ $number }},<br>  <strong>Start date </strong>{{ $card->add_date }},<br> <strong>Price</strong> {{ $price }}.'
            },
            @endforeach


            @foreach($packages as $package)
            {
                <?php $data = App\Models\Packages::where('id',$package->package_id)->first(); ?>
                    title: 'Charge Package',
                    start: '{{ $package->add_date }}',
                @if($package->package_expiration_date)
                    end:   '{{ $package->package_expiration_date }}',
                @endif
                    color: '#26A69A',
                    desc: '<strong>Name </strong> {{ $data->name }},<br> <strong>Start date</strong> {{ $package->add_date }},<br> <strong>End date</strong> {{ $package->package_expiration_date }},<br> <strong>Price</strong> {{ $data->price }}.'
            },
            @endforeach

        ];
    @endif
	
    var $_export;
    function _destination_month_logs(id, monthname,that) {
        $_export = $(that); 
        jQuery('#modal_destination .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');

        // LOADING THE AJAX MODAL
        jQuery('#modal_destination').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'destination_logs/'+ id + '-' + monthname,
            success: function (response) {
                jQuery('#modal_destination .modal-body').html(response);   
            }
        });
    }
    var $_export;
    function _destination_day_logs(id, day,that) {
        $_export = $(that); 
        jQuery('#modal_destination .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');

        // LOADING THE AJAX MODAL
        jQuery('#modal_destination').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'destination_logs_day/'+ id + '-' + day,
            success: function (response) {
                 jQuery('#modal_destination .modal-body').html(response);   
            }
        });
    }
    // Initialize calendar with options
    $('.schedule').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: '{{ $dt->toDateString() }}',
        editable: false,
        events: events,
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


    // Marketing campaigns progress pie chart
    // ------------------------------

    // Initialize chart
    progressMeter("#today-progress", 20, 20, '#7986CB');
    progressMeter("#yesterday-progress", 20, 20, '#7986CB');

    // Chart setup
    function progressMeter(element, width, height, color) {


        // Basic setup
        // ------------------------------

        // Main variables
        var d3Container = d3.select(element),
            border = 2,
            radius = Math.min(width / 2, height / 2) - border,
            twoPi = 2 * Math.PI,
            progress = $(element).data('progress'),
            total = 100;



        // Construct chart layout
        // ------------------------------

        // Arc
        var arc = d3.svg.arc()
            .startAngle(0)
            .innerRadius(0)
            .outerRadius(radius)
            .endAngle(function(d) {
              return (d.value / d.size) * 2 * Math.PI;
            })



        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append("svg");

        // Add SVG group
        var svg = container
            .attr("width", width)
            .attr("height", height)
            .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");



        //
        // Append chart elements
        //

        // Progress group
        var meter = svg.append("g")
            .attr("class", "progress-meter");

        // Background
        meter.append("path")
            .attr("d", arc.endAngle(twoPi))
            .style('fill', '#fff')
            .style('stroke', color)
            .style('stroke-width', 1.5);

        // Foreground
        var foreground = meter.append("path")
            .style('fill', color);

        // Animate foreground path
        foreground
            .transition()
                .ease("cubic-out")
                .duration(2500)
                .attrTween("d", arcTween);


        // Tween arcs
        function arcTween() {
            var i = d3.interpolate(0, progress);
            return function(t) {
                var currentProgress = progress / (100/t);
                var endAngle = arc.endAngle(twoPi * (currentProgress));
                return arc(i(endAngle));
            };
        }
    }

</script>

@endif