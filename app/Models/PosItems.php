<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosItems extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.pos_items';

    }
}
