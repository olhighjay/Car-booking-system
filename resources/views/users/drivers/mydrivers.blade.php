@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'mydriver',
])
@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Drivers</h3></div>
  <div class="container" style="margin-top: 20px">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">My Drivers</h4>
            <small> This is the list of all the drivers in my department </small>
            &nbsp; &nbsp; &nbsp;
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($drivers) > 0)
              <div class="table-responsive">
                  <table id="datatable" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Profile</th>
                          <th>Name</th>
                          <th>Phone Number</th>
                          <th class="disabled-sorting text-right">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($drivers as $driver)
                          <tr>
                            <td>{{$driver->id}}</td>
                            <td>
                              <span class="avatar avatar-sm rounded-circle" >
                                @if($driver->display_picture)
                                        <!-- Button trigger modal -->
                                  <a class="loader" href="/drivers/{{$driver->id}}" style="text-decoration: none" title="View Profile" >
                                  <img src="{{asset('storage/profile-picture/thumbnail/'.$driver->display_picture)}}" 
                                alt="Display Picture" style="width: 60px; border-radius: 400px; height: 50px">
                                  </a>
                                @else
                                        <!-- Button trigger modal -->
                                  <a  href="" title="View Profile" data-toggle="modal" data-target="#editDriver{{$driver->id}}" style="text-decoration: none">
                                    <img src="{{asset('storage/profile-picture/default-avatar.png')}}" alt="" style="width: 80px; border-radius: 5px; height:80px">
                                    <small style="padding: auto; margin:auto !important">Edit</small>
                                  </a>
                                @endif
                              </span>
                            </td>
                            <td>{{$driver->name}}</td>
                            <td>{{$driver->phone_number}}</td>
                            
                            <td>{{$driver->car_availability->name}}</td>
                          </tr>
                              
                        @endforeach
                      </tbody>
                    </table> 
              </div>
            @else 
              <h6 class ="emptyList"><b>No Driver attached to your department yet</b></h6>
            @endif
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
    
