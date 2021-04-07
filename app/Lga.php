<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lga extends Model
{
    public function Trip()
    {
      return $this->hasMany('App\Trip');  
    }

    public function emergency_trip()
    {
      return $this->hasMany('App\Emergency_trip');  
    }

    public static function getLgaName($lgaId)
    {
      return Lga::find($lgaId);
    }
}
