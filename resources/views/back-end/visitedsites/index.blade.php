@extends('.back-end.layouts.master')

@section('title', 'Visited Sites')
@section('sidebar')

    <div class="sidebar sidebar-secondary sidebar-default">
        <div class="sidebar-content">

        <form id="search123" action="{{ url('search_visitedsites') }}" method="POST">
            <!-- ///////////////////////////////////////////////////////////// Start Filters ///////////////////////////////////////////////////////////////// -->
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <!--Filter Branches for -->
            @foreach ($networks as $network)
                <div class="sidebar-category">
                    <div class="category-title category-collapsed">
                        <span>{{ $network->name }} Branches</span>
                        <input type="hidden" name="networks" value="{{ $network->id }}"> 
                        <ul class="icons-list">
                            <li><a href="#" data-action="collapse"></a></li>
                        </ul>
                    </div>

                    <div class="category-content" @if(app('request')->input('network') ) style="display: block;"
                         @else style="display: none;" @endif >
                        @foreach ($branches as $branch)
                            <?php  if ($branch->network_id != $network->id) continue; ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="styled network" name="branches[]" value="{{ $branch->id }}">
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
                                <input type="checkbox" class="styled groups" name="groups[]" value="{{ $area_group->id }}">
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
                    <span> User info </span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('userid') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>User id</label>
                    <input class="frequency form-control" type="text" name="userid">
                </div>
                <div class="category-content" @if(app('request')->input('username') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>User name</label>
                    <input class="frequency form-control" type="text" name="username">
                </div>
                <div class="category-content" @if(app('request')->input('name') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Name</label>
                    <input class="frequency form-control" type="text" name="name">
                </div>
                <div class="category-content" @if(app('request')->input('mac') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>MACAddress</label>
                    <input class="frequency form-control" type="text" name="mac">
                </div>
                <div class="category-content" @if(app('request')->input('phone') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Mobile</label>
                    <input class="frequency form-control" type="text" name="phone">
                </div>
                <div class="category-content" @if(app('request')->input('email') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Email</label>
                    <input class="frequency form-control" type="text" name="email">
                </div>

                <div class="category-content" @if(app('request')->input('country') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>{{ trans('search.country') }}</label>
                    @foreach ($countrys as $country)
                        <?php  if($country->country != null){ ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled country" name="country" value="{{ $country->country }}">
                                {{ $country->country}} <span> ({{ $country->count}})</span>
                            </label>
                        </div>
                        <?}?>
                    @endforeach
                </div>

                <div class="category-content "
                    @if(app('request')->input('male')=="on" or app('request')->input('female')=="on" or app('request')->input('Unknown')=="on") style="display: block;"
                        @else style="display: none;" @endif >
                    <label>{{ trans('search.gender') }}</label>

                    @foreach ($genders as $gender)
                        <?php  if($gender->u_gender == 1){ ?>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled male" name="male">
                                Male <span> ({{ $gender->count_g }}) </span>
                            </label>
                        </div>
                        <? }elseif($gender->u_gender == 0){?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled female" name="female">
                                Female <span> ({{ $gender->count_g }}) </span>
                            </label>
                        </div>
                        <?} else{?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="styled Unknown" name="unknown">

                                Unknown <span> ({{ $gender->count_g }}) </span>
                            </label>
                        </div>
                        <?}?>
                    @endforeach
                </div>
            </div>
            <!-- /actions -->

            <!-- VIEW USERS CHARGED-->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Visit date</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content"
                     @if(app('request')->input('Users_not_charged_from') || app('request')->input('Users_charged_from')) style="display: block;"
                     @else style="display: none;" @endif>
                    <label>Visit date from: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control pickadate-format" value="" id="" name="visitdate-from">
                    </div>
                    <label>Visit date to: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control pickadate-format" value="" id="" name="visitdate-to">
                    </div>
                </div>
            </div>
            <!--VIEW USERS CHARGED -->

             <!-- VIEW USERS CHARGED-->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Session end date</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content"
                     @if(app('request')->input('Users_not_charged_from') || app('request')->input('Users_charged_from')) style="display: block;"
                     @else style="display: none;" @endif>
                    <label>Session end date from: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control pickadate-format" value="" id="" name="session-end-from">
                    </div>
                    <label>Session end date to: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control pickadate-format" value="" id="" name="session-end-to">
                    </div>
                </div>
            </div>
            <!--VIEW USERS CHARGED -->


             <!-- VIEW USERS CHARGED-->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Session start date</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content"
                     @if(app('request')->input('Users_not_charged_from') || app('request')->input('Users_charged_from')) style="display: block;"
                     @else style="display: none;" @endif>
                    <label>Session start date from: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control pickadate-format" value="" id="" name="session-start-from">
                    </div>
                    <label>Session start date to: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input type="text" class="form-control pickadate-format" value="" id="" name="session-start-to">
                    </div>
                </div>
            </div>
            <!--VIEW USERS CHARGED -->

            <!--Filter Gender -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Connection Type</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>


                <div class="category-content "
                     @if(app('request')->input('male')=="on" or app('request')->input('female')=="on" or app('request')->input('Unknown')=="on") style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Connection Type</label>
                    <select class="select-fixed-single" name="connection-type">
                        <option value="">All</option>
                        <option value="IN">Inbound</option>
                        <option value="OUT">Outgoing</option>    
                    </select>
                </div>

            </div>
            <!-- /Gender -->

           
            <!--Filter Protocol -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Protocol</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('country') ) style="display: block;"
                    @else style="display: none;" @endif >
                    <label>Protocol</label>
                    <select class="select-fixed-single" name="protocol">
                        <option value="">All</option>
                        <option value="ICMP">ICMP</option>
                        <option value="TCP">TCP</option>
                        <option value="UDP">UDP</option>    
                        <option value="igmp">igmp</option>    
                        <option value="ggp">ggp</option>    
                        <option value="ip-encap">ip-encap</option>    
                        <option value="st">st</option>    
                        <option value="egp">egp</option>    
                        <option value="pup">pup</option>    
                        <option value="hmp">hmp</option>    
                        <option value="xns-idp">xns-idp</option>    
                        <option value="rdp">rdp</option>    
                        <option value="ios-tp4">ios-tp4</option>    
                        <option value="xtp">xtp</option>    
                        <option value="ddp">ddp</option>    
                        <option value="idpr-cmtp">idpr-cmtp</option>    
                        <option value="gre">gre</option>    
                        <option value="ipsec-esp">ipsec-esp</option>    
                        <option value="ipsec-ah">ipsec-ah</option>    
                        <option value="rspf">rspf</option>    
                        <option value="vmtp">vmtp</option>    
                        <option value="ospf">ospf</option>    
                        <option value="ipip">ipip</option>    
                        <option value="encap">encap</option>    
                        <option value="vmp">vmp</option>     

                    </select>
                </div>
            </div>
            <!-- /Protocol -->

            <!--Filter Source Address -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Source Address</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('country') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Source Address</label>
                    <input class="form-control" type="text" name="source-address"> 
                </div>
            </div>
            <!-- /Source Address -->

            <!--Filter Source  Port -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Source  Port</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('country') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Source Port</label>
                    <input class="form-control" type="text name="source-port">  
                </div>
            </div>
            <!-- /Source  Port -->

            <!--Filter Destination Address -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Destination Address</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('country') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Destination Address</label>
                    <input class="form-control" type="text" name="destination-address">  
                </div>
            </div>
            <!-- /Destination Address -->   

            <!--Filter Destination Port -->
            <div class="sidebar-category">
                <div class="category-title category-collapsed">
                    <span>Destination Port</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content" @if(app('request')->input('country') ) style="display: block;"
                     @else style="display: none;" @endif >
                    <label>Destination Port</label>
                    <input class="form-control" type="text" name="destination-port">      
                </div>
            </div>
            <!-- /Destination Port -->

            <div class="form-group">
                <p></p>
                <center> 
                    <button id="search222" type="submit" class="btn btn-info btn-labeled btn-lg"><b><i class="icon-search4"></i></b> Search </button>
                </center>    
            </div>
        </div>
    </div>
    <!-- /main content -->
    <!-- /////////////////////////////////////////////////////////////End Filters ///////////////////////////////////////////////////////////////// -->
    </form>


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
                    <h5 class="panel-title">Cyber Defense Operations Center</h5>
                    <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
                <div class="panel-body">
                    
                    <table class="table" id="table-user">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select" class="styled"></th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Mobile</th>
                            
                            <th>Dst. Address</th>
                            <th>Dst. Port</th>

                            <th>Protocol</th>
                            <th>No of Visits</th>
                            <th>First Visit</th>
                            <th>Last Visit</th>
                            <th>Src. Address</th>
                            <th>Src. Port</th>

                            <th>Session started</th>
                            <th>Session ended</th>
                            <th>Mac Address</th>
                            <th>Country</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>User id</th>
                            <th>Connection Type</th>

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
@endsection
@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>

    <script type="text/javascript" src="assets/js/timepicki.js"></script>
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
    <script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>


    <script type="text/javascript" src="assets/js/plugins/forms/wizards/stepy.min.js"></script>
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

    <script>

            

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        
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


        // var network_array = [], setting_array = [], groups_array = [], Country_array = [];

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
            // var queryString = {};
            // queryString.parse = function (str) {
            //     if (typeof str !== 'string') {
            //         return {};
            //     }

            //     str = str.trim().replace(/^\?/, '');

            //     if (!str) {
            //         return {};
            //     }

            //     return str.trim().split('&').reduce(function (ret, param) {
            //         var parts = param.replace(/\+/g, ' ').split('=');
            //         var key = parts[0];
            //         var val = parts[1];

            //         key = decodeURIComponent(key);
            //         // missing `=` should be `null`:
            //         // http://w3.org/TR/2012/WD-url-20120524/#collect-url-parameters
            //         val = val === undefined ? null : decodeURIComponent(val);

            //         if (!ret.hasOwnProperty(key)) {
            //             ret[key] = val;
            //         } else if (Array.isArray(ret[key])) {
            //             ret[key].push(val);
            //         } else {
            //             ret[key] = [ret[key], val];
            //         }

            //         return ret;
            //     }, {});
            // };
            // queryString.stringify = function (obj) {
            //     return obj ? Object.keys(obj).map(function (key) {
            //         var val = obj[key];
            //         if (Array.isArray(val)) {
            //             return val.map(function (val2) {
            //                 return encodeURIComponent(key) + '=' + encodeURIComponent(val2);
            //             }).join('&');
            //         }
            //         return encodeURIComponent(key) + '=' + encodeURIComponent(val);
            //     }).join('&') : '';
            // };
            // queryString.push = function (key, new_value, run = {}) {
            //     var params = {};
            //     params = queryString.parse(location.search);
            //     params[key] = new_value;

            //     if ($.isEmptyObject(run)) {
            //         var new_params_string = queryString.stringify(params)
            //         history.pushState({}, "", window.location.pathname + '?' + new_params_string);
            //         table.ajax.url("{{ url('search.json') }}" + '?' + new_params_string).load();
            //     } else {
            //         $.each(run, function (i, val) {
            //             params[i] = val;
            //         });
            //         var new_params_string = queryString.stringify(params)
            //         history.pushState({}, "", window.location.pathname + '?' + new_params_string);
            //         table.ajax.url("{{ url('search.json') }}" + '?' + new_params_string).load();
            //     }
            // }
            // if (typeof module !== 'undefined' && module.exports) {
            //     module.exports = queryString;
            // } else {
            //     window.queryString = queryString;
            // }
            $(".dropdown-menu li a").click(function () {
                var selText = $(this).text();
                $("#btn:first-child").html(selText + '<span class="caret"></span>');

            });
            //Network
            $('.network').on('click', function (event) {
                if ($(this).is(':checked')) {
                    var selText = $(this).val();
                    // var index = network_array.indexOf(selText);
                    // if (index < 0) {
                    //     // network_array.push(selText);
                    //     // queryString.push('network', network_array.join());
                    // }
                }
                else {
                    var selText = $(this).val();
                    // var index = network_array.indexOf(selText);
                    // if (index > -1) {
                    //     network_array.splice(index, 1);
                    // }
                    // queryString.push('network', network_array.join());
                }

            });

            //Groups
            $('.groups').on('click', function (event) {
                if ($(this).is(':checked')) {
                    var selText = $(this).val();
                    // var index = groups_array.indexOf(selText);
                    // if (index < 0) {
                    //     // groups_array.push(selText);
                    //     // queryString.push('groups', groups_array.join());
                    // }
                }
                else {
                    var selText = $(this).val();
                    // var index = groups_array.indexOf(selText);
                    // if (index > -1) {
                    //     // groups_array.splice(index, 1);
                    // }
                    // queryString.push('groups', groups_array.join());
                }

            });
            //Country
            $('.country').on('click', function (event) {
                if ($(this).is(':checked')) {
                    var selText = $(this).val();
                    // var index = Country_array.indexOf(selText);
                    // if (index < 0) {
                    //     Country_array.push(selText);
                    //     // queryString.push('country', Country_array.join());
                    // }

                }
                else {
                    var selText = $(this).val();
                    // var index = Country_array.indexOf(selText);
                    // if (index > -1) {
                    //     Country_array.splice(index, 1);
                    // }
                    // queryString.push('country', Country_array.join());
                    //queryString.push('country','');

                }

            });

            $('.frequency').keypress(function (e) {
                var key = e.which;
                if (key == 13)  // the enter key code
                {
                    // queryString.push('frequency', $(this).val());
                }
            });

            //Male
            $('.male').on('click', function (event) {
                if ($(this).is(':checked')) {
                    // queryString.push('male', 'on');
                }
                else {
                    // queryString.push('male', '');
                }

            });

            //Female
            $('.female').on('click', function (event) {
                if ($(this).is(':checked')) {
                    // queryString.push('female', 'on');
                }
                else {
                    // queryString.push('female', '');
                }

            });

            //Unknown
            $('.Unknown').on('click', function (event) {
                if ($(this).is(':checked')) {
                    // queryString.push('Unknown', 'on');
                }
                else {
                    // queryString.push('Unknown', '');
                }

            });

            //Reset FREQUENCY
            $('.resetFrequency').on('click', function (event) {
                // queryString.push('frequency', '');
                // queryString.push('user_frequency_charged_to', '');
                // queryString.push('user_frequency_charged_from', '');
            });

            //Reset resetCharged
            $('.resetCharged').on('click', function (event) {

                // queryString.push('Users_charged_from', '');
                // queryString.push('Users_charged_to', '');
            });

            //Reset resetNotCharged
            $('.resetNotCharged').on('click', function (event) {
                // queryString.push('Users_not_charged_from', '');
                // queryString.push('Users_not_charged_to', '');
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
                    // var index = network_array.indexOf($(this).val());
                    // if (index > -1) {
                    //     $(this).prop("checked", true);
                    // }
                });
            }


            if (getParameterByName('groups') != null) {
                groups_array = getParameterByName('groups').replace("[", "").replace("]", "").split(',');
                $('.groups').each(function (e) {
                    // var index = groups_array.indexOf($(this).val());
                    // if (index > -1) {
                    //     $(this).prop("checked", true);
                    // }
                });
            }
            if (getParameterByName('country') != null) {
                Country_array = getParameterByName('country').replace("[", "").replace("]", "").split(',');
                $('.country').each(function (e) {
                    // var index = Country_array.indexOf($(this).val());
                    // if (index > -1) {
                    //     $(this).prop("checked", true);
                    // }
                });
            }

            /*if (getParameterByName('Users_charged_from') != null) {
                $('#data_find1').val(getParameterByName('Users_charged_from') + ' - ' + getParameterByName('Users_charged_to'));
            }

            if (getParameterByName('Users_not_charged_from') != null) {
                $('#data_find2').val(getParameterByName('Users_not_charged_from') + ' - ' + getParameterByName('Users_not_charged_to'));
            }

            if (getParameterByName('user_frequency_charged_from') != null) {
                $('#data_find3').val(getParameterByName('user_frequency_charged_from') + ' - ' + getParameterByName('user_frequency_charged_to'));
            }*/
            if (getParameterByName('male') == 'on') {
                $('.male').prop('checked', true);

            }
            if (getParameterByName('female') == 'on') {
                $('.female').prop('checked', true);
            }

            /*if (getParameterByName('frequency') != null) {
                $('.frequency').val(getParameterByName('frequency'));
            }*/

            // Format options
            $('.pickadate-format').pickadate({

                // Escape any “rule” characters with an exclamation mark (!).
                formatSubmit: 'Y-m-d',
            });


            $('#data_find1').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    },
                    function (start, end, label) {
                        // queryString.push('Users_charged_from', start.format('DD/MM/YYYY'),
                        //         {"Users_charged_to": end.format('DD/MM/YYYY')});
                    });
            $('#data_find2').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    },
                    function (start, end, label) {
                        // queryString.push('Users_not_charged_from', start.format('DD/MM/YYYY'),
                        //         {"Users_not_charged_to": end.format('DD/MM/YYYY')});
                    });
            $('#data_find3').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY'
                        }
                    },
                    function (start, end, label) {
                        // queryString.push('user_frequency_charged_from', start.format('DD/MM/YYYY'),
                        //         {
                        //             "user_frequency_charged_to": end.format('DD/MM/YYYY'),
                        //             "frequency": $('.frequency').val()
                        //         });
                    });

            //datatable
            //var params = queryString.parse(location.search)
             var table = $('#table-user').DataTable({
                    responsive: {
                        details: {
                            type: 'column',
                            target: -1
                        }
                    },

                    buttons: {
                        buttons: [
                            /*{
                                extend: 'colvis',
                                text: '<i title="Search result." class=icon-floppy-disk></i>',
                                className: 'btn btn-default',
                                action: function (e, dt, node, config) {
                                    $('#modal_search_result').modal('show');
                                }
                            },*/{
                                text: '<i title="Copy selected users." class="icon-copy3"></i>',
                                extend: 'copyHtml5',
                                className: 'btn btn-default',
                                exportOptions: {
                                    columns: ':visible',
                                    modifier: {selected: true}
                                }
                            },
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
                                extend: 'colvis',
                                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                                className: 'btn bg-blue btn-icon'
                            }
                        ]
                    },

                    'order': [[1, 'asc']],
                    "processing": true,
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
                        {"data": "Name"}, 
                        {"data": "Username"},
                        {"data": "Mobile"},
                        {"data": "Dstaddress"},
                        {"data": "Dstport"},
                        {"data": "Protocol"},

                        {"data": "VisitsCount"},
                        {"data": "FirstVisit"},
                        {"data": "LastVisit"},
                        
                        {"data": "Srcaddress"},
                        {"data": "Srcport"},
                        {"data": "Sessionstarted"},
                        {"data": "Sessionended"},
                        {"data": "MacAddress"},
                        {"data": "Country"},
                        {"data": "Gender"},
                        {"data": "Email"},
                        {"data": "Id"},
                        {"data": "Connection Type"},
                        {"data": null, "defaultContent": ""}
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    }
                });   
            
            $('#table-user thead').on('change', 'input[type="checkbox"]', function () {
                if (!this.checked) {
                    table.rows().deselect();
                } else {
                    table.rows().select();
                }
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

            // Fixed width. Single select
            $('.select-fixed-single').select2({
                minimumResultsForSearch: Infinity
            });
            $("[name='my-checkbox']").bootstrapSwitch();

            var options = { 
                success: function(responseText, statusText, xhr, $form) {
                    table.rows().remove().draw();
                    table.rows.add(responseText).draw();
                } 
            }; 
             
            // pass options to ajaxForm 
            $('#search123').ajaxForm(options);

        });   
    </script>


@endsection