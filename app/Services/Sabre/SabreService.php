<?php

namespace App\Services\Sabre;

use GuzzleHttp\Client;

class SabreService
{
    private $client;
    private $accessToken;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('SABRE_API_BASE_URL')
        ]);
    }

    public function searchFlights($params)
    {
        $this->getToken();

        $response = $this->client->request('GET', '/v2/shop/flights', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ],
            'query' => $params
        ]);

        return json_decode($response->getBody()->getContents());
    }

    private function getToken()
    {
        if (!$this->accessToken) {
            $response = $this->client->request('POST', env('SABRE_ACCESS_TOKEN_URL'), [
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ],
                'auth' => [
                    (string) env('SABRE_CLIENT_ID'),
                    (string) env('SABRE_CLIENT_SECRET')
                ]
            ]);

            $body = json_decode($response->getBody()->getContents());

            $this->accessToken = $body->access_token;
        }
    }
}
