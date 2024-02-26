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
use App\Http\Controllers\Whatsapp as testHere;

class WhatsappPayCallback extends Controller

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

        // test
        // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body]]);
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
        // set WeAccept start merchantOrderIDStartFrom
        if($obj_currency == "USD"){$merchantOrderIDStartFrom = "900000";}
        else{$merchantOrderIDStartFrom = "9000";}

        // check if connection from fawry direct
        if(isset($request['fawryRefNumber'])) { $fawry = "1"; $fawryDirect = "1"; $orderStatus = $request['orderStatus']; $merchantRefNumber = $request['merchantRefNumber']; }
        else{ $fawry = "0";}
        if(isset($request['orderAmount'])) { $amount = $request['orderAmount']; }//else{ $amount=""; } 
        if(isset($request['customerMobile'])) { $buyerMobile = $request['customerMobile']; }else{ $buyerMobile=""; } 
        if(isset($request['customerMail'])) { $buyerEmail = $request['customerMail']; }else{ $buyerEmail=""; } 
        //////////////////////////////////
        ///////////// Fawry //////////////
        //////////////////////////////////
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
            $orderID = $merchantRefNumber;
            $paymentData = DB::table('end_users_payment')->where('id',$merchantRefNumber)->where('state','0')->first();
        }else{
            // WeAccept
            $orderID = $obj_order_merchant_order_id-$merchantOrderIDStartFrom;
            $paymentData = DB::table('end_users_payment')->where('id',$orderID)->where('state','0')->first();
        }

        ///////////////////////////////////
        ///////////// Wallet //////////////
        ///////////////////////////////////
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
        
        
        if( isset($paymentData->customer_id) ){ $customerID = $paymentData->customer_id; }
        if( isset($customerID)){
            
            // inset payment_response data into DB    
            if( $fawry == "1" ){
                DB::table('payment_response')->insert([
                    'type' => 'TRANSACTION'
                    , 'amount' => $amount // requested
                    , 'customer_id' => $customerID // requested
                    , 'obj_id' => 'fawry' // requested
                    , 'obj_success' => '1' // requested
                    , 'obj_createdAt' => $created_at // requested
                    , 'obj_currency' => 'EGP' // requested
                    , 'obj_success' => '1' // requested
                    , 'buyerMobile' => $buyerMobile
                    , 'buyerEmail' => $buyerEmail
                    , 'quantity' => $quantity
                ]);
            }else{   
                DB::table('end_users_payment_response')->insert([
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

            /////////////////////////////////////////
            ////  Check is valid Payment or not  ////
            /////////////////////////////////////////
            
            if($obj_success == "true"){$obj_success = 1;}
            // make sure transaction is success
            if( $obj_success == 1 and $obj_is_voided == 0 and $obj_is_refunded ==0 and isset($amount) and $amount!="" ){
                
				// get customer record
                $Customer = DB::table('customers')->where('id',$customerID)->first();
                $endUserData = DB::table( $Customer->database.".users" )->where('u_id', $paymentData->local_user_id)->first();
                // $whatsappClass = new App\Http\Controllers\Whatsapp();
                $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
                
                // mark payment as success
                DB::table('end_users_payment')->where('id',$orderID)->update(['state' => '1', 'updated_at'=>$created_at]);

                // insert history paymeny in customer database
                DB::table("$Customer->database.history")->insert([['operation' => 'whatsappCardPaymentSuccess', 'details' => $amount, 'u_id' => $paymentData->local_user_id, 'notes' => '', 'type1' => 'hotspot', 'type2' => 'auto', 'add_date' => $today, 'add_time' => $today_time]]);
                
                // calculate loyality points
                $amountToLoyaltyPoints = DB::table("$Customer->database.settings")->where('type', 'amountToLoyaltyPoints')->value('value');
                $earnedPoints = $amount * $amountToLoyaltyPoints;
                // insert points
                DB::table("$Customer->database.loyalty_points")->insert([['state' => '1','type' => '1', 'a_id' => '0', 'u_id' => $paymentData->local_user_id, 'amount' => $amount, 'points' => $earnedPoints, 'created_at' => $created_at]]);
                // get all customer points
                $endUserLoyaltyPoints = $whatsappClass->getCustomerLoyaltyPoints($Customer->database, $paymentData->local_user_id, $created_at);
                // send Whatsapp Message to Admin
                $adminWAmessage = "💳 Congratulations, you have received $amount".DB::table("$Customer->database.settings")->where('type', 'currency')->value('value')." successfully.💰 \n";
                $adminWAmessage.= "🛎 Table: $paymentData->order_notes \n";
                $adminWAmessage.= $whatsappClass->getAllCustomerInfoToAdmin($Customer->database, $paymentData->local_user_id, $created_at, '1' );
                $adminWAmessage.= "✅ $earnedPoints Points has been added successfully.";
                $adminWAmessage = urlencode($adminWAmessage);
                $whatsappAdmins = DB::table( $Customer->database.".admins" )->where('permissions', 'like','%WAadmin%')->orWhere('permissions', 'like','%WAregPoints%')->get();
				foreach($whatsappAdmins as $admin){
                    if(isset($admin->mobile)){$whatsappClass->send( "", $admin->mobile, $adminWAmessage, $customerID, $Customer->database, "", "", "", "1");}
                }
                // send Whatsapp Message to endUser
                $endUserWAmessage = "💳 Congratulations, you have paid $amount".DB::table("$Customer->database.settings")->where('type', 'currency')->value('value')." successfully.💰 \n\n";
                $endUserWAmessage.= DB::table("$Customer->database.settings")->where('type', 'whatsappUserReceivePointsMsg')->value('value');
                $endUserWAmessage = @str_replace("@earned","$earnedPoints",$endUserWAmessage);
                $endUserWAmessage = @str_replace("@points","$endUserLoyaltyPoints",$endUserWAmessage);
                $allAndAvilableLoyaltyProgram = $whatsappClass->getAllAndAvilableLoyaltyProgram($Customer->database, $paymentData->local_user_id, $created_at);
                $endUserWAmessage = @str_replace("@all_loyalty_programs",$allAndAvilableLoyaltyProgram['all'],$endUserWAmessage);
                $endUserWAmessage = @str_replace("@available_loyalty_programs",$allAndAvilableLoyaltyProgram['available'],$endUserWAmessage);
                if($allAndAvilableLoyaltyProgram['available']!=null){ $endUserWAmessage = @str_replace("@available_loyalty_programs",$allAndAvilableLoyaltyProgram['available'],$endUserWAmessage); }
                else{$viewLoyaltyProgramMsg = @str_replace("@available_loyalty_programs","till now nothing😳!",$endUserWAmessage);}
                
                $endUserWAmessage = urlencode($endUserWAmessage);
                // return $endUserWAmessage;
                return $whatsappClass->send( "", $paymentData->mobile, $endUserWAmessage, $customerID, $Customer->database, "", "", "", "1");
                
                // sending email
                // $content = "Dear $customerName, <br> <font color=green> Congratulations, Your subscription has been renewed for $package->months months,</font> 
                // <br>
                // your next renewal date is $nextBill.
                // <br>
                // Access to your <a target='_blank' href='http://$Customer->url/settings'>Administration control panel</a>
                // <br>
                // Your account mananager: +2 010 126 66 854
                // <br><br>
                // Thanks,<br>
                // Best Regards.<br>";
                // $from = "support@microsystem.com.eg";
                // $subject = "Microsystem WiFi | Congratulations, Your subscription has been renewed successfully.";

                // Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                //     $message->from($from, $customerName);
                //     $message->to($customerEmailArray, $customerName)->subject($subject);
                // });
                // insert notification details
                // DB::table("notifications")->insert([['customer_id' => $customerID, 'date' => $today, 'created_at' => $created_at, 'type' => 'subscription_remind_last_day']]);

                // return JSON responce
                $returnObj['state'] = "1";
                $returnObj['amoumt'] = "$amount"; 
                return json_encode($returnObj);      
            }

        }

        // incase any of the above conditions falure, responce with zero state
        // return JSON responce
        $returnObj['state'] = "0";
        return json_encode($returnObj);
        
    }

}

