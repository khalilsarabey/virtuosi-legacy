<?php

namespace App\Http\Livewire\Frontend\LowCost\FlyDubai;

use App\Services\Airlines\FlyDubai\FlyDubaiAirline;
use Illuminate\Support\Facades\Storage;
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

    public function mount()
    {
        $this->access_token = request()->accesstoken;
        $this->session_timestamp = request()->sessiontimestamp;
        $this->airline = request()->airline;
        $this->passengers = is_array(request()->passengers) ? request()->passengers : json_decode(request()->passengers, true);
        $this->ancillaries_payload = request()->ancillariespayload;
        $params["payload"] = json_decode(request()->payload, true);
        $params["payload2"] = json_decode(request()->payload2, true);
        //$params["selected_departure"] = json_decode(request()->departure, true);
        //$params["selected_return"] = json_decode(request()->return, true);
        $params["passengers"] = $this->passengers;
        $params["type"] = request()->type;
        $params["ancillaries_payload"] = request()->ancillariespayload;
        $params["access_token"] = $this->access_token;
        $params["session_timestamp"] = $this->session_timestamp;
        if ($this->airline) {
            $resl = (new FlyDubaiAirline())->confirmationStep($params);
            if (isset($resl["error"])) {
                $this->error = $resl["error"];
            } else {
                $total_anc_amount = 0;
                $total_seats_amount = 0;
                foreach($this->passengers as $pass_key){
                    foreach($pass_key as $pass){
                        if(isset($pass["baggage"])){
                            foreach($pass["baggage"] as $bag){
                                if($bag){
                                    $bag = \is_array($bag) ? $bag : \json_decode($bag, true);
                                    $total_anc_amount += $bag["amount"];
                                }
                            }
                        }
                        if(isset($pass["meals"])){
                            foreach($pass["meals"] as $meal){
                                if($meal){
                                    $meal = \is_array($meal) ? $meal : \json_decode($meal, true);
                                    $total_anc_amount += $meal["amount"];
                                }
                            }
                        }
                        if(isset($pass["seats"])){
                            foreach($pass["seats"] as $seat){
                                if($seat){
                                    $seat = \is_array($seat) ? $seat : \json_decode($seat, true);
                                    $total_seats_amount += $seat["charge"];
                                }
                            }
                        }
                    }
                }
                if(\ceil($resl["reservation_balance"]) != \ceil($resl["total_f"] + $total_anc_amount) && \ceil($resl["reservation_balance"]) != \ceil($resl["total_f"] + $total_anc_amount + $total_seats_amount)){
                    Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$this->session_timestamp.DIRECTORY_SEPARATOR."pricing_error.json", json_encode([
                        'results' => $resl,
                        'total_anc_amount' => $total_anc_amount,
                        'total_seats_amount' => $total_seats_amount
                    ]));
                }
                $this->results = $resl;
                $this->amount_no_tax = calculateMarkupPrice($resl["base_f"], $this->airline, 1, count($this->results['travellers']));
                $this->taxes_amount = $resl["reservation_balance"] - $resl["base_f"];
                $this->total_amount =  $this->amount_no_tax + $this->taxes_amount;
            }
        }
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.fly-dubai.summary');
    }
}
