@extends('..back-end.layouts.master')
@section('title', 'Settings')
@section('content')
<?php
include '../config.php';
$subdomain = url()->full();
$split = explode('/', $subdomain);
$customerData =  DB::table('customers')->where('url',$split[2])->first();
if($customerData->currency == "USD"){
    $priceColumnName = "price_USD";
}else{
    $priceColumnName = "price";
}

if($customerData->global == "1"){
    $packagesTable = "packages_global";
}else{
    $packagesTable = "packages";
}
?>
<script type="text/javascript" src="assets/js/pages/form_checkboxes_radios.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
<!-- Page header -->



<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <!-- <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/inputs/maxlength.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_select.min.js"></script> -->
    <!-- <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script> -->
    <!-- <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script> -->

    <!-- <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script> -->
     <!-- <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>  -->
	<!-- <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script> -->

    <!-- <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script> -->

     <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script> 

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <!-- <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script> -->
    <!-- <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script> -->
    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

	<!-- <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script> -->

    <!-- <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script> -->









<!-- Primary modal -->
        <div id="subscription_modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Renew Microsystem WiFi Subscription</h6>
                    </div>

                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
<!-- /primary modal -->

    <div class="page-header">

        <!-- Header content -->
            <div class="page-header-content">
                <div class="page-title">
                    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Settings</h4>
                </div>
            </div>
        <!-- /header content -->
        
        <!-- Toolbar -->
        <div class="navbar navbar-default navbar-component navbar-xs">

            <div class="navbar-collapse collapse" id="navbar-filter">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#settings" data-toggle="tab"><i class="icon-cog3 position-left"></i> Settings</a></li>
                    <li><a href="#crm" data-toggle="tab"><i class="icon-markup position-left"></i> CRM Integrations</a></li>
                    <li><a href="#social" data-toggle="tab"><i class="icon-dribbble position-left"></i> Social Integrations</a></li>
                    <li><a href="#sms" data-toggle="tab"><i class="icon-mail-read position-left"></i> SMS Integrations</a></li>
                    <li><a href="#push" data-toggle="tab"><i class="icon-mail-read position-left"></i> Push Notification</a></li>
                    <li><a href="#whatsapp" data-toggle="tab"><i class="icon-envelope position-left"></i> WhatsApp Integrations</a></li>
                    <li><a href="#telegram" data-toggle="tab"><i class="icon-envelope position-left"></i> Telegram Integrations</a></li>
                    <li><a href="#pos" data-toggle="tab"><i class="icon-barcode2 position-left"></i> POS Integrations</a></li>
                    <li><a href="#pms" data-toggle="tab"><i class="icon-city position-left"></i>PMS Integrations</a></li>
                    <li><a href="#ai_copilot" data-toggle="tab"><i class="icon-brain position-left"></i> AI CoPilot</a></li>
                    <!-- <li><a href="#wifi" data-toggle="tab"><i class="icon-connection position-left"></i> Wi-Fi Controllers</a></li> -->
                </ul>
            </div>
        </div>
        <!-- /toolbar -->
    </div>
    <!-- /page header -->

<!-- Content area -->
    <div class="content">

        <!-- New WhatsApp Integration -->
        <div id="add_whatsapp_integration" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Add new WhatsApp integration</h6>
                    </div>


                    <div class="modal-body">
                        <div class="row">
                            <form action="{{ url('addWhatsappIntegration') }}" method="POST" id="addwhatsapp" class="form-horizontal">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <div class="panel-group panel-group-control content-group-lg" id="accordion-control">
                                    <div class="panel-white">
                                            <div class="panel-body">

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Provider</label>
                                                    <div class="col-lg-8">
                                                        <select class="selectt" name="integration_type">
                                                            <option value="5">Mikofi.com</option>
                                                            <option value="2">ChatApi.com</option>
                                                            <option value="3">Mercury.chat</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Mobile No</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="server_mobile" type="text" class="form-control input-xlg"
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
                                                            <input name="chatapi_instance_url" type="text" class="form-control input-xlg"
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
                                                            <input name="chatapi_instance_id" type="text" class="form-control input-xlg"
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
                                                            <input name="chatapi_instance_token" type="text" class="form-control input-xlg"
                                                                placeholder="ace928a542d57029d1941cc8802e21d6">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-key"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        
                                    </div>
                                   
                                </div>
                                <!-- /accordion with left control button -->
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="Success_message"
                                onclick="document.forms['addwhatsapp'].submit(); return false;">Create
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- /New Whatsapp Integration -->

        <!-- Edit WhatsApp integration -->
        <div id="modal_ajax_editWhatsappIntegration" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Edit WhatsApp Integration</h6>
                    </div>

                    <div class="modal-body">


                        <hr>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="Success_message"
                                onclick="document.forms['editWhatsappIntegration'].submit(); return false;">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit WhatsApp integration -->

        <!-- New PMS Integration -->
        <div id="add_pms_integration" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Add new PMS integration</h6>
                    </div>


                    <div class="modal-body">
                        <div class="row">
                            <form action="{{ url('addPmsIntegration') }}" method="POST" id="addpms" class="form-horizontal">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <div class="panel-group panel-group-control content-group-lg" id="accordion-control">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion-control"
                                                href="#accordion-control-group1">Basic</a>
                                            </h6>
                                        </div>
                                        <div id="accordion-control-group1" class="panel-collapse collapse in">
                                            <div class="panel-body">

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Name</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="name" type="text" class="form-control input-xlg"
                                                                placeholder="">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">PMS type</label>
                                                    <div class="col-lg-8">
                                                        <select class="selectt" name="type">
                                                            <option value="opera51">Opera V5.1</option>
                                                            <option value="opera55">Opera V5.5</option>
                                                            <option value="suite8">Suite 8</option>
                                                            <option value="protel">Protel</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Connection type</label>
                                                    <div class="col-lg-8">
                                                        <select class="selectt" name="connection_type" id="connection_type">
                                                            <option value="interface">Through Interface</option>
                                                            <option value="database">Database directly</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Check-In Group</label>
                                                    <div class="col-lg-8">
                                                        <select class="selectt" name="internet_group">
                                                            @foreach(DB::table($customerData->database."."."area_groups")->where('is_active','1')->where('as_system','0')->get() as $group)
                                                                <option value="{{$group->id}}"> {{$group->name}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Check-Out Group</label>
                                                    <div class="col-lg-8">
                                                        <select class="selectt" name="checkout_group">
                                                            @foreach(App\Groups::where('is_active','1')->where('as_system','0')->get() as $group)
                                                                <option value="{{$group->id}}"> {{$group->name}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Interface connection variables -->
                                    <div class="panel panel-white interface_connection_variables" >
                                        <div class="panel-heading">
                                            <h6 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                                href="#accordion-control-group2">Interface Connection</a>
                                            </h6>
                                        </div>
                                        <div id="accordion-control-group2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                
                                                <div class="alert alert-info alert-styled-left alert-bordered">
                                                    <span class="text-semibold">You can ask the PMS interface provider</span> to configure the following parameter into the interface to be able to get the guest contacts:
                                                    <br>
                                                    <span class="text-semibold">A0 (Guest Birthday) Format: DDMMYYYY</span> <br>
                                                    <span class="text-semibold">A1 (Guest E-Mail)</span> <br>
                                                    <span class="text-semibold">A2 (Guest Mobile)</span> <br>
                                                    <span class="text-semibold">A3 (Guest Nationality)</span> <br>
                                                    <span class="text-semibold">A4 (Guest Gender)</span> <br>
                                                    <span class="text-semibold">A5 (Guest Confirmation Number)</span><br>
                                                    <span class="text-semibold">A6 (Guest Room Type)</span><br>
                                                    <span class="text-semibold">A7 (Passport No)</span><br><br>
                                                    <span class="text-semibold">Notes: Guest will be assigned to a group with the same name or Room Type if exist.</span><br>
                                                    <span class="text-semibold">Notes: Make sure that PMS sends (GC) update in case of any change in guest profile ex.(email, mobile, birthdate).</span>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Interface IP</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="interface_ip" type="text" class="form-control input-xlg"
                                                                placeholder="127.0.0.1">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Interface Port</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="interface_port" type="text" class="form-control input-xlg"
                                                                placeholder="9090">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Interface connection variables -->
                                    
                                    <!-- Database connection variables -->
                                    <div class="panel panel-white database_connection_variables" style="display: none;">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                                href="#accordion-control-group3">Database Connection</a>
                                            </h6>
                                        </div>
                                        <div id="accordion-control-group3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                
                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Database IP</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="db_ip" type="text" class="form-control input-xlg"
                                                                placeholder="127.0.0.1">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Database Port</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="db_port" type="text" class="form-control input-xlg"
                                                                placeholder="1521">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Database Name</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="db_name" type="text" class="form-control input-xlg"
                                                                placeholder="opera">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Database Username</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="db_username" type="text" class="form-control input-xlg"
                                                                placeholder="opera">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Database Password</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="db_password" type="password" class="form-control input-xlg"
                                                                placeholder="opera">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Internet Revenue Code</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="db_transaction_code" type="text" class="form-control input-xlg"
                                                                placeholder="5608">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Supervisor or IFC Username</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="db_posting_username" type="text" class="form-control input-xlg"
                                                                placeholder="SUPERVISOR">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-server"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Database connection variables -->

                                    <!-- Login by -->
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                                href="#accordion-control-group4">Login by</a>
                                            </h6>
                                        </div>
                                        <div id="accordion-control-group4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                
                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Login Username</label>
                                                    <div class="col-lg-8">
                                                        <select class="selectt" name="login_username">
                                                            <option selected value="room_no">Room Number</option>
                                                            <option value="first_name">First Name</option>
                                                            <option value="last_name">Last Name</option>
                                                            <option value="mobile">Mobile</option>
                                                            <option value="email">Email</option>
                                                            <option value="birth_date">Birth Date</option>
                                                            <option value="reservation_no">Reservation Number</option>
                                                            <option value="confirmation_no">Confirmation Number</option>
                                                            <option value="check_in_date">Check-In date (DDMMYYYY)</option>
                                                            <option value="check_out_date">Check-Out date (DDMMYYYY)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Login Password</label>
                                                    <div class="col-lg-8">
                                                        <select class="selectt" name="login_password">
                                                            <option value="room_no">Room Number</option>
                                                            <option value="first_name">First Name</option>
                                                            <option selected value="last_name">Last Name</option>
                                                            <option value="mobile">Mobile</option>
                                                            <option value="email">Email</option>
                                                            <option value="birth_date">Birth Date</option>
                                                            <option value="reservation_no">Reservation Number</option>
                                                            <option value="confirmation_no">Confirmation Number</option>
                                                            <option value="check_in_date">Check-In date (DDMMYYYY)</option>
                                                            <option value="check_out_date">Check-Out date (DDMMYYYY)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Login by -->

                                </div>
                                <!-- /accordion with left control button -->
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="Success_message"
                                onclick="document.forms['addpms'].submit(); return false;">Create
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- /New PMS Integration -->

        <!-- Edit PMS integration -->
        <div id="modal_ajax" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Edit PMS Integration</h6>
                    </div>

                    <div class="modal-body">


                        <hr>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="Success_message"
                                onclick="document.forms['editPmsIntegration'].submit(); return false;">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit PMS integration -->
        
        <!-- User profile -->
        <div class="row">
            <div class="col-lg-12">
                <div class="tabbable">
                    <div class="tab-content">

                        <div class="tab-pane fade <?php if($object == 1) echo 'in active'; ?>" id="crm">
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><img src="assets/images/agilecrm.png" title="Agile CRM"> </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body" @if(App\Settings::where('type', 'agile_rest_api')->value('state') == 1) style="display: block;" @else style="display: none;" @endif>
                                        <form action="{{ url('agilesetting') }}" method="POST" id="agilesetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Domain name</label>
                                                        <input type="text" name="agile_domain_name" value="{{ App\Settings::where('type', 'agile_domain_name')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Admin Email</label>
                                                        <input type="text" name="agile_admin_email" value="{{ App\Settings::where('type', 'agile_admin_email')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Rest API</label>
                                                        <input type="text" name="agile_rest_api" value="{{ App\Settings::where('type', 'agile_rest_api')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">


                                                    <div class="col-md-12">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="agile_send_comtacts" value='1' class="styled" @if(App\Settings::where('type', 'agile_send_comtacts')->value('state') == 1) checked="checked"@else @endif>
                                                                Send contacts to Agile CRM
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox"  value='1' name="agile_receive_contacts" class="styled" @if(App\Settings::where('type', 'agile_receive_contacts')->value('state') == 1) checked="checked"@else @endif>
                                                                Receive contacts from Agile CRM
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox"  value='1' name="agile_send_login_score" class="styled" @if(App\Settings::where('type', 'agile_send_login_score')->value('state') == 1) checked="checked"@else @endif>
                                                                Add user score for each login
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value='1' name="enable" class="styled" @if(App\Settings::where('type', 'agile_rest_api')->value('state') == 1) checked="checked"@else @endif>
                                                                Enable Agile CRM Integrations
                                                            </label>
                                                        </div>
                                                    </div>

                                                    
                                                </div>
                                            </div>

                                            

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['agilesetting'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-flat in active">
                                    <div class="panel-heading in active">
                                        <h6 class="panel-title"><img src="assets/images/salesforce.png"> Sales force ( Coming Soon... )</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <!--<div class="panel-body ">
                                        <form action="{{ url('salesforcesetting') }}" method="POST" id="salesforcesetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Username</label>
                                                        <input type="text" value="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>New password</label>
                                                        <input type="password" placeholder="Enter new password" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="enable" value='1' class="styled" checked="checked">
                                                                Enable Sales force Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['salesforcesetting'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>-->
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade <?php if($object == 3) echo 'in active'; ?>" id="social">
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><i class="icon-facebook position-left"></i> Facebook</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body" @if(App\Settings::where('type', 'facebook_client_id')->value('state') == 1) style="display: block;" @else style="display: none;" @endif>
                                        <form action="{{ url('facebooksetting') }}" method="POST" id="facebooksetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client ID</label>
                                                        <input type="text" name="facebook_id" value="{{ App\Settings::where('type', 'facebook_client_id')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client Secret</label>
                                                        <input type="password" name="facebook_secret" value="{{ App\Settings::where('type', 'facebook_client_secret')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        

                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="enable" type="checkbox" value='1' class="styled" @if(App\Settings::where('type', 'facebook_client_id')->value('state') == 1) checked="checked"@else @endif >
                                                                Enable Facebook Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['facebooksetting'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><i class="icon-twitter position-left"></i> Twitter</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body" @if(App\Settings::where('type', 'twitter_client_id')->value('state') == 1) style="display: block;" @else style="display: none;" @endif>
                                        <form action="{{ url('twittersetting') }}" method="POST" id="twittersetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client ID</label>
                                                        <input type="text" name="twitter_id" value="{{ App\Settings::where('type', 'twitter_client_id')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client Secret</label>
                                                        <input type="password" name="twitter_secret" value="{{ App\Settings::where('type', 'twitter_client_secret')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="enable"  value='1' class="styled" @if(App\Settings::where('type', 'twitter_client_id')->value('state') == 1) checked="checked"@else @endif>
                                                                Enable Twitter Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['twittersetting'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><i class="icon-google-plus position-left"></i> Google +</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body" @if(App\Settings::where('type', 'google_client_id')->value('state') == 1) style="display: block;" @else style="display: none;" @endif>
                                        <form action="{{ url('googlesetting') }}" method="POST" id="googlesetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client ID</label>
                                                        <input type="text" name="google_id" value="{{ App\Settings::where('type', 'google_client_id')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client Secret</label>
                                                        <input type="password" name="google_secret" value="{{ App\Settings::where('type', 'google_client_secret')->value('value') }}"  class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="enable" value='1' class="styled" @if(App\Settings::where('type', 'google_client_id')->value('state') == 1) checked="checked"@else @endif>
                                                                Enable Google + Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['googlesetting'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><i class="icon-linkedin position-left"></i> Linkedin</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body" @if(App\Settings::where('type', 'linkedin_client_id')->value('state') == 1) style="display: block;" @else style="display: none;" @endif>
                                        <form action="{{ url('linkedinsetting') }}" method="POST" id="linkedinsetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client ID</label>
                                                        <input type="text" name="linkedin_id" value="{{ App\Settings::where('type', 'linkedin_client_id')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Client Secret</label>
                                                        <input type="password" name="linkedin_secret" value="{{ App\Settings::where('type', 'linkedin_client_secret')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="enable" value='1' class="styled" @if(App\Settings::where('type', 'linkedin_client_id')->value('state') == 1) checked="checked"@else @endif>
                                                                Enable Linkedin Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['linkedinsetting'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="sms">
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">SMS <img src="assets/images/orange.png"> <img src="assets/images/unifonic2.png"> <img src="assets/images/Razy.png"> <img src="assets/images/infobip.png"> <img src="assets/images/masrawy.png"></h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                       <form action="{{ url('smssetting') }}" method="POST" id="SMSSettings">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Username</label>
                                                        <input type="text" name="providerusername" value="{{ App\Settings::where('type', 'SMSProviderusername')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Password</label>
                                                        <input type="password" name="providerpassword" value="{{ App\Settings::where('type', 'SMSProviderpassword')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Sender Name</label>
                                                        <input type="text" name="providersendername" value="{{ App\Settings::where('type', 'SMSProvidersendername')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Provider</label>
                                                        <select class="selectt" name="provider">
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 6) selected @endif value="6">Orange(EGY)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 1) selected @endif value="1">Unifonic(EGY)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 2) selected @endif value="2">Masrawy(EGY)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 3) selected @endif value="3">Valuedsms(EGY)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 4) selected @endif value="4">Infobip (International)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 5) selected @endif value="5">Razytech (EGY)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 7) selected @endif value="7">Mobily.WS (KSA)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 8) selected @endif value="8">Smart SMS (UAE)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 9) selected @endif value="9">SMS Misr(EGY)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 10) selected @endif value="10"> SMS (EGY)</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 11) selected @endif value="11">VictoryLink</option>
                                                        <option @if(App\Settings::where('type', 'SMSProvider')->value('value') == 12) selected @endif value="12">Orange Wi-Fi verification Code</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>SMS Verification Template</label>
                                                        <?php 
                                                            $smsVerificationTemplateQuery = App\Settings::where('type', 'smsVerificationTemplate')->first();
                                                            if(isset($smsVerificationTemplateQuery->value)){
                                                                $smsVerificationTemplate = $smsVerificationTemplateQuery->value;
                                                                if( $smsVerificationTemplateQuery->state == "1" ){ $disabledSmsVerificationTemplate = ""; }
                                                                else{ $disabledSmsVerificationTemplate = "disabled=yes"; }
                                                            }else{
                                                                $smsVerificationTemplate = "Microsystem Smart Wi-Fi code is @CODE";
                                                                $disabledSmsVerificationTemplate = "disabled=yes";
                                                            }
                                                        ?>
                                                        <input type="text" name="smsVerificationTemplate" {{ $disabledSmsVerificationTemplate }} value="{{ $smsVerificationTemplate }}" placeholder="Microsystem Smart Wi-Fi code is @CODE" class="form-control">
                                                        <span class="help-block"> Note: Don't forget to add @CODE in your SMS template.</span>
                                                        <span class="help-block"> Note: SMS verification template must comply with the NTRA rules.</span>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="sms-enable" type="checkbox" class="styled" @if(App\Settings::where('type', 'SMSProvider')->value('state') == 1) checked="checked" @else @endif>
                                                                Enable SMS Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['SMSSettings'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- AccountKit -->
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><img src="assets/images/fb-art.png"> <img src="assets/images/account-kit-hero.png"> Accountkit</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <form action="{{ url('accountkitsmssetting') }}" method="POST" id="AcountkitSMSSettings">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Account Kit App ID</label>
                                                        <input type="text" name="accountkitappid" value="{{ App\Settings::where('type', 'Accountkitappid')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Account Kit App Secret</label>
                                                        <input type="password" name="accountkitappsecret" value="{{ App\Settings::where('type', 'Accountkitappsecret')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="accountkit-enable" type="checkbox" class="styled" @if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1) checked="checked" @else @endif>
                                                                Enable Accountkit Integration for SMS verification codes
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['AcountkitSMSSettings'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Firebase Phone Auth -->
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><img src="assets/images/firebase.png"> </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <form action="{{ url('firebaseSMSauthSetting') }}" method="POST" id="firebaseSMSauthSetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Firebase Web API Key</label>
                                                        <input type="text" name="firebaseApiKey" value="{{ App\Settings::where('type', 'firebaseAuthentication')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="firebase-enable" type="checkbox" class="styled" @if(App\Settings::where('type', 'firebaseAuthentication')->value('state') == 1) checked="checked" @else @endif>
                                                                Enable Firebase Integration for SMS verification codes.
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['firebaseSMSauthSetting'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="tab-pane fade" id="push">
                            <div class="col-md-5">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">Push Notification</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                    <form action="{{ url('push-notification') }}" method="POST" id="push-notification">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Api Key</label>
                                                        <input type="text" name="providerusername" value="{{ App\Settings::where('type', 'push_api')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Password</label>
                                                        <input type="password" name="providerpassword" value="{{ App\Settings::where('type', 'SMSProviderpassword')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" class="styled" value='1' checked="checked">
                                                                Enable Push Notification
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['push-notification'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="whatsapp">
                            <!-- // settings -->
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"> <img src="assets/images/whatsapp.png"> <img src="assets/images/whatsapp-logo.png"> </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                       <form action="{{ url('whatsappSetting') }}" method="POST" id="whatsappSetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Username</label>
                                                        <input type="text" name="whatsappProviderUsername" value="{{ App\Settings::where('type', 'whatsappProviderUsername')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Password</label>
                                                        <input type="password" name="whatsappProviderPassword" value="{{ App\Settings::where('type', 'whatsappProviderPassword')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Sender Name</label>
                                                        <input type="text" name="whatsappSenderName" value="{{ App\Settings::where('type', 'whatsappSenderName')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Provider</label>
                                                        <select class="selectt" name="whatsappProvider">
                                                            <option @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 1) selected @endif value="1">Linux</option>
                                                            <option @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 2) selected @endif value="2">Chat-Api.com</option>
                                                            <option @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 3) selected @endif value="3">Mercury.chat</option>
                                                            <option @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 5) selected @endif value="5">Mikofi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="whatsapp-enable" type="checkbox" class="styled" @if(App\Settings::where('type', 'whatsappProvider')->value('state') == 1) checked="checked" @else @endif>
                                                                Enable WhatsApp Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['whatsappSetting'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- WhatsApp channels -->
                            <?php /* @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 1 or App\Settings::where('type', 'whatsappProvider')->value('value') == 2 or App\Settings::where('type', 'whatsappProvider')->value('value') == 3 or App\Settings::where('type', 'whatsappProvider')->value('value') == 5) */ ?>
                                <div class="col-lg-6">
                                    <div class="panel panel-flat">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">WhatsApp Channels</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>


                                        <div class="panel-body" >
                                            <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_whatsapp_integration"><b><i class="icon-tree6"></i></b> New WhatsApp Integration</button>
                                            <!-- <div class='alert alert-info alert-styled-left alert-bordered'><center><span class='text-semibold'> -->
                                                    <!-- Pay As You Go service is to allow your system to exceed concurrent devices, and just pay for exceeded days. -->
                                            <!-- </center></div> -->
                                            
                                                <table class="table" width="100%" id="table-whatsappChannels">
                                                    <thead>
                                                        <tr>
                                                            <th>State</th>
                                                            <th>Number</th>
                                                            <th>Type</th>
                                                            <th class="text-center"></th>
                                                        </tr>
                                                    </thead>
                                                </table>
            
                                                <!--   
                                                <br>
                                                <div class="text-right">
                                                    <button type="button" class="click_payasyougoPaymentMethod btn btn-primary btn-block" data-target="#subscription_modal"><b><i class="icon-envelope"></i> &nbsp&nbsp Add New WhatsApp Channel !</b></button>
                                                </div>
                                                -->
                                        </div>
                                    </div> 
                                </div>
                            <?php /* @endif */ ?>


                        </div>

                        <div class="tab-pane fade" id="telegram">
                            <!-- // settings -->
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"> <img src="assets/images/telegram.png"> <img src="assets/images/telegram-title.png"> </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                       <form action="{{ url('telegramSetting') }}" method="POST" id="telegramSetting">
                                       <?php 
                                            $whatsappTokenData = DB::table('whatsapp_token')->where('customer_id',$customerData->id)->where('integration_type','4')->first();
                                            if(isset($whatsappTokenData)){ $telegramApiToken=$whatsappTokenData->telegram_api_token; $telegramApiState=$whatsappTokenData->state;}
                                            else{ $telegramApiToken=""; $telegramApiState=0;}
                                       ?>
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>API token</label>
                                                        <input type="text" name="telegramApiToken" value="{{ $telegramApiToken }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="telegramApiState" type="checkbox" class="styled" @if($telegramApiState == 1) checked="checked" @else @endif>
                                                                Enable Telegram Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['telegramSetting'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            


                        </div>

                        <div class="tab-pane fade" id="pos">

                            <!-- Simble Touch POS -->
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"> <img src="assets/images/simbleTouchPOS.png"> </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                        
                                        <br>
                                        <div class='alert alert-info alert-styled-left alert-bordered'><center><span class='text-semibold'>
                                            Click to the following button to disable or enable the integration of Simble Touch POS
                                            <br>
                                            <label>
                                                    <input type="checkbox" id='simpleTouchPosIntegrationStateBtn' name="simpleTouchPosIntegrationStateBtn"  @if( App\Settings::where('type', 'simpleTouchPosIntegration')->value('state') == "1" ) checked="checked" value="1" @else value="0" @endif class="switchery simpleTouchPosIntegrationStateBtn" data-switchery="true">
                                            </label> 
                                            </center></div>
                                        </div>

                                    <div class="panel-body">
                                       <form action="{{ url('simpleTouchSetting') }}" method="POST" id="simpleTouchSetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Customer ID in Simble Touch POS</label>
                                                        <input type="text" name="simpleTouchPosIntegrationID" value="{{ App\Settings::where('type', 'simpleTouchPosIntegration')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['simpleTouchSetting'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- POS rocket -->
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"> <img src="assets/images/POSrocket.png"> </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                        
                                        @if( App\Settings::where('type', 'PosRocketIntegration')->value('state') == 1 )
                                            <br>
                                            <div class='alert alert-info alert-styled-left alert-bordered'><center><span class='text-semibold'>
                                                Click to the following button to disable or enable your POS rocket account
                                                <br>
                                                <label>
                                                <input type="checkbox" id='posRocketIntegrationStateBtn' name="posRocketIntegrationStateBtn"  @if( App\Settings::where('type', 'PosRocketIntegration')->value('state') == "1" ) checked="checked" value="1" @else value="0" @endif class="switchery posRocketIntegrationStateBtn" data-switchery="true">
                                                </label> 
                                                <br>
                                                <label>
                                                    <a target='_blank' href="https://developer.posrocket.com/oauth/authorize/?redirect_uri=https://{{$customerData->url}}/api/POSrocketCallback&response_type=code&client_id={{$posRocketClientID}}&access_type=offline" class="btn btn-primary btn-rounded btn-block">Reintegrate again!<i class="icon-circle-right2 position-right"></i> </a>
                                                </label>                                               
                                                </center></div>
                                            </div>
                                        @else
                                            <br>
                                            <div class='alert alert-info alert-styled-left alert-bordered'><center><span class='text-semibold'>
                                                Click to the following link to integrate your POS rocket account
                                                <br>
                                                <label>
                                                    <a target='_blank' href="https://developer.posrocket.com/oauth/authorize/?redirect_uri=https://{{$customerData->url}}/api/POSrocketCallback&response_type=code&client_id={{$posRocketClientID}}&access_type=offline" class="btn btn-primary btn-rounded btn-block">Next <i class="icon-circle-right2 position-right"></i> </a>
                                                </label>
                                                </center></div>
                                            </div>
                                        @endif

                                </div>
                            </div>
                        
                        </div>

                        <div class="tab-pane fade" id="pms">
                            <!-- // settings -->
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"> <i class="icon-bed2 position-left"></i> Captive Portal Configurations </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="panel-body">
                                       <form action="{{ url('pmsSetting') }}" method="POST" id="pmsSetting">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Premium login label</label>
                                                        <input type="text" name="pms_premium_portal_label" value="{{ App\Settings::where('type', 'pms_premium_portal_label')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Complementary login label</label>
                                                        <input type="text" name="pms_complementary_portal_label" value="{{ App\Settings::where('type', 'pms_complementary_portal_label')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Login Username Label</label>
                                                        <input type="text" name="pms_login_username_portal_label" value="{{ App\Settings::where('type', 'pms_login_username_portal_label')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Login Password Label</label>
                                                        <input type="text" name="pms_login_password_portal_label" value="{{ App\Settings::where('type', 'pms_login_password_portal_label')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="pms-enable" type="checkbox" class="styled" @if(App\Settings::where('type', 'pms_integration')->value('state') == 1) checked="checked" @else @endif>
                                                                Enable PMS Integration
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="pms_save_mobile_from_login_page" type="checkbox" class="styled" @if(App\Settings::where('type', 'pms_save_mobile_from_login_page')->value('state') == 1) checked="checked" @else @endif>
                                                                Add mobile number filed in the premium login page and save it into DB without SMS verification
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="pms_save_email_from_login_page" type="checkbox" class="styled" @if(App\Settings::where('type', 'pms_save_email_from_login_page')->value('state') == 1) checked="checked" @else @endif>
                                                                Add Email filed in the premium login page and save it into DB
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['pmsSetting'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Multiple PMS integrations -->
                            
                            <div class="col-lg-6">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"> <i class="icon-stack-check position-left"></i> Multiple PMS integrations</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    


                                    <div class="panel-body" >

                                    <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_pms_integration"><b><i class="icon-tree6"></i></b> New Integration</button>
                                        <!-- <div class='alert alert-info alert-styled-left alert-bordered'><center><span class='text-semibold'> -->
                                                <!-- Pay As You Go service is to allow your system to exceed concurrent devices, and just pay for exceeded days. -->
                                        <!-- </center></div> -->
                                        
                                            <table class="table" width="100%" id="table-pms">
                                                <thead>
                                                    <tr>
                                                        <th>State</th>
                                                        <th>Name</th>
                                                        <th>Connection Type</th>
                                                        <th>Type</th>
                                                        <th>Last check</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                </thead>
                                            </table>
        
                                            <!--   
                                            <br>
                                            <div class="text-right">
                                                <button type="button" class="click_payasyougoPaymentMethod btn btn-primary btn-block" data-target="#subscription_modal"><b><i class="icon-envelope"></i> &nbsp&nbsp Add New WhatsApp Channel !</b></button>
                                            </div>
                                            -->
                                    </div>
                                </div> 
                            </div>
                            


                        </div>

                        <div class="tab-pane fade <?php if($object == 3) echo 'in active'; ?>" id="ai_copilot">

                            <!-- AI Email Verification -->
                            <div class="col-lg-6">
                                <!-- Profile info -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><i class="icon-mention position-left"></i> AI Email Verification</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                   
                                        <div class="panel-body">
                                            <form action="{{ url('Systemsettingemail') }}" method="POST" id="Save_setting_email">
                                                {{ csrf_field() }}

                                                <div class="row">   
                                                    <div class="col-md-6">         
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="emailVerificationForLogin" @if(App\Settings::where('type', 'emailVerificationForLogin')->value('state')==1) checked="checked" @endif class="switchery switchery1" value="1" data-switchery="true">
                                                                Email verification for <span class="text-semibold">Guest in house </span> users.
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">         
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="emailVerificationForSignup" @if(App\Settings::where('type', 'emailVerificationForSignup')->value('state')==1) checked="checked" @endif class="switchery switchery2" value="1" data-switchery="true">
                                                                Email verification for <span class="text-semibold">Complimentary</span> users.
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>   

                                                <br>

                                                <div class="row">   
                                                    <div class="col-md-6">         
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="emailVerificationWithoutAiForLogin" @if(App\Settings::where('type', 'emailVerificationForLogin')->value('value')!="WithoutAi") checked="checked" @endif class="switchery switchery1" value="1" data-switchery="true">
                                                                Use Generative Ai for <span class="text-semibold">Guest in house </span> emails.
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">         
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="emailVerificationWithoutAiForSignup" @if(App\Settings::where('type', 'emailVerificationForSignup')->value('value')!="WithoutAi") checked="checked" @endif class="switchery switchery2" value="1" data-switchery="true">
                                                                Use Generative Ai for <span class="text-semibold">Complimentary</span> emails.
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>   

                                                <br>

                                                <div class="row">   
                                                    <div class="col-md-6">
                                                    <div class="divGuest0">
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="emailVerificationSwitchRoomTypeForLogin" @if(App\Settings::where('type', 'emailVerificationSwitchRoomTypeForLogin')->value('state')==1) checked="checked" @endif class="switchery Guest-in-house" value="1" data-switchery="true">
                                                                Switch <span class="text-semibold">Guest in house</span> users to PMS room type after successfull email verification.
                                                            </label>
                                                        </div>
                                                        </div>             
                                                    </div>

                                                    <div class="col-md-6">      
                                                    <div class="divComplimentary0">   
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="emailVerificationSwitchRoomTypeForSignup" @if(App\Settings::where('type', 'emailVerificationSwitchRoomTypeForSignup')->value('state')==1) checked="checked" @endif class="switchery Complimentary" value="1" data-switchery="true">
                                                                Switch <span class="text-semibold">Complimentary</span> users to PMS room type after successfull email verification.
                                                            </label>
                                                        </div>
                                                        </div>       
                                                    </div>
                                                </div>  

                                                <br>

                                                <div class="row"> 
                                                    <div class="col-md-6">
                                                        <div class="divGuest1">
                                                        <label>Switch <span class="text-semibold">Guest in house</span> users after successfull email verification to:</label>
                                                        <select name="emailVerificationSwitchToGroupIdForLogin" class="selectt">
                                                            @foreach(App\Groups::where('is_active','1')->get() as $group)
                                                                <option @if(App\Settings::where('type', 'emailVerificationSwitchToGroupIdForLogin')->value('value') == $group->id) selected @endif value="{{ $group->id }}">{{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <div class="divComplimentary1">
                                                        <label>Switch <span class="text-semibold">Complimentary</span> users after successfull email verification to:</label>
                                                        <select name="emailVerificationSwitchToGroupIdForSignup" class="selectt">
                                                        @foreach(App\Groups::where('is_active','1')->get() as $group)
                                                                <option @if(App\Settings::where('type', 'emailVerificationSwitchToGroupIdForSignup')->value('value') == $group->id) selected @endif value="{{ $group->id }}">{{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div> 

                                                <br>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                    <div class="divGuest2">
                                                        <label>Motivational Msg For Login</label>
                                                        <input name="emailVerificationMotivationalMsgForLogin" type="text" value="{{ App\Settings::where('type', 'emailVerificationMotivationalMsgForLogin')->value('value') }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <div class="divComplimentary2">

                                                        <label>Motivational Msg For Signup</label>
                                                        <input name="emailVerificationMotivationalMsgForSignup" type="text" value="{{ App\Settings::where('type', 'emailVerificationMotivationalMsgForSignup')->value('value') }}" class="form-control">
                                                    </div>
                                                    </div>
                                                </div>

                                                <br>

                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="panel-heading text-center">
                                                            <h3 class="panel-title"><strong> <i class="icon-brain"></i>  Available Variables </strong></h3><br>
                                                            <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span>
                                                        </div>

                                                        <div class="divGuest2">
                                                            <label>AI or direct content:</label>
                                                            <textarea name="emailVerificationUsingChatGptMessage" type="text" class="form-control input-xlg" rows="6" placeholder="">{{ App\Settings::where('type', 'emailVerificationUsingChatGptMessage')->value('value') }}</textarea>
                                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content, or enter email content directly if you disable generative Ai.</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <br>

                                                <div class="text-right">
                                                    <button type="button" class="btn btn-primary" onclick="document.forms['Save_setting_email'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                   
                                </div>

                            </div>
                        
                            <!-- ChatGPT integration -->
                            <div class="col-md-6">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><i class="icon-brain position-left"></i> ChatGPT integration </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body" style="display: none;" >
                                        <form action="{{ url('chatGptSetting') }}" method="POST" id="chatGptSetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Api Token:</label>
                                                        <input type="text" name="chatGptApiToken" value="{{ App\Settings::where('type', 'chatGptApiToken')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Send email notifications from:</label>
                                                        <input type="email" name="sendEmailsFromEmail" value="{{ App\Settings::where('type', 'sendEmailsFromEmail')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        

                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="enable" type="checkbox" value='1' class="styled" @if(App\Settings::where('type', 'chatGptApiToken')->value('state') == 1) checked="checked" @else @endif >
                                                                Enable ChatGPT Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['chatGptSetting'].submit(); return false;">Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Google integration -->
                            <div class="col-md-6">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><i class="icon-google position-left"></i> Google Bard integration (comming soon ...) </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
         
                            

                        </div> 

                        <?php /*
                        <div class="tab-pane fade" id="wifi">
                            <div class="col-md-4">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"> <img src="assets/images/whatsapp.png"> <img src="assets/images/whatsapp-logo.png"> </h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                       <form action="{{ url('whatsappSetting') }}" method="POST" id="whatsappSetting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Username</label>
                                                        <input type="text" name="whatsappProviderUsername" value="{{ App\Settings::where('type', 'whatsappProviderUsername')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Password</label>
                                                        <input type="password" name="whatsappProviderPassword" value="{{ App\Settings::where('type', 'whatsappProviderPassword')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Sender Name</label>
                                                        <input type="text" name="whatsappSenderName" value="{{ App\Settings::where('type', 'whatsappSenderName')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Provider</label>
                                                        <select class="selectt" name="whatsappProvider">
                                                            <option @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 1) selected @endif value="1">Linux</option>
                                                            <option @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 2) selected @endif value="2">Chat-Api.com</option>
                                                            <option @if(App\Settings::where('type', 'whatsappProvider')->value('value') == 3) selected @endif value="3">Mercury.chat</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="whatsapp-enable" type="checkbox" class="styled" @if(App\Settings::where('type', 'whatsappProvider')->value('state') == 1) checked="checked" @else @endif>
                                                                Enable WhatsApp Integrations
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary" onclick="document.forms['whatsappSetting'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                           

                        </div> -->
                        */ ?>
                        <div class="tab-pane fade in active" id="settings">
                            @if( !isset($customerData->can_buy) or $customerData->can_buy != 0 )    
                            <div class="col-lg-6">
                                <!-- Microsystem WiFi subscription -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">Microsystem WiFi subscription</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body" >
                                    <form action="{{ url('subscription') }}" method="POST" id="subscription_id">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    
                                                        <?php 
                                                        date_default_timezone_set("Africa/Cairo");
                                                        $today = date("Y-m-d");
                                                        $today_time = date("g:i a");
                                                        $created_at = date("Y-m-d H:i:s");
                                                        
                                                        //print_r($customerData);
                                                        echo "<div class='alert alert-info alert-styled-left alert-bordered'><center><span class='text-semibold'>";
                                                        if(isset($customerData->package_id) and $customerData->package_id != "0")
                                                        {
                                                            echo "Current subscription: ";
                                                            echo DB::table($packagesTable)->where('id',$customerData->package_id)->value('name');
                                                        }else{ echo "Please select from the following subscription types:"; }
                                                        echo "</center></div>";

                                                        if(isset($customerData->package_id) and $customerData->package_id != "0" )
                                                        {
                                                            $packageData = DB::table($packagesTable)->where('id', $customerData->package_id )->first();
                             
                                                            $date1 = strtotime("$customerData->next_bill");
                                                            $date2 = strtotime("$today");
                                                            $diff = ($date1 - $date2);
                                                            $datediff=round($diff/86400);
                                                            
                                                            if( $datediff <= 5 ){ $alertColor = "alert-danger"; }else { $alertColor = "alert-info"; }
                                                            echo "<div class='alert $alertColor alert-styled-left alert-bordered'><center><span class='text-semibold'>";
                                                            
                                                            if($packageData->modules == "internet_management"){$currPackage = "Automated internet Management module"; }
                                                            elseif($packageData->modules == "wifi_marketing"){$currPackage = "Automated internet management + Smart WiFi marketing modules"; }
                                                            else{ $currPackage=" "; }
                                                            echo "Registered Modules: $currPackage <br>";
                                                            
                                                            echo "Concurrent devices: $packageData->concurrent_devices <br>"; 
                                                            
                                                            // if($customerData->payasyougo == 1){ $payasyougoState = "On"; }else{ $payasyougoState = "Off"; }
                                                            // echo "Pay As You Go state: $payasyougoState <br>";
                                                            // $totalInvoicesValue = DB::table('invoices')->where('type', 'payasyougo')->where('state', '0')->where('customer_id', $customerData->id)->sum('amount');
                                                            // echo "Pay As You Go Outstanding invoices: $totalInvoicesValue $customerData->currency <br>";

                                                            echo "Renewal date: $customerData->next_bill <br>";
                                                            
                                                            if($datediff == "0"){
                                                                echo "<font size='5' color='red'><strong> Hurry up to renew, your subscription will end Today! </strong></font><br>";
                                                            }elseif ($datediff < 0){
                                                                echo "<font size='2'><font size='5'><strong> Your subscription has been ended, Please renew to activate service again.</strong></font><br>";
                                                            }else{
                                                                echo "<font size='2'> <strong> Remaining Days </strong> </font> <br> <font size='6'><strong> $datediff </strong></font><br>";
                                                            }
                                                            
                                                            echo "</center></div>";
                                                        }
                                                        ?>
                                                        <!--   -->
                                                        <div class="form-group col-lg-12">
                                                            <label class="control-label col-lg-3">Module</label>
                                                            <div class="col-lg-9">
                                                                <div class="form-group has-feedback has-feedback-left">
                                                                
                                                                    <select onChange="myCalculation()" class="selectt" id='modules' name="modules">
                                                                        <option @if(isset($packageData->modules) and $packageData->modules == "internet_management" ) selected @endif value="internet_management">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Automated Internet Management Module </option>
                                                                        <option @if(isset($packageData->modules) and $packageData->modules == "wifi_marketing" ) selected @endif value="wifi_marketing">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Auto Internet Management + Smart WiFi marketing Modules </option>
                                                                    </select>
                                                                    <div class="form-control-feedback">
                                                                        <i class=" icon-bag"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--   -->
                                                        <div class="form-group col-lg-12">
                                                            <label class="control-label col-lg-3">Concurrent devices</label>
                                                            <div class="col-lg-9">
                                                                <div class="form-group has-feedback has-feedback-left">
                                                                
                                                                    <select onChange="myCalculation()" class="selectt" id='concurrent' name="concurrent">
                                                                        <option @if(isset($packageData->concurrent_devices) and $packageData->concurrent_devices == "20" ) selected @endif value="20">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 20 </option>
                                                                        <option @if(isset($packageData->concurrent_devices) and $packageData->concurrent_devices == "50" ) selected @endif value="50">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 50 </option>
                                                                        <option @if(isset($packageData->concurrent_devices) and $packageData->concurrent_devices == "100" ) selected @endif value="100">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 100 </option>
                                                                        <option @if(isset($packageData->concurrent_devices) and $packageData->concurrent_devices == "150" ) selected @endif value="150">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 150 </option>
                                                                        <option @if(isset($packageData->concurrent_devices) and $packageData->concurrent_devices == "250" ) selected @endif value="250">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 250 </option>
                                                                        <option @if(isset($packageData->concurrent_devices) and $packageData->concurrent_devices == "500" ) selected @endif value="500">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 500 </option>
                                                                    </select>
                                                                    <div class="form-control-feedback">
                                                                        <i class="icon-users4"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--   -->
                                                        <div class="form-group col-lg-12">
                                                            <label class="control-label col-lg-3">Billing Cycle</label>
                                                            <div class="col-lg-9">
                                                                <div class="form-group has-feedback has-feedback-left">
                                                                
                                                                    <select onChange="myCalculation()" class="selectt" id='billing_cycle' name="billing_cycle">
                                                                        <!-- <option @if(isset($packageData->months) and $packageData->months == "1" ) selected @endif value="1">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Monthly</option> -->
                                                                        <option @if(isset($packageData->months) and $packageData->months == "3" ) selected @endif value="3">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Quarterly (3 Months)</option>
                                                                        <option @if(isset($packageData->months) and $packageData->months == "12" ) selected @endif value="12">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Yearly (10% off) </option>
                                                                    </select>
                                                                    <div class="form-control-feedback">
                                                                        <i class="icon-rotate-cw2"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--   -->
                                                        <!-- <div class="form-group col-lg-12">
                                                            <label class="control-label col-lg-3">Subscription type</label>
                                                            <div class="col-lg-9">
                                                                <div class="form-group has-feedback has-feedback-left">
                                                                
                                                                    <select onChange="myCalculation()" class="selectt" id='packageID' name="package">
                                                                        @foreach(DB::table($packagesTable)->where('show','1')->get() as $package)
                                                                            <option @if($customerData->package_id == $package->id) selected @endif value="{{$package->id}}">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp {{$package->name}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="form-control-feedback">
                                                                        <i class=" icon-credit-card"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                    <!--   -->
                                                    <div class="form-group col-lg-12">
                                                        <label class="control-label col-lg-3">Payment method</label>
                                                        <div class="col-lg-9">
                                                            <div class="form-group has-feedback has-feedback-left">
                                                                <select onChange="myCalculation()" class="selectt" id='paymentMethod' name="paymentMethod">
                                                                    <option value="card">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Visa / Master Card</option>
                                                                    @if( $customerData->currency != "USD") <option value="cash">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Cash ( by courier service ) </option> @endif
                                                                    @if( $customerData->currency != "USD") <option value="fawry">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Fawry</option>@endif
                                                                    <option value="qnb">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Bank transfer ( QNB Alahli )</option>
                                                                    <option value="alex">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Bank transfer ( Alexandria Bank )</option>
                                                                    @if( $customerData->currency != "USD") <option value="wallet">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Mobile Wallet ( Etisalat Felosy | Ahly PhoneCash | CIB Smart Wallet | Saib Cashati | ABK Wallet | Qahera Cash | Audi DoPay )</option> @endif
                                                                    
                                                                    
                                                                </select>
                                                                <div class="form-control-feedback">
                                                                    <i class="icon-credit-card"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--   -->
                                                    <p id="dynamic_mobile_wallet_number"> </p>
                                                    <!--   -->
                                                    <p id="dynamic_mobile_wallet_number_form"> 
                                                        <input type="hidden" class="default_mobile_wallet_check" name="mobile_wallet_numner" id="mobile_wallet_numner" value="0">
                                                        <input type="hidden" class="default_mobile_wallet_check" name="mobile_wallet_check" id="mobile_wallet_check" value="0">
                                                    </p>
                                                    
                                                </div>
                                            </div>

                                            <center><h1><font color="red"><div class='alert alert-warning alert-bordered'><p onLoad="myCalculation()" id="calculation"> </p></div></font></h1></center>
                                            <div class="text-right">
                                                <button type="button" class="click_subscribe btn btn-primary btn-block" data-target="#subscription_modal"><b><i class="icon-cash3"></i> &nbsp&nbsp Pay Now!</b></button>
                                            </div>
                                            <br>
                                            <center>
                                                <img src="assets/images/f_visa.png" width='100' height='33.3' title="">
                                                <img src="assets/images/f_mastercard.png" width='100' height='33.3' title="">
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_etisalat.png" width='100' height='33.3' title="">@endif
                                                <img src="assets/images/f_qnb.png" width='100' height='33.3' title="">
                                                <img src="assets/images/f_alex.png" width='100' height='33.3' title="">

                                                <img src="assets/images/f_accept.png" width='100' height='33.3' title="">
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_cib.png" width='100' height='33.3' title="">@endif
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_alahly.png" width='100' height='33.3' title="">@endif
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_abk.png" width='100' height='33.3' title="">@endif
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_cash.png" width='100' height='33.3' title="">@endif

                                                @if( $customerData->currency != "USD")<img src="assets/images/f_cashati2.png" width='100' height='33.3' title="">@endif
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_fawry.png" width='100' height='33.3' title=""> @endif
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_quhira.png" width='100' height='33.3' title="">@endif
                                                @if( $customerData->currency != "USD")<img src="assets/images/f_dopay.png" width='100' height='33.3' title="">@endif
                                            <center>
                                        </form>
                                    </div>
                                </div>
                            </div>  
                            @endif
                            <div class="col-lg-6">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">Pay As You Go</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>


                                    <?php 
                                    $totalInvoicesAmount = DB::table('invoices')->where('type', 'payasyougo')->where('state', '0')->where('customer_id', $customerData->id)->sum('amount');
                                    ?>
                                    <div class="panel-body" >
                                        <div class='alert alert-info alert-styled-left alert-bordered'><center><span class='text-semibold'>
                                                  Pay As You Go service is to allow your system to exceed concurrent devices, and just pay for exceeded days.
                                        <!-- -->
                                                <br>
                                                <label>
                                                    <input type="checkbox" id='payasyougoStateBtn' name="payasyougoStateBtn"  @if( $customerData->payasyougo == 1 ) checked="checked" value="1" @else value="0" @endif class="switchery payasyougoStateBtn" data-switchery="true">
                                                </label>
                                               
                                        <!-- -->

                                        </center></div>
                                        @if(isset($totalInvoicesAmount) and $totalInvoicesAmount != 0)
                                        
                                            <table class="table" width="100%" id="table-invoices">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice State</th>
                                                        <th>Concurrent level</th>
                                                        <th>Cost</th>
                                                        <th>Issue date</th>
                                                        <th>Paid date</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                </thead>
                                            </table>
        
                                            <!--   -->
                                            <br>
                                            <form action="" method="POST" id="payasyougoSubscription">
                                                <div class="form-group col-lg-12">
                                                    <label class="control-label col-lg-3">Payment method</label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <select class="selectt" id='payasyougoPaymentMethod' name="payasyougoPaymentMethod">
                                                                <option value="card">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Visa / Master Card</option>
                                                                @if( $customerData->currency != "USD")<option value="cash">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Cash ( by courier service ) </option> @endif
                                                                @if( $customerData->currency != "USD")<option value="fawry">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Fawry</option> @endif
                                                                <option value="qnb">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Bank transfer ( QNB Alahli )</option> 
                                                                <option value="alex">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Bank transfer ( Alexandria Bank )</option>
                                                                @if( $customerData->currency != "USD") <option value="wallet">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Mobile Wallet ( Etisalat Felosy | Ahly PhoneCash | CIB Smart Wallet | Saib Cashati | ABK Wallet | Qahera Cash | Audi DoPay )</option> @endif
                                                                
                                                                
                                                            </select>
                                                            <div class="form-control-feedback">
                                                                <i class="icon-credit-card"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--   -->
                                                <p id="dynamic_mobile_wallet_number2"> </p>
                                                <!--   -->
                                                <p id="dynamic_mobile_wallet_number_form2"> 
                                                    <input type="hidden" class="default_mobile_wallet_check2" name="mobile_wallet_numner2" id="mobile_wallet_numner2" value="0">
                                                    <input type="hidden" class="default_mobile_wallet_check2" name="mobile_wallet_check2" id="mobile_wallet_check2" value="0">
                                                </p>
                                                <!--   -->
                                                <br>
                                                <center><h1><font color="red"><div class='alert alert-warning alert-bordered'><p> Total Invoices: @if( isset($customerData) ) {{ DB::table('invoices')->where('type', 'payasyougo')->where('state', '0')->where('customer_id', $customerData->id)->sum('amount') }} {{$customerData->currency}} @endif</p></div></font></h1></center>
                                                <div class="text-right">
                                                    <input type="hidden" name='payasyougoAmount' id='payasyougoAmount' value='{{$totalInvoicesAmount}}'>
                                                    <button type="button" class="click_payasyougoPaymentMethod btn btn-primary btn-block" data-target="#subscription_modal"><b><i class="icon-cash3"></i> &nbsp&nbsp Pay Now!</b></button>
                                                </div>
                                            </form>
                                        
                                        @endif
                                    </div>
                                </div> 
                            </div>                             

                            <div class="col-lg-6">
                                <!-- Profile info -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">System Settings</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body" style="display: none;">
                                        <form action="{{ url('Systemsetting') }}" method="POST" id="Save_setting">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>System name</label>
                                                        <input name="appname" type="text" value="{{ App\Settings::where('type', 'app_name')->value('value') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Description</label>
                                                        <input name="description" type="text" value="{{ App\Settings::where('type', 'description')->value('value') }}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Address line 1</label>
                                                        <input name="address" type="text" value="{{ App\Settings::where('type', 'address')->value('value') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Address line 2</label>
                                                        <input type="text" value="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <!--div class="col-md-3">
                                                        <label>Auto suspend</label>
                                                        <select class="select0">
                                                            <option value="0">Off</option>
                                                            <option value="1">On</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Auto Run</label>
                                                        <select class="select0">
                                                            <option value="0">Off</option>
                                                            <option value="1">On</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Guest Login</label>
                                                        <select class="select0">
                                                            <option value="0">Off</option>
                                                            <option value="1">On</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Auto catch MAC-Address</label>
                                                        <select class="select0">
                                                            <option value="0">Off</option>
                                                            <option value="1">On</option>
                                                        </select>
                                                    </div>-->
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Email</label>
                                                        <input name="email" type="text" value="{{ App\Settings::where('type', 'email')->value('value') }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Country</label>
                                                        <select name="country" class="select" value="{{ App\Settings::where('type', 'country')->value('value') }}">
                                                            @foreach($countries as $countrie)
                                                            <option @if(App\Settings::where('type', 'country')->value('value') == $countrie) selected @endif value="{{ $countrie }}">{{ $countrie }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!--
                                                    <div class="col-md-4">
                                                        <label>Time Zone</label>
                                                        <select class="select">
                                                        <option value=""></option>
                                                        </select>
                                                    </div>
                                                    -->
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Phone</label>
                                                        <input name="phone" type="text" value="{{ App\Settings::where('type', 'phone')->value('value') }}" class="form-control">
                                                        <span class="help-block">+201145929570</span>
                                                    </div>
													<div class="col-md-6">
                                                        <label>Currency</label>
                                                        <input name="currency" type="text" value="{{ App\Settings::where('type', 'currency')->value('value') }}" class="form-control">
                                                        <span class="help-block">$, LE, etc...</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- -->
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Registration Type</label>
                                                        <select name="regType" class="selectt">
                                                            <?php $RegistrationType=App\Network::value('r_type'); ?>
                                                            <option @if($RegistrationType==2) selected @endif value="2">SMS Verification</option>
                                                            <option @if($RegistrationType==0) selected @endif value="0">Dircet without Approvement</option>
                                                            <option @if($RegistrationType==1) selected @endif value="1">Wait Admin Approvement</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Internet Mode</label>
                                                        <select name="commercial" class="selectt">
                                                            <?php $commercial=App\Network::value('commercial'); ?>
                                                            <option @if($commercial==1) selected @endif value="1">Free</option>
                                                            <option @if($commercial==0) selected @endif value="0">Commercial (selling internet)</option>
                                                            <option @if($commercial==2) selected @endif value="2">Free + Commercial</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <!-- -->

                                            <div class="panel-heading">
                                                <h6 class="panel-title"><strong>User control panel</strong></h6>
                                                <hr>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Terms and Conditions</label>
                                                        <?php 
                                                        $terms=App\Settings::where('type', 'terms')->value('value');
                                                        if(!isset($terms))
                                                        {
                                                            $terms="All Internet data that is composed, transmitted and received by our network.
                                computer systems is considered to belong to our business and is recognized as part of its official data. It is therefore subject to disclosure for legal reasons or to other appropriate third parties.";
                                                        }
                                                        ?>
                                                        <textarea name="terms" rows='5' class="form-control">{{ $terms }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getPassword" @if(App\Settings::where('type', 'getPassword')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <!-- <span class="text-semibold">Require</span> Password on registration ( or make password the same mobile number ). -->
                                                                <span class="text-semibold">Require</span> Password on registration.
                                                            </label>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getUserName" @if(App\Settings::where('type', 'getUserName')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Replace</span> Mobile number with username on registration.
                                                            </label>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="mergeAccounts" @if(App\Settings::where('type', 'mergeAccounts')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Auto Merge</span> Accounts (Allow users to register all devices at the same account).
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getName" @if(App\Settings::where('type', 'getName')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Require</span> Name on registration.
                                                            </label>
                                                        </div>

                                                    </div>

                                                    <!-- <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getNetwork" @if(App\Settings::where('type', 'getNetwork')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Require</span> Network on registration.
                                                            </label>
                                                        </div>

                                                    </div> -->

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getGender" @if(App\Settings::where('type', 'getGender')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Require</span> Gender on registration.
                                                            </label>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getEmail" @if(App\Settings::where('type', 'getEmail')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Require</span> E-Mail on registration.
                                                            </label>
                                                        </div>

                                                    </div>
                                                    

                                                </div>

                                                
                                                <div class="row">

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="signupDefault" @if(App\Settings::where('type', 'signupDefault')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Set Signup tab</span> to be default.
                                                            </label>
                                                        </div>

                                                    </div>
                                                    @if(App\Settings::where('type', 'landing')->value('value')=='default')
                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="alwaysOpenPasswordLoginInUserCP" @if(App\Settings::where('type', 'alwaysOpenPasswordLoginInUserCP')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Always</span> open login and registration model on user panel.
                                                            </label>
                                                        </div>

                                                    </div>
                                                    @endif
                                                    <div class="col-md-4">
                                                            
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="disableLogin" @if(App\Settings::where('type', 'disableLogin')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Disable</span> user login.
                                                            </label>
                                                        </div>

                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="row">

                                                    <div class="col-md-4">

                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getMobileInSignupTab" @if(App\Settings::where('type', 'getMobileInSignupTab')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Require</span> Mobile on registration.
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4">
                                                            
                                                        <div class="checkbox checkbox-switchery switchery-lg">
                                                            <label>
                                                                <input type="checkbox" name="getCardSerialInSignupTab" @if(App\Settings::where('type', 'getCardSerialInSignupTab')->value('state')==1) checked="checked" @endif class="switchery" value="1" data-switchery="true">
                                                                <span class="text-semibold">Require</span> card serial on registration.
                                                            </label>
                                                        </div>

                                                    </div>
                                                    
                                                </div>
                                                
                                                

                                            </div>


                                            <div class="text-right">
                                                <button type="button" class="btn btn-primary" onclick="document.forms['Save_setting'].submit(); return false;" >Save <i class="icon-arrow-right14 position-right"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- Profile info -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">Logo</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <!--<li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>-->
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="panel-body" style="display: none;">
                                        <div class="col-md-12">
                                        
                                            {{ Form::open(array('url' => 'uploadLogo', 'files' => true, 'method' => 'post')) }}
                                                {{ csrf_field() }}
                                                <center><img src='{{ asset('/') }}upload/{{ App\Settings::where('type','logo')->value('value') }}' style='width: 80%;'></center>
                                                <input name="file" type="file" class="file-input" multiple="multiple">
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- /profile info -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- /user profile -->


        @include('..back-end.footer')

    </div>
        <!-- /content area -->
    @section('js')
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/uploaders/fileinput2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <!--<script type="text/javascript" src="assets/js/pages/uploader_bootstrap.js"></script>-->

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
        // Basic initialization (get invoices data from URL )
        $('.datatable-button-init-basic').DataTable({
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                ajax:{"url" : "payasyougoInvoices",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
                buttons: [
                    {extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel'},
                    {extend: 'pdf'},
                    {extend: 'print'}
                ]
            }
        });
        // Basic initialization
        var table = $('#table-invoices').DataTable({
            responsive: {
            details: {
               type: 'column',
               target: -1
               }
            },
            ajax:{"url" : "payasyougoInvoices",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
                    // {extend: 'copy',text: '<i title="Copy" class="icon-copy3"></i>'},
                    // {extend: 'csv' ,text: '<i title="Export to CSV sheet." class="icon-file-spreadsheet"></i>'},
                    {extend: 'excel' ,text: '<i title="Export to excel sheet." class="icon-file-excel"></i>'},
                    // {extend: 'pdf' , text: '<i title="Export to PDF file." class="icon-file-pdf"></i>'},
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
            { "data": null,
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.state == 0)
              return '<span class="label border-left-danger label-striped">Pending Payment</span>' ;
              else
              return '<span class="label border-left-success label-striped">Paid</span>';
            }},
            { "data": "concurrent" },
            { "data": "amount" },
            { "data": "issue_date" },
            { "data": "paid_date" },
            { "data":null,"defaultContent":"" }
            ]
        });

        // WhatsApp integration datatable
        var tableWhatsapp = $('#table-whatsappChannels').DataTable({
            responsive: {
            details: {
               type: 'column',
               target: -1
               }
            },
            ajax:{"url" : "getWhatsappChannels",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
                    // {extend: 'copy',text: '<i title="Copy" class="icon-copy3"></i>'},
                    // {extend: 'csv' ,text: '<i title="Export to CSV sheet." class="icon-file-spreadsheet"></i>'},
                    {extend: 'excel' ,text: '<i title="Export to excel sheet." class="icon-file-excel"></i>'},
                    // {extend: 'pdf' , text: '<i title="Export to PDF file." class="icon-file-pdf"></i>'},
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
            { "data": null,
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.state == 1)
              return '<span class="label border-left-success label-striped">Running</span>' ;
              else if(data.state == 2)
              return '<span class="label border-left-danger label-striped">Unregistered</span>';
              else if(data.state == 3)
              return '<span class="label border-left-danger label-striped">Blocked</span>';
              else if(data.state == 0)
              return '<span class="label border-left-danger label-striped">Inactive</span>';
              else if(data.state == 4)
              return '<span class="label border-left-danger label-striped">Unregistered Or Removed</span>';
              else
              return '<span class="label border-left-danger label-striped">Unknown</span>';
            }},
            {"render": function ( type, full, data, meta ) {
                return '<a href="#" title="Edit" class="editWhatsappIntegration" >'+data.server_mobile+'</a>';
            }},
            { "data": "integration_type" },
            {
                "render": function (type, full, data, meta) {
                    return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                            '<li><a href="#" class="restartWhatsappIntegration"><i class="icon-power-cord"></i> Restart</a></li>' +
                            '<li><a href="#" class="editWhatsappIntegration"><i class="icon-pencil3"></i> Edit</a></li>' +
                            '<li><a href="#" class="deleteWhatsappIntegration"><i class="icon-cross3"></i> Delete</a></li>' +
                            '</ul> </li> </ul>';
                }
            },
            { "data":null,"defaultContent":"" }
            ]
            
        });

        $('#table-whatsappChannels tbody').on('click', '.editWhatsappIntegration', function () {
            var that = this;
            var data = tableWhatsapp.row($(that).parents('tr')).data();
            if (data == null) {
                data = tableWhatsapp.row($(that).parents('tr').prev()).data();
            }
            
            // LOADING THE AJAX MODAL
            jQuery('#modal_ajax_editWhatsappIntegration').modal('show', {backdrop: 'true'});


            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'viewEditOfWhatsappIntegration/' + data.id,
                success: function (response) {
                    jQuery('#modal_ajax_editWhatsappIntegration .modal-body').html(response);
                }
            });
        });

        $('#table-whatsappChannels tbody').on('click', '.deleteWhatsappIntegration', function () {
                var that = this;
                swal({
                title: "Are you sure?",
                text: "You will not be able to recover the WhatsApp integration again!",
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
                    var data = tableWhatsapp.row($(that).parents('tr')).data();
                    if (data == null) {
                        data = tableWhatsapp.row($(that).parents('tr').prev()).data();
                    }
                    $.ajax({
                        url: 'deleteWhatsappIntegration/' + data.id,
                        success: function (data) {
                            tableWhatsapp.row($(that).parents('tr')).remove().draw();
                            swal("Deleted!", "WhatsApp integration has been deleted successfully.", "error");
                            
                        },
                        error: function () {
                            swal("Cancelled", "WhatsApp integration is safe :)", "success");
                        }
                    });
                } else {
                    swal("Cancelled", "Your delete request has been Cancelled :)", "error");
                }

            });

        });

        $('#table-whatsappChannels tbody').on('click', '.restartWhatsappIntegration', function () {
                var that = this;
                swal({
                title: "Are you sure?",
                text: "You want to restart your WhatsApp instance!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, restart it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
            },
            function (isConfirm) {

                if (isConfirm) {
                    var data = tableWhatsapp.row($(that).parents('tr')).data();
                    if (data == null) {
                        data = tableWhatsapp.row($(that).parents('tr').prev()).data();
                    }
                    $.ajax({
                        url: 'restartWhatsappIntegration/' + data.id,
                        success: function (data) {
                            swal("Restarted", "Your WhatsApp instance has been restarted successfully.", "success");
                            
                        },
                        error: function () {
                            swal("Cancelled", "restart has been cancelled", "error");
                        }
                    });
                } else {
                    swal("Cancelled", "Your restart request has been Cancelled", "success");
                }

            });

        });
        
        // PMS integration datatable
        var tablePms = $('#table-pms').DataTable({
            responsive: {
            details: {
               type: 'column',
               target: -1
               }
            },
            ajax:{"url" : "getPms",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
                    // {extend: 'copy',text: '<i title="Copy" class="icon-copy3"></i>'},
                    // {extend: 'csv' ,text: '<i title="Export to CSV sheet." class="icon-file-spreadsheet"></i>'},
                    {extend: 'excel' ,text: '<i title="Export to excel sheet." class="icon-file-excel"></i>'},
                    // {extend: 'pdf' , text: '<i title="Export to PDF file." class="icon-file-pdf"></i>'},
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
            { "data": null,
              "searchable": false,
              "render": function ( type, full, data, meta ) {
              if(data.state == 1)
              return '<span class="label border-left-success label-striped">Enabled</span>' ;
              else
              return '<span class="label border-left-danger label-striped">Disabled</span>';
            }},
            {"render": function ( type, full, data, meta ) {
                return '<a href="#" title="Edit" class="editPmsIntegration" >'+data.name+'</a>';
            }},
            { "data": "connection_type" },
            { "data": "type" },
            {
                "render": function (type, full, data, meta) {
                    if(data.connection_type == 'database' && data.last_check_since_seconds <= 300)
                        return '<span title="Last Check: '+data.last_check+' ('+ data.last_check_since +')" class="label bg-success">Connected</span>';
                    else if(data.connection_type == 'database' && data.last_check_since_seconds > 300)
                        return '<span title="Last Check: '+data.last_check+' ('+ data.last_check_since +')" class="label bg-danger">Disconnected</span>';
                    else if(data.connection_type == 'interface' && data.last_check_since_seconds <= 60)
                        return '<span title="Last Check: '+data.last_check+' ('+ data.last_check_since +')" class="label bg-success">Connected</span>';
                    else if(data.connection_type == 'interface' && data.last_check_since_seconds > 60)
                        return '<span title="Last Check: '+data.last_check+' ('+ data.last_check_since +')" class="label bg-danger">Disconnected</span>';
                    else
                        return '<p title="'+data.last_check+'">'+ data.last_check_since +'</p>';
                }
            },
            {
                "render": function (type, full, data, meta) {
                    return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                            '<li><a href="#" class="restartPmsIntrface"><i class="icon-pencil3"></i> Restart / Swap</a></li>' +
                            '<li><a href="#" class="editPmsIntegration"><i class="icon-pencil3"></i> Edit</a></li>' +
                            '<li><a href="#" class="deletePmsIntegration"><i class="icon-cross3"></i> Delete</a></li>' +
                            '</ul> </li> </ul>';
                }
            },
            { "data":null,"defaultContent":"" }
            ]
        });
        
        $("#connection_type").change(function(){
            var connectiontype = $("#connection_type option:selected").val();
            
            if(connectiontype == 'interface'){
                $('.interface_connection_variables').show();
                $('.database_connection_variables').hide();
            }

            if(connectiontype == 'database'){
                $('.database_connection_variables').show();
                $('.interface_connection_variables').hide();
            }
        });
        
        $('#table-pms tbody').on('click', '.editPmsIntegration', function () {
            var that = this;
            var data = tablePms.row($(that).parents('tr')).data();
            if (data == null) {
                data = tablePms.row($(that).parents('tr').prev()).data();
            }

            // LOADING THE AJAX MODAL
            jQuery('#modal_ajax').modal('show', {backdrop: 'true'});


            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'viewEditOfPmsIntegration/' + data.id,
                success: function (response) {
                    jQuery('#modal_ajax .modal-body').html(response);
                }
            });
        });

        $('#table-pms tbody').on('click', '.deletePmsIntegration', function () {
                var that = this;
                swal({
                title: "Are you sure?",
                text: "You will not be able to recover the PMS integration again!",
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
                    var data = tablePms.row($(that).parents('tr')).data();
                    if (data == null) {
                        data = tablePms.row($(that).parents('tr').prev()).data();
                    }
                    $.ajax({
                        url: 'deletePmsIntegration/' + data.id,
                        success: function (data) {
                            tablePms.row($(that).parents('tr')).remove().draw();
                            swal("Deleted!", "PMS integration has been deleted successfully.", "error");
                            
                        },
                        error: function () {
                            swal("Cancelled", "PMS integration is safe :)", "success");
                        }
                    });
                } else {
                    swal("Cancelled", "Your delete request has been Cancelled :)", "error");
                }

            });

        });

        $('#table-pms tbody').on('click', '.restartPmsIntrface', function () {
                var that = this;
                swal({
                title: "Are you sure?",
                text: "The PMS interface will restart within 10 seconds!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, restart it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
            },
            function (isConfirm) {

                if (isConfirm) {
                    var data = tablePms.row($(that).parents('tr')).data();
                    if (data == null) {
                        data = tablePms.row($(that).parents('tr').prev()).data();
                    }
                    $.ajax({
                        url: 'restartPmsIntrface?id=' + data.id,
                        success: function (data) {
                            swal("Restarting!", "PMS interface will restart within 10 seconds.", "success");
                            
                        },
                        error: function () {
                            swal("Cancelled", "Can't restart the PMS interface.", "error");
                        }
                    });
                } else {
                    swal("Cancelled", "Restart has been Cancelled.", "error");
                }

            });

        });

        // /PMS integration

    // Column selectors
        $('.datatable-button-html5-columns').DataTable({
            buttons: {
                buttons: [
                    {
                        extend: 'copyHtml5',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: [ 0, ':visible' ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: [0, 1, 2, 5]
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                        className: 'btn btn-default btn-icon'
                    }
                ]
            }
        });

    // Scrollable datatable
        $('.datatable-scroll-y').DataTable({
        autoWidth: true,
        scrollY: 200
    });
        $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
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
    function changeFunc() {
        var selectBox = document.getElementById("selectBox");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        window.open("http://" + selectedValue + '/index.blade.php', '_blank');
        
        //alert(selectedValue);
    } 
    // Basic example
    $('.file-input').fileinput({
        browseLabel: 'Browse',
        browseIcon: '<i class="icon-file-plus"></i>',
        uploadIcon: '<i class="icon-file-upload2"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>'
        },
        @if(App\Settings::where('type','logo')->value('value') !== "")
        initialPreview: [
            
        ],
        @endif
        initialCaption: "No file selected",
        overwriteInitial: false,
    });

    $('#subscription_id').on('click', '.click_subscribe', function () {
        var that = this;
        // var packageID = document.getElementById("packageID").value;
        var paymentMethod = document.getElementById("paymentMethod").value;
        var modules = document.getElementById("modules").value;
        var concurrent = document.getElementById("concurrent").value;
        var billing_cycle = document.getElementById("billing_cycle").value;
        
        var mobile_wallet_numner = document.getElementById("mobile_wallet_numner").value;
        var mobile_wallet_check = document.getElementById("mobile_wallet_check").value;
        var type = "package";

        jQuery('#subscription_modal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');
        // LOADING THE AJAX MODAL
        jQuery('#subscription_modal').modal('show', {backdrop: 'true'});
        
        if(mobile_wallet_check == "1"){
            $.ajax({
            // url: 'payment/' + '1' + '-' + 'groups',
            url: 'payment/'+type+'/'+paymentMethod+'/'+modules+'/'+concurrent+'/'+billing_cycle+'/'+mobile_wallet_numner,
            success: function (response) {
                jQuery('#subscription_modal .modal-body').html(response);
            }
            });
        }else{
            $.ajax({
            // url: 'payment/' + '1' + '-' + 'groups',
            url: 'payment/'+type+'/'+paymentMethod+'/'+modules+'/'+concurrent+'/'+billing_cycle+'/0',
            success: function (response) {
                jQuery('#subscription_modal .modal-body').html(response);
            }
            });
        }
        
    });


    $('#payasyougoSubscription').on('click', '.click_payasyougoPaymentMethod', function () {
        var that = this;
        var payasyougoPaymentMethod = document.getElementById("payasyougoPaymentMethod").value;
        var payasyougoAmount = document.getElementById("payasyougoAmount").value;
        var type = "payasyougo";

        var mobile_wallet_numner = document.getElementById("mobile_wallet_numner2").value;
        var mobile_wallet_check = document.getElementById("mobile_wallet_check2").value;

        if (mobile_wallet_numner == '') {
            var mobile_wallet_numner = 0;
        }
        jQuery('#subscription_modal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');
        // LOADING THE AJAX MODAL
        jQuery('#subscription_modal').modal('show', {backdrop: 'true'});
        
        if(mobile_wallet_check == "1"){
            $.ajax({
            // payment/'+type+'/'+paymentMethod+'/'+modules+'/'+concurrent+'/'+billing_cycle+'/0',
            url: 'payment/'+type+'/'+payasyougoPaymentMethod+'/0/0/0/'+mobile_wallet_numner+'/'+payasyougoAmount,
            success: function (response) {
                jQuery('#subscription_modal .modal-body').html(response);
            }
            });
        }else{
            $.ajax({
            // payment/'+type+'/'+paymentMethod+'/'+modules+'/'+concurrent+'/'+billing_cycle+'/0',
            url: 'payment/'+type+'/'+payasyougoPaymentMethod+'/0/0/0/0/'+payasyougoAmount,
            success: function (response) {
                jQuery('#subscription_modal .modal-body').html(response);
            }
            });
        }        
        
    });
    window.onload = function() {
        myCalculation();
    };
    <?php 
    // if( $customerData->currency == "USD"){ $currencyCalculationBefore = "("; $currencyCalculationAfter = "/17).toFixed(2);"; }
    // else{ $currencyCalculationBefore = ""; $currencyCalculationAfter = ";"; }
    $currencyCalculationBefore = ""; $currencyCalculationAfter = ";";
    ?>
    function myCalculation() {  

        var modules = document.getElementById("modules").value;
        var concurrent = document.getElementById("concurrent").value;
        var billing_cycle = document.getElementById("billing_cycle").value;
        var paymentMethod = document.getElementById("paymentMethod").value;
        
        if(billing_cycle == 1){
            if(concurrent == 20){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','20')->where('months','1')->value("$priceColumnName"); ?>
            }else if(concurrent == 50){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','50')->where('months','1')->value("$priceColumnName"); ?>
            }else if(concurrent == 100){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','100')->where('months','1')->value("$priceColumnName"); ?>
            }else if(concurrent == 150){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','150')->where('months','1')->value("$priceColumnName"); ?>
            }else if(concurrent == 250){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','250')->where('months','1')->value("$priceColumnName"); ?>
            }else if(concurrent == 500){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','500')->where('months','1')->value("$priceColumnName"); ?>
            }
        }else if(billing_cycle == 3){
            if(concurrent == 20){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','20')->where('months','3')->value("$priceColumnName"); ?>
            }else if(concurrent == 50){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','50')->where('months','3')->value("$priceColumnName"); ?>
            }else if(concurrent == 100){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','100')->where('months','3')->value("$priceColumnName"); ?>
            }else if(concurrent == 150){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','150')->where('months','3')->value("$priceColumnName"); ?>
            }else if(concurrent == 250){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','250')->where('months','3')->value("$priceColumnName"); ?>
            }else if(concurrent == 500){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','500')->where('months','3')->value("$priceColumnName"); ?>
            }
        }else if(billing_cycle == 12){
            if(concurrent == 20){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','20')->where('months','12')->value("$priceColumnName"); ?>
            }else if(concurrent == 50){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','50')->where('months','12')->value("$priceColumnName"); ?>
            }else if(concurrent == 100){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','100')->where('months','12')->value("$priceColumnName"); ?>
            }else if(concurrent == 150){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','150')->where('months','12')->value("$priceColumnName"); ?>
            }else if(concurrent == 250){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','250')->where('months','12')->value("$priceColumnName"); ?>
            }else if(concurrent == 500){ var amount = <?php echo DB::table($packagesTable)->where('concurrent_devices','500')->where('months','12')->value("$priceColumnName"); ?>
            }
        }
        
        if(modules == "wifi_marketing"){
            var amount = amount * 2; 
        }

        // if(paymentMethod == "cash"){
        //     var addon = Math.round( ( amount * 3 ) / 100 );
        //     var amount = amount + addon; 
        // }

            var totalPrice = "Total Price: ";
            var currency = " {{$customerData->currency}}";
            var finalAmount = amount.toString();
            var step1 = finalAmount.concat(currency);
            var final = totalPrice.concat(step1);
        document.getElementById("calculation").innerHTML = final;
    }

    $('#paymentMethod').change(function () {
        
        var paymentMethod = document.getElementById("paymentMethod").value;

        if( paymentMethod == "wallet" ){
            
            $('.default_mobile_wallet_check').remove();

            var code = '<div id="mobile_wallet_field" class="form-group col-lg-12"> \n';
                code += '<label class="control-label col-lg-3"> Wallet Mobile Number* </label> \n';
                    code += '<div class="col-lg-9"> \n';
                        code += '<div class="form-group has-feedback has-feedback-left"> \n';
                            code += '<input type="text" placeholder="01XXXXXXXXX" class="form-control" id="mobile_wallet_numner" name="mobile_wallet_numner"> \n';
                            code += '<input type="hidden" name="mobile_wallet_check" id="mobile_wallet_check" value="1"> \n';
                            code += '<div class="form-control-feedback"> \n';
                                code += '<i class="icon-mobile"></i> \n';
                            code += '</div> \n';
                        code += '</div> \n';
                    code += '</div> \n';
                code += '</div> \n';
            $('#dynamic_mobile_wallet_number').append(code);
        } else{
            $('#mobile_wallet_field').remove();
            var code = '<input type="hidden" class="default_mobile_wallet_check" name="mobile_wallet_numner" id="mobile_wallet_numner" value="0"> \n';
                code += '<input type="hidden" class="default_mobile_wallet_check" name="mobile_wallet_check" id="mobile_wallet_check" value="0"> \n';
            
            $('#dynamic_mobile_wallet_number_form').append(code);
        }
         
    });

    $('#payasyougoPaymentMethod').change(function () {
        
        var paymentMethod = document.getElementById("payasyougoPaymentMethod").value;

        if( paymentMethod == "wallet" ){
            
            $('.default_mobile_wallet_check2').remove();

            var code = '<div id="mobile_wallet_field2" class="form-group col-lg-12"> \n';
                code += '<label class="control-label col-lg-3"> Wallet Mobile Number* </label> \n';
                    code += '<div class="col-lg-9"> \n';
                        code += '<div class="form-group has-feedback has-feedback-left"> \n';
                            code += '<input type="text" placeholder="01XXXXXXXXX" class="form-control" id="mobile_wallet_numner2" name="mobile_wallet_numner2"> \n';
                            code += '<input type="hidden" name="mobile_wallet_check2" id="mobile_wallet_check2" value="1"> \n';
                            code += '<div class="form-control-feedback"> \n';
                                code += '<i class="icon-mobile"></i> \n';
                            code += '</div> \n';
                        code += '</div> \n';
                    code += '</div> \n';
                code += '</div><div id="mobile_wallet_field3"><br><br><br></div> \n';
            $('#dynamic_mobile_wallet_number2').append(code);
        } else{
            $('#mobile_wallet_field2').remove();
            $('#mobile_wallet_field3').remove();
            var code = '<input type="hidden" class="default_mobile_wallet_check2" name="mobile_wallet_numner2" id="mobile_wallet_numner2" value="0"> \n';
                code += '<input type="hidden" class="default_mobile_wallet_check2" name="mobile_wallet_check2" id="mobile_wallet_check2" value="0"> \n';
            
            $('#dynamic_mobile_wallet_number_form2').append(code);
        }
         
    });

    $('#payasyougoStateBtn').click(function (){

            var state = document.getElementById("payasyougoStateBtn").value;
             
            if( state == 1){
                document.getElementById("payasyougoStateBtn").value = "0";
                 $.ajax({
                    url:'payasyougoState/0'
                });
            }else{
                document.getElementById("payasyougoStateBtn").value = "1";
                $.ajax({
                    url:'payasyougoState/1'
                });
            }
      
    });

    // simble touch POS change state Btn
    $('#simpleTouchPosIntegrationStateBtn').click(function (){

        var state = document.getElementById("simpleTouchPosIntegrationStateBtn").value;
        
        if( state == 1){
            document.getElementById("simpleTouchPosIntegrationStateBtn").value = "0";
            $.ajax({
                url:'simpleTouchPosIntegrationState/0'
            });
        }else{
            document.getElementById("simpleTouchPosIntegrationStateBtn").value = "1";
            $.ajax({
                url:'simpleTouchPosIntegrationState/1'
            });
        }

    });

    // POS rocket change state Btn
    $('#posRocketIntegrationStateBtn').click(function (){

        var state = document.getElementById("posRocketIntegrationStateBtn").value;

        if( state == 1){
            document.getElementById("posRocketIntegrationStateBtn").value = "0";
            $.ajax({
                url:'posRocketIntegrationState/0'
            });
        }else{
            document.getElementById("posRocketIntegrationStateBtn").value = "1";
            $.ajax({
                url:'posRocketIntegrationState/1'
            });
        }

    });

    var switchery1 = document.querySelector('.switchery1')
  , switchery2  = document.querySelector('.switchery2');

    var Guestinhouse = document.querySelector('.Guest-in-house')
  , Complimentary  = document.querySelector('.Complimentary');

  switchery1.onchange = function() {
    if(!switchery1.checked){
        $(".divGuest0").hide();
        $(".divGuest1").hide();
        $(".divGuest2").hide();
    }else{
        $(".divGuest0").show();
        $(".divGuest1").show();
        $(".divGuest2").show();
    }
};
switchery2.onchange = function() {
    if(!switchery2.checked){
        $(".divComplimentary0").hide();
        $(".divComplimentary1").hide();
        $(".divComplimentary2").hide();
    }else{
        $(".divComplimentary0").show();
        $(".divComplimentary1").show();
        $(".divComplimentary2").show();
    }
};


  Guestinhouse.onchange = function() {
    if(!Guestinhouse.checked){
        $(".divGuest1").hide();
    }else{
        $(".divGuest1").show();
    }
  
};
Complimentary.onchange = function() {
    if(!Complimentary.checked){
        $(".divComplimentary1").hide();
    }else{
        $(".divComplimentary1").show();
    }
};
    </script>
@endsection