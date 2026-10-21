<div>
    <section class="breadcrumb-area bread-bg-flights mb-5">
        <div class="breadcrumb-wrap">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="breadcrumb-content my-5">
                            <div class="section-heading">
                                <div class="left-side-info" style="color:#fff">
                                    <h2 class="sec__title_list mb-1"
                                        style="font-size: 2rem !important; font-weight: 500 !important">My Bookings</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <div class="breadcrumb-content my-5">
                            <div class="section-heading">
                                <div class="left-side-info" style="color:#fff">
                                    <h2 class="sec__title_list mb-1"
                                        style="font-size: 2rem !important; font-weight: 500 !important">
                                        ${{$profile->balance}} </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col">
                    <label class="font-weight-bold">REFERENCE NUMBER</label>
                    <input type="text" class="form-control" placeholder="Reference Number" wire:model.defer="reference" />
                </div>
                <div class="col">
                    <label class="font-weight-bold">BOOKING DATE</label>
                    <input type="date" class="form-control" placeholder="Booking Date" wire:model.defer="booking_date"/>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <label class="font-weight-bold"></label>
                    <button class="btn btn-primary w-100 mb-1 font-weight-bold" wire:click="search">FILTER</button>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-12">
                    <style>
                            .active {
                                border-bottom: 2px solid var(--theme);
                            }
                        </style>
                    <ul class="nav nav-fill nav-underline">
                        <li class="nav-item">
                                <a class="nav-link text-uppercase {{ $filter == 'charter' ? 'active font-weight-bold' : '' }}" aria-current="page" href="#" wire:click.prevent="airline('charter')">Charter</a>
                            </li>
                        @if(config('features.packages') && $site_settings->packages == 1)
                            <li class="nav-item">
                                <a class="nav-link text-uppercase {{ $filter == 'packages' ? 'active font-weight-bold' : '' }}" href="#" wire:click.prevent="airline('packages')">Packages</a>
                            </li>
                        @endif
                        @if(config('features.hotels') && $site_settings->hotels == 1)
                            <li class="nav-item">
                                <a class="nav-link text-uppercase {{ $filter == 'hotels' ? 'active font-weight-bold' : '' }}" href="#" wire:click.prevent="airline('hotels')">Hotels</a>
                            </li>
                        @endif
                        @if(config('features.lowcost') && $site_settings->lowcost == 1 && auth()->user()->agency_id == 1)
                            <li class="nav-item">
                                <a class="nav-link text-uppercase {{ $filter == 'low-cost' ? 'active font-weight-bold' : '' }}" wire:click.prevent="airline('low-cost')">Low Cost</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if(in_array($filter, ['charter', 'packages', 'hotels']))
                        @forelse($bookings as $booking)
                            <div class="search-results-cell px-3 py-3 waves-effect mb-3">
                                <a href="{{ route('frontend.booking.invoice', $booking->id) }}" class="row g-0">
                                    <div class="col-md-10">
                                        <div class="d-flex align-items-center h-100">
                                            <div class="row d-flex w-100 align-items-center justify-content-center">
                                                <div class="col-md-2 text-center justify-content-center">
                                                    @if(($booking?->depFlight?->airline && $booking?->depFlight?->airline?->thumbnail) || $booking->dep_airline_thumbnail)
                                                        <img src="/airlines/{{ $booking->dep_airline_thumbnail ?? $booking->depFlight->airline->thumbnail }}" class="img-fluid" />
                                                    @else
                                                        <img src="https://via.placeholder.com/30/6c757d/FFFFFF/?text={{ config('app.url') }}" class="img-fluid" />
                                                    @endif
                                                    <div  style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;"><small>{{ $booking->dep_airline_name ?? $booking?->depFlight?->airline->name ?? '' }}</small></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                        <label class=" mb-0"></label>
                                                        <h4 class="sec__title_list text-success">{{ $booking->dep_from_code ?? $booking->depFlight?->dep_from_code }}</h4>
                                                        <label class="mb-0"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="theme-search-results-item-flight-section-path">
                                                        <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($booking->dep_time ?? $booking->depFlight->dep_time ?? '')->format('H:i') }}</strong></div>
                                                            <div class="text-success"><strong>{{ \Carbon\Carbon::parse($booking->dep_date ?? $booking->depFlight->dep_date ?? '')->format('D, j M y') }}</strong></div>
                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($booking->dep_arr_time ?? $booking->depFlight->dep_arr_time ?? '')->format('H:i') }}</strong></div>
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
                                                        <h4 class="sec__title_list text-success">{{ $booking->dep_to_code ?? $booking->depFlight->dep_to_code ?? '' }}</h4>
                                                        <label class="label"></label>
                                                    </div>
                                                </div>
                                                @if($booking->type == 'fixed')
                                                    <div class="col-md-2 mt-3 text-center">
                                                        <img src="/airlines/{{ $booking->depFlight->airline->thumbnail ?? '' }}" class="img-fluid" />
                                                        <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;">{{ $booking->depFlight->airline->name ?? $booking->dep_airline_name ?? '' }}</div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="theme-search-results-item-flight-section-meta text-center">
                                                            <label class=""></label>
                                                            <h4 class="sec__title_list text-success">{{ $booking->depFlight->ret_from_code ?? $booking->ret_from_code ??'' }}</h4>
                                                            <label class=""></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="theme-search-results-item-flight-section-path">
                                                            <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                                <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($booking->depFlight->ret_dep_time ?? $booking->ret_dep_time ?? '')->format('H:i') }}</strong></div>
                                                                <div class="text-success"><strong>{{ \Carbon\Carbon::parse($booking->depFlight->ret_dep_date ?? $booking->ret_dep_date ?? '')->format('D, j M y') }}</strong></div>
                                                                <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($booking->depFlight->ret_arr_time ?? $booking->ret_arr_time ?? '')->format('H:i') }}</strong></div>
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
                                                            <h4 class="sec__title_list  text-success">{{ $booking->depFlight->ret_to_code ?? $booking->ret_to_code ?? '' }}</h4>
                                                            <label class="label"></label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($booking->type == 'roundtrip' || $booking->type == 'multi')
                                                    <div class="col-md-2 mt-3 text-center">
                                                        <img src="/airlines/{{ $booking->ret_airline_thumbnail ?? $booking->retFlight->airline->thumbnail ?? '' }}" class="img-fluid" />
                                                        <div style="line-height: 1.2; font-size:12px; margin-top: 5px; opacity: .7;">{{ $booking->ret_airline_name ?? $booking->retFlight->airline->name ?? '' }}</div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="theme-search-results-item-flight-section-meta text-center">
                                                            <label class=""></label>
                                                            <h4 class="sec__title_list text-success">{{ $booking->ret_from_code ?? $booking->retFlight->dep_from_code ?? '' }}</h4>
                                                            <label class=""></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="theme-search-results-item-flight-section-path">
                                                            <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                                <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($booking->ret_time ?? $booking->retFlight->dep_time ?? '')->format('H:i') }}</strong></div>
                                                                <div class="text-success"><strong>{{ \Carbon\Carbon::parse($booking->ret_date ?? $booking->retFlight->dep_date ?? '')->format('D, j M y') }}</strong></div>
                                                                <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($booking->ret_arr_time ?? $booking->retFlight->dep_arr_time)->format('H:i') }}</strong></div>
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
                                                            <h4 class="sec__title_list  text-success">{{ $booking->ret_to_code ?? $booking->retFlight->dep_to_code ?? '' }}</h4>
                                                            <label class="label"></label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex flex-column justify-content-center">
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">REF</div>
                                                <div class="text-center font-weight-bold text-dark">{{ $booking->reference }}</div>
                                            </div>
                                            <hr/>
                                            @if($booking->adults > 0)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">ADULT ({{ $booking->adults }})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $booking->adults_price }}</div>
                                            </div>
                                            @endif
                                            @if($booking->children > 0)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">CHILD ({{ $booking->children }})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $booking->children_price }}</div>
                                            </div>
                                            @endif
                                            @if($booking->infants > 0)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">INFANT ({{ $booking->infants }})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $booking->infants_price }}</div>
                                            </div>
                                            @endif
                                            @if($booking->supplements)
                                                <hr/>
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-dark small">SUPPLEMENTS</div>
                                                    <div class="text-center font-weight-bold text-dark">${{ $booking->supplements }}</div>
                                                </div>
                                            @endif
                                                @if($booking->hotel_id || $booking->hotel_name)
                                                    <hr/>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="text-dark small">HOTEL</div>
                                                        <div class="text-center font-weight-bold text-dark">${{ $booking->hotel_price }}</div>
                                                    </div>
                                                @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="text-center my-5">No Bookings Available</div>
                        @endforelse
                    @elseif($filter == 'low-cost')
                        @forelse($bookings as $booking)
                            <div class="search-results-cell px-3 py-3 waves-effect mb-3">
                                <a target="_blank" href="{{ route('frontend.booking.airline-invoice', $booking->id) }}" class="row g-0">
                                    <div class="col-md-10">
                                        <div class="d-flex align-items-center h-100">
                                            <div class="row d-flex w-100 align-items-center">
                                                <div class="col-md-3">
                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                        <h4 class="sec__title_list text-primary">{{ $booking->dep_from }}</h4>
                                                        <div class="text-dark font-size-14">{{ \Carbon\Carbon::parse($booking->dep_from_date)->format('d/m/Y') }}</div>
                                                        <div class="text-dark font-size-14">{{ \Carbon\Carbon::parse($booking->dep_from_date)->format('H:i') }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="theme-search-results-item-flight-section-path">
                                                        <div class="theme-search-results-item-flight-section-path-fly-time">
                                                            <p>
                                                                <strong>{{ $booking->departure_duration() }}</strong>
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="theme-search-results-item-flight-section-path-line"></div>
                                                        <div
                                                            class="theme-search-results-item-flight-section-path-line-start">
                                                            <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                        </div>
                                                        <div
                                                            class="theme-search-results-item-flight-section-path-line-middle w-100"
                                                            wire:ignore>
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-line-title text-danger text-weight-bold"
                                                                style="font-size: 0.8rem; margin-top:35px;"></div>
                                                        </div>
                                                        <div class="theme-search-results-item-flight-section-path-line-end">
                                                            <i class="la la-plane-arrival theme-search-results-item-flight-section-path-icon"></i>
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                        <h4 class="sec__title_list text-primary">{{ $booking->dep_to }}</h4>
                                                        <div class="label text-dark font-size-14">
                                                            {{ \Carbon\Carbon::parse($booking->dep_to_date)->format('d/m/Y')  }}
                                                        </div>
                                                        <div class="label text-dark font-size-14">
                                                            {{ \Carbon\Carbon::parse($booking->dep_to_date)->format('H:i')  }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($booking->ret_from)
                                                    <div class="col-md-3 mt-4">
                                                        <div
                                                            class="theme-search-results-item-flight-section-meta text-center">
                                                            <h4 class="sec__title_list text-primary">{{ $booking->ret_from }}</h4>
                                                            <div class="text-dark font-size-14">{{ \Carbon\Carbon::parse($booking->ret_from_date)->format('d/m/Y') }}</div>
                                                            <div class="text-dark font-size-14">{{ \Carbon\Carbon::parse($booking->ret_from_date)->format('H:i') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="theme-search-results-item-flight-section-path">
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-fly-time">
                                                                <p>
                                                                    <strong>{{ $booking->return_duration() }}</strong>
                                                                </p>
                                                                <p><strong></strong></p>
                                                            </div>
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-line"></div>
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-line-start">
                                                                <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                                                <div
                                                                    class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                            </div>
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-line-middle w-100"
                                                                wire:ignore>
                                                                <div
                                                                    class="theme-search-results-item-flight-section-path-line-title text-danger text-weight-bold"
                                                                    style="font-size: 0.8rem; margin-top:35px;"></div>
                                                            </div>
                                                            <div
                                                                class="theme-search-results-item-flight-section-path-line-end">
                                                                <i class="la la-plane-arrival theme-search-results-item-flight-section-path-icon"></i>
                                                                <div
                                                                    class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div
                                                            class="theme-search-results-item-flight-section-meta text-center">
                                                            <h4 class="sec__title_list text-primary">{{ $booking->ret_to }}</h4>
                                                            <div class="label text-dark font-size-14">
                                                                {{ \Carbon\Carbon::parse($booking->ret_to_date)->format('d/m/Y')  }}
                                                            </div>
                                                            <div class="label text-dark font-size-14">
                                                                {{ \Carbon\Carbon::parse($booking->ret_to_date)->format('H:i')  }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                                        <div class="mb-2">
                                            @if(strtolower($booking->status) != 'confirmed')
                                                @if($booking->on_hold_expiry_date && \Carbon\Carbon::parse($booking->on_hold_expiry_date)->timezone('Asia/Beirut') > \Carbon\Carbon::now('Asia/Beirut'))
                                                    <div class="font-weight-bold text-center text-primary">ON HOLD</div>
                                                @elseif($booking->on_hold_expiry_date && \Carbon\Carbon::parse($booking->on_hold_expiry_date)->timezone('Asia/Beirut') < \Carbon\Carbon::now('Asia/Beirut'))
                                                    <div class="font-weight-bold text-center text-danger">CANCELLED</div>
                                                @endif
                                                <hr/>
                                            @else
                                                <div class="font-weight-bold text-center text-success">{{ strtoupper($booking->status) }}</div>
                                            @endif
                                            <div class="text-dark text-center">PNR</div>
                                            <div class="text-center font-weight-bold text-dark">{{ json_decode($booking->payload)->booking_num }}</div>
                                        </div>
                                    </div>
                                </a>
                                @if(strtolower($booking->status) != 'confirmed')
                                    <div class="text-center alert alert-danger">@if(json_decode($booking->payload)->on_hold_expiry_date) To avoid cancellation, pay before {{ \Carbon\Carbon::parse(json_decode($booking->payload)->on_hold_expiry_date)->setTimezone('Asia/Beirut')->format('d/m/Y \a\t H:i') }}@endif</div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center my-5">No Bookings Available</div>
                        @endforelse
                    @endif
                </div>
                <div class="col-12">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
