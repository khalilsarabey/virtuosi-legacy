<?php

namespace App\Http\Controllers\Connections\Client;

class ApiClientSearchController extends BaseConnection
{
    public static function getSearchLocations($type = 'oneway', $query = [])
    {
        $results = parent::performOnAllClients('get', 'search/locations', $query);
        $response = [];
        $blockedConnections = self::getBlocked();
        foreach ($results as $source => $result) {
            if (in_array($source, $blockedConnections)) {
                continue; // Skip blocked connections
            }
            foreach($result->json() as $key => $value) {
                $response = array_merge($value ?? [], $response);
            }
        }
        return $response;
    }

    public static function searchFlights($request)
    {
        $results = parent::performOnAllClients('get', 'search/flights', $request);
        $response = [];
        $blockedConnections = self::getBlocked();
        //New code
        foreach ($results as $source => $result) {
            if (in_array($source, $blockedConnections)) {
                continue; // Skip blocked connections
            }
            foreach($result->json() as $key => $value) {
                $response[] = [
                    'source' => $source,
                    $key => $value
                ];
            }
        }
        return $response;
    }

    public static function searchHotels($request)
    {
        $response = [];
        $blockedConnections = self::getBlocked();
        if(!empty($request['api'])) {
            $results = parent::performOnOneClients('get', 'search/hotels', $request);
            foreach ($results as $source => $result) {
                if (in_array($source, $blockedConnections)) {
                    continue;
                }
                foreach($result->json() as $key => $value) {
                    $response['source'] = $source;
                    $response[$key] = $value;
                }
            }
        } else {
            $results = parent::performOnAllClients('get', 'search/hotels', $request);
            foreach ($results as $source => $result) {
                if (in_array($source, $blockedConnections)) {
                    continue; // Skip blocked connections
                }
                foreach($result->json() as $key => $value) {
                    $response['source'] = $source;
                    $response[$key] = $value;
                }
            }
        }
        return $response;
    }

    public static function getHotelDetails($params = [])
    {
        $results = parent::performOnOneClients('get', 'hotel', $params);
        $response = [];
        $blockedConnections = self::getBlocked();
        foreach ($results as $source => $result) {
            if (in_array($source, $blockedConnections)) {
                continue; // Skip blocked connections
            }
            foreach($result->json() as $key => $value) {
                $response['source'] = $source;
                $response[$key] = $value;
            }
        }
        return $response;
    }

    public static function getFlights($request)
    {
        $blockedConnections = self::getBlocked();
        $results = parent::performOnOneClients('get', 'flights', $request);
        $response = [];
        foreach ($results as $source => $result) {
            if (in_array($source, $blockedConnections)) {
                continue; // Skip blocked connections
            }
            foreach($result->json() as $key => $value) {
                $response['source'] = $source;
                $response[$key] = $value;
            }
        }
        return $response;
    }

    public static function getLowestPrice($request)
    {
        $response = [];
        $blockedConnections = self::getBlocked();
        $results = parent::performOnAllClients('get', 'search/grid', $request);
        foreach ($results as $source => $result) {
            foreach($result->json() as $key => $value) {
                if (in_array($source, $blockedConnections)) {
                    continue; // Skip blocked connections
                }
                if(count($value['grids'] ?? [])) {
                    $response['source'] = $source;
                    $response[$key] = $value;
                }
            }
        }
        return $response;
    }

    public static function getFlexibleDates($request)
    {
        $response = [];
        $blockedConnections = self::getBlocked();
        $results = parent::performOnAllClients('get', 'search/flexible', $request);
        foreach ($results as $source => $result) {
            if (in_array($source, $blockedConnections)) {
                continue; // Skip blocked connections
            }
            foreach($result->json() as $key => $value) {
                $response['source'] = $source;
                $response[$key] = $value;
            }
        }
        return $response;
    }

    private static function getBlocked()
    {
        $blockedConnections = [];
        if (auth()->check() && !empty(auth()->user()->blocked_connections)) {
            // Support both array and comma-separated string storage
            $blockedConnections = is_array(auth()->user()->blocked_connections)
                ? auth()->user()->blocked_connections
                : explode(',', auth()->user()->blocked_connections);
            $blockedConnections = array_map('trim', $blockedConnections);
        }
        return $blockedConnections;
    }
}
