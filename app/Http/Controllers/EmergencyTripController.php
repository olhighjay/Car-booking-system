<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmergencyApproved;
use App\Notifications\EmergencyDenied;
use App\Notifications\EmergencyTripCreated;
use App\Notifications\TripCreated;

use App\Events\SendDenyEmergency;
use App\Events\SendApproveEmergency;
use App\Events\EmergencyTripCreatedEvent;
use App\Events\SendCreatedMail;


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
use App\Emergency_trip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;



class EmergencyTripController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','verified']);
    }


    public function create()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        $user = Auth::user();
        $PendingTrips = $user->emergency_trip()->where('trip_request_id', 2)->get();
        if(count($PendingTrips) > 0){
            return back()->with('error', 'You have a pending Emergency trip already');
        }
        // $lastEmergencyTrip = Emergency_trip::orderBy('created_at', 'desc')->where('user_id', $user->id)->first();
        // if($lastEmergencyTrip){
        //     $parsedLastTrip = Carbon::parse($lastEmergencyTrip->created_at);
        //     // dd($parsedLastTrip);
        //         $waitingTime = $parsedLastTrip->addMinutes(15); 
        //     // dd(Carbon::now());
        //     // dd($waitingTime, $lastTrip->created_at, $parsedLastTrip);
        //     if(Carbon::now() < $waitingTime ){
        //          return back()->with('error', 'You have created an emergency trip in the last 15 minutes. Kindly wait a bit and try again');
        //     }
        // };

        $lgas = LGA::orderBy('name')->get();

        return view('trips.emergency.new', compact('lgas'));      
    }

    
    public function store(Request $request)
    {
        $currentNow = Carbon::now();
        $start = date("Y-m-d H:i:s",strtotime($request['trip_date'].$request['trip_start_time']));
        $end = date("Y-m-d H:i:s", strtotime($request['trip_date'].$request['trip_end_time']));
        // $user is the logged in user who is trying to book a trip
        $user = Auth::user();
        $admins = User::whereRoleIs('admin')->get();
        
        // Getting the department of the User
        $department = $user->department_id;

        // Validation of inputs
        $this->validate($request, [
            'lga' => 'required',
            'address' => 'required',
            'purpose' => 'nullable',
            'trip_date' => 'required',
            'trip_start_time' => 'required',
            'trip_end_time' => 'required',
        ]);
        // dd($request['trip_start_time']);

         // validate chosen date
         if(Carbon::parse(date('Y-m-d')) > Carbon::parse($request['trip_date'])){
            return back()->with('error', 'The date you picked is invalid. You can\'t pick a day that has passed');
        }

        // validate chosen time
        if($currentNow > $start){
            return back()->with('error', 'Invalid time!!.. You can\'t pick a time that has passed');
        }
        if($currentNow >= $end){
            return back()->with('error', 'Invalid Time.. Trip end time error');
        }
        if($start >= $end){
            return back()->with('error', 'Invalid Time');
        }

        
        // Save new trip inputs in trip table
        $trip = new Emergency_trip();
        $trip->name = $user->name;
        $trip->lga_id = $request['lga'];
        $trip->address = $request['address'];
        $trip->purpose = $request['purpose'];
        $trip->department_id = $department;
        $trip->trip_request_id = 2;
        $trip->user_id = $user->id;
        $trip->user_role = $user->roles()->first()->name;
        $trip->trip_date = $request['trip_date'];
        $trip->trip_start_time = $request['trip_start_time'];
        $trip->trip_end_time = $request['trip_end_time'];
        $trip->save();

        $userId = $user->id;
        $tripId = $trip->id;

        foreach($admins as $admin){
            $adminId = $admin->id;
            $admin->notify(new EmergencyTripCreated($trip));
            // EmergencyTripCreatedEvent::dispatch($userId, $adminId, $tripId);
        }

        return redirect('/myemergencies')->with('success', 'Your Trip has been created and is pending admin\'s approval. Check your notification inbox and email to know when it is approved');
    }

    public function index()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $trips = Emergency_trip::orderBy('created_at', 'desc')->where('trip_request_id', 2 )->paginate(20);
        
        return view('trips.emergency.index', compact('trips'));
    }

    public function myIndex()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        $user = Auth::user();

        $trips = Emergency_trip::orderBy('created_at', 'desc')->where('user_id', $user->id )->paginate(20);
        
        return view('trips.emergency.myindex', compact('trips'));
    }

    public function dismissEmergencyTrip(Request $request, Emergency_trip $trip)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        
        $trip->trip_request_id = 6;
        $trip->save();

        $user = User::where('id', $trip->user_id)->first();
        $userId = $user->id;
        $tripId = $trip->id;
        $user->notify(new EmergencyDenied($trip));
        SendDenyEmergency::dispatch($userId);

        return redirect('/emertrips')->with('success', 'Request Dismissed Successfully');
    }

    public function showApproval(Emergency_trip $trip)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        if($trip->trip_request_id !== 2){
            return back()->with('error','Invalid page');
        }else{
            $cars = Car::all();
            $drivers = Driver::all();

            return view('trips.emergency.approval', compact('trip', 'cars', 'drivers'));  
        }    
    }

    public function saveApproval(Request $request, Emergency_trip $eTrip)
    {
        
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $currentNow = Carbon::now();
        $start = date("Y-m-d H:i:s",strtotime($request['trip_date'].$request['trip_start_time']));
        $end = date("Y-m-d H:i:s", strtotime($request['trip_date'].$request['trip_end_time']));


        //GRACE PERIOD
        $start_grace = Carbon::parse($eTrip->trip_date. ' ' . $eTrip->trip_start_time);
        $subed_time = $start_grace->subHour(); 

        $end_grace = Carbon::parse($eTrip->trip_date. ' ' . $eTrip->_end_time);
        $added_time = $end_grace->addHour(); 

        $extra_grace = Carbon::parse($eTrip->trip_date. ' ' . $eTrip->trip_start_time);
        $cancelGrace = $extra_grace->addMinutes(15); 

        
        // Save new trip inputs in trip table
        $trip = new Trip();
        $trip->name = $eTrip->name;
        $trip->lga_id = $eTrip->lga_id;
        $trip->address = $eTrip->address;
        $trip->car_id = $request['car'];
        $trip->department_id = $eTrip->department_id;
        $trip->trip_request_id = 2;
        $trip->user_role = $eTrip->user_role;
        $trip->trip_date = $eTrip->trip_date;
        $trip->trip_start_time = $eTrip->trip_start_time;
        $trip->trip_end_time = $eTrip->trip_end_time;
        $trip->start_time_grace = $subed_time;
        $trip->extra_started_grace =  $cancelGrace;
        $trip->end_time_grace = $added_time;
        $trip->driver_id = $request['driver'];
        $trip->save();
        
        $driver = Driver::find($request['driver']);
         // Change the Availability status of the booked car
         $car = Car::find($trip->car_id);
         $car->car_availability_id = 3;
         $car->save();
        
         // attach user and driver to trip
        // $users = [$user->id, $driver->id];
        $trip->user()->attach($eTrip->user_id);
        // $trip->driver()->attach($driver->id); 

         // change the availability status of the chosen driver for the new trip
        $driver->car_availability_id = 3;
        $driver->save();

        $eTrip->trip_request_id = 8;
        $eTrip->save();
        
        $user = $trip->user->first();
        $userId = $user->id;
        $tripId = $trip->id;
        $user->notify(new EmergencyApproved($trip));
        SendApproveEmergency::dispatch($userId, $tripId);

       

        return redirect('/trips')->with('success', 'Trip approved');
    }

    public function dismissExpiredEmergencyTrip(Request $request, Emergency_trip $trip)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $trip->trip_request_id = 6;
        $trip->save();

        return redirect('/emertrips')->with('success', 'Request Dismissed Successfully');
    }

    public function createAdminEmergency()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $user = Auth::user();
        $lgas = LGA::orderBy('name')->get();
        // All Cars that User could have access to
        $cars = Car::orderBy('name')->get();
        $drivers = Driver::orderBy('name')->get();
        $users = User::all();

        // Checking if there is any car attached to user's department yet
        if(count($cars) > 0)
        {

            return view('trips.emergency.admin-new', compact('user', 'users', 'lgas', 'cars', 'drivers'));;
        }
        
        // dd($lgas);
        return redirect('/mycars')->with('error', 'You do not have access to any vehicle yet. Please contact the admin to resolve this');
    }

    public function storeAdminEmergency(Request $request)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        
        $currentNow = Carbon::now();
        $start = date("Y-m-d H:i:s",strtotime($request['trip_date'].$request['trip_start_time']));
        $end = date("Y-m-d H:i:s", strtotime($request['trip_date'].$request['trip_end_time']));
        // dd($a, $b, $c, $d, $g, $e, $f);
       

        // Validation of inputs
        $this->validate($request, [
            'lga' => 'required',
            'address' => 'required',
            'car' => 'required',
            'trip_date' => 'required',
            'trip_start_time' => 'required',
            'trip_end_time' => 'required',
            'users' => 'required',
        ]);
        // dd($request['trip_start_time']);

         // $user is the logged in user who is trying to book a trip
         $user = User::find($request['users']);
         // Getting the department of the User
         $department = $user->department_id;

        // validate chosen date
        if(Carbon::parse(date('Y-m-d')) > Carbon::parse($request['trip_date'])){
            return back()->with('error', 'The date you picked is invalid. You can\'t pick a day that has passed');
        }

        // validate chosen time
        if($currentNow > $start){
            return back()->with('error', 'Invalid time!!.. You can\'t pick a time that has passed');
        }
        if($currentNow >= $end){
            return back()->with('error', 'Invalid Time.. Trip end time error');
        }
        if($start >= $end){
            return back()->with('error', 'Invalid Time');
        }

        //GRACE PERIOD
        $start_grace = Carbon::parse($request['trip_date']. ' ' . $request['trip_start_time']);
        $subed_time = $start_grace->subHour(); 

        $end_grace = Carbon::parse($request['trip_date']. ' ' . $request['trip_end_time']);
        $added_time = $end_grace->addHour(); 

        $extra_grace = Carbon::parse($request['trip_date']. ' ' . $request['trip_start_time']);
        $cancelGrace = $extra_grace->addMinutes(15); 

        
        // Save new trip inputs in trip table
        $trip = new Trip();
        $trip->name = $user->name;
        $trip->lga_id = $request['lga'];
        $trip->address = $request['address'];
        $trip->car_id = $request['car'];
        $trip->driver_id = $request['driver'];
        $trip->department_id = $department;
        $trip->trip_request_id = 2;
        $trip->user_role = $user->roles()->first()->name;
        $trip->trip_date = $request['trip_date'];
        $trip->trip_start_time = $request['trip_start_time'];
        $trip->trip_end_time = $request['trip_end_time'];
        $trip->start_time_grace = $subed_time;
        $trip->extra_started_grace =  $cancelGrace;
        $trip->end_time_grace = $added_time;
        $trip->save();

       

        // Change the Availability status of the booked car
        $car = Car::where('id', $trip->car_id)->first();
        $car->car_availability_id = 3;
        $car->save();

        // attach user and driver to trip
        // $users = [$user->id, $driver->id];
        $driver = $request['driver'];
        $trip->user()->attach($user->id);


        // change the availability status of the chosen driver for the new trip
        $realDriver = Driver::find($driver);
        $realDriver->car_availability_id = 3;
        $realDriver->save();

        $userId = $user->id;
        $tripId = $trip->id;

        //send notifications
        $user->notify(new TripCreated($trip));
        //send email
        SendCreatedMail::dispatch($userId, $tripId);

        return redirect('/trips')->with('success', 'Your Trip has been created. See the Receptionist to start the trip when it\'s time');
    }


}
