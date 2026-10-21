<?php

namespace App\Services\Airlines\AirArabia;

use App\Interfaces\AirlineServiceInterface;
use App\Mail\NewAirlineBookingEmail;
use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Storage;
use App\Models\AirlineBooking;
use Mail;

class AirArabia implements AirlineServiceInterface
{
    public function authenticate()
    {
        $auth = Http::withBody(
            '{
                "login":"' . config("airlines.AirArabia.login") . '",
                "password":"' . config("airlines.AirArabia.password") . '"
            }',
            'text/plain'
        )->post(config("airlines.AirArabia.urls.authenticate"));

        if ($auth->successful()) {
            if ($json_auth = $auth->json("tokenPair")) {
                return $json_auth["accessToken"];
            }
        }

        return false;
    } //authenticate


    public function searchFlights($params)
    {
        $session_timestamp = time();
        $direction = "ONEWAY";
        if (isset($params["itinerary"]["return"]["from"]) && $params["itinerary"]["return"]["from"] != null) $direction = "RETURN";
        $params["flexible_dates"] = false;
        $params["flexibility_days"] = 1;
        $jsonRequest = AirArabiaSupport::generateSearchRequest($params);
        if ($token = self::authenticate()) {
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Search_req.xml", json_encode($jsonRequest));
            $response = Http::asJson()
                ->withToken($token)
                ->withHeaders([
                    'X-AERO-SALES-CHANNEL'      => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                    "X-AERO-JOURNEY-TYPE"       => $direction,
                    "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                    "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                ])
                ->post("https://aero-search-external-api-service-prod-airarabia.accelaero.com/aerosearch.FlightService/findOndWiseFlightCombinations", $jsonRequest);
            //->post(config("airlines.AirArabia.urls.search_flights"), $jsonRequest);
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Search_res.xml", $response);
            if ($response->successful()) {
                $adult_count = $params["itinerary"]["departure"]["adult"];
                $child_count = $params["itinerary"]["departure"]["child"];
                $search_results = AirArabiaSupport::processSearchResults($response->json(), $adult_count, $child_count);
                $params_pool = [];
                $params2 = [];
                $params2["is_return"] = $direction == "RETURN";
                if (count($search_results["departure"]) > 0) {
                    $params2["payload"] = $search_results["payload"];
                    $params2["adult_count"] = $adult_count;
                    $params2["child_count"] = $child_count;
                    $params2["infant_count"] = $params["itinerary"]["departure"]["infant"];
                    foreach ($search_results["departure"] as $dkey => $dep) {
                        $params2["departure_flight_number"] = $dep["flight_number"];
                        $params2["departure_from"] = $dep["origin"];
                        $params2["departure_to"] = $dep["destination"];
                        $params2["departure_from_date"] = $dep["departure_time"];
                        $params2["departure_to_date"] = $dep["arrival_time"];
                        $params2["dep_key"] = $dkey;
                        if ($params2["is_return"] && count($search_results["return"]) > 0) {
                            foreach ($search_results["return"] as $rkey => $ret) {
                                $params2["return_flight_number"] = $ret["flight_number"];
                                $params2["return_from"] = $ret["origin"];
                                $params2["return_to"] = $ret["destination"];
                                $params2["return_from_date"] = $ret["departure_time"];
                                $params2["return_to_date"] = $ret["arrival_time"];
                                $params2["ret_key"] = $rkey;
                                $params_pool[] = $params2;
                            } //foreach return flights
                        } else {
                            $params_pool[] = $params2;
                        } //else oneway
                    } //foreach departure flights
                } //if count departure > 0

                $pool_results = $this->getPricePool($params_pool, $token, $session_timestamp);
                $search_results["js_cookie"] = $pool_results["js_cookie"] ?? null;
                $price_responses = $pool_results["responses"];
                foreach ($price_responses as $pr_key => $pres) {
                    $price_r = AirArabiaSupport::processGetPriceResultsForSearch($pres, $params2);
                    if (isset($price_r["Errors"])) {
                        //return $price_r;
                        continue;
                    }

                    if ($direction == "ONEWAY") {
                        $dep_k = $pr_key;
                    } else {
                        $keys = \explode("-", $pr_key);
                        $dep_k = $keys[0];
                        $ret_k = $keys[1];
                    }

                    if (isset($search_results["departure"][$dep_k]["price_checked"]) && $search_results["departure"][$dep_k]["price_checked"]) {
                        if ($search_results["departure"][$dep_k]["price"] > $price_r["price"]) {
                            $search_results["departure"][$dep_k]["price"] = $price_r["price"];
                            $search_results["departure"][$dep_k]["base_fare"] = $price_r["base_fare"];
                            $search_results["departure"][$dep_k]["taxes"] = $price_r["taxes"];
                        }
                    } else {
                        $search_results["departure"][$dep_k]["price"] = $price_r["price"];
                        $search_results["departure"][$dep_k]["base_fare"] = $price_r["base_fare"];
                        $search_results["departure"][$dep_k]["taxes"] = $price_r["taxes"];
                        $search_results["departure"][$dep_k]["price_checked"] = true;
                    }

                    if ($direction == "RETURN") {
                        if (isset($search_results["return"][$ret_k]["price_checked"]) && $search_results["return"][$ret_k]["price_checked"]) {
                            if ($search_results["return"][$ret_k]["price"] > $price_r["price"]) {
                                $search_results["return"][$ret_k]["price"] = $price_r["price"];
                                $search_results["return"][$ret_k]["base_fare"] = $price_r["base_fare"];
                                $search_results["return"][$ret_k]["taxes"] = $price_r["taxes"];
                            }
                        } else {
                            $search_results["return"][$ret_k]["price"] = $price_r["price"];
                            $search_results["return"][$ret_k]["base_fare"] = $price_r["base_fare"];
                            $search_results["return"][$ret_k]["taxes"] = $price_r["taxes"];
                            $search_results["return"][$ret_k]["price_checked"] = true;
                        }
                    }
                }

                if ($params["flexible_dates"]) {
                    if (isset($search_results["departure"])) {
                        $search_results["dates_range"]["departure"] = [];
                        foreach ($search_results["departure"] as $dflt) {
                            $fdt = date("Y-m-d", strtotime($dflt["departure_time"]));
                            $search_results["dates_range"]["departure"][$fdt][] = $dflt;
                        }
                    }
                    if (isset($search_results["return"])) {
                        $search_results["dates_range"]["return"] = [];
                        foreach ($search_results["return"] as $rflt) {
                            $fdt = date("Y-m-d", strtotime($rflt["departure_time"]));
                            $search_results["dates_range"]["return"][$fdt][] = $rflt;
                        }
                    }
                } //if lexible_dates

                $search_results["access_token"] = $token;
                $search_results["session_timestamp"] = $session_timestamp;
                return $search_results;
            }
        }
        return [];
    } //searchFlights

    public function bookFlight($params, $payment = false)
    {
        $direction = "ONEWAY";
        if (isset($params["return"]) && $params["return"] != null) $direction = "RETURN";
        $token = $params["access_token"] ?? null;
        $session_timestamp = $params["session_timestamp"] ?? null;
        $js_cookie = $params["js_cookie"] ?? null;
        if ($token) {
            $request = AirArabiaSupport::generateBookRequest($params, $payment);
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Book_req.xml", $request);

            $response_xml = Http::withToken($token)
                ->withHeaders([
                    "X-AERO-SALES-CHANNEL" => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                    "X-AERO-JOURNEY-TYPE" => $direction,
                    "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                    "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                    "Cookie" => $js_cookie
                ])
                ->withBody($request, "application/xml")
                ->post(config("airlines.AirArabia.urls.all_requests"));
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Book_res_r.xml", $response_xml->body());

            if ($response_xml->successful()) {
                return AirArabiaSupport::processBookResults($response_xml->body(), $params);
            } else {
                return [
                    "Errors" => [
                        "code" => $response_xml->status(),
                        "text" => $response_xml->body()
                    ]
                ];
            }
        }
        return false;
    }

    public function getPrice($params)
    {
        $direction = $params["is_return"] ? "RETURN" : "ONEWAY";
        $token = $params["access_token"] ?? null;
        $session_timestamp = $params["session_timestamp"] ?? null;
        $js_cookie = $params["js_cookie"] ?? null;
        if ($token) {
            $request = AirArabiaSupport::generateGetPriceRequest($params);
            $time_s = time();
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Price_req_" . $time_s . ".xml", $request);
            $response_xml = Http::withToken($token)
                ->withHeaders([
                    'X-AERO-SALES-CHANNEL'      => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                    "X-AERO-JOURNEY-TYPE"       => $direction,
                    "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                    "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                    "Cookie" => $js_cookie
                ])
                ->withBody(trim($request), "application/xml")
                ->post(config("airlines.AirArabia.urls.all_requests"));
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Price_res_" . $time_s . ".xml", $response_xml->body());
            if ($response_xml->successful()) {
                if (!$js_cookie) {
                    $headers = $response_xml->headers();
                    $scookie = $headers["set-cookie"];
                    foreach ($scookie as $sck) {
                        if (\str_starts_with($sck, "JSESSIONID=")) {
                            $js_cookie = $sck;
                            break;
                        }
                    }
                }
                return [
                    "response" => $response_xml->body(),
                    "js_cookie" => $js_cookie
                ];
            }
            return [
                "Errors" => [
                    "code" => $response_xml->status(),
                    "text" => $response_xml->body()
                ]
            ];
        }
        return false;
    }

    public function getPricePool(array $params, $token, $session_timestamp)
    {
        $js_cookie = null;
        if ($token) {
            $responses = Http::pool(function (Pool $pool) use ($params, $token, $session_timestamp, $js_cookie) {
                foreach ($params as $prm) {
                    $direction = $prm["is_return"] ? "RETURN" : "ONEWAY";
                    $request = AirArabiaSupport::generateGetPriceRequest($prm);
                    $key = isset($prm["ret_key"]) ? $prm["dep_key"] . "-" . $prm["ret_key"] : $prm["dep_key"];
                    Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Price_req_" . $key . ".xml", $request);
                    $pool->as($key)->withToken($token)
                        ->withHeaders([
                            'X-AERO-SALES-CHANNEL'      => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                            "X-AERO-JOURNEY-TYPE"       => $direction,
                            "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                            "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                            "Cookie" => $js_cookie
                        ])
                        ->withBody(trim($request), "application/xml")
                        ->post(config("airlines.AirArabia.urls.all_requests"));
                }
            });

            $response_returns = [];
            foreach ($responses as $res_key => $response) {
                Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Price_res_" . $res_key . ".xml", $response);
                if ($response->successful()) {
                    if (!$js_cookie) {
                        $headers = $response->headers();
                        $scookie = $headers["set-cookie"];
                        foreach ($scookie as $sck) {
                            if (\str_starts_with($sck, "JSESSIONID=")) {
                                $js_cookie = $sck;
                                break;
                            }
                        }
                    }
                    $response_returns[$res_key] = $response->body();
                } else {
                    $response_returns[$res_key] = [
                        "Errors" => [
                            "code" => $response->status(),
                            "text" => $response->body()
                        ]
                    ];
                }
            }
            $results["responses"] = $response_returns;
            $results["js_cookie"] = $js_cookie;
            return $results;
        }
        return false;
    } //getPricePool

    public function processPriceResults($results)
    {
        return AirArabiaSupport::processGetPriceResults($results);
    }

    public function processPriceBundles($result, $dep_ret, $params)
    {
        return AirArabiaSupport::processGetPriceResultBundles($result, $dep_ret, $params);
    }

    public function getExtras($params)
    {
        $p_response = $this->getPrice($params);
        $price_response = $p_response["response"] ?? $p_response;
        $js_cookie = $p_response["js_cookie"] ?? null;
        $anc_params = AirArabiaSupport::processGetPriceResultsForAncillaries($price_response);
        if (isset($anc_params["Errors"])) {
            return $anc_params;
        }
        $anc_params["is_return"] = $params["is_return"];
        $anc_params["access_token"] = $params["access_token"];
        $anc_params["session_timestamp"] = $params["session_timestamp"];
        $anc_params["js_cookie"] = $js_cookie;
        $errors["Errors"] = [];
        $meal_response = $this->getMealService($anc_params);
        if (isset($meal_response["Errors"])) {
            $errors["Errors"] = array_merge($errors["Errors"], $meal_response["Errors"]);
        }
        $baggage_response = $this->getBaggageService($anc_params);
        if (isset($baggage_response["Errors"])) {
            $errors["Errors"] = array_merge($errors["Errors"], $baggage_response["Errors"]);
        }
        $seat_response = $this->getSeatMap($anc_params);
        if (isset($seat_response["Errors"])) {
            $errors["Errors"] = array_merge($errors["Errors"], $seat_response["Errors"]);
        }
        if ($errors["Errors"]) {
            return $errors;
        }

        return [
            "payload" => $price_response,
            "baggage" => $baggage_response,
            "meals" => $meal_response,
            "seats" => $seat_response,
            "js_cookie" => $js_cookie,
            "transaction_identifier" => $anc_params["transaction_identifier"]
        ];
    }

    public function getSeatMap($params)
    {
        $direction = $params["is_return"] ? "RETURN" : "ONEWAY";
        $token = $params["access_token"] ?? null;
        $session_timestamp = $params["session_timestamp"] ?? null;
        $js_cookie = $params["js_cookie"] ?? null;
        if ($token) {
            $request = AirArabiaSupport::generateSeatMapRequest($params);
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Seat_req.xml", $request);
            $response_xml = Http::withToken($token)
                ->withHeaders([
                    "X-AERO-SALES-CHANNEL" => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                    "X-AERO-JOURNEY-TYPE" => $direction,
                    "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                    "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                    "Cookie" => $js_cookie
                ])
                ->withBody($request, "application/xml")
                ->post(config("airlines.AirArabia.urls.all_requests"));
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Seat_res.xml", $response_xml->body());
            if ($response_xml->successful()) {
                return AirArabiaSupport::processSeatMapResults($response_xml->body(), $params);
            }
            return [
                "Errors" => [
                    "code" => $response_xml->status(),
                    "text" => $response_xml->body()
                ]
            ];
        }
        return false;
    }

    public function getMealService($params)
    {
        $direction = $params["is_return"] ? "RETURN" : "ONEWAY";
        $token = $params["access_token"] ?? null;
        $session_timestamp = $params["session_timestamp"] ?? null;
        $js_cookie = $params["js_cookie"] ?? null;
        if ($token) {
            $request = AirArabiaSupport::generateMealRequest($params);
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Meal_req.xml", $request);
            $response_xml = Http::withToken($token)
                ->withHeaders([
                    "X-AERO-SALES-CHANNEL" => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                    "X-AERO-JOURNEY-TYPE" => $direction,
                    "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                    "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                    "Cookie" => $js_cookie
                ])
                ->withBody($request, "application/xml")
                ->post(config("airlines.AirArabia.urls.all_requests"));
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Meal_res.xml", $response_xml->body());
            if ($response_xml->successful()) {
                return AirArabiaSupport::processMealResults($response_xml->body(), $params);
            }
            return [
                "Errors" => [
                    "code" => $response_xml->status(),
                    "text" => $response_xml->body()
                ]
            ];
        }
        return false;
    }

    public function getBaggageService($params)
    {
        $direction = $params["is_return"] ? "RETURN" : "ONEWAY";
        $token = $params["access_token"] ?? null;
        $session_timestamp = $params["session_timestamp"] ?? null;
        $js_cookie = $params["js_cookie"] ?? null;
        if ($token) {
            $request = AirArabiaSupport::generateBaggageRequest($params);
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Baggage_req.xml", $request);
            $response_xml = Http::withToken($token)
                ->withHeaders([
                    "X-AERO-SALES-CHANNEL" => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                    "X-AERO-JOURNEY-TYPE" => $direction,
                    "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                    "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                    "Cookie" => $js_cookie
                ])
                ->withBody($request, "application/xml")
                ->post(config("airlines.AirArabia.urls.all_requests"));
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Baggage_res.xml", $response_xml->body());
            if ($response_xml->successful()) {
                return AirArabiaSupport::processBaggageResults($response_xml->body(), $params);
            }
            return [
                "Errors" => [
                    "code" => $response_xml->status(),
                    "text" => $response_xml->body()
                ]
            ];
        }
        return false;
    }

    public function getFlightFareTypes($params)
    {
        $js_cookie = null;
        $trip = "d";
        if ($params["departure_selected"]) {
            $price_results_raw = $params["payload"];
            $trip = "r";
        } else {
            $p_res = $this->getPrice($params);
            $price_results_raw = $p_res["response"] ?? $p_res;
            $js_cookie = $p_res["js_cookie"] ?? null;
        }
        //$price_results_raw = $params["departure_selected"] ? $params["payload"] : $this->getPrice($params);
        if ($errors = AirArabiaSupport::getErrors($price_results_raw)) {
            if (isset($errors["Errors"])) {
                return $errors;
            }
        }
        if ($price_results_raw) {
            //$bundles = $this->processPriceBundles($price_results_raw, $params["sol_id"] != null ? "r" : "d");
            $bundles = $this->processPriceBundles($price_results_raw, $trip, $params);
            return [
                "fares" => $bundles["fares"] ?? $bundles,
                "transaction_identifier" => $bundles["transaction_identifier"] ?? null,
                "payload" => $price_results_raw,
                "js_cookie" => $js_cookie
            ];
        }
        return [];
    }

    public function confirmationStep($params)
    {
        $token = $params["access_token"] ?? null;
        $session_timestamp = $params["session_timestamp"] ?? null;
        $js_cookie = $params["js_cookie"] ?? null;
        $arr["travellers"] = [];
        $arr["departure"] = null;
        $arr["return"] = null;
        $arr["amount"] = 0;
        $arr["taxes"] = 0;
        $arr["pax_codes"] = [];

        $is_return = count($params["selected_return"]) ? true : false;
        $params2["is_return"] = $is_return;
        $params2["payload"] = $params["payload"];
        $params2["departure_flight_number"] = $params["selected_departure"]["flight_number"];
        $params2["departure_from_date"] = $params["selected_departure"]["departure_date"];
        $params2["departure_to_date"] = $params["selected_departure"]["arrival_date"];
        $params2["departure_from"] = $params["selected_departure"]["departure_form"];
        $params2["departure_to"] = $params["selected_departure"]["departure_to"];
        $params2["adult_count"] = isset($params["passengers"]["adult"]) ? count($params["passengers"]["adult"]) : 0;
        $params2["child_count"] = isset($params["passengers"]["child"]) ? count($params["passengers"]["child"]) : 0;
        $params2["infant_count"] = isset($params["passengers"]["infant"]) ? count($params["passengers"]["infant"]) : 0;
        $params2["bundled_services"]["departure"] = $params["selected_departure"]["bundle_id"] > 0 ? $params["selected_departure"]["bundle_id"] : 0;

        if ($is_return) {
            $params2["return_flight_number"] = $params["selected_return"]["flight_number"];
            $params2["return_from_date"] = $params["selected_return"]["departure_date"];
            $params2["return_to_date"] = $params["selected_return"]["arrival_date"];
            $params2["return_from"] = $params["selected_return"]["departure_form"];
            $params2["return_to"] = $params["selected_return"]["departure_to"];
            $params2["bundled_services"]["return"] = $params["selected_return"]["bundle_id"] > 0 ? $params["selected_return"]["bundle_id"] : 0;
        }

        $params2["service_requests"]["meals"] = [];
        $params2["service_requests"]["baggages"] = [];
        $params2["service_requests"]["seats"] = [];
        foreach ($params["passengers"] as $key => $values) {
            foreach ($values as $pass) {
                $tr["title"] = $pass["title"];
                $tr["first_name"] = $pass["fname"];
                $tr["last_name"] = $pass["lname"];
                $tr["dob"] = date("Y-m-d", strtotime($pass["dob"]));
                $tr["type"] = strtoupper($key);
                $tr["nationality"] = $pass["nationality"];
                if (isset($pass["phone"])) $tr["phone"] =  $pass["phone"];
                if (isset($pass["email"])) $tr["email"] = $pass["email"];
                $tr["passport_number"] = $pass["passport"];
                $tr["passport_expiry"] = $pass["passportexpiry"];
                if (isset($pass["baggage"])) $tr["ancillaries"]["baggage"] = is_array($pass["baggage"]) ? $pass["baggage"] : \json_decode($pass["baggage"], true);
                if (isset($pass["meals"])) $tr["ancillaries"]["meals"] = is_array($pass["meals"]) ? $pass["meals"] : \json_decode($pass["meals"], true);
                if (isset($pass["seats"])) $tr["ancillaries"]["seats"] = is_array($pass["seats"]) ? $pass["seats"] : \json_decode($pass["seats"], true);

                if (isset($pass["meals"])) {
                    foreach ($tr["ancillaries"]["meals"] as $mkey => $mllp) {
                        $mll = json_decode($mllp, true);
                        $tr["ancillaries"]["meals"][$mkey] = $mll;
                        if (!$mll) continue;
                        $meal = [];
                        $meal["meal_code"] = $mll["code"];
                        $meal["quantity"] = 1;
                        $meal["traveller_ref_number"] = $pass["ref_rph"];
                        $meal["flight_rph"] = $mll["rph"];
                        $meal["departure_date"] = $mll["departure_date"];
                        $meal["flight_num"] = $mll["flight_number"];
                        $params2["service_requests"]["meals"][] = $meal;
                    }
                }
                if (isset($pass["baggage"])) {
                    foreach ($tr["ancillaries"]["baggage"] as $bkey => $bggp) {
                        $bgg = json_decode($bggp, true);
                        $tr["ancillaries"]["baggage"][$bkey] = $bgg;
                        if (!$bgg) continue;
                        $bag = [];
                        $bag["baggage_code"] = $bgg["code"];
                        $bag["traveller_ref_number"] = $pass["ref_rph"];
                        $bag["flight_rph"] = $bgg["rph"];
                        $bag["departure_date"] = $bgg["departure_date"];
                        $bag["flight_num"] = $bgg["flight_number"];
                        $bag["segments"] = $bgg["segments"];
                        $params2["service_requests"]["baggages"][] = $bag;
                    }
                }
                if (isset($pass["seats"])) {
                    foreach ($pass["seats"] as $sst) {
                        if (!$sst) continue;
                        $seat = [];
                        $seat["seat_number"] = $sst["row_number"] . $sst["seat_number"];
                        $seat["flight_rph"] = $sst["rph"];
                        $seat["traveller_ref_number"] = $pass["ref_rph"];
                        $params2["service_requests"]["seats"][] = $seat;
                    }
                }

                $arr["travellers"][] = $tr;
            }
        }

        $params2["access_token"] = $token;
        $params2["session_timestamp"] = $session_timestamp;
        $params2["js_cookie"] = $js_cookie;
        $params2["transaction_identifier"] = $params["transaction_identifier"] ?? null;

        $p_results = $this->getPrice($params2);
        $price_results = $p_results["response"] ?? $p_results;
        Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Price_res2.xml", $price_results);
        if (isset($price_results["Errors"])) {
            return $price_results;
        }
        $resl = AirArabiaSupport::processGetPriceResultsConfirmationStep($price_results);
        if (isset($resl["Errors"])) {
            return $resl;
        }
        $arr["amount"] = $resl["amount"];
        $arr["taxes"] = $resl["taxes"];
        $arr["departure"] = $resl["departure"];
        $arr["return"] = $resl["return"];
        $arr["pax_codes"] = $resl["pax_codes"];
        $arr["js_cookie"] = $p_results["js_cookie"] ?? null;

        return $arr;
    }

    public function processBookingStep($params, $payment = false)
    {
        $arr["access_token"] = $params["access_token"];
        $arr["session_timestamp"] = $params["session_timestamp"];
        $arr["js_cookie"] = $params["js_cookie"];
        $arr["total_payment"] = $params["amount"] + $params["taxes"];
        $arr["transaction_identifier"] = $params["departure"]["transaction_identifier"];
        /*
        $arr["departure_departure_date"] = $params["departure"]["departure_time"];
        $arr["departure_arrival_date"] = $params["departure"]["arrival_time"];
        $arr["departure_flight_number"] = $params["departure"]["flight_number"];
        $arr["departure_rph"] = $params["departure"]["rph"];
        $arr["departure_from"] = $params["departure"]["from"];
        $arr["departure_to"] = $params["departure"]["to"];

        $arr["return_departure_date"] = isset($params["return"]["departure_time"]) ? $params["return"]["departure_time"] : null;
        $arr["return_arrival_date"] = isset($params["return"]["arrival_time"]) ? $params["return"]["arrival_time"] : null;
        $arr["return_flight_number"] = isset($params["return"]["flight_number"]) ? $params["return"]["flight_number"] : null;
        $arr["return_rph"] = isset($params["return"]["rph"]) ? $params["return"]["rph"] : null;
        $arr["return_from"] = isset($params["return"]["from"]) ? $params["return"]["from"] : null;
        $arr["return_to"] = isset($params["return"]["to"]) ? $params["return"]["to"] : null;
        */

        $arr["departure"] = $params["departure"];
        $arr["return"] = $params["return"] ?? null;

        $arr["travellers"] = [];
        foreach ($params["passengers"] as $key => $values) {
            foreach ($values as $indx => $pass) {
                $px = [];
                $type_code = "ADT";
                $type_code = $key == strtolower("child") ? "CHD" : $type_code;
                $type_code = $key == strtolower("infant") ? "INF" : $type_code;
                $px["birth_date"] = date("Y-m-d", strtotime($pass["dob"]));
                $px["type_code"] = $type_code;
                $px["first_name"] = $pass["fname"];
                $px["last_name"] = $pass["lname"];
                $px["title"] = $pass["title"];
                $px["country_phone_code"] = "";
                $px["phone_number"] = $pass["phone"] ?? "";
                $px["country_name_code"] = "";
                $px["document_id"] = $pass["passport"];
                $px["document_type"] = "PSPT";
                $px["document_nationality"] = $pass["nationality"];
                $px["document_expiry"] = $pass["passportexpiry"];
                $px["ref_code"] = $params["pax_codes"][$type_code][$indx];
                $arr["travellers"][] = $px;
            }
        }

        $result = $this->bookFlight($arr, $payment);
        return $result;
    } //processBookingStep

    public function retrievePnr($params)
    {
        if ($token = self::authenticate()) {
            $js_cookie = null;
            $request = AirArabiaSupport::generateRetrievePnrRequest($params);
            if ($request) {
                $session_timestamp = $params["session_timestamp"] ?? null;
                $response_xml = Http::withToken($token)->withBody($request, "application/xml")->post(config("airlines.AirArabia.urls.all_requests"));
                Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_Retrieve_PNR_res.xml", $response_xml->body());
                if ($response_xml->successful()) {
                    if (!$js_cookie) {
                        $headers = $response_xml->headers();
                        $scookie = $headers["set-cookie"];
                        foreach ($scookie as $sck) {
                            if (\str_starts_with($sck, "JSESSIONID=")) {
                                $js_cookie = $sck;
                                break;
                            }
                        }
                    }
                    $resl = AirArabiaSupport::processBookResults($response_xml->body(), null);
                    $resl["access_token"] = $token;
                    $resl["js_cookie"] = $js_cookie;
                    return $resl;
                }
                return [
                    "Errors" => [
                        "code" => $response_xml->status(),
                        "text" => $response_xml->body()
                    ]
                ];
            }
        }
        return false;
    } //retrievePnr

    public function modifyProcessPayment($params)
    {
        $token = $params["access_token"] ?? null;
        $session_timestamp = $params["session_timestamp"] ?? null;
        $js_cookie = $params["js_cookie"] ?? null;
        if ($token) {
            $request = AirArabiaSupport::generateModifyPaymentRequest($params);
            Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_ModifyPayment_req.xml", $request);
            if ($request) {
                $response_xml = Http::withToken($token)
                    ->withHeaders([
                        "SOAPAction" => "modifyReservation",
                        "X-AERO-SALES-CHANNEL" => config("airlines.AirArabia.headers.X-AERO-SALES-CHANNEL"),
                        "X-AERO-USERID" => config("airlines.AirArabia.headers.X-AERO-USERID"),
                        "X-AERO-AGENT-CODE" => config("airlines.AirArabia.headers.X-AERO-AGENT-CODE"),
                        "Cookie" => $js_cookie
                    ])
                    ->withBody($request, "application/xml")
                    ->post(config("airlines.AirArabia.urls.all_requests"));
                Storage::disk("local")->put("AirArabia_Logs" . DIRECTORY_SEPARATOR . \date("Ymd") . DIRECTORY_SEPARATOR . $session_timestamp . DIRECTORY_SEPARATOR . "AA_ModifyPayment_res.xml", $response_xml->body());
                if ($response_xml->successful()) {
                    return AirArabiaSupport::processBookResults($response_xml->body(), null);
                }
                return [
                    "Errors" => [
                        "code" => $response_xml->status(),
                        "text" => $response_xml->body()
                    ]
                ];
            }
        }
        return false;
    } //modifyProcessPayment

    public function retrieveAndPay($data)
    {
        $resl["error"] = [];
        $resl["success"] = false;

        $params["session_timestamp"] = time();
        $params["booking_ref"] = $data["bookref"];
        $pnr = $this->retrievePnr($params);
        if (isset($pnr["Errors"])) {
            $resl["error"][] = $pnr["Errors"][0]["code"] . " - " . $pnr["Errors"][0]["text"];
        } else {
            $params["access_token"] = $pnr["access_token"] ?? null;
            $params["js_cookie"] = $pnr["js_cookie"] ?? null;
            $amount = calculateMarkupPrice($pnr["amount"], 'AirArabia');
            $total_amount = $amount + $pnr["taxes"];
            if (auth()->user()->type == 'agency' && $total_amount > auth()->user()->balance) {
                $resl["error"][] = 'Insufficient Balance';
            } else {
                $params["transaction_identifier"] = $pnr["transaction_identifier"];
                $params["charge"] = $pnr["itin_total_fare"];
                $results = $this->modifyProcessPayment($params);
                if (isset($results["Errors"])) {
                    $resl["error"][] = $results["Errors"][0]["code"] . " - " . $results["Errors"][0]["text"];
                } else {
                    $booking = AirlineBooking::findOrFail($data["booking_id"]);
                    $booking->status = "confirmed";
                    try {
                        $booking->save();
                        if (!$this->hold) {
                            Integration::find(1)->decrement('balance', ($pnr["amount"] + $pnr["taxes"]));
                            if (auth()->user()->type == 'agency') {
                                $user = auth()->user();
                                $user->balance -= $total_amount;
                                $user->save();
                            }
                            $resl["success"] = true;
                        }
                        Mail::send(new NewAirlineBookingEmail('AirArabia', $booking->id, auth()->user()->email));
                    } catch (\Exception $e) {
                        $this->balance_error = $e->getMessage();
                    }
                }
            }
        }
        return $resl;
    } //retrieveAndPay
}
