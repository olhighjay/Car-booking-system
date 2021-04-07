<?php

namespace App\Listeners;

use App\Events\EmergencyTripCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\EventCreated;
use App\User;
use App\Emergency_trip;


class EmergencyTripCreatedTrigger
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
     * @param  EmergencyTripCreatedEvent  $event
     * @return void
     */
    public function handle(EmergencyTripCreatedEvent $event)
    {
        $user = User::find($event->userId)->toArray();
        $admin = User::find($event->adminId)->toArray();
        $trip = Emergency_trip::find($event->tripId)->toArray();
        Mail::to($admin['email'])
        ->send(new EventCreated($user, $admin, $trip ));
    }
}
