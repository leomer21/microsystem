<?php 
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////

// IF YOU MADE CHANGE IN RADIUS FILES YOU SHOULD COPY THIS FILES TO /home/hotspot/public_html/public/api/radius AND COMMENT ALL (insert, update, delete) QUERIES
// TO BE ABLE TO GET THE RADIUS RESPONSE MESSAGE INTO USER PANEL AFTER LOGIN

//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////

        //////////////////  First step 1 : find how is campaign will apply ///////////////////
        $getAllCampaigns="SELECT * FROM `campaigns` WHERE `state` = '1' AND `type` = 'sms' or `state` = '1' and `type` = 'mail' or `state` = '1' and `type` = 'loyalty'";
        $campaign=@mysqli_query($conn,$getAllCampaigns);

        if (isset($campaign) && mysqli_num_rows($campaign)>0) {

            while ($curr_campaign=@mysqli_fetch_array($campaign)){
                
                if( ($curr_campaign['type']=="sms" and isset($db_mobile) and $db_mobile!="") or ($curr_campaign['type']=="mail" and isset($db_mail) and $db_mail!="") or ($curr_campaign['type']=="loyalty" and isset($db_mobile) and $db_mobile!=""))
                {
                    $campaign_ID=$curr_campaign['id'];
                    // check if user already reached
                    $check_reach=@mysqli_query($conn,"select * from `campaign_statistics` where `u_id`='$db_Uid' and `campaign_id`='$campaign_ID'");

                    // check offer limit
                    if($curr_campaign['type']=="loyalty" and $curr_campaign['loyalty_offer']=="1")
                    {
                        $offer_code = rand(111111,999999);

                        if($curr_campaign['offer_limit']=="" or $curr_campaign['offer_limit']=="0")
                        {
                            $unlimited_offers=1;
                        }else{
                            $check_offer_reach=@mysqli_query($conn,"select `id` from `campaign_statistics` where `campaign_id`='$campaign_ID' and `type`='offer'"); 
                            $offer_reach=@mysqli_num_rows($check_offer_reach);
                            $remaining_offers=$curr_campaign['offer_limit']-$offer_reach;
                        }

                        
                    }//if($curr_campaign['type']=="loyalty" and $curr_campaign['loyalty_offer']=="1")

                   
                    if ( (@mysqli_num_rows($check_reach)>0) or ($curr_campaign['type']=="loyalty" and @mysqli_num_rows($check_reach)>0) or ($curr_campaign['type']=="loyalty" and $curr_campaign['loyalty_offer']=="1" and $unlimited_offers!=1 and $remaining_offers<=0) ){ 
                        // Do nothing 
                        // user already sent before
                        
                    }else{
                        
                        $start_date_campaign = $curr_campaign['startdate'];
                        $end_date_campaign = $curr_campaign['enddate'];
                        
                        $datetimenow=date('Y-m-d');
                        if (isset($end_date_campaign) && $end_date_campaign != Null) {// have start and end data
                            
                            if ($datetimenow >= $start_date_campaign && $datetimenow <= $end_date_campaign) {
                                $passedStep1 = 1;
                            }

                        } elseif (isset($start_date_campaign) && $start_date_campaign != Null) { //have start date onley
                            if ($datetimenow >= $start_date_campaign) {
                                $passedStep1 = 1;
                            }
                        }

                        /////////////////////////  step2 : day parting ///////////////////////

                        if (isset($passedStep1) && $passedStep1 == 1) {
                            
                            if (isset($curr_campaign['day_parting']) && $curr_campaign['day_parting'] == 1) {
                                $start_parting_time = $curr_campaign['day_parting_start'];
                                $end_parting_time = $curr_campaign['day_parting_end'];

                                $todayName = date('l');

                                if (isset($curr_campaign['days']) && $curr_campaign['days'] != "") {
                                    $days = explode(',', $curr_campaign['days']);
                                    $justCounter = 1;
                                    foreach ($days as $day) {

                                        if ($day == 'sun') {
                                            $avilableDays[$justCounter] = "Sunday";
                                        }
                                        if ($day == 'mon') {
                                            $avilableDays[$justCounter] = "Monday";
                                        }
                                        if ($day == 'tue') {
                                            $avilableDays[$justCounter] = "Tuesday";
                                        }
                                        if ($day == 'wed') {
                                            $avilableDays[$justCounter] = "Wednesday";
                                        }
                                        if ($day == 'thu') {
                                            $avilableDays[$justCounter] = "Thursday";
                                        }
                                        if ($day == 'fri') {
                                            $avilableDays[$justCounter] = "Friday";
                                        }
                                        if ($day == 'sat') {
                                            $avilableDays[$justCounter] = "Saturday";
                                        }
                                        $justCounter++;
                                    }
                                    if (isset($avilableDays)) {
                                        $avilableDaysCount = count($avilableDays);

                                        for ($i = 1; $i <= $avilableDaysCount; $i++) {
                                            if ($todayName == $avilableDays[$i]) {

                                                if (isset($start_parting_time) && isset($end_parting_time)) {
                                                    //return date('H:i:s');
                                                    if ( (date('H:i:s') >= $start_parting_time && date('H:i:s') <= $end_parting_time) or ($start_parting_time == "00:00:00" && $end_parting_time == "00:00:00")) {
                                                        $passedStep2 = 1;
                                                        //$activeCampaignID = $campaign_ID;
                                                        break;
                                                    }
                                                } else {
                                                    $passedStep2 = 1;
                                                }

                                            }
                                        }
                                    }

                                }

                            } else {
                                $passedStep2 = 1;
                                //$activeCampaignID = $campaign_ID;
                            }

                            // step 3
                            // check targrt group, baranch and network
                            if (isset($passedStep2) and $passedStep2 == 1) {

                                if (isset($curr_campaign['network_id'])) {
                                    $network_split = explode(',', $curr_campaign['network_id']);
                                    foreach ($network_split as $network_value) {
                                        if ($network_value == $db_networkID) {
                                            $found_network = 1;
                                        }
                                    }
                                } else {
                                    $found_network = 1;
                                }
                                if (isset($curr_campaign['group_id'])) {
                                    $group_split = explode(',', $curr_campaign['group_id']);
                                    foreach ($group_split as $group_value) {
                                        if ($group_value == $db_group_id) {
                                            $found_group = 1;

                                        }
                                    }
                                } else {
                                    $found_group = 1;
                                }
                                if (isset($curr_campaign['branch_id'])) {
                                    $branch_split = explode(',', $curr_campaign['branch_id']);
                                    foreach ($branch_split as $branch_value) {
                                        if ($branch_value == $location_ID) {

                                            $found_branch = 1;
                                        }
                                    }
                                } else {
                                    $found_branch = 1;
                                }

                                if (isset($found_network) && isset($found_branch) && isset($found_group)) {
                                    $activeCampaignID = $curr_campaign['id'];
                                    unset($found_network);
                                    unset($found_branch);
                                    unset($found_group);
                                }
                            }

                        }// end step 1  if (isset($passedStep1) && $passedStep1 == 1)

                        //////////////////////////////////////////////////  Final Step //////////////////////////////////////////////////

                        if (isset($activeCampaignID)) {

                            if($curr_campaign['type']=="sms" and isset($db_mobile) and $db_mobile!="")
                            {
                                $to=$db_mobile;
                                $message=$curr_campaign['offer_sms_message'];
                                //insert new reach
                                // @mysqli_query($conn,"insert into `campaign_statistics` (`campaign_id`,`u_id`,`type`,`created_at`) values ('$campaign_ID','$db_Uid','reach','$created_at') ");
                                //insert history
                                // @mysqli_query($conn,"insert into `history` (`add_date`,`add_time`,`type1`,`type2`,`operation`,`details`,`notes`,`u_id`,`branch_id`,`group_id`,`network_id`) values ('$today','$today_time','hotspot','auto','campaigns_reach','$campaign_ID','campaigns','$db_Uid','$location_ID','$db_branchID','$db_networkID') ");
                                
                                include 'sms.php';
                                
                            }
                            elseif($curr_campaign['type']=="mail" and isset($db_mail) and $db_mail!="")
                            {
                                
                                $mailMessage=$curr_campaign['offer_email_message'];
                                
                                $systemEmailValue=@mysqli_query($conn,"select `value` from `settings` where `type`='email' ");
                                $systemEmailRow=@mysqli_fetch_array($systemEmailValue);
                                if(isset($systemEmailRow)){$systemEmail=$systemEmailRow['value'];}
                                else{$systemEmail="no-reply@microsystem.com.eg";}
                                $headers = 'From: '.$systemEmail. "\r\n" .
                                'Reply-To: '.$systemEmail. "\r\n";
                                $headers .= "MIME-Version: 1.0\r\n";
                                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";   
   
                                @mail("$db_mail","Offer",$mailMessage, $headers);
                                //insert new reach
                                // @mysqli_query($conn,"insert into `campaign_statistics` (`campaign_id`,`u_id`,`type`,`created_at`) values ('$campaign_ID','$db_Uid','reach','$created_at') ");
                                //insert history
                                // @mysqli_query($conn,"insert into `history` (`add_date`,`add_time`,`type1`,`type2`,`operation`,`details`,`notes`,`u_id`,`branch_id`,`group_id`,`network_id`) values ('$today','$today_time','hotspot','auto','campaigns_reach','$campaign_ID','campaigns','$db_Uid','$location_ID','$db_branchID','$db_networkID') ");

                            }elseif($curr_campaign['type']=="loyalty" and isset($db_mobile) and $db_mobile!="")
                            {
                                // get first and last date depend on loyalty method
                                if($curr_campaign['loyalty_method']=="1") //count visits in current week
                                {
                                    $week = date('w');
                                    $firstDay = date('Y-m-d', strtotime('-'.$week.' days'));  // First day in this week
                                    $lastDay = date('Y-m-d', strtotime('+'.(6-$week).' days')); // Last day in this week
                                    
                                }elseif($curr_campaign['loyalty_method']=="2"){//count visits in current Month
                                    $firstDay=date("Y-m-d", strtotime('first day of this month')); // First day in this month
                                    $lastDay=date("Y-m-d", strtotime('last day of this month'));// Last day in this month
                                    
                                }elseif($curr_campaign['loyalty_method']=="3"){//count visits in current Year
                                    $firstDay=date('Y')."-01-01"; // First day in this year
                                    $lastDay=date('Y')."-12-31"; // Last day in this year

                                }elseif($curr_campaign['loyalty_method']=="4"){//count visits during last week
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 7 days')); 
                                }elseif($curr_campaign['loyalty_method']=="5"){//count visits during last month
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 30 days')); 
                                }elseif($curr_campaign['loyalty_method']=="6"){//count visits during last 2 months
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 60 days')); 
                                }elseif($curr_campaign['loyalty_method']=="7"){//count visits during last 3 months
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 90 days')); 
                                }elseif($curr_campaign['loyalty_method']=="8"){//count visits during last 4 months
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 120 days')); 
                                }elseif($curr_campaign['loyalty_method']=="9"){//count visits during last 5 months
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 150 days')); 
                                }elseif($curr_campaign['loyalty_method']=="10"){//count visits during last 6 months
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 180 days')); 
                                }elseif($curr_campaign['loyalty_method']=="11"){//count visits during last year
                                    $firstDay=$today;
                                    $lastDay=date('Y-m-d', strtotime($today. ' - 360 days')); 
                                }elseif($curr_campaign['loyalty_method']=="12"){//count visits whole the period
                                    $whole_period=1;
                                }

                                // make DB query
                                if(isset($whole_period) and $whole_period==1){
                                    $check_query=@mysqli_query($conn,"select * from `users_radacct` where `u_id`='$db_Uid' ");
                                }else{
                                    $check_query=@mysqli_query($conn,"select * from `users_radacct` where `u_id`='$db_Uid' and `dates` between '$firstDay' and '$lastDay' ");
                                }

                                // //test
                                // $test=@mysqli_num_rows($check_query);
                                // $test2=$curr_campaign['loyalty_visits'];
                                // @mysqli_query($conn,"insert into `campaign_statistics` (`campaign_id`,`u_id`,`type`,`created_at`) values ('333','555','$created_at','$created_at') ");

                                //check if user logged in today
                                //$check_if_user_logged_in_today=@mysqli_query($conn,"select * from `users_radacct` where `u_id`='$db_Uid' and `dates` = '$today' ");
                                while($row_check_query=@mysqli_fetch_array($check_query))
                                {
                                    if($row_check_query['dates']==$today){$user_logged_in_today=1;}
                                }
                                //if(@mysqli_num_rows($check_if_user_logged_in_today)>0){
                                if($user_logged_in_today==1){
                                    // user already logged in today
                                    $loginCount=@mysqli_num_rows($check_query);
                                }else{
                                    // this is first login today
                                    $loginCount=@mysqli_num_rows($check_query);
                                    $loginCount=$loginCount+1;
                                }
                                // //test
                                // $test=@mysqli_num_rows($check_query);
                                // $test2=$curr_campaign['loyalty_visits'];
                                // @mysqli_query($conn,"insert into `campaign_statistics` (`campaign_id`,`u_id`,`type`,`created_at`) values ('666','$db_Uid','$test','$created_at') ");

                                // count and compare
                                if( ($loginCount >= $curr_campaign['loyalty_visits'] and $curr_campaign['loyalty_exact_visit_count']!="1") or ($loginCount == $curr_campaign['loyalty_visits'] and $curr_campaign['loyalty_exact_visit_count']=="1") ){
                                    $can_start_loyalty_campaign=1;
                                }

                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                if(isset($can_start_loyalty_campaign) and $can_start_loyalty_campaign==1)
                                {

                                    $to=$db_mobile;
                                    $message = $curr_campaign['offer_sms_message'];

                                    if($curr_campaign['loyalty_offer']=="1"){$message = $message.', Offer code:'.$offer_code;}
                                    
                                    if($curr_campaign['offer_sendmail']=="1" and isset($db_mail) and $db_mail!="")
                                    {
                                        $mailMessage=$curr_campaign['offer_email_message'];

                                        //check if offer code applied
                                        if($curr_campaign['loyalty_offer']=="1")
                                        {
                                            $mailMessage.="<br><center><h2> Your offer code is : ".$offer_code."</h2></center>"; 
                                        }
                                        
                                        $systemEmailValue=@mysqli_query($conn,"select `value` from `settings` where `type`='email' ");
                                        $systemEmailRow=@mysqli_fetch_array($systemEmailValue);
                                        if(isset($systemEmailRow)){$systemEmail=$systemEmailRow['value'];}
                                        else{$systemEmail="no-reply@microsystem.com.eg";}
                                        $headers = 'From: '.$systemEmail. "\r\n" .
                                        'Reply-To: '.$systemEmail. "\r\n";
                                        $headers .= "MIME-Version: 1.0\r\n";
                                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";   
        
                                        @mail("$db_mail","Offer",$mailMessage, $headers);
                                    }
                                    
                                    //insert offer code
                                    // if($curr_campaign['loyalty_offer']=="1")
                                    // {@mysqli_query($conn,"insert into `campaign_statistics` (`campaign_id`,`u_id`,`type`,`created_at`,`offer_code`,`state`) values ('$campaign_ID','$db_Uid','offer','$created_at','$offer_code','0') ");}
                                    //insert new reach
                                    // @mysqli_query($conn,"insert into `campaign_statistics` (`campaign_id`,`u_id`,`type`,`created_at`) values ('$campaign_ID','$db_Uid','reach','$created_at') ");
                                    //insert history
                                    // @mysqli_query($conn,"insert into `history` (`add_date`,`add_time`,`type1`,`type2`,`operation`,`details`,`notes`,`u_id`,`branch_id`,`group_id`,`network_id`) values ('$today','$today_time','hotspot','auto','campaigns_reach','$campaign_ID','campaigns','$db_Uid','$location_ID','$db_branchID','$db_networkID') ");

                                    include 'sms.php';
                                    
                                }//if(isset($can_start_loyalty_campaign) and $can_start_loyalty_campaign==1)                                
                            }
                           

                        }

                    }//if (isset($check_reach) && @mysqli_num_rows($check_reach)>0)
                }//if( ($curr_campaign['type']=="sms" and isset($db_mobile) and $db_mobile!="") or ($curr_campaign['type']=="mail" and isset($db_mail) and $db_mail!="") )
                unset($check_query);
                unset($loginCount);
                unset($can_start_loyalty_campaign);
                unset($user_logged_in_today);
                unset($firstDay);
                unset($lastDay);
                unset($whole_period);
                unset($check_reach);
                unset($remaining_offers);
                unset($offer_reach);
                unset($unlimited_offers);
                unset($campaign_ID);
                unset($check_offer_reach);
               
            }//end while ($curr_campaign=@mysqli_fetch_array($campaign))
        }//if(isset($campaign) && count($campaign) != 0)


?>