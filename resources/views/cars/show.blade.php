@extends('layouts.app',[
    'activePage' => 'vehicles',
    'namePage' => 'vehicles'
])

    
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{asset('/css/sample.css')}}" rel="stylesheet">
    
    


  
@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Vehicles</h3></div>
	
	<div class="container">
		<div class="card">
			<div class="">
				<div class=" row">
					<div class="preview col-md-6 col-lg-6">
            <img src="{{asset('storage/images/car/'.$car_thumbnails->first()['name'])}}" alt="Car Thumbnail" style = "height:250px; width:100%"  />
              <br>
            
            <div style="padding-left:50px">
              <div style="text-align: center; margin-left:-15%"> 
                {{-- <div class="action"> --}}
                  @if($car->car_availability->id !== 2)
                    <button class="add-to-cart btn btn-default" type="button" style="border-radius:30px"><a class="loader" href="/book-car/{{$car->id}}" style="color: white; text-decoration:none; border-radius:30px"> Book Now</a></button>
                  @endif
                  @if(Auth::user()->roles->first()->id === 1 || Auth::user()->roles->first()->id === 2)
                    <a class="btn btn-primary" style="border-radius:30px; padding:5px 15px; color:white" type="button" data-toggle="modal" data-target="#fuelpurchase">
                      Add Fuel &nbsp; <i class="fa fa-tint" aria-hidden="true"></i>
                    </a> 
                    <a class="btn btn-primary" style="border-radius:30px; padding:5px 15px; color:white; background-color: brown" type="button" data-toggle="modal" data-target="#repair">
                      Repair &nbsp; <i class="fa fa-wrench" aria-hidden="true"></i>
                    </a>
                  @endif
              </div> <br>
          
          </div>
          </div>
					<div class="details col-md-6 col-lg-6">
						<h3 class="product-title">{{$car->name}}</h3>
						<div>
              @if($car->car_availability->id === 1)
                  <span class="available">{{{$car->car_availability->name}}} </span>
              @elseif($car->car_availability->id === 2)
                  <span class="unavailable">{{{$car->car_availability->name}}} </span>
              @elseif($car->car_availability->id === 3)
                  <span class="booked">{{{$car->car_availability->name}}} </span>
              @endif
              
						</div> <br>
						<p class="product-description">{{$car->description}}</p>
						<h6 class="price">current health status: <span>{{$car->car_health_status->name}}</span></h6>
                        <p class="vote"><small><strong>Created </strong> {{$car->created_at->diffForHumans()}}</small> <br>
						<small><strong>Last Updated </strong> {{$car->updated_at->diffForHumans()}}</small></p>

						<h5 class="sizes">trips:
							<span class="size" data-toggle="tooltip" title="small">{{count($trips)}}</span>
						</h5>
                <div>
                  @if(Auth::user()->roles->first()->id === 1 || Auth::user()->roles->first()->id === 2)
                    <a class="loader" href="/editcar/{{$car->id}}" class="btn btn-primary" style="border-radius:30px; padding:5px 15px; text-decoration:none; color:white; background-color: rgba(7, 7, 82, 0.836)" type="button">Edit</a>
                    <a class="btn btn-primary" style="border-radius:30px; padding:5px 15px; color:white" type="button" data-toggle="modal" data-target="#deleteCar{{$car->id}}">Delete</a>
                  @elseif(Auth::user()->roles->first()->id === 3)
                    <a class="btn btn-primary" style="border-radius:30px; padding:5px 15px; color:white" type="button" data-toggle="modal" data-target="#fuelpurchase">
                      Add Fuel &nbsp; <i class="fa fa-tint" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-primary" style="border-radius:30px; padding:5px 15px; color:white; background-color: brown" type="button" data-toggle="modal" data-target="#repair">
                      Repair &nbsp; <i class="fa fa-wrench" aria-hidden="true"></i>
                    </a>
                  @endif

                </div>
					</div>
        </div>
        <br> <br>
        <h4 class="product-title">Car Details</h4>
        <div style="width: 80%"> 
          <hr >
          <p class="vote"><b>Name:</b> &nbsp;
              {{$car->name}} 
          </p>
          <p class="vote"><b>Model:</b> &nbsp;
          {{$car->name}}
          </p>
          <p class="vote"><b>Brand:</b> &nbsp;
          {{$car->name}} 
          </p>
          <p class="vote"><b>Description:</b> &nbsp;
          {{$car->description}}
          </p>
          <p class="vote"><b>Properties:</b> &nbsp;
          {{$car->properties}}
          </p>
          <p class="vote"><b>Plate number:</b> &nbsp;
            {{$car->plate_number}}
          </p>
          <p class="vote"><b>Department:</b> &nbsp;
          {{$car->department->name}}
          </p>
          <p class="vote"><b>Health Status:</b> &nbsp;
          {{$car->car_health_status->name}}
          </p>
          <p class="vote"><b>availability:</b> &nbsp;
          {{$car->car_availability->name}}</p>
          <p class="vote"><b>Driver:</b> &nbsp;
              @foreach ($drivers as $driver)
                  {{$driver->name}} &nbsp;
              @endforeach
          </p>
          <p class="vote"><b>Last Trip Destination:</b> &nbsp;
              @if($lastTrip ?? '')
                  {{$lastTrip->address}}, {{$lastTrip->lga->name}}.
              @else 
                  <em> This car hasn't embarked on any trip yet </em>
              @endif
          </p>
          <p class="vote"><b>Last Fuel Consumption:</b> &nbsp;
          {{$car->name}}
          </p>
        </div>
          <br> <br> <br>
          <!-- Only show these if logged in user is an admin or accountant -->
        @if (Auth::user()->can('admin') || Auth::user()->can('accountant'))
            <h4 style="text-align:center" class="product-title">Car Trips</h4>
            <hr style="width: 75%">
            <h4 class="card-title"> <b>Trips in the last 30 days</b> </h4>
            <small> This is the list of all the trips for the last 30 days </small>

            <div class="form-inline pull-right"> 
              <form id="form-1" class="form-inline" method="POST" action="/cartrips/{{$car->id}}">
                @csrf
                <label for="month">Select:</label>&nbsp;
                <input type="month" id="month" class="custom-select form-control" name="month" required> &nbsp;
                <button class="jayformbutton btn btn-primary" style="border-radius:30px; padding:5px 15px" type="submit" onclick="load_preloader('form-1')">View trips</button>
              </form>
                
            </div>
            &nbsp; &nbsp;
            {{-- <hr style="width: 75%"> --}}
            
            @if(count($ltdTrips) > 0)
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>
                          {{-- <small class="label">Select all</small>
                          <input class="jay-check" tabindex="1" id="parent" type="checkbox" onclick="checkAll()" checked /> --}}
                      </th>
                      <th>ID</th>
                      <th>Passenger</th>
                      <th>Driver</th>
                      <th>Destination</th>
                      <th>Date</th>
                      <th>Time Used</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($ltdTrips as $trip)
                      <tr id="trip{{$trip->id}}">
                        <td>
                          {{-- <input class="jay-check" id="child_chkbox" type="checkbox" checked /> --}}
                        </td>
                        <td>{{$trip->id}}</td>
                        <td>
                          @if($trip->user()->first()['name'] ?? '')
                            {{$trip->user()->first()['name']}}
                          @else 
                            User has been deleted <br> <br>
                          @endif
                        </td>
                        <td>
                          @if($trip->driver ?? '')
                            {{$trip->driver->name}} <br> <br>
                          @else 
                            Driver has been deleted <br> <br>
                          @endif
                        </td>
                        <td>{{$trip->address . ','. ' ' . $trip->lga->name}}</td>
                        <td>
                          {{Carbon::parse($trip->trip_date)->format('jS F Y')}}
                        </td>
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
                        </td>
                        <td>
                      </tr>

                    @endforeach
                  </tbody>
                </table>  
                {{-- {{ # Adding links to next and back page to pagination;
                  $trips->links()}}   --}}
              </div>
            @else
              <div style="text-align: center" >
              <h6 style="color: slategray"><b>This car didn't embark on any trip in the last 30 days </b></h6>
              </div>
            @endif

            <br> <br> <br> <br>
            <!-- FUEL -->
            <div>
              <h4 style="text-align:center" class="product-title">Car Fuel Records</h4>
              <hr style="width: 75%">

              <h4 class="card-title"><b>Records for {{date('F')}}</b></h4>
              <div class="form-inline pull-right"> 
              <form id="form-2" class="form-inline" method="POST" action="/fuel/{{$car->id}}/records">
                  @csrf
                  <label for="month">Select:</label> &nbsp;
                  <input type="month" id="month" class="custom-select form-control" name="month" required> &nbsp;
                  <button class="jayformbutton btn btn-primary" style="border-radius:30px; padding:5px 15px" type="submit" onclick="load_preloader('form-2')">View records</button>
              </form>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              
              </div>
              <small> This is the list of all the fuel records for this month </small>
              
              @if(count($records) > 0)
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Date</th>
                      <th>Issuer</th>
                      <th>Volume (ltrs)</th>
                      <th>Amount ₦</th>
                    </tr>
                  </thead>
                  <tfoot>
                        <tr>
                        <th></th>
                        <th></th>
                        <th>Total</th>
                        <th>{{$totalVolume}} </th>
                        <th>₦{{number_format($totalAmount)}}</th>
                        </tr>
                  </tfoot>

                  <tbody>
                    <?php $c=1; ?>
                    @foreach ($records as $record)
                      <tr>
                        <td style="width: 10%">{{$c++}}</td>
                        <td>{{Carbon::parse($record->created_at)->format('jS F Y')}}</td>
                        <td>
                          @if($record->user->name ?? '')
                            {{$record->user->name}}
                          @else 
                            User has been deleted <br> <br>
                          @endif
                        </td>
                        <td>{{$record->volume}}</td>
                        <td>{{number_format($record->amount)}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>    
                  {{-- {{ # Adding links to next and back page to pagination;
                  $records->links()}} --}}
              </div>
              @else
                <div style="text-align: center" >
                  <h6 style="color: slategray"><b>No Fuel Record for this Period </b></h6>
                </div>
              @endif
            </div>

            <br> <br> <br> <br>
            <!-- REPAIR -->
          <div> 
            <h4 style="text-align:center" class="product-title">Car Repair Records</h4>
              <hr style="width: 75%">
            <h4 class="card-title"><b>Records for {{date('F')}}</b></h4>
            <div class="form-inline pull-right"> 
              <form id="form-3" class="form-inline" method="POST" action="/repair/{{$car->id}}">
                  @csrf
                  <label for="month">Select:</label> &nbsp;
                  <input type="month" id="month" class="custom-select form-control" name="month" required> &nbsp;
                  <button class="jayformbutton btn btn-primary" style="border-radius:30px; padding:5px 15px" type="submit" onclick="load_preloader('form-3')">View records</button>
              </form>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              {{-- <div>
                  <a href="/car/car->id"  style="margin-top: -10px; float: right; text-decoration:none">View trips for the last 30 days </a>
              </div> --}}
            </div>
            <small> This is the list of all the vehicle repairs made in this month </small>
            &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">

            <div class="col-12 mt-2"></div>
            @if(count($repRecords) > 0)
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Date</th>
                      <th>Issuer</th>
                      <th>Fault</th>
                      <th>Solution</th>
                      <th>Amount ₦</th>
                    </tr>
                  </thead>
                  <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total</th>
                            <th>₦{{number_format($totalRepAmount)}}</th>
                        </tr>
                  </tfoot>

                  <tbody>
                    <?php $c=1; ?>
                    @foreach ($repRecords as $rRecord)
                      <tr>
                        <td style="width: 10%">{{$c++}}</td>
                        <td>{{Carbon::parse($rRecord->created_at)->format('jS F Y')}}</td>
                        <td>
                          @if($rRecord->user->name ?? '')
                            {{$rRecord->user->name}}
                          @else 
                            User has been deleted <br> <br>
                          @endif
                        </td>
                        <td>{{$rRecord->fault}}</td>
                        <td>{{$rRecord->solution}}</td>
                        <td>{{number_format($rRecord->amount)}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>    
                  {{-- {{ # Adding links to next and back page to pagination;
                  $records->links()}} --}}
              </div>
            @else
              <div style="text-align: center" >
                <h6 style="color: slategray"><b>No Repair Record for this Period </b></h6>
              </div>
            @endif
          </div>

        @endif
      </div>
    </div>
  </div>

             
              <!-- MODAL Fuel Purchase-->
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
                        <form id="form-4" method="POST" action="/fuelcar/{{$car->id}}">
                          @csrf

                          <div class="form-group">
                            <label for="volume">Volume</label>
                            <input id="volume" class="form-control" type="number" name="volume" placeholder="Input Volume" required>
                          </div>

                          <div class="form-group">
                            <label for="amount">Amount ₦</label>
                            <input id="amount" class="form-control" type="number" step="0.01" name="amount" placeholder="Input Amount" required>
                          </div>

                          <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-4">
                              <button type="submit" class="btn btn-primary jayformbutton form-control" onclick="load_preloader('form-4')">
                                {{ __('Submit') }}
                              </button>
                            </div>
                          </div>
                        </form> 
                      </div> <!-- End Fuel Purchase Form -->
                    </div>
                  </div>
                </div>
              </div>  
              <!-- MODAL end -->

              <!-- MODAL Repair-->
              <div class="modal fade" id="repair" tabindex="-1" role="dialog" aria-labelledby="repair" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="repairTitle" >Vehicle Repair</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div style="color: orangered; text-align:center">
                      <small>Note: These inputs can't be edited after submission..</small>
                    </div>
                    <div class="modal-body" style="padding-bottom:80px">
                      <!-- Repair Form -->
                      <div>
                        <form id="form-5" method="POST" action="/repair/{{$car->id}}">
                          @csrf

                          <div class="form-group">
                            <label for="fault">Fault</label>
                            <textarea id="fault" class="form-control" name="fault" placeholder="What is wrong with this car??" required></textarea>
                          </div>

                          <div class="form-group">
                            <label for="solution">Solution</label>
                            <textarea id="solution" class="form-control" name="solution" placeholder="What is the solution??"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="amount">Amount ₦</label>
                            <input id="amount" class="form-control" type="number" step="0.01" name="amount" placeholder="Input Amount" required>
                          </div>

                          <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-4">
                              <button type="submit" class="btn btn-primary jayformbutton form-control" onclick="load_preloader('form-5')">
                                {{ __('Submit') }}
                              </button>
                            </div>
                          </div>
                        </form> 
                      </div> <!-- End Repair Form -->
                    </div>
                  </div>
                </div>
              </div>  
              <!-- MODAL end -->

              <!-- MODAL For Car delete-->
              <div class="modal fade" id="deleteCar{{$car->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteUser" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="deleteCar">You are about to delete {{$car->name}}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" style="padding-bottom:80px; " >
                      <!-- Car delete -->
                      <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                        <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                        <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                        </button>
                         &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                         <span>
                           <!-- Car delete  Form-->
                          <form method="POST" id="form-6" action="/deletecar/{{$car->id}}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit"  style="border:none" >
                               YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true" onclick="load_preloader('form-6')"></i>
                            </button>
                          </form>
                          <!-- End Car delete  Form-->
                        </span>
                      </div> 
                      <!-- End Car delete  -->
                      <br> 
                    </div>
              
                  </div>
                </div>
              </div>  
              <!-- MODAL end -->
@endsection


    <script>
        $('#moreimages a').each(function() {
            $(this).on("click", function() {
                console.log('here');
                var attr = $(this).attr('id');
                $('#productimagewrap div').each(function() {
                    $('#productimagewrap div').removeClass("active")
                });
                    var hey = "#"+ $(attr).selector;
                var aimg = $(hey);
                // console.log($(attr).selector);
                // console.log($(aimg));
                $(aimg).addClass("active")
                // $('#productimagewrap img').attr('src', $(aimg).attr('src'));
            });
        });
    </script>
  {{-- </body>
</html> --}}
