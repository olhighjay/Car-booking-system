@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])
@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Drivers</h3></div>
  <div class="container" style="margin-top: 20px">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <a class="loader btn btn-primary btn-round text-white pull-right" href="/newdriver">Create New</a>
            <h4 class="card-title">Drivers</h4>
            <small> This is the list of all the drivers </small>
            &nbsp; &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">
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
                          <th>Car</th>
                          <th>Status</th>
                          <th class="disabled-sorting text-right">Actions</th>
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
                                    <a class="loader" href="" title="View Profile" data-toggle="modal" data-target="#editDriver{{$driver->id}}" style="text-decoration: none">
                                      <img src="{{asset('storage/profile-picture/default-avatar.png')}}" alt="" style="width: 80px; border-radius: 5px; height:80px">
                                      <small style="padding: auto; margin:auto !important">Edit</small>
                                    </a>
                                  @endif
                                </span>
                              </td>
                              <td>{{$driver->name}}</td>
                              <td>{{$driver->phone_number}}</td>
                              <td>
                                @if($driver->car->name ?? '')
                                  {{$driver->car->name}}
                                @else
                                  {{'No Vehicle assigned yet'}}
                                @endif
                              </td>
                              <td>{{$driver->car_availability->name}}</td>
                              <td class="text-right">
                                <!-- Modal Caller for Department and Status -->
                                <a type="button" title="Update Role and Department" class="btn btn-default btn-icon btn-sm "
                                data-toggle="modal" data-target="#exampleModalCenter{{$driver->id}}" style="text-decoration: none; background-color: blue">
                                <i class="fa fa-pencil "></i>
                                </a>
                                          <!-- MODAL for Department and Status -->
                                  <div class="modal fade" id="exampleModalCenter{{$driver->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalCenterTitle">Edit driver</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body" style="padding-bottom:80px">
                                          <!-- Edit Department Form -->
                                          <div>
                                            <form id="form-1{{$driver->id}}" method="POST" action="/update-drivers-dept/{{$driver->id}}" name="department">
                                              @csrf
                                              <input type="hidden" name="_method" value="PUT">
                                              <div class="form-group row" >
                                                <label for="department" class="col-md-4 col-form-label text-md-right">Change department</label>
                                                <div class="col-md-6">
                                                  <select id="department" class="custom-select" name="department">
                                                      @foreach ($departments as $department)
                                                          @if ($department->id === $driver->department_id)
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
                                                  <button type="submit" class="btn btn-primary" onclick="load_preloader('form-1'+{{$driver->id}})">
                                                    {{ __('Update department') }}
                                                  </button>
                                                </div>
                                              </div>
                                            </form> 
                                          </div> <!-- End Department Form -->
                                          <br>  <hr>
                                          <!-- Edit Driver's Availability Form -->
                                          <div>
                                            <form id="form-2{{$driver->id}}" method="POST" action="/update-drivers-availability/{{$driver->id}}" name="availability">
                                              @csrf
                                              <input type="hidden" name="_method" value="PUT">
                                              <div class="form-group row" >
                                                  <label for="role" class="col-md-4 col-form-label text-md-right">Change Availability Status</label>
                                                  <div class="col-md-6">
                                                      <select id="role" class="custom-select" name="driver_availability">
                                                          @foreach ($car_availabilities as $car_availability)
                                                              @if ($car_availability->id === $driver->car_availability_id)
                                                                  <option value = "{{$car_availability->id}}" selected> {{$car_availability->name}} </option>
                                                              @else
                                                                  <option value = "{{$car_availability->id}}"> {{$car_availability->name}} </option>
                                                              @endif
                                                          @endforeach
                                                      </select>
                                                  </div>
                                              </div>
                              
                                              <div class="form-group row mb-0">
                                                  <div class="col-md-6 offset-md-4">
                                                      <button type="submit" class="btn btn-primary" onclick="load_preloader('form-2'+{{$driver->id}})">
                                                          {{ __('Update Status') }}
                                                      </button>
                                                  </div>
                                              </div>
                                            </form>
                                          </div> <!-- End Department Form -->
                                        </div>
                                  
                                      </div>
                                    </div>
                                  </div>  
                                  <!-- MODAL -->

                                <!-- Modal Caller for Cars -->
                                <a type="button" title="Attach Car" class="btn btn-default btn-icon btn-sm "
                                  data-toggle="modal" data-target="#exampleModalCenter{{$driver->id}}2" style="text-decoration: none; background-color: green">
                                  <i class="fa fa-car "></i>
                                </a>
                                          <!-- MODAL for Adding Car to a Driver -->
                                  <div class="modal fade" id="exampleModalCenter{{$driver->id}}2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalCenterTitle{{$driver->id}}2">Add Car to Driver</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body" style="padding-bottom:80px">
                                          <!-- Edit Cars Form -->
                                          <div>
                                            <form id="form-3{{$driver->id}}" method="POST" action="/update-drivers-car/{{$driver->id}}" name="cars">
                                              @csrf
                                              <input type="hidden" name="_method" value="PUT">
                                              <div class="form-group row" >
                                                <label for="car" class="col-md-4 col-form-label text-md-right">Select Car</label>
                                                <div class="col-md-6">
                                                  <select id="car" class="custom-select" name="car">
                                                      @foreach ($cars as $car)
                                                          @if ($car->id === $driver->car_id)
                                                              <option value = "{{$car->id}}" selected> {{$car->name}} </option>
                                                          @else
                                                              <option value = "{{$car->id}}"> {{$car->name}} </option>
                                                          @endif
                                                      @endforeach
                                                  </select>
                                                </div>
                                              </div>
                              
                                              <div class="form-group row mb-0">
                                                <div class="col-md-6 offset-md-4">
                                                  <button type="submit" class="btn btn-primary" onclick="load_preloader('form-3'+{{$driver->id}})">
                                                    {{ __('Update Car') }}
                                                  </button>
                                                </div>
                                              </div>
                                            </form> 
                                          </div> <!-- End Car Form -->
                                          <br>  <hr>
                                        </div>
                                  
                                      </div>
                                    </div>
                                  </div>  
                                  <!-- MODAL -->
                                
                                <a type="button" rel="tooltip" class="btn btn-danger btn-icon btn-sm " data-original-title="" title="Delete Driver"
                                data-toggle="modal" data-target="#deleteDriver{{$driver->id}}">
                                  <i class="fa fa-trash "></i>
                                </a>
                              </td>
                            </tr>

                                        <!-- MODAL HERE     -->


                                  <!-- MODAL For Driver delete-->
                          <div class="modal fade" id="deleteDriver{{$driver->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelreport" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="deleteDriver">You are about to delete {{$driver->name}}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body" style="padding-bottom:80px; " >
                                  <!-- Driver delete -->
                                  <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                    <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                    <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                    </button>
                                    &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                    <span>
                                      <!-- Driver delete  Form-->
                                      <form id="form-4{{$driver->id}}" method="POST" id="{{$driver->id}}" action="deletedriver/{{$driver->id}}">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE" >
                                        <button type="submit"  style="border:none" onclick="load_preloader('form-4'+{{$driver->id}})">
                                          YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                        </button>
                                      </form>
                                      <!-- End Driver delete  Form-->
                                    </span>
                                  </div> 
                                  <!-- End Driver delete  -->
                                  <br> 
                                </div>
                          
                              </div>
                            </div>
                          </div>  
                      <!-- MODAL end -->
                                
                        @endforeach
                      </tbody>
                    </table> {{ # Adding links to next and back page to pagination;
                      $drivers->links()}}
              </div>
            @else 
              <h6 class ="emptyList"><b>No Driver created yet</b></h6>
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
    
