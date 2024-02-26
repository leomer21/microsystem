@extends('.back-end.layouts.master')
@section('title', 'Landing Pages')
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Landing Pages</h4>
        </div>
    </div>
</div>
<!-- /page header -->


    <!-- Content area -->
    <div class="content">
        <!-- Primary modal -->
        <!--
        <div id="add_media" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Add Media</h6>
                    </div>
                    {{ Form::open(array('url' => 'add_media', 'files' => true, 'method' => 'post', 'id' => 'add')) }}

                        <div class="modal-body">
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Title</label>
                            <div class="col-lg-8">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="title" type="text" class="form-control input-xlg" placeholder="Title">
                                    <div class="form-control-feedback">
                                        <i class="icon-spell-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Template</label>
                            <div class="col-lg-8">
                                <select class="select" name="template">
                                    <option value="template1">Template 1</option>
                                    <option value="template2">Template 2</option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Description</label>
                            <div class="col-lg-8">
                                <textarea rows="5" cols="5" class="form-control" name="description" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Media</label>
                            <div class="col-lg-8">
                                <input type="file" class="file-styled" name="file">
                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-4">Publish</label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="state" class="switchery-primary" checked>
                                </label>
                            </div>
                        </div>
                        <hr>
                        </div>

                            <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="document.forms['add'].submit(); return false;">Save changes</button>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>-->
        <!-- /primary modal -->
        
        <!-- Add branch model -->
        <div id="add_branch_landing" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Add landing page for a branch</h6>
                    </div>

                    <div class="modal-body">
                        <h6 class="text-semibold"></h6>
                  
                                {{ Form::open(array('url' => 'add_branch_landing', 'files' => true, 'method' => 'post', 'id' => 'branch_landing')) }}
                                    <div class="form-group">
                                        <input type="hidden" name="template" value="branch" >
                                        <label class="col-lg-2 control-label text-semibold">Multiple file upload:</label>
                                        <div class="col-lg-10">
                                            <input type="file" class="file-input-preview" multiple="multiple" name="file[]">
                                            <span class="help-block">&nbsp</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group has-feedback-left">
                                        <label class="text-semibold col-lg-2 ">Branch</label>
                                        <div class="col-lg-8">
                                            <select class="select-fixed-single" name="branch_id">
                                                @foreach(App\Branches::get() as $valueBranches)
                                                    <!-- remove branches have a specific landing -->
                                                    @if(App\Media::where('template', 'branch_landing')->where('branch_id', $valueBranches->id)->count() > 0)
                                                    @else
                                                        <option value="{{ $valueBranches->id }}">{{ $valueBranches->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="form-control-feedback">
                                                <i class="icon-mail"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <br>    
                                {{ Form::close() }}
                                <script type="text/javascript">       
                                    $(function() {

                                        //
                                        // Define variables
                                        //

                                        // Modal template
                                        var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
                                            '  <div class="modal-content">\n' +
                                            '    <div class="modal-header">\n' +
                                            '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
                                            '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
                                            '    </div>\n' +
                                            '    <div class="modal-body">\n' +
                                            '      <div class="floating-buttons btn-group"></div>\n' +
                                            '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
                                            '    </div>\n' +
                                            '  </div>\n' +
                                            '</div>\n';

                                        // Buttons inside zoom modal
                                        var previewZoomButtonClasses = {
                                            toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
                                            fullscreen: 'btn btn-default btn-icon btn-xs',
                                            borderless: 'btn btn-default btn-icon btn-xs',
                                            close: 'btn btn-default btn-icon btn-xs'
                                        };

                                        // Icons inside zoom modal classes
                                        var previewZoomButtonIcons = {
                                            prev: '<i class="icon-arrow-left32"></i>',
                                            next: '<i class="icon-arrow-right32"></i>',
                                            toggleheader: '<i class="icon-menu-open"></i>',
                                            fullscreen: '<i class="icon-screen-full"></i>',
                                            borderless: '<i class="icon-alignment-unalign"></i>',
                                            close: '<i class="icon-cross3"></i>'
                                        };

                                        // File actions
                                        var fileActionSettings = {
                                            zoomClass: 'btn btn-link btn-xs btn-icon',
                                            zoomIcon: '<i class="icon-zoomin3"></i>',
                                            dragClass: 'btn btn-link btn-xs btn-icon',
                                            dragIcon: '<i class="icon-three-bars"></i>',
                                            removeClass: 'btn btn-link btn-icon btn-xs',
                                            removeIcon: '<i class="icon-trash"></i>',
                                            indicatorNew: '<i class="icon-file-plus text-slate"></i>',
                                            indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
                                            indicatorError: '<i class="icon-cross2 text-danger"></i>',
                                            indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
                                        };

                                        //
                                        // Always display preview
                                        //
                                        $(".file-input-preview").fileinput({
                                            browseLabel: 'Browse',
                                            browseIcon: '<i class="icon-file-plus"></i>',
                                            uploadIcon: '<i class="icon-file-upload2"></i>',
                                            removeIcon: '<i class="icon-cross3"></i>',
                                            layoutTemplates: {
                                                icon: '<i class="icon-file-check"></i>',
                                                modal: modalTemplate
                                            },
                                            initialPreview: [
                                                
                                            ],
                                            initialPreviewAsData: true,
                                            overwriteInitial: true,
                                            maxFileSize: 5120,
                                            allowedFileExtensions: ["jpg", "jpeg", "gif", "png"],
                                            previewZoomButtonClasses: previewZoomButtonClasses,
                                            previewZoomButtonIcons: previewZoomButtonIcons,
                                            fileActionSettings: fileActionSettings
                                        });

                                    });    
                                </script>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['branch_landing'].submit(); return false;">Create</button>
                                </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /primary modal -->

        <!-- Edit default landing modal -->
        <div id="modal_edit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Edit</h6>
                    </div>

                    <div class="modal-body">


                    <hr>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['edit'].submit(); return false;">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit modal -->


        <!-- Media library -->
        <div class="panel panel-white">

            @if(App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                <div class="panel-body">
                    <a href="{{ asset('/') }}builder/index.php?type=landing" target="_blank" class="btn bg-teal-400 btn-labeled"><b><i class="icon-magic-wand2"></i></b> Website Builder</a>
                    <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_branch_landing"><b><i class="icon-tree6"></i></b> Add landing page for a branch</button>
                </div>
            @endif
            <table class="table table-striped table-lg" id="media-library">

                <thead>
                    <tr>
                        <th>Title</th>
                        <th>State</th>
                        <th>Created at</th>
                        <th class="text-center">Actions</th>
                        <th></th>
                        
                    </tr>
                </thead>
            </table>

        </div>
        <!-- /media library -->
        @include('..back-end.footer')

    </div>
    <!-- /content area -->
    @section('js')
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/media/fancybox.min.js"></script>
	    <script type="text/javascript" src="assets/js/pages/gallery_library.js"></script>
        <script type="text/javascript" src="assets/js/plugins/ui/prism.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>

        <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/uploaders/fileinput.min.js"></script>




    @endsection

     <script>
        $('.select-fixed-single').select2({
            minimumResultsForSearch: Infinity,
        });
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
        var table =  $('#media-library').DataTable({
             ajax: {"url": "get_landing", type: "get", data: {_token: $('meta[name="csrf-token"]').attr('content')}},
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
                    targets: -1
                }],
            deferRender: true,
            columns: [
                // {"data": "name"},
                {"render": function ( type, full, data, meta ) {
                    if(data.name == "default")
                        return '<a href="#" title="Edit slider pictures" class="edit" >Basic slider landing page</a>';
                    else if(data.name == "branch_landing")
                        return '<a href="#" title="Edit slider pictures of '+data.branch_name+'" class="edit_branch_landing"> Landing page of <strong>'+data.branch_name+'</strong> branch </a>';
                    else
                        return 'Professional customized landing page';
                }},
                {
                    "data": "state",
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        if(data.name == "branch_landing")
                            return '<span class="label bg-success heading-text">Active</span>';
                        else
                            if (data.state == 1)
                                return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Active</span></button>';
                            else
                                return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Inactive</span></button>';
                    }
                },
                {
                    "data": "add_date",
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        return data.add_date + ' - ' + data.add_time;
                    }
                },
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        if(data.name == 'default')
                            return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                        '<li><a href="#" class="preview" ><i class="icon-presentation"></i> Preview</a></li>' +
                                        '<li><a href="#" class="edit"><i class="icon-pencil4"></i> Edit</a></li>';
                        else if(data.name == "branch_landing")
                            return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                            '<li><a href="#" class="preview_branch_landing" ><i class="icon-presentation"></i> Preview</a></li>' +
                                            '<li><a href="#" class="edit_branch_landing"><i class="icon-pencil4"></i> Edit</a></li>' +
                                            '<li><a href="#" class="delete_branch_landing"><i class="icon-trash-alt"></i> Delete</a></li>';
                        else
                            return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                        '<li><a href="#" class="preview" ><i class="icon-presentation"></i> Preview</a></li>' +
                                        '<li><a href="#" class="delete"><i class="icon-trash-alt"></i> Delete</a></li>';

                    }
                },
                {"data": null, "defaultContent": "" }
            ]
         });
        $('#media-library tbody').on('click', 'button.btn-ladda-spinner', function () {
            var data = table.row($(this).parents('tr')).data(),
                    sus = ($(this).hasClass('btn-success')) ? false : true,
                    that = this;
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            $(this).text('Loading...');
            $.ajax({
                url: 'landing_state/' + data.unique_id + '/' + sus,
                success: function (data) {
                    location.reload();
                    if (sus) {
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
                error: function () {
                    location.reload();
                    if (!sus) {
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

        $('#media-library tbody').on('click', '.preview', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            window.open("/perview_landing/" + data.unique_id, '_blank');
        });

        $('#media-library tbody').on('click', '.preview_branch_landing', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            window.open("/?identify=-"+data.database+"-"+data.branch_id+"-hotspot-ip-mac/", '_blank');
        });

        $('#media-library tbody').on('click', '.delete', function () {
                var that = this;
                swal({
                title: "Are you sure?",
                text: "You will not be able to recover landing page again!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
            },
            function (isConfirm) {

                if (isConfirm) {
                    var data = table.row($(that).parents('tr')).data();
                    if (data == null) {
                        data = table.row($(that).parents('tr').prev()).data();
                    }
                    $.ajax({
                        url: 'landing_delete/' + data.id,
                        success: function (data) {
                            table.row($(that).parents('tr')).remove().draw();
                            swal("Deleted!", "Landing page has been deleted.", "error");
                            
                        },
                        error: function () {
                            swal("Cancelled", "Landing page is safe :)", "success");
                        }
                    });
                } else {
                    swal("Cancelled", "Your delete has been Cancelled :)", "error");
                }

            });

        });

        $('#media-library tbody').on('click', '.delete_branch_landing', function () {
                var that = this;
                swal({
                title: "Are you sure?",
                text: "You will not be able to recover landing page again!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
            },
            function (isConfirm) {

                if (isConfirm) {
                    var data = table.row($(that).parents('tr')).data();
                    if (data == null) {
                        data = table.row($(that).parents('tr').prev()).data();
                    }
                    $.ajax({
                        url: 'branch_landing_delete/' + data.branch_id,
                        success: function (data) {
                            table.row($(that).parents('tr')).remove().draw();
                            swal("Deleted!", "Landing page has been deleted.", "error");
                            
                        },
                        error: function () {
                            swal("Cancelled", "Landing page is safe :)", "success");
                        }
                    });
                } else {
                    swal("Cancelled", "Your delete request has been Cancelled :)", "error");
                }

            });

        });

        $('#media-library tbody').on('click', '.edit', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_edit').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'landing_info/' + data.name,
                success: function (response) {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_edit .modal-body').html(response);

                }
            });

        });
        
        $('#media-library tbody').on('click', '.edit_branch_landing', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_edit').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'landing_info/' + data.name+'/'+data.branch_id,
                success: function (response) {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_edit .modal-body').html(response);

                }
            });

        });
         // Styled file input
        $(".file-styled").uniform({
            fileButtonClass: 'action btn bg-warning'
        });

        $(".switch").bootstrapSwitch();

        // Styled checkboxes, radios
        $(".styled").uniform({
            radioClass: 'choice'
        });
        // Default initialization
        $('.select').select2({
            minimumResultsForSearch: Infinity
        });
        var primary = document.querySelector('.switchery-primary');
        var switchery = new Switchery(primary, { color: '#2196F3' });
        // Bootstrap switch
        // ------------------------------

        $(".switch").bootstrapSwitch();
        function _edit(id, that) {
            $td_edit = $(that);
            jQuery('#modal_edit .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="http://bookkeeping.dbcinfotech.net/assets/images/preloader.gif" /></div>');

            // LOADING THE AJAX MODAL
            jQuery('#modal_edit').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'landing_info/' + id,
                success: function(response)
                {
                   jQuery('#modal_edit .modal-body').html(response);
                }
            });
        }
     </script>
@endsection