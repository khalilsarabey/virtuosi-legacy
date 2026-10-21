<section>
    @php $prices = []; $businessPrices = []; @endphp
    @if($flight['economy_seats'] >= request()->query('adults', 0) + request()->query('children', 0))
        @php $prices = getPrice($flight, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]); @endphp
    @endif
    @if(config('features.cabins'))
        @if($flight['business_seats'] >= request()->query('adults', 0) + request()->query('children', 0))
            @php $businessPrices = getPrice($flight, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')], 'business'); @endphp
        @endif
    @endif
    @php $requestType = $requestType ?? null; @endphp
    <div class="search-results-cell px-3 py-3 waves-effect mb-3">
        <form>
            <div class="row g-0">
                <div class="col-md-{{ config('features.cabins') ? '8' : '10' }}">
                    <div class="d-flex align-items-center h-100">
                        <div class="row d-flex w-100 align-items-center justify-content-center">
                            <div class="col-md-2 text-center justify-content-center">
                                @if($flight['airline'] && $flight['airline']['thumbnail'])
                                    <img src="/airlines/{{ $flight['airline']['thumbnail'] }}" class="img-fluid" />
                                @else
                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF" class="img-fluid" />
                                @endif
                                <div  style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;"><small>{{ $flight['airline']['name'] }}</small></div>
                                <div style="line-height: 1.2; font-size:14px; font-weight: bold; margin-top: 5px; opacity: .7;">{{ $flight['dep_flight_number'] ?? '' }}</div>
                            </div>
                            <div class="col-md-2">
                                <div class="theme-search-results-item-flight-section-meta text-center">
                                    <label class=" mb-0"></label>
                                    <h4 class="sec__title_list text-success">{{ $flight['dep_from_code'] }}</h4>
                                    <label class="mb-0"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="theme-search-results-item-flight-section-path">
                                    <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                        <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight['dep_time'])->format('H:i') }}</strong></div>
                                        <div class="text-success"><strong>{{ \Carbon\Carbon::parse($flight['dep_date'])->format('D, j M y') }}</strong></div>
                                        <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight['dep_arr_time'])->format('H:i') }}</strong></div>
                                    </div>
                                    <div class="theme-search-results-item-flight-section-path-line"></div>
                                    <div class="theme-search-results-item-flight-section-path-line-start">

                                        <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                        <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                    </div>
                                    <div class="theme-search-results-item-flight-section-path-line-middle w-100" wire:ignore>
                                        <div
                                            class="theme-search-results-item-flight-section-path-line-title text-danger text-weight-bold"
                                            style="font-size: 0.8rem; margin-top:35px;">
                                            @if(auth()->user()->type == 'user' || auth()->user()->type == 'admin')
                                                <strong>Remaining Seats: {{ $flight['seats'] - $flight['tickets_count'] }}</strong>
                                            @elseif(($flight['seats'] - $flight['tickets_count']) <= $flight['remaining_seats_display'])
                                                <strong>Remaining Seats: {{ $flight['seats'] - $flight['tickets_count'] }}</strong>
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
                                    <h4 class="sec__title_list text-success">{{ $flight['dep_to_code'] }}</h4>
                                    <label class="label"></label>
                                </div>
                            </div>
                            @if($flight['type'] == 'fixed')
                                <div class="col-md-2 mt-3 text-center">
                                    <img src="/airlines/{{ $flight['airline']['thumbnail'] }}" class="img-fluid" />
                                    <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;">{{ $flight['airline']['name'] }}</div>
                                    <div style="line-height: 1.2; font-size:14px; font-weight: bold; margin-top: 5px; opacity: .7;">{{ $flight['ret_flight_number'] ?? '' }}</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                        <label class=""></label>
                                        <h4 class="sec__title_list text-success">{{ $flight['ret_from_code'] }}</h4>
                                        <label class=""></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="theme-search-results-item-flight-section-path">
                                        <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight['ret_dep_time'])->format('H:i') }}</strong></div>
                                            <div class="text-success"><strong>{{ \Carbon\Carbon::parse($flight['ret_dep_date'])->format('D, j M y') }}</strong></div>
                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight['ret_arr_time'])->format('H:i') }}</strong></div>
                                        </div>
                                        <div class="theme-search-results-item-flight-section-path-line"></div>
                                        <div class="theme-search-results-item-flight-section-path-line-start">
                                            <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                            <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                        </div>
                                        <div class="theme-search-results-item-flight-section-path-line-middle w-100" wire:ignore>
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
                                        <h4 class="sec__title_list  text-success">{{ $flight['ret_to_code'] }}</h4>
                                        <label class="label"></label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-{{ config('features.cabins') ? '4' : '2' }} d-flex {{ $flight['type'] == 'fixed' ? 'flex-column justify-content-center' : 'flex-column' }} ">
                    <div class="mb-2">
                        <div class="row" wire:ignore>
                            @if(config('features.cabins'))
                                <div class="col">
                                    <div class="font-weight-bold text-uppercase text-primary mb-2">Business</div>
                                    @if(!empty($businessPrices))
                                        @php $supplementsQty = 0 @endphp
                                        @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                            @foreach($businessPrices['adult'] as $adult)
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                    <div class="text-center font-weight-bold text-dark">${{ $adult['price'] }}</div>
                                                </div>
                                                @php $supplementsQty += $adult['number'] @endphp
                                            @endforeach
                                        @endif
                                        @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                            @foreach($businessPrices['child'] as $child)
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                    <div class="text-center font-weight-bold text-dark">${{ $child['price'] }}</div>
                                                </div>
                                                @php $supplementsQty += $child['number'] @endphp
                                            @endforeach
                                        @endif
                                        @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                            @foreach($businessPrices['infant'] as $infant)
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                    <div class="text-center font-weight-bold text-dark">${{ $infant['price'] }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if($requestType == 'oneway')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $flight['oneway_supplements'] }}</div>
                                            </div>
                                        @endif
                                        @if($requestType == 'multi')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $flight['multi_supplements'] }}</div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-gray small">Not Available</div>
                                    @endif
                                </div>
                            @endif
                            <div class="col">
                                <div class="font-weight-bold text-uppercase text-primary mb-2">Economy</div>
                                @if(!empty($prices))
                                    @php $supplementsQty = 0 @endphp
                                    @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                        @foreach($prices['adult'] as $adult)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $adult['price'] }}</div>
                                            </div>
                                            @php $supplementsQty += $adult['number'] @endphp
                                        @endforeach
                                    @endif
                                    @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                        @foreach($prices['child'] as $child)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $child['price'] }}</div>
                                            </div>
                                            @php $supplementsQty += $child['number'] @endphp
                                        @endforeach
                                    @endif
                                    @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                        @foreach($prices['infant'] as $infant)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $infant['price'] }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if($requestType == 'oneway')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                            <div class="text-center font-weight-bold text-dark">${{ $flight['oneway_supplements'] }}</div>
                                        </div>
                                    @endif
                                    @if($requestType == 'multi')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                            <div class="text-center font-weight-bold text-dark">${{ $flight['multi_supplements'] }}</div>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-dark small">Not Available</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
