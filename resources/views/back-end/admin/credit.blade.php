<!-- Small modal -->
<div id="add_credit" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Credit</h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <form action="{{ url('reseller_add_credit') }}" method="post" id="add">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                       <div class="col-lg-12">
                            <label class="control-label col-lg-3">Credit</label>
                            <input type="hidden" name="admin_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="reseller_id" value="{{ $id }}">
                            <input name="credit" type="number" class="form-control" maxlength="10" placeholder="Credit">
                        </div>
                    </form>
                </div>
                <hr>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="document.forms['add'].submit(); return false;">Add</button>
            </div>
        </div>
    </div>
</div>
<!-- /small modal -->

<button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_credit"><b><i class="icon-user"></i></b> Add Credit</button>
<div class="panel-body">
    <table class="table datatable-basic">
        <thead>
            <tr>
                <th>Date</th>
                <th>Credit</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resellers as $reseller)
                <tr>
                    <td><a href="#">{{ $reseller->add_date }}</a></td>
                    <td><a href="#">{{ $reseller->details }}</a></td>
                    <td>
                        <ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#" onclick="_delete('{{$reseller->id}}','{{$reseller->reseller_id}}', '{{ $reseller->details }}')"><i class="icon-trash-alt"></i> Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- /scrollable datatable -->
<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script>
    $('.maxlength').maxlength();
    // Basic datatable
    $('.datatable-basic').DataTable();

    // Alternative pagination
    $('.datatable-pagination').DataTable({
        pagingType: "simple",
        language: {
            paginate: {'next': 'Next &rarr;', 'previous': '&larr; Prev'}
        }
    });


    // Datatable with saving state
    $('.datatable-save-state').DataTable({
        stateSave: true
    });


    // Scrollable datatable
    $('.datatable-scroll-y').DataTable({
        autoWidth: true,
        scrollY: 300
    });

    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    function _delete(id, reseller_id, credit,  that) {
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
                    url:'reseller_delete_credit/'+ id + '/' + reseller_id + '/' + credit,
                     success:function(data) {
                         location.reload();
                         table.row( $(that).parents('tr') ).remove().draw();
                         swal("Deleted!", "Your credit has been deleted.", "success");
                     },
                     error:function(){
                         swal("Cancelled", "Your credit is safe :)", "error");
                     }
                 });
             } else {
                 swal("Cancelled", "Your Cancelled :)", "success");
             }
       });
    }
</script>