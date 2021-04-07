@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])
@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">{{$user->roles()->first()['name']}}</h3></div>

<div class="container bootstrap snippets bootdey" style="background-color: white">
    <div class="panel-body inf-content" style="padding-top: 50px;">
        <div class="row" style="padding-bottom: 5%">
            <div class="col-md-6 col-sm-4 col-lg-4">
                <div style="width:70%; height:60%; margin:auto">
                    <img alt="" style="width:100%; height:100%; border-radius:50%" title="" class="img-circle img-thumbnail isTooltip" 
                    src="{{asset('storage/profile-picture/'.$user->display_picture)}}" data-original-title="Usuario"> 
                </div>                 
                <br>
                <!-- Button trigger modal -->
                <div style="text-align: center">
                    <a  href="" data-toggle="modal" data-target="#edituser{{$user->id}}" style="text-decoration: none">
                        <b>Choose Profile Picture</b>
                    </a>
                    <br> <br>
                    <table class="table">
                        <tr>        
                            <td>
                                <h6>Completed Trips</h6>
                                {{count($user->trip()->where('trip_request_id', 4)->get())}}

                            </td>
                            <td>
                                <h6>Canceled Trips</h6>
                                {{count($user->trip()->where('trip_request_id', 5)->get())}}

                            </td>
                        </tr>
                    </table>
                    <div style="margin-top: -10%">
                    <h6>Expired Trips</h6>
                    {{count($user->trip()->where('trip_request_id', 7)->get())}} </div>

                </div>
                
            </div>
            <div class="col-sm-8 col-md-6 col-lg-6">
                {{-- &nbsp; <strong>Information</strong><br> --}}
                <div class="table-responsive"  style="margin-top:20px">
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
                                    {{$user->id}}    
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
                                    {{$user->name}}    
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
                                    {{$user->department->name}}
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
                                    {{$user->roles()->first()['name']}}
                                </td>
                            </tr>
                            <tr>        
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-envelope text-primary"></span> 
                                         Email
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{$user->email}}  
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
                                    {{$user->created_at}}
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
                                    {{$user->updated_at}}
                                </td>
                            </tr>                                    
                        </tbody>
                    </table>
                    <div style="text-align: center">
                        <button class="btn  btn-primary" data-toggle="modal" data-target="#fullEdituser{{$user->id}}" style="border-radius: 30px "> Edit user</button>
                    </div>
                </div>
            </div>
            <div class=" col-lg-2"></div>
        </div>
    </div>
</div>         
    

    <!-- Modal Edit Profile-->
    <div class="modal fade" id="fullEdituser{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="fullEdituser{{$user->id}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                                    <!-- Edit user Profile form -->
                    <form id="form-1{{$user->id}}" action="{{ url('/updatefull/'.$user->id) }}" method="POST" style="margin:auto" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right" >{{ __('Full Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$user->name}}" required autocomplete="name" placeholder="Add Fullname" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" autocomplete="email" placeholder="Add Email" required>

                                @error('email')
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
                                        @if ($department->id === $user->department_id)
                                            <option value = "{{$department->id}}" selected> {{$department->name}} </option>
                                        @else
                                            <option value = "{{$department->id}}"> {{$department->name}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label for="role" class="col-md-4 col-form-label text-md-right">Change role</label>
                            <div class="col-md-6">
                                <select id="role" class="custom-select" name="role">
                                    @foreach ($roles as $role)
                                        @if ($role->id === $user->roles()->first()['id'])
                                            <option value = "{{$role->id}}" selected> {{$role->name}} </option>
                                        @else
                                            <option value = "{{$role->id}}"> {{$role->name}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #888;!important; margin:auto;  padding:5px 20px; border-radius:25px">Cancel</button>
                            <button type="submit" class="btn jayformbutton btn-primary"  style="position: relative; overflow: hidden; margin:auto;  padding:5px 20px; border-radius:25px" onclick="load_preloader('form-1'+{{$user->id}})">Update user</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>  <!-- MODAL -->
    
    <!-- MODAL HERE     -->
    <!-- Modal -->
    <div class="modal fade" id="edituser{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="edituser{{$user->id}}" aria-hidden="true">
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
                    <form id="form-2" action="{{ url('/updatedp/'.$user->id) }}" method="POST" style="margin:auto" enctype="multipart/form-data">
                        @csrf
                        <div class="">
                        {{-- <label for="exampleInputFile"><b>Choose Profile Picture</b></label> <br> --}}
                        <input type="file" class="image" name="profile_picture" id="exampleInputFile" required>
                        </div> 
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #888;!important; margin:auto;  padding:5px 20px; border-radius:25px">Cancel</button>
                            <button type="submit" class="btn btn-primary"  style="position: relative; overflow: hidden; margin:auto;  padding:5px 20px; border-radius:25px" onclick="load_preloader('form-2')">Upload profile picture</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>  <!-- MODAL -->

@endsection