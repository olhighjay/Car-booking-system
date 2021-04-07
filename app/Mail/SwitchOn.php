<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SwitchOn extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $trip;
    public $user;
    public $time;

    public function __construct($trip, $user, $time)
    {
        $this->trip = $trip;
        $this->user = $user;
        $this->time = $time;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.switchon')
        ->subject('Trip - ID'.' '.$this->trip['id'] . ' ' . ' In Progress');
    }
}
