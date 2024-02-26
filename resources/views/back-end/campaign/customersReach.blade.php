<!-- Scrollable datatable -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Customers Reach</h5>
        </div>

        <div class="panel-body">
        </div>
        <table class="table" width="100%" id="table-customersReach">
            <thead>
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                @if($campaign_type == "survey")
                    <th>Survey Result</th>
                    <th>User Reply</th>
                @endif
                <th>Reach date</th>
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
        @foreach($reach_value as $value)
            <?php 
            $users = App\Users::where('u_id', $value->u_id)->first(); 
            if( $campaign_type=="survey" ){

                // Add 24 Hours after reach time to get all results of the reach with in next 24 hours
                $add24HoursToReachTime = date('Y-m-d H:i:s', strtotime('+24 hours', strtotime($value->created_at)));

                // get choice name in case pool or rating survey
                if($survey_type == "poll"){
                    $surveyResultStep1 = App\Models\Survey::where('campaign_id', $value->campaign_id)->where('u_id', $value->u_id)->whereBetween('created_at', [$value->created_at, $add24HoursToReachTime])->first();
                    if(isset($surveyResultStep1)){
                        $surveyResult = App\Models\Survey::where('id', $surveyResultStep1->options)->value('options');
                        $surveyResultID = $surveyResultStep1->options;
                    }else{
                        $surveyResult = "";
                        $surveyResultID = "";
                    }
                }else{ // Rating
                    $surveyResultData = App\Models\Survey::where('campaign_id', $value->campaign_id)->where('u_id', $value->u_id)->whereBetween('created_at', [$value->created_at, $add24HoursToReachTime])->first();
                    if(isset($surveyResultData)){
                        $surveyResult = $surveyResultData->options;
                        $masterSurveyData = App\Models\Survey::where('campaign_id', $value->campaign_id)->whereNull('u_id')->where('options',$surveyResult)->first();
                        $surveyResultID = $masterSurveyData->id;
                    }else{
                        $surveyResult = "";
                        $surveyResultID = "";
                    }
                }
 
                // get customer reply from History table
                // echo "campaign_id: $value->campaign_id - surveyResultID: $surveyResultID - surveyResult: $surveyResult";
                $customerReply = App\History::where('details', $value->campaign_id)->where('operation', 'whatsapp_survey_user_reply')->where('type2', $surveyResultID)->where('u_id', $value->u_id)->orderBy('id','desc')->value('notes');
                if(!isset($customerReply)){ $customerReply = ""; }

            }
            ?>
            @if(isset($users) && isset($surveyResult))
                ['{{ $users->u_name }}', '{{ $users->u_phone }}', '{{ $surveyResult }}','{{ $customerReply }}','{{ $value->created_at }}'], 
            @elseif( isset($users) )
                ['{{ $users->u_name }}', '{{ $users->u_phone }}', '{{ $value->created_at }}'], 
            @else
                ['Deleted ( User ID: {{$value->u_id}})',' ' ,' ' , '  ','{{ $value->created_at }}'],
            @endif
            <?php 
            unset($surveyResultID);
            unset($surveyResult);
            unset($customerReply);
            ?>
        @endforeach
    ];


    // Basic initialization
    var table = $('#table-customersReach').DataTable({
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
                {extend: 'print', text: '<i title="Print" class="icon-printer"></i>'}
            ]
        },
        data: dataSet,
        columnDefs: []

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