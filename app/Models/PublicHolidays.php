<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicHolidays extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.public_holidays';

    }
    public $timestamps = false;
}
