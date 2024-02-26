@extends('..back-end.layouts.master') @section('title', 'Network') @section('content')
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Network</h4>
        </div>
    </div>
</div>
<!-- /page header -->



<!-- Primary modal -->
<div id="add_network" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Network</h6>
            </div>

            <div class="modal-body">
                <h6 class="text-semibold"></h6>

                <div class="row">
                    <form action="{{ url('add_network') }}" method="POST" id="addnetwork" class="form-horizontal">
                        {{ csrf_field() }}

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Network Name</label>
                            <div class="col-lg-8">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="name" type="text" class="form-control input-xlg">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Network Mode</label>
                            <div class="col-lg-8">
                                <select class="select-fixed-single" name="mode">
                            <option value="0">Cloud</option>
                            <option value="1">Localhost</option>
                            </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Connection Type</label>
                            <div class="col-lg-8">
                                <select class="select-fixed-single" name="c_type">
                            <option value="1">Radius</option>
                            <option value="0">API</option>
                            </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Network state</label>
                            <div class="col-lg-8">
                                <select class="select-fixed-single" name="state">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                            </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Register Type</label>
                            <div class="col-lg-8">
                                <select class="select-fixed-single" name="r_type">
                            <option value="0">Dircet</option>
                            <option value="1">Admin Confirm</option>
                            <option value="2">SMS</option>
                            </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Notes</label>
                            <div class="col-lg-8">
                                <textarea name="notes" type="text" rows="3" class="form-control input-xlg"></textarea>
                            </div>
                        </div>
                        <!--
                        <h6 class="content-group text-semibold no-margin-top">
                            <center> Additional Entries </center>
                            <small class="display-block"></small>
                        </h6>
                        -->
                        <div class="panel-group panel-group-control panel-group-control-right content-group-lg col-lg-12" id="accordion-control-right">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group1">Commercial mode</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group1" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">System mode </label>
                                            <div class="col-lg-2">
                                                <select class="select-fixed-singless" name="commercial">
                                               <option value="1">Free</option>
                                               <option value="0">Commercial</option>
                                               <option value="2">Free + Commercial</option>
                                            </select>
                                            </div>
                                            <!--<label class="control-label col-lg-3">System Name</label>
                                        <div class="col-lg-4">
                                           <div class="form-group has-feedback has-feedback-left">
                                           <input name="system_name" type="text" class="form-control input-xlg">
                                           <div class="form-control-feedback">
                                               <i class="icon-server"></i>
                                           </div>
                                           </div>
                                        </div>-->
                                        </div>

                                        <!--<div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User can back to trial</label>
                                        <div class="col-lg-3">
                                            <select class="select-fixed-single" name="back_trial">
                                               <option value="1">Yes</option>
                                               <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>-->
                                    </div>
                                </div>
                            </div>
                            <!--
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group2">Additional Entries</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Register state</label>
                                            <div class="col-lg-3">
                                                <select class="select-fixed-singles" name="register_state">
                                           <option value="1">On</option>
                                           <option value="0">Off</option>
                                        </select>
                                            </div>
                                        </div>
                                        <!--<div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Auto Disable Users If Not Disabled*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="autoDisableUsersIfNotDisabled">
                                           <option value="0">No</option> 
                                           <option value="1">Yes</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User charge state*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="charge_account_state">
                                           <option value="1">On</option>
                                           <option value="0">Off</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Charge by visa state*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="charge_visa_state">
                                           <option value="0">Off</option>
                                           <option value="1">On</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User tracking account*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="user_tracking_account">
                                           <option value="1">On</option>
                                           <option value="0">Off</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User SMS service</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="sms_service_state">
                                           <option value="0">Off</option>
                                           <option value="1">On</option>
                                        </select>
                                        </div>
                                    </div>-
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">User Change password</label>
                                            <div class="col-lg-3">
                                                <select class="select-fixed-singles" name="change_pass_state">
                                           <option value="0">Yes</option>
                                           <option value="1">No</option>
                                        </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">User update profile </label>
                                            <div class="col-lg-3">
                                                <select class="select-fixed-singles" name="update_profile_state">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                            </div>
                                        </div>
                                        <!-<div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User can update group</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="update_user_region_status">
                                           <option value="0">No</option>
                                           <option value="1">Yes</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User update PIN</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="personal_number">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User Pay Mobile Cards*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="mobile_card_pay">
                                           <option value="0">No</option>
                                           <option value="1">Yes</option>
                                        </select>
                                        </div>
                                    </div>-->
                                        <!--<div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">User view Mobile Card History*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="mobile_card_history">
                                           <option value="0">Yes</option>
                                           <option value="1">No</option>
                                        </select>
                                        </div>
                                    </div>-->
                                        <!--<div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Where user ended </label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-single" name="stop_user_type">
                                           <option value="0">Disable user</option>
                                           <option value="1">Switch to END profile</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">End Profile</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-single" name="stop_user_profile_id">
                                           <option value="0">One Mega</option>
                                           <option value="1">512 K</option>
                                           <option value="2">256 K</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Company system</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="company_system">
                                           <option value="0">Off</option>
                                           <option value="1">On</option>
                                        </select>
                                        </div>
                                    </div>-
                                    </div>
                                </div>
                            </div>-->
                            <!--
                        <div class="panel panel-white">
                            <div class="panel-heading">
                                <h6 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group3">Packages</a>
                                </h6>
                            </div>
                            <div id="accordion-control-right-group3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Show monthly packages*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="show_package_monthly">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Show Validity packages*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="show_package_validity">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Show SMS packages*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="show_package_sms">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Show Bandwidth packages*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="show_package_bandwidth">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Show period packages*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="show_package_period">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-4">Show offer packages*</label>
                                        <div class="col-lg-3">
                                        <select class="select-fixed-singles" name="show_package_offer">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        </div>
                        <!-- /basic accordion -->

                    </form>
                </div>

                <hr>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['addnetwork'].submit(); return false;">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->

<!-- Primary modal -->
<div id="modal_ajax" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Edit Network</h6>
            </div>

            <div class="modal-body">


                <hr>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['edit'].submit(); return false;">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->


<!-- Primary modal -->
<div id="modal_download" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Download</h6>
            </div>

            <div class="modal-body">


                <hr>
            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->

<!-- Modal with basic title -->
<div id="modal_timeline" class="modal fade">
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
    
<!-- Content area -->
<div class="content">
    <!-- Scrollable datatable -->
    <div class="panel panel-flat">
        <!--<div class="panel-heading">
                <h5 class="panel-title">Network Table</h5>
            </div> -->

        <div class="panel-body">
            <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_network"><b><i class="icon-server"></i></b> Add Network</button>
        </div>

        <table class="table" width="100%" id="table-network">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Network Name</th>
                    <th>Network State</th>
                    <th>Connection Type</th>
                    <th>Online Users</th>
                    <th>Monthly usage</th>
                    <th>Total usage</th>
                    <th class="text-center">Actions</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /scrollable datatable -->
    @include('..back-end.footer')
</div>
@section('js')
<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>

<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script> @endsection
<script>
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
       var table = $('#table-network').DataTable({
            responsive: {
            details: {
               type: 'column',
               target: -1
               }
            },
           "processing": true,
            ajax:{"url" : "networks",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
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
            { "data": "id" },
            // { "data": "name" },
            {"render": function ( type, full, data, meta ) {
                return '<a href="#" title="Edit" class="edit" >'+data.name+'</a>';
            }},
            { "data": "state",
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.state == 1)
              return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Active</span></button>';
              else
              return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Inactive</span></button>';

            }},
            { "data": "c_type",
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.c_type == 0)
              return '<td><span class="label label-danger">API</span></td>';
              else
              return '<td><span class="label label-info">Radius</span></td>';

            }},
            {"render": function ( type, full, data, meta ) {
                        return '<div class="col-md-3 col-sm-4"><i class="icon-users2"></i></div> &nbsp &nbsp' + data.count_online + '/' + data.count_users + '';
            }},
            { "data": "monthly_usage" },
            { "data": "total_usage" },
            { "data": null, "defaultContent":'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
             '<li><a href="#" class="edit" ><i class="icon-pencil3"></i> Edit</a></li>' +
             '<li><a href="#" class="timeline" ><i class="icon-file-stats"></i> Timeline</a></li>' +
             '<li><a href="#" class="destinations" ><i class="icon-link2"></i> Export Website visited</a></li>' +
             '<li><a href="#" class="delete"><i class="icon-cross3"></i> Delete</a></li>' +
             '</ul> </li> </ul>'},
             { "data":null,"defaultContent":"" }
            ]
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
     // Alert combination
        $('#table-network tbody').on( 'click', '.delete', function () {
           var that = this;
           swal({
             title: "Are you sure?",
             text: "You will not be able to recover network data again!",
             type: "warning",
             showCancelButton: true,
             confirmButtonColor: "#DD6B55",
             confirmButtonText: "Yes, delete it!",
             cancelButtonText: "No, cancel plx!",
             closeOnConfirm: false,
             closeOnCancel: false,
             showLoaderOnConfirm: true
           },
           function(isConfirm){

             if (isConfirm) {
                 var data = table.row( $(that).parents('tr') ).data();
                 if(data  == null){
                   data = table.row( $(that).parents('tr').prev() ).data();
                 }
                 $.ajax({
                    url:'delete_network/'+data.id,
                     success:function(data) {
                         table.row( $(that).parents('tr') ).remove().draw();
                         swal("Deleted!", "Your  network data has been deleted.", "success");
                     },
                     error:function(){
                         swal("Cancelled", "Your network data is safe :)", "error");

                     }
                 });
             } else {
                 swal("Cancelled", "Your Cancelled :)", "success");
             }

           });

           });
        $('#table-network tbody').on( 'click', '.edit', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
             //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_ajax').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'getid/' + data.id,
                success: function(response)
                {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_ajax .modal-body').html(response);

                }
            });

        });
        $('#table-network tbody').on( 'click', '.destinations', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
            // LOADING THE AJAX MODAL
            jQuery('#modal_download').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'download_modal/' + data.id + '-' + 'networks',
                success: function(response)
                {
                    jQuery('#modal_download .modal-body').html(response);
                }
            });

        });
        $('#table-network tbody').on( 'click', '.timeline', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
            // LOADING THE AJAX MODAL
            jQuery('#modal_timeline').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'modal_timeline/' + data.id + '-' + 'networks',
                success: function(response)
                {
                    jQuery('#modal_timeline .modal-body').html(response);
                }
            });

        });
        $('#table-network tbody').on( 'click', 'button.btn-ladda-spinner', function () {
           var data = table.row( $(this).parents('tr') ).data(),
           sus = ($(this).hasClass('btn-success'))? false : true,
                   that = this;
           if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
           }
           $(this).text('Loading...');
           $.ajax({
               url:'network_state/' + data.id + '/' + sus,
               success:function(data) {
                   if(sus) {
                       $(that).text('Active');
                       $(that).removeClass('btn-danger');
                       $(that).addClass('btn-success');

                   }
                   else {
                       $(that).text('Inactive');
                       $(that).removeClass('btn-success');
                       $(that).addClass('btn-danger');
                   }
               },
               error:function(){
                   if(!sus) {
                       $(that).text('Active');
                       $(that).removeClass('btn-danger');
                       $(that).addClass('btn-success');
                   }
                   else {
                       $(that).text('Inactive');
                       $(that).removeClass('btn-success');
                       $(that).addClass('btn-danger');
                   }

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
        $('.select-fixed-single').select2({
        minimumResultsForSearch: Infinity,
        width: 250
    });
    // Bootstrap switch
    // ------------------------------

    $(".switch").bootstrapSwitch();
    // Checkboxes
       $(".styled").uniform({
           radioClass: 'choice'
       });
    $('#Radiuscheck').on('click', function () {
          if($(this).is(':checked')){
               $('#RadiusDiv').show();
          }else{
              $('#RadiusDiv').hide();
          }
       });
    $('#RadiusDiv').hide();


       $('#Radiuschecks').on('click', function () {
          if($(this).is(':checked')){
               $('#RadiusDiv2').show();
          }else{
              $('#RadiusDiv2').hide();
          }
       });
    $('#RadiusDiv2').hide();
    $('.select-fixed-singles').select2({
          minimumResultsForSearch: Infinity,
          width: 75
      });
   $('.select-fixed-singless').select2({
            minimumResultsForSearch: Infinity,
            width: 240
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
    </script> @endsection