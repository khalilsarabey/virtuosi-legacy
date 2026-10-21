<div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" />
    <section class="breadcrumb-area bread-bg-flights mb-5">
        <div class="breadcrumb-wrap">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="breadcrumb-content my-5">
                            <div class="section-heading">
                                <div class="left-side-info" style="color:#fff">
                                    <h2 class="sec__title_list mb-1"
                                        style="font-size: 2rem !important; font-weight: 500 !important">Auto Pricing</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($announcement && count($announcement))
        <section>
            <div class="container">
                <div class="row">
                    @foreach($announcement as $a)
                        <div class="col-md-12">
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">{{ $a->title }}</h4>
                                <div>{!! $a->content !!}</div>
                            </div>
                            <div class="text-right mb-4">
                                <button type="button" wire:click="closeAnnouncement({{ $a->id }})" class="btn btn-primary btn-sm">Close</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <section @if($announcement && count($announcement)) class="d-none" @endif>
        <div class="container">
            <div class="row">
                @if(auth()->user()->type == 'admin' || auth()->user()->type == 'user')
                    <div class="col-md-4">
                        <select id="agencies" class="select2 form-select form-control" wire:model.live="selectedAgency">
                            <option value="">Select Agency</option>
                            @foreach($agencies as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('frontend.auto-pricing.announcements') }}" class="btn btn-outline-primary">View Announcements</a>
                    </div>
                    @isset($agencyObject)
                        @if($agencyObject->notes)
                        <div class="col-12">
                            <div class="mt-4 alert alert-info" role="alert">
                                {!! $agencyObject->notes !!}
                            </div>
                        </div>
                        @endif
                    @endisset
                @endif
            </div>
            <div class="row mt-3">
                @if($selectedAgency)
                    <div class="col-md-4">
                        <select id="categories" class="select2 form-select form-control" wire:model.live="selectedCategory">
                            <option value="">Select Airline</option>
                            @foreach($turkish_sections as $turkish_section)
                                <option value="turkish-{{ $turkish_section->id }}">{{ $turkish_section->title }}</option>
                            @endforeach
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if($selectedAgency && $selectedCategory)
                    @if($is_turkish)
                        <div class="col-md-4">
                            <select id="aiport" class="select2 form-select form-control" wire:model.live="selectedAirport">
                                <option value="">Select Airport</option>
                                    @foreach($airports as $air)
                                        <option value="{{ $air->id }}">{{ $air->code }}</option>
                                    @endforeach
                            </select>
                        </div>
                    @else
                        <div class="col-md-4">
                                <select id="airlines" class="select2 form-select form-control" wire:model.live="selectedAirline">
                                    <option value="">Select Airline</option>
                                    @foreach($airlines as $line)
                                        <option value="{{ $line->id }}">{{ $line->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    @endif
                @endif
                @if($selectedAgency && $selectedCategory && $selectedAirport)
                    <div class="col-md-4">
                        <select id="airport_class" class="select2 form-select form-control" wire:model.live="selectedAirportClass">
                            <option value="">Select Class</option>
                                @foreach($airportClasses as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                        </select>
                    </div>
                @endif
            </div>
            @if(($selectedAgency && $selectedAirline && $selectedCategory) || ($selectedAgency && $selectedAirport && $selectedAirportClass))
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="bg-white h-100 p-4 border">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold text-uppercase font-size-15" for="base_fare">Base Fare</label>
                                <input type="number" class="form-control" wire:model="base_fare" name="base_fare" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold text-uppercase font-size-15" for="total_fare">Total Fare</label>
                                <input type="number" class="form-control" name="total_fare" wire:model="total_fare" />
                            </div>
                            <div class="col-md-12 text-right">
                                <div class="row">
                                    <div class="col-md-6">
                                        @if(auth()->user()->type == 'admin' || auth()->user()->type == 'user')
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="font-size-13 text-primary font-weight-bold mt-3 text-left">Selling Calculations are based on:</div>
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <div class="font-size-13 text-secondary font-weight-bold">Selling Commission</div>
                                                        <div class="font-size-13 text-secondary font-weight-bold">{{ floatval($pricing['selling_commission']) }}%</div>
                                                    </div>
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <div class="font-size-13 text-secondary font-weight-bold">Selling Service Fee</div>
                                                        <div class="font-size-13 text-secondary font-weight-bold">{{ floatval($pricing['selling_service_fee']) }}%</div>
                                                    </div>
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <div class="font-size-13 text-secondary font-weight-bold">Fixed Fee</div>
                                                        <div class="font-size-13 text-secondary font-weight-bold">${{ floatval($pricing['selling_fixed_fee']) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" wire:click="calculate" class="btn btn-primary mt-4">Calculate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white border p-4 d-flex align-items-center w-100 justify-content-between" style="height: 110px;">
                            <div class="form-label font-weight-bold text-uppercase font-size-15" style="color: #c9245f">Selling Fare</div>
                            <div class="form-label font-weight-bold text-uppercase font-size-15">{{ number_format($selling_fare, 2) ?? '-' }}</div>
                        </div>
                        <div class="bg-white border p-4 mt-4">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(auth()->user()->type == 'admin' || auth()->user()->type == 'user')
                                        <div class="d-flex justify-content-between">
                                            <div class="form-label font-weight-bold text-uppercase font-size-15">Tour Code</div>
                                            <div class="form-label font-weight-bold text-uppercase font-size-15">{{ $airline->tour_code ?? '-' }}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="form-label font-weight-bold text-uppercase font-size-15" for="base_fare">Our Net</div>
                                            <div class="form-label font-weight-bold text-uppercase font-size-15" for="base_fare">{{ number_format($net_fare, 2) ?? '-' }}</div>
                                        </div>
                                    @endif
                                </div>
                                @if(auth()->user()->type == 'admin' || auth()->user()->type == 'user')
                                    <div class="col-12">
                                        <div class="font-size-13 text-primary font-weight-bold mt-3">Net calculations are based on:</div>
                                        <div class="w-100 d-flex justify-content-between">
                                            <div class="font-size-13 text-secondary font-weight-bold">Commission</div>
                                            <div class="font-size-13 text-secondary font-weight-bold">{{ floatval($pricing['net_commission']) }}%</div>
                                        </div>
                                        <div class="w-100 d-flex justify-content-between">
                                            <div class="font-size-13 text-secondary font-weight-bold">Service Fee</div>
                                            <div class="font-size-13 text-secondary font-weight-bold">{{ floatval($pricing['net_service_fee']) }}%</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(($airline && $selectedAgency && $selectedAirline && $selectedCategory) || ($selectedAgency && $selectedAirport))
                @if($pricing['agency_remark'] && $pricing['agency_remark'] != '')
                    <div class="p-4 mt-4 border" style="background-color: rgba(13,202,240, .1)!important">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-label font-weight-bold text-uppercase font-size-15">Agency Remark</h5>
                                {!! nl2br($pricing['agency_remark']) ?? '-' !!}
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->type == 'admin' || auth()->user()->type == 'user')
                    @if($pricing['fare_remark'] && $pricing['fare_remark'] != '')
                        <div class="mt-4 p-4 border" style="background-color: rgba(13,202,240, .1) !important">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-label font-weight-bold text-uppercase font-size-15">Fare Remark</h5>
                                    {!! nl2br($pricing['fare_remark']) ?? '-' !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </section>
</div>
