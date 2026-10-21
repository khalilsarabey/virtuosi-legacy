<?php

namespace App\Http\Controllers;


use App\Support\AirlineSupport;
use Illuminate\Http\Request;


class AirlineFlights extends Controller
{

    public function index()
    {
    }

    public function search(Request $request)
    {
        $results = [];
        $array = (new AirlineSupport())->processArray($request);
        foreach (config('airlines') as $key => $airline) :
            $results[] = (new $airline['class'])->searchFlights($array);
        endforeach;
        $data = (new AirlineSupport())->prepare($results);
        //return view();
    }
}
