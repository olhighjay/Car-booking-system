<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offense extends Model
{

    public function driver_report()
    {
      return $this->hasMany('App\Driver_report');  
    }

    
}
