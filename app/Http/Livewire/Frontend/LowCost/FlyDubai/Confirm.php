<?php

namespace App\Http\Livewire\Frontend\LowCost\FlyDubai;

use App\Mail\NewAirlineBookingEmail;
use App\Models\AirlineBooking;
use App\Models\Integration;
use App\Services\Airlines\FlyDubai\FlyDubaiAirline;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Confirm extends Component
{
    public $error = null;
    public $airline = null;
    public $results = null;
    public $total_amount = 0;
    public $taxes_amount = 0;
    public $amount_no_tax = 0;
    public $amount = 0;
    public $hold = false;
    public string $balance_error;

    public function mount()
    {
        $this->hold = isset(request()->hold) && request()->hold == '1';
        $params = json_decode(request()->payload, true);
        $params["passengers"] = json_decode(request()->passengers, true);
        $params["ancillaries_payload"] = request()->ancillariespayload;
        $params["access_token"] = request()->accesstoken;
        $params["session_timestamp"] = request()->sessiontimestamp;
        $just_payment = false;
        $num_of_passengers = 0;
        foreach($params['passengers'] as $passenger) {
            $num_of_passengers += count($passenger);
        }
        $amount_no_tax = calculateMarkupPrice($params['base_f'], 'FlyDubai', 1, $num_of_passengers);
        $amount = $params['base_f'];
        $taxes_amount = $params["reservation_balance"] - $params["base_f"];
        $total_amount = $amount_no_tax + $taxes_amount;

        if(auth()->user()->type == 'agency' && $total_amount > auth()->user()->balance) {
            $this->balance_error = "Insufficient Balance.";
        } else {
            $this->airline = request()->airline;
            if ($this->airline) {
                $resl = (new FlyDubaiAirline())->processBookingStep($params, !$this->hold);
                if (isset($resl["error"])) {
                    $this->error = $resl["error"];
                } else {
                    $this->results = $resl;
                    $this->amount_no_tax    = $amount_no_tax;
                    $this->taxes_amount     = $taxes_amount;
                    $this->total_amount     = $total_amount;
                    $this->amount           = $amount;
                    //dd($params, $resl, ['amount_no_tax' => $this->amount_no_tax, 'taxes_amount' => $this->taxes_amount, 'total_amount' => $this->total_amount]);

                    $booking = new AirlineBooking;
                    $booking->user_id = auth()->user()->id;
                    if(request()->remarks)
                    {
                        $booking->remarks = request()->remarks;
                    }
                    $booking->integration_id = 2;
                    $booking->airline = 'FlyDubai';
                    $booking->pnr = $this->results['booking_num'];
                    $booking->dep_from = $this->results['departure']['from'];
                    $booking->dep_from_date = $this->results['departure']['departure_time'];
                    $booking->dep_to =  $this->results['departure']['to'];
                    $booking->dep_to_date = $this->results['departure']['arrival_time'];
                    if(isset($this->results['return']) && isset($this->results['return']['from'])) {
                        $booking->ret_from = $this->results['return']['from'];
                        $booking->ret_from_date = $this->results['return']['departure_time'];
                        $booking->ret_to =  $this->results['return']['to'];
                        $booking->ret_to_date = $this->results['return']['arrival_time'];
                    }
                    $booking->amount_with_markup = $this->amount_no_tax;
                    $booking->taxes = $this->taxes_amount;
                    $booking->total = $this->total_amount;
                    $booking->amount = $this->amount;
                    $booking->markup = auth()->user()->fly_dubai_markup;
                    $booking->markup_type = auth()->user()->fly_dubai_markup_type;
                    $booking->payload = json_encode($this->results);
                    $booking->request = json_encode($params);
                    $booking->on_hold_expiry_date = isset($this->results['on_hold_expiry_date']) && !empty($this->results['on_hold_expiry_date']) ? Carbon::parse($this->results['on_hold_expiry_date'])->format('Y-m-d H:i:s') : null;
                    $booking->status = $this->hold ? 'pending' : 'confirmed';
                    try {
                        $booking->save();
                        if(!$this->hold) {
                            Integration::find(2)->decrement('balance', $this->results['amount']);
                            if(auth()->user()->type == 'agency') {
                                $user = auth()->user();
                                $user->balance -= $this->total_amount;
                                $user->save();
                            }
                        }
                        Mail::send(new NewAirlineBookingEmail('AirArabia', $booking->id, auth()->user()->email ));
                    } catch(\Exception $e) {
                        $this->balance_error = $e->getMessage();
                    }
                }
            }
        }
    }


    public function render()
    {
        return view('livewire.frontend.low-cost.fly-dubai.confirm');
    }
}
