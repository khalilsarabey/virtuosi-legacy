<?php

namespace App\Http\Livewire\Frontend\Components\Header\Search;

use App\Models\Flight;
use App\Models\Location;
use App\Services\FlightService;
use Livewire\Component;

class Fixed extends Component
{
    public $query           = [];
    public $dep_from_code   = '';
    public $dep_to_code     = '';
    public $dep_date        = '';
    public $fromList        = [];
    public $toList          = [];
    public $departureList   = [];

    public $adults          = 1;
    public $infants         = 0;
    public $children        = 0;

    public function mount(): void
    {
        $this->query    = request()->query();
        $locations = getSearchData()->lazy();
        $this->fromList = $locations
            ->where('type', 'fixed')
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
        $this->populate();
    }

    public function populate(): void
    {
        if(count($this->query) && $this->query['type'] == 'fixed') {
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

    public function render()
    {
        return view('livewire.frontend.components.header.search.fixed');
    }

    public function updatedDepFromCode(): void
    {
        $locations = getSearchData()->lazy();
        $this->toList = $locations
            ->where('dep_from_city_code', $this->dep_from_code)
            ->where('type', 'fixed')
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
    }

    public function updatedDepToCode(): void
    {
        $locations = getSearchData()->lazy();
        $this->departureList = $locations
            ->where('dep_from_city_code', $this->dep_from_code)
            ->where('dep_to_city_code', $this->dep_to_code)
            ->where('type', 'fixed')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->unique('dep_date')
            ->pluck('dep_date')
            ->toArray();

        $this->dispatchBrowserEvent('set-fixed-departure-date', ['dates' => $this->departureList]);
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
