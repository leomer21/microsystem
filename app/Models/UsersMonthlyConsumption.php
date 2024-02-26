<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersMonthlyConsumption extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.users_monthly_consumption';

    }
    public $timestamps = false;
}
