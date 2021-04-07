<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;
use App\Driver;
use App\Role;
use App\Carimage;
use App\Department;
use App\Car;
use App\Car_Availability;
use App\Car_health_status;
use App\Trip_request;


class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */

    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    
    public function index(User $model)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        return view('users.index', ['users' => $model->paginate(15)]);
    }


    public function showUser(User $user)
    {
        // Log out suspended user
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        //  only and superadmin have access
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        if(Auth::user()->role()->first()['id'] == $user->role()->first()['id']){
            return view('unauthorised');
        }
        $roles = Role::all();
        $departments = Department::all();
        
        return view('users.show', compact('user', 'departments', 'roles'));
    }

    public function updateUserDP(Request $request, User $user)
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
        }

        $user->display_picture = $filenametostore;
        $user->save();
        return back()->with('success', $user->name.'\'s dispaly picture' . ' ' . 'updated successfully');
    }

    public function fullUserUpdate(Request $request, User $user)
    {
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }

        $this::validate($request, [
            'name' => ['required', 'max:255'],
            'email' => ['required'],
            'department'=> 'nullable',
            'role'=> 'nullable',
        ]);

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->department_id =$request['department'];
        $user->save();


        $old_role =  $user->roles()->first()['id'];
        $new_role = $request['role'];

        $user->detachRole($old_role);
        $user->attachRole($new_role);

        return back()->with('success', 'User updated successfully');
    }


    public function destroyUser(User $user)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        $user->delete();
        return back()->with('success', 'user deleted successfully');
    }

    public function editSelfUser()
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        
        $user = Auth::user();
        // dd($user->role()->first()['id']);

        // dd($user);
        return view('users.profile.edit')->with('user', $user);
    }

    public function updateSelfUser(Request $request)
    {
        $this::validate($request, [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = $user = Auth::user();
        //If User changes email, email_verified at should be deleted so that user can confirm new email
        // dd($user->email, $request['email'], $user->email != $request['name']  );
        if(strtolower($user->email) != strtolower($request['email']) ){
            $user->email_verified_at = null;
        };
        // dd($user->email_verified_at = null);
        
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->save();

        return back()->with('success', 'Your profile has been updated successfully');     
    }

    public function storeProfilePicture(Request $request)
    {
            #Validate profile picture
        $this->validate($request,[
            'profile_picture'=>'image|required|max:1999'
        ]);
    
            #Save profile picture on database
            $user = Auth()->user();
                            //Handle file upload
        if($request->hasFile('profile_picture')){
            $fileNameWithExt = $request->file('profile_picture')->getClientOriginalName();
            $filename = pathInfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // $filePath = 'images/profile_pictures/'. $fileNameToStore;
            $path = $request['profile_picture']->storeAs('public/profile-picture', $fileNameToStore);

        }else{
            $fileNameToStore='default-avatar.png';
        }

        $user->display_picture = $fileNameToStore;
        $user->save();

        return back()->with('success', 'Profile Picture updated successfully');
    }

    public function updateSelfPassword(Request $request)
    {
            #Validate Form
        $this->validate($request,[
            'old_password'=>'required',
            'password'=>'required|confirmed|min:6',
        ]);

        $user= Auth()->user();
        $hashedPassword = $user->password;
        if (Hash::check($request['old_password'], $hashedPassword)) {
            $user->password = \Hash::make($request['password']);
            $user->save();
            return redirect('/profile')->with('success', 'Password updated successfuly');
        } else{
            return redirect('/profile')->with('error', 'Password incorrect! Kindly input the correct password');
        }
        // $password = \Hash::make($request['old_password']);
        // dd($password)       ;
        // if($user->password != $password ){
        
    }

    public function unauthorised()
    {
        return view('unauthorised');
    }

    public function indexStaff($roleName)
    {
        if(Auth::user()->suspension_id == 2){
            Auth::logout();
            return view('/suspended');
        }
        if (Gate::denies('admin')) {
            return redirect('/unauthorised')->with('error', 'Error 403');
        }
        $users= User::orderBy('name')->whereRoleIs($roleName)->get();
        // dd($users);
        
        // $users= User::whereRoleIs('Staff')->get();
        $departments = Department::all();
        // $users= User::whereRoleIs($roleName)->paginate(50);
        if($roleName == 'allroles'){
            $users= User::orderBy('name')->where('id', '!=', 0)->paginate(50);
            $roleName = 'All staff member';
        }else{
            $users= User::orderBy('name')->whereRoleIs($roleName)->paginate(50);
        }
        $roles = Role::where('id', '!=', 1)->get();
        // dd($excos->suspension->name);
        return view('users.index', compact('departments', 'users', 'roles', 'roleName'));   
    }


    


}
