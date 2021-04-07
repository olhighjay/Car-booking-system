<?php

namespace App;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    // public $guarded = [];
    
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
