<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadacctTodayConsumptionActiveUsers extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.radacct_today_consumption_active_users';
    }
    public $timestamps = false;
}
