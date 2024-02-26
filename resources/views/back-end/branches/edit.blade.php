<h6 class="text-semibold"></h6>

<div class="row">
<form action="{{ url('edit_branch/'.$branch->id) }}" method="POST" id="edit" class="form-horizontal">
{{ csrf_field() }}

<!-- Accordion with right control button -->
<div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="edit-accordion-control-right">
    <div class="panel panel-white">

        <div class="panel-heading">
            <h6 class="panel-title">
                <a data-toggle="collapse" data-parent="#edit-accordion-control-right" href="#edit-accordion-control-right-group1">General</a>
            </h6>
        </div>
        <div id="edit-accordion-control-right-group1" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Branch Name</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="name" type="text" class="form-control input-xlg" value="{!! old('name',$branch->name) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-server"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Network Name</label>
                    <div class="col-lg-9">
                        <select class="select-fixed-single200" name="networkname">
                        @foreach($networks as $network)
                        <option value="{{ $network->id }}">{{ $network->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
<!--
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Branch state</label>
                    <div class="col-lg-8">
                        <select class="select-fixed-single200" name="state" value="{!! old('state',$branch->state) !!}">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
-->             
                @if(isset($branch->url) and $branch->url!="")
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">URL</label>
                        <div class="col-lg-8">
                            <div class="form-group has-feedback has-feedback-left">
                            <a class="form-control input-xlg" href="http://{!! old('url',$branch->url) !!}" target="_blank">{!! old('url',$branch->url) !!}</a>
                                <!-- <input name="url" type="text" class="form-control input-xlg" placeholder="Mikrotik static Link" value="{!! old('url',$branch->url) !!}"> -->
                                <div class="form-control-feedback">
                                    <i class="icon-link2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Address</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="address" type="text" class="form-control input-xlg" value="{!! old('address',$branch->address) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-home"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Phone</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="phone" type="text" class="form-control input-xlg" placeholder="201000000000" value="{!! old('phone',$branch->phone) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-mobile2"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Notes</label>
                    <div class="col-lg-8">
                        <textarea name="notes" type="text" rows="3" class="form-control input-xlg">{!! old('notes',$branch->notes) !!}</textarea>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
    <!-- Basic settings -->
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group2">Basic settings</a>
            </h6>
        </div>
        <div id="edit-accordion-control-right-group2" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" >Hardware Type</label>
                    <div class="col-lg-8">
                        <select class="select-fixed-single" name="r_type">
                            <option @if($branch->radius_type=="mikrotik") selected @endif value="mikrotik">Router</option>
                            <option @if($branch->radius_type=="aruba") selected @endif value="aruba">Aruba</option>
                            <option @if($branch->radius_type=="ddwrt") selected @endif value="ddwrt">DD-WRT</option>
                            <!-- <option @if($branch->radius_type=="cisco") selected @endif value="cisco">CISCO</option> -->
                        </select>
                    </div>
                </div>

                @if($branch->radius_type=="mikrotik")
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Router Username</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="username" type="text" class="form-control input-xlg" value="{!! old('username',$branch->username) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-user"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Router Password</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="password" type="text" class="form-control input-xlg" value="{!! old('password',$branch->password) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-key"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Radius Secret</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="Radiussecret" type="text" class="form-control input-xlg" value="microsystem" readonly>
                            <div class="form-control-feedback">
                                <i class="icon-key"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3"> Serial</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="serial" type="text" class="form-control input-xlg" placeholder="Router serial" value="{!! old('serial', $branch->serial) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-barcode2"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <input name="username" type="hidden" value="{!! old('username',$branch->username) !!}">
                    <input name="password" type="hidden" value="{!! old('password',$branch->password) !!}">
                    <input name="Radiussecret" type="hidden" value="microsystem">
                    <!-- <input name="ip" type="hidden" value="{!! old('ip',$branch->ip) !!}"> -->
                    <input name="serial" type="hidden" value="{!! old('serial', $branch->serial) !!}">
                @endif

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">IP Address</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="ip" type="text" class="form-control input-xlg" placeholder="000.000.000.000" value="{!! old('ip',$branch->ip) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-link2"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Device Mac</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="device_mac" type="text" class="form-control input-xlg" placeholder="00:00:00:00:00" value="{!! old('ip',$branch->device_mac) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-link2"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!--
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">API Port</label>
                    <div class="col-lg-3">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="APIport" type="text" class="form-control input-xlg" value="{!! old('APIport',$branch->APIport) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-lan"></i>
                            </div>
                        </div>
                    </div>

                    <label class="control-label col-lg-2">Radius Port</label>
                    <div class="col-lg-3">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="Radiusport" type="text" class="form-control input-xlg" value="{!! old('Radiusport',$branch->Radiusport) !!}">
                            <div class="form-control-feedback">
                                <i class="icon-lan"></i>
                            </div>
                        </div>
                    </div>
                </div>
                -->
                <input name="APIport" type="hidden" value="{!! old('APIport',$branch->APIport) !!}">
                <input name="Radiusport" type="hidden" value="{!! old('Radiusport',$branch->Radiusport) !!}">

                <div class="form-group col-lg-12">
                  <label class="control-label col-lg-3">Monthly Quota GB</label>
                  <div class="col-lg-3">
                    <div class="form-group has-feedback has-feedback-left">
                      <input name="monthly_quota" type="text" class="form-control input-xlg" placeholder="100" value="{!! old('monthly_quota',$branch->monthly_quota) !!}">
                      <div class="form-control-feedback">
                        <i class="icon-cloud"></i>
                      </div>
                    </div>
                  </div>
                  <label class="control-label col-lg-2">Renew day</label>
                  <div class="col-lg-3">
                    <div class="form-group has-feedback has-feedback-left">
                      <input name="start_quota" type="number" class="form-control input-xlg" max="31" min="1" value="{!! old('start_quota',$branch->start_quota) !!}">
                      <div class="form-control-feedback">
                        <i class="icon-sun3"></i>
                      </div>
                    </div>
                  </div>
                </div>
                
            </div>
        </div>
    </div>
    @if($branch->radius_type=="mikrotik")

        <!-- Internet Mode -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group9">Internet Mode</a>
                </h6>
            </div>
            
            <div id="edit-accordion-control-right-group9" class="panel-collapse collapse">    
                <div class="panel-body"> 

                    <div class="alert alert-info alert-styled-left alert-bordered">
                        <center><span class="text-semibold">Wi-Fi Mode</span> used to set high bandwidth priority for</center>
                    </div>
                    
                    <!-- Home Mode -->
                    <div class="alert alert-info alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <span class="text-semibold">Home Mode &nbsp</span>
                            
                            <!-- <img style="max-width: 50px;" src="assets/images/primevideo.jpg"> -->
                            <img style="max-width: 55px;" title="Netflix" src="assets/images/netflix.png">
                            <img style="max-width: 55px;" title="Youtube" src="assets/images/youtube.png">
                            <img style="max-width: 50px;" title="Disney Plus" src="assets/images/disneyplus.png">
                            <img style="max-width: 55px;" title="Facebook Videos" src="assets/images/facebook_icon.png">
                            <img style="max-width: 50px;" title="TikTok" src="assets/images/tikok.png">
                            <img style="max-width: 45px;" title="Apple TV" src="assets/images/apple_tv.png">
                            <img style="max-width: 50px;" title="Instagram" src="assets/images/instagram.png">
                        
                    </div>
                    <!-- Office Mode -->
                    <div class="alert alert-info alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <span class="text-semibold">Office Mode &nbsp</span>
                            
                            <img style="max-width: 80px;" title="Zoom" src="assets/images/zoom.png">
                            <!-- <img style="max-width: 50px;" title="Google Do" src="assets/images/google_do.png"> -->
                            <img style="max-width: 80px;" title="Slack" src="assets/images/slack.png">
                            <img style="max-width: 50px;" title="Microsoft Team" src="assets/images/microsoftteam.png">
                            <img style="max-width: 45px;" title="Github" src="assets/images/github.png">
                            <img style="max-width: 50px;" title="Dropbox" src="assets/images/dropbox.png">
                            <img style="max-width: 55px;" title="Cisco Webex" src="assets/images/ciscowebex.png">
                        
                    </div>
                    <!-- Gaming Mode -->
                    <div class="alert alert-info alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <span class="text-semibold">Gaming Mode &nbsp</span>
                            
                            <img style="max-width: 50px;" title="Pubg" src="assets/images/pubg-icon.png">
                            &nbsp
                            <img style="max-width: 75px;" title="Disney Plus" src="assets/images/disneyplus.png">
                        
                    </div>
                
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4">Internet Mode</label>
                        <div class="col-lg-6">
                            <select class="select-fixed-single" name="internet-mode">
                                <option @if( isset($branch->internet_mode) and $branch->internet_mode == "default") selected @endif value="default">Default Mode</option>
                                <option @if( isset($branch->internet_mode) and $branch->internet_mode == "home") selected @endif value="home">Home Mode</option>
                                <option @if( isset($branch->internet_mode) and $branch->internet_mode == "office") selected @endif value="office">Office Mode</option>
                                <option @if( isset($branch->internet_mode) and $branch->internet_mode == "gaming") selected @endif value="gaming">Gaming Mode</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    
        <!-- Internet Connection -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group3">Internet Connection</a>
                </h6>
            </div>
            <div id="edit-accordion-control-right-group3" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3">Connection type</label>
                        <div class="col-lg-8">
                            <select class="select-fixed-single" name="connection-type" id="edit-connection-type">
                                <option @if($branch->connection_type == 1) selected @endif value="1">ADSL</option>
                                <option @if($branch->connection_type == 2) selected @endif value="2">PPP</option>
                                <option @if($branch->connection_type == 3) selected @endif value="3">Vodafone usb modem</option>
                                <option @if($branch->connection_type == 4) selected @endif value="4">Etisalat usb modem</option>
                                <option @if($branch->connection_type == 5) selected @endif value="5">Orange usb modem</option>
                                <option @if($branch->connection_type == 6) selected @endif value="6">Load balancing</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-adsl-username" @if($branch->connection_type == 2) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">ADSL Username</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="adsl-username" type="text" class="form-control input-xlg" value="{{ $branch->adsl_user }}">
                                <div class="form-control-feedback">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-adsl-password" @if($branch->connection_type == 2) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">ADSL Password</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="adsl-password" type="text" class="form-control input-xlg" value="{{ $branch->adsl_pass }}">
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 edit-table-bordered" @if($branch->connection_type == 6) @else style="display: none;" @endif>
                        <div class="table-responsive">
                            <div class="panel-body">
                                <button type="button" name="add" id="edit-add_load" class="btn btn-success"><i class="icon-plus2"></i></button>
                            </div>    
                            <table class="table edit-table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic_load">
                                <thead> 
                                    <tr>
                                        <th>IP/Gateway</th>
                                        <th>Speed</th>
                                        <!-- <th>Type</th> -->
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($load as $value)
                                    <tr>
                                        <td>
                                            <label class="control-label">IP</label>
                                            <input name="load-ip[]" type="text" class="form-control input-xlg edit-load-ip" value="{{ $value->ip }}" style="width: 130px;" required>
                                            <label class="control-label">Gateway</label>
                                            <input name="load-gateway[]" type="text" class="form-control input-xlg edit-load-gateway" value="{{ $value->gateway }}" style="width: 130px;" required>
                                        </td>
                                        <td>
                                            <select class="edit-select" name="load-speed[]" required>
                                                <option @if($value->speed == 1) selected @endif value="1">1 M</option>
                                                <option @if($value->speed == 2) selected @endif value="2">2 M</option>
                                                <option @if($value->speed == 4) selected @endif value="4">4 M</option>
                                                <option @if($value->speed == 8) selected @endif value="8">8 M</option>
                                                <option @if($value->speed == 16) selected @endif value="16">16 M</option>
                                                <option @if($value->speed == 32) selected @endif value="32">32 M</option>
                                                <option @if($value->speed == 64) selected @endif value="64">64 M</option>
                                            </select>
                                        </td>
                                        <input type='hidden' name='load-type[]' value='0'>
                                        <!-- <td>                                        
                                            <div class="form-group">
                                                <select class="edit-select edit-land-type" name="load-type[]" required>
                                                    <option @if($value->type == 1) selected @endif value="0">Direct</option>
                                                    <option @if($value->type == 1) selected @endif value="1">Bridge</option>
                                                    <option @if($value->type == 2) selected @endif value="2">Vodafone modem</option>
                                                    <option @if($value->type == 3) selected @endif value="3">Etisalat modem</option>
                                                    <option @if($value->type == 4) selected @endif value="4">Orange modem</option>
                                                </select>
                                            </div> 
                                            <div class="form-group load-username" style="display: none;">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="load-username" type="text" class="form-control input-xlg" style="width: 100px;" value="{{ $value->user }}">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group load-password" style="display: none;">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="load-password" type="text" class="form-control input-xlg" style="width: 100px;" value="{{ $value->pass }}">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </td> -->

                                        <td>
                                            <button type="button" name="remove" class="btn btn-danger edit-btn_remove" onclick="_delete({{$value->id}}, {{$value->branch_id}})" ><i class="icon-minus2"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <!-- Backup connection -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group4">Backup connection</a>
                </h6>
            </div>
            <div id="edit-accordion-control-right-group4" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4">Backup Connection state</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input id="edit-backup-connection-state" name="backup-connection-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->backup_connection_state == 1)  checked @endif >
                            </div>
                        </div>
                    </div>     
                    <div class="form-group col-lg-12 edit-backup-connection-type">
                        <label class="control-label col-lg-4">Backup Connection type</label>
                        <div class="col-lg-6">
                            <select class="select-fixed-single" name="backup-connection-type" id="edit-backup-connection-type">
                                <option @if($branch->backup_connection_type == 1) selected @endif value="1">ADSL</option>
                                <!--<option value="2">PPP</option>-->
                                <option @if($branch->backup_connection_type == 2) selected @endif value="2">Vodafone usb modem</option>
                                <option @if($branch->backup_connection_type == 3) selected @endif value="3">Etisalat usb modem</option>
                                <option @if($branch->backup_connection_type == 4) selected @endif value="4">Orange usb modem</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-backup-adsl-username" style="display: none;">
                        <label class="control-label col-lg-4">ADSL Username</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="backup-adsl-username" type="text" class="form-control input-xlg">
                                <div class="form-control-feedback">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-backup-adsl-password" style="display: none;">
                        <label class="control-label col-lg-4">ADSL Password</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="backup-adsl-password" type="text" class="form-control input-xlg">
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group5">Wireless</a>
                </h6>
            </div>
            <div id="edit-accordion-control-right-group5" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4">Wireless state</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input id="edit-wireless-state" name="wireless-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->wireless_state == 1)  checked @endif>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-wireless-username" @if($branch->wireless_state == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Wireless name</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="wireless-username" type="text" class="form-control input-xlg" value="{{ $branch->wireless_name }}">
                                <div class="form-control-feedback">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-wireless-password" @if($branch->wireless_state == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Wireless password</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="wireless-password" type="text" class="form-control input-xlg" value="{{ $branch->wireless_pass }}">
                                <span class="help-block">If you need wifi without password, leave password empty.</span>
                                <span class="help-block">If you need to set wifi password make sure characters doesn't less than 8 characters.</span>
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group6">Private wireless ( Isolated Wi-Fi without any control )</a>
                </h6>
            </div>
            <div id="edit-accordion-control-right-group6" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4">Wireless state</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input id="edit-private-wireless-state" name="private-wireless-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->private_wireless_state == 1)  checked @endif>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-private-wireless-username" @if($branch->private_wireless_state == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Wireless name</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="private-wireless-username" type="text" class="form-control input-xlg" value="{{ $branch->private_wireless_name }}">
                                <div class="form-control-feedback">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-private-wireless-password" @if($branch->private_wireless_state == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Wireless password</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="private-wireless-password" type="text" class="form-control input-xlg" value="{{ $branch->private_wireless_pass }}">
                                <span class="help-block">If you need wifi without password, leave password empty.</span>
                                <span class="help-block">If you need to set wifi password make sure characters doesn't less than 8 characters.</span>
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 edit-private-wireless-ip" @if($branch->private_wireless_state == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Wireless IP</label>
                        <div class="col-lg-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="private-wireless-ip" type="text" class="form-control input-xlg" value="{{ $branch->private_wireless_ip }}" readonly>
                                <div class="form-control-feedback">
                                    <i class="icon-link2"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group7">Security & Block</a>
                </h6>
            </div>
            <div id="edit-accordion-control-right-group7" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="Limit All Downloads speed to 128KB except Torrent, also you can block torrent by switching on (Block torrent download) option. " data-placement="left">Block Downloading</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="block_downloading" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->block_downloading == 1) checked @endif>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="Block all torrent download" data-placement="left">Block torrent download</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="block_torrent_download" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->block_torrent_download  == 1)  checked @endif>
                            </div>
                        </div>
                    </div>    

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="Disable Microsoft Windows and IPhone auto update" data-placement="left">Block Windows and IPhone Updates</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="block_windows_update" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->block_windows_update == 1)  checked @endif>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="Anti Virus on network level to protect your devices from virus spreading and SynFlood, ICMP Flood, Port Scan, Email Spam and much more." data-placement="left">Anti Virus</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="antivirus" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->antivirus == 1) checked @endif>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="This feature will disable network shareing between devices" data-placement="left">Hacking and NetCut Protection</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="security-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->hacking_protection == 1)  checked @endif>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="Will affect DNS for all users" data-placement="left">Adult Protection</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="adult-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->adult_state  == 1)  checked @endif>
                            </div>
                        </div>
                    </div>    

                </div>
            </div>
        </div>

        <!-- Auto Login -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group21">Auto Login</a>
                </h6>
            </div>
            <div id="accordion-control-group21" class="panel-collapse collapse">
                <div class="panel-body">
                    
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-3" data-popup="tooltip"
                                title="Auto save Mac-address after first login to remove landing page for future login's "
                                data-placement="right">Auto Login </label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left auto_login2">
                                <input id="auto_login" type="checkbox" name="auto_login" value="1" class="switch"
                                        @if($branch->auto_login == 1) checked @endif >
                            </div>
                        </div>
                        <span class="help-block">(before view Wi-Fi marketing campaigns)</span>
                    </div>
                    <div class="form-group col-lg-12 auto-login-expiry2" @if($branch->auto_login == 1) @else style="display:none;" @endif>
                        <label class="control-label col-lg-3">Auto login Expiry(Days)</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="auto-login-expiry" class="frequency form-control"
                                        type="number"
                                        min="1" value="{{ $branch->auto_login_expiry }}">
                                <div class="form-control-feedback">
                                    <i class="icon-sun3"></i>
                                </div>
                                <span class="help-block">0 : Unlimited</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 auto-login-ByMacFromWeb">
                        <label class="control-label col-lg-3" data-popup="tooltip"
                                title="Auto login if user is registerd before to remove landing page for future login's and view Wi-Fi marketing campaign"
                                data-placement="right">Web Auto Login</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left ">
                                <input id="autoLoginByMacFromWeb" type="checkbox" name="autoLoginByMacFromWeb" value="1" class="switch"
                                        @if(App\Settings::where('type', 'autoLoginByMacFromWeb')->where('value', $branch->id)->value('state') == 1) checked @endif >
                            </div>
                        </div>
                        <span class="help-block">(They will see Wi-Fi marketing campaigns)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bypass Printers, DVR, Smart devices -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-bypass">Bypass Printers, DVR, Smart devices</a>
                </h6>
            </div>
            <div id="accordion-control-bypass" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group col-lg-12 edit-table-bordered">
                        <div class="table-responsive">
                            <div class="panel-body">
                                <button type="button" name="add" id="edit-bypass" class="btn btn-success"><i class="icon-plus2"></i></button>
                            </div>    
                            <table class="table edit-table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic_bypass">
                                <thead> 
                                    <tr>
                                        <th>Device IP</th>
                                        <th>Mac-Address</th>
                                        <th><font color="gray" title="Only to be accesable from outside enter your port ex.80 or 0-65535">< Port ></font></th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bypass as $value)
                                    <tbody>
                                        <input type='hidden' name='bypass-id[]' value="{{ $value->id }}">
                                        <tr id="row">
                                            <td>
                                                <input name="bypass-ip[]" type="text" value="{{ $value->ip }}" class="form-control input-xlg edit-bypass-ip" title="If you know device IP enter here to obtain MAC-Address automatically in one minute." placeholder="10.5.50.100" value="" style="width: 120px;" required>
                                            </td>
                                            <td>
                                                <input name="bypass-mac[]" type="text" value="{{ $value->mac }}" class="form-control input-xlg edit-bypass-mac" title="If you know device Mac enter here to obtain IP automatically in one minute." placeholder="00:00:00:00:00" value="" style="width: 160px;" required>
                                            </td>
                                            <td>
                                                <input name="bypass-port[]" type="text" value="{{ $value->port }}" class="form-control input-xlg edit-bypass-port" title="Only to be accesable from outside enter your port ex.80 or 0-65535" placeholder="0-65535" value="" style="width: 100px;" required>
                                            </td>
                                            <td>
                                                <button type="button" name="remove" class="btn btn-danger edit-btn_remove" onclick="bypass_delete({{$value->id}}, {{$value->branch_id}})" ><i class="icon-minus2"></i></button>
                                            </td>
                                        </tr>
                                    <tbody>
                                    @endforeach
                                </tbody>
                            </table> 

                            <div class="alert alert-info alert-styled-left alert-bordered">
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>×</span><span class="sr-only">Close</span></button>
                                <span class="text-semibold">(ByPass)</span> Please enter your device IP or Mac-Address to apply "static IP", and give full internet access without login page, then system auto detect all information and you can check this window after 1 minute.
                                <br>
                                <span class="text-semibold">(Nat)</span> Also you can optionally add specific port ex."80" or range ports ex."0-65535" to be able to open this device from outside network, but please don't forget to enable "DMZ" function in your router.

                            </div>

                        </div>
                    </div>  
                    
                </div>
            </div>
        </div>


        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-group8">Advanced script</a>
                </h6>
            </div>
            <div id="edit-accordion-control-right-group8" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="Will affect DNS for all users" data-placement="left">Advanced script state</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="advanced-script-state" id="edit-advanced-script-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->advanced_script_state  == 1)  checked @endif>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group col-lg-12 edit-advanced-script" @if($branch->advanced_script_state  == 1) @else style="display: none;" @endif>
                        <textarea id="edit-editor"> {{ $branch->advanced_script }}</textarea>
                        <input type="hidden" name="edit-advanced-script">
                    </div> 



                </div>
            </div>
        </div>
        <!-- Cyber Defense Operations Center -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group91">Cyber Defense Operations Center</a>
                </h6>
            </div>
            <div id="accordion-control-right-group91" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="" data-placement="left">Cyber tracking state</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="log-history-state" id="edit-log-visited" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->users_log_history_state  == 1) checked @endif> 
                            </div>
                        </div>
                    </div> 

                    <div class="form-group col-lg-12 edit-log-visited" @if($branch->users_log_history_state  == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Cyber tracking type</label>
                        <div class="col-lg-6">
                            <select class="select-fixed-single" name="log-history-type" id="backup-connection-type">
                                <option @if($branch->users_log_history_type  == 1) selected @endif disabled value="1">Websites</option>
                                <option @if($branch->users_log_history_type  == 2) selected @endif value="2">Detailed IP of outgoing requests</option>
                                <option @if($branch->users_log_history_type  == 3) selected @endif disabled value="3">Detailed IP of inbound requests</option>
                                <option @if($branch->users_log_history_type  == 4) selected @endif disabled value="4">Detailed IP of inbound and outgoing requests</option>
                                <option @if($branch->users_log_history_type  == 5) selected @endif disabled value="5">All of the above</option>
                            </select>
                        </div>   
                    </div> 
                </div>
            </div>
        </div>

        <!-- PMS Landing page settings -->
        @if(App\Settings::where('type', 'pms_integration')->value('state') == "1" )
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group92">PMS Landing Page Settings</a>
                    </h6>
                </div>
                <div id="accordion-control-right-group92" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-4" data-popup="tooltip" title="" data-placement="left">Premium Login</label>
                            <div class="col-lg-6">
                                <div class="checkbox checkbox-switch">
                                    <input name="pms_premium_login_state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->pms_premium_login_state  == 1) checked @endif> 
                                </div>
                            </div>
                        </div> 

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-4" data-popup="tooltip" title="" data-placement="left">Complementary Login</label>
                            <div class="col-lg-6">
                                <div class="checkbox checkbox-switch">
                                    <input name="pms_complementary_login_state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->pms_complementary_login_state  == 1) checked @endif> 
                                </div>
                            </div>
                        </div> 


                    </div>
                </div>
            </div>
        @endif

        <!-- Location Based Group Switching -->
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group93">Location Based Group Switching </a>
                </h6>
            </div>
            <div id="accordion-control-right-group93" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group col-lg-12">
                        <label class="control-label col-lg-4" data-popup="tooltip" title="" data-placement="left">Group Switching State</label>
                        <div class="col-lg-6">
                            <div class="checkbox checkbox-switch">
                                <input name="temporary_group_switching_state" id="edit-group-switching-temporary" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" @if($branch->temporary_group_switching_state  == 1) checked @endif> 
                            </div>
                        </div>
                    </div> 

                    <div class="form-group col-lg-12 edit-group-switching-temporary" @if($branch->temporary_group_switching_state == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Temporary Group</label>
                        <div class="col-lg-6">
                            <select class="select-fixed-single" name="temporary_group_switching_group_id">
                                @foreach(App\Groups::where('as_system', '0')->where('is_active', '1')->get() as $group)
                                    <option @if($branch->temporary_group_switching_group_id == $group->id) selected @endif value="{{$group->id}}"> {{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>   
                    </div>
                    
                    <div class="form-group col-lg-12 edit-group-switching-temporary"  @if($branch->temporary_group_switching_state == 1) @else style="display: none;" @endif>
                        <label class="control-label col-lg-4">Except for</label>
                        <div class="col-lg-8">
                            <select multiple="multiple" class="form-control" name="temporary_group_switching_exception_groups[]">
                                <option @if($branch->temporary_group_switching_exception_groups == "0" or $branch->temporary_group_switching_exception_groups== "") selected @endif value=0> NO EXCEPTION FOR ANY GROUP</option>      
                                @foreach(App\Groups::where('as_system', '0')->where('is_active', '1')->get() as $group)
                                    <option @if(strpos($branch->temporary_group_switching_exception_groups, "$group->id") !== false) selected @endif value="{{$group->id}}"> {{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    @endif
</div>
<!-- /accordion with right control button -->

</form>
</div>
	<script>

        $('#edit-add_load').click(function () {
            var code = '<tbody> <tr id="row">\n';
                                                                
                code += '<td>\n';
                    code += '<label class="control-label">IP</label> \n';
                    code += '<input name="load-ip[]" type="text" class="form-control input-xlg edit-load-ip" value="192.168.2.10" style="width: 130px;" required> \n';
                    code += '<label class="control-label">Gateway</label> \n';
                    code += '<input name="load-gateway[]" type="text" class="form-control input-xlg edit-load-gateway" value="192.168.2.1" style="width: 130px;" required> \n';
                code += '</td>\n';

                code += '<td>\n';
                    code += '<select class="edit-select" name="load-speed[]" required>\n';
                    code += '<option value="1">1 M</option>\n';
                    code += '<option value="2">2 M</option>\n';
                    code += '<option value="4">4 M</option>\n';
                    code += '<option value="8">8 M</option>\n';
                    code += '<option value="16">16 M</option>\n';
                    code += '<option value="32">32 M</option>\n';
                    code += '<option value="64">64 M</option>\n';
                    code += '</select>\n';
                code += '</td>\n';
                code += '<input type="hidden" name="load-type[]" value="0">';
                //code += '<td>\n';
                    // code += '<select class="edit-select edit-land-type" name="load-type[]" required>\n';
                    // code += '<option value="0">Direct</option>\n';
                    // code += '<option value="1">Bridge</option>\n';
                    // code += '<option value="2">Vodafone modem</option>\n';
                    // code += '<option value="3">Etisalat modem</option>\n';
                    // code += '<option value="4">Orange modem</option>\n';
                    // code += '</select>\n';
                //     code += '<div class="form-group">\n';
                //     code += '<div class="form-group edit-load-username" style="display: none;">\n';
                //     code += '<div class="form-group has-feedback has-feedback-left">\n';
                //     code += '<input name="load-username[]" type="text" class="form-control input-xlg" style="width: 100px;">\n';
                //     code += '<div class="form-control-feedback">\n';
                //     code += '<i class="icon-user"></i>\n';
                //     code += '</div>\n';
                //     code += '</div>\n';
                //     code += '</div>\n';
                //     code += '</div>\n';

                //     code += '<div class="form-group edit-load-password" style="display: none;">\n';
                //     code += '<div class="form-group has-feedback has-feedback-left">\n';
                //     code += '<input name="load-password[]" type="text" class="form-control input-xlg" style="width: 100px;">\n';
                //     code += '<div class="form-control-feedback">\n';
                //     code += '<i class="icon-key"></i>\n';
                //     code += '</div>\n';
                //     code += '</div>\n';
                //     code += '</div>\n';
                // code += '</td>\n';
                
                code += '<td>\n';
                    code += '<button type="button" name="remove" class="btn btn-danger edit-btn_remove" ><i class="icon-minus2"></i></button>\n';
                code += '</td>\n';
                
                code += '</tr> </tbody>';
            $('#edit-dynamic_load').append(code);
            $('.edit-select-fixed-single85').select2({
                minimumResultsForSearch: Infinity,
                width: 85
            });
        });

        $('#edit-bypass').click(function () {
            var code = '<tbody> <tr id="row">\n';
                                                                
                code += '<td>\n';
                    code += '<input name="bypass-ip[]" type="text" class="form-control input-xlg edit-bypass-ip" title="If you know device IP enter here to obtain MAC-Address automatically in one minute." placeholder="10.5.50.100" value="" style="width: 120px;" required> \n';
                code += '</td>\n';

                code += '<td>\n';
                    code += '<input name="bypass-mac[]" type="text" class="form-control input-xlg edit-bypass-mac" title="If you know device Mac enter here to obtain IP automatically in one minute." placeholder="00:00:00:00:00" value="" style="width: 160px;" required> \n';
                code += '</td>\n';

                code += '<td>\n';
                    code += '<input name="bypass-port[]" type="text" class="form-control input-xlg edit-bypass-port" title="Only to be accesable from outside enter your port ex.80 or 0-65535" placeholder="0-65535" value="" style="width: 100px;" required> \n';
                code += '</td>\n';

                code += '<td>\n';
                    code += '<button type="button" name="remove" class="btn btn-danger edit-btn_remove" ><i class="icon-minus2"></i></button>\n';
                code += '</td>\n';
                
                code += '</tr> </tbody>';
            $('#edit-dynamic_bypass').append(code);
            $('.edit-select-fixed-single85').select2({
                minimumResultsForSearch: Infinity,
                width: 85
            });
        });

        $(document).on('change', '.edit-land-type', function () {
            var loadtype = $(this).val();

            if(loadtype == 1){
                $(this).parent().find('.edit-load-username').show();
                $(this).parent().find('.edit-load-password').show();

            }else{
                $(this).parent().find('.edit-load-username').hide();
                $(this).parent().find('.edit-load-password').hide();
                
            }
        });

        $(document).on('change', '.edit-load-ip', function () {
            var aaaaa = $(this).val();
            var ret = aaaaa.split(".");
            var ip = ret[0] +'.' + ret[1] +'.'+ ret[2] + '.1';
            
            $(this).parent().find('.edit-load-gateway').val(ip);
        });
            
        $(document).on('click', '.edit-btn_remove', function () {
            $(this).parent().parent().remove();
        });


        function _delete($id, $branch_id){

            console.log($id);
            var that = this;

               swal({
                 title: "Are you sure?",
                 text: "You will not be able to recover data again!",
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
                     
                     $.ajax({
                        url:'load-balancing-delete/'+ $id +'/' + $branch_id,
                         success:function(data) {

                             $(this).parent().parent().remove();
                             swal("Deleted!", "Your data has been deleted.", "success");
                         },
                         error:function(){
                             swal("Cancelled", "Your data is safe :)", "error");

                         }
                     });
                } else {
                     swal("Cancelled", "Your Cancelled :)", "success");
                }
            });
        }

        function bypass_delete($id, $branch_id){

            // console.log($id);
            var that = this;

               swal({
                 title: "Are you sure?",
                 text: "You will not be able to recover data again!",
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
                     
                     $.ajax({
                        url:'bypass-delete/'+ $id +'/' + $branch_id,
                         success:function(data) {

                             $(this).parent().parent().remove();
                             swal("Deleted!", "Your data has been deleted.", "success");
                         },
                         error:function(){
                             swal("Cancelled", "Your data is safe :)", "error");

                         }
                     });
                } else {
                     swal("Cancelled", "Your Cancelled :)", "success");
                }
            });
        }

       // Alert combination
        /*$(document).on( 'click', '.edit-btn_remove', function () {
            var that = this;
            var table = $('.edit-table-bordered');

               swal({
                 title: "Are you sure?",
                 text: "You will not be able to branch data again!",
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
                     
                     console.log(data.id);

                     $.ajax({
                        url:'load-balancing-delete/'+ data.id,
                         success:function(data) {

                             table.row( $(that).parents('tr') ).remove().draw();
                             swal("Deleted!", "Your branch data has been deleted.", "success");
                         },
                         error:function(){
                             swal("Cancelled", "Your branch data is safe :)", "error");

                         }
                     });
                } else {
                     swal("Cancelled", "Your Cancelled :)", "success");
                }
            });
        });*/


        $('.edit-select-fixed-single805').select2({
            minimumResultsForSearch: Infinity,
            width: 85
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

        $(".switch").bootstrapSwitch();
        
        $("#edit-connection-type").change(function(){
            var connectiontype = $("#edit-connection-type option:selected").val();
            if(connectiontype == 2){
                $('.edit-adsl-username').show();
                $('.edit-adsl-password').show();
            }else{
                $('.edit-adsl-username').hide();
                $('.edit-adsl-password').hide();
            }
            if(connectiontype == 6){
                $('.edit-table-bordered').show();
            }else{
                $('.edit-table-bordered').hide();
            }
        });

        $(".edit-land-type").change(function(){
            var loadtype = $(".land-type option:selected").val();
            if(loadtype == 1){
                $('.load-username').show();
                $('.load-password').show();
            }else{
                $('.load-username').hide();
                $('.load-password').hide();
            }
        });    

        // $("#edit-backup-connection-type").change(function(){
        //     var connectiontype = $("#edit-backup-connection-type option:selected").val();
        //     if(connectiontype == 1){
        //         $('.edit-backup-adsl-username').show();
        //         $('.edit-backup-adsl-password').show();
        //     }else{
        //         $('.edit-backup-adsl-username').hide();
        //         $('.edit-backup-adsl-password').hide();
        //     }
        // });

        $('#edit-backup-connection-state').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.edit-backup-connection-type').show();
                
            } else {
                $('.edit-backup-connection-type').hide();
            }
        });

        $('#edit-wireless-state').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.edit-wireless-username').show();
                $('.edit-wireless-password').show();
                
            } else {
                $('.edit-wireless-username').hide();
                $('.edit-wireless-password').hide();
            }
        });

        $('#edit-private-wireless-state').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.edit-private-wireless-username').show();
                $('.edit-private-wireless-password').show();
                $('.edit-private-wireless-ip').show();
                
            } else {
                $('.edit-private-wireless-username').hide();
                $('.edit-private-wireless-password').hide();
                $('.edit-private-wireless-ip').hide();            
            }
        });

        $('#edit-advanced-script-state').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.edit-advanced-script').show();
                
            } else {
                $('.edit-advanced-script').hide();
            }
        });

        // Ruby editor
        var edit_editor = ace.edit("edit-editor");
        edit_editor.setTheme("ace/theme/monokai");
        edit_editor.getSession().setMode("ace/mode/ruby");
        edit_editor.setShowPrintMargin(false);
        var input = $('input[name="edit-advanced-script"]');
            edit_editor.getSession().on("change", function () {
            input.val(edit_editor.getSession().getValue());
        });


        $('#edit-log-visited').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.edit-log-visited').show();
                
            } else {
                $('.edit-log-visited').hide();
            }
        });  

        $('#edit-group-switching-temporary').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.edit-group-switching-temporary').show();
                
            } else {
                $('.edit-group-switching-temporary').hide();
            }
        });  

        $('.auto_login2').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.auto-login-expiry2').show();
            // $('.auto-login-ByMacFromWeb').hide();
        } else {
            $('.auto-login-expiry2').hide();
            // $('.auto-login-ByMacFromWeb').show();
        }
    });  
	</script>

