@if ($error)
<div class="container">
    <div class="row">
        <div class="col-12 mt-5">
            <br/><br/>
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
    <div class="bg-primary" style="padding-top: 50px;font-size: 22px;padding-bottom: 50px;text-align:center;font-weight: bold;color: white;margin-bottom: 30px;">Booking Summary</div>
    <div class="container margin_60">
        <div class="p-4 bg-white">
            <div class="cart-section">
                <div class="d-flex justify-content-between">
                    <h5 class="text-primary text-uppercase"><strong>Departure</strong></h5>
                </div>
                <div class="row my-4" style="font-size: 1rem">
                    <div class="col text-uppercase">Airline<br/><img width="70" src="{{ asset('airlines/AirArabia.png') }}" /></div>
                    <div class="col text-uppercase">FROM<br /><strong>{{ $results["departure"]["from"] }}</strong></div>
                    <div class="col text-uppercase">TO<br /><strong>{{ $results["departure"]["to"] }}</strong></div>
                    <div class="col text-uppercase">DEPARTURE DATE<br /><strong>{{ \Carbon\Carbon::parse($results["departure"]["departure_time"])->format('d/m/Y \a\t H:i') }}</strong></div>
                    <div class="col text-uppercase">ARRIVAL DATE<br /><strong>{{ \Carbon\Carbon::parse($results["departure"]["arrival_time"])->format('d/m/Y \a\t H:i') }}</strong></div>
                </div>
                <div class="">
                    <div style="background: lightgrey; height:1px; width:100%"></div>
                </div>
                @php $itineraries = []; @endphp
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
                                @php $itineraries[$stp["departure_time"]] = $stp["from"] . ' → ' .  $stp["to"]; $itineraries[$stp["flight_number"]] = $stp["from"] . ' &#8594; ' .  $stp["to"] @endphp
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
                    <div class="col text-uppercase">Airline<br/><img width="70" src="{{ asset('airlines/AirArabia.png') }}" /></div>
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
                                @php $itineraries[$stp["departure_time"]] = $stp["from"] . ' → ' .  $stp["to"]; $itineraries[$stp["flight_number"]] = $stp["from"] . ' &#8594; ' .  $stp["to"] @endphp
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
                        @foreach ($results["travellers"] as $pass)
                        <tr>
                            <td>
                                <div><strong>{{ $pass["first_name"] }} {{ $pass['last_name'] }} ({{ $pass["type"] }})</strong></div>
                                <div class="font-weight-bold"><small>{{ $pass["email"] ?? '' }}</small></div>
                                <div class="font-weight-bold"><small>{{ $pass["phone"] ?? '' }}</small></div>
                            </td>
                            <td>
                                <div><strong>{{ $pass["passport_number"] ?? '' }}</strong></div>
                            </td>
                            <td>
                                <div><strong>{{ $pass["passport_expiry"] ? \Carbon\Carbon::parse($pass["passport_expiry"])->format('d/m/Y') : '' }}</strong></div>
                            </td>
                            <td>
                                <div><strong>{{ \Carbon\Carbon::parse($pass["dob"])->format('d/m/Y') }}</strong></div>
                            </td>
                            <td>
                                <div><strong>{{ $pass["nationality"] ?? '' }}</strong></div>
                            </td>
                        </tr>
                        @if(strtolower($pass['type']) != 'infant')
                            <tr>
                            <td>
                                @if(isset($pass['ancillaries']['baggage']) && count($pass['ancillaries']['baggage']) > 0)
                                    <div class="mb-2 font-weight-bold text-primary text-uppercase font-size-13">Baggage Info</div>
                                    <hr/>
                                    @forelse(array_filter($pass['ancillaries']['baggage']) as $baggage)
                                        @if(!is_null($baggage))
                                            <div class="font-weight-bold font-size-13">{{ $baggage['departure_date'] ?? '' }}</div>
                                            <small>{{ $baggage['description'] }}</small>
                                        @endif
                                    @empty
                                        <div class="text-muted font-size-13">None</div>
                                    @endforelse
                                @endif
                            </td>
                            <td colspan="2">
                                @if(isset($pass['ancillaries']['meals']) && count($pass['ancillaries']['meals']) > 0)
                                    <div class="mb-2 font-weight-bold text-primary text-uppercase font-size-13">Meal Info</div>
                                    <hr/>
                                    @forelse(array_filter($pass['ancillaries']['meals']) as $meal)
                                        @if(!is_null($meal))
                                            <div class="font-weight-bold font-size-13">{{ $meal['departure_date'] ?? '' }}</div>
                                            <img width="36" src="{{ $meal['image_url'] ?? '' }}" /> <small>{{ $meal['name'] }}</small>
                                        @endif
                                    @empty
                                        <div class="text-muted font-size-13">None</div>
                                    @endforelse
                                @endif
                            </td>
                             <td></td>
                            <td>
                                @if(isset($pass['ancillaries']['seats']) && count($pass['ancillaries']['seats']) > 0)
                                    <div class="mb-2 font-weight-bold text-primary text-uppercase font-size-13">Seat Info</div>
                                    <hr/>
                                    @forelse(array_filter($pass['ancillaries']['seats']) as $seat)
                                        @if(!is_null($seat))
                                            <div class="font-weight-bold font-size-13">{{ $itineraries[$seat['flight_number']] ?? '' }}</div>
                                            <small>Row {{ $seat['row_number'] }}, Seat {{ $seat['seat_number'] }}</small>
                                        @endif
                                    @empty
                                        <div class="text-muted font-size-13">None</div>
                                    @endforelse
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
            <form class="row mt-5" id="form" method="post" action="{{ route('frontend.low-cost.confirm') }}">
                @csrf
                <input type="hidden" name="airline" value="{{ $airline }}">
                <input type="hidden" name="payload" value="{{json_encode($results)}}">
                <input type="hidden" name="passengers" value="{{json_encode($passengers)}}">
                <input type="hidden" name="ancillariespayload" value="{{ $ancillaries_payload }}">
                <input type="hidden" name="accesstoken" value="{{ $access_token }}" />
                <input type="hidden" name="sessiontimestamp" value="{{ $session_timestamp }}" />
                <input type="hidden" name="jscookie" value="{{ $js_cookie }}" />
                <div class="col-12">
                    <h5 class="text-primary text-uppercase mb-3"><strong>Remarks</strong></h5>
                    <div class="mb-3">
                        <textarea class="form-control" name="remarks" rows="5"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="hold" id="hold" value="1" checked>
                        <label class="form-check-label" for="hold">Process as on hold booking</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <button id="confirm" type="submit" name="confirm" class="btn btn-primary text-uppercase text-white w-100 border-0 h6 bg-primary p-3">Proceed <i class="icon-left"></i></button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).on('submit', '#form', function() {
            let submitButton = document.getElementById('confirm');
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.textContent = 'Please wait...';
        })
    </script>
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
    .btn:focus {
        box-shadow: none !important;
        color: white !important;
    }
</style>

@endpush
