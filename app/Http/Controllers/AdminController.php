<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Admins;
use Input;
use DB;
use Validator;
use Auth;
use App\Branches;
use Carbon\Carbon;
use App;
use Identify;
use Image;

class AdminController extends Controller
{

    public function Index(){
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['administration'] == 1){
            return view('back-end.admin.index',['branches'=> Branches::all()]);
        }else{
            return view('errors.404');
        }
    }


    public function profile(){
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
        return view('back-end.admin.profile',['country' => $country]);
    }
    public function Data(){
        $admin_data = Admins::all();

        $alldata=array();
        $counter = 0;

        foreach ($admin_data as $data) {
            $payment =  App\History::where('operation','reseller_payment')->where('reseller_id',$data->id)->sum('details');
            if($payment == null and $data->credit == null){
                $credit = "0";
                $payment = "0";
            }else{
                $credit = $data->credit;
            }
            $remaining = $payment - $credit;
            $alldata[$counter] = ['id' => $data->id, 'name' => $data->name, 'mobile' => $data->mobile, 'address' => $data->address, 'email' => $data->email, 'payment' => $payment, 'credit' => $credit, 'type' => $data->type, 'remaining' => $remaining];
            $counter++;
        }
        return array('aaData'=>$alldata);
    }
    public function register(Request $data) {

        $admins = new Admins();
        $admins->name = $data['name'];
        $admins->email = $data['email'];
        $admins->uname = $data['password'];
        if($data['password'] == $data['password_confirmation']){
            $admins-> password= bcrypt($data['password']);
        }else{
            return redirect()->route('admins');
        }
        $admins->type = $data['type'] == 'on' ? '1' : '2';

        $branches = $data['branches'];
        if(isset($branches)){
            $branche = implode(",",$branches);
            $admins->branches = $branche;
        }

        $permissions = Input::get('permissions');
        if(isset($permissions)){
            $permission= implode(",",$permissions);
            $admins->permissions = $permission;
        }

        $admins->mobile = $data['phone'];
        $admins->address = $data['address'];
        $admins->gender = $data['gender'];
        $admins->notes = $data['notes'];
        $admins->save();

        return redirect()->route('admins');
    }
    public function edit($id) {

        $admins = Admins::find($id);
        $admins->name = Input::get('name');
        $admins->email = Input::get('email');
        $admins->uname = Input::get('password');
        if(Input::get('password') ){
            $admins->password = bcrypt(Input::get('password'));
        }
        // if($admins->password != Input::get('password') and Input::get('password')!=""){
        //     $admins->uname = Input::get('password');
        //     $admins->password = bcrypt(Input::get('password'));
        // }
        $branches = Input::get('branches');
        if(isset($branches)){
            $branche = implode(",",$branches);
            $admins->branches = $branche;
        }

        $permissions = Input::get('permissions');
        if(isset($permissions)){
            $permission= implode(",",$permissions);
            $admins->permissions = $permission;
        }

        $admins->type = Input::get('type');
        $admins->mobile = Input::get('phone');
        $admins->address = Input::get('address');
        $admins->gender = Input::get('gender');
        $admins->notes = Input::get('notes');
        $admins->update();

        return redirect()->route('admins');
    }


    public function getadmin($id){
        
        return view('back-end.admin.edit',['admins' => Admins::find($id),'branches'=> Branches::all()]);
    }
    public function Delete($id){

        $delete = Admins::where('id',$id)->first();
        $delete->delete();

        return redirect()->route('admins');
    }

    //Reseller Cards
    public function reseller_cards($id){
        $reseller_cards = App\History::where('operation','reseller_cards_package')->where('reseller_id',$id)->get();

        return view('back-end.admin.cards',['id'=>$id, 'resellers' => $reseller_cards]);
    }
    public function reseller_add_cards(Request $request){
        $dt = Carbon::now();

        App\History::insert(
            ['type1' => 'hotspot', 'type2' => 'admin', 'a_id' => $request['admin_id'], 'reseller_id' => $request['reseller_id'],'operation' => 'reseller_cards_package', 'details' => $request['to'].';'.$request['from'], 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString(),'notes' => '1']
        );
        return redirect()->route('admins');
    }
    public function reseller_delete_cards($id){
        $delete = App\History::where('reseller_id',$id)->where('operation','reseller_cards_package')->get();
        $delete->delete();
        return redirect()->route('admins');
    }

    //Reseller Credit
    public function reseller_credit($id){
        $reseller_credit = App\History::where('operation','reseller_credit')->where('reseller_id',$id)->get();
        return view('back-end.admin.credit',['id'=>$id, 'resellers' => $reseller_credit]);
    }
    public function reseller_add_credit(Request $request){
        $dt = Carbon::now();

        $reseller_credit = App\Admins::where('id',Input::get('reseller_id'))->value('credit');
        $new_credit =  $reseller_credit + Input::get('credit');

        App\Admins::where('id', Input::get('reseller_id'))
            ->update(['credit' =>  $new_credit]);

        App\History::insert(
            ['type1' => 'hotspot', 'type2' => 'admin', 'a_id' => $request['admin_id'], 'reseller_id' => $request['reseller_id'],'operation' => 'reseller_credit', 'details' => Input::get('credit'), 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString(),'notes' => '1']
        );
        return redirect()->route('admins');
    }
    public function reseller_delete_credit($id, $reseller_id, $credit){
        App\History::where('reseller_id',$reseller_id)
            ->where('id',$id)->where('operation','reseller_credit')->delete();

        $reseller_credit = App\Admins::where('id',$reseller_id)->value('credit');
        $new_credit = ($reseller_credit - $credit );
        App\Admins::where('id', $reseller_id)
            ->update(['credit' =>  $new_credit]);
    }

    //Reseller revenue payment
    public function reseller_payment($id){
        $reseller_payment = App\History::where('operation','reseller_payment')->where('reseller_id',$id)->get();
        return view('back-end.admin.payment',['id'=>$id, 'resellers' => $reseller_payment]);
    }

    public function reseller_add_payment(Request $request){
        $dt = Carbon::now();

        App\History::insert(
            ['type1' => 'hotspot', 'type2' => 'admin', 'a_id' => $request['admin_id'], 'reseller_id' => $request['reseller_id'],'operation' => 'reseller_payment', 'details' => Input::get('payment'), 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString(),'notes' => '1']
        );
        return redirect()->route('admins');
    }

    public function reseller_delete_payment($id){
        App\History::where('id',$id)
            ->where('operation','reseller_payment')->delete();
    }
    public function changepassword(){
        if(Input::get('password') == Input::get('confirmpassword')){
            App\Admins::where('id', Auth::user()->id)
                ->update(['password' =>  bcrypt(Input::get('password'))]);
        }else{
            return redirect()->route('myprofile');
        }
        return redirect()->route('myprofile');

    }
    public function editprofile(Request $request){

        $update = Admins::find(Auth::user()->id);
        $update->name = $request['name'];
        $update->mobile = $request['phone'];
        $update->address = $request['address'];
        $update->country = $request['country'];
        if($request->hasFile('file')) {
            $file = Input::file('file');
            $name = $file->getClientOriginalName();
            $update->photo = $name;
            $file->move(public_path().'/upload/photo/', $name);
        }
        $update->update();
        return redirect()->route('myprofile');

    }

}