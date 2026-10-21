<?php

namespace App\Http\Livewire\Admin\Flight;

use App\Models\Agency;
use App\Models\Airline;
use App\Models\Category;
use App\Models\Contract;
use App\Models\Flight;
use App\Models\Location;
use App\Models\Price;
use App\Models\User;
use Cache;
use Livewire\Component;

class Create extends Component
{
    public Flight $flight;

    public array $pricing = [];
    public array $business_pricing = [];

    public array $category = [];

    public array $listsForFields = [];

    public function mount(Flight $flight)
    {
        $this->flight                           = $flight;
        $this->flight->active                   = '0';
        $this->flight->remaining_seats_display  = '0';
        $this->flight->type                     = 'oneway';
        $this->flight->oneway_supplements       = '0';
        $this->flight->stop_booking_before      = '0';
        $this->flight->multi_supplements        = '0';
        $this->flight->combinable_supplements   = '0';
        $this->flight->seats                    = '0';
        $this->pricing                          = [
            ['class' => 'L', 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => 0, 'package_child' => 0, 'package_infant' => 0],
            ['class' => 'M', 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => 0, 'package_child' => 0, 'package_infant' => 0],
            ['class' => 'Y', 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => 0, 'package_child' => 0, 'package_infant' => 0],
        ];
        $this->business_pricing                          = [
            ['class' => 'Z', 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => 0, 'package_child' => 0, 'package_infant' => 0],
            ['class' => 'K', 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => 0, 'package_child' => 0, 'package_infant' => 0],
            ['class' => 'J', 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => 0, 'package_child' => 0, 'package_infant' => 0],
        ];
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.flight.create')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->flight->seats = $this->calculateSeats()['seats'];
        $this->flight->business_seats = $this->calculateSeats()['business_seats'];
        if($this->flight->type == 'fixed') {
            $this->flight->ret_from_code = $this->flight['dep_to_code'];
            $this->flight->ret_to_code = $this->flight['dep_from_code'];
        }
        $this->flight->save();
        $this->savePrices();
        $this->flight->category()->sync($this->category);
        Cache::forget('search');
        return redirect()->route('admin.flights.index');
    }

    public function savePrices()
    {
        foreach($this->pricing as $k => $price):
            $price['flight_id'] = $this->flight->id;
            $price['position'] = $k + 1;
            $price['type'] = 'economy';
            Price::query()->create($price);
        endforeach;

        foreach($this->business_pricing as $l => $price):
            $price['flight_id'] = $this->flight->id;
            $price['position'] = $l + 1;
            $price['type'] = 'business';
            Price::query()->create($price);
        endforeach;
    }

    protected function calculateSeats()
    {
        $seats = 0;
        $business_seats = 0;
        foreach($this->pricing as $price):
            $seats += $price['seats'];
        endforeach;
        foreach($this->business_pricing as $price):
            $seats += $price['seats'];
            $business_seats += $price['seats'];
        endforeach;
        return ['seats' => $seats, 'business_seats' => $business_seats];
    }

    protected function rules(): array
    {
        return [
            'flight.expiry_date' => [
                'nullable',
                'date_format:' . config('project.date_format'),
            ],
            'flight.agencies_permissions' => [
                'nullable'
            ],
            'flight.package_note' => [
                'nullable'
            ],
            'flight.stop_booking_before' => [
                'required', 'integer'
            ],
            'flight.whitelabel_permissions' => [
                'nullable'
            ],
            'flight.onhold_bookings' => [
                'integer',
                'min:0',
                'max:1',
                'nullable',
            ],
            'category' => [
                'array',
            ],
            'category.*.id' => [
                'integer',
                'exists:categories,id',
            ],
            'flight.dep_flight_number' => [
                'required',
                'string'
            ],
            'flight.remaining_seats_display' => [
                'nullable'
            ],
            'flight.promo' => [
                'nullable'
            ],
            'flight.ret_flight_number' => [
                'nullable',
                'string'
            ],
            'flight.dep_from_code' => [
                'exists:locations,airport_code',
                'required',
            ],
            'flight.dep_to_code' => [
                'exists:locations,airport_code',
                'required',
            ],
            'flight.dep_date' => [
                'nullable',
                'date_format:' . config('project.date_format'),
            ],
            'flight.dep_time' => [
                'required',
                'date_format:' . config('project.time_format'),
            ],
            'flight.dep_arr_time' => [
                'required',
                'date_format:' . config('project.time_format'),
            ],
            'flight.ret_from_code' => [
                'exists:locations,airport_code',
                'nullable',
            ],
            'flight.ret_to_code' => [
                'exists:locations,airport_code',
                'nullable',
            ],
            'flight.ret_dep_date' => [
                'nullable',
                'date_format:' . config('project.date_format'),
            ],
            'flight.ret_dep_time' => [
                'nullable',
                'date_format:' . config('project.time_format'),
            ],
            'flight.ret_arr_time' => [
                'nullable',
                'date_format:' . config('project.time_format'),
            ],
            'flight.baggage_allowance' => [
                'string',
                'nullable',
            ],
            'flight.contract_id' => [
                'integer',
                'exists:contracts,id',
                'nullable',
            ],
            'flight.airline_id' => [
                'integer',
                'exists:airlines,id',
                'nullable',
            ],
            'flight.active' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'flight.type' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['type'])),
            ],
            'flight.combinable_supplements' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'flight.oneway_supplements' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'flight.multi_supplements' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'flight.seats' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['category'] = Category::pluck('name', 'id')->toArray();
        $locations = Location::where('active', 1)->pluck('airport_code', 'airport_code')->toArray();
        $this->listsForFields['dep_from']           = $locations;
        $this->listsForFields['dep_to']             = $locations;
        $this->listsForFields['ret_from']           = $locations;
        $this->listsForFields['ret_to']             = $locations;
        $this->listsForFields['airlines']           = Airline::pluck('name', 'id')->toArray();
        $this->listsForFields['users']              = User::orderBy('name')->pluck('name', 'email')->toArray();
        $this->listsForFields['whitelabels']        = Agency::orderBy('name')->pluck('name', 'id')->toArray();
        $this->listsForFields['contract']           = Contract::orderBy('name')->pluck('name', 'id')->toArray();
        $this->listsForFields['type']               = $this->flight::TYPE_SELECT;
        $this->listsForFields['onhold_bookings']    = $this->flight::ONHOLD_BOOKINGS;
    }
}
