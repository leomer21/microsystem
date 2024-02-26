<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Campaigns;
use App\Models\Survey;
use App\Models\CampaignStatistics;
use App\History;
use Illuminate\Http\Response;
use Input;
use DB;
use Validator;
use Auth;
use Carbon\Carbon;
use App;
use Identify;
use Image;
use Redirect;
use Facebook;
use Share;
use FacebookAccountKit;
use Mail;
use Session;
use Excel;

class CampaignController extends Controller
{
    public function Index()
    {
        $permissions = app('App\Http\Controllers\DashboardController')->permissions();
        if (Auth::user()->type == 1 && $permissions['cards'] == 1) {
            return view('back-end.campaign.index');
        } else {
            return view('errors.404');
        }
    }

    // Data table data view
    public function Json()
    {
        $campaigns = Campaigns::where('type', '!=', 'birthdaysCelebrationOfferUnique')->get();
        foreach ($campaigns as $key => $value) {
            $value->reach_count = CampaignStatistics::where('campaign_id', $value->id)->where('type','reach')->count();
            $value->users_count = App\Users::count();
            if($value->users_count == 0){$value->reach_percentage = 0;}
            else{$value->reach_percentage = round(($value->reach_count / $value->users_count) * 100, 1);}
            
            // if($value->type=='publicHolidays'){
            //     App\Settings::where('type', 'whatsappQRaskForName')->update(['value' => Input::get('whatsappQRaskForName')]);
            //     $value->publicHolidaysState = 1;
            // }

        }
        return array('aaData' => $campaigns);
    }
 
    public function Insert(Request $request)
    {
        // days selected of Day parting
        $dt = Carbon::now();
        $days = "";
        $days = $request['sun-day'] == 'on' ? 'sun,' : '';
        $days .= $request['mon-day'] == 'on' ? 'mon,' : '';
        $days .= $request['tue-day'] == 'on' ? 'tue,' : '';
        $days .= $request['wed-day'] == 'on' ? 'wed,' : '';
        $days .= $request['thu-day'] == 'on' ? 'thu,' : '';
        $days .= $request['fri-day'] == 'on' ? 'fri,' : '';
        $days .= $request['sat-day'] == 'on' ? 'sat,' : '';

        $open_profile = $request['open-profile'] == 'on' ? '1' : '0';

        $offer_sms = $request['offer-sms'] == 'on' ? '1' : '0';
        $offer_email = $request['offer-email'] == 'on' ? '1' : '0';

        $survey_type = $request['survey-type'] == 'on' ? '1' : '0';

        $start_end_date_state = $request['check-end-date'] == 'on' ? '1' : '0';

        $whatsapp_immediately = Input::get('whatsapp_immediately') == 'on' ? '1' : '0';
        $whatsapp_first_survey = Input::get('whatsapp_first_survey') == 'on' ? '1' : '0';
        $whatsapp = Input::get('whatsapp') == 'on' ? '1' : '0';
        if(Input::get('whatsapp_after_value') == "0" or Input::get('whatsapp_after_value') == "" or Input::get('whatsapp_after_menu') == "0"){
            $whatsapp_after = "0";
        }else{
            $whatsapp_after = Input::get('whatsapp_after_value').'/'.Input::get('whatsapp_after_menu');
        }
        if($whatsapp == "1"){$surveyState = 0;}else{$surveyState = 1;}
        
        if ($start_end_date_state == 0) {
            if ($request['start-date'] == Null || $request['start-date'] == "" || !$request['start-date'] || !isset($request['start-date'])) {
                $Start_date = Carbon::now();
                $End_date = Null;
            } else {
                $old_date_timestamp = strtotime($request['start-date']);
                $Start_date = date('Y-m-d', $old_date_timestamp);
                $End_date = Null;
            }

        } else {
            /*
            $split = explode('-', $request['start-and-end-date']);
            $Start_date = $split[0];
            $Start_date = strtotime($Start_date);
            $Start_date = date('Y-m-d', $Start_date);

            $End_date = $split[1];
            $End_date = strtotime($End_date);
            $End_date = date('Y-m-d', $End_date);
            */
            $start_date_timestamp = strtotime($request['start-date']);
            $Start_date = date('Y-m-d', $start_date_timestamp);

            $end_date_timestamp = strtotime($request['end-date']);
            $End_date = date('Y-m-d', $end_date_timestamp);
            
        }
        
        if (isset($request['offer-expire-date']) and $request['offer-expire-date']!="") {
            $old_date_timestamp = strtotime($request['offer-expire-date']);
            $Offer_expire_date = date('Y-m-d', $old_date_timestamp);
        } else {
            $Offer_expire_date = date('Y-m-d', strtotime("+12 months", strtotime(date('Y-m-d'))));
        }
        
        if (isset($request['networks'])) {
            $networks = implode(",", $request['networks']);
        } else {
            $networks = Null;
        }
        if (isset($request['branches'])) {
            $branches = implode(",", $request['branches']);
        } else {
            $branches = Null;
        }
        if (isset($request['groups'])) {
            $groups = implode(",", $request['groups']);
        } else {

            $groups = Null;
        }
        $social_offer = $request['social-offer'] == 'on' ? '1' : '0';
        $social_network = $request['social-network'] == 'on' ? '1' : '2';

        if ($request['type'] == "survey") {
            if ($survey_type == 1) {
                $survey_type = "poll";
            } else {
                $survey_type = "rating";
            }
            //Survey campaign insert
            $campaign_id = Campaigns::insertGetId(
                ['state' => $surveyState, 'campaign_name' => $request['campaign-name'], 'ad_name' => $request['ad-name'], 'description' => $request['ad-desc'], 'type' => $request['type'], 'open_profile' => $open_profile, 'startdate' => $Start_date, 'enddate' => $End_date, 'delay' => $request['time-delay'], 'url' => $request['url'], 'question' => $request['question'], 'day_parting' => $request['day-parting'] == 'on' ? '1' : '0', 'day_parting_start' => $request['day-parting-start'], 'day_parting_end' => $request['day-parting-end'], 'days' => $days, 'network_id' => $networks, 'branch_id' => $branches, 'group_id' => $groups, 'survey_type' => $survey_type
                , 'whatsapp' => $whatsapp
                , 'whatsapp_first_survey' => $whatsapp_first_survey
                , 'whatsapp_immediately' => $whatsapp_immediately
                , 'whatsapp_after' => $whatsapp_after
                , 'whatsapp_repeat_survey' => Input::get('whatsapp_repeat_survey')
                , 'offer_desc' => Input::get('offer-desc')
                , 'offer_limit' => Input::get('offer-limit')
                , 'offer_terms' => Input::get('offer-terms')
                ]
            );
            $options = explode(',', $request['options']);
            $pollOptionsArray[] ="";
            $pollOptionsCounter = 0;
            if(isset($options[1])){
                foreach ($options as $op) {
                    //Survey insert
                    $pollOptions[$pollOptionsCounter] = $op;
                    $pollOptionsArray[$pollOptionsCounter] = Survey::insertGetId(
                        ['campaign_id' => $campaign_id, 'options' => $op]
                    );
                    $pollOptionsCounter++;
                }
            }

            // insert all survey options
            $recordsCount = @count($request['WA_options']);
            for($i = 0; $i < $recordsCount; $i++)
            { 
                if(isset($request['WA_options'][$i])){$options=$request['WA_options'][$i];}else{$options="";}
                if(isset($request['is_reply'][$i])){$is_reply=$request['is_reply'][$i];}else{$is_reply="";}
                if(isset($request['reply_message'][$i])){$reply_message=$request['reply_message'][$i];}else{$reply_message="";}
                if(isset($request['is_reply_after_user_reply'][$i])){$is_reply_after_user_reply=$request['is_reply_after_user_reply'][$i];}else{$is_reply_after_user_reply="";}
                if(isset($request['reply_message_after_user_reply'][$i])){$reply_message_after_user_reply=$request['reply_message_after_user_reply'][$i];}else{$reply_message_after_user_reply="";}
                if(isset($request['is_offer'][$i])){$is_offer=$request['is_offer'][$i];}else{$is_offer="";}
                if(isset($request['send_user_reply_to_admin_wa'][$i])){$send_user_reply_to_admin_wa=$request['send_user_reply_to_admin_wa'][$i];}else{$send_user_reply_to_admin_wa="";}
                if(isset($request['next_survey_id'][$i])){$next_survey_id=$request['next_survey_id'][$i];}else{$next_survey_id="";}
                
                // insert or update options in database
                if($survey_type == "poll"){
                    App\Models\Survey::where('id', $pollOptionsArray[$i])->update([
                        'options' => $pollOptions[$i],
                        'is_reply' => $is_reply,
                        'reply_message' => $reply_message,
                        'is_reply_after_user_reply' => $is_reply_after_user_reply,
                        'reply_message_after_user_reply' => $reply_message_after_user_reply,
                        'is_offer' => $is_offer,
                        'send_user_reply_to_admin_wa' => $send_user_reply_to_admin_wa,
                        'next_campaign_id' => $next_survey_id,
                        'updated_at' => \Carbon\Carbon::now()
                    ]);
                }else {
                    App\Models\Survey::insert([
                        'campaign_id' => $campaign_id,
                        'options' => $options,
                        'is_reply' => $is_reply,
                        'reply_message' => $reply_message,
                        'is_reply_after_user_reply' => $is_reply_after_user_reply,
                        'reply_message_after_user_reply' => $reply_message_after_user_reply,
                        'is_offer' => $is_offer,
                        'send_user_reply_to_admin_wa' => $send_user_reply_to_admin_wa,
                        'next_campaign_id' => $next_survey_id,
                        'created_at' => \Carbon\Carbon::now()
                    ]);
                }
            }
            
           
            // check if we will send whatsapp_immediately to all users
            if($whatsapp_immediately == "1"){

                // initialize users array
                $usersArray = array();

                // get users in the targeted Networks
                // if (isset($networks) and $networks!=Null) {
                //     $network_split = explode(',', $networks);
                //     foreach ($network_split as $network_value) {
                //         // array_push($usersArray, App\Users::select('u_id')->where('network_id', $network_value)->get());
                //         $networkUsersIDs = App\Users::select('u_id')->where('network_id', $network_value)->get();
                //         foreach ($networkUsersIDs as $uID){
                //             $usersArray[]=$uID;
                //         }
                //     }
                // } 
                
                // get users in the targeted groups
                if (isset($groups) and $groups!=Null) {
                    $group_split = explode(',', $groups);
                    foreach ($group_split as $group_value) {
                        // array_push($usersArray, App\Users::select('u_id')->where('group_id', $group_value)->get());
                        $groupUsersIDs = App\Users::select('u_id')->where('group_id', $group_value)->get();
                        foreach ($groupUsersIDs as $uID){
                            $usersArray[]=$uID;
                        }
                    }
                } 
                
                // get users in the targeted Branches
                if (isset($branches) and $branches!=Null) {
                    $branch_split = explode(',', $branches);
                    foreach ($branch_split as $branch_value) {
                        // array_push($usersArray, App\Users::select('u_id')->where('branch_id', $branch_value)->get());
                        $branchUsersIDs = App\Users::select('u_id')->where('branch_id', $branch_value)->get();
                        foreach ($branchUsersIDs as $uID){
                            $usersArray[]=$uID;
                        }
                    }
                }
                
                // return $usersArray;
                if(isset($usersArray)){

                    // remove any duplicated u_id
                    $usersArray = array_unique($usersArray); 
                    // insert into whatsapp_campaign table to start sending immediately
                    foreach ($usersArray as $userID) {
                        // echo $userID['u_id']." ";
                        App\Models\WhatsappCampaign::insert(['state' => '0', 'user_id' => $userID['u_id'], 'campaign_id'=> $campaign_id, 'created_at'=> \Carbon\Carbon::now()]);
                    }
                }
            }

        } else {

            if (isset($request['custom-landing-page'])) {
                $url = 'http://' . $request['custom-landing-page'] . '/index.html';
            } else {
                $url = $request['url'];
            }

            // check for new loyality campaign variables
            if(isset($request['loyalty_visits'])){ $loyalty_visits = $request['loyalty_visits'];  }
            else{$loyalty_visits="";}

            if(isset($request['loyalty_method'])){ $loyalty_method = $request['loyalty_method'];  }
            else{$loyalty_method="";}

            if(isset($request['loyalty_offer'])){ $loyalty_offer = $request['loyalty_offer'];  }
            else{$loyalty_offer="";}

            if(isset($request['loyalty_exact_visit_count'])){ $loyalty_exact_visit_count = $request['loyalty_exact_visit_count'];  }
            else{$loyalty_exact_visit_count="";}

            // check for new Antiloss campaign variables
            if(isset($request['antiloss_minimum_visits_count'])){ $antiloss_minimum_visits_count = $request['antiloss_minimum_visits_count'];  }
            else{$antiloss_minimum_visits_count="";}

            if(isset($request['antiloss_last_visit_since'])){ $antiloss_last_visit_since = $request['antiloss_last_visit_since'];  }
            else{$antiloss_last_visit_since="";}
            
            if(isset($request['antiloss_send_time'])){ $antiloss_send_time = $request['antiloss_send_time'];  }
            else{$antiloss_send_time="";}

            // check of admin didnt filled this fields to fill it automatically
            if(isset($request['offer-limit']) and $request['offer-limit']!=""){
                $offer_limit4insert = $request['offer-limit'];
            }else{
                $offer_limit4insert = 100000;
            }
            
            if(isset($request['offer-terms']) and $request['offer-terms']!=""){
                $offer_terms4insert = $request['offer-terms'];
            }else{
                $offer_terms4insert = "     ";
            }
            
            // start insert
            $campaign_id = Campaigns::insertGetId(
                //Campaign insert without survey
                ['campaign_name' => $request['campaign-name'], 'ad_name' => $request['ad-name'], 'description' => $request['ad-desc'], 'type' => $request['type'], 'state' => '1', 'open_profile' => $open_profile, 'invite_friends' => $request['invite-friends'], 'startdate' => $Start_date, 'enddate' => $End_date, 'delay' => $request['time-delay'], 'url' => $url, 'ios_url' => $request['ios-url'], 'android_url' => $request['android-url'], 'video_url' => $request['video-url'], 'text' => $request['message'], 'offer_title' => $request['offer-title'], 'social_offer' => $social_offer, 'social_network' => $social_network, 'social_post_type' => $request['social-post-type'],'offer_desc' => $request['offer-desc'], 'offer_terms' => $offer_terms4insert, 'offer_limit' => $offer_limit4insert, 'offer_sendmail' => $offer_email, 'offer_sendsms' => $offer_sms, 'offer_email_message' => $request['offer-email-message'], 'offer_sms_message' => $request['offer-sms-message'], 'offer_expire_date' => $Offer_expire_date, 'day_parting' => $request['day-parting'] == 'on' ? '1' : '0', 'day_parting_start' => $request['day-parting-start'], 'day_parting_end' => $request['day-parting-end'], 'days' => $days, 'network_id' => $networks, 'branch_id' => $branches, 'group_id' => $groups, 'loyalty_visits'=> $loyalty_visits, 'loyalty_method'=> $loyalty_method, 'loyalty_offer'=> $loyalty_offer, 'loyalty_exact_visit_count'=> $loyalty_exact_visit_count, 'antiloss_minimum_visits_count'=> $antiloss_minimum_visits_count, 'antiloss_last_visit_since'=> $antiloss_last_visit_since, 'antiloss_send_time'=> $antiloss_send_time] 
            );

            //Upload Background Pic
            if ($request->hasFile('file')) {
                $file = Input::file('file');
                foreach ($file as $value) {
                    $name = date('Y-m-d-H:i:s') . "-" . $value->getClientOriginalName();
                    $value->move(public_path() . '/upload/campaigns/', $name);

                    //Campaigns slider insert
                    App\Media::insert(
                        ['campaign_id' => $campaign_id, 'file' => $name, 'type' => 'campaigns']
                    );
                }
            }
        }

        return redirect()->back();
    }
    // Calculate number of days in each month for a given year.
    public function getDays($year){

        $num_of_days = array();
        $total_month = 12;
        if($year == date('Y'))
            $total_month = date('m');
        else
            $total_month = 12;

        for($m=1; $m<=$total_month; $m++){
            $num_of_days[$m] = cal_days_in_month(CAL_GREGORIAN, $m, $year);
        }
        return $num_of_days;
    }
    // clicked edit
    public function get_campaign($id)
    {

        $campaigns = Campaigns::find($id);


        // $campaigns_start_date = $campaigns->startdate;
        // $campaigns_end_date = $campaigns->enddate;
        

        // $campaigns_views =  App\History::where(['operation' => 'campaigns_views', 'details' => $id])->get();
        // $counter = 0;
        // foreach ($campaigns_views as $key => $value) {
        //   $value->count = $counter++;
        // }

        // return $campaigns_views;

        // $campaigns_clicks =  App\History::where(['operation' => 'campaigns_clicks', 'details' => $id])->groupBy('add_date')->get();
        // foreach ($campaigns_clicks as $key => $value) {

        //   $value->count = App\History::when($value, function ($query) use ($value) {
        //             return $query->where('add_date', $value->add_date);
        //     })->count();
        // }
        // $campaigns_reach =  CampaignStatistics::where(['campaign_id' => $id, 'type' => 'reach'])->groupBy('created_at')->get();
        // foreach ($campaigns_reach as $key => $value) {

        //   $value->count = App\History::when($value, function ($query) use ($value) {
        //             return $query->where('add_date', $value->add_date);
        //     })->count();
        // }

        // $campaigns_months = App\History::where(['notes' => 'campaigns', 'details' => $id])->groupBy('add_date')->get();
        // $str = '';
        // $array = [];
        // $count = 0;
        // foreach ($campaigns_months as $key => $value) {
        //   $split = explode('-', $value->add_date);
        //   $month =  $split[0].'-'.$split[1];
        //   if($month == $str){
        //         break;
        //   }else{
        //     $value->count = $count++;
        //     $str = $month;
        //   }
        //   $array[] = $value;
        // }

        //$array = App\Models\CampaignsStatisticss::where(['details' => $id])->get();

        $campaigns_survey = Survey::where(['campaign_id' => $id , 'u_id' => Null])->get();
        
        return view('back-end.campaign.edit', ['campaigns' => $campaigns, 'id' => $id, /*'months' => $array, 'campaigns_views' => $campaigns_views, 'campaigns_clicks' => $campaigns_clicks, 'campaigns_reach' => $campaigns_reach,*/ 'campaigns_survey' => $campaigns_survey ,'days' => $this->getDays(date("Y"))]);
    }

    public function Update($id, Request $request)
    { 
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = date("Y-m-d H:i:s");
        
        $dt = Carbon::now();
        //Days selected of Day parting
        $days = "";
        $days = Input::get('sun-day') == 'on' ? 'sun,' : '';
        $days .= Input::get('mon-day') == 'on' ? 'mon,' : '';
        $days .= Input::get('tue-day') == 'on' ? 'tue,' : '';
        $days .= Input::get('wed-day') == 'on' ? 'wed,' : '';
        $days .= Input::get('thu-day') == 'on' ? 'thu,' : '';
        $days .= Input::get('fri-day') == 'on' ? 'fri,' : '';
        $days .= Input::get('sat-day') == 'on' ? 'sat,' : '';

        $open_profile = Input::get('open-profile') == 'on' ? '1' : '0';
        $start_end_date_state = Input::get('check-end-date') == 'on' ? '1' : '0';
        // whatsapp survey
        $whatsapp_immediately = Input::get('whatsapp_immediately') == 'on' ? '1' : '0';
        $whatsapp_first_survey = Input::get('whatsapp_first_survey') == 'on' ? '1' : '0';
        $whatsapp = Input::get('whatsapp') == 'on' ? '1' : '0';
        if(Input::get('whatsapp_after_value') == "0" or Input::get('whatsapp_after_value') == "" or Input::get('whatsapp_after_menu') == "0"){
            $whatsapp_after = "0";
        }else{
            $whatsapp_after = Input::get('whatsapp_after_value').'/'.Input::get('whatsapp_after_menu');
        }
        
        if ($start_end_date_state == 0) {
            $old_date_timestamp = strtotime($request['start-date']);
            $Start_date = date('Y-m-d', $old_date_timestamp);
            $End_date = Null;
        } else {

            /*
            $split = explode('-', $request['start-and-end-date']);

            $old_date_timestamp_one = strtotime($split[0]);
            $start = date("Y-m-d", $old_date_timestamp_one);

            $old_date_timestamp_tow = strtotime($split[1]);
            $end = date("Y-m-d", $old_date_timestamp_tow);

            $Start_date = $start;
            $End_date = $end;
            */
            $start_date_timestamp = strtotime($request['start-date']);
            $Start_date = date('Y-m-d', $start_date_timestamp);

            $end_date_timestamp = strtotime($request['end-date']);
            $End_date = date('Y-m-d', $end_date_timestamp);
        }

        if (Input::get('networks')) {
             $networks = implode(",", Input::get('networks'));
        } else {
            $networks = Null;
        }
        if (Input::get('branches')) {
             $branches = implode(",", Input::get('branches'));
        } else {
            $branches = Null;
        }
        if (Input::get('groups')) {
             $groups = implode(",", Input::get('groups'));
        } else {
            $groups = Null;
        }

        $offer_sms = Input::get('offer-sms') == 'on' ? '1' : '0';
        $offer_email = Input::get('offer-email') == 'on' ? '1' : '0';

        $survey_type = Input::get('survey-type') == 'on' ? '1' : '0';

        $social_offer = Input::get('social-offer') == 'on' ? '1' : '0';
        $social_network = Input::get('social-network') == 'on' ? '1' : '2';

        if (Input::get('type') == "survey") {
            
            // this function is disables to avoid any business mistake or any change in the result
            // if ($survey_type == 1) { $survey_type = "poll"; } 
            // else { $survey_type = "rating"; }

            // Campaigns::where('id', $id)->update(
            //     ['campaign_name' => Input::get('campaign-name'), 'ad_name' => Input::get('ad-name'), 'description' => Input::get('ad-desc'), 'type' => Input::get('type'), 'open_profile' => $open_profile, 'startdate' => $Start_date, 'enddate' => $End_date, 'delay' => Input::get('time-delay'), 'url' => Input::get('url'), 'question' => Input::get('question'), 'day_parting' => Input::get('day-parting') == 'on' ? '1' : '0', 'day_parting_start' => Input::get('day-parting-start'), 'day_parting_end' => Input::get('day-parting-end'), 'days' => $days, 'network_id' => $networks, 'branch_id' => $branches, 'group_id' => $groups]
            // );
            
            Campaigns::where('id', $id)->update(
                ['campaign_name' => Input::get('campaign-name'), 'ad_name' => Input::get('ad-name'), 'description' => Input::get('ad-desc'), 'type' => Input::get('type'), 'open_profile' => $open_profile, 'startdate' => $Start_date, 'enddate' => $End_date, 'delay' => Input::get('time-delay'), 'url' => Input::get('url'), 'question' => Input::get('question'), 'day_parting' => Input::get('day-parting') == 'on' ? '1' : '0', 'day_parting_start' => Input::get('day-parting-start'), 'day_parting_end' => Input::get('day-parting-end'), 'days' => $days, 'network_id' => $networks, 'branch_id' => $branches, 'group_id' => $groups
                , 'whatsapp' => $whatsapp
                , 'whatsapp_first_survey' => $whatsapp_first_survey
                , 'whatsapp_immediately' => $whatsapp_immediately
                , 'whatsapp_after' => $whatsapp_after
                , 'whatsapp_repeat_survey' => Input::get('whatsapp_repeat_survey')
                , 'offer_desc' => Input::get('offer-desc')
                , 'offer_limit' => Input::get('offer-limit')
                , 'offer_terms' => Input::get('offer-terms')
                ]
            );

            // delete all options then insert the updated options
            // this function is disables to avoid any business mistake or any change in the result
            // Survey::where('campaign_id', $id)->delete();
            // $options = explode(',', Input::get('options'));
            // foreach ($options as $op) {
            //     //Survey insert
            //     Survey::insert(
            //         ['campaign_id' => $id, 'options' => $op, 'created_at' => \Carbon\Carbon::now()]
            //     );
            // }
            
            // insert and update all survey options
            if(isset($request['option_id'])){
                $recordsCount = @count($request['option_id']);
                for($i = 0; $i < $recordsCount; $i++)
                {
                    if(isset($request['option_id'][$i])){$option_id=$request['option_id'][$i];}else{$option_id="";}
                    if(isset($request['options'][$i])){$options=$request['options'][$i];}else{$options="";}
                    if(isset($request['is_reply'][$i])){$is_reply=$request['is_reply'][$i];}else{$is_reply="";}
                    if(isset($request['reply_message'][$i])){$reply_message=$request['reply_message'][$i];}else{$reply_message="";}
                    if(isset($request['is_reply_after_user_reply'][$i])){$is_reply_after_user_reply=$request['is_reply_after_user_reply'][$i];}else{$is_reply_after_user_reply="";}
                    if(isset($request['reply_message_after_user_reply'][$i])){$reply_message_after_user_reply=$request['reply_message_after_user_reply'][$i];}else{$reply_message_after_user_reply="";}
                    if(isset($request['is_offer'][$i])){$is_offer=$request['is_offer'][$i];}else{$is_offer="";}
                    if(isset($request['send_user_reply_to_admin_wa'][$i])){$send_user_reply_to_admin_wa=$request['send_user_reply_to_admin_wa'][$i];}else{$send_user_reply_to_admin_wa="";}
                    if(isset($request['next_survey_id'][$i])){$next_survey_id=$request['next_survey_id'][$i];}else{$next_survey_id="";}
                    
                    // check if this survey option is exist 
                    $updateOrCreate = App\Models\Survey::where('id', $option_id)->first();
                    if(isset($updateOrCreate)){
                        App\Models\Survey::where('id', $option_id)->update([
                            'options' => $options,
                            'is_reply' => $is_reply,
                            'reply_message' => $reply_message,
                            'is_reply_after_user_reply' => $is_reply_after_user_reply,
                            'reply_message_after_user_reply' => $reply_message_after_user_reply,
                            'is_offer' => $is_offer,
                            'send_user_reply_to_admin_wa' => $send_user_reply_to_admin_wa,
                            'next_campaign_id' => $next_survey_id,
                            'updated_at' => \Carbon\Carbon::now()
                        ]);
                    }else {
                        App\Models\Survey::insert([
                            'campaign_id' => $id,
                            'options' => $options,
                            'is_reply' => $is_reply,
                            'reply_message' => $reply_message,
                            'is_reply_after_user_reply' => $is_reply_after_user_reply,
                            'reply_message_after_user_reply' => $reply_message_after_user_reply,
                            'is_offer' => $is_offer,
                            'send_user_reply_to_admin_wa' => $send_user_reply_to_admin_wa,
                            'next_campaign_id' => $next_survey_id,
                            'created_at' => \Carbon\Carbon::now()
                        ]);
                    }
                }
            }
            // check if we will send whatsapp_immediately to all users
            if($whatsapp_immediately == "1"){

                // initialize users array
                $usersArray = array();

                // get users in the targeted Networks
                // if (isset($networks) and $networks!=Null) {
                //     $network_split = explode(',', $networks);
                //     foreach ($network_split as $network_value) {
                //         // array_push($usersArray, App\Users::select('u_id')->where('network_id', $network_value)->get());
                //         $networkUsersIDs = App\Users::select('u_id')->where('network_id', $network_value)->get();
                //         foreach ($networkUsersIDs as $uID){
                //             $usersArray[]=$uID->u_id;
                //         }
                //     }
                // } 
                
                // get users in the targeted groups
                if (isset($groups) and $groups!=Null) {
                    $group_split = explode(',', $groups);
                    foreach ($group_split as $group_value) {
                        // array_push($usersArray, App\Users::select('u_id')->where('group_id', $group_value)->get());
                        $groupUsersIDs = App\Users::select('u_id')->where('group_id', $group_value)->get();
                        foreach ($groupUsersIDs as $uID){
                            $usersArray[]=$uID->u_id;
                        }
                    }
                }
                
                // get users in the targeted Branches
                if (isset($branches) and $branches!=Null) {
                    $branch_split = explode(',', $branches);
                    foreach ($branch_split as $branch_value) {
                        // array_push($usersArray, App\Users::select('u_id')->where('branch_id', $branch_value)->get());
                        $branchUsersIDs = App\Users::select('u_id')->where('branch_id', $branch_value)->get();
                        foreach ($branchUsersIDs as $uID){
                            $usersArray[]=$uID->u_id;
                        }
                    }
                }
                
                // return $usersArray;
                if(isset($usersArray)){

                    // remove any duplicated u_id
                    $usersArray = array_unique($usersArray); 
                    
                    // get all waiting records from whatsapp_campaign table to remove dublicated this Array
                    foreach (App\Models\WhatsappCampaign::where('campaign_id', $id)->get() as $lastRecords) {
                        if (($key = array_search($lastRecords->user_id, $usersArray)) !== false) {
                            unset($usersArray[$key]);
                        }
                    }
                    
                    // insert into whatsapp_campaign table to start sending immediately
                    foreach ($usersArray as $userID) {
                        App\Models\WhatsappCampaign::insert(['state' => '0', 'user_id' => $userID, 'campaign_id'=> $id, 'created_at'=> \Carbon\Carbon::now()]);
                    }
                }
            }

        }elseif (Input::get('type') == "whatsappFirstBot") {
            
            $whatsappReferralinviterIsPoints_state = Input::get('whatsappReferralinviterIsPoints_state') == 'on' ? '1' : '0';
            $whatsappReferralinviteeIsPoints_state = Input::get('whatsappReferralinviteeIsPoints_state') == 'on' ? '1' : '0';
            $whatsappReferralinviterIsOffer = Input::get('whatsappReferralinviterIsOffer') == 'on' ? '1' : '0';
            $whatsappReferralinviteeIsOffer = Input::get('whatsappReferralinviteeIsOffer') == 'on' ? '1' : '0';
            $whatsappPayFawryState = Input::get('whatsappPayFawryState') == 'on' ? '1' : '0';
            $whatsappPayVisaState = Input::get('whatsappPayVisaState') == 'on' ? '1' : '0';
            $whatsappPayWalletState = Input::get('whatsappPayWalletState') == 'on' ? '1' : '0';
            $birthdaysCelebrationOffer = Input::get('birthdaysCelebrationOffer') == 'on' ? '1' : '0';
            
            // update settings
            App\Settings::where('type', 'whatsappQRaskForName')->update(['value' => Input::get('whatsappQRaskForName')]);
            App\Settings::where('type', 'mainBotLoyaltyBendingOffersMsg')->update(['value' => Input::get('mainBotLoyaltyBendingOffersMsg')]);
            App\Settings::where('type', 'whatsappUserWrongResponse')->update(['value' => Input::get('whatsappUserWrongResponse')]);
            App\Settings::where('type', 'amountToLoyaltyPoints')->update(['value' => Input::get('amountToLoyaltyPoints')]);
            App\Settings::where('type', 'loyaltyPointsExpireAfterDays')->update(['value' => Input::get('loyaltyPointsExpireAfterDays')]);
            App\Settings::where('type', 'whatsappUserReceivePointsMsg')->update(['value' => Input::get('whatsappUserReceivePointsMsg')]);
            App\Settings::where('type', 'whatsappUserRefundPointsMsg')->update(['value' => Input::get('whatsappUserRefundPointsMsg')]);
            App\Settings::where('type', 'whatsappUserAskForLoyaltyProgram')->update(['value' => Input::get('whatsappUserAskForLoyaltyProgram')]);
            App\Settings::where('type', 'whatsappReferralinviterIsPoints')->update(['state' => $whatsappReferralinviterIsPoints_state]);
            App\Settings::where('type', 'whatsappReferralinviterIsPoints')->update(['value' => Input::get('whatsappReferralinviterIsPoints')]);
            App\Settings::where('type', 'whatsappReferralinviteeIsPoints')->update(['state' => $whatsappReferralinviteeIsPoints_state]);
            App\Settings::where('type', 'whatsappReferralinviteeIsPoints')->update(['value' => Input::get('whatsappReferralinviteeIsPoints')]);
            App\Settings::where('type', 'whatsappReferralinviterIsOffer')->update(['state' => $whatsappReferralinviterIsOffer]);
            App\Settings::where('type', 'whatsappReferralinviteeIsOffer')->update(['state' => $whatsappReferralinviteeIsOffer]);
            App\Settings::where('type', 'whatsappReferralInviterMsg')->update(['value' => Input::get('whatsappReferralInviterMsg')]);
            App\Settings::where('type', 'whatsappReferralInvitationForwardMsg')->update(['value' => Input::get('whatsappReferralInvitationForwardMsg')]);
            App\Settings::where('type', 'whatsappReferralInvitedAskBeforeInvitationMsg')->update(['value' => Input::get('whatsappReferralInvitedAskBeforeInvitationMsg')]);
            App\Settings::where('type', 'whatsappReferralInvitedAskInvitationSuccessMsg')->update(['value' => Input::get('whatsappReferralInvitedAskInvitationSuccessMsg')]);
            App\Settings::where('type', 'whatsappReferralInvitedAskInvitationFailMsg')->update(['value' => Input::get('whatsappReferralInvitedAskInvitationFailMsg')]);
            App\Settings::where('type', 'whatsappReferralInvitedAskAfterInvitationMsg')->update(['value' => Input::get('whatsappReferralInvitedAskAfterInvitationMsg')]);
            App\Settings::where('type', 'whatsappReferralInvitationOpenWiFi')->update(['value' => Input::get('whatsappReferralInvitationOpenWiFi')]);
            App\Settings::where('type', 'whatsappReferralInvitationOfferLimitExceeded')->update(['value' => Input::get('whatsappReferralInvitationOfferLimitExceeded')]);
            App\Settings::where('type', 'whatsappPayFawryState')->update(['state' => $whatsappPayFawryState]);
            App\Settings::where('type', 'whatsappPayVisaState')->update(['state' => $whatsappPayVisaState]);
            App\Settings::where('type', 'whatsappPayWalletState')->update(['state' => $whatsappPayWalletState]);
            App\Settings::where('type', 'birthdaysCelebrationOffer')->update(['state' => $birthdaysCelebrationOffer]);
            App\Settings::where('type', 'whatsappPayEnterAmountMsg')->update(['value' => Input::get('whatsappPayEnterAmountMsg')]);
            App\Settings::where('type', 'whatsappPayEnterTableNoMsg')->update(['value' => Input::get('whatsappPayEnterTableNoMsg')]);
            App\Settings::where('type', 'whatsappPayFinishMsg')->update(['value' => Input::get('whatsappPayFinishMsg')]);
            App\Settings::where('type', 'whatsappPayErrorMsg')->update(['value' => Input::get('whatsappPayErrorMsg')]);

            App\Settings::where('type', 'whatsappEnterBirthdateMsg')->update(['value' => Input::get('whatsappEnterBirthdateMsg')]);
            App\Settings::where('type', 'whatsappBirthdateSuccessMsg')->update(['value' => Input::get('whatsappBirthdateSuccessMsg')]);
            App\Settings::where('type', 'whatsappBirthdateFailMsg')->update(['value' => Input::get('whatsappBirthdateFailMsg')]);
            App\Settings::where('type', 'whatsappBirthdateAlreadyEnterdMsg')->update(['value' => Input::get('whatsappBirthdateAlreadyEnterdMsg')]);
            App\Settings::where('type', 'whatsappBirthdaySendOfferBeforeNoDays')->update(['value' => Input::get('whatsappBirthdaySendOfferBeforeNoDays')]);
            App\Settings::where('type', 'whatsappBirthdayMsg')->update(['value' => Input::get('whatsappBirthdayMsg')]);
            App\Settings::where('type', 'avoidWiFiWhenCallStaff')->update(['state' => Input::get('avoidWiFiWhenCallStaff')]);
            // update birthdaysCelebrationOfferUnique campaign
            Campaigns::where('type', 'birthdaysCelebrationOfferUnique')->update(
                [
                  'offer_desc' => Input::get('birthdate-offer-desc')
                , 'offer_terms' => Input::get('birthdate-offer-terms')
                , 'offer_limit' => Input::get('birthdate-offer-limit')
                ]
            );

            // update campaign 
            Campaigns::where('id', $id)->update(
                [
                  'question' => Input::get('question')
                , 'network_id' => $networks, 'branch_id' => $branches, 'group_id' => $groups
                , 'whatsapp_after' => $whatsapp_after
                , 'whatsapp_repeat_survey' => Input::get('whatsapp_repeat_survey')
                , 'offer_desc' => Input::get('offer-desc')
                , 'offer_limit' => Input::get('offer-limit')
                , 'offer_terms' => Input::get('offer-terms')
                ]
            );
            
            // insert and update all survey options
            if(isset($request['option_id'])){
                $recordsCount = @count($request['option_id']);
                for($i = 0; $i < $recordsCount; $i++)
                {   
                    if(isset($request['option_id'][$i])){$option_id=$request['option_id'][$i];}else{$option_id="";}
                    if(isset($request['options'][$i])){$options=$request['options'][$i];}else{$options="";}
                    if(isset($request['is_reply'][$i])){$is_reply=$request['is_reply'][$i];}else{$is_reply="";}
                    if(isset($request['reply_message'][$i])){$reply_message=$request['reply_message'][$i];}else{$reply_message="";}
                    if(isset($request['view_loyalty_program'][$i])){$view_loyalty_program=$request['view_loyalty_program'][$i];}else{$view_loyalty_program="";}
                    if(isset($request['call_staff'][$i])){$call_staff=$request['call_staff'][$i];}else{$call_staff="";}
                    if(isset($request['call_staff_success_msg'][$i])){$call_staff_success_msg=$request['call_staff_success_msg'][$i];}else{$call_staff_success_msg="";}
                    if(isset($request['login_to_wifi_msg'][$i])){$login_to_wifi_msg=$request['login_to_wifi_msg'][$i];}else{$login_to_wifi_msg="";}
                    if(isset($request['whatsapp_referral_inviter'][$i])){$whatsapp_referral_inviter=$request['whatsapp_referral_inviter'][$i];}else{$whatsapp_referral_inviter="";}
                    if(isset($request['whatsapp_referral_invitee'][$i])){$whatsapp_referral_invitee=$request['whatsapp_referral_invitee'][$i];}else{$whatsapp_referral_invitee="";}
                    if(isset($request['next_campaign_id'][$i])){$next_campaign_id=$request['next_campaign_id'][$i];}else{$next_campaign_id="";}
                    if(isset($request['whatsappPay'][$i])){$whatsappPay=$request['whatsappPay'][$i];}else{$whatsappPay="";}
                    
                    // check if this survey option is exist 
                    $updateOrCreate = App\Models\Survey::where('id', $option_id)->first();
                    if(isset($updateOrCreate)){
                        App\Models\Survey::where('id', $option_id)->update([
                            'options' => $options,
                            'is_reply' => $is_reply,
                            'reply_message' => $reply_message,
                            'view_loyalty_program' => $view_loyalty_program,
                            'call_staff' => $call_staff,
                            'call_staff_success_msg' => $call_staff_success_msg,
                            'login_to_wifi_msg' => $login_to_wifi_msg,
                            'whatsapp_referral_inviter' => $whatsapp_referral_inviter,
                            'whatsapp_referral_invitee' => $whatsapp_referral_invitee,
                            'next_campaign_id' => $next_campaign_id,
                            'whatsappPay' => $whatsappPay,
                            'updated_at' => \Carbon\Carbon::now()
                        ]);
                    }else {
                        App\Models\Survey::insert([
                            'campaign_id' => $id,
                            'options' => $options,
                            'is_reply' => $is_reply,
                            'reply_message' => $reply_message,
                            'view_loyalty_program' => $view_loyalty_program,
                            'call_staff' => $call_staff,
                            'call_staff_success_msg' => $call_staff_success_msg,
                            'login_to_wifi_msg' => $login_to_wifi_msg,
                            'whatsapp_referral_inviter' => $whatsapp_referral_inviter,
                            'whatsapp_referral_invitee' => $whatsapp_referral_invitee,
                            'next_campaign_id' => $next_campaign_id,
                            'whatsappPay' => $whatsappPay,
                            'created_at' => \Carbon\Carbon::now()
                        ]);
                    }
                    unset($options);
                    unset($is_reply);
                    unset($reply_message);
                    unset($view_loyalty_program);
                    unset($call_staff);
                    unset($call_staff_success_msg);
                    unset($login_to_wifi_msg);
                    unset($whatsapp_referral_inviter);
                    unset($whatsapp_referral_invitee);
                    unset($next_campaign_id);
                    unset($whatsappPay);
                }
            }
            // print_r($request['loyalty_program_item_id']);
            // return "";
            // insert and update all Loyalty programs
            if(isset($request['loyaltyProgram_id'])){
                $recordsCount = @count($request['loyaltyProgram_id']);
                for($i = 0; $i < $recordsCount; $i++)
                {   
                    if(isset($request['loyaltyProgram_id'][$i])){$loyaltyProgram_id=$request['loyaltyProgram_id'][$i];}else{$loyaltyProgram_id="";}
                    if(isset($request['loyalty_program_points'][$i])){$loyalty_program_points=$request['loyalty_program_points'][$i];}else{$loyalty_program_points="";}
                    if(isset($request['loyalty_program_type'][$i])){$loyalty_program_type=$request['loyalty_program_type'][$i];}else{$loyalty_program_type="";}
                    if(isset($request['loyalty_program_whatsapp'][$i])){$loyalty_program_whatsapp=$request['loyalty_program_whatsapp'][$i];}else{$loyalty_program_whatsapp="";}
                    if(isset($request['just_reached_whatsapp_msg'][$i])){$just_reached_whatsapp_msg=$request['just_reached_whatsapp_msg'][$i];}else{$just_reached_whatsapp_msg="";}
                    if(isset($request['discount_type'][$i])){$discount_type=$request['discount_type'][$i];}else{$discount_type="";}
                    if(isset($request['discount_value'][$i])){$discount_value=$request['discount_value'][$i];}else{$discount_value="";}
                    if(isset($request['depends_on_item_name'][$i])){$depends_on_item_name=$request['depends_on_item_name'][$i];}else{$depends_on_item_name="";}
                    if(isset($request['max_discount_amount'][$i])){$max_discount_amount=$request['max_discount_amount'][$i];}else{$max_discount_amount="";}
                    if(isset($request['depends_on_item_id'][$i])){$depends_on_item_id=$request['depends_on_item_id'][$i];}else{$depends_on_item_id="";}
                    // if(isset($request['loyalty_program_item_id'][$i])){$loyalty_program_item_id=$request['loyalty_program_item_id'][$i];}else{$loyalty_program_item_id="";}
                    
                    
                    // check if this loyalty program is exist
                    $updateOrCreate = App\Models\LoyaltyProgram::where('id', $loyaltyProgram_id)->first();
                    if(isset($updateOrCreate)){
                        App\Models\LoyaltyProgram::where('id', $loyaltyProgram_id)->update([
                            'campaign_id' => $id,
                            'row_type' => '1',
                            'points' => $loyalty_program_points,
                            'type' => $loyalty_program_type,
                            'whatsapp' => $loyalty_program_whatsapp,
                            'just_reached_whatsapp_msg' => $just_reached_whatsapp_msg,
                            'discount_type' => $discount_type,
                            'discount_value' => $discount_value,
                            'depends_on_item_name' => $depends_on_item_name,
                            'depends_on_item_id' => $depends_on_item_id,
                            'max_discount_amount' => $max_discount_amount,
                            'updated_at' => \Carbon\Carbon::now()
                        ]);
                    }else {
                        App\Models\LoyaltyProgram::insert([
                            'state' => '1',
                            'campaign_id' => $id,
                            'row_type' => '1',
                            'points' => $loyalty_program_points,
                            'type' => $loyalty_program_type,
                            'whatsapp' => $loyalty_program_whatsapp,
                            'just_reached_whatsapp_msg' => $just_reached_whatsapp_msg,
                            'discount_type' => $discount_type,
                            'discount_value' => $discount_value,
                            'depends_on_item_name' => $depends_on_item_name,
                            'depends_on_item_id' => $depends_on_item_id,
                            'max_discount_amount' => $max_discount_amount,
                            'created_at' => \Carbon\Carbon::now()
                        ]);
                    }
                    if(isset($request['loyalty_program_item_id'][$loyaltyProgram_id])){
                        // App\Models\LoyaltyProgramItems::where('loyalty_program_id',$loyaltyProgram_id)->delete();
                        foreach($request['loyalty_program_item_id'][$loyaltyProgram_id] as $key => $item){
                            // make shure admin select specific item not empty 
                            if(isset($item) and $item!=""){
                                // check if this item is added before or not
                                $updateOrCreateLoyalProgItem = App\Models\LoyaltyProgramItems::where('loyalty_program_id',$loyaltyProgram_id)->where('id',$key)->first();
                                if(isset($updateOrCreateLoyalProgItem)){
                                    App\Models\LoyaltyProgramItems::where('loyalty_program_id',$loyaltyProgram_id)->where('id',$key)->update([
                                        'item_id' => $item,
                                        'item_name' => App\Models\PosItems::where('pos_id',$item)->value('name'),
                                        'created_at' => \Carbon\Carbon::now()
                                    ]); 
                                }else{
                                    App\Models\LoyaltyProgramItems::insert([
                                        'loyalty_program_id' => $loyaltyProgram_id,
                                        'item_id' => $item,
                                        'item_name' => App\Models\PosItems::where('pos_id',$item)->value('name'),
                                        'created_at' => \Carbon\Carbon::now()
                                    ]);
                                }
                            }
                        }
                    }

                    unset($loyalty_program_points);
                    unset($loyalty_program_type);
                    unset($loyalty_program_whatsapp);
                    unset($just_reached_whatsapp_msg);
                    unset($discount_type);
                    unset($discount_value);
                    unset($depends_on_item_name);
                    unset($max_discount_amount);
                    unset($whatsappPay);
                    unset($depends_on_item_id);
                    // unset($loyalty_program_item_id);
                }
            }
            
        }elseif (Input::get('type') == "publicHolidays") {
            
            // prepare all switches
            $sendPublicHolidayEmailReminder1_state = Input::get('sendPublicHolidayEmailReminder1_state') == 'on' ? '1' : '0';
            $sendPublicHolidayEmailReminder2_state = Input::get('sendPublicHolidayEmailReminder2_state') == 'on' ? '1' : '0';
            $sendPublicHolidayEmailReminder3_state = Input::get('sendPublicHolidayEmailReminder3_state') == 'on' ? '1' : '0';
            $sendPublicHolidayWhatsappReminder1_state = Input::get('sendPublicHolidayWhatsappReminder1_state') == 'on' ? '1' : '0';
            $sendPublicHolidayWhatsappReminder2_state = Input::get('sendPublicHolidayWhatsappReminder2_state') == 'on' ? '1' : '0';
            $sendPublicHolidayWhatsappReminder3_state = Input::get('sendPublicHolidayWhatsappReminder3_state') == 'on' ? '1' : '0';
            $sendPublicHolidaySMSReminder1_state = Input::get('sendPublicHolidaySMSReminder1_state') == 'on' ? '1' : '0';
            $sendPublicHolidaySMSReminder2_state = Input::get('sendPublicHolidaySMSReminder2_state') == 'on' ? '1' : '0';
            $sendPublicHolidaySMSReminder3_state = Input::get('sendPublicHolidaySMSReminder3_state') == 'on' ? '1' : '0';
            
            // Update Email notification settings
            App\Settings::where('type', 'sendPublicHolidayEmailReminder1')->update(['state' => $sendPublicHolidayEmailReminder1_state ]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder1')->update(['value' => Input::get('sendPublicHolidayEmailReminder1_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder1chatGptContent')->update(['value' => Input::get('sendPublicHolidayEmailReminder1chatGptContent')]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder2')->update(['state' => $sendPublicHolidayEmailReminder2_state ]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder2')->update(['value' => Input::get('sendPublicHolidayEmailReminder2_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder2chatGptContent')->update(['value' => Input::get('sendPublicHolidayEmailReminder2chatGptContent')]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder3')->update(['state' => $sendPublicHolidayEmailReminder3_state ]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder3')->update(['value' => Input::get('sendPublicHolidayEmailReminder3_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidayEmailReminder3chatGptContent')->update(['value' => Input::get('sendPublicHolidayEmailReminder3chatGptContent')]);

            // Update Whatsapp notification settings
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder1')->update(['state' => $sendPublicHolidayWhatsappReminder1_state ]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder1')->update(['value' => Input::get('sendPublicHolidayWhatsappReminder1_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder1chatGptContent')->update(['value' => Input::get('sendPublicHolidayWhatsappReminder1chatGptContent')]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder2')->update(['state' => $sendPublicHolidayWhatsappReminder2_state ]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder2')->update(['value' => Input::get('sendPublicHolidayWhatsappReminder2_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder2chatGptContent')->update(['value' => Input::get('sendPublicHolidayWhatsappReminder2chatGptContent')]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder3')->update(['state' => $sendPublicHolidayWhatsappReminder3_state ]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder3')->update(['value' => Input::get('sendPublicHolidayWhatsappReminder3_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidayWhatsappReminder3chatGptContent')->update(['value' => Input::get('sendPublicHolidayWhatsappReminder3chatGptContent')]);
            
            // Update SMS notification settings
            App\Settings::where('type', 'sendPublicHolidaySMSReminder1')->update(['state' => $sendPublicHolidaySMSReminder1_state ]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder1')->update(['value' => Input::get('sendPublicHolidaySMSReminder1_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder1chatGptContent')->update(['value' => Input::get('sendPublicHolidaySMSReminder1chatGptContent')]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder2')->update(['state' => $sendPublicHolidaySMSReminder2_state ]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder2')->update(['value' => Input::get('sendPublicHolidaySMSReminder2_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder2chatGptContent')->update(['value' => Input::get('sendPublicHolidaySMSReminder2chatGptContent')]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder3')->update(['state' => $sendPublicHolidaySMSReminder3_state ]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder3')->update(['value' => Input::get('sendPublicHolidaySMSReminder3_beforeDays')]);
            App\Settings::where('type', 'sendPublicHolidaySMSReminder3chatGptContent')->update(['value' => Input::get('sendPublicHolidaySMSReminder3chatGptContent')]);

            // update campaign 
            Campaigns::where('type', 'publicHolidays')->update( [ 'updated_at' => $created_at ] );

            // Upload public holidays excel sheet
            if ($request->hasFile('publicHolidaysExcel')) {

                $file = Input::file('publicHolidaysExcel');
                foreach ($file as $value) {
                    @unlink(public_path() . '/upload/PublicHolidays.xlsx'); // Delete existing file
                    $value->move(public_path() . '/upload/', 'PublicHolidays.xlsx'); // Add new excel file
                }
                
                // delete all `public_holidays` table content
                $split = explode('/', url()->full());
                $customerData = DB::table('customers')->where('url',$split[2])->first();
                $tableName = $customerData->database.'.public_holidays';
                DB::statement( 'TRUNCATE TABLE '.$tableName.';');

                // Import data from the uploaded file
                Excel::load(public_path().'/upload/PublicHolidays.xlsx', function($reader) {
                    $reader->each(function($sheet) {
                        if(isset($sheet->name)){   
                            $newHoliday = new App\Models\PublicHolidays();
                            $newHoliday->date = $sheet->date;
                            $newHoliday->country_name = $sheet->country_name;
                            $newHoliday->country_code = $sheet->country_code;
                            $newHoliday->name = $sheet->name;
                            $newHoliday->save();
                        }
                    });
                });
    
            }

        }elseif (Input::get('type') == "guestBirthdate") {
            
            // prepare all switches
            $guestBirthdateEmail1_state = Input::get('guestBirthdateEmail1_state') == 'on' ? '1' : '0';
            $guestBirthdateEmail2_state = Input::get('guestBirthdateEmail2_state') == 'on' ? '1' : '0';
            $guestBirthdateEmail3_state = Input::get('guestBirthdateEmail3_state') == 'on' ? '1' : '0';
            $guestBirthdateWhatsapp1_state = Input::get('guestBirthdateWhatsapp1_state') == 'on' ? '1' : '0';
            $guestBirthdateWhatsapp2_state = Input::get('guestBirthdateWhatsapp2_state') == 'on' ? '1' : '0';
            $guestBirthdateWhatsapp3_state = Input::get('guestBirthdateWhatsapp3_state') == 'on' ? '1' : '0';
            $guestBirthdateSMS1_state = Input::get('guestBirthdateSMS1_state') == 'on' ? '1' : '0';
            $guestBirthdateSMS2_state = Input::get('guestBirthdateSMS2_state') == 'on' ? '1' : '0';
            $guestBirthdateSMS3_state = Input::get('guestBirthdateSMS3_state') == 'on' ? '1' : '0';

            // Update Email notification settings
            App\Settings::where('type', 'guestBirthdateEmail1')->update(['state' => $guestBirthdateEmail1_state ]);
            App\Settings::where('type', 'guestBirthdateEmail1')->update(['value' => Input::get('guestBirthdateEmail1_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateEmailchatGptContent1')->update(['value' => Input::get('guestBirthdateEmailchatGptContent1')]);
            App\Settings::where('type', 'guestBirthdateEmail2')->update(['state' => $guestBirthdateEmail2_state ]);
            App\Settings::where('type', 'guestBirthdateEmail2')->update(['value' => Input::get('guestBirthdateEmail2_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateEmailchatGptContent2')->update(['value' => Input::get('guestBirthdateEmailchatGptContent2')]);
            App\Settings::where('type', 'guestBirthdateEmail3')->update(['state' => $guestBirthdateEmail3_state ]);
            App\Settings::where('type', 'guestBirthdateEmail3')->update(['value' => Input::get('guestBirthdateEmail3_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateEmailchatGptContent3')->update(['value' => Input::get('guestBirthdateEmailchatGptContent3')]);

            // Update Whatsapp notification settings
            App\Settings::where('type', 'guestBirthdateWhatsapp1')->update(['state' => $guestBirthdateWhatsapp1_state ]);
            App\Settings::where('type', 'guestBirthdateWhatsapp1')->update(['value' => Input::get('guestBirthdateWhatsapp1_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateWhatsappchatGptContent1')->update(['value' => Input::get('guestBirthdateWhatsappchatGptContent1')]);
            App\Settings::where('type', 'guestBirthdateWhatsapp2')->update(['state' => $guestBirthdateWhatsapp2_state ]);
            App\Settings::where('type', 'guestBirthdateWhatsapp2')->update(['value' => Input::get('guestBirthdateWhatsapp2_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateWhatsappchatGptContent2')->update(['value' => Input::get('guestBirthdateWhatsappchatGptContent2')]);
            App\Settings::where('type', 'guestBirthdateWhatsapp3')->update(['state' => $guestBirthdateWhatsapp3_state ]);
            App\Settings::where('type', 'guestBirthdateWhatsapp3')->update(['value' => Input::get('guestBirthdateWhatsapp3_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateWhatsappchatGptContent3')->update(['value' => Input::get('guestBirthdateWhatsappchatGptContent3')]);
            
            // Update SMS notification settings
            App\Settings::where('type', 'guestBirthdateSMS1')->update(['state' => $guestBirthdateSMS1_state ]);
            App\Settings::where('type', 'guestBirthdateSMS1')->update(['value' => Input::get('guestBirthdateSMS1_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateSMSchatGptContent1')->update(['value' => Input::get('guestBirthdateSMSchatGptContent1')]);
            App\Settings::where('type', 'guestBirthdateSMS2')->update(['state' => $guestBirthdateSMS2_state ]);
            App\Settings::where('type', 'guestBirthdateSMS2')->update(['value' => Input::get('guestBirthdateSMS2_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateSMSchatGptContent2')->update(['value' => Input::get('guestBirthdateSMSchatGptContent2')]);
            App\Settings::where('type', 'guestBirthdateSMS3')->update(['state' => $guestBirthdateSMS3_state ]);
            App\Settings::where('type', 'guestBirthdateSMS3')->update(['value' => Input::get('guestBirthdateSMS3_beforeDays')]);
            App\Settings::where('type', 'guestBirthdateSMSchatGptContent3')->update(['value' => Input::get('guestBirthdateSMSchatGptContent3')]);

            // update campaign 
            Campaigns::where('type', 'guestBirthdate')->update( [ 'updated_at' => $created_at ] );


        }elseif (Input::get('type') == "guestCheckin") {
            
            // prepare all switches
            $guestCheckinEmail_state = Input::get('guestCheckinEmail_state') == 'on' ? '1' : '0';
            $guestCheckinWhatsapp_state = Input::get('guestCheckinWhatsapp_state') == 'on' ? '1' : '0';
            $guestCheckinSMS_state = Input::get('guestCheckinSMS_state') == 'on' ? '1' : '0';

            // Update Email notification settings
            App\Settings::where('type', 'guestCheckinEmail')->update(['state' => $guestCheckinEmail_state ]);
            App\Settings::where('type', 'guestCheckinEmail')->update(['value' => Input::get('guestCheckinEmail')]);

            // Update Whatsapp notification settings
            App\Settings::where('type', 'guestCheckinWhatsapp')->update(['state' => $guestCheckinWhatsapp_state ]);
            App\Settings::where('type', 'guestCheckinWhatsapp')->update(['value' => Input::get('guestCheckinWhatsapp')]);
            
            // Update SMS notification settings
            App\Settings::where('type', 'guestCheckinSMS')->update(['state' => $guestCheckinSMS_state ]);
            App\Settings::where('type', 'guestCheckinSMS')->update(['value' => Input::get('guestCheckinSMS')]);

            // update campaign 
            Campaigns::where('type', 'guestCheckin')->update( [ 'updated_at' => $created_at ] );


        }elseif (Input::get('type') == "guestCheckout") {
            
            // prepare all switches
            $guestCheckoutEmail_state = Input::get('guestCheckoutEmail_state') == 'on' ? '1' : '0';
            $guestCheckoutWhatsapp_state = Input::get('guestCheckoutWhatsapp_state') == 'on' ? '1' : '0';
            $guestCheckoutSMS_state = Input::get('guestCheckoutSMS_state') == 'on' ? '1' : '0';

            // Update Email notification settings
            App\Settings::where('type', 'guestCheckoutEmail')->update(['state' => $guestCheckoutEmail_state ]);
            App\Settings::where('type', 'guestCheckoutEmail')->update(['value' => Input::get('guestCheckoutEmail')]);

            // Update Whatsapp notification settings
            App\Settings::where('type', 'guestCheckoutWhatsapp')->update(['state' => $guestCheckoutWhatsapp_state ]);
            App\Settings::where('type', 'guestCheckoutWhatsapp')->update(['value' => Input::get('guestCheckoutWhatsapp')]);
            
            // Update SMS notification settings
            App\Settings::where('type', 'guestCheckoutSMS')->update(['state' => $guestCheckoutSMS_state ]);
            App\Settings::where('type', 'guestCheckoutSMS')->update(['value' => Input::get('guestCheckoutSMS')]);

            // update campaign 
            Campaigns::where('type', 'guestCheckout')->update( [ 'updated_at' => $created_at ] );


        }elseif (Input::get('type') == "animationProgram") {
            
            // update campaign 
            Campaigns::where('type', 'animationProgram')->update( [ 'updated_at' => $created_at ] );

            // Upload public holidays excel sheet
            if ($request->hasFile('AnimationProgramScheduleExcel')) {
                
                $file = Input::file('AnimationProgramScheduleExcel');
                foreach ($file as $value) {
                    @unlink(public_path() . '/upload/AnimationProgramSchedule.xlsx'); // Delete existing file
                    $value->move(public_path() . '/upload/', 'AnimationProgramSchedule.xlsx'); // Add new excel file
                }
                
                // delete all `public_holidays` table content
                $split = explode('/', url()->full());
                $customerData = DB::table('customers')->where('url',$split[2])->first();
                $tableName = $customerData->database.'.animation_program_schedule';
                DB::statement( 'TRUNCATE TABLE '.$tableName.';');
                
                // Import data from the uploaded file
                Excel::load(public_path().'/upload/AnimationProgramSchedule.xlsx', function($reader) {
                    $reader->each(function($sheet) {
                        if(isset($sheet->notification_day)){   
                            $newAnimationProgram = new App\Models\AnimationProgramSchedule();
                            $newAnimationProgram->notification_day = $sheet->notification_day;
                            $newAnimationProgram->notification_time = $sheet->notification_time;
                            $newAnimationProgram->notification_name = $sheet->notification_name;
                            $newAnimationProgram->ai_email_content = $sheet->ai_email_content;
                            $newAnimationProgram->ai_whatsapp_content = $sheet->ai_whatsapp_content;
                            $newAnimationProgram->ai_sms_content = $sheet->ai_sms_content;
                            $newAnimationProgram->final_email_without_ai = $sheet->final_email_without_ai;
                            $newAnimationProgram->final_whatsapp_without_ai = $sheet->final_whatsapp_without_ai;
                            $newAnimationProgram->final_sms_without_ai = $sheet->final_sms_without_ai;
                            $newAnimationProgram->save();
                        }
                    });
                });
    
            }

        } else {
            if (Input::get('custom-landing-page')) {
                $url = 'http://' . Input::get('custom-landing-page') . '/index.html';
            } else {
                $url = Input::get('url');
            }

            // check for new loyality campaign variables
            if(Input::get('loyalty_visits')){ $loyalty_visits = Input::get('loyalty_visits');  }
            else{$loyalty_visits="";}

            if(Input::get('loyalty_method')){ $loyalty_method = Input::get('loyalty_method');  }
            else{$loyalty_method="";}

            if(Input::get('loyalty_offer')){ $loyalty_offer = Input::get('loyalty_offer');  }
            else{$loyalty_offer="";}

            if(Input::get('loyalty_exact_visit_count')){ $loyalty_exact_visit_count = Input::get('loyalty_exact_visit_count');  }
            else{$loyalty_exact_visit_count="";}
            
            // check for new Antiloss campaign variables
            if(Input::get('antiloss_minimum_visits_count')){ $antiloss_minimum_visits_count = Input::get('antiloss_minimum_visits_count');  }
            else{$antiloss_minimum_visits_count="";}

            if(Input::get('antiloss_last_visit_since')){ $antiloss_last_visit_since = Input::get('antiloss_last_visit_since');  }
            else{$antiloss_last_visit_since="";}

            if(Input::get('antiloss_send_time')){ $antiloss_send_time = Input::get('antiloss_send_time');  }
            else{$antiloss_send_time="";}

            // set offer-expire-date format 
            if (Input::get('offer-expire-date') !== null) {
                $old_date_timestamp = strtotime(Input::get('offer-expire-date'));
                $Offer_expire_date = date('Y-m-d', $old_date_timestamp);
            } else {
                $Offer_expire_date = "";
            }
            // return Input::get('day-parting-start');
            // start update
            Campaigns::where('id', $id)->update(
            
                ['campaign_name' => Input::get('campaign-name'), 'ad_name' => Input::get('ad-name'), 'description' => Input::get('ad-desc'), 'type' => Input::get('type'), 'open_profile' => $open_profile, 'delay' => Input::get('time-delay'), 'startdate' => $Start_date, 'enddate' => $End_date, 'url' => $url, 'ios_url' => Input::get('ios-url'), 'android_url' => Input::get('android-url'), 'video_url' => Input::get('video-url'), 'text' => Input::get('message'), 'offer_title' => Input::get('offer-title'), 'offer_desc' => Input::get('offer-desc'), 'social_offer' => $social_offer, 'social_network' => $social_network, 'offer_terms' => Input::get('offer-terms'), 'offer_limit' => Input::get('offer-limit'), 'social_post_type' => Input::get('social-post-type'), 'offer_sendmail' => $offer_email, 'offer_sendsms' => $offer_sms, 'offer_email_message' => Input::get('offer-email-message'), 'offer_sms_message' => Input::get('offer-sms-message'),'offer_expire_date' => $Offer_expire_date, 'day_parting' => Input::get('day-parting') == 'on' ? '1' : '0', 'day_parting_start' => Input::get('day-parting-start'), 'day_parting_end' => Input::get('day-parting-end'), 'days' => $days, 'network_id' => $networks, 'branch_id' => $branches, 'group_id' => $groups, 'loyalty_visits'=> $loyalty_visits, 'loyalty_method'=> $loyalty_method, 'loyalty_offer'=> $loyalty_offer, 'loyalty_exact_visit_count'=> $loyalty_exact_visit_count, 'antiloss_minimum_visits_count'=> $antiloss_minimum_visits_count, 'antiloss_last_visit_since'=> $antiloss_last_visit_since, 'antiloss_send_time'=> $antiloss_send_time]  
            );

        }

        //Upload Background Pic
            if ($request->hasFile('file')) {
                $file = Input::file('file');
                App\Media::where(['campaign_id'=> $id, 'type'=>'campaigns'])->delete();
                foreach ($file as $value) {
                        $name = date('Y-m-d-H:i:s') . "-" . $value->getClientOriginalName();
                        $value->move(public_path() . '/upload/campaigns/', $name);
                        App\Media::insert(
                            ['campaign_id' => $id, 'file' => $name, 'type' => 'campaigns']
                        );
                        
                }

            }
        return redirect()->back();

    }

    // delete survey option from small button in monitoring page in campaign
    public function surveyOptionDelete($id){
        return App\Models\Survey::where('id', $id)->delete();
    }

    // delete survey loyalty program from small button in landbot page in campaign
    public function loyaltyProgramDelete($id){
        return App\Models\LoyaltyProgram::where('id', $id)->delete();
    }

    // delete survey loyalty program from small button in landbot page in campaign
    public function loyaltyProgramItemDelete($id){
        return App\Models\LoyaltyProgramItems::where('id', $id)->delete();
        // return "1";
    }

    public function Delete($id)
    {
        Campaigns::where('id', $id)->delete();
        App\Media::where('campaign_id', $id, 'type', 'campaigns')->delete();
    }

    public function delete_survey_option($id)
    {
        Survey::where('id', $id)->delete();
        return redirect()->back();
    }

    public function poll($id)
    {   
        //get campaign data
        $campaign = App\Models\Campaigns::where('id', $id)->first();
        if($campaign->survey_type=="poll"){
            return view('back-end.campaign.poll', ['poll' => Survey::where('campaign_id', $id)->whereNull('u_id')->get()]);
        }else{//rating
           return view('back-end.campaign.poll', ['rating' => 'rating', 'campaignID'=>$id]); 
        }
    }

    // second step in login journey
    public function campaign_offline(Request $request)
    {
        //////////////////  First step 1 : find how is campaign will apply ///////////////////
        $campaign = App\Models\Campaigns::where('state', '1')->orderBy('id', 'desc')->get();
        
        if (isset($campaign) && count($campaign) != 0) {
            date_default_timezone_set("Africa/Cairo");

            foreach ($campaign as $curr_campaign) {
                
                if( $curr_campaign->type == "website" or $curr_campaign->type == "offer" or $curr_campaign->type == "video" or $curr_campaign->type == "apps" or $curr_campaign->type == "social" or $curr_campaign->type == "survey" ){

                    $start_date_campaign = $curr_campaign->startdate;
                    $end_date_campaign = $curr_campaign->enddate;

                    $datetimenow = \Carbon\Carbon::now()->format('Y-m-d');
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
                        //return "DEBUG: go to step 2";
                        if (isset($curr_campaign->day_parting) && $curr_campaign->day_parting == 1) {
                            $start_parting_time = $curr_campaign->day_parting_start;
                            $end_parting_time = $curr_campaign->day_parting_end;

                            $todayName = date('l');

                            if (isset($curr_campaign->days) && $curr_campaign->days != "") {
                                $days = explode(',', $curr_campaign->days);
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
                                                    //$activeCampaignID = $curr_campaign->id;
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
                            //$activeCampaignID = $curr_campaign->id;
                        }

                        // step 3
                        // check if user in selected Group, Branch, Network
                        if (isset($passedStep2) and $passedStep2 == 1) {
                            
                            $user_data = App\Users::where('u_id', session('login')[0]['id'])->first();
                            if(!isset($user_data)){
                                return view('front-end.landing.index', ['errorMessage' => "Oops, missing user or session data 001, please login again."]); // trying to identify why screen refresh in login page 7/6/2022
                                return redirect()->back();
                            }
                            if (isset($curr_campaign->network_id)) {
                                $network_split = explode(',', $curr_campaign->network_id);
                                foreach ($network_split as $network_value) {
                                    if ($network_value == $user_data->network_id) {
                                        $found_network = 1;
                                    }
                                }
                            } else {
                                $found_network = 1;
                            }
                            if (isset($curr_campaign->group_id)) {
                                $group_split = explode(',', $curr_campaign->group_id);
                                foreach ($group_split as $group_value) {
                                    if ($group_value == $user_data->group_id) {
                                        $found_group = 1;
                                    }
                                }
                            } else {
                                $found_group = 1;
                            }
                            if (isset($curr_campaign->branch_id)) {
                                $branch_split = explode(',', $curr_campaign->branch_id);
                                foreach ($branch_split as $branch_value) {
                                    if ($branch_value == $user_data->branch_id) {
                                        $found_branch = 1;
                                    }
                                }
                            } else {
                                $found_branch = 1;
                            }

                            if (isset($found_network) && isset($found_branch) && isset($found_group)) {
                                $activeCampaignID = $curr_campaign->id;
                            }
                        }
                    
                    if (isset($activeCampaignID)) {//  found valid campaign right now.
                        break;
                    }

                    }// end step 1  if (isset($passedStep1) && $passedStep1 == 1)
                }  
            }//endforeach
        }//if(isset($campaign) && count($campaign) != 0)

        /////////////////////////  Final Step ///////////////////////
        if (!isset($activeCampaignID)) {// not found valid campaign right now.
            return redirect()->route('account');
        } else {// founded campaign ID
            $campaign = App\Models\Campaigns::where('id', $activeCampaignID)->first();
            $dt = Carbon::now();
            $id = session('login')[0]['id'];
            Campaigns::where('id', $activeCampaignID)->increment('views_count');
            
            History::insert(
                ['type1' => 'hotspot', 'type2' => 'Admin', 'operation' => 'campaigns_views', 'details' => $activeCampaignID, 'notes' => 'campaigns', 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
            );
            
            $check_reach = CampaignStatistics::where(['u_id' =>  $id, 'campaign_id' => $activeCampaignID , 'type' => 'reach'])->first();
            $check_offer = CampaignStatistics::where(['u_id' =>  $id, 'campaign_id' => $activeCampaignID , 'type' => 'offer'])->first();
            $offer_counter=App\Models\CampaignStatistics::where(['campaign_id' => $activeCampaignID, 'type' => 'offer'])->count();
            $survey_count=App\Models\Survey::where(['u_id' =>  $id, 'campaign_id' => $activeCampaignID])->count();

            if (isset($check_reach) && $check_reach->u_id == $id && $check_reach->campaign_id == $activeCampaignID) {
                // user already seen before 
                CampaignStatistics::insert(['campaign_id' => $activeCampaignID, 'u_id' => $id, 'type' => 'reach','created_at' => \Carbon\Carbon::now()]);
            } else {
                // this is the first time to view this survey
                CampaignStatistics::insert(['campaign_id' => $activeCampaignID, 'u_id' => $id, 'type' => 'reach','created_at' => \Carbon\Carbon::now()]);
            }
 
            // check if campaign type offer and offer limit has been reached 
            if($campaign->type == "offer" && $offer_counter >= $campaign->offer_limit){
                return redirect()->route('account');
            }

            // check if user take offer before
            if ($campaign->type == "offer" && count($check_offer) > 0) { 
                    // so user did not have offer record
                    return redirect()->route('account');
            }else{$canLogin=1;}
            
            //check if user fill survey before
            if ($campaign->type == "survey" && $survey_count > 0) { 
                    // so user did not have offer record
                    return redirect()->route('account');
            }else{$canLogin=1;}

            if(isset ($canLogin) and $canLogin==1){

                // type : social offer
                if ($campaign->type == "offer" && $campaign->social_offer == 1) {
                    // check if user click close on share post page on facebook
                    if(strpos($_SERVER['REQUEST_URI'], 'finalpage') !== false) {
                        // so user clicked on close
                        return Redirect::to(url('campaign_offline'));
                    }else{
                        // check if user take offer before or not
                        // not working but I well leave it for last check  
                        if ((isset($request->campaign) && $request->campaign == $activeCampaignID) && (isset($request->u_id) && $request->u_id = $id)) {
                            return view('back-end.campaign.preview', ['campaign' => $campaign, 'userid' => $id, 'share'=>'100']);  
                        }else{   
                            return view('back-end.campaign.social', ['campaign' => $campaign, 'userid' => $id]);    
                        }
                    }
                // type : landing   
                } elseif ($campaign->type == "landing") {
                    return Redirect::to($campaign->url);
                // type : other    
                } else {
                    // Redirect user to submit SMS and EMail forum (preview page 4 offer campaign)
                    //return $request->campaign;
                
                    if ($request->campaign == $activeCampaignID && $request->u_id = $id) {
                        return view('back-end.campaign.preview', ['campaign' => $campaign, 'userid' => $id]);
                    } else {
                        return view('back-end.campaign.preview', ['campaign' => $campaign, 'userid' => $id]);
                    }
                    //return redirect()->back();
                }
            
            }// End else if user already taked any type "ofer" or "survey"

            //if user didnt reach ti any thing in above conditions, will redirected to user panel
            return redirect()->route('account');
        }

    }

    // change campaign state from red & green button in campaigns page
    public function state($id, $value)
    {
        $value = ($value == 'true') ? 1 : 0;
        Campaigns::where('id', '=', $id)->update(['state' => $value]);
    }

    // change Whatsapp campaign state from red & green button in campaigns page
    public function whatsappState($id, $value)
    {
        $value = ($value == 'true') ? 1 : 0;
        Campaigns::where('id', '=', $id)->update(['whatsapp' => $value]);
    }

    //return view('back-end.campaign.preview', ['campaign' =>  $campaign]);


    public function preview($id)
    {
        if (isset($id)) {
            $campaign = App\Models\Campaigns::where('id', $id)->first();

            if ($campaign->type == "landing") {
                return Redirect::to($campaign->url);
            }
            return view('back-end.campaign.preview', ['campaign' => $campaign]);
        } else {
            return view('erorrs.404');
        }
    }

    public function campaign_click($id, $userid, $type = Null)
    {
        $dt = Carbon::now();
        Campaigns::where('id', $id)->increment('clicks_count');
        History::insert(
            ['type1' => 'hotspot', 'type2' => 'Admin', 'operation' => 'campaigns_clicks', 'details' => $id, 'notes' => 'campaigns','add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
        );
        $user = App\Users::where('u_id', $userid)->first();

        $check_reach = CampaignStatistics::where(['u_id' =>  $userid, 'campaign_id' => $id , 'type' => 'reach'])->first();

        $tokens = rand(1, 9999) . chr(rand(65, 90)) . rand(1111, 5555) . chr(rand(65, 90)) . rand(2222, 6666) . chr(rand(65, 90)) . rand(3333, 7777) . rand(4444, 8888) . rand(5555, 9999) . chr(rand(65, 90));

        if (App\Users::where('u_id', $userid)->update(['token' => $tokens])) {
            $username = $user->username;
            //print "<iframe src=\"http://internet.microsystem.com.eg/login?username=$username&password=$tokens\" ></iframe>";
            //sleep(10);
        }

        if ($check_reach->u_id == $userid && $check_reach->campaign_id == $id) {
            // user already seen before
        } else {
            CampaignStatistics::insert(['campaign_id' => $id, 'u_id' => $userid, 'type' => 'reach','created_at' => \Carbon\Carbon::now()]);
        }
        $campaign = Campaigns::where('id', $id)->first();
        if(isset($campaign->url)) {
            Session::push('campaign_url', $campaign->url);
        }

        if ($type == "account") {
            return redirect()->route('account');
        }else if ($type == "ios") {
            Session::push('ios_url', $campaign->ios_url);
            return redirect()->route('account');

        }else if ($type == "android") {
            Session::push('android_url', $campaign->android_url);
            return redirect()->route('account');
        } else {
            return redirect()->route('account');
        }


    }

    public function survey_vote(Request $request)
    {   
        date_default_timezone_set("Africa/Cairo");
        $dt = Carbon::now();
        $url = Campaigns::where('id', $request->campaign)->value('url');
        Campaigns::where('id', $request->campaign)->increment('clicks_count');
        History::insert(
            ['type1' => 'hotspot', 'type2' => 'Admin', 'operation' => 'campaigns_clicks', 'details' => $request->campaign,  'notes' => 'campaigns', 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
        );
        $check_reach = CampaignStatistics::where(['u_id' =>  $request->userid, 'campaign_id' => $request->campaign , 'type' => 'reach'])->first();

        if ($check_reach->u_id == $request->userid && $check_reach->campaign_id == $request->campaign) {
            // user already seen before
        } else {
            CampaignStatistics::insert(['campaign_id' => $request->campaign, 'u_id' => $request->userid,'type' => 'reach' ,'created_at' => \Carbon\Carbon::now()]);
        }
        if($request->type == "poll") {
            if (App\Models\Survey::where(['u_id' => $request->userid])->value('u_id') == $request->userid) {
                if (isset($url)) {
                    Session::push('campaign_url', $url);
                    return redirect()->route('account');
                } else {
                    return redirect()->route('account');
                }
            } else {
                App\Models\Survey::insert([
                    'options' => $request->option, 'campaign_id' => $request->campaign, 'u_id' => $request->userid, 'created_at' => \Carbon\Carbon::now()
                ]);

                History::insert(
                    ['type1' => 'hotspot', 'type2' => $request->option, 'operation' => 'campaigns_survey_poll', 'details' => $request->campaign, 'u_id' => $request->userid, 'notes' => 'campaigns', 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                if (isset($url)) {
                   Session::push('campaign_url', $url);
                    return redirect()->route('account');
                } else {
                    return redirect()->route('account');
                }

            }
        }else{
            // check if user filled this survey before
            if (App\Models\Survey::where(['u_id' => $request->userid, 'campaign_id' => $request->campaign])->count() > 0) {
                if (isset($url)) {
                    Session::push('campaign_url', $url);
                    return redirect()->route('account');
                } else {
                    return redirect()->route('account');
                }
            } else {
                // user will fill this survey now...
                App\Models\Survey::insert([
                    'options' => $request->rating, 'campaign_id' => $request->campaign, 'u_id' => $request->userid, 'created_at' => \Carbon\Carbon::now()
                ]);

                History::insert(
                    ['type1' => 'hotspot', 'type2' => $request->rating, 'operation' => 'campaigns_survey_rating', 'details' => $request->campaign, 'u_id' => $request->userid, 'notes' => 'campaigns', 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
                );
                if (isset($url)) {
                    Session::push('campaign_url', $url);
                    return redirect()->route('account');
                } else {
                    return redirect()->route('account');
                }

            }
        }
    }

    public function get_offer(Request $request)
    {
        $dt = Carbon::now();
        $user = App\Users::where('u_id', $request->userid)->first();
        $campaign = Campaigns::where('id', $request->campaignid)->first();
        Campaigns::where('id', $request->campaignid)->increment('clicks_count');
        History::insert(
            ['type1' => 'hotspot', 'type2' => 'Admin', 'operation' => 'campaigns_clicks', 'details' => $request->campaignid, 'notes' => 'campaigns', 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
        );
        $offer_code = rand(111111,999999);
        if(CampaignStatistics::where('offer_code', $offer_code)->count() > 0)
        {
            $offer_code = rand(121121,999999);
        }

        if( CampaignStatistics::where(['campaign_id' => $request->campaignid, 'u_id' => $request->userid,'type' => 'offer'])->count() > 0) {

        }else{
            CampaignStatistics::insert(['campaign_id' => $request->campaignid, 'u_id' => $request->userid,'type' => 'offer', 'offer_code' => $offer_code , 'state' => '0' ,'created_at' => \Carbon\Carbon::now()]);
        }

        if ($campaign->offer_sendsms == 1) {
            
            if (isset($request->phone) && count($user) != 0) {
                $message = $campaign->offer_sms_message.', Offer code:'.$offer_code;
                $sendmessage = new App\Http\Controllers\Integrations\SMS();
                $sendmessage->send($request->countrycode.$request->phone, $message);

                // send whatsapp with SMS 11/9/2019
                $split = explode('/', url()->full());
                $customerData = DB::table('customers')->where('url',$split[2])->first();
                $message = urlencode($message);
                $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                $sendWhatsappMessage->send( "", $request->countrycode.$request->phone , $message, $customerData->id, $customerData->database, "", "", "", "1");

            } else {
                return redirect()->back();
            }
        }
         
        if(isset($request->email))
        {
            // if (strpos($campaign->offer_sendmail, '@') !== false and strpos($campaign->offer_sendmail, '.') !== false) { // I thing this is not working 
            if (strpos($request->email, '@') !== false and strpos($request->email, '.') !== false) {
                $validEmail=1;
            }else{$validEmail=0;}

        }else{$validEmail=0;}

        if ($campaign->offer_sendmail == 1 and $validEmail==1) {
            
            if (isset($request->email) && count($user) != 0) {

                // update this email into user profile
                App\Users::where('u_id', '=', $request->userid)->update(['u_email' => $request->email]);
                
                if (App\Settings::where('type', 'email')->value('value')) {

                    $from = App\Settings::where('type', 'email')->value('value');
                } else {
                    $from = $request->email;
                }
                $to = $request->email;
                $subject = $campaign->campaign_name . ' Offer code';
                
                  Mail::send('emails.offer', ['title' => $subject, 'offer_code' => $offer_code, 'messageContent' => $campaign->offer_email_message, 'offerTitle' => $campaign->offer_title, 'offerDescription' => $campaign->offer_desc, 'userid' => $request->userid, 'campaign_id' => $request->campaignid, 'offer_terms' => $campaign->offer_terms], function ($message) use ($user,$to ,$from, $subject) {
                    $message->from($from, App\Settings::where('type', 'app_name')->value('value'));
                    $message->to($to, $user->u_name)->subject($subject);
                });
                
            } else {
                return redirect()->back();
            }
        }

        if(isset($campaign->url)){
            Session::push('campaign_url', $campaign->url);
            return redirect()->route('account');
            //return Redirect::to($campaign->url);
        }else{
            return redirect()->route('account');
        }

    }

    public function offers($id){
        $offers =  CampaignStatistics::where(['campaign_id' => $id, 'type' => 'offer'])->get();

        if(count($offers) != 0 ){
            return view('back-end.campaign.offers', ['offers' => $offers]);
        }else{
            return "<div class=\"alert alert-danger no-border\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span><span class=\"sr-only\">Close</span></button>
                    <span class=\"text-semibold\">Oops!</span> This campaign does not have any submitted offer</a>.
                </div>";
        }
    }

    public function customersReach($id){
        $customers_reach =  CampaignStatistics::where(['campaign_id' => $id, 'type' => 'reach'])->get();
        $campaignData = Campaigns::where('id', $id)->first();

        if(count($customers_reach) != 0 ){
            return view('back-end.campaign.customersReach', ['reach_value' => $customers_reach, 'campaign_type' => $campaignData->type, 'survey_type'=> $campaignData->survey_type ]);
        }else{
            return "<div class=\"alert alert-danger no-border\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span>&times;</span><span class=\"sr-only\">Close</span></button>
                    <span class=\"text-semibold\">Oops!</span> This campaign does not have any customer yet</a>.
                </div>";
        }
    }
    public function offer_state($id, $value){

        $value = ($value == 'true') ? 1 : 0;
       return CampaignStatistics::where('id', '=', $id)->update(['state' => $value, 'a_id' => Auth::user()->id]);
    }

    public function Specialoffer($id,$campaign,$offercode){

        return view('back-end.campaign.signupoffer', ['id' => $id, 'campaign' => $campaign]);
    }
    public function signupoffer(Request $request){

        $dt = Carbon::now();
        $referral_user =  CampaignStatistics::where(['u_id' => $request->user_id, 'campaign_id' => $request->campaign_id])->first();
        $campaign = Campaigns::where('id', $request->campaign_id)->first();
        Campaigns::where('id', $request->campaign_id)->increment('clicks_count');
        History::insert(
            ['type1' => 'hotspot', 'type2' => 'Admin', 'operation' => 'campaigns_clicks', 'details' => $request->campaign_id, 'notes' => 'campaigns', 'add_date' => $dt->toDateString(), 'add_time' => $dt->toTimeString()]
        );

        if(isset($referral_user) && count($referral_user) != 0) {
            $offer_code = rand(111111,999999);
            if(CampaignStatistics::where('offer_code', $offer_code)->count() > 0)
            {
                $offer_code = rand(121121,999999);
            }

            // $password = $request->password;
            $password = rand(11111111,999999999);
            if(strlen($request->mobile)==10){$mobile="0".$request->mobile;}else{$mobile=$request->mobile;}
            $phone = $request->countrycode . $mobile;
            // get networks 
            $networkID = App\Network::where('state','1')->value('id');
            $groupID = App\Groups::where('is_active','1')->orWhere('name','Default')->value('id');
            $BranchID = App\Branches::where('state','1')->value('id');
            $created_at = date("Y-m-d H:i:s");
            $insert = App\Users::insertGetId([
                'Registration_type' => '2', 'branch_id' => $BranchID, 'network_id' => $networkID, 'group_id' => $groupID, 'u_name' => $request->fullname, 'u_uname' => $phone, 'u_password' => $password, 'u_phone' => $phone, 'u_email' => $request->email, 'updated_at' => $created_at
            ]);
            CampaignStatistics::Insert([
                'u_id' => $insert, 'campaign_id' => $request->campaign_id, 'state' => '0', 'type' => 'offer', 'offer_code' => $offer_code, 'created_at' => \Carbon\Carbon::now(), 'referral_code' => $request->user_id
            ]);

            if ($campaign->offer_sendsms == 1) {

                if (isset($phone) && count($insert) != 0) {

                    $message = $campaign->offer_sms_message.', Offer code:'.$offer_code;
                    $sendmessage = new App\Http\Controllers\Integrations\SMS();
                    $sendmessage->send($phone, $message);
                    // send whatsapp with SMS 11/9/2019
                    $split = explode('/', url()->full());
                    $customerData = DB::table('customers')->where('url',$split[2])->first();
                    $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                    $message = urlencode($message);
                    $sendWhatsappMessage->send( "", $phone , $message, $customerData->id, $customerData->database, "", "", "", "1");

                    if(isset($campaign->url)){
                        //return Redirect::to($campaign->url);
                        Session::push('campaign_url', $campaign->url);
                        return redirect()->route('account');
                    }else{
                        return redirect()->route('account');
                    }
                } else {
                    return redirect()->back();
                }
            }


        if(isset($request->email))
        {
            if (strpos($campaign->offer_sendmail, '@') !== false and strpos($campaign->offer_sendmail, '.') !== false) {
                $validEmail=1;
            }else{$validEmail=0;}
            
        }else{$validEmail=0;}
            
            if ($campaign->offer_sendmail == 1 and $validEmail==1) {
                    
                if (isset($request->email) && count($insert) != 0) {

                    if (App\Settings::where('type', 'email')->value('value')) {

                        $from = App\Settings::where('type', 'email')->value('value');
                    } else {
                        $from = $request->email;
                    }
                    $to = $request->email;
                    $subject = $campaign->campaign_name . ' Offer code';

                    Mail::send('emails.offer', ['title' => $subject, 'offer_code' => $offer_code, 'message' => $campaign->offer_email_message, 'userid' => $request->userid], function ($message) use ($insert,$to, $from, $subject) {
                        $message->from($from, App\Settings::where('type', 'app_name')->value('value'));
                        $message->to($to, $insert->u_name)->subject($subject);
                    });
                    if(isset($campaign->url)){
                        //return Redirect::to($campaign->url);
                        Session::push('campaign_url', $campaign->url);
                        return redirect()->route('account');
                    }else{
                        return redirect()->route('account');
                    }
                } else {
                    return redirect()->back();
                }
            }

        }

    }


}