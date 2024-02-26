<?php  $campaign_type = App\Models\Campaigns::where('id', $id)->value('type');
$old_date_timestamp = strtotime($campaigns->startdate);
$new_date = date('d F, Y', $old_date_timestamp);
?>

@if(App\Models\CampaignsStatisticsMonths::where('campaign_id',$id)->count() != 0)
<!-- Daily sales -->
<div class="panel panel-flat">
    <div class="panel-heading">
        <h6 class="panel-title">statistics</h6>
    </div>

    <div class="panel-body">
        <div class="chart-container">
            <div class="chart has-fixed-height" id="basic_area"></div>
        </div>
    </div>
</div>
<!-- /daily sales -->
@else

@endif
@if($campaign_type == "website")

    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab1">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true ,'method' => 'post', 'id' => 'edit-website')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="website">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                    </div>
                    <div class="form-control-feedback">
                        <i class="icon-pencil6"></i>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                <div class="col-lg-2">
                    <div class="checkbox option-skip-edit">
                        <label>
                            <input name="open-profile" type="checkbox"
                                   class="control-primary" {{ $campaigns->open_profile == '1' ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
                <label class="control-label col-lg-2 option-skip-value-edit" data-popup="tooltip" title="Waiting time to skip Ad" @if($campaigns->open_profile == 1) @else style="display: none" @endif >Time delay: (seconds)</label>
                <div class="col-lg-4 option-skip-value-edit" @if($campaigns->open_profile == 1) @else style="display: none" @endif>

                    <div class="form-group has-feedback has-feedback-left">
                        <input name="time-delay" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->delay }}">
                        <div class="form-control-feedback">
                            <i class="icon-watch"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Upload background picture:</label>
                <div class="col-lg-8">
                    <input name="file[]" type="file" class="file-input" multiple="multiple">
                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Click-through URL:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="url" type="text" class="form-control input-xlg" value="{{ $campaigns->url }}">
                        <div class="form-control-feedback">
                            <i class="icon-hyperlink"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3"  data-popup="tooltip" title="Appear on the top of ad button to motivate users to click on button and open your ad.">Motivational message:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="message" type="text"
                                  class="form-control input-xlg">{{ $campaigns->text }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-website2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-website2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-website2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-website2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-website2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-website2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-website2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-website2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-website2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-website'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "video")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab2">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-video')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="video">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                <div class="col-lg-2">
                    <div class="checkbox option-skip-edit">
                        <label>
                            <input name="open-profile" type="checkbox"
                                   class="control-primary" {{ $campaigns->open_profile == '1' ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
                <label class="control-label col-lg-2 option-skip-value-edit" data-popup="tooltip" title="Waiting time to skip Ad" @if($campaigns->open_profile == 1) @else style="display: none" @endif>Time delay: (seconds)</label>
                <div class="col-lg-4 option-skip-value-edit" @if($campaigns->open_profile == 1) @else style="display: none" @endif>

                    <div class="form-group has-feedback has-feedback-left">
                        <input name="time-delay" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->delay }}">
                        <div class="form-control-feedback">
                            <i class="icon-watch"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Upload background picture:</label>
                <div class="col-lg-8">
                    <input name="file[]" type="file" class="file-input" multiple="multiple">
                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="Enter your youtube video embed code">Enter your YouTube video code:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="video-url" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->video_url }}" placeholder="0Lfxmwgl20E">
                        <div class="form-control-feedback">
                            <i class="icon-youtube"></i>
                        </div>
                    </div>
                    <span class="help-block">https://www.youtube.com/watch?v=<h style="color: red;">0Lfxmwgl20E</h></span>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Landing page URL:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="url" type="text" class="form-control input-xlg" value="{{ $campaigns->url }}">
                        <div class="form-control-feedback">
                            <i class="icon-hyperlink"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-video2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-video2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-video2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>
                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-video2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-video2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-video2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-video2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-video2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-video2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-video'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "offer")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab3">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-offer')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="offer">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
               <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                <div class="col-lg-2">
                    <div class="checkbox option-skip-edit">
                        <label>
                            <input name="open-profile" type="checkbox"
                                   class="control-primary" {{ $campaigns->open_profile == '1' ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
                <label class="control-label col-lg-2 option-skip-value-edit" data-popup="tooltip" title="Waiting time to skip Ad" @if($campaigns->open_profile == 1) @else style="display: none" @endif>Time delay: (seconds)</label>
                <div class="col-lg-4 option-skip-value-edit" @if($campaigns->open_profile == 1) @else style="display: none" @endif>

                    <div class="form-group has-feedback has-feedback-left">
                        <input name="time-delay" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->delay }}">
                        <div class="form-control-feedback">
                            <i class="icon-watch"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12 social-offer">
                <center>
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="social-offer" type="checkbox" class="switch"
                                   {{ $campaigns->social_offer == '1' ? 'checked' : ''}} data-popup="tooltip" title=""
                                   data-on-text="Social offer" data-off-text="Default offer" data-on-color="success"
                                   data-off-color="default">

                        </label>
                    </div>
                </center>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Upload background picture:</label>
                <div class="col-lg-8">
                    <input name="file[]" type="file" class="file-input" multiple="multiple">
                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                </div>
            </div>
            
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3"></label>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch offer-sms-edit">
                        <label>
                            <input name="offer-sms" type="checkbox"
                                   {{ $campaigns->offer_sendsms == '1' ? 'checked' : ''}} class="switch"
                                   data-on-text="On" data-off-text="Off" data-on-color="success"
                                   data-off-color="default">
                            SMS
                        </label>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch offer-email-edit">
                        <label>
                            <input name="offer-email" type="checkbox"
                                   {{ $campaigns->offer_sendmail == '1' ? 'checked' : ''}} class="switch"
                                   data-on-text="On" data-off-text="Off" data-on-color="success"
                                   data-off-color="default">
                            Email
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Offer expiration date:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <?php $old_date_timestamp = strtotime($campaigns->offer_expire_date);
                        $offer_expire_date = date('d F, Y', $old_date_timestamp); ?>
                        <input name="offer-expire-date" type="text" class="form-control input-xlg pickadate"
                               value="{{ $offer_expire_date }}">
                        <div class="form-control-feedback">
                            <i class="icon-calendar22"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Offer limit:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="offer-limit" type="text" class="form-control input-xlg" value="{{ $campaigns->offer_limit }}">
                        </div>
                        <div class="form-control-feedback">
                            <i class="icon-hour-glass"></i>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="form-group col-lg-12 invite-friends"
                 @if($campaigns->social_offer == '1') @else style="display: none" @endif>
                <label class="control-label col-lg-3">Invite friends:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="invite-friends" type="text" class="form-control input-xlg"
                                value="{{ $campaigns->invite_friends }}">
                        </div>
                        <div class="form-control-feedback">
                            <i class="icon-users4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Offer title:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <div class="form-group has-feedback has-feedback-left">
                            <textarea name="offer-title" type="text" class="form-control input-xlg"
                                    placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_title }}</textarea>
                        </div>
                        <div class="form-control-feedback">
                            <i class="icon-file-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Offer description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <div class="form-group has-feedback has-feedback-left">
                            <textarea name="offer-desc" type="text" class="form-control input-xlg"
                                    placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_desc }}</textarea>
                        </div>
                        <div class="form-control-feedback">
                            <i class="icon-reading"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12 email-message-edit" @if( $campaigns->offer_sendmail  == '1')  @else style="display: none;" @endif>
                <label class="control-label col-lg-3">Offer Email message:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <div class="form-group has-feedback has-feedback-left">
                            <textarea name="offer-email-message" type="text"
                                        class="form-control input-xlg"
                                        placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_email_message }}</textarea>
                        </div>
                        <div class="form-control-feedback">
                            <i class="icon-mention"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12 sms-message-edit" @if( $campaigns->offer_sendsms  == '1')  @else style="display: none;" @endif>
                <label class="control-label col-lg-3">Offer SMS message:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <div class="form-group has-feedback has-feedback-left maxlength">
                                                    <textarea name="offer-sms-message" type="text"
                                                            class="form-control input-xlg maxlength-options-edit"
                                                            placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_sms_message }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-mail5"></i>
                        </div>
                            <div class="count-edit">(0) 0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12 online-redemption"
                 @if($campaigns->social_offer == '1')  style="display: none" @else @endif>
                <label class="control-label col-lg-3">Online Redemption Link:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="url" type="text" class="form-control input-xlg"
                               @if( $campaigns->url  !== '')  value="{{ $campaigns->url }}"
                               @else placeholder="Enter a website where people can redeem your offer" @endif >
                        <div class="form-control-feedback">
                            <i class="icon-hyperlink"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Terms and conditions:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <div class="form-group has-feedback has-feedback-left">
                            <textarea name="offer-terms" type="text" class="form-control input-xlg"
                                    placeholder="Enter optional terms and conditions">{{ $campaigns->offer_terms }}</textarea>
                        </div>
                        <div class="form-control-feedback">
                            <i class="icon-balance"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="col-lg-12 social-network" @if($campaigns->social_offer == '1') @else style="display: none" @endif><label class="text-semibold">Social network</label></div>
            <div class="form-group has-feedback-left col-lg-12 social-network" @if($campaigns->social_offer == '1') @else style="display: none" @endif>

                <div class="col-lg-6">
                    <select class="select-fixed-singles" name="social-post-type" >
                        <optgroup label="Options">
                            <option {{ $campaigns->social_post_type == '1' ? 'selected' : ''}} value="1">Feed</option>
                            <option {{ $campaigns->social_post_type == '2' ? 'selected' : ''}} value="2">Share</option>
                            <option {{ $campaigns->social_post_type == '3' ? 'selected' : ''}} value="3">Send massage</option>
                        </optgroup>
                    </select>
                </div>
                <div class="col-lg-6">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" data-on-color="primary" name="social-network" data-off-color="info" data-on-text="Facebook" data-off-text="Twitter" class="switch" @if($campaigns->social_network == 1) checked @endif>
                        </label>
                    </div>
                </div>
            </div>


            <div class="form-group has-feedback-left col-lg-12">

                <div class="col-lg-6">
                    <label class="text-semibold">Networks</label>
                     <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-offer2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="text-semibold">Branches</label>
                <?php
                    if(isset($campaigns->branch_id)){
                        $split_branch = explode(',', $campaigns->branch_id);
                    }?>

                    <select class="bootstrap-select select-all-values-branches-offer2" multiple="multiple"
                            data-width="100%" name="branches[]">
                        @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">

                <div class="col-lg-6">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-offer2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-offer2">Deselect
                            all
                        </button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-offer2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-offer2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-6">
                    <label class="text-semibold">Groups</label>
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>
                    <select class="bootstrap-select select-all-values-groups-offer2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-6">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-offer2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-offer2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-offer'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "apps")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab4">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-apps')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="apps">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                                  <div class="form-control-feedback">
                                    <i class="icon-pencil6"></i>
                                  </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                <div class="col-lg-2">
                    <div class="checkbox option-skip-edit">
                        <label>
                            <input name="open-profile" type="checkbox"
                                   class="control-primary" {{ $campaigns->open_profile == '1' ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
                <label class="control-label col-lg-2 option-skip-value-edit" data-popup="tooltip" title="Waiting time to skip Ad" @if($campaigns->open_profile == 1) @else style="display: none" @endif>Time delay: (seconds)</label>
                <div class="col-lg-4 option-skip-value-edit" @if($campaigns->open_profile == 1) @else style="display: none" @endif>

                    <div class="form-group has-feedback has-feedback-left">
                        <input name="time-delay" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->delay }}">
                        <div class="form-control-feedback">
                            <i class="icon-watch"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Upload background picture:</label>
                <div class="col-lg-8">
                    <input name="file[]" type="file" class="file-input" multiple="multiple">
                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                    <label class="control-label">App store Marketplace url to your app :</label>
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ios-url" type="text" class="form-control input-xlg" value="{{ $campaigns->ios_url }}">
                        <div class="form-control-feedback">
                            <i class="icon-apple2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                    <label class="control-label">Google Play Marketplace url to your app :</label>
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="android-url" type="text" class="form-control input-xlg" value="{{ $campaigns->android_url }}">
                        <div class="form-control-feedback">
                            <i class="icon-android"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
               <label class="control-label col-lg-3"  data-popup="tooltip" title="Appear on the top of ad button to motivate users to click on button and open your ad.">Motivational message:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="message" type="text" class="form-control input-xlg"
                                  placeholder="25% off"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-apps2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-apps2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-apps2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-apps2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-apps2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-apps2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-apps2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-apps2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-apps2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="document.forms['edit-apps'].submit(); return false;">
                Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "survey")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab5">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-survey')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="survey">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                <div class="col-lg-2">
                    <div class="checkbox option-skip-edit">
                        <label>
                            <input name="open-profile" type="checkbox"
                                   class="control-primary" {{ $campaigns->open_profile == '1' ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
                <label class="control-label col-lg-2 option-skip-value-edit" data-popup="tooltip" title="Waiting time to skip Ad" @if($campaigns->open_profile == 1) @else style="display: none" @endif>Time delay: (seconds)</label>
                <div class="col-lg-4 option-skip-value-edit" @if($campaigns->open_profile == 1) @else style="display: none" @endif>

                    <div class="form-group has-feedback has-feedback-left">
                        <input name="time-delay" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->delay }}">
                        <div class="form-control-feedback">
                            <i class="icon-watch"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title"><text class="icon-cogs"> Set up a creative</text></h6>
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Upload background picture:</label>
                <div class="col-lg-8">
                    <input name="file[]" type="file" class="file-input" multiple="multiple">
                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Click-through URL :</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="url" type="text" class="form-control input-xlg" value="{{ $campaigns->url }}">
                        <div class="form-control-feedback">
                            <i class="icon-hyperlink"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- this function is disables to avoid any business mistake or any change in the result -->
            <!-- <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Survey type :</label>

                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="survey-type" class="switch survey-type" data-on-text="Poll"
                                   data-off-text="Rating" data-on-color="success" data-off-color="default"
                                   @if($campaigns->survey_type == "poll") checked @endif>
                        </label>
                    </div>
                </div>
            </div> -->
            <div class="form-group col-lg-12 survey-types"
                 @if($campaigns->survey_type == "poll" || $campaigns->survey_type == "rating")  @else style="display: none" @endif>
                <label class="control-label col-lg-3">Type your question here :</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="question" type="text" class="form-control input-xlg"
                                  placeholder="Example: How did our service make you feel?">{{ $campaigns->question }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12 poll"
                 @if($campaigns->survey_type == "poll")  @else style="display: none" @endif>
                <label class="control-label col-lg-3">Options :</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-lg">
                        <div class="input-group-addon"><i class="icon-menu7"></i></div>
                        <?php
                        
                        $counter = 0;
                        $options = "";
                        foreach (App\Models\Survey::where('campaign_id', $campaigns->id)->whereNull('u_id')->get() as $survey) {
                            $counter++;
                            if ($counter != 1) {
                                $options .= ",";
                            }
                            $options .= $survey->options;
                        }
                        
                        ?>
                        <input name="options" disabled type="text" class="form-control tokenfield" value="{{ $options }}">
                    </div>
                </div>
            </div>

            <!--  ----------------------------- WhatsApp ---------------------------  -->

            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3"><text class="icon-comment-discussion"> WhatsApp </text></label>

                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="whatsapp" class="switch whatsapp-survey" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->whatsapp == "1") checked @endif>
                        </label>
                    </div>
                </div>
            </div>
            
            <div @if($campaigns->whatsapp != "1") style="display: none;" @endif  class="whatsapp-survey-form">

                @if( App\Models\Campaigns::where('whatsapp_first_survey','1')->where('whatsapp','1')->count() == 0 or $campaigns->whatsapp_first_survey == "1")
                    <div class="form-group col-lg-12 whatsapp-survey-form">
                        <label class="control-label col-lg-3">This is the first survey will reach to customers?</label>
                        <div class="col-lg-4">
                            <div class="checkbox checkbox-switch">
                                <label>
                                    <input type="checkbox" name="whatsapp_first_survey" class="switch" data-on-text="Yes"
                                        data-off-text="No" data-on-color="success" data-off-color="default"
                                        @if($campaigns->whatsapp_first_survey == "1") checked @endif>
                                </label>
                            </div>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="whatsapp_first_survey" value="off">
                @endif
                
                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">Send after Wi-Fi connected:</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-connection"></i></span>
                            <?php 
                            // whatsapp_after
                            if(isset($campaigns->whatsapp_after) and $campaigns->whatsapp_after!="0"){
                                $splitWhatsappAfter = explode('/', $campaigns->whatsapp_after);
                                    $whatsappAfterValue = $splitWhatsappAfter[0];
                                    $whatsappAfterMenu = $splitWhatsappAfter[1];
                            }else{
                                    $whatsappAfterValue = "";
                                    $whatsappAfterMenu = "0";
                            }
                            ?>
                            <select class="form-control bootstrap-select whatsapp_after_menu" data-width="100%" name="whatsapp_after_menu">
                                    <option @if($whatsappAfterMenu == "0") selected @endif value="0">don't send</option>
                                    <option @if($whatsappAfterMenu == "minuts") selected @endif value="minuts">Send after the following Minutes</option>
                                    <option @if($whatsappAfterMenu == "hours") selected @endif value="hours">Send after the following Hours</option>
                                    <option @if($whatsappAfterMenu == "days") selected @endif value="days">Send after the following Days</option>
                                    <option @if($whatsappAfterMenu == "0") selected @endif value="0">don't send</option>
                            </select>
                            <input name="whatsapp_after_value" type="number" class="form-control input-xlg" value="{{ $whatsappAfterValue }}">
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">Repeat survey every no of days:<br>if guest visit your location</label>
                    <div class="col-lg-6">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="whatsapp_repeat_survey" type="number" class="form-control input-xlg"
                                value="{{ $campaigns->whatsapp_repeat_survey }}"> 
                                <div class="form-control-feedback">
                                    <i class="icon-rotate-cw3"></i>
                                </div>
                            <span class="help-block"> Repeat survey again for the same user ( to disable repeating set it 0 ) </span>
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div class="form-group col-lg-12 edit-table-bordered whatsapp-survey-form">
                    <div class="table-responsive">
                        @if($campaigns->survey_type != "poll")
                        <div class="panel-body">
                            <button type="button" name="add" id="edit-add_load" class="btn btn-success"><i class="icon-plus2"></i></button>
                        </div>    
                        @endif
                        <table class="table edit-table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic_load">
                            <thead> 
                                <tr>
                                    <th>Option</th>
                                    <th>Auto Reply</th>
                                    <th>Guest comment</th>
                                    <th>Features</th>
                                    <!-- <th>Type</th> -->
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\Survey::where('campaign_id',$campaigns->id)->whereNull('u_id')->whereNotNull('options')->get() as $option)
                                    <tr>
                                        <td>
                                            @if($campaigns->survey_type == "poll") 
                                                <input name="text" type="text" disabled class="form-control input-xlg edit-load-ip" value="{{$option->options}}" style="width: 80px;" required>
                                                <input type="hidden" name="options[]" value="{{$option->options}}">
                                            @else
                                                <input name="options[]" type="text" class="form-control input-xlg edit-load-ip" value="{{$option->options}}" style="width: 80px;" required>
                                            @endif
                                        </td>

                                        <td>
                                            <!-- <label class="control-label">system reply</label> -->
                                            <center>
                                            <select class="edit-select" name="is_reply[]" required>
                                                <option @if($option->is_reply == "0") selected @endif value="0">Off</option>
                                                <option @if($option->is_reply == "1") selected @endif value="1">On</option>
                                            </select>
                                            </center>
                                            <textarea rows="4" name="reply_message[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thank you very much for your reply,">{{ $option->reply_message }}</textarea>
                                        </td>
                                        
                                        <td>
                                            <center>
                                            <select class="edit-select" name="is_reply_after_user_reply[]" required>
                                                <option @if($option->is_reply_after_user_reply == "0") selected @endif value="0">Off</option>
                                                <option @if($option->is_reply_after_user_reply == "1") selected @endif value="1">On</option>
                                            </select>
                                            </center>
                                            <br>
                                            <label class="control-label">Thanks message after comment</label>
                                            <textarea name="reply_message_after_user_reply[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thanks so much for the feedback, we will solve your problem as soon as possible.">{{ $option->reply_message_after_user_reply }}</textarea>
                                        </td>
                                        
                                        <td>
                                            <label class="control-label">Generate offer code when choosing this option:</label>
                                            <select class="edit-select" name="is_offer[]" required>
                                                <option @if($option->is_offer == "0") selected @endif value="0">Off</option>
                                                <option @if($option->is_offer == "1") selected @endif value="1">On</option>
                                            </select>
                                            <br>
                                            <label class="control-label">Send guest comment to Admins through Whatsapp:</label>
                                            <select class="edit-select" name="send_user_reply_to_admin_wa[]" required>
                                                <option @if($option->send_user_reply_to_admin_wa == "0") selected @endif value="0">Off</option>
                                                <option @if($option->send_user_reply_to_admin_wa == "1") selected @endif value="1">On</option>
                                            </select>
                                            <br>
                                            <label class="control-label">Next Survey: </label>
                                            <select class="edit-select" name="next_survey_id[]">
                                                <option value="0"></option>
                                                @foreach( App\Models\Campaigns::where('type', 'survey')->where('whatsapp', '1')->get() as $menu )
                                                    <option @if($option->next_campaign_id == $menu->id) selected @endif value="{{$menu->id}}"> {{$menu->campaign_name}} </option>
                                                @endforeach
                                            </select>
                                            
                                        </td>

                                        <td>
                                            @if($campaigns->survey_type != "poll")
                                                <button type="button" name="remove" class="btn btn-danger survey-btn-remove" onclick="_delete({{$option->id}})" ><i class="icon-minus2"></i></button>
                                            @endif
                                            <input type='hidden' name='option_id[]' value="{{$option->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>  
                
                <script>
                    $('#edit-add_load').click(function () {
                        var code = '<tbody> <tr id="row">\n';
                                                                            
                            code += '<td>\n';
                                @if($campaigns->survey_type == "poll") 
                                    code += '<input name="text" type="text" disabled class="form-control input-xlg edit-load-ip" value="" style="width: 80px;" required> \n';
                                    code += '<input type="hidden" name="options[]" value="{{$option->options}}"> \n';
                                @else
                                    code += '<input name="options[]" type="text" class="form-control input-xlg edit-load-ip" value="" style="width: 80px;" required> \n';
                                @endif
                                
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<center>\n';
                                code += '<select class="edit-select" name="is_reply[]" required>\n';
                                code += '<option value="0">Off</option>\n';
                                code += '<option value="1">On</option>\n';
                                code += '</select>\n';
                                code += '</center>\n';
                                code += '<textarea rows="4" name="reply_message[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thank you very much for your reply,"></textarea>\n';
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<center>\n';
                                code += '<select class="edit-select" name="is_reply_after_user_reply[]" required>\n';
                                code += '<option value="0">Off</option>\n';
                                code += '<option value="1">On</option>\n';
                                code += '</select>\n';
                                code += '</center>\n';
                                code += '<br>\n';
                                code += '<label class="control-label">Thanks message after comment</label>\n';
                                code += '<textarea name="reply_message_after_user_reply[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thanks so much for the feedback, we will solve your problem as soon as possible."></textarea>\n';
                            code += '</td>\n';
                            
                            code += '<td>\n';
                                code += '<label class="control-label">Generate offer code when choosing this option:</label>\n';
                                code += '<select class="edit-select" name="is_offer[]" required>\n';
                                    code += '<option value="0">Off</option>\n';
                                    code += '<option value="1">On</option>\n';
                                code += '</select>\n';
                                code += '<br>\n';
                                code += '<label class="control-label">Send guest comment to Admins through Whatsapp:</label>\n';
                                code += '<select class="edit-select" name="send_user_reply_to_admin_wa[]" required>\n';
                                    code += '<option value="0">Off</option>\n';
                                    code += '<option value="1">On</option>\n';
                                code += '</select>\n';
                                code += '<br>\n';
                                code += '<label class="control-label">Next Survey:</label>\n';
                                code += '<select class="edit-select" name="next_survey_id[]">\n';
                                    code += '<option value="0"></option>\n';
                                    @foreach( App\Models\Campaigns::where('type', 'survey')->where('whatsapp', '1')->get() as $menu )
                                        code += '<option value="{{$menu->id}}"> {{$menu->campaign_name}} </option>\n';
                                    @endforeach
                                code += '</select>\n';
                            code += '</td>\n';                
                                            
                            code += '<td>\n';
                                @if($campaigns->survey_type != "poll")
                                code += '<button type="button" name="remove" class="btn btn-danger survey-btn-remove" onclick="_delete" ><i class="icon-minus2"></i></button>\n';
                                @endif
                                code += '<input type="hidden" name="option_id[]" value="">\n';
                            code += '</td>\n';
                            
                            code += '</tr> </tbody>';
                        $('#edit-dynamic_load').append(code);
                        $('.edit-select-fixed-single85').select2({
                            minimumResultsForSearch: Infinity,
                            width: 85
                        });
                    });

                    $(document).on('click', '.survey-btn-remove', function () {
                        $(this).parent().parent().remove();
                    });

                    function _delete($id){
                        
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
                                    url:'surveyOptionDelete/'+$id,
                                    success:function(data) {

                                        $(this).parent().parent().remove();
                                        swal("Deleted!", "Option has been deleted, please refresh.", "success");
                                    },
                                    error:function(){
                                        swal("Cancelled", "this option is safe :)", "error");

                                    }
                                });
                            } else {
                                swal("Cancelled", "Your Cancelled :)", "success");
                            }
                        });
                    }
                </script>
                <!-- Offer Limit -->
                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            If you enabled generate offer code feature in any of survey options, <br>
                            please fill the following information to view it to your waiter when redeeming the points.
                    </div>

                    <!-- <label class="control-label col-lg-3">Offer title:</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <div class="form-group has-feedback has-feedback-left">
                                <textarea name="offer-title" type="text" class="form-control input-xlg"
                                        placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_title }}</textarea>
                            </div>
                            <div class="form-control-feedback">
                                <i class="icon-file-eye"></i>
                            </div>
                        </div>
                    </div> -->
                </div>
                
                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">Offer description:<br>(Seen by Staff and Guest)</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <div class="form-group has-feedback has-feedback-left">
                                <textarea name="offer-desc" type="text" class="form-control input-xlg"
                                        placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_desc }}</textarea>
                            </div>
                            <div class="form-control-feedback">
                                <i class="icon-reading"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">Terms and conditions:<br>(Seen by Staff)</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <div class="form-group has-feedback has-feedback-left">
                                <textarea name="offer-terms" type="text" class="form-control input-xlg"
                                        placeholder="Enter optional terms and conditions">{{ $campaigns->offer_terms }}</textarea>
                            </div>
                            <div class="form-control-feedback">
                                <i class="icon-balance"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12 whatsapp-survey-form">

                    <label class="control-label col-lg-3">Offer limit:</label>
                        <div class="col-lg-8">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="offer-limit" type="number" class="form-control input-xlg"
                                    value="{{ $campaigns->offer_limit }}">
                                    <div class="form-control-feedback">
                                        <i class="icon-hour-glass2"></i>
                                    </div>
                                <span class="help-block"> Campaign will stop after reach to limit ( for unlimited offer set limit 0 ) </span>
                            </div>
                        </div>
                </div>

                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">Send immediately to registerd users in targeting:</label>
                    <div class="col-lg-4">
                        <div class="checkbox checkbox-switch">
                            <label>
                                <input type="checkbox" name="whatsapp_immediately" class="switch"
                                        data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!--  ----------------------------- WhatsApp ---------------------------  -->

        </div>
        
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title"><text class="icon-target"> Targeting</text></h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-survey2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-survey2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-survey2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>

                <select class="bootstrap-select select-all-values-branches-survey2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-survey2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-survey2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-survey2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-survey2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-survey2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-survey'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "social")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab6">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-social')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="social">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                <div class="col-lg-2">
                    <div class="checkbox option-skip-edit">
                        <label>
                            <input name="open-profile" type="checkbox"
                                   class="control-primary" {{ $campaigns->open_profile == '1' ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
                <label class="control-label col-lg-2 option-skip-value-edit" data-popup="tooltip" title="Waiting time to skip Ad" @if($campaigns->open_profile == 1) @else style="display: none" @endif>Time delay: (seconds)</label>
                <div class="col-lg-4 option-skip-value-edit" @if($campaigns->open_profile == 1) @else style="display: none" @endif>

                    <div class="form-group has-feedback has-feedback-left">
                        <input name="time-delay" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->delay }}">
                        <div class="form-control-feedback">
                            <i class="icon-watch"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Upload background picture:</label>
                <div class="col-lg-8">
                    <input name="file[]" type="file" class="file-input" multiple="multiple">
                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                    <label class="control-label">Address of the Social network :</label>
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="url" type="text" class="form-control input-xlg"
                               placeholder="http://facebook.com/yourpage" value="{{ $campaigns->url }}">
                        <div class="form-control-feedback">
                            <i class="icon-hyperlink"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                    <label class="control-label col-lg-3"  data-popup="tooltip" title="Appear on the top of ad button to motivate users to click on button and open your ad.">Motivational message:</label>
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="message" type="text" class="form-control input-xlg"
                                  placeholder="Example: I went to placement today">{{ $campaigns->text }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                    <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-social2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-social2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-social2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>
                <div class="col-lg-6">
                    <?php
                    if(isset($campaigns->branch_id)){
                        $split_branch = explode(',', $campaigns->branch_id);
                    }?>
                    <select class="bootstrap-select select-all-values-branches-social2" multiple="multiple"
                            data-width="100%" name="branches[]">
                        @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-social2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-social2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>
                    <select class="bootstrap-select select-all-values-groups-social2" multiple="multiple"
                            data-width="100%" name="groups[]">

                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-social2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-social2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-social'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "landing")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab7">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-landing-page')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="landing">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                <div class="col-lg-2">
                    <div class="checkbox option-skip-edit">
                        <label>
                            <input name="open-profile" type="checkbox"
                                   class="control-primary" {{ $campaigns->open_profile == '1' ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>
                <label class="control-label col-lg-2 option-skip-value-edit" data-popup="tooltip" title="Waiting time to skip Ad" @if($campaigns->open_profile == 1) @else style="display: none" @endif>Time delay: (seconds)</label>
                <div class="col-lg-4 option-skip-value-edit" @if($campaigns->open_profile == 1) @else style="display: none" @endif>

                    <div class="form-group has-feedback has-feedback-left">
                        <input name="time-delay" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->delay }}">
                        <div class="form-control-feedback">
                            <i class="icon-watch"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                    <label class="control-label">Landing page :</label>
                    <select class="select-fixed-singles" name="custom-landing-page" id="selectBoxs" onchange="Perview();">
                        @foreach(App\History::where(['type2' => 'admin', 'operation' => 'custom_landing_page'])->orderBy('id','DESC')->get() as $value)
                            <option @if($campaigns->url == $value->details) selected @endif value="{{ $value->details  }}">{{ $value->add_date.' '.$value->add_time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-landing2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-landing2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-landing2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-landing2" multiple="multiple"
                        data-width="100%" name="branches[]">
                        @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-landing2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-landing2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-landing2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-landing2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-landing2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-landing-page'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "sms")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab8">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-sms')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="sms">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            
            <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                All registered users will receive SMS immediately when enter to Wi-Fi coverage of your target branches.
            </div>

            <div class="form-group col-lg-12 sms-message-edit">
                <label class="control-label col-lg-3">SMS message:</label>
                <div class="col-lg-8">
                    
                    <div class="form-group has-feedback has-feedback-left maxlength">
                                                <textarea name="offer-sms-message" type="text"
                                                          class="form-control input-xlg maxlength-options-edit"
                                                          placeholder="You've earned 25% off your total purchase!">{{ $campaigns->offer_sms_message }}</textarea>
                        <div class="count-edit">(0) 0</div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-sms2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-sms2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-sms2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-sms2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-sms2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-sms2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-sms2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-sms2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-sms2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-sms'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "mail")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab9">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-mail')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="mail">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">

            <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                All registered users will receive E-Mail immediately when enter to Wi-Fi coverage of your target branches.
            </div>

            <div class="form-group col-lg-12 email-message-edit">
                <label class="control-label col-lg-3">Email message:</label>
                <div class="col-lg-8">
                    
                    <div class="form-group has-feedback has-feedback-left">
                                                <textarea name="offer-email-message" type="text"
                                                          class="form-control input-xlg"
                                                          placeholder="You've earned 25% off your total purchase!">{{ $campaigns->offer_email_message }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        
        <!--<center><span class="help-block"><h6 style="color: red;">All registered users will receive E-Mail immediately when enter to Wi-Fi coverage of your target branches.</h6></span></center>                      -->
        <!-- CKEditor default -->
        <!--<div class="panel panel-flat">
            <div class="panel-body">
                    <div class="content-group">
                        <textarea name="offer-email-message" id="editor-full" >{{ $campaigns->offer_email_message }}</textarea>
                    </div>
            </div>
        </div>-->
        <!-- /CKEditor default -->
       
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-mail2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-mail2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-mail2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-mail2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-mail2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-mail2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-mail2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-mail2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-mail2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-mail'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "loyalty")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab10">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-loyalty')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="loyalty">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            
            <div class="form-group col-lg-12">

                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    <span class="text-semibold">Now,</span> you can reward your customers based on visits count, by using automation tracking for each customers visits.
                </div>

                <label class="control-label col-lg-3">Send SMS after no visits:
                    <span class="help-block">
                        *Note: visit count per day.
                    </span>
                </label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="loyalty_visits" value="{{$campaigns->loyalty_visits}}" type="number" class="form-control input-xlg">
                        <div class="form-control-feedback">
                            <i class="icon-stats-growth"></i>
                        </div>
                        <span class="help-block">
                            <input name="loyalty_exact_visit_count" @if($campaigns->loyalty_exact_visit_count) checked="checked" @endif type="checkbox" data-on-text="On" data-off-text="Off" class="switch" data-size="mini" value="1"> Must equal the same visit count no more.
                        </span>         
                    </div>
                </div>
                </div>

                <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Visits count method:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <select class="form-control select-fixed-single" style="box-shadow: 0 0 3px #6962ff; margin: 3px" name="loyalty_method">
                            <option @if($campaigns->loyalty_method==1) selected  @endif value='1'>count visits in current week</option>
                            <option @if($campaigns->loyalty_method==2) selected  @endif value='2'>count visits in current Month</option>
                            <option @if($campaigns->loyalty_method==3) selected  @endif value='3'>count visits in current Year</option>
                            <option @if($campaigns->loyalty_method==4) selected  @endif value='4'>count visits during last week</option>
                            <option @if($campaigns->loyalty_method==5) selected  @endif value='5'>count visits during last month</option>
                            <option @if($campaigns->loyalty_method==6) selected  @endif value='6'>count visits during last 2 months</option>
                            <option @if($campaigns->loyalty_method==7) selected  @endif value='7'>count visits during last 3 months</option>
                            <option @if($campaigns->loyalty_method==8) selected  @endif value='8'>count visits during last 4 months</option>
                            <option @if($campaigns->loyalty_method==9) selected  @endif value='9'>count visits during last 5 months</option>
                            <option @if($campaigns->loyalty_method==10) selected  @endif value='10'>count visits during last 6 months</option>
                            <option @if($campaigns->loyalty_method==11) selected  @endif value='11'>count visits during last year</option>
                            <option @if($campaigns->loyalty_method==12) selected  @endif value='12'>count visits whole the period</option>
                        </select>
                        <div class="form-control-feedback">
                            <i class="icon-stairs-up"></i>
                        </div>
                    </div>
                </div>
            </div> 


            <div class="form-group col-lg-12 sms-message-edit">
                <label class="control-label col-lg-3">SMS message:</label>
                <div class="col-lg-8">
                    
                    <div class="form-group has-feedback has-feedback-left maxlength">
                        <textarea name="offer-sms-message" type="text"
                                    class="form-control input-xlg maxlength-options-edit"
                                    placeholder="You've earned 25% off your total purchase!">{{ $campaigns->offer_sms_message }}</textarea>
                                    <div class="form-control-feedback">
                                        <i class="icon-mail5"></i>
                                    </div>
                        <div class="count-edit">(0) 0</div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12" >
                <label class="control-label col-lg-3">Apply Offer system:</label>
                <div class="col-lg-8">
                    <div class="checkbox checkbox-switch antiloss_offer_edit">
                            <input name="loyalty_offer" value="1"  @if($campaigns->loyalty_offer==1) checked="checked" @endif type="checkbox" class="switch"
                                data-on-text="On" data-off-text="Off" data-on-color="success"
                                data-off-color="default">
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12 offer-limit-edit"  @if($campaigns->loyalty_offer!=1) style="display: none;" @endif >

                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    Your customers will receive offer code at the end of each message.
                </div>

                <label class="control-label col-lg-3">Offer limit:</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="offer-limit" type="number" class="form-control input-xlg"
                                value="{{ $campaigns->offer_limit }}">
                                <div class="form-control-feedback">
                                    <i class="icon-hour-glass2"></i>
                                </div>
                             <span class="help-block"> Campaign will stop after reach to limit ( for unlimited offer set limit 0 ) </span>
                        </div>
                    </div>
            </div>
            
            <div class="form-group col-lg-12" >
                <label class="control-label col-lg-3">E-Mail:</label>
                <div class="col-lg-8">
                    <div class="checkbox checkbox-switch offer-email-edit-loyalty">
                            <input name="offer-email" @if($campaigns->offer_sendmail==1) checked="checked" @endif type="checkbox" class="switch"
                                data-on-text="On" data-off-text="Off" data-on-color="success"
                                data-off-color="default">
                                
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12 email-message-edit email-message-edit-loyalty" @if($campaigns->offer_sendmail!=1) style="display: none;" @endif>
                <label class="control-label col-lg-3">Email message:</label>
                <div class="col-lg-8">
                    
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="offer-email-message" type="text"
                                    class="form-control input-xlg"
                                    placeholder="You've earned 25% off your total purchase!">{{ $campaigns->offer_email_message }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-mailbox"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-loyalty2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-loyalty2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-loyalty2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-loyalty2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-loyalty2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-loyalty2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-loyalty2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-loyalty2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-loyalty2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-loyalty'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "antiloss")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab11">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-antiloss')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <h6 class="panel-title">General</h6>
            <input type="hidden" name="type" value="antiloss">
        </div>

        <div class="panel-body">
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Campaign name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="campaign-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->campaign_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad name:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="ad-name" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->ad_name }}">
                        <div class="form-control-feedback">
                            <i class="icon-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Ad description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="ad-desc" type="text"
                                  class="form-control input-xlg">{{ $campaigns->description }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-pencil6"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Start and end date:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php 
                        $start_date_timestamp = strtotime($campaigns->startdate);
                        $start_date = date('d F, Y', $start_date_timestamp);
                        if(isset($campaigns->enddate)){
                            $end_date = date('d F, Y', strtotime($campaigns->enddate));
                        }else{
                            $end_date = date('j F, Y', strtotime("+1 months", strtotime(date("j F, Y"))));
                        }
                        ?>
                        <input name="start-date" type="text" class="form-control pickadate" value="{{ $start_date }}">

                        <input name="end-date" type="text" value='{{$end_date}}' class="form-control pickadate start-and-end-date" @if(!isset($campaigns->enddate)) style="display: none" @endif>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="check-end-date" type="checkbox" class="switch end-date" 
                            data-on-text="with end" data-off-text="without end" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Set up a creative -->
        <div class="panel-heading">
            <h6 class="panel-title">Set up a creative</h6>
        </div>

        <div class="panel-body">
            
            <div class="form-group col-lg-12">

                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    <span class="text-semibold">Now,</span> you will never lose any customer, by using automation tracking for each customer.
                </div>

                <label class="control-label col-lg-3">Minimum number of visits:</label>

                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                    <input name="antiloss_minimum_visits_count" min="1" value="{{$campaigns->antiloss_minimum_visits_count}}" type="number" class="form-control input-xlg">
                        <div class="form-control-feedback">
                            <i class="icon-history"></i>
                        </div>
                    </div>
                    <span class="help-block">*Note: How many times customer visit your place before.</span>
                </div>
            </div>

            <div class="form-group col-lg-12">

                <label class="control-label col-lg-3">Last visit since (days):</label>

                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                    <input name="antiloss_last_visit_since" value="{{$campaigns->antiloss_last_visit_since}}" min="1" type="number" class="form-control input-xlg">
                        <div class="form-control-feedback">
                            <i class="icon-reset"></i>
                        </div>
                        <!-- <span class="help-block">
                            Last visit since (days).
                        </span> -->
                    </div>
                </div>
                </div>

                <div class="form-group col-lg-12">

                <label class="control-label col-lg-3">Sending Time:</label>

                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                    <input name="antiloss_send_time" value="{{$campaigns->antiloss_send_time}}"" type="text" class="form-control input-xlg">
                        <div class="form-control-feedback">
                            <i class="icon-alarm"></i> 
                        </div>
                        <span class="help-block">
                            Note: Timeformat 24H.
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12 sms-message-edit">
                <label class="control-label col-lg-3">SMS message:</label>
                <div class="col-lg-8">
                    
                    <div class="form-group has-feedback has-feedback-left maxlength">
                        <textarea name="offer-sms-message" type="text"
                                    class="form-control input-xlg maxlength-options-edit"
                                    placeholder="You've earned 25% off your total purchase!">{{ $campaigns->offer_sms_message }}</textarea>
                                    <div class="form-control-feedback">
                                        <i class="icon-mail5"></i>
                                    </div>
                        <div class="count-edit">(0) 0</div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12" >
                <label class="control-label col-lg-3">Apply Offer system:</label>
                <div class="col-lg-8">
                    <div class="checkbox checkbox-switch loyalty_offer_edit">
                            <input name="loyalty_offer" value="1"  @if($campaigns->loyalty_offer==1) checked="checked" @endif type="checkbox" class="switch"
                                data-on-text="On" data-off-text="Off" data-on-color="success"
                                data-off-color="default">
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12 offer-limit-edit"  @if($campaigns->loyalty_offer!=1) style="display: none;" @endif >

                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    Your customers will receive offer code at the end of each message.
                </div>

                <label class="control-label col-lg-3">Offer limit:</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="offer-limit" type="number" class="form-control input-xlg"
                                value="{{ $campaigns->offer_limit }}">
                                <div class="form-control-feedback">
                                    <i class="icon-hour-glass2"></i>
                                </div>
                             <span class="help-block"> Campaign will stop after reach to limit ( for unlimited offer set limit 0 ) </span>
                        </div>
                    </div>
            </div>
            
            <div class="form-group col-lg-12" >
                <label class="control-label col-lg-3">E-Mail:</label>
                <div class="col-lg-8">
                    <div class="checkbox checkbox-switch offer-email-edit-antiloss">
                            <input name="offer-email" @if($campaigns->offer_sendmail==1) checked="checked" @endif type="checkbox" class="switch"
                                data-on-text="On" data-off-text="Off" data-on-color="success"
                                data-off-color="default">
                                
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12 email-message-edit email-message-edit-antiloss" @if($campaigns->offer_sendmail!=1) style="display: none;" @endif>
                <label class="control-label col-lg-3">Email message:</label>
                <div class="col-lg-8">
                    
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="offer-email-message" type="text"
                                    class="form-control input-xlg"
                                    placeholder="You've earned 25% off your total purchase!">{{ $campaigns->offer_email_message }}</textarea>
                        <div class="form-control-feedback">
                            <i class="icon-mailbox"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title">Targeting</h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-antiloss2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-antiloss2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-antiloss2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>
                <select class="bootstrap-select select-all-values-branches-antiloss2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-antiloss2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-antiloss2">
                            Deselect all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-antiloss2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-antiloss2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-antiloss2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <!--
            <div class="form-group col-lg-12">
                <div class="col-lg-12">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input type="checkbox" name="day-parting" class="switch day-parting" data-on-text="On"
                                   data-off-text="Off" data-on-color="success"
                                   data-off-color="default" {{ $campaigns->day_parting == 1 && isset($campaigns->days) ? 'checked' : ''}}>
                            Day parting
                        </label>
                    </div>
                    <div>
                        <div class="form-group col-lg-2 start"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">Start:</label>
                            <input name="day-parting-start" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_start }}">
                        </div>
                        <div class="form-group col-lg-2 end"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <label class="control-label">End:</label>
                            <input name="day-parting-end" type="text" class="form-control input-xlg pickatime-limits"
                                   value="{{ $campaigns->day_parting_end }}">
                        </div>
                        <?php
                        if (isset($campaigns->days) && $campaigns->days != "") {
                            $days = explode(',', $campaigns->days);
                            foreach ($days as $day) {
                                if ($day == 'sun') {
                                    $sunday = "sunday";
                                }
                                if ($day == 'mon') {
                                    $monday = "monday";
                                }
                                if ($day == 'tue') {
                                    $tueday = "tueday";
                                }
                                if ($day == 'wed') {
                                    $wedday = "wedday";
                                }
                                if ($day == 'thu') {
                                    $thuday = "thuday";
                                }
                                if ($day == 'fri') {
                                    $friday = "friday";
                                }
                                if ($day == 'sat') {
                                    $satday = "satday";
                                }
                            }
                        }
                        ?>

                        <div class="form-group col-lg-2 sun"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif >
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sun-day" type="checkbox"
                                           class="switchery" {{ isset($sunday) ? 'checked' : ''}}>
                                    Sun
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 mon"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="mon-day" type="checkbox"
                                           class="switchery" {{ isset($monday) ? 'checked' : ''}}>
                                    Mon
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 tue"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="tue-day" type="checkbox"
                                           class="switchery" {{ isset($tueday) ? 'checked' : ''}}>
                                    Tue
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 wed"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="wed-day" type="checkbox"
                                           class="switchery" {{ isset($wedday) ? 'checked' : ''}}>
                                    Wed
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 thu"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="thu-day" type="checkbox"
                                           class="switchery" {{ isset($thuday) ? 'checked' : ''}}>
                                    Thu
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 fri"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="fri-day" type="checkbox"
                                           class="switchery" {{ isset($friday) ? 'checked' : ''}}>
                                    Fri
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-2 sat"
                             @if($campaigns->day_parting == 1) @else style="display: none;" @endif>
                            <div class="checkbox checkbox-switchery switchery-sm">
                                <label>
                                    <input name="sat-day" type="checkbox"
                                           class="switchery" {{ isset($satday) ? 'checked' : ''}}>
                                    Sat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-antiloss'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "whatsappFirstBot")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab13">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-whatsappFirstBot')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <!-- <h6 class="panel-title">Whatsapp Bot settings</h6> -->
            <input type="hidden" name="type" value="whatsappFirstBot">
        </div>

        <div class="panel-body">
            
            <div class="whatsappFirstBot-form">
                
                <!-- Setting Messages -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-botSettingMessages"> <i class="icon-bubbles2"></i> &nbsp Auto reply messages</a>
                        </h6>
                    </div>
                    <div id="edit-accordion-control-right-botSettingMessages" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If new user scan QR for the first time
                                <span class="help-block">Ask customer to enter his name</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappQRaskForName" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappQRaskForName')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-notification2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If customer has a pending Offer code, this message will show in main menu, under this message system will show all pending codes. </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="mainBotLoyaltyBendingOffersMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'mainBotLoyaltyBendingOffersMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                           &nbsp <i class="icon-wallet"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If customer reply to WhatsApp by unrecognized character</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappUserWrongResponse" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappUserWrongResponse')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-notification2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- loyalty Program Setting -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-botLoyaltySetting"> <i class="icon-folder-heart"></i> &nbsp Loyalty Program</a>
                        </h6>
                    </div>
                    <div id="edit-accordion-control-right-botLoyaltySetting" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">Amount ({{ App\Settings::where('type', 'currency')->value('value') }}) To Loyalty Points</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input name="amountToLoyaltyPoints" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'amountToLoyaltyPoints')->value('value') }}"> 
                                        <div class="form-control-feedback">
                                            <i class="icon-coin-dollar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">Points expire after #days</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input name="loyaltyPointsExpireAfterDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'loyaltyPointsExpireAfterDays')->value('value') }}"> 
                                        <div class="form-control-feedback">
                                            <i class="icon-database-remove"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">When staff add a bill to convert this bill amount into points
                                <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@points</span>
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@earned</span>
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@available_loyalty_programs</span>
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@all_loyalty_programs</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappUserReceivePointsMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappUserReceivePointsMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-coins"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If customer refund any amount
                                    <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@points</span>
                                    <span class="label btn-success btn-ladda btn-ladda-spinner">@refund</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappUserRefundPointsMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappUserRefundPointsMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-reset"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If customer asks for the loyalty program
                                    <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@id</span>
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@points</span> 
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@available_loyalty_programs</span> 
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@all_loyalty_programs</span> 
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappUserAskForLoyaltyProgram" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappUserAskForLoyaltyProgram')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp  <i class="icon-price-tags"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <!-- Loyalty Program Tiers -->
                            <div class="form-group col-lg-12 edit-table-bordered whatsapp-loyalty-form">
                                <div class="table-responsive">
                                    <div class="panel-body">
                                        <button type="button" name="add" id="edit-add-loyalty-program" class="btn btn-success"><i class="icon-plus2"></i></button>
                                    </div>
                                    <table class="table edit-table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic-loyalty-program">
                                        <thead> 
                                            <tr>
                                                <th>Points / Type</th>
                                                <th>Message</th>
                                                <th><i class="icon-arrow-right5">Discount Per Item</th>
                                                <th><i class="icon-arrow-right5">By One Get Many</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(App\Models\LoyaltyProgram::where('state','1')->where('row_type','1')->get() as $loyaltyProgram)
                                                <tr>
                                                    <td>
                                                        <center>
                                                            <label class="control-label">Points</label>
                                                            <input name="loyalty_program_points[]" type="text" class="form-control input-xlg" value="{{$loyaltyProgram->points}}" style="width: 130px;" required>
                                                            <br>
                                                            <select class="edit-select" name="loyalty_program_type[]" required>
                                                                <option @if($loyaltyProgram->type == "1") selected @endif value="1">Free Item</option>
                                                                <option @if($loyaltyProgram->type == "2") selected @endif value="2">Discount Per Item</option>
                                                                <option @if($loyaltyProgram->type == "3") selected @endif value="3">By One Get Many</option>
                                                                <option @if($loyaltyProgram->type == "4") selected @endif value="4">Discount Per Sale</option>
                                                            </select>
                                                        </center>
                                                    </td>

                                                    <td>
                                                        <label class="control-label">Item</label>
                                                        <textarea rows="2" dir=rtl name="loyalty_program_whatsapp[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Discount 10%.">{{ $loyaltyProgram->whatsapp }}</textarea>
                                                        <!-- <br> -->
                                                        <button type="button" name="add" onclick="addLoyaltyProgramItem({{$loyaltyProgram->id}})" class="btn-success"><i class="icon-plus2"></i></button>
                                                        <div id="edit-dynamic-loyalty-program-item-{{$loyaltyProgram->id}}">
                                                            <table id="POITable">
                                                            @foreach( App\Models\LoyaltyProgramItems::where('loyalty_program_id', $loyaltyProgram->id)->get() as $loyalProgItem )
                                                            <tr class="loyalty-program-item-btn-remove-{{$loyalProgItem->id}}" ><td>
                                                                <select class="edit-select" name="loyalty_program_item_id[{{$loyaltyProgram->id}}][{{$loyalProgItem->id}}]" style="width: 130px;" required>
                                                                    <option value=""> </option>
                                                                    @foreach( App\Models\PosItems::get() as $item )
                                                                        <option @if(App\Models\LoyaltyProgramItems::where('id',$loyalProgItem->id)->value('item_id') == $item->pos_id) selected @endif value="{{$item->pos_id}}"> {{$item->name}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td> 
                                                                <!-- <i class="icon-minus2 loyalty-program-item-btn-remove" onclick="loyalty_program_item_delete({{$loyalProgItem->id}})"></i>  -->
                                                                <button type="button" class="close loyalty-program-item-btn-remove" data-dismiss="alert" onclick="loyalty_program_item_delete({{$loyalProgItem->id}})"><span>×</span><span class="sr-only">X</span></button>
                                                            </td>
                                                            </tr>
                                                            @endforeach
                                                            </table>
                                                        </div>
                                                        <label class="control-label">When Customer reached:</label>
                                                        <textarea rows="2" dir=rtl name="just_reached_whatsapp_msg[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Congratulations you have reached to discount 10%.">{{ $loyaltyProgram->just_reached_whatsapp_msg }}</textarea>
                                                    </td>
                                                    
                                                    <td>
                                                        <center>
                                                            <label class="control-label"><i class="icon-arrow-right5"></i>Discount Type</label>
                                                            <select class="edit-select" name="discount_type[]" required>
                                                                <option @if($loyaltyProgram->discount_type == "0") selected @endif value="0"></option>
                                                                <option @if($loyaltyProgram->discount_type == "1") selected @endif value="1">Percentage</option>
                                                                <option @if($loyaltyProgram->discount_type == "2") selected @endif value="2">Value</option>
                                                            </select>
                                                            <br>
                                                            <label class="control-label"><i class="icon-arrow-right5"></i>Discount Value</label>
                                                            <input name="discount_value[]" type="text" class="form-control input-xlg" value="{{ $loyaltyProgram->discount_value }}" style="width: 90px;">
                                                            <label class="control-label"><i class="icon-arrow-right5"></i>Max Discount {{ App\Settings::where('type', 'currency')->value('value') }}</label>
                                                            <input name="max_discount_amount[]" type="text" class="form-control input-xlg" value="{{ $loyaltyProgram->max_discount_amount }}" style="width: 90px;">
                                                        </center>
                                                    </td>
                                                    
                                                    <td>
                                                        <label class="control-label"><i class="icon-arrow-right5"></i>Depend on Item</label>
                                                        <input dir=rtl name="depends_on_item_name[]" type="text" class="form-control input-xlg" value="{{$loyaltyProgram->depends_on_item_name}}" style="width: 100px;" required>
                                                        <select class="edit-select" name="depends_on_item_id[]" style="width: 100px;" required>
                                                            <option value=""> </option>
                                                            @foreach( App\Models\PosItems::get() as $item )
                                                                <option @if($loyaltyProgram->depends_on_item_id == $item->pos_id) selected @endif value="{{$item->pos_id}}"> {{$item->name}} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <button type="button" name="remove" class="btn btn-danger loyalty-program-btn-remove" onclick="loyalty_program_delete({{$loyaltyProgram->id}})" ><i class="icon-minus2"></i></button>
                                                        <input type='hidden' name='loyaltyProgram_id[]' value="{{$loyaltyProgram->id}}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>  
                            
                            <script>
                                $('#edit-add-loyalty-program').click(function () {
                                    var code = '<tbody> <tr id="row">\n';
                                                                                        
                                        code += '<td>\n';
                                        code += '<center>\n';
                                            code += '<label class="control-label">Points</label>\n';
                                            code += '<input name="loyalty_program_points[]" type="text" class="form-control input-xlg" style="width: 130px;" required>\n';
                                            code += '<br>\n';
                                            code += '<select class="edit-select" name="loyalty_program_type[]" required>\n';
                                                code += '<option value="1">Free Item</option>\n';
                                                code += '<option value="2">Discount Per Item</option>\n';
                                                code += '<option value="3">By One Get Many</option>\n';
                                                code += '<option value="4">Discount Per Sale</option>\n';
                                            code += '</select>\n';
                                        code += '</center>\n';
                                        code += '</td>\n';

                                        code += '<td>\n';
                                            code += '<label class="control-label">Item</label>\n';
                                            code += '<textarea rows="2" dir=rtl name="loyalty_program_whatsapp[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Discount 10%."></textarea>\n';
                                            code += '<select class="edit-select" name="loyalty_program_item_id[][]" style="width: 130px;" required> \n';
                                                code += '<option value=""> </option> \n';
                                                @foreach( App\Models\PosItems::get() as $item )
                                                    code += '<option value="{{$item->pos_id}}"> {{$item->name}} </option> \n';
                                                @endforeach
                                            code += '</select> \n';
                                            code += '<label class="control-label">When Customer reached:</label>\n';
                                            code += '<textarea rows="2" dir=rtl name="just_reached_whatsapp_msg[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Congratulations you have reached to discount 10%."></textarea>\n';
                                        code += '</td>\n';

                                        code += '<td>\n';
                                            code += '<center>\n';
                                                code += '<label class="control-label">Discount Type</label>\n';
                                                code += '<select class="edit-select" name="discount_type[]" required>\n';
                                                    code += '<option value="0"></option>\n';
                                                    code += '<option value="1">Percentage</option>\n';
                                                    code += '<option value="2">Value</option>\n';
                                                code += '</select>\n';
                                                code += '<br>\n';
                                                code += '<label class="control-label">Discount Value</label>\n';
                                                code += '<input name="discount_value[]" type="text" class="form-control input-xlg" style="width: 90px;">\n';
                                                code += '<label class="control-label"></i>Max Discount {{ App\Settings::where("type", "currency")->value("value") }}</label>\n';
                                                code += '<input name="max_discount_amount[]" type="text" class="form-control input-xlg" style="width: 90px;">\n';
                                            code += '</center>\n';
                                        code += '</td>\n';
                                        
                                        code += '<td>\n';
                                            code += '<label class="control-label">Depend on Item</label>\n';
                                            code += '<input dir=rtl name="depends_on_item_name[]" type="text" class="form-control input-xlg" style="width: 100px;" required>\n';
                                            code += '<select class="edit-select" name="depends_on_item_id[]" style="width: 100px;" required> \n';
                                                code += '<option value=""> </option> \n';
                                                @foreach( App\Models\PosItems::get() as $item )
                                                    code += '<option value="{{$item->pos_id}}"> {{$item->name}} </option> \n';
                                                @endforeach
                                            code += '</select> \n';
                                        code += '</td>\n';                                                                        
                                        code += '<td>\n';
                                            code += '<button type="button" name="remove" class="btn btn-danger loyalty-program-btn-remove" ><i class="icon-minus2"></i></button>\n';
                                            code += '<input type="hidden" name="loyaltyProgram_id[]" value="">\n';
                                        code += '</td>\n';
                                        
                                        code += '</tr> </tbody>';
                                    $('#edit-dynamic-loyalty-program').append(code);
                                    $('.edit-select-fixed-single85').select2({
                                        minimumResultsForSearch: Infinity,
                                        width: 85
                                    });
                                });

                                // will delete
                                // $('#edit-add-loyalty-program-item').click(function () {
                                //     var code = '<br><select class="edit-select" name="loyalty_program_item_id[][]" style="width: 130px;" required> \n';
                                //             code += '<option value=""> </option> \n';
                                //             @foreach( App\Models\PosItems::get() as $item )
                                //                 code += '<option value="{{$item->pos_id}}"> {{$item->name}} </option> \n';
                                //             @endforeach
                                //         code += '</select> \n';
                                        
                                //     $('#edit-dynamic-loyalty-program-item').append(code);
                                // });
                                
                                function addLoyaltyProgramItem($id){
                                    var code = '<tr class="new-loyalty-program-item-btn-remove-'+$id+'"><td><select class="edit-select" name="loyalty_program_item_id['+$id+'][]" style="width: 130px;" required> \n';
                                            code += '<option value=""> </option> \n';
                                            @foreach( App\Models\PosItems::get() as $item )
                                                code += '<option value="{{$item->pos_id}}"> {{$item->name}} </option> \n';
                                            @endforeach
                                        code += '</select> </td>\n';
                                        code += '<td><button type="button" class="close loyalty-program-item-btn-remove" data-dismiss="alert" onclick="new_loyalty_program_item_delete('+$id+')"><span>×</span><span class="sr-only">X</span></button></td></tr>'
                                    $('#edit-dynamic-loyalty-program-item-'+$id).append(code);
                                }

                                $(document).on('click', '.loyalty-program-btn-remove', function () {
                                    $(this).parent().parent().remove();
                                });

                                function loyalty_program_delete($id){
                                    
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
                                                url:'loyaltyProgramDelete/'+$id,
                                                success:function(data) {

                                                    $(this).parent().parent().remove();
                                                    swal("Deleted!", "Option has been deleted, please refresh.", "success");
                                                },
                                                error:function(){
                                                    swal("Cancelled", "this option is safe :)", "error");

                                                }
                                            });
                                        } else {
                                            swal("Cancelled", "Your Cancelled :)", "success");
                                        }
                                    });
                                }

                                // $(document).on('click', '.loyalty-program-item-btn-remove', function () {
                                //     $(this).parent().parent().remove();
                                // });

                                function new_loyalty_program_item_delete($id){
                                    // remove any new row
                                    [...document.getElementsByClassName('new-loyalty-program-item-btn-remove-'+$id)].map(n => n && n.remove());
                                }
                                function loyalty_program_item_delete($id){
                                    
                                    var that = this;
                                    
                                    swal({
                                        title: "Are you sure?",
                                        text: "You will not be able to recover this item again!",
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
                                                url:'loyaltyProgramItemDelete/'+$id,
                                                success:function(data) {
                                                    [...document.getElementsByClassName('loyalty-program-item-btn-remove-'+$id)].map(n => n && n.remove());
                                                    $(this).parent().parent().remove();
                                                    swal("Deleted!", "Option has been deleted, please refresh.", "success");
                                                },
                                                error:function(){
                                                    swal("Cancelled", "this item is safe :)", "error");

                                                }
                                            });
                                        } else {
                                            swal("Cancelled", "Your Cancelled :)", "success");
                                        }
                                    });
                                    
                                    
                                }
                            </script>

                        </div>
                    </div>
                </div>

                <!-- Referral Program Setting -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#edit-accordion-control-right-botReferralSetting"> <i class="icon-man-woman"></i> &nbsp Referral Program</a>
                        </h6>
                    </div>
                    <div id="edit-accordion-control-right-botReferralSetting" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        Edit the following options if you add referral program option in main menu
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsappReferralinviterIsPoints">
                                <label class="control-label col-lg-4">Give points to inviter? 
                                    <span class="help-block">Once the invitee visit your branch and connect to Wi-Fi and entered the inviter mobile number in WhatsApp.</span> 
                                </label>
                                <?php $whatsappReferralinviterIsPoints = App\Settings::where('type', 'whatsappReferralinviterIsPoints')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="whatsappReferralinviterIsPoints_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($whatsappReferralinviterIsPoints == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">Inviter points:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input name="whatsappReferralinviterIsPoints" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'whatsappReferralinviterIsPoints')->value('value') }}"> 
                                        <div class="form-control-feedback">
                                            <i class="icon-coins"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsappReferralinviteeIsPoints">
                                <label class="control-label col-lg-4">Give points to invitee?</label>
                                <?php $whatsappReferralinviteeIsPoints = App\Settings::where('type', 'whatsappReferralinviteeIsPoints')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="whatsappReferralinviteeIsPoints_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($whatsappReferralinviteeIsPoints == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">Invitee points:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input name="whatsappReferralinviteeIsPoints" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'whatsappReferralinviteeIsPoints')->value('value') }}"> 
                                        <div class="form-control-feedback">
                                            <i class="icon-coins"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsappReferralinviterIsOffer">
                                <label class="control-label col-lg-4">Give special offer to inviter?
                                    <span class="help-block">The inviter offer is the same invitee offer, you can edit from the following offer fields.</span> 
                                </label>
                                <?php $whatsappReferralinviterIsOffer = App\Settings::where('type', 'whatsappReferralinviterIsOffer')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="whatsappReferralinviterIsOffer" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($whatsappReferralinviterIsOffer == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsappReferralinviteeIsOffer">
                                <label class="control-label col-lg-4">Give special offer to invitee?</label>
                                <?php $whatsappReferralinviteeIsOffer = App\Settings::where('type', 'whatsappReferralinviteeIsOffer')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="whatsappReferralinviteeIsOffer" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($whatsappReferralinviteeIsOffer == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Offer Limit -->
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        This offer will apply on inviter and invitee, if you enable inviter or invitee Offer.
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Offer description:<br>(Seen by Staff and Guest)</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea name="offer-desc" type="text" class="form-control input-xlg"
                                                    placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_desc }}</textarea>
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Terms and conditions:<br>(Seen by Staff)</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea name="offer-terms" type="text" class="form-control input-xlg"
                                                    placeholder="Enter optional terms and conditions">{{ $campaigns->offer_terms }}</textarea>
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-balance"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">

                                <label class="control-label col-lg-3">Offer limit:</label>
                                    <div class="col-lg-8">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <input name="offer-limit" type="number" class="form-control input-xlg"
                                                value="{{ $campaigns->offer_limit }}">
                                                <div class="form-control-feedback">
                                                    <i class="icon-hour-glass2"></i>
                                                </div>
                                            <span class="help-block"> ( for unlimited offer set limit 0 ) </span>
                                        </div>
                                    </div>
                            </div>
                            
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        <i class="icon-circle-small"></i> Referral Program Inviter:
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">When customer click to invite friends: </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInviterMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInviterMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                           &nbsp <i class="icon-users4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">When customer click to invite friends, <br>this predefined message to forward it directly to his friends.
                                    <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@name </span>
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@mobile </span> 
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@available_loyalty_programs</span> 
                                     <span class="label btn-success btn-ladda btn-ladda-spinner">@all_loyalty_programs</span> 
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInvitationForwardMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInvitationForwardMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-bubbles10"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        <i class="icon-circle-small"></i> Referral Program Invitee:
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If invitee asked to insert invitation code: </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInvitedAskBeforeInvitationMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInvitedAskBeforeInvitationMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                           &nbsp <i class="icon-barcode2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">Invitation Success:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInvitedAskInvitationSuccessMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInvitedAskInvitationSuccessMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-checkmark4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">Wrong invitation number:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInvitedAskInvitationFailMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInvitedAskInvitationFailMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-cross2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">Already invited before:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInvitedAskAfterInvitationMsg" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInvitedAskAfterInvitationMsg')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-blocked"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If invitee insert the invitation code but didn't open his Wi-Fi:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInvitationOpenWiFi" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInvitationOpenWiFi')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-connection"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">If invitee insert the invitation code but the inviter limit has been reached:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="whatsappReferralInvitationOfferLimitExceeded" type="text" class="form-control input-xlg" dir="rtl" rows="2" placeholder="">{{ App\Settings::where('type', 'whatsappReferralInvitationOfferLimitExceeded')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-heart-broken2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Online Payment Setting -->
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#edit-accordion-control-right-botWhatsappPay"> <i class="icon-credit-card"></i> &nbsp Online Payment</a>
                        </h6>
                    </div>
                    <div id="edit-accordion-control-right-botWhatsappPay" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        Edit the following options if you add (Online Payment) option in main menu <br>
                                        To enable it, add new option and enable the (Online Payment) option to "On" then edit the following settings <br>
                                        Don't forget to complete your payment methods integration config in settings page.
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsappPayFawryState">
                                <label class="control-label col-lg-4">Fawry? 
                                    <span class="help-block">Your customers will receive Fawry code by SMS and WhatsApp.</span> 
                                </label>
                                <?php $whatsappPayFawryState = App\Settings::where('type', 'whatsappPayFawryState')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="whatsappPayFawryState" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($whatsappPayFawryState == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsappPayVisaState">
                                <label class="control-label col-lg-4">Card (Visa, MasterCard, Miza)? 
                                    <span class="help-block">Your customers will receive payment link on WhatsApp directly.</span> 
                                </label>
                                <?php $whatsappPayVisaState = App\Settings::where('type', 'whatsappPayVisaState')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="whatsappPayVisaState" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($whatsappPayVisaState == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsappPayWalletState">
                                <label class="control-label col-lg-4">Wallet? 
                                    <span class="help-block">Your customers will receive payment link on WhatsApp directly.</span> 
                                </label>
                                <?php $whatsappPayWalletState = App\Settings::where('type', 'whatsappPayWalletState')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="whatsappPayWalletState" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($whatsappPayWalletState == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Enter Amount Message:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappPayEnterAmountMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappPayEnterAmountMsg')->value('value') }}</textarea>
                                            <span class="help-block">This is the first message when customer click on (Online Payment) choice.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Enter Table No Message:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappPayEnterTableNoMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappPayEnterTableNoMsg')->value('value') }}</textarea>
                                            <span class="help-block">This is the second message when customer click on (Online Payment) choice.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Receiving Payment links:
                                <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@fawry</span>
                                <span class="label btn-success btn-ladda btn-ladda-spinner">@visa</span>
                                <span class="label btn-success btn-ladda btn-ladda-spinner">@wallet</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappPayFinishMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappPayFinishMsg')->value('value') }}</textarea>
                                            <span class="help-block">This is the final message when customer click on (Online Payment) choice.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Error Message:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappPayErrorMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappPayErrorMsg')->value('value') }}</textarea>
                                            <span class="help-block">In case there's any problem with payment gateways.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Birthday Program Setting -->
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control" href="#edit-accordion-control-right-botBirthday"> <i class="icon-gift"></i> &nbsp Birthday Program</a>
                        </h6>
                    </div>
                    <div id="edit-accordion-control-right-botBirthday" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        Edit the following options if you add (Birthday Program) option in main menu <br>
                                        To enable it, add new option and enable the (Birthday Program) option to "On" then edit the following settings.
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Auto Send birthday offer before no of days:</label>
                                <div class="col-lg-6">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input name="whatsappBirthdaySendOfferBeforeNoDays" style="width: 85px;" type="number" class="form-control input-xlg"
                                            value="{{ App\Settings::where('type', 'whatsappBirthdaySendOfferBeforeNoDays')->value('value') }}"> 
                                            <div class="form-control-feedback">
                                                <i class="icon-alarm"></i>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <?php $birthdaysCelebrationUniqueCampaign = App\Models\Campaigns::where('type', 'birthdaysCelebrationOfferUnique')->first(); ?>
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">First birthday reminder: <br>Offer description (Seen by Staff and Guest)
                                <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@name</span>
                                <span class="label btn-success btn-ladda btn-ladda-spinner">@offer</span>
                                <span class="label btn-success btn-ladda btn-ladda-spinner">@birthdate</span>
                                </label>
                                
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea  dir="rtl" name="birthdate-offer-desc" type="text" class="form-control input-xlg"
                                                    placeholder="Take 25% off your total purchase!">{{ $birthdaysCelebrationUniqueCampaign->offer_desc }}</textarea>
                                            <span class="help-block">first birthday reminder before no of days.</span>
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Second birthday reminder:
                                <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@name</span>
                                <span class="label btn-success btn-ladda btn-ladda-spinner">@offer</span>
                                <span class="label btn-success btn-ladda btn-ladda-spinner">@birthdate</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappBirthdayMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappBirthdayMsg')->value('value') }}</textarea>
                                            <span class="help-block">Birthday reminder in the same birthday date.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            &nbsp <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Terms and conditions:<br>(Seen by Staff)</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea name="birthdate-offer-terms" type="text" class="form-control input-xlg"
                                                    placeholder="Enter optional terms and conditions">{{ $birthdaysCelebrationUniqueCampaign->offer_terms }}</textarea>
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-balance"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">

                                <label class="control-label col-lg-3">Offer limit:</label>
                                    <div class="col-lg-8">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <input name="birthdate-offer-limit" type="number" class="form-control input-xlg"
                                                value="{{ $birthdaysCelebrationUniqueCampaign->offer_limit }}">
                                                <div class="form-control-feedback">
                                                    <i class="icon-hour-glass2"></i>
                                                </div>
                                            <span class="help-block"> Birthday program will stop after reach to the limit ( for unlimited offer set limit 0 ) </span>
                                        </div>
                                    </div>
                            </div>
                        
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Enter Birthday Message:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappEnterBirthdateMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappEnterBirthdateMsg')->value('value') }}</textarea>
                                            <span class="help-block">This is the first message when customer click on (Birthday Program) choice.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">True birthday:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappBirthdateSuccessMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappBirthdateSuccessMsg')->value('value') }}</textarea>
                                            <span class="help-block">If customer entered birthday correctly.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Wrong birthday:</label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappBirthdateFailMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappBirthdateFailMsg')->value('value') }}</textarea>
                                            <span class="help-block">If customer entered wrong birthday, tell customer to try again.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 whatsapp-survey-form">
                                <label class="control-label col-lg-3">Already entered birthdaty before:
                                    <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@name</span>
                                    <span class="label btn-success btn-ladda btn-ladda-spinner">@birthdate</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <textarea dir="rtl" name="whatsappBirthdateAlreadyEnterdMsg" type="text" class="form-control input-xlg"
                                                    placeholder="">{{ App\Settings::where('type', 'whatsappBirthdateAlreadyEnterdMsg')->value('value') }}</textarea>
                                            <span class="help-block">In case customer try to enter birthday again.</span> 
                                        </div>
                                        <div class="form-control-feedback">
                                            <i class="icon-reading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Main settings -->
                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">Auto send after Wi-Fi connected:</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-connection"></i></span>
                            <?php 
                            // whatsapp_after
                            if(isset($campaigns->whatsapp_after) and $campaigns->whatsapp_after!="0"){
                                $splitWhatsappAfter = explode('/', $campaigns->whatsapp_after);
                                    $whatsappAfterValue = $splitWhatsappAfter[0];
                                    $whatsappAfterMenu = $splitWhatsappAfter[1];
                            }else{
                                    $whatsappAfterValue = "";
                                    $whatsappAfterMenu = "0";
                            }
                            ?>
                            <select class="form-control bootstrap-select whatsapp_after_menu" data-width="100%" name="whatsapp_after_menu">
                                    <option @if($whatsappAfterMenu == "0") selected @endif value="0">don't send</option>
                                    <option @if($whatsappAfterMenu == "minuts") selected @endif value="minuts">Send after the following Minutes</option>
                                    <option @if($whatsappAfterMenu == "hours") selected @endif value="hours">Send after the following Hours</option>
                                    <option @if($whatsappAfterMenu == "days") selected @endif value="days">Send after the following Days</option>
                                    <option @if($whatsappAfterMenu == "0") selected @endif value="0">don't send</option>
                            </select>
                            <input name="whatsapp_after_value" type="number" class="form-control input-xlg" value="{{ $whatsappAfterValue }}">
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">Auto repeat every no of days:<br>if guest visit your location</label>
                    <div class="col-lg-6">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="whatsapp_repeat_survey" style="width: 85px;" type="number" class="form-control input-xlg"
                                value="{{ $campaigns->whatsapp_repeat_survey }}"> 
                                <div class="form-control-feedback">
                                    <i class="icon-rotate-cw3"></i>
                                </div>
                            <span class="help-block"> Repeat menu again for the same user ( to disable repeating set it 0 ) </span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12 survey-types">
                    <label class="control-label col-lg-3">Menu :
                        <br> <span class="label btn-success btn-ladda btn-ladda-spinner">@id</span>
                        <span class="label btn-success btn-ladda btn-ladda-spinner">@name</span>
                        <span class="label btn-success btn-ladda btn-ladda-spinner">@mobile</span>
                        <span class="label btn-success btn-ladda btn-ladda-spinner">@email</span>
                        <span class="label btn-success btn-ladda btn-ladda-spinner">@points</span>
                        <span class="label btn-success btn-ladda btn-ladda-spinner">@offerCodes</span> 
                    </label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <textarea name="question" type="text" class="form-control input-xlg" dir="rtl" rows="10" placeholder="Example: How did our service make you feel?">{{ $campaigns->question }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div class="form-group col-lg-12 edit-table-bordered whatsapp-survey-form">
                    <div class="table-responsive">
                        <div class="panel-body">
                            <button type="button" name="add" id="edit-add_load" class="btn btn-success"><i class="icon-plus2"></i></button>
                        </div>
                        <table class="table edit-table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic_load">
                            <thead> 
                                <tr>
                                    <th>Menu option</th>
                                    <th>Reply</th>
                                    <th>Options</th>
                                    <!-- <th>Type</th> -->
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\Survey::where('campaign_id',$campaigns->id)->whereNull('u_id')->whereNotNull('options')->get() as $option)
                                    <tr>
                                        <td>
                                            <input name="options[]" type="text" class="form-control input-xlg edit-load-ip" value="{{$option->options}}" style="width: 80px;" required>
                                        </td>

                                        <td>
                                            @if($option->is_reply == 1)
                                                <!-- <select class="edit-select" name="is_reply[]" required>
                                                    <option @if($option->is_reply == "0") selected @endif value="0">Off</option>
                                                    <option @if($option->is_reply == "1") selected @endif value="1">On</option>
                                                </select> -->
                                                <input type="hidden" name="is_reply[]" value="{{$option->is_reply}}">
                                                <textarea rows="4" name="reply_message[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thank you very much for your reply,">{{ $option->reply_message }}</textarea>
                                            @else
                                                <input type="hidden" name="is_reply[]" value="{{$option->is_reply}}">
                                                <input type="hidden" name="reply_message[]" value="{{$option->reply_message}}">
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <ul>
                                                @if($option->view_loyalty_program == 1)
                                                    <li>
                                                        <label class="control-label">View Loyalty Program</label>
                                                        <!-- <select class="edit-select" name="view_loyalty_program[]" required>
                                                            <option @if($option->view_loyalty_program == "0") selected @endif value="0">Off</option>
                                                            <option @if($option->view_loyalty_program == "1") selected @endif value="1">On</option>
                                                        </select> -->
                                                        <input type="hidden" name="view_loyalty_program[]" value="{{$option->view_loyalty_program}}">
                                                    </li>
                                                @else
                                                    <input type="hidden" name="view_loyalty_program[]" value="{{$option->view_loyalty_program}}">
                                                @endif    
                                                @if($option->call_staff == 1)
                                                    <li>
                                                        <label class="control-label">Call Staff</label>
                                                        <!-- <select class="edit-select" name="call_staff[]" required>
                                                            <option @if($option->call_staff == "0") selected @endif value="0">Off</option>
                                                            <option @if($option->call_staff == "1") selected @endif value="1">On</option>
                                                        </select> -->
                                                        <input type="hidden" name="call_staff[]" value="{{$option->call_staff}}">
                                                        <br> <i class="icon-arrow-right15"></i> Success Message <input name="call_staff_success_msg[]" type="text" class="form-control input-xlg edit-load-ip" value="{{$option->call_staff_success_msg}}" style="width: 150px;">
                                                        <br> <i class="icon-arrow-right15"></i> Open Wi-Fi Required <br>
                                                            <?php $avoidWiFiWhenCallStaff = App\Settings::where('type', 'avoidWiFiWhenCallStaff')->value('state'); ?>
                                                            <select class="edit-select" name="avoidWiFiWhenCallStaff" style="width: 150px;">
                                                                <option @if($avoidWiFiWhenCallStaff == "0") selected @endif value="0">Yes</option>
                                                                <option @if($avoidWiFiWhenCallStaff == "1") selected @endif value="1">No</option>
                                                            </select>
                                                        <br> <i class="icon-arrow-right15"></i> Fail, Please open your Wi-Fi <input name="login_to_wifi_msg[]" type="text" class="form-control input-xlg edit-load-ip" value="{{$option->login_to_wifi_msg}}" style="width: 150px;">    
                                                       
                                                    </li>
                                                @else
                                                    <input type="hidden" name="call_staff[]" value="{{$option->call_staff}}">
                                                    <input type="hidden" name="call_staff_success_msg[]" value="{{$option->call_staff_success_msg}}">
                                                    <input type="hidden" name="login_to_wifi_msg[]" value="{{$option->login_to_wifi_msg}}">
                                                @endif    
                                                @if($option->whatsapp_referral_inviter == 1)
                                                    <li>
                                                        <label class="control-label">Referral Program Inviter</label>
                                                        <!-- <select class="edit-select" name="whatsapp_referral_inviter[]">
                                                            <option @if($option->whatsapp_referral_inviter == "0") selected @endif value="0">Off</option>
                                                            <option @if($option->whatsapp_referral_inviter == "1") selected @endif value="1">On</option>
                                                        </select> -->
                                                        <input type="hidden" name="whatsapp_referral_inviter[]" value="{{$option->whatsapp_referral_inviter}}">
                                                    </li>
                                                @else
                                                    <input type="hidden" name="whatsapp_referral_inviter[]" value="{{$option->whatsapp_referral_inviter}}">
                                                @endif    
                                                @if($option->whatsapp_referral_invitee == 1)
                                                    <li>
                                                        <label class="control-label">Referral Program Invitee</label>
                                                        <!-- <select class="edit-select" name="whatsapp_referral_invitee[]">
                                                            <option @if($option->whatsapp_referral_invitee == "0") selected @endif value="0">Off</option>
                                                            <option @if($option->whatsapp_referral_invitee == "1") selected @endif value="1">On</option>
                                                        </select> -->
                                                        <input type="hidden" name="whatsapp_referral_invitee[]" value="{{$option->whatsapp_referral_invitee}}">
                                                    </li>
                                                @else
                                                    <input type="hidden" name="whatsapp_referral_invitee[]" value="{{$option->whatsapp_referral_invitee}}">
                                                @endif    
                                                @if(isset($option->next_campaign_id) and $option->next_campaign_id!="0")
                                                    <li>
                                                        <label class="control-label">Enter submenu: </label>
                                                        <!-- <select class="edit-select" name="next_campaign_id[]">
                                                            <option value="0"></option>
                                                            @foreach( App\Models\Campaigns::where('type', 'whatsappBot')->get() as $menu )
                                                                <option @if($option->next_campaign_id == $menu->id) selected @endif value="{{$menu->id}}"> {{$menu->campaign_name}} </option>
                                                            @endforeach
                                                        </select> -->
                                                        @foreach( App\Models\Campaigns::where('type', 'whatsappBot')->get() as $menu )
                                                            @if($option->next_campaign_id == $menu->id) {{$menu->campaign_name}} @endif
                                                        @endforeach
                                                        <input type="hidden" name="next_campaign_id[]" value="{{$option->next_campaign_id}}">
                                                    </li>
                                                @else
                                                    <input type="hidden" name="next_campaign_id[]" value="{{$option->next_campaign_id}}">
                                                @endif 
                                                @if($option->whatsappPay == 1)
                                                    <li>
                                                        <label class="control-label">Online Payment</label>
                                                        <!-- <select class="edit-select" name="whatsappPay[]">
                                                            <option @if($option->whatsappPay == "0") selected @endif value="0">Off</option>
                                                            <option @if($option->whatsappPay == "1") selected @endif value="1">On</option>
                                                        </select> -->
                                                        <input type="hidden" name="whatsappPay[]" value="{{$option->whatsappPay}}">
                                                    </li>
                                                @else
                                                    <input type="hidden" name="whatsappPay[]" value="{{$option->whatsappPay}}">
                                                @endif    

                                                @if($option->birthdaysCelebrationOffer == 1)
                                                    <li>
                                                        <label class="control-label">Birthday Program</label>
                                                        <!-- <select class="edit-select" name="birthdaysCelebrationOffer[]">
                                                            <option @if($option->birthdaysCelebrationOffer == "0") selected @endif value="0">Off</option>
                                                            <option @if($option->birthdaysCelebrationOffer == "1") selected @endif value="1">On</option>
                                                        </select> -->
                                                        <input type="hidden" name="birthdaysCelebrationOffer[]" value="{{$option->birthdaysCelebrationOffer}}">
                                                    </li>
                                                @else
                                                    <input type="hidden" name="birthdaysCelebrationOffer[]" value="{{$option->birthdaysCelebrationOffer}}">
                                                @endif  
                                            </ul>
                                        </td>

                                        <td>
                                            <button type="button" name="remove" class="btn btn-danger survey-btn-remove" onclick="_delete({{$option->id}})" ><i class="icon-minus2"></i></button>
                                            <input type='hidden' name='option_id[]' value="{{$option->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>  
                
                <script>
                    $('#edit-add_load').click(function () {
                        var code = '<tbody> <tr id="row">\n';
                                                                            
                            code += '<td>\n';
                                code += '<input name="options[]" type="text" class="form-control input-xlg edit-load-ip" value="" style="width: 80px;" required> \n';
                                
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<select class="edit-select" name="is_reply[]" required>\n';
                                code += '<option value="0">Off</option>\n';
                                code += '<option value="1">On</option>\n';
                                code += '</select>\n';
                                code += '<textarea rows="4" name="reply_message[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thank you very much for your reply,"></textarea>\n';
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<ul>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">View Loyalty Program:</label>\n';
                                        code += '<select class="edit-select" name="view_loyalty_program[]" required>\n';
                                            code += '<option value="0">Off</option>\n';
                                            code += '<option value="1">On</option>\n';
                                        code += '</select>\n';
                                    code += '</li>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">Call Staff:</label>\n';
                                        code += '<select class="edit-select" name="call_staff[]" required>\n';
                                            code += '<option value="0">Off</option>\n';
                                            code += '<option value="1">On</option>\n';
                                        code += '</select>\n';
                                        code += '<br> <i class="icon-arrow-right15"></i> Success Message <input name="call_staff_success_msg[]" type="text" class="form-control input-xlg edit-load-ip" value="" style="width: 150px;">\n';
                                        code += '<br> <i class="icon-arrow-right15"></i> Open Wi-Fi Required <br> \n';
                                        code += '<select class="edit-select" name="avoidWiFiWhenCallStaff" style="width: 150px;"> \n';
                                        code += '<option value="0">Yes</option> \n';
                                        code += '<option value="1">No</option> \n';
                                        code += '</select> \n';
                                        code += '<br> <i class="icon-arrow-right15"></i> Fail, Please open your Wi-Fi <input name="login_to_wifi_msg[]" type="text" class="form-control input-xlg edit-load-ip" value="" style="width: 150px;">\n';
                                    code += '</li>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">Referral Program Inviter:</label>\n';
                                        code += '<select class="edit-select" name="whatsapp_referral_inviter[]">\n';
                                            code += '<option value="0">Off</option>\n';
                                            code += '<option value="1">On</option>\n';
                                        code += '</select>\n';
                                    code += '</li>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">Referral Program Invitee:</label>\n';
                                        code += '<select class="edit-select" name="whatsapp_referral_invitee[]">\n';
                                            code += '<option value="0">Off</option>\n';
                                            code += '<option value="1">On</option>\n';
                                        code += '</select>\n';
                                    code += '</li>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">Enter submenu:</label>\n';
                                        code += '<select class="edit-select" name="next_campaign_id[]">\n';
                                            code += '<option value="0"></option>\n';
                                            @foreach( App\Models\Campaigns::where('type', 'whatsappBot')->get() as $menu )
                                                code += '<option value="{{$menu->id}}"> {{$menu->campaign_name}} </option>\n';
                                            @endforeach
                                        code += '</select>\n';
                                    code += '</li>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">Online Payment:</label>\n';
                                        code += '<select class="edit-select" name="whatsappPay[]">\n';
                                            code += '<option value="0">Off</option>\n';
                                            code += '<option value="1">On</option>\n';
                                        code += '</select>\n';
                                    code += '</li>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">Birthday Program:</label>\n';
                                        code += '<select class="edit-select" name="birthdaysCelebrationOffer[]">\n';
                                            code += '<option value="0">Off</option>\n';
                                            code += '<option value="1">On</option>\n';
                                        code += '</select>\n';
                                    code += '</li>\n';
                                code += '</ul>\n';
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<button type="button" name="remove" class="btn btn-danger survey-btn-remove" onclick="_delete" ><i class="icon-minus2"></i></button>\n';
                                code += '<input type="hidden" name="option_id[]" value="">\n';
                            code += '</td>\n';
                            
                            code += '</tr> </tbody>';
                        $('#edit-dynamic_load').append(code);
                        $('.edit-select-fixed-single85').select2({
                            minimumResultsForSearch: Infinity,
                            width: 85
                        });
                    });

                    $(document).on('click', '.survey-btn-remove', function () {
                        $(this).parent().parent().remove();
                    });

                    function _delete($id){
                        
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
                                    url:'surveyOptionDelete/'+$id,
                                    success:function(data) {

                                        $(this).parent().parent().remove();
                                        swal("Deleted!", "Option has been deleted, please refresh.", "success");
                                    },
                                    error:function(){
                                        swal("Cancelled", "this option is safe :)", "error");

                                    }
                                });
                            } else {
                                swal("Cancelled", "Your Cancelled :)", "success");
                            }
                        });
                    }
                </script>
                
            </div>

        </div>
        
        <!-- Targeting -->
        <div class="panel-heading">
            <h6 class="panel-title"><text class="icon-target"> Targeting</text></h6>
        </div>

        <div class="panel-body">
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Networks</label></div>
                <div class="col-lg-6">
                 <?php
                    if(isset($campaigns->network_id)){
                        $split_network = explode(',', $campaigns->network_id);
                    }?>
                    <select class="bootstrap-select select-all-values-networks-survey2" multiple="multiple"
                            data-width="100%" name="networks[]">
                        @foreach(App\Network::all() as $network) 
                            <option  value="{{ $network->id }}" 
                               @if(isset($split_network))
                                    @foreach($split_network as $value)
                                        @if($value == $network->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $network->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-networks-survey2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-networks-survey2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Branches</label></div>

                <div class="col-lg-6">
                <?php
                if(isset($campaigns->branch_id)){
                    $split_branch = explode(',', $campaigns->branch_id);
                }?>

                <select class="bootstrap-select select-all-values-branches-survey2" multiple="multiple"
                        data-width="100%" name="branches[]">
                    @foreach(App\Branches::all() as $branch) 
                            <option  value="{{ $branch->id }}" 
                               @if(isset($split_branch))
                                    @foreach($split_branch as $value)
                                        @if($value == $branch->id) selected @endif
                                    @endforeach
                               @endif 
                            >{{ $branch->name }} </option>
                        @endforeach
                </select>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-branches-survey2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-branches-survey2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                <div class="col-lg-6">
                    <?php
                    
                    if(isset($campaigns->group_id)){
                        $split_group = explode(',', $campaigns->group_id);
                       
                    }?>    
                    <select class="bootstrap-select select-all-values-groups-survey2" multiple="multiple"
                            data-width="100%" name="groups[]">
                        @foreach(App\Groups::where('as_system', 0)->get() as $group) 
                        <option  value="{{ $group->id }}" 
                           @if(isset($split_group))
                                @foreach($split_group as $value)
                                    @if($value == $group->id) selected @endif
                                @endforeach
                           @endif 
                        >{{ $group->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group has-feedback-left col-lg-12">
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info" id="select-all-values-groups-survey2">Select all
                        </button>
                        <button type="button" class="btn btn-default" id="deselect-all-values-groups-survey2">Deselect
                            all
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-whatsappFirstBot'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif
@if($campaign_type == "whatsappBot")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab12">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-whatsappBot')) }}
        {{ csrf_field() }}
        <div class="panel-heading">
            <!-- <h6 class="panel-title">Whatsapp Bot settings</h6> -->
            <input type="hidden" name="type" value="whatsappBot">
        </div>

        <div class="panel-body">
            
            <div   class="whatsappBot-form">
                
                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            Don't forget to add (0. Back) at the end of the menu text.
                    </div>
                </div>
                <!-- Main settings -->
                <div class="form-group col-lg-12 whatsapp-survey-form">
                    <label class="control-label col-lg-3">This submenu of:</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-tree7"></i></span>
                            
                            <select class="form-control bootstrap-select back_campaign_id" data-width="100%" name="back_campaign_id">
                                @foreach( App\Models\Campaigns::where('type', 'whatsappBot')->orWhere('type', 'whatsappFirstBot')->get() as $menu )
                                    <option @if($campaigns->back_campaign_id == $menu->id) selected @endif value="{{$menu->id}}"> {{$menu->campaign_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12 survey-types">
                    <label class="control-label col-lg-3">Name :</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="campaign_name" type="text" class="form-control input-xlg" value="{{ $campaigns->campaign_name }}" placeholder="">
                            <div class="form-control-feedback"><i class="icon-pencil6"></i></div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12 survey-types">
                    <label class="control-label col-lg-3">Menu text:</label>
                    <div class="col-lg-8">
                        <div class="form-group has-feedback has-feedback-left">
                        <!-- $campaigns->description -->
                            <textarea name="description" type="text" class="form-control input-xlg" dir="rtl" rows="5" placeholder="Example: How did our service make you feel?">{{ App\Models\Survey::where('campaign_id',$campaigns->id)->value('reply_message') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div class="form-group col-lg-12 edit-table-bordered whatsapp-survey-form">
                    <div class="table-responsive">
                        <div class="panel-body">
                            <button type="button" name="add" id="edit-add_load" class="btn btn-success"><i class="icon-plus2"></i></button>
                        </div>
                        <table class="table edit-table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic_load">
                            <thead> 
                                <tr>
                                    <th>Menu option</th>
                                    <th>Submenu</th>
                                    <th></th>
                                    <!-- <th>Type</th> -->
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\Survey::where('campaign_id',$campaigns->id)->whereNull('u_id')->whereNotNull('options')->get() as $option)
                                    <tr>
                                        <td>
                                            <input name="options[]" type="text" class="form-control input-xlg edit-load-ip" value="{{$option->options}}" style="width: 80px;" required>
                                        </td>

                                        <td>
                                            @if($option->is_reply == 1)
                                                <input type="hidden" name="is_reply[]" value="{{$option->is_reply}}">
                                                <textarea rows="4" name="reply_message[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thank you very much for your reply,">{{ $option->reply_message }}</textarea>
                                            @else
                                                <input type="hidden" name="is_reply[]" value="{{$option->is_reply}}">
                                                <input type="hidden" name="reply_message[]" value="{{$option->reply_message}}">
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <ul>
                                                @if(isset($option->next_campaign_id) and $option->next_campaign_id!="0")
                                                    <li>
                                                        <label class="control-label">Enter submenu:</label>
                                                        <select class="edit-select" name="next_campaign_id[]">
                                                            <option value="0"></option>
                                                            @foreach( App\Models\Campaigns::where('type', 'whatsappBot')->get() as $menu )
                                                                <option @if($option->next_campaign_id == $menu->id) selected @endif value="{{$menu->id}}"> {{$menu->campaign_name}} </option>
                                                            @endforeach
                                                        </select>
                                                    </li>
                                                @else
                                                    <input type="hidden" name="next_campaign_id[]" value="{{$option->next_campaign_id}}">
                                                @endif     
                                            </ul>
                                        </td>

                                        <td>
                                            <button type="button" name="remove" class="btn btn-danger survey-btn-remove" onclick="_delete({{$option->id}})" ><i class="icon-minus2"></i></button>
                                            <input type='hidden' name='option_id[]' value="{{$option->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>  
                
                <script>
                    $('#edit-add_load').click(function () {
                        var code = '<tbody> <tr id="row">\n';
                                                                            
                            code += '<td>\n';
                                code += '<input name="options[]" type="text" class="form-control input-xlg edit-load-ip" value="" style="width: 80px;" required> \n';
                                
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<input type="hidden" name="is_reply[]" value="1">';
                                code += '<textarea rows="4" name="reply_message[]" type="text" class="form-control input-xlg" style="width: 130px;" placeholder="Thank you very much for your reply,"></textarea>\n';
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<ul>\n';
                                    code += '<li>\n';
                                        code += '<label class="control-label">Enter submenu:</label>\n';
                                        code += '<select class="edit-select" name="next_campaign_id[]">\n';
                                            code += '<option value="0"></option>\n';
                                            @foreach( App\Models\Campaigns::where('type', 'whatsappBot')->get() as $menu )
                                                code += '<option value="{{$menu->id}}"> {{$menu->campaign_name}} </option>\n';
                                            @endforeach
                                        code += '</select>\n';
                                    code += '</li>\n';
                                code += '</ul>\n';
                            code += '</td>\n';

                            code += '<td>\n';
                                code += '<button type="button" name="remove" class="btn btn-danger survey-btn-remove" onclick="_delete" ><i class="icon-minus2"></i></button>\n';
                                code += '<input type="hidden" name="option_id[]" value="">\n';
                            code += '</td>\n';
                            
                            code += '</tr> </tbody>';
                        $('#edit-dynamic_load').append(code);
                        $('.edit-select-fixed-single85').select2({
                            minimumResultsForSearch: Infinity,
                            width: 85
                        });
                    });

                    $(document).on('click', '.survey-btn-remove', function () {
                        $(this).parent().parent().remove();
                    });

                    function _delete($id){
                        
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
                                    url:'surveyOptionDelete/'+$id,
                                    success:function(data) {

                                        $(this).parent().parent().remove();
                                        swal("Deleted!", "Option has been deleted, please refresh.", "success");
                                    },
                                    error:function(){
                                        swal("Cancelled", "this option is safe :)", "error");

                                    }
                                });
                            } else {
                                swal("Cancelled", "Your Cancelled :)", "success");
                            }
                        });
                    }
                </script>
                
            </div>

        </div>
     
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-whatsappBot'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif

@if($campaign_type == "publicHolidays")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab13">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-publicHolidays')) }}
        {{ csrf_field() }}
        <!-- Header of variables, upload, and download excel -->     
        <div class="panel-heading text-center">
            <h3 class="panel-title"><strong> <i class="icon-brain"></i>  Public Holidays Available Variables </strong></h3><br>
            <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@HOLIDAY_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@HOLIDAY_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@HOLIDAY_COUNTRY</span>
            <br><br>
            <div class="input-group ">
                <div class="input-group-btn">
                <div tabindex="500" class="btn btn-primary btn-file"><i class="icon-file-plus"></i> <span class="">Update Public Holidays Excel sheet</span><input name="publicHolidaysExcel[]" type="file" class="file-input2" ></div>
                </div>
            </div>
            <span class="help-block">Download current public holidays</span>
            <ul class="icons-list">
                <?php $split = explode('/', url()->full()); ?>
                <li><a href="http://{{$split[2]}}/upload/PublicHolidays.xlsx"><i class="icon-download"></i></a></li>
            </ul>
            <input type="hidden" name="type" value="publicHolidays">
        </div>

        <div class="panel-body">
            
            <div class="publicHolidays-form">
                
                <!-- Email Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysEmailSettingsAll"> <i class="icon-mail-read"></i> &nbsp Email notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-publicHolidaysEmailSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <!-- 1st Email reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysEmailSettings1st"> <i class="icon-bell3 icon-medal-first"></i> &nbsp 1st reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysEmailSettings1st" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidayEmailReminder1chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidayEmailReminder1chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidayEmailReminder1_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidayEmailReminder1')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidayEmailReminder1chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending email notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidayEmailReminder1 = App\Settings::where('type', 'sendPublicHolidayEmailReminder1')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidayEmailReminder1_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidayEmailReminder1 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Email reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysEmailSettings2nd"> <i class="icon-bell3 icon-medal-second"></i> &nbsp 2nd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysEmailSettings2nd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidayEmailReminder2chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidayEmailReminder2chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidayEmailReminder2_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidayEmailReminder2')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidayEmailReminder2chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending email notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidayEmailReminder2 = App\Settings::where('type', 'sendPublicHolidayEmailReminder2')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidayEmailReminder2_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidayEmailReminder2 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 3rd Email reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysEmailSettings3rd"> <i class="icon-bell3 icon-medal-third"></i> &nbsp 3rd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysEmailSettings3rd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidayEmailReminder3chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidayEmailReminder3chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidayEmailReminder3_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidayEmailReminder3')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidayEmailReminder3chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending email notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidayEmailReminder3 = App\Settings::where('type', 'sendPublicHolidayEmailReminder3')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidayEmailReminder3_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidayEmailReminder3 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- Whatsapp Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysWhatsappSettingsAll"> <h6 class="panel-title"> <img src="assets/images/whatsapp.png"> <img src="assets/images/whatsapp-logo.png"> notifications using Artificial Intelligence</a></h6>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-publicHolidaysWhatsappSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <!-- 1st Whatsapp reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysWhatsappSettings1st"> <i class="icon-bell3 icon-medal-first"></i> &nbsp 1st reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysWhatsappSettings1st" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidayWhatsappReminder1chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidayWhatsappReminder1chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidayWhatsappReminder1_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidayWhatsappReminder1')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidayWhatsappReminder1chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidayWhatsappReminder1 = App\Settings::where('type', 'sendPublicHolidayWhatsappReminder1')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidayWhatsappReminder1_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidayWhatsappReminder1 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Whatsapp reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysWhatsappSettings2nd"> <i class="icon-bell3 icon-medal-second"></i> &nbsp 2nd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysWhatsappSettings2nd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidayWhatsappReminder2chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidayWhatsappReminder2chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidayWhatsappReminder2_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidayWhatsappReminder2')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidayWhatsappReminder2chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidayWhatsappReminder2 = App\Settings::where('type', 'sendPublicHolidayWhatsappReminder2')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidayWhatsappReminder2_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidayWhatsappReminder2 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 3rd Whatsapp reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysWhatsappSettings3rd"> <i class="icon-bell3 icon-medal-third"></i> &nbsp 3rd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysWhatsappSettings3rd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidayWhatsappReminder3chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidayWhatsappReminder3chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidayWhatsappReminder3_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidayWhatsappReminder3')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidayWhatsappReminder3chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidayWhatsappReminder3 = App\Settings::where('type', 'sendPublicHolidayWhatsappReminder3')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidayWhatsappReminder3_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidayWhatsappReminder3 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- SMS Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysSMSSettingsAll"> <i class="icon-envelope"></i> &nbsp SMS notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-publicHolidaysSMSSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <!-- 1st SMS reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysSMSSettings1st"> <i class="icon-bell3 icon-medal-first"></i> &nbsp 1st reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysSMSSettings1st" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidaySMSReminder1chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidaySMSReminder1chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidaySMSReminder1_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidaySMSReminder1')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidaySMSReminder1chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidaySMSReminder1 = App\Settings::where('type', 'sendPublicHolidaySMSReminder1')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidaySMSReminder1_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidaySMSReminder1 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 2nd SMS reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysSMSSettings2nd"> <i class="icon-bell3 icon-medal-second"></i> &nbsp 2nd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysSMSSettings2nd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidaySMSReminder2chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidaySMSReminder2chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidaySMSReminder2_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidaySMSReminder2')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidaySMSReminder2chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidaySMSReminder2 = App\Settings::where('type', 'sendPublicHolidaySMSReminder2')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidaySMSReminder2_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidaySMSReminder2 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 3rd SMS reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-publicHolidaysSMSSettings3rd"> <i class="icon-bell3 icon-medal-third"></i> &nbsp 3rd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-publicHolidaysSMSSettings3rd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="sendPublicHolidaySMSReminder3chatGptContent" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'sendPublicHolidaySMSReminder3chatGptContent')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="sendPublicHolidaySMSReminder3_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'sendPublicHolidaySMSReminder3')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 sendPublicHolidaySMSReminder3chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                            </label>
                                            <?php $sendPublicHolidaySMSReminder3 = App\Settings::where('type', 'sendPublicHolidaySMSReminder3')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="sendPublicHolidaySMSReminder3_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($sendPublicHolidaySMSReminder3 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        
        
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-publicHolidays'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif

@if($campaign_type == "guestBirthdate")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab13">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-guestBirthdate')) }}
        {{ csrf_field() }}
        <!-- Header of variables-->     
        <div class="panel-heading text-center">
            <h3 class="panel-title"><strong> <i class="icon-brain"></i>  Guest Birthdate Available Variables </strong></h3><br>
            <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@HOLIDAY_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@HOLIDAY_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@HOLIDAY_COUNTRY</span>
            <input type="hidden" name="type" value="guestBirthdate">
        </div>

        <div class="panel-body">
            
            <div class="guestBirthdate-form">
                
                <!-- Email Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateEmailSettingsAll"> <i class="icon-mail-read"></i> &nbsp Email notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestBirthdateEmailSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <!-- 1st Email reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateEmailSettings1st"> <i class="icon-bell3 icon-medal-first"></i> &nbsp 1st reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateEmailSettings1st" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateEmailchatGptContent1" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateEmailchatGptContent1')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateEmail1_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateEmail1')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateEmail1chatGptContent">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending email notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateEmail1 = App\Settings::where('type', 'guestBirthdateEmail1')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateEmail1_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateEmail1 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Email reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateEmailSettings2nd"> <i class="icon-bell3 icon-medal-second"></i> &nbsp 2nd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateEmailSettings2nd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateEmailchatGptContent2" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateEmailchatGptContent2')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateEmail2_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateEmail2')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateEmailchatGptContent2">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending email notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateEmail2 = App\Settings::where('type', 'guestBirthdateEmail2')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateEmail2_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateEmail2 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 3rd Email reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateEmailSettings3rd"> <i class="icon-bell3 icon-medal-third"></i> &nbsp 3rd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateEmailSettings3rd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateEmailchatGptContent3" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateEmailchatGptContent3')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateEmail3_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateEmail3')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateEmailchatGptContent3">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending email notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateEmail3 = App\Settings::where('type', 'guestBirthdateEmail3')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateEmail3_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateEmail3 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- Whatsapp Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateWhatsappSettingsAll"> <h6 class="panel-title"> <img src="assets/images/whatsapp.png"> <img src="assets/images/whatsapp-logo.png"> notifications using Artificial Intelligence</a></h6>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestBirthdateWhatsappSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <!-- 1st Whatsapp reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateWhatsappSettings1st"> <i class="icon-bell3 icon-medal-first"></i> &nbsp 1st reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateWhatsappSettings1st" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateWhatsappchatGptContent1" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateWhatsappchatGptContent1')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateWhatsapp1_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateWhatsapp1')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateWhatsappchatGptContent1">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateWhatsapp1 = App\Settings::where('type', 'guestBirthdateWhatsapp1')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateWhatsapp1_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateWhatsapp1 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Whatsapp reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateWhatsappSettings2nd"> <i class="icon-bell3 icon-medal-second"></i> &nbsp 2nd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateWhatsappSettings2nd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateWhatsappchatGptContent2" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateWhatsappchatGptContent2')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateWhatsapp2_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateWhatsapp2')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateWhatsappchatGptContent2">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateWhatsapp2 = App\Settings::where('type', 'guestBirthdateWhatsapp2')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateWhatsapp2_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateWhatsapp2 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 3rd Whatsapp reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateWhatsappSettings3rd"> <i class="icon-bell3 icon-medal-third"></i> &nbsp 3rd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateWhatsappSettings3rd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateWhatsappchatGptContent3" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateWhatsappchatGptContent3')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateWhatsapp3_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateWhatsapp3')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateWhatsappchatGptContent3">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateWhatsapp3 = App\Settings::where('type', 'guestBirthdateWhatsapp3')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateWhatsapp3_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateWhatsapp3 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- SMS Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateSMSSettingsAll"> <i class="icon-envelope"></i> &nbsp SMS notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestBirthdateSMSSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <!-- 1st SMS reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateSMSSettings1st"> <i class="icon-bell3 icon-medal-first"></i> &nbsp 1st reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateSMSSettings1st" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateSMSchatGptContent1" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateSMSchatGptContent1')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateSMS1_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateSMS1')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateSMSchatGptContent1">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateSMS1 = App\Settings::where('type', 'guestBirthdateSMS1')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateSMS1_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateSMS1 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 2nd SMS reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateSMSSettings2nd"> <i class="icon-bell3 icon-medal-second"></i> &nbsp 2nd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateSMSSettings2nd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateSMSchatGptContent2" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateSMSchatGptContent2')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateSMS2_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateSMS2')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateSMSchatGptContent2">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateSMS2 = App\Settings::where('type', 'guestBirthdateSMS2')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateSMS2_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateSMS2 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 3rd SMS reminder Settings -->  
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestBirthdateSMSSettings3rd"> <i class="icon-bell3 icon-medal-third"></i> &nbsp 3rd reminder</a>
                                    </h6>
                                </div>
                                <div id="edit-accordion-control-right-guestBirthdateSMSSettings3rd" class="panel-collapse collapse">
                                    <div class="panel-body">

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">AI content 
                                            <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea name="guestBirthdateSMSchatGptContent3" type="text" class="form-control input-xlg" rows="4" placeholder="">{{ App\Settings::where('type', 'guestBirthdateSMSchatGptContent3')->value('value') }}</textarea>
                                                    <div class="form-control-feedback">
                                                    &nbsp <i class="icon-brain"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="control-label col-lg-4">Days before
                                                <span class="help-block">Send reminder before the number of days.</span> 
                                            </label>
                                            <div class="col-lg-8">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input name="guestBirthdateSMS3_beforeDays" style="width: 100px;" type="number" class="form-control input-xlg" value="{{ App\Settings::where('type', 'guestBirthdateSMS3')->value('value') }}"> 
                                                    <div class="form-control-feedback">
                                                        <i class="icon-history"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 guestBirthdateSMSchatGptContent3">
                                            <label class="control-label col-lg-4">Enabled
                                                <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                            </label>
                                            <?php $guestBirthdateSMS3 = App\Settings::where('type', 'guestBirthdateSMS3')->value('state');?>
                                            <div class="col-lg-8">
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="guestBirthdateSMS3_state" class="switch"
                                                                data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                                @if($guestBirthdateSMS3 == "1") checked @endif >
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        
        
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-guestBirthdate'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif

@if($campaign_type == "guestCheckin")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab13">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-guestCheckin')) }}
        {{ csrf_field() }}
        <!-- Header of variables, upload, and download excel -->     
        <div class="panel-heading text-center">
            <h3 class="panel-title"><strong> <i class="icon-brain"></i>  Check-in Infolettre Available Variables </strong></h3><br>
            <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span>
            <input type="hidden" name="type" value="guestCheckin">
        </div>

        <div class="panel-body">
            
            <div class="guestCheckin-form">
                
                <!-- Email Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestCheckinEmailSettingsAll"> <i class="icon-mail-read"></i> &nbsp Email notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestCheckinEmailSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">AI content 
                                <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="guestCheckinEmail" type="text" class="form-control input-xlg" rows="10" placeholder="">{{ App\Settings::where('type', 'guestCheckinEmail')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-brain"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 guestCheckinEmail">
                                <label class="control-label col-lg-4">Enabled
                                    <span class="help-block">Enable/Disable sending email notifications.</span> 
                                </label>
                                <?php $guestCheckinEmail = App\Settings::where('type', 'guestCheckinEmail')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="guestCheckinEmail_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($guestCheckinEmail == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Whatsapp Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestCheckinWhatsappSettingsAll"> <h6 class="panel-title"> <img src="assets/images/whatsapp.png"> <img src="assets/images/whatsapp-logo.png"> notifications using Artificial Intelligence</a></h6>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestCheckinWhatsappSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">AI content 
                                <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="guestCheckinWhatsapp" type="text" class="form-control input-xlg" rows="5" placeholder="">{{ App\Settings::where('type', 'guestCheckinWhatsapp')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-brain"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
      
                            <div class="form-group col-lg-12 guestCheckinWhatsapp">
                                <label class="control-label col-lg-4">Enabled
                                    <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                </label>
                                <?php $guestCheckinWhatsapp = App\Settings::where('type', 'guestCheckinWhatsapp')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="guestCheckinWhatsapp_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($guestCheckinWhatsapp == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>

                <!-- SMS Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestCheckinSMSSettingsAll"> <i class="icon-envelope"></i> &nbsp SMS notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestCheckinSMSSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">AI content 
                                <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="guestCheckinSMS" type="text" class="form-control input-xlg" rows="5" placeholder="">{{ App\Settings::where('type', 'guestCheckinSMS')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-brain"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 guestCheckinSMS">
                                <label class="control-label col-lg-4">Enabled
                                    <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                </label>
                                <?php $guestCheckinSMS = App\Settings::where('type', 'guestCheckinSMS')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="guestCheckinSMS_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($guestCheckinSMS == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        
        
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-guestCheckin'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif

@if($campaign_type == "guestCheckout")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab13">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-guestCheckout')) }}
        {{ csrf_field() }}
        <!-- Header of variables, upload, and download excel -->     
        <div class="panel-heading text-center">
            <h3 class="panel-title"><strong> <i class="icon-brain"></i>  Check-Out Letter Available Variables </strong></h3><br>
            <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_Checkout_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span>
            <input type="hidden" name="type" value="guestCheckout">
        </div>

        <div class="panel-body">
            
            <div class="guestCheckout-form">
                
                <!-- Email Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestCheckoutEmailSettingsAll"> <i class="icon-mail-read"></i> &nbsp Email notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestCheckoutEmailSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">AI content 
                                <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate email content.</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="guestCheckoutEmail" type="text" class="form-control input-xlg" rows="5" placeholder="">{{ App\Settings::where('type', 'guestCheckoutEmail')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-brain"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 guestCheckoutEmail">
                                <label class="control-label col-lg-4">Enabled
                                    <span class="help-block">Enable/Disable sending email notifications.</span> 
                                </label>
                                <?php $guestCheckoutEmail = App\Settings::where('type', 'guestCheckoutEmail')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="guestCheckoutEmail_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($guestCheckoutEmail == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Whatsapp Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestCheckoutWhatsappSettingsAll"> <h6 class="panel-title"> <img src="assets/images/whatsapp.png"> <img src="assets/images/whatsapp-logo.png"> notifications using Artificial Intelligence</a></h6>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestCheckoutWhatsappSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">AI content 
                                <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate Whatsapp content.</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="guestCheckoutWhatsapp" type="text" class="form-control input-xlg" rows="5" placeholder="">{{ App\Settings::where('type', 'guestCheckoutWhatsapp')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-brain"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
      
                            <div class="form-group col-lg-12 guestCheckoutWhatsapp">
                                <label class="control-label col-lg-4">Enabled
                                    <span class="help-block">Enable/Disable sending Whatsapp notifications.</span> 
                                </label>
                                <?php $guestCheckoutWhatsapp = App\Settings::where('type', 'guestCheckoutWhatsapp')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="guestCheckoutWhatsapp_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($guestCheckoutWhatsapp == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>

                <!-- SMS Settings -->            
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#edit-accordion-control-right-guestCheckoutSMSSettingsAll"> <i class="icon-envelope"></i> &nbsp SMS notifications using Artificial Intelligence</a>
                        </h6>
                    </div>
                    
                    <div id="edit-accordion-control-right-guestCheckoutSMSSettingsAll" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="form-group col-lg-12">
                                <label class="control-label col-lg-4">AI content 
                                <span class="help-block">Write a prompt that sends to the AI (ChatGPT, Bard, etc...) to generate SMS content.</span>
                                </label>
                                <div class="col-lg-8">
                                    <div class="form-group has-feedback has-feedback-left">
                                        <textarea name="guestCheckoutSMS" type="text" class="form-control input-xlg" rows="5" placeholder="">{{ App\Settings::where('type', 'guestCheckoutSMS')->value('value') }}</textarea>
                                        <div class="form-control-feedback">
                                        &nbsp <i class="icon-brain"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 guestCheckoutSMS">
                                <label class="control-label col-lg-4">Enabled
                                    <span class="help-block">Enable/Disable sending SMS notifications.</span> 
                                </label>
                                <?php $guestCheckoutSMS = App\Settings::where('type', 'guestCheckoutSMS')->value('state');?>
                                <div class="col-lg-8">
                                    <div class="checkbox checkbox-switch">
                                        <label>
                                            <input type="checkbox" name="guestCheckoutSMS_state" class="switch"
                                                    data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="default"
                                                    @if($guestCheckoutSMS == "1") checked @endif >
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        
        
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-guestCheckout'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif

@if($campaign_type == "animationProgram")
    <!-- General -->
    <div class="panel panel-flat tab-pane" id="tab13">
        {{ Form::open(array('url' => 'update_campaign/'.$campaigns->id, 'files' => true, 'method' => 'post', 'id' => 'edit-animationProgram')) }}
        {{ csrf_field() }}
        <!-- Header of variables, upload, and download excel -->     
        <div class="panel-heading text-center">
            <h3 class="panel-title"><strong> <i class="icon-brain"></i>  Animation Program Available Variables </strong></h3><br>
            <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span>
            <br><br>
            <div class="input-group ">
                <div class="input-group-btn">
                <div tabindex="500" class="btn btn-primary btn-file"><i class="icon-file-plus"></i> <span class="">Update Animation Program Excel sheet</span><input name="AnimationProgramScheduleExcel[]" type="file" class="file-input2" ></div>
                </div>
            </div>
            <span class="help-block">Download current Animation Program</span>
            <ul class="icons-list">
                <?php $split = explode('/', url()->full()); ?>
                <li><a href="http://{{$split[2]}}/upload/AnimationProgramSchedule.xlsx"><i class="icon-download"></i></a></li>
            </ul>
            <input type="hidden" name="type" value="animationProgram">

        </div>
                
        <div class="panel-body">
            
            <div class="animationProgram-form">
                    
                <div class="alert alert-info alert-styled-left alert-arrow-left alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        Download the Animation program Excel sheet template, then write a prompt that sends to the AI platform (ChatGPT, Bard, etc...) to generate an email, WhatsApp, SMS content,
                        or If you need to avoid AI, fill final message columns.
                </div>


                <table class="table edit-table-bordered" data-toggle="context" data-target=".context-table" id="edit-dynamic_loadXXX">
                    <thead> 
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email content</th>
                            <th>Whatsapp content</th>
                            <th>SMS content</th>
                            <!-- <th>Type</th> -->
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\AnimationProgramSchedule::get() as $animation)
                            <tr>
                                <td>
                                    <label class="control-label">{{$animation->notification_day}} at {{$animation->notification_time}}</label>
                                </td>

                                <td>
                                    <label class="control-label">{{$animation->notification_name}}</label>
                                </td>

                                <td>
                                    @if( isset($animation->final_email_without_ai) and strlen($animation->final_email_without_ai) > 25  )
                                        <!-- <textarea rows="4" type="text" class="form-control input-xlg"  disabled>{{ $animation->final_email_without_ai }}</textarea> -->
                                        <i class="icon-quill4" title="Traditional content without AI"></i><label class="control-label">{{$animation->final_email_without_ai}}</label>
                                    @else
                                        <!-- <textarea rows="4" type="text" class="form-control input-xlg"  disabled>{{ $animation->ai_email_content }}</textarea> -->
                                        <i class="icon-brain" title="AI content"></i><label class="control-label">{{$animation->ai_email_content}}</label>
                                    @endif
                                </td>

                                <td>
                                    @if( isset($animation->final_whatsapp_without_ai) and strlen($animation->final_whatsapp_without_ai) > 25  )
                                        <!-- <textarea rows="4" type="text" class="form-control input-xlg"  disabled>{{ $animation->final_whatsapp_without_ai }}</textarea> -->
                                        <i class="icon-quill4" title="Traditional content without AI"></i><label class="control-label">{{$animation->final_whatsapp_without_ai}}</label>
                                    @else
                                        <!-- <textarea rows="4" type="text" class="form-control input-xlg"  disabled>{{ $animation->ai_whatsapp_content }}</textarea> -->
                                        <i class="icon-brain" title="AI content"></i><label class="control-label">{{$animation->ai_whatsapp_content}}</label>
                                    @endif
                                </td>

                                <td>
                                    @if( isset($animation->final_sms_without_ai) and strlen($animation->final_sms_without_ai) > 25  )
                                        <!-- <textarea rows="4" type="text" class="form-control input-xlg"  disabled>{{ $animation->final_sms_without_ai }}</textarea> -->
                                        <i class="icon-quill4" title="Traditional content without AI"></i><label class="control-label">{{$animation->final_sms_without_ai}}</label>
                                    @else
                                        <!-- <textarea rows="4" type="text" class="form-control input-xlg"  disabled>{{ $animation->ai_sms_content }}</textarea> -->
                                        <i class="icon-brain" title="AI content"></i><label class="control-label">{{$animation->ai_sms_content}}</label>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
        
        
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary"
                    onclick="document.forms['edit-animationProgram'].submit(); return false;">Save changes
            </button>
        </div>
    </div>
@endif

<script type="text/javascript" src="assets/js/plugins/uploaders/fileinput.min.js"></script>
<script type="text/javascript" src="assets/js/pages/components_popups.js"></script>

<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>


<script>

    @if(App\Models\CampaignsStatisticsMonths::where('campaign_id',$id)->count() != 0)
    $(function() {
        // ------------------------------
        require.config({
            paths: {
                echarts: 'assets/js/plugins/visualization/echarts'
            }
        });


        // Configuration
        // ------------------------------

        require(
            [
                'echarts',
                'echarts/theme/limitless',
                'echarts/chart/bar',
                'echarts/chart/line'
            ],
            // Charts setup
            function (ec, limitless) {


                // Initialize charts
                // ------------------------------
                var basic_area = ec.init(document.getElementById('basic_area'), limitless);

                //
                // Basic area options
                //

                <?php
                    $Months = App\Models\CampaignsStatisticsMonths::where('campaign_id',$id)->get();
                    if(isset($Months) and count($Months)>0){
                        $MonthsCounter=count($Months);  ?>
                basic_area_options = {

                    // Setup timeline
                    timeline: {
                        data: [
                            <?php 
                            $justCounter2=0;
                            foreach($Months as $currMonth)
                            {
                                $justCounter2++;
                                echo "'".$currMonth->month."'";
                                if($justCounter2!=$MonthsCounter){echo ",";}
                            }
                            ?>
                        ],
                        x: 10,
                        x2: 10,
                        label: {
                            formatter: function(s) {
                                return s.slice(0, 10);
                            }
                        },
                        autoPlay: true,
                        playInterval: 3000
                    },

                    // Main options
                    options: [
                        {

                            // Setup grid
                            grid: {
                                x: 55,
                                x2: 110,
                                y: 35,
                                y2: 100
                            },

                            // Add tooltip
                            tooltip: {
                                trigger: 'axis'
                            },

                            // Add legend
                            legend: {
                                @if($campaign_type == "survey" and $campaigns->survey_type == "poll")
                                    data: [@foreach($campaigns_survey as $value) '{{$value->options}}', @endforeach]
                                @elseif($campaign_type == "survey" and $campaigns->survey_type == "rating")
                                <?php $ratingArray=[0.5,1,1.5,2,2.5,3,3.5,4,4.5,5]; ?>
                                    data: [@foreach($ratingArray as $rate) '{{$rate}}', @endforeach]
                                @elseif($campaign_type == "sms" or $campaign_type == "mail" or $campaign_type == "loyalty") 
                                    data: ['Reach']
                                @else
                                    data: ['Impressions', 'Clicks', 'Reach']
                                @endif
                            },

                            // Add toolbox
                            toolbox: {
                                show: true,
                                orient: 'vertical',
                                x: 'right',
                                y: 70,
                                feature: {
                                    mark: {
                                        show: true,
                                        title: {
                                            mark: 'Markline switch',
                                            markUndo: 'Undo markline',
                                            markClear: 'Clear markline'
                                        }
                                    },
                                    dataView: {
                                        show: true,
                                        readOnly: false,
                                        title: 'View data',
                                        lang: ['View chart data', 'Close', 'Update']
                                    },
                                    magicType: {
                                        show: true,
                                        title: {
                                            line: 'Switch to line chart',
                                            bar: 'Switch to bar chart',
                                            stack: 'Switch to stack',
                                            tiled: 'Switch to tiled'
                                        },
                                        type: ['line', 'bar', 'stack', 'tiled']
                                    },
                                    restore: {
                                        show: true,
                                        title: 'Restore'
                                    },
                                    saveAsImage: {
                                        show: true,
                                        title: 'Same as image',
                                        lang: ['Save']
                                    }
                                }
                            },

                            // Enable drag recalculate
                            calculable: true,

                            // Horizontal axis
                            xAxis: [{
                                type: 'category',
                                axisLabel: {
                                    interval: 0
                                },
                                data: [
                                    <?php 
                                    for($i=1;$i<=31;$i++)
                                    {
                                        echo "'".$i."'";
                                        if($i!=31){echo ",";}
                                    }
                                    ?>
                                    ]
                            }],

                            // Vertical axis
                            yAxis: [
                                {
                                    type: 'value',
                                    name: ''
                                },
                                {
                                    type: 'value',
                                    name: ''
                                }
                            ],

                            // Add series
                            // fourth week
                            <?php 
                                $Counter=0;
                                foreach($Months as $currMonth)
                                {
                                    $Counter++;
                                    if($Counter==1){
                                         /////////////////////////////////// Pool ////////////////////////////////////
                                        if($campaign_type == "survey" and $campaigns->survey_type == "poll"){ ?>
                                        series: [
                                           <?php foreach($campaigns_survey as $value): ?>
                                            {
                                                name: '{{$value->options}}',
                                                type: 'line',
                                                smooth: true,
                                                itemStyle: {normal: {areaStyle: {type: 'default'}}},
                                                data: [
                                                    <?php  
                                                  
                                                        $allDayData=App\Models\CampaignsStatisticsDays::where(['campaign_id' => $id, 'survey_id' => $value->id])->where('month', $currMonth->month)->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){

                                                                if($record->operation == "campaigns_survey_poll")
                                                                {
                                                                    if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                                    $finalValue[$record->day]++;
                                                                }
                                                            }
                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                          
                                                     ?>
                                                ]
                                            },
                                            <?php endforeach;?>
                                        ]
                                        <?php /////////////////////////////////// Rating ///////////////////////////////////
                                        }elseif($campaign_type == "survey" and $campaigns->survey_type == "rating")
                                        {
                                            echo "series: [";
                                            $ratingArray=[0.5,1,1.5,2,2.5,3,3.5,4,4.5,5];
                                            
                                            foreach($ratingArray as $rate): ?>
                                            {
                                                name: '{{$rate}}',
                                                type: 'line',
                                                smooth: true,
                                                itemStyle: {normal: {areaStyle: {type: 'default'}}},
                                                data: [
                                                    <?php  
                                                  
                                                        $allDayData=App\Models\CampaignsStatisticsDays::where(['campaign_id' => $id, 'survey_id' => $rate])->where('month', $currMonth->month)->get();
                                                            if(isset($finalValue)){unset($finalValue);}
                                                            foreach($allDayData as $record){

                                                                if($record->operation == "campaigns_survey_rating")
                                                                {
                                                                    if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                                    $finalValue[$record->day]++;
                                                                }
                                                            }
                                                                for($i=1;$i<=31;$i++)
                                                                {
                                                                    echo "'";
                                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                                    
                                                                    if(isset($finalValue[$number])){
                                                                        echo $finalValue[$number];
                                                                    }else{echo "0";}
                                                                    echo "'";
                                                                    if($i!="31"){echo ",";}
                                                                }
                                                          
                                                     ?>
                                                ]
                                            },
                                            <?php endforeach;
                                            echo "]";// end series
                                        /////////////////////////////////// SMS Campaign - E-Mail ///////////////////////////////////
                                        }elseif($campaign_type == "sms" or $campaign_type == "mail" or $campaign_type == "loyalty")
                                        {
                                                     
                                        $allDayData= App\Models\CampaignsStatisticsDays::where('campaign_id',$id)->where('month', $currMonth->month)->get();
                                        $reachData = App\Models\CampaignsStatisticsReach::where('campaign_id',$id)->where('month', $currMonth->month)->get();

                                        if(isset($finalValue)){unset($finalValue);}
                                        //$totalCampaignViews=0;

                                        foreach($reachData as $record){
                                            if($record->type == "reach")
                                            {
                                                if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                else{$finalValue[$record->day]++;}
                                            }
                                        }

                                        ?>
                                        series: [
                                            {
                                                name: 'Reach',
                                                type: 'line',
                                                yAxisIndex: 1,
                                                data: [
                                                    <?php 
                                                    
                                                        for($i=1;$i<=31;$i++)
                                                        {
                                                            echo "'";
                                                            if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                            
                                                            if(isset($finalValue[$number])){
                                                                echo $finalValue[$number];
                                                            }else{echo "0";}
                                                            echo "'";
                                                            if($i!="31"){echo ",";}
                                                        }
                                                       
                                                    ?>
                                                    ]
                                            }
                                        ]
                                        /////////////////////////////////// Normal ////////////////////////////////////
                                        //else:
                                        <?php
                                        }else{ ?>
                                        <?php         
                                        $allDayData= App\Models\CampaignsStatisticsDays::where('campaign_id',$id)->where('month', $currMonth->month)->get();
                                        $reachData = App\Models\CampaignsStatisticsReach::where('campaign_id',$id)->where('month', $currMonth->month)->get();

                                        if(isset($finalValue)){unset($finalValue);}
                                        //$totalCampaignViews=0;
                                        foreach($allDayData as $record){
                                            if($record->operation == "campaigns_views")
                                            {
                                                if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                $finalValue[$record->day]++;
                                            }
                                            
                                            //$totalCampaignViews+=count($record->operation == "campaigns_views");
                                        }
                                        ?>
                                        series: [
                                            {
                                                name: 'Impressions',
                                                type: 'line',
                                                yAxisIndex: 1,
                                                data: [
                                                    <?php 
                                                    
                                                        for($i=1;$i<=31;$i++)
                                                        {
                                                            echo "'";
                                                            if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                            
                                                            if(isset($finalValue[$number])){
                                                                echo $finalValue[$number];
                                                            }else{echo "0";}
                                                            echo "'";
                                                            if($i!="31"){echo ",";}
                                                        }
                                                       
                                                    ?>
                                                    ]
                                            },
                                            {
                                                name: 'Clicks',
                                                yAxisIndex: 1,
                                                type: 'bar',
                                                data: [
                                                    <?php
                                                    if(isset($finalValue)){unset($finalValue);}
                                                    foreach($allDayData as $record){

                                                        if($record->operation == "campaigns_clicks")
                                                        {
                                                            if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                            $finalValue[$record->day]++;
                                                        }
                                                        //$finalValue[$record->day] = count($record->operation == "campaigns_clicks");
                                                    }

                                                    for($i=1;$i<=31;$i++)
                                                    {
                                                        echo "'";
                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                        
                                                        if(isset($finalValue[$number])){
                                                            echo $finalValue[$number];
                                                        }else{echo "0";}
                                                        echo "'";
                                                        if($i!="31"){echo ",";}
                                                    }
                                                    
                                                    ?>
                                                    ]
                                            },
                                            {
                                                name: 'Reach',
                                                yAxisIndex: 1,
                                                type: 'bar',
                                                data: [
                                                    <?php 
                                                        if(isset($finalValue)){unset($finalValue);}
                                                        foreach($reachData as $record){
                                                        if($record->type == "reach")
                                                        {
                                                            if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                            else{$finalValue[$record->day]++;}
                                                        }
                                                        //$finalValue[$record->day] = $record->days;
                                                        }

                                                    for($i=1;$i<=31;$i++)
                                                    {
                                                        echo "'";
                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                        
                                                        if(isset($finalValue[$number])){
                                                            echo $finalValue[$number];
                                                        }else{echo "0";}
                                                        echo "'";
                                                        if($i!="31"){echo ",";}
                                                    }
                                                    
                                                    ?>
                                                    ]
                                            }
                                        ]
                                        <?php //endif; 
                                        }?>
                                    
                        },

                                    <?php
                                    }else{
                                        ?>
                                        {
                                            <?php ///////////////////////////////////  Poll ////////////////////////////////////
                                            if($campaign_type == "survey" and $campaigns->survey_type == "poll"): ?>
                                            series: [
                                                <?php 
                                                foreach ($campaigns_survey as $key => $value) {
                                                   
                                                   echo "{data: [";
                                                    $allDayData = App\Models\CampaignsStatisticsDays::where(['campaign_id' => $id, 'survey_id' => $value->id])->where('month', $currMonth->month)->get();
                                                    if(isset($finalValue)){unset($finalValue);}
                                                    foreach($allDayData as $record){
                                                        if($record->operation == "campaigns_survey_poll")
                                                        {
                                                            if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                            $finalValue[$record->day]++;
                                                        }
                                                        //$finalValue[$record->day] = App\Models\Survey::where(['options' => $record->survey_id, 'campaign_id' => $id])->count();
                                                    }
                                                    
                                                    for($i=1;$i<=31;$i++)
                                                    {
                                                        echo "'";
                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                            if(isset($finalValue[$number])){
                                                                echo $finalValue[$number];
                                                            }else{echo "0";}
                                                        echo "'";
                                                        if($i!="31"){echo ",";}
                                                    }
                                                    echo "]},";
                                                }
                                                ?>
                                               
                                            ]
                                            <?php 
                                            /////////////////////////////////// rating ////////////////////////////////////
                                            elseif($campaign_type == "survey" and $campaigns->survey_type == "rating"): ?>                                           
                                            series: [
                                                <?php 
                                                $ratingArray=[0.5,1,1.5,2,2.5,3,3.5,4,4.5,5];
                                                foreach ($ratingArray as $rate) {
                                                   
                                                    echo "{data: [";
                                                    $allDayData = App\Models\CampaignsStatisticsDays::where(['campaign_id' => $id, 'survey_id' => $rate])->where('month', $currMonth->month)->get();
                                                    if(isset($finalValue)){unset($finalValue);}
                                                    foreach($allDayData as $record){
                                                        if($record->operation == "campaigns_survey_rating")
                                                        {
                                                            if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                            $finalValue[$record->day]++;
                                                        }
                                                        //$finalValue[$record->day] = App\Models\Survey::where(['options' => $record->survey_id, 'campaign_id' => $id])->count();
                                                    }
                                                    
                                                    for($i=1;$i<=31;$i++)
                                                    {
                                                        echo "'";
                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                            if(isset($finalValue[$number])){
                                                                echo $finalValue[$number];
                                                            }else{echo "0";}
                                                        echo "'";
                                                        if($i!="31"){echo ",";}
                                                    }
                                                    echo "]},";
                                                }
                                                ?>
                                            ]
                                             <?php 
                                            /////////////////////////////////// SMS - E-Mail ////////////////////////////////////
                                            elseif($campaign_type == "sms" or $campaign_type == "mail" or $campaign_type == "loyalty"): ?> 
                                            series: [
                                                <?php
                                                $allDayData = App\Models\CampaignsStatisticsDays::where('campaign_id',$id)->where('month', $currMonth->month)->get();
                                                $reachData = App\Models\CampaignsStatisticsReach::where('campaign_id',$id)->where('month', $currMonth->month)->get();
                                                echo "{data: [";
                                                if(isset($finalValue)){unset($finalValue);}
                                                foreach($reachData as $record){
                                                    if($record->type == "reach")
                                                    {
                                                        if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                        else{$finalValue[$record->day]++;}
                                                    }
                                                }
                                                for($i=1;$i<=31;$i++)
                                                    {
                                                        echo "'";
                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                        
                                                       if(isset($finalValue[$number])){
                                                            echo $finalValue[$number];
                                                        }else{echo "0";}
                                                        echo "'";
                                                        if($i!="31"){echo ",";}
                                                    }
                                                
                                                echo "]},";
                                                ?>
                                               
                                            ]
                                            <?php /////////////////////////////////// Normal ////////////////////////////////////
                                            else: ?>
                                            series: [
                                                <?php 
                                                
                                                echo "{data: [";
                                                    $allDayData = App\Models\CampaignsStatisticsDays::where('campaign_id',$id)->where('month', $currMonth->month)->get();
                                                    $reachData = App\Models\CampaignsStatisticsReach::where('campaign_id',$id)->where('month', $currMonth->month)->get();

                                                //////////////////////////////////// campaigns_views

                                                if(isset($finalValue)){unset($finalValue);}
                                                foreach($allDayData as $record){
                                                    if($record->operation == "campaigns_views")
                                                        {
                                                            if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                            $finalValue[$record->day]++;
                                                        }
                                                    //$finalValue[$record->day] = count($record->operation == "campaigns_views");
                                                }
                                                
                                                for($i=1;$i<=31;$i++)
                                                {
                                                    echo "'";
                                                    if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                        if(isset($finalValue[$number])){
                                                            echo $finalValue[$number];
                                                        }else{echo "0";}
                                                    echo "'";
                                                    if($i!="31"){echo ",";}
                                                }
                                                echo "]},";

                                                //////////////////////////////////// campaigns_clicks

                                                echo "{data: [";
                                                if(isset($finalValue)){unset($finalValue);}
                                                foreach($allDayData as $record){
                                                    if($record->operation == "campaigns_clicks")
                                                        {
                                                            if(!isset($finalValue[$record->day])){$finalValue[$record->day]=0;}
                                                            $finalValue[$record->day]++;
                                                        }
                                                    //$finalValue[$record->day] = count($record->operation == "campaigns_clicks");
                                                }
                                                for($i=1;$i<=31;$i++)
                                                    {
                                                        echo "'";
                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                        
                                                        if(isset($finalValue[$number])){
                                                            echo $finalValue[$number];
                                                        }else{echo "0";}
                                                        echo "'";
                                                        if($i!="31"){echo ",";}
                                                    }
                                                 
                                                echo "]},";
                                                //////////////////////////////////// Reach
                                                echo "{data: [";
                                                if(isset($finalValue)){unset($finalValue);}
                                                foreach($reachData as $record){
                                                    if($record->type == "reach")
                                                        {
                                                            if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
                                                            else{$finalValue[$record->day]++;}
                                                        }
                                                    //$finalValue[$record->day] = $record->days;
                                                }
                                                for($i=1;$i<=31;$i++)
                                                    {
                                                        echo "'";
                                                        if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                                                        
                                                       if(isset($finalValue[$number])){
                                                            echo $finalValue[$number];
                                                        }else{echo "0";}
                                                        echo "'";
                                                        if($i!="31"){echo ",";}
                                                    }
                                                
                                                echo "]},";
                                                ?>
                                               
                                            ]
                                            <?php endif; ?>
                                        }
                                        <?php
                                        if($Counter != $MonthsCounter){echo ",";}
                                    }// end else if($justCounter4==1)
                                 
                                }// end for each
                                ?>

                    ]
                };

                <?php } ?>
                
                // Apply options
                // ------------------------------
                basic_area.setOption(basic_area_options);

                // Resize charts
                // ------------------------------
                window.onresize = function () {
                    setTimeout(function () {

                        basic_area.resize();

                    }, 200);
                }
            }
        );
    });
    @else

    @endif
    function Perview() {
        var selectBox = document.getElementById("selectBoxs");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        window.open("http://" + selectedValue + '/index.blade.php', '_blank');
        
        //alert(selectedValue);
    }  

    // Basic initialization
    $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default'
    });
    // Basic example
    $('.file-input').fileinput({
        browseLabel: 'Browse',
        browseIcon: '<i class="icon-file-plus"></i>',
        uploadIcon: '<i class="icon-file-upload2"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>'
        },
        initialPreview: [
            @foreach(App\Media::where('type', 'campaigns')->where('campaign_id', $campaigns->id)->get() as $campaigns)
                    "<img src='{{ asset('') }}/upload/campaigns/{{ $campaigns->file }}' alt='' style='width:auto;height:160px;'>",
            @endforeach
        ],
        maxFilesNum: 10,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "bmp"],
        initialCaption: "No file selected",
        overwriteInitial: false,
    });
    $('.pickadate').pickadate();

    // Primary
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-primary-600 text-primary-800'
    });

    // Success
    $(".control-success").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-success-600 text-success-800'
    });

    // Fixed width. Single select
    $('.select-fixed-singles').select2({
        minimumResultsForSearch: Infinity,
        width: 250
    });


    // Basic initialization
    $('.tokenfield').tokenfield();

    // Tabs
    // -------------------------

    // Basic example
    $(".jui-tabs-basic").tabs();
    // Time limits
    $('.pickatime-limits').pickatime({
        format: 'H:i A',
        formatSubmit: 'HH:i',
        hiddenName: true
    });

    // Bootstrap switch
    // ------------------------------

    $(".switch").bootstrapSwitch();
    $('.day-parting').on('switchChange.bootstrapSwitch', function (event, state) {

        if (state === true) {
            $('.start').show();
            $('.end').show();
            $('.sun').show();
            $('.mon').show();
            $('.tue').show();
            $('.wed').show();
            $('.thu').show();
            $('.fri').show();
            $('.sat').show();
        } else {
            $('.start').hide();
            $('.end').hide();
            $('.sun').hide();
            $('.mon').hide();
            $('.tue').hide();
            $('.wed').hide();
            $('.thu').hide();
            $('.fri').hide();
            $('.sat').hide();
        }
    });
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).is(":checked")){
            $('.option-skip-value-edit').show();
            }
            else if($(this).is(":not(:checked)")){
            $('.option-skip-value-edit').hide();
            }
        });
    });
    $('.social-offer').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.offer-promocode').hide();
            $('.online-redemption').hide();
            $('.invite-friends').show();
            $('.social-network').show();

        }
        else {
            $('.offer-promocode').show();
            $('.online-redemption').show();
            $('.invite-friends').hide();
            $('.social-network').hide();

        }
    });
    $('.end-date').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.startdate').show();
            $('.start-and-end-date').hide();
        } else {
            $('.start-and-end-date').show();
            $('.startdate').hide();
        }
    });
    $('.offer-sms-edit').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.sms-message-edit').hide();

        } else {
            $('.sms-message-edit').show();

        }
    });
    
    $('.offer-email-edit').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.email-message-edit').hide();
        } else {
            $('.email-message-edit').show();

        }
    });

    $('.offer-email-edit-loyalty').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.email-message-edit-loyalty').hide();
        } else {
            $('.email-message-edit-loyalty').show();

        }
    });

    $('.offer-email-edit-antiloss').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.email-message-edit-antiloss').hide();
        } else {
            $('.email-message-edit-antiloss').show();

        }
    });

    $('.loyalty_offer_edit').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.offer-limit-edit').hide();
        } else {
            $('.offer-limit-edit').show();

        }
    });

    $('.antiloss_offer_edit').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === false) {
            $('.offer-limit-edit').hide();
        } else {
            $('.offer-limit-edit').show();

        }
    });

    $(function () {
        $('.maxlength-options-edit').smsCounter('.count-edit');
    });
    
    $('.survey-type').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.poll').show();
            $('.survey-types').show();
            $('.rating').hide();
        } else {
            $('.rating').show();
            $('.survey-types').show();
            $('.poll').hide();

        }
    });

    $('.whatsapp-survey').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.whatsapp-survey-form').show();
        } else {
            $('.whatsapp-survey-form').hide();
        }
    });

    // Switchery
    // ------------------------------

    // Initialize multiple switches
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }
    // Basic select
    $('.bootstrap-select').selectpicker();

    // Select all method
    $('#select-all-values-networks-website2').on('click', function () {
        $('.select-all-values-networks-website2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-networks-website2').on('click', function () {
        $('.select-all-values-networks-website2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-networks-video2').on('click', function () {
        $('.select-all-values-networks-video2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-networks-video2').on('click', function () {
        $('.select-all-values-networks-video2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-networks-offer2').on('click', function () {
        $('.select-all-values-networks-offer2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-networks-offer2').on('click', function () {
        $('.select-all-values-networks-offer2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-networks-apps2').on('click', function () {
        $('.select-all-values-networks-apps2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-networks-apps2').on('click', function () {
        $('.select-all-values-networks-apps2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-networks-survey2').on('click', function () {
        $('.select-all-values-networks-survey2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-networks-survey2').on('click', function () {
        $('.select-all-values-networks-survey2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-networks-social2').on('click', function () {
        $('.select-all-values-networks-social2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-networks-social2').on('click', function () {
        $('.select-all-values-networks-social2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-networks-landing2').on('click', function () {
        $('.select-all-values-networks-landing2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-networks-landing2').on('click', function () {
        $('.select-all-values-networks-landing2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-networks-sms2').on('click', function () {
        $('.select-all-values-networks-sms2').selectpicker('selectAll');
    });

    // Deselect all method
    $('#deselect-all-values-networks-sms2').on('click', function () {
        $('.select-all-values-networks-sms2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-networks-loyalty2').on('click', function () {
        $('.select-all-values-networks-loyalty2').selectpicker('selectAll');
    });

    // Deselect all method
    $('#deselect-all-values-networks-loyalty2').on('click', function () {
        $('.select-all-values-networks-loyalty2').selectpicker('deselectAll');
    });

    // Select all method antiloss
    $('#select-all-values-networks-antiloss2').on('click', function () {
        $('.select-all-values-networks-antiloss2').selectpicker('selectAll');
    });

    // Deselect all method antiloss
    $('#deselect-all-values-networks-antiloss2').on('click', function () {
        $('.select-all-values-networks-antiloss2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-networks-mail2').on('click', function () {
        $('.select-all-values-networks-mail2').selectpicker('selectAll');
    });

    // Deselect all method
    $('#deselect-all-values-networks-mail2').on('click', function () {
        $('.select-all-values-networks-mail2').selectpicker('deselectAll');
    });

    // --------------------------- Branches -----------------------------
    // Select all method
    $('#select-all-values-branches-website2').on('click', function () {
        $('.select-all-values-branches-website2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-website2').on('click', function () {
        $('.select-all-values-branches-website2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-branches-video2').on('click', function () {
        $('.select-all-values-branches-video2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-video2').on('click', function () {
        $('.select-all-values-branches-video2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-branches-offer2').on('click', function () {
        $('.select-all-values-branches-offer2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-offer2').on('click', function () {
        $('.select-all-values-branches-offer2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-branches-apps2').on('click', function () {
        $('.select-all-values-branches-apps2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-apps2').on('click', function () {
        $('.select-all-values-branches-apps2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-branches-survey2').on('click', function () {
        $('.select-all-values-branches-survey2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-survey2').on('click', function () {
        $('.select-all-values-branches-survey2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-branches-social2').on('click', function () {
        $('.select-all-values-branches-social2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-social2').on('click', function () {
        $('.select-all-values-branches-social2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-branches-landing2').on('click', function () {
        $('.select-all-values-branches-landing2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-landing2').on('click', function () {
        $('.select-all-values-branches-landing2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-branches-sms2').on('click', function () {
        $('.select-all-values-branches-sms2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-sms2').on('click', function () {
        $('.select-all-values-branches-sms2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-branches-loyalty2').on('click', function () {
        $('.select-all-values-branches-loyalty2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-loyalty2').on('click', function () {
        $('.select-all-values-branches-loyalty2').selectpicker('deselectAll');
    });

    // Select all method antiloss
    $('#select-all-values-branches-antiloss2').on('click', function () {
        $('.select-all-values-branches-antiloss2').selectpicker('selectAll');
    });


    // Deselect all method antiloss
    $('#deselect-all-values-branches-antiloss2').on('click', function () {
        $('.select-all-values-branches-antiloss2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-branches-mail2').on('click', function () {
        $('.select-all-values-branches-mail2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-branches-mail2').on('click', function () {
        $('.select-all-values-branches-mail2').selectpicker('deselectAll');
    });
    // --------------------------- groups -----------------------------
    // Select all method
    $('#select-all-values-groups-website2').on('click', function () {
        $('.select-all-values-groups-website2').selectpicker('selectAll');
    });

    // Deselect all method
    $('#deselect-all-values-groups-website2').on('click', function () {
        $('.select-all-values-groups-website2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-groups-video2').on('click', function () {
        $('.select-all-values-groups-video2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-groups-video2').on('click', function () {
        $('.select-all-values-groups-video2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-groups-offer2').on('click', function () {
        $('.select-all-values-groups-offer2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-groups-offer2').on('click', function () {
        $('.select-all-values-groups-offer2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-groups-apps2').on('click', function () {
        $('.select-all-values-groups-apps2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-groups-apps2').on('click', function () {
        $('.select-all-values-groups-apps2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-groups-survey2').on('click', function () {
        $('.select-all-values-groups-survey2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-groups-survey2').on('click', function () {
        $('.select-all-values-groups-survey2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-groups-social2').on('click', function () {
        $('.select-all-values-groups-social2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-groups-social2').on('click', function () {
        $('.select-all-values-groups-social2').selectpicker('deselectAll');
    });


    // Select all method
    $('#select-all-values-groups-landing2').on('click', function () {
        $('.select-all-values-groups-landing2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-groups-landing2').on('click', function () {
        $('.select-all-values-groups-landing2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-groups-sms2').on('click', function () {
        $('.select-all-values-groups-sms2').selectpicker('selectAll');
    });

    // Deselect all method
    $('#deselect-all-values-groups-sms2').on('click', function () {
        $('.select-all-values-groups-sms2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-groups-loyalty2').on('click', function () {
        $('.select-all-values-groups-loyalty2').selectpicker('selectAll');
    });

    // Deselect all method
    $('#deselect-all-values-groups-loyalty2').on('click', function () {
        $('.select-all-values-groups-loyalty2').selectpicker('deselectAll');
    });

    // Select all method for antiloss
    $('#select-all-values-groups-antiloss2').on('click', function () {
        $('.select-all-values-groups-antiloss2').selectpicker('selectAll');
    });

    // Deselect all method for antiloss
    $('#deselect-all-values-groups-antiloss2').on('click', function () {
        $('.select-all-values-groups-antiloss2').selectpicker('deselectAll');
    });

    // Select all method
    $('#select-all-values-groups-mail2').on('click', function () {
        $('.select-all-values-groups-mail2').selectpicker('selectAll');
    });


    // Deselect all method
    $('#deselect-all-values-groups-mail2').on('click', function () {
        $('.select-all-values-groups-mail2').selectpicker('deselectAll');
    });


    // Colored switches
    var primary = document.querySelector('.switchery-primary');
    var switchery = new Switchery(primary, {color: '#2196F3'});

    var danger = document.querySelector('.switchery-danger');
    var switchery = new Switchery(danger, {color: '#EF5350'});

    var warning = document.querySelector('.switchery-warning');
    var switchery = new Switchery(warning, {color: '#FF7043'});

    var info = document.querySelector('.switchery-info');
    var switchery = new Switchery(info, {color: '#00BCD4'});
    
</script>   