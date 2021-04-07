<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TripCreated;
use App\Notifications\TripCancelled;
use App\Notifications\TripDenied;
use App\Notifications\TripSwitchedOn;
use App\Notifications\TripSwitchedOff;
use App\Events\SendCreatedMail;
use App\Events\SendDenyTrip;
use App\Events\SendSwitchedOn;
use App\Events\SendSwitchedOff;
use Event;



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
// use Timezone\Timezone;


class TripController extends Controller
{
    
    public function __construct(){
        $this->middleware(['auth','verified']);;
    }


    public function create()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        $user = Auth::user();

        $cars = Car::orderBy('created_at', 'desc')->where('department_id', $user->department_id)->get();

        //A user can only create atrip in 15 minutes
        $lastTrip = $user->trip()->orderBy('created_at', 'desc')->first();
        if($lastTrip){
            $parsedLastTrip = Carbon::parse($lastTrip->created_at);
            // dd($parsedLastTrip);
            $waitingTime = $parsedLastTrip->addMinutes(59); 
            // dd(Carbon::now());
            // dd($waitingTime, $lastTrip->created_at, $parsedLastTrip);
            if(Carbon::now() < $waitingTime ){
                return back()->with('error', 'You can only create a trip in an hour. Kindly wait a bit and try again');
            }
        };

        $ids = [1,3];
        $drivers = Driver::where('department_id', $user->department_id)->whereIn('car_availability_id', $ids)->get();
        $lgas = LGA::orderBy('name')->get();
        // All Cars that User could have access to
        $carz = Car::where('department_id', $user->department_id)->whereIn('car_availability_id', $ids)->get();

        // If all cars are unavailable and or all drivers are unavailable, throw an error
        if(count($carz) < 1)
        {
            return redirect('/mycars')->with('error', 'All your cars are currently unavailable');
        }
        if(count($drivers) < 1)
        {
            return redirect('/mydrivers')->with('error', 'All your drivers are currently unavailable');
        }
        
        return view('trips.new', compact('user', 'lgas', 'cars'));
    }

    public function store(Request $request)
    {
        
        // current time
        $currentNow = Carbon::now();
        // requested start time
        $start = date("Y-m-d H:i:s",strtotime($request['trip_date'].$request['trip_start_time']));
        // requested end time
        $end = date("Y-m-d H:i:s", strtotime($request['trip_date'].$request['trip_end_time']));
        
        // $user is the logged in user who is trying to book a trip
        $user = Auth::user();
        
        // Getting the department of the User
        $department = $user->department_id;

        //Validation of inputs
        $this->validate($request, [
            'lga' => 'required',
            'address' => 'required',
            'car' => 'required',
            'trip_date' => 'required',
            'trip_start_time' => 'required',
            'trip_end_time' => 'required',
        ]);
        
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

        
        // Getting the attached drivers to the department that are available
        $availableDrivers = Driver::where('department_id' , $department)->where('car_availability_id' , 1)->get();
        //ids of all the available drivers
        foreach($availableDrivers as $avDriver){
            $arrayIDs[] = $avDriver->id;
        }
        
        // Booked drivers
        $bookedDrivers = Driver::where('department_id' , $department)->where('car_availability_id' , 3)->get();
        //IF THERE IS ANY AVAILABLE DRIVER IN THE DEPARTMENT, PICK THE ONE THAT HAS NOT GONE FOR THE RECENT TRIPS
        if(count($availableDrivers) > 1){
            //the earliest trip of all completed trips embarked on by the available drivers
            $earliertrip = Trip::orderBy('trip_ended_time', 'asc')->whereIn('driver_id', $arrayIDs)->where('trip_request_id', 4)->first();
            if($earliertrip){
                $driver = $earliertrip->driver;
            }else{
                $driver = Driver::where('department_id' , $department)->where('car_availability_id' , 1)->first();
            }
            
        }elseif(count($availableDrivers) === 1){
            $driver = Driver::where('department_id' , $department)->where('car_availability_id' , 1)->first();
        }
        /* IF ALL DRIVERS IN THE DEPARTMENT ARE BOKED, PICK THE ONE THAT HAS NOT GONE FOR THE RECENT TRIPS THAT THE CURRENT TRIP TIME DOES NOT
            CONTRADICT ANY OF HIS BOOKED TIME */
        elseif(count($bookedDrivers) > 0){
            foreach($bookedDrivers as $bDriver){
                // Get all the pending trips of the booked driver
                $pendingbDriverTrips = $bDriver->trip()->where('driver_id', $bDriver->id)->where('trip_request_id', 2)->get();
            }
           
            if(count($pendingbDriverTrips) > 0){
                foreach($pendingbDriverTrips as $pdtrip){
                    //Get the carbonized start time and start time grace of pending trip
                    $parsedPDStartTime = Carbon::parse($pdtrip->trip_date. ' ' . $pdtrip->trip_start_time);
                    $parsedPDStartGrace = Carbon::parse($pdtrip->start_time_grace);
                    //Get the carbonized start time and end time of current trip
                    $currTripEnd = Carbon::parse($request['trip_date'] . ' ' . $request['trip_end_time']);
                    $currTripStart = Carbon::parse($request['trip_date'] . ' ' . $request['trip_start_time']);
                    //Get the carbonized end time grace of pending trip
                    $parsedPDEndGrace = Carbon::parse($pdtrip->end_time_grace);

                    /* if the starting time of the current trip is not up to the start time of the pending trip but the ending time of 
                        the current trip is more than the 1hr grace given to the starting of the pending trip, Save the trip in contradictingStartTime */
                    if($currTripStart < $parsedPDStartTime && $currTripEnd > $parsedPDStartGrace){
                        $contradictingDriverIds = $pdtrip->driver_id;

                        
                    /* if the starting time of the current trip is more than the start time of the pending trip but the starting time of 
                    the current trip is not up to the 1hr grace given to the ending of the pending trip, Save the trip in contradictingStartTime */
                    }elseif($currTripStart > $parsedPDStartTime && $currTripStart < $parsedPDEndGrace){
                        $contradictingDriverIds = $pdtrip->driver_id; 
                    }else{
                        $contradictingDriverIds = [];
                    }
                }
            }else{
                return back()->with('error', 'All the drivers are not available  at the moment');
            }

            // Get the ids of the booked drivers that their pending trips' time don't contradict current trip time
            if(count($contradictingDriverIds) > 0){
                $uncontradictedDriversIds = Driver::where('department_id' , $department)->whereIn('id' , '!==', $contradictingDriverIds)->where('car_availability_id' , 3)->get()['id'];
            }else{
                foreach($bookedDrivers as $bookedDriver){
                    $uncontradictedDriversIds[] = $bookedDriver->id;
                }
            }

            $x = count($uncontradictedDriversIds);
            $earliertrips = Trip::latest()->where('trip_request_id', 2)->whereIn('driver_id', $uncontradictedDriversIds)->take($x)->get();
            $earliertrip = $earliertrips[$x-1];

            // $earliertrip = Trip::orderBy('created_at', 'asc')->whereIn('driver_id', $uncontradictedDriversIds)->where('trip_request_id', 2)->first();
            $driver = $earliertrip->driver;
            

        }else{
            return back()->with('error', 'No available driver at the moment');
        }
        
        // Save new trip inputs in trip table
        $trip = new Trip();
        $trip->name = $user->name;
        $trip->lga_id = $request['lga'];
        $trip->address = $request['address'];
        $trip->car_id = $request['car'];
        $trip->driver_id = $driver->id;
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

        // Get the just booked car
        $bookedCar = $trip->car;
        //Get all the pending trips of the car besides the just booked trip
        $pendingTrips = $bookedCar->trip()->where('id', '!=', $trip->id)->where('trip_request_id', 2)->get();
        // dd($pendingTrips);
        if(count($pendingTrips) > 0){
            foreach($pendingTrips as $pendingTrip){
                //Get the carbonized start time and start time grace of pending trip
                $parsedPendingStartTime = Carbon::parse($pendingTrip->trip_date. ' ' . $pendingTrip->trip_start_time);
                $parsedPendingStartGrace = Carbon::parse($pendingTrip->start_time_grace);
                //Get the carbonized start time and end time of current trip
                $tripEnd = Carbon::parse($trip->trip_date. ' ' . $trip->trip_end_time);
                $tripStart = Carbon::parse($trip->trip_date. ' ' . $trip->trip_start_time);
                //Get the carbonized end time grace of pending trip
                $parsedPendingEndGrace = Carbon::parse($pendingTrip->end_time_grace);

                // Check if the starting time of the current trip is not up to the start time of the pending trip
                if($tripStart < $parsedPendingStartTime ){
                    /* check if the ending time of the current trip is more than the 1hr grace given to the starting of the pending trip,
                       Save the trip in contradictingStartTime*/
                    if($tripEnd > $parsedPendingStartGrace){
                        $contradictingStartTime[] = $pendingTrip;
                        
                        /*if there is any trip saved in contradictingStartTime which means that there is at least a trip
                          that the current trip's end time is more than its starting time*/
                        if(count($contradictingStartTime) > 0 ){
                            // If there is a trip contrdicting with the time of the new trip, delete current trip and throw an error
                            $trip->delete();
                            return back()->with('error', 'The vehicle is booked for a trip that is scheduled to start before your trip ends. 
                            Please set your trip to end earlier or you pick a different car if there\'s or you request for an emergency trip');
                        }
                    }

                }

                // Check if the starting time of the current trip is more than the start time of the pending trip
                if($tripStart > $parsedPendingStartTime ){
                     /* check if the starting time of the current trip is not up to the 1hr grace given to the ending of the pending trip,
                       Save the trip in contradictingStartTime*/
                    if($tripStart < $parsedPendingEndGrace){
                        $contradictingEndTime[] = $pendingTrips; 
                        
                         /*if there is any trip saved in contradictingEndTime which means that there is at least a trip
                          that the current trip's start time is more than its ending time*/
                        if(count($contradictingEndTime) > 0 ){
                            // If there is a trip contrdicting with the time of the new trip, delete current trip and throw an error
                            $trip->delete();
                            return back()->with('error', 'The vehicle is booked for a trip which will not end before your trip starts. 
                            Please set your trip to end earlier or you pick a different car if there\'s or you request for an emergency trip');
                        }
                    }
                    
                }
            }
        }

            /*   Get  all the drivers that are either available or booked but not unavailable
                $driver_avails = [1,3];
                $drivers = Driver::where('department_id' , $department)->whereIn('car_availability_id', $driver_avails)->get();
                
                $completedTrips = Trip::where('trip_request_id', 4)->where('department_id', $department)->get();

                dd($completedTrips);
                // Checking if any of the drivers has gone on any trip previously
                if(count($completedTrips) > 0 ){
                    $trips = Trip::orderBy('trip_ended_time', 'desc')->where('trip_request_id', 4)->where('department_id', $department)->take($x)->get();
                } else{
                    $trips = Trip::orderBy('trip_ended_time', 'desc')->where('trip_request_id', 2)->where('department_id', $department)->take($x)->get();
                }

                Available drivers

                // all trips embarked on in the department that are either pending or completed
                $request_ids = [2,4];
                $allTrips = Trip::where('department_id', $department)->whereIn('trip_request_id', $request_ids)->get();

                Check if there had been previous trips embarked on in passenger's department
                if(count($allTrips) > 1){
                    // Get the just booked car
                    $bookedCar = $trip->car;
                    //Get all the pending trips of the car besides the just booked trip
                    $pendingTrips = $bookedCar->trip()->where('id', '!=', $trip->id)->where('trip_request_id', 2)->get();
                    // dd($pendingTrips);
                    if(count($pendingTrips) > 0){
                        foreach($pendingTrips as $pendingTrip){
                            //Get the carbonized start time and start time grace of pending trip
                            $parsedPendingStartTime = Carbon::parse($pendingTrip->trip_date. ' ' . $pendingTrip->trip_start_time);
                            $parsedPendingStartGrace = Carbon::parse($pendingTrip->start_time_grace);
                            //Get the carbonized start time and end time of current trip
                            $tripEnd = Carbon::parse($trip->trip_date. ' ' . $trip->trip_end_time);
                            $tripStart = Carbon::parse($trip->trip_date. ' ' . $trip->trip_start_time);
                            //Get the carbonized end time grace of pending trip
                            $parsedPendingEndGrace = Carbon::parse($pendingTrip->end_time_grace);

                            // Check if the starting time of the current trip is not up to the start time of the pending trip
                            if($tripStart < $parsedPendingStartTime ){
                                //check if the ending time of the current trip is more than the 1hr grace given to the starting of the pending trip,
                                // Save the trip in contradictingStartTime
                                if($tripEnd > $parsedPendingStartGrace){
                                    $contradictingStartTime[] = $pendingTrip;
                                    
                                    // if there is any trip saved in contradictingStartTime which means that there is at least a trip
                                    //that the current trip's end time is more than its starting time
                                    if(count($contradictingStartTime) > 0 ){
                                        // If there is a trip contrdicting with the time of the new trip, delete current trip and throw an error
                                        $trip->delete();
                                        return back()->with('error', 'The vehicle is booked for a trip that is scheduled to start before your trip ends. 
                                        Please set your trip to end earlier or you pick a different car if there\'s or you request for an emergency trip');
                                    }
                                }

                            }

                            // Check if the starting time of the current trip is more than the start time of the pending trip
                            if($tripStart > $parsedPendingStartTime ){
                                // check if the starting time of the current trip is not up to the 1hr grace given to the ending of the pending trip,
                                //  Save the trip in contradictingStartTime
                                if($tripStart < $parsedPendingEndGrace){
                                    $contradictingEndTime[] = $pendingTrips; 
                                    
                                    //if there is any trip saved in contradictingEndTime which means that there is at least a trip
                                    // that the current trip's start time is more than its ending time
                                    if(count($contradictingEndTime) > 0 ){
                                        // If there is a trip contrdicting with the time of the new trip, delete current trip and throw an error
                                        $trip->delete();
                                        return back()->with('error', 'The vehicle is booked for a trip which will not end before your trip starts. 
                                        Please set your trip to end earlier or you pick a different car if there\'s or you request for an emergency trip');
                                    }
                                }
                                
                            }
                        }
                    

                        if(count($contradictingStartTime) > 0 ){
                            // If there is a trip contrdicting with the time of the new trip
                            $trip->delete();
                            return back()->with('error', 'The vehicle is booked for a trip that is scheduled to start before your trip ends. 
                            Please set your trip to end earlier or you pick a different car if there\'s or you request for an emergency trip');
                        }

                        if(count($contradictingEndTime) > 0 ){
                            // If there is a trip contrdicting with the time of the new trip
                            $trip->delete();
                            return back()->with('error', 'The vehicle is booked for a trip which will not end before your trip starts. 
                            Please set your trip to end earlier or you pick a different car if there\'s or you request for an emergency trip');
                        }
                    }
                    // dd($parsedStartGrace);

                    // dd($pendingTrips);
                    // Checking if the available drivers are more than 1
                    if(count($drivers) > 1 ){
                        // subtract 1 from the total number of the available drivers
                        $x = count($drivers) - 1;
                

                        
                        // Checking if any of the drivers has gone on any trip previously
                        if(count($completedTrips) > 0 ){
                            $trips = Trip::orderBy('trip_ended_time', 'desc')->where('trip_request_id', 4)->where('department_id', $department)->take($x)->get();
                        } else{
                            $trips = Trip::orderBy('trip_start_time', 'asc')->where('id', '!=', $trip->id)->where('trip_request_id', 2)->where('department_id', $department)->take($x)->get();
                        } 
                        // Getting the last $x completed trips in the departmnent in the order of the time they ended
                            The latest trips that ended come first //
                        $trips = Trip::orderBy('trip_ended_time', 'desc')->where('trip_request_id', 4)->where('department_id', $department)->take($x)->get();
                        
                        // Getting the drivers that drove in the trips above. i.e $busyDrivers
                        $busyDrivers = [];
                        foreach($trips as $atrip){
                            for($y = 0; $y < count($drivers); $y++){
                                if($drivers[$y]->id === $atrip->driver()->first()['id']){
                                    $busyDrivers[] = $drivers[$y];
                                }
                            }
                            // $busyDrivers++;
                        }
                        // dd($busyDrivers);

                        // Save the ids of the bussy drivers
                        foreach($busyDrivers as $busyDriver){
                            $busyDrivers_id[] = $busyDriver->id;
                        }
                        // Save the ids of all the available drivers
                        foreach($drivers as $driver){
                            $drivers_id[] = $driver->id;
                        }

                        // Run an array_diff function for the 2 set of ids to get the id of the available driver that has not been busy
                        $result = array_diff($drivers_id, $busyDrivers_id);

                        // the available driver that has not been  busy in the last $x trips
                        $driver = Driver::find($result)->first();

                    } elseif(count($drivers) === 1 ){
                        // If there is only 1 available driver at the moment
                        $driver = Driver::where('department_id' , $department)->where('car_availability_id' , $driver_availability)->first();
                    } elseif(count($drivers) < 1 ){
                        // If there is no available driver at the moment
                        $trip->delete();
                        return back()->with('error', 'All the drivers in your department are currently unavailable. Request for an emergency trip if the trip can\'t wait');
                    }

                } else{
                    $driver = Driver::where('department_id' , $department)->where('car_availability_id' , $driver_availability)->first();
                }
                } else{
                    $driver = Driver::where('department_id' , $department)->where('car_availability_id' , $driver_availability)->first();
                }

                // Change the Availability status of the booked car
                $car = Car::where('id', $trip->car_id)->first();
                $car->car_availability_id = 3;
                $car->save();

                // attach user to trip
                $trip->user()->attach($user->id);


                // change the availability status of the chosen driver for the new trip
                $driver->car_availability_id = 3;
                $driver->save();

                //send notification email to the user that trip s created. Use either of thr 2 lines
                /* $user->notify(new TripCreated($trip));
                or request()->user()->notify(new TripCreated()); 
            */

            
            $tripId = $trip->id;
            $userId = $user->id;
            $user->trip()->attach($tripId );
            
            //send notifications
            $user->notify(new TripCreated($trip));
            //send email
            SendCreatedMail::dispatch($userId, $tripId);

            return redirect('/mytrips')->with('success', 'Your Trip has been created successfully');
        
        
    }

    public function index()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        $user = Auth::user();
        $trips = $user->trip()->orderBy('created_at', 'desc')->paginate(20);
        $car_statuses = Car_health_status::all();
        
        return view('trips.index', compact('user', 'trips', 'car_statuses'));
    }

    public function addTripReport(Request $request, Trip $trip)
    {

        $this->validate($request, [
            'summary' => 'required',
            'complaints' => 'nullable',
            'accident' => 'nullable',
        ]);

        $trip->summary = $request['summary'];
        $trip->complaints = $request['complaints'];
        $trip->accident = $request['accident'];
        $trip->car_health_status_id = $request['car_status'];
        $trip->save();

        return back()->with('success', 'You have successful submitted your report on your last trip');
    }

    public function cancelTrip(Request $request, Trip $trip)
    {

        // dd('Hey whastsupp niggie');
        $trip->trip_request_id = 5;
        $trip->save();

        //CHANGING THE CANCELLED TRIP CAR'S AVAILABILITY
        $car = $trip->car;
        //Get all the pending trips that the car is attached to
        $pendingTrips = $car->trip()->where('trip_request_id', 2)->get();

        // check if there is any pending trip that the car is attached to
        // If there's, car's availabilty status should be booked, else the car is available
        if(count($pendingTrips) > 0){
            $car->car_availability_id = 3;
        } else{
            $car->car_availability_id = 1;
        }
        $car->save();
        // dd($car);

        //CHANGING THE SWITCHED ON TRIP DRIVER'S AVAILABILITY
        $id = $trip->driver()->first()['id'];
        $driver = Driver::find($id);
        //Get all the pending trips that the driver is attached to
        $pendingDrvTrips = $driver->trip()->where('trip_request_id', 2)->get();

        // check if there is any pending trip that the driver is attached to
        // If there's, driver's availabilty status should be booked, else the driver is available
        if(count($pendingDrvTrips) > 0){
            $driver->car_availability_id = 3;
        } else{
            $driver->car_availability_id = 1;
        }
        $driver->save();

        //send notifications
        $user = Auth::user();
        $user->notify(new TripCancelled($trip));
        //send email
        // SendCreatedMail::dispatch($userId, $tripId);
       

        return back()->with('success', 'You have successfully cancelled trip - ID:'.$trip->id);
    }

    public function tripSwitch()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::allows('admin')|| Gate::allows('receptionist')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        } 

        // Autoatic expiration of pending trips that have elapsed 15mins grace after the proposed starting time
        $pendingTrips = Trip::where('trip_request_id',  2)->get();
        if(count($pendingTrips) > 0){
            foreach($pendingTrips as $pendingTrip){
                //Get the carbonized extra time grace of pending trip
                $parsedExtraTime = Carbon::parse($pendingTrip->extra_started_grace);
                //Get the carbonized current time
                $now = Carbon::now();
        
                //Check if it's more than 15mins a trip should have started
                if($now > $parsedExtraTime){
                    $pendingTrip->trip_request_id = 7;
                    $pendingTrip->save();

                    //car of the trip
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
            
                    //CHANGING THE SWITCHED ON TRIP DRIVER'S AVAILABILITY
                    $driver = $pendingTrip->driver;
                    // $driver = Driver::find($id);
                    //Get all the pending trips that the driver is attached to
                    $pendingDrvTrips = $driver->trip()->where('trip_request_id', 2)->get();
            
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

        $activeids = [2,3];
        $trips = Trip::orderBy('created_at', 'desc')->whereIn('trip_request_id',  $activeids)->get();
        
        return view('trips.switch-trip', compact('trips'));
    }

    public function switchOnTrip(Request $request, Trip $trip)
    {
        if (Gate::allows('admin')|| Gate::allows('receptionist')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        } 

        
        $trip->trip_request_id = 3;
        $trip->trip_started_time = date("H:i:s", time());
        $trip->trip_started_date = Carbon::now();
        $trip->save();

        $car = Car::find($trip->car->id);
        $car->car_availability_id = 2;
        $car->save();

        $id = $trip->driver()->first()['id'];
        $driver = Driver::find($id);
        $driver->car_availability_id = 2;
        $driver->save();

        $user = $trip->user()->first();
        $user->notify(new TripSwitchedOn($trip));

        $userId = $user->id;
        $tripId = $trip->id;

        if(Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time)) < 60 ){
            $estmTime = Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'minutes';
        } else{
            $estmTime = Carbon::parse($trip->trip_end_time)->diffInHours(Carbon::parse($trip->trip_start_time)). 'hrs,' . ' '.
            Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'mins';
        };
        // SendSwitchedOn::dispatch($userId, $tripId, $estmTime);

        return back()->with('success', 'You have successfully started trip - ID:' . ' '.$trip->id);
    }

    public function switchOffTrip(Request $request, Trip $trip)
    {
        if (Gate::allows('admin')|| Gate::allows('receptionist')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        } 

        // dd('Hey whastsupp niggie');
        $trip->trip_request_id = 4;
        $trip->trip_ended_time = date("H:i:s", time());
        $trip->trip_ended_date = Carbon::now();
        $trip->save();

        //CHANGING THE SWITCHED ON TRIP CAR'S AVAILABILITY
        $car = $trip->car;
        //Get all the pending trips that the car is attached to
        $pendingTrips = $car->trip()->where('trip_request_id', 2)->get();

        // check if there is any pending trip that the car is attached to
        // If there's, car's availabilty status should be booked, else the car is available
        if(count($pendingTrips) > 0){
            $car->car_availability_id = 3;
        } else{
            $car->car_availability_id = 1;
        }
        $car->save();
       
        
        //CHANGING THE SWITCHED ON TRIP DRIVER'S AVAILABILITY
        $id = $trip->driver()->first()['id'];
        $driver = Driver::find($id);
        //Get all the pending trips that the driver is attached to
        $pendingDrvTrips = $driver->trip()->where('trip_request_id', 2)->get();

        // check if there is any pending trip that the driver is attached to
        // If there's, driver's availabilty status should be booked, else the driver is available
        if(count($pendingDrvTrips) > 0){
            $driver->car_availability_id = 3;
        } else{
            $driver->car_availability_id = 1;
        }
        $driver->save();

        $user = $trip->user()->first();
        $user->notify(new TripSwitchedOff($trip));

        $userId = $user->id;
        $tripId = $trip->id;
        
        if(Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date)) < 60 ){
            $usedTime = Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'minutes';
        }else{
                $usedTime = Carbon::parse($trip->trip_ended_date)->diffInHours(Carbon::parse($trip->trip_started_date)). 'hrs,' . ' '.
            Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'mins';
        };
        // SendSwitchedOff::dispatch($userId, $tripId, $usedTime);

        return back()->with('success', 'trip - ID ' . $trip->id . ' ' . 'has been stopped');
    }

    public function allTrips()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        
        $trips = Trip::orderBy('created_at', 'desc')->paginate(20);
        $cars = Car::orderBy('name')->get();
        // dd($cars);
        $lgas = LGA::orderBy('name')->get();
        $drivers = Driver::all();

        return view('trips.admin.alltrips', compact('trips', 'cars', 'lgas', 'drivers'));
    }

    public function updateTrip(Request $request, Trip $trip)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $currentNow = Carbon::now();
        $start = date("Y-m-d H:i:s",strtotime($request['trip_date'].$request['trip_start_time']));
        $end = date("Y-m-d H:i:s", strtotime($request['trip_date'].$request['trip_end_time']));

        $this->validate($request, [
            'lga' => 'required',
            'address' => 'required',
            'car' => 'required',
            'trip_date' => 'required',
            'trip_start_time' => 'required',
            'trip_end_time' => 'required',
        ]);

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

       
        // trip's previous car availability
        $prevCar = Car::find($trip->car_id);
        $prevCar->car_availability_id = 1;
        $prevCar->save();

        // remove previous driver from the trip
        $prevDriver = $trip->driver;
        // Change the Availability status of the previous driver
        $prevDriver->car_availability_id = 1;
        $prevDriver->save();

        // update trip inputs in trip table
        $trip->lga_id = $request['lga'];
        $trip->address = $request['address'];
        $trip->car_id = $request['car'];
        $trip->trip_date = $request['trip_date'];
        $trip->driver_id =  $request['driver'];
        $trip->trip_start_time = $request['trip_start_time'];
        $trip->trip_end_time = $request['trip_end_time'];
        $trip->save();

        // Change the Availability status of the booked car
        $car = Car::find($trip->car_id);
        $car->car_availability_id = 3;
        $car->save();

        $newDriver_id = $request['driver'];
        // change the availability status of the new driver
        $newDriver = Driver::find($newDriver_id);
        $newDriver->car_availability_id = 3;

        return back()->with('success', 'Trip Updated Successfully');

    }

    public function denyTrip(Request $request, Trip $trip)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        // dd('Hey whastsupp niggie');
        $trip->trip_request_id = 6;
        $trip->save();

        //CHANGING THE SWITCHED ON TRIP CAR'S AVAILABILITY
        $car = $trip->car;
        //Get all the pending trips that the car is attached to
        $pendingTrips = $car->trip()->where('trip_request_id', 2)->get();

        // check if there is any pending trip that the car is attached to
        // If there's, car's availabilty status should be booked, else the car is available
        if(count($pendingTrips) > 0){
            $car->car_availability_id = 3;
        } else{
            $car->car_availability_id = 1;
        }
        $car->save();
        
        
        //CHANGING THE SWITCHED ON TRIP DRIVER'S AVAILABILITY
        $id = $trip->driver()->first()['id'];
        $driver = Driver::find($id);
        //Get all the pending trips that the driver is attached to
        $pendingDrvTrips = $driver->trip()->where('trip_request_id', 2)->get();

        // check if there is any pending trip that the driver is attached to
        // If there's, driver's availabilty status should be booked, else the driver is available
        if(count($pendingDrvTrips) > 0){
            $driver->car_availability_id = 3;
        } else{
            $driver->car_availability_id = 1;
        }
        $driver->save();

        $user = $trip->user()->first();
        $userId = $user->id;
        $tripId = $trip->id;

        $user->notify(new TripDenied($trip));
        SendDenyTrip::dispatch($userId, $tripId);
        

        return back()->with('success', 'You have successfully denied trip - ID:'.$trip->id . 'request');
    }



}
