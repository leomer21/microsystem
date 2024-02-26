<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupsCount extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.groups_count';

    }
}
