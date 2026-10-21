<div>
    <livewire:frontend.components.header.search.container />
    <section class="container mt-5">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="row g-3 append_template justify-content-md-center">
                    @forelse($hotels as $hotel)
                        <div class="card--item col-12 mb-3">
                            <div class="card p-2 rounded-2">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="bg-light rounded-1 overflow-hidden" style="height:150px">
                                            @if(count($hotel['logo']))
                                                @foreach($hotel['logo'] as $logo)
                                                    <img src="{{ $logo['medium'] }}" class="img-fluid" alt="{{ $hotel['name'] }}">
                                                @endforeach
                                            @else
                                                <img src="{{ asset('img/default.jpeg') }}" class="img-fluid" alt="{{ $hotel['name'] }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-body p-1 overflow-hidden" style="line-height:25px">
                                            <h5 class="text-nowrap card-title d-flex align-items-center mt-2 mb-3"><strong>{{ $hotel['name'] }}</strong></h5>
                                            <small class="d-flex justify-content-start gap-1 align-items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                                </svg>
                                                <span class="px-2">Rating {{ (int) $hotel['rating'] }}<span class="">/5</span></span>

                                                @for ($i = 0; $i < $hotel['rating']; $i++)
                                                    <svg class="stars" style="margin-right:3px; fill: #ff8b00; stroke: #ff8b00;" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                    </svg>
                                                @endfor

                                            </small>
                                            <p class="card-text text-capitalize mb-0"><small>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         viewBox="0 0 24 24" fill="none" stroke="#0f294d" stroke-width="2"
                                                         stroke-linecap="square" stroke-linejoin="arcs">
                                                        <path
                                                            d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path>
                                                        <circle cx="12" cy="10" r="3"></circle>
                                                    </svg>
                                                    {{ $hotel['city']['city'] }}, {{ ucfirst(strtolower($hotel['city']['country'])) }}
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-auto">
                                        <div class="h6"><small style="font-size: 14px; font-weight: 300; color: #5b5b5b;">Available Rooms</small></div>
                                        @foreach($hotel['rooms'] as $hotel_room)
                                            <div class="d-flex justify-content-between">
                                                <div class="mb-auto">{{ $hotel_room['room_type']['name'] }}</div>
                                                @if(auth()->user()->type == 'user' || auth()->user()->type == 'admin')
                                                    <div class="font-size-13 text-muted">x {{ $hotel_room['units'] - $hotel_room['bookings_count'] }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end  flex-wrap mb-2 px-3">
                                        <div class="w-100">
                                                <?php
                                                $lowestPrice = null;
                                                foreach($hotel['rooms'] as $hotel_room) {
                                                    foreach($query['rooms'] as $requestedRoom) {
                                                        $price = calculateRoomPrice($hotel_room, $hotel_room['prices'], $startDate, $endDate, ['adult' => $requestedRoom['adult'], 'child' => $requestedRoom['child'], 'infant' => $requestedRoom['infant']]);
                                                        if($lowestPrice === null) {
                                                            $lowestPrice = $price;
                                                        } else if($lowestPrice > $price) {
                                                            $lowestPrice = $price;
                                                        }
                                                    }
                                                }
                                                ?>
                                            <p class="card-text h6 text-center">
                                                <span style="font-size: 16px; font-weight: 400; color: #5b5b5b;">STARTING</span>
                                                <br/><br/>
                                                <strong>${{ number_format($lowestPrice) }}</strong>
                                            </p>
                                        </div>
                                        <div class="w-100">
                                            <form method="post" action="{{ route('frontend.hotels.show', $hotel['id']) }}">
                                                @csrf
                                                @foreach($this->query as $k => $v)
                                                    @if(is_array($v))
                                                        <input type="hidden" name="{{ $k }}" value="{{ json_encode($v) }}"/>
                                                    @else
                                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}"/>
                                                    @endif
                                                @endforeach
                                                <button class="btn btn-outline-primary w-100 text-uppercase disable-styles"  style="font-weight: bold; text-transform: uppercase; font-size: 13px; padding: 10px 13px; border-radius: 3px;" onclick="this.form.submit(); this.disabled=true; this.textContent='Please wait…'; ">
                                                    Select
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">No hotels available</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
