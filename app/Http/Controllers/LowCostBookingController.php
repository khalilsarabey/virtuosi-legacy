<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LowCostBookingController extends Controller
{
    public function booking(Request $request)
    {
        $data = $request->except('_token');
        return view('frontend.low-cost-booking', ['data' => $data, 'airline' => strtolower(preg_replace('/(?<=[a-z])([A-Z]+)/','-$1', $data['airline']))]);
    }

    public function summary(Request $request)
    {
        $data = $request->except('_token');
        return view('frontend.low-cost-booking-summary', ['data' => $data, 'airline' => strtolower(preg_replace('/(?<=[a-z])([A-Z]+)/','-$1', $data['airline']))]);
    }

    public function confirm(Request $request)
    {
        $data = $request->except('_token');
        return view('frontend.low-cost-booking-confirm', ['data' => $data, 'airline' => strtolower(preg_replace('/(?<=[a-z])([A-Z]+)/','-$1', $data['airline']))]);
    }

    public function seats(Request $request)
    {
        $data = $request->except('_token');
        if(isset($data['airline'])) {
            return view('frontend.low-cost-booking-seats', ['data' => $data, 'airline' => strtolower(preg_replace('/(?<=[a-z])([A-Z]+)/','-$1', $data['airline']))]);
        }
        return view('frontend.low-cost-booking-seats');
    }

    public function pnr(Request $request)
    {
        $data = $request->except('_token');
        if(isset($data['airline'])) {
            return view('frontend.low-cost-booking-pnr', ['data' => $data, 'airline' => strtolower(preg_replace('/(?<=[a-z])([A-Z]+)/','-$1', $data['airline']))]);
        }
        return view('frontend.low-cost-booking-pnr');
    }

    public function retrieveAndPay(Request $request)
    {
        $data = $request->except('_token');
        $airline = $data["airline"];
        if($airline){
            $airlines = config('airlines');
            $resl = (new $airlines[$airline]['class'])->retrieveAndPay($data);
            if($resl["success"]){
                return redirect()->back();
            }else if(isset($resl["error"]) && count($resl["error"]) > 0){
                return redirect()->back()->withErrors($resl["error"]);
            }
        }
        return redirect()->back();
    }
}
