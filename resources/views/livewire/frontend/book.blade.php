<div>
    @if($query['type'] != 'hotel')
        <section class="breadcrumb-area bread-bg-flights mb-5">
            <div class="breadcrumb-wrap">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <div class="breadcrumb-content my-5">
                                <div class="section-heading">
                                    <div class="left-side-info" style="color:#fff">
                                        <h2 class="sec__title_list mb-1" style="font-size: 2rem !important; font-weight: 500 !important">{{ $departure['dep_from']['airport_code'] }} <i class="la la-arrow-right"></i> {{ $departure['dep_to']['airport_code'] }}</h2>
                                        <div>
                                            <p class="mb-1">{{ $departure['dep_date'] }}</p>
                                            <p class="mb-1">{{ \Carbon\Carbon::parse($departure['dep_time'])->format('H:i') }} <i class="la la-arrow-right"></i> {{ \Carbon\Carbon::parse($departure['dep_arr_time'])->format('H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 text-right">
                            <div class="breadcrumb-content my-5">
                                <div class="section-heading">
                                    <div class="left-side-info" style="color:#fff">
                                        @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                                            <h2 class="sec__title_list mb-1" style="font-size: 2rem !important; font-weight: 500 !important">{{ $return['dep_from']['airport_code'] }} <i class="la la-arrow-right"></i> {{ $return['dep_to']['airport_code'] }}</h2>
                                            <div>
                                                <p class="mb-1">{{ $return['dep_date'] }}</p>
                                                <p class="mb-1">{{ $return['dep_date'] }} <i class="la la-arrow-right"></i> {{ $return['arr_date'] ?? '' }}</p>
                                            </div>
                                        @elseif($query['type'] == 'fixed')
                                            <h2 class="sec__title_list mb-1" style="font-size: 2rem !important; font-weight: 500 !important">{{ $departure['dep_to']['airport_code'] }} <i class="la la-arrow-right"></i> {{ $departure['dep_from']['airport_code'] }}</h2>
                                            <div>
                                                <p class="mb-1">{{ $departure['ret_dep_date'] }}</p>
                                                <p class="mb-1">{{ \Carbon\Carbon::parse($departure['ret_dep_time'])->format('H:i') }} <i class="la la-arrow-right"></i> {{ \Carbon\Carbon::parse($departure['ret_arr_time'])->format('H:i') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="container margin_60">
        <div class="cart-section">
            @if(config('app.saas_id') == 'tb2book')
                @if(!empty($site_settings->alert))
                    <h5 class="alert alert-danger mb-5">{!! nl2br($site_settings->alert) !!}</h5>
                @endif
            @endif
            @if($query['type'] != 'hotel')
                <div class="d-flex justify-content-between">
                    <h5 class="text-primary text-uppercase"><strong>Departure</strong></h5>
                </div>
                <hr/>
                <div class="row my-4" style="font-size: 1rem">
                    <div class="col-md text-uppercase">FROM<br/><strong>{{ $departure['dep_from']['city'] }}</strong></div>
                    <div class="col-md text-uppercase">TO<br/><strong>{{ $departure['dep_to']['city'] }}</strong></div>
                    <div class="col-md text-uppercase">DEPARTURE DATE<br/><strong>{{ $departure['dep_date'] }}</strong></div>
                    <div class="col-md text-uppercase">ADULT<br/><strong>{{ $query['adults'] }}</strong></div>
                    <div class="col-md text-uppercase">INFANT<br/><strong>{{ $query['infants'] }}</strong></div>
                    <div class="col-md text-uppercase">CHILD<br/><strong>{{ $query['children'] }}</strong></div>
                </div>
                <div class="row bg-white py-3 border-top">
                    <div class="col-md text-uppercase text-primary" style="font-weight: bold">
                        Airline
                    </div>
                    <div class="col-md text-uppercase text-primary" style="font-weight: bold">
                        Flight Number
                    </div>
                    <div class="col-md text-uppercase text-primary" style="font-weight: bold">
                        Departure Time
                    </div>
                    <div class="col-md text-uppercase text-primary" style="font-weight: bold">
                        Arrival Time
                    </div>
                    <div class="col-md text-uppercase text-primary" style="font-weight: bold">
                        Duration
                    </div>
                </div>
                <div class="row py-3" style="background: rgba(0,0,0,.05)">
                    <div class="col text-uppercase" style="font-weight: bold">{{ $departure['airline']['name'] }}</div>
                    <div class="col text-uppercase" style="font-weight: bold">{{ $departure['dep_flight_number'] }}</div>
                    <div class="col text-uppercase" style="font-weight: bold">{{ \Carbon\Carbon::parse($departure['dep_time'])->format('H:i') }}</div>
                    <div class="col text-uppercase" style="font-weight: bold">{{ \Carbon\Carbon::parse($departure['dep_arr_time'])->format('H:i') }}</div>
                    <div class="col text-uppercase" style="font-weight: bold">
                        {{ calculate_duration($departure['dep_time'], $departure['dep_arr_time']) }} Hours
                    </div>
                </div>
                @if($query['type'] == 'roundtrip' || $query['type'] == 'multi' || $query['type'] == 'fixed')
                    <div class="d-flex justify-content-between">
                        <h5 class="text-primary text-uppercase mt-5"><strong>Return</strong></h5>
                    </div>
                    <hr/>
                    @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                        <div class="row my-4" style="font-size: 1rem">
                            <div class="col text-uppercase">FROM<br/><strong>{{ $return['dep_from']['city'] }}</strong></div>
                            <div class="col text-uppercase">TO<br/><strong>{{ $return['dep_to']['city'] }}</strong></div>
                            <div class="col text-uppercase">DEPARTURE DATE<br/><strong>{{ $return['dep_date'] }}</strong></div>
                            <div class="col text-uppercase">ADULT<br/><strong>{{ $query['adults'] }}</strong></div>
                            <div class="col text-uppercase">INFANT<br/><strong>{{ $query['infants'] }}</strong></div>
                            <div class="col text-uppercase">CHILD<br/><strong>{{ $query['children'] }}</strong></div>
                        </div>
                        <div class="row bg-white py-3 border-top border-bottom">
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Airline
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Flight Number
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Departure Time
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Arrival Time
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Duration
                            </div>
                        </div>
                        <div>
                            <div class="row py-3" style="background: rgba(0,0,0,.05)">
                                <div class="col text-uppercase" style="font-weight: bold">{{ $return['airline']['name'] }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">{{ $return['dep_flight_number'] }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">{{ \Carbon\Carbon::parse($return['dep_time'])->format('H:i') }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">{{ \Carbon\Carbon::parse($return['dep_arr_time'])->format('H:i') }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">
                                    {{ calculate_duration($return['dep_time'], $return['dep_arr_time']) }} Hours
                                </div>
                            </div>
                        </div>
                    @elseif($query['type'] == 'fixed')
                        <div class="row my-4" style="font-size: 1rem">
                            <div class="col text-uppercase">FROM<br/><strong>{{ $departure['ret_from']['city'] }}</strong></div>
                            <div class="col text-uppercase">TO<br/><strong>{{ $departure['ret_to']['city'] }}</strong></div>
                            <div class="col text-uppercase">DEPARTURE DATE<br/><strong>{{ $departure['ret_dep_date'] }}</strong></div>
                            <div class="col text-uppercase">ADULT<br/><strong>{{ $query['adults'] }}</strong></div>
                            <div class="col text-uppercase">INFANT<br/><strong>{{ $query['infants'] }}</strong></div>
                            <div class="col text-uppercase">CHILD<br/><strong>{{ $query['children'] }}</strong></div>
                        </div>
                        <div class="row bg-white py-3 border-top border-bottom">
                            <div class="col text-uppercase text-success" style="font-weight: bold">
                                Airline
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Flight Number
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Departure Time
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Arrival Time
                            </div>
                            <div class="col text-uppercase text-primary" style="font-weight: bold">
                                Duration
                            </div>
                        </div>
                        <div>
                            <div class="row py-3" style="background: rgba(0,0,0,.05)">
                                <div class="col text-uppercase" style="font-weight: bold">{{ $departure['airline']['name'] }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">{{ $departure['ret_flight_number'] }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">{{ \Carbon\Carbon::parse($departure['ret_dep_time'])->format('H:i') }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">{{ \Carbon\Carbon::parse($departure['ret_arr_time'])->format('H:i') }}</div>
                                <div class="col text-uppercase" style="font-weight: bold">
                                    {{ calculate_duration($departure['ret_dep_time'], $departure['ret_arr_time']) }} Hours
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif

            @if(($is_package == 1 || $query['type'] == 'hotel'))
                @if($hotel)
                    <div class="card mt-4">
                    <div class="row">
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
                        <div class="col-md-2 my-auto">
                        </div>
                    </div>
                </div>
                @endif
            @endif

            @if($query['type'] != 'hotel')
                @if(isset($departure['contract']) && !empty($departure['contract']['content']))
                    <div class="mt-5 mb-2">
                        <hr/>
                        <h5 class="text-primary text-uppercase"><strong>Rules</strong></h5>
                        <hr/>
                    </div>
                    {!! $departure['contract']['content'] !!}
               @endif
            @endif
            <h5 class="text-primary text-uppercase mt-5"><strong>Passengers</strong></h5>
            <form action="{{ route('frontend.book.store') }}" method="post" id="form">
                @csrf
                <input type="hidden" name="confirmation" value="{{ $query['confirmation'] ?? null }}">
                <input type="hidden" name="api" value="{{ $query['api'] ?? 0 }}">
                <input type="hidden" name="type" value="{{ $query['type'] }}">
                <input type="hidden" name="dep_cabin" value="{{ $query['dep_cabin'] ?? 'economy' }}">
                <input type="hidden" name="ret_cabin" value="{{ $query['ret_cabin'] ?? 'economy' }}">
                <input type="hidden" name="is_package" value="{{ $is_package ?? 0 }}">
                @if($check_in)
                    <input type="hidden" name="check_in" value="{{ $check_in }}" />
                @endif

                @if($check_out)
                    <input type="hidden" name="check_out" value="{{ $check_out }}" />
                @endif

                @if($is_package == 1 || $query['type'] == 'hotel')
                    <input type="hidden" name="room_selection" value="{{ json_encode($room_selection) }}" />
                    <input type="hidden" name="hotel" value="{{ $hotel['id'] ?? '' }}" />
                    <input type="hidden" name="hotel_id" value="{{ $hotel['id'] ?? '' }}" />
                @endif

                @isset($query['adults'])
                <input type="hidden" name="adults" value="{{ $query['adults'] }}">
                <input type="hidden" name="infants" value="{{ $query['infants'] }}">
                <input type="hidden" data-form-name="children" value="{{ $query['children'] }}">
                @endisset

                @isset($query['departure'])
                <input type="hidden" name="departure" value="{{ $query['departure'] }}">
                <input type="hidden" name="return" value="{{ array_key_exists('return', $query) ? $query['return'] : 0 }}">
                @endisset
                <input type="hidden" name="price" value="{{ $total }}">
                <input type="hidden" name="total" value="{{ $total }}">
                @if(($is_package == 1 || $query['type'] == 'hotel') && $hotel)
                    @php $mainKey = 0; $adultKey = 0; $childKey = 0; $infantKey = 0; $room_number = 0 @endphp
                    @foreach($room_selection as $room)
                        @php ++$room_number @endphp
                        <div class="bg-white mb-3 px-3">
                            <div class="d-flex justify-content-between align-items-center my-4 bg-white py-3 shadow-sm">
                                <div class="h5 mb-0">{{ $room['room_type'] }} <span class="font-size-14 text-muted">{{ numberToWords($room['requested']['adult']) }} adult(s) {{ $room['requested']['child'] > 0 ? ', ' . numberToWords($room['requested']['child']) . ' child(ren)' : '' }} {{ $room['requested']['infant'] > 0 ? ', ' . numberToWords($room['requested']['infant']) . ' infant(s)' : '' }}</span></div>
                            </div>
                            @for($i = 0; $i < $room['requested']['adult']; $i++)
                                <div class="h6 my-4">({{ $i + 1 }}/{{ $room['requested']['adult'] + $room['requested']['child'] + $room['requested']['infant'] }})</div>
                                @isset($adult_prices[$adultKey])
                                    <input type="hidden" name="reservations[{{ $mainKey }}][price]" value="{{$adult_prices[$adultKey]}}" />
                                    <input type="hidden" name="reservations[{{ $mainKey }}][class]" value="{{$adult_classes[$adultKey] ?? ''}}" />
                                @endisset
                                <input type="hidden" name="reservations[{{ $mainKey }}][room_number]" value="{{ $room_number }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][meal_price]" value="{{ $room['meal_price'] ?? null }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][meal_plan]" value="{{ $room['meal_plan'] ?? null }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][room_id]" value="{{$room['room_id']}}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][hotel_id]" value="{{$hotel['id']}}" />
                                @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                                    @isset($adult_prices[$adultKey])
                                        <input type="hidden" name="reservations[{{ $mainKey }}][return_price]" value="{{$return_adult_prices[$adultKey]}}" />
                                        <input type="hidden" name="reservations[{{ $mainKey }}][return_class]" value="{{$return_adult_classes[$adultKey]}}" />
                                    @endisset
                                @endif
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-primary">*</span></label>
                                            <select name="reservations[{{ $mainKey }}][title]" class="form-control form-select w-100" required>
                                                <option value="" selected></option>
                                                <option>Mr.</option>
                                                <option>Miss.</option>
                                                <option>Mrs.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-primary">*</span></label>
                                            <input type="text" class="form-control first_name" name="reservations[{{ $mainKey }}][first_name]" required >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-primary">*</span></label>
                                            <input type="text" class="form-control last_name" name="reservations[{{ $mainKey }}][last_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mobile <span class="text-primary">*</span></label>
                                            <input type="text" name="reservations[{{ $mainKey }}][phone]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .form-control:disabled, .form-control[readonly] {
                                        background-color: white !important;
                                    }
                                </style>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth <span class="text-danger">*</span></label>
                                            <div class="flatpickr relative" data-date="adult">
                                                <input type="text" class="form-control bg-white dob" required data-input name="reservations[{{ $mainKey }}][date_of_birth]" picker="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nationality <span class="text-danger">*</span></label>
                                            <select required name="reservations[{{ $mainKey }}][country]"  class="form-control select2 w-100" data-placeholder="Select">
                                                @if(!isset($attributes['multiple']))
                                                    <option></option>
                                                @endif
                                                @foreach($countries as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @php $mainKey++ @endphp
                                @php $adultKey++ @endphp
                            @endfor
                            @for($k = 0; $k < $room['requested']['infant']; $k++)
                                @php $i++ @endphp
                                @isset($infant_prices[$infantKey])
                                    <input type="hidden" name="reservations[{{ $mainKey }}][price]" value="{{$infant_prices[$infantKey]}}" />
                                    <input type="hidden" name="reservations[{{ $mainKey }}][class]" value="{{$infant_classes[$infantKey] ?? '' }}" />
                                @endisset
                                <input type="hidden" name="reservations[{{ $mainKey }}][room_number]" value="{{ $room_number }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][room_id]" value="{{$room['room_id']}}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][hotel_id]" value="{{$hotel['id']}}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][meal_price]" value="{{ $room['meal_price'] ?? null }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][meal_plan]" value="{{ $room['meal_plan'] ?? null }}" />
                                @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                                    @isset($return_infant_prices[$infantKey])
                                        <input type="hidden" name="reservations[{{ $mainKey }}][return_price]" value="{{$return_infant_prices[$infantKey]}}" />
                                        <input type="hidden" name="reservations[{{ $mainKey }}][return_class]" value="{{$return_infant_classes[$infantKey]}}" />
                                    @endisset
                                @endif
                                <div class="h6 my-4">({{ $i }}/{{ $room['requested']['adult'] + $room['requested']['child'] + $room['requested']['infant'] }})</div>
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <select name="reservations[{{ $mainKey }}][title]" class="form-control form-select w-100" required>
                                                <option selected>Infant.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control first_name" name="reservations[{{ $mainKey }}][first_name]" required >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control last_name" id="last_name" name="reservations[{{ $mainKey }}][last_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mobile <span class="text-danger">*</span></label>
                                            <input type="text" id="phone" name="reservations[{{ $mainKey }}][phone]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .form-control:disabled, .form-control[readonly] {
                                        background-color: white !important;
                                    }
                                </style>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth <span class="text-danger">*</span></label>
                                            <div class="flatpickr relative" data-date="infant">
                                                <input type="text" class="form-control bg-white dob" required data-input name="reservations[{{ $mainKey }}][date_of_birth]" picker="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nationality <span class="text-danger">*</span></label>
                                            <select required name="reservations[{{ $mainKey }}][country]"  class="form-control select2 w-100" data-placeholder="Select">
                                                @if(!isset($attributes['multiple']))
                                                    <option></option>
                                                @endif
                                                @foreach($countries as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @php $mainKey++ @endphp
                                @php $infantKey++ @endphp
                            @endfor
                            @for($l = 0; $l < $room['requested']['child']; $l++)
                                @php $i++ @endphp
                                @isset($child_prices[$l])
                                    <input type="hidden" name="reservations[{{ $mainKey }}][price]" value="{{$child_prices[$l]}}" />
                                    <input type="hidden" name="reservations[{{ $mainKey }}][class]" value="{{$child_classes[$l] ?? '' }}" />
                                @endisset
                                <input type="hidden" name="reservations[{{ $mainKey }}][room_number]" value="{{ $room_number }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][room_id]" value="{{$room['room_id'] ?? '' }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][hotel_id]" value="{{$hotel['id'] ?? '' }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][meal_price]" value="{{ $room['meal_price'] ?? null }}" />
                                <input type="hidden" name="reservations[{{ $mainKey }}][meal_plan]" value="{{ $room['meal_plan'] ?? null }}" />
                                @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                                    @isset($return_child_prices[$l])
                                    <input type="hidden" name="reservations[{{ $mainKey }}][return_price]" value="{{$return_child_prices[$l]}}" />
                                        <input type="hidden" name="reservations[{{ $mainKey }}][return_class]" value="{{$return_child_classes[$l]}}" />
                                    @endisset
                                @endif
                                <div class="h6">({{ $i }}/{{ $room['requested']['adult'] + $room['requested']['child'] + $room['requested']['infant'] }})</div>
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <select name="reservations[{{ $mainKey }}][title]" class="form-control form-select w-100" required>
                                                <option selected>Child.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control first_name" name="reservations[{{ $mainKey }}][first_name]" required >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control last_name" name="reservations[{{ $mainKey }}][last_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mobile <span class="text-danger">*</span></label>
                                            <input type="text" id="phone" name="reservations[{{ $mainKey }}][phone]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .form-control:disabled, .form-control[readonly] {
                                        background-color: white !important;
                                    }
                                </style>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth <span class="text-danger">*</span></label>
                                            <div class="flatpickr relative" data-date="child">
                                                <input type="text" class="form-control bg-white dob" required data-input name="reservations[{{ $mainKey }}][date_of_birth]" picker="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nationality <span class="text-danger">*</span></label>
                                            <select required name="reservations[{{ $mainKey }}][country]"  class="form-control select2 w-100" data-placeholder="Select">
                                                @if(!isset($attributes['multiple']))
                                                    <option></option>
                                                @endif
                                                @foreach($countries as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @php $mainKey++ @endphp
                                @php $childKey++ @endphp
                            @endfor
                        </div>
                    @endforeach
                @else
                    @for($i = 0; $i < $query['adults']; $i++)
                        <div class="h6 my-4">({{ $i + 1 }}/{{ $query['adults'] + $query['infants'] + $query['children'] }})</div>
                        <input type="hidden" name="reservations[{{ $i }}][price]" value="{{$adult_prices[$i]}}" />
                        <input type="hidden" name="reservations[{{ $i }}][class]" value="{{$adult_classes[$i]}}" />
                        @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                            <input type="hidden" name="reservations[{{ $i }}][return_price]" value="{{$return_adult_prices[$i]}}" />
                            <input type="hidden" name="reservations[{{ $i }}][return_class]" value="{{$return_adult_classes[$i]}}" />
                        @endif
                        <div class="passenger">
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Title <span class="text-primary">*</span></label>
                                        <select name="reservations[{{ $i }}][title]" class="form-control form-select w-100" required>
                                            <option value="" selected></option>
                                            <option>Mr.</option>
                                            <option>Miss.</option>
                                            <option>Mrs.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>First Name <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control first_name" name="reservations[{{ $i }}][first_name]" required >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control last_name" name="reservations[{{ $i }}][last_name]" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mobile <span class="text-primary">*</span></label>
                                        <input type="text" name="reservations[{{ $i }}][phone]" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <style>
                                .form-control:disabled, .form-control[readonly] {
                                    background-color: white !important;
                                }
                            </style>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date of Birth <span class="text-danger">*</span></label>
                                        <div class="flatpickr relative" data-date="adult">
                                            <input type="text" class="form-control bg-white dob" required data-input name="reservations[{{ $i }}][date_of_birth]" picker="date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nationality <span class="text-danger">*</span></label>
                                        <select required name="reservations[{{ $i }}][country]"  class="form-control select2 w-100" data-placeholder="Select">
                                            @if(!isset($attributes['multiple']))
                                                <option></option>
                                            @endif
                                            @foreach($countries as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                    @for($k = 0; $k < $query['infants']; $k++)
                        @php $i++ @endphp
                        <input type="hidden" name="reservations[{{ $i }}][price]" value="{{$infant_prices[$k]}}" />
                        <input type="hidden" name="reservations[{{ $i }}][class]" value="{{$infant_classes[$k]}}" />
                        @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                            <input type="hidden" name="reservations[{{ $i }}][return_price]" value="{{$return_infant_prices[$k]}}" />
                            <input type="hidden" name="reservations[{{ $i }}][return_class]" value="{{$return_infant_classes[$k]}}" />
                        @endif
                        <div class="h6 my-4">({{ $i }}/{{ $query['adults'] + $query['infants'] + $query['children'] }})</div>
                            <div class="passenger">
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <select name="reservations[{{ $i }}][title]" class="form-control form-select w-100" required>
                                                <option selected>Infant.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control first_name" name="reservations[{{ $i }}][first_name]" required >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control last_name" id="last_name" name="reservations[{{ $i }}][last_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mobile <span class="text-danger">*</span></label>
                                            <input type="text" id="phone" name="reservations[{{ $i }}][phone]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .form-control:disabled, .form-control[readonly] {
                                        background-color: white !important;
                                    }
                                </style>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth <span class="text-danger">*</span></label>
                                            <div class="flatpickr relative" data-date="infant">
                                                <input type="text" class="form-control bg-white dob" required data-input name="reservations[{{ $i }}][date_of_birth]" picker="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nationality <span class="text-danger">*</span></label>
                                            <select required name="reservations[{{ $i }}][country]"  class="form-control select2 w-100" data-placeholder="Select">
                                                @if(!isset($attributes['multiple']))
                                                    <option></option>
                                                @endif
                                                @foreach($countries as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endfor
                    @for($k = 0; $k < $query['children']; $k++)
                        @php $i++ @endphp
                        <input type="hidden" name="reservations[{{ $i }}][price]" value="{{$child_prices[$k]}}" />
                        <input type="hidden" name="reservations[{{ $i }}][class]" value="{{$child_classes[$k] ?? '' }}" />
                        @if($query['type'] == 'roundtrip' || $query['type'] == 'multi')
                            <input type="hidden" name="reservations[{{ $i }}][return_price]" value="{{$return_child_prices[$k]}}" />
                                <input type="hidden" name="reservations[{{ $i }}][return_class]" value="{{$return_child_classes[$k]}}" />
                        @endif
                        <div class="h6">({{ $i }}/{{ $query['adults'] + $query['infants'] + $query['children'] }})</div>
                            <div class="passenger">
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <select name="reservations[{{ $i }}][title]" class="form-control form-select w-100" required>
                                                <option selected>Child.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control first_name" name="reservations[{{ $i }}][first_name]" required >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control last_name" name="reservations[{{ $i }}][last_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mobile <span class="text-danger">*</span></label>
                                            <input type="text" id="phone" name="reservations[{{ $i }}][phone]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .form-control:disabled, .form-control[readonly] {
                                        background-color: white !important;
                                    }
                                </style>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date of Birth <span class="text-danger">*</span></label>
                                            <div class="flatpickr relative" data-date="child">
                                                <input type="text" class="form-control bg-white dob" required data-input name="reservations[{{ $i }}][date_of_birth]" picker="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nationality <span class="text-danger">*</span></label>
                                            <select required name="reservations[{{ $i }}][country]"  class="form-control select2 w-100" data-placeholder="Select">
                                                @if(!isset($attributes['multiple']))
                                                    <option></option>
                                                @endif
                                                @foreach($countries as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endfor
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <hr/>
                            <h5 class="text-right text-primary">Total: <span>${{ number_format($total) }}</span></h5>
                            <hr/>
                        </div>
                    </div>
                    <div class="col-12">
                        @if(isset($departure['contract']) && !empty($departure['contract']['content']))
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" required type="checkbox" value="1" id="rulesAgree">
                                    <label class="form-check-label" for="rulesAgree">
                                        I have read and agree to the booking rules.
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="bg-white p-3">
                            <button id="confirm" type="submit" class="text-uppercase text-white w-100 border-0 h6 btn-primary p-3 mb-0">Submit Booking<i class="icon-left"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endPushOnce
@push('styles')
    <style>
        .cart-section .totals-table {
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .cart-section ul.totals-table {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .cart-section .totals-table li {
            line-height: 24px;
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        .cart-section .totals-table li:last-child {
            border-bottom: none;
            margin-bottom: 0px;
        }

        .cart-section .totals-table .col {
            position: relative;
            display: block;
            float: left;
            line-height: 24px;
            width: 50%;
            text-transform: uppercase;
        }

        .cart-section .totals-table li .col:last-child {
            text-align: right;
        }

        .cart-section .totals-table .total {
            color: #f7542b;
            font-weight: 600;
        }
    </style>
@endpush
@push('scripts')
    <script>
        @php
            $ageReferenceDate = null;
            if (isset($query['type']) && $query['type'] !== 'hotel') {
                if ($query['type'] === 'oneway') {
                    $ageReferenceDate = $departure['dep_date'] ?? null;
                } elseif ($query['type'] === 'roundtrip' || $query['type'] === 'multi') {
                    $ageReferenceDate = $return['dep_date'] ?? null;
                } elseif ($query['type'] === 'fixed') {
                    $ageReferenceDate = $departure['ret_dep_date'] ?? null;
                }
            }
            $ageReferenceDateIso = $ageReferenceDate
                ? \Carbon\Carbon::parse($ageReferenceDate)->toDateString()
                : null;
        @endphp

        const travelReferenceDate = (() => {
            const iso = @json($ageReferenceDateIso);
            if (!iso) return new Date();
            return new Date(iso + 'T00:00:00');
        })();

        $('.flatpickr').each(function(index, picker) {
            $(picker).flatpickr({
                dateFormat: "{{ config('project.flatpickr_date_format') }}",
                wrap: true,
                maxDate: maxDate($(picker).data('date')),
                minDate: minDate($(picker).data('date')),
                allowInput: true,
                onOpen: (selectedDates, dateStr, instance) => {
                    $(instance.input).prop('readonly', true);
                },
                onClose: function(selectedDates, dateStr, instance) {
                    $(instance.input).prop('readonly', false);
                    $(instance.input).blur();
                },
            })
        })

        let form = document.getElementById('form');
        let submitButton = document.getElementById('confirm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const passengers = document.querySelectorAll('.passenger');
            let valid = true;
            const seen = {};
            passengers.forEach(passenger => {
                const firstName = passenger.querySelector('.first_name').value.trim();
                const lastName = passenger.querySelector('.last_name').value.trim();
                //const birthdate = passenger.querySelector('.dob').value.trim();
                const key = `${firstName}-${lastName}`;
                if (seen[key]) {
                    valid = false;
                } else {
                    seen[key] = true;
                }
            });

            if (!valid) {
                alert('Duplicate passengers detected');
            } else {
                submitButton.setAttribute('disabled', 'disabled');
                submitButton.textContent = 'Please wait...';
                this.submit();
            }
        }, false);

        function minDate(title)
        {
            const ref = travelReferenceDate;
            if(title === 'adult') {
                // Adult: oldest allowed = 100 years before travel date
                return new Date(ref.getFullYear() - 100, ref.getMonth(), ref.getDate());
            } else if (title === 'infant') {
                // Infant: oldest allowed = day before 2nd birthday (so they're still under 2)
                const d = new Date(ref.getFullYear() - 2, ref.getMonth(), ref.getDate());
                d.setDate(d.getDate() + 1);
                return d;
            } else if (title === 'child') {
                // Child: oldest allowed = day before 12th birthday (so they're still under 12)
                const d = new Date(ref.getFullYear() - 12, ref.getMonth(), ref.getDate());
                d.setDate(d.getDate() + 1);
                return d;
            }
        }

        function maxDate(title)
        {
            const ref = travelReferenceDate;
            if(title === 'adult') {
                // Adult: youngest allowed = exactly 12 years before travel (12th birthday = adult)
                return new Date(ref.getFullYear() - 12, ref.getMonth(), ref.getDate());
            } else if (title === 'infant') {
                // Infant: youngest allowed = travel date (can be newborn)
                return new Date(ref.getFullYear(), ref.getMonth(), ref.getDate());
            } else if (title === 'child') {
                // Child: youngest allowed = exactly 2 years before travel (2nd birthday = child)
                return new Date(ref.getFullYear() - 2, ref.getMonth(), ref.getDate());
            }
        }
    </script>
@endpush

