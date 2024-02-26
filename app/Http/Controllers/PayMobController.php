<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Network;
use Illuminate\Http\Request;
use App\Branches;
use App\Models\LoadBalancing;
use Input;
use DB;
use Auth;
use App;
use Carbon\Carbon;


class PayMobController extends Controller
{

    public function paymob($type, $paymentMethod, $modules = null, $concurrent = null, $billing_cycle = null, $mobile_wallet_numner = null, $paymentAmount = null)
    {   
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        $currentHour = date("H");
        // Credentials setup
        require_once '../config.php';

        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();
        if($customerData->currency == "USD"){
            $priceColumnName = "price_USD";
            $merchantOrderIDStartFrom = "100000";
        }else{
            $priceColumnName = "price";
            $merchantOrderIDStartFrom = "1000";
        }
        if($customerData->global == "1"){ $packagesTable = "packages_global"; }else{ $packagesTable = "packages"; }  

        $packageID =  DB::table($packagesTable)->where('concurrent_devices',$concurrent)->where('months',$billing_cycle)->where('modules',$modules)->value('id');
        if( isset($packageID) or $type == "payasyougo" ){

            if( $paymentMethod == "fawry"){
                /*
                // get system email
                $packageURL = DB::table($packagesTable)->where('id',$packageID)->value('url');
                if(isset($packageURL)){

                    $customerPhone = DB::table($customerData->database.".settings")->where('type','phone')->value('value');
                    $customerEmail = DB::table($customerData->database.".settings")->where('type','email')->value('value');
                    $return = "<div class='alert alert-danger alert-bordered'><center>
                    Make sure to update <strong> your Mobile number with your country code ( +2 ) </strong> in settings page.
                    </center></div>";

                    $return.= "<div class='alert alert-info alert-styled-left alert-bordered'><center>
                    First step: Open link then select <strong> Quantity “1” for (Automated internet management module only) </strong> or choose <strong> Quantity “2” for (Auto internet management + smart WiFi marketing modules) </strong> then select “Fawry” Then click “proceed to payment”.<br>
                    <img src=assets/images/fawry1.jpeg width='50%' height='50%'>    
                    </center></div>";

                    $return.= "<div class='alert alert-info alert-styled-left alert-bordered'><center>
                    Second step: Make sure to enter your Registered mobile number: <strong><font color=red> $customerPhone </font></strong>
                    <br> and Registered Email: <strong><font color=red> $customerEmail </font></strong> then create new account for payment. <br>
                    <img src=assets/images/fawry2.jpeg width='50%' height='50%'>    
                    </center></div>";

                    $return.= "<div class='alert alert-info alert-styled-left alert-bordered'><center>
                    Third step: Click on “Fawry” then click “Confirm”.<br>
                    <img src=assets/images/fawry3.jpeg width='50%' height='50%'>    
                    </center></div>";

                    $return.= "<div class='alert alert-info alert-styled-left alert-bordered'><center>
                    Fourth step: copy order number then goto any Fawry branch and ask him to pay through (At Fawry) service and till him your order number.<br>
                    <img src=assets/images/fawry4.jpeg width='50%' height='50%'>    
                    </center></div>";

                    $return.= "<div class='alert alert-warning  alert-bordered'><center>
                    If you need to update your mobile number or email, change it firstly from (Settings) tap in setting page, then click pay again with Fawry, for immediately activating your system after your transaction.   
                    </center></div>";
                    $return.= "<div class='alert alert-info alert-bordered'><center>
                    <form target='_blank' action='$packageURL'> <button type='submit' class='btn btn-primary btn-block'><b><i class='icon-cash3'></i> Pay Now!</b></button>
                    </form></center></div>";
                    // insert "payment" record
                    if( $type == "payasyougo" ){
                        $realAmount = $paymentAmount;
                    }else{
                        $realAmount = DB::table($packagesTable)->where('id',$packageID)->value("$priceColumnName"); 
                    }
                    $merchantOrderID = DB::table('payment')->insertGetId(
                    array('customer_id' => $customerData->id, 'package_id' => $packageID, 'amount' => $realAmount, 'type' => $type, 'payment_method' => $paymentMethod, 'mobile' => App\Settings::where('type', 'phone')->value('value'), 'created_at' => $created_at )
                    );
                    return $return;
                }else{
                    return "<div class='alert alert-danger alert-styled-left alert-bordered'><center> This package not available to pay at the moment through Fawry, Please choose another payment method. </center></div>";
                }
                */
                // new Fawry Direct integration

                // 1 - get customer contacts
                $customerPhone = DB::table($customerData->database.".settings")->where('type','phone')->value('value');
                $customerEmail = DB::table($customerData->database.".settings")->where('type','email')->value('value');

                // 2 - insert payment record to get unique code of merchantOrderID
                if( $type == "payasyougo" ){
                    $realAmount = $paymentAmount;
                }else{
                    $realAmount = DB::table($packagesTable)->where('id',$packageID)->value("$priceColumnName"); 
                }
                $merchantOrderID = DB::table('payment')->insertGetId(
                array('customer_id' => $customerData->id, 'package_id' => $packageID, 'amount' => $realAmount, 'type' => $type, 'payment_method' => $paymentMethod, 'mobile' => App\Settings::where('type', 'phone')->value('value'), 'created_at' => $created_at )
                );

                // 3 - sending fawry Json request
                $merchantRefNum = $merchantOrderID; // must be unique
                $customerProfileId = $customerData->id; // must be unique
                $amount = $realAmount.'.00'; // must be dicimal ex(20.00)
                $hashVar = $fawryMerchantCode.$merchantRefNum.$customerProfileId."PAYATFAWRY".$amount.$fawrySecurityKey;
                $finalHashCode = hash('sha256', $hashVar);
                $data = '
                {
                    "merchantCode":"'.$fawryMerchantCode.'",
                    "merchantRefNum":"'.$merchantRefNum.'",
                    "customerProfileId":"'.$customerProfileId.'",
                    "customerMobile":"'.$customerPhone.'",
                    "customerEmail":"'.$customerEmail.'",
                    "paymentMethod":"PAYATFAWRY",
                    "amount":'.$amount.',
                    "currencyCode":"EGP",
                    "description":"Microsystem Hotspot",
                    "paymentExpiry":'.strtotime("+1 week").'077,
                    "chargeItems":[
                       {
                          "itemId":"897fa8e81be26df25db592e81c31c",
                          "description":"Microsystem",
                          "price":'.$amount.',
                          "quantity":1
                       }
                    ],
                    "signature":"'.$finalHashCode.'"
                }
                ';
        
                // SENDING 
                $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$data")));
                $response = @file_get_contents($fawryIntegrationUrl, FALSE, $context);
        
                // check if sending done succesfully
                if(isset($response)){
                    $responseJson = json_decode($response);
                    if(isset($responseJson)){
                        if($responseJson->statusCode == "200"){
                            $referenceNumber = $responseJson->referenceNumber;
                        }
                    }
                }
        
                if(isset($referenceNumber)){
                    $return = "<div class='alert alert-info alert-styled-left alert-bordered'><center>
                    <h3> Fawry Code: $referenceNumber </h3> <br>
                     <strong><a href='https://fawry.com/%d8%a7%d9%8a%d9%86-%d9%8a%d8%aa%d9%85-%d8%a7%d9%84%d8%af%d9%81%d8%b9-%d8%a7%d9%84%d8%a7%d9%84%d9%83%d8%aa%d8%b1%d9%88%d9%86%d9%8a-%d9%85%d8%b9-%d9%81%d9%88%d8%b1%d9%8a%d8%9f/?lang=ar' target='_blank'>
                    برجاء بالإحتفاظ بهذا الرقم والتوجه الى أي منفذ من منافذ فوري وأطلب من التاجر الدفع من خلال رقم فوري باي أو بكود الخدمه 788
                    </a></strong>
                    </center></div>";
                    // return "$referenceNumber";
                }else{
                    $return = "<div class='alert alert-danger alert-bordered'><center>
                    <strong> There is an error, Make sure to update your Email and Mobile number with your country code ( +2 ) in settings page.: <br> $response </strong>
                    </center></div>";
                    // return "There is an error: \n $response";
                }
                
                return $return;


            }elseif( $paymentMethod == "qnb"){
                $return= "<div class='alert alert-info alert-styled-left alert-bordered'><center>
                    • Bank Name: QNB Alahli
                    <br>
                    • Account Name: Microsystem
                    <br>
                    • Account Number: 20315994889-67
                    <br>
                    • Branch name: Sadat City
                    <br>
                    • Swift Code: QNBAEGCX
                    </center></div>";
                    
                    $return.= "<div class='alert alert-warning  alert-bordered'><center>
                    Please complete your deposit then send invoice by mail to <br>
                    sales@microsystem.com.eg
                    <br>
                    With your system name or call your account manager directly through 
                    <br>
                    +2 011 459 29 570
                    <br>
                    +2 010 126 66 854
                    </center></div>";

                    $return.= "<div class='alert alert-info alert-bordered'><center>
                    <form target='_blank' action='http://www.qnbalahli.com/cs/Satellite/QNBEgypt/en_EG/enbranchesEgypt'> <button type='submit' class='btn btn-primary btn-block'><b> QNB Alahli Branches </b></button>
                    </form></center></div>";
                    return $return;

            }elseif( $paymentMethod == "alex"){
                    $return= "<div class='alert alert-info alert-styled-left alert-bordered'><center>
                    • Bank Name: Bank of Alexandria
                    <br>
                    • Account Name: Microsystem
                    <br>
                    • Account Number: 144058262001
                    <br>
                    • Branch name: Investment Authority
                    <br>
                    • Swift Code: ALEXEGCXACC
                    </center></div>";
                    
                    $return.= "<div class='alert alert-warning  alert-bordered'><center>
                    Please complete your deposit then send invoice by mail to <br>
                    sales@microsystem.com.eg
                    <br>
                    With your system name or call your account manager directly through 
                    <br>
                    +2 011 459 29 570
                    <br>
                    +2 010 126 66 854
                    </center></div>";

                    $return.= "<div class='alert alert-info alert-bordered'><center>
                    <form target='_blank' action='https://www.alexbank.com/En/Home/StoreLocator'> <button type='submit' class='btn btn-primary btn-block'><b> Alex Branches </b></button>
                    </form></center></div>";
                    return $return;


            }elseif( ($paymentMethod != "wallet") or ($paymentMethod == "wallet" and isset($mobile_wallet_numner) and strlen($mobile_wallet_numner)==11) ){

                if( $type == "payasyougo" ){
                    $realAmount = $paymentAmount;
                    $amount = $realAmount * 100; // to convert amount into cents
                }else{
                    $realAmount = DB::table($packagesTable)->where('id',$packageID)->value("$priceColumnName"); 
                    $amount = $realAmount * 100; // to convert amount into cents
                }
                
                // if($paymentMethod == "cash"){
                //     $amount = $amount + round( ($amount * 3) / 100 ,1 );
                // }
                $currency = $customerData->currency; 
                //$currency = "USD"; // for test only (http://requestbin.fullcontact.com/17jlxtq1?inspect)
                $merchantOrderID = DB::table('payment')->insertGetId(
                    array('customer_id' => $customerData->id, 'package_id' => $packageID, 'amount' => $realAmount, 'payment_method' => $paymentMethod, 'type' => $type, 'created_at' => $created_at )
                );
                $merchantOrderID = $merchantOrderID+$merchantOrderIDStartFrom;
                // validation
                $firstName = App\Settings::where('type', 'app_name')->value('value');
                if(!isset( $firstName ) or $firstName == ""){ $firstName = "Microsystem"; }
                $lastName = App\Settings::where('type', 'description')->value('value'); 
                if(!isset( $lastName ) or $lastName == ""){ $lastName = " "; }
                $email = App\Settings::where('type', 'email')->value('value');
                if(!isset( $email ) or $email == ""){ $email = "support@microsystem.com.eg"; }
                $street = App\Settings::where('type', 'address')->value('value');
                if(!isset( $street ) or $street == ""){ $street = "Master st"; }
                $phone_number = App\Settings::where('type', 'phone')->value('value');
                if(!isset( $phone_number ) or $phone_number == ""){ $phone_number = "201145929570"; }
                $country = App\Settings::where('type', 'country')->value('value');
                if(!isset( $country ) or $country == ""){ $country = "Egypt"; }


                $userData = array(
                    "apartment"=> "0", 
                    "email"=> $email, 
                    "first_name"=> $firstName,
                    "last_name"=> $lastName,
                    "floor"=> "0",  
                    "street"=> $street, 
                    "building"=> "0", 
                    "phone_number"=> $phone_number, 
                    "postal_code"=> "0", 
                    "city"=> "Cairo", 
                    "country"=> $country,  
                    "state"=> "Cairo",
                    "shipping_method"=> "PKG"
                );

                
                // step 1
                // The data to send to the API
                $postData = array(
                    'username' => $username,
                    'password' => $password,
                    'expiration' => '36000'
                );

                // Create the context for the request
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData)
                    )
                ));

                // Send the request
                $response = file_get_contents('https://accept.paymobsolutions.com/api/auth/tokens', FALSE, $context);

                // Check for errors
                if($response === FALSE){
                    die('Error');
                }

                // Decode the response
                $responseData = json_decode($response, TRUE);

                // Print the date from the response
                $tokenFromStep1 = $responseData['token'];
                $merchantIDFromStep1 = $responseData['profile']['id'];

                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                ////////////                                    step 2                                   //////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////

                // The data to send to the API
                $postData2 = array(
                    "delivery_needed"=> "false",
                    "merchant_id"=> "$merchantIDFromStep1",
                    "amount_cents"=> "$amount",
                    "currency"=> "$currency",
                    "merchant_order_id"=> "$merchantOrderID"
                );
                $postData2['items'] = array(
                );
                $postData2['shipping_data'] = $userData;
                
                // Create the context for the request
                $context2 = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData2)
                    )
                ));

                // Send the request
                $response2 = file_get_contents("https://accept.paymobsolutions.com/api/ecommerce/orders?token=$tokenFromStep1", FALSE, $context2);

                // Check for errors
                if($response2 === FALSE){
                    die('Error');
                }

                // Decode the response2
                $response2Data = json_decode($response2, TRUE);

                // Print the date from the response2
                $orderIDFromStep2 = $response2Data['id'];
                //if(isset($response2Data['url'])) {$orderUrl = $response2Data['url'];} // existed only in case Type=card not fount in Type=wallet
                
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                ////////////                                    step 3                                   //////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                
                // check integration id type
                if($paymentMethod=="card" and $currency=="EGP"){$finalIntegration_id = $integration_id4cardEGP;}
                if($paymentMethod=="card" and $currency=="USD"){$finalIntegration_id = $integration_id4cardUSD;}
                if($paymentMethod=="wallet"){$finalIntegration_id = $integration_id4wallet;}
                if($paymentMethod=="cash"){$finalIntegration_id = $integration_id4cash;}

                // The data to send to the API
                $postData3 = array(
                    "amount_cents"=> "$amount",
                    "expiration"=> "36000",
                    "order_id"=> "$orderIDFromStep2",
                    "currency"=> "$currency", 
                    "integration_id"=> "$finalIntegration_id"
                );
                $postData3['billing_data'] = $userData;
                
                // Create the context for the request
                $context3 = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\n",
                        'content' => json_encode($postData3)
                    )
                ));

                // Send the request
                $response3 = file_get_contents("https://accept.paymobsolutions.com/api/acceptance/payment_keys?token=$tokenFromStep1", FALSE, $context3);

                // Check for errors
                if($response3 === FALSE){
                    die('Error');
                }

                // Decode the response2
                $response3Data = json_decode($response3, TRUE);

                // Print the date from the response2
                $finalTokenFromStep3 = $response3Data['token'];

                if($paymentMethod=="card")
                {
                    $iframe = "https://accept.paymobsolutions.com/api/acceptance/iframes/$iframe_id?payment_token=$finalTokenFromStep3";
                    
                    $return = "<div class='alert alert-info  alert-bordered'><center>
                    Please click on the following button to proceed your request.
                    </center></div>";

                    $return.= "<div class='alert alert-info alert-bordered'><center>
                    <a class='btn btn-primary btn-block btn-rounded' href='$iframe' target='_blank'> Next </a>
                    </form></center></div>";

                    return $return; 
                    // $iframe = "https://accept.paymobsolutions.com/api/acceptance/iframes/$iframe_id?payment_token=$finalTokenFromStep3";
                    // return view('back-end.settings.payment',['iframe'=> $iframe, 'type' => 'card']);
                
                    //return "https://accept.paymobsolutions.com/api/acceptance/iframes/$iframe_id?payment_token=$finalTokenFromStep3";
                }

                if($paymentMethod=="wallet" or $paymentMethod=="cash")
                {
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////                   step 4 in case type = wallet  or cash                     //////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    
                    // The data to send to the API
                    $postData4 = array(
                        "payment_token"=> "$finalTokenFromStep3"
                    );

                    if($paymentMethod=="wallet")
                    {
                        $postData4['source'] = array(
                            "identifier"=> "$mobile_wallet_numner",
                            "subtype"=> "WALLET"
                        );
                    }
                    elseif($paymentMethod=="cash")
                    {
                        $postData4['source'] = array(
                            "identifier"=> "cash",
                            "subtype"=> "CASH"
                        );
                    }

                    $postData4['billing'] = $userData;
                    
                    // Create the context for the request
                    $context4 = stream_context_create(array(
                        'http' => array(
                            'method' => 'POST',
                            'header' => "Content-Type: application/json\r\n",
                            'content' => json_encode($postData4)
                        )
                    ));

                    // Send the request
                    $response4 = file_get_contents("https://accept.paymobsolutions.com/api/acceptance/payments/pay", FALSE, $context4);

                    // Check for errors
                    if($response4 === FALSE){
                        die('Error');
                    }

                    // Decode the response2
                    $response4Data = json_decode($response4, TRUE);

                    // Print the date from the response2
                    if($paymentMethod=="wallet"){

                        $redirectURL = $response4Data['redirect_url'];

                        $return= "<div class='alert alert-info  alert-bordered'><center>
                        Please click on the following button to proceed mobile payment request.
                        </center></div>";

                        $return.= "<div class='alert alert-info alert-bordered'><center>
                        <a class='btn btn-primary btn-block btn-rounded' href='$redirectURL' target='_blank'> Next </a>
                        </form></center></div>";

                        return $return;
                        //return view('back-end.settings.payment',['iframe'=> $response4Data['redirect_url'], 'type' => 'wallet' ]);  
                        //return $redirectUrlFromStep4 = $response4Data['redirect_url'];
                    }elseif($paymentMethod=="cash"){
                        return $response4Data['merchant_response'];
                        // merchant_response
                        // success
                        // $iframe = "<button type='button' class='btn btn-primary btn-block' id='Success_message'> Done, Please wait courier service at registered address into system info.</button>";
                        // return view('back-end.settings.payment',['iframe'=> $iframe, 'type' => 'cash']);
                        // return "Done :)";
                    }
                    
                }  
            }else{return "<div class='alert alert-danger alert-styled-left alert-bordered'><center> Error in Mobile wallet number, Please enter valid number and try again. </center></div>";}

        }else{return "<div class='alert alert-danger alert-styled-left alert-bordered'><center> Error in package info, Please refresh page and select package again. </center></div>";}
        
    }


   


}