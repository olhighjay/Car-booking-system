@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Trip Report</h3></div>

    <div class="container" style="margin-top: 20px">
        <div class="row">
          {{-- <div class="col-md-12"> --}}
            <div class="col-md-1 col-lg-2"></div>

            <div class="col-sm-8 col-md-8 col-lg-8">
                <div class="card" >
                    <div class="card-header">
                        <h4 class="card-title"><b>TRIP ID - {{$trip->id}}</b></h4>
                    </div>
                    <div class="card-body">
                        <div style="padding-left: 5%">
                            <th>Trip ID</th>
                            {{$trip->id}}
                            <h6>Passenger</h6>
                            @if($trip->user()->first()['name'] ?? '')
                                {{$trip->user()->first()['name']}} <br> <br>
                            @endif
                            <h6>Driver</h6>
                                @if($trip->driver()->first() ?? '')
                                    {{$trip->driver()->first()['name']}} <br> <br>
                                @else 
                                    Driver has been deleted
                                @endif
                            <h6>Car</h6>
                                @if($trip->car ?? '')
                                    {{$trip->car->name}} <br> <br>
                                @else 
                                    Car has been deleted
                                @endif
                            <h6>LGA</h6>
                            {{$trip->lga->name}} <br> <br>
                            <h6>Address</h6>
                            {{$trip->address}} <br> <br>
                            <h6>Trip Summary</h6>
                            {{$trip->summary}} <br> <br>
                            <h6>Complaints</h6>
                            {{$trip->complaints}} <br> <br>
                            <h6>Accident</h6>
                            {{$trip->accident}} <br> <br>  
                            <h6>Car Health Status</h6>
                            @if($trip->car ?? '')
                                {{$trip->car->car_health_status->name}} <br> <br>
                            @else 
                                --- <br><br>
                            @endif
                            <h6>Trip date</h6>
                            {{$trip->trip_date}} <br> <br>
                            <hr style="width: 70%">
                            <div style="text-align:center">
                            <small>You can view the full details of the trip <a class="loader" href="/trips#trip{{$trip->id}}">here</a></small>
                            </div>
                        </div>
                    </div>
                <!-- end content-->

                </div>
                <!--  end card  -->

            </div>
            <!-- end col-md-7 -->

            <div class="col-md-1 col-lg-2"></div>

            {{-- </div> --}}
            <!-- end col-md-12 -->

        </div>
        <!-- end row -->
        
    </div>
@endsection