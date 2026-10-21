<div class="bg-white">
    <div class="container">
        <div class="row">
            <div class="col-4 mt-5">
                <div>
                    <h3>Passengers</h3>
                    @if(isset($passengers["adult"]))
                    @foreach ($passengers["adult"] as $pass)
                    <div class="my-2">
                        <button wire:click="passengerSelected('{{$pass['ref_rph']}}')" class="passenger-btn {{$selected_passenger == $pass['ref_rph'] ? 'selected' : ''}}">{{ $pass["fname"] }} {{ $pass["lname"]}} {{$this->getPassSeats($pass['ref_rph'])}}</button>
                    </div>
                    @endforeach
                    @endif
                    @if(isset($passengers["child"]))
                    @foreach ($passengers["child"] as $pass)
                    <div class="my-2">
                        <button wire:click="passengerSelected('{{$pass['ref_rph']}}')" class="passenger-btn {{$selected_passenger == $pass['ref_rph'] ? 'selected' : ''}}">{{ $pass["fname"] }} {{ $pass["lname"]}} {{$this->getPassSeats($pass['ref_rph'])}}</button>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="mt-4">
                    <div class="mb-3"><div class="d-inline-block" style="width: 15px; height: 15px; background: #f8d7da"></div> Unavailable</div>
                    <div class="mb-3"><div class="d-inline-block bg-paid" style="width: 15px; height: 15px;"></div> Paid Seat</div>
                    <div class="mb-3"><div class="d-inline-block bg-free" style="width: 15px; height: 15px;"></div> Free Seat</div>
                    <div class="mb-3"><div class="d-inline-block bg-info" style="width: 15px; height: 15px;"></div> Selected Seat</div>
                </div>
            </div>
            <div class="col-4 mt-5">
                <div class="airplane-map">
                    <div class="plane-head"></div>
                    <div class="plane-body">
                        <section id="seats-numbers" class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col text-center">
                                        <div class="seat-number left-seat-number h6">A</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="seat-number left-seat-number h6">B</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="seat-number left-seat-number h6">C</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col text-center">
                                        <div class="seat-number right-seat-number h6">D</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="seat-number right-seat-number h6">E</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="seat-number right-seat-number h6">F</div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        @foreach ($display_seats as $srow)
                        <section class="row">
                            @foreach ($srow["seats"] as $key => $sseat)
                                @if($key == 3)
                                    <div class="col">{{$srow["row_number"]}}</div>
                                @endif
                                @if(strtolower($sseat['availability']) == 'vac')
                                        <div class="col" data-bs-toggle="tooltip" data-bs-title="USD {{ $sseat['charge'] }}">
                                            <a href="#" wire:click="seatSelected({{json_encode($sseat)}})" class="seat text-dark {{ $sseat['charge'] == 0 ? 'bg-free' : 'bg-paid' }} {{ $this->isSeatSelectedOtherCurrent($srow['row_number'], $sseat['seat_number']) }}">{{ $sseat["seat_number"] }}</a>
                                        </div>
                                @else
                                        <div class="col">
                                            <span class="text-dark {{ $this->isSeatSelectedOtherCurrent($srow['row_number'], $sseat['seat_number']) }}" style="height: 30px; width: 100%; display: inline-block; background: #f8d7da; opacity: .3; text-align: center; margin-bottom: 10px;">{{ $sseat["seat_number"] }}</span>
                                        </div>
                                @endif
                            @endforeach
                        </section>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-4 mt-5">
                <div>
                    <h3>Segments</h3>
                    @foreach ($ancillaries_payload["seats"]["segments"] as $seg)
                    <div class="my-2">
                        <button wire:click="segmentSelected('{{$seg['flight_number']}}')" class="segment-btn {{$selected_segment == $seg['flight_number'] ? 'selected' : ''}}">{{ $seg["origin"] }} -> {{ $seg["destination"]}}</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <form class="row" method="post" id="form" action="{{ route('frontend.low-cost.summary') }}">
            @csrf
            <input type="hidden" name="airline" value="{{ $airline }}" />
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="passengers" value="{{ json_encode($passengers) }}" />
            <input type="hidden" name="payload" value="{{ json_encode($payload) }}" />
            <input type="hidden" name="payload2" value="{{ json_encode($payload2) }}" />
            <input type="hidden" name="departure" value="{{ json_encode($selected_departure) }}" />
            <input type="hidden" name="return" value="{{ json_encode($selected_return) }}" />
            <input type="hidden" name="ancillariespayload" value="{{ json_encode($ancillaries_payload) }}" />
            <input type="hidden" name="accesstoken" value="{{ $access_token }}"/>
            <input type="hidden" name="sessiontimestamp" value="{{ $session_timestamp }}"/>
            <input type="hidden" name="jscookie" value="{{ $js_cookie }}"/>
            <input type="hidden" name="transactionidentifier" value="{{ $transaction_identifier }}"/>
            <div class="col-12 text-center">
                <button id="confirm" type="submit" {{ $canProceed ? '' : 'disabled' }} class="btn btn-primary text-uppercase text-white w-100 border-0 my-5 h6 bg-primary p-3">Confirm & Proceed<i class="icon-left"></i></button>
            </div>
        </form>
    </div>
    <style>
        .bg-free {
            background: #d1e7dd !important;
        }
        .bg-paid {
            background: #cfe2ff !important;
        }
        .bg-booked {
            background: #f8d7da;
        }
        .passenger-btn {
            width: 100%;
            border: none;
            padding: 5px;
            background-color: #E9EBE6;
        }

        .passenger-btn.selected {
            background-color: #b4cf98;
        }

        .segment-btn {
            width: 100%;
            border: none;
            padding: 5px;
            background-color: #E9EBE6;
        }

        .segment-btn.selected {
            background-color: #b4cf98;
        }

        .airplane-map {
            min-height: 700px;
            width: 300px;
            margin: 0 auto;
            top: 0;
            left: 50%;
            background: white;
        }

        .plane-head {
            height: 245px;
            width: 299px;
            background-color: #fff;
            background-image: url({{ asset('img/plane-head.png') }});
            background-repeat: no-repeat;
            background-size: cover;
        }

        .plane-body {
            min-height: 500px;
            margin: 0 4px;
            padding: 0 30px;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }

        .seat {
            height: 30px;
            width: 100%;
            display: inline-block;
            background: #eeeeee;
            text-align: center;
            margin-bottom: 10px;
        }

        .seat:hover {
            cursor: pointer;
            background: #dddddd;
        }

        .left-seat {
            margin-left: 5px;
        }

        .right-seat {
            margin-right: 5px;
        }

        .seat-number {
            height: 30px;
            width: 100%;
            display: inline-block;
            text-align: center;
        }

        .left-seat-number {
            margin-left: 5px;
        }

        .right-seat-number {
            margin-right: 5px;
        }

        .col {
            padding: 3px;
            text-align: center;
        }

        .booked {
            background: darkgray;
            color: white;
            opacity: .3
        }

        .seat.selected-c {
            background: #17a2b8 !important;
            color: white;
        }

        .seat.selected-o {
            background: #a18b23 !important;
            color: white;
        }

        .btn:focus {
            box-shadow: none !important;
            color: white !important;
        }

    </style>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: 'window',
                trigger: 'hover'
            }))
        })
    </script>
    <script>
        $(document).on('submit', '#form', function() {
            let submitButton = document.getElementById('confirm');
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.textContent = 'Please wait...';
        })
    </script>
@endpush
