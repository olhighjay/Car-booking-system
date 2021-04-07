@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Car Report</h3></div>
        <div class=" justify-content-center align-items-center container">
            <div class="card" style="width:80%; margin-left:10%">
                <div class="card-body" style="padding-left: 10%;padding-right: 10%">
                    <br> <br>
                    <form id="form-1" method="POST" action="/save-carreport"  enctype="multipart/form-data">
                        @csrf
                        <span class="jaylabel">Select Car</span>
                        <select id="car" class="custom-select form-control" name="car" required>
                        </form>>
                            <option value = ""> -- </option>
                            @foreach ($cars as $car)
                                <option value = "{{$car->id}}"> {{$car->name}} </option>
                            @endforeach
                        </select> <br> <br>

                        <span class="jaylabel">Car Health Status</span>
                        <select id="car_status" class="custom-select form-control" name="car_status" >
                            <option value = ""> -- </option>
                            @foreach ($healths as $health)
                                <option value = "{{$health->id}}"> {{$health->name}} </option>
                            @endforeach
                        </select> <br> <br>

                        <div class="form-group">
                            <label for="note" class="jaylabel">Short Note about the Selected Car</label>
                            <textarea class="form-control"  id="note" name="note" required></textarea>
                        </div> <br>

                        <div class="form-group">
                            <label for="correction" class="jaylabel">Suggest a Solution</label>
                            <textarea class="form-control" id="correction" name="correction"></textarea>
                        </div>

                        <div class="" >
                            <label for="exampleInputFile"><b>Add Images</b> <em> Not more than 4 Pictures</em></label> <br>
                            <input type="file" class="image" name="images[]" id="exampleInputFile" multiple style="height: 10% !important; width:120%">
                        </div> <br>

                        <br>
                        <div class="form-group row mb-0">
                        <div class="col-md-4 offset-md-4">
                            <button type="submit" class="btn jayformbutton btn-primary form-control" onclick="load_preloader('form-1')">
                            {{ __('Submit Report') }}
                            </button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>    
          <br> 

@endsection
       