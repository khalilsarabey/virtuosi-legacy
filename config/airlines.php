<?php

$data['FlyDubai'] = [
    "client_id"             => env('FLY_DUBAI_ID', 'SAMADTOURS_FZ_NP'),
    "client_secret"         => env('FLY_DUBAI_SECRET', 'AB509898E0C31A64035BE87196E4D48F'),
    "username"              => env('FLY_DUBAI_USERNAME', 'apisamad'),
    "password"              => env('FLY_DUBAI_PASSWORD', 'Qiertnj@402'),
    "IATA"                  => env('FLY_DUBAI_IATA', '95151225'),
    "currency"              => "USD",
    'agency'                => '',
    'class'                 => \App\Services\Airlines\FlyDubai\FlyDubaiAirline::class,
    "urls" => [
        "authenticate"      => env('FLY_DUBAI_AUTHENTICATE_URL', 'https://devapi.flydubai.com/res/v3/authenticate'),
        "search_flights"    => env('FLY_DUBAI_SEARCH_URL', 'https://devapi.flydubai.com/res/v3/pricing/flightswithfares'),
        "add_to_cart"       => env('FLY_DUBAI_ADD_TO_CART_URL', 'https://devapi.flydubai.com/res/v3/order/cart'),
        "seat_map"          => env('FLY_DUBAI_SEAT_MAP_URL', 'https://devapi.flydubai.com/res/v3/pricing/seats'),
        "ancillaries"       => env('FLY_DUBAI_ANCILLARIES_URL', 'https://devapi.flydubai.com/res/v3/pricing/ancillary'),
        "summary_pnr"       => env('FLY_DUBAI_SUMMARY_PNR_URL', 'https://devapi.flydubai.com/res/v3/cp/summaryPNR'),
        "commit_pnr"        => env('FLY_DUBAI_COMMIT_PNR_URL', 'https://devapi.flydubai.com/res/v3/cp/commitPNR'),
        "fop"               => env('FLY_DUBAI_FOP_URL', 'https://devapi.flydubai.com/res/v3/order/payment/processFOP'),
        "retrieve_pnr"      => env('FLY_DUBAI_RETRIEVE_PNR_URL', 'https://devapi.flydubai.com/res/v3/cp/RetrievePNR'),
    ]
];

if(env('SAAS_ID') == 'samadonline'):
    $data['AirArabia'] = [
    'login'                  => env('AIR_ARABIA_LOGIN', 'ABYSAMADTOURSONEAPI'),
    'username'               => env('AIR_ARABIA_USERNAME', 'SAMADTOURSONEAPI'),
    'password'               => env('AIR_ARABIA_PASSWORD', 'Pass@1234'),
    'agent_code'             => env('AIR_ARABIA_AGENT_CODE', 'AAASHJ0205'),
    'agent_code_all_requests' => env('AIR_ARABIA_AGENT_CODE_ALL_REQUESTS', 'AAASHJ0205'),
    "currency"               => "USD",
    'agency'                 => '',
    'class'                  => \App\Services\Airlines\AirArabia\AirArabia::class,
    'headers'                => [
        'X-AERO-SALES-CHANNEL'      => "OTA",
        'X-AERO-JOURNEY-TYPE'       => "RETURN",
        'X-AERO-USERID'             => "ABYSAMADAPI",
        'X-AERO-AGENT-CODE'         => "ABYBEY2030",
    ],
    "urls" => [
        "authenticate"       => env('AIR_ARABIA_AUTHENTICATE', 'https://aero-suite-stage4-airarabia.isaaviation.net/api/auth/authenticate'),
        "search_flights"     => env('AIR_ARABIA_SEARCH_FLIGHTS', 'https://aero-search-api-service-stage4-airarabia.isaaviation.net/aerosearch.FlightService/findOndWiseFlightCombinations'),
        "all_requests"       => env('AIR_ARABIA_ALL_REQUESTS', 'https://g914.isaaviations.com/webservices/services/AAResWebServices'),
    ]
];
endif;

if(env('SAAS_ID') == 'tourdumonde'):
    $data['AirArabia'] = [
        'login'                  => env('AIR_ARABIA_LOGIN', 'ABYTOURDUMONDEAPI'),
        'username'               => env('AIR_ARABIA_USERNAME', 'TOURDUMONDEAPI'),
        'password'               => env('AIR_ARABIA_PASSWORD', 'P@ss1234'),
        'agent_code'             => env('AIR_ARABIA_AGENT_CODE', 'BEY2500'),
        'agent_code_all_requests' => env('AIR_ARABIA_AGENT_CODE_ALL_REQUESTS', 'BEY2500'),
        "currency"               => "USD",
        'agency'                 => '',
        'class'                  => \App\Services\Airlines\AirArabia\AirArabia::class,
        'headers'                => [
            'X-AERO-SALES-CHANNEL'      => "OTA",
            'X-AERO-JOURNEY-TYPE'       => "RETURN",
            'X-AERO-USERID'             => "ABYTOURDUMONDEAPI",
            'X-AERO-AGENT-CODE'         => "ABYBEY2500",
        ],
        "urls" => [
            "authenticate"       => env('AIR_ARABIA_AUTHENTICATE', 'https://aero-suite-prod-airarabia.accelaero.com/api/auth/authenticate'),
            "search_flights"     => env('AIR_ARABIA_SEARCH_FLIGHTS', 'https://aero-search-external-api-service-prod-airarabia.accelaero.com/aerosearch.FlightService/findOndWiseFlightCombinations'),
            "all_requests"       => env('AIR_ARABIA_ALL_REQUESTS', 'https://reservations.airarabia.com/webservices/services/AAResWebServices'),
        ]
    ];
endif;

/*
    'AirArabia' => [
        'login'                  => 'ABYSAMADTOURSONEAPI',
        'username'               => 'SAMADTOURSONEAPI',
        'password'               => 'Pass@1234',
        'agent_code'             => 'AAASHJ0205',
        'agent_code_all_requests' => 'AAASHJ0205',
        "currency"               => "USD",
        'agency'                 => '',
        'class'                  => \App\Services\Airlines\AirArabia\AirArabia::class,
        'headers'                => [
            'X-AERO-SALES-CHANNEL'      => "OTA",
            'X-AERO-JOURNEY-TYPE'       => "RETURN",
            'X-AERO-USERID'             => "ABYSAMADAPI",
            'X-AERO-AGENT-CODE'         => "ABYBEY2030",
        ],
        "urls" => [
            "authenticate"       => 'https://aero-suite-stage4-airarabia.isaaviation.net/api/auth/authenticate',
            "search_flights"     => 'https://aero-search-api-service-stage4-airarabia.isaaviation.net/aerosearch.FlightService/findOndWiseFlightCombinations',
            "all_requests"       => 'https://g914.isaaviations.com/webservices/services/AAResWebServices',
        ]
    ]
*/
return $data;
