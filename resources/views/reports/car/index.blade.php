@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])


@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Car Report</h3></div>
    
    <div class="container" style="margin-top: 20px">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title"><b>Reports</b></h4>
                
                <div class="col-12 mt-2"></div>
              </div>
              <div class="card-body">
                <div class="toolbar">
                  <!--        Here you can write extra buttons/actions for the toolbar              -->
                </div>
                @if(count($reports) > 0)
                    @foreach($reports as $report)
                        <div class=""> 
                            <div class="row">
                                <div class="col-dm-4 col-sm-4" style="padding-left: 5%">
                                  @if($report->car_report_image->first()['name'] ?? '')
                                    <img src="storage/images/car_reports/{{$report->car_report_image->first()['name']}}" 
                                    alt="Car Thumbnail" style="width: 70%;"" height= "150px">
                                  @else 
                                    <img src="{{asset('img/car-icon.png')}}" 
                                    alt="Car Thumbnail" style="width: 70%;"" height= "150px">
                                  @endif
                                  
                                <br> <br> <br>
                                </div>
                                

                                <div class="col-dm-8 col-sm-6">
                                  @if($report->car ?? '')
                                        <h3 style="color:#0C2164 !important">{{$report->car->name}}</h3>
                                  @endif
                                        {{-- <p>{!!str_limit()!!}</p> <a href="/blog/{{$report->title}}" >Read more</a> --}}
                                        <p>{{\Illuminate\Support\Str::limit($report->review, 250)}}</p> <a class="loader" href="/carreport/{{$report->id}}" >See Details</a>
                                        <hr><br> <br>
                                </div>
                            </div>
                        </div>
                    @endforeach
            
               {{ # Adding links to next and back page to pagination;
               $reports->links()}}

                @else
                  <div style="text-align: center" >
                    <p class ="required"><b>No Car report at the moment</b></p>
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
       