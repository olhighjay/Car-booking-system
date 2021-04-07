<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $trip;
    public $car;
    public $driver;
    public $user;
    public $lga;

    public function __construct($trip, $car, $driver, $user, $lga)
    {
        $this->trip = $trip;
        $this->car = $car;
        $this->driver = $driver;
        $this->user = $user;
        $this->lga = $lga;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.contactus')
        // ->subject('this is for'.' '. $this->topic);

        return $this->markdown('emails.tripcreated')
        ->subject('Trip - ID'.' '.$this->trip['id'] . ' ' . ' Created Successfully');
    }
}
