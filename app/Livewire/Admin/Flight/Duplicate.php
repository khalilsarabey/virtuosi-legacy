<?php

namespace App\Livewire\Admin\Flight;

use App\Models\Airline;
use App\Models\Contract;
use App\Models\Flight;
use App\Models\Location;
use App\Models\Price;
use App\Models\User;
use Livewire\Component;

class Duplicate extends Component
{
    public $num;
    public $generated = [];
    public $flight;
    public $listsForFields = [];

    public function mount($id)
    {
        $this->flight = Flight::with('prices')->findOrFail($id);
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.flight.duplicate')->layout('layouts.admin');
    }

    public function updatingNum($v)
    {
        $this->generated = [];
        for ($i = 1; $i <= (int) $v; $i++) {
            // Store plain arrays: Livewire 3+ cannot round-trip unsaved models nested in arrays.
            $this->generated[] = $this->flight->replicate()->toArray();
        }
    }

    public function save()
    {
        foreach($this->generated as $duplication):
            $seats = 0;
            $business_seats = 0;
            $prices = [];
            foreach($duplication['prices'] as $i => $price):
                // Same seat count as Flight Create: sum of seats per price row
                $seats += (int) ($price['seats'] ?? 0);
                if (($price['type'] ?? null) === 'business') {
                    $business_seats += (int) ($price['seats'] ?? 0);
                }
                unset($duplication['prices'][$i]['flight_id']);
                $priceObject = new Price();
                $priceObject->fill($price);
                $prices[] = $priceObject;
            endforeach;
            $flight = new Flight;
            $flight->airline_id = $duplication['airline_id'];
            $flight->dep_date = $duplication['dep_date'];
            $flight->dep_time = $duplication['dep_time'];
            $flight->dep_arr_time = $duplication['dep_arr_time'];
            $flight->dep_flight_number = $duplication['dep_flight_number'];
            $flight->ret_dep_date = $duplication['ret_dep_date'];
            $flight->ret_dep_time = $duplication['ret_dep_time'];
            $flight->ret_arr_time = $duplication['ret_arr_time'];
            $flight->ret_flight_number = $duplication['ret_flight_number'];
            $flight->baggage_allowance = $duplication['baggage_allowance'];
            $flight->active = $duplication['active'];
            $flight->type = $duplication['type'];
            $flight->oneway_supplements = $duplication['oneway_supplements'];
            $flight->multi_supplements = $duplication['multi_supplements'];
            $flight->deleted_at = $duplication['deleted_at'];
            $flight->dep_from_code = $duplication['dep_from_code'];
            $flight->dep_to_code = $duplication['dep_to_code'];
            $flight->ret_from_code = $duplication['ret_from_code'];
            $flight->ret_to_code = $duplication['ret_to_code'];
            $flight->contract_id = $duplication['contract_id'];
            $flight->promo = $duplication['promo'] ?? 0;
            $flight->seats = $seats;
            $flight->business_seats = $business_seats;
            $flight->save();
            $flight->prices()->saveMany($prices);
        endforeach;
        return redirect()->route('admin.flights.index');
    }

    protected function initListsForFields(): void
    {
        $locations = Location::where('active', 1)->pluck('city', 'airport_code')->toArray();
        $this->listsForFields['dep_from'] = $locations;
        $this->listsForFields['dep_to']   = $locations;
        $this->listsForFields['ret_from'] = $locations;
        $this->listsForFields['ret_to']   = $locations;
        $this->listsForFields['airlines'] = Airline::pluck('name', 'id')->toArray();
        $this->listsForFields['users']    = User::orderBy('name')->pluck('name', 'email')->toArray();
        $this->listsForFields['contract'] = Contract::orderBy('name')->pluck('name', 'id')->toArray();
    }
}
