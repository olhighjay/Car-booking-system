@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Update {{$user->name}}'s department</h2></div>
    

    

    <div class="container">   
        </div> 

    <br>

    <div class=" justify-content-center align-items-center container " style="width:70% " >
        <a href="/admin/departments" class="btn btn-default" style="background-color:#243d6b; color:white; margin-top:20px; margin-left:-50px" type="button">Back</a>
        <br> <br>
        {{-- <div class="justify-content-center"> --}}
            <form method="POST" action="/update-users-dept/{{$user->id}}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group row" >
                    <label for="department" class="col-md-4 col-form-label text-md-right">Change department</label>
                    <div class="col-md-6">
                        <select id="department" class="custom-select" name="department">
                            @foreach ($departments as $department)
                                @if ($department->id === $user_dept->id)
                                    <option value = "{{$department->id}}" selected> {{$department->name}} </option>
                                @else
                                    <option value = "{{$department->id}}"> {{$department->name}} </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update department') }}
                        </button>
                    </div>
                </div>
            </form>
    {{-- </div> --}}
    </div>


@endsection