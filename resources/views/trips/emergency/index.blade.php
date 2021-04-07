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
            <h4 class="card-title"><b>Emergency Trip Requests</b></h4>
            <a class="loader btn btn-primary btn-round text-white pull-right" href="/adminemergencytrip">Create New</a>
            
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
                      <th>Passenger</th>
                      <th>LGA</th>
                      <th>Destination</th>
                      <th>Trip Date</th>
                      <th>Purpose</th>
                      <th>Estd. Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $c = 1 ?>
                    @foreach ($trips as $trip)
                      <tr>
                        <td>{{$c++}}</td>
                        <td>@if($trip->user ?? ''){{$trip->user->name}}@endif</td>
                        <td>{{$trip->lga->name}}</td>
                        <td>{{$trip->address}}</td>
                        <td>{{Carbon::parse($trip->trip_date . $trip->trip_start_time)->diffForHumans()}}</td>
                        <td>{{$trip->purpose}}</td>
                        <td>
                          @if(Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time)) < 60 )
                            {{Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'minutes'}}
                          @else
                            {{Carbon::parse($trip->trip_end_time)->diffInHours(Carbon::parse($trip->trip_start_time)). 'hrs,' . ' '.
                            Carbon::parse($trip->trip_end_time)->diffInMinutes(Carbon::parse($trip->trip_start_time))%60 . 'mins'}}
                          @endif
                        </td>
                        <td><a class="loader" href="approvetrip/{{$trip->id}}" style="text-decoration: none"><i class="fa fa-eye fa-2x" aria-hidden="true" style="color: blue" title="view"></i></a> &nbsp;
                            <i class="fa fa-times-circle fa-2x" aria-hidden="true" style="color: red" data-toggle="modal" data-target="#cancelETR{{$trip->id}}" title="Dismiss"></i>
                        </td>
                      </tr>

                      <!-- MODAL to Dismiss trip -->
                      <div class="modal fade" id="cancelETR{{$trip->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelETR" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalCenterTitle">You are about to dismiss this emergency trip request</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px; " >
                              <!-- Dismiss Trip -->
                              <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                </button>
                                  &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                  <span>
                                    <!-- Dismiss  Form-->
                                  <form id="form-1" method="POST" id="{{$trip->id}}" action="dismissemergtrip/{{$trip->id}}">
                                    @csrf
                                    <button type="submit"  style="border:none" onclick="load_preloader('form-1')">
                                        YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                    </button>
                                  </form>
                                  <!-- End Diismiss  Form-->
                                </span>
                              </div> 
                              <!-- End Dismiss Trip  -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                      <!-- MODAL end -->

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
    
