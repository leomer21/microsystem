<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupTemporarySwitch extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.group_temporary_switch';

    }
}
