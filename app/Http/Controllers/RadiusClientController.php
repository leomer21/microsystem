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

class RadiusClientController extends Controller
{

    public function radiusClient(Request $request){
        
		date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $todayDateTime = $today." ".date("H:i:s");
		// $unifiClass = new App\Http\Controllers\Integrations\Unifi();

		include '../config.php';
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$body = @file_get_contents('php://input');
		// DB::table("test")->insert([['value1' => $actual_link, 'value2' => $body]]);
		$body = json_decode($body);
		// return "stop here";
		
		// start testing 

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        //////////////////////////////////////////////////////////////////////
        
        
        $radius = new App\Http\Controllers\Integrations\RadiusClient();
        $radius->setServer('18.158.29.87')        // IP or hostname of RADIUS server | ProductionServerIP: 159.122.180.78 | stagingServerIP: 18.158.29.87
            ->setSecret('microsystem')       // RADIUS shared secret
            ->setNasIpAddress('111.222.222.222')  // IP or hostname of NAS (device authenticating user)
            ->setAttribute(32, 'demo')       // NAS identifier
            ->setAttribute(31, '48:51:B7:DE:BC:64')       // Calling-Station-Id
            ->setAttribute(30, 'hotspot1')  // Called-Station-Id (Hotspot name)
            ->setAttribute(43, '444')  // Called-Station-Id (Hotspot name)
            ->setAuthenticationPort(1813) // enable it if you need to send accouting message
            //    ->setAttribute(26, 'WISPr-Location-ID')  // WISPr-Location-ID (branch id or VLAN)
            //    ->setAttribute(300, 'orange1') // WISPr-Location-Name (branch name)
            //    ->setAttribute(2, 'password') // User-Password
            ->setDebug()                   // Enable debug output to screen/console
            ;
        // Send access request for a user with username = 'username' and password = 'password!'
        
        $response = $radius->accountingRequest('111111111', '2222222', '333333333', '444444444');
        if ($response === false) {
            // failed
            echo sprintf("Accounting-Request failed with error %d (%s).\n",$radius->getErrorCode(), $radius->getErrorMessage() );
        }else {
            // access request was accepted - client authenticated successfully
            echo "Accounting Request Success!";
            print_r($radius->GetReadableReceivedAttributes());
        }
        return "finish";

        // $radius->setVendorSpecificAttribute('14988', 'WISPr-Location-ID', '16');
        $response = $radius->accessRequest('48:51:B7:DE:BC:64', 'pass');
        // print_r($response);
        // return $response;
        if ($response === false) {
            // false returned on failure
            echo sprintf("Access-Request failed with error %d (%s).\n",
                $radius->getErrorCode(),
                $radius->getErrorMessage()
            );
        } else {
            // access request was accepted - client authenticated successfully
            echo "Success!  Received Access-Accept response from RADIUS server.\n";
            echo "<br><br>";
            foreach($radius->GetReadableReceivedAttributes() as $row){
                // print_r($row);
                if($row[0] == "Vendor-Specific: "){
                    // echo "<br><br>";
                    // print_r($radius->decodeVendorSpecificContent($row[1]['value']));
                    // echo "<br><br>";
                    echo "attr_type = $row[0] |"."| Vendor-Id = ".$row[1]['Vendor-Id']."| Vendor-type = ".$row[1]['Vendor-type']."| value = ".$row[1]['value']." <br>";
                }else{
                    echo "attr_type = $row[0] | value = ".$row[1]." <br>";
                }
                
            }
            return "Finish";
            $attributes = $radius->GetReadableReceivedAttributes();
            print_r($attributes);
            return $attributes[0];
        }
        

        ///////////////////////////////////////////////////////////////////////////////////////////////////

        /*
        $radius = new App\Http\Controllers\Integrations\RadiusClient('18.158.29.87', 'microsystem');
        // $radius->SetNasPort(0);
        // $radius->setDebug();
        $radius->setAttribute(32, 'demo');       // NAS identifier
        $radius->setAttribute(31, '48:51:B7:DE:BC:64');       // Calling-Station-Id
        $radius->setAttribute(30, 'hotspot1');  // Called-Station-Id (Hotspot name)
        $radius->SetNasIpAddress('127.0.0.1'); // Needed for some devices, and not auto_detected if PHP not runned through a web server
        // Enable Debug Mode for the demonstration
        // $radius->SetDebugMode(TRUE);
        if ($radius->AccessRequest('48:51:B7:DE:BC:64', 'pass'))
        {
            echo "Authentication accepted.";
            echo "<br />";
        }
        else
        {
            echo "Authentication rejected.";
            echo "<br />";
        }
        echo $radius->GetReadableReceivedAttributes();
		*/
    }
    
    // Orange AAA login
    public function radiusLogin(Request $request){
        
        date_default_timezone_set("Africa/Cairo");
        $today = date("Y-m-d");
        $today_time = date("g:i a");
        $created_at = $today." ".date("H:i:s");
        
        // // for testing only
        // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// $body = @file_get_contents('php://input');
        // DB::table('test')->insert([['value1' => $actual_link, 'value2' => $body]]);
        
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
   
        $radius = new App\Http\Controllers\Integrations\RadiusClient();
        $radius->setServer('18.158.29.87')        // IP or hostname of RADIUS server | ProductionServerIP: 159.122.180.78 | stagingServerIP: 18.158.29.87
            ->setSecret('microsystem')       // RADIUS shared secret
            ->setNasIpAddress($request->user_ip)  // IP or hostname of NAS (device authenticating user)
            ->setAttribute(32, 'demo')       // NAS identifier
            ->setAttribute(31, $request->user_mac)       // Calling-Station-Id
            ->setAttribute(30, 'hotspot1')  // Called-Station-Id (Hotspot name)
            //    ->setAttribute(26, 'WISPr-Location-ID')  // WISPr-Location-ID (branch id or VLAN)
            //    ->setAttribute(300, 'orange1') // WISPr-Location-Name (branch name)
            //    ->setAttribute(2, 'password') // User-Password
            // ->setDebug()                   // Enable debug output to screen/console
            ;
        // Send access request for a user with username = 'username' and password = 'password!'

        // $radius->setVendorSpecificAttribute('14988', 'WISPr-Location-ID', '16');
        $response = $radius->accessRequest($request->username, $request->password);
        // print_r($response);
        // return $response;
        if ($response === false) {
            // false returned on failure
            // echo sprintf("Access-Request failed with error %d (%s).\n",$radius->getErrorCode(),$radius->getErrorMessage());
            return json_encode(array('state' => '0', 'message' => sprintf("Access-Request failed with error %d (%s).",$radius->getErrorCode(),$radius->getErrorMessage()) ));
        } else {
            // access request was accepted - client authenticated successfully
            // echo "Success!  Received Access-Accept response from RADIUS server.\n";
            // echo "<br><br>";
            foreach($radius->GetReadableReceivedAttributes() as $row){
                // if($row[0] == "Vendor-Specific: "){
                //     echo "attr_type = $row[0] |"."| Vendor-Id = ".$row[1]['Vendor-Id']."| Vendor-type = ".$row[1]['Vendor-type']."| value = ".$row[1]['value']." <br>";
                // }else{
                //     echo "attr_type = $row[0] | value = ".$row[1]." <br>";
                // }
                return json_encode(array('state' => '1', 'message' => 'login successfully.'));
            }
            // return "Finish";
            // $attributes = $radius->GetReadableReceivedAttributes();
            // print_r($attributes);
            // return $attributes[0];
        }
        
    }
}