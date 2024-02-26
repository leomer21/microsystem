<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignStatistics extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.campaign_statistics';
        //$this->dates = ['created_at', 'updated_at'];
    }


}
