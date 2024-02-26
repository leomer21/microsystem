@extends('..back-end.layouts.master')
@section('title', 'Administration')
@section('content')
    <!-- Page header -->
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Administration</h4>
            </div>
        </div>
    </div>
    <!-- /page header -->

    <!-- Primary modal -->
    <div id="add_admin" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Add Administrator</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold"></h6>

                    <div class="row">
                    <form action="{{ url('/addadmin') }}" method="POST" id="addadmin" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="form-group col-lg-12 {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-3">Name</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="name" type="text" class="form-control input-xlg" placeholder="">
                                <div class="form-control-feedback">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="email" type="text" class="form-control input-xlg" placeholder="">
                                <div class="form-control-feedback">
                                    <i class="icon-mention"></i>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-3">Password <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="password" type="password" class="form-control input-xlg" placeholder="">
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-3">Confirm Password <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="password_confirmation" type="password" class="form-control input-xlg" placeholder="">
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback-left col-lg-12">
                         <div class="col-lg-3"></div>
                         <div class="col-lg-3">
                            <label class="text-semibold">Gender</label>
                             <select class="select-fixed-single" name="gender">
                                 <option value="1">Male</option>
                                 <option value="0">Female</option>
                             </select>
                             <div class="form-control-feedback">
                                 <i class="icon-mail"></i>
                             </div>
                         </div>
                         <div class="col-lg-2"></div>
                         <div class="col-lg-4">
                             <label class="text-semibold">Type</label>
                             <div class="checkbox checkbox-switch typessss">
                                 <label>
                                     <input name="type" type="checkbox" class="switch"  data-on-text="Admin" data-off-text="Reseller" data-on-color="default" data-off-color="danger" checked="checked">
                                 </label>
                             </div>
                         </div>
                    </div>
                    <div class="form-group has-feedback-left col-lg-12 branches" style="display: none;">
                         <div class="col-lg-3"></div>
                         <div class="col-lg-9">
                            <label class="text-semibold">Branches</label>
                            <select class="bootstrap-select0 select-all-values0" multiple="multiple" data-width="100%" name="branches[]">
                                @foreach($branches as $branche)
                                    <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                @endforeach
                            </select>
                         </div>
                    </div>
                    <div class="form-group has-feedback-left col-lg-12 branches" style="display: none;">
                         <div class="col-lg-3"></div>
                         <div class="col-lg-9">
                              <div class="input-group-btn">
                                  <button type="button" class="btn btn-info" id="select-all-values0">Select all</button>
                                  <button type="button" class="btn btn-default" id="deselect-all-values0">Deselect all</button>
                              </div>
                         </div>
                    </div>
                    <div class="form-group has-feedback-left col-lg-12 permissions">
                         <div class="col-lg-3"></div>
                         <div class="col-lg-9">
                            <label class="text-semibold">Permission</label>
                            <select class="bootstrap-select select-all-values" multiple="multiple" data-width="100%" name="permissions[]">
                                <option value="dashboard">Dashboard</option>
                                <option value="users">Users</option>
                                <option value="onlineusers">Online users</option>
                                <option value="groups">Groups</option>
                                <option value="branches">Branches</option>
                                <option value="administration">Administration</option>
                                <option value="packages">Packages</option>
                                <option value="cards" >Cards</option>
                                <option value="campaign">Campaigns</option>
                                <option value="landingpage">Landing Pages</option>
                                <option value="settings">Settings</option>
                                <option value="WAadmin">WhatsApp Administrator</option>
                                <option value="WAregPoints">WhatsApp Loyalty Register Points</option>
                                <option value="WAredeemPoints">WhatsApp Loyalty Redeem Points</option>
                                
                            </select>
                         </div>
                    </div>
                    <div class="form-group has-feedback-left col-lg-12 permissions">
                         <div class="col-lg-3"></div>
                         <div class="col-lg-9">
                              <div class="input-group-btn">
                                  <button type="button" class="btn btn-info" id="select-all-values">Select all</button>
                                  <button type="button" class="btn btn-default" id="deselect-all-values">Deselect all</button>
                              </div>
                         </div>
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Mobile <span class="text-danger">*</span> </label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="phone" type="text" class="form-control input-xlg" placeholder="201012345678">
                                <span class="help-block"> Please enter WhatsApp numner with country code ex.(201012345678) </span>
                                <div class="form-control-feedback">
                                    <i class="icon-mobile"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Address</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="address" type="text" class="form-control input-xlg" placeholder="">
                                <div class="form-control-feedback">
                                    <i class="icon-home"></i>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Notes</label>
                        <div class="col-lg-9">
                            <textarea name="notes" type="text" rows="3" class="form-control input-xlg"></textarea>
                        </div>
                    </div>

                    </form>
                    </div>

                     <hr>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="document.forms['addadmin'].submit(); return false;">Save</button>
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
                <h6 class="modal-title">Edit Administrator</h6>
            </div>

            <div class="modal-body">


                 <hr>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.forms['editadmin'].submit(); return false;">Save</button>
            </div>
        </div>
    </div>
    </div>
    <!-- /primary modal -->

    <!-- Primary modal -->
    <div id="modal_cards" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reseller card Package</h6>
            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
    </div>
    <!-- /primary modal -->

    <!-- Primary modal -->
    <div id="modal_credit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reseller credit</h6>
            </div>

            <div class="modal-body">
            </div>
        </div>
    </div>
    </div>
    <!-- /primary modal -->

    <!-- Primary modal -->
    <div id="modal_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reseller revenue payment</h6>
            </div>

            <div class="modal-body">
            </div>
        </div>
    </div>
    </div>
    <!-- /primary modal -->




    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        <div class="panel panel-flat">
            <!-- <div class="panel-heading"> -->
                <!-- <h5 class="panel-title">Administration Panel</h5> -->
            <!-- </div> -->

            <div class="panel-body">
                 <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_admin"><b><i class="icon-user"></i></b> Add Administrator</button>
            </div>
            <table class="table" width="100%" id="table-network">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Reseller</th>
                        <th class="text-center">Actions</th>
                        <th class="text-center"></th>
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
	<script type="text/javascript" src="assets/js/plugins/forms/inputs/maxlength.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script>

    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>


	@endsection
    <script>
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
        $('.datatable-button-init-basic').DataTable({
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                ajax:{"url" : "adminjson",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
                buttons: [
                    {extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel'},
                    {extend: 'pdf'},
                    {extend: 'print'}
                ]
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
            ajax:{"url" : "adminjson",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
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
            { "data": null,
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.type == 2)
              return '<span class="label border-left-danger label-striped">Reseller</span><a href="#" title="Edit" class="edit" > ' + data.name + '</a>';
              else
              return '<span class="label border-left-success label-striped">Admin</span> <a href="#" title="Edit" class="edit" >   ' + data.name + '</a>';
            }},
            { "data": "email" },
            { "data": "mobile" },
            { "data": null,
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.type == 2)

              return 'Credit ' + data.credit + ' ,Paid Payments ' + data.payment + ' ,Remaining ' + data.remaining ;
              else
              return '<span class="label border-left-success label-striped">Admin</span>';
            }},
            { "data": null,
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.type == 2)
              return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                          '<li><a href="#" class="edit" ><i class="icon-pencil4"></i> Edit</a></li>' +
                                          '<li><a href="#" class="payment" ><i class="icon-wallet"></i> Revenue payment</a></li>' +
                                          '<li><a href="#" class="credit" ><i class="icon-cash"></i> Credit</a></li>' +
                                          '<li><a href="#" class="cards" ><i class="icon-puzzle2"></i> Cards Package</a></li>' +
                                          '<li><a href="#" class="delete"><i class="icon-trash-alt"></i> Delete</a></li>';
              else
              return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                          '<li><a href="#" class="edit" ><i class="icon-pencil4"></i> Edit</a></li>' +
                                          '<li><a href="#" class="delete"><i class="icon-trash-alt"></i> Delete</a></li>';

            }},
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
             text: "You will not be able to recover this imaginary file!",
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
                    url:'delete_admin/'+data.id,
                     success:function(data) {
                         table.row( $(that).parents('tr') ).remove().draw();
                         swal("Deleted!", "Your imaginary file has been deleted.", "success");
                     },
                     error:function(){
                         swal("Cancelled", "Your imaginary file is safe :)", "error");

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
                url: 'getadmin/' + data.id,
                success: function(response)
                {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_ajax .modal-body').html(response);

                }
            });
        });
        $('#table-network tbody').on( 'click', '.payment', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
             //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_payment').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'reseller_payment/' + data.id,
                success: function(response)
                {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_payment .modal-body').html(response);

                }
            });
        });
        $('#table-network tbody').on( 'click', '.credit', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
             //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_credit').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'reseller_credit/' + data.id,
                success: function(response)
                {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_credit .modal-body').html(response);

                }
            });
        });
        $('#table-network tbody').on( 'click', '.cards', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
             //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_cards').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'reseller_cards/' + data.id,
                success: function(response)
                {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_cards .modal-body').html(response);

                }
            });
        });
    // Styled checkboxes and radios
    $('.multiselect').multiselect({
       onChange: function() {
           $.uniform.update();
       }
    });
    $(".switch").bootstrapSwitch();
    $('.typessss').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.branches').show();
            $('.permissions').hide();
        }else{
            $('.branches').hide();
            $('.permissions').show();

        }
    });

    // Basic select
    $('.bootstrap-select').selectpicker();

    // Select all method
    $('#select-all-values').on('click', function() {
        $('.select-all-values').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values').on('click', function() {
        $('.select-all-values').selectpicker('deselectAll');
    });
    // Basic select
    $('.bootstrap-select0').selectpicker();

    // Select all method
    $('#select-all-values0').on('click', function() {
        $('.select-all-values0').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values0').on('click', function() {
        $('.select-all-values0').selectpicker('deselectAll');
    });
    // Scrollable datatable
        $('.datatable-scroll-y').DataTable({
        autoWidth: true,
        scrollY: 200
    });
        $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    // Fixed width. Single select
    $('.select-fixed-single').select2({
       minimumResultsForSearch: Infinity,
       width: 165
    });

    // Format icon
    function iconFormat(icon) {
        var originalOption = icon.element;
        if (!icon.id) { return icon.text; }
        var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

        return $icon;
    }
    // Initialize with options
    $(".select-icons").select2({
        templateResult: iconFormat,
        minimumResultsForSearch: Infinity,
        templateSelection: iconFormat,
        escapeMarkup: function(m) { return m; }
    });

    </script>
@endsection