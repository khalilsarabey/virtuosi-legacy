<?php

namespace App\Livewire\Frontend\Components\Header\Search;

use App\Models\Flight;
use App\Models\Location;
use App\Services\FlightService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Multiple extends Component
{
    public $allLocations = [];
    public $query = [];
    public $fromList = [];
    public $toList = [];
    public $departureList = [];
    public $returnFromList = [];
    public $returnToList = [];
    public $returnDepartureList = [];

    public $dep_from_code = '';
    public $dep_to_code = '';
    public $dep_date = '';

    public $ret_from_code = '';
    public $ret_to_code = '';
    public $ret_date = '';

    public $adults = 1;
    public $infants = 0;
    public $children = 0;
    public function mount()
    {
        $this->query     = request()->query();
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
        return view('livewire.frontend.components.header.search.multiple');
    }

    public function populate(): void
    {
        if(count($this->query) && $this->query['type'] == 'multi') {
            $this->dep_from_code    = $this->query['dep_from_code'];
            $this->updatedDepFromCode();
            $this->dep_to_code      = $this->query['dep_to_code'];
            $this->updatedDepToCode();
            $this->dep_date         = $this->query['dep_date'];
            $this->updatedDepDate();
            $this->ret_from_code    = $this->query['ret_from_code'];
            $this->updatedRetFromCode();
            $this->ret_to_code      = $this->query['ret_to_code'];
            $this->updatedRetToCode();
            $this->ret_date         = $this->query['ret_date'];
            $this->adults           = $this->query['adults'];
            $this->infants          = $this->query['infants'];
            $this->children         = $this->query['children'];
        }
    }

    public function updatedDepFromCode(): void
    {
        $this->dep_to_code = '';
        $locations = getSearchData();
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
//            ->filter(function ($location) {
//                return isset($location['is_connection']) && $location['is_connection'] == false;
//            })
            ->filter(function ($location) {
                return Carbon::parse($location['dep_date'])->isFuture();
            })
            ->unique('dep_to_city_code')
            ->pluck('dep_to_city_code', 'dep_to_city')
            ->toArray();
        $this->toList = $locations;

        $this->dep_date         = '';
        $this->ret_date         = '';
        $this->ret_from_code    = '';
        $this->ret_to_code      = '';
    }

    public function updatedDepToCode(): void
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
//            ->filter(function ($location) {
//                return isset($location['is_connection']) && $location['is_connection'] == false;
//            })
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
        $this->dep_date         = '';
        $this->ret_date         = '';
        $this->ret_from_code    = '';
        $this->ret_to_code      = '';
        $this->departureList = $locations;
        $this->dispatch('set-multi-departure-date', dates: $this->departureList);
    }

    public function updatedDepDate(): void
    {
        $this->ret_date         = '';
        $this->ret_from_code    = '';
        $this->ret_to_code      = '';
        $this->returnFromList = $this->fromList;
    }

    public function updatedRetFromCode(): void
    {
        $this->ret_to_code = '';
        $searchLocations = getSearchData();
        $this->returnToList = $searchLocations
            ->where('dep_from_city_code', $this->ret_from_code)
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
//            ->filter(function ($location) {
//                return isset($location['is_connection']) && $location['is_connection'] == false;
//            })
            ->unique('dep_to_city_code')
            ->pluck('dep_to_city_code', 'dep_to_city')
            ->toArray();
    }

    public function updatedRetToCode(): void
    {
        $targetDate = Carbon::parse($this->dep_date);
        $searchLocations = getSearchData();
        $this->returnDepartureList = $searchLocations
            ->where('dep_from_city_code', $this->ret_from_code)
            ->where('dep_to_city_code', $this->ret_to_code)
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
//            ->filter(function ($location) {
//                return isset($location['is_connection']) && $location['is_connection'] == false;
//            })
            ->filter(function ($location) use ($targetDate) {
                return Carbon::parse($location['dep_date'])->gt($targetDate);
            })
            ->unique('dep_date')
            ->pluck('dep_date')
            ->sortBy(function($date) {
                return Carbon::createFromFormat('d-m-Y', $date);
            })
            ->values()
            ->all();

        $this->dispatch('set-multi-return-departure-date', dates: $this->returnDepartureList);
    }

    public function inc($passenger): void
    {
        $this->{$passenger}++;
    }

    public function dec($passenger): void
    {
        if($this->{$passenger} > 0) {
            $this->{$passenger}--;
        }
    }
}
