@extends('layouts.app', [
    'namePage' => 'Emergency',
    'activePage' => 'emergency',
])

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Emergency Trip Request</h2></div>
    

    

    <div class="container" style="background-color: white; margin-top:50px; padding-bottom:100px">   
        

    <br>

        <div class=" justify-content-center align-items-center container " style="width:70% " >
            <br> <br>
            <p class="required">Required *</p>
            {{-- <div class="justify-content-center"> --}}
            {{ Form::open(array('url' => 'storeemergency', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'id'=>'form-1')) }}

                <div class="form-group">
                    <label for="lga">Destination L.G.A</label> <span class="required">*</span>
                    <select id="lga" class="custom-select form-control" name="lga" required>
                        <option value = ""> -- </option>
                        @foreach ($lgas as $lga)
                                    <option value = "{{$lga->id}}"> {{$lga->name}} </option>
                        @endforeach
                    </select>
                </div> <br>

                <div class="form-group">
                    {{form::label('address', 'Address')}} <span class="required">*</span>
                    {{form::text('address', '', ['class'=>'form-control', 'placeholder'=>'Destination Address', 'required'])}}
                </div> <br>

                <div class="form-group">
                    {{form::label('Purpose', 'Purpose')}}
                    {{form::textArea('purpose', '', ['class'=>'form-control', 'placeholder'=>'Trip Purpose'])}}
                </div>
                <br>

                <div class="form-group">
                    <label for="date">Trip Day</label> <span class="required">*</span>
                    <input type="date" id="date" name="trip_date" required>
                </div>
                <br>

                <div class="form-group">
                    <label for="start_time">Select a time to start Trip:</label> <span class="required">*</span>
                    <input type="time" id="start_time" name="trip_start_time" required>
                </div>
                <br>

                <div class="form-group">
                    <label for="end_time">Select a time to end Trip:</label> <span class="required">*</span>
                    <input type="time" id="end_time" name="trip_end_time" required>
                </div>
                
                {{form::submit('Book', ['class'=>'btn btn-primary', 'onclick'=>"load_preloader('form-1')"])}}
            
            {{ Form::close() }}
        {{-- </div> --}}
        </div>
    </div> 


@endsection