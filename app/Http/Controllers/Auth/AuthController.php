<?php

namespace App\Http\Controllers\Auth;

use App\Users;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Response;
use Session;
use DB;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    //protected $guard = 'admin';
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'admin';

    //protected $username = 'u_uname';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
         
    }

    public function showLoginForm()
    {
        
        $split = explode('/', url()->full());
        
        if((isset($split) && $split[2] == "my.microsystem.com.eg" || $split[2] == "cloud.mymicrosystem.com" || $split[2] == "my.mymicrosystem.com" || isset($split) && $split[2] == "my.microsystemapp.com" || $split[2] == "server2.microsystem.com.eg" || $split[2] == "whitelabel.microsystem.com.eg" || $split[2] == "s2.microsystem.com.eg" || $split[2] == "s1.microsystem.com.eg" || $split[2] == "backup.microsystem.com.eg" || $split[2] == "microsystem.cloud" || $split[2] == "my.microsystem.cloud" || $split[2] == "my.mikrotik.com.eg" || $split[2] == "controller.mikrotik.com.eg" || $split[2] == "cloud.mikrotik.com.eg" || $split[2] == "install.microsystem.com.eg") && !session('Identify')){
            
            return view('back-end.auth/subdomain');

        }elseif( $split[2] == "payment.microsystem.com.eg" or $split[2] == "payment.mikrotik.com.eg" ){
            return view('back-end.settings/paymentState');
        }
        else{

            return view('back-end.auth/login');
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return Users::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }



}
