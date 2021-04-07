@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Update {{$user->name}}'s Role</h2></div>
    

    

    <div class="container">   
        </div> 

    <br>

    <div class=" justify-content-center align-items-center container " style="width:70% " >
        <a href="/admin/departments" class="btn btn-default" style="background-color:#243d6b; color:white; margin-top:20px; margin-left:-50px" type="button">Back</a>
        <br> <br>
        {{-- <div class="justify-content-center"> --}}
            <form method="POST" action="/update-users-role/{{$user->id}}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group row" >
                    <label for="role" class="col-md-4 col-form-label text-md-right">Change Role</label>
                    <div class="col-md-6">
                        <select id="role" class="custom-select" name="role">
                            @foreach ($roles as $role)
                                @if ($role->id === $user_role->id)
                                    <option value = "{{$role->id}}" selected> {{$role->name}} </option>
                                @else
                                    <option value = "{{$role->id}}"> {{$role->name}} </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update Role') }}
                        </button>
                    </div>
                </div>
            </form>
    {{-- </div> --}}
    </div>


@endsection