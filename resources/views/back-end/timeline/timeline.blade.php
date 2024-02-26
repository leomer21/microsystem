@if(isset($error) && $error == 1)
<div class="alert alert-warning alert-styled-right">

    <span class="text-semibold">Sorry!</span>
    @if($type == "networks")
        this network doesn't have any activities. 
    @elseif($type == "groups")
        this group doesn't have any activities. 
    @elseif($type == "branchs")
        this branch doesn't have any activities. 
    @endif
</div>

@else

<!-- Content area -->
<div class="content">
    <!-- Timeline -->
    <div class="timeline timeline-left">
        <div class="">
            <!-- Date stamp -->
            <div class="timeline-date text-muted">
                @if($type == "networks")
                    {{ App\Network::where('id', $id)->value('name') }}
                @elseif($type == "groups")
                    {{ App\Groups::where('id', $id)->value('name') }}
                @elseif($type == "branchs")
                    {{ App\Branches::where('id', $id)->value('name') }}
                @endif
            </div>
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
                        @if($type == "networks")
                            <h6 class="panel-title text-size-small">Network timeline</h6>
                        @elseif($type == "groups")
                            <h6 class="panel-title text-size-small">Group timeline</h6>
                        @elseif($type == "branchs")
                            <h6 class="panel-title text-size-small">Branch timeline</h6>
                        @endif
                    </div> 
                    <div class="panel-body">
                        @foreach($months as $currentMonth)
                        <div class="panel-group panel-group-control panel-group-control-right content-group-lg col-lg-12" id="accordion-control-right">
                            <div class="panel panel-white">
                                <div class="panel-heading">
									<h6 class="panel-title">
                                        <a  data-toggle="collapse" class="collapsed " data-parent="#accordion-control-right" href="#accordion-control-right-group-{{ $currentMonth['month'] }}" aria-expanded="true" class=""><i class="icon-calendar52 position-left"></i> {{ $currentMonth['month'] }} <i class="icon-alarm position-left"></i>Total time @if( $currentMonth['acctsessiontime'] > 86400) {{ gmdate("d",$currentMonth['acctsessiontime'])-1 }}d {{ gmdate("H:i:s", $currentMonth['acctsessiontime']) }} @else {{ gmdate("H:i:s",$currentMonth['acctsessiontime']) }} @endif
                                        <i class="icon-sun3 position-left"></i>Days @if($type == "networks") {{App\Models\RadacctNetworkDays::where(['month' => $currentMonth['month'], 'network_id' => $id])->count()}}
                                             @elseif($type == "groups") {{App\Models\RadacctGroupDays::where(['month' => $currentMonth['month'], 'group_id' => $id])->count()}} 
                                             @elseif($type == "branchs")  {{App\Models\RadacctBranchDays::where(['month' => $currentMonth['month'], 'branch_id' => $id])->count()}} @endif
                                        <i class="icon-cloud-upload position-left"></i> @if($currentMonth['acctinputoctets'] > 1073741824) {{round($currentMonth['acctinputoctets']/1024/1024/1024,1)}} GB @else {{ round($currentMonth['acctinputoctets']/1024/1024,1)}} MB @endif

                                        <i class="icon-cloud-download position-left"></i> @if($currentMonth['acctoutputoctets'] > 1073741824) {{ round($currentMonth['acctoutputoctets']/1024/1024/1024,1) }} GB @else {{ round($currentMonth['acctoutputoctets']/1024/1024,1) }} MB @endif

                                        <i class="icon-cloud position-left"></i> @if($currentMonth['total'] > 1073741824) {{ round($currentMonth['total']/1024/1024/1024,1) }} GB @else {{ round($currentMonth['total']/1024/1024,1) }} MB @endif</a>


                                        </a>

									</h6>
									<div class="heading-elements">
										<ul class="icons-list">
					                		<li class="dropdown">
					                			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i> <span class="caret"></span></a>
												<ul class="dropdown-menu dropdown-menu-right">
                                                    @if($type == "networks")
                                                        <li><a href="{{ url('export_timeline/'.$id.'/'.$currentMonth['month'].'/networks')  }}"><i class="icon-file-excel"></i> Export report</a></li>
                                                    @elseif($type == "groups")
                                                        <li><a href="{{ url('export_timeline/'.$id.'/'.$currentMonth['month'].'/groups')  }}"><i class="icon-file-excel"></i> Export report</a></li>
                                                    @elseif($type == "branchs")
                                                        <li><a href="{{ url('export_timeline/'.$id.'/'.$currentMonth['month'].'/branches')  }}"><i class="icon-file-excel"></i> Export report</a></li>                              
                                                    @endif
												</ul>
					                		</li>
					                	</ul>
				                	</div>
                                </div>
                                <div id="accordion-control-right-group-{{ $currentMonth['month'] }}" class="panel-collapse collapse" aria-expanded="true">
                                    <div class="panel-body">
                                        @if($type == "networks")
                                           <?php $query = App\Models\RadacctNetworkDays::where(['network_id' => $id, 'month' => $currentMonth['month']])->get(); ?>
                                        @elseif($type == "groups")
                                           <?php $query = App\Models\RadacctGroupDays::where(['group_id' => $id, 'month' => $currentMonth['month']])->get(); ?>
                                        @elseif($type == "branchs")    
                                           <?php $query = App\Models\RadacctBranchDays::where(['branch_id' => $id, 'month' => $currentMonth['month']])->get(); ?>
                                        @endif
                                        @foreach($query as $value) <!-- Days -->
                                            <div class="panel-group panel-group-control panel-group-control-right content-group-lg">
                                                <div class="panel panel-white">
                                                    <div class="panel-heading"> 
                                                        <h6 class="panel-title">
                                                            <i class="icon-sun3 position-left"></i> {{ $value->dates }} <i class="icon-alarm-check position-left"></i> Total time @if($value->acctsessiontime > 3600) {{ gmdate("H:i:s",$value->acctsessiontime)  }} @else {{ gmdate("H:i:s",$value->acctsessiontime) }} @endif
                                                             <i class="icon-cloud-upload position-left"></i> 
                                                             @if($value->acctinputoctets > 1073741824) {{round($value->acctinputoctets/1024/1024/1024,1)}} GB @else {{round($value->acctinputoctets/1024/1024,1)}} MB @endif
                                                             <i class="icon-cloud-download position-left"></i> 
                                                             @if($value->acctoutputoctets > 1073741824) {{round($value->acctoutputoctets/1024/1024/1024,1)}} GB @else {{round($value->acctoutputoctets/1024/1024,1)}} MB @endif
                                                            <i class="icon-cloud position-left"></i> 
                                                            @if($value->total > 1073741824) {{round($value->total/1024/1024/1024,1)}} GB @else {{round($value->total/1024/1024,1)}} MB @endif 
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- /invoices -->
            
        </div>
    </div>
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
    
    var $_export;
    function _destination_month_logs(id, monthname,that) {
        $_export = $(that); 
        jQuery('#modal_destination .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/loading-ttcredesign.gif" /></div>');

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
        jQuery('#modal_destination .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/loading-ttcredesign.gif" /></div>');

        // LOADING THE AJAX MODAL
        jQuery('#modal_destination').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'destination_logs/'+ id + '-' + day,
            success: function (response) {
                 jQuery('#modal_destination .modal-body').html(response);   
            }
        });
    }
    
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