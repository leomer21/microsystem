<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTags extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.user_tags';

    }
    public $timestamps = false;
}
