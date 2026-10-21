<?php

namespace App\Http\Controllers\Connections\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SearchLocationsCollection;
use App\Models\Flight;
use App\Services\FlightService;
use App\Services\HotelService;
use App\View\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiSearchController extends Controller
{
    use HttpResponses;

    private $query = [];
    private $needed_seats = 1;
    private $foundDepartures = [];
    private $foundReturns = [];
    private $foundResultsLowestPrices = [];
    private $dep;
    private $ret;
    private $departuresHeader = [];
    private $grids = [];

    public function locations(Request $request)
    {
        $conditions = $request->query('from') ? ['dep_from_code' => $request->query('from')] : [];
        $results = FlightService::get_flights_locations('oneway', $conditions);
        return new SearchLocationsCollection($results);
    }

    public function flights(Request $request)
    {
        $conditions = $request->all();
        $results = FlightService::search_flights($conditions);
        return new SearchLocationsCollection($results);
    }

    public function hotels(Request $request)
    {
        $conditions = $request->all();
        $results = HotelService::search_hotels($conditions);
        return new SearchLocationsCollection($results);
    }

    public function getHotelDetails(Request $request)
    {
        $conditions = $request->all();
        $results = HotelService::get_hotel_details($conditions);
        return new SearchLocationsCollection($results);
    }

    public function getHotelRooms(Request $request)
    {
        $conditions = $request->all();
        $results = HotelService::get_hotel_rooms($conditions);
        return new SearchLocationsCollection($results);
    }

    public function getFlights(Request $request)
    {
        $conditions = $request->all();
        $results = FlightService::get_flights($conditions);
        return new SearchLocationsCollection($results);
    }

    public function storeBooking(Request $request)
    {
        $conditions = $request->all();
        $results = FlightService::store_booking($conditions);
        return new SearchLocationsCollection($results);
    }

    public function flexible(Request $request)
    {
        $conditions = $request->all();
        $results = FlightService::flexible_dates($conditions);
        return new SearchLocationsCollection($results);
    }

    public function grid(Request $request)
    {
        $conditions = $request->all();
        $results = FlightService::grid($conditions);
        return new SearchLocationsCollection($results);
    }

    protected function departureDates()
    {
        $flights = Flight::restrict($this->needed_seats)
            ->with(['prices', 'airline'])
            ->where('dep_from_code', $this->query['dep_from_code'])
            ->where('dep_to_code', $this->query['dep_to_code'])
            ->where('type', 'oneway')
            ->where('dep_date', '>=', $this->dep->format('Y-m-d'))
            ->groupBy('dep_date')
            ->orderBy('dep_date')
            ->take(7)
            ->get();
        $this->departuresHeader = $flights->pluck('dep_date');
        return $flights;
    }

    protected function returnDates()
    {
        return Flight::restrict($this->needed_seats)
            ->with(['prices', 'airline'])
            ->where('dep_from_code', $this->query['dep_to_code'])
            ->where('dep_to_code', $this->query['dep_from_code'])
            ->where('type', 'oneway')
            ->where('dep_date', '>=', $this->ret->format('Y-m-d'))
            ->groupBy('dep_date')
            ->orderBy('dep_date')
            ->take(7)
            ->get();
    }

    private function calculatePrices($priceArray)
    {
        $prices = [
            'adult'     => 0,
            'child'     => 0,
            'infant'    => 0
        ];
        if(count($priceArray['adult'] ?? []) > 0) {
            foreach ($priceArray['adult'] as $adult) {
                if ($adult['number'] > 0) {
                    $prices['adult'] += $adult['number'] * $adult['price'];
                }
            }
        }
        if(count($priceArray['child'] ?? []) > 0) {
            foreach ($priceArray['child'] ?? [] as $child) {
                if ($child['number'] > 0) {
                    $prices['child'] += $child['number'] * $child['price'];
                }
            }
        }
        if(count($priceArray['infant'] ?? []) > 0) {
            foreach ($priceArray['infant'] ?? [] as $infant) {
                if ($infant['number'] > 0) {
                    $prices['infant'] += $infant['number'] * $infant['price'];
                }
            }
        }
        return $prices;
    }

    private function getLowestPriceFromResults()
    {
        $lowestDeparture    = 0;
        $lowestReturn       = 0;

        $query = request()->query();

        foreach($this->foundDepartures as $departure) {
            $prices  = getPrice($departure, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
            $total = 0;
            // Check for adult prices
            if (count($query) && isset($query['adults']) && $query['adults'] > 0) {
                foreach ($prices['adult'] as $adult) {
                    $total += $adult['number'] * $adult['price'];
                }
            }

            // Check for child prices
            if (count($query) && isset($query['children']) && $query['children'] > 0) {
                foreach ($prices['child'] as $child) {
                    $total += $child['number'] * $child['price'];
                }
            }

            // Check for infant prices
            if (count($query) && isset($query['infants']) && $query['infants'] > 0) {
                foreach ($prices['infant'] as $infant) {
                    $total += $infant['number'] * $infant['price'];
                }
            }
            if($lowestDeparture == 0 || $total < $lowestDeparture) {
                $lowestDeparture = $total;
            }
        }

        foreach($this->foundReturns as $return) {
            $prices     = getPrice($return, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
            $total = 0;
            // Check for adult prices
            if (count($query) && isset($query['adults']) && $query['adults'] > 0) {
                foreach ($prices['adult'] as $adult) {
                    $total += $adult['number'] * $adult['price'];
                }
            }

            // Check for child prices
            if (count($query) && isset($query['children']) && $query['children'] > 0) {
                foreach ($prices['child'] as $child) {
                    $total += $child['number'] * $child['price'];
                }
            }

            // Check for infant prices
            if (count($query) && isset($query['infants']) && $query['infants'] > 0) {
                foreach ($prices['infant'] as $infant) {
                    $total += $infant['number'] * $infant['price'];
                }
            }
            if($lowestReturn == 0 || $total < $lowestReturn) {
                $lowestReturn = $total;
            }

        }

        return ['lowestDeparture' => $lowestDeparture, 'lowestReturn' => $lowestReturn, 'total' => $lowestDeparture + $lowestReturn];
    }
}
