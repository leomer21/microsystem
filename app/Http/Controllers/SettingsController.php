<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use DB;
use Validator;
use App\Settings;
use Image;
use Auth;
use App;
use Identify;
use Carbon;
class SettingsController extends Controller
{

    public function Index(Request $request){
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['settings'] == 1){
            $object  = $request->input('q');
            echo $object;

            $country = array(
                'Select a Country' => 'Select a Country',
                'Ascension Island' => 'Ascension Island',
                'Andorra' => 'Andorra',
                'United Arab Emirates' => 'United Arab Emirates',
                'Afghanistan' => 'Afghanistan',
                'Antigua And Barbuda' => 'Antigua And Barbuda',
                'Anguilla' => 'Anguilla',
                'Albania' => 'Albania',
                'Armenia' => 'Armenia',
                'Netherlands Antilles' => 'Netherlands Antilles',
                'Angola' => 'Angola',
                'Antarctica' => 'Antarctica',
                'Argentina' => 'Argentina',
                'American Samoa' => 'American Samoa',
                'Austria' => 'Austria',
                'Australia' => 'Australia',
                'Aruba' => 'Aruba',
                'Ãƒâ€¦land' => 'Ãƒâ€¦land',
                'Azerbaijan' => 'Azerbaijan',
                'Bosnia And Herzegovina' => 'Bosnia And Herzegovina',
                'Barbados' => 'Barbados',
                'Belgium' => 'Belgium',
                'Bangladesh' => 'Bangladesh',
                'Burkina Faso' => 'Burkina Faso',
                'Bulgaria' => 'Bulgaria',
                'Bahrain' => 'Bahrain',
                'Burundi' => 'Burundi',
                'Benin' => 'Benin',
                'Bermuda' => 'Bermuda',
                'Brunei Darussalam' => 'Brunei Darussalam',
                'Bolivia' => 'Bolivia',
                'Brazil' => 'Brazil',
                'Bahamas' => 'Bahamas',
                'Bhutan' => 'Bhutan',
                'Bouvet Island' => 'Bouvet Island',
                'Botswana' => 'Botswana',
                'Belarus' => 'Belarus',
                'Belize' => 'Belize',
                'Canada' => 'Canada',
                'Cocos (Keeling) Islands' => 'Cocos (Keeling) Islands',
                'Congo (Democratic Republic)' => 'Congo (Democratic Republic)',
                'Central African Republic' => 'Central African Republic',
                'Congo (Republic)' => 'Congo (Republic)',
                'Switzerland' => 'Switzerland',
                'Cote DÃ¢â‚¬â„¢Ivoire' => 'Cote DÃ¢â‚¬â„¢Ivoire',
                'Cook Islands' => 'Cook Islands',
                'Chile' => 'Chile',
                'Cameroon' => 'Cameroon',
                'PeopleÃ¢â‚¬â„¢s Republic of China' => 'PeopleÃ¢â‚¬â„¢s Republic of China',
                'Colombia' => 'Colombia',
                'Costa Rica' => 'Costa Rica',
                'Cuba' => 'Cuba',
                'Cape Verde' => 'Cape Verde',
                'Christmas Island' => 'Christmas Island',
                'Cyprus' => 'Cyprus',
                'Czech Republic' => 'Czech Republic',
                'Germany' => 'Germany',
                'Djibouti' => 'Djibouti',
                'Denmark' => 'Denmark',
                'Dominica' => 'Dominica',
                'Dominican Republic' => 'Dominican Republic',
                'Algeria' => 'Algeria',
                'Ecuador' => 'Ecuador',
                'Estonia' => 'Estonia',
                'Egypt' => 'Egypt',
                'Eritrea' => 'Eritrea',
                'Spain' => 'Spain',
                'Ethiopia' => 'Ethiopia',
                'European Union' => 'European Union',
                'Finland' => 'Finland',
                'Fiji' => 'Fiji',
                'Falkland Islands (Malvinas)' => 'Falkland Islands (Malvinas)',
                'Micronesia, Federated States Of' => 'Micronesia, Federated States Of',
                'Faroe Islands' => 'Faroe Islands',
                'France' => 'France',
                'Gabon' => 'Gabon',
                'United Kingdom' => 'United Kingdom',
                'Grenada' => 'Grenada',
                'Georgia' => 'Georgia',
                'French Guiana' => 'French Guiana',
                'Guernsey' => 'Guernsey',
                'Ghana' => 'Ghana',
                'Gibraltar' => 'Gibraltar',
                'Greenland' => 'Greenland',
                'Gambia' => 'Gambia',
                'Guinea' => 'Guinea',
                'Guadeloupe' => 'Guadeloupe',
                'Equatorial Guinea' => 'Equatorial Guinea',
                'Greece' => 'Greece',
                'South Georgia And The South Sandwich Islands' => 'South Georgia And The South Sandwich Islands',
                'Guatemala' => 'Guatemala',
                'Guam' => 'Guam',
                'Guinea-Bissau' => 'Guinea-Bissau',
                'Guyana' => 'Guyana',
                'Hong Kong' => 'Hong Kong',
                'Heard And Mc Donald Islands' => 'Heard And Mc Donald Islands',
                'Honduras' => 'Honduras',
                'Croatia (local name: Hrvatska)' => 'Croatia (local name: Hrvatska)',
                'Haiti' => 'Haiti',
                'Hungary' => 'Hungary',
                'Indonesia' => 'Indonesia',
                'Ireland' => 'Ireland',
                'Isle of Man' => 'Isle of Man',
                'India' => 'India',
                'British Indian Ocean Territory' => 'British Indian Ocean Territory',
                'Iraq' => 'Iraq',
                'Iran (Islamic Republic Of)' => 'Iran (Islamic Republic Of)',
                'Iceland' => 'Iceland',
                'Italy' => 'Italy',
                'Jersey' => 'Jersey',
                'Jamaica' => 'Jamaica',
                'Jordan' => 'Jordan',
                'Japan' => 'Japan',
                'Kenya' => 'Kenya',
                'Kyrgyzstan' => 'Kyrgyzstan',
                'Cambodia' => 'Cambodia',
                'Kiribati' => 'Kiribati',
                'Comoros' => 'Comoros',
                'Saint Kitts And Nevis' => 'Saint Kitts And Nevis',
                'Korea, Republic Of' => 'Korea, Republic Of',
                'Kuwait' => 'Kuwait',
                'Cayman Islands' => 'Cayman Islands',
                'Kazakhstan' => 'Kazakhstan',
                'Lao PeopleÃ¢â‚¬â„¢s Democratic Republic' => 'Lao PeopleÃ¢â‚¬â„¢s Democratic Republic',
                'Lebanon' => 'Lebanon',
                'Saint Lucia' => 'Saint Lucia',
                'Liechtenstein' => 'Liechtenstein',
                'Sri Lanka' => 'Sri Lanka',
                'Liberia' => 'Liberia',
                'Lesotho' => 'Lesotho',
                'Lithuania' => 'Lithuania',
                'Luxembourg' => 'Luxembourg',
                'Latvia' => 'Latvia',
                'Libyan Arab Jamahiriya' => 'Libyan Arab Jamahiriya',
                'Morocco' => 'Morocco',
                'Monaco' => 'Monaco',
                'Moldova, Republic Of' => 'Moldova, Republic Of',
                'Montenegro' => 'Montenegro',
                'Madagascar' => 'Madagascar',
                'Marshall Islands' => 'Marshall Islands',
                'Macedonia, The Former Yugoslav Republic Of' => 'Macedonia, The Former Yugoslav Republic Of',
                'Mali' => 'Mali',
                'Myanmar' => 'Myanmar',
                'Mongolia' => 'Mongolia',
                'Macau' => 'Macau',
                'Northern Mariana Islands' => 'Northern Mariana Islands',
                'Martinique' => 'Martinique',
                'Mauritania' => 'Mauritania',
                'Montserrat' => 'Montserrat',
                'Malta' => 'Malta',
                'Mauritius' => 'Mauritius',
                'Maldives' => 'Maldives',
                'Malawi' => 'Malawi',
                'Mexico' => 'Mexico',
                'Malaysia' => 'Malaysia',
                'Mozambique' => 'Mozambique',
                'Namibia' => 'Namibia',
                'New Caledonia' => 'New Caledonia',
                'Niger' => 'Niger',
                'Norfolk Island' => 'Norfolk Island',
                'Nigeria' => 'Nigeria',
                'Nicaragua' => 'Nicaragua',
                'Netherlands' => 'Netherlands',
                'Norway' => 'Norway',
                'Nepal' => 'Nepal',
                'Nauru' => 'Nauru',
                'Niue' => 'Niue',
                'New Zealand' => 'New Zealand',
                'Oman' => 'Oman',
                'Panama' => 'Panama',
                'Peru' => 'Peru',
                'French Polynesia' => 'French Polynesia',
                'Papua New Guinea' => 'Papua New Guinea',
                'Philippines, Republic of the' => 'Philippines, Republic of the',
                'Pakistan' => 'Pakistan',
                'Poland' => 'Poland',
                'St. Pierre And Miquelon' => 'St. Pierre And Miquelon',
                'Pitcairn' => 'Pitcairn',
                'Puerto Rico' => 'Puerto Rico',
                'Palestine' => 'Palestine',
                'Portugal' => 'Portugal',
                'Palau' => 'Palau',
                'Paraguay' => 'Paraguay',
                'Qatar' => 'Qatar',
                'Reunion' => 'Reunion',
                'Romania' => 'Romania',
                'Serbia' => 'Serbia',
                'Russian Federation' => 'Russian Federation',
                'Rwanda' => 'Rwanda',
                'Saudi Arabia' => 'Saudi Arabia',
                'United Kingdom' => 'United Kingdom',
                'Solomon Islands' => 'Solomon Islands',
                'Seychelles' => 'Seychelles',
                'Sudan' => 'Sudan',
                'Sweden' => 'Sweden',
                'Singapore' => 'Singapore',
                'St. Helena' => 'St. Helena',
                'Slovenia' => 'Slovenia',
                'Svalbard And Jan Mayen Islands' => 'Svalbard And Jan Mayen Islands',
                'Slovakia (Slovak Republic)' => 'Slovakia (Slovak Republic)',
                'Sierra Leone' => 'Sierra Leone',
                'San Marino' => 'San Marino',
                'Senegal' => 'Senegal',
                'Somalia' => 'Somalia',
                'Suriname' => 'Suriname',
                'Sao Tome And Principe' => 'Sao Tome And Principe',
                'Soviet Union' => 'Soviet Union',
                'El Salvador' => 'El Salvador',
                'Syrian Arab Republic' => 'Syrian Arab Republic',
                'Swaziland' => 'Swaziland',
                'Turks And Caicos Islands' => 'Turks And Caicos Islands',
                'Chad' => 'Chad',
                'French Southern Territories' => 'French Southern Territories',
                'Togo' => 'Togo',
                'Thailand' => 'Thailand',
                'Tajikistan' => 'Tajikistan',
                'Tokelau' => 'Tokelau',
                'East Timor (new code)' => 'East Timor (new code)',
                'Turkmenistan' => 'Turkmenistan',
                'Tunisia' => 'Tunisia',
                'Tonga' => 'Tonga',
                'East Timor (old code)' => 'East Timor (old code)',
                'Turkey' => 'Turkey',
                'Trinidad And Tobago' => 'Trinidad And Tobago',
                'Tuvalu' => 'Tuvalu',
                'Taiwan' => 'Taiwan',
                'Tanzania, United Republic Of' => 'Tanzania, United Republic Of',
                'Ukraine' => 'Ukraine',
                'Uganda' => 'Uganda',
                'United States Minor Outlying Islands' => 'United States Minor Outlying Islands',
                'United States' => 'United States',
                'Uruguay' => 'Uruguay',
                'Uzbekistan' => 'Uzbekistan',
                'Vatican City State (Holy See)' => 'Vatican City State (Holy See)',
                'Saint Vincent And The Grenadines' => 'Saint Vincent And The Grenadines',
                'Venezuela' => 'Venezuela',
                'Virgin Islands (British)' => 'Virgin Islands (British)',
                'Virgin Islands (U.S.)' => 'Virgin Islands (U.S.)',
                'Viet Nam' => 'Viet Nam',
                'Vanuatu' => 'Vanuatu',
                'Wallis And Futuna Islands' => 'Wallis And Futuna Islands',
                'Samoa' => 'Samoa',
                'Yemen' => 'Yemen',
                'Mayotte' => 'Mayotte',
                'South Africa' => 'South Africa',
                'Zambia' => 'Zambia',
                'Zimbabwe' => 'Zimbabwe'
            );
            return view('back-end.settings.index',array(
                'countries' => $country,
                'object' => $object
            ));
        }else{
            return view('errors.404');
        }
    }
    public function SMSSettings(){

        App\Settings::where('type', 'SMSProvider')->update(['value' => Input::get('provider')]);
        App\Settings::where('type', 'SMSProviderusername')->update(['value' => Input::get('providerusername')]);
        App\Settings::where('type', 'SMSProviderpassword')->update(['value' => Input::get('providerpassword')]);
        App\Settings::where('type', 'SMSProvidersendername')->update(['value' => Input::get('providersendername')]);
        App\Settings::where('type', 'SMSProvider')->update(['state' => Input::get('sms-enable') == "on" ? '1' : '0']);
        if(App\Settings::where('type', 'smsVerificationTemplate')->count() == 0){
            if(Input::get('smsVerificationTemplate')!=""){ $smsVerificationTemplate = Input::get('smsVerificationTemplate');}
            else{$smsVerificationTemplate = "Microsystem Smart Wi-Fi code is @CODE";}
            App\Settings::insert(['type' => 'smsVerificationTemplate', 'value' => $smsVerificationTemplate, 'state' => '0']);
        }else{
            if(Input::get('smsVerificationTemplate')!=""){ $smsVerificationTemplate = Input::get('smsVerificationTemplate');}
            else{$smsVerificationTemplate = "Microsystem Smart Wi-Fi code is @CODE";}
            App\Settings::where('type', 'smsVerificationTemplate')->update(['value' => $smsVerificationTemplate]);
        }
        
        return redirect()->route('settings');
    }

    public function whatsappSetting(){

        App\Settings::where('type', 'whatsappProvider')->update(['value' => Input::get('whatsappProvider')]);
        App\Settings::where('type', 'whatsappProviderUsername')->update(['value' => Input::get('whatsappProviderUsername')]);
        App\Settings::where('type', 'whatsappProviderPassword')->update(['value' => Input::get('whatsappProviderPassword')]);
        App\Settings::where('type', 'whatsappSenderName')->update(['value' => Input::get('whatsappSenderName')]);
        App\Settings::where('type', 'whatsappProvider')->update(['state' => Input::get('whatsapp-enable') == "on" ? '1' : '0']);


        return redirect()->route('settings');
    }

    public function pmsSetting(){

        App\Settings::where('type', 'pms_complementary_portal_label')->update(['value' => Input::get('pms_complementary_portal_label')]);
        App\Settings::where('type', 'pms_premium_portal_label')->update(['value' => Input::get('pms_premium_portal_label')]);
        App\Settings::where('type', 'pms_login_password_portal_label')->update(['value' => Input::get('pms_login_password_portal_label')]);
        App\Settings::where('type', 'pms_login_username_portal_label')->update(['value' => Input::get('pms_login_username_portal_label')]);
        App\Settings::where('type', 'pms_integration')->update(['state' => Input::get('pms-enable') == "on" ? '1' : '0']);
        App\Settings::where('type', 'pms_save_mobile_from_login_page')->update(['state' => Input::get('pms_save_mobile_from_login_page') == "on" ? '1' : '0']);
        App\Settings::where('type', 'pms_save_email_from_login_page')->update(['state' => Input::get('pms_save_email_from_login_page') == "on" ? '1' : '0']);

        return redirect()->route('settings');
    }

    public function telegramSetting(){

        date_default_timezone_set("Africa/Cairo");
        $todayDateTime = date("Y-m-d H:i:s");
        $telegramApiState = Input::get('telegramApiState') == "on" ? '1' : '0';
        //check if record is created before or not
        $split = explode('/', url()->full());
        $customerData = DB::table('customers')->where('url',$split[2])->first();
        // get system phone for Telegram
        $systemPhone = App\Settings::where('type', 'phone')->value('value');
        if(!isset($systemPhone)){$systemPhone = rand(111111111,999999999);}
        $whatsappTokenData = DB::table('whatsapp_token')->where('customer_id',$customerData->id)->where('integration_type','4')->first();
        if(isset($whatsappTokenData)){
            // record already created before, so we will update
            DB::table('whatsapp_token')->where('customer_id', $customerData->id)->where('integration_type','4')->update(['server_mobile' => "Telegram".$systemPhone, 'telegram_api_token' => Input::get('telegramApiToken'), 'state' => $telegramApiState, 'updated_at' => $todayDateTime]);
            // update or delete api webhook url
            if($telegramApiState == '1'){ $webhookState = "/setWebhook"; }else{ $webhookState = "/deleteWebhook";}
            $webhookUrl = "https://$customerData->url/api/telegramWebhook";
            $data = ['url' => $webhookUrl];
            $msg = json_encode($data); // Encode data to JSON
            $url = "https://api.telegram.org/bot".Input::get('telegramApiToken').$webhookState;
            $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
            $response = @file_get_contents($url, FALSE, $context);
        
        }else{
            // create new record in `whatsapp_token`
            // get system phone
            $systemPhone = App\Settings::where('type', 'phone')->value('value');
            if(!isset($systemPhone)){$systemPhone = rand(111111111,999999999);}
            // insert record
            DB::table('whatsapp_token')->insert(['customer_id' => $customerData->id, 'integration_type' => 4, 'state' => $telegramApiState, 'server_mobile' => "Telegram".$systemPhone, 'telegram_api_token' => Input::get('telegramApiToken'), 'created_at' => $todayDateTime, 'registered_at' =>$todayDateTime]);
            // update or delete api webhook url
            if($telegramApiState == '1'){ $webhookState = "/setWebhook"; }else{ $webhookState = "/deleteWebhook";}
            $webhookUrl = "https://$customerData->url/api/telegramWebhook";
            $data = ['url' => $webhookUrl];
            $msg = json_encode($data); // Encode data to JSON
            $url = "https://api.telegram.org/bot".Input::get('telegramApiToken').$webhookState;
            $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
            $response = @file_get_contents($url, FALSE, $context);
        
        }

        return redirect()->route('settings');
    }    
    
    public function simpleTouchSetting(){

        App\Settings::where('type', 'simpleTouchPosIntegration')->update(['value' => Input::get('simpleTouchPosIntegrationID')]);
        return redirect()->route('settings');
    }

    public function Systemsetting(){

        App\Settings::where('type', 'app_name')->update(['value' => Input::get('appname')]);
        App\Settings::where('type', 'description')->update(['value' => Input::get('description')]);
        App\Settings::where('type', 'address')->update(['value' => Input::get('address')]);
        App\Settings::where('type', 'email')->update(['value' => Input::get('email')]);
        // check if mobile number contains country code?
        $checkCountry = explode('+', Input::get('phone'));
        if(isset($checkCountry[1])){App\Settings::where('type', 'phone')->update(['value' => Input::get('phone')]);} // contains country code
        else{
            if(Input::get('country')=="Egypt"){$fullMobileNumber = "+2".Input::get('phone'); App\Settings::where('type', 'phone')->update(['value' => $fullMobileNumber]);}
            else{$fullMobileNumber = "+".Input::get('phone'); App\Settings::where('type', 'phone')->update(['value' => $fullMobileNumber]);}
        }
		App\Settings::where('type', 'currency')->update(['value' => Input::get('currency')]);
        App\Settings::where('type', 'country')->update(['value' => Input::get('country')]);
        //App\Settings::where('type', 'template')->update(['value' => Input::get('template')]);
        App\Settings::where('type', 'alwaysOpenPasswordLoginInUserCP')->update(['state' => Input::get('alwaysOpenPasswordLoginInUserCP')]);
        App\Settings::where('type', 'getUserName')->update(['state' => Input::get('getUserName')]);
        App\Settings::where('type', 'getGender')->update(['state' => Input::get('getGender')]);
        App\Settings::where('type', 'getNetwork')->update(['state' => Input::get('getNetwork')]);
        App\Settings::where('type', 'getEmail')->update(['state' => Input::get('getEmail')]);
        App\Settings::where('type', 'getName')->update(['state' => Input::get('getName')]);
        App\Settings::where('type', 'signupDefault')->update(['state' => Input::get('signupDefault')]);
        App\Settings::where('type', 'getPassword')->update(['state' => Input::get('getPassword')]);
        App\Settings::where('type', 'disableLogin')->update(['state' => Input::get('disableLogin')]);
        App\Settings::where('type', 'mergeAccounts')->update(['state' => Input::get('mergeAccounts')]);
        App\Settings::where('type', 'getMobileInSignupTab')->update(['state' => Input::get('getMobileInSignupTab')]);
        App\Settings::where('type', 'getCardSerialInSignupTab')->update(['state' => Input::get('getCardSerialInSignupTab')]);
        
        App\Network::where('r_type','!=',"10")->update(['r_type' => Input::get('regType')]);
        App\Network::where('commercial','!=',"10")->update(['commercial' => Input::get('commercial')]);
        App\Network::where('name','!='," name ")->update(['name' => Input::get('appname')]);

        // $subdomain = url()->full();
        // $split = explode('/', $subdomain);
        // $customerData =  DB::table('customers')->where('url',$split[2])->update(['payasyougo' => Input::get('payasyougo')]);
        
        $terms=App\Settings::where('type', 'terms')->value('value');
        if(isset($terms)){
            App\Settings::where('type', 'terms')->update(['value' => Input::get('terms')]);
        }else{
            App\Settings::insert(['type' => 'terms', 'value' => Input::get('terms')]);
        }
        if(Input::hasFile('logo')) {
            $logo = Input::file('logo');
            $name = $logo->getClientOriginalName();
            App\Settings::where('type', 'logo')->update(['value' => $name]);
            $logo->move(public_path().'/upload/', $name);
        }
        return redirect()->route('settings');
        
    }
    public function Systemsettingemail(){
        if(Input::get('emailVerificationWithoutAiForLogin')=="1"){$emailVerificationWithoutAiForLogin="";}else{$emailVerificationWithoutAiForLogin="WithoutAi";}
        if(Input::get('emailVerificationWithoutAiForSignup')=="1"){$emailVerificationWithoutAiForSignup="";}else{$emailVerificationWithoutAiForSignup="WithoutAi";}
        App\Settings::where('type', 'emailVerificationForLogin')->update(['state' => Input::get('emailVerificationForLogin'), 'value' => $emailVerificationWithoutAiForLogin ]);
        App\Settings::where('type', 'emailVerificationForSignup')->update(['state' => Input::get('emailVerificationForSignup'), 'value' => $emailVerificationWithoutAiForSignup ]);

        App\Settings::where('type', 'emailVerificationSwitchRoomTypeForLogin')->update(['state' => Input::get('emailVerificationSwitchRoomTypeForLogin')]);
        App\Settings::where('type', 'emailVerificationSwitchRoomTypeForSignup')->update(['state' => Input::get('emailVerificationSwitchRoomTypeForSignup')]);

        App\Settings::where('type', 'emailVerificationSwitchToGroupIdForLogin')->update(['value' => Input::get('emailVerificationSwitchToGroupIdForLogin')]);
        App\Settings::where('type', 'emailVerificationSwitchToGroupIdForSignup')->update(['value' => Input::get('emailVerificationSwitchToGroupIdForSignup')]);

        App\Settings::where('type', 'emailVerificationMotivationalMsgForLogin')->update(['value' => Input::get('emailVerificationMotivationalMsgForLogin')]);
        App\Settings::where('type', 'emailVerificationMotivationalMsgForSignup')->update(['value' => Input::get('emailVerificationMotivationalMsgForSignup')]);
  
        App\Settings::where('type', 'emailVerificationUsingChatGptMessage')->update(['value' => str_replace("\n", "", Input::get('emailVerificationUsingChatGptMessage')) ]);
        return redirect()->route('settings');
    }

    public function ChatGptSetting(){
        App\Settings::where('type', 'chatGptApiToken')->update(['state' => Input::get('enable')]);
        App\Settings::where('type', 'sendEmailsFromEmail')->update(['value' => Input::get('sendEmailsFromEmail')]);
        App\Settings::where('type', 'chatGptApiToken')->update(['value' => Input::get('chatGptApiToken')]);

        return redirect()->route('settings');
    }

    public function Accountsetting(){

        return redirect()->route('settings');
    }
    public function Facebooksettings(){
        App\Settings::where('type', 'facebook_client_id')->update(['value' => Input::get('facebook_id')]);
        App\Settings::where('type', 'facebook_client_secret')->update(['value' => Input::get('facebook_secret')]);
        App\Settings::where('type', 'facebook_client_id')->update(['state' => Input::get('enable')]);

        return redirect()->route('settings');
    }
    public function Twittersettings(){
        //return Input::get('enable');
        App\Settings::where('type', 'twitter_client_id')->update(['value' => Input::get('twitter_id'),'state' => Input::get('enable')]);
        App\Settings::where('type', 'twitter_client_secret')->update(['value' => Input::get('twitter_secret')]);
        App\Settings::where('type', 'twitter_client_id')->update(['state' => Input::get('enable')]);

        return redirect()->route('settings');
    }
    public function Googlesettings(){
        App\Settings::where('type', 'google_client_id')->update(['value' => Input::get('google_id')]);
        App\Settings::where('type', 'google_client_secret')->update(['value' => Input::get('google_secret')]);
        App\Settings::where('type', 'google_client_id')->update(['state' => Input::get('enable')]);

        return redirect()->route('settings');
    }
    public function Linkedinsettings(){
        App\Settings::where('type', 'linkedin_client_id')->update(['value' => Input::get('linkedin_id')]);
        App\Settings::where('type', 'linkedin_client_secret')->update(['value' => Input::get('linkedin_secret')]);
        App\Settings::where('type', 'linkedin_client_id')->update(['state' => Input::get('enable')]);

        return redirect()->route('settings');
    }
    public function Agilesetting(){
        
        App\Settings::where('type', 'agile_domain_name')->update(['value' => Input::get('agile_domain_name')]);
        App\Settings::where('type', 'agile_admin_email')->update(['value' => Input::get('agile_admin_email')]);
        App\Settings::where('type', 'agile_send_comtacts')->update(['state' => Input::get('agile_send_comtacts')]);
        App\Settings::where('type', 'agile_receive_contacts')->update(['state' => Input::get('agile_receive_contacts')]);
        App\Settings::where('type', 'agile_rest_api')->update(['value' => Input::get('agile_rest_api')]);
        App\Settings::where('type', 'agile_rest_api')->update(['state' => Input::get('enable')]);
        App\Settings::where('type', 'agile_send_login_score')->update(['state' => Input::get('agile_send_login_score')]);

        return redirect()->route('settings');
    }

    public function logo(Request $request){

        //Upload logo
        if($request->hasFile('file')) {
            $file = Input::file('file');
            $name = $file->getClientOriginalName();
            App\Settings::where('type', 'logo')->update(['value' => $name]);
            $file->move(public_path().'/upload/', $name);

        }
        return redirect()->back();
    }

    public function AccountkitSMSSettings(){

        App\Settings::where('type', 'Accountkitappid')->update(['value' => Input::get('accountkitappid')]);
        App\Settings::where('type', 'Accountkitappsecret')->update(['value' => Input::get('accountkitappsecret')]);
        App\Settings::where('type', 'Accountkitappid')->update(['state' => Input::get('accountkit-enable') == "on" ? '1' : '0']);

        return redirect()->route('settings');
    }

    public function firebaseSMSauthSetting(){

        App\Settings::where('type', 'firebaseAuthentication')->update(['value' => Input::get('firebaseApiKey')]);
        App\Settings::where('type', 'firebaseAuthentication')->update(['state' => Input::get('firebase-enable') == "on" ? '1' : '0']);
        // Auto enable/disable registration type to SMS verification 
        if(Input::get('firebase-enable') == "on"){
            // Dnable SMS verification 
            App\Network::where('r_type','!=',"10")->update(['r_type' => '2']);
        }else{
            // Disable SMS verification 
            App\Network::where('r_type','!=',"10")->update(['r_type' => '0']);
        }
        return redirect()->route('settings');
    }

    public function payasyougoInvoices(){

        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();

        if($customerData->global == "1"){ $packagesTable = "packages_global"; }else{ $packagesTable = "packages"; }  
        $alldata = DB::table('invoices')->where('type', 'payasyougo')->where('customer_id', $customerData->id)->get();

        foreach ($alldata as $key => $value) {
            $packageData = DB::table('packages')->where('id', $value->package_id)->first();
            $value->concurrent = $packageData->concurrent_devices;
        }
        return array('aaData'=>$alldata);
    }

    public function getWhatsappChannels(){

        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();

        $alldata = DB::table('whatsapp_token')->where('customer_id', $customerData->id)->get();

        foreach ($alldata as $key => $value) {
            if($value->integration_type == "1"){$value->integration_type="Linux";}
            elseif($value->integration_type == "2"){$value->integration_type="ChatAPI";}
            elseif($value->integration_type == "3"){$value->integration_type="Mercury";}
            elseif($value->integration_type == "4"){$value->integration_type="Telegram Direct";}
            elseif($value->integration_type == "5"){$value->integration_type="Mikofi";}
        }
        return array('aaData'=>$alldata);
    }

    public function getPms(){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
        
        $pms = App\Models\Pms::get();
        foreach ($pms as $key => $value){
            date_default_timezone_set("Africa/Cairo");
            if(isset($value->last_check)) {
                $value->last_check_since = Carbon\Carbon::parse($value->last_check)->diffForHumans();
                // diffrence two times into seconds
		        $value->last_check_since_seconds = Carbon\Carbon::parse($todayDateTime)->diffInSeconds(Carbon\Carbon::parse($value->last_check));
            }
            else{$value->last_check_since = ""; $value->last_check_since_seconds=0;}
        }
        return array('aaData'=>$pms );
    }

    public function payasyougoState($state){

        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        // $customerData =  DB::table('customers')->where('url',$split[2])->first();

        DB::table('customers')->where('url',$split[2])->update(['payasyougo' => $state]);
        return "Done";
    }

    public function simpleTouchPosIntegrationState($state){

        App\Settings::where('type', 'simpleTouchPosIntegration')->update(['state' => $state]);
        return "Done";
    }

    public function posRocketIntegrationState($state){

        App\Settings::where('type', 'PosRocketIntegration')->update(['state' => $state]);
        return "Done";
    }
    
    public function addPmsIntegration(Request $request)
    {
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");

        $newPms = new App\Models\Pms();
        $newPms->state = '1';
        $newPms->name = $request['name'];
        $newPms->type = $request['type'];
        $newPms->connection_type = $request['connection_type'];
        $newPms->internet_group = $request['internet_group'];
        $newPms->checkout_group = $request['checkout_group'];
        
        if($request['connection_type'] == "database"){
            $newPms->db_ip = $request['db_ip'];
            $newPms->db_port = $request['db_port'];
            $newPms->db_name = $request['db_name'];
            $newPms->db_username = $request['db_username'];
            $newPms->db_password = $request['db_password'];
            $newPms->db_transaction_code = $request['db_transaction_code'];
            $newPms->db_posting_username = $request['db_posting_username'];
        }elseif($request['connection_type'] == "interface"){
            $newPms->interface_ip = $request['interface_ip'];
            $newPms->interface_port = $request['interface_port'];
        }
        $newPms->login_username = $request['login_username'];
        $newPms->login_password = $request['login_password'];
        $newPms->created_at = $created_at;
        $newPms->save();

        return redirect()->route('settings');
    }

    public function viewEditOfPmsIntegration($id)
    {
        $pms = App\Models\Pms::find($id);
        return view('back-end.settings.editPmsIntegration',array(
            'pms' => $pms
        ));
    }

    public function editPmsIntegration($id)
    {   
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");

        $pms = App\Models\Pms::find($id);
        $pms->state = Input::get('state');
        $pms->name = Input::get('name');
        $pms->type = Input::get('type');
        $pms->connection_type = Input::get('connection_type');
        $pms->internet_group = Input::get('internet_group');
        $pms->checkout_group = Input::get('checkout_group');
        
        if(Input::get('connection_type') == "database"){
            $pms->db_ip = Input::get('db_ip');
            $pms->db_port = Input::get('db_port');
            $pms->db_name = Input::get('db_name');
            $pms->db_username = Input::get('db_username');
            $pms->db_password = Input::get('db_password');
            $pms->db_transaction_code = Input::get('db_transaction_code');
            $pms->db_posting_username = Input::get('db_posting_username');
        }elseif(Input::get('connection_type') == "interface"){
            $pms->interface_ip = Input::get('interface_ip');
            $pms->interface_port = Input::get('interface_port');
        }
        
        $pms->login_username = Input::get('login_username');
        $pms->login_password = Input::get('login_password');
        $pms->updated_at = $created_at;
        $pms->update();

        return redirect()->route('settings');
    }

    public function deletePmsIntegration($id)
    {   
        App\Models\Pms::where('id', $id)->delete();
        return redirect()->route('settings');
    }

    public function addWhatsappIntegration(Request $request)
    {
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");

        // get system data
        $split = explode('/', url()->full());
        $customerData = DB::table('customers')->where('url',$split[2])->orderBy('id', 'desc')->first();
        // update whatsapp status in `customers` table in `Microsystem` database
        DB::table('customers')->where('id',$customerData->id)->update(['whatsapp' => '1', 'whatsapp_credit' => '100000']);
        // update whatsapp status and settings into `settings` table
        App\Settings::where('type', 'whatsappProvider')->update(['value' => $request['integration_type'], 'state' => '1']);
        App\Settings::where('type', 'whatsappProviderUsername')->update(['value' => $customerData->database]);
        App\Settings::where('type', 'whatsappProviderPassword')->update(['value' => $customerData->password]);
        App\Settings::where('type', 'whatsappServerMobile')->update(['value' => $request['server_mobile']]);
        App\Settings::where('type', 'whatsappProvider')->update(['value' => $request['integration_type']]);
        // create whatsapp token record
        DB::table('whatsapp_token')->insert(['customer_id' => $customerData->id, 'integration_type' => $request['integration_type'], 'state' => '1', 'server_mobile' => $request['server_mobile'], 'chatapi_instance_url' => $request['chatapi_instance_url'], 'chatapi_instance_id' => $request['chatapi_instance_id'], 'chatapi_instance_token' => $request['chatapi_instance_token'], 'created_at' => $created_at, 'registered_at' =>$created_at]);
        // check if this integration with our provider (Mikofi.com) to register the webhook url
        if($request['integration_type'] == "5"){
            // register the webhook url
            $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
            // New Version
            // $url = "https://mikofi.com/api/setwebhook.php?webhook_url=http://".$customerData->url."/api/whatsapp&enable=true&instance_id=".Input::get('chatapi_instance_id')."&access_token=".Input::get('chatapi_instance_token');
            $url = "https://mikofi.com/api/set_webhook?webhook_url=http://".$customerData->url."/api/whatsapp&enable=true&instance_id=".Input::get('chatapi_instance_id')."&access_token=".Input::get('chatapi_instance_token');
            // $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
            $arrContextOptions=array('http' => array('method' => 'GET'),"ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),); 
        }
        // finished, return to the settings page
        return redirect()->route('settings');
    }

    public function viewEditOfWhatsappIntegration($id)
    {
        $whatsapp = DB::table('whatsapp_token')->find($id);
        return view('back-end.settings.editWhatsappIntegration',array(
            'whatsapp' => $whatsapp
        ));
    }

    public function editWhatsappIntegration($id)
    {   
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        // update whatsapp token record
        DB::table('whatsapp_token')->where('id',$id)->update(['state' => Input::get('state'), 'server_mobile' => Input::get('server_mobile'), 'chatapi_instance_url' => Input::get('chatapi_instance_url'), 'chatapi_instance_id' => Input::get('chatapi_instance_id'), 'chatapi_instance_token' => Input::get('chatapi_instance_token'), 'updated_at' => $created_at]);
        // check if this integration with our provider (Mikofi.com) to register the webhook url
        if( Input::get('integration_type')=="5" ){
            // register the webhook url
            $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);
            $customerData = DB::table('customers')->where('id',Input::get('customer_id'))->first();
            $url = "https://mikofi.com/api/setwebhook.php?webhook_url=http://".$customerData->url."/api/whatsapp&enable=true&instance_id=".Input::get('chatapi_instance_id')."&access_token=".Input::get('chatapi_instance_token');
            $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
        }
        return redirect()->route('settings');

    }

    public function deleteWhatsappIntegration($id)
    {   
        // get customer ID
        $whatsapp_token_data = DB::table('whatsapp_token')->where('id',$id)->first();
        // check if this integration with our provider (Mikofi.com) to register the webhook url
        if( $whatsapp_token_data->integration_type=="5" ){
            // remove instance
            $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);
            $customerData = DB::table('customers')->where('id',$whatsapp_token_data->customer_id)->first();
            $url = "https://mikofi.com/api/resetinstance.php?instance_id=".$whatsapp_token_data->chatapi_instance_id."&access_token=".$whatsapp_token_data->chatapi_instance_token;
            $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
        }
        // delete the record
        DB::table('whatsapp_token')->where('id', $id)->delete();
        // return to settings page
        return redirect()->route('settings');
    }

    public function restartWhatsappIntegration($id)
    {   
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        // get customer ID
        $whatsapp_token_data = DB::table('whatsapp_token')->where('id',$id)->first();
        // check if this integration with our provider (Mikofi.com) to register the webhook url
        if( $whatsapp_token_data->integration_type=="5" ){
            // reconnect instance
            $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);
            $customerData = DB::table('customers')->where('id',$whatsapp_token_data->customer_id)->first();
            $url = "https://mikofi.com/api/reconnect.php?instance_id=".$whatsapp_token_data->chatapi_instance_id."&access_token=".$whatsapp_token_data->chatapi_instance_token;
            $response = @file_get_contents($url, FALSE, stream_context_create($arrContextOptions));
        }
        return redirect()->route('settings');

    }
    
}