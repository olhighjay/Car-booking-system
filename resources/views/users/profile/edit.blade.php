@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Profile',
    'activePage' => 'profile',
    'activeNav' => '',
])

@section('content')
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-user">
                  <div class="image">
                      <img src="img/bg5.jpg" alt="...">
                  </div>
                  <div class="card-body">
                      <div class="author">
                          <a href="#">

                            @if(Auth::user()->display_picture)
                              <img class="avatar border-gray" src="{{asset('storage/profile-picture/'.$user->display_picture)}}" alt="...">
                            @else 
                              <img class="avatar border-gray" src="{{asset('/img/default-avatar.png')}}" alt="..."> 
                            @endif
                          </a>
                          <h6>{{$user->name}}</h6>
                          <h6 class="description">
                              {{$user->email}}
                          </h6>

                                        <!-- MODAL HERE     -->

                                        <!-- Button trigger modal -->
                        <a  href="#exampleModalCenter" data-toggle="modal" data-target="#exampleModalCenter" style="text-decoration: none">
                          <b>Choose Profile Picture</b>
                        </a>

                                              <!-- Modal -->
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                  <form id="form-1" action="{{ url('/profile-picture') }}" method="POST" style="margin:auto" enctype="multipart/form-data">
                                      @csrf
                                    <div class="">
                                      {{-- <label for="exampleInputFile"><b>Choose Profile Picture</b></label> <br> --}}
                                      <input type="file" class="image" name="profile_picture" id="exampleInputFile" required>
                                    </div> 
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                                        style="background-color: #888;!important; margin:auto;  padding:5px 20px; border-radius:25px">Cancel</button>
                                        <button type="submit" class="btn btn-primary"  style="position: relative; overflow: hidden; margin:auto;
                                        padding:5px 20px; border-radius:25px" onclick="load_preloader('form-1')">Upload profile picture</button>
                                    </div>
                                  </form>
                              </div>
                              
                          </div>
                          </div>
                      </div>  <!-- MODAL -->
                          
                      <div class="modal fade" id="uploadimageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-12">
                                  <div id="image_demo">

                                  </div>
                                </div>
                                <div class="col-12">
                                  <button class="btn btn-primary" onclick="crop_image(event, {{ Auth::user()->id }})">Show Crop Image</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>


                      </div>
                  </div>
                  <hr>
                  <div class="button-container">
                      <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                      <i class="fab fa-facebook-square"></i>
                      </button>
                      <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                      <i class="fab fa-twitter"></i>
                      </button>
                      <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                      <i class="fab fa-google-plus-square"></i>
                      </button>
                  </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <h5 class="title">{{__(" Edit Profile")}}</h5>
                </div>
                <div class="card-body">
                <form id="form-2" method="POST" action="{{url('update-profile')}}" autocomplete="off"
                  enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('alerts.success')
                    <div class="row">
                    </div>
                      <div class="row">
                          <div class="col-md-7 pr-1">
                              <div class="form-group">
                                  <label>{{__(" Name")}}</label>
                                      <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" 
                                      placeholder="Full name" required>
                                      @include('alerts.feedback', ['field' => 'name'])
                              </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-7 pr-1">
                          <div class="form-group">
                            <label for="exampleInputEmail1">{{__(" Email address")}}</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                            @include('alerts.feedback', ['field' => 'email'])
                          </div>
                        </div>
                      </div>
                    <div class="card-footer ">
                      <button type="submit" class="btn btn-primary btn-round" onclick="load_preloader('form-2')">{{__('Save')}}</button>
                    </div>
                    <hr class="half-rule"/>
                  </form>
                </div>
                <div class="card-header">
                  <h5 class="title">{{__("Password")}}</h5>
                </div>
                <div class="card-body">
                  <form id="form-3" method="post" action="{{url('update-password')}}" autocomplete="off">
                    @csrf
                    @method('put')
                    @include('alerts.success', ['key' => 'password_status'])
                    <div class="row">
                      <div class="col-md-7 pr-1">
                        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                        <label>{{__(" Current Password")}}</label>
                        <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="old_password" placeholder="{{ __('Current Password') }}" type="password"  required>
                        @include('alerts.feedback', ['field' => 'old_password'])
                        </div>
                      </div>
                      </div>
                      <div class="row">
                      <div class="col-md-7 pr-1">
                        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                        <label>{{__(" New password")}}</label>
                        <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" type="password" name="password" required>
                        @include('alerts.feedback', ['field' => 'password'])
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 pr-1">
                        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label>{{__(" Confirm New Password")}}</label>
                            <input class="form-control" placeholder="{{ __('Confirm New Password') }}" type="password" name="password_confirmation" required>
                        </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <button type="submit" class="btn btn-primary btn-round" onclick="load_preloader('form-3')">{{__('Change Password')}}</button>
                    </div>
                  </form>
                </div>
              </div> <!-- card end -->
            </div> <!-- col-8-md end -->
            
        </div>
    </div>
  </div>
@endsection

{{-- <script>
  function showCrop(event, userID) {
    var input = event.target
    if(input.files[0].type == 'image/jpeg' || input.files[0].type == 'image/png' || input.files[0].type == 'image/jpg'){
      $image_crop = $('#image_demo').croppie({
        enableExif:true,
        viewport:{
          width:300,
          height:300,
          type:'square' //circle
        },
        boundary:{
          height:400
        }
      });
       var reader = new FileReader();
       reader.onload = function() {
         $image_crop.croppie('bind', {
           url:reader.result
         }).then(function(){
           console.log('jquery bind complete')
         })
       }
       reader.readAsDataURL(input.files[0]);
       $('#uploadimageModal').modal('show');
    } else {
      alert('Wrong format. Please select an image with one of the following formats (jpg, jepg, png)');
      //clear value for input
    }
  }
  
  function crop_image(event, userID){
    $image_crop.croppie('result', {
      type:'cavas',
      size:'viewport'
    }).then(function(response){
      $.ajax({
        url:'/api/upload_profile_image',
        type:"POST",
        data:{"image":response},
        success:function(data)
        {
          $('#uploadimageModal').modal('hide');
        }
      });
    })
  }
</script> --}}