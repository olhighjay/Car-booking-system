@extends('layouts.app', [
    'namePage' => 'emergency',
    'activePage' => 'emergency',
])


@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Trips</h3></div>
<!-- <a href="">Faulty Vehicles</a>
<a href="">Avaialable Vehicles</a> -->
  <div class="container" style="margin-top: 20px">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><b> My Emergency Trip Requests</b></h4>
            <a class=" loader btn btn-primary btn-round text-white pull-right" href="/newemergency">Create New</a>
            
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($trips) > 0)
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>LGA</th>
                      <th>Destination</th>
                      <th>Trip Date</th>
                      <th>Purpose</th>
                      <th>Estd. Time</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $c = 1 ?>
                    @foreach ($trips as $trip)
                      <tr>
                        <td>{{$c++}}</td>
                        <td>{{$trip->lga->name}}</td>
                        <td>{{$trip->address}}</td>
                        <td>{{Carbon::parse($trip->trip_date . $trip->trip_start_time)->diffForHumans()}}</td>
                        <td>@if(!$trip->purpose)
                              -- -- 
                            @else
                              {{$trip->purpose}}
                            @endif
                        </td>
                        <td>
                          @if(Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time)) < 60 )
                            {{Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'minutes'}}
                          @else
                            {{Carbon::parse($trip->trip_end_time)->diffInHours(Carbon::parse($trip->trip_start_time)). 'hrs,' . ' '.
                            Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'mins'}}
                          @endif
                        <td>
                          @if($trip->trip_request->name == 'Approved')
                            <span style="color: green">{{$trip->trip_request->name}}</span>
                          @elseif($trip->trip_request->name == 'Denied')
                            <span style="color: red">{{$trip->trip_request->name}}</span>
                          @elseif($trip->trip_request->name == 'Pending')
                            <span style="color: rgb(17, 0, 174)">{{$trip->trip_request->name}}</span>
                          @endif
                        </td>
                        </td>
                      </tr>

                    @endforeach
                  </tbody>
                </table>    
              </div>
            @else
              <div style="text-align: center" >
                <h6 class ="emptyList"><b>No emergency trip request at the moment </b></h6>
              </div>
            @endif
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>


    <!-- end row -->
  </div>
  @endsection
    
