<?php

namespace App\Livewire\Frontend\LowCost\AirArabia;

use App\Services\Airlines\AirArabia\AirArabia;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Booking extends Component
{
    public $error = null;
    public $data;
    public $type;
    public $initLoad = false;
    public $countries = [];
    public $airline = null;
    public $departure_from = null;
    public $departure_to = null;
    public $departure_from_date = null;
    public $departure_to_date = null;
    public $departure_flight_number = null;
    public $departure_stops = [];
    public $return_from = null;
    public $return_to = null;
    public $return_from_date = null;
    public $return_to_date = null;
    public $return_flight_number = null;
    public $return_stops = [];
    public $adult = 1;
    public $child = 0;
    public $infant = 0;
    public $fares = [
        "departure_fares" => [],
        "return_fares" => []
    ];
    public $passenger_codes = [];
    public $payload = null;
    public $payload2 = null; //payload from getPrice
    public $selected = [
        "departure" => [],
        "return" => [],
    ];
    public $total_no_tax = 0;
    public $total = 0;
    public $ancillaries = [];
    public $params = [];
    public $fares_selected = false;
    public $access_token = null;
    public $session_timestamp = null;
    public $js_cookie = null;
    public $transaction_identifier = null;


    public function mount()
    {
    }

    public function init()
    {
        $departure_info = json_decode($this->data['departure'], true);
        $return_info = isset($this->data['return']) ? json_decode($this->data['return'], true) : null;
        $this->airline = $departure_info["airline"];
        $this->payload = $departure_info["payload"];
        $this->type = 'oneway';
        $this->countries = countries();
        $this->adult = $this->data['adult'] + 0;
        $this->child = $this->data['child'] + 0;
        $this->infant = $this->data['infant'] + 0;
        $this->departure_from = $departure_info["origin"];
        $this->departure_to = $departure_info["destination"];
        $this->departure_from_date = $departure_info["departure_time"];
        $this->departure_to_date = $departure_info["arrival_time"];
        $this->departure_flight_number = $departure_info["flight_number"];
        $this->departure_stops = $departure_info["stop_details"];
        $this->access_token = $departure_info["access_token"] ?? null;
        $this->session_timestamp = $departure_info["session_timestamp"] ?? null;
        $this->js_cookie = $departure_info["js_cookie"] ?? null;

        $this->params["is_return"] = false;

        if ($return_info) {
            $this->type = 'roundtrip';
            $this->return_from = $return_info["origin"];
            $this->return_to = $return_info["destination"];
            $this->return_from_date = $return_info["departure_time"];
            $this->return_to_date = $return_info["arrival_time"];
            $this->return_flight_number = $return_info["flight_number"];
            $this->return_stops = $return_info["stop_details"];

            $this->params["is_return"] = true;
            $this->params["return_from_date"] = $this->return_from_date;
            $this->params["return_to_date"] = $this->return_to_date;
            $this->params["return_flight_number"] = $this->return_flight_number;
            $this->params["return_from"] = $this->return_from;
            $this->params["return_to"] = $this->return_to;
        }

        //$this->fares["departure_fares"] = FlyDubaiSupport::getFareTypes($this->payload, $this->departure_flight_number, $this->departure_from_date);
        //$this->results = (new $airlines[$this->airline]['class'])->confirmationStep($params);
        $airlines = config("airlines");
        $this->params["payload"] = $this->payload;
        $this->params["departure_flight_number"] = $this->departure_flight_number;
        $this->params["departure_from_date"] = $this->departure_from_date;
        $this->params["departure_to_date"] = $this->departure_to_date;
        $this->params["departure_from"] = $this->departure_from;
        $this->params["departure_to"] = $this->departure_to;
        $this->params["adult_count"] = (int)$this->adult;
        $this->params["child_count"] = (int)$this->child;
        $this->params["infant_count"] = (int)$this->infant;
        $this->params["sol_id"] = null;
        $this->params["departure_selected"] = false;
        $this->params["access_token"] = $this->access_token;
        $this->params["session_timestamp"] = $this->session_timestamp;
        $this->params["js_cookie"] = $this->js_cookie;

        $fare_results = (new AirArabia())->getFlightFareTypes($this->params);
        if (isset($fare_results["Errors"])) {
            $this->error = $fare_results["Errors"];
            return;
        }

        $this->transaction_identifier = $fare_results["transaction_identifier"] ?? null;
        $this->params["transaction_identifier"] = $this->transaction_identifier;
        $this->js_cookie = $fare_results["js_cookie"] ?? $this->js_cookie;
        $this->passenger_codes = $fare_results["fares"]["passengers"];
        unset($fare_results["fares"]["passengers"]);
        $this->fares["departure_fares"] = $fare_results["fares"];
        $this->payload2 = $fare_results["payload"];

        if ($this->params["is_return"]) {
            $this->params["departure_selected"] = true;
            $this->params["payload"] = $this->payload2;
            //$this->params["is_return"] = $this->type == 'roundtrip';
            $fare_results = (new AirArabia())->getFlightFareTypes($this->params);
            if (isset($fare_results["Errors"])) {
                $this->error = $fare_results["Errors"];
                return;
            }
            $this->transaction_identifier = $fare_results["transaction_identifier"] ?? null;
            //$this->js_cookie = $fare_results["js_cookie"] ?? $this->js_cookie;
            unset($fare_results["fares"]["passengers"]);
            $this->fares["return_fares"] = $fare_results["fares"];
            $this->payload2 = $fare_results["payload"] ?: $this->payload2;
        }

        if (isset($this->fares["departure_fares"])) {
            foreach ($this->fares["departure_fares"] as $fkey => $dfare) {
                $this->fares["departure_fares"][$fkey]["amount_no_tax"] = calculateMarkupPrice($this->fares["departure_fares"][$fkey]["amount_no_tax"], $this->airline, isset($this->fares["return_fares"]) && count($this->fares["return_fares"]) ? 2 : 1, $this->adult + $this->child + $this->infant);
                $this->fares["departure_fares"][$fkey]["amount"] = $this->fares["departure_fares"][$fkey]["amount_no_tax"] + $this->fares["departure_fares"][$fkey]["taxes"];
            }
        }

        if (isset($this->fares["return_fares"])) {
            foreach ($this->fares["return_fares"] as $frkey => $rfare) {
                $this->fares["return_fares"][$frkey]["amount_no_tax"] = calculateMarkupPrice($this->fares["return_fares"][$frkey]["amount_no_tax"], $this->airline, 2, $this->adult + $this->child + $this->infant);
                $this->fares["return_fares"][$frkey]["amount"] = $this->fares["return_fares"][$frkey]["amount_no_tax"] + $this->fares["return_fares"][$frkey]["taxes"];
            }
        }

        $this->initLoad = true;
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.air-arabia.booking');
    }

    public function selectDeparture($key)
    {
        $departure = $this->fares["departure_fares"][$key];
        $this->selected["departure"]["lfid"] = $departure["lfid"];
        $this->selected["departure"]["fare_type_id"] = $departure["type_id"];
        $this->selected["departure"]["bundle_id"] = $departure["sol_id"];
        $this->selected["departure"]["amount"] = $departure["amount"];
        $this->selected["departure"]["amount_no_tax"] = $departure["amount_no_tax"];
        $this->selected["departure"]["flight_number"] = $this->departure_flight_number;
        $this->selected["departure"]["departure_date"] = $this->departure_from_date;
        $this->selected["departure"]["arrival_date"] = $this->departure_to_date;
        $this->selected["departure"]["departure_form"] = $this->departure_from;
        $this->selected["departure"]["departure_to"] = $this->departure_to;
        if ($departure["sol_id"] > 0) $this->params["bundled_services"]["departure"] = $departure["sol_id"];
        $this->calculateFareAmount();
        $this->fares_selected = $this->params["is_return"] ? false : true;
        if ($this->fares_selected) {
            $extras = (new AirArabia())->getExtras($this->params);
            if (isset($extras["Errors"])) {
                $this->error = $extras["Errors"];
                return;
            }
            $this->payload2 = $extras["payload"];
            $this->transaction_identifier = $extras["transaction_identifier"];
            unset($extras["payload"]);
            $this->ancillaries = $extras;
        }
    }

    public function selectReturn($key)
    {
        $return = $this->fares["return_fares"][$key];
        $this->selected["return"]["lfid"] = $return["lfid"];
        $this->selected["return"]["fare_type_id"] = $return["type_id"];
        $this->selected["return"]["bundle_id"] = $return["sol_id"];
        $this->selected["return"]["amount"] = $return["amount"];
        $this->selected["return"]["amount_no_tax"] = $return["amount_no_tax"];
        $this->selected["return"]["flight_number"] = $this->return_flight_number;
        $this->selected["return"]["departure_date"] = $this->return_from_date;
        $this->selected["return"]["arrival_date"] = $this->return_to_date;
        $this->selected["return"]["departure_form"] = $this->return_from;
        $this->selected["return"]["departure_to"] = $this->return_to;
        if ($return["sol_id"] > 0) $this->params["bundled_services"]["return"] = $return["sol_id"];
        $this->calculateFareAmount();
        $this->fares_selected = true;

        $this->params["payload"] = $this->payload;
        $extras = (new AirArabia())->getExtras($this->params);
        if (isset($extras["Errors"])) {
            $this->error = $extras["Errors"];
            return;
        }
        $this->payload2 = $extras["payload"];
        $this->transaction_identifier = $extras["transaction_identifier"];
        unset($extras["payload"]);
        $this->ancillaries = $extras;
    }

    public function willGoToSeats()
    {
        if ((isset($this->selected['departure']['bundle_id']) && $this->selected['departure']['bundle_id'] > 0) || (isset($this->selected['return']['bundle_id']) && $this->selected['return']['bundle_id'] > 0)) {
            return true;
        }
        return false;
    }

    function calculateFareAmount()
    {
        $this->total_no_tax = 0;
        $this->total = 0;
        if (count($this->selected["departure"])) {
            $this->total += $this->selected["departure"]["amount"];
            $this->total_no_tax += $this->selected["departure"]["amount_no_tax"];
        }
        if (count($this->selected["return"])) {
            $this->total += $this->selected["return"]["amount"];
            $this->total_no_tax += $this->selected["return"]["amount_no_tax"];
        }
    }
}
