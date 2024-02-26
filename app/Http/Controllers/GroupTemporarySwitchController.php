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

class GroupTemporarySwitchController extends Controller
{ 
    public function index(Request $request)
    {
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();
        $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
        
        // // get user info
        // $userData = App\Users::where('u_phone',$sessionRequest->mobile)->first();
        if( isset($request->groupTemporarySwitchToken)){
            $sessionRequest = App\Models\GroupTemporarySwitch::where('token',$request->groupTemporarySwitchToken)->first();
            if(isset($sessionRequest)){
                $allUserInfo = $whatsappClass->getAllCustomerInfoToAdmin($customerData->database, $sessionRequest->u_id, $created_at, '1' );
                return view('back-end.settings.groupTemporarySwitch', ['customerData' => $customerData, 'request' => $request, 'sessionRequest' => $sessionRequest, 'allUserInfo' => $allUserInfo]);
            }else{
                return view('back-end.settings.groupTemporarySwitch', ['customerData' => $customerData, 'request' => $request]);
            }
        }else{
            return view('back-end.settings.groupTemporarySwitch', ['customerData' => $customerData, 'request' => $request]);
        }
    }

    public function groupTemporarySwitch(Request $request)
    {
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        $customerData =  DB::table('customers')->where('url',$split[2])->first();
        $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
        // get group_temporary_switch session request
        $sessionRequest = DB::table("$customerData->database.group_temporary_switch")->where('token',$request->token)->first();
        // get user data
        $userData = DB::table("$customerData->database.users")->where('u_id',$sessionRequest->u_id)->first();

        // validation: check if user make a refresh again and resupmit again
        if($sessionRequest->state == 1 and $sessionRequest->approved == null){
            if(isset($request->state) and $request->state == "approved" ){ // admin approved
                // calculate duration
                if($request->duration_type == "minutes"){$durationByMinutes = $request->duration_value;}
                elseif($request->duration_type == "hour"){$durationByMinutes = $request->duration_value*60;}
                elseif($request->duration_type == "day"){$durationByMinutes = ($request->duration_value*24)*60;}
                // calculate `finishing_at` by adding minutes to timeNOW
                $finishingAt = date('Y-m-d H:i:s',strtotime("+$durationByMinutes minutes",strtotime($created_at)));
                // update record `group_temporary_switch`
                DB::table("$customerData->database.group_temporary_switch")->where('token', $request->token)->update(['duration_by_minutes' => $durationByMinutes, 'started_at' => $created_at, 'finishing_at' => $finishingAt, 'new_group_id' => $request->new_group_id, 'approved' => '1', 'updated_at' => $created_at ]);
                // update new group id into user DB
                DB::table("$customerData->database.users")->where('u_id',$sessionRequest->u_id)->update(['group_id' => $request->new_group_id]); 
                // disconnect Mikrotik session to apply new group speed
                DB::table("$customerData->database.radacct")->where('u_id',$sessionRequest->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
                // send notification to user
                $notificationMsg = "🚀 Your speed-up request has been approved for $request->duration_value $request->duration_type started from NOW till $finishingAt";
                $whatsappClass->sendWhatsappWithoutSourceWithoutWaiting( "", $userData->u_phone , $notificationMsg, $customerData->id, $customerData->database);
                // return to web view
                return view('back-end.settings.groupTemporarySwitch', ['customerData' => $customerData, 'submit_state' => '1']);
            
            }else{// admin declined
                // diactivate session request
                DB::table("$customerData->database.group_temporary_switch")->where('token', $request->token)->update(['state' => '0', 'approved' => '0', 'updated_at' => $created_at ]);
                // send notification to user
                $notificationMsg = "Your speed-up request has been rejected";
                $whatsappClass->sendWhatsappWithoutSourceWithoutWaiting( "", $userData->u_phone , $notificationMsg, $customerData->id, $customerData->database);
                // return to web view
                return view('back-end.settings.groupTemporarySwitch', ['customerData' => $customerData, 'submit_state' => '0']);

            }
        }else{
            // return to web view 
            return view('back-end.settings.groupTemporarySwitch', ['customerData' => $customerData, 'submit_state' => '3']);
        }

    }

}