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
 
class WebsiteFilterController extends Controller
{
    public function websiteFilter(Request $request){
        foreach(DB::table('customers')->where('state','1')->where('websites_log','1')->get() as $client){
            DB::table($client->database.'.SystemEvents')->whereNull('u_id')->delete();
        }
        /*
        foreach(DB::table('customers')->where('state','1')->where('websites_log','1')->get() as $client){

            if(count(DB::table($client->database.'.branches')->where('users_log_history_type','2')->orWhere('users_log_history_type','3')->orWhere('users_log_history_type','4')->first()) > 0 )
            {
                foreach(DB::table($client->database.'.SystemEvents')->where('detected','0')->limit(50000)->get() as $IPrecord){
                    //return $IPrecord->Message;
                    // get mac address
                    $value=explode('src-mac ',$IPrecord->Message);
                    if(isset($value[1])){// make sure this record is IP not URL
                        $value2=explode(',',$value[1]);
                        $macRecord=$value2[0];
                        // get connection type
                        $typeValue=explode('in:',$IPrecord->Message);
                        $typeValue2=explode(' ',$typeValue[1]);
                        if($typeValue2=="IN"){$type=2;}else{$type=1;}
                        // get protocol
                        $protocolValue=explode('proto ',$IPrecord->Message);
                        $protocolValue=explode(', ',$protocolValue[1]);
                        $protocol=$protocolValue[0];
                        if (strpos($protocolValue[1],")") !== false) {
                        // found
                        $protocol=$protocolValue[0].$protocolValue[1];
                        $protocolValue[1]=$protocolValue[2];
                        }
                        // get src address and port
                        $srcipTypeValue=explode('->',$protocolValue[1]);
                        $srcipTypeValue=explode(':',$srcipTypeValue[0]);
                        $src_ip=$srcipTypeValue[0];
                        if(isset($srcipTypeValue[1])){$src_port=$srcipTypeValue[1];}else{$src_port="";}
                        // get dst address and port
                        $dstipTypeValue=explode('->',$protocolValue[1]);
                        $dstipTypeValue=explode(':',$dstipTypeValue[1]);
                        $dst_ip=$dstipTypeValue[0];
                        if(isset($dstipTypeValue[1])){$dst_port=$dstipTypeValue[1];}else{$dst_port="";}

                        $radacct=DB::table($client->database.'.radacct')->where('callingstationid','like',"%$macRecord%")->orderBy('radacctid', 'desc')->first();
                        if(isset($radacct->u_id) and $radacct->u_id!=0){
                            DB::table($client->database.'.visited_ip')->insert(['u_id' => $radacct->u_id, 'mac' => $radacct->callingstationid
                            , 'type'=> $type
                            , 'src_ip'=> $src_ip
                            , 'src_port'=> $src_port
                            , 'dst_ip'=> $dst_ip
                            , 'dst_port'=> $dst_port
                            , 'protocol'=> $protocol
                            , 'created_at'=> $IPrecord->ReceivedAt]);
                        }
                        DB::table($client->database.'.SystemEvents')->where('ID', $IPrecord->ID)->delete();
                    }else{
                        // this record is not URL
                        // we will deactivate this record for next search
                        DB::table($client->database.'.SystemEvents')->where('ID', $IPrecord->ID)->update(['detected' => 1]);
                    }
                    
                    
                }
            }
        }//foreach(DB::table('customers')->where('state','1')->get() as $client)
        */
    }
} 