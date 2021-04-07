@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Create A User</h2></div>
    <br>

    <div class=" justify-content-center align-items-center container">
        <div class="card" style="width:80%; margin-left:10%">
            <div class="card-body">
                {{-- <h5 class="card-title">Add User</h5> --}}
                <br> <br>
            {{-- <div class="justify-content-center"> --}}
                <form id="form-1" method="POST" action="{{ route('save-user') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Full Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Add Fullname" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Add Email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Add Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="confirm Password">
                        </div>
                    </div> --}}

                    <div class="form-group row" >
                        <label for="department" class="col-md-4 col-form-label text-md-right">Choose a Department</label>
                        <div class="col-md-6">
                            <select id="department" class="custom-select" name="department" required>
                                <option value = ""> -- </option>
                                @foreach ($departments as $department)
                                    <option value = "{{$department->id}}"> {{$department->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" >
                        <label for="role" class="col-md-4 col-form-label text-md-right">Choose a Role</label>
                        <div class="col-md-6">
                            <select id="role" class="custom-select" name="role" required>
                                <option value = ""> -- </option>
                                @foreach ($roles as $role)
                                            <option value = "{{$role->id}}"> {{$role->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" onclick="load_preloader('form-1')">
                                {{ __('Create') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection