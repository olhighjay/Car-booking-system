<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;
use Illuminate\Support\Str;
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
use App\Mail\SendEmail;


class SuperAdminController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','verified']);
    }
    //SELF-USER
   


    //USERS
    public function createUser()
    {
        // Log out suspended user
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        
        // Only Admin and Superadmin have access
        if (Gate::denies('admin')) {
            return redirect('/unathorised')->with('error', 'Error 405');
        }
        
        $user = Auth::user();
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->where('id', '!=', 1)->get();
        // $roles = Role::all();
        return view('users.new-user', compact('departments', 'roles'));
    }
    

    public function storeUser(Request $request){

        // Only Admin and Superadmin have access
        if (Gate::denies('admin')) {
            return redirect('/unathorised')->with('error', 'Error 405');
        }

        $this::validate($request, [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'min:6', 'confirmed'],
            'department'=> 'required',
            'role'=> 'required',
        ]);
        
        $password = Str::random(10);
        
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = \Hash::make($password);
        $user->department_id =$request['department'];
        $user->display_picture = 'default-avatar.png';
        $user->save();

        $role = $request['role'];
        $user->attachRole($role);
        

        Mail::raw('This is your Password:'. ' ' .$password, function($message){
            $message->to(request('email'))
                ->subject('Car App Login Details');
                $message->from('olhighjayartrage@gmail.com');
        });   

        return redirect('/')->with('success', 'You have successfully created a new User');
    }

    public function updateUserRole(Request $request, User $user){

        if (Gate::denies('admin')) {
            return redirect('/unathorised')->with('error', 'Error 405');
        }

        $old_role =  $user->roles()->first()->id;
        $new_role = $request['role'];
        $user->detachRole($old_role);
        $user->attachRole($new_role);
        return back()->with('success', $user->name.'\'s role' . ' ' . 'updated successfully');
    }

    public function testMail(){
        // $to_name = $user->name;
        // $to_email = $user->email;
        // $data = array('name'=>"Ifeoluwa", "body" => "Test mail just for testing");
        // Mail::send('testmail', $data, function($message){
        //     $message->to('olhighjayartrage@gmail.com')
        //     ->subject('Artisans Web Testing Mail');
        //     $message->from('d.railjayluv0910@gmail.com','systemtech App');
        // });

        $to_name = 'YOUR NAME';
        $to_email = 'ifeoluwaolajid@gmail.com';
        $data = array('name'=>"Sam Jose", "body" => "testmail");
            
        // Mail::send('testmail', $data, function($message) use ($to_email) {
        //     $message->to($to_email)
        //             ->subject('Artisans Web Testing Mail');
        //     $message->from('olhighjayartrage@gmail.com');
        // });
        // return view('testmail');
        
        // $to_email = "lolsluvsemowo_jadou@yahoo.com";

        // Mail::to($to_email)->send(new SendEmail);

        return "<p> Your E-mail has been sent successfully. </p>";
    }

    public function showEmail()
    {
        return view('emails');
    }

    public function storeEmail()
    {
        request()->validate(['email'=>'required|email']);
        // $this::validate($request, [
        //     'email' => ['required', 'email',]
        // ]);

        //this worked
        // Mail::raw('it works very well o', function($message){
        //     $message->to(request('email'))
        //         ->subject('Hello there my friend');
        //         $message->from('olhighjayartrage@gmail.com');
        // });
        $topic = 'Mail testing';

        Mail::to(request('email'))
        ->send(new Contact($topic));
        // $message->from('olhighjayartrage@gmail.com');
        // Mail::send('testmail', function($message){
        //     $message->to(request('email'))
        //             ->subject('Artisans Web Testing Mail');
        //     $message->from('olhighjayartrage@gmail.com');
        // });
        return back()->with('success', 'message sent');
    }

}
