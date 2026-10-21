<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        try {
            return $this
                ->to($this->email)
                ->from($this->agency->mail_sender_email)
                ->subject($this->agency->name . ': New Booking')
                ->bcc($bcc)
                ->view('emails.deleted');
        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }
}
