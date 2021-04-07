<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Car App</title>

  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet" />
    <!-- CSS for Pages header banner -->
  <link href="{{ asset('css/jay-page.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
  {{-- <link rel="stylesheet" href="{{ asset('css/now-ui-dashboard.min.css') }}"> --}}
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/now-ui-dashboard.css?v=1.3.0') }}" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link href="{{asset('/css/log.css')}}" rel="stylesheet">

</head>
<body>


<div id="overlay">
    <div class="cv-spinner">
      <span class="spinner"></span>
      
    </div>
  </div>

{{-- @section('content') --}}
<div class="jay-cont">
<div class="jay-cont container">
    <div class="jay-cont row justify-content-center">
        <div class="col-md-8 col-lg-6" >
            <div class="jay-card card" style="border-radius: 30px">
                <div class="card-header" style="text-align:center;">
                    <h2 style="color:white">Login</h2>
                    <img src="{{ asset('storage/img/systemtc.png') }}" alt="" width="500px">

                </div>
                <br> <br>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="login">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right" style="color: rgb(214, 207, 207) !important">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right" style="color: rgb(214, 207, 207) !important">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember" style="color: rgb(214, 207, 207) !important">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class=" btn btn-primary" onclick="load_preloader('login')">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="loader btn btn-link" href="{{ route('password.request') }}" style="color: rgb(214, 207, 207) !important;">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <br> <br>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('js/jay/loader.js')}}"></script>
</div>
</div>
{{-- @endsection --}}
</body>
</html>
