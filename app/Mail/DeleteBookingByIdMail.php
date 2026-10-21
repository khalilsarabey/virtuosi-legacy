<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeleteBookingByIdMail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public $ids;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking, $ids = [])
    {
        $this->booking  = $booking;
        $this->ids      = $ids;
    }

    public function build()
    {
        try {
            return $this
                ->subject(config('app.name') . ': Booking Deleted')
                ->to(config('app.admins_emails'))
                ->view('emails.booking_deleted');
        } catch(\Exception $e) {

        }
    }
}
