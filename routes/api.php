<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//////////// ChatGPT Integration ////////

// send email verification
Route::any('api/sendEmailVerifyUsingChatGptWithoutWaiting', function (Request $request) {

    // // testing purposes
    // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // $body = @file_get_contents('php://input');
    // DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '3 - api/sendEmailVerifyUsingChatGptWithoutWaiting' ]]);

    $chatGPT = new App\Http\Controllers\Integrations\ChatGPT();
    print_r($chatGPT->sendEmailVerifyUsingChatGpt( $request->userId, $request->type, $request->email, $request->name, $request->country ));
});

Route::get('emailVerify/{code}', function (Request $request,$userId) {
    $chatGPT = new App\Http\Controllers\Integrations\ChatGPT();
    return $chatGPT->userClickedEmailVerify( $request,$userId );
});

Route::get('api/whatsappPay/{microsystemORenduser}/{systemID}/{systemName}/{customerID?}/{customerMobile}/{customerEmail}/{amount}/{currency}/{fawry?}/{visa?}/{wallet?}/{orderNotes?}', function ($microsystemORenduser, $systemID, $systemName, $customerID=null, $customerMobile, $customerEmail, $amount, $currency, $fawry=null, $visa=null, $wallet=null, $orderNotes=null) {
    $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
    return $whatsappClass->pay($microsystemORenduser, $systemID, $systemName, $customerID, $customerMobile, $customerEmail, $amount, $currency, $fawry, $visa, $wallet, $orderNotes);
});

// sending Email notification
Route::any('api/sendEmailNotificationUsingChatGptWithoutWaiting', function (Request $request) {

    // testing purposes
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $body = @file_get_contents('php://input');
    DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '2 - api/sendEmailNotificationUsingChatGptWithoutWaiting' ]]);

    $chatGPT = new App\Http\Controllers\Integrations\ChatGPT();
    print_r($chatGPT->sendEmailNotificationUsingChatGpt( $request->database, $request->notificationId ));
});

// sending Whatsapp notification
Route::any('api/sendWhatsappNotificationUsingChatGptWithoutWaiting', function (Request $request) {

    // testing purposes
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $body = @file_get_contents('php://input');
    DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '2 - api/sendWhatsappNotificationUsingChatGptWithoutWaiting' ]]);

    $chatGPT = new App\Http\Controllers\Integrations\ChatGPT();
    print_r($chatGPT->sendWhatsappOrSMSNotificationUsingChatGpt( $request->database, $request->notificationId, 'Whatsapp' ));
});

// sending SMS notification
Route::any('api/sendSMSNotificationUsingChatGptWithoutWaiting', function (Request $request) {

    // testing purposes
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $body = @file_get_contents('php://input');
    DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '2 - api/sendSMSNotificationUsingChatGptWithoutWaiting' ]]);

    $chatGPT = new App\Http\Controllers\Integrations\ChatGPT();
    print_r($chatGPT->sendWhatsappOrSMSNotificationUsingChatGpt( $request->database, $request->notificationId, 'SMS' ));
});
//////////// ChatGPT Integration ////////


//////////// PMS Integration ////////////

Route::any('api/pms/databasePull', 'PmsDatabase@pull');
Route::any('api/pmsInterface/checkin', 'PmsInterface@checkin');
Route::any('api/pmsInterface/checkout', 'PmsInterface@checkout');
Route::any('api/pmsInterface/changeRoom', 'PmsInterface@changeRoom');
Route::any('api/pmsInterface/pullInvoices', 'PmsInterface@pullInvoices');
Route::any('api/pmsInterface/rowData', 'PmsInterface@rowData');
//////////// PMS Integration ////////////

//////////// Admin API for Mobile App ////////////
Route::any('api/admin/login', 'AdminApiController@login');
Route::any('api/admin/dashboard', 'AdminApiController@dashboard');
Route::any('api/admin/getBranches', 'AdminApiController@getBranches');
Route::any('api/admin/verifyToken', 'AdminApiController@verifyToken');
Route::any('api/admin/getOnlineUsers', 'AdminApiController@getOnlineUsers');
Route::any('api/admin/getUsers', 'AdminApiController@getUsers');
Route::any('api/admin/sendWhatsApp', 'AdminApiController@sendWhatsApp');
Route::any('api/admin/chatBotAI', 'AdminApiController@chatBotAI');
Route::any('api/admin/updateBranch', 'AdminApiController@updateBranch');
Route::any('api/admin/addLoadBalancingLine', 'AdminApiController@addLoadBalancingLine');
Route::any('api/admin/updateLoadBalancingLine', 'AdminApiController@updateLoadBalancingLine');
Route::any('api/admin/deleteLoadBalancingLine', 'AdminApiController@deleteLoadBalancingLine');
Route::any('api/admin/addEditLoadBalancingLines', 'AdminApiController@addEditLoadBalancingLines');
Route::any('api/admin/getUserInfo', 'AdminApiController@getUserInfo');
Route::any('api/admin/suspendUser', 'AdminApiController@suspendUser');
Route::any('api/admin/unsuspendUser', 'AdminApiController@unsuspendUser');
Route::any('api/admin/getSearchFilter', 'AdminApiController@getSearchFilter');
Route::any('api/admin/deleteUser', 'AdminApiController@deleteUser');
Route::any('api/admin/editUser', 'AdminApiController@editUser');
Route::any('api/admin/createUser', 'AdminApiController@createUser');
Route::any('api/admin/removeBypass', 'AdminApiController@removeBypass');
Route::any('api/admin/createBypass', 'AdminApiController@createBypass');
Route::any('api/admin/assignDeviceToUser', 'AdminApiController@assignDeviceToUser');
Route::any('api/admin/getGroups', 'AdminApiController@getGroups');
Route::any('api/admin/createGroup', 'AdminApiController@createGroup');
Route::any('api/admin/editGroup', 'AdminApiController@editGroup');
Route::any('api/admin/deleteGroup', 'AdminApiController@deleteGroup');
Route::any('api/admin/loyaltyPoints', 'AdminApiController@loyaltyPoints');
Route::any('api/admin/sendNotificationMsg', 'AdminApiController@sendNotificationMsg');
Route::any('api/admin/createCouponCode', 'AdminApiController@createCouponCode');
Route::any('api/admin/getUserByOnlineIpOrMac', 'AdminApiController@getUserByOnlineIpOrMac');
//////////// Admin API for Mobile App ////////////

//////////// Microsystem SMS server //////////////
Route::any('api/sendMicrosystemSMS', function (Request $request) {
    $microsystemSMSserver = new App\Http\Controllers\ApiController();
    print_r($microsystemSMSserver->sendMicrosystemSMS($request->customer_id, $request->type, $request->to, $request->message));
});
Route::any('api/retrieveMicrosystemSMS', 'AdminApiController@retrieveMicrosystemSMS');
Route::any('api/errorLastRetrievedMicrosystemSMS', 'AdminApiController@errorLastRetrievedMicrosystemSMS');
Route::any('api/smsStatusDLR', 'AdminApiController@smsStatusDLR');
//////////// Microsystem SMS server //////////////
Route::any('api/replication', 'replicationController@replication');
Route::any('api', 'ApiController@index');
Route::any('api/unifi', 'UnifiController@unifi');
Route::any('api/radius', 'RadiusClientController@radiusClient');
Route::any('api/orange/radiusLogin', 'RadiusClientController@radiusLogin');


// Route::any('api/telegramWebhook', 'TelegramWebhookController@telegramWebhook');
Route::any('api/telegramWebhook', function(Request $request){ 
    $basicBotMiddlewareWebhook4allGatewaysClass = new App\Http\Controllers\BasicBotMiddlewareWebhook4allGateways();
    return $basicBotMiddlewareWebhook4allGatewaysClass->basicBotMiddlewareHandler($request);
});


Route::get('api/whatsappPay/{microsystemORenduser}/{systemID}/{systemName}/{customerID?}/{customerMobile}/{customerEmail}/{amount}/{currency}/{fawry?}/{visa?}/{wallet?}/{orderNotes?}', function ($microsystemORenduser, $systemID, $systemName, $customerID=null, $customerMobile, $customerEmail, $amount, $currency, $fawry=null, $visa=null, $wallet=null, $orderNotes=null) {
    $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
    return $whatsappClass->pay($microsystemORenduser, $systemID, $systemName, $customerID, $customerMobile, $customerEmail, $amount, $currency, $fawry, $visa, $wallet, $orderNotes);
});
Route::post('api/linkShortener/{baseUrl?}/{tallUrl?}', function ($baseUrl=null,$tallUrl=null) {
    date_default_timezone_set("Africa/Cairo");
    $created_at = date("Y-m-d H:i:s");
    if($baseUrl==null or $tallUrl==null){
        $body = @file_get_contents('php://input');
        $request = json_decode($body, true);
        $baseUrl = $request['baseUrl'];
        $tallUrl = $request['tallUrl'];
    }
    $shortID = DB::table('link_shortener')->insertGetId(array('base_url' => $baseUrl, 'tall_url' => $tallUrl, 'created_at' => $created_at ));
    $finalUrl = "https://".$baseUrl."/api/url/".$shortID;
    DB::table('link_shortener')->where('id',$shortID)->update(['final_url' => $finalUrl]);
    return $finalUrl;
});
Route::get('api/url/{urlId}/{branchID?}/{tableID?}', function ($urlId, $branchID=null, $tableID=null) {
    $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
    return $whatsappClass->linkShortenerFetch($urlId, $branchID, $tableID);
});

// send whatsapp message (without assigned server mobile) without waiting
Route::any('api/sendWhatsappWithoutSourceWithoutWaiting', function (Request $request) {
    
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $body = @file_get_contents('php://input');
    DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '3 - sendWhatsappWithoutSourceWithoutWaiting' ]]);

    $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
    print_r($whatsappClass->Send($request->from, $request->to, $request->message, $request->customerID, $request->database, $request->loadBalance, $request->senderName, $request->msg_type, $request->urlEncode, $request->campaignID, $request->resendID) );
});
// send whatsapp message (with assigned server mobile) without waiting
Route::any('api/sendWhatsappWithoutWaiting', function (Request $request) {

    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $body = @file_get_contents('php://input');
    DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body, 'value3' => '3 - sendWhatsappWithoutWaiting' ]]);

    $whatsappClass = new App\Http\Controllers\Integrations\WhatsApp();
    print_r($whatsappClass->sendWhatsapp($request->customerDB, $request->todayDateTime, $request->from, $request->to, $request->message, $request->campaign_id, $request->pending_survey_id, $request->sentMsgID) );
});