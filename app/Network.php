<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.networks';

    }
    protected $quantity = 5;


}
