<?php

namespace App\Http\Livewire\Frontend\LowCost\AirArabia;

use Livewire\Component;

class Seats extends Component
{
    public $error = null;
    public $type = null;
    public $airline = null;
    public $results = null;
    public $passengers = null;
    public $ancillaries_payload = null;
    public $payload = null;
    public $payload2 = null;
    public $selected_departure = null;
    public $selected_return = null;
    public $departure_stops = [];
    public $return_stops = [];
    public $selected_passenger = null;
    public $selected_segment = null;
    public $display_seats = [];
    public $selected_seats = [];
    public $canProceed = false;
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
        $this->passengers = request()->passengers;
        $this->ancillaries_payload = json_decode(request()->ancillariespayload, true);
        $this->payload = json_decode(request()->payload, true);
        $this->payload2 = json_decode(request()->payload2, true);
        $this->selected_departure = json_decode(request()->departure, true);
        $this->selected_return = json_decode(request()->return, true);
        $this->departure_stops = json_decode(request()->departurestops, true);
        $this->return_stops = json_decode(request()->returnstops, true);
        $this->type = request()->type;
        $this->selected_passenger = $this->passengers["adult"][0]["ref_rph"];
        if (isset($this->ancillaries_payload["seats"]["segments"])) {
            //remove segments of Basic fare if exist
            $segments_to_remove = [];
            if (isset($this->selected_departure["bundle_id"]) && (int)$this->selected_departure["bundle_id"] == 0) $segments_to_remove = array_merge($segments_to_remove, $this->departure_stops);
            if (isset($this->selected_return["bundle_id"]) && (int)$this->selected_return["bundle_id"] == 0) $segments_to_remove = array_merge($segments_to_remove, $this->return_stops);
            if (count($segments_to_remove)) {
                foreach ($this->ancillaries_payload["seats"]["segments"] as $skey => $sval) {
                    foreach ($segments_to_remove as $rmseg) {
                        if ($rmseg["origin"] == $sval["origin"] && $rmseg["destination"] == $sval["destination"]) {
                            unset($this->ancillaries_payload["seats"]["segments"][$skey]);
                            break;
                        }
                    }
                }
            }

            $this->ancillaries_payload["seats"]["segments"] = array_values($this->ancillaries_payload["seats"]["segments"]);
            $this->segmentSelected($this->ancillaries_payload["seats"]["segments"][0]["flight_number"]);
            //Generating selected seats structure
            foreach ($this->ancillaries_payload["seats"]["segments"] as $sseg) {
                foreach ($this->passengers as $pkey => $ptype) {
                    if ($pkey == "infant") {
                        continue;
                    }
                    foreach ($ptype as $pass) {
                        $this->selected_seats[$sseg["flight_number"]][$pass["ref_rph"]]["row"] = null;
                        $this->selected_seats[$sseg["flight_number"]][$pass["ref_rph"]]["seat"] = null;
                    }
                }
            }
        } //if isset
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.air-arabia.seats');
    }

    public function passengerSelected($ref)
    {
        $this->selected_passenger = $ref;
    }

    public function segmentSelected($seg)
    {
        $this->selected_segment = $seg;
        if (isset($this->ancillaries_payload["seats"]["segments"])) {
            foreach ($this->ancillaries_payload["seats"]["segments"] as $sseg) {
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
                if ($pass["ref_rph"] == $this->selected_passenger) {
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
                    $this->selected_seats[$seat["flight_number"]][$pass["ref_rph"]]["row"] = $seat["row_number"];
                    $this->selected_seats[$seat["flight_number"]][$pass["ref_rph"]]["seat"] = $seat["seat_number"];
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
        if (strtolower($seat["availability"]) != "vac") {
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
                    if (count($ppass["seats"]) != count($this->ancillaries_payload["seats"]["segments"])) return;
                }
            }
        }
        $this->canProceed = true;
        return;
    }
}
