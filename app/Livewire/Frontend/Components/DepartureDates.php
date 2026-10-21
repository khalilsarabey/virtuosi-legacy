<?php

namespace App\Livewire\Frontend\Components;

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Models\Flight;
use App\Services\Local\FlightService;
use Carbon\Carbon;
use Livewire\Component;

class DepartureDates extends Component
{
    public $query;
    public $needed_seats;
    public $departureDates = [];
    public $flightsBefore = [];
    public $flightsAfter = [];
    public $flightCurrent;

    public function init()
    {
        if (isset($this->query['category'])) return false;

        //$type = isset($this->query['type']) && $this->query['type'] == 'fixed' ? 'fixed' : 'oneway';

        $results = FlightService::flexible_dates($this->query);

        $this->flightsBefore    = $results['beforeFlights'] ?? [];
        $this->flightsAfter     = $results['afterFlights'] ?? [];
        $this->flightCurrent    = $results['currentFlight'][0] ?? null;

        if(count($this->flightsBefore) == 0 && count($this->flightsAfter) == 0) {
            $apiResponse = ApiClientSearchController::getFlexibleDates($this->query);
            $this->flightsBefore    = $apiResponse['data']['beforeFlights'] ?? [];
            $this->flightsAfter     = $apiResponse['data']['afterFlights'] ?? [];
            $this->flightCurrent    = $apiResponse['data']['currentFlight'][0] ?? null;
        }

        $this->dispatch('departure-dates-updated');
    }

    public function render()
    {
        return view('livewire.frontend.components.departure-dates');
    }
}
