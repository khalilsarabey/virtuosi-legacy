<?php

namespace App\Services\Airlines\FlyDubai;

use Exception;

class FlyDubaiSupport
{
    public static function generateSearchRequest($params)
    {
        $request = json_decode(file_get_contents(app_path('Services/Airlines/FlyDubai/JSON/FareQuoteRequest.json')), true);
        $request_base = &$request["RetrieveFareQuoteDateRange"]["RetrieveFareQuoteDateRangeRequest"];

        $request_base["CurrencyOfFareQuote"] = config("airlines.FlyDubai.currency");
        $request_base["IataNumberOfRequestor"] = config("airlines.FlyDubai.IATA");
        $request_base["HistoricUserName"] = config("airlines.FlyDubai.username");
        $request_base["FullInBoundDate"] = date("d/m/Y", strtotime($params["itinerary"]["departure"]["date"]));
        $request_base["FullOutBoundDate"] = date("d/m/Y", strtotime($params["itinerary"]["departure"]["date"]));

        $orig_dest_arr = &$request_base["FareQuoteDetails"]["FareQuoteDetailDateRange"];
        $return_segment = $orig_dest_arr[0];
        $orig_dest_arr[0]["Origin"] = $params["itinerary"]["departure"]["from"];
        $orig_dest_arr[0]["Destination"] = $params["itinerary"]["departure"]["to"];
        $orig_dest_arr[0]["DateOfDepartureStart"] = $params["flexible_dates"] ? date("Y-m-d", strtotime("-".$params["flexibility_days"]." day", strtotime($params["itinerary"]["departure"]["date"]))) . "T00:00:00" : date("Y-m-d", strtotime($params["itinerary"]["departure"]["date"])) . "T00:00:00";
        $orig_dest_arr[0]["DateOfDepartureEnd"] = $params["flexible_dates"] ? date("Y-m-d", strtotime("+".$params["flexibility_days"]." day", strtotime($params["itinerary"]["departure"]["date"]))) . "T23:59:00" : date("Y-m-d", strtotime($params["itinerary"]["departure"]["date"])) . "T23:59:00";

        $passenger_quote = $orig_dest_arr[0]["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][0];
        $orig_dest_arr[0]["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][0]["PassengerTypeID"] = 1;
        $orig_dest_arr[0]["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][0]["TotalSeatsRequired"] = (int)$params["itinerary"]["departure"]["adult"];

        $child_count_d = (int)$params["itinerary"]["departure"]["child"];
        if($child_count_d > 0){
            $child_quote = $passenger_quote;
            $child_quote["PassengerTypeID"] = 6;
            $child_quote["TotalSeatsRequired"] = $child_count_d;
            $orig_dest_arr[0]["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][] = $child_quote;
        }

        $infant_count_d = (int)$params["itinerary"]["departure"]["infant"];
        if($infant_count_d > 0){
            $infant_quote = $passenger_quote;
            $infant_quote["PassengerTypeID"] = 5;
            $infant_quote["TotalSeatsRequired"] = $infant_count_d;
            $orig_dest_arr[0]["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][] = $infant_quote;
        }

        if (isset($params["itinerary"]["return"]) && $params["itinerary"]["return"]["from"]) {
            $request_base["FullInBoundDate"] = date("d/m/Y", strtotime($params["itinerary"]["return"]["date"]));
            $return_segment["Origin"] = $params["itinerary"]["return"]["from"];
            $return_segment["Destination"] = $params["itinerary"]["return"]["to"];
            $return_segment["DateOfDepartureStart"] = $params["flexible_dates"] ? date("Y-m-d", strtotime("-".$params["flexibility_days"]." day", strtotime($params["itinerary"]["return"]["date"]))) . "T00:00:00" : date("Y-m-d", strtotime($params["itinerary"]["return"]["date"])) . "T00:00:00";
            $return_segment["DateOfDepartureEnd"] = $params["flexible_dates"] ? date("Y-m-d", strtotime("+".$params["flexibility_days"]." day", strtotime($params["itinerary"]["return"]["date"]))) . "T23:59:00" : date("Y-m-d", strtotime($params["itinerary"]["return"]["date"])) . "T23:59:00";
            $return_segment["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][0]["PassengerTypeID"] = 1;
            $return_segment["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][0]["TotalSeatsRequired"] = (int)$params["itinerary"]["return"]["adult"];

            $child_count_r = (int)$params["itinerary"]["return"]["child"];
            if($child_count_r > 0){
                $child_quote = $passenger_quote;
                $child_quote["PassengerTypeID"] = 6;
                $child_quote["TotalSeatsRequired"] = $child_count_d;
                $return_segment["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][] = $child_quote;
            }

            $infant_count_r = (int)$params["itinerary"]["return"]["infant"];
            if($infant_count_r > 0){
                $infant_quote = $passenger_quote;
                $infant_quote["PassengerTypeID"] = 5;
                $infant_quote["TotalSeatsRequired"] = $infant_count_r;
                $return_segment["FareQuoteRequestInfos"]["FareQuoteRequestInfo"][] = $infant_quote;
            }

            $orig_dest_arr[] = $return_segment;
        }

        return $request;
    } //generateSearchRequest

    public static function processSearchResults($results, $dep_from, $dep_to): array
    {
        $array['payload'] = $results;
        $array["departure"] = [];
        $array["return"] = [];

        if(!isset($results["RetrieveFareQuoteDateRangeResponse"])){
            return $array;
        }

        $segments = $results["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["FlightSegments"]["FlightSegment"];
        $segDetails = $results["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["SegmentDetails"]["SegmentDetail"];
        $legDetails = $results["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["LegDetails"]["LegDetail"];

        if (count($legDetails) == 0) {
            return $array;
        }

        foreach ($segments as $seg) {
            $lfid = $seg["LFID"];
            $seg_d = array_search($lfid, array_column($segDetails, "LFID"));
            $obj["lfid"] = $lfid;
            $obj["flight_number"] = $segDetails[$seg_d]["FlightNum"];
            $obj["origin"] = $segDetails[$seg_d]["Origin"];
            $obj["destination"] = $segDetails[$seg_d]["Destination"];
            $obj["departure_time"] = $seg["DepartureDate"];
            $obj["arrival_time"] = $seg["ArrivalDate"];
            $obj["stops"] = $segDetails[$seg_d]["Stops"];
            $obj["stop_details"] = [];
            $obj["aircraft"] = $segDetails[$seg_d]["AircraftType"] ?? "";
            //$obj["price"] = $seg["FareTypes"]["FareType"][0]["FareInfos"]["FareInfo"][0]["Pax"][0]["FareAmtInclTax"];

            $price = 0;
            $base_fare = 0;
            foreach ($seg["FareTypes"]["FareType"] as $ftp) {
                $fprice = 0;
                $fbase = 0;
                foreach ($ftp["FareInfos"]["FareInfo"] as $finf) {
                    foreach ($finf["Pax"] as $fpax) {
                        $fprice += (((float)$fpax["BaseFareAmtInclTax"]) * ((int)$fpax["PaxCount"]));
                        $fbase += (((float)$fpax["BaseFareAmtNoTaxes"]) * ((int)$fpax["PaxCount"]));
                    }
                }
                if ($price == 0 || $price > $fprice) {
                    $price = $fprice;
                    $base_fare = $fbase;
                }
            }
            $obj["price"] = $price;
            $obj["base_fare"] = $base_fare;
            $obj["taxes"] = $price - $base_fare;

            $legs = $seg["FlightLegDetails"]["FlightLegDetail"];
            foreach ($legs as $leg) {
                $leg_d_i = array_search($leg["PFID"], array_column($legDetails, "PFID"));
                $leg_d = $legDetails[$leg_d_i];
                $st["pfid"] = $leg["PFID"];
                $st["origin"] = $leg_d["Origin"];
                $st["destination"] = $leg_d["Destination"];
                $st["departure_time"] = $leg_d["DepartureDate"];
                $st["arrival_time"] = $leg_d["ArrivalDate"];
                $st["flight_number"] = $leg_d["FlightNum"];
                $st["carrier"] = $leg_d["OperatingCarrier"];
                $obj["stop_details"][] = $st;
            }

            if (strtolower($obj["origin"]) == strtolower($dep_from) && strtolower($obj["destination"]) == strtolower($dep_to)) {
                $array["departure"][] = $obj;
            } else {
                $array["return"][] = $obj;
            }
        }
        return $array;
    } //processSearchResults

    public static function getFareTypes($payload, $flight_num, $departure_date, $sol_id = null)
    {
        $flight_lfid = null;
        $flight_fare = [
            "T1" => [
                "sol_id" => 0,
                "lfid" => 0,
                "type_id" => 0,
                "name" => "LITE",
                "amount" => 0,
                "taxes" => 0,
                "amount_no_tax" => 0,
                "carryon" => "7 Kg Carry-on Baggage",
                "bag" => "NO",
                "seat" => "NO",
                "meal" => "NO",
                "modification" => "NO",
                "cancellation" => "NO"
            ],
            "T2" => [
                "sol_id" => 0,
                "lfid" => 0,
                "type_id" => 0,
                "name" => "VALUE",
                "amount" => 0,
                "taxes" => 0,
                "amount_no_tax" => 0,
                "carryon" => "7 Kg Carry-on Baggage",
                "bag" => "NO",
                "seat" => "NO",
                "meal" => "NO",
                "modification" => "Penalties Apply",
                "cancellation" => "Penalties Apply"
            ],
            "T3" => [
                "sol_id" => 0,
                "lfid" => 0,
                "type_id" => 0,
                "name" => "FLEX",
                "amount" => 0,
                "taxes" => 0,
                "amount_no_tax" => 0,
                "carryon" => "7 Kg Carry-on Baggage",
                "bag" => "NO",
                "seat" => "NO",
                "meal" => "NO",
                "modification" => "YES",
                "cancellation" => "Penalties Apply"
            ]
        ];

        if (!isset($payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["Combinability"]) || $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["Combinability"] == null) {
            $sol_id = null;
        }

        $accepted_sol = [];
        if ($sol_id) {
            $combs = $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["Combinability"]["BS"];
            foreach ($combs as $comb) {
                if (in_array($sol_id, $comb["SolnRef"])) {
                    $clone_comb = $comb["SolnRef"];
                    array_splice($clone_comb, array_search($sol_id, $clone_comb), 1);
                    $accepted_sol[] = $clone_comb[0];
                }
            }
        }

        $segment_details = $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["SegmentDetails"]["SegmentDetail"];
        $flight_segments = $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["FlightSegments"]["FlightSegment"];

        foreach ($segment_details as $seg) {
            if ($seg["FlightNum"] == $flight_num && $seg["DepartureDate"] == $departure_date) {
                $flight_lfid = $seg["LFID"];
            }
        }

        foreach ($flight_segments as $seg) {
            if ($flight_lfid != $seg["LFID"]) {
                continue;
            }

            $fare_types = $seg["FareTypes"]["FareType"];
            foreach ($fare_types as $ft) {
                if ($sol_id) {
                    if (!in_array($ft["SolnId"], $accepted_sol)) {
                        continue;
                    }
                }

                $total_amount = 0;
                $total_tax = 0;
                $total_no_tax = 0;
                foreach ($ft["FareInfos"]["FareInfo"] as $fi) {
                    foreach ($fi["Pax"] as $pax) {
                        $total_amount += ($pax["FareAmtInclTax"] * $pax["PaxCount"]);
                        $total_no_tax += ($pax["FareAmtNoTaxes"] * $pax["PaxCount"]);
                        $total_tax = ($total_amount - $total_no_tax);
                    }
                }

                $bag = "NO";
                $seat = "NO";
                $meal = "NO";

                if(isset($ft["ApplicableServices"]["ServiceIDs"])) {
                    foreach ($ft["ApplicableServices"]["ServiceIDs"] as $srv) {
                        if ($srv["ID"] == 11186) {
                            $meal = "Standard meal";
                        }
                        if ($srv["ID"] == 11187) {
                            $seat = "Included seat";
                        }
                    }
                }

                foreach ($ft["FareInfos"]["FareInfo"][0]["Pax"][0]["ApplicableTaxDetails"]["ApplicableTaxDetail"] as $tax) {
                    if ($tax["TaxID"] == 8947) {
                        $bag = "30kg BAG";
                    }
                    if ($tax["TaxID"] == 6544) {
                        $bag = "40kg BAG";
                    }
                }

                if ($ft["FareTypeName"] == "LITE" && ($flight_fare["T1"]["amount"] > $total_amount || $flight_fare["T1"]["amount"] == 0)) {
                    $flight_fare["T1"]["sol_id"] = $ft["SolnId"];
                    $flight_fare["T1"]["lfid"] = $flight_lfid;
                    $flight_fare["T1"]["type_id"] = $ft["FareTypeID"];
                    $flight_fare["T1"]["amount"] = $total_amount;
                    $flight_fare["T1"]["taxes"] = $total_tax;
                    $flight_fare["T1"]["amount_no_tax"] = $total_no_tax;
                    $flight_fare["T1"]["bag"] = $bag;
                    $flight_fare["T1"]["seat"] = $seat;
                    $flight_fare["T1"]["meal"] = $meal;
                }
                if ($ft["FareTypeName"] == "VALUE" && ($flight_fare["T2"]["amount"] > $total_amount || $flight_fare["T2"]["amount"] == 0)) {
                    $flight_fare["T2"]["sol_id"] = $ft["SolnId"];
                    $flight_fare["T2"]["lfid"] = $flight_lfid;
                    $flight_fare["T2"]["type_id"] = $ft["FareTypeID"];
                    $flight_fare["T2"]["amount"] = $total_amount;
                    $flight_fare["T2"]["taxes"] = $total_tax;
                    $flight_fare["T2"]["amount_no_tax"] = $total_no_tax;
                    $flight_fare["T2"]["bag"] = $bag;
                    $flight_fare["T2"]["seat"] = $seat;
                    $flight_fare["T2"]["meal"] = $meal;
                }
                if ($ft["FareTypeName"] == "FLEX" && ($flight_fare["T3"]["amount"] > $total_amount || $flight_fare["T3"]["amount"] == 0)) {
                    $flight_fare["T3"]["sol_id"] = $ft["SolnId"];
                    $flight_fare["T3"]["lfid"] = $flight_lfid;
                    $flight_fare["T3"]["type_id"] = $ft["FareTypeID"];
                    $flight_fare["T3"]["amount"] = $total_amount;
                    $flight_fare["T3"]["taxes"] = $total_tax;
                    $flight_fare["T3"]["amount_no_tax"] = $total_no_tax;
                    $flight_fare["T3"]["bag"] = $bag;
                    $flight_fare["T3"]["seat"] = $seat;
                    $flight_fare["T3"]["meal"] = $meal;
                }
            }

            break;
        }

        return $flight_fare;
    } //getFareTypes

    public static function getFareCombinations($payload, $departure_lfid, $departure_date, $return_lfid = null, $return_date = null)
    {
        $flight_fares = [
            [
                "departure_lfid" => 0,
                "return_lfid" => 0,
                "departure_type_id" => 0, //FareTypeID
                "return_type_id" => 0,
                "departure_sol_id" => 0,
                "return_sol_id" => 0,
                "name" => "LITE",
                "amount" => 0,
                "taxes" => 0,
                "amount_no_tax" => 0,
                "services" => [],
            ]
        ];

        $flight_fares = [];

        $combinable = false;
        if (isset($payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["Combinability"]) && $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["Combinability"] != null) {
            $combinable = true;
        }

        $tax_details = $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["TaxDetails"]["TaxDetail"];
        $service_details = $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["ServiceDetails"]["ServiceDetail"];
        $flight_segments = $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["FlightSegments"]["FlightSegment"];

        $departure_segment = null;
        $return_segment = null;
        foreach ($flight_segments as $seg) {
            if ($departure_date == $seg["DepartureDate"] && $departure_lfid == $seg["LFID"]) {
                $departure_segment = $seg;
            }
            if ($return_date && $return_date == $seg["DepartureDate"] && $return_lfid == $seg["LFID"]) {
                $return_segment = $seg;
            }
        }

        $departure_fare_types = $departure_segment["FareTypes"]["FareType"];
        foreach ($departure_fare_types as $dep_f) {
            $amount = 0;
            $amount_no_tax = 0;
            $return_type_id = 0;
            $return_sol_id = 0;
            $return_fare = null;
            if ($return_segment) {
                $return_fare_types = $return_segment["FareTypes"]["FareType"];
                foreach ($return_fare_types as $ret_f) {
                    if ($combinable) {
                        $combs = $payload["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["Combinability"]["BS"];
                        foreach ($combs as $comb) {
                            if (!array_diff([$dep_f["SolnId"], $ret_f["SolnId"]], $comb["SolnRef"]) && $dep_f["FareTypeName"] == $ret_f["FareTypeName"]) {
                                $return_fare = $ret_f;
                                $return_type_id = $ret_f["FareTypeID"];
                                $return_sol_id = $ret_f["SolnId"];
                                foreach ($ret_f["FareInfos"]["FareInfo"] as $fi) {
                                    foreach ($fi["Pax"] as $pax) {
                                        $amount += ($pax["FareAmtInclTax"] * $pax["PaxCount"]);
                                        $amount_no_tax += ($pax["FareAmtNoTaxes"] * $pax["PaxCount"]);
                                    }
                                }

                                foreach ($dep_f["FareInfos"]["FareInfo"] as $dfi) {
                                    foreach ($dfi["Pax"] as $dpax) {
                                        $amount += ($dpax["FareAmtInclTax"] * $dpax["PaxCount"]);
                                        $amount_no_tax += ($dpax["FareAmtNoTaxes"] * $dpax["PaxCount"]);
                                    }
                                }
                            }
                        }
                    } else {
                        if ($dep_f["FareTypeName"] == $ret_f["FareTypeName"]) {
                            $return_fare = $ret_f;
                            $return_type_id = $ret_f["FareTypeID"];
                            $return_sol_id = $ret_f["SolnId"];
                            foreach ($ret_f["FareInfos"]["FareInfo"] as $fi) {
                                foreach ($fi["Pax"] as $pax) {
                                    $amount += ($pax["FareAmtInclTax"] * $pax["PaxCount"]);
                                    $amount_no_tax += ($pax["FareAmtNoTaxes"] * $pax["PaxCount"]);
                                }
                            }

                            foreach ($dep_f["FareInfos"]["FareInfo"] as $dfi) {
                                foreach ($dfi["Pax"] as $dpax) {
                                    $amount += ($dpax["FareAmtInclTax"] * $dpax["PaxCount"]);
                                    $amount_no_tax += ($dpax["FareAmtNoTaxes"] * $dpax["PaxCount"]);
                                }
                            }
                        }
                    }
                }
            } else {
                foreach ($dep_f["FareInfos"]["FareInfo"] as $dfi) {
                    foreach ($dfi["Pax"] as $dpax) {
                        $amount += ($dpax["FareAmtInclTax"] * $dpax["PaxCount"]);
                        $amount_no_tax += ($dpax["FareAmtNoTaxes"] * $dpax["PaxCount"]);
                    }
                }
            }

            if ($amount > 0) {
                $departure_services = [
                    "7 Kg Carry-on Baggage"
                ];
                $departure_services_codes = [];
                $return_services = [];
                $return_services_codes = [];

                if(isset($dep_f["ApplicableServices"]["ServiceIDs"])) {
                    foreach ($dep_f["ApplicableServices"]["ServiceIDs"] as $dserv) {
                        foreach ($service_details as $serv_d) {
                            if ($dserv["ID"] == $serv_d["ID"]) {
                                $departure_services[] = $serv_d["Description"];
                                $departure_services_codes[] = $serv_d["Code"];
                                break;
                            }
                        }
                    }
                }

                foreach ($dep_f["FareInfos"]["FareInfo"] as $finf) {
                    foreach ($finf["Pax"] as $dpax) {
                        if ($dpax["PTCID"] == 1) {
                            foreach ($dpax["ApplicableTaxDetails"]["ApplicableTaxDetail"] as $ptax) {
                                if ($ptax["Amt"] == 0) {
                                    foreach ($tax_details as $tdet) {
                                        if ($tdet["TaxID"] == $ptax["TaxID"]) {
                                            $departure_services[] = $tdet["TaxDesc"];
                                            $departure_services_codes[] = $tdet["TaxCode"];
                                            break;
                                        }
                                    }
                                }
                            }
                            break;
                        }
                    }
                    break;
                }

                if ($return_fare) {
                    $return_services[] = "7 Kg Carry-on Baggage";
                    if(isset($return_fare["ApplicableServices"]["ServiceIDs"])) {
                        foreach ($return_fare["ApplicableServices"]["ServiceIDs"] as $serv) {
                            foreach ($service_details as $serv_d) {
                                if ($serv["ID"] == $serv_d["ID"]) {
                                    $return_services[] = $serv_d["Description"];
                                    $return_services_codes[] = $serv_d["Code"];
                                }
                            }
                        }
                    }

                    foreach ($return_fare["FareInfos"]["FareInfo"] as $finf) {
                        foreach ($finf["Pax"] as $dpax) {
                            if ($dpax["PTCID"] == 1) {
                                foreach ($dpax["ApplicableTaxDetails"]["ApplicableTaxDetail"] as $ptax) {
                                    if ($ptax["Amt"] == 0) {
                                        foreach ($tax_details as $tdet) {
                                            if ($tdet["TaxID"] == $ptax["TaxID"]) {
                                                $return_services[] = $tdet["TaxDesc"];
                                                $return_services_codes[] = $tdet["TaxCode"];
                                                break;
                                            }
                                        }
                                    }
                                }
                                break;
                            }
                        }
                        break;
                    }
                }

                $flight_fares[] = [
                    "departure_lfid" => $departure_segment["LFID"],
                    "return_lfid" => $return_segment["LFID"] ?? 0,
                    "departure_type_id" => $dep_f["FareTypeID"],
                    "return_type_id" => $return_type_id,
                    "departure_sol_id" => $dep_f["SolnId"],
                    "return_sol_id" => $return_sol_id,
                    "name" => $dep_f["FareTypeName"],
                    "amount" => $amount,
                    "taxes" => ($amount - $amount_no_tax),
                    "amount_no_tax" => $amount_no_tax,
                    "services" => [
                        "departure" => $departure_services,
                        "return" => $return_services,
                        "departure_codes" => $departure_services_codes,
                        "return_codes" => $return_services_codes,
                    ],
                ];
            }
        } //foreach departure_fare_types

        $cheapest_lite_index = -1;
        $cheapest_lite_amount = -1;
        $cheapest_value_index = -1;
        $cheapest_value_amount = -1;
        $cheapest_flex_index = -1;
        $cheapest_flex_amount = -1;
        $cheapest_business_index = -1;
        $cheapest_business_amount = -1;

        foreach ($flight_fares as $ind => $flf) {
            if (strtolower($flf["name"]) == "lite") {
                if ($cheapest_lite_amount > (float)$flf["amount"] || $cheapest_lite_amount ==  -1) {
                    $cheapest_lite_amount = (float)$flf["amount"];
                    $cheapest_lite_index = $ind;
                }
            }
            if (strtolower($flf["name"]) == "value") {
                if ($cheapest_value_amount > (float)$flf["amount"] || $cheapest_value_amount ==  -1) {
                    $cheapest_value_amount = (float)$flf["amount"];
                    $cheapest_value_index = $ind;
                }
            }
            if (strtolower($flf["name"]) == "flex") {
                if ($cheapest_flex_amount > (float)$flf["amount"] || $cheapest_flex_amount ==  -1) {
                    $cheapest_flex_amount = (float)$flf["amount"];
                    $cheapest_flex_index = $ind;
                }
            }
            if (strtolower($flf["name"]) == "business") {
                if ($cheapest_business_amount > (float)$flf["amount"] || $cheapest_business_amount ==  -1) {
                    $cheapest_business_amount = (float)$flf["amount"];
                    $cheapest_business_index = $ind;
                }
            }
        }

        $flight_fares_filtered = [];

        if ($cheapest_lite_index > -1) {
            $flight_fares_filtered[] = $flight_fares[$cheapest_lite_index];
        }
        if ($cheapest_value_index > -1) {
            $flight_fares_filtered[] = $flight_fares[$cheapest_value_index];
        }
        if ($cheapest_flex_index > -1) {
            $flight_fares_filtered[] = $flight_fares[$cheapest_flex_index];
        }
        if ($cheapest_business_index > -1) {
            $flight_fares_filtered[] = $flight_fares[$cheapest_business_index];
        }

        return $flight_fares_filtered;
    } //getFareCombinations

    public static function generateAddToCartRequest($params, $selected_departure = [], $selected_return = [])
    {
        //Params is the response from Search Flights
        $request = json_decode(file_get_contents(app_path('Services/Airlines/FlyDubai/JSON/AddToCartRequest.json')), true);
        $request["currency"] = config("airlines.FlyDubai.currency");
        $request["IATA"] = config("airlines.FlyDubai.IATA");

        $response_segments = $params["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["FlightSegments"]["FlightSegment"];
        $response_segments_details = $params["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["SegmentDetails"]["SegmentDetail"];
        $leg_details = $params["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["LegDetails"]["LegDetail"];

        $tax_details = $params["RetrieveFareQuoteDateRangeResponse"]["RetrieveFareQuoteDateRangeResult"]["TaxDetails"]["TaxDetail"];

        $return = $request["originDestinations"][0];
        if (!empty($selected_departure)) {
            //$seg_key = array_search($selected_departure["lfid"], array_column($response_segments, "LFID"));
            //$seg_details_key = array_search($selected_departure["lfid"], array_column($response_segments_details, "LFID"));

            $seg_key = null;
            foreach($response_segments as $skey => $sseg){
                if($sseg["LFID"] == $selected_departure["lfid"] && $sseg["DepartureDate"] == $selected_departure["departure_date"]){
                    $seg_key = $skey;
                    break;
                }
            }

            $seg_details_key = null;
            foreach($response_segments_details as $sdkey => $dseg){
                if($dseg["LFID"] == $selected_departure["lfid"] && $dseg["DepartureDate"] == $selected_departure["departure_date"]){
                    $seg_details_key = $sdkey;
                    break;
                }
            }

            $departure = &$request["originDestinations"][0];
            $departure["odID"] = $selected_departure["lfid"];
            $departure["origin"] = $response_segments_details[$seg_details_key]["Origin"];
            $departure["destination"] = $response_segments_details[$seg_details_key]["Destination"];
            $departure["flightNum"] = $response_segments_details[$seg_details_key]["FlightNum"];
            $departure["depDate"] = $response_segments_details[$seg_details_key]["DepartureDate"];

            $leg_part = $departure["segmentDetails"][0];
            $departure["segmentDetails"] = [];
            foreach ($response_segments[$seg_key]["FlightLegDetails"]["FlightLegDetail"] as $leg) {
                foreach ($leg_details as $leg_d) {
                    if ($leg_d["PFID"] == $leg["PFID"] && $leg_d["DepartureDate"] == $leg["DepartureDate"]) {
                        $lg = $leg_part;
                        $lg["segmentID"] = $leg_d["PFID"];
                        $lg["origin"] = $leg_d["Origin"];
                        $lg["destination"] = $leg_d["Destination"];
                        $lg["depDate"] = $leg_d["DepartureDate"];
                        $lg["arrDate"] = $leg_d["ArrivalDate"];
                        $lg["operCarrier"] = $leg_d["OperatingCarrier"];
                        $lg["operFlightNum"] = $leg_d["FlightNum"];
                        $lg["mrktCarrier"] = $leg_d["MarketingCarrier"];
                        $lg["mrktFlightNum"] = $leg_d["MarketingFlightNum"];
                        $lg["bookingCodes"] = [];
                        $departure["segmentDetails"][] = $lg;
                    }
                }
                //$leg_key = array_search($leg["PFID"], array_column($leg_details, "PFID"));
            }

            $booking_codes_arr = [];
            foreach ($response_segments[$seg_key]["FareTypes"]["FareType"] as $fare) {
                if ($fare["FareTypeID"] == $selected_departure["fare_type_id"] && $fare["SolnId"] == $selected_departure["soln_id"]) {
                    $departure["fareBrand"][0]["fareBrandID"] = $fare["FareTypeID"];
                    $departure["fareBrand"][0]["fareBrandName"] = $fare["FareTypeName"];
                    $cloned_pax_fare_info = $departure["fareBrand"][0]["fareInfos"][0]["paxFareInfos"][0];
                    //array_diff($departure["fareBrand"][0]["fareInfos"][0]["paxFareInfos"], $departure["fareBrand"][0]["fareInfos"][0]["paxFareInfos"]);
                    $departure["fareBrand"][0]["fareInfos"][0]["paxFareInfos"] = [];
                    $cloned_fare_info = $departure["fareBrand"][0]["fareInfos"][0];
                    //array_diff($departure["fareBrand"][0]["fareInfos"], $departure["fareBrand"][0]["fareInfos"]);
                    $departure["fareBrand"][0]["fareInfos"] = [];
                    foreach ($fare["FareInfos"]["FareInfo"] as $fare_info) {
                        $fare_info_e = $cloned_fare_info;
                        foreach ($fare_info["Pax"] as $pax) {
                            $pax_info = $cloned_pax_fare_info;
                            $pax_info["fareID"] = $pax["FareID"];
                            $pax_info["ID"] = $pax["ID"];
                            $pax_info["FBC"] = $pax["FBCode"];
                            $pax_info["fareClass"] = $pax["FCCode"];
                            $pax_info["cabin"] = $pax["Cabin"];
                            $pax_info["baseFare"] = $pax["BaseFareAmtNoTaxes"];
                            $pax_info["ruleID"] = $pax["RuleId"];
                            $pax_info["originalFare"] = $pax["OriginalFare"];
                            $pax_info["totalFare"] = $pax["FareAmtInclTax"];
                            $pax_info["PTC"] = $pax["PTCID"];
                            $pax_info["seatAvailability"] = $pax["SeatsAvailable"];
                            $pax_info["infantAvailability"] = $pax["InfantSeatsAvailable"];
                            $pax_info["secureHash"] = $pax["hashcode"];
                            $pax_info["fareCarrier"] = $pax["FareCarrier"];
                            //$pax_info["applicableTaxDetails"] = $pax["ApplicableTaxDetails"]["ApplicableTaxDetail"];

                            $tax_arr = [];
                            foreach ($pax["ApplicableTaxDetails"]["ApplicableTaxDetail"] as $ptax) {
                                $tax_p = [];
                                foreach ($tax_details as $tax_d) {
                                    if ($tax_d["TaxID"] == $ptax["TaxID"]) {
                                        $tax_p["Amt"] = $ptax["Amt"];
                                        $tax_p["TaxID"] = $ptax["TaxID"];
                                        $tax_p["TaxCode"] = $tax_d["TaxCode"];
                                        continue;
                                    }
                                }
                                $tax_arr[] = $tax_p;
                            }
                            $pax_info["applicableTaxDetails"] = $tax_arr;

                            $fare_info_e["paxFareInfos"][] = $pax_info;

                            $booking_codes_arr[$pax["FCCode"]][$pax["Cabin"]][] = $pax["ID"];
                        }
                        $departure["fareBrand"][0]["fareInfos"][] = $fare_info_e;
                    }
                    break;
                }
            }

            $booking_codes = [];
            foreach ($booking_codes_arr as $fc_code => $val) {
                foreach ($val as $cab => $pax_ids) {
                    $booking_code["fareClass"] = $fc_code;
                    $booking_code["cabin"] = $cab;
                    $booking_code["paxID"] = $pax_ids;
                    $booking_codes[] = $booking_code;
                }
            }
            for ($i = 0; $i < count($departure["segmentDetails"]); $i++) {
                $departure["segmentDetails"][$i]["bookingCodes"] = $booking_codes;
            }
        } //departure

        if (!empty($selected_return)) {
            //$seg_key = array_search($selected_return["lfid"], array_column($response_segments, "LFID"));
            //$seg_details_key = array_search($selected_return["lfid"], array_column($response_segments_details, "LFID"));

            $seg_key = null;
            foreach($response_segments as $skey => $sseg){
                if($sseg["LFID"] == $selected_return["lfid"] && $sseg["DepartureDate"] == $selected_return["departure_date"]){
                    $seg_key = $skey;
                    break;
                }
            }

            $seg_details_key = null;
            foreach($response_segments_details as $sdkey => $dseg){
                if($dseg["LFID"] == $selected_return["lfid"] && $dseg["DepartureDate"] == $selected_return["departure_date"]){
                    $seg_details_key = $sdkey;
                    break;
                }
            }

            $return["odID"] = $selected_return["lfid"];
            $return["origin"] = $response_segments_details[$seg_details_key]["Origin"];
            $return["destination"] = $response_segments_details[$seg_details_key]["Destination"];
            $return["flightNum"] = $response_segments_details[$seg_details_key]["FlightNum"];
            $return["depDate"] = $response_segments_details[$seg_details_key]["DepartureDate"];

            $leg_part = $return["segmentDetails"][0];
            $return["segmentDetails"] = [];
            foreach ($response_segments[$seg_key]["FlightLegDetails"]["FlightLegDetail"] as $leg) {
                foreach ($leg_details as $leg_d) {
                    if ($leg_d["PFID"] == $leg["PFID"] && $leg_d["DepartureDate"] == $leg["DepartureDate"]) {
                        $lg = $leg_part;
                        $lg["segmentID"] = $leg_d["PFID"];
                        $lg["origin"] = $leg_d["Origin"];
                        $lg["destination"] = $leg_d["Destination"];
                        $lg["depDate"] = $leg_d["DepartureDate"];
                        $lg["arrDate"] = $leg_d["ArrivalDate"];
                        $lg["operCarrier"] = $leg_d["OperatingCarrier"];
                        $lg["operFlightNum"] = $leg_d["FlightNum"];
                        $lg["mrktCarrier"] = $leg_d["MarketingCarrier"];
                        $lg["mrktFlightNum"] = $leg_d["MarketingFlightNum"];
                        $lg["bookingCodes"] = [];
                        $return["segmentDetails"][] = $lg;
                    }
                }
            }

            $booking_codes_arr = [];
            foreach ($response_segments[$seg_key]["FareTypes"]["FareType"] as $fare) {
                if ($fare["FareTypeID"] == $selected_return["fare_type_id"] && $fare["SolnId"] == $selected_return["soln_id"]) {
                    $return["fareBrand"][0]["fareBrandID"] = $fare["FareTypeID"];
                    $return["fareBrand"][0]["fareBrandName"] = $fare["FareTypeName"];
                    $cloned_pax_fare_info = $return["fareBrand"][0]["fareInfos"][0]["paxFareInfos"][0];
                    $return["fareBrand"][0]["fareInfos"][0]["paxFareInfos"] = [];
                    $cloned_fare_info = $return["fareBrand"][0]["fareInfos"][0];
                    $return["fareBrand"][0]["fareInfos"] = [];
                    foreach ($fare["FareInfos"]["FareInfo"] as $fare_info) {
                        $fare_info_e = $cloned_fare_info;
                        foreach ($fare_info["Pax"] as $pax) {
                            $pax_info = $cloned_pax_fare_info;
                            $pax_info["fareID"] = $pax["FareID"];
                            $pax_info["ID"] = $pax["ID"];
                            $pax_info["FBC"] = $pax["FBCode"];
                            $pax_info["fareClass"] = $pax["FCCode"];
                            $pax_info["cabin"] = $pax["Cabin"];
                            $pax_info["baseFare"] = $pax["BaseFareAmtNoTaxes"];
                            $pax_info["ruleID"] = $pax["RuleId"];
                            $pax_info["originalFare"] = $pax["OriginalFare"];
                            $pax_info["totalFare"] = $pax["FareAmtInclTax"];
                            $pax_info["PTC"] = $pax["PTCID"];
                            $pax_info["seatAvailability"] = $pax["SeatsAvailable"];
                            $pax_info["infantAvailability"] = $pax["InfantSeatsAvailable"];
                            $pax_info["secureHash"] = $pax["hashcode"];
                            $pax_info["fareCarrier"] = $pax["FareCarrier"];
                            //$pax_info["applicableTaxDetails"] = $pax["ApplicableTaxDetails"]["ApplicableTaxDetail"];

                            $tax_arr = [];
                            foreach ($pax["ApplicableTaxDetails"]["ApplicableTaxDetail"] as $ptax) {
                                $tax_p = [];
                                foreach ($tax_details as $tax_d) {
                                    if ($tax_d["TaxID"] == $ptax["TaxID"]) {
                                        $tax_p["Amt"] = $ptax["Amt"];
                                        $tax_p["TaxID"] = $ptax["TaxID"];
                                        $tax_p["TaxCode"] = $tax_d["TaxCode"];
                                        continue;
                                    }
                                }
                                $tax_arr[] = $tax_p;
                            }
                            $pax_info["applicableTaxDetails"] = $tax_arr;

                            $fare_info_e["paxFareInfos"][] = $pax_info;

                            $booking_codes_arr[$pax["FCCode"]][$pax["Cabin"]][] = $pax["ID"];
                        }
                        $return["fareBrand"][0]["fareInfos"][] = $fare_info_e;
                    }
                    break;
                }
            }

            $booking_codes = [];
            foreach ($booking_codes_arr as $fc_code => $val) {
                foreach ($val as $cab => $pax_ids) {
                    $booking_code["fareClass"] = $fc_code;
                    $booking_code["cabin"] = $cab;
                    $booking_code["paxID"] = $pax_ids;
                    $booking_codes[] = $booking_code;
                }
            }
            for ($i = 0; $i < count($departure["segmentDetails"]); $i++) {
                $return["segmentDetails"][$i]["bookingCodes"] = $booking_codes;
            }

            $request["originDestinations"][] = $return;
        } //return

        return $request;
    } //generateAddToCartRequest

    public static function generateAncillaryRequest($params)
    {
        //Params is the response from Add To Cart
        $request = json_decode(file_get_contents(app_path('Services/Airlines/FlyDubai/JSON/AncillaryRequest.json')), true);
        $flights_clone = $request["flights"][0];
        $request["flights"] = [];

        $segments = $params["originDestinations"];
        foreach ($segments as $segment) {
            $seg_details = $segment["segmentDetails"];
            foreach ($seg_details as $segd) {
                /*
                $legs = $segd["legDetails"];
                foreach ($legs as $leg) {
                    $seat_seg = $flights_clone;
                    $seat_seg["lfId"] = $segd["segmentID"];
                    $seat_seg["flightNum"] = $leg["operFlightNum"];
                    $seat_seg["depDate"] = $leg["depDate"];
                    $seat_seg["origin"] = $leg["origin"];
                    $seat_seg["currency"] = config("airlines.FlyDubai.currency");
                    $seat_seg["operatingCarrierCode"] = $leg["operCarrier"];
                    $seat_seg["marketingCarrierCode"] = $leg["mrktCarrier"];
                    array_push($request["flights"], $seat_seg);
                }
                */
                $seat_seg = $flights_clone;
                $seat_seg["lfId"] = $segd["segmentID"];
                $seat_seg["flightNum"] = $segment["flightNum"];
                $seat_seg["depDate"] = $segd["depDate"];
                $seat_seg["origin"] = $segd["origin"];
                $seat_seg["dest"] = $segd["destination"];
                $seat_seg["currency"] = config("airlines.FlyDubai.currency");
                //$seat_seg["operatingCarrierCode"] = $segd["operCarrier"];
                //$seat_seg["marketingCarrierCode"] = $segd["mrktCarrier"];
                unset($seat_seg["operatingCarrierCode"]);
                unset($seat_seg["marketingCarrierCode"]);
                $request["flights"][] = $seat_seg;
            }
        }

        return $request;
    } //generateAncillaryRequest

    private static function compareDatesAncillariesSegments($d1, $d2)
    {
        $d1_t = strtotime($d1["segments"]["depDate"]);
        $d2_t = strtotime($d2["segments"]["depDate"]);

        if ($d1_t == $d2_t) {
            return 0;
        }

        return ($d1_t > $d2_t) ? 1 : -1;
    }

    private static function compareDatesAncillariesLegs($d1, $d2)
    {
        $d1_t = strtotime($d1["depDate"]);
        $d2_t = strtotime($d2["depDate"]);

        if ($d1_t == $d2_t) {
            return 0;
        }

        return ($d1_t > $d2_t) ? 1 : -1;
    }

    private static function sortSeatMapSegments(array $segments_array, array $sorting_array)
    {
        if (!count($sorting_array)) {
            return $segments_array;
        }
        $ordered_array = [];
        foreach ($sorting_array as $key => $val) {
            $org_des = explode("-", $val);
            foreach ($segments_array as $seg) {
                if (trim($seg["origin"]) == $org_des[0] && trim($seg["dest"]) == $org_des[1]) {
                    $ordered_array[$key] = $seg;
                    break;
                }
            }
        }
        return array_values($ordered_array);
    }

    private static function sortAncillariesSegments(array $segments_array, array $sorting_array)
    {
        if (!count($sorting_array)) {
            return $segments_array;
        }
        $ordered_array = [];
        foreach ($sorting_array as $key => $val) {
            $org_des = explode("-", $val);
            foreach ($segments_array as $seg) {
                if (trim($seg["segments"]["origin"]) == $org_des[0]) {
                    $ordered_array[$key] = $seg;
                    break;
                }
            }
        }
        return array_values($ordered_array);
    }

    private static function sortAncillariesLegs(array $legs_array, array $sorting_array)
    {
        if (!count($sorting_array)) {
            return $legs_array;
        }
        $ordered_array = [];
        foreach ($sorting_array as $key => $val) {
            $org_des = explode("-", $val);
            foreach ($legs_array as $leg) {
                if (trim($leg["origin"]) == $org_des[0] && trim($leg["dest"]) == $org_des[1]) {
                    $ordered_array[$key] = $leg;
                    break;
                }
            }
        }
        return array_values($ordered_array);
    }

    private static function compareFlightsOrder($f1, $f2)
    {
        return ($f1["flight_order"] > $f2["flight_order"]) ? 1 : -1;
    }

    private static function sortSeatNumbers($a, $b)
    {
        return strcmp($a["seat_number"], $b["seat_number"]);
    }

    public static function processAncillaryRequest($response, $departure_type_name, $departure_services, $return_services = null, $departure_stops = [], $return_stops = [])
    {
        /*
        $results = [
            [
                "lfid" => 0,
                "origin" => null,
                "destination" => null,
                "departure_date" => null,
                "baggage" => [
                    "lfid" => 0,
                    "departure_date" => null,
                    "code" => 0,
                    "category_id" => 0,
                    "description" => null,
                    "amount" => 0,
                    "rule_id" => null
                ],
                "legs" => [
                    "pfid" => 0,
                    "origin" => null,
                    "destination" => null,
                    "departure_date" => null,
                    "meals" => [
                        "lfid" => 0,
                        "pfid" => 0,
                        "departure_date" => null,
                        "code" => 0,
                        "category_id" => 0,
                        "description" => null,
                        "amount" => 0,
                        "rule_id" => null
                    ]
                ],
            ]
        ];*/

        $sort_array = [];
        foreach ($departure_stops as $dstp) {
            $sort_array[] = trim($dstp["origin"]) . "-" . trim($dstp["destination"]);
        }
        foreach ($return_stops as $rstp) {
            $sort_array[] = trim($rstp["origin"]) . "-" . trim($rstp["destination"]);
        }

        $results = [];

        $flights = FlyDubaiSupport::sortAncillariesSegments($response["ancillaryQuotes"]["flights"], $sort_array);
        foreach ($flights as $indx => $flight) {
            $fl_arr = [];
            $fl_arr["lfid"] = $flight["segments"]["lfID"];
            $fl_arr["origin"] = $flight["segments"]["origin"];
            $fl_arr["departure_date"] = $flight["segments"]["depDate"];
            $fl_arr["baggage"] = [];

            $allowed_bags = [];
            if (strtolower($departure_type_name) == "lite") {
                $allowed_bags = ["BAGB", "BAGL", "BAGX"];
            } else {
                $service_codes = $indx == 0 ? $departure_services : $return_services;
                foreach ($service_codes as $code) {
                    if ($code == "BAGX" || $code == "JBAG") {
                        $allowed_bags = [];
                        break;
                    } else if ($code == "BAGB") {
                        $allowed_bags = ["BUPL", "BUPX"];
                    } else if ($code == "BAGL") {
                        $allowed_bags = ["BUPZ"];
                    }
                }
            }

            foreach ($flight["segments"]["serviceQuotes"] as $bag) {
                if (!$allowed_bags || !count($allowed_bags)) {
                    break;
                }
                if (!in_array($bag["code"], $allowed_bags)) {
                    continue;
                }
                $bg_arr = [];
                $bg_arr["lfid"] = $flight["segments"]["lfID"];
                $bg_arr["departure_date"] = $flight["segments"]["depDate"];
                $bg_arr["code"] = $bag["code"];
                $bg_arr["category_id"] = $bag["categoryID"];
                $bg_arr["description"] = $bag["description"];
                $bg_arr["amount"] = $bag["amount"];
                $bg_arr["rule_id"] = $bag["ruleID"] ?? null;
                $fl_arr["baggage"][] = $bg_arr;
            }

            $legs = FlyDubaiSupport::sortAncillariesLegs($flight["legs"], $sort_array);
            foreach ($legs as $ind => $leg) {
                if ($ind == (count($legs) - 1)) {
                    $fl_arr["destination"] = $leg["dest"];
                }
                $lg_arr = [];
                $lg_arr["pfid"] = $leg["pfID"];
                $lg_arr["origin"] = $leg["origin"];
                $lg_arr["destination"] = $leg["dest"];
                $lg_arr["departure_date"] = $leg["depDate"];
                $lg_arr["lf_departure_date"] = $flight["segments"]["depDate"];
                $lg_arr["meals"] = [];
                foreach ($leg["serviceQuotes"] as $meal) {
                    // if (strtolower($departure_type_name) != "business" && strtolower($departure_type_name) != "lite") {
                    //     break;
                    // }

                    // $allowed_meals = ["CHML", "MLIN"];
                    // if (!in_array($meal["code"], $allowed_meals) && strtolower($departure_type_name) == "lite") {
                    //     continue;
                    // }
                    $ml_arr = [];
                    $ml_arr["lfid"] = $flight["segments"]["lfID"];
                    $ml_arr["pfid"] = $leg["pfID"];
                    $ml_arr["departure_date"] = $leg["depDate"];
                    $ml_arr["lf_departure_date"] = $flight["segments"]["depDate"];
                    $ml_arr["code"] = $meal["code"];
                    $ml_arr["category_id"] = $meal["categoryID"];
                    $ml_arr["description"] = $meal["description"];
                    $ml_arr["amount"] = $meal["amount"];
                    $ml_arr["rule_id"] = $meal["ruleID"] ?? null;
                    $lg_arr["meals"][] = $ml_arr;
                }
                $fl_arr["legs"][] = $lg_arr;
            }
            $results[] = $fl_arr;
        }

        return $results;
    } //processAncillaryRequest

    public static function processSeatMapResults($response, $departure_type_name, $departure_stops = [], $return_stops = [])
    {
        $sort_array = [];
        foreach ($departure_stops as $dstp) {
            $sort_array[] = trim($dstp["origin"]) . "-" . trim($dstp["destination"]);
        }
        foreach ($return_stops as $rstp) {
            $sort_array[] = trim($rstp["origin"]) . "-" . trim($rstp["destination"]);
        }

        $results = [];

        $flights = FlyDubaiSupport::sortSeatMapSegments($response["seatQuotes"]["flights"], $sort_array);
        foreach($flights as $indx => $flight){
            $seg_arr = [];
            $seg_arr["pfid"] = $flight["pfID"];
            $seg_arr["origin"] = trim($flight["origin"]);
            $seg_arr["destination"] = trim($flight["dest"]);
            $seg_arr["flight_number"] = trim($flight["flightNum"]);
            $seg_arr["seats"] = [];
            foreach($flight["cabins"] as $cabin){
                if(strtolower($cabin["cabin"]) == "economy" && strtolower($departure_type_name) == "business") continue;
                if(strtolower($cabin["cabin"]) == "business" && strtolower($departure_type_name) != "business") continue;
                $seg_arr["cabin"] = $cabin["cabin"];
                foreach($cabin["seatMaps"] as $seatmap){
                    $row_info = [];
                    $row_info["row_number"] = $seatmap["rowNumber"];
                    $row_info["is_wing"] = $seatmap["isWing"];
                    $row_info["is_exit"] = $seatmap["isExit"];
                    $row_info["seats"] = [];
                    $seats_done = [];
                    foreach($seatmap["seats"] as $seat){
                        $seat_number = $seat["seat"];
                        if(!in_array($seat_number, $seats_done)){
                            $seat_info = [];
                            $seat_info["row_number"] = $seatmap["rowNumber"];
                            $seat_info["seat_number"] = $seat_number;
                            $seat_info["is_available"] = (!$seat["assigned"] && !$seat["isBlocked"] && !$seat["isPreBlocked"]);
                            $seat_info["charge"] = $seat["amount"];
                            $seat_info["service_code"] = $seat["serviceCode"];
                            $seat_info["flight_number"] = trim($flight["flightNum"]);
                            $seat_info["pfid"] = $flight["pfID"];
                            $row_info["seats"][] = $seat_info;
                            $seats_done[] = $seat_number;
                        }
                    }
                    usort($row_info["seats"], array(FlyDubaiSupport::class, 'sortSeatNumbers'));
                    $seg_arr["seats"][] = $row_info;
                }
            }
            $results[] = $seg_arr;
        }

        return $results;
    }//processSeatMapResults

    public static function generateSummaryPnrRequest($cart_response, $travellers, $has_return = false)
    {
        try {
            if(!is_array($cart_response)) $cart_response = json_decode($cart_response, true);
            $request = json_decode(file_get_contents(app_path('Services/Airlines/FlyDubai/JSON/SummaryRequest.json')), true);
            $request["HistoricUserName"] = config("airlines.FlyDubai.username");
            $request["CarrierCurrency"] = config("airlines.FlyDubai.currency");
            $request["DisplayCurrency"] = config("airlines.FlyDubai.currency");
            $request["IATANum"] = config("airlines.FlyDubai.IATA");
            $request["User"] = config("airlines.FlyDubai.username");

            $traveller_clone = $request["Passengers"][0];
            $request["Passengers"] = [];

            $ssr_clone = $request["Segments"][0]["SpecialServices"][0];
            $request["Segments"][0]["SpecialServices"] = [];

            $seatsr_clone = $request["Segments"][0]["Seats"][0];
            $request["Segments"][0]["Seats"] = [];

            $segment_clone = $request["Segments"][0];
            $request["Segments"] = [];

            for ($i = 0; $i < sizeof($travellers); $i++) {
                $pass = $traveller_clone;
                $pers_org_id = ($i + 1) * (-1);
                $pass["PersonOrgID"] = $pers_org_id;
                $pass["FirstName"] = $travellers[$i]["first_name"];
                $pass["LastName"] = $travellers[$i]["last_name"];
                $pass["Age"] = date("Y") - date("Y", strtotime($travellers[$i]["birth_date"]));
                $pass["DOB"] = $travellers[$i]["birth_date"];
                $pass["Gender"] = $travellers[$i]["gender"];
                $pass["Title"] = $travellers[$i]["title"] ?? "";
                $pass["WBCID"] = $travellers[$i]["type"];
                $pass["PTCID"] = $travellers[$i]["type"];
                $pass["TravelsWithPersonOrgID"] = $pers_org_id;
                $pass["Nationality"] = $travellers[$i]["nationality"];
                $pass["IsPrimaryPassenger"] = $i == 0;

                // $pass["Address"]["Address1"] = $travellers[$i]["address"]["address"];
                // $pass["Address"]["City"] = $travellers[$i]["address"]["city"];
                // $pass["Address"]["Country"] = $travellers[$i]["address"]["country"];
                // $pass["Address"]["CountryCode"] = $travellers[$i]["address"]["country_name_code"];
                // $pass["Address"]["PhoneNumber"] = $travellers[$i]["address"]["phone_number"];

                $pass["ContactInfos"][0]["PersonOrgID"] = $pass["PersonOrgID"];
                $pass["ContactInfos"][0]["ContactField"] = $travellers[$i]["contact"]["email"];
                //$pass["ContactInfos"][0]["CountryCode"] = $travellers[$i]["contact"]["country_code"];
                $pass["ContactInfos"][0]["PhoneNumber"] = $travellers[$i]["contact"]["phone_number"];

                $pass["DocumentInfos"][0]["DocNumber"] = $travellers[$i]["document_id"];
                $pass["DocumentInfos"][0]["IssuingCountry"] = $travellers[$i]["nationality"];
                $pass["DocumentInfos"][0]["ExpiryDate"] = $travellers[$i]["document_expiry_date"];

                $request["Passengers"][] = $pass;

                $fare_info_id = 0;
                $seg_rounds = $has_return ? 2 : 1;
                for ($j = 0; $j < $seg_rounds; $j++) {
                    $od_id = $cart_response["originDestinations"][$j]["odID"];
                    foreach ($cart_response["originDestinations"][$j]["fareBrands"] as $fb) {
                        foreach ($fb["fareInfos"] as $finf) {
                            if ((int)$finf["paxFareInfos"][0]["PTC"] == (int)$travellers[$i]["type"]) {
                                $fare_info_id = (int)$finf["paxFareInfos"][0]["fareID"];

                                $seg = $segment_clone;
                                $seg["PersonOrgID"] = $pers_org_id;
                                $seg["FareInformationID"] = $fare_info_id;
                                $seg["SpecialServices"] = [];
                                $seg["Seats"] = [];

                                foreach ($travellers[$i]["baggage"] as $bag) {
                                    $bag = json_decode($bag, true);
                                    if ($bag && $bag["lfid"] == $od_id) {
                                        $ssr = $ssr_clone;
                                        $ssr["PersonOrgID"] = $pers_org_id;
                                        $ssr["SSRCategory"] = $bag["category_id"];
                                        $ssr["CodeType"] = $bag["code"];
                                        $ssr["LogicalFlightID"] = $bag["lfid"];
                                        $ssr["DepartureDate"] = $bag["departure_date"];
                                        $ssr["Amount"] = $bag["amount"];
                                        $ssr["ChargeComment"] = $bag["rule_id"];
                                        unset($ssr["PhysicalFlightID"]);
                                        $seg["SpecialServices"][] = $ssr;
                                    }
                                }


                                foreach ($travellers[$i]["meals"] as $meal) {
                                    $meal = json_decode($meal, true);
                                    if ($meal && $meal["lfid"] == $od_id) {
                                        $ssr = $ssr_clone;
                                        $ssr["PersonOrgID"] = $pers_org_id;
                                        $ssr["SSRCategory"] = $meal["category_id"];
                                        $ssr["CodeType"] = $meal["code"];
                                        $ssr["LogicalFlightID"] = $meal["lfid"];
                                        $ssr["PhysicalFlightID"] = $meal["pfid"];
                                        $ssr["DepartureDate"] = $meal["lf_departure_date"];
                                        $ssr["Amount"] = $meal["amount"];
                                        $ssr["ChargeComment"] = $meal["rule_id"];
                                        $seg["SpecialServices"][] = $ssr;
                                    }
                                }

                                foreach($travellers[$i]["seats"] as $seat){
                                    //$seat = json_decode($seat, true);
                                    foreach($cart_response["originDestinations"][$j]["segmentDetails"] as $odseg){
                                        foreach($odseg["legDetails"] as $odleg){
                                            if((int)$odleg["legID"] == (int)$seat["pfid"]){
                                                $stsr = $seatsr_clone;
                                                $stsr["PersonOrgID"] = $pers_org_id;
                                                $stsr["LogicalFlightID"] = $odseg["segmentID"];
                                                $stsr["PhysicalFlightID"] = $seat["pfid"];
                                                $stsr["DepartureDate"] = $odleg["depDate"];
                                                $stsr["SeatSelected"] = $seat["seat_number"];
                                                $stsr["RowNumber"] = $seat["row_number"];
                                                $seg["Seats"][] = $stsr;
                                                break 2;
                                            }
                                        }
                                    }
                                }

                                $request["Segments"][] = $seg;
                            } //if ptc_id
                        }
                    }
                }
            } //end for travellers

            return $request;
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return ["error" => "Unknown Error. Summary PNR Request."];
    } //generateSummaryPnrRequest

    public static function processSummaryResults($results, $ancillaries_payload = null)
    {
        try {
            $arr["travellers"] = [];
            $arr["departure"] = null;
            $arr["return"] = null;
            $arr["amount"] = 0;
            $arr["taxes"] = 0;
            $arr["reservation_balance"] = $results["ReservationBalance"];

            $travellers = $results["Airlines"][0]["LogicalFlight"][0]["PhysicalFlights"][0]["Customers"][0]["AirlinePersons"];
            foreach ($travellers as $trav) {
                $tr["org_id"] = $trav["PersonOrgID"];
                $tr["title"] = $trav["Title"];
                $tr["first_name"] = $trav["FirstName"];
                $tr["last_name"] = $trav["LastName"];
                $tr["dob"] = $trav["DOB"];
                $tr["type"] = "ADULT";
                $tr["type"] = $trav["PTCID"] == 5 ? "INFANT" : $tr["type"];
                $tr["type"] = $trav["PTCID"] == 6 ? "CHILD" : $tr["type"];
                $tr["ancillaries"]["baggage"] = [];
                $tr["ancillaries"]["meals"] = [];
                $tr["ancillaries"]["seats"] = [];

                if ($ancillaries_payload) {
                    if (!is_array($ancillaries_payload)) {
                        $ancillaries_payload = json_decode($ancillaries_payload, true);
                    }

                    foreach ($results["Airlines"][0]["LogicalFlight"] as $flt) {
                        foreach ($flt["PhysicalFlights"] as $pflt) {
                            foreach($pflt["Customers"] as $cust){
                                foreach($cust["AirlinePersons"] as $aper){
                                    if($aper["PersonOrgID"] == $trav["PersonOrgID"]){
                                        foreach($aper["Charges"] as $chrg){
                                            foreach ($ancillaries_payload["ancillaryQuotes"]["flights"] as $fl) {
                                                foreach ($fl["segments"]["serviceQuotes"] as $bags) {
                                                    if ($bags["code"] == $chrg["CodeType"]) {
                                                        $tr["ancillaries"]["baggage"][] = [
                                                            "code" => $bags["code"],
                                                            "description" => $bags["description"],
                                                            "flight_number" => $pflt["FlightNumber"],
                                                            "origin" => $pflt["Origin"],
                                                            "destination" => $pflt["Destination"]
                                                        ];
                                                        break 2;
                                                    }
                                                }
                                                foreach ($fl["legs"] as $leg) {
                                                    foreach ($leg["serviceQuotes"] as $meal) {
                                                        if ($meal["code"] == $chrg["CodeType"]) {
                                                            $tr["ancillaries"]["meals"][] = [
                                                                "code" => $meal["code"],
                                                                "description" => $meal["description"],
                                                                "flight_number" => $pflt["FlightNumber"],
                                                                "origin" => $pflt["Origin"],
                                                                "destination" => $pflt["Destination"]
                                                            ];
                                                            break 3;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if(isset($aper["SeatAssignments"]) && $aper["PTCID"] != 5){
                                            foreach($aper["SeatAssignments"] as $sta){
                                                $tr["ancillaries"]["seats"][] = [
                                                    "row_number" => $sta["RowNumber"],
                                                    "seat_number" => $sta["Seat"],
                                                    "flight_number" => $pflt["FlightNumber"],
                                                    "origin" => $pflt["Origin"],
                                                    "destination" => $pflt["Destination"]
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $arr["travellers"][] = $tr;
            }

            foreach ($results["DocumentInfos"] as $docInf) {
                foreach ($arr["travellers"] as $ind => $trav) {
                    if ($trav["org_id"] == $docInf["PersonOrgID"]) {
                        $arr["travellers"][$ind]["nationality"] = $docInf["IssuingCountry"];
                        $arr["travellers"][$ind]["passport_number"] = $docInf["DocNumber"];
                        $arr["travellers"][$ind]["passport_expiry"] = $docInf["ExpiryDate"];
                        break;
                    }
                }
            }

            foreach ($results["ContactInfos"] as $cont) {
                foreach ($arr["travellers"] as $ind => $trav) {
                    if ($trav["org_id"] == $cont["PersonOrgID"]) {
                        $arr["travellers"][$ind]["phone"] = $cont["PhoneNumber"];
                        $arr["travellers"][$ind]["email"] = $cont["ContactField"];
                        break;
                    }
                }
            }

            $logicalFlight = $results["Airlines"][0]["LogicalFlight"];
            for ($i = 0; $i < count($logicalFlight); $i++) {
                $fl = [];
                $fl["from"] = $logicalFlight[$i]["Origin"];
                $fl["to"] = $logicalFlight[$i]["Destination"];
                $fl["flight_number"] = $logicalFlight[$i]["FlightNumber"];
                $fl["departure_time"] = $logicalFlight[$i]["DepartureTime"];
                $fl["arrival_time"] = $logicalFlight[$i]["Arrivaltime"];
                foreach ($logicalFlight[$i]["PhysicalFlights"] as $stops) {
                    $st = [];
                    $st["from"] = $stops["Origin"];
                    $st["to"] = $stops["Destination"];
                    $st["flight_number"] = $stops["FlightNumber"];
                    $st["departure_time"] = $stops["DepartureTime"];
                    $st["arrival_time"] = $stops["Arrivaltime"];
                    $st["carrier"] = $stops["OperatingCarrier"];
                    $st["flight_order"] = $stops["FlightOrder"] ?? 1;
                    $fl["stops"][] = $st;

                    foreach ($stops["Customers"][0]["AirlinePersons"] as $cst) {
                        foreach ($cst["Charges"] as $chrg) {
                            if ((int)$chrg["TaxID"] <= 0) {
                                $arr["amount"] += $chrg["Amount"];
                            } else {
                                $arr["taxes"] += $chrg["Amount"];
                            }
                        }
                    }
                }

                $stops_col = $fl["stops"];
                usort($stops_col, array(FlyDubaiSupport::class, "compareFlightsOrder"));
                $fl["stops"] = $stops_col;

                if ($i == 0) {
                    $arr["departure"] = $fl;
                } else {
                    $arr["return"] = $fl;
                }
            }

            //check departure & return order
            if ($arr["return"]) {
                if (strtotime($arr["departure"]["arrival_time"]) > strtotime($arr["return"]["departure_time"])) {
                    $swap_var = $arr["departure"];
                    $arr["departure"] = $arr["return"];
                    $arr["return"] = $swap_var;
                }
            }

            if ($arr["departure"]["departure_time"]) {
                return $arr;
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }

        return ["error" => "Unkown Error while processing summary results."];
    } //processSummaryResults

    public static function generateCommitPnrRequest()
    {
        $request = json_decode(file_get_contents(app_path('Services/Airlines/FlyDubai/JSON/CommitRequest.json')), true);
        $request["HistoricUserName"] = config("airlines.FlyDubai.username");
        return $request;
    } //generateCommitPnrRequest

    public static function generateFOPRequest($params, $payor = [])
    {
        try {
            //Params is the response from Commit
            $payor = [
                "first_name" => "Khalil",
                "last_name" => "Sarabey",
                "birth_date" => "1980-02-03",
                "gender" => "male",
                "address" => "Hamra",
                "city" => "Beirut",
                "country" => "Lebanon",
                "phone_number" => "96170540250"
            ];
            //Params is the response from Commit PNR
            $request = json_decode(file_get_contents(app_path('Services/Airlines/FlyDubai/JSON/FOPRequest.json')), true);
            $request["TransactionInfo"]["HistoricUserName"] = config("airlines.FlyDubai.username");
            $request["ReservationInfo"]["ConfirmationNumber"] = $params["ConfirmationNumber"];
            $request["PNRPayments"][0]["CurrencyPaid"] = config("airlines.FlyDubai.currency");
            $request["PNRPayments"][0]["DatePaid"] = date("Y-m-d\TH:i:s");
            $request["PNRPayments"][0]["ExpirationDate"] = date("Y-m-d\TH:i:s");
            $request["PNRPayments"][0]["ExchangeRateDate"] = date("Y-m-d\TH:i:s");
            $request["PNRPayments"][0]["OriginalAmount"] = $params["ReservationBalance"];
            $request["PNRPayments"][0]["PaymentAmount"] = $params["ReservationBalance"];
            $request["PNRPayments"][0]["UserID"] = config("airlines.FlyDubai.client_id");
            $request["PNRPayments"][0]["IataNumber"] = config("airlines.FlyDubai.IATA");
            $request["PNRPayments"][0]["ReservationCurrency"] = config("airlines.FlyDubai.currency");
            $request["PNRPayments"][0]["ReservationAmount"] = $params["ReservationBalance"];
            $request["PNRPayments"][0]["BaseAmount"] = $params["ReservationBalance"];
            //unset($request["PNRPayments"][0]["Payor"]);
            /*
            $request["PNRPayments"][0]["Payor"]["FirstName"] = $payor["first_name"];
            $request["PNRPayments"][0]["Payor"]["LastName"] = $payor["last_name"];
            $request["PNRPayments"][0]["Payor"]["Age"] = date("Y") - date("Y", strtotime($payor["birth_date"]));
            $request["PNRPayments"][0]["Payor"]["DOB"] = $payor["birth_date"];
            $request["PNRPayments"][0]["Payor"]["Gender"] = $payor["gender"];
            $request["PNRPayments"][0]["Payor"]["Address"]["Address1"] = $payor["address"];
            $request["PNRPayments"][0]["Payor"]["Address"]["City"] = $payor["city"];
            $request["PNRPayments"][0]["Payor"]["Address"]["Country"] = $payor["country"];
            $request["PNRPayments"][0]["Payor"]["Address"]["PhoneNumber"] = $payor["phone_number"];
            */
            return $request;
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public static function processFOPResults($results, $ancillaries_payload = null, $is_paid = true)
    {
        try {

            $results = is_array($results) ? $results : json_decode($results, true);
            $results = $is_paid ? $results["ViewPNR"] : $results;

            $arr["travellers"] = [];
            $arr["departure"] = null;
            $arr["return"] = null;
            $arr["amount"] = 0;
            $arr["taxes"] = 0;
            $arr["booking_num"] = $results["ConfirmationNumber"];

            if(isset($results["ReservationFulfillmentRequiredByGMT"])){
                $arr["on_hold_expiry_date"] = $results["ReservationFulfillmentRequiredByGMT"];
            }

            $travellers = $results["Airlines"][0]["LogicalFlight"][0]["PhysicalFlights"][0]["Customers"][0]["AirlinePersons"];
            foreach ($travellers as $trav) {
                $tr["org_id"] = $trav["PersonOrgID"];
                $tr["title"] = $trav["Title"];
                $tr["first_name"] = $trav["FirstName"];
                $tr["last_name"] = $trav["LastName"];
                $tr["dob"] = $trav["DOB"];
                $tr["type"] = "ADULT";
                $tr["type"] = $trav["PTCID"] == 5 ? "INFANT" : $tr["type"];
                $tr["type"] = $trav["PTCID"] == 6 ? "CHILD" : $tr["type"];
                $tr["ancillaries"]["baggage"] = [];
                $tr["ancillaries"]["meals"] = [];
                $tr["ancillaries"]["seats"] = [];

                if ($ancillaries_payload) {
                    if (!is_array($ancillaries_payload)) {
                        $ancillaries_payload = json_decode($ancillaries_payload, true);
                    }

                    foreach ($results["Airlines"][0]["LogicalFlight"] as $flt) {
                        foreach ($flt["PhysicalFlights"] as $pflt) {
                            foreach($pflt["Customers"] as $cust){
                                foreach($cust["AirlinePersons"] as $aper){
                                    if($aper["PersonOrgID"] == $trav["PersonOrgID"]){
                                        foreach($aper["Charges"] as $chrg){
                                            foreach ($ancillaries_payload["ancillaryQuotes"]["flights"] as $fl) {
                                                foreach ($fl["segments"]["serviceQuotes"] as $bags) {
                                                    if ($bags["code"] == $chrg["CodeType"]) {
                                                        $tr["ancillaries"]["baggage"][] = [
                                                            "code" => $bags["code"],
                                                            "description" => $bags["description"],
                                                            "flight_number" => $pflt["FlightNumber"],
                                                            "origin" => $pflt["Origin"],
                                                            "destination" => $pflt["Destination"]
                                                        ];
                                                        break 2;
                                                    }
                                                }
                                                foreach ($fl["legs"] as $leg) {
                                                    foreach ($leg["serviceQuotes"] as $meal) {
                                                        if ($meal["code"] == $chrg["CodeType"]) {
                                                            $tr["ancillaries"]["meals"][] = [
                                                                "code" => $meal["code"],
                                                                "description" => $meal["description"],
                                                                "flight_number" => $pflt["FlightNumber"],
                                                                "origin" => $pflt["Origin"],
                                                                "destination" => $pflt["Destination"]
                                                            ];
                                                            break 3;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if(isset($aper["SeatAssignments"]) && $aper["PTCID"] != 5){
                                            foreach($aper["SeatAssignments"] as $sta){
                                                $tr["ancillaries"]["seats"][] = [
                                                    "row_number" => $sta["RowNumber"],
                                                    "seat_number" => $sta["Seat"],
                                                    "flight_number" => $pflt["FlightNumber"],
                                                    "origin" => $pflt["Origin"],
                                                    "destination" => $pflt["Destination"]
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $arr["travellers"][] = $tr;
            }

            foreach ($results["DocumentInfos"] as $docInf) {
                foreach ($arr["travellers"] as $ind => $trav) {
                    if ($trav["org_id"] == $docInf["PersonOrgID"]) {
                        $arr["travellers"][$ind]["nationality"] = $docInf["IssuingCountry"];
                        $arr["travellers"][$ind]["passport_number"] = $docInf["DocNumber"];
                        $arr["travellers"][$ind]["passport_expiry"] = $docInf["ExpiryDate"];
                        break;
                    }
                }
            }

            foreach ($results["ContactInfos"] as $cont) {
                foreach ($arr["travellers"] as $ind => $trav) {
                    if ($trav["org_id"] == $cont["PersonOrgID"]) {
                        $arr["travellers"][$ind]["phone"] = $cont["PhoneNumber"];
                        $arr["travellers"][$ind]["email"] = $cont["ContactField"];
                        break;
                    }
                }
            }

            $logicalFlight = $results["Airlines"][0]["LogicalFlight"];
            for ($i = 0; $i < count($logicalFlight); $i++) {
                $fl = [];
                $fl["from"] = $logicalFlight[$i]["Origin"];
                $fl["to"] = $logicalFlight[$i]["Destination"];
                $fl["flight_number"] = $logicalFlight[$i]["FlightNumber"];
                $fl["departure_time"] = $logicalFlight[$i]["DepartureTime"];
                $fl["arrival_time"] = $logicalFlight[$i]["Arrivaltime"];
                $fl["terminal"] = $logicalFlight[$i]["OriginDefaultTerminal"];
                foreach ($logicalFlight[$i]["PhysicalFlights"] as $stops) {
                    $st = [];
                    $st["from"] = $stops["Origin"];
                    $st["to"] = $stops["Destination"];
                    $st["flight_number"] = $stops["FlightNumber"];
                    $st["departure_time"] = $stops["DepartureTime"];
                    $st["arrival_time"] = $stops["Arrivaltime"];
                    $st["terminal"] = $stops["OriginDefaultTerminal"];
                    $st["carrier"] = $stops["OperatingCarrier"];
                    $st["flight_order"] = $stops["FlightOrder"];
                    $fl["stops"][] = $st;

                    foreach ($stops["Customers"][0]["AirlinePersons"] as $cst) {
                        foreach ($cst["Charges"] as $chrg) {
                            if ((int)$chrg["TaxID"] <= 0) {
                                $arr["amount"] += $chrg["Amount"];
                            } else {
                                $arr["taxes"] += $chrg["Amount"];
                            }
                        }
                    }
                }

                $stops_col = $fl["stops"];
                uasort($stops_col, array(FlyDubaiSupport::class, "compareFlightsOrder"));
                $fl["stops"] = $stops_col;

                if ($i == 0) {
                    $arr["departure"] = $fl;
                } else {
                    $arr["return"] = $fl;
                }
            }

            //check departure & return order
            if ($arr["return"]) {
                if (strtotime($arr["departure"]["arrival_time"]) > strtotime($arr["return"]["departure_time"])) {
                    $swap_var = $arr["departure"];
                    $arr["departure"] = $arr["return"];
                    $arr["return"] = $swap_var;
                }
            }

            return $arr;
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    } //processFOPResults

    public static function generateRetrievePnrRequest($params)
    {
        try{
            $request = json_decode(file_get_contents(app_path('Services/Airlines/FlyDubai/JSON/RetrievePnrRequest.json')), true);
            $request["ReservationInfo"]["ConfirmationNumber"] = $params["booking_ref"];
            return $request;
        }catch(Exception $e){
            return ["error" => $e->getMessage()];
        }
    }//generateRetrievePnrRequest
}
