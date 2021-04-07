<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car_health_status extends Model
{
    public function car(){
        $this->hasMany('App\Car');
    }

    public function car_report(){
        $this->hasMany('App\Car_report');
    }
}
