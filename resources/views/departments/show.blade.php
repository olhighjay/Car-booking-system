@extends('layouts.app', [
    'namePage' => 'Departments',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'departments',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">{{$dept->name}}</h2></div>

    <div class="container" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                    <a class="loader btn btn-primary btn-round text-white pull-right" href="/newdepartment">Create New Department</a>
                    <h4 class="card-title">Department's Members</h4>
                  <div class="col-12 mt-2"></div>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                  @if(count($members) > 0 )
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">
                            <thead>
                                <tr>
                                <th><b>Profile</b></th>
                                <th><b>Name</b></th>
                                <th><b>Email</b></th>
                                <th class="disabled-sorting"><b>Role</b></th>
                                </tr>
                            </thead>
                                <tbody>
                                    @foreach ($members as $member)
                                        <tr>
                                            <td>
                                                <span class="avatar avatar-sm rounded-circle">
                                                    @if($member->display_picture)
                                                    <a class="loader" href="/viewuser/{{$member->id}}">
                                                        <img src="{{asset('storage/profile-picture/'.$member->display_picture)}}" 
                                                        alt="display picture" style="width: 75px; height: 75px">
                                                    </a>
                                                    @else
                                                    <a class="loader" href="/viewuser/{{$member->id}}">
                                                        <img src="{{asset('storage/profile-picture/default-avatar.png')}}" alt="" style="max-width: 80px; border-radiu: 100px">
                                                    </a>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{$member->name}}</td>
                                            <td>{{$member->email}}</td>
                                            <td>{{$member->roles()->first()['display_name']}} </td>
                                        </tr>
                                        
                                    @endforeach
                                    
                                </tbody>
                            </table>
                    </div>
                  @else 
                  <h6 class ="emptyList"><b>No member yet</b></h6>
                  @endif
                </div>
                <!-- end content-->
              </div>
              <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
          </div>
          <!-- end row -->
    
            <!-- Faulty Cars -->
        <br><hr><br>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <h4 class="card-title">Department's Vehicles</b></h4>
                    <div class="col-12 mt-2"></div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                                <thead>
                                    <tr>
                                    <th></th>
                                    <th> <b>Name</b> </th>
                                    <th> <b>Status</b> </th>
                                    <th> <b>Health</b> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dept_cars as $dept_car)
                                        <tr>
                                            <td>
                                            <span class="avatar avatar-sm rounded-circle">
                                                <a class="loader" href="/cars/{{$dept_car->id}}">
                                                    <img src="{{asset('storage/images/car/thumbnail/' . $dept_car->carimage->first()->name)}}" 
                                                    alt="Car Thumbnail" style="width: 60px; border-radius: 400px; height: 50px">                                                    
                                                </a>
                                            </span>
                                            </td>
                                            <td>{{$dept_car->name}}</td>
                                            <td >{{$dept_car->car_availability->name}} </td>
                                            <td >{{$dept_car->car_health_status->name}} </td>
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
        <br> <hr> <br>
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Department's Drivers</h4>
                  <div class="col-12 mt-2"></div>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                  @if(count($drivers) > 0 )
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">
                            <thead>
                                <tr>
                                <th><b>Profile</b></th>
                                <th><b>Name</b></th>
                                <th><b>Phone number</b></th>
                                </tr>
                            </thead>
                                <tbody>
                                    @foreach ($drivers as $driver)
                                        <tr>
                                            <td>
                                                <span class="avatar avatar-sm rounded-circle">
                                                    @if($driver->display_picture)
                                                    <a class="loader" href="/drivers/{{$driver->id}}">
                                                        <img src="{{asset('storage/profile-picture/thumbnail/'.$driver->display_picture)}}" 
                                                        alt="display picture" style="width: 75px; height: 75px">
                                                    </a>
                                                    @else
                                                        <a class="loader" href="/viewuser/{{$driver->id}}">
                                                        <img src="{{asset('storage/profile-picture/default-avatar.png')}}" alt="" style="max-width: 80px; border-radiu: 100px">
                                                    </a>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{$driver->name}}</td>
                                            <td>{{$driver->phone_number}}</td>
                                        </tr>
                                        
                                    @endforeach
                                    
                                </tbody>
                            </table>
                    </div>
                  @else 
                <h6 class ="emptyList"><b>No Driver yet </b></h6>
                  @endif
                </div>
                <!-- end content-->
              </div>
              <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
        </div>
          <!-- end row -->
    
            <!-- Faulty Cars -->
        <br><br>
    
        <!-- end container -->
    </div>

@endsection