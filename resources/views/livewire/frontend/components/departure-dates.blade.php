<div class="row" wire:init="init">
    @if(count($flightsBefore) || count($flightsAfter))
        <div class="col-md-12">
            <div class="swiper swiper-departure">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->

                    <!-- before flights -->
                    @php $q = $query; $index = 0; @endphp
                    @foreach($flightsBefore as $key => $departureBeforeDate)
                        @php $q['dep_date'] = $departureBeforeDate['dep_date']; @endphp
                        <div data-id="{{ $index }}" class="swiper-slide {{ $departureBeforeDate['dep_date'] == $query['dep_date'] ? 'active-departure-date' : '' }}"><a href="/v2/frontend/search?{{ http_build_query($q) }}#search-results">{{ \Carbon\Carbon::parse($departureBeforeDate['dep_date'])->format('l, d M Y') }}
                                @if($departureBeforeDate['airline'] && $departureBeforeDate['airline']['thumbnail'])
                                    <img src="/airlines/{{ $departureBeforeDate['airline']['thumbnail'] }}" class="img-fluid ml-3" style="height:15px;" />
                                @else
                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF/?text=samadonline.com" class="img-fluid ml-3"  style="height:15px;" />
                                @endif
                                <small>${{ $departureBeforeDate['total_price'] }}</small>
                            </a>
                        </div>
                        @php $index++ @endphp
                    @endforeach

                    @if($flightCurrent)
                        <!-- Current flights -->
                        @php $q['dep_date'] = $flightCurrent['dep_date'] ?? null; @endphp
                        @if($flightCurrent)
                            <div data-id="{{ $index }}" class="swiper-slide active-departure-date">
                                <a href="/v2/frontend/search?{{ http_build_query($q) }}#search-results">{{ \Carbon\Carbon::parse($flightCurrent['dep_date'] ?? null)->format('l, d M, Y') }}
                                    @if($flightCurrent['airline'] && $flightCurrent['airline']['thumbnail'])
                                        <img src="/airlines/{{ $flightCurrent['airline']['thumbnail'] }}" class="img-fluid ml-3" style="height:15px;" />
                                    @else
                                        <img src="https://via.placeholder.com/150/6c757d/FFFFFF/?text=samadonline.com" class="img-fluid ml-3"  style="height:15px;" />
                                    @endif
                                    <small>${{ $flightCurrent['total_price'] }}</small>
                                </a>
                            </div>
                        @endif
                    @endif

                    @php $index++ @endphp

                    <!-- After flights -->
                    @foreach($flightsAfter as $key => $departureAfterDate)
                        @php $q['dep_date'] = $departureAfterDate['dep_date']; @endphp
                        <div data-id="{{ $index }}" class="swiper-slide {{ $departureAfterDate['dep_date'] == $query['dep_date'] ? 'active-departure-date' : '' }}"><a href="/v2/frontend/search?{{ http_build_query($q) }}#search-results">{{ \Carbon\Carbon::parse($departureAfterDate['dep_date'] ?? null)->format('l, d M Y') }}
                                @if($departureAfterDate['airline'] && $departureAfterDate['airline']['thumbnail'])
                                    <img src="/airlines/{{ $departureAfterDate['airline']['thumbnail'] }}" class="img-fluid ml-3" style="height:15px;" />
                                @else
                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF/?text=samadonline.com" class="img-fluid ml-3"  style="height:15px;" />
                                @endif
                                <small>${{ $departureAfterDate['total_price'] }}</small>
                            </a>
                        </div>
                        @php $index++ @endphp
                    @endforeach
                </div>
                <div class="swiper-button swiper-button-prv swiper-button-departure-previous">
                    <svg fill="#ffffff" height="15px" width="15px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 491.1 491.1" xml:space="preserve" transform="rotate(180)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M379.25,282.85l-192.8,192.8c-20.6,20.6-54,20.6-74.6,0s-20.6-54,0-74.6l155.5-155.5l-155.5-155.5 c-20.6-20.6-20.6-54,0-74.6s54-20.6,74.6,0l192.8,192.8C399.85,228.85,399.85,262.25,379.25,282.85z"></path> </g> </g></svg>
                </div>
                <div class="swiper-button swiper-button-nxt swiper-button-departure-next">
                    <svg fill="#ffffff" height="15px" width="15px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 491.1 491.1" xml:space="preserve"><g><path d="M379.25,282.85l-192.8,192.8c-20.6,20.6-54,20.6-74.6,0s-20.6-54,0-74.6l155.5-155.5l-155.5-155.5c-20.6-20.6-20.6-54,0-74.6s54-20.6,74.6,0l192.8,192.8C399.85,228.85,399.85,262.25,379.25,282.85z"/></g></svg>
                </div>
            </div>
        </div>
    @endif
</div>
@pushOnce('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <style>
        .swiper {
            width: 100%;
            transition: all 500ms;
            height: 45px;
            margin-bottom: 5px;
            text-align: center;
        }
        .swiper-button {
            width: 50px;
            background: var(--theme);
        }
        .swiper-button-nxt {
            right: 0;
            left: auto;
            position: absolute;
            top: 0;
            color: white;
            height: 100%;
            z-index: 10;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .swiper-button-prv {
            left: 0;
            right: auto;
            position: absolute;
            top: 0;
            color: white;
            height: 100%;
            z-index: 10;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .swiper-slide {
            padding: 5px;
            width: 100px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e0e2e2;
            font-size: 1rem;
            font-weight: 500;
        }
        .swiper-slide-active {
            background: white;
            color: var(--primary);
            border-top: 3px solid;
        }
    </style>
@endPushOnce
@push('scripts')
    <script>
        window.addEventListener('departure-dates-updated', event => {
            setTimeout(function() {
                let activeSlide = parseInt($('.active-departure-date').data('id'));
                const swiperDeparture = new Swiper('.swiper-departure', {
                    slidesPerView: 4,
                    spaceBetween: 5,
                    initialSlide: activeSlide,
                    direction: 'horizontal',
                    loop: false,
                    centeredSlides: true,
                    cssMode: false,
                    navigation: {
                        nextEl: '.swiper-button-departure-next',
                        prevEl: '.swiper-button-departure-previous',
                    }
                });
                if(activeSlide)
                {
                    swiperDeparture.initialSlide = activeSlide;
                }
            }, 0);
        });
    </script>
@endpush
