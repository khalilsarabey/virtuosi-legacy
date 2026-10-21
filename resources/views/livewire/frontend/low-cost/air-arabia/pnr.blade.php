@if ($error)
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                <div>An error has occurred:</div>
                @foreach ($error as $err)
                <div>{{$err["code"]}} - {{$err["text"]}}</div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div>
    <div class="bg-primary" style="padding-top: 50px;font-size: 22px;padding-bottom: 50px;text-align:center;font-weight: bold;color: white;">Booking Reference Number: {{$results["booking_num"]}}</div>
    @if($results["on_hold_expiry_date"])
    <div class="bg-danger text-white py-3 text-lg text-center">
        <div>On Hold Booking Valid Till</div>
        <div>{{ \Carbon\Carbon::parse($results["on_hold_expiry_date"])->setTimezone('Asia/Beirut')->format('d/m/Y') }}</div>
    </div>
    @endif

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
                        @if(isset($results["departure"]["stops"]) && count($results["departure"]["stops"]) > 0)
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
                        @if(isset($results["return"]["stops"]) && count($results["return"]["stops"]) > 0)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <div class="text-muted text-uppercase"><strong>Amount: </strong></div>
                    <div class="text-success"><strong>${{ $amount_no_tax }}</strong></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="text-muted text-uppercase"><strong>Taxes & Fees: </strong></div>
                    <div class="text-success"><strong>${{ $taxes_amount }}</strong></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="text-muted text-uppercase"><strong>Total: </strong></div>
                    <div class="text-success"><strong>${{ $total_amount }}</strong></div>
                </div>
            </div>
        </div>
        <div>
            <form method="post" action="{{ route('frontend.low-cost.confirm') }}">
                @csrf
                <input type="hidden" name="airline" value="{{ $airline }}">
                <input type="hidden" name="booking_id" value="{{ $booking_id }}">
                <input type="hidden" name="charge" value="{{ $charge }}">
                <input type="hidden" name="justpayment" value="{{ $just_payment }}">
                <input type="hidden" name="bookingref" value="{{ $booking_ref }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="taxes" value="{{ $taxes }}">
                <input type="hidden" name="transactionidentifier" value="{{ $transaction_identifier }}">
                <button id="confirm" type="submit" class="text-uppercase text-white w-100 border-0 h6 bg-success p-3">Process Payment <i class="icon-left"></i></button>
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
