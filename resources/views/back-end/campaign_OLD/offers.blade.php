<!-- Scrollable datatable -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Offers Table</h5>
        </div>

        <div class="panel-body">
        </div>
        <table class="table" width="100%" id="table-network">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>Mobile</th>
                <th>Offer code</th>
                <th>Offer state</th>
                <th class="text-center">Admin</th>
            </tr>
            </thead>
        </table>
    </div>
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
    // Javascript sourced data
    var dataSet = [
        @foreach($offers as $value)
         <?php $users = App\Users::where('u_id', $value->u_id)->first(); ?>
        ['{{ $value->id }}','{{ $users->u_name }}','{{ $users->u_uname }}' , '{{ $users->u_phone }}','{{ $value->offer_code }}','@if($value->state == 1) <button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Delivered</span></button>@else<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Pending</span></button>@endif','@if(isset($value->a_id)) {{ Auth::user($value->a_id)->name }} @else None @endif'],
        @endforeach
    ];


    // Basic initialization
    var table = $('#table-network').DataTable({
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
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
        data: dataSet,
        columnDefs: []

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
            url: 'offer_state/' + data[0] + '/' + sus,
            success:function(data) {
                if(sus) {
                    $(that).text('Delivered');
                    $(that).removeClass('btn-danger');
                    $(that).addClass('btn-success');

                }
                else {
                    $(that).text('Pending');
                    $(that).removeClass('btn-success');
                    $(that).addClass('btn-danger');
                }
            },
            error:function(){
                if(!sus) {
                    $(that).text('Delivered');
                    $(that).removeClass('btn-danger');
                    $(that).addClass('btn-success');
                }
                else {
                    $(that).text('Pending');
                    $(that).removeClass('btn-success');
                    $(that).addClass('btn-danger');
                }

            }
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