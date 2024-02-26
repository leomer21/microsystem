<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    //protected $quantity = 5;
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.messages';

    }
}
