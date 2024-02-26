<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class URLFilter extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.url_filter';

    }
}
