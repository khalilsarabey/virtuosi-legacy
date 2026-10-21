<div wire:init="populate">
    @include('components.lowcost-searchbar')
    <div id="search-results" class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex">
                    <svg fill="{{ $site_settings->primary_color }}" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 241.016 241.016" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M210.818,96.393l-49.202,1.644L108.753,0H83.279c-2.791,0-5.052,2.259-5.052,5.055l27.504,94.843l-50.097,2.037
                                    c-4.312,0.004-8.372,0.732-11.97,1.997l-18.925-32.14L8.857,71.788c-2.105,0.004-3.811,1.708-3.811,3.814l13.848,42.361v5.09
                                    L5.047,165.414c-0.002,2.105,1.704,3.814,3.809,3.814l15.885,0.004l19.257-32.713c3.514,1.197,7.455,1.885,11.637,1.885
                                    l50.288,2.046l-27.698,95.516c0,2.795,2.259,5.05,5.052,5.05h25.474l53.227-98.696l48.84,1.631
                                    c13.894,0,25.152-10.652,25.152-23.779C235.971,107.041,224.713,96.393,210.818,96.393z" />
                            </g>
                        </g>
                    </svg>
                    <h5 class="text-primary mb-3 ml-3">DEPARTURE FLIGHT</h5>
                </div>
                @if($loading)
                <div class="spinner-border spinner-border-sm d-flex justify-content-center" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                @else
                <div>
                    @forelse($departures as $k => $departure)
                        @if($departure['price'] > 0)
                            <div x-data="{ open: false }" class="search-results-cell px-3 py-3 waves-effect mb-3">
                                <div class="row g-0">
                                    <div x-on:click="open = ! open" class="col-md-10">
                                        <div class="d-flex align-items-center h-100">
                                            <div class="row d-flex w-100 align-items-center justify-content-center">
                                                <div class="col-md-2 text-center justify-content-center">
                                                    <img src="{{ asset('airlines/' . $departure['airline'] . '.png' ?? '') }}" class="img-fluid" />
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="theme-search-results-item-flight-section-meta text-center">
                                                        <label class=" mb-0"></label>
                                                        <h4 class="sec__title_list text-success">{{ $departure['origin'] }}</h4>
                                                        <label class="mb-0"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="theme-search-results-item-flight-section-path">
                                                        <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($departure['departure_time'])->format('H:i') }}</strong></div>
                                                            <div class="text-success"><strong>{{ \Carbon\Carbon::parse($departure['departure_time'])->format('d/m/Y') }}</strong></div>
                                                            <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($departure['arrival_time'])->format('H:i') }}</strong></div>
                                                        </div>
                                                        <div class="theme-search-results-item-flight-section-path-line"></div>
                                                        <div class="theme-search-results-item-flight-section-path-line-start">

                                                            <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                                            <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                        </div>
                                                        <div class="theme-search-results-item-flight-section-path-line-middle w-100" wire:ignore>
                                                            <div class="theme-search-results-item-flight-section-path-line-title text-secondary text-weight-bold" style="font-size: 0.8rem; margin-top:35px;">
                                                                <strong>{{ $departure['stops'] > 0 ? $departure['stops'] . ' Stops' : 'Direct' }}</strong>
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
                                                        <label class="label"></label>
                                                        <h4 class="sec__title_list text-success">{{ $departure['destination'] }}</h4>
                                                        <label class="label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col text-right d-flex align-items-center justify-content-end">
                                        <button @if(!empty($selected) && isset($selected['departure']['key']) && $selected['departure']['key'] !==$k) disabled @endif type="button" wire:click="selectDeparture({{$k}})" class="btn btn-primary text-uppercase font-weight-bold">
                                            @if(!empty($selected) && isset($selected['departure']['key']) && $selected['departure']['key'] == $k)
                                            Selected
                                            @else
                                            From ${{ $departure['price'] }}
                                            @endif
                                        </button>
                                    </div>
                                </div>
                                <div x-show="open" class="row">
                                    @foreach($departure['stop_details'] as $stop)
                                    <div class="col-12">
                                        <hr />
                                    </div>
                                    <div class="col-2"></div>
                                    <div class="col-8">
                                        <h6 class="sec__title_list text-success">{{ $stop['origin'] }} - {{ $stop['destination'] }}</h6>
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <div style="font-size: 14px; text-transform: uppercase; font-weight: bold;">Departure:</div>
                                            </div>
                                            <div class="col-6 font-weight-bold text-primary">{{ \Carbon\Carbon::parse($stop['departure_time'])->format('d-m-Y') }} at {{ \Carbon\Carbon::parse($stop['departure_time'])->format('H:i') }}</div>
                                            <div class="col-6">
                                                <div style="font-size: 14px; text-transform: uppercase; font-weight: bold;">Arrival:</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="font-weight-bold text-primary">
                                                    {{ \Carbon\Carbon::parse($stop['arrival_time'])->format('d-m-Y') }} at {{ \Carbon\Carbon::parse($stop['arrival_time'])->format('H:i') }}
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div style="font-size: 14px; text-transform: uppercase; font-weight: bold;">Flight Number:</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="font-weight-bold text-primary">
                                                    {{ $stop['flight_number'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                    <div class="mt-4">No flights available</div>
                    @endforelse
                </div>
                @endif

                @if($type == 'roundtrip')
                <div class="d-flex mt-5 pt-5">
                    <svg fill="{{ $site_settings->primary_color }}" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 472.615 472.615" xml:space="preserve" transform="matrix(-1, 0, 0, 1, 0, 0)">
                        <g id="SVGRepo_bgCarrier" stroke-width="0" />
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <g>
                                    <path d="M399.3,179.692c-29.984,0.057-59.984,0.517-89.994,1.313L182.565,0h-66.816l85.4,185.271 c-35.293,1.789-70.583,3.921-105.857,6.347L42.16,98.18H6.482l34.143,97.387c-17.54,0.637-31.654,18.562-31.654,40.719 c0,22.164,14.121,40.092,31.668,40.721L6.482,374.432H42.16l53.151-93.471c35.269,2.431,70.554,4.573,105.841,6.37 l-85.404,185.284h66.816l126.726-180.983c30.015,0.8,60.02,1.266,90.009,1.324c36.339-0.307,66.834-26.385,66.834-56.517 c0-0.077,0-0.154,0-0.154C466.134,206.077,435.733,179.923,399.3,179.692z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                    <h5 class="text-primary mb-3 ml-3">RETURN FLIGHT</h5>
                </div>
                @if($loading)
                <div class="spinner-border spinner-border-sm d-flex justify-content-center" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                @else
                <div>
                    @forelse($returns as $key => $return)
                        @if($return['price'] > 0)
                            <div x-data="{ open: false }" class="search-results-cell px-3 py-3 waves-effect mb-3">
                        <div class="row g-0">
                            <div x-on:click="open = ! open" class="col-md-10">
                                <div class="d-flex align-items-center h-100">
                                    <div class="row d-flex w-100 align-items-center justify-content-center">
                                        <div class="col-md-2 text-center justify-content-center">
                                            <img src="{{ asset('airlines/' . $return['airline'] . '.png' ?? '') }}" class="img-fluid" />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="theme-search-results-item-flight-section-meta text-center">
                                                <label class=" mb-0"></label>
                                                <h4 class="sec__title_list text-success">{{ $return['origin'] }}</h4>
                                                <label class="mb-0"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="theme-search-results-item-flight-section-path">
                                                <div class="theme-search-results-item-flight-section-path-fly-time d-flex justify-content-between px-3">
                                                    <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($return['departure_time'])->format('H:i') }}</strong></div>
                                                    <div class="text-success"><strong>{{ \Carbon\Carbon::parse($return['departure_time'])->format('d/m/Y') }}</strong></div>
                                                    <div class="text-primary"><strong>{{ \Carbon\Carbon::parse($return['arrival_time'])->format('H:i') }}</strong></div>
                                                </div>
                                                <div class="theme-search-results-item-flight-section-path-line"></div>
                                                <div class="theme-search-results-item-flight-section-path-line-start">

                                                    <i class="la la-plane-departure theme-search-results-item-flight-section-path-icon"></i>
                                                    <div class="theme-search-results-item-flight-section-path-line-dot"></div>
                                                </div>
                                                <div class="theme-search-results-item-flight-section-path-line-middle w-100" wire:ignore>
                                                    <div class="theme-search-results-item-flight-section-path-line-title text-secondary text-weight-bold" style="font-size: 0.8rem; margin-top:35px;">
                                                        <strong>{{ $return['stops'] > 0 ? $return['stops'] . ' Stops' : 'Direct' }}</strong>
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
                                                <h4 class="sec__title_list text-success">{{ $return['destination'] }}</h4>
                                                <label class="label"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col text-right d-flex align-items-center justify-content-end">
                                <button @if(empty($selected) || isset($selected['departure']['airline']) && $selected['departure']['airline'] !==$return['airline']) disabled @endif type="button" wire:click="selectReturn({{$key}})" class="btn btn-primary text-uppercase font-weight-bold">
                                    @if(isset($selected['return']['key']) && $selected['return']['key'] === $key)
                                    @if(empty($selected)) From ${{ $return['price'] }} @else Selected @endif
                                    @else
                                    From ${{ $return['price'] }}
                                    @endif
                                </button>
                            </div>
                        </div>
                        <div x-show="open" class="row">
                            @foreach($return['stop_details'] as $stop)
                                <div class="col-12">
                                    <hr />
                                </div>
                                <div class="col-2"></div>
                                <div class="col-8">
                                    <h6 class="sec__title_list text-success">{{ $stop['origin'] }} - {{ $stop['destination'] }}</h6>
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <div style="font-size: 14px; text-transform: uppercase; font-weight: bold;">Departure:</div>
                                        </div>
                                        <div class="col-6 font-weight-bold text-primary">{{ \Carbon\Carbon::parse($stop['departure_time'])->format('d-m-Y') }} at {{ \Carbon\Carbon::parse($stop['departure_time'])->format('H:i') }}</div>
                                        <div class="col-6">
                                            <div style="font-size: 14px; text-transform: uppercase; font-weight: bold;">Arrival:</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="font-weight-bold text-primary">
                                                {{ \Carbon\Carbon::parse($stop['arrival_time'])->format('d-m-Y') }} at {{ \Carbon\Carbon::parse($stop['arrival_time'])->format('H:i') }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div style="font-size: 14px; text-transform: uppercase; font-weight: bold;">Flight Number:</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="font-weight-bold text-primary">
                                                {{ $stop['flight_number'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                        @endif
                    @empty
                    <div class="mt-4">No flights available</div>
                    @endforelse
                </div>
                @endif
                @endif

                @php $canProceed = false @endphp
                @if(count($this->selected) > 0)
                @if($type == 'roundtrip' && $this->selected['departure'] != null && $this->selected['return'] != null)
                @php $canProceed = true @endphp
                @elseif($type == 'oneway' && $this->selected['departure'] != null)
                @php $canProceed = true @endphp
                @endif
                @endif

                @if($canProceed)
                <form method="post" action="{{ route('frontend.low-cost.booking') }}" class="d-flex justify-content-end">
                    @csrf
                    @isset($selected['departure'])
                    <input type="hidden" name="departure" value="{{ json_encode($selected['departure']) }}" />
                    <input type="hidden" name="airline" value="{{ json_encode($selected['departure']['airline']) }}" />
                    @endisset
                    @isset($selected['return'])
                    <input type="hidden" name="return" value="{{ json_encode($selected['return']) }}" />
                    @endisset
                    <input type="hidden" name="adult" value="{{ $adult }}" />
                    <input type="hidden" name="child" value="{{ $child }}" />
                    <input type="hidden" name="infant" value="{{ $infant }}" />
                    <button type="submit" class="btn btn-lg btn-primary font-weight-bold text-uppercase">Proceed to booking</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@pushOnce('scripts')
<style>
    #search-results {
        margin-top: 100px;
    }
    .btn:focus {
        box-shadow: none !important;
        color: white !important;
    }
</style>
<script>
</script>
@endPushOnce
