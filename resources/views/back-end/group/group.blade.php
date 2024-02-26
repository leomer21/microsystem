@extends('..back-end.layouts.master')
@section('title', 'Group')
@section('content')
    <?php
    // check if any branch have ddwrt
    foreach(App\Branches::get() as $branch)
    {   
        if( $branch->radius_type == "aruba" ){ $foundDDWRT=1; }
        if( $branch->radius_type == "ddwrt" ){ $foundDDWRT=1; }
        if( $branch->radius_type == "mikrotik" ){ $foundMikrotik=1; }
        if( isset($branch->hardware_version) and $branch->hardware_version == "mikrotik_5.X"){$foundMikrotikV5=1;}
    }
    if(!isset($foundDDWRT)){$foundDDWRT=0;}
    if(!isset($foundMikrotik)){$foundMikrotik=0;}
    if(!isset($foundMikrotikV5)){$foundMikrotikV5=0;}
    // check on mikrotik version
    ?>
    <!-- Page header -->
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Group policy</h4>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Primary modal -->
    <div id="add_group" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Add Group</h6>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <form action="{{ url('add_group') }}" method="POST" id="addgroup" class="form-horizontal">
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
                                                <label class="control-label col-lg-3">Group Name</label>
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
                                                <label class="control-label col-lg-3">Group state</label>
                                                <div class="col-lg-8">
                                                    <select class="select-fixed-single" name="state">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3">Network name</label>
                                                <div class="col-lg-8">
                                                    <select class="select-fixed-single" name="network">
                                                        @foreach($networks as $network)
                                                            <option value="{{ $network->id }}">{{ $network->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3">Radius Type</label>
                                                <div class="col-lg-8">
                                                    <select class="select-fixed-single" name="r_type">
                                                        <option value="mikrotik">Mikrotik</option>
                                                        <option value="ddwrt">DD-WRT</option>
                                                        <option value="cisco">CISCO</option>
                                                    </select>
                                                </div>
                                            </div> -->

                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="Waiting time after device disconnected to remove user from active list to allow login again from another device with the same account."
                                                       data-placement="right">IDLE Timeout</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="idle_timeout" type="text"
                                                               class="form-control timepicker"
                                                               placeholder="00:02:00" value="00:02:00">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-alarm"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3">Notes</label>
                                                <div class="col-lg-9">
                                    <textarea name="notes" type="text" rows="2"
                                              class="form-control input-xlg"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                               href="#accordion-control-group3">Login</a>
                                        </h6>
                                    </div>
                                    <div id="accordion-control-group3" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="Allowed online devices simultaneously for each account"
                                                       data-placement="right">Concurrent sessions</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="u_multi_session" class="frequency form-control"
                                                               type="number"
                                                               value="1" min="1">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-users"></i>
                                                        </div>
                                                        <span class="help-block">0 : Unlimited</span>

                                                    </div>
                                                </div>


                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="Number of saved devices for auto login without landing page." data-placement="right">Saved
                                                    device
                                                    limit </label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="limited_devices" class="frequency form-control"
                                                               type="number"
                                                               value="0" min="0">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-screen3"></i>
                                                        </div>
                                                        <span class="help-block">0 : Unlimited</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="alert alert-info alert-styled-left alert-bordered">
                                                    <button type="button" class="close" data-dismiss="alert">
                                                        <span>&times;</span><span class="sr-only">Close</span></button>
                                                    <span class="text-semibold">Example 1</span> if you set (saved
                                                    devices) 0 and (concurrent devices) 2 : if user reach to maximum
                                                    saved devices and need to login through new device, system will
                                                    delete the first device and insert new device</a>.
                                                    <br/>
                                                    <span class="text-semibold">Example 2</span> if you set (saved
                                                    devices) 2 and (concurrent devices) 2 : system will save 2 devices
                                                    only, if user reach to maximum saved devices, user will not able to
                                                    login never from any new device</a>.

                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="Auto save Mac-address after first login to remove landing page for future login's "
                                                       data-placement="right">Auto Login</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left auto_login">
                                                        <input id="auto_login" checked type="checkbox" name="auto_login"
                                                               value="1"
                                                               class="switch">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12 auto-login-expiry">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="Number of waiting days till require username and password from auto logged in devices." data-placement="right">Auto login Expiry(Days)</label>
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

                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group_expire_users_after_days">Ticket expiration</a>
                                        </h6>
                                    </div>
                                    <div id="accordion-control-group_expire_users_after_days" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                    title="Allowed online devices simultaneously for each account"
                                                    data-placement="right">Auto expire after</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="expire_users_after_days" class="frequency form-control" type="number"
                                                            value="0" min="0">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-bin"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label class="control-label col-lg-1" data-popup="tooltip"
                                                    title="Allowed online devices simultaneously for each account"
                                                    data-placement="left">Days</label>
                                                
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="alert alert-info alert-styled-left alert-bordered">
                                                    if you need to expire/delete assigned users/tickets, please enter number of days to calculate it after
                                                    first login.
                                                    <br/>
                                                    <span class="text-semibold">To disable this feature</span> please enter 0</a>.
                                                </div>
                                            </div>
                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                               href="#accordion-control-group4">Limitations</a>
                                        </h6>
                                    </div>
                                    <div id="accordion-control-group4" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3">Upload (MB)</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="quota_limit_upload" class="frequency form-control"
                                                               type="number"
                                                               min="1">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-cloud-upload"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label class="control-label col-lg-3">Download (MB)</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="quota_limit_download"
                                                               class="frequency form-control"
                                                               type="number"
                                                               min="1">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-cloud-download"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12 has-feedback-left">
                                                <label class="control-label col-lg-3">Total (MB)</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="quota_limit_total" class="frequency form-control"
                                                               type="number"
                                                               min="1">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-cloud"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label class="control-label col-lg-3">Session Time</label>
                                                <div class="col-lg-3">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="session_time" type="text"
                                                               class="form-control timepicker">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-alarm"></i>
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
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                               href="#accordion-control-group5">Speed</a>
                                        </h6>
                                    </div>
                                    <div id="accordion-control-group5" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <!-- /////////////////////////////////  Start speed ////////////////////////////// -->
                                            <div class="alert alert-info alert-styled-left alert-bordered">
                                                <button type="button" class="close" data-dismiss="alert">
                                                    <span>&times;</span><span class="sr-only">Close</span></button>
                                                <center><span class="text-semibold">Start speed</span> before consuming limitations</a>.</center>
                                                <center>Note: Browsing mode speeds must be <span class="text-semibold"> greater </span> than Download mode.</a></center>
                                            </div>
                                            <div class="start_speed form-group col-lg-12">
                                                @if($foundDDWRT==1)
                                                    <label class="control-label col-lg-2">Speed</label>
                                                @else
                                                    <label class="control-label col-lg-2">Browsing Mode</label>
                                                @endif
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <input name="speed_limit1" title="Upload Speed" class="frequency form-control"
                                                               type="number"
                                                               placeholder="Upload" min="1"
                                                               onkeypress="return isNumber(event)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="input-group">
                                                        <select class="select-fixed-single75" name="stype1">
                                                            <option value="M">MB</option>
                                                            <option value="K">KB</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <input name="speed_limit2" title="Download Speed" class="frequency form-control"
                                                               type="number"
                                                               placeholder="Download" min="1"
                                                               onkeypress="return isNumber(event)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="input-group">
                                                        <select class="select-fixed-single75" name="stype2">
                                                            <option value="M">MB</option>
                                                            <option value="K">KB</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($foundDDWRT==0)
                                                <div class="start_speed form-group col-lg-12">
                                                    <label class="control-label col-lg-2">Download speed</label>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <input name="downSpeed_limit1" title="Upload Speed" class="frequency form-control"
                                                                type="number"
                                                                placeholder="Upload" min="1"
                                                                onkeypress="return isNumber(event)">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="input-group">
                                                            <select class="select-fixed-single75" name="downSpeedType1">
                                                                <option value="M">MB</option>
                                                                <option value="K">KB</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <input name="downSpeed_limit2" title="Download Speed" class="frequency form-control"
                                                                type="number"
                                                                placeholder="Download" min="1"
                                                                onkeypress="return isNumber(event)">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="input-group">
                                                            <select class="select-fixed-single75" name="downSpeedType2">
                                                                <option value="M">MB</option>
                                                                <option value="K">KB</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--  ///////////// priority ///////////////// -->
                                    
                                                @if($foundMikrotik==1 and $foundMikrotikV5==0)
                                                <div class="form-group col-lg-12 ">
                                                    <label class="control-label col-lg-2"> Priority </label>
                                                    <div class="col-lg-2">
                                                        <div class="input-group">
                                                            <select class="select-fixed-single75" name="startPriority">
                                                                <option value="8"> 8 </option>
                                                                <option value="7"> 7 </option>
                                                                <option value="6"> 6 </option>
                                                                <option value="5"> 5 </option>
                                                                <option value="4"> 4 </option>
                                                                <option value="3"> 3 </option>
                                                                <option value="2"> 2 </option>
                                                                <option value="1"> 1 </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <span class="">1 : Maximum <br>8 : Minimum</span>
                                                    </div>  
                                                </div>
                                                @endif
                                                <!--  ////////////////////////////// -->
                                            @endif

                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3"></label>
                                                <div class="col-lg-8">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input name="equationchecks" type="checkbox" class="styled"
                                                                   id="equationchecks">
                                                            Equation speed ( Advanced mode )
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="equation form-group col-lg-12 has-feedback-left"
                                                 style="display: none;">
                                                <label class="equation control-label col-lg-3"></label>
                                                <div class="equation col-lg-9">
                                                    <div class="equation form-group has-feedback has-feedback-left">
                                                        <input name="equationstart" class="frequency form-control"
                                                               type="text"
                                                               placeholder="end - start - average - seconds - priority - minimum">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-cloud"></i>
                                                        </div>
                                                        <span class="help-block"> 16k/256k 128k/2048k 10k/190k 30/30 8 128k/1024k</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title=" that applies if you set values in limit section, after user reach to any limit, speed will be downgraded till renewing date.
                                                        if you set off internet will be disconnected till renewing date."
                                                       data-placement="right">Downgrade Speed</label>
                                                <div class="col-lg-3">
                                                    <input id="self-rules2" type="checkbox" name="selfrules"
                                                           class="switch">
                                                </div>
                                                <div class="done"><input name="values" type="hidden" value="0"></div>

                                                <div class="end_speed form-group col-lg-12">
                                                    
                                                    <div class="endspeed alert alert-info alert-styled-left alert-bordered" style="display: none;">
                                                        <button type="button" class="close" data-dismiss="alert">
                                                            <span>&times;</span><span class="sr-only">Close</span></button>
                                                        <center><span class="text-semibold">End speed</span> after consuming limitations</a>.</center>
                                                        <center>Note: Browsing mode speeds must be <span class="text-semibold"> greater </span> than Download mode.</a></center>
                                                    </div> 

                                                    @if($foundDDWRT==1)
                                                        <label class="endspeed control-label col-lg-2" style="display: none;">Speed</label>
                                                    @else
                                                        <label class="endspeed control-label col-lg-2" style="display: none;">Browsing speed</label>
                                                    @endif
                                                    <div class="endspeed col-lg-3" style="display: none;">
                                                        <div class=" input-group">
                                                            <input name="end_speed1" title="Upload speed"
                                                                   class="endspeed frequency form-control"
                                                                   type="number" placeholder="Upload"
                                                                   onkeypress="return isNumber(event)">
                                                        </div>
                                                    </div>
                                                    <div class="endspeed col-lg-2" style="display: none;">
                                                        <div class="endspeed input-group">
                                                            <select class="endspeed select-fixed-single75" name="etype">
                                                                <option value="M">MB</option>
                                                                <option value="K">KB</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="endspeed col-lg-3" style="display: none;">
                                                        <div class="endspeedd input-group">
                                                            <input name="end_speed2" title="Download speed"
                                                                   class="endspeed frequency form-control"
                                                                   type="number" placeholder="Download"
                                                                   onkeypress="return isNumber(event)">
                                                        </div>
                                                    </div>

                                                    <div class="endspeed col-lg-2" style="display: none;">
                                                        <div class="endspeed input-group">
                                                            <select class="endspeed select-fixed-single75"
                                                                    name="etype2">
                                                                <option value="M">MB</option>
                                                                <option value="K">KB</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($foundDDWRT==0)
                                                    <div class="end_speed form-group col-lg-12">
                                                        <label class="endspeed control-label col-lg-2" style="display: none;">Download speed</label>
                                                        <div class="endspeed col-lg-3" style="display: none;">
                                                            <div class=" input-group">
                                                                <input name="endDownSpeed_limit1" title="Upload speed"
                                                                    class="endspeed frequency form-control"
                                                                    type="number" placeholder="Upload"
                                                                    onkeypress="return isNumber(event)">
                                                            </div>
                                                        </div>

                                                        <div class="endspeed col-lg-2" style="display: none;">
                                                            <div class="endspeed input-group">
                                                                <select class="endspeed select-fixed-single75" name="endDownSpeedType1">
                                                                    <option value="M">MB</option>
                                                                    <option value="K">KB</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="endspeed col-lg-3" style="display: none;">
                                                            <div class="endspeedd input-group">
                                                                <input name="endDownSpeed_limit2" title="Download speed"
                                                                    class="endspeed frequency form-control"
                                                                    type="number" placeholder="Download"
                                                                    onkeypress="return isNumber(event)">
                                                            </div>
                                                        </div>

                                                        <div class="endspeed col-lg-2" style="display: none;">
                                                            <div class="endspeed input-group">
                                                                <select class="endspeed select-fixed-single75"
                                                                        name="endDownSpeedType2">
                                                                    <option value="M">MB</option>
                                                                    <option value="K">KB</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--  ///////////// priority ///////////////// -->
                                    
                                                    @if($foundMikrotik==1 and $foundMikrotikV5==0)
                                                    <div class="endspeed form-group col-lg-12 " style="display: none;">
                                                        <label class="endspeed control-label col-lg-2"> Priority </label>
                                                        <div class="col-lg-2">
                                                            <div class="input-group">
                                                                <select class="endspeed select-fixed-single75" name="endPriority">
                                                                    <option value="8"> 8 </option>
                                                                    <option value="7"> 7 </option>
                                                                    <option value="6"> 6 </option>
                                                                    <option value="5"> 5 </option>
                                                                    <option value="4"> 4 </option>
                                                                    <option value="3"> 3 </option>
                                                                    <option value="2"> 2 </option>
                                                                    <option value="1"> 1 </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <span class="">1 : Maximum <br>8 : Minimum</span>
                                                        </div>  
                                                    </div>
                                                    @endif
                                                    <!--  ////////////////////////////// -->
                                                @endif

                                                <div class="endspeed form-group col-lg-12" style="display: none;">
                                                    <label class="endspeed control-label col-lg-3"></label>
                                                    <div class="endspeed col-lg-8">
                                                        <div class="endspeed checkbox">
                                                            <label>
                                                                <input name="equationcheckss" type="checkbox"
                                                                       class="endspeed styled"
                                                                       id="equationcheckss">
                                                                Equation speed (Advanced mode )
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="equation_end abc form-group col-lg-12 has-feedback-left" style="display: none;">
                                                    <label class="control-label col-lg-3"></label>
                                                    <div class="col-lg-9">
                                                        <div class="form-group has-feedback has-feedback-left">
                                                            <input name="equationend" class="frequency form-control"
                                                                   type="text"
                                                                   placeholder="end - start - average - seconds - priority - minimum">
                                                            <div class="form-control-feedback">
                                                                <i class="icon-cloud"></i>
                                                            </div>
                                                            <span class="help-block"> 24k/128k 32k/256k 24k/196k 30/30 8 16k/64k</span>
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
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                               href="#accordion-control-group2">Advertising</a>
                                        </h6>
                                    </div>
                                    <div id="accordion-control-group2" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="stop internet till user open browser then auto redirected to your link "
                                                       data-placement="right">URL Redirect</label>
                                                <div class="col-lg-9">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="url_redirect" type="text"
                                                               class="form-control input-xlg">
                                                        <div class="form-control-feedback">
                                                            http://
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($foundDDWRT==0)
                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="Reopen advertising link each: " data-placement="right">URL
                                                    Redirect Interval</label>
                                                <div class="col-lg-6">
                                                    <div class="form-group has-feedback has-feedback-left">
                                                        <input name="url_redirect_Interval" type="text"
                                                               class="form-control timepicker">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-watch2"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($foundDDWRT==0)
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                                                href="#accordion-control-group7">Website filtration</a>
                                            </h6>
                                        </div>
                                        <div id="accordion-control-group7" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <!-- Individual column searching (selects) -->
                                                <div class="panel panel-flat">
                                                    <div class="panel-body">
                                                        <div class="col-lg-6">
                                                            <input id="filtration" type="checkbox" name="website-state" class="switch">
                                                        </div>
                                                        <div class="col-lg-6 filtration-type" style="display: none;">
                                                        <select class="select-fixed-single" name="website-type">
                                                            <option value="1">Block all the following websites</option>
                                                            <option value="2">Block all websites expect the following site</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive filtration-table" style="display: none;">
                                                        <table class="table table-bordered" data-toggle="context" data-target=".context-table" id="dynamic_date">
                                                            <thead> 
                                                                <tr>
                                                                    <th>Website</th>
                                                                    <th class="text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">http://</span>
                                                                            <input name="websitename[]" type="text" class="form-control input-xlg"  placeholder="Website name" required>
                                                                        </div>
                                                                        <span class="help-block"> Please enter "Website name" without .com or any other things, You can enter just word to block any URL have this word...</span>
                                                                    </td>
                                                                    

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
                                                <!-- /individual column searching (selects) -->
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!-- /accordion with left control button -->
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Success_message"
                            onclick="document.forms['addgroup'].submit(); return false;">Save changes
                    </button>
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
                    <h6 class="modal-title">Edit Group</h6>
                </div>

                <div class="modal-body">


                    <hr>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="Success_message"
                            onclick="document.forms['edit'].submit(); return false;">Save changes
                    </button>
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
                <h5 class="panel-title">Groups Table</h5>
            </div>-->

            <div class="panel-body">
                <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_group">
                    <b><i class="icon-grid"></i></b> Add Group
                </button>
            </div>
            <table class="table" width="100%" id="table-group">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <!-- <th>Network Name</th> -->
                    <th>State</th>
                    <th>Total Quota</th>
                    <th>Limit Speed</th>
                    <th>End Speed</th>
                    <th>Online Users</th>
                    <th>Monthly usage (GB)</th>
                    <!-- <th>Total usage</th> -->
                    <th class="text-center">Actions</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
            </table>
        </div>
        <!-- /scrollable datatable -->
        @include('..back-end.footer')
    </div>

@section('css')
    <style>
        .ti_tx, .mi_tx, .si_tx, .mer_tx {
            width: 100%;
            text-align: center;
            margin: 10px 0
        }

        .time, .mins, .sand, .meridian {
            width: 60px;
            float: left;
            margin: 0 10px;
            font-size: 20px;
            color: #2d2e2e;
            font-family: arial;
            font-weight: 700
        }

        .prev, .next1 {
            cursor: pointer;
            padding: 18px;
            width: 28%;
            border: 1px solid #ccc;
            margin: auto;
            background: url(assets/images/arrow.png) no-repeat;
            border-radius: 5px
        }

        .prev:hover, .next1:hover {
            background-color: #ccc
        }

        .next1 {
            background-position: 50% 150%
        }

        .prev {
            background-position: 50% -50%
        }

        .time_pick {
            position: relative
        }

        .timepicker_wrap {
            width: 262px;
            padding: 10px;
            border-radius: 5px;
            z-index: 998;
            display: none;
            box-shadow: 2px 2px 5px 0 rgba(50, 50, 50, 0.35);
            background: #f6f6f6;
            border: 1px solid #ccc;
            float: left;
            position: absolute;
            top: 27px;
            left: 0
        }

        .arrow_top {
            position: absolute;
            top: -10px;
            left: 20px;
            background: url(assets/images/top_arr.png) no-repeat;
            width: 18px;
            height: 10px;
            z-index: 999
        }

        input.timepicki-input {
            background: none repeat scroll 0 0 #fff;
            border: 1px solid #ccc;
            border-radius: 5px 5px 5px 5px;
            float: none;
            margin: 0;
            text-align: center;
            width: 70%
        }

        a.reset_time {
            float: left;
            margin-top: 5px;
            color: #000
        }
    </style>
    <link rel="stylesheet" type="text/css" href="assets/css/timepicker.css">
@endsection
@section('js')

    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/notifications/bootbox.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>

    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="assets/js/timepicker.js"></script>
    <script type="text/javascript" src="assets/js/timepicki.js"></script>
@endsection
<script>
	$('#add_load').click(function () {
        var code = '<tbody> <tr id="row">\n';
                                                            
            code += '<td>\n';
            	code += '<div class="input-group">';
            	code += '<span class="input-group-addon">http://</span>';
                code += '<input name="websitename[]" type="text" class="form-control input-xlg" placeholder="Website name" required> \n';
                code += '</div>';
            code += '</td>\n';

            
            
            code += '<td>\n';
                code += '<button type="button" name="remove" class="btn btn-danger btn_remove"><i class="icon-minus2"></i></button>\n';
            code += '</td>\n';
            
            code += '</tr> </tbody>';
        $('#dynamic_date').append(code);
    });


    $(document).on('click', '.btn_remove', function () {
        $(this).parent().parent().remove();
    });
    $('#filtration').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.filtration-table').show();
            $('.filtration-type').show();            

        } else {
            $('.filtration-table').hide();
            $('.filtration-type').hide();            

        }
    });
    // Table setup
    // ------------------------------

    // Setting datatable defaults
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            orderable: false,
            width: '100px',
            targets: [5]
        }],
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        }

    });

    // Basic initialization
    var table = $('#table-group').DataTable({
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        ajax: {"url": "groups", type: "get", data: {_token: $('meta[name="csrf-token"]').attr('content')}},
        buttons: {
            dom: {
                button: {
                    className: 'btn btn-default'
                }
            },
            buttons: [
                {extend: 'copy', text: '<i title="Copy" class="icon-copy3"></i>'},
                {extend: 'csv', text: '<i title="Export to CSV sheet." class="icon-file-spreadsheet"></i>'},
                {extend: 'excel', text: '<i title="Export to excel sheet." class="icon-file-excel"></i>'},
                {extend: 'pdf', text: '<i title="Export to PDF file." class="icon-file-pdf"></i>'},
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
                targets: -1
            }],
        deferRender: true,
        columns: [

            // {"data": "name"},
            {"data": "id"},
            {"render": function ( type, full, data, meta ) {
                return '<a href="#" title="Edit" class="edit" >'+data.name+'</a>';
            }},
            // {"data": "n_name"},
            {
                "data": "state",
                "searchable": false,
                "render": function (type, full, data, meta) {
                    if (data.is_active == 1)
                        return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Active</span></button>';
                    else
                        return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Inactive</span></button>';

                }
            }
            ,
            {
                "data": "quota_limit_total",
                "searchable": false,
                "render": function (type, full, data, meta) {
                    if (data.quota_limit_total != 0)
                        return (data.quota_limit_total / 1024 / 1024) + " MB";
                    else
                        return '<span class="label border-left-success label-striped">Unlimited </span>';


                }

            },
            {
                "data": "speed_limit",
                "searchable": false,
                "render": function (type, full, data, meta) {
                    if (!data.speed_limit || data.speed_limit == 0)
                        return '<span class="label border-left-success label-striped">Unlimited </span>';
                    else
                        return data.designed_speed;
                        //return data.speed_limit;
                }
            },
            {
                "data": "end_speed",
                "searchable": false,
                "render": function (type, full, data, meta) {
                    if (data.if_downgrade_speed == 0)
                        if( (data.quota_limit_upload==0 || !data.quota_limit_upload) && (data.quota_limit_download==0 || !data.quota_limit_download) && (data.quota_limit_total==0 || !data.quota_limit_total) && (data.session_time==0 || !data.session_time) )
                            return '<td><span class="label border-left-success label-striped">Unlimited </span></td>';
                        else
                            return '<td><span class="label label-danger">Internet will Stop</span></td>';
                    else
                        return data.designed_end_speed;
                        //return data.end_speed;

                }
            },
            {
                "render": function (type, full, data, meta) {
                    return '<div class="col-md-3 col-sm-4"><i class="icon-users2"></i></div> &nbsp &nbsp' + data.count_online + '/' + data.count_users + '';
                }
            },
            {"data": "monthly_usage"},
            // {"data": "total_usage"},
            {
                "render": function (type, full, data, meta) {
                    if (data.name == "default" || data.name == "Default")
                        return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                '<li><a href="#" class="edit"><i class="icon-pencil3"></i> Edit</a></li>' +
                                '<li><a href="#" class="timeline" ><i class="icon-file-stats"></i> Timeline</a></li>' +
                                '<li><a href="#" class="destinations" ><i class="icon-link2"></i> Export visited Websites</a></li>' +
                                '</ul> </li> </ul>';
                    else
                        return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                '<li><a href="#" class="edit"><i class="icon-pencil3"></i> Edit</a></li>' +
                                '<li><a href="#" class="timeline" ><i class="icon-file-stats"></i> Timeline</a></li>' +
                                '<li><a href="#" class="destinations" ><i class="icon-link2"></i> Export visited Websites</a></li>' +
                                '<li><a href="#" class="delete"><i class="icon-cross3"></i> Delete</a></li>' +
                                '</ul> </li> </ul>';

                }
                // "data": null,
                // "defaultContent": '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                // '<li><a href="#" class="edit"><i class="icon-pencil3"></i> Edit</a></li>' +
                // '<li><a href="#" class="timeline" ><i class="icon-file-stats"></i> Timeline</a></li>' +
                // '<li><a href="#" class="destinations" ><i class="icon-link2"></i> Export Website visited</a></li>' +
                // '<li><a href="#" class="delete"><i class="icon-cross3"></i> Delete</a></li>' +
                // '</ul> </li> </ul>'
            },
            {"data": null, "defaultContent": ""}
        ]
    });
    // Responsive integration
    $('.datatable-row-responsive').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    });

    // Column selectors
    $('.datatable-button-html5-columns').DataTable({
        buttons: {
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: [0, ':visible']
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
    $('#table-group tbody').on('click', '.delete', function () {
        var that = this;
        swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover group data again!",
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
                        var data = table.row($(that).parents('tr')).data();
                        if (data == null) {
                            data = table.row($(that).parents('tr').prev()).data();
                        }
                        $.ajax({
                            url: 'delete_group/' + data.id,
                            success: function (data) {
                                table.row($(that).parents('tr')).remove().draw();
                                swal("Deleted!", "Your group data has been deleted.", "success");
                            },
                            error: function () {
                                swal("Cancelled", "Your group data is safe :)", "error");

                            }
                        });
                    } else {
                        swal("Cancelled", "Your Cancelled :)", "success");
                    }

                });

    });

    $('#table-group tbody').on('click', '.edit', function () {
        var that = this;
        var data = table.row($(that).parents('tr')).data();
        if (data == null) {
            data = table.row($(that).parents('tr').prev()).data();
        }

        // LOADING THE AJAX MODAL
        jQuery('#modal_ajax').modal('show', {backdrop: 'true'});


        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'groupz/' + data.id,
            success: function (response) {
                jQuery('#modal_ajax .modal-body').html(response);
            }
        });
    });

    $('#table-group tbody').on('click', '.timeline', function () {
        var that = this;
        var data = table.row($(that).parents('tr')).data();
        if (data == null) {
            data = table.row($(that).parents('tr').prev()).data();
        }
        // LOADING THE AJAX MODAL
        jQuery('#modal_timeline').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'modal_timeline/' + data.id + '-' + 'groups',
            success: function (response) {
                jQuery('#modal_timeline .modal-body').html(response);
            }
        });

    });

    $('#table-group tbody').on('click', '.destinations', function () {
        var that = this;
        var data = table.row($(that).parents('tr')).data();
        if (data == null) {
            data = table.row($(that).parents('tr').prev()).data();
        }
        // LOADING THE AJAX MODAL
        jQuery('#modal_download').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: 'download_modal/' + data.id + '-' + 'groups',
            success: function (response) {
                jQuery('#modal_download .modal-body').html(response);
            }
        });

    });
    

    $('#table-group tbody').on('click', 'button.btn-ladda-spinner', function () {
        var data = table.row($(this).parents('tr')).data(),
                sus = ($(this).hasClass('btn-success')) ? false : true,
                that = this;
        if (data == null) {
            data = table.row($(that).parents('tr').prev()).data();
        }
        $(this).text('Loading...');
        $.ajax({
            url: 'group_state/' + data.id + '/' + sus,
            success: function (data) {
                if (sus) {
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
            error: function () {
                if (!sus) {
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
        minimumResultsForSearch: Infinity,
        width: 250
    });
    $('.select-fixed-single75').select2({
        minimumResultsForSearch: Infinity,
        width: 75
    });
    // Checkboxes
    $(".styled").uniform({
        radioClass: 'choice'
    });

    $('.timepicker').timepicki({
        show_meridian: false,
        min_hour_value: 0,
        max_hour_value: 23,
        overflow_minutes: true,
        increase_direction: 'up',
        disable_keyboard_mobile: true
    });
    $(".switch").bootstrapSwitch();
    $('#self-rules2').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.endspeed').show();
            $('.equation_end').hide();

            $('.done').append('<input name="values" type="hidden" value="1">');

        } else {
            $('.endspeed').hide();
            $('.equation_end').hide();
            $('.done').append('<input name="values" type="hidden" value="0">');
        }
    });

    $('#equationchecks').on('click', function () {
        if ($(this).is(':checked')) {
            $('.equation').show();
            $('.start_speed').hide();
        } else {
            $('.equation').hide();
            $('.start_speed').show();
        }
    });

    $('#equationcheckss').on('click', function () {
        if ($(this).is(':checked')) {
            $('.equations').show();
            $('.end_speed').hide();
            $('.equation_end').show();
            $('#endspeedd').hide();
        } else {
            $('.equations').hide();
            $('.end_speed').show();
            $('.equation_end').hide();
            $('#endspeedd').show();
        }
    });
    $('.auto_login').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.auto-login-expiry').show();
        } else {
            $('.auto-login-expiry').hide();
        }
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    $('.select-fixed-singless').select2({
        minimumResultsForSearch: Infinity,
        width: 75
    });
</script>
@endsection