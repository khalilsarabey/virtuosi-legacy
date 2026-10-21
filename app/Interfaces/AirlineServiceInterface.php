<?php

namespace App\Interfaces;

interface AirlineServiceInterface
{

    public function authenticate();

    public function searchFlights($params);

    public function getFlightFareTypes($params);

    public function confirmationStep($params);

    public function processBookingStep($params, $payment);

    public function bookFlight($params);

    public function getPrice($params);

    public function getExtras($params);

    public function getMealService($params);

    public function getBaggageService($params);
}
