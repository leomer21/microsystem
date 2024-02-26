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

// this function should run each minute to send the public holiday, birthdate, checkin, checkout, animation program, generic weekly, monthly, annually emails, Whatsapp, SMSs
class CronSendHotelGuestNotificationsStep2 extends Controller
{

    public function cronSendHotelGuestNotificationsStep2(Request $request)
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

            foreach(DB::table("$Customer->database.scheduled_notifications")->where('state', '1')->limit(2)->get() as $notification){
                
                // check if this message needs chatGPT or this is final message
                if($notification->final_message == "'0'" and $notification->chatgpt_content !="0"){
                    
                    if($notification->type == "1"){

                        // call chatGPT Email sending without waiting
                        echo "Sending Email Notification ID: $notification->id to $notification->email <br>";
                        $this->sendNotificationUsingChatGptWithoutWaiting($notification->database, $notification->id, 'sendEmailNotificationUsingChatGptWithoutWaiting');
                        // disable this notification
                        DB::table("$Customer->database.scheduled_notifications")->where('id',$notification->id)->update(['state' => '0', 'updated_at' => $created_at]); 

                    }elseif($notification->type == "2"){

                        // call chatGPT Whatsapp sending without waiting
                        echo "Sending Whatsapp Notification ID: $notification->id to $notification->mobile <br>";
                        $this->sendNotificationUsingChatGptWithoutWaiting($notification->database, $notification->id, 'sendWhatsappNotificationUsingChatGptWithoutWaiting');
                        // disable this notification
                        DB::table("$Customer->database.scheduled_notifications")->where('id',$notification->id)->update(['state' => '0', 'updated_at' => $created_at]); 

                    }elseif($notification->type == "3"){

                        // call chatGPT SMS sending without waiting
                        echo "Sending SMS Notification ID: $notification->id to $notification->mobile <br>";
                        $this->sendNotificationUsingChatGptWithoutWaiting($notification->database, $notification->id, 'sendSMSNotificationUsingChatGptWithoutWaiting');
                        // disable this notification
                        DB::table("$Customer->database.scheduled_notifications")->where('id',$notification->id)->update(['state' => '0', 'updated_at' => $created_at]); 

                    }
                }

            }
            
        }
    }

    // validate only one email and return 1 or 0
    public function validateEmail($email){
        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'required|email']
        );
    
        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return 0;
        }
        return 1;
    }

    // validate only one mobile and return 1 or 0
    public function validateMobile($mobile){
        $validator = Validator::make(
            ['mobile' => $mobile],
            ['mobile' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10']]
        );

        if ($validator->fails()) {
            // return response()->json(['error' => $validator->errors()], 400);
            return 0;
        }
        return 1;
    }

    /////////////////////////////Fire and Forget HTTP Request //////////////////////////
    public function sendNotificationUsingChatGptWithoutWaiting($database, $notificationId, $apiFunction){
    
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $body = @file_get_contents('php://input');
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '1 - '.$apiFunction ]]);

        // prepare function sending without waiting 
        $data = ['database' => $database, 'notificationId' => $notificationId];
        $postData = json_encode($data); // Encode data to JSON
        $endpoint = "https://{$_SERVER['HTTP_HOST']}/api/$apiFunction";
        
        $endpointParts = parse_url($endpoint);
        $endpointParts['path'] = $endpointParts['path'] ?? '/';
        if (isset($specialPort)) { $endpointParts['port'] = $specialPort;} 
        else { $endpointParts['port'] = $endpointParts['port'] ?? $endpointParts['scheme'] === 'https' ? 443 : 80; }
        $contentLength = strlen($postData);
        $request = "POST {$endpointParts['path']} HTTP/1.1\r\n";
        $request .= "Host: {$endpointParts['host']}\r\n";
        $request .= "User-Agent: Microsystem Internal sending Notification without waiting function in chatGPT controller v2.2.0\r\n";
        $request .= "Content-Length: {$contentLength}\r\n";
        $request .= "Content-Type: application/json\r\n\r\n";
        $request .= $postData;
        $prefix = substr($endpoint, 0, 8) === 'https://' ? 'tls://' : '';
        $context = stream_context_create([ 'ssl' => [ 'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,'verify_peer' => false,'verify_peer_name' => false,],]);
        $socket = stream_socket_client($prefix . $endpointParts['host'] . ':' . $endpointParts['port'], $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) { die("Connection failed: $errstr ($errno)"); }
        fwrite($socket, $request);
        fclose($socket);
        $response = "$apiFunction";
        return $response;
    }




}