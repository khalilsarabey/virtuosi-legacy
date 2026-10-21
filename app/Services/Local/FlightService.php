<?php

namespace App\Services\Local;

use App\Interfaces\FlightServiceInterface;
use App\Models\Flight;
use App\Models\Location;
use Carbon\Carbon;

class FlightService
{

    public static function get_flights_locations($type = null, $query = [])
    {
        return Flight::searching()
            ->get()
            ->toArray();
    }

    public static function get_flights_dates($type, $query = [])
    {
    }

    public static function search_flights($request)
    {
        $query = $request;
        $isPackage  = 0;
        $adults     = 0;
        $infants    = 0;
        $children   = 0;
        $departures = [];
        $returns    = [];

        if(count($query) == 0) return [];

        if(($request['is_package'] ?? 0) == 1) {
            $isPackage    = 1;
            foreach($request['rooms'] ?? [] as $room) {
                $adults   +=  $room['adult'];
                $infants  +=  $room['infant'];
                $children +=  $room['child'];
            }
            $query['adults']      =  $adults;
            $query['infants']     =  $infants;
            $query['children']    =  $children;
        }

        $supplements  = 0;

        $needed_seats = ($request['adults'] ?? 1) + ($request['children'] ?? 0);

        if(isset($query['type']) && $query['type'] == 'fixed') {
            if($needed_seats <= config('app.max_seats')) {
                $departure = Flight::restrict($needed_seats);

                if(isset($query['dep_from_code'])) {
                    $departure->whereHas('depFrom', function($q) use ($query) {
                        $q->where('city_code', $query['dep_from_code']);
                    });
                }

                if(isset($query['dep_to_code'])) {
                    $departure->whereHas('depTo', function($q) use ($query) {
                        $q->where('city_code', $query['dep_to_code']);
                    });
                }

                if(isset($query['type'])) {
                    $departure->where('type', 'fixed');
                }

                if(isset($query['dep_date'])) {
                    $departure->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $query['dep_date'])->format('Y-m-d'));
                }

                $departure->with([
                    'prices', 'depFrom', 'depTo', 'retFrom', 'retTo', 'airline'
                ]);

                $departure->orderBy('dep_date');
                $departure->orderBy('dep_time');
                $departures = $departure->get();
                $departures = $departures->sortBy(function ($departures) {
                    return $departures->prices->first()->adult;
                });
            }

        } else {
            if($needed_seats <= config('app.max_seats')) {
                $departure = Flight::restrict($needed_seats);

                if(isset($query['dep_from_code'])) {
                    $departure->whereHas('depFrom', function($q) use ($query) {
                        $q->where('city_code', $query['dep_from_code']);
                    });
                }

                if(isset($query['dep_to_code'])) {
                    $departure->whereHas('depTo', function($q) use ($query) {
                        $q->where('city_code', $query['dep_to_code']);
                    });
                }

                $departure->where('type', 'oneway');

                if(isset($query['dep_date'])) {
                    $departure->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $query['dep_date'])->format('Y-m-d'));
                }

                $departure->with([
                    'prices', 'depFrom', 'depTo', 'retFrom', 'retTo', 'airline'
                ]);
                $departure->orderBy('dep_date');
                $departure->orderBy('dep_time');
                $departures = $departure->get();

                $departures = $departures->sortBy(function ($departures) {
                    return $departures->prices->first()->adult;
                });

            }
        }

        if($request['type'] == 'roundtrip') {
            if($needed_seats <= config('app.max_seats')) {
                $return = Flight::restrict($needed_seats);

                if(isset($query['dep_to_code'])) {
                    $return->whereHas('depFrom', function($q) use ($query) {
                        $q->where('city_code', $query['dep_to_code']);
                    });
                }

                if(isset($query['dep_from_code'])) {
                    $return->whereHas('depTo', function($q) use ($query) {
                        $q->where('city_code', $query['dep_from_code']);
                    });
                }
                $return->where('type', 'oneway');
                if(isset($query['ret_date'])) {
                    $return->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $query['ret_date'])->format('Y-m-d'));
                }
                $return->orderBy('dep_date');
                $return->orderBy('dep_time');
                $return->with(['prices', 'depFrom', 'depTo', 'retFrom', 'retTo', 'airline']);
                $returns = $return->get();
                $returns = $returns->sortBy(function ($returns) {
                    return $returns->prices->first()->adult;
                });
            }

        }

        if($request['type'] == 'multi') {
            if($needed_seats <= config('app.max_seats')) {
                $return = Flight::restrict($needed_seats);

                if(isset($query['ret_from_code'])) {
                    $return->whereHas('depFrom', function($q) use ($query) {
                        $q->where('city_code', $query['ret_from_code']);
                    });
                }
                if(isset($query['ret_to_code'])) {
                    $return->whereHas('depTo', function($q) use ($query) {
                        $q->where('city_code', $query['ret_to_code']);
                    });
                }
                if(isset($query['type'])) {
                    $return->where('type', 'oneway');
                }
                if(isset($query['ret_date'])) {
                    $return->where('dep_date', '=', Carbon::createFromFormat('d-m-Y', $query['ret_date'])->format('Y-m-d'));
                }
                $return->orderBy('dep_date');
                $return->orderBy('dep_time');
                $return->with(['prices', 'depFrom', 'depTo', 'retFrom', 'retTo', 'airline']);
                $returns = $return->get();
                $returns = $returns->sortBy(function ($returns) {
                    return $returns->prices->first()->adult;
                });
            }
        }
        return collect(['departures' => $departures, 'returns' => $returns]);
    }

    public static function get_flights($request)
    {
        return Flight::restrict($request['adults'] + $request['children'])
            ->where('id', $request['id'])
            ->with(['prices', 'depFrom', 'depTo', 'retFrom', 'retTo', 'airline', 'contract'])
            ->get()
            ->toArray();
    }

    public static function store_booking($request)
    {
        return ['reply from store booking function'];
    }

    public static function flexible_dates($query)
    {
        if(empty($query)) return [];

        $type = isset($query['type']) && $query['type'] == 'fixed' ? 'fixed' : 'oneway';
        $departureDate = Carbon::createFromFormat('d-m-Y', $query['dep_date'])->format('Y-m-d');

        // Base query setup
        $baseQuery = Flight::restrict($query['adults'] + ($query['children'] ?? 0))
            ->with(['airline', 'prices'])
            ->where('type', $type);

        if (isset($query['dep_from_code'])) {
            $baseQuery->whereHas('depFrom', function($q) use ($query) {
                $q->where('city_code', $query['dep_from_code']);
            });
        }
        if (isset($query['dep_to_code'])) {
            $baseQuery->whereHas('depTo', function($q) use ($query) {
                $q->where('city_code', $query['dep_to_code']);
            });
        }

        // Get flights before current date
        $beforeFlights = (clone $baseQuery)
            ->where('dep_date', '<', $departureDate)
            ->orderBy('dep_date', 'desc')
            ->take(10)
            ->get()
            ->groupBy('dep_date')->reverse();

        // Get current date flight
        $currentFlights = (clone $baseQuery)
            ->where('dep_date', $departureDate)
            ->get()
            ->groupBy('dep_date');

        // Get flights after current date
        $afterFlights = (clone $baseQuery)
            ->where('dep_date', '>', $departureDate)
            ->orderBy('dep_date')
            ->take(10)
            ->get()
            ->groupBy('dep_date');

        $beforeFlightsList = self::addPriceToFlight($beforeFlights, $query) ?? [];
        $afterFlights = self::addPriceToFlight($afterFlights, $query) ?? [];
        $currentFlight = self::addPriceToFlight($currentFlights, $query) ?? [];

        return ['beforeFlights' => $beforeFlightsList, 'afterFlights' => $afterFlights, 'currentFlight' => $currentFlight];
    }

    public static function grid($request): array
    {
        $query = $request['query'] ?? [];
        if(count($query) == 0) return [];
        //Request params
        $dep_from_code      = $query['dep_from_code'] ?? '';
        $dep_to_code        = $query['dep_to_code'] ?? '';
        $dep_date           = $query['dep_date'] ?? '';
        $ret_date           = $query['ret_date'] ?? '';
        $adults             = $query['adults'] ?? 1;
        $children           = $query['children'] ?? 0;
        $infants            = $query['infants'] ?? 0;
        $cabin_type         = $query['cabin_type'] ?? null;
        $needed_seats       = $request['needed_seats'] ?? 1;

        $dep = Carbon::createFromFormat('d-m-Y', $dep_date);
        $ret = Carbon::createFromFormat('d-m-Y', $ret_date);

        //Departure Dates
        $start_date = $dep->format('Y-m-d');
        $end_date = $dep->copy()->addDays(15)->format('Y-m-d');
        $flights = Flight::restrict($needed_seats, $cabin_type);
        $flights->whereHas('depFrom', function($q) use ($dep_from_code) {
            $q->where('city_code', $dep_from_code);
        });
        $flights->whereHas('depTo', function($q) use ($dep_to_code) {
            $q->where('city_code', $dep_to_code);
        });
        $flights->where('dep_from_code', $dep_from_code);
        $flights->where('dep_to_code', $dep_to_code);
        $flights->where('type', 'oneway');
        $flights->whereBetween('dep_date', [$start_date, $end_date]);
        $flights->orderBy('dep_date');
        $flights->orderBy('dep_time');
        $flights->with(['airline', 'prices']);
        $departureDates = $flights->get();
        $departuresHeader = $departureDates->unique('dep_date')->take(7)->pluck('dep_date');

        //Return Dates
        $start_date = $ret->format('Y-m-d');
        $end_date = $ret->copy()->addDays(15)->format('Y-m-d');

        $return = Flight::restrict($needed_seats, $cabin_type);
        $flights->whereHas('depFrom', function($q) use ($dep_to_code) {
            $q->where('city_code', $dep_to_code);
        });
        $flights->whereHas('depTo', function($q) use ($dep_from_code) {
            $q->where('city_code', $dep_from_code);
        });
        $return->where('type', 'oneway');
        $return->whereBetween('dep_date', [$start_date, $end_date]);
        $return->orderBy('dep_date');
        $return->orderBy('dep_time');
        $return->with(['airline', 'prices']);
        $returnDates = $return->get();

        $groupedDepartures = $departureDates->groupBy('dep_date');
        $groupedReturns = $returnDates->groupBy('dep_date');
        $departures = [];
        $returns = [];
        $grid = [];

        foreach($groupedDepartures as $groupedDeparture) {
            $cheapest = 0;
            $object = null;
            foreach($groupedDeparture as $gDeparture) {
                $price = getPrice($gDeparture, $cabin_type, ['adult' => $adults ?? 1, 'child' => $children ?? 0, 'infant' => $infants ?? 0]);
                if(count($price) == 0) {
                    $price = getBusinessPrice($gDeparture, $cabin_type, ['adult' => $adults ?? 1, 'child' => $children ?? 0, 'infant' => $infants ?? 0]);
                }
                $calculated = self::calculatePrices($price);
                $currentObjectPrice = $calculated['total'];
                if($cheapest == 0 || $currentObjectPrice < $cheapest) {
                    $cheapest = $currentObjectPrice;
                    $price['total'] = $currentObjectPrice;
                    $object = $gDeparture;
                }
            }

            if ($object) {
                $departures[] = [
                    'dep_from' => $object->dep_from_code,
                    'dep_to' => $object->dep_to_code,
                    'airline_name' => $object->airline->name,
                    'airline_thumb' => $object->airline->thumbnail,
                    'airline_code' => $object->airline->icao,
                    'dep_date' => $object->dep_date,
                    'price' => $cheapest,
                    'combinable_supplements' => $object->combinable_supplements,
                    'multi_supplements' => $object->multi_supplements,
                    'oneway_supplements' => $object->multi_supplements
                ];
            }
        }

        foreach($groupedReturns as $groupedReturn) {
            $cheapest = 0;
            $object = null;
            foreach($groupedReturn as $gReturn) {
                $price = getPrice($gReturn, $cabin_type, ['adult' => $adults ?? 1, 'child' => $children ?? 0, 'infant' => $infants ?? 0]);
                if(count($price) == 0) {
                    $price = getBusinessPrice($gReturn, $cabin_type, ['adult' => $adults ?? 1, 'child' => $children ?? 0, 'infant' => $infants ?? 0]);
                }
                $calculated = self::calculatePrices($price, false);
                $currentObjectPrice = $calculated['total'];
                if($cheapest == 0 || $currentObjectPrice < $cheapest) {
                    $cheapest = $currentObjectPrice;
                    $price['total'] = $currentObjectPrice;
                    $object = $gReturn;
                }
            }

            if ($object) {
                $returns[] = [
                    'dep_from' => $object->dep_from_code,
                    'dep_to' => $object->dep_to_code,
                    'airline_name' => $object->airline->name,
                    'airline_thumb' => $object->airline->thumbnail,
                    'airline_code' => $object->airline->icao,
                    'dep_date' => $object->dep_date,
                    'price' => $cheapest,
                    'combinable_supplements' => $object->combinable_supplements,
                    'multi_supplements' => $object->multi_supplements,
                    'oneway_supplements' => $object->multi_supplements
                ];
            }
        }

        // Limit both arrays to 7 elements
        $departures = array_slice($departures, 0, 7);
        $returns = array_slice($returns, 0, 7);

        foreach($returns as $return) {
            foreach($departures as $departure) {
                if (Carbon::parse($return['dep_date'])->gt(Carbon::parse($departure['dep_date']))) {
                    $dep_price = $departure['price'];
                    $ret_price = $return['price'];
                    $prices = ['adult' => 0, 'child' => 0, 'infant' => 0, 'total' => $dep_price + $ret_price];

                    if($departure['airline_code'] != $return['airline_code']) {
                        $prices['total'] = $prices['total'] + ($departure['combinable_supplements'] + $return['combinable_supplements']);
                    }

                    $grid[] = [
                        'departure' => $departure,
                        'return' => $return,
                        'dep_price' => $dep_price,
                        'ret_price' => $ret_price,
                        'prices' => $prices,
                        'url' => '',
                    ];
                } else {
                    $grid[] = [
                        'departure' => [],
                        'return' => [],
                    ];
                }
            }
        }

        return [
            'departuresHeader' => $departuresHeader,
            'grids' => $grid
        ];
    }

    private static function calculatePrices($priceArray, $includeInfant = true)
    {
        $prices = [
            'adult'     => 0,
            'child'     => 0,
            'infant'    => 0,
            'total'     => 0
        ];
        if(count($priceArray['adult'] ?? []) > 0) {
            foreach ($priceArray['adult'] as $adult) {
                if ($adult['number'] > 0) {
                    $prices['adult'] += $adult['number'] * $adult['price'];
                    $prices['total'] += $adult['number'] * $adult['price'];
                }
            }
        }
        if(count($priceArray['child'] ?? []) > 0) {
            foreach ($priceArray['child'] ?? [] as $child) {
                if ($child['number'] > 0) {
                    $prices['child'] += $child['number'] * $child['price'];
                    $prices['total'] += $child['number'] * $child['price'];
                }
            }
        }
        if($includeInfant) {
            if(count($priceArray['infant'] ?? []) > 0) {
                foreach ($priceArray['infant'] ?? [] as $infant) {
                    if ($infant['number'] > 0) {
                        $prices['infant'] += $infant['number'] * $infant['price'];
                        $prices['total'] += $infant['number'] * $infant['price'];
                    }
                }
            }
        }
        return $prices;
    }

    protected static function addPriceToFlight($flights, $query)
    {
        $results = [];

        foreach ($flights as $date => $flightsForDate) {
            $cheapestFlight = null;
            $cheapestPrice = PHP_FLOAT_MAX;

            foreach ($flightsForDate as $flight) {
                // Check economy price
                $prices = getPrice($flight, null, [
                    'adult' => $query['adults'],
                    'infant' => $query['infants'],
                    'child' => $query['children']
                ]);

                $totalPrice = 0;

                if (is_array($prices)) {
                    // Calculate total economy price
                    if (isset($prices['adult']) && is_array($prices['adult'])) {
                        foreach ($prices['adult'] as $adult) {
                            $totalPrice += $adult['price'] * $adult['number'];
                        }
                    }
                    if (isset($prices['child']) && is_array($prices['child'])) {
                        foreach ($prices['child'] as $child) {
                            $totalPrice += $child['price'] * $child['number'];
                        }
                    }
                    if (isset($prices['infant']) && is_array($prices['infant'])) {
                        foreach ($prices['infant'] as $infant) {
                            $totalPrice += $infant['price'] * $infant['number'];
                        }
                    }
                }

                // Check business price
                $businessPrices = getBusinessPrice($flight, null, [
                    'adult' => $query['adults'],
                    'infant' => $query['infants'],
                    'child' => $query['children']
                ]);

                $totalBusinessPrice = 0;

                if (is_array($businessPrices)) {
                    // Calculate total business price
                    if (isset($businessPrices['adult']) && is_array($businessPrices['adult'])) {
                        foreach ($businessPrices['adult'] as $adult) {
                            $totalBusinessPrice += $adult['price'] * $adult['number'];
                        }
                    }
                    if (isset($businessPrices['child']) && is_array($businessPrices['child'])) {
                        foreach ($businessPrices['child'] as $child) {
                            $totalBusinessPrice += $child['price'] * $child['number'];
                        }
                    }
                    if (isset($businessPrices['infant']) && is_array($businessPrices['infant'])) {
                        foreach ($businessPrices['infant'] as $infant) {
                            $totalBusinessPrice += $infant['price'] * $infant['number'];
                        }
                    }
                }

                // Use the cheaper price between economy and business
                $finalPrice = $totalPrice > 0 ? $totalPrice : $totalBusinessPrice;
                if ($totalBusinessPrice > 0 && $totalBusinessPrice < $finalPrice) {
                    $finalPrice = $totalBusinessPrice;
                }

                // Update cheapest flight if this one is cheaper
                if ($finalPrice > 0 && $finalPrice < $cheapestPrice) {
                    $cheapestPrice = $finalPrice;
                    $flightArray = $flight->toArray();
                    $flightArray['total_price'] = $finalPrice;
                    $cheapestFlight = $flightArray;
                }
            }

            // Add the cheapest flight for this date to results
            if ($cheapestFlight) {
                $results[] = $cheapestFlight;
            }
        }

        return $results;
    }
}
