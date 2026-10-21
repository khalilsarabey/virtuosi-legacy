<?php

namespace App\Services;

use App\Interfaces\FlightServiceInterface;
use App\Models\Flight;
use App\Services\Local\FlightService as LocalFlightService;
use App\Models\Location;
use Carbon\Carbon;

class FlightService implements FlightServiceInterface
{

    public static function get_flights_locations($type, $query = [])
    {
        return LocalFlightService::get_flights_locations($type, $query);
    }

    public static function get_flights_dates($type, $query = [])
    {
        return LocalFlightService::get_flights_locations($type, $query);
    }

    public static function search_flights($params)
    {
        return LocalFlightService::search_flights($params);
    }

    public static function get_flights($params)
    {
        return LocalFlightService::get_flights($params);
    }

    public static function store_booking($params)
    {
        return LocalFlightService::store_booking($params);
    }

    public static function flexible_dates($params = [])
    {
        return LocalFlightService::flexible_dates($params);
    }

    public static function grid($params = [])
    {
        return LocalFlightService::grid($params);
    }
}
