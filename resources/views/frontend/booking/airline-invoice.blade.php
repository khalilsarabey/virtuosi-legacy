<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $site_settings->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Gochi+Hand&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <x-brand-colors />
    <style>
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div class="container">
@if ($errors->any())
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger">
            <div>For issuance please contact us offline.</div>
        </div>
    </div>
</div>
@endif
    @php
        $payload = json_decode($booking->payload);
        $request = json_decode($booking->request);
    @endphp
    <div class="cart-section">
        <div class="w-100 py-3 text-center no-print" style="background-color: #eee">
            @if($booking->status != 'confirmed')
                @if($booking->on_hold_expiry_date && \Carbon\Carbon::parse($booking->on_hold_expiry_date)->timezone('Asia/Beirut') > \Carbon\Carbon::now('Asia/Beirut'))
                    <form method="post" action="{{ route('frontend.low-cost.retrieveandpay') }}">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}" />
                        <input type="hidden" name="airline" value="{{ $booking->airline }}" />
                        <input type="hidden" name="bookref" value="{{ $payload->booking_num }}" />
                        <button type="submit" class="h6 btn btn-primary" href="#"><i class="icon_printer"></i> CONFIRM BOOKING</button>
                    </form>
                    <div class="text-danger">Reservation is on hold. To avoid cancellation, pay before {{ \Carbon\Carbon::parse($payload->on_hold_expiry_date)->setTimezone('Asia/Beirut')->format('d/m/Y H:i') }}</div>
                @elseif($booking->on_hold_expiry_date && \Carbon\Carbon::parse($booking->on_hold_expiry_date)->timezone('Asia/Beirut') < \Carbon\Carbon::now('Asia/Beirut'))
                    <div class="font-weight-bold text-center text-danger">CANCELLED</div>
                @endif
            @else
                <a class="h6 text-dark" href="#" onclick="window.print(); return;"><i class="icon_printer"></i> PRINT TICKET</a>
            @endif
        </div>
        <div class="logo-container d-flex justify-content-between align-items-center my-5">
            <div>
                @if(auth()->user()->type == 'agency' || auth()->user()->type == 'white')
                    @if(count(auth()->user()->logo))
                        @foreach(auth()->user()->logo as $key => $entry)
                            <img src="{{ $entry['url'] }}" height="50" />
                        @endforeach
                    @else
                        <h5>{{ auth()->user()->name }}</h5>
                    @endif
                @else
                    @if($site_settings->print_logo)
                        @foreach($site_settings->print_logo as $key => $entry)
                            <img src="{{ $entry['url'] }}" height="50" />
                        @endforeach
                    @else
                        <h5>{{ $site_settings->name }}</h5>
                    @endif
                @endif
            </div>
            <div class="text-right">
                <strong>RESERVATION NUMBER: </strong> {{ $payload->booking_num }}
                <div class="h5 no-print">
                    TOTAL: ${{ number_format($booking->amount_with_markup + $booking->taxes, 2) }}
                </div>
            </div>
        </div>
        @php $itineraries = []; @endphp
        <div class="d-flex justify-content-between mb-4">
            <h5 class="text-primary text-uppercase"><strong>Departure</strong></h5>
        </div>
        <table class="table table-striped cart-list shopping-cart">
            <thead class="text-success text-uppercase">
            <tr>
                <th>
                    Airline
                </th>
                <th>
                    From
                </th>
                <th>
                    To
                </th>
                <th>
                    Airline
                </th>
                <th>
                    Flight Num
                </th>
                <th>
                    Departure
                </th>
                <th>
                    Arrival
                </th>
            </tr>
            </thead>
            <tbody>
                @foreach($payload->departure->stops as $flight)
                    @php $itineraries[$flight->departure_time] = $flight->from . ' - ' .  $flight->to; $itineraries[$flight->flight_number] = $flight->from . ' - ' .  $flight->to @endphp
                <tr>
                    <td>
                        <strong>{{ $booking->airline }}</strong>
                    </td>
                    <td>
                        <strong>{{ $flight->from }}</strong>
                    </td>
                    <td>
                        <strong>{{ $flight->to }}</strong>
                    </td>
                    <td>
                        <strong>{{ $booking->airline }}</strong>
                    </td>
                    <td>
                        <strong>{{ $flight->flight_number }}</strong>
                    </td>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($flight->departure_time)->format('d/m/Y') }}</strong><br/>
                        <strong>{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</strong>
                    </td>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($flight->arrival_time)->format('d/m/Y') }}</strong>
                        <strong>{{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($booking->ret_from)
            <div class="d-flex justify-content-between mb-4 mt-4">
                <h5 class="text-primary text-uppercase"><strong>Return</strong></h5>
            </div>
            <table class="table table-striped cart-list shopping-cart">
                <thead class="text-success text-uppercase">
                <tr>
                    <th>
                        Airline
                    </th>
                    <th>
                        From
                    </th>
                    <th>
                        To
                    </th>
                    <th>
                        Airline
                    </th>
                    <th>
                        Flight Num
                    </th>
                    <th>
                        Departure
                    </th>
                    <th>
                        Arrival
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($payload->return->stops as $flight)
                    @php $itineraries[$flight->departure_time] = $flight->from . ' - ' .  $flight->to; $itineraries[$flight->flight_number] = $flight->from . ' - ' .  $flight->to @endphp
                    <tr>
                        <td>
                            <strong>{{ $booking->airline }}</strong>
                        </td>
                        <td>
                            <strong>{{ $flight->from }}</strong>
                        </td>
                        <td>
                            <strong>{{ $flight->to }}</strong>
                        </td>
                        <td>
                            <strong>{{ $booking->airline }}</strong>
                        </td>
                        <td>
                            <strong>{{ $flight->flight_number }}</strong>
                        </td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($flight->departure_time)->format('d/m/Y') }}</strong><br/>
                            <strong>{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</strong>
                        </td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($flight->arrival_time)->format('d/m/Y') }}</strong>
                            <strong>{{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}</strong>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <h5 class="text-primary text-uppercase mt-5 mb-4"><strong>Passenger Details</strong></h5>

        <table class="table table-striped cart-list shopping-cart">
            <thead class="text-success text-uppercase">
            <tr>
                <th style="text-align: left">
                    Name
                </th>
                <th style="text-align: left">
                    Passport No.
                </th>
                <th style="text-align: left">
                    Passport Expiry
                </th>
                <th style="text-align: left">
                    Date of Birth
                </th>
                <th style="text-align: left">
                    Nationality
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($request->travellers as $traveller)
                <tr>
                    <td>
                        <div><strong>{{ $traveller->title ?? '' }} {{ $traveller->first_name }} {{ $traveller->last_name }} ({{ $traveller->type }})</strong></div>
                        <div class="font-weight-bold"><small>{{ $traveller->email ?? '' }}</small></div>
                        <div class="font-weight-bold"><small>{{ $traveller->phone ?? '' }}</small></div>
                    </td>
                    <td>
                        <strong>{{ $traveller->passport_number ?? $traveller->passport ?? '' }}</strong>
                    </td>
                    <td>
                        <strong>{{ $traveller->passport_expiry ? \Carbon\Carbon::parse($traveller->passport_expiry)->format('d/m/Y') : '' }}</strong>
                    </td>
                    <td>
                        <div><strong>{{ \Carbon\Carbon::parse($traveller->dob)->format('d/m/Y') }}</strong></div>
                    </td>
                    <td>
                        <strong>{{ $traveller->nationality }}</strong>
                    </td>
                </tr>

                    <tr>
                        <td>
                            @if(isset($traveller->ancillaries->baggage) && count((array)$traveller->ancillaries->baggage) > 0)
                                <div class="mb-1 font-weight-bold text-primary text-uppercase font-size-13">Baggage Info</div>
                                <hr/>
                                @forelse(array_filter((array)$traveller->ancillaries->baggage) as $b)
                                    <div class="font-weight-bold font-size-13">{{ $b->origin }} - {{ $b->destination }}</div>
                                    <small>{{ $b->description }}</small>
                                @empty
                                    <div class="text-muted font-size-13">None</div>
                                @endforelse
                            @endif
                        </td>
                        <td colspan="2">
                            @if(isset($traveller->ancillaries->meals) && count((array)$traveller->ancillaries->meals) > 0)
                                <div class="mb-1 font-weight-bold text-primary text-uppercase font-size-13">Meal Info</div>
                                <hr/>
                                @forelse(array_filter((array)$traveller->ancillaries->meals) as $meal)
                                    <div class="font-weight-bold font-size-13">{{ $meal->origin }} - {{ $meal->destination }}</div>
                                    @if(isset($meal->image_url))
                                    <img width="36" src="{{ $meal->image_url ?? '' }}" />
                                    @endif
                                    <small>{{ $meal->name ?? $meal->description }}</small>
                                @empty
                                    <div class="text-muted font-size-13">None</div>
                                @endforelse
                            @endif
                        </td>
                        <td colspan="3">
                            @if(isset($traveller->ancillaries->seats) && count((array)$traveller->ancillaries->seats) > 0)
                                <div class="mb-1 font-weight-bold text-primary text-uppercase font-size-13">Seat Info</div>
                                <hr/>
                                @forelse(array_filter((array)$traveller->ancillaries->seats) as $seat)
                                    <div class="font-weight-bold font-size-13">{{ $seat->origin }} - {{ $seat->destination }}</div>
                                    <small>Row {{ $seat->row_number }}, Seat {{ $seat->seat_number }}</small>
                                @empty
                                    <div class="text-muted font-size-13">None</div>
                                @endforelse
                            @endif
                        </td>
                    </tr>

            @endforeach
            </tbody>
        </table>
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-between">
                <div class="text-uppercase">Booked By <br/>
                    <strong>
                        {{ $booking->user->name }}
                    </strong>
                </div>
                <div class="text-uppercase text-right">Booking Date: <br/><strong>{{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y') }}</strong></div>
            </div>
            @if(!empty($booking->contract))
                <div class="col-12">
                    <div class="mb-5 mt-5">
                        <div style="border-bottom: 1px solid lightgrey; height:0px; width:100%;"></div>
                    </div>
                    <h5 class="text-primary text-uppercase"><strong>Rules</strong></h5>
                    {!! $booking->contract->content !!}
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
