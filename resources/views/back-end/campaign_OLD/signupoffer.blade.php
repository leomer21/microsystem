<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <?php $campaigns =  App\Models\Campaigns::where('id',explode('/', url()->full())[5])->first()?>
    <title>{{ $campaigns->ad_name }}</title> 

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('/') }}assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>

    <script type="text/javascript" src="{{ asset('/') }}assets/js/core/app.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/pages/login.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}assets/js/pages/form_bootstrap_select.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
    <script type="text/javascript" src="assets/js/pages/form_validation.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}landing/js/jquery.flexslider.js"></script><!-- Flexslider JS -->
    <script type="text/javascript" src="{{ asset('/') }}landing/js/jquery.vegas.js"></script><!-- For Banner Slider JS -->
    <!-- HTTPS required. HTTP will give a 403 forbidden response -->
    <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>

    <!-- /theme JS files -->

</head>
<style>
    .panel-transparent {
        background: rgba(255,255,255, 0.5)!important;
    }

</style>
<body class="login-container background">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href=""><img src="{{ asset('/') }}upload/{{ App\Settings::where('type','logo')->value('value') }}" alt=""></a>
    </div>
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">
 
            <!-- Content area -->
            <div class="content">
                <form action="{{ url('loginkit') }}" method="post" id="form">
                   {{ csrf_field() }}
                    <input type="hidden" name="code" id="code">
                </form>    
                @if(App\Models\CampaignStatistics::where(['campaign_id' => explode('/', url()->full())[5], 'type' => 'offer'])->count() < $campaigns->offer_limit && App\Models\CampaignStatistics::where(['referral_code' => explode('/', url()->full())[4], 'campaign_id' => explode('/', url()->full())[5], 'type' => 'offer'])->count() < $campaigns->invite_friends)
                <!-- Registration form -->
                <form action="{{ url('signupoffer') }}" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3">
                            <div class="panel registration-form panel-transparent">
                                <div class="panel-body">
                                    <div class="text-center" style="background: rgba(255,255,255, 0.7)!important;">
                                        <div class="icon-object border-success text-success"><i class="icon-gift"></i>
                                        </div>
                                        
                                        <h5 class="content-group-lg">Remaining offers of {{ App\Users::where('u_id', explode('/', url()->full())[4])->value('u_name') }} : {{  $campaigns->invite_friends - App\Models\CampaignStatistics::where(['referral_code' => explode('/', url()->full())[4], 'campaign_id' => explode('/', url()->full())[5], 'type' => 'offer'])->count() }} <br> {{ $campaigns->offer_title }}   
                                            <small class="display-block">{{ $campaigns->offer_desc }}</small><br>
                                        </h5>
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ explode('/', url()->full())[4] }}">
                                    <input type="hidden" name="campaign_id" value="{{ explode('/', url()->full())[5] }}">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback">
                                                <input name="fullname" type="text" class="form-control"
                                                    placeholder="Full name" required>
                                                <div class="form-control-feedback">
                                                    <i class="icon-user-check text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback">
                                                <input name="email" type="email" class="form-control"
                                                       placeholder="Your email">
                                                <div class="form-control-feedback">
                                                    <i class="icon-mention text-muted"></i>
                                                </div>
                                                <label class="basic-error-email validation-error-label validation-valid-label" for="basic" style="display: none;"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                           <div class="form-group has-feedback">
                                                <input name="username" type="text" class="form-control"
                                                       placeholder="Enter username" required id="username">
                                                <div class="form-control-feedback">
                                                    <i class="icon-user-plus text-muted"></i>
                                                </div>
                                                <label class="basic-error-username validation-error-label validation-valid-label" for="basic" style="display: none;"></label>
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group has-feedback">
                                                <input name="password" type="password" class="form-control"
                                                       placeholder="Create password" required>
                                                <div class="form-control-feedback">
                                                    <i class="icon-user-lock text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="icon-earth"></i>
                                                </div>
                                                <select class="bootstrap-select" data-width="100%"  required name="countrycode">
                                                    <option value="44">UK +44
                                                    </option>
                                                    <option value="1">USA +1
                                                    </option>
                                                    <option value="213">Algeria +213
                                                    </option>
                                                    <option value="376">Andorra +376
                                                    </option>
                                                    <option value="244">Angola +244
                                                    </option>
                                                    <option value="1264">Anguilla +1264
                                                    </option>
                                                    <option value="1268">Antigua &amp; Barbuda +1268
                                                    </option>
                                                    <option value="599">Antilles Dutch +599
                                                    </option>
                                                    <option value="54">Argentina +54
                                                    </option>
                                                    <option value="374">Armenia +374
                                                    </option>
                                                    <option value="297">Aruba +297
                                                    </option>
                                                    <option value="247">Ascension Island +247
                                                    </option>
                                                    <option value="61">Australia +61
                                                    </option>
                                                    <option value="43">Austria +43
                                                    </option>
                                                    <option value="994">Azerbaijan +994
                                                    </option>
                                                    <option value="1242">Bahamas +1242
                                                    </option>
                                                    <option value="973">Bahrain +973
                                                    </option>
                                                    <option value="880">Bangladesh +880
                                                    </option>
                                                    <option value="1246">Barbados +1246
                                                    </option>
                                                    <option value="375">Belarus +375
                                                    </option>
                                                    <option value="32">Belgium +32
                                                    </option>
                                                    <option value="501">Belize +501
                                                    </option>
                                                    <option value="229">Benin +229
                                                    </option>
                                                    <option value="1441">Bermuda +1441
                                                    </option>
                                                    <option value="975">Bhutan +975
                                                    </option>
                                                    <option value="591">Bolivia +591
                                                    </option>
                                                    <option value="387">Bosnia Herzegovina +387
                                                    </option>
                                                    <option value="267">Botswana +267
                                                    </option>
                                                    <option value="55">Brazil +55
                                                    </option>
                                                    <option value="673">Brunei +673
                                                    </option>
                                                    <option value="359">Bulgaria +359
                                                    </option>
                                                    <option value="226">Burkina Faso +226
                                                    </option>
                                                    <option value="257">Burundi +257
                                                    </option>
                                                    <option value="855">Cambodia +855
                                                    </option>
                                                    <option value="237">Cameroon +237
                                                    </option>
                                                    <option value="1">Canada +1
                                                    </option>
                                                    <option value="238">Cape Verde Islands +238
                                                    </option>
                                                    <option value="1345">Cayman Islands +1345
                                                    </option>
                                                    <option value="236">Central African Republic +236
                                                    </option>
                                                    <option value="56">Chile +56
                                                    </option>
                                                    <option value="86">China +86
                                                    </option>
                                                    <option value="57">Colombia +57
                                                    </option>
                                                    <option value="269">Comoros +269
                                                    </option>
                                                    <option value="242">Congo +242
                                                    </option>
                                                    <option value="682">Cook Islands +682
                                                    </option>
                                                    <option value="506">Costa Rica +506
                                                    </option>
                                                    <option value="385">Croatia +385
                                                    </option>
                                                    <option value="53">Cuba +53
                                                    </option>
                                                    <option value="90392">Cyprus North +90392
                                                    </option>
                                                    <option value="357">Cyprus South +357
                                                    </option>
                                                    <option value="42">Czech Republic +42
                                                    </option>
                                                    <option value="45">Denmark +45
                                                    </option>
                                                    <option value="2463">Diego Garcia +2463
                                                    </option>
                                                    <option value="253">Djibouti +253
                                                    </option>
                                                    <option value="1809">Dominica +1809
                                                    </option>
                                                    <option value="1809">Dominican Republic +1809
                                                    </option>
                                                    <option value="593">Ecuador +593
                                                    </option>
                                                    <option selected="" value="2">Egypt
                                                    </option>
                                                    <option value="353">Eire +353
                                                    </option>
                                                    <option value="503">El Salvador +503
                                                    </option>
                                                    <option value="240">Equatorial Guinea +240
                                                    </option>
                                                    <option value="291">Eritrea +291
                                                    </option>
                                                    <option value="372">Estonia +372
                                                    </option>
                                                    <option value="251">Ethiopia +251
                                                    </option>
                                                    <option value="500">Falkland Islands +500
                                                    </option>
                                                    <option value="298">Faroe Islands +298
                                                    </option>
                                                    <option value="679">Fiji +679
                                                    </option>
                                                    <option value="358">Finland +358
                                                    </option>
                                                    <option value="33">France +33
                                                    </option>
                                                    <option value="594">French Guiana +594
                                                    </option>
                                                    <option value="689">French Polynesia +689
                                                    </option>
                                                    <option value="241">Gabon +241
                                                    </option>
                                                    <option value="220">Gambia +220
                                                    </option>
                                                    <option value="7880">Georgia +7880
                                                    </option>
                                                    <option value="49">Germany +49
                                                    </option>
                                                    <option value="233">Ghana +233
                                                    </option>
                                                    <option value="350">Gibraltar +350
                                                    </option>
                                                    <option value="30">Greece +30
                                                    </option>
                                                    <option value="299">Greenland +299
                                                    </option>
                                                    <option value="1473">Grenada +1473
                                                    </option>
                                                    <option value="590">Guadeloupe +590
                                                    </option>
                                                    <option value="671">Guam +671
                                                    </option>
                                                    <option value="502">Guatemala +502
                                                    </option>
                                                    <option value="224">Guinea +224
                                                    </option>
                                                    <option value="245">Guinea - Bissau +245
                                                    </option>
                                                    <option value="592">Guyana +592
                                                    </option>
                                                    <option value="509">Haiti +509
                                                    </option>
                                                    <option value="504">Honduras +504
                                                    </option>
                                                    <option value="852">Hong Kong +852
                                                    </option>
                                                    <option value="36">Hungary +36
                                                    </option>
                                                    <option value="354">Iceland +354
                                                    </option>
                                                    <option value="91">India +91
                                                    </option>
                                                    <option value="62">Indonesia +62
                                                    </option>
                                                    <option value="98">Iran +98
                                                    </option>
                                                    <option value="964">Iraq +964
                                                    </option>
                                                    <option value="972">Israel +972
                                                    </option>
                                                    <option value="39">Italy +39
                                                    </option>
                                                    <option value="225">Ivory Coast +225
                                                    </option>
                                                    <option value="1876">Jamaica +1876
                                                    </option>
                                                    <option value="81">Japan +81
                                                    </option>
                                                    <option value="962">Jordan +962
                                                    </option>
                                                    <option value="7">Kazakhstan +7
                                                    </option>
                                                    <option value="254">Kenya +254
                                                    </option>
                                                    <option value="686">Kiribati +686
                                                    </option>
                                                    <option value="850">Korea North +850
                                                    </option>
                                                    <option value="82">Korea South +82
                                                    </option>
                                                    <option value="965">Kuwait +965
                                                    </option>
                                                    <option value="996">Kyrgyzstan +996
                                                    </option>
                                                    <option value="856">Laos +856
                                                    </option>
                                                    <option value="371">Latvia +371
                                                    </option>
                                                    <option value="961">Lebanon +961
                                                    </option>
                                                    <option value="266">Lesotho +266
                                                    </option>
                                                    <option value="231">Liberia +231
                                                    </option>
                                                    <option value="218">Libya +218
                                                    </option>
                                                    <option value="417">Liechtenstein +417
                                                    </option>
                                                    <option value="370">Lithuania +370
                                                    </option>
                                                    <option value="352">Luxembourg +352
                                                    </option>
                                                    <option value="853">Macao +853
                                                    </option>
                                                    <option value="389">Macedonia +389
                                                    </option>
                                                    <option value="261">Madagascar +261
                                                    </option>
                                                    <option value="265">Malawi +265
                                                    </option>
                                                    <option value="60">Malaysia +60
                                                    </option>
                                                    <option value="960">Maldives +960
                                                    </option>
                                                    <option value="223">Mali +223
                                                    </option>
                                                    <option value="356">Malta +356
                                                    </option>
                                                    <option value="692">Marshall Islands +692
                                                    </option>
                                                    <option value="596">Martinique +596
                                                    </option>
                                                    <option value="222">Mauritania +222
                                                    </option>
                                                    <option value="269">Mayotte +269
                                                    </option>
                                                    <option value="52">Mexico +52
                                                    </option>
                                                    <option value="691">Micronesia +691
                                                    </option>
                                                    <option value="373">Moldova +373
                                                    </option>
                                                    <option value="377">Monaco +377
                                                    </option>
                                                    <option value="976">Mongolia +976
                                                    </option>
                                                    <option value="1664">Montserrat +1664
                                                    </option>
                                                    <option value="212">Morocco +212
                                                    </option>
                                                    <option value="258">Mozambique +258
                                                    </option>
                                                    <option value="95">Myanmar +95
                                                    </option>
                                                    <option value="264">Namibia +264
                                                    </option>
                                                    <option value="674">Nauru +674
                                                    </option>
                                                    <option value="977">Nepal +977
                                                    </option>
                                                    <option value="31">Netherlands +31
                                                    </option>
                                                    <option value="687">New Caledonia +687
                                                    </option>
                                                    <option value="64">New Zealand +64
                                                    </option>
                                                    <option value="505">Nicaragua +505
                                                    </option>
                                                    <option value="227">Niger +227
                                                    </option>
                                                    <option value="234">Nigeria +234
                                                    </option>
                                                    <option value="683">Niue +683
                                                    </option>
                                                    <option value="672">Norfolk Islands +672
                                                    </option>
                                                    <option value="670">Northern Marianas +670
                                                    </option>
                                                    <option value="47">Norway +47
                                                    </option>
                                                    <option value="968">Oman +968
                                                    </option>
                                                    <option value="680">Palau +680
                                                    </option>
                                                    <option value="507">Panama +507
                                                    </option>
                                                    <option value="675">Papua New Guinea +675
                                                    </option>
                                                    <option value="595">Paraguay +595
                                                    </option>
                                                    <option value="51">Peru +51
                                                    </option>
                                                    <option value="63">Philippines +63
                                                    </option>
                                                    <option value="48">Poland +48
                                                    </option>
                                                    <option value="351">Portugal +351
                                                    </option>
                                                    <option value="1787">Puerto Rico +1787
                                                    </option>
                                                    <option value="974">Qatar +974
                                                    </option>
                                                    <option value="262">Reunion +262
                                                    </option>
                                                    <option value="40">Romania +40
                                                    </option>
                                                    <option value="7">Russia +7
                                                    </option>
                                                    <option value="250">Rwanda +250
                                                    </option>
                                                    <option value="378">San Marino +378
                                                    </option>
                                                    <option value="239">Sao Tome &amp; Principe +239
                                                    </option>
                                                    <option value="966">Saudi Arabia +966
                                                    </option>
                                                    <option value="221">Senegal +221
                                                    </option>
                                                    <option value="381">Serbia +381
                                                    </option>
                                                    <option value="248">Seychelles +248
                                                    </option>
                                                    <option value="232">Sierra Leone +232
                                                    </option>
                                                    <option value="65">Singapore +65
                                                    </option>
                                                    <option value="421">Slovak Republic +421
                                                    </option>
                                                    <option value="386">Slovenia +386
                                                    </option>
                                                    <option value="677">Solomon Islands +677
                                                    </option>
                                                    <option value="252">Somalia +252
                                                    </option>
                                                    <option value="27">South Africa +27
                                                    </option>
                                                    <option value="34">Spain +34
                                                    </option>
                                                    <option value="94">Sri Lanka +94
                                                    </option>
                                                    <option value="290">St. Helena +290
                                                    </option>
                                                    <option value="1869">St. Kitts +1869
                                                    </option>
                                                    <option value="1758">St. Lucia +1758
                                                    </option>
                                                    <option value="249">Sudan +249
                                                    </option>
                                                    <option value="597">Suriname +597
                                                    </option>
                                                    <option value="268">Swaziland +268
                                                    </option>
                                                    <option value="46">Sweden +46
                                                    </option>
                                                    <option value="41">Switzerland +41
                                                    </option>
                                                    <option value="963">Syria +963
                                                    </option>
                                                    <option value="886">Taiwan +886
                                                    </option>
                                                    <option value="7">Tajikstan +7
                                                    </option>
                                                    <option value="66">Thailand +66
                                                    </option>
                                                    <option value="228">Togo +228
                                                    </option>
                                                    <option value="676">Tonga +676
                                                    </option>
                                                    <option value="1868">Trinidad &amp; Tobago +1868
                                                    </option>
                                                    <option value="216">Tunisia +216
                                                    </option>
                                                    <option value="90">Turkey +90
                                                    </option>
                                                    <option value="7">Turkmenistan +7
                                                    </option>
                                                    <option value="993">Turkmenistan +993
                                                    </option>
                                                    <option value="1649">Turks &amp; Caicos Islands +1649
                                                    </option>
                                                    <option value="688">Tuvalu +688
                                                    </option>
                                                    <option value="256">Uganda +256
                                                    </option>
                                                    <option value="44">UK +44
                                                    </option>
                                                    <option value="380">Ukraine +380
                                                    </option>
                                                    <option value="971">United Arab Emirates +971
                                                    </option>
                                                    <option value="598">Uruguay +598
                                                    </option>
                                                    <option value="1">USA +1
                                                    </option>
                                                    <option value="7">Uzbekistan +7
                                                    </option>
                                                    <option value="678">Vanuatu +678
                                                    </option>
                                                    <option value="379">Vatican City +379
                                                    </option>
                                                    <option value="58">Venezuela +58
                                                    </option>
                                                    <option value="84">Vietnam +84
                                                    </option>
                                                    <option value="84">Virgin Islands - British +1284
                                                    </option>
                                                    <option value="84">Virgin Islands - US +1340
                                                    </option>
                                                    <option value="681">Wallis &amp; Futuna +681
                                                    </option>
                                                    <option value="969">Yemen North +969
                                                    </option>
                                                    <option value="967">Yemen South +967
                                                    </option>
                                                    <option value="381">Yugoslavia +381
                                                    </option>
                                                    <option value="243">Zaire +243
                                                    </option>
                                                    <option value="260">Zambia +260
                                                    </option>
                                                    <option value="263">Zimbabwe +263
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <div class="form-group has-feedback">
                                                <input name="mobile" type="text" class="form-control"
                                                       placeholder="Your Mobile number" required id="phone">
                                                <div class="form-control-feedback">
                                                    <i class="icon-mobile text-muted"></i>
                                                </div>
                                                <label class="basic-error-phone validation-error-label validation-valid-label" for="basic" style="display: none;"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">

                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="styled" checked="checked">
                                                Accept <a href="#" style="color: #014bf9">terms of service</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <!--<button type="submit" class="btn btn-link"><i class="icon-arrow-left13 position-left"></i> Back to login form</button>-->
                                        <button type="submit"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right ml-10"><b><i
                                                        class="icon-gift"></i></b> Get my offer code by SMS NOW !
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /registration form -->
                @else
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3">
                            <div class="panel registration-form panel-transparent">
                                <div class="panel-body">
                                    <div class="text-center">
                                        <div class="icon-object border-success text-success"><i class="icon-gift"></i>
                                        </div>
                                        
                                        <h5 class="content-group-lg" style="color: #ffffff">Sorry! Offer limit has been reached
                                            <small class="display-block" style="color: #ffffff"> :(</small>
                                        </h5>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ url('/') }}" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-10"><b><i
                                                        class="icon-arrow-right8"></i></b> Back to home
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif    
                @include('..back-end.footer')


            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->
<script>
    $(window).load(function () {
        $("#loader").fadeOut();
        $("#mask").delay(1000).fadeOut("slow");
    });

    //Adding fixed position to header
    $(document).scroll(function () {
        if ($(document).scrollTop() >= 10000) {
            $('.navbar').addClass('navbar-fixed-top');
            $('html').addClass('has-fixed-nav');
        } else {
            $('.navbar').removeClass('navbar-fixed-top');
            $('html').removeClass('has-fixed-nav');
        }
    });

    // ---------- Header Slideshow ----------

    $(function () {
        $.vegas('slideshow', {
            backgrounds: [
                @foreach(App\Media::where('type', 'campaigns')->where('campaign_id', explode('/', url()->full())[5])->get() as $campaigns)
                {
                    src: '{{ asset('') }}/upload/campaigns/{{ $campaigns->file }}', fade: 1000
                },
                @endforeach
            ]
        })
    });


    // ---------- Flexslider Script ----------
    $('.flexslider').flexslider({
        animation: "fade",
        start: function (slider) {
            $('body').removeClass('loading');
        }
    });
    $("#username").change(function(){
        var username = $("#username").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:"post",
            url:"{{ url('validation/username') }}",
            data:{_token: CSRF_TOKEN, username:username},
            success:function(data){
                if(data > 0){
                    $('.basic-error-username').show();
                    $('#usernamevalid').val("0");
                    $('.basic-error-username').removeClass('validation-valid-label');
                    $('.basic-error-username').text("Sorry! User already exists.");
                }
                else{
                    $('.basic-error-username').show();
                    $('#usernamevalid').val("1");
                    $('.basic-error-username').addClass('validation-valid-label');
                    $('.basic-error-username').text("Success.");
                }

            }
        });
    });
    $("#email").change(function(){
        var email = $("#email").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:"post",
            url:"{{ url('validation/email') }}",
            data:{_token: CSRF_TOKEN, email:email},
            success:function(data){
                if(data > 0){
                    $('.basic-error-email').show();
                    $('#emailvalid').val("0");
                    $('.basic-error-email').removeClass('validation-valid-label');
                    $('.basic-error-email').text("Sorry! Email already exists.");
                }
                else{
                    $('.basic-error-email').show();
                    $('#emailvalid').val("1");
                    $('.basic-error-email').addClass('validation-valid-label');
                    $('.basic-error-email').text("Success.");
                }
            }
        });
    });
    $("#phone").change(function(){
        var phone = $("#phone").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type:"post",
            url:"{{ url('validation/phone') }}",
            data:{_token: CSRF_TOKEN, phone:phone},
            success:function(data){

                if(data > 0){
                    $('.basic-error-phone').show();
                    $('#phonevalid').val("0");
                    $('.basic-error-phone').removeClass('validation-valid-label');
                    $('.basic-error-phone').text("Sorry! Phone already exists.");
                }
                else{
                    $('.basic-error-phone').show();
                    $('#phonevalid').val("1");
                    $('.basic-error-phone').addClass('validation-valid-label');
                    $('.basic-error-phone').text("Success.");
                }
            }
        });
    });
</script>
</body>
</html>
