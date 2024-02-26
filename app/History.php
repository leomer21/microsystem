<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.history';

    }

    public $timestamps = false;
}
