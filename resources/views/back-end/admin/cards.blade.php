<!-- Small modal -->
    <div id="timeline" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Timeline</h6>
            </div>

            <div class="modal-body">

                <hr>
            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- /small modal -->
<!-- Small modal -->
    <div id="cardlist" class="modal fade">
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
            </div>
        </div>
    </div>
</div>
<!-- /small modal -->
<!-- Small modal -->
<div id="add_cards" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Card Package</h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <form action="{{ url('reseller_add_cards') }}" method="post" id="add">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">To</label>
                            <div class="col-lg-9">
                                <input type="hidden" name="admin_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="reseller_id" value="{{ $id }}">
                                <input name="to" type="text" class="form-control maxlength" maxlength="10" placeholder="Bootstrap Maxlength">
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">From</label>
                            <div class="col-lg-9">
                                <input name="from" type="text" class="form-control maxlength" maxlength="10" placeholder="Bootstrap Maxlength">
                            </div>
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

<button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_cards"><b><i class="icon-user"></i></b> Add Card Package</button>
<div class="panel-body">
    <table class="table datatable2-basic">
        <thead>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Count</th>
                <th>State</th>
				<th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resellers as $reseller)
            <?php
                $split = explode(';',$reseller->details);?>
                <tr>
                    <td><a href="#">{{ $split[0] }}</a></td>
                    <td><a href="#">{{ $split[1] }}</a></td>
                    <td><a href="#">{{ ($split[1]-$split[0]) }}</a></td>
                    @if($reseller->notes == 1)
                    <td><span class="label label-success">Active</span></td>
                    @else
                    <td><span class="label label-danger">Inactive</span></td>
                    @endif
                    <td>
                        <ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#" onclick="_cardlist('{{$split[0]}}','{{$split[1]}}')"><i class="icon-file-pdf"></i> Search</a></li>
                                    <li><a href="#" onclick="_delete({{$reseller->id}})"><i class="icon-trash-alt"></i> Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>
<!-- /scrollable datatable -->
<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/maxlength.min.js"></script>
<script>
    $('.maxlength').maxlength();
    // Basic datatable
    $('.datatable2-basic').DataTable();

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
    function _cardlist(from, to, that) {
        $td_edit = $(that);
        jQuery('#cardlist .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="http://bookkeeping.dbcinfotech.net/assets/images/preloader.gif" /></div>');

        // LOADING THE AJAX MODAL
        jQuery('#cardlist').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'getcardlist/' + from + '/' + to,
            success: function(response)
            {
                jQuery('#cardlist .modal-body').html(response);
            }
        });
    }
    function _delete(id, that) {
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
                    url:'reseller_delete_cards/'+id,
                     success:function(data) {
                         location.reload();
                         table.row( $(that).parents('tr') ).remove().draw();
                         swal("Deleted!", "Your card package has been deleted.", "success");
                     },
                     error:function(){
                         swal("Cancelled", "Your card package is safe :)", "error");
                     }
                 });
             } else {
                 swal("Cancelled", "Your Cancelled :)", "success");
             }
       });
    }
</script>