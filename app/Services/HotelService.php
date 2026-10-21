<?php

namespace App\Services;

use App\Services\Local\HotelService as LocalHotelService;

class HotelService
{
    public static function search_hotels($params)
    {
        $destination    = $params['destination'] ?? null;
        $checkIn        = $params['checkIn'] ?? null;
        $checkOut       = $params['checkOut'] ?? null;
        $rooms          = $params['rooms'] ?? null;
        $hotel          = $params['hotel'] ?? null;
        return LocalHotelService::search_hotels($destination, $checkIn, $checkOut, $rooms, $hotel);
    }

    public static function get_hotel_details($params)
    {
        $parameters                 = [];
        $parameters['start_date']   = $params['start_date'] ?? null;
        $parameters['end_date']     = $params['end_date'] ?? null;
        $parameters['rooms']        = $params['rooms'] ?? null;
        $parameters['type']         = $params['type'] ?? null;
        $parameters['rules']        = $params['rules'] ?? null;
        return LocalHotelService::get_hotel_details($params['id'], $parameters);
    }

    public static function get_hotel_rooms($params)
    {
        $parameters                 = [];
        $parameters['start_date']   = $params['start_date'] ?? null;
        $parameters['end_date']     = $params['end_date'] ?? null;
        $parameters['rooms']        = $params['rooms'] ?? null;
        $parameters['type']         = $params['type'] ?? null;
        return LocalHotelService::get_hotel_rooms($params['id'], $parameters);
    }
}
