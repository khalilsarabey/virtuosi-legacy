<?php

namespace App\Http\Controllers\Connections\Client;

class ApiClientBookingController extends BaseConnection
{

    public static function getBookingUpdate($request)
    {
        $results = parent::performOnOneClients('get', 'get_booking_update', $request);
        $response = [];
        foreach ($results as $source => $result) {
            foreach($result->json() as $key => $value) {
                $response['source'] = $source;
                $response[$key] = $value;
            }
        }
        return $response;
    }

    public static function sendBookingRequest($request)
    {
        $results = parent::performOnOneClients('get', 'book', $request);
        $response = [];
        foreach ($results as $source => $result) {
            foreach($result->json() as $key => $value) {
                $response['source'] = $source;
                $response[$key] = $value;
            }
        }
        return $response;
    }
}
