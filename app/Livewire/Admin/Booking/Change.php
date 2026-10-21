<?php

namespace App\Livewire\Admin\Booking;

use App\Mail\NewBookingEmail;
use App\Models\Booking;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Change extends Component
{
    public $ticket;
    public $booking;
    public $tickets;
    public $change = [];

    public function mount($id)
    {
        $ticket = Ticket::with('booking')->find($id);
        $this->booking = Booking::with('tickets')->find($ticket->booking_id);
        if($this->booking->dep_flight_id) {
            $this->change['dep_flight_id'] = $this->booking->dep_flight_id;
        }
        if($this->booking->ret_flight_id) {
            $this->change['ret_flight_id'] = $this->booking->ret_flight_id;
        }
    }

    public function render()
    {
        return view('livewire.admin.booking.change')->layout('layouts.admin');
    }

    public function submit()
    {
        $booking = new Booking;
        $booking->user_id               = $this->booking->user_id;
        $booking->agency_id             = $this->booking->agency_id;
        $booking->supplements           = $this->booking->supplements;
        $booking->status                = $this->booking->status;
        $booking->flight_id              = $this->change['dep_flight_id'];
        $booking->dep_flight_id          = $this->change['dep_flight_id'];
        $booking->dep_date              = $this->booking->dep_date;
        $booking->dep_time              = $this->booking->dep_time;

        $booking->dep_from_city         = $this->booking->dep_from_city;
        $booking->dep_to_city           = $this->booking->dep_to_city;
        $booking->dep_from_code         = $this->booking->dep_from_code;
        $booking->dep_to_code           = $this->booking->dep_to_code;
        $booking->dep_flight_number      = $this->booking->dep_flight_number;

        $booking->type                  = $this->booking->type;
        $booking->price                 = $this->booking->price;
        $booking->adults                = $this->booking->adults;
        $booking->children              = $this->booking->children;
        $booking->infants               = $this->booking->infants;
        $booking->adults_price          = $this->booking->adults_price;
        $booking->children_price        = $this->booking->children_price;
        $booking->infants_price         = $this->booking->infants_price;
        $booking->supplements           = $this->booking->supplements;
        $booking->total                 = $this->booking->total;
        $booking->payload               = $this->booking->payload;

        if(array_key_exists('ret_flight_id', $this->change)) {
            $booking->ret_flight_id          = $this->change['ret_flight_id'];
            $booking->ret_from_city         = $this->booking->ret_from_city;
            $booking->ret_to_city           = $this->booking->ret_to_city;
            $booking->ret_from_code         = $this->booking->ret_from_code;
            $booking->ret_to_code           = $this->booking->ret_to_code;
            $booking->ret_date              = $this->booking->ret_date;
            $booking->ret_dep_time          = $this->booking->ret_dep_time;
            $booking->ret_flight_number      = $this->booking->ret_flight_number;
        }

        try {
            DB::beginTransaction();

            $booking->save();

            $departure_tickets = $this->booking->tickets->where('flight_id', $this->booking->dep_flight_id);

            foreach($departure_tickets as $dep_ticket):
                $d = $dep_ticket->replicate();
                $d->booking_id = $booking->id;
                $d->flight_id = $this->change['dep_flight_id'];
                $d->save();
                $dep_ticket->delete();
            endforeach;

            if(array_key_exists('ret_flight_id', $this->change)) {
                $return_tickets = $this->booking->tickets->where('flight_id', $this->booking->ret_flight_id);
                foreach($return_tickets as $ret_ticket):
                    $r = $ret_ticket->replicate();
                    $r->booking_id = $booking->id;
                    $r->flight_id = $this->change['ret_flight_id'];
                    $r->save();
                    $ret_ticket->delete();
                endforeach;
            }
            $this->booking->delete();
            DB::commit();
            try {
                Mail::send(new NewBookingEmail($booking->id, auth()->user()->email));
            } catch(\Exception $e) {

            }
            return redirect()->route('admin.bookings');
        } catch(\Exception $e) {
            DB::rollBack();
            die($e->getMessage());
        }
    }

}
