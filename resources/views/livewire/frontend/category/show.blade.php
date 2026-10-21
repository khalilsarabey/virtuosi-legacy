<div class="container">
        <div class="row">
            <div class="col-12">
                @foreach($category->banner as $key => $entry)
                    <img data-src="{{ $entry['url'] }}" class="ls-is-cached lazyloaded w-100" src="{{ $entry['url'] }}">
                @endforeach
            </div>
            <div class="col-12 mt-5">
                @foreach($flights as $id => $flight)
                    <section wire:key="{{ $id }}">
                        <div class="search-results-cell px-3 py-3 waves-effect mb-3">
                            <form method="post" action="{{ route('frontend.book') }}">
                                <div class="row g-0">
                                    <div class="col-md-10">
                                        <div class="d-flex align-items-center h-100">
                                            <div class="row d-flex w-100 align-items-center">
                                                <div class="col-md-2 text-center">
                                                    @if($flight->airline && $flight->airline->thumbnail)
                                                        <img src="/airlines/{{ $flight->airline->thumbnail }}" class="img-fluid" />
                                                    @else
                                                        <img src="https://via.placeholder.com/150/6c757d/FFFFFF/?text=samadonline.com" class="img-fluid" />
                                                    @endif
                                                    <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;">{{ $flight->airline?->name }}</div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                        <label class=""></label>
                                                        <h4 class="sec__title_list text-success">{{ $flight->dep_from_code }}</h4>
                                                        <label class=""></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="theme-search-results-item-flight-section-path">
                                                        <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight->dep_time)->format('H:i') }}</strong></div>
                                                            <div class="text-success"><strong>{{ \Carbon\Carbon::parse($flight->dep_date)->format('D, j M y') }}</strong></div>
                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight->dep_arr_time)->format('H:i') }}</strong></div>
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
                                                                @if(($flight->seats - $flight->tickets_count) <= $flight->remaining_seats_display)
                                                                    <strong>Remaining Seats: {{ $flight->seats - $flight->tickets_count }}</strong>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="theme-search-results-item-flight-section-path-line-end">
                                                            <i class="la la-plane-arrival theme-search-results-item-flight-section-path-icon"></i>
                                                            <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                        <label class="label">

                                                        </label>
                                                        <h4 class="sec__title_list text-success">{{ $flight->dep_to_code }}</h4>
                                                        <label class="label"></label>
                                                    </div>
                                                </div>
                                                @if($flight->type == 'fixed')
                                                    <div class="col-md-2 mt-3 text-center">
                                                        <img src="/airlines/{{ $flight->airline?->thumbnail }}" class="img-fluid" />
                                                        <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;">{{ $flight->airline?->name }}</div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="theme-search-results-item-flight-section-meta text-center">
                                                            <label class=""></label>
                                                            <h4 class="sec__title_list text-success">{{ $flight->ret_from_code }}</h4>
                                                            <label class=""></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="theme-search-results-item-flight-section-path">
                                                            <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                                <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight->ret_dep_time)->format('H:i') }}</strong></div>
                                                                <div class="text-success"><strong>{{ \Carbon\Carbon::parse($flight->ret_dep_date)->format('D, j M y') }}</strong></div>
                                                                <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($flight->ret_arr_time)->format('H:i') }}</strong></div>
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
                                                            <h4 class="sec__title_list  text-success">{{ $flight->ret_to_code }}</h4>
                                                            <label class="label"></label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex {{ $flight->type == 'fixed' ? 'flex-column justify-content-center' : 'flex-column' }} ">
                                        <div class="mb-2">
                                            @php $price = 0 @endphp
                                            @php $prices = getPrice($flight, null, ['adult' => $adults[$flight->id], 'infant' => $infants[$flight->id], 'child' => $children[$flight->id]]); @endphp
                                            @if(isset($adults[$flight->id]) && $adults[$flight->id] > 0)
                                                @foreach($prices['adult'] as $adult)
                                                    <div class="d-flex justify-content-between">
                                                        <div class="text-dark small font-weight-bold">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                        <div class="text-center font-weight-bold text-dark">${{ $adult['price'] }}</div>
                                                    </div>
                                                    @php $price += $adult['price'] * $adult['number'] @endphp
                                                @endforeach
                                            @endif
                                            @if(isset($children[$flight->id]) && $children[$flight->id] > 0)
                                                @foreach($prices['child'] as $child)
                                                    <div class="d-flex justify-content-between">
                                                        <div class="text-dark small font-weight-bold">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                        <div class="text-center font-weight-bold text-dark">${{ $child['price'] }}</div>
                                                    </div>
                                                    @php $price += $child['price'] * $child['number'] @endphp
                                                @endforeach
                                            @endif
                                            @if(isset($infants[$flight->id]) && $infants[$flight->id] > 0)
                                                @foreach($prices['infant'] as $infant)
                                                    <div class="d-flex justify-content-between">
                                                        <div class="text-dark small font-weight-bold">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                        <div class="text-center font-weight-bold text-dark">${{ $infant['price'] }}</div>
                                                    </div>
                                                    @php $price += $infant['price'] * $infant['number'] @endphp
                                                @endforeach
                                            @endif
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small text-primary font-weight-bold">TOTAL</div>
                                                <div class="text-center font-weight-bold text-primary">${{ $price }}</div>
                                            </div>
                                            <div>
                                                <div class="mb-3 mt-3">
                                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                                        <label><i class="la la-user"></i> Adult</label>
                                                        <div class="roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                            <div wire:click="decrement('adults', '{{$flight->type}}', '{{$flight->id}}')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                            <input disabled type="text" name="adults" wire:model="adults.{{$flight->id}}">
                                                            <div wire:ignore.self wire:click="increment('adults', '{{$flight->type}}', '{{$flight->id}}', {{ ($flight->seats - $flight->tickets_count) }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                                        <label><i class="la la-female"></i> Child</label>
                                                        <div class="children roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                            <div wire:click="decrement('children', '{{$flight->type}}', '{{$flight->id}}')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                            <input disabled type="text" name="children" wire:model="children.{{$flight->id}}">
                                                            <div wire:ignore.self wire:click="increment('children', '{{$flight->type}}', '{{$flight->id}}', {{ ($flight->seats - $flight->tickets_count) }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                                        <label><i class="la la-female"></i> Infant</label>
                                                        <div class="infants roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                            <div wire:click="decrement('infants', '{{$flight->type}}', '{{$flight->id}}')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                            <input disabled type="text" name="infants" wire:model="infants.{{$flight->id}}">
                                                            <div wire:ignore.self wire:click="increment('infants', '{{$flight->type}}', '{{$flight->id}}', {{ ($flight->seats - $flight->tickets_count) }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="dep_cabin" value="{{ $selectedDepartureCabin ?? 'economy' }}" />
                                            <input type="hidden" name="ret_cabin" value="{{ $selectedReturnCabin ?? 'economy' }}" />
                                            <input type="hidden" name="departure" value="{{ $flight->id }}" />
                                            <input type="hidden" name="type" value="{{ $flight->type }}" />
                                            <input type="hidden" name="total" value="{{ $price }}" />
                                            <input type="hidden" name="adults" value="{{ $adults[$flight->id] }}" />
                                            <input type="hidden" name="infants" value="{{ $infants[$flight->id] }}" />
                                            <input type="hidden" name="children" value="{{ $children[$flight->id] }}" />
                                            <button type="submit" class="btn btn-outline-primary w-100 text-uppercase disable-styles" style="font-weight: bold; text-transform: uppercase; font-size: 13px; padding: 10px 13px; border-radius: 3px;">Book Now</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    </div>
