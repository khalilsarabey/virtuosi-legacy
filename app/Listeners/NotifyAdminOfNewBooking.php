<?php

namespace App\Listeners;

use App\Mail\BookingEmail;
use App\Mail\NewBookingEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfNewBooking
{
    public function handle($event)
    {
        $booking = $event->booking;
        // send out email to admin with order details
        Mail::send(new NewBookingEmail($booking->id, auth()->user()->email));
    }
}
