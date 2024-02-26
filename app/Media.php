<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.media';

    }
    //protected $table = "hotspot.media";

}
