<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
      'name'
    ];

    public function lga()
    {
      return $this->belongsTo('App\Lga');  
    }

    public function user()
    {
      return $this->belongsToMany('App\User');  
    }

    public function car()
    {
      return $this->belongsTo('App\Car');
    }

    public function driver()
    {
      return $this->belongsTo('App\Driver');  
    }

    public function trip_request()
    {
      return $this->belongsTo('App\Trip_request');
    }


    public static function getDriverName($tripId)
    {
      $trip = Trip::find($tripId);
      return $trip->driver;
    }


}
