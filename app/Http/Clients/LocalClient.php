<?php

namespace App\Http\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class LocalClient
{
    public static function connect($params)
    {
        try {
            $client = new Client([
                'base_uri' => $params['host'],
            ]);
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];
            $options = [
                'form_params' => [
                    'email'     => $params['email'],
                    'password'  => $params['password']
                ]];
            $request = new Request('POST',  'api/login', $headers);
            $res = $client->sendAsync($request, $options)->wait();
            if (200 == $res->getStatusCode()) {
                $response = $res->getBody();
                return $response;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
