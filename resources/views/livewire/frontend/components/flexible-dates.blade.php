<div class="row" wire:init="init">
    @if(count($flightsBefore) || count($flightsAfter) || count($flightCurrent))
        <div class="col-md-12">
            <div class="swiper swiper-flexible">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    @php $q = $query; $index = 0; @endphp
                    @foreach($flightsBefore as $key => $flightBeforeDate)
                        @php $q['dep_date'] = Carbon\Carbon::parse($flightBeforeDate['dep_date'])->format('d-m-Y'); @endphp
                        <div data-id="{{$index}}" class="swiper-slide {{ $flightBeforeDate['dep_date'] == $query['dep_date'] ? 'active-flexible-date' : '' }}">
                            <a href="/v2/frontend/search?{{ http_build_query($q) }}#search-results">{{ \Carbon\Carbon::parse($flightBeforeDate['dep_date'])->format('l, d M y') }}
                                @if($flightBeforeDate['airline'] && $flightBeforeDate['airline']['logo'])
                                    <img src="/airlines/{{ $flightBeforeDate['airline']['logo'] }}" class="img-fluid ml-3" style="height:15px;" />
                                @else
                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF/?text=samadonline.com" class="img-fluid ml-3"  style="height:15px;" />
                                @endif
                                <small>${{ $flightBeforeDate['price'] }}</small>
                            </a>
                        </div>
                        @php $index++; @endphp
                    @endforeach

                    @if(count($flightCurrent))
                        @php $q['dep_date'] = Carbon\Carbon::parse($flightCurrent[0]['dep_date'])->format('d-m-Y'); @endphp
                        <div data-id="{{ $index }}" class="swiper-slide active-flexible-date">
                            <a href="/v2/frontend/search?{{ http_build_query($q) }}#search-results">{{ \Carbon\Carbon::parse($flightCurrent[0]['dep_date'])->format('l, d M y') }}
                                @if($flightCurrent[0]['airline'] && $flightCurrent[0]['airline']['logo'])
                                    <img src="/airlines/{{ $flightCurrent[0]['airline']['logo'] }}" class="img-fluid ml-3" style="height:15px;" />
                                @else
                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF/?text=na" class="img-fluid ml-3"  style="height:15px;" />
                                @endif
                                <small>${{ $flightCurrent[0]['price'] }}</small>
                            </a>
                        </div>
                        @php $index++ @endphp
                    @endif

                    @foreach($flightsAfter as $key => $flightAfterDate)
                        @php $q['dep_date'] = Carbon\Carbon::parse($flightAfterDate['dep_date'])->format('d-m-Y'); @endphp
                        <div data-id="{{$index}}" class="swiper-slide {{ $flightAfterDate['dep_date'] == $query['dep_date'] ? 'active-flexible-date' : '' }}">
                            <a href="/v2/frontend/search?{{ http_build_query($q) }}#search-results">{{ \Carbon\Carbon::parse($flightAfterDate['dep_date'])->format('l, d M y') }}
                                @if($flightAfterDate['airline'] && $flightAfterDate['airline']['logo'])
                                    <img src="/airlines/{{ $flightAfterDate['airline']['logo'] }}" class="img-fluid ml-3" style="height:15px;" />
                                @else
                                    <img src="https://via.placeholder.com/150/6c757d/FFFFFF/?text=samadonline.com" class="img-fluid ml-3"  style="height:15px;" />
                                @endif
                                <small>${{ $flightAfterDate['price'] }}</small>
                            </a>
                        </div>
                        @php $index++; @endphp
                    @endforeach
                </div>
                <div class="swiper-button swiper-button-prv swiper-button-flexible-previous">
                    <svg fill="#ffffff" height="15px" width="15px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 491.1 491.1" xml:space="preserve" transform="rotate(180)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M379.25,282.85l-192.8,192.8c-20.6,20.6-54,20.6-74.6,0s-20.6-54,0-74.6l155.5-155.5l-155.5-155.5 c-20.6-20.6-20.6-54,0-74.6s54-20.6,74.6,0l192.8,192.8C399.85,228.85,399.85,262.25,379.25,282.85z"></path> </g> </g></svg>
                </div>
                <div class="swiper-button swiper-button-nxt swiper-button-flexible-next">
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
    window.addEventListener('flexible-dates-updated', event => {
        setTimeout(function(){
            let activeSlide = parseInt($('.active-flexible-date').data('id'));
            const swiperFlexible = new Swiper('.swiper-flexible', {
                slidesPerView: 4,
                spaceBetween: 5,
                initialSlide: activeSlide,
                direction: 'horizontal',
                loop: false,
                centeredSlides: true,
                cssMode: false,
                navigation: {
                    nextEl: '.swiper-button-flexible-next',
                    prevEl: '.swiper-button-flexible-previous',
                }
            });
            swiperFlexible.initialSlide = activeSlide;
            $('.swiper-flexible').css('opacity', 1);
        }, 0);
    });
</script>
@endpush
