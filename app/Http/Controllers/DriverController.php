<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;

use Illuminate\Http\Request;
use App\Driver;
use App\Role;
use App\Carimage;
use App\Department;
use App\Car;
use App\Car_Availability;
use App\Car_health_status;
use App\Trip_request;
use Illuminate\Support\Facades\Gate;


class DriverController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    public function createDriver()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $departments = Department::all();
        return view('users.drivers.new', compact('departments'));
    }

    public function storeDriver(Request $request)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $this::validate($request, [
            'name' => ['required', 'max:255'],
            'phone_number' => ['unique:drivers','required','max:15'],
            'department'=> 'required',
        ]);

        $user = new Driver();
        $user->name = $request['name'];
        $user->phone_number = $request['phone_number'];
        $user->department_id =$request['department'];
        $user->display_picture = 'default-avatar.png';
        $user->save();

        return redirect('/drivers')->with('success', 'You have successfully created added new Driver');
    }


    public function indexDriver()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $drivers= Driver::orderBy('name')->paginate(20);
        $departments = Department::all();
        $car_availabilities = Car_availability::all();
        $cars = Car::all();
        // dd($excos->suspension->name);
        return view('users.drivers.index', compact('departments', 'drivers', 'car_availabilities', 'cars'));    
    }

    public function myDriver()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        $user = Auth::user();
        $drivers= Driver::where('department_id', $user->department_id)->orderBy('name')->get();
        $departments = Department::all();
        $car_availabilities = Car_availability::all();
        $cars = Car::all();
        // dd($excos->suspension->name);
        return view('users.drivers.mydrivers', compact('departments', 'drivers', 'car_availabilities', 'cars'));    
    }

    public function showDriver(Driver $driver)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        $statuses = Car_availability::all();
        $departments = Department::all();
        return view('users.drivers.show', compact('driver', 'departments', 'statuses'));
    }

    public function updateDriverAvailability(Request $request, Driver $driver)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $this::validate($request, [
            'driver_availability' => ['required'],
        ]);

        $ongoingTrips = $driver->trip()->where('trip_request_id', 3)->get();
        if(count($ongoingTrips) > 0)
        {
            return back()->with('error', 'You can\'t change the availability of a driver currently on a trip');
        }
        $driver->car_availability_id = $request['driver_availability'];
        $driver->save();
        return back()->with('success', $driver->name.'\'s status' . ' ' . 'updated successfully');
    }

    public function AddCarToDriver(Request $request, Driver $driver)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $driver->car_id = $request['car'];
        $driver->save();
        $car = $request['car'];
        // dd($user->car->name);
        // if($car){
        // dd($car );
        // }else{
        //     return 'how are you';
        // }
        return back()->with('success', $driver->car->name . ' ' . 'has been added to' . ' ' . $driver->name . ' ' . 'successfully');
    }

    public function updateDriverDept(Request $request, Driver $driver)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $this::validate($request, [
            'department' => 'required',
        ]);

        $driver->department_id = $request['department'];
        $driver->save();
        return back()->with('success', $driver->name.'\'s department' . ' ' . 'updated successfully');
    }

    public function updateDriverProfile(Request $request, Driver $driver)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $this::validate($request, [
            'profile_picture' => 'required|image|max:1999'
        ]);
    
            #Save profile picture on database
        //Handle file upload
        if($request->hasFile('profile_picture')) {

            $image = $request->file('profile_picture');
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
            Storage::put('public/profile-picture/'. $filenametostore, fopen($image, 'r+'));
            Storage::put('public/profile-picture/thumbnail/'. $filenametostore, fopen($image, 'r+'));
            // $path = $image->storeAs('public/cover_images', $fileNameToStore);

                //Resize image here
            $thumbnailpath = 'storage/profile-picture/thumbnail/'.$filenametostore;
            $img = Image::make($thumbnailpath)->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);
            // Storage::put('public/images/car/'. $filenametostore, fopen($image, 'r+'));
            //     Storage::put('public/images/car/thumbnail/'. $filenametostore, fopen($image, 'r+'));
            //     // $path = $image->storeAs('public/cover_images', $fileNameToStore);

            //         //Resize image here
            //     $thumbnailpath = 'storage/images/car/thumbnail/'.$filenametostore;
            //     $img = Image::make($thumbnailpath)->resize(100, 100, function($constraint) {
            //         $constraint->aspectRatio();
            //     });
            //     $img->save($thumbnailpath);
            
        }

        $driver->display_picture = $filenametostore;
        $driver->save();
        return back()->with('success', $driver->name.'\'s display picture' . ' ' . 'updated successfully');
    }

    public function fullUpdate(Request $request, Driver $driver)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $this::validate($request, [
            'name' => ['required', 'max:255'],
            'phone_number' => ['required'],
            'department'=> 'nullable',
            'status'=> 'nullable',
        ]);

        $driver->name = $request['name'];
        $driver->phone_number = $request['phone_number'];
        $driver->department_id =$request['department'];
        $driver->car_availability_id =$request['status'];
        $driver->save();

        return back()->with('success', 'driver updated successfully');
    }


    public function destroyDriver(Driver $driver)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        
        $driver->delete();
        return back()->with('success', 'driver deleted successfully');
    }



}