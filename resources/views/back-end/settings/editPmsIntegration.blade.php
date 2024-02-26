
    
<h6 class="text-semibold"></h6>

<div class="row">
    <form action="{{ url('editPmsIntegration/'.$pms->id) }}" method="POST" id="editPmsIntegration" class="form-horizontal">

        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="panel-group panel-group-control content-group-lg" id="accordion-control">

            <!-- basic -->
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-control"
                        href="#accordion-control-group11">Basic</a>
                    </h6>
                </div>
                <div id="accordion-control-group11" class="panel-collapse collapse in">
                    <div class="panel-body">

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Name</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="name" type="text" value="{{$pms->name}}" class="form-control input-xlg"
                                        placeholder="">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">State</label>
                            <div class="col-lg-8">
                                <select class="selectt" name="state">
                                    <option @if($pms->state == '1') selected @endif value="1">Activated</option>
                                    <option @if($pms->state == '0') selected @endif value="0">Deactivated</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">PMS type</label>
                            <div class="col-lg-8">
                                <select class="selectt" name="type">
                                    <option @if($pms->type == 'opera51') selected @endif value="opera51">Opera V5.1</option>
                                    <option @if($pms->type == 'opera55') selected @endif value="opera55">Opera V5.5</option>
                                    <option @if($pms->type == 'suite8') selected @endif value="suite8">Suite 8</option>
                                    <option @if($pms->type == 'protel') selected @endif value="protel">Protel</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Connection type</label>
                            <div class="col-lg-8">
                                <select class="selectt" name="connection_type" id="edit_connection_type">
                                    <option @if($pms->connection_type == 'interface') selected @endif value="interface">Through Interface</option>
                                    <option @if($pms->connection_type == 'database') selected @endif value="database">Database directly</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Check-In Group</label>
                            <div class="col-lg-8">
                                <select class="selectt" name="internet_group">
                                    @foreach(App\Groups::where('is_active','1')->where('as_system','0')->get() as $group)
                                        <option @if($pms->internet_group == $group->id) selected @endif value="{{$group->id}}"> {{$group->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Check-Out Group</label>
                            <div class="col-lg-8">
                                <select class="selectt" name="checkout_group">
                                    @foreach(App\Groups::where('is_active','1')->where('as_system','0')->get() as $group)
                                        <option @if($pms->checkout_group == $group->id) selected @endif value="{{$group->id}}"> {{$group->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /basic -->
            
            <!-- Interface connection variables -->
            <div class="panel panel-white edit_interface_connection_variables" @if($pms->connection_type == 'database') style="display: none;" @endif>
                <div class="panel-heading">
                    <h6 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                        href="#accordion-control-group22">Interface Connection</a>
                    </h6>
                </div>
                <div id="accordion-control-group22" class="panel-collapse collapse">
                    <div class="panel-body">
                        
                        <div class="alert alert-info alert-styled-left alert-bordered">
                            <span class="text-semibold">You can ask the PMS interface provider</span> to configure the following parameter into the interface to be able to get the guest contacts:
                            <br>
                            <span class="text-semibold">A0 (Guest Birthday) Format: DDMMYYYY</span> <br>
                            <span class="text-semibold">A1 (Guest E-Mail)</span> <br>
                            <span class="text-semibold">A2 (Guest Mobile)</span> <br>
                            <span class="text-semibold">A3 (Guest Nationality)</span> <br>
                            <span class="text-semibold">A4 (Guest Gender)</span><br>
                            <span class="text-semibold">A5 (Guest Confirmation Number)</span><br>
                            <span class="text-semibold">A6 (Guest Room Type)</span><br>
                            <span class="text-semibold">A7 (Passport No)</span><br><br>
                                                    <span class="text-semibold">Notes: Guest will be assigned to a group with the same name or Room Type if exist.</span><br>
                                                    <span class="text-semibold">Notes: Make sure that PMS sends (GC) update in case of any change in guest profile ex.(email, mobile, birthdate).</span>
                            
                        </div>
                            
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Interface IP</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="interface_ip" type="text" class="form-control input-xlg" value="{{$pms->interface_ip}}"
                                        placeholder="127.0.0.1">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Interface Port</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="interface_port" type="text" class="form-control input-xlg" value="{{$pms->interface_port}}"
                                        placeholder="9090">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- /Interface connection variables -->
            
            <!-- Database connection variables -->
            <div class="panel panel-white edit_database_connection_variables" @if($pms->connection_type == 'interface') style="display: none;" @endif >
                <div class="panel-heading">
                    <h6 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                        href="#accordion-control-group33">Database Connection</a>
                    </h6>
                </div>
                <div id="accordion-control-group33" class="panel-collapse collapse">
                    <div class="panel-body">
                        
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Database IP</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="db_ip" type="text" class="form-control input-xlg" value="{{$pms->db_ip}}"
                                        placeholder="127.0.0.1">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Database Port</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="db_port" type="text" class="form-control input-xlg" value="{{$pms->db_port}}"
                                        placeholder="1521">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Database Name</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="db_name" type="text" class="form-control input-xlg" value="{{$pms->db_name}}"
                                        placeholder="opera">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Database Username</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="db_username" type="text" class="form-control input-xlg" value="{{$pms->db_username}}"
                                        placeholder="opera">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Database Password</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="db_password" type="password" class="form-control input-xlg" value="{{$pms->db_password}}" placeholder="opera">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Internet Revenue Code</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="db_transaction_code" type="text" class="form-control input-xlg" value="{{$pms->db_transaction_code}}"
                                        placeholder="5608">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Supervisor or IFC Username</label>
                            <div class="col-lg-9">
                                <div class="form-group has-feedback has-feedback-left">
                                    <input name="db_posting_username" type="text" class="form-control input-xlg" value="{{$pms->db_posting_username}}"
                                        placeholder="SUPERVISOR">
                                    <div class="form-control-feedback">
                                        <i class="icon-server"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /Database connection variables -->

            <!-- Login by -->
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h6 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control"
                        href="#accordion-control-group44">Login by</a>
                    </h6>
                </div>
                <div id="accordion-control-group44" class="panel-collapse collapse">
                    <div class="panel-body">
                        
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Login Username</label>
                            <div class="col-lg-8">
                                <select class="selectt" name="login_username">
                                    <option @if($pms->login_username == 'room_no') selected @endif value="room_no">Room Number</option>
                                    <option @if($pms->login_username == 'first_name') selected @endif value="first_name">First Name</option>
                                    <option @if($pms->login_username == 'last_name') selected @endif value="last_name">Last Name</option>
                                    <option @if($pms->login_username == 'mobile') selected @endif value="mobile">Mobile</option>
                                    <option @if($pms->login_username == 'email') selected @endif value="email">Email</option>
                                    <option @if($pms->login_username == 'birth_date') selected @endif value="birth_date">Birth Date</option>
                                    <option @if($pms->login_username == 'reservation_no') selected @endif value="reservation_no">Reservation Number</option>
                                    <option @if($pms->login_username == 'confirmation_no') selected @endif value="confirmation_no">Confirmation Number</option>
                                    <option @if($pms->login_username == 'check_in_date') selected @endif value="check_in_date">Check-In date (DDMMYYYY)</option>
                                    <option @if($pms->login_username == 'check_out_date') selected @endif value="check_out_date">Check-Out date (DDMMYYYY)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group col-lg-12">
                            <label class="control-label col-lg-3">Login Password</label>
                            <div class="col-lg-8">
                                <select class="selectt" name="login_password">
                                    <option @if($pms->login_password == 'room_no') selected @endif value="room_no">Room Number</option>
                                    <option @if($pms->login_password == 'first_name') selected @endif value="first_name">First Name</option>
                                    <option @if($pms->login_password == 'last_name') selected @endif value="last_name">Last Name</option>
                                    <option @if($pms->login_password == 'mobile') selected @endif value="mobile">Mobile</option>
                                    <option @if($pms->login_password == 'email') selected @endif value="email">Email</option>
                                    <option @if($pms->login_password == 'birth_date') selected @endif value="birth_date">Birth Date</option>
                                    <option @if($pms->login_password == 'reservation_no') selected @endif value="reservation_no">Reservation Number</option>
                                    <option @if($pms->login_password == 'confirmation_no') selected @endif value="confirmation_no">Confirmation Number</option>
                                    <option @if($pms->login_password == 'check_in_date') selected @endif value="check_in_date">Check-In date (DDMMYYYY)</option>
                                    <option @if($pms->login_password == 'check_out_date') selected @endif value="check_out_date">Check-Out date (DDMMYYYY)</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- /Login by -->

        </div>
    </form>
</div>

<script>
    $("#edit_connection_type").change(function(){
        var connectiontype = $("#edit_connection_type option:selected").val();
        
        if(connectiontype == 'interface'){
            $('.edit_interface_connection_variables').show();
            $('.edit_database_connection_variables').hide();
        }

        if(connectiontype == 'database'){
            $('.edit_database_connection_variables').show();
            $('.edit_interface_connection_variables').hide();
        }
    });


    // Form components
    // ------------------------------

    // Select2 selects
    $('.select').select2({
        minimumInputLength: 2,
        minimumResultsForSearch: Infinity
    });

    $('.select0').select2({
        minimumResultsForSearch: Infinity
    });

    $('.selectt').select2({
        minimumResultsForSearch: Infinity
    });

    // Styled checkboxes, radios
    $(".styled").uniform({
        radioClass: 'choice'
    });

</script>