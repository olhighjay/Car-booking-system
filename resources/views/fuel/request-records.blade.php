@extends('layouts.app', [
    'namePage' => 'Fuel Purchases',
    'activePage' => 'fuel puurchase',
])


@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Fuel RecordFuels</h3></div>
<!-- <a href="">Faulty Vehicles</a>
<a href="">Avaialable Vehicles</a> -->
  <div class="container" style="margin-top: 20px">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><b>Fuel Records for {{date('F Y', strtotime($month))}}</b></h4>
            <div class="form-inline pull-right"> 
                <form class="form-inline" method="POST" id="form-1" action="/fuel/records">
                    @csrf
                    <label for="month">Select:</label>&nbsp;
                    <input type="month" id="month" class="custom-select form-control" name="month" required> &nbsp;
                    <button class="jayformbutton btn btn-primary" style="border-radius:30px; padding:5px 15px" 
                    type="submit" onclick="load_preloader('form-1')">View records</button>
                </form>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                {{-- <div>
                    <a href="/car/car->id"  style="margin-top: -10px; float: right; text-decoration:none">View trips for the last 30 days </a>
                </div> --}}
            </div>
            <small> This is the list of all the fuel recordFuels for this month </small>
            &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">

            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            @if(count($recordFuels) > 0)
              <div class="table-responsive">
                <table id="datatable" class="table" cellspacing="0" style="width:100%;">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Date</th>
                      <th>Issuer</th>
                      <th>Car</th>
                      <th>Volume (ltrs)</th>
                      <th>Amount ₦</th>
                     </tr>
                  </thead>
                  <tfoot>
                        <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total</th>
                        <th>{{$totalVolume}} </th>
                        <th>₦{{number_format($totalAmount)}}</th>
                        </tr>
                  </tfoot>

                  <tbody>
                    <?php $c=1; ?>
                    @foreach ($recordFuels as $recordFuel)
                      <tr>
                        <td style="width: 10%">{{$c++}}</td>
                        <td>{{Carbon::parse($recordFuel->created_at)->format('jS F Y')}}</td>
                        <td>
                          @if($recordFuel->user->name ?? '')
                            {{$recordFuel->user->name}}
                          @else 
                            User has been deleted <br> <br>
                          @endif
                        </td>
                        <td>
                          @if($recordFuel->car->name ?? '')
                            {{$recordFuel->car->name}}
                          @else 
                            Car has been deleted <br> <br>
                          @endif
                        </td>
                        <td>{{$recordFuel->volume}}</td>
                        <td>{{number_format($recordFuel->amount)}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>    
                  {{-- {{ # Adding links to next and back page to pagination;
                  $recordFuels->links()}} --}}
              </div>
            @else
              <div style="text-align: center" >
                <p class ="required"><b>No Fuel Record for this Period </b></p>
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
    
