<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver_report extends Model
{
    
    public function driver()
    {
      return $this->belongsTo('App\Driver');  
    }

    public function offense()
    {
        return $this->belongsTo('App\Offense');
    }
}
