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

class Copy extends Controller
{
    /////////////////////////////////////////////////////////////////////////////////
    // http://demo.microsystem.com.eg/copy?from=villa307&to=homewifi&groupLimit=1000
    /////////////////////////////////////////////////////////////////////////////////
    // copy groups and users from database to another (used for whatsapp campaigns)
    public function copy(Request $request){
        
      date_default_timezone_set("Africa/Cairo");
          $today = date("Y-m-d");
          $today_time = date("g:i a");
          $created_at = $today." ".date("H:i:s");
      
          // get network and branch data
          $networkID = DB::table("$request->to.networks")->value('id');
          $branchID = DB::table("$request->to.branches")->value('id');

          // get total users in source DB
          $totalUsersInDistnationDB = DB::table("$request->from.users")->count();
          $totalSplits = round( $totalUsersInDistnationDB/$request->groupLimit,0 );
          $shiftAfter = round( $totalUsersInDistnationDB/$totalSplits,0 )+10;
          $groupID = array();
          // create groups in distnation DB
          for($i=1; $i<=$totalSplits; $i++){
              
              $groupID[$i] = DB::table("$request->to.area_groups")->insertGetId(array(
                'name' => "$request->from - $i"
              , 'is_active' => '1'
              , 'as_system' => '0'
              , 'radius_type' => 'mikrotik'
              , 'network_id' => $networkID
              ,  'created_at' => $created_at
              ));
          }
          
          $counter = 1;
          $groupIdCounter = 1;
          $currentGroupID = $groupID[1];
          foreach( DB::table("$request->from.users")->get() as $user){
              // check if we reached to shifting limit, raise $groupIdCounter by 1 to get next group ID
              if($counter >= $shiftAfter){ $counter = 1; $groupIdCounter++; $currentGroupID = $groupID[$groupIdCounter];}
              DB::table("$request->to.users")->insert([
                'Registration_type' => '2'
              , 'u_state' => '1'
              , 'suspend' => '0'
              , 'u_name' => $user->u_name
              , 'u_uname' => $user->u_uname
              , 'u_password' => $user->u_password
              , 'u_phone' => $user->u_phone
              , 'u_email' => $user->u_email
              , 'u_gender' => $user->u_gender
              , 'branch_id' => $branchID
              , 'network_id' => $networkID
              , 'group_id' => $currentGroupID
              , 'created_at' => $user->created_at
              ]);
              $counter++;
          }

      return "Done";
		
    }

    /////////////////////////////////////////////////////////////////////////////////
    // http://demo.microsystem.com.eg/upgradeAndExecuteSQLqueriesAllDB
    /////////////////////////////////////////////////////////////////////////////////
    // upgrade system version and execute database queries on all databases in one shot
    public function upgradeAndExecuteSQLqueriesAllDB(Request $request){

        $allCustomers=DB::table('customers')->where('database','mescolare')->groupBy('database')->get();
        // $allCustomers=DB::table('customers')->where('database','!=','4shopping')->where('database','!=','demo')->where('database','!=','alex')->where('database','!=','7starsmall')->where('database','!=','agora')->where('database','!=','aievcharger')->where('state','1')->groupBy('database')->get();
        // $allCustomers=DB::table('customers')->groupBy('database')->get();
        // $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        // $allCustomers=DB::table('customers')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
          // baron hotel upgrade 27.07.2022
          try { DB::statement( "ALTER TABLE `$Customer->database`.`users` CHANGE `notes` `notes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;"); }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "CREATE TABLE `$Customer->database`.`user_tags` (`id` int(11) NOT NULL,`pms_profile_id` int(11) NOT NULL DEFAULT 0,`u_id` int(11) NOT NULL, `tag` text DEFAULT NULL, `value` text DEFAULT NULL, `created_at` datetime NOT NULL DEFAULT current_timestamp() ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");}catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "ALTER TABLE `$Customer->database`.`user_tags` ADD PRIMARY KEY (`id`);");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "ALTER TABLE `$Customer->database`.`user_tags` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "ALTER TABLE `$Customer->database`.`pms` CHANGE `last_ckeck` `last_check` DATETIME NULL DEFAULT NULL;");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "ALTER TABLE `$Customer->database`.`pms` ADD `internet_group` INT NOT NULL DEFAULT '0' COMMENT 'Check-In Basic Group' AFTER `login_password`;");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "ALTER TABLE `$Customer->database`.`pms` ADD `checkout_group` INT NOT NULL DEFAULT '0' AFTER `internet_group`;");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "ALTER TABLE `$Customer->database`.`branches` CHANGE `last_check` `last_check` DATETIME NULL DEFAULT NULL AFTER `serial`, CHANGE `cpu` `cpu` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `last_check`, CHANGE `uptime` `uptime` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `cpu`, CHANGE `ram` `ram` VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `uptime`, CHANGE `boardname` `boardname` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `ram`, CHANGE `reset` `reset` TINYINT(1) NULL DEFAULT '0' AFTER `boardname`, CHANGE `reboot` `reboot` TINYINT(1) NULL DEFAULT '0' AFTER `reset`, CHANGE `connection_type` `connection_type` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '1=ADSL, 2=PPP, 3=vodafone4G 4=etisalat4G, 5=orange4G' AFTER `reboot`, CHANGE `backup_connection_type` `backup_connection_type` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '1=ADSL, 2=PPP, 3=vodafone4G 4=etisalat4G, 5=orange4G' AFTER `connection_type`, CHANGE `adsl_user` `adsl_user` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `backup_connection_type`, CHANGE `adsl_pass` `adsl_pass` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `adsl_user`, CHANGE `wireless_state` `wireless_state` TINYINT(1) NULL DEFAULT '0' AFTER `adsl_pass`, CHANGE `wireless_name` `wireless_name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `wireless_state`, CHANGE `wireless_pass` `wireless_pass` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `wireless_name`, CHANGE `private_wireless_state` `private_wireless_state` TINYINT(1) NULL DEFAULT '0' AFTER `wireless_pass`, CHANGE `private_wireless_name` `private_wireless_name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `private_wireless_state`, CHANGE `private_wireless_pass` `private_wireless_pass` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `private_wireless_name`, CHANGE `private_wireless_ip` `private_wireless_ip` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `private_wireless_pass`, CHANGE `hacking_protection` `hacking_protection` TINYINT(1) NULL DEFAULT '0' AFTER `private_wireless_ip`, CHANGE `load_balance_state` `load_balance_state` TINYINT(1) NULL DEFAULT '0' AFTER `hacking_protection`, CHANGE `load_balance_type` `load_balance_type` VARCHAR(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '1=PCC, 2=NTH' AFTER `load_balance_state`, CHANGE `users_log_history_state` `users_log_history_state` TINYINT(4) NULL DEFAULT '0' AFTER `load_balance_type`, CHANGE `users_log_history_type` `users_log_history_type` INT(1) NULL DEFAULT NULL COMMENT '1=websites, 2=detailed IP of outgoing requests, 3=detailed IP of inbound requests, 4=detailed IP of inbound and outgoing requests, 5=websites+detailed IP of outgoing requests' AFTER `users_log_history_state`, CHANGE `auto_login` `auto_login` TINYINT(1) NULL DEFAULT '1' AFTER `users_log_history_type`, CHANGE `change_auto_login` `change_auto_login` TINYINT(4) NULL DEFAULT NULL AFTER `auto_login`, CHANGE `auto_login_expiry` `auto_login_expiry` INT(11) NULL DEFAULT '0' AFTER `change_auto_login`, CHANGE `hardware_version` `hardware_version` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '\'mikrotik_6.X\'' AFTER `auto_login_expiry`, CHANGE `block_windows_update` `block_windows_update` TINYINT(4) NULL DEFAULT NULL AFTER `hardware_version`, CHANGE `change_block_windows_update` `change_block_windows_update` TINYINT(4) NULL DEFAULT NULL AFTER `block_windows_update`, CHANGE `block_torrent_download` `block_torrent_download` TINYINT(4) NULL DEFAULT NULL AFTER `change_block_windows_update`, CHANGE `change_block_torrent_download` `change_block_torrent_download` TINYINT(4) NULL DEFAULT NULL AFTER `block_torrent_download`, CHANGE `block_downloading` `block_downloading` TINYINT(4) NULL DEFAULT NULL AFTER `change_block_torrent_download`, CHANGE `change_block_downloading` `change_block_downloading` TINYINT(4) NULL DEFAULT NULL AFTER `block_downloading`, CHANGE `antivirus` `antivirus` TINYINT(4) NULL DEFAULT NULL AFTER `change_block_downloading`, CHANGE `change_antivirus` `change_antivirus` TINYINT(4) NULL DEFAULT NULL AFTER `antivirus`, CHANGE `change_connection_type` `change_connection_type` TINYINT(1) NULL DEFAULT '0' AFTER `change_antivirus`, CHANGE `change_adsl_user` `change_adsl_user` TINYINT(1) NULL DEFAULT '0' AFTER `change_connection_type`, CHANGE `change_adsl_pass` `change_adsl_pass` TINYINT(1) NULL DEFAULT '0' AFTER `change_adsl_user`, CHANGE `change_wireless_state` `change_wireless_state` TINYINT(1) NULL DEFAULT '0' AFTER `change_adsl_pass`, CHANGE `change_wireless_name` `change_wireless_name` TINYINT(1) NULL DEFAULT '0' AFTER `change_wireless_state`, CHANGE `change_wireless_pass` `change_wireless_pass` TINYINT(1) NULL DEFAULT '0' AFTER `change_wireless_name`, CHANGE `change_private_wireless_state` `change_private_wireless_state` TINYINT(1) NULL DEFAULT '0' AFTER `change_wireless_pass`, CHANGE `change_private_wireless_name` `change_private_wireless_name` TINYINT(1) NULL DEFAULT '0' AFTER `change_private_wireless_state`, CHANGE `change_private_wireless_pass` `change_private_wireless_pass` TINYINT(1) NULL DEFAULT '0' AFTER `change_private_wireless_name`, CHANGE `change_private_wireless_ip` `change_private_wireless_ip` TINYINT(1) NULL DEFAULT '0' AFTER `change_private_wireless_pass`, CHANGE `change_hacking_protection` `change_hacking_protection` TINYINT(1) NULL DEFAULT '0' AFTER `change_private_wireless_ip`, CHANGE `change_load_balance_state` `change_load_balance_state` TINYINT(1) NULL DEFAULT '0' AFTER `change_hacking_protection`, CHANGE `change_load_balance_type` `change_load_balance_type` TINYINT(1) NULL DEFAULT '0' AFTER `change_load_balance_state`, CHANGE `change_username_or_password` `change_username_or_password` TINYINT(1) NULL DEFAULT '0' AFTER `change_load_balance_type`, CHANGE `change_state` `change_state` TINYINT(1) NULL DEFAULT '0' AFTER `change_username_or_password`, CHANGE `backup_connection_state` `backup_connection_state` TINYINT(1) NULL DEFAULT '0' AFTER `change_state`, CHANGE `change_backup_connection_state` `change_backup_connection_state` TINYINT(1) NULL DEFAULT '0' AFTER `backup_connection_state`, CHANGE `change_backup_connection_type` `change_backup_connection_type` TINYINT(1) NULL DEFAULT '0' AFTER `change_backup_connection_state`, CHANGE `change_users_log_history_state` `change_users_log_history_state` TINYINT(4) NULL DEFAULT '0' AFTER `change_backup_connection_type`, CHANGE `change_users_log_history_type` `change_users_log_history_type` TINYINT(4) NULL DEFAULT '0' AFTER `change_users_log_history_state`, CHANGE `internet_mode` `internet_mode` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '\'default\'' COMMENT 'default, home, office, gaming' AFTER `change_users_log_history_type`, CHANGE `change_internet_mode` `change_internet_mode` TINYINT(1) NULL DEFAULT NULL COMMENT '0|1' AFTER `internet_mode`, CHANGE `pms_premium_login_state` `pms_premium_login_state` TINYINT(1) NOT NULL DEFAULT '1' AFTER `change_internet_mode`, CHANGE `pms_complementary_login_state` `pms_complementary_login_state` TINYINT(1) NOT NULL DEFAULT '1' AFTER `pms_premium_login_state`, CHANGE `temporary_group_switching_state` `temporary_group_switching_state` INT(11) NOT NULL DEFAULT '0' AFTER `pms_complementary_login_state`, CHANGE `temporary_group_switching_group_id` `temporary_group_switching_group_id` INT(11) NOT NULL DEFAULT '0' AFTER `temporary_group_switching_state`");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "ALTER TABLE `$Customer->database`.`branches` ADD `temporary_group_switching_exception_groups` TEXT NOT NULL DEFAULT '0' AFTER `temporary_group_switching_group_id`;");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `$Customer->database`.`radacct_today_consumption_active_users`  AS SELECT `$Customer->database`.`radacct`.`u_id` AS `u_id`, curdate() AS `dates`, sum(`$Customer->database`.`radacct`.`acctinputoctets`) AS `TodayUpload`, sum(`$Customer->database`.`radacct`.`acctoutputoctets`) AS `TodayDownload`, sum(`$Customer->database`.`radacct`.`acctinputoctets` + `$Customer->database`.`radacct`.`acctoutputoctets`) AS `TodayTotalConsumption`, sum(`$Customer->database`.`radacct`.`acctsessiontime`) AS `TodayTotalSessionsTime` FROM `$Customer->database`.`radacct` WHERE `$Customer->database`.`radacct`.`dates` = curdate() GROUP BY `$Customer->database`.`radacct`.`u_id` ;");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }
          try { DB::statement( "CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `$Customer->database`.`radacct_active_users`  AS SELECT `$Customer->database`.`users`.`u_name` AS `u_name`
              , `$Customer->database`.`users`.`u_phone` AS `u_phone`
              , `$Customer->database`.`area_groups`.`name` AS `groupname`
              , `$Customer->database`.`radacct`.`radacctid` AS `radacctid`
              , `$Customer->database`.`radacct`.`acctsessionid` AS `acctsessionid`
              , `$Customer->database`.`radacct`.`acctuniqueid` AS `acctuniqueid`
              , `$Customer->database`.`radacct`.`username` AS `username`
              , `$Customer->database`.`radacct`.`servicetype` AS `wifi_signal`
              , `$Customer->database`.`radacct`.`framedprotocol` AS `speed_rate`
              , `$Customer->database`.`radacct`.`acctauthentic` AS `uptime`
              , `$Customer->database`.`radacct`.`acctstarttime` AS `acctstarttime`
              , `$Customer->database`.`radacct`.`groupname` AS `devicename`
              , `$Customer->database`.`radacct`.`acctstoptime` AS `acctstoptime`
              , `$Customer->database`.`radacct`.`acctsessiontime` AS `acctsessiontime`
              , `$Customer->database`.`radacct`.`acctinputoctets` AS `acctinputoctets`
              , `$Customer->database`.`radacct`.`acctoutputoctets` AS `acctoutputoctets`
              , `$Customer->database`.`radacct`.`callingstationid` AS `callingstationid`
              , `$Customer->database`.`radacct`.`framedipaddress` AS `framedipaddress`
              , `$Customer->database`.`radacct`.`u_id` AS `u_id`
              , `$Customer->database`.`radacct`.`branch_id` AS `branch_id`
              , `$Customer->database`.`radacct`.`group_id` AS `group_id`
              , `$Customer->database`.`radacct`.`network_id` AS `network_id`
              , `$Customer->database`.`radacct`.`total_quota` AS `total_quota`
              , `$Customer->database`.`area_groups`.`speed_limit` AS `speed_limit`
              , `$Customer->database`.`area_groups`.`end_speed` AS `end_speed`
              , `$Customer->database`.`branches`.`name` AS `branch_name`
              , `$Customer->database`.`radacct`.`realm` AS `realm`
              , `$Customer->database`.`radacct_today_consumption_active_users`.`TodayUpload` AS `TodayUpload`
              , `$Customer->database`.`radacct_today_consumption_active_users`.`TodayDownload` AS `TodayDownload` FROM ((((`$Customer->database`.`users` join `$Customer->database`.`radacct` on(`$Customer->database`.`radacct`.`u_id` = `$Customer->database`.`users`.`u_id`)) join `$Customer->database`.`area_groups` on(`$Customer->database`.`area_groups`.`id` = `$Customer->database`.`users`.`group_id`)) join `$Customer->database`.`branches` on(`$Customer->database`.`radacct`.`branch_id` = `$Customer->database`.`branches`.`id`)) join `$Customer->database`.`radacct_today_consumption_active_users` on(`$Customer->database`.`radacct_today_consumption_active_users`.`u_id` = `$Customer->database`.`users`.`u_id`)) WHERE `$Customer->database`.`radacct`.`acctstoptime` is null AND `$Customer->database`.`radacct`.`acctstarttime` is not null GROUP BY `$Customer->database`.`radacct`.`radacctid` ;");
            }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }

            try { DB::statement( "CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `$Customer->database`.`groups_network` AS SELECT `$Customer->database`.`area_groups`.`id` AS `id`, `$Customer->database`.`area_groups`.`name` AS `name`, `$Customer->database`.`area_groups`.`is_active` AS `is_active`, `$Customer->database`.`area_groups`.`as_system` AS `as_system`, `$Customer->database`.`area_groups`.`radius_type` AS `radius_type`, `$Customer->database`.`area_groups`.`url_redirect` AS `url_redirect`, `$Customer->database`.`area_groups`.`url_redirect_Interval` AS `url_redirect_Interval`, `$Customer->database`.`area_groups`.`session_time` AS `session_time`, `$Customer->database`.`area_groups`.`port_limit` AS `port_limit`, `$Customer->database`.`area_groups`.`idle_timeout` AS `idle_timeout`, `$Customer->database`.`area_groups`.`quota_limit_upload` AS `quota_limit_upload`, `$Customer->database`.`area_groups`.`quota_limit_download` AS `quota_limit_download`, `$Customer->database`.`area_groups`.`quota_limit_total` AS `quota_limit_total`, `$Customer->database`.`area_groups`.`speed_limit` AS `speed_limit`, `$Customer->database`.`area_groups`.`renew` AS `renew`, `$Customer->database`.`area_groups`.`if_downgrade_speed` AS `if_downgrade_speed`, `$Customer->database`.`area_groups`.`end_speed` AS `end_speed`, `$Customer->database`.`area_groups`.`network_id` AS `network_id`, `$Customer->database`.`area_groups`.`created_at` AS `created_at`, `$Customer->database`.`area_groups`.`updated_at` AS `updated_at`, `$Customer->database`.`area_groups`.`notes` AS `notes`, `$Customer->database`.`networks`.`name` AS `n_name`, `$Customer->database`.`radacct_group_months`.`acctinputoctets` AS `this_month_acctinputoctets`, `$Customer->database`.`radacct_group_months`.`acctoutputoctets` AS `this_month_acctoutputoctets`, `$Customer->database`.`radacct_group_months`.`total` AS `this_month_total_consumption`, `$Customer->database`.`radacct_group_months`.`acctsessiontime` AS `this_month_acctsessiontime` FROM ((`$Customer->database`.`area_groups` join `$Customer->database`.`networks` on(`$Customer->database`.`area_groups`.`network_id` = `$Customer->database`.`networks`.`id`)) LEFT join `$Customer->database`.`radacct_group_months` on(`$Customer->database`.`radacct_group_months`.`group_id` = `$Customer->database`.`area_groups`.`id`)) GROUP BY `$Customer->database`.`area_groups`.`id` ;");   }catch(\Illuminate\Database\QueryException $ex){ print_r($ex->getMessage());  echo "<br><br>"; }

            echo "$Customer->database DONE <br>";
          
        }
    }

}