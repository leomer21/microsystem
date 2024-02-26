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

// this function should run each minute to send the Animation program, generic weekly, monthly, annually emails, Whatsapp, SMSs
class CronScheduleWeeklyMonthlyAnnuallyNotifications extends Controller
{ 
    public function cronScheduleWeeklyMonthlyAnnuallyNotifications(Request $request)
    {   
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        echo "Today is: ".date("l")." $created_at <br>";
        // get current and archive database
        $allCustomers=DB::table('customers')->where('state','1')->groupBy('database')->get();
        foreach( $allCustomers as $Customer )
        {
            // check if we should send animationProgram for hotels
            if(DB::table("$Customer->database.campaigns")->where('type', 'animationProgram')->value( 'state') == "1"){

                echo '<br>sendAnimationProgramEmailReminder';
                // Get all matching animationProgram and add email notification record
                $sendPublicHolidayEmailReminderChatGptContent = DB::table("$Customer->database.settings")->where('type', 'sendPublicHolidayEmailReminder'.'chatGptContent')->value('value');
                // $notification_day = '2023'.date('-m-d', strtotime("+$sendPublicHolidayReminder->value days", strtotime($today)));
                $notification_day = date("l");
                // get check-in group
                $pms = DB::table($Customer->database.'.pms')->where('state', '1')->first();

                foreach(DB::table("$Customer->database.animation_program_schedule")->where( 'notification_day', 'like', '%'.$notification_day.'%' )->whereBetween('notification_time', [date("H:i:00"), date("H:i:59")] )->get() as $animation){

                    echo "<hr>Animation Program ($animation->notification_name) at $animation->notification_day $animation->notification_time";

                    // get users from current DB, then check if the notification AI or not
                    foreach(DB::table("$Customer->database.users")->where( 'group_id', $pms->internet_group )->get() as $user){
                        
                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Email: $user->u_email";
                        // insert email notification record into `scheduled_notifications` table if email valid
                        if(isset($animation->final_email_without_ai) and strlen($animation->final_email_without_ai) > 25 and isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database, 'u_id' => $user->u_id, 'email' => $user->u_email, 'final_message' => $animation->final_email_without_ai, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]);
                        }elseif(isset($animation->ai_email_content) and strlen($animation->ai_email_content) > 25 and isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database, 'u_id' => $user->u_id, 'email' => $user->u_email, 'chatgpt_content' => $animation->ai_email_content, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]); 
                        }else{
                            echo " Not valid Email $user->u_email ";
                        }

                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Whatsapp: $user->u_phone";
                        // insert Whatsapp notification record into `scheduled_notifications` table if phone valid
                        if(isset($animation->final_whatsapp_without_ai) and strlen($animation->final_whatsapp_without_ai) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'final_message' => $animation->final_whatsapp_without_ai, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]);
                        }elseif(isset($animation->ai_email_content) and strlen($animation->ai_email_content) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $animation->ai_whatsapp_content, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]); 
                        }else{
                            echo " Not valid Whatsapp $user->u_phone ";
                        }

                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, SMS: $user->u_phone";
                        // insert sms notification record into `scheduled_notifications` table if phone valid
                        if(isset($animation->final_sms_without_ai) and strlen($animation->final_sms_without_ai) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'final_message' => $animation->final_sms_without_ai, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]);
                        }elseif(isset($animation->ai_email_content) and strlen($animation->ai_email_content) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $animation->ai_sms_content, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]); 
                        }else{
                            echo " Not valid SMS $user->u_phone ";
                        }

                    }

                    echo "<hr>";

                    // get users from archive DB, then check if the notification AI or not
                    foreach(DB::table("$Customer->database"."_archive.users")->where( 'group_id', $pms->internet_group )->get() as $user){
                        
                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Email: $user->u_email";
                        // insert email notification record into `scheduled_notifications` table if email valid
                        if(isset($animation->final_email_without_ai) and strlen($animation->final_email_without_ai) > 25 and isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database, 'u_id' => $user->u_id, 'email' => $user->u_email, 'final_message' => $animation->final_email_without_ai, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]);
                        }elseif(isset($animation->ai_email_content) and strlen($animation->ai_email_content) > 25 and isset($user->u_email) and $this->validateEmails($user->u_email) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '1', 'database' => $Customer->database, 'u_id' => $user->u_id, 'email' => $user->u_email, 'chatgpt_content' => $animation->ai_email_content, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]); 
                        }else{
                            echo " Not valid Email $user->u_email ";
                        }

                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, Whatsapp: $user->u_phone";
                        // insert Whatsapp notification record into `scheduled_notifications` table if phone valid
                        if(isset($animation->final_whatsapp_without_ai) and strlen($animation->final_whatsapp_without_ai) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'final_message' => $animation->final_whatsapp_without_ai, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]);
                        }elseif(isset($animation->ai_whatsapp_content) and strlen($animation->ai_whatsapp_content) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '2', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $animation->ai_whatsapp_content, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]); 
                        }else{
                            echo " Not valid Whatsapp $user->u_phone ";
                        }

                        echo "<br>Database: $Customer->database, UserId: $user->u_id, Name: $user->u_name, SMS: $user->u_phone";
                        // insert sms notification record into `scheduled_notifications` table if phone valid
                        if(isset($animation->final_sms_without_ai) and strlen($animation->final_sms_without_ai) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'final_message' => $animation->final_sms_without_ai, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]);
                        }elseif(isset($animation->ai_sms_content) and strlen($animation->ai_sms_content) > 25 and isset($user->u_phone) and $this->validateMobiles($user->u_phone) >= '1' ){ 
                            DB::table("$Customer->database.scheduled_notifications")->insert([['state' => '1', 'type' => '3', 'database' => $Customer->database, 'u_id' => $user->u_id, 'mobile' => $user->u_phone, 'chatgpt_content' => $animation->ai_sms_content, 'by' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications', 'reason'=>"Animation program ($animation->notification_name) for $user->u_name `$user->u_id` at $animation->notification_day $animation->notification_time.", 'created_at'=>$created_at]]); 
                        }else{
                            echo " Not valid SMS $user->u_phone ";
                        }

                    }
                }
                 
                
                


                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
                echo "<hr>";
            }
            
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