<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{

    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.branches';

    }
    //protected $quantity = 5;



}
