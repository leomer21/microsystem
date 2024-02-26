<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Use Salesforce;
//Authentication
// To make free error
//Route::auth();

// Authentication Routes...
$this->get('login', 'Auth\AuthController@showLoginForm');
$this->post('login', 'Auth\AuthController@login');
$this->get('logout', 'Auth\AuthController@logout');

// Registration Routes...
$this->get('register', 'Auth\AuthController@showRegistrationForm');
$this->post('register', 'Auth\AuthController@register');

// Password Reset Routes...
$this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
$this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
$this->post('password/reset', 'Auth\PasswordController@reset');

// // dashboard time reduction (daily crons) not USED 19.4.2023
// Route::get('CronCopyGroupViewToTable', ['as' => 'cron', 'uses' => 'CronCopyGroupViewToTable@cron']);
// Route::get('CronCopyBranchViewToTable', ['as' => 'cron', 'uses' => 'CronCopyBranchViewToTable@cron']);
// Route::get('CronCopyNetworkViewToTable', ['as' => 'cron', 'uses' => 'CronCopyNetworkViewToTable@cron']);
// Route::get('CronCopyUsersRadacctViewToTable', ['as' => 'cron', 'uses' => 'CronCopyUsersRadacctViewToTable@cron']);
// Route::get('CronDeleteUnusedRecForPerformance', ['as' => 'cron', 'uses' => 'CronDeleteUnusedRecForPerformance@cron']);

Route::get('cronSendHotelGuestEmailsStep1', ['as' => 'cronSendHotelGuestEmailsStep1', 'uses' => 'CronSendHotelGuestEmailsStep1@cronSendHotelGuestEmailsStep1']);
Route::get('cronSendHotelGuestNotificationsStep2', ['as' => 'cronSendHotelGuestNotificationsStep2', 'uses' => 'CronSendHotelGuestNotificationsStep2@cronSendHotelGuestNotificationsStep2']);


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

Route::any('groupTemporarySwitch', 'GroupTemporarySwitchController@index');
Route::any('groupTemporarySwitchSubmit', 'GroupTemporarySwitchController@groupTemporarySwitch');

Route::any('directCharge', 'DirectChargeController@index');
Route::any('directChargeValues', 'DirectChargeController@directChargeValues');

Route::any('vapulusPayment', 'VapulusPaymentController@index');
Route::any('vapulusPaymentRetrieve', 'VapulusPaymentController@retrieve');
Route::any('vapulusPaymentPay', 'VapulusPaymentController@pay');
Route::any('vapulusPaymentSuccess', 'VapulusPaymentController@vapulusPaymentSuccess');
Route::any('vapulusPaymentfail', 'VapulusPaymentController@vapulusPaymentfail');


Route::any('api', 'ApiController@index');
Route::any('api/unifi', 'UnifiController@unifi');
Route::any('api/radius', 'RadiusClientController@radiusClient');
Route::any('api/orange/radiusLogin', 'RadiusClientController@radiusLogin');
Route::get('firebase-phone-authentication', 'FirebasePhoneAuthController@invisiblecaptcha')->name('invisiblecaptcha');
Route::any('installation', ['uses' => 'InstallationController@installation', 'as' => 'installation']);
Route::post('subdomain', function(Request $request){
    if(isset($request->domain)){

        $fullDomain=$request->domain.$request->masterDomain;
        $domain = DB::table('microsystem.customers')->where('url', $fullDomain)->value('database');
        if(isset($domain) && count($domain) !== 0){

            $systemIdentify= $domain;

            if(!session('Identify')){
                Session::push('Identify', $systemIdentify);
            }
            //Session::flush();

            //return Session::all();
            //return Redirect()->route('dashboard');
            $fullDomain="http://".$fullDomain."/admin";
            return Redirect("$fullDomain");
            //return view('back-end.auth/login');
        }else{

            return view('back-end.auth/subdomain', ['error' => '1']);
        }
    }
});
Route::any('api', 'ApiController@index');
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

Route::group(['middleware' => ['auth']], function () {

    $database =  app('App\Http\Controllers\Controller')->configuration();
    if($database !== 0) {
        
        
        //Users
        Route::get('search', ['uses' => 'SearchController@index', 'as' => 'search']);
        Route::post('/search.json', ['uses' => 'SearchController@json', 'as' => 'searchjson']);
        Route::get('/deleteuser/{id}', ['uses' => 'SearchController@delete', 'as' => 'deleteuser']);
        Route::get('/deletes/{ids}', ['uses' => 'SearchController@deletes', 'as' => 'deletes']);
        Route::get('/suspend/{id}/{v}', ['uses' => 'SearchController@suspend', 'as' => 'suspend']);
        Route::post('/bulkGroupSwitch', ['uses' => 'SearchController@bulkGroupSwitch', 'as' => 'bulkGroupSwitch']);
        
        Route::get('/controlSpeedLimit/{id}/{state}/{acctuniqueid}', ['uses' => 'ActiveusersController@controlSpeedLimit', 'as' => 'controlSpeedLimit']);
        Route::get('/updateTotalDownloadSpeed/{branch_id}/{speed}', ['uses' => 'ActiveusersController@updateTotalDownloadSpeed', 'as' => 'updateTotalDownloadSpeed']);
        Route::get('/updateTotalUploadSpeed/{branch_id}/{speed}', ['uses' => 'ActiveusersController@updateTotalUploadSpeed', 'as' => 'updateTotalUploadSpeed']);
        
        Route::get('campaignid/{id}', ['uses' => 'SearchController@campaignid', 'as' => 'campaignid']);
        Route::post('/campaign', ['uses' => 'SearchController@campaign', 'as' => 'campaign']);
        Route::post('/campaignAI', ['uses' => 'SearchController@campaignAI', 'as' => 'campaignAI']);
        Route::post('/mobilecounts', ['uses' => 'SearchController@mobilecounts', 'as' => 'mobilecounts']);
        Route::post('/getgruop/{id}', 'SearchController@getgruop');
        Route::post('/user/{id}/view', ['uses' => 'SearchController@userview', 'as' => 'users']);
        Route::get('/downloadFullExceldata/{type}', ['as' => 'download', 'uses' => 'SearchController@downloadFullExceldata']);
        Route::get('/downloaddemoExcel/{type}', ['as' => 'download', 'uses' => 'SearchController@downloaddemoExcel']);
        Route::post('/importExcel', ['as' => 'import', 'uses' => 'SearchController@importExcel']);
        Route::get('user_profile/{id}', 'SearchController@userid');
        Route::get('/timeline/{id}', 'TimelineController@timeline');

		Route::get('export_month/{id}-{monthname}', ['uses' => 'TimelineController@export_month_excel', 'as ' => 'export_month_excel']);
        Route::get('export_day/{id}-{day}', ['uses' => 'TimelineController@export_day_excel', 'as ' => 'export_day_excel']);

        Route::get('export_month_log/{id}-{monthname}', ['uses' => 'TimelineController@export_month_log_excel', 'as ' => 'export_month_log_excel']);
        Route::get('export_day_log/{id}-{day}', ['uses' => 'TimelineController@export_day_log_excel', 'as ' => 'export_day_log_excel']);
		
        Route::get('destination_logs/{id}-{monthname}', ['uses' => 'TimelineController@destinations_month_list', 'as ' => 'destinations_month_list']);
        Route::get('destination_logs_day/{id}-{day}', ['uses' => 'TimelineController@destinations_day_list', 'as ' => 'destinations_day_list']);

        // Export networks and groups and branches
        Route::get('download_modal/{id}-{type}', ['uses' => 'TimelineController@download_modal', 'as ' => 'download_modal']);

        //Network destination_logs
        Route::get('network_destinations/{id}-{type}', ['uses' => 'TimelineController@network_destinations', 'as ' => 'network_destinations']);

        //Network monthly usage
        Route::get('network_usage/{id}-{type}', ['uses' => 'TimelineController@network_monthly_usge', 'as ' => 'network_usage']);
        
        //Group destination_logs
        Route::get('group_destinations/{id}-{type}', ['uses' => 'TimelineController@group_destinations', 'as ' => 'group_destinations']);

        //Branch destination_logs
        Route::get('branch_destinations/{id}-{type}', ['uses' => 'TimelineController@branch_destinations', 'as ' => 'branch_destinations']);

        
        //Timeline networks and groups and branches
        Route::get('modal_timeline/{id}-{type}', ['uses' => 'TimelineController@modal_timeline', 'as ' => 'modal_timeline']);
        

        //Export timeline excel sheet
        Route::get('export_timeline/{id}/{month}/{type}', ['uses' => 'TimelineController@export_timeline', 'as ' => 'export_timeline']);


        Route::post('/upload_excel', ['uses' => 'SearchController@upload_excel', 'as ' => 'upload_excel']);


        Route::get('/autologin', 'Usersc\HomeUserController@login');
        Route::post('sendmail', ['as' => 'sendmail', 'uses' => 'SearchController@sendmail']);
        Route::get('charagepackages/{id}', ['uses' => 'SearchController@charagepackages', 'as' => 'charagepackages']);

        //Active Users
        Route::get('/activeusers', ['uses' => 'ActiveusersController@Index', 'as' => 'activeusers']);
        Route::get('/activeusersjson', ['uses' => 'ActiveusersController@Data', 'as' => 'activeusersjson']);
        Route::get('/disconnect/{id}', ['uses' => 'ActiveusersController@Disconnect', 'as' => 'disconnect']);
        Route::get('/disconnectandsuspend/{id}', ['uses' => 'ActiveusersController@Disconnectandsuspend', 'as' => 'disconnectandsuspend']);
        Route::get('/unsuspend/{id}', ['uses' => 'ActiveusersController@unsuspend', 'as' => 'unsuspend']);
        Route::get('/removeBypass/{mac}', ['uses' => 'ActiveusersController@removeBypass', 'as' => 'removeBypass']);
        // Add unregisterd device
        Route::get('addUnregisteredUsers/{mac}/{branch_id?}', 'ActiveusersController@addUnregisteredUsers');
        Route::post('/addNewUnregisteredUser', ['uses' => 'ActiveusersController@addNewUnregisteredUser', 'as' => 'addNewUnregisteredUser']);

        //Search Result
        Route::post('/add_search', ['uses' => 'SearchController@add_search_result', 'as' => 'add_search']);
        Route::get('/delete/{id}', ['uses' => 'SearchController@delete_search_result', 'as' => 'delete']);
        Route::get('/advancedReport', ['uses' => 'SearchController@advancedReport', 'as' => 'advancedReport']);
        
        //Dashboard
        Route::get('admin', ['uses' => 'DashboardController@index', 'as' => 'dashboard']);
        Route::get('counter', ['uses' => 'DashboardController@counter', 'as' => 'counter_dashboard']);
        Route::get('dashboard_type', ['uses' => 'DashboardController@dashboard_type', 'as' => 'dashboard_type']);
        Route::get('dashboard_ajax', ['uses' => 'DashboardController@dashboard_ajax', 'as' => 'dashboard_ajax']);
        


        Route::get('not-found', 'DashboardController@notFound');
        Route::post('deleteusers/{id}', ['as' => 'deleteusers', 'uses' => 'DashboardController@deleteusers']);
        Route::post('confirmusers/{id}', ['as' => 'confirmusers', 'uses' => 'DashboardController@confirmusers']);
        Route::get('message/{id}', ['as' => 'messages', 'uses' => 'DashboardController@details']);
        Route::get('delete_message/{id}', ['as' => 'delete_message', 'uses' => 'DashboardController@delete']);
        Route::post('reply', ['as' => 'reply', 'uses' => 'DashboardController@reply']);
        Route::get('ignore_message/{id}', ['as' => 'ignore_message', 'uses' => 'DashboardController@ignore']);
        Route::get('visitors', ['as' => 'visitors', 'uses' => 'DashboardController@visitors']);
        Route::get('cornjob', ['as' => 'cornjob', 'uses' => 'DashboardController@cornjob']);
        Route::get('cornjob2', ['as' => 'cornjob2', 'uses' => 'DashboardController@cornjob2']);
        
        Route::get('online', ['as' => 'online', 'uses' => 'DashboardController@online']);
        Route::post('statistics', ['as' => 'statistics', 'uses' => 'DashboardController@statistics']);

        //Users
        Route::post('/adduser', ['uses' => 'UsersController@add_user', 'as' => 'adduser']);
        Route::post('/edit_user/{id}', ['uses' => 'UsersController@edit_user', 'as' => 'edituser']);
        

        //Network
        Route::get('/network', ['uses' => 'NetworkController@index', 'as' => 'network']);
        Route::get('/networks', ['uses' => 'NetworkController@index2', 'as' => 'networks']);
        Route::post('/add_network', ['uses' => 'NetworkController@Add_Network', 'as' => 'add_network']);
        Route::get('/delete_network/{id}', ['uses' => 'NetworkController@Delete', 'as' => 'delete']);
        Route::post('/edit_network/{id}', ['as' => 'edit', 'uses' => 'NetworkController@update']);
        Route::get('/network_state/{id}/{v}', 'NetworkController@state');
        route::get('/getid/{id}', 'NetworkController@getid');

        //group
        Route::get('/group', ['uses' => 'GroupController@index', 'as' => 'group']);
        Route::get('/groups', ['uses' => 'GroupController@Jasondata', 'as' => 'groups']);
        Route::get('/delete_group/{id}', ['uses' => 'GroupController@Delete', 'as' => 'delete']);
        Route::post('/edit_group/{id}', ['uses' => 'GroupController@update', 'as' => 'edit']);
        Route::get('/groupz/{id}', ['uses' => 'GroupController@Viewedit', 'as' => 'groupz']);
        Route::get('/group_state/{id}/{v}', 'GroupController@state');
        Route::post('/add_group', ['as' => 'add_group', 'uses' => 'GroupController@add']);
        Route::get('website-filtration-delete/{id}/{groupid}', ['uses' => 'GroupController@website_delete']);


        //Administration
        Route::get('admins', ['uses' => 'AdminController@index', 'as' => 'admins']);
        Route::get('adminjson', ['uses' => 'AdminController@Data', 'as' => 'adminjson']);
        Route::post('addadmin', 'AdminController@register');
        Route::post('editadmin/{id}', 'AdminController@edit');
        Route::get('delete_admin/{id}', 'AdminController@Delete');
        Route::get('getadmin/{id}', 'AdminController@getadmin');
        Route::get('myprofile', ['uses' => 'AdminController@profile', 'as' => 'myprofile']);
        Route::post('changepassword', ['uses' => 'AdminController@changepassword', 'as' => 'changepassword']);
        Route::post('editprofile', ['uses' => 'AdminController@editprofile', 'as' => 'editprofile']);
        Route::any('logout', function(){
            Session::flush();
            return redirect('/admin');
        });

        //Reseller
        Route::get('reseller_payment/{id}', ['uses' => 'AdminController@reseller_payment', 'as' => 'reseller_payment']);
        Route::post('reseller_add_payment', ['uses' => 'AdminController@reseller_add_payment', 'as' => 'reseller_add_payment']);
        Route::get('reseller_delete_payment/{id}/{credit}', ['uses' => 'AdminController@reseller_delete_payment', 'as' => 'reseller_delete_payment']);

        Route::get('reseller_credit/{id}', ['uses' => 'AdminController@reseller_credit', 'as' => 'reseller_credit']);
        Route::post('reseller_add_credit', ['uses' => 'AdminController@reseller_add_credit', 'as' => 'reseller_add_credit']);
        Route::get('reseller_delete_credit/{id}/{reseller_id}/{credit}', ['uses' => 'AdminController@reseller_delete_credit', 'as' => 'reseller_delete_credit']);

        Route::get('reseller_cards/{id}', ['uses' => 'AdminController@reseller_cards', 'as' => 'reseller_cards']);
        Route::post('reseller_add_cards', ['uses' => 'AdminController@reseller_add_cards', 'as' => 'reseller_add_cards']);
        Route::get('reseller_delete_cards/{id}', ['uses' => 'AdminController@reseller_delete_cards', 'as' => 'reseller_delete_cards']);

        //Branches
        Route::get('/branches', ['uses' => 'BranchesController@index', 'as' => 'branches']);
        Route::get('/branchesjson', ['uses' => 'BranchesController@Json', 'as' => 'branchesjson']);
        Route::post('/addbranch', ['uses' => 'BranchesController@Add', 'as' => 'addbranch']);
        Route::get('/delete_branch/{id}', 'BranchesController@delete_branch');
        Route::get('/branch_state/{id}/{v}', 'BranchesController@state');
        Route::get('/branchid/{id}', 'BranchesController@branchid');
        Route::post('/edit_branch/{id}', 'BranchesController@Edit');
        Route::get('/reboot/{id}', 'BranchesController@reboot');
        Route::get('/reset/{id}', 'BranchesController@reset');

        Route::get('load-balancing-delete/{id}/{branchid}', ['uses' => 'BranchesController@load_balancing_delete']);
        Route::get('bypass-delete/{id}/{branchid}', ['uses' => 'BranchesController@bypass_delete']);
            

        Route::get('notifications', function(){
            
            $value = "";
            foreach (App\History::where(['type1' => 'branches_changes'])->orderBy('id','desc')->get() as $values) {
                $value.= '<li class="media green">';
                    $value.= '<div class="media-left">';   
                
                if($values->details == 1){
                    $value.= '<a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></a>';   
                }else{
                    $value.= '<a href="#" class="btn border-success text-success btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-commit"></i></a>';   
                }
                    $value.= '</div>';   
                $value.= '<div class="media-body">';
                    $operation = str_replace('_', ' ', $values->operation);   
                    if($values->details == 1){
                        $value.= "<a href=\"#\"> $operation</a> Pending.";
                    }else{
                        $value.= "<a href=\"#\"> $operation</a> Done.";
                    }   
                $value.= "<div class=\"media-annotation\">$values->add_date $values->add_time</div>";   
                    $value.= '</div>';   
                $value.= '</li>';
            }
            return $value;
        });

        Route::get('notifications_opend', function(){
            
            App\History::where('type1' , 'branches_changes')->WhereNull('notes')->update(['notes' => "1"]);
        });

        //Settings
        Route::get('/settings', ['uses' => 'SettingsController@Index', 'as' => 'settings']);
        Route::post('/Systemsetting', ['uses' => 'SettingsController@Systemsetting', 'as' => 'savesetting']);
        Route::post('/Systemsettingemail', ['uses' => 'SettingsController@Systemsettingemail', 'as' => 'savesetting']);

        Route::post('/Accountsetting', ['uses' => 'SettingsController@Accountsetting', 'as' => 'accountsetting']);
        Route::post('logo', 'SettingsController@logo');
        Route::post('smssetting', ['uses' => 'SettingsController@SMSSettings', 'as' => 'smssetting']);
        Route::post('whatsappSetting', ['uses' => 'SettingsController@whatsappSetting', 'as' => 'whatsappSetting']);
        Route::post('pmsSetting', ['uses' => 'SettingsController@pmsSetting', 'as' => 'pmsSetting']);
        Route::post('telegramSetting', ['uses' => 'SettingsController@telegramSetting', 'as' => 'telegramSetting']);
        Route::post('simpleTouchSetting', ['uses' => 'SettingsController@simpleTouchSetting', 'as' => 'simpleTouchSetting']);
        Route::post('facebooksetting', ['uses' => 'SettingsController@Facebooksettings', 'as' => 'facebooksetting']);
        Route::post('chatGptSetting', ['uses' => 'SettingsController@ChatGptSetting', 'as' => 'chatGptSetting']);
        Route::post('twittersetting', ['uses' => 'SettingsController@Twittersettings', 'as' => 'twittersetting']);
        Route::post('googlesetting', ['uses' => 'SettingsController@Googlesettings', 'as' => 'googlesetting']);
        Route::post('agilesetting', ['uses' => 'SettingsController@Agilesetting', 'as' => 'agilesetting']);
        Route::post('linkedinsetting', ['uses' => 'SettingsController@Linkedinsettings', 'as' => 'linkedinsetting']);
        Route::post('accountkitsmssetting', ['uses' => 'SettingsController@AccountkitSMSSettings', 'as' => 'accountkitsmssetting']);
        Route::post('firebaseSMSauthSetting', ['uses' => 'SettingsController@firebaseSMSauthSetting', 'as' => 'firebaseSMSauthSetting']);
        Route::get('payment/{type}/{paymentMethod}/{modules?}/{concurrent?}/{billing_cycle?}/{mobile_wallet_numner?}/{paymentAmount?}', ['uses' => 'PayMobController@paymob', 'as' => 'paymob']);
        Route::get('payasyougoInvoices', ['uses' => 'SettingsController@payasyougoInvoices', 'as' => 'payasyougoInvoices']);
        Route::get('payasyougoState/{state}', ['uses' => 'SettingsController@payasyougoState', 'as' => 'payasyougoState']);
        Route::get('simpleTouchPosIntegrationState/{state}', ['uses' => 'SettingsController@simpleTouchPosIntegrationState', 'as' => 'simpleTouchPosIntegrationState']);
        Route::get('posRocketIntegrationState/{state}', ['uses' => 'SettingsController@posRocketIntegrationState', 'as' => 'posRocketIntegrationState']);
        Route::get('getWhatsappChannels', ['uses' => 'SettingsController@getWhatsappChannels', 'as' => 'getWhatsappChannels']);
        Route::get('getPms', ['uses' => 'SettingsController@getPms', 'as' => 'getPms']);
        Route::post('addPmsIntegration', ['uses' => 'SettingsController@addPmsIntegration', 'as' => 'addPmsIntegration']);
        Route::get('viewEditOfPmsIntegration/{id}', ['uses' => 'SettingsController@viewEditOfPmsIntegration', 'as' => 'viewEditOfPmsIntegration']);
        Route::post('editPmsIntegration/{id}', ['uses' => 'SettingsController@editPmsIntegration', 'as' => 'editPmsIntegration']);
        Route::get('deletePmsIntegration/{d}', ['uses' => 'SettingsController@deletePmsIntegration', 'as ' => 'deletePmsIntegration']);
        Route::get('restartPmsIntrface', ['uses' => 'PmsInterface@restartPmsIntrface', 'as ' => 'restartPmsIntrface']);
        Route::post('addWhatsappIntegration', ['uses' => 'SettingsController@addWhatsappIntegration', 'as' => 'addWhatsappIntegration']);
        Route::get('viewEditOfWhatsappIntegration/{id}', ['uses' => 'SettingsController@viewEditOfWhatsappIntegration', 'as' => 'viewEditOfWhatsappIntegration']);
        Route::post('editWhatsappIntegration/{id}', ['uses' => 'SettingsController@editWhatsappIntegration', 'as' => 'editWhatsappIntegration']);
        Route::get('deleteWhatsappIntegration/{d}', ['uses' => 'SettingsController@deleteWhatsappIntegration', 'as ' => 'deleteWhatsappIntegration']);
        Route::get('restartWhatsappIntegration/{d}', ['uses' => 'SettingsController@restartWhatsappIntegration', 'as ' => 'restartWhatsappIntegration']);
        
        //Landing page
        Route::get('landings', ['as' => 'landings', 'uses' => 'LandingController@landing']);
        Route::get('landing_delete/{id}', ['uses' => 'LandingController@landing_delete', 'as ' => 'landing_delete']);
        Route::get('get_landing', ['uses' => 'LandingController@get_landing', 'as' => 'get_landing']);
        Route::get('landing_state/{unique_id}/{v}', ['uses' => 'LandingController@landing_state', 'as' => 'landing_state']);
        Route::get('perview_landing/{unique_id}', ['uses' => 'LandingController@perview_landing', 'as' => 'perview_landing']);
        Route::get('landing_info/{name}/{branch_id?}', ['uses' => 'LandingController@landing_info', 'as ' => 'landing_info']);
        Route::post('landing_edit', ['uses' => 'LandingController@add_media', 'as' => 'landing_edit']);
        Route::post('add_branch_landing', ['uses' => 'LandingController@add_branch_landing', 'as' => 'add_branch_landing']);
        Route::get('branch_landing_delete/{branch_id}', ['uses' => 'LandingController@branch_landing_delete', 'as ' => 'branch_landing_delete']);
        
        //Packages
        Route::get('packages', ['as' => 'packages', 'uses' => 'PackagesController@Index']);
        Route::get('/packagesjson', ['uses' => 'PackagesController@Json', 'as' => 'packagesjson']);
        Route::post('addpackages', ['uses' => 'PackagesController@Add', 'as' => 'addpackages']);
        Route::get('packages/{id}', ['as' => 'packages', 'uses' => 'PackagesController@getedit']);
        Route::get('delete_packages/{id}', ['as' => 'delete_packages', 'uses' => 'PackagesController@delete']);
        Route::post('editpackages', ['uses' => 'PackagesController@Edit', 'as' => 'editpackages']);
        Route::get('packages_state/{id}/{v}', ['uses' => 'PackagesController@state', 'as' => 'state']);

        //Cards
        Route::get('cards', ['as' => 'cards', 'uses' => 'CardsController@Index']);
        Route::get('getcards', ['as' => 'getcards', 'uses' => 'CardsController@Json']);
        Route::post('addcards', ['as' => 'addcards', 'uses' => 'CardsController@add']);
        Route::get('getcards/{from}/{to}', ['as' => 'getcards', 'uses' => 'CardsController@getcards']);
        Route::get('getcardlist/{from}/{to}', ['as' => 'getcardlist', 'uses' => 'CardsController@getcardlist']);
        Route::get('deletecards/{from}/{to}/{h_id}', ['as' => 'deletecards', 'uses' => 'CardsController@delete']);
        Route::get('statecards/{from}/{to}/{v}/{h_id}', ['uses' => 'CardsController@state', 'as' => 'statecards']);
        Route::get('exportcards/{from}/{to}', ['uses' => 'CardsController@exportcards', 'as' => 'exportcards']);


        //Campaigns
        Route::get('campaign', ['uses' => 'CampaignController@Index', 'as' => 'Campaign']);
        Route::get('get_campaign', ['uses' => 'CampaignController@Json', 'as' => 'get_campaign']);
        Route::get('get_campaign/{id}', ['uses' => 'CampaignController@get_campaign', 'as' => 'get_campaign']);
        Route::post('update_campaign/{id}', ['uses' => 'CampaignController@Update', 'as' => 'update_campaign']);
        Route::post('createCampaign', ['uses' => 'CampaignController@Insert', 'as' => 'Campaigns']);
        Route::get('delete_campaign/{id}', ['uses' => 'CampaignController@Delete', 'as' => 'delete_campaign']);
        Route::get('poll/{id}', ['uses' => 'CampaignController@poll', 'as' => 'poll']);
        Route::get('update_poll/{id}', ['uses' => 'CampaignController@Pollupdate', 'as' => 'update_poll']);
        Route::get('poll_delete/{id}', ['uses' => 'CampaignController@delete_survey_option', 'as' => 'poll_delete']);
        Route::get('campaign_state/{id}/{v}', ['uses' => 'CampaignController@state', 'as' => 'state']);
        Route::get('campaign_whatsapp_state/{id}/{v}', ['uses' => 'CampaignController@whatsappState', 'as' => 'whatsappState']);
        Route::get('preview/{id}', ['uses' => 'CampaignController@preview', 'as' => 'preview']);
        Route::get('offers/{id}', ['uses' => 'CampaignController@offers', 'as' => 'offers']);
        Route::get('customersReach/{id}', ['uses' => 'CampaignController@customersReach', 'as' => 'customersReach']);
        Route::get('getoffers', ['uses' => 'CampaignController@getoffers', 'as' => 'getoffers']);
        Route::get('offer_state/{id}/{v}', ['uses' => 'CampaignController@offer_state', 'as' => 'offer_state']);
        Route::get('surveyOptionDelete/{id}', ['uses' => 'CampaignController@surveyOptionDelete']);
        Route::get('loyaltyProgramDelete/{id}', ['uses' => 'CampaignController@loyaltyProgramDelete']);
        Route::get('loyaltyProgramItemDelete/{id}', ['uses' => 'CampaignController@loyaltyProgramItemDelete']);


        Route::post('uploadLogo',['uses' => 'SettingsController@logo', 'as' => 'uploadlogo']);

        Route::post('data', function (Request $request) {

            return Input::get('sartdate') . " " . Input::get('enddate') . " " . Input::get('admin') . " " . Input::get('name');
        });

        Route::get('onlineUsersNow/{id}', function ($id) {
            $counterOnlineUsers = App\Models\UserActive::where('branch_id', $id)->count();
            $countAllUsers = App\Users::where("Registration_type", "2")->where("u_state", "1")->where("suspend", "0")->where('branch_id', $id)->count();
            if ($countAllUsers != 0) {
                $percentage = round(($counterOnlineUsers / $countAllUsers) * 100, 1);
            } else {
                $percentage = 0;
            }
            //return ['percentage' => $percentage,'counterOnlineUsers' => $counterOnlineUsers,'countAllUsers' => $countAllUsers];
            return $percentage . ";" . $counterOnlineUsers . " of " . $countAllUsers;
        });

        Route::get('totalDownloadSpeed/{id}', function ($id) {
            $currentDownSpeed = App\History::where('operation','interface_out_rate')->where('branch_id',$id)->value('notes');
            $netDownSpeed = App\History::where('operation','interface_out_net_speed')->where('branch_id',$id)->value('notes');
            if ($currentDownSpeed != 0) {
                $percentage = round(($currentDownSpeed / $netDownSpeed) * 100, 1);
            } else {
                $percentage = 0;
            }
            $currentDownSpeedToMB = round($currentDownSpeed/1024,1);
            $netDownSpeedToMB = round($netDownSpeed/1024,1);
            return $percentage . ";" . $currentDownSpeedToMB . "MB of " . $netDownSpeedToMB . "MB";
        });
        
        Route::get('totalUploadSpeed/{id}', function ($id) {
            $currentUpSpeed = App\History::where('operation','interface_out_rate')->where('branch_id',$id)->value('details');
            $netUpSpeed = App\History::where('operation','interface_out_net_speed')->where('branch_id',$id)->value('details');
            if ($currentUpSpeed != 0) {
                $percentage = round(($currentUpSpeed / $netUpSpeed) * 100, 1);
            } else {
                $percentage = 0;
            }
            $currentUpSpeedToMB = round($currentUpSpeed/1024,1);
            $netUpSpeedToMB = round($netUpSpeed/1024,1);
            return $percentage . ";" . $currentUpSpeedToMB . "MB of " . $netUpSpeedToMB . "MB";
        });
        

        Route::get('quotaUsageNow/{id}', function ($id) {

            $branches = App\Branches::where('id', $id)->get();

            foreach ($branches as $branche) {
                $branche_limit = $branche->monthly_quota;
                $startQuotaDay = $branche->start_quota;
                if (isset($branche_limit)) {
                    // $f = new DateTime('first day of this month');
                    // $startMonth = $f->format('Y-m-d');
                    // $l = new DateTime('last day of this month');
                    // $endMonth = $l->format('Y-m-d');
                    $currDay=date('d');
                    if($currDay<$startQuotaDay){
                        // get last month
                        $endMonth=date("Y-m")."-".$startQuotaDay;
                        $startMonth = date('Y-m-d', strtotime('-1 month', strtotime($endMonth)));
                    }else{
                        // get next month
                        $startMonth=date("Y-m")."-".$startQuotaDay;
                        $endMonth = date('Y-m-d', strtotime('+1 month', strtotime($startMonth)));
                    }
					/*
                    $radacct = App\Radacct::where('branch_id', $branche->id)->whereBetween('dates', [$startMonth, $endMonth])->get();
                    if (isset($radacct)) {
                        $totalUpload = 0;
                        $totalDownload = 0;
                        $totalQuota = 0;
                        foreach ($radacct as $currRadacct) {
                            //$totalUpload+=$currRadacct->acctinputoctets;
                            //$totalDownload+=$currRadacct->acctoutputoctets;
                            $totalQuota += ($currRadacct->acctinputoctets + $currRadacct->acctoutputoctets);
                        }
                        //$totalUpload=round($totalUpload/1024/1024/1024,1);
                        //$totalDownload=round($totalDownload/1024/1024/1024,1);
                        $totalQuota = round($totalQuota / 1024 / 1024 / 1024, 1);
                        $percentage = round(($totalQuota / $branche_limit) * 100, 1);

                        return $percentage . ";" . $totalQuota . "GB of " . $branche_limit . "GB";
                    }
					*/
					
					////////////////////////// NEW way to genarate the same result but with lowest execution time 15.3.2019
					$totalUpload=App\Radacct::where('branch_id', $branche->id)->whereBetween('dates', [$startMonth, $endMonth])->sum('acctinputoctets');
					$totalDownload=App\Radacct::where('branch_id', $branche->id)->whereBetween('dates', [$startMonth, $endMonth])->sum('acctoutputoctets');
					if(isset($totalUpload) and isset($totalDownload)){
						$totalQuota = round(($totalUpload+$totalDownload)/1024/1024/1024,1);
						$percentage = round(($totalQuota / $branche_limit) * 100, 1);
					}else{
						$totalQuota = 0;
						$percentage = 0;
					}					
					return $percentage . ";" . $totalQuota . "GB of " . $branche_limit . "GB";
					
                }
            }
        });

        // API charge Package
        Route::get('chargePackages/{u_id}/{package_id}/{confirm?}/{reseller?}', ['as' => 'Chargepackage', 'uses' => 'LandingController@Chargepackage']);



        //Visited Sites
        Route::get('visitedsites', ['uses' => 'VisitedSitesController@index', 'as' => 'visitedsites']);
        Route::post('search_visitedsites', ['uses' => 'VisitedSitesController@search', 'as' => 'search_visitedsites']);












    }else{
        return view('back-end.auth/subdomain');
    }
});



Route::group(['middleware' => ['web']], function () {


    Route::post('validation/{type}', ['as' => 'validations','uses' => 'LandingController@validation']);

    Route::post('install/validation', ['as' => 'validations','uses' => 'InstallationController@validation']);
    Route::post('install/session', ['as' => 'session','uses' => 'InstallationController@session']);
    Route::any('install/installation', ['as' => 'installation','uses' => 'InstallationController@installation']);
    Route::any('install/validateVirificationCode', ['as' => 'installation','uses' => 'InstallationController@validateVirificationCode']);

    $database =  app('App\Http\Controllers\Controller')->configuration();
    if($database !== 0) {
        //User interface
        //Session::has('login')
        Route::any('api/paymobResponseCallback', 'PaymobResponseCallback@responseCallback'); // all "api/" method post is accepted without token from (/home/hotspot/public_html/app/Http/Middleware/VerifyCsrfToken.php)
        Route::any('api/paymeResponseCallback', 'PaymobResponseCallback@responseCallback'); // all "api/" method post is accepted without token from (/home/hotspot/public_html/app/Http/Middleware/VerifyCsrfToken.php)
        Route::any('api/fawrycallback', 'PaymobResponseCallback@responseCallback'); // Fawry direct 
        Route::any('api/whatsappPayCallback', 'WhatsappPayCallback@responseCallback'); // all "api/" method post is accepted without token from (/home/hotspot/public_html/app/Http/Middleware/VerifyCsrfToken.php)
        Route::get('lang/{lang?}', 'LandingController@lang');
        Route::get('/', 'LandingController@index');
        Route::any('/guest/{value1?}/{unifiSiteId?}', ['uses' => 'LandingController@index']);
        
        Route::any('iframe', 'LandingController@iframe');
        Route::any('indexIframe', 'LandingController@indexIframe');
        
        Route::any('firebaseStep1/{gender?}/{name?}/{email?}/{countryCode}/{mobile}', ['uses' => 'LandingController@firebaseStep1', 'as' => 'firebaseStep1']);
        Route::any('firebaseVerifyCode/{gender?}/{name?}/{email?}/{countryCode}/{mobile}/{code}', ['uses' => 'LandingController@firebaseVerifyCode', 'as' => 'firebaseVerifyCode']);
        Route::any('firebaseCreateToken/{mobile}', ['uses' => 'LandingController@firebaseCreateToken', 'as' => 'firebaseCreateToken']);
        Route::any('firebaseLoginSuccess/{token}', ['uses' => 'LandingController@firebaseLoginSuccess', 'as' => 'firebaseLoginSuccess']);
        
        Route::any('signup', ['as' => 'signup', 'uses' => 'LandingController@signup']);
        Route::any('signup_kit',['uses' => 'LandingController@signup_kit']);
        Route::any('social_signup_kit',['uses' => 'LandingController@social_signup_kit']);

        Route::any('activation', 'LandingController@activation');


        Route::get('paymob/{type}', ['as' => 'paymob', 'uses' => 'PayMobController@paymob']);
        // Route::get('paymobWallet', ['as' => 'paymobWallet', 'uses' => 'PayMobController@paymobWallet']);
        // Route::get('paymobCash', ['as' => 'paymobCash', 'uses' => 'PayMobController@paymobCash']);
        
        Route::any('firebase', ['as' => 'firebaseController', 'uses' => 'firebaseController@firebaseView']);
        Route::any('api/testController/{var1?}/{var2?}/{var3?}/{var4?}/{var5?}', ['as' => 'testController', 'uses' => 'TestController@testController']);
        Route::any('api/testController2', ['as' => 'whatsapp', 'uses' => 'Whatsapp@whatsapp']);
        Route::any('api/whatsapp', ['as' => 'whatsapp', 'uses' => 'Whatsapp@whatsapp']);
        Route::any('api/POSrocketCallback', ['as' => 'POSrocketCallback', 'uses' => 'POSrocketCallback@POSrocket']);
        Route::any('api/POSrocketCron', ['as' => 'POSrocketCron', 'uses' => 'POSrocketCron@posRocketCron']);
        Route::any('api/test', 'ApiController@index');
        
		Route::any('replication', ['as' => 'replication', 'uses' => 'ReplicationController@replication']);
        Route::any('mikrotikapi', ['as' => 'mikrotikapi', 'uses' => 'MikrotikapiController@mikrotikapi']);
        Route::any('microsystemCron', ['as' => 'MicrosystemCron', 'uses' => 'MicrosystemCron@MicrosystemCron']);
        Route::any('websitefilter', ['as' => 'websiteFilter', 'uses' => 'WebsiteFilterController@websiteFilter']);

        Route::get('cron', ['as' => 'cron', 'uses' => 'Cron@cron']);
        Route::get('whatsappcron', ['as' => 'whatsappCron', 'uses' => 'WhatsappCron@whatsappCron']);
        Route::get('cronEveryMinute', ['as' => 'cronEveryMinute', 'uses' => 'CronEveryMinute@cronEveryMinute']);
        Route::get('RemoveLockedSessions', ['as' => 'RemoveLockedSessions', 'uses' => 'RemoveLockedSessions@removeLockedSessions']);
        Route::get('abuseDetectionPmsCron', ['as' => 'abuseDetectionPmsCron', 'uses' => 'AbuseDetectionPmsCron@abuseDetectionPmsCron']);
        Route::get('dataBaseArchiveCron', ['as' => 'dataBaseArchiveCron', 'uses' => 'DataBaseArchiveCron@dataBaseArchiveCron']);
        Route::get('cronScheduleHotelGuestNotificationsStep1', ['as' => 'cronScheduleHotelGuestNotificationsStep1', 'uses' => 'CronScheduleHotelGuestNotificationsStep1@cronScheduleHotelGuestNotificationsStep1']);
        Route::get('cronScheduleWeeklyMonthlyAnnuallyNotifications', ['as' => 'cronScheduleWeeklyMonthlyAnnuallyNotifications', 'uses' => 'CronScheduleWeeklyMonthlyAnnuallyNotifications@cronScheduleWeeklyMonthlyAnnuallyNotifications']);
        
        // copy users from database to another database
        Route::any('copy', ['uses' => 'Copy@copy']);
        // upgrade system version and execute database queries on all databases in one shot
        Route::any('upgradeAndExecuteSQLqueriesAllDB', ['uses' => 'Copy@upgradeAndExecuteSQLqueriesAllDB']);
        Route::get('account', ['as' => 'account', 'uses' => 'LandingController@account']);
        Route::any('userlogin', ['as' => 'userlogin', 'uses' => 'LandingController@login']);
        Route::post('disconnect', ['as' => 'disconnect', 'uses' => 'LandingController@disconnect']);
        Route::any('userlogout', ['as' => 'userlogout', 'uses' => 'LandingController@logout']);
        Route::post('sendmessage', ['as' => 'sendmessage', 'uses' => 'LandingController@contact']);
        Route::get('delete_message_user/{id}', ['as' => 'delete_message_user', 'uses' => 'LandingController@deletebyuser']);
        Route::post('confirm', ['as' => 'smsconfirm', 'uses' => 'LandingController@smsconfirm']);
        Route::post('send_code', ['as' => 'sendcode', 'uses' => 'LandingController@sendcode']);
        Route::get('showcode/{u_id}', ['as' => 'showcode', 'uses' => 'LandingController@showcode']);
        Route::any('/charge', ['uses' => 'LandingController@charge', 'as' => 'charge']);

        Route::get('forget_modal', ['as' => 'forget_password', 'uses' => 'LandingController@forget_modal']);

        Route::any('forget_password', ['as' => 'forget_password', 'uses' => 'LandingController@forget']);

        Route::get('resetPassword/{token}', ['as' => 'reset_password', 'uses' => 'LandingController@reset_modal']);

        Route::post('reset', ['as' => 'reset_password', 'uses' => 'LandingController@reset']);

        Route::get('viewPackage/{u_id}/{package_id}', ['as' => 'viewPackage', 'uses' => 'LandingController@ViewPackage']);

        // API charge Package
        Route::get('chargePackage/{u_id}/{package_id}/{confirm?}', ['as' => 'Chargepackage', 'uses' => 'LandingController@Chargepackage']);


        Route::get('campaign_click/{id}/{userid}/{type?}', ['uses' => 'CampaignController@campaign_click', 'as' => 'campaign_click']);
        Route::post('survey_vote', ['uses' => 'CampaignController@survey_vote', 'as' => 'survey_vote']);
        Route::get('specialoffer/{id}/{campaign}/{offercode}', ['uses' => 'CampaignController@Specialoffer', 'as' => 'Specialoffer']);

        Route::post('signupoffer', ['uses' => 'CampaignController@signupoffer', 'as' => 'signupoffer']);
        Route::get('success', ['uses' => 'CampaignController@success', 'as' => 'success']);


        Route::post('sharee', ['uses' => 'CampaignController@doShare', 'as' => 'share']);


        // Social Authentication
        Route::get('auth/facebook', 'Auth\SocialController@redirectToProvider');
        Route::get('auth/facebook/callback', 'Auth\SocialController@handleProviderCallback');

        Route::get('auth/google', 'Auth\SocialController@redirectToProvider');
        Route::get('auth/google/callback', 'Auth\SocialController@handleProviderCallback');

        Route::get('auth/twitter', 'Auth\SocialController@redirectToProvider');
        Route::get('auth/twitter/callback', 'Auth\SocialController@handleProviderCallback');

        Route::get('auth/linkedin', 'Auth\SocialController@redirectToProvider');
        Route::get('auth/linkedin/callback', 'Auth\SocialController@handleProviderCallback');

        Route::get('campaign_offline', ['uses' => 'CampaignController@campaign_offline', 'as' => 'campaign_offline']);

        Route::post('get_offer', ['uses' => 'CampaignController@get_offer', 'as' => 'get_offer']);

        Route::get('salesforce', function () {
            try {
                echo print_r(Salesforce::describeLayout('Account'), true);
            } catch (Exception $e) {
                echo $e->getMessage();
                //echo $e->getTraceAsString;
            }
        });

        /////////////////////////////////////////////////////////////////////////// Agile CRM ////////////////////////////////////////////////////////////////////////////
        // Route::get('agiles', function () {
        //     include("Controllers/agilecrm.php");
        //     $contacts = curl_wrap("contacts/4756592260022272", null, "GET", "application/json");
        //     $results = json_decode($contacts, false, 512, JSON_BIGINT_AS_STRING);
        //     return $contacts;
        // });
        // Route::get('test', function()
        // {
        //      $url =  Share::load('http://fr3on.info', 'aaaaaaaaa','aaaaaaaa')->services('facebook','twitter');
        //      return Redirect::to($url['facebook']);
        // });
        Route::get('test111', function()
        {
            
            
            $mac = 'C8147912D795';
            $macType = strpos($mac,":");
            if ($macType === false and strlen($mac)>=12 ) {
                return "Aruba";
            } else {
                return "normal";
            }

        });
        Route::get('sendContactsToAgileCRM', function () {

            if (App\Settings::where('type', 'agile_send_comtacts')->value('state') == 1) {

                include("Controllers/agilecrm.php");
                $resultWaitingForInsert = App\Users::where('Registration_type', '2')->whereNull('agilecrm_id')->orWhere('agilecrm_id','')->get();
                if (isset($resultWaitingForInsert)) {// founded user so I will skip it founded user ( user already sent to agile pefore
                // user not founded
                    foreach ($resultWaitingForInsert as $waitingForInsert) {
                        if (strpos($waitingForInsert->u_email, '@') !== false) {
                            $contact_email = $waitingForInsert->u_email;
                        } else {
                            $contact_email = $waitingForInsert->u_id;
                        }// make random mail

                        if ($waitingForInsert->network_id) {
                            $networkName = "Network_" . App\Network::where('id', $waitingForInsert->network_id)->value('name');
                        }
                        if ($waitingForInsert->group_id) {
                            $groupName = "Group_" . App\Groups::where('id', $waitingForInsert->group_id)->value('name');
                        }
                        if ($waitingForInsert->branch_id) {
                            $branchName = "Branch_" . App\Branches::where('id', $waitingForInsert->branch_id)->value('name');
                        }

                        if ($waitingForInsert->u_gender == 2) {
                            $genderName = "Unknown";
                        } elseif ($waitingForInsert->u_gender == 1) {
                            $genderName = "Male";
                        } elseif ($waitingForInsert->u_gender == 0) {
                            $genderName = "Female";
                        } else {
                            $genderName = "Unknown";
                        }
                        $userAddress = json_encode($waitingForInsert->u_address);

                        if ($waitingForInsert->u_country) {
                            if ($waitingForInsert->u_country == "Egypt" or $waitingForInsert->u_country == "EG" or $waitingForInsert->u_country == "egypt") {
                                $userCountry = "Egypt";
                            } else {
                                $userCountry = $waitingForInsert->u_country;
                            }
                        } else {
                            $userCountry = "";
                        }

                        $contact_json = array(
                            "tags" => array("Microsystem Hotspot", "$networkName", "$groupName", "$branchName"),

                            "properties" => array(
                                array(
                                    "name" => "first_name",
                                    "value" => $waitingForInsert->u_name,
                                    "type" => "SYSTEM"
                                ),
                                array(
                                    "name" => "email",
                                    "value" => "$contact_email",
                                    "type" => "SYSTEM"
                                ),
                                array(
                                    "name" => "address",
                                    "value" => "{address:$userAddress,country:$userCountry}",
                                    "type" => "SYSTEM"
                                ),
                                array(
                                    "name" => "country",
                                    "value" => "$userCountry",
                                    "type" => "SYSTEM"
                                ),
                                array(
                                    "name" => "phone",
                                    "value" => $waitingForInsert->u_phone,
                                    "type" => "SYSTEM"
                                ),
                                array(
                                    "name" => "username",  //This is custom field which you should first define in custom field region.
                                    //Example - created custom field : http://snag.gy/kLeQ0.jpg
                                    "value" => $waitingForInsert->u_uname,
                                    "type" => "CUSTOM"
                                ),
                                array(
                                    "name" => "password",
                                    "value" => "$waitingForInsert->u_password",      // This is epoch time in seconds.
                                    "type" => "CUSTOM"
                                ),
                                array(
                                    "name" => "gender",
                                    "value" => "$genderName",      // This is epoch time in seconds.
                                    "type" => "CUSTOM"
                                )

                            )
                        );

                        $contact_json = json_encode($contact_json);
                        $resultsReturnedData = curl_wrap("contacts", $contact_json, "POST", "application/json");

                        $final_id = "";
                        if (is_numeric($resultsReturnedData[5])) {
                            $final_id .= $resultsReturnedData[5];
                        }
                        if (is_numeric($resultsReturnedData[6])) {
                            $final_id .= $resultsReturnedData[6];
                        }
                        if (is_numeric($resultsReturnedData[7])) {
                            $final_id .= $resultsReturnedData[7];
                        }
                        if (is_numeric($resultsReturnedData[8])) {
                            $final_id .= $resultsReturnedData[8];
                        }
                        if (is_numeric($resultsReturnedData[9])) {
                            $final_id .= $resultsReturnedData[9];
                        }
                        if (is_numeric($resultsReturnedData[10])) {
                            $final_id .= $resultsReturnedData[10];
                        }
                        if (is_numeric($resultsReturnedData[11])) {
                            $final_id .= $resultsReturnedData[11];
                        }
                        if (is_numeric($resultsReturnedData[12])) {
                            $final_id .= $resultsReturnedData[12];
                        }
                        if (is_numeric($resultsReturnedData[13])) {
                            $final_id .= $resultsReturnedData[13];
                        }
                        if (is_numeric($resultsReturnedData[14])) {
                            $final_id .= $resultsReturnedData[14];
                        }
                        if (is_numeric($resultsReturnedData[15])) {
                            $final_id .= $resultsReturnedData[15];
                        }
                        if (is_numeric($resultsReturnedData[16])) {
                            $final_id .= $resultsReturnedData[16];
                        }
                        if (is_numeric($resultsReturnedData[17])) {
                            $final_id .= $resultsReturnedData[17];
                        }
                        if (is_numeric($resultsReturnedData[18])) {
                            $final_id .= $resultsReturnedData[18];
                        }
                        if (is_numeric($resultsReturnedData[19])) {
                            $final_id .= $resultsReturnedData[19];
                        }
                        if (is_numeric($resultsReturnedData[20])) {
                            $final_id .= $resultsReturnedData[20];
                        }
                        if (is_numeric($resultsReturnedData[21])) {
                            $final_id .= $resultsReturnedData[21];
                        }
                        if (is_numeric($resultsReturnedData[22])) {
                            $final_id .= $resultsReturnedData[22];
                        }
                        if (is_numeric($resultsReturnedData[23])) {
                            $final_id .= $resultsReturnedData[23];
                        }
                        if (is_numeric($resultsReturnedData[24])) {
                            $final_id .= $resultsReturnedData[24];
                        }
                        if (is_numeric($resultsReturnedData[25])) {
                            $final_id .= $resultsReturnedData[25];
                        }
                        //$final_id=(int)$final_id;
                        App\Users::where('u_id', $waitingForInsert->u_id)->update(['agilecrm_id' => $final_id]);
                        $final_id;

                    }
                }
            }
        });
        Route::get('getAgileContacts', function () {

            if (App\Settings::where('type', 'agile_receive_contacts')->value('state') == 1) {
                include("Controllers/agilecrm.php");
                //$contacts = curl_wrap("contacts", null, "GET", "application/json");
                $result = curl_wrap("contacts", null, "GET", "application/json");

                $results = json_decode($result, false, 512, JSON_BIGINT_AS_STRING);

                foreach ($results as $agileUser) {
                    //echo $agileUser->id;echo "<br>";
                    if (App\Users::where('agilecrm_id', $agileUser->id)->first()) {// founded user so I will skip it

                    } else {// user not founded
                        //return $agileUser->id;
                        //print_r( $agileUser->properties['0']->value );// name
                        //print_r( $agileUser->properties['1']->value );
                        $propertiesCounter = count($agileUser->properties);
                        $first_name = "";
                        $last_name = "";
                        $title = "";
                        $email = "";
                        $phone = "";
                        $website = "";
                        $country = "";
                        $countryname = "";
                        $address = "";
                        $company = "";
                        $gender = "";

                        for ($i = 0; $i < $propertiesCounter; $i++) {
                            if ($agileUser->properties[$i]->name == "first_name") {
                                $first_name = $agileUser->properties[$i]->value;
                            }
                            if ($agileUser->properties[$i]->name == "last_name") {
                                $last_name = $agileUser->properties[$i]->value;
                            }
                            if ($agileUser->properties[$i]->name == "title") {
                                $title = $agileUser->properties[$i]->value;
                            }
                            if ($agileUser->properties[$i]->name == "email") {
                                if ($email) {
                                    $email .= ";" . $agileUser->properties[$i]->value;
                                } else {
                                    $email = $agileUser->properties[$i]->value;
                                }
                            }
                            if ($agileUser->properties[$i]->name == "phone") {
                                if ($phone) {
                                    $phone .= ";" . $agileUser->properties[$i]->value;
                                } else {
                                    $phone = $agileUser->properties[$i]->value;
                                }
                            }
                            if ($agileUser->properties[$i]->name == "website") {
                                $website = $agileUser->properties[$i]->value;
                            }
                            if ($agileUser->properties[$i]->name == "gender") {
                                $gender = $agileUser->properties[$i]->value;
                            }
                            if ($agileUser->properties[$i]->name == "address") {
                                $addressDecoded = json_decode($agileUser->properties[$i]->value);
                                if (isset($addressDecoded->country)) {
                                    $country = $addressDecoded->country;
                                }
                                if (isset($addressDecoded->countryname)) {
                                    $countryname = $addressDecoded->countryname;
                                }
                                if (isset($addressDecoded->address)) {
                                    $address = $addressDecoded->address;
                                }
                            }
                            if ($agileUser->properties[$i]->name == "company") {
                                $company = $agileUser->properties[$i]->value;
                            }

                        }

                        $fullName = $first_name . " " . $last_name;

                        if ($countryname) {
                            $finalCountry = $countryname;
                        } elseif ($country == "EG") {
                            $finalCountry = "Egypt";
                        } else {
                            $finalCountry = $country;
                        }

                        if ($gender == "Male" or $gender == "male" or $gender == "ط°ظƒط±") {
                            $gender = "1";
                        } elseif ($gender == "Female" or $gender == "female" or $gender == "ط§ظ†ط«ظ‰" or $gender == "ط£ظ†ط«ظ‰" or $gender == "ط§ظ†ط«ظٹ") {
                            $gender = "0";
                        } else {
                            $gender = 2;
                        }
                        // echo "first_name : $first_name";echo "<br>";
                        // echo "last_name : $last_name";echo "<br>";
                        // echo "title : $title";echo "<br>";
                        // echo "email : $email";echo "<br>";
                        // echo "phone : $phone";echo "<br>";
                        // echo "website : $website";echo "<br>";
                        // echo "country : $country";echo "<br>";
                        // echo "countryname : $countryname";echo "<br>";
                        // echo "Address : $address";echo "<br>";
                        // echo "company : $company";echo "<br>";echo "<br>";

                        // get default or first Network
                        $getDefaultNetworkID = App\Network::where('name', 'default')->value('id');
                        if (isset($getDefaultNetworkID)) {
                            $finalNetworkID = $getDefaultNetworkID;
                        } else {
                            $finalNetworkID = App\Network::value('id');
                        }

                        //get default or first group
                        $getDefaultGroupsID = App\Groups::where('name', 'default')->value('id');
                        if (isset($getDefaultGroupsID)) {
                            $finalGroupID = $getDefaultGroupsID;
                        } else {
                            $finalGroupID = App\Groups::value('id');
                        }

                        // get default or first branch
                        $getDefaultBranchID = App\Branches::where('name', 'default')->value('id');
                        if (isset($getDefaultBranchID)) {
                            $finalBranchID = $getDefaultBranchID;
                        } else {
                            $finalBranchID = App\Branches::value('id');
                        }

                        App\Users::insert(['agilecrm_id' => $agileUser->id
                            , 'u_name' => $fullName
                            , 'u_country' => $finalCountry
                            , 'u_phone' => $phone
                            , 'u_email' => $email
                            , 'u_address' => $address
                            , 'suspend' => '0'
                            , 'u_state' => '1'
                            , 'Registration_type' => '2'
                            , 'u_gender' => $gender
                            , 'network_id' => $finalNetworkID
                            , 'group_id' => $finalGroupID
                            , 'branch_id' => $finalBranchID
                            , 'u_country' => $finalCountry]);
                    }


                }
                //return count($results);
            }
        });
        
        Route::get('agile_score', function () {
            if (App\Settings::where('type', 'agile_send_login_score')->value('state') == 1) {
                include("Controllers/agilecrm.php");
                if ($resultAgileUsers = App\Users::whereNotNull('agilecrm_id')->where('agilecrm_id','!=','')->get()) {
                    foreach ($resultAgileUsers as $agileUsers) {

                        if ($currentUserLoginCountr = App\Models\UsersRadacct::where('u_id', $agileUsers->u_id)->count()) {
                            $contact_json = array(
                                "id" => $agileUsers->agilecrm_id, //It is mandatory field. Id of contact
                                "lead_score" => $currentUserLoginCountr
                            );

                            $contact_json = json_encode($contact_json);
                            curl_wrap("contacts/edit/lead-score", $contact_json, "PUT", "application/json");
                        }
                    }
                }

            }

        });
        ///////////////////////////////////////////////////////////////////////////  End Agiel CRM ///////////////////////////////////////////////////////////////////
    }else{
        return view('back-end.auth/subdomain');
    }
});
