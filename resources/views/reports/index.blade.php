@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title"></h3></div>
    <div class="container" >
      <div class="row" style="padding-top: 30px">
        <div class="col-md-1 col-lg-2"></div>
        <div class="col-sm-7 col-md-5 col-lg-4">
          <div class="card">
            {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
            <div class="card-body">
              <a class="loader" href="/carreports" style="text-decoration: none">
                <h5 class="card-title">Car's Reports &nbsp; &nbsp; <i class="fa fa-car fa-2x" aria-hidden="true"></i></h5>
              </a>
              <p class="card-text text-muted"> Reports submitted by users concerning specific vehicles.This helps to have a better 
                maintenance of the vehicles</p>
              {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small>  --}}
                <a class="loader" href="/carreport" type="button" class="btn float-right btn-primary" style="border-radius:30px; padding:5px 15px">Create</a>
              </p> 
            </div>
          </div>
        </div>
        <div class="col-sm-7 col-md-5 col-lg-4">
          <div class="card">
            {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
            <div class="card-body">
              <a class="loader" href="/driverreports" style="text-decoration: none">
                <h5 class="card-title">Driver's Reports &nbsp; &nbsp;  <i class="fa fa-wheelchair-alt fa-2x" aria-hidden="true"></i>
                </h5>
              </a>
              <p class="card-text text-muted">Anonymous reports of drivers to help monitor drivers' character during trips and in the 
                office</p>
              {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small>  --}}
                <a class="loader" href="/reportadriver" type="button" class="btn float-right btn-primary" style="border-radius:30px; padding:5px 15px">Create</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-1 col-lg-2"></div>
      </div>

      <div class="row" >
        <div class="col-md-4 col-lg-4"></div>

        <div class="col-sm-7 col-md-5 col-lg-4">
          <div class="card">
            {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
            <div class="card-body">
              <a class="loader" href="/tripreports" style="text-decoration: none">
                <h5 class="card-title">Trip's Reports &nbsp; &nbsp; <i class="fa fa-road fa-2x" aria-hidden="true"></i></h5>
              </a>
              <p class="card-text text-muted">Report of every trip embarked on. this helps to know the condition of vehicles before and 
                after a particular trip and more details about the trip</p>
              {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small>  --}}
                <a class="loader" href="/mytrips" type="button" class="btn float-right btn-primary" style="border-radius:30px; padding:5px 15px">Create</a>
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-lg-4"></div>
      </div>
      
    </div>
    

@endsection
       