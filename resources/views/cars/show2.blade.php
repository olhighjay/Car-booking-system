@extends('layouts.app',[
    'activePage' => 'vehicles',
    'namePage' => 'vehicles'
])

@section('content')
  <div class="jay-header jay-header-lg"><h3 class="jay-title">Vehicles</h3></div>
  <div class="container" style="margin-top: 20px">
    <div class="card">
      <div class="row">
        <div class="col-12">
          <div class="card-header">
            @if($car->car_availability->id !== 2)
              <a class="btn btn-primary btn-round text-white pull-right" href="/book-car/{{$car->id}}">Book Now</a>
            @endif
            <h3 class="card-title"><b>{{$car->name}}</b></h3>
          </div>
        </div>
      </div>
      <div class="card-body"> 
        <div class="row">
          <div class="col-8">
            <!-- Add color to availability status -->
            @if($car->car_availability->id === 1)
              <b style="color:lawngreen">{{$car->car_availability->name}}</b>
            @elseif($car->car_availability->id === 2)
            <b style="color:red">{{$car->car_availability->name}}</b>
            @elseif($car->car_availability->id === 3)
            <b style="color:orange">{{$car->car_availability->name}}</b>
            @endif
            {{-- <b>{{$car->car_availability->name}}</b> --}}
            <div style="border-right: 1px solid rgb(249, 99, 50, 0.3); text-align: center; padding-top:20px; padding-bottom:50px">
              <img src="{{asset('storage/images/car/'.$car_image)}}" alt="" width="80%" style="max-height:400px">
              <div style="margin-top: 10px"> 
                @foreach ($car_thumbnails as $car_thumbnail)
                  <img src="{{asset('storage/images/car/thumbnail/'.$car_thumbnail->name)}}" alt="" style="padding-right: 20px; padding-top:10px">
                @endforeach
              </div>
            </div>
          </div>
          <div class="col-4" style="padding-left:2px; padding-right:10px">
            <h3> Properties </h3>
            {{$car->properties}}
          </div> 
            {{-- </div>
          </div> --}}
        </div>  
      </div>


      <div class="row">
        <div class="col-12" style="padding: 30px ">
          <div class="col-11">
            <p><b>Name:</b> &nbsp;
              {{$car->name}} 
            </p>
            <p><b>Model:</b> &nbsp;
              {{$car->name}}
            </p>
            <p><b>Brand:</b> &nbsp;
              {{$car->name}} 
            </p>
            <p><b>Description:</b> &nbsp;
              {{$car->description}}
            </p>
            <p><b>Department:</b> &nbsp;
              {{$car->department->name}}
            </p>
            <p><b>Health Status:</b> &nbsp;
              {{$car->car_health_status->name}}
            </p>
            <p><b>availability:</b> &nbsp;
              {{$car->car_availability->name}}</p>
            <p><b>Driver:</b> &nbsp;
              {{$car->name}}
            </p>
            <p><b>Last Trip:</b> &nbsp;
              {{$car->name}}
            </p>
            <p><b>Last Fuel Consumption:</b> &nbsp;
              {{$car->name}}
            </p>
          </div>

          <hr>
          <div>
            <h3 style="text-align:center">TRIPS</h3>
          </div>

          <hr>
          <div>
            <h3 style="text-align:center">FUEL CONSUPTION</h3>

            <!-- Button trigger modal -->
            <span type="button" class="btn btn-default" style="border-radius:30px; padding:5px 15px" data-toggle="modal" data-target="#fuelpurchase">
              Report
            </span>
                                         
                    <!-- MODAL -->
            <div class="modal fade" id="fuelpurchase" tabindex="-1" role="dialog" aria-labelledby="fuelpurchase" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle" >Fuel Purchase</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div style="color: orangered; text-align:center">
                    <small>Note: These inputs can't be edited after submission..</small>
                  </div>
                  <div class="modal-body" style="padding-bottom:80px">
                    <!-- fuelpurchase Form -->
                    <div>
                      <form method="POST" action="/fuelcar/{{$car->id}}">
                        @csrf

                        <div class="form-group">
                          <label for="volume">Volume</label>
                          <input id="volume" class="form-control" type="number" name="volume" placeholder="Input Volume">
                        </div>

                        <div class="form-group">
                          <label for="amount">Amount</label>
                          <input id="amount" class="form-control" type="number" step="0.01" name="amount" placeholder="Input Amount">
                        </div>

                        <div class="form-group row mb-0">
                          <div class="col-md-4 offset-md-4">
                            <button type="submit" class="btn btn-primary jayformbutton form-control">
                              {{ __('Submit') }}
                            </button>
                          </div>
                        </div>
                      </form> 
                    </div> <!-- End Report Form -->
                  </div>
                </div>
              </div>
            </div>  
            <!-- MODAL end -->
          </div>
        </div>
      </div>
    </div>
  </div>      
    
    
@endsection