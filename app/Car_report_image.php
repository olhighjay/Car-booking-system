<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car_report_image extends Model
{
    public function car_report()
    {
      return $this->belongsTo('App\Car_report');  
    }
}
