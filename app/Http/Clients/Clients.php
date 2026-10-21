<?php

namespace App\Http\Clients;

class Clients
{
    public static function connect($params)
    {
        if($params['type'] == 'local') {
            unset($params['type']);
            return LocalClient::connect($params);
        }
    }
}
