<?php

namespace App\Http\Livewire\Frontend\Components\Header\Search;

use Carbon\Carbon;
use Livewire\Component;

class Roundtrip extends Component
{

    public $locations       = [];
    public $query           = [];
    public $fromList        = [];
    public $toList          = [];
    public $departureList   = [];
    public $returnList      = [];

    public $dep_from_code   = '';
    public $dep_to_code     = '';
    public $dep_date        = '';
    public $ret_date        = '';
    public $adults          = 1;
    public $infants         = 0;
    public $children        = 0;
    public function mount()
    {
        $this->query = request()->query();
        $locations = getSearchData();
        $locations = $locations
            ->where('type', 'oneway')
            ->unique('dep_from_city_code')
            ->pluck('dep_from_city_code', 'dep_from_city')
            ->toArray();
        $this->fromList = $locations;
        $this->populate();
    }

    public function render()
    {
        return view('livewire.frontend.components.header.search.roundtrip');
    }

    public function populate(): void
    {
        if(request()->input('type') == 'roundtrip') {
            $this->dep_from_code    = request()->input('dep_from_code');
            $this->updatedDepFromCode();
            $this->dep_to_code      = request()->input('dep_to_code');
            $this->updatedDepToCode();
            $this->dep_date         = request()->input('dep_date');
            $this->updatedDepDate();
            $this->ret_date         = request()->input('ret_date');
            $this->updatedRetDate();
            $this->adults           = request()->input('adults');
            $this->infants          = request()->input('infants');
            $this->children         = request()->input('children');
        }
    }

    public function updatedDepFromCode(): void
    {
        $this->dep_to_code  = '';
        $this->dep_date     = '';
        $this->ret_date     = '';
        $this->adults       = 1;
        $this->children     = 0;
        $this->infants      = 0;
        $this->dispatchBrowserEvent('set-roundtrip-passengers-number', ['passengers' => $this->adults + $this->infants + $this->children]);
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

    public function updatedDepToCode(): void
    {
        $this->dep_date     = '';
        $this->ret_date     = '';
        $this->adults       = 1;
        $this->children     = 0;
        $this->infants      = 0;
        $this->dispatchBrowserEvent('set-roundtrip-passengers-number', ['passengers' => $this->adults + $this->infants + $this->children]);
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
            ->unique('dep_date')
            ->pluck('dep_date')
            ->sortBy(function($date) {
                return Carbon::createFromFormat('d-m-Y', $date);
            })
            ->values()
            ->all();
        $this->departureList = $locations;
        $this->dispatchBrowserEvent('set-roundtrip-departure-date', ['dates' => $this->departureList]);
    }

    public function updatedDepDate(): void
    {
        //Reset other filters
        $this->ret_date     = '';
        $this->adults       = 1;
        $this->children     = 0;
        $this->infants      = 0;
        $this->dispatchBrowserEvent('set-roundtrip-passengers-number', ['passengers' => $this->adults + $this->infants + $this->children]);
        $locations = getSearchData();
        $targetDate = Carbon::parse($this->dep_date);

        // Filter the collection using where and convert back to strings
        $filteredLocations = $locations
            ->where('dep_from_city_code', $this->dep_to_code)
            ->where('dep_to_city_code', $this->dep_from_code)
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->filter(function ($location) use ($targetDate) {
                return Carbon::parse($location['dep_date'])->gt($targetDate);
            })
            ->unique('dep_date')
            ->pluck('dep_date')
            ->toArray();
        $this->returnList = $filteredLocations;
        $this->dispatchBrowserEvent('set-roundtrip-return-date', ['dates' => $this->returnList]);
    }

    public function updatedRetDate(): void
    {
        $this->adults   = 1;
        $this->children = 0;
        $this->infants  = 0;
        $this->dispatchBrowserEvent('set-roundtrip-passengers-number', ['passengers' => $this->adults + $this->infants + $this->children]);
    }

    public function inc($passenger): void
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

    public function dec($passenger): void
    {
        if($this->{$passenger} > 0) {
            $this->{$passenger}--;
        }
    }
}
