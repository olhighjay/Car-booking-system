<?php

namespace App\Listeners;

use App\Events\SendSwitchedOn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\SwitchOn;
use App\User;
use App\Trip;

class SendSwitchedOnFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendSwitchedOn  $event
     * @return void
     */
    public function handle(SendSwitchedOn $event)
    {
        $user = User::find($event->userId)->toArray();
        $trip = Trip::find($event->tripId)->toArray();
        $time = $event->estmTime;
        Mail::to($user['email'])
        ->send(new SwitchOn($trip, $user, $time));
    }
}
