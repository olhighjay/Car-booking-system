<?php

namespace App\Listeners;

use App\Events\SendDenyTrip;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\DenyTrip;
use App\User;
use App\Trip;

class SendDenyTripFired
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
     * @param  SendDenyTrip  $event
     * @return void
     */
    public function handle(SendDenyTrip $event)
    {
        $user = User::find($event->userId)->toArray();
        $trip = Trip::find($event->tripId)->toArray();
        Mail::to($user['email'])
        ->send(new DenyTrip($trip, $user));
    }
}
