<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimationProgramSchedule extends Model
{
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.animation_program_schedule';

    }
    public $timestamps = false;
}
