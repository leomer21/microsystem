<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{

    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.settings';

    }

    //protected $table = "settings";

    public $timestamps = false;
}
