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
<h6 class="text-semibold"></h6>

<div class="row">
    <form action="{{ url('edit_group/'.$group->id) }}" method="POST" id="edit" class="form-horizontal">

        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <!-- Accordion with left control button -->

        <div class="panel-group panel-group-control content-group-lg" id="accordion-control">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group11"> Basic</a>
                    </h6>
                </div>
                <div id="accordion-control-group11" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Group Name</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="name" type="text" class="form-control input-xlg" value="{{ $group->name}}">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Group state</label>
                            <div class="col-lg-8">
                                <select class="select-fixed-single" name="state" value="{{ $group->state }}">
                                    <option @if($group->is_active == 1) selected @endif value="1">Active</option>
                                    <option @if($group->is_active == 0) selected @endif value="0">Inactive</option>
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

                        <!--
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3" >Radius Type</label>
                            <div class="col-lg-8">
                                <select class="select-fixed-single" name="r_type">
                                    <option @if($group->radius_type=="mikrotik") selected @endif value="mikrotik">Mikrotik</option>
                                    <option @if($group->radius_type=="ddwrt") selected @endif value="ddwrt">DD-WRT</option>
                                    <option @if($group->radius_type=="cisco") selected @endif value="cisco">CISCO</option>
                                </select>
                            </div>
                        </div>
                        -->

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3" data-popup="tooltip"
                                   title="Waiting time after device disconnected to remove user from active list to allow login again from another device with the same account."
                                   data-placement="right">IDLE Timeout</label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="idle_timeout" type="text" class="form-control timepicker"
                                           value="{{ $group->idle_timeout }}">
                                    <div class="form-control-feedback">
                                        <i class="icon-alarm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Notes</label>
                            <div class="col-lg-9">
                                <textarea name="notes" type="text" rows="3" class="form-control input-xlg">{{$group->notes }}</textarea>
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
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3" data-popup="tooltip"
                                   title="Allowed online devices simultaneously for each account"
                                   data-placement="right">Concurrent sessions</label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="u_multi_session" class="frequency form-control" type="number"
                                           value="{{ $group->port_limit }}" min="1">
                                    <div class="form-control-feedback">
                                        <i class="icon-users"></i>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label col-lg-3" data-popup="tooltip"
                                                                         data-original-title="0: Unlimited">Saved
                                    device
                                    limit</abbr> </label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="limited_devices" class="frequency form-control" type="number"
                                           value="{{ $group->limited_devices }}" min="0">
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
                                <div class="form-group has-feedback has-feedback-left auto_login2">
                                    <input id="auto_login" type="checkbox" name="auto_login" value="1" class="switch"
                                           @if($group->auto_login == 1) checked @endif >
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12 auto-login-expiry2" @if($group->auto_login == 1) @else style="display:none;" @endif>
                            <label class="control-label col-lg-3">Auto login Expiry(Days)</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="auto-login-expiry" class="frequency form-control"
                                           type="number"
                                           min="1" value="{{ $group->auto_login_expiry }}">
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
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group_expire_users_after_days-edit">Ticket expiration</a>
                    </h6>
                </div>
                <div id="accordion-control-group_expire_users_after_days-edit" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3" data-popup="tooltip"
                                   title="Allowed online devices simultaneously for each account"
                                   data-placement="right">Auto expire after</label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="expire_users_after_days" class="frequency form-control" type="number"
                                           value="{{ $group->expire_users_after_days }}" min="0">
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
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group31">Limitations</a>
                    </h6>
                </div>
                <div id="accordion-control-group31" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Upload (MB)</label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="quota_limit_upload" class="frequency form-control" type="number" min="1"
                                           value="{{ $group->quota_limit_upload /1024/1024 }}">
                                    <div class="form-control-feedback">
                                        <i class="icon-cloud-upload"></i>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label col-lg-3">Download (MB)</label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="quota_limit_download" class="frequency form-control" type="number" min="1"
                                           value="{{$group->quota_limit_download /1024/1024 }}">
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
                                    <input name="quota_limit_total" class="frequency form-control" type="number" min="1"
                                           value="{{ $group->quota_limit_total /1024/1024 }}">
                                    <div class="form-control-feedback">
                                        <i class="icon-cloud"></i>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label col-lg-3">Session Time</label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="session_time" type="text" class="form-control timepicker"
                                           value="{{ $group->session_time }}">
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
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group41">Speed</a>
                    </h6>
                </div>
                <div id="accordion-control-group41" class="panel-collapse collapse">
                    <div class="panel-body">
                        <!-- /////////////////////////////////  Start speed ////////////////////////////// -->
                        <div class="alert alert-info alert-styled-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span><span class="sr-only">Close</span></button>
                            <center><span class="text-semibold">Start speed</span> before consuming limitations</a>.</center>
                            <center>Note: Browsing mode speeds must be <span class="text-semibold"> greater </span> than Download mode.</a></center>
                        </div>

                        <?php
                        
                        if($equationStartSpeed == "1"){
                            // check if equation speed or not
                            $firstExplode=explode(" ",$group->speed_limit);
                            
                            //XXXXXXXXXXXXX Browseing speed XXXXXXXXXXXXXXXXXX
                            $secondExplode=explode(" ",$firstExplode[1]);

                            $equFirstEX=explode("/",$secondExplode[0]);
                            //upload
                            $equUPcheck=explode("K",$equFirstEX[0]);
                            if(isset($equUPcheck[1])){
                                //so this is upload speed in KB
                                $finalBrowseModeUploadType="K";
                                $finalBrowseModeUploadSpeed=$equUPcheck[0];
                            }else{
                                //so this is upload speed in MB
                                $finalBrowseModeUploadType="M";
                                $equStepMegaByteUpload=explode("M",$equFirstEX[0]);
                                $finalBrowseModeUploadSpeed=$equStepMegaByteUpload[0];
                            }
                            
                            // download
                            $equDowncheck=explode("K",$equFirstEX[1]);
                            if(isset($equDowncheck[1])){
                                //so this is Download speed in KB
                                $finalBrowseModeDownloadType="K";
                                $finalBrowseModeDownloadSpeed=$equDowncheck[0];
                            }else{
                                //so this is Download speed in MB
                                $finalBrowseModeDownloadType="M";
                                $equStepMegaByteDownload=explode("M",$equFirstEX[1]);
                                $finalBrowseModeDownloadSpeed=$equStepMegaByteDownload[0];
                            }

                            //XXXXXXXXXXXXX Download speed XXXXXXXXXXXXXXXXXX
                            $thirdExplode=explode(" ",$firstExplode[0]);

                            $downModeEquFirstEX=explode("/",$thirdExplode[0]);
                            //upload
                            $downModeEquUPcheck=explode("K",$downModeEquFirstEX[0]);
                            if(isset($downModeEquUPcheck[1])){
                                //so this is upload speed in KB
                                $finalDownModeUploadType="K";
                                $finalDownModeUploadSpeed=$downModeEquUPcheck[0];
                            }else{
                                //so this is upload speed in MB
                                $finalDownModeUploadType="M";
                                $equStepMegaByteUpload=explode("M",$downModeEquFirstEX[0]);
                                $finalDownModeUploadSpeed=$equStepMegaByteUpload[0];
                            }
                            
                            // download
                            $downModeEquDowncheck=explode("K",$downModeEquFirstEX[1]);
                            if(isset($downModeEquDowncheck[1])){
                                //so this is Download speed in KB
                                $finalDownModeDownloadType="K";
                                $finalDownModeDownloadSpeed=$downModeEquDowncheck[0];
                            }else{
                                //so this is Download speed in MB
                                $finalDownModeDownloadType="M";
                                $equStepMegaByteDownload=explode("M",$downModeEquFirstEX[1]);
                                $finalDownModeDownloadSpeed=$equStepMegaByteDownload[0];
                            }
                            //XXXXXXXXXXXXX priority XXXXXXXXXXXXXXXXXX
                            if(isset($firstExplode[4])){
                                $fourthExplode=explode(" ",$firstExplode[4]);
                                $startPriority=$fourthExplode[0];
                            }else{
                                $startPriority=8;
                            }
                            //XXXXXXXXXXXXX Minimum Speed XXXXXXXXXXXXXXXXXX
                            if(isset($firstExplode[5])){
                                $FifthExplode=explode(" ",$firstExplode[5]);

                                $startMinSpeedEquFirstEX=explode("/",$FifthExplode[0]);
                                //upload
                                $startMinSpeedEquUPcheck=explode("K",$startMinSpeedEquFirstEX[0]);
                                if(isset($startMinSpeedEquUPcheck[1])){
                                    //so this is upload speed in KB
                                    $finalStartMinSpeedUploadType="K";
                                    $finalStartMinSpeedUploadSpeed=$startMinSpeedEquUPcheck[0];
                                }else{
                                    //so this is upload speed in MB
                                    $finalStartMinSpeedUploadType="M";
                                    $equStepMegaByteUpload=explode("M",$startMinSpeedEquFirstEX[0]);
                                    $finalStartMinSpeedUploadSpeed=$equStepMegaByteUpload[0];
                                }
                                
                                // download
                                $finalStartMinSpeedEquDowncheck=explode("K",$startMinSpeedEquFirstEX[1]);
                                if(isset($finalStartMinSpeedEquDowncheck[1])){
                                    //so this is Download speed in KB
                                    $finalStartMinSpeedDownloadType="K";
                                    $finalStartMinSpeedDownloadSpeed=$finalStartMinSpeedEquDowncheck[0];
                                }else{
                                    //so this is Download speed in MB
                                    $finalStartMinSpeedDownloadType="M";
                                    $equStepMegaByteDownload=explode("M",$startMinSpeedEquFirstEX[1]);
                                    $finalStartMinSpeedDownloadSpeed=$equStepMegaByteDownload[0];
                                }
                            }else{
                                $finalStartMinSpeedUploadType="";
                                $finalStartMinSpeedUploadSpeed="";
                                $finalStartMinSpeedDownloadType="";
                                $finalStartMinSpeedDownloadSpeed="";
                            }
                        }else{
                            $finalBrowseModeUploadSpeed="";
                            $finalBrowseModeUploadType="";
                            $finalBrowseModeDownloadSpeed="";
                            $finalBrowseModeDownloadType="";

                            $finalDownModeUploadSpeed="";
                            $finalDownModeUploadType="";
                            $finalDownModeDownloadSpeed="";
                            $finalDownModeDownloadType="";

                        }
                        ?>

                        <!-- <div class="start_speed2 form-group col-lg-12" @if($equationStartSpeed == "1") style="display: none;" @endif> -->
                        <div class="start_speed2 form-group col-lg-12" @if($equationStartSpeed == "1")  @endif>
                            @if($foundDDWRT==1)
                                <label class="control-label col-lg-2">Speed</label>
                            @else
                                <label class="control-label col-lg-2">Browsing Mode</label>
                            @endif
                            
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <input title="Upload Speed" name="speed_limit1" class="frequency form-control" type="text" min="1" placeholder="Upload"
                                           value="{{ $finalBrowseModeUploadSpeed }}"
                                           onkeypress="return isNumber(event)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <select class="select-fixed-single75" name="stype1"
                                            value="@if(isset($group->speed_limit[1]) && $equationStartSpeed != "1") {{ $group->speed_limit[1] }}@endif">
                                        <option @if($finalBrowseModeUploadType == "K") selected @endif value="K">KB</option>
                                        <option @if($finalBrowseModeUploadType == "M") selected @endif value="M">MB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="input-group">
                                    <input title="Download Speed" name="speed_limit2" class="frequency form-control" type="text" min="1" placeholder="Download"
                                           value="{{ $finalBrowseModeDownloadSpeed }}"
                                           onkeypress="return isNumber(event)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <select class="select-fixed-single75" name="stype2"
                                            value="@if( isset($group->speed_limit[3]) && $equationStartSpeed != "1") {{ $group->speed_limit[3] }}@endif">
                                        <option @if($finalBrowseModeDownloadType == "K") selected @endif value="K">KB</option>
                                        <option @if($finalBrowseModeDownloadType == "M") selected @endif value="M">MB</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if($foundDDWRT==0)
                            <div class="start_speed2 form-group col-lg-12">
                                <!-- Download Mode -->
                                <label class="control-label col-lg-2">Download Mode</label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input title="Upload Speed" name="downSpeed_limit1" class="frequency form-control" type="text" min="1" placeholder="Upload"
                                            value="{{ $finalDownModeUploadSpeed }}"
                                            onkeypress="return isNumber(event)">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <select class="select-fixed-single75" name="downSpeedType1">
                                            <option @if($finalDownModeUploadType == "K") selected @endif value="K">KB</option>
                                            <option @if($finalDownModeUploadType == "M") selected @endif value="M">MB</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input title="Download Speed" name="downSpeed_limit2" class="frequency form-control" type="text" min="1" placeholder="Download"
                                            value="{{ $finalDownModeDownloadSpeed }}"
                                            onkeypress="return isNumber(event)">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <select class="select-fixed-single75" name="downSpeedType2">
                                            <option @if($finalDownModeDownloadType == "K") selected @endif value="K">KB</option>
                                            <option @if($finalDownModeDownloadType == "M") selected @endif value="M">MB</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- - -->
                            </div>

                            <!--  ////////////////////////////// -->
                            
                            @if($foundMikrotik==1 and $foundMikrotikV5==0)
                                <?php /*
                                <!-- <div class="start_speed2 form-group col-lg-12">
                                    <label class="control-label col-lg-2"> Minimum speed </label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input title="Upload Speed" name="startMinSpeed1" class="frequency form-control" type="text" min="1" placeholder="Upload"
                                                value="{{ $finalStartMinSpeedUploadSpeed }}"
                                                onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <select class="select-fixed-single75" name="startMinType1">
                                                <option @if($finalStartMinSpeedUploadType == "K") selected @endif value="K">KB</option>
                                                <option @if($finalStartMinSpeedUploadType == "M") selected @endif value="M">MB</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input title="Download Speed" name="startMinSpeed2" class="frequency form-control" type="text" min="1" placeholder="Download"
                                                value="{{ $finalStartMinSpeedDownloadSpeed }}"
                                                onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <select class="select-fixed-single75" name="startMinType2">
                                                <option @if($finalStartMinSpeedDownloadType == "K") selected @endif value="K">KB</option>
                                                <option @if($finalStartMinSpeedDownloadType == "M") selected @endif value="M">MB</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <span class="">Note : To guarantee Minimum speed must be smaller or equal download speed.</span>
                                    </div> 
                                </div> -->
                                */ ?>
                                <div class="start_speed2 form-group col-lg-12">
                                    <label class="control-label col-lg-2"> Priority </label>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <select class="select-fixed-single75" name="startPriority">
                                                <option @if(isset($startPriority) and $startPriority == "1") selected @endif value="1"> 1 </option>
                                                <option @if(isset($startPriority) and $startPriority == "2") selected @endif value="2"> 2 </option>
                                                <option @if(isset($startPriority) and $startPriority == "3") selected @endif value="3"> 3 </option>
                                                <option @if(isset($startPriority) and $startPriority == "4") selected @endif value="4"> 4 </option>
                                                <option @if(isset($startPriority) and $startPriority == "5") selected @endif value="5"> 5 </option>
                                                <option @if(isset($startPriority) and $startPriority == "6") selected @endif value="6"> 6 </option>
                                                <option @if(isset($startPriority) and $startPriority == "7") selected @endif value="7"> 7 </option>
                                                <option @if(isset($startPriority) and $startPriority == "8") selected @endif value="8"> 8 </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <span class="">1 : Maximum <br>8 : Minimum</span>
                                    </div>  
                                </div>

                            @endif
                            
                            <!--  ////////////////////////////// -->

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-3"></label>
                                <div class="col-lg-8">
                                    <div class="checkbox">
                                        <label>
                                            <input name="equationchecks2" type="checkbox" class="styled" id="equationchecks2">
                                            Equation speed (advanced mode).
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="equation2 form-group col-lg-12 has-feedback-left" style="display: none;">
                                <label class="control-label col-lg-3">Start Speed</label>
                                <div class="col-lg-9">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input name="equationstart" class="frequency form-control" type="text"
                                            value="@if($equationStartSpeed == 1){{$group->speed_limit}}@endif" placeholder="end - start - average - seconds - priority - minimum">
                                        <div class="form-control-feedback">
                                            <i class="icon-cloud"></i>
                                        </div>
                                        <span class="help-block"> 16k/256k 128k/2048k 10k/190k 30/30 8 128k/1024k</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- /////////////////////////////////  End speed ////////////////////////////// -->
                        <?php
                        if($equationEndSpeed == "1"){
                            // check if equation speed or not
                            $firstExplode=explode(" ",$group->end_speed);
                            
                            //XXXXXXXXXXXXX Browseing speed XXXXXXXXXXXXXXXXXX
                            $secondExplode=explode(" ",$firstExplode[1]);

                            $equFirstEX=explode("/",$secondExplode[0]);
                            //upload
                            $equUPcheck=explode("K",$equFirstEX[0]);
                            if(isset($equUPcheck[1])){
                                //so this is upload speed in KB
                                $finalEndBrowseModeUploadType="K";
                                $finalEndBrowseModeUploadSpeed=$equUPcheck[0];
                            }else{
                                //so this is upload speed in MB
                                $finalEndBrowseModeUploadType="M";
                                $equStepMegaByteUpload=explode("M",$equFirstEX[0]);
                                $finalEndBrowseModeUploadSpeed=$equStepMegaByteUpload[0];
                            }
                            
                            // download
                            $equDowncheck=explode("K",$equFirstEX[1]);
                            if(isset($equDowncheck[1])){
                                //so this is Download speed in KB
                                $finalEndBrowseModeDownloadType="K";
                                $finalEndBrowseModeDownloadSpeed=$equDowncheck[0];
                            }else{
                                //so this is Download speed in MB
                                $finalEndBrowseModeDownloadType="M";
                                $equStepMegaByteDownload=explode("M",$equFirstEX[1]);
                                $finalEndBrowseModeDownloadSpeed=$equStepMegaByteDownload[0];
                            }

                            //XXXXXXXXXXXXX Download speed XXXXXXXXXXXXXXXXXX
                            $thirdExplode=explode(" ",$firstExplode[0]);

                            $downModeEquFirstEX=explode("/",$thirdExplode[0]);
                            //upload
                            $downModeEquUPcheck=explode("K",$downModeEquFirstEX[0]);
                            if(isset($downModeEquUPcheck[1])){
                                //so this is upload speed in KB
                                $finalEndDownModeUploadType="K";
                                $finalEndDownModeUploadSpeed=$downModeEquUPcheck[0];
                            }else{
                                //so this is upload speed in MB
                                $finalEndDownModeUploadType="M";
                                $equStepMegaByteUpload=explode("M",$downModeEquFirstEX[0]);
                                $finalEndDownModeUploadSpeed=$equStepMegaByteUpload[0];
                            }
                            
                            // download
                            $downModeEquDowncheck=explode("K",$downModeEquFirstEX[1]);
                            if(isset($downModeEquDowncheck[1])){
                                //so this is Download speed in KB
                                $finalEndDownModeDownloadType="K";
                                $finalEndDownModeDownloadSpeed=$downModeEquDowncheck[0];
                            }else{
                                //so this is Download speed in MB
                                $finalEndDownModeDownloadType="M";
                                $equStepMegaByteDownload=explode("M",$downModeEquFirstEX[1]);
                                $finalEndDownModeDownloadSpeed=$equStepMegaByteDownload[0];
                            }

                        }else{

                            $finalEndBrowseModeUploadSpeed="";
                            $finalEndBrowseModeUploadType="";
                            $finalEndBrowseModeDownloadSpeed="";
                            $finalEndBrowseModeDownloadType="";

                            $finalEndDownModeUploadSpeed="";
                            $finalEndDownModeUploadType="";
                            $finalEndDownModeDownloadSpeed="";
                            $finalEndDownModeDownloadType="";
                        }

                        //XXXXXXXXXXXXX priority XXXXXXXXXXXXXXXXXX
                        if(isset($firstExplode[4])){
                            $fourthExplode=explode(" ",$firstExplode[4]);
                            $endPriority=$fourthExplode[0];
                        }else{
                            $endPriority=8;
                        }
                        ?>

                        <div class="form-group col-lg-12">
                            
                            <label class="control-label col-lg-3" data-popup="tooltip"
                                   title=" that applies if you set values in limit section, after user reach to any limit, speed will be downgraded till renewing date.
                                                        if you set off internet will be disconnected till renewing date."
                                   data-placement="right">Downgrade Speed</label>
                            <div class="col-lg-3">
                                <input id="self-rules22" type="checkbox" value="1" @if($group->if_downgrade_speed == "1" ) checked @endif name="ifDownGradeSpeed" class="switch">
                            </div>
                            
                            <span class="" @if($group->if_downgrade_speed == "1" ) style="display: none;" @endif > Note: If downgrade speed off, internet will stop after users exceed limitations.</span>

                            <div class="donea"></div>
                            <br><br>
                            <div class="form-group col-lg-12 allendspeed" @if($group->if_downgrade_speed != "1" ) style="display: none;" @endif>
                            
                                <div class="endspeedd alert alert-info alert-styled-left alert-bordered"  >
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span><span class="sr-only">Close</span></button>
                                    <center><span class="text-semibold">End speed</span> after consuming limitations</a>.</center>
                                    <center>Note: Browsing mode speeds must be <span class="text-semibold"> greater </span> than Download mode.</a></center>
                                </div>      

                                <!--  //////////// Browsing Mode ////////////////// -->
                                @if($foundDDWRT==1)
                                    <label class="endspeedd control-label col-lg-2"  > Speed</label>
                                @else
                                    <label class="endspeedd control-label col-lg-2"  > Browsing Mode</label>
                                @endif                                    
                                <div class="endspeedd col-lg-3" >
                                    <div class="endspeedd input-group">
                                        <input title="Upload Speed" name="end_speed1" class="endspeedd frequency form-control" type="text" placeholder="Upload"
                                               value="{{ $finalEndBrowseModeUploadSpeed }}"
                                               onkeypress="return isNumber(event)">
                                    </div>
                                </div>

                                <div class="endspeedd col-lg-2" >
                                    <div class="endspeedd input-group">
                                        <select class="endspeedd select-fixed-single75" name="etype1">
                                            <option @if($finalEndBrowseModeUploadType == "K") selected @endif value="K">KB</option>
                                            <option @if($finalEndBrowseModeUploadType == "M") selected @endif value="M">MB</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="endspeedd col-lg-3" >
                                    <div class="endspeedd input-group">
                                        <input title="Download Speed" name="end_speed2" class="endspeedd frequency form-control" type="text" placeholder="Download"
                                               value="{{ $finalEndBrowseModeDownloadSpeed }}"
                                               onkeypress="return isNumber(event)">
                                    </div>
                                </div>

                                <div class="endspeedd col-lg-2" >
                                    <div class="endspeedd input-group">
                                        <select class="endspeedd select-fixed-single75" name="etype2">
                                            <option @if($finalEndBrowseModeDownloadType == "K")  selected @endif  value="K">KB</option>
                                            <option @if($finalEndBrowseModeDownloadType == "M") selected @endif  value="M">MB</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            
                            <!--  //////////// Download Mode ////////////////// -->
                            @if($foundDDWRT==0)
                                <div class="form-group col-lg-12 allendspeed" @if($group->if_downgrade_speed != "1" ) style="display: none;" @endif>
                                    <label class="endspeedd control-label col-lg-2"> Download Mode</label>
                                    <div class="endspeedd col-lg-3" >
                                        <div class="endspeedd input-group">
                                            <input title="Upload Speed" name="endDownSpeed_limit1" class="endspeedd frequency form-control" type="text" placeholder="Upload"
                                                value="{{ $finalEndDownModeUploadSpeed }}"
                                                onkeypress="return isNumber(event)">
                                        </div>
                                    </div>

                                    <div class="endspeedd col-lg-2">
                                        <div class="endspeedd input-group">
                                            <select class="endspeedd select-fixed-single75" name="endDownSpeedType1">
                                                <option @if($finalEndDownModeUploadType == "K") selected @endif value="K">KB</option>
                                                <option @if($finalEndDownModeUploadType == "M") selected @endif value="M">MB</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="endspeedd col-lg-3">
                                        <div class="endspeedd input-group">
                                            <input title="Download Speed" name="endDownSpeed_limit2" class="endspeedd frequency form-control" type="text" placeholder="Download"
                                                value="{{ $finalEndDownModeDownloadSpeed }}"
                                                onkeypress="return isNumber(event)">
                                        </div>
                                    </div>

                                    <div class="endspeedd col-lg-2">
                                        <div class="endspeedd input-group">
                                            <select class="endspeedd select-fixed-single75" name="endDownSpeedType2">
                                                <option @if($finalEndDownModeDownloadType == "K")  selected @endif  value="K">KB</option>
                                                <option @if($finalEndDownModeDownloadType == "M") selected @endif  value="M">MB</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--  ///////////// priority ///////////////// -->
                                
                                @if($foundMikrotik==1 and $foundMikrotikV5==0)
                                <div class="endspeedd form-group col-lg-12 allendspeed" @if($group->if_downgrade_speed != "1" ) style="display: none;" @endif>
                                    <label class="endspeedd control-label col-lg-2"> Priority </label>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <select class="endspeedd select-fixed-single75" name="endPriority">
                                                <option @if($endPriority == "1") selected @endif value="1"> 1 </option>
                                                <option @if($endPriority == "2") selected @endif value="2"> 2 </option>
                                                <option @if($endPriority == "3") selected @endif value="3"> 3 </option>
                                                <option @if($endPriority == "4") selected @endif value="4"> 4 </option>
                                                <option @if($endPriority == "5") selected @endif value="5"> 5 </option>
                                                <option @if($endPriority == "6") selected @endif value="6"> 6 </option>
                                                <option @if($endPriority == "7") selected @endif value="7"> 7 </option>
                                                <option @if($endPriority == "8") selected @endif value="8"> 8 </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <span class="">1 : Maximum <br>8 : Minimum</span>
                                    </div>  
                                </div>
                                @endif
                                <!--  ////////////////////////////// -->

                                <div class="form-group col-lg-12 allendspeed" @if($group->if_downgrade_speed != "1" ) style="display: none;" @endif>
                                    <label class="control-label col-lg-3"></label>
                                    <div class="aaaaa col-lg-8">
                                        <div class="checkbox">
                                            <label>
                                                <input name="equationcheckss2" type="checkbox" class="styled" id="equationcheckss2">
                                                Equation speed (advanced mode).
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="forHideOnlyInCaseAdminOpendEndSpeedFirstTime"
                                     style="display: none;" >
                                    <div class="equation_end endspeedd form-group col-lg-12 has-feedback-left" style="display: none;">
                                        <label class="control-label col-lg-3"></label>
                                        <div class="col-lg-9">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="equationend" class="frequency form-control" type="text"
                                                       value="@if($equationEndSpeed == 1){{$group->end_speed}}@endif" placeholder="end - start - average - seconds - priority - minimum">
                                                <div class="form-control-feedback">
                                                    <i class="icon-cloud"></i>
                                                </div>
                                                <span class="help-block"> 24k/128k 32k/256k 24k/196k 30/30 8 16k/64k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                        </div>

                        <!--  ////////////////////////////// --> <!--  ////////////////////////////// -->
                        <!--  ////////////////////////////// -->
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
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3" data-popup="tooltip"
                                   title="stop internet till user open browser then auto redirected to your link "
                                   data-placement="right">URL Redirect</label>
                            <div class="col-lg-6">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="url_redirect" type="text" class="form-control input-xlg"
                                           value="{{$group->url_redirect }}">
                                    <div class="form-control-feedback">
                                        <i class="icon-earth"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($foundDDWRT==0)
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3" data-popup="tooltip"
                                   title="Reopen advertising link each: " data-placement="right">URL Redirect Interval</label>
                            <div class="col-lg-6">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="url_redirect_Interval" type="text" class="form-control timepicker"
                                           value="{{ $group->url_redirect_Interval }}">
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
                                    <input id="edit-filtration" type="checkbox" name="website-state"  class="switch" @if($group->url_filter_state == 1) checked @endif>
                                </div>
                                <div class="col-lg-7 filtration-type" @if($group->url_filter_state == 1)  @else style="display: none;" @endif>
                                    <select class="select-fixed-singles" name="website-type">
                                        <option @if($group->url_filter_type == 1) selected @endif value="1">Block all the following websites</option>
                                        <option @if($group->url_filter_type == 2) selected @endif value="2">Block all websites expect the following sites</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 filtration-type" @if($group->url_filter_state == 1)  @else style="display: none;" @endif>
                                    <button type="button" name="add" id="edit_load" class="btn btn-success"><i class="icon-plus2"></i></button>
                                </div>
                            </div>
                            <div class="table-responsive filtration-table"  @if($group->url_filter_state == 1) @else style="display: none;" @endif > 
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
            @endif

        </div>
        <!-- /accordion with left control button -->
    </form>
</div>
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

    $(".switch").bootstrapSwitch();
    $('#self-rules22').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {

            $('.allendspeed').show();
            $('.endspeedd').show();
            $('.donea').append('<input name="abc" type="hidden" value="1">');
            $('.equation_end').show();
        } else {
            $('.allendspeed').hide();
            $('.endspeedd').hide();
            $('.donea').append('<input name="abc" type="hidden" value="0">');
            $('.equation_end').hide();
        }
    });
    // Checkboxes
    $(".styled").uniform({
        radioClass: 'choice'
    });
    $('#equationchecks2').on('click', function () {
        if ($(this).is(':checked')) {
            $('.equation2').show();
            $('.start_speed2').hide();
        } else {
            $('.equation2').hide();
            $('.start_speed2').show();
        }
    });
    $('#equationcheckss2').on('click', function () {
        if ($(this).is(':checked')) {

            $('.forHideOnlyInCaseAdminOpendEndSpeedFirstTime').show();
            $('.endspeedd').hide();
            $('.equation_end').show();
        } else {

            $('.endspeedd').show();
            $('.equation_end').hide();

        }
    });
    $('.auto_login2').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.auto-login-expiry2').show();
        } else {
            $('.auto-login-expiry2').hide();
        }
    });
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    $('.select-fixed-single').select2({
        minimumResultsForSearch: Infinity,
        width: 250
    });
    $('.select-fixed-singles').select2({
        minimumResultsForSearch: Infinity
    });
    $('.select-fixed-single75').select2({
        minimumResultsForSearch: Infinity,
        width: 75
    });
    $('.timepicker').timepicki({
        show_meridian: false,
        min_hour_value: 0,
        max_hour_value: 23,
        overflow_minutes: true,
        increase_direction: 'up',
        disable_keyboard_mobile: true
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>