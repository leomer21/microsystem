@extends('.back-end.layouts.master')
@section('title', 'Packages')
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Internet Packages</h4>
        </div>
    </div>
</div>
<!-- /page header -->



    <!-- Primary modal -->
    <div id="add_network" class="modal fade">
        <div class="modal-dialog lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Add Packages</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold"></h6>

                    <div class="row">
                        <form action="#" class="steps-validation">

                            <h6>Network data</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Network name: <span class="text-danger">*</span></label>
                                            <select id="networkname" name="networkname" data-placeholder="Choose a Network..." class="select required">
                                                <option></option>
                                                @foreach($networks as $network)
                                                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Packages Type: <span class="text-danger">*</span></label>
                                            <select name="packages-type" data-placeholder="Choose a Packages Type..." class="select required" id="packages-type">
                                                <option></option>
                                                <option value="1" id="monthly">Monthly</option>
                                                <option value="2" id="validity">Validity</option>
                                                <option value="3" id="time">Time</option>
                                                <option value="4" id="bandwidth">Extra Quota</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h6>Packages data</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Package Name: <span class="text-danger">*</span></label>
                                            <div id="type"></div>
                                            <input type="text" name="packagename" id="packagename" placeholder="Package Name" class="form-control required">
                                        </div>
                                    </div>

                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Package Price: <span class="text-danger">*</span></label>
                                            <input type="number" id="packageprice" name="packageprice" placeholder="Package Price" class="form-control required">
                                        </div>
                                   </div>
                                   <div class="col-md-6" style="display: none;" id="packageperiodtime">
                                        <div class="form-group">
                                            <label>Package Period: <span class="text-danger">*</span></label>
                                            <div class="form-group has-feedback has-feedback-left">
                                                 <input id="packageperiod" name="packageperiod" type="text" class="form-control timepicker" >
                                                 <div class="form-control-feedback">
                                                     <i class="icon-alarm"></i>
                                                 </div>
                                            </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6" style="display: none;" id="packageperiods">
                                        <div class="form-group">
                                            <div id="label1"><label>Package Period: (Months) <span class="text-danger">*</span></label></div>
                                            <div id="label2"><label>Package Period: (Days) <span class="text-danger">*</span></label></div>
                                            <input type="number" id="packageperiod2" name="packageperiod2" placeholder="Package Period" class="form-control">
                                        </div>
                                   </div>

                                   <div class="col-md-6" style="display: none;" id="group-name">
                                        <div class="form-group">
                                            <label>Groups:<span class="text-danger">*</span></label>
                                            <select id="groupname" name="groupname"  data-placeholder="Choose a Groups..." class="select">
                                                <option></option>
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                   </div>

                                   <div class="col-md-6" style="display: none;" id="expiration">
                                        <div class="form-group">
                                            <label>Expiration after the following dayes:<span class="text-danger">*</span></label>
                                            <input type="number" id="expiration" name="expiration" placeholder="30" class="form-control">
                                        </div>
                                   </div>

                                   <div class="col-md-6" style="display: none;"  id="extra">
                                        <div class="form-group">
                                            <label>Extra Quota (GB):<span class="text-danger">*</span></label>
                                            <input type="number" id="extrab" name="extra" placeholder="Extra Quota" class="form-control">
                                        </div>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status:</label>
                                            <div class="checkbox checkbox-switchery">
                                                <label>
                                                    <input type="checkbox" id="state" name="state" class="switchery-info" checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Offer:</label>
                                            <div class="checkbox checkbox-switchery">
                                                <label>
                                                    <input type="checkbox" id="offer" name="offer" class="switchery-warning">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notes:</label>
                                            <div class="checkbox checkbox-switchery">
                                                    <textarea name="notes" id="notes" type="text" rows="3" class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>

                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['addbranch'].submit(); return false;">Add</button>
                </div>-->
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
                    <h6 class="modal-title">Edit Packages</h6>
                </div>

                <div class="modal-body">


                <hr>
                </div>

                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['edit'].submit(); return false;">Save changes</button>
                </div>-->
            </div>
        </div>
    </div>
    <!-- /primary modal -->


    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        <div class="panel panel-flat">
            <!-- <div class="panel-heading">
                <h5 class="panel-title">Internet Packages</h5>
            </div> -->

            <div class="panel-body">
            	<button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_network"><b><i class="icon-cash4"></i></b> Add Packages</button>
            </div>
            <table class="table" width="100%" id="table-network">
                <thead>
                    <tr>
                        <th>Network Name</th>
                        <th>Packages Name</th>
                        <th>Packages Type</th>
                        <th>Packages Period</th>
                        <th>Packages Price</th>
                        <th>Packages State</th>
                        <th class="text-center">Actions</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /scrollable datatable -->
        <br>
        @include('..back-end.footer')
    </div>
    @section('css')
       <style>
        .ti_tx,.mi_tx,.si_tx,.mer_tx{width:100%;text-align:center;margin:10px 0}.time,.mins,.sand,.meridian{width:60px;float:left;margin:0 10px;font-size:20px;color:#2d2e2e;font-family:arial;font-weight:700}.prev,.next1{cursor:pointer;padding:18px;width:28%;border:1px solid #ccc;margin:auto;background:url(assets/images/arrow.png) no-repeat;border-radius:5px}.prev:hover,.next1:hover{background-color:#ccc}.next1{background-position:50% 150%}.prev{background-position:50% -50%}.time_pick{position:relative}.timepicker_wrap{width:262px;padding:10px;border-radius:5px;z-index:998;display:none;box-shadow:2px 2px 5px 0 rgba(50,50,50,0.35);background:#f6f6f6;border:1px solid #ccc;float:left;position:absolute;top:27px;left:0}.arrow_top{position:absolute;top:-10px;left:20px;background:url(assets/images/top_arr.png) no-repeat;width:18px;height:10px;z-index:999}input.timepicki-input{background:none repeat scroll 0 0 #fff;border:1px solid #ccc;border-radius:5px 5px 5px 5px;float:none;margin:0;text-align:center;width:70%}a.reset_time{float:left;margin-top:5px;color:#000}
        </style>
    <link rel="stylesheet" type="text/css" href="assets/css/timepicker.css">
    @endsection
    @section('js')
	<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/forms/wizards/steps.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jasny_bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/extensions/cookie.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>

	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script>

    <script type="text/javascript" src="assets/js/timepicker.js"></script>
    <script type="text/javascript" src="assets/js/timepicki.js"></script>
    <script type="text/javascript" src="assets/js/datetime.min.js"></script>

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
       var table = $('#table-network').DataTable({
            responsive: {
            details: {
               type: 'column',
               target: -1
               }
            },
            ajax:{"url" : "packagesjson",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
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
                /*buttons: [
                    {
                        extend: 'colvisGroup',
                        text: 'Monthly Packages',
                        className: 'btn btn-default',
                        show: [0, 1, 2,4],
                        hide: [3,5]
                    },
                    {
                        extend: 'colvisGroup',
                        className: 'btn btn-default',
                        text: 'Time Packages',
                        show: [3, 4, 5],
                        hide: [0, 1, 2]
                    },
                    {
                        extend: 'colvisGroup',
                        className: 'btn btn-default',
                        text: 'Validity Packages',
                        show: [3, 4, 5],
                        hide: [0, 1, 2]
                    },
                    {
                        extend: 'colvisGroup',
                        className: 'btn btn-default',
                        text: 'Bandwidth Packages',
                        show: [3, 4, 5],
                        hide: [0, 1, 2]
                    },
                    {
                        extend: 'colvisGroup',
                        className: 'btn btn-default',
                        text: 'Show all',
                        show: ':hidden'
                    }
                ]*/
            },
                columnDefs: [
                    {
                        className: 'control',
                        orderable: false,
                        targets:   -1
                    }],
                deferRender: true,
            columns:[ 
            { "data": "network_id"},
            { "data": "name" },
            { "data": "type" ,
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.type == 1)
                return "Monthly";
              else if(data.type == 2)
                return "Validity";
              else if(data.type == 3)
                return "Time";
              else if(data.type == 4)
                return "Extra Quota";
              else
                return " ";
            }},
            { "data": "period",
                "searchable": false,
                "render": function ( type, full, data, meta ) {
                if(data.type == 3)
                    return gmdate('H:i:s', data.period);
                else
                    return data.period;
             }},
            { "data": "price"},
            { "data": "state",
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.state == 1)
              return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Active</span></button>';
              else
              return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Inactive</span></button>';

            }},
            { "data": null, "defaultContent":'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
             '<li><a href="#" class="edit" ><i class="icon-pencil3"></i> Edit</a></li>' +
             '<li><a href="#" class="delete"><i class="icon-cross3"></i> Delete</a></li>' +
             '</ul> </li> </ul>'},
            { "data":null,"defaultContent":"" }
            ]
        });
        // Alert combination
        $('#table-network tbody').on( 'click', '.delete', function () {
           var that = this;
           swal({
             title: "Are you sure?",
             text: "You will not be able to recover this data again!",
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
                    url:'delete_packages/'+data.id,
                     success:function(data) {
                         table.row( $(that).parents('tr') ).remove().draw();
                         swal("Deleted!", "Your data has been deleted.", "success");
                     },
                     error:function(){
                         swal("Cancelled", "Your data is safe :)", "error");

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
                url: 'packages/' + data.id,
                success: function(response)
                {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_ajax .modal-body').html(response);

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
                       url:'packages_state/' + data.id + '/' + sus,
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
	//
    // Wizard with validation
    //

    // Show form
    var form = $(".steps-validation").show();


    // Initialize wizard
    $(".steps-validation").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="number">#index#</span> #title#',
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex) {

            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }

            // Forbid next action on "Warning" step if the user is to young
            if (newIndex === 3 && Number($("#age-2").val()) < 18) {

                return false;
            }

            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {

                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },

        onStepChanged: function (event, currentIndex, priorIndex) {

            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {


                form.steps("next");
            }

            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                
				form.steps("previous");
            }
        },

        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },

        onFinished: function (event, currentIndex) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if(document.querySelector("#packagestype")){var packagestype = document.querySelector("#packagestype").value;}
            if(document.querySelector("#packagename")){var packagename = document.querySelector("#packagename").value;}
            if(document.querySelector("#packageprice")){var packageprice = document.querySelector("#packageprice").value;}
            if(document.querySelector("#packageperiod")){var packageperiod = document.querySelector("#packageperiod").value;}
            if(document.querySelector("#packageperiod2")){var packageperiod2 = document.querySelector("#packageperiod2").value;}

            if(document.querySelector("#groupname")){var groupname = document.querySelector("#groupname").value;}
            if(document.querySelector("#networkname")){var networkname = document.querySelector("#networkname").value;}
            if(document.querySelector("#expiration")){var expiration = document.querySelector("#expiration").value;}
            if(document.querySelector("#extrab")){var extra = document.querySelector("#extrab").value;}
            if(document.querySelector("#notes")){var notes = document.querySelector("#notes").value;}
            if(document.querySelector("#state")){var state = document.querySelector("#state").checked;}
            if(document.querySelector("#offer")){var offer = document.querySelector("#offer").checked;}

            //if(state == "on"){var state = 1;}else{var state = 0; }
            $.ajax({
                'url':'addpackages',
                'data':{_token: CSRF_TOKEN,packagestype:packagestype,offer:offer,packagename:packagename,packageprice:packageprice,packageperiod:packageperiod,packageperiod2:packageperiod2,groupname:groupname,networkname:networkname,expiration:expiration,extra:extra,notes:notes,state:state},
                'type':'post',
                success:function(data) {
                    new PNotify({
                        title: 'Success',
                        text: 'Package has been created successfully.',
                        addclass: 'alert bg-success alert-styled-right',
                        type: 'error'
                    });
                    location.reload();
                },
                error:function(data) {
                    new PNotify({
                        title: 'Oops!!',
                        text: 'Filed creation, please check your fileds.',
                        addclass: 'alert bg-danger alert-styled-right',
                        type: 'error'
                    });
                }
            });
        }
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
        width: 353
    });
    $('.select-fixed-single200').select2({
            minimumResultsForSearch: Infinity,
            width: 353
        });
    $('.select-fixed-single100').select2({
        minimumResultsForSearch: Infinity,
        width: 120
    });
    var info = document.querySelector('.switchery-info');
    var switchery = new Switchery(info, { color: '#00BCD4'});

    var warning = document.querySelector('.switchery-warning');
    var switchery = new Switchery(warning, { color: '#FF7043' });

    // Info
    $(".control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });
    // Warning
    $(".control-warning").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-warning-600 text-warning-800'
    });
    // Checkboxes
    $(".styled").uniform({
           radioClass: 'choice'
       });
    $('.timepicker').timepicki({
        show_meridian:false,
        min_hour_value:0,
        max_hour_value:23,
        overflow_minutes:true,
        increase_direction:'up',
        disable_keyboard_mobile: true
    });
    // Select2 selects
    $('.select').select2();
    $("#packages-type").change(function(){
            var packagestype = $("#packages-type option:selected").val();
            if(packagestype == 1){
                $('#label1').show();
                $('#label2').hide();
                $('#type').append('<input id="packagestype" name="packagestype" type="hidden" value="1">');
                $('#expiration').hide();
                $('#extra').hide();
                $('#group-name').show();
                $('#packageperiods').show();
                $('#packageperiodtime').hide();
            }
            else if(packagestype == 2){
                $('#label2').show();
                $('#label1').hide();
                $('#type').append('<input id="packagestype" name="packagestype" type="hidden" value="2">');
                $('#expiration').hide();
                $('#extra').hide();
                $('#group-name').show();
                $('#packageperiods').show();
                $('#packageperiodtime').hide();
            }
            else if(packagestype == 3){
                $('#type').append('<input id="packagestype" name="packagestype" type="hidden" value="3">');
                $('#expiration').show();
                $('#extra').hide();
                $('#label1').hide();
                $('#label2').hide();
                $('#packageperiodtime').show();
                $('#group-name').show();
                $('#packageperiods').hide();
            }
            else if(packagestype == 4){
                $('#type').append('<input id="packagestype" name="packagestype" type="hidden" value="4">');
                $('#group-name').hide();
                $('#expiration').hide();
                $('#label1').hide();
                $('#label2').hide();
                $('#packageperiods').hide();
                $('#packageperiodtime').hide();
                $('#extra').show();
            }
            else{}

    });


    </script>
@endsection