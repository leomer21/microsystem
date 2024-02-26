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
use DB;
use increment;
use Carbon\Carbon;
use Mail;


class PaymobResponseCallback extends Controller

{

    public function responseCallback(Request $request)
    {
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");

        // @file_get_contents('http://requestbin.fullcontact.com/zt908hzt'); // for API debug http://requestbin.fullcontact.com/zt908hzt?inspect

        $body = @file_get_contents('php://input');
        $request = json_decode($body, true);
        
        ///////////////////////////////////////////////////////
        //////      check if connection from weAccept   ///////
        ///////////////////////////////////////////////////////
        if(isset($request['type'])) { $type = $request['type']; }else{ $type=""; } 
        if(isset($request['obj']['id'])) { $obj_id = $request['obj']['id'];}else{$obj_id = ""; } // requested
        if(isset($request['obj']['pending'])) { $obj_pending = $request['obj']['pending'];}else{$obj_pending = ""; }// requested
        if(isset($request['obj']['amount_cents'])) { $obj_amount_cents = $request['obj']['amount_cents']; }else{ $obj_amount_cents = "";} // requested
        if(isset($request['obj']['success'])) { $obj_success = $request['obj']['success']; }else{ $obj_success = ""; } // requested
      
        if(isset($request['obj']['is_voided'])) { $obj_is_voided = $request['obj']['is_voided']; }else{ $obj_is_voided = ""; }// requested
        if(isset($request['obj']['is_refunded'])) { $obj_is_refunded = $request['obj']['is_refunded']; }else{ $obj_is_refunded = ""; } // requested
        if(isset($request['obj']['is_3d_secure'])) { $obj_is_3d_secure = $request['obj']['is_3d_secure']; }else{ $obj_is_3d_secure = ""; } // requested
        if(isset($request['obj']['integration_id'])) { $obj_integration_id = $request['obj']['integration_id']; }else{ $obj_integration_id = ""; } // requested
        // $obj_profile_id = $request['obj']['profile_id'];
        if(isset($request['obj']['has_parent_transaction'])) { $obj_has_parent_transaction = $request['obj']['has_parent_transaction']; }else{ $obj_has_parent_transaction = ""; } // requested
        
        if(isset($request['obj']['order']['amount_cents'])) { $obj_order_amount_cents = $request['obj']['order']['amount_cents']; }else{ $obj_order_amount_cents = ""; } // requested

        if(isset($request['obj']['order']['merchant_order_id'])) { $obj_order_merchant_order_id = $request['obj']['order']['merchant_order_id']; }else{ $obj_order_merchant_order_id = ""; } // requested
        if(isset($request['obj']['order']['paid_amount_cents'])) { $obj_order_paid_amount_cents = $request['obj']['order']['paid_amount_cents']; $amount = $obj_order_paid_amount_cents/100; }else{ $obj_order_paid_amount_cents = ""; $amount = "";} // requested

        if(isset($request['obj']['created_at'])) { $obj_created_at = $request['obj']['created_at']; }else{ $obj_created_at = ""; } // requested

        if(isset( $request['obj']['currency'] )) { $obj_currency = $request['obj']['currency']; }else{ $obj_currency = "";} // requested

        if(isset( $request['obj']['source_data']['sub_type'] )) { $obj_source_data_sub_type = $request['obj']['source_data']['sub_type']; }else{ $obj_source_data_sub_type = ""; } // requested
        if(isset( $request['obj']['source_data']['type'] )) { $obj_source_data_type = $request['obj']['source_data']['type']; }else{ $obj_source_data_type = ""; } // requested
        if($obj_source_data_type == "wallet"){
            // if transaction through wallet 
            $obj_source_data_pan = $request['obj']['source_data']['identifier']; // requested
        }else{
            // if transaction through Card
            if(isset($request['obj']['source_data']['pan']) ){  
                $obj_source_data_pan = $request['obj']['source_data']['pan']; // requested
            }else{
                $obj_source_data_pan = "";
            }    
        }

        if($obj_currency == "USD"){
            $merchantOrderIDStartFrom = "100000";
        }else{
            $merchantOrderIDStartFrom = "1000";
        }
        ///////////////////////////////////////////////////////////////
        ////////      check if connection from fawry (Payme)   ////////
        ///////////////////////////////////////////////////////////////
        // check if responce from fawry 3rd party
        if(isset($request['BuyerMobile'])) { $fawry = "1"; }else{ $fawry = "0"; }
        if(isset($request['amount'])) { $amount = $request['amount']; }//else{ $amount=""; } 
        if(isset($request['productTitle'])) { $productTitle = $request['productTitle']; }else{ $productTitle=""; } 
        if(isset($request['buyerName'])) { $buyerName = $request['buyerName']; }else{ $buyerName=""; } 
        if(isset($request['BuyerMobile'])) { $buyerMobile = $request['BuyerMobile']; }else{ $buyerMobile=""; } 
        if(isset($request['buyerEmail'])) { $buyerEmail = $request['buyerEmail']; }else{ $buyerEmail=""; } 
        if(isset($request['productType'])) { $productType = $request['productType']; }else{ $productType=""; } 
        if(isset($request['productNumber'])) { $productNumber = $request['productNumber']; }else{ $productNumber=""; } 
        if(isset($request['quantity'])) { $quantity = $request['quantity']; }else{ $quantity=""; } 
        // multiply amount and quantity
        if(isset($request['amount']) and isset($request['quantity']) ){ $amount = $amount * $quantity; }
        
        // check if connection from fawry direct
            if(isset($request['fawryRefNumber'])) { $fawry = "1"; $fawryDirect = "1"; $orderStatus = $request['orderStatus']; $merchantRefNumber = $request['merchantRefNumber']; }
            else{ $fawry = "0";}
            if(isset($request['orderAmount'])) { $amount = $request['orderAmount']; }//else{ $amount=""; } 
            if(isset($request['customerMobile'])) { $buyerMobile = $request['customerMobile']; }else{ $buyerMobile=""; } 
            if(isset($request['customerMail'])) { $buyerEmail = $request['customerMail']; }else{ $buyerEmail=""; } 
            
        /*
        { "requestId":"c72827d084ea4b88949d91dd2db4996e", 
            "fawryRefNumber":"970177", 
                "merchantRefNumber":"9708f1cea8b5426cb57922df51b7f790", 
            "customerMobile":"01004545545", 
            "customerMail":"fawry@fawry.com", 
            "paymentAmount":152.00, "orderAmount":150.00, "fawryFees":2.00, "shippingFees":null, 
            "orderStatus":"NEW",  // New, PAID, CANCELED, DELIVERED, REFUNDED, EXPIRED
            "paymentMethod":"PAYATFAWRY", 
            "messageSignature":"56bca514b2cc6822bf972a869a008f03cacebb14d19829368daa647dbc212aa5", 
            "orderExpiryDate":1533554719314, "orderItems":[ { "itemCode":"e6aacbd5a498487ab1a10ae71061535d", "price":150.0, "quantity":1 } ] }
        */
        //////////////////////////////
        //////// insert DB  //////////
        //////////////////////////////

        if( isset($fawryDirect) and $fawryDirect == "1"){
            // check if orderStatus is PAID or // New, CANCELED, DELIVERED, REFUNDED, EXPIRED 
            // to stop the following steps if order status in not PAID
            if($orderStatus != "PAID"){ return "Fawry not PAID transaction.";}
            // set unreceived values to null
            $productTitle=""; $buyerName=""; $productType=""; $productNumber=""; $quantity="1";
            // complete all missed variables
            $obj_success = 1;
            $obj_is_voided = 0;
            $obj_is_refunded = 0;
            $obj_currency = "EGP";
            // get payment record directly
            $paymentData = DB::table('payment')->where('id',$merchantRefNumber)->where('state','0')->first();
        }
        elseif( $fawry == "1" ){
            $before3days = date('Y-m-d H:i:s', strtotime("-3 days", strtotime($created_at)));
            $paymentData = DB::table('payment')->where(['mobile' => $buyerMobile, 'amount' => $amount, 'state' => '0', 'payment_method' => 'fawry'])->whereBetween('created_at',[$before3days, $created_at])->orderBy('id','desc')->first(); // Found (search for last payment by -3 days from today)
            // complete all missed variables
            $obj_success = 1;
            $obj_is_voided = 0;
            $obj_is_refunded = 0;
            $obj_currency = "EGP";
        }else{
            $orderID = $obj_order_merchant_order_id-$merchantOrderIDStartFrom;
            $paymentData = DB::table('payment')->where('id',$orderID)->where('state','0')->first();
        }
        
        
        if( isset($paymentData->customer_id) ){ $customerID = $paymentData->customer_id; }
        if( isset($customerID) or $type == "DELIVERY_STATUS" ){
            if( $fawry == "1" ){
                
                $Customer = DB::table('customers')->where('id',$customerID)->first();
                if($Customer->global == "1"){ $packagesTable = "packages_global"; }else{ $packagesTable = "packages"; }    
                $package = DB::table($packagesTable)->where('id',$paymentData->package_id)->first();
                // get current package info
                $packageBefore = DB::table($packagesTable)->where('id',$Customer->package_id)->first();
                // inset data into DB    
                DB::table('payment_response')->insert([
                    'type' => 'TRANSACTION'
                    , 'amount' => $amount // requested
                    , 'customer_id' => $customerID // requested
                    , 'obj_id' => 'fawry' // requested
                    , 'obj_success' => '1' // requested
                    , 'obj_createdAt' => $created_at // requested
                    , 'obj_currency' => 'EGP' // requested
                    , 'obj_success' => '1' // requested

                    , 'productTitle' => $productTitle
                    , 'buyerName' => $buyerName
                    , 'buyerMobile' => $buyerMobile
                    , 'buyerEmail' => $buyerEmail
                    , 'productType' => $productType
                    , 'productNumber' => $productNumber
                    , 'quantity' => $quantity
                
                ]);

            }else{
                if( $type == "DELIVERY_STATUS" ){
                    // update cash delevery state
                    $registerdOrderID = $request['obj']['order_id'];
                    $deleveryState = $request['obj']['order_delivery_status'];
                    DB::table("payment_response")->where('obj_id', $registerdOrderID )->update([ 'obj_success' => $deleveryState, 'updated_at' => $request['obj']['updated_at'] ]);

                    // get payment data
                    $paymentResponceData = DB::table("payment_response")->where('obj_id', $registerdOrderID )->first();
                    $orderID = $paymentResponceData->obj_order_merchantOrderID - $merchantOrderIDStartFrom;
                    $paymentData = DB::table('payment')->where('id',$orderID)->first();
                    $customerID = $paymentData->customer_id;
                    $Customer = DB::table('customers')->where('id',$customerID)->first();
                    if($Customer->global == "1"){ $packagesTable = "packages_global"; }else{ $packagesTable = "packages"; }    
                    $amount = $paymentResponceData->obj_amountCents / 100;
                    $package = DB::table($packagesTable)->where('id',$paymentData->package_id)->first();
                    $packageBefore = DB::table($packagesTable)->where('id',$Customer->package_id)->first();
                    
                }else{
                        
                    $Customer = DB::table('customers')->where('id',$customerID)->first();
                    if($Customer->global == "1"){ $packagesTable = "packages_global"; }else{ $packagesTable = "packages"; }    
                    $package = DB::table($packagesTable)->where('id',$paymentData->package_id)->first();
                    // get current package info
                    $packageBefore = DB::table($packagesTable)->where('id',$Customer->package_id)->first();
                    // inset data into DB    
                    DB::table('payment_response')->insert([
                        'type' => $type
                        , 'amount' => $amount // requested
                        , 'customer_id' => $customerID // requested
                        , 'obj_id' => $obj_id // requested
                        , 'obj_pending' => $obj_pending // requested
                        , 'obj_amountCents' => $obj_amount_cents // requested
                        , 'obj_success' => $obj_success // requested
                        , 'obj_isVoided' => $obj_is_voided // requested
                        , 'obj_isRefunded' => $obj_is_refunded // requested
                        , 'obj_is3dsecure' => $obj_is_3d_secure // requested
                        , 'obj_integrationID' => $obj_integration_id // requested
                        , 'obj_hasParentTransaction' => $obj_has_parent_transaction // requested
                        
                        , 'obj_order_amountCents' => $obj_order_amount_cents // requested
                        
                        , 'obj_order_merchantOrderID' => $obj_order_merchant_order_id // requested
                        , 'obj_order_paidAmountCents' => $obj_order_paid_amount_cents // requested
                        
                        , 'obj_createdAt' => $obj_created_at // requested
                        , 'obj_currency' => $obj_currency // requested
                        
                        , 'obj_sourceData_subType' => $obj_source_data_sub_type // requested
                        , 'obj_sourceData_pan' => $obj_source_data_pan // requested
                        , 'obj_sourceData_type' => $obj_source_data_type // requested

                    ]);
                }
            }
            //////////////////////////////
            //// update subscription  ////
            //////////////////////////////
            
            if($obj_success == "true"){$obj_success = 1;}
            // make sure transaction is success
            if( ($obj_success == 1 and $obj_is_voided == 0 and $obj_is_refunded ==0 and isset($amount) and $amount!="") or ( $type == "DELIVERY_STATUS" and isset($request['obj']['order_delivery_status']) and $request['obj']['order_delivery_status'] == "Delivered") ){
				// get customer record
                $Customer = DB::table('customers')->where('id',$customerID)->first();
                if( $paymentData->type == "payasyougo" )
                {
                    // chech if transaction amount = invoices amount
                    if( DB::table('invoices')->where('type', 'payasyougo')->where('state', '0')->where('customer_id', $customerID)->sum('amount') == $amount)
                    {
                        DB::table("invoices")->where('customer_id', $customerID )->where('type', 'payasyougo' )->where('state', '0' )->update([ 'state' => '1', 'paid_date' => $created_at ]);
                    }else{
                        // transaction amount != invoices amount
                        foreach ( DB::table("invoices")->where('customer_id', $customerID )->where('type', 'payasyougo' )->where('state', '0' )->get() as $invoice )
                        {   
                            if( $invoice->amount <= $amount)
                            {
                                DB::table("invoices")->where('id', $invoice->id )->update([ 'state' => '1', 'paid_date' => $created_at ]);
                                $amount = $amount - $invoice->amount;
                            }
                        }

                    }
                    
                    // set currently concurrent limit to 0 (unlimited) for radius page
                    DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => '0' ]);

                    // return JSON responce
                    $returnObj['state'] = "1";
                    $returnObj['amoumt'] = "$amount"; 
                    return json_encode($returnObj);

                }elseif( $paymentData->type == "package" )
				{

                    $date1 = strtotime("$Customer->next_bill");
                    $date2 = strtotime("$today");
                    $diff = ($date1 - $date2);
                    $datediff = round($diff/86400);
                    $nextBill = date('Y-m-d', strtotime("+$package->months months", strtotime($today)));

                    if( $datediff > 0 )
                    { // client have credit in current package
                
                        if( isset($Customer->package_id) and $Customer->package_id!="" and $Customer->package_id!="0" ){
                            // check if renew the same package or the same concurrent but upgrade months
                            if( $Customer->package_id == $paymentData->package_id or ( $packageBefore->concurrent_devices == $package->concurrent_devices and $package->months > $packageBefore->months)  )
                            {
                                $nextBill = date('Y-m-d', strtotime("+$package->months months", strtotime($Customer->next_bill)));
                                $canRenew = "yes";
                            }else{
                                
                                // if( $package->concurrent_devices > $packageBefore->months ){
                                    // client upgrade or downgrade concurrent devices with valid backage
                                    // Memorable Note in Franko : 7sbna leh kam wedinahomlo ayam
                                    if($Customer->currency == "USD"){ $priceColumnName = $package->price_USD; $priceColumnNamePackageBefore = $packageBefore->price_USD;
                                    }else{ $priceColumnName = $package->price; $priceColumnNamePackageBefore = $packageBefore->price;}

                                    $costPerDayFoeNewPackage = $priceColumnName / ( 30 * $package->months );
                                    $costPerDayFoeOldPackage = $priceColumnNamePackageBefore / ( 30 * $packageBefore->months );
                                    // Memorable Note in Franko : ba2elo kam feloos 
                                    $costOfRemainingDaysInOldPackage = $datediff * $costPerDayFoeOldPackage;

                                    $convertMoneyintoDays = round( $costOfRemainingDaysInOldPackage / $costPerDayFoeNewPackage,0 );
                                    
                                    $nextBill = date('Y-m-d', strtotime("+$package->months months", strtotime($today)));
                                    $nextBill = date('Y-m-d', strtotime("+$convertMoneyintoDays days", strtotime($nextBill)));
                                    $canRenew = "yes";
                                // }
                            }
                        }else{
                            $canRenew = "yes";
                        }
                        

                    }else{
                        $canRenew = "yes";
                    }

                    if($canRenew == "yes"){
                        // Enable subscription 
                        // set customer state 1 and update nextbill and package ID into customers table
                        DB::table("customers")->where( 'id', $customerID )->update([ 'state' => '1', 'package_id' => $package->id, 'next_bill' => $nextBill ]);
                        // set currently concurrent limit to 0 (unlimited) for radius page
                        DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => $package->concurrent_devices ]);
                        // set WiFi Marketing state
                        if( $package->modules == "wifi_marketing" ){
                            DB::table($Customer->database.".settings")->where( 'type', 'marketing_enable' )->update([ 'value' => '1', 'state' => '1' ]);
                            DB::table($Customer->database.".settings")->where( 'type', 'commercial_enable' )->update([ 'value' => '1', 'state' => '1' ]);
                        }
                        if( $package->modules == "internet_management" ){
                            DB::table($Customer->database.".settings")->where( 'type', 'marketing_enable' )->update([ 'value' => '0', 'state' => '0' ]);
                            DB::table($Customer->database.".settings")->where( 'type', 'commercial_enable' )->update([ 'value' => '0', 'state' => '0' ]);
                        }
                        // set network state 1
                        DB::table($Customer->database.".networks")->where( 'r_type','!=',"10" )->update([ 'state' => '1' ]);
                        if( DB::table('invoices')->where('customer_id', $customerID )->where('type', 'package' )->where('state', '0' )->count() == 0){
                            DB::table("invoices")->insert([
                            ['customer_id' => $customerID, 'type' => 'package', 'package_id' => $package->id, 'amount' => $amount , 'currency' => $obj_currency, 'issue_date' => $today, 'due_date' => $today, 'state' => '1', 'paid_date' => $today, 'created_at' => $created_at, 'updated_at' => $created_at]
                            ]);
                        }else{
                            // set unpaid invoice into done
                            DB::table("invoices")->where(['customer_id'=> $customerID, 'type' => 'package', 'state' => '0' ] )->update([ 'state' => '1' , 'package_id' => $package->id, 'amount' => $amount , 'currency' => $obj_currency, 'paid_date' => $today,'updated_at' => $created_at ]);
                        }
                        
                        // set payment record state to 1
                        DB::table("payment")->where('id',$paymentData->id)->update([ 'state' => '1', 'updated_at' => $created_at ]);
                        
                        // send email
                        // get system email
                        $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                        $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                        $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                        $customerEmailArray = array($customerEmail, 'support@microsystem.com.eg', 'mr.ahmed@microsystem.com.eg');
                        $customerEmail = $customerEmail.';support@microsystem.com.eg';
                        
                        // sending email
                        $content = "Dear $customerName, <br> <font color=green> Congratulations, Your subscription has been renewed for $package->months months,</font> 
                        <br>
                        your next renewal date is $nextBill.
                        <br>
                        Access to your <a target='_blank' href='http://$Customer->url/settings'>Administration control panel</a>
                        <br>
                        Your account mananager: +2 010 126 66 854
                        <br><br>
                        Thanks,<br>
                        Best Regards.<br>";
                        $from = "support@microsystem.com.eg";
                        $subject = "Microsystem WiFi | Congratulations, Your subscription has been renewed successfully.";

                        Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                            $message->from($from, $customerName);
                            $message->to($customerEmailArray, $customerName)->subject($subject);
                        });
                        // insert notification details
                        DB::table("notifications")->insert([['customer_id' => $customerID, 'date' => $today, 'created_at' => $created_at, 'type' => 'subscription_remind_last_day']]);

                        // return JSON responce
                        $returnObj['state'] = "1";
                        $returnObj['amoumt'] = "$amount"; 
                        return json_encode($returnObj);

                    }
                }
            }

        }

        // incase any of the above conditions falure, responce with zero state
        // return JSON responce
        $returnObj['state'] = "0";
        return json_encode($returnObj);
        
    }

}

