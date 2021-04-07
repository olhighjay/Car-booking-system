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
use App\Lga;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


class HomeController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','verified']);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // UPDATE EVERY EXPIRED PENDING TRIP AT EVERY TIME THE HOMEPAGE IS LOADED BY ANY USER
        $pendingTrips = Trip::where('trip_request_id',  2)->get();
        if(count($pendingTrips) > 0){
            foreach($pendingTrips as $pendingTrip){
                //Get the carbonized extra time grace of pending trip
                $parsedExtraTime = Carbon::parse($pendingTrip->extra_started_grace);
                //Get the carbonized current time
                $now = Carbon::now();
        
                //Check if it's more than extrea time grace a trip should have started
                if($now > $parsedExtraTime){
                    $pendingTrip->trip_request_id = 7;
                    $pendingTrip->save();

                    //car of the trip
                    if($pendingTrip->car ?? '')
                    {
                        $car = $pendingTrip->car;
                        //checking if the car was previously booked in any of the pevious trips that are still pending
                        $pendTrips = $car->trip()->where('trip_request_id', 2)->get();

                        // check if there is any pending trip that the car is attached to
                        // If there's, car's availabilty status should be booked, else the car is available
                        if(count($pendTrips) > 0){
                            $car->car_availability_id = 3;
                        } else{
                            $car->car_availability_id = 1;
                        }
                        $car->save();
                    }
            
                    //CHANGING THE SWITCHED ON TRIP DRIVER'S AVAILABILITY
                    if($pendingTrip->driver ?? '')
                    {
                        $driver = $pendingTrip->driver;
                        // $driver = Driver::find($id);
                        //Get all the pending trips that the driver is attached to
                        $pendingDrvTrips = $driver->trip()->where('trip_request_id', 2)->get();
                        // dd($pendingDrvTrips);
                
                        // check if there is any pending trip that the driver is attached to
                        // If there's, driver's availabilty status should be booked, else the driver is available
                        if(count($pendingDrvTrips) > 0){
                            $driver->car_availability_id = 3;
                        } else{
                            $driver->car_availability_id = 1;
                        }
                        $driver->save();
                    }
                   
                }
            }
        }

        $user = Auth::user();
        // Susupended user should out
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        if(Auth::user()->roles->first()->id === 1 || Auth::user()->roles->first()->id === 2)
        {
            $users = User::all();
            $drivers = Driver::all();
            $cars = Car::all();
            $trips = Trip::where('trip_request_id', 4)->get();
            $departments = Department::orderBy('name')->get();
            $lastDept = Department::orderBy('created_at', 'desc')->first();
            // dd($lastDept);
            return view('home', compact('drivers', 'cars', 'trips', 'departments', 'lastDept', 'users'));
        }

        $trips = $user->trip()->get();
        $drivers = Driver::where('department_id', $user->department_id)->get();
        $cars = Car::where('department_id', $user->department_id)->get();

        return view('home-staff', compact('drivers', 'cars', 'trips'));

    }

    // public function login()
    // {
    //     return view('welcome');
    // }





}
