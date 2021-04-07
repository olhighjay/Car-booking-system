<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Image;

use Illuminate\Http\Request;
use App\User;
use App\Department;
use App\Car;
use App\Carimage;
use App\Car_Availability;
use App\Car_health_status;
use App\Trip_request;
use App\LGA;
use App\Trip;
use App\Driver;
use Carbon\Carbon;
use App\Fuel;
use App\Repair;




class CarController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','verified']);
    }


     //CAR
    public function createCar()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }    

        $data = [
        'departments' => Department::all(),
        'healths' => Car_health_status::all(),
        'availabilities' => Car_availability::all(),
        ];

        return view('cars.new')->with($data);
    }


    public function storeCar(Request $request)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        #input requirement check/partial authentication
        $this->validate($request, [
            'name'=>'required',
            'description'=>'nullable',
            'properties'=> 'required',
            'plate_number'=> ['required', 'unique:cars'],
            'department'=> 'required',
            'health'=> 'nullable',
            'availablity'=> 'nullable',
            'images'=> 'required'
        ]);
        //Check if uploaded images are not more than 4
        // if(count($request['images']) > 4){
        //     return back()->with('error', 'Vehicle Pictures can\'t be more 4.'.' '. 'Please refill the form');
        // }
        #To save the request data in database table
        $car = new Car();
        $car->name = $request['name'];
        $car->description = $request ['description'];
        $car->properties = $request ['properties'];
        $car->plate_number = $request ['plate_number'];
        $car->department_id = $request ['department'];
        $car->car_health_status_id = $request ['health'];
        $car->car_availability_id = $request ['availability'];
        $car->save();

        
        
        if($request->hasFile('images')) {
            $image = $request['images'];
            // foreach($request->file('images') as $image){
                //get filename with extension
                $filenamewithextension = $image->getClientOriginalName();

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                //get file extension
                $extension = $image->getClientOriginalExtension();

                //filename to store
                $filenametostore = $filename.'_'.uniqid().'.'.$extension;
                
                    //Upload File
                // $path = $image->storeAs('public/images/car', $filenametostore);
                // $path = $image->storeAs('public/images/car/thumbnail', $filenametostore);
                Storage::put('public/images/car/'. $filenametostore, fopen($image, 'r+'));
                Storage::put('public/images/car/thumbnail/'. $filenametostore, fopen($image, 'r+'));
                // $path = $image->storeAs('public/cover_images', $fileNameToStore);

                    //Resize image here
                $thumbnailpath = 'storage/images/car/thumbnail/'.$filenametostore;
                $img = Image::make($thumbnailpath)->resize(100, 100, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);
                
                $carimage = new Carimage();
                $carimage->name = $filenametostore;
                $carimage->car_id = $car->id;
                $carimage->save();

                // $carimage_id = $carimage->id;

                // $car->carimage()->attach($carimage_id);

            // }
        }
        return redirect('/cars')->with('success', "Car created successfully."); //change the route as per your flow
    }

    public function indexCar()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        $cars = Car::orderBy('created_at', 'desc')->paginate(20);
        $faulty_cars = Car::orderBy('created_at', 'desc')->where('car_health_status_id', 3)->get();
        // dd($faulty_cars);
        $available_cars = Car::orderBy('created_at', 'desc')->where('car_availability_id', 1)->get();
        // dd($available_cars);
        return view('cars.index', compact('cars', 'faulty_cars', 'available_cars'));
    }


    public function staffIndexCar(){
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        $user = Auth::user();
        $cars = Car::orderBy('created_at', 'desc')->where('department_id', $user->department_id)->get();
        // dd($cars); 
        
        // Checking if there is any car attached to user's department yet
        if(count($cars) > 0)
        {
            //Getting the the cars that are still available or booked but not in use yet
            foreach($cars as $car){
                if($car->car_availability_id == 1 || $car->car_availability_id == 3){
                    $available_cars[] = $car;
                }
            }
            // dd($available_cars);
            if($available_cars ?? ''){
            return view('cars.staff.index', compact('cars', 'available_cars'));
            }
        }
        // dd($available_cars);
        return view('cars.staff.index', compact('cars', ));
    }



    public function bookCar(Car $pickedCar)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        if($pickedCar->car_availability->id !== 2)
        {
            $user = Auth::user();
            $lgas = LGA::orderBy('name')->get();
            // All Cars that User could have access to
            $allCars = Car::orderBy('name')->where('department_id', $user->department_id)->get();
            // Checking if there is any car attached to user's department yet
            if(count($allCars) > 0)
            {
                //Getting the the cars that are still available or booked but not in use yet
                foreach($allCars as $allCar){
                    if($allCar->car_availability_id === 1 || $allCar->car_availability_id === 3){
                        // Cars that are currently available for trip
                        $cars[] = $allCar;
                    }
                }
            };

            return view('cars.book', compact('user', 'lgas', 'cars', 'pickedCar'));
        }

        return redirect('/mycars')->with('error', 'The Vehicle you are trying to book is currently unaivalable!');
    }

    public function showCar(Car $car){

        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        $car_image = $car->carimage()->first()->name;
        $car_thumbnails = $car->carimage()->get();
        $trips = Trip::where('car_id', $car->id)->where('trip_request_id', 4)->get();
        $drivers = Driver::where('car_id', $car->id)->get();
        $lastTrip = Trip::orderBy('trip_ended_date', 'desc')->where('trip_request_id', 4)->where('car_id', $car->id)->first();

        $last_30_days = Carbon::today()->subDays(30);
        $ltdTrips = Trip::where('trip_ended_date','>=',$last_30_days)->where('car_id', $car->id)->where('trip_request_id', 4)->get();

        $month = date('m');
        $records = Fuel::whereMonth('created_at', $month)->where('car_id', $car->id)->get();
        $totalVolume = 0;
        $totalAmount = 0;
        for($x=0; $x < count($records); $x++)
        {
            $totalVolume += $records[$x]->volume;
            $totalAmount += $records[$x]->amount;
        }

        $repRecords = Repair::whereMonth('created_at', $month)->where('car_id', $car->id)->get();
        $totalRepAmount = 0;
        for($x=0; $x < count($repRecords); $x++)
        {
            $totalRepAmount += $repRecords[$x]->amount;
        }
        // dd($ltdTrips);
        return view('cars.show', compact('records', 'repRecords', 'totalVolume' ,'totalRepAmount', 'totalAmount', 'car', 'car_image', 'trips', 'car_thumbnails', 'drivers', 'lastTrip', 'ltdTrips'));
    }

    public function carTripsForRequestRecords(Request $request, Car $car)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::allows('admin')|| Gate::allows('accountant')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $this->validate($request, [
            'month' => 'required',
        ]);


        $car_image = $car->carimage()->first()->name;
        $car_thumbnails = $car->carimage()->get();
        $trips = Trip::where('car_id', $car->id)->where('trip_request_id', 4)->get();
        $drivers = Driver::where('car_id', $car->id)->get();
        $lastTrip = Trip::orderBy('trip_ended_date', 'desc')->where('trip_request_id', 4)->where('car_id', $car->id)->first();

        $month = $request['month'];
        $date = Carbon::createFromFormat('Y-m', $month);
        $recordTrips = Trip::where('car_id', $car->id)->whereYear('trip_ended_date', $date->year)->whereMonth('trip_ended_date', $date->month)->where('trip_request_id', 4)->get();
        // dd($recordTrips);

        $currentMonth =  date('m');
        $records = Fuel::whereMonth('created_at', $currentMonth)->where('car_id', $car->id)->get();
        $totalVolume = 0;
        $totalAmount = 0;
        for($x=0; $x < count($records); $x++)
        {
            $totalVolume += $records[$x]->volume;
            $totalAmount += $records[$x]->amount;
        }

        $repRecords = Repair::whereMonth('created_at', $currentMonth)->where('car_id', $car->id)->get();
        $totalRepAmount = 0;
        for($x=0; $x < count($repRecords); $x++)
        {
            $totalRepAmount += $repRecords[$x]->amount;
        }
        
        return view('cars.trips', compact('recordTrips', 'repRecords', 'records', 'totalVolume' ,'totalRepAmount', 'totalAmount',  'month', 'car', 'car_image', 'trips', 'car_thumbnails', 'drivers', 'lastTrip',));
    }

    public function carFuelRequestRecords(Request $request, Car $car)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        $this->validate($request, [
            'month' => 'required',
        ]);


        $car_image = $car->carimage()->first()->name;
        $car_thumbnails = $car->carimage()->get();
        $trips = Trip::where('car_id', $car->id)->where('trip_request_id', 4)->get();
        $drivers = Driver::where('car_id', $car->id)->get();
        $lastTrip = Trip::orderBy('trip_ended_date', 'desc')->where('trip_request_id', 4)->where('car_id', $car->id)->first();

        $last_30_days = Carbon::today()->subDays(30);
        $ltdTrips = Trip::where('trip_ended_date','>=',$last_30_days)->where('car_id', $car->id)->where('trip_request_id', 4)->get();

        $month = $request['month'];
        $date = Carbon::createFromFormat('Y-m', $month);
        $recordFuels = Fuel:: whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->where('car_id', $car->id)->get();
        // dd($recordFuels);
        $totalVolume = 0;
        $totalAmount = 0;
        for($x=0; $x < count($recordFuels); $x++)
        {
            $totalVolume += $recordFuels[$x]->volume;
            $totalAmount += $recordFuels[$x]->amount;
        }

        $currentMonth =  date('m');
        $repRecords = Repair::whereMonth('created_at', $currentMonth)->where('car_id', $car->id)->get();
        $totalRepAmount = 0;
        for($x=0; $x < count($repRecords); $x++)
        {
            $totalRepAmount += $repRecords[$x]->amount;
        }
        // dd($ltdTrips);
        return view('cars.fuelrecords', compact('recordFuels', 'totalVolume' , 'totalAmount', 'totalRepAmount', 'repRecords','month', 'car', 'car_image', 'trips', 'car_thumbnails', 'drivers', 'lastTrip', 'ltdTrips'));
    }

    public function carRepairRequestRecords(Request $request, Car $car)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        $this->validate($request, [
            'month' => 'required',
        ]);


        $car_image = $car->carimage()->first()->name;
        $car_thumbnails = $car->carimage()->get();
        $trips = Trip::where('car_id', $car->id)->where('trip_request_id', 4)->get();
        $drivers = Driver::where('car_id', $car->id)->get();
        $lastTrip = Trip::orderBy('trip_ended_date', 'desc')->where('trip_request_id', 4)->where('car_id', $car->id)->first();

        $last_30_days = Carbon::today()->subDays(30);
        $ltdTrips = Trip::where('trip_ended_date','>=',$last_30_days)->where('car_id', $car->id)->where('trip_request_id', 4)->get();

        $currentMonth =  date('m');
        $recordFuels = Fuel:: whereMonth('created_at', $currentMonth)->where('car_id', $car->id)->get();
        // dd($recordFuels);
        $totalVolume = 0;
        $totalAmount = 0;
        for($x=0; $x < count($recordFuels); $x++)
        {
            $totalVolume += $recordFuels[$x]->volume;
            $totalAmount += $recordFuels[$x]->amount;
        }

        $month = $request['month'];
        $date = Carbon::createFromFormat('Y-m', $month);
        $repRecords = Repair::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->where('car_id', $car->id)->get();
        $totalRepAmount = 0;
        for($x=0; $x < count($repRecords); $x++)
        {
            $totalRepAmount += $repRecords[$x]->amount;
        }
        // dd($ltdTrips);
        return view('cars.repairrecords', compact('recordFuels', 'totalVolume' , 'totalAmount', 'totalRepAmount', 'repRecords','month', 'car', 'car_image', 'trips', 'car_thumbnails', 'drivers', 'lastTrip', 'ltdTrips'));
    }

    public function destroyCar(Car $car)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $car->delete();
        return redirect('cars')->with('success', 'Car deleted successfully');
    }

    public function editCar(Car $car)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        } 

        $data = [
            'departments' => Department::all(),
            'healths' => Car_health_status::all(),
            'availabilities' => Car_availability::all(),
            'car' => $car
            ];
            // dd($data);
        return view('cars.edit')->with($data)->with('car', $car);
    }

    public function updateCar(Request $request, Car $car)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        #input requirement check/partial authentication
        $this->validate($request, [
            'name'=>'required',
            'description'=>'nullable',
            'properties'=> 'required',
            'department'=> 'required',
            'plate_number'=> 'required',
            'health'=> 'nullable',
            'availablity'=> 'nullable',
            'images'=> 'nullable'
        ]);
        //Check if uploaded images are not more than 4
        // if($request->hasFile('images')){
        //     if(count($request['images']) > 4){
        //         return back()->with('error', 'Vehicle Pictures can\'t be more 4.'.' '. 'Please refill the form');
        //     }
        // }
        #To save the request data in database table
        $car->name = $request['name'];
        $car->description = $request ['description'];
        $car->properties = $request ['properties'];
        $car->plate_number = $request ['plate_number'];
        $car->department_id = $request ['department'];
        $car->car_health_status_id = $request ['health'];
        $car->car_availability_id = $request ['availability'];
        $car->save();

        
        if($request->hasFile('images')) {
            $oldImages = Carimage::where('car_id', $car->id)->get();
            foreach($oldImages as $oldImage){
                $oldImage->delete();
            }
            $image = $request['images'];
            // foreach($request->file('images') as $image){
                //get filename with extension
                $filenamewithextension = $image->getClientOriginalName();

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                //get file extension
                $extension = $image->getClientOriginalExtension();

                //filename to store
                $filenametostore = $filename.'_'.uniqid().'.'.$extension;
                
                    //Upload File
                // $path = $image->storeAs('public/images/car', $filenametostore);
                // $path = $image->storeAs('public/images/car/thumbnail', $filenametostore);
                Storage::put('public/images/car/'. $filenametostore, fopen($image, 'r+'));
                Storage::put('public/images/car/thumbnail/'. $filenametostore, fopen($image, 'r+'));
                // $path = $image->storeAs('public/cover_images', $fileNameToStore);

                    //Resize image here
                $thumbnailpath = 'storage/images/car/thumbnail/'.$filenametostore;
                $img = Image::make($thumbnailpath)->resize(100, 100, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);
                
                $carimage = new Carimage();
                $carimage->name = $filenametostore;
                $carimage->car_id = $car->id;
                $carimage->save();

                // $carimage_id = $carimage->id;

                // $car->carimage()->attach($carimage_id);

            // }
        }
        return redirect('cars/'.$car->id)->with('success', "Car updated successfully."); //change the route as per your flow

    
    }




}
