@if($error)
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                <div>We're unable to process your booking. Please try again later.</div>
            </div>
        </div>
    </div>
</div>
@else
<div>
    <div style="height: 200px" class="alert alert-{{ isset($results['on_hold_expiry_date']) && !empty($results['on_hold_expiry_date']) ? 'warning' : 'info' }} d-flex align-items-center justify-content-center flex-column">
        @if(isset($results['on_hold_expiry_date']) && !empty($results['on_hold_expiry_date']))
            <div class="text-danger mb-3 text-center">Booking is on hold. <br/>To avoid cancellation, pay before <strong>{{ \Carbon\Carbon::parse($results['on_hold_expiry_date'])->setTimezone('Asia/Beirut')->format('d/m/Y \a\t H:i') }}</strong></div>
        @else
            <h5 class="text-primary mb-3 text-center">Booking Confirmed</h5>
        @endif
        <div class="mb-3">Booking Reference Number: {{ $results["booking_num"] ?? 'Unknown'}}</div>
        @if(auth()->user()->type == 'agency')
            <a class="btn btn-primary" href="{{ route('frontend.my-bookings') }}">Go to My Bookings</a>
        @else
            <a class="btn btn-primary" href="{{ route('admin.airline-bookings.index') }}">View Bookings</a>
        @endif
    </div>
    <div class="container margin_60">
        <div class="p-4 bg-white">
            <div class="cart-section">
                <div class="d-flex justify-content-between">
                    <h5 class="text-primary text-uppercase"><strong>Departure</strong></h5>
                </div>
                <div class="row my-4" style="font-size: 1rem">
                    <div class="col text-uppercase">FROM<br /><strong>{{ $results["departure"]["from"] }}</strong></div>
                    <div class="col text-uppercase">TO<br /><strong>{{ $results["departure"]["to"] }}</strong></div>
                    <div class="col text-uppercase">DEPARTURE DATE<br /><strong>{{ \Carbon\Carbon::parse($results["departure"]["departure_time"])->format('d/m/Y \a\t H:i') }}</strong></div>
                    <div class="col text-uppercase">ARRIVAL DATE<br /><strong>{{ \Carbon\Carbon::parse($results["departure"]["arrival_time"])->format('d/m/Y \a\t H:i') }}</strong></div>
                </div>
                <div class="">
                    <div style="background: lightgrey; height:1px; width:100%"></div>
                </div>
                <table class="table table-striped">
                    <thead class="text-success text-uppercase">
                        <tr>
                            <th>
                                Routing
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
                        @if(!isset($results["departure"]["stops"]) || count($results["departure"]["stops"]) == 1)
                        <tr class="text-center">
                            <td class="text-uppercase">
                                <strong>{{ $results["departure"]["from"] }} - {{ $results["departure"]["to"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ $results["departure"]["flight_number"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($results["departure"]["departure_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($results["departure"]["arrival_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                @php
                                $d = \Carbon\Carbon::parse($results["departure"]["departure_time"]);
                                $a = \Carbon\Carbon::parse($results["departure"]["arrival_time"]);
                                @endphp
                                <strong>{{ $a->diff($d)->format('%h:%I') }}</strong>
                            </td>
                        </tr>
                        @endif
                        @if(isset($results["departure"]["stops"]) && count($results["departure"]["stops"]) > 1)
                        @foreach ($results["departure"]["stops"] as $stp)
                        <tr class="text-center">
                            <td class="text-uppercase">
                                <strong>{{ $stp["from"] }} - {{ $stp["to"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ $stp["flight_number"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($stp["departure_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($stp["arrival_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                @php
                                $d = \Carbon\Carbon::parse($stp["departure_time"]);
                                $a = \Carbon\Carbon::parse($stp["arrival_time"]);
                                @endphp
                                <strong>{{ $a->diff($d)->format('%h:%I') }}</strong>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <br /><br />
                <div class="mb-5">
                    <div style="background: lightgrey; height:1px; width:100%"></div>
                </div>
                @if ($results["return"])
                <div class="d-flex">
                    <h5 class="text-primary text-uppercase"><strong>Return</strong></h5>
                </div>
                <div class="row my-4" style="font-size: 1rem">
                    <div class="col text-uppercase">FROM<br /><strong>{{ $results["return"]["from"] }}</strong></div>
                    <div class="col text-uppercase">TO<br /><strong>{{ $results["return"]["to"] }}</strong></div>
                    <div class="col text-uppercase">DEPARTURE DATE<br /><strong>{{ \Carbon\Carbon::parse($results["return"]["departure_time"])->format('d/m/Y \a\t H:i') }}</strong></div>
                    <div class="col text-uppercase">ARRIVAL DATE<br /><strong>{{ \Carbon\Carbon::parse($results["return"]["arrival_time"])->format('d/m/Y \a\t H:i') }}</strong></div>
                </div>
                <div class="">
                    <div style="background: lightgrey; height:1px; width:100%"></div>
                </div>
                <table class="table table-striped cart-list shopping-cart">
                    <thead class="text-success text-uppercase">
                        <tr>
                            <th>
                                Routing
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
                        @if(!isset($results["return"]["stops"]) || count($results["return"]["stops"]) == 1)
                        <tr class="text-center">
                            <td class="text-uppercase">
                                <strong>{{ $results["return"]["from"] }} - {{ $results["return"]["to"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ $results["return"]["flight_number"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($results["return"]["departure_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($results["return"]["arrival_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                @php
                                $d = \Carbon\Carbon::parse($results["return"]["departure_time"]);
                                $a = \Carbon\Carbon::parse($results["return"]["arrival_time"]);
                                @endphp
                                <strong>{{ $a->diff($d)->format('%h:%I') }}</strong>
                            </td>
                        </tr>
                        @endif
                        @if(isset($results["return"]["stops"]) && count($results["return"]["stops"]) > 1)
                        @foreach ($results["return"]["stops"] as $stp)
                        <tr class="text-center">
                            <td class="text-uppercase">
                                <strong>{{ $stp["from"] }} - {{ $stp["to"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ $stp["flight_number"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($stp["departure_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($stp["arrival_time"])->format('H:i') }}</strong>
                            </td>
                            <td>
                                @php
                                $d = \Carbon\Carbon::parse($stp["departure_time"]);
                                $a = \Carbon\Carbon::parse($stp["arrival_time"]);
                                @endphp
                                <strong>{{ $a->diff($d)->format('%h:%I') }}</strong>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <br /><br />
                <div class="mb-5">
                    <div style="background: lightgrey; height:1px; width:100%"></div>
                </div>
                @endif
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
                                Nationality
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results["travellers"] as $pass)
                        <tr class="text-center">
                            <td>
                                <strong>{{ $pass["type"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ $pass["first_name"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ $pass["last_name"] }}</strong>
                            </td>
                            <td>
                                <strong>{{ $pass["nationality"] ?? '' }}</strong>
                            </td>
                        </tr>
                            @if(isset($pass['ancillaries']) && count($pass['ancillaries']) > 0)
                                <tr>
                                    <td>
                                        @if(isset($pass['ancillaries']['baggage']) && count($pass['ancillaries']['baggage']) > 0)
                                        <h6 class="mb-2">Baggage Info</h6>
                                        @foreach($pass['ancillaries']['baggage'] as $bag)
                                        {{ $bag["origin"] }}-{{ $bag["destination"] }} : {{ $bag["description"] }} <br/>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($pass['ancillaries']['meals']) && count($pass['ancillaries']['meals']) > 0)
                                        <h6 class="mb-2">Meal Info</h6>
                                        @foreach($pass['ancillaries']['meals'] as $meal)
                                        {{ $meal["origin"] }}-{{ $meal["destination"] }} : {{ $meal["description"] }} <br/>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($pass['ancillaries']['seats']) && count($pass['ancillaries']['seats']) > 0)
                                        <h6 class="mb-2">Seat Info</h6>
                                        @foreach($pass['ancillaries']['seats'] as $seat)
                                        {{ $seat["origin"] }}-{{ $seat["destination"] }} : Row {{ $seat['row_number'] }}, Seat {{ $seat['seat_number'] }} <br/>
                                        @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <div class="text-muted text-uppercase"><strong>Amount: </strong></div>
                    <div class="text-success"><strong>${{ number_format($amount_no_tax, 2) }}</strong></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="text-muted text-uppercase"><strong>Taxes & Fees: </strong></div>
                    <div class="text-success"><strong>${{ number_format($taxes_amount, 2) }}</strong></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="text-muted text-uppercase"><strong>Total: </strong></div>
                    <div class="text-success"><strong>${{ number_format($total_amount, 2) }}</strong></div>
                </div>
            </div>
        </div>
        <div>
            <form method="post" action="{{ route('frontend.low-cost.confirm') }}">
                @csrf
                <input type="hidden" name="airline" value="{{ $airline }}">
                <input type="hidden" name="payload" value="{{json_encode($results)}}">
                <input type="hidden" name="passengers" value="{{json_encode($results["travellers"])}}">
            </form>
        </div>
    </div>
</div>
@endif
@push('styles')
<style>
    #overlay {
        position: fixed;
        /* Sit on top of the page content */
        display: none;
        /* Hidden by default */
        width: 100%;
        /* Full width (cover the whole page) */
        height: 100%;
        /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.8);
        /* Black background with opacity */
        z-index: 9999925;
        /* Specify a stack order in case you're using a different order for other elements */
        cursor: pointer;
        /* Add a pointer on hover */
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
