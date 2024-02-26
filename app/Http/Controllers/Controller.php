<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Http\Request;
use App\Models\Visitors;
use Auth;
use Identify;
use DB;
use Session;
use App;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    
    public function __construct(Request $request){
        $subdomain = url()->full();
        $split = explode('/', $subdomain);

        // start set lang
        //App::setLocale('en');
        if (session('lang')) {
            $lang = session('lang');
            //print_r($lang);
            //die ;
            App::setLocale($lang[0]);
        }
        // End set lang

        
        if(isset($split[3]))
        {   
            if(strpos($split[3],"identify=-"))
            {
                $split2 = explode('-', $split[3]);
                $systemIdentify=$split2[1];
                
                if(!session('Identify')){
                    Session::push('Identify', $systemIdentify);
                    
                    // try to share branch id for registration
                    if(isset($split2[2])){
                    Session::push('mikrotikLocationID', $split2[2]);
                    }
                }

                //Session::flush();
            }
        }


    }
    public function configuration(){
        if(session('Identify')){ 
            $session = session('Identify');
            return $session[0];
        }else{
            $subdomain = url()->full();
            $split = explode('/', $subdomain);
            if(isset($split[3]))
            {   
                //return $split[3];
                if(strpos($split[3],"identify=-"))
                {
                    $split2 = explode('-', $split[3]);
                    return $systemIdentify=$split2[1];

                }else{$systemIdentify=$split[2];}
            }else{$systemIdentify=$split[2];}
            $database =  DB::table('customers')->where('url',$systemIdentify)->value('database');
            if($database){
                return $database;
            }else{return 0;}
        }
    }

}
