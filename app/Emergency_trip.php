<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emergency_trip extends Model
{
    public function lga()
    {
      return $this->belongsTo('App\Lga');  
    }

    public function user()
    {
      return $this->belongsTo('App\User');  
    }

    public function car()
    {
      return $this->belongsTo('App\Car');
    }

    public function trip_request()
    {
      return $this->belongsTo('App\Trip_request');
    }
}
