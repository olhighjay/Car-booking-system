@extends('layouts.app', [
    'namePage' => 'Vehicles',
    'activePage' => 'vehicles',
])
@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Vehicles</h3></div>
<!-- <a href="">Faulty Vehicles</a>
<a href="">Avaialable Vehicles</a> -->
  <div class="container" style="margin-top: 20px">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <a class="btn btn-primary btn-round text-white pull-right" href="#jay-tableButton">Available Vehicles</a>
            <h4 id="all-vehicles" class="card-title"><b>My Vehicles</b></h4>
            <small> This is the list of the vehicles I have access to </small>
            &nbsp; &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">

            <div class="col-12 mt-2">
            </div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($cars) > 0)
              {{-- &nbsp; &nbsp; --}}

              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>Plate number</th>
                      <th>Display Picture</th>
                      <th>Name</th>
                      <th>Properties</th>
                      <th>Status</th>
                      <th>Health</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                    @foreach ($cars as $car)
                      <tr>
                        <td>{{$car->plate_number}}</td>
                        <td>
                          <span class="avatar avatar-sm rounded-circle">
                            <a href="/cars/{{$car->id}}">
                              <img src="storage/images/car/thumbnail/{{$car->carimage->first()['name']}}" 
                              alt="Car Thumbnail" style="width: 60px; border-radius: 400px; height: 50px">
                            </a>
                          </span>
                        </td>
                        <td>{{$car->name}}</td>
                        <td>{{\Illuminate\Support\Str::limit($car->properties, 30)}}</td>
                        <td>{{$car->car_availability->name}}</td>
                        <td >{{$car->car_health_status->name}} </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else 
              <div style="text-align: center" >
                <h6 class ="emptyList"><b>No vehicle is attached to your vehicle yet</b></h6>
              </div>
            @endif
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>
    
    <br><hr><br>

   
    @if($available_cars ?? '' )
      <div style="text-align: center">
        <button id="jay-tableButton" class="btn btn-primary" style="width: 70%">Show Available Vehicles</button>
      </div>
      <br>
      <div class="row" style="margin-top: 20px;  display:none" id="jay-tableSlide">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <a class="btn btn-primary btn-round text-white pull-right" href="#all-vehicles">All Vehicles</a>
              <h4 id="available" class="card-title"><b>Available Vehicles</b></h4>
              <div class="col-12 mt-2"></div>
            </div>
            <div class="card-body">
              <div class="toolbar">
                <!--        Here you can write extra buttons/actions for the toolbar              -->
              </div>
              <div class="table-responsive">
                  <table  class="table" cellspacing="0" style="width:100%">
                      <thead>
                        <tr>
                          <th>Plate number</th>
                          <th>Display picture</th>
                          <th>Name</th>
                          <th>Properties</th>
                          <th>Department</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      
                      <tbody>
                        @foreach ($available_cars as $available_car)
                          <tr>
                            <td>{{$car->name}}</td>
                            <td>
                              <span class="avatar avatar-sm rounded-circle">
                                <a href="/cars/{{$available_car->id}}">
                                  <img src="storage/images/car/thumbnail/{{$available_car->carimage->first()['name']}}" 
                                  alt="Car Thumbnail" style="width: 60px; border-radius: 400px; height: 50px">
                                  
                                </a>
                              </span>
                            </td>
                            <td>{{$available_car->name}}</td>
                            <td>{{\Illuminate\Support\Str::limit($available_car->properties, 30)}}</td>
                            <td >{{$available_car->Department->name}} </td>
                            <td>{{$available_car->car_availability->name}}</td>
                          </tr>
                              
                        @endforeach
                      </tbody>
                    </table>
              </div>
            </div>
            <!-- end content-->
          </div>
          <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
      </div>
    @else
    <div class="card">
      <div class="card-body" style="text-align: center">
        {{-- <h5 class="card-title">Title</h5> --}}
      <h6 class="emptyList">No available car at the moment</h6>
      </div>
    </div>
    @endif




    <!-- end row -->
  </div>
  @endsection
    
