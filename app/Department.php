<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Driver;

class Department extends Model
{
    public function car(){
        $this->belongsToMany('App\Car');
    }

    public function user(){
        $this->hasMany('App\User');
    }

    
    public function countDriver($id){
        $drivers = Driver::where('department_id', $id)->get();
        return count ($drivers);
    }

     public function countCar($id){
        $cars = Car::where('department_id', $id)->get();
        return count ($cars);
    }

    public function countUser($id){
        $users = User::where('department_id', $id)->get();
        return count ($users);
    }

    public function driver()
    {
      return $this->belongsToMany('App\Driver');  
    }


}
