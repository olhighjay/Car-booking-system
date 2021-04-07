@extends('layouts.app', [
    'namePage' => 'Trips',
    'activePage' => 'trips',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Trip Request</h2></div>
    <br>

    <div class=" justify-content-center align-items-center container">
        <div class="card" style="width:80%; margin-left:10%">
            <div class="card-body" style="padding-left: 10%; padding-right:10%">
                <br> <br>

                <p class="required">Required *</p>
                {{-- <div class="justify-content-center"> --}}
                {{ Form::open(array('url' => 'save-admintrip', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'id'=>'form-1')) }}
                    <div class="form-group">
                        <label for="lga">Destination L.G.A</label> <span class="required">*</span>
                        <select id="lga" class="custom-select form-control" name="lga" required>
                            <option value = ""> -- </option>
                            @foreach ($lgas as $lga)
                                        <option value = "{{$lga->id}}"> {{$lga->name}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        {{form::label('address', 'Address')}} <span class="required">*</span>
                        {{form::text('address', '', ['class'=>'form-control', 'placeholder'=>'Destination Address', 'required'])}}
                    </div>

                    <div class="form-group">
                        <label for="users">Select Passenger</label> <span class="required">*</span>
                        <select id="users" class="custom-select form-control" name="users" required>
                            <option value = "" > -- </option>
                            @foreach ($users as $staff)
                                <option value = "{{$staff->id}}" > {{$staff->name}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group"> <br>
                        <label for="car">Choose a Car</label> <span class="required">*</span>
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
                        <label for="driver">Choose Driver</label> <span class="required">*</span>
                        <br> <small style="color:#9A9A9A">A driver in yellow shows that the driver has been booked</small>
                        <br> <small style="color:#9A9A9A">A driver in red shows that the driver is currently not available</small>
                        <select id="driver" class="custom-select form-control" name="driver" required>
                            @foreach ($drivers as $driver)
                                @if($driver->car_availability->id === 3)
                                    <option value = "{{$driver->id}}" style="color:orange"> {{$driver->name}} </option>
                                @elseif($driver->car_availability->id === 2)
                                    <option value = "{{$driver->id}}" style="color:red" disabled> {{$driver->name}} </option>
                                @else 
                                    <option value = "{{$driver->id}}" > {{$driver->name}} </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="date">Trip Day</label>
                        <input type="date" id="date" name="trip_date" required>
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="start_time">Select a time to start Trip:</label>
                        <input type="time" id="start_time" name="trip_start_time" required>
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="end_time">Select a time to end Trip:</label>
                        <input type="time" id="end_time" name="trip_end_time" required>
                    </div>
                    
                    {{form::submit('Book', ['class'=>'btn btn-primary', 'onclick'=>"load_preloader('form-1')"])}}
                
                {{ Form::close() }}
            </div>
        </div>
    </div> 


@endsection