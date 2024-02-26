<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadacctDays extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.radacct_all_days_group';

    }
}
