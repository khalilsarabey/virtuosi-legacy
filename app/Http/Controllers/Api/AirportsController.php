<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportsController extends Controller
{

    public function index()
    {
        $locations = Airport::all();
        return response()->json($locations);
    }

    public function search(Request $request)
    {
        $locations = Airport::where('city', 'like', '%' . $request->city . '%')
            ->orWhere('airport', 'like', '%' . $request->city . '%')
            ->orWhere('airport_code', 'like', '%' . $request->city . '%')
            ->orWhere('country', 'like', '%' . $request->city . '%')
            ->get();
        return response()->json($locations);
    }
}
