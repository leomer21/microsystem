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

// this function should run 1 time only at 6PM of each day (to make sure we collected guest contacts after ckecking in), and to send the holiday email before midnight
class CronScheduleHotelGuestNotificationsStep1 extends Controller
{ 
    public function cronScheduleHotelGuestNotificationsStep1(Request $request)
    {   
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        echo "Today is: $created_at <hr>";
        // get current and archive database
        $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            // publicHolidays
            if(DB::table("$Customer->database.campaigns")->where('type', 'publicHolidays')->value( 'state') == "1"){

                // check if we should send Public Holiday email reminder 1, 2, 3
                for($counterPublicHoliday=1; $counterPublicHoliday<=3; $counterPublicHoliday++){
                    // check state of sending Public Holiday
                    $sendPublicHolidayReminder = DB::table("$Customer->database.settings")->where('type', 'sendPublicHolidayEmailReminder'.$counterPublicHoliday)->first();
                    if(isset( $sendPublicHolidayReminder ) and $sendPublicHolidayReminder->state == "1"){
                        echo '<br>sendPublicHolidayEmailReminder'.$counterPublicHoliday;
                        // Get all matching holidays and add email notification record
                        $sendPublicHolidayEmailReminderChatGptContent = DB::table("$Customer->database.settings")->where('type', 'sendPublicHolidayEmailReminder'.$counterPublicHoliday.'chatGptContent')->value('value');
                        // $dataAfterAdd = '2023'.date('-m-d', strtotime("+$sendPublicHolidayReminder->value days", strtotime($today)));
                        // foreach( DB::table("$Customer->database.public_holidays")->where( 'date', $dataAfterAdd )->get() as $holiday){
                        $dataAfterAdd = date('m-d', strtotime("+$sendPublicHolidayReminder->value days", strtotime($today)));
                        foreach(DB::table("$Customer->database.public_holidays")->select('id', 'date', 'name', 'country_name', 'country_code', DB::raw("DATE_FORMAT(date, '%m-%d') as date"))->whereRaw("DATE_FORMAT(date,'%m-%d') = ?", [$dataAfterAdd])->get() as $holiday){
                            echo "<br>Public Holiday ($holiday->name) in $holiday->country_name at $dataAfterAdd";
                            // get users from current DB
                            foreach(DB::table("$Customer->database.users")->where( 'u_country', 'like', '%'.$holiday->country_name.'%' )->orWhere( 'u_country', $holiday->country_code )->get() as $user){
                                echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Email: $user->u_email";
                                // insert email notification record into `scheduled_notifications` table if email valid
                                if(isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database, 'public_holiday_id' => $holiday->id, 'u_id' => $user->u_id, 'email' => $user->u_email, 'chatgpt_content' => $sendPublicHolidayEmailReminderChatGptContent, 'by' => 'CronScheduleHotelGuestNotificationsStep'.$counterPublicHoliday, 'reason'=>"Public Holiday ($holiday->name) for $user->u_name `$user->u_id` at $holiday->date in $holiday->country_name.", 'created_at'=>$created_at]]); }else{echo " Not valid Email $user->u_email ";}
                            }
                            // get users from archive DB
                            foreach(DB::table("$Customer->database"."_archive.users")->where( 'u_country', 'like', '%'.$holiday->country_name.'%' )->orWhere( 'u_country', $holiday->country_code )->get() as $user){
                                echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Email: $user->u_email";
                                // insert email notification record into `scheduled_notifications` table if email valid
                                if(isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database."_archive", 'public_holiday_id' => $holiday->id, 'u_id' => $user->u_id,'email' => $user->u_email, 'chatgpt_content' => $sendPublicHolidayEmailReminderChatGptContent, 'by' => 'CronScheduleHotelGuestNotificationsStep'.$counterPublicHoliday, 'reason'=>"Public Holiday ($holiday->name) for $user->u_name `$user->u_id` at $holiday->date in $holiday->country_name.", 'created_at'=>$created_at]]); }else{echo " Not valid Email $user->u_email ";}
                            }
                        }
                    }
                }

                echo "<hr>";
                

                // check if we should send Public Holiday WhatsApp reminder 1, 2, 3
                for($counterPublicHoliday=1; $counterPublicHoliday<=3; $counterPublicHoliday++){
                    // check state of sending Public Holiday
                    $sendPublicHolidayReminder = DB::table("$Customer->database.settings")->where('type', 'sendPublicHolidayWhatsappReminder'.$counterPublicHoliday)->first();
                    if(isset( $sendPublicHolidayReminder ) and $sendPublicHolidayReminder->state == "1"){
                        echo '<br>sendPublicHolidayWhatsappReminder'.$counterPublicHoliday;
                        // Get all matching holidays and add Whatsapp notification record
                        $sendPublicHolidayWhatsappReminderChatGptContent = DB::table("$Customer->database.settings")->where('type', 'sendPublicHolidayWhatsappReminder'.$counterPublicHoliday.'chatGptContent')->value('value');
                        $dataAfterAdd = date('m-d', strtotime("+$sendPublicHolidayReminder->value days", strtotime($today)));
                        foreach(DB::table("$Customer->database.public_holidays")->select('id', 'date', 'name', 'country_name', 'country_code', DB::raw("DATE_FORMAT(date, '%m-%d') as date"))->whereRaw("DATE_FORMAT(date,'%m-%d') = ?", [$dataAfterAdd])->get() as $holiday){
                            echo "<br>Public Holiday ($holiday->name) in $holiday->country_name at $dataAfterAdd";
                            // get users from current DB
                            foreach(DB::table("$Customer->database.users")->where( 'u_country', 'like', '%'.$holiday->country_name.'%' )->orWhere( 'u_country', $holiday->country_code )->get() as $user){
                                echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                                // insert Whatsapp notification record into `scheduled_notifications` table if mobile valid
                                if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database, 'public_holiday_id' => $holiday->id, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $sendPublicHolidayWhatsappReminderChatGptContent, 'by' => 'CronScheduleHotelGuestNotificationsStep'.$counterPublicHoliday, 'reason'=>"Public Holiday ($holiday->name) for $user->u_name `$user->u_id` at $holiday->date in $holiday->country_name.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                            }
                            // get users from archive DB
                            foreach(DB::table("$Customer->database"."_archive.users")->where( 'u_country', 'like', '%'.$holiday->country_name.'%' )->orWhere( 'u_country', $holiday->country_code )->get() as $user){
                                echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                                // insert Whatsapp notification record into `scheduled_notifications` table if Whatsapp valid
                                if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database."_archive", 'public_holiday_id' => $holiday->id, 'u_id' => $user->u_id,'mobile' => $user->u_phone, 'chatgpt_content' => $sendPublicHolidayWhatsappReminderChatGptContent, 'by' => 'CronScheduleHotelGuestNotificationsStep'.$counterPublicHoliday, 'reason'=>"Public Holiday ($holiday->name) for $user->u_name `$user->u_id` at $holiday->date in $holiday->country_name.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                            }
                        }
                    }
                }

                echo "<hr>";

                // check if we should send Public Holiday SMS reminder 1, 2, 3
                for($counterPublicHoliday=1; $counterPublicHoliday<=3; $counterPublicHoliday++){
                    // check state of sending Public Holiday
                    $sendPublicHolidayReminder = DB::table("$Customer->database.settings")->where('type', 'sendPublicHolidaySMSReminder'.$counterPublicHoliday)->first();
                    if(isset( $sendPublicHolidayReminder ) and $sendPublicHolidayReminder->state == "1"){
                        echo '<br>sendPublicHolidaySMSReminder'.$counterPublicHoliday;
                        // Get all matching holidays and add SMS notification record
                        $sendPublicHolidaySMSReminderChatGptContent = DB::table("$Customer->database.settings")->where('type', 'sendPublicHolidaySMSReminder'.$counterPublicHoliday.'chatGptContent')->value('value');
                        $dataAfterAdd = date('m-d', strtotime("+$sendPublicHolidayReminder->value days", strtotime($today)));
                        foreach(DB::table("$Customer->database.public_holidays")->select('id', 'date', 'name', 'country_name', 'country_code', DB::raw("DATE_FORMAT(date, '%m-%d') as date"))->whereRaw("DATE_FORMAT(date,'%m-%d') = ?", [$dataAfterAdd])->get() as $holiday){
                            echo "<br>Public Holiday ($holiday->name) in $holiday->country_name at $dataAfterAdd";
                            // get users from current DB
                            foreach(DB::table("$Customer->database.users")->where( 'u_country', 'like', '%'.$holiday->country_name.'%' )->orWhere( 'u_country', $holiday->country_code )->get() as $user){
                                echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                                // insert SMS notification record into `scheduled_notifications` table if mobile valid
                                if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database, 'public_holiday_id' => $holiday->id, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $sendPublicHolidaySMSReminderChatGptContent, 'by' => 'CronScheduleHotelGuestNotificationsStep'.$counterPublicHoliday, 'reason'=>"Public Holiday ($holiday->name) for $user->u_name `$user->u_id` at $holiday->date in $holiday->country_name.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                            }
                            // get users from archive DB
                            foreach(DB::table("$Customer->database"."_archive.users")->where( 'u_country', 'like', '%'.$holiday->country_name.'%' )->orWhere( 'u_country', $holiday->country_code )->get() as $user){
                                echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                                // insert SMS notification record into `scheduled_notifications` table if SMS valid
                                if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database."_archive", 'public_holiday_id' => $holiday->id, 'u_id' => $user->u_id,'mobile' => $user->u_phone, 'chatgpt_content' => $sendPublicHolidaySMSReminderChatGptContent, 'by' => 'CronScheduleHotelGuestNotificationsStep'.$counterPublicHoliday, 'reason'=>"Public Holiday ($holiday->name) for $user->u_name `$user->u_id` at $holiday->date in $holiday->country_name.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                            }
                        }
                    }
                }

                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
            }

            // guestBirthdate
            if(DB::table("$Customer->database.campaigns")->where('type', 'guestBirthdate')->value( 'state') == "1"){

                // check for guest birthdate email 1, 2, 3
                for($counterBirthdateEmail=1; $counterBirthdateEmail<=3; $counterBirthdateEmail++){
                    $guestBirthdateEmail = DB::table("$Customer->database.settings")->where('type', 'guestBirthdateEmail'.$counterBirthdateEmail)->first();
                    if(isset( $guestBirthdateEmail ) and $guestBirthdateEmail->state == "1"){
                        echo '<br>guestBirthdateEmail'.$counterBirthdateEmail;
                        // Get all matching birthdates and add email notification record
                        $guestBirthdateEmailchatGptContent = DB::table("$Customer->database.settings")->where('type', 'guestBirthdateEmailchatGptContent'.$counterBirthdateEmail)->value('value');
                        $dataAfterAdd = date('m-d', strtotime("+$guestBirthdateEmail->value days", strtotime($today)));
                        foreach(DB::table("$Customer->database.users")->select('u_id', 'u_name', 'u_email', 'u_phone', DB::raw("DATE_FORMAT(birthdate, '%m-%d') as birthdate"))->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$dataAfterAdd])->get() as $user){
                            echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Email: $user->u_email, Mobile: $user->u_phone";
                            if(isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database, 'u_id' => $user->u_id, 'email' => $user->u_email, 'chatgpt_content' => $guestBirthdateEmailchatGptContent, 'by' => 'guestBirthdateEmailCron'.$counterBirthdateEmail, 'reason'=>"Birthday celebration for $user->u_name `$user->u_id` Birthday $user->birthdate.", 'created_at'=>$created_at]]); }else{echo " Not valid Email $user->u_email ";}
                        }
                        foreach(DB::table("$Customer->database"."_archive.users")->select('u_id', 'u_name', 'u_email', 'u_phone', DB::raw("DATE_FORMAT(birthdate, '%m-%d') as birthdate"))->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$dataAfterAdd])->get() as $user){
                            echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Email: $user->u_email, Mobile: $user->u_phone";
                            if(isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database."_archive", 'u_id' => $user->u_id, 'email' => $user->u_email, 'chatgpt_content' => $guestBirthdateEmailchatGptContent, 'by' => 'guestBirthdateEmailCron'.$counterBirthdateEmail, 'reason'=>"Birthday celebration for $user->u_name `$user->u_id` Birthday $user->birthdate.", 'created_at'=>$created_at]]); }else{echo " Not valid Email $user->u_email ";}
                        }
                    }
                }

                echo "<hr>";

                // check for guest birthdate Whatsapp 1, 2, 3
                for($counterBirthdateWhatsapp=1; $counterBirthdateWhatsapp<=3; $counterBirthdateWhatsapp++){
                    $guestBirthdateWhatsapp = DB::table("$Customer->database.settings")->where('type', 'guestBirthdateWhatsapp'.$counterBirthdateWhatsapp)->first();
                    if(isset( $guestBirthdateWhatsapp ) and $guestBirthdateWhatsapp->state == "1"){
                        echo '<br>guestBirthdateWhatsapp'.$counterBirthdateWhatsapp;
                        // Get all matching birthdates and add Whatsapp notification record
                        $guestBirthdateWhatsappchatGptContent = DB::table("$Customer->database.settings")->where('type', 'guestBirthdateWhatsappchatGptContent'.$counterBirthdateWhatsapp)->value('value');
                        $dataAfterAdd = date('m-d', strtotime("+$guestBirthdateWhatsapp->value days", strtotime($today)));
                        foreach(DB::table("$Customer->database.users")->select('u_id', 'u_name', 'u_phone', DB::raw("DATE_FORMAT(birthdate, '%m-%d') as birthdate"))->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$dataAfterAdd])->get() as $user){
                            echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                            if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $guestBirthdateWhatsappchatGptContent, 'by' => 'guestBirthdateWhatsappCron'.$counterBirthdateWhatsapp, 'reason'=>"Birthday celebration for $user->u_name `$user->u_id` Birthday $user->birthdate.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                        }
                        foreach(DB::table("$Customer->database"."_archive.users")->select('u_id', 'u_name', 'u_phone', DB::raw("DATE_FORMAT(birthdate, '%m-%d') as birthdate"))->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$dataAfterAdd])->get() as $user){
                            echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                            if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database."_archive", 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $guestBirthdateWhatsappchatGptContent, 'by' => 'guestBirthdateWhatsappCron'.$counterBirthdateWhatsapp, 'reason'=>"Birthday celebration for $user->u_name `$user->u_id` Birthday $user->birthdate.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                        }
                    }
                }

                echo "<hr>";

                // check for guest birthdate SMS 1, 2, 3
                for($counterBirthdateSMS=1; $counterBirthdateSMS<=3; $counterBirthdateSMS++){
                    $guestBirthdateSMS = DB::table("$Customer->database.settings")->where('type', 'guestBirthdateSMS'.$counterBirthdateSMS)->first();
                    if(isset( $guestBirthdateSMS ) and $guestBirthdateSMS->state == "1"){
                        echo '<br>guestBirthdateSMS'.$counterBirthdateSMS;
                        // Get all matching birthdates and add SMS notification record
                        $guestBirthdateSMSchatGptContent = DB::table("$Customer->database.settings")->where('type', 'guestBirthdateSMSchatGptContent'.$counterBirthdateSMS)->value('value');
                        $dataAfterAdd = date('m-d', strtotime("+$guestBirthdateSMS->value days", strtotime($today)));
                        foreach(DB::table("$Customer->database.users")->select('u_id', 'u_name', 'u_phone', DB::raw("DATE_FORMAT(birthdate, '%m-%d') as birthdate"))->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$dataAfterAdd])->get() as $user){
                            echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                            if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $guestBirthdateSMSchatGptContent, 'by' => 'guestBirthdateSMSCron'.$counterBirthdateSMS, 'reason'=>"Birthday celebration for $user->u_name `$user->u_id` Birthday $user->birthdate.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                        }
                        foreach(DB::table("$Customer->database"."_archive.users")->select('u_id', 'u_name', 'u_phone', DB::raw("DATE_FORMAT(birthdate, '%m-%d') as birthdate"))->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$dataAfterAdd])->get() as $user){
                            echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                            if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database."_archive", 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $guestBirthdateSMSchatGptContent, 'by' => 'guestBirthdateSMSCron'.$counterBirthdateSMS, 'reason'=>"Birthday celebration for $user->u_name `$user->u_id` Birthday $user->birthdate.", 'created_at'=>$created_at]]); }else{echo " Not valid Mobile $user->u_phone ";}
                        }
                    }
                }

                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";

            }

            // guestCheckin
            if(DB::table("$Customer->database.campaigns")->where('type', 'guestCheckin')->value( 'state') == "1"){
                // check for check-in welcome Email
                $guestCheckinEmail = DB::table("$Customer->database.settings")->where('type', 'guestCheckinEmail')->first();
                if(isset( $guestCheckinEmail ) and $guestCheckinEmail->state == "1"){
                    echo '<br>guestCheckinEmail';
                    foreach(DB::table("$Customer->database.users")->where('notes', 'like', '%checkIn: '.$today.'%')->get() as $user){
                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Email: $user->u_email, Mobile: $user->u_phone";
                        if(isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database, 'u_id' => $user->u_id, 'email' => $user->u_email, 'chatgpt_content' => $guestCheckinEmail->value, 'by' => 'guestCheckinEmailCron', 'reason'=>"Guest $user->u_name `$user->u_id` checked-in $today.", 'created_at'=>$created_at]]);  }else{echo " Not valid Email $user->u_email ";}
                    }
                }

                echo "<hr>";

                // check for check-in welcome Whatsapp
                $guestCheckinWhatsapp = DB::table("$Customer->database.settings")->where('type', 'guestCheckinWhatsapp')->first();
                if(isset( $guestCheckinWhatsapp ) and $guestCheckinWhatsapp->state == "1"){
                    echo '<br>guestCheckinWhatsapp';
                    foreach(DB::table("$Customer->database.users")->where('notes', 'like', '%checkIn: '.$today.'%')->get() as $user){
                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                        if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $guestCheckinWhatsapp->value, 'by' => 'guestCheckinWhatsappCron', 'reason'=>"Guest $user->u_name `$user->u_id` checked-in $today.", 'created_at'=>$created_at]]);  }else{echo " Not valid Mobile $user->u_phone ";}
                    }
                }

                echo "<hr>";

                // check for check-in welcome SMS
                $guestCheckinSMS = DB::table("$Customer->database.settings")->where('type', 'guestCheckinSMS')->first();
                if(isset( $guestCheckinSMS ) and $guestCheckinSMS->state == "1"){
                    echo '<br>guestCheckinSMS';
                    foreach(DB::table("$Customer->database.users")->where('notes', 'like', '%checkIn: '.$today.'%')->get() as $user){
                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Mobile: $user->u_phone";
                        if(isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $guestCheckinSMS->value, 'by' => 'guestCheckinSMSCron', 'reason'=>"Guest $user->u_name `$user->u_id` checked-in $today.", 'created_at'=>$created_at]]);  }else{echo " Not valid Mobile $user->u_phone ";}
                    }
                }

                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
            }

            // // check for check-out Email
            // $guestCheckoutEmail = DB::table("$Customer->database.settings")->where('type', 'guestCheckoutEmail')->first();
            // if(isset( $guestCheckoutEmail ) and $guestCheckoutEmail->state == "1"){

            // }
            
        }
    }

    // validate more than one email and return no of valid emails
    public function validateEmails($emails){
        $validCount = 0;
        $emails = explode(',', $emails);
        foreach($emails as $email) {
            $email = str_replace(" ","",$email);
            $validator = Validator::make(
                ['email' => $email],
                ['email' => 'required|email']
            );
            if ($validator->fails()) {
                continue;
            }
            $validCount++;
        }
        return $validCount;
    }

    // validate more than one mobile and return no of valid mobiles
    public function validateMobiles($mobiles){
        $validCount = 0;
        $mobiles = explode(',', $mobiles);
        foreach($mobiles as $mobile) {
            $mobile = str_replace(" ","",$mobile);
            $validator = Validator::make(
                ['mobile' => $mobile],
                ['mobile' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10']]
            );
            if ($validator->fails()) {
                continue;
            }
            $validCount++;
        }
        return $validCount;
    }    

}