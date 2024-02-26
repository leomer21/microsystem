<div class="row">
    <form action="#" class="steps-validation2">

        <h6>Network data</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Network name: <span class="text-danger">*</span></label>
                        <select id="networkname2" name="networkname" data-placeholder="Choose a Network..." class="select required">
                            <option></option>
                            @foreach($networks as $network)
                                <option  @if($packages->network_id == $network->id) selected  @endif value="{{ $network->id }}">{{ $network->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </fieldset>

        <h6>Packages data</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Package Name: <span class="text-danger">*</span></label>
                        <div id="type2"></div>
                        <input type="hidden" name="id" id="id" value="{{$packages->id}}">
                        <input type="text" name="packagename" id="packagename2" value="{{$packages->name}}" class="form-control required">
                    </div>
                </div>

               <div class="col-md-6">
                    <div class="form-group">
                        <label>Package Price: <span class="text-danger">*</span></label>
                        <input type="number" id="packageprice2" name="packageprice" value="{{$packages->price}}" class="form-control required">
                    </div>
               </div>
               <div class="col-md-6" style="display: none;" id="packageperiodtime2">
                    <div class="form-group">
                        <label>Package Period: <span class="text-danger">*</span></label>
                        <div class="form-group has-feedback has-feedback-left"><?php $time = gmdate("H:i:s", $packages->period); $split = explode(":",$time);?>
                             <input id="packageperiods2" name="packageperiods2" type="text" class="form-control timepicker" value="{{ $time }}"  data-timepicki-tim="{{ $split[0] }}" data-timepicki-mini="{{ $split[1] }}" data-timepicki-sand="{{ $split[2] }}">
                             <div class="form-control-feedback">
                                 <i class="icon-alarm"></i>
                             </div>
                        </div>
                    </div>
               </div>
               <div class="col-md-6" id="packagePeriodGeneral" style="display: none;">
                    <div class="form-group">
                        <label>Package Period: <span id="specificTitle" class="text-danger">*</span></label>
                        <input type="number" id="packageperiod22" name="packageperiod22" value="{{$packages->period}}" class="form-control">
                    </div>
               </div>

               <div class="col-md-6" id="group-name2" style="display: none;" >
                    <div class="form-group">
                        <label>Groups:<span class="text-danger">*</span></label>
                        <select id="groupname2" name="groupname"  data-placeholder="Choose a Groups..." class="select" value="{{ $packages->group_id }}">
                            <option></option>
                            <option value="0">Without Group</option>
                            @foreach($groups as $group)
                                <option  @if($packages->group_id == $group->id) selected  @endif value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
               </div>
               <div class="col-md-6" style="display: none;" id="expiration2">
                    <div class="form-group">
                        <label>Expiration after the following dayes:<span class="text-danger">*</span></label>
                        <input type="number" id="expirations2" name="expiration" value="{{$packages->time_package_expiry}}" class="form-control">
                    </div>
               </div>

               <div class="col-md-6" style="display: none;"  id="extra2">
                    <div class="form-group">
                        <label>Extra Quota:<span class="text-danger">*</span></label>
                        <input type="number" id="extraa" name="extra" value="{{$packages->period}}" class="form-control">
                    </div>
               </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status:</label>
                        <div class="checkbox checkbox-switchery">
                            <label>
                                <input type="checkbox" id="state2" name="state" value="1" class="switchery-info2" @if($packages->state == 1) checked @endif>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Offer:</label>
                        <div class="checkbox checkbox-switchery">
                            <label>
                                <input type="checkbox" id="offer2" name="offer" value="1" class="switchery-warning2" @if($packages->offer == 1) checked @endif>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Notes:</label>
                        <div class="checkbox checkbox-switchery">
                                <textarea name="notes" id="notes2" type="text" rows="3" class="form-control input-xlg">{{$packages->notes}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>
    </form>
</div>


    <meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
    $( document ).ready(function() {
        @if($packages->type == 1){
            $('#specificTitle').append("(Months) ");
            $('#type2').append('<input id="packagestype2" name="packagestype" type="hidden" value="1">');
            $('#expiration2').hide();
            $('#extra2').hide();
            $('#group-name2').show();
            $('#packagePeriodGeneral').show();
            $('#packageperiodtime2').hide();
        }
        @elseif($packages->type == 2){
            $('#specificTitle').append("(Days) ");
            $('#type2').append('<input id="packagestype2" name="packagestype" type="hidden" value="2">');
            $('#expiration').hide();
            $('#extra2').hide();
            $('#group-name2').show();
            $('#packagePeriodGeneral').show();
            $('#packageperiodtime2').hide();
        }
        @elseif($packages->type == 3){
            $('#type2').append('<input id="packagestype2" name="packagestype" type="hidden" value="3">');
            $('#expiration2').show();
            $('#extra2').hide();
            $('#packageperiodtime2').show();
            $('#group-name2').show();
            $('#packagePeriodGeneral').hide();
        }
        @elseif($packages->type == 4){
            $('#type2').append('<input id="packagestype2" name="packagestype" type="hidden" value="4">');
            $('#group-name2').hide();
            $('#expiration2').hide();
            $('#packageperiodtime2').hide();
            $('#packagePeriodGeneral').hide();
            $('#extra2').show();    
        }
        @else{}
        @endif
    });
    // Show form
    var form = $(".steps-validation2").show();


    // Initialize wizard
    $(".steps-validation2").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="number">#index#</span> #title#',
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex) {

            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }

            // Forbid next action on "Warning" step if the user is to young
            if (newIndex === 3 && Number($("#age-2").val()) < 18) {

                return false;
            }

            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {

                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },

        onStepChanged: function (event, currentIndex, priorIndex) {

            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {


                form.steps("next");
            }

            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                form.steps("previous");
            }
        },

        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },

        onFinished: function (event, currentIndex) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if(document.querySelector("#id")){var id = document.querySelector("#id").value;}
            if(document.querySelector("#packagestype2")){var packagestype = document.querySelector("#packagestype2").value;}
            if(document.querySelector("#packagename2")){var packagename = document.querySelector("#packagename2").value;}
            if(document.querySelector("#packageprice2")){var packageprice = document.querySelector("#packageprice2").value;}
            if(document.querySelector("#packageperiods2")){var packageperiod = document.querySelector("#packageperiods2").value;}
            if(document.querySelector("#packageperiod22")){var packageperiod2 = document.querySelector("#packageperiod22").value;}
            if(document.querySelector("#groupname2")){var groupname = document.querySelector("#groupname2").value;}
            if(document.querySelector("#networkname2")){var networkname = document.querySelector("#networkname2").value;}
            if(document.querySelector("#expirations2")){var expiration = document.querySelector("#expirations2").value;}
            if(document.querySelector("#extraa")){var extra = document.querySelector("#extraa").value;}
            if(document.querySelector("#notes2")){var notes = document.querySelector("#notes2").value;}
            if(document.querySelector("#state2")){var state = document.querySelector("#state2").checked;}
            if(document.querySelector("#offer2")){var offer = document.querySelector("#offer2").checked;}

            //if(state == "on"){var state = 1;}else{var state = 0; }

            $.ajax({
                'url':'editpackages',
                'data':{_token: CSRF_TOKEN,id:id,packagestype:packagestype,offer:offer,packagename:packagename,packageprice:packageprice,packageperiod:packageperiod,packageperiod2:packageperiod2,groupname:groupname,networkname:networkname,expiration:expiration,extra:extra,notes:notes,state:state},
                'type':'post',
                success:function(data) {
                    new PNotify({
                        title: 'Success',
                        text: 'Package has been modified successfully.',
                        addclass: 'alert bg-success alert-styled-right',
                        type: 'error'
                    });
                    location.reload();
                },
                error:function(data) {
                    new PNotify({
                        title: 'Oops!!',
                        text: 'Filed creation, please check your fileds.',
                        addclass: 'alert bg-danger alert-styled-right',
                        type: 'error'
                    });
                }
            });
        }
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
        width: 353
    });
    $('.select-fixed-single200').select2({
            minimumResultsForSearch: Infinity,
            width: 353
        });
    $('.select-fixed-single100').select2({
        minimumResultsForSearch: Infinity,
        width: 120
    });
    var info = document.querySelector('.switchery-info2');
    var switchery = new Switchery(info, { color: '#00BCD4'});

    var warning = document.querySelector('.switchery-warning2');
    var switchery = new Switchery(warning, { color: '#FF7043' });

    // Info
    $(".control-info").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });
    // Warning
    $(".control-warning").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-warning-600 text-warning-800'
    });
    $('.timepicker').timepicki({
            show_meridian:false,
            min_hour_value:0,
            max_hour_value:23,
            overflow_minutes:true,
            increase_direction:'up',
            disable_keyboard_mobile: true
        });
    // Select2 selects
    $('.select').select2();


</script>