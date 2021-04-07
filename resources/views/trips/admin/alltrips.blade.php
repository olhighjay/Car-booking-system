@extends('layouts.app', [
    'namePage' => 'trips',
    'activePage' => 'trips',
])


@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Trips</h3></div>
<!-- <a href="">Faulty Vehicles</a>
<a href="">Avaialable Vehicles</a> -->
  <div class="container" style="margin-top: 20px">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><b>All Trips</b></h4>
            <small> This is the list of all the trips </small> &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">
            
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
              
              <div class="jay-topnav">
                <div class="jay-topnav-right">
                  <a class="loader" href="/newtrip"><i class="fa fa-plus" aria-hidden="true"></i> Create Trip</a>
                  <a class="loader" href="/switch"><i class="fa fa-toggle-on" aria-hidden="true"></i> Trip Switch</a>
                </div>
              </div>
              {{-- <span style="margin-top: -50px !important">{{ # Adding links to next and back page to pagination;
                $trips->links()}} </span> --}}
            </div>
            @if(count($trips) > 0)
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>
                          {{-- <small class="label">Select all</small>
                          <input class="jay-check" tabindex="1" id="parent" type="checkbox" onclick="checkAll()" checked /> --}}
                      </th>
                      <th>ID</th>
                      <th>Passenger</th>
                      <th>Driver</th>
                      <th>Destination</th>
                      <th>Estd. Time</th>
                      <th>Time Used</th>
                      <th>Trip Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($trips as $trip)
                      <tr id="trip{{$trip->id}}">
                        <td>
                          {{-- <input class="jay-check" id="child_chkbox" type="checkbox" checked /> --}}
                        </td>
                        <td>{{$trip->id}}</td>
                        <td>
                          @if($trip->user()->first()['name'] ?? '')
                            {{$trip->user()->first()['name']}}
                          @else 
                            User has been deleted <br> <br>
                          @endif</td>
                        <td>
                          @if($trip->driver ?? '')
                            {{$trip->driver->name}}
                          @else 
                            Driver has been deactivated <br> <br>
                          @endif
                        </td>
                        <td>{{$trip->address . ','. ' ' . $trip->lga->name}}</td>
                        <td>
                          @if(Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time)) < 60 )
                            {{Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'minutes'}}
                          @else
                            {{Carbon::parse($trip->trip_end_time)->diffInHours(Carbon::parse($trip->trip_start_time)). 'hrs,' . ' '.
                            Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'mins'}}
                          @endif
                        </td>
                        <td> 
                          @if($trip->trip_request->id === 3)
                            @if(Carbon::parse(date("H:i:s", time()))->diffInMinutes(Carbon::parse($trip->trip_started_date)) < 60 )
                              {{Carbon::parse(date("H:i:s", time()))->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'minutes'}}
                            @else
                              {{Carbon::parse(date("H:i:s", time()))->diffInHours(Carbon::parse($trip->trip_started_date)). 'hrs,' . ' '.
                              Carbon::parse(date("H:i:s", time()))->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'mins'}}
                            @endif
                          @elseif($trip->trip_request->id === 4)
                            @if(Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date)) < 60 )
                              {{Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'minutes'}}
                            @else
                              {{Carbon::parse($trip->trip_ended_date)->diffInHours(Carbon::parse($trip->trip_started_date)). 'hrs,' . ' '.
                              Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'mins'}}
                            @endif
                          @else
                            {{'-- -- --'}}
                          @endif
                        </td>
                        <td >
                          @if($trip->trip_request->id === 2)
                            <span style="color: orange">{{$trip->trip_request->name}}</span>
                          @elseif($trip->trip_request->id === 3)
                            <span style="color:chartreuse">{{$trip->trip_request->name}}</span>
                          @elseif($trip->trip_request->id === 4)
                            <span style="color: green">{{$trip->trip_request->name}}</span>
                          @elseif($trip->trip_request->id === 5 || $trip->trip_request->id === 6 || $trip->trip_request->id === 7)
                            <span style="color: red">{{$trip->trip_request->name}}</span>
                          @endif
                        </td>
                        <td> 
                          
                          <!-- Action dropdown -->
                          <div class="dropdown">
                            <button type="button" class="btn jayDropdownButton btn-primary dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bars" aria-hidden="true" ></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item"  alt="info" aria-hidden="true" data-toggle="modal" data-target="#info{{$trip->id}}">More Info</a>
                              @if($trip->trip_request->id === 2)
                                <a class="dropdown-item"  alt="info" aria-hidden="true" data-toggle="modal" data-target="#editTrip{{$trip->id}}">Edit</a>
                                <a class="dropdown-item"  alt="info" aria-hidden="true" data-toggle="modal" data-target="#denyTrip{{$trip->id}}">Deny Trip</a>
                              @endif
                              {{-- <a class="dropdown-item"  alt="info" aria-hidden="true" data-toggle="modal" data-target="#exampleModalLong{{$trip->id}}">Delete</a> --}}
                            </div>
                          </div>
                          
                        </td>
                      </tr>
                            


                      <!-- Modal For Trip Details-->
                      <div class="modal fade" id="info{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLongTitle">Trip Full Details</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="row-centered" >
                                <div class="  col-centered" >
                                  @if($trip->car ?? '')
                                    <img src="storage/images/car/{{$trip->car->carimage->first()->name}}" alt="Car Thumbnail" >
                                  @endif
                                  </p> @if($trip->car->name ?? ''){{$trip->car->name}} @else Car has been deleted @endif</p>
                                </div>
                              </div>
                              <br> 
                              <div>
                                <p><b>Trip ID:</b> &nbsp {{$trip->id}} </p>
                                <p><b>Passenger:</b> &nbsp 
                                  @if($trip->user()->whereRoleIs(['admin', 'staff', 'accountant', 'receptionist'])->first()['name'] ?? '')
                                    {{$trip->user()->whereRoleIs(['admin', 'staff', 'accountant', 'receptionist'])->first()['name']}}
                                  @else 
                                      User has been deleted
                                  @endif
                                </p>
                                <p><b>Driver:</b> &nbsp 
                                  @if($trip->driver ?? '')
                                      {{$trip->driver->name}}
                                  @else 
                                      Driver has been deactivated
                                  @endif
                                </p>
                                <p><b>Destination:</b> &nbsp {{$trip->address . ','. ' ' . $trip->lga->name . '.'}} </p>
                                <p><b>Trip date:</b> &nbsp {{Carbon::parse($trip->trip_date)->format('jS F Y')}} </p>
                                <p><b>Time to start trip:</b> &nbsp {{date("h:i:s a", strtotime($trip->trip_start_time))}} </p>
                                <p><b>Time to end trip:</b> &nbsp {{date("h:i:s a", strtotime($trip->trip_end_time))}} </p>
                                <p><b>Estimated time to use:</b> &nbsp
                                  @if(Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time)) < 60 )
                                    {{Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'minutes'}}
                                  @else
                                    {{Carbon::parse($trip->trip_end_time)->diffInHours(Carbon::parse($trip->trip_start_time)). 'hrs,' . ' '.
                                    Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'mins'}}
                                  @endif
                                </p>
                                  <p><b>Trip status:</b> &nbsp {{$trip->trip_request->name}} </p>
                                  @if($trip->trip_request_id === 3 || $trip->trip_request_id === 4)
                                    <p><b>Time the trip started:</b> &nbsp {{date("jS F Y h:i:s a", strtotime($trip->trip_started_date))}}</p>
                                    @if($trip->trip_request_id === 4)
                                      <p><b>Time the trip ended:</b> &nbsp {{date("jS F Y h:i:s a", strtotime($trip->trip_ended_date))}}</p>
                                    @endif
                                    <p><b>Estimated time used:</b> &nbsp 
                                    @if($trip->trip_request->id === 3)
                                      @if(Carbon::parse(date("H:i:s", time()))->diffInMinutes(Carbon::parse($trip->trip_started_date)) < 60 )
                                        {{Carbon::parse(date("H:i:s", time()))->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'minutes'}}
                                      @else
                                        {{Carbon::parse(date("H:i:s", time()))->diffInHours(Carbon::parse($trip->trip_started_date)). 'hrs,' . ' '.
                                        Carbon::parse(date("H:i:s", time()))->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'mins'}}
                                      @endif
                                    @elseif($trip->trip_request->id === 4)
                                      @if(Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date)) < 60 )
                                        {{Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'minutes'}}
                                      @else
                                        {{Carbon::parse($trip->trip_ended_date)->diffInHours(Carbon::parse($trip->trip_started_date)). 'hrs,' . ' '.
                                        Carbon::parse($trip->trip_ended_date)->diffInMinutes(Carbon::parse($trip->trip_started_date))%60 . 'mins'}}
                                      @endif
                                    @else
                                      {{'-- -- --'}}
                                    @endif
                                    </p>
                                  @endif
                                  <p><b>Created on:</b> &nbsp {{$trip->created_at}} </p>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                            </div>
                          </div>
                        </div>
                      </div> <!-- Modal End -->


                      <!-- MODAL Edit Trip -->
                      <div class="modal fade" id="editTrip{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="editTrip" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalCenterTitle">Edit Trip ID: {{$trip->id}}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            {{-- <div style="color: orangered; text-align:center">
                              <small>Note: You can only submit a report on a trip once..</small>
                            </div> --}}
                            <div class="modal-body" style="padding-bottom:80px">
                              <!-- Edit Trip Form -->
                              <div>
                                <p class="required">Required *</p>
                                <form id="form-1{{$trip->id}}" method="POST" action="/updatetrip/{{$trip->id}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                
                                    <div class="form-group">
                                        <label for="lga">Destination L.G.A</label> <span class="required">*</span>
                                        <select id="lga" class="custom-select form-control" name="lga" required>
                                            @foreach ($lgas as $lga)
                                                @if($lga->id === $trip->lga_id)
                                                    <option value = "{{$lga->id}}" selected> {{$lga->name}} </option>
                                                @else 
                                                    <option value = "{{$lga->id}}" > {{$lga->name}} </option>
                                                @endif

                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="form-group">
                                        <label for="address"> Address </label> <span class="required">*</span>
                                        <input type="text" id="address" name="address" class="form-control"
                                        placeholder="Destination Address" value="{{$trip->address}}" required>
                                    </div>
                    
                                    <div class="form-group"> <br>
                                        <label for="car">Choose a Car</label> <span class="required">*</span>
                                        <br> <small style="color:#9A9A9A">A car in yellow shows the car has been booked</small>
                                        <br> <small style="color:#9A9A9A">A car in red shows the car is not available</small>
                                        <select id="car" class="custom-select form-control" name="car" required>
                                            @foreach ($cars as $car)
                                                @if($car->car_availability_id === 3)
                                                    @if($car->id === $trip->car_id)
                                                        <option value = "{{$car->id}}" style="color:orange" selected> {{$car->name}} </option>
                                                    @else
                                                        <option value = "{{$car->id}}" style="color:orange"> {{$car->name}} </option>
                                                    @endif 
                                                @elseif($car->car_availability_id === 2)
                                                    @if($car->id === $trip->car_id)
                                                        <option value = "{{$car->id}}" style="color:red" selected disabled> {{$car->name}} </option>
                                                    @else
                                                        <option value = "{{$car->id}}" style="color:red" disabled> {{$car->name}} </option>
                                                    @endif
                                                @else 
                                                    @if($car->id === $trip->car_id)
                                                        <option value = "{{$car->id}}" selected> {{$car->name}} </option>
                                                    @else
                                                        <option value = "{{$car->id}}"> {{$car->name}} </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <div class="form-group"> <br>
                                        <label for="driver">Select Driver</label> <span class="required">*</span>
                                        <br> <small style="color:#9A9A9A">A driver in yellow shows the driver has been booked</small>
                                        <br> <small style="color:#9A9A9A">A driver in red shows the driver is not available</small>
                                        <select id="driver" class="custom-select form-control" name="driver" required>
                                            @foreach ($drivers as $driver)
                                            
                                              @if($trip->driver()->first()['id'] ?? '')
                                              
                                                @if($driver->car_availability_id === 3)
                                                
                                                    @if($driver->id === $trip->driver->id)
                                                        <option value = "{{$driver->id}}" style="color:orange" selected> {{$driver->name}} </option>
                                                    @else 
                                                        <option value = "{{$driver->id}}" style="color:orange"> {{$driver->name}} </option>
                                                    @endif
                                                @elseif($driver->car_availability_id === 2)
                                                    @if($driver->id === $trip->driver->id)
                                                        <option value = "{{$driver->id}}" style="color:red" selected disabled> {{$driver->name}} </option>
                                                    @else 
                                                        <option value = "{{$driver->id}}" style="color:red" disabled> {{$driver->name}} </option>
                                                    @endif
                                                @else
                                                    @if($driver->id === $trip->driver->id)
                                                        <option value = "{{$driver->id}}" selected> {{$driver->name}} </option>
                                                    @else 
                                                        <option value = "{{$driver->id}}"> {{$driver->name}} </option>
                                                    @endif    
                                                @endif
                                              @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                    
                                    <div class="form-group">
                                        <label for="date">Trip Day</label>
                                        <input type="date" id="date" name="trip_date" value="{{$trip->trip_date}}" required>
                                    </div>
                                    <br>
                    
                                    <div class="form-group">
                                        <label for="start_time">Select a time to start Trip:</label>
                                        <input type="time" id="start_time" name="trip_start_time" value="{{$trip->trip_start_time}}" required>
                                    </div>
                                    <br>
                    
                                    <div class="form-group">
                                        <label for="end_time">Select a time to end Trip:</label>
                                        <input type="time" id="end_time" name="trip_end_time" value="{{$trip->trip_end_time}}" required>
                                    </div>
                                    
                                    <div class="form-group row mb-0">
                                        <div class="col-md-4 offset-md-4">
                                          <button type="submit" class="btn btn-primary jayformbutton form-control" onclick="load_preloader('form-1'+{{$trip->id}})">
                                            {{ __('Update Trip') }}
                                          </button>
                                        </div>
                                      </div>
                            
                                </form> 
                              </div> <!-- End Edit Trip Form -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                      <!-- MODAL end -->

                                   <!-- MODAL Deny Trip -->
                      <div class="modal fade" id="denyTrip{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="denyTrip" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="denyTrip{{$trip->id}}">You are about to deny trip - ID: {{$trip->id}} request</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px; " >
                              <!-- Deny Trip -->
                              <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                </button>
                                 &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                 <span>
                                   <!-- Deny Trip Form-->
                                  <form id="form-2{{$trip->id}}" method="POST" id="deny{{$trip->id}}" action="deny/{{$trip->id}}">
                                    @csrf
                                    <button type="submit"  style="border:none" onclick="load_preloader('form-2'+{{$trip->id}})">
                                       YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                    </button>
                                  </form>
                                  <!-- End Deny Trip Form-->
                                </span>
                              </div> 
                              <!-- End Deny Trip  -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                  <!-- MODAL end -->



                    @endforeach
                  </tbody>
                </table>  
                {{ # Adding links to next and back page to pagination;
                  $trips->links()}}  
              </div>
            @else
              <div style="text-align: center" >
                <h6 class ="emptyList"><b>There has not been any trip yet </b></h6>
              </div>
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



  {{-- <script>
    function checkAll(){
      var parent = document.querySelector("#parent");
      var label = document.querySelector("#label");
      var input = document.querySelector(".jay-check");
      console.log(parent);

      if(parent.checked === true){
        for(var i = 0; i < input.length; i++){
          if(input[i].type === "checkbox" && input.id ==="child_chkbox" && input[i].checked === false){
            input[i].checked = true;
            label.innerHTML = "All Selected";
          }
        }
      }

      else if(parent.checked === false){
        for(var i = 0; i < input.length; i++){
          if(input[i].type === "checkbox" && input.id ==="child_chkbox" && input[i].checked === true){
            input[i].checked = false;
            label.innerHTML = "Sellect All";
          }
        }
      }

    }
  </script> --}}
  @endsection
    
