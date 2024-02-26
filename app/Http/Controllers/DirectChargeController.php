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

class DirectChargeController extends Controller
{
    public function Index()
    {
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();
        return view('back-end.settings.directCharge', ['customerData' => $customerData]);
    }

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
    

}