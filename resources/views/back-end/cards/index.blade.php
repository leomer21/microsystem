@extends('.back-end.layouts.master')
@section('title', 'Cards')
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Cards</h4>
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
                    <h6 class="modal-title">Add Package cards</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold"></h6>

                    <div class="row">
                        <form action="#" class="steps-validation">

                            <h6>Package data</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Package price: <span class="text-danger">*</span></label>
                                            <input type="number" name="cardprice" id="cardprice" placeholder="Card price" class="form-control required">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total cards: <span class="text-danger">*</span></label>
                                            <div id="type"></div>
                                            <input type="number" name="counter" id="counter" placeholder="Total cards" class="form-control required">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h6>Options</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start date:</label>
                                            <div id="type"></div>
                                            <input type="text" name="startdate" id="startdate" placeholder="Start date" class="form-control pickadate">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>End date:</label>
                                            <div id="type"></div>
                                            <input type="text" name="enddate" id="enddate" placeholder="End date" class="form-control pickadate">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status:</label>
                                            <div class="checkbox checkbox-switchery">
                                                <label>
                                                    <input type="checkbox" id="state" name="state" class="switchery-info" checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
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
        <div id="modal_list" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Cards list</h6>
                    </div>

                    <div class="modal-body">


                    <hr>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- /primary modal -->

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

    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        <div class="panel panel-flat">
            <!-- <div class="panel-heading">
                <h5 class="panel-title">Cards Table</h5>
            </div> -->

            <div class="panel-body">
            	<button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_network"><b><i class="icon-puzzle2"></i></b> Generate cards</button>
            </div>
            <table class="table" width="100%" id="table-network">
                <thead>
                    <tr>
                        <th>Creation Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Price</th>
                        <th>Count</th>
                        <th>Package state</th>
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

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/forms/wizards/steps.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jasny_bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/extensions/cookie.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/anytime.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>

	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script>



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
            ajax:{"url" : "getcards",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
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
            { "data": "date" },
            { "data": "from" },
            { "data": "to" },
            { "data": "price" },
            { "data": "count"},
            { "data": "state",
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.state == 1)
              return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Active</span></button>';
              else
              return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Inactive</span></button>';

            }},
            { "data": null, "defaultContent":'<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
             '<li><a href="#" class="list" ><i class="icon-search4"></i> Search</a></li>' +
             '<li><a href="#" class="export"><i class="icon-file-excel"></i> Export to Excel</a></li>'+
             '<li><a href="#" class="delete"><i class="icon-cross3"></i> Delete</a></li>' +
             '</ul> </li> </ul>'
            },
            { "data":null,"defaultContent":"" }
            ]
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
                        url:'deletecards/' + data.from + '/' + data.to + '/' + data.h_id,
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
       $('#table-network tbody').on( 'click', '.export', function () {
           var that = this;
           var data = table.row( $(that).parents('tr') ).data();
           if(data  == null){
              data = table.row( $(that).parents('tr').prev() ).data();
           }
           var productLink = 'exportcards/' + data.from + '/' + data.to;

           //productLink.attr("target", "_blank");
           window.open(productLink);

           return false;
           // SHOW AJAX RESPONSE ON REQUEST SUCCESS
           /*$.ajax({
               url: 'exportcards/' + data.from + '/' + data.to,
               success: function(response)
               {

               }
           });*/

       });
       $('#table-network tbody').on( 'click', '.list', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
            if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
            }
            //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_list').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'getcardlist/' + data.from + '/' + data.to,
                success: function(response)
                {
                   //console.log( response );
                   //$('.id').append(data.id);
                   jQuery('#modal_list .modal-body').html(response);

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
              url:'statecards/' + data.from + '/' + data.to + '/' + sus + '/' + data.h_id,
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

            $.ajax({
                'url':'addcards',
                'data':{_token: CSRF_TOKEN,
                        price: $('#cardprice').val(),
                        startdate: $('#startdate').val(),
                        enddate: $('#enddate').val(),
                        cardscount: $('#counter').val(),
                        state: $('#state').val()
                },
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
    // Checkboxes
       $(".styled").uniform({
           radioClass: 'choice'
       });
    // Select2 selects
    $('.select').select2();
    $('.select-fixed-singles').select2({
          minimumResultsForSearch: Infinity,
          width: 75
      });
    // Basic options
    $('.pickadate').pickadate({
        format: 'yyyy/mm/dd'
    });

    var info = document.querySelector('.switchery-info');
    var switchery = new Switchery(info, { color: '#00BCD4'});
    // Info
    $(".control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });

    </script>
@endsection