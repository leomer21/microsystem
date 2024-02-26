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
use Excel;
use App\Radacct;
use App\Models\UsersRadacct;
use App\Models\Visitedsites;
use Response;
use View;
use Illuminate\Support\Facades\Log;
use Codecourse\Notify\Facades\Notify;
use Notification;
use Bpocallaghan\Notify\Facades\Notify as Notifyyy;


class TimelineController extends Controller
{	
	// user timeline
	public function timeline($id){

        $radacct = Radacct::where('u_id', $id)->orderBy('dates','desc')->get();

        //$total = App\Models\UsersRadacct::where('u_id', $id)->orderBy('radacctid', 'desc')->get();

        //$monthsFilterd = array();
        $daysCounter=0;
        $visits = 0;
        $totalQuotaConsumptionAllMonths = 0;

        foreach($radacct as $d){
            $curr_acctinputoctets=$d->acctinputoctets;
            $curr_acctoutputoctets=$d->acctoutputoctets;
            $curr_acctoutputoctets_total=$curr_acctinputoctets+$curr_acctoutputoctets;
            $curr_acctsessiontime=$d->acctsessiontime;
            $totalQuotaConsumptionAllMonths += $curr_acctoutputoctets_total;

            $curr_month= explode("-",$d->dates)[0]."-".explode("-",$d->dates)[1];
            $curr_day = explode("-",$d->dates)[0]."-".explode("-",$d->dates)[1]."-".explode("-",$d->dates)[2];

            if(isset($monthsFilterd[$curr_month])) {// that means this month was created before and we sum and calculate values to insert in array
                //$daysCounter++;
                $curr2_acctsessiontime+=$curr_acctsessiontime;
                $curr2_acctinputoctets+=$curr_acctinputoctets;
                $curr2_acctoutputoctets+=$curr_acctoutputoctets;
                $curr2_acctoutputoctets_total+=$curr_acctoutputoctets_total;

                //$curr2_acctsessiontime=$curr2_acctsessiontime/60/60;
                
                if(!isset($daysFilterd[$curr_month][$curr_day])){
                    $daysFilterd[$curr_month][$curr_day]=1;
                    $daysCounter=count($daysFilterd[$curr_month]);
                }else{
                    $daysFilterd[$curr_month][$curr_day]++;
                    $daysCounter=count($daysFilterd[$curr_month]);
                }
                
                $monthsFilterd[$curr_month] = array('monthname'=>$curr_month,'countDays' => $daysCounter,'sessions'=>$curr2_acctsessiontime,'upload'=>$curr2_acctinputoctets,'download'=>$curr2_acctoutputoctets,'total'=>$curr2_acctoutputoctets_total);

            }else{// New Month
                
                $daysFilterd[$curr_month][$curr_day]=1;
                $daysCounter=1;
                $curr2_acctsessiontime=$curr_acctsessiontime;
                $curr2_acctinputoctets=$curr_acctinputoctets;
                $curr2_acctoutputoctets=$curr_acctoutputoctets;
                $curr2_acctoutputoctets_total=$curr_acctoutputoctets_total;
                //$daysFilterd[$curr_day];
                //$daysCounter=count($daysFilterd);
                $monthsFilterd[$curr_month] = array('monthname'=>$curr_month,'countDays' => $daysCounter,'sessions'=>$curr2_acctsessiontime,'upload'=>$curr2_acctinputoctets,'download'=>$curr2_acctoutputoctets,'total'=>$curr2_acctoutputoctets_total);
            }
            
        }

        $cards = App\History::where('operation','Charged card')->where('u_id',$id)->get();

        $package = App\History::where('operation','user_charge_package')->where('u_id',$id)->get();

        $user_data = App\Users::where('u_id',$id)->first();
        if(isset($user_data)){
            $network_data = App\Network::where('id',$user_data->network_id)->first();
        }

        $network_commercial = App\Network::where('id',$user_data->network_id)->value('commercial');

        //return $monthsFilterd;
        if(!isset($monthsFilterd)){$monthsFilterd=array('notFoundAnyData'=>1);}
        if(!isset($radacct)){$radacct = "";}
        if(!isset($user_data)){$user_data = null;}
        if(!isset($network_data)){$network_data = null;}

        $visits = array_sum(array_column($monthsFilterd, 'countDays'));
        
        return view('back-end.user.timeline',array(
            'months' => $monthsFilterd,
            'radacct' => $radacct,
            'cards' => $cards,
            'packages' => $package,
            'u_id' => $id,

            'visits' => $visits,
            'totalQuotaConsumptionAllMonths' => $totalQuotaConsumptionAllMonths,
            'user_data' => $user_data,
            'network_commercial' => $network_commercial

        ));
    }

    //functional test done:)
	public function export_month_excel($id, $monthname){

        $type = "xlsx";

		$firstDayMonth = $monthname."-01";
        $lastDayMonth =date('Y-m-t', strtotime($firstDayMonth));

		$user_data =  UsersRadacct::where('u_id', $id)->whereBetween('dates', [$firstDayMonth, $lastDayMonth])->get();
		$daysCounter = 0;
		$curr2_acctsessiontime = 0;
		$curr2_acctinputoctets = 0;
		$curr2_acctoutputoctets = 0;
		$curr2_acctoutputoctets_total = 0;
        if(isset($user_data) && count($user_data) != 0){
    		foreach($user_data as $d){

    			$user_id=$d->u_id;
    			$name=$d->u_name;
    			$username = $d->u_uname;
    			$network_id = $d->network_id;
    			$group_id = $d->group_id;
    			$branch_id = $d->branch_id;


                $curr_acctinputoctets = $d->acctinputoctets;
                $curr_acctoutputoctets=$d->acctoutputoctets;
                $curr_acctoutputoctets_total=$curr_acctinputoctets+$curr_acctoutputoctets;
                $curr_acctsessiontime=$d->acctsessiontime;

                $curr= explode("-",$d->dates)[0]."-".explode("-",$d->dates)[1];
                $daysCounter++;
                $curr2_acctsessiontime+=$curr_acctsessiontime;
                $curr2_acctinputoctets+=$curr_acctinputoctets;
                $curr2_acctoutputoctets+=$curr_acctoutputoctets;
                $curr2_acctoutputoctets_total+=$curr_acctoutputoctets_total;



                //if upload = 0
                if($d->acctinputoctets == "" or !isset($d->acctinputoctets)) { $upload ="0"; } else { $upload = round($d->acctinputoctets /1024 /1024 /1024,1); } 
                //if download = 0
                if($d->acctoutputoctets == "" or !isset($d->acctoutputoctets)) {$download ="0";} else { $download = round($d->acctoutputoctets /1024 /1024 /1024,1); }
                //if total = 0
                if(($d->acctinputoctets == "" or !isset($d->acctinputoctets)) and ($d->acctoutputoctets == "" or !isset($d->acctoutputoctets))) {$total ="0";} else { $total = round(($d->acctinputoctets + $d->acctoutputoctets) /1024 /1024 /1024,1); }

                $days[$daysCounter] = [ 'Day' => $d->dates , 'Start at ' => \Carbon\Carbon::parse($d->acctstarttime)->format("h:i:s A"), 'Stop at ' => \Carbon\Carbon::parse($d->acctstoptime)->format("h:i:s A"),'Upload (GB)' => $upload, 'Download (GB)' => $download, 'Total (GB)' => $total];
                  
            }	
            
            //Upload
            if($curr2_acctinputoctets >= 1073741824){$upload = "Upload (GB)"; $curr2_acctinputoctets = round($curr2_acctinputoctets/1024 /1024 /1024,1); }
            elseif($curr2_acctinputoctets < 1073741824 and $curr2_acctinputoctets > 1048576){$upload = "Upload (MB)"; $curr2_acctinputoctets = round($curr2_acctinputoctets/1024 /1024,1);}
            else{ $upload = "Upload (KB)"; $curr2_acctinputoctets = round($curr2_acctinputoctets/1024 ,1);}
            //download
            if($curr2_acctoutputoctets >= 1073741824){$download = "Download (GB)"; $curr2_acctoutputoctets = round($curr2_acctoutputoctets/1024 /1024 /1024,1); }
            elseif($curr2_acctoutputoctets < 1073741824 and $curr2_acctoutputoctets > 1048576){$download = "Download (MB)"; $curr2_acctoutputoctets = Round($curr2_acctoutputoctets/1024 /1024,1);}
            else{ $download = "Download (KB)"; $curr2_acctoutputoctets = Round($curr2_acctoutputoctets/1024,1);}
            //total usage
            if($curr2_acctoutputoctets_total >= 1073741824){$total = "Total (GB)";  if($curr2_acctoutputoctets_total == "" or !isset($curr2_acctoutputoctets_total)) {$curr2_acctoutputoctets_total = "0"; } else {$curr2_acctoutputoctets_total = round($curr2_acctoutputoctets_total/1024 /1024 /1024,1); } }
            elseif($curr2_acctoutputoctets_total < 1073741824 and $curr2_acctoutputoctets_total > 1048576){$total = "Total (MB)"; if($curr2_acctoutputoctets_total == "" or !isset($curr2_acctoutputoctets_total)) {$curr2_acctoutputoctets_total = "0"; } else { $curr2_acctoutputoctets_total = Round($curr2_acctoutputoctets_total/1024 /1024,1);} }
            else{ $total = "Total (KB)"; if($curr2_acctoutputoctets_total == "" or !isset($curr2_acctoutputoctets_total)) {$curr2_acctoutputoctets_total = "0"; } else { $curr2_acctoutputoctets_total = Round($curr2_acctoutputoctets_total/1024,1); } }
        
    		$monthsFilterd[$curr] = array('User id' => $user_id, 'name' => $name, 'username' => $username,'Month'=>$curr,'Days count' => $daysCounter,'Total Sessions time'=> gmdate("H:i:s",$curr2_acctsessiontime), $upload =>$curr2_acctinputoctets, $download =>$curr2_acctoutputoctets, $total =>$curr2_acctoutputoctets_total , 'Network' => App\Network::where('id', $network_id)->value('name') , 'Branch' => App\Branches::where('id', $branch_id)->value('name'), 'Group' => App\Groups::where('id', $group_id)->value('name'));
           	
    		$user_name = App\Users::where('u_id', $id)->value('u_name');
    		

    		return Excel::create($user_name.'-'.$monthname, function($excel) use ($monthsFilterd, $days, $user_name, $curr) {

                // Set the title
                $excel->setTitle($user_name.' report '.$curr);

                // Chain the setters
                $excel->setCreator('Microsystem');

                $excel->setDescription('Monthly report');

                $excel->sheet('Sheet', function($sheet) use ($monthsFilterd, $days)
                {
                    // Set black background
                    $sheet->cells(1, function($row) {

                        // call cell manipulation methods
                        $row->setBackground('#fac305');

                    });
                    // Set black background
                    $sheet->cells(3, function($row) {

                        // call cell manipulation methods
                        $row->setBackground('#05cbfc');

                    });
                    $sheet->fromArray($monthsFilterd);
                    $sheet->fromArray($days);
                });

            })->download($type); Redirect::back();
        }else{
            return redirect()->route('activeusers',['status' =>'0']);
        }
	}

    //functional test done :)
    public function export_month_log_excel(Request $request, $id, $monthname){

    	$type = "xlsx";

    	$firstDayMonth = $monthname."-01";
        $lastDayMonth =date('Y-m-t', strtotime($firstDayMonth));
       	$user_data =  Visitedsites::where('u_id', $id)->whereBetween('ReceivedAt', [$firstDayMonth, $lastDayMonth])->orderBy('radacctid', 'desc')->limit(7000)->get();
       	if(isset($user_data) && count($user_data) != 0){
                $user = App\Users::where('u_id', $id)->first();
                $user_name = $user->u_name;
                $network_id = $user->network_id;
                $group_id = $user->group_id;
                $branch_id = $user->branch_id;
                $urlFilterType=App\Branches::where('id', $branch_id)->value('users_log_history_type');
                if(isset($urlFilterType) and $urlFilterType=="1"){$urlFilterType="URL";}
                else{$urlFilterType="IP";}

                // insert all records
                $logsCounter = 0;
                if($urlFilterType=="URL"){
                    // excel format will show as URL
                    foreach ($user_data as $value) {
                        //Method cached
                        if(explode(' ', $value->Message)[1] == "cached"){

                        }else{  
                            $logs[$logsCounter] = ['Visit Time' =>  $value->ReceivedAt, 'Method' => explode(' ', $value->Message)[1], 'Destination' => explode(' ', $value->Message)[2], 'User IP' => $value->framedipaddress,  'Session start' => $value->acctstarttime, 'Session end' => $value->acctstoptime];
                            $logsCounter++;
                        } 
                    }
                }else{
                   // excel format will show as IP
                    foreach ($user_data as $value) {

                            // get mac address
                            $macValue=explode('src-mac ',$value->Message);
                            $macValue=explode(',',$macValue[1]);
                            $macRecord=$macValue[0];
                            // get connection type
                            $typeValue=explode('in:',$value->Message);
                            $typeValue2=explode(' ',$typeValue[1]);
                            if($typeValue2=="IN"){$connType="Inbound";}else{$connType="Outgoing";}
                            // get protocol
                            $protocolValue=explode('proto ',$value->Message);
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
                        
                            $logs[$logsCounter] = ['No of Visits' =>  $value->visits_count,'First Visit' =>  $value->ReceivedAt,'Last Visit' =>  $value->last_visit, 'MacAddress' => $macRecord, 'Connection Type' => $connType, 'Protocol' => $protocol,  'Src. Address' => $src_ip,  'Src. Port' => $src_port,  'Dst. Address' => $dst_ip,  'Dst. Port' => $dst_port,  'Session started At' => $value->acctstarttime, 'Session ended At' => $value->acctstoptime];
                            $logsCounter++;
                        
                    }
                }
                $destination[$monthname] = ['User ID' => $id, 'Username' => $user_name,'Destination count' => $logsCounter, 'Network' => App\Network::where('id', $network_id)->value('name') ,'Branch' => App\Branches::where('id', $branch_id)->value('name'), 'Group' => App\Groups::where('id', $group_id)->value('name')];
                
                
                return Excel::create($user_name.'-'.$monthname, function($excel) use ($destination, $logs, $user_name, $monthname) {

                    // Set the title
                    $excel->setTitle($user_name.' report '.$monthname);

                    // Chain the setters
                    $excel->setCreator('Microsystem');

                    $excel->setDescription('Monthly report');

                    $excel->sheet('Sheet', function($sheets) use ($destination, $logs)
                    {
                        // Set black background
                        $sheets->cells(1, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#fac305');

                        });
                        // Set black background
                        $sheets->cells(3, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#05cbfc');

                        });
                        $sheets->fromArray($destination);
                        $sheets->fromArray($logs);
                    });
                })->download($type); Redirect::back();  
        }else{   
                             
                return redirect()->route('activeusers',['status' =>'0']);
        }
       
    }

    //functional test done :)
	public function export_day_excel($id, $day){
        
        $type = "xlsx";

        $user = App\Users::where('u_id', $id)->first();
		$user_name = $user->u_uname;
        $name = $user->u_name;

        $day_sessions = Radacct::where('u_id', $id)->where('dates', $day)->get();
        $sessions_counter = 0;
        $curr2_acctsessiontime = 0;
        $curr2_acctinputoctets = 0;
        $curr2_acctoutputoctets = 0;
        $curr2_acctoutputoctets_total = 0;
        if(isset($day_sessions) && count($day_sessions) != 0){
            foreach($day_sessions as $d){

                $user_id=$d->u_id;
                $network_id = $d->network_id;
                $group_id = $d->group_id;
                $branch_id = $d->branch_id;
                $day = $d->dates;

                $curr_acctinputoctets = $d->acctinputoctets;
                $curr_acctoutputoctets=$d->acctoutputoctets;
                $curr_acctoutputoctets_total=$curr_acctinputoctets+$curr_acctoutputoctets;
                $curr_acctsessiontime=$d->acctsessiontime;

                $curr= explode("-",$d->dates)[0]."-".explode("-",$d->dates)[1];
                $sessions_counter++;
                $curr2_acctsessiontime+=$curr_acctsessiontime;
                $curr2_acctinputoctets+=$curr_acctinputoctets;
                $curr2_acctoutputoctets+=$curr_acctoutputoctets;
                $curr2_acctoutputoctets_total+=$curr_acctoutputoctets_total;
                

                //if upload = 0
                if($d->acctinputoctets == "" or !isset($d->acctinputoctets)) { $upload ="0"; } else { $upload = round($d->acctinputoctets /1024 /1024,1); } 
                //if download = 0
                if($d->acctoutputoctets == "" or !isset($d->acctoutputoctets)) {$download ="0";} else { $download = round($d->acctoutputoctets /1024 /1024,1); }
                //if total = 0
                if(($d->acctinputoctets == "" or !isset($d->acctinputoctets)) and ($d->acctoutputoctets == "" or !isset($d->acctoutputoctets))) {$total ="0";} else { $total = round(($d->acctinputoctets + $d->acctoutputoctets) /1024 /1024,1); }
                

                $days[$sessions_counter] = [ 'Session time' => gmdate("H:i:s",$d->acctsessiontime) , 'Start at ' => \Carbon\Carbon::parse($d->acctstarttime)->format("h:i:s A"), 'Stop at ' => \Carbon\Carbon::parse($d->acctstoptime)->format("h:i:s A"),'Upload (MB)' => $upload, 'Download (MB)' => $download, 'Total (MB)' => $total];
                               
            }

            //Upload
            if($curr2_acctinputoctets >= 1073741824){$upload = "Upload (GB)"; $curr2_acctinputoctets = round($curr2_acctinputoctets/1024 /1024 /1024,1); }
            elseif($curr2_acctinputoctets < 1073741824 and $curr2_acctinputoctets > 1048576){$upload = "Upload (MB)"; $curr2_acctinputoctets = round($curr2_acctinputoctets/1024 /1024,1);}
            else{ $upload = "Upload (KB)"; $curr2_acctinputoctets = round($curr2_acctinputoctets/1024 ,1);}
            //download
            if($curr2_acctoutputoctets >= 1073741824){$download = "Download (GB)"; $curr2_acctoutputoctets = round($curr2_acctoutputoctets/1024 /1024 /1024,1); }
            elseif($curr2_acctoutputoctets < 1073741824 and $curr2_acctoutputoctets > 1048576){$download = "Download (MB)"; $curr2_acctoutputoctets = Round($curr2_acctoutputoctets/1024 /1024,1);}
            else{ $download = "Download (KB)"; $curr2_acctoutputoctets = Round($curr2_acctoutputoctets/1024,1);}
            //total usage
            if($curr2_acctoutputoctets_total >= 1073741824){$total = "Total (GB)";  if($curr2_acctoutputoctets_total == "" or !isset($curr2_acctoutputoctets_total)) {$curr2_acctoutputoctets_total = "0"; } else {$curr2_acctoutputoctets_total = round($curr2_acctoutputoctets_total/1024 /1024 /1024,1); } }
            elseif($curr2_acctoutputoctets_total < 1073741824 and $curr2_acctoutputoctets_total > 1048576){$total = "Total (MB)"; if($curr2_acctoutputoctets_total == "" or !isset($curr2_acctoutputoctets_total)) {$curr2_acctoutputoctets_total = "0"; } else { $curr2_acctoutputoctets_total = Round($curr2_acctoutputoctets_total/1024 /1024,1);} }
            else{ $total = "Total (KB)"; if($curr2_acctoutputoctets_total == "" or !isset($curr2_acctoutputoctets_total)) {$curr2_acctoutputoctets_total = "0"; } else { $curr2_acctoutputoctets_total = Round($curr2_acctoutputoctets_total/1024,1); } }


            $sessions[$curr] = array('User id' => $user_id, 'name' => $name, 'username' => $user_name,'day'=>$day,'Sessions count' => $sessions_counter, $upload => $curr2_acctinputoctets, $download => $curr2_acctoutputoctets, $total => $curr2_acctoutputoctets_total, 'Network' => App\Network::where('id', $network_id)->value('name') , 'Branch' => App\Branches::where('id', $branch_id)->value('name'), 'Group' => App\Groups::where('id', $group_id)->value('name'));

            return  Excel::create($name.'-'.$day, function($excel) use ($sessions, $name, $day, $days) {

                // Set the title
                $excel->setTitle($name.' report '.$day);

                // Chain the setters
                $excel->setCreator('Microsystem');

                $excel->setDescription('Day sessions');

                $excel->sheet('Sheet', function($sheet) use ($sessions, $days)
                {
                    // Set black background
                    $sheet->cells(1, function($row) {

                        // call cell manipulation methods
                        $row->setBackground('#fac305');

                    });
                    // Set black background
                    $sheet->cells(3, function($row) {

                        // call cell manipulation methods
                        $row->setBackground('#05cbfc');

                    });
                    $sheet->fromArray($sessions);
                    $sheet->fromArray($days);
                });

            })->download($type); Redirect::back();

        }else{
            return redirect()->route('activeusers',['status' =>'0']);
        }       
    }

    //functional test done :)
    public function export_day_log_excel($id, $day){

        $type = "xlsx";
        
        //$day = "2017-01-6";

        $start = $day.' 00:00:00';
        $end = $day.' 23:59:59';

    	$user_data =  Visitedsites::where('u_id', $id)->whereBetween('ReceivedAt', [$start, $end])->orderBy('radacctid', 'desc')->limit(7000)->get();
        if(isset($user_data) and count($user_data) != 0){
                $user = App\Users::where('u_id', $id)->first();
                $user_name = $user->u_name;
                $network_id = $user->network_id;
                $group_id = $user->group_id;
                $branch_id = $user->branch_id;
                $urlFilterType=App\Branches::where('id', $branch_id)->value('users_log_history_type');
                if(isset($urlFilterType) and $urlFilterType=="1"){$urlFilterType="URL";}
                else{$urlFilterType="IP";}

                $logsCounter = 0;
                if($urlFilterType=="URL"){
                    foreach ($user_data as $value) {

                        //Method cached
                        if(explode(' ', $value->Message)[1] == "cached"){

                        }else{  
                            $sessions[$logsCounter] = ['Visit Time' =>  $value->ReceivedAt, 'Method' => explode(' ', $value->Message)[1], 'Destination' => explode(' ', $value->Message)[2], 'User IP' => $value->framedipaddress,  'Session start' => $value->acctstarttime, 'Session end' => $value->acctstoptime];
                            $logsCounter++;
                        } 
                    }
                }else{
                    // excel format will show as IP
                    foreach ($user_data as $value) {

                        // get mac address
                        $macValue=explode('src-mac ',$value->Message);
                        $macValue=explode(',',$macValue[1]);
                        $macRecord=$macValue[0];
                        // get connection type
                        $typeValue=explode('in:',$value->Message);
                        $typeValue2=explode(' ',$typeValue[1]);
                        if($typeValue2=="IN"){$connType="Inbound";}else{$connType="Outgoing";}
                        // get protocol
                        $protocolValue=explode('proto ',$value->Message);
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
                    
                        $sessions[$logsCounter] = ['No of Visits' =>  $value->visits_count,'First Visit' =>  $value->ReceivedAt,'Last Visit' =>  $value->last_visit, 'MacAddress' => $macRecord, 'Connection Type' => $connType, 'Protocol' => $protocol,  'Src. Address' => $src_ip,  'Src. Port' => $src_port,  'Dst. Address' => $dst_ip,  'Dst. Port' => $dst_port,  'Session started At' => $value->acctstarttime, 'Session ended At' => $value->acctstoptime];
                        $logsCounter++;
                        
                    }
                }

                $destination[$day] = ['User ID' => $id, 'Username' => $user_name,'Destination count' => $logsCounter, 'Network' => App\Network::where('id', $network_id)->value('name') ,'Branch' => App\Branches::where('id', $branch_id)->value('name'), 'Group' => App\Groups::where('id', $group_id)->value('name')];
                
                
                return Excel::create($user_name.'-'.$day, function($excel) use ($destination, $sessions, $user_name, $day) {

                    // Set the title
                    $excel->setTitle($user_name.' report '.$day);

                    // Chain the setters
                    $excel->setCreator('Microsystem');

                    $excel->setDescription('Monthly report');

                    $excel->sheet('Sheet', function($sheets) use ($destination, $sessions)
                    {
                        // Set black background
                        $sheets->cells(1, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#fac305');

                        });
                        // Set black background
                        $sheets->cells(3, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#05cbfc');

                        });
                        $sheets->fromArray($destination);
                        $sheets->fromArray($sessions);
                    });
                })->download($type); Redirect::back();  
        }else{
                //return redirect()->route('activeusers',['status' =>'0']);
                return back()->withInput(['status' =>'0']);
        }
    }

    // view Visited URL / IP in model
    public function destinations_month_list($id, $monthname){
        
        $firstDayMonth = $monthname."-01";
        $lastDayMonth =date('Y-m-t', strtotime($firstDayMonth));
    
        $destination = Visitedsites::where('u_id', $id)->whereBetween('ReceivedAt', [$firstDayMonth, $lastDayMonth])->orderBy('radacctid', 'desc')->limit(2000)->get(); 
        $branchID=App\Users::where('u_id', $id)->value('branch_id');
        $urlFilterType=App\Branches::where('id', $branchID)->value('users_log_history_type');
        if(isset($urlFilterType) and $urlFilterType=="1"){$urlFilterType="URL";}
        else{$urlFilterType="IP";}

    	return view('back-end.timeline.destination', [ 'destinations' => $destination, 'urlFilterType'=>$urlFilterType]);
    }

    public function destinations_day_list($id, $day){

        $start = $day.' 00:00:00';
        $end = $day.' 23:59:59';

        $destination = Visitedsites::where('u_id', $id)->whereBetween('ReceivedAt', [$start, $end])->orderBy('radacctid', 'desc')->limit(2000)->get();
        $branchID=App\Users::where('u_id', $id)->value('branch_id');
        $urlFilterType=App\Branches::where('id', $branchID)->value('users_log_history_type');
        if(isset($urlFilterType) and $urlFilterType=="1"){$urlFilterType="URL";}
        else{$urlFilterType="IP";}

        return view('back-end.timeline.destination', [ 'destinations' => $destination, 'urlFilterType'=>$urlFilterType]);
    }

    public function download_modal($id, $type){ 
               
        if($type == "networks"){
            $network =  Visitedsites::where('network_id', $id)->orderBy('radacctid', 'desc')->limit(1)->get();
            
            if(isset($network) && count($network) !== 0){
                return view('back-end.timeline.download', ['id' => $id, 'type' => $type]);
            }
            else{    
                return view('back-end.timeline.download', ['error' => '1', 'type' => $type]);
            }
        }elseif($type == "groups"){
            $group =  Visitedsites::where('group_id', $id)->orderBy('radacctid', 'desc')->limit(1)->get();
            
            if(isset($group) && count($group) !== 0){
                return view('back-end.timeline.download', ['id' => $id, 'type' => $type]);
            }
            else{    
                return view('back-end.timeline.download', ['error' => '1', 'type' => $type]);
            }
        }elseif($type == "branches"){
            $brach =  Visitedsites::where('branch_id', $id)->orderBy('radacctid', 'desc')->limit(1)->get();
            
            if(isset($brach) && count($brach) !== 0){
                return view('back-end.timeline.download', ['id' => $id, 'type' => $type]);
            }
            else{    
                return view('back-end.timeline.download', ['error' => '1', 'type' => $type]);
            }
        }
    }

    public function network_destinations(Request $request, $id, $type){

        $network_data =  Visitedsites::where('network_id', $id)->orderBy('radacctid', 'desc')->limit(7000)->get();
        $urlFilterType=App\Branches::whereNotNull('users_log_history_type')->value('users_log_history_type');
        if(isset($urlFilterType) and $urlFilterType=="1"){$urlFilterType="URL";}
        else{$urlFilterType="IP";}

        if(isset($network_data) && count($network_data) != 0){
                $logsCounter = 0;
                if($urlFilterType=="URL"){
                    foreach ($network_data as $value) {

                        //Method cached
                        if(explode(' ', $value->Message)[1] == "cached"){

                        }else{  
                            $logs[$logsCounter] = ['Visit Time' =>  $value->ReceivedAt, 'Method' => explode(' ', $value->Message)[1], 'Destination' => explode(' ', $value->Message)[2], 'User IP' => $value->framedipaddress,  'Session start' => $value->acctstarttime, 'Session end' => $value->acctstoptime];
                            $logsCounter++;
                        } 
                    }                
                }else{
                    $allUsersArray[0]=0;
                    foreach ($network_data as $value) {
                        
                        // get mac address
                        $macValue=explode('src-mac ',$value->Message);
                        $macValue=explode(',',$macValue[1]);
                        $macRecord=$macValue[0];
                        // get connection type
                        $typeValue=explode('in:',$value->Message);
                        $typeValue2=explode(' ',$typeValue[1]);
                        if($typeValue2=="IN"){$connType="Inbound";}else{$connType="Outgoing";}
                        // get protocol
                        $protocolValue=explode('proto ',$value->Message);
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
                        //get user date
                        if(!isset($allUsersArray[$value->u_id])){
                            $userDetails=App\Users::where('u_id',$value->u_id)->first();
                            $allUsersArray[$value->u_id]=['Name'=> $userDetails->u_name,'Username'=> $userDetails->u_uname,'Mobile'=>$userDetails->u_phone];
                        }
                        $logs[$logsCounter] = ['Name' =>  $allUsersArray[$value->u_id]['Name'],'Username' =>  $allUsersArray[$value->u_id]['Username'],'Mobile' => $allUsersArray[$value->u_id]['Mobile'],'Visit Time' =>  $value->ReceivedAt, 'MacAddress' => $macRecord, 'Connection Type' => $connType, 'Protocol' => $protocol,  'Src. Address' => $src_ip,  'Src. Port' => $src_port,  'Dst. Address' => $dst_ip,  'Dst. Port' => $dst_port,  'Session started At' => $value->acctstarttime, 'Session ended At' => $value->acctstoptime];

                        $logsCounter++;
                        
                    }              
                }
                $network_name = App\Network::where('id', $id)->value('name');

                return Excel::create($network_name, function($excel) use ($logs, $network_name) {

                    // Set the title
                    $excel->setTitle($network_name.' report ');

                    // Chain the setters
                    $excel->setCreator('Microsystem');

                    $excel->setDescription('Monthly report');

                    $excel->sheet('Sheet', function($sheets) use ($logs)
                    {
                        // Set black background
                        $sheets->cells(1, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#fac305');

                        });
                        $sheets->fromArray($logs);
                    });
                })->download($type); Redirect::back();  
        }else{   
                             
                return redirect()->route('network',['status' =>'0']);
        }       
    }

    public function group_destinations(Request $request, $id, $type){

        $group_data =  Visitedsites::where('group_id', $id)->orderBy('radacctid', 'desc')->limit(7000)->get();
        $urlFilterType=App\Branches::whereNotNull('users_log_history_type')->value('users_log_history_type');
        if(isset($urlFilterType) and $urlFilterType=="1"){$urlFilterType="URL";}
        else{$urlFilterType="IP";}

        if(isset($group_data) && count($group_data) != 0){
                $logsCounter = 0;
                if($urlFilterType=="URL"){
                    foreach ($group_data as $value) {

                        //Method cached
                        if(explode(' ', $value->Message)[1] == "cached"){

                        }else{  
                            $logs[$logsCounter] = ['Visit Time' =>  $value->ReceivedAt, 'Method' => explode(' ', $value->Message)[1], 'Destination' => explode(' ', $value->Message)[2], 'User IP' => $value->framedipaddress,  'Session start' => $value->acctstarttime, 'Session end' => $value->acctstoptime];
                            $logsCounter++;
                        } 
                    }
                }else{
                    $allUsersArray[0]=0;
                    foreach ($group_data as $value) {
                        // get mac address
                        $macValue=explode('src-mac ',$value->Message);
                        $macValue=explode(',',$macValue[1]);
                        $macRecord=$macValue[0];
                        // get connection type
                        $typeValue=explode('in:',$value->Message);
                        $typeValue2=explode(' ',$typeValue[1]);
                        if($typeValue2=="IN"){$connType="Inbound";}else{$connType="Outgoing";}
                        // get protocol
                        $protocolValue=explode('proto ',$value->Message);
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
                        //get user date
                        if(!isset($allUsersArray[$value->u_id])){
                            $userDetails=App\Users::where('u_id',$value->u_id)->first();
                            $allUsersArray[$value->u_id]=['Name'=> $userDetails->u_name,'Username'=> $userDetails->u_uname,'Mobile'=>$userDetails->u_phone];
                        }
                        $logs[$logsCounter] = ['Name' =>  $allUsersArray[$value->u_id]['Name'],'Username' =>  $allUsersArray[$value->u_id]['Username'],'Mobile' => $allUsersArray[$value->u_id]['Mobile'],'No of Visits' =>  $value->visits_count,'First Visit' =>  $value->ReceivedAt,'Last Visit' =>  $value->last_visit, 'MacAddress' => $macRecord, 'Connection Type' => $connType, 'Protocol' => $protocol,  'Src. Address' => $src_ip,  'Src. Port' => $src_port,  'Dst. Address' => $dst_ip,  'Dst. Port' => $dst_port,  'Session started At' => $value->acctstarttime, 'Session ended At' => $value->acctstoptime];

                        $logsCounter++;
                    }
                     
                }
                $group_name = App\Groups::where('id', $id)->value('name');

                return Excel::create($group_name, function($excel) use ($logs, $group_name) {

                    // Set the title
                    $excel->setTitle($group_name.' report ');

                    // Chain the setters
                    $excel->setCreator('Microsystem');

                    $excel->setDescription('Monthly report');

                    $excel->sheet('Sheet', function($sheets) use ($logs)
                    {
                        // Set black background
                        $sheets->cells(1, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#fac305');

                        });
                        $sheets->fromArray($logs);
                    });
                })->download($type); Redirect::back();  
        }else{   
                             
                return redirect()->route('group',['status' =>'0']);
        }       
    }

    public function branch_destinations(Request $request, $id, $type){

        $branch_data =  Visitedsites::where('branch_id', $id)->orderBy('radacctid', 'desc')->limit(7000)->get();
        $urlFilterType=App\Branches::where('id',$id)->value('users_log_history_type');
        if(isset($urlFilterType) and $urlFilterType=="1"){$urlFilterType="URL";}
        else{$urlFilterType="IP";}

        if(isset($branch_data) && count($branch_data) != 0){
                $logsCounter = 0;
                if($urlFilterType=="URL"){
                    foreach ($branch_data as $value) {

                        //Method cached
                        if(explode(' ', $value->Message)[1] == "cached"){

                        }else{  
                            $logs[$logsCounter] = ['Visit Time' =>  $value->ReceivedAt, 'Method' => explode(' ', $value->Message)[1], 'Destination' => explode(' ', $value->Message)[2], 'User IP' => $value->framedipaddress,  'Session start' => $value->acctstarttime, 'Session end' => $value->acctstoptime];
                            $logsCounter++;
                        } 
                    }
                }else{
                    $allUsersArray[0]=0;
                   foreach ($branch_data as $value) {
 
                        // get mac address
                        $macValue=explode('src-mac ',$value->Message);
                        $macValue=explode(',',$macValue[1]);
                        $macRecord=$macValue[0];
                        // get connection type
                        $typeValue=explode('in:',$value->Message);
                        $typeValue2=explode(' ',$typeValue[1]);
                        if($typeValue2=="IN"){$connType="Inbound";}else{$connType="Outgoing";}
                        // get protocol
                        $protocolValue=explode('proto ',$value->Message);
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
                        //get user date
                        if(!isset($allUsersArray[$value->u_id])){
                            $userDetails=App\Users::where('u_id',$value->u_id)->first();
                            $allUsersArray[$value->u_id]=['Name'=> $userDetails->u_name,'Username'=> $userDetails->u_uname,'Mobile'=>$userDetails->u_phone];
                        }
                        $logs[$logsCounter] = ['Name' =>  $allUsersArray[$value->u_id]['Name'],'Username' =>  $allUsersArray[$value->u_id]['Username'],'Mobile' => $allUsersArray[$value->u_id]['Mobile'],'No of Visits' =>  $value->visits_count,'First Visit' =>  $value->ReceivedAt,'Last Visit' =>  $value->last_visit, 'MacAddress' => $macRecord, 'Connection Type' => $connType, 'Protocol' => $protocol,  'Src. Address' => $src_ip,  'Src. Port' => $src_port,  'Dst. Address' => $dst_ip,  'Dst. Port' => $dst_port,  'Session started At' => $value->acctstarttime, 'Session ended At' => $value->acctstoptime];

                        $logsCounter++;
                         
                    } 
                }
                $branch_name = App\Branches::where('id', $id)->value('name');

                return Excel::create($branch_name, function($excel) use ($logs, $branch_name) {

                    // Set the title
                    $excel->setTitle($branch_name.' report ');

                    // Chain the setters
                    $excel->setCreator('Microsystem');

                    $excel->setDescription('Monthly report');

                    $excel->sheet('Sheet', function($sheets) use ($logs)
                    {
                        // Set black background
                        $sheets->cells(1, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#fac305');

                        });
                        $sheets->fromArray($logs);
                    });
                })->download($type); Redirect::back();  
        }else{   
                             
                return redirect()->route('group',['status' =>'0']);
        }       
    }
    // network or group or branch timeline
    public function modal_timeline($id, $type){
        
        if($type == "networks"){
            $Query =  App\Models\RadacctNetworkMonthes::where('network_id', $id)->groupBy('month')->orderBy('month','desc')->get();
        }elseif($type == "groups"){
            $Query =  App\Models\RadacctGroupMonthes::where('group_id', $id)->groupBy('month')->orderBy('month','desc')->get();
        }elseif ($type == "branchs") {
            $Query =  App\Models\RadacctBranchMonthes::where('branch_id', $id)->groupBy('month')->orderBy('month','desc')->get();
        } 


        if(isset($Query) && count($Query) !== 0){

            return view('back-end.timeline.timeline', ['id' => $id, 'type' => $type, 'months' => $Query]);
        }
        else{    
            return view('back-end.timeline.timeline', ['error' => '1', 'type' => $type]);
        }          

    }
    // network or group or branch export timeline
    public function export_timeline($id, $month, $type){

          if($type == "networks"){
            $query_months =  App\Models\RadacctNetworkMonthes::where(['network_id' => $id, 'month' => $month])->groupBy('month')->orderBy('month','desc')->first();
              $query_days =  App\Models\RadacctNetworkDays::where(['network_id'=> $id, 'month' => $month ])->orderBy('day','ASC')->get();

              $name = App\Network::where('id', $id)->value('name');
              $route = "network";
          }elseif($type == "groups"){
              $query_months =  App\Models\RadacctGroupMonthes::where(['group_id' => $id, 'month' => $month])->groupBy('month')->orderBy('month','desc')->first();
              $query_days =  App\Models\RadacctGroupDays::where(['group_id'=> $id, 'month' => $month])->orderBy('day','ASC')->get();

              $name = App\Groups::where('id', $id)->value('name');
              $route = "group";   
          }elseif ($type == "branches") {
              $query_months =  App\Models\RadacctBranchMonthes::where(['branch_id' => $id, 'month' => $month])->groupBy('month')->orderBy('month','desc')->first();
              $query_days =  App\Models\RadacctBranchDays::where(['branch_id' => $id, 'month' => $month])->orderBy('day','ASC')->get();

              $name = App\Branches::where('id', $id)->value('name');
              $route = "branches";
          }
        if((isset($query_months) && count($query_months) != 0) && (isset($query_days) && count($query_days) != 0)){

                foreach ($query_days as  $value) {
                    //if upload = 0
                    if($value->acctinputoctets == "" or !isset($value->acctinputoctets)) { $upload ="0"; } else { $upload = round($value->acctinputoctets /1024 /1024 /1024,1); if(!$upload or !isset($upload) or $upload==0){$upload="0";}} 
                    //if download = 0
                    if($value->acctoutputoctets == "" or !isset($value->acctoutputoctets)) {$download ="0";} else { $download = round($value->acctoutputoctets /1024 /1024 /1024,1); if(!$download or !isset($download) or $download==0){$download="0";}}
                    //if total = 0
                    if(($value->acctinputoctets == "" or !isset($value->acctinputoctets)) and ($value->acctoutputoctets == "" or !isset($value->acctoutputoctets))) {$total ="0";} else { $total = round(($value->acctinputoctets + $value->acctoutputoctets) /1024 /1024 /1024,1); if(!$total or !isset($total) or $total==0){$total="0";} }

                  $days[$value->dates] = ['Day' => $value->dates, 'Month' => $value->month, 'Upload (GB)' => $upload, 'Download (GB)' => $download, 'Total (GB)' => $total];
                
                }
                //if upload = 0
                if($query_months->acctinputoctets == "" or !isset($query_months->acctinputoctets)) { $upload ="0"; } else { $upload = round($query_months->acctinputoctets /1024 /1024 /1024,1); if(!$upload or !isset($upload) or $upload==0){$upload="0";}} 
                //if download = 0
                if($query_months->acctoutputoctets == "" or !isset($query_months->acctoutputoctets)) {$download ="0";} else { $download = round($query_months->acctoutputoctets /1024 /1024 /1024,1); if(!$download or !isset($download) or $download==0){$download="0";}}
                //if total = 0
                if(($query_months->acctinputoctets == "" or !isset($query_months->acctinputoctets)) and ($query_months->acctoutputoctets == "" or !isset($query_months->acctoutputoctets))) {$total ="0";} else { $total = round(($query_months->acctinputoctets + $query_months->acctoutputoctets) /1024 /1024 /1024,1); if(!$total or !isset($total) or $total==0){$total="0";}}

                $months[$query_months->month] = ['Name' => $name,'Month' => $query_months->month, 'Upload (GB)' => $upload, 'Download (GB)' => $download, 'Total (GB)' => $total, 'Total Sessions time'=> gmdate("H:i:s", $query_months->acctsessiontime) ];


                return Excel::create($name, function($excel) use ($months, $days, $name) {

                    // Set the title
                    $excel->setTitle($name.' report ');

                    // Chain the setters
                    $excel->setCreator('Microsystem');

                    $excel->setDescription('Monthly report');

                    $excel->sheet('Sheet', function($sheets) use ($months, $days)
                    {
                        // Set black background
                        $sheets->cells(1, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#fac305');

                        });
                        // Set black background
                        $sheets->cells(3, function($row) {

                            // call cell manipulation methods
                            $row->setBackground('#fac305');

                        });
                        $sheets->fromArray($months);
                        $sheets->fromArray($days);
                    });
                })->download('xlsx'); Redirect::back();  
        }else{         
                return redirect()->route($route,['status' =>'0']);
        }   

    }
    
}