<h6 class="text-semibold"></h6>

<div class="row">
<form action="{{ url('edit_network/'.$network->id) }}" method="POST" id="edit" class="form-horizontal">
{{ csrf_field() }}

<div class="form-group col-lg-12">
    <label class="control-label col-lg-3">Network Name</label>
    <div class="col-lg-8">
        <div class="form-group has-feedback has-feedback-left">
            <input name="name" type="text" class="form-control input-xlg" value="{!! old('name',$network->name) !!}">
            <div class="form-control-feedback">
                <i class="icon-server"></i>
            </div>
        </div>
    </div>
</div>

<div class="form-group col-lg-12">
    <label class="control-label col-lg-3">Network Mode</label>
    <div class="col-lg-8">
        <select class="select-fixed-single" name="mode">
        <option @if($network->mode==0) selected @endif value="0">Cloud</option>
        <option @if($network->mode==1) selected @endif value="1">Localhost</option>
        </select>
    </div>
</div>

<div class="form-group col-lg-12">
    <label class="control-label col-lg-3">Connection Type</label>
    <div class="col-lg-8">
        <select class="select-fixed-single" name="c_type">
        <option @if($network->c_type==1) selected @endif value="1">Radius</option>
        <option @if($network->c_type==0) selected @endif value="0">API</option>
        </select>
    </div>
</div>

<div class="form-group col-lg-12">
    <label class="control-label col-lg-3">Network state</label>
    <div class="col-lg-8">
        <select class="select-fixed-single" name="state">
        <option @if($network->state==1) selected @endif value="1">Active</option>
        <option @if($network->state==0) selected @endif value="0">Inactive</option>
        </select>
    </div>
</div>

<div class="form-group col-lg-12">
    <label class="control-label col-lg-3">Register Type</label>
    <div class="col-lg-8">
        <select class="select-fixed-single" name="r_type">
        <option @if($network->r_type==0) selected @endif value="0">Dircet</option>
        <option @if($network->r_type==1) selected @endif value="1">Admin</option>
        <option @if($network->r_type==2) selected @endif value="2">SMS</option>
        </select>
    </div>
</div>
&nbsp;
<div class="form-group col-lg-12">
    <label class="control-label col-lg-3">Notes</label>
    <div class="col-lg-8">
        <textarea name="notes" type="text" rows="3" class="form-control input-xlg">{!! old('notes',$network->notes) !!}</textarea>
    </div>
</div>
<!-- Basic accordion -->
<h6 class="content-group text-semibold no-margin-top">
    <center> Additional Setting </center>
    <small class="display-block"></small>
</h6>

<div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right">
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group01">Commercial mode</a>
            </h6>
        </div>
        <div id="accordion-control-right-group01" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">System mode</label>
                    <div class="col-lg-2">
                        <select class="select-fixed-single" name="commercial">
                            <option @if($network->commercial==1) selected @endif value="1">Free</option>
                            <option @if($network->commercial==0) selected @endif value="0">Commercial</option>
                            <option @if($network->commercial==2) selected @endif value="2">Free + Commercial</option>
                        </select>
                    </div>
                    <!--<label class="control-label col-lg-3">Open System Name</label>
                    <div class="col-lg-4">
                       <div class="form-group has-feedback has-feedback-left">
                       <input name="system_name" type="text" class="form-control input-xlg">
                       <div class="form-control-feedback">
                           <i class="icon-server"></i>
                       </div>
                       </div>
                    </div>-->
                </div>

            <!--<div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User can back to trial</label>
                    <div class="col-lg-3">
                        <select class="select-fixed-single" name="back_trial">
                           <option value="1">Yes</option>
                           <option value="0">No</option>
                        </select>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
    <!--
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group02">Additional Entries</a>
            </h6>
        </div>
        <div id="accordion-control-right-group02" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Register state</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="register_state">
                       <option @if($network->register_state ==1)  selected @endif value="1">Yes</option>
                       <option @if($network->register_state ==0)  selected @endif value="0">No</option>
                    </select>
                    </div>
                </div>
                <!-<div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Auto Disable Users If Not Disabled</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="autoDisableUsersIfNotDisabled">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User charge account state</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="charge_account_state">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Charge by visa state</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="charge_visa_state">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User tracking account</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="user_tracking_account">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">SMS service state</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="sms_service_state">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>--><!--
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User Change password</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="change_pass_state">
                       <option @if($network->change_pass_state ==1) selected @endif value="1">Yes</option>
                       <option @if($network->change_pass_state ==0) selected @endif value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User update profile state</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="update_profile_state">
                       <option @if($network->update_profile_state ==1) selected @endif value="1">Yes</option>
                       <option @if($network->update_profile_state ==0) selected @endif value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User update profile</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="update_user_region_status">
                       <option @if($network->update_user_region_status ==1) selected @endif value="1">Yes</option>
                       <option @if($network->update_user_region_status ==0) selected @endif value="0">No</option>
                    </select>
                    </div>
                </div>-->
                <!--<div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User update personal number</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="personal_number">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User Pay Mobile Cards</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="mobile_card_pay">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">User view Mobile Card History</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="mobile_card_history">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Where user ended </label>
                    <div class="col-lg-3">
                    <select class="select-fixed-single" name="stop_user_type">
                       <option value="0">Disable user</option>
                       <option value="1">Switch to END profile</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">End Profile</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-single" name="stop_user_profile_id">
                       <option value="0">One Mega</option>
                       <option value="1">512 K</option>
                       <option value="2">256 K</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Company system</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="company_system">
                       <option value="0">Off</option>
                       <option value="1">On</option>
                    </select>
                    </div>
                </div>--><!--
            </div>
        </div>
    </div>-->
<!--
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group03">Packages</a>
            </h6>
        </div>
        <div id="accordion-control-right-group03" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Show monthly packages</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="show_package_monthly">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Show Validity packages</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="show_package_validity">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Show SMS packages</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="show_package_sms">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Show Bandwidth packages</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="show_package_bandwidth">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Show period packages</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="show_package_period">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-4">Show offer packages</label>
                    <div class="col-lg-3">
                    <select class="select-fixed-singles" name="show_package_offer">
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                    </select>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
<!-- /basic accordion -->
</form>
</div>
	<script>
	    $(".switch").bootstrapSwitch();
        $('.select-fixed-single').select2({
          minimumResultsForSearch: Infinity,
          width: 250
           });
        // Checkboxes
           $(".styled").uniform({
               radioClass: 'choice'
           });
        $('#Radiuscheck').on('click', function () {
              if($(this).is(':checked')){
                   $('#RadiusDiv').show();
              }else{
                  $('#RadiusDiv').hide();
              }
           });
        $('#RadiusDiv').hide();


           $('#Radiuschecks').on('click', function () {
              if($(this).is(':checked')){
                   $('#RadiusDiv2').show();
              }else{
                  $('#RadiusDiv2').hide();
              }
           });
        $('#RadiusDiv2').hide();
        $('.select-fixed-singles').select2({
              minimumResultsForSearch: Infinity,
              width: 75
          });
	</script>

