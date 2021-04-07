<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Car;
use App\Fuel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;



class FuelController extends Controller
{
    
    public function __construct(){
        $this->middleware(['auth','verified']);
    }


    public function saveFuelRecord( Request $request, Car $car)
    {
        if (Gate::allows('admin')|| Gate::allows('accountant')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        $user = Auth::user();

        $this->validate($request, [
            'volume' => 'required',
            'amount' => 'required',
        ]);

        $fuel = new Fuel();
        $fuel->user_id = $user->id;
        $fuel->car_id = $car->id;
        $fuel->amount = $request['amount'];
        $fuel->volume = $request['volume'];
        $fuel->save();
        return redirect('/fuelrecords')->with('success', 'Fuel record saved');
    }

    public function indexMonthlyRecord()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::allows('admin')|| Gate::allows('accountant')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $month = date('m');
        $records = Fuel::whereMonth('created_at', $month)->get();
        $totalVolume = 0;
        $totalAmount = 0;
        for($x=0; $x < count($records); $x++)
        {
            $totalVolume += $records[$x]->volume;
            $totalAmount += $records[$x]->amount;
        }
        
        return view('fuel.index', compact('records', 'totalVolume' , 'totalAmount'));
    }

    public function fuelRequestRecords(Request $request, Car $car)
    {
        if (Gate::allows('admin')|| Gate::allows('accountant')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        } 

        $this->validate($request, [
            'month' => 'required',
        ]);

        $month = $request['month'];
        $date = Carbon::createFromFormat('Y-m', $month);
        $recordFuels = Fuel:: whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->get();
        // dd($recordFuels);
        $totalVolume = 0;
        $totalAmount = 0;
        for($x=0; $x < count($recordFuels); $x++)
        {
            $totalVolume += $recordFuels[$x]->volume;
            $totalAmount += $recordFuels[$x]->amount;
        }
        return view('fuel.request-records', compact('recordFuels', 'totalVolume' , 'totalAmount', 'month'));
    }


}
