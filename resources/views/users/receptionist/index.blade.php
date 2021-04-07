@extends('layouts.app', [
    'namePage' => 'User Management',
    'activePage' => 'user-management',
])
@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Receptionists</h3></div>
  <div class="container" >
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <a class="btn btn-primary btn-round text-white pull-right" href="/new-user">Create New</a>
            <h3 class="card-title">Receptionists</h3>
            <small> This is the list of all the receptionists </small>
            &nbsp; &nbsp; &nbsp;
            <input id="myInput" class="jay-input-field" style="width: 200px;" type="search" placeholder="Search..">
            <div class="col-12 mt-2"></div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            <div class="card-body">
              <div class="toolbar">
                <!--        Here you can write extra buttons/actions for the toolbar              -->
              </div>
              @if(count($users) > 0)
                <div class="table-responsive">
                  <div class="table-wrapper">
                    <table id="datatable" class="table table-hover" cellspacing="0" style="width:100%;">
                      <thead>
                        <tr>
                          <th>S/N</th>
                          <th>Thumbnail</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Department</th>
                          <th>Status</th>
                          <th class="disabled-sorting text-right">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $c=1 ?>
                          @foreach ($users as $user)
                          <tr>
                            <td>{{$c++}}</td>
                            <td>
                              <span class="avatar avatar-sm rounded-circle">
                                  @if($user->display_picture)
                                    <img src="{{asset('storage/profile-picture/'.$user->display_picture)}}" alt="" style="max-width: 80px; border-radiu: 100px">
                                  @else
                                    <img src="{{asset('storage/profile-picture/default-avatar.png')}}" alt="" style="max-width: 80px; border-radiu: 100px">
                                  @endif 
                                  &nbsp; 
                                
                              </span> </td>
                            <td> <a href="/viewuser/{{$user->id}}">{{$user->name}} </a></td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->department->name}}</td>
                            <td>
                              @if($user->suspension_id === 1)
                                <i class="fa fa-circle" style="color: green; font-size:10px" aria-hidden="true"></i>
                                Active
                              @else 
                                <i class="fa fa-circle danger" style="color: red; font-size:10px" aria-hidden="true"></i>
                                Deactivated
                              @endif 
                              </td>
                              <td class="text-right">
                                <a  href="#exampleModalCenter" type="button" title="Update Role" class="btn btn-default btn-icon btn-sm "
                                data-toggle="modal" data-target="#exampleModalCenter{{$user->id}}" style="text-decoration: none; background-color: blue">
                                <i class="fa fa-pencil "></i>
                                </a>
                                          <!-- MODAL -->
                                  <div class="modal fade" id="exampleModalCenter{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalCenterTitle">Edit user</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body" style="padding-bottom:80px">
                                          <!-- Edit Department Form -->
                                          <div>
                                            <form method="POST" action="/update-users-dept/{{$user->id}}">
                                              @csrf
                                              <input type="hidden" name="_method" value="PUT">
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
                              
                                              <div class="form-group row mb-0">
                                                <div class="col-md-6 offset-md-4">
                                                  <button type="submit" class="btn btn-primary">
                                                    {{ __('Update department') }}
                                                  </button>
                                                </div>
                                              </div>
                                            </form> 
                                          </div> <!-- End Department Form -->
                                          <br>  <hr>
                                          <!-- Edit Role Form -->
                                          <div>
                                            <form method="POST" action="/update-users-role/{{$user->id}}">
                                              @csrf
                                              <input type="hidden" name="_method" value="PUT">
                                              <div class="form-group row" >
                                                  <label for="role" class="col-md-4 col-form-label text-md-right">Change Role</label>
                                                  <div class="col-md-6">
                                                      <select id="role" class="custom-select" name="role">
                                                          @foreach ($roles as $role)
                                                              @if ($role->id === $user->roles()->first()->id)
                                                                  <option value = "{{$role->id}}" selected> {{$role->name}} </option>
                                                              @else
                                                                  <option value = "{{$role->id}}"> {{$role->name}} </option>
                                                              @endif
                                                          @endforeach
                                                      </select>
                                                  </div>
                                              </div>
                              
                                              <div class="form-group row mb-0">
                                                  <div class="col-md-6 offset-md-4">
                                                      <button type="submit" class="btn btn-primary">
                                                          {{ __('Update Role') }}
                                                      </button>
                                                  </div>
                                              </div>
                                            </form>
                                          </div> <!-- End Department Form -->
                                        </div>            
                                      </div>
                                    </div>
                                  </div>  
                                  
                                @if($user->suspension_id === 1)
                                  <a type="button" href="#" rel="tooltip" class="btn btn-primary btn-icon btn-sm " data-original-title="" title="Deactivate"
                                  data-toggle="modal" data-target="#deactivate{{$user->id}}">
                                    <i class="fa fa-ban "></i>
                                  </a>
                                @elseif($user->suspension_id === 2)
                                  <a type="button" href="#" rel="tooltip" class="btn btn-success btn-icon btn-sm " data-original-title="" title="Activate" 
                                  data-toggle="modal" data-target="#activate{{$user->id}}">
                                    <i class="fa fa-check"></i>
                                  </a>
                                @endif
                                
                                <a type="button" href="#" rel="tooltip" class="btn btn-danger btn-icon btn-sm " data-original-title="" title="Delete"
                                data-toggle="modal" data-target="#deleteUser{{$user->id}}">
                                  <i class="fa fa-trash "></i>
                                </a>
                              </td>
                          </tr>
  
                          <!-- MODAL For User Deactiivation-->
                      <div class="modal fade" id="deactivate{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelreport" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="deactivate">You are about to deactivate {{$user->name}}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px; " >
                              <!-- Deactiivation -->
                              <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                </button>
                                &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                <span>
                                  <!-- DeactiivationDeactiivation form  Form-->
                                  <form method="POST" id="deactivate{{$user->id}}" action="suspenduser/{{$user->id}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <button type="submit"  style="border:none" >
                                      YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                    </button>
                                  </form>
                                  <!-- End Deactivation  Form-->
                                </span>
                              </div> 
                              <!-- End Deactivation  -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                  <!-- MODAL end -->
  
  
                      <!-- MODAL For User Actiivation-->
                      <div class="modal fade" id="activate{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelreport" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="deleteuser">You are about to activate {{$user->name}}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px; " >
                              <!-- Actiivation -->
                              <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                </button>
                                  &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                  <span>
                                    <!-- ActiivationDeactiivation form  Form-->
                                  <form method="POST" id="{{$user->id}}" action="unsuspenduser/{{$user->id}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <button type="submit"  style="border:none" >
                                        YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                    </button>
                                  </form>
                                  <!-- End Activation  Form-->
                                </span>
                              </div> 
                              <!-- End Activation  -->
                              <br> 
                            </div>
                      
                          </div>
                        </div>
                      </div>  
                      <!-- MODAL end -->
  
                      <!-- MODAL For User delete-->
                      <div class="modal fade" id="deleteUser{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelreport" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="deleteUser">You are about to delete {{$user->name}}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body" style="padding-bottom:80px; " >
                              <!-- User delete -->
                              <div style=" text-align:center; padding-top:30px; display:inline-flex !important" >
                                <p><b>Do you want to continue?</b></p> &nbsp; &nbsp; &nbsp;
                                <button type="button" style="border:none" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">NO <i class="fa fa-times fa-2x" style="color:red"  aria-hidden="true"></i></span>
                                </button>
                                &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; 
                                <span>
                                  <!-- User delete  Form-->
                                  <form method="POST" id="{{$user->id}}" action="deleteuser/{{$user->id}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit"  style="border:none" >
                                      YES <i class="fa fa-check fa-2x" style="color:green"  aria-hidden="true"></i>
                                    </button>
                                  </form>
                                  <!-- End User delete  Form-->
                                </span>
                              </div> 
                              <!-- End User delete  -->
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
                </div>
              @else
                <div style="text-align: center" >
                  <h6 class ="emptyList"><b>No receptionist created yet</b></h6>
                </div>
              @endif
            </div>
            <!-- end content-->
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
    
