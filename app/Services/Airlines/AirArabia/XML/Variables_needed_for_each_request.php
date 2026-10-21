<?php

//generateGetPriceRequest
$params["is_return"] = true;
$params["departure_departure_date"] = "2023-12-26T08:00:00";
$params["departure_arrival_date"] = "2023-12-26T08:10:00";
$params["departure_flight_number"] = "G9101";
$params["departure_from"] = "SHJ";
$params["departure_to"] = "BAH";
$params["return_departure_date"] = "2024-01-26T04:00:00";
$params["return_arrival_date"] = "2024-01-26T06:20:00";
$params["return_flight_number"] = "G9654";
$params["return_from"] = "BAH";
$params["return_to"] = "SHJ";
$params["adult_count"] = 2;
$params["child_count"] = 1;
$params["infant_count"] = 1;
$params["bundled_services"] = [];
$params["service_requests"]["seats"] = [];
$params["service_requests"]["meals"] = [];
$params["service_requests"]["baggages"] = [];
//services requests, keep empty if none
$params["bundled_services"]["departure"] = "15539";
$params["bundled_services"]["return"] = "15250";
$params["service_requests"]["seats"] =
    [
        [
            "seat_number" => "2A",
            "traveller_ref_number" => "A1",
            "flight_rph" => "G9\$SHJ/JAI$2797159$20221126234500$20221127042500",
            "departure_date" => "2022-06-26T05:05:00",
            "flight_num" => "G9435"
        ],
        [
            "seat_number" => "2B",
            "traveller_ref_number" => "A2",
            "flight_rph" => "G9\$SHJ/JAI$2797159$20221126234500$20221127042500",
            "departure_date" => "2022-06-26T05:05:00",
            "flight_num" => "G9435"
        ],
    ];
$params["service_requests"]["meals"] =
    [
        [
            "meal_code" => "SKWY",
            "quantity" => "1",
            "traveller_ref_number" => "A1",
            "flight_rph" => "G9\$SHJ/JAI$2797159$20221126234500$20221127042500",
            "departure_date" => "2022-06-26T05:05:00",
            "flight_num" => "G9435"
        ],
        [
            "meal_code" => "SKWY",
            "quantity" => "1",
            "traveller_ref_number" => "A2",
            "flight_rph" => "G9\$SHJ/JAI$2797159$20221126234500$20221127042500",
            "departure_date" => "2022-06-26T05:05:00",
            "flight_num" => "G9435"
        ],
    ];
$params["service_requests"]["baggages"] =
    [
        [
            "baggage_code" => "40 Kg Free Piece",
            "traveller_ref_number" => "A1",
            "flight_rph" => "G9\$SHJ/JAI$2797159$20221126234500$20221127042500",
            "departure_date" => "2022-06-26T05:05:00",
            "flight_num" => "G9435"
        ],
        [
            "baggage_code" => "40 Kg Free Piece",
            "traveller_ref_number" => "A2",
            "flight_rph" => "G9\$SHJ/JAI$2797159$20221126234500$20221127042500",
            "departure_date" => "2022-06-26T05:05:00",
            "flight_num" => "G9435"
        ],
    ];


//generateSeatMapRequest, generateMealRequest, generateBaggageRequest
$params["transaction_identifier"] = "TID$1666871217033528441.demo2144";
$params["departure_departure_date"] = "2022-11-26T23:45:00";
$params["departure_arrival_date"] = "2022-11-27T04:25:00";
$params["departure_flight_number"] = "G9435";
$params["departure_rph"] = "G9$SHJ/JAI$2797159$20221126234500$20221127042500";
$params["departure_from"] = "SHJ";
$params["departure_to"] = "JAI";
$params["return_departure_date"] = "2022-11-30T05:05:00";
$params["return_arrival_date"] = "2022-11-30T07:00:00";
$params["return_flight_number"] = "G9436";
$params["return_rph"] = "G9$JAI/SHJ$2797345$20221130050500$20221130070000";
$params["return_from"] = "JAI";
$params["return_to"] = "SHJ";


//generateBookRequest
$params["transaction_identifier"] = "TID$1666871217033528441.demo2144";
$params["departure_departure_date"] = "2022-11-26T23:45:00";
$params["departure_arrival_date"] = "2022-11-27T04:25:00";
$params["departure_flight_number"] = "G9435";
$params["departure_rph"] = "G9$SHJ/JAI$2797159$20221126234500$20221127042500";
$params["departure_from"] = "SHJ";
$params["departure_to"] = "JAI";
$params["return_departure_date"] = "2022-11-30T05:05:00";
$params["return_arrival_date"] = "2022-11-30T07:00:00";
$params["return_flight_number"] = "G9436";
$params["return_rph"] = "G9$JAI/SHJ$2797345$20221130050500$20221130070000";
$params["return_from"] = "JAI";
$params["return_to"] = "SHJ";
$params["travellers"] =
    [
        [
            "birth_date" => "1985-05-23",
            "type_code" => "ADT",
            "first_name" => "Waleed",
            "last_name" => "Tayyar",
            "title" => "Mr",
            "country_phone_code" => "961",
            "phone_number" => "7012548",
            "country_name_code" => "LB",
            "document_id" => "RL14522015",
            "document_type" => "PSPT",
            "document_nationality" => "LB",
            "ref_code" => "A1" //get this from getPrice response
        ],
        [
            "birth_date" => "1989-02-15",
            "type_code" => "ADT",
            "first_name" => "Lara",
            "last_name" => "Singer",
            "title" => "Mrs",
            "country_phone_code" => "961",
            "phone_number" => "715248",
            "country_name_code" => "LB",
            "document_id" => "RL58745",
            "document_type" => "PSPT",
            "document_nationality" => "LB",
            "ref_code" => "A2" //get this from getPrice response
        ],
        [
            "birth_date" => "2013-04-20",
            "type_code" => "CHD",
            "first_name" => "Walad",
            "last_name" => "Tayyar",
            "title" => "Mr",
            "country_phone_code" => "961",
            "phone_number" => "7012548",
            "country_name_code" => "LB",
            "document_id" => "RL214587",
            "document_type" => "PSPT",
            "document_nationality" => "LB",
            "ref_code" => "C3" //get this from getPrice response
        ],
        [
            "birth_date" => "2023-06-05",
            "type_code" => "INF",
            "first_name" => "Bent",
            "last_name" => "Tayyar",
            "title" => "Ms",
            "country_phone_code" => "961",
            "phone_number" => "7012548",
            "country_name_code" => "LB",
            "document_id" => "RL569874",
            "document_type" => "PSPT",
            "document_nationality" => "LB",
            "ref_code" => "C4" //get this from getPrice response
        ]
    ];
$params["contact"] =
    [
        "first_name" => "Khalil",
        "last_name" => "Sarabey",
        "title" => "Mr",
        "country_phone_code" => "961",
        "phone_number" => "34442255",
        "email" => "khalil@virtuosi.com",
        "address" => "Hamra",
        "city" => "Beirut",
        "country" => "Lebanon",
        "country_name_code" => "LB",
    ];
