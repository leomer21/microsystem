<link rel="shortcut icon" href="{{ asset('/') }}upload/photo/faviconlogosmall.ico" type="image/x-icon" />
@if(isset($social_mobile_exist) && $social_mobile_exist == 1)
    <br><center><span style="color:red;"> Hmmm, Mobile already exist</span></center>
    <br>
    <form action="{{ url('social_signup_kit') }}" method="post" id="form">
       {{ csrf_field() }}
        <input type="hidden" name="code" id="code">
        <div class="col-md-6">
            <div class="form-group has-feedback has-feedback-left">
                <select class="select-fixed-single" data-width="100%"  required name="countrycode" id="country">
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
                    <option selected value="2">Egypt
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
        <div class="col-md-6">
            <div class="form-group has-feedback has-feedback-left">
                <input type="text" class="form-control" name="mobile" placeholder="  Mobile number" maxlength="11" onkeypress="return isNumber(event)" id="phone" >
            </div>
        </div>  
    </form>
    <br> 
    <button type="submit" class="btn btn-primary btn-block" onclick="smsLogin()" id="accountkit_signup">Sign up</button>
@else

@if(isset($successfullRegistration) &&  $successfullRegistration == 1)
    <img src="{{ asset('/') }}landing/img/true.png"></img>
    <h4 style="color:green;">Your Account has been created successfully</h4>

    <h3 style="color:green;" >Please wait while network administrator confirm your account.</h3>
    <br><br>
    <form method="POST" action="{{ url('userlogin') }}" >
        {{ csrf_field() }}
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Login </button>
        </div>
    </form>
@elseif(isset($smsConfirm) &&  $smsConfirm == 1)

@if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1 && !Session::has('Accountkit'))
     @if(isset($mobile_exist) && $mobile_exist == 1)
     <br><center><span style="color:red;"> Hmmm, Mobile already exist</span></center>
     @endif
    <br>
    <form action="{{ url('social_signup_kit') }}" method="post" id="form">
       {{ csrf_field() }}
        <input type="hidden" name="code" id="code">
        <input type="hidden" name="userid" value="{{ $u_id }}">

        <div class="col-md-6">
            <div class="form-group has-feedback has-feedback-left">
                <select class="select-fixed-single" data-width="100%"  required name="countrycode" id="country">
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
                    <option selected value="2">Egypt
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
        <div class="col-md-6">
            <div class="form-group has-feedback has-feedback-left">
                <input type="text" class="form-control" name="mobile" placeholder="  Mobile number" maxlength="11" onkeypress="return isNumber(event)" id="phone" >
            </div>
        </div>  
    </form>
    <br> 
    <button type="submit" class="btn btn-primary btn-block" onclick="smsLogin()" id="accountkit_signup">Sign up </button>
@else
    <center>
    <img src="{{ asset('/') }}landing/img/true.png"></img>
    <h4 style="color:green;">Please check your mobile to get SMS verification code</h4>
    </center>

    <form method="POST" action="{{ url('confirm') }}" >
        {{ csrf_field() }}
        <div class="form-group has-feedback has-feedback-left">
            <input name="sms_code" style="box-shadow: 0 0 3px #6962ff; margin: 3px" type="text" class="form-control" placeholder="  Confirm code">
            <input name="user_id" type="hidden" value="{{ $u_id }}">
        </div>
        <a  class="help-block text-danger" style="color:black" href="{{ url('showcode/'.$u_id) }}">Send Confirmation SMS Code</a>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Confirm </button>
        </div>
    </form>
    <form action="/">
        <div class="form-group">
            <button type="submit" class="btn  btn-block"> Cancel...! </button>
        </div>
    </form>
@endif    

@elseif(isset($confirm_error) &&  $confirm_error == 1)
    <h4 style="color:red;">Error in Confirmation Code</h4>
    <form method="POST" action="{{ url('confirm') }}" >
        {{ csrf_field() }}
        <div class="form-group has-feedback has-feedback-left">
            <input name="sms_code" style="box-shadow: 0 0 3px #6962ff; margin: 3px" type="text" class="form-control" placeholder="  Confirm code">
            <input name="user_id" type="hidden" value="{{ $u_id }}">
        </div>
        <a  class="help-block text-danger" style="color:black" href="{{ url('showcode/'.$u_id) }}">Send Confirmation SMS Code</a>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Confirm </button>
        </div>
    </form>
    <form action="/">
        <div class="form-group">
            <button type="submit" class="btn  btn-block"> Cancel...! </button>
        </div>
    </form>

@elseif(isset($contact_system_administrator) &&  $contact_system_administrator == 1)
    <center><h4 style="color:red;"> Please Contact System Administrator <br> {{ App\Settings::where('type','phone')->value('value') }} <br> {{ App\Settings::where('type','email')->value('value') }} </h4></center>

@elseif(isset($send_code) &&  $send_code == 1)
    <form method="POST" action="{{ url('send_code') }}" >
        {{ csrf_field() }}
        <div class="form-group has-feedback has-feedback-left">
            <center>
            <select class="form-control select-fixed-single" name="countrycode">
                <option value="44">UK +44
                </option><option value="1">USA +1
                </option><option value="213">Algeria +213
                </option><option value="376">Andorra +376
                </option><option value="244">Angola +244
                </option><option value="1264">Anguilla +1264
                </option><option value="1268">Antigua &amp; Barbuda +1268
                </option><option value="599">Antilles Dutch +599
                </option><option value="54">Argentina +54
                </option><option value="374">Armenia +374
                </option><option value="297">Aruba +297
                </option><option value="247">Ascension Island +247
                </option><option value="61">Australia +61
                </option><option value="43">Austria +43
                </option><option value="994">Azerbaijan +994
                </option><option value="1242">Bahamas +1242
                </option><option value="973">Bahrain +973
                </option><option value="880">Bangladesh +880
                </option><option value="1246">Barbados +1246
                </option><option value="375">Belarus +375
                </option><option value="32">Belgium +32
                </option><option value="501">Belize +501
                </option><option value="229">Benin +229
                </option><option value="1441">Bermuda +1441
                </option><option value="975">Bhutan +975
                </option><option value="591">Bolivia +591
                </option><option value="387">Bosnia Herzegovina +387
                </option><option value="267">Botswana +267
                </option><option value="55">Brazil +55
                </option><option value="673">Brunei +673
                </option><option value="359">Bulgaria +359
                </option><option value="226">Burkina Faso +226
                </option><option value="257">Burundi +257
                </option><option value="855">Cambodia +855
                </option><option value="237">Cameroon +237
                </option><option value="1">Canada +1
                </option><option value="238">Cape Verde Islands +238
                </option><option value="1345">Cayman Islands +1345
                </option><option value="236">Central African Republic +236
                </option><option value="56">Chile +56
                </option><option value="86">China +86
                </option><option value="57">Colombia +57
                </option><option value="269">Comoros +269
                </option><option value="242">Congo +242
                </option><option value="682">Cook Islands +682
                </option><option value="506">Costa Rica +506
                </option><option value="385">Croatia +385
                </option><option value="53">Cuba +53
                </option><option value="90392">Cyprus North +90392
                </option><option value="357">Cyprus South +357
                </option><option value="42">Czech Republic +42
                </option><option value="45">Denmark +45
                </option><option value="2463">Diego Garcia +2463
                </option><option value="253">Djibouti +253
                </option><option value="1809">Dominica +1809
                </option><option value="1809">Dominican Republic +1809
                </option><option value="593">Ecuador +593
                </option><option selected="" value="2">Egypt
                </option><option value="353">Eire +353
                </option><option value="503">El Salvador +503
                </option><option value="240">Equatorial Guinea +240
                </option><option value="291">Eritrea +291
                </option><option value="372">Estonia +372
                </option><option value="251">Ethiopia +251
                </option><option value="500">Falkland Islands +500
                </option><option value="298">Faroe Islands +298
                </option><option value="679">Fiji +679
                </option><option value="358">Finland +358
                </option><option value="33">France +33
                </option><option value="594">French Guiana +594
                </option><option value="689">French Polynesia +689
                </option><option value="241">Gabon +241
                </option><option value="220">Gambia +220
                </option><option value="7880">Georgia +7880
                </option><option value="49">Germany +49
                </option><option value="233">Ghana +233
                </option><option value="350">Gibraltar +350
                </option><option value="30">Greece +30
                </option><option value="299">Greenland +299
                </option><option value="1473">Grenada +1473
                </option><option value="590">Guadeloupe +590
                </option><option value="671">Guam +671
                </option><option value="502">Guatemala +502
                </option><option value="224">Guinea +224
                </option><option value="245">Guinea - Bissau +245
                </option><option value="592">Guyana +592
                </option><option value="509">Haiti +509
                </option><option value="504">Honduras +504
                </option><option value="852">Hong Kong +852
                </option><option value="36">Hungary +36
                </option><option value="354">Iceland +354
                </option><option value="91">India +91
                </option><option value="62">Indonesia +62
                </option><option value="98">Iran +98
                </option><option value="964">Iraq +964
                </option><option value="972">Israel +972
                </option><option value="39">Italy +39
                </option><option value="225">Ivory Coast +225
                </option><option value="1876">Jamaica +1876
                </option><option value="81">Japan +81
                </option><option value="962">Jordan +962
                </option><option value="7">Kazakhstan +7
                </option><option value="254">Kenya +254
                </option><option value="686">Kiribati +686
                </option><option value="850">Korea North +850
                </option><option value="82">Korea South +82
                </option><option value="965">Kuwait +965
                </option><option value="996">Kyrgyzstan +996
                </option><option value="856">Laos +856
                </option><option value="371">Latvia +371
                </option><option value="961">Lebanon +961
                </option><option value="266">Lesotho +266
                </option><option value="231">Liberia +231
                </option><option value="218">Libya +218
                </option><option value="417">Liechtenstein +417
                </option><option value="370">Lithuania +370
                </option><option value="352">Luxembourg +352
                </option><option value="853">Macao +853
                </option><option value="389">Macedonia +389
                </option><option value="261">Madagascar +261
                </option><option value="265">Malawi +265
                </option><option value="60">Malaysia +60
                </option><option value="960">Maldives +960
                </option><option value="223">Mali +223
                </option><option value="356">Malta +356
                </option><option value="692">Marshall Islands +692
                </option><option value="596">Martinique +596
                </option><option value="222">Mauritania +222
                </option><option value="269">Mayotte +269
                </option><option value="52">Mexico +52
                </option><option value="691">Micronesia +691
                </option><option value="373">Moldova +373
                </option><option value="377">Monaco +377
                </option><option value="976">Mongolia +976
                </option><option value="1664">Montserrat +1664
                </option><option value="212">Morocco +212
                </option><option value="258">Mozambique +258
                </option><option value="95">Myanmar +95
                </option><option value="264">Namibia +264
                </option><option value="674">Nauru +674
                </option><option value="977">Nepal +977
                </option><option value="31">Netherlands +31
                </option><option value="687">New Caledonia +687
                </option><option value="64">New Zealand +64
                </option><option value="505">Nicaragua +505
                </option><option value="227">Niger +227
                </option><option value="234">Nigeria +234
                </option><option value="683">Niue +683
                </option><option value="672">Norfolk Islands +672
                </option><option value="670">Northern Marianas +670
                </option><option value="47">Norway +47
                </option><option value="968">Oman +968
                </option><option value="680">Palau +680
                </option><option value="507">Panama +507
                </option><option value="675">Papua New Guinea +675
                </option><option value="595">Paraguay +595
                </option><option value="51">Peru +51
                </option><option value="63">Philippines +63
                </option><option value="48">Poland +48
                </option><option value="351">Portugal +351
                </option><option value="1787">Puerto Rico +1787
                </option><option value="974">Qatar +974
                </option><option value="262">Reunion +262
                </option><option value="40">Romania +40
                </option><option value="7">Russia +7
                </option><option value="250">Rwanda +250
                </option><option value="378">San Marino +378
                </option><option value="239">Sao Tome &amp; Principe +239
                </option><option value="966">Saudi Arabia +966
                </option><option value="221">Senegal +221
                </option><option value="381">Serbia +381
                </option><option value="248">Seychelles +248
                </option><option value="232">Sierra Leone +232
                </option><option value="65">Singapore +65
                </option><option value="421">Slovak Republic +421
                </option><option value="386">Slovenia +386
                </option><option value="677">Solomon Islands +677
                </option><option value="252">Somalia +252
                </option><option value="27">South Africa +27
                </option><option value="34">Spain +34
                </option><option value="94">Sri Lanka +94
                </option><option value="290">St. Helena +290
                </option><option value="1869">St. Kitts +1869
                </option><option value="1758">St. Lucia +1758
                </option><option value="249">Sudan +249
                </option><option value="597">Suriname +597
                </option><option value="268">Swaziland +268
                </option><option value="46">Sweden +46
                </option><option value="41">Switzerland +41
                </option><option value="963">Syria +963
                </option><option value="886">Taiwan +886
                </option><option value="7">Tajikstan +7
                </option><option value="66">Thailand +66
                </option><option value="228">Togo +228
                </option><option value="676">Tonga +676
                </option><option value="1868">Trinidad &amp; Tobago +1868
                </option><option value="216">Tunisia +216
                </option><option value="90">Turkey +90
                </option><option value="7">Turkmenistan +7
                </option><option value="993">Turkmenistan +993
                </option><option value="1649">Turks &amp; Caicos Islands +1649
                </option><option value="688">Tuvalu +688
                </option><option value="256">Uganda +256
                </option><option value="44">UK +44
                </option><option value="380">Ukraine +380
                </option><option value="971">United Arab Emirates +971
                </option><option value="598">Uruguay +598
                </option><option value="1">USA +1
                </option><option value="7">Uzbekistan +7
                </option><option value="678">Vanuatu +678
                </option><option value="379">Vatican City +379
                </option><option value="58">Venezuela +58
                </option><option value="84">Vietnam +84
                </option><option value="84">Virgin Islands - British +1284
                </option><option value="84">Virgin Islands - US +1340
                </option><option value="681">Wallis &amp; Futuna +681
                </option><option value="969">Yemen North +969
                </option><option value="967">Yemen South +967
                </option><option value="381">Yugoslavia +381
                </option><option value="243">Zaire +243
                </option><option value="260">Zambia +260
                </option><option value="263">Zimbabwe +263
                </option></select>
            </select>
            </center>
        </div>
        <div class="form-group has-feedback has-feedback-left">
            <input name="phone_code" style="box-shadow: 0 0 3px #6962ff; margin: 3px" type="text" class="form-control" placeholder="  Your mobile number">
            <input name="user_id" type="hidden" value="{{ $u_id }}">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Send </button>
        </div>
        <a  class="help-block" style="color: black; text-align: left;" href="{{ url('/') }}">Login >></a>
    </form>
    <form action="/">
        <div class="form-group">
            <button type="submit" class="btn  btn-block"> Cancel...! </button>
        </div>
    </form>

@elseif(isset($forget) &&  $forget == 1)
    
    <form method="POST" action="{{ url('forget_password') }}" >
        {{ csrf_field() }}
        <p>Please enter your email to receive activation code.</p>
        <div class="form-group has-feedback has-feedback-left">
            <input style="box-shadow: 0 0 3px #6962ff; margin: 3px" name="email" type="text" class="form-control" placeholder="  Email">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Send </button>
        </div>
        <a  class="help-block" style="color: black; text-align: left;" href="{{ url('/') }}">Login >></a> 
    </form>

@elseif(isset($reset) &&  $reset == 1 && isset($token))
    
    <form method="POST" action="{{ url('reset') }}" >
        {{ csrf_field() }}
        <p>Your password has been reset, Please enter new password.</p>
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group has-feedback has-feedback-left">
            <input style="box-shadow: 0 0 3px #6962ff; margin: 3px" name="password" type="password" class="form-control" placeholder="  Enter new password">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Login </button>
        </div>
    </form>

@elseif(isset($mailsend) &&  $mailsend == 0)

    <center><h4 style="color:red;">Error in your email address </h4></center>
    <form method="POST" action="{{ url('forget_password') }}" >
        {{ csrf_field() }}
        <p>Please Enter your email.</p>
        <div class="form-group has-feedback has-feedback-left">
            <input style="box-shadow: 0 0 3px #6962ff; margin: 3px" name="email" type="text" class="form-control" placeholder="  Email">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Send </button>
        </div>
        <a  class="help-block" style="color: black; text-align: left;" href="{{ url('/') }}">Login</a>
    </form>

@elseif(isset($mailsend) &&  $mailsend == 1)
    <center>
        <img src="{{ asset('/') }}landing/img/true.png"></img>
        <h4 style="color:green;">Plases check your email address then click on reset password button to change your passwords.</h4>
        <a  class="help-block" style="color: black; text-align: left;" href="{{ url('/') }}">Login</a>
    </center>
@else
<div class="panel-body">

    <div class="tabbable">
        <ul class="nav nav-tabs nav-tabs-bottom bottom-divided nav-justified nav-tabs-icon">
            @if(App\Settings::where('type', 'disableLogin')->value('state')!=1)
            <li  @if( !Session::has('Accountkit') and !isset($social_mobile_exist)  and !isset($mobile_exist) and App\Settings::where('type', 'signupDefault')->value('state')!=1 ) class="active" @endif><a href="#tab1" data-toggle="tab"> Sign in</a></li>
            @endif
            <li  @if( (Session::has('Accountkit')) or (isset($social_mobile_exist) && $social_mobile_exist == 1) or (isset($mobile_exist) &&  $mobile_exist == 1) or (App\Settings::where('type', 'signupDefault')->value('state')==1) )  class="active" @endif><a href="#tab2" data-toggle="tab"> @if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1) <i class="icon-wifi"></i> Access to WiFi  @else <i class="icon-wifi"></i> WiFi Registration @endif  </a></li>
            
        </ul>
        <div class="tab-content">
            @if(!Session::has('login')) 
                @if(App\Settings::where('type', 'disableLogin')->value('state')!=1)
                <div @if( (Session::has('Accountkit')) or (isset($social_mobile_exist) && $social_mobile_exist == 1) or (isset($mobile_exist) &&  $mobile_exist == 1) or (App\Settings::where('type', 'signupDefault')->value('state')==1) ) class="tab-pane" @else  class="tab-pane active" @endif id="tab1">

                    <form method="POST" action="{{ url('userlogin') }}">
                        {{ csrf_field() }}
                        <br>
                        @if(isset($errorMessage))<center><span style="color:red;" class="help-block text-danger">  {{ $errorMessage }} </span> </center>@endif

                        <div class="form-group has-feedback has-feedback-left">
                            <input name="username" style="box-shadow: 0 0 3px #6962ff; margin: 3px" value="{{Request::old('username')}}" class="form-control" style="" @if(App\Settings::where('type', 'getUserName')->value('state') !=1) type="text" placeholder="  Username" @else placeholder="  Mobile number" type="number" @endif>
                            
                            <!--<span class="help-block text-danger"></span>-->
                        </div>
                        @if(App\Settings::where('type', 'getPassword')->value('state')==1)
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="password" style="box-shadow: 0 0 3px #6962ff; margin: 3px" value="{{Request::old('password')}}" type="password" class="form-control" placeholder="  Password">
                            <!--<span class="help-block text-danger"> </span>-->
                        </div>
                        @endif
                        <a  class="help-block" style="color: black; text-align: left;" href="{{ url('forget_modal') }}">Forgot password ?</a>
                        <div class="form-group">
                            <button id="login" type="submit" name="submit" class="btn btn-primary btn-block">Sign in </button>
                        </div>

                        <!--<div class="text-center">
                            <a href="">Forgot password?</a>
                        </div>-->

                    </form>
                </div>  
                @endif
            @else
            <meta http-equiv="refresh" content="0; url=/account" />
            @endif
            
            <div @if( (Session::has('Accountkit')) or (isset($social_mobile_exist) && $social_mobile_exist == 1) or (isset($mobile_exist) &&  $mobile_exist == 1) or (App\Settings::where('type', 'signupDefault')->value('state')==1) or (App\Settings::where('type', 'disableLogin')->value('state')==1) ) class="tab-pane active" @else class="tab-pane" @endif id="tab2">
            

                <?php //@if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1 && !Session::has('Accountkit')) ?>
                @if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1 && !Session::has('Accountkit') && !Session::has('AccountkitFullMobile') && ( !isset($_GET['status']) and !isset($_GET['code']) ) )

                    @if(isset($mobile_exist) && $mobile_exist == 1)
                    <br><center><span style="color:red;"> Hmmm, Mobile already exist</span></center>
                    @endif
                    <br>
                    <!-- <form action="{{ url('signup_kit') }}" method="post" id="form"> -->
                    <form class="signupForm" action="https://www.accountkit.com/v1.0/basic/dialog/sms_login/" method="get" id="form">
                        {{ csrf_field() }}
                        <input type="hidden" name="code" id="code">
                        <div class="col-md-12">
                            <center>
                            <div class="input-group">
                                
                                <!-- <select class="select-fixed-single" data-width="100%"  required name="countrycode" id="country"> -->
                                <select class="select-fixed-single" data-width="100%" required name="country_code" id="country">
                                    <?php $systemCountry=App\Settings::where('type', 'country')->value('value'); ?>
                                    <option @if($systemCountry=="Saudi Arabia") selected @endif value="+966">Saudi Arabia +966</option>
                                    <option @if($systemCountry=="United Arab Emirates") selected @endif value="971">United Arab Emirates +971</option>
                                    <option @if($systemCountry=="Qatar") selected @endif value="+974">Qatar +974</option>
                                    <option @if($systemCountry=="Iraq") selected @endif value="+964">Iraq +964</option>
                                    <option @if($systemCountry=="Kuwait") selected @endif value="+965">Kuwait +965</option>
                                    <option @if($systemCountry=="Lebanon") selected @endif value="+961">Lebanon +961</option>
                                    <option @if($systemCountry=="Jordan") selected @endif value="+962">Jordan +962</option>
                                    <option @if($systemCountry=="Egypt") selected @endif value="+20">Egypt +2</option>
                                    <option @if($systemCountry=="Gambia") selected @endif value="+220">Gambia +220</option>
                                    <option value="+44">UK +44</option>
                                    <option value="+1">USA +1</option>
                                    <option value="+213">Algeria +213</option>
                                    <option value="+376">Andorra +376</option>
                                    <option value="+244">Angola +244</option>
                                    <option value="+1264">Anguilla +1264</option>
                                    <option value="+1268">Antigua &amp; Barbuda +1268</option>
                                    <option value="+599">Antilles Dutch +599</option>
                                    <option value="+54">Argentina +54</option>
                                    <option value="+374">Armenia +374</option>
                                    <option value="+297">Aruba +297</option>
                                    <option value="+247">Ascension Island +247</option>
                                    <option value="+61">Australia +61</option>
                                    <option value="+43">Austria +43</option>
                                    <option value="+994">Azerbaijan +994</option>
                                    <option value="+1242">Bahamas +1242</option>
                                    <option value="+973">Bahrain +973</option>
                                    <option value="+880">Bangladesh +880</option>
                                    <option value="+1246">Barbados +1246</option>
                                    <option value="+375">Belarus +375</option>
                                    <option value="+32">Belgium +32</option>
                                    <option value="+501">Belize +501</option>
                                    <option value="+229">Benin +229</option>
                                    <option value="+1441">Bermuda +1441</option>
                                    <option value="+975">Bhutan +975</option>
                                    <option value="+591">Bolivia +591</option>
                                    <option value="+387">Bosnia Herzegovina +387</option>
                                    <option value="+267">Botswana +267</option>
                                    <option value="+55">Brazil +55</option>
                                    <option value="+673">Brunei +673</option>
                                    <option value="+359">Bulgaria +359</option>
                                    <option value="+226">Burkina Faso +226</option>
                                    <option value="+257">Burundi +257</option>
                                    <option value="+855">Cambodia +855</option>
                                    <option value="+237">Cameroon +237</option>
                                    <option value="+1">Canada +1</option>
                                    <option value="+238">Cape Verde Islands +238</option>
                                    <option value="+1345">Cayman Islands +1345</option>
                                    <option value="+236">Central African Republic +236</option>
                                    <option value="+56">Chile +56</option>
                                    <option value="+86">China +86</option>
                                    <option value="+57">Colombia +57</option>
                                    <option value="+269">Comoros +269</option>
                                    <option value="+242">Congo +242</option>
                                    <option value="+682">Cook Islands +682</option>
                                    <option value="+506">Costa Rica +506</option>
                                    <option value="+385">Croatia +385</option>
                                    <option value="+53">Cuba +53</option>
                                    <option value="+90392">Cyprus North +90392</option>
                                    <option value="+357">Cyprus South +357</option>
                                    <option value="+42">Czech Republic +42</option>
                                    <option value="+45">Denmark +45</option>
                                    <option value="+2463">Diego Garcia +2463</option>
                                    <option @if($systemCountry=="Djibouti") selected @endif value="+253">Djibouti +253</option>
                                    <option value="+1809">Dominica +1809</option>
                                    <option value="+1809">Dominican Republic +1809</option>
                                    <option value="+593">Ecuador +593</option>
                                    <option value="+353">Eire +353</option>
                                    <option value="+503">El Salvador +503</option>
                                    <option value="+240">Equatorial Guinea +240</option>
                                    <option value="+291">Eritrea +291</option>
                                    <option value="+372">Estonia +372</option>
                                    <option value="+251">Ethiopia +251</option>
                                    <option value="+500">Falkland Islands +500</option>
                                    <option value="+298">Faroe Islands +298</option>
                                    <option value="+679">Fiji +679</option>
                                    <option value="+358">Finland +358</option>
                                    <option value="+33">France +33</option>
                                    <option value="+594">French Guiana +594</option>
                                    <option value="+689">French Polynesia +689</option>
                                    <option value="+241">Gabon +241</option>
                                    <option value="+7880">Georgia +7880</option>
                                    <option value="+49">Germany +49</option>
                                    <option value="+233">Ghana +233</option>
                                    <option value="+350">Gibraltar +350</option>
                                    <option value="+30">Greece +30</option>
                                    <option value="+299">Greenland +299</option>
                                    <option value="+1473">Grenada +1473</option>
                                    <option value="+590">Guadeloupe +590</option>
                                    <option value="+671">Guam +671</option>
                                    <option value="+502">Guatemala +502</option>
                                    <option value="+224">Guinea +224</option>
                                    <option value="+245">Guinea - Bissau +245</option>
                                    <option value="+592">Guyana +592</option>
                                    <option value="+509">Haiti +509</option>
                                    <option value="+504">Honduras +504</option>
                                    <option value="+852">Hong Kong +852</option>
                                    <option value="+36">Hungary +36</option>
                                    <option value="+354">Iceland +354</option>
                                    <option value="+91">India +91</option>
                                    <option value="+62">Indonesia +62</option>
                                    <option value="+98">Iran +98</option>
                                    <option value="+964">Iraq +964</option>
                                    <option value="+972">Israel +972</option>
                                    <option value="+39">Italy +39</option>
                                    <option value="+225">Ivory Coast +225</option>
                                    <option value="+1876">Jamaica +1876</option>
                                    <option value="+81">Japan +81</option>
                                    <option value="+962">Jordan +962</option>
                                    <option value="+7">Kazakhstan +7</option>
                                    <option value="+254">Kenya +254</option>
                                    <option value="+686">Kiribati +686</option>
                                    <option value="+850">Korea North +850</option>
                                    <option value="+82">Korea South +82</option>
                                    <option value="+965">Kuwait +965</option>
                                    <option value="+996">Kyrgyzstan +996</option>
                                    <option value="+856">Laos +856</option>
                                    <option value="+371">Latvia +371</option>
                                    <option value="+961">Lebanon +961</option>
                                    <option value="+266">Lesotho +266</option>
                                    <option value="+231">Liberia +231</option>
                                    <option value="+218">Libya +218</option>
                                    <option value="+417">Liechtenstein +417</option>
                                    <option value="+370">Lithuania +370</option>
                                    <option value="+352">Luxembourg +352</option>
                                    <option value="+853">Macao +853</option>
                                    <option value="+389">Macedonia +389</option>
                                    <option value="+261">Madagascar +261</option>
                                    <option value="+265">Malawi +265</option>
                                    <option value="+60">Malaysia +60</option>
                                    <option value="+960">Maldives +960</option>
                                    <option value="+223">Mali +223</option>
                                    <option value="+356">Malta +356</option>
                                    <option value="+692">Marshall Islands +692</option>
                                    <option value="+596">Martinique +596</option>
                                    <option value="+222">Mauritania +222</option>
                                    <option value="+269">Mayotte +269</option>
                                    <option value="+52">Mexico +52</option>
                                    <option value="+691">Micronesia +691</option>
                                    <option value="+373">Moldova +373</option>
                                    <option value="+377">Monaco +377</option>
                                    <option value="+976">Mongolia +976</option>
                                    <option value="+1664">Montserrat +1664</option>
                                    <option value="+212">Morocco +212</option>
                                    <option value="+258">Mozambique +258</option>
                                    <option value="+95">Myanmar +95</option>
                                    <option value="+264">Namibia +264</option>
                                    <option value="+674">Nauru +674</option>
                                    <option value="+977">Nepal +977</option>
                                    <option value="+31">Netherlands +31</option>
                                    <option value="+687">New Caledonia +687</option>
                                    <option value="+64">New Zealand +64</option>
                                    <option value="+505">Nicaragua +505</option>
                                    <option value="+227">Niger +227</option>
                                    <option value="+234">Nigeria +234</option>
                                    <option value="+683">Niue +683</option>
                                    <option value="+672">Norfolk Islands +672</option>
                                    <option value="+670">Northern Marianas +670</option>
                                    <option value="+47">Norway +47</option>
                                    <option value="+968">Oman +968</option>
                                    <option value="+680">Palau +680</option>
                                    <option value="+507">Panama +507</option>
                                    <option value="+675">Papua New Guinea +675</option>
                                    <option value="+595">Paraguay +595</option>
                                    <option value="+51">Peru +51</option>
                                    <option value="+63">Philippines +63</option>
                                    <option value="+48">Poland +48</option>
                                    <option value="+351">Portugal +351</option>
                                    <option value="+1787">Puerto Rico +1787</option>
                                    <option value="+974">Qatar +974</option>
                                    <option value="+262">Reunion +262</option>
                                    <option value="+40">Romania +40</option>
                                    <option value="+7">Russia +7</option>
                                    <option value="+250">Rwanda +250</option>
                                    <option value="+378">San Marino +378</option>
                                    <option value="+239">Sao Tome &amp; Principe +239</option>
                                    <option value="+966">Saudi Arabia +966</option>
                                    <option value="+221">Senegal +221</option>
                                    <option value="+381">Serbia +381</option>
                                    <option value="+248">Seychelles +248</option>
                                    <option value="+232">Sierra Leone +232</option>
                                    <option value="+65">Singapore +65</option>
                                    <option value="+421">Slovak Republic +421</option>
                                    <option value="+386">Slovenia +386</option>
                                    <option value="+677">Solomon Islands +677</option>
                                    <option value="+252">Somalia +252</option>
                                    <option value="+27">South Africa +27</option>
                                    <option value="+34">Spain +34</option>
                                    <option value="+94">Sri Lanka +94</option>
                                    <option value="+290">St. Helena +290</option>
                                    <option value="+1869">St. Kitts +1869</option>
                                    <option value="+1758">St. Lucia +1758</option>
                                    <option value="+249">Sudan +249</option>
                                    <option value="+597">Suriname +597</option>
                                    <option value="+268">Swaziland +268</option>
                                    <option value="+46">Sweden +46</option>
                                    <option value="+41">Switzerland +41</option>
                                    <option value="+963">Syria +963</option>
                                    <option value="+886">Taiwan +886</option>
                                    <option value="+7">Tajikstan +7</option>
                                    <option value="+66">Thailand +66</option>
                                    <option value="+228">Togo +228</option>
                                    <option value="+676">Tonga +676</option>
                                    <option value="+1868">Trinidad &amp; Tobag</option>
                                    <option value="+216">Tunisia +216</option>
                                    <option value="+90">Turkey +90</option>
                                    <option value="+7">Turkmenistan +7</option>
                                    <option value="+993">Turkmenistan +993</option>
                                    <option value="+1649">Turks &amp; Caicos Islands +1649</option>
                                    <option value="+688">Tuvalu +688</option>
                                    <option value="+256">Uganda +256</option>
                                    <option value="+44">UK +44</option>
                                    <option value="+380">Ukraine +380</option>
                                    <option value="+971">United Arab Emirates +971</option>
                                    <option value="+598">Uruguay +598</option>
                                    <option value="+1">USA +1</option>
                                    <option value="+7">Uzbekistan +7</option>
                                    <option value="+678">Vanuatu +678</option>
                                    <option value="+379">Vatican City +379</option>
                                    <option value="+58">Venezuela +58</option>
                                    <option value="+84">Vietnam +84</option>
                                    <option value="+1284">Virgin Islands - British +1284</option>
                                    <option value="+1340">Virgin Islands - US +1340</option>
                                    <option value="+681">Wallis &amp; Futuna +681</option>
                                    <option value="+969">Yemen North +969</option>
                                    <option value="+967">Yemen South +967</option>
                                    <option value="+381">Yugoslavia +381</option>
                                    <option value="+243">Zaire +243</option>
                                    <option value="+260">Zambia +260</option>
                                    <option value="+263">Zimbabwe +263</option>
                                </select>
                            </div>
                            </center>
                        </div>	
                        <div class="col-md-12">
                            <div class="form-group has-feedback has-feedback-left">
                                <!-- <input type="number" style="box-shadow: 0 0 3px #6962ff; margin: 3px" class="form-control" name="mobile" placeholder="  Mobile" id="phone"> -->
                                <input type="number" style="box-shadow: 0 0 2px #6962ff; margin: 5px; padding-left:5px" class="form-control" name="phone_number" placeholder=" Mobile number" id="phone">
                            </div>
                        </div>
                          
                        <!-- NEW AccountKit without Javascript -->
                        <input type="hidden" name="debug" value="true">
                        <input type="hidden" name="app_id" value="{{ App\Settings::where('type', 'Accountkitappid')->value('value') }}">
                        <?php 
                        $subdomain = url()->full();
                        $split = explode('/', $subdomain);
                        ?>
                        <input type="hidden" name="redirect" value="http://{{ $split[2] }}/indexIframe">
                        <input type="hidden" name="state" value="{{ csrf_token() }}">
                        <input type="hidden" name="fbAppEventsEnabled" value=true>
                        <button type="submit" style="color: white" class="btn btn-danger btn-rounded btn-block">Next <i class="icon-arrow-right"></i> </button>     
                        <!-- NEW AccountKit without Javascript -->
                    </form>
                    
                    <!-- <button type="submit" class="btn btn-primary btn-block" onclick="smsLogin()" id="accountkit_signup">Sign up </button> -->
                @else
                <form method="POST" action="{{ url('signup') }}" class="signup">
                    {{ csrf_field() }}
                    @if(App\Settings::where('type', 'Accountkitappid')->value('state') == 1)
                    <?php //<input type="hidden" name="phone" value="{{ session('Accountkit')[0] }}"> ?>
                    <input type="hidden" name="phone" value="{{ session('AccountkitFullMobile')[0] }}">
                    <input type="hidden" name="accountkit" value="1">
                    <br>
                    <center><h2 style='color: green;'>Your Mobile has been confirmed,</h2><h4>Please enter this informations to complete registration. </h4></center>	
                    <br>
                    @endif	
                    
                    @if(App\Settings::where('type', 'getNetwork')->value('state')==1)
                        <?php
                        $networks = App\Network::where('state',1)->get();
                        ?>
                        <center>
                        <div class="input-group">
                            <!--<label class="col-lg-4 text-blue">Network Name</label>-->
                            <select style="box-shadow: 0 0 3px #6962ff; margin: 3px" class="form-control select-fixed-single" name="network">
                                @foreach ($networks as $network)
                                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        </center>
                        <br>
                    @endif

                    @if(App\Settings::where('type', 'getGender')->value('state')==1)
                        <div class="form-group has-feedback has-feedback-left">
                            <center><i class="icon-man-woman"></i>   
                            <select style="box-shadow: 0 0 3px #6962ff; margin: 3px; max-inline-size: 120px" class="form-control select-fixed-single" name="gender" required>
                                <option value="1">  Male<i class="icon-man"></i> </option>
                                <option value="0"> Female<i class="icon-woman"> </i> </option>
                            </select>
                            </center>
                            
                        </div>
                    @endif

                    @if(App\Settings::where('type', 'getName')->value('state')==1)
                        <div class="form-group has-feedback has-feedback-left">
                            <input name="name" style="box-shadow: 0 0 3px #6962ff; margin: 3px; padding-left:5px" type="text" class="form-control" placeholder="  Full Name" required="required" aria-required="true">
                            
                            <!--<span class="help-block text-danger"> This username is already taken</span>-->
                        </div>
                    @endif
                    

                    @if(App\Settings::where('type', 'Accountkitappid')->value('state') != 1)	
                        <center>
                        <div class="input-group">
                            
                            <select class="form-control select-fixed-single" style="box-shadow: 0 0 3px #6962ff; margin: 3px" name="countrycode" id="countrycode">
                            <?php $systemCountry=App\Settings::where('type', 'country')->value('value'); ?>
                            <option @if($systemCountry=="Saudi Arabia") selected @endif value="966">Saudi Arabia +966
                                </option>
                                <option @if($systemCountry=="United Arab Emirates") selected @endif value="971">United Arab Emirates +971
                                </option>
                                <option @if($systemCountry=="Qatar") selected @endif value="974">Qatar +974
                                </option>
                                <option @if($systemCountry=="Iraq") selected @endif value="964">Iraq +964
                                </option>
                                <option @if($systemCountry=="Kuwait") selected @endif value="965">Kuwait +965
                                </option>
                                <option @if($systemCountry=="Lebanon") selected @endif value="961">Lebanon +961
                                </option>
                                <option @if($systemCountry=="Jordan") selected @endif value="962">Jordan +962
                                </option>
                                <option @if($systemCountry=="Egypt") selected @endif value="2">Egypt +2
                                </option>
                                <option @if($systemCountry=="Gambia") selected @endif value="220">Gambia +220
                                </option>
                                <option value="44">UK +44
                                </option><option value="1">USA +1
                                </option><option value="213">Algeria +213
                                </option><option value="376">Andorra +376
                                </option><option value="244">Angola +244
                                </option><option value="1264">Anguilla +1264
                                </option><option value="1268">Antigua &amp; Barbuda +1268
                                </option><option value="599">Antilles Dutch +599
                                </option><option value="54">Argentina +54
                                </option><option value="374">Armenia +374
                                </option><option value="297">Aruba +297
                                </option><option value="247">Ascension Island +247
                                </option><option value="61">Australia +61
                                </option><option value="43">Austria +43
                                </option><option value="994">Azerbaijan +994
                                </option><option value="1242">Bahamas +1242
                                </option><option value="973">Bahrain +973
                                </option><option value="880">Bangladesh +880
                                </option><option value="1246">Barbados +1246
                                </option><option value="375">Belarus +375
                                </option><option value="32">Belgium +32
                                </option><option value="501">Belize +501
                                </option><option value="229">Benin +229
                                </option><option value="1441">Bermuda +1441
                                </option><option value="975">Bhutan +975
                                </option><option value="591">Bolivia +591
                                </option><option value="387">Bosnia Herzegovina +387
                                </option><option value="267">Botswana +267
                                </option><option value="55">Brazil +55
                                </option><option value="673">Brunei +673
                                </option><option value="359">Bulgaria +359
                                </option><option value="226">Burkina Faso +226
                                </option><option value="257">Burundi +257
                                </option><option value="855">Cambodia +855
                                </option><option value="237">Cameroon +237
                                </option><option value="1">Canada +1
                                </option><option value="238">Cape Verde Islands +238
                                </option><option value="1345">Cayman Islands +1345
                                </option><option value="236">Central African Republic +236
                                </option><option value="56">Chile +56
                                </option><option value="86">China +86
                                </option><option value="57">Colombia +57
                                </option><option value="269">Comoros +269
                                </option><option value="242">Congo +242
                                </option><option value="682">Cook Islands +682
                                </option><option value="506">Costa Rica +506
                                </option><option value="385">Croatia +385
                                </option><option value="53">Cuba +53
                                </option><option value="90392">Cyprus North +90392
                                </option><option value="357">Cyprus South +357
                                </option><option value="42">Czech Republic +42
                                </option><option value="45">Denmark +45
                                </option><option value="2463">Diego Garcia +2463
                                </option><option @if($systemCountry=="Djibouti") selected @endif value="253">Djibouti +253
                                </option><option value="1809">Dominica +1809
                                </option><option value="1809">Dominican Republic +1809
                                </option><option value="593">Ecuador +593
                                </option><option value="353">Eire +353
                                </option><option value="503">El Salvador +503
                                </option><option value="240">Equatorial Guinea +240
                                </option><option value="291">Eritrea +291
                                </option><option value="372">Estonia +372
                                </option><option value="251">Ethiopia +251
                                </option><option value="500">Falkland Islands +500
                                </option><option value="298">Faroe Islands +298
                                </option><option value="679">Fiji +679
                                </option><option value="358">Finland +358
                                </option><option value="33">France +33
                                </option><option value="594">French Guiana +594
                                </option><option value="689">French Polynesia +689
                                </option><option value="241">Gabon +241
                                </option><option value="220">Gambia +220
                                </option><option value="7880">Georgia +7880
                                </option><option value="49">Germany +49
                                </option><option value="233">Ghana +233
                                </option><option value="350">Gibraltar +350
                                </option><option value="30">Greece +30
                                </option><option value="299">Greenland +299
                                </option><option value="1473">Grenada +1473
                                </option><option value="590">Guadeloupe +590
                                </option><option value="671">Guam +671
                                </option><option value="502">Guatemala +502
                                </option><option value="224">Guinea +224
                                </option><option value="245">Guinea - Bissau +245
                                </option><option value="592">Guyana +592
                                </option><option value="509">Haiti +509
                                </option><option value="504">Honduras +504
                                </option><option value="852">Hong Kong +852
                                </option><option value="36">Hungary +36
                                </option><option value="354">Iceland +354
                                </option><option value="91">India +91
                                </option><option value="62">Indonesia +62
                                </option><option value="98">Iran +98
                                </option><option value="964">Iraq +964
                                </option><option value="972">Israel +972
                                </option><option value="39">Italy +39
                                </option><option value="225">Ivory Coast +225
                                </option><option value="1876">Jamaica +1876
                                </option><option value="81">Japan +81
                                </option><option value="962">Jordan +962
                                </option><option value="7">Kazakhstan +7
                                </option><option value="254">Kenya +254
                                </option><option value="686">Kiribati +686
                                </option><option value="850">Korea North +850
                                </option><option value="82">Korea South +82
                                </option><option value="965">Kuwait +965
                                </option><option value="996">Kyrgyzstan +996
                                </option><option value="856">Laos +856
                                </option><option value="371">Latvia +371
                                </option><option value="961">Lebanon +961
                                </option><option value="266">Lesotho +266
                                </option><option value="231">Liberia +231
                                </option><option value="218">Libya +218
                                </option><option value="417">Liechtenstein +417
                                </option><option value="370">Lithuania +370
                                </option><option value="352">Luxembourg +352
                                </option><option value="853">Macao +853
                                </option><option value="389">Macedonia +389
                                </option><option value="261">Madagascar +261
                                </option><option value="265">Malawi +265
                                </option><option value="60">Malaysia +60
                                </option><option value="960">Maldives +960
                                </option><option value="223">Mali +223
                                </option><option value="356">Malta +356
                                </option><option value="692">Marshall Islands +692
                                </option><option value="596">Martinique +596
                                </option><option value="222">Mauritania +222
                                </option><option value="269">Mayotte +269
                                </option><option value="52">Mexico +52
                                </option><option value="691">Micronesia +691
                                </option><option value="373">Moldova +373
                                </option><option value="377">Monaco +377
                                </option><option value="976">Mongolia +976
                                </option><option value="1664">Montserrat +1664
                                </option><option value="212">Morocco +212
                                </option><option value="258">Mozambique +258
                                </option><option value="95">Myanmar +95
                                </option><option value="264">Namibia +264
                                </option><option value="674">Nauru +674
                                </option><option value="977">Nepal +977
                                </option><option value="31">Netherlands +31
                                </option><option value="687">New Caledonia +687
                                </option><option value="64">New Zealand +64
                                </option><option value="505">Nicaragua +505
                                </option><option value="227">Niger +227
                                </option><option value="234">Nigeria +234
                                </option><option value="683">Niue +683
                                </option><option value="672">Norfolk Islands +672
                                </option><option value="670">Northern Marianas +670
                                </option><option value="47">Norway +47
                                </option><option value="968">Oman +968
                                </option><option value="680">Palau +680
                                </option><option value="507">Panama +507
                                </option><option value="675">Papua New Guinea +675
                                </option><option value="595">Paraguay +595
                                </option><option value="51">Peru +51
                                </option><option value="63">Philippines +63
                                </option><option value="48">Poland +48
                                </option><option value="351">Portugal +351
                                </option><option value="1787">Puerto Rico +1787
                                </option><option value="974">Qatar +974
                                </option><option value="262">Reunion +262
                                </option><option value="40">Romania +40
                                </option><option value="7">Russia +7
                                </option><option value="250">Rwanda +250
                                </option><option value="378">San Marino +378
                                </option><option value="239">Sao Tome &amp; Principe +239
                                </option><option value="966">Saudi Arabia +966
                                </option><option value="221">Senegal +221
                                </option><option value="381">Serbia +381
                                </option><option value="248">Seychelles +248
                                </option><option value="232">Sierra Leone +232
                                </option><option value="65">Singapore +65
                                </option><option value="421">Slovak Republic +421
                                </option><option value="386">Slovenia +386
                                </option><option value="677">Solomon Islands +677
                                </option><option value="252">Somalia +252
                                </option><option value="27">South Africa +27
                                </option><option value="34">Spain +34
                                </option><option value="94">Sri Lanka +94
                                </option><option value="290">St. Helena +290
                                </option><option value="1869">St. Kitts +1869
                                </option><option value="1758">St. Lucia +1758
                                </option><option value="249">Sudan +249
                                </option><option value="597">Suriname +597
                                </option><option value="268">Swaziland +268
                                </option><option value="46">Sweden +46
                                </option><option value="41">Switzerland +41
                                </option><option value="963">Syria +963
                                </option><option value="886">Taiwan +886
                                </option><option value="7">Tajikstan +7
                                </option><option value="66">Thailand +66
                                </option><option value="228">Togo +228
                                </option><option value="676">Tonga +676
                                </option><option value="1868">Trinidad &amp; Tobago +1868
                                </option><option value="216">Tunisia +216
                                </option><option value="90">Turkey +90
                                </option><option value="7">Turkmenistan +7
                                </option><option value="993">Turkmenistan +993
                                </option><option value="1649">Turks &amp; Caicos Islands +1649
                                </option><option value="688">Tuvalu +688
                                </option><option value="256">Uganda +256
                                </option><option value="44">UK +44
                                </option><option value="380">Ukraine +380
                                </option><option value="971">United Arab Emirates +971
                                </option><option value="598">Uruguay +598
                                </option><option value="1">USA +1
                                </option><option value="7">Uzbekistan +7
                                </option><option value="678">Vanuatu +678
                                </option><option value="379">Vatican City +379
                                </option><option value="58">Venezuela +58
                                </option><option value="84">Vietnam +84
                                </option><option value="84">Virgin Islands - British +1284
                                </option><option value="84">Virgin Islands - US +1340
                                </option><option value="681">Wallis &amp; Futuna +681
                                </option><option value="969">Yemen North +969
                                </option><option value="967">Yemen South +967
                                </option><option value="381">Yugoslavia +381
                                </option><option value="243">Zaire +243
                                </option><option value="260">Zambia +260
                                </option><option value="263">Zimbabwe +263
                                </option></select>
                            </select>
                        </div>
                        </center>
                        <br>
                    @endif
                    
                    <div class="form-group has-feedback has-feedback-left">

                        @if(App\Settings::where('type', 'Accountkitappid')->value('state') != 1)
                            <input name="phone" type="number" style="box-shadow: 0 0 3px #6962ff; margin: 3px" class="form-control" placeholder="  Mobile number" required="required" aria-required="true" maxlength="11" onkeypress="return isNumber(event)" id="phone">
                            
                            <input type="hidden" id="phonevalid" value="0">
                            <label class="basic-error-phone validation-error-label" for="basic" style="display: none;"></label>
                            <br>
                        @endif
                        
                        @if(App\Settings::where('type', 'getUserName')->value('state') !=1)
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="username" type="text" style="box-shadow: 0 0 3px #6962ff; margin: 3px; padding-left:5px" class="form-control" placeholder="  Username" style="" required="required" aria-required="true" id="username">
                                
                                <input type="hidden" id="usernamevalid" value="0">
                                <label class="basic-error-username validation-error-label" for="basic" style="display: none;"></label>
                            </div>
                        @endif

                        @if(App\Settings::where('type', 'getPassword')->value('state')==1)
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="password" type="password" style="box-shadow: 0 0 3px #6962ff; margin: 3px; padding-left:5px" class="form-control" placeholder="  Password" required="required" aria-required="true">
                                
                            </div>
                        @endif
 
                        @if(App\Settings::where('type', 'getEmail')->value('state')==1)
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="email" type="email" style="box-shadow: 0 0 3px #6962ff; margin: 3px; padding-left:5px" class="form-control" placeholder="  Email" required="required" aria-required="true" id="email">
                                
                                <input type="hidden" id="emailvalid" value="0">
                                <label class="basic-error-email validation-error-label" for="basic" style="display: none;"></label>
                            </div>
                        @endif

                        @if(App\Network::value('r_type') == "2")
                            @if(App\Settings::where('type', 'Accountkitappid')->value('state') != 1)
                                <br>
                                <center><span style="color:black;" align="center"> You will receive SMS verification code </span></center>
                            @endif
                        @endif

                        @if(isset($phone_error) &&  $phone_error == 1)
                            <center><span style="color:red;"> Please check your mobile number</span></center>
                        @endif

                        @if(isset($user_exist) &&  $user_exist == 1)
                            <center><span style="color:red;"> Hmmm, Username or Mobile already exist</span></center>
                        @endif
                        
                        @if(isset($mobile_exist) &&  $mobile_exist == 1)
                            <center><span style="color:red;"> Hmmm, Mobile already exist</span></center>
                        @endif
                        
                        @if(isset($email_exist) &&  $email_exist == 1)
                            <center><span style="color:red;"> Hmmm, Email already exist</span></center>

                        @endif
                    </div>
                    <!--<div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked class="styled">
                                    <a href="#" class="text-blue" style="color:black;">Accept terms of service</a>
                            </label>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" id="signup">Register </button>
                    </div>
                </form>
                @endif
            </div>
            
        </div>
    </div>
</div>
@endif

@endif