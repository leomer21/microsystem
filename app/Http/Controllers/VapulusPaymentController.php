<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Cards;
use App\Network;
use App\Groups;
use Input;
use DB;
use Validator;
use Auth;
use Carbon\Carbon;
use Excel;
use App;
use Redirect;

class VapulusPaymentController extends Controller
{
    /*
    Test Session ID: c97ce9f7-477c-4e3f-a8d0-19c6e4ded30b
    using testing card: 5123456789012346 05/2021 123
    */
    /*
    Application ID: a31d56e5-a42f-4c87-b520-12d7f87dbfe5
    Password: 7yat2lby
    Hash: e6680a0365643739303734322d333336
    */
    public function Index()
    {
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();
        return view('back-end.settings.vapulusPayment', ['customerData' => $customerData]);
    }

    // we call this function after user enter Visa card details into html page,
    // so if the card details is correct, we will receive session_ID,
    // then send it with the required amount with user mobile and email
    public function pay (Request $request){

        date_default_timezone_set("Africa/Cairo");
	    $created_at = date("Y-m-d H:i:s");
        //https://repl.it/@islamvapulus/php-http-request-with-hashing
        function generateHash($hashSecret,$postData) {
            ksort($postData);
                $message="";
                $appendAmp=0;
            foreach($postData as $key => $value) {
                    if (strlen($value) > 0) {
                        if ($appendAmp == 0) {
                            $message .= $key . '=' . $value;
                            $appendAmp = 1;
                        } else {
                            $message .= '&' . $key . "=" . $value;
                        }
                    }
                }

            $secret = pack('H*', $hashSecret);
            return hash_hmac('sha256', $message, $secret);
        }

        function HTTPPost($url, array $params) {
                $query = http_build_query($params);
                $ch    = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                $response = curl_exec($ch);
                curl_close($ch);
                return $response;
            }

        $postData = array(
            'sessionId' =>  '801c4d89-8b7d-4840-bf82-c2e575cf2fde',
            'mobileNumber' => '01061030454',
            'email' => 'sales@microsystem.com.eg',
            'amount' => '100.50',
            'onAccept' => 'https://demo.microsystem.com.eg/vapulusPaymentSuccess',
            'onFail' => 'https://demo.microsystem.com.eg/vapulusPaymentfail'
        );

        $secureHash= 'e6680a0365643739303734322d333336';
        $postData['hashSecret'] = generateHash($secureHash,$postData);

        $postData['appId']='a31d56e5-a42f-4c87-b520-12d7f87dbfe5';
        $postData['password']='7yat2lby';

        $url ='https://api.vapulus.com:1338/app/session/pay';

        $output=HTTPPost($url,$postData);

        print_r($output);
    }

    // double check if the payment is success or fail
    public function retrieve (Request $request){
        //https://repl.it/@islamvapulus/php-http-request-with-hashing
        function generateHash($hashSecret,$postData) {
            ksort($postData);
                $message="";
                $appendAmp=0;
            foreach($postData as $key => $value) {
                    if (strlen($value) > 0) {
                        if ($appendAmp == 0) {
                            $message .= $key . '=' . $value;
                            $appendAmp = 1;
                        } else {
                            $message .= '&' . $key . "=" . $value;
                        }
                    }
                }

            $secret = pack('H*', $hashSecret);
            return hash_hmac('sha256', $message, $secret);
        }

        function HTTPPost($url, array $params) {
                $query = http_build_query($params);
                $ch    = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                $response = curl_exec($ch);
                curl_close($ch);
                return $response;
            }

        $postData = array(
            'sessionId' =>  'c97ce9f7-477c-4e3f-a8d0-19c6e4ded30b'
        );

        $secureHash= 'e6680a0365643739303734322d333336';
        $postData['hashSecret'] = generateHash($secureHash,$postData);

        $postData['appId']='a31d56e5-a42f-4c87-b520-12d7f87dbfe5';
        $postData['password']='7yat2lby';

        $url ='https://api.vapulus.com:1338/app/session/retrieve';

        $output=HTTPPost($url,$postData);

        print_r($output);
    }

    // receive success callback
    public function vapulusPaymentSuccess (Request $request){
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        // for testing only
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
        DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);

    }

    // receive fail callback
    public function vapulusPaymentfail (Request $request){
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        // for testing only
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
        DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);

    }

    /*
    public function directChargeValues(Request $request)
    {
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();

        // check if Admin need to convert USD amount to EGP
        if( App\Settings::where('type','directChargeCurrency')->value('value') == "USD->EGP" ){
            $url = 'https://openexchangerates.org/api/latest.json?app_id='.App\Settings::where('type','openexchangerates_org_app_id')->value('value');
            $rawdata = file_get_contents($url, false);
            $rawdata = json_decode($rawdata);
            $request->amount = round($request->amount * $rawdata->rates->EGP,2);
            $currency = "EGP";
        }else{
            $currency = App\Settings::where('type','directChargeCurrency')->value('value');
        }

        // call system to generate Visa Payment Link
        // $url = "https://demo.microsystem.com.eg/api/whatsappPay/enduser/3/Second cub Cafe/124/201061030454/a.mansour@microsystem.com.eg/10/EGP/0/1/1/5";
        // $rawdata = file_get_contents($url, false);

        // check if the pament will send to Microsystem Bank account or Customer Bank account
        if(App\Settings::where('type','directChargeWeAcceptIntegrationThroughCustomer')->value('state') == "1"){ $microsystemOrdirectCharge="directCharge";}
        else{$microsystemOrdirectCharge="microsystem";}
        
        $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
        $paymentResponse = $whatsappClass->pay($microsystemOrdirectCharge, $customerData->id, $request->name, '0', $request->mobile, $request->email, $request->amount, $currency, '0', '1', '0', '');
        return Redirect::to($paymentResponse['visa']);

    }
    */
    

}