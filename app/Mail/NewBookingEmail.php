<?php

namespace App\Mail;

use App\Models\Agency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $email;
    public $subject;
    public $agency;
    public $site_settings;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $email, $subject = null)
    {
        $this->email = $email;
        $this->subject = $subject ?? null;
        $booking = \App\Models\Booking::where('id', $id)->with(['rooms', 'user', 'tickets']);
        $this->booking = $booking->firstOrFail();
        $this->agency = Agency::find(auth()->user()->agency_id);
        $this->site_settings = Agency::find($this->booking->agency_id);
    }

    public function build()
    {
        $bcc = config('app.admins_emails');
        if($this->agency->id != 1) {
            $bcc[] = $this->agency->email;
        }
        try {
            $subject = ': New Booking';
            if($this->subject) {
                $subject = ': ' . $this->subject;
            }
            return $this
                ->to($this->email)
                ->from($this->agency->mail_sender_email)
                ->subject($this->agency->name . $subject)
                ->bcc($bcc)
                ->view('emails.template');
        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }


}
