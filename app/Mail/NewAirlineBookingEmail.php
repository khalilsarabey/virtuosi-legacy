<?php

namespace App\Mail;

use App\Models\Agency;
use App\Models\AirlineBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAirlineBookingEmail extends Mailable
{
    use Queueable, SerializesModels;
    public string $email;
    public mixed $agency;
    public string $airline;
    public $booking;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($airline, $id, $user)
    {
        $this->airline  = $airline;
        $this->email    = auth()->user()->email;
        $this->agency   = Agency::find(auth()->user()->agency_id);
        $this->booking = AirlineBooking::find($id);
    }

    public function build()
    {
        $bcc = config('app.admins_emails');
        $subject = $this->agency->name . ':' . $this->booking->airline . ' Booking Info';
        try {
            return $this
                ->to($this->email)
                ->from($this->agency->mail_sender_email)
                ->subject($subject)
                ->bcc($bcc)
                ->view('emails.airline-template');
        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }
}
