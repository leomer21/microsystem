<?php 

// step 1 : get required variables

        // user table
        $monthly_package_start=$row_checkUser['monthly_package_start'];
        $monthly_package_expiry=$row_checkUser['monthly_package_expiry'];

        // package table
        $getPackageData="select * from `packages` where `id`='$monthly_package_id'";
        $r_getPackageData=@mysqli_query($conn,$getPackageData);
        $row_getPackageData=@mysqli_fetch_array($r_getPackageData);
        $packagePrice=$row_getPackageData['price'];
        $packagePeriod=$row_getPackageData['period'];
        $packageGroupID=$row_getPackageData['group_id'];

        // Group Table
        if($packageGroupID){
            $getPackageGroupData="select * from `area_groups` where `id`='$packageGroupID'";
            $r_getPackageGroupData=@mysqli_query($conn,$getPackageGroupData);
            $row_getPackageGroupData=@mysqli_fetch_array($r_getPackageGroupData);
            $radius_type=$row_getPackageGroupData['radius_type'];
            $url_redirect=$row_getPackageGroupData['url_redirect'];
            $url_redirect_Interval=$row_getPackageGroupData['url_redirect_Interval'];
            if($url_redirect_Interval){$url_redirect_Interval = strtotime("$url_redirect_Interval") - strtotime('TODAY');} // convert Time "01:00:00" seconds 3600
            $session_time=$row_getPackageGroupData['session_time'];
            if($session_time){$session_time = strtotime("$session_time") - strtotime('TODAY');} // convert Time "01:00:00" seconds 3600
            $port_limit=$row_getPackageGroupData['port_limit'];
            $idle_timeout=$row_getPackageGroupData['idle_timeout'];
            if($idle_timeout ){$idle_timeout = strtotime("$idle_timeout") - strtotime('TODAY');} // convert Time "01:00:00" seconds 3600
            $quota_limit_upload=$row_getPackageGroupData['quota_limit_upload'];
            $quota_limit_download=$row_getPackageGroupData['quota_limit_download'];
            $quota_limit_total=$row_getPackageGroupData['quota_limit_total'];
            $speed_limit=$row_getPackageGroupData['speed_limit'];
            $renew=$row_getPackageGroupData['renew'];
            $if_downgrade_speed=$row_getPackageGroupData['if_downgrade_speed'];
            $end_speed=$row_getPackageGroupData['end_speed'];
        }
        // Get Bandwidth Data and add them to this package if exist and valid
        if($quota_limit_upload or $quota_limit_download or $quota_limit_total){// if user have quota limit
            if($bandwidth_package_id){// if user have bandwidth package
                $bandwidth_package_before_convert= strtotime("$monthly_package_expiry");
                $today_before_convert = strtotime("$today");
                $datediff_before_convert = ($bandwidth_package_before_convert - $today_before_convert ); // sub dates
                $final_sub_bandwidth_date=round($datediff_before_convert/86400);
                if($final_sub_bandwidth_date>=0){// user have remaining days in bandwidth package
                    $getBandwidthData = "select * from `packages` where `id`='$bandwidth_package_id'";
                    $r_getBandwidthData = @mysqli_query($conn,$getBandwidthData);
                    $row_getBandwidthData = @mysqli_fetch_array($r_getBandwidthData);
                    $extraBandwidthPackage = $row_getBandwidthData['period'];
                    $extraBandwidthPackage = $extraBandwidthPackage * 1024 * 1024 * 1024;
                    $quota_limit_total = $extraBandwidthPackage;// set new quota
                    $monthly_package_start=$bandwidth_package_start;
                }else{// user have bandwidth package but expired so i will delete it
                    @mysqli_query($conn,"update `users` set `bandwidth_package_id`=null, `bandwidth_package_start`=null, `bandwidth_package_expiry`=null where `u_id`='$db_Uid'");
                }
            }
        }

        // detect if user logged in automatecally by new feature ( auto insert mac )
        if( $mac == $user ){$userLoginByMacAuto="yes";}


// step 2 : check if package has expired
       $nowdate_charging_x_x_x = strtotime("$monthly_package_expiry");
       $thendate_charging_x_x_x = strtotime("$today");
       $datediff_charging_x_x_x = ($nowdate_charging_x_x_x - $thendate_charging_x_x_x);                 // sub dates
       $final_validate_date_charging_x_x_x=round($datediff_charging_x_x_x/86400);

       if($final_validate_date_charging_x_x_x>=0) 
       { // user have remaining days
       	
// step 3 : check if user have limit
		 if($quota_limit_upload or $quota_limit_download or $quota_limit_total)
		 {
			
    // step 3.A : get login history

            // user logged in by new feature ( auto insert mac ) and logged in automatically
            if($userLoginByMacAuto=="yes"){$getAllUserRecords="SELECT * FROM `radacct` WHERE `username` = '$db_user' AND `acctstarttime` >= '$monthly_package_start'";}
            // Normal Case
            else{$getAllUserRecords="SELECT * FROM `radacct` WHERE `username` = '$user' AND `acctstarttime` >= '$monthly_package_start'";}
      		 $r_getLoginHistory=@mysqli_query($conn,$getAllUserRecords);
			 while ($row_getLoginHistory=@mysqli_fetch_array($r_getLoginHistory)) {
			 	$acctsessiontime+=$row_getLoginHistory['acctsessiontime'];// session time seconds
			 	$acctinputoctets+=$row_getLoginHistory['acctinputoctets'];// upload bytes
			 	$acctoutputoctets+=$row_getLoginHistory['acctoutputoctets'];// download bytes
			 }
    // step 3.B : check if limit exceed			 
			 if($quota_limit_upload and $quota_limit_upload!=0){
			 	$final_limit_bandwidth_upload=$quota_limit_upload-$acctinputoctets;
				if($final_limit_bandwidth_upload<=0){$rejected="yes";} // Reject Reason : quota upload ended
			 }
			 
			if($quota_limit_download and $quota_limit_download!=0){
			 	$final_limit_bandwidth_download=$quota_limit_download-$acctoutputoctets;
				if($final_limit_bandwidth_download<=0){$rejected="yes";} // Reject Reason : quota Download ended
			 }
			 
			if($quota_limit_total and $quota_limit_total!=0){
			 	$final_limit_bandwidth_total=$quota_limit_total-($acctinputoctets+$acctoutputoctets);
				if($final_limit_bandwidth_total<=0){$rejected="yes";} // Reject Reason : quota total ended
			 }
            
    // step 3.C : check for downgrade speed
			 if($rejected=="yes" and $if_downgrade_speed=="1"){// limit finished and speed will downgrade

			  $speed_limit=$end_speed;
			  $session_time=0;
			  $quota_limit_upload=0;
			  $quota_limit_download=0;
			  $quota_limit_total=0;
			  unset($rejected);
			  $endLimitOverwrited="yes";
			 }//if($rejected=="yes") 

		}

// step 4 : complete login process if user have limit or not
           
			if(!isset($rejected) or $rejected!="yes"){

				// put data to overwrite if user have limit but not finished it or user not have limit
				if($endLimitOverwrited!="yes"){
				// No change in first speed limit // $speed_limit=$speed_limit;
				$session_time=0;
				$quota_limit_upload=$final_limit_bandwidth_upload;
				$quota_limit_download=$final_limit_bandwidth_download;
				$quota_limit_total=$final_limit_bandwidth_total;
				}

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//  Delete all related user records in `radreply` to clr any previous data
				@mysqli_query($conn,"delete from `radreply` where `username`='$db_user'");
				@mysqli_query($conn,"delete from `radgroupcheck` where `groupname`='$db_user'");
				@mysqli_query($conn,"delete from `radusergroup` where `username`='$db_user'");
				@mysqli_query($conn,"delete from `radcheck` where `username`='$db_user'");
			
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//  add new user in table `radcheck` to learn radius to take attention to attriputes
				@mysqli_query($conn," insert into `radcheck` (`username`,`attribute`,`op`,`value`) values ('$db_user','User-Password',':=','') ");

// step 4.A get Radius type Attributes
				$getRadiusAttr="select * from `radius_attributes` where `radius_type`='$radius_type'";
				$r_getRadiusAttr=@mysqli_query($conn,$getRadiusAttr);
				while ($row_getRadiusAttr=@mysqli_fetch_array($r_getRadiusAttr)) {
				
				//url_redirect
				$dbb_url_redirect=$row_getRadiusAttr['attribute_type'];
				if(isset($url_redirect) and $dbb_url_redirect=="url_redirect")
				{	$dbb_url_redirect=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_url_redirect',':=','$url_redirect')");
				}
				//url_redirect_Interval
				$dbb_url_redirect_Interval=$row_getRadiusAttr['attribute_type'];
				if(isset($url_redirect_Interval) and $dbb_url_redirect_Interval=="url_redirect_Interval")
				{	$dbb_url_redirect_Interval=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_url_redirect_Interval',':=','$url_redirect_Interval')");
				} 
//				//session_time
//				$dbb_session_time=$row_getRadiusAttr['attribute_type'];
//				if(isset($session_time) and $dbb_session_time=="session_time")
//				{	$dbb_session_time=$row_getRadiusAttr['attribute'];
//					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_session_time',':=','$session_time')");
//				}
				//port_limit (`radreply`) then (create group in `radusergroup`) then ()
				$dbb_port_limit=$row_getRadiusAttr['attribute_type'];
				if(isset($port_limit) and $dbb_port_limit=="port_limit")
				{	$dbb_port_limit=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','Port-Limit',':=','$port_limit')");
					@mysqli_query($conn,"insert into `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) values ('$db_user','Simultaneous-Use',':=','$port_limit')");
					@mysqli_query($conn,"insert into `radusergroup` (`username`,`groupname`,`priority`) values ('$db_user','$db_user','1')");
				}
				//idle_timeout
				$dbb_idle_timeout=$row_getRadiusAttr['attribute_type'];
				if(isset($idle_timeout) and $dbb_idle_timeout=="idle_timeout")
				{	$dbb_idle_timeout=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_idle_timeout',':=','$idle_timeout')");
				}
				//quota_limit_upload
				$dbb_quota_limit_upload=$row_getRadiusAttr['attribute_type'];
				if(isset($quota_limit_upload) and $dbb_quota_limit_upload=="quota_limit_upload")
				{	$dbb_quota_limit_upload=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_quota_limit_upload',':=','$quota_limit_upload')");
				}
				//quota_limit_download
				$dbb_quota_limit_download=$row_getRadiusAttr['attribute_type'];
				if(isset($quota_limit_download) and $dbb_quota_limit_download=="quota_limit_download")
				{	$dbb_quota_limit_download=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_quota_limit_download',':=','$quota_limit_download')");
				}
				//quota_limit_total
				$dbb_quota_limit_total=$row_getRadiusAttr['attribute_type'];
				if(isset($quota_limit_total) and $dbb_quota_limit_total=="quota_limit_total")
				{	$dbb_quota_limit_total=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_quota_limit_total',':=','$quota_limit_total')");
				}
				
				//speed_limit
				$dbb_speed_limit=$row_getRadiusAttr['attribute_type'];
				if(isset($speed_limit) and $dbb_speed_limit=="speed_limit")
				{	$dbb_speed_limit=$row_getRadiusAttr['attribute'];
					@mysqli_query($conn,"insert into `radreply` (`username`,`attribute`,`op`,`value`) values ('$db_user','$dbb_speed_limit',':=','$speed_limit')");
				}
				
				
			}//while ($row_getRadiusAttr=@mysqli_fetch_array($r_getRadiusAttr)) 
	
// Step 4.C Get system information for 1: auto insert mac address

            include 'auto_insert_mac.php';

            $Acct_Session_Id=$argv[9]; // %{Acct-Session-Id}
            $NAS_IP_Address=$argv[10];     //NAS-IP-Address

            // 16/6/2016 we are changed insert 'radacct' record while user login from 'dialup.conf' to the following query
            @mysqli_query($conn,"delete from `radacct` where `acctstarttime` is null");
            $insertTest="insert into `radacct` (`acctsessionid`,`nasipaddress`,`username`,`u_id`,`dates`,`branch_id`,`group_id`,`network_id`,`total_quota`) values ('$Acct_Session_Id','$NAS_IP_Address','$user','$db_Uid','$today','$location_ID','$db_group_id','$db_networkID','$totalQuota')";
            @mysqli_query($conn,$insertTest);

            //3/8/2016 send token for each user after successfully login from hotspot login page
            @mysqli_query($conn,"update `users` set `token`='' where `u_id`='$db_Uid'");// delete token after successfully login

// step 4.D Allow user to login
			echo "Accept";
			$sendAcceptFromPackagesToMaster=1;

			
		}//if($rejected!="yes")
		else{//// Limit finished
			@mysqli_query($conn,"update `users` set `u_state`='0' where `u_id`='$db_Uid'");
			if($open_system=="2"){
                $goFreeInternet="yes";
            }// last chance if user can back to free internet
			else{echo "Reject";}
			}// Limit finished
		
				
       	
       	
       }else{
            //@mysqli_query($conn,"update `users` set `u_state`='0' where `u_id`='$db_Uid'");
            if($open_system=="2"){
                $goFreeInternet="yes";
                $checkOrderChargePackage="yes";
            }// last chance if user can back to free internet
            else{$checkOrderChargePackage="yes";}
       }// package has expired


?>