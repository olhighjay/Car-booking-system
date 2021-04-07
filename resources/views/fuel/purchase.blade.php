@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Car Report</h3></div>
    <div class="container" style="background-color: white; padding:50px; padding-bottom:100px">       
        <div class=" justify-content-center align-items-center container " style="width:70%; padding-top:30px ">
            <form method="POST" action="/save-carreport">
                @csrf
                <span class="jaylabel">Select Car</span>
                <select id="car" class="custom-select form-control" name="car">
                    <option value = ""> -- </option>
                    @foreach ($cars as $car)
                        <option value = "{{$car->id}}"> {{$car->name}} </option>
                    @endforeach
                </select> <br> <br>

                <span class="jaylabel">Car Health Status</span>
                <select id="car_status" class="custom-select form-control" name="car_status">
                    <option value = ""> -- </option>
                    @foreach ($healths as $health)
                        <option value = "{{$health->id}}"> {{$health->name}} </option>
                    @endforeach
                </select> <br> <br>

                <div class="form-group">
                    <label for="note" class="jaylabel">Short Review about the Selected Car</label>
                    <textarea class="form-control"  id="note" name="note"></textarea>
                </div> <br>

                <div class="form-group">
                    <label for="correction" class="jaylabel">Suggest a Solution</label>
                    <textarea class="form-control" id="correction" name="correction"></textarea>
                </div>

                

                <div class="form-group row mb-0">
                <div class="col-md-4 offset-md-4">
                    <button type="submit" class="btn jayformbutton btn-primary form-control">
                    {{ __('Submit Report') }}
                    </button>
                </div>
                </div>
            </form> 
        </div>    
    </div> <!-- End Report Form -->
          <br> 

@endsection
       