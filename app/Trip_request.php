<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip_request extends Model
{
    protected $fillable = [
        'name'
        ];

    public function car(){
        return $this->hasMany('App\Car');
    }

    public function trip()
    {
        return $this->hasMany('App\Trip');
    }

    public function emergency_trip()
    {
      return $this->belongsTo('App\Emergency_trip');
    }
}
