<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.cards';

    }
    public $timestamps = false;
}
