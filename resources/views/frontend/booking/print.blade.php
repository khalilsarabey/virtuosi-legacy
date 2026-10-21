<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $site_settings->name }} - Booking Print</title>
    <link href="https://fonts.googleapis.com/css2?family=Gochi+Hand&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
        th {
            font-size: 13px;
        }
    </style>
    <x-brand-colors />
</head>
<body>
<div class="container">
    <div class="cart-section">
        <div class="w-100 py-3 text-center no-print" style="background-color: #eee">
            <a class="h6 text-dark" href="#" onclick="window.print(); return;"><i class="icon_printer"></i> PRINT TICKET</a>
        </div>
        <div class="logo-container d-flex justify-content-between align-items-center my-5">
            @if(auth()->user()->type == 'agency' || auth()->user()->type == 'white')
                @foreach(auth()->user()->logo as $key => $entry)
                    <img src="{{ $entry['url'] }}" height="50" />
                @endforeach
            @else
                @if($site_settings->print_logo)
                    @foreach($site_settings->print_logo as $key => $entry)
                        <img src="{{ $entry['url'] }}" height="50" />
                    @endforeach
                @else
                    <h5>{{ $site_settings->name }}</h5>
                @endif
            @endif
            <div class="text-right">
                <strong>RESERVATION NUMBER: </strong> {{ \Carbon\Carbon::parse($booking->created_at)->format('Y') .  str_pad($booking->id, 4, "0", STR_PAD_LEFT) }}
                <div class="h5 no-print">
                    TOTAL: ${{ number_format($booking->total) }}
                </div>
            </div>
        </div>
        <div class="margin_60">
            <div class="bg-white">
                @if($booking->flight_id || $booking->dep_from_code)
                    <div class="cart-section">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-primary text-uppercase"><strong>Departure</strong></h5>
                        </div>
                        <div class="row my-4" style="font-size: 1rem">
                            <div class="col text-uppercase">FROM<br/><strong>{{ $booking->dep_from_city ?? $booking->depFlight->depFrom->city }}</strong></div>
                            <div class="col text-uppercase">TO<br/><strong>{{ $booking->dep_to_city ?? $booking->depFlight->depTo->city }}</strong></div>
                            <div class="col text-uppercase">DEP DATE<br/><strong>{{ $booking->dep_date ?? $booking->depFlight->dep_date }}</strong></div>
                            <div class="col text-uppercase">CABIN<br/><strong>{{ $booking->dep_cabin }}</strong></div>
                            <div class="col text-uppercase">ADULT<br/><strong>{{ $booking->adults }}</strong></div>
                            <div class="col text-uppercase">CHILD<br/><strong>{{ $booking->children }}</strong></div>
                            <div class="col text-uppercase">INFANT<br/><strong>{{ $booking->infants }}</strong></div>
                        </div>
                        <div class="">
                            <div style="background: lightgrey; height:1px; width:100%"></div>
                        </div>
                        <table class="table table-striped">
                            <thead class="text-success text-uppercase">
                            <tr>
                                <th>
                                    Airline
                                </th>
                                <th>
                                    Flight Number
                                </th>
                                <th>
                                    Departure Time
                                </th>
                                <th>
                                    Arrival Time
                                </th>
                                <th>
                                    Duration
                                </th>
                                <th>
                                    Baggage Allowance
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="text-left">
                                <td>
                                    <strong>{{ $booking->dep_airline_name ?? $booking->depFlight->airline->name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $booking->dep_flight_number ?? $booking->depFlight->dep_flight_number }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $booking->dep_time ?? $booking->depFlight->dep_time }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $booking->dep_arr_time ?? $booking->depFlight->dep_arr_time }}</strong>
                                </td>
                                <td>
                                    @php
                                        $d = \Carbon\Carbon::parse($booking->dep_time ?? $booking->depFlight->dep_time);
                                        $a = \Carbon\Carbon::parse($booking->dep_arr_time ?? $booking->depFlight->dep_arr_time);
                                    @endphp
                                    <strong>{{ $a->diff($d)->format('%h:%I') }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $booking->baggage_allowance ?? $booking->depFlight->baggage_allowance ?? '-' }}</strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br/><br/>
                        <div class="mb-5">
                            <div style="background: lightgrey; height:1px; width:100%"></div>
                        </div>
                        @endif

                        @if($booking->ret_flight_id || $booking->ret_from_code)
                            <div class="d-flex">
                                <h5 class="text-primary text-uppercase"><strong>Return</strong></h5>
                            </div>
                            @if($booking->type == 'fixed')
                                <div class="row my-4" style="font-size: 1rem">
                                    <div class="col text-uppercase">FROM<br/><strong>{{ $booking->ret_from_city ?? $booking->retFlight->depTo->city }}</strong></div>
                                    <div class="col text-uppercase">TO<br/><strong>{{ $booking->ret_to_city ?? $booking->retFlight->depFrom->city }}</strong></div>
                                    <div class="col text-uppercase">DEP DATE<br/><strong>{{ $booking->ret_date ?? $booking->retFlight->ret_date }}</strong></div>
                                    <div class="col text-uppercase">CABIN<br/><strong>{{ $booking->ret_cabin }}</strong></div>
                                    <div class="col text-uppercase">ADULT<br/><strong>{{ $booking->adults }}</strong></div>
                                    <div class="col text-uppercase">CHILD<br/><strong>{{ $booking->children }}</strong></div>
                                    <div class="col text-uppercase">INFANT<br/><strong>{{ $booking->infants }}</strong></div>
                                </div>
                            @else
                                <div class="row my-4" style="font-size: 1rem">
                                    <div class="col text-uppercase">FROM<br/><strong>{{ $booking->ret_from_city ?? $booking->retFlight->depFrom->city }}</strong></div>
                                    <div class="col text-uppercase">TO<br/><strong>{{ $booking->ret_to_city ?? $booking->retFlight->depTo->city }}</strong></div>
                                    <div class="col text-uppercase">DEP DATE<br/><strong>{{ $booking->ret_date ?? $booking->retFlight->dep_date }}</strong></div>
                                    <div class="col text-uppercase">CABIN<br/><strong>{{ $booking->ret_cabin }}</strong></div>
                                    <div class="col text-uppercase">ADULT<br/><strong>{{ $booking->adults }}</strong></div>
                                    <div class="col text-uppercase">CHILD<br/><strong>{{ $booking->children }}</strong></div>
                                    <div class="col text-uppercase">INFANT<br/><strong>{{ $booking->infants }}</strong></div>
                                </div>
                            @endif
                            <div class="">
                                <div style="background: lightgrey; height:1px; width:100%"></div>
                            </div>
                            <table class="table table-striped cart-list shopping-cart">
                                <thead class="text-success text-uppercase">
                                <tr>
                                    <th>
                                        Airline
                                    </th>
                                    <th>
                                        Flight Number
                                    </th>
                                    <th>
                                        Departure Time
                                    </th>
                                    <th>
                                        Arrival Time
                                    </th>
                                    <th>
                                        Duration
                                    </th>
                                    <th>
                                        Baggage Allowance
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($booking->type == 'fixed')
                                    <tr class="text-left">
                                        <td>
                                            <strong>{{ $booking->ret_airline_name ?? $booking->retFlight->airline->name  }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->ret_flight_number ?? $booking->retFlight->ret_flight_number }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->ret_dep_time ?? $booking->retFlight->ret_dep_time }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->ret_arr_time ?? $booking->retFlight->ret_arr_time }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $d = \Carbon\Carbon::parse($booking->ret_dep_time ?? $booking->retFlight->ret_dep_time);
                                                $a = \Carbon\Carbon::parse($booking->ret_arr_time ?? $booking->retFlight->ret_arr_time);
                                            @endphp
                                            <strong>{{ $a->diff($d)->format('%h.%I') }} Hours</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->baggage_allowance ?? $booking->retFlight->baggage_allowance ?? '-' }}</strong>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="text-left">
                                        <td>
                                            <strong>{{ $booking->ret_airline_name ?? $booking->retFlight->airline->name  }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->ret_flight_number ?? $booking->retFlight->dep_flight_number }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->ret_dep_time ?? $booking->retFlight->dep_time }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->ret_arr_time ?? $booking->retFlight->dep_arr_time }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $d = \Carbon\Carbon::parse($booking->ret_dep_time ?? $booking->retFlight->dep_time);
                                                $a = \Carbon\Carbon::parse($booking->ret_arr_time ?? $booking->retFlight->dep_arr_time);
                                            @endphp
                                            <strong>{{ $a->diff($d)->format('%h.%I') }} Hours</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->baggage_allowance ?? $booking->retFlight->baggage_allowance ?? '-' }}</strong>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <br/><br/>
                            <div class="mb-5">
                                <div style="background: lightgrey; height:1px; width:100%"></div>
                            </div>
                        @endif

                        @if($booking->rooms->isNotEmpty())
                            @php
                                $hotel = $booking->rooms->first()->hotel;
                                $hotel_name = $hotel ? $hotel->name : $booking->hotel_name;
                                $hotel_location = $hotel ? $hotel->city->city : $booking->hotel_location;
                            @endphp
                            <div class="d-flex">
                                <h5 class="text-primary text-uppercase"><strong>Hotel</strong></h5>
                            </div>
                            <div class="row my-4" style="font-size: 1rem">
                                <div class="col text-uppercase">NAME<br><strong>{{ $hotel_name }}</strong></div>
                                <div class="col text-uppercase">LOCATION<br><strong>{{ $hotel_location ?? $booking->dep_to_city ?? $booking->depFlight->depTo->city }}</strong></div>
                                <div class="col text-uppercase">CHECK IN<br><strong>{{ $booking->dep_date ?? $booking->depFlight->dep_date }} at {{ $booking->hotel_check_in }}</strong></div>
                                <div class="col text-uppercase">CHECK OUT<br><strong>{{ $booking->ret_date ?? $booking->retFlight->ret_date }} at {{ $booking->hotel_check_out }}</strong></div>
                            </div>
                            <div class="">
                                <div style="background: lightgrey; height:1px; width:100%"></div>
                            </div>
                            <br/><br/>
                        @endif

                        @if($booking->tickets->isNotEmpty())
                            <h5 class="text-primary text-uppercase mb-3"><strong>Passengers</strong></h5>
                            <table class="table table-striped cart-list shopping-cart">
                                <thead class="text-success text-uppercase">
                                <tr>
                                    <th>
                                        Title
                                    </th>
                                    <th>
                                        First Name
                                    </th>
                                    <th>
                                        Last Name
                                    </th>
                                    <th>
                                        Date of Birth
                                    </th>
                                    <th>
                                        Phone
                                    </th>
                                    <th>
                                        Nationality
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $tickets = $booking->tickets->unique(function ($item) {
                                        return $item['title'].$item['first_name'].$item['last_name'].$item['date_of_birth'].$item['phone'];
                                    });
                                @endphp
                                @foreach($tickets as $ticket)
                                    <tr class="text-left">
                                        <td>
                                            <strong>{{ $ticket->title }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket->first_name }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket->last_name }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket->date_of_birth }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket->phone  }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket->nationality }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if($booking->rooms->isNotEmpty())
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary text-uppercase mb-3 mt-5"><strong>Rooms</strong></h5>
                                    <table class="table table-striped cart-list shopping-cart" style="display: table">
                                        <thead class="text-success text-uppercase">
                                        <tr>
                                            <th>Rooms</th>
                                            <th>Title</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Date of Birth</th>
                                            <th>Phone</th>
                                            <th>Nationality</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($booking->rooms->groupBy('room_number') as $roomType => $rooms)
                                            @foreach($rooms as $index => $room)
                                                <tr>
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rooms->count() }}" style="vertical-align: middle; text-align: center">
                                                            <strong>{{ $room->room->room_type->name ?? $room->room_name }} ({{ $room->meal_plan }})</strong>
                                                        </td>
                                                    @endif
                                                    <td style="vertical-align: middle; text-align: center">{{ $room->title }}</td>
                                                    <td style="vertical-align: middle; text-align: center">{{ $room->first_name }}</td>
                                                    <td style="vertical-align: middle; text-align: center">{{ $room->last_name }}</td>
                                                    <td style="vertical-align: middle; text-align: center">{{ $room->date_of_birth }}</td>
                                                    <td style="vertical-align: middle; text-align: center">{{ $room->phone }}</td>
                                                    <td style="vertical-align: middle; text-align: center">{{ $room->nationality }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        @endif

                        @if(!empty($booking->depFlight?->contract))
                            <h5 class="text-primary text-uppercase mt-5 mb-3"><strong>Rules</strong></h5>
                            {!! $booking->depFlight->contract->content !!}
                            <div class="my-5">
                                <div style="background: lightgrey; height:1px; width:100%"></div>
                            </div>
                        @endif

                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-between">
                                <div class="text-uppercase">Booked By <br/> <strong>{{ $booking->user->name }}</strong></div>
                                <div class="text-uppercase text-right">Booking Date:
                                    <br/><strong>{{ $booking->created_at }}</strong></div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
