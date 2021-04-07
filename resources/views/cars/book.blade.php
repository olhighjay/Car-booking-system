@extends('layouts.app', [
    'namePage' => 'Trips',
    'activePage' => 'trips',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Trip Request</h2></div>
    

    

    <div class="container" style="background-color: white; margin-top:50px; padding-bottom:100px">   
        

    <br>

        <div class=" justify-content-center align-items-center container " style="width:70% " >

            {{-- <p class="required">Required *</p> --}}
            {{-- <div class="justify-content-center"> --}}
            {{ Form::open(array('url' => 'save-trip', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'id'=>'form-1')) }}
               <div class="form-group">
                    <label for="lga">Destination L.G.A</label>
                    <select id="lga" class="custom-select form-control" name="lga" required>
                        <option value =""> Select LGA </option>
                        @foreach ($lgas as $lga)
                            <option value = "{{$lga->id}}"> {{$lga->name}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    {{form::label('address', 'Address')}}
                    {{form::text('address', '', ['class'=>'form-control', 'placeholder'=>'Destination Address', 'required'])}}
                </div>

                <div class="form-group">
                    <label for="car">Choose a Car</label>
                    <br> <small>A car in yellow shows the car has been booked</small>
                    <select id="car" class="custom-select form-control" name="car" required>
                        <option value =""> Select Car </option>
                        @foreach ($cars as $car)
                            @if($car->car_availability->id === 3)
                                @if($car->id === $pickedCar->id)
                                    <option value = "{{$car->id}}" style="color:orange" selected> {{$car->name}} </option>
                                @else
                                    <option value = "{{$car->id}}" style="color:orange"> {{$car->name}} </option>
                                @endif
                            @else 
                                @if($car->id === $pickedCar->id)
                                    <option value = "{{$car->id}}" selected> {{$car->name}} </option>
                                @else
                                    <option value = "{{$car->id}}"> {{$car->name}} </option>
                                @endif
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
                
                {{form::submit('Book', ['class'=>'btn btn-primary', 'onclick' => "load_preloader('form-1')"])}}
            
            {{ Form::close() }}
        {{-- </div> --}}
        </div>
    </div> 


@endsection