<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EditBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public function __construct($id)
    {
        $booking = \App\Models\Booking::where('id', $id)->with('rooms');
        //$booking = \App\Models\Booking::with('tickets');
        $booking->with('departure');
        $booking->with('return');
        $booking->with('user');
        $this->booking = $booking->firstOrFail();
    }

    public function build()
    {
        try {
            return $this
                ->subject(config('app.name') . ': Booking Modified')
                ->to(config('app.admins_emails'))
                ->view('emails.template');

        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }

}
