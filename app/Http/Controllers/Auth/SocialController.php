<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use App\Users;
use App\Network;
use App\Branches;
use App\Groups;
use App\Settings;
use DB;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request as Request;
use Laravel\Socialite\Facades\Socialite;
use Laracasts\Flash\Flash;

use App;
class SocialController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    private $network = ['facebook', 'google', 'twitter', 'linkedin'];

    public function __construct(HttpRequest $request)
    {
        //parent::__construct($request);

        $this->middleware('guest');
        $subdomain = url()->full();
        $split = explode('/', $subdomain);
        //Facebook
        config(['services.facebook.client_id' => Settings::where('type', 'facebook_client_id')->value('value')]);
        config(['services.facebook.client_secret' => Settings::where('type', 'facebook_client_secret')->value('value')]);
        config(['services.facebook.redirect' => 'http://'.$split[2].'/auth/facebook/callback']);

        //Google
        config(['services.google.client_id' => Settings::where('type', 'google_client_id')->value('value')]);
        config(['services.google.client_secret' => Settings::where('type', 'google_client_secret')->value('value')]);
        config(['services.google.redirect' =>'http://'.$split[2].'/auth/google/callback']);

        //Twitter
        config(['services.twitter.client_id' => Settings::where('type', 'twitter_client_id')->value('value')]);
        config(['services.twitter.client_secret' => Settings::where('type', 'twitter_client_secret')->value('value')]);
        config(['services.twitter.redirect' => 'http://'.$split[2].'/auth/twitter/callback']);

        //Linkedin
        config(['services.linkedin.client_id' => Settings::where('type', 'linkedin_client_id')->value('value')]);
        config(['services.linkedin.client_secret' => Settings::where('type', 'linkedin_client_secret')->value('value')]);
        config(['services.linkedin.redirect' => 'http://'.$split[2].'/auth/linkedin/callback']);

    }
    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
		
        $provider = Request::segment(2);
        if (!in_array($provider, $this->network)) {
            $provider = Request::segment(3);
        }
        if (!in_array($provider, $this->network)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $provider = Request::segment(2);
        if (!in_array($provider, $this->network)) {
            $provider = Request::segment(3);
        }
        if (!in_array($provider, $this->network)) {
            abort(404);
        }
		
        // API CALL - GET USER FROM PROVIDER
        try {
			
            $user_data = Socialite::driver($provider)->user();
				
            if (!$user_data) {
                //return "Unknown error. Please try again in a few minutes.";
                return redirect('/');
            }
        } catch (\Exception $e) {
			
            $msg = $e->getMessage();
            // debug
            //return $msg;
            if (is_string($msg) and !empty($msg)) {
                //return $msg;
                return redirect('/');
            } else {
                return "Unknown error. The service does not work.";
                return redirect('/');
            }
            //return redirect('/b');
            return redirect('/');
        }

        // Debug
        //return dd($user_data);
        // DATA MAPPING
        try {
            $map_user = [];
            if ($provider == 'facebook') {
                $map_user['u_gender'] = (isset($user_data->user['gender']) and $user_data->user['gender'] == 'male') ? 0 : 1;
                $map_user['u_name'] = (isset($user_data->user['u_name'])) ? $user_data->user['u_name'] : '';
                if ($map_user['u_name'] == '') {
                    if (isset($user_data->user['first_name']) and isset($user_data->user['last_name'])) {
                        $map_user['u_name'] = $user_data->user['first_name'] . ' ' . $user_data->user['last_name'];
                    }
                }

            } elseif ($provider == 'google') {
                $map_user = [
                    'u_gender' => (isset($user_data->user['gender']) and $user_data->user['gender'] == 'male') ? 0 : 1,
                    'u_name' => (isset($user_data->name)) ? $user_data->name : '',
                ];
            }
            elseif ($provider == 'twitter') {

                $map_user = [
                    'u_gender' => (isset($user_data->user['gender']) and $user_data->user['gender'] == 'male') ? 0 : 2,
                    'u_name' => (isset($user_data->name)) ? $user_data->name : '',
                ];
            }
            elseif ($provider == 'linkedin') {

                $map_user = [
                    'u_gender' => (isset($user_data->user['gender']) and $user_data->user['gender'] == 'male') ? 0 : 2,
                    'u_name' => (isset($user_data->name)) ? $user_data->name : '',
                ];
            }

            //Get Informations
            $social_id = $user_data->getId();
            $social_email  = $user_data->getEmail();
            $social_email=(isset($social_email)) ? $social_email : $user_data->getId();
            //$avatar = explode("/", $user_data->avatar);
            //return $avatar[4]."/".$avatar[5];

            if($provider == "facebook") { $current_provider_filed = "facebook_id";}
            elseif($provider == "twitter") { $current_provider_filed = "twitter_id";}
            elseif($provider == "google") { $current_provider_filed = "google_id";}
            elseif($provider == "linkedin") { $current_provider_filed = "linkedin_id";}

            //$current_user_data = App\Users::where('u_email', 'like', '%'.$social_email.'%')->first();
            $current_user_data = App\Users::where('u_email',$social_email)->first();

            if($current_user_data){
                
                $current_user_id =  $current_user_data->u_id;
                $current_user_uname =  $current_user_data->u_uname;
                $current_user_password =  $current_user_data->u_password;
                if(isset($current_user_id))
                { // User alrady registerd
                    
                    // GET LOCAL USER
                    $checkUserExist = App\Users::where('facebook_id', $social_id)->orWhere('twitter_id', $social_id)->orWhere('google_id', $social_id)->orWhere('linkedin_id', $social_id)->value('u_id');
                    if(isset($checkUserExist)){ //user already registerd and have social media account so we wil redirect to login
                       
                        $checkUserExist = array('username'=> $social_email ,'social'=> $current_provider_filed , 'password' => $social_id);
                        
                        return app('App\Http\Controllers\LandingController')->loginAuto($checkUserExist);
                    }
                    else{ //user already registerd before but that is the first time login by social so we will update user record with NEW social ID
                        
                        if($current_user_uname && $current_user_password){
                            App\Users::where('u_id', $current_user_id)->update([$current_provider_filed => $social_id]);
                            $loginData = array('username'=> $current_user_uname , 'password' => $current_user_password);
                        }else{
                            App\Users::where('u_id', $current_user_id)->update([$current_provider_filed => $social_id, 'u_uname' => $social_email]);
                            $loginData = array('username'=> $social_email ,'social'=> $current_provider_filed , 'password' => $social_id);
                        }
                        
                        return app('App\Http\Controllers\LandingController')->loginAuto($loginData);
                    }
                }
            }
            else{ // New User :)
                
                // get default or first network
                $getDefaultNetworkID=Network::where('name','default')->first();
                if(isset($getDefaultNetworkID)){$finalNetworkID=$getDefaultNetworkID;}
                else{$finalNetworkID=Network::first();}

                // get default or first branch or Mikrotik location in the session
                if(session('mikrotikLocationID')){
                    $finalBranchID = session('mikrotikLocationID')[0];
                }else{
                    $getDefaultBranchID = App\Branches::where('name', 'default')->orWhere('name', 'Default')->value('id');
                    if (isset($getDefaultBranchID)) {
                        $finalBranchID = $getDefaultBranchID;
                    } else {
                        $finalBranchID = App\Branches::first()->value('id');
                    }
                }

                // // get default or first branch
                // $getDefaultBranchID=Branches::where('name','default')->value('id');
                // if(isset($getDefaultBranchID)){$finalBranchID=$getDefaultBranchID;}
                // else{$finalBranchID=Branches::first()->value('id');}

                //get default or first group
                $getDefaultGroupsID=Groups::where('name','default')->value('id');
                if(isset($getDefaultGroupsID)){$finalGroupID=$getDefaultGroupsID;}
                else{$finalGroupID=Groups::first()->value('id');}

                if($finalNetworkID)
                {
                    $networkType = $finalNetworkID->r_type;
                    $signup = new Users();

                    // get network registration state
                    $signup->network_id = $finalNetworkID->id;
                    $signup->group_id = $finalGroupID;
                    $signup->branch_id = $finalBranchID;
                    $signup->u_name = $user_data->name;
                    $getted_email=$user_data->getEmail();
                    $getted_email=(isset($getted_email)) ? $getted_email : $user_data->getId();
                    $signup->u_uname = $getted_email;
                    $signup->u_email = $getted_email;
                    $signup->$current_provider_filed = $user_data->getId();
                    $signup->u_state = 1;
                    $signup->suspend = 0;
                    $signup->u_gender = 2;
                    if($networkType == "0")//Direct Registration
                    {$signup->Registration_type = 2;}//activated
                    if($networkType == "1")//Waiting Admin Confirm
                    {$signup->Registration_type = 1;}//Waiting Admin Confirm
                    if($networkType == "2")//Waiting SMS confirm
                    {$signup->Registration_type = 0;}//Waiting Admin Confirm
                    if($provider == "twitter"){
                        $avatar = explode("/", $user_data->avatar);
                        $signup->twitter_pic = $avatar[4]."/".$avatar[5];
                    }
                    if($provider == "linkedin"){
                        $signup->linkedin_pic = $user_data->avatar_original;
                    }
                    if($provider == "google"){
                        $signup->google_pic = $user_data->avatar;
                    }

                    //$signup->u_country = App\Settings::where('type', 'country')->value('value');
                    $signup->u_country="Unknown (Social)";
                    
                    $signup->save();

                    $loginData = array('username'=> $getted_email,'social'=> $current_provider_filed , 'password' => $user_data->getId());
                    return  app('App\Http\Controllers\LandingController')->loginAuto($loginData);

                }
            }

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (is_string($msg) and !empty($msg)) {
                return redirect('/');
            } else {
                return redirect('/');
            }
            return redirect('/');
        }
    }
}