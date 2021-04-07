<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }
}
