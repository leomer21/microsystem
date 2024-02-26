<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchVisitedSites extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.visited_sites_full';

    }
}
