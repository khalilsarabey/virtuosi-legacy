<?php

namespace App\Livewire\Frontend\Components;

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Models\Flight;
use App\Services\Local\FlightService;
use Carbon\Carbon;
use Livewire\Component;

class ReturnDates extends Component
{
    public $query;
    public $needed_seats;
    public $returnDates = [];
    public $flightsBefore = [];
    public $flightsAfter = [];
    public $flightCurrent;

    public function init()
    {

        if ($this->query['type'] == 'roundtrip') {
            //$type = isset($this->query['type']) && $this->query['type'] == 'fixed' ? 'fixed' : 'oneway';

            $dep_from_code  = $this->query['dep_to_code'];
            $dep_to_code    = $this->query['dep_from_code'];
            $query = $this->query;
            $query['dep_date']        = $this->query['ret_date'];
            $query['dep_from_code']   = $dep_from_code;
            $query['dep_to_code']     = $dep_to_code;

            $results = FlightService::flexible_dates($query);

            $this->flightsBefore    = $results['beforeFlights'] ?? [];
            $this->flightsAfter     = $results['afterFlights'] ?? [];
            $this->flightCurrent    = $results['currentFlight'][0] ?? null;

            if(count($this->flightsBefore) == 0 && count($this->flightsAfter) == 0) {
                $apiResponse = ApiClientSearchController::getFlexibleDates($query);
                $this->flightsBefore    = $apiResponse['data']['beforeFlights'] ?? [];
                $this->flightsAfter     = $apiResponse['data']['afterFlights'] ?? [];
                $this->flightCurrent    = $apiResponse['data']['currentFlight'][0] ?? null;
            }

            $this->dispatch('return-dates-updated');
        }

    }


    public function render()
    {
        return view('livewire.frontend.components.return-dates');
    }
}
