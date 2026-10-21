<?php

namespace App\Livewire\Frontend\LowCost\FlyDubai;

use App\Services\Airlines\FlyDubai\FlyDubaiAirline;
use Livewire\Component;

class Seats extends Component
{
    public $error = null;
    public $airline = null;
    public $results = null;
    public $passengers = null;
    public $ancillaries_payload = null;
    public $seats_payload = null;
    public $payload = null;
    public $payload2 = null;
    public $type = null;
    public $selected_passenger = null;
    public $selected_fare = null;
    public $departure_stops = [];
    public $return_stops = [];
    public $selected_segment = null;
    public $display_seats = [];
    public $selected_seats = [];
    public $canProceed = false;
    public $access_token = null;
    public $session_timestamp = null;

    public function mount()
    {
        $this->access_token = request()->accesstoken;
        $this->session_timestamp = request()->sessiontimestamp;
        $this->airline = request()->airline;
        $this->passengers = request()->passengers;
        $this->ancillaries_payload = \json_decode(request()->ancillariespayload, true);
        $this->payload = json_decode(request()->payload, true);
        $this->payload2 = json_decode(request()->payload2, true);
        $this->type = request()->type;
        $this->selected_fare = \json_decode(request()->selectedfare, true);
        $this->departure_stops = \json_decode(request()->departurestops, true);
        $this->return_stops = \json_decode(request()->returnstops, true);

        $cart_response = $this->payload2;
        $params2["departure_type_name"] = $this->selected_fare["name"];
        $params2["departure_stops"] = $this->departure_stops;
        $params2["return_stops"] = $this->return_stops;
        $params2["access_token"] = $this->access_token;
        $params2["session_timestamp"] = $this->session_timestamp;
        $seats_response = (new FlyDubaiAirline())->getSeatMap($cart_response, $params2);
        if (isset($seats_response["error"])) {
            $this->error = $seats_response["error"];
        } else {
            $this->seats_payload = $seats_response["results"];
            $this->selected_passenger = $this->passengers["adult"][0]["ref_index"];
            $this->segmentSelected($this->seats_payload[0]["flight_number"]);
            //Generating selected seats structure
            foreach ($this->seats_payload as $sseg) {
                foreach ($this->passengers as $pkey => $ptype) {
                    if ($pkey == "infant") {
                        continue;
                    }
                    foreach ($ptype as $pass) {
                        $this->selected_seats[$sseg["flight_number"]][$pass["ref_index"]]["row"] = null;
                        $this->selected_seats[$sseg["flight_number"]][$pass["ref_index"]]["seat"] = null;
                    }
                }
            }
        }
    }//mount

    public function render()
    {
        return view('livewire.frontend.low-cost.fly-dubai.seats');
    }

    public function passengerSelected($ref)
    {
        $this->selected_passenger = $ref;
    }

    public function segmentSelected($seg)
    {
        $this->selected_segment = $seg;
        if (count($this->seats_payload) > 0) {
            foreach ($this->seats_payload as $sseg) {
                if ($sseg["flight_number"] == $seg) {
                    $this->display_seats = $sseg["seats"];
                }
            }
        }
    } //segmentSelected

    public function seatSelected($seat)
    {
        if (!$this->isSeatAvailable($seat)) {
            return;
        }

        $pass_seats = [];
        foreach ($this->passengers as $pkey => $ps1) {
            foreach ($ps1 as $pkey2 => $pass) {
                if ($pass["ref_index"] == $this->selected_passenger) {
                    if (isset($pass["seats"])) {
                        $pass_seats = $pass["seats"];
                        foreach ($pass_seats as $key => $pseat) {
                            if ($pseat["flight_number"] == $seat["flight_number"]) {
                                unset($pass_seats[$key]);
                            }
                        }
                    }
                    $pass_seats[] = $seat;
                    $this->passengers[$pkey][$pkey2]["seats"] = $pass_seats;
                    $this->selected_seats[$seat["flight_number"]][$pass["ref_index"]]["row"] = $seat["row_number"];
                    $this->selected_seats[$seat["flight_number"]][$pass["ref_index"]]["seat"] = $seat["seat_number"];
                }
            }
        }
        
        $this->validateSubmit();
    } //seatSelected

    public function isSeatSelectedOtherCurrent($row, $seat)
    {
        foreach ($this->selected_seats[$this->selected_segment] as $ref => $pass) {
            if ($pass["row"] == $row && $pass["seat"] == $seat) {
                return $ref == $this->selected_passenger ? 'selected-c' : 'selected-o';
            }
        }
        return '';
    }

    public function isSeatAvailable($seat)
    {
        if (!$seat["is_available"]) {
            return false;
        }
        foreach ($this->selected_seats[$this->selected_segment] as $pass) {
            if ($pass["row"] == $seat["row_number"] && $pass["seat"] == $seat["seat_number"]) {
                return false;
            }
        }
        return true;
    }

    public function getPassSeats($ref_rph)
    {
        $str = "";
        foreach ($this->selected_seats as $seg) {
            foreach ($seg as $ref => $pass) {
                if ($ref == $ref_rph) {
                    if ($pass["seat"] && $pass["row"]) {
                        $str .= $pass["row"] . $pass["seat"] . ", ";
                        continue 2;
                    }
                }
            }
        }
        if (strlen($str)) {
            $str = "(" . rtrim($str, ", ") . ")";
        }

        return $str;
    } //getPassSeats

    public function validateSubmit()
    {
        foreach ($this->passengers as $ptype => $tpass) {
            if ($ptype != "infant") {
                foreach ($tpass as $ppass) {
                    if (!isset($ppass["seats"])) return;
                    if (count($ppass["seats"]) != count($this->seats_payload)) return;
                }
            }
        }
        $this->canProceed = true;
        return;
    }
}
