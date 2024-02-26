<?php
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////

// IF YOU MADE CHANGE IN RADIUS FILES YOU SHOULD COPY THIS FILES TO /home/hotspot/public_html/public/api/radius AND COMMENT ALL (insert, update, delete) QUERIES
// TO BE ABLE TO GET THE RADIUS RESPONSE MESSAGE INTO USER PANEL AFTER LOGIN
$user = $_REQUEST['user']; //  %{User-Name}
$password = $_REQUEST['password']; //  %{User-Password}
$ip = $_REQUEST['ip']; //  %{Client-IP-Address}
$mac = $_REQUEST['mac']; //  %{Calling-Station-Id}
$systemID=$_REQUEST['systemID']; // %{NAS-Identifier} getted from (system->Identify) #NOTE VERY IMPORTANT IF WE HOST IN ETISALAT IN ONE MIKROTIK REBLASE $systemID TO $hotspotName AND CREATE MICROSYSTEM SCRIPT FOR EVERY CUSTOMER AND DISABLE BRANCH FEATURES FROM GUI AND MAKE AUTO LOGIN CONTROL FROM THIS PAGE
$hotspotName=$_REQUEST['hotspotName']; //%{Called-Station-Id} getted from  (ip->hotspot->server profile->"profile name ex.hsprof1")
$location_ID=$_REQUEST['location_ID']; //%{WISPr-Location-ID} getted from  (ip->hotspot->server profile->"open Profile then GOTO RADIUS Tab")
$location_Name=$_REQUEST['location_Name']; //%{WISPr-Location-Name} getted from  (ip->hotspot->server profile->"open Profile then GOTO RADIUS Tab")
$Acct_Session_Id=$_REQUEST['Acct_Session_Id']; // %{Acct-Session-Id}
$NAS_IP_Address=$_REQUEST['NAS_IP_Address'];     //NAS-IP-Address
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////// *** NOTES VERY IMPORTANT ////////////////////////////////////////////////////////////////////////
//error_reporting(0); // Turn off all error reporting
// $user = $argv[1]; //  %{User-Name}
// $password = $argv[2]; //  %{User-Password}
// $ip = $argv[3]; //  %{Client-IP-Address}
// $mac = $argv[4]; //  %{Calling-Station-Id}
// $systemID=$argv[5]; // %{NAS-Identifier} getted from (system->Identify) #NOTE VERY IMPORTANT IF WE HOST IN ETISALAT IN ONE MIKROTIK REBLASE $systemID TO $hotspotName AND CREATE MICROSYSTEM SCRIPT FOR EVERY CUSTOMER AND DISABLE BRANCH FEATURES FROM GUI AND MAKE AUTO LOGIN CONTROL FROM THIS PAGE
// $hotspotName=$argv[6]; //%{Called-Station-Id} getted from  (ip->hotspot->server profile->"profile name ex.hsprof1")
// $location_ID=$argv[7]; //%{WISPr-Location-ID} getted from  (ip->hotspot->server profile->"open Profile then GOTO RADIUS Tab")
// $location_Name=$argv[8]; //%{WISPr-Location-Name} getted from  (ip->hotspot->server profile->"open Profile then GOTO RADIUS Tab")
// $Acct_Session_Id=$argv[9]; // %{Acct-Session-Id}
// $NAS_IP_Address=$argv[10];     //NAS-IP-Address
/////////////////////////////////////////////////////////////////////
// Aruba check Con.
$macType = strpos($mac,":");
if ($macType === false and strlen($mac)>=12 ) {
    // "Aruba" format so we will convert small to capital letters and add ":"
    $mac = $mac[0].$mac[1].":".$mac[2].$mac[3].":".$mac[4].$mac[5].":".$mac[6].$mac[7].":".$mac[8].$mac[9].":".$mac[10].$mac[11];
    $mac = strtoupper( $mac );
}
/////////////////////////////////////////////////////////////////////
$dbhost = 'localhost';
$dbuser = 'hotspot_hotspot';
$dbpass = 'O2E/pGx5cR9rK[M]';//1403636mra
//$dbname = 'hotspot';

//include '../config.php';
date_default_timezone_set("Africa/Cairo");
$today = date("Y-m-d");
$today_time = date("g:i a");
$created_at = date("Y-m-d H:i:s");
$today_full24=$created_at;

//$ifLocationIDwithSystemID=explode("-",$systemID);
//if(isset($ifLocationIDwithSystemID[1]))
//{
//    $systemID=$ifLocationIDwithSystemID[1];
//    $location_ID=$ifLocationIDwithSystemID[0];
     //echo "               systemID = $systemID                  ";
     //echo "               location_ID = $location_ID                 ";
//}

//$dbname = "demo";
$conn =@mysqli_connect($dbhost, $dbuser, $dbpass,$systemID);
//$conn =@mysqli_connect($dbhost, $dbuser, $dbpass);
//@mysqli_select_db($dbname);
// @mysqli_query($conn,"set characer set utf8mb4");//utf8mb4_general_ci
@mysqli_set_charset($conn,"utf8mb4_general_ci");
@mysqli_query($conn,"set names utf8mb4"); //'collation' => 'utf8mb4_general_ci',

// get max concurrent limit currently
$getConcurrentLimit="select * from `settings` where `type`='currently_max_concurrent'";
$r_getConcurrentLimit = mysqli_query($conn,$getConcurrentLimit);
$row_getConcurrentLimit = mysqli_fetch_array($r_getConcurrentLimit);
$maxConcurrentLimit = $row_getConcurrentLimit['value'];
// get total concurrent devices NOW
$getConcurrentDevicesNOW="select * from `radacct` where `acctstoptime` IS NULL";
$r_getConcurrentDevicesNOW = @mysqli_query($conn,$getConcurrentDevicesNOW);
$totalConcurrentNOW = @mysqli_num_rows($r_getConcurrentDevicesNOW);

if(!isset($maxConcurrentLimit) or $maxConcurrentLimit=="" or $maxConcurrentLimit=="0" or $totalConcurrentNOW <= $maxConcurrentLimit )
{
    
    //$update='update radcheck set mac="'.$password.'" where id="10"'; // for test
    //mysqli_query($update);
    //echo "mikrotik user:".$user."database user".$db_user."password".$password."token".$db_token."   ";

    ///////////////////////////////////////////////////////////////////
    // Get User Informations from table users
    //$checkUser="select * from `users` where ( `u_uname`='$user' or `u_mac` LIKE '%$mac%' ) and `registration_type`='2' and `u_state`='1' and `suspend`='0' ";
    //$checkUser="select * from `users` where ( `u_uname`='$user' and `u_password`='$password' and `registration_type`='2' and `u_state`='1' and `suspend`='0' ) or ( `u_mac` LIKE '%$mac%' and `registration_type`='2' and `u_state`='1' and `suspend`='0' )";
    //$checkUser="select * from `users` where (`u_password`='$password' or `token`='$password' and `u_uname`='$user' and `registration_type`='2' and `u_state`='1' and `suspend`='0' ) or ( `u_mac` LIKE '%$mac%' and `registration_type`='2' and `u_state`='1' and `suspend`='0' )";
    if( $mac== $user){$checkUser="select * from `users` where `u_mac` LIKE '%$mac%' and `registration_type`='2' and `u_state`='1' and `suspend`='0'";}
    else{$checkUser="select * from `users` where `u_password`='$password' or `token`='$password' and `u_uname`='$user' and `registration_type`='2' and `u_state`='1' and `suspend`='0' ";}
//echo "$checkUser";
    $r_checkUser=@mysqli_query($conn,$checkUser);
    if(@mysqli_num_rows($r_checkUser)>0){

        // get user data
        $row_checkUser=@mysqli_fetch_array($r_checkUser);
        $db_Uid=$row_checkUser['u_id'];
        $db_user=$row_checkUser['u_uname'];
        $db_name=$row_checkUser['u_name'];
        $db_password=$row_checkUser['u_password'];
        $db_group_id=$row_checkUser['group_id'];
        $db_u_macaddress=$row_checkUser['u_mac'];
        $db_networkID=$row_checkUser['network_id'];
        $db_branchID=$row_checkUser['branch_id'];
        $db_uState=$row_checkUser['u_state'];
        $db_token=$row_checkUser['token'];
        $last_login_manual=$row_checkUser['last_login_manual'];
        $db_mobile=$row_checkUser['u_phone'];
        $db_mail=$row_checkUser['u_email'];
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // check if value of branch getted from Mikrotik or from user db
        if(!isset($location_ID) or $location_ID==0)
        {$location_ID=$db_branchID;}
        
        //get branch state 
        $getUserLoginBranchState="select `auto_login`,`state`,`auto_login_expiry`,`radius_type`,`temporary_group_switching_state`,`temporary_group_switching_group_id` from `branches` where `id`='$location_ID'";
        $r_getUserLoginBranchState=@mysqli_query($conn,$getUserLoginBranchState);
        $row_UserLoginBranchState=@mysqli_fetch_array($r_getUserLoginBranchState);
        $UserLoginBranchState=$row_UserLoginBranchState['state'];
        $auto_login_expiry=$row_UserLoginBranchState['auto_login_expiry'];
        $dba_radius_type=$row_UserLoginBranchState['radius_type'];
        $auto_insert_mac=$row_UserLoginBranchState['auto_login'];
        if($row_UserLoginBranchState['temporary_group_switching_state'] == "1"){
            $db_group_id = $row_UserLoginBranchState['temporary_group_switching_group_id'];
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // get group data
        $getGroupDatails="select * from `area_groups` where `id`='$db_group_id'";
        $r_getGroupDatails=@mysqli_query($conn,$getGroupDatails);
        $row_getGroupDatails=@mysqli_fetch_array($r_getGroupDatails);
        $dba_state=$row_getGroupDatails['is_active'];
        // $dba_radius_type=$row_getGroupDatails['radius_type'];
        $dba_renew=$row_getGroupDatails['renew'];
        $dba_url_redirect=$row_getGroupDatails['url_redirect'];
        $dba_url_redirect_Interval=$row_getGroupDatails['url_redirect_Interval'];
        if($dba_url_redirect_Interval){$dba_url_redirect_Interval = strtotime("$dba_url_redirect_Interval") - strtotime('TODAY');} // convert Time "01:00:00" seconds 3600
        $dba_session_time=$row_getGroupDatails['session_time'];
        if($dba_session_time ){$dba_session_time = strtotime("$dba_session_time") - strtotime('TODAY'); }// convert Time "01:00:00" seconds 3600
        $dba_port_limit=$row_getGroupDatails['port_limit'];
        $dba_idle_timeout=$row_getGroupDatails['idle_timeout'];
        if($dba_idle_timeout ){$dba_idle_timeout = strtotime("$dba_idle_timeout") - strtotime('TODAY'); }// convert Time "01:00:00" seconds 3600
        $dba_quota_limit_upload=$row_getGroupDatails['quota_limit_upload'];
        $dba_quota_limit_download=$row_getGroupDatails['quota_limit_download'];
        $dba_quota_limit_total=$row_getGroupDatails['quota_limit_total'];
        //$dba_quota_limit_total="4194304000";
        $dba_speed_limit=$row_getGroupDatails['speed_limit'];
        $dba_if_downgrade_speed=$row_getGroupDatails['if_downgrade_speed'];
        $dba_end_speed=$row_getGroupDatails['end_speed'];
        // get total_quota before ant calculation to insert it in radacct table for current session 
        if(!$dba_quota_limit_total or $dba_quota_limit_total==0){$totalQuota=$dba_quota_limit_upload+$dba_quota_limit_download;}else{$totalQuota=$dba_quota_limit_total;}
                            
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // step 1 check network status
        $checkNetworkStatus="select `state`,`commercial` from `networks` where `id`='$db_networkID'";
        $r_checkNetworkStatus=@mysqli_query($conn,$checkNetworkStatus);
        $row_checkNetworkStatus=@mysqli_fetch_array($r_checkNetworkStatus);
        $currentNetworkState=$row_checkNetworkStatus['state'];
        $open_system=$row_checkNetworkStatus['commercial']; // 0:commercial // 1:free // 2  free + commercial

        // we removed validation on branch state at 25/4/2018. if($currentNetworkState=="1" and ($UserLoginBranchState=="1" or !$location_ID) and $dba_state=="1")// Network Running AND Branch running AND Group is Running
        if($currentNetworkState=="1" and $dba_state=="1")// Network Running AND Group is Running
        {
            //if ( ($user == $db_user && $password == $db_password && !isset($db_u_macaddress)) or ($user == $db_user && $password == $db_password && $db_u_macaddress=="") or ($user == $db_user && $password == $db_password && $mac==$db_u_macaddress) or ($user == $db_user && $mac == $db_u_macaddress)) {
            //if ( ($user == $db_user && $password == $db_password) or ($user == $db_user && $mac == $db_u_macaddress) or ( $mac == $db_u_macaddress ) or ( $mac == $user ) ) {
            if ( ($user == $db_user && $password == $db_password) or ($user == $db_user && $password == $db_token)  or ( $mac == $user ) ) {

                //$getSystemInformation="select `port_limit`,`limited_devices`,`auto_login_expiry` from `area_groups` where `id`='$db_group_id'";
                //$r_getSystemInformation=@mysqli_query($conn,$getSystemInformation);
                //$row_getSystemInformation=@mysqli_fetch_array($r_getSystemInformation);
                $port_limit=$row_getGroupDatails['port_limit'];
                $limited_devices=$row_getGroupDatails['limited_devices'];
                $countMacForUsers=count (explode(",",$db_u_macaddress));

                // detect if user logged in automatically by new feature ( auto insert mac )
                if( $mac == $user ) {$userLoginByMacAuto="yes";}

                // check if user not have limited devices, or user have valid mac in user db, or user still have credit to add new mac in db
                if($userLoginByMacAuto!="yes" or $limited_devices==0 or strpos($db_u_macaddress, $mac) !== false or $countMacForUsers<$limited_devices){

                    // delete any opened session if port limit 1
                    //if($dba_port_limit==1){@mysqli_query($conn,"update `radacct` set `acctstoptime`='$created_at' where `acctstoptime` IS NULL and `u_id`='$db_Uid'");}

                    // check if user have locked session
                    $checkLockedSessions="select * from `radacct` where `acctstoptime` IS NULL and `u_id`='$db_Uid'";
                    $r_checkLockedSessions=@mysqli_query($conn,$checkLockedSessions);
                    if(@mysqli_num_rows($r_checkLockedSessions)>0)
                    {   
                        $totalOnlineSessions=@mysqli_num_rows($r_checkLockedSessions);
                        
                        while($row_checkLockedSessions=@mysqli_fetch_array($r_checkLockedSessions))
                        {
                            $thisSessionID=$row_checkLockedSessions['radacctid'];
                            $startSessionTime=$row_checkLockedSessions['acctstarttime'];
                            $realSessionTime = strtotime($created_at) - strtotime($startSessionTime);
                            $dbSessionTime=$row_checkLockedSessions['acctsessiontime'];
                            $delayBetweenUpdate=$realSessionTime-$dbSessionTime;
                            // if($delayBetweenUpdate>=3700){@mysqli_query($conn,"update `radacct` set `acctstoptime`='$created_at' where `radacctid`='$thisSessionID'"); $totalOnlineSessions--;}
                        }
                    }// <!-- \check if user have locked session -->

                    // check if session limit reached
                    if($totalOnlineSessions<$port_limit){

                        // check auto login expiration
                        if($userLoginByMacAuto=="yes"){
                            if(isset($last_login_manual) and $last_login_manual!="" and isset($auto_login_expiry) and $auto_login_expiry!="" and $auto_login_expiry!="0")
                            {
                                $afterSumAutoExpiryDays=date('Y-m-d H:i:s', strtotime($last_login_manual. ' + '.$auto_login_expiry.' days'));
                                if($afterSumAutoExpiryDays>=$today_full24){//can login not expired
                                    $bassedAutoLoginexpiry=1;
                                }
                            }else{$bassedAutoLoginexpiry=1;}// can login ( not found last login date or expiry not set )
                        }// <!-- \check auto login expiration -->

                        // if bassed AutoLogin expiry or user logged in manually 
                        if( ($userLoginByMacAuto=="yes" and $bassedAutoLoginexpiry=="1") or ($userLoginByMacAuto!="yes") )
                        {
                            // check if system commercial or free internet
                            if($open_system=="0" or $open_system=="2")// commercial
                            {

                                // step A ( Get commercial values from DB )
                                include 'paid.php';

                            }
                            if($open_system=="1" or $goFreeInternet=="yes"){ // internet free

                                
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                // step 5 Delete all related user records in `radreply` to clr any previous data
                                // @mysqli_query($conn,"delete from `radreply` where `username`='$user'");
                                // @mysqli_query($conn,"delete from `radgroupcheck` where `groupname`='$user'");
                                // @mysqli_query($conn,"delete from `radusergroup` where `username`='$user'");
                                // @mysqli_query($conn,"delete from `radcheck` where `username`='$user'");
                                
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                // step 6 add new user in table `radcheck` to learn radius to take attention to attriputes
                                // @mysqli_query($conn," insert into `radcheck` (`username`,`attribute`,`op`,`value`) values ('$user','User-Password',':=','') ");

                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                // step 7 Get user History Data

                                // user logged in by new feature ( auto insert mac ) and logged in automatically
                                //if($userLoginByMacAuto=="yes"){$getAllUserRecords="SELECT * FROM `radacct` WHERE `username` = '$db_user' AND `acctstarttime` >= '$today'";}
                                // Normal Case
                                //else{$getAllUserRecords="SELECT * FROM `radacct` WHERE `username` = '$user' AND `acctstarttime` >= '$today'";}
                                // after add user ID field in radacct table
                                $getAllUserRecords="SELECT * FROM `radacct` WHERE `u_id` = '$db_Uid' AND `acctstarttime` >= '$today'";

                                $r_getAllUserRecords=@mysqli_query($conn,$getAllUserRecords);
                                while ($row_getAllUserRecords=@mysqli_fetch_array($r_getAllUserRecords)) {
                                    $acctsessiontime+=$row_getAllUserRecords['acctsessiontime'];
                                    $acctinputoctets+=$row_getAllUserRecords['acctinputoctets'];
                                    $acctoutputoctets+=$row_getAllUserRecords['acctoutputoctets'];
                                }// End while

                                // check if session time ended
                                if(isset($dba_session_time) and $dba_session_time!=0 and isset($acctsessiontime) and $acctsessiontime>0){
                                    $dba_session_time=$dba_session_time-$acctsessiontime;
                                    if($dba_session_time<=0){$rejected="yes";}// Reject Reason: session time ended
                                }

                                // check if quota upload ended
                                if(isset($dba_quota_limit_upload) and $dba_quota_limit_upload!=0)
                                {
                                    $dba_quota_limit_upload=$dba_quota_limit_upload-$acctinputoctets;
                                    if($dba_quota_limit_upload<=0){$rejected="yes";} // Reject Reason : quota upload ended
                                }

                                // check if quota download ended
                                if(isset($dba_quota_limit_download) and $dba_quota_limit_download!=0)
                                {
                                    $dba_quota_limit_download=$dba_quota_limit_download-$acctoutputoctets;
                                    if($dba_quota_limit_download<=0){$rejected="yes";}// Reject Reason : quota Download ended
                                }

                                // check if quota total ended
                                if(isset($dba_quota_limit_total) and $dba_quota_limit_total!=0)
                                {
                                    $dba_quota_limit_total=$dba_quota_limit_total-($acctoutputoctets+$acctinputoctets);
                                    if($dba_quota_limit_total<=0){$rejected="yes";}// Reject Reason : quota Download ended
                                }

                                // check for downgrade speed
                                if($rejected=="yes" and $dba_if_downgrade_speed=="1"){
                                    $dba_speed_limit=$dba_end_speed;
                                    $dba_session_time=0;
                                    $dba_quota_limit_upload=0;
                                    $dba_quota_limit_download=0;
                                    $dba_quota_limit_total=0;
                                    unset($rejected);
                                }//if($rejected=="yes") // check for downgrade speed


                                if(!isset($rejected) or $rejected!="yes"){


                                    // step 8 get Radius type Attributes
                                    $getRadiusAttr="select * from `radius_attributes` where `radius_type`='$dba_radius_type'";
                                    $r_getRadiusAttr=@mysqli_query($conn,$getRadiusAttr);
                                    while ($row_getRadiusAttr=@mysqli_fetch_array($r_getRadiusAttr)) {

                                        //url_redirect
                                        $dbb_url_redirect=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_url_redirect) and $dbb_url_redirect=="url_redirect")
                                        {
                                            $dbb_url_redirect=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_url_redirect',':=','$dba_url_redirect')");
                                        }

                                        //url_redirect_Interval
                                        $dbb_url_redirect_Interval=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_url_redirect_Interval) and $dbb_url_redirect_Interval=="url_redirect_Interval")
                                        {
                                            $dbb_url_redirect_Interval=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_url_redirect_Interval',':=','$dba_url_redirect_Interval')");
                                        }

                                        //session_time
                                        $dbb_session_time=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_session_time) and $dbb_session_time=="session_time")
                                        {
                                            $dbb_session_time=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_session_time',':=','$dba_session_time')");
                                        }

                                        //port_limit (`radreply`) then (create group in `radusergroup`) then ()
                                        $dbb_port_limit=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_port_limit) and $dbb_port_limit=="port_limit")
                                        {
                                            $dbb_port_limit=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','Port-Limit',':=','$dba_port_limit')");
                                            // @mysqli_query($conn,"insert into `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) values ('$user','Simultaneous-Use',':=','$dba_port_limit')");
                                            // @mysqli_query($conn,"insert into `radusergroup` (`username`,`groupname`,`priority`) values ('$user','$user','1')");
                                        }

                                        //idle_timeout
                                        $dbb_idle_timeout=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_idle_timeout) and $dbb_idle_timeout=="idle_timeout")
                                        {
                                            $dbb_idle_timeout=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_idle_timeout',':=','$dba_idle_timeout')");
                                            //@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','keepalive-Timeout',':=','$dba_idle_timeout')");
                                        }

                                        //quota_limit_upload
                                        $dbb_quota_limit_upload=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_quota_limit_upload) and $dbb_quota_limit_upload=="quota_limit_upload")
                                        {
                                            $dbb_quota_limit_upload=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_quota_limit_upload',':=','$dba_quota_limit_upload')");
                                        }

                                        //quota_limit_download
                                        $dbb_quota_limit_download=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_quota_limit_download) and $dbb_quota_limit_download=="quota_limit_download")
                                        {
                                            $dbb_quota_limit_download=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_quota_limit_download',':=','$dba_quota_limit_download')");
                                        }

                                        //quota_limit_total
                                        $dbb_quota_limit_total=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_quota_limit_total) and $dbb_quota_limit_total=="quota_limit_total")
                                        {
                                            $dbb_quota_limit_total=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_quota_limit_total',':=','$dba_quota_limit_total')");
                                        }

                                        //speed_limit
                                        $dbb_speed_limit=$row_getRadiusAttr['attribute_type'];
                                        if(isset($dba_speed_limit) and $dbb_speed_limit=="speed_limit")
                                        {
                                            $dbb_speed_limit=$row_getRadiusAttr['attribute'];
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_speed_limit',':=','$dba_speed_limit')");
                                        }

                                        // MIKROTIK_ADDRESS_LIST
                                        $dbb_address_list=$row_getRadiusAttr['attribute_type'];
                                        if($dbb_address_list=="address_list")
                                        {
                                            $dbb_address_list=$row_getRadiusAttr['attribute'];

                                            if($row_getGroupDatails['name'] and $row_getGroupDatails['name']!="" and strpos($row_getGroupDatails['name'], "Special Rules") === false)
                                            {$address_list_name=$row_getGroupDatails['name'];}
                                            elseif($row_checkUser['u_uname'] and $row_checkUser['u_uname']!="")
                                            {$address_list_name=$row_checkUser['u_uname'];}
                                            else{$address_list_name=$user;}

                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','$dbb_address_list',':=','$address_list_name')");
                                        }

                                    }//while ($row_getRadiusAttr=@mysqli_fetch_array($r_getRadiusAttr))

                                    if($dba_radius_type == "ddwrt" or $dba_radius_type == "aruba")
                                    {
                                        // insert speed_max_up and down
                                        $firstExplode=explode(" ",$dba_speed_limit); 
                                        if(isset($firstExplode[1])){
                                            
                                            //XXXXXXXXXXXXX Browseing speed XXXXXXXXXXXXX
                                            $secondExplode=explode(" ",$firstExplode[1]);

                                            $equFirstEX=explode("/",$secondExplode[0]);
                                            //upload
                                            $equUPcheck=explode("K",$equFirstEX[0]);
                                            if(isset($equUPcheck[1])){
                                                //so this is upload speed in KB
                                                $finalEndBrowseModeUploadType="K";
                                                $finalEndBrowseModeUploadSpeed=$equUPcheck[0];
                                                $speed_max_up = $finalEndBrowseModeUploadSpeed*1024;
                                            }else{
                                                //so this is upload speed in MB
                                                $finalEndBrowseModeUploadType="M";
                                                $equStepMegaByteUpload=explode("M",$equFirstEX[0]);
                                                $finalEndBrowseModeUploadSpeed=$equStepMegaByteUpload[0];
                                                $speed_max_up = $finalEndBrowseModeUploadSpeed*1024*1024;
                                            }

                                            // download
                                            $equDowncheck=explode("K",$equFirstEX[1]);
                                            if(isset($equDowncheck[1])){
                                                //so this is Download speed in KB
                                                $finalEndBrowseModeDownloadType="K";
                                                $finalEndBrowseModeDownloadSpeed=$equDowncheck[0];
                                                $speed_max_down = $finalEndBrowseModeDownloadSpeed*1024;
                                            }else{
                                                //so this is Download speed in MB
                                                $finalEndBrowseModeDownloadType="M";
                                                $equStepMegaByteDownload=explode("M",$equFirstEX[1]);
                                                $finalEndBrowseModeDownloadSpeed=$equStepMegaByteDownload[0];
                                                $speed_max_down = $finalEndBrowseModeDownloadSpeed*1024*1024;
                                            }
                                            
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','WISPr-Bandwidth-Max-Up',':=','$speed_max_up')");
                                            // @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','WISPr-Bandwidth-Max-Down',':=','$speed_max_down')");

                                            if( $dba_radius_type == "aruba" ){
                                                // Nomadix attributes using to apply specific upload and download rate for each device of this user
                                                //@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','Nomadix-Group-Bw-Policy-ID',':=','$db_Uid')");
                                            }
                                        }
                                    }
                                    // test static IP address
                                    // if($mac=="D8:D3:85:94:AF:D1"){
                                    //     @mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','Framed-IP-Address',':=','10.5.50.8')");
                                    //     //@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','MT-Host-IP',':=','10.5.50.155')");
                                    // } 

                                    include 'auto_insert_mac.php';
                                    
                                    include 'campaign.php';

                                    $Acct_Session_Id=$Acct_Session_Id; // %{Acct-Session-Id}
                                    $NAS_IP_Address=$NAS_IP_Address;     //NAS-IP-Address

                                    // 16/6/2016 we are changed insert 'radacct' record while user login from 'dialup.conf' to the following query
                                    if($dba_radius_type != "aruba"){
                                        // @mysqli_query($conn,"delete from `radacct` where `acctstarttime` is null");
                                    }
                                    
                                    // $insertTest="insert into `radacct` (`acctsessionid`,`nasipaddress`,`username`,`u_id`,`dates`,`branch_id`,`group_id`,`network_id`,`total_quota`) values ('$Acct_Session_Id','$NAS_IP_Address','$user','$db_Uid','$today','$location_ID','$db_group_id','$db_networkID','$totalQuota')";
                                    @mysqli_query($conn,$insertTest);

                                    //3/8/2016 send token for each user after successfully login from hotspot login page
                                    //$token=rand(1,9999).chr(rand(65,90)).rand(1111,5555).chr(rand(65,90)).rand(2222,6666).chr(rand(65,90)).rand(3333,7777).rand(4444,8888).rand(5555,9999).chr(rand(65,90));
                                    //@mysqli_query($conn,"update `users` set `token`='$token' where `u_id`='$db_Uid'");
                                    //@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$user','WISPr-Redirection-URL',':=','http://6433053c8827.sn.mynetname.net/~hotspot/login?tooooooooken')");
                                    // @mysqli_query($conn,"update `users` set `token`='' where `u_id`='$db_Uid'");// delete token after successfully login

                                    // set last_login_manual for auto login expiration function
                                    // if($userLoginByMacAuto!="yes"){@mysqli_query($conn,"update `users` set `last_login_manual`='$today_full24' where `u_id`='$db_Uid'");}
                                    // step 10 Allow user to login
                                    echo "Accept";


                                }//if($rejected!="yes")
                                else{
                                    echo "Reject1";// for any reason in step 7
                                }


                            }//End else if internet free
                            else{
                                if($sendAcceptFromPackagesToMaster!=1){
                                    echo "Reject6";// ( user didn't have valid days in any package and commercial mode is 0 ) or ( unknown commercial or free network mode )
                                }
                            }
                        }else{echo "Reject9";}// Auto Login expired 
                    }else{echo "Reject8";}// not allowed more sessions
                }else{echo "Reject7";}// check if user not have limited devices, or user have valid mac in user db, or user still have credit to add new mac in db
            }
            else{echo "Reject3"; }// Invalid user name or password or mac address not found
        }//if($currentNetworkState=="1")// Network Running
        else{
            echo "Reject4";// User Network Disabled
        }

    }// user founded
    else{echo "Reject5";}// Not found user or notfound mac

}else{echo "Reject10";}// system exceded max of concurrent users
@mysqli_close($conn);
?>