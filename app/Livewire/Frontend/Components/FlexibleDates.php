<?php

namespace App\Livewire\Frontend\Components;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Models\Flight;
use Carbon\Carbon;
use App\Http\Controllers\Connections\Client\ApiClientSearchController;

class FlexibleDates extends Component
{
    public $query;
    public $return = 0;
    public $needed_seats;
    public $flightsBefore = [];
    public $flightCurrent = [];
    public $flightsAfter = [];

    public function init(): void
    {
        $departureDate = Carbon::createFromFormat('d-m-Y', $this->return == 1 ? $this->query['ret_date'] : $this->query['dep_date']);
        $baseQuery = $this->getBaseFlightQuery();

        // Fetch flights for dates before, on, and after the selected date
        $this->flightsBefore = $this->getFlightsBeforeDate($baseQuery, $departureDate);
        $this->flightCurrent = $this->getFlightsForCurrentDate($baseQuery, $departureDate);
        $this->flightsAfter = $this->getFlightsAfterDate($baseQuery, $departureDate);

        // If no local flights found, try to get from API
        if ($this->shouldFetchFromApi()) {
            $this->fetchFlightsFromApi();
        }

        $this->dispatch('flexible-dates-updated');
    }

    /**
     * Get the base query for flight searches
     *
     * @return Builder
     */
    protected function getBaseFlightQuery(): Builder
    {
        return Flight::query()
            ->with('airline', 'prices')
            ->where('dep_from_code', $this->return == 0 ? $this->query['dep_from_code'] : $this->query['dep_to_code'])
            ->where('dep_to_code', $this->return == 0 ? $this->query['dep_to_code'] : $this->query['dep_from_code'])
            ->restrict($this->needed_seats);
    }

    /**
     * Get flights for dates before the selected date
     *
     * @param Builder $baseQuery
     * @param Carbon $departureDate
     * @return array
     */
    protected function getFlightsBeforeDate($baseQuery, $departureDate): array
    {
        $datesBefore = (clone $baseQuery)
            ->where('dep_date', '<', $departureDate->format('Y-m-d'))
            ->orderBy('dep_date', 'desc')
            ->take(10)
            ->get()
            ->groupBy('dep_date');

        return $this->processFlightGroups($datesBefore);
    }

    /**
     * Get flights for the current selected date
     *
     * @param Builder $baseQuery
     * @param Carbon $departureDate
     * @return array
     */
    protected function getFlightsForCurrentDate($baseQuery, $departureDate): array
    {
        $currentFlights = (clone $baseQuery)
            ->where('dep_date', $departureDate->format('Y-m-d'))
            ->get();

        $result = $this->findCheapestFlight($currentFlights);

        return $result ? [$this->formatFlightData($result['flight'], $result['price'])] : [];
    }

    /**
     * Get flights for dates after the selected date
     *
     * @param Builder $baseQuery
     * @param Carbon $departureDate
     * @return array
     */
    protected function getFlightsAfterDate($baseQuery, $departureDate): array
    {
        $afterDates = (clone $baseQuery)
            ->where('dep_date', '>', $departureDate->format('Y-m-d'))
            ->orderBy('dep_date')
            ->take(10)
            ->get()
            ->groupBy('dep_date');

        return $this->processFlightGroups($afterDates);
    }

    /**
     * Process flight groups and find the cheapest flight for each date
     *
     * @param \Illuminate\Support\Collection $flightGroups
     * @return array
     */
    protected function processFlightGroups($flightGroups): array
    {
        $results = [];

        foreach ($flightGroups as $date => $flights) {
            $result = $this->findCheapestFlight($flights);

            if ($result) {
                $results[] = $this->formatFlightData($result['flight'], $result['price']);
            }
        }

        return $results;
    }

    /**
     * Find the cheapest flight from a collection of flights
     *
     * @param \Illuminate\Support\Collection $flights
     * @return array|null
     */
    protected function findCheapestFlight($flights): ?array
    {
        $cheapestPrice = 0;
        $cheapestFlight = null;

        foreach ($flights as $flight) {
            $total = $this->calculateTotalPrice($flight);

            if ($cheapestPrice === 0 || $total < $cheapestPrice) {
                $cheapestPrice = $total;
                $cheapestFlight = $flight;
            }
        }

        return $cheapestFlight ? ['flight' => $cheapestFlight, 'price' => $cheapestPrice] : null;
    }

    /**
     * Calculate the total price for a flight
     *
     * @param Flight $flight
     * @return float
     */
    protected function calculateTotalPrice($flight): float|int
    {
        // Convert to array to avoid modifying the original object
        $price = getPrice($flight->toArray(), null, $this->getPassengersArray());

        $total = 0;

        // Calculate total price for all passenger types
        foreach (['adult', 'child', 'infant'] as $passengerType) {
            if (isset($price[$passengerType])) {
                foreach ($price[$passengerType] as $passenger) {
                    $total += $passenger['number'] * $passenger['price'];
                }
            }
        }

        return $total;
    }

    /**
     * Get the passengers array for price calculation
     *
     * @return array
     */
    protected function getPassengersArray(): array
    {
        return [
            'adult' => $this->query['adults'] ?? 1,
            'child' => $this->query['children'] ?? 0,
            'infant' => $this->query['infants'] ?? 0
        ];
    }

    /**
     * Format flight data for the view
     *
     * @param Flight $flight
     * @param float $price
     * @return array
     */
    protected function formatFlightData($flight, $price): array
    {
        return [
            'price' => $price,
            'airline' => [
                'name' => $flight->airline?->name,
                'logo' => $flight->airline?->thumbnail
            ],
            'dep_date' => $flight->dep_date
        ];
    }

    /**
     * Check if we should fetch flights from the API
     *
     * @return bool
     */
    protected function shouldFetchFromApi(): bool
    {
        return count($this->flightsBefore) === 0 &&
               count($this->flightsAfter) === 0 &&
               empty($this->flightCurrent);
    }

    /**
     * Fetch flights from the API
     */
    protected function fetchFlightsFromApi(): void
    {
        $apiResponse = ApiClientSearchController::getFlexibleDates([
            'dep_from_code' => $this->query['dep_from_code'] ?? '',
            'dep_to_code'   => $this->query['dep_to_code'] ?? '',
            'dep_date'      => $this->query['dep_date'] ?? '',
            'adults'        => $this->query['adults'] ?? 1,
            'children'      => $this->query['children'] ?? 0,
            'infants'       => $this->query['infants'] ?? 0,
        ]);

        // Process API response if available
        if ($apiResponse && isset($apiResponse['data'])) {
            // Process API data to populate flightsBefore, flightCurrent, and flightsAfter
            // Implementation would depend on the API response structure
        }
    }

    public function render()
    {
        return view('livewire.frontend.components.flexible-dates');
    }
}
