<?php

namespace App\Services\Airlines\FlyDubai;

use App\Interfaces\AirlineServiceInterface;
use App\Mail\NewAirlineBookingEmail;
use App\Models\Integration;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\AirlineBooking;
use Mail;

class FlyDubaiAirline implements AirlineServiceInterface
{

    public function authenticate()
    {
        $response = Http::asForm()
            ->post(
                config("airlines.FlyDubai.urls.authenticate"),
                [
                    "client_id" => config('airlines.FlyDubai.client_id'),
                    "client_secret" => config('airlines.FlyDubai.client_secret'),
                    "grant_type" => "password",
                    "username" => config('airlines.FlyDubai.username'),
                    "password" => config('airlines.FlyDubai.password'),
                    "scope" => "res"
                ]
            );
        if ($response->successful()) {
            $res = $response->collect();
            $token = $res->get('access_token');
            return $token;
        }

        return false;
    } //authenticate

    public function searchFlights($params)
    {
        $session_timestamp = time();
        $params["flexible_dates"] = false;
        $params["flexibility_days"] = 1;
        $jsonRequest = FlyDubaiSupport::generateSearchRequest($params);
        if ($token = self::authenticate()) {
            Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."search_request.json", json_encode($jsonRequest));
            $response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
                ->post(
                    config("airlines.FlyDubai.urls.search_flights"),
                    $jsonRequest
                );
            Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."search_result.json", json_encode($response->body()));
            if ($response->successful()) {
                $search_results = FlyDubaiSupport::processSearchResults($response->json(), $params["itinerary"]["departure"]["from"], $params["itinerary"]["departure"]["to"]);
                if($params["flexible_dates"]){
                    if(isset($search_results["departure"])){
                        $search_results["dates_range"]["departure"] = [];
                        foreach($search_results["departure"] as $dflt){
                            $fdt = date("Y-m-d", strtotime($dflt["departure_time"]));
                            $search_results["dates_range"]["departure"][$fdt][] = $dflt;
                        }
                    }
                    if(isset($search_results["return"])){
                        $search_results["dates_range"]["return"] = [];
                        foreach($search_results["return"] as $rflt){
                            $fdt = date("Y-m-d", strtotime($rflt["departure_time"]));
                            $search_results["dates_range"]["return"][$fdt][] = $rflt;
                        }
                    }
                }//if lexible_dates
                $search_results["access_token"] = $token;
                $search_results["session_timestamp"] = $session_timestamp;
                return $search_results;
            }
        }

        return [];
    } //searchFlights

    public function addToCart($params)
    {
        try {
            $search_results = $params["payload"];
            $selected_departure = $params["selected_departure"];
            $selected_return = $params["selected_return"];
            $token = $params["access_token"] ?? null;
            $session_timestamp = $params["session_timestamp"] ?? null;
            if ($token) {
                $jsonRequest = FlyDubaiSupport::generateAddToCartRequest($search_results, $selected_departure, $selected_return);
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."cart_request.json", json_encode($jsonRequest));
                $cart_response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
                    ->post(
                        config("airlines.FlyDubai.urls.add_to_cart"),
                        $jsonRequest
                    );
                $response_body = json_decode($cart_response->body(), true);
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."cart_response.json", $cart_response->body());
                if ($cart_response->successful()) {
                    if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 0) {
                        return $response_body;
                    } else if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 800) {
                        return ["error" => "Security token expired..."];
                    } else {
                        return ["error" => $response_body["Exceptions"][0]["ExceptionDescription"]];
                    }
                } else {
                    return ["error" => $cart_response->body()];
                }
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return ["error" => "Unkown Error. Add To Cart"];
    } //addToCart

    public function bookFlight($params)
    {
        // TODO: Implement bookFlight() method.
    } //bookFlight

    public function getPrice($params)
    {
        // TODO: Implement getPrice() method.
    }

    public function getExtras($params)
    {
        // TODO: Implement getExtras() method.
    }

    public function getSeatMap($params, $params2)
    {
        try{
            $token = $params2["access_token"];
            $session_timestamp = $params2["session_timestamp"];
            $departure_type_name = $params2["departure_type_name"];
            $departure_stops = $params2["departure_stops"];
            $return_stops = $params2["return_stops"];
            if ($token) {
                $jsonRequest = FlyDubaiSupport::generateAncillaryRequest($params);
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."seatmap_request.json", json_encode($jsonRequest));
                $seat_response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
                    ->post(
                        config("airlines.FlyDubai.urls.seat_map"),
                        $jsonRequest
                    );
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."seatmap_response.json", json_encode($seat_response->body()));
                if ($seat_response->successful()) {
                    $response_body = json_decode($seat_response->body(), true);
                    if (isset($response_body["Exceptions"]) && sizeof($response_body["Exceptions"]) > 0){
                        return ["error" => $response_body["Exceptions"][0]["ExceptionDescription"]];
                    }
                    return [
                        "payload" => $response_body,
                        "results" => FlyDubaiSupport::processSeatMapResults($response_body, $departure_type_name, $departure_stops, $return_stops)
                    ];
                }else {
                    return ["error" => $seat_response->body()];
                }
            }
        }catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return ["error" => "Unkown Error. Get Seat Map"];
    }//getSeatMap

    public function getAncillaries($params, $params2)
    {
        try {
            $token = $params2["access_token"] ?? null;
            $session_timestamp = $params2["session_timestamp"] ?? null;
            if ($token) {
                $jsonRequest = FlyDubaiSupport::generateAncillaryRequest($params);
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."ancillaries_request.json", json_encode($jsonRequest));
                $anc_response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
                    ->post(
                        config("airlines.FlyDubai.urls.ancillaries"),
                        $jsonRequest
                    );
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."ancillaries_response.json", json_encode($anc_response->body()));
                if ($anc_response->successful()) {
                    $response_body = json_decode($anc_response->body(), true);
                    if (isset($response_body["Exceptions"]) && sizeof($response_body["Exceptions"]) > 0) {
                        return ["error" => $response_body["Exceptions"][0]["ExceptionDescription"]];
                    }

                    $departure_type_name = $params2["departure_type_name"];
                    $departure_services = $params2["departure_codes"];
                    $return_services = $params2["return_codes"] ?? null;
                    $departure_stops = $params2["departure_stops"] ?? [];
                    $return_stops = $params2["return_stops"] ?? [];

                    return [
                        "payload" => $response_body,
                        "results" => FlyDubaiSupport::processAncillaryRequest($response_body, $departure_type_name, $departure_services, $return_services, $departure_stops, $return_stops)
                    ];
                } else {
                    return ["error" => $anc_response->body()];
                }
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return ["error" => "Unkown Error. Get Ancillaries"];
    }

    public function getMealService($params)
    {
        // TODO: Implement getMealService() method.
    }

    public function getBaggageService($params)
    {
        // TODO: Implement getBaggageService() method.
    }

    public function summaryPNR($cart_response, $params)
    {
        try {
            $passengers = $params["passengers"];
            $has_return = $params["has_return"] ?? false;
            $token = $params["access_token"] ?? null;
            $session_timestamp = $params["session_timestamp"] ?? null;

            $travellers = [];
            $passengers_keys = ["adult", "child", "infant"];
            foreach ($passengers_keys as $pk) {
                if (isset($passengers[$pk])) {
                    $type = 1; //1= Adult, 5 = Infant, 6 = Child
                    $type = ($pk == "child") ? 6 : $type;
                    $type = ($pk == "infant") ? 5 : $type;
                    foreach ($passengers[$pk] as $pass) {
                        //$info["title"] = $pass["title"];
                        //$info["gender"] = strtolower(trim($pass["title"], ".")) == "mr" ? "MALE" : "FEMALE";
                        $info["gender"] = $pass["title"];
                        $info["first_name"] = $pass["fname"];
                        $info["last_name"] = $pass["lname"];
                        $info["birth_date"] = date("Y-m-d", strtotime($pass["dob"]));
                        $info["type"] = $type;
                        $info["nationality"] = $pass["nationality"];
                        $info["document_id"] = $pass["passport"];
                        $info["document_expiry_date"] = $pass["passportexpiry"];
                        $info["contact"]["email"] = isset($pass["email"]) ? $pass["email"] : "";
                        $info["contact"]["phone_number"] = isset($pass["phone"]) ? $pass["phone"] : "";
                        $info["baggage"] = $pass["baggage"] ?? [];
                        $info["meals"] = $pass["meals"] ?? [];
                        $info["seats"] = $pass["seats"] ?? [];
                        $travellers[] = $info;
                    }
                }
            }

            if ($token) {
                $jsonRequest = FlyDubaiSupport::generateSummaryPnrRequest($cart_response, $travellers, $has_return);
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."summary_request.json", json_encode($jsonRequest));
                $summary_response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
                    ->post(
                        config("airlines.FlyDubai.urls.summary_pnr"),
                        $jsonRequest
                    );
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."summary_response.json", json_encode($summary_response->body()));
                if ($summary_response->successful()) {
                    $response_body = json_decode($summary_response->body(), true);
                    if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 0) {
                        return $response_body;
                    } else if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 800) {
                        return ["error" => "Security token expired..."];
                    } else {
                        return ["error" => $response_body["Exceptions"][0]["ExceptionDescription"]];
                    }
                } else {
                    return ["error" => $summary_response->body()];
                }
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return ["error" => "Unkown Error. Summary PNR request"];
    }

    public function commitPNR($params)
    {
        try {
            $token = $params["access_token"];
            $session_timestamp = $params["session_timestamp"];
            if ($token) {
                $jsonRequest = FlyDubaiSupport::generateCommitPnrRequest();
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."commit_request.json", json_encode($jsonRequest));
                $commit_response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
                    ->post(
                        config("airlines.FlyDubai.urls.commit_pnr"),
                        $jsonRequest
                    );
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."commit_response.json", json_encode($commit_response->body()));
                if ($commit_response->successful()) {
                    $response_body = json_decode($commit_response->body(), true);
                    if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 0) {
                        return $response_body;
                    } else if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 800) {
                        return ["error" => "Security token expired..."];
                    } else {
                        return ["error" => $response_body["Exceptions"][0]["ExceptionDescription"]];
                    }
                }
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return ["error" => "Unkown Error. Commit PNR request"];
    }

    public function processPayment($params, $params2, $payor)
    {
        try {
            $token = $params2["access_token"] ?? null;
            $session_timestamp = $params2["session_timestamp"] ?? null;
            if ($token) {
                $jsonRequest = FlyDubaiSupport::generateFOPRequest($params, $payor);
                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."payment_request.json", json_encode($jsonRequest));
                $fop_response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
                    ->post(
                        config("airlines.FlyDubai.urls.fop"),
                        $jsonRequest
                    );

                Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."payment_response.json", json_encode($fop_response->body()));
                if ($fop_response->successful()) {
                    $response_body = json_decode($fop_response->body(), true);
                    if (isset($response_body["ViewPNR"]["Exceptions"]) && $response_body["ViewPNR"]["Exceptions"][0]["ExceptionCode"] == 0) {
                        return $response_body;
                    } else if (isset($response_body["ViewPNR"]["Exceptions"]) && (int)$response_body["ViewPNR"]["Exceptions"][0]["ExceptionCode"] == 800) {
                        return ["error" => "Security token expired..."];
                    } else {
                        return ["error" => $response_body["ViewPNR"]["Exceptions"][0]["ExceptionDescription"]];
                    }
                }
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return ["error" => "Unkown Error. Commit PNR request"];
    } //processPayment

    public function getFlightFareTypes($params)
    {
        $fares = FlyDubaiSupport::getFareCombinations($params["payload"], $params["departure_lfid"], $params["departure_from_date"], $params["return_lfid"] ?? null, $params["return_from_date"] ?? null);
        return [
            "fares" => $fares,
            "payload" => $params['payload']
        ];
    }

    public function confirmationStep($params)
    {
        try{
            $params2["access_token"] = $params["access_token"] ?? null;
            $params2["session_timestamp"] = $params["session_timestamp"] ?? null;
            $params2["passengers"] = $params["passengers"];
            $params2["has_return"] = strtolower($params["type"]) == "roundtrip";
            $summary = $this->summaryPNR($params['payload2'], $params2);
            if(isset($summary["error"])){
                return $summary;
            }
            $resp = FlyDubaiSupport::processSummaryResults($summary, $params["ancillaries_payload"]);
            $total_f = 0;
            $base_f = 0;
            foreach($params['payload2']["flightGroups"] as $flg){
                foreach($flg["fareBrands"] as $fb){
                    foreach($fb["fareInfos"] as $fi){
                        foreach($fi["paxFareInfos"] as $pfi){
                            $total_f += $pfi["totalFare"];
                            $base_f += $pfi["baseFare"];
                        }
                    }
                }
            }
            $resp["total_f"] = $total_f;
            $resp["base_f"] = $base_f;
            $resp["tax_f"] = $total_f - $base_f;
            
            return $resp;
        }catch(Exception $e){
            return [
                "error" => $e.getMessage()
            ];
        }
    }

    public function processBookingStep($params, $payment, $just_payment = false)
    {
        $commit = $just_payment ? (is_array($params["pnr_payload"]) ? $params["pnr_payload"] : \json_decode($params["pnr_payload"], true)) : $this->commitPNR($params);
        if (isset($commit["error"])) {
            return $commit;
        } else {
            $response = $payment ? $this->processPayment($commit, $params, null) : $commit;
            return isset($response["error"]) ? $response : FlyDubaiSupport::processFOPResults($response, $params["ancillaries_payload"], $payment);
        }
    } //processBookingStep

    public function retrievePnr($params)
    {
        if ($token = self::authenticate()) {
            $session_timestamp = time();
            $jsonRequest = FlyDubaiSupport::generateRetrievePnrRequest($params);
            $response = Http::asJson()->withHeaders(["Accept-Encoding", "gzip,deflate"])->withToken($token)
            ->post(
                config("airlines.FlyDubai.urls.retrieve_pnr"),
                $jsonRequest
            );
            Storage::disk('local')->put("FlyDubai_Logs".DIRECTORY_SEPARATOR.\date("Ymd").DIRECTORY_SEPARATOR.$session_timestamp.DIRECTORY_SEPARATOR."retrieve_response.json", json_encode($response->body()));
            if ($response->successful()) {
                $response_body = json_decode($response->body(), true);
                if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 0) {
                    return [
                        "pnr_payload" => $response_body,
                        "results" => FlyDubaiSupport::processFOPResults($response_body, null, false),
                        "session_timestamp" => $session_timestamp,
                        "access_token" => $token
                    ];
                } else if (isset($response_body["Exceptions"]) && (int)$response_body["Exceptions"][0]["ExceptionCode"] == 800) {
                    return ["error" => "Security token expired..."];
                } else {
                    return ["error" => $response_body["Exceptions"][0]["ExceptionDescription"]];
                }
            }
        }
    }

    public function retrieveAndPay($data)
    {
        $resl["error"] = [];
        $resl["success"] = false;

        $params["booking_ref"] = $data["bookref"];
        $pnr = $this->retrievePnr($params);
        if(isset($pnr["error"])){
            $resl["error"][] = $pnr["error"];
        }else{
            $amount = calculateMarkupPrice($pnr["results"]["amount"], 'FlyDubai');
            $total_amount = $amount + $pnr["results"]["taxes"];
            if(auth()->user()->type == 'agency' && $total_amount > auth()->user()->balance) {
                $resl["error"][] = 'Insufficient Balance';
            }else{
                $params["pnr_payload"] = $pnr["pnr_payload"];
                $params["ancillaries_payload"] = null;
                $params["access_token"] = $pnr["access_token"];
                $params["session_timestamp"] = $pnr["session_timestamp"];
                $results = $this->processBookingStep($params, true, true);
                if(isset($results["error"])){
                    $resl["error"][] = $results["error"];
                }else{
                    $booking = AirlineBooking::findOrFail($data["booking_id"]);
                    $booking->status = "confirmed";
                    try {
                        $booking->save();
                        if(!$this->hold) {
                            Integration::find(2)->decrement('balance', ($pnr["results"]["amount"] + $pnr["results"]["taxes"]));
                            if(auth()->user()->type == 'agency') {
                                $user = auth()->user();
                                $user->balance -= $total_amount;
                                $user->save();
                            }
                            $resl["success"] = true;
                        }
                        Mail::send(new NewAirlineBookingEmail('FlyDubai', $booking->id, auth()->user()->email ));
                    } catch(\Exception $e) {
                        $this->balance_error = $e->getMessage();
                    }
                }
            }
        }
        return $resl;
    }//retrieveAndPay
}
