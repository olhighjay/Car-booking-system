@extends('layouts.app', [
    'namePage' => 'reports',
    'activePage' => 'reports',
])
    <link href="{{asset('/css/sample.css')}}" rel="stylesheet">

@section('content')
    <div class="jay-header jay-header-lg"><h3 class="jay-title">Car Report</h3></div>
    
    <div class="container" style="margin-top: 20px">
        <div class="row">
          {{-- <div class="col-md-12"> --}}
            <div class="col-md-1 col-lg-2"></div>

            <div class="col-sm-8 col-md-8 col-lg-8">
                <div class="card" >
                    <div class="card-header">
                        @if($report->car ?? '')
                            <h4 class="card-title"><b>{{$report->car->name}}</b></h4>
                        @else
                            Car has been deleted!
                        @endif
                        
                        {{-- <div class="col-12 mt-2"></div> --}}
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                        </div>
                        
                        <div class="row">
                            {{-- <div class="col-12"> --}}
                                <div class="col-md-2 col-sm-1 col-lg-2"></div>
                    
                                <div class="col-md-8 col-sm-10 col-lg-8">
                                    {{-- <div id="carouselExampleControls" class="carousel slide" data-interval="4000" data-ride="carousel" >
                                        <div class="carousel-inner">
                                            @foreach ($reportPictures as $reportPicture)
                                                <div class="carousel-item @if ($reportPicture === $reportPictures[0]) active @endif">
                                                    <img class="d-block w-100" src="{{asset('storage/images/car_reports/'.$reportPicture)}}"  alt="Report Image" style = "height:250px">
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    HERE --}}

                                    @if(count($car_thumbnails) > 0)
                                        <div id="carouselExampleControls" class="carousel slide" data-interval="4000" data-ride="carousel" >
                                            <div id="productimagewrap" class=" preview-pic tab-content carousel-inner">
                                                @foreach ($car_thumbnails as $car_thumbnail)
                                                    <div class="carousel-item tab-pane  @if($car_thumbnail === $car_thumbnails[0]) active @endif " id="pic-{{$car_thumbnail->id}}">
                                                        <img src="{{asset('storage/images/car_reports/'.$car_thumbnail->name)}}" alt="Report Image" style = "height:250px; width:100%" />
                                                    </div>
                                                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div style="padding-left:30px">
                                            <ul id="moreimages" class="preview-thumbnail nav nav-tabs">
                                                {{-- <tr> --}}
                                                @foreach ($car_thumbnails as $car_thumbnail)
                            
                                                <li class="">
                                                    <a id="pic-{{$car_thumbnail->id}}" data-target="#pic-{{$car_thumbnail->id}}" data-toggle="tab">
                                                    <img src="{{asset('storage/images/car_reports/thumbnail/'.$car_thumbnail->name)}}" alt="Car Thumbnail" style="padding-right: 20px; padding-top:10px; height:50px; width:70%" />
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul> <br>
                                        </div>
                                    @else
                                        <div>     
                                            <img src="{{asset('img/car-icon.png')}}" 
                                            alt="Car Thumbnail" style = "height:250px; width:100%">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2 col-sm-1 col-lg-2"></div>
                            {{-- </div> --}}
                        </div>

                            <div style="padding-left: 5%; padding-top:10%">
                                <h6>Car Plate Number</h6>
                                @if($report->car ?? '')
                                    {{$report->car->plate_number}} <br>
                                @else 
                                    This Car has been deleted
                                @endif 
                                <h6>Car Health Status</h6>
                                {{$report->car_health_status->name}} <br> <br>
                                <h6>Review</h6>
                                {{$report->review}} <br> <br>
                                <h6>Suggested Solution</h6>
                                {{$report->solution}} <br> 
                                <h6>Author</h6>
                                <td>
                                    @if($report->user->name ?? '')
                                      {{$report->user->name}}
                                    @else 
                                      User has been deleted <br> <br>
                                    @endif
                                  </td>
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
       