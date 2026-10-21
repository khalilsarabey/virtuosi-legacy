<?php

namespace App\Listeners;

use App\Mail\NewBookingEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyClientOfNewBooking
{
    public function handle($event)
    {
        $booking = $event->booking;
        Mail::send(new NewBookingEmail($booking->id, auth()->user()->email));
    }
}
