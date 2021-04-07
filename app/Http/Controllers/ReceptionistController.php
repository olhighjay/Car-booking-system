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
use App\Car_Availability;
use App\Car_health_status;
use App\Trip_request;
use Illuminate\Support\Facades\Gate;


class ReceptionistController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    
    public function indexReceptionist()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $users= User::whereRoleIs('Receptionist')->get();
        $departments = Department::all();
        $roles = Role::where('id', '!=', 1)->get();
        // dd($excos->suspension->name);
        return view('users.receptionist.index', compact('departments', 'users', 'roles'));    
    }
}
