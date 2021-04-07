<?php

namespace App\Listeners;

use App\Events\SendSwitchedOff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\SwitchOff;
use App\User;
use App\Trip;

class SendSwitchedOffFired
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
     * @param  SendSwitchedff  $event
     * @return void
     */
    public function handle(SendSwitchedOff $event)
    {
        $user = User::find($event->userId)->toArray();
        $trip = Trip::find($event->tripId)->toArray();
        $time = $event->usedTime;
        Mail::to($user['email'])
        ->send(new SwitchOff($trip, $user, $time ));
    }
}
