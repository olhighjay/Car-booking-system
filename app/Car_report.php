<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car_report extends Model
{

    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function car_health_status()
    {
        return $this->belongsTo('App\Car_health_status');
    }

    public function car_report_image()
    {
      return $this->hasMany('App\Car_report_image');  
    }





}
