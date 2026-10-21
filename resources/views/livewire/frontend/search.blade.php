<div>
    <livewire:frontend.components.header.search.container/>
    <div id="search-results" class="container mt-5">
        @if($hasCheaper && auth()->user()->agency_id == 1)
            <livewire:components.round-trip-grid :query="$query" :needed_seats="$needed_seats" :foundDepartures="$departures" :foundReturns="$returns" />
        @endif
        <style>
            .promo-badge {
                position: absolute;
                top: 0;
                right: 0;
                background-color: #ff6f61;
                color: #ffffff;
                font-weight: 600;
                font-size: 12px;
                padding: 0px 10px;
                border-radius: 0 8px 0 8px;

            }
        </style>
        <div class="row">
            <div class="col-12">
                <div class="d-flex">
                    <svg fill="{{ $site_settings->primary_color }}" height="20px" width="20px" version="1.1"
                         id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 241.016 241.016" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M210.818,96.393l-49.202,1.644L108.753,0H83.279c-2.791,0-5.052,2.259-5.052,5.055l27.504,94.843l-50.097,2.037
                                    c-4.312,0.004-8.372,0.732-11.97,1.997l-18.925-32.14L8.857,71.788c-2.105,0.004-3.811,1.708-3.811,3.814l13.848,42.361v5.09
                                    L5.047,165.414c-0.002,2.105,1.704,3.814,3.809,3.814l15.885,0.004l19.257-32.713c3.514,1.197,7.455,1.885,11.637,1.885
                                    l50.288,2.046l-27.698,95.516c0,2.795,2.259,5.05,5.052,5.05h25.474l53.227-98.696l48.84,1.631
                                    c13.894,0,25.152-10.652,25.152-23.779C235.971,107.041,224.713,96.393,210.818,96.393z"/>
                            </g>
                        </g>
                    </svg>
                    <h5 class="text-primary mb-3 ml-3">DEPARTURE FLIGHT</h5>
                </div>

                @if($query['type'] == 'fixed' || $query['type'] == 'oneway' || $query['type'] == 'roundtrip' || $query['type'] == 'multi')
                    <div>
                        <livewire:frontend.components.departure-dates wire:key="dep-date" :query="$query" :needed_seats="$needed_seats"/>
                    </div>
                @endif

                @if(count($departures))
                    @php $c = 0 @endphp
                    @forelse($departures as $departureId => $depFlight)
                        @php $c++ @endphp
                        <div wire:key="dep-flight-row-{{ $departureId }}">
                            @if(($depFlight['economy_seats'] >= $needed_seats) || ($depFlight['business_seats'] >= $needed_seats))
                                <section>
                                    <div class="search-results-cell px-3 py-3 waves-effect mb-3">
                                        <form>
                                            @if(($depFlight['promo'] ?? 0) == 1)
                                            <div class="promo-badge">Promo</div>
                                            @endif
                                            <div class="row g-0">
                                                <div class="col-md-{{ config('features.cabins') ? '8' : '10' }}">
                                                    <div class="d-flex align-items-center h-100">
                                                        <div class="row d-flex w-100 align-items-center justify-content-center">
                                                            <div class="col-md-2 text-center justify-content-center">
                                                                @if($depFlight['airline'] && $depFlight['airline']['thumbnail'])
                                                                    <img src="/airlines/{{ $depFlight['airline']['thumbnail'] }}" class="img-fluid" />
                                                                @else
                                                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF" class="img-fluid" />
                                                                @endif
                                                                <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;"><small>{{ $depFlight['airline']['name'] }}</small></div>
                                                                <div style="line-height: 1.2; font-size:14px; font-weight: bold; margin-top: 5px; opacity: .7;">{{ $depFlight['dep_flight_number'] ?? '' }}</div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="theme-search-results-item-flight-section-meta text-center">
                                                                    <label class=" mb-0"></label>
                                                                    <h4 class="sec__title_list text-success">{{ $depFlight['dep_from_code'] }}</h4>
                                                                    <label class="mb-0"></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="theme-search-results-item-flight-section-path">
                                                                    <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                                        <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($depFlight['dep_time'])->format('H:i') }}</strong></div>
                                                                        <div class="text-success"><strong>{{ \Carbon\Carbon::parse($depFlight['dep_date'])->format('D, j M y') }}</strong></div>
                                                                        <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($depFlight['dep_arr_time'])->format('H:i') }}</strong></div>
                                                                    </div>
                                                                    <div class="theme-search-results-item-flight-section-path-line"></div>
                                                                    <div class="theme-search-results-item-flight-section-path-line-start">

                                                                        <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                                                        <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                                    </div>
                                                                    <div class="theme-search-results-item-flight-section-path-line-middle w-100">
                                                                        <div
                                                                            class="theme-search-results-item-flight-section-path-line-title text-danger text-weight-bold"
                                                                            style="font-size: 0.8rem; margin-top:35px;">
                                                                            @if(!empty($depFlight['package_note']) && $depFlight['package_note'] != '' && !empty($query['is_package'])  && $query['is_package'] == 1)
                                                                                <strong>{{ $depFlight['package_note'] }}</strong>
                                                                            @endif
                                                                            @if((auth()->user()->type == 'user' || auth()->user()->type == 'admin') && empty($depFlight['source']))
                                                                                    <strong>Remaining Seats: {{ $depFlight['seats'] - ((int)$depFlight['tickets_count'] + (int)$depFlight['business_tickets_count']) }}</strong>
                                                                                @else
                                                                                    @if(($depFlight['seats'] - ((int)$depFlight['tickets_count'] + (int)$depFlight['business_tickets_count'])) <= $depFlight['remaining_seats_display'])
                                                                                        <strong>Remaining Seats: {{ $depFlight['seats'] - ((int)$depFlight['tickets_count'] + (int)$depFlight['business_tickets_count']) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="theme-search-results-item-flight-section-path-line-end">
                                                                        <i class="la la-plane-arrival theme-search-results-item-flight-section-path-icon"></i>
                                                                        <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="theme-search-results-item-flight-section-meta text-center">
                                                                    <label class="label">

                                                                    </label>
                                                                    <h4 class="sec__title_list text-success">{{ $depFlight['dep_to_code'] }}</h4>
                                                                    <label class="label"></label>
                                                                </div>
                                                            </div>
                                                            @if($depFlight['type'] == 'fixed')
                                                                <div class="col-md-2 mt-3 text-center">
                                                                    <img src="/airlines/{{ $depFlight['airline']['thumbnail'] }}" class="img-fluid" />
                                                                    <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;">{{ $depFlight['airline']['name'] }}</div>
                                                                    <div style="line-height: 1.2; font-size:14px; font-weight: bold; margin-top: 5px; opacity: .7;">{{ $depFlight['ret_flight_number'] ?? '' }}</div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                                        <label class=""></label>
                                                                        <h4 class="sec__title_list text-success">{{ $depFlight['ret_from_code'] }}</h4>
                                                                        <label class=""></label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="theme-search-results-item-flight-section-path">
                                                                        <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($depFlight['ret_dep_time'])->format('H:i') }}</strong></div>
                                                                            <div class="text-success"><strong>{{ \Carbon\Carbon::parse($depFlight['ret_dep_date'])->format('D, j M y') }}</strong></div>
                                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($depFlight['ret_arr_time'])->format('H:i') }}</strong></div>
                                                                        </div>
                                                                        <div class="theme-search-results-item-flight-section-path-line"></div>
                                                                        <div class="theme-search-results-item-flight-section-path-line-start">
                                                                            <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                                                            <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                                        </div>
                                                                        <div class="theme-search-results-item-flight-section-path-line-middle w-100">
                                                                            <div class="theme-search-results-item-flight-section-path-line-title text-danger text-weight-bold" style="font-size: 0.8rem; margin-top:35px;">

                                                                            </div>
                                                                        </div>
                                                                        <div class="theme-search-results-item-flight-section-path-line-end">
                                                                            <i class="la la-plane-arrival theme-search-results-item-flight-section-path-icon"></i>
                                                                            <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                                        <label class="label">

                                                                        </label>
                                                                        <h4 class="sec__title_list  text-success">{{ $depFlight['ret_to_code'] }}</h4>
                                                                        <label class="label"></label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-{{ config('features.cabins') ? '4' : '2' }} d-flex {{ $depFlight['type'] == 'fixed' ? 'flex-column justify-content-center' : 'flex-column' }}">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="font-weight-bold text-uppercase text-primary mb-2">Economy</div>
                                                            @if(!empty($depFlight['has_economy_seats']))
                                                                <div wire:ignore>
                                                                    @php $supplementsQty = 0 @endphp
                                                                    @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                                                        @foreach($depFlight['base_economy_seats_prices']['adult'] ?? [] as $adult)
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $adult['price'] }}</div>
                                                                            </div>
                                                                            @php $supplementsQty += $adult['number'] @endphp
                                                                        @endforeach
                                                                    @endif
                                                                    @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                                                        @foreach($depFlight['base_economy_seats_prices']['child'] as $child)
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $child['price'] }}</div>
                                                                            </div>
                                                                            @php $supplementsQty += $child['number'] @endphp
                                                                        @endforeach
                                                                    @endif
                                                                    @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                                                        @foreach($depFlight['base_economy_seats_prices']['infant'] as $infant)
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $infant['price'] }}</div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                    @if($query['type'] == 'oneway')
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                                                            <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['oneway_supplements'] }}</div>
                                                                        </div>
                                                                    @endif
                                                                    @if($query['type'] == 'multi')
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                                                            <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['multi_supplements'] }}</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="text-gray small">Not Available</div>
                                                            @endif
                                                        </div>
                                                        @if(config('features.cabins'))
                                                            <div class="col">
                                                                <div class="font-weight-bold text-uppercase text-primary mb-2">Business</div>
                                                                @if($depFlight['has_business_seats'])
                                                                    <div wire:ignore>
                                                                        @php $supplementsQty = 0 @endphp
                                                                        @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                                                            @foreach($depFlight['base_business_seats_prices']['adult'] ?? [] as $adult)
                                                                                <div class="d-flex justify-content-between">
                                                                                    <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                                                    <div class="text-dark small font-weight-bold text-dark">${{ $adult['price'] }}</div>
                                                                                </div>
                                                                                @php $supplementsQty += $adult['number'] @endphp
                                                                            @endforeach
                                                                        @endif
                                                                        @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                                                            @foreach($depFlight['base_business_seats_prices']['child'] ?? [] as $child)
                                                                                <div class="d-flex justify-content-between">
                                                                                    <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                                                    <div class="text-dark small font-weight-bold text-dark">${{ $child['price'] }}</div>
                                                                                </div>
                                                                                @php $supplementsQty += $child['number'] @endphp
                                                                            @endforeach
                                                                        @endif
                                                                        @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                                                            @foreach($depFlight['base_business_seats_prices']['infant'] ?? [] as $infant)
                                                                                <div class="d-flex justify-content-between">
                                                                                    <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                                                    <div class="text-dark small font-weight-bold text-dark">${{ $infant['price'] }}</div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                        @if($query['type'] == 'oneway')
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['oneway_supplements'] }}</div>
                                                                            </div>
                                                                        @endif
                                                                        @if($query['type'] == 'multi')
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['multi_supplements'] }}</div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <div class="small text-gray">Not Available</div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            @if($depFlight['has_economy_seats'])
                                                                <div class="{{ $depFlight['type'] != 'fixed' ? 'mt-auto' : '' }}">
                                                                    <button wire:click.prevent="setDeparture({{ $departureId }}, 'economy')"
                                                                            wire:target="setDeparture"
                                                                            wire:loading.attr="disabled"
                                                                            class="mt-2 btn {{ is_numeric($selectedDeparture) && $selectedDeparture == $depFlight['id'] && $selectedDepartureCabin == 'economy' ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles"
                                                                            {{ is_numeric($selectedDeparture) && $selectedDeparture == $depFlight['id'] && $selectedDepartureCabin == 'economy' ? 'disabled' : '' }}
                                                                            style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                                        <div class="d-none" wire:target="setDeparture" wire:loading.class="d-block">
                                                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                                                <span class="visually-hidden"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div wire:target="setDeparture" wire:loading.class="d-none">
                                                                            {{ is_numeric($selectedDeparture) && $selectedDeparture == $depFlight['id'] && $selectedDepartureCabin == 'economy' ? 'Selected' : ($depFlight['onhold_bookings'] == 1 ? 'Request' : 'Select') }}
                                                                        </div>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if(config('features.cabins'))
                                                            <div class="col">
                                                                @if($depFlight['has_business_seats'])
                                                                    <div class="{{ $depFlight['type'] != 'fixed' ? 'mt-auto' : '' }}">
                                                                        <button wire:click.prevent="setDeparture({{ $departureId }}, 'business')"
                                                                                wire:target="setDeparture"
                                                                                wire:loading.attr="disabled"
                                                                                class="mt-2 btn {{ is_numeric($selectedDeparture) && $selectedDeparture == $depFlight['id'] && $selectedDepartureCabin == 'business' ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles"
                                                                                {{ is_numeric($selectedDeparture) && $selectedDeparture == $depFlight['id'] && $selectedDepartureCabin == 'business' ? 'disabled' : '' }}
                                                                                style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                                            <div class="d-none" wire:target="setDeparture" wire:loading.class="d-block">
                                                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                                                    <span class="visually-hidden"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div wire:target="setDeparture" wire:loading.class="d-none">
                                                                                {{ is_numeric($selectedDeparture) && $selectedDeparture == $depFlight['id'] && $selectedDepartureCabin == 'business' ? 'Selected' : ($depFlight['onhold_bookings'] == 1 ? 'Request' : 'Select') }}
                                                                            </div>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            @else
                                <div class="mt-4">No available seats</div>
                            @endif
                        </div>
                    @empty
                        <div class="mt-4">No available seats</div>
                    @endforelse
                @else
                    <div class="mt-4">No available seats</div>
                @endif

                @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                    <div class="d-flex mt-5 pt-5">
                        <svg fill="{{ $site_settings->primary_color }}" height="20px" width="20px" version="1.1"
                             id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 472.615 472.615" xml:space="preserve" transform="matrix(-1, 0, 0, 1, 0, 0)">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <g>
                                        <path
                                            d="M399.3,179.692c-29.984,0.057-59.984,0.517-89.994,1.313L182.565,0h-66.816l85.4,185.271 c-35.293,1.789-70.583,3.921-105.857,6.347L42.16,98.18H6.482l34.143,97.387c-17.54,0.637-31.654,18.562-31.654,40.719 c0,22.164,14.121,40.092,31.668,40.721L6.482,374.432H42.16l53.151-93.471c35.269,2.431,70.554,4.573,105.841,6.37 l-85.404,185.284h66.816l126.726-180.983c30.015,0.8,60.02,1.266,90.009,1.324c36.339-0.307,66.834-26.385,66.834-56.517 c0-0.077,0-0.154,0-0.154C466.134,206.077,435.733,179.923,399.3,179.692z"/>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        <h5 class="text-primary mb-3 ml-3">RETURN FLIGHT</h5>
                    </div>

                    @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                        <div>
                            <livewire:frontend.components.return-dates wire:key="ret-date" :query="$query" :needed_seats="$needed_seats"/>
                        </div>
                    @endif

                    @if(count($returns))
                        @php $i = 0; @endphp
                        @forelse($returns as $returnId => $return)
                            @php $i++ @endphp
                            @php $disabled = false; @endphp
                            @if(
                                !is_numeric($selectedDepartureKey) ||
                                (!empty($selectedAirline) && $return['airline']['icao'] != $selectedAirline && $return['airline']['combinable'] != 1) ||
                                (is_numeric($apiSource) && (!empty($return['source']) && $return['source'] != $apiSource || empty($return['source']))) ||
                                (!is_numeric($apiSource) && !empty($return['source']))
                            )
                                @php $disabled = true @endphp
                            @endif
                                <section>
                                    <div class="search-results-cell px-3 py-3 waves-effect mb-3">
                                        <form>
                                            @if(($return['promo'] ?? 0) == 1)
                                                <div class="promo-badge">Promo</div>
                                            @endif
                                            <div class="row g-0">
                                                <div class="col-md-{{ config('features.cabins') ? '8' : '10' }}">
                                                    <div class="d-flex align-items-center h-100">
                                                        <div class="row d-flex w-100 align-items-center justify-content-center">
                                                            <div class="col-md-2 text-center justify-content-center">
                                                                @if($return['airline'] && $return['airline']['thumbnail'])
                                                                    <img src="/airlines/{{ $return['airline']['thumbnail'] }}" class="img-fluid" />
                                                                @else
                                                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF" class="img-fluid" />
                                                                @endif
                                                                <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;"><small>{{ $return['airline']['name'] }}</small></div>
                                                                <div style="line-height: 1.2; font-size:14px; font-weight: bold; margin-top: 5px; opacity: .7;">{{ $return['dep_flight_number'] ?? '' }}</div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="theme-search-results-item-flight-section-meta text-center">
                                                                    <label class=" mb-0"></label>
                                                                    <h4 class="sec__title_list text-success">{{ $return['dep_from_code'] }}</h4>
                                                                    <label class="mb-0"></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="theme-search-results-item-flight-section-path">
                                                                    <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                                        <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($return['dep_time'])->format('H:i') }}</strong></div>
                                                                        <div class="text-success"><strong>{{ \Carbon\Carbon::parse($return['dep_date'])->format('D, j M y') }}</strong></div>
                                                                        <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($return['dep_arr_time'])->format('H:i') }}</strong></div>
                                                                    </div>
                                                                    <div class="theme-search-results-item-flight-section-path-line"></div>
                                                                    <div class="theme-search-results-item-flight-section-path-line-start">

                                                                        <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                                                        <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                                    </div>
                                                                    <div class="theme-search-results-item-flight-section-path-line-middle w-100">
                                                                        <div
                                                                            class="theme-search-results-item-flight-section-path-line-title text-danger text-weight-bold"
                                                                            style="font-size: 0.8rem; margin-top:35px;">
                                                                            @if(!empty($return['package_note']) && $return['package_note'] != '' && !empty($query['is_package'])  && $query['is_package'] == 1)
                                                                                <strong>{{ $return['package_note'] }}</strong><br/>
                                                                            @endif
                                                                                @if((auth()->user()->type == 'user' || auth()->user()->type == 'admin') && empty($return['source']))
                                                                                    <strong>Remaining Seats: {{ $return['seats'] - ((int)$return['tickets_count'] + (int)$return['business_tickets_count']) }}</strong>
                                                                                @else
                                                                                    @if(($return['seats'] - ((int)$return['tickets_count'] + (int)$return['business_tickets_count'])) <= $return['remaining_seats_display'])
                                                                                        <strong>Remaining Seats: {{ $return['seats'] - ((int)$return['tickets_count'] + (int)$return['business_tickets_count']) }}</strong>
                                                                                    @endif
                                                                                @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="theme-search-results-item-flight-section-path-line-end">
                                                                        <i class="la la-plane-arrival theme-search-results-item-flight-section-path-icon"></i>
                                                                        <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="theme-search-results-item-flight-section-meta text-center">
                                                                    <label class="label">

                                                                    </label>
                                                                    <h4 class="sec__title_list text-success">{{ $return['dep_to_code'] }}</h4>
                                                                    <label class="label"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-{{ config('features.cabins') ? '4' : '2' }} d-flex {{ $return['type'] == 'fixed' ? 'flex-column justify-content-center' : 'flex-column' }}">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="font-weight-bold text-uppercase text-primary mb-2">Economy</div>
                                                            @if(!empty($return['has_economy_seats']))
                                                                <div wire:ignore>
                                                                    @php $supplementsQty = 0 @endphp
                                                                    @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                                                        @foreach($return['base_economy_seats_prices']['adult'] ?? [] as $adult)
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $adult['price'], 2 }}</div>
                                                                            </div>
                                                                            @php $supplementsQty += $adult['number'] @endphp
                                                                        @endforeach
                                                                    @endif
                                                                    @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                                                        @foreach($return['base_economy_seats_prices']['child'] as $child)
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $child['price'], 2 }}</div>
                                                                            </div>
                                                                            @php $supplementsQty += $child['number'] @endphp
                                                                        @endforeach
                                                                    @endif
                                                                    @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                                                        @foreach($return['base_economy_seats_prices']['infant'] as $infant)
                                                                            <div class="d-flex justify-content-between">
                                                                                <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $infant['price'], 2 }}</div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                    @if($query['type'] == 'oneway')
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                                                            <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['oneway_supplements'] }}</div>
                                                                        </div>
                                                                    @endif
                                                                    @if($query['type'] == 'multi')
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                                                            <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['multi_supplements'] }}</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="text-gray small">Not Available</div>
                                                            @endif
                                                        </div>
                                                        @if(config('features.cabins'))
                                                            <div class="col">
                                                                <div class="font-weight-bold text-uppercase text-primary mb-2">Business</div>
                                                                @if($return['has_business_seats'])
                                                                    <div wire:ignore>
                                                                        @php $supplementsQty = 0 @endphp
                                                                        @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                                                            @foreach($return['base_business_seats_prices']['adult'] ?? [] as $adult)
                                                                                <div class="d-flex justify-content-between">
                                                                                    <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                                                    <div class="text-dark small font-weight-bold text-dark">${{ $adult['price'], 2 }}</div>
                                                                                </div>
                                                                                @php $supplementsQty += $adult['number'] @endphp
                                                                            @endforeach
                                                                        @endif
                                                                        @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                                                            @foreach($return['base_business_seats_prices']['child'] ?? [] as $child)
                                                                                <div class="d-flex justify-content-between">
                                                                                    <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                                                    <div class="text-dark small font-weight-bold text-dark">${{ $child['price'], 2 }}</div>
                                                                                </div>
                                                                                @php $supplementsQty += $child['number'] @endphp
                                                                            @endforeach
                                                                        @endif
                                                                        @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                                                            @foreach($return['base_business_seats_prices']['infant'] ?? [] as $infant)
                                                                                <div class="d-flex justify-content-between">
                                                                                    <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                                                    <div class="text-dark small font-weight-bold text-dark">${{ $infant['price'], 2 }}</div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                        @if($query['type'] == 'oneway')
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['oneway_supplements'] }}</div>
                                                                            </div>
                                                                        @endif
                                                                        @if($query['type'] == 'multi')
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                                                                <div class="text-dark small font-weight-bold text-dark">${{ $depFlight['multi_supplements'] }}</div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <div class="small text-gray">Not Available</div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            @if($return['has_economy_seats'])
                                                                <div class="{{ $return['type'] != 'fixed' ? 'mt-auto' : '' }}">
                                                                    <button wire:click.prevent="setReturn({{ $returnId }}, 'economy')"
                                                                            wire:target="setReturn"
                                                                            {{ $disabled ? 'disabled' : '' }}
                                                                            wire:loading.attr="disabled"
                                                                            class="mt-2 btn {{ is_numeric($selectedReturn) && $selectedReturn == $return['id'] && $selectedReturnCabin == 'economy' ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles"
                                                                            {{ is_numeric($selectedReturn) && $selectedReturn == $return['id'] && $selectedReturnCabin == 'economy' ? 'disabled' : '' }}
                                                                            style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                                        <div class="d-none" wire:target="setReturn" wire:loading.class="d-block">
                                                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                                                <span class="visually-hidden"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div wire:target="setReturn" wire:loading.class="d-none">
                                                                            {{ is_numeric($selectedReturn) && $selectedReturn == $return['id'] && $selectedReturnCabin == 'economy' ? 'Selected' : ($return['onhold_bookings'] == 1 ? 'Request' : 'Select') }}
                                                                        </div>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if(config('features.cabins'))
                                                            <div class="col">
                                                                @if($return['has_business_seats'])
                                                                    <div class="{{ $return['type'] != 'fixed' ? 'mt-auto' : '' }}">
                                                                        <button wire:click.prevent="setReturn({{ $returnId }}, 'business')"
                                                                                wire:target="setReturn"
                                                                                {{ $disabled ? 'disabled' : '' }}
                                                                                wire:loading.attr="disabled"
                                                                                class="mt-2 btn {{ is_numeric($selectedReturn) && $selectedReturn == $return['id'] && $selectedReturnCabin == 'business' ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles"
                                                                                {{ is_numeric($selectedReturn) && $selectedReturn == $return['id'] && $selectedReturnCabin == 'business' ? 'disabled' : '' }}
                                                                                style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                                            <div class="d-none" wire:target="setReturn" wire:loading.class="d-block">
                                                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                                                    <span class="visually-hidden"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div wire:target="setReturn" wire:loading.class="d-none">
                                                                                {{ is_numeric($selectedReturn) && $selectedReturn == $return['id'] && $selectedReturnCabin == 'business' ? 'Selected' : ($return['onhold_bookings'] == 1 ? 'Request' : 'Select') }}
                                                                            </div>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                        @empty
                            <div class="mt-4">No available seats</div>
                        @endforelse
                    @else
                        <div class="mt-4">No available seats</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div id="payment-summary">
        @if($selectedDeparture || $selectedReturn)
            @if($isPackage)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 ml-auto">
                            <h5 class="mb-3">Flights Summary</h5>
                            <form method="post" action="{{ $selectedHotel ? route('frontend.hotels.show', $selectedHotel) : route('frontend.book') }}">
                                <div class="p-3 bg-white mb-3" style="border: 1px solid lightgrey">
                                    <div class="row">
                                        @if($basePrices['adults'] > 0)
                                            <div class="col-6 font-weight-medium text-dark">
                                                Adult(s)
                                            </div>
                                            <div class="col-6 text-right font-weight-bold text-primary">
                                                ${{ $basePrices['adults'] }}
                                            </div>
                                        @endif
                                        @if($basePrices['children'] > 0)
                                            <div class="col-6 font-weight-medium">
                                                Child(ren)
                                            </div>
                                            <div class="col-6 text-right font-weight-bold text-primary">
                                                ${{ $basePrices['children'] }}
                                            </div>
                                        @endif
                                        @if($basePrices['infants'] > 0)
                                            <div class="col-6 font-weight-medium">
                                                Infant(s)
                                            </div>
                                            <div class="col-6 text-right font-weight-medium text-primary">
                                                ${{ $basePrices['infants'] }}
                                            </div>
                                        @endif
                                        @if($basePrices['supplements'] > 0)
                                            <div class="col-6 font-weight-medium">
                                                Supplements
                                            </div>
                                            <div class="col-6 text-right font-weight-medium text-primary">
                                                ${{ $basePrices['supplements'] }}
                                            </div>
                                        @endif
                                            @if($basePrices['supplements'] < 0)
                                                <div class="col-6 font-weight-medium">
                                                    Discount
                                                </div>
                                                <div class="col-6 text-right font-weight-medium text-primary">
                                                    ${{ abs($basePrices['supplements']) }}
                                                </div>
                                            @endif
                                        <div class="mx-3 my-3 w-100" style="background:lightgrey; height:1px;"></div>
                                        <div class="col-6 font-weight-medium">
                                            Total
                                        </div>
                                        <div class="col-6 text-right font-weight-medium text-primary">
                                            ${{ $basePrices['total'] }}
                                        </div>
                                    </div>
                                </div>
                                @if(is_array($hotels))
                                    <div class="row">
                                        @if(count($hotels))
                                            <?php
                                                $sortedHotels = collect($hotels)->map(function ($hotel) use ($query) {
                                                    $lowestPrice = null;
                                                    foreach ($hotel['rooms'] as $hotel_room) {
                                                        foreach ($query['rooms'] as $requestedRoom) {
                                                            $price = (int) calculateRoomPrice($hotel_room, $hotel_room['prices'], $query['dep_date'], $query['ret_date'], ['adult' => $requestedRoom['adult'], 'child' => $requestedRoom['child'], 'infant' => $requestedRoom['infant']]);
                                                            if($price > 0) {
                                                                if ($lowestPrice == 0 || $lowestPrice > $price) {
                                                                    $lowestPrice = (int) $price;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $hotel['lowest_price'] = (int) $lowestPrice;
                                                    return $hotel;
                                                })->sortBy('lowest_price')->values()->toArray();
                                                ?>
                                            <div class="col-12">
                                                <h5 class="mb-3">Select a Hotel</h5>
                                            </div>
                                            @foreach($sortedHotels as $hotel)
                                                <div class="card--item col-12 mb-3">
                                                    <div class="card p-2 rounded-2">
                                                        <div class="row g-3">
                                                            <div class="col-md-3">
                                                                <div class="bg-light rounded-1 overflow-hidden"
                                                                     style="height:150px">
                                                                    @if(count($hotel['logo']))
                                                                        @foreach($hotel['logo'] as $logo)
                                                                            <img src="{{ $logo['medium'] }}"
                                                                                 class="img-fluid"
                                                                                 alt="{{ $hotel['name'] }}">
                                                                        @endforeach
                                                                    @else
                                                                        <img src="{{ asset('img/default.jpeg') }}"
                                                                             class="img-fluid"
                                                                             alt="{{ $hotel['name'] }}">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="card-body p-1 overflow-hidden"
                                                                     style="line-height:25px">
                                                                    <h5 class="text-nowrap card-title d-flex align-items-center mt-2 mb-3">
                                                                        <strong>{{ $hotel['name'] }}</strong></h5>
                                                                    <small
                                                                        class="d-flex justify-content-start gap-1 align-items-center mb-1">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="16" height="16" viewBox="0 0 24 24"
                                                                             fill="none" stroke="#000000"
                                                                             stroke-width="2" stroke-linecap="round"
                                                                             stroke-linejoin="round">
                                                                            <path
                                                                                d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                                                        </svg>
                                                                        <span class="px-2">Rating {{ (int) $hotel['rating'] }}<span
                                                                                class="">/5</span></span>

                                                                        @for ($i = 0; $i < $hotel['rating']; $i++)
                                                                            <svg class="stars"
                                                                                 style="margin-right:3px; fill: #ff8b00; stroke: #ff8b00;"
                                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                                 width="14" height="14"
                                                                                 viewBox="0 0 24 24" fill="none"
                                                                                 stroke="" stroke-width="2"
                                                                                 stroke-linecap="round"
                                                                                 stroke-linejoin="round">
                                                                                <polygon
                                                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                            </svg>
                                                                        @endfor

                                                                    </small>
                                                                    <p class="card-text text-capitalize mb-0"><small>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="16" height="16"
                                                                             viewBox="0 0 24 24" fill="none"
                                                                             stroke="#0f294d" stroke-width="2"
                                                                             stroke-linecap="square"
                                                                             stroke-linejoin="arcs">
                                                                            <path
                                                                                d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path>
                                                                            <circle cx="12" cy="10" r="3"></circle>
                                                                        </svg>
                                                                        {{ $hotel['city']['city'] }}
                                                                        , {{ ucfirst(strtolower($hotel['city']['country'])) }}
                                                                    </small>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 my-auto">
                                                                <div class="h6"><small style="font-size: 14px; font-weight: 300; color: #5b5b5b;">Available Rooms</small></div>
                                                                @foreach($hotel['rooms'] as $hotel_room)
                                                                    <div class="d-flex justify-content-between mb-0" style="line-height: 100%;">
                                                                        <div class="mb-auto font-size-13">{{ $hotel_room['room_type']['name'] }}</div>
                                                                        @if(auth()->user()->type == 'user' || auth()->user()->type == 'admin')
                                                                            <div class="font-size-12 text-muted">x {{ $hotel_room['units'] - $hotel_room['bookings_count'] }}</div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-end  flex-wrap mb-2 px-3">
                                                                <div class="w-100">
                                                                    <div class="w-100">
                                                                        <p class="card-text h6 text-center">
                                                                            <span style="font-size: 15px; font-weight: 500; color: #5b5b5b;">STARTING</span>
                                                                            <br/><br/>
                                                                            <strong>${{ number_format($hotel['lowest_price']) }}</strong>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="w-100">
                                                                    <button wire:target="selectHotel" wire:loading.attr="disabled" type="button" wire:click="selectHotel({{ $hotel['id'] }})"
                                                                            class="btn {{ $selectedHotel == $hotel['id'] ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase"
                                                                            style="font-weight: bold; text-transform: uppercase; font-size: 13px;">
                                                                            <div class="d-none" wire:target="selectHotel" wire:loading.class="d-block">
                                                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                                                    <span class="visually-hidden"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div wire:target="selectHotel" wire:loading.class="d-none">
                                                                                @if($selectedHotel == $hotel['id'])
                                                                                    <span class="px-2">Selected</span>
                                                                                @else
                                                                                    <span class="px-2">Select Hotel</span>
                                                                                @endif
                                                                            </div>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="alert alert-danger text-center w-100">No hotels available
                                                </div>
                                                @if(1 == 2)
                                                <div class="w-100 text-center">
                                                    <button
                                                        class="ml-auto mt-4 text-uppercase text-white border-0 h6 bg-success p-3 disable-styles"
                                                        onclick="this.form.submit(); this.disabled=true; this.textContent='Please wait…'; "
                                                        wire:loading.attr="disabled">Book Flights Only
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        @csrf
                                        @if(is_numeric($selectedHotel))
                                            <input type="hidden" name="hotel_id" value="{{ $selectedHotel }}" />
                                        @endif
                                        @foreach($this->query as $k => $v)
                                            @if(is_array($v))
                                                <input type="hidden" name="{{ $k }}" value="{{ json_encode($v) }}"/>
                                            @else
                                                <input type="hidden" name="{{ $k }}" value="{{ $v }}"/>
                                            @endif
                                        @endforeach
                                        <input type="hidden" name="dep_class" value="{{ $prices['dep_class'] ?? '' }}" />
                                        <input type="hidden" name="ret_class" value="{{ $prices['ret_class'] ?? '' }}" />
                                        <input type="hidden" name="dep_cabin" value="{{ $selectedDepartureCabin ?? '' }}" />
                                        <input type="hidden" name="ret_cabin" value="{{ $selectedReturnCabin ?? '' }}" />
                                        <input type="hidden" name="departure" value="{{ $selectedDeparture }}"/>
                                        <input type="hidden" name="return" value="{{ $selectedReturn }}"/>
                                        <input type="hidden" name="total" value="{{ $prices['total'] }}"/>
                                        <input type="hidden" name="type" value="{{ $this->query['type'] }}"/>
                                        @if(isset($selectedApiDeparture['source']) )
                                            <input type="hidden" name="api" value="{{  $selectedApiDeparture['source'] ?? '' }}" />
                                        @endif
                                        <input type="hidden" name="adults" value="{{ $adults }}" />
                                        <input type="hidden" name="infants" value="{{ $infants }}" />
                                        <input type="hidden" name="children" value="{{ $children }}" />
                                        @if(($selectedDeparture && $query['type'] != 'roundtrip' && $query['type'] != 'multi') || (($query['type'] == 'roundtrip' || $query['type'] == 'multi') && $selectedDeparture && $selectedReturn))
                                            <button
                                                id="confirm-and-proceed-button"
                                                class="mt-4 text-uppercase w-100 border-0 h6 btn-primary p-3"
                                                onclick="this.form.submit(); this.disabled=true; this.textContent='Please wait…'; "
                                                wire:loading.attr="disabled">Confirm & Proceed
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row">
                        <div class="col-4 ml-auto">
                            <div class="p-3 bg-white" style="border: 1px solid lightgrey">
                                <form method="post" action="{{ route('frontend.book') }}">
                                    <div class="row">
                                        @if($prices['adults'] > 0)
                                            <div class="col-6 font-weight-medium text-dark">
                                                Adult(s)
                                            </div>
                                            <div class="col-6 text-right font-weight-bold text-primary">
                                                ${{ $prices['adults'] }}
                                            </div>
                                        @endif
                                        @if($prices['children'] > 0)
                                            <div class="col-6 font-weight-medium">
                                                Child(ren)
                                            </div>
                                            <div class="col-6 text-right font-weight-bold text-primary">
                                                ${{ $prices['children'] }}
                                            </div>
                                        @endif
                                        @if($prices['infants'] > 0)
                                            <div class="col-6 font-weight-medium">
                                                Infant(s)
                                            </div>
                                            <div class="col-6 text-right font-weight-medium text-primary">
                                                ${{ $prices['infants'] }}
                                            </div>
                                        @endif
                                        @if($prices['supplements'] > 0)
                                            <div class="col-6 font-weight-medium">
                                                Supplements
                                            </div>
                                            <div class="col-6 text-right font-weight-medium text-primary">
                                                ${{ $prices['supplements'] }}
                                            </div>
                                        @endif
                                            @if($prices['supplements'] < 0)
                                                <div class="col-6 font-weight-medium">
                                                    Discount
                                                </div>
                                                <div class="col-6 text-right font-weight-medium text-primary">
                                                    ${{ abs($prices['supplements']) }}
                                                </div>
                                            @endif
                                        <div class="mx-3 my-3 w-100" style="background:lightgrey; height:1px;"></div>
                                        <div class="col-6 font-weight-medium">
                                            Total
                                        </div>
                                        <div class="col-6 text-right font-weight-medium text-primary">
                                            ${{ $prices['total'] }}
                                        </div>
                                        <div class="col-12">
                                            @csrf
                                            @if(isset($selectedApiDeparture['source']) )
                                            <input type="hidden" name="api" value="{{ ($query['type'] == 'roundtrip' && !empty($selectedApiDeparture) && !empty($selectedApiReturn)) || ($query['type'] != 'roundtrip' && !empty($selectedApiDeparture)) ? $selectedApiDeparture['source'] : '' }}" />
                                            @endif
                                            <input type="hidden" name="departure" value="{{ $selectedDeparture }}" />
                                            <input type="hidden" name="return" value="{{ $selectedReturn }}" />
                                            <input type="hidden" name="ret_cabin" value="{{ $selectedReturnCabin ?? '' }}" />
                                            <input type="hidden" name="dep_cabin" value="{{ $selectedDepartureCabin }}"/>
                                            <input type="hidden" name="dep_class" value="{{ $prices['dep_class'] ?? '' }}" />
                                            <input type="hidden" name="ret_class" value="{{ $prices['ret_class'] ?? '' }}" />
                                            <input type="hidden" name="total" value="{{ $prices['total'] }}" />
                                            <input type="hidden" name="type" value="{{ $query['type'] }}" />
                                            <input type="hidden" name="adults" value="{{ $adults }}" />
                                            <input type="hidden" name="infants" value="{{ $infants }}" />
                                            <input type="hidden" name="children" value="{{ $children }}" />
                                            @if(($selectedDeparture && $query['type'] != 'roundtrip' && $query['type'] != 'multi') || (($query['type'] == 'roundtrip' || $query['type'] == 'multi') && $selectedDeparture && $selectedReturn))
                                                <button
                                                    class="mt-4 text-uppercase w-100 border-0 h6 btn-primary p-3"
                                                    onclick="this.form.submit(); this.disabled=true; this.textContent='Please wait…'; "
                                                    wire:loading.attr="disabled">Confirm & Proceed
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@push('scripts')
    <script>
        const element = document.getElementById('search-results');
        element.scrollTop = element.scrollHeight;

        window.addEventListener('scroll-to-summary', event => {
            $('html, body').animate({
                scrollTop: $('#payment-summary').offset().top
            }, 800);
        });

        window.addEventListener('scroll-to-button', event => {
            scrollToHotelButton();
        });

        function scrollToHotelButton() {
            $('html, body').animate({
                scrollTop: $('#confirm-and-proceed-button').offset().top
            }, 800);
        }
    </script>
@endpush
