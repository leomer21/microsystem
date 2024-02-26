<?php 
// this file included from api/include/inc_user_charge.php
session_start();
error_reporting(0); // Turn off all error reporting
if(isset($_GET['u_id'])) { $u_id_charging = $_GET['u_id']; } else{ $u_id_charging = $argv[1]; } //  %{$u_id}
if(isset($_GET['package_id'])) { $package_id = $_GET['package_id']; } else{ $package_id = $argv[2]; } //  %{$package_id}
if(isset($_GET['confirm'])) { $confirm = $_GET['confirm']; } else{ $confirm = $argv[3]; }  //  %{$confirm}
if(isset($_GET['reseller'])) { $reseller = $_GET['reseller']; } else{ $reseller = $argv[4]; } //  %{reseller}

date_default_timezone_set("Africa/Cairo");
$today_charging = date("Y-m-d"); // No 1
$today_time = date("H:i:s");
$today_time24 = date(" H:i:s");
$created_at = date("Y-m-d H:i:s");
$today_full24= $created_at;
$url=$_SERVER['HTTP_HOST'];

include 'include/config.php';		// Connect to database
include 'include/lang.php';
$conn_microsystem=mysqli_connect($sys_db_host, $sys_db_user, $sys_db_pass, $sys_db_name);
//mysqli_select_db($sys_db_name,$conn_microsystem);
$getCustomerDB="select * from `customers` where `url`='$url'";
$r_getCustomerDB=@mysqli_query($conn_microsystem,$getCustomerDB);
if(@mysqli_num_rows($r_getCustomerDB)>0) {
    
    $row_CustomerDB=@mysqli_fetch_array($r_getCustomerDB);
    $customerDatabase=$row_CustomerDB['database'];

    //$conn_customer = new mysqli($sys_dbhost, $sys_db_user, $sys_db_pass, $customerDatabase);
    //@mysqli_select_db($customerDatabase, $conn_customer);
    //mysqli_query("set characer set cp1256")or die(mysqli_error());
    //mysqli_query("set characer set cp1256");
    // if ($page_utf8 != "yes") {
    //     mysqli_query($customerDatabase,"SET NAMES cp1256");
    // } elseif ($page_utf8 == "yes") {
    //     @mysqli_query($customerDatabase,"set names 'utf8'");
    // }

    //$u_id_charging=$_GET['u_id'];
    //$package_id=$_GET['package_id'];

    // step 1 : Get Users Data
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $select_current_user_data = "select * from $customerDatabase.users where `u_id`='$u_id_charging'";
    $result_select_current_user_data = @mysqli_query($conn_microsystem,$select_current_user_data);
    if (@mysqli_num_rows($result_select_current_user_data) > 0) {

        $row_result_select_current_user_data = @mysqli_fetch_array($result_select_current_user_data);
        $current_user_credit = $row_result_select_current_user_data['credit'];
        $current_user_mobile = $row_result_select_current_user_data['u_phone'];
        $monthly_package_id = $row_result_select_current_user_data['monthly_package_id'];
        $monthly_package_expiry = $row_result_select_current_user_data['monthly_package_expiry'];
        $validity_package_id = $row_result_select_current_user_data['validity_package_id'];
        $validity_package_expiry = $row_result_select_current_user_data['validity_package_expiry'];
        $time_package_id = $row_result_select_current_user_data['time_package_id'];
        $time_package_expiry = $row_result_select_current_user_data['time_package_expiry'];
        $bandwidth_package_expiry = $row_result_select_current_user_data['bandwidth_package_expiry'];
        $user_name = $row_result_select_current_user_data['u_name'];
        $user_uname = $row_result_select_current_user_data['u_uname'];
        $branch_id = $row_result_select_current_user_data['branch_id'];
        $group_id = $row_result_select_current_user_data['group_id'];
        $network_id = $row_result_select_current_user_data['network_id'];
        $u_lang = $row_result_select_current_user_data['u_lang'];

        // step 2 : Get Package Data
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $get_current_package_data = "select * from $customerDatabase.packages where id='$package_id'";
        $result_get_current_package_data = @mysqli_query($conn_microsystem,$get_current_package_data);
        $row_result_get_current_package_data = @mysqli_fetch_array($result_get_current_package_data);
        $package_name = $row_result_get_current_package_data['name'];
        $type = $row_result_get_current_package_data['type'];//1 Monthly, 2 Validity, 3 Time, 4 Bandwidth
        $package_price = $row_result_get_current_package_data['price'];
        $package_period = $row_result_get_current_package_data['period'];
        $packageTime_package_expiry = $row_result_get_current_package_data['time_package_expiry'];
        $group_id = $row_result_get_current_package_data['group_id'];

        // step 3 : check for credit
        if ($reseller) {
            $getResellerDate = "select `credit` from $customerDatabase.admins where `id`='$reseller'";
            $r_getResellerDate = @mysqli_query($conn_microsystem,$getResellerDate);
            $row_ResellerDate = @mysqli_fetch_array($r_getResellerDate);
            $rsellerCredit = $row_ResellerDate['credit'];
            if ($package_price > $rsellerCredit) {
                $haveCredit = 0;
            } else {
                $haveCredit = 1;
            }
        } else {
            if ($package_price > $current_user_credit) {
                $haveCredit = 0;
            } else {
                $haveCredit = 1;
            }
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($haveCredit != 1) {
            echo "2";
            $stop_working = "yes";// USER DIDN'T HAVE CREDIT
        } else {

            // step 4 : check if any package already charged before.
            //////////////////////////////////////////////////////////////////////////////////////
            $nowdate_monthly_charging_x_x_x = strtotime("$monthly_package_expiry");
            $thendate_monthly_charging_x_x_x = strtotime("$today_full24");
            $datediff_monthly_charging_x_x_x = ($nowdate_monthly_charging_x_x_x - $thendate_monthly_charging_x_x_x);// subtract dates
            $final_validate_date_monthly_charging_x_x_x = round($datediff_monthly_charging_x_x_x / 86400);
            //////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////
            $nowdate_validity_charging_x_x_x = strtotime("$validity_package_expiry");
            $thendate_validity_charging_x_x_x = strtotime("$today_full24");
            $datediff_validity_charging_x_x_x = ($nowdate_validity_charging_x_x_x - $thendate_validity_charging_x_x_x);// subtract dates
            $final_validate_date_validity_charging_x_x_x = round($datediff_validity_charging_x_x_x / 86400);
            //////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////
            $nowdate_time_charging_x_x_x = strtotime("$time_package_expiry");
            $thendate_time_charging_x_x_x = strtotime("$today_full24");
            $datediff_time_charging_x_x_x = ($nowdate_time_charging_x_x_x - $thendate_time_charging_x_x_x);// subtract dates
            $final_validate_date_time_charging_x_x_x = round($datediff_time_charging_x_x_x / 86400);
            //////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////
            $nowdate_bandwidth_charging_x_x_x = strtotime("$bandwidth_package_expiry");
            $thendate_bandwidth_charging_x_x_x = strtotime("$today_full24");
            $datediff_bandwidth_charging_x_x_x = ($nowdate_bandwidth_charging_x_x_x - $thendate_bandwidth_charging_x_x_x);// subtract dates
            $final_validate_date_bandwidth_charging_x_x_x = round($datediff_bandwidth_charging_x_x_x / 86400);
            //////////////////////////////////////////////////////////////////////////////////////
            if ($type == "4" and isset($bandwidth_package_expiry) and $datediff_bandwidth_charging_x_x_x >= 0) {
                if ($confirm == 1) {
                    $stop_working = "no";
                } else {
                    $stop_working = "yes";
                    echo "5";
                }
            } elseif ($type != "4") {
                if (($datediff_monthly_charging_x_x_x >= 0 and isset($monthly_package_expiry)) or ($datediff_validity_charging_x_x_x >= 0 and isset($validity_package_expiry)) or ($datediff_time_charging_x_x_x >= 0 and isset($time_package_expiry))) {
                    // user still have valid  days that is conflict
                    if ($confirm == 1) {
                        $stop_working = "no";
                    } else {
                        $stop_working = "yes";
                        echo "5";
                    }
                }
            }

            // step 5 : start navigate to selected package

            if (!$stop_working or $stop_working == "no")// Good Work :)
            {
                
                if ($type == "4")// BandwidthPackage
                {
                    include_once 'charging_radius/bandwidthPackage.php';// OLD IN API : bandwidth_card.php
                }//if($type=="4") BandwidthPackage
                //////////////////////////////////////////////////////////////

                elseif ($type == "2" and $stop_working != "yes") // validity
                {
                    include_once 'charging_radius/validityPackage.php';// OLD IN API :  card_type_monthly.php
                }//if($type=="monthly" or !$type)

                //////////////////////////////////////////////////////////////

                elseif ($type == "1") // Monthly
                {
                    include_once 'charging_radius/monthlyPackage.php';    // OLD IN API : card_type_monthly2.php
                }//if($type=="monthly2")

                //////////////////////////////////////////////////////////////

                elseif ($type == "3") // Time
                {
                    include_once 'charging_radius/periodPackage.php'; // OLD IN API : card_type_period.php
                }//if($type=="period")

                //////////////////////////////////////////////////////////////

                //                 elseif($type=="5") // SMS
                //                 {
                //                    include_once 'charging_radius/smsPackage.php'; // OLD IN API : card_type_sms.php
                //                 }//if($type=="sms")

                //////////////////////////////////////////////////////////////
                else {
                    echo "0";// error in package ID
                }

            }// if(!$stop_working or $stop_working=="no")

        }//else{ //if($current_user_credit>$package_price)   User Have Credit

    }//if(@mysqli_num_rows($r_getUserData)>0)
    else {
        //--> send message ERROR
        $content_of_send_message_to_selected_user = "Not Found User ID in database";
        $message_address_to_selected_user = "Incomplete Charging";

        echo "3";
        //        $insert_message_to_send_message="insert into $customerDatabase.messages (messages,created_at,state,u_id) values
        //        ('$message_address_to_selected_user','$created_at','1','$db_id')";@mysqli_query($conn_microsystem,$insert_message_to_send_message);

    }
}
else{echo "Not found customer data for $url";}
?>