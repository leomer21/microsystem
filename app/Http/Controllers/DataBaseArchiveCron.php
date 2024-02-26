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

class DataBaseArchiveCron extends Controller
{
    
    public function dataBaseArchiveCron(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d 00:00:00");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        $currentHour = date("H");
        $today_full24=$created_at;
        $lastWeek = date('Y-m-d', strtotime("-7 days", strtotime($today)));
        $last2Weeks = date('Y-m-d', strtotime("-14 days", strtotime($today)));
        require_once '../config.php';

       
        $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {

            // check if the Archive database exist or not
            try{
                // echo "Database Exist";
                DB::table($Customer->database."_archive.users")->limit(1)->get();
            }catch (\Exception $e) {
                // create DB tables by copy "MicrosystemDefault" db to new DB
                DB::statement("create database $Customer->database"."_archive;");
                shell_exec("mysqldump -u $sys_db_user --password='$sys_db_pass' MicrosystemDefault | mysql -u $sys_db_user --password='$sys_db_pass' -h localhost $Customer->database"."_archive;");
            }
            
            // truncate all unused data tables
            DB::statement( 'TRUNCATE TABLE '.$Customer->database.'.radreply;');
            DB::statement( 'TRUNCATE TABLE '.$Customer->database.'.radpostauth;');
            DB::statement( 'TRUNCATE TABLE '.$Customer->database.'.radcheck;');
            DB::statement( 'TRUNCATE TABLE '.$Customer->database.'.radgroupcheck;');
            DB::statement( 'TRUNCATE TABLE '.$Customer->database.'.radusergroup;');

            // Add DNS record at Orange
            // then add record at customers table
                // INSERT INTO `customers` (`id`, `url`, `database`, `next_bill`, `admin_username`, `admin_password`, `password`, `state`, `can_buy`, `buy_monthly`, `buy_quarterly`, `buy_semiannually`, `buy_annually`, `max_concurrent`, `package_id`, `payasyougo`, `payasyougo_max_limit`, `start_date`, `currency`, `global`, `websites_log`, `whatsapp`, `whatsapp_credit`, `name`, `role`, `company_type`, `address`, `phone`, `mail`, `notes`, `last_user_id`, `pos_rocket_id`, `microsystem_sms_verfy_free`, `microsystem_whatsapp_verfy_free`, `updated_at`) VALUES (NULL, 'baronarchive.microsystem.com.eg', 'baron', NULL, '3094-11-09', NULL, NULL, '0', '0', '0', '1', '0', '1', '150', NULL, NULL, '100', '2023-03-12 21:11:10.000000', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, '0', '0', current_timestamp());
            // then create domain record at CWP user panel

            // Copy table admins
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`admins`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`admins` SELECT * FROM `'.$Customer->database.'`.`admins`');

            // Copy table area_groups
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`area_groups`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`area_groups` SELECT * FROM `'.$Customer->database.'`.`area_groups`');

            // Copy table branches
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`branches`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`branches` SELECT * FROM `'.$Customer->database.'`.`branches`');

            // Copy table campaigns
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`campaigns`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`campaigns` SELECT * FROM `'.$Customer->database.'`.`campaigns`');

            // Copy table load_balancing
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`load_balancing`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`load_balancing` SELECT * FROM `'.$Customer->database.'`.`load_balancing`');

            // Copy table networks
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`networks`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`networks` SELECT * FROM `'.$Customer->database.'`.`networks`');

            // Copy table packages
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`packages`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`packages` SELECT * FROM `'.$Customer->database.'`.`packages`');

            // Copy table pms
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`pms`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`pms` SELECT * FROM `'.$Customer->database.'`.`pms`');

            // Copy table settings
            DB::statement( 'TRUNCATE TABLE `'.$Customer->database.'_archive`.`settings`');
            DB::statement( 'INSERT INTO `'.$Customer->database.'_archive`.`settings` SELECT * FROM `'.$Customer->database.'`.`settings`');

            /*
            // Move Visits till last week
            $visitors = DB::table($Customer->database.'.visitors')->where('created_at', '<', $lastWeek)->limit(4000)->get();
            if (count($visitors) > 0) {
                // archive the rows in another database
                // DB::table($Customer->database.'_archive.visitors')->insert(collect($visitors)->map(function ($item) {return (array) $item;})->toArray());
                DB::table($Customer->database.'_archive.visitors')->ignore()->insert(collect($visitors)->map(function ($item) {return (array) $item;})->toArray());

                // DB::connection($Customer->database.'_archive')->table('visitors')->insert($visitors->toArray());
                // delete the rows from the original database
                DB::table($Customer->database.'.visitors')->where('created_at', '<', $lastWeek)->limit(4000)->delete();
                echo "<br> Visits moved till last week: ".count($visitors);
            }
            return "<br> END";
            // check if there is active PMS integration
            $pms = DB::table($Customer->database.'.pms')->where('state', '1')->first();
            if(isset($pms)){

                // Move checked out users to the archive 
                // $checkedOut = DB::table($Customer->database.'.users')->where('pms_id', 0)->where('group_id', $pms->checkout_group)->where('pms_guest_id', '<>', '0')->where('updated_at', '<', $today)->limit(4000)->get();
                return $checkedOut = DB::table($Customer->database.'.users')->where('suspend', 0)->where('pms_id', 0)->where('group_id', $pms->checkout_group)->where('pms_guest_id', '<>', '0')->where('updated_at', '<', Carbon::now()->subDays(7))->limit(4000)->get();
                if (count($checkedOut) > 0) {
                    // archive the rows in another database
                    DB::table($Customer->database.'_archive.users')->insert(collect($checkedOut)->map(function ($item) {return (array) $item;})->toArray());
                    // delete the rows from the original database
                    DB::table($Customer->database.'.users')->where('pms_id', 0)->where('group_id', $pms->checkout_group)->where('pms_guest_id', '<>', '0')->where('updated_at', '<', Carbon::now()->subDays(7))->limit(4000)->limit(4000)->delete();
                    echo "<br> Checked out users moved: ".count($checkedOut);
                }

                // Move all yesterday complementry guests 
                $complementaryUsers = DB::table($Customer->database.'.users')->where('suspend', 0)->where('group_id', '71')->where('pms_guest_id', '=', '0')->where('created_at', '<', "$today")->limit(4000)->get();
                if (count($complementaryUsers) > 0) {
                    // archive the rows in another database
                    DB::table($Customer->database.'_archive.users')->insert(collect($complementaryUsers)->map(function ($item) {return (array) $item;})->toArray());
                    // delete the rows from the original database
                    DB::table($Customer->database.'.users')->where('group_id', '71')->where('pms_guest_id', '=', '0')->where('created_at', '<', "$today")->limit(4000)->delete();
                    DB::statement( 'OPTIMIZE TABLE '.$Customer->database.'.users;');
                    echo "<br> Complementry users moved: ".count($complementaryUsers);
                }

                // Move Radius sessions till last week
                $radiusSessions = DB::table($Customer->database.'.radacct')->where('dates', '<', $last2Weeks)->limit(4000)->get();
                if (count($radiusSessions) > 0) {
                    // archive the rows in another database
                    DB::table($Customer->database.'_archive.radacct')->insert(collect($radiusSessions)->map(function ($item) {return (array) $item;})->toArray());
                    // delete the rows from the original database
                    DB::table($Customer->database.'.radacct')->where('dates', '<', $last2Weeks)->limit(4000)->delete();
                    DB::statement( 'OPTIMIZE TABLE '.$Customer->database.'.radacct;');
                    echo "<br> radacct sessions moved: ".count($radiusSessions);
                }

            }
            */
            
        }

    echo "<center><h1>Archiving Done</h1></center>";
	}
	
}