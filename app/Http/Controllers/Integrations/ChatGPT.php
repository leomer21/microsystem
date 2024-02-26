<?php
namespace App\Http\Controllers\Integrations;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use DB;
use Redirect;
use Auth;
use Carbon\Carbon;
use Mail;
use Validator;

class ChatGPT
{
    //////////////////////////////////////////////////////////////////////
    //////////////////// Sending Email Verification //////////////////////
    //////////////////////////////////////////////////////////////////////
    // this function must be called as the last step in any sending message function
    public function sendingWithoutWaiting($url, $msg, $specialPort = null){
                
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$body = @file_get_contents('php://input');
				

        //////////////// Working but we will choose only one
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 1); // set timeout to 1 second
        // curl_setopt($ch, CURLOPT_NOSIGNAL, 1); // ignore signals, including timeout

        // curl_exec($ch);
        // curl_close($ch);
        //////////////////
        $endpoint = $url;
        $postData = $msg;
        
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => $url, 'value3' => $msg ]]);

        $endpointParts = parse_url($endpoint);
        // DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '632 - sendingWithoutWaiting' ]]);

        $endpointParts['path'] = $endpointParts['path'] ?? '/';
        if (isset($specialPort)) {
            $endpointParts['port'] = $specialPort;
        } else {
            $endpointParts['port'] = $endpointParts['port'] ?? $endpointParts['scheme'] === 'https' ? 443 : 80;
        }
        // DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '75 - sendingWithoutWaiting' ]]);

        $contentLength = strlen($postData);

        $request = "POST {$endpointParts['path']} HTTP/1.1\r\n";
        $request .= "Host: {$endpointParts['host']}\r\n";
        $request .= "User-Agent: Microsystem Internal sending without waiting function in chatGPT controller v2.2.0\r\n";
        $request .= "Authorization: Bearer api_key\r\n";
        $request .= "Content-Length: {$contentLength}\r\n";
        $request .= "Content-Type: application/json\r\n\r\n";
        $request .= $postData;

        $prefix = substr($endpoint, 0, 8) === 'https://' ? 'tls://' : '';
				// DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '66 - sendingWithoutWaiting' ]]);

        $context = stream_context_create([
            'ssl' => [
                'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
				// DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '51 - sendingWithoutWaiting' ]]);

        $socket = stream_socket_client($prefix . $endpointParts['host'] . ':' . $endpointParts['port'], $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);

        // DB::table("test")->insert([['value1' => $actual_link, 'value2' => $socket, 'value3' => '52 - sendingWithoutWaiting' ]]);

        if (!$socket) {
            die("Connection failed: $errstr ($errno)");
        }

        fwrite($socket, $request);
        fclose($socket);
        $response = "sent without waiting";
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '2 - sendingWithoutWaiting' ]]);
        return $response;
        /////////////////////////////Fire and Forget HTTP Request //////////////////////////
    }
    
    public function sendEmailVerifyUsingChatGptWithoutWaiting($userId,$type,$email,$name,$country){
        
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$body = @file_get_contents('php://input');
				DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '1 - sendEmailVerifyUsingChatGptWithoutWaiting' ]]);

        // prepare function sending without waiting 
        $data = ['userId' => $userId, 'type' => $type, 'email' => $email, 'name' => $name, 'country' => $country];
        $msg = json_encode($data); // Encode data to JSON
        $url = "http://{$_SERVER['HTTP_HOST']}/api/sendEmailVerifyUsingChatGptWithoutWaiting";
        return $response = $this->sendingWithoutWaiting($url, $msg); // not working with https
    }

    public function sendEmailVerifyUsingChatGpt($userId,$type,$email,$name,$country){
 
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$body = @file_get_contents('php://input');
				DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '4 - sendEmailVerifyUsingChatGpt' ]]);

        $emailVerificationInfo = App\Settings::where('type', 'emailVerificationFor'.$type)->first();
        $emailVerificationState = $emailVerificationInfo->state;
        $emailVerificationWithoutAiState = $emailVerificationInfo->value;
        // check if the email verification is enabled or not
        if (isset($emailVerificationState) && $emailVerificationState == '1') {
            
            // prepare the activation link
            $encryptedUserId = base64_encode(base64_encode($userId.",".$type));
            $activationLink =  "http://{$_SERVER['HTTP_HOST']}/emailVerify/".$encryptedUserId;

            // prepare variables
            $businessName = App\Settings::where('type', 'app_name')->value('value');
            $chatGptApiToken = App\Settings::where('type', 'chatGptApiToken')->value('value');
            $sendEmailsFromEmail = App\Settings::where('type', 'sendEmailsFromEmail')->value('value');
            $emailVerificationUsingChatGptMessage = App\Settings::where('type', 'emailVerificationUsingChatGptMessage')->value('value');
            $emailVerificationUsingChatGptMessage = str_replace("@USER_NAME","$name",$emailVerificationUsingChatGptMessage);
            $emailVerificationUsingChatGptMessage = str_replace("@BUSINESS_NAME","$businessName",$emailVerificationUsingChatGptMessage);
            $emailVerificationUsingChatGptMessage = str_replace("@USER_COUNTRY","$country",$emailVerificationUsingChatGptMessage);
            $emailVerificationUsingChatGptMessage = str_replace("@VERIFICATION_LINK","$activationLink",$emailVerificationUsingChatGptMessage);
          
            if($emailVerificationWithoutAiState == "WithoutAi"){
              $chatGptEmail = $emailVerificationUsingChatGptMessage;
            }else{
              // send API request to chat GPT to generate the Email using guest native language
              $chatGptEmailRequest = $this->gptTurbo($emailVerificationUsingChatGptMessage, $chatGptApiToken);
              $chatGptEmailObj = json_decode($chatGptEmailRequest);
                  
              // Access the "content" value into JSON responce
              $chatGptEmail = $chatGptEmailObj->choices[0]->message->content;
              $chatGptEmail = str_replace("\n", "<br>", $chatGptEmail);
            }
            // Sending email
            $customerEmailArray = array($email);
            $from = $sendEmailsFromEmail;
            // $subject = "Wi-Fi Activation at $businessName";
            $subject = "Wi-Fi Activation";
            Mail::send('emails.send', ['title' => $subject, 'content' => $chatGptEmail], function ($message) use ($customerEmailArray, $businessName, $from, $subject) {
                $message->from($from, $businessName);
                $message->to($customerEmailArray, $businessName)->subject($subject);
            });
            DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '5 - sendEmailDone' ]]);

            // // built in PHP email service            
            // $headers = "From: <".$email.">\r\n";
            // $headers .= "Reply-To:  <noreply@microsystem.com.eg>\r\n";
            // $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
            // //   $result = mail($email, $subject, $message, $headers);
            // Mail::send('emails.activation', ['content' => $chatGptEmail], function ($message) use ($email) {
            //     $message->from('noreply@microsystem.com.eg', App\Settings::where('type', 'app_name')->value('value'));
            //     $message->to($email)->subject("Wi-Fi Activation at $businessName");
            // });
            //   Mail::send('emails.activation',$chatGptEmail,function($message) use($email)
            //   {
            //       $message->to($email)->subject('Activate your account');
            //   });

        }
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '6 - FinalSendingEmailDone' ]]);

    }

    public function addEmailIfNotExist($email_entry,$email_chain){

      
      // Convert the comma-separated string into an array
      $email_array = explode(",", $email_chain);
      
      // Check if the email entry exists in the array
      if (!in_array($email_entry, $email_array)) {
        // If it does not exist, append it to the array
        $email_array[] = $email_entry;
      }
      
      // Convert the array back into a comma-separated string
      $email_chain = implode(",", $email_array);
      
      return $email_chain;
    }
    
    // sending chatGPT preparation contenet and receive GPT responce (Final Email)
    public function gptTurbo($chatgptPreparationContent, $chatGptApiToken){
      $curl = curl_init();
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "model": "gpt-3.5-turbo",
          "messages": [{"role": "assistant",
            "content": "'.str_replace("\n", " ",$chatgptPreparationContent).'"}]
          }',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$chatGptApiToken,
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      
      return $response;
    }

    // guest clicked on the wifi verification link on his email
    public function userClickedEmailVerify(Request $request,$userId){

      //   if (strpos($userData4PMS->notes, 'checkOut:') !== false) {
      //     $userCheckoutDate = @explode(",",end(preg_split('/checkOut: /', $userData4PMS->notes)))[0];
      //     if(isset($userCheckoutDate) and strlen($userCheckoutDate)>8 ){
      //         // calculate remaining days till checkout
      //         $datediff = strtotime($userCheckoutDate) - $now;
      //         $remainingDaysCheckout = round($datediff / (60 * 60 * 24)); // no of remaining days till checkout
      //         if(isset($remainingDaysCheckout) and $remainingDaysCheckout < 0){
      //             // suspend this user because this mac address online after midnight of checkout date
      //             echo "<br> AbusersStaff:".$value->callingstationid.", U_ID:$value->u_id";
      //             // insert this mac to abusersStaffAccount and notes
      //             $abusersStaffAccount = DB::table($Customer->database.".users")->where('u_id', $abusersStaffId)->first();
      //             $note = "New abuse detection at:$created_at, using Mac:$value->callingstationid, founded in user:$userData4PMS->u_name, checkOut:$userCheckoutDate";
      //         DB::table($Customer->database.".users")->where('u_id', $abusersStaffId)->update(['u_mac' => $abusersStaffAccount->u_mac.','.$value->callingstationid, 'notes' => $abusersStaffAccount->notes.'; '.$note]);
      //             // send suspend signal
      //             $searchController->suspend($abusersStaffId, "false");
      //         }
      //     }
      // }

      // decode the userId
      $userId = base64_decode(base64_decode($userId));
      $array = explode(",", $userId);
      if($array[1]== 'Signup'){

        $emailVerificationSwitchRoomType = App\Settings::where('type', 'emailVerificationSwitchRoomTypeForSignup')->value('state');
        $emailVerificationSwitchToGroupId = App\Settings::where('type', 'emailVerificationSwitchToGroupIdForSignup')->value('value');
        $emailVerificationMotivationalMsg = App\Settings::where('type', 'emailVerificationMotivationalMsgForSignup')->value('value');
      }else{

        $emailVerificationSwitchRoomType = App\Settings::where('type', 'emailVerificationSwitchRoomTypeForLogin')->value('state');
        $emailVerificationSwitchToGroupId = App\Settings::where('type', 'emailVerificationSwitchToGroupIdForLogin')->value('value');
        $emailVerificationMotivationalMsg = App\Settings::where('type', 'emailVerificationMotivationalMsgForLogin')->value('value');

        // $email = App\Users::where('u_id', $array[0])->first();
        //->value('notes');

      }
      if (isset($emailVerificationSwitchRoomType) && $emailVerificationSwitchRoomType == '1') {
        $notes = App\Users::where('u_id', $array[0])->first();
        //->value('notes');
        if (strpos($notes->notes, 'Room Type:') !== false) {
          $userRoomType = @explode(",",end(preg_split('/Room Type: /', $notes)))[0];
          if(isset($userRoomType) ){
            $group  = App\Groups::where('name', $userRoomType)->first();
            if(isset($userRoomType) && isset($group)){
              $emailVerificationSwitchToGroupId = $groupId;
            }
          }
        }
      }
  

      $user_check = App\Users::where('u_id', $array[0])->update(
        ['group_id' => $emailVerificationSwitchToGroupId]
      );

      return view("front-end.landing.email");

      //$request->session()->push('message', $emailVerificationMotivationalMsg);
      //return redirect()->route('account');
    }

    ///////////////////////////////////////////////////////////////////////////
    ///////////////////// Sending Email notification //////////////////////////
    ///////////////////////////////////////////////////////////////////////////

    public function sendEmailNotificationUsingChatGpt($database, $notificationId, $withoutAI = null){

      date_default_timezone_set("Africa/Cairo");
      $today = date("Y-m-d");
      $today_time = date("g:i a");
      $created_at = $today." ".date("H:i:s");
      
      // testing purposes
      $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $body = @file_get_contents('php://input');
      DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '3 - STARTING .. sendEmailNotificationUsingChatGpt' ]]);

      $emailNotification = DB::table("$database.scheduled_notifications")->where('id', $notificationId)->first();
      if(isset($emailNotification)){
        
        // Get public Holiday data if exist
        if($emailNotification->public_holiday_id != 0){
          $publicHoliday = DB::table("$database.public_holidays")->where('id', $emailNotification->public_holiday_id)->first();
        }

        // Prepare message attriputes
        $businessName = DB::table("$database.settings")->where('type', 'app_name')->value('value');
        $user = DB::table("$database.users")->where('u_id', $emailNotification->u_id)->first();
        $chatGptApiToken = DB::table("$database.settings")->where('type', 'chatGptApiToken')->value('value');
        $sendEmailsFromEmail = DB::table("$database.settings")->where('type', 'sendEmailsFromEmail')->value('value');
        if(isset($withoutAI) and $withoutAI == 'withoutAI') {$preparationContent = $emailNotification->final_message; } else {$preparationContent = $emailNotification->chatgpt_content; }
        $preparationContent = $this->replaceVariables($database, $preparationContent, $emailNotification->u_id);

        if(isset($withoutAI) and $withoutAI == 'withoutAI') {
          $finalEmailContent = $preparationContent;
        }else{
          // Send API request to chat GPT to generate the Email using guest native language
          $chatGptEmailRequest = $this->gptTurbo($preparationContent, $chatGptApiToken);
          $chatGptEmailObj = json_decode($chatGptEmailRequest);
  
          // Access the "content" value into JSON responce
          $finalEmailContent = $chatGptEmailObj->choices[0]->message->content;
          $finalEmailContent = str_replace("\n", "<br>", $finalEmailContent);
        }
        // validate Emails
        $emails = explode(',', $emailNotification->email);
        foreach($emails as $email) {
          $email = str_replace(" ","",$email);
          $validator = Validator::make(
              ['email' => $email],
              ['email' => 'required|email']
          );
          if ($validator->fails()) {
              // testing purposes
              DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => "4 - Email format not valid ($email) .. sendEmailNotificationUsingChatGpt" ]]);
              return " Email format not valid. ";
          }else{
            // email format valid
            // Sending email
            $customerEmailArray = array($email);
            $from = $sendEmailsFromEmail;
            $subject = "$user->u_name at $businessName";
            // set direction
            if($user->u_country == "Egypt"){$emailHtmlName = 'emails.sendRTL';}
            else{$emailHtmlName = 'emails.send';}
            Mail::send($emailHtmlName, ['title' => $subject, 'content' => $finalEmailContent], function ($message) use ($customerEmailArray, $businessName, $from, $subject) {
                $message->from($from, $businessName);
                $message->to($customerEmailArray, $businessName)->subject($subject);
            });
            // Disable this notification
            DB::table("$database.scheduled_notifications")->where('id',$emailNotification->id)->update(['state' => '0', 'final_message' => $finalEmailContent, 'sent_at' => date("Y-m-d H:i:s")]); 
            // testing purposes
            DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '4 - DONE... sendEmailNotificationUsingChatGpt' ]]);
            return "Notification Email Sent Successfilly <br>$finalEmailContent. ";
          }
        }
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => "4 - END OF FUNCTION .. sendEmailNotificationUsingChatGpt" ]]);
      }
    }

    ///////////////////////////////////////////////////////////////////////////
    ///////////////////// Sending Whatsapp notification ///////////////////////
    ///////////////////////////////////////////////////////////////////////////

    public function sendWhatsappOrSMSNotificationUsingChatGpt($database, $notificationId, $type, $withoutAI = null){

      date_default_timezone_set("Africa/Cairo");
      $today = date("Y-m-d");
      $today_time = date("g:i a");
      $created_at = $today." ".date("H:i:s");
      
      // testing purposes
      $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $body = @file_get_contents('php://input');
      DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '3 - STARTING .. sendWhatsappOrSMSNotificationUsingChatGpt' ]]);

      $WhatsappOrSMSNotification = DB::table("$database.scheduled_notifications")->where('id', $notificationId)->first();
      if(isset($WhatsappOrSMSNotification)){
        
        // Get public Holiday data if exist
        if($WhatsappOrSMSNotification->public_holiday_id != 0){
          $publicHoliday = DB::table("$database.public_holidays")->where('id', $WhatsappOrSMSNotification->public_holiday_id)->first();
        }

        // Prepare message attriputes
        $businessName = DB::table("$database.settings")->where('type', 'app_name')->value('value');
        $user = DB::table("$database.users")->where('u_id', $WhatsappOrSMSNotification->u_id)->first();
        $chatGptApiToken = DB::table("$database.settings")->where('type', 'chatGptApiToken')->value('value');
        if( (isset($withoutAI) and $withoutAI == 'withoutAI') or ( isset($WhatsappOrSMSNotification->final_message) and strlen($WhatsappOrSMSNotification->final_message) > 5 ) ) {
          $preparationContent = $WhatsappOrSMSNotification->final_message;
        }else {
          $preparationContent = $WhatsappOrSMSNotification->chatgpt_content;
        }
        
        $preparationContent = $this->replaceVariables($database, $preparationContent, $WhatsappOrSMSNotification->u_id);

        if( (isset($withoutAI) and $withoutAI == 'withoutAI') or ( isset($WhatsappOrSMSNotification->final_message) and strlen($WhatsappOrSMSNotification->final_message) > 5 ) ) {
          $finalMessageContent = $preparationContent;
        }else{

          // Send API request to chat GPT to generate the Whatsapp using guest native language
          $chatGptMessageRequest = $this->gptTurbo($preparationContent, $chatGptApiToken);
          $chatGptMessageRequest = json_decode($chatGptMessageRequest);
  
          // Access the "content" value into JSON responce
          $finalMessageContent = $chatGptMessageRequest->choices[0]->message->content;
        }
        
        // validate mobiles
        $mobiles = explode(',', $WhatsappOrSMSNotification->mobile);
        foreach($mobiles as $mobile) {
          $mobile = str_replace(" ","",$mobile);
          $validator = Validator::make(
            ['mobile' => $mobile],
            ['mobile' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10']]
          );
          if ($validator->fails()) {
              // testing purposes
              DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => "4 - mobile format not valid ($mobile) .. sendWhatsappOrSMSNotificationUsingChatGpt" ]]);
              return " Mobile format not valid. ";
          }else{
            // Mobile format valid
            if($type=="Whatsapp"){
              // Sending Whatsapp
              $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
              $customerData = DB::table("customers")->where('database', $database)->first(); 
              $whatsappClass->sendWhatsappWithoutSourceWithoutWaiting( "", $mobile , $finalMessageContent, $customerData->id, $database);
              // // testing purposes
              // return $whatsappClass->send( "", $mobile , $finalMessageContent, $customerData->id, $database);
            
            }elseif($type=="SMS"){
              // Sending SMS
              $sendmessage = new App\Http\Controllers\Integrations\SMS();
              $sendmessage->send($mobile, $finalMessageContent);
            }
            // Disable this notification
            DB::table("$database.scheduled_notifications")->where('id',$WhatsappOrSMSNotification->id)->update(['state' => '0', 'final_message' => $finalMessageContent, 'sent_at' => date("Y-m-d H:i:s")]); 
            
            // testing purposes
            DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '4 - DONE... sendWhatsappOrSMSNotificationUsingChatGpt' ]]);
            return "Notification Whatsapp Sent Successfilly <br>$finalMessageContent. ";
          }
        }
        DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => "4 - END OF FUNCTION .. sendWhatsappOrSMSNotificationUsingChatGpt" ]]);
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

    // replace GPT contenet variables
    public function replaceVariables($database, $preparationContent, $userID)
    {
      # - Variables for message content 
      #	@USER_NAME
      #	@USER_USERNAME
      #	@USER_PASSWORD
      #	@USER_BIRTHDATE
      # @USER_GENDER
      # @USER_LANGUAGE
      #	@BUSINESS_NAME
      #	@USER_COUNTRY
      #	@USER_CHECKIN_DATE
      #	@USER_CHECKOUT_DATE
      #	@USER_ROOM_TYPE
      #	@USER_RESERVATION_NUMBER
      #	@USER_CONFIRMATION_NUMBER
      #	@USER_ROOM_NUMBER
      #	@USER_PMS_ID
      #	@HOLIDAY_NAME
      #	@HOLIDAY_DATE
      #	@HOLIDAY_COUNTRY
      
      date_default_timezone_set("Africa/Cairo");

      // Prepare message attriputes
      $businessName = DB::table("$database.settings")->where('type', 'app_name')->value('value');
      $user = DB::table("$database.users")->where('u_id', $userID)->first();
      $preparationContent = str_replace("@BUSINESS_NAME","$businessName",$preparationContent);
      $preparationContent = str_replace("@USER_NAME","$user->u_name",$preparationContent);
      $preparationContent = str_replace("@USER_USERNAME","$user->u_uname",$preparationContent);
      $preparationContent = str_replace("@USER_PASSWORD","$user->u_password",$preparationContent);
      $preparationContent = str_replace("@USER_BIRTHDATE","$user->birthdate",$preparationContent);
      $preparationContent = str_replace("@USER_COUNTRY","$user->u_country",$preparationContent);

      if($user->u_gender==1){ $userGender = "Male";}
      elseif($user->u_gender==0){ $userGender = "Female";}
      else{ $userGender = "Unknown";}
      $preparationContent = str_replace("@USER_GENDER","$userGender",$preparationContent);
      $preparationContent = str_replace("@USER_LANGUAGE","$user->u_lang",$preparationContent);

      if (strpos($user->notes, 'checkOut:') !== false and strpos($user->notes, 'checkIn:') !== false) {
        $userCheckIn = @explode(",",end(preg_split('/checkIn: /', $user->notes)))[0];
        $preparationContent = str_replace("@USER_CHECKIN_DATE",$userCheckIn,$preparationContent);
      
        $userCheckOut = @explode(",",end(preg_split('/checkOut: /', $user->notes)))[0];
        $preparationContent = str_replace("@USER_CHECKOUT_DATE",$userCheckOut,$preparationContent);
        
        $checkin = strtotime($userCheckIn);
        $checkout = strtotime($userCheckOut);
        $datediff = $checkout - $checkin;
        $userTotalNights = @round($datediff / (60 * 60 * 24));
        $preparationContent = str_replace("@USER_TOTAL_NIGHTS",$userTotalNights,$preparationContent);
      }

      if (strpos($user->notes, 'Room Type:') !== false) {
        $guestRoomType = @explode(",",end(preg_split('/Room Type:/', $user->notes)))[0];
        $guestFinalRoomType = DB::table("$database.area_groups")->where('name',str_replace(' ', '', $guestRoomType))->value('notes');
        $preparationContent = str_replace("@USER_ROOM_TYPE",$guestFinalRoomType,$preparationContent);
      }

      if (strpos($user->notes, 'Reservation Number:') !== false) {
        $guestReservationNumber = @explode(",",end(preg_split('/Reservation Number:/', $user->notes)))[0];
        $preparationContent = str_replace("@USER_RESERVATION_NUMBER",$guestReservationNumber,$preparationContent);
      }

      if (strpos($user->notes, 'Confirmation Number:') !== false) {
        $guestConfirmationNumber = @explode(",",end(preg_split('/Confirmation Number:/', $user->notes)))[0];
        $preparationContent = str_replace("@USER_CONFIRMATION_NUMBER",$guestConfirmationNumber,$preparationContent);
      }

      if (isset($user->pms_room_no)) {
        $preparationContent = str_replace("@USER_ROOM_NUMBER","$user->pms_room_no",$preparationContent);
      }

      if (isset($user->pms_guest_id)) {
        $preparationContent = str_replace("@USER_PMS_ID","$user->pms_guest_id",$preparationContent);
      }

      if(isset($publicHoliday)){
        $preparationContent = str_replace("@HOLIDAY_NAME","$publicHoliday->name",$preparationContent);
        $publicHolidayDate = date("Y-").explode('-', $publicHoliday->date)[1].'-'.explode('-', $publicHoliday->date)[2];
        $preparationContent = str_replace("@HOLIDAY_DATE",$publicHolidayDate,$preparationContent);
        $preparationContent = str_replace("@HOLIDAY_COUNTRY",$publicHoliday->country_name,$preparationContent);
      }

      return $preparationContent;
      
    }
}
