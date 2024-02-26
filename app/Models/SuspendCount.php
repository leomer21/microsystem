<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuspendCount extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.suspend_count';

    }
}
