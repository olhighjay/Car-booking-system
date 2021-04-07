@extends('layouts.app', [
    'namePage' => 'Dashboard',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'home',
    'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

  <link href="{{asset('/css/home.css')}}" rel="stylesheet">
  <script src="{{asset('/js/jay/home.js')}}"></script>
  {{-- <script src="{{asset('js/core/jquery.min.js')}}"></script> --}}



@section('content')
    <div class="panel-header panel-header-lg">
        {{-- <canvas id="bigDashboardChart"></canvas> --}}
        <div><center><img src="storage/img/systemtc.png" alt=""></center>
        </div>
        <h1 style="color: white; text-align:center; font-weight:heavy"><strong> Car App</strong></h1>
    </div>
    <div class="content" style="padding-top: 30px"> 
        <div class="row" style="padding-top: 30px">
            <div class="col-md-1 col-lg-2"></div>
            <div class="col-sm-7 col-md-5 col-lg-4">
            <div class="card">
                {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
                <div class="card-body">
                    <div class="card card-chart counter-box">
                        <i class="fa fa-bus"></i></i>
                        <span class="counter">{{count($cars)}}</span>
                        <p><a class="loader" href="/mycars" style="text-decoration: none"> Vehicles</a></p>
                    </div> 
                </div>
            </div>
            </div>
            <div class="col-sm-7 col-md-5 col-lg-4">
            <div class="card">
                {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
                <div class="card-body">
                    <div class="card card-chart counter-box"> 
                        <i class=" fa fa-id-card"></i>
                        <span class="counter">{{count($drivers)}}</span>
                        <p><a class="loader" href="/mydrivers" style="text-decoration: none">Drivers</a></p>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-md-1 col-lg-2"></div>
        </div>

        <div class="row" >
            <div class="col-md-4 col-lg-4"></div>

            <div class="col-sm-7 col-md-5 col-lg-4">
                <div class="card">
                    {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
                    <div class="card-body">
                        <div class="card card-chart counter-box"> 
                            <i class="fa fa-bookmark"></i>
                            <span class="counter">{{count($trips)}}</span>
                            <p><a class="loader" href="/mytrips" style="text-decoration: none">Trips </a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-lg-4"></div>
        </div>
    </div>

@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      demo.initDashboardPageCharts();

    });
  </script>
@endpush