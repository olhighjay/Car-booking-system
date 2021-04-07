@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Report A Driver</h3></div>
    <div class="container" style="background-color: white; padding-top:50px; padding-bottom:100px">       
        <div class=" justify-content-center align-items-center container " style="width:70%; padding-top:30px ">
            <form id="form-1" method="POST" action="/save-driverreport">
                @csrf
                <span class="jaylabel">Select Driver</span>
                <select id="driver" class="custom-select form-control" name="driver" required>
                    <option value = ""> -- </option>
                    @foreach ($drivers as $driver)
                        <option value = "{{$driver->id}}"> {{$driver->name}} </option>
                    @endforeach
                </select> <br> <br>

                <span class="jaylabel">Reporting for</span>
                <select id="offense" class="custom-select form-control" name="offense" required>
                    <option value = ""> -- </option>
                    @foreach ($offenses as $offense)
                        <option value = "{{$offense->id}}"> {{$offense->name}} </option>
                    @endforeach
                </select> <br> <br>

                <div class="form-group">
                    <label for="note" class="jaylabel">Short Review about Driver</label>
                    <textarea class="form-control"  id="note" name="note" required></textarea>
                </div> <br>

                <div class="form-group">
                    <label for="correction" class="jaylabel">Suggest a Punishment/Correctional method</label>
                    <textarea class="form-control" id="correction" name="correction"></textarea>
                </div>

                

                <div class="form-group row mb-0">
                <div class="col-md-4 offset-md-4">
                    <button type="submit" class="btn btn-primary jayformbutton form-control" onclick="load_preloader('form-1')">
                    {{ __('Submit Report') }}
                    </button>
                </div>
                </div>
            </form> 
        </div>    
    </div> <!-- End Report Form -->
          <br> 

@endsection
       