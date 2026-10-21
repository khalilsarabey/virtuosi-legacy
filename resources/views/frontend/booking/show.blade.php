@extends('layouts.blade')
@section('content')
    <div style="height: 50px;"></div>
    <div class="container mb-5">
        <div class="p-4 bg-light">
            <div class="row">
                <div class="col-md-8">
                    <div style="font-size: 1.3rem; font-weight:bold; text-transform: uppercase" class="{{ $booking->status == 'Confirmed' ? 'text-success' : 'text-danger' }}">{{ $booking->status }}</div>
                </div>
                <div class="col-md-4 text-right">
                    <h3 id="price_single_main" class="text-dark"><span class="text-dark"><sup>$</sup>{{ number_format($booking->total) }}</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container margin_60">
        <div class="p-4 bg-white">

            @if($booking->flight_id || $booking->dep_from_code)
                <div class="cart-section">
                <div class="d-flex justify-content-between">
                    <h5 class="text-primary text-uppercase"><strong>Departure</strong></h5>
                    <div class="d-flex">
                        <div class="mx-4">
                            <a class="h6" href="{{ route('frontend.booking.print', $booking->id) }}" target="_blank">
                                <svg fill="#000000" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                                    <g id="Printer">
                                        <path d="M57.7881012,14.03125H52.5v-8.0625c0-2.2091999-1.7909012-4-4-4h-33c-2.2091999,0-4,1.7908001-4,4v8.0625H6.2119002
                                            C2.7871001,14.03125,0,16.8183498,0,20.2431507V46.513649c0,3.4248009,2.7871001,6.2119026,6.2119002,6.2119026h2.3798995
                                            c0.5527,0,1-0.4472008,1-1c0-0.5527-0.4473-1-1-1H6.2119002C3.8896,50.7255516,2,48.8359489,2,46.513649V20.2431507
                                            c0-2.3223,1.8896-4.2119007,4.2119002-4.2119007h51.5762024C60.1102982,16.03125,62,17.9208508,62,20.2431507V46.513649
                                            c0,2.3223-1.8897018,4.2119026-4.2118988,4.2119026H56c-0.5527992,0-1,0.4473-1,1c0,0.5527992,0.4472008,1,1,1h1.7881012
                                            C61.2128983,52.7255516,64,49.9384499,64,46.513649V20.2431507C64,16.8183498,61.2128983,14.03125,57.7881012,14.03125z
                                             M13.5,5.96875c0-1.1027999,0.8971996-2,2-2h33c1.1027985,0,2,0.8972001,2,2v8h-37V5.96875z"/>
                                        <path d="M44,45.0322495H20c-0.5517998,0-0.9990005,0.4472008-0.9990005,0.9990005S19.4482002,47.0302505,20,47.0302505h24
                                            c0.5517006,0,0.9990005-0.4472008,0.9990005-0.9990005S44.5517006,45.0322495,44,45.0322495z"/>
                                        <path d="M44,52.0322495H20c-0.5517998,0-0.9990005,0.4472008-0.9990005,0.9990005S19.4482002,54.0302505,20,54.0302505h24
                                            c0.5517006,0,0.9990005-0.4472008,0.9990005-0.9990005S44.5517006,52.0322495,44,52.0322495z"/>
                                        <circle cx="7.9590998" cy="21.8405495" r="2"/>
                                        <circle cx="14.2856998" cy="21.8405495" r="2"/>
                                        <circle cx="20.6121998" cy="21.8405495" r="2"/>
                                        <path d="M11,62.03125h42v-26H11V62.03125z M13.4036999,38.4349518h37.1925964v21.1925964H13.4036999V38.4349518z"/>
                                    </g>
                                    </svg>
                            </a>
                        </div>
                    </div>
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
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                        <td>
                            <strong>{{ $booking->dep_airline_name ?? $booking->depFlight->airline->name ?? '' }}</strong>
                        </td>
                        <td>
                            <strong>{{ $booking->dep_flight_number ?? $booking->depFlight->dep_flight_number ?? '' }}</strong>
                        </td>
                        <td>
                            <strong>{{ $booking->dep_time ?? $booking->depFlight->dep_time ?? '' }}</strong>
                        </td>
                        <td>
                            <strong>{{ $booking->dep_arr_time ?? $booking->depFlight->dep_arr_time ?? '' }}</strong>
                        </td>
                        <td>
                            @php
                                $d = \Carbon\Carbon::parse($booking->dep_time ?? $booking->depFlight?->dep_time);
                                $a = \Carbon\Carbon::parse($booking->dep_arr_time ?? $booking->depFlight?->dep_arr_time);
                            @endphp
                            <strong>{{ $a->diff($d)->format('%h:%I') }}</strong>
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
                        <div class="col text-uppercase">FROM<br/><strong>{{ $booking->ret_from_city ?? $booking->retFlight->depTo->city ?? '' }}</strong></div>
                        <div class="col text-uppercase">TO<br/><strong>{{ $booking->ret_to_city ?? $booking->retFlight->depFrom->city ?? '' }}</strong></div>
                        <div class="col text-uppercase">DEP DATE<br/><strong>{{ $booking->ret_date ?? $booking->retFlight?->ret_date }}</strong></div>
                        <div class="col text-uppercase">CABIN<br/><strong>{{ $booking->ret_cabin }}</strong></div>
                        <div class="col text-uppercase">ADULT<br/><strong>{{ $booking->adults }}</strong></div>
                        <div class="col text-uppercase">CHILD<br/><strong>{{ $booking->children }}</strong></div>
                        <div class="col text-uppercase">INFANT<br/><strong>{{ $booking->infants }}</strong></div>
                    </div>
                @else
                    <div class="row my-4" style="font-size: 1rem">
                        <div class="col text-uppercase">FROM<br/><strong>{{ $booking->ret_from_city ?? $booking->retFlight->depFrom->city ?? '' }}</strong></div>
                        <div class="col text-uppercase">TO<br/><strong>{{ $booking->ret_to_city ?? $booking->retFlight->depTo->city ?? '' }}</strong></div>
                        <div class="col text-uppercase">DEP DATE<br/><strong>{{ $booking->ret_date ?? $booking->retFlight?->dep_date }}</strong></div>
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
                    </tr>
                    </thead>
                    <tbody>
                    @if($booking->type == 'fixed')
                        <tr class="text-center">
                            <td>
                                <strong>{{ $booking->ret_airline_name ?? $booking->retFlight->airline->name ?? ''  }}</strong>
                            </td>
                            <td>
                                <strong>{{ $booking->ret_flight_number ?? $booking->retFlight->ret_flight_number ?? '' }}</strong>
                            </td>
                            <td>
                                <strong>{{ $booking->ret_dep_time ?? $booking->retFlight->ret_dep_time ?? '' }}</strong>
                            </td>
                            <td>
                                <strong>{{ $booking->ret_arr_time ?? $booking->retFlight->ret_arr_time ?? '' }}</strong>
                            </td>
                            <td>
                                @php
                                    $d = \Carbon\Carbon::parse($booking->ret_dep_time ?? $booking->retFlight?->ret_dep_time);
                                    $a = \Carbon\Carbon::parse($booking->ret_arr_time ?? $booking->retFlight?->ret_arr_time);
                                @endphp
                                <strong>{{ $a->diff($d)->format('%h.%I') }} Hours</strong>
                            </td>
                        </tr>
                    @else
                        <tr class="text-center">
                            <td>
                                <strong>{{ $booking->ret_airline_name ?? $booking->retFlight->airline->name ?? ''  }}</strong>
                            </td>
                            <td>
                                <strong>{{ $booking->ret_flight_number ?? $booking->retFlight->dep_flight_number ?? '' }}</strong>
                            </td>
                            <td>
                                <strong>{{ $booking->ret_dep_time ?? $booking->retFlight->dep_time ?? '' }}</strong>
                            </td>
                            <td>
                                <strong>{{ $booking->ret_arr_time ?? $booking->retFlight->dep_arr_time ?? '' }}</strong>
                            </td>
                            <td>
                                @php
                                    $d = \Carbon\Carbon::parse($booking->ret_dep_time ?? $booking->retFlight?->dep_time);
                                    $a = \Carbon\Carbon::parse($booking->ret_arr_time ?? $booking->retFlight?->dep_arr_time);
                                @endphp
                                <strong>{{ $a->diff($d)->format('%h.%I') }} Hours</strong>
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
                @endphp
                <div class="d-flex">
                    <h5 class="text-primary text-uppercase"><strong>Hotel</strong></h5>
                </div>
                <div class="row my-4" style="font-size: 1rem">
                    <div class="col text-uppercase">NAME<br><strong>{{ $hotel_name }}</strong></div>
                    <div class="col text-uppercase">LOCATION<br><strong>{{ $booking->dep_to_city ?? $booking->depFlight->depTo->city ?? '' }}</strong></div>
                    <div class="col text-uppercase">CHECK IN<br><strong>{{ $booking->rooms->first()->check_in }} at {{ $booking->hotel_check_in }}</strong></div>
                    <div class="col text-uppercase">CHECK OUT<br><strong>{{ $booking->rooms->first()->check_out }} at {{ $booking->hotel_check_out }}</strong></div>
                </div>
                <div class="">
                    <div style="background: lightgrey; height:1px; width:100%"></div>
                </div>
                <br/><br/>
            @endif
            @if(!empty($booking->depFlight?->contract))
                <h5 class="text-primary text-uppercase"><strong>Rules</strong></h5>
                {!! $booking->depFlight->contract->content !!}
                <div class="my-5">
                    <div style="background: lightgrey; height:1px; width:100%"></div>
                </div>
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
                        <tr class="text-center">
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
@endsection
@push('styles')
    <style>
        #overlay {
            position: fixed; /* Sit on top of the page content */
            display: none; /* Hidden by default */
            width: 100%; /* Full width (cover the whole page) */
            height: 100%; /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8); /* Black background with opacity */
            z-index: 9999925; /* Specify a stack order in case you're using a different order for other elements */
            cursor: pointer; /* Add a pointer on hover */
        }

        .cart-section .totals-table {
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .cart-section ul.totals-table {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .cart-section .totals-table li {
            line-height: 24px;
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        .cart-section .totals-table li:last-child {
            border-bottom: none;
            margin-bottom: 0px;
        }

        .cart-section .totals-table .col {
            position: relative;
            display: block;
            float: left;
            line-height: 24px;
            width: 50%;
            text-transform: uppercase;
        }

        .cart-section .totals-table li .col:last-child {
            text-align: right;
        }

        .cart-section .totals-table .total {
            color: #f7542b;
            font-weight: 600;
        }
    </style>
@endpush
