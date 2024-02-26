<?php

namespace App;

use Illuminate\Database\Eloquent\Model;;

class Radacct extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.radacct';

    }
    public $timestamps = false;
    //protected $table = 'radacct';
}
