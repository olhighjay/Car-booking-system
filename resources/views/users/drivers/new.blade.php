@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Create A Driver</h2></div>
    

    <br>

    <div class=" justify-content-center align-items-center container ">
        <div class="card" style="width:80%; margin-left:10%">
            <div class="card-body">
                <br> <br>
        {{-- <div class="justify-content-center"> --}}
                <form id="form-1" method="POST" action="/savedriver">
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
                        <label for="phone-number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                        <div class="col-md-6">
                            <input id="phone-number" type="number" class="form-control @error('phone-number') is-invalid @enderror" name="phone_number" value="{{ old('phone-number') }}" autocomplete="email" placeholder="Add Phone number">

                            @error('phone-number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

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

                    {{-- <div class="row">
                        <label for="profilePicture">Choose Profile Picture</label> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
                        <input type="file" class="image" name="profile_picture" id="profilePicture">
                    </div>  --}}

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