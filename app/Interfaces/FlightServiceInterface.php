<?php

namespace App\Interfaces;

interface FlightServiceInterface
{
    public static function get_flights_locations($type, $query = []);
    public static function get_flights_dates($type, $query = []);
    public static function search_flights($params);
}
