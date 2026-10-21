<?php

namespace App\Livewire\Frontend\Components\Header\Search;

use App\Models\Flight;
use App\Models\Location;
use App\Services\FlightService;
use Carbon\Carbon;
use Livewire\Component;

class Oneway extends Component
{
    public $locations       = [];
    public $query           = [];
    public $fromList        = [];
    public $toList          = [];
    public $departureList   = [];

    public $dep_from_code   = '';
    public $dep_to_code     = '';
    public $dep_date        = '';
    public $adults          = 1;
    public $infants         = 0;
    public $children        = 0;

    public function mount()
    {
        $this->query = request()->query();
        $locations = getSearchData();
        $locations = $locations
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->unique('dep_from_city_code')
            ->pluck('dep_from_city_code', 'dep_from_city')
            ->toArray();
        $this->fromList = $locations;
        $this->populate();
    }

    public function render()
    {
        return view('livewire.frontend.components.header.search.oneway');
    }

    public function populate()
    {
        if(request()->input('type') == 'oneway') {
            $this->dep_from_code = $this->query['dep_from_code'];
            $this->updatedDepFromCode();
            $this->dep_to_code  = $this->query['dep_to_code'];
            $this->updatedDepToCode();
            $this->dep_date     = $this->query['dep_date'];
            $this->adults       = $this->query['adults'];
            $this->infants      = $this->query['infants'];
            $this->children     = $this->query['children'];
        }
    }

    public function updatedDepFromCode()
    {
        $locations = getSearchData()->lazy();
        $locations = $locations
            ->where('dep_from_city_code', $this->dep_from_code)
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->unique('dep_to_city_code')
            ->pluck('dep_to_city_code', 'dep_to_city')
            ->toArray();
        $this->toList = $locations;
    }

    public function updatedDepToCode()
    {
        $locations = getSearchData();
        $locations = $locations
            ->where('dep_from_city_code', $this->dep_from_code)
            ->where('dep_to_city_code', $this->dep_to_code)
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->filter(function ($location) {
                $date = Carbon::today()->addDays(isset($location['stop_booking_before']) && $location['stop_booking_before'] > 0 ? $location['stop_booking_before'] : 0);
                return Carbon::parse($location['dep_date'])->greaterThanOrEqualTo($date);
            })
            ->sortBy('dep_date')
            ->unique('dep_date')
            ->pluck('dep_date')
            ->sortBy(function($date) {
                return Carbon::createFromFormat('d-m-Y', $date);
            })
            ->values()
            ->all();
        $this->departureList = $locations;
        $this->dispatch('set-oneway-departure-date', dates: $this->departureList);
    }

    public function inc($passenger)
    {
        if(config('app.saas_id') == 'tb2book' || config('app.saas_id') == 'ctsonline') {
            if(($this->adults + $this->children < 9) || $passenger == 'infants')
            {
                $this->{$passenger}++;
            }
        } else {
            $this->{$passenger}++;
        }
    }

    public function dec($passenger)
    {
        if($this->{$passenger} > 0) {
            $this->{$passenger}--;
        }
    }
}
