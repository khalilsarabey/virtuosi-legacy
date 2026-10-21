<?php

namespace App\Http\Livewire\Frontend\LowCost\AirArabia;

use App\Services\Airlines\AirArabia\AirArabia;
use Livewire\Component;

class Summary extends Component
{
    public $error = null;
    public $airline = null;
    public $results = null;
    public $passengers = null;
    public $ancillaries_payload = null;
    public $total_amount = 0;
    public $taxes_amount = 0;
    public $amount_no_tax = 0;
    public $access_token = null;
    public $session_timestamp = null;
    public $js_cookie = null;
    public $transaction_identifier = null;

    public function mount()
    {
        $this->access_token = request()->accesstoken;
        $this->session_timestamp = request()->sessiontimestamp;
        $this->js_cookie = request()->jscookie;
        $this->transaction_identifier = request()->transactionidentifier;
        $this->airline = request()->airline;
        $this->passengers = is_array(request()->passengers) ? request()->passengers : json_decode(request()->passengers, true);
        $this->ancillaries_payload = request()->ancillariespayload;
        $params["payload"] = json_decode(request()->payload, true);
        $params["payload2"] = json_decode(request()->payload2, true);
        $params["selected_departure"] = json_decode(request()->departure, true);
        $params["selected_return"] = json_decode(request()->return, true);
        $params["passengers"] = $this->passengers;
        $params["type"] = request()->type;
        $params["ancillaries_payload"] = request()->ancillariespayload;
        $params["access_token"] = $this->access_token;
        $params["session_timestamp"] = $this->session_timestamp;
        $params["js_cookie"] = $this->js_cookie;
        $params["transaction_identifier"] = $this->transaction_identifier;
        if ($this->airline) {
            $resl = (new AirArabia())->confirmationStep($params);
            if (isset($resl["Errors"])) {
                $this->error = $resl["Errors"];
            } else {
                $this->results = $resl;
                $this->amount_no_tax = calculateMarkupPrice($resl["amount"], $this->airline, 1, count($this->results["travellers"]));
                $this->taxes_amount = $resl["taxes"];
                $this->total_amount = $this->amount_no_tax + $this->taxes_amount;
            }
        }
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.air-arabia.summary');
    }
}
