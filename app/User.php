<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use Notifiable;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function suspension()
    {
      return $this->belongsTo('App\Suspension');  
    }

    public function department()
    {
      return $this->belongsTo('App\Department');  
    }

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }

    public function trip()
    {
      return $this->belongsToMany('App\Trip');  
    }

    public function car_report()
    {
      return $this->hasMany('App\Car_report');  
    }

    public function emergency_trip()
    {
      return $this->hasMany('App\Emergency_trip');  
    }

    public function fuel()
    {
      return $this->belongsTo('App\Fuel');  
    }

    public function repair()
    {
      return $this->belongsToMany('App\Repair');  
    }


    public function isAdmin($user)
    {
      if($user->role()->first()['id'] == 2){
        return true ;
      } else{
        return false;
      }
    }

    public function isAccountant($user)
    {
      if($user->role()->first()['id'] == 3){
        return true ;
      } else{
        return false;
      }
    }

    public function isReceptionist($user)
    {
      if($user->role()->first()['id'] == 4){
        return true ;
      } else{
        return false;
      }
    }

    public function isSuperAdmin($user)
    {
      if($user->role()->first()['id'] == 1){
        return true ;
      } else{
        return false;
      }
    }

}
