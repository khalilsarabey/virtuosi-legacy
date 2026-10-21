<?php

namespace App\Http\Livewire\Frontend\LowCost\AirArabia;

use App\Mail\NewAirlineBookingEmail;
use App\Models\AirlineBooking;
use App\Models\Integration;
use App\Services\Airlines\AirArabia\AirArabia;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Pnr extends Component
{
    public $error = null;
    public $airline = null;
    public $results = null;
    public $total_amount = 0;
    public $taxes_amount = 0;
    public $amount_no_tax = 0;
    public $booking_ref = null;
    public $just_payment = true;
    public $charge = 0;
    public $amount = 0;
    public $taxes = 0;
    public $booking_id;
    public $transaction_identifier = null;


    public function mount()
    {
        $this->booking_ref = request()->bookref;
        $this->airline = request()->airline;
        $booking = AirlineBooking::findOrFail(request()->booking_id);

        if ($this->airline) {
            if(auth()->user()->type == 'agency' && ($booking->amount_with_markup + $booking->taxes) > auth()->user()->balance) {
                die('Insufficient Balance');
            }
            $this->booking_id = $booking->id;
            $params["booking_ref"] = $this->booking_ref;
            $results = (new AirArabia())->retrievePnr($params);
            if (isset($results["Errors"])) {
                $this->error = $results["Errors"];
            } else {
                $this->results = $results;
                $this->transaction_identifier = $results["transaction_identifier"];
                $this->charge = $results["itin_total_fare"];
                $this->amount = $results["amount"];
                $this->taxes = $results["taxes"];
                $this->amount_no_tax = calculateMarkupPrice($results["amount"], $this->airline, 1, count($results["travellers"]));
                $this->taxes_amount = $results["taxes"];
                $this->total_amount = $this->amount_no_tax + $this->taxes_amount;
            }
        }
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.air-arabia.pnr');
    }
}
