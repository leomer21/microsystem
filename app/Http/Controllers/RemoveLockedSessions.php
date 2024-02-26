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
use Mail;
use DateTime;

class RemoveLockedSessions extends Controller
{
    public function removeLockedSessions(Request $request){
        
        // delete 350 session every minute to avoid CPU high load 30.7.2019
        shell_exec("rm -rf /home/hotspot/public_html/storage/framework/sessions/*");
	}
	
}