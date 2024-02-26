<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Hash;
use Session;
use Input;
use Validator;
use Auth;
use Redirect;
use App\Models\SearchVisitedSites;
use DB;
use Carbon\Carbon;

class VisitedSitesController extends Controller
{
	public function index()
    {	
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
                'Zimbabwe' => 'Zimbabwe');
        return view('back-end.visitedsites.index', ['networks' => App\Network::all(),
	        'branches' => App\Models\BranchCount::get(),
	        'area_groups' => App\Models\GroupsCount::get(),
	        'genders' => App\Models\Gender::get(),
	        'countries' => $countries,
	        'countrys' => App\Models\CountryCount::get(), 'searchresults' => App\SearchResult::orderBy('id', 'desc')->get() ]);	
    }
    
    public function getDobAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function search(Request $request){
	   	$database =  app('App\Http\Controllers\Controller')->configuration();
	   
	   	$networks = $request->networks;
	   	$groups = $request->groups;
	   	$branches = $request->branches;
	   	$userid = $request->userid;
	   	$username = $request->username;
	   	$name = $request->name;
	   	$mac = $request->mac;
	   	$phone = $request->phone;
	   	$email = $request->email;
	   	$connection_type = $request['connection-type'];
	   	$protocol = $request->protocol;
	   	$source_address = $request['source-address'];
	    $source_port = $request['source-port'];
	    $destination_address = $request['destination-address'];
	    $destination_port  = $request['destination-port'];
	    $country = $request->country;
	    $unknown = $request->unknown == 'on' ? '0' : '0';
	    $male = $request->male == 'on' ? '1' : '0';
	    $female = $request->female == 'on' ? '2' : '0';



	    $data = DB::table($database.'.visited_sites_full');
	    
	    if (!empty($groups)) {
	        foreach ($groups as $key => $value) {
                $data->where('group_id', $value);
            }
	    }
        if (!empty($branches)) {
    	    foreach ($branches as $key => $value) {
                $data->where('branch_id', $value);
            }
        }
		if (!empty($networks)) {
	       $data->where('network_id', $networks);
	    }
	    if (!empty($userid)) {
	        $data->where('u_id', $userid);
	    }
	    if (!empty($username)) {
	        $data->where('username','like', '%'.$username.'%');
	    }
	    if (!empty($name)) {
	        $data->where('name','like', '%'.$name.'%');
	    }
	    if (!empty($email)) {
	        $data->where('mail','like', '%'.$email.'%');
	    }
	    if (!empty($phone)) {
	        $data->where('mobile','like', '%'.$phone.'%');
	    }

	    if (!empty($mac)) {
	        $data->where('mac','like', '%'.$mac.'%');
	    }
	    if (!empty($connection_type)) {
	        //return $connection_type;
           $data->where('type', $connection_type);
	    }
	    if (!empty($protocol)) {
	        $data->where('protocol','like', '%'.$protocol.'%');
	    }
	    if (!empty($source_address)) {
	        $data->where('src_ip', $source_address);
	    }
	    if (!empty($source_port)) {
	        $data->where('src_port', $source_port);
	    }
	    if (!empty($destination_address)) {
	        $data->where('dst_ip', 'like', '%'.$destination_address.'%');
	    }
	    if (!empty($destination_port)) {
	        $data->where('dst_port', $destination_port);
	    }

	    
	    if (!empty($male)) {
	        $data->where('gender', $male);
	    }
	    if (!empty($female)) {
	        $data->where('gender', $female);
	    }
	    if (!empty($unknown)) {
	        $data->where('gender', $unknown);
	    }

	    if (!empty($country)) {
	        $data->where('country', $country);
	    }

		
        if(isset($request['visitdate-from']) and $request['visitdate-from'] != ""){
	        $data->whereBetween('ReceivedAt',[$this->getDobAttribute($request['visitdate-from']).' 00:00:01', $this->getDobAttribute($request['visitdate-to']).' 23:59:59']);
	    }

        if(isset($request['session-start-from']) and $request['session-start-from'] != ""){
            $data->whereBetween('acctstarttime',[$this->getDobAttribute($request['session-start-from']).' 00:00:01', $this->getDobAttribute($request['session-start-to']).' 23:59:59']);
        }

        if(isset($request['session-end-from']) and $request['session-end-from'] != ""){
            $data->whereBetween('acctstoptime',[$this->getDobAttribute($request['session-end-from']).' 00:00:01', $this->getDobAttribute($request['session-end-to']).' 23:59:59']);
        }

	    $data = $data->orderBy('radacctid', 'desc')->limit(1000)->get();
		if(!empty($data)){
			foreach ($data as $value) {
	            // get mac address
	            $macValue=explode('src-mac ',$value->Message);
	            $macValue=explode(',',$macValue[1]);
	            $macRecord=$macValue[0];
	            // get connection type
	            $typeValue=explode('in:',$value->Message);
	            $typeValue2=explode(' ',$typeValue[1]);
	            if($typeValue2=="IN"){$connType="Inbound";}else{$connType="Outgoing";}
	            // get protocol
	            $protocolValue=explode('proto ',$value->Message);
	            $protocolValue=explode(', ',$protocolValue[1]);
	            $protocol=$protocolValue[0];
	            if (strpos($protocolValue[1],")") !== false) {
	            // found
	            $protocol=$protocolValue[0].$protocolValue[1];
	            $protocolValue[1]=$protocolValue[2];
	            }
	            // get src address and port
	            $srcipTypeValue=explode('->',$protocolValue[1]);
	            $srcipTypeValue=explode(':',$srcipTypeValue[0]);
	            $src_ip=$srcipTypeValue[0];
	            if(isset($srcipTypeValue[1])){$src_port=$srcipTypeValue[1];}else{$src_port="";}
	            // get dst address and port
	            $dstipTypeValue=explode('->',$protocolValue[1]);
	            $dstipTypeValue=explode(':',$dstipTypeValue[1]);
	            $dst_ip=$dstipTypeValue[0];
	            if(isset($dstipTypeValue[1])){$dst_port=$dstipTypeValue[1];}else{$dst_port="";}
	            
                if($value->gender == '1'){$gender="Male";} 
                elseif($value->gender == '0'){$gender="Female";} 
                else{$gender="Undefined";}
	            $logs[] = ['Name'=> $value->name,'Username'=> $value->username,'Email' => $value->mail, 'Mobile'=> $value->mobile, 'Gender' => $gender, 'Country' => $value->country, 'Id' => $value->u_id, 'FirstVisit' =>  $value->ReceivedAt, 'LastVisit' =>  $value->last_visit, 'VisitsCount' =>  $value->visits_count, 'MacAddress' => $macRecord, 'Connection Type' => $connType, 'Protocol' => $protocol,  'Srcaddress' => $src_ip,  'Srcport' => $src_port,  'Dstaddress' => $dst_ip,  'Dstport' => $dst_port,  'Sessionstarted' => $value->acctstarttime, 'Sessionended' => $value->acctstoptime];   
	        }
		    return $logs;
		}
    }
} 