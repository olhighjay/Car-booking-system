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
            <h4 class="card-title"><b>Active Trips</b></h4>
            <small> Trips are to be switched on at the point of giving out the car key to the Driver </small>
            
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($trips) > 0)
              &nbsp; &nbsp;
              <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Car</th>
                      <th>Driver</th>
                      <th>Passenger</th>
                      <th> Date</th>
                      <th> Start time</th>
                      <th> End time</th>
                      <th> Time Used</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                  
                  <tbody>
                    
                    @foreach ($trips as $trip)
                      <tr> 
                        <td>{{$trip->id}}</td>
                        <td>@if($trip->car ?? ''){{$trip->car->name}}@endif</td>
                        <td>
                          @if($trip->driver->name ?? '')
                            {{$trip->driver->name}}
                          @else 
                            Driver has been deleted
                          @endif
                        </td>
                        <td> 
                          @if($trip->user()->first()['name'] ?? '')
                            {{$trip->user()->first()['name']}}
                          @else 
                            User has been deleted
                          @endif
                        </td>
                        <td>{{Carbon::parse($trip->trip_date . $trip->trip_start_time)->diffForHumans()}}</td>
                        <td>{{date("h:i:s a", strtotime($trip->trip_start_time))}}</td>
                        <td>{{date("h:i:s a", strtotime($trip->trip_end_time))}}</td>
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
                          </p>
                          
                        </td>
                        <td>{{$trip->trip_request->name}}</td>
                        <td >
                          @if($trip->trip_request->id === 2)
                            <span type="button" class="btn btn-primary" style="border-radius:30px; padding:5px 15px" data-toggle="modal" data-target="#starttrip{{$trip->id}}">
                              Start Trip
                            </span>
                          @elseif($trip->trip_request->id === 3)
                            <span type="button" class="btn btn-defauly" style="border-radius:30px; padding:5px 18px; background-color:blue !important" data-toggle="modal" data-target="#stoptrip{{$trip->id}}">
                              End Trip
                            </span>
                          @endif
                        </td>
                      </tr>

                        <!-- MODAL to start trip -->
                        <div class="modal fade" id="starttrip{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="starttrip" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">You are about to start trip - ID: {{$trip->id}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body" style="padding-bottom:80px; " >
                                <!-- Start Trip -->
                                <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                  <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                  <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                  </button>
                                    &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                    <span>
                                      <!-- Start  Form-->
                                    <form id="form-1" method="POST" id="{{$trip->id}}" action="ontrip/{{$trip->id}}">
                                      @csrf
                                      <button type="submit"  style="border:none" onclick="load_preloader('form-1')">
                                          YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                      </button>
                                    </form>
                                    <!-- End Start  Form-->
                                  </span>
                                </div> 
                                <!-- End Start Trip  -->
                                <br> 
                              </div>
                        
                            </div>
                          </div>
                        </div>  
                    <!-- MODAL end -->


                    <!-- MODAL Stop Trip -->
                    <div class="modal fade" id="stoptrip{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="stoptrip" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">You are about to end trip - ID: {{$trip->id}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" style="padding-bottom:80px; " >
                            <!-- Stop Trip -->
                            <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                              <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                              <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                              </button>
                                &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                <span>
                                  <!-- Stop Form-->
                                <form id="form-2" method="POST" id="{{$trip->id}}" action="offtrip/{{$trip->id}}">
                                  @csrf
                                  <button type="submit"  style="border:none" onclick="load_preloader('form-2')">
                                      YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                  </button>
                                </form>
                                <!-- End Stop Form-->
                              </span>
                            </div> 
                            <!-- End Stop Trip  -->
                            <br> 
                          </div>
                    
                        </div>
                      </div>
                    </div>  
                <!-- MODAL end -->
                    @endforeach
                  </tbody>
                </table>    
              </div>
            @else
              <div style="text-align: center" >
                <h6 class ="emptyList"><b>No Active Trip at the moment  </b></h6>
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
    
