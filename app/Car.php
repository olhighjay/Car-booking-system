<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Car extends Model
{
    use SoftDeletes;

  
    public function car_availability()
    {
      return $this->belongsTo('App\Car_Availability');  
    }

    public function car_health_status()
    {
      return $this->belongsTo('App\Car_health_status');  
    }

    public function department()
    {
      return $this->belongsTo('App\Department');  
    }

    public function trip_request()
    {
      return $this->belongsTo('App\Trip_request');  
    }

    public function driver()
    {
      return $this->hasMany('App\Driver');  
    }

    public function carimage()
    {
      return $this->hasMany('App\Carimage');  
    }

    public function trip()
    {
      return $this->hasMany('App\Trip');  
    }

    public function emergency_trip()
    {
      return $this->hasMany('App\Emergency_trip');  
    }

    public function car_report()
    {
      return $this->hasMany('App\Car_report');  
    }

    public function fuel()
    {
      return $this->belongsToMany('App\Fuel');  
    }

    public function repair()
    {
      return $this->belongsToMany('App\Repair');  
    }

    
    public static function getCarName($carId)
    {
      return Car::find($carId);
    }
    
}
