<h6 class="text-semibold"></h6>
<?php $pmsIntegrationState = App\Settings::where('type', 'pms_integration')->value('state'); ?>
<form action="{{ url('edit_user/'.$user->u_id) }}" method="POST" id="edituser" class="form-horizontal">
    <input type="hidden" name="as_system" value="{{ $groups->as_system}}">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="panel border-left-danger">
        <div class="panel-body">
        @if(App\Network::where('state','1')->value('commercial')!='1')
            <div class="row">
                <div class="col-lg-5">Credit</div>
                <div class="col-lg-7">@if($user->credit) {{ $user->credit }} @else 0 @endif</div>
            </div>
        @endif
            <div class="row">
                <div class="col-md-5">Registration Type</div>
                <div class="col-lg-7">@if(isset($user->facebook_id)) Facebook @elseif(isset($user->twitter_id))
                        Twitter @elseif(isset($user->google_id)) Google @elseif(isset($user->linkedin_id))
                        Linkedin @else Self registration @endif</div>
            </div>
        @if(App\Network::where('state','1')->value('commercial')!='1')
            <div class="row">
                <div class="col-md-5">Package start</div>
                @if($user->monthly_package_start != null)
                    <div class="col-lg-7">{{ $user->monthly_package_start }}</div>
                @elseif($user->validity_package_start != null)
                    <div class="col-lg-7">{{ $user->validity_package_start }}</div>
                @elseif($user->time_package_start != null)
                    <div class="col-lg-7">{{ $user->time_package_start }}</div>
                @elseif($user->bandwidth_package_start != null)
                    <div class="col-lg-7">{{ $user->bandwidth_package_start }}</div>
                @else
                    <div class="col-lg-7">Notyet</div>
                @endif
            </div>
        @endif
            <div class="row">
                <div class="col-md-5">Registration Date</div>
                <div class="col-lg-7">{{ $user->created_at->format('d-m-Y g:i a') }}</div>
            </div>
            
            <div class="row">
                <div class="col-md-5">Last modification</div>
                <div class="col-lg-7">@if(isset($user->updated_at) && $user->updated_at!="-0001-11-30 00:00:00") {{$user->updated_at->format('d-m-Y g:i a')}} @endif </div>
            </div>

            <div class="row">
                <div class="col-md-5">Last SMS Verification</div>
                <div class="col-lg-7">{{ $user->sms_code }}</div>
            </div>

            <div class="row">
                <div class="col-md-5">Total Visits</div>
                <div class="col-lg-7"> @if($visits == 1 or $visits == 0) {{$visits}} day @else {{$visits}} days @endif </div>
            </div>

            <div class="row">
                <div class="col-md-5"> Total Consumption: </div>
                <div class="col-lg-7"> @if($totalQuotaConsumptionAllMonths > 1073741824) {{ round($totalQuotaConsumptionAllMonths/1024/1024/1024,1) }} GB @else {{ round($totalQuotaConsumptionAllMonths/1024/1024,1) }} MB @endif </div>
            </div>

            @if($pmsIntegrationState==1)
                <div class="row">
                    <div class="col-md-5"> Check-In: </div>
                    <div class="col-lg-7"> 
                        <?php 
                        if (strpos($user->notes, 'checkIn:') !== false) {
                            // echo explode(",",explode("checkIn:",$user->notes)[1])[0];
                            echo $userCheckIn = @explode(",",end(preg_split('/checkIn: /', $user->notes)))[0];
                        }
                        ?> </div>
                </div>

                <div class="row">
                    <div class="col-md-5"> Check-Out: </div>
                    <div class="col-lg-7"> 
                        <?php 
                        if (strpos($user->notes, 'checkOut:') !== false) {
                            // echo explode(",",explode("checkOut:",$user->notes)[1])[0];
                            echo $userCheckOut = @explode(",",end(preg_split('/checkOut: /', $user->notes)))[0];
                        }
                        ?> </div>
                </div>

                <div class="row">
                    <div class="col-md-5"> Nights: </div>
                    <div class="col-lg-7"> 
                        <?php 
                        if (strpos($user->notes, 'checkOut:') !== false and strpos($user->notes, 'checkIn:') !== false) {
                            // $checkin = strtotime(explode(",",explode("checkIn:",$user->notes)[1])[0]);
                            // $checkout = strtotime(explode(",",explode("checkOut:",$user->notes)[1])[0]);
                            $checkin = strtotime($userCheckIn);
                            $checkout = strtotime($userCheckOut);
                            $datediff = $checkout - $checkin;
                            echo round($datediff / (60 * 60 * 24));
                        }
                        ?> </div>
                </div>

                <div class="row">
                    <div class="col-md-5"> Room Type: </div>
                    <div class="col-lg-7"> 
                        <?php 
                        if (strpos($user->notes, 'Room Type:') !== false) {
                            // echo $guestRoomType=explode(",",explode("Room Type:",$user->notes)[1])[0];
                            echo $guestRoomType = @explode(",",end(preg_split('/Room Type:/', $user->notes)))[0];
                            echo " ".App\Groups::where('name',str_replace(' ', '', $guestRoomType))->value('notes');
                        }
                        ?> </div>
                </div>

                <div class="row">
                    <div class="col-md-5"> Birthday: </div>
                    <div class="col-lg-7"> 
                        <?php 
                         if (strpos($user->notes, 'Guest birthday:') !== false ) {
                            // echo $dateOfBirth = explode(",",explode("Guest birthday:",$user->notes)[1])[0];
                            echo $dateOfBirth = @explode(",",end(preg_split('/Guest birthday:/', $user->notes)))[0];
                            if($dateOfBirth!= "" and $dateOfBirth!=" -" and $dateOfBirth!="-"){
                                // calculate age
                                $diff = @date_diff(date_create($dateOfBirth), date_create(date("Y-m-d")));
                                if(isset($diff) and $diff!="" and $diff!="0"){
                                    echo ' Age is '.$diff->format('%y');
                                }
                                
                            }
                        }
                        ?> </div>
                </div>

                <div class="row">
                    <div class="col-md-5"> Reservation NO: </div>
                    <div class="col-lg-7"> 
                        <?php 
                        if (strpos($user->notes, 'Reservation Number:') !== false) {
                            // echo explode(",",explode("Reservation Number:",$user->notes)[1])[0];
                            echo @explode(",",end(preg_split('/Reservation Number:/', $user->notes)))[0];
                        }
                        ?> </div>
                </div>

                <div class="row">
                    <div class="col-md-5"> Confirmation NO: </div>
                    <div class="col-lg-7"> 
                        <?php 
                        if (strpos($user->notes, 'Confirmation Number:') !== false) {
                            // echo explode(",",explode("Confirmation Number:",$user->notes)[1])[0];
                            echo @explode(",",end(preg_split('/Confirmation Number:/', $user->notes)))[0];
                        }
                        ?> </div>
                </div>

            @endif
            
        </div>
    </div>
    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Full Name</label>
        <div class="col-lg-10">
            <input name="name" type="text" class="form-control" value="{{ $user->u_name }}">
            <div class="form-control-feedback">
                <i class="icon-user"></i>
            </div>
        </div>
    </div>
    
    @if( App\Network::where('r_type', '2')->count() > 0 and $pmsIntegrationState!= 1 )
        <!-- SMS verification enabled so we will update the same username and password -->
        <input name="username" type="hidden" value="{{ $user->u_uname }}">
        <input name="password" type="hidden" value="{{ $user->u_password }}">
    @else
        <div class="form-group has-feedback-left">
            <label class="text-semibold col-lg-2 control-label">Username *</label>
            <div class="col-lg-10">
                <input name="username" type="text" class="form-control input-xlg" value="{{ $user->u_uname }}" required>
                <div class="form-control-feedback">
                    <i class="icon-vcard"></i>
                </div>
            </div>
        </div>

        <div class="form-group has-feedback-left">
            <label class="text-semibold col-lg-2 control-label">Password *</label>
            <div class="col-lg-10">
                <input name="password" type="text" class="form-control input-xlg" value="{{ $user->u_password }}"
                    required>
                <div class="form-control-feedback">
                    <i class="icon-key"></i>
                </div>
            </div>
        </div>
    @endif

    @if(App\Network::where('id', $user->network_id)->value('commercial') == 1)

    @else
        <!-- check if user burchase any backage before -->
        @if( $user->monthly_package_expiry != null or $user->validity_package_expiry != null or $user->time_package_expiry != null or $user->bandwidth_package_expiry != null )
        <div class="form-group has-feedback-left">
            <label class="text-semibold col-lg-2 control-label">Package expiry</label>
            <div class="col-lg-10">
                @if($user->monthly_package_expiry != null)
                    <input name="expiry" type="text" class="form-control" value="{{ $user->monthly_package_expiry}}">
                @elseif($user->validity_package_expiry != null)
                    <input name="expiry" type="text" class="form-control" value="{{ $user->validity_package_expiry}}">
                @elseif($user->time_package_expiry != null)
                    <input name="expiry" type="text" class="form-control" value="{{ $user->time_package_expiry}}">
                @endif
                @if($user->bandwidth_package_expiry != null)
                    <input name="expiry" type="text" class="form-control" value="{{ $user->bandwidth_package_expiry}}">
                @endif
            </div>
        </div>
        @endif
    @endif
    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Gender</label>
        <div class="col-lg-4">
            <select class="select-fixed-singles" name="gender">
                <option value="1" @if($user->u_gender==1)selected @endif >Male</option>
                <option value="0" @if($user->u_gender==0)selected @endif >Female</option>
            </select>
        </div>
        <label class="text-semibold col-lg-2 control-label">Language</label>
        <div class="col-lg-4">
            <select class="select-fixed-singles" name="lang">
                <option value="en">English</option>
            </select>
        </div>
    </div>

    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Address</label>
        <div class="col-lg-10">
            <input name="address" type="text" class="form-control input-xlg" value="{{ $user->u_address }}">
            <div class="form-control-feedback">
                <i class="icon-location4"></i>
            </div>
        </div>
    </div>

    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Mobile</label>
        <div class="col-lg-10">
            <input name="phone" type="text" class="form-control tokenfield" value="{{ $user->u_phone }}"
                   placeholder="2010000">
            <div class="form-control-feedback">
                <i class="icon-mobile"></i>
            </div>
        </div>
    </div>

    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Email</label>
        <div class="col-lg-10">
            <input name="email" type="text" class="form-control tokenfield" value="{{ $user->u_email }}"
                   placeholder="mail@">
            <div class="form-control-feedback">
                <i class="icon-mail5"></i>
            </div>
        </div>
    </div>

    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Mac Address</label>
        <div class="col-lg-10">
            <input name="mac" type="text" class="form-control tokenfield" value="{{ $user->u_mac }}"
                   placeholder="00:00:00">
            <div class="form-control-feedback">
                <i class="icon-server"></i>
            </div>
        </div>
    </div>

    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Branch</label>
        <div class="col-lg-4">
            <select class="select-fixed-singles" name="branch_id">
                @foreach($branches as $valueBranches)
                    <option @if($user->branch_id == $valueBranches->id) selected
                            @endif value="{{ $valueBranches->id }}">{{ $valueBranches->name }}</option>
                @endforeach
            </select>
        </div>
        <label class="text-semibold col-lg-2 control-label">Country</label>
        <div class="col-lg-4">
            <select class="countries" name="countrie" value="{{$user->u_country}}">
                @foreach($countries as $countrie)
                    <option @if($user->u_country == $countrie) selected @endif value="{{ $countrie }}">{{ $countrie }}</option>
                @endforeach
            </select>
        </div>
    </div>


<!--<div class="form-group has-feedback-left">
            <label class="text-semibold col-lg-3 control-label">Registration state</label>
            <div class="col-lg-9">
                <input class="radios1 radios" type="radio" id="radio1" name="Registration" value="0" @if($user->Registration_type == "0"){ checked } @endif>
                <label class="radiol1 radiol" for="radio1">Waiting admin confirm</label>
                <input class="radios1 radios" type="radio" id="radio2" name="Registration" value="1" @if($user->Registration_type == "1"){ checked } @endif>
                <label class="radiol1 radiol" for="radio2">Waiting sms confirm</label>
                <input class="radios2 radios" type="radio" id="radio3" name="Registration" value="2" @if($user->Registration_type == "2"){ checked } @endif>
                <label class="radiol2 radiol" for="radio3">Approved</label>
            </div>
        </div>-->

    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Notes</label>
        <div class="col-lg-10">
            <textarea name="notesnotes" rows="2" type="text" class="form-control input-xlg">{{ $user->notes }}</textarea>
        </div>
    </div>

    <div class="form-group">
        <!-- <label class="control-label col-lg-3">Internet state</label>
        <div class="col-lg-3">
            <input type="checkbox" name="state" class="switch" @if($user->u_state == "1") checked  @endif>
        </div> -->
    
        <label class="text-semibold col-lg-2 control-label">Suspend</label>
        <div class="col-lg-4">
            <input type="checkbox" name="Suspend" class="switch" @if($user->suspend == "1") checked  @endif>
        </div>
    </div>

    <!-- <div class="row"> -->
        <!-- <div class="col-lg-6 form-group has-feedback-left">
            <label class="text-semibold col-lg-5 control-label">Network Name</label>
            <div class="col-lg-3">
                <select class="network-edit" name="networkname">
                    @foreach ($networks as $network)
                        <option value="{{ $network->id }}">{{ $network->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div id="divgruop" class="col-lg-6 form-group has-feedback-left" style="display: none;">
            <label class="text-semibold col-lg-3 control-label">Gruop</label>
            <div class="col-lg-9">

                <select class="gruop-edit" name="groupname" data-placeholder="Select an option" id="select_problem">

                </select>
                <input id="group_problem" type="hidden" name="groupname" value="{{ $user->group_id }}">
            </div>
        </div> -->
        
        
    <div class="form-group has-feedback-left">
        <label class="text-semibold col-lg-2 control-label">Self Rules</label>
        <div class="col-lg-4">
            <input id="self-rules" type="checkbox" name="selfrules" value='1'
                    @if($user->Selfrules == 1) checked @else @endif class="switch">
        </div>
        <div class="done2"><input name="donedone" type="hidden" value="0"></div>
        
        <label class="text-semibold col-lg-2 control-label">Group</label>
        <div id="divgruop" class="col-lg-4">
            <select class="select-fixed-singles" name="groupname" data-placeholder="Select an option">
                @foreach(App\Groups::where('as_system','0')->get() as $group)
                    <option @if($user->group_id == $group->id) selected @endif value='{{ $group->id }}'> {{ $group->name }} </option>
                @endforeach
            </select>
        </div>
    </div>


    <!-- </div> -->
    <!-- Accordion with right control button -->

    <div class="groupsss panel-group panel-group-control panel-group-control-right content-group-lg" @if($user->Selfrules == 1)  @else style="display: none;" @endif id="accordion">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group11"> Basic</a>
                </h6>
            </div>
            <div id="accordion-control-group11" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="pgrup form-group">
                        <label class="control-label col-lg-3">Radius Type</label>
                        <div class="col-lg-8">
                            <select class="select-fixed-singles" name="r_type">
                                <option @if($groups->raduis_type == "mikrotik") selected @endif value="mikrotik">Mikrotik</option>
                                <option @if($groups->raduis_type == "ddwrt") selected @endif value="ddwrt">DD-WRT</option>
                                <option @if($groups->raduis_type == "cisco") selected @endif value="cisco">CISCO</option>
                            </select>
                        </div>
                    </div>
                    <div class="pgrup form-group">
                        <label class="control-label col-lg-3" data-popup="tooltip"
                               title="Waiting time after device disconnected to remove user from active list to allow login again from another device with the same account."
                               data-placement="right">IDLE Timeout</label>
                        <div class="col-lg-4">
                            <div class="form-group has-feedback has-feedback-left timepicker">
                                <input type="text" class="form-control timepicker" name="idle_timeout"
                                       value="{{$groups->idle_timeout}}">
                                <div class="form-control-feedback">
                                    <i class="icon-alarm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pgrup form-group">
                        <label class="control-label col-lg-3">Notes</label>
                        <div class="col-lg-8">
                            <textarea name="notes" type="text" rows="3" class="form-control input-xlg">{{$groups->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group21">Login</a>
                </h6>
            </div>
            <div id="accordion-control-group21" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="pgrup form-group">
                        <label class="control-label col-lg-3" data-popup="tooltip-custom"
                               title="Allowed online devices simultaneously for each account"
                               data-placement="left">Concurrent sessions</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="u_multi_session" class="frequency form-control" type="number"
                                       value="{{$groups->port_limit}}" min="1">
                                <div class="form-control-feedback">
                                    <i class="icon-alarm"></i>
                                </div>
                            </div>
                        </div>
                        <label class="control-label col-lg-3" data-popup="tooltip"
                               title="0 : Unlimited " data-placement="left">Saved device
                                limit</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left timepicker">
                                <input name="limited_devices" class="frequency form-control" type="number"
                                       value="{{ $groups->limited_devices }}" min="0">
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
                    <!-- <div class="form-group col-lg-12"> // transferred to branches
                        <label class="control-label col-lg-3" data-popup="tooltip"
                               title="Auto save Mac-address after first login to remove landing page for future login's "
                               data-placement="right">Auto Login</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left auto_login2">
                                <input id="auto_login" type="checkbox" name="auto_login" value="1" class="switch"
                                       @if($groups->auto_login == 1) checked @endif >
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 auto-login-expiry2" @if($groups->auto_login == 1) @else style="display:none;" @endif>
                        <label class="control-label col-lg-3">Auto login Expiry(Days)</label>
                        <div class="col-lg-9">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="auto-login-expiry" class="frequency form-control"
                                       type="number"
                                       min="1" value="{{ $groups->auto_login_expiry }}">
                                <div class="form-control-feedback">
                                    <i class="icon-sun3"></i>
                                </div>
                                <span class="help-block">0 : Unlimited</span>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group31">Limitations</a>
                </h6>
            </div>
            <div id="accordion-control-group31" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="pgrup form-group">
                        <label class="control-label col-lg-3">Session Time</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control timepicker" name="session_time"
                                       value="{{$groups->session_time}}">
                                <div class="form-control-feedback">
                                    <i class="icon-alarm"></i>
                                </div>
                            </div>
                        </div>
                        <label class="control-label col-lg-3">Upload (MB)</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left">
                                <input class="frequency form-control" type="number" min="1" name="quota_limit_upload"
                                       value=@if(isset($groups->quota_limit_upload) and $groups->quota_limit_upload!=0) {{$groups->quota_limit_upload/1024/1024}} @endif >
                                <div class="form-control-feedback">
                                    <i class="icon-cloud-upload"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pgrup form-group">
                        <label class="control-label col-lg-3">Download (MB)</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left">
                                <input class="frequency form-control" type="number" min="1" name="quota_limit_download"
                                       value=@if(isset($groups->quota_limit_download) and $groups->quota_limit_download!=0) {{$groups->quota_limit_download/1024/1024}} @endif >
                                <div class="form-control-feedback">
                                    <i class="icon-cloud-download"></i>
                                </div>
                            </div>
                        </div>
                        <label class="control-label col-lg-3">Total (MB)</label>
                        <div class="col-lg-3">
                            <div class="form-group has-feedback has-feedback-left">
                                <input class="frequency form-control" type="number" min="1" name="quota_limit_total"
                                       value=@if(isset($groups->quota_limit_total) and $groups->quota_limit_total!=0) {{$groups->quota_limit_total/1024/1024}} @endif >
                                <div class="form-control-feedback">
                                    <i class="icon-cloud"></i>
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
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group41">Speed</a>
                </h6>
            </div>
            <div id="accordion-control-group41" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="pgrup form-group col-lg-12">
                        <div class="equationStartSpeed" @if($equationStartSpeed == "1") style="display: none;" @endif>
                            <label class="start_speed2 control-label col-lg-2">Start Speed</label>
                            <div class="start_speed2 col-lg-3">
                                <div class="input-group">
                                    <input name="speed_limit1" class="frequency form-control" type="text"
                                           value="{{ $groups->speed_limit[0] }}">
                                </div>
                            </div>
                            <div class="start_speed2 col-lg-2">
                                <div class="input-group">
                                    <select class="select-fixed-single75" name="stype1">
                                        <option @if($groups->speed_limit[1] == "K"){ selected } @endif value="K">Kilobyte</option>
                                        <option @if($groups->speed_limit[1] == "M"){ selected } @endif value="M">Megabyte</option>
                                    </select>
                                </div>
                            </div>

                            <div class="start_speed2 col-lg-3">
                                <div class="input-group">
                                    <input name="speed_limit2" class="frequency form-control" type="text"
                                           value="{{ $groups->speed_limit[2] }}">
                                </div>
                            </div>
                            <div class="start_speed2 col-lg-2">
                                <div class="input-group">
                                    <select class="select-fixed-single75" name="stype2">
                                        <option @if($groups->speed_limit[3] == "K"){ selected } @endif value="K">Kilobyte</option>
                                        <option @if($groups->speed_limit[3] == "M"){ selected } @endif value="M">Megabyte</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3"></label>
                            <div class="col-lg-8">
                                <div class="checkbox">
                                    <label>
                                        <input name="equationCheckOfStartSpeed" type="checkbox" class="styled" id="equationCheckOfStartSpeed"
                                               @if($equationStartSpeed == 1) checked @endif>
                                        Equation speed (advanced mode).
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="equation2 form-group col-lg-12 has-feedback-left"
                             @if($equationStartSpeed == 1) @else style="display: none;" @endif>
                            <label class="control-label col-lg-3">Start Speed</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="equationstart" class="frequency form-control" type="text"
                                           value="@if($equationStartSpeed == 1){{$groups->speed_limit}}@endif" placeholder="end - start - average - seconds - priority - minimum">
                                    <div class="form-control-feedback">
                                        <i class="icon-cloud"></i>
                                    </div>
                                    <span class="help-block"> 16k/256k 128k/2048k 10k/190k 30/30 8 128k/1024k</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pgrup form-group col-lg-12">
                        <label class="control-label col-lg-3" data-popup="tooltip"
                               title=" that applies if you set values in limit section, after user reach to any limit, speed will be downgraded till renewing date.
                                                        if you set off internet will be disconnected till renewing date."
                               data-placement="right">Downgrade Speed</label>
                        <div class="col-lg-3">
                            <input id="self-ruless2" type="checkbox" value="1" name="ifDownGradeSpeed" class="switch"
                                   @if($groups->if_downgrade_speed == "1") checked @endif>
                        </div>
                        <div class="done"><input name="values" type="hidden" value="0"></div>
                        
                        <div class="form-group allDownSpeed col-lg-12"  @if($groups->if_downgrade_speed != "1") style="display: none;" @endif >
                            <!-- normal -------------- -->
                            <div class="normalEquationEndSpeed" @if( ($groups->as_system=="1" and $groups->if_downgrade_speed != "1") or ($groups->as_system=="1" and $equationEndSpeed==1) ) style="display: none;" @endif>
                                <label class="equation_end control-label col-lg-2"></label>

                                <div class="equation_end endspeed col-lg-3">
                                    <div class=" input-group">
                                        <input name="end_speed1" class="endspeed frequency form-control" type="text" placeholder="Upload"
                                               @if($equationEndSpeed != 1 && $groups->end_speed[0]) value="{{$groups->end_speed[0]}}" @endif >
                                    </div>
                                </div>

                                <div class="equation_end endspeed col-lg-2">
                                    <div class="endspeed input-group">
                                        <select class="endspeed select-fixed-single75" name="etype1">
                                            <option @if($groups->end_speed[1] == "K"){ selected } @endif value="K">Kilobyte</option>
                                            <option @if($groups->end_speed[1] == "M"){ selected } @endif value="M">Megabyte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="equation_end endspeed col-lg-3">
                                    <div class="endspeedd input-group">
                                        <input name="end_speed2" class="endspeed frequency form-control" type="text" placeholder="Download"
                                               @if($equationEndSpeed != 1 && $groups->end_speed[2])  value="{{$groups->end_speed[2]}}" @endif >
                                    </div>
                                </div>

                                <div class=" equation_end endspeed col-lg-2">
                                    <div class="endspeed input-group">
                                        <select class="endspeed select-fixed-single75" name="etype2">
                                            <option @if($groups->end_speed[3] == "K") selected @endif value="K">Kilobyte</option>
                                            <option @if($groups->end_speed[3] == "M") selected @endif value="M">Megabyte</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- checkbox-------------- -->

                            <div class="equationCheckOfEndSpeed form-group col-lg-12">
                                <label class="control-label col-lg-3"></label>
                                <div class="aaaaa col-lg-8">
                                    <div class="checkbox">
                                        <label>
                                            <input name="equationCheckOfEndSpeed" type="checkbox" class="styled" id="equationCheckOfEndSpeed"
                                                   @if($equationEndSpeed == 1) checked @endif>
                                            Equation speed (advanced mode).
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- equation -------------- -->
                            <div class="forHideOnlyInCaseAdminOpendEndSpeedFirstTime"
                                 @if($equationEndSpeed != 1) style="display: none;" @endif >
                                <div class="equations2 form-group col-lg-12 has-feedback-left"
                                     @if($equationEndSpeed != 1) style="display: none;" @endif>
                                    <label class="control-label col-lg-3"></label>
                                    <div class="col-lg-9">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <input name="equationend" class="frequency form-control" type="text"
                                                   value="@if($equationEndSpeed == 1){{$groups->end_speed}}@endif" placeholder="end - start - average - seconds - priority - minimum">
                                            <div class="form-control-feedback">
                                                <i class="icon-cloud"></i>
                                            </div>
                                            <span class="help-block"> 24k/128k 32k/256k 24k/196k 30/30 8 16k/64k</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- -------------- -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group51">Advertising</a>
                </h6>
            </div>
            <div id="accordion-control-group51" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="pgrup form-group">
                        <label class="control-label col-lg-2" data-popup="tooltip"
                               title="stop internet till user open browser then auto redirected to your link "
                               data-placement="right">URL Redirect</label>
                        <div class="col-lg-8">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="url_redirect" type="text" class="form-control input-xlg"
                                       value="{{ $groups->url_redirect }}">
                                <div class="form-control-feedback">
                                    http://
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pgrup form-group"><label class="control-label col-lg-2" data-popup="tooltip"
                                                         title="Reopen advertising link each: " data-placement="right">URL Interval</label>
                        <div class="col-lg-4">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="url_redirect_Interval" type="text" class="form-control timepicker"
                                       value="{{ $groups->url_redirect_Interval }}">
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
                       href="#edit-accordion-control-group7">Website filtration</a>
                </h6>
            </div>
            <div id="edit-accordion-control-group7" class="panel-collapse collapse">
                <div class="panel-body">
                    <!-- Individual column searching (selects) -->
                    <div class="panel panel-flat">
                        <div class="panel-body">
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-3">
                                <input id="edit-filtration" type="checkbox" name="website-state"  class="switch" @if($groups->url_filter_state == 1) checked @endif>
                            </div>
                            <div class="col-lg-7 filtration-type" @if($groups->url_filter_state == 1)  @else style="display: none;" @endif>
                                <select class="select-fixed-single" name="website-type">
                                    <option @if($groups->url_filter_type == 1) selected @endif value="1">Block all the following websites</option>
                                    <option @if($groups->url_filter_type == 2) selected @endif value="2">Block all websites expect the following sites</option>
                                </select>
                            </div>
                            <div class="col-lg-2 filtration-type" @if($groups->url_filter_state == 1)  @else style="display: none;" @endif>
                                <button type="button" name="add" id="edit_load" class="btn btn-success"><i class="icon-plus2"></i></button>
                            </div>
                        </div>
                        <div class="table-responsive filtration-table" @if($groups->url_filter_state == 1) @else style="display: none;" @endif>
                            <table class="table table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic_date">
                                <thead> 
                                    <tr>
                                        <th>Website</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($url as $value)
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">http://</span>
                                                <input name="websitename[]" type="text" class="form-control input-xlg" value="{{ $value->url }}">
                                            </div>
                                        </td>
                                        

                                        <td>
                                            <button type="button" name="remove" class="btn btn-danger edit-btn_remove" onclick="_delete({{$value->id}}, {{$value->group_id}})" ><i class="icon-minus2"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /individual column searching (selects) -->
                </div>
            </div>
        </div>
    </div>
    <!-- /accordion with right control button -->
</form>
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>

<script>
    $('#edit_load').click(function () {
        var code = '<tbody> <tr id="row">\n';
                                                            
            code += '<td>\n';
                code += '<div class="input-group">';
                code += '<span class="input-group-addon">http://</span>';
                code += '<input name="websitename[]" type="text" class="form-control input-xlg" placeholder="Website name" required> \n';
                code += '</div>';
                code += '<span class="help-block"> Please enter "Website name" without .com or any other things, You can enter just word to block any URL have this word...</span>';
            code += '</td>\n';

            
            
            code += '<td>\n';
                code += '<button type="button" name="remove" class="btn btn-danger edit-btn_remove"><i class="icon-minus2"></i></button>\n';
            code += '</td>\n';
            
            code += '</tr> </tbody>';
        $('#edit-dynamic_date').append(code);
    });
    $(document).on('click', '.edit-btn_remove', function () {
        $(this).parent().parent().remove();
    });
    function _delete($id, $group_id){
        var that = this;

           swal({
             title: "Are you sure?",
             text: "You will not be able to data again!",
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
                    url:'website-filtration-delete/'+ $id +'/' + $group_id,
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

    $('#edit-filtration').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.filtration-table').show();
            $('.filtration-type').show();
        } else {
            $('.filtration-table').hide();
            $('.filtration-type').hide();                           
        }
    });


    $('.select-fixed-single75').select2({
        minimumResultsForSearch: Infinity,
        width: 75
    });
    $('.countries').select2({
        minimumInputLength: 1,
        minimumResultsForSearch: Infinity
    });
    $('.tokenfield').tokenfield();
    // Add class on init
    $('.tokenfield-primary').on('tokenfield:initialize', function (e) {
        $(this).parent().find('.token').addClass('bg-primary')
    });

    // Initialize plugin
    $('.tokenfield-primary').tokenfield();

    // Add class when token is created
    $('.tokenfield-primary').on('tokenfield:createdtoken', function (e) {
        $(e.relatedTarget).addClass('bg-primary')
    });

    // Bootstrap switch
    // ------------------------------
    $(".switch").bootstrapSwitch();
    $('#green').hide();
    if ('{{$checked}}' != 'checked') {
        $('#divgruop').show();
        $('.pgrup').hide();
    } else {
        $('#divgruop').hide();
        $('.pgrup').show();
    }
    $('#self-rules').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('#divgruop').hide();
            $('.pgrup').show();
            $('.groupsss').show();
        } else {
            $('#divgruop').show();
            $('.pgrup').hide();
            $('.groupsss').hide();
        }
    });
    $('#self-rules2').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.endspeed').show();
            $('.done').append('<input name="values" type="hidden" value="1">');

        } else {
            $('.endspeed').hide();
            $('.done').append('<input name="values" type="hidden" value="0">');
        }
    });
    $('#self-ruless').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('#divgruops').hide();
            $('.pgrup').show();
            $('.done2').append('<input name="donedone" type="hidden" value="0">');
        } else {
            $('#divgruops').show();
            $('.pgrup').hide();
            $('.done2').append('<input name="donedone" type="hidden" value="1">');
        }
    });
    $('#self-ruless2').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.allDownSpeed').show();
            $('.endspeed').show();
            $('.equationCheckOfEndSpeed').show();
            $('.equations2').show();
            
            

        } else {
            $('.allDownSpeed').hide();
            $('.endspeed').hide();
            $('.equationCheckOfEndSpeed').hide();
            $('.equations2').hide();
            
        }
    });
    // Checkboxes
    $(".styled").uniform({
        radioClass: 'choice'
    });
    $('#equationCheckOfStartSpeed').on('click', function () {
        if ($(this).is(':checked')) {
            $('.equation2').show();
            $('.start_speed2').hide();
            $('.equationStartSpeed').hide();
        } else {
            $('.equation2').hide();
            $('.start_speed2').show();
            $('.equationStartSpeed').show();
        }
    });
    $('#equationCheckOfEndSpeed').on('click', function () {
        if ($(this).is(':checked')) {

            $('.forHideOnlyInCaseAdminOpendEndSpeedFirstTime').show();
            $('.equation_end').hide();
            $('.equations2').show();
            $('.normalEquationEndSpeed').hide();
        } else {
            $('.equation_end').show();
            $('.equations2').hide();
            $('.normalEquationEndSpeed').show();
        }
    });
    $('.auto_login2').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.auto-login-expiry2').show();
        } else {
            $('.auto-login-expiry2').hide();
        }
    });
    $('.select-fixed-singles').select2({
        minimumResultsForSearch: Infinity,
        width: 150
    });
    $('.select-fixed-single').select2({
        minimumResultsForSearch: Infinity
    });
    $('.select-fixed-ss').select2({
        minimumResultsForSearch: Infinity,
        width: 75
    });
    $('#select_problem').select2({}).on("change", function (e) {
        $('#group_problem').remove();
    })

    // $('.gruop-edit').select2();

    $('.network-edit').select2({
        minimumResultsForSearch: Infinity,
        width: 150
    }).on("change", function (e) {
        get_group()
        $('#group_problem').hide();
    })
    get_group()

    $('.timepicker').timepicki({
        show_meridian: false,
        min_hour_value: 0,
        max_hour_value: 23,
        overflow_minutes: true,
        increase_direction: 'up',
        disable_keyboard_mobile: true
    });
</script>
