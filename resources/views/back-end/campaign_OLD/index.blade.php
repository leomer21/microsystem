@extends('..back-end.layouts.master')
@section('title', 'Campaigns')
@section('content')
    <!-- Page header -->
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Campaigns (beta)
                </h4>
            </div>
        </div>
    </div>
    <!-- /page header -->

    <!-- Content area -->
    <div class="content">
        <!-- Primary modal -->
        <div id="add_ads" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Create new ad</h6>
                    </div>

                    <div class="modal-body">
                        <!-- Daily sales -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Select ad type</h6>
                            </div>

                            <div class="panel-body">
                                <div class="category-content">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <a href="#tab1" data-toggle="tab"
                                               class="btn bg-teal-400 btn-block btn-float btn-float-lg" type="button"><i
                                                        class="icon-sphere"></i> <span>Website</span></a>
                                        </div>

                                        <div class="col-xs-2">
                                            <a href="#tab2" data-toggle="tab"
                                               class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i
                                                        class="icon-video-camera2"></i> <span>Video</span></a>
                                        </div>

                                        <div class="col-xs-2">
                                            <a href="#tab3" data-toggle="tab"
                                               class="btn bg-warning-400 btn-block btn-float btn-float-lg"
                                               type="button"><i class="icon-price-tag2"></i> <span>Offer</span></a>
                                        </div>

                                        <div class="col-xs-2">
                                            <a href="#tab4" data-toggle="tab"
                                               class="btn bg-blue btn-block btn-float btn-float-lg" type="button"><i
                                                        class="icon-mobile"></i> <span>App install</span></a>
                                        </div>

                                        <div class="col-xs-2">
                                            <a href="#tab5" data-toggle="tab"
                                               class="btn bg-teal-400 btn-block btn-float btn-float-lg" type="button"><i
                                                        class="icon-theater"></i> <span>Survey</span></a>
                                        </div>

                                        <div class="col-xs-2">
                                            <a href="#tab6" data-toggle="tab"
                                               class="btn bg-warning-400 btn-block btn-float btn-float-lg"
                                               type="button"><i class="icon-facebook"></i> <span>Social</span></a>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <a href="#tab7" data-toggle="tab"
                                               class="btn bg-blue btn-block btn-float btn-float-lg" type="button"><i
                                                        class="icon-rocket"></i> <span>Landing page</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /daily sales -->

                        <div class="tab-content">
                            <!-- General -->
                            <div class="panel panel-flat tab-pane" id="tab1">
                                {{ Form::open(array('url' => 'campaigns', 'files' => true, 'method' => 'post', 'id' => 'website')) }}
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
                                                <input name="campaign-name" type="text" class="form-control input-xlg">
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
                                                <input name="ad-name" type="text" class="form-control input-xlg">
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
                                                          class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                                        <div class="col-lg-2">
                                            <div class="checkbox option-skip">
                                                <label>
                                                    <input name="open-profile" checked  checked type="checkbox" class="control-primary option-skip">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2 option-skip-value" data-popup="tooltip"
                                               title="Waiting time to skip Ad" data-placement="right">Time delay: (seconds)</label>
                                        <div class="col-lg-4 option-skip-value">

                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="time-delay" type="text" class="form-control input-xlg" value="10">
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
                                                <input name="start-and-end-date" type="text"
                                                       class="form-control daterange-basic start-and-end-date"
                                                       style="display: none;">
                                                <input name="start-date" type="text"
                                                       class="form-control pickadate startdate">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="end-date" type="checkbox" class="switch end-date"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Never end
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">statistics</h6>
                                    </div>

                                    <div class="panel-body">
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
                                                        <input name="url" type="text" class="form-control input-xlg">
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
                                                                  class="form-control input-xlg"></textarea>
                                                    </div>
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
                                            <select class="bootstrap-select select-all-values-networks-website"
                                                    multiple="multiple" data-width="100%" name="networks[]">
                                                @foreach(App\Network::all() as $network)
                                                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-networks-website">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-networks-website">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Branches</label></div>
                                        <div class="col-lg-6">
                                            <select class="bootstrap-select select-all-values-branches-website"
                                                    multiple="multiple" data-width="100%" name="branches[]">
                                                @foreach(App\Branches::all() as $branche)
                                                    <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-branches-website">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-branches-website">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                                        <div class="col-lg-6">

                                            <select class="bootstrap-select select-all-values-groups-website"
                                                    multiple="multiple" data-width="100%" name="groups[]">
                                                @foreach(App\Groups::where('as_system', 0)->get() as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-groups-website">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-groups-website">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input type="checkbox" name="day-parting" class="switch day-parting"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Day parting
                                                </label>
                                            </div>
                                            <div class="form-group col-lg-2 start" style="display: none">
                                                <label class="control-label">Start:</label>
                                                <input name="day-parting-start" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 end" style="display: none">
                                                <label class="control-label">End:</label>
                                                <input name="day-parting-end" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 sun" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sun-day" type="checkbox" class="switchery">
                                                        Sun
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 mon" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="mon-day" type="checkbox" class="switchery">
                                                        Mon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 tue" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="tue-day" type="checkbox" class="switchery">
                                                        Tue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 wed" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="wed-day" type="checkbox" class="switchery">
                                                        Wed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 thu" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="thu-day" type="checkbox" class="switchery">
                                                        Thu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 fri" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="fri-day" type="checkbox" class="switchery">
                                                        Fri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 sat" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sat-day" type="checkbox" class="switchery">
                                                        Sat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                            onclick="document.forms['website'].submit(); return false;">Save changes
                                    </button>
                                </div>
                            </div>
                            <!-- General -->
                            <div class="panel panel-flat tab-pane" id="tab2">
                                {{ Form::open(array('url' => 'campaigns', 'files' => true, 'method' => 'post', 'id' => 'video')) }}
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
                                                <input name="campaign-name" type="text" class="form-control input-xlg">
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
                                                <input name="ad-name" type="text" class="form-control input-xlg">
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
                                                          class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                                        <div class="col-lg-2">
                                            <div class="checkbox option-skip">
                                                <label>
                                                    <input name="open-profile" checked  type="checkbox" class="control-primary">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2 option-skip-value" data-popup="tooltip"
                                               title="Waiting time to skip Ad">Time delay: (seconds)</label>
                                        <div class="col-lg-4 option-skip-value">

                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="time-delay" type="text" class="form-control input-xlg" value="10">                                                
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
                                                <input name="start-date" type="text"
                                                       class="form-control pickadate startdate">
                                                <input name="start-and-end-date" type="text"
                                                       class="form-control daterange-basic start-and-end-date"
                                                       style="display: none">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="end-date" type="checkbox" class="switch end-date"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Never end
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
                                                <input name="video-url" type="text" class="form-control input-xlg" placeholder="0Lfxmwgl20E">
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
                                                <input name="url" type="text" class="form-control input-xlg">
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
                                            <select class="bootstrap-select select-all-values-networks-video"
                                                    multiple="multiple" data-width="100%" name="networks[]">
                                                @foreach(App\Network::all() as $network)
                                                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-networks-video">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-networks-video">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Branches</label></div>
                                        <div class="col-lg-6">
                                            <select class="bootstrap-select select-all-values-branches-video"
                                                    multiple="multiple" data-width="100%" name="branches[]">
                                                @foreach(App\Branches::all() as $branche)
                                                    <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-branches-video">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-branches-video">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                                        <div class="col-lg-6">

                                            <select class="bootstrap-select select-all-values-groups-video"
                                                    multiple="multiple" data-width="100%" name="groups[]">
                                                @foreach(App\Groups::where('as_system', 0)->get() as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-groups-video">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-groups-video">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input type="checkbox" name="day-parting" class="switch day-parting"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Day parting
                                                </label>
                                            </div>
                                            <div class="form-group col-lg-2 start" style="display: none">
                                                <label class="control-label">Start:</label>
                                                <input name="day-parting-start" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 end" style="display: none">
                                                <label class="control-label">End:</label>
                                                <input name="day-parting-end" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 sun" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sun-day" type="checkbox" class="switchery">
                                                        Sun
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 mon" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="mon-day" type="checkbox" class="switchery">
                                                        Mon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 tue" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="tue-day" type="checkbox" class="switchery">
                                                        Tue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 wed" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="wed-day" type="checkbox" class="switchery">
                                                        Wed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 thu" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="thu-day" type="checkbox" class="switchery">
                                                        Thu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 fri" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="fri-day" type="checkbox" class="switchery">
                                                        Fri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 sat" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sat-day" type="checkbox" class="switchery">
                                                        Sat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                            onclick="document.forms['video'].submit(); return false;">Save changes
                                    </button>
                                </div>
                            </div>
                            <!-- General -->
                            <div class="panel panel-flat tab-pane" id="tab3">
                                {{ Form::open(array('url' => 'campaigns', 'files' => true, 'method' => 'post', 'id' => 'offer')) }}
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
                                                <input name="campaign-name" type="text" class="form-control input-xlg">
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
                                                <input name="ad-name" type="text" class="form-control input-xlg">
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
                                                          class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                                        <div class="col-lg-2">
                                            <div class="checkbox option-skip">
                                                <label>
                                                    <input name="open-profile" checked  type="checkbox" class="control-primary">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2 option-skip-value" data-popup="tooltip"
                                               title="Waiting time to skip Ad">Time delay: (seconds)</label>
                                        <div class="col-lg-4 option-skip-value">

                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="time-delay" type="text" class="form-control input-xlg" value="10">                                                <div class="form-control-feedback">
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
                                                <input name="start-date" type="text"
                                                       class="form-control pickadate startdate">
                                                <input name="start-and-end-date" type="text"
                                                       class="form-control daterange-basic start-and-end-date"
                                                       style="display: none">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="end-date" type="checkbox" class="switch end-date"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Never end
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
                                                           data-popup="tooltip" title="" data-on-text="Social offer"
                                                           data-off-text="Default offer" data-on-color="success"
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
                                                <input name="offer-expire-date" type="text"
                                                       class="form-control input-xlg pickadate">
                                                <div class="form-control-feedback">
                                                    <i class="icon-calendar22"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2" data-popup="tooltip"
                                               title="Total limit for users">Claims limit:</label>
                                        <div class="col-lg-2">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="offer-limit" type="text" class="form-control input-xlg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3"></label>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch offer-sms">
                                                <label>
                                                    <input name="offer-sms" type="checkbox" class="switch"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    SMS
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch offer-email">
                                                <label>
                                                    <input name="offer-email" type="checkbox" class="switch"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Email
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 invite-friends" style="display: none">
                                        <label class="control-label col-lg-3">Invite friends:</label>
                                        <div class="col-lg-8">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="invite-friends" type="text" class="form-control input-xlg"
                                                       value="5">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3">Offer title:</label>
                                        <div class="col-lg-8">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <textarea name="offer-title" type="text" class="form-control input-xlg"
                                                          placeholder="Take 25% off your total purchase!"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3">Offer description:</label>
                                        <div class="col-lg-8">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <textarea name="offer-desc" type="text" class="form-control input-xlg"
                                                          placeholder="Take 25% off your total purchase!"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 email-message" style="display: none;">
                                        <label class="control-label col-lg-3">Offer Email message:</label>
                                        <div class="col-lg-8">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <textarea name="offer-email-message" type="text"
                                                          class="form-control input-xlg"
                                                          placeholder="Take 25% off your total purchase!"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 sms-message" style="display: none;">
                                        <label class="control-label col-lg-3">Offer SMS message:</label>
                                        <div class="col-lg-8">
                                            <div class="form-group has-feedback has-feedback-left maxlength">
                                                <textarea name="offer-sms-message" type="text"
                                                          class="form-control input-xlg maxlength-options"
                                                          placeholder="Take 25% off your total purchase!, Offer code:000000"></textarea>
                                                <div class="count">(0) 0</div>
                                                <span class="help-block">Please leave 18 characters for offer code ex.(, Offer code:000000)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 online-redemption">
                                        <label class="control-label col-lg-3">Online Redemption Link:</label>
                                        <div class="col-lg-8">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="url" type="text" class="form-control input-xlg"
                                                       placeholder="Enter a website where people can redeem your offer">
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
                                                          placeholder="Enter optional terms and conditions"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Targeting -->
                                <div class="panel-heading">
                                    <h6 class="panel-title">Targeting</h6>
                                </div>

                                <div class="panel-body">
                                    <div class="col-lg-12 social-network" style="display: none;"><label class="text-semibold">Social network</label></div>

                                    <div class="form-group has-feedback-left col-lg-12 social-network" style="display: none;">
                                        <div class="col-lg-6">
                                            <select class="select-fixed-single" name="social-post-type">
                                                <optgroup label="Options">
                                                    <option value="1">Feed</option>
                                                    <option value="2">Share</option>
                                                    <option value="3">Send massage</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="social-network" type="checkbox" data-on-color="primary"
                                                           data-off-color="info" data-on-text="Facebook"
                                                           data-off-text="Twitter" class="switch">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="form-group has-feedback-left col-lg-12">
                                            <div class="col-lg-6">
                                                <label class="text-semibold">Networks</label>
                                                <select class="bootstrap-select select-all-values-networks-offer"
                                                        multiple="multiple" data-width="100%" name="networks[]">
                                                    @foreach(App\Network::all() as $network)
                                                        <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="text-semibold">Branches</label>
                                                <select class="bootstrap-select select-all-values-branches-offer"
                                                        multiple="multiple" data-width="100%" name="branches[]">
                                                    @foreach(App\Branches::all() as $branche)
                                                        <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback-left col-lg-12">
                                            <div class="col-lg-6">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-info"
                                                            id="select-all-values-networks-offer">Select all
                                                    </button>
                                                    <button type="button" class="btn btn-default"
                                                            id="deselect-all-values-networks-offer">Deselect all
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-info"
                                                            id="select-all-values-branches-offer">Select all
                                                    </button>
                                                    <button type="button" class="btn btn-default"
                                                            id="deselect-all-values-branches-offer">Deselect all
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback-left col-lg-12">
                                            <div class="col-lg-6">
                                                <label class="text-semibold">Groups</label>
                                                <select class="bootstrap-select select-all-values-groups-offer"
                                                        multiple="multiple" data-width="100%" name="groups[]">
                                                    @foreach(App\Groups::where('as_system', 0)->get() as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback-left col-lg-12">
                                            <div class="col-lg-6">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-info"
                                                            id="select-all-values-groups-offer">Select all
                                                    </button>
                                                    <button type="button" class="btn btn-default"
                                                            id="deselect-all-values-groups-offer">Deselect all
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input type="checkbox" name="day-parting" class="switch day-parting"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Day parting
                                                </label>
                                            </div>
                                            <div class="form-group col-lg-2 start" style="display: none">
                                                <label class="control-label">Start:</label>
                                                <input name="day-parting-start" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 end" style="display: none">
                                                <label class="control-label">End:</label>
                                                <input name="day-parting-end" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 sun" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sun-day" type="checkbox" class="switchery">
                                                        Sun
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 mon" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="mon-day" type="checkbox" class="switchery">
                                                        Mon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 tue" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="tue-day" type="checkbox" class="switchery">
                                                        Tue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 wed" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="wed-day" type="checkbox" class="switchery">
                                                        Wed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 thu" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="thu-day" type="checkbox" class="switchery">
                                                        Thu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 fri" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="fri-day" type="checkbox" class="switchery">
                                                        Fri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 sat" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sat-day" type="checkbox" class="switchery">
                                                        Sat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                            onclick="document.forms['offer'].submit(); return false;">Save changes
                                    </button>
                                </div>
                            </div>
                            <!-- General -->
                            <div class="panel panel-flat tab-pane" id="tab4">
                                {{ Form::open(array('url' => 'campaigns', 'files' => true, 'method' => 'post', 'id' => 'apps')) }}
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
                                                <input name="campaign-name" type="text" class="form-control input-xlg">
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
                                                <input name="ad-name" type="text" class="form-control input-xlg">
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
                                                          class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                                        <div class="col-lg-2">
                                            <div class="checkbox option-skip">
                                                <label>
                                                    <input name="open-profile" checked  type="checkbox" class="control-primary">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2 option-skip-value" data-popup="tooltip"
                                               title="Waiting time to skip Ad">Time delay: (seconds)</label>
                                        <div class="col-lg-4 option-skip-value">

                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="time-delay" type="text" class="form-control input-xlg" value="10">                                                <div class="form-control-feedback">
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
                                                <input name="start-date" type="text"
                                                       class="form-control pickadate startdate">
                                                <input name="start-and-end-date" type="text"
                                                       class="form-control daterange-basic start-and-end-date"
                                                       style="display: none">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="end-date" type="checkbox" class="switch end-date"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Never end
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
                                            <label class="control-label">App store Marketplace
                                                url to your app :</label>
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="ios-url" type="text" class="form-control input-xlg">
                                                <div class="form-control-feedback">
                                                    <i class="icon-apple2"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-8">
                                            <label class="control-label">Google Play Marketplace
                                                url to your app :</label>
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="android-url" type="text" class="form-control input-xlg">
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
                                            <select class="bootstrap-select select-all-values-networks-apps"
                                                    multiple="multiple" data-width="100%" name="networks[]">
                                                @foreach(App\Network::all() as $network)
                                                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-networks-apps">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-networks-apps">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Branches</label></div>
                                        <div class="col-lg-6">
                                            <select class="bootstrap-select select-all-values-branches-apps"
                                                    multiple="multiple" data-width="100%" name="branches[]">
                                                @foreach(App\Branches::all() as $branche)
                                                    <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-branches-apps">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-branches-apps">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                                        <div class="col-lg-6">

                                            <select class="bootstrap-select select-all-values-groups-apps"
                                                    multiple="multiple" data-width="100%" name="groups[]">
                                                @foreach(App\Groups::where('as_system', 0)->get() as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-groups-apps">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-groups-apps">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input type="checkbox" name="day-parting" class="switch day-parting"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Day parting
                                                </label>
                                            </div>
                                            <div class="form-group col-lg-2 start" style="display: none">
                                                <label class="control-label">Start:</label>
                                                <input name="day-parting-start" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 end" style="display: none">
                                                <label class="control-label">End:</label>
                                                <input name="day-parting-end" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 sun" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sun-day" type="checkbox" class="switchery">
                                                        Sun
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 mon" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="mon-day" type="checkbox" class="switchery">
                                                        Mon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 tue" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="tue-day" type="checkbox" class="switchery">
                                                        Tue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 wed" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="wed-day" type="checkbox" class="switchery">
                                                        Wed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 thu" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="thu-day" type="checkbox" class="switchery">
                                                        Thu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 fri" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="fri-day" type="checkbox" class="switchery">
                                                        Fri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 sat" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sat-day" type="checkbox" class="switchery">
                                                        Sat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                            onclick="document.forms['apps'].submit(); return false;">Save changes
                                    </button>
                                </div>
                            </div>
                            <!-- General -->
                            <div class="panel panel-flat tab-pane" id="tab5">
                                {{ Form::open(array('url' => 'campaigns', 'files' => true, 'method' => 'post', 'id' => 'survey')) }}
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
                                                <input name="campaign-name" type="text" class="form-control input-xlg">
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
                                                <input name="ad-name" type="text" class="form-control input-xlg">
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
                                                          class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                                        <div class="col-lg-2">
                                            <div class="checkbox option-skip">
                                                <label>
                                                    <input name="open-profile" checked  type="checkbox" class="control-primary">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2 option-skip-value" data-popup="tooltip"
                                               title="Waiting time to skip Ad">Time delay: (seconds)</label>
                                        <div class="col-lg-4 option-skip-value">

                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="time-delay" type="text" class="form-control input-xlg" value="10">                                                <div class="form-control-feedback">
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
                                                <input name="start-date" type="text"
                                                       class="form-control pickadate startdate">
                                                <input name="start-and-end-date" type="text"
                                                       class="form-control daterange-basic start-and-end-date"
                                                       style="display: none">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="end-date" type="checkbox" class="switch end-date"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Never end
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
                                                <input name="url" type="text" class="form-control input-xlg">
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
                                                    <input type="checkbox" name="survey-type" class="switch survey-type"
                                                           data-on-text="Poll" data-off-text="Rating" data-on-color="success"
                                                           data-off-color="default">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 survey-types">
                                        <label class="control-label col-lg-3">Type your question here :</label>
                                        <div class="col-lg-8">
                                            <div class="form-group has-feedback has-feedback-left">
                                                <textarea name="question" type="text" class="form-control input-xlg"
                                                          placeholder="Example: How did our service make you feel?"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12 poll" style="display: none">
                                        <label class="control-label col-lg-3">Options :</label>
                                        <div class="col-lg-8">
                                            <div class="input-group input-group-lg">
                                                <div class="input-group-addon"><i class="icon-menu7"></i></div>
                                                <input name="options" type="text" class="form-control tokenfield">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Targeting -->
                                <div class="panel-heading">
                                    <h6 class="panel-title">Targeting</h6>
                                </div>

                                <div class="panel-body">
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="form-group has-feedback-left col-lg-12">
                                                <div class="col-lg-3"><label class="text-semibold">Networks</label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <select class="bootstrap-select select-all-values-networks-survey"
                                                            multiple="multiple" data-width="100%" name="networks[]">
                                                        @foreach(App\Network::all() as $network)
                                                            <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback-left col-lg-12">
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-9">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-info"
                                                                id="select-all-values-networks-survey">Select all
                                                        </button>
                                                        <button type="button" class="btn btn-default"
                                                                id="deselect-all-values-networks-survey">Deselect all
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback-left col-lg-12">
                                                <div class="col-lg-3"><label class="text-semibold">Branches</label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <select class="bootstrap-select select-all-values-branches-survey"
                                                            multiple="multiple" data-width="100%" name="branches[]">
                                                        @foreach(App\Branches::all() as $branche)
                                                            <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback-left col-lg-12">
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-9">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-info"
                                                                id="select-all-values-branches-survey">Select all
                                                        </button>
                                                        <button type="button" class="btn btn-default"
                                                                id="deselect-all-values-branches-survey">Deselect all
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback-left col-lg-12">
                                                <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                                                <div class="col-lg-6">

                                                    <select class="bootstrap-select select-all-values-groups-survey"
                                                            multiple="multiple" data-width="100%" name="groups[]">
                                                        @foreach(App\Groups::where('as_system', 0)->get() as $group)
                                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback-left col-lg-12">
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-9">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-info"
                                                                id="select-all-values-groups-survey">Select all
                                                        </button>
                                                        <button type="button" class="btn btn-default"
                                                                id="deselect-all-values-groups-survey">Deselect all
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input type="checkbox" name="day-parting" class="switch day-parting"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Day parting
                                                </label>
                                            </div>
                                            <div class="form-group col-lg-2 start" style="display: none">
                                                <label class="control-label">Start:</label>
                                                <input name="day-parting-start" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 end" style="display: none">
                                                <label class="control-label">End:</label>
                                                <input name="day-parting-end" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 sun" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sun-day" type="checkbox" class="switchery">
                                                        Sun
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 mon" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="mon-day" type="checkbox" class="switchery">
                                                        Mon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 tue" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="tue-day" type="checkbox" class="switchery">
                                                        Tue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 wed" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="wed-day" type="checkbox" class="switchery">
                                                        Wed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 thu" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="thu-day" type="checkbox" class="switchery">
                                                        Thu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 fri" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="fri-day" type="checkbox" class="switchery">
                                                        Fri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 sat" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sat-day" type="checkbox" class="switchery">
                                                        Sat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                            onclick="document.forms['survey'].submit(); return false;">Save changes
                                    </button>
                                </div>
                            </div>
                            <!-- General -->
                            <div class="panel panel-flat tab-pane" id="tab6">
                                {{ Form::open(array('url' => 'campaigns', 'files' => true, 'method' => 'post', 'id' => 'social')) }}
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
                                                <input name="campaign-name" type="text" class="form-control input-xlg">
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
                                                <input name="ad-name" type="text" class="form-control input-xlg">
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
                                                          class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                                        <div class="col-lg-2">
                                            <div class="checkbox option-skip">
                                                <label>
                                                    <input name="open-profile" checked  type="checkbox" class="control-primary">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2 option-skip-value" data-popup="tooltip"
                                               title="Waiting time to skip Ad">Time delay: (seconds)</label>
                                        <div class="col-lg-4 option-skip-value">

                                            <div class="form-group has-feedback has-feedback-left">
                                                <input name="time-delay" type="text" class="form-control input-xlg" value="10">                                                <div class="form-control-feedback">
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
                                                <input name="start-date" type="text"
                                                       class="form-control pickadate startdate">
                                                <input name="start-and-end-date" type="text"
                                                       class="form-control daterange-basic start-and-end-date"
                                                       style="display: none">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="end-date" type="checkbox" class="switch end-date"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Never end
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
                                                       placeholder="http://facebook.com/yourpage">
                                                <div class="form-control-feedback">
                                                    <i class="icon-hyperlink"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-8">
                                            <label class="control-label"  data-popup="tooltip" title="Appear on the top of ad button to motivate users to click on button and open your ad.">Motivational message:</label>
                                            <div class="form-group has-feedback has-feedback-left">
                                                <textarea name="message" type="text" class="form-control input-xlg"
                                                          placeholder="Example: I went to placement today"></textarea>
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
                                            <select class="bootstrap-select select-all-values-networks-social"
                                                    multiple="multiple" data-width="100%" name="networks[]">
                                                @foreach(App\Network::all() as $network)
                                                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-networks-social">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-networks-social">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Branches</label></div>
                                        <div class="col-lg-6">
                                            <select class="bootstrap-select select-all-values-branches-social"
                                                    multiple="multiple" data-width="100%" name="branches[]">
                                                @foreach(App\Branches::all() as $branche)
                                                    <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-branches-social">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-branches-social">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                                        <div class="col-lg-6">

                                            <select class="bootstrap-select select-all-values-groups-social"
                                                    multiple="multiple" data-width="100%" name="groups[]">
                                                @foreach(App\Groups::where('as_system', 0)->get() as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-groups-social">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-groups-social">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input type="checkbox" name="day-parting" class="switch day-parting"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Day parting
                                                </label>
                                            </div>
                                            <div class="form-group col-lg-2 start" style="display: none">
                                                <label class="control-label">Start:</label>
                                                <input name="day-parting-start" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 end" style="display: none">
                                                <label class="control-label">End:</label>
                                                <input name="day-parting-end" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 sun" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sun-day" type="checkbox" class="switchery">
                                                        Sun
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 mon" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="mon-day" type="checkbox" class="switchery">
                                                        Mon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 tue" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="tue-day" type="checkbox" class="switchery">
                                                        Tue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 wed" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="wed-day" type="checkbox" class="switchery">
                                                        Wed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 thu" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="thu-day" type="checkbox" class="switchery">
                                                        Thu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 fri" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="fri-day" type="checkbox" class="switchery">
                                                        Fri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 sat" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sat-day" type="checkbox" class="switchery">
                                                        Sat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                            onclick="document.forms['social'].submit(); return false;">Save changes
                                    </button>
                                </div>
                            </div>
                            <!-- General -->
                            <div class="panel panel-flat tab-pane" id="tab7">
                                
                                {{ Form::open(array('url' => 'campaigns', 'files' => true, 'method' => 'post', 'id' => 'landing-page')) }}
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
                                                <input name="campaign-name" type="text" class="form-control input-xlg">
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
                                                <input name="ad-name" type="text" class="form-control input-xlg">
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
                                                          class="form-control input-xlg"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="control-label col-lg-3" data-popup="tooltip"
                                               title="User can skip ad after delay time">Option skip ad:</label>
                                        <div class="col-lg-2">
                                            <div class="checkbox option-skip">
                                                <label>
                                                    <input name="open-profile" checked  type="checkbox" class="control-primary">
                                                </label>
                                            </div>
                                        </div>
                                        <label class="control-label col-lg-2 option-skip-value" data-popup="tooltip"
                                               title="Waiting time to skip Ad">Time delay: (seconds)</label>
                                        <div class="col-lg-4 option-skip-value">

                                            <div class="form-group has-feedback has-feedback-left">
                                                
                                                <input name="time-delay" type="text" class="form-control input-xlg" value="10">
                                                <div class="form-control-feedback">
                                                    <i class="icon-watch"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group start-and-end-datecol-lg-12">
                                        <label class="control-label col-lg-3">Start and end dates:</label>
                                        <div class="col-lg-5">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                <input name="start-date" type="text"
                                                       class="form-control pickadate startdate">
                                                <input name="" type="text"
                                                       class="form-control daterange-basic start-and-end-date"
                                                       style="display: none">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="end-date" type="checkbox" class="switch end-date"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Never end
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Set up a creative -->
                                <div class="panel-heading">
                                    <h6 class="panel-title">Set up a creative</h6>
                                </div> 
                                <!-- Primary modal -->
                                <div id="modal-perview" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h6 class="modal-title">Primary header</h6>
                                            </div>

                                            <div class="modal-body" id="preview">

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /primary modal -->   
                                <div class="panel-body">
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-8">
                                            <label class="control-label">Landing page :</label>
                                            <select class="select-fixed-single" name="custom-landing-page" id="selectBox" onchange="changeFunc();">
                                                @foreach(App\History::where(['type2' => 'admin', 'operation' => 'custom_landing_page'])->orderBy('id','DESC')->get() as $value)
                                                    <option value="{{ $value->details  }}">{{ $value->add_date.' '.$value->add_time }}</option>
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
                                            <select class="bootstrap-select select-all-values-networks-landing"
                                                    multiple="multiple" data-width="100%" name="networks[]">
                                                @foreach(App\Network::all() as $network)
                                                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-networks-landing">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-networks-landing">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Branches</label></div>
                                        <div class="col-lg-6">
                                            <select class="bootstrap-select select-all-values-branches-landing"
                                                    multiple="multiple" data-width="100%" name="branches[]">
                                                @foreach(App\Branches::all() as $branche)
                                                    <option value="{{ $branche->id }}">{{ $branche->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-branches-landing">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-branches-landing">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"><label class="text-semibold">Groups</label></div>
                                        <div class="col-lg-6">

                                            <select class="bootstrap-select select-all-values-groups-landing"
                                                    multiple="multiple" data-width="100%" name="groups[]">
                                                @foreach(App\Groups::where('as_system', 0)->get() as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback-left col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-info"
                                                        id="select-all-values-groups-landing">Select all
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                        id="deselect-all-values-groups-landing">Deselect all
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input type="checkbox" name="day-parting" class="switch day-parting"
                                                           data-on-text="On" data-off-text="Off" data-on-color="success"
                                                           data-off-color="default">
                                                    Day parting
                                                </label>
                                            </div>
                                            <div class="form-group col-lg-2 start" style="display: none">
                                                <label class="control-label">Start:</label>
                                                <input name="day-parting-start" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 end" style="display: none">
                                                <label class="control-label">End:</label>
                                                <input name="day-parting-end" type="text"
                                                       class="form-control input-xlg pickatime-limits">
                                            </div>
                                            <div class="form-group col-lg-2 sun" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sun-day" type="checkbox" class="switchery">
                                                        Sun
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 mon" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="mon-day" type="checkbox" class="switchery">
                                                        Mon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 tue" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="tue-day" type="checkbox" class="switchery">
                                                        Tue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 wed" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="wed-day" type="checkbox" class="switchery">
                                                        Wed
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 thu" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="thu-day" type="checkbox" class="switchery">
                                                        Thu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 fri" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="fri-day" type="checkbox" class="switchery">
                                                        Fri
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 sat" style="display: none">
                                                <div class="checkbox checkbox-switchery switchery-sm">
                                                    <label>
                                                        <input name="sat-day" type="checkbox" class="switchery">
                                                        Sat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                            onclick="document.forms['landing-page'].submit(); return false;">Save
                                        changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /primary modal -->

        <!-- Info modal -->
        <div id="modal_poll" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Survey Poll</h6>
                    </div>

                    <div class="modal-body">

                    </div>

                    <!--<div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Save changes</button>
                </div>-->
                </div>
            </div>
        </div>
        <!-- /info modal -->

        <!-- Info modal -->
        <div id="modal_edit" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Edit Campaign</h6>
                    </div>

                    <div class="modal-body">

                    </div>

                    <!--<div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Save changes</button>
                </div>-->
                </div>
            </div>
        </div>
        <!-- /info modal -->

        <!-- Info modal -->
        <div id="modal_offers" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Offers</h6>
                    </div>

                    <div class="modal-body">

                    </div>

                    <!--<div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Save changes</button>
                </div>-->
                </div>
            </div>
        </div>
        <!-- /info modal -->
        <!-- Scrollable datatable -->
        <div class="panel panel-flat">
            <!--<div class="panel-heading">
            <h5 class="panel-title">Braches Table</h5>
        </div>-->

            <div class="panel-body">
                <button type="button" class="btn bg-teal-400 btn-labeled" data-toggle="modal" data-target="#add_ads"><b><i
                                class="icon-megaphone"></i></b> Add New Ad
                </button>
                <a href="{{ asset('/') }}builder/index.php?type=campaign" target="_blank" class="btn bg-teal-400 btn-labeled"><b><i
                                class="icon-magic-wand2"></i></b> Website Builder
                </a>
            </div>
            <table class="table" width="100%" id="table-network">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Campaigns</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Reach</th>
                    <th>Impressions</th>
                    <th>Clicks</th>
                    <th>State</th>
                    <th class="text-center">Actions</th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
        <!-- /scrollable datatable -->
        @include('..back-end.footer')
    </div>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript"
            src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>

    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/uploaders/dropzone.min.js"></script>

    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/touch.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/inputs/maxlength.min.js"></script>

    <script type="text/javascript" src="assets/js/core/libraries/jasny_bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/editable/editable.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/extensions/mockjax.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/editable/address.js"></script>
    <script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/inputs/autosize.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/inputs/touchspin.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>

    <script type="text/javascript" src="assets/js/pages/components_popups.js"></script>

    <script type="text/javascript" src="assets/js/plugins/uploaders/fileinput.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/anytime.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>

    <script>

        // Table setup
        // ------------------------------

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            responsive: true,
            dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
            }

        });
        var table = $('#table-network').DataTable({
            responsive: {
                details: {
                    type: 'column',
                    target: -1
                }
            },
            ajax: {"url": "get_campaign", type: "get", data: {_token: $('meta[name="csrf-token"]').attr('content')}},
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
                {"data": "ad_name"},
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        if (data.type == "website")
                            return '<span class="label bg-teal-400 label-icon"><i class="icon-sphere"></i> Website click</span>';
                        if (data.type == "video")
                            return '<span class="label bg-purple-300 label-icon"><i class="icon-video-camera2"></i> Video</span>';
                        if (data.type == "offer")
                            return '<span class="label bg-warning-400 label-icon"><i class="icon-price-tag2"></i> Offer</span>';
                        if (data.type == "apps")
                            return '<span class="label bg-blue-400 label-icon"><i class="icon-mobile"></i> Mobile app</span>';
                        if (data.type == "survey")
                            return '<span class="label bg-teal-400 label-icon"><i class="icon-theater"></i> Survey</span>';
                        if (data.type == "social")
                            return '<span class="label bg-warning-400 label-icon"><i class="icon-facebook"></i> Social network</span>';
                        if (data.type == "landing")
                            return '<span class="label bg-blue-400 label-icon"><i class="icon-rocket"></i> Landing page</span>';
                    }
                },
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        var startdate = $.datepicker.formatDate("M d, yy", new Date(data.startdate));
                        return 'Start date ' + startdate;
                    }
                },
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        var enddate = $.datepicker.formatDate("M d, yy", new Date(data.enddate)); 
                        if (data.enddate) {
                            return 'End date ' + enddate;
                        }
                        else {
                            return "Never end";    
                            }
                    }
                },
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        return '<i class="icon-users2"> </i>' + data.reach_count + '/' + data.users_count + '<br /> <i class="icon-percent"></i>  ' + data.reach_percentage + ' %'
                    }
                },
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        return '<i class="icon-eye4"> </i>  ' + data.views_count
                    }
                },
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        return '<i class="icon-touch"> </i>  ' + data.clicks_count
                    }
                },
                {
                    "data": "state",
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        if (data.state == 1)
                            return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Active</span></button>';
                        else
                            return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Inactive</span></button>';

                    }
                },
                {
                    "data": null,
                    "searchable": false,
                    "render": function (type, full, data, meta) {
                        if (data.survey_type == "poll")
                            return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                    '<li><a href="#" class="preview" ><i class="icon-presentation"></i> Preview</a></li>' +
                                    '<li><a href="#" class="edit" ><i class="icon-pencil4"></i> Edit</a></li>' +
                                    '<li><a href="#" class="poll" ><i class="icon-wallet"></i> Poll</a></li>' +
                                    '<li><a href="#" class="delete"><i class="icon-trash-alt"></i> Delete</a></li>';
                        if (data.type == "offer")
                            return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                    '<li><a href="#" class="preview" ><i class="icon-presentation"></i> Preview</a></li>' +
                                    '<li><a href="#" class="edit" ><i class="icon-pencil4"></i> Edit</a></li>' +
                                    '<li><a href="#" class="offers" ><i class="icon-gift"></i> Offers</a></li>' +
                                    '<li><a href="#" class="delete"><i class="icon-trash-alt"></i> Delete</a></li>';
                        else
                            return '<ul class="icons-list"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a><ul class="dropdown-menu dropdown-menu-right">' +
                                    '<li><a href="#" class="preview" ><i class="icon-presentation"></i> Preview</a></li>' +
                                    '<li><a href="#" class="edit" ><i class="icon-pencil4"></i> Edit</a></li>' +
                                    '<li><a href="#" class="delete"><i class="icon-trash-alt"></i> Delete</a></li>';

                    }
                },
                {"data": null, "defaultContent": ""}
            ]
        });
        // Basic initialization
        $('.daterange-basic').daterangepicker({
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-default'
        });
        // Basic example
        $('.file-input').fileinput({
            browseLabel: 'Browse',
            browseIcon: '<i class="icon-file-plus"></i>',
            removeIcon: '<i class="icon-cross3"></i>',
            layoutTemplates: {
                icon: '<i class="icon-file-check"></i>'
            },
            allowedFileExtensions: ["jpg", "gif", "png"],
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
        $('.select-fixed-single').select2({
            minimumResultsForSearch: Infinity,
            width: 250
        });
        // Basic initialization
        $('.tokenfield').tokenfield();

        // Tabs
        // -------------------------

        // Basic example
        $(".jui-tabs-basic").tabs();

        function is_ascii(Str) {
            for (i = 0; i < Str.length; i++) {
                charCode = Str.charCodeAt(i);
                if (charCode > 127) {
                    return false;
                }
            }
            return true;
        }
        (function ($) {
            $.fn.smsCounter = function (selector) {
                doCount = function () {
                    var text = $(this).val();
                    if (is_ascii(text))limit = 160;
                    else limit = 70;

                    if (text.length > limit) {
                        if (is_ascii(text))limit = limit - 7; else limit = limit - 3;
                    }
                    diff = text.length % limit;
                    left = limit - diff;
                    count = ((text.length - diff) / limit) + 1;
                    if (diff == 0) {
                        left = 0;
                        count = count - 1;
                    }
                    $(selector).html('(' + count + ' <i class="icon-mail5"> </i> ) Remaining <span style="color: #103580;">' + left + '</span> ');
                };
                this.keyup(doCount);
                this.keyup();
            }
        })(jQuery)

        function changeFunc() {
            var selectBox = document.getElementById("selectBox");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            window.open("http://" + selectedValue + '/index.blade.php', '_blank');
            
            //alert(selectedValue);
        }    

        $(function () {
            $('.maxlength-options').smsCounter('.count');
        });

        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });

        // Basic initialization
        $('#table-network tbody').on('click', '.preview', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            window.open("/preview/" + data.id, '_blank');
        });
        // Alert combination
        $('#table-network tbody').on('click', '.delete', function () {
            var that = this;
            swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover campaign again!",
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
                                url: 'delete_campaign/' + data.id,
                                success: function (data) {
                                    table.row($(that).parents('tr')).remove().draw();
                                    swal("Cancelled", "Campaign is safe :)", "error");

                                },
                                error: function () {
                                    swal("Deleted!", "Campaign has been deleted.", "success");
                                }
                            });
                        } else {
                            swal("Cancelled", "Your delete has been Cancelled :)", "success");
                        }

                    });

        });
        $('#table-network tbody').on('click', '.edit', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_edit').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'get_campaign/' + data.id,
                success: function (response) {
                    //console.log( response );
                    //$('.id').append(data.id);
                    jQuery('#modal_edit .modal-body').html(response);

                }
            });

        });

        $('#table-network tbody').on('click', '.offers', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            // LOADING THE AJAX MODAL
            jQuery('#modal_offers').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'offers/' + data.id,
                success: function (response) {
                    jQuery('#modal_offers .modal-body').html(response);
                }
            });
        });

        $('#table-network tbody').on('click', '.poll', function () {
            var that = this;
            var data = table.row($(that).parents('tr')).data();
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            //console.log(a);
            // LOADING THE AJAX MODAL
            jQuery('#modal_poll').modal('show', {backdrop: 'true'});

            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: 'poll/' + data.id,
                success: function (response) {
                    jQuery('#modal_poll .modal-body').html(response);

                }
            });

        });
        $('#table-network tbody').on('click', 'button.btn-ladda-spinner', function () {
            var data = table.row($(this).parents('tr')).data(),
                    sus = ($(this).hasClass('btn-success')) ? false : true,
                    that = this;
            if (data == null) {
                data = table.row($(that).parents('tr').prev()).data();
            }
            $(this).text('Loading...');
            $.ajax({
                url: 'campaign_state/' + data.id + '/' + sus,
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
        // Time limits
        $('.pickatime-limits').pickatime({
            formatSubmit: 'HH:i',
            hiddenName: true
        });
        // Bootstrap switch
        // ------------------------------

        $(".switch").bootstrapSwitch();
        $('.day-parting').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === false) {
                $('.start').hide();
                $('.end').hide();
                $('.sun').hide();
                $('.mon').hide();
                $('.tue').hide();
                $('.wed').hide();
                $('.thu').hide();
                $('.fri').hide();
                $('.sat').hide();
            } else {
                $('.start').show();
                $('.end').show();
                $('.sun').show();
                $('.mon').show();
                $('.tue').show();
                $('.wed').show();
                $('.thu').show();
                $('.fri').show();
                $('.sat').show();
            }
        });
        $(document).ready(function(){
            $('input[type="checkbox"]').click(function(){
                if($(this).is(":checked")){
                $('.option-skip-value').show();
                }
                else if($(this).is(":not(:checked)")){
                $('.option-skip-value').hide();
                }
            });
        });
   
        $('.offer-sms').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === false) {
                $('.sms-message').hide();

            } else {
                $('.sms-message').show();

            }
        });

    
        $('.offer-email').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === false) {
                $('.email-message').hide();
            } else {
                $('.email-message').show();

            }
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

        $('.survey-type').on('switchChange.bootstrapSwitch', function (event, state) {
            if (state === true) {
                $('.poll').show();
                $('.survey-types').show();
                $('.rating').hide();
            } else {
                $('.survey-types').show();
                $('.rating').show();
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
        $('#select-all-values-networks-website').on('click', function () {
            $('.select-all-values-networks-website').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-networks-website').on('click', function () {
            $('.select-all-values-networks-website').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-networks-video').on('click', function () {
            $('.select-all-values-networks-video').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-networks-video').on('click', function () {
            $('.select-all-values-networks-video').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-networks-offer').on('click', function () {
            $('.select-all-values-networks-offer').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-networks-offer').on('click', function () {
            $('.select-all-values-networks-offer').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-networks-apps').on('click', function () {
            $('.select-all-values-networks-apps').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-networks-apps').on('click', function () {
            $('.select-all-values-networks-apps').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-networks-survey').on('click', function () {
            $('.select-all-values-networks-survey').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-networks-survey').on('click', function () {
            $('.select-all-values-networks-survey').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-networks-social').on('click', function () {
            $('.select-all-values-networks-social').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-networks-social').on('click', function () {
            $('.select-all-values-networks-social').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-networks-landing').on('click', function () {
            $('.select-all-values-networks-landing').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-networks-landing').on('click', function () {
            $('.select-all-values-networks-landing').selectpicker('deselectAll');
        });


        // Deselect all method
        $('#select-all-values-branches-website').on('click', function () {
            $('.select-all-values-branches-website').selectpicker('selectAll');
        });

        // Deselect all method
        $('#deselect-all-values-branches-website').on('click', function () {
            $('.select-all-values-branches-website').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-branches-video').on('click', function () {
            $('.select-all-values-branches-video').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-branches-video').on('click', function () {
            $('.select-all-values-branches-video').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-branches-offer').on('click', function () {
            $('.select-all-values-branches-offer').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-branches-offer').on('click', function () {
            $('.select-all-values-branches-offer').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-branches-apps').on('click', function () {
            $('.select-all-values-branches-apps').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-branches-apps').on('click', function () {
            $('.select-all-values-branches-apps').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-branches-survey').on('click', function () {
            $('.select-all-values-branches-survey').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-branches-survey').on('click', function () {
            $('.select-all-values-branches-survey').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-branches-social').on('click', function () {
            $('.select-all-values-branches-social').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-branches-social').on('click', function () {
            $('.select-all-values-branches-social').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-branches-landing').on('click', function () {
            $('.select-all-values-branches-landing').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-branches-landing').on('click', function () {
            $('.select-all-values-branches-landing').selectpicker('deselectAll');
        });


        // Deselect all method
        $('#select-all-values-groups-website').on('click', function () {
            $('.select-all-values-groups-website').selectpicker('selectAll');
        });

        // Deselect all method
        $('#deselect-all-values-groups-website').on('click', function () {
            $('.select-all-values-groups-website').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-groups-video').on('click', function () {
            $('.select-all-values-groups-video').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-groups-video').on('click', function () {
            $('.select-all-values-groups-video').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-groups-offer').on('click', function () {
            $('.select-all-values-groups-offer').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-groups-offer').on('click', function () {
            $('.select-all-values-groups-offer').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-groups-apps').on('click', function () {
            $('.select-all-values-groups-apps').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-groups-apps').on('click', function () {
            $('.select-all-values-groups-apps').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-groups-survey').on('click', function () {
            $('.select-all-values-groups-survey').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-groups-survey').on('click', function () {
            $('.select-all-values-groups-survey').selectpicker('deselectAll');
        });

        // Select all method
        $('#select-all-values-groups-social').on('click', function () {
            $('.select-all-values-groups-social').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-groups-social').on('click', function () {
            $('.select-all-values-groups-social').selectpicker('deselectAll');
        });


        // Select all method
        $('#select-all-values-groups-landing').on('click', function () {
            $('.select-all-values-groups-landing').selectpicker('selectAll');
        });


        // Deselect all method
        $('#deselect-all-values-groups-landing').on('click', function () {
            $('.select-all-values-groups-landing').selectpicker('deselectAll');
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

        $('.timepicker').timepicki({
            show_meridian: false,
            min_hour_value: 0,
            max_hour_value: 23,
            overflow_minutes: true,
            increase_direction: 'up',
            disable_keyboard_mobile: true
        });
    </script>
@endsection