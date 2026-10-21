<?php

namespace App\Interfaces;

interface HotelServiceInterface
{
    public static function get_hotels_locations($type, $query = []);
    public static function search_hotels($destination, $checkIn, $checkOut, $rooms);

    public static function get_hotel_details($id, $params = []);
}
