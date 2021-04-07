@extends('layouts.app', [
    'namePage' => 'notifications',
    'activePage' => 'notifications',
])


@section('content')
<div class="jay-header jay-header-lg"><h3 class="jay-title">Notifications</h3></div>
<div class="container" style="padding:3% 7%">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"></h5>
            <p class="card-text"></p>
        
            
            @if(count($allNotifications)> 0)
                <ul>
                    @foreach ($notifications as $notification)
                        <li>
                            @if($notification->type === 'App\Notifications\TripCreated')
                                <div  class="notification-shadow">
                                    <b> Your trip ID - {{$notification->data['trip']['id']}} has been created successfully. See the Receptionist to start the trip when it's time <a class="loader" href="/mytrips#{{$notification->data['trip']['id']}} ">view</a>   </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($notification->type === 'App\Notifications\TripCancelled')
                                <div  class="notification-shadow">
                                    <b> You have cancelled your trip ID - {{$notification->data['trip']['id']}}. Kindly ensure you update the trip driver about the cancellation  <a class="loader" href="/mytrips#{{$notification->data['trip']['id']}} ">view</a>  </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($notification->type === 'App\Notifications\TripDenied')
                                <div  class="notification-shadow">
                                    <b> Your trip ID - {{$notification->data['trip']['id']}} has been denied by the Admin. Kindly co ntact the Admin for more inmformation <a class="loader" href="/mytrips#{{$notification->data['trip']['id']}} ">view</a> </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($notification->type === 'App\Notifications\TripSwitchedOn')
                                <div  class="notification-shadow">
                                    <b> Your trip ID - {{$notification->data['trip']['id']}} has started. We wish you safe trip <a class="loader" href="/mytrips#{{$notification->data['trip']['id']}} ">view</a> </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($notification->type === 'App\Notifications\TripSwitchedOff')
                                <div  class="notification-shadow">
                                    <b> You have completed your trip ID - {{$notification->data['trip']['id']}}. Kindly drop your trip report on the trip table <a class="loader" href="/mytrips#{{$notification->data['trip']['id']}} ">view</a> </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($notification->type === 'App\Notifications\EmergencyApproved')
                                <div  class="notification-shadow">
                                    <b> Your emergency trip has been approved. Check your mail and trip table for more details <a class="loader" href="/mytrips#{{$notification->data['trip']['id']}} ">view</a> </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($notification->type === 'App\Notifications\EmergencyDenied')
                                <div  class="notification-shadow">
                                    <b> Your emergency trip was aborted. You can contact the admin for more details.  <a class="loader" href="/mytrips} ">view your trips</a> </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($notification->type === 'App\Notifications\EmergencyTripCreated')
                                <div  class="notification-shadow">
                                    <b>  <a href="" style="color: #757575 !important">{{$notification->data['trip']['trip']['name']}}</a> Has just requested for an emergency trip. <a class="loader" href="/approvetrip/{{$notification->data['trip']['trip']['id']}}">Approve/Cancel</a> &nbsp; <a href="/emertrips">View</a> </b>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$notification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @endif
                        </li>
                    @endforeach
                    {{-- @if(count($readNotifications) > 0 ) --}}
                    @foreach ($readNotifications as $readNotification)
                        <li>
                            @if($readNotification->type === 'App\Notifications\TripCreated')
                                <div  class="notification-shadow">
                                    Your trip ID - {{$readNotification->data['trip']['id']}} has been created successfully. See the Receptionist to start the trip when it's time <a class="loader" href="/mytrips#{{$readNotification->data['trip']['id']}}">view</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($readNotification->type === 'App\Notifications\TripCancelled')
                                <div  class="notification-shadow">
                                    You have cancelled your trip ID - {{$readNotification->data['trip']['id']}}. Kindly ensure you update the trip driver about the cancellation <a class="loader" href="/mytrips#{{$readNotification->data['trip']['id']}}">view</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($readNotification->type === 'App\Notifications\TripDenied')
                                <div  class="notification-shadow">
                                    Your trip ID - {{$readNotification->data['trip']['id']}}. has been denied by the Admin. Kindly co ntact the Admin for more inmformation <a class="loader" href="/mytrips#{{$readNotification->data['trip']['id']}}">view</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($readNotification->type === 'App\Notifications\TripSwitchedOn')
                                <div  class="notification-shadow">
                                    Your trip ID - {{$readNotification->data['trip']['id']}} has started. We wish you safe trip <a class="loader" href="/mytrips#{{$readNotification->data['trip']['id']}} "> view</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($readNotification->type === 'App\Notifications\TripSwitchedOff')
                                <div  class="notification-shadow">
                                    You have completed your trip ID - {{$readNotification->data['trip']['id']}}. Kindly drop your trip report on the trip table <a class="loader" href="/mytrips#{{$readNotification->data['trip']['id']}} ">view</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($readNotification->type === 'App\Notifications\EmergencyApproved')
                                <div  class="notification-shadow">
                                    Your emergency trip has been approved. Check your mail and trip table for more details <a class="loader" href="/mytrips#{{$readNotification->data['trip']['id']}} ">view</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($readNotification->type === 'App\Notifications\EmergencyDenied')
                                <div  class="notification-shadow">
                                    Your emergency trip was aborted. You can contact the admin for more details.  <a class="loader" href="/mytrips} ">view your trips</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @elseif($readNotification->type === 'App\Notifications\EmergencyTripCreated')
                                <div  class="notification-shadow">
                                    <a href="" style="color: #757575 !important">{{$readNotification->data['trip']['trip']['name']}}</a> Has just requested for an emergency trip. <a class="loader" href="/approvetrip/{{$readNotification->data['trip']['trip']['id']}}">Approve/Cancel</a> &nbsp; <a href="/emertrips">View</a>
                                    <br>
                                    <small style="margin-top: -10px"> <em>{{$readNotification->created_at->diffForHumans()}}</em></small>     
                                    <hr style="margin-left:0; width:70%"> 
                                </div> <br>
                            @endif 
                        </li>
                    @endforeach
                    {{-- @endif --}}
                </ul>
            @else 
                You curently have no notifications 
            @endif
            <br> <br> <br>
        </div>
    </div>
</div>
    
@endsection