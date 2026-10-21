<div class="d-flex align-items-center h-100">
        <div class="row d-flex w-100 align-items-center justify-content-center">
            <div class="col-md-2 text-center justify-content-center">
                @if($flight['airline'] && $flight['airline']['thumbnail'])
                    <img src="/airlines/{{ $flight['airline']['thumbnail'] }}" class="img-fluid" />
                @else
                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF" class="img-fluid" />
                @endif
                <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;"><small>{{ $flight['airline']['name'] }}</small></div>
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
