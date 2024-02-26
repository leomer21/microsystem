@extends('..back-end.layouts.master')
@section('title', 'Online Users')
@section('content')
<?php 
if(App\History::where('operation','interface_out_rate')->count() > 0){
    $monitorOutinterfaceDownload=1;
    $monitorOutinterfaceUpload=1;
}
$todayDateTime = date("Y-m-d 00:00:00");
$branches = App\Branches::where('state','1')->where('last_check','>=',$todayDateTime)->get();

// pms integration status
if(App\Models\Pms::where('state', '1')->count() > 0){ $pmsIntegration = 1; }else{ $pmsIntegration = 0; }
    
?>
  


<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Online Users</h4>
        </div>
    </div>
</div>
<!-- /page header -->

	<!-- Modal with basic title -->
    <div id="timeline" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span class="text-semibold modal-title">Timeline</span>
                </div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /modal with basic title -->
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

    <!-- Info modal -->
    <div id="profile" class="modal fade">
        <div class="modal-dialog modal-ls">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h6 class="modal-title">User Profile</h6>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" onclick="document.forms['edituser'].submit(); return false;">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal -->

    <!-- Info modal addUnregisteredUsers -->
    <div id="addUnregisteredUsers" class="modal fade">
        <div class="modal-dialog modal-ls">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h6 class="modal-title">Add Unregistered Device</h6>
                </div>
                <div class="modal-body">

                </div>
                
            </div>
        </div>
    </div>
    <!-- /info modal addUnregisteredUsers -->

<!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        <div class="panel panel-flat">
            <!-- <div class="panel-heading">
                <h5 class="panel-title">Track Your Online users NOW!</h5>
            </div> -->

            <!-- <div class="panel-body">
            </div> -->
            <?php
        $counter=0;	
        foreach($branches as $branch){
            $branch_name = $branch->name;
            $counter++;
            ?>
            
            <!-- monitor Out interface Download -->
            @if(isset($monitorOutinterfaceDownload)) 
                <!-- <div class="col-lg-3"></div> -->
                <div class="col-lg-6">
                    <br><div class="chart has-fixed-height" id="monitorOutInterfaceDownload_<?php echo $counter;?>"></div>
                    <div dir='ltr' class="row">
                        <div class="col-md-5"></div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback-left">
                                <input title='Net Download Speed (MB)' class="form-control text-center" type="number" onChange="OutInterfaceDownloadSpeedUpdate({{$branch->id}})" id="OutInterfaceDownloadSpeed{{$branch->id}}" min="1" placeholder="Net Download Speed" name="OutInterfaceDownloadSpeed{{$branch->id}}" value="{{round(App\History::where('operation','interface_out_net_speed')->where('branch_id',$branch->id)->value('notes')/1024,1)}}">
                                <input type="hidden" name="branch_id{{$branch->id}}" id="branch_id{{$branch->id}}" value="{{$branch->id}}">
                                <div class="form-control-feedback">
                                    &nbsp &nbsp &nbsp <i class="icon-cloud-download" title='Net Download Speed (MB)'></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                </div>
                <!-- java script for change download value -->
            @endif

            <!-- monitor Out interface Upload -->
            @if(isset($monitorOutinterfaceUpload)) 
                <div class="col-lg-6">
                    <br><div class="chart has-fixed-height" id="monitorOutInterfaceUpload_<?php echo $counter;?>"></div>
                    <div class="row">
                        <div class="col-md-5"></div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback-left">
                            <input title='Net Upload Speed (MB)' class="form-control text-center" type="number" min="1" onChange="OutInterfaceUploadSpeedUpdate({{$branch->id}})" id="OutInterfaceUploadSpeed{{$branch->id}}" placeholder="Net Upload Speed" name="OutInterfaceUploadSpeed{{$branch->id}}" value="{{round(App\History::where('operation','interface_out_net_speed')->where('branch_id',$branch->id)->value('details')/1024,1)}}"> 
                                <input type="hidden" name="branch_id{{$branch->id}}" id="branch_id{{$branch->id}}" value="{{$branch->id}}">
                                <div class="form-control-feedback">
                                    &nbsp &nbsp &nbsp <i class="icon-cloud-upload" title='Net Upload Speed (MB)'></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                </div>
            @endif
            <?php
        }
        ?>

            <table class="table" width="100%" id="table-active">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        @if($pmsIntegration == 1)
                            <th>Room</th>
                        @else
                            <th>Mobile</th>
                        @endif
                        <th>Branch</th>
                        <th>Group</th>
                        @if($pmsIntegration != 1)
                            <th>Uptime</th>
                        @endif
                        <th>IP Address</th>
                        <th>Speed</th>
                        <th>Limitd Quota</th>
                        <th>Speed Limit</th>
                        <th class="text-center">Actions</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /scrollable datatable -->

        
        @include('..back-end.footer')
    </div>
    @section('css')
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <style>
            .stepy-navigator{
                padding: 0 10px;
            }
             .radios{
                display:none;
             }.radios + .radiol{
                display:inline-block;
                margin:-2px;
                padding: 4px 12px;
                margin-bottom: 0;
                font-size: 14px;
                line-height: 20px;
                color: #333;
                text-align: center;
                vertical-align: middle;
                cursor: pointer;
                background-color: #fbd755;
                background-repeat: repeat-x;
                border: 1px solid #ccc;
                border-color: #e6e6e6 #e6e6e6 #bfbfbf;
                border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
                border-bottom-color: #b3b3b3;
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
                filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
            }

            .radios2 + .radiol2{
                color:#fff;
                background-color: #43a047;
            }
            .radios:checked + .radiol{
                background-image: none;
                outline: 0;
                background-color:#8d8d8d;
           }

            .radioss{
                display:none;
            }

            .radioss + .radioll{
                display:inline-block;
                margin:-2px;
                padding: 4px 12px;
                margin-bottom: 0;
                font-size: 14px;
                line-height: 20px;
                color: #333;
                text-align: center;
                vertical-align: middle;
                cursor: pointer;
                background-color: #fbd755;
                background-repeat: repeat-x;
                border: 1px solid #ccc;
                border-color: #e6e6e6 #e6e6e6 #bfbfbf;
                border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
                border-bottom-color: #b3b3b3;
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
                filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
            }

            .radioss2 + .radioll2{
                color:#fff;
                background-color: #43a047;
            }
            .radioss:checked + .radioll{
                background-image: none;
                outline: 0;
                background-color:#8d8d8d;
            }
            .ti_tx,.mi_tx,.si_tx,.mer_tx{width:100%;text-align:center;margin:10px 0}.time,.mins,.sand,.meridian{width:60px;float:left;margin:0 10px;font-size:20px;color:#2d2e2e;font-family:arial;font-weight:700}.prevs,.next1{cursor:pointer;padding:18px;width:28%;border:1px solid #ccc;margin:auto;background:url(assets/images/arrow.png) no-repeat;border-radius:5px}.prevs:hover,.next1:hover{background-color:#ccc}.next1{background-position:50% 150%}.prevs{background-position:50% -50%}.time_pick{position:relative}.timepicker_wrap{width:262px;padding:10px;border-radius:5px;z-index:998;display:none;box-shadow:2px 2px 5px 0 rgba(50,50,50,0.35);background:#f6f6f6;border:1px solid #ccc;float:left;position:absolute;top:27px;left:0}.arrow_top{position:absolute;top:-10px;left:20px;background:url(assets/images/top_arr.png) no-repeat;width:18px;height:10px;z-index:999}input.timepicki-input{background:none repeat scroll 0 0 #fff;border:1px solid #ccc;border-radius:5px 5px 5px 5px;float:none;margin:0;text-align:center;width:70%}a.reset_time{float:left;margin-top:5px;color:#000}
            </style>
        @endsection
    @section('js')
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>

        <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/loaders/progressbar.min.js"></script>

        <script type="text/javascript" src="assets/js/timepicki.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
        <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
        <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>


        <script type="text/javascript" src="assets/js/plugins/forms/wizards/stepy.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
        <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
        <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
        <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.time.js"></script>
        <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/notifications/noty.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/uploaders/plupload/plupload.full.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/uploaders/plupload/plupload.queue.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/editors/summernote/summernote.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/buttons/ladda.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>

        <script type="text/javascript" src="assets/js/dropzone.js"></script> 
        
        @if(isset($monitorOutinterfaceDownload))
            <!-- Global stylesheets -->
            <!-- <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css"> -->
            

            <!-- Theme JS files MALO ELOSTAZ!!! -->
            <!-- DETECT ERROR WITH MENU BUTTON ON MOBILE 3.3.2021 -->
            <script type="text/javascript" src="assets/js/dashboardForOnlineUsers.js"></script> 
            <!-- <style>.cke{visibility:hidden;}</style> -->
 
            <!-- /theme JS files -->
            <!-- <script data-require-id="echarts/theme/limitless" src="assets/js/plugins/visualization/echarts/theme/limitless.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/pie" src="assets/js/plugins/visualization/echarts/chart/pie.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/funnel" src="assets/js/plugins/visualization/echarts/chart/funnel.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/line" src="assets/js/plugins/visualization/echarts/chart/line.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/scatter" src="assets/js/plugins/visualization/echarts/chart/scatter.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/k" src="assets/js/plugins/visualization/echarts/chart/k.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/radar" src="assets/js/plugins/visualization/echarts/chart/radar.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/gauge" src="assets/js/plugins/visualization/echarts/chart/gauge.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/bar" src="assets/js/plugins/visualization/echarts/chart/bar.js" async=""></script> -->
            <!-- <script data-require-id="echarts/chart/chord" src="assets/js/plugins/visualization/echarts/chart/chord.js" async=""></script></head> -->
        @endif

        <script>
            $(function () {

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
                        'echarts/chart/pie',
                        'echarts/chart/funnel',
                        'echarts/chart/line',
                        'echarts/chart/scatter',
                        'echarts/chart/k',
                        'echarts/chart/radar',
                        'echarts/chart/gauge',
                        'echarts/chart/line',
                        'echarts/chart/bar',
                        'echarts/chart/chord'
                    ],
                    // Charts setup (var)
                    function (ec, limitless) {
                        //////////////////////////////////////////////
                        // Monitor OUT interface download seed rate //
                        //////////////////////////////////////////////
                        <?php 
                        $counter = 0;
                        foreach($branches as $branch){
                            if(isset($branch->id)){
                                $counter++;
                                ?>
                                <!-- ----------------------------------------------------------------------------------------------------------------- -->
                                // VAR
                                @if(isset($monitorOutinterfaceDownload)) var monitorOutInterfaceDownload_<?php echo $counter;?> = ec.init(document.getElementById('monitorOutInterfaceDownload_<?php echo $counter;?>'), limitless); @endif
                                @if(isset($monitorOutinterfaceUpload)) var monitorOutInterfaceUpload_<?php echo $counter;?> = ec.init(document.getElementById('monitorOutInterfaceUpload_<?php echo $counter;?>'), limitless); @endif
                                
                                <!--  ----------------------------------------------------------------------------------------------------------------- -->
                                // Setup DATA
                                // Monitor Out interface Download
                                @if(isset($monitorOutinterfaceDownload))            

                                    // new speed dashboard
                                    // Setup chart
                                    var www = $.ajax({url:"totalDownloadSpeed",type: "get",dataType: "html",success:function(percentage){ return percentage;}});

                                    monitorOutInterfaceDownload_<?php echo $counter;?>_options = {

                                        // Add title
                                        title: {
                                            text: 'Download Speed',
                                            subtext: '( {{$branch->name}} branch )',
                                            x: 'center'
                                        },

                                        // Add tooltip
                                        tooltip: {
                                            formatter: "{a} <br/>{b}, Percentage: {c}%"
                                        },

                                        // Add series
                                        series: [
                                            {
                                                name: 'Online',
                                                type: 'gauge',
                                                center: ['50%', '55%'],
                                                detail: {formatter:'{value}% '},
                                                data: [{value: <?php
                                                                    $currentDownSpeed = App\History::where('operation','interface_out_rate')->where('branch_id',$branch->id)->value('notes');
                                                                    $netDownSpeed = App\History::where('operation','interface_out_net_speed')->where('branch_id',$branch->id)->value('notes');
                                                                    if ($currentDownSpeed != 0) {
                                                                        echo $percentage = round(($currentDownSpeed / $netDownSpeed) * 100, 1);
                                                                    } else {
                                                                        echo $percentage = 0;
                                                                    }?>, name: 'Download Speed'}]

                                            }
                                        ]
                                    };

                                    // Add random data
                                    clearInterval(timeTickerMonitorOutInterfaceDownload_<?php echo $counter;?>);
                                    var timeTickerMonitorOutInterfaceDownload_<?php echo $counter;?> = setInterval(function () {
                                        $.ajax({url:"totalDownloadSpeed/{{$branch->id}}",type: "get",dataType: "html",success:function(data){
                                            var res = data.split(";");
                                            monitorOutInterfaceDownload_<?php echo $counter;?>_options.series[0].data[0].value =  res[0];
                                            monitorOutInterfaceDownload_<?php echo $counter;?>_options.series[0].data[0].name =   res[1]; }});
                                        monitorOutInterfaceDownload_<?php echo $counter;?>.setOption(monitorOutInterfaceDownload_<?php echo $counter;?>_options, true);
                                    }, 18000); // refresh every 10 seconds

                                @endif

                                // Monitor Out interface Upload
                                @if(isset($monitorOutinterfaceUpload))            

                                    // new speed dashboard
                                    // Setup chart
                                    var www = $.ajax({url:"totalUploadSpeed",type: "get",dataType: "html",success:function(percentage){ return percentage;}});

                                    monitorOutInterfaceUpload_<?php echo $counter;?>_options = {

                                        // Add title
                                        title: {
                                            text: 'Upload Speed',
                                            subtext: '( {{$branch->name}} branch )',
                                            x: 'center'
                                        },

                                        // Add tooltip
                                        tooltip: {
                                            formatter: "{a} <br/>{b}, Percentage: {c}%"
                                        },

                                        // Add series
                                        series: [
                                            {
                                                name: 'Online',
                                                type: 'gauge',
                                                center: ['50%', '55%'],
                                                detail: {formatter:'{value}% '},
                                                data: [{value: <?php $currentDownSpeed = App\History::where('operation','interface_out_rate')->where('branch_id',$branch->id)->value('details');
                                                                    $netDownSpeed = App\History::where('operation','interface_out_net_speed')->where('branch_id',$branch->id)->value('details');
                                                                    if ($currentDownSpeed != 0) {
                                                                        echo $percentage = round(($currentDownSpeed / $netDownSpeed) * 100, 1);
                                                                    } else {
                                                                        echo $percentage = 0;
                                                                    }
                                                                    ?>, name: 'Upload Speed'}]

                                            }
                                        ]
                                    };

                                    // Add random data
                                    clearInterval(timeTickerMonitorOutInterfaceUpload_<?php echo $counter;?>);
                                    var timeTickerMonitorOutInterfaceUpload_<?php echo $counter;?> = setInterval(function () {
                                        $.ajax({url:"totalUploadSpeed/{{$branch->id}}",type: "get",dataType: "html",success:function(data){
                                            var res = data.split(";");
                                            monitorOutInterfaceUpload_<?php echo $counter;?>_options.series[0].data[0].value =  res[0];
                                            monitorOutInterfaceUpload_<?php echo $counter;?>_options.series[0].data[0].name =   res[1]; }});
                                        monitorOutInterfaceUpload_<?php echo $counter;?>.setOption(monitorOutInterfaceUpload_<?php echo $counter;?>_options, true);
                                    }, 19000); // refresh every 10 seconds

                                @endif
                                <!--  ---------------------------------------------------------------------------------------------------------------- -->
                                // Options
                                @if(isset($monitorOutinterfaceDownload)) monitorOutInterfaceDownload_<?php echo $counter;?>.setOption(monitorOutInterfaceDownload_<?php echo $counter;?>_options); @endif
                                @if(isset($monitorOutinterfaceUpload)) monitorOutInterfaceUpload_<?php echo $counter;?>.setOption(monitorOutInterfaceUpload_<?php echo $counter;?>_options); @endif
                                <!--  ---------------------------------------------------------------------------------------------------------------- -->
                                // Resize
                                @if(isset($monitorOutinterfaceDownload)) monitorOutInterfaceDownload_<?php echo $counter;?>.resize(); @endif
                                @if(isset($monitorOutinterfaceUpload)) monitorOutInterfaceUpload_<?php echo $counter;?>.resize(); @endif
                            <?php
                            }
                        }
                        ?>
                    }// Ends of charts setup
                
                );
            });
        </script>

       
	@endsection
    










    <script>
    $(document).ready(function(){
        setInterval(function() {
            $.ajax({
                url:'activeusersjson', type: 'get', data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function(){
                    $('#table-active').DataTable().ajax.reload();
                }
            });

        }, 25000); //10 seconds
    });
    // update download and uplaod speed
    function OutInterfaceDownloadSpeedUpdate(branchID) {
        var outInterfaceName = 'OutInterfaceDownloadSpeed'+ branchID;
        var OutDownloadSpeed = document.getElementById(outInterfaceName).value;
        // var branchID = document.getElementById("branch_id").value;
        $.ajax({
            url:'updateTotalDownloadSpeed/'+branchID+'/'+OutDownloadSpeed
        });
    };
    function OutInterfaceUploadSpeedUpdate(branchID) {
        var outInterfaceName = 'OutInterfaceUploadSpeed'+ branchID;
        var OutUploadSpeed = document.getElementById(outInterfaceName).value;
        // var branchID = document.getElementById("branch_id").value;
        $.ajax({
            url:'updateTotalUploadSpeed/'+branchID+'/'+OutUploadSpeed
        });
    };
    // Table setup
    // ------------------------------

    // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            responsive: true,
            dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            }
        });
    // Basic initialization
       var table = $('#table-active').DataTable({
            responsive: {
            details: {
               type: 'column',
               target: -1
               }
            },
            ajax:{"url" : "activeusersjson",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
                     {
                        extend: 'colvis',
                        text: '<i class=icon-loop3></i>',
                        className: 'btn btn-default',
                        action: function ( e, dt, node, config ) {
                            $.ajax({
                                url:'activeusersjson', type: 'get', data: {_token: $('meta[name="csrf-token"]').attr('content')},
                                success: function(){
                                    $('#table-active').DataTable().ajax.reload();
                                }
                            });

                        }
                    },
                    {extend: 'copy',text: '<i title="Copy" class="icon-copy3"></i>'},
                    {extend: 'csv' ,text: '<i title="Export to CSV sheet." class="icon-file-spreadsheet"></i>'},
                    {extend: 'excel' ,text: '<i title="Export to excel sheet." class="icon-file-excel"></i>'},
                    {extend: 'pdf' , text: '<i title="Export to PDF file." class="icon-file-pdf"></i>'},
                    {extend: 'print', text: '<i title="Print" class="icon-printer"></i>'},
                    {
                        extend: 'colvis',
                        text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                        className: 'btn bg-blue btn-icon'
                    }

                ]
            },
            columnDefs: [
                {
                    className: 'control',
                    orderable: false,
                    targets:   -1
                }],
            deferRender: true,
            columns:[
            {"render": function ( type, full, data, meta ) {
                // check if this record bypassed to add delete option
                if (!data.groupname && data.u_name == '<span class="label btn-success btn-ladda btn-ladda-spinner">Bypassed</span>'){return data.wifi_signal+data.u_name; }
                // check if device not registerd
                else if(!data.groupname){ return '<a href="#" title="Add Unregistered Device" class="addUnregisteredUsers" >'+data.wifi_signal+data.u_name+'</a>'; }
                // normal record
                else{ return '<a href="#" title="Open profile" class="profile" >'+data.wifi_signal+data.u_name+'</a>'; }
            }},
            {"render": function ( type, full, data, meta ) {
                // check if user not registerd
                if(!data.groupname){
                    if(!data.deviceName){ return '<span>'+data.username+'</span>';}
                    else{ return '<span title="Open timeline for '+data.username+'" >'+data.deviceName+'</span>'; }
                }else{
                    if(!data.deviceName){ return '<a href="#" title="Open Timeline" class="history" >'+data.username+'</a>';}
                    else{ return '<a href="#" title="Open timeline for '+data.username+'" class="history" >'+data.deviceName+'</a>'; } 
                }
            }},
            @if($pmsIntegration == 1)
                { "data": "pms_room_no"},
            @else
                {"render": function ( type, full, data, meta ) {
                    if(data.u_phone!= null){
                        return '<a target="_blank" href="https://www.google.com/search?q='+data.u_phone+'" title="Google search" >'+data.u_phone+'</a>';
                    }else{
                        return '';
                    }
                }},
            @endif

            { "data": "branch_name" },
            // Group_name
            {"render": function ( type, full, data, meta ) {
                if(!data.groupname && !data.branch_id){ return '';}
                else if(!data.groupname){ return '<span class="label label-info">self rules</span>';}
                else{return data.groupname;}
            }},
            @if($pmsIntegration != 1)
                { "data": "acctstarttime" },
            @endif
            { "data": "framedipaddress" },
            {"render": function ( type, full, data, meta ) {
               // check if not speed limit
               var totalQuata=Math.round(data.total_quota/1024/1024);
			   //if( data.foundSpeedLimitInGroup==0 || data.u_name == '<span class="label btn-success btn-ladda btn-ladda-spinner">Bypassed</span>' || data.realm=='2done' || data.realm=='2'){return '<span class="label border-left-success label-striped">Unlimited <i class="icon-medal-star"></i></span>';}
               if(totalQuata==0 || data.realm=='2done' || data.realm=='2'){return '<span class="label border-left-success label-striped">Unlimited <i class="icon-medal-star"></i></span>';}
               // build download bar
               if(data.downloadPersentage >= 90){ var downloadColor = 'danger';} else{ var downloadColor = 'info';}
               var downloadBar = '<center>' + data.currentDownloadSpeed + 'M'+'  <i title="Download Speed" class="icon-cloud-download position-left"></i>' + data.finalGroupDownloadSpeed + 'M'+'</center>' + '<div class="progress" title="Download Speed '+data.downloadPersentage+'%"><div class="progress-bar progress-bar-'+downloadColor+' progress-bar-striped active" style="width:'+ data.downloadPersentage +'%"><span>'+data.downloadPersentage+'%</span></div></div>';
               // build upload bar
               if(data.uploadPersentage >= 90){ var uploadColor = 'danger';} else{ var uploadColor = 'info';}
               var uploadBar = '<center>' + data.currentUploadSpeed + 'M'+'  <i title="Upload Speed" class="icon-cloud-upload position-left"></i>' + data.finalGroupUploadSpeed + 'M'+'</center>' + '<div class="progress" title="Upload Speed '+data.uploadPersentage+'%"><div class="progress-bar progress-bar-'+uploadColor+' progress-bar-striped active" style="width:'+ data.uploadPersentage +'%"><span>'+data.uploadPersentage+'%</span></div></div>';
               return '<center>'+downloadBar+uploadBar+'</center>';
              }
            },
            { "data": "total_quota",
            "searchable": false,
               "render": function ( type, full, data, meta ) {
               //if(data.total_quota)
               var totalUsagePersentage1 = Math.round((data.TodayUpload + data.TodayDownload) / data.total_quota * 100);
               if(totalUsagePersentage1>100){totalUsagePersentage1=100;}
               var usedQuota=Math.round(((data.TodayUpload + data.TodayDownload)/1024)/1024);
               var totalQuata=Math.round(data.total_quota/1024/1024);
               if(totalQuata==0){return '<span class="label border-left-success label-striped">Unlimited <i class="icon-medal-star"></i></span>';}
               if(usedQuota>totalQuata){var finalUsedQuota='<small class="text-danger text-size-base">' + usedQuota + 'M</small>';}else{var finalUsedQuota=usedQuota+'M';}
               return '<center>' +   finalUsedQuota   +  '<i class="icon-meter-fast"></i>  ' + totalQuata + 'M'+'</center>' + '<div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped active" style="width:'+ totalUsagePersentage1 +'%"><span>'+totalUsagePersentage1+'%</span></div></div>';

              }
            },
            {"render": function ( type, full, data, meta ) {
                    var totalUsagePersentage = Math.round((data.TodayUpload + data.TodayDownload) / data.total_quota * 100);
                    var totalQuata=Math.round(data.total_quota/1024/1024);
                    var usedQuota=Math.round(((data.TodayUpload + data.TodayDownload)/1024)/1024);
                    if(data.realm=='2done' || data.realm=='2'){
                        var controlSpeedLimit='<br><button type="button" class="label btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="red" ><span class="ladda-label" title="Speed limitation removed in this session, Click to reactivate again.">Click to reactivate limit </span></button>';
                        return controlSpeedLimit;
                    }else{
                        var controlSpeedLimit='<br><button type="button" class="label btn-success btn-ladda btn-ladda-spinner" data-spinner-color="red" ><span class="ladda-label" title="Click to avoid speed limitation temporary.">Click to stop limit temporary</span></button>';
                        if(!data.designed_speed || data.designed_speed=='0K/0K'){return '<span class="label border-left-success label-striped remove">Unlimited <i class="icon-medal-star"></i></span> ';}
                        //if(usedQuota<totalQuata){return '<span class="label border-left-success label-striped">Unlimited <i class="icon-medal-star"></i></span>';}
                        if(usedQuota<totalQuata){return data.designed_speed + controlSpeedLimit;}
                        else if(totalQuata !=0 && usedQuota>totalQuata){return '<small class="text-danger text-size-base"><i class="icon-arrow-down12"></i>' + data.designed_end_speed + '</small>' + controlSpeedLimit;}
                        else{return data.designed_speed + controlSpeedLimit;}
                    }
                    
            }},
        
            {
                "data": null,
                "searchable": false,
                "render": function (type, full, data, meta) {
                    // check if this record is suspended to add unsuspend option
                    if (data.suspend == "1")
                        return '<center><ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                '<li><a href="#" class="history" ><i class="icon-file-stats"></i> Timeline</a></li>' +
                                '<li><a href="#" class="profile" ><i class="icon-user"></i> Profile</a></li>' +
                                '<li><a href="#" class="unsuspend"><i class="icon-user-check"></i> Unsuspend </a></li>' +
                                '</ul> </li> </ul></center>';
                    // check if this record bypassed to add delete option
                    else if (data.u_name == '<span class="label btn-success btn-ladda btn-ladda-spinner">Bypassed</span>')
                        return '<center><ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                '<li><a href="#" class="removeBypass" ><i class="icon-close2"></i> Remove bypass</a></li>' +
                                '</ul> </li> </ul></center>';
                    // check if this record is unregisterd device so we remove all options
                    else if (!data.groupname)
                        return '<center><ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                '<li><a href="#" title="Add Unregistered Device" class="addUnregisteredUsers" ><i class="icon-user-plus"></i> Add Unregistered Device</a></a></li>' +
                                '</ul> </li> </ul></center>';
                    // normal record
                    else
                        return '<center><ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                               '<li><a href="#" class="history" ><i class="icon-file-stats"></i> Timeline</a></li>' +
                               '<li><a href="#" class="profile" ><i class="icon-user"></i> Profile</a></li>' +
                               '<li><a href="#" class="disconnect"><i class="icon-user-cancel"></i> Disconnect </a></li>' +
                               '<li><a href="#" class="suspend"><i class="icon-user-block"></i> Suspend </a></li>' +
                               '</ul> </li> </ul></center>';

                }
            } 
             ,{ "data":null,"defaultContent":"" }
            ]
        });
         function get_group(){
           var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                 type: "POST",
                 url: 'getgruop/'+$('.network-edit').select2('data')[0].id,
                 data: {_token: CSRF_TOKEN},
                 success:function(data) {
                     $('.gruop-edit').html('');
                     $(data).each(function(k,v){
                         $('.gruop-edit').append('<option value="'+v.id+'">' + v.name + '</option>');
                     });
                     $('.gruop-edit').select2();
                 },
                 error:function(){
                     $('.gruop-edit').select2({data:null});
                 }
            });
        }

        $('#table-active tbody').on('click', 'button.btn-ladda-spinner', function () {
            var data = table.row($(this).parents('tr')).data(),
                    sus = ($(this).hasClass('btn-success')) ? false : true,
                    that = this;
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            $(this).text('Loading ..');
            $.ajax({
                url: 'controlSpeedLimit/' + data.u_id + '/' + sus + '/' + data.acctuniqueid,
                
                success: function (data) {
                    if (sus) {
                        $(that).text('limitation will apply again shortly');
                        $(that).removeClass('btn-danger');
                        $(that).addClass('btn-success');
                        
                    }
                    else {
                        // $(that).remove();
                        $(that).text('Click to reactivate limit');
                        $(that).removeClass('btn-success');
                        $(that).addClass('btn-danger');
                    }
                },
                error: function () {
                    $(that).remove();

                }
            });
        });


        // Column selectors
        $('.datatable-button-html5-columns').DataTable({
            buttons: {
                buttons: [
                    {
                        extend: 'copyHtml5',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: [ 0, ':visible' ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: [0, 1, 2, 5]
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                        className: 'btn btn-default btn-icon'
                    }
                ]
            }
        });
        $('.timepicker').timepicki({
            show_meridian:false,
            min_hour_value:0,
            max_hour_value:23,
            overflow_minutes:true,
            time:1,
            increase_direction:'up',
            disable_keyboard_mobile: true });

        $('.timepicker2').timepicki({
            show_meridian:false,
            min_hour_value:0,
            max_hour_value:23,
            overflow_minutes:true,
            mint:1,
            increase_direction:'up',
            disable_keyboard_mobile: true });
        $('#table-active tbody').on( 'click', '.disconnect', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'disconnect/' + data.acctuniqueid,
                success: function(response)
                {
					//location.reload();
                }
            });
        });
        $('#table-active tbody').on( 'click', '.history', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }

            jQuery('#timeline .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');

            jQuery('#timeline').modal('show', {backdrop: 'true'});
            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'timeline/' + data.u_id,
                success: function(response)
                {
                    jQuery('#timeline .modal-body').html(response);
                }
            });
        });

		$('#table-active tbody').on( 'click', '.profile', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }

            jQuery('#profile .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/preloader.gif" /></div>');

            jQuery('#profile').modal('show', {backdrop: 'true'});
            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'user_profile/' + data.u_id,
                success: function(response)
                {
					jQuery('#profile .modal-body').html(response);
                }
            });
        });

		$('#table-active tbody').on( 'click', '.addUnregisteredUsers', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }

            jQuery('#addUnregisteredUsers .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/preloader.gif" /></div>');

            jQuery('#addUnregisteredUsers').modal('show', {backdrop: 'true'});
            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'addUnregisteredUsers/' + data.username + '/' + data.branch_id,
                success: function(response)
                {
					jQuery('#addUnregisteredUsers .modal-body').html(response);
                }
            });
        });

        $('#table-active tbody').on( 'click', '.suspend', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'disconnectandsuspend/' + data.acctuniqueid,
                success: function(response)
                {
					location.reload();
                }
            });
        });

        $('#table-active tbody').on( 'click', '.unsuspend', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'unsuspend/' + data.u_id,
                success: function(response)
                {
					location.reload();
                }
            });
        });
        
        $('#table-active tbody').on( 'click', '.removeBypass', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'removeBypass/' + data.username,
                success: function(response)
                {
					location.reload();
                }
            });
        });

        

    // Basic
        $('#Success_message').on('click', function() {
            swal({
                title: "Success!",
                confirmButtonColor: "#2196F3"
            });
        });
    // Scrollable datatable
        $('.datatable-scroll-y').DataTable({
        autoWidth: true,
        scrollY: 300
    });
        $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    // Checkboxes
       $(".styled").uniform({
           radioClass: 'choice'
       });
    // Accordion component sorting
        $(".accordion-sortable").sortable({
            connectWith: '.accordion-sortable',
            items: '.panel',
            helper: 'original',
            cursor: 'move',
            handle: '[data-action=move]',
            revert: 100,
            containment: '.content-wrapper',
            forceHelperSize: true,
            placeholder: 'sortable-placeholder',
            forcePlaceholderSize: true,
            tolerance: 'pointer',
            start: function(e, ui){
                ui.placeholder.height(ui.item.outerHeight());
            }
        });


        // Collapsible component sorting
        $(".collapsible-sortable").sortable({
            connectWith: '.collapsible-sortable',
            items: '.panel',
            helper: 'original',
            cursor: 'move',
            handle: '[data-action=move]',
            revert: 100,
            containment: '.content-wrapper',
            forceHelperSize: true,
            placeholder: 'sortable-placeholder',
            forcePlaceholderSize: true,
            tolerance: 'pointer',
            start: function(e, ui){
                ui.placeholder.height(ui.item.outerHeight());
            }
        });

    </script>
@endsection
