

<div class="panel-body">
    <table class="table datatable-basic">

        <thead>
            <tr>
                <th>#</th>
                <th>Serial</th>
                <th>State</th>
                <!--<th class="text-center">Assignee</th>-->
            </tr>
        </thead>
        <tbody>
            @foreach($cards as $data)
            <tr>
                <td><a>{{ $data->id }}</a></td>
                <td><a href="#">{{ $data->number }}</a></td>
                @if($data->state == 1)
                <td><span class="label label-success">Active</span></td>
                @else
                    @if(isset($data->u_id))
                        <td><a href="#" onclick="_open({{ $data->u_id }})">{{ App\Users::where('u_id', $data->u_id)->value('u_name')  }}</a></td>
                    @else
                    <td><span class="label label-danger">Inactive</span></td>
                    @endif
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- /scrollable datatable -->
<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>

<script>

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
    function _open(id, that) {
        $td_edit = $(that);
        jQuery('#modal_default .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="http://bookkeeping.dbcinfotech.net/assets/images/preloader.gif" /></div>');

        // LOADING THE AJAX MODAL
        jQuery('#timeline').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'timeline/' + id,
            success: function(response)
            {
                jQuery('#timeline .modal-body').html(response);
            }
        });
    }
</script>