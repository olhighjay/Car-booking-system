<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car_availability extends Model
{
    public function car(){
        $this->hasMany('App\Car');
    }

    public function driver()
    {
      return $this->hasMany('App\Driver');  
    }
}
