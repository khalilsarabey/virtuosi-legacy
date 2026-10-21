<?php

namespace App\Http\Controllers\Connections\Client;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class BaseConnection
{

    protected static function performOnAllClients($method = 'get', $action = '', $data = [], $headers = [], $includeBlocked = false)
    {
        $response = Http::pool(function (Pool $pool) use ($includeBlocked, $method, $action, $headers, $data) {
            foreach (connections() as $connection) {
                $pool->as($connection->id)
                    ->withToken($connection->token)
                    ->withHeaders(array_merge($headers, [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ]))
                    ->{$method}($connection->host . 'api/v1/' . $action, $data);
            }
        });
        return $response;
    }

    protected static function performOnOneClients($method = 'get', $action = '', $data = [], $headers = [], $includeBlocked = false)
    {
        $response = Http::pool(function (Pool $pool) use ($includeBlocked, $method, $action, $headers, $data) {
            foreach (connections()->where('id', $data['api']) as $connection) {
                $pool->as($connection->id)
                    ->withToken($connection->token)
                    ->withHeaders(array_merge($headers, [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ]))
                    ->{$method}($connection->host . 'api/v1/' . $action, $data);
            }
        });
        return $response;
    }
}
