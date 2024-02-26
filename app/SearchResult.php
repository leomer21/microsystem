<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchResult extends Model
{

    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.search_result';

    }
}
