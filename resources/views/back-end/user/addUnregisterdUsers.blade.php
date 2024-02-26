    <!-- Info modal -->
    <div id="add_user" class="modal fade">
        <div class="modal-dialog modal-ls">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title"><i class=' icon-user-plus'></i> Add User</h6>
                </div>
                <div class="modal-body">
                    <h6 class="text-semibold"></h6>
                    <form action="{{ url('adduser') }}" method="POST" id="adduser" class="form-horizontal">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Name</label>
                            <div class="col-lg-10">
                                <input name="fullname" type="text" class="form-control" placeholder="Full Name">
                                <div class="form-control-feedback">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Username *</label>
                            <div class="col-lg-10">
                                <input name="username" type="text" class="form-control input-xlg"
                                       placeholder="Username">
                                <div class="form-control-feedback">
                                    <i class="icon-user"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Password *</label>
                            <div class="col-lg-10">
                                <input name="password" type="password" class="form-control input-xlg"
                                       placeholder="Password">
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Gender</label>
                            <div class="col-lg-4">
                                <select class="select-fixed-singles" name="gender">
                                    <option value="1">Male</option>
                                    <option value="0">Female</option>
                                </select>
                            </div>
                            <label class="text-semibold col-lg-2">Language</label>
                            <div class="col-lg-4">
                                <select class="select-fixed-singles" name="lang">
                                    <!--<option value="ar">Arabic</option>-->
                                    <option value="en">English</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Address</label>
                            <div class="col-lg-10">
                                <input name="address" type="text" class="form-control input-xlg"
                                       placeholder="Address">
                                <div class="form-control-feedback">
                                    <i class="icon-location4"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Mobile</label>
                            <div class="col-lg-10">
                                <input name="phone" type="text" class="form-control tokenfield" placeholder="201X XXX XXXXX">
                                <div class="form-control-feedback">
                                    <i class="icon-mobile"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input name="email" type="text" class="form-control tokenfield" placeholder="E-Mail">
                                <div class="form-control-feedback">
                                    <i class="icon-mail5"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Mac Address</label>
                            <div class="col-lg-10">
                                <input name="mac" type="text" class="form-control tokenfield" placeholder="00:00:00">
                                <div class="form-control-feedback">
                                    <i class="icon-server"></i>
                                </div>
                            </div>
                        </div>


                        <!--<div class="form-group has-feedback-left">
                                            <label class="text-semibold col-lg-2 control-label">Mac Address</label>
                                            <div class="col-lg-10">
                                              <input  name="mac" type="text" class="form-control tokenfield">
                                            </div>
                                        </div>-->
                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Branch</label>
                            <div class="col-lg-4">
                                <select class="select-fixed-singles" name="branch_id">
                                    @foreach($branchesData as $valueBranches)
                                        <option value="{{ $valueBranches->id }}">{{ $valueBranches->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="text-semibold col-lg-2 control-label">Country</label>
                            <div class="col-lg-4">
                                <select class="countries" name="countrie">
                                    <?php $systemCountry=App\Settings::where('type', 'country')->value('value'); ?>
                                    @foreach($countries as $countrie)
                                        <option @if($systemCountry==$countrie) selected @endif value="{{ $countrie }}">{{ $countrie }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="control-label col-lg-3">Internet state</label>
                            <div class="col-lg-3">
                                <input type="checkbox" name="state" value='1' class="switch" checked>
                            </div>
                            <label class="control-label col-lg-2">Suspend</label>
                            <div class="col-lg-4">
                                <input type="checkbox" name="Suspend" class="switch">
                            </div>
                        </div> -->

                        <!--<div class="form-group has-feedback-left">
                                            <label class="text-semibold col-lg-3 control-label">Registration state</label>
                                            <div class="col-lg-9">
                                                <input class="radioss1 radioss" type="radio" id="radioo1" name="Registration" value="0" checked>
                                                <label class="radioll1 radioll" for="radioo1">Waiting admin confirm</label>
                                                <input class="radioss1 radioss" type="radio" id="radioo2" name="Registration" value="1">
                                                <label class="radioll1 radioll" for="radioo2">Waiting sms confirm</label>
                                                <input class="radioss2 radioss" type="radio" id="radioo3" name="Registration" value="2">
                                                <label class="radioll2 radioll" for="radioo3">Approved</label>
                                            </div>
                                        </div>-->

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Notes</label>
                            <div class="col-lg-10">
                                <textarea name="notes" rows="3" type="text" class="form-control input-xlg"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-3">Self Rules</label>
                            <div class="col-lg-3">
                                <input id="self-ruless" type="checkbox" name="selfrulesState" value="1"
                                       class="switch">
                            </div>
                            <div class="done2"></div>

                        </div>
                        <div class="row " id="hidden">
                            <div class="col-lg-6 form-group has-feedback-left">
                                <label class=" codntrol-label col-lg-5">Network Name</label>
                                <div class="col-lg-5">
                                    <select class="network-edit" name="networkname">
                                        @foreach ($networks as $network)
                                            <option value="{{ $network->id }}">{{ $network->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="divgruops" class="col-lg-6 form-group has-feedback-left">
                                <label class="control-label col-lg-3">Gruop</label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select an option" class="gruop-edit" name="groupnames">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="div1" style="display:none">
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

                                            <div class="form-group col-lg-12">
                                                <label class="control-label col-lg-3">Radius Type</label>
                                                <div class="col-lg-8">
                                                    <select class="select-fixed-single" name="r_type">
                                                        <option value="mikrotik">Mikrotik</option>
                                                        <option value="ddwrt">DD-WRT</option>
                                                        <option value="cisco">CISCO</option>
                                                    </select>
                                                </div>
                                            </div>

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
                                                    </div>
                                                </div>


                                                <label class="control-label col-lg-3" data-popup="tooltip"
                                                       title="0 : Unlimited " data-placement="right">Saved
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
                                                        <input id="auto_login" type="checkbox" name="auto_login"
                                                               value="1"
                                                               class="switch">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12 auto-login-expiry" style="display:none;">
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
                                            <div class="start_speed form-group col-lg-12">
                                                <label class="control-label col-lg-2">Start Speed</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <input name="speed_limit1" class="frequency form-control"
                                                               type="number"
                                                               placeholder="Upload" min="1"
                                                               onkeypress="return isNumber(event)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="input-group">
                                                        <select class="select-fixed-single75" name="stype1">
                                                            <option value="K">Kilobyte</option>
                                                            <option value="M">Mega</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <input name="speed_limit2" class="frequency form-control"
                                                               type="number"
                                                               placeholder="Download" min="1"
                                                               onkeypress="return isNumber(event)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="input-group">
                                                        <select class="select-fixed-single75" name="stype2">
                                                            <option value="K">Kilobyte</option>
                                                            <option value="M">Mega</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
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
                                                    <label class="control-label col-lg-2"></label>
                                                    <div class="endspeed col-lg-3" style="display: none;">
                                                        <div class=" input-group">
                                                            <input name="end_speed1"
                                                                   class="endspeed frequency form-control"
                                                                   type="number" placeholder="Upload"
                                                                   onkeypress="return isNumber(event)">
                                                        </div>
                                                    </div>

                                                    <div class="endspeed col-lg-2" style="display: none;">
                                                        <div class="endspeed input-group">
                                                            <select class="endspeed select-fixed-single75" name="etype">
                                                                <option value="K">Kilobyte</option>
                                                                <option value="M">Mega</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="endspeed col-lg-3" style="display: none;">
                                                        <div class="endspeedd input-group">
                                                            <input name="end_speed2"
                                                                   class="endspeed frequency form-control"
                                                                   type="number" placeholder="Download"
                                                                   onkeypress="return isNumber(event)">
                                                        </div>
                                                    </div>

                                                    <div class="endspeed col-lg-2" style="display: none;">
                                                        <div class="endspeed input-group">
                                                            <select class="endspeed select-fixed-single75"
                                                                    name="etype2">
                                                                <option value="K">Kilobyte</option>
                                                                <option value="M">Mega</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="equation_endspeed form-group col-lg-12"
                                                     style="display: none;">
                                                    <label class=" control-label col-lg-3"></label>
                                                    <div class=" col-lg-8">
                                                        <div class=" checkbox">
                                                            <label>
                                                                <input name="equationcheckss" type="checkbox"
                                                                       class="endspeed styled"
                                                                       id="equationcheckss">
                                                                Equation speed (Advanced mode )
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="equation_end form-group col-lg-12 has-feedback-left"
                                                     style="display: none;">
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
                                        </div>
                                    </div>
                                </div>


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
                                                        <option value="2">Block all websites expect the following sites</option>
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
                            </div>
                            <!-- /accordion with left control button -->
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info"
                            onclick="document.forms['adduser'].submit(); return false;">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal -->