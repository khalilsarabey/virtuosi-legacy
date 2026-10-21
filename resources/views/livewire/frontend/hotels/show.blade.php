<div>
    <style>
        h3 {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .fancybox__content {
            height: 500px !important;
            width: auto !important
        }

        .carasoul img {
            border-radius: 5px;
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer
        }

        .carasoul {
            position: relative;
            list-style-type: none;
            height: 282px;
            overflow: hidden
        }

        .carasoul > .carasoul--img {
            display: block;
            width: 100%;
            height: 100%;
            float: left
        }

        .carasoul > :first-child {
            width: 40%;
            height: 100%
        }

        .carasoul > .carasoul--img:not(.carasoul>:first-child) {
            width: calc(calc(100% - 40%) / 3);
            height: 50%;
            padding-bottom: 4px;
            padding-left: 4px
        }

        .carasoul--img:nth-child(6), .carasoul--img:nth-child(7), .carasoul > .carasoul--img:nth-child(5) {
            padding-bottom: 0 !important
        }

        .carasoul--icon {
            position: absolute;
            cursor: pointer
        }

        .love {
            top: 10px;
            left: 10px
        }

        .imgvalue {
            display: none;
            justify-content: center;
            align-items: center;
            bottom: 20px;
            left: 10px;
            background-color: rgba(0, 0, 0, .7);
            border-radius: 5px;
            width: 50px;
            font-weight: 600
        }

        .carasoul--btn {
            display: none;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 50%;
            background-color: rgba(0, 0, 0, .4);
            border: 2px solid #fff;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            transform: translate(0, -50%);
            cursor: pointer
        }

        .btn--left {
            left: 0
        }

        .btn--right {
            right: 0
        }

        .carasoul--last {
            position: relative
        }

        .carasoul--last .overlay {
            position: absolute;
            top: 0;
            right: 0;
            background-color: rgba(0, 0, 0, .5);
            height: 100%;
            width: calc(100% - 4px)
        }

        @media screen and (max-width: 768px) {
            img {
                border-radius: 0
            }

            .carasoul > .carasoul--img:not(.carasoul>:first-child) {
                display: none
            }

            .carasoul > .carasoul--img {
                width: 100% !important;
                height: 100% !important;
                padding: 0 !important
            }

            .carasoul--btn {
                display: flex !important
            }

            .imgvalue {
                display: flex !important
            }
        }

        .slick-next, .slick-prev {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute !important;
            top: 43% !important;
            background-color: #fff !important;
            border: .5px solid rgba(180, 180, 180, .4) !important;
            border-radius: 50%;
            height: 60px !important;
            width: 60px !important;
            color: transparent;
            transform: translateY(-50%);
            transition: background-color .3s !important;
            box-shadow: rgb(0 0 0/10%) 0 50px 100px, rgb(0 0 0/14%) 0 5px 50px !important;
            z-index: 100
        }

        .slick-next:hover, .slick-prev:hover {
            background-color: #f4f4f4 !important
        }

        .slick-prev {
            left: -20px
        }

        .slick-next {
            right: -20px
        }

        .slick-next::before, .slick-prev::before {
            content: '';
            position: absolute !important;
            border: 2px solid #000;
            width: 14px !important;
            height: 14px !important;
            color: red;
            opacity: 1 !important
        }

        .slick-prev::before {
            border-right: none !important;
            border-bottom: none !important;
            margin-inline-start: 4px;
            transform: rotate(-45deg)
        }

        .slick-next::before {
            border-bottom: none !important;
            border-left: none !important;
            margin-inline-end: 4px;
            transform: rotate(45deg)
        }

        .booking-detail-form {
            background-color: var(--theme-bg);
            color: #fff !important
        }

        .form-title-wrap {
            padding: 20px;
            border-bottom: 1px solid rgba(128, 137, 150, .1);
            background: rgb(255 255 255/11%)
        }

        .form-content {
            padding: 20px
        }
    </style>
    <div class="container mt-5">
        <div class="bg-white mb-3">
            <div class="px-3 py-3">
                <div class="fw-bold mb-0 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="h4">{{ $hotel['name'] }}</div>
                        <div class="d-block gap-2">
                        <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"></path>
                        </svg>
                        </span>
                            <span
                                class="">{{ $hotel['address'] }}, {{ $hotel['city']['city'] }}, {{ $hotel['city']['country'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="my-auto">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="la la-star @if ($hotel['rating'] >= $i) text-primary @endif"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white p-3 mt-3">
            <div class="row">
                <div class="col-12">
                    <ul class="carasoul px-0 m-0">
                        @foreach($hotel['gallery'] as $item)
                            <li data-src="{{ $item['large'] }}" data-fancybox="gallery"
                                class="carasoul--img">
                                <img src="{{ $item['medium'] }}" alt="">
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white p-3 mt-3">
            {!! $hotel['description'] !!}
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="px-3 py-4">
                    <div class="mb-0 d-flex justify-content-between align-items-center">
                        <div class="h5">
                            <strong>Choose Your Rooms</strong>
                            <div class="d-flex justify-content-between">
                                <div class="font-size-14 mt-2 text-muted">Check In</div>
                                <div class="font-size-14 mt-2 text-muted">{{ $startDate }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="font-size-14 mt-2 text-muted">Check Out</div>
                                <div class="font-size-14 mt-2 text-muted">{{ $endDate }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach($rooms as $k => $room)
            <div class="pb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="bg-light p-3 mb-0 mt-2 d-flex justify-content-between">
                            <div>
                                Adult: <strong>{{ $room['requested']['adult'] }}</strong>
                                Child: <strong>{{ $room['requested']['child'] }}</strong>
                                Infant: <strong>{{ $room['requested']['infant'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-4 mt-3">
                    @foreach($room['results'] as $key => $result)
                        <div class="col mb-3"
                             x-data="{
                                id: {{ $k }},
                                key: '{{ $key }}',
                                checkSelection() {
                                    const select = document.getElementById(`select-${this.key}-${this.id}`);
                                    const error = document.getElementById(`plan-error-${this.key}-${this.id}`);
                                    if (select.value === '') {
                                        error.classList.remove('d-none');
                                    } else {
                                        error.classList.add('d-none');
                                        // Trigger Livewire action with id and key as parameters
                                        @this.call('select', this.id, this.key);
                                    }
                                }}"
                        >
                            <div class="card h-100">
                                @if(count($result['gallery']))
                                    <img data-fancybox="gallery" data-src="{{ $result['gallery'][0]['medium'] }}" class="w-100 card-img-top p-3" src="{{ $result['gallery'][0]['medium'] }}" alt="{{ $hotel['name'] }}" style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('img/default.jpeg') }}" class="card-img-top p-3" style="opacity: .5" alt="{{ $hotel['name'] }}">
                                @endif
                                <div class="card-body pt-0">
                                    <h6 class="card-title">{{ $result['room_type']['name'] }}</h6>
                                    @foreach($result['features'] as $feature)
                                        <div>
                                            <span class="text-primary la la-check-circle"></span>
                                            <span>{{ $feature['title'] }} </span>
                                        </div>
                                    @endforeach
                                    @if(config('app.hide_remaining_from_agents') && (auth()->user()->type == 'agency' || auth()->user()->type == 'white')))
                                    <div>
                                        <span class="text-primary la la-hotel"></span>
                                        <span class="text-muted font-size-13">{{ $result['units'] - $result['bookings_count'] }} Room{{ $result['units'] - $result['bookings_count'] > 1 ? 's' : '' }} Available</span>
                                    </div>
                                    @endif
                                    <div>
                                        <hr/>
                                        <label id="label-{{ $key }}-{{ $k }}">Select a Plan</label>
                                        <div id="plan-error-{{ $key }}-{{ $k }}" class="text-danger d-none">Plan is required</div>
                                        <select id="select-{{ $key }}-{{ $k }}" name="meal_plan" wire:model.live="rooms.{{ $k }}.results.{{$key}}.meal_plan" class="form-control">
                                            <option selected></option>
                                            @foreach($this->mealPlans as $a => $b)
                                                <option value="{{ $a }}">{{ strtoupper($a) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="card-footer bg-white">
                                    <div>
                                        @php
                                            $meal_price = 0;
                                            if(isset($result['meal_price']) && $result['meal_price'] > 0) {
                                                $child = (int) $room['requested']['child'] > 0 ? (int) $room['requested']['child'] - 1 : 0;
                                                $child_price = ($result['meal_price'] * $child) / 2;
                                                $adult_price = $result['meal_price'] * $room['requested']['adult'];
                                                $meal_price =  $adult_price + $child_price;
                                                $meal_price = $meal_price * $duration;
                                            }
                                        @endphp
                                        <div class="text-right text-primary h5 font-weight-bold">${{ number_format($meal_price + calculateRoomPrice($result, $result['prices'], $startDate, $endDate, ['adult' => $room['requested']['adult'], 'child' => $room['requested']['child'], 'infant', $room['requested']['infant']])) }}</div>
                                        @if($result['units'] - $result['bookings_count'] <= 0 && !(array_key_exists($k, $selection) && $selection[$k]['room_id'] == $result['id']))
                                            <div class="fw-bold w-100 mt-2 h-100"><span class="text-danger">No Rooms Available</span></div>
                                        @else
                                            <button {{ (array_key_exists($k, $selection) && $selection[$k]['room_id'] == $result['id']) || $result['units'] - $result['bookings_count'] <= 0 ? 'disabled' : '' }}
                                                    id="button-{{ $key }}-{{ $k }}"
                                                    type="button"
                                                    data-id="{{ $k }}"
                                                    data-key="{{ $key }}"
                                                    @click="checkSelection"
                                                    x-data
                                                    class="btn btn-block btn-outline-primary fw-bold w-100 mt-2 h-100 waves-effect select-button">
                                                @if(array_key_exists($k, $selection) && $selection[$k]['room_id'] == $result['id'])
                                                    Selected
                                                @else
                                                    Select
                                                @endif
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        @if(count($selection) == count($rooms))
            <div class="bg-white p-3">
                <div class="row">
                    <div class="col-12 text-right">
                        <form id="form" method="post" action="{{ route('frontend.book') }}">
                            @csrf
                            <input type="hidden" name="room_selection" value="{{ json_encode($selection) }}" />
                            <input type="hidden" name="type" value="{{ $type }}" />
                            <input type="hidden" name="confirmation" value="{{ $hotel['confirmation'] }}" />
                            <input type="hidden" name="hotel_id" value="{{ $hotel['id'] }}" />
                            <input type="hidden" name="startDate" value="{{ $startDate }}" />
                            <input type="hidden" name="endDate" value="{{ $endDate }}" />
                            @foreach($query as $k => $v)
                                @if(is_string($v))
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                                @else
                                    <input type="hidden" name="{{ $k }}" value="{{ json_encode($v) }}" />
                                @endif
                            @endforeach
                            <button id="confirm" type="submit" class="text-uppercase text-white w-100 border-0 h6 btn-primary p-3 mb-0">Confirm Selection & Proceed</button>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                let form = document.getElementById('form');
                let submitButton = document.getElementById('confirm');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitButton.setAttribute('disabled', 'disabled');
                    submitButton.textContent = 'Please wait...';
                    this.submit();
                }, false);
            </script>
        @endif
    </div>
</div>
@push('scripts')

@endpush
