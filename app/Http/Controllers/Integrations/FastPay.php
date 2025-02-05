<?php
namespace App\Http\Controllers\Integrations;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use DB;
use Redirect;
use Auth;
use Carbon\Carbon;


class FastPay
{

    public function pay($microsystemORenduser, $systemID, $systemName, $customerID=null, $customerMobile, $customerEmail, $amount, $currency, $fawry=null, $visa=null, $wallet=null, $orderNotes=null)
    {   
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        $currentHour = date("H");
        // Credentials setup
        
        $systemData =  DB::table('customers')->where('id',$systemID)->where('state','1')->first();
        if($microsystemORenduser=="enduser"){
            $customerData = DB::table( $systemData->database.".users" )->where('u_id', $customerID)->first();
            $customerName = $customerData->u_name;
            $fawryState = DB::table("$systemData->database.settings")->where('type', 'fawryState')->value('state');
            $visaState = DB::table("$systemData->database.settings")->where('type', 'visaState')->value('state');
            $walletState = DB::table("$systemData->database.settings")->where('type', 'walletState')->value('state');
            // for WeAccept Visa and Wallet 
            if($currency == "USD"){
                $merchantOrderIDStartFrom = "900000";
            }else{
                $merchantOrderIDStartFrom = "9000";
            }
        }else{
            require_once '../config.php';
            $customerName = $systemName;
            $fawryState = "1";
            $visaState = "1";
            $walletState = "1";
            $fawryPaymentExpiryDays = "7";
            $weacceptUsername = $username;
            $weacceptPassword = $password;
            $weacceptIframeID = $iframe_id;
            // for WeAccept Visa and Wallet 
            if($currency == "USD"){
                $merchantOrderIDStartFrom = "100000";
            }else{
                $merchantOrderIDStartFrom = "1000";
            }
        }
        
        
        if( isset($systemData) ){

            if($fawry == "1" and $fawryState!="0"){
                
                ///// Fawry Direct Integration Server2Server /////
                if($microsystemORenduser=="enduser"){
                    if($fawryState == "1"){// live
                        $fawryIntegrationUrl = "https://www.atfawry.com/ECommerceWeb/Fawry/payments/charge";
                        $fawryMerchantCode = DB::table("$systemData->database.settings")->where('type', 'fawryMerchantCodeLive')->value('value'); // LIVE
                        $fawrySecurityKey = DB::table("$systemData->database.settings")->where('type', 'fawrySecurityKeyLive')->value('value'); // LIVE
                        $fawryPaymentExpiryDays = DB::table("$systemData->database.settings")->where('type', 'fawryPaymentExpiryDays')->value('value');
                    }else{ // test
                        $fawryIntegrationUrl = "https://atfawry.fawrystaging.com//ECommerceWeb/Fawry/payments/charge";
                        $fawryMerchantCode = DB::table("$systemData->database.settings")->where('type', 'fawryMerchantCodeTest')->value('value'); // test
                        $fawrySecurityKey = DB::table("$systemData->database.settings")->where('type', 'fawrySecurityKeyTest')->value('value'); // test
                        $fawryPaymentExpiryDays = DB::table("$systemData->database.settings")->where('type', 'fawryPaymentExpiryDays')->value('value');
                    }
                }

                // 1 - insert payment record to get unique code of merchantOrderID
                if($microsystemORenduser=="enduser"){
                    $merchantOrderID = DB::table('end_users_payment')->insertGetId(array('customer_id' => $systemData->id, 'local_user_id' => $customerID, 'amount' => $amount, 'payment_method' => 'fawry', 'order_notes' => $orderNotes, 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }else{
                    $merchantOrderID = DB::table('payment')->insertGetId(array('customer_id' => $systemData->id, 'amount' => $amount, 'payment_method' => 'fawry', 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }
                // 2 - sending fawry Json request
                $merchantRefNum = $merchantOrderID; // must be unique
                $customerProfileId = $systemData->id; // must be unique
                $finalAmount = $amount.'.00'; // must be dicimal ex(20.00)
                $hashVar = $fawryMerchantCode.$merchantRefNum.$customerProfileId."PAYATFAWRY".$finalAmount.$fawrySecurityKey;
                $finalHashCode = hash('sha256', $hashVar);
                $data = '
                {
                    "merchantCode":"'.$fawryMerchantCode.'",
                    "merchantRefNum":"'.$merchantRefNum.'",
                    "customerProfileId":"'.$customerProfileId.'",
                    "customerMobile":"'.$customerMobile.'",
                    "customerEmail":"'.$customerEmail.'",
                    "paymentMethod":"PAYATFAWRY",
                    "amount":'.$finalAmount.',
                    "currencyCode":"'.$currency.'",
                    "description":"'.$systemName.'",
                    "paymentExpiry":'.strtotime("+$fawryPaymentExpiryDays day").'077,
                    "chargeItems":[
                       {
                          "itemId":"897fa8e81be26df25db592e81c31c",
                          "description":"'.$customerName.'",
                          "price":'.$finalAmount.',
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
                            if(isset($referenceNumber) and $microsystemORenduser=="enduser"){ DB::table('end_users_payment')->where('id',$merchantOrderID)->update(['fawry_code' => $referenceNumber]);}
                        }
                    }
                }
                
            }
            
            if( $visa == "1" and $visaState!="0" ){

                //// WeAccept  /////
                if($microsystemORenduser=="enduser"){
                    $weacceptUsername = DB::table("$systemData->database.settings")->where('type', 'weacceptUsername')->value('value');
                    $weacceptPassword = DB::table("$systemData->database.settings")->where('type', 'weacceptPassword')->value('value');
                    $weacceptIframeID = DB::table("$systemData->database.settings")->where('type', 'weacceptIframeID')->value('value');
                    
                    // get integration ID
                    if($visaState == "1"){// live
                        if($currency=="USD"){
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardUSDlive')->value('value'); // LIVE USD
                        }else{ // EGP
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardEGPlive')->value('value'); // LIVE EGP
                        }
                    }else{ // test
                        if($currency=="USD"){
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardUSDtest')->value('value'); // test USD    
                        }else{
                            $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptCardEGPtest')->value('value'); // test EGP
                        }
                    }
                }else{
                    if($currency=="USD"){
                        $weacceptIntegrationID = $integration_id4cardUSD;
                    }else{ // EGP
                        $weacceptIntegrationID = $integration_id4cardEGP;
                    }
                }
                $finalAmount = $amount * 100; // to convert amount into cents
                
                // 1 - insert payment record to get unique code of merchantOrderID
                if($microsystemORenduser=="enduser"){
                    $merchantOrderID = DB::table('end_users_payment')->insertGetId(array('customer_id' => $systemData->id, 'local_user_id' => $customerID, 'amount' => $amount, 'payment_method' => 'card', 'mobile' => $customerMobile, 'order_notes' => $orderNotes, 'created_at' => $created_at ));
                }else{
                    $merchantOrderID = DB::table('payment')->insertGetId(array('customer_id' => $systemData->id, 'amount' => $amount, 'payment_method' => 'card', 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }
                $merchantOrderIDfinal = $merchantOrderID+$merchantOrderIDStartFrom;
                
                // validation
                $firstName = $customerName;
                if(!isset( $firstName ) or $firstName == ""){ $firstName = "Microsystem"; }
                $lastName = $systemName; 
                if(!isset( $lastName ) or $lastName == ""){ $lastName = " "; }
                $email = $customerEmail;
                if(!isset( $email ) or $email == ""){ $email = "support@microsystem.com.eg"; }
                $street = DB::table("$systemData->database.settings")->where('type', 'address')->value('value');
                if(!isset( $street ) or $street == ""){ $street = "Master st"; }
                $phone_number = $customerMobile;
                if(!isset( $phone_number ) or $phone_number == ""){ $phone_number = "201145929570"; }
                $country = DB::table("$systemData->database.settings")->where('type', 'country')->value('value');
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
                    'username' => $weacceptUsername,
                    'password' => $weacceptPassword,
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
                    "amount_cents"=> "$finalAmount",
                    "currency"=> "$currency",
                    "merchant_order_id"=> "$merchantOrderIDfinal"
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
                
                // The data to send to the API
                $postData3 = array(
                    "amount_cents"=> "$finalAmount",
                    "expiration"=> "36000",
                    "order_id"=> "$orderIDFromStep2",
                    "currency"=> "$currency", 
                    "integration_id"=> "$weacceptIntegrationID"
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

                if($visa=="1")
                {
                    $iframe = "https://accept.paymobsolutions.com/api/acceptance/iframes/$weacceptIframeID?payment_token=$finalTokenFromStep3";
                    if(isset($iframe)){ 
                        $iframe=$this->linkShortener($systemData->url,$iframe);
                        if($microsystemORenduser=="enduser"){DB::table('end_users_payment')->where('id',$merchantOrderID)->update(['visa_link' => $iframe]);}
                    }
                }

            }
            
            if( $wallet == "1" and $walletState!="0" ){

                //// WeAccept  /////
                if($microsystemORenduser=="enduser"){
                    $weacceptUsername = DB::table("$systemData->database.settings")->where('type', 'weacceptUsername')->value('value');
                    $weacceptPassword = DB::table("$systemData->database.settings")->where('type', 'weacceptPassword')->value('value');
                    $weacceptIframeID = DB::table("$systemData->database.settings")->where('type', 'weacceptIframeID')->value('value');

                    // get integration ID
                    if($visaState == "1"){// live
                        $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptWalletLive')->value('value'); // LIVE EGP
                    }else{ // test
                        $weacceptIntegrationID = DB::table("$systemData->database.settings")->where('type', 'weacceptWalletTest')->value('value'); // test EGP
                    }
                }else{
                    $weacceptIntegrationID = $integration_id4wallet;
                }

                $finalAmount = $amount * 100; // to convert amount into cents
                
                // 1 - insert payment record to get unique code of merchantOrderID
                if($microsystemORenduser=="enduser"){
                    $merchantOrderID = DB::table('end_users_payment')->insertGetId(array('customer_id' => $systemData->id, 'local_user_id' => $customerID, 'amount' => $amount, 'payment_method' => 'wallet', 'mobile' => $customerMobile, 'order_notes' => $orderNotes, 'created_at' => $created_at ));
                }else{
                    $merchantOrderID = DB::table('payment')->insertGetId(array('customer_id' => $systemData->id, 'amount' => $amount, 'payment_method' => 'wallet', 'mobile' => $customerMobile, 'created_at' => $created_at ));
                }
                $merchantOrderIDfinal = $merchantOrderID+$merchantOrderIDStartFrom;
                
                // validation
                $firstName = $customerName;
                if(!isset( $firstName ) or $firstName == ""){ $firstName = "Microsystem"; }
                $lastName = $systemName; 
                if(!isset( $lastName ) or $lastName == ""){ $lastName = " "; }
                $email = $customerEmail;
                if(!isset( $email ) or $email == ""){ $email = "support@microsystem.com.eg"; }
                $street = DB::table("$systemData->database.settings")->where('type', 'address')->value('value');
                if(!isset( $street ) or $street == ""){ $street = "Master st"; }
                $phone_number = $customerMobile;
                if(!isset( $phone_number ) or $phone_number == ""){ $phone_number = "201145929570"; }
                $country = DB::table("$systemData->database.settings")->where('type', 'country')->value('value');
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
                    'username' => $weacceptUsername,
                    'password' => $weacceptPassword,
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
                    "amount_cents"=> "$finalAmount",
                    "currency"=> "$currency",
                    "merchant_order_id"=> "$merchantOrderIDfinal"
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
                
                // The data to send to the API
                $postData3 = array(
                    "amount_cents"=> "$finalAmount",
                    "expiration"=> "36000",
                    "order_id"=> "$orderIDFromStep2",
                    "currency"=> "$currency", 
                    "integration_id"=> "$weacceptIntegrationID"
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

                if($wallet=="1")
                {
                    ////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////                   step 4 in case type = wallet                       //////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////
                    
                    // The data to send to the API
                    $postData4 = array(
                        "payment_token"=> "$finalTokenFromStep3"
                    );

                    $postData4['source'] = array(
                        "identifier"=> "$customerMobile",
                        "subtype"=> "WALLET"
                    );
                    
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
                    $redirectURL = $response4Data['redirect_url'];
                    if(isset($redirectURL)){ 
                        $redirectURL=$this->linkShortener($systemData->url,$redirectURL);
                        if($microsystemORenduser=="enduser"){DB::table('end_users_payment')->where('id',$merchantOrderID)->update(['wallet_link' => $redirectURL]);}
                    }
                    
                }  
            }

            if(isset($referenceNumber)){$fawryReturn=$referenceNumber;}else{$fawryReturn="";}
            if(isset($iframe)){$visaReturn=$iframe;}else{$visaReturn="";}
            if(isset($redirectURL)){$walletReturn=$redirectURL;}else{$walletReturn="";}
            $return['fawry']=$fawryReturn;
            $return['visa']=$visaReturn;
            $return['wallet']=$walletReturn;
            return $return;
        }else{return "System subscription has been ended.";}   
    }

    public function linkShortener($baseUrl=null,$tallUrl=null)
    {
        // for External API
        // URL: https://demo.microsystem.com.eg/api/linkShortener
        // Method: POST
        // Body: {"baseUrl":"demo.microsystem.com.eg","tallUrl":"https://accept.paymobsolutions.com/api/acceptance/iframes/2155"}
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        if(!isset($baseUrl) and !isset($tallUrl)){
            $body = @file_get_contents('php://input');
            $request = json_decode($body, true);
            $baseUrl = $request['baseUrl'];
            $tallUrl = $request['tallUrl'];
        }

        $shortID = DB::table('link_shortener')->insertGetId(array('base_url' => $baseUrl, 'tall_url' => $tallUrl, 'created_at' => $created_at ));
        $finalUrl = "https://".$baseUrl."/api/url/".$shortID;
        DB::table('link_shortener')->where('id',$shortID)->update(['final_url' => $finalUrl]);
        return $finalUrl;
    }

    public function linkShortenerFetch($urlId)
    {
        date_default_timezone_set("Africa/Cairo");
        $created_at = date("Y-m-d H:i:s");
        $urlData = DB::table('link_shortener')->where('id',$urlId)->first();
        function getUserIP()
        {
            // Get real visitor IP behind CloudFlare network
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                    $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];
            if(filter_var($client, FILTER_VALIDATE_IP)){$ip = $client;}
            elseif(filter_var($forward, FILTER_VALIDATE_IP)){$ip = $forward;}
            else{$ip = $remote;}
            return $ip;
        }
        $user_ip = getUserIP();

        DB::table('link_shortener')->where('id',$urlId)->update(['visits' => DB::raw('visits + 1'), 'last_visit_ip' => $user_ip, 'last_visit'=>$created_at]);
        return Redirect::to($urlData->tall_url);
    }
}