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
            <a class="loader btn btn-primary btn-round text-white pull-right" href="/new-car">Create New</a>
            <h4 class="card-title"><b>All Vehicles</b></h4>
            <small> This is the list of all the vehicles </small>
            &nbsp; &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($cars) > 0)
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
                      
                    </table>{{ # Adding links to next and back page to pagination;
                      $cars->links()}}
              </div>
            @else 
              <h6 class ="emptyList"><b>No Car created yet</b></h6>
            @endif
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>

     <!-- Faulty Cars -->
<br><hr><br>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><b>Faulty Vehicles</b></h4>
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($cars) > 0)
              <div class="table-responsive">
                  <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                      <thead>
                        <tr>
                          <th>Plate number</th>
                          <th>Display Picture</th>
                          <th>Name</th>
                          <th>Properties</th>
                          <th>Department</th>
                          <th>Health</th>
                        </tr>
                      </thead>
                    
                      <tbody>
                          @foreach ($faulty_cars as $faulty_car)
                          <tr>
                            <td>{{$faulty_car->plate_number}}</td>
                            <td>
                              <span class="avatar avatar-sm rounded-circle">
                              <a href="/cars/{{$faulty_car->id}}">
                                <img src="storage/images/car/thumbnail/{{$faulty_car->carimage->first()->name}}" 
                                alt="Car Thumbnail" style="width: 60px; border-radius: 400px; height: 50px">
                              </a>
                              </span>
                            </td>
                            <td>{{$faulty_car->name}}</td>
                            <td>{{\Illuminate\Support\Str::limit($car->properties, 30)}}</td>
                            <td >{{$faulty_car->Department->name}} </td>
                            <td >{{$faulty_car->car_health_status->name}} </td>
                          </tr>
                              
                          @endforeach
                      </tbody>
                    </table>
              </div>
            @else 
            <h6 class ="emptyList"><b>No faulty vehicle at the moment</b></h6>
            @endif
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>

    <!-- Available Cars -->
    <br><hr><br>
    <div style="text-align: center">
      <button id="jay-tableButton" class="btn btn-primary" style="width: 70%">Show Available Vehicles</button>
    </div>
    <div class="row" style="margin-top: 20px; display:none" id="jay-tableSlide">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><b>Available Vehicles</b></h4>
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                    <thead>
                      <tr>
                        <th>Plate number</th>
                        <th>Dispay Picture</th>
                        <th>Name</th>
                        <th>Properties</th>
                        <th>Department</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                   
                    <tbody>
                        @foreach ($available_cars as $available_car)
                        <tr>
                          <td>{{$available_car->plate_number}}</td>
                          <td>
                            <span class="avatar avatar-sm rounded-circle">
                            <a href="/cars/{{$available_car->id}}">
                              {{-- <img src="storage/images/car/thumbnail/{{$faulty_car->carimage->first()->name}}" 
                                alt="Car Thumbnail" style="width: 60px; border-radius: 400px; height: 50px"> --}}
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



    <!-- end row -->
  </div>
  @endsection
    
