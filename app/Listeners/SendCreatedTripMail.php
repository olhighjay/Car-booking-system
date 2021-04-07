<?php

namespace App\Listeners;

use App\Events\SendCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;
use App\User;
use App\Trip;
use App\Car;

class SendCreatedTripMail
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
     * @param  SendCreatedMail  $event
     * @return void
     */
    public function handle(SendCreatedMail $event)
    {
        $user = User::find($event->userId)->toArray();
        $trip = Trip::find($event->tripId)->toArray();
        $car = \App\Car::getCarName($trip['car_id'])->toArray();
        $driver = \App\Trip::getDriverName($trip['id'])->toArray();
        $lga = \App\Lga::getLgaName($trip['lga_id'])->toArray();
        Mail::to($user['email'])
        ->send(new Contact($trip, $car, $driver, $user, $lga));
    }
}
