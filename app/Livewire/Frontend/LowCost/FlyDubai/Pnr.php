<?php

namespace App\Livewire\Frontend\LowCost\FlyDubai;

use App\Models\AirlineBooking;
use App\Models\Integration;
use App\Services\Airlines\FlyDubai\FlyDubaiAirline;
use Livewire\Component;

class Pnr extends Component
{
    public $error = null;
    public $airline = null;
    public $results = null;
    public $total_amount = 0;
    public $taxes_amount = 0;
    public $amount_no_tax = 0;
    public $just_payment = true;
    public $pnr_payload = null;

    public function mount()
    {
        $booking_ref = request()->bookref;
        $this->airline = request()->airline;

        $params["booking_ref"] = $booking_ref;
        $res = (new FlyDubaiAirline())->retrievePnr($params);
        $results = $res["results"];

        if (isset($results["error"])) {
            $this->error = $results["error"];
        } else {
            $this->results = $results;
            $this->pnr_payload = $res["pnr_payload"];
            $this->amount_no_tax = calculateMarkupPrice($results["amount"], $this->airline, 1, count($results["travellers"]));
            $this->taxes_amount = $results["taxes"];
            $this->total_amount = $this->amount_no_tax + $this->taxes_amount;
        }
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.fly-dubai.pnr');
    }
}
