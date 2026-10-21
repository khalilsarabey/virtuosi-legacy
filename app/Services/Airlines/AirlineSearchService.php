<?php

namespace App\Services\Airlines;

use App\Services\Airlines\AirArabia\AirArabia;

class AirlineSearchService
{

    public function process($params)
    {
        $results = [];
        $airlines = config('airlines');
        foreach($airlines as $airline => $value):
            $results[$airline] = (new $value['class'])->searchFlights($params);
        endforeach;
        return $results;
    }
}
