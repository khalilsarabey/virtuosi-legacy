<?php

namespace App\Providers;

use Barryvdh\Debugbar\ServiceProvider;

class AirlineServiceProvider extends ServiceProvider
{
    public function register()
    {
        $airlines = config('airlines');

        foreach($airlines as $airlineName => $airlineConfig):
            $this->app->bind($airlineName, function($app) use ($airlineConfig) {
                return new $airlineConfig['class']();
            });
        endforeach;
    }
}
