<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    
    use SoftDeletes;
    
    public function suspension()
    {
      return $this->belongsTo('App\Suspension');  
    }

    public function department()
    {
      return $this->belongsTo('App\Department');  
    }

    public function car()
    {
      return $this->belongsTo('App\Car');  
    }

    public function car_availability()
    {
      return $this->belongsTo('App\Car_Availability');  
    }

    public function trip()
    {
      return $this->hasMany('App\Trip'); 
    }

    public function driver_report()
    {
      return $this->hasMany('App\Driver_report');  
    }


    // public static function getDriverName($tripId)
    // {
    //   return Car::find($carId);
    // }



}
