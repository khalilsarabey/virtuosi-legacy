<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeleteBookingMail extends Mailable
{
    use Queueable, SerializesModels;
    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            return $this
                ->subject(config('app.name') . ': Booking Deleted')
                ->to(config('app.admins_emails'))
                ->view('emails.deleted');

        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }
}
