<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadacctNetworkDays extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.radacct_network_days';

    }
}
