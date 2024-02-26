<?php  $campaign_type = App\Models\Campaigns::where('id', $id)->value('type');
$old_date_timestamp = strtotime($campaigns->startdate);
$new_date = date('d F, Y', $old_date_timestamp);
?>
<!-- Daily sales -->
<!--<div class="panel panel-flat">
    <div class="panel-heading">
        <h6 class="panel-title">statistics</h6>
    </div>

    <div class="panel-body">
        <div class="chart-container">
            <div class="chart has-fixed-height" id="basic_area"></div>
        </div>
    </div>
</div>-->
<!-- /daily sales -->
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
                <label class="control-label col-lg-3">Start and end dates:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php $old_date_timestamp = strtotime($campaigns->startdate);
                        $new_date = date('d F, Y', $old_date_timestamp);?>
                        <input name="start-date" type="text" class="form-control pickadate startdate"
                               @if($campaigns->enddate) style="display: none" @else @endif value="{{ $new_date }}">
                        <input name="start-and-end-date" type="text"
                               class="form-control daterange-basic start-and-end-date"
                               @if($campaigns->enddate)  @else style="display: none" @endif>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="end-date" type="checkbox" class="switch end-date" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   No End Date
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
                <label class="control-label col-lg-3">Start and end dates:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php $old_date_timestamp = strtotime($campaigns->startdate);
                        $new_date = date('d F, Y', $old_date_timestamp);?>
                        <input name="start-date" type="text" class="form-control pickadate startdate"
                               @if($campaigns->enddate) style="display: none" @else @endif value="{{ $new_date }}">
                        <input name="start-and-end-date" type="text"
                               class="form-control daterange-basic start-and-end-date"
                               @if($campaigns->enddate)  @else style="display: none" @endif>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="end-date" type="checkbox" class="switch end-date" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   No End Date
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
                <label class="control-label col-lg-3">Start and end dates:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php $old_date_timestamp = strtotime($campaigns->startdate);
                        $new_date = date('d F, Y', $old_date_timestamp);?>
                        <input name="start-date" type="text" class="form-control pickadate startdate"
                               @if($campaigns->enddate) style="display: none" @else @endif value="{{ $new_date }}">
                        <input name="start-and-end-date" type="text"
                               class="form-control daterange-basic start-and-end-date"
                               @if($campaigns->enddate)  @else style="display: none" @endif>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="end-date" type="checkbox" class="switch end-date" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   No End Date
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
                <label class="control-label col-lg-3">Expiration date:</label>
                <div class="col-lg-4">
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
                <label class="control-label col-lg-2">Claims limit:</label>
                <div class="col-lg-2">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="offer-limit" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->offer_limit }}">
                    </div>
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
            <div class="form-group col-lg-12 invite-friends"
                 @if($campaigns->social_offer == '1') @else style="display: none" @endif>
                <label class="control-label col-lg-3">Invite friends:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <input name="invite-friends" type="text" class="form-control input-xlg"
                               value="{{ $campaigns->invite_friends }}">
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Offer title:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="offer-title" type="text" class="form-control input-xlg"
                                  placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_title }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="control-label col-lg-3">Offer description:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                        <textarea name="offer-desc" type="text" class="form-control input-xlg"
                                  placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_desc }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12 email-message-edit" @if( $campaigns->offer_sendmail  == '1')  @else style="display: none;" @endif>
                <label class="control-label col-lg-3">Offer Email message:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left">
                                                <textarea name="offer-email-message" type="text"
                                                          class="form-control input-xlg"
                                                          placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_email_message }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-12 sms-message-edit" @if( $campaigns->offer_sendsms  == '1')  @else style="display: none;" @endif>
                <label class="control-label col-lg-3">Offer SMS message:</label>
                <div class="col-lg-8">
                    <div class="form-group has-feedback has-feedback-left maxlength">
                                                <textarea name="offer-sms-message" type="text"
                                                          class="form-control input-xlg maxlength-options-edit"
                                                          placeholder="Take 25% off your total purchase!">{{ $campaigns->offer_sms_message }}</textarea>
                        <div class="count-edit">(0) 0</div>
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
                        <textarea name="offer-terms" type="text" class="form-control input-xlg"
                                  placeholder="Enter optional terms and conditions">{{ $campaigns->offer_terms }}</textarea>
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
                <label class="control-label col-lg-3">Start and end dates:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php $old_date_timestamp = strtotime($campaigns->startdate);
                        $new_date = date('d F, Y', $old_date_timestamp);?>
                        <input name="start-date" type="text" class="form-control pickadate startdate"
                               @if($campaigns->enddate) style="display: none" @else @endif value="{{ $new_date }}">
                        <input name="start-and-end-date" type="text"
                               class="form-control daterange-basic start-and-end-date"
                               @if($campaigns->enddate)  @else style="display: none" @endif>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="end-date" type="checkbox" class="switch end-date" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   No End Date
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
                <label class="control-label col-lg-3">Start and end dates:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php $old_date_timestamp = strtotime($campaigns->startdate);
                        $new_date = date('d F, Y', $old_date_timestamp);?>
                        <input name="start-date" type="text" class="form-control pickadate startdate"
                               @if($campaigns->enddate) style="display: none" @else @endif value="{{ $new_date }}">
                        <input name="start-and-end-date" type="text"
                               class="form-control daterange-basic start-and-end-date"
                               @if($campaigns->enddate)  @else style="display: none" @endif>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="end-date" type="checkbox" class="switch end-date" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   No End Date
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
            <div class="form-group col-lg-12">
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
            </div>
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
                        <input name="options" type="text" class="form-control tokenfield" value="{{ $options }}">
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
                <label class="control-label col-lg-3">Start and end dates:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php $old_date_timestamp = strtotime($campaigns->startdate);
                        $new_date = date('d F, Y', $old_date_timestamp);?>
                        <input name="start-date" type="text" class="form-control pickadate startdate"
                               @if($campaigns->enddate) style="display: none" @else @endif value="{{ $new_date }}">
                        <input name="start-and-end-date" type="text"
                               class="form-control daterange-basic start-and-end-date"
                               @if($campaigns->enddate)  @else style="display: none" @endif>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="end-date" type="checkbox" class="switch end-date" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   No End Date
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
                <label class="control-label col-lg-3">Start and end dates:</label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <?php $old_date_timestamp = strtotime($campaigns->startdate);
                        $new_date = date('d F, Y', $old_date_timestamp);?>
                        <input name="start-date" type="text" class="form-control pickadate startdate"
                               @if($campaigns->enddate) style="display: none" @else @endif value="{{ $new_date }}">
                        <input name="start-and-end-date" type="text"
                               class="form-control daterange-basic start-and-end-date"
                               @if($campaigns->enddate)  @else style="display: none" @endif>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="end-date" type="checkbox" class="switch end-date" data-on-text="On"
                                   data-off-text="Off" data-on-color="success" data-off-color="default"
                                   @if($campaigns->enddate) checked @else @endif
                                   No End Date
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
<script type="text/javascript" src="assets/js/plugins/uploaders/fileinput.min.js"></script>
<script type="text/javascript" src="assets/js/pages/components_popups.js"></script>

<!--<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>-->
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>

<script>
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


    // Colored switches
    var primary = document.querySelector('.switchery-primary');
    var switchery = new Switchery(primary, {color: '#2196F3'});

    var danger = document.querySelector('.switchery-danger');
    var switchery = new Switchery(danger, {color: '#EF5350'});

    var warning = document.querySelector('.switchery-warning');
    var switchery = new Switchery(warning, {color: '#FF7043'});

    var info = document.querySelector('.switchery-info');
    var switchery = new Switchery(info, {color: '#00BCD4'});
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

                basic_area_options = {

                    // Setup grid
                    grid: {
                        x: 40,
                        x2: 20,
                        y: 35,
                        y2: 25
                    },

                    // Add tooltip
                    tooltip: {
                        trigger: 'axis'
                    },

                    // Add legend
                    legend: {
                        data: ['Impressions', 'Clicks']
                    },


                    // Enable drag recalculate
                    calculable: true,

                    // Horizontal axis
                    xAxis: [{
                        type: 'category',
                        boundaryGap: false,
                        data: [
                            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                        ]
                    }],

                    // Vertical axis
                    yAxis: [{
                        type: 'value'
                    }],
                    @if($campaign_type == "website")
                    // Add series
                    series: [
                        {
                            name: 'Impressions',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [10, 12, 21, 54, 260, 830, 710]
                        },
                        {
                            name: 'Clicks',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [30, 182, 434, 791, 390, 30, 10]
                        },{
                            series: [
                                {
                                    name: 'Impressions',
                                    type: 'line',
                                    smooth: true,
                                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                                    data: [10, 12, 21, 54, 260, 830, 710]
                                },
                                {
                                    name: 'Clicks',
                                    type: 'line',
                                    smooth: true,
                                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                                    data: [30, 182, 434, 791, 390, 30, 10]
                                }
                            ]  
                        }
                    ]
                    @endif
                    @if($campaign_type == "video")
                    // Add series
                    series: [
                        {
                            name: 'Impressions',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [10, 12, 21, 54, 260, 830, 710]
                        },
                        {
                            name: 'Clicks',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [30, 182, 434, 791, 390, 30, 10]
                        }
                    ]
                    @endif
                    @if($campaign_type == "offer")
                    // Add series
                    series: [
                        {
                            name: 'Impressions',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [10, 12, 21, 54, 260, 830, 710]
                        },
                        {
                            name: 'Clicks',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [30, 182, 434, 791, 390, 30, 10]
                        }
                    ]
                    @endif
                    @if($campaign_type == "apps")
                    // Add series
                    series: [
                        {
                            name: 'Impressions',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [10, 12, 21, 54, 260, 830, 710]
                        },
                        {
                            name: 'Clicks',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [30, 182, 434, 791, 390, 30, 10]
                        }
                    ]
                    @endif
                    @if($campaign_type == "survey")
                    // Add series
                    series: [
                        {
                            name: 'Impressions',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [1, 2, 3, 4, 5, 6, 7]
                        },
                        {
                            name: 'Clicks',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [7, 6, 5, 4, 3, 2, 1]
                        }
                    ]
                    @endif
                    @if($campaign_type == "social")
                    // Add series
                    series: [
                        {
                            name: 'Impressions',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [10, 12, 21, 54, 260, 830, 710]
                        },
                        {
                            name: 'Clicks',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [30, 182, 434, 791, 390, 30, 10]
                        }
                    ]
                    @endif
                    @if($campaign_type == "landing-page")
                    // Add series
                    series: [
                        {
                            name: 'Impressions',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [10, 12, 21, 54, 260, 830, 710]
                        },
                        {
                            name: 'Clicks',
                            type: 'line',
                            smooth: true,
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [30, 182, 434, 791, 390, 30, 10]
                        }
                    ]
                    @endif
                };

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
</script>