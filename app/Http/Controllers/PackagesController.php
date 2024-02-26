<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Packages;
use App\Network;
use App\Groups;
use Input;
use DB;
use Validator;
use Auth;
use App;

class PackagesController extends Controller
{

	public function Index(){
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['packages'] == 1){
            $groups = Groups::where('as_system','0')->get();
            return view('back-end.packages.index', ['networks' => Network::all(), 'groups' => $groups]);
        }else{
            return view('errors.404');
        }
    }
    public function Json(){
        $data = App\Models\Packages::get();

        foreach($data as $a){
            $network_id = $a->network_id;
            $a->network_id = App\Network::where('id',$network_id)->value('name');
        }

        return array('aaData'=>$data);
    }
    public function Add(){

        $Packages = new Packages();
        $Packages->name = Input::get('packagename');
        $Packages->type = Input::get('packagestype');
        $Packages->price = Input::get('packageprice');
        if(Input::get('packagestype') == 3){
            // convert time format ex.01:00:00 to seconds ex.120
            $str_time = Input::get('packageperiod'); //"01:00:00";
            $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
            sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
            $packageSeconds = $hours * 3600 + $minutes * 60 + $seconds;

            $Packages->period = $packageSeconds;
        }else{
            $Packages->period = Input::get('packageperiod2');
        }

        if(Input::get('extra')){
            $Packages->period = Input::get('extra');
        }else{}
        if(Input::get('expiration')) {
            $Packages->time_package_expiry = Input::get('expiration');
        }
        $Packages->network_id = Input::get('networkname');
        if(Input::get('groupname')) {
            $Packages->group_id = Input::get('groupname');
        }
        $Packages->notes = Input::get('notes');
        if(Input::get('state') == "true"){
            $Packages->state = 1;
        }else{ $Packages->state = 0; }

        if(Input::get('offer') == "true"){
            $Packages->offer = 1;
        }else{ $Packages->offer = 0; }

        $Packages->save();
        return "done";
    }
    public function getedit($id){
        $groups = Groups::where('as_system','0')->get();
        $packages = Packages::find($id);
        return view('back-end.packages.edit', ['networks' => Network::all(), 'groups' => $groups, 'packages' => $packages]);

    }
    public function Edit(){

        $ids = Input::get('id');
            $update = Packages::find($ids);
            $update->name = Input::get('packagename');
            $update->type = Input::get('packagestype');
            $update->price = Input::get('packageprice');
            
            if(Input::get('packagestype') == 3){
                // convert time format ex.01:00:00 to seconds ex.120
                $str_time = Input::get('packageperiod'); //"01:00:00";
                $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $packageSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                $update->period = $packageSeconds;
            }elseif ( Input::get('packagestype') == 4 and Input::get('extra') ){
                $update->period = Input::get('extra');
            }
            else{
                $update->period = Input::get('packageperiod2');
            }
            
                // return $update->period;
            $update->time_package_expiry = Input::get('expiration');
            $update->network_id = Input::get('networkname');
            $update->group_id = Input::get('groupname');

            $update->notes = Input::get('notes');
            if(Input::get('state') == "true"){
                $update->state = 1;
            }else{ $update->state = 0; }

            if(Input::get('offer') == "true"){
                $update->offer = 1;
            }else{ $update->offer = 0; }

            $update->update();
        return "done";
    }
    public function delete($id){

        Packages::where('id', $id)->delete();
    }
    public function state($id,$value){
        $value = ($value == 'true')? 1 : 0;
        Packages::where('id', '=', $id)->update(['state'=>$value]);
    }
}