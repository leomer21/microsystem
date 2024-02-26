<div class="row">
                <form action="{{ url('/editadmin/'.$admins->id) }}" method="POST" id="editadmin" class="form-horizontal">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="form-group col-lg-12 {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="control-label col-lg-3">Name</label>
                    <div class="col-lg-9">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="name" type="text" class="form-control input-xlg" value="{{ $admins->name }}">
                            <div class="form-control-feedback">
                                <i class="icon-user"></i>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Email <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="email" type="text" class="form-control input-xlg" value="{{ $admins->email }}">
                            <div class="form-control-feedback">
                                <i class="icon-mention"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="control-label col-lg-3">Password <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="password" type="password" class="form-control input-xlg" value="{{ $admins->uname }}" >
                            <div class="form-control-feedback">
                                <i class="icon-key"></i>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group has-feedback-left col-lg-12">
                      <div class="col-lg-3"></div>
                      <div class="col-lg-3">
                         <label class="text-semibold">Gender</label>
                          <select class="select-fixed-single" name="gender">
                              <option @if($admins->gender == 1) selected @endif value="1">Male</option>
                              <option @if($admins->gender == 0) selected @endif value="0">Female</option>
                          </select>
                          <div class="form-control-feedback">
                              <i class="icon-mail"></i>
                          </div>
                      </div>
                      <div class="col-lg-2"></div>
                      @if($admins->type == 1)
                      <input type="hidden" name="type" value="1">
                      @endif

                      @if($admins->type == 2)
                      <input type="hidden" name="type" value="2">
                      @endif
                      
                        <!--<div class="col-lg-4">
                            <label class="text-semibold">Type</label>
                            <select class="select-fixed-single" name="type">
                                <option @if($admins->type == 1) selected @endif  value="1">Admin</option>
                                <option @if($admins->type == 2) selected @endif value="2">Reseller</option>
                            </select>
                        </div>-->
                      
                </div>
                @if($admins->type != 1)
                    <div class="form-group has-feedback-left col-lg-12">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9">
                            <label class="text-semibold">Branches</label>
                            <?php
                                if(isset($admins->branches)){
                                    $split_branches = explode(',', $admins->branches);
                                }?>

                            <select class="bootstrap-selectt2 select-all-valuess2" multiple="multiple" data-width="100%" name="branches[]">
                                @foreach($branches as  $branche)
                                    <option value="{{ $branche->id }}"
                                    @if(isset($split_branches))
                                        @foreach($split_branches as $value)
                                            @if($value == $branche->id) selected @endif 
                                        @endforeach
                                    @endif
                                    >{{ $branche->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group has-feedback-left col-lg-12">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info" id="select-all-valuess2">Select all</button>
                                <button type="button" class="btn btn-default" id="deselect-all-valuess2">Deselect all</button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($admins->type != 2)
                <div class="form-group has-feedback-left col-lg-12">
                     <div class="col-lg-3"></div>
                     <div class="col-lg-9">
                        <label class="text-semibold">Permission</label>
                           <select class="bootstrap-select2 select-all-values2" multiple="multiple" data-width="100%" name="permissions[]">
                                <option @if(strpos($admins->permissions, 'dashboard') !== false) selected @endif value="dashboard">Dashboard</option>
                                <option @if(strpos($admins->permissions, 'users') !== false) selected @endif value="users">Users</option>
                                <option @if(strpos($admins->permissions, 'onlineusers') !== false) selected @endif value="onlineusers">Online users</option>
                                <option @if(strpos($admins->permissions, 'groups') !== false) selected @endif value="groups">Groups</option>
                                <option @if(strpos($admins->permissions, 'branches') !== false) selected @endif value="branches">Branches</option>
                                <option @if(strpos($admins->permissions, 'administration') !== false) selected @endif value="administration">Administration</option>
                                <option @if(strpos($admins->permissions, 'packages') !== false) selected @endif value="packages">Packages</option>
                                <option @if(strpos($admins->permissions, 'cards') !== false) selected @endif value="cards" >Cards</option>
                                <option @if(strpos($admins->permissions, 'campaign') !== false) selected @endif value="campaign">Campaigns</option>
                                <option @if(strpos($admins->permissions, 'landingpage') !== false) selected @endif value="landingpage">Landing Pages</option>
                                <option @if(strpos($admins->permissions, 'settings') !== false) selected @endif value="settings">Settings</option>
                                <option @if(strpos($admins->permissions, 'WAadmin') !== false) selected @endif value="WAadmin">WhatsApp Administrator</option>
                                <option @if(strpos($admins->permissions, 'WAregPoints') !== false) selected @endif value="WAregPoints">WhatsApp Loyalty Register Points</option>
                                <option @if(strpos($admins->permissions, 'WAredeemPoints') !== false) selected @endif value="WAredeemPoints">WhatsApp Loyalty Redeem Points</option>
                           </select>
                     </div>
                </div>
                <div class="form-group has-feedback-left col-lg-12">
                     <div class="col-lg-3"></div>
                     <div class="col-lg-9">
                          <div class="input-group-btn">
                              <button type="button" class="btn btn-info" id="select-all-values2">Select all</button>
                              <button type="button" class="btn btn-default" id="deselect-all-values2">Deselect all</button>
                          </div>
                     </div>
                </div>
                @endif

                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Mobile <span class="text-danger">*</span> </label>
                    <div class="col-lg-9">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="phone" type="text" class="form-control input-xlg" value="{{ $admins->mobile }}" placeholder="201012345678">
                            <span class="help-block"> Please enter WhatsApp numner with country code ex.(201012345678) </span>
                            <div class="form-control-feedback">
                                <i class="icon-mobile"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Address</label>
                    <div class="col-lg-9">
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="address" type="text" class="form-control input-xlg" value="{{ $admins->address }}">
                            <div class="form-control-feedback">
                                <i class="icon-home"></i>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="form-group col-lg-12">
                    <label class="control-label col-lg-3">Notes</label>
                    <div class="col-lg-9">
                        <textarea name="notes" type="text" rows="3" class="form-control input-xlg">{{ $admins->notes }}</textarea>
                    </div>
                </div>

                </form>
                </div>
                <script>
                 // Fixed width. Single select
                   $('.select-fixed-single').select2({
                       minimumResultsForSearch: Infinity,
                       width: 165
                   });
                   // Format icon
                   function iconFormat(icon) {
                       var originalOption = icon.element;
                       if (!icon.id) { return icon.text; }
                       var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

                       return $icon;
                   }
                   // Initialize with options
                   $(".select-icons").select2({
                       templateResult: iconFormat,
                       minimumResultsForSearch: Infinity,
                       templateSelection: iconFormat,
                       escapeMarkup: function(m) { return m; }
                   });
                   // Basic select
                   $('.bootstrap-select2').selectpicker();

                   // Select all method
                   $('#select-all-values2').on('click', function() {
                       $('.select-all-values2').selectpicker('selectAll');
                   });


                   // Deselect all method
                   $('#deselect-all-values2').on('click', function() {
                       $('.select-all-values2').selectpicker('deselectAll');
                   });
                  // Basic select
                  $('.bootstrap-selectt2').selectpicker();

                  // Select all method
                  $('#select-all-valuess2').on('click', function() {
                      $('.select-all-valuess2').selectpicker('selectAll');
                  });


                  // Deselect all method
                  $('#deselect-all-valuess2').on('click', function() {
                      $('.select-all-valuess2').selectpicker('deselectAll');
                  });
                </script>