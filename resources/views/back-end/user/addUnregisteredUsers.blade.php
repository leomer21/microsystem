<div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right">

    <!-- Assign device to user -->
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group1"><i class="icon-users position-left"></i> Assign device to user</a>
            </h6>
        </div>
        <div id="accordion-control-right-group1" class="panel-collapse collapse">
            <div class="panel-body">
            <form action="{{ url('addNewUnregisteredUser') }}" method="POST" id="assign" class="form-horizontal">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <input name="type" type="hidden" value="assign">
                <input name="mac" type="hidden" value="{{ $mac }}">

                    <div class="alert alert-info  alert-bordered">
                        <center><span class="text-semibold">Search</span> by name or mobile number.</center>
                    </div>
                    <div class="form-group has-feedback-left">
                        <!-- <label class="text-semibold col-lg-2 control-label">Select User</label> -->
                        <div class="col-lg-12">
                            <select class="countries" name="u_id">
                                @foreach(App\Users::where(['u_state'=>'1', 'suspend'=>'0', 'Registration_type'=>'2'])->get() as $user)
                                    <option value="{{ $user->u_id }}">{{ $user->u_name }} | {{ $user->u_uname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-block content-group" onclick="document.forms['assign'].submit(); return false;"> <i class="icon-users position-left"></i> Assign device to selected user</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New User -->
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group2"> <i class="icon-user-plus position-left"></i> Add new user</a>
            </h6>
        </div>
        <div id="accordion-control-right-group2" class="panel-collapse collapse">
            <div class="panel-body">

                <form action="{{ url('addNewUnregisteredUser/') }}" method="POST" id="new" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); $random = rand(11111111,999999999); ?>">
                    <input name="type" type="hidden" value="new">
                    <input name="mac" type="hidden" value="{{ $mac }}">
                    
                    <?php $systemCountry=App\Settings::where('type', 'country')->value('value'); ?>
                    <input name="country" type="hidden" value="{{$systemCountry}}">
                              
                    
                    <div class="form-group has-feedback-left">
                        <label class="text-semibold col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input name="name" type="text" class="form-control" value="">
                            <div class="form-control-feedback">
                                <i class="icon-user"></i>
                            </div>
                        </div>
                    </div>

                    @if( App\Network::where('r_type', '2')->count() > 0 and App\Settings::where('type', 'pms_integration')->value('state') != 1 )
                        <!-- SMS verification enabled so we will generate any username and password -->
                        <input name="username" type="hidden" value="{{$random}}">
                        <input name="password" type="hidden" value="{{$random}}">
                    @else
                        <!-- SMS verification disabled so we will show username and password -->
                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Usernane</label>
                            <div class="col-lg-10">
                                <input name="username" type="text" class="form-control" value="{{$random}}">
                                <!-- <span class="help-block">if your enable SMS verification dont change this vale</span> -->
                                <div class="form-control-feedback">
                                    <i class="icon-vcard"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback-left">
                            <label class="text-semibold col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input name="password" type="password" class="form-control" value="{{$random}}">
                                <div class="form-control-feedback">
                                    <i class="icon-key"></i>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group has-feedback-left">
                        <label class="text-semibold col-lg-2 control-label">Mobile</label>
                        <div class="col-lg-10">
                            <input name="phone" type="text" class="form-control tokenfield" value=""
                                placeholder="2010000">
                            <div class="form-control-feedback">
                                <i class="icon-mobile"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback-left">
                        <label class="text-semibold col-lg-2 control-label">Email</label>
                        <div class="col-lg-10">
                            <input name="email" type="text" class="form-control tokenfield" value=""
                                placeholder="mail@">
                            <div class="form-control-feedback">
                                <i class="icon-mail5"></i>
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
                    </div>

                    <div class="form-group has-feedback-left">
                        <label class="text-semibold col-lg-2 control-label">Branch</label>
                        <div class="col-lg-10">
                            <select class="select-fixed-singles" name="branch">
                                @foreach($branches as $valueBranches)
                                    <option value="{{ $valueBranches->id }}">{{ $valueBranches->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group has-feedback-left">
                        <label class="text-semibold col-lg-2 control-label">Gruop</label>
                        <div class="col-lg-10">
                            <select class="select-fixed-singles" name="group">
                            @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

  
                    <button type="button" class="btn btn-primary btn-block content-group" onclick="document.forms['new'].submit(); return false;"> <i class="icon-user-plus position-left"></i> Add New User</button>
                </form>

            </div>
        </div>
    </div>

    <!-- Bypass Printers, DVR, Smart devices -->
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group3"> <i class="icon-printer2 position-left"></i> Bypass Printers, DVR, Smart devices</a>
            </h6>
        </div>
        <div id="accordion-control-right-group3" class="panel-collapse collapse">
            <div class="panel-body">

                <form action="{{ url('addNewUnregisteredUser/') }}" method="POST" id="bypass" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input name="type" type="hidden" value="bypass">
                    <input name="mac" type="hidden" value="{{ $mac }}">

                    <div class="alert alert-info  alert-bordered">
                        <center><span class="text-semibold">Use it to remove landing page,open internet and make this IP static, </span>
                        <br>If you need to open this device through real IP just enter port,
                        <br>* you can manage all devices by open Branch page and click edit.
                        <br>* System affected within 1 minute.</center>
                    </div>
                    <div class="form-group has-feedback-left">
                        <label class="text-semibold col-lg-2 control-label" title="Leave it empty if you don't know it">Port</label>
                        <div class="col-lg-10">
                            <input name="port" type="text" class="form-control" title="Only to be accesable from outside enter your port ex.80 or 0-65535" placeholder="Leave it empty or enter port ex(0-65535)">
                            <div class="form-control-feedback">
                                <i class="icon-server"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback-left">
                        <label class="text-semibold col-lg-2 control-label">Branch</label>
                        <div class="col-lg-10">
                            <select class="select-fixed-singles" name="branch">
                                @foreach($branches as $valueBranches)
                                    <option value="{{ $valueBranches->id }}">{{ $valueBranches->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary btn-block content-group" onclick="document.forms['bypass'].submit(); return false;"> <i class="icon-server position-left"></i> Bypass device</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>

<script>
    /////////////////////////////////////
    // for view all HTML fields correctly
    /////////////////////////////////////
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

    $('.select-fixed-singles').select2({
        minimumResultsForSearch: Infinity,
        width: 150
    });

    /////////////////////////////////////
    // End view all HTML fields correctly
    /////////////////////////////////////
    
    $('#edit-filtration').on('switchChange.bootstrapSwitch', function (event, state) {
        if (state === true) {
            $('.filtration-table').show();
            $('.filtration-type').show();
        } else {
            $('.filtration-table').hide();
            $('.filtration-type').hide();                           
        }
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

</script>
