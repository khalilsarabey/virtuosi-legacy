<?php

namespace App\Support;

use Illuminate\Http\Request;

class AirlineSupport
{

    public function processArray(object $request): array
    {
        return [
            'flexible_dates' => $request->flexible_dates,
            'itinerary' => [
                'departure' => [
                    'from'  => $request->dep_from,
                    'to'    => $request->dep_to,
                    'date'  => $request->dep_date,
                    'adult' => $request->adult,
                    'child' => $request->child,
                    'infant' => $request->infant,
                ],
                'return' => [
                    'from'  => $request->ret_from ?? null,
                    'to'    => $request->ret_to ?? null,
                    'date'  => $request->ret_date ?? null,
                    'adult' => $request->adult ?? null,
                    'child' => $request->child ?? null,
                    'infant' => $request->infant ?? null,
                ]
            ]
        ];
    }

    public function prepare()
    {
        return [];
    }
}
