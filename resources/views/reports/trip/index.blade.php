@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Reports</h3></div>
    
    <div class="container" style="margin-top: 20px">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                <h4 class="card-title"></h4>
                <div class="col-12 mt-2"></div>
              </div> {{-- <div class="card-header">
                <h4 class="card-title"><b>Reports</b></h4>
                
                <div class="col-12 mt-2"></div>
              </div> --}}
              <div class="card-body">
                <div class="toolbar">
                  <!--        Here you can write extra buttons/actions for the toolbar              -->
                </div>                  

                <div class="container-xl">
                 @if(count($trips) > 0)
                    <div class="table-responsive">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-md-8 col-lg-6 col-sm-8"><h3>Trip <b>Reports</b></h3></div>
                                    <div class="col-sm-1 col-md-2 col-lg-6">
                                        <div class="search-box" style="float: right; width:50%">
                                            {{-- <i class="material-icons">&#xE8B6;</i> --}}
                                            <input id="myInput" type="search" class="form-control" placeholder="Search" type="search">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="datatable" class="table table-hover">
                                <thead>
                                    <tr>
                                      <th>Trip ID</th>
                                      <th>Car <i class="fa fa-sort"></i></th>
                                      <th>Car Status <i class="fa fa-sort"></i></th>
                                      <th>LGA <i class="fa fa-sort"></i></th>
                                      <th>Address</th>
                                      <th>Trip Summary <i class="fa fa-sort"></i></th>
                                      <th>Complaints</th>
                                      <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach ($trips as $trip)                                       
                                    <tr>
                                        <td>{{$trip->id}}</td>
                                        <td>
                                          @if($trip->car ?? '')
                                            {{$trip->car->name}}
                                          @endif
                                        </td>
                                        <td>
                                          @if($trip->car ?? '')
                                            {{$trip->car->car_health_status->name}}
                                          @endif
                                        </td>
                                        <td>{{$trip->lga->name}}</td>
                                        <td>{{$trip->address}}</td>
                                        <td>{{\Illuminate\Support\Str::limit($trip->summary, 50)}}</td>
                                        <td>{{Illuminate\Support\Str::limit($trip->complaints, 50)}}</td>

                                        <td><a class="loader" href="/tripreport/{{$trip->id}}" style="text-decoration:none"> view details</a></td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>
                            {{ # Adding links to next and back page to pagination;
                                $trips->links()}}  
                        </div>
                    </div>
                  @else
                    <div style="text-align: center" >
                      <p class ="required"><b>No Trip report at the moment</b></p>
                    </div>
                  @endif
                </div>   
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
       