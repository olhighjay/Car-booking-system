<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Car;
use App\Repair;
use Carbon\Carbon;

class RepairController extends Controller
{
    
    public function __construct(){
        $this->middleware(['auth','verified']);
    }


    public function saveRepairRecord( Request $request, Car $car)
    {
        if (Gate::allows('admin')|| Gate::allows('accountant')) {

        }else{
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        $user = Auth::user();

        $this->validate($request, [
            'fault' => 'required',
            'solution' => 'required',
            'amount' => 'required',
        ]);

        $repair = new Repair();
        $repair->user_id = $user->id;
        $repair->car_id = $car->id;
        $repair->amount = $request['amount'];
        $repair->fault = $request['fault'];
        $repair->solution = $request['solution'];
        $repair->save();
        
        return back()->with('success', 'repair record saved');
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
        $records = Repair::whereMonth('created_at', $month)->get();
        $totalAmount = 0;
        for($x=0; $x < count($records); $x++)
        {
            $totalAmount += $records[$x]->amount;
        }
        
        return view('repair.index', compact('records', 'totalAmount'));
    }

    public function RepairRequestRecords(Request $request, Car $car)
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
        $records = Repair:: whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->get();
        // dd($recordFuels);
        $totalAmount = 0;
        for($x=0; $x < count($records); $x++)
        {
            $totalAmount += $records[$x]->amount;
        }
        return view('repair.request-records', compact('records', 'totalAmount', 'month'));
    }


}
