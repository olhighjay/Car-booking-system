@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Driver Report</h3></div>

    <div class="container" style="margin-top: 20px">
        <div class="row">
          {{-- <div class="col-md-12"> --}}
            <div class="col-md-1 col-lg-2"></div>

            <div class="col-sm-8 col-md-8 col-lg-8">
                <div class="card" >
                    <div class="card-header">
                        @if($report->driver ?? '')
                            <h4 class="card-title"><b>{{$report->driver->name}}</b></h4>
                        @endif
                    </div>
                    <div class="card-body">
                        <div style="padding-left: 5%; padding-top:10%">
                            <h6>Driver</h6>
                            @if($report->driver ?? '')
                                {{$report->driver->name}} <br> <br>
                            @else 
                                Driver has been deleted <br> <br>
                            @endif
                            <h6>Offense</h6>
                            {{$report->offense->name}} <br> <br>
                            <h6>Review</h6>
                            {{$report->review}} <br> <br>
                            <h6>Suggested Punishment</h6>
                            {{$report->solution}} <br> <br>                              
                            <hr>
                            <small>Written on {{$report->created_at}}</small>
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