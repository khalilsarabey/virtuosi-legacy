<?php

namespace App\Services\Airlines\AirArabia;

use DateTime;
use DateTimeZone;
use DOMDocument;
use Exception;
use SimpleXMLElement;

class AirArabiaSupport
{
    public static function generateSearchRequest($params): array
    {
        //searchOnds is array of origins and destinations
        //pax counts is array of passengers
        $data = [
            "searchOnds" =>
                [
                    [
                        "origin" =>
                            [
                                "code" => $params["itinerary"]["departure"]["from"],
                                "locationType" => "AIRPORT"
                            ],
                        "destination" =>
                            [
                                "code" => $params["itinerary"]["departure"]["to"],
                                "locationType" => "AIRPORT"
                            ],
                        "searchStartDate" => $params["flexible_dates"] ? date("Y-m-d", strtotime("-" . $params["flexibility_days"] . " day", strtotime($params["itinerary"]["departure"]["date"]))) : date("Y-m-d", strtotime($params["itinerary"]["departure"]["date"])),
                        "searchEndDate" => $params["flexible_dates"] ? date("Y-m-d", strtotime("+" . $params["flexibility_days"] . " day", strtotime($params["itinerary"]["departure"]["date"]))) : date("Y-m-d", strtotime($params["itinerary"]["departure"]["date"])),
                        "cabinClass" => "",
                        "bookingType" => "NORMAL",
                        "ondRef" => $params["itinerary"]["departure"]["from"] . "/" . $params["itinerary"]["departure"]["to"],
                    ]
                ],
            "paxCounts" =>
                [
                    [
                        "paxType" => "ADT",
                        "count" => $params["itinerary"]["departure"]["adult"]
                    ],
                    [
                        "paxType" => "CHD",
                        "count" => $params["itinerary"]["departure"]["child"]
                    ],
                    [
                        "paxType" => "INF",
                        "count" => $params["itinerary"]["departure"]["infant"]
                    ]
                ],
            "currencyCode" => config("airlines.AirArabia.currency"),
            "cabinClass" => "",
            "metaData" =>
                [
                    "agentCode" => config("airlines.AirArabia.agent_code"),
                    "country" => "LB",
                    "station" => "SHA",
                    "salesChannel" => "OTA",
                    "otherMetaData" =>
                        [
                            [
                                "metaDataKey" => "FLIGHT_CUTOVER_TIME",
                                "metaDataValue" => date("Y-m-d") . "T00:00:00"
                            ],
                            [
                                "metaDataKey" => "SKIP_OND_MERGE",
                                "metaDataValue" => "false"
                            ]
                        ]
                ],
        ];

        if ($params["itinerary"]["return"]["from"]) {
            $returnNode =
                [
                    "origin" =>
                        [
                            "code" => $params["itinerary"]["return"]["from"],
                            "locationType" => "AIRPORT"
                        ],
                    "destination" =>
                        [
                            "code" => $params["itinerary"]["return"]["to"],
                            "locationType" => "AIRPORT"
                        ],
                    "searchStartDate" => $params["flexible_dates"] ? date("Y-m-d", strtotime("-" . $params["flexibility_days"] . " day", strtotime($params["itinerary"]["return"]["date"]))) : date("Y-m-d", strtotime($params["itinerary"]["return"]["date"])),
                    "searchEndDate" => $params["flexible_dates"] ? date("Y-m-d", strtotime("+" . $params["flexibility_days"] . " day", strtotime($params["itinerary"]["return"]["date"]))) : date("Y-m-d", strtotime($params["itinerary"]["return"]["date"])),
                    "cabinClass" => "",
                    "bookingType" => "NORMAL",
                    "ondRef" => $params["itinerary"]["return"]["from"] . "/" . $params["itinerary"]["return"]["to"],
                ];
            $data["searchOnds"][] = $returnNode;
        }
        return $data;
    } //generateSearchRequest

    public static function processSearchResults($results, $adult_count, $child_count)
    {
        $array['payload'] = $results;
        $array["departure"] = [];
        $array["return"] = [];

        $ondsCount = count($results["ondWiseFlightCombinations"]);
        if ($ondsCount == 0) {
            return $array;
        }

        $seg_counter = 1;
        foreach ($results["ondWiseFlightCombinations"] as $seg) {
            //first seg is departure and the second is return
            foreach ($seg["dateWiseFlightCombinations"] as $dtw) {
                foreach ($dtw["flightOptions"] as $fl) {
                    $num_segs = count($fl["flightSegments"]);
                    if ($num_segs == 0) {
                        continue;
                    }
                    $obj["flight_number"] = $fl["flightSegments"][0]["flightNumber"];
                    $obj["origin"] = $fl["flightSegments"][0]["origin"]["airportCode"];
                    $obj["destination"] = $fl["flightSegments"][$num_segs - 1]["destination"]["airportCode"];
                    $obj["departure_time"] = $fl["flightSegments"][0]["departureDateTimeLocal"];
                    $obj["arrival_time"] = $fl["flightSegments"][$num_segs - 1]["arrivalDateTimeLocal"];
                    $obj["stops"] = $num_segs - 1;
                    $obj["stop_details"] = [];
                    $obj["price"] = 0;
                    foreach ($fl["cabinPrices"] as $cab) {
                        $obj["price"] += ($cab["price"] + 0);
                    }
                    foreach ($fl["flightSegments"] as $stp) {
                        foreach ($stp["availablePaxCounts"] as $av_pax) {
                            if ($av_pax["paxType"] == "ADT" && intval($av_pax["count"]) < (intval($adult_count) + intval($child_count))) {
                                continue 3;
                            }
                        }
                        $st["origin"] = $stp["origin"]["airportCode"];
                        $st["destination"] = $stp["destination"]["airportCode"];
                        $st["departure_time"] = $stp["departureDateTimeLocal"];
                        $st["arrival_time"] = $stp["arrivalDateTimeLocal"];
                        $st["flight_number"] = $stp["flightNumber"];
                        $st["aircraft"] = $stp["aircraftModel"];
                        $obj["stop_details"][] = $st;
                    }

                    if ($seg_counter == 1) {
                        $array["departure"][] = $obj;
                    } else {
                        $array["return"][] = $obj;
                    }
                }
            }
            $seg_counter++;
        }

        if ($ondsCount > 1 && (empty($array["departure"]) || empty($array["return"]))) {
            $array["departure"] = [];
            $array["return"] = [];
        }

        return $array;
    } //processSearchResults

    public static function generateGetPriceRequest($params)
    {
        try {
            $xml = new DOMDocument();
            $xml->load(app_path("Services/Airlines/AirArabia/XML/PriceQuote.xml"));
            $xml->getElementsByTagName("Username")->item(0)->nodeValue = config("airlines.AirArabia.username");
            $xml->getElementsByTagName("Password")->item(0)->nodeValue = config("airlines.AirArabia.password");
            $xml->getElementsByTagName("Source")->item(0)->setAttribute("TerminalID", config("airlines.AirArabia.agent_code_all_requests"));
            $xml->getElementsByTagName("RequestorID")->item(0)->setAttribute("ID", config("airlines.AirArabia.username"));
            $xml->getElementsByTagName("AirItinerary")->item(0)->setAttribute("DirectionInd", $params["is_return"] ? "Return" : "OneWay");

            $tr_id = $params["transaction_identifier"] ?? null;
            if ($tr_id) {
                $xml->getElementsByTagName("OTA_AirPriceRQ")->item(0)->setAttribute("TransactionIdentifier", $params["transaction_identifier"]);
            }

            $org_dst_clone = $xml->getElementsByTagName("OriginDestinationOption")->item(0)->cloneNode(true);
            $fl_seg_clone = $org_dst_clone->getElementsByTagName("FlightSegment")->item(0)->cloneNode(true);

            $org_dst_options_parent = $xml->getElementsByTagName("OriginDestinationOptions")->item(0);
            $org_dst_options_parent->removeChild($org_dst_options_parent->getElementsByTagName("OriginDestinationOption")->item(0));

            $dep_counter = 1;
            foreach ($params["payload"]["ondWiseFlightCombinations"] as $dep_ret) {
                $dep_flight_num = $dep_counter == 1 ? $params["departure_flight_number"] : $params["return_flight_number"];
                $dep_from = $dep_counter == 1 ? $params["departure_from"] : $params["return_from"];
                $dep_to = $dep_counter == 1 ? $params["departure_to"] : $params["return_to"];
                $dep_from_date = $dep_counter == 1 ? $params["departure_from_date"] : $params["return_from_date"];
                $dep_to_date = $dep_counter == 1 ? $params["departure_to_date"] : $params["return_to_date"];

                $orig_dist = $org_dst_clone->cloneNode(true);
                $orig_dist->removeChild($orig_dist->getElementsByTagName("FlightSegment")->item(0));

                $dt_segs = $dep_ret["dateWiseFlightCombinations"][date("Y-m-d", strtotime($dep_from_date))];
                foreach ($dt_segs["flightOptions"] as $fl) {
                    $stp_count = count($fl["flightSegments"]);

                    if ($fl["flightSegments"][0]["flightNumber"] != $dep_flight_num || $fl["flightSegments"][0]["origin"]["airportCode"] != $dep_from) {
                        continue;
                    }
                    if ($fl["flightSegments"][$stp_count - 1]["arrivalDateTimeLocal"] != $dep_to_date || $fl["flightSegments"][$stp_count - 1]["destination"]["airportCode"] != $dep_to) {
                        continue;
                    }

                    foreach ($fl["flightSegments"] as $stp) {
                        $fl_seg_temp = $fl_seg_clone->cloneNode(true);
                        $fl_seg_temp->setAttribute("DepartureDateTime", $stp["departureDateTimeLocal"]);
                        $fl_seg_temp->setAttribute("ArrivalDateTime", $stp["arrivalDateTimeLocal"]);
                        $fl_seg_temp->setAttribute("FlightNumber", $stp["flightNumber"]);
                        $fl_seg_temp->getElementsByTagName("DepartureAirport")->item(0)->setAttribute("LocationCode", $stp["origin"]["airportCode"]);
                        $fl_seg_temp->getElementsByTagName("ArrivalAirport")->item(0)->setAttribute("LocationCode", $stp["destination"]["airportCode"]);
                        $fl_seg_temp->getElementsByTagName("OperatingAirline")->item(0)->setAttribute("Code", substr($stp["flightNumber"], 0, 2));
                        $orig_dist->appendChild($fl_seg_temp);
                    }
                }
                $xml->getElementsByTagName("OriginDestinationOptions")->item(0)->appendChild($orig_dist);
                $dep_counter++;
            }

            $childNode = $xml->getElementsByTagName("PassengerTypeQuantity")->item(0)->cloneNode(false);
            $infantNode = $xml->getElementsByTagName("PassengerTypeQuantity")->item(0)->cloneNode(false);
            $xml->getElementsByTagName("PassengerTypeQuantity")->item(0)->setAttribute("Code", "ADT");
            $xml->getElementsByTagName("PassengerTypeQuantity")->item(0)->setAttribute("Quantity", $params["adult_count"]);
            $childNode->setAttribute("Code", "CHD");
            $childNode->setAttribute("Quantity", $params["child_count"]);
            $infantNode->setAttribute("Code", "INF");
            $infantNode->setAttribute("Quantity", $params["infant_count"]);
            $xml->getElementsByTagName("AirTravelerAvail")->item(0)->appendChild($childNode);
            $xml->getElementsByTagName("AirTravelerAvail")->item(0)->appendChild($infantNode);

            $services_node = $xml->getElementsByTagName("SpecialReqDetails")->item(0);
            if (isset($params["service_requests"]["seats"]) && sizeof($params["service_requests"]["seats"]) > 0) {
                $seats = $xml->createElement("ns1:SeatRequests");
                foreach ($params["service_requests"]["seats"] as $seat_info) {
                    $seat = $xml->createElement("ns1:SeatRequest");

                    $seat_num = $xml->createAttribute("SeatNumber");
                    $seat_num->value = $seat_info["seat_number"];
                    $seat->appendChild($seat_num);

                    $tr_ref_num = $xml->createAttribute("TravelerRefNumberRPHList");
                    $tr_ref_num->value = $seat_info["traveller_ref_number"];
                    $seat->appendChild($tr_ref_num);

                    $rph = $xml->createAttribute("FlightRefNumberRPHList");
                    $rph->value = $seat_info["flight_rph"];
                    $seat->appendChild($rph);

                    $seats->appendChild($seat);
                }
                $services_node->appendChild($seats);
            }
            if (isset($params["service_requests"]["meals"]) && sizeof($params["service_requests"]["meals"]) > 0) {
                $meals = $xml->createElement("ns1:MealRequests");
                foreach ($params["service_requests"]["meals"] as $meal_info) {
                    $meal = $xml->createElement("ns1:MealRequest");

                    $meal_code = $xml->createAttribute("mealCode");
                    $meal_code->value = $meal_info["meal_code"];
                    $meal->appendChild($meal_code);

                    $meal_qty = $xml->createAttribute("mealQuantity");
                    $meal_qty->value = $meal_info["quantity"];
                    $meal->appendChild($meal_qty);

                    $tr_ref_num = $xml->createAttribute("TravelerRefNumberRPHList");
                    $tr_ref_num->value = $meal_info["traveller_ref_number"];
                    $meal->appendChild($tr_ref_num);

                    $rph = $xml->createAttribute("FlightRefNumberRPHList");
                    $rph->value = $meal_info["flight_rph"];
                    $meal->appendChild($rph);

                    $depart = $xml->createAttribute("DepartureDateTime");
                    $depart->value = $meal_info["departure_date"];
                    $meal->appendChild($depart);

                    $flight_num = $xml->createAttribute("FlightNumber");
                    $flight_num->value = $meal_info["flight_num"];
                    $meal->appendChild($flight_num);

                    $meals->appendChild($meal);
                }
                $services_node->appendChild($meals);
            }
            if (isset($params["service_requests"]["baggages"]) && sizeof($params["service_requests"]["baggages"]) > 0) {
                $baggages = $xml->createElement("ns1:BaggageRequests");
                foreach ($params["service_requests"]["baggages"] as $bag_info) {
                    foreach ($bag_info["segments"] as $bseg) {
                        $bag = $xml->createElement("ns1:BaggageRequest");

                        $bag_code = $xml->createAttribute("baggageCode");
                        $bag_code->value = $bag_info["baggage_code"];
                        $bag->appendChild($bag_code);

                        $tr_ref_num = $xml->createAttribute("TravelerRefNumberRPHList");
                        $tr_ref_num->value = $bag_info["traveller_ref_number"];
                        $bag->appendChild($tr_ref_num);

                        $rph = $xml->createAttribute("FlightRefNumberRPHList");
                        $rph->value = $bseg["rph"];
                        $bag->appendChild($rph);

                        $depart = $xml->createAttribute("DepartureDateTime");
                        $depart->value = $bseg["departure_date"];
                        $bag->appendChild($depart);

                        $flight_num = $xml->createAttribute("FlightNumber");
                        $flight_num->value = $bseg["flight_number"];
                        $bag->appendChild($flight_num);

                        $baggages->appendChild($bag);
                    }
                }
                $services_node->appendChild($baggages);
            }

            $bundle_added = false;
            $bundle_element = $xml->createElement("ns1:BundledServiceSelectionOptions");
            if (isset($params["bundled_services"]["departure"]) && (int)$params["bundled_services"]["departure"] > 0) {
                $bundle_added = true;
                $out_bundle = $xml->createElement("ns1:OutBoundBunldedServiceId", $params["bundled_services"]["departure"]);
                $bundle_element->appendChild($out_bundle);
            }
            if (isset($params["bundled_services"]["return"]) && (int)$params["bundled_services"]["return"] > 0) {
                $bundle_added = true;
                $in_bundle = $xml->createElement("ns1:InBoundBunldedServiceId", $params["bundled_services"]["return"]);
                $bundle_element->appendChild($in_bundle);
            }
            if ($bundle_added) {
                $xml->getElementsByTagName("OTA_AirPriceRQ")->item(0)->appendChild($bundle_element);
            }

            return trim($xml->saveXML());
        } catch (Exception $e) {
            return false;
        }
    } //generateGetPriceRequest

    public static function processGetPriceResultsForSearch($results, $params)
    {
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());
            $response_array = [];

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $segments = $params["is_return"] ? 2 : 1;
            $basic_fare_info = $domd->getElementsByTagName("ItinTotalFare")->item(0);
            $total_price = ((float)$basic_fare_info->getElementsByTagName("TotalFareWithCCFee")->item(0)->getAttribute("Amount")) / $segments;
            $base_fare = ((float)$basic_fare_info->getElementsByTagName("BaseFare")->item(0)->getAttribute("Amount")) / $segments;
            return [
                "price" => $total_price,
                "base_fare" => $base_fare,
                "taxes" => ($total_price - $base_fare),
            ];
        } catch (Exception $e) {
            return [
                "Errors" => [
                    "code" => $e->getCode(),
                    "text" => $e->getMessage()
                ]
            ];
        }
    } //processGetPriceResultsForSearch

    public static function processGetPriceResults($results)
    {
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());
            $departure_ond = null;
            $return_ond = null;
            $response_array = [];

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $response_array["transaction_identifier"] = $domd->getElementsByTagName("OTA_AirPriceRS")->item(0)->getAttribute("TransactionIdentifier");
            $departure_segment = $domd->getElementsByTagName("OriginDestinationOption")->item(0)->getElementsByTagName("FlightSegment")->item(0);
            $response_array["departure_departure_date"] = $departure_segment->getAttribute("DepartureDateTime");
            $response_array["departure_arrival_date"] = $departure_segment->getAttribute("ArrivalDateTime");
            $response_array["departure_flight_number"] = $departure_segment->getAttribute("FlightNumber");
            $response_array["departure_rph"] = $departure_segment->getAttribute("RPH");
            $response_array["departure_from"] = $departure_segment->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
            $response_array["departure_to"] = $departure_segment->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
            $departure_ond = $response_array["departure_from"] . "/" . $response_array["departure_to"];
            if ($domd->getElementsByTagName("OriginDestinationOption")->item(1)) {
                $return_segment = $domd->getElementsByTagName("OriginDestinationOption")->item(1)->getElementsByTagName("FlightSegment")->item(0);
                $response_array["return_departure_date"] = $return_segment->getAttribute("DepartureDateTime");
                $response_array["return_arrival_date"] = $return_segment->getAttribute("ArrivalDateTime");
                $response_array["return_flight_number"] = $return_segment->getAttribute("FlightNumber");
                $response_array["return_rph"] = $return_segment->getAttribute("RPH");
                $response_array["return_from"] = $return_segment->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $response_array["return_to"] = $return_segment->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $return_ond = $response_array["return_from"] . "/" . $response_array["return_to"];
            }
            //Services
            $response_array["departure_services"] = [];
            $response_array["return_services"] = [];
            foreach ($domd->getElementsByTagName("AABundledServiceExt") as $bundle) {
                foreach ($bundle->getElementsByTagName("bundledService") as $srvc) {
                    $arr["bundle_service_id"] = $srvc->getElementsByTagName("bunldedServiceId")->item(0)->nodeValue;
                    $arr["bundle_service_name"] = $srvc->getElementsByTagName("bundledServiceName")->item(0)->nodeValue;
                    $arr["per_pax_fee"] = $srvc->getElementsByTagName("perPaxBundledFee")->item(0)->nodeValue;
                    $arr["class"] = $srvc->getElementsByTagName("bookingClasses")->item(0)->nodeValue;
                    $arr["description"] = $srvc->getElementsByTagName("description")->item(0)->nodeValue;
                    $arr["included_services"] = [];
                    $incArr = [];
                    foreach ($srvc->getElementsByTagName("includedServies") as $inc) {
                        $incArr[] = $inc->nodeValue;
                    }
                    $arr["included_services"][] = $incArr;
                    if ($bundle->getAttribute("applicableOnd") == $departure_ond) {
                        $response_array["departure_services"][] = $arr;
                    } else {
                        $response_array["return_services"][] = $arr;
                    }
                }
            }
            //Taxes & Fees
            $response_array["fare_info"] = [];
            foreach ($domd->getElementsByTagName("PTC_FareBreakdown") as $fare) {
                $fareArr["pax_code"] = $fare->getElementsByTagName("PassengerTypeQuantity")->item(0)->getAttribute("Code");
                $fareArr["pax_quantity"] = $fare->getElementsByTagName("PassengerTypeQuantity")->item(0)->getAttribute("Quantity");
                $fareArr["base_fare"] = $fare->getElementsByTagName("BaseFare")->item(0)->getAttribute("Amount") + 0;
                $fareArr["currency"] = $fare->getElementsByTagName("BaseFare")->item(0)->getAttribute("CurrencyCode");
                $fareArr["total_taxes"] = 0;
                $fareArr["total_fees"] = 0;
                $fareArr["taxes"] = [];
                $fareArr["fees"] = [];
                foreach ($fare->getElementsByTagName("Tax") as $tax) {
                    $fareArr["total_taxes"] += ($tax->getAttribute("Amount") + 0);
                    $tax_arr["amount"] = $tax->getAttribute("Amount");
                    $tax_arr["tax_code"] = $tax->getAttribute("TaxCode");
                    $tax_arr["tax_name"] = $tax->getAttribute("TaxName");
                    $fareArr["taxes"][] = $tax_arr;
                }
                foreach ($fare->getElementsByTagName("Fee") as $fee) {
                    $fareArr["total_fees"] += ($fee->getAttribute("Amount") + 0);
                    $fee_arr["amount"] = $fee->getAttribute("Amount");
                    $fee_arr["fee_code"] = $fee->getAttribute("FeeCode");
                    $fareArr["fees"][] = $fee_arr;
                }
                $fareArr["total_fare"] = $fare->getElementsByTagName("TotalFare")->item(0)->getAttribute("Amount") + 0;
                $fareArr["pax_ref"] = [];
                foreach ($fare->getElementsByTagName("TravelerRefNumber") as $pRef) {
                    $fareArr["pax_ref"][] = $pRef->getAttribute("RPH");
                }
                $response_array["fare_info"][] = $fareArr;
            }

            return $response_array;
        } catch (Exception $e) {
            //TODO: handle error
            return false;
        }
    } //processGetPriceResults

    public static function processGetPriceResultBundles($results, $dep_ret = "d", $params)
    {
        $flight_fare = [
            "T1" => [
                "sol_id" => 0,
                "lfid" => 0,
                "type_id" => 0,
                "name" => "BASIC",
                "amount" => 0,
                "taxes" => 0,
                "amount_no_tax" => 0,
                "carryon" => "10 Kg Carry-on Baggage",
                "bag" => "NO",
                "seat" => "NO",
                "meal" => "NO",
                "modification" => "NO",
                "cancellation" => "NO",
                "description" => null
            ],
            "T2" => [
                "sol_id" => 0,
                "lfid" => 0,
                "type_id" => 0,
                "name" => "VALUE",
                "amount" => 0,
                "taxes" => 0,
                "amount_no_tax" => 0,
                "carryon" => "10 Kg Carry-on Baggage",
                "bag" => "20 Kg Checked Baggage",
                "seat" => "Free Seat (row 8 onwards)",
                "meal" => "Sandwich and Water",
                "modification" => "Free Modification (One Up to 8h)",
                "cancellation" => "Free Cancellation (Up to 8h)",
                "description" => null
            ],
            "T3" => [
                "sol_id" => 0,
                "lfid" => 0,
                "type_id" => 0,
                "name" => "ULTIMATE",
                "amount" => 0,
                "taxes" => 0,
                "amount_no_tax" => 0,
                "carryon" => "10 Kg Carry-on Baggage",
                "bag" => "30 Kg Checked Baggage",
                "seat" => "Free Seat",
                "meal" => "Free Meal",
                "modification" => "Free Modification (One Up to 8h)",
                "cancellation" => "Free Cancellation (Up to 8h)",
                "description" => null
            ]
        ];

        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());
            $response_array = [];

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $transaction_identifier = $domd->getElementsByTagName("OTA_AirPriceRS")->item(0)->getAttribute("TransactionIdentifier");

            $segment_groups = $params["is_return"] ? 2 : 1;
            $basic_fare_info = $domd->getElementsByTagName("ItinTotalFare")->item(0);
            $basic_amount_no_tax = ((float)$basic_fare_info->getElementsByTagName("BaseFare")->item(0)->getAttribute("Amount")) / $segment_groups;
            $basic_amount_total = ((float)$basic_fare_info->getElementsByTagName("TotalFareWithCCFee")->item(0)->getAttribute("Amount")) / $segment_groups;

            $basic_tax = $basic_amount_total - $basic_amount_no_tax;
            $flight_fare["T1"]["sol_id"] = 0;
            $flight_fare["T1"]["amount"] = $basic_amount_total;
            $flight_fare["T1"]["taxes"] = $basic_tax;
            $flight_fare["T1"]["amount_no_tax"] = $basic_amount_no_tax;

            $bun_ind = $dep_ret == 'r' ? 1 : 0;
            $bundles_ext = $domd->getElementsByTagName("AABundledServiceExt");
            $has_bundles = false;
            foreach ($bundles_ext as $bnd) {
                $appl_ond = (int)$bnd->getAttribute("applicableOndSequence");
                if ($bun_ind == $appl_ond) {
                    $has_bundles = true;
                    $bundles = $bnd->getElementsByTagName("bundledService");
                    foreach ($bundles as $bundle) {
                        $name = $bundle->getElementsByTagName("bundledServiceName")->item(0)->nodeValue;
                        $sol_id = $bundle->getElementsByTagName("bunldedServiceId")->item(0)->nodeValue;
                        $amount = $bundle->getElementsByTagName("perPaxBundledFee")->item(0)->nodeValue;
                        $description_node = $bundle->getElementsByTagName("description");
                        $description = $description_node ? $description_node->item(0)->nodeValue : null;
                        if ($name == "Value") {
                            $flight_fare["T2"]["sol_id"] = $sol_id;
                            $flight_fare["T2"]["amount"] = $basic_amount_total + $amount;
                            $flight_fare["T2"]["taxes"] = $basic_tax + $amount;
                            $flight_fare["T2"]["amount_no_tax"] = $basic_amount_no_tax;
                            $flight_fare["T2"]["description"] = $description;
                        }
                        if ($name == "Ultimate") {
                            $flight_fare["T3"]["sol_id"] = $sol_id;
                            $flight_fare["T3"]["amount"] = $basic_amount_total + $amount;
                            $flight_fare["T3"]["taxes"] = $basic_tax + $amount;
                            $flight_fare["T3"]["amount_no_tax"] = $basic_amount_no_tax;
                            $flight_fare["T3"]["description"] = $description;
                        }
                    }
                }
            }

            if (!$has_bundles) {
                unset($flight_fare["T2"]);
                unset($flight_fare["T3"]);
            }
            /*
            $bundles = $domd->getElementsByTagName("AABundledServiceExt")->item($bun_ind)->getElementsByTagName("bundledService");
            foreach ($bundles as $bundle) {
                $name = $bundle->getElementsByTagName("bundledServiceName")->item(0)->nodeValue;
                $sol_id = $bundle->getElementsByTagName("bunldedServiceId")->item(0)->nodeValue;
                $amount = $bundle->getElementsByTagName("perPaxBundledFee")->item(0)->nodeValue;
                $description_node = $bundle->getElementsByTagName("description");
                $description = $description_node ? $description_node->item(0)->nodeValue : null;
                if ($name == "Value") {
                    $flight_fare["T2"]["sol_id"] = $sol_id;
                    $flight_fare["T2"]["amount"] = $basic_amount_total + $amount;
                    $flight_fare["T2"]["taxes"] = $basic_tax + $amount;
                    $flight_fare["T2"]["amount_no_tax"] = $basic_amount_no_tax;
                    $flight_fare["T2"]["description"] = $description;
                }
                if ($name == "Extra") {
                    $flight_fare["T3"]["sol_id"] = $sol_id;
                    $flight_fare["T3"]["amount"] = $basic_amount_total + $amount;
                    $flight_fare["T3"]["taxes"] = $basic_tax + $amount;
                    $flight_fare["T3"]["amount_no_tax"] = $basic_amount_no_tax;
                    $flight_fare["T3"]["description"] = $description;
                }
            }
*/
            $flight_fare["passengers"] = [];
            $ptc_br = $domd->getElementsByTagName("PTC_FareBreakdown");
            foreach ($ptc_br as $br) {
                $p_code = $br->getElementsByTagName("PassengerTypeQuantity")->item(0)->getAttribute("Code");
                $flight_fare["passengers"][$p_code] = [];
                $p_refs = $br->getElementsByTagName("TravelerRefNumber");
                foreach ($p_refs as $p_ref) {
                    $flight_fare["passengers"][$p_code][] = $p_ref->getAttribute("RPH");
                }
            }
            //$response_array["transaction_identifier"] = $domd->getElementsByTagName("OTA_AirPriceRS")->item(0)->getAttribute("TransactionIdentifier");
            return [
                "fares" => $flight_fare,
                "transaction_identifier" => $transaction_identifier
            ];
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
            return $response_array;
        }
    } //processGetPriceResultBundles

    public static function processGetPriceResultsConfirmationStep($results)
    {
        $response_array = [];
        //results are from get price
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $response_array["departure"] = null;
            $response_array["return"] = null;

            $is_return = count($domd->getElementsByTagName("AABundledServiceExt")) > 1;
            $fl_segments = $domd->getElementsByTagName("FlightSegment");

            $orig_dest_count = $is_return ? 2 : 1;
            $total_segments = count($fl_segments);
            $num_segments_per_flight = $total_segments / $orig_dest_count;
            //TODO: replace with num segments for departure and return

            for ($i = 0; $i < $orig_dest_count; $i++) {
                $first_of = $i * $num_segments_per_flight;
                $last_of = $is_return ? (($total_segments / (2 - $i)) - 1) : ($total_segments - 1);

                $fl = [];
                $fl["from"] = $fl_segments->item($first_of)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $fl["to"] = $fl_segments->item($last_of)->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $fl["flight_number"] = $fl_segments->item($first_of)->getAttribute("FlightNumber");
                $fl["departure_time"] = $fl_segments->item($first_of)->getAttribute("DepartureDateTime");
                $fl["arrival_time"] = $fl_segments->item($last_of)->getAttribute("ArrivalDateTime");
                $fl["transaction_identifier"] = $domd->getElementsByTagName("OTA_AirPriceRS")->item(0)->getAttribute("TransactionIdentifier");
                $fl["stops"] = [];

                for ($j = $first_of; $j < ($last_of + 1); $j++) {
                    $st = [];
                    $st["from"] = $fl_segments->item($j)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                    $st["to"] = $fl_segments->item($j)->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                    $st["flight_number"] = $fl_segments->item($j)->getAttribute("FlightNumber");
                    $st["departure_time"] = $fl_segments->item($j)->getAttribute("DepartureDateTime");
                    $st["arrival_time"] = $fl_segments->item($j)->getAttribute("ArrivalDateTime");
                    $st["rph"] = $fl_segments->item($j)->getAttribute("RPH");
                    $fl["stops"][] = $st;
                }

                if ($i == 0) {
                    $response_array["departure"] = $fl;
                } else {
                    $response_array["return"] = $fl;
                }
            }

            $fare_brs = $domd->getElementsByTagName("PTC_FareBreakdown");
            $amount = 0;
            $taxes = 0;
            $pax_codes = [];
            foreach ($fare_brs as $fr) {
                $quantity = (int)$fr->getElementsByTagName("PassengerTypeQuantity")->item(0)->getAttribute("Quantity");
                $pax_type = $fr->getElementsByTagName("PassengerTypeQuantity")->item(0)->getAttribute("Code");
                $pass_fare = $fr->getElementsByTagName("PassengerFare");
                foreach ($pass_fare as $fr2) {
                    $amount += (number_format($fr2->getElementsByTagName("BaseFare")->item(0)->getAttribute("Amount"), 2) * $quantity);
                    $taxes_nd = $fr2->getElementsByTagName("Tax");
                    foreach ($taxes_nd as $tax) {
                        $taxes += (number_format($tax->getAttribute("Amount"), 2) * $quantity);
                    }
                    $fees_nd = $fr2->getElementsByTagName("Fee");
                    foreach ($fees_nd as $fee) {
                        $taxes += (number_format($fee->getAttribute("Amount"), 2) * $quantity);
                    }
                }
                $paxs = $fr->getElementsByTagName("TravelerRefNumber");
                foreach ($paxs as $pxr) {
                    $pax_codes[$pax_type][] = $pxr->getAttribute("RPH");
                }
            }

            $response_array["amount"] = $amount;
            $response_array["taxes"] = $taxes;
            $response_array["total"] = $amount + $taxes;
            $response_array["pax_codes"] = $pax_codes;
            return $response_array;
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
        }
        return $response_array;
    } //processGetPriceResultsConfirmationStep

    public static function processGetPriceResultsForAncillaries($results)
    {
        $response_array = [];
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $response_array["transaction_identifier"] = $domd->getElementsByTagName("OTA_AirPriceRS")->item(0)->getAttribute("TransactionIdentifier");
            $response_array["segments"] = [];

            $flight_segments = $domd->getElementsByTagName("FlightSegment");
            foreach ($flight_segments as $seg) {
                $arr = [];
                $arr["departure_date"] = $seg->getAttribute("DepartureDateTime");
                $arr["arrival_date"] = $seg->getAttribute("ArrivalDateTime");
                $arr["flight_number"] = $seg->getAttribute("FlightNumber");
                $arr["rph"] = $seg->getAttribute("RPH");
                $arr["origin"] = $seg->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $arr["destination"] = $seg->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $response_array["segments"][] = $arr;
            }
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
        }

        return $response_array;
    } //processGetPriceResultsForAncillaries

    public static function generateSeatMapRequest($params)
    {
        //params is the output of processGetPriceResultsForAncillaries
        try {
            $xml = new DOMDocument();
            $xml->load(app_path("Services/Airlines/AirArabia/XML/SeatRequest.xml"));
            $xml->getElementsByTagName("Username")->item(0)->nodeValue = config("airlines.AirArabia.username");
            $xml->getElementsByTagName("Password")->item(0)->nodeValue = config("airlines.AirArabia.password");
            $xml->getElementsByTagName("Source")->item(0)->setAttribute("TerminalID", config("airlines.AirArabia.agent_code"));
            $xml->getElementsByTagName("RequestorID")->item(0)->setAttribute("ID", config("airlines.AirArabia.username"));
            $xml->getElementsByTagName("OTA_AirSeatMapRQ")->item(0)->setAttribute("TransactionIdentifier", $params["transaction_identifier"]);

            $seat_req = $xml->getElementsByTagName("SeatMapRequest")->item(0);
            $seat_req_clone = $seat_req->cloneNode(true);
            $seat_req->parentNode->removeChild($seat_req);

            foreach ($params["segments"] as $seg) {
                $seat = $seat_req_clone->cloneNode(true);
                $seat->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("DepartureDateTime", $seg["departure_date"]);
                $seat->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("ArrivalDateTime", $seg["arrival_date"]);
                $seat->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("FlightNumber", $seg["flight_number"]);
                $seat->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("RPH", $seg["rph"]);
                $seat->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("DepartureAirport")->item(0)->setAttribute("LocationCode", $seg["origin"]);
                $seat->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("ArrivalAirport")->item(0)->setAttribute("LocationCode", $seg["destination"]);
                $seat->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("OperatingAirline")->item(0)->setAttribute("Code", substr($seg["flight_number"], 0, 2));
                $xml->getElementsByTagName("SeatMapRequests")->item(0)->appendChild($seat);
            }

            return trim($xml->saveXML());
        } catch (Exception $e) {
            //TODO: handle error
            return false;
        }
    } //generateSeatMapRequest

    public static function processSeatMapResults($results, $req_params)
    {
        $response_array = [];
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());
            $response_array = [];

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $response_array["segments"] = [];
            foreach ($domd->getElementsByTagName("SeatMapResponse") as $seat_response) {
                $origin = $seat_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $destination = $seat_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $segment_code = $seat_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getAttribute("SegmentCode");
                $flight_number = $seat_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getAttribute("FlightNumber");
                $seats = [];
                foreach ($seat_response->getElementsByTagName("AirRow") as $row) {
                    $s_row = [];
                    $s_row["row_number"] = $row->getAttribute("RowNumber");
                    $s_row["seats"] = [];
                    foreach ($row->getElementsByTagName("AirSeat") as $seat) {
                        $seat_info = [];
                        $seat_info["row_number"] = $row->getAttribute("RowNumber");
                        $seat_info["seat_number"] = $seat->getAttribute("SeatNumber");
                        $seat_info["availability"] = $seat->getAttribute("SeatAvailability");
                        $seat_info["charge"] = $seat->getAttribute("SeatCharacteristics");
                        //$seat_info["rph"] = $seat_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getAttribute("RPH");
                        $seat_info["flight_number"] = $flight_number;
                        $seat_info["origin"] = $origin;
                        $seat_info["destination"] = $destination;
                        foreach ($req_params["segments"] as $rseg) {
                            if (trim($rseg["flight_number"]) == trim($flight_number)) {
                                $seat_info["rph"] = $rseg["rph"];
                            }
                        }
                        $s_row["seats"][] = $seat_info;
                    }
                    $seats[] = $s_row;
                }
                $response_array["segments"][] = [
                    "origin" => $origin,
                    "destination" => $destination,
                    "segment_code" => $segment_code,
                    "flight_number" => $flight_number,
                    "seats" => $seats
                ];
            }

            return $response_array;
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
        }
        return $response_array;
    } //processSeatMapResults

    public static function generateMealRequest($params)
    {
        //params is the output of processGetPriceResultsForAncillaries
        try {
            $xml = new DOMDocument();
            $xml->load(app_path("Services/Airlines/AirArabia/XML/MealRequest.xml"));
            $xml->getElementsByTagName("Username")->item(0)->nodeValue = config("airlines.AirArabia.username");
            $xml->getElementsByTagName("Password")->item(0)->nodeValue = config("airlines.AirArabia.password");
            $xml->getElementsByTagName("Source")->item(0)->setAttribute("TerminalID", config("airlines.AirArabia.agent_code"));
            $xml->getElementsByTagName("RequestorID")->item(0)->setAttribute("ID", config("airlines.AirArabia.username"));
            $xml->getElementsByTagName("AA_OTA_AirMealDetailsRQ")->item(0)->setAttribute("TransactionIdentifier", $params["transaction_identifier"]);

            $meal_req = $xml->getElementsByTagName("MealDetailsRequest")->item(0);
            $meal_req_clone = $meal_req->cloneNode(true);
            $meal_req->parentNode->removeChild($meal_req);

            foreach ($params["segments"] as $seg) {
                $meal = $meal_req_clone->cloneNode(true);
                $meal->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("DepartureDateTime", $seg["departure_date"]);
                $meal->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("ArrivalDateTime", $seg["arrival_date"]);
                $meal->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("FlightNumber", $seg["flight_number"]);
                $meal->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("RPH", $seg["rph"]);
                $meal->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("DepartureAirport")->item(0)->setAttribute("LocationCode", $seg["origin"]);
                $meal->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("ArrivalAirport")->item(0)->setAttribute("LocationCode", $seg["destination"]);
                $meal->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("OperatingAirline")->item(0)->setAttribute("Code", substr($seg["flight_number"], 0, 2));
                $xml->getElementsByTagName("MealDetailsRequests")->item(0)->appendChild($meal);
            }
            return trim($xml->saveXML());
        } catch (Exception $e) {
            //TODO: handle error
            return false;
        }
    } //generateMealRequest

    public static function processMealResults($results, $req_params)
    {
        $response_array = [];
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());
            $response_array = [];

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $response_array["segments"] = [];
            foreach ($domd->getElementsByTagName("MealDetailsResponse") as $meal_response) {
                $origin = $meal_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $destination = $meal_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $order = 0;
                $meals = [];
                foreach ($meal_response->getElementsByTagName("Meal") as $meal) {
                    $meal_info = [];
                    $flight_number = $meal_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getAttribute("FlightNumber");
                    $meal_info["code"] = $meal->getElementsByTagName("mealCode")->item(0)->nodeValue;
                    $meal_info["description"] = $meal->getElementsByTagName("mealDescription")->item(0)->nodeValue;
                    $meal_info["charge"] = $meal->getElementsByTagName("mealCharge")->item(0)->nodeValue;
                    $meal_info["name"] = $meal->getElementsByTagName("mealName")->item(0)->nodeValue;
                    $meal_info["available_meals"] = $meal->getElementsByTagName("availableMeals")->item(0)->nodeValue;
                    $meal_info["sold_meals"] = $meal->getElementsByTagName("soldMeals")->item(0)->nodeValue;
                    $meal_info["allocated_meals"] = $meal->getElementsByTagName("allocatedMeals")->item(0)->nodeValue;
                    $meal_info["image_url"] = $meal->getElementsByTagName("mealImageLink")->item(0)->nodeValue;
                    $meal_info["category"] = $meal->getElementsByTagName("mealCategoryCode")->item(0)->nodeValue;
                    //$meal_info["rph"] = $meal_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getAttribute("RPH");
                    $meal_info["flight_number"] = $flight_number;
                    $meal_info["origin"] = $origin;
                    $meal_info["destination"] = $destination;
                    $meal_info["departure_date"] = $meal_response->getElementsByTagName("FlightSegmentInfo")->item(0)->getAttribute("DepartureDateTime");
                    foreach ($req_params["segments"] as $key => $rseg) {
                        if (trim($rseg["flight_number"]) == trim($flight_number)) {
                            $meal_info["rph"] = $rseg["rph"];
                            $order = $key + 1;
                        }
                    }
                    $meals[] = $meal_info;
                }
                $response_array["segments"][] = [
                    "origin" => $origin,
                    "destination" => $destination,
                    "flight_order" => $order,
                    "meals" => $meals
                ];
            }

            //reOrder segments
            $segs = $response_array["segments"];
            uasort($segs, array(AirArabiaSupport::class, "compareFlightsOrder"));
            $response_array["segments"] = $segs;

            return $response_array;
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
        }
        return $response_array;
    } //processMealResults

    public static function generateBaggageRequest($params)
    {
        //params is the output of processGetPriceResultsForAncillaries
        try {
            $xml = new DOMDocument();
            $xml->load(app_path("Services/Airlines/AirArabia/XML/BaggageRequest.xml"));
            $xml->getElementsByTagName("Username")->item(0)->nodeValue = config("airlines.AirArabia.username");
            $xml->getElementsByTagName("Password")->item(0)->nodeValue = config("airlines.AirArabia.password");
            $xml->getElementsByTagName("Source")->item(0)->setAttribute("TerminalID", config("airlines.AirArabia.agent_code"));
            $xml->getElementsByTagName("RequestorID")->item(0)->setAttribute("ID", config("airlines.AirArabia.username"));
            $xml->getElementsByTagName("AA_OTA_AirBaggageDetailsRQ")->item(0)->setAttribute("TransactionIdentifier", $params["transaction_identifier"]);

            $bg_req = $xml->getElementsByTagName("BaggageDetailsRequest")->item(0);
            $bg_req_clone = $bg_req->cloneNode(true);
            $bg_req->parentNode->removeChild($bg_req);

            foreach ($params["segments"] as $seg) {
                $bag = $bg_req_clone->cloneNode(true);
                $bag->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("DepartureDateTime", $seg["departure_date"]);
                $bag->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("ArrivalDateTime", $seg["arrival_date"]);
                $bag->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("FlightNumber", $seg["flight_number"]);
                $bag->getElementsByTagName("FlightSegmentInfo")->item(0)->setAttribute("RPH", $seg["rph"]);
                $bag->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("DepartureAirport")->item(0)->setAttribute("LocationCode", $seg["origin"]);
                $bag->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("ArrivalAirport")->item(0)->setAttribute("LocationCode", $seg["destination"]);
                $bag->getElementsByTagName("FlightSegmentInfo")->item(0)->getElementsByTagName("OperatingAirline")->item(0)->setAttribute("Code", substr($seg["flight_number"], 0, 2));
                $xml->getElementsByTagName("BaggageDetailsRequests")->item(0)->appendChild($bag);
            }
            return trim($xml->saveXML());
        } catch (Exception $e) {
            //TODO: handle error
            return false;
        }
    } //generateBaggageRequest

    public static function processBaggageResults($results, $req_params)
    {
        $response_array = [];
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());
            $response_array = [];

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $response_array["segments"] = [];
            foreach ($domd->getElementsByTagName("OnDBaggageDetailsResponse") as $bag_response) {
                $segments = $bag_response->getElementsByTagName("OnDFlightSegmentInfo");
                $origin = $segments->item(0)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $destination = $segments->item(count($segments) - 1)->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $order = 0;
                $baggage = [];
                $segments_array = [];
                foreach ($segments as $seg) {
                    $seg_info = [];
                    $seg_info["departure_date"] = $seg->getAttribute("DepartureDateTime");
                    $seg_info["flight_number"] = trim($seg->getAttribute("FlightNumber"));
                    foreach ($req_params["segments"] as $key => $rseg) {
                        if (trim($rseg["flight_number"]) == $seg_info["flight_number"]) {
                            $seg_info["rph"] = $rseg["rph"];
                            $order = $key + 1;
                        }
                    }
                    $segments_array[] = $seg_info;
                }
                foreach ($bag_response->getElementsByTagName("Baggage") as $bag) {
                    $bag_info = [];
                    $flight_number = $segments->item(0)->getAttribute("FlightNumber");
                    $bag_info["code"] = $bag->getElementsByTagName("baggageCode")->item(0)->nodeValue;
                    $bag_info["description"] = $bag->getElementsByTagName("baggageDescription")->item(0)->nodeValue;
                    $bag_info["charge"] = $bag->getElementsByTagName("baggageCharge")->item(0)->nodeValue;
                    //$bag_info["rph"] = $segments->item(0)->getAttribute("RPH");
                    $bag_info["flight_number"] = $flight_number;
                    $bag_info["origin"] = $origin;
                    $bag_info["destination"] = $destination;
                    $bag_info["departure_date"] = $segments->item(0)->getAttribute("DepartureDateTime");
                    foreach ($req_params["segments"] as $rseg) {
                        if (trim($rseg["flight_number"]) == trim($flight_number)) {
                            $bag_info["rph"] = $rseg["rph"];
                        }
                    }
                    $bag_info["segments"] = $segments_array;
                    $baggage[] = $bag_info;
                }
                $response_array["segments"][] = [
                    "origin" => $origin,
                    "destination" => $destination,
                    "flight_order" => $order,
                    "baggage" => $baggage
                ];
            }

            //reOrder segments
            $segs = $response_array["segments"];
            uasort($segs, array(AirArabiaSupport::class, "compareFlightsOrder"));
            $response_array["segments"] = $segs;

            return $response_array;
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
        }
        return $response_array;
    } //processBaggageResults


    private static function compareFlightsOrder($f1, $f2)
    {
        return ($f1["flight_order"] > $f2["flight_order"]) ? 1 : -1;
    }

    public function processSummaryResults($results)
    {
        //results are output from get price
        $arr["travellers"] = [];
        $arr["departure"] = null;
        $arr["return"] = null;
        $arr["amount"] = 0;
        $arr["taxes"] = 0;

        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $arr["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $arr["Errors"][] = $err;
                }
                return $arr;
            }

            //TODO: Handle flights with multiple stops
            $flights = $domd->getElementsByTagName("OriginDestinationOption");
            for ($i = 0; $i < count($flights); $i++) {
                $segment = $flights->item($i)->getElementsByTagName("FlightSegment")->item(0);
                $fl = [];
                $fl["from"] = $segment->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $fl["to"] = $segment->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $fl["flight_number"] = $segment->getAttribute("FlightNumber");
                $fl["departure_time"] = $segment->getAttribute("DepartureDateTime");
                $fl["arrival_time"] = $segment->getAttribute("ArrivalDateTime");
                $st = []; //stops
                $fl["stops"][] = $st;

                if ($i == 0) {
                    $arr["departure"] = $fl;
                } else {
                    $arr["return"] = $fl;
                }
            }

            return $arr;
        } catch (Exception $e) {
            //TODO: handle error
            return false;
        }
    } //processSummaryResults

    public static function generateBookRequest($params, $with_payment = false)
    {
        try {
            $xml = new DOMDocument();
            $xml->load(app_path("Services/Airlines/AirArabia/XML/BookRequest.xml"));
            $xml->getElementsByTagName("Username")->item(0)->nodeValue = config("airlines.AirArabia.username");
            $xml->getElementsByTagName("Password")->item(0)->nodeValue = config("airlines.AirArabia.password");
            $xml->getElementsByTagName("Source")->item(0)->setAttribute("TerminalID", config("airlines.AirArabia.agent_code"));
            $xml->getElementsByTagName("RequestorID")->item(0)->setAttribute("ID", config("airlines.AirArabia.username"));
            $xml->getElementsByTagName("OTA_AirBookRQ")->item(0)->setAttribute("TransactionIdentifier", $params["transaction_identifier"]);
            $currentTimeStamp = new DateTime();
            $currentTimeStamp->setTimezone(new DateTimeZone('UTC'));
            $currentTimeStampStr = $currentTimeStamp->format('Y-m-d\TH-i-s.\0\0\0\Z');
            $xml->getElementsByTagName("OTA_AirBookRQ")->item(0)->setAttribute("TimeStamp", $currentTimeStampStr);

            $req_1 = $xml->getElementsByTagName("OriginDestinationOption")->item(0);
            $orig_dest_node = $req_1->cloneNode(true);
            $req_1->parentNode->removeChild($req_1);

            $orig_dest_count = ($params["return"] && count($params["return"]["stops"]) > 0) ? 2 : 1;
            for ($i = 0; $i < $orig_dest_count; $i++) {
                $stop_segments = $i == 0 ? $params["departure"]["stops"] : $params["return"]["stops"];
                $orig_dest_clone = $orig_dest_node->cloneNode(true);
                $segment_node = $orig_dest_clone->getElementsByTagName("FlightSegment")->item(0);
                $segment_clone = $segment_node->cloneNode(true);
                $segment_node->parentNode->removeChild($segment_node);
                foreach ($stop_segments as $stp) {
                    $seg = $segment_clone->cloneNode(true);
                    $seg->setAttribute("DepartureDateTime", $stp["departure_time"]);
                    $seg->setAttribute("ArrivalDateTime", $stp["arrival_time"]);
                    $seg->setAttribute("FlightNumber", $stp["flight_number"]);
                    $seg->setAttribute("RPH", $stp["rph"]);
                    $seg->getElementsByTagName("DepartureAirport")->item(0)->setAttribute("LocationCode", $stp["from"]);
                    $seg->getElementsByTagName("ArrivalAirport")->item(0)->setAttribute("LocationCode", $stp["to"]);
                    $seg->getElementsByTagName("OperatingAirline")->item(0)->setAttribute("Code", substr($stp["flight_number"], 0, 2));
                    $orig_dest_clone->appendChild($seg);
                }
                $xml->getElementsByTagName("OriginDestinationOptions")->item(0)->appendChild($orig_dest_clone);
            }

            //filling Pax Infos
            $addr = $xml->getElementsByTagName("AirTraveler")->item(0)->getElementsByTagName("Address")->item(0);
            $addr->parentNode->removeChild($addr);
            for ($i = 0; $i < sizeof($params["travellers"]); $i++) {
                if ($i == 0) {
                    $traveller = $xml->getElementsByTagName("AirTraveler")->item(0);
                } else {
                    $traveller = $xml->getElementsByTagName("AirTraveler")->item(0)->cloneNode(true);
                }
                $traveller->setAttribute("BirthDate", $params["travellers"][$i]["birth_date"] . "T00:00:00");
                $traveller->setAttribute("PassengerTypeCode", $params["travellers"][$i]["type_code"]);
                $traveller->getElementsByTagName("GivenName")->item(0)->nodeValue = $params["travellers"][$i]["first_name"];
                $traveller->getElementsByTagName("Surname")->item(0)->nodeValue = $params["travellers"][$i]["last_name"];
                $traveller->getElementsByTagName("NameTitle")->item(0)->nodeValue = $params["travellers"][$i]["title"];
                $traveller->getElementsByTagName("Telephone")->item(0)->setAttribute("CountryAccessCode", $params["travellers"][$i]["country_phone_code"]);
                $traveller->getElementsByTagName("Telephone")->item(0)->setAttribute("PhoneNumber", $params["travellers"][$i]["phone_number"]);
                //$traveller->getElementsByTagName("CountryName")->item(0)->setAttribute("Code", $params["travellers"][$i]["country_name_code"]);
                $traveller->getElementsByTagName("Document")->item(0)->setAttribute("DocID", $params["travellers"][$i]["document_id"]);
                $traveller->getElementsByTagName("Document")->item(0)->setAttribute("DocType", $params["travellers"][$i]["document_type"]);
                $traveller->getElementsByTagName("Document")->item(0)->setAttribute("DocHolderNationality", $params["travellers"][$i]["document_nationality"]);
                $traveller->getElementsByTagName("Document")->item(0)->setAttribute("DocIssueCountry", $params["travellers"][$i]["document_nationality"]);
                $traveller->getElementsByTagName("Document")->item(0)->setAttribute("ExpireDate", $params["travellers"][$i]["document_expiry"]);
                $traveller->getElementsByTagName("TravelerRefNumber")->item(0)->setAttribute("RPH", $params["travellers"][$i]["ref_code"]);
                if ($i > 0) {
                    $xml->getElementsByTagName("TravelerInfo")->item(0)->appendChild($traveller);
                }
            }

            //filling contact info
            $contact = $xml->getElementsByTagName("AAAirBookRQExt")->item(0)->getElementsByTagName("ContactInfo")->item(0);
            //$contact->parentNode->removeChild($contact);
            $contact->getElementsByTagName("PersonName")->item(0)->getElementsByTagName("Title")->item(0)->nodeValue = config("contact_info.title");
            $contact->getElementsByTagName("PersonName")->item(0)->getElementsByTagName("FirstName")->item(0)->nodeValue = config("contact_info.first_name");
            $contact->getElementsByTagName("PersonName")->item(0)->getElementsByTagName("LastName")->item(0)->nodeValue = config("contact_info.last_name");
            $contact->getElementsByTagName("Telephone")->item(0)->getElementsByTagName("PhoneNumber")->item(0)->nodeValue = config("contact_info.phone_number");
            $contact->getElementsByTagName("Telephone")->item(0)->getElementsByTagName("CountryCode")->item(0)->nodeValue = config("contact_info.country_code");
            $contact->getElementsByTagName("Email")->item(0)->nodeValue = config("contact_info.email");
            $contact->getElementsByTagName("Address")->item(0)->getElementsByTagName("AddressLine1")->item(0)->nodeValue = config("contact_info.address");
            $contact->getElementsByTagName("Address")->item(0)->getElementsByTagName("CityName")->item(0)->nodeValue = config("contact_info.city");
            $contact->getElementsByTagName("Address")->item(0)->getElementsByTagName("CountryName")->item(0)->getElementsByTagName("CountryName")->item(0)->nodeValue = config("contact_info.country_name");
            $contact->getElementsByTagName("Address")->item(0)->getElementsByTagName("CountryName")->item(0)->getElementsByTagName("CountryCode")->item(0)->nodeValue = config("contact_info.country_name_code");

            $payment_node = $xml->getElementsByTagName("Fulfillment")->item(0);
            if ($with_payment) {
                $payment_node->getElementsByTagName("CompanyName")->item(0)->setAttribute("Code", config("airlines.AirArabia.agent_code_all_requests"));
                $payment_node->getElementsByTagName("PaymentAmount")->item(0)->setAttribute("CurrencyCode", config("airlines.AirArabia.currency"));
                $payment_node->getElementsByTagName("PaymentAmount")->item(0)->setAttribute("Amount", $params["total_payment"]);
            } else {
                $payment_node->parentNode->removeChild($payment_node);
            }

            return trim($xml->saveXML());
        } catch (Exception $e) {
            //TODO: handle error
            return false;
        }
    } //generateBookRequest

    public static function processBookResults($results, $params)
    {
        $response_array = [];
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
                return $response_array;
            }

            $response_array["travellers"] = [];
            $response_array["departure"] = null;
            $response_array["return"] = null;
            $response_array["amount"] = 0;
            $response_array["taxes"] = 0;
            $response_array["booking_num"] = $domd->getElementsByTagName("BookingReferenceID")->item(0)->getAttribute("ID");
            $response_array["ticket_advisory"] = $domd->getElementsByTagName("TicketAdvisory")->item(0)->nodeValue;
            $response_array["on_hold_expiry_date"] = $domd->getElementsByTagName("Ticketing")->item(0)->getAttribute("TicketTimeLimit");

            $ota_rs = $domd->getElementsByTagName("OTA_AirBookRS");
            if ($ota_rs) {
                $response_array["transaction_identifier"] = $ota_rs->item(0)->getAttribute("TransactionIdentifier");
            }

            $segments = $domd->getElementsByTagName("FlightSegment");
            $departure_segment_count = $params ? count($params["departure"]["stops"]) : count($segments);
            $return_segment_count = isset($params["return"]["stops"]) ? count($params["return"]["stops"]) : 0;
            $is_return = $return_segment_count > 0;

            for ($i = 0; $i < ($is_return ? 2 : 1); $i++) {
                $first_of = ($i > 0) ? $departure_segment_count : 0;
                $last_of = ($i > 0) ? (($departure_segment_count + $return_segment_count) - 1) : ($departure_segment_count - 1);
                $fl = [];
                $fl["from"] = $segments->item($first_of)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                $fl["to"] = $segments->item($last_of)->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                $fl["flight_number"] = $segments->item($first_of)->getAttribute("FlightNumber");
                $fl["departure_time"] = $segments->item($first_of)->getAttribute("DepartureDateTime");
                $fl["arrival_time"] = $segments->item($last_of)->getAttribute("ArrivalDateTime");
                $fl["terminal"] = $segments->item($first_of)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("Terminal");
                $fl["stops"] = [];

                for ($j = $first_of; $j <= $last_of; $j++) {
                    $st = [];
                    $st["from"] = $segments->item($j)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("LocationCode");
                    $st["to"] = $segments->item($j)->getElementsByTagName("ArrivalAirport")->item(0)->getAttribute("LocationCode");
                    $st["flight_number"] = $segments->item($j)->getAttribute("FlightNumber");
                    $st["departure_time"] = $segments->item($j)->getAttribute("DepartureDateTime");
                    $st["arrival_time"] = $segments->item($j)->getAttribute("ArrivalDateTime");
                    $st["terminal"] = $segments->item($j)->getElementsByTagName("DepartureAirport")->item(0)->getAttribute("Terminal");
                    $fl["stops"][] = $st;
                }

                if ($i == 0) {
                    $response_array["departure"] = $fl;
                } else {
                    $response_array["return"] = $fl;
                }
            }

            //Taxes & Fees
            $response_array["itin_total_fare"] = $domd->getElementsByTagName("ItinTotalFare")->item(0)->getElementsByTagName("TotalFare")->item(0)->getAttribute("Amount");
            $response_array["fare_info"] = [];
            $response_array["amount"] = 0;
            $response_array["taxes"] = 0;
            foreach ($domd->getElementsByTagName("PTC_FareBreakdown") as $fare) {
                $fareArr["pax_code"] = $fare->getElementsByTagName("PassengerTypeQuantity")->item(0)->getAttribute("Code");
                $fareArr["pax_quantity"] = $fare->getElementsByTagName("PassengerTypeQuantity")->item(0)->getAttribute("Quantity");
                $fareArr["base_fare"] = $fare->getElementsByTagName("BaseFare")->item(0)->getAttribute("Amount") + 0;
                $fareArr["currency"] = $fare->getElementsByTagName("BaseFare")->item(0)->getAttribute("CurrencyCode");
                $fareArr["total_taxes"] = 0;
                $fareArr["total_fees"] = 0;
                $fareArr["taxes"] = [];
                $fareArr["fees"] = [];
                foreach ($fare->getElementsByTagName("Tax") as $tax) {
                    $fareArr["total_taxes"] += ($tax->getAttribute("Amount") + 0);
                    $tax_arr["amount"] = $tax->getAttribute("Amount");
                    $tax_arr["tax_code"] = $tax->getAttribute("TaxCode");
                    $tax_arr["tax_name"] = $tax->getAttribute("TaxName");
                    $fareArr["taxes"][] = $tax_arr;
                }
                foreach ($fare->getElementsByTagName("Fee") as $fee) {
                    $fareArr["total_fees"] += ($fee->getAttribute("Amount") + 0);
                    $fee_arr["amount"] = $fee->getAttribute("Amount");
                    $fee_arr["fee_code"] = $fee->getAttribute("FeeCode");
                    $fareArr["fees"][] = $fee_arr;
                }
                $fareArr["total_fare"] = $fare->getElementsByTagName("TotalFare")->item(0)->getAttribute("Amount") + 0;
                $fareArr["pax_ref"] = [];
                foreach ($fare->getElementsByTagName("TravelerRefNumber") as $pRef) {
                    $fareArr["pax_ref"][] = $pRef->getAttribute("RPH");
                }
                $response_array["fare_info"][] = $fareArr;
                $response_array["amount"] += $fareArr["base_fare"];
                $response_array["taxes"] += ($fareArr["total_taxes"] + $fareArr["total_fees"]);
            }

            //Travellers
            $response_array["travellers"] = [];
            foreach ($domd->getElementsByTagName("AirTraveler") as $pax) {
                $ntitle = $pax->getElementsByTagName("NameTitle");
                $doc_info = $pax->getElementsByTagName("Document");
                $pax_arr["title"] = count($ntitle) > 0 ? $ntitle->item(0)->nodeValue : "";
                $pax_arr["first_name"] = $pax->getElementsByTagName("GivenName")->item(0)->nodeValue;
                $pax_arr["last_name"] = $pax->getElementsByTagName("Surname")->item(0)->nodeValue;
                $pax_arr["dob"] = $pax->getAttribute("BirthDate");
                $pax_arr["passport"] = count($doc_info) > 0 ? $doc_info->item(0)->getAttribute("DocID") : "";
                $pax_arr["nationality"] = count($doc_info) > 0 ? $doc_info->item(0)->getAttribute("DocHolderNationality") : "";
                $pax_type = "ADULT";
                $pax_type = $pax->getAttribute("PassengerTypeCode") == "CHD" ? "CHILD" : $pax_type;
                $pax_type = $pax->getAttribute("PassengerTypeCode") == "INF" ? "INFANT" : $pax_type;
                $pax_arr["type"] = $pax_type;
                $response_array["travellers"][] = $pax_arr;
            }

            //Services
            // $response_array["services"] = [];
            // foreach ($domd->getElementsByTagName("SeatRequest") as $seat) {
            //     $response_array["services"][$seat->getAttribute("FlightNumber")]["seats"][$seat->getAttribute("TravelerRefNumberRPHList")] = $seat->getAttribute("SeatNumber");
            // }
            // foreach ($domd->getElementsByTagName("MealRequest") as $meal) {
            //     $response_array["services"][$meal->getAttribute("FlightNumber")]["meals"][$meal->getAttribute("TravelerRefNumberRPHList")] = $meal->getAttribute("mealCode");
            // }
            // foreach ($domd->getElementsByTagName("BaggageRequest") as $bag) {
            //     $response_array["services"][$bag->getAttribute("FlightNumber")]["baggage"][$bag->getAttribute("TravelerRefNumberRPHList")] = $bag->getAttribute("baggageCode");
            // }

            //Ticket Advisory
            // $response_array["ticket_advisory"] = [];
            // foreach ($domd->getElementsByTagName("TicketAdvisory") as $advisory) {
            //     $response_array["ticket_advisory"][] = $advisory->nodeValue;
            // }

            return $response_array;
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
        }
        return $response_array;
    } //processBookResults

    public static function generateRetrievePnrRequest($params)
    {
        try {
            $xml = new DOMDocument();
            $xml->load(app_path("Services/Airlines/AirArabia/XML/RetrievePnrRequest.xml"));
            $xml->getElementsByTagName("Username")->item(0)->nodeValue = config("airlines.AirArabia.username");
            $xml->getElementsByTagName("Password")->item(0)->nodeValue = config("airlines.AirArabia.password");
            $xml->getElementsByTagName("Source")->item(0)->setAttribute("TerminalID", config("airlines.AirArabia.agent_code"));
            $xml->getElementsByTagName("RequestorID")->item(0)->setAttribute("ID", config("airlines.AirArabia.username"));
            $xml->getElementsByTagName("UniqueID")->item(0)->setAttribute("ID", $params["booking_ref"]);
            return trim($xml->saveXML());
        } catch (Exception $e) {
            return false;
        }
    } //generateRetrievePnrRequest

    public static function generateModifyPaymentRequest($params)
    {
        try {
            $xml = new DOMDocument();
            $xml->load(app_path("Services/Airlines/AirArabia/XML/ModifyBookingPaymentRequest.xml"));
            $xml->getElementsByTagName("Username")->item(0)->nodeValue = config("airlines.AirArabia.username");
            $xml->getElementsByTagName("Password")->item(0)->nodeValue = config("airlines.AirArabia.password");
            $xml->getElementsByTagName("Source")->item(0)->setAttribute("TerminalID", config("airlines.AirArabia.agent_code"));
            $xml->getElementsByTagName("RequestorID")->item(0)->setAttribute("ID", config("airlines.AirArabia.username"));
            $xml->getElementsByTagName("CompanyName")->item(0)->setAttribute("Code", config("airlines.AirArabia.agent_code_all_requests"));
            $xml->getElementsByTagName("OTA_AirBookModifyRQ")->item(0)->setAttribute("TransactionIdentifier", $params["transaction_identifier"]);
            $xml->getElementsByTagName("PaymentAmount")->item(0)->setAttribute("Amount", $params["charge"]);
            $xml->getElementsByTagName("PaymentAmount")->item(0)->setAttribute("CurrencyCode", config("airlines.AirArabia.currency"));
            $xml->getElementsByTagName("BookingReferenceID")->item(0)->setAttribute("ID", $params["booking_ref"]);
            return trim($xml->saveXML());
        } catch (Exception $e) {
            return false;
        }
    } //generateModifyPaymentRequest

    public static function getErrors($results)
    {
        $response_array = [];
        try {
            $response = new SimpleXMLElement($results);
            $domd = new DOMDocument();
            $domd->loadXML($response->asXML());

            $errors = $domd->getElementsByTagName("Error");
            if (count($errors) > 0) {
                $response_array["Errors"] = [];
                foreach ($errors as $error) {
                    $err["code"] = $error->getAttribute("Code");
                    $err["text"] = $error->getAttribute("ShortText");
                    $response_array["Errors"][] = $err;
                }
            }
        } catch (Exception $e) {
            $response_array["Errors"][] = [
                "code" => $e->getCode(),
                "text" => $e->getMessage()
            ];
        }
        return $response_array;
    } //getErrors
}
