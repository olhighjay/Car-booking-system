<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Image;

use Illuminate\Http\Request;
use App\User;
use App\Driver;
use App\Offense;
use App\Department;
use App\Car;
use App\Car_report;
use App\Driver_report;
use App\Carimage;
use App\Car_report_image;
use App\Car_Availability;
use App\Car_health_status;
use App\Trip_request;
use App\LGA;
use App\Trip;



class ReportController extends Controller
{

    public function __construct(){
        $this->middleware(['auth','verified']);
    }
   
    public function reportsDisplay()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        
        return view('reports.index');
    }


    public function createDriverReport()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        $drivers = Driver::orderBy('name')->get();
        $offenses = Offense::all();
        // dd($offenses);
        return view('reports.driver.new', compact('drivers', 'offenses'));
    }

    public function storeDriverReport(Request $request)
    {

        $this->validate($request, [
            'driver' => 'required',
            'offense' => 'required',
            'note' => 'required',
            'correction' => 'nullable'
        ]);

        $driver_report = new Driver_report();
        $driver_report->driver_id = $request['driver'];
        $driver_report->offense_id = $request['offense'];
        $driver_report->review = $request['note'];
        $driver_report->punishment = $request['correction'];
        $driver_report->save();
        // dd($offenses);
        return redirect('/driverreports')->with('success', 'Your report has been succesfully submitted');
    }

    public function driverReportIndex()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $reports = Driver_report::orderBy('created_at', 'desc')->paginate(30);
        // $reports = Driver_report::all()->orderBy('created_at', 'desc');
        // dd($reports);
        return view('reports.driver.index', compact('reports'));
    }

    public function showDriverReport(Driver_report $report)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        return view('reports.driver.show', compact('report'));
    }


    
    public function createCarReport()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        $cars = Car::all();
        $healths = Car_health_status::all();
        // dd($offenses);
        return view('reports.car.new', compact('cars', 'healths'));
    }

    public function storeCarReport(Request $request)
    {

        $user = Auth::user();

        $this->validate($request, [
            'car' => 'required',
            'car_status' => 'required',
            'note' => 'required',
            'correction' => 'nullable',
            // 'images' => 'required'
        ]);

        if($request->hasFile('images')) {
            if(count($request['images']) > 4){
                return back()->with('error', 'Vehicle Pictures can\'t be more 4.'.' '. 'Please refill the form');
            }
        }

        $car_report = new Car_report();
        $car_report->user_id = $user->id;
        $car_report->car_id = $request['car'];
        $car_report->car_health_status_id = $request['car_status'];
        $car_report->review = $request['note'];
        $car_report->solution = $request['correction'];
        $car_report->save();
        // dd($offenses);

        if($request['car_status'] == 3){
            $car = Car::find($request['car']);
            $car->car_health_status_id = 3;
            // dd($car->car_health_status_id);
            $car->car_availability_id = 2;
            $car->save();
        }


        if($request->hasFile('images')) {

            foreach($request->file('images') as $image){
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
                Storage::put('public/images/car_reports/'. $filenametostore, fopen($image, 'r+'));
                Storage::put('public/images/car_reports/thumbnail/'. $filenametostore, fopen($image, 'r+'));
                // $path = $image->storeAs('public/cover_images', $fileNameToStore);

                //     //Resize image here
                $thumbnailpath = 'storage/images/car_reports/thumbnail/'.$filenametostore;
                $img = Image::make($thumbnailpath)->resize(100, 100, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);
                
                $car_report_image = new Car_report_image();
                $car_report_image->name = $filenametostore;
                $car_report_image->car_id =  $car_report->car_id;
                $car_report_image->car_report_id =  $car_report->id;
                $car_report_image->save();

                // $carimage_id = $carimage->id;

                // $car->carimage()->attach($carimage_id);

            }
            
        }
        return redirect('/')->with('success', 'Your report has been succesfully submitted');
    }

    public function showCarReport(Car_report $report)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $car_thumbnails = $report->car_report_image()->get();
        // dd($reportImages);
        // $reportImages = Carimage::where('car_report_id', $report)->first()['id'];
        // foreach($reportImages as $reportImage){
        //     $reportPictures[] = $reportImage->name;
        // }
        // dd($reportImagesz);
        return view('reports.car.show', compact('report', 'car_thumbnails'));
    }

    public function carReportIndex()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }

        $reports = Car_report::orderBy('created_at', 'desc')->paginate(30);
        return view('reports.car.index', compact('reports'));
    }



    public function tripReportIndex()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $trips = Trip::orderBy('created_at', 'desc')->where('summary', '!=', null)->paginate(30);
        // $reports = Driver_report::all()->orderBy('created_at', 'desc');
        // dd($trips);
        return view('reports.trip.index', compact('trips'));
    }

    public function tripReportShow(Trip $trip)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        
        // $trip = Trip::orderBy('created_at', 'desc')->where('summary', '!=', null)->paginate(30);
        // $reports = Driver_report::all()->orderBy('created_at', 'desc');
        if($trip->summary == null){
            return back()->with('error', 'no trip report available');
        }
        return view('reports.trip.show', compact('trip'));
    }


  



}
