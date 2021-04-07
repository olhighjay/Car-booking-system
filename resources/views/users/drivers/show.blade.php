@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])
@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Driver</h3></div>

<div class="container bootstrap snippets bootdey" style="background-color: white">
    <div class="panel-body inf-content" style="padding-top: 50px">
        <div class="row">
            <div class="col-md-4">
                <div style="width:70%; height:50%; margin:auto">
                    <img alt="" style="width:100%; height:100%; border-radius:50%" title="" class="img-circle img-thumbnail isTooltip"  
                    src="{{asset('storage/profile-picture/'.$driver->display_picture)}}" data-original-title="Usuario"> 
                </div>
                 
                <br>
                <!-- Button trigger modal -->
                @if(Auth::user()->roles->first()->id === 1 || Auth::user()->roles->first()->id === 2)
                    <div style="text-align: center">
                        <a  href="" data-toggle="modal" data-target="#editDriver{{$driver->id}}" style="text-decoration: none">
                            <b>Choose Profile Picture</b>
                        </a>
                    </div>
                @endif
                <br> <hr> <br>
                <div class="row" style="color: rgb(7, 47, 122)">
                    {{-- <div class="col-12"> --}}
                        <div class="col-md-1">
                        </div>
                        <div class="col-6" style="text-align: center">
                            <h6>Completed Trips</h6>
                            {{count($driver->trip()->where('trip_request_id', 4)->get())}}
                        </div>
                        <div class="col-5" style="text-align: center">
                            <h6>Pending Trips</h6>
                            {{count($driver->trip()->where('trip_request_id', 2)->get())}}
                        </div>
                        {{-- <div class="col-md-1">
                        </div> --}}
                    {{-- </div> --}}
                </div>

           

            </div>
            <div class="col-md-6">
                <strong>Information</strong><br>
                <div class="table-responsive">
                    <table class="table table-user-information">
                        <tbody>
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-asterisk text-primary"></span>
                                        Identificacion                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$driver->id}}    
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-user  text-primary"></span>    
                                        Name                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$driver->name}}    
                                </td>
                            </tr>
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-cloud text-primary"></span>  
                                        Department                                               
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$driver->department->name}}
                                </td>
                            </tr>
        
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-bookmark text-primary"></span> 
                                        Vehicle                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    @if($driver->car->name ?? '' )
                                        {{$driver->car->name}}
                                    @else 
                                        <em>No car attached yet</em>
                                    @endif
                                </td>
                            </tr>
        
        
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-eye-open text-primary"></span> 
                                        Role                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    Driver
                                </td>
                            </tr>
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-envelope text-primary"></span> 
                                        Phone Number                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$driver->phone_number}}  
                                </td>
                            </tr>
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-envelope text-primary"></span> 
                                        Status                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$driver->car_availability->name}}  
                                </td>
                            </tr>
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-calendar text-primary"></span>
                                        Date Created                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$driver->created_at}}
                                </td>
                            </tr>
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-calendar text-primary"></span>
                                        Last Modified                                                
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$driver->updated_at}}
                                </td>
                            </tr>                                    
                        </tbody>
                    </table>
                    @if(Auth::user()->roles->first()->id === 1 || Auth::user()->roles->first()->id === 2)
                        <div style="text-align: center">
                            <button class="btn  btn-primary" data-toggle="modal" data-target="#fullEditDriver{{$driver->id}}" style="border-radius: 30px "> Edit Driver</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>         
    

    <!-- Modal Edit Profile-->
    <div class="modal fade" id="fullEditDriver{{$driver->id}}" tabindex="-1" role="dialog" aria-labelledby="fullEditDriver{{$driver->id}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                                    <!-- Edit Driver Profile form -->
                    <form id="form-1" action="{{ url('/fullUpdate/'.$driver->id) }}" method="POST" style="margin:auto" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Full Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$driver->name}}" required autocomplete="name" placeholder="Add Fullname" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone-number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone-number" type="number" class="form-control @error('phone-number') is-invalid @enderror" name="phone_number" value="{{$driver->phone_number}}" placeholder="Add Phone number" required>

                                @error('phone-number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label for="department" class="col-md-4 col-form-label text-md-right">Change department</label>
                            <div class="col-md-6">
                                <select id="department" class="custom-select" name="department">
                                    @foreach ($departments as $department)
                                        @if ($department->id === $driver->department_id)
                                            <option value = "{{$department->id}}" selected> {{$department->name}} </option>
                                        @else
                                            <option value = "{{$department->id}}"> {{$department->name}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label for="status" class="col-md-4 col-form-label text-md-right">Change Status</label>
                            <div class="col-md-6">
                                <select id="status" class="custom-select" name="status">
                                    @foreach ($statuses as $status)
                                        @if ($status->id === $driver->car_availability_id)
                                            <option value = "{{$status->id}}" selected> {{$status->name}} </option>
                                        @else
                                            <option value = "{{$status->id}}"> {{$status->name}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="background-color: #888;!important; margin:auto;  padding:5px 20px; border-radius:25px">Cancel</button>
                            <button type="submit" class="btn jayformbutton btn-primary"  
                            style="position: relative; overflow: hidden; margin:auto;  padding:5px 20px; border-radius:25px" onclick="load_preloader('form-1')">Update Driver</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>  <!-- MODAL -->
    
    <!-- MODAL HERE     -->
    <!-- Modal -->
    <div class="modal fade" id="editDriver{{$driver->id}}" tabindex="-1" role="dialog" aria-labelledby="editDriver{{$driver->id}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                                    <!-- Profile picture buttons -->
                    <form id="form-2" action="{{ url('/driverprofile/'.$driver->id) }}" method="POST" style="margin:auto" enctype="multipart/form-data">
                        @csrf
                        <div class="">
                        {{-- <label for="exampleInputFile"><b>Choose Profile Picture</b></label> <br> --}}
                        <input type="file" class="image" name="profile_picture" id="exampleInputFile" required>
                        </div> 
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="background-color: #888;!important; margin:auto;  padding:5px 20px; border-radius:25px">Cancel</button>
                            <button type="submit" class="btn btn-primary"  style="position: relative; overflow: hidden; margin:auto;  padding:5px 20px; border-radius:25px" 
                            onclick="load_preloader('form-2')">Upload profile picture</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>  <!-- MODAL -->

@endsection