<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Radacct;
use App\Messages;
use App\History;
use App\Settings;
use App;
use DB;
use Session;
use Excel;
use App\Http\Requests;
use DateTime;
use Input;
use Mail;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use Carbon\Carbon;
use Auth;
use App\Models\URLFilter;

class SearchController extends Controller
{
    public function __construct(){
        Session::put('admin_login', '1');

    }

    public function index(){

        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 2 || Auth::user()->type == 1 || $permissions['users'] == 1) {
            $countries = array(
                'Egypt' => 'Egypt',
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
            // get counters for laft panel filter
            return view('back-end.user.search', array(
                'networks' => App\Network::all(),
                'branchs' => App\Models\BranchCount::get(),
                'area_groups' => App\Models\GroupsCount::get(),
                'genders' => App\Models\Gender::get(),
                'statues' => App\Models\StatuesCount::get(),
                'suspends' => App\Models\SuspendCount::get(),
                'registerconfirm' => App\Models\RegisterConfirm::get(),
                'countrys' => App\Models\CountryCount::get(),
                'searchresults' => App\SearchResult::orderBy('id', 'desc')->get(),
                // 'users' => App\Users::get(),
                'branchesData' => App\Branches::where('state', '1')->get(),
                'countries' => $countries
            ));
           
        }else{
            return view('errors.404');
        }
    }
    public function json(Request $request){

        // IMPORTANT : we can use "whereIn" in search for all the following where functions or "oeWhereIn" to get another value in search page
        //form search button
        $object  = $request->input('by');
        $value   = $request->input('find');
        $length  = $request->input('length');
        $start   = $request->input('start');
        // $order   = $request->input('order');
        $columns = $request->input('columns');
        // $olderBy =  $columns[$order[0]['column']]['data'];

        $network = $request->input('network');
        $groups = $request->input('groups');
        $user_frequency_charged_from = $request->input('user_frequency_charged_from');
        $user_frequency_charged_to   = $request->input('user_frequency_charged_to');
        $frequency = $request->input('frequency');
        $Users_charged_from = $request->input('Users_charged_from');
        $Users_charged_to   = $request->input('Users_charged_to');
        $Users_not_charged_from = $request->input('Users_not_charged_from');
        $Users_not_charged_to = $request->input('Users_not_charged_to');
        $male = $request->input('male');
        $female = $request->input('female');
        $Unknown = $request->input('Unknown');
        $active = $request->input('active');
        $inactive = $request->input('inactive');
        $online = $request->input('online');
        $SortByMostVisited = $request->input('SortByMostVisited');
        $SortByInternetConsumption = $request->input('SortByInternetConsumption');
        $suspend = $request->input('suspend');
        $unsuspend = $request->input('unsuspend');
        $register = $request->input('register');
        $adminconfirm = $request->input('adminconfirm');
        $smsconfirm = $request->input('smsconfirm');
        $country = $request->input('country');
        $filtrationMenu = 0;
        $database =  app('App\Http\Controllers\Controller')->configuration();

        // pms integration status
        if(App\Models\Pms::where('state', '1')->count() > 0){ $pmsIntegration = 1; }else{ $pmsIntegration = 0; }

        if(isset($network) and $network == ''){
            unset($network);
        }

        if(isset($groups) and $groups == ''){
            unset($groups);
        }

        if(isset($country) and $country == ''){
            unset($country);
        }

        if((isset($user_frequency_charged_from) and $user_frequency_charged_from == '') or (isset($user_frequency_charged_to) and $user_frequency_charged_to == '')){
            unset($user_frequency_charged_from);
            unset($user_frequency_charged_to);
            unset($frequency);
        }

        if((isset($Users_charged_from) and $Users_charged_from == '') or (isset($Users_charged_to) and $Users_charged_to == '')){
            unset($Users_charged_from);
            unset($Users_charged_to);
        }

        $Gender = array();
        if(isset($male) and $male == 'on'){
            $Gender[] = 1;
        }

        if(isset($female) and $female == 'on'){
            $Gender[] = 0;
        }

        if(isset($Unknown) and $Unknown == 'on'){
            $Gender[] = 2;
        }
        //Statues
        $Active = array();

        if(isset($active) and $active == 'on'){
            $Active[] = 1;
        }

        $Inactive = array();

        if(isset($inactive) and $inactive == 'on'){
            $Inactive[] = 0;
        }

       //online but I didnt use this section

        //Suspend
        $Suspend = array();

        if(isset($suspend) and $suspend == 'on'){
            $Suspend[] = 1;
        }

        $Unsuspend = array();
        if(isset($unsuspend) and $unsuspend == 'on'){
            $Unsuspend[] = 0;
        }
        //Register Confirm
        $Register = array();
        if(isset($register) and $register == 'on'){
            $Register[] = 2;
        }
        $Adminconfirm = array();
        if(isset($adminconfirm) and $adminconfirm == 'on'){
            $Adminconfirm[] = 0;
        }

        $SMSconfirm = array();
        if(isset($smsconfirm) and $smsconfirm == 'on'){
            $SMSconfirm[] = 1;
        }
        
        $data = array(); // model from url
        switch ($object) {
            case 'Name':
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
                    $data = App\Models\UsersRadacct::where('u_name','like', '%'.$value.'%');
                }else{
                    $data = App\Users::where('u_name','like', '%'.$value.'%');
                }
                break;
            case 'User name':
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
                    $data = App\Models\UsersRadacct::where('u_uname','like', '%'.$value.'%');
                }else{
                    $data = App\Users::where('u_uname','like', '%'.$value.'%');
                }
                break;
            case 'Comment':
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
                    $data = App\Models\UsersRadacct::where('notes','like', '%'.$value.'%');
                }else{
                    $data = App\Users::where('notes','like', '%'.$value.'%');
                }
                break;
            case 'Mobile':
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
                    $data = App\Models\UsersRadacct::where('u_phone','like', '%'.$value.'%');
                }else{
                    $data = App\Users::where('u_phone','like', '%'.$value.'%');
                }
                break;
            case 'E-mail':
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
                    $data = App\Models\UsersRadacct::where('u_email','like', '%'.$value.'%');
                }else{
                    $data = App\Users::where('u_email','like', '%'.$value.'%');
                }
                break;
            case 'Computer(Macaddress)':
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
                    $data = App\Models\UsersRadacct::where('u_mac','like', '%'.$value.'%');
                }else{
                    $data = App\Users::where('u_mac','like', '%'.$value.'%');
                }
                break;
            default:
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
                    //$data = App\Models\UsersRadacct::get();
                    $data = DB::table($database.'.users_radacct');
                }else{
                    //$data = App\Users::all();
                    $data = DB::table($database.'.users');
                }
                break;
        }
        if(Auth::user()->type == 2) {
            $admin_branches = App\Admins::where('id', Auth::user()->id)->value('branches');
            $branches = explode(',', $admin_branches);
            $data->whereIn('branch_id', $branches);
        }else{
            if (isset($network)) {
                $network = explode(',', $network);
                $data->whereIn('branch_id', $network);
            }
        }
        if(isset($groups)){
            $filtrationMenu++;
            $groups = explode(',',$groups);
            $data->whereIn('group_id',$groups);
        }
        if(isset($country)){
            $filtrationMenu++;
            $country = explode(',',$country);
            $data->whereIn('u_country',$country);
        }
        if(isset($frequency)){
            $filtrationMenu++;
            $data->select(DB::raw('count(u_id) as counts, `u_id`, `u_name`, `u_uname`, `u_email`, `u_phone`, `suspend`, `Selfrules`, `created_at`, `pms_guest_id`'))->whereBetween('dates',[$this->convertDate($user_frequency_charged_from),$this->convertDate($user_frequency_charged_to)])
                // ->groupBy('u_id') // moved to the end of IF CONDITIONS
                ->having('counts', '>=', $frequency);
                //->having('counts', '>=', $frequency)->max('radacctid'); we remove ->max('radacctid') after bug in 7.2.2017 because we don't know this jop
        }
        if(isset($Users_charged_from) and $Users_charged_from!=""){
            $filtrationMenu++;
            $data->whereBetween('u_card_date_of_charging',[$this->convertDate($Users_charged_from),$this->convertDate($Users_charged_to)]);
        }
        if(isset($Users_not_charged_from) and $Users_not_charged_from!=""){
            $filtrationMenu++;
            $data->whereNotBetween('u_card_date_of_charging',[$this->convertDate($Users_not_charged_from),$this->convertDate($Users_not_charged_to)])->orWhereNull('u_card_date_of_charging');
        }
        if (!empty($Gender)) {
            $filtrationMenu++;
            $data->whereIn('u_gender', $Gender);
        }
        //Statues
        if (!empty($Active)) {
            $filtrationMenu++;
            $data->whereIn('u_state',$Active);
        }//Statues
        if (!empty($Inactive)) {
            $filtrationMenu++;
            $data->whereIn('u_state',$Inactive);
        }
        if (!empty($Suspend)) {
            $filtrationMenu++;
            $data->whereIn('suspend',$Suspend);
        }
        if (!empty($Unsuspend)) {
            $filtrationMenu++;
            $data->whereIn('suspend',$Unsuspend);
        }
        if (!empty($Register)) {
            $filtrationMenu++;
            $data->whereIn('Registration_type',$Register);
        }
        if (!empty($Adminconfirm)) {
            $filtrationMenu++;
            $data->whereIn('Registration_type',$Adminconfirm);
        }
        if (!empty($SMSconfirm)) {
            $filtrationMenu++;
            $data->whereIn('Registration_type',$SMSconfirm);
        }

        if (!empty($SortByInternetConsumption)) {
            $filtrationMenu++;
            $data->orderByRaw('SUM(acctinputoctets+acctoutputoctets) DESC');
        }

        if (!empty($online)) {
            $filtrationMenu++;
            // check what is the table name we are searching in 
            if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){ $searchInTable = 'users_radacct'; }else{ $searchInTable = 'users'; }
            $data
                // ->select('users.u_id', 'users.u_name', 'users.u_uname', 'users.u_email', 'users.u_phone', 'users.suspend', 'users.Selfrules', 'users.created_at')
				// ->join($database.'.radacct', $database.'.radacct.u_id', '=', $database.'.users.u_id')
                // ->whereNull($database.'.radacct.acctstoptime');

				// ->select('u_id', 'u_name', 'u_uname', 'u_email', 'u_phone', 'suspend', 'Selfrules', 'created_at')
				// ->where(DB::raw(' EXISTS ( SELECT radacctid FROM '.$database.'.radacct WHERE radacct.u_id = '.$searchInTable.'.u_id AND radacct.acctstoptime IS NULL ) group by '.$searchInTable.'.u_id;'))
                ->where(DB::raw(' EXISTS ( SELECT radacctid FROM '.$database.'.radacct WHERE radacct.u_id = '.$searchInTable.'.u_id AND radacct.acctstoptime IS NULL group by radacct.u_id) group by '.$searchInTable.'.u_id;'))
               	->get();    
        }

        // check if admin using mix of frequency filter and SortByMostVisited filter (to avoid conflict bacause we already filterd visits `counts` before)
        if (!empty($SortByMostVisited) and isset($frequency)) {
            $filtrationMenu++;
            $data->orderBy('counts', 'DESC');
        }elseif(!empty($SortByMostVisited) and !isset($frequency)){
            $filtrationMenu++;
            $data->select(DB::raw('count(u_id) as counts, `u_id`, `u_name`, `u_uname`, `u_email`, `u_phone`, `suspend`, `Selfrules`, `created_at`, `pms_guest_id`'))->orderBy('counts', 'DESC');
        }
        
        // check if admin use any of this filters that using `users_radacct` table
        if(isset($frequency) or !empty($SortByMostVisited) or !empty($SortByInternetConsumption)){
            $data->groupBy('u_id');   
        }

        // --> end of filters

        
        
        if(Session::has('advancedReport')){

            // Disabled for page performance 23.5.2022
            $data = $data->get();
            $dataCounter= count($data);
            $today=date("Y-m-d");
            $yesterday=date("Y-m-d", strtotime( '-1 days' ) );
            // $firstDayMonth=date("Y-m")."-01";
            // $lastDayMonth=date('Y-m-t', strtotime($firstDayMonth));
            $subdomain = url()->full();
            $split = explode('/', $subdomain);
            $customerData =  DB::table('customers')->where('url',$split[2])->first();
            $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
            $justCounter=0;
            
            // Get no of visits and last visit for all users
            $allCountUsers=App\Models\UsersRadacct::select(DB::raw('* ,count(u_id) as visits'))->groupBy('u_id')->get();
            if(isset($allCountUsers))
            {
                foreach($allCountUsers as $userRecord)
                {
                    $visitsOf[$userRecord->u_id]=$userRecord->visits;
                    //if(!isset($lastVisitOf[$userRecord->u_id])){$lastVisitOf[$userRecord->u_id]=$userRecord->dates;}
                    $lastVisitOf[$userRecord->u_id]=$userRecord->dates;
                    //$up=$userRecord->acctinputoctets;
                    //$down=$userRecord->acctoutputoctets;
                    //if(!isset($totalOf[$userRecord->u_id])){$totalOf[$userRecord->u_id]=0;}
                    //else{$totalOf[$userRecord->u_id]+=$up+$down;}
                    //$totalOf[$userRecord->u_id]=$up+$down;
                }
            }

            // Get Group name
            $allGroups=App\Groups::where('as_system',"0")->get();
            if(isset($allGroups))
            {
                foreach($allGroups as $groupRecord)
                {$groupOf[$groupRecord->id]=$groupRecord->name;}
            }

            // Get Branch name
            $allBranchs=App\Branches::get();
            if(isset($allBranchs))
            {
                foreach($allBranchs as $branchRecord)
                {$branchOf[$branchRecord->id]=$branchRecord->name;}
            }

            // Get Online users
            $allOnlineUsers=App\Radacct::whereNull('acctstoptime')->get();
            if(isset($allOnlineUsers))
            {
                foreach($allOnlineUsers as $onlineRecord)
                {$onlineOf[$onlineRecord->u_id]=1;}
            }


            for ($i=0; $i<=$dataCounter; $i++)
            {
                if(isset($data[$i])){
                    
                    
                    // get Visits
                    if(isset($noOfVisits)){unset($noOfVisits);}
                    if(isset($visitsOf[$data[$i]->u_id]))
                    {$noOfVisits=$visitsOf[$data[$i]->u_id];}
                    else{$noOfVisits=0;}

                    // Get last visit
                    if(isset($lastVisit)){unset($lastVisit);}
                    if(isset($lastVisitOf[$data[$i]->u_id]))
                    {$lastVisit=$lastVisitOf[$data[$i]->u_id];}
                    else{$lastVisit="";}
                    if($today==$lastVisit){$lastVisit="<div class='text-success text-size-small'><span class='status-mark border-success position-left'></span> Today </div>";}
                    elseif($yesterday==$lastVisit){$lastVisit="<div class='text-blue text-size-small'><span class='status-mark border-blue position-left'></span> Yesterday </div>";}
                    elseif($lastVisit){$lastVisit="<div class='text-muted text-size-small'><span class='status-mark border-orange position-left'></span> $lastVisit </div>";}
                    

                    // // get Visits
                    // $allUsersSessions=App\Models\UsersRadacct::select(DB::raw('* ,count(u_id) as visits'))->where('u_id',$data[$i]->u_id)->first();
                    // $noOfVisits = $allUsersSessions->visits;
                    // // Get last visit
                    // if($noOfVisits > 0){
                    //     $getLastVisit = App\Radacct::where('u_id', $data[$i]->u_id)->orderBy('radacctid','desc')->first();
                    //     $lastVisit = $getLastVisit->dates;
                    // }else{
                    //     $lastVisit = "";
                    // }
                    // if($today==$lastVisit){$lastVisit="<div class='text-success text-size-small'><span class='status-mark border-success position-left'></span> Today </div>";}
                    // elseif($yesterday==$lastVisit){$lastVisit="<div class='text-blue text-size-small'><span class='status-mark border-blue position-left'></span> Yesterday </div>";}
                    // elseif($lastVisit){$lastVisit="<div class='text-muted text-size-small'><span class='status-mark border-orange position-left'></span> $lastVisit </div>";}

                    // Get Total
                    // if(isset($userTotal)){unset($userTotal);}
                    // if(isset($totalOf[$data[$i]->u_id]))
                    // {$userTotal=$totalOf[$data[$i]->u_id];}
                    // else{$userTotal=0;}
                    

                    // get GroupName
                    if(isset($groupName)){unset($groupName);}
                    if(isset($data[$i]->group_id) and isset($groupOf[$data[$i]->group_id]))
                    {$groupName=$groupOf[$data[$i]->group_id];}
                    else{$groupName="";}

                    // get BranchName
                    if(isset($branchName)){unset($branchName);}
                    if(isset($data[$i]->branch_id) and isset($branchOf[$data[$i]->branch_id]))
                    {$branchName=$branchOf[$data[$i]->branch_id];}
                    else{$branchName="";}

                    // Get OlineUsers
                    if(isset($onlineState)){unset($onlineState);}
                    if(isset($onlineOf[$data[$i]->u_id]))
                    {$onlineState=$onlineOf[$data[$i]->u_id];}
                    else{$onlineState="0";}
                    
                    // get the first day of renwing day to get monthly usage in GB
                    $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($customerData->database, $data[$i]->branch_id);
                    $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                    $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];   
                    
                    // Get Monthly Usage
                    $monthlyUsageTotal=App\Radacct::where('u_id',$data[$i]->u_id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum(DB::raw('acctinputoctets + acctoutputoctets'));
                    $monthlyTotalUsage=round(($monthlyUsageTotal)/1024/1024/1024,2);
                    // Get Total Usage
                    $usageTotal=App\Radacct::where('u_id',$data[$i]->u_id)->sum(DB::raw('acctinputoctets + acctoutputoctets')); // ~ 150 MS
                    $totalUsage=round(($usageTotal)/1024/1024/1024,2);

                    if ($online=="on") {// check on users who is Offline and remove it
                        if($onlineState==1){$canInsertThisUser=1;}// so user Online NOW...
                        else{$canInsertThisUser=0;}
                    }else{$canInsertThisUser=1;}

                    // split mobile number
                    $userMobile = $data[$i]->u_phone;
                    $userUname = $data[$i]->u_uname;
                    if( App\Settings::where('type', 'marketing_enable')->value('value') != 1 ){
                        $userMobile = substr($userMobile, 0, -8)."XXXX".substr($userMobile, -4);
                        $userUname = substr($userUname, 0, -8)."XXXX".substr($userUname, -4);
                    }

                    if($pmsIntegration == 1){
                        
                        // get no of stays
                        // $pmsStayDays = App\Models\UserTags::where('tag', 'pms_stay_nights')->where('pms_profile_id', $data[$i]->pms_guest_id)->select(DB::raw('SUM(value) as total_stay_nights'))->value('total_stay_nights');

                        // // get Reputations
                        // $pmsReputations = App\Models\UserTags::where('tag', 'pms_checkin_count')->where('pms_profile_id', $data[$i]->pms_guest_id)->orderBy('id', 'desc')->value('value');

                        // // get last check in
                        // $pmsLastCheckIn = App\Models\UserTags::where('tag', 'pms_checkin_date')->where('pms_profile_id', $data[$i]->pms_guest_id)->orderBy('id', 'desc')->value('value');

                        // // get last check out
                        // $pmsLastCheckOut = App\Models\UserTags::where('tag', 'pms_checkout_date')->where('pms_profile_id', $data[$i]->pms_guest_id)->orderBy('id', 'desc')->value('value');
                    }

                    // if(!isset($pmsStayDays)){$pmsStayDays="";}
                    if(!isset($pmsReputations)){$pmsReputations="";}
                    if(!isset($pmsLastCheckIn)){$pmsLastCheckIn="";}
                    if(!isset($pmsLastCheckOut)){$pmsLastCheckOut="";}
                    

                    if($canInsertThisUser==1)// so user Online NOW...
                    {// User Offline now so I will remove it from array
                    
                        $data2[$justCounter] = array('u_id'=>$data[$i]->u_id,
                        'Registration_type'=>$data[$i]->Registration_type,
                        'Selfrules'=>$data[$i]->Selfrules,
                        'branch_id'=>$data[$i]->branch_id,
                        'created_at'=>$data[$i]->created_at,
                        'credit'=>$data[$i]->credit,
                        'group_id'=>$data[$i]->group_id,
                        'network_id'=>$data[$i]->network_id,
                        'notes'=>$data[$i]->notes,
                        'sms_credit'=>$data[$i]->sms_credit,
                        'suspend'=>$data[$i]->suspend,
                        'token'=>$data[$i]->token,
                        'u_address'=>$data[$i]->u_address,
                        'u_card_date_of_charging'=>$data[$i]->u_card_date_of_charging,
                        'u_country'=>$data[$i]->u_country,
                        'u_email'=>$data[$i]->u_email,
                        'u_gender'=>$data[$i]->u_gender,
                        'u_lang'=>$data[$i]->u_lang,
                        'u_mac'=>$data[$i]->u_mac,
                        'u_name'=>$data[$i]->u_name,
                        'u_password'=>$data[$i]->u_password,
                        'u_phone'=>$userMobile,
                        'u_state'=>$data[$i]->u_state,
                        'u_uname'=>$userUname,
                        'last_visit'=>$lastVisit,
                        'visits'=>$noOfVisits,
                        'online_state'=>$onlineState,
                        'group_name'=> $groupName,
                        'branch_name'=>$branchName,
                        'monthly_usage'=> $monthlyTotalUsage,
                        'total_usage'=> $totalUsage,
                        // 'pmsStayDays'=> $pmsStayDays,
                        'pmsReputations'=> $pmsReputations,
                        'pmsLastCheckIn'=> $pmsLastCheckIn,
                        'pmsLastCheckOut'=> $pmsLastCheckOut,
                        'pms_guest_id'=>$data[$i]->pms_guest_id,
                        );
                        $justCounter++;
                        unset($lastVisit);
                        unset($canInsertThisUser);
                        unset($usageUpload);
                        unset($usageDownload);
                        unset($totalUsage);
                        unset($monthlyUsageUpload);
                        unset($monthlyUsageDownload);
                        unset($monthlyTotalUsage);
                        unset($userMobile);
                        unset($userUname);
                        // unset($pmsStayDays);
                        unset($pmsReputations);
                        unset($pmsLastCheckIn);
                        unset($pmsLastCheckOut);
                    }
                }

            }
            if(!isset($data2)){$data2=array();}// fix datatable no data error

            return array('aaData'=>$data2);
            
        }else{
            // check if admin select any filter from the left menu to view all users in database without limitation to be able to make a campaign
            if($filtrationMenu>0){
                // check if admin used any filter already select the only used coloumns
                if(isset($frequency) or !empty($SortByMostVisited) or !empty($online)){
                    $data = $data->get();
                }else{
                    $data = $data->select('u_id', 'u_name', 'u_uname', 'u_password', 'u_email', 'u_phone', 'suspend', 'Selfrules', 'created_at','pms_guest_id')->orderBy('created_at', 'desc')->get();
                }
            }else{
                $data = $data->select('u_id', 'u_name', 'u_uname', 'u_password', 'u_email', 'u_phone', 'suspend', 'Selfrules', 'created_at','pms_guest_id')->orderBy('created_at', 'desc')->limit(40000)->get();
            }
            
            if(!isset($data)){$data=array();} // enhance page performance 23.5.2022
            return array('aaData'=>$data); // enhance page performance 23.5.2022
        }
    }
    public function delete($id){
        date_default_timezone_set("Africa/Cairo");
        $this->logsdelete('delete single user '.$id);
        // remove this user id from any record in Hosts table to avoid any errors
        App\Models\Hosts::where('u_id',$id)->update(['u_id' => '0']);
        // remove his mac from active seccession in Mikrotik
        App\Radacct::where('u_id',$id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 

        // Insert log in history table to unblock user in Mikrotik in each branch
        $state = "unsuspend_user";
        $adminID = 400;
        $allUserMac = App\Users::where('u_id', $id)->value('u_mac');
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $time = date('H:i:s', strtotime(Carbon::now()));
        foreach(App\Branches::where('state', '1')->get() as $branch){
        
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = "suspend_unsuspend_user";
            $insert->type2 = "admin";
            $insert->operation = "$state";
            $insert->details = 1;
            $insert->notes = $allUserMac;
            $insert->a_id = $adminID;
            $insert->u_id = $id;
            $insert->branch_id = $branch->id;
            $insert->save();
        }
        // delete user from DB
       App\Users::where('u_id', '=', $id)->delete();
    }

    public function deletes($ids){

        date_default_timezone_set("Africa/Cairo");
        $this->logsdelete('delete more than one '.$ids,count(explode(',',$ids)));

        $ids = explode(',',$ids);
        

        // $ids = explode(',',$ids);
        // remove this user id from any record in Hosts table to avoid any errors
        App\Models\Hosts::whereIn('u_id',$ids)->update(['u_id' => '0']);
        // remove his mac from active seccession in Mikrotik
        App\Radacct::whereIn('u_id',$ids)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']);
        // Insert log in history table to unblock user in Mikrotik in each branch
        $state = "unsuspend_user";
        $adminID = 400;
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $time = date('H:i:s', strtotime(Carbon::now()));
        foreach($ids as $id){
            $allUserMac = App\Users::where('u_id', $id)->value('u_mac');
            foreach(App\Branches::where('state', '1')->get() as $branch){
            
                $insert = new History();
                $insert->add_date = $date;
                $insert->add_time = $time;
                $insert->type1 = "suspend_unsuspend_user";
                $insert->type2 = "admin";
                $insert->operation = "$state";
                $insert->details = 1;
                $insert->notes = $allUserMac;
                $insert->a_id = $adminID;
                $insert->u_id = $id;
                $insert->branch_id = $branch->id;
                $insert->save();
            }
        }
        // delete user from DB
       App\Users::whereIn('u_id', $ids)->delete();
    }

    PUBLIC function logsdelete( $logMessage,$count = -1){

        $date = date('Y-m-d_H-i-s');

        $filePath = '/home/hotspot/public_html/public/logs/';
        $fileName = $filePath .'log_' . $date . '.|'.$count.'|.txt';

        // Open the file for appending
        $file = fopen($fileName, 'a');

        // Write the log message to the file with a timestamp
        fwrite($file, date('Y-m-d H:i:s') . '- user:'.Auth::user()->id.' - ' . $logMessage . "\n");

        // Close the file
        fclose($file);
    }

    public function suspend($id,$v, $bot=null){
        $v = ($v == 'true')? 0 : 1;
        // $adminID = ($bot == null) ? Auth::user()->id : 400;
        $adminID = 400;
        // Update state into user record
        App\Users::where('u_id', '=', $id)->update(['suspend'=>$v]);

        // Insert log in history table to block user in Mikrotik in each branch
        $state = ($v == '1')? "suspend_user" : "unsuspend_user";
        $allUserMac = App\Users::where('u_id', $id)->value('u_mac');
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $time = date('H:i:s', strtotime(Carbon::now()));
        foreach(App\Branches::where('state', '1')->get() as $branch){
        
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = "suspend_unsuspend_user";
            $insert->type2 = "admin";
            $insert->operation = "$state";
            $insert->details = 1;
            $insert->notes = $allUserMac;
            $insert->a_id = $adminID;
            $insert->u_id = $id;
            $insert->branch_id = $branch->id;
            $insert->save();
        }
        
        // Disconnect online session
        $getUserData = App\Radacct::where('u_id',$id)->whereNull('acctstoptime')->orderBy('radacctid', 'desc')->first(); 
        if(isset($getUserData)){
            $radacct_id = $getUserData->radacctid;
            $geted_User_Name = $getUserData->username;
            $geted_Framed_IP_Address = $getUserData->framedipaddress;
            $geted_nasipaddress = $getUserData->nasipaddress;
            $geted_branch_id = $getUserData->branch_id;
            $geted_u_id = $getUserData->u_id;
            $acctuniqueid = $getUserData->acctuniqueid;

            //Branch Data
            $getbranchdata = App\Branches::where('id',$geted_branch_id)->first();
            $geted_secret = $getbranchdata->Radiussecret;
            $coaport = $getbranchdata->Radiusport;
            $ip = $getbranchdata->ip;
            $radiusType = $getbranchdata->radius_type;
            
            if($radiusType == "mikrotik"){
                App\Radacct::where('acctuniqueid',$acctuniqueid)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
            }else{
                // disconnect user from shell if branch type Aruba or DDWRT
                $beExecuted='echo User-Name='.$geted_User_Name.',Framed-IP-Address='.$geted_Framed_IP_Address.' | radclient -x '.$ip.':'.$coaport.' disconnect '.$geted_secret.'  2>&1 ';
                exec($beExecuted, $output);
            }
        }
    }

    public function campaign(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $ids = $request['ids'];
        $countNew = count($ids);
        $ip     = $this->getClientIP();
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $time = date('H:i:s', strtotime(Carbon::now()));
        if($request['email_From'] || $request['email_subject']){
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "email_campaign";
            $insert->details = $ip;
            $insert->notes = "";
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }
        if($request['sms_sender'] || $request['sms_message']){
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "SMS_campaign";
            $insert->details = $ip;
            $insert->notes = "";
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }
        if($request['push_head'] && $request['push_body']){
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "pushNotification_campaign";
            $insert->details = $ip;
            $insert->notes = "";
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }
        if($request['local_from'] || $request['local_message']){
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "localMessages_campaign";
            $insert->details = $ip;
            $insert->notes = "";
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }


        for($i = 0; $i < $countNew; $i++) {
            $data = App\Users::where('u_id', $ids[$i]);
            $data = $data->get();
            if($request['email_From'] && $request['message']){
                foreach ($data as $data1) {
                    if($data1->u_email and filter_var($data1->u_email, FILTER_VALIDATE_EMAIL)) {
                        
                        $content = $request['message'];
                        $from = $request['email_From'];
                        $subject = $request['email_subject'];

                        Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($data1, $from, $subject) {
                            $message->from($from, App\settings::where('type','Hotspot')->value('value'));
                            $message->to($data1->u_email, $data1->u_name)->subject($subject);
                        });
                    }
                }

            }
            if($request['sms_sender'] && $request['sms_message']){

                foreach($data as $data1) {
                        if($data1->u_phone) {
                            $to = $data1->u_phone;
                            $sendmessage = new App\Http\Controllers\Integrations\SMS();
                            $sendmessage->send($to, $request['sms_message'],$request['sms_sender']);
                            
                            // // send whatsapp with SMS 11/9/2019 // replaced with local messages
                            // $MessageEncoded = urlencode($request['sms_message']);
                            // $split = explode('/', url()->full());
                            // $customerData = DB::table('customers')->where('url',$split[2])->first();
                            // $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                            // $sendWhatsappMessage->send( "", $to , $MessageEncoded, $customerData->id, $customerData->database, "", $request['sms_sender'],"","1");
                        }
                }

            }
            if($request['push_head'] && $request['push_body']){
                foreach($data as $data1){
                    $deviceToken = $data1->deviceToken;
                    if($deviceToken){
                        $message = PushNotification::Message($request['push_content'],array(
                            'badge' => 1,
                            'sound' => 'example.aiff',

                            'actionLocKey' => $request['push_head'],
                            'locKey' => 'localized key',
                            'locArgs' => array(
                                'localized args',
                                'localized args',
                            ),
                            'launchImage' => 'image.jpg',

                            'custom' => array('custom data' => array(
                                'we' => 'want', 'send to app'
                            ))
                        ));

                        PushNotification::app('appNameAndroid')
                            ->to($deviceToken)
                            ->send($message);
                    }
                }

            }
            if($request['local_message']){
                
                // converted to whatsapp, insert WhatsApp standaloneMessage text into "history" table
                $standaloneMsgID = App\History::insertGetId(['operation' => 'standaloneMessage', 'type1' => 'hotspot', 'type2' => 'admin', 'details' => $request['local_message'], 'add_date' => $date, 'a_id' => Auth::user()->id, 'add_time' => $time]);
                
                foreach($data as $data1){
                    
                    // insert message text into "history" table to schedule sending message later
                    App\Models\WhatsappCampaign::insert([['state' => '0', 'user_id' => $data1->u_id, 'message_id' => $standaloneMsgID, 'created_at' => $date." ".$time]]);

                    $userid =  $data1->u_id;
                    $insert = new Messages();
                    $insert->u_id = $userid;
                    $insert->name = $request['local_from'];
                    $insert->subject = $request['local_subject'];
                    $insert->message = $request['local_message'];
                    $insert->state = 1;
                    $insert->save();
                }
            }
        }
        echo response()->json(['message' => 'Request completed']);
    }

    public function campaignAI(Request $request){
        
        // prepare static VARS
        date_default_timezone_set("Africa/Cairo");
        $ids = $request['ids'];
        $countNew = count($ids);
        $ip     = $this->getClientIP();
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $time = date('H:i:s', strtotime(Carbon::now()));
        $created_at = $date." ".date("H:i:s");
        $cronScheduleHotelGuestNotificationsStep1 = app()->make('App\Http\Controllers\CronScheduleHotelGuestNotificationsStep1');
        $split = explode('/', url()->full());
        $customerData = DB::table('customers')->where('url',$split[2])->first();
       
        // create history notification
        if($request['email_message']){
            $request['email_message'] = str_replace("\n",", ",$request['email_message']);
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "email_ai_campaign";
            $insert->details = $ip;
            $insert->notes = $request['email_message'];
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }
        if($request['sms_message']){
            $request['sms_message'] = str_replace("\n",", ",$request['sms_message']);
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "SMS_ai_campaign";
            $insert->details = $ip;
            $insert->notes = $request['sms_message'];
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }
        if($request['push_head'] && $request['push_body']){
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "pushNotification_ai_campaign";
            $insert->details = $ip;
            $insert->notes = $request['push_body'];
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }
        if( $request['whatsapp_message']){
            $request['whatsapp_message'] = str_replace("\n",", ",$request['whatsapp_message']);
            $insert = new History();
            $insert->add_date = $date;
            $insert->add_time = $time;
            $insert->type1 = Settings::where('type', 'app_name')->value('value');
            $insert->type2 = "admin";
            $insert->operation = "WhatsappMessages_ai_campaign";
            $insert->details = $ip;
            $insert->notes = $request['whatsapp_message'];
            $insert->a_id = Auth::user()->id;
            $insert->save();
        }
        
        // for-loop for each user
        for($i = 0; $i < $countNew; $i++) {  
            $userData = App\Users::where('u_id', $ids[$i])->first();
            // insert Email scheduled notifications
            if($request['email_message']){
                if($cronScheduleHotelGuestNotificationsStep1->validateEmails($userData->u_email) >= '1'){
                    App\Models\ScheduledNotifications::insert([['state' => '1', 'type' => '1', 'database' => $customerData->database, 'u_id' => $userData->u_id, 'email' => $userData->u_email, 'chatgpt_content' => $request['email_message'], 'by' => 'AIcampaignByAdminUsingUsersPage', 'reason'=>"On-spot Email By Admin Using Users Page for $userData->u_name `$userData->u_id` at $created_at.", 'created_at'=>$created_at]]);
                }
            }

            // insert SMS scheduled notifications
            if($request['sms_message']){
                if($cronScheduleHotelGuestNotificationsStep1->validateMobiles($userData->u_phone) >= '1'){
                    App\Models\ScheduledNotifications::insert([['state' => '1', 'type' => '3', 'database' => $customerData->database, 'u_id' => $userData->u_id, 'mobile' => $userData->u_phone, 'chatgpt_content' => $request['sms_message'], 'by' => 'AIcampaignByAdminUsingUsersPage', 'reason'=>"On-spot SMS By Admin Using Users Page for $userData->u_name `$userData->u_id` at $created_at.", 'created_at'=>$created_at]]);
                }
            }
            
            // insert WhatsApp scheduled notifications
            if($request['whatsapp_message']){
                if($cronScheduleHotelGuestNotificationsStep1->validateMobiles($userData->u_phone) >= '1'){
                    App\Models\ScheduledNotifications::insert([['state' => '1', 'type' => '2', 'database' => $customerData->database, 'u_id' => $userData->u_id, 'mobile' => $userData->u_phone, 'chatgpt_content' => $request['whatsapp_message'], 'by' => 'AIcampaignByAdminUsingUsersPage', 'reason'=>"On-spot WhstsApp By Admin Using Users Page for $userData->u_name `$userData->u_id` at $created_at.", 'created_at'=>$created_at]]);
                }
            }
            
            // still not handeld 27.4.2023
            if($request['push_head'] && $request['push_body']){
                $deviceToken = $userData->deviceToken;
                if($deviceToken){
                    $message = PushNotification::Message($request['push_content'],array(
                        'badge' => 1,
                        'sound' => 'example.aiff',

                        'actionLocKey' => $request['push_head'],
                        'locKey' => 'localized key',
                        'locArgs' => array(
                            'localized args',
                            'localized args',
                        ),
                        'launchImage' => 'image.jpg',

                        'custom' => array('custom data' => array(
                            'we' => 'want', 'send to app'
                        ))
                    ));

                    PushNotification::app('appNameAndroid')->to($deviceToken)->send($message);
                }
            }

        }
        return response()->json(['message' => 'Request completed']);
    }

    protected function convertDate($date){
        $date_array = explode("/",$date); // split the array
        $var_day = $date_array[0]; //day seqment
        $var_month = $date_array[1]; //month segment
        $var_year = $date_array[2]; //year segment
        return "$var_year-$var_month-$var_day";
    }
    public function add_search_result(Request $request)
    {
        $actions = new App\SearchResult();
        $actions->title = ucfirst(strtolower($request['title']));
        $actions->link = url()->previous();
        $actions->created = $request['created'];
        $actions->save();

        return redirect()->route('search');
    }
    public  function edit_search_result($id){

        $edit = SearchResult::find($id);
        $edit->edit();

        return redirect()->route('search');

    }
    public  function delete_search_result($id){
        $this->logsdelete('delete_search_result '.$id);

        $delete = App\SearchResult::where('id',$id)->first();
        $delete->delete();

        return redirect()->route('search');
    }
    public function getgruop($id){
        $data = App\Groups::where('network_id',$id)->where('as_system',0)->get();
        return $data;
    }
    protected function IsItUnicode($msg){
        $unicode=0;
        $str = "ط¯ط¬ط­ط®ظ‡ط¹ط؛ظپظ‚ط«طµط¶ط·ظƒظ…ظ†طھط§ظ„ط¨ظٹط³ط´ط¸ط²ظˆط©ظ‰ظ„ط§ط±ط¤ط،ط¦ط¥ظ„ط¥ط£ظ„ط£ط¢ظ„ط¢";
        for($i=0;$i<=strlen($str);$i++)
        {
            $strResult= substr($str,$i,1);
            for($R=0;$R<=strlen($msg);$R++)
            {
                $msgResult= substr($msg,$R,1);
                if($strResult==$msgResult && $strResult)
                    $unicode=1;
            }
        }

        return $unicode;
    }
    public function downloadFullExceldata($type)
    {
        $data = App\Users::get()->toArray();
        return Excel::create('Fulldata', function($excel) use ($data) {

            $excel->sheet('Sheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });

        })->download($type); redirect()->route('search');
    }
    public function downloaddemoExcel($type)
    {
        $data = App\Users::take(1)->get()->toArray();
        return Excel::create('Example', function($excel) use ($data) {

            $excel->sheet('Sheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });

        })->download($type); redirect()->route('search');
    }
    public function importExcel()
    {
        Excel::load($pathfile = base_path() . '/public/catalog/'.'users.xlsx', function($reader) {
            $reader->each(function($sheet) {
                echo $sheet->registration_type;
            });
        });
    }
    public function userid($id){ // Clicked edit user from search page or from online users page
        $permissions = ['dashboard' => 'Dashboard','users' => 'Users','onlineusers' => 'Online users','networks' => 'Networks','groups' => 'Groups','branches' => 'Branches','administration' => 'Administration','packages' => 'Packages','cards' => 'Cards','settings' => 'Settings','landingpages' => 'Landing Pages'];
        $data = App\Users::find($id);
        $groups = App\Groups::find($data->group_id);
        if(isset($groups)){

            $speed_limit = explode("/" , $groups->speed_limit);
            $end_speed = explode("/" , $groups->end_speed);
            $speed_limit_counts = count($speed_limit);
            $end_speed_counts = count($end_speed);
            $equationStartSpeed="";
            $equationEndSpeed="";

            if(isset($groups->speed_limit) && $speed_limit_counts > "2"){
                $equationStartSpeed="1";
                $groups->speed_limit;
            }else{
                $groups->speed_limit = $this->conve($groups->speed_limit);
            }

            if(isset($groups->end_speed) && $end_speed_counts > "2"){
                $equationEndSpeed="1";
                $groups->end_speed;
            }else{
                $groups->end_speed = $this->conve($groups->end_speed);
            }

            /*
            if(isset($groups->speed_limit)){
                $groups->speed_limit = $this->conve($groups->speed_limit);
            }else{
                
                $groups->speed_limit[0] = 0;
                $groups->speed_limit[1] = 'K';
                $groups->speed_limit[2] = 0;
                $groups->speed_limit[3] = 'K';
                
            }
            if(isset($groups->end_speed)){
                $groups->end_speed = $this->conve($groups->end_speed);
            }else{
                
                $groups->end_speed[0] = 0;
                $groups->end_speed[1] = 'K';
                $groups->end_speed[2] = 0;
                $groups->end_speed[3] = 'K';
                
            }
            */        
            $countries = array(
                'Egypt' => 'Egypt',
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
                'Israel' => 'Israel',
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

            // get total visits
            $visits = App\Models\UsersRadacct::where('u_id', $data->u_id)->select(DB::raw('count(u_id) as visits'))->value('visits');
            // get total consumption
            $totalQuotaConsumptionAllMonths = App\Models\UsersRadacct::where('u_id', $id)->select(DB::raw('SUM(acctinputoctets+acctoutputoctets) as total_consumption'))->value('total_consumption');

            $checked = ($data->Selfrules) ? 'checked':' ';
            return view('back-end.user.profile',array(
                'user' => App\Users::find($id),
                'networks' => App\Network::all(),
                'branches' => App\Branches::all(),
                'groups' => $groups,
                'equationStartSpeed' => $equationStartSpeed,
                'equationEndSpeed' => $equationEndSpeed,
                'checked' => $checked,
                'countries' => $countries,
                'url' => URLFilter::where('group_id', $data->group_id)->get(),
                'visits' => $visits,
                'totalQuotaConsumptionAllMonths' => $totalQuotaConsumptionAllMonths
            ));
        }
    }
    
    public function upload_excel(Request $request)
    {
       $name = $request->input('file');
        
        $filename = 'users.' . 
        $request->file('file')->getClientOriginalExtension();
        $pathfile = base_path() . '/public/catalog/';    
        $request->file('file')->move(
            $pathfile , $filename
        );
        Excel::load($pathfile .$filename, function($reader) {
            $reader->each(function($sheet) {
                /*
                $actions = App\Users::find($sheet->u_id);
                if(!isset($actions->u_id)){
                    $actions = new App\Users();
                }
                $actions->u_name = $sheet->u_name;
                $actions->u_uname = $sheet->u_uname;
                $actions->u_phone = $sheet->u_phone;
                $actions->u_email = $sheet->u_email;
                $actions->u_mac = $sheet->u_mac;
                $actions->u_address = $sheet->u_address;
                $actions->u_password = $sheet->u_password;
                $actions->credit = $sheet->credit;
                $actions->sms_credit = $sheet->sms_credit;
                if($sheet->suspend==1){$actions->suspend=$sheet->suspend;}else{$actions->suspend='0';}
                //$actions->u_canuse = $sheet->u_canuse;
                if($sheet->u_state==1){$actions->u_state=$sheet->u_state;}else{$actions->u_state='0';}
                if($sheet->u_gender==1){$actions->u_gender=$sheet->u_gender;}else{$actions->u_gender='0';}
                //$actions->u_state = $sheet->u_state;
                //$actions->u_gender = $sheet->u_gender;
                $actions->notes =$sheet->notes;
                $actions->branch_id =$sheet->branch_id;
                $actions->network_id =$sheet->network_id;
                $actions->group_id =$sheet->group_id;
                $actions->u_card_date_of_charging =$sheet->u_card_date_of_charging;
                $actions->u_lang = $sheet->u_lang;
                $actions->u_country = $sheet->u_country;
                if($sheet->registration_type==1){$actions->registration_type="1";}
                elseif($sheet->registration_type==2){$actions->registration_type='2';}
                else{$actions->registration_type='0';}
                if($sheet->Selfrules==1){$actions->Selfrules=$sheet->Selfrules;}else{$actions->Selfrules='0';}
                // $actions->provider = $sheet->provider;
                // $actions->provider_id = $sheet->provider_id;
                //$actions->registration_type = $sheet->registration_type;
                //$actions->u_password = $sheet->u_address;
                $actions->save();
                */
                if(isset($sheet->username) and $sheet->username!=""){   
                    $newUser = new App\Users();
                    $newUser->Registration_type ='2';
                    $newUser->u_state = '1';
                    $newUser->suspend = '0';
                    $newUser->u_email = ' ';
                    $newUser->network_id = '2';
                    $newUser->u_name =$sheet->name;
                    $newUser->u_uname =$sheet->username;
                    $newUser->u_password =$sheet->password;
                    $newUser->branch_id =$sheet->branch_id;
                    $newUser->group_id =$sheet->group_id;
                    $newUser->u_mac =$sheet->mac;
                    $newUser->save();
                }
            });
        });
    }
    private function conve($string){
	    $arr = [];
	    $strings = (explode("/",$string));
	    foreach($strings as $str){
		    $arr[] = preg_replace("/[^0-9]/", '',$str);
		    $arr[] = substr($str, -1);
	    }
        if(!isset($arr[3])){
            $arr[0] = 0;
            $arr[1] = 'K';
            $arr[2] = 0;
            $arr[3] = 'K';
        }
	    return $arr;
    }
    public function getClientIP()
    {
        $ip = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ip = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ip = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ip = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ip = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ip = getenv('REMOTE_ADDR');
        else
            $ip = 'UNKNOWN';

        return $ip;
    }

    public function charagepackages($id){
        return view('back-end.user.packages',['id' => $id, 'packages' => App\Models\Packages::all()]);
    }

    // Admin clicked on advancedReport button in users page
    public function advancedReport(){
        if(Session::has('advancedReport')){
            Session::forget('advancedReport');
            return redirect()->route('search');
        }else{
            Session::push('advancedReport', '1');
            return redirect()->route('search');
        }
    }

    // Admin clicked on Change users group in search page
    public function bulkGroupSwitch(Request $request)
    {
        // return $request->data;
        foreach(explode(',',$request->data) as $userId){
            App\Users::where('u_id',$userId)->update(['group_id' => $request->targetGroupSwitch]);
        }
        return 'done';

    }
    
}
