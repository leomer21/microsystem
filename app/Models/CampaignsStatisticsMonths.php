<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignsStatisticsMonths extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.campaigns_statistics_months';
        //$this->dates = ['created_at', 'updated_at'];
    }


}
