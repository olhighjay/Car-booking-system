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



class AdminController extends Controller
{
        
    public function __construct(){
        $this->middleware(['auth','verified']);
    }
    //This Controller SUBADMIN  is the one for the ADMIN on the client side
        //while ADMIN controller is for the SUPERADMIN
        public function createAdmin()
        {
            if(Auth::user()->role()->first()['id'] != 1 ){
                return redirect('/unauthorised')->with('error', 'Error 403');
            }
            return view('users.admins.new');
        }
    
        public function storeAdmin(Request $request)
        {
            if(Auth::user()->role()->first()['id'] != 1 ){
                return redirect('/unauthorised')->with('error', 'Error 403');
            }

            $this::validate($request, [
                'name' => ['required', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'min:8', 'confirmed'],
            ]);
    
            $user = new User();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = \Hash::make($request['password']);
            $user->save();
    
            $role = 2;
            $user->attachRole($role);
    
            return redirect('/admins')->with('success', 'You have successfully created a new Admin');
        }
    
        public function indexAdmin()
        {
            if(Auth::user()->role()->first()['id'] != 1 ){
                return redirect('/unauthorised')->with('error', 'Error 403');
            }

            $admins= User::whereRoleIs('Admin')->get();
            $departments = Department::all();
            $roles = Role::where('id', '!=', 1)->get();
            // $admin_dept = $user->department;
            // dd($excos->suspension->name);
            return view('users.admins.index', compact('departments', 'admins', 'roles'));  
        }

        public function updateUserDept(Request $request, User $user)
        {
            if(Auth::user()->suspension_id == 2){
                Auth::logout();
                return view('/suspended');
            }
            if (Gate::denies('admin')) {
                return redirect('/unauthorised')->with('error', 'Error 403');
            }

            $this::validate($request, [
                'department' => 'required',
            ]);
            $user->department_id = $request['department'];
            $user->save();
            return back()->with('success', $user->name.'\'s department' . ' ' . 'updated successfully');
        }



}