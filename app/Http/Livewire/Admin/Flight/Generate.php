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
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Generate extends Component
{
    public $flight = [];
    public array $category = [];
    public $start;
    public $end;
    public $days;
    public $total;
    public $duration;
    public $pricing = [];
    public $business_pricing = [];
    public $listsForFields = [];

    public function mount()
    {
        $this->total = 0;
        $this->pricing = [
            ['class' => 'L', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'position' => 1],
            ['class' => 'M', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'position' => 2],
            ['class' => 'Y', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'position' => 3],
        ];
        $this->business_pricing = [
            ['class' => 'Z', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'position' => 1],
            ['class' => 'K', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'position' => 2],
            ['class' => 'J', 'price' => 0, 'adult' => 0, 'child' => 0, 'infant' => 0, 'seats' => 0, 'position' => 3],
        ];
        $this->flight['type'] = 'oneway';
        $this->flight['remaining_seats_display'] = 0;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.flight.generate')->layout('layouts.admin');
    }

    public function updatedDays()
    {
        if (!$this->start || !$this->end) return;
        $this->total = 0;
        $dateRange = $this->dateRange();
        foreach ($dateRange as $date) {
            if (in_array($date->format("D"), $this->days)) {
                $this->total += 1;
            }
        }
    }

    protected function dateRange()
    {
        $begin = new DateTime($this->start);
        $end = new DateTime($this->end);
        $end = $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        return new DatePeriod($begin, $interval, $end);
    }

    public function save()
    {
        $dateRange = $this->dateRange();
        $this->validate([
            'flight.type'                       => 'required',
            'flight.airline_id'                 => 'required',
            'flight.dep_time'                   => 'required',
            'flight.dep_arr_time'               => 'required',
            'flight.dep_flight_number'          => 'required',
            'flight.dep_from_code'              => 'required',
            'flight.dep_to_code'                => 'required',
            'flight.remaining_seats_display'    => 'required',
        ]);
        DB::beginTransaction();
        try {
            foreach ($dateRange as $date) {
                if (in_array($date->format("D"), $this->days)) {
                    $flight = new Flight;
                    $flight->type                       = $this->flight['type'];
                    $flight->airline_id                 = $this->flight['airline_id'];
                    $flight->dep_date                   = $date->format('d-m-Y');
                    $flight->dep_time                   = $this->flight['dep_time'];
                    $flight->dep_arr_time               = $this->flight['dep_arr_time'];
                    $flight->dep_flight_number           = $this->flight['dep_flight_number'];
                    $flight->dep_from_code              = $this->flight['dep_from_code'];
                    $flight->dep_to_code                = $this->flight['dep_to_code'];
                    $flight->baggage_allowance          = $this->flight['baggage_allowance'] ?? null;
                    $flight->oneway_supplements         = $this->flight['oneway_supplements'] ?? null;
                    $flight->multi_supplements          = $this->flight['multi_supplements'] ?? null;
                    $flight->expiry_date                = $this->flight['expiry_date'] ?? null;
                    $flight->agencies_permissions       = $this->flight['agencies_permissions'] ?? null;
                    $flight->remaining_seats_display    = (int) $this->flight['remaining_seats_display'];
                    $flight->seats                      = $this->calculateSeats()['seats'];
                    $flight->business_seats             = $this->calculateSeats()['business_seats'];
                    $flight->active                     = $this->flight['active'] ?? 0;

                    if (isset($this->flight['contract_id'])) $flight->contract_id = $this->flight['contract_id'];

                    if ($this->flight['type'] == 'fixed') {
                        $flight->ret_from_code            = $this->flight['dep_to_code'];
                        $flight->ret_to_code              = $this->flight['dep_from_code'];
                        $flight->ret_dep_date           = $date->modify('+' . $this->duration . ' day')->format('d-m-Y');
                        $flight->ret_dep_time           = $this->flight['ret_dep_time'];
                        $flight->ret_arr_time           = $this->flight['ret_arr_time'];
                        $flight->ret_flight_number       = $this->flight['ret_flight_number'];
                    }
                    $flight->save();
                    foreach ($this->pricing as $k => $price):
                        $model = new Price;
                        $model->flight_id   = $flight->id;
                        $model->class       = $price['class'];
                        $model->adult       = $price['adult'];
                        $model->infant      = $price['infant'];
                        $model->child       = $price['child'];
                        $model->package_adult = $price['package_adult'] ?? $price['adult'];
                        $model->package_infant = $price['package_infant'] ?? $price['infant'];
                        $model->package_child  = $price['package_child'] ?? $price['child'];
                        $model->seats       = $price['seats'];
                        $model->type        = 'economy';
                        $model->position    = $k + 1;
                        $model->save();
                    endforeach;
                    if(config('features.cabins')):
                        foreach ($this->business_pricing as $k => $price):
                            $model = new Price;
                            $model->flight_id   = $flight->id;
                            $model->class       = $price['class'];
                            $model->adult       = $price['adult'];
                            $model->infant      = $price['infant'];
                            $model->child       = $price['child'];
                            $model->seats       = $price['seats'];
                            $model->package_adult       = $price['package_adult'] ?? $price['adult'];
                            $model->package_infant      = $price['package_infant'] ?? $price['infant'];
                            $model->package_child       = $price['package_child'] ?? $price['child'];
                            $model->type        = 'business';
                            $model->position    = $k + 1;
                            $model->save();
                        endforeach;
                    endif;
                    $flight->category()->sync($this->category);
                }
            }
            DB::commit();
            return redirect()->route('admin.flights.index');
        } catch (\Exception $e) {
            DB::rollback();
            $this->addError('error', $e->getMessage());
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

    public function initListsForFields(): void
    {
        $this->listsForFields['category']           = Category::pluck('name', 'id')->toArray();
        $locations = Location::where('active', 1)->pluck('airport_code', 'airport_code')->toArray();
        $this->listsForFields['dep_from']           = $locations;
        $this->listsForFields['dep_to']             = $locations;
        $this->listsForFields['ret_from']           = $locations;
        $this->listsForFields['ret_to']             = $locations;
        $this->listsForFields['users']              = User::orderBy('name')->pluck('name', 'email')->toArray();
        $this->listsForFields['whitelabels']        = Agency::orderBy('name')->pluck('name', 'id')->toArray();
        $this->listsForFields['airlines']           = Airline::pluck('name', 'id')->toArray();
        $this->listsForFields['contract']           = Contract::orderBy('name')->pluck('name', 'id')->toArray();
        $this->listsForFields['type']               = Flight::TYPE_SELECT;
    }
}
