<?php

return [
    'low-cost' => env('ALLOW_LOW_COST', false),
    'white-label' => env('ALLOW_WHITELABEL', false),
    'auto-pricing' => env('ALLOW_AUTO_PRICING', false),
    'hotels' => env('ALLOW_HOTELS', false),
    'packages' => env('ALLOW_PACKAGES', false),
    'charter' => env('ALLOW_CHARTER', false),
    'api' => env('ALLOW_API', false),
];
