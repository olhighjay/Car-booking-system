<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\SendCreatedMail' => [
            'App\Listeners\SendCreatedTripMail',
        ],
        'App\Events\SendDenyTrip' => [
            'App\Listeners\SendDenyTripFired',
        ],
        'App\Events\SendSwitchedOff' => [
            'App\Listeners\SendSwitchedOffFired',
        ],
        'App\Events\SendSwitchedOn' => [
            'App\Listeners\SendSwitchedOnFired',
        ],
        'App\Events\SendApproveEmergency' => [
            'App\Listeners\SendApproveEmergencyFired',
        ],
        'App\Events\SendDenyEmergency' => [
            'App\Listeners\SendDenyEmergencyFired',
        ],
        'App\Events\EmergencyTripCreatedEvent' => [
            'App\Listeners\EmergencyTripCreatedTrigger',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
