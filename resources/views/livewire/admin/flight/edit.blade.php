<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>

    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Flight
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        @if($errors->any())
            <div class="text-danger">
                {!! implode('', $errors->all('<div>:message</div>')) !!}
            </div>
        @endif
        <div class="container-fluid mb-4">
            <form wire:submit="submit" class="pt-3">
                <div class="row">
                    <div class="col">
                        <label class="{{ $errors->has('type') ? 'text-danger' : '' }}">Type</label>
                        <x-select-list required class="form-control" id="type" name="type" wire:model.live="flight.type" :options="['oneway' => 'Oneway', 'fixed' => 'Fixed']" />
                        <div class="validation-message">
                            {{ $errors->first('type') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('airline_id') ? 'text-danger' : '' }}">Airline</label>
                        <x-select-list required class="form-control" id="airline_id" name="airline_id" wire:model.live="flight.airline_id" :options="$this->listsForFields['airlines']" />
                        <div class="validation-message">
                            {{ $errors->first('airline_id') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.promo') ? 'text-danger' : '' }}">Flight Promo</label>
                        <x-select-list required class="form-control" id="promo" name="promo" wire:model.live="flight.promo" :options="['0' => 'No', '1' => 'Yes']" />
                    </div>
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Dates</h6>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_from_code') ? 'text-danger' : '' }}" for="dep_from_code">From</label>
                        <x-select-list required class="form-control" id="dep_from_code" name="dep_from_code" :options="$this->listsForFields['dep_from']" wire:model.live="flight.dep_from_code" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_from_code') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_to_code') ? 'text-danger' : '' }}" for="dep_to_code">To</label>
                        <x-select-list required class="form-control" required id="dep_to_code" name="dep_to_code" :options="$this->listsForFields['dep_to']" wire:model.live="flight.dep_to_code" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_to_code') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_flight_number') ? 'text-danger' : '' }}" for="dep_flight_number">Flight Number</label>
                        <input type="text" name="dep_flight_number" wire:model="flight.dep_flight_number" class="form-control" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_flight_number') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_date') ? 'text-danger' : '' }}" for="dep_date">Departure Date</label>
                        <x-date-picker required class="form-control" wire:model.live="flight.dep_date" id="dep_date" name="dep_date" picker="date" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_date') }}
                        </div>
                    </div>
                    <div class="col" wire:ignore>
                        <label class="{{ $errors->has('flight.dep_time') ? 'text-danger' : '' }}" for="dep_time">Departure Time</label>
                        <x-flatpickr required class="form-control" required wire:model.live="flight.dep_time" id="dep_time" name="dep_time" picker="time" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_time') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_arr_time') ? 'text-danger' : '' }}" for="arr_time">Arrival Time</label>
                        <x-date-picker required class="form-control" required wire:model.live="flight.dep_arr_time" id="dep_arr_time" name="dep_arr_time" picker="time" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_arr_time') }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3 {{ $flight->type == 'fixed' ? '' : 'd-none' }}">
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_from_code') ? 'text-danger' : '' }}" for="ret_from">Return From</label>
                        <x-select-list class="form-control" id="ret_from" name="ret_from" :options="$this->listsForFields['ret_from']" wire:model.live="flight.ret_from_code" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_from_code') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_to_code') ? 'text-danger' : '' }}" for="ret_to">Return To</label>
                        <x-select-list class="form-control" id="ret_to" name="ret_to" :options="$this->listsForFields['ret_to']" wire:model.live="flight.ret_to_code" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_to_code') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_flight_number') ? 'text-danger' : '' }}" for="ret_flight_number">Flight Number</label>
                        <input type="text" name="ret_flight_number" wire:model="flight.ret_flight_number" class="form-control" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_flight_number') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_dep_date') ? 'text-danger' : '' }}" for="ret_dep_date">Departure Date</label>
                        <x-date-picker class="form-control" required wire:model.live="flight.ret_dep_date" id="ret_dep_date" name="ret_dep_date" picker="date" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_dep_date') }}
                        </div>
                    </div>
                    <div class="col" wire:ignore>
                        <label class="{{ $errors->has('flight.ret_dep_time') ? 'text-danger' : '' }}" for="ret_dep_time">Departure Time</label>
                        <x-date-picker class="form-control" required wire:model.live="flight.ret_dep_time" id="ret_dep_time" name="ret_dep_time" picker="time" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_dep_time') }}
                        </div>
                    </div>
                    <div class="col" wire:ignore>
                        <label class="{{ $errors->has('flight.ret_arr_time') ? 'text-danger' : '' }}" for="ret_arr_time">{{ trans('cruds.flight.fields.ret_arr_time') }}</label>
                        <x-date-picker class="form-control" required wire:model.live="flight.ret_arr_time" id="ret_arr_time" name="ret_arr_time" picker="time" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_arr_time') }}
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Pricing & Seats</h6>
                        <hr/>
                        <p class="text-info">Prices are pulled based on the number of seats you enter for each class.<br/>Starting Alphabetically, class A is the first to be used until the number of booked seats is less or equals to the number of seats entered in the class A seats field, after that, class B will be used etc...</p>
                        <div class="my-3 alert alert-danger">When changing number of seats, make sure the number of seats is greater than or equal to the number of booked seats for each class.</div>
                        <hr/>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Economy Class L <span class="text-danger">({{ $this->getBookedSeats('L') }} seats booked)</span></label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" min="{{ $this->getBookedSeats('L') }}" wire:model="pricing.0.seats" />
                    </div>
                    <div class="col">
                        <label>Adult Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.0.adult" />
                    </div>
                    <div class="col">
                        <label>Child Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.0.child" />
                    </div>
                    <div class="col">
                        <label>Infant Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.0.infant" />
                    </div>
                </div>
                @if(config('features.hotels'))
                <div class="row">
                    <div class="col d-flex align-items-center">
                    </div>
                    <div class="col">
                    </div>
                    <div class="col">
                        <label>Package Adult Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.0.package_adult" />
                    </div>
                    <div class="col">
                        <label>Package Child Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.0.package_child" />
                    </div>
                    <div class="col">
                        <label>Package Infant Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.0.package_infant" />
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Economy Class M <span class="text-danger">({{ $this->getBookedSeats('M') }} seats booked)</span></label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" min="{{ $this->getBookedSeats('M') }}" wire:model="pricing.1.seats" />
                    </div>
                    <div class="col">
                        <label>Adult Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.1.adult" />
                    </div>
                    <div class="col">
                        <label>Child Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.1.child" />
                    </div>
                    <div class="col">
                        <label>Infant Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.1.infant" />
                    </div>
                </div>
                @if(config('features.hotels'))
                <div class="row">
                    <div class="col d-flex align-items-center">
                    </div>
                    <div class="col">
                    </div>
                    <div class="col">
                        <label>Package Adult Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.1.package_adult" />
                    </div>
                    <div class="col">
                        <label>Package Child Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.1.package_child" />
                    </div>
                    <div class="col">
                        <label>Package Infant Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.1.package_infant" />
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Economy Class Y <span class="text-danger">({{ $this->getBookedSeats('Y') }} seats booked)</span></label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" min="{{ $this->getBookedSeats('Y') }}" wire:model="pricing.2.seats" />
                    </div>
                    <div class="col">
                        <label>Adult Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.2.adult" />
                    </div>
                    <div class="col">
                        <label>Child Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.2.child" />
                    </div>
                    <div class="col">
                        <label>Infant Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.2.infant" />
                    </div>
                </div>
                @if(config('features.hotels'))
                <div class="row">
                    <div class="col d-flex align-items-center">
                    </div>
                    <div class="col">
                    </div>
                    <div class="col">
                        <label>Package Adult Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.2.package_adult" />
                    </div>
                    <div class="col">
                        <label>Package Child Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.2.package_child" />
                    </div>
                    <div class="col">
                        <label>Package Infant Price</label>
                        <input required type="number" class="form-control" wire:model="pricing.2.package_infant" />
                    </div>
                </div>
                @endif
                <hr/>
                @if(config('features.cabins'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label>Business Class Z <span class="text-danger">({{ $this->getBookedSeats('Z') }} seats booked)</span></label>
                        </div>
                        <div class="col">
                            <label>Seats</label>
                            <input required type="number" class="form-control" min="{{ $this->getBookedSeats('Z') }}" wire:model="business_pricing.0.seats" />
                        </div>
                        <div class="col">
                            <label>Adult Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.0.adult" />
                        </div>
                        <div class="col">
                            <label>Child Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.0.child" />
                        </div>
                        <div class="col">
                            <label>Infant Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.0.infant" />
                        </div>
                    </div>
                    @if(config('features.hotels'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                        </div>
                        <div class="col">
                        </div>
                        <div class="col">
                            <label>Package Adult Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.0.package_adult" />
                        </div>
                        <div class="col">
                            <label>Package Child Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.0.package_child" />
                        </div>
                        <div class="col">
                            <label>Package Infant Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.0.package_infant" />
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label>Business Class K <span class="text-danger">({{ $this->getBookedSeats('K') }} seats booked)</span></label>
                        </div>
                        <div class="col">
                            <label>Seats</label>
                            <input required type="number" class="form-control" min="{{ $this->getBookedSeats('K') }}" wire:model="business_pricing.1.seats" />
                        </div>
                        <div class="col">
                            <label>Adult Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.1.adult" />
                        </div>
                        <div class="col">
                            <label>Child Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.1.child" />
                        </div>
                        <div class="col">
                            <label>Infant Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.1.infant" />
                        </div>
                    </div>
                    @if(config('features.hotels'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                        </div>
                        <div class="col">
                        </div>
                        <div class="col">
                            <label>Package Adult Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.1.package_adult" />
                        </div>
                        <div class="col">
                            <label>Package Child Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.1.package_child" />
                        </div>
                        <div class="col">
                            <label>Package Infant Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.1.package_infant" />
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label>Business Class J <span class="text-danger">({{ $this->getBookedSeats('J') }} seats booked)</span></label>
                        </div>
                        <div class="col">
                            <label>Seats</label>
                            <input required type="number" class="form-control" min="{{ $this->getBookedSeats('J') }}" wire:model="business_pricing.2.seats" />
                        </div>
                        <div class="col">
                            <label>Adult Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.2.adult" />
                        </div>
                        <div class="col">
                            <label>Child Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.2.child" />
                        </div>
                        <div class="col">
                            <label>Infant Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.2.infant" />
                        </div>
                    </div>
                    @if(config('features.hotels'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                        </div>
                        <div class="col">
                        </div>
                        <div class="col">
                            <label>Package Adult Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.2.package_adult" />
                        </div>
                        <div class="col">
                            <label>Package Child Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.2.package_child" />
                        </div>
                        <div class="col">
                            <label>Package Infant Price</label>
                            <input required type="number" class="form-control" wire:model="business_pricing.2.package_infant" />
                        </div>
                    </div>
                    @endif
                    <hr/>
                @endif
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Additional Info</h6>
                        <hr/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label class="{{ $errors->has('flight.contract_id') ? 'text-danger' : '' }}" for="contract">Contract</label>
                        <x-select-list class="form-control" id="contract" name="contract" :options="$this->listsForFields['contract']" wire:model.live="flight.contract_id" />
                        <div class="validation-message">
                            {{ $errors->first('flight.contract_id') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.baggage_allowance') ? 'text-danger' : '' }}" for="baggage_allowance">Baggage Allowance</label>
                        <input class="form-control" type="text" name="baggage_allowance" id="baggage_allowance" wire:model="flight.baggage_allowance">
                        <div class="validation-message">
                            {{ $errors->first('flight.baggage_allowance') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.stop_booking_before') ? 'text-danger' : '' }}" for="stop_booking_before">Booking Cutoff Days</label>
                        <input class="form-control" type="number" name="stop_booking_before" id="stop_booking_before" wire:model="flight.stop_booking_before">
                        <div class="validation-message">
                            {{ $errors->first('flight.stop_booking_before') }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="">
                            <div>
                                <label for="multi_supplements">Remaining Seats Display</label>
                                <input class="form-control" type="number" name="remaining_seats_display" id="remaining_seats_display" wire:model="flight.remaining_seats_display" step="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label class="{{ $errors->has('flight.oneway_supplements') ? 'text-danger' : '' }}" for="oneway_supplements">{{ trans('cruds.flight.fields.oneway_supplements') }}</label>
                        <input class="form-control" type="number" name="oneway_supplements" id="oneway_supplements" wire:model="flight.oneway_supplements" step="1">
                        <div class="validation-message">
                            {{ $errors->first('flight.oneway_supplements') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.multi_supplements') ? 'text-danger' : '' }}" for="multi_supplements">{{ trans('cruds.flight.fields.multi_supplements') }}</label>
                        <input class="form-control" type="number" name="multi_supplements" id="multi_supplements" wire:model="flight.multi_supplements" step="1">
                        <div class="validation-message">
                            {{ $errors->first('flight.multi_supplements') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.combinable_supplements') ? 'text-danger' : '' }}" for="combinable_supplements">Mixed Airlines Supplements</label>
                        <input class="form-control" type="number" name="combinable_supplements" id="combinable_supplements" wire:model="flight.combinable_supplements" step="1">
                        <div class="validation-message">
                            {{ $errors->first('flight.combinable_supplements') }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col">
                                <label class="{{ $errors->has('category') ? 'text-danger' : '' }}">Categories</label>
                                <x-select-list class="form-control" id="category" name="category" wire:model.live="category" :options="$this->listsForFields['category']" multiple />
                                <div class="validation-message">
                                    {{ $errors->first('category') }}
                                </div>
                            </div>
                            <div class="col">
                                <label class="{{ $errors->has('flight.expiry_date') ? 'text-danger' : '' }}" for="expiry_date">Expiry Date</label>
                                <x-date-picker class="form-control" wire:model.live="flight.expiry_date" id="expiry_date" name="expiry_date" picker="date" />
                                <div class="validation-message">
                                    {{ $errors->first('flight.expiry_date') }}
                                </div>
                            </div>
                            <div class="col">
                                    <div class="form-group {{ $errors->has('flight.onhold_bookings') ? 'invalid' : '' }}">
                                        <label class="form-label" for="active">Restrict to On Hold bookings</label>
                                        <x-select-list required class="form-control" id="onhold_bookings" name="onhold_bookings" wire:model.live="flight.onhold_bookings" :options="['1' => 'Yes', '0' => 'No']" />
                                        <div class="validation-message">
                                            {{ $errors->first('flight.onhold_bookings') }}
                                        </div>
                                    </div>
                            </div>
                            <div class="col">
                                <div class="form-group {{ $errors->has('flight.active') ? 'invalid' : '' }}">
                                    <label class="form-label" for="active">Status</label>
                                    <x-select-list required class="form-control" id="active" name="active" wire:model.live="flight.active" :options="['1' => 'Active', '0' => 'Inactive']" />
                                    <div class="validation-message">
                                        {{ $errors->first('flight.active') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <label>HIDE FROM THE FOLLOWING USERS & AGENCIES</label>
                        <x-select-list buttons multiple class="form-control" id="agencies_permissions" name="agencies_permissions" :options="$this->listsForFields['users']" wire:model.live="flight.agencies_permissions" />
                    </div>
                    <div class="col-12 mt-3">
                        <label>PACKAGE NOTE</label>
                        <input type="text" id="package-note" wire:model="flight.package_note" class="form-control" />
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="col d-flex align-items-end justify-content-end">
                            <div>
                                <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button class="btn btn-success mr-2" type="submit">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
