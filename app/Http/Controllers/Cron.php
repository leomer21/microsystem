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
use Carbon\Carbon;
use Mail;
use DateTime;

class Cron extends Controller
{
    function encrypt($key, $payload) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    function decrypt($key, $garble) {
        list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    }
    
    public function cron(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        $currentHour = date("H");
        $today_full24=$created_at;

        // for test only
        // return "11";
        // $allCustomers=DB::table('customers')->groupBy('database')->limit(2)->get();
        // $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->limit(28)->get();
        $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            
            $tableName = $Customer->database.'.cron_users_radacct';
            $counter=0;

            if($Customer->global == "1"){ $packagesTable = "packages_global"; }else{ $packagesTable = "packages"; }    


            ///////////////////////////////////////////////////////////////////////////////////////////////
			///      					  Check on primise license subscription  4shopping    			///
			///////////////////////////////////////////////////////////////////////////////////////////////
			/*
            $licenseCustomerID = "4shopping";
            $licenseCustomerName = "4shopping mall";
            $licenseUniqueID = "CVVRRES245gshOAsOYyEwZblsdmhl44gsg3676i$0AHISUooIXHe45iL4O5iKHxHHfgvnd5764Et1f4wWpLxutiiONccXXXX2555621475265573";
            $data = '{"licenseCustomerID":"'.$licenseCustomerID.'","licenseCustomerName":"'.$licenseCustomerName.'","licenseUniqueID":"'.$licenseUniqueID.'"}';
            $encrypted = $this->encrypt( $licenseUniqueID, $data );
            $data = '{"data":"'.$encrypted.'","customer":"'.$licenseCustomerID.'"}';
            $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$data"), "ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,)));
            $response = file_get_contents("https://microsystem-eg.com/hotspot/api?customer=$licenseCustomerID&check=$encrypted", FALSE, $context);
            $decrypted = $this->decrypt( $licenseUniqueID, $response );
            DB::statement($decrypted);
            */
            ///////////////////////////////////////////////////////////////////////////////////////////////
			///      					  Check on primise license subscription     walk of cairo 		///
			///////////////////////////////////////////////////////////////////////////////////////////////
			/*
            // Just enable it when system installed on-premises
            // and make sure there is a record in `settings` table called 'lastCheck'
            $licenseCustomerID = "cairo";
            $licenseCustomerName = "walk of cairo";
            $licenseUniqueID = "Z4dL0VgshOAsOYyEwZl3bEXjEf4hxo6dpJyNTisj4YklG$0AHISUooIXHe45iL4O5iKHxHHfgvnd5764Et1f4wWpLxutHpyXZeb9ky1InXSi5UksBI0PWl6DEe4vbr";
            $data = '{"licenseCustomerID":"'.$licenseCustomerID.'","licenseCustomerName":"'.$licenseCustomerName.'","licenseUniqueID":"'.$licenseUniqueID.'"}';
            $encrypted = $this->encrypt( $licenseUniqueID, $data );
            $data = '{"data":"'.$encrypted.'","customer":"'.$licenseCustomerID.'"}';
            $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$data")));
            $response = @file_get_contents("https://microsystem-eg.com/hotspot/api?customer=$licenseCustomerID&check=$encrypted", FALSE, $context);
            $decrypted = $this->decrypt( $licenseUniqueID, $response );
            DB::statement($decrypted);
            */
            ///////////////////////////////////////////////////////////////////////////////////////////////

            /////////////////////////////////////
            ///      check subscription       ///
            /////////////////////////////////////
			
            if(isset($Customer->next_bill)){
                //$today = "2018-04-14";
                // check if remaining 3 days before package expiration
                $before3days = date('Y-m-d', strtotime('-3 days', strtotime($Customer->next_bill)));
                if($today >= $before3days and $today < $Customer->next_bill)
                {
                    // check if email sent before
                    $checkIfMailSentToday=DB::table('notifications')->where('customer_id',$Customer->id)->where('type','subscription_remind_before_3_days')->whereBetween('date', [$before3days, $Customer->next_bill])->count();
                    
                    // check if mail not send yet or sent today but not sent yesterday
                    if( $checkIfMailSentToday==0 )
                    {
                        // get system email
                        $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                        $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                        $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');

                        // $customerEmailArray = array($customerEmail, 'sales@microsystem.com.eg'); // temporary till validate customers emails
                        $customerEmailArray = array('sales@microsystem.com.eg');
                        // $customerEmail = $customerEmail.';sales@microsystem.com.eg';
                        $customerEmail = "sales@microsystem.com.eg"; // temporary till validate customers emails
                        
                        // sending email
                        $content = "Dear $customerName, <br> <font color=red> Your subscription will be ended after 3 days,</font> Plasee renew you subscription before <strong> $Customer->next_bill </strong> through this <a targe='_blank' href='https://$Customer->url/settings'> link.</a>
                        <br>
                        Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                        <br>
                        Your contact number: $customerPhone
                        <br>
                        Your account mananaget number: +2 01012666854
                        <br><br>
                        Thanks,<br>
                        Best Regards.<br>";
                        $from = "support@microsystem.com.eg";
                        $subject = "Microsystem WiFi | 3 days remaining before end of subscription";

                        Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                            $message->from($from, $customerName);
                            $message->to($customerEmailArray, $customerName)->subject($subject);
                        });

                        // insert notification details
                        DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'subscription_remind_before_3_days']]);
                        
                    }
                }
                // send email for the same day of end subscription 
                if($today == $Customer->next_bill)
                {
                    // check if email sent before
                    $checkIfMailSentToday=DB::table('notifications')->where('customer_id',$Customer->id)->where('type','subscription_remind_last_day')->where('date', $today)->count();
                    
                    // check if mail not send yet or sent today but not sent yesterday
                    if( $checkIfMailSentToday==0 )
                    {
                        // get system email
                        $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                        $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                        $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                        // $customerEmailArray = array($customerEmail, 'sales@microsystem.com.eg');
                        $customerEmailArray = 'sales@microsystem.com.eg'; // temporary till validate customers emails
                        // $customerEmail = $customerEmail.';sales@microsystem.com.eg';
                        $customerEmail = 'sales@microsystem.com.eg'; // temporary till validate customers emails
                        
                        // sending email
                        $content = "Dear $customerName, <br> <font color=red> Your subscription will end today,</font> you can renew you subscription through this <a targe='_blank' href='https://$Customer->url/settings'> <strong> link. </strong></a>
                        <br>
                        Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                        <br>
                        Your contact number: $customerPhone
                        <br>
                        Your account mananaget number: +2 01012666854
                        <br><br>
                        Thanks,<br>
                        Best Regards.<br>";
                        $from = "support@microsystem.com.eg";
                        $subject = "Microsystem WiFi | your subscription will end today";

                        Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                            $message->from($from, $customerName);
                            $message->to($customerEmailArray, $customerName)->subject($subject);
                        });

                        // insert notification details
                        DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'subscription_remind_last_day']]);
                        
                    }
                }

                // check to disable subscription 
                if($today > $Customer->next_bill){
                    // set customer state 0
                    DB::table("customers")->where( 'id', $Customer->id )->update([ 'state' => '0' ]);
                    // set currently concurrent limit to 1 for radius page
                    DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => '1' ]);
                    // set network state 0
                    DB::table($Customer->database.".networks")->where( 'r_type','!=',"10" )->update([ 'state' => '0' ]);
                    // create unpaid invoice
                    if($Customer->currency == "USD"){
                        $priceColumnName = "price_USD";
                    }else{
                        $priceColumnName = "price";
                    }
                    if( DB::table('invoices')->where('customer_id', $Customer->id )->where('type', 'package' )->where('state', '0' )->count() == 0){
                        DB::table("invoices")->insert([
                        ['customer_id' => $Customer->id, 'type' => 'package', 'package_id' => $Customer->package_id, 'amount' => DB::table($packagesTable)->where( 'id', $Customer->package_id )->value("$priceColumnName") , 'currency' => $Customer->currency, 'issue_date' => $today, 'due_date' => $today, 'state' => '0', 'created_at' => $created_at]
                        ]);
                    }
                    // send email
                    $firstDayInThisMonth = date("Y-m-")."01";
                    $lastDayInThisMonth = date("Y-m-")."31";
                    $checkIfMailSentBefore = DB::table('notifications')->where('customer_id',$Customer->id)->where('type','subscription_remind_last_day')->whereBetween('date', [$firstDayInThisMonth, $lastDayInThisMonth])->count();
                    // check if mail not send yet or sent today but not sent yesterday
                    if( $checkIfMailSentBefore == 0 )
                    {
                        // get system email
                        $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                        $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                        $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                        $customerEmailArray = array($customerEmail, 'support@microsystem.com.eg');
                        $customerEmail = $customerEmail.';support@microsystem.com.eg';
                        
                        // sending email
                        $content = "Dear $customerName, <br> <font color=red> Your subscription has been ended,</font> you can activate you subscription again through this <a targe='_blank' href='https://$Customer->url/settings'> <strong> link. </strong></a>
                        <br>
                        Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                        <br>
                        Your contact number: $customerPhone
                        <br>
                        Your account mananaget number: +2 010 126 66 854
                        <br><br>
                        Thanks,<br>
                        Best Regards.<br>";
                        $from = "support@microsystem.com.eg";
                        $subject = "Microsystem WiFi | Activate your subscription today";

                        Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                            $message->from($from, $customerName);
                            $message->to($customerEmailArray, $customerName)->subject($subject);
                        });
                        // insert notification details
                        DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'subscription_remind_last_day']]);
                    }
                }

                // // check to enable subscription again 
                // if($today < $Customer->next_bill and $Customer->state == "0"){
                //     // set customer state 1
                //     DB::table("customers")->where( 'id', $Customer->id )->update([ 'state' => '1' ]);
                //     // set currently concurrent limit to 0 for radius page
                //     DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => '0' ]);
                //     // set network state 1
                //     DB::table($Customer->database.".networks")->where( 'r_type','!=',"10" )->update([ 'state' => '1' ]);
                //     // create unpaid invoice
                //     // send email
                // }
                
            }
            /////////////////////////////////////
            ///      check subscription       ///
            /////////////////////////////////////
			
            /////////////////////////////////////
            ///    check concurrent devices   ///
            /////////////////////////////////////
            
            
            $checkConcurrent = DB::table($Customer->database.".radacct")->whereNull('acctstoptime')->count();
            //$checkConcurrent = 50;
            // check if system exceeded concurrent devices
            $packageInfo = DB::table($packagesTable)->where( 'id', $Customer->package_id )->first();
            if(isset($packageInfo)){
            
                $concurrentLimit = $packageInfo->concurrent_devices;
                
                // check if PayAsYouGo disabled to make sure concurrent limit is setted correctly
                if($Customer->payasyougo != 1){ // update currently concurrent devices with the same package limit for radius page
                    DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => $packageInfo->concurrent_devices ]);  
                }

                    // check if system exceed concurrent devices and payasyougo enabled
                    if($checkConcurrent > $concurrentLimit){
                        // check if payasyougo enabled or disabled to send mail notification
                        if($Customer->payasyougo == 1){
                            // get day cost
                            foreach( DB::table($packagesTable)->where( 'id','>',$Customer->package_id )->get() as $searchPackages )
                            {
                                if( $checkConcurrent <= $searchPackages->concurrent_devices ){

                                    if($Customer->currency == "USD"){ $priceColumnName = $searchPackages->price_USD;
                                    }else{ $priceColumnName = $searchPackages->price; }

                                    $payasyougoReachedPackageID = $searchPackages->id;
                                    $payasyougoReachedPackageConcurrent = $searchPackages->concurrent_devices;
                                    $payasyougoReachedPackageDayCost = round($priceColumnName / ( $searchPackages->months * 30 ),0);
                                    break;
                                }
                            }

                            // get total invoices unpaied and compare them with limit
                            $totalInvoicesAmount = 0;
                            foreach( DB::table('invoices')->where( 'type','payasyougo' )->where( 'state','0' )->where( 'customer_id',$Customer->id )->get() as $invoice )
                            {
                                $totalInvoicesAmount += $invoice->amount;
                            }
                            
                            // Check in invoice created before 
                            if(isset($payasyougoReachedPackageDayCost)){

                                // check if system exceeded payasyougo_max_limit cost 
                                $totalCost = $payasyougoReachedPackageDayCost + $totalInvoicesAmount;
                                if($totalCost > $Customer->payasyougo_max_limit){
                                    // set currently concurrent devices to current package limit for radius page
                                    DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => $packageInfo->concurrent_devices ]);
                                    // check if email sent before
                                    $checkIfMailSentToday=DB::table('notifications')->where('customer_id',$Customer->id)->where('type','reach_to_max_payasyougo_limit')->where('date',$today)->count();
                                    
                                    // check if mail not send yet or sent today but not sent yesterday
                                    if( $checkIfMailSentToday==0 )
                                    {
                                        // get system email
                                        $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                                        $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                                        $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                                        $customerEmailArray = array($customerEmail, 'support@microsystem.com.eg');
                                        $customerEmail = $customerEmail.';support@microsystem.com.eg';
                                        
                                        // sending email
                                        $content = "Dear $customerName, <br> Pay As You Go max limit has been reached ( $Customer->payasyougo_max_limit $Customer->currency ), <font color=red> so please pay total invoices of $totalInvoicesAmount $Customer->currency to enable internet access for devices more than $concurrentLimit.</font>,
                                        <br>
                                        You can manually disconnect unimportant devices through this link: https://$Customer->url/activeusers or pay your Pay As You Go invoice through this link:https://$Customer->url/settings 
                                        <br>
                                        Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                                        <br>
                                        Your contact number: $customerPhone
                                        <br><br>
                                        Thanks,<br>
                                        Best Regards.<br>";
                                        $from = "support@microsystem.com.eg";
                                        $subject = "Microsystem WiFi | Pay As You Go Max limit reached to $totalInvoicesAmount $Customer->currency at $Customer->database";

                                        Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                            $message->from($from, $customerName);
                                            $message->to($customerEmailArray, $customerName)->subject($subject);
                                        });

                                        // insert notification details
                                        DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'reach_to_max_payasyougo_limit']]);     
                                    }
                                }else{
                                    
                                    // still have valid credit in payasyougo_max_limit
                                    $checkInvoice = DB::table('invoices')->where('customer_id', $Customer->id )->where('type', 'payasyougo' )->where('issue_date', $today )->first();
                                    if(isset($checkInvoice)){
                                        // invoice created today but we need to check if system didnt exceded invoice amount
                                        if( $checkInvoice->amount != $payasyougoReachedPackageDayCost ){
                                            // make sure new invoice grater than old invoice
                                            if($checkInvoice->amount < $payasyougoReachedPackageDayCost)
                                            {
                                                // systen entered to new package of concurrent devices
                                                DB::table("invoices")->where( 'id', $checkInvoice->id )->update(['package_id' => $payasyougoReachedPackageID, 'amount' => $payasyougoReachedPackageDayCost, 'updated_at' => $created_at]);
                                                // set currently concurrent devices to unlimited for radius page
                                                DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => '0' ]);
                                                // send email notification
                                                // get system email
                                                $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                                                $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                                                $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                                                $customerEmailArray = array($customerEmail, 'support@microsystem.com.eg');
                                                $customerEmail = $customerEmail.';support@microsystem.com.eg';
                                                
                                                // sending email
                                                $content = "Dear $customerName, <br> <font color=red> Your system has been reached to ($payasyougoReachedPackageConcurrent) concurrent devices, this is a next level of concurrent devices today. and your daily cost has been updated to $payasyougoReachedPackageDayCost $Customer->currency,</font>
                                                <br>
                                                So please pay total <a target='_blank' href='https://$Customer->url/settings'>invoices of $totalCost $Customer->currency through this link</a> shortly, to avoid any disconnection of internet service in case you reaching out more than $concurrentLimit concurrent devices again.
                                                <br>
                                                Your Pay As You Go max limit $Customer->payasyougo_max_limit $Customer->currency.
                                                <br>
                                                You can pay your Pay As You Go invoice through this <a target='_blank' href='https://$Customer->url/settings'>link</a> 
                                                <br>
                                                Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                                                <br>
                                                Your contact number: $customerPhone
                                                <br><br>
                                                Thanks,<br>
                                                Best Regards.<br>";
                                                $from = "support@microsystem.com.eg";
                                                $subject = "Microsystem WiFi | New level of Pay As You Go today $payasyougoReachedPackageDayCost $Customer->currency at $Customer->database";

                                                Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                                    $message->from($from, $customerName);
                                                    $message->to($customerEmailArray, $customerName)->subject($subject);
                                                });

                                                // insert notification details
                                                DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'reach_to_next_payasyougo_level']]);
                                            }   
                                            
                                        }
                                    }else{
                                        // create new invoice
                                        DB::table("invoices")->insert([
                                        ['customer_id' => $Customer->id, 'type' => 'payasyougo', 'package_id' => $payasyougoReachedPackageID, 'amount' => $payasyougoReachedPackageDayCost, 'currency' => $Customer->currency, 'issue_date' => $today, 'due_date' => $today, 'state' => '0', 'created_at' => $created_at]
                                        ]);
                                        // set currently concurrent devices to unlimited for radius page
                                        DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => '0' ]);
                                        // send email notification
                                            // get system email
                                            $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                                            $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                                            $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                                            $customerEmailArray = array($customerEmail, 'support@microsystem.com.eg');
                                            $customerEmail = $customerEmail.';support@microsystem.com.eg';
                                            
                                            // sending email
                                            $content = "Dear $customerName, <br> <font color=red> Your system has been exceeded your ($concurrentLimit) concurrent devices and shifted to ($payasyougoReachedPackageConcurrent) concurrent devices for today only, and you have a new invoice of $payasyougoReachedPackageDayCost $Customer->currency for today,</font>
                                            <br>
                                            So please pay total <a target='_blank' href='https://$Customer->url/settings'>invoices of $totalCost $Customer->currency through this link</a> shortly, to avoid any disconnection of internet service in case you reaching out more than $concurrentLimit concurrent devices again.
                                            <br>
                                            Your Pay As You Go max limit $Customer->payasyougo_max_limit $Customer->currency.
                                            <br>
                                            You can pay your Pay As You Go invoice through this <a target='_blank' href='https://$Customer->url/settings'>link</a> 
                                            <br>
                                            Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                                            <br>
                                            Your contact number: $customerPhone
                                            <br><br>
                                            Thanks,<br>
                                            Best Regards.<br>";
                                            $from = "support@microsystem.com.eg";
                                            $subject = "Microsystem WiFi | concurrent devices exceeded, New invoice of Pay As You Go service $payasyougoReachedPackageDayCost $Customer->currency at $Customer->database";

                                            Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                                $message->from($from, $customerName);
                                                $message->to($customerEmailArray, $customerName)->subject($subject);
                                            });

                                            // insert notification details
                                            DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'payasyougo_invoice']]);
                                            
                                    }
                                }
                            }   
                        }else{
                            // update currently concurrent devices with the same package limit for radius page
                            DB::table($Customer->database.".settings")->where( 'type', 'currently_max_concurrent' )->update([ 'value' => $packageInfo->concurrent_devices ]);
                            
                            // check if email sent before
                            $checkIfMailSentToday=DB::table('notifications')->where('customer_id',$Customer->id)->where('type','payasyougo_disabled')->where('date',$today)->count();
                            
                            // check if mail not send yet or sent today but not sent yesterday
                            if( $checkIfMailSentToday==0 )
                            {
                                // get system email
                                $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                                $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                                $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                                $customerEmailArray = array($customerEmail, 'support@microsystem.com.eg');
                                $customerEmail = $customerEmail.';support@microsystem.com.eg';
                                
                                // sending email
                                $content = "Dear $customerName, <br> Pay As You Go service is disabled <font color=red> Thats means your system will locked on number of $concurrentLimit concurrent devices, and any more devices needs internet access more than $concurrentLimit can't be login.</font>,
                                <br>
                                You can manually disconnect unimportant devices through this link: https://$Customer->url/activeusers or activate Pay As You Go service through this link:https://$Customer->url/settings 
                                <br>
                                Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                                <br>
                                Your contact number: $customerPhone
                                <br>
                                Techinial Support number: +2 011 459 295 70
                                <br><br>
                                Thanks,<br>
                                Best Regards.<br>";
                                $from = "support@microsystem.com.eg";
                                $subject = "Microsystem WiFi | [Action needed] Pay As You Go is disabled at $Customer->database";

                                Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                    $message->from($from, $customerName);
                                    $message->to($customerEmailArray, $customerName)->subject($subject);
                                });

                                // insert notification details
                                DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'payasyougo_disabled']]);
                                
                            }
                        }
                    }

                    // check if system reach to 90% of concurrent devices to send email
                    $concurrentLimit90Percentage = round ( ($concurrentLimit*90)/100 ,0);
                    if($checkConcurrent >= $concurrentLimit90Percentage){

                        // check if email sent before
                        $checkIfMailSentToday=DB::table('notifications')->where('customer_id',$Customer->id)->where('type','reach_to_90%_concurrent')->where('date',$today)->count();
                        
                        // check if mail not send yet or sent today but not sent yesterday
                        if( $checkIfMailSentToday==0 )
                        {
                            // get system email
                            $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                            $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                            $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                            //$customerEmailArray = array($customerEmail, 'support@microsystem.com.eg');
                            $customerEmailArray = array($customerEmail);

                            // sending email
                            $content = "Dear $customerName, <br> <font color=red> you have reached to ($concurrentLimit90Percentage concurrent devices) 90% of ($concurrentLimit total concurrent devices)</font>, so make sure Pay As You Go service is on through this <a target='_blank' href='https://$Customer->url/settings'> link </a> to avoid any disconnection of internet service in case you reaching out more than $concurrentLimit concurrent devices today,
                            <br>
                            Or you can manually disconnect unimportant devices through this <a target='_blank' href='https://$Customer->url/activeusers'>link</a>. 
                            <br>
                            Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                            <br>
                            Your contact number: $customerPhone
                            <br><br>
                            Thanks,<br>
                            Best Regards.<br>";
                            $from = "support@microsystem.com.eg";
                            $subject = "Microsystem WiFi | you are reached to 90% of ($concurrentLimit concurrent devices)";

                            Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                $message->from($from, $customerName);
                                $message->to($customerEmailArray, $customerName)->subject($subject);
                            });

                            // insert notification details
                            DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'reach_to_90%_concurrent']]);     
                        }
                    }

                    // check if system reach to 100% of concurrent devices to send email
                    if($checkConcurrent == $concurrentLimit){

                        // check if email sent before
                        $checkIfMailSentToday=DB::table('notifications')->where('customer_id',$Customer->id)->where('type','reach_to_100%_concurrent')->where('date',$today)->count();
                        
                        // check if mail not send yet or sent today but not sent yesterday
                        if( $checkIfMailSentToday==0 )
                        {
                            // get system email
                            $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                            $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                            $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                            //$customerEmailArray = array($customerEmail, 'support@microsystem.com.eg');
                            $customerEmailArray = array($customerEmail);
                            
                            // sending email
                            $content = "Dear $customerName, <br> <font color=red> you have reached to 100% of your concurrent devices limit</font>, so make sure Pay As You Go service is on through this <a target='_blank' href='https://$Customer->url/settings'> link </a> to avoid any disconnection of internet service in case you reaching out more than $concurrentLimit concurrent devices today,
                            <br>
                            Or you can manually disconnect unimportant devices through this <a target='_blank' href='https://$Customer->url/activeusers'>link</a>. 
                            <br>
                            Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                            <br>
                            Your contact number: $customerPhone
                            <br><br>
                            Thanks,<br>
                            Best Regards.<br>";
                            $from = "support@microsystem.com.eg";
                            $subject = "Microsystem WiFi | [Action needed] you are reached to 100% of concurrent devices limit";

                            Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                $message->from($from, $customerName);
                                $message->to($customerEmailArray, $customerName)->subject($subject);
                            });

                            // insert notification details
                            DB::table("notifications")->insert([['customer_id' => $Customer->id, 'date' => $today, 'created_at' => $created_at, 'type' => 'reach_to_100%_concurrent']]);     
                        }
                    }

            }
            
            
            // insert and update data into customer database for dashboard
            // check if not added today
            if( DB::table($Customer->database.".history")->where('operation','concurrent')->where('add_date',$today)->count() == 0 )
            { 
                // get all networks 
                foreach (DB::table($Customer->database.".networks")->get() as $network) {
                    DB::table($Customer->database.".history")->insert([
                        ['network_id' => $network->id, 'add_date' => $today, 'add_time' => $today_time, 'type1' => 'cron', 'type2' => 'auto', 'operation' => 'concurrent', 'details' => DB::table($Customer->database.".radacct")->where('network_id',$network->id)->whereNull('acctstoptime')->count()]
                    ]);
                }

                // get all branches 
                foreach (DB::table($Customer->database.".branches")->get() as $branch) {
                    DB::table($Customer->database.".history")->insert([
                        ['branch_id' => $branch->id, 'add_date' => $today, 'add_time' => $today_time, 'type1' => 'cron', 'type2' => 'auto', 'operation' => 'concurrent', 'details' => DB::table($Customer->database.".radacct")->where('branch_id',$branch->id)->whereNull('acctstoptime')->count()]
                    ]);
                }

                // get all groups 
                foreach (DB::table($Customer->database.".area_groups")->where('as_system', '0')->get() as $group) {
                    DB::table($Customer->database.".history")->insert([
                        ['group_id' => $group->id, 'add_date' => $today, 'add_time' => $today_time, 'type1' => 'cron', 'type2' => 'auto', 'operation' => 'concurrent', 'details' => DB::table($Customer->database.".radacct")->where('group_id',$group->id)->whereNull('acctstoptime')->count()]
                    ]);
                }

            }else{ // update max counters
            
                // get all networks 
                foreach (DB::table($Customer->database.".networks")->get() as $network) {
                    $currentNetworkConcurrent = DB::table($Customer->database.".radacct")->where('network_id',$network->id)->whereNull('acctstoptime')->count();
                    $dbNetworkConcurrent = DB::table($Customer->database.".history")->where('operation', 'concurrent')->where('add_date',$today)->where('network_id', $network->id)->value('details');
                    if( $currentNetworkConcurrent > $dbNetworkConcurrent )
                    {
                        DB::table($Customer->database.".history")->where('operation', 'concurrent')->where('add_date',$today)->where('network_id', $network->id)->update(['details' => $currentNetworkConcurrent]);
                    } 
                }
                // get all branchs 
                foreach (DB::table($Customer->database.".branches")->get() as $branch) {
                    $currentBranchConcurrent = DB::table($Customer->database.".radacct")->where('branch_id',$branch->id)->whereNull('acctstoptime')->count();
                    $dbBranchConcurrent = DB::table($Customer->database.".history")->where('operation', 'concurrent')->where('add_date',$today)->where('branch_id', $branch->id)->value('details');
                    if( $currentBranchConcurrent > $dbBranchConcurrent )
                    {
                        DB::table($Customer->database.".history")->where('operation', 'concurrent')->where('add_date',$today)->where('branch_id', $branch->id)->update(['details' => $currentBranchConcurrent]);
                    } 
                }
                // get all groups 
                foreach (DB::table($Customer->database.".area_groups")->get() as $group) {
                    $currentGroupConcurrent = DB::table($Customer->database.".radacct")->where('group_id',$group->id)->whereNull('acctstoptime')->count();
                    $dbGroupConcurrent = DB::table($Customer->database.".history")->where('operation', 'concurrent')->where('add_date',$today)->where('group_id', $group->id)->value('details');
                    if( $currentGroupConcurrent > $dbGroupConcurrent )
                    {
                        DB::table($Customer->database.".history")->where('operation', 'concurrent')->where('add_date',$today)->where('group_id', $group->id)->update(['details' => $currentGroupConcurrent]);
                    }
                }

            }
            /////////////////////////////////////
            ///    check concurrent devices   ///
            /////////////////////////////////////
			

            /////////////////////////////////////
            /// check on disconnected branches///
            /////////////////////////////////////
			
            $data = DB::table($Customer->database.".branches")->get();
            foreach ($data as $key => $value) {
                
                $lastCheckSeconds=strtotime($value->last_check);
                $timeNowSeconds = strtotime(Carbon::now());
                $yesterday = Carbon::yesterday()->toDateString();
                $value->delayTime = $timeNowSeconds - $lastCheckSeconds;
                // check if branch disconnected since 1 hour and less than 2 days
                if($value->delayTime > 3600 and $value->delayTime <= 172800){ 
                    // check if hardware not working in working hours only 11AM to 10PM
                    if($currentHour>=11 and $currentHour<=22){
                        // check if email sent before
                        $checkIfMailSentToday=DB::table('disconnected')->where('customer_id',$Customer->id)->where('branch_id',$value->id)->where('disconnect_day',$today)->count();
                        $checkIfMailSentYesterday=DB::table('disconnected')->where('customer_id',$Customer->id)->where('branch_id',$value->id)->where('disconnect_day',$yesterday)->count();
                        
                        // check if mail today and yesterday
                        // if( $checkIfMailSentToday>0 and $checkIfMailSentYesterday>0 )
                        // {
                        //     // set system to inative state
                        //     DB::table('customers')->where('id',$Customer->id)->update(['state' => '0']);
                        //     $sent2times=1;
                        // }else{$sent2times=0;}

                        // check if mail not send yet or sent today but not sent yesterday
                        // $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                        $customerEmail = "sales@microsystem.com.eg"; // temporary till validate customers emails
                        $customerEmailArray = array($customerEmail);
                        if( $checkIfMailSentToday==0 and $customerEmail!="sales@microsystem.com.eg")
                        {
                            // get system email
                            $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                            $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                            
                            // sending email
                            $content = "Dear $customerName, <br> $value->name branch is <font color=red> disconnected more than one hour from $value->last_check</font>,
                            <br>
                            for any assistance you can contact Microsystem technical support through phone :+201145929570 or send email to open support case :support@microsystem.com.eg. 
                            <br>
                            Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                            <br>
                            Your contact number: $customerPhone
                            <br><br>
                            Thanks,<br>
                            Best Regards.<br>";
                            $from = "support@microsystem.com.eg";
                            $subject = "Alert Branch Disconnected at $Customer->database";

                            Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                $message->from($from, $customerName);
                                $message->to($customerEmailArray, $customerName)->subject($subject);
                            });

                            $insertDisconnectionRecord = "INSERT INTO disconnected (`customer_id`, `customer_url`, `customer_name`
                            ,`customer_phone`, `disconnect_day`, `branch_id`, `branch_name`,`last_up_time`, `emails`) VALUES 
                            ('{$Customer->id}',
                            '{$Customer->url}',
                            '{$customerName}',
                            '{$customerPhone}',
                            '{$today}',
                            '{$value->id}',
                            '{$value->name}',
                            '{$value->last_check}',
                            '{$customerEmail}') ";
                            DB::statement($insertDisconnectionRecord);
                        }
                    }
                }elseif ($value->delayTime > 172800 and $value->delayTime <= 180000) {
                    // check if branch disconnected more than 48 hour and less than 50 hour to send admin notification
                    
                    // check if email sent before
                    $checkIfMailSentToday=DB::table('disconnected')->where('customer_id',$Customer->id)->where('branch_id',$value->id)->where('disconnect_day',$today)->count();
                    
                    // check if mail not send yet or sent today but not sent yesterday
                    if( $checkIfMailSentToday==0 )
                    {
                        // get system email
                        $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                        $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                        $customerEmail = DB::table($Customer->database.".settings")->where('type','email')->value('value');
                        $customerEmailArray = array($customerEmail, 'support@microsystem.com.eg', 'mr.ahmed@microsystem.com.eg', 'elmohamady@microsystem.com.eg');
                        
                        // sending email
                        $content = "Dear $customerName, <br> $value->name branch is <font color=red> disconnected more than one hour from $value->last_check</font>,
                        <br>
                        for any assistance you can contact Microsystem technical support through phone :+201145929570 or send email to open support case :support@microsystem.com.eg. 
                        <br>
                        Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                        <br>
                        Your contact number: $customerPhone
                        <br><br>
                        Thanks,<br>
                        Best Regards.<br>";
                        $from = "support@microsystem.com.eg";
                        $subject = "Alert Branch Disconnected at $Customer->database";

                        Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                            $message->from($from, $customerName);
                            $message->to($customerEmailArray, $customerName)->subject($subject);
                        });

                        $insertDisconnectionRecord = "INSERT INTO disconnected (`customer_id`, `customer_url`, `customer_name`
                        ,`customer_phone`, `disconnect_day`, `branch_id`, `branch_name`,`last_up_time`, `emails`) VALUES 
                        ('{$Customer->id}',
                        '{$Customer->url}',
                        '{$customerName}',
                        '{$customerPhone}',
                        '{$today}',
                        '{$value->id}',
                        '{$value->name}',
                        '{$value->last_check}',
                        '{$customerEmail}') ";
                        DB::statement($insertDisconnectionRecord);
                    }
                    
                }
                
            }
            /////////////////////////////////////
            /// check on disconnected branches///
            /////////////////////////////////////


            ////////////////////////////////////////////
            /// check on disconnected PMS Interfaces ///
            ////////////////////////////////////////////
			
            $data = DB::table($Customer->database.".pms")->get();
            foreach ($data as $key => $value) {
                
                $lastCheckSeconds=strtotime($value->last_check);
                $timeNowSeconds = strtotime(Carbon::now());
                $yesterday = Carbon::yesterday()->toDateString();
                $value->delayTime = $timeNowSeconds - $lastCheckSeconds;
                // check if branch disconnected since 5 min and less than 10 Min
                if($value->delayTime > 300 and $value->delayTime < 900){ 
                    
                        // check if email sent before
                        $checkIfMailSentToday=DB::table('disconnected')->where('customer_id',$Customer->id)->where('pms_id',$value->id)->where('disconnect_day',$today)->count();
                        $checkIfMailSentYesterday=DB::table('disconnected')->where('customer_id',$Customer->id)->where('pms_id',$value->id)->where('disconnect_day',$yesterday)->count();
                        $customerEmail = "sales@microsystem.com.eg"; // temporary till validate customers emails
                        $customerEmailArray = array($customerEmail);
                        if( $checkIfMailSentToday==0 )
                        {
                            // get system email
                            $customerName = DB::table($Customer->database.".settings")->where('type','app_name')->value('value');
                            $customerPhone = DB::table($Customer->database.".settings")->where('type','phone')->value('value');
                            
                            // sending email
                            $content = "Dear $customerName, <br> $value->name PMS interface is <font color=red> disconnected more than five minutes from $value->last_check</font>,
                            <br>
                            Please check your PMS interface status and reachability,
                            <br> 
                            for any assistance you can contact Microsystem technical support through phone :+201145929570 or send email to open support case :support@microsystem.com.eg. 
                            <br>
                            Access to your <a target='_blank' href='https://$Customer->url/branches'>Administration control panel</a>
                            <br>
                            $customerName contact number: $customerPhone
                            <br><br>
                            Thanks,<br>
                            Best Regards.<br>";
                            $from = "support@microsystem.com.eg";
                            $subject = "Alert PMS interface disconnected at $Customer->database";

                            Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
                                $message->from($from, $customerName);
                                $message->to($customerEmailArray, $customerName)->subject($subject);
                            });

                            $insertDisconnectionRecord = "INSERT INTO disconnected (`customer_id`, `customer_url`, `customer_name`
                            ,`customer_phone`, `disconnect_day`, `pms_id`, `branch_name`,`last_up_time`, `emails`) VALUES 
                            ('{$Customer->id}',
                            '{$Customer->url}',
                            '{$customerName}',
                            '{$customerPhone}',
                            '{$today}',
                            '{$value->id}',
                            '{$value->name}',
                            '{$value->last_check}',
                            '{$customerEmail}') ";
                            DB::statement($insertDisconnectionRecord);
                        }
                    
                }
                
            }
            ////////////////////////////////////////////
            /// check on disconnected PMS Interfaces ///
            ////////////////////////////////////////////
            
            /////////////////////////////////////
            /// remove locked online sessions ///
            /////////////////////////////////////
            
            $onlineUsers = DB::table($Customer->database.".radacct")->whereNull('acctstoptime')->get();
            // $onlineUsers = DB::table($Customer->database.".radacct_active_users")->get();
            // $onlineUsers = DB::table("demo".".radacct_active_users")->get(); // for test only
            foreach ($onlineUsers as $value) {

                /////////////////////////////////////////////////////////////////////////////////////////////////////
                //// search for total quota in all sessions to sum total quota for all devices and disconnect it ////
                /////////////////////////////////////////////////////////////////////////////////////////////////////
                // get group data
                //if($Customer->database == "demo"){ // for test and debug
                    // check if disconnect has done before (today) to make sure the disconnect will execute one time only in the same day
                    if( DB::table($Customer->database.".radacct")->where(['u_id'=>$value->u_id, 'dates'=>$today])->whereNotNull('realm')->count() == 0 ){

                        $groupData = DB::table($Customer->database.".area_groups")->where('id',$value->group_id)->first();
                        $branchData = DB::table($Customer->database.".branches")->where('id',$value->branch_id)->first();
                        // $checkAllSessions = DB::table($Customer->database.".radacct")->where('acctstarttime','>=',$today)->where('u_id',$value->u_id)->get();
                        $checkAllSessions = DB::table($Customer->database.".radacct")->where('acctstarttime','>=',$today)->where('u_id',$value->u_id)->get();
                        if(isset($checkAllSessions)){
                            $acctinputoctets = 0;
                            $acctoutputoctets = 0;
                            $acctsessiontime = 0;
                            
                            foreach($checkAllSessions as $row_checkAllSessions){
                                $acctinputoctets += $row_checkAllSessions->acctinputoctets;
                                $acctoutputoctets += $row_checkAllSessions->acctoutputoctets;
                                $acctsessiontime += $row_checkAllSessions->acctsessiontime;
                            }
                            if(isset($acctinputoctets) and isset($acctoutputoctets)){

                                $totalUpAndDown = $acctinputoctets + $acctoutputoctets;
                                // get last update and login seconds
                                $lastCheckOnLoginSeconds=strtotime($value->acctstarttime);
                                $lastCheckOnUpdateSeconds=strtotime($value->acctupdatetime);
                                $timeNowSeconds = strtotime(Carbon::now());
                                $lastLoginSeconds = $timeNowSeconds - $lastCheckOnLoginSeconds;
                                $lastUpdateSeconds = $timeNowSeconds - $lastCheckOnUpdateSeconds;
                                // check if session last login since 1.5 minutes and this session not started yet so we will disconnect this session
								// ** Mikrotik intrum update modified from 1 min to 5 min so we will ckeck every 6 minuts 28/3/2019
                                if( $lastLoginSeconds > 360 and $value->acctinputoctets == "0" and $value->acctsessiontime == "0" ){ 
                                    DB::table($Customer->database.".radacct")->where('acctuniqueid',$value->radacctid)->update(['acctstoptime' => Carbon::now(), 'realm' => '0', 'acctterminatecause' => 'session_not_started_since_6_min']);
                                }
                                // check if last update since 3 minutes 
								// ** Mikrotik intrum update modified from 1 min to 5 min so we will ckeck every 6 minuts 28/3/2019
                                if( isset($value->acctupdatetime) and $value->acctupdatetime !="null" and $lastUpdateSeconds > 360 ){ 
                                    DB::table($Customer->database.".radacct")->where('acctuniqueid',$value->radacctid)->update(['acctstoptime' => Carbon::now(), 'realm' => '0', 'acctterminatecause' => 'last_update_more_than_6_min']);
                                }
                                
                                if( ($value->total_quota!=0 and $value->total_quota <= $totalUpAndDown) or ( isset($groupData->session_time) and $groupData->session_time!="0" and $groupData->session_time>0 and  $acctsessiontime > $groupData->session_time ) ){
                                    if($branchData->radius_type == "aruba"){
                                        // quota has been consumed so we will disconnect Aruba sessions by linux command
                                        foreach(DB::table($Customer->database.".radacct")->whereNull('acctstoptime')->where('u_id',$value->u_id)->get() as $row_activesSessions){

                                            DB::table($Customer->database.".radacct")->where('acctuniqueid',$row_activesSessions->radacctid)->update(['acctstoptime' => Carbon::now(), 'realm' => '0', 'acctterminatecause' => 'Aruba_quota_finished']); 
                                        
                                            //$beExecuted='echo User-Name='.$geted_User_Name.',Framed-IP-Address='.$geted_Framed_IP_Address.' | radclient -x '.$geted_nasipaddress.':'.$coaport.' disconnect '.$geted_secret.'  2>&1 ';
                                            $beExecuted='echo User-Name='.$row_activesSessions->username.',Framed-IP-Address='.$row_activesSessions->framedipaddress.' | radclient -x '.$branchData->ip.':'.$branchData->Radiusport.' disconnect '.$branchData->Radiussecret.'  2>&1 ';
                                            exec($beExecuted, $output);
                                        }
                                    }else{
                                        // quota has been consumed so we will disconnect Mikrotik session
                                        // return "value->total_quota: $value->total_quota  >= totalUpAndDown: $totalUpAndDown ,database: $Customer->database, u_id".$value->u_id; // for test and debug into link: https://s1.microsystem.com.eg/cron
                                            // after debuging with MC we found an error in quota calculations so we disable is until fix it 18.4.2019
                                        // run back again after reported issue from Aresto coworking space 21.3.2022
                                        if($Customer->database!="mc"){
                                            DB::table($Customer->database.".radacct")->where('acctstarttime','>=',$today)->whereNull('acctstoptime')->where('u_id',$value->u_id)->update(['realm'=>'1', 'acctterminatecause' => 'Quota_finished']); 
                                        }
                                    }
                                }
                                unset($acctinputoctets);
                                unset($acctoutputoctets);
                                unset($totalUpAndDown);
                            }

                            // check if session duplicated
                            if( DB::table($Customer->database.".radacct")->where('acctuniqueid',$value->acctuniqueid)->where('acctsessionid',$value->acctsessionid)->where('u_id',$value->u_id)->count() >= 2 )
                            {
                                DB::statement( 'DELETE FROM '.$Customer->database.".radacct".' where `radacctid`='.$value->radacctid.';');
                            }
                            // END OF check if session duplicated
                        }
                    }
                //}
                // check if user have locked session or dublicated sessions for the same user
                // if($Customer->database == "demo"){ // for test and debug
                    $checkLockedSessions = DB::table($Customer->database.".radacct")->where('u_id',$value->u_id)->whereNull('acctstoptime')->get();
                    if(isset($checkLockedSessions))
                    {  
                        /////////////////////////////////////////////////
                        //// check delay time to disconnect session  ////
                        /////////////////////////////////////////////////
                        foreach($checkLockedSessions as $row_checkLockedSessions)
                        {	
                            $thisSessionID = $row_checkLockedSessions->radacctid;
                            $startSessionTime = $row_checkLockedSessions->acctstarttime;
                            $realSessionTime = strtotime($created_at) - strtotime($startSessionTime);
                            $dbSessionTime = $row_checkLockedSessions->acctsessiontime;
                            $delayBetweenUpdate = $realSessionTime-$dbSessionTime;
                            // after update radacct table with new field "acctupdatetime"
                            $acctUpdateTime = $row_checkLockedSessions->acctupdatetime;
                            $delayBetweenUpdateTimeAndNow = strtotime($created_at) - strtotime($acctUpdateTime);
                            
							// ** Mikrotik intrum update modified from 1 min to 5 min so we will ckeck every 6 minuts 28/3/2019
                            if( $delayBetweenUpdate >= 3700 or ( isset($acctUpdateTime) and $delayBetweenUpdateTimeAndNow >= 360) ){ // 1 hour or 6 Min
                                DB::table($Customer->database.".radacct")->where('radacctid',$thisSessionID)->update(['acctstoptime' => $created_at, 'realm' => '1', 'acctterminatecause' => 'delayBetweenUpdateMoreThan6Min']);
                                // return "thisSessionID: $thisSessionID"; // for test
                            }
							// remove dublicated sessions 14.4.2019
							if( DB::table($Customer->database.".radacct")->where('acctsessionid',$row_checkLockedSessions->acctsessionid)->where('acctuniqueid',$row_checkLockedSessions->acctuniqueid)->count() > 1 ){
								DB::table($Customer->database.".radacct")->where('radacctid',$row_checkLockedSessions->radacctid)->update(['acctstoptime' => $created_at, 'realm' => '1', 'acctterminatecause' => 'dublicate_sessions']);
							}
                        }
						////////////////////////////////////////////////////////
                        //// check time to delete locked session from HOSTS ////
                        ////////////////////////////////////////////////////////
						foreach(DB::table($Customer->database.".hosts")->get() as $row_hostsSessions){
							
							$realHostSessionTime = strtotime($created_at) - strtotime($row_hostsSessions->created_at);
							if( $realHostSessionTime >= 600){
								// if record add from more than 10 minutes 
								DB::statement( 'DELETE FROM '.$Customer->database.".hosts".' where `id`='.$row_hostsSessions->id.';');
							}
							
						}
						
                    }// <!-- \check if user have locked session -->
                // }
                 

            } //foreach ($onlineUsers as $key => $value)
            
			
            /////////////////////////////////////
            /// insert data to ignore "View" ////
            /////////////////////////////////////
            //echo "Done $Customer->database ";// BIG ISSUE currently unable to handle this request. HTTP ERROR 500
            // alternative way to do the same result
            DB::statement( 'OPTIMIZE TABLE '.$Customer->database.'.radacct;');
            DB::statement( 'OPTIMIZE TABLE '.$Customer->database.'.users;');
            DB::statement( 'TRUNCATE TABLE '.$tableName.';');
            $str = 'INSERT INTO '.$Customer->database.'.cron_users_radacct (`month`, `radacctid`, `u_id`, `dates`, `u_name`, `u_uname`, `u_card_date_of_charging`,
                `u_phone`, `u_address`, `u_password`, `credit`, `u_gender`, `notes`, `branch_id`, `created_at`, `updated_at`, `u_email`,
                `u_mac`, `suspend`, `u_state`, `u_country`, `group_id`, `acctstarttime`, `acctinputoctets`, `acctoutputoctets`,
                `acctsessiontime`, `countseccions`, `Registration_type`, `u_lang`, `network_id`, `Selfrules`, `token`, `sms_credit`,
                `google_id`, `twitter_id`, `facebook_id`, `linkedin_id`)
                SELECT `month`, `radacctid`, `u_id`, `dates`, `u_name`, `u_uname`, `u_card_date_of_charging`,
                `u_phone`, `u_address`, `u_password`, `credit`, `u_gender`, `notes`, `branch_id`, `created_at`, `updated_at`, `u_email`,
                `u_mac`, `suspend`, `u_state`, `u_country`, `group_id`, `acctstarttime`, `acctinputoctets`, `acctoutputoctets`,
                `acctsessiontime`, `countseccions`, `Registration_type`, `u_lang`, `network_id`, `Selfrules`, `token`, `sms_credit`,
                `google_id`, `twitter_id`, `facebook_id`, `linkedin_id`
                FROM '.$Customer->database.'.users_radacct'.'';
            DB::statement($str);
            /*
                $customerUsersRadacct = DB::table($Customer->database.".users_radacct")->orderBy('radacctid', 'desc')->limit(10000)->get();
                DB::statement( 'TRUNCATE TABLE '.$tableName.';');
                $recordsCounter=count($customerUsersRadacct);
                foreach($customerUsersRadacct as $newRadacct){
                    $counter++;
                    $recordsCounter--;
                    if($counter==1)
                    {
                        $str = 'INSERT INTO '.$tableName.' (`month`, `radacctid`, `u_id`, `dates`, `u_name`, `u_uname`, `u_card_date_of_charging`,
                        `u_phone`, `u_address`, `u_password`, `credit`, `u_gender`, `notes`, `branch_id`, `created_at`, `updated_at`, `u_email`,
                        `u_mac`, `suspend`, `u_state`, `u_country`, `group_id`, `acctstarttime`, `acctinputoctets`, `acctoutputoctets`,
                        `acctsessiontime`, `countseccions`, `Registration_type`, `u_lang`, `network_id`, `Selfrules`, `token`, `sms_credit`,
                        `google_id`, `twitter_id`, `facebook_id`, `linkedin_id`) VALUES ';
                    }
                    if($counter!=1){
                        $str .= ",";
                    }
                    //return DB::connection()->getPdo()->quote($newRadacct->u_name);
                    $newRadacct->u_name = preg_replace('/[^a-zA-Z0-9_ -]/s','',$newRadacct->u_name);
                    $newRadacct->u_name = DB::connection()->getPdo()->quote($newRadacct->u_name);
                    $str .= "('{$newRadacct->month}',
                    '{$newRadacct->radacctid}',
                    '{$newRadacct->u_id}',
                    '{$newRadacct->dates}',
                    {$newRadacct->u_name},
                    '{$newRadacct->u_uname}',
                    '{$newRadacct->u_card_date_of_charging}',
                    '{$newRadacct->u_phone}',
                    '{$newRadacct->u_address}',
                    '{$newRadacct->u_password}',
                    '{$newRadacct->credit}',
                    '{$newRadacct->credit}',
                    '{$newRadacct->u_gender}',
                    '{$newRadacct->branch_id}',
                    '{$newRadacct->created_at}',
                    '{$newRadacct->updated_at}',
                    '{$newRadacct->u_email}',
                    '{$newRadacct->u_mac}',
                    '{$newRadacct->suspend}',
                    '{$newRadacct->u_state}',
                    '{$newRadacct->u_country}',
                    '{$newRadacct->group_id}',
                    '{$newRadacct->acctstarttime}',
                    '{$newRadacct->acctinputoctets}',
                    '{$newRadacct->acctoutputoctets}',
                    '{$newRadacct->acctsessiontime}',
                    '{$newRadacct->countseccions}',
                    '{$newRadacct->Registration_type}',
                    '{$newRadacct->u_lang}',
                    '{$newRadacct->network_id}',
                    '{$newRadacct->Selfrules}',
                    '{$newRadacct->token}',
                    '{$newRadacct->sms_credit}',
                    '{$newRadacct->google_id}',
                    '{$newRadacct->twitter_id}',
                    '{$newRadacct->facebook_id}',
                    '{$newRadacct->linkedin_id}') ";
                    if($counter==1000)
                    {   
                        DB::statement($str);
                        $counter=0;
                    }elseif($recordsCounter==0){
                        DB::statement($str);
                    }
                }
            
                ////$str .=';';
                ////echo $str."<br>";
            */
        
        
            /////////////////////////////////////
            /// Anti loss customers campaign ////
            /////////////////////////////////////
            //if($Customer->database=="demo"){
                // step 1: check for active campaign
                $campaign = DB::table($Customer->database.".campaigns")->where(['state'=>'1','type'=>'antiloss'])->get();
                if(isset($campaign) and count($campaign)>0){
                    
                    foreach ($campaign as $activeCampaign) {
                        // add one hour to "antiloss_send_time"
                        $timeNOW = date("H:i:s");
                        $timestamp = strtotime($activeCampaign->antiloss_send_time) + 60*60;
                        $after1hour = date('H:i', $timestamp).':00';
                        $Current = DateTime::createFromFormat('H:i:s',"$timeNOW");
                        $Start = DateTime::createFromFormat('H:i:s',"$activeCampaign->antiloss_send_time");
                        $End = DateTime::createFromFormat('H:i:s',"$after1hour");
                        // check for start end end sending time and campaign end date
                        if (($Current >= $Start) && ($Current <= $End) && ($activeCampaign->enddate == "" || $activeCampaign->enddate >= $today ) )
                        { 
                            // return "In time";
                            // get date of last Visit Date from 30 day ""
                            $lastVisitDate = date('Y-m-d', strtotime("-$activeCampaign->antiloss_last_visit_since days", strtotime($today)));
                            // get all customers eligible to campaign
                            $sqlQuery = "SELECT ".$Customer->database.".users_radacct.u_id, Count(users_radacct.radacctid) AS visitsCount, users_radacct.dates FROM ".$Customer->database.".users_radacct GROUP BY users_radacct.u_id HAVING visitsCount >= $activeCampaign->antiloss_minimum_visits_count and dates <='$lastVisitDate' ORDER BY users_radacct.radacctid DESC ;";
                            $users = DB::select(DB::raw($sqlQuery));
                            if(isset($campaign) and count($campaign)>0){
                                // return $result; //Fount users
                                foreach ($users as $user) {
                                    // check if user complete this campaign pefore
                                    if( DB::table($Customer->database.".campaign_statistics")->where(['campaign_id'=>$activeCampaign->id,'u_id'=>$user->u_id])->count() == 0){
                                    
                                        // get mobile,mail, network id, branch id and group id
                                        $user = DB::table($Customer->database.".users")->where('u_id', $user->u_id)->first();
                                        
                                        // check if target network, branch and group are exist
                                        if (isset($activeCampaign->network_id)) {
                                            $network_split = explode(',', $activeCampaign->network_id);
                                            foreach ($network_split as $network_value) {
                                                if ($network_value == $user->network_id) {
                                                    $found_network = 1;
                                                }
                                            }
                                        } else {
                                            $found_network = 1;
                                        }
                                        if (isset($activeCampaign->group_id)) {
                                            $group_split = explode(',', $activeCampaign->group_id);
                                            foreach ($group_split as $group_value) {
                                                if ($group_value == $user->group_id) {
                                                    $found_group = 1;
                                                }
                                            }
                                        } else {
                                            $found_group = 1;
                                        }
                                        if (isset($activeCampaign->branch_id)) {
                                            $branch_split = explode(',', $activeCampaign->branch_id);
                                            foreach ($branch_split as $branch_value) {
                                                if ($branch_value == $user->branch_id) {
                                                    $found_branch = 1;
                                                }
                                            }
                                        } else {
                                            $found_branch = 1;
                                        }
                
                                        if (isset($found_network) && isset($found_branch) && isset($found_group)) {
                                            // check if campaign has offer
                                            $countGivenOffers = DB::table($Customer->database.".campaign_statistics")->where(['campaign_id'=>$activeCampaign->id, 'type'=>'offer'])->count();
                                            if($activeCampaign->offer_limit=="" or $activeCampaign->offer_limit=="0" or $activeCampaign->offer_limit > $countGivenOffers){$offerLimitPass=1;}else{$offerLimitPass=0;}
                                            if($activeCampaign->loyalty_offer == "1" and $offerLimitPass == "1")
                                            {
                                                // generate offer code
                                                $offer_code = rand(111111,999999);
                                                if(DB::table($Customer->database.".campaign_statistics")->where(['offer_code'=>$offer_code])->count() > 0 ) 
                                                {$offer_code = rand(121121,999999);}
                                                //insert offer code
                                                DB::statement("insert into ".$Customer->database.".campaign_statistics (`campaign_id`,`u_id`,`type`,`created_at`,`offer_code`,`state`) values ('$activeCampaign->id','$user->u_id','offer','$created_at','$offer_code','0') ");
                                            }
                                            //insert new reach
                                            DB::statement("insert into ".$Customer->database.".campaign_statistics (`campaign_id`,`u_id`,`type`,`created_at`) values ('$activeCampaign->id','$user->u_id','reach','$created_at') ");
                                            //insert history
                                            DB::statement("insert into ".$Customer->database.".history (`add_date`,`add_time`,`type1`,`type2`,`operation`,`details`,`notes`,`u_id`,`branch_id`,`group_id`,`network_id`) values ('$today','$today_time','hotspot','auto','campaigns_reach','$activeCampaign->id','campaigns','$user->u_id','$user->branch_id','$user->group_id','$user->network_id') ");
                                            
                                            // sending SMS  
                                                
                                            if (isset($user->u_phone) && $user->u_phone != "") {
                                                
                                                if($activeCampaign->loyalty_offer == "1" and $offerLimitPass == "1")
                                                {$message = $activeCampaign->offer_sms_message.', Offer code:'.$offer_code;}
                                                else
                                                {$message = $activeCampaign->offer_sms_message;}
                                                
                                                $sendmessage = new App\Http\Controllers\Integrations\SMS();
                                                $sendmessage->send($user->u_phone, $message, null,$Customer->database);
                                                // send whatsapp with SMS 11/9/2019
                                                $message = urlencode($message);
                                                $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                                                $sendWhatsappMessage->send( "", $user->u_phone , $message, $Customer->id, $Customer->database, "", "", "", "1");
                                            }
                                        
                                                
                                            // sending E-Mail
                                            if(isset($user->u_email))
                                            {
                                                if (strpos($user->u_email, '@') !== false and strpos($user->u_email, '.') !== false) {
                                                    $validEmail=1;
                                                }else{$validEmail=0;}

                                            }else{$validEmail=0;}

                                            if ($activeCampaign->offer_sendmail == 1 and $validEmail==1) {
                                                
                                                if (isset($user->u_email) && count($user) != 0) {
                                                    // get system email to define $from and $to
                                                    $from = DB::table($Customer->database.".settings")->where('type', 'email')->value('value');
                                                    $from = "support@microsystem.com.eg"; // temporary till validate customers emails
                                                    if ( !isset($from) or $from=="") {
                                                        $from = $user->u_email;
                                                    }
                                                    $to = $user->u_email;
                                                    
                                                    if($activeCampaign->loyalty_offer == "1" and $offerLimitPass == "1"){
                                                        $subject = $activeCampaign->campaign_name . ' Offer code';
                                                        Mail::send('emails.offer', ['title' => $subject, 'offer_code' => $offer_code, 'messageContent' => $activeCampaign->offer_email_message, 'offerTitle' => $activeCampaign->offer_title, 'offerDescription' => $activeCampaign->offer_desc, 'userid' => $user->u_id, 'campaign_id' => $activeCampaign->id], function ($message) use ($user,$to ,$from, $subject, $Customer) {
                                                            $message->from($from, DB::table($Customer->database.".settings")->where('type', 'app_name')->value('value') );
                                                            $message->to($to, $user->u_name)->subject($subject);
                                                        });
                                                    }else{
                                                        $subject = $activeCampaign->campaign_name;
                                                        Mail::send('emails.send', ['title' => $subject, 'content' => $activeCampaign->offer_email_message], function ($message) use ($user,$to ,$from, $subject, $Customer) {
                                                            $message->from($from, DB::table($Customer->database.".settings")->where('type', 'app_name')->value('value') );
                                                            $message->to($to, $user->u_name)->subject($subject);
                                                        });
                                                    }
                                                } 
                                            }
                                            unset($found_network);
                                            unset($found_branch);
                                            unset($found_group);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            //}
        
            ////////////////////////////////////////////////////////////////////////////////
            /// auto delete users/tickets in case of group have expire_users_after_days ////
            ////////////////////////////////////////////////////////////////////////////////

            // check if the system is on free internet mode
            if( DB::table($Customer->database.".networks")->where('commercial', '1')->count() > 0){
                // get all expired users/tickets
                foreach( DB::table($Customer->database.".users")->whereNotNull('time_package_expiry')->where('time_package_expiry', '<', $today_full24)->get() as $expiredUser ){
                    // disconnect user
                    DB::table($Customer->database.".radacct")->where('acctstarttime','>=',$today)->whereNull('acctstoptime')->where('u_id',$expiredUser->u_id)->update(['realm'=>'1', 'acctterminatecause' => 'ticket_expired']);
                    // delete user
                    DB::table($Customer->database.".users")->where('u_id', $expiredUser->u_id)->delete();
                }
            }
            
        }
    echo "<center><h1>Done</h1></center>";
	}
	
}