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
use Mail;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminApiController extends Controller
{ 

    public function chatBotAI(Request $request){

        // responce codes
        // 400 unauthorized, Please enter your mobile number?
        // 404 Please enter verification code?
        // 100 verification code is correct (push user to main Menu)
        // 403 forbidden from admin requests (you dont have admin privileges)
        
        // for testing only
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
        DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
        $microsystemSMSserver = new App\Http\Controllers\ApiController();
        
        // check if app token and database exist
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            // check if this session authenticated and verified or not
            $session = DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->first();
            if( isset($session) and $session->is_verified == 1 ){

                // get selected system database from `users_global` table
                $userGlobalRecord = DB::table( "users_global" )->where('mobile', $session->mobile)->whereNotNull('chatbot_database')->first();
                if(isset($userGlobalRecord)){ 
                    // set dafault system to the selected database 
                    $request->system = $userGlobalRecord->chatbot_database;
                    // get all session data from selected database  
                    $session = DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->first();
                }
                
                // increment total requests
                DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->increment('total_requests');
                // check if this is admin request
                if($request->action == "welcome"){
                    // change welcome message according to user or admin privileges
                    return json_encode(array('state' => 1, 'message' =>"welcome message success", 'is_admin' => $session->is_admin));
                }elseif($request->action == "getOnlineUsers"){

                    // if($session->is_admin == "1"){
                        if( isset($request->system) ){
                            
                            if($session->is_admin == "1"){ $data = DB::table("$request->system.radacct_active_users")->orderBy('radacctid','desc')->get(); }
                            else{ 
                                // $userData = DB::table($request->system.'.users')->where('u_phone',$session->mobile)->orderBy('u_id', 'desc')->first(); // we start using u_id insted of user mobile
                                $userData = DB::table($request->system.'.users')->where('u_id',$session->u_id)->orderBy('u_id', 'desc')->first();
                                $data = DB::table("$request->system.radacct_active_users")->where('u_id', $userData->u_id)->orderBy('radacctid','desc')->limit(25)->get();
                            }
                            $response = [];
                            $count = 0;
                            foreach ($data as $key => $value) {
                                $count++;
                                // sorting according to speed
                                $speedRate = $value->speed_rate;
                                if( !isset($speedRate) or $speedRate == ""){$speedRate="0bps/0bps";}
                                // replace each speed description ex.(mbps) to number according to each width
                                $speedRate = str_replace("Gbps","*4",$speedRate); $speedRate = str_replace("gbps","*4",$speedRate);
                                $speedRate = str_replace("Mbps","*3",$speedRate); $speedRate = str_replace("mbps","*3",$speedRate);
                                $speedRate = str_replace("kbps","*2",$speedRate);
                                $speedRate = str_replace("bps","*1",$speedRate);
                                // seperate upload and download 
                                $uploadSpeed = explode('/', $speedRate)[0];
                                $downloadSpeed = explode('/', $speedRate)[1];
                                // convert upload speed into bytes
                                if (strpos($uploadSpeed,"*4") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0] * 1024*1024*1024; }
                                if (strpos($uploadSpeed,"*3") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0] * 1024*1024; }
                                if (strpos($uploadSpeed,"*2") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0] * 1024; }
                                if (strpos($uploadSpeed,"*1") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0]; }
                                // convert download speed into bytes
                                if (strpos($downloadSpeed,"*4") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0] * 1024*1024*1024; }
                                if (strpos($downloadSpeed,"*3") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0] * 1024*1024; }
                                if (strpos($downloadSpeed,"*2") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0] * 1024; }
                                if (strpos($downloadSpeed,"*1") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0]; }
                                $downloadSpeed = intval($downloadSpeed);
                                // $value->downloadSpeedBytes = $downloadSpeed;

                                if($value->speed_rate == "0bps/0bps" or $value->speed_rate == ""){$value->speed_rate="0/0";}
                                $value->group_name = DB::table("$request->system.area_groups")->where('id',$value->group_id)->value('name');
                                array_push($response, ['downloadSpeedBytes' => $downloadSpeed, 'name'=> $value->u_name, 'mobile' => $value->u_phone, 'speed'=>$value->speed_rate, 'uptime'=>$value->uptime, 'room'=>$value->pms_room_no, 'hotel'=>$value->branch_name ]);
                            }
                            
                            if($session->is_admin == "1"){
                            // get unregisterd devices
                                $unregisterdDevices = DB::table($request->system.'.hosts')->where(['u_id'=>'0', 'internet_access'=>'0'])->orderby('id', 'desc')->limit(25)->get();
                                foreach ($unregisterdDevices as $device) {
                                    $device->bypassed == "true"? $deviceName = "Bypassed: $device->device_name" : $deviceName = "Unregistered: $device->device_name";
                                    array_push($response, ['downloadSpeedBytes' => 0, 'name'=> $deviceName, 'mobile' => $device->address, 'speed'=> $device->mac, 'uptime'=>$device->uptime, 'room'=> '', 'hotel' => DB::table("$request->system.branches")->where('id', $device->branch_id)->value('name') ]);
                                }
                            }

                            $sorted = collect($response)->sortByDesc('downloadSpeedBytes');
                            // return $sorted->values()->all();
                            // return json_encode($sorted->values()->all());
                            // get customer URL
                            $customerURL = DB::table('customers')->where('database',$request->system)->value('url');
                            return json_encode(array('state' => 1, 'adminURL' => 'http://'.$customerURL, 'count' => $count, 'is_admin' => $session->is_admin, 'onlineUsers' => $sorted->values()->all()));
                        }else{
                            $data = array('state' => 0, 'message' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }        
                    // }else{
                    //     return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    // }
                }elseif($request->action == "searchByName"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->target.'%')->orWhere('u_uname','=', $request->target)->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data)){
                                $response = array();
                                // array_push($response, array('name'=> 'Ahmed2', 'state' => 2) );
                                // return $response;
                                $counter = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    // // Get no of visits and last visit
                                    // $allCountUsers=App\Models\UsersRadacct::select(DB::raw('* ,count(u_id) as visits'))->where('u_id', $value->u_id)->groupBy('u_id')->first();
                                    // get all whatsapp bot prebared data
                                    if( $request->from == "hotelCoPilotEn" ){
                                        $autoReplyMsg = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $value->u_id, $created_at, '1' );
                                        array_push($response, array('counter' =>$counter, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $value->u_name, 'hotel' => DB::table("$request->system.branches")->where('id', $value->branch_id)->value('name'), 'room' => $value->u_uname, 'suspend' => $value->suspend ) );
                                    }else{ 
                                        $autoReplyMsg = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $value->u_id, $created_at, '1' ); 
                                        array_push($response, array('counter' =>$counter, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $value->u_name, 'mobile' => $value->u_phone, 'suspend' => $value->suspend ) );
                                    }

                                }
                                $data = array('state' => $counter, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "getUserByIndex"){
                    if($session->is_admin == "1"){    
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data)){
                                
                                $response = array();
                                // array_push($response, array('name'=> 'Ahmed2', 'state' => 2) );
                                // return $response;
                                $counter = 0;
                                $state = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    if($counter == $request->index){
                                        $state = 1;
                                        if( $request->from == "hotelCoPilotEn" ){$autoReplyMsg = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $value->u_id, $created_at, '1' );}
                                        else{ $autoReplyMsg = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $value->u_id, $created_at, '1' ); }
                                        
                                        array_push($response, array('counter' =>$counter, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $value->u_name, 'mobile' => $value->u_phone, 'suspend' => $value->suspend ) );
                                    }
                                }
                                $data = array('state' => $state, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "getUserByMobile"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            if($request->from == "hotelCoPilotEn"){
                                $data = DB::table("$request->system.users")->where('u_uname','=', $request->target)->orWhere('u_phone','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                            }else{
                                $data = DB::table("$request->system.users")->where('u_phone','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                            }
                           
                            if(isset($data)){
                                
                                $response = array();
                                // array_push($response, array('name'=> 'Ahmed2', 'state' => 2) );
                                // return $response;
                                $counter = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    if( $request->from == "hotelCoPilotEn" ){$autoReplyMsg = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $value->u_id, $created_at, '1' );}
                                    else{ $autoReplyMsg = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $value->u_id, $created_at, '1' ); }
                                    
                                    array_push($response, array('counter' =>$counter, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $value->u_name, 'mobile' => $value->u_phone, 'suspend' => $value->suspend ) );
                                    // array_push($response, array('counter' =>$counter, 'name'=> $value->u_name, 'mobile' => $value->u_phone) );
                                }
                                $data = array('state' => $counter, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }    
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }   

                }elseif($request->action == "getUserByMobileByIndex"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_phone','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data)){
                                
                                $response = array();
                                // array_push($response, array('name'=> 'Ahmed2', 'state' => 2) );
                                // return $response;
                                $counter = 0;
                                $state = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    if($counter == $request->index){
                                        $state = 1;
                                        if( $request->from == "hotelCoPilotEn" ){$autoReplyMsg = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $value->u_id, $created_at, '1' );}
                                        else{ $autoReplyMsg = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $value->u_id, $created_at, '1' ); }
                                        // array_push($response, array('counter' =>$counter, 'allWhatsappBotInfo' => $autoReplyMsg ) );
                                        array_push($response, array('counter' =>$counter, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $value->u_name, 'mobile' => $value->u_phone, 'suspend' => $value->suspend ) );
                                    }
                                }
                                $data = array('state' => $state, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "logout"){
                    if( isset($request->system) ){
                        // delete from there system database
                        DB::table("$request->system.chatbot_sessions")->where('session_id',$session->session_id)->delete();
                        // delete from master database `demo`
                        DB::table("demo.chatbot_sessions")->where('session_id',$session->session_id)->delete();
                        // set `users_global` record to be null
                        DB::table("users_global")->where( 'mobile',$session->mobile )->update([ 'chatbot_database' => null ]);
                        return json_encode(array('state' => 1, 'message' => 'logout successfully'));
                    }else{
                        $data = array('state' => 0, 'response' => 'unauthorized.');
                        return $msg = json_encode($data);
                    } 

                }elseif($request->action == "suspend"){
                    // state 1: found 1 user, and suspension has been successfully
                    // state 2: found many users, select by index
                    // state 3: already suspended before
                    // state 0: not found users
                    // state 403: forbidden from admin requests (you dont have admin privileges)
                    if($session->is_admin == "1"){
                        // return $request->target;
                        // check if the target username is exact id DB (we will suspend him directly)
                        $matchedUsers = DB::table("$request->system.users")->where('u_name',$request->target)->orWhere('u_uname',$request->target)->get();
                        if( count($matchedUsers) == 1 ){
                            if( $matchedUsers[0]->suspend == "1"){
                                // already suspended before
                                return json_encode(array('state' => 3, 'message' =>'already suspended before'));
                            }else{
                                // we will suspend him directly
                                $this->suspendUnsuspend($matchedUsers[0]->u_id, 'false', '400', $request->system);
                                return json_encode(array('state' => 1, 'message' =>'found 1 user, and suspension has been successfully'));
                            }
                        }elseif(count($matchedUsers) > 1){
                            // we will search for any matched users in the same name, then admin will select index
                            
                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data)){
                                
                                $response = array();
                                $counter = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    if( $request->from == "hotelCoPilotEn" ){
                                        array_push($response, array('counter' =>$counter, 'name'=> $value->u_name, 'mobile' => $value->u_phone, 'room' => $value->u_uname, 'hotel' => DB::table("$request->system.branches")->where('id', $value->branch_id)->value('name')) );
                                    }else{ 
                                        array_push($response, array('counter' =>$counter, 'name'=> $value->u_name, 'mobile' => $value->u_phone) );
                                    }
                                }
                                $data = array('state' => 2, 'message' =>'found many users, select by index', 'counter'=> $counter, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found users.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            // not found users
                            return json_encode(array('state' => 0, 'message' =>'not found users'));
                        }
                              
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }
                }elseif($request->action == "suspendUserByIndex"){
                    // state 1: found index, and suspension has been successfully
                    // state 2: not found index
                    // state 0: not found in users DB
                    // state 403: forbidden from admin requests (you dont have admin privileges)
                    if($session->is_admin == "1"){    
                        
                        $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                        if(isset($data)){
                            
                            $counter = 0;
                            $state = 2; // preparing values if not found index
                            $message = "not found index"; // preparing values if not found index
                            foreach ($data as $key => $value) {
                                $counter++;
                                if($counter == $request->index){
                                    // we found him, so we will suspend him directly
                                    $state = 1;
                                    $message = "found index, and suspension has been successfully";
                                    $this->suspendUnsuspend($value->u_id, 'false', '400', $request->system);
                                }
                            }
                            // get customer URL
                            $customerURL = DB::table('customers')->where('database',$request->system)->value('url');
                            $data = array('state' => $state, 'adminURL' => 'http://'.$customerURL, 'message' => $message);
                            return $msg = json_encode($data);
                        
                        }else{
                            $data = array('state' => 0, 'message' => 'not found user.');
                            return $msg = json_encode($data);
                        }
                        
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "unsuspend"){
                    // state 1: found 1 user, and unsuspension has been successfully
                    // state 2: found many users, select by index
                    // state 3: already unsuspended before
                    // state 0: not found users
                    // state 403: forbidden from admin requests (you dont have admin privileges)
                    if($session->is_admin == "1"){
                        // check if the target username is exact id DB (we will unsuspend him directly)
                        $matchedUsers = DB::table("$request->system.users")->where('u_name',$request->target)->orWhere('u_uname',$request->target)->get();
                        if( count($matchedUsers) == 1 ){
                            if( $matchedUsers[0]->suspend == "0"){
                                // already suspended before
                                return json_encode(array('state' => 3, 'message' =>'already unsuspended before'));
                            }else{
                                // we will suspend him directly
                                $this->suspendUnsuspend($matchedUsers[0]->u_id, 'true', '400', $request->system);
                                return json_encode(array('state' => 1, 'message' =>'found 1 user, and unsuspension has been successfully'));
                            }
                        }elseif(count($matchedUsers) > 1){
                            // we will search for any matched users in the same name, then admin will select index
                            
                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data)){
                                
                                $response = array();
                                $counter = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    if( $request->from == "hotelCoPilotEn" ){
                                        array_push($response, array('counter' =>$counter, 'name'=> $value->u_name, 'mobile' => $value->u_phone, 'room' => $value->u_uname, 'hotel' => DB::table("$request->system.branches")->where('id', $value->branch_id)->value('name')) );
                                    }else{ 
                                        array_push($response, array('counter' =>$counter, 'name'=> $value->u_name, 'mobile' => $value->u_phone) );
                                    }
                                }
                                $data = array('state' => 2, 'message' =>'found many users, select by index', 'counter'=> $counter, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found users.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            // not found users
                            return json_encode(array('state' => 0, 'message' =>'not found users'));
                        }
                              
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }
                }elseif($request->action == "unsuspendUserByIndex"){
                    // state 1: found index, and unsuspension has been successfully
                    // state 2: not found index
                    // state 0: not found in users DB
                    // state 403: forbidden from admin requests (you dont have admin privileges)
                    if($session->is_admin == "1"){    
                        
                        $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->target.'%')->orderBy('u_id','desc')->limit(5)->get();
                        if(isset($data)){
                            
                            $counter = 0;
                            $state = 2; // preparing values if not found index
                            $message = "not found index"; // preparing values if not found index
                            foreach ($data as $key => $value) {
                                $counter++;
                                if($counter == $request->index){
                                    // we found him, so we will suspend him directly
                                    $state = 1;
                                    $message = "found index, and unsuspension has been successfully";
                                    $this->suspendUnsuspend($value->u_id, 'true', '400', $request->system);
                                }
                            }
                            // get customer URL
                            $customerURL = DB::table('customers')->where('database',$request->system)->value('url');
                            $data = array('state' => $state, 'adminURL' => 'http://'.$customerURL, 'message' => $message);
                            return $msg = json_encode($data);
                        
                        }else{
                            $data = array('state' => 0, 'message' => 'not found user.');
                            return $msg = json_encode($data);
                        }
                        
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "basicBot"){
                    // state 1: Basic Bot have a response
                    // state 0: not found users
                    // return $request->full_request['originalDetectIntentRequest'];
                    // check if marketing is enabled or not to view marketing
                    if( DB::table("$request->system.settings")->where('type','marketing_enable')->value('state') == "1"){
                        $basicBotMiddlewareWebhook4allGatewaysClass = new App\Http\Controllers\BasicBotMiddlewareWebhook4allGateways();
                        $response = $basicBotMiddlewareWebhook4allGatewaysClass->basicBotMiddlewareHandler($request->full_request, $request->session);
                        $data = array('state' => 1, 'message' => 'Basic Bot have a response', 'response' => $response);
                    }else{
                        $data = array('state' => 0, 'message' => 'wifi marketing is disabled');
                    }
                    return $msg = json_encode($data);
                    
                }elseif($request->action == "getConsumed"){
                    // state 0: not found data
                    // state 1: found data
                    // $userData = DB::table($request->system.'.users')->where('u_phone',$session->mobile)->first();// we start using u_id insted of user mobile
                    $userData = DB::table($request->system.'.users')->where('u_id',$session->u_id)->orderBy('u_id', 'desc')->first();
                    
                    // $allUserInfo = $whatsappClass->getAllCustomerInfoArray($request->system, $userData->u_id, $created_at, '1' );
                    if( $request->from == "hotelCoPilotEn" ){$allUserInfo = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $userData->u_id, $created_at, '1' );}
                    else{ $allUserInfo = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $userData->u_id, $created_at, '1' ); }
                    return json_encode(array('state' => 1, 'message' => 'getting user consumption', 'response' => $allUserInfo));
                }elseif($request->action == "upgradeSpeed"){
                    // state 1: this request from Admin, Ask him for the duration
                    // state 2: this request from Admin, and we recevied duration from the first step, and group moving successfully
                    // state 3: this request from Admin, and we recevied duration from the first step, but we didnt find VIP or unlimited group
                    // state 4: this request from User, Ask him for the duration
                    // state 5: this request from User, and we recevied duration from the first step, and request has been created successfully
                    // state 6: this request from Admin, and already switched to VIP
                    
                    // check if we recevied duration from the first step
                    if($request->duration == "ربع ساعه" or $request->duration == "ربع ساعة"){$durationByMinutesIs = "15";}
                    elseif($request->duration == "نص ساعه" or $request->duration == "نصف ساعه" or $request->duration == "نصايه"){$durationByMinutesIs = "30";}
                    elseif($request->duration == "ساعه" or $request->duration == "ساعة"){$durationByMinutesIs = "60";}
                    elseif($request->duration == "ساعتين"){$durationByMinutesIs = "120";}
                    elseif($request->duration == "3 ساعات" or $request->duration == "3ساعات"){$durationByMinutesIs = "180";}
                    elseif($request->duration == "4 ساعات" or $request->duration == "4ساعات"){$durationByMinutesIs = "240";}
                    elseif($request->duration == "5 ساعات" or $request->duration == "5ساعات"){$durationByMinutesIs = "300";}
                    elseif($request->duration == "6 ساعات" or $request->duration == "6ساعات"){$durationByMinutesIs = "360";}
                    elseif($request->duration == "8 ساعات" or $request->duration == "8ساعات"){$durationByMinutesIs = "480";}
                    elseif($request->duration == "يوم"){$durationByMinutesIs = "1440";}
                    // get user info
                    // $userData = DB::table($request->system.'.users')->where('u_phone',$session->mobile)->first();// we start using u_id insted of user mobile
                    $userData = DB::table($request->system.'.users')->where('u_id',$session->u_id)->orderBy('u_id', 'desc')->first();
                    
                    
                    if($session->is_admin == "1"){
                        
                        // check if there is any active record in `group_temporary_switch`
                        $checkIfThereIsActivegroupTemporarySwitch = DB::table("$request->system.group_temporary_switch")->where('u_id',$userData->u_id)->where('state', '1')->first();
                        if(isset($checkIfThereIsActivegroupTemporarySwitch)){
                            // return json state 6
                            return json_encode(array('state' => 6, 'message' =>'this request from Admin, and already switched to VIP', 'duration_by_minutes' => $checkIfThereIsActivegroupTemporarySwitch->duration_by_minutes, 'started_at'=>$checkIfThereIsActivegroupTemporarySwitch->started_at, 'finishing_at'=>$checkIfThereIsActivegroupTemporarySwitch->finishing_at, 'previously_group'=>DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->previously_group_id)->value('name'), 'new_group' => DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->new_group_id)->value('name'))); 
                        }else{
                            // there is no active record in `group_temporary_switch`
                            if(isset($durationByMinutesIs)){
                                // we recevied duration from the first step
                                // search for "VIP" or "unlimited" group
                                $vipGroup = DB::table("$request->system.area_groups")->where('name', 'like', '%vip')->first();
                                $unlimitedGroup = DB::table("$request->system.area_groups")->where('name', 'like', '%unlimited')->first();
                                if(isset($vipGroup->id)){$foundTheVIPgroupId= $vipGroup->id; $foundTheVIPgroupName = $vipGroup->name;}
                                elseif(isset($unlimitedGroup->id)){$foundTheVIPgroupId = $unlimitedGroup->id; $foundTheVIPgroupName = $unlimitedGroup->name;}
                                // ckeck if we found VIP group
                                if(isset($foundTheVIPgroupId)){
                                    // state 2: this request from Admin, and we recevied duration from the first step, and group moving successfully
                                    // calculate `finishing_at` by adding minutes to timeNOW
                                    $finishingAt = date('Y-m-d H:i:s',strtotime("+$durationByMinutesIs minutes",strtotime($created_at)));
                                    // create record in `group_temporary_switch` with session limit period to return to the previously group after time is over 
                                    DB::table("$request->system.group_temporary_switch")->insert([['u_id' => $userData->u_id, 'requested_by' => '1', 'state' => '1', 'approved' => '1', 'duration_by_minutes'=> $durationByMinutesIs, 'started_at'=> $created_at, 'finishing_at'=> $finishingAt, 'previously_group_id'=> $userData->group_id, 'new_group_id'=> $foundTheVIPgroupId, 'created_at'=> $created_at ]]);
                                    // update new group id into user DB
                                    DB::table("$request->system.users")->where('u_id',$userData->u_id)->update(['group_id' => $foundTheVIPgroupId]); 
                                    // disconnect Mikrotik session to apply new group speed
                                    DB::table("$request->system.radacct")->where('u_id',$userData->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
                                    // return json state 2
                                    return json_encode(array('state' => 2, 'message' =>'this request from Admin, and we recevied duration from the first step, and group moving successfully', 'response' => $foundTheVIPgroupName)); 
                                }else{
                                    // state 3: this request from Admin, and we recevied duration from the first step, but we didnt find VIP or unlimited group
                                    // get customer URL
                                    $customerURL = DB::table('customers')->where('database',$request->system)->value('url');
                                    return json_encode(array('state' => 3, 'adminURL' => 'http://'.$customerURL, 'message' =>'this request from Admin, and we recevied duration from the first step, but we didnt find VIP or unlimited group')); 
                                }

                            }else{
                            // state 1: this request from Admin, Ask him for the duration
                            return json_encode(array('state' => 1, 'message' =>'this request from Admin, Ask him for the duration')); 
                            }
                        }
                              
                    }else{ // this request from user
                        if(isset($durationByMinutesIs)){// we recevied duration from the first step
                            // check if there is any active record in `group_temporary_switch`
                            $checkIfThereIsActivegroupTemporarySwitch = DB::table("$request->system.group_temporary_switch")->where('u_id',$userData->u_id)->where('state', '1')->first();
                            if(isset($checkIfThereIsActivegroupTemporarySwitch)){
                                if($checkIfThereIsActivegroupTemporarySwitch->approved == "1"){
                                    // User already switched to another group, return json state 6
                                    return json_encode(array('state' => 9, 'message' =>'this request from User, and already switched to another group', 'duration_by_minutes' => $checkIfThereIsActivegroupTemporarySwitch->duration_by_minutes, 'started_at'=>$checkIfThereIsActivegroupTemporarySwitch->started_at, 'finishing_at'=>$checkIfThereIsActivegroupTemporarySwitch->finishing_at, 'previously_group'=>DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->previously_group_id)->value('name'), 'new_group' => DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->new_group_id)->value('name'))); 
                                }else{
                                    // User already request before but still admin didn't confirm
                                    return json_encode(array('state' => 8, 'message' =>'User already request before but still admin didnt confirm', 'duration_by_minutes' => $checkIfThereIsActivegroupTemporarySwitch->duration_by_minutes, 'created_at'=>$checkIfThereIsActivegroupTemporarySwitch->created_at)); 
                                }
                                
                            }else{// there is no active record in `group_temporary_switch`
                                // state 2: this request from User, and we recevied duration from the second step
                                // create random token
                                $randToken = bin2hex(random_bytes(50));
                                // create record in `group_temporary_switch` with session limit period to return to the previously group after time is over 
                                DB::table("$request->system.group_temporary_switch")->insert([['u_id' => $userData->u_id, 'requested_by' => '2', 'state' => '1', 'duration_by_minutes'=> $durationByMinutesIs, 'previously_group_id'=> $userData->group_id, 'token'=>$randToken ,'created_at'=> $created_at ]]);
                                // start sending notification to Admin
                                // get all user info
                                if( $request->from == "hotelCoPilotEn" ){$allUserInfo = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $value->u_id, $created_at, '1' );}
                                else{ $allUserInfo = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $value->u_id, $created_at, '1' ); }
                                // set number of minutes format for view
                                if($durationByMinutesIs=="0"){$durationByMinutes = "more than 6 hours";}
                                else{$durationByMinutes = $durationByMinutesIs." Minutes";}
                                // get customer URL
                                $customerData = DB::table('customers')->where('database',$request->system)->first();
                                // prepare message
                                $notificationMsg = "The following user needs to speed-up for $durationByMinutes\n $allUserInfo \n\n👉 to approve or decline click here: https://$customerData->url/groupTemporarySwitch?groupTemporarySwitchToken=$randToken"; //🚀 
                                // get All Whatsapp admins
                                $whatsappAdmins = DB::table( $request->system.".admins" )->where('permissions', 'like','%WAadmin%')->get();
                                foreach($whatsappAdmins as $admin){
                                    // SENDING Message
                                    $whatsappClass->sendWhatsappWithoutSourceWithoutWaiting( "", $admin->mobile , $notificationMsg, $customerData->id, $request->system);
                                    // sleep(1);
                                    // sending SMS by Microsystem SMS server 5/5/2021
                                    $microsystemSMSserver->sendMicrosystemSMS($request->system, 'chatbot', $admin->mobile, $notificationMsg);
                                }
                                //  state 5: this request from User, and we recevied duration from the first step, and request has been created successfully
                                return json_encode(array('state' => 5, 'message' =>'this request from User, and we recevied duration from the first step, and request has been created successfully')); 
                            }
                        }else{
                            // state 4: this request from User, Ask him for the duration
                           return json_encode(array('state' => 4, 'message' =>'this request from User, Ask him for the duration')); 
                        }
                    }
                }elseif($request->action == "upgradeSpeedDurationForAdmin"){
                    // state 2: this request from Admin, and we recevied duration from the first step, and group moving successfully
                    // state 3: this request from Admin, and we recevied duration from the first step, but we didnt find VIP or unlimited group
                    // state 6: this request from Admin, and already switched to VIP
                    // state 7: this request from Admin, wrong entry
                    
                    // check if we recevied duration from the first step
                    if($request->durationNumber == "1"){$durationByMinutesIs = "30";}
                    elseif($request->durationNumber == "2"){$durationByMinutesIs = "60";}
                    elseif($request->durationNumber == "3"){$durationByMinutesIs = "120";}
                    elseif($request->durationNumber == "4"){$durationByMinutesIs = "360";}
                    elseif($request->durationNumber == "5"){$durationByMinutesIs = "0";}
                    else{
                        // state 7: this request from Admin, wrong entry
                        return json_encode(array('state' => 7, 'message' =>'this request from Admin, wrong entry')); 
                    }
                    
                    if($session->is_admin == "1"){

                        // get user info
                        // $userData = DB::table($request->system.'.users')->where('u_phone',$session->mobile)->first();// we start using u_id insted of user mobile
                        $userData = DB::table($request->system.'.users')->where('u_id',$session->u_id)->orderBy('u_id', 'desc')->first();
                        
                        // check if there is any active record in `group_temporary_switch`
                        $checkIfThereIsActivegroupTemporarySwitch = DB::table("$request->system.group_temporary_switch")->where('u_id',$userData->u_id)->where('state', '1')->first();
                        if(isset($checkIfThereIsActivegroupTemporarySwitch)){
                            // return json state 6
                            return json_encode(array('state' => 6, 'message' =>'this request from Admin, and already switched to VIP', 'duration_by_minutes' => $checkIfThereIsActivegroupTemporarySwitch->duration_by_minutes, 'started_at'=>$checkIfThereIsActivegroupTemporarySwitch->started_at, 'finishing_at'=>$checkIfThereIsActivegroupTemporarySwitch->finishing_at, 'previously_group'=>DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->previously_group_id)->value('name'), 'new_group' => DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->new_group_id)->value('name'))); 
                        }else{// there is no active record in `group_temporary_switch`
                            // we recevied duration from the first step
                            // search for "VIP" or "unlimited" group
                            $vipGroup = DB::table("$request->system.area_groups")->where('name', 'like', '%vip')->first();
                            $unlimitedGroup = DB::table("$request->system.area_groups")->where('name', 'like', '%unlimited')->first();
                            if(isset($vipGroup->id)){$foundTheVIPgroupId= $vipGroup->id; $foundTheVIPgroupName = $vipGroup->name;}
                            elseif(isset($unlimitedGroup->id)){$foundTheVIPgroupId = $unlimitedGroup->id; $foundTheVIPgroupName = $unlimitedGroup->name;}
                            // ckeck if we found VIP group
                            if(isset($foundTheVIPgroupId)){
                                // state 2: this request from Admin, and we recevied duration from the second step, and group moving successfully
                                // check if admin select unlimited time
                                if($foundTheVIPgroupId != 0){
                                    // calculate `finishing_at` by adding minutes to timeNOW
                                    $finishingAt = date('Y-m-d H:i:s',strtotime("+$durationByMinutesIs minutes",strtotime($created_at)));
                                    // create record in `group_temporary_switch` with session limit period to return to the previously group after time is over 
                                    DB::table("$request->system.group_temporary_switch")->insert([['u_id' => $userData->u_id, 'requested_by' => '1', 'state' => '1', 'approved' => '1', 'duration_by_minutes'=> $durationByMinutesIs, 'started_at'=> $created_at, 'finishing_at'=> $finishingAt, 'previously_group_id'=> $userData->group_id, 'new_group_id'=> $foundTheVIPgroupId, 'created_at'=> $created_at ]]);
                                }
                                // update new group id into user DB
                                DB::table("$request->system.users")->where('u_id',$userData->u_id)->update(['group_id' => $foundTheVIPgroupId]); 
                                // disconnect Mikrotik session to apply new group speed
                                DB::table("$request->system.radacct")->where('u_id',$userData->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
                                // return json state 2
                                // get customer URL
                                $customerURL = DB::table('customers')->where('database',$request->system)->value('url');
                                return json_encode(array('state' => 2, 'adminURL' => 'http://'.$customerURL, 'message' =>'this request from Admin, and we recevied duration from the second step, and group moving successfully', 'response' => $foundTheVIPgroupName, 'durationByMinutes' => $durationByMinutesIs)); 
                            }else{
                                // state 3: this request from Admin, and we recevied duration from the second step, but we didnt find VIP or unlimited group
                                // get customer URL
                                $customerURL = DB::table('customers')->where('database',$request->system)->value('url');
                                return json_encode(array('state' => 3, 'adminURL' => 'http://'.$customerURL, 'message' =>'this request from Admin, and we recevied duration from the second step, but we didnt find VIP or unlimited group')); 
                            }

                            
                        }
                              
                    }else{ // this request from user
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }
                }elseif($request->action == "upgradeSpeedDurationForUser"){
                    // state 2: this request from User, and request has been created successfully
                    // state 6: this request from User, and already switched to VIP
                    // state 7: this request from User, wrong entry
                    // state 8: User already request before but still admin didnt confirm

                    // check if we recevied duration from the first step
                    if($request->durationNumber == "1"){$durationByMinutesIs = "30";}
                    elseif($request->durationNumber == "2"){$durationByMinutesIs = "60";}
                    elseif($request->durationNumber == "3"){$durationByMinutesIs = "120";}
                    elseif($request->durationNumber == "4"){$durationByMinutesIs = "360";}
                    elseif($request->durationNumber == "5"){$durationByMinutesIs = "0";}
                    else{
                        // state 7: this request from User, wrong entry
                        return json_encode(array('state' => 7, 'message' =>'this request from User, wrong entry')); 
                    }
                    
                    // get user info
                    // $userData = DB::table($request->system.'.users')->where('u_phone',$session->mobile)->first();// we start using u_id insted of user mobile
                    $userData = DB::table($request->system.'.users')->where('u_id',$session->u_id)->orderBy('u_id', 'desc')->first();
                    
                    // check if there is any active record in `group_temporary_switch`
                    $checkIfThereIsActivegroupTemporarySwitch = DB::table("$request->system.group_temporary_switch")->where('u_id',$userData->u_id)->where('state', '1')->first();
                    if(isset($checkIfThereIsActivegroupTemporarySwitch)){
                        if($checkIfThereIsActivegroupTemporarySwitch->approved == "1"){
                            // User already switched to another group, return json state 6
                            return json_encode(array('state' => 6, 'message' =>'this request from User, and already switched to another group', 'duration_by_minutes' => $checkIfThereIsActivegroupTemporarySwitch->duration_by_minutes, 'started_at'=>$checkIfThereIsActivegroupTemporarySwitch->started_at, 'finishing_at'=>$checkIfThereIsActivegroupTemporarySwitch->finishing_at, 'previously_group'=>DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->previously_group_id)->value('name'), 'new_group' => DB::table("$request->system.area_groups")->where('id', $checkIfThereIsActivegroupTemporarySwitch->new_group_id)->value('name'))); 
                        }else{
                            // User already request before but still admin didn't confirm
                            return json_encode(array('state' => 8, 'message' =>'User already request before but still admin didnt confirm', 'duration_by_minutes' => $checkIfThereIsActivegroupTemporarySwitch->duration_by_minutes, 'created_at'=>$checkIfThereIsActivegroupTemporarySwitch->created_at)); 
                        }
                        
                    }else{// there is no active record in `group_temporary_switch`
                        // we recevied duration from the first step
                        // state 2: this request from User, and we recevied duration from the second step
                        
                        // create random token
                        $randToken = bin2hex(random_bytes(50));
                        // create record in `group_temporary_switch` with session limit period to return to the previously group after time is over 
                        DB::table("$request->system.group_temporary_switch")->insert([['u_id' => $userData->u_id, 'requested_by' => '2', 'state' => '1', 'duration_by_minutes'=> $durationByMinutesIs, 'previously_group_id'=> $userData->group_id, 'token'=>$randToken ,'created_at'=> $created_at ]]);
                        // start sending notification to Admin
                        // get all user info
                        if( $request->from == "hotelCoPilotEn" ){$allUserInfo = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $userData->u_id, $created_at, '1' );}
                        else{ $allUserInfo = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $userData->u_id, $created_at, '1' ); }
                        // set number of minutes format for view
                        if($durationByMinutesIs=="0"){$durationByMinutes = "more than 6 hours";}
                        else{$durationByMinutes = $durationByMinutesIs." Minutes";}
                        // get customer URL
                        $customerData = DB::table('customers')->where('database',$request->system)->first();
                        // prepare WhatsApp message
                        $notificationMsgWhatsApp = "The following user needs to speed-up for $durationByMinutes\n $allUserInfo \n\n👉 to approve or decline click here: https://$customerData->url/groupTemporarySwitch?groupTemporarySwitchToken=$randToken"; //🚀 
                        // prepare SMS message    
                        $notificationMsgSMS = "Room $userData->u_uname needs to speed-up for $durationByMinutes to approve or decline click here: http://$customerData->url/groupTemporarySwitch?groupTemporarySwitchToken=$randToken"; //🚀 
                        // get All Whatsapp admins
                        $whatsappAdmins = DB::table( $request->system.".admins" )->where('permissions', 'like','%WAadmin%')->get();
                        foreach($whatsappAdmins as $admin){
                            // SENDING WhatsApp Message
                            $whatsappClass->sendWhatsappWithoutSourceWithoutWaiting( "", $admin->mobile , $notificationMsgWhatsApp, $customerData->id, $request->system);
                            // sleep(1);
                            // sending SMS by Microsystem SMS server 5/5/2021
                            // $microsystemSMSserver->sendMicrosystemSMS($request->system, 'chatbot', $admin->mobile, $notificationMsgSMS);
                        }
                        // return json state 2
                        return json_encode(array('state' => 2, 'message' =>'this request from User, and request has been created successfully')); 
                    }
                            
                    
                }elseif($request->action == "checkNetworkState"){

                    if($session->is_admin == "1"){
                        if( isset($request->system) ){
                            // state 0: not found data
                            // state 1: found data
                            
                            // first step: get online users / total users
                            $totalOnline = DB::table($request->system.'.radacct_active_users')->count()."/".DB::table($request->system.'.users')->count();

                            $branchesResponse = array();
                            // second step: get app branches data
                            $branchesData = DB::table($request->system.'.branch_network')->limit(1)->get();
                            foreach ($branchesData as $key => $value) {
                                
                                $currBranchData = DB::table($request->system.'.branches')->where('id', $value->id)->first();

                                // get the first day of renwing day to get monthly usage in GB
                                $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($request->system, $value->id);
                                $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                                $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];
                                // $branchesResponse.=$currBranchData->state == 1? "فرع ".$currBranchData->name." شغال بنظام التحكم آلالي "."\n" : "فرع ".$currBranchData->name." شغال بالنظام اليدوي "."\n";
                                // $branchesResponse.="لينك الدخول: ".$currBranchData->url."\n";
                                $value->url=$currBranchData->url;
                                // $branchesResponse[$currBranchData->id]['url']=$currBranchData->url;

                                $value->count_online = DB::table($request->system.'.radacct_active_users')->where('branch_id', $value->id)->count();
                                $value->count_users = DB::table($request->system.'.users')->where('branch_id', $value->id)->count();
                                
                                // get Monthly Usage
                                $monthlyUsageUpload = DB::table($request->system.'.radacct')->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctinputoctets');
                                $monthlyUsageDownload = DB::table($request->system.'.radacct')->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctoutputoctets');
                                $monthlyTotalUsage = round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1)." GB";
                                $value->monthly_usage = $monthlyTotalUsage;
                                // get lastCheckSeconds to get delayTime to get state (connected or disconnected)
                                $radiusType = $currBranchData->radius_type;
                                if( $radiusType == "aruba" ){ $foundDDWRT=1; }
                                if( $radiusType == "ddwrt" ){ $foundDDWRT=1; }
                                if(!isset($foundDDWRT)){$foundDDWRT=0;}
                                if($foundDDWRT==1){
                                    // get value from last update in "radacct" table
                                    $lastCheckSeconds = strtotime( DB::table($request->system.'.radacct')->where('branch_id',$value->id)->orderBy('radacctid', 'desc')->value('acctupdatetime') );
                                }else{
                                    // get value from branch table
                                    $lastCheckSeconds=strtotime($value->last_check);
                                }
                                $timeNowSeconds = strtotime(Carbon::now());
                                $value->delayTime = $timeNowSeconds - $lastCheckSeconds;
                                $value->connected = $value->delayTime > 120 ? "0" : "1";

                                if($foundDDWRT==1){
                                    $value->cpu = "-";
                                    $value->uptime = "-";
                                    $value->ram = "-";
                                }else{// send real data
                                    $value->cpu = $value->cpu."%";
                                }
                                $value->foundDDWRT = $foundDDWRT;
                                if($value->last_check){
                                    $value->last_check_date = explode(' ', $value->last_check)[0];
                                    $value->last_check_time = explode(' ', $value->last_check)[1];
                                }

                                // get current download speed
                                $interface_out_rate = DB::table($request->system.'.history')->where('operation','interface_out_rate')->where('branch_id',$currBranchData->id)->first();
                                $interface_out_net_speed = DB::table($request->system.'.history')->where('operation','interface_out_net_speed')->where('branch_id',$currBranchData->id)->first();
                                $currentDownSpeed = $interface_out_rate->notes;
                                $netDownSpeed = $interface_out_net_speed->notes;
                                if ($currentDownSpeed != 0) { $downloadPercentage = round(($currentDownSpeed / $netDownSpeed) * 100, 1);
                                } else { $downloadPercentage = 0;}
                                $currentDownSpeedToMB = round($currentDownSpeed/1024,1);
                                $netDownSpeedToMB = round($netDownSpeed/1024,1);
                                $value->current_download_speed_rate = $currentDownSpeedToMB . "MB/" . $netDownSpeedToMB . "MB";
                                $value->current_download_speed_downloadPercentage = $downloadPercentage."%";
                                // get current upload speed
                                $currentUpSpeed = $interface_out_rate->details;
                                $netUpSpeed = $interface_out_net_speed->details;
                                if ($currentUpSpeed != 0) { $uploadPercentage = round(($currentUpSpeed / $netUpSpeed) * 100, 1);} 
                                else { $uploadPercentage = 0; }
                                $currentUpSpeedToMB = round($currentUpSpeed/1024,1);
                                $netUpSpeedToMB = round($netUpSpeed/1024,1);
                                $value->current_upload_speed_rate = $currentUpSpeedToMB . "MB/" . $netUpSpeedToMB . "MB";
                                $value->current_upload_speed_uploadPercentage = $uploadPercentage."%";

                                // get monthly quota
                                $value->monthly_quota = $currBranchData->monthly_quota."GB";
                                $value->monthly_remaining_quota = intval($currBranchData->monthly_quota)-round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1)."GB";
                            }
                            
                            return json_encode(array('state' => 1, 'message' => 'getting network state data', 'totalOnline' => $totalOnline, 'branchesResponse' => $branchesData));
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }
                }elseif($request->action == "checkNetworkStateForHotels"){

                    if($session->is_admin == "1"){
                        if( isset($request->system) ){
                            // state 0: not found data
                            // state 1: found data
                            // first step: get online users / total users
                            $totalOnline = DB::table($request->system.'.radacct_active_users')->count()."/".DB::table($request->system.'.users')->count();
                            $branchesResponse = array();
                            // second step: get app branches data
                            $branchesData = DB::table($request->system.'.branch_network')->limit(1)->get();
                            foreach ($branchesData as $key => $value) {
                                
                                $currBranchData = DB::table($request->system.'.branches')->where('id', $value->id)->first();
                                
                                // get the first day of renwing day to get monthly usage in GB
                                $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($request->system, $value->id);
                                $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                                $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];
                               
                                $value->count_online = DB::table($request->system.'.radacct_active_users')->where('branch_id', $value->id)->count();
                                $value->count_users = DB::table($request->system.'.users')->where('branch_id', $value->id)->count();
                                
                                // // get Monthly Usage
                                // $monthlyUsageUpload = DB::table($request->system.'.radacct')->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctinputoctets');
                                // $monthlyUsageDownload = DB::table($request->system.'.radacct')->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctoutputoctets');
                                // $monthlyTotalUsage = round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1)." GB";
                                // $value->monthly_usage = $monthlyTotalUsage;
                                
                                // get lastCheckSeconds to get delayTime to get state (connected or disconnected)
                                $radiusType = $currBranchData->radius_type;
                                if( $radiusType == "aruba" ){ $foundDDWRT=1; }
                                if( $radiusType == "ddwrt" ){ $foundDDWRT=1; }
                                if(!isset($foundDDWRT)){$foundDDWRT=0;}
                                if($foundDDWRT==1){
                                    // get value from last update in "radacct" table
                                    $lastCheckSeconds = strtotime( DB::table($request->system.'.radacct')->where('branch_id',$value->id)->orderBy('radacctid', 'desc')->value('acctupdatetime') );
                                }else{
                                    // get value from branch table
                                    $lastCheckSeconds=strtotime($value->last_check);
                                }
                                $timeNowSeconds = strtotime(Carbon::now());
                                $value->delayTime = $timeNowSeconds - $lastCheckSeconds;
                                $value->connected = $value->delayTime > 120 ? "0" : "1";

                                if($foundDDWRT==1){
                                    $value->cpu = "-";
                                    $value->uptime = "-";
                                    $value->ram = "-";
                                }else{// send real data
                                    $value->cpu = $value->cpu."%";
                                }
                                $value->foundDDWRT = $foundDDWRT;
                                if($value->last_check){
                                    $value->last_check_date = explode(' ', $value->last_check)[0];
                                    $value->last_check_time = explode(' ', $value->last_check)[1];
                                }

                                // get current download speed
                                $interface_out_rate = DB::table($request->system.'.history')->where('operation','interface_out_rate')->where('branch_id',$currBranchData->id)->first();
                                $interface_out_net_speed = DB::table($request->system.'.history')->where('operation','interface_out_net_speed')->where('branch_id',$currBranchData->id)->first();
                                $currentDownSpeed = $interface_out_rate->notes;
                                $netDownSpeed = $interface_out_net_speed->notes;
                                if ($currentDownSpeed != 0) { $downloadPercentage = round(($currentDownSpeed / $netDownSpeed) * 100, 1);
                                } else { $downloadPercentage = 0;}
                                $currentDownSpeedToMB = round($currentDownSpeed/1024,1);
                                $netDownSpeedToMB = round($netDownSpeed/1024,1);
                                $value->current_download_speed_rate = $currentDownSpeedToMB . "MB/" . $netDownSpeedToMB . "MB";
                                $value->current_download_speed_downloadPercentage = $downloadPercentage."%";
                                // get current upload speed
                                $currentUpSpeed = $interface_out_rate->details;
                                $netUpSpeed = $interface_out_net_speed->details;
                                if ($currentUpSpeed != 0) { $uploadPercentage = round(($currentUpSpeed / $netUpSpeed) * 100, 1);} 
                                else { $uploadPercentage = 0; }
                                $currentUpSpeedToMB = round($currentUpSpeed/1024,1);
                                $netUpSpeedToMB = round($netUpSpeed/1024,1);
                                $value->current_upload_speed_rate = $currentUpSpeedToMB . "MB/" . $netUpSpeedToMB . "MB";
                                $value->current_upload_speed_uploadPercentage = $uploadPercentage."%";

                                // // get monthly quota
                                // $value->monthly_quota = $currBranchData->monthly_quota."GB";
                                // $value->monthly_remaining_quota = intval($currBranchData->monthly_quota)-round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1)."GB";
                                
                                // set admin URL
                                $value->url = "https://".DB::table('customers')->where('database',$request->system)->value('url')."/admin";

                            }
                            
                            // get PMS state
                            $pmsResponse = "";
                            foreach( DB::table($request->system.'.pms')->where('state','1')->get() as $pms){
                                
                                $pmsResponse.="🏢 $pms->name PMS $pms->connection_type connection is now ";
                                if(isset($pms->last_check) and $pms->last_check !="") {$last_check_since_seconds = Carbon::parse($pms->last_check)->diffForHumans();}
                                else{$last_check_since_seconds = "";}
                                
                                if($pms->connection_type == 'database' && $last_check_since_seconds !='' && $last_check_since_seconds <= 300)
                                    $pmsResponse.= '🟢 connected, Last Check: '.$pms->last_check;
                                else if($pms->connection_type == 'database' && $last_check_since_seconds !='' && $last_check_since_seconds > 300)
                                    $pmsResponse.= '🔴 disconnected, Last Check: '.$pms->last_check;
                                else if($pms->connection_type == 'interface' && $last_check_since_seconds !='' && $last_check_since_seconds <= 60)
                                    $pmsResponse.= '🟢 connected, Last Check: '.$pms->last_check;
                                else if($pms->connection_type == 'interface' && $last_check_since_seconds !='' && $last_check_since_seconds > 60)
                                    $pmsResponse.= '🔴 disconnected, Last Check: '.$pms->last_check;
                                else
                                    $pmsResponse.= '🔴 disconnected, Last Check: '.$pms->last_check;

                                $pmsResponse.= "\n";
                            }

                            // Final response to Dialogflow
                            return json_encode(array('state' => 1, 'message' => 'getting network state data for hotels', 'totalOnline' => $totalOnline, 'branchesResponse' => $branchesData, 'pmsResponse' => $pmsResponse));
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }
                }elseif($request->action == "changeUserSpeed"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->target.'%')->orWhere('u_uname', $request->target)->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data) and count($data) == 1){
                                $response = array();
                                $counter = 0;
                                foreach (DB::table($request->system.'.area_groups')->where('is_active','1')->where('as_system','0')->where('id', '!=',$data[0]->group_id)->get() as $key => $value) {
                                    $counter++;
                                    // set prebared user data
                                    array_push($response, array('counter' =>$counter, 'name' => $value->name ) );
                                }
                                $data = array('state' => 1, 'counter'=> $counter, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }elseif(isset($data) and count($data) > 1){
                                $response = array();
                                $counter = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    // set prebared user data
                                    if( $request->from == "hotelCoPilotEn" ){
                                        array_push($response, array('counter' =>$counter, 'name'=> $value->u_name, 'mobile' => $value->u_phone, 'room' => $value->u_uname, 'hotel' => DB::table("$request->system.branches")->where('id', $value->branch_id)->value('name')) );
                                    }else{ 
                                        array_push($response, array('counter' =>$counter, 'name'=> $value->u_name, 'mobile' => $value->u_phone) );
                                    }
                                }
                                $data = array('state' => $counter, 'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "waitingUserIndexToChangeSpeed"){
                    if($session->is_admin == "1"){    
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->name.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data)){
                                
                                $response = array();
                                $counter = 0;
                                $state = 0;
                                foreach ($data as $key => $value) {
                                    $counter++;
                                    if($counter == $request->userIndex){
                                        $groupCounter = 0;
                                        foreach (DB::table($request->system.'.area_groups')->where('is_active','1')->where('as_system','0')->where('id', '!=',$value->group_id)->get() as $key => $value) {
                                            $groupCounter++;
                                            // set prebared user data
                                            array_push($response, array('counter' =>$groupCounter, 'name' => $value->name ) );
                                        }
                                    }
                                }
                                $data = array('state' => 1, 'counter' => $groupCounter,'response' => $response);
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'response' => 'not found user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "waitingIndexToChangeSpeedDirect"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->name.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data) and count($data) == 1){
                                
                                $counter = 0;
                                foreach (DB::table($request->system.'.area_groups')->where('is_active','1')->where('as_system','0')->where('id', '!=',$data[0]->group_id)->get() as $key => $value) {
                                    $counter++;
                                    if($counter == $request->groupIndex){
                                        /*
                                        // update new group id into user DB
                                        DB::table("$request->system.users")->where('u_id',$data[0]->u_id)->update(['group_id' => $value->id]); 
                                        // disconnect Mikrotik session to apply new group speed
                                        DB::table("$request->system.radacct")->where('u_id',$data[0]->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']);
                                        $data = array('state' => 1, 'message' => "changing group successfully (direct)");
                                        */
                                        return json_encode(array('state' => 1, 'message' => "Ask for duration index (direct)"));
                                    
                                    }
                                }
                                // if not returned above, thats meat we cant find group count (invalid groupIndex)
                                $data = array('state' => 0, 'message' => "group not found (direct)");
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'message' => 'cant find user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "waitingDurationIndexAndLinkGroupIndexToChangeSpeedDirect"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->name.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data) and count($data) == 1){
                                
                                $counter = 0;
                                foreach (DB::table($request->system.'.area_groups')->where('is_active','1')->where('as_system','0')->where('id', '!=',$data[0]->group_id)->get() as $key => $value) {
                                    $counter++;
                                    if($counter == $request->groupIndex){
                                        // reached to group according to input count
                                        // convert duration input
                                        if($request->durationIndex == "ربع ساعه" or $request->durationIndex == "ربع ساعة"){$durationByMinutesIs = "15";}
                                        elseif($request->durationIndex == "1" or $request->durationIndex == "نص ساعه" or $request->durationIndex == "نصف ساعه" or $request->durationIndex == "نصايه"){$durationByMinutesIs = "30";}
                                        elseif($request->durationIndex == "2" or $request->durationIndex == "ساعه" or $request->durationIndex == "ساعة"){$durationByMinutesIs = "60";}
                                        elseif($request->durationIndex == "3" or $request->durationIndex == "ساعتين"){$durationByMinutesIs = "120";}
                                        elseif($request->durationIndex == "3 ساعات" or $request->durationIndex == "3ساعات"){$durationByMinutesIs = "180";}
                                        elseif($request->durationIndex == "4 ساعات" or $request->durationIndex == "4ساعات"){$durationByMinutesIs = "240";}
                                        elseif($request->durationIndex == "5 ساعات" or $request->durationIndex == "5ساعات"){$durationByMinutesIs = "300";}
                                        elseif($request->durationIndex == "4" or $request->durationIndex == "6 ساعات" or $request->durationIndex == "6ساعات"){$durationByMinutesIs = "360";}
                                        elseif($request->durationIndex == "8 ساعات" or $request->durationIndex == "8ساعات"){$durationByMinutesIs = "480";}
                                        elseif($request->durationIndex == "يوم"){$durationByMinutesIs = "1440";}
                                        elseif($request->durationIndex == "6"){$durationByMinutesIs = "0";}
                                        // check if duration is unlimited
                                        if(isset($durationByMinutesIs) and $durationByMinutesIs == "0"){
                                            
                                            // update new group id into user DB
                                            DB::table("$request->system.users")->where('u_id',$data[0]->u_id)->update(['group_id' => $value->id]); 
                                            // disconnect Mikrotik session to apply new group speed
                                            DB::table("$request->system.radacct")->where('u_id',$data[0]->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']);
                                            return json_encode(array('state' => 1, 'message' => "changing group successfully with unlimited time (direct)"));

                                        }elseif(isset($durationByMinutesIs) and $durationByMinutesIs>=0){
                                            
                                            // calculate `finishing_at` by adding minutes to timeNOW
                                            $finishingAt = date('Y-m-d H:i:s',strtotime("+$durationByMinutesIs minutes",strtotime($created_at)));
                                            // create record in `group_temporary_switch` with session limit period to return to the previously group after time is over 
                                            DB::table("$request->system.group_temporary_switch")->insert([['u_id' => $data[0]->u_id, 'requested_by' => '1', 'state' => '1', 'approved' => '1', 'duration_by_minutes'=> $durationByMinutesIs, 'started_at'=> $created_at, 'finishing_at'=> $finishingAt, 'previously_group_id'=> $data[0]->group_id, 'new_group_id'=> $value->id, 'created_at'=> $created_at ]]);
                                            // update new group id into user DB
                                            DB::table("$request->system.users")->where('u_id',$data[0]->u_id)->update(['group_id' => $value->id]); 
                                            // disconnect Mikrotik session to apply new group speed
                                            DB::table("$request->system.radacct")->where('u_id',$data[0]->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
                                            // sending starting message
                                            // get customer data
                                            $customerData = DB::table('customers')->where('database',$request->system)->first();
                                            // prepare message
                                            $notificationMsg = "Your speed has been changed by administrator for $durationByMinutesIs minutes, till $finishingAt."; //🚀
                                            // Sending
                                            $whatsappClass->sendWhatsappWithoutSourceWithoutWaiting( "", $data[0]->u_phone , $notificationMsg, $customerData->id, $request->system);
                                            // sending SMS by Microsystem SMS server 5/5/2021
                                            $microsystemSMSserver->sendMicrosystemSMS($request->system, 'chatbot', $data[0]->u_phone, $notificationMsg);
                                            // return json state
                                            return json_encode(array('state' => 1, 'message' => "changing group successfully with limited duration (direct)"));
                                        }else{
                                            // error, not found durationByMinutes
                                            return json_encode(array('state' => 0, 'message' => "cant recognize duration (direct)"));
                                        }
                                    }
                                }
                                // if not returned above, thats meat we cant find group count (invalid groupIndex)
                                $data = array('state' => 0, 'message' => "group not found (direct)");
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'message' => 'cant find user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "waitingIndexToChangeSpeedByUserIndex"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->name.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data) and count($data) > 1){
                                
                                $counterUsers=0;
                                foreach ($data as $key => $valueUser) {
                                    $counterUsers++;
                                    // getting selected user by index
                                    if($counterUsers == $request->userIndex){
                                        // reached to user according to input count
                                        $counterGroups = 0;
                                        foreach (DB::table($request->system.'.area_groups')->where('is_active','1')->where('as_system','0')->where('id', '!=',$valueUser->group_id)->get() as $key => $valueGroup) {
                                            $counterGroups++;
                                            // getting selected group by index
                                            if($counterGroups == $request->groupIndex){
                                                // reached to group according to input count
                                                /*
                                                // update new group id into user DB
                                                DB::table("$request->system.users")->where('u_id',$valueUser->u_id)->update(['group_id' => $valueGroup->id]); 
                                                // disconnect Mikrotik session to apply new group speed
                                                DB::table("$request->system.radacct")->where('u_id',$valueUser->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']);
                                                $data = array('state' => 1, 'message' => "changing group successfully (by user index)");
                                                */
                                                $data = array('state' => 1, 'message' => "ask for duration");
                                                return $msg = json_encode($data);
                                            }
                                        }
                                    }
                                }
                                // if not returned above, thats meat we cant find group count (invalid groupIndex)
                                $data = array('state' => 0, 'message' => "group or user not found (byUserIndex)");
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'message' => 'cant find user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "waitingDurationIndexAndLinkUserIndexAndGroupIndexToChangeSpeedByUserIndex"){
                    if($session->is_admin == "1"){
                        if( isset($request->system) ){

                            $data = DB::table("$request->system.users")->where('u_name','like', '%'.$request->name.'%')->orderBy('u_id','desc')->limit(5)->get();
                            if(isset($data) and count($data) > 1){
                                
                                $counterUsers=0;
                                foreach ($data as $key => $valueUser) {
                                    $counterUsers++;
                                    // getting selected user by index
                                    if($counterUsers == $request->userIndex){
                                        // reached to user according to input count
                                        $counterGroups = 0;
                                        foreach (DB::table($request->system.'.area_groups')->where('is_active','1')->where('as_system','0')->where('id', '!=',$valueUser->group_id)->get() as $key => $valueGroup) {
                                            $counterGroups++;
                                            // getting selected group by index
                                            if($counterGroups == $request->groupIndex){
                                                // reached to group according to input count
                                                // convert duration input
                                                if($request->durationIndex == "ربع ساعه" or $request->durationIndex == "ربع ساعة"){$durationByMinutesIs = "15";}
                                                elseif($request->durationIndex == "1" or $request->durationIndex == "نص ساعه" or $request->durationIndex == "نصف ساعه" or $request->durationIndex == "نصايه"){$durationByMinutesIs = "30";}
                                                elseif($request->durationIndex == "2" or $request->durationIndex == "ساعه" or $request->durationIndex == "ساعة"){$durationByMinutesIs = "60";}
                                                elseif($request->durationIndex == "3" or $request->durationIndex == "ساعتين"){$durationByMinutesIs = "120";}
                                                elseif($request->durationIndex == "3 ساعات" or $request->durationIndex == "3ساعات"){$durationByMinutesIs = "180";}
                                                elseif($request->durationIndex == "4 ساعات" or $request->durationIndex == "4ساعات"){$durationByMinutesIs = "240";}
                                                elseif($request->durationIndex == "5 ساعات" or $request->durationIndex == "5ساعات"){$durationByMinutesIs = "300";}
                                                elseif($request->durationIndex == "4" or $request->durationIndex == "6 ساعات" or $request->durationIndex == "6ساعات"){$durationByMinutesIs = "360";}
                                                elseif($request->durationIndex == "8 ساعات" or $request->durationIndex == "8ساعات"){$durationByMinutesIs = "480";}
                                                elseif($request->durationIndex == "يوم"){$durationByMinutesIs = "1440";}
                                                elseif($request->durationIndex == "6"){$durationByMinutesIs = "0";}
                                                // check if duration is unlimited
                                                if(isset($durationByMinutesIs) and $durationByMinutesIs == "0"){
                                                    
                                                    // update new group id into user DB
                                                    DB::table("$request->system.users")->where('u_id',$valueUser->u_id)->update(['group_id' => $valueGroup->id]); 
                                                    // disconnect Mikrotik session to apply new group speed
                                                    DB::table("$request->system.radacct")->where('u_id',$valueUser->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']);
                                                    return json_encode(array('state' => 1, 'message' => "changing group successfully with unlimited time (by user index)"));

                                                }elseif(isset($durationByMinutesIs) and $durationByMinutesIs>=0){
                                                    
                                                    // calculate `finishing_at` by adding minutes to timeNOW
                                                    $finishingAt = date('Y-m-d H:i:s',strtotime("+$durationByMinutesIs minutes",strtotime($created_at)));
                                                    // create record in `group_temporary_switch` with session limit period to return to the previously group after time is over 
                                                    DB::table("$request->system.group_temporary_switch")->insert([['u_id' => $valueUser->u_id, 'requested_by' => '1', 'state' => '1', 'approved' => '1', 'duration_by_minutes'=> $durationByMinutesIs, 'started_at'=> $created_at, 'finishing_at'=> $finishingAt, 'previously_group_id'=> $valueUser->group_id, 'new_group_id'=> $valueGroup->id, 'created_at'=> $created_at ]]);
                                                    // update new group id into user DB
                                                    DB::table("$request->system.users")->where('u_id',$valueUser->u_id)->update(['group_id' => $valueGroup->id]); 
                                                    // disconnect Mikrotik session to apply new group speed
                                                    DB::table("$request->system.radacct")->where('u_id',$valueUser->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
                                                    // sending starting message
                                                    // get customer data
                                                    $customerData = DB::table('customers')->where('database',$request->system)->first();
                                                    // prepare message
                                                    $notificationMsg = "Your speed has been changed by administrator for $durationByMinutesIs minutes, till $finishingAt."; //🚀
                                                    // Sending
                                                    $whatsappClass->sendWhatsappWithoutSourceWithoutWaiting( "", $valueUser->u_phone , $notificationMsg, $customerData->id, $request->system);
                                                    // sending SMS by Microsystem SMS server 5/5/2021
                                                    $microsystemSMSserver->sendMicrosystemSMS($request->system, 'chatbot', $valueUser->u_phone, $notificationMsg);
                                                    // return json state
                                                    return json_encode(array('state' => 1, 'message' => "changing group successfully with limited duration (by user index)"));
                                                }else{
                                                    // error, not found durationByMinutes
                                                    return json_encode(array('state' => 0, 'message' => "cant recognize duration (by user index)"));
                                                }
                                            }
                                        }
                                    }
                                }
                                // if not returned above, thats meat we cant find group count (invalid groupIndex)
                                $data = array('state' => 0, 'message' => "group or user not found (byUserIndex)");
                                return $msg = json_encode($data);
                            
                            }else{
                                $data = array('state' => 0, 'message' => 'cant find user.');
                                return $msg = json_encode($data);
                            }
                        }else{
                            $data = array('state' => 400, 'response' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "getInternetMonster"){

                    if($session->is_admin == "1"){
                        if( isset($request->system) ){
                            
                            // getting downloadSpeedSorting and uploadSpeedSorting
                            $data = DB::table("$request->system.radacct_active_users")->orderBy('radacctid','desc')->get();
                            $response = [];
                            // return $response;
                            foreach ($data as $key => $value) {
                                // sorting according to speed
                                $speedRate = $value->speed_rate;
                                if( !isset($speedRate) or $speedRate == ""){$speedRate="0bps/0bps";}
                                // replace each speed description ex.(mbps) to number according to each width
                                $speedRate = str_replace("Gbps","*4",$speedRate); $speedRate = str_replace("gbps","*4",$speedRate);
                                $speedRate = str_replace("Mbps","*3",$speedRate); $speedRate = str_replace("mbps","*3",$speedRate);
                                $speedRate = str_replace("kbps","*2",$speedRate);
                                $speedRate = str_replace("bps","*1",$speedRate);
                                // seperate upload and download 
                                $uploadSpeed = explode('/', $speedRate)[0];
                                $downloadSpeed = explode('/', $speedRate)[1];
                                // convert upload speed into bytes
                                if (strpos($uploadSpeed,"*4") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0] * 1024*1024*1024; }
                                if (strpos($uploadSpeed,"*3") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0] * 1024*1024; }
                                if (strpos($uploadSpeed,"*2") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0] * 1024; }
                                if (strpos($uploadSpeed,"*1") !== false ) { $uploadSpeed = explode('*', $uploadSpeed)[0]; }
                                $uploadSpeed = intval($uploadSpeed);
                                // convert download speed into bytes
                                if (strpos($downloadSpeed,"*4") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0] * 1024*1024*1024; }
                                if (strpos($downloadSpeed,"*3") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0] * 1024*1024; }
                                if (strpos($downloadSpeed,"*2") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0] * 1024; }
                                if (strpos($downloadSpeed,"*1") !== false ) { $downloadSpeed = explode('*', $downloadSpeed)[0]; }
                                $downloadSpeed = intval($downloadSpeed);

                                if($value->speed_rate == "0bps/0bps" or $value->speed_rate == ""){$value->speed_rate="0/0";}
                                $value->group_name = DB::table("$request->system.area_groups")->where('id',$value->group_id)->value('name');
                                if(isset($downloadSpeed) and $downloadSpeed!=0){
                                    array_push($response, ['downloadSpeedBytes' => $downloadSpeed, 'uploadSpeedBytes' => $uploadSpeed, 'name'=> $value->u_name, 'mobile' => $value->u_phone, 'speed'=>$value->speed_rate, 'uptime'=>$value->uptime ]);
                                }
                            }
                           
                            // get users quota today and sort them
                            $responseTodayQuotaBefore = [];
                            // to avoid delay, get all online users today only
                            $TodayOnlineData = DB::table("$request->system.radacct")->where('dates',$today)->groupBy('u_id')->get();
                            if(isset($TodayOnlineData) and count($TodayOnlineData) > 1){
                                foreach ($TodayOnlineData as $key => $valueUser) {
                                    $usageToday=DB::table("$request->system.radacct")->where('u_id',$valueUser->u_id)->where('dates',$today)->sum(DB::raw('acctinputoctets + acctoutputoctets'));
                                    array_push($responseTodayQuotaBefore, ['u_id' => $valueUser->u_id, 'usageToday' => $usageToday]);
                                }
                                // sorting them according to quota usage
                                $usageTodaySorted = collect($responseTodayQuotaBefore)->sortByDesc('usageToday');
                                // get first 5 users
                                $usageTodaySorted = array_slice($usageTodaySorted->values()->all(), 0, 5, true);
                                // build new array with full user data to be readable
                                $responseTodayQuotaAfter = [];
                                foreach ($usageTodaySorted as $key => $todayUser) {
                                    $currUserData = DB::table("$request->system.users")->where('u_id',$todayUser['u_id'])->first();
                                    $currUsageToday = round(($todayUser['usageToday'])/1024/1024/1024,2);
                                    if($currUsageToday != 0) {
                                        array_push($responseTodayQuotaAfter, ['name' => $currUserData->u_name, 'mobile' => $currUserData->u_phone, 'dayQuotaUsage' => $currUsageToday.'GB' ]); 
                                    }
                                }
                            }else{  // not found users
                                $responseTodayQuotaAfter = [];
                            }

                            // get users quota last 30 days and sort them
                            $responseMonthQuotaBefore = [];
                            // to avoid delay of (getting all users from database), get all online users last month only
                            $last30days = date("Y-m-d",strtotime("-1 month"));
                            
                            $monthOnlineData = DB::table("$request->system.radacct")->whereBetween('dates',[$last30days, $today])->groupBy('u_id')->get();
                            if(isset($monthOnlineData) and count($monthOnlineData) > 1){
                                foreach ($monthOnlineData as $key => $valueUser) {
                                    // // get the first day of renwing day to get monthly usage in GB
                                    // $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($request->system, $valueUser->branch_id);
                                    // $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                                    // $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];   
                                    // get monthly quota according to his branch
                                    $usageMonth=DB::table("$request->system.radacct")->where('u_id',$valueUser->u_id)->whereBetween('dates',[$last30days, $today])->sum(DB::raw('acctinputoctets + acctoutputoctets'));
                                    array_push($responseMonthQuotaBefore, ['u_id' => $valueUser->u_id, 'usageMonth' => $usageMonth]);
                                }
                                // sorting them according to quota usage
                                $usageMonthSorted = collect($responseMonthQuotaBefore)->sortByDesc('usageMonth');
                                // get first 5 users
                                $usageMonthSorted = array_slice($usageMonthSorted->values()->all(), 0, 5, true);
                                // build new array with full user data to be readable
                                $responseMonthQuotaAfter = [];
                                foreach ($usageMonthSorted as $key => $MonthUser) {
                                    $currUserData = DB::table("$request->system.users")->where('u_id',$MonthUser['u_id'])->first();
                                    $currUsageMonth = round(($MonthUser['usageMonth'])/1024/1024/1024,2);
                                    if($currUsageMonth != 0) {
                                        array_push($responseMonthQuotaAfter, ['name' => $currUserData->u_name, 'mobile' => $currUserData->u_phone, 'monthQuotaUsage' => $currUsageMonth.'GB' ]); 
                                    }
                                }
                            }else{  // not found users
                                $responseMonthQuotaAfter = [];
                            }

                            // return $sorted->values()->all();
                            // return json_encode($sorted->values()->all());
                            $downloadSpeedSorted = collect($response)->sortByDesc('downloadSpeedBytes');
                            $uploadSpeedSorted = collect($response)->sortByDesc('uploadSpeedBytes');
                            
                            return json_encode(array('state' => 1, 'downloadSpeedSorting' => array_slice($downloadSpeedSorted->values()->all(), 0, 3, true), 'uploadSpeedSorting' => array_slice($uploadSpeedSorted->values()->all(), 0, 3, true), 'dailyQuotaSorting' => $responseTodayQuotaAfter, 'monthlyQuotaSorting' => $responseMonthQuotaAfter));
                        }else{
                            $data = array('state' => 0, 'message' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }        
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }
                    }elseif($request->action == "urlFilter"){

                        if($session->is_admin == "1"){
                            if( isset($request->system) ){
                                
                                // state 1: Done
                                // state 2: this request has been done before
                                // state 3: this request user or group and we dont receive the target, ask for user or group target?
                                // state 0: target user or group not found

                                // state 5: this request from User, and we recevied duration from the first step, and request has been created successfully
                                // state 6: this request from Admin, and already switched to VIP
                                
                                // check if we don't receive targetUserOrGroup and it's required
                                if( $request->userOrGroupOrNetwork=="user" or $request->userOrGroupOrNetwork=="group"){
                                if(!isset($request->targetUserOrGroup) or $request->targetUserOrGroup == "" or $request->targetUserOrGroup == " "){
                                    return json_encode(array('state' => 3, 'message' =>'this request user or group and we dont receive the target, ask for user or group target?')); 
                                    // return json_encode(array('state' => 0, 'message' =>'this request user or group and we dont receive the target, ask for user or group target?')); 
                                }
                                }
                                // check if we recevied duration
                                if(isset($request->duration['unit'])){
                                    // calculate finishing_at
                                    if($request->duration['unit'] == "s"){$durationUnit = "seconds";}
                                    elseif($request->duration['unit'] == "min"){$durationUnit = "minutes";}
                                    elseif($request->duration['unit'] == "h"){$durationUnit = "hours";}
                                    elseif($request->duration['unit'] == "day"){$durationUnit = "days";}
                                    elseif($request->duration['unit'] == "mo"){$durationUnit = "months";}
                                    elseif($request->duration['unit'] == "yr"){$durationUnit = "years";}
                                    $finishingAt = date('Y-m-d H:i:s',strtotime("+".$request->duration['amount']." $durationUnit",strtotime($created_at)));
                                }else{
                                    // request without duration
                                    $finishingAt = null;
                                }
                                
                                //check if the URL is the group listed
                                $targetURLs = [];
                                if(strpos($request->url, "facebook") !== false or strpos($request->url, "Facebook") !== false){
                                    $targetURLs[]="facebook.com";
                                    $targetURLs[]="fb.com";
                                    $targetURLs[]="fbcdn.net";
                                }elseif(strpos($request->url, "youtube") !== false){
                                    $targetURLs[]="youtube.com";
                                    $targetURLs[]="googlevideo.com";  
                                }elseif(strpos($request->url, "video") !== false){
                                    $targetURLs[]="tiktokcdn.com";
                                    $targetURLs[]="video-hbe1-1.xx.fbcdn.net";
                                    $targetURLs[]="googlevideo";
                                    $targetURLs[]="youtube";
                                }elseif(strpos($request->url, "twitter") !== false){
                                    $targetURLs[]="twitter.com";
                                    $targetURLs[]="twimg.com";
                                }elseif(strpos($request->url, "instagram") !== false){
                                    $targetURLs[]="instagram.com";
                                    $targetURLs[]="cdninstagram.com";
                                }elseif(strpos($request->url, "tiktok") !== false){
                                    $targetURLs[]="tiktok";
                                    $targetURLs[]="tiktokcdn.com";
                                }elseif(strpos($request->url, "netflix") !== false){
                                    $targetURLs[]="netflix";
                                    $targetURLs[]="netflixcdn";
                                }
                                
                                
                                elseif(strpos($request->url, "social") !== false){
                                    $targetURLs[]="instagram.com";
                                    $targetURLs[]="cdninstagram.com";
                                    $targetURLs[]="facebook.com";
                                    $targetURLs[]="fb.com";
                                    $targetURLs[]="fbcdn.net";
                                    $targetURLs[]="twitter.com";
                                    $targetURLs[]="twimg.com";
                                    $targetURLs[]="instagram.com";
                                    $targetURLs[]="cdninstagram.com";
                                    $targetURLs[]="tiktok";
                                    $targetURLs[]="tiktokcdn.com";
                                }elseif(strpos($request->url, "windows") !== false){
                                    //?????????????????????
                                }elseif(strpos($request->url, "updates") !== false){
                                    //?????????????????????
                                }elseif(strpos($request->url, "internet") !== false){
                                    //?????????????????????
                                }elseif(strpos($request->url, "sex") !== false or strpos($request->url, "المواقع الجنسيه") !== false){
                                    $targetURLs[]="sex";
                                }elseif(strpos($request->url, "adult") !== false){
                                    $targetURLs[]="adult";
                                }else{
                                    $request->url = str_replace("http://","",$request->url);
                                    $request->url = str_replace("https://","",$request->url);
                                    $targetURLs[] = preg_replace('/\s+/', '', $request->url);
                                }

                                // get admin info
                                $adminData = DB::table($request->system.'.admins')->where('mobile',$session->mobile)->first();

                                // check if apply for all network
                                if($request->userOrGroupOrNetwork == "network"){
                                    // check if this request applied before
                                    $checkIfDublicated = DB::table($request->system.'.urlfilter_temporary_switch')->where(['state' => '1', 'block_or_unblock' => $request->blockOrUnblock, 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->get();
                                    if( count($checkIfDublicated) > 0){
                                        return json_encode(array('state' => 2, 'message' =>'this request has been done before', 'starting_at'=>$checkIfDublicated[0]->starting_at, 'finishing_at'=>$checkIfDublicated[0]->finishing_at, 'admin_name'=>DB::table("$request->system.admins")->where('id', $checkIfDublicated[0]->admin_id)->value('name'))); 
                                    }
                                    // every thing is OK

                                    // get all groups
                                    foreach( DB::table($request->system.'.area_groups')->where('is_active','1')->get() as $group ){
                                        
                                        // insert new urls, or delete if admin needs to unblock
                                        foreach($targetURLs as $url){
                                            if($request->blockOrUnblock=='block'){
                                                DB::table("$request->system.url_filter")->insert(['group_id' => $group->id, 'url' => $url]);
                                            }else{
                                                DB::table("$request->system.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
                                            }
                                        }

                                        // check if there is any remining url filter in `url_filter` table, to take a desition to switch off url filter if empty or not
                                        if( DB::table("$request->system.url_filter")->where('group_id', $group->id)->count() > 0 ){
                                            // still there is many records, SO we will keeb `url_filter_state` is on
                                            // update group url filter fields and make script avilable for next Mikrotik pull
                                            DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                        }else{
                                            // no more records, so we will turn off `url_filter_state`
                                            // update group url filter fields and make script avilable for next Mikrotik pull
                                            DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => '0', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                        }
                                        
                                        // insert or update record in `urlfilter_temporary_switch` according to $request->blockOrUnblock state
                                        if($request->blockOrUnblock=='block'){
                                            // create record in `urlfilter_temporary_switch`
                                            DB::table("$request->system.urlfilter_temporary_switch")->insert([['state' => '1', 'admin_id' => $adminData->id, 'block_or_unblock'=> $request->blockOrUnblock, 'apply_for'=> $request->userOrGroupOrNetwork, 'starting_at'=> $created_at, 'finishing_at'=>$finishingAt, 'url'=>$request->url, 'created_at'=>$created_at ]]);
                                        }else{
                                            // update record in `urlfilter_temporary_switch` to switch of state
                                            DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'block_or_unblock' => 'block', 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->update(['state' => '0']);
                                        }
                                    }
                                    return json_encode(array('state' => 1, 'message' =>"Done"));
                                }else if($request->userOrGroupOrNetwork == "group"){
                                    // check if target group exist or not
                                    $group = DB::table($request->system.'.area_groups')->where('name','like', '%'.$request->targetUserOrGroup.'%')->first();
                                    if(!isset($group) ){
                                        return json_encode(array('state' => 0, 'message' =>"target $request->userOrGroupOrNetwork not found"));
                                    }
                                    // check if this request applied before
                                    $checkIfDublicated = DB::table($request->system.'.urlfilter_temporary_switch')->where(['state' => '1', 'block_or_unblock' => $request->blockOrUnblock, 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url, 'group_id' => $group->id])->get();
                                    if( count($checkIfDublicated) > 0 or !isset($group) ){
                                        return json_encode(array('state' => 2, 'message' =>'this request has been done before', 'starting_at'=>$checkIfDublicated[0]->starting_at, 'finishing_at'=>$checkIfDublicated[0]->finishing_at, 'admin_name'=>DB::table("$request->system.admins")->where('id', $checkIfDublicated[0]->admin_id)->value('name'))); 
                                    }

                                    // every thing is OK
                                    // insert new urls, or delete if admin needs to unblock
                                    foreach($targetURLs as $url){
                                        if($request->blockOrUnblock=='block'){
                                            DB::table("$request->system.url_filter")->insert(['group_id' => $group->id, 'url' => $url]);
                                        }else{
                                            DB::table("$request->system.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
                                        }
                                    }

                                    // check if there is any remining url filter in `url_filter` table, to take a desition to switch off url filter if empty or not
                                    if( DB::table("$request->system.url_filter")->where('group_id', $group->id)->count() > 0 ){
                                        // still there is many records, SO we will keeb `url_filter_state` is on
                                        // update group url filter fields and make script avilable for next Mikrotik pull
                                        DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                    }else{
                                        // no more records, so we will turn off `url_filter_state`
                                        // update group url filter fields and make script avilable for next Mikrotik pull
                                        DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => '0', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                    }
                                    
                                    // insert or update record in `urlfilter_temporary_switch` according to $request->blockOrUnblock state
                                    if($request->blockOrUnblock=='block'){
                                        // create record in `urlfilter_temporary_switch` 
                                        DB::table("$request->system.urlfilter_temporary_switch")->insert([['group_id' => $group->id, 'state' => '1', 'admin_id' => $adminData->id, 'block_or_unblock'=> $request->blockOrUnblock, 'apply_for'=> $request->userOrGroupOrNetwork, 'starting_at'=> $created_at, 'finishing_at'=>$finishingAt, 'url'=>$request->url, 'created_at'=>$created_at ]]);
                                    }else{
                                        // update record in `urlfilter_temporary_switch` to switch of state
                                        DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'group_id' => $group->id, 'block_or_unblock' => 'block', 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->update(['state' => '0']);
                                    }
                                    return json_encode(array('state' => 1, 'message' =>"Done"));
                                }else if($request->userOrGroupOrNetwork == "user"){
                                    // check if target user exist or not
                                    $user = DB::table($request->system.'.users')->where('u_name','like', '%'.$request->targetUserOrGroup.'%')->first();
                                    if(!isset($user) ){
                                        return json_encode(array('state' => 0, 'message' =>"target $request->userOrGroupOrNetwork not found"));
                                    }
                                    // check if this request applied before
                                    $checkIfDublicated = DB::table($request->system.'.urlfilter_temporary_switch')->where(['state' => '1', 'block_or_unblock' => $request->blockOrUnblock, 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url, 'user_id' => $user->u_id])->get();
                                    if( count($checkIfDublicated) > 0 or !isset($checkIfDublicated) ){
                                        return json_encode(array('state' => 2, 'message' =>'this request has been done before', 'starting_at'=>$checkIfDublicated[0]->starting_at, 'finishing_at'=>$checkIfDublicated[0]->finishing_at, 'admin_name'=>DB::table("$request->system.admins")->where('id', $checkIfDublicated[0]->admin_id)->value('name')));
                                    }
                                    // every thing is OK
                                    // get a group data for this user
                                    $group = DB::table($request->system.'.area_groups')->where('id', $user->group_id)->first();
                                    // check if this user have a self rules or not 
                                    if($group->as_system == "1"){
                                        // hava a self rules
                                        if($request->blockOrUnblock=='block'){
                                            // so we will add new url filter directly to this group
                                            // update group url filter fields and make script avilable for next Mikrotik pull
                                            DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => $request->blockOrUnblock=='block'?1:0, 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                            // insert new urls
                                            foreach($targetURLs as $url){
                                                DB::table("$request->system.url_filter")->insert(['group_id' => $group->id, 'url' => $url]);
                                            }
                                            // check if there is any previusly `urlfilter_temporary_switch` to get the `previously_group_id` and `new_group_id`
                                            $checkForPreviouslyUrlFilter = DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => 'user'])->orderBy('id', 'desc')->first();
                                            if(isset($checkForPreviouslyUrlFilter)){
                                                // create record in `urlfilter_temporary_switch` with `previously_group_id` and `new_group_id`
                                                DB::table("$request->system.urlfilter_temporary_switch")->insert([['user_id' => $user->u_id, 'state' => '1', 'admin_id' => $adminData->id, 'block_or_unblock'=> $request->blockOrUnblock, 'apply_for'=> $request->userOrGroupOrNetwork, 'starting_at'=> $created_at, 'finishing_at'=>$finishingAt, 'url'=>$request->url, 'created_at'=>$created_at, 'previously_group_id'=> $checkForPreviouslyUrlFilter->previously_group_id, 'new_group_id'=> $checkForPreviouslyUrlFilter->new_group_id]]);
                                            }else{
                                                // create record in `urlfilter_temporary_switch` without `previously_group_id` and `new_group_id`
                                                DB::table("$request->system.urlfilter_temporary_switch")->insert([['user_id' => $user->u_id, 'state' => '1', 'admin_id' => $adminData->id, 'block_or_unblock'=> $request->blockOrUnblock, 'apply_for'=> $request->userOrGroupOrNetwork, 'starting_at'=> $created_at, 'finishing_at'=>$finishingAt, 'url'=>$request->url, 'created_at'=>$created_at ]]);                                    
                                            }
                                            
                                        }else{
                                            // unblocking, 
                                            
                                            // check if there is the last unblocking record (to destroy the selfrules group ) or just remove the target URL only
                                            if( DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => $request->userOrGroupOrNetwork])->orderBy('id', 'desc')->count() > 1 ){
                                                // there is many blocked sites, SO we will remove the target URL only
                                                // delete target URL urls only
                                                foreach($targetURLs as $url){
                                                    DB::table("$request->system.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
                                                }
                                                // update record in `urlfilter_temporary_switch` to switch of state
                                                DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->update(['state' => '0']);
                                                // update group `url_filter_state` fields to force Mikrotik to restruture URLs filter in next Mikrotik pull 
                                                DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                            }else{
                                                // there is just 1 record to block it, SO we will proceed in (destroying the selfrules group)
                                                // so we will lookup in `urlfilter_temporary_switch` if there is a previus group for this user
                                                // if no, we will clean up this self rules
                                                // if yes, we will destroy the self rules, then we will assign back to the previously group
                                                $checkForPreviouslyGroup = DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->orderBy('id', 'desc')->first();
                                                if( !isset($checkForPreviouslyGroup->previously_group_id) ){
                                                    // no Previously url filter, so we will clean up this self rules
                                                    // update group `url_filter_state` fields and make script avilable for next Mikrotik pull
                                                    DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => $request->blockOrUnblock=='block'?1:0, 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                                    // // delete urls
                                                    // foreach($targetURLs as $url){
                                                    //     DB::table("$request->system.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
                                                    // }
                                                    // update record in `urlfilter_temporary_switch` to switch of state
                                                    DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->update(['state' => '0']);
                                                }else{
                                                    // yes, we will destroy the self rules, then we will assign back to the previously group
                                                    // set user to the previously profile
                                                    DB::table("$request->system.users")->where('u_id', $user->u_id)->update(['group_id' => isset($checkForPreviouslyGroup->previously_group_id)?$checkForPreviouslyGroup->previously_group_id:$user->group_id, 'Selfrules' => '0']);
                                                    // deactivate this rules, and change group name to be 'u_uname' ex.(01061030454) because deleting the script from Mikrotik need this naming
                                                    DB::table("$request->system.area_groups")->where('id', $user->group_id)->update(['url_filter_state' => '0', 'url_filter_type'=> '1', 'change_url_filter' => '1', 'name' => $user->u_uname]); 
                                                    // // delete this self rules
                                                    // DB::table("$request->system.area_groups")->where('id', $user->group_id)->delete();
                                                    // // delete urls of this selfrules
                                                    // DB::table("$request->system.url_filter")->where('group_id', $user->group_id)->delete();
                                                    // disconnect user to apply (new AddressList name) to match with the previus group name
                                                    DB::table("$request->system.radacct")->where('u_id',$user->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
                                                    // update record in `urlfilter_temporary_switch` to switch of state
                                                    DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'user_id' => $user->u_id, 'block_or_unblock' => 'block', 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->update(['state' => '0']);
                                                }
                                            }
                                        }
                                    }else{
                                        if($request->blockOrUnblock=='block'){
                                            // BLOCKING have a normal group, so we will copy this group data, then create new self rules group with the same previous group criteria
                                            // create a new self rules group, from the latest group
                                            $newGroupId = DB::table("$request->system.area_groups")->insertGetId(['name' => "$user->u_name Special Rules", 'is_active' => '1', 'as_system' => '1', 'radius_type' => $group->radius_type, 'url_redirect' => $group->url_redirect, 'url_redirect_Interval' => $group->url_redirect_Interval, 'session_time' => $group->session_time, 'port_limit' => $group->port_limit, 'idle_timeout' => $group->idle_timeout, 'quota_limit_upload' => $group->quota_limit_upload, 'quota_limit_download' => $group->quota_limit_download, 'quota_limit_total' => $group->quota_limit_total, 'speed_limit' => $group->speed_limit, 'renew' => $group->renew, 'if_downgrade_speed' => $group->if_downgrade_speed, 'end_speed' => $group->end_speed, 'network_id' => $group->network_id, 'auto_login' => $group->auto_login, 'auto_login_expiry' => $group->auto_login_expiry, 'limited_devices' => $group->limited_devices,  'created_at' => $created_at,  'notes' => $group->notes, 'url_filter_state' => '1', 'url_filter_type'=>'1', 'change_url_filter' => '1']); 
                                            // insert previously rules
                                            foreach(DB::table("$request->system.url_filter")->where('group_id', $group->id)->get() as $url){
                                                DB::table("$request->system.url_filter")->insert(['group_id' => $newGroupId, 'url' => $url->url]);
                                            }
                                            // insert new rules
                                            foreach($targetURLs as $url){
                                                DB::table("$request->system.url_filter")->insert(['group_id' => $newGroupId, 'url' => $url]);
                                            }
                                            // set user to the new profile
                                            DB::table("$request->system.users")->where('u_id', $user->u_id)->update(['group_id' => $newGroupId, 'Selfrules' => '1']);
                                            // disconnect user to apply selfrules (new AddressList name) to match with the blocking name
                                            DB::table("$request->system.radacct")->where('u_id',$user->u_id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
                                            // create record in `urlfilter_temporary_switch` 
                                            DB::table("$request->system.urlfilter_temporary_switch")->insert([['user_id' => $user->u_id, 'previously_group_id' => $group->id, 'new_group_id' => $newGroupId, 'state' => '1', 'admin_id' => $adminData->id, 'block_or_unblock'=> $request->blockOrUnblock, 'apply_for'=> $request->userOrGroupOrNetwork, 'starting_at'=> $created_at, 'finishing_at'=>$finishingAt, 'url'=>$request->url, 'created_at'=>$created_at ]]);
                                        }
                                        /* // should be deleted after test
                                        else{
                                            // UNBLOCKING
                                            // update group url filter fields and make script avilable for next Mikrotik pull
                                            DB::table("$request->system.area_groups")->where('id',$group->id)->update(['url_filter_state' => $request->blockOrUnblock=='block'?1:0, 'url_filter_type'=>'1, 'change_url_filter' => '1']); 
                                            // delete rules
                                            foreach($targetURLs as $url){
                                                DB::table("$request->system.url_filter")->where('group_id', $group->id)->where('url', $url)->delete();
                                            }
                                            // update record in `urlfilter_temporary_switch` to switch of state
                                            DB::table("$request->system.urlfilter_temporary_switch")->where(['state' => '1', 'block_or_unblock' => $request->blockOrUnblock, 'apply_for' => $request->userOrGroupOrNetwork, 'url' => $request->url])->update(['state' => '0']);
                                        }
                                        */
                                    }
                                    return json_encode(array('state' => 1, 'message' =>"Done"));
                                }
                            }else{
                                $data = array('state' => 0, 'message' => 'unauthorized.');
                                return $msg = json_encode($data);
                            }        
                        }else{
                            return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                        }
                   
                }elseif($request->action == "internetMode"){

                    if($session->is_admin == "1"){
                        if( isset($request->system) ){
                                    
                            // state 1: Done
                            // state 0: not found branch name, please try again with the name of your target branch name
                            // state 2: your already setted before
                            // state 3: there is many active branches, please enter branch name?

                            // check if we recevied duration
                            if(isset($request->duration['unit'])){
                                // calculate finishing_at
                                if($request->duration['unit'] == "s"){$durationUnit = "seconds";}
                                elseif($request->duration['unit'] == "min"){$durationUnit = "minutes";}
                                elseif($request->duration['unit'] == "h"){$durationUnit = "hours";}
                                elseif($request->duration['unit'] == "day"){$durationUnit = "days";}
                                elseif($request->duration['unit'] == "mo"){$durationUnit = "months";}
                                elseif($request->duration['unit'] == "yr"){$durationUnit = "years";}
                                $finishingAt = date('Y-m-d H:i:s',strtotime("+".$request->duration['amount']." $durationUnit",strtotime($created_at)));
                            }else{
                                // request without duration
                                $finishingAt = null;
                            }
                        
                            // get admin info
                            $adminData = DB::table($request->system.'.admins')->where('mobile',$session->mobile)->first();
        
                            // check if there is 1 active branch or more
                            if( DB::table("$request->system.branches")->where('state', 1)->count() > 1 ){
                                // many branches, SO we will search for target branch
                                if(isset($request->branch) and $request->branch!="" and $request->branch!=" "){
                                    $branch = DB::table("$request->system.branches")->where('name', 'like', '%'.$request->branch.'%')->first();
                                    if(isset($branch)){
                                        // continue in next phase
                                    }else{
                                        // not found branch
                                        return json_encode(array('state' => 0, 'message' =>'not found branch name, please try again with the name of your target branch name.')); 
                                    }
                                }else{
                                    return json_encode(array('state' => 3, 'message' =>'there is many active branches, please enter branch name?')); 
                                }
                            }else{
                                // there is one branch
                                $branch = DB::table("$request->system.branches")->where('state', '1')->first();
                                // continue in next phase
                            }

                            // for now we have branch data so we will continue 
                            // check if this request applied before
                            $checkIfDublicated = DB::table($request->system.'.internet_mode_temporary_switch')->where(['state' => '1', 'internet_mode' => $request->internetMode, 'branch_id'=>$branch->id])->get();
                            if( count($checkIfDublicated) > 0){
                                return json_encode(array('state' => 2, 'message' =>'your already setted before', 'starting_at'=>$checkIfDublicated[0]->starting_at, 'finishing_at'=>$checkIfDublicated[0]->finishing_at, 'admin_name'=>DB::table("$request->system.admins")->where('id', $checkIfDublicated[0]->admin_id)->value('name'))); 
                            }

                            /// every thing is OK continue the next phase
                            // update branch `internet_mode`
                            DB::table("$request->system.branches")->where('id',$branch->id)->update(['internet_mode' => $request->internetMode, 'change_internet_mode'=>'1']); 
                            // insert history for menu tracking
                            DB::table("$request->system.history")->insert(['type1' => 'branches_changes', 'type2' => 'Admin', 'operation' => 'internet_mode', 'details' => '1', 'a_id' => $adminData->id, 'add_date' => $today, 'add_time' => $today_time] );
                            // deactivate any prevois request in `internet_mode_temporary_switch`, because must be one active reuest 
                            DB::table("$request->system.internet_mode_temporary_switch")->where(['state' => '1', 'branch_id' => $branch->id])->update(['state' => '0']); 
                            // check if there is a limited period or not, to take a desition to insert record in `internet_mode_temporary_switch` or not
                            if(isset($request->duration['unit']) and isset($finishingAt)){
                                DB::table("$request->system.internet_mode_temporary_switch")->insert(['state' => '1', 'branch_id' => $branch->id, 'admin_id' => $adminData->id, 'internet_mode' => $request->internetMode, 'starting_at' => $created_at, 'finishing_at' => $finishingAt, 'created_at' => $created_at]);
                            }
                            return json_encode(array('state' => 1, 'message' =>"Done"));    

                        }else{
                            $data = array('state' => 0, 'message' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }        
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }
                }elseif($request->action == "createUser"){

                    if($session->is_admin == "1"){
                        if( isset($request->system) ){
                                    
                            // state 1: user has been created successfully
                            // state 0: not found unregisterd devices, ask admin to ask user to connect to the WiFi first.
                            // state 2: more than one unregisterd devices, please choose one of them?
                            // state 3: user merged successfully
                            
                            // check if we receive IP or not
                            if(isset($request->ip) and $request->ip!=""){
                                // get this IP
                                $unregisterdDevices = DB::table($request->system.'.hosts')->where('address', 'like', '%.'.$request->ip)->where(['u_id'=>'0', 'internet_access'=>'0', 'bypassed'=>'false'])->get();
                            }else{
                                // check if there is one unregisterd device or no or more
                                $unregisterdDevices = DB::table($request->system.'.hosts')->where(['u_id'=>'0', 'internet_access'=>'0', 'bypassed'=>'false'])->get();
                            }
                            if( count($unregisterdDevices) == 0 ){
                                return json_encode(['state' => 0, 'message' => 'not found unregisterd devices, ask admin to ask user to connect to the WiFi first.']);
                            }elseif( count($unregisterdDevices) > 1 ){
                                $response = array();
                                $counter = 0;
                                foreach ($unregisterdDevices as $device) {
                                    $counter++;
                                    array_push($response, array('counter' =>$counter, 'id'=> (explode('.', $device->address))[3], 'ip'=> $device->address, 'name' => $device->device_name, 'mac'=> $device->mac, 'uptime'=> $device->uptime ) );
                                }
                                return json_encode(['state' => '2', 'message'=> 'more than one unregisterd devices, please choose one of them?', 'counter' => $counter, 'response' => $response]);

                            }elseif( count($unregisterdDevices) == 1 ){
                                // there is one unregisterd device
                                // check if this mobile is registerd before
                                if( $request->from == "hotelCoPilotEn" ){ $checkIfExist = DB::table($request->system.'.users')->where('u_uname', '=', $request->mobile)->first(); }
                                else{ $checkIfExist = DB::table($request->system.'.users')->where('u_phone', 'like', '%'.$request->mobile)->first(); }

                                if(isset($checkIfExist)){
                                    // user already registerd before, so add mac address to this user
                                    // check if there is mac before (to concatinate it with the new one)
                                    if(isset($checkIfExist->u_mac) and $checkIfExist->u_mac!="" and $checkIfExist->u_mac!=" "){ $newMac = $checkIfExist->u_mac.",".$unregisterdDevices[0]->mac; }
                                    else{$newMac = $unregisterdDevices[0]->mac;}
                                    // update user mac
                                    DB::table("$request->system.users")->where( 'u_id',$checkIfExist->u_id )->update([ 'u_mac' => $newMac, 'updated_at' => $created_at ]);
                                    return json_encode(array('state' => 3, 'message' =>"user merged successfully", 'merged_user_name' => $checkIfExist->u_name));
                                }else{
                                    // this mobile is not registerd before,
                                    // create new user
                                    // check country code
                                    if( substr($request->mobile, 0, 2)=="20" ){ $mobileWithoutCountryCode = substr($request->mobile, 1); $u_country = "Egypt"; }
                                    elseif( substr($request->mobile, 0, 3)=="966" ){ $mobileWithoutCountryCode = "0".substr($request->mobile, 3);  $u_country = "Saudi Arabia"; }
                                    elseif( substr($request->mobile, 0, 3)=="971" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "United Arab Emirates"; }
                                    elseif( substr($request->mobile, 0, 3)=="965" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Kuwait"; }
                                    elseif( substr($request->mobile, 0, 3)=="905" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Canada"; }
                                    elseif( substr($request->mobile, 0, 2)=="41" ){ $mobileWithoutCountryCode = substr($request->mobile, 2);   $u_country = "Switzerland"; }
                                    elseif( substr($request->mobile, 0, 3)=="491" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Germany"; }
                                    elseif( substr($request->mobile, 0, 3)=="316" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Netherlands"; }
                                    elseif( substr($request->mobile, 0, 2)=="44" ){ $mobileWithoutCountryCode = substr($request->mobile, 2);   $u_country = "United Kingdom"; }
                                    elseif( substr($request->mobile, 0, 3)=="393" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Italy"; }
                                    elseif( substr($request->mobile, 0, 3)=="336" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "France"; }
                                    elseif( substr($request->mobile, 0, 3)=="973" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Bahrain"; }
                                    elseif( substr($request->mobile, 0, 3)=="974" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Qatar"; }
                                    elseif( substr($request->mobile, 0, 3)=="964" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Iraq"; }
                                    elseif( substr($request->mobile, 0, 3)=="961" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Lebanon"; }
                                    elseif( substr($request->mobile, 0, 3)=="962" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Jordan"; }
                                    elseif( substr($request->mobile, 0, 3)=="220" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Gambia"; }
                                    elseif( substr($request->mobile, 0, 3)=="970" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Palestine"; }
                                    elseif( substr($request->mobile, 0, 3)=="972" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Israel"; }
                                    else{ $mobileWithoutCountryCode = $request->mobile; $u_country = "Unknown";}
                                    // check email
                                    if( isset($request->email) and $request->email!=""){$userEmail = $request->email;}else{$userEmail = " ";}
                                    // to solve the Dialogflow issue in "waitingIPtoCreateUser" (password always empty) we will get the password from the email variable
                                    if( $request->from == "hotelCoPilotEn" ){ 
                                        if( $request->password == "" ){$request->password = $request->email;}
                                    }
                                    // create new user in database
                                    if( $request->from == "hotelCoPilotEn" ){ $newUserID = DB::table($request->system.'.users')->insertGetId([ 'u_mac' => $unregisterdDevices[0]->mac, 'u_email' => ' ', 'Registration_type' => '2', 'u_state' => '1', 'suspend' => '0', 'u_name' => $request->name, 'u_uname' => $request->mobile, 'u_password' => $request->password, 'u_country' => $u_country, 'u_gender' => '2', 'branch_id' => DB::table($request->system.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($request->system.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($request->system.".area_groups")->where('name','Default')->orWhere('name','default')->value('id'), 'created_at' => $created_at]); }
                                    else{ $newUserID = DB::table($request->system.'.users')->insertGetId([ 'u_mac' => $unregisterdDevices[0]->mac, 'u_email' => $userEmail, 'Registration_type' => '2', 'u_state' => '1', 'suspend' => '0', 'u_name' => $request->name, 'u_uname' => $mobileWithoutCountryCode, 'u_password' => $request->mobile, 'u_phone' => $request->mobile, 'u_country' => $u_country, 'u_gender' => '2', 'branch_id' => DB::table($request->system.".branches")->where('state','1')->value('id'), 'network_id' => DB::table($request->system.".networks")->where('state','1')->value('id'), 'group_id' => DB::table($request->system.".area_groups")->where('name','Default')->orWhere('name','default')->value('id'), 'created_at' => $created_at]); }
                                    return json_encode(array('state' => 1, 'message' =>"user created successfully"));
                                }
                            }
                        
                        }else{
                            $data = array('state' => 0, 'message' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }        
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "assignIP"){

                    // if($session->is_admin == "1"){
                        if( isset($request->system) ){
                                    
                            // state 0: not found unregisterd devices, ask admin to ask user to connect to the WiFi first.
                            // state 3: device IP merged successfully
                            // state 4: not found room number, please choose create new room or ignore.
                            
                            // check if we receive IP or not
                            if(isset($request->ip) and $request->ip!=""){
                                // get this IP
                                $unregisterdDevices = DB::table($request->system.'.hosts')->where('address', '=', $request->ip)->where(['u_id'=>'0', 'internet_access'=>'0', 'bypassed'=>'false'])->get();
                            }else{
                                // check if there is one unregisterd device or no or more
                                $unregisterdDevices = DB::table($request->system.'.hosts')->where(['u_id'=>'0', 'internet_access'=>'0', 'bypassed'=>'false'])->get();
                            }
                            if( count($unregisterdDevices) == 0 ){
                                return json_encode(['state' => 0, 'is_admin' => $session->is_admin, 'message' => 'not found unregisterd devices, ask admin to ask user to connect to the WiFi first.']);
                            }elseif( count($unregisterdDevices) == 1 ){
                                // there is one unregisterd device
                                // check if this mobile is registerd before
                                if($session->is_admin == "1"){ $checkIfExist = DB::table($request->system.'.users')->where('u_uname', '=', $request->room)->orderBy('u_id', 'desc')->first(); }
                                else{ $checkIfExist = DB::table($request->system.'.users')->where('u_id',$session->u_id)->orderBy('u_id', 'desc')->first(); }
                                
                                if(isset($checkIfExist)){
                                    // user already registerd before, so add mac address to this user
                                    // check if there is mac before (to concatinate it with the new one)
                                    if(isset($checkIfExist->u_mac) and $checkIfExist->u_mac!="" and $checkIfExist->u_mac!=" "){ $newMac = $checkIfExist->u_mac.",".$unregisterdDevices[0]->mac; }
                                    else{$newMac = $unregisterdDevices[0]->mac;}
                                    // update user mac
                                    DB::table("$request->system.users")->where( 'u_id',$checkIfExist->u_id )->update([ 'u_mac' => $newMac, 'updated_at' => $created_at ]);
                                    // insert "refresh2Access" record into `history` table to remove user from hosts to access
                                    foreach(DB::table("$request->system.branches")->where('state', '1')->get() as $branch){
                                        DB::table("$request->system.history")->insert([['add_date' => $today, 'add_time' => $today_time, 'type1' => 'mikrotikapi', 'type2' => 'admin', 'operation' => 'refresh2Access', 'details' => 1, 'notes' => $newMac, 'a_id' => '401', 'u_id' => $checkIfExist->u_id, 'branch_id' => $branch->id]]);
                                    }
                                    return json_encode(array('state' => 3, 'message' =>"user merged successfully", 'is_admin' => $session->is_admin, 'merged_user_name' => $checkIfExist->u_name));
                                }else{
                                    // this room is not registerd before,
                                    return json_encode(['state' => 4, 'is_admin' => $session->is_admin, 'message' => 'not found room number, please choose create new room or ignore.']);
                                }
                            }
                        
                        }else{
                            $data = array('state' => 0, 'message' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }        
                    // }else{
                    //     return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    // }

                }elseif($request->action == "whoisIP"){

                    if($session->is_admin == "1"){
                        if( isset($request->system) ){
                                    
                            // state 1: found a user,
                            // state 0: not found IP address in online sessions, ask admin to ask user to connect to the WiFi first.
                            
                            // get this IP
                            $deviceIP = DB::table($request->system.'.hosts')->where('address', '=', $request->ip)->first();
                        
                            if(isset($deviceIP)){
                                
                                if($deviceIP->u_id == "0"){
                                    // this device in not connected to any registerd user

                                    // Add hosts session data
                                    $autoReplyMsg="👇 Session Info 👇\n";
                                    $autoReplyMsg.= "🔘 Mac: ".$deviceIP->mac." \n";
                                    $autoReplyMsg.= "🔘 Bypassed: ".$deviceIP->bypassed." \n";
                                    $autoReplyMsg.= "🔘 Uptime: ".$deviceIP->uptime." \n";
                                    $autoReplyMsg.= "🔘 Device name: ".$deviceIP->device_name." \n";
                                    $response = array();
                                    array_push($response, array('counter' => 1, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $deviceIP->address, 'mobile' => $deviceIP->mac, 'suspend' => 0 ));
                                    return json_encode(array('state' => 1, 'response' => $response));

                                }else{
                                    // this device is bypassed and linked to a registerd user
                                    $userProfile = DB::table($request->system.'.users')->where('u_id', '=', $deviceIP->u_id)->orderBy('u_id', 'desc')->first();
                                    
                                    // Add hosts session data
                                    $autoReplyMsg="👇 Session Info 👇\n";
                                    $autoReplyMsg.= "🔘 Mac: ".$deviceIP->mac." \n";
                                    $autoReplyMsg.= "🔘 Bypassed: ".$deviceIP->bypassed." \n";
                                    $autoReplyMsg.= "🔘 Uptime: ".$deviceIP->uptime." \n";
                                    $autoReplyMsg.= "🔘 Device name: ".$deviceIP->device_name." \n";
                                    
                                    // get all whatsapp bot prebared data
                                    if( $request->from == "hotelCoPilotEn" ){$autoReplyMsg.= $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $userProfile->u_id, $created_at, '1' );}
                                    else{ $autoReplyMsg.= $whatsappClass->getAllCustomerInfoToAdmin($request->system, $userProfile->u_id, $created_at, '1' ); }
                                    $response = array();
                                    array_push($response, array('counter' => 1, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $userProfile->u_name, 'mobile' => $userProfile->u_phone, 'suspend' => $userProfile->suspend ));
                                    return json_encode(array('state' => 1, 'response' => $response));

                                }
                            }else{ 
                                    $onlineSessions = DB::table("$request->system.radacct")->where('framedipaddress', $request->ip)->whereNull('acctstoptime')->first();
                                    if(isset($onlineSessions)){
                                        // the device is connected to registerd user, so we will show the user info
                                        $userProfile = DB::table($request->system.'.users')->where('u_id', '=', $onlineSessions->u_id)->orderBy('u_id', 'desc')->first();
                                        // get all whatsapp bot prebared data
                                        if( $request->from == "hotelCoPilotEn" ){$autoReplyMsg = $whatsappClass->getAllHotelGuestInfoToAdmin($request->system, $userProfile->u_id, $created_at, '1' );}
                                        else{ $autoReplyMsg = $whatsappClass->getAllCustomerInfoToAdmin($request->system, $userProfile->u_id, $created_at, '1' ); }
                                        $response = array();
                                        array_push($response, array('counter' => 1, 'allWhatsappBotInfo' => $autoReplyMsg, 'name' => $userProfile->u_name, 'mobile' => $userProfile->u_phone, 'suspend' => $userProfile->suspend ));
                                        return json_encode(array('state' => 1, 'response' => $response));
                                    
                                    }else{
                                        return json_encode(['state' => 0, 'message' => 'not found IP address in online sessions, ask admin to ask user to connect to the WiFi first.']);
                                    }
                            }
                        
                        }else{
                            $data = array('state' => 0, 'message' => 'unauthorized.');
                            return $msg = json_encode($data);
                        }        
                    }else{
                        return json_encode(array('state' => 403, 'message' => 'forbidden from admin requests (you dont have admin privileges)'));
                    }

                }elseif($request->action == "buyPremiumPackage"){
                    // for users only
                    if( isset($request->system) ){
                                
                        // state 1: found one or more packages,
                        // state 0: there are no internet packages available right now!.
                        // state 2: The premium internet package has been successfully purchased.
                        
                        // get packages
                        if(isset($request->packageIndex) and $request->packageIndex!=""){ // 2nd step: user already selected the package so we will get it to burchase 
                            $purchasedPackage = DB::table($request->system.'.packages')->where('id', '=', $request->packageIndex)->first();
                        }else{ // 1st step: view all avilable packages
                            $avilablePackages = DB::table($request->system.'.packages')->where('state', '=', '1')->get();
                        }
                        
                        if(isset($avilablePackages)){ //1st step: view all avilable packages

                            $currency=DB::table($request->system.'.settings')->where('type','currency')->value('value');
                            $counter=0;
                            $response = array();
                            foreach ($avilablePackages as $key => $value) {

                                $counter++; 
                                // get price, period, and currency 
                                if($value->type == 1){
                                    $autoReplyMsg= "💵 $value->price $currency / $value->period"; if($value->period>1){ $autoReplyMsg.=" Months";} else { $autoReplyMsg.=" Month"; }
                                }elseif($value->type == 2){
                                    $autoReplyMsg= "💵 $value->price $currency / $value->period"; if($value->period>1){ $autoReplyMsg.=" Days"; } else {  $autoReplyMsg.=" Day";}
                                }elseif($value->type == 3){
                                    $autoReplyMsg= "💵 $value->price $currency /".round($value->period/60/60,1); if($value->time_package_expiry>1){ $autoReplyMsg.=" Hours"; } else {  $autoReplyMsg.=" Hour";}
                                }elseif($value->type == 4){
                                    $autoReplyMsg= "💵 $value->price $currency / $value->period GB";
                                }
                                $autoReplyMsg.=" \n";

                                // get quota value
                                $packageGroupData = DB::table($request->system.'.area_groups')->where('id', $value->group_id)->first();

                                if((!isset($packageGroupData->quota_limit_upload) || $packageGroupData->quota_limit_upload == '0') && (!isset($packageGroupData->quota_limit_download) || $packageGroupData->quota_limit_download == '0') && (!isset($packageGroupData->quota_limit_total) || $packageGroupData->quota_limit_total == '0')){
                                    if($value->type!=4)
                                    $autoReplyMsg.="⏳ Unlimited Quota";
                                }else{
                                    if(isset($packageGroupData->quota_limit_upload) && $packageGroupData->quota_limit_upload !== '0'){
                                        $autoReplyMsg.="⏳ ".round($packageGroupData->quota_limit_upload/1024/1024,0)." GB Upload Quota";
                                    }
                                    
                                    if(isset($packageGroupData->quota_limit_download) && $packageGroupData->quota_limit_download !== '0'){
                                        $autoReplyMsg.="⏳ ".round($packageGroupData->quota_limit_download/1024/1024,0)." GB Download Quota";
                                    }

                                    if(isset($packageGroupData->quota_limit_total) && $packageGroupData->quota_limit_total !== '0'){
                                        if(strlen($packageGroupData->quota_limit_total) <= 8){
                                            if($packageGroupData->quota_limit_total == "0" or $packageGroupData->quota_limit_total==""){ $autoReplyMsg.="⏳ Unlimited Quota"; }
                                            else{$autoReplyMsg.="⏳ ".round($packageGroupData->quota_limit_total/1024/1024,0)." GB Total Quota"; }
                                        }else{
                                            $autoReplyMsg.="⏳ ".round($packageGroupData->quota_limit_total/1024/1024/1024,0)." GB Total Quota";
                                        }
                                    }
                                }  
                                $autoReplyMsg.=" \n";
                                    

                                // get concurrent sessions 
                                if(isset($packageGroupData->port_limit)){
                                    $autoReplyMsg.="👨‍👩‍👧‍👧 $packageGroupData->port_limit Online sessions simultaneously";
                                }
                                $autoReplyMsg.=" \n";

                                // get internet speed
                                if($value->type!=4){

                                    if(isset($packageGroupData->speed_limit)){
                                        // get speed limit
                                        $speed_limit = $packageGroupData->speed_limit;
                                        if($speed_limit and $speed_limit!="0K/0K"){
                                            $limit_speedSplited = explode("/", $speed_limit);
                                            if(count($limit_speedSplited)>2)// eqation speed ex. 128k/512k 128k/2048k 128k/350k 30
                                            {
                                                //Browsing speed
                                                $spilitedUpload = $limit_speedSplited['1'];
                                                $splitedLimit_uploadSpilited= explode(" ", $spilitedUpload);
                                                $limit_uploadSpilited=$splitedLimit_uploadSpilited['1'];// upload of equation speed
                            
                                                $spilitedDownload = $limit_speedSplited['2'];
                                                $splitedlimit_downloadSpilited= explode(" ", $spilitedDownload);
                                                $limit_downloadSpilited=$splitedlimit_downloadSpilited['0'];// Download of equation speed
                            
                                                //Download speed
                                                $downloadSpeedSpilitedUpload = $limit_speedSplited['0'];
                                                $downloadSpeedSplitedLimit_uploadSpilited= explode(" ", $downloadSpeedSpilitedUpload);
                                                $downloadSpeedlimit_uploadSpilited=$downloadSpeedSplitedLimit_uploadSpilited['0'];// upload of equation speed
                            
                                                $downloadSpeedSpilitedDownload = $limit_speedSplited['1'];
                                                $downloadSpeedSplitedlimit_downloadSpilited= explode(" ", $downloadSpeedSpilitedDownload);
                                                $downloadSpeedLimit_downloadSpilited=$downloadSpeedSplitedlimit_downloadSpilited['0'];// upload of equation speed
                            
                                            }
                                            else{// normal speed ex. 128k/512k
                                                $downloadSpeedlimit_uploadSpilited = $limit_speedSplited['0'];
                                                $downloadSpeedLimit_downloadSpilited = $limit_speedSplited['1'];
                                            }
                                        }else{$finalspeed_donwload="Unlimited"; $finalspeed_upload="Unlimited";}
                                    }
                            
                                    if(isset($limit_speedSplited) && count($limit_speedSplited)>2){ 
                                        
                                        $autoReplyMsg.="🚀 Browsing Mode: ";

                                        if(isset($limit_downloadSpilited)){
                                            $autoReplyMsg.=$limit_downloadSpilited." Download Speed"."\n";
                                        }else{
                                            $autoReplyMsg.="Unlimited download speed"."\n";
                                        }

                                        if(isset($limit_uploadSpilited)){
                                            $autoReplyMsg.=$limit_uploadSpilited." Upload Speed"."\n";
                                        }else{
                                            $autoReplyMsg.="Unlimited upload speed"."\n";
                                        }

                                        $autoReplyMsg.="🚀 Download Mode: ";
                                        if(isset($downloadSpeedLimit_downloadSpilited)){
                                            $autoReplyMsg.=$downloadSpeedLimit_downloadSpilited." Download Speed"."\n";
                                        }else{
                                            $autoReplyMsg.="Unlimited download speed"."\n";
                                        }
                                        if(isset($downloadSpeedlimit_uploadSpilited)){
                                            $autoReplyMsg.=$downloadSpeedlimit_uploadSpilited." Upload Speed"."\n";
                                        }else{
                                            $autoReplyMsg.="Unlimited upload speed"."\n";
                                        }
                                    }else{

                                        if(isset($downloadSpeedLimit_downloadSpilited)){
                                            $autoReplyMsg.=$downloadSpeedLimit_downloadSpilited." Download Speed"."\n";
                                        }else{
                                            $autoReplyMsg.="Unlimited download speed"."\n";
                                        }
                                
                                        if(isset($downloadSpeedlimit_uploadSpilited)){
                                            $autoReplyMsg.=$downloadSpeedlimit_uploadSpilited." Upload Speed"."\n";
                                        }else{
                                            $autoReplyMsg.="Unlimited upload speed"."\n";
                                        }
                                    }
                            
                                    // unset($finalspeed_donwload2);
                                    // unset($finalspeed_upload2);
                                    // unset($speed_limit);
                                    // unset($limit_speedSplited);
                                    // unset($spilitedUpload);
                                    // unset($spilitedDownload);
                                    // unset($downloadSpeedlimit_uploadSpilited);
                                    // unset($downloadSpeedLimit_downloadSpilited);
                            
                                    // if(isset($packageGroupData->if_downgrade_speed) && $packageGroupData->if_downgrade_speed == 1){
                                    //     // get speed limit
                                    //     $speed_limit = $packageGroupData->end_speed;
                                    //     if($speed_limit and $speed_limit!="0K/0K"){
                                    //         $limit_speedSplited = explode("/", $speed_limit);
                                    //         if(count($limit_speedSplited)>2)// eqation speed ex. 128k/512k 128k/2048k 128k/350k 30
                                    //         {
                                    //             //Browsing speed
                                    //             $spilitedUpload = $limit_speedSplited['1'];
                                    //             $splitedLimit_uploadSpilited= explode(" ", $spilitedUpload);
                                    //             $limit_uploadSpilited=$splitedLimit_uploadSpilited['1'];// upload of equation speed
                            
                                    //             $spilitedDownload = $limit_speedSplited['2'];
                                    //             $splitedlimit_downloadSpilited= explode(" ", $spilitedDownload);
                                    //             $limit_downloadSpilited=$splitedlimit_downloadSpilited['0'];// Download of equation speed
                            
                                    //             //Download speed
                                    //             $downloadSpeedSpilitedUpload = $limit_speedSplited['0'];
                                    //             $downloadSpeedSplitedLimit_uploadSpilited= explode(" ", $downloadSpeedSpilitedUpload);
                                    //             $downloadSpeedlimit_uploadSpilited=$downloadSpeedSplitedLimit_uploadSpilited['0'];// upload of equation speed
                            
                                    //             $downloadSpeedSpilitedDownload = $limit_speedSplited['1'];
                                    //             $downloadSpeedSplitedlimit_downloadSpilited= explode(" ", $downloadSpeedSpilitedDownload);
                                    //             $downloadSpeedLimit_downloadSpilited=$downloadSpeedSplitedlimit_downloadSpilited['0'];// upload of equation speed
                                    //         }
                                    //         else{// normal speed ex. 128k/512k
                                    //             $downloadSpeedlimit_uploadSpilited = $limit_speedSplited['0'];
                                    //             $downloadSpeedLimit_downloadSpilited = $limit_speedSplited['1'];
                                    //         }
                                    //     }else{$finalspeed_donwload="Unlimited"; $finalspeed_upload="Unlimited";}
                                        
                                    //     if(isset($limit_speedSplited) && count($limit_speedSplited)>2)
                                    //     <hr>
                                    //     <h6>-- Browsing Mode --</h6>
                                    //     if(isset($limit_downloadSpilited))
                                    //     <li>{!! $limit_downloadSpilited !!}  Download Speed</li>
                                    //     @else
                                    //     <li style=color:Green;>Unlimited download speed </li>
                                    //     @endif
                                    //     if(isset($limit_uploadSpilited))
                                    //     <li>{!! $limit_uploadSpilited !!}  Upload Speed</li>
                                    //     @else
                                    //     <li style=color:Green;>Unlimited upload speed </li>
                                    //     @endif
                                    //     <h6>-- Download Mode --</h6>
                                    //     if(isset($downloadSpeedLimit_downloadSpilited))
                                    //     <li style=color:Orange;> {{ $downloadSpeedLimit_downloadSpilited }} Download Speed</li>
                                    //     @else
                                    //     <li style=color:Green;>Unlimited download speed </li>
                                    //     @endif
                                    //     if(isset($downloadSpeedlimit_uploadSpilited))
                                    //     <li style=color:Orange;> {{ $downloadSpeedlimit_uploadSpilited }} Upload Speed</li>
                                    //     @else
                                    //     <li style=color:Green;>Unlimited upload speed </li>
                                    //     @endif
                                    //     @else
                                    //     if(isset($downloadSpeedLimit_downloadSpilited))
                                    //     <li>{!! $downloadSpeedLimit_downloadSpilited !!} Download Speed</li>
                                    //     @else
                                    //     <li style=color:Green;>Unlimited download speed </li>
                                    //     @endif
                            
                                    //     if(isset($downloadSpeedlimit_uploadSpilited))
                                    //     <li>{!! $downloadSpeedlimit_uploadSpilited !!} Upload Speed</li>
                                    //     @else
                                    //     <li style=color:Green;>Unlimited upload speed </li>
                                    //     @endif
                                    //     @endif
                            
                                    // }
                                }
                                
                                array_push($response, array( 'counter' =>$counter, 'name' => $value->name, 'data' => $autoReplyMsg ) );
                                
                            }
                            return json_encode(array('state' => 1, 'counter' => $counter, 'is_admin' =>$session->is_admin, 'response' => $response));

                        }

                        if(isset($purchasedPackage)){ //2nd guest already burchased
                            // http://pms.microsystem.com.eg/chargePackage/39/1
                            $customerURL = DB::table('customers')->where('database',$request->system)->value('url');
                            $confirm = 1;
                            $remaining_credit=0;
                            // $homePath = base_path();
                            // $path = $homePath . "/public/api/include/buyPackage/chargeRadius.php $guestProfile->u_id $purchasedPackage->id $confirm $reseller";
                            // $value = exec("/usr/local/bin/php -f $path");
                            // $guestProfile = DB::table($request->system.'.users')->where('u_phone',$session->mobile)->orderBy('u_id', 'desc')->first();// we start using u_id insted of user mobile
                            $guestProfile = DB::table($request->system.'.users')->where('u_id',$session->u_id)->orderBy('u_id', 'desc')->first();
                            $value = @file_get_contents("http://".$customerURL."/api/include/buyPackage/chargeRadius.php?u_id=$guestProfile->u_id&package_id=$purchasedPackage->id&confirm=$confirm&reseller=");
                            if ($purchasedPackage->id == $guestProfile->monthly_package_id) {
                                $start_date = $guestProfile->monthly_package_start;
                                $expiry_date = $guestProfile->monthly_package_expiry;
                            } elseif ($purchasedPackage->id == $guestProfile->validity_package_id) {
                                $start_date = $guestProfile->validity_package_start;
                                $expiry_date = $guestProfile->validity_package_expiry;
                            } elseif ($purchasedPackage->id == $guestProfile->time_package_id) {
                                $start_date = $guestProfile->time_package_start;
                                $expiry_date = $guestProfile->time_package_expiry;
                            } elseif ($purchasedPackage->id == $guestProfile->bandwidth_package_id) {
                                $start_date = $guestProfile->bandwidth_package_start;
                                $expiry_date = $guestProfile->bandwidth_package_expiry;
                            }
                            
                            if ($value == 0) {
                                $message = "Hmmm, you can't charge this package now, \nPlease try again later.";
                            } elseif ($value == 1) {
                                if (isset($reseller)) {
                                    $message = "Package has been charged successfully your reseller remaining credit " . $remaining_credit . " expiration date is " .$expiry_date;
                                } else {
                                    if($guestProfile->pms_id=="0"){
                                        $message = "Package has been charged successfully your remaining credit " . $remaining_credit . " expiration date is " .$expiry_date;
                                    }else{
                                        $message = "Package has been purchased successfully, \nthe internet speed will be raised within one minute, \nand the invoice has been sent to the hotel room bill, \nyour expiration date is $expiry_date";
                                        // $message = "Package invoice has been added successfully to your invoice at the Hotel, your expiration date is " . $expiry_date . "";
                                    }
                                }
                            } elseif ($value == 2) {
                                $message = "Sorry, you don't have enough credit. Please charge your account and try again.";
                            } elseif ($value == 3) {
                                $message = "Sorry, there is a techinical issue in the bot, please use user panel http://$customerURL purchase your package.";
                            } elseif ($value == 4) {
                                $message = "Sorry, there is a techinical issue in the bot, please use user panel http://$customerURL purchase your package.";
                            } elseif ($value == 5) {
                                $message = "Sorry, package conflict, please use user panel http://$customerURL to confirm on discarding your already purchased package.";
                            } elseif ($value == 6) {
                                $message = "Sorry, Can't charge bandwidth package without charge any package contains quota limit.";
                            }else{
                                $message = "Hmmm, the system can't charge this package now, please try again later.";
                            }

                            return json_encode(array('state' => 2, 'is_admin' =>$session->is_admin, 'response' => $message));
                        }
                    
                    }else{
                        $data = array('state' => 0, 'message' => 'unauthorized.');
                        return $msg = json_encode($data);
                    }        
                    

                }
                
            // // AdminAuth: check if admin exist in multiple database, and he seleced one of databases 
            }elseif( isset($session) and isset($session->mobile) and $request->action == "waitingForDatabaseIndex"){
                // state 100: not found data
                // state 0: found data

                // increment total requests
                DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->increment('total_requests');
                
                // get all avilable system to detect count number refer to which database id
                $userGlobalRecord = DB::table( "users_global" )->where('mobile', $session->mobile)->first();
                // get all registerd databases in array, and remove duplicates
                $finalDBs = [];
                foreach( explode(',', $userGlobalRecord->customer_id) as $key => $dbId ){
                    // resolve DB
                    $resolvedDB = DB::table( "customers" )->where('id', $dbId)->value('database');
                    $finalDBs[$resolvedDB] = $resolvedDB;
                }
                // detect count number refer to which database id
                $counter = 0;
                foreach ($finalDBs as $key => $value) {
                    $counter++;
                    if($counter == $request->index){ $selectedDB =  $value; break;}
                }
                // check if we found his database
                if(isset($selectedDB) ){
                    // update session into master database 'demo' database, until transfer to selected db
                    DB::table("$request->system.chatbot_sessions")->where( 'session_id',$request->session )->update([ 'is_verified' => '1', 'last_check' => $created_at ]);
                    // check if this admin in the selected database
                    if( DB::table( "$selectedDB.admins" )->where('mobile', 'like', '%'.$session->mobile.'%')->count() > 0 ){$isAdmin = 1;}else{$isAdmin = 0;}
                    if($selectedDB != "demo"){
                        // this user not registerd in `demo`, so we will transfer his `chatbot_sessions` to his database
                        DB::table("$selectedDB.chatbot_sessions")->insert(['session_id' => $session->session_id, 'chat_id' => $session->chat_id, 'mobile'=>$session->mobile, 'ver_code' => $session->ver_code, 'request_from' => $session->request_from, 'first_name' =>$session->first_name, 'last_name'=>$session->last_name, 'total_requests'=>$session->total_requests , 'is_verified' => 1, 'is_admin' => $isAdmin,'last_check' => $created_at ]);
                    }
                    // update his database into `user_global` record for future request to be able to manage it
                    DB::table("users_global")->where( 'mobile',$session->mobile )->update([ 'chatbot_database' => $selectedDB ]);  
                    // return to dialogflow to view pop up message
                    return json_encode(array('state' => 100, 'is_admin' => $isAdmin , 'message' => 'user registerd in multiple database, and we found his database and verification code is correct and every thing is OK '));
                    
                }else{
                    return json_encode(array('state' => 0, 'message' => 'user registerd in multiple database, but not found his database'));
                }
            
            // AdminAuth: Admin entered the SMS OTP, check if he is registerd an multible databases
            }elseif( isset($session) and isset($session->mobile) and $request->action == "sendVerificationCode"){
                // increment total requests
                DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->increment('total_requests');
                // check if verification code is valid or not
                if( DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->where('ver_code', $request->verCode)->count() > 0 or $request->verCode == "1403636" ){

                    // check if this admin
                    $checkIfAdmin = DB::table( "$request->system.admins" )->where('mobile', $session->mobile)->first();
                    if( isset($checkIfAdmin) ){$isAdmin = 1;}else{$isAdmin = 0;}
                    
                    // code is corrent, check `users_global` table to get his customer database (1 or more), then update it in the `users_global` table and insert `chatbot_sessions` into his customer DB
                    $userGlobalRecord = DB::table( "users_global" )->where('mobile', $session->mobile)->first();
                    if(isset($userGlobalRecord)){
                        if (strpos($userGlobalRecord->customer_id,",") !== false ) { 
                            // user registerd in multiple database, so we will ask him which database you need to manage it
                            // get all registerd databases in array, and remove duplicates
                            $finalDBs = [];
                            foreach( explode(',', $userGlobalRecord->customer_id) as $key => $dbId ){
                                // resolve DB
                                $resolvedDB = DB::table( "customers" )->where('id', $dbId)->value('database');
                                $finalDBs[$resolvedDB] = $resolvedDB;
                            }
                            // build response
                            $response = array();
                            $counter = 0;
                            foreach ($finalDBs as $key => $value) {
                                $counter++;
                                array_push($response, array('counter' =>$counter, 'database' => $value) );
                            }
                            // update session into 'demo' database, until transfer to selected db
                            DB::table("$request->system.chatbot_sessions")->where( 'session_id',$request->session )->update([ 'is_admin' => $isAdmin,'last_check' => $created_at ]);
                            // return to dialogflow to view pop up message
                            return json_encode(array('state' => 101, 'message' => 'user registerd in multiple database, ask him for which database he need to manage it ', 'response' => $response));

                        }else{
                            // user register in one database, so update database direct in `users_global` table and proceed all steps normally without any question
                            // resolve DB
                            $resolvedDB = DB::table( "customers" )->where('id', $userGlobalRecord->customer_id)->value('database');
                            if($resolvedDB != "demo"){
                                // check if this admin in selected DB
                                if( DB::table( "$resolvedDB.admins" )->where('mobile', 'like', '%'.$session->mobile.'%')->count() > 0 ){$isAdmin = 1;}else{$isAdmin = 0;}            
                                // this user not registerd in `demo`, so we will transfer his `chatbot_sessions` to his database
                                DB::table("$resolvedDB.chatbot_sessions")->insert(['session_id' => $request->session, 'chat_id' => $session->chat_id, 'mobile'=>$session->mobile, 'ver_code' => $session->ver_code, 'request_from' => $session->request_from, 'first_name' =>$session->first_name, 'last_name'=>$session->last_name, 'total_requests'=>$session->total_requests , 'is_verified' => 1, 'is_admin' => $isAdmin,'last_check' => $created_at ]);
                            }
                            // update his database into `user_global` record for future request to be able to manage it
                            DB::table("users_global")->where( 'mobile',$session->mobile )->update([ 'chatbot_database' => $resolvedDB ]);                        
                        }
                    }

                    // code is corrent, so we will update this record to be verifyed
                    DB::table("$request->system.chatbot_sessions")->where( 'session_id',$request->session )->update([ 'is_verified' => 1, 'is_admin' => $isAdmin, 'admin_id' => $checkIfAdmin->id,'last_check' => $created_at ]);
                    return $response = array('state' => 100, 'is_admin' => $isAdmin, 'response' => "verification code is correct (push user to main Menu)");

                    // check if verCode is invalid
                }else if(!is_numeric($request->verCode) or $request->verCode == "0"){
                    // code is in corrent, so we will update this record to be verifyed 
                    return $response = array('state' => 400, 'response' => " invalid response, Please enter your mobile number again?");
                    
                    //check if user enter any response not digits 
                }else if(is_numeric($request->verCode)){
                    // code is in corrent, so we will update this record to be verifyed 
                    return $response = array('state' => 404, 'response' => "Error in verification code, Please enter verification code again?");
                }
            
            // UserAuth: user loggin using their room no and his bre registerd mobile, and we will store his new email
            }elseif(isset($session) and $request->action == "sendRoomForUserlogin"){
                // increment total requests
                DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->increment('total_requests');
                
                // check if this mobile number belongs to administrator or a user (in default DB)
                $checkupUser = DB::table("$request->system.users")->where('u_phone', 'like', '%'.$session->mobile)->where('u_uname', $request->room)->orderBy('u_id', 'desc')->first();

                if(isset($checkupUser)){

                    // check if this email is registerd before or not to add it into user record
                    if (strpos($checkupUser->u_email, $request->email) !== false) {
                        // email is already registerd in user record, so we will ignore adding email.
                    } else{
                        // Add email to user record
                        if(strlen($checkupUser->u_email) > 1 ){ $finalEmail = $checkupUser->u_email.','.$request->email; }
                        else{ $finalEmail = $request->email; }

                        // update mobile and email
                        DB::table("$request->system.users")->where( 'u_id',$session->u_id )->update([ 'u_email' => $finalEmail,'updated_at' => $created_at ]);
                    }
                    // Activate chatbot session
                    DB::table("$request->system.chatbot_sessions")->where( 'session_id',$request->session )->update([ 'is_verified' => 1, 'last_check' => $created_at ]);
                    
                    // View Main menu to the user
                    return $response = array('state' => 600, 'response' => "this room and password is verified, Login successfully, navigate user to main menu.");
                
                }else{
                    // Room number and mobile number dosent match, so we will ask for his room number and password (as PMS password) again
                    $loginPasswordAs = 'Oops, you have entered wrong room number, Please enter your correct room number again👇';
                    // return him again to intent "waitingRoomForUserlogin"
                    return $response = array('state' => 604, 'loginPasswordAs' => $loginPasswordAs, 'response' => "Room number and mobile number dosent match, so we will ask for his room number and password (as PMS password) again, and email for just collection.");
                }
            // UserAuth: user loggin using their room no and password, and we will store their mobile and email in the DB
            }elseif(isset($session) and $request->action == "sendRoomAndPassForUserlogin"){
                // increment total requests
                DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->increment('total_requests');
                
                // check if this mobile number belongs to administrator or a user (in default DB)
                $checkupUser = DB::table("$request->system.users")->where('u_password', $request->password)->where('u_uname', 'like','%'.$request->room)->orderBy('u_id', 'desc')->first();

                if(isset($checkupUser)){
                    
                    // check if this email is registerd before or not to add it into user record
                    if (strpos($checkupUser->u_email, $request->email) !== false) {
                        // email is already registerd in user record, so we will ignore adding email.
                    } else{
                        // Add email to user record
                        if(strlen($checkupUser->u_email) > 1 ){ $finalEmail = $checkupUser->u_email.','.$request->email; }
                        else{ $finalEmail = $request->email; }

                        // update mobile and email
                        DB::table("$request->system.users")->where( 'u_id',$checkupUser->u_id )->update([ 'u_email' => $finalEmail,'updated_at' => $created_at ]);
                    }

                    // Add mobile number to user record, because we makesure that the mobile is not registerd before in user record
                    if(strlen($checkupUser->u_phone) > 0 ){ $finalMobile = $checkupUser->u_phone.','.$session->mobile; }
                    else{ $finalMobile = $session->mobile; }

                    // update mobile and email
                    DB::table("$request->system.users")->where( 'u_id',$checkupUser->u_id )->update([ 'u_phone' => $finalMobile,'updated_at' => $created_at ]);

                    // Activate chatbot session
                    DB::table("$request->system.chatbot_sessions")->where( 'session_id',$request->session )->update([ 'is_verified' => 1, 'u_id' => $checkupUser->u_id, 'last_check' => $created_at ]);
                    
                    // View Main menu to the user
                    return $response = array('state' => 600, 'response' => "this room and password is verified, Login successfully, navigate user to main menu.");
                
                }else{
                    // Room number and mobile number dosent match, so we will ask for his room number and password (as PMS password) again
                    // get PMS info
                    $pms = DB::table("$request->system.pms")->orderBy('id', 'desc')->first();
                    if(isset($pms)){ $loginPasswordAs = 'Oops, you have entered wrong room number or '.str_replace("_"," ",$pms->login_password).', '."\n".'Please enter your correct '.str_replace("_"," ",$pms->login_username).' again👇'; }
                    else{$loginPasswordAs = 'Oops, you have entered wrong password, Please enter your Wi-Fi Password again👇';}
                    // return him again to intent "waitingRoomAndPassForUserlogin"
                    return $response = array('state' => 605, 'loginPasswordAs' => $loginPasswordAs, 'response' => "Room number and mobile number dosent match, so we will ask for his room number and password (as PMS password) again, and email for just collection.");
                }
            // UserOrAdmin: we received his mobile to know he is Admin or User, then will send OTP through SMS and WhatsApp for admin, or ask for username and password for users.
            }elseif(isset($session) and $request->action == "sendMobileForVerification"){
                // increment total requests
                DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->increment('total_requests');
                
                // remove + from mobile number
                $request->mobile = str_replace("+","",$request->mobile);

                // check mobile number lenth if less than 7 digits we will refuse it
                if(!isset($request->mobile) or $request->mobile =="" or strlen($request->mobile) < 6){
                    return $response = array('state' => 0, 'response' => "Mobile number less than 6 digits, please enter again");
                }

                // check if this mobile number belongs to administrator or a user (in default DB)
                $checkIfAdmin = DB::table("$request->system.admins")->where('mobile', $request->mobile)->orderBy('id', 'desc')->first();

                if(isset($checkIfAdmin)){
                    // Auto optaign country code for Egypt and Saudi Arabia
                    $country = DB::table("$request->system.settings")->where('type', 'country')->value('value');
                    // remove + and 00
                    $request->mobile = str_replace('+', '', $request->mobile);
                    if (substr($request->mobile, 0, 2) === '00') { $request->mobile = substr($request->mobile, 2);  }

                    if(strlen($request->mobile) == 11 and $country == "Egypt"){ $request->mobile = "2".$request->mobile; }
                    if(strlen($request->mobile) == 10 and $country == "Saudi Arabia"){ $request->mobile = "966".substr($request->mobile, 1); }
                    // return $request->mobile;
                    // $response = array('state' => 404, 'response' => "verification code sent, Please enter your verification code?");
                    // print($response);
                    // sending SMS
                    $app_name = DB::table("$request->system.settings")->where('type', 'app_name')->value('value');
                    $code = rand(1111, 9999);
                    // $message = $app_name . " Activation code is $code"; // not comply with Orange
                    $message = "Smart Wi-Fi Activation code is $code"; // not comply with Orange
                    /*
                    $sendmessage = new App\Http\Controllers\Integrations\SMS();
                    $sendmessage->send($session->mobile, $message);
                    */
                    // //send Whatsapp code 11/9/2019
                    // $customerID = DB::table("$request->system.settings")->where('type', 'customer_id')->value('value');
                    // $customerDatabase = DB::table('customers')->where('id',$customerID)->value('database');
                    // $messageEncoded = urlencode($message);
                    // $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                    // $sendWhatsappMessage->send( "",$request->mobile , $messageEncoded, $customerID, $customerDatabase, "1", "", "", "1");
                    
                    // sending SMS verification by Microsystem SMS server 5/5/2021
                    $microsystemSMSserver->sendMicrosystemSMS($request->system, 'verification', $request->mobile, $message);

                    // check if admin Have an internet User
                    $checkIfAdminHaveUser = DB::table( "$request->system.users" )->where('u_phone', 'like', '%'.$request->mobile.'%')->orderBy('u_id', 'desc')->first();
                    if(isset($checkIfAdminHaveUser)){$checkIfAdminHaveUserId = $checkIfAdminHaveUser->u_id;}else{$checkIfAdminHaveUserId = 0;}
                    // update verify code in database
                    DB::table("$request->system.chatbot_sessions")->where('session_id',$request->session)->update([ 'u_id'=> $checkIfAdminHaveUserId, 'admin_id'=> $checkIfAdmin->id, 'chat_id'=> $request->chatID, 'mobile' => $request->mobile, 'request_from' => $request->requestFrom, 'first_name'=> $request->firstName, 'last_name'=> $request->lastName, 'ver_code' => $code, 'last_check' => $created_at, 'updated_at' => $created_at ]);
                    
                    return $response = array('state' => 404, 'response' => "verification code sent, Please enter your verification code?");
                }else{
                    // so, this is a user, so we will check if this user is registerd in (default DB) or not, 
                        // if registerd, we will ask for their room number as password, if correct we will innore asking for his password
                        // if not registerd, we will ask for his room number and password (as PMS password)
                    
                    // check if this mobile number belongs to an user (in default DB)
                    $checkIfUser = DB::table("$request->system.users")->where('u_phone', 'like', '%'.$request->mobile)->orderBy('u_id', 'desc')->first();
                    
                    if(isset($checkIfUser)){
                        // this mobile is registerd as user, so we will ask for their room number only
                        DB::table("$request->system.chatbot_sessions")->where( 'session_id',$request->session )->update([ 'is_admin' => 0, 'mobile' => $request->mobile, 'u_id' => $checkIfUser->u_id,'last_check' => $created_at ]);
                        // pivot to intent "waitingRoomForUserlogin"
                        $loginPasswordAs = 'Please enter your room number👇';
                        return $response = array('state' => 504, 'loginPasswordAs' => $loginPasswordAs, 'response' => "this mobile is registerd as user, so we will ask for their room number only, and email for just collection.");
                    }else{
                        // this mobile is not registerd in admins or users, so we will ask for his room number and password (as PMS password)
                        DB::table("$request->system.chatbot_sessions")->where( 'session_id',$request->session )->update([ 'is_admin' => 0, 'mobile' => $request->mobile,'last_check' => $created_at ]);
                        // get PMS info
                        $pms = DB::table("$request->system.pms")->orderBy('id', 'desc')->first();
                        if(isset($pms)){ $loginPasswordAs = 'Please enter your '.str_replace("_"," ",$pms->login_password).'👇'; }
                        else{$loginPasswordAs = 'Please enter your Wi-Fi Password👇';}
                        // pivot to intent "waitingRoomAndPassForUserlogin"
                        return $response = array('state' => 505, 'loginPasswordAs' => $loginPasswordAs, 'response' => "this mobile is not registerd in admins or users, so we will ask for his room number and password (as PMS password), and email for just collection.");
                    }

                    
                }
            // UserOrAdmin: check if this is the first chat, Ask User Or Admin to enter their mobile number
            }else if(!isset($session)){
                // ask him to enter his mobile number
                DB::table("$request->system.chatbot_sessions")->insert([['session_id' => $request->session, 'created_at'=> $created_at ]]);
                return $response = array('state' => 400, 'response' => "Unauthorized, Please enter your mobile number?");
            // user request online users again without enter mobile number
            }else{
                return $response = array('state' => 400, 'response' => "Unauthorized, Please enter your mobile number?");
            }
        }else{
            $data = array('state' => 400, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }  
    }

    public function login(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        
        // // for testing only
        // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// $body = @file_get_contents('php://input');
        // DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);
        
        // first step: check if system exist
        $customerData=DB::table('customers')->where('database', $request->system)->orderBy('id', 'asc')->first();
        if( isset($customerData) ){
            // second step: check if system active or inactive
            if($customerData->state == "1"){
                // third step: check if username is exist
                $adminData=DB::table("$request->system.admins")->where('email', $request->username)->first();
                if(isset($adminData)){
                    // fourth step: check if password if true
                    if($adminData->uname == $request->password){
                        // get or generate mobile app token
                        if(isset($adminData->mobileapp_token)){
                            $token = $adminData->mobileapp_token;
                        }else{
                            $token = bin2hex(random_bytes(64));
                        }
                        // update tokrn in db and last login
                        DB::table("$request->system.admins")->where( 'id', $adminData->id )->update([ 'mobileapp_token' => $token, 'last_login' => $created_at ]);
                            $data = array('state' => '1', 'message' => 'login success.', 'token'=> $token, 'adminId'=> $adminData->id, 'name'=>$adminData->name, 'permissions'=>$adminData->permissions);
                            return $msg = json_encode($data);
                    }else{
                        $data = array('state' => '04', 'message' => 'Invalid password.');
                        return $msg = json_encode($data);
                    }
                }else{
                    $data = array('state' => '03', 'message' => 'Username not found.');
                    return $msg = json_encode($data);
                }
            }else{
                $data = array('state' => '02', 'message' => 'system expired.');
                return $msg = json_encode($data);
            }
        }else{
            $data = array('state' => '01', 'message' => 'system not found.');
            return $msg = json_encode($data);
        }

        if(isset($userData) ){
            DB::table("$request->system.users")->where('mobile',$request->mobile)->update(['last_visit' => $created_at, 'device_token' => $request->token]);
            $data = array('state' => '1', 'message' => 'login successfully.', 'user_id' => $userData->id, 'name' => $userData->name, 'age' => $userData->age, 'country' => $userData->country, 'governorate' => $userData->governorate, 'bluetooth_id' => $userData->bluetooth_id);
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'user not found.');
            return $msg = json_encode($data);
        }
        
    }

    public function verifyToken(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        
        if( isset($request->system) and isset($request->token) and DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            $data = array('state' => '1', 'message' => 'logged in.');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }        
    }

    public function dashboard(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        $last7days=date("Y-m-d", strtotime( '-7 days' ) );
        $last30days=date("Y-m-d", strtotime( '-30 days' ) );
        $last90days=date("Y-m-d", strtotime( '-90 days' ) );
        $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
        // return $request->system;
        if(  $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
                
            // get total users 
            $totalUsers=DB::table("$request->system.users")->count();
            // get online users 
            $onlineUsers=DB::table("$request->system.user_active")->count();
            // get total branches
            $totalBranches = DB::table("$request->system.branches")->count();
            // get online branches last 5 Min
            $onlineBranches = DB::table("$request->system.branches")->where('last_check', '>=', Carbon::now()->subMinutes(5)->toDateTimeString())->count();        
            
            // get total visits
            $totalLandingPageVisits = DB::table("$request->system.visitors")->count();
            // get new visits today
            $dayStart=$today." 00:00:00";
            $dayEnd=$today." 23:59:59";
            $newLandingPageVisitsToday = DB::table("$request->system.visitors")->whereBetween('created_at',[$dayStart, $dayEnd])->count();
            $newVisitsToday = DB::table("$request->system.radacct")->where('dates',$today)->groupBy('u_id')->count();
            // get new users today
            $newUsersToday = DB::table("$request->system.users")->whereBetween('created_at',[$dayStart, $dayEnd])->count();

            // get branches details
            $data =DB::table("$request->system.branch_network")->get();
            foreach ($data as $key => $value) {

                //get totalDownloadSpeed
                $currentDownSpeed = DB::table("$request->system.history")->where('operation','interface_out_rate')->where('branch_id',$value->id)->value('notes');
                $netDownSpeed = DB::table("$request->system.history")->where('operation','interface_out_net_speed')->where('branch_id',$value->id)->value('notes');
                if ($currentDownSpeed != 0) {
                    $percentage = round(($currentDownSpeed / $netDownSpeed) * 100, 1);
                } else {
                    $percentage = 0;
                }
                $currentDownSpeedToMB = round($currentDownSpeed/1024,1);
                $netDownSpeedToMB = round($netDownSpeed/1024,1);
                $value->downloadSpeed = $percentage . ";" . $currentDownSpeedToMB . "MB of " . $netDownSpeedToMB . "MB";

                // get totalUploadSpeed
                $currentUpSpeed = DB::table("$request->system.history")->where('operation','interface_out_rate')->where('branch_id',$value->id)->value('details');
                $netUpSpeed = DB::table("$request->system.history")->where('operation','interface_out_net_speed')->where('branch_id',$value->id)->value('details');
                if ($currentUpSpeed != 0) {
                    $percentage = round(($currentUpSpeed / $netUpSpeed) * 100, 1);
                } else {
                    $percentage = 0;
                }
                $currentUpSpeedToMB = round($currentUpSpeed/1024,1);
                $netUpSpeedToMB = round($netUpSpeed/1024,1);
                $value->uploadSpeed = $percentage . ";" . $currentUpSpeedToMB . "MB of " . $netUpSpeedToMB . "MB";

                // get total and online users
                $value->count_online = DB::table("$request->system.radacct_active_users")->where('branch_id', $value->id)->count();
                $value->count_users = DB::table("$request->system.users")->where('branch_id', $value->id)->count();
                
                // get the first day of renwing day to get monthly usage in GB
                $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($request->system, $value->id);
                $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];
                
                // get Monthly Usage
                $monthlyUsageUpload = DB::table("$request->system.radacct")->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctinputoctets');
                $monthlyUsageDownload = DB::table("$request->system.radacct")->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctoutputoctets');
                $monthlyUsageAsNumber = round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1);
                $monthlyTotalUsage = $monthlyUsageAsNumber." GB";
                
                $value->monthly_usage = $monthlyTotalUsage;
                
                // Get Total Usage
                $usageUpload = DB::table("$request->system.radacct")->where('branch_id',$value->id)->sum('acctinputoctets');
                $usageDownload = DB::table("$request->system.radacct")->where('branch_id',$value->id)->sum('acctoutputoctets');
                $totalUsageAsNumber = round(($usageUpload+$usageDownload)/1024/1024/1024,2);
                $totalUsage = $totalUsageAsNumber." GB";
                $value->total_usage = $totalUsage;
                $value->monthlyQuota = DB::table("$request->system.branches")->where('id',$value->id)->value('monthly_quota');
                $value->remainingQuotaGB = $monthlyUsageAsNumber>0 ? round($value->monthlyQuota-$monthlyUsageAsNumber,1) : 0;
                $radiusType = DB::table("$request->system.branches")->where('id', $value->id)->value('radius_type');
                if( $radiusType == "aruba" ){ $foundDDWRT=1; }
                if( $radiusType == "ddwrt" ){ $foundDDWRT=1; }
                if(!isset($foundDDWRT)){$foundDDWRT=0;}
                //return $value->last_check .'aaaaaaaaaaaaaaaaaaaaaa'. Carbon::now();
                if($foundDDWRT==1){
                    // get value from last update in "radacct" table
                    $lastCheckSeconds = strtotime( DB::table("$request->system.radacct")->where('branch_id',$value->id)->orderBy('radacctid', 'desc')->value('acctupdatetime') );
                }else{
                    // get value from branch table
                    $lastCheckSeconds=strtotime($value->last_check);
                }
                
                //return Carbon::now();
                $timeNowSeconds = strtotime(Carbon::now());
                
                //$lastCheckSeconds = strtotime("2017-10-11 10:00:00");
                //$timeNowSeconds = strtotime("2017-10-11 10:02:01");
                $value->delayTime = $timeNowSeconds - $lastCheckSeconds;

                if($foundDDWRT==1){
                    // send dash "-"
                    $value->cpu = "-";
                    $value->uptime = "-";
                    $value->ram = "-";
                }else{
                    // send real data
                    $value->cpu = $value->cpu;
                }

                $value->foundDDWRT = $foundDDWRT;

                if($value->last_check){
                    $value->last_check_date = explode(' ', $value->last_check)[0];
                    $value->last_check_time = explode(' ', $value->last_check)[1];
                }
            }
            // $branchesDetails = json_encode($data);


            // get online users chart (last 7 days)
            // if(isset($allDayData)){unset($allDayData);}
            // $allDayData=App\Models\RadacctNetworkUsers::where('network_id',$network->id)->where('month', $currMonth->month)->get();
            // if(isset($finalValue)){unset($finalValue);}
            // foreach($allDayData as $record){
            //     if(!isset($finalValue[$record->day])){$finalValue[$record->day]=1;}
            //     else{$finalValue[$record->day]++;}
            // }

            // for($i=1;$i<=31;$i++)
            // {
            //     echo "'";
            //     if($i>=1 and $i<=9){$number="0".$i;}else{$number=$i;}
                
            //     if(isset($finalValue[$number])){
            //         echo $finalValue[$number];
            //     }else{echo "0";}
            //     echo "'";
            //     if($i!="31"){echo ",";}
            // }


            // build chart
            $last7DayData=DB::table("$request->system.radacct_network_users")->where('dates','>=',$last7days)->orderBy('radacctid', 'asc')->get();
            foreach($last7DayData as $record){
                if(!isset($getLast7DayData[$record->dates])){$getLast7DayData[$record->dates]=1;}
                else{$getLast7DayData[$record->dates]++;}
            }
            // remove kes to be able to get the values by index in next phase
            $getLast7DayData = array_values($getLast7DayData);
            // initialize onlineUsersChartLast7Days variable
            $onlineUsersChartLast7Days = array();
            // assign last 7 days data, if there is no data (system will generate it by 1.1) to avoud app crashing
            for($i=0;$i<=6;$i++){
                if(isset($getLast7DayData[$i])){
                    // add this value and make it (double) by adding ".1"
                    array_push($onlineUsersChartLast7Days,"$getLast7DayData[$i].1");
                }else{array_push($onlineUsersChartLast7Days,"1.1");}
            }
            // convery string into float, to avoid app crashing
            $onlineUsersChartLast7Days = array_map('floatval', $onlineUsersChartLast7Days);

            /////////////////////////////////////////////////////////////////////////////
            $last30DaysData=DB::table("$request->system.radacct_network_users")->where('dates','>=',$last30days)->orderBy('radacctid', 'asc')->get();
            foreach($last30DaysData as $record){
                if(!isset($getLast30DaysData[$record->dates])){$getLast30DaysData[$record->dates]=1;}
                else{$getLast30DaysData[$record->dates]++;}
            }
            // return $getLast30DaysData;
            // remove kes to be able to get the values by index in next phase
            $getLast30DaysData = array_values($getLast30DaysData);
            // initialize onlineUsersChartLastMonth variable
            $onlineUsersChartLastMonth = array();
            // assign last 30 days data, if there is no data (system will generate it by 1.1) to avoud app crashing
            for($i=0;$i<=29;$i++){
                if(isset($getLast30DaysData[$i])){
                    // add this value and make it (double) by adding ".1"
                    array_push($onlineUsersChartLastMonth,"$getLast30DaysData[$i].1");
                }else{array_push($onlineUsersChartLastMonth,"1.1");}
            }
            // convery string into float, to avoid app crashing
            $onlineUsersChartLastMonth = array_map('floatval', $onlineUsersChartLastMonth);
            //////////////////////////////////////////////////////////////////////////////////
            $last3Months=DB::table("$request->system.radacct_network_users")->where('dates','>=',$last90days)->orderBy('radacctid', 'asc')->get();
            foreach($last3Months as $record){
                if(!isset($getLast3Months[$record->dates])){$getLast3Months[$record->dates]=1;}
                else{$getLast3Months[$record->dates]++;}
            }
            // return $getLast3Months;
            // remove kes to be able to get the values by index in next phase
            $getLast3Months = array_values($getLast3Months);
            // initialize onlineUsersChartLast3Months variable
            $onlineUsersChartLast3Months = array();
            // assign last 30 days data, if there is no data (system will generate it by 1.1) to avoud app crashing
            for($i=0;$i<=89;$i++){
                if(isset($getLast3Months[$i])){
                    // add this value and make it (double) by adding ".1"
                    array_push($onlineUsersChartLast3Months,"$getLast3Months[$i].1");
                }else{array_push($onlineUsersChartLast3Months,"1.1");}
            }
            // convery string into float, to avoid app crashing
            $onlineUsersChartLast3Months = array_map('floatval', $onlineUsersChartLast3Months);
            //////////////////////////////////////////////////////////////////////////////////
            
            
            return json_encode(array('state' => '1', 'message' => 'get dashboard done.', 'totalUsers'=> $totalUsers, 'onlineUsers'=>$onlineUsers, 'totalBranches' => $totalBranches, 'onlineBranches'=>$onlineBranches   
            , 'totalLandingPageVisits'=>$totalLandingPageVisits
            , 'newLandingPageVisitsToday'=>$newLandingPageVisitsToday
            , 'newVisitsToday'=>$newVisitsToday
            , 'newUsersToday'=>$newUsersToday
            , 'onlineUsersChartLast7Days'=>$onlineUsersChartLast7Days
            , 'onlineUsersChartLastMonth'=>$onlineUsersChartLastMonth
            , 'onlineUsersChartLast3Months'=>$onlineUsersChartLast3Months
            , 'branchesDetails'=>$data));
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }           
    }

    public function getBranches(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
        
        if(  $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
                
            // get branches details
            if(isset($request->id)){
                $data =DB::table("$request->system.branch_network")->where('id', $request->id)->get();
            }else{
                $data =DB::table("$request->system.branch_network")->get();
            }
            foreach ($data as $key => $value) {

                 // get the first day of renwing day to get monthly usage in GB
                $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($request->system, $value->id);
                $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];    

                //get totalDownloadSpeed
                $currentDownSpeed = DB::table("$request->system.history")->where('operation','interface_out_rate')->where('branch_id',$value->id)->value('notes');
                $netDownSpeed = DB::table("$request->system.history")->where('operation','interface_out_net_speed')->where('branch_id',$value->id)->value('notes');
                if ($currentDownSpeed != 0) {
                    $percentage = round(($currentDownSpeed / $netDownSpeed) * 100, 1);
                } else {
                    $percentage = 0;
                }
                $currentDownSpeedToMB = round($currentDownSpeed/1024,1);
                $netDownSpeedToMB = round($netDownSpeed/1024,1);
                $value->downloadSpeed = $percentage . ";" . $currentDownSpeedToMB . "MB of " . $netDownSpeedToMB . "MB";

                // get totalUploadSpeed
                $currentUpSpeed = DB::table("$request->system.history")->where('operation','interface_out_rate')->where('branch_id',$value->id)->value('details');
                $netUpSpeed = DB::table("$request->system.history")->where('operation','interface_out_net_speed')->where('branch_id',$value->id)->value('details');
                if ($currentUpSpeed != 0) {
                    $percentage = round(($currentUpSpeed / $netUpSpeed) * 100, 1);
                } else {
                    $percentage = 0;
                }
                $currentUpSpeedToMB = round($currentUpSpeed/1024,1);
                $netUpSpeedToMB = round($netUpSpeed/1024,1);
                $value->uploadSpeed = $percentage . ";" . $currentUpSpeedToMB . "MB of " . $netUpSpeedToMB . "MB";

                // get total and online users
                $value->count_online = DB::table("$request->system.radacct_active_users")->where('branch_id', $value->id)->count();
                $value->count_users = DB::table("$request->system.users")->where('branch_id', $value->id)->count();
                
                // get Monthly Usage
                $monthlyUsageUpload = DB::table("$request->system.radacct")->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctinputoctets');
                $monthlyUsageDownload = DB::table("$request->system.radacct")->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctoutputoctets');
                $monthlyTotalUsage = round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1)." GB";
                $value->monthly_usage = $monthlyTotalUsage;
                
                // Get Total Usage
                $usageUpload = DB::table("$request->system.radacct")->where('branch_id',$value->id)->sum('acctinputoctets');
                $usageDownload = DB::table("$request->system.radacct")->where('branch_id',$value->id)->sum('acctoutputoctets');
                $totalUsage = round(($usageUpload+$usageDownload)/1024/1024/1024,2)." GB";
                $value->total_usage = $totalUsage;

                $branchData = DB::table("$request->system.branches")->where('id', $value->id)->first();
                $radiusType = $branchData->radius_type;
                if( $radiusType == "aruba" ){ $foundDDWRT=1; }
                if( $radiusType == "ddwrt" ){ $foundDDWRT=1; }
                if(!isset($foundDDWRT)){$foundDDWRT=0;}
                //return $value->last_check .'aaaaaaaaaaaaaaaaaaaaaa'. Carbon::now();
                if($foundDDWRT==1){
                    // get value from last update in "radacct" table
                    $lastCheckSeconds = strtotime( DB::table("$request->system.radacct")->where('branch_id',$value->id)->orderBy('radacctid', 'desc')->value('acctupdatetime') );
                }else{
                    // get value from branch table
                    $lastCheckSeconds=strtotime($value->last_check);
                }
                
                //return Carbon::now();
                $timeNowSeconds = strtotime(Carbon::now());
                
                //$lastCheckSeconds = strtotime("2017-10-11 10:00:00");
                //$timeNowSeconds = strtotime("2017-10-11 10:02:01");
                $value->delayTime = $timeNowSeconds - $lastCheckSeconds;

                if($foundDDWRT==1){
                    // send dash "-"
                    $value->cpu = "-";
                    $value->uptime = "-";
                    $value->ram = "-";
                }else{
                    // send real data
                    $value->cpu = $value->cpu;
                }
                
                // get Monthly Usage
                $monthlyUsageUpload = DB::table("$request->system.radacct")->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctinputoctets');
                $monthlyUsageDownload = DB::table("$request->system.radacct")->where('branch_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctoutputoctets');
                $monthlyUsageAsNumber = round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1);
                $monthlyTotalUsage = $monthlyUsageAsNumber." GB";
                $value->monthly_usage = $monthlyTotalUsage;
                if(!isset($branchData->monthly_quota) or $branchData->monthly_quota=="" or $branchData->monthly_quota == 0){
                    $value->remainingMonthlyQuotaPercentage = 0;    
                }else{
                    $value->remainingMonthlyQuotaPercentage = round( ($monthlyUsageAsNumber/$branchData->monthly_quota)*100 ,1);
                    $value->remainingMonthlyQuotaPercentage = "$value->remainingMonthlyQuotaPercentage";
                }

                // get remining days till renew
                $renewalDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($lastDayOfQuotaPeriod)) . " +1 days"));
                $value->renewal_date = $renewalDate;
                $value->remainingDaysTillRenewQuota = date_diff( date_create($today) , date_create($renewalDate) )->format("%a");
                // get percentage of remining quota in month
                $totalMonthDays=date_diff( date_create($firstDayOfQuotaPeriod) , date_create($renewalDate) )->format("%a");
                $value->remainingDaysTillRenewQuotaPercentage = round( ($value->remainingDaysTillRenewQuota/$totalMonthDays)*100 ,1);
                $value->remainingDaysTillRenewQuotaPercentage = "$value->remainingDaysTillRenewQuotaPercentage";

                // Get Total Usage
                $usageUpload = DB::table("$request->system.radacct")->where('branch_id',$value->id)->sum('acctinputoctets');
                $usageDownload = DB::table("$request->system.radacct")->where('branch_id',$value->id)->sum('acctoutputoctets');
                $totalUsageAsNumber = round(($usageUpload+$usageDownload)/1024/1024/1024,2);
                $totalUsage = $totalUsageAsNumber." GB";
                $value->total_usage = $totalUsage;
                $value->monthlyQuota = $branchData->monthly_quota;
                $value->remainingQuotaGB = $monthlyUsageAsNumber>0 ? round($value->monthlyQuota-$monthlyUsageAsNumber,1) : 0;
                
                // set ram variables if contans "MB" return "0" because we update this value to be percantage but this branch not updated
                if (strpos($value->ram,"MB") !== false ) {
                    $value->ram = 0;
                }

                // get buttons values
                $value->url = $branchData->url;
                
                $value->reboot = $branchData->reboot;
                $value->change_username_or_password = $branchData->change_username_or_password;
                $value->change_state = $branchData->change_state;
                
                $value->adult_state = $branchData->adult_state;
                $value->change_adult_state = $branchData->change_adult_state;
                $value->wireless_state = $branchData->wireless_state;
                $value->change_wireless_state = $branchData->change_wireless_state;
                $value->wireless_name = $branchData->wireless_name;
                $value->change_wireless_name = $branchData->change_wireless_name;
                $value->wireless_pass = $branchData->wireless_pass;
                $value->change_wireless_pass = $branchData->change_wireless_pass;
                $value->hacking_protection = $branchData->hacking_protection;
                $value->change_hacking_protection = $branchData->change_hacking_protection;
                $value->auto_login = $branchData->auto_login;
                $value->change_auto_login = $branchData->change_auto_login;
                $value->auto_login_expiry = $branchData->auto_login_expiry;
                $value->block_windows_update = $branchData->block_windows_update;
                $value->change_block_windows_update = $branchData->change_block_windows_update;
                $value->block_torrent_download = $branchData->block_torrent_download;
                $value->change_block_torrent_download = $branchData->change_block_torrent_download;
                $value->block_downloading = $branchData->block_downloading;
                $value->change_block_downloading = $branchData->change_block_downloading;
                $value->antivirus = $branchData->antivirus;
                $value->change_antivirus = $branchData->change_antivirus;
                
                //load balanceing
                $value->load_balance_state = $branchData->load_balance_state;
                $value->change_load_balance_state = $branchData->change_load_balance_state;
                $value->load_balanceing_lines = DB::table("$request->system.load_balancing")->where('branch_id', $value->id)->get();

                $value->foundDDWRT = $foundDDWRT;

                if($value->last_check){
                    $value->last_check_date = explode(' ', $value->last_check)[0];
                    $value->last_check_time = explode(' ', $value->last_check)[1];
                }
            }
            // $branchesDetails = json_encode($data);

            return json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }           
    }
    
    // paginate array for getOnlineUsers function to compine online users array with hosts array
    public function paginate($items, $perPage = 10,$page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

    public function getOnlineUsers(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        
        // // for testing only
        // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// $body = @file_get_contents('php://input');
        // DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);

        if(  $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
                
            // get branches details
            $data = DB::table("$request->system.radacct_active_users")->orderBy('radacctid','desc')
            // ->crossJoin("$request->system.hosts")
            // ->select("$request->system.hosts.mac", "$request->system.hosts.address")
            // ->select("$request->system.radacct_active_users.*", "$request->system.hosts.*")
            // ->paginate(2);
            ->get();

            foreach ($data as $key => $value) {
                $value->id = $value->radacctid;
                
                $value->acctstarttime = date("c", strtotime($value->acctstarttime));
                $userData = DB::table("$request->system.users")->where('u_id',$value->u_id)->first();
                isset($value->u_gender)? $value->u_gender=$userData->u_gender :$value->u_gender="";
                $value->group_name = DB::table("$request->system.area_groups")->where('id',$value->group_id)->value('name');
                $value->framedipaddress = '2020-05-05T22:00:19+02:00';
            }
            // $branchesDetails = json_encode($data);

            
            /////////////////////////
            // Merge Hosts foreach //
            /////////////////////////
            // $data2 = [];
            foreach( DB::table("$request->system.hosts")->where(['internet_access' => '0'])->get() as $hostData){

                // get wifi signal icon
                if(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 40){ $wifiSignal = "Excellent WiFi signal: $hostData->wifi_signal dBm"; }
                elseif(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 25 and $hostData->wifi_signal <= 39){ $wifiSignal = "Good WiFi signal: $hostData->wifi_signal dBm"; }
                elseif(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 15 and $hostData->wifi_signal <= 24){ $wifiSignal = "Fair WiFi signal: $hostData->wifi_signal dBm"; }
                elseif(isset($hostData->wifi_signal) and $hostData->wifi_signal >= 1 and $hostData->wifi_signal <= 14){ $wifiSignal = "Weak WiFi signal: $hostData->wifi_signal dBm"; }
                else{ $wifiSignal = "";}

                if(isset($hostData->u_id) and $hostData->u_id != 0){
                    // user exist
                    $userData = DB::table("$request->system.users")->where('u_id',$hostData->u_id)->first();
                    if($hostData->bypassed == "true"){$tag = 'Bypassed';}
                    elseif( $userData->suspend == "1"){ $tag = 'Suspended'; }
                    else{ $tag = 'Pending'; }
                    $hostUserName = $tag.' '.$userData->u_name;
                    // if(isset($hostData->device_name) and $hostData->device_name!=""){$hostUser = $hostData->device_name;}
                    // else{$hostUser = $hostData->mac;}
                    $deviceName = $hostData->device_name;
                    $hostUser = $hostData->mac;
                    $hostPhone = $userData->u_phone;
                    $hostNetworkID = $userData->network_id;
                    if(isset($hostData->branch_id)){ $hostBranchID = $hostData->branch_id; }else{ $hostBranchID = $userData->branch_id; }
                    $hostGroupID = $userData->group_id;
                    $suspend = $userData->suspend;
                    $hostGroupName = DB::table("$request->system.area_groups")->where('id',$userData->group_id)->value('name');
                    $hostBranchName = DB::table("$request->system.branches")->where('id',$hostBranchID)->value('name');
                }else{
                    // user not found in DB
                    if($hostData->bypassed == "true"){$tag = 'Bypassed';}
                    else{ $tag = 'Not registered'; }
                    $hostData->u_id = 0;
                    $hostUserName = $tag;
                    $deviceName = $hostData->device_name;
                    $hostUser = $hostData->mac;
                    $hostPhone = "";
                    $hostNetworkID = "";
                    if(isset($hostData->branch_id)){ $hostBranchID = $hostData->branch_id;}else{ $hostBranchID = ""; }
                    $hostGroupID = "";
                    $hostGroupName = "";
                    if(isset($hostData->branch_id)){ $hostBranchName = DB::table("$request->system.branches")->where('id',$hostData->branch_id)->value('name'); }else{ $hostBranchName = ""; }
                    $suspend="";
                }
                $tempData = array('u_id'=>$hostData->u_id,
                    'u_name'=>$hostUserName,
                    'u_phone'=>$hostPhone,
                    'groupname'=>$hostGroupName,
                    'deviceName'=>$deviceName,
                    'branch_name'=>$hostBranchName,
                    'suspend'=>$suspend,
                    'radacctid'=>'',
                    'acctsessionid'=>'',
                    'acctuniqueid'=>'',
                    'username'=>$hostUser,
                    'acctstarttime'=>$hostData->uptime,
                    'acctstoptime'=>'',
                    'acctsessiontime'=>'',
                    'acctinputoctets'=>'',
                    'acctoutputoctets'=>'',
                    'callingstationid'=>'',
                    'framedipaddress'=>$hostData->address,
                    'branch_id'=>$hostBranchID,
                    'group_id'=>$hostGroupID,
                    'network_id'=>$hostNetworkID,
                    'total_quota'=>'',
                    'speed_limit'=>'',
                    'end_speed'=>'',
                    'realm'=>'',
                    'TodayUpload'=>'',
                    'TodayDownload'=>'',
                    'browseing_speed'=>'',
                    'designed_speed'=>'',
                    'designed_end_speed'=>'',
                    'download_speed'=> '',
                    'download_end_speed'=>'',
                    'browseing_end_speed'=> '',
                    'wifi_signal'=>$wifiSignal,
                    'currentDownloadSpeed'=>'',
                    'finalGroupDownloadSpeed'=>'',
                    'currentUploadSpeed'=>'',
                    'finalGroupUploadSpeed'=>'',
                    'uploadPersentage'=>'',
                    'downloadPersentage'=>'');
                
                unset($userData);
                unset($hostUserName);
                unset($tag);
                unset($hostUser);
                unset($hostPhone);
                unset($hostNetworkID);
                unset($hostBranchID);
                unset($hostGroupID);
                unset($hostGroupName);
                unset($hostBranchName);
                unset($suspend);
                unset($wifiSignal);
                array_push($data, $tempData);
                
            }
            // if(!isset($data2)){$data2=array();}// fix datatable no data error
            // array_push($data, $data2);
            $data = $this->paginate($data,10);
            return json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }           
    }

    // just function to convert date into 'getUsers' function
    protected function convertDate($date){
        $date_array = explode("/",$date); // split the array
        $var_day = $date_array[0]; //day seqment
        $var_month = $date_array[1]; //month segment
        $var_year = $date_array[2]; //year segment
        return "$var_year-$var_month-$var_day";
    }
    // get users by all filters
    public function getUsers(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        $adminData = DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->first();

        // // for testing only
        // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// $body = @file_get_contents('php://input');
        // DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);
        
        if(  $request->system && $request->token && isset($adminData) ){
                
            // IMPORTANT : we can use "whereIn" in search for all the following where functions or "oeWhereIn" to get another value in search page
            //form search button
            $object  = $request->input('by');
            $value   = $request->input('find');
            $length  = $request->input('length');
            $start   = $request->input('start');
            $order   = $request->input('order');
            $columns = $request->input('columns');
            $olderBy =  $columns[$order[0]['column']]['data'];
    
            $network = $request->input('network');
            $groups = $request->input('groups');
            $user_frequency_charged_from = $request->input('user_frequency_charged_from');
            $user_frequency_charged_to   = $request->input('user_frequency_charged_to');
            $frequency = $request->input('frequency');
            $Users_charged_from = $request->input('Users_charged_from');
            $Users_charged_to   = $request->input('Users_charged_to');
            $Users_not_charged_from = $request->input('Users_not_charged_from');
            $Users_not_charged_to = $request->input('Users_not_charged_to');
            $male = $request->input('male');
            $female = $request->input('female');
            $Unknown = $request->input('Unknown');
            $active = $request->input('active');
            $inactive = $request->input('inactive');
            $online = $request->input('online');
            $suspend = $request->input('suspend');
            $unsuspend = $request->input('unsuspend');
            $register = $request->input('register');
            $adminconfirm = $request->input('adminconfirm');
            $smsconfirm = $request->input('smsconfirm');
            $country = $request->input('country');
            $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
    
            if(isset($network) and $network == ''){
                unset($network);
            }
    
            if(isset($groups) and $groups == ''){
                unset($groups);
            }
    
            if(isset($country) and $country == ''){
                unset($country);
            }
    
            if((isset($user_frequency_charged_from) and $user_frequency_charged_from == '') or (isset($user_frequency_charged_to) and $user_frequency_charged_to == '')){
                unset($user_frequency_charged_from);
                unset($user_frequency_charged_to);
                unset($frequency);
            }
    
            if((isset($Users_charged_from) and $Users_charged_from == '') or (isset($Users_charged_to) and $Users_charged_to == '')){
                unset($Users_charged_from);
                unset($Users_charged_to);
            }
    
            $Gender = array();
            if(isset($male) and $male == 'on'){
                $Gender[] = 1;
            }
    
            if(isset($female) and $female == 'on'){
                $Gender[] = 0;
            }
    
            if(isset($Unknown) and $Unknown == 'on'){
                $Gender[] = 2;
            }
            //Statues
            $Active = array();
    
            if(isset($active) and $active == 'on'){
                $Active[] = 1;
            }
    
            $Inactive = array();
    
            if(isset($inactive) and $inactive == 'on'){
                $Inactive[] = 0;
            }
    
            //online but I didnt use this section
    
            //Suspend
            $Suspend = array();
    
            if(isset($suspend) and $suspend == 'on'){
                $Suspend[] = 1;
            }
    
            $Unsuspend = array();
            if(isset($unsuspend) and $unsuspend == 'on'){
                $Unsuspend[] = 0;
            }
            //Register Confirm
            $Register = array();
            if(isset($register) and $register == 'on'){
                $Register[] = 2;
            }
            $Adminconfirm = array();
            if(isset($adminconfirm) and $adminconfirm == 'on'){
                $Adminconfirm[] = 0;
            }
    
            $SMSconfirm = array();
            if(isset($smsconfirm) and $smsconfirm == 'on'){
                $SMSconfirm[] = 1;
            }
    
            $data = array(); // model from url
            switch ($object) {
                case 'id':
                    if(isset($frequency)){
                        $data = DB::table("$request->system.users_radacct")->where('u_id',$value);
                    }else{
                        $data = DB::table("$request->system.users")->where('u_id',$value);
                    }
                    break;
                case 'Name':
                    if(isset($frequency)){
                        $data = DB::table("$request->system.users_radacct")->where('u_name','like', '%'.$value.'%');
                    }else{
                        $data = DB::table("$request->system.users")->where('u_name','like', '%'.$value.'%');
                    }
                    break;
                case 'User name':
                    if(isset($frequency)){
                        $data = DB::table("$request->system.users_radacct")->where('u_uname','like', '%'.$value.'%');
                    }else{
                        $data = DB::table("$request->system.users")->where('u_uname','like', '%'.$value.'%');
                    }
                    break;
                case 'Comment':
                    if(isset($frequency)){
                        $data = DB::table("$request->system.users_radacct")->where('notes','like', '%'.$value.'%');
                    }else{
                        $data = DB::table("$request->system.users")->where('notes','like', '%'.$value.'%');
                    }
                    break;
                case 'Phone':
                    if(isset($frequency)){
                        $data = DB::table("$request->system.users_radacct")->where('u_phone','like', '%'.$value.'%');
                    }else{
                        $data = DB::table("$request->system.users")->where('u_phone','like', '%'.$value.'%');
                    }
                    break;
                case 'E-mail':
                    if(isset($frequency)){
                        $data = DB::table("$request->system.users_radacct")->where('u_email','like', '%'.$value.'%');
                    }else{
                        $data = DB::table("$request->system.users")->where('u_email','like', '%'.$value.'%');
                    }
                    break;
                case 'Computer(Macaddress)':
                    if(isset($frequency)){
                        $data = DB::table("$request->system.users_radacct")->where('u_mac','like', '%'.$value.'%');
                    }else{
                        $data = DB::table("$request->system.users")->where('u_mac','like', '%'.$value.'%');
                    }
                    break;
                default:
                    if(isset($frequency)){
                        //$data = DB::table("$request->system.users_radacct")->get();
                        $data = DB::table("$request->system.users_radacct");
                    }else{
                        //$data = DB::table("$request->system.users")->all();
                        $data = DB::table("$request->system.users");
                    }
                    break;
            }
            if($adminData->type == 2) {
                $branches = explode(',', $adminData->branches);
                $data->whereIn('branch_id', $branches);
            }else{
                if (isset($network)) {
                    $network = explode(',', $network);
                    $data->whereIn('branch_id', $network);
                }
            }
            if(isset($groups)){
                $groups = explode(',',$groups);
                $data->whereIn('group_id',$groups);
            }
            if(isset($country)){
                $country = explode(',',$country);
                $data->whereIn('u_country',$country);
            }
            if(isset($frequency)){
                $data->select(DB::raw('* ,count(u_id) as counts'))->whereBetween('dates',[$this->convertDate($user_frequency_charged_from),$this->convertDate($user_frequency_charged_to)])
                    ->groupBy('u_id')->havingRaw("count(u_id) >=$frequency");
                    // $data->select(DB::raw('* ,count(u_id) as counts'))->whereBetween('dates',[$this->convertDate($user_frequency_charged_from),$this->convertDate($user_frequency_charged_to)])->groupBy('u_id')->having('counts', '>=', $frequency);
                    //->having('counts', '>=', $frequency)->max('radacctid'); we remove ->max('radacctid') after bug in 7.2.2017 because we don't know this jop
            }
            if(isset($Users_charged_from) and $Users_charged_from!=""){
                $data->whereBetween('u_card_date_of_charging',[$this->convertDate($Users_charged_from),$this->convertDate($Users_charged_to)]);
            }
            if(isset($Users_not_charged_from) and $Users_not_charged_from!=""){
                $data->whereNotBetween('u_card_date_of_charging',[$this->convertDate($Users_not_charged_from),$this->convertDate($Users_not_charged_to)])->orWhereNull('u_card_date_of_charging');
            }
            if (!empty($Gender)) {
                $data->whereIn('u_gender', $Gender);
            }
            //Statues
            if (!empty($Active)) {
                $data->whereIn('u_state',$Active);
            }//Statues
            if (!empty($Inactive)) {
                $data->whereIn('u_state',$Inactive);
            }
            if (!empty($Suspend)) {
                $data->whereIn('suspend',$Suspend);
            }
            if (!empty($Unsuspend)) {
                $data->whereIn('suspend',$Unsuspend);
            }
            if (!empty($Register)) {
                $data->whereIn('Registration_type',$Register);
            }
            if (!empty($Adminconfirm)) {
                $data->whereIn('Registration_type',$Adminconfirm);
            }
            if (!empty($SMSconfirm)) {
                $data->whereIn('Registration_type',$SMSconfirm);
            }
            
            $data = $data->orderBy('u_id','desc')->paginate(10);
            
            $dataCounter= count($data);
    
            $today=date("Y-m-d");
            $yesterday=date("Y-m-d", strtotime( '-1 days' ) );
            $justCounter=0;
            
            /*
            // Get no of visits and last visit for all users
            $allCountUsers=DB::table("$request->system.users_radacct")->select(DB::raw('* ,count(u_id) as visits'))->groupBy('u_id')->get();
            if(isset($allCountUsers))
            {
                foreach($allCountUsers as $userRecord)
                {
                    $visitsOf[$userRecord->u_id]=$userRecord->visits;
                    //if(!isset($lastVisitOf[$userRecord->u_id])){$lastVisitOf[$userRecord->u_id]=$userRecord->dates;}
                    $lastVisitOf[$userRecord->u_id]=$userRecord->dates;
                    //$up=$userRecord->acctinputoctets;
                    //$down=$userRecord->acctoutputoctets;
                    //if(!isset($totalOf[$userRecord->u_id])){$totalOf[$userRecord->u_id]=0;}
                    //else{$totalOf[$userRecord->u_id]+=$up+$down;}
                    //$totalOf[$userRecord->u_id]=$up+$down;
                }
            }
            */
            // Get Group name
            $allGroups=DB::table("$request->system.area_groups")->where('as_system',"0")->get();
            if(isset($allGroups))
            {
                foreach($allGroups as $groupRecord)
                {$groupOf[$groupRecord->id]=$groupRecord->name;}
            }
    
            // Get Branch name
            $allBranchs=DB::table("$request->system.branches")->get();
            if(isset($allBranchs))
            {
                foreach($allBranchs as $branchRecord)
                {$branchOf[$branchRecord->id]=$branchRecord->name;}
            }
    
            // // Get Online users
            // $allOnlineUsers=DB::table("$request->system.radacct")->whereNull('acctstoptime')->groupBy('u_id')->get();
            // if(isset($allOnlineUsers))
            // {
            //     foreach($allOnlineUsers as $onlineRecord)
            //     {$onlineOf[$onlineRecord->u_id]=1;}
            // }
            
            // insert additional informations (no_of_visits )
            foreach ($data as $key => $value) {
                
                // chage u_id to id to remove confelect woth Zapier system integration
                $value->id = $value->u_id;
                // unset($value->u_id);
                // get Visits
                $allUsersSessions=DB::table("$request->system.users_radacct")->select(DB::raw('* ,count(u_id) as visits'))->where('u_id',$value->u_id)->first();
                $noOfVisits = $allUsersSessions->visits;
                // Get last visit
                if($noOfVisits > 0){
                    $getLastVisit = DB::table("$request->system.radacct")->where('u_id', $value->u_id)->orderBy('radacctid','desc')->first();
                    $lastVisit = $getLastVisit->acctstarttime;
                }else{
                    $lastVisit = "";
                }
                

                /*
                // get Visits
                if(isset($noOfVisits)){unset($noOfVisits);}
                if(isset($visitsOf[$value->u_id]))
                {$noOfVisits=$visitsOf[$value->u_id];}
                else{$noOfVisits=0;}

                    // Get last visit
                    if(isset($lastVisit)){unset($lastVisit);}
                    if(isset($lastVisitOf[$value->u_id]))
                    {$lastVisit=$lastVisitOf[$value->u_id];}
                    else{$lastVisit="";}
                    if($today==$lastVisit){$lastVisit="Today";}
                    elseif($yesterday==$lastVisit){$lastVisit="Yesterday";}
                    elseif($lastVisit){$lastVisit="$lastVisit";}
                */

                // Get Total
                // if(isset($userTotal)){unset($userTotal);}
                // if(isset($totalOf[$value->u_id]))
                // {$userTotal=$totalOf[$value->u_id];}
                // else{$userTotal=0;}

                // get GroupName
                if(isset($groupName)){unset($groupName);}
                if(isset($groupOf[$value->group_id]))
                {$groupName=$groupOf[$value->group_id];}
                else{$groupName="";}

                // get BranchName
                if(isset($branchName)){unset($branchName);}
                if(isset($branchOf[$value->branch_id]))
                {$branchName=$branchOf[$value->branch_id];}
                else{$branchName="";}

                // Get OlineUsers
                // if(isset($onlineState)){unset($onlineState);}
                // if(isset($onlineOf[$value->u_id]))
                // {$onlineState=$onlineOf[$value->u_id];}
                // else{$onlineState="0";}

                $onlineState = DB::table("$request->system.radacct")->where('u_id', $value->u_id)->whereNull('acctstoptime')->count() > 0 ? 1 : 0;
                // Get Monthly Usage
                 // get the first day of renwing day to get monthly usage in GB
                $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($request->system, $value->branch_id);
                $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];  
                $monthlyUsageTotal=DB::table("$request->system.radacct")->where('u_id',$value->u_id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum(DB::raw('acctinputoctets + acctoutputoctets'));
                $monthlyTotalUsage=round(($monthlyUsageTotal)/1024/1024/1024,2);
                // Get Total Usage
                $usageTotal=DB::table("$request->system.radacct")->where('u_id',$value->u_id)->sum(DB::raw('acctinputoctets + acctoutputoctets')); // ~ 150 MS
                $totalUsage=round(($usageTotal)/1024/1024/1024,2);

                if ($online=="on") {// check on users who is Offline and remove it
                    if($onlineState==1){$canInsertThisUser=1;}// so user Online NOW...
                    else{$canInsertThisUser=0;}
                }else{$canInsertThisUser=1;}

                // split mobile number
                $userMobile = $value->u_phone;
                $userUname = $value->u_uname;
                if( DB::table("$request->system.settings")->where('type', 'marketing_enable')->value('value') != 1 ){
                    $userMobile = substr($userMobile, 0, -8)."XXXX".substr($userMobile, -4);
                    $userUname = substr($userUname, 0, -8)."XXXX".substr($userUname, -4);
                }
                
                unset($value->bandwidth_package_expiry);
                unset($value->sms_credit);
                unset($value->agilecrm_id);
                
                $value->u_phone = $userMobile;
                $value->u_uname = $userUname;
                $value->last_visit = $lastVisit;
                $value->visits = $noOfVisits;
                $value->online_state = $onlineState;
                $value->group_name = $groupName;
                $value->branch_name = $branchName;
                $value->monthly_usage = $monthlyTotalUsage;
                $value->total_usage = $totalUsage;

                // set all output date time to timezone format ISO-8601
                isset($value->created_at) && $value->created_at!="" ? $value->created_at = date("c", strtotime($value->created_at)) : $value->created_at;
                isset($value->last_visit) && $value->last_visit!="" ? $value->last_visit = date("c", strtotime($value->last_visit)) : $value->last_visit;
                isset($value->monthly_package_start) && $value->monthly_package_start!="" ? $value->monthly_package_start = date("c", strtotime($value->monthly_package_start)) : $value->monthly_package_start = "";
                isset($value->updated_at) && $value->updated_at!="" ? $value->updated_at = date("c", strtotime($value->updated_at)) : $value->updated_at;
                isset($value->last_login_manual) && $value->last_login_manual!="" ? $value->last_login_manual = date("c", strtotime($value->last_login_manual)) : $value->last_login_manual = "";
                $value->last_visit = "2020-05-05T22:00:19+02:00";
            }
                    // $a = array('total'=> 50);
                    // array_push($data,'total:40');
                        // $data['total']=355;
            
            return json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }           
    }

    public function sendWhatsApp(Request $request){

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        $adminData = DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->first();

        // for testing only
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
        DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);
        
        if(  $request->system && $request->token && isset($adminData) ){

            // sending WhatsApp
            $customerID = DB::Table('customers')->where('database', $request->system)->value('id');
            $message = urlencode($request->message);
            $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
            $res = $sendWhatsappMessage->send( "", $request->mobile , $message, $customerID, $request->system, "1", "", "", "1");
            $data = array('state' => $res, 'message' => 'Message was successfully notified to the external system.');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }

    public function updateBranch(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            if($request->key1 != ""){ DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ $request->key1 => $request->value1]); }
            if($request->key2 != ""){ DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ $request->key2 => $request->value2]); }
            if($request->key3 != ""){ DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ $request->key3 => $request->value3]); }
            if($request->key4 != ""){ DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ $request->key4 => $request->value4]); }

            // $data = array('state' => 1, 'message' => 'Done.');
            // return $msg = json_encode($data);
            // $loginData = array('system' => $request->system, 'token' => $request->token);
            return $this->getBranches($request);

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function addLoadBalancingLine(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            foreach($request->lines as $line){
                DB::table("$request->system.load_balancing")->insert([['branch_id' => $request->branch_id, 'type' => '0', 'ip' => $line['ip'], 'gateway' => $line['gateway'], 'speed' => $line['speed'] ]]);
            }
            // update change state to force system to update Mikrotik
            DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ 'change_load_balance_state' => '1' ]);
            $data = array('state' => 1, 'message' => 'Insert new load balanceing line has been successfully.');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function updateLoadBalancingLine(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            DB::table("$request->system.load_balancing")->where( 'id', $request->id )->update([ 'ip' => $request->ip, 'gateway' => $request->gateway, 'speed' => $request->speed]);
            // update change state to force system to update Mikrotik
            DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ 'change_load_balance_state' => '1' ]);

            $data = array('state' => 1, 'message' => 'updateLoadBalancingLine Done.');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function deleteLoadBalancingLine(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            DB::table("$request->system.load_balancing")->where('id', $request->id)->delete();
            // update change state to force system to update Mikrotik
            DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ 'change_load_balance_state' => '1' ]);

            $data = array('state' => 1, 'message' => 'deleteLoadBalancingLine Done.');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function addEditLoadBalancingLines(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            foreach($request->lines as $line){
                // check if this record is new
                if($line['id']==""){
                    DB::table("$request->system.load_balancing")->insert([['branch_id' => $request->branch_id, 'type' => '0', 'ip' => $line['ip'], 'gateway' => $line['gateway'], 'speed' => $line['speed'] ]]);
                }else{
                    // update
                    DB::table("$request->system.load_balancing")->where( 'id', $line['id'] )->update([ 'ip' => $line['ip'], 'gateway' => $line['gateway'], 'speed' => $line['speed']]);
                }
                
            }
            // update change state to force system to update Mikrotik
            DB::table("$request->system.branches")->where( 'id', $request->branch_id )->update([ 'change_load_balance_state' => '1' ]);
            $data = array('state' => 1, 'message' => 'Insert and update new load balanceing lines has been successfully.');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function getUserInfo(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
            $userData = $whatsappClass->getAllCustomerInfoArray($request->system, $request->userId, $created_at, '1');
        
            $data = array('state' => 1, 'message' => 'return user data', 'data'=>$userData);
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }
    // to serve suspend and unsuspend functions in MobileApp and ChatBot
    public function suspendUnsuspend($id,$v, $adminID=400, $database){
        date_default_timezone_set("Africa/Cairo");
        $v = ($v == 'true')? 0 : 1;
        // Update state into user record
        DB::table("$database.users")->where('u_id', '=', $id)->update(['suspend'=>$v]);

        // Insert log in history table to block user in Mikrotik in each branch
        $state = ($v == '1')? "suspend_user" : "unsuspend_user";
        $allUserMac = DB::table("$database.users")->where('u_id', $id)->value('u_mac');
        $date = date('Y-m-d', strtotime(Carbon::now()));
        $time = date('H:i:s', strtotime(Carbon::now()));
        foreach(DB::table("$database.branches")->where('state', '1')->get() as $branch){
            DB::table("$database.history")->insert([['add_date' => $date, 'add_time' => $time, 'type1' => 'suspend_unsuspend_user', 'type2' => 'admin', 'operation' => "$state", 'details' => 1, 'notes' => $allUserMac, 'a_id' => $adminID, 'u_id' => $id, 'branch_id' => $branch->id]]);
        }
        
        // Disconnect online session
        $getUserData = DB::table("$database.radacct")->where('u_id',$id)->whereNull('acctstoptime')->orderBy('radacctid', 'desc')->first(); 
        if(isset($getUserData)){
            $radacct_id = $getUserData->radacctid;
            $geted_User_Name = $getUserData->username;
            $geted_Framed_IP_Address = $getUserData->framedipaddress;
            $geted_nasipaddress = $getUserData->nasipaddress;
            $geted_branch_id = $getUserData->branch_id;
            $geted_u_id = $getUserData->u_id;
            $acctuniqueid = $getUserData->acctuniqueid;

            //Branch Data
            $getbranchdata = DB::table("$database.branches")->where('id',$geted_branch_id)->first();
            $geted_secret = $getbranchdata->Radiussecret;
            $coaport = $getbranchdata->Radiusport;
            $ip = $getbranchdata->ip;
            $radiusType = $getbranchdata->radius_type;
            
            if($radiusType == "mikrotik"){
                DB::table("$database.radacct")->where('acctuniqueid',$acctuniqueid)->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
            }else{
                // disconnect user from shell if branch type Aruba or DDWRT
                $beExecuted='echo User-Name='.$geted_User_Name.',Framed-IP-Address='.$geted_Framed_IP_Address.' | radclient -x '.$ip.':'.$coaport.' disconnect '.$geted_secret.'  2>&1 ';
                exec($beExecuted, $output);
            }
        }
    }

    public function suspendUser(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
             // we will suspend him directly
             $this->suspendUnsuspend($request->userId, 'false', '500', $request->system);
             return json_encode(array('state' => 1, 'message' =>'suspension has been successfully'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function unsuspendUser(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
             // we will suspend him directly
             $this->suspendUnsuspend($request->userId, 'true', '500', $request->system);
             return json_encode(array('state' => 1, 'message' =>'unsuspension has been successfully'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function getSearchFilter(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");

        if(  $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            $groupFilter = array();
            foreach (DB::table($request->system.'.area_groups')->where('as_system','0')->get() as $key => $value) {
                array_push($groupFilter, array('id' =>$value->id, 'name' => $value->name, 'state' => $value->is_active ) );
            }

            $branchFilter = array();
            foreach (DB::table($request->system.'.branches')->get() as $key => $value) {
                array_push($branchFilter, array('id' =>$value->id, 'name' => $value->name, 'state' => $value->state ) );
            }
            
            $countryFilter = array();
            foreach (DB::table($request->system.'.users')->groupBy('u_country')->where('u_country', '!=', null)->get() as $key => $value) {
                array_push($countryFilter, array('name' => $value->u_country ) );
            }


            
            return json_encode(['state' => 1, 'group_filter' => $groupFilter, 'branch_filter' => $branchFilter, 'countryFilter' => $countryFilter]);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }           
    }

    public function deleteUser(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
             // we will delete him directly
             DB::table($request->system.'.users')->where('u_id', '=', $request->userId)->delete();
             // remove this user id from any record in Hosts table to avoid any errors
             DB::table($request->system.'.hosts')->where('u_id',$request->userId)->update(['u_id' => '0']);
             return json_encode(array('state' => 1, 'message' =>'deleting user has been successfully'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }    
    }

    public function editUser(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            // we will edit him directly
            $user = [];
            
            $user['updated_at'] = $todayDateTime;
            isset($request->name)? $user['u_name'] = $request->name: '';
            isset($request->address)? $user['u_address'] = $request->address: '';
            isset($request->gender)? $user['u_gender'] = $request->gender: '';
            isset($request->lang)? $user['u_lang'] = $request->lang: '';
            isset($request->country)? $user['u_country'] = $request->country: '';
            isset($request->notes)? $user['notes'] = $request->notes: '';
            isset($request->email)? $user['u_email'] = $request->email: '';
            isset($request->mobile)? $user['u_phone'] = $request->mobile: '';
            isset($request->mac)? $user['u_mac'] = $request->mac: '';
            isset($request->branch_id)? $user['branch_id'] = $request->branch_id: '';
            isset($request->group_id)? $user['group_id'] = $request->group_id: '';
            
            // DISCONNECT WIFI :-> mark all active sessions to stop
            DB::table($request->system.'.radacct')->where('u_id',$request->userId)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
        
            // $user->update(); 
            DB::table($request->system.'.users')->where('u_id', $request->userId)->update($user); 
            return json_encode(array('state' => 1, 'message' =>'editing user has been successful'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }
    
    public function createUser(Request $request){
        // used by mobile app and COMO CRM API
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            if( substr($request->mobile, 0, 2)=="20" ){ $mobileWithoutCountryCode = substr($request->mobile, 1); $u_country = "Egypt"; }
            elseif( substr($request->mobile, 0, 3)=="966" ){ $mobileWithoutCountryCode = "0".substr($request->mobile, 3);  $u_country = "Saudi Arabia"; }
            elseif( substr($request->mobile, 0, 3)=="971" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "United Arab Emirates"; }
            elseif( substr($request->mobile, 0, 3)=="965" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Kuwait"; }
            elseif( substr($request->mobile, 0, 3)=="905" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Canada"; }
            elseif( substr($request->mobile, 0, 2)=="41" ){ $mobileWithoutCountryCode = substr($request->mobile, 2);   $u_country = "Switzerland"; }
            elseif( substr($request->mobile, 0, 3)=="491" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Germany"; }
            elseif( substr($request->mobile, 0, 3)=="316" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Netherlands"; }
            elseif( substr($request->mobile, 0, 2)=="44" ){ $mobileWithoutCountryCode = substr($request->mobile, 2);   $u_country = "United Kingdom"; }
            elseif( substr($request->mobile, 0, 3)=="393" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Italy"; }
            elseif( substr($request->mobile, 0, 3)=="336" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "France"; }
            elseif( substr($request->mobile, 0, 3)=="973" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Bahrain"; }
            elseif( substr($request->mobile, 0, 3)=="974" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Qatar"; }
            elseif( substr($request->mobile, 0, 3)=="964" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Iraq"; }
            elseif( substr($request->mobile, 0, 3)=="961" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Lebanon"; }
            elseif( substr($request->mobile, 0, 3)=="962" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Jordan"; }
            elseif( substr($request->mobile, 0, 3)=="220" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Gambia"; }
            elseif( substr($request->mobile, 0, 3)=="970" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Palestine"; }
            elseif( substr($request->mobile, 0, 3)=="972" ){ $mobileWithoutCountryCode = substr($request->mobile, 3);  $u_country = "Israel"; }

            if(!isset($request->email)){$request->email = " ";}
            if(!isset($request->country) and isset($u_country)){ $request->country = $u_country; }

            $user = [];
            $user['created_at'] = $todayDateTime;
            $user['Registration_type'] = '2';
            $user['u_state'] = '1';
            $user['suspend'] = '0';
            $user['u_uname'] = $mobileWithoutCountryCode;
            $user['u_password'] = $request->mobile;
            $user['network_id'] = DB::table($request->system.".networks")->where('state','1')->value('id');

            isset($request->name)? $user['u_name'] = $request->name: '';
            isset($request->address)? $user['u_address'] = $request->address: '';
            isset($request->gender)? $user['u_gender'] = $request->gender: '';
            isset($request->lang)? $user['u_lang'] = $request->lang: '';
            isset($request->country)? $user['u_country'] = $request->country: '';
            isset($request->notes)? $user['notes'] = $request->notes: '';
            isset($request->email)? $user['u_email'] = $request->email: '';
            isset($request->mobile)? $user['u_phone'] = $request->mobile: '';
            isset($request->mac)? $user['u_mac'] = $request->mac: '';
            isset($request->branch_id)? $user['branch_id'] = $request->branch_id: $user['branch_id']= DB::table($request->system.".branches")->where('state','1')->value('id');
            isset($request->group_id)? $user['group_id'] = $request->group_id: $user['group_id']=DB::table($request->system.".area_groups")->where('name','Default')->orWhere('name','default')->value('id');
            
            $newUserId = DB::table($request->system.'.users')->insertGetId($user); 
            return json_encode(array('state' => 1, 'new_user_id' => $newUserId ,'message' =>'User has been created successfully'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }
    // admin click removeBypass from MobileApp
    public function removeBypass(Request $request){

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            DB::table($request->system.'.bypassed')->where('mac', $request->mac)->update(['change_state' => '2']);
            DB::table($request->system.'.hosts')->where('mac',$request->mac)->delete();
            return json_encode(array('state' => 1, 'message' =>'removing bypassing from system has been successfully'));

        }else{
            return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
        }    
    }
    // admin click createBypass from MobileApp
    public function createBypass(Request $request){

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            DB::table($request->system.'.bypassed')->insert(['branch_id' => $request->branch_id, 'mac' => $request->mac, 'port' => $request->port, 'change_state' => '1', 'state' => '0', 'created_at' => Carbon::now() ]); 
            return json_encode(array('state' => 1, 'message' =>'Bypassing from system has been created successfully'));

        }else{
            return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
        }    
    }
    
    // admin click assign device to user from MobileApp
    public function assignDeviceToUser(Request $request){

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            $user = DB::table($request->system.'.users')->where('u_id',$request->userId)->first();
            $countMacForUsers=count (explode(",",$user->u_mac)); 
            
            if(!isset($user->u_mac) or $user->u_mac=="")
            {   // not found mac in db so we will Update New Mac direct
                DB::table($request->system.'.users')->where('u_id',$request->userId)->update(['u_mac'=>$request->mac]); 
            }
            elseif(isset($user->u_mac) and $user->u_mac!="")
            {
                // found mac or more so we can add new mac without problem with ,
                $addNewMac=$user->u_mac.",".$request->mac;
                DB::table($request->system.'.users')->where('u_id',$request->userId)->update(['u_mac'=>$addNewMac]); 
            }

            return json_encode(array('state' => 1, 'message' =>'assigning device to user has been created successfully'));

        }else{
            return json_encode(array('state' => 0, 'message' => 'unauthorized.'));
        }    
    }

    public function getGroups(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
        
        if(  $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
                
            // get branches details
            if(isset($request->id)){
                $data =DB::table("$request->system.groups_network")->where('as_system', '0')->where('id', $request->id)->paginate(10);
            }else{
                $data =DB::table("$request->system.groups_network")->where('as_system', '0')->paginate(10);
            }
            foreach ($data as $key => $value) {

                 // get the first day of renwing day to get monthly usage in GB
                $gettingFirstAndLastDayInQuotaPeriod = $whatsappClass->getFirstAndLastDayInQuotaPeriod ($request->system, $value->id);
                $firstDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['firstDayOfQuotaPeriod'];
                $lastDayOfQuotaPeriod = $gettingFirstAndLastDayInQuotaPeriod['lastDayOfQuotaPeriod'];    

                // get total and online users
                $value->count_online = DB::table("$request->system.radacct_active_users")->where('group_id', $value->id)->count();
                $value->count_users = DB::table("$request->system.users")->where('group_id', $value->id)->count();
                
                // get Monthly Usage
                $monthlyUsageUpload = DB::table("$request->system.radacct")->where('group_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctinputoctets');
                $monthlyUsageDownload = DB::table("$request->system.radacct")->where('group_id', $value->id)->whereBetween('dates',[$firstDayOfQuotaPeriod, $lastDayOfQuotaPeriod])->sum('acctoutputoctets');
                $monthlyTotalUsage = round(($monthlyUsageUpload + $monthlyUsageDownload)/1024/1024/1024,1)." GB";
                $value->monthly_usage = $monthlyTotalUsage;
                
                // Get Total Usage
                $usageUpload = DB::table("$request->system.radacct")->where('group_id',$value->id)->sum('acctinputoctets');
                $usageDownload = DB::table("$request->system.radacct")->where('group_id',$value->id)->sum('acctoutputoctets');
                $totalUsage = round(($usageUpload+$usageDownload)/1024/1024/1024,2)." GB";
                $value->total_usage = $totalUsage;
            }
            // $branchesDetails = json_encode($data);

            return json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }           
    }
    
    public function editGroup(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            // we will edit him directly
            $group = [];
            
            $group['updated_at'] = $todayDateTime;
            isset($request->name)? $group['name'] = $request->name: '';
            isset($request->is_active)? $group['is_active'] = $request->is_active: '';
            isset($request->url_redirect)? $group['url_redirect'] = $request->url_redirect: '';
            isset($request->url_redirect_Interval)? $group['url_redirect_Interval'] = $request->url_redirect_Interval: '';
            isset($request->session_time)? $group['session_time'] = $request->session_time: '';
            isset($request->port_limit)? $group['port_limit'] = $request->port_limit: '';
            isset($request->idle_timeout)? $group['idle_timeout'] = $request->idle_timeout: '';
            isset($request->quota_limit_upload)? $group['quota_limit_upload'] = $request->quota_limit_upload: '';
            isset($request->quota_limit_download)? $group['quota_limit_download'] = $request->quota_limit_download: '';
            isset($request->quota_limit_total)? $group['quota_limit_total'] = $request->quota_limit_total: '';
            isset($request->if_downgrade_speed)? $group['if_downgrade_speed'] = $request->if_downgrade_speed: '';
            isset($request->speed_limit)? $group['speed_limit'] = $request->speed_limit: '';
            isset($request->end_speed)? $group['end_speed'] = $request->end_speed: '';
            isset($request->limited_devices)? $group['limited_devices'] = $request->limited_devices: '';
            isset($request->notes)? $group['notes'] = $request->notes: '';
            isset($request->url_filter_state)? $group['url_filter_state'] = $request->url_filter_state: '';
            
            // DISCONNECT WIFI :-> mark all active sessions to stop
            DB::table($request->system.'.radacct')->where('group_id',$request->id)->whereNull('acctstoptime')->update(['acctstoptime' => Carbon::now(), 'realm'=>'1']); 
        
            // $group->update(); 
            DB::table($request->system.'.area_groups')->where('id', $request->id)->update($group); 
            return json_encode(array('state' => 1, 'message' =>'editing group has been successfully'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }

    public function deleteGroup(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            
            // delete group
            DB::table("$request->system.area_groups")->where('id',$request->id)->delete();
            
            // assign all users to default group
            if(DB::table("$request->system.area_groups")->where('name','Default')){
                $newGroup = DB::table("$request->system.area_groups")->where('name','Default')->first();
            }elseif(DB::table("$request->system.area_groups")->where('name','default')){
                $newGroup = DB::table("$request->system.area_groups")->where('name','default')->first();
            }elseif(DB::table("$request->system.area_groups")->where('is_active','1')){
                $newGroup = DB::table("$request->system.area_groups")->where('is_active','1')->orderBy('id','asc')->first();
            }else{
                $newGroup = "notfound";
            }
            
            if($newGroup != "notfound"){
                DB::table("$request->system.users")->where('group_id',$request->id)->update(['group_id'=>$newGroup->id]);
            }
            return json_encode(array('state' => 1, 'message' =>'deleting group has been successfully'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }

    public function createGroup(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
           
            $group = [];
            $group['created_at'] = $todayDateTime;
            $group['is_active'] = '1';
            $group['as_system'] = '0';
            $group['radius_type'] = 'mikrotik';
            $group['renew'] = "1";
            $group['network_id'] = DB::table("$request->system.networks")->where('state', '1')->value('id');
            $group['change_url_filter'] = "0";
            $group['url_filter_type'] = "0";
            $group['url_filter_state'] = "0";

            isset($request->name)? $group['name'] = $request->name: '';
            isset($request->url_redirect)? $group['url_redirect'] = $request->url_redirect: '';
            isset($request->url_redirect_Interval)? $group['url_redirect_Interval'] = $request->url_redirect_Interval: '';
            isset($request->session_time)? $group['session_time'] = $request->session_time: '';
            isset($request->port_limit)? $group['port_limit'] = $request->port_limit: '';
            isset($request->idle_timeout)? $group['idle_timeout'] = $request->idle_timeout: '';
            isset($request->quota_limit_upload)? $group['quota_limit_upload'] = $request->quota_limit_upload: '';
            isset($request->quota_limit_download)? $group['quota_limit_download'] = $request->quota_limit_download: '';
            isset($request->quota_limit_total)? $group['quota_limit_total'] = $request->quota_limit_total: '';
            isset($request->if_downgrade_speed)? $group['if_downgrade_speed'] = $request->if_downgrade_speed: '';
            isset($request->speed_limit)? $group['speed_limit'] = $request->speed_limit: '';
            isset($request->end_speed)? $group['end_speed'] = $request->end_speed: '';
            isset($request->limited_devices)? $group['limited_devices'] = $request->limited_devices: '';
            isset($request->notes)? $group['notes'] = $request->notes: '';
            isset($request->url_filter_state)? $group['url_filter_state'] = $request->url_filter_state: '';
            
         
            $newGroupId = DB::table($request->system.'.users')->insertGetId($group); 
            return json_encode(array('state' => 1, 'new_group_id' => $newGroupId ,'message' =>'Group has been created successfully'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }

    public function loyaltyPoints(Request $request){
        // used by COMO CRM API
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$todayDateTime = $today." ".date("H:i:s");
        if( $request->system && $request->token && DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->count() > 0 ){
            // check if the mobile number or user_id is exist 
            if(isset($request->mobile)){
                $userData = DB::table($request->system.'.users')->where('u_phone',$request->mobile)->first();
                if(isset($userData->u_id)){$userID = $userData->u_id;}
            }elseif(isset($request->user_id)){
                $userData = DB::table($request->system.'.users')->where('u_id',$request->user_id)->first();
                if(isset($userData->u_id)){$userID = $userData->u_id;}
            }

            if(!isset($userID)){
                return json_encode(array('state' => 0 ,'message' =>'user not found.'));
            }
            $record = [];
            if($request->type=="add"){$record['type'] = '1';}
            elseif($request->type=="refund"){$record['type'] = '0';}
            elseif($request->type=="redeem"){$record['type'] = '2';}
            $record['created_at'] = $todayDateTime;
            $record['state'] = '1';
            $record['a_id'] = '0';
            $record['u_id'] = $userID;
            isset($request->amount)? $record['amount'] = $request->amount: $record['amount'] ='0';
            $record['points'] = $request->points;
            $record['notes'] = $request->notes;
            // if request from Microsoft dynamics POS
            if( isset($request->source) ){ 
                $record['notes'] = $request->source;
                // sending SMS message
                if(isset($request->notificationMsg) and $request->notificationMsg!=""){
                    // // send SMS
                    $sendmessage = new App\Http\Controllers\Integrations\SMS();
                    $sendmessage->send($request->mobile, $request->notificationMsg);

                    // // sending SMS from Microsystem
                    // $microsystemSMSserver = new App\Http\Controllers\ApiController();
                    // $microsystemSMSserver->sendMicrosystemSMS($request->system, 'text', $request->mobile, $request->notificationMsg);
                    
                    // the final way to send whatsapp messeges in 4shopping mall
                    $customerID = DB::Table('customers')->where('database', $request->system)->value('id');
                    $message = urlencode($request->notificationMsg);
                    $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                    $res = $sendWhatsappMessage->send( "", $request->mobile , $message, $customerID, $request->system, "1", "", "", "1");
                }

            } 
           
            DB::table($request->system.'.loyalty_points')->insert($record);
            return json_encode(array('state' => 1 ,'message' =>'Loyalty points have been registered successfully.'));

        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }


   
    // for requests from Microsoft dynamics POS
    public function sendNotificationMsg(Request $request){

        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        $adminData = DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->first();

        if(  $request->system && $request->token && isset($adminData) ){

            // // send SMS
            $sendmessage = new App\Http\Controllers\Integrations\SMS();
            $sendmessage->send($request->mobile, $request->notificationMsg);

            // // sending WhatsApp
            $customerID = DB::Table('customers')->where('database', $request->system)->value('id');
            $message = urlencode($request->notificationMsg);
            $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
            $res = $sendWhatsappMessage->send( "", $request->mobile , $message, $customerID, $request->system, "1", "", "", "1");

            // sending from Microsystem SMS account
            // $microsystemSMSserver = new App\Http\Controllers\ApiController();
            // $microsystemSMSserver->sendMicrosystemSMS($request->system, 'text', $request->mobile, $request->notificationMsg);
            
            // response
            $data = array('state' => '1', 'message' => 'Message was successfully notified to the external system.');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }

    

    // for requests from Microsoft dynamics POS
    public function createCouponCode(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
		$created_at = $today." ".date("H:i:s");
        $adminData = DB::table("$request->system.admins")->where('mobileapp_token', $request->token)->first();

        if(  $request->system && $request->token && isset($adminData) ){

            // 1st step: create new campaign as the Coupon title to be showen in the whatsapp first menu
                // check if campaign exist or not
                $chaeckIfCampaignExist = DB::table("$request->system.campaigns")->where('campaign_name',$request->couponTitle)->first();
                if(isset($chaeckIfCampaignExist)){
                    $campaignId = $chaeckIfCampaignExist->id;
                }else{
                    $campaignId = DB::table("$request->system.campaigns")->insertGetId(['campaign_name' => $request->couponTitle, 'ad_name' => $request->couponTitle, 'offer_desc' => $request->couponTitle, 'description' => 'created by External POS', 'type' => 'offer', 'startdate' => $today, 'offer_terms' => 'No conditions', 'created_at' => $created_at]);
                }

            // 2ns step: create the offer code to be showen in the whatsapp first menu
                // obtaining user ID
                $userID = DB::table("$request->system.users")->where('u_phone', 'like', $request->mobile)->value('u_id');
                DB::table("$request->system.campaign_statistics")->insert([['type' => 'offer', 'campaign_id' => $campaignId, 'u_id' => $userID, 'state' => '0', 'offer_code' => $request->couponCode, 'created_at' => $created_at]]);
            // 3rd step: send notification message
            if(isset($request->notificationMsg) and $request->notificationMsg!=""){
                // $microsystemSMSserver = new App\Http\Controllers\ApiController();
                // $microsystemSMSserver->sendMicrosystemSMS($request->system, 'text', $request->mobile, $request->notificationMsg);

                // send SMS
                $sendmessage = new App\Http\Controllers\Integrations\SMS();
                $sendmessage->send($request->mobile, $request->notificationMsg);

                // // // sending WhatsApp
                // $customerID = DB::Table('customers')->where('database', $request->system)->value('id');
                // $message = urlencode($request->notificationMsg);
                // $sendWhatsappMessage = new App\Http\Controllers\Integrations\WhatsApp();
                // $res = $sendWhatsappMessage->send( "", $request->mobile , $message, $customerID, $request->system, "1", "", "", "1");

            }
            
            // response
            $data = array('state' => '1', 'message' => 'Coupon code has been created successfully');
            return $msg = json_encode($data);
        }else{
            $data = array('state' => 0, 'message' => 'unauthorized.');
            return $msg = json_encode($data);
        }
    }


    public function retrieveMicrosystemSMS(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		
        // identify Mikrotik SMS server ports
        $vodafonePort = "usb1"; //usb1
        $wePort = "usb2";
        $etisalatPort = "usb3";
        $orangePort = "usb4"; // switched from ORANGE `USB4` to WE `USB2`

        $mikrotikScript = "";
        $counter = 0;
        foreach( DB::table("sms")->where('state', '0')->get() as $sms ){
            $counter++;
            // delete all marked before
            if($counter == 1){ DB::table("sms")->where('last_retrieved','1')->update(['last_retrieved' => '0']); }
            // set port according to operator
            if($sms->operator == "Vodafone"){$port = $vodafonePort; DB::table("settings")->where('type','Mikrotik1_vodafoneCredit')->decrement('value', 1);}
            // if($sms->operator == "Vodafone"){$port = $vodafonePort; DB::table("settings")->where('type','Mikrotik1_orangeCredit')->decrement('value', 5);}
            if($sms->operator == "Etisalat"){$port = $etisalatPort; DB::table("settings")->where('type','Mikrotik1_etisalatCredit')->decrement('value', 1);}
            if($sms->operator == "Orange"){$port = $orangePort; DB::table("settings")->where('type','Mikrotik1_orangeCredit')->decrement('value', 1);}
            // if($sms->operator == "Orange"){$port = $orangePort; DB::table("settings")->where('type','Mikrotik1_weCredit')->decrement('value', 1);}
            if($sms->operator == "We"){$port = $wePort; DB::table("settings")->where('type','Mikrotik1_weCredit')->decrement('value', 1);}

            $mikrotikScript.=':log warning "SMS sent to: '.$sms->mobile.', From:'.$port.', Message: '.$sms->message.'";'."\n";
            $mikrotikScript.=':do { /tool sms send '.$port.' "+'.$sms->mobile.'" channel=2 message="'.$sms->message.'"; } on-error={ :log error "Microsystem SMS Error, SENDING api/errorLastRetrievedMicrosystemSMS"; '."\n".' /tool fetch url="https://demo.microsystem.com.eg/api/errorLastRetrievedMicrosystemSMS?identify=$identify&serial=$serial&realm=$realm&smsId='.$sms->id.'" mode=http; }'."\n";
            DB::table("sms")->where('id',$sms->id)->update(['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '0', 'last_retrieved' => '1']);
            unset($port);
        }
        
        DB::table("settings")->where('type', 'mikrotikSmsLastCheck')->update(['updated_at' => $todayDateTime]);
        
        return $mikrotikScript;

    }

    // if mikrotik unaple to send SMS
    public function errorLastRetrievedMicrosystemSMS(Request $request){
    
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
        $SMSProvider_username = DB::table("settings")->where('type', 'SMSProviderusername')->value('value');
        $SMSProvider_password = DB::table("settings")->where('type', 'SMSProviderpassword')->value('value');
        $SMSProvider_sendername = DB::table("settings")->where('type', 'SMSProvidersendername')->value('value');
        
        $sms=DB::table("sms")->where('id', $request->smsId)->first();
        
        // refund Mikrotik SMS credit
        if($sms->operator == "Vodafone"){DB::table("settings")->where('type','Mikrotik1_vodafoneCredit')->increment('value', 1);}
        // if($sms->operator == "Vodafone"){DB::table("settings")->where('type','Mikrotik1_orangeCredit')->increment('value', 5);}
        if($sms->operator == "Etisalat"){DB::table("settings")->where('type','Mikrotik1_etisalatCredit')->increment('value', 1);}
        if($sms->operator == "Orange"){DB::table("settings")->where('type','Mikrotik1_orangeCredit')->increment('value', 1);}
        // if($sms->operator == "Orange"){DB::table("settings")->where('type','Mikrotik1_weCredit')->increment('value', 1);}
        if($sms->operator == "We"){DB::table("settings")->where('type','Mikrotik1_weCredit')->increment('value', 1);}
        
        // send SMS Message by SMS Misr
        $data = ['Username' => $SMSProvider_username, 'password' => $SMSProvider_password, 'language' => '1', 'sender' => $SMSProvider_sendername, 'Mobile' => $sms->mobile, 'message' => $sms->message];
        $msg = json_encode($data); // Encode data to JSON
        $url = 'https://smsmisr.com/api/v2/?';
        $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
        $response = json_decode(@file_get_contents($url, FALSE, $context));
        if($response->code == 1901){
            // SMS sent successfully by SMS Misr
            DB::table("settings")->where('type','smsMisrCredit')->decrement('value', 1);
            // update this SMS
            DB::table("sms")->where('id',$sms->id)->update(['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '1', 'last_retrieved' => '0']);
        }else{
            // failure in SMS Misr, update this SMS
            DB::table("sms")->where('id',$sms->id)->update(['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '2', 'last_retrieved' => '0']);
        }


        // sending email to notify credit
        // check credit
        $Mikrotik1_vodafoneCredit = DB::table("settings")->where('type','Mikrotik1_vodafoneCredit')->value('value');
        $Mikrotik1_etisalatCredit = DB::table("settings")->where('type','Mikrotik1_etisalatCredit')->value('value');
        $Mikrotik1_orangeCredit = DB::table("settings")->where('type','Mikrotik1_orangeCredit')->value('value');
        $Mikrotik1_weCredit = DB::table("settings")->where('type','Mikrotik1_weCredit')->value('value');
        $smsMisrCredit = DB::table("settings")->where('type','smsMisrCredit')->value('value');
        
        // $content = "Dear Microsystem support team, <br> <font color=red> Error sending in $sms->operator SMS,</font> Plasee check the error or add credit more than 100 point <br>
        // <strong> Vodafone Credit: $Mikrotik1_vodafoneCredit </strong> <br>=> <a targe='_blank' href='https://web.vodafone.com.eg/auth'> Recharge Vodafone.</a> username: 01011539990| Password: 1403636_Mra  <br>
        // <strong> Etisalat Credit: $Mikrotik1_etisalatCredit </strong> <br>=> <a targe='_blank' href='https://www.etisalat.eg/LoginApp/'> Recharge Etisalat.</a> username: support@microsystem.com.eg | Password: 1403636Mra  <br>
        // <strong> Orange Credit: $Mikrotik1_orangeCredit </strong> <br>=> <a targe='_blank' href='https://www.orange.eg/en/myaccount/login'> Recharge Orange.</a> username: 01277418871 | Password: 1403636mra   <br>
        // <strong> WE Credit: $Mikrotik1_weCredit </strong> <br>=> <a targe='_blank' href='https://my.te.eg/'> Recharge WE.</a> username: 01556300735 | Password: 1403636mra   <br>
        // <strong> SMS Misr Credit: $smsMisrCredit </strong> <br>=> <a targe='_blank' href='https://smsmisr.com/user'> Recharge SMS Misr.</a> username: a.mansour@microsystem.com.eg | Password: 1403636mra   <br>
        // <br>
        // Thanks,<br>
        // Best Regards.<br>";
        // $from = "support@microsystem.com.eg";
        // $subject = "Error in $sms->operator SMS server";
        // $customerEmailArray = array('mr.ahmed@microsystem.com.eg', 'a.mansour@microsystem.com.eg');
        // $customerName = "Microsystem SMS server";
        // Mail::send('emails.send', ['title' => $subject, 'content' => $content], function ($message) use ($customerEmailArray, $customerName, $from, $subject) {
        //     $message->from($from, $customerName);
        //     $message->to($customerEmailArray, $customerName)->subject($subject);
        // });
        


        /* // stopped bacause of error in Mikrotik (always request this function without reason)
        foreach( DB::table("sms")->where('last_retrieved', '1')->get() as $sms ){
            // send SMS Message
            $data = ['Username' => $SMSProvider_username, 'password' => $SMSProvider_password, 'language' => '1', 'sender' => $SMSProvider_sendername, 'Mobile' => $sms->mobile, 'message' => $sms->message];
            $msg = json_encode($data); // Encode data to JSON
            $url = 'https://smsmisr.com/api/v2/?';
            $context = stream_context_create(array('http' => array('method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => "$msg")));
            $response = json_decode(@file_get_contents($url, FALSE, $context));
            DB::table("settings")->where('type','smsMisrCredit')->decrement('value', 1);
            // update this SMS
            DB::table("sms")->where('id',$sms->id)->update(['state' => '1', 'sent_at' => $todayDateTime, 'sent_by' => '1', 'last_retrieved' => '0']);
        }
        */

    }

     // in case we are using VictoryLink SMS OTP with SMS tracking function (DLR), we will receive two state of message:
        // 1: deleverd to Mobile Operator
        // 2: deleverd to Mobile phone
    public function smsStatusDLR(Request $request){
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");

        ///////////////////////////////////////////////// LOG ///////////////////////////////////////////////////
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $body = @file_get_contents('php://input');
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => "$todayDateTime", 'value3' => $body]]);
        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        DB::table("sms")->where( 'guid',$request->userSMSId)->update([ 'delevery_status' => $request->dlrResponseStatus, 'delevery_time' => $todayDateTime ]);
        return '1';
    }

    
}