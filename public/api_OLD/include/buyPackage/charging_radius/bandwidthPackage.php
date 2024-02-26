<?php
// this file included from /api/include/buyPackage/chargeRadius.php in IF condition($card_type=="1")
// *Parent from /api/include/buyPackage/charging_radius/chargeRadius.php


// step 1 : check if user charge package have bandwidth

if($monthly_package_id)
{
    //////////////////////////////////////////////////////////////////////////////////////
    $monthlyBeforeConvert = strtotime("$monthly_package_expiry");
    $todayBeforeConvert = strtotime("$today_full24");
    $dateDiffCharging = ($monthlyBeforeConvert - $todayBeforeConvert);// subtract dates
    $final_validate_date_charging = round($dateDiffCharging / 86400);
    //////////////////////////////////////////////////////////////////////////////////////

    if ($final_validate_date_charging >= 0) { // user still have valid days
        $getGroupIDfromCurrentPackage="select `group_id` from $customerDatabase.packages where `id`='$monthly_package_id'";
        $r_getGroupIDfromCurrentPackage=@mysqli_query($conn_microsystem,$getGroupIDfromCurrentPackage);
        if(@mysqli_num_rows($r_getGroupIDfromCurrentPackage)>0)
        {
            $row_getGroupIDfromCurrentPackage=@mysqli_fetch_array($r_getGroupIDfromCurrentPackage);
            $groupIDforPreviousPackage=$row_getGroupIDfromCurrentPackage['group_id'];
            $getGroubData="select * from $customerDatabase.area_groups where `id`='$groupIDforPreviousPackage'";
            $r_getGroubData=@mysqli_query($conn_microsystem,$getGroubData);
            if(@mysqli_num_rows($r_getGroubData)>0){
                $row_groubData=@mysqli_fetch_array($r_getGroubData);
                $previous_quota_limit_upload=$row_groubData['quota_limit_upload'];
                $previous_quota_limit_downloadd=$row_groubData['quota_limit_download'];
                $previous_quota_limit_total=$row_groubData['quota_limit_total'];
                if(($previous_quota_limit_upload and $previous_quota_limit_upload!="0") or ($previous_quota_limit_downloadd and $previous_quota_limit_downloadd!="0") or ($previous_quota_limit_total and $previous_quota_limit_total!="0")){
                    // user have quota
                    $canCharge=1;
                    $previousPackageType="monthly";

                }
            }
        }
    }
}

if($validity_package_id and $canCharge!=1)
{
    //////////////////////////////////////////////////////////////////////////////////////
    $validityBeforeConvert = strtotime("$validity_package_expiry");
    $todayBeforeConvert = strtotime("$today_full24");
    $dateDiffCharging = ($validityBeforeConvert - $todayBeforeConvert);// subtract dates
    $final_validate_date_charging = round($dateDiffCharging / 86400);
    //////////////////////////////////////////////////////////////////////////////////////

    if ($dateDiffCharging >= 0) { // user still have valid days
        $getGroupIDfromCurrentPackage="select `group_id` from $customerDatabase.packages where `id`='$validity_package_id'";
        $r_getGroupIDfromCurrentPackage=@mysqli_query($conn_microsystem,$getGroupIDfromCurrentPackage);
        if(@mysqli_num_rows($r_getGroupIDfromCurrentPackage)>0)
        {
            $row_getGroupIDfromCurrentPackage=@mysqli_fetch_array($r_getGroupIDfromCurrentPackage);
            $groupIDforPreviousPackage=$row_getGroupIDfromCurrentPackage['group_id'];
            $getGroubData="select * from $customerDatabase.area_groups where `id`='$groupIDforPreviousPackage'";
            $r_getGroubData=@mysqli_query($conn_microsystem,$getGroubData);
            if(@mysqli_num_rows($r_getGroubData)>0){
                $row_groubData=@mysqli_fetch_array($r_getGroubData);
                $previous_quota_limit_upload=$row_groubData['quota_limit_upload'];
                $previous_quota_limit_downloadd=$row_groubData['quota_limit_download'];
                $previous_quota_limit_total=$row_groubData['quota_limit_total'];
                if(($previous_quota_limit_upload and $previous_quota_limit_upload!="0")
                    or ($previous_quota_limit_downloadd and $previous_quota_limit_downloadd!="0")
                    or ($previous_quota_limit_total and $previous_quota_limit_total!="0")){
                    // user have quota
                    $canCharge=1;
                    $previousPackageType="validity";
                }
            }
        }
    }
}

if($time_package_id  and $canCharge!=1)
{
    //////////////////////////////////////////////////////////////////////////////////////
    $timeBeforeConvert = strtotime("$time_package_expiry");
    $todayBeforeConvert = strtotime("$today_charging");
    $dateDiffCharging = ($timeBeforeConvert - $todayBeforeConvert);// subtract dates
    $final_validate_date_charging = round($dateDiffCharging / 86400);
    //////////////////////////////////////////////////////////////////////////////////////

    if ($final_validate_date_charging >= 0) { // user still have valid days
        $getGroupIDfromCurrentPackage="select `group_id` from $customerDatabase.packages where `id`='$time_package_id'";
        $r_getGroupIDfromCurrentPackage=@mysqli_query($conn_microsystem,$getGroupIDfromCurrentPackage);
        if(@mysqli_num_rows($r_getGroupIDfromCurrentPackage)>0)
        {
            $row_getGroupIDfromCurrentPackage=@mysqli_fetch_array($r_getGroupIDfromCurrentPackage);
            $groupIDforPreviousPackage=$row_getGroupIDfromCurrentPackage['group_id'];
            $getGroubData="select * from $customerDatabase.area_groups where `id`='$groupIDforPreviousPackage'";
            $r_getGroubData=@mysqli_query($conn_microsystem,$getGroubData);
            if(@mysqli_num_rows($r_getGroubData)>0){
                $row_groubData=@mysqli_fetch_array($r_getGroubData);
                $previous_quota_limit_upload=$row_groubData['quota_limit_upload'];
                $previous_quota_limit_downloadd=$row_groubData['quota_limit_download'];
                $previous_quota_limit_total=$row_groubData['quota_limit_total'];
                if(($previous_quota_limit_upload and $previous_quota_limit_upload!="0")
                    or ($previous_quota_limit_downloadd and $previous_quota_limit_downloadd!="0")
                    or ($previous_quota_limit_total and $previous_quota_limit_total!="0")){
                    // user have quota
                    $canCharge=1;
                    $previousPackageType="time";
                }
            }
        }
    }
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($canCharge==1)
{
    {

        // Get expiration date
        if($previousPackageType=="monthly"){$card_validate_date_charging = $monthly_package_expiry;}
        elseif($previousPackageType=="validity"){$card_validate_date_charging = $validity_package_expiry;}
        elseif($previousPackageType=="time"){$card_validate_date_charging = $time_package_expiry;}

        if($reseller)
        {
            // Reseller discount balance
            $new_credit_after_sub = $rsellerCredit - $package_price;
            @mysqli_query($conn_microsystem,"update $customerDatabase.admins set `credit`='$new_credit_after_sub' where `id`='$reseller'");
            // Reseller Insert History
            $insert_into_history2 = "insert into $customerDatabase.history (add_date,add_time,type1,type2,operation,u_id,package_type,package_period,package_id,package_expiration_date,package_price,branch_id,group_id,network_id,reseller_id) values
            ('$today_charging','$today_time','hotspot','reseller','reseller_charge_package','$u_id_charging','1','$package_period','$package_id','$card_validate_date_charging','$package_price','$branch_id','$group_id','$network_id','$reseller')";
            @mysqli_query($conn_microsystem,$insert_into_history2);
        }
        else
        {
            // User discount balance
            $new_credit_after_sub = $current_user_credit - $package_price;
            @mysqli_query($conn_microsystem,"update $customerDatabase.users set `credit`='$new_credit_after_sub' where `u_id`='$u_id_charging'");
            // User Insert History
            $insert_into_history2 = "insert into $customerDatabase.history (add_date,add_time,type1,type2,operation,u_id,package_type,package_period,package_id,package_expiration_date,package_price,branch_id,group_id,network_id) values
            ('$today_charging','$today_time','hotspot','user','user_charge_package','$u_id_charging','1','$package_period','$package_id','$card_validate_date_charging','$package_price','$branch_id','$group_id','$network_id')";
            @mysqli_query($conn_microsystem,$insert_into_history2);
        }

        // B update user data
        $todayTime24Format = date("H:i:s"); // ex. 13.01.20
        $updateUserDetails = "update $customerDatabase.users set
        `u_card_date_of_charging`='$today_charging',
        `bandwidth_package_id`='$package_id',
        `bandwidth_package_start`='$created_at',
        `bandwidth_package_expiry`='$card_validate_date_charging'
        where `u_id`='$u_id_charging'";
        @mysqli_query($conn_microsystem,$updateUserDetails) or die(mysqli_error());

        // C send local message
        if ($sms_state_user_lang == "ar") {$v1 = "������:"; $v2 = "�� ����� � "; $v3 = "���� ��� ������� ��"; $v4 = "���";
            $content_message = "$v1$user_name$v2$package_price$v3$today_charging$v4$card_validate_date_charging";}
        else{$content_message = "Dear: $user_name, <br> Greetings,<br> You have charged package of $package_price LE, <br> Internet is now connected from $today_charging till $card_validate_date_charging, <br> Thanks for choosing our network, <br>Best regards.";}
        $insert_into_messages = "insert into $customerDatabase.messages (`created_at`,`u_id`,`name`,`subject`,`message`,`state`,`admin_id`) values
        ('$created_at','$u_id_charging','Administration','Charge package','$content_message','1','1')";
        @mysqli_query($conn_microsystem,$insert_into_messages);


        // E send SMS
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $getSMSinformaions = "select * from $customerDatabase.seetings";
        $r_getSMSinformaions = @mysqli_query($conn_microsystem,$getSMSinformaions);
        while ($row_getSMSinformaions = @mysqli_fetch_array($r_getSMSinformaions)) {
            if ($row_getSMSinformaions['SMSProvider']) {
                $smsProviderID = $row_getSMSinformaions['value'];
            }
            if ($row_getSMSinformaions['SMSProviderusername']) {
                $admin_uname_sms = $row_getSMSinformaions['value'];
            }
            if ($row_getSMSinformaions['SMSProviderpassword']) {
                $admin_pass_sms = $row_getSMSinformaions['value'];
            }
            if ($row_getSMSinformaions['SMSProvidersendername']) {
                $company_name_sms = $row_getSMSinformaions['value'];
            }
            if ($row_getSMSinformaions['SMSstateUserChargePackage']) {
                $sms_state_user_charge = $row_getSMSinformaions['state'];
            }
            if ($row_getSMSinformaions['SMSUserLang']) {
                $sms_state_user_lang = $row_getSMSinformaions['value'];
            }
        }
        if ($smsProviderID == 1) {
            $current_sms_provider_name = "resalty";
        } elseif ($smsProviderID == 2) {
            $current_sms_provider_name = "masrawy";
        } elseif ($smsProviderID == 3) {
            $current_sms_provider_name = "valuedsms";
        } elseif ($smsProviderID == 4) {
            $current_sms_provider_name = "infobip";
        }

        if ($sms_state_user_charge == "1") {

            if ($current_user_mobile && $current_user_mobile != "null") {
                $getCurrency=@mysqli_query($conn_microsystem,"select `value` from $customerDatabase.settings where `type`='currency'");
                $row_getCurrency=@mysqli_fetch_array($getCurrency);
                $currency=$row_getCurrency['value'];
                if ($sms_state_user_lang == "en") {
                    $content_message = "Dear:$user_uname you have charged package of $package_price $currency , Internet is NOW Connected from $today_charging To $card_validate_date_charging";
                }

                if ($sms_state_user_lang == "ar") {
                    $v1 = "������:";
                    $v2 = "�� ����� � ";
                    $v3 = "���� ��� ������� ��";
                    $v4 = "���";
                    $URLtext1 = "%D8%B9%D9%85%D9%8A%D9%84%D9%86%D8%A7%3A";
                    $URLtext2 = "%D8%AA%D9%85+%D8%A7%D9%84%D8%B4%D8%AD%D9%86+%D8%A8+";
                    $URLtext3 = "%D8%AC%D9%86%D9%8A%D8%A9+%D9%88%D8%AA%D9%85+%D8%A7%D9%84%D8%AA%D8%B4%D8%BA%D9%8A%D9%84+%D9%85%D9%86";
                    $URLtext4 = "%D8%AD%D8%AA%D9%89";
                    $content_message = "$URLtext1$user_uname$URLtext2$package_price$URLtext3$today_charging$URLtext4$card_validate_date_charging";
                }

                //Insert SMS history
                $insert_into_history2 = "insert into $customerDatabase.history (add_date,add_time,type1,type2,operation,u_id,details,notes) values
                ('$today_charging','$today_time','sms_provider','auto','autoSMSuserChargePackage','$u_id_charging','$content_message','$current_user_mobile')";
                @mysqli_query($conn_microsystem,$insert_into_history2);

                // call send sms function to start jop
                $pageName4SMS = "chargePackageSMS.php";
                include 'include/sms.php';

            } // End if($current_user_mobile&&$current_user_mobile!="null")
        } // if($sms_state_user_charge=="on")
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // send successfully code
        echo "1";
    }

}else{
    echo "6";
}

?>