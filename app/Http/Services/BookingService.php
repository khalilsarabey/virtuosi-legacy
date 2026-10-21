<?php

namespace App\Http\Services;

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Models\Flight;
use Illuminate\Support\Str;

class BookingService
{
    private $adults         = 0;
    private $infants        = 0;
    private $children       = 0;
    private $total          = 0;
    private $supplements    = 0;
    private $ret_class      = null;
    private $dep_class      = null;
    private $dep_adult      = 0;
    private $dep_infant     = 0;
    private $dep_child      = 0;
    private $is_restricted  = false;

    private $supplements_breakdown = [];

    public function calculate($type = null, Array $flights = [], Array $passengers = [], $isApi = false, $package = false)
    {
        if(!$type || !count($flights) || !count($passengers)) return false;
        $departure_cabin = $flights['departure_cabin'] ?? 'economy';
        $return_cabin = $flights['return_cabin'] ?? 'economy';

        if(!$isApi) {
            $departure = Flight::restrict()->with(['prices', 'airline'])->find($flights['departure']);
        } else {
            if(is_array($flights['apiDeparture'])) {
                $departure = !empty($flights['apiDeparture']) ? arrayToObject($flights['apiDeparture']) : null;
            } else {
                if(is_array($flights['apiDeparture']) && !empty($flights['apiDeparture'])) {
                    $departure = arrayToObject($flights['apiDeparture']);
                } elseif (is_numeric($flights['apiDeparture']) && $flights['apiDeparture'] > 0) {
                    $departureQuery = ApiClientSearchController::getFlights([
                        'adults'    => $passengers['adults'] ?? 0,
                        'children'  => $passengers['children'] ?? 0,
                        'infants'   => $passengers['infants'] ?? 0,
                        'id'        => $flights['apiDeparture'],
                        'api'       => $isApi
                    ]);
                    $departure = arrayToObject($departureQuery['data'][0] ?? []);
                } else {
                    $departure = null;
                }
            }
        }

        if(auth()->user()->restricted == 1 || $departure?->onhold_bookings == 1) $this->is_restricted = true;

        if($departure_cabin == 'economy') {
            $prices  = getPrice($departure, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']], $package);
        }

        if($departure_cabin == 'business') {
            $prices  = getBusinessPrice($departure, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']], $package);
        }

        $classes = [];

        foreach($prices as $key => $price):
            $k = Str::plural($key);
            foreach($price as $v):
                $this->{$k} += ($v['price'] * $v['number']);
                $classes[] = $v['class'];
            endforeach;
        endforeach;

        $this->dep_class     = implode(",", $classes);

        if($type == 'roundtrip' || $type == 'multi')
        {
            if(!$isApi) {
                $return = Flight::restrict()->with(['prices', 'airline'])->find($flights['return']);
            } else {
                if(is_array($flights['apiReturn']) && !empty($flights['apiReturn'])) {
                    $return = arrayToObject($flights['apiReturn']);
                } elseif (is_numeric($flights['apiReturn']) && $flights['apiReturn'] > 0) {
                    $returnQuery = ApiClientSearchController::getFlights([
                        'adults'    => $passengers['adults'] ?? 0,
                        'children'  => $passengers['children'] ?? 0,
                        'infants'   => $passengers['infants'] ?? 0,
                        'id'        => $flights['apiReturn'],
                        'api'       => $isApi
                    ]);
                    $return = arrayToObject($returnQuery['data'][0] ?? []);
                } else {
                    $return = null;
                }
            }

            if($return) {

                if($return->onhold_bookings == 1) $this->is_restricted = true;

                if($return_cabin == 'economy') {
                    $prices  = getPrice($return, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']], $package);
                }

                if($return_cabin == 'business') {
                    $prices  = getBusinessPrice($return, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']],$package);
                }

                $classes = [];
                foreach($prices as $key => $price):
                    $k = Str::plural($key);
                    foreach($price as $v):
                        $this->{$k} += ($v['price'] * $v['number']);
                        $classes[] = $v['class'];
                    endforeach;
                endforeach;
                $this->ret_class = implode(",", $classes);
            }
        }

        if($type == 'multi')
        {
            $this->supplements_breakdown['multi'] = ($departure->multi_supplements * $passengers['adults']) + $departure->multi_supplements * $passengers['children'];
            $this->supplements += $departure->multi_supplements * $passengers['adults'];
            $this->supplements += $departure->multi_supplements * $passengers['children'];
            if(isset($return)) {
                $this->supplements_breakdown['multi'] = ($return->multi_supplements * $passengers['adults']) + $return->multi_supplements * $passengers['children'];
                $this->supplements += $return->multi_supplements * $passengers['adults'];
                $this->supplements += $return->multi_supplements * $passengers['children'];
            }
        }

        if($type == 'oneway')
        {
            $this->supplements_breakdown['oneway'] = ($departure->oneway_supplements * $passengers['adults']) + $departure->oneway_supplements * $passengers['children'];
            $this->supplements += $departure->oneway_supplements * $passengers['adults'];
            $this->supplements += $departure->oneway_supplements * $passengers['children'];
        }

        if($type == 'roundtrip' || $type == 'multi')
        {
            if(isset($departure) && isset($return))
            {
                if($departure->airline->icao != $return->airline->icao)
                {
                    $this->supplements_breakdown['combinable'] = $departure->combinable_supplements * $passengers['adults'] +  $departure->combinable_supplements * $passengers['children'] + $return->combinable_supplements * $passengers['adults'] + $return->combinable_supplements * $passengers['children'];
                    $this->supplements  = 0;
                    $this->supplements += $departure->combinable_supplements * $passengers['adults'];
                    $this->supplements += $departure->combinable_supplements * $passengers['children'];
                    $this->supplements += $return->combinable_supplements * $passengers['adults'];
                    $this->supplements += $return->combinable_supplements * $passengers['children'];
                }
            }
        }

        if($type == 'fixed') {
            $this->ret_class = getPrice($departure, 'class');
        }

        $this->total = $this->adults + $this->children + $this->infants + $this->supplements;

        return [
            'dep_class'     => $this->dep_class,
            'ret_class'     => $this->ret_class,
            'departure'     => $departure,
            'return'        => $return ?? null,
            'departure_cabin' => $departure_cabin,
            'return_cabin'  => ($return ?? null) ? $return_cabin : null,
            'total'         => $this->total,
            'supplements'   => $this->supplements,
            'supplements_breakdown' => $this->supplements_breakdown,
            'adults'        => $this->adults,
            'children'      => $this->children,
            'infants'       => $this->infants,
            'dep_adult'     => $this->dep_adult,
            'dep_infant'    => $this->dep_infant,
            'dep_child'     => $this->dep_child,
            'restricted'    => $this->is_restricted,
            'prices'        => []
        ];
    }

    public function book($query)
    {
        $isApi  = isset($query['api']) && is_numeric($query['api']) && $query['api'] > 0 ? $query['api'] : null;;
        $type   = $query['type'];
        $departure_cabin = $query['dep_cabin'];
        $return_cabin = $query['ret_cabin'];
        $flights = [
            $isApi ? 'apiDeparture' : 'departure'       => $query['departure'],
            $isApi ? 'apiReturn'    : 'return'          => $query['return'] ?? null,
            'departure_cabin' => $departure_cabin,
            'return_cabin' => $return_cabin,
        ];
        $passengers = [
            'adults' => 0,
            'infants' => 0,
            'children' => 0,
        ];
        foreach($query['reservations'] as $q):
            if($q['title'] == 'Infant.') {
                $passengers['infants'] += 1;
            } else if ($q['title'] == 'Child.') {
                $passengers['children'] += 1;
            } else {
                $passengers['adults'] += 1;
            }
        endforeach;
        return $this->calculate($type, $flights, $passengers, $isApi);
    }

}
