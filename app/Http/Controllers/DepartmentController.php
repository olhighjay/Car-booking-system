<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Department;
use App\Car;
use App\Driver;
use App\Car_Availability;
use App\Car_health_status;
use App\Trip_request;
use Illuminate\Support\Facades\Gate;


class DepartmentController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    public function createDepartment()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        return view('departments.new');
    }

    public function storeDepartment(Request $request)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        #input requirement check/partial authentication
        $this->validate($request, [
            'name'=>'required',
            'description'=>'nullable',
        ]);

        
        #To save the request data in database table
        $department = new Department();
        $department->name = $request['name'];
        $department->description = $request ['description'];
        $department->save();
        return redirect('/departments/'.$department->id)->with('success', $department->name . ' ' .'department has been created');
    }

    public function showDepartment(Department $dept)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        
        $members = User::orderBy('name')->where('department_id', $dept->id)->get();
        // $name = $members->roles()->first()->name;
        // dd($members);
        // $dept_cars = Car::where('department_id', $dept)->get();
        $dept_cars = Car::where('department_id', $dept->id)->get();
        // dd($dept_cars);
        $drivers = Driver::where('department_id', $dept->id)->get();
        return view('departments.show', compact('members', 'dept_cars', 'dept', 'drivers'));
    }
}
