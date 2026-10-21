<?php

namespace App\Http\Controllers;

use App\Services\Sabre\SabreService;
use Illuminate\Http\Request;

class SabreController extends Controller
{
    public function index()
    {
        return view('sabre');
    }

    public function search(Request $request, SabreService $sabreService)
    {
        $params = [
            'origin' => $request->input('origin'),
            'destination' => $request->input('destination'),
            'departuredate' => $request->input('departure_date')
        ];

        $results = $sabreService->searchFlights($params);

        return view('sabre-search', [
            'results' => $results
        ]);
    }
}
