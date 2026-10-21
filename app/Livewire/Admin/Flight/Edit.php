<?php

namespace App\Livewire\Admin\Flight;

use App\Models\Agency;
use App\Models\Airline;
use App\Models\Category;
use App\Models\Contract;
use App\Models\Flight;
use App\Models\Location;
use App\Models\Price;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public Flight $flight;

    public array $category = [];

    public array $pricing = [];
    public $booked_seats;

    public array $business_pricing = [];

    public array $permissions = [];

    public array $listsForFields = [];

    public function mount($id)
    {
        $this->flight   = Flight::find($id);
        $this->flight->dep_time = Carbon::parse($this->flight->dep_time)->format('H:i');
        $this->flight->dep_arr_time = Carbon::parse($this->flight->dep_arr_time)->format('H:i');
        $this->flight->ret_dep_time     = $this->flight->ret_dep_time ?  Carbon::parse($this->flight->ret_dep_time)->format('H:i') : null;
        $this->flight->ret_arr_time = $this->flight->ret_arr_time ? Carbon::parse($this->flight->ret_arr_time)->format('H:i') : null;
        $this->category = $this->flight->category()->pluck('id')->toArray();
        $this->booked_seats = \App\Models\Ticket::query()->where('flight_id', $id)->get();
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.flight.edit')->layout('layouts.admin');
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
        $this->savePrices();
        $this->flight->save();
        $this->flight->category()->sync($this->category);
        Cache::forget('search');
        return redirect()->route('admin.flights.index');
    }

    public function getBookedSeats($class)
    {
        return $this->booked_seats->where('class', $class)->where('title', '<>', 'Infant.')->count();
    }

    public function savePrices()
    {
        foreach($this->pricing as $n => $price):
            if(!empty($price['id'])) {
                Price::query()
                    ->where([
                        'id' => $price['id']
                    ])->update([
                        "adult" => $price['adult'],
                        "child" => $price['child'],
                        "infant" => $price['infant'],
                        "package_adult" => $price['package_adult'] ?? $price['adult'],
                        "package_child" => $price['package_child'] ?? $price['child'],
                        "package_infant" => $price['package_infant'] ?? $price['infant'],
                        "seats" => $price['seats']
                    ]);
            } else {
                Price::create([
                    "flight_id" => $this->flight->id,
                    "adult" => $price['adult'],
                    "child" => $price['child'],
                    "infant" => $price['infant'],
                    "package_adult" => $price['package_adult'] ?? $price['adult'],
                    "package_child" => $price['package_child'] ?? $price['child'],
                    "package_infant" => $price['package_infant'] ?? $price['infant'],
                    "seats" => $price['seats'],
                    "class" => $price['class'],
                    "type" => 'economy',
                    'position' => $n + 1
                ]);
            }
        endforeach;
            if(config('features.cabins')) {
                foreach($this->business_pricing as $k => $price):
                    if(!empty($price['id'])) {
                        Price::query()
                            ->where(['id' => $price['id']
                            ])->update([
                                "adult" => $price['adult'],
                                "child" => $price['child'],
                                "infant" => $price['infant'],
                                "package_adult" => $price['package_adult'] ?? $price['adult'],
                                "package_child" => $price['package_child'] ?? $price['child'],
                                "package_infant" => $price['package_infant'] ?? $price['infant'],
                                "seats" => $price['seats'],
                            ]);
                    } else {
                        Price::create([
                            "flight_id" => $this->flight->id,
                            "adult" => $price['adult'],
                            "child" => $price['child'],
                            "infant" => $price['infant'],
                            "package_adult" => $price['package_adult'] ?? $price['adult'],
                            "package_child" => $price['package_child'] ?? $price['child'],
                            "package_infant" => $price['package_infant'] ?? $price['infant'],
                            "seats" => $price['seats'],
                            "class" => $price['class'],
                            "type" => 'business',
                            'position' => $k + 1
                        ]);
                    }
                endforeach;
            }
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

    protected $messages = [
        'flight.expiry_date.date_format' => 'Invalid expiry date format.',
        'flight.dep_time.required' => 'Departure Time is required.',
    ];

    protected function rules(): array
    {
        return [
            'flight.expiry_date' => [
                'nullable',
                'date_format:' . config('project.date_format'),
            ],
            'flight.package_note' => [
                'nullable'
            ],
            'flight.agencies_permissions' => [
                'nullable'
            ],
            'flight.stop_booking_before' => [
                'required',
                'integer'
            ],
            'flight.whitelabel_permissions' => [
                'nullable'
            ],
            'category' => [
                'array',
                'nullable'
            ],
            'flight.airline_id' => [
                'nullable',
                'exists:airlines,id',
            ],
            'category.*.id' => [
                'integer',
                'exists:categories,id',
            ],
            'flight.dep_from_code' => [
                'required',
            ],
            'flight.promo' => [
                'nullable',
            ],
            'flight.dep_to_code' => [
                'required',
            ],
            'flight.ret_flight_number' => [
                'nullable',
            ],
            'flight.dep_date' => [
                'nullable',
                'date_format:' . config('project.date_format'),
            ],
            'flight.dep_time' => [
                'required'
            ],
            'flight.ret_from_code' => [
                'nullable',
            ],
            'flight.ret_to_code' => [
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
            'flight.remaining_seats_display' => [
                'nullable'
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
            'flight.active' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'flight.onhold_bookings' => [
                'integer',
                'min:0',
                'max:1',
                'nullable',
            ],
            'flight.dep_flight_number' => [
                'required',
                'string'
            ],
            'flight.dep_arr_time' => [
                'required',
                'date_format:' . config('project.time_format'),
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
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $locations                                  = Location::where('active', 1)->pluck('airport_code', 'airport_code')->toArray();
        $pricing                                    = Price::query()->where(['flight_id' => $this->flight->id, 'type' => 'economy'])->orderBy('position')->get()->toArray();

        if($pricing) {
            $this->pricing = $pricing;
        }

        $default_classes = [
            'Z' => ['type' => 'business', 'class' => 'Z', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => '0', 'package_child' => '0', 'package_infant' => '0', 'position' => 1],
            'K' => ['type' => 'business', 'class' => 'K', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => '0', 'package_child' => '0', 'package_infant' => '0', 'position' => 2],
            'J' => ['type' => 'business', 'class' => 'J', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'package_adult' => '0', 'package_child' => '0', 'package_infant' => '0', 'position' => 3],
        ];

        // Get existing pricing from DB
        $business_pricing = Price::query()
            ->where(['flight_id' => $this->flight->id, 'type' => 'business'])
            ->orderBy('position')
            ->get()
            ->keyBy('class') // index by class for easy merging
            ->toArray();

        // Merge DB values over defaults
        $merged = array_values(array_replace_recursive($default_classes, $business_pricing));

        // Assign to component
        $this->business_pricing = $merged;

        $this->listsForFields['category']           = Category::pluck('name', 'id')->toArray();
        $this->listsForFields['dep_from']           = $locations;
        $this->listsForFields['dep_to']             = $locations;
        $this->listsForFields['ret_from']           = $locations;
        $this->listsForFields['ret_to']             = $locations;
        $this->listsForFields['airlines']           = Airline::pluck('name', 'id')->toArray();
        $this->listsForFields['users']              = User::orderBy('name')->pluck('name', 'email')->toArray();
        $this->listsForFields['contract']           = Contract::orderBy('name')->pluck('name', 'id')->toArray();
        $this->listsForFields['type']               = $this->flight::TYPE_SELECT;
        $this->listsForFields['onhold_bookings']    = $this->flight::ONHOLD_BOOKINGS;
    }
}
