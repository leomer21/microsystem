
    
<h6 class="text-semibold"></h6>

<div class="row">
    <form action="{{ url('editWhatsappIntegration/'.$whatsapp->id) }}" method="POST" id="editWhatsappIntegration" class="form-horizontal">

        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="customer_id" value="{{$whatsapp->customer_id}}">
        <input type="hidden" name="integration_type" value="{{$whatsapp->integration_type}}">
        
        <div class="panel-group panel-group-control content-group-lg" id="accordion-control">

            <!-- basic -->
            <div class=" panel-white">
                
                <div class="panel-body">
                @if($whatsapp->integration_type == "5")
                    <div class="alert alert-info alert-styled-left alert-bordered">
                        Don't worry, by clicking <span class="text-semibold">Save changes </span> the webhook will set again automatically.
                    </div>
                @endif
                    <!-- <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Provider</label>
                        <div class="col-lg-8">
                            <select class="selectt" name="integration_type">
                                <option @if($whatsapp->integration_type == '5') selected @endif value="5">Mikofi.com</option>
                                <option @if($whatsapp->integration_type == '2') selected @endif value="2">ChatApi.com</option>
                                <option @if($whatsapp->integration_type == '3') selected @endif value="3">Mercury.chat</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">State</label>
                        <div class="col-lg-8">
                            <select class="selectt" name="state">
                                <option @if($whatsapp->state == '1') selected @endif value="1">Activated</option>
                                <option @if($whatsapp->state != '1') selected @endif value="0">Deactivated</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Mobile No</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="server_mobile" value="{{$whatsapp->server_mobile}}" type="text" class="form-control input-xlg"
                                    placeholder="201145929570">
                                <div class="form-control-feedback">
                                    <i class="icon-mobile"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Instance URL</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="chatapi_instance_url" value="{{$whatsapp->chatapi_instance_url}}" type="text" class="form-control input-xlg"
                                    placeholder="https://www.mikofi.com">
                                <div class="form-control-feedback">
                                    <i class="icon-server"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Instance ID</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="chatapi_instance_id" value="{{$whatsapp->chatapi_instance_id}}" type="text" class="form-control input-xlg"
                                    placeholder="62250DCA68A95">
                                <div class="form-control-feedback">
                                    <i class="icon-barcode2"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Instance Token</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="chatapi_instance_token" type="text" value="{{$whatsapp->chatapi_instance_token}}" class="form-control input-xlg"
                                    placeholder="ace928a542d57029d1941cc8802e21d6">
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
            <!-- /basic -->
           

        </div>
    </form>
</div>

<script>
    $("#edit_connection_type").change(function(){
        var connectiontype = $("#edit_connection_type option:selected").val();
        
        if(connectiontype == 'interface'){
            $('.edit_interface_connection_variables').show();
            $('.edit_database_connection_variables').hide();
        }

        if(connectiontype == 'database'){
            $('.edit_database_connection_variables').show();
            $('.edit_interface_connection_variables').hide();
        }
    });


    // Form components
    // ------------------------------

    // Select2 selects
    $('.select').select2({
        minimumInputLength: 2,
        minimumResultsForSearch: Infinity
    });

    $('.select0').select2({
        minimumResultsForSearch: Infinity
    });

    $('.selectt').select2({
        minimumResultsForSearch: Infinity
    });

    // Styled checkboxes, radios
    $(".styled").uniform({
        radioClass: 'choice'
    });

</script>