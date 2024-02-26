<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use DB;
use Input;
use Redirect;
use Auth;
use Session;


class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //include("agilecrm.php");
    }


    public function statisticsDateSetting()
    {
        // Get statistics setting for this admin
        $admin_id=Auth::user()->id;
        $today=date("Y-m-d");

        $value=App\Settings::where('type','statistics'.$admin_id)->value('value');
        if(isset($value)) {

            // check if Today
            if ($value == "Today") {
                $statisticsStartDate = $today;
                $statisticsEndDate= $today;
                $statisticsType="Today";
            }
            elseif($value == "Yesterday"){
                $yesterdayBeforeConvert = strtotime(date("$today", strtotime($today)) . " -1 day");
                $yesterday = date('Y-m-d', $yesterdayBeforeConvert);
                $statisticsStartDate=$yesterday;
                $statisticsEndDate=$yesterday;
                $statisticsType="Yesterday";
            }
            elseif($value == "Last 7 Days"){
                $last7DaysBeforeConvert = strtotime(date("$today", strtotime($today)) . " -6 day");
                $Last7Days = date('Y-m-d', $last7DaysBeforeConvert);
                $statisticsStartDate=$Last7Days;
                $statisticsEndDate=$today;
                $statisticsType="Last 7 Days";
            }
            elseif($value == "Last 30 Days"){
                $last30DaysBeforeConvert = strtotime(date("$today", strtotime($today)) . " -29 day");
                $Last30Days = date('Y-m-d', $last30DaysBeforeConvert);
                $statisticsStartDate=$Last30Days;
                $statisticsEndDate=$today;
                $statisticsType="Last 30 Days";
            }
            elseif($value == "This Month"){
                $firstDayThisMonth = date("Y-m") . "-01";
                $lastDayThisMonth = date('Y-m-t', strtotime($firstDayThisMonth));
                $statisticsStartDate=$firstDayThisMonth;
                $statisticsEndDate=$lastDayThisMonth;
                $statisticsType="This Month";

            }
            elseif($value == "Last Month"){
                $lastMonth = strtotime(date("$today", strtotime($today)) . " -1 month");
                $LastMonth = date('m', $lastMonth);
                $firstDayLastMonth = date("Y") . "-$LastMonth-01";
                $lastDayLastMonth = date('Y-m-t', strtotime($firstDayLastMonth));
                $statisticsStartDate=$firstDayLastMonth;
                $statisticsEndDate=$lastDayLastMonth;
                $statisticsType="Last Month";

            }
            elseif($value == "Whole period"){
                $lastMonth = strtotime(date("$today", strtotime($today)) . " -1 month");
                $LastMonth = date('m', $lastMonth);
                $firstDayLastMonth = date("Y") . "-$LastMonth-01";
                $lastDayLastMonth = date('Y-m-t', strtotime($firstDayLastMonth));
                $statisticsStartDate="2016-01-01";
                $statisticsEndDate=$today;
                $statisticsType="Whole period";

            }
            else{
                $split=explode(",",$value);
                $statisticsStartDate=$split[0];
                $statisticsEndDate=$split[1];
                $statisticsType="$statisticsStartDate  :  $statisticsEndDate";
            }

        }else{
            $firstDayThisMonth = date("Y-m") . "-01";
            $lastDayThisMonth = date('Y-m-t', strtotime($firstDayThisMonth));
            $statisticsStartDate=$firstDayThisMonth;
            $statisticsEndDate=$lastDayThisMonth;
            $statisticsType="$statisticsStartDate  :  $statisticsEndDate";
        }

        return ['statisticsStartDate' => $statisticsStartDate,'statisticsEndDate' => $statisticsEndDate,'statisticsType' => $statisticsType];

    }
    public function index()
    {
        // get agile count from agile database
        //$result = curl_wrap("contacts", null, "GET", "application/json");
        //$agile_count = json_decode($result, false, 512, JSON_BIGINT_AS_STRING);
        //return view('back-end.dashboard.home',['agile_count' => $agile_count]);
        // get agile count from system

        // $dashboardType = App\Settings::where('type','dashboard_type')->get()[0]->value;
        // echo $dashboardType;
        // die;
        $agile_count=App\Users::whereNotNull('agilecrm_id')->where('agilecrm_id','!=','')->count();
        $statistics=app('App\Http\Controllers\DashboardController')->statisticsDateSetting();
        $statisticsStartDate=$statistics['statisticsStartDate'];
        $statisticsEndDate=$statistics['statisticsEndDate'];
        $statisticsType=$statistics['statisticsType'];
        return view('back-end.dashboard.home',['agile_count' => $agile_count,'statisticsStartDate' => $statisticsStartDate,'statisticsEndDate' => $statisticsEndDate,'statisticsType' => $statisticsType]);
    }

    public function dashboard_ajax(){
        $agile_count=App\Users::whereNotNull('agilecrm_id')->where('agilecrm_id','!=','')->count();
        $statistics=app('App\Http\Controllers\DashboardController')->statisticsDateSetting();
        $statisticsStartDate=$statistics['statisticsStartDate'];
        $statisticsEndDate=$statistics['statisticsEndDate'];
        $statisticsType=$statistics['statisticsType'];
        return view('back-end.dashboard.ajax',['agile_count' => $agile_count,'statisticsStartDate' => $statisticsStartDate,'statisticsEndDate' => $statisticsEndDate,'statisticsType' => $statisticsType]);

    }

    /**
     * @return \Illuminate\View\View
     */
    public function notFound()
    {
        return view('errors.404');
    }

    public function deleteusers($id){
        $delete = App\Users::where('u_id',$id)->first();
        $delete->delete();
        return Redirect::back();
    }
    public function confirmusers($id){
        $update = App\Users::find($id);
        $update->branch_id = Input::get('branches');
        $update->group_id = Input::get('groups');
        $update->Registration_type = 2;
        $update->u_state = 1;
        $update->suspend = 0;
        $update->update(); 

        return Redirect::back();
    }
    public function details($id){

        $message = App\Messages::where('u_id', $id)->get();
        $message_count = App\Messages::where('u_id', $id)->count();
        $getMasterID = App\Messages::where('admin_id', Null)->where('u_id', $id)->orderBy('id', 'desc')->first();
        
        //return view('back-end.dashboard.message', compact('message'), compact('message_count'), compact('getMasterID'));
        return view('back-end.dashboard.message', array('message' => $message ,'message_count' => $message_count ,'getMasterID' => $getMasterID));
        
    }

    public function delete($id){
        $delete = App\Messages::where('id',$id)->first();
        $delete->delete();
        return redirect()->route('dashboard');
    }
    public function reply(Request $request){
        $reply = new App\Messages();
        $reply->admin_id = $request['admin'];
        $reply->message = Input::get('message');
        $reply->u_id = Input::get('user');
        $reply->name = Input::get('name');
        $reply->state = 1;
        $reply->parent_id = Input::get('parent');
        $reply->save();

        App\Messages::where('u_id', Input::get('user'))->update(
            ['state' => 1]
        );
        return redirect()->route('dashboard');
    } 
    public function ignore($id){
        $ignore = App\Messages::find($id);
        $ignore->state = 1;
        $ignore->update();
        return redirect()->route('dashboard');
    }
    public function visitors(){
        // get rush Hours of portal visitors
        // New Way 4 time rediction
        date_default_timezone_set("Africa/Cairo");
        $Last7Days = date('Y-m-d', strtotime(date(date('Y-m-d'), strtotime(date('Y-m-d'))) . " -7 day"));
        $statisticsStartDate = $Last7Days." 23:59:59";; // Last7Days
		$statisticsEndDate = date('Y-m-d')." 00:00:00"; // Today
        $rushHours = App\Models\Visitors::whereBetween('created_at',[ $statisticsStartDate,$statisticsEndDate])->select(DB::raw(' hour( created_at ) as hour, DATE_FORMAT(`created_at`,"01/%m/%y %H:00") as date, count(*) as value '))->groupBy(DB::raw('hour(created_at)'))->get();
        return $rushHours;

        /*
        // when select specific data you will update the following query
        $statistics=app('App\Http\Controllers\DashboardController')->statisticsDateSetting();
        $statisticsStartDate=$statistics['statisticsStartDate'];
        $statisticsEndDate=$statistics['statisticsEndDate'];

        $firstDayCurrentMonth=$statisticsStartDate." 00:00:00";
        $lastDayCurrentMonth=$statisticsEndDate." 23:59:59";
        $visitors_json = App\Models\Visitors::whereBetween('created_at',[$firstDayCurrentMonth, $lastDayCurrentMonth])->get();

        $hr0=0;$hr1=0;$hr2=0;$hr3=0;$hr4=0;$hr5=0;$hr6=0;$hr7=0;$hr8=0;$hr9=0;$hr10=0;$hr11=0;$hr12=0;$hr13=0;$hr14=0;$hr15=0; $hr16=0;$hr17=0;$hr18=0;$hr19=0;$hr20=0;$hr21=0;$hr22=0;$hr23=0;

        foreach($visitors_json as $visitorsData){
            $visitorsTime = $visitorsData->created_at;
            $visitorsTime=explode(" ",$visitorsTime);
            if(date($visitorsTime[1])>=date("00:00:00") && date($visitorsTime[1])<=date("01:00:00")){$hr0++;}
            if(date($visitorsTime[1])>date("01:00:00") && date($visitorsTime[1])<=date("02:00:00")){$hr1++;}
            if(date($visitorsTime[1])>date("02:00:00") && date($visitorsTime[1])<=date("03:00:00")){$hr2++;}
            if(date($visitorsTime[1])>date("03:00:00") && date($visitorsTime[1])<=date("04:00:00")){$hr3++;}
            if(date($visitorsTime[1])>date("04:00:00") && date($visitorsTime[1])<=date("05:00:00")){$hr4++;}
            if(date($visitorsTime[1])>date("05:00:00") && date($visitorsTime[1])<=date("06:00:00")){$hr5++;}
            if(date($visitorsTime[1])>date("06:00:00") && date($visitorsTime[1])<=date("07:00:00")){$hr6++;}
            if(date($visitorsTime[1])>date("07:00:00") && date($visitorsTime[1])<=date("08:00:00")){$hr7++;}
            if(date($visitorsTime[1])>date("08:00:00") && date($visitorsTime[1])<=date("09:00:00")){$hr8++;}
            if(date($visitorsTime[1])>date("09:00:00") && date($visitorsTime[1])<=date("10:00:00")){$hr9++;}
            if(date($visitorsTime[1])>date("10:00:00") && date($visitorsTime[1])<=date("11:00:00")){$hr10++;}
            if(date($visitorsTime[1])>date("11:00:00") && date($visitorsTime[1])<=date("12:00:00")){$hr11++;}
            if(date($visitorsTime[1])>date("12:00:00") && date($visitorsTime[1])<=date("13:00:00")){$hr12++;}
            if(date($visitorsTime[1])>date("13:00:00") && date($visitorsTime[1])<=date("14:00:00")){$hr13++;}
            if(date($visitorsTime[1])>date("14:00:00") && date($visitorsTime[1])<=date("15:00:00")){$hr14++;}
            if(date($visitorsTime[1])>date("15:00:00") && date($visitorsTime[1])<=date("16:00:00")){$hr15++;}
            if(date($visitorsTime[1])>date("16:00:00") && date($visitorsTime[1])<=date("17:00:00")){$hr16++;}
            if(date($visitorsTime[1])>date("17:00:00") && date($visitorsTime[1])<=date("18:00:00")){$hr17++;}
            if(date($visitorsTime[1])>date("18:00:00") && date($visitorsTime[1])<=date("19:00:00")){$hr18++;}
            if(date($visitorsTime[1])>date("19:00:00") && date($visitorsTime[1])<=date("20:00:00")){$hr19++;}
            if(date($visitorsTime[1])>date("20:00:00") && date($visitorsTime[1])<=date("21:00:00")){$hr20++;}
            if(date($visitorsTime[1])>date("21:00:00") && date($visitorsTime[1])<=date("22:00:00")){$hr21++;}
            if(date($visitorsTime[1])>date("22:00:00") && date($visitorsTime[1])<=date("23:00:00")){$hr22++;}
            if(date($visitorsTime[1])>date("23:00:00") && date($visitorsTime[1])<=date("00:00:00")){$hr23++;}

        }
        $visitorsData=array();
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr0, 'date' => date("d/m/y")." 00:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr1, 'date' => date("d/m/y")." 01:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr2, 'date' => date("d/m/y")." 02:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr3, 'date' => date("d/m/y")." 03:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr4, 'date' => date("d/m/y")." 04:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr5, 'date' => date("d/m/y")." 05:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr6, 'date' => date("d/m/y")." 06:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr7, 'date' => date("d/m/y")." 07:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr8, 'date' => date("d/m/y")." 08:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr9, 'date' => date("d/m/y")." 09:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr10, 'date' => date("d/m/y")." 10:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr11, 'date' => date("d/m/y")." 11:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr12, 'date' => date("d/m/y")." 12:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr13, 'date' => date("d/m/y")." 13:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr14, 'date' => date("d/m/y")." 14:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr15, 'date' => date("d/m/y")." 15:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr16, 'date' => date("d/m/y")." 16:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr17, 'date' => date("d/m/y")." 17:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr18, 'date' => date("d/m/y")." 18:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr19, 'date' => date("d/m/y")." 19:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr20, 'date' => date("d/m/y")." 20:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr21, 'date' => date("d/m/y")." 21:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr22, 'date' => date("d/m/y")." 22:00"];
        $visitorsData[] = ['key' => "Visitors", 'value' => $hr23, 'date' => date("d/m/y")." 23:00"];


        return json_encode($visitorsData);
        */
    }
    public function online(){
        // get rush Hours of online users
        // New Way 4 time rediction
        date_default_timezone_set("Africa/Cairo");
        $Last7Days = date('Y-m-d', strtotime(date(date('Y-m-d'), strtotime(date('Y-m-d'))) . " -7 day"));
        $statisticsStartDate = $Last7Days." 23:59:59";; // Last7Days
		$statisticsEndDate = date('Y-m-d')." 00:00:00"; // Today
        $rushHours = App\Radacct::whereBetween('acctstarttime',[ $statisticsStartDate,$statisticsEndDate])->select(DB::raw(' hour( acctstarttime ) as hour, DATE_FORMAT(`acctstarttime`,"01/%m/%y %H:00") as date, count(*) as value '))->groupBy(DB::raw('hour(acctstarttime)'))->get();
        return $rushHours;

        /*
        // when select specific data you will update the following query
        $statistics=app('App\Http\Controllers\DashboardController')->statisticsDateSetting();
        $statisticsStartDate=$statistics['statisticsStartDate'];
        $statisticsEndDate=$statistics['statisticsEndDate'];


        //$firstDayCurrentMonth=date("Y-m");
        //$lastDayCurrentMonth=date('Y-m-t', strtotime($firstDayCurrentMonth));
        $online_json = App\Models\UsersRadacct::whereBetween('dates',[ $statisticsStartDate,$statisticsEndDate])->get();

        $hr0=0;$hr1=0;$hr2=0;$hr3=0;$hr4=0;$hr5=0;$hr6=0;$hr7=0;$hr8=0;$hr9=0;$hr10=0;$hr11=0;$hr12=0;$hr13=0;$hr14=0;$hr15=0; $hr16=0;$hr17=0;$hr18=0;$hr19=0;$hr20=0;$hr21=0;$hr22=0;$hr23=0;

        foreach($online_json as $onlineData){
            $onlineTime = $onlineData->acctstarttime;
            $onlineTime=explode(" ",$onlineTime);
            if(date($onlineTime[1])>=date("00:00:00") && date($onlineTime[1])<=date("01:00:00")){$hr0++;}
            if(date($onlineTime[1])>date("01:00:00") && date($onlineTime[1])<=date("02:00:00")){$hr1++;}
            if(date($onlineTime[1])>date("02:00:00") && date($onlineTime[1])<=date("03:00:00")){$hr2++;}
            if(date($onlineTime[1])>date("03:00:00") && date($onlineTime[1])<=date("04:00:00")){$hr3++;}
            if(date($onlineTime[1])>date("04:00:00") && date($onlineTime[1])<=date("05:00:00")){$hr4++;}
            if(date($onlineTime[1])>date("05:00:00") && date($onlineTime[1])<=date("06:00:00")){$hr5++;}
            if(date($onlineTime[1])>date("06:00:00") && date($onlineTime[1])<=date("07:00:00")){$hr6++;}
            if(date($onlineTime[1])>date("07:00:00") && date($onlineTime[1])<=date("08:00:00")){$hr7++;}
            if(date($onlineTime[1])>date("08:00:00") && date($onlineTime[1])<=date("09:00:00")){$hr8++;}
            if(date($onlineTime[1])>date("09:00:00") && date($onlineTime[1])<=date("10:00:00")){$hr9++;}
            if(date($onlineTime[1])>date("10:00:00") && date($onlineTime[1])<=date("11:00:00")){$hr10++;}
            if(date($onlineTime[1])>date("11:00:00") && date($onlineTime[1])<=date("12:00:00")){$hr11++;}
            if(date($onlineTime[1])>date("12:00:00") && date($onlineTime[1])<=date("13:00:00")){$hr12++;}
            if(date($onlineTime[1])>date("13:00:00") && date($onlineTime[1])<=date("14:00:00")){$hr13++;}
            if(date($onlineTime[1])>date("14:00:00") && date($onlineTime[1])<=date("15:00:00")){$hr14++;}
            if(date($onlineTime[1])>date("15:00:00") && date($onlineTime[1])<=date("16:00:00")){$hr15++;}
            if(date($onlineTime[1])>date("16:00:00") && date($onlineTime[1])<=date("17:00:00")){$hr16++;}
            if(date($onlineTime[1])>date("17:00:00") && date($onlineTime[1])<=date("18:00:00")){$hr17++;}
            if(date($onlineTime[1])>date("18:00:00") && date($onlineTime[1])<=date("19:00:00")){$hr18++;}
            if(date($onlineTime[1])>date("19:00:00") && date($onlineTime[1])<=date("20:00:00")){$hr19++;}
            if(date($onlineTime[1])>date("20:00:00") && date($onlineTime[1])<=date("21:00:00")){$hr20++;}
            if(date($onlineTime[1])>date("21:00:00") && date($onlineTime[1])<=date("22:00:00")){$hr21++;}
            if(date($onlineTime[1])>date("22:00:00") && date($onlineTime[1])<=date("23:00:00")){$hr22++;}
            if(date($onlineTime[1])>date("23:00:00") && date($onlineTime[1])<=date("00:00:00")){$hr23++;}

        }
        $onlineData=array();
        $onlineData[] = ['key' => "online", 'value' => $hr0, 'date' => date("d/m/y")." 00:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr1, 'date' => date("d/m/y")." 01:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr2, 'date' => date("d/m/y")." 02:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr3, 'date' => date("d/m/y")." 03:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr4, 'date' => date("d/m/y")." 04:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr5, 'date' => date("d/m/y")." 05:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr6, 'date' => date("d/m/y")." 06:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr7, 'date' => date("d/m/y")." 07:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr8, 'date' => date("d/m/y")." 08:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr9, 'date' => date("d/m/y")." 09:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr10, 'date' => date("d/m/y")." 10:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr11, 'date' => date("d/m/y")." 11:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr12, 'date' => date("d/m/y")." 12:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr13, 'date' => date("d/m/y")." 13:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr14, 'date' => date("d/m/y")." 14:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr15, 'date' => date("d/m/y")." 15:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr16, 'date' => date("d/m/y")." 16:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr17, 'date' => date("d/m/y")." 17:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr18, 'date' => date("d/m/y")." 18:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr19, 'date' => date("d/m/y")." 19:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr20, 'date' => date("d/m/y")." 20:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr21, 'date' => date("d/m/y")." 21:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr22, 'date' => date("d/m/y")." 22:00"];
        $onlineData[] = ['key' => "online", 'value' => $hr23, 'date' => date("d/m/y")." 23:00"];

        return json_encode($onlineData);
        */
    }
    public function counter(){
        return;
        $agile_count=App\Users::whereNotNull('agilecrm_id')->where('agilecrm_id','!=','')->count();
        $statistics=app('App\Http\Controllers\DashboardController')->statisticsDateSetting();
        $statisticsStartDate=$statistics['statisticsStartDate'];
        $statisticsEndDate=$statistics['statisticsEndDate'];
        $statisticsType=$statistics['statisticsType'];
        return view('back-end.dashboard.home',['agile_count' => $agile_count,'statisticsStartDate' => $statisticsStartDate,'statisticsEndDate' => $statisticsEndDate,'statisticsType' => $statisticsType]);
  
    }
    public function cornJob()
    {
        
        

        //UPDATE  `test`.`cronjob` SET  `visitors` =  'asda1' WHERE  `cronjob`.`id` =1;
        
        //return $visitorsData;

        



        $agile_count=App\Users::whereNotNull('agilecrm_id')->where('agilecrm_id','!=','')->count();
        $statistics=app('App\Http\Controllers\DashboardController')->statisticsDateSetting();
        $statisticsStartDate=$statistics['statisticsStartDate'];
        $statisticsEndDate=$statistics['statisticsEndDate'];
        $statisticsType=$statistics['statisticsType'];
        $database =  app('App\Http\Controllers\Controller')->configuration();
        DB::table($database.'.cronjob')->where('id', 1)->update(['counter'=>view('back-end.dashboard.branches',['agile_count' => $agile_count,'statisticsStartDate' => $statisticsStartDate,'statisticsEndDate' => $statisticsEndDate,'statisticsType' => $statisticsType]),'online' => $onlineData,'visitors'=>$visitorsData]);
    }
    public function cornJob2(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        DB::table($database.'.cronjob2')->truncate();
    }
    // change statistics date
    public function statistics(){
        $startdate = Input::get('enddate');
        $enddate = Input::get('sartdate');
        $admin_id = Input::get('admin');
        $today=date("Y-m-d");

        // check if Today
        if($startdate == $today and $enddate== $today){$value="Today";}

        // check if yesterday
        $checkYesterdayBeforeConvert = strtotime(date("$today", strtotime($today)) . " -1 day");
        $yesterday=date('Y-m-d',$checkYesterdayBeforeConvert);
        if($startdate==$yesterday and $enddate==$yesterday)
        {$value="Yesterday";}

        if(!isset($value)){
            // check if Last 7 Days
            $checkLast7DaysBeforeConvert = strtotime(date("$startdate", strtotime($startdate)) . " -6 day");
            $Last7Days=date('Y-m-d',$checkLast7DaysBeforeConvert);
            if($startdate==$today and $enddate==$Last7Days)
            {$value="Last 7 Days";}
        }

        if(!isset($value)) {
            // check if Last 30 Days
            $checkLast30DaysBeforeConvert = strtotime(date("$startdate", strtotime($startdate)) . " -29 day");
            $Last30Days = date('Y-m-d', $checkLast30DaysBeforeConvert);
            if ($startdate == $today and $enddate == $Last30Days) {
                $value = "Last 30 Days";
            }
        }

        if(!isset($value)) {
            // check if This Month
            $firstDayThisMonth = date("Y-m") . "-01";
            $lastDayThisMonth = date('Y-m-t', strtotime($firstDayThisMonth));
            if ($enddate == $firstDayThisMonth and $startdate == $lastDayThisMonth) {
                $value = "This Month";
            }
        }

        if(!isset($value)) {
            // check if Last Month
            $checkLastMonth = strtotime(date("$today", strtotime($today)) . " -1 month");
            $LastMonth = date('m', $checkLastMonth);
            $firstDayLastMonth = date("Y") . "-$LastMonth-01";
            $lastDayLastMonth = date('Y-m-t', strtotime($firstDayLastMonth));
            if ($enddate == $firstDayLastMonth and $startdate == $lastDayLastMonth) {
                $value = "Last Month";
            }
        }

        if(!isset($value)) {
            // check if All
            if($enddate == "2016-01-01" and $startdate== $today){

                $value = "Whole period";
            }
        }

        if(!isset($value)){$value=$enddate.",".$startdate;}

        // check if record already exist in History table
        if(App\Settings::where('type','statistics'.$admin_id)->value('value')){
            // record already exist so we will update
            App\Settings::where('type', 'statistics'.$admin_id)->update(['value' =>  $value]);
        }else{// insert new record
            App\Settings::insert(['type' => 'statistics'.$admin_id, 'value' => $value]);
        }

        return redirect()->route('dashboard');

    }
    public function permissions(){
        $permissions = App\Admins::where('id', Auth::user()->id)->value('permissions');
        $split = explode(',', $permissions);
        $dashboard=0;
        $users=0;
        $onlineusers = 0;
        $networks = 0;
        $groups = 0;
        $branches = 0;
        $administration = 0;
        $packages = 0;
        $cards = 0;
        $settings = 0;
        $landingpage = 0;
        foreach($split as $permission){
            if($permission == 'dashboard'){ $dashboard = 1; }
            if($permission == 'users'){ $users = 1; }
            if($permission == 'onlineusers'){ $onlineusers = 1; }
            if($permission == 'networks'){ $networks = 1; }
            if($permission == 'groups'){ $groups = 1; }
            if($permission == 'branches'){ $branches = 1; }
            if($permission == 'administration'){ $administration = 1; }
            if($permission == 'packages'){ $packages = 1; }
            if($permission == 'cards'){ $cards = 1; }
            if($permission == 'settings'){ $settings = 1; }
            if($permission == 'landingpage'){ $landingpage = 1; }
        }
        return ['dashboard' => $dashboard, 'users' => $users, 'onlineusers' => $onlineusers, 'networks' => $networks, 'groups' => $groups,'branches' => $branches,'administration' => $administration,'packages' => $packages,'cards' => $cards,'settings' => $settings,'landingpage' => $landingpage];
    }

    
    public function dashboard_type(Request $request){ // change dashboard type
        //return $request;
        if(isset($request['action']) and $request['action']=="branch" and isset($request['id'])){
            App\Settings::where('type','dashboard_type')->where('state',Auth::user()->id)->update(['value'=>'branchid','state'=>$request['id']]);
            return redirect()->route('dashboard');
        }else{
            App\Settings::where('type','dashboard_type')->where('state',Auth::user()->id)->update(['value'=>$request['action']]);
            Session::forget('selected_dashboard_type_in_session');
            Session::push('selected_dashboard_type_in_session', $request['action']);
            return redirect()->route('dashboard');
        }
    }

}
