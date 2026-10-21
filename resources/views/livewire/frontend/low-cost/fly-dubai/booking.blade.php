@if ($error)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <div>An error has occurred:</div>
                    <div>{{$error}}</div>
                </div>
            </div>
        </div>
    </div>
@else
    <div>
        <style>
            .btn:active,
            .btn:visited,
            .btn:focus {
                color: white;
            !important;
                border-color: #000000;
            !important;
                box-shadow: none;
            !important;
            }

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
        <style>
            .btn:focus {
                outline: none;
            }
        </style>
        <div>
            <section class="breadcrumb-area bread-bg-flights mb-5">
                <div class="breadcrumb-wrap">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <div class="breadcrumb-content my-5">
                                    <div class="section-heading">
                                        <div class="left-side-info" style="color:#fff">
                                            <h2 class="sec__title_list mb-1"
                                                style="font-size: 2rem !important; font-weight: 500 !important">{{$departure_from}}
                                                <i class="la la-arrow-right"></i> {{$departure_to}}
                                            </h2>
                                            <div>
                                                <p class="mb-1">{{ \Carbon\Carbon::parse($departure_from_date)->format('d/m/Y') }}</p>
                                                <p class="mb-1">{{ \Carbon\Carbon::parse($departure_from_date)->format('H:i') }}
                                                    <i class="la la-arrow-right"></i> {{ \Carbon\Carbon::parse($departure_to_date)->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 text-right">
                                <div class="breadcrumb-content my-5">
                                    <div class="section-heading">
                                        <div class="left-side-info" style="color:#fff">
                                            @if($type == 'roundtrip')
                                                <h2 class="sec__title_list mb-1"
                                                    style="font-size: 2rem !important; font-weight: 500 !important">{{$return_from}}
                                                    <i class="la la-arrow-right"></i> {{$return_to}}
                                                </h2>
                                                <div>
                                                    <p class="mb-1">{{ \Carbon\Carbon::parse($return_from_date)->format('d/m/Y') }}</p>
                                                    <p class="mb-1">{{ \Carbon\Carbon::parse($return_from_date)->format('H:i') }}
                                                        <i class="la la-arrow-right"></i> {{ \Carbon\Carbon::parse($return_to_date)->format('H:i') }}
                                                    </p>
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
            <div class="container margin_60">
                <div class="cart-section">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-primary text-uppercase"><strong>Departure</strong></h5>
                            </div>
                            <hr/>
                            <div class="row my-4" style="font-size: 1rem;">
                                <div class="col-md text-uppercase">FROM<br/><strong>{{$departure_from}}</strong></div>
                                <div class="col-md text-uppercase">TO<br/><strong>{{$departure_to}}</strong></div>
                                <div class="col-md text-uppercase">
                                    DEPARTURE<br/><strong>{{ \Carbon\Carbon::parse($departure_from_date)->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($departure_from_date)->format('H:i') }}
                                </div>
                                <div class="col-md text-uppercase">
                                    ARRIVAL<br/><strong>{{ \Carbon\Carbon::parse($departure_to_date)->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($departure_to_date)->format('H:i') }}
                                </div>
                                <div class="col-md text-uppercase">ADULT<br/><strong>{{$adult}}</strong></div>
                                <div class="col-md text-uppercase">INFANT<br/><strong>{{$infant}}</strong></div>
                                <div class="col-md text-uppercase">CHILD<br/><strong>{{$child}}</strong></div>
                            </div>
                            @if(count($departure_stops) > 1)
                                <h5 class="text-primary text-uppercase"><strong>Stops</strong></h5>
                                @foreach ($departure_stops as $stp)
                                    <div class="row my-4" style="font-size: 1rem;">
                                        <div class="col-md text-uppercase">FROM<br/><strong>{{$stp["origin"]}}</strong>
                                        </div>
                                        <div class="col-md text-uppercase">
                                            TO<br/><strong>{{$stp["destination"]}}</strong></div>
                                        <div class="col-md text-uppercase">
                                            DEPARTURE<br/><strong>{{ \Carbon\Carbon::parse($stp["departure_time"])->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($stp["departure_time"])->format('H:i') }}
                                        </div>
                                        <div class="col-md text-uppercase">
                                            ARRIVAL<br/><strong>{{ \Carbon\Carbon::parse($stp["arrival_time"])->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($stp["arrival_time"])->format('H:i') }}
                                        </div>
                                        <div class="col"></div>
                                        <div class="col"></div>
                                        <div class="col"></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @if($type == 'roundtrip')
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="text-primary text-uppercase"><strong>Return</strong></h5>
                                </div>
                                <hr/>
                                <div class="row my-4" style="font-size: 1rem">
                                    <div class="col-md text-uppercase">FROM<br/><strong>{{$return_from}}</strong></div>
                                    <div class="col-md text-uppercase">TO<br/><strong>{{$return_to}}</strong></div>
                                    <div class="col-md text-uppercase">
                                        DEPARTURE<br/><strong>{{ \Carbon\Carbon::parse($return_from_date)->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($return_from_date)->format('H:i') }}
                                    </div>
                                    <div class="col-md text-uppercase">
                                        ARRIVAL<br/><strong>{{ \Carbon\Carbon::parse($return_to_date)->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($return_to_date)->format('H:i') }}
                                    </div>
                                    <div class="col-md text-uppercase">ADULT<br/><strong>{{$adult}}</strong></div>
                                    <div class="col-md text-uppercase">INFANT<br/><strong>{{$infant}}</strong></div>
                                    <div class="col-md text-uppercase">CHILD<br/><strong>{{$child}}</strong></div>
                                </div>
                                @if(count($return_stops) > 1)
                                    <h5 class="text-primary text-uppercase"><strong>Stops</strong></h5>
                                    @foreach ($return_stops as $stp)
                                        <div class="row my-4" style="font-size: 1rem;">
                                            <div class="col-md text-uppercase">
                                                FROM<br/><strong>{{$stp["origin"]}}</strong></div>
                                            <div class="col-md text-uppercase">
                                                TO<br/><strong>{{$stp["destination"]}}</strong></div>
                                            <div class="col-md text-uppercase">
                                                DEPARTURE<br/><strong>{{ \Carbon\Carbon::parse($stp["departure_time"])->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($stp["departure_time"])->format('H:i') }}
                                            </div>
                                            <div class="col-md text-uppercase">
                                                ARRIVAL<br/><strong>{{ \Carbon\Carbon::parse($stp["arrival_time"])->format('d/m/Y') }}</strong><br/>{{ \Carbon\Carbon::parse($stp["arrival_time"])->format('H:i') }}
                                            </div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                            <div class="col"></div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div id="fairs" class="mb-3">
                    @if($selected_fare)

                    @else
                        @foreach($fares as $key => $value)
                            <div class="row mt-5 fare-{{ $key }} fare-row" id="{{ $key }}">
                                <div class="col-md-12">
                                    <div class="card h-100">
                                        <div class="card-body p-0">
                                            <div
                                                class="card-header bg-primary text-white font-weight-bold text-uppercase">{{ $value["name"] }}</div>
                                            <div
                                                class="text-primary pl-3 mt-2 font-weight-bold border-bottom pb-2 mb-2">
                                                Includes
                                            </div>
                                            <ul class="list-group list-group-flush ml-3">
                                                @foreach($value["services"]['departure'] as $service)
                                                    <li class="mb-2">{{ $service }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between">
                                            <div class="font-weight-bold">USD {{ $value["amount"] }}</div>
                                            <div>
                                                @if(isset($selected_fare['fare_type_id']) && $selected_fare['departure']['fare_type_id'] == $value['type_id'])
                                                    <button wire:click="selectFare('{{$key}}')" class="btn btn-dark font-weight-bold text-uppercase btn-sm">
                                                        Selected
                                                    </button>
                                                @else
                                                    <button id="{{ $key }}" data-model-id="{{ $key }}" wire:click="selectFare('{{$key}}')" class="select-fare btn btn-dark font-weight-bold text-uppercase btn-sm">
                                                        Select
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="text-center">
                        <div class="spinner-border mt-4" role="status" wire:loading wire:target="selectFare">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </div>
                @if($selected_fare)
                    <h4 class="text-primary text-uppercase mt-5 mb-4"><strong>Passengers</strong></h4>
                    <form method="post" id="form"
                          action="{{ (isset($selected_fare['name']) && (strtolower($selected_fare['name']) == 'flex' || strtolower($selected_fare['name']) == 'business')) ? route('frontend.low-cost.seats') : route('frontend.low-cost.summary') }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        @for ($i=0; $i < ($adult); $i++)
                            <div class="card mb-4">
                                <input type="hidden" name="passengers[adult][{{$i}}][ref_index]" value="A{{ $i+1 }}">
                                <div class="card-body">
                                    <div class="h5">Adult ({{$i+1}}/{{$adult}})</div>
                                    <div class="my-4" style="background: #cccccc; height: 1px; width: 100%"></div>
                                    <div class="row mt-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Gender <span class="text-primary">*</span></label>
                                                <select name="passengers[adult][{{$i}}][title]"
                                                        class="form-control form-select w-100" required>
                                                    <option value="" selected></option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>First Name <span class="text-primary">*</span></label>
                                                <input name="passengers[adult][{{$i}}][fname]" type="text"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Last Name <span class="text-primary">*</span></label>
                                                <input name="passengers[adult][{{$i}}][lname]" type="text"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        @if ($i == 0)
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Email <span class="text-primary">*</span></label>
                                                    <input name="passengers[adult][{{$i}}][email]" type="email"
                                                           class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Phone <span class="text-primary">*</span></label>
                                                    <input name="passengers[adult][{{$i}}][phone]" type="text"
                                                           class="form-control">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Date of Birth <span class="text-danger">*</span></label>
                                                <div class="relative" data-date="adult">
                                                    <input name="passengers[adult][{{$i}}][dob]" type="date"
                                                           class="form-control bg-white flatpickr" required data-input
                                                           picker="date" data-date="adult">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-3 mt-3">Passport Details</h6>
                                    <div class="my-4" style="background: #cccccc; height: 1px; width: 100%"></div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Nationality <span class="text-danger">*</span></label>
                                                <select name="passengers[adult][{{$i}}][nationality]" required
                                                        class="form-control w-100" data-placeholder="Select">
                                                    @foreach($countries as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Passport Number <span class="text-primary">*</span></label>
                                                <input name="passengers[adult][{{$i}}][passport]" type="text"
                                                       required class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Expiry Date <span class="text-primary">*</span></label>
                                                <input name="passengers[adult][{{$i}}][passportexpiry]" type="date"
                                                      required class="form-control password_expiry">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="mb-3 mt-3">Baggage</h6>
                                            <div class="my-4"
                                                 style="background: #cccccc; height: 1px; width: 100%"></div>
                                        </div>
                                        @foreach ($ancillaries as $flt)
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>{{ $flt["origin"] }} -> {{ $flt["destination"] }}</label>
                                                    <select class="form-control"
                                                            name="passengers[adult][{{$i}}][baggage][]">
                                                        <option value="">Select Baggage</option>
                                                        @foreach ($flt["baggage"] as $bgg)
                                                            <option
                                                                value="{{json_encode($bgg)}}">{{$bgg["description"]}}
                                                                ({{$bgg["amount"]}} USD)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="mb-3 mt-3">Meals</h6>
                                            <div class="my-4"
                                                 style="background: #cccccc; height: 1px; width: 100%"></div>
                                        </div>
                                        @foreach ($ancillaries as $flt)
                                            @foreach ($flt["legs"] as $leg)
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Route: {{$leg["origin"]}}
                                                            -> {{$leg["destination"]}}</label>
                                                        <select class="form-control"
                                                                name="passengers[adult][{{$i}}][meals][]">
                                                            <option value="">Select Meal</option>
                                                            @foreach ($leg["meals"] as $mll)
                                                                @if ($mll["code"] != "CHML")
                                                                    <option
                                                                        value="{{json_encode($mll)}}">{{$mll["description"]}}
                                                                        ({{$mll["amount"]}} USD)
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endfor

                        @for ($i=0; $i < ($child); $i++)
                            <div class="card mb-4">
                                <input type="hidden" name="passengers[child][{{$i}}][ref_index]" value="C{{ $i+1 }}">
                                <div class="card-body">
                                    <div class="h5">Child ({{$i+1}}/{{$child}})</div>
                                    <div class="my-4" style="background: #cccccc; height: 1px; width: 100%"></div>
                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Gender <span class="text-primary">*</span></label>
                                                <select name="passengers[child][{{$i}}][title]"
                                                        class="form-control form-select w-100" required>
                                                    <option value="" selected>Select</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>First Name <span class="text-primary">*</span></label>
                                                <input name="passengers[child][{{$i}}][fname]" type="text"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Last Name <span class="text-primary">*</span></label>
                                                <input name="passengers[child][{{$i}}][lname]" type="text"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Date of Birth <span class="text-danger">*</span></label>
                                                <div class="relative" data-date="child">
                                                    <input name="passengers[child][{{$i}}][dob]" type="date"
                                                           class="form-control bg-white flatpickr" required data-input
                                                           picker="date" data-date="child">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-3 mt-3">Passport Details</h6>
                                    <div class="my-4" style="background: #cccccc; height: 1px; width: 100%"></div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Nationality <span class="text-danger">*</span></label>
                                                <select name="passengers[child][{{$i}}][nationality]" required
                                                        class="form-control w-100" data-placeholder="Select">
                                                    @foreach($countries as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Passport Number <span class="text-primary">*</span></label>
                                                <input name="passengers[child][{{$i}}][passport]" type="text"
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Expiry Date <span class="text-primary">*</span></label>
                                                <input name="passengers[child][{{$i}}][passportexpiry]" type="date"
                                                       class="form-control password_expiry"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="mb-3 mt-3">Baggage</h6>
                                            <div class="my-4"
                                                 style="background: #cccccc; height: 1px; width: 100%"></div>
                                        </div>
                                        @foreach ($ancillaries as $flt)
                                            <div class="col">
                                                <div class="form-group">
                                                    <div><label>Route: {{$flt["origin"]}}
                                                            -> {{$flt["destination"]}}</label></div>
                                                    <select class="form-control"
                                                            name="passengers[child][{{$i}}][baggage][]">
                                                        <option value="">Select Baggage</option>
                                                        @foreach ($flt["baggage"] as $bgg)
                                                            <option
                                                                value="{{json_encode($bgg)}}">{{$bgg["description"]}}
                                                                ({{$bgg["amount"]}} USD)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="mb-3 mt-3">Meals</h6>
                                            <div class="my-4"
                                                 style="background: #cccccc; height: 1px; width: 100%"></div>
                                        </div>
                                        @foreach ($ancillaries as $flt)
                                            @foreach ($flt["legs"] as $leg)
                                                <div class="col">
                                                    <div class="form-group">
                                                        <div><label>Route: {{$leg["origin"]}}
                                                                -> {{$leg["destination"]}}</label>
                                                        </div>
                                                        <select class="form-control"
                                                                name="passengers[child][{{$i}}][meals][]">
                                                            <option value="">Select Meal</option>
                                                            @foreach ($leg["meals"] as $mll)
                                                                <option
                                                                    value="{{json_encode($mll)}}">{{$mll["description"]}}
                                                                    ({{$mll["amount"]}} USD)
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endfor

                        @for ($i=0; $i < ($infant); $i++)
                            <div class="card mb-4">
                                <input type="hidden" name="passengers[infant][{{$i}}][ref_index]" value="I{{ $i+1 }}">
                                <div class="card-body">
                                    <div class="h5">Infant ({{$i+1}}/{{$infant}})</div>
                                    <div class="my-4" style="background: #cccccc; height: 1px; width: 100%"></div>
                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Gender <span class="text-primary">*</span></label>
                                                <select name="passengers[infant][{{$i}}][title]"
                                                        class="form-control form-select w-100" required>
                                                    <option value="" selected>Select</option>
                                                    <option value="male" selected>Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>First Name <span class="text-primary">*</span></label>
                                                <input name="passengers[infant][{{$i}}][fname]" type="text"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Last Name <span class="text-primary">*</span></label>
                                                <input name="passengers[infant][{{$i}}][lname]" type="text"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Date of Birth <span class="text-danger">*</span></label>
                                                <div class="relative" data-date="infant">
                                                    <input name="passengers[infant][{{$i}}][dob]" type="date"
                                                           class="form-control bg-white flatpickr" required data-input
                                                           picker="date" data-date="infant">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-3 mt-3">Passport Details</h6>
                                    <div class="my-4" style="background: #cccccc; height: 1px; width: 100%"></div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Nationality <span class="text-danger">*</span></label>
                                                <select name="passengers[infant][{{$i}}][nationality]" required
                                                        class="form-control w-100" data-placeholder="Select">
                                                    @foreach($countries as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Passport Number <span class="text-primary">*</span></label>
                                                <input name="passengers[infant][{{$i}}][passport]" type="text"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Expiry Date <span class="text-primary">*</span></label>
                                                <input name="passengers[infant][{{$i}}][passportexpiry]" type="date"
                                                       class="form-control password_expiry">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor

                        <div class="row">
                            <div class="col-12">
                                <button id="confirm" type="submit"
                                        class="text-uppercase text-white w-100 border-0 h6 bg-primary p-3">
                                    {{ (isset($selected_fare['name']) && (strtolower($selected_fare['name']) == 'flex' || strtolower($selected_fare['name']) == 'business')) ? 'Select Seats ' : 'Confirm ' }}
                                    <i class="icon-left"></i></button>
                            </div>
                        </div>
                        <input type="hidden" name="airline" value="{{ $airline }}"/>
                        <input type="hidden" name="payload" value="{{ json_encode($payload) }}"/>
                        <input type="hidden" name="payload2" value="{{ json_encode($payload2) }}"/>
                        <input type="hidden" name="adult" value="{{ $adult }}"/>
                        <input type="hidden" name="child" value="{{ $child }}"/>
                        <input type="hidden" name="infant" value="{{ $infant }}"/>
                        <input type="hidden" name="ancillariespayload" value="{{ json_encode($ancillaries_payload) }}">
                        <input type="hidden" name="selectedfare" value="{{ json_encode($selected_fare) }}"/>
                        <input type="hidden" name="departurestops" value="{{ json_encode($departure_stops) }}"/>
                        <input type="hidden" name="returnstops" value="{{ json_encode($return_stops) }}"/>
                        <input type="hidden" name="accesstoken" value="{{ $access_token }}"/>
                        <input type="hidden" name="sessiontimestamp" value="{{ $session_timestamp }}"/>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <style>
        .btn:focus {
            box-shadow: none !important;
            color: white !important;
        }
    </style>
@endif
@push('styles')
    <style>
        .btn:focus {
            box-shadow: none !important;
            color: white !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).on('click', '.select-fare', function () {
            let model = $(this).data('model-id');
            let elementsToFade = $('.fare-row').not('.fare-' + model);
            elementsToFade.each(function (index) {
                let currentElement = $(this);
                setTimeout(function () {
                    currentElement.hide("slow");
                }, index * 200);
            });
        });

        $(document).on('focus', ".flatpickr", function (picker) {
            let dateInputList = document.querySelectorAll('.flatpickr');
            dateInputList.forEach(function (input) {
                const title = $(input).data('date');
                input.min = minDate(title).toISOString().split('T')[0];
                input.max = maxDate(title).toISOString().split('T')[0];
            });
        });

        function minDate(title) {
            const currentDate = new Date();
            if (title === 'adult') {
                const yearAgo = currentDate.getFullYear() - 100;
                return new Date(yearAgo, currentDate.getMonth(), currentDate.getDate());
            } else if (title === 'infant') {
                const yearAgo = currentDate.getFullYear() - 2;
                return new Date(yearAgo, currentDate.getMonth(), currentDate.getDate() - 1);
            } else if (title === 'child') {
                const yearAgo = currentDate.getFullYear() - 12;
                return new Date(yearAgo, currentDate.getMonth(), currentDate.getDate() - 1);
            }
        }

        function maxDate(title) {
            const currentDate = new Date();
            if (title === 'adult') {
                const yearAgo = currentDate.getFullYear() - 12;
                return new Date(yearAgo, currentDate.getMonth(), currentDate.getDate() - 1);
            } else if (title === 'infant') {
                const yearAgo = currentDate.getFullYear();
                return new Date(yearAgo, currentDate.getMonth(), currentDate.getDate() - 1);
            } else if (title === 'child') {
                const yearAgo = currentDate.getFullYear() - 2;
                return new Date(yearAgo, currentDate.getMonth(), currentDate.getDate() - 1);
            }
        }

        $(document).on('submit', '#form', function () {
            let submitButton = document.getElementById('confirm');
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.textContent = 'Please wait...';
        })

        $(document).on('focus', '.password_expiry', function () {
            var pminDate = new Date();
            pminDate.setMonth(pminDate.getMonth() + 6);
            var pminDateString = pminDate.toISOString().split('T')[0];
            $('.password_expiry').attr('min', pminDateString);
        });
    </script>
@endpush
