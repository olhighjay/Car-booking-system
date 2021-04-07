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
            <h4 class="card-title"><b>My Trips</b></h4>
            <a class="loader btn btn-primary btn-round text-white pull-right" href="/newtrip">Create Trip</a>
            <small> This is the list of all the trips you've embarked on </small>
            &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">

            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($trips) > 0)
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Car</th>
                      <th>Destination</th>
                      <th>Driver</th>
                      <th>Estd. Time</th>
                      <th>Time Used</th>
                      <th>Trip Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($trips as $trip)
                      <tr id="{{$trip->id}}">
                        <td>{{$trip->id}}</td>
                        <td>
                          <span class="avatar avatar-sm rounded-circle">
                            @if($trip->car ?? '')
                              <a class="loader" href="/cars/{{$trip->car_id}}">
                                <img src="storage/images/car/thumbnail/{{$trip->car->carimage->first()->name}}" 
                                alt="Car Thumbnail" style="width: 60px; border-radius: 400px; height: 50px">
                              </a>
                            @endif
                          </span>
                        </td>
                        <td>{{$trip->address . ','. ' ' . $trip->lga->name}}</td>
                        <td>
                          @if($trip->driver ?? '')
                            {{$trip->driver->name}}
                          @else 
                            Driver has been deactivated <br> <br>
                          @endif
                        </td>
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
                        <td>  <!-- Icon trigger modal -->
                          <span type="button" class="btn btn-default" style="border-radius:30px; padding:5px 15px; background-color: green; color: white"
                          aria-hidden="true" data-toggle="modal" data-target="#exampleModalLong{{$trip->id}}">
                          <i class="fa fa-info" alt="info" ></i>
                          </span> &nbsp; 
                          @if(!$trip->summary && $trip->trip_request->id === 4)
                          <span type="button" class="btn btn-default" style="border-radius:30px; padding:5px 15px" data-toggle="modal" data-target="#tripReport{{$trip->id}}">
                            Report
                          </span>
                          @endif
                          {{-- @if($trip->trip_request->id === 2)
                          <span type="button" class="btn btn-danger" style="border-radius:30px; padding:5px 15px" data-toggle="modal" data-target="#cancelreport{{$trip->id}}">
                            Cancel Trip
                          </span>
                          @elseif($trip->trip_request->id === 3 )
                            
                          @elseif( $trip->trip_request->id === 5)
                            <small style="color: red">{{'You Cancelled this Trip'}}</small>                          

                          @elseif($trip->trip_request->id === 6)
                            <span style="color: red">{{'Your Trip request has been Denied'}}</span>
                          @elseif($trip->trip_request->id === 4)
                            @if($trip->summary)
                              <span style="color: green">{{'Report Submitted'}}</span>
                            @else
                                <!-- Button trigger modal -->
                              <span type="button" class="btn btn-default" style="border-radius:30px; padding:5px 15px" data-toggle="modal" data-target="#tripReport{{$trip->id}}">
                                Report
                              </span>
                            @endif
                          @endif --}}
                        </td>
                      </tr>
                            <!-- MODAL -->
                      <div class="modal fade" id="tripReport{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="tripReport" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalCenterTitle">We would love to know, how was your trip??</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div style="color: orangered; text-align:center">
                              <small>Note: You can only submit a report on a trip once..</small>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px">
                              <!-- Report Form -->
                              <div>
                                <p class="required">Required *</p>
                                <form id="form-1" method="POST" action="/savetripreport/{{$trip->id}}">
                                  @csrf
                                  <div class="form-group">
                                    <label for="summary" >Trip Summary <span class="required">*</span></label>
                                    <textarea class="form-control"  id="summary" name="summary" required></textarea>
                                  </div>

                                  <div class="form-group">
                                    <label for="complaints">Any Complaints?</label>
                                    <textarea class="form-control"  id="complaints" name="complaints"></textarea>
                                  </div>

                                  <div class="form-group">
                                    <label for="accident">Any Accident?</label>
                                  <textarea class="form-control" id="accident" name="accident"></textarea>
                                  </div> <br>

                                  <span style="color:#9A9A9A"> How is the Car after the Trip <span class="required">*</span></span>
                                  <select id="car_status" class="custom-select form-group" name="car_status" required>
                                      <option value = ""> -- </option>
                                      @foreach ($car_statuses as $car_status)
                                        <option value = "{{$car_status->id}}" > {{$car_status->name}} </option>
                                      @endforeach
                                  </select>
                  
                                  <div class="form-group row mb-0">
                                    <div class="col-md-4 offset-md-4">
                                      <button type="submit" class="btn btn-primary jayformbutton form-control" onclick="load_preloader('form-1')">
                                        {{ __('Submit Report') }}
                                      </button>
                                    </div>
                                  </div>
                                </form> 
                              </div> <!-- End Report Form -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                      <!-- MODAL end -->

                      
                            <!-- MODAL -->
                      <div class="modal fade" id="cancelreport{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelreport" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalCenterTitle">You are about to cancel your trip - ID: {{$trip->id}}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px; " >
                              <!-- Cancellation -->
                              <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                </button>
                                 &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                 <span>
                                   <!-- Cancellation  Form-->
                                  <form id="form-2" method="POST" id="{{$trip->id}}" action="xtrip/{{$trip->id}}">
                                    @csrf
                                    <button type="submit"  style="border:none" onclick="load_preloader('form-2')">
                                       YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                    </button>
                                  </form>
                                  <!-- End Cancellation  Form-->
                                </span>
                              </div> 
                              <!-- End Cancellation  -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                  <!-- MODAL end -->

                      <!-- Modal For Trip Details-->
                      <div class="modal fade" id="exampleModalLong{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
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
                                </div>
                              </div>
                              <br> <br> <br> <br>
                              <div>
                                </p><b>Car:</b> &nbsp @if($trip->car->name ?? ''){{$trip->car->name}} @else Car has been deleted @endif </p>
                                <p><b>Destination:</b> &nbsp {{$trip->address . ','. ' ' . $trip->lga->name . '.'}} </p>
                                <p><b>Driver:</b> &nbsp @if($trip->driver ?? ''){{$trip->driver->name}}@else Driver has been deactivated  @endif</p>
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
                                      <p><b>Time the trip ended:</b> &nbsp {{date("jS F Y h:i:s a",  strtotime($trip->trip_ended_date))}}</p>
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
                                </p><b>Created on:</b> &nbsp {{$trip->created_at}}</p>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                            </div>
                          </div>
                        </div>
                      </div> <!-- Modal End -->
                    @endforeach
                  </tbody>
                </table>    
                  {{ # Adding links to next and back page to pagination;
                  $trips->links()}}
              </div>
            @else
              <div style="text-align: center" >
                <h6 class ="emptyList"><b>YOu have not embarked on any trip yet </b></h6>
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
  @endsection
    
