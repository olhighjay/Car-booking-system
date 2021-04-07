<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class UserNotificationsController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','verified']);;
    }

    
    public function show()
    {
        // All notifications
        $allNotifications = auth()->user()->notifications;
        // Notifications
        $notifications = auth()->user()->unreadNotifications->sortByDesc('created_at');
        // Read notifications
        $readNotifications = auth()->user()->readNotifications->sortByDesc('created_at');

        //delete notifications that have been read for a month
        foreach($readNotifications as $readNotification){
            $readTime = Carbon::parse($readNotification->read_at);
            $readForAMonth = $readTime->addMonth(); 
            $currentNow = Carbon::now();
           
            if($currentNow  > $readForAMonth){
                $readNotification->delete();
            }
        }
        
        $user = Auth::user();

        // Mark the notifications as read
        $notifications->markAsRead();
        return view('notifications.show', [
            'readNotifications' =>$readNotifications,
            'notifications' =>$notifications,
            'user'=>$user,
            'allNotifications'=> $allNotifications
        ]);
    }
}
