<?php

namespace App\Http\Livewire\Components;

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Models\Flight;
use Carbon\Carbon;
use Livewire\Component;

class RoundTripGrid extends Component
{
    public $query = [];
    public $skip = 0;
    public $needed_seats = 1;
    public $foundDepartures = [];
    public $foundReturns = [];
    public $foundResultsLowestPrices = [];
    public $dep;
    public $ret;
    public $departuresHeader = [];
    public $grids = [];
    public $show = false;

    public function mount(): void
    {
        $this->query = request()->query();
        $this->foundResultsLowestPrices = $this->getLowestPriceFromResults();
    }

    public function toggle(): void
    {
        $this->show = !$this->show;
        if($this->show)
        {
            $this->init();
        }
    }

    public function render()
    {
        $this->dep = Carbon::createFromFormat('d-m-Y', $this->query['dep_date'])->addDays($this->skip);
        $this->ret = Carbon::createFromFormat('d-m-Y', $this->query['ret_date'])->addDays($this->skip);
        return view('livewire.components.round-trip-grid');
    }

    public function init(): void
    {

        $departureDates = $this->departureDates();
        $returnDates    = $this->returnDates();
        $departures     = [];
        $returns        = [];
        $grid           = [];

        $groupedDepartures  = $departureDates->groupBy('dep_date');
        $groupedReturns     = $returnDates->groupBy('dep_date');

        foreach($groupedDepartures as $groupedDeparture) {
            $cheapest = 0;
            $object = null;
            foreach($groupedDeparture as $gDeparture) {
                $price = getPrice($gDeparture, null, ['adult' => $this->query['adults'] ?? 1, 'child' => $this->query['children'] ?? 0, 'infant' => $this->query['infants'] ?? 0]);
                if(count($price) == 0) {
                    $price = getBusinessPrice($gDeparture, null, ['adult' => $this->query['adults'] ?? 1, 'child' => $this->query['children'] ?? 0, 'infant' => $this->query['infants'] ?? 0]);
                }
                $calculated = $this->calculatePrices($price);
                $currentObjectPrice = $calculated['total'];
                if($cheapest == 0 || $currentObjectPrice < $cheapest) {
                    $cheapest           = $currentObjectPrice;
                    $price['total']    = $currentObjectPrice;
                    $object = $gDeparture;
                }
            }

            $departures[] = [
                'dep_from'                  => $object->dep_from_code,
                'dep_to'                    => $object->dep_to_code,
                'airline_name'              => $object->airline->name,
                'airline_thumb'             => $object->airline->thumbnail,
                'airline_code'              => $object->airline->icao,
                'dep_date'                  => $object->dep_date,
                'price'                     => $cheapest,
                'combinable_supplements'    => $object->combinable_supplements,
                'multi_supplements'         => $object->multi_supplements,
                'oneway_supplements'        => $object->multi_supplements
            ];
        }

        foreach($groupedReturns as $groupedReturn) {
            $cheapest = 0;
            $object = null;
            foreach($groupedReturn as $gReturn) {
                $price = getPrice($gReturn, null, ['adult' => $this->query['adults'] ?? 1, 'child' => $this->query['children'] ?? 0, 'infant' => $this->query['infants'] ?? 0]);
                if(count($price) == 0) {
                    $price = getBusinessPrice($gReturn, null, ['adult' => $this->query['adults'] ?? 1, 'child' => $this->query['children'] ?? 0, 'infant' => $this->query['infants'] ?? 0]);
                }
                $calculated = $this->calculatePrices($price, false);
                $currentObjectPrice = $calculated['total'];
                if($cheapest == 0 || $currentObjectPrice < $cheapest) {
                    $cheapest           = $currentObjectPrice;
                    $price['total']    = $currentObjectPrice;
                    $object             = $gReturn;
                }
            }
            $returns[] = [
                'dep_from'                  => $object->dep_from_code,
                'dep_to'                    => $object->dep_to_code,
                'airline_name'              => $object->airline->name,
                'airline_thumb'             => $object->airline->thumbnail,
                'airline_code'              => $object->airline->icao,
                'dep_date'                  => $object->dep_date,
                'price'                     => $cheapest,
                'combinable_supplements'    => $object->combinable_supplements,
                'multi_supplements'         => $object->multi_supplements,
                'oneway_supplements'        => $object->multi_supplements
            ];
        }

        // Limit both arrays to 7 elements
        $departures = array_slice($departures, 0, 7);
        $returns = array_slice($returns, 0, 7);

        foreach($returns as $return) {

            foreach($departures as $departure) {
                if (Carbon::parse($return['dep_date'])->gt(Carbon::parse($departure['dep_date']))) {
                    $dep_price = $departure['price'];
                    $ret_price = $return['price'];
                    $prices = ['adult' => $this->query['adults'] ?? 1, 'child' => $this->query['children'] ?? 0, 'infant' => $this->query['infants'] ?? 0, 'total' => $dep_price + $ret_price];
                    if($departure['airline_code'] != $return['airline_code']) {
                        $departureSupp  = $departure['combinable_supplements'] * ( ($this->query['adults'] ?? 1) + ($this->query['children'] ?? 0) );
                        $returnSupp     = $return['combinable_supplements'] * ( ($this->query['adults'] ?? 1) + ($this->query['children'] ?? 0) );
                        $prices['total'] = $prices['total'] + $departureSupp + $returnSupp;
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

        //Call the API to get the prices
//        if(count($grid) == 0) {
//            $grid = ApiClientSearchController::getLowestPrice([
//                'query'         => $this->query,
//                'needed_seats'  => $this->needed_seats,
//                'departures'    => $departures,
//                'returns'       => $returns,
//                'skip'          => $this->skip
//            ]);
//            $this->departuresHeader = $grid['data']['departuresHeader'] ?? [];
//            $grid = $grid['data']['grids'] ?? [];
//        }
        $this->grids = $grid ?? [];
    }

    protected function departureDates()
    {
        $start_date = $this->dep->format('Y-m-d');
        $end_date = $this->dep->copy()->addDays(30)->format('Y-m-d');
        $flights = Flight::restrict($this->needed_seats);
        $flights->whereHas('depFrom', function($q) {
            $q->where('city_code', $this->query['dep_from_code']);
        });
        $flights->whereHas('depTo', function($q) {
            $q->where('city_code', $this->query['dep_to_code']);
        });
        $flights->where('type', 'oneway');
        $flights->whereBetween('dep_date', [$start_date, $end_date]);
        $flights->orderBy('dep_date');
        $flights->orderBy('dep_time');
        $flights->with(['airline', 'prices']);
        $flights = $flights->get();
        $this->departuresHeader = $flights->unique('dep_date')->take(7)->pluck('dep_date');
        return $flights;
    }

    protected function returnDates()
    {
        $start_date = $this->ret->format('Y-m-d');
        $end_date = $this->ret->copy()->addDays(30)->format('Y-m-d');
        $return = Flight::restrict($this->needed_seats);
        $return->whereHas('depFrom', function($q) {
            $q->where('city_code', $this->query['dep_to_code']);
        });
        $return->whereHas('depTo', function($q) {
            $q->where('city_code', $this->query['dep_from_code']);
        });
        $return->where('type', 'oneway');
        $return->whereBetween('dep_date', [$start_date, $end_date]);
        $return->orderBy('dep_date');
        $return->orderBy('dep_time');
        $return->with(['airline', 'prices']);
        return $return->get();
    }

    private function calculatePrices($priceArray, $includeInfant = true)
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

    private function getLowestPriceFromResults(): array
    {
        $lowestTotal = null;
        $bestCombo = [
            'lowestDeparture' => 0,
            'lowestReturn' => 0,
            'lowestDepartureAirline' => '',
            'lowestReturnAirline' => '',
            'lowestDepartureSupplements' => 0,
            'lowestReturnSupplements' => 0,
            'total' => 0,
        ];

        $query = request()->query();
        $adults = $this->query['adults'] ?? 1;
        $children = $this->query['children'] ?? 0;

        foreach ($this->foundDepartures as $departure) {
            $depPrices = getPrice($departure, null, ['adult' => $adults, 'infant' => $query['infants'] ?? 0, 'child' => $children]);
            if (count($depPrices) == 0) {
                $depPrices = getBusinessPrice($departure, null, ['adult' => $adults, 'infant' => $query['infants'] ?? 0, 'child' => $children]);
            }
            $depTotal = 0;
            if ($adults > 0) {
                foreach ($depPrices['adult'] ?? [] as $adult) {
                    $depTotal += $adult['number'] * $adult['price'];
                }
            }
            if ($children > 0) {
                foreach ($depPrices['child'] ?? [] as $child) {
                    $depTotal += $child['number'] * $child['price'];
                }
            }
            if (($query['infants'] ?? 0) > 0) {
                foreach ($depPrices['infant'] ?? [] as $infant) {
                    $depTotal += $infant['number'] * $infant['price'];
                }
            }

            foreach ($this->foundReturns as $return) {
                $retPrices = getPrice($return, null, ['adult' => $adults, 'infant' => $query['infants'] ?? 0, 'child' => $children]);
                if (count($retPrices) == 0) {
                    $retPrices = getBusinessPrice($return, null, ['adult' => $adults, 'infant' => $query['infants'] ?? 0, 'child' => $children]);
                }
                $retTotal = 0;

                if ($adults > 0) {
                    foreach ($retPrices['adult'] ?? [] as $adult) {
                        $retTotal += $adult['number'] * $adult['price'];
                    }
                }

                if ($children > 0) {
                    foreach ($retPrices['child'] ?? [] as $child) {
                        $retTotal += $child['number'] * $child['price'];
                    }
                }

                if (($query['infants'] ?? 0) > 0) {
                    foreach ($retPrices['infant'] ?? [] as $infant) {
                        $retTotal += $infant['number'] * $infant['price'];
                    }
                }

                $depAirline = $departure['airline']['icao'] ?? '';
                $retAirline = $return['airline']['icao'] ?? '';
                $depSupp = is_numeric($departure['combinable_supplements']) ? ($departure['combinable_supplements'] * ($adults + $children)) : 0;
                $retSupp = is_numeric($return['combinable_supplements']) ? ($return['combinable_supplements'] * ($adults + $children)) : 0;
                $supplements = 0;

                if ($depAirline !== '' && $retAirline !== '' && $depAirline !== $retAirline) {
                    $supplements = $depSupp + $retSupp;
                }
                $total = $depTotal + $retTotal + $supplements;

                if ($lowestTotal === null || $total < $lowestTotal) {
                    $lowestTotal = $total;
                    $bestCombo = [
                        'lowestDeparture' => $depTotal,
                        'lowestReturn' => $retTotal,
                        'lowestDepartureAirline' => $depAirline,
                        'lowestReturnAirline' => $retAirline,
                        'lowestDepartureSupplements' => $depSupp,
                        'lowestReturnSupplements' => $retSupp,
                        'total' => $total,
                    ];
                }
            }
        }
        return $bestCombo;
    }

    public function next(): void
    {
        $this->skip = $this->skip + 1;
        $this->init();
    }

    public function previous(): void
    {
        $this->skip = $this->skip - 1;
        $this->init();
    }
}
