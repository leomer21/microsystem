@extends('.back-end.layouts.master')

@section('title', 'Users')
@section('sidebar')
    
    <?php /* @if(App\Settings::where('type', 'marketing_enable')->value('state') == 1) --> */ ?>
    <?php
        // pms integration status
        if(App\Models\Pms::where('state', '1')->count() > 0){ $pmsIntegration = 1; }else{ $pmsIntegration = 0; }
    ?>
    <div class="sidebar sidebar-secondary sidebar-default">
        <div class="sidebar-content">

            <!-- ///////////////////////////////////////////////////////////// Start Filters ///////////////////////////////////////////////////////////////// -->

            <!--Filter Branches for -->
            @foreach ($networks as $network)
                <div class="sidebar-category">
                    <div class="category-title category-collapsed">
                        <span>{{ $network->name }} Branches</span>
                        <ul class="icons-list">
                            <li><a href="#" data-action="collapse"></a></li>
                        </ul>
                    </div>

                    <div class="category-content" @if(app('request')->input('network') ) style="display: block;"
                         @else style="display: none;" @endif >
                        @foreach ($branchs as $branch)
                            <?php  if ($branch->network_id != $network->id) continue; ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="styled network" value="{{ $branch->id }}">
                                    {{ $branch->name }} ({{ $branch->b_name }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <!-- /Filter Branches for -->

            <!--Filter Groups -->
            <div class="sidebar-category ">
                <div class="category-title category-collapsed">
                    <span>{{ trans('search.groups') }}</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>
                <div class="category-content" @if(app('request')->input('groups') ) style="display: block;"
                     @else style="display: none;" @endif >
                    @foreach ($area_groups as $area_group)
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled groups" value="{{ $area_group->id }}">
                                {{ $area_group->name }} ({{ $area_group->count_g }})
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- /Groups -->

            <!--Filter Frequency -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span> Visits FREQUENCY </span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('frequency') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>No of Visits</label>
                    <input class="frequency form-control" type="number" value="2" min="0">
                    <br>
                    <label>Between</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control daterange-weeknumbers" value="" id="data_find3">
                    </div>
                    <div class="input-group">
                        <h6></h6>
                        <button type="button" class="btn btn-info btn-labeled btn-xs resetFrequency"><b><i class="icon-reset"></i></b> Reset</button>
                    </div>
                </div>
            </div>
            <!-- /actions -->

            <!-- VIEW USERS CHARGED-->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>{{ trans('search.Users_charged') }}</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content"
                     @if(app('request')->input('Users_not_charged_from') || app('request')->input('Users_charged_from')) style="display: block;"
                     @else style="display: none;" @endif>
                    <label>{{ trans('search.Users_charged') }}: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control daterange-weeknumbers" value="" id="data_find1">
                    </div>

                    <div class="form-group">
                        <h6></h6>
                    <button type="button" class="btn btn-info btn-labeled btn-xs resetCharged"><b><i class="icon-reset"></i></b> Reset</button>
                    </div>
                    <br>
                    <label>{{ trans('search.Users_not_charged') }}: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control daterange-weeknumbers" value="" id="data_find2">
                    </div>
                    <div class="form-group">
                        <h6></h6>
                        <button type="button" class="btn btn-info btn-labeled btn-xs resetNotCharged"><b><i class="icon-reset"></i></b> Reset</button>
                    </div>
                </div>
            </div>
            <!--VIEW USERS CHARGED -->


            <!--Filter Gender -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>{{ trans('search.gender') }}</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>


                <div class="category-content "
                     @if(app('request')->input('male')=="on" or app('request')->input('female')=="on" or app('request')->input('Unknown')=="on") style="display: block;"
                     @else style="display: none;" @endif >
                    @foreach ($genders as $gender)
                        <?php  if($gender->u_gender == 1){ ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled male">

                                Male <span> ({{ $gender->count_g }}) </span>
                            </label>
                        </div>
                        <? }elseif($gender->u_gender == 0){?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled female">

                                Female <span> ({{ $gender->count_g }}) </span>
                            </label>
                        </div>
                        <?} else{?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled Unknown">

                                Unknown <span> ({{ $gender->count_g }}) </span>
                            </label>
                        </div>
                        <?}?>
                    @endforeach
                </div>

            </div>
            <!-- /Gender -->

            <?php /*
            <!--Filter statue  -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed ">
                    <span> status </span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content"
                     @if(app('request')->input('inactive')=="on" or app('request')->input('active')=="on") style="display: block;"
                     @else style="display: none;" @endif >
                    @foreach ($statues as $statue)
                        <?php  if($statue->u_state == 1){ ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled active">
                                Active <span> ({{ $statue->count }}) </span>
                            </label>
                        </div>
                        <? }else{?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled inactive">
                                Inactive <span> ({{ $statue->count }}) </span>
                            </label>
                        </div>

                        <?}?>
                    @endforeach
                </div>
            </div>
            <!-- statue  -->
            */ ?>

            <!--Filter Online  -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span> Internet </span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('online')=="on") style="display: block;"
                     @else style="display: none;" @endif >
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="styled online">
                            Online
                            Users<span> ( <?php  print App\Models\RadacctActiveUsers::whereNull('acctstoptime')->count() ?>
                                )  </span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- Online  -->

            <!--Filter Suspended -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span> suspension </span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content"
                     @if(app('request')->input('suspend')=="on" or app('request')->input('unsuspend')=="on") style="display: block;"
                     @else style="display: none;" @endif >
                    @foreach ($suspends as $suspend)
                        <?php  if($suspend->suspend == 1){ ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled suspend">
                                suspend <span> ({{ $suspend->count }}) </span>
                            </label>
                        </div>
                        <?}else{?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled unsuspend">
                                unsuspend <span> ({{ $suspend->count }}) </span>
                            </label>
                        </div>
                        <?}?>
                    @endforeach
                </div>
            </div>
            <!-- /Suspended -->


            <!--Filter Registration state -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Registration state</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content"
                     @if(app('request')->input('register')=="on" or app('request')->input('adminconfirm')=="on" or app('request')->input('adminconfirm')=="on") style="display: block;"
                     @else style="display: none;" @endif >
                    @foreach ($registerconfirm as $confirm)
                        <?php  if($confirm->Registration_type == 2){ ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled register">
                                Registered ({{$confirm->count}}) </span>
                            </label>
                        </div>

                        <?}else if($confirm->Registration_type == 0){?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled adminconfirm">
                                Waiting admin confirm ({{$confirm->count}}) </span>
                            </label>
                        </div>

                        <?}else if($confirm->Registration_type == 1){?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled smsconfirm">
                                Waiting sms confirm ({{$confirm->count}}) </span>
                            </label>
                        </div>
                        <?}?>
                    @endforeach

                </div>
            </div>
            <!-- /Registration state -->
            
            <!--Filter Country -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>{{ trans('search.country') }}</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('country') ) style="display: block;"
                     @else style="display: none;" @endif >
                    @foreach ($countrys as $country)
                        <?php  if($country->country != null){ ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled country" value="{{ $country->country }}">
                                {{ $country->country}} <span> ({{ $country->count}})</span>
                            </label>
                        </div>
                        <?}?>
                    @endforeach
                </div>
            </div>
            <!-- /Country -->

            <!--Filter sort by most visited -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Sort by </span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('SortByMostVisited') ) style="display: block;" @else style="display: none;" @endif >
                     <div class="checkbox">
                        <label>
                            <input type="checkbox" class="styled SortByMostVisited" value="SortByMostVisited">
                            Most visited
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="styled SortByInternetConsumption" value="SortByInternetConsumption">
                            Internet consumption
                        </label>
                    </div>
                </div>
            </div>
            <!-- /Filter sort by most visited -->

        </div>
    </div>
    <!-- /main content -->
    <!-- /////////////////////////////////////////////////////////////End Filters ///////////////////////////////////////////////////////////////// -->
    <!-- Info modal -->
    <div id="packages" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Charge Packages</h6>
                </div>

                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-info">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal -->
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
                        <?php $random = rand(11111111,999999999); ?>
                        @if( App\Network::where('r_type', '2')->count() > 0 and App\Settings::where('type', 'pms_integration')->value('state') != 1 )
                            <!-- SMS verification enabled so we will generate any username and password -->
                            <input name="username" type="hidden" value="{{$random}}">
                            <input name="password" type="hidden" value="{{$random}}">
                        @else
                            <div class="form-group has-feedback-left">
                                <label class="text-semibold col-lg-2 control-label">Username *</label>
                                <div class="col-lg-10">
                                    <input name="username" type="text" class="form-control input-xlg"
                                        placeholder="Username">
                                    <div class="form-control-feedback">
                                        <i class="icon-vcard"></i>
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
                        @endif
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
						@if ( Auth::user()->type == 1 )
                        <div class="form-group">
                            <label class="control-label col-lg-3">Self Rules</label>
                            <div class="col-lg-3">
                                <input id="self-ruless" type="checkbox" name="selfrulesState" value="1"
                                       class="switch">
                            </div>
                            <div class="done2"></div>

                        </div>
						@else
							
						@endif
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

                        <!--<div class="form-group">
                            <label class="control-label col-lg-2">URL Redirect</label>
                            <div class="col-lg-8">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="url_redirect" type="text" class="form-control input-xlg">
                                    <div class="form-control-feedback">
                                        http://
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-lg-2">URL Interval</label>
                            <div class="col-lg-4">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="url_redirect_Interval" type="text" class="form-control timepicker">
                                    <div class="form-control-feedback">
                                        <i class="icon-watch2"></i>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label col-lg-2">IDLE Timeout</label>
                            <div class="col-lg-4">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="idle_timeout" type="text" class="form-control timepicker">
                                    <div class="form-control-feedback">
                                        <i class="icon-alarm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group"><label class="control-label col-lg-2">Auto Login</label>
                            <div class="col-lg-4">
                                <div class="form-group has-feedback has-feedback-left auto_login">
                                    <input id="auto_login" type="checkbox" name="auto_login" value="1" class="switch">
                                </div>
                            </div>
                            <label class="control-label col-lg-2">IDLE Timeout</label>
                            <div class="col-lg-4">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="limited_devices" class="frequency form-control" type="number" value="0"
                                           min="0">
                                    <div class="form-control-feedback">
                                        <i class="icon-screen3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12 auto-login-expiry" style="display:none;">
                            <label class="control-label col-lg-3">Auto login Expiry(Days)</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="auto-login-expiry" class="frequency form-control" type="number" min="1"
                                           value="0">
                                    <div class="form-control-feedback">
                                        <i class="icon-alarm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Session Time</label>
                            <div class="col-lg-4">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="session_time" type="text" class="form-control timepicker">
                                    <div class="form-control-feedback">
                                        <i class="icon-alarm"></i>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label col-lg-3">Concurrent sessions</label>
                            <div class="col-lg-3">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="u_multi_session" class="frequency form-control" type="number" value="1"
                                           min="1">
                                    <div class="form-control-feedback">
                                        <i class="icon-alarm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">Upload (MB)</label>
                            <div class="col-lg-4">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="quota_limit_upload" class="frequency form-control" type="number"
                                           min="1">
                                    <div class="form-control-feedback">
                                        <i class="icon-cloud-upload"></i>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label col-lg-2">Download (MB)</label>
                            <div class="col-lg-4">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="quota_limit_download" class="frequency form-control" type="number"
                                           min="1">
                                    <div class="form-control-feedback">
                                        <i class="icon-cloud-download"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-2">Total (MB)</label>
                            <div class="col-lg-8">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="quota_limit_total" class="frequency form-control" type="number"
                                           min="1">
                                    <div class="form-control-feedback">
                                        <i class="icon-cloud"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="start_speed control-label col-lg-2">Start Speed</label>
                            <div class="start_speed col-lg-3">
                                <div class="input-group">
                                    <input name="speed_limit1" class="frequency form-control" type="text"
                                           placeholder="Upload" onkeypress="return isNumber(event)">
                                </div>
                            </div>
                            <div class="start_speed col-lg-2">
                                <div class="input-group">
                                    <select class="select-fixed-single75" name="stype1">
                                        <option value="K">Kilobyte</option>
                                        <option value="M">Megabyte</option>
                                    </select>
                                </div>
                            </div>

                            <div class="start_speed col-lg-3">
                                <div class="input-group">
                                    <input name="speed_limit2" class="frequency form-control" type="text"
                                           placeholder="Download" onkeypress="return isNumber(event)">
                                </div>
                            </div>
                            <div class="start_speed col-lg-2">
                                <div class="input-group">
                                    <select class="select-fixed-single75" name="stype2">
                                        <option value="K">Kilobyte</option>
                                        <option value="M">Megabyte</option>
                                    </select>
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
                            <div class="equation form-group col-lg-12 has-feedback-left" style="display: none;">
                                <label class="equation control-label col-lg-3"></label>
                                <div class="equation col-lg-9">
                                    <div class="equation form-group has-feedback has-feedback-left">
                                        <input name="equationstart" class="frequency form-control" type="text"
                                               placeholder="Equation start speed">
                                        <div class="form-control-feedback">
                                            <i class="icon-cloud"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class=" control-label col-lg-3">End Speed</label>
                            <div class=" col-lg-3">
                                <input id="self-rules2" type="checkbox" name="selfrules2" class="switch">
                            </div>
                            <div class="done"><input name="values" type="hidden" value="0"></div>
                            <div class=" form-group col-lg-12">
                                <label class="control-label col-lg-2"></label>
                                <div class="endspeed col-lg-3" style="display: none;">
                                    <div class=" input-group">
                                        <input name="end_speed1" class="endspeed frequency form-control" type="text"
                                               placeholder="Download" onkeypress="return isNumber(event)">
                                    </div>
                                </div>

                                <div class="endspeed col-lg-2" style="display: none;">
                                    <div class="endspeed input-group">
                                        <select class="endspeed select-fixed-single75" name="etype1">
                                            <option value="K">Kilobyte</option>
                                            <option value="M">Megabyte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="endspeed col-lg-3" style="display: none;">
                                    <div class="endspeedd input-group">
                                        <input name="end_speed2" class="endspeed frequency form-control" type="text"
                                               placeholder="Download" onkeypress="return isNumber(event)">
                                    </div>
                                </div>

                                <div class="endspeed col-lg-2" style="display: none;">
                                    <div class="endspeed input-group">
                                        <select class="endspeed select-fixed-single75" name="etype2">
                                            <option value="K">Kilobyte</option>
                                            <option value="M">Megabyte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="equation_endspeed form-group col-lg-12" style="display: none;">
                                    <label class=" control-label col-lg-3"></label>
                                    <div class=" col-lg-8">
                                        <div class=" checkbox">
                                            <label>
                                                <input name="equationcheckss" type="checkbox" class="endspeed styled"
                                                       id="equationcheckss">
                                                Equation speed (Advanced mode )
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="equation_end form-group col-lg-12 has-feedback-left" style="display: none;">
                                    <label class="control-label col-lg-3"></label>
                                    <div class="col-lg-9">
                                        <div class="form-group has-feedback has-feedback-left">
                                            <input name="equationend" class="frequency form-control" type="text"
                                                   placeholder="Equation end speed">
                                            <div class="form-control-feedback">
                                                <i class="icon-cloud"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->

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
    <!-- Info modal_campaign -->
    <div id="modal_campaign" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Start Campaign</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold">Select campaign methods</h6>
                    <div class="col-dm-6">
                        <div class="well well-sm">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="styled" id="c_email">
                                    <i class="icon-envelop5"></i> E-Mail
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="styled" id="c_sms">
                                    <i class="icon-mobile"></i> SMS
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="styled" id="c_notifications">
                                    <i class="icon-arrow-right16"></i> Push notifications
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="styled" id="c_messages">
                                    <i class="icon-comment-discussion"></i> WhatsApp
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-4">
                        <button id="btn-campaign" onclick="loadingIcon_remove_disabled()" type="button" class="btn btn-primary"><i class="icon-add"></i> Start
                            Campaign
                        </button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-info">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal campaign -->

    <!-- Info modal_ai_campaign -->
    <div id="modal_ai_campaign" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Start AI Campaign</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold">Select AI campaign methods</h6>
                    <div class="col-dm-6">
                        <div class="well well-sm">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="ai_campaign" class="styled" id="c_email_ai">
                                    <i class="icon-envelop5"></i> E-Mail
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="ai_campaign" class="styled" id="c_sms_ai">
                                    <i class="icon-mobile"></i> SMS
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="ai_campaign" class="styled" id="c_notifications_ai">
                                    <i class="icon-arrow-right16"></i> Push notifications
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="ai_campaign" class="styled" id="c_messages_ai">
                                    <i class="icon-comment-discussion"></i> WhatsApp
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-4">
                        <button id="btn-ai-campaign" onclick="loadingIcon_ai_remove_disabled()" type="button" class="btn btn-primary"><i class="icon-add"></i> Start AI
                            Campaign
                        </button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-info">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal AI campaign -->

    <!-- Info modal -->
    <div id="modal_group_switch" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Change users group</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold">Select your target group:</h6>

                        <!-- <div class="form-group col-lg-12"> -->
                            <div id="divgruops" >
                                <!-- <label class="control-label col-lg-3">Gruop</label> -->
                                <!-- <div class="col-lg-9"> -->
                                    <center> <select data-placeholder="Select an option" class="gruop-edit" id="target_group_switch" name="target_group_switch" > </select> </center>
                                <!-- </div> -->
                            </div>
                            <br>
                        <!-- </div> -->
                    <!-- <hr> -->
                    <!-- <div class="col-md-4"> -->
                        <center> <button id="btn-group_switch" type="button" data-dismiss="modal" class="btn btn-primary"><i class="icon-move-up2"></i> Move NOW! </button> </center>
                    <!-- </div> -->
                            <br>
                    <!-- <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">Charge Packages</h6>
                    </div> -->
                    

                </div>

            </div>
        </div>
    </div>
    <!-- /info modal -->

    <!-- Info modal -->
    <div id="user_upload" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-brown-400">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Download and upload users</h6>

                </div>

                <div class="modal-body">
                    <h6 class="text-semibold"></h6>
                    <div class="panel-body">
                        <div class="tabbable">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="active"><a href="#left-icon-tab1" data-toggle="tab"><i
                                                class="icon-file-upload position-left"></i> Upload</a></li>
                                <li><a href="#left-icon-tab2" data-toggle="tab"><i
                                                class="icon-file-download position-left"></i> Download</a></li>

                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="left-icon-tab1">
                                    <form action="{{ url('upload_excel') }}" class="dropzone"
                                          enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    </form>
                                </div>

                                <div class="tab-pane" id="left-icon-tab2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" data-toggle="context"
                                               data-target=".context-table">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th class="text-center" style="width: 100px;">Download</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php /*
                                            <tr>
                                                <td><a href="{{ URL::to('downloaddemoExcel/xlsx') }}">Demo Excel
                                                        Sheet.</a></td>
                                                <td class="text-center">
                                                    <ul class="icons-list">
                                                        <li><a href="{{ URL::to('downloaddemoExcel/xlsx') }}"><i
                                                                        class="icon-download"></i></a></li>
                                                        <!--<li><a href="#"><i class="icon-three-bars"></i></a></li>-->
                                                    </ul>
                                                </td>
                                            </tr>
                                            */ ?>
                                            <tr>
                                                <td><a href="{{ URL::to('Example.xlsx') }}">Excel
                                                        Sheet Example <br>(Please fill it out and upload it again.)</a></td>
                                                <td class="text-center">
                                                    <ul class="icons-list">
                                                        <li><a href="{{ URL::to('Example.xlsx') }}"><i
                                                                        class="icon-download"></i></a></li>
                                                        <!--<li><a href="#"><i class="icon-three-bars"></i></a></li>-->
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="{{ URL::to('downloadFullExceldata/xlsx') }}">Full Data
                                                        Excel Sheet.</a></td>
                                                <td class="text-center">
                                                    <ul class="icons-list">
                                                        <li><a href="{{ URL::to('downloadFullExceldata/xlsx') }}"><i
                                                                        class="icon-download"></i></a></li>
                                                        <!--<li><a href="#"><i class="icon-three-bars"></i></a></li>-->
                                                    </ul>
                                                </td>
                                            </tr>
                                            </tbody>

                                        </table>
                                    <!--<a href="{{ URL::to('downloadExcel/xlsx') }}"><button type="button" class="btn bg-teal-400 btn-labeled"><b><i class="icon-server"></i></b> Download</button></a>-->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal -->

    <!-- Info modal -->
    <div id="email_campaign" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Start Campaign</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold">Selcet campaign methods</h6>
                    <div id="f_1">
                        <form class="stepy-validation" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <fieldset title="1">
                                <legend class="text-semibold t_1_1"></legend>
                                <div class="f_1_1"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon()" id="loadingButton" class="btn btn-primary stepy-finish">Submit <i
                                        class="icon-check position-right"></i></button>

                        </form>
                    </div>
                    <div id="f_2">
                        <form class="stepy-validation" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <fieldset title="1">
                                <legend class="text-semibold t_1_1"></legend>
                                <div class="f_2_1"></div>
                            </fieldset>
                            <fieldset title="2">
                                <legend class="text-semibold t_2_2"></legend>
                                <div class="f_2_2"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon()" id="loadingButton" class="btn btn-primary stepy-finish">Submit <i
                                        class="icon-check position-right"></i></button>
                        </form>
                    </div>
                    <div id="f_3">
                        <form class="stepy-validation" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <fieldset title="1">
                                <legend class="text-semibold t_1_1"></legend>
                                <div class="f_3_1"></div>
                            </fieldset>
                            <fieldset title="2">
                                <legend class="text-semibold t_2_2"></legend>
                                <div class="f_3_2"></div>
                            </fieldset>
                            <fieldset title="3">
                                <legend class="text-semibold t_3_3"></legend>
                                <div class="f_3_3"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon()" id="loadingButton" class="btn btn-primary stepy-finish">Submit <i
                                        class="icon-check position-right"></i></button>
                        </form>
                    </div>
                    <div id="f_4">
                        <form class="stepy-validation" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <fieldset title="1">
                                <legend class="text-semibold t_1_1"></legend>
                                <div class="f_4_1"></div>
                            </fieldset>
                            <fieldset title="2">
                                <legend class="text-semibold t_2_2"></legend>
                                <div class="f_4_2"></div>
                            </fieldset>
                            <fieldset title="3">
                                <legend class="text-semibold t_3_3"></legend>
                                <div class="f_4_3"></div>
                            </fieldset>
                            <fieldset title="4">
                                <legend class="text-semibold t_4_4"></legend>
                                <div class="f_4_4"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon()" id="loadingButton" class="btn btn-primary stepy-finish">Submit <i
                                        class="icon-check position-right"></i></button>
                                        
                        </form>
                        <script>
                            function loadingIcon() {
                                $('#loadingButton').attr("disabled", "disabled");
                                // $('#loadingButton').value("Done");
                                // document.getElementById('loadingButton').value= "Done";
                            }
                            function loadingIcon_remove_disabled() {
                                $('#loadingButton').removeAttr('disabled');
                            }
                        </script>
                    </div>
                    <hr>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-info">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal -->

    <!-- Info AI modal -->
    <div id="email_ai_campaign" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Start AI Campaign</h6>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold">Selcet campaign methods</h6>
                    <div id="f_1_ai">
                        <form class="stepy-validation-ai" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <fieldset title="1">
                                <legend class="text-semibold t_1_1_ai"></legend>
                                <div class="f_1_1_ai"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon_ai()" id="loadingButton_ai" class="btn btn-primary stepy-finish-ai">Submit <i
                                        class="icon-check position-right"></i></button>

                        </form>
                    </div>
                    <div id="f_2_ai">
                        <form class="stepy-validation-ai" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <fieldset title="1">
                                <legend class="text-semibold t_1_1_ai"></legend>
                                <div class="f_2_1_ai"></div>
                            </fieldset>
                            <fieldset title="2">
                                <legend class="text-semibold t_2_2_ai"></legend>
                                <div class="f_2_2_ai"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon_ai()" id="loadingButton_ai" class="btn btn-primary stepy-finish-ai">Submit <i
                                        class="icon-check position-right"></i></button>
                        </form>
                    </div>
                    <div id="f_3_ai">
                        <form class="stepy-validation-ai" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <fieldset title="1">
                                <legend class="text-semibold t_1_1_ai"></legend>
                                <div class="f_3_1_ai"></div>
                            </fieldset>
                            <fieldset title="2">
                                <legend class="text-semibold t_2_2_ai"></legend>
                                <div class="f_3_2_ai"></div>
                            </fieldset>
                            <fieldset title="3">
                                <legend class="text-semibold t_3_3_ai"></legend>
                                <div class="f_3_3_ai"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon_ai()" id="loadingButton_ai" class="btn btn-primary stepy-finish-ai">Submit <i
                                        class="icon-check position-right"></i></button>
                        </form>
                    </div>
                    <div id="f_4_ai">
                        <form class="stepy-validation-ai" action="#">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <fieldset title="1">
                                <legend class="text-semibold t_1_1_ai"></legend>
                                <div class="f_4_1_ai"></div>
                            </fieldset>
                            <fieldset title="2">
                                <legend class="text-semibold t_2_2_ai"></legend>
                                <div class="f_4_2_ai"></div>
                            </fieldset>
                            <fieldset title="3">
                                <legend class="text-semibold t_3_3_ai"></legend>
                                <div class="f_4_3_ai"></div>
                            </fieldset>
                            <fieldset title="4">
                                <legend class="text-semibold t_4_4_ai"></legend>
                                <div class="f_4_4_ai"></div>
                            </fieldset>
                            <button type="submit" onclick="loadingIcon_ai()" id="loadingButton_ai" class="btn btn-primary stepy-finish-ai">Submit <i
                                        class="icon-check position-right"></i></button>
                                        
                        </form>
                        <script>
                            function loadingIcon_ai() {
                                $('#loadingButton_ai').attr("disabled", "disabled");
                            }
                            function loadingIcon_ai_remove_disabled() {
                                $('#loadingButton_ai').removeAttr('disabled');
                            }
                        </script>
                    </div>
                    <hr>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-info">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal -->

    <!-- Info modal -->
    <div id="edit_user" class="modal fade">
        <div class="modal-dialog modal-ls">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">User Profile</h6>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info"
                            onclick="document.forms['edituser'].submit(); return false;">Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /info modal -->

    <!-- Modal with basic title -->
    <div id="modal_search_result" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span class="text-semibold modal-title">Saved Searches</span>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" data-toggle="context" data-target=".context-table">
                            <thead>
                            <tr>

                                <th>Title</th>
                                <th>Created</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <form action="{{ url('add_search') }}" method="POST" id="add" class="form-horizontal">

                                    <td><input name="title" id="title" type="text" class="form-control"></td>
                                    <td><input name="created" id="created" value="<?php echo date("Y-m-d"); ?>"
                                               type="text" class="form-control pickadate"></td>
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <li><a href="#" onclick="document.forms['add'].submit(); return false;"><i
                                                            class="icon-checkmark"></i></a></li>
                                        </ul>
                                    </td>
                                </form>
                            </tr>
                            @foreach ($searchresults as $searchresult)
                                <tr>

                                    <td><a href="{{ $searchresult->link }}">{{ $searchresult->title }}</a></td>
                                    <td>{{ $searchresult->created }}</td>
                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <form action="{{ url('delete/'.$searchresult->id) }}" method="GET"
                                                  id="delete{{ $searchresult->id }}">
                                                <li><a href="#"
                                                       onclick="document.forms['delete{{ $searchresult->id }}'].submit(); return false;"><i
                                                                class="icon-close2"></i></a></li>
                                            </form>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- /modal with basic title -->

    <!-- Modal with basic title -->
    <div id="timeline" class="modal fade">
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
    <script>
        @if(isset($chargepackage[0]) and $chargepackage[0])
                    $('#status').modal('show');
        @endif
    </script>
    <!-- Basic modal -->
    <div id="status" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span class="text-semibold modal-title">Message</span>
                </div>

                <div class="modal-body">

                    @if(isset($chargepackage[0]))
                        @if($chargepackage[0] == "Error package conflict")
                            <center><h2> You still have period in your account, </h2><h4>
                                    If you need to discard your last package and buy new package Click (Charge).
                                </h4></center>
                            <form action="{{ url('chargePackages/'. $userid[0] .'/'. $packageid[0] .'1/'. Auth::user()->id ) }}"
                                  method="get" id="conflict" class="form-horizontal">
                                {{ csrf_field() }}
                                <center>
                                    <button type="submit" class="btn btn-default rex-primary-btn-effect"
                                            href="javascript:void(0)"
                                            onclick="document.forms['conflict'].submit(); return false;">Charge
                                    </button>
                                </center>
                            </form>
                        @else
                            <center><h2> {{$chargepackage[0]}} </h2></center>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /basic modal -->
    <?php
    $url = url()->full();
    $split_full_url_1 = explode('/', $url);
    $split_full_url_2 = explode('=', $split_full_url_1[3]);

    if ($split_full_url_2[0] == "search?message") {
        $split = explode('=', $url);
        if (isset($split[1])) {
            $check = strpos($split[1], "message");
        }
    }

    ?>

    <!-- Main content -->
    <div class="content-wrapper">
        <div class="panel-body">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    @if(isset($check))
                        <?php $message = urldecode($split[1]); ?>
                        <div class="alert alert-primary alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span
                                        class="sr-only">Close</span></button>
                            <span class="text-semibold">Charage state!</span> {{ $message }}
                        </div>
                        <br>
                        <br>
                        <br>
                    @endif
                    @if(App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                        <h5 class="panel-title">@if(Auth::user()->type == 1) Manage accounts and start your campaign (in one
                        shot) @else
                            Credit: {{ Auth::user()->credit }} {{ App\Settings::where('type', 'currency')->value('value') }} @endif</h5>
                        
                    @else
                        <h5 class="panel-title">@if(Auth::user()->type == 1) Manage user accounts @else
                        Credit: {{ Auth::user()->credit }} {{ App\Settings::where('type', 'currency')->value('value') }} @endif</h5>
                    @endif

                    <div class="heading-elements">
                        <ul class="icons-list">
                            <!--<li><a data-action="collapse"></a></li>
                                    <li><a data-action="close"></a></li>-->
                            <button type="button" class="btn btn-default" data-toggle="modal" title="save your filters for an easy way to get search result." data-target="#modal_search_result"><i class="icon-collaboration position-left"></i>
                                Custom Audience
                            </button>
                            &nbsp&nbsp&nbsp&nbsp
                            @if(Session::has('advancedReport'))
                                <a class="btn btn-default" href="/advancedReport" title="fast and easy way to get results."><i class="icon-magazine"></i> simple report </a>
                            @else
                                <a class="btn btn-default" href="/advancedReport" title="it may take a while to generate a detailed report for each user."><i class="icon-magazine"></i> detailed report </a>
                            @endif
                            
                            
                            <!--                                 
                            <button type="button" class="btn btn-default" data-toggle="modal"
                                    data-target="#modal_search_result"><i class="icon-floppy-disk position-left"></i>
                                Search result
                            </button> -->

                        </ul>
                    </div>
                    
                  </div>
                <div class="panel-body">
                    <br>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" id="btn" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">{{ trans('search.phone') }}<span class="caret"></span></button>
                            <ul class="dropdown-menu">
                            <!--<li><a >{{ trans('search.ALL') }}</a></li>-->
                                <li><a>{{ trans('search.phone') }}</a></li>
                                <li><a>{{ trans('search.NAME') }}</a></li>
                                <li><a>{{ trans('search.USER_NAME') }}</a></li>
                                <li><a>{{ trans('search.comment') }}</a></li>
                                <li><a>{{ trans('search.E_mail') }}</a></li>
                                <li><a>{{ trans('search.Account_Macaddress') }}</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <input id="input-search" type="text" class="form-control" aria-label="..."
                               placeholder="{{ trans('search.Search') }}">
                        <span class="input-group-btn">
                                        <button id="bnt-search" class="btn btn-default" type="button">{{ trans('search.GO') }}
                                            !</button>
                                      </span>
                    </div><!-- /input-group -->

                    <table class="table" id="table-user">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select" class="styled"></th>
                            <th>Name</th>
                            <th>Username</th>
                            @if($pmsIntegration == 1)
                                <th>Password</th>
                                <th>PMS Profile</th>
                            @endif
                            <th>Mobile</th>
                            @if(Session::has('advancedReport'))
                                @if($pmsIntegration == 1)
                                    <!-- <th>Nights</th> -->
                                    <!-- <th>Reputations</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th> -->
                                @endif
                                <th>Visits</th>
                                <th>Last Visit</th>
                                <th>Monthly (GB)</th>
                                <th>Total (GB)</th>
                                <th>Branch</th>
                                <th>Group</th>
                                <th>Internet</th>
                            @endif
                            <th>E-Mail</th>
                            <th>Suspend</th>

                            <th>Created At</th>
                            <th>&nbsp</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <style>
        .stepy-navigator {
            padding: 0 10px;
        }

        .stepy-navigator-ai {
            padding: 0 10px;
            margin-top: 100;
            text-align: right;
        }

        .radios {
            display: none;
        }

        .radios + .radiol {
            display: inline-block;
            margin: -2px;
            padding: 4px 12px;
            margin-bottom: 0;
            font-size: 14px;
            line-height: 20px;
            color: #333;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: #fbd755;
            background-repeat: repeat-x;
            border: 1px solid #ccc;
            border-color: #e6e6e6 #e6e6e6 #bfbfbf;
            border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
            border-bottom-color: #b3b3b3;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .radios2 + .radiol2 {
            color: #fff;
            background-color: #43a047;
        }

        .radios:checked + .radiol {
            background-image: none;
            outline: 0;
            background-color: #8d8d8d;
        }

        .radioss {
            display: none;
        }

        .radioss + .radioll {
            display: inline-block;
            margin: -2px;
            padding: 4px 12px;
            margin-bottom: 0;
            font-size: 14px;
            line-height: 20px;
            color: #333;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: #fbd755;
            background-repeat: repeat-x;
            border: 1px solid #ccc;
            border-color: #e6e6e6 #e6e6e6 #bfbfbf;
            border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
            border-bottom-color: #b3b3b3;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .radioss2 + .radioll2 {
            color: #fff;
            background-color: #43a047;
        }

        .radioss:checked + .radioll {
            background-image: none;
            outline: 0;
            background-color: #8d8d8d;
        }

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

        .prevs, .next1 {
            cursor: pointer;
            padding: 18px;
            width: 28%;
            border: 1px solid #ccc;
            margin: auto;
            background: url(assets/images/arrow.png) no-repeat;
            border-radius: 5px
        }

        .prevs:hover, .next1:hover {
            background-color: #ccc
        }

        .next1 {
            background-position: 50% 150%
        }

        .prevs {
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
@endsection
@section('js')
    <script type="text/javascript" src="assets/js/timepicki.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>

    <script type="text/javascript" src="assets/js/plugins/forms/wizards/stepy.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/wizards/stepy_ai.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/noty.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/uploaders/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/uploaders/plupload/plupload.queue.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/editors/summernote/summernote.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script type="text/javascript"
            src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>

    <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/buttons/ladda.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/notifications/pnotify.min.js"></script>

    <script type="text/javascript" src="assets/js/dropzone.js"></script>


    <script>

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
        };
        $("#dropzone").dropzone({url: "/file/post"});
        $(document).ready(function () {
            @if(isset($chargepackage[0]) and $chargepackage[0])
                    new PNotify({
                title: 'Done',
                text: '',
                addclass: 'alert bg-success alert-styled-right',
                type: 'error'
            });
            <?php Session::pull('chargepackage'); ?>
            @endif
        });
        function get_group() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: 'getgruop/' + $('.network-edit').select2('data')[0].id,
                data: {_token: CSRF_TOKEN},
                success: function (data) {
                    $('.gruop-edit').html('');
                    $(data).each(function (k, v) {
                        $('.gruop-edit').append('<option value="' + v.id + '">' + v.name + '</option>');
                    });
                    $('.gruop-edit').select2();
                },
                error: function () {
                    $('.gruop-edit').select2({data: null});
                }

            });
        }
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
                    else limit = 70
                    ;
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
                    $(selector).html('(' + count + ') ' + left);
                };
                this.keyup(doCount);
                this.keyup();
            }
        })(jQuery)

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
        // Override defaults
        $.fn.stepy.defaults.legend = false;
        $.fn.stepy.defaults.transition = 'fade';
        $.fn.stepy.defaults.duration = 150;
        $.fn.stepy.defaults.backLabel = '<i class="icon-arrow-left13 position-left"></i> Back';
        $.fn.stepy.defaults.nextLabel = 'Next <i class="icon-arrow-right14 position-right"></i>';

        // Override defaults
        $.fn.stepy_ai.defaults.legend = false;
        $.fn.stepy_ai.defaults.transition = 'fade';
        $.fn.stepy_ai.defaults.duration = 150;
        $.fn.stepy_ai.defaults.backLabel = '<i class="icon-arrow-left13 position-left"></i> Back';
        $.fn.stepy_ai.defaults.nextLabel = 'Next <i class="icon-arrow-right14 position-right"></i>';


        var network_array = [], setting_array = [],
                groups_array = [],
                Country_array = [];

        function getParameterByName(name) {
            var url = window.location.href;
            name = name.replace(/[\[\]]/g, "\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        $(function () {
            var queryString = {};
            queryString.parse = function (str) {
                if (typeof str !== 'string') {
                    return {};
                }

                str = str.trim().replace(/^\?/, '');

                if (!str) {
                    return {};
                }

                return str.trim().split('&').reduce(function (ret, param) {
                    var parts = param.replace(/\+/g, ' ').split('=');
                    var key = parts[0];
                    var val = parts[1];

                    key = decodeURIComponent(key);
                    // missing `=` should be `null`:
                    // http://w3.org/TR/2012/WD-url-20120524/#collect-url-parameters
                    val = val === undefined ? null : decodeURIComponent(val);

                    if (!ret.hasOwnProperty(key)) {
                        ret[key] = val;
                    } else if (Array.isArray(ret[key])) {
                        ret[key].push(val);
                    } else {
                        ret[key] = [ret[key], val];
                    }

                    return ret;
                }, {});
            };
            queryString.stringify = function (obj) {
                return obj ? Object.keys(obj).map(function (key) {
                    var val = obj[key];
                    if (Array.isArray(val)) {
                        return val.map(function (val2) {
                            return encodeURIComponent(key) + '=' + encodeURIComponent(val2);
                        }).join('&');
                    }
                    return encodeURIComponent(key) + '=' + encodeURIComponent(val);
                }).join('&') : '';
            };
            queryString.push = function (key, new_value, run = {}) {
                var params = {};
                params = queryString.parse(location.search);
                params[key] = new_value;

                if ($.isEmptyObject(run)) {
                    var new_params_string = queryString.stringify(params)
                    history.pushState({}, "", window.location.pathname + '?' + new_params_string);
                    table.ajax.url("{{ url('search.json') }}" + '?' + new_params_string).load();
                } else {
                    $.each(run, function (i, val) {
                        params[i] = val;
                    });
                    var new_params_string = queryString.stringify(params)
                    history.pushState({}, "", window.location.pathname + '?' + new_params_string);
                    table.ajax.url("{{ url('search.json') }}" + '?' + new_params_string).load();
                }
            }
            if (typeof module !== 'undefined' && module.exports) {
                module.exports = queryString;
            } else {
                window.queryString = queryString;
            }
            $(".dropdown-menu li a").click(function () {
                var selText = $(this).text();
                $("#btn:first-child").html(selText + '<span class="caret"></span>');

            });
            //Network
            $('.network').on('click', function (event) {
                if ($(this).is(':checked')) {
                    var selText = $(this).val();
                    var index = network_array.indexOf(selText);
                    if (index < 0) {
                        network_array.push(selText);
                        queryString.push('network', network_array.join());
                    }
                }
                else {
                    var selText = $(this).val();
                    var index = network_array.indexOf(selText);
                    if (index > -1) {
                        network_array.splice(index, 1);
                    }
                    queryString.push('network', network_array.join());
                }

            });

            //Groups
            $('.groups').on('click', function (event) {
                if ($(this).is(':checked')) {
                    var selText = $(this).val();
                    var index = groups_array.indexOf(selText);
                    if (index < 0) {
                        groups_array.push(selText);
                        queryString.push('groups', groups_array.join());
                    }
                }
                else {
                    var selText = $(this).val();
                    var index = groups_array.indexOf(selText);
                    if (index > -1) {
                        groups_array.splice(index, 1);
                    }
                    queryString.push('groups', groups_array.join());
                }

            });
            //Country
            $('.country').on('click', function (event) {
                if ($(this).is(':checked')) {
                    var selText = $(this).val();
                    var index = Country_array.indexOf(selText);
                    if (index < 0) {
                        Country_array.push(selText);
                        queryString.push('country', Country_array.join());
                    }

                }
                else {
                    var selText = $(this).val();
                    var index = Country_array.indexOf(selText);
                    if (index > -1) {
                        Country_array.splice(index, 1);
                    }
                    queryString.push('country', Country_array.join());
                    //queryString.push('country','');

                }

            });

            // SortByMostVisited
            $('.SortByMostVisited').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('SortByMostVisited', 'on');
                }
                else {
                    queryString.push('SortByMostVisited', '');
                }

            });

            // SortByInternetConsumption
            $('.SortByInternetConsumption').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('SortByInternetConsumption', 'on');
                }
                else {
                    queryString.push('SortByInternetConsumption', '');
                }

            });

            $('.frequency').keypress(function (e) {
                var key = e.which;
                if (key == 13)  // the enter key code
                {
                    queryString.push('frequency', $(this).val());
                }
            });

            //Male
            $('.male').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('male', 'on');
                }
                else {
                    queryString.push('male', '');
                }

            });

            //Female
            $('.female').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('female', 'on');
                }
                else {
                    queryString.push('female', '');
                }

            });

            //Unknown
            $('.Unknown').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('Unknown', 'on');
                }
                else {
                    queryString.push('Unknown', '');
                }

            });

            //Reset FREQUENCY
            $('.resetFrequency').on('click', function (event) {
                queryString.push('frequency', '');
                queryString.push('user_frequency_charged_to', '');
                queryString.push('user_frequency_charged_from', '');
            });

            //Reset resetCharged
            $('.resetCharged').on('click', function (event) {
                queryString.push('Users_charged_from', '');
                queryString.push('Users_charged_to', '');
            });

            //Reset resetNotCharged
            $('.resetNotCharged').on('click', function (event) {
                queryString.push('Users_not_charged_from', '');
                queryString.push('Users_not_charged_to', '');
            });

            //Statues Active
            $('.active').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('active', 'on');

                }
                else {
                    queryString.push('active', '');
                }

            });
            //Statues Inactive
            $('.inactive').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('inactive', 'on');
                }
                else {
                    queryString.push('inactive', '');
                }
            });

            //Online Users
            $('.online').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('online', 'on');
                }
                else {
                    queryString.push('online', '');
                }

            });

            //Suspended
            $('.suspend').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('suspend', 'on');

                }
                else {
                    queryString.push('suspend', '');
                }

            });
            //Unsuspend
            $('.unsuspend').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('unsuspend', 'on');

                }
                else {
                    queryString.push('unsuspend', '');
                }

            });

            //Register
            $('.register').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('register', 'on');

                }
                else {
                    queryString.push('register', '');
                }

            });

            //admin confirm
            $('.adminconfirm').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('adminconfirm', 'on');

                }
                else {
                    queryString.push('adminconfirm', '');
                }

            });
            //sms confirm
            $('.smsconfirm').on('click', function (event) {
                if ($(this).is(':checked')) {
                    queryString.push('smsconfirm', 'on');

                }
                else {
                    queryString.push('smsconfirm', '');
                }

            });
            $('.setting').on('ifChecked', function (event) {
                var selText = $(this).val();
                var index = setting_array.indexOf(selText);
                if (index < 0) {
                    setting_array.push(selText);
                    queryString.push('setting', setting_array.join());
                }
            });
            $('.setting').on('ifUnchecked', function (event) {
                var selText = $(this).val();
                var index = setting_array.indexOf(selText);
                if (index > -1) {
                    setting_array.splice(index, 1);
                }
                queryString.push('setting', setting_array.join());
            });

            //set pramtar serg;
            //befor set psamter chak is in array
            if (getParameterByName('by') != null)
                $('#btn:first-child').html(getParameterByName('by') + ' <span class="caret"></span>');
            if (getParameterByName('find') != null)
                $('#input-search').val(getParameterByName('find'));
            if (getParameterByName('network') != null) {
                network_array = getParameterByName('network').replace("[", "").replace("]", "").split(',');
                $('.network').each(function (e) {
                    var index = network_array.indexOf($(this).val());
                    if (index > -1) {
                        $(this).prop("checked", true);
                    }
                });
            }


            if (getParameterByName('groups') != null) {
                groups_array = getParameterByName('groups').replace("[", "").replace("]", "").split(',');
                $('.groups').each(function (e) {
                    var index = groups_array.indexOf($(this).val());
                    if (index > -1) {
                        $(this).prop("checked", true);
                    }
                });
            }
            if (getParameterByName('country') != null) {
                Country_array = getParameterByName('country').replace("[", "").replace("]", "").split(',');
                $('.country').each(function (e) {
                    var index = Country_array.indexOf($(this).val());
                    if (index > -1) {
                        $(this).prop("checked", true);
                    }
                });
            }

            if (getParameterByName('setting') != null) {
                $('.setting').on('ifCreated', function (event) {
                    setting_array = getParameterByName('setting').replace("[", "").replace("]", "").split(',');
                    var selText = $(this).val();
                    var index = setting_array.indexOf(selText);
                    if (index > -1) {
                        $(this).iCheck('check');
                    }
                });
            }
            if (getParameterByName('Users_charged_from') != null) {
                $('#data_find1').val(getParameterByName('Users_charged_from') + ' - ' + getParameterByName('Users_charged_to'));
            }

            if (getParameterByName('Users_not_charged_from') != null) {
                $('#data_find2').val(getParameterByName('Users_not_charged_from') + ' - ' + getParameterByName('Users_not_charged_to'));
            }

            if (getParameterByName('user_frequency_charged_from') != null) {
                $('#data_find3').val(getParameterByName('user_frequency_charged_from') + ' - ' + getParameterByName('user_frequency_charged_to'));
            }
            if (getParameterByName('male') == 'on') {
                $('.male').prop('checked', true);

            }
            if (getParameterByName('female') == 'on') {
                $('.female').prop('checked', true);
            }
            if (getParameterByName('active') == 'on') {
                $('.active').prop('checked', true);
            }
            if (getParameterByName('inactive') == 'on') {
                $('.inactive').prop('checked', true);
            }
            if (getParameterByName('online') == 'on') {
                $('.online').prop('checked', true);
            }
            if (getParameterByName('SortByMostVisited') == 'on') {
                $('.SortByMostVisited').prop('checked', true);
            }
            if (getParameterByName('SortByInternetConsumption') == 'on') {
                $('.SortByInternetConsumption').prop('checked', true);
            }
            if (getParameterByName('suspend') == 'on') {
                $('.suspend').prop('checked', true);
            }
            if (getParameterByName('unsuspend') == 'on') {
                $('.unsuspend').prop('checked', true);
            }

            if (getParameterByName('frequency') != null) {
                $('.frequency').val(getParameterByName('frequency'));
            }
            $('#data_find1').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    },
                    function (start, end, label) {
                        queryString.push('Users_charged_from', start.format('DD/MM/YYYY'),
                                {"Users_charged_to": end.format('DD/MM/YYYY')});
                    });
            $('#data_find2').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    },
                    function (start, end, label) {
                        queryString.push('Users_not_charged_from', start.format('DD/MM/YYYY'),
                                {"Users_not_charged_to": end.format('DD/MM/YYYY')});
                    });
            $('#data_find3').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    },
                    function (start, end, label) {
                        queryString.push('user_frequency_charged_from', start.format('DD/MM/YYYY'),
                                {
                                    "user_frequency_charged_to": end.format('DD/MM/YYYY'),
                                    "frequency": $('.frequency').val()
                                });
                    });

            //datatable
            var params = queryString.parse(location.search);


            if ({{ Auth::user()->type }} == 1 )
            {
                var table = $('#table-user').DataTable({
                    responsive: {
                        details: {
                            type: 'column',
                            target: -1
                        }
                    },

                    buttons: {
                        buttons: [
                            {
                                extend: 'colvis',
                                text: '<i class=icon-user-plus></i>',
                                //text: '<button type="button" class="btn bg-slate btn-icon"><i class="icon-grid-alt"></i></button>',
                                className: 'btn btn-default',
                                action: function (e, dt, node, config) {
                                    //alert( 'Button activated' );
                                    $('#add_user').modal('show');
                                }
                            },
                            {
                                text: '<i title="Upload users." class="icon-download4"></i>',
                                className: 'btn btn-default',
                                action: function (e, dt, node, config) {
                                    $('#user_upload').modal('show');
                                }
                            },
                            @if(App\Settings::where('type', 'marketing_enable')->value('value') == 1)
                            {
                                text: '<i title="Export selected users to excel sheet." class="icon-file-excel"></i>',
                                extend: 'excelHtml5',
                                className: 'btn btn-default',
                                exportOptions: {
                                    columns: ':visible',
                                    modifier: {selected: true}
                                }
                            },
                            {
                                    text: '<i title="Export selected users to PDF file." class="icon-file-pdf"></i>',
                                    extend: 'pdfHtml5',
                                    className: 'btn btn-default',
                                    exportOptions: {
                                        columns: ':visible',
                                        modifier: {selected: true}
                                    }
                            },
                            @endif
                            @if(App\Settings::where('type', 'marketing_enable')->value('state') == 1)
                                {
                                    text: '<i title="Copy selected users." class="icon-copy3"></i>',
                                    extend: 'copyHtml5',
                                    className: 'btn btn-default',
                                    exportOptions: {
                                        columns: ':visible',
                                        modifier: {selected: true}
                                    }
                                },
                                {
                                    text: '<i title="Print selected users." class="icon-printer"></i>',
                                    extend: 'print',
                                    className: 'btn btn-default',
                                    exportOptions: {
                                        columns: ':visible',
                                        modifier: {selected: true}
                                    }
                                },  
                                {
                                    text: '<i title="Make Campaign for selected users." class="icon-megaphone"></i>',
                                    className: 'btn btn-default',
                                    action: function (e, dt, node, config) {
                                        var data = table.rows({selected: true}).data();
                                        if (data.length == 0) {

                                            new PNotify({
                                                title: 'Campaign empty',
                                                text: 'Please select users to make campaign.',
                                                addclass: '',
                                                buttons: {
                                                    closer_hover: false,
                                                    sticker_hover: false
                                                }
                                            });
                                        } else {
                                            $('#modal_campaign').modal('show');
                                        }
                                    }
                                },  
                                {
                                    text: '<i title="Make AI Campaign for selected users." class="icon-brain"></i>',
                                    className: 'btn btn-default',
                                    action: function (e, dt, node, config) {
                                        var data = table.rows({selected: true}).data();
                                        if (data.length == 0) {

                                            new PNotify({
                                                title: 'AI Campaign empty',
                                                text: 'Please select users to make AI campaign.',
                                                addclass: '',
                                                buttons: {
                                                    closer_hover: false,
                                                    sticker_hover: false
                                                }
                                            });
                                        } else {
                                            $('#modal_ai_campaign').modal('show');
                                        }
                                    }
                                },
                            @endif 
                            {
                                text: '<i title="Move users to a group." class="icon-move"></i>',
                                className: 'btn btn-default',
                                action: function (e, dt, node, config) {
                                    var data = table.rows({selected: true}).data();
                                    if (data.length == 0) {

                                        new PNotify({
                                            title: 'Empty list',
                                            text: 'Please select users to be able to move it to the specific group.',
                                            addclass: '',
                                            buttons: {
                                                closer_hover: false,
                                                sticker_hover: false
                                            }
                                        });
                                    } else {
                                        $('#modal_group_switch').modal('show');
                                    }
                                }
                            },
                            {
                                text: '<i title="Delete selected users." class="icon-bin"></i>',
                                className: 'btn btn-default',
                                action: function (e, dt, node, config) {
                                    var that = this;
                                    var count = table.rows({selected: true}).count();
                                    swal({
                                                title: "Are you sure?",
                                                text: "You want to delete (" + count + ") users!",
                                                type: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#DD6B55",
                                                confirmButtonText: "Yes, delete it!",
                                                cancelButtonText: "No, cancel",
                                                closeOnConfirm: false,
                                                closeOnCancel: false,
                                                showLoaderOnConfirm: true
                                            },
                                            function (isConfirm) {
                                                if (isConfirm) {
                                                    var data = table.rows({selected: true}).data(), ids = []; //as ajax
                                                    $.each(data, function (index, value) {
                                                        ids.push(value.u_id);
                                                    });
                                                    $.ajax({
                                                        //url:{{ url('deletes') }}+"/"+ids.toString(),
                                                        url: 'deletes/' + ids.toString(),
                                                        success: function (data) {
                                                            table.rows({selected: true}).remove().draw();
                                                            swal("Deleted!", "Your selected users has been deleted successfully.", "success");
                                                        },
                                                        error: function () {
                                                            swal("Cancelled", "Your selected users is safe", "error");

                                                        }
                                                    });
                                                } else {
                                                    swal("Cancelled", "Your selected users is save :)", "success");
                                                }

                                            });
                                }
                            },
                            {
                                extend: 'colvis',
                                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                                className: 'btn bg-blue btn-icon'
                            }
                        ]
                    },

                    // 'order': [[1, 'asc']],
                    "processing": true,
                    ajax: {
                        "url": "{{ url('search.json') }}" + "?" + queryString.stringify(params),
                        type: "post",
                        data: {_token: $('meta[name="csrf-token"]').attr('content')}
                    },
                    columnDefs: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    },
                        {
                            className: 'control',
                            orderable: false,
                            targets: -1
                        }],
                    deferRender: true,
                    select: true,
                    columns: [
                        {"data": null, "defaultContent": ""},

                        {"render": function ( type, full, data, meta ) {
                            return '<a href="#" title="Open profile" class="edit" >'+data.u_name+'</a>';
                        }},
                        {"render": function ( type, full, data, meta ) {
                            return '<a href="#" title="Open Timeline" class="timeline" >'+data.u_uname+'</a>';
                        }},
                        @if($pmsIntegration == 1)
                            {"data": "u_password"},
                            {"data": "pms_guest_id"},
                        @endif
                        {"render": function ( type, full, data, meta ) {
                            if(data.u_phone!= null){
                                return '<a target="_blank" href="https://www.google.com/search?q='+data.u_phone+'" title="Google search" >'+data.u_phone+'</a>';
                            }else{
                                return '';
                            }
                        }},
                        @if(Session::has('advancedReport'))
                            @if($pmsIntegration == 1)
                                // {"data": "pmsStayDays"},
                                // {"data": "pmsReputations"},
                                // {"data": "pmsLastCheckIn"},
                                // {"data": "pmsLastCheckOut"},
                            @endif
                            {"data": "visits"},
                            {"data": "last_visit"},
                            //{ "data": "u_email" },
                            {"data": "monthly_usage"},
                            {"data": "total_usage"},
                            {"data": "branch_name"},
                            { // group name
                                "render": function (type, full, data, meta) {
                                    if (data.Selfrules == 1)
                                        return '<span class="label bg-blue">Self Rule</span>';
                                    else
                                        return data.group_name;

                                }
                            },

                            { // online state
                                "render": function (type, full, data, meta) {
                                    if (data.online_state==1)
                                        return '<span class="label bg-success heading-text"> Online </span>';
                                    else
                                        return '<span class="label bg-danger heading-text">offline</span>';
                                }
                            },
                        @endif
                        {"data": "u_email"},
                        {
                            "data": "suspend",
                            "searchable": false,
                            "render": function (type, full, data, meta) {
                                if (data.suspend == 0)
                                    return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Unsuspend</span></button>';
                                else
                                    return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Suspend</span></button>';

                            }
                        },
                        {
                            "data": "suspend",
                            "searchable": false,
                            "render": function (type, full, data, meta) {
                                if (!data.created_at.date)
                                    return data.created_at;
                                else
                                    var created_at_before_explode = data.created_at.date;
                                    var created_at_exploded = created_at_before_explode.split(".");
                                    return created_at_exploded[0];

                            }
                        },
                        {
                            "data": null,
                            "defaultContent": '<ul class="icons-list"><li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-menu7"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-right"><li><a href="#" class="edit"><i class="icon-magazine"></i> Profile </a></li>' +
                            '<li><a class="timeline"><i class="icon-file-stats"></i> Timeline</a></li>' +
                            '<li><a class="packages"><i class="icon-cash4"></i> Charge packages</a></li>' +
                            '<li><a href="#" class="delete"><i class="icon-cross3"></i> {{ trans('search.Delete') }}</a></li></ul></li></ul>'
                        },
                        {"data": null, "defaultContent": ""}
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    }
                });
            }
            else
            {
                var table = $('#table-user').DataTable({
                    responsive: {
                        details: {
                            type: 'column',
                            target: -1
                        }
                    },

                    buttons: {
                        buttons: [
						{
                                extend: 'colvis',
                                text: '<i class=icon-user-plus></i>',
                                //text: '<button type="button" class="btn bg-slate btn-icon"><i class="icon-grid-alt"></i></button>',
                                className: 'btn btn-default',
                                action: function (e, dt, node, config) {
                                    //alert( 'Button activated' );
                                    $('#add_user').modal('show');
                                }
						},
						{
                            extend: 'colvis',
                            text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                            className: 'btn bg-blue btn-icon'
                        }
                        ]
                    },

                    'order': [[1, 'asc']],
                    "processing": true,
                    ajax: {
                        "url": "{{ url('search.json') }}" + "?" + queryString.stringify(params),
                        type: "post",
                        data: {_token: $('meta[name="csrf-token"]').attr('content')}
                    },
                    columnDefs: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    },
                        {
                            className: 'control',
                            orderable: false,
                            targets: -1
                        }],
                    deferRender: true,
                    select: true,
                    columns: [

                        {"data": null, "defaultContent": ""},
                        {"data": "u_name"},
                        {"data": "u_uname"},
                        {"data": "u_phone"},
                        {"data": "visits"},
                        {"data": "last_visit"},
                        //                            { "data": "u_email" },
                        {"data": "branch_name"},
                        { // group name
                            "render": function (type, full, data, meta) {
                                if (data.Selfrules == 1)
                                    return '<span class="label bg-blue">Self Rule</span>';
                                else
                                    return data.group_name;

                            }
                        },

                        { // online state
                            "render": function (type, full, data, meta) {
                                if (data.online_state)
                                    return '<span class="label bg-success heading-text"> Online </span>';
                                else
                                    return '<span class="label bg-danger heading-text">offline</span>';
                            }
                        },
                        {
                            "data": "suspend",
                            "searchable": false,
                            "render": function (type, full, data, meta) {
                                if (data.suspend == 0)
                                    return '<button type="button" class="btn btn-success btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Unsuspend</span></button>';
                                else
                                    return '<button type="button" class="btn btn-danger btn-ladda btn-ladda-spinner" data-spinner-color="#333" data-style="radius" style="width: 91px;"><span class="ladda-label">Suspend</span></button>';

                            }
                        },
                        {"data": "monthly_usage"},
                        {"data": "total_usage"},
                        {
                            "data": null,
                            "defaultContent": '<ul class="icons-list"><li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-menu7"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-right">' +
                            '<li><a class="packages"><i class="icon-cash4"></i> Charge packages</a></li>' +
                            '</ul></li></ul>'
                        },
                        {"data": null, "defaultContent": ""}
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    }
                });
            }
            $('#table-user thead').on('change', 'input[type="checkbox"]', function () {
                if (!this.checked) {
                    table.rows().deselect();
                } else {
                    table.rows().select();
                }
            });


            $('#table-user tbody').on('click', '.suspend', function () {
                var that = this;

                swal({
                            title: "Are you sure ?",
                            text: "You will not be able to recover this user again!",
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "green",
                            CancelButtonColor: "red",
                            confirmButtonText: "Suspend",
                            cancelButtonText: "Unsuspend",
                            closeOnConfirm: false,
                            closeOnCancel: false,
                            showLoaderOnConfirm: true
                        },
                        function (isConfirm) {

                            if (isConfirm) {
                                setTimeout(function () {
                                    /*var data = table.row( $(that).parents('tr') ).data(); //as ajax
                                     table.row( $(that).parents('tr') ).remove().draw();*/

                                    swal("Suspend!", "User has been Suspend.", "success");
                                }, 2000);
                            } else {
                                swal("Unsuspend!", "User has been Unsuspend :)", "success");
                            }

                        });

            });
            //actain
            $('#table-user tbody').on('click', '.delete', function () {
                var that = this;
                swal({
                            title: "Are you sure?",
                            text: "You will not be able to recover this user again!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete!",
                            cancelButtonText: "No, cancel!",
                            closeOnConfirm: false,
                            closeOnCancel: false,
                            showLoaderOnConfirm: true
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                var data = table.row($(that).parents('tr')).data(); //as ajax
                                if (data == null) {
                                    data = table.row($(that).parents('tr').prev()).data();
                                }
                                $.ajax({
                                    url: "{{ url('deleteuser') }}" +'/'+ data.u_id,
                                    success: function (data) {
                                        table.row($(that).parents('tr')).remove().draw();
                                        swal("Deleted!", "user data has been deleted.", "success");
                                    },
                                    error: function () {
                                        swal("Cancelled", "user data is safe :)", "error");

                                    }
                                });
                            } else {
                                swal("Cancelled", "Cancelled :)", "success");
                            }
                        });

            });
            //
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

            // Checkboxes
            $(".styled").uniform({
                radioClass: 'choice'
            });
            $('.countries').select2({
                minimumInputLength: 1,
                minimumResultsForSearch: Infinity
            });
            // Basic options
            $('.pickadate').pickadate(
                    {
                        format: 'yyyy-mm-dd'
                    }
            );

            // Add user
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
            $('#self-rules').on('switchChange.bootstrapSwitch', function (event, state) {
                if (state === true) {
                    $('#divgruop').show();
                    $('.pgrup').hide();

                } else {
                    $('#divgruop').hide();
                    $('.pgrup').show();

                }
            });
            $('#self-rules2').on('switchChange.bootstrapSwitch', function (event, state) {
                if (state === true) {
                    $('.endspeed').show();
                    $('.equation_end').hide();
                    $('.equation_endspeed').show();
                    $('.done').append('<input name="values" type="hidden" value="1">');

                } else {
                    $('.endspeed').hide();
                    $('.equation_end').hide();
                    $('.equation_endspeed').hide();
                    $('.done').append('<input name="values" type="hidden" value="0">');

                }
            });

            $('#self-ruless').on('switchChange.bootstrapSwitch', function (event, state) {

                if (state === true) {
                    $('#divgruops').hide();//group
                    $('#hidden').hide();
                    $('#div1').show();//selfrules
                    $('.done2').append('<input name="donedone" type="hidden" value="0">');
                } else {
                    $('#divgruops').show();
                    $('#div1').hide();

                    $('.done2').append('<input name="donedone" type="hidden" value="1">');
                }
            });
            $('#self-ruless2').on('switchChange.bootstrapSwitch', function (event, state) {
                if (state === true) {
                    $('.endspeed').show();
                } else {
                    $('.endspeed').hide();
                }
            });
            $('.select-fixed-single75').select2({
                minimumResultsForSearch: Infinity,
                width: 75
            });
            // Fixed width. Single select
            $('.select-fixed-single').select2({
                minimumResultsForSearch: Infinity,
                width: 200
            });

            $('.gruop-edit').select2({
                minimumResultsForSearch: Infinity,
            });
            $('.network-edit').select2({
                minimumResultsForSearch: Infinity,
                width: 150
            }).on("change", function (e) {
                // mostly used event, fired to the original element when the value changes
                get_group()
            })
            get_group()
            $('.select-fixed-singles').select2({
                minimumResultsForSearch: Infinity,
                width: 150
            });
            $('.select-fixed-ss').select2({
                minimumResultsForSearch: Infinity,
                width: 75
            });


            $('#bnt-search').click(function () {
                var value = $('#input-search').val(),
                        by = $('#btn').text();
                console.log(by);
                queryString.push('find', value, {"by": by});
            });
            $('#input-search').keypress(function (e) {
                var key = e.which;
                if (key == 13)  // the enter key code
                {
                    $('#bnt-search').click();
                    return false;
                }
            });
            var i_i = 0;

            $('#btn-group_switch').click(function () {

                var target_group_switch = document.querySelector('#target_group_switch').value;
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                // $(this).text('Moving ..');

                var data = table.rows({selected: true}).data(), ids = []; //as ajax
                $.each(data, function (index, value) {
                    ids.push(value.u_id);
                });

                $.ajax({
                    'url': 'bulkGroupSwitch',
                    'data': {
                        _token: CSRF_TOKEN,
                        'data': ids.toString(),
                        'targetGroupSwitch': target_group_switch
                    },
                    type: 'post',
                    success: function (data) {
                        // table.rows({selected: true}).remove().draw();
                        new PNotify({
                            title: 'Success',
                            text: 'Users has been moved successfully',
                            addclass: 'alert bg-success alert-styled-right',
                            type: 'error'
                        });
                    },
                    error: function () {
                        new PNotify({
                            title: 'Oops!!',
                            text: 'Erorr in moving',
                            addclass: 'alert bg-danger alert-styled-right',
                            type: 'error'
                        });
                    }

                });

            });


            
            $('#btn-campaign').click(function () {
                var data = table.rows({selected: true}).data();
                var i = 0;
                $('#f_1').hide();
                $('#f_2').hide();
                $('#f_3').hide();
                $('#f_4').hide();

                //html content
                var html_email = '<div class="col-lg-12"> <center><h1> Email Campaign </h1></center> <div class="row"> <div class="col-md-6">  <div class="form-group has-feedback has-feedback-left">  <input name="email_From"  id="email_From" type="text" class="form-control input-xlg" placeholder="From">  <span class="help-block">example@domain.com</span> <div class="form-control-feedback"> <i class="icon-mention">  </i> </div></div>  </div> <!-----> <div class="col-md-6">  <div class="form-group has-feedback has-feedback-left"><input name="email_subject" id="email_subject" type="text" class="form-control input-lg" placeholder="Subject"> <div class="form-control-feedback"> <i class="icon-quill4"></i> </div> </div> </div> <!-----> <div></div> <div class="col-md-12"><input class="wysihtml5 wysihtml5-min form-control wysihtml5-editor" name="message" id="message"></div></div></div><div class="col-lg-12" id="count-email"></div>'
                        , html_sms = '<div class="col-lg-12"> <center><h1> SMS Campaign </h1></center> <div class="row">  <!-----> <div class="col-md-6">      </div> <div class="form-group has-feedback has-feedback-left">  <input name="sms_sender" id="sms_sender" type="text" class="form-control input-xlg" value="{{ App\Settings::where('type', 'SMSProvidersendername')->value('value') }}">   <div class="form-control-feedback"> <i class="icon-user">  </i> </div></div> <!------>  <div></div> <div class="col-md-12"><textarea cols="1" rows="1" class="sms_mass form-control" name="sms_message" id="sms_message" placeholder="Enter text ..."></textarea></div><div class=""><i><span class="label border-left-success label-striped">Message Count </span><div class="count">(0) 0</div></i> </div> </div> </div></div>'
                        , html_notifications = '<div class="col-lg-12"> <center><h2> Push Notifications Campaign </h2></center>  <div class="row">  <!-----> <div class="col-md-6"> <div class="form-group has-feedback has-feedback-left">  <input name="push_head" id="push_head" type="text" class="form-control input-xlg" placeholder="Title head">  <div class="form-control-feedback"> <i class="icon-user">  </i> </div></div>  </div> <div class="col-md-6">  <div class="form-group has-feedback has-feedback-left">   <input name="push_body" id="push_body" type="text" class="form-control input-lg" placeholder="Title body"> <div class="form-control-feedback"> <i class="icon-quill4"></i> </div> </div> </div><div></div> <div class="col-md-12"><input name="push_content" id="push_content" class="wysihtml5 wysihtml5-min form-control wysihtml5-editor"></div></div> </div> <div></div>'
                        , html_messages = '<div class="col-lg-12"> <center><h2> WhatsApp Campaign </h2></center>  <div class="row"> <div class="col-md-6"> </div> <div class="form-group has-feedback has-feedback-left">  <input name="local_from" id="local_from" type="hidden" class="form-control input-xlg" placeholder="From"> <div class="form-control-feedback"> <i class="icon-quill4"></i>  </div> </div> <!-----> <div></div> <div class="col-md-12"><textarea id="local_message" placeholder="Enter your Whatsapp message here ..." rows="3" class="form-control"></textarea><span class="help-block">You can open Whatsapp web and copy any emojis and paste it here.</span><br></div></div> </div> <div></div>';

                if ($('#c_email').is(':checked')) {
                    $('.f_1_1').html(html_email);
                    $('.f_2_1').html(html_email);
                    $('.f_3_1').html(html_email);
                    $('.f_4_1').html(html_email);
                    i++;
                }
                if ($('#c_sms').is(':checked')) {

                    if (i == 0) {
                        $('.f_1_1').html(html_sms);
                        $('.f_2_1').html(html_sms);
                        $('.f_3_1').html(html_sms);
                    } else {
                        $('.f_2_2').html(html_sms);
                        $('.f_3_2').html(html_sms);
                        $('.f_4_2').html(html_sms);
                    }
                    i++;
                }
                if ($('#c_notifications').is(':checked')) {
                    if (i == 0) {
                        $('.f_1_1').html(html_notifications);
                        $('.f_2_1').html(html_notifications);
                    } else if (i == 1) {

                        $('.f_2_2').html(html_notifications);
                        $('.f_3_2').html(html_notifications);
                    } else if (i == 2) {
                        $('.f_3_3').html(html_notifications);
                        $('.f_4_3').html(html_notifications);
                    }
                    i++;
                }
                if ($('#c_messages').is(':checked')) {
                    if (i == 0) {

                        $('.f_1_1').html(html_messages);
                    } else if (i == 1) {

                        $('.f_2_2').html(html_messages);
                    } else if (i == 2) {

                        $('.f_3_3').html(html_messages);
                    } else if (i == 2) {

                        $('.f_4_3').html(html_messages);
                    } else if (i == 3) {

                        $('.f_4_4').html(html_messages);
                    }
                    i++;
                }

                if (i != 0) {

                    if (i == 1)$('#f_1').show();
                    if (i == 2)$('#f_2').show();
                    if (i == 3)$('#f_3').show();
                    if (i == 4)$('#f_4').show();


                    i_i = i;
                    $('.summernote').summernote();

                    // Simple toolbar
                    $('.wysihtml5-min').wysihtml5({
                        parserRules: wysihtml5ParserRules,
                        stylesheets: ["assets/css/components.css"],
                        "font-styles": true, // Font styling, e.g. h1, h2, etc. Default true
                        "emphasis": true, // Italics, bold, etc. Default true
                        "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers. Default true
                        "html": false, // Button which allows you to edit the generated HTML. Default false
                        "link": true, // Button to insert a link. Default true
                        "image": false, // Button to insert an image. Default true,
                        "action": false, // Undo / Redo buttons,
                        "color": true // Button to change color of font
                    });
                    console.log($('div#f_' + i_i).find('.sms_mass'));
                    $('div#f_' + i_i).find('.sms_mass').smsCounter('.count');
                    $('.stepy-step').find('.button-next').addClass('btn btn-primary');
                    $('.stepy-step').find('.button-back').addClass('btn btn-default');

                    $('#email_campaign').modal('show');

                } else {
                    // Permanent buttons
                    new PNotify({
                        title: 'Please select campaign',
                        text: '',
                        addclass: 'bg-danger',
                        buttons: {
                            closer_hover: false,
                            sticker_hover: false
                        }
                    });

                }
            });

            $('#btn-ai-campaign').click(function () {
                var data = table.rows({selected: true}).data();
                var i = 0;
                $('#f_1_ai').hide();
                $('#f_2_ai').hide();
                $('#f_3_ai').hide();
                $('#f_4_ai').hide();

                //html content
                var html_email = '<div class="col-lg-12"> <center><h1>AI Email Campaign </h1></center>  <div class="row">  <!-----> <div class="form-group has-feedback has-feedback-left">  <!-------> <label class="control-label col-lg-12">Available Variables :<br> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span> </label> <!----------------- End Of Variables ------------------> </div> <!------>  <div></div> <!----->  <div class="col-md-12"><textarea name="email_message_ai" id="email_message_ai" placeholder="ChatGPT content ..." rows="8" class="form-control">Write an email without the sign, signature, or subject, based on the native language of @USER_COUNTRY, to @USER_NAME to welcome him to our hotel @BUSINESS_NAME, and tell him his Wi-Fi username is (@USER_USERNAME) and password is (@USER_PASSWORD), and tell him he can use our chatbot on Messenger (https://www.messenger.com/t/105159434641245) or Telegram (https://t.me/HotelWiFiBot) to buy premium Wi-Fi packages and submit a request if he needs high-speed internet to make online conference meetings and know his family locations inside the hotel, also explore the entertainment animation team program, premium restaurants, and spa, and suggest a holiday plan day by day for him, based on his countrys culture (@USER_COUNTRY), age (@USER_BIRTHDATE), and gender(@USER_GENDER), starting from check-in date @USER_CHECKIN_DATE, till check-out date @USER_CHECKOUT_DATE.</textarea><span class="help-block">Notes: Please avoid new lines while writing a GPT content message.</span><br></div> </div></div><div class="col-lg-12" id="count-email_ai"></div>'
                    , html_sms = '<div class="col-lg-12"> <center><h1>AI SMS Campaign </h1></center> <div class="row">  <!-----> <div class="form-group has-feedback has-feedback-left">  <!-------> <label class="control-label col-lg-12">Available Variables :<br> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span> </label> <!----------------- End Of Variables ------------------> </div> <!------>  <div></div> <div class="col-md-12"><textarea cols="1" rows="8" class="sms_mass_ai form-control" name="sms_message_ai" id="sms_message_ai" placeholder="ChatGPT content ...">Write a short SMS message of 120 characters without a subject or any variables in the signature for @USER_NAME  to welcome him to our hotel (@BUSINESS_NAME), based on the native language of his country @USER_COUNTRY.</textarea></div> </div> </div></div> <span class="help-block">Notes: Please avoid new lines while writing a GPT content message.</span><br>'
                    , html_notifications = '<div class="col-lg-12"> <center><h2> Push Notifications Campaign </h2></center>  <div class="row">  <!-----> <div class="col-md-6"> <div class="form-group has-feedback has-feedback-left">  <input name="push_head" id="push_head" type="text" class="form-control input-xlg" placeholder="Title head">  <div class="form-control-feedback"> <i class="icon-user">  </i> </div></div>  </div> <div class="col-md-6">  <div class="form-group has-feedback has-feedback-left">   <input name="push_body" id="push_body" type="text" class="form-control input-lg" placeholder="Title body"> <div class="form-control-feedback"> <i class="icon-quill4"></i> </div> </div> </div><div></div> <div class="col-md-12"> <textarea name="message_ai" id="message_ai" placeholder="Enter your Whatsapp message here ..." rows="8" class="form-control"></textarea> <span class="help-block">Notes: Please avoid new lines while writing a GPT content message.</span><br></div></div> </div> <div></div><br>'
                    , html_messages = '<div class="col-lg-12"> <center><h2>AI WhatsApp Campaign </h2></center>  <div class="row">  <!-----> <div class="form-group has-feedback has-feedback-left">  <!-------> <label class="control-label col-lg-12">Available Variables :<br> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_NAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_USERNAME</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PASSWORD</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_BIRTHDATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_GENDER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_LANGUAGE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_COUNTRY</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKIN_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CHECKOUT_DATE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_TYPE</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_RESERVATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_CONFIRMATION_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_ROOM_NUMBER</span> <span class="label btn-success btn-ladda btn-ladda-spinner">@USER_PMS_ID</span> </label> <!----------------- End Of Variables ------------------> </div> <!------>  <div></div>  <div class="row"> <div class="col-md-6"> </div> <div class="form-group has-feedback has-feedback-left">  <input name="local_from_ai" id="local_from_ai" type="hidden" class="form-control input-xlg" placeholder="From"> <div class="form-control-feedback">  </div> </div> <!-----> <div></div> <div class="col-md-12"><textarea name="whatsapp_message_ai" id="whatsapp_message_ai" placeholder="Enter your Whatsapp message here ..." rows="8" class="form-control">Write a short WhatsApp message without a subject or any variables in the signature for @USER_NAME to see him off after his stay with us at our hotel (@BUSINESS_NAME), based on the native language of his country @USER_COUNTRY.</textarea><span class="help-block">Notes: Please avoid new lines while writing a GPT content message.</span><br></div></div> </div> <div></div>';

                if ($('#c_email_ai').is(':checked')) {
                    $('.f_1_1_ai').html(html_email);
                    $('.f_2_1_ai').html(html_email);
                    $('.f_3_1_ai').html(html_email);
                    $('.f_4_1_ai').html(html_email);
                    i++;
                }
                if ($('#c_sms_ai').is(':checked')) {

                    if (i == 0) {
                        $('.f_1_1_ai').html(html_sms);
                        $('.f_2_1_ai').html(html_sms);
                        $('.f_3_1_ai').html(html_sms);
                    } else {
                        $('.f_2_2_ai').html(html_sms);
                        $('.f_3_2_ai').html(html_sms);
                        $('.f_4_2_ai').html(html_sms);
                    }
                    i++;
                }
                if ($('#c_notifications_ai').is(':checked')) {
                    if (i == 0) {
                        $('.f_1_1_ai').html(html_notifications);
                        $('.f_2_1_ai').html(html_notifications);
                    } else if (i == 1) {

                        $('.f_2_2_ai').html(html_notifications);
                        $('.f_3_2_ai').html(html_notifications);
                    } else if (i == 2) {
                        $('.f_3_3_ai').html(html_notifications);
                        $('.f_4_3_ai').html(html_notifications);
                    }
                    i++;
                }
                if ($('#c_messages_ai').is(':checked')) {
                    if (i == 0) {

                        $('.f_1_1_ai').html(html_messages);
                    } else if (i == 1) {

                        $('.f_2_2_ai').html(html_messages);
                    } else if (i == 2) {

                        $('.f_3_3_ai').html(html_messages);
                    } else if (i == 2) {

                        $('.f_4_3_ai').html(html_messages);
                    } else if (i == 3) {

                        $('.f_4_4_ai').html(html_messages);
                    }
                    i++;
                }

                if (i != 0) {

                    if (i == 1)$('#f_1_ai').show();
                    if (i == 2)$('#f_2_ai').show();
                    if (i == 3)$('#f_3_ai').show();
                    if (i == 4)$('#f_4_ai').show();


                    i_i = i;
                    $('.stepy-step-ai').find('.button-next-ai').addClass('btn btn-primary position-right');
                    $('.stepy-step-ai').find('.button-back-ai').addClass('btn btn-default position-right');

                    $('#email_ai_campaign').modal('show');

                } else {
                    // Permanent buttons
                    new PNotify({
                        title: 'Please select campaign',
                        text: '',
                        addclass: 'bg-danger',
                        buttons: {
                            closer_hover: false,
                            sticker_hover: false
                        }
                    });

                }
            });
            
            $(".stepy-validation").stepy();

            $(".stepy-validation-ai").stepy_ai();
            //wizard

            $('#RadiusDiv').hide();

            $('#Radiuscheck').on('click', function () {
                if ($(this).is(':checked')) {
                    $('#RadiusDiv').show();
                } else {
                    $('#RadiusDiv').hide();
                }
            });

            $('#GroupDiv').show();
            $('#Radiuss').on('click', function () {
                if ($(this).is(':checked')) {
                    $('#GroupDiv').show();
                    $('#RadiusDiv').hide();
                } else {
                    $('#GroupDiv').hide();
                }
            });

            $('#DowngradeDiv').hide();
            $('#Downgradecheck').on('click', function () {
                if ($(this).is(':checked')) {
                    $('#DowngradeDiv').show();
                } else {
                    $('#DowngradeDiv').hide();
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
                    $('.endspeed').hide();
                    $('.equation_end').show();
                } else {
                    $('.equations').hide();
                    $('.endspeed').show();
                    $('.equation_end').hide();
                }
            });
            $('.auto_login').on('switchChange.bootstrapSwitch', function (event, state) {
                if (state === true) {
                    $('.auto-login-expiry').show();
                } else {
                    $('.auto-login-expiry').hide();
                }
            });
            $('#table-user tbody').on('click', 'button.btn-ladda-spinner', function () {
                var data = table.row($(this).parents('tr')).data(),
                        sus = ($(this).hasClass('btn-success')) ? false : true,
                        that = this;
                if (data == null) {
                    data = table.row($(that).parents('tr').prev()).data();
                }
                $(this).text('Loading ..');
                $.ajax({
                    url: 'suspend/' + data.u_id + '/' + sus,
                    success: function (data) {
                        if (sus) {
                            $(that).text('Unsuspend');
                            $(that).removeClass('btn-danger');
                            $(that).addClass('btn-success');

                        }
                        else {
                            $(that).text('Suspend');
                            $(that).removeClass('btn-success');
                            $(that).addClass('btn-danger');
                        }
                    },
                    error: function () {
                        if (!sus) {
                            $(that).text('Unsuspend');
                            $(that).removeClass('btn-danger');
                            $(that).addClass('btn-success');
                        }
                        else {
                            $(that).text('Suspend');
                            $(that).removeClass('btn-success');
                            $(that).addClass('btn-danger');
                        }

                    }
                });
            });

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

            $('#table-user tbody').on('click', '.edit', function () {
                var that = this;
                var data = table.row($(that).parents('tr')).data();
                if (data == null) {
                    data = table.row($(that).parents('tr').prev()).data();
                }


                // LOADING THE AJAX MODAL
                jQuery('#edit_user').modal('show', {backdrop: 'true'});
                jQuery('#edit_user .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');


                // SHOW AJAX RESPONSE ON REQUEST SUCCESS
                $.ajax({
                    url: 'user_profile/' + data.u_id,
                    success: function (response) {
                        jQuery('#edit_user .modal-body').html(response);
                    }
                });
            });


            $('#table-user tbody').on('click', '.packages', function (e) {
                var that = this;
                var data = table.row($(that).parents('tr')).data();
                if (data == null) {
                    data = table.row($(that).parents('tr').prev()).data();
                }
                jQuery('#packages .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');
                // LOADING THE AJAX MODAL
                jQuery('#packages').modal('show', {backdrop: 'true'});

                // SHOW AJAX RESPONSE ON REQUEST SUCCESS
                $.ajax({
                    url: 'charagepackages/' + data.u_id,
                    success: function (response) {
                        jQuery('#packages .modal-body').html(response);
                    }
                });
            })
            $('#table-user tbody').on('click', '.timeline', function (e) {
                var that = this;
                var data = table.row($(that).parents('tr')).data();
                if (data == null) {
                    data = table.row($(that).parents('tr').prev()).data();
                }
                jQuery('#timeline .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="{{ asset('/') }}assets/images/preloader.gif" /></div>');
                // LOADING THE AJAX MODAL
                jQuery('#timeline').modal('show', {backdrop: 'true'});

                // SHOW AJAX RESPONSE ON REQUEST SUCCESS
                $.ajax({
                    url: 'timeline/' + data.u_id,
                    success: function (response) {
                        //$('.username').append('<span class="text-semibold modal-title">'+ data.u_name + ' Timeline</span>');
                        jQuery('#timeline .modal-body').html(response);
                    }
                });
            })
            
            $('#table-user tbody').on('click', '.u-panel', function (e) {
                //$.cookie("u_id", data.u_id);
                var data = table.row($(this).parents('tr')).data()

                if (data == null) {
                    data = table.row($(this).parents('tr').prev()).data();
                }
                document.cookie = "u_id=" + data.u_id;
                document.location.href = "/autologin";
            })

            // last step to lunch Basic campaign
            $('.stepy-finish').click(function (e) {
                console.log($('div#f_' + i_i))
                console.log($('div#f_' + i_i).find('form'))
                console.log($('div#f_' + i_i).find('form').serializeArray())
                e.preventDefault();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var data = table.rows({selected: true}).data(), ids = [];
                $.each(data, function (index, value) {
                    ids.push(value.u_id);
                });
                if (document.querySelector('#message')) {
                    var message = document.querySelector('#message').value;
                }
                if (document.querySelector('#email_From')) {
                    var email_From = document.querySelector('#email_From').value;
                }
                if (document.querySelector('#email_subject')) {
                    var email_subject = document.querySelector('#email_subject').value;
                }
                if (document.querySelector('#sms_message')) {
                    var sms_message = document.querySelector('#sms_message').value;
                }
                if (document.querySelector('#sms_sender')) {
                    var sms_sender = document.querySelector('#sms_sender').value;
                }
                if (document.querySelector('#push_content')) {
                    var push_content = document.querySelector('#push_content').value;
                }
                if (document.querySelector('#push_head')) {
                    var push_head = document.querySelector('#push_head').value;
                }
                if (document.querySelector('#push_body')) {
                    var push_body = document.querySelector('#push_body').value;
                }
                if (document.querySelector('#local_from')) {
                    var local_from = document.querySelector('#local_from').value;
                }
                if (document.querySelector('#local_subject')) {
                    var local_subject = document.querySelector('#local_subject').value;
                }
                if (document.querySelector('#local_message')) {
                    var local_message = document.getElementById("local_message").innerHTML = document.querySelector('#local_message').value;
                }

                $.ajax({
                    'url': 'campaign',
                    'data': {
                        _token: CSRF_TOKEN,
                        'ids': ids,
                        'message': message,
                        'email_From': email_From,
                        'email_subject': email_subject,
                        'sms_message': sms_message,
                        'sms_sender': sms_sender,
                        'push_content': push_content,
                        'push_head': push_head,
                        'push_body': push_body,
                        'local_from': local_from,
                        'local_subject': local_subject,
                        'local_message': local_message, 'data': {i: i_i}
                    },
                    type: 'post',
                    success: function (data) {

                        new PNotify({
                            title: 'Success',
                            text: 'Campaign has been successfully.',
                            addclass: 'alert bg-success alert-styled-right',
                            type: 'success'
                        });
                    },
                    error: function () {
                        new PNotify({
                            title: 'Oops!!',
                            text: 'Erorr in campaign.',
                            addclass: 'alert bg-danger alert-styled-right',
                            type: 'error'
                        });
                    }

                });
            })

            // last step to lunch AI campaign
            $('.stepy-finish-ai').click(function (e) {
                console.log($('stepy-finish-ai'))
                // console.log($('div#f_' + i_i))
                // console.log($('div#f_' + i_i).find('form'))
                // console.log($('div#f_' + i_i).find('form').serializeArray())
                e.preventDefault();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var data = table.rows({selected: true}).data(), ids = [];
                $.each(data, function (index, value) {
                    ids.push(value.u_id);
                });
                if (document.querySelector('#email_message_ai')) {
                    var email_message = document.querySelector('#email_message_ai').value;
                }
                // if (document.querySelector('#email_From_ai')) {
                //     var email_From = document.querySelector('#email_From_ai').value;
                // }
                // if (document.querySelector('#email_subject_ai')) {
                //     var email_subject = document.querySelector('#email_subject_ai').value;
                // }
                if (document.querySelector('#sms_message_ai')) {
                    var sms_message = document.querySelector('#sms_message_ai').value;
                }
                // if (document.querySelector('#sms_sender_ai')) {
                //     var sms_sender = document.querySelector('#sms_sender_ai').value;
                // }
                if (document.querySelector('#push_content_ai')) {
                    var push_content = document.querySelector('#push_content_ai').value;
                }
                if (document.querySelector('#push_head_ai')) {
                    var push_head = document.querySelector('#push_head_ai').value;
                }
                if (document.querySelector('#push_body_ai')) {
                    var push_body = document.querySelector('#push_body_ai').value;
                }
                // if (document.querySelector('#local_from_ai')) {
                //     var local_from = document.querySelector('#local_from_ai').value;
                // }
                if (document.querySelector('#whatsapp_message_ai')) {
                    var whatsapp_message = document.getElementById("whatsapp_message_ai").innerHTML = document.querySelector('#whatsapp_message_ai').value;
                }

                console.log('ids:'+ids)
                console.log('email_message:'+email_message)
                // console.log('email_From:'+email_From)
                // console.log('email_subject:'+email_subject)
                console.log('sms_message:'+sms_message)
                // console.log('sms_sender:'+sms_sender)
                console.log('push_content:'+push_content)
                console.log('push_head:'+push_head)
                console.log('push_body:'+push_body)
                // console.log('local_from:'+local_from)
                console.log('whatsapp_message:'+whatsapp_message)

                
                $.ajax({
                    'url': 'campaignAI',
                    'data': {
                        _token: CSRF_TOKEN,
                        'ids': ids,
                        'email_message': email_message,
                        // 'email_From': email_From,
                        // 'email_subject': email_subject,
                        'sms_message': sms_message,
                        // 'sms_sender': sms_sender,
                        'push_content': push_content,
                        'push_head': push_head,
                        'push_body': push_body,
                        // 'local_from': local_from,
                        'whatsapp_message': whatsapp_message, 'data': {i: i_i}
                    },
                    type: 'post',
                    success: function (data) {

                        new PNotify({
                            title: 'Success',
                            text: 'AI campaign has been successfully scheduled.',
                            addclass: 'alert bg-success alert-styled-right',
                            type: 'success'
                        });
                    },
                    error: function () {
                        new PNotify({
                            title: 'Oops!!',
                            text: 'Erorr in AI campaign.',
                            addclass: 'alert bg-danger alert-styled-right',
                            type: 'error'
                        });
                    }

                });
            })

            $("[name='my-checkbox']").bootstrapSwitch();

            $('.timepicker').timepicki({
                show_meridian: false,
                min_hour_value: 0,
                max_hour_value: 23,
                overflow_minutes: true,
                time: 1,
                increase_direction: 'up',
                disable_keyboard_mobile: true
            });

            $('.timepicker2').timepicki({
                show_meridian: false,
                min_hour_value: 0,
                max_hour_value: 23,
                overflow_minutes: true,
                mint: 1,
                increase_direction: 'up',
                disable_keyboard_mobile: true
            });
        });


    </script>


@endsection
