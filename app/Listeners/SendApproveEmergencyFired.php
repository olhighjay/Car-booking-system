<?php

namespace App\Listeners;

use App\Events\SendApproveEmergency;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\ApproveEmergency;
use App\User;
use App\Trip;
use App\Car;

class SendApproveEmergencyFired
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
     * @param  SendApproveEmergency  $event
     * @return void
     */
    public function handle(SendApproveEmergency $event)
    {
        $user = User::find($event->userId)->toArray();
        $trip = Trip::find($event->tripId)->toArray();
        $car = \App\Car::getCarName($trip['car_id'])->toArray();
        $driver = \App\Trip::getDriverName($trip['id'])->toArray();
        $lga = \App\Lga::getLgaName($trip['lga_id'])->toArray();
        Mail::to($user['email'])
        ->send(new ApproveEmergency($trip, $car, $driver, $user, $lga));
    }
}
