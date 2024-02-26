<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public function __construct(){
        $database =  app('App\Http\Controllers\Controller')->configuration();
        $this->table = $database.'.area_groups';

    }

    //protected $table = 'area_groups';
    protected $primaryKey = 'id';
    /**
     * @var array
     */
    //protected $fillable = ['id','name'];
    public function network()
    {
        return $this->belongsTo('App\Network');
    }

}
