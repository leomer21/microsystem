<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignsStatisticsDays extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.campaigns_statistics_days';
        //$this->dates = ['created_at', 'updated_at'];
    }


}
