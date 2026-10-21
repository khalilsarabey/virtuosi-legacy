<?php

namespace App\Http\Livewire\Frontend\LowCost\FlyDubai;

use App\Services\Airlines\FlyDubai\FlyDubaiAirline;
use Livewire\Component;

class Booking extends Component
{
    public $error = null;
    public $data;
    public $type;
    public $countries = [];
    public $airline = null;
    public $departure_lfid = null;
    public $departure_from = null;
    public $departure_to = null;
    public $departure_from_date = null;
    public $departure_to_date = null;
    public $departure_flight_number = null;
    public $departure_stops = [];
    public $return_lfid = null;
    public $return_from = null;
    public $return_to = null;
    public $return_from_date = null;
    public $return_to_date = null;
    public $return_flight_number = null;
    public $return_stops = [];
    public $adult = 1;
    public $child = 0;
    public $infant = 0;
    public $fares = [];
    public $selected_fare = null;
    public $payload = null;
    public $payload2 = null;
    public $total_no_tax = 0;
    public $total = 0;
    public $ancillaries = [];
    public $ancillaries_payload = null;
    public $access_token = null;
    public $session_timestamp = null;


    public function mount()
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
        $this->departure_lfid = $departure_info["lfid"];
        $this->departure_from = $departure_info["origin"];
        $this->departure_to = $departure_info["destination"];
        $this->departure_from_date = $departure_info["departure_time"];
        $this->departure_to_date = $departure_info["arrival_time"];
        $this->departure_flight_number = $departure_info["flight_number"];
        $this->departure_stops = $departure_info["stop_details"];
        $this->access_token = $departure_info["access_token"] ?? null;
        $this->session_timestamp = $departure_info["session_timestamp"] ?? null;

        $params["is_return"] = false;
        if ($return_info) {
            $this->type = 'roundtrip';
            $this->return_lfid = $return_info["lfid"];
            $this->return_from = $return_info["origin"];
            $this->return_to = $return_info["destination"];
            $this->return_from_date = $return_info["departure_time"];
            $this->return_to_date = $return_info["arrival_time"];
            $this->return_flight_number = $return_info["flight_number"];
            $this->return_stops = $return_info["stop_details"];
            $params["is_return"] = true;
            $params["return_lfid"] = $this->return_lfid;
            $params["return_from_date"] = $this->return_from_date;
            $params["return_to_date"] = $this->return_to_date;
            $params["return_flight_number"] = $this->return_flight_number;
            $params["return_from"] = $this->return_from;
            $params["return_to"] = $this->return_to;
        }

        //$this->fares["departure_fares"] = FlyDubaiSupport::getFareTypes($this->payload, $this->departure_flight_number, $this->departure_from_date);
        //$this->results = (new $airlines[$this->airline]['class'])->confirmationStep($params);
        $params["payload"] = $this->payload;
        $params["departure_lfid"] = $this->departure_lfid;
        $params["departure_flight_number"] = $this->departure_flight_number;
        $params["departure_from_date"] = $this->departure_from_date;
        $params["departure_to_date"] = $this->departure_to_date;
        $params["departure_from"] = $this->departure_from;
        $params["departure_to"] = $this->departure_to;
        $params["adult_count"] = (int)$this->adult;
        $params["child_count"] = (int)$this->child;
        $params["infant_count"] = (int)$this->infant;
        $params["sol_id"] = null;
        $params["departure_selected"] = false;
        $fare_results = (new FlyDubaiAirline())->getFlightFareTypes($params);
        $this->fares = $fare_results["fares"];
        //$this->payload2 = $fare_results["payload"];
        foreach ($this->fares as $fkey => $fval) {
            $this->fares[$fkey]["amount_no_tax"] = calculateMarkupPrice($this->fares[$fkey]["amount_no_tax"], $this->airline, 1, $this->adult + $this->child + $this->infant);
            $this->fares[$fkey]["amount"] = $this->fares[$fkey]["amount_no_tax"] + $this->fares[$fkey]["taxes"];
        }
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.fly-dubai.booking');
    }

    public function selectFare($key)
    {
        //TODO: add loading indicator
        $this->selected_fare = $this->fares[$key];

        $params["payload"] = $this->payload;
        $params["selected_departure"]["lfid"] = $this->selected_fare["departure_lfid"];
        $params["selected_departure"]["fare_type_id"] = $this->selected_fare["departure_type_id"];
        $params["selected_departure"]["soln_id"] = $this->selected_fare["departure_sol_id"];
        $params["selected_departure"]["departure_date"] = $this->departure_from_date;
        $params["selected_return"] = [];
        if ($this->selected_fare["return_lfid"]) {
            $params["selected_return"]["lfid"] = $this->selected_fare["return_lfid"];
            $params["selected_return"]["fare_type_id"] = $this->selected_fare["return_type_id"];
            $params["selected_return"]["soln_id"] = $this->selected_fare["return_sol_id"];
            $params["selected_return"]["departure_date"] = $this->return_from_date;
        }
        $params["access_token"] = $this->access_token;
        $params["session_timestamp"] = $this->session_timestamp;
        //$cart_response = (new FlyDubaiAirline())->addToCart($params["payload"], $params["selected_departure"], $params["selected_return"]);
        $cart_response = (new FlyDubaiAirline())->addToCart($params);
        if (isset($cart_response["error"])) {
            $this->error = $cart_response["error"];
        } else {
            $this->payload2 = $cart_response;
            $params2["departure_type_name"] = $this->selected_fare["name"];
            $params2["departure_codes"] = $this->selected_fare["services"]["departure_codes"];
            $params2["return_codes"] = $this->selected_fare["services"]["return_codes"] ?? null;
            $params2["departure_stops"] = $this->departure_stops;
            $params2["return_stops"] = $this->return_stops;
            $params2["access_token"] = $this->access_token;
            $params2["session_timestamp"] = $this->session_timestamp;
            $ancillaries_response = (new FlyDubaiAirline())->getAncillaries($cart_response, $params2);
            if (isset($ancillaries_response["error"])) {
                $this->error = $ancillaries_response["error"];
            } else {
                $this->ancillaries = $ancillaries_response["results"];
                $this->ancillaries_payload = $ancillaries_response["payload"];
            }
        }
    }//selectFare
}
