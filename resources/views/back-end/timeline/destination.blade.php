

<div class="panel-body">
    <table class="table datatable-basic">
        <thead>
            <tr>
                @if($urlFilterType=="URL")
                    <th>Visit Time</th>
                    <th>Method</th>
                    <th>Destination</th>
                    <th>IP Address</th>
                    <th>Session start</th>
                    <th>Session stop</th>
                    <th class="text-center"></th>
                @else
                    <th>Visit Time</th>
                    <th>Mac</th>
                    <th>Src.IP/port</th>
                    <th>Dst.IP</th>
                    <th>Dst.Port</th>
                    <th>Protocol</th>
                    <th>Session start</th>
                    <th>Session stop</th>
                    <th class="text-center"></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if($urlFilterType=="URL")

                @foreach($destinations as $data)
                    <tr>
                        <td>{{ $data->ReceivedAt }}</td>
                        <td>{{ explode(' ', $data->Message)[1] }}</td>
                        <td>{{ explode(' ', $data->Message)[2] }}</td>
                        <td>{{ $data->framedipaddress }}</td>
                        <td>{{ $data->acctstarttime }}</td>
                        <td>{{ $data->acctstoptime }}</td>
                        <td></td>
                    </tr>
                @endforeach
            @else
                @foreach($destinations as $data)
                <?php
                // get mac address
                $value=explode('src-mac ',$data->Message);
                if(isset($value[1])){// make sure this record is IP not URL
                    $value2=explode(',',$value[1]);
                    $macRecord=$value2[0];
                    // get connection type
                    $typeValue=explode('in:',$data->Message);
                    $typeValue2=explode(' ',$typeValue[1]);
                    if($typeValue2=="IN"){$type=2;}else{$type=1;}
                    // get protocol
                    $protocolValue=explode('proto ',$data->Message);
                    $protocolValue=explode(', ',$protocolValue[1]);
                    $protocol=$protocolValue[0];
                    if (strpos($protocolValue[1],")") !== false) {
                    // found
                    $protocol=$protocolValue[0].$protocolValue[1];
                    $protocolValue[1]=$protocolValue[2];
                    }
                    // get src address and port
                    $srcipTypeValue=explode('->',$protocolValue[1]);
                    $srcipTypeValue=explode(':',$srcipTypeValue[0]);
                    $src_ip=$srcipTypeValue[0];
                    if(isset($srcipTypeValue[1])){$src_port=$srcipTypeValue[1];}else{$src_port="";}
                    // get dst address and port
                    $dstipTypeValue=explode('->',$protocolValue[1]);
                    $dstipTypeValue=explode(':',$dstipTypeValue[1]);
                    $dst_ip=$dstipTypeValue[0];
                    if(isset($dstipTypeValue[1])){$dst_port=$dstipTypeValue[1];}else{$dst_port="";}
                }
                
                ?>
                <tr>
                    <td>{{ $data->ReceivedAt }}</td>
                    <td><a target='_blank' href="http://www.coffer.com/mac_find/?string={{$macRecord}}">{{$macRecord}}</a></td>
                    <td>{{$src_ip}}:{{$src_port}}</td>
                    <td><a target='_blank' href="http://whatismyipaddress.com/ip/{{$dst_ip}}">{{$dst_ip}}</a></td>
                    <td>{{$dst_port}}</td>
                    <td>{{$protocol}}</td>
                    <td>{{ $data->acctstarttime }}</td>
                    <td>{{ $data->acctstoptime }}</td>
                    <td></td>
                </tr>
                @endforeach
            @endif
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
        jQuery('#modal_default .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/loading-ttcredesign.gif" /></div>');

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