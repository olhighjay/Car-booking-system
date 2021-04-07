<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;

use Illuminate\Http\Request;
use App\User;
use App\Driver;
use App\Role;
use App\Department;
use App\Car;
use App\Car_Availability;
use App\Car_health_status;
use App\Trip_request;
use Illuminate\Support\Facades\Gate;


class SuspensionController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','verified']);
    }


    public function unsuspendUser(User $user)
    {
        // Log out suspended user
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        // Only admins and super admin have access
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $user->suspension_id = 1;
        $user->save();

        return back()->with('success', $user->name . ' ' . 'has been re-activated successfully');
    }

    public function suspendUser(User $user)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $user->suspension_id = 2;
        $user->save();

        return back()->with('success', $user->name . ' ' . 'has been deactivated successfully');
    }






}
