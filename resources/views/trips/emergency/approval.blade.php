@extends('layouts.app', [
    'namePage' => 'emergency',
    'activePage' => 'emergency',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Trip Request</h2></div>
    

    

    <div class="container" style="text-align:center">   
        <div class="card" style="width: 70%; text-align:center">
            <div class="card-header">
              <h4 class="card-title"><b>Emergency Trip Requests</b></h4>
  
              <div class="col-12 mt-2"></div>
            </div>
            <div class="card-body">

                <div>
                    <p><b>Trip ID:</b> &nbsp {{$trip->id}} </p>
                    <p><b>Passenger:</b> &nbsp @if($trip->user->name ?? ''){{$trip->user->name}} @endif </p>
                    <p><b>Destination:</b> &nbsp {{$trip->address . ','. ' ' . $trip->lga->name . '.'}} </p>
                    <p><b>Purpose:</b> &nbsp {{$trip->purpose}} </p>
                    <p><b>Passenger role:</b> &nbsp {{$trip->user_role}} </p>
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
                </div>
                <br>
                <?php 
                   $extra_grace = Carbon::parse($trip->trip_date. ' ' . $trip->trip_start_time);
                    $cancelGrace = $extra_grace->addMinutes(15);
                    //Get the carbonized extra time grace of pending trip
                    $parsedExtraTime = Carbon::parse($cancelGrace);
                    //Get the carbonized current time
                    $now = Carbon::now();
                ?>

                @if($now > $parsedExtraTime)
                    <br>
                    <h2>TRIP REQUEST EXPIRED!</h2>
                    <br> <hr>
                    Click on the button below to delete the Request
                    <br>
                    <form method="POST" id="{{$trip->id}}" action="/dismissexemergtrip/{{$trip->id}}">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="border:none" >
                            Delete
                        </button>
                      </form>
                @else
                    <div class=" justify-content-center align-items-center container " style="width:70% " >
                        <br> <br>

                            <a class="btn btn-default" aria-hidden="true" data-toggle="modal" data-target="#cancelETR{{$trip->id}}" 
                              title="Dismiss" style="color: white">Cancel</a>
                        

                        {{-- <div class="justify-content-center"> --}}
                        {{ Form::open(array('url' => 'saveapproval/'.$trip->id, 'method'=>'POST', 'enctype'=>'multipart/form-data', 'id'=>'form-1')) }}


                            <div class="form-group"> <br>
                                <label for="car">Choose a Car</label>
                                <br> <small style="color:#9A9A9A">A car in yellow shows that the car has been booked</small>
                                <br> <small style="color:#9A9A9A">A car in red shows that the car is currently not available</small>
                                <select id="car" class="custom-select form-control" name="car" required>
                                    @foreach ($cars as $car)
                                        @if($car->car_availability->id === 3)
                                            <option value = "{{$car->id}}" style="color:orange"> {{$car->name}} </option>
                                        @elseif($car->car_availability->id === 2)
                                            <option value = "{{$car->id}}" style="color:red" disabled> {{$car->name}} </option>
                                        @else 
                                            <option value = "{{$car->id}}" > {{$car->name}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="driver">Choose Driver</label>
                                <br> <small style="color:#9A9A9A">A driver in yellow shows that the driver has been booked</small>
                                <br> <small style="color:#9A9A9A">A driver in red shows that the driver is currently not available</small>
                                <select id="driver" class="custom-select form-control" name="driver" required>
                                    @foreach ($drivers as $driver)
                                        @if($driver->car_availability->id === 3)
                                            <option value = "{{$driver->id}}" style="color:orange"> {{$driver->name}} </option>
                                        @elseif($car->car_availability->id === 2)
                                            <option value = "{{$driver->id}}" style="color:red" disabled> {{$driver->name}} </option>
                                        @else 
                                            <option value = "{{$driver->id}}" > {{$driver->name}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>


                            {{form::submit('Approve', ['class'=>'btn btn-primary', 'onclick'=>"load_preloader('form-1')"])}}
                        
                        {{ Form::close() }}
                    </div>

                     <!-- MODAL to Dismiss trip -->
                     <div class="modal fade" id="cancelETR{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelETR" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalCenterTitle">You are about to dismiss this emergency trip request</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px; " >
                              <!-- Dismiss Trip -->
                              <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                </button>
                                  &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                  <span>
                                    <!-- Dismiss  Form-->
                                  <form id="form-2" method="POST" id="{{$trip->id}}" action="/dismissemergtrip/{{$trip->id}}">
                                    @csrf
                                    <button type="submit"  style="border:none" onclick="load_preloader('form-2')">
                                        YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                    </button>
                                  </form>
                                  <!-- End Diismiss  Form-->
                                </span>
                              </div> 
                              <!-- End Dismiss Trip  -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                      <!-- MODAL end -->
                @endif
            </div>
        </div> 


@endsection