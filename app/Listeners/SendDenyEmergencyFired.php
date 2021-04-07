<?php

namespace App\Listeners;

use App\Events\SendDenyEmergency;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\DenyEmergency;
use App\User;
use App\Trip;
use App\Car;

class SendDenyEmergencyFired
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
     * @param  SendDenyEmergency  $event
     * @return void
     */
    public function handle(SendDenyEmergency $event)
    {
        $user = User::find($event->userId)->toArray();
        // $trip = Trip::find($event->tripId)->toArray();
        Mail::to($user['email'])
        ->send(new DenyEmergency($user));
    }
}
