<?php

namespace App\Http\Controllers\Integrations;

use App\Http\Requests;
use Illuminate\Http\Request;
use App;
use Input;
use DB;
use Redirect;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use Config;
use App\Settings;

class Accountkit
{

    protected $appId;
    protected $appSecret;
    protected $tokenExchangeUrl;
    protected $endPointUrl;
    public $userAccessToken;
    protected $refreshInterval;


    function __construct()
    {

        $this->appId            = Settings::where('type', 'Accountkitappid')->value('value');
        $this->client           = new GuzzleHttpClient();
        $this->appSecret        = Settings::where('type', 'Accountkitappsecret')->value('value');
        $this->endPointUrl      = config('AccountKit.end_point');
        $this->tokenExchangeUrl = config('AccountKit.tokenExchangeUrl');
    }

    public function Send($code)
    {
        $url = $this->tokenExchangeUrl.'grant_type=authorization_code'.
            '&code='. $code.
            "&access_token=AA|$this->appId|$this->appSecret";
        $apiRequest = $this->client->request('GET', $url);
        $body = json_decode($apiRequest->getBody());
        $this->userAccessToken = $body->access_token;
        $this->refreshInterval = $body->token_refresh_interval_sec;
        $request = $this->client->request('GET', $this->endPointUrl.$this->userAccessToken);
        $data = json_decode($request->getBody());
        $userId = $data->id;
        return $phone =  $data->phone->country_prefix.$data->phone->national_number;
    }
}