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
    <div><center><img src="storage/img/systemtc.png" alt=""></center></div>
    <h1 style="color: white; text-align:center; font-weight:heavy"><strong> Car App</strong></h1>
  </div>
  <div class="content">
    <div class="row">
      <div class="col-lg-12">
        {{-- <div class="card card-chart"> --}}
          {{-- <div class="card-header">
            <h5 class="card-category">Global Sales</h5>
            <h4 class="card-title">Shipped Products</h4>
            <div class="dropdown">
              <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                <i class="now-ui-icons loader_gear"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <a class="dropdown-item text-danger" href="#">Remove Data</a>
              </div>
            </div>
          </div> --}}
          <div class="row">
            <div class="four col-md-3">
                <div class="card card-chart counter-box">
                  <i class="fa fa-bus"></i></i>
                  <span class="counter">{{count($cars)}}</span>
                  <p><a class="loader" href="/cars" style="text-decoration: none"> Vehicles</a></p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="card card-chart counter-box"> 
                  <i class=" fa fa-id-card"></i>
                  <span class="counter">{{count($drivers)}}</span>
                  <p><a class="loader" href="/drivers" style="text-decoration: none"> Drivers</a></p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="card card-chart counter-box">
                  <i class="fa fa-user" aria-hidden="true"></i>
                  <span class="counter">{{count($users)}}</span>
                  <p><a class="loader" href="/users/allroles" style="text-decoration: none">Users</a></p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="card card-chart counter-box"> 
                  <i class="fa fa-bookmark"></i>
                  <span class="counter">{{count($trips)}}</span>
                  <p><a class="loader" href="/trips" style="text-decoration: none">Trips </a></p>
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="card  card-tasks">
          <div class="card-header ">
            <h5 class="card-category">Reports</h5>
            <h4 class="card-title">All Reports</h4>
          </div>
          <div class="card-body ">
            <div class="table-full-width table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                    <td> <br>
                      <a class="loader" href="/tripreports"><h5><span class="fa fa-road fa-2x" aria-hidden="true"></span> <br>
                        Trip Reports   
                      </h5>
                      </a><br>
                    </td>
                  </tr>
                  <tr>
                    <td> <br>
                      <a class="loader" href="/carreports"><h5><span class="fa fa-car" aria-hidden="true"></span> <br>
                         Vehicle Reports  
                       </h5>
                       </a><br>
                      <br>                      
                    </td>
                  </tr>
                  <tr>
                    <td> <br>
                      <h5><a class="loader" href="/driverreports"><span class="fa fa-wheelchair-alt fa-2x" aria-hidden="true"></span><br>
                         Driver Reports 
                      </a></h5>
                       
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-7">
        <div class="card">
          <div class="card-header">
            <h5 class="card-category">All Departments List</h5>
            <h4 class="card-title"> Departments Stats</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    S/N
                  </th>
                  <th>
                    Name
                  </th>
                  <th>
                    Staff
                  </th>
                  <th>
                    Driver
                  </th>
                  <th class="text-right">
                    Vehicles
                  </th>
                </thead>
                <tbody>
                  <?php $c=1; ?>
                  @foreach($departments as $department)
                  <tr>
                    <td>
                      {{$c++}}
                    </td>
                    <td>
                      <a class="loader" href="departments/{{$department->id}}">{{$department->name}}</a>
                    </td>
                    <td>
                      {{ $department->countUser($department->id) }}
                    </td>
                    <td>
                      {{ $department->countDriver($department->id) }}
                    </td>
                    <td>
                      {{ $department->countCar($department->id) }}
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <i class="now-ui-icons loader_refresh spin"></i>Last updated {{$lastDept->created_at->diffForHumans()}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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