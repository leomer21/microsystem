<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Network;
use Input;
use DB;
use Auth;
use App;

class NetworkController extends Controller
{

    public function index()
    {
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['networks'] == 1){
            return view('back-end.network.network',array(
                'networks' => Network::all()
            ));
        }else{
            return view('errors.404');
        }
    }
    public function index2()
    {
        $permissions =  app('App\Http\Controllers\DashboardController')->permissions();
        if(Auth::user()->type == 1 && $permissions['networks'] == 1) {

            $firstDayMonth=date("Y-m")."-01";
            $lastDayMonth=date('Y-m-t', strtotime($firstDayMonth));

            $data = Network::all();
            foreach ($data as $key => $value) {
                $value->count_online = App\Models\RadacctActiveUsers::where('network_id', $value->id)->count();
                $value->count_users = App\Users::where('network_id', $value->id)->count();
                // get Monthly Usage
                $monthlyUsageUpload=App\Radacct::where('network_id',$value->id)->whereBetween('dates',[$firstDayMonth, $lastDayMonth])->sum('acctinputoctets');
                $monthlyUsageDownload=App\Radacct::where('network_id',$value->id)->whereBetween('dates',[$firstDayMonth, $lastDayMonth])->sum('acctoutputoctets');
                $monthlyTotalUsage=round(($monthlyUsageUpload+$monthlyUsageDownload)/1024/1024/1024,1)." GB";
                $value->monthly_usage=$monthlyTotalUsage;
                // Get Total Usage
                $usageUpload=App\Radacct::where('network_id',$value->id)->sum('acctinputoctets');
                $usageDownload=App\Radacct::where('network_id',$value->id)->sum('acctoutputoctets');
                $totalUsage=round(($usageUpload+$usageDownload)/1024/1024/1024,2)." GB";
                $value->total_usage=$totalUsage;
            }
            return array('aaData' => $data);
        }else{
            return view('errors.404');
        }
    }
    public function index3($id)
    {
        return array(Network::find($id));
    }

    public function Add_Network(Request $request)
    {
        $network = new Network();
        $network->name = ucfirst(strtolower($request['name']));
        $network->mode = $request['mode'];
        $network->c_type = $request['c_type'];
        $network->state = $request['state'];
        $network->r_type = $request['r_type'];
        $network->commercial = $request['commercial'];
        $network->notes = $request['notes'];
        //$network->open_system = $request['open_system'];
        //$network->system_name = $request['system_name'];
        //$network->back_to_trial = $request['back_trial'];
        // $network->register_state = $request['register_state'];
        // $network->autoDisableUsersIfNotDisabled = $request['autoDisableUsersIfNotDisabled'];
        // $network->charge_visa_state = $request['charge_visa_state'];
        // $network->user_tracking_account = $request['user_tracking_account'];
        // $network->sms_service_state = $request['sms_service_state'];
        // $network->change_pass_state = $request['change_pass_state'];
        // $network->update_profile_state = $request['update_profile_state'];
        // $network->update_user_region_status = $request['update_user_region_status'];
        // $network->personal_number = $request['personal_number'];
        // $network->mobile_card_pay = $request['mobile_card_pay'];
        // $network->mobile_card_history = $request['mobile_card_history'];
        // $network->stop_user_type = $request['stop_user_type'];
        // $network->stop_user_profile_id = $request['stop_user_profile_id'];
        // $network->company_system = $request['company_system'];
        // $network->show_package_monthly = $request['show_package_monthly'];
        // $network->show_package_validity = $request['show_package_validity'];
        // $network->show_package_sms = $request['show_package_sms'];
        // $network->show_package_bandwidth = $request['show_package_bandwidth'];
        // $network->show_package_period = $request['show_package_period'];
        // $network->show_package_offer = $request['show_package_offer'];
        $network->save();

        return redirect()->route('network');
    }
    public function Delete($id){

        $delete = Network::where('id',$id)->first();
        $delete->delete();

        return redirect()->route('network');
    }
    public function update($id)
    {
        $network = Network::find($id);
        $network->name = Input::get('name');
        $network->mode = Input::get('mode');
        $network->c_type = Input::get('c_type');
        $network->state = Input::get('state');
        $network->r_type = Input::get('r_type');
        $network->commercial = Input::get('commercial');
        $network->notes = Input::get('notes');
        //$network->open_system = Input::get('open_system');
        // $network->system_name = Input::get('system_name');
        // $network->back_to_trial = Input::get('back_trial');
        // $network->register_state = Input::get('register_state');
        // $network->autoDisableUsersIfNotDisabled = Input::get('autoDisableUsersIfNotDisabled');
        // $network->charge_visa_state = Input::get('charge_visa_state');
        // $network->charge_account_state = Input::get('charge_account_state');
        // $network->user_tracking_account = Input::get('user_tracking_account');
        // $network->sms_service_state = Input::get('sms_service_state');
        // $network->change_pass_state = Input::get('change_pass_state');
        // $network->update_profile_state = Input::get('update_profile_state');
        // $network->update_user_region_status = Input::get('update_user_region_status');
        // $network->personal_number = Input::get('personal_number');
        // $network->mobile_card_pay = Input::get('mobile_card_pay');
        // $network->mobile_card_history = Input::get('mobile_card_history');
        // $network->stop_user_type = Input::get('stop_user_type');
        // $network->stop_user_profile_id = Input::get('stop_user_profile_id');
        // $network->company_system = Input::get('company_system');
        // $network->show_package_monthly = Input::get('show_package_monthly');
        // $network->show_package_validity = Input::get('show_package_validity');
        // $network->show_package_sms = Input::get('show_package_sms');
        // $network->show_package_bandwidth = Input::get('show_package_bandwidth');
        // $network->show_package_period = Input::get('show_package_period');
        // $network->show_package_offer = Input::get('show_package_offer');
        $network->update();

        return redirect()->route('network');
    }

    public function state($id,$value){
        $value = ($value == 'true')? 1 : 0;
        Network::where('id', '=', $id)->update(['state'=>$value]);
    }

    public function getid($id){

        return view('back-end.network.edit',array(
            'network' => Network::find($id)
        ));
    }
}
