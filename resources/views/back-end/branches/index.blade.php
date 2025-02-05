@extends('..back-end.layouts.master')
@section('title', 'Branches')
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Branches (Hardware Devices)</h4>
        </div>
    </div>
</div>
<!-- /page header -->



    <!-- Primary modal -->
    <div id="add_network" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Add Branches</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold"></h6>

                    <div class="row">
                    <form action="{{ url('addbranch') }}" method="POST" id="addbranch" class="form-horizontal">
                    {{ csrf_field() }}

                    <!-- Accordion with right control button -->
                        <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right">
                            
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group1">General</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group1" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Branch Name</label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="name" type="text" class="form-control input-xlg">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-server"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Network Name</label>
                                            <div class="col-lg-7">
                                                <select class="select-fixed-single200" name="networkname">
                                                @foreach($networks as $network)
                                                <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Access control</label>
                                            <div class="col-lg-7">
                                                <select class="select-fixed-single" name="state">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Address</label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="address" type="text" class="form-control input-xlg">
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
                                                    <input name="phone" type="text" class="form-control input-xlg" placeholder="201000000000">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-mobile2"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Notes</label>
                                            <div class="col-lg-8">
                                                <textarea name="notes" type="text" rows="3" class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Basic settings -->
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group2">Basic settings</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group2" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Hardware Type</label>
                                            <div class="col-lg-8">
                                                <select class="select-fixed-single" name="r_type">
                                                    <option value="mikrotik">Router</option>
                                                    <option value="aruba">Aruba</option>
                                                    <option value="ddwrt">DD-WRT</option>
                                                    <!-- <option value="cisco">CISCO</option> -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Router Username</label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="username" type="text" class="form-control input-xlg">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Router Password</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="password" type="password" class="form-control input-xlg">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Radius Secret</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="Radiussecret" type="text" class="form-control input-xlg" value="microsystem" readonly>
                                                    <div class="form-control-feedback">
                                                        <i class="icon-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">IP Address</label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="ip" type="text" class="form-control input-xlg" placeholder="000.000.000.000">
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
                                                    <input name="device_mac" type="text" class="form-control input-xlg" placeholder="00:00:00:00:00">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-server"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3"> Serial</label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="serial" type="text" class="form-control input-xlg" placeholder="Router serial">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-barcode2"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">API Port</label>
                                            <div class="col-lg-3">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="APIport" type="text" class="form-control input-xlg" value="8728">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-lan"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <label class="control-label col-lg-2">Radius Port</label>
                                            <div class="col-lg-3">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="Radiusport" type="text" class="form-control input-xlg" value="3799">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-lan"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Monthly Quota</label>
                                            <div class="col-lg-3">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="monthly_quota" type="text" class="form-control input-xlg">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-cloud"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <label class="control-label col-lg-2">Renew day</label>
                                            <div class="col-lg-3">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="start_quota" type="number" class="form-control input-xlg" max="31" min="1" value="1">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-sun3"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>

                            <!-- Internet Mode -->
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group9">Internet Mode</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group9" class="panel-collapse collapse">
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
                                                    <option value="default">Default Mode</option>
                                                    <option value="home">Home Mode</option>
                                                    <option value="office">Office Mode</option>
                                                    <option value="gaming">Gaming Mode</option>
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
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group3">Internet Connection</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group3" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3">Connection type</label>
                                            <div class="col-lg-8">
                                                <select class="select-fixed-single" name="connection-type" id="connection-type">
                                                    <option value="1">ADSL</option>
                                                    <option value="2">PPP</option>
                                                    <option value="3">Vodafone usb modem</option>
                                                    <option value="4">Etisalat usb modem</option>
                                                    <option value="5">Orange usb modem</option>
                                                    <option value="6">Load balancing</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 adsl-username" style="display: none;">
                                            <label class="control-label col-lg-4">ADSL Username</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="adsl-username" type="text" class="form-control input-xlg">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 adsl-password" style="display: none;">
                                            <label class="control-label col-lg-4">ADSL Password</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="adsl-password" type="password" class="form-control input-xlg">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 table-bordered" style="display: none;">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" data-toggle="context" data-target=".context-table" id="dynamic_date">
                                                    <thead> 
                                                        <tr>
                                                            <th>IP/Gateway</th>
                                                            <th>Speed</th>
                                                            <!-- <th>Type</th> -->
                                                            <th class="text-center"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <label class="control-label">IP</label>
                                                                <input name="load-ip[]" type="text" class="form-control input-xlg load-ip" value="192.168.1.10" style="width: 130px;" required>
                                                                <label class="control-label">Gateway</label>
                                                                <input name="load-gateway[]" type="text" class="form-control input-xlg load-gateway" value="192.168.1.1" style="width: 130px;" required>
                                                            </td>
                                                            <td>
                                                                <select class="select" name="load-speed[]" required>
                                                                    <option value="1">1 M</option>
                                                                    <option value="2">2 M</option>
                                                                    <option value="4">4 M</option>
                                                                    <option value="8">8 M</option>
                                                                    <option value="16">16 M</option>
                                                                    <option value="32">32 M</option>
                                                                    <option value="64">64 M</option>
                                                                </select>
                                                            </td>
                                                            <input type='hidden' name='load-type[]' value='0'>
                                                            <!-- <td>
                                                                 <div class="form-group">
	                                                                <select class="select land-type" name="load-type[]" required>
	                                                                    <option value="0">Direct</option>
	                                                                    <option value="1">Bridge</option>
	                                                                    <option value="2">Vodafone modem</option>
	                                                                    <option value="3">Etisalat modem</option>
	                                                                    <option value="4">Orange modem</option>
	                                                                </select>
                                                                </div> 
                                                                <div class="form-group load-username" style="display: none;">
                                                                    <div class="form-group has-feedback has-feedback-left">
                                                                        <input name="load-username" type="text" class="form-control input-xlg" style="width: 100px;">
                                                                        <div class="form-control-feedback">
                                                                            <i class="icon-user"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group load-password" style="display: none;">
                                                                    <div class="form-group has-feedback has-feedback-left">
                                                                        <input name="load-password" type="password" class="form-control input-xlg" style="width: 100px;">
                                                                        <div class="form-control-feedback">
                                                                            <i class="icon-key"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td> -->

                                                            <td>
                                                                <button type="button" name="add" id="add_load" class="btn btn-success">
                                                                    <i class="icon-plus2"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
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
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group4">Backup connection</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group4" class="panel-collapse collapse">
                                    <div class="panel-body">
                                         <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Backup Connection state</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input id="backup-connection-state" name="backup-connection-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
                                                </div>
                                            </div>
                                        </div>     
                                        <div class="form-group col-lg-12 backup-connection-type" style="display: none;">
                                            <label class="control-label col-lg-4">Backup Connection type</label>
                                            <div class="col-lg-6">
                                                <select class="select-fixed-single" name="backup-connection-type" id="backup-connection-type">
                                                    <option value="1">ADSL</option>
                                                    <!---<option value="2">PPP</option>-->
                                                    <option value="2">Vodafone usb modem</option>
                                                    <option value="3">Etisalat usb modem</option>
                                                    <option value="4">Orange usb modem</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 backup-adsl-username" style="display: none;">
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

                                        <div class="form-group col-lg-12 backup-adsl-password" style="display: none;">
                                            <label class="control-label col-lg-4">ADSL Password</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="backup-adsl-password" type="password" class="form-control input-xlg">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Wireless -->
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group5">Wireless</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group5" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Wireless state</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input id="wireless-state" name="wireless-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default" checked>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 wireless-username" >
                                            <label class="control-label col-lg-4">Wireless name</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="wireless-username" type="text" class="form-control input-xlg" value="Microsystem">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 wireless-password">
                                            <label class="control-label col-lg-4">Wireless password</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="wireless-password" type="password" class="form-control input-xlg">
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
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group6">Private wireless ( Isolated Wi-Fi without any control )</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group6" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Wireless state</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input id="private-wireless-state" name="private-wireless-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 private-wireless-username" style="display: none;">
                                            <label class="control-label col-lg-4">Wireless name</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="private-wireless-username" type="text" class="form-control input-xlg" value="Microsystem">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 private-wireless-password" style="display: none;">
                                            <label class="control-label col-lg-4">Wireless password</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="private-wireless-password" type="password" class="form-control input-xlg">
                                                    <span class="help-block">If you need wifi without password, leave password empty.</span>
                                                    <span class="help-block">If you need to set wifi password make sure characters doesn't less than 8 characters.</span>
                                                    <div class="form-control-feedback">
                                                        <i class="icon-key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 private-wireless-ip" style="display: none;">
                                            <label class="control-label col-lg-4">Wireless IP</label>
                                            <div class="col-lg-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="private-wireless-ip" type="text" class="form-control input-xlg" value="10.10.10.1">
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
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group7_add">Security & Block</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group7_add" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="Limit All Downloads speed to 128KB except Torrent, also you can block torrent by switching on (Block torrent download) option. " data-placement="left">Block Downloading</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="block_downloading" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
                                                </div>
                                            </div>
                                        </div> 

                                         <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="Block all torrent download" data-placement="left">Block torrent download</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="torr" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
                                                </div>
                                            </div>
                                        </div>     

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="Disable Microsoft Windows and IPhone auto update" data-placement="left">Block Windows and IPhone Updates</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="block_windows_update" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="Anti Virus on network level to protect your devices from virus spreading and SynFlood, ICMP Flood, Port Scan, Email Spam and much more." data-placement="left">Anti Virus</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="antivirus" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
                                                </div>
                                            </div>
                                        </div> 
                                    
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="This feature will disable network shareing between devices" data-placement="left">Hacking and NetCut Protection</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="security-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="Will affect DNS for all users" data-placement="left">Adult Protection</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="adult-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default">
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
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group21_add">Auto Login</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-group21_add" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-3" data-popup="tooltip"
                                                    title="Auto save Mac-address after first login to remove landing page for future login's "
                                                    data-placement="right">Auto Login</label>
                                            <div class="col-lg-3">
                                                <div class="form-group has-feedback has-feedback-left auto_login_add">
                                                    <input id="auto_login" type="checkbox" name="auto_login" class="switch" checked>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 auto-login-expiry-add">
                                            <label class="control-label col-lg-3">Auto login Expiry(Days)</label>
                                            <div class="col-lg-9">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="auto-login-expiry" class="frequency form-control"
                                                            type="number"
                                                            min="1" value="0">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-sun3"></i>
                                                    </div>
                                                    <span class="help-block">0 : Unlimited</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bypass Printers, DVR, Smart devices -->
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-bypass_add">Bypass Printers, DVR, Smart devices</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-bypass_add" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12 add-table-bordered">
                                            <div class="table-responsive">
                                                <div class="panel-body">
                                                    <button type="button" name="add" id="add-bypass" class="btn btn-success"><i class="icon-plus2"></i></button>
                                                </div>    
                                                <table class="table add-table-bordered" data-toggle="context" data-target=".context-table" id="add-dynamic_bypass">
                                                    <thead> 
                                                        <tr>
                                                            <th>Device IP</th>
                                                            <th>Mac-Address</th>
                                                            <th><font color="gray" title="Only to be accesable from outside enter your port ex.80 or 0-65535">< Port ></font></th>
                                                            <th class="text-center"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>  
                                        
                                    </div>
                                </div>
                            </div>


                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group8">Advanced script</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group8" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="Will affect DNS for all users" data-placement="left">Advanced script state</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="advanced-script-state" id="advanced-script-state" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default"> 
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="form-group col-lg-12 advanced-script" style="display: none;">
                                            <div id="ruby_editor" ></div>
                                            <input type="hidden" name="advanced-script">
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group9">Log visited sites</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group9" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4" data-popup="tooltip" title="" data-placement="left">Log visited sites state</label>
                                            <div class="col-lg-6">
                                                <div class="checkbox checkbox-switch">
                                                    <input name="log-history-state" id="log-visited" type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-on-color="primary" data-off-color="default"> 
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="form-group col-lg-12 log-visited" style="display: none;">
                                            <label class="control-label col-lg-4">Log history type</label>
                                            <div class="col-lg-6">
                                                <select class="select-fixed-single" name="backup-connection-type" id="log-history-type">
                                                    <option disabled value="1">Websites</option>
                                                    <option selected value="2">Detailed IP of outgoing requests</option>
                                                    <option disabled value="3">Detailed IP of inbound requests</option>
                                                    <option disabled value="4">Detailed IP of inbound and outgoing requests</option>
                                                    <option disabled value="5">All of the above</option>
                                                </select>
                                            </div>   
                                        </div> 
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /accordion with right control button -->
                    </form>
                    </div>

                     <hr>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['addbranch'].submit(); return false;">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /primary modal -->

    <!-- Primary modal -->
    <div id="modal_ajax" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Edit Branch</h6>
                </div>

                <div class="modal-body">


                <hr>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Success_message" onclick="document.forms['edit'].submit(); return false;">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /primary modal -->
    
    <!-- Primary modal -->
    <div id="modal_download" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Download</h6>
                </div>

                <div class="modal-body">


                    <hr>
                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- /primary modal -->

    <!-- Modal with basic title -->
    <div id="modal_timeline" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span class="text-semibold modal-title">Timeline</span>
                </div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /modal with basic title -->

    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        <div class="panel panel-flat">
            <!--<div class="panel-heading">
                <h5 class="panel-title">Braches Table</h5>
            </div>-->

            <div class="panel-body">
                <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_network"><b><i class="icon-server"></i></b> Add Branches</button>
                
            </div>
            <table class="table" width="100%" id="table-network">
                <thead>
                    <tr>
                        <th>Branch ID</th>
                        <!-- <th>Network Name</th> -->
                        <th>Name</th>
                        <th>Access control</th>
                        <th>Public IP</th>
                        <th>CPU</th>
                        <th>Uptime</th>
                        <th>RAM</th>
                        <th>Connection state</th>
                        <th>Online Users</th>
                        <th>Monthly usage</th>
                        <th>Total usage</th>
                        <th class="text-center">Actions</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /scrollable datatable -->
        @include('..back-end.footer')
    </div>
    @section('js')
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
    
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/editors/ace/ace.js"></script>

    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script>



    @endsection
    <script>
    // Ruby editor
    var ruby_editor = ace.edit("ruby_editor");
    ruby_editor.setTheme("ace/theme/monokai");
    ruby_editor.getSession().setMode("ace/mode/ruby");
    ruby_editor.setShowPrintMargin(false);
    var input = $('input[name="advanced-script"]');
        ruby_editor.getSession().on("change", function () {
        input.val(ruby_editor.getSession().getValue());
    });

    /*ruby_editor.getSession().on('change', function(e) {
        var value = ruby_editor.getvalue();
        //console.log($('#ruby_editor').val());
        //  $('textarea[name=aas]').val();
        $('#ruby_editor').val(value);
    });*/
    // Basic datatable
    $('#add_load').click(function () {
        var code = '<tbody> <tr id="row">\n';
                                                            
            code += '<td>\n';
                code += '<label class="control-label">IP</label> \n';
                code += '<input name="load-ip[]" type="text" class="form-control input-xlg load-ip" value="192.168.2.10" style="width: 130px;" required> \n';
                code += '<label class="control-label">Gateway</label> \n';
                code += '<input name="load-gateway[]" type="text" class="form-control input-xlg load-gateway" value="192.168.2.1" style="width: 130px;" required> \n';
            code += '</td>\n';

            code += '<td>\n';
                code += '<select class="select" name="load-speed[]" required>\n';
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

            // code += '<td>\n';
            //     code += '<select class="select land-type" name="load-type[]" required>\n';
            //     code += '<option value="0">Direct</option>\n';
            //     code += '<option value="1">Bridge</option>\n';
            //     code += '<option value="2">Vodafone modem</option>\n';
            //     code += '<option value="3">Etisalat modem</option>\n';
            //     code += '<option value="4">Orange modem</option>\n';
            //     code += '</select>\n';
            //     code += '<div class="form-group">\n';
            //     code += '<div class="form-group load-username" style="display: none;">\n';
            //     code += '<div class="form-group has-feedback has-feedback-left">\n';
            //     code += '<input name="load-username[]" type="text" class="form-control input-xlg" style="width: 100px;">\n';
            //     code += '<div class="form-control-feedback">\n';
            //     code += '<i class="icon-user"></i>\n';
            //     code += '</div>\n';
            //     code += '</div>\n';
            //     code += '</div>\n';
            //     code += '</div>\n';

            //     code += '<div class="form-group load-password" style="display: none;">\n';
            //     code += '<div class="form-group has-feedback has-feedback-left">\n';
            //     code += '<input name="load-password[]" type="password" class="form-control input-xlg" style="width: 100px;">\n';
            //     code += '<div class="form-control-feedback">\n';
            //     code += '<i class="icon-key"></i>\n';
            //     code += '</div>\n';
            //     code += '</div>\n';
            //     code += '</div>\n';
            // code += '</td>\n';
            
            code += '<td>\n';
                code += '<button type="button" name="remove" class="btn btn-danger btn_remove"><i class="icon-minus2"></i></button>\n';
            code += '</td>\n';
            
            code += '</tr> </tbody>';
        $('#dynamic_date').append(code);
        $('.select-fixed-single100').select2({
            minimumResultsForSearch: Infinity,
            width: 85
        });
    });

    $(document).on('change', '.land-type', function () {
        var loadtype = $(this).val();

        if(loadtype == 1){
            $(this).parent().find('.load-username').show();
            $(this).parent().find('.load-password').show();

        }else{
            $(this).parent().find('.load-username').hide();
            $(this).parent().find('.load-password').hide();
            
        }
    });

    $(document).on('change', '.load-ip', function () {
        var aaaaa = $(this).val();
        var ret = aaaaa.split(".");
        var ip = ret[0] +'.' + ret[1] +'.'+ ret[2] + '.1';
        
        $(this).parent().find('.load-gateway').val(ip);
    });

    
    $(document).on('click', '.btn_remove', function () {
        $(this).parent().parent().remove();
    });

    $(document).ready(function(){
        setInterval(function() {
            $.ajax({
                url:'branchesjson', type: 'get', data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function(){
                    $('#table-network').DataTable().ajax.reload();
                }
            });
            
        }, 10000); //15 seconds
    });

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
   
    
    // Basic initialization
       var table = $('#table-network').DataTable({
            responsive: {
            details: {
               type: 'column',
               target: -1
               }
            },
            ajax:{"url" : "branchesjson",type:"get",data:{_token: $('meta[name="csrf-token"]').attr('content')}},
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
            columnDefs: [
                {
                    className: 'control',
                    orderable: false,
                    targets:   -1
                }],
            deferRender: true,
            columns:[ 
            { "data": "id" },
            // { "data": "network_id" ,
            //   "searchable": false,
            //   "render": function ( type, full, data, meta ) {
            //   if(data.network_id)
            //   return data.networkname;

            // }},
            
            { "data": "name",
              "render": function ( type, full, data, meta ) {
              return '<a href="#" class="edit">'+ data.name +'</a>';
              
            }},
            { "data": "state",
              "searchable": false,
              "render": function ( type, full, data, meta ) {
                if(!data.last_check)
                    return '';
                else if(data.state == 1)
                    return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Enabled</span></button>';
                else
                    return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Disabled</span></button>';

            }},
            { "data": "ip",
              "render": function ( type, full, data, meta ) {
              return '<a href="http://'+ data.ip +'" target="_blank">'+ data.ip +'</a>';
            }},
            {"render": function ( type, full, data, meta ) {
                if(data.cpu == null || data.cpu == '%')
                    return '';
                else
                    return data.cpu;
            }},
            {"render": function ( type, full, data, meta ) {
                if(data.uptime == null)
                    return '';
                else
                    return data.uptime;
            }},
            {"render": function ( type, full, data, meta ) {
                if(data.ram == null)
                    return '';
                else
                    return data.ram+"%";
            }},
            {"render": function ( type, full, data, meta ) {
                if(!data.last_check)
                    return '';
                else if(data.delayTime > 120)
                    return '<h> <span class="label bg-danger">Disconnected</span> Since '+ data.last_check_date +' '+ data.last_check_time +' </h>';
                else
                    return '<h> <span title="Last Check: '+ data.last_check +'" class="label bg-success">Connected</span></h>';
                    
            }},
            {"render": function ( type, full, data, meta ) {
                        return '<div class="col-md-3 col-sm-4"><i class="icon-users2"></i></div> &nbsp &nbsp' + data.count_online + '/' + data.count_users + '';
            }},
            { "data": "monthly_usage" },
            { "data": "total_usage" },
            {"render": function ( type, full, data, meta ) {
                var menu= '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">';
                menu+='<li><a href="#" class="edit" ><i class="icon-pencil3"></i> Edit</a></li>';
                if(data.foundDDWRT == 0){
                    menu+='<li><a href="#" class="reboot" ><i class="icon-file-pdf"></i> Restart</a></li>';
                }
                menu+='<li><a href="#" class="timeline" ><i class="icon-file-stats"></i> Timeline</a></li>';
                if(data.foundDDWRT == 0){
                    menu+='<li><a href="#" class="destinations" ><i class="icon-link2"></i> Export Website visited</a></li>';
                }
                //menu+='<li><a href="#" class="delete"><i class="icon-cross3"></i> Delete</a></li>';
                return menu;
            }},
             { "data":null,"defaultContent":"" }
            ]
        });

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
        // Alert combination
        $('#table-network tbody').on( 'click', '.delete', function () {
            var that = this;
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
                     $.ajax({
                        url:'delete_branch/'+data.id,
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
        });
        $('#table-network tbody').on( 'click', '.reboot', function () {
            var that = this;
               swal({
                 title: "Are you sure?",
                 text: "Router will be rebooted!",
                 type: "warning",
                 showCancelButton: true,
                 confirmButtonColor: "#DD6B55",
                 confirmButtonText: "Yes, reboot it!",
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
                        url:'reboot/' + data.id,
                         success:function(data) {
                            location.reload();  
                             
                             //table.row( $(that).parents('tr') ).remove().draw();
                             swal("Rebooted!", "Your router will reboot shortly.", "success");
                         },
                         error:function(){
                             swal("Cancelled", "Rebooting has been canceled.", "error");

                         }
                     });
                } else {
                     swal("Cancelled", "Rebooting has been canceled.", "success");
                }
            });
        });

        $('#table-network tbody').on( 'click', '.reset', function () {
            var that = this;
               swal({
                 title: "Are you sure?",
                 text: "Router will be reset, and you will lose any special configuration!",
                 type: "warning",
                 showCancelButton: true,
                 confirmButtonColor: "#DD6B55",
                 confirmButtonText: "Yes, reset it!",
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
                        url:'reset/' + data.id,
                         success:function(data) {
                            location.reload();  
                             //table.row( $(that).parents('tr') ).remove().draw();
                             swal("reset!", "Your router will reset shortly.", "success");
                         },
                         error:function(){
                             swal("Cancelled", "Your router is safe :)", "error");

                         }
                     });
                } else {
                     swal("Cancelled", "Reset has been cancelled :)", "success");
                }
            });
        });
        $('#table-network tbody').on( 'click', '.edit', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
             //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_ajax').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'branchid/' + data.id,
                success: function(response)
                {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_ajax .modal-body').html(response);

                }
            });
        });
        $('#table-network tbody').on( 'click', '.timeline', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
            // LOADING THE AJAX MODAL
            jQuery('#modal_timeline').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'modal_timeline/' + data.id + '-' + 'branchs',
                success: function(response)
                {
                    jQuery('#modal_timeline .modal-body').html(response);
                }
            });
        });

        $('#table-network tbody').on( 'click', '.destinations', function () {
            var that = this;
            var data = table.row( $(that).parents('tr') ).data();
             if(data  == null){
               data = table.row( $(that).parents('tr').prev() ).data();
             }
            // LOADING THE AJAX MODAL
            jQuery('#modal_download').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'download_modal/' + data.id + '-' + 'branches',
                success: function(response)
                {
                    jQuery('#modal_download .modal-body').html(response);
                }
            });
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
               url:'branch_state/' + data.id + '/' + sus,
               success:function(data) {
                   if(sus) {
                       $(that).text('Active');
                       $(that).removeClass('btn-danger');
                       $(that).addClass('btn-success');

                   }
                   else {
                       $(that).text('Inactive');
                       $(that).removeClass('btn-success');
                       $(that).addClass('btn-danger');
                   }
               },
               error:function(){
                   if(!sus) {
                       $(that).text('Active');
                       $(that).removeClass('btn-danger');
                       $(that).addClass('btn-success');
                   }
                   else {
                       $(that).text('Inactive');
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
        minimumResultsForSearch: Infinity
    });
     $('.select-fixed-single200').select2({
            minimumResultsForSearch: Infinity,
            width: 353
        });
    $('.select-fixed-single100').select2({
        minimumResultsForSearch: Infinity,
        width: 85
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
          width: 85
      });
    $(".switch").bootstrapSwitch();
    
    $("#connection-type").change(function(){
        var connectiontype = $("#connection-type option:selected").val();
        
        if(connectiontype == 2){
            $('.adsl-username').show();
            $('.adsl-password').show();
        }else{
            $('.adsl-username').hide();
            $('.adsl-password').hide();
        }
        if(connectiontype == 6){
            $('.table-bordered').show();
        }else{
            $('.table-bordered').hide();
        }
    });

    $(".land-type").change(function(){
        var loadtype = $(".land-type option:selected").val();
        if(loadtype == 1){
            $('.load-username').show();
            $('.load-password').show();
        }else{
            $('.load-username').hide();
            $('.load-password').hide();
        }
    });  

    $("#backup-connection-type").change(function(){
        var connectiontype = $("#backup-connection-type option:selected").val();
        if(connectiontype == 2){
            $('.backup-adsl-username').show();
            $('.backup-adsl-password').show();
        }else{
            $('.backup-adsl-username').hide();
            $('.backup-adsl-password').hide();
        }
    });

    $('#backup-connection-state').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.backup-connection-type').show();
            
        } else {
            $('.backup-connection-type').hide();
        }
    });

    $('#wireless-state').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.wireless-username').show();
            $('.wireless-password').show();
            
        } else {
            $('.wireless-username').hide();
            $('.wireless-password').hide();
        }
    });

    $('#private-wireless-state').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.private-wireless-username').show();
            $('.private-wireless-password').show();
            $('.private-wireless-ip').show();
            
        } else {
            $('.private-wireless-username').hide();
            $('.private-wireless-password').hide();
            $('.private-wireless-ip').hide();            
        }
    });
    $('#advanced-script-state').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.advanced-script').show();
            
        } else {
            $('.advanced-script').hide();
        }
    });
        
    $('#log-visited').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.log-visited').show();
            
        } else {
            $('.log-visited').hide();
        }
    });

    $('#add-bypass').click(function () {
        var code = '<tbody> <tr id="row">\n';
                                                            
            code += '<td>\n';
                code += '<input name="bypass-ip[]" type="text" class="form-control input-xlg add-bypass-ip" title="If you know device IP enter here to obtain MAC-Address automatically in one minute." placeholder="10.5.50.100" value="" style="width: 120px;" required> \n';
            code += '</td>\n';

            code += '<td>\n';
                code += '<input name="bypass-mac[]" type="text" class="form-control input-xlg add-bypass-mac" title="If you know device Mac enter here to obtain IP automatically in one minute." placeholder="00:00:00:00:00" value="" style="width: 160px;" required> \n';
            code += '</td>\n';

            code += '<td>\n';
                code += '<input name="bypass-port[]" type="text" class="form-control input-xlg add-bypass-port" title="Only to be accesable from outside enter your port ex.80 or 0-65535" placeholder="0-65535" value="" style="width: 100px;" required> \n';
            code += '</td>\n';

            code += '<td>\n';
                code += '<button type="button" name="remove" class="btn btn-danger edit-btn_remove" ><i class="icon-minus2"></i></button>\n';
            code += '</td>\n';
            
            code += '</tr> </tbody>';
        $('#add-dynamic_bypass').append(code);
        $('.edit-select-fixed-single85').select2({
            minimumResultsForSearch: Infinity,
            width: 85
        });
 
    });

    $('.auto_login_add').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.auto-login-expiry-add').show();
            } else {
                $('.auto-login-expiry-add').hide();
            }
    }); 
    </script>
@endsection