<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Http\Services\BookingService;
use App\Models\Flight;
use App\Services\Local\HotelService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;

class Search extends Component
{
    //394 510 205
    public $query;
    public $hasCheaper = false;
    public $isPackage = 0;
    public $showCheaperPrices = 0;
    public $selectedAirline;
    public $departures  = [];
    public $returns     = [];
    public $passengers  = [];
    public $apiDepartures  = [];
    public $apiReturns     = [];

    public $selectedDeparture;
    public $selectedReturn;
    public $total;
    public $supplements;
    public $adults;
    public $infants;
    public $children;
    public $prices = [
        'adults'        => 0,
        'children'      => 0,
        'infants'       => 0,
        'supplements'   => 0,
        'total'         => 0,
    ];
    public $basePrices = [
        'adults'        => 0,
        'children'      => 0,
        'infants'       => 0,
        'supplements'   => 0,
        'total'         => 0,
    ];
    public $selectedDepartureKey;
    public $selectedReturnKey;
    public $hotels = null;
    public $needed_seats = 1;
    public $departureDates = [];
    public $returnDates = [];
    public $selectedHotel;
    public $selectedApiDeparture = [];
    public $selectedApiReturn = [];
    public $apiSource;
    public $selectedReturnCabin;
    public $selectedDepartureCabin = 'economy';

    public function mount(Request $request)
    {

        $this->query = $request->all();

        $this->apiSource = null;

        if(count($this->query) == 0) abort(404);

        if($request->input('is_package', 0) == 1) {
            $this->isPackage    = 1;
            $this->adults       = 0;
            $this->infants      = 0;
            $this->children     = 0;
            foreach($request->input('rooms', []) as $room) {
                $this->adults   +=  $room['adult'];
                $this->infants  +=  $room['infant'];
                $this->children +=  $room['child'];
            }
            $this->query['adults']      =  $this->adults;
            $this->query['infants']     =  $this->infants;
            $this->query['children']    =  $this->children;
        } else {
            $this->adults       = $request->input('adults', 1);
            $this->infants      = $request->input('infants', 0);
            $this->children     = $request->input('children', 0);
        }

        $this->supplements  = 0;

        $this->validateRequest($this->query['type']);

        if(isset($this->query['adults']) && isset($this->query['children'])) {
            $this->needed_seats = $this->query['adults'] + $this->query['children'];
        }

        if(isset($this->query['type']) && $this->query['type'] == 'fixed') {

            if($this->needed_seats <= config('app.max_seats') || !empty($this->query['category'])) {

                $departure = Flight::restrict($this->needed_seats);

                if(isset($this->query['category'])) {
                    $departure->whereHas('category', function($q) {
                        $q->whereIn('id', $this->query['category']);
                    });
                }

                if(isset($this->query['dep_from_code'])) {
                    $departure->whereHas('depFrom', function($q) {
                        $q->where('city_code', $this->query['dep_from_code']);
                    });
                }

                if(isset($this->query['dep_to_code'])) {
                    $departure->whereHas('depTo', function($q) {
                        $q->where('city_code', $this->query['dep_to_code']);
                    });
                }

                if(isset($this->query['type'])) {
                    $departure->where('type', 'fixed');
                }

                if(isset($this->query['dep_date'])) {
                    $departure->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $this->query['dep_date'])->format('Y-m-d'));
                }

                $departure->with([
                    'prices', 'depFrom', 'depTo', 'retFrom', 'retTo', 'airline'
                ]);

                $departure->orderBy('dep_date');
                $departure->orderBy('dep_time');
                $departures = $departure->get();
                $this->departures = $departures->sortBy(function ($departures) {
                    return $departures->prices->first()->adult;
                })->sortBy('airline_id')->toArray();
            }

        } else {

            if($this->needed_seats <= config('app.max_seats') || !empty($this->query['category'])) {
                $departure = Flight::restrict($this->needed_seats);

                if(isset($this->query['category'])) {
                    $departure->whereHas('category', function($q) {
                        $q->whereIn('id', $this->query['category']);
                    });
                }

                if(isset($this->query['dep_from_code'])) {
                    $departure->whereHas('depFrom', function($q) {
                        $q->where('city_code', $this->query['dep_from_code']);
                    });
                }

                if(isset($this->query['dep_to_code'])) {
                    $departure->whereHas('depTo', function($q) {
                        $q->where('city_code', $this->query['dep_to_code']);
                    });
                }

                if(isset($this->query['type'])) {
                    $departure->where('type', 'oneway');
                }

                if(isset($this->query['dep_date'])) {
                    $departure->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $this->query['dep_date'])->format('Y-m-d'));
                }

                $departure->with([
                    'prices', 'depFrom', 'depTo', 'retFrom', 'retTo', 'airline'
                ]);

                $departure->orderBy('dep_date');
                $departure->orderBy('dep_time');
                $departures = $departure->get();

                $this->departures = $departures->sortBy(function ($departures) {
                    return $departures->prices->first()->adult;
                })->sortBy('airline_id')->toArray();
            }
        }

        if($request['type'] == 'roundtrip') {
            if($this->needed_seats <= config('app.max_seats') || !empty($this->query['category'])) {
                $return = Flight::restrict($this->needed_seats);
                if(isset($this->query['category'])) {
                    $return->whereHas('category', function($q) {
                        $q->whereIn('id', [$this->query['category']]);
                    });
                }
                if(isset($this->query['dep_from_code'])) {
                    $return->whereHas('depTo', function($q) {
                        $q->where('city_code', $this->query['dep_from_code']);
                    });
                }
                if(isset($this->query['dep_to_code'])) {
                    $return->whereHas('depFrom', function($q) {
                        $q->where('city_code', $this->query['dep_to_code']);
                    });
                }

                if(isset($this->query['type'])) {
                    $return->where('type', 'oneway');
                }

                if(isset($this->query['ret_date'])) {
                    $return->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $this->query['ret_date'])->format('Y-m-d'));
                }

                $return->orderBy('dep_date');
                $return->orderBy('dep_time');
                $return->with('airline');
                $returns = $return->get();
                $this->returns = $returns->sortBy(function ($returns) {
                    return $returns->prices->first()->adult;
                })->sortBy('airline_id')->toArray();
            }
        }

        if($request->type == 'multi') {
            if($this->needed_seats <= config('app.max_seats') || !empty($this->query['category'])) {
                $return = Flight::restrict($this->needed_seats);
                if(isset($this->query['category'])) {
                    $return->whereHas('category', function($q) {
                        $q->whereIn('id', [$this->query['category']]);
                    });
                }

                if(isset($this->query['ret_from_code'])) {
                    $return->whereHas('depFrom', function($q) {
                        $q->where('city_code', $this->query['ret_from_code']);
                    });
                }

                if(isset($this->query['ret_to_code'])) {
                    $return->whereHas('depTo', function($q) {
                        $q->where('city_code', $this->query['ret_to_code']);
                    });
                }

                if(isset($this->query['type'])) {
                    $return->where('type', 'oneway');
                }

                if(isset($this->query['ret_date'])) {
                    $return->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $this->query['ret_date'])->format('Y-m-d'));
                }
                $return->orderBy('dep_date');
                $return->orderBy('dep_time');
                $return->with('airline');
                $returns = $return->get();
                $this->returns = $returns->sortBy(function ($returns) {
                    return $returns->prices->first()->adult;
                })->sortBy('airline_id')->toArray();
            }
        }

        if(auth()->user()->agency_id == 1) {

            $results = ['departures' => [], 'returns' => []];

            $apiResults = ApiClientSearchController::searchFlights($request);

            foreach($apiResults as $apiResult) {
                $newKeyValue = ['source' => $apiResult['source'] ?? null];

                // Add key-value pair to all departure arrays
                if (isset($apiResult['data']['departures'])) {
                    $apiResult['data']['departures'] = array_map(function($departure) use ($newKeyValue) {
                        return array_merge($departure, $newKeyValue);
                    }, $apiResult['data']['departures']);
                    $results['departures'] = array_merge($results['departures'], $apiResult['data']['departures']);
                }

                // Add key-value pair to all return arrays
                if (isset($apiResult['data']['returns'])) {
                    $apiResult['data']['returns'] = array_map(function($return) use ($newKeyValue) {
                        return array_merge($return, $newKeyValue);
                    }, $apiResult['data']['returns']);
                    $results['returns'] = array_merge($results['returns'], $apiResult['data']['returns']);
                }
            }

            $departures   = array_merge($this->departures, $results['departures']);
            $returns      = array_merge($this->returns, $results['returns']);

            $this->departures = [];
            $this->returns = [];
            foreach($departures as $departure) {
                $hasEconomySeats = false;
                $hasBusinessSeats = false;
                if(isset($departure['economy_seats']) && ($departure['economy_seats'] - $departure['tickets_count']) >= $this->needed_seats) {
                    $baseEconomyPrices = getPrice($departure, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
                    $economyPrices = getPrice($departure, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')], request()->query('is_package', 0) == 1);

                    if(count($economyPrices) > 0) {
                        $hasEconomySeats = true;
                    }
                }
                if(config('features.cabins') && isset($departure['business_seats']) && ($departure['business_seats'] - $departure['business_tickets_count']) >= $this->needed_seats) {
                    $baseBusinessPrices = getBusinessPrice($departure, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
                    $businessPrices = getBusinessPrice($departure, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')], request()->query('is_package', 0) == 1);
                    if(count($businessPrices) > 0) {
                        $hasBusinessSeats = true;
                    }
                }
                $this->departures[] = [
                    'id' => $departure['id'],
                    'has_economy_seats' => $hasEconomySeats,
                    'has_business_seats' => $hasBusinessSeats,
                    'base_economy_seats_prices' => $baseEconomyPrices ?? [],
                    'base_business_seats_prices' => $baseBusinessPrices ?? [],
                    'economy_seats_prices' => $economyPrices ?? [],
                    'business_seats_prices' => $businessPrices ?? [],
                    'airline' => $departure['airline'],
                    'dep_date' => $departure['dep_date'],
                    'stop_booking_before' => $departure['stop_booking_before'],
                    'dep_time' => $departure['dep_time'],
                    'dep_arr_time' => $departure['dep_arr_time'],
                    'dep_flight_number' => $departure['dep_flight_number'],
                    'ret_dep_date' => $departure['ret_dep_date'],
                    'ret_dep_time' => $departure['ret_dep_time'],
                    'ret_arr_time' => $departure['ret_arr_time'],
                    'ret_flight_number' => $departure['ret_flight_number'],
                    'baggage_allowance' => $departure['baggage_allowance'],
                    'type' => $departure['type'],
                    'oneway_supplements' => $departure['oneway_supplements'],
                    'multi_supplements' => $departure['multi_supplements'],
                    'combinable_supplements' => $departure['combinable_supplements'],
                    'package_supplements' => $departure['package_supplements'] ?? 0,
                    'seats' => $departure['seats'],
                    'promo' => $departure['promo'] ?? 0,
                    'business_seats' => $departure['business_seats'] ?? 0,
                    'dep_from_code' => $departure['dep_from_code'],
                    'dep_to_code' => $departure['dep_to_code'],
                    'ret_from_code' => $departure['ret_from_code'],
                    'ret_to_code' => $departure['ret_to_code'],
                    'remaining_seats_display' => $departure['remaining_seats_display'],
                    'onhold_bookings' => $departure['onhold_bookings'],
                    'tickets_count' => $departure['tickets_count'],
                    'business_tickets_count' => $departure['business_tickets_count'] ?? 0,
                    'economy_seats' => $departure['economy_seats'] ?? 0,
                    'package_note' => $departure['package_note'] ?? null,
                    'dep_from' => $departure['dep_from'],
                    'dep_to' => $departure['dep_to'],
                    'ret_from' => $departure['ret_from'],
                    'ret_to' => $departure['ret_to'],
                    'prices' => $departure['prices'],
                    'source' => $departure['source'] ?? null,
                ];
            }

            foreach($returns as $return) {
                $hasEconomySeats = false;
                $hasBusinessSeats = false;
                if(isset($return['economy_seats']) && ($return['economy_seats'] - $return['tickets_count']) >= $this->needed_seats) {
                    $baseEconomyPrices = getPrice($return, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
                    $economyPrices = getPrice($return, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')], request()->query('is_package', 0) == 1);
                    if(count($economyPrices) > 0) {
                        $hasEconomySeats = true;
                    }
                }
                if(config('features.cabins') && ($return['business_seats'] - $return['business_tickets_count']) && $return['business_seats'] >= $this->needed_seats) {
                    $baseBusinessPrices = getBusinessPrice($return, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
                    $businessPrices = getBusinessPrice($return, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')], request()->query('is_package', 0) == 1);
                    if(count($businessPrices) > 0) {
                        $hasBusinessSeats = true;
                    }
                }
                $this->returns[] = [
                    'id' => $return['id'],
                    'has_economy_seats' => $hasEconomySeats,
                    'has_business_seats' => $hasBusinessSeats,
                    'base_economy_seats_prices' => $baseEconomyPrices ?? [],
                    'base_business_seats_prices' => $baseBusinessPrices ?? [],
                    'economy_seats_prices' => $economyPrices ?? [],
                    'business_seats_prices' => $businessPrices ?? [],
                    'airline' => $return['airline'],
                    'dep_date' => $return['dep_date'],
                    'stop_booking_before' => $return['stop_booking_before'],
                    'package_note' => $return['package_note'] ?? null,
                    'dep_time' => $return['dep_time'],
                    'dep_arr_time' => $return['dep_arr_time'],
                    'dep_flight_number' => $return['dep_flight_number'],
                    'ret_dep_date' => $return['ret_dep_date'],
                    'ret_dep_time' => $return['ret_dep_time'],
                    'ret_arr_time' => $return['ret_arr_time'],
                    'ret_flight_number' => $return['ret_flight_number'],
                    'baggage_allowance' => $return['baggage_allowance'],
                    'type' => $return['type'],
                    'oneway_supplements' => $return['oneway_supplements'],
                    'multi_supplements' => $return['multi_supplements'],
                    'combinable_supplements' => $return['combinable_supplements'],
                    'package_supplements' => $return['package_supplements'] ?? 0,
                    'seats' => $return['seats'],
                    'promo' => $return['promo'] ?? 0,
                    'business_seats' => $return['business_seats'] ?? 0,
                    'dep_from_code' => $return['dep_from_code'],
                    'dep_to_code' => $return['dep_to_code'],
                    'ret_from_code' => $return['ret_from_code'],
                    'ret_to_code' => $return['ret_to_code'],
                    'remaining_seats_display' => $return['remaining_seats_display'],
                    'onhold_bookings' => $return['onhold_bookings'],
                    'tickets_count' => $return['tickets_count'],
                    'business_tickets_count' => $return['business_tickets_count'] ?? 0,
                    'economy_seats' => $return['economy_seats'] ?? 0,
                    'dep_from' => $return['dep_from'] ?? null,
                    'dep_to' => $return['dep_to'] ?? null,
                    'ret_from' => $return['ret_from'] ?? null,
                    'ret_to' => $return['ret_to'] ?? null,
                    'prices' => $return['prices'],
                    'source' => $return['source'] ?? null,
                ];
            }
        }

        if($this->query['type'] == 'roundtrip')
        {
            $this->hasCheaper = true;
        }
    }

    public function render()
    {
        if($this->selectedHotel) {
            $this->dispatchBrowserEvent('scroll-to-button');
        } else if(($this->selectedDeparture && $this->query['type'] != 'roundtrip' && $this->query['type'] != 'multi') || (($this->query['type'] == 'roundtrip' || $this->query['type'] == 'multi') && $this->selectedDeparture && $this->selectedReturn)) {
            $this->dispatchBrowserEvent('scroll-to-summary');
        }
        return view('livewire.frontend.search', [
            'departures' => $this->departures,
            'returns' => $this->returns,
        ]);
    }

    public function selectHotel($id)
    {
        $this->selectedHotel = $id;
    }

    /**
     * @param integer $id
     */
    public function setDeparture(int $id, $cabin = 'economy'): void
    {
        $this->selectedDeparture    = $this->departures[$id]['id'];
        $this->selectedDepartureCabin = $cabin;
        $this->selectedDepartureKey = $id;
;        if(isset($this->departures[$id]['source']) && !empty($this->departures[$id]['source'])) {
            $this->selectedApiDeparture = $this->departures[$id];
            if(isset($this->departures[$id]['source'])) {
                $this->apiSource = $this->departures[$id]['source'];
            }
        } else {
            $this->apiSource = null;
            $this->selectedApiDeparture = [];
        }

        // Only reset the selected return, not all returns
        $this->selectedReturn = null;
        $this->selectedReturnKey = null;
        $this->selectedApiReturn = null;
        $this->selectedReturnCabin = null;

        $this->selectedAirline = $this->departures[$id]['airline']['icao'];

        $results = new BookingService();

        $prices = $results->calculate($this->query['type'], [
            'departure' => $this->selectedDeparture,
            'return' => $this->selectedReturn,
            'departure_cabin' => $this->selectedDepartureCabin,
            'return_cabin' => $this->selectedReturnCabin,
            'apiDeparture' => $this->selectedApiDeparture,
            'apiReturn' => $this->selectedApiReturn,
        ], [
            'adults'    => $this->adults,
            'children'  => $this->children,
            'infants'   => $this->infants,
        ], isset($this->departures[$id]['source']) && !empty($this->departures[$id]['source']) ? $this->selectedApiDeparture : null, $this->query['is_package'] ?? 0 == 1);

        $baseResults = new BookingService();

        $departureBasePrices = $baseResults->calculate($this->query['type'], [
            'departure' => $this->selectedDeparture,
            'return' => $this->selectedReturn,
            'departure_cabin' => $this->selectedDepartureCabin,
            'return_cabin' => $this->selectedReturnCabin,
            'apiDeparture' => $this->selectedApiDeparture,
            'apiReturn' => $this->selectedApiReturn,
        ], [
            'adults'    => $this->adults,
            'children'  => $this->children,
            'infants'   => $this->infants,
        ], isset($this->departures[$id]['source']) && !empty($this->departures[$id]['source']) ? $this->selectedApiDeparture : null, false);

        $this->basePrices['dep_class'] = $departureBasePrices['dep_class'] ?? null;
        $this->basePrices['adults'] = $departureBasePrices['adults'];
        $this->basePrices['children'] = $departureBasePrices['children'];
        $this->basePrices['infants'] = $departureBasePrices['infants'];
        $this->basePrices['total'] = $departureBasePrices['total'];
        $this->basePrices['supplements'] = $departureBasePrices['supplements'];
        $this->basePrices['supplements_breakdown'] = $departureBasePrices['supplements_breakdown'] ?? [];

        $this->prices['dep_class'] = $prices['dep_class'] ?? null;
        $this->prices['adults'] = $prices['adults'];
        $this->prices['children'] = $prices['children'];
        $this->prices['infants'] = $prices['infants'];
        $this->prices['total'] = $prices['total'];
        $this->prices['supplements'] = $prices['supplements'];
        $this->prices['supplements_breakdown'] = $prices['supplements_breakdown'] ?? [];
    }

    public function setReturn(int $id, $cabin = 'economy'): void
    {
        $this->selectedReturn    = $this->returns[$id]['id'];
        $this->selectedReturnCabin = $cabin;
        $this->selectedReturnKey = $id;
        if(isset($this->returns[$id]['source']) && !empty($this->returns[$id]['source'])) {
            $this->selectedApiReturn = $this->returns[$id];
        } else {
            $this->selectedApiReturn = [];
        }
        $results = new BookingService();
        $prices = $results->calculate($this->query['type'], [
            'departure' => $this->selectedDeparture,
            'return' => $this->selectedReturn,
            'departure_cabin' => $this->selectedDepartureCabin,
            'return_cabin' => $this->selectedReturnCabin,
            'apiDeparture' => $this->selectedApiDeparture,
            'apiReturn' => $this->selectedApiReturn
        ], [
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
        ], isset($this->returns[$id]['source']) && !empty($this->returns[$id]['source']) ? $this->selectedApiReturn : null, $this->query['is_package'] ?? 0 == 1);

        $baseResults = new BookingService();
        $returnBasePrices = $baseResults->calculate($this->query['type'], [
            'departure' => $this->selectedDeparture,
            'return' => $this->selectedReturn,
            'departure_cabin' => $this->selectedDepartureCabin,
            'return_cabin' => $this->selectedReturnCabin,
            'apiDeparture' => $this->selectedApiDeparture,
            'apiReturn' => $this->selectedApiReturn
        ], [
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
        ], isset($this->returns[$id]['source']) && !empty($this->returns[$id]['source']) ? $this->selectedApiReturn : null, false);

        $this->basePrices['dep_class'] = $returnBasePrices['dep_class'] ?? null;
        $this->basePrices['ret_class'] = $returnBasePrices['ret_class'] ?? null;
        $this->basePrices['adults'] = $returnBasePrices['adults'];
        $this->basePrices['children'] = $returnBasePrices['children'];
        $this->basePrices['infants'] = $returnBasePrices['infants'];
        $this->basePrices['total'] = $returnBasePrices['total'];
        $this->basePrices['supplements'] = $returnBasePrices['supplements'];
        $this->basePrices['supplements_breakdown'] = $returnBasePrices['supplements_breakdown'] ?? [];

        $this->prices['dep_class'] = $prices['dep_class'] ?? null;
        $this->prices['ret_class'] = $prices['ret_class'] ?? null;
        $this->prices['adults'] = $prices['adults'];
        $this->prices['children'] = $prices['children'];
        $this->prices['infants'] = $prices['infants'];
        $this->prices['total'] = $prices['total'];
        $this->prices['supplements'] = $prices['supplements'];
        $this->prices['supplements_breakdown'] = $prices['supplements_breakdown'] ?? [];


        if($this->isPackage) {

            if($this->apiSource) {

                if(auth()->user()->agency_id == 1)
                {
                    $apiData = ApiClientSearchController::searchHotels([
                        'destination'   => $this->query['dep_to_code'],
                        'checkIn'       => $this->query['dep_date'],
                        'checkOut'      => $this->query['ret_date'],
                        'rooms'         => $this->query['rooms'],
                        'api'           => $this->apiSource
                    ]);

                    $newKeyValue = ['source' => $apiData['source'] ?? null];

                    if (isset($apiData['data']['departures'])) {
                        $apiData['data'] = array_map(function($departure) use ($newKeyValue) {
                            return array_merge($departure, $newKeyValue);
                        }, $apiData['data']['departures']);
                    }
                    $this->hotels = $apiData['data'] ?? [];
                }
            } else {
                $this->hotels = HotelService::search_hotels(
                    $this->query['dep_to_code'],
                    $this->query['dep_date'],
                    $this->query['ret_date'],
                    $this->query['rooms']);
            }
        }
    }

    public function validateRequest($type)
    {
        // Define the required parameters for each type
        $requiredParams = [
            'roundtrip' => ['dep_from_code', 'dep_to_code', 'dep_date', 'ret_date'],
            'oneway'    => ['dep_from_code', 'dep_to_code', 'dep_date'],
            'multi'     => ['dep_from_code', 'dep_to_code', 'dep_date', 'ret_from_code', 'ret_to_code', 'ret_date'],
            'fixed'     => ['dep_from_code', 'dep_to_code', 'dep_date'],
        ];

        // Check if the type is valid and has defined required parameters
        if (!array_key_exists($type, $requiredParams)) {
            return redirect('/');
        }

        // Check for missing parameters
        foreach ($requiredParams[$type] as $param) {
            if (!isset($this->query[$param])) {
                return redirect('/');
            }
        }
    }

    public function showCheaper()
    {
        $this->showCheaperPrices = $this->showCheaperPrices == 1 ? 0 : 1;
    }
}
