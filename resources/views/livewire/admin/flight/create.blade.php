<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Flight
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <form wire:submit.prevent="submit" class="pt-3">
                <div class="row">
                    <div class="col">
                        <label class="{{ $errors->has('type') ? 'text-danger' : '' }}">Type</label>
                        <x-select-list required class="form-control" id="type" name="type" wire:model="flight.type" :options="['oneway' => 'Oneway', 'fixed' => 'Fixed']" />
                        <div class="validation-message">
                            {{ $errors->first('type') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('airline_id') ? 'text-danger' : '' }}">Airline</label>
                        <x-select-list required class="form-control" id="airline_id" name="airline_id" wire:model="flight.airline_id" :options="$this->listsForFields['airlines']" />
                        <div class="validation-message">
                            {{ $errors->first('airline_id') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.promo') ? 'text-danger' : '' }}">Flight Promo</label>
                        <x-select-list required class="form-control" id="promo" name="promo" wire:model="flight.promo" :options="['0' => 'No', '1' => 'Yes']" />
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
                        <label class="{{ $errors->has('flight.dep_from_code') ? 'text-danger' : '' }}" for="dep_from">From</label>
                        <x-select-list required class="form-control" id="dep_from" name="dep_from" :options="$this->listsForFields['dep_from']" wire:model="flight.dep_from_code" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_from_code') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_to_code') ? 'text-danger' : '' }}" for="dep_to">To</label>
                        <x-select-list required class="form-control" required id="dep_to" name="dep_to" :options="$this->listsForFields['dep_to']" wire:model="flight.dep_to_code" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_to_code') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_flight_number') ? 'text-danger' : '' }}" for="dep_flight_number">Flight Number</label>
                        <input type="text" name="dep_flight_number" wire:model.defer="flight.dep_flight_number" class="form-control" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_flight_number') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_date') ? 'text-danger' : '' }}" for="dep_date">Departure Date</label>
                        <x-date-picker required class="form-control" wire:model="flight.dep_date" id="dep_date" name="dep_date" picker="date" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_date') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_time') ? 'text-danger' : '' }}" for="dep_time">Departure Time</label>
                        <x-date-picker required class="form-control" required wire:model="flight.dep_time" id="dep_time" name="dep_time" picker="time" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_time') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.dep_arr_time') ? 'text-danger' : '' }}" for="arr_time">Arrival Time</label>
                        <x-date-picker required class="form-control" required wire:model="flight.dep_arr_time" id="dep_arr_time" name="dep_arr_time" picker="time" />
                        <div class="validation-message">
                            {{ $errors->first('flight.dep_arr_time') }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3 {{ $flight->type == 'fixed' ? '' : 'd-none' }}">
                    <div class="col"></div>
                    <div class="col"></div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_flight_number') ? 'text-danger' : '' }}" for="ret_flight_number">Flight Number</label>
                        <input type="text" name="ret_flight_number" wire:model.defer="flight.ret_flight_number" class="form-control" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_flight_number') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_dep_date') ? 'text-danger' : '' }}" for="ret_dep_date">Departure Date</label>
                        <x-date-picker class="form-control" wire:model="flight.ret_dep_date" id="ret_dep_date" name="ret_dep_date" picker="date" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_dep_date') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_dep_time') ? 'text-danger' : '' }}" for="ret_dep_time">Departure Time</label>
                        <x-date-picker class="form-control" wire:model="flight.ret_dep_time" id="ret_dep_time" name="ret_dep_time" picker="time" />
                        <div class="validation-message">
                            {{ $errors->first('flight.ret_dep_time') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.ret_arr_time') ? 'text-danger' : '' }}" for="ret_arr_time">{{ trans('cruds.flight.fields.ret_arr_time') }}</label>
                        <x-date-picker class="form-control" wire:model="flight.ret_arr_time" id="ret_arr_time" name="ret_arr_time" picker="time" />
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
                        <hr/>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Economy Class L</label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.0.seats" />
                    </div>
                    <div class="col">
                        <label>Adult Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.0.adult" />
                    </div>
                    <div class="col">
                        <label>Child Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.0.child" />
                    </div>
                    <div class="col">
                        <label>Infant Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.0.infant" />
                    </div>
                </div>
                @if(config('features.hotels'))
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label></label>
                    </div>
                    <div class="col">
                        <label></label>
                    </div>
                    <div class="col">
                        <label>Package Adult Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.0.package_adult" />
                    </div>
                    <div class="col">
                        <label>Package Child Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.0.package_child" />
                    </div>
                    <div class="col">
                        <label>Package Infant Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.0.package_infant" />
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Economy Class M</label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.1.seats" />
                    </div>
                    <div class="col">
                        <label>Adult Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.1.adult" />
                    </div>
                    <div class="col">
                        <label>Child Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.1.child" />
                    </div>
                    <div class="col">
                        <label>Infant Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.1.infant" />
                    </div>
                </div>
                @if(config('features.hotels'))
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label></label>
                    </div>
                    <div class="col">
                        <label></label>
                    </div>
                    <div class="col">
                        <label>Package Adult Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.1.package_adult" />
                    </div>
                    <div class="col">
                        <label>Package Child Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.1.package_child" />
                    </div>
                    <div class="col">
                        <label>Package Infant Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.1.package_infant" />
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Economy Class Y</label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.2.seats" />
                    </div>
                    <div class="col">
                        <label>Adult Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.2.adult" />
                    </div>
                    <div class="col">
                        <label>Child Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.2.child" />
                    </div>
                    <div class="col">
                        <label>Infant Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.2.infant" />
                    </div>
                </div>
                @if(config('features.hotels'))
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label></label>
                    </div>
                    <div class="col">
                        <label></label>
                    </div>
                    <div class="col">
                        <label>Package Adult Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.2.package_adult" />
                    </div>
                    <div class="col">
                        <label>Package Child Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.2.package_child" />
                    </div>
                    <div class="col">
                        <label>Package Infant Price</label>
                        <input required type="number" class="form-control" wire:model.defer="pricing.2.package_infant" />
                    </div>
                </div>
                @endif
                <hr/>
                @if(config('features.cabins'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label>Business Class Z</label>
                        </div>
                        <div class="col">
                            <label>Seats</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.0.seats" />
                        </div>
                        <div class="col">
                            <label>Adult Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.0.adult" />
                        </div>
                        <div class="col">
                            <label>Child Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.0.child" />
                        </div>
                        <div class="col">
                            <label>Infant Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.0.infant" />
                        </div>
                    </div>
                    @if(config('features.hotels'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label></label>
                        </div>
                        <div class="col">
                            <label></label>
                        </div>
                        <div class="col">
                            <label>Package Adult Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.0.package_adult" />
                        </div>
                        <div class="col">
                            <label>Package Child Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.0.package_child" />
                        </div>
                        <div class="col">
                            <label>Package Infant Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.0.package_infant" />
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label>Business Class K</label>
                        </div>
                        <div class="col">
                            <label>Seats</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.1.seats" />
                        </div>
                        <div class="col">
                            <label>Adult Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.1.adult" />
                        </div>
                        <div class="col">
                            <label>Child Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.1.child" />
                        </div>
                        <div class="col">
                            <label>Infant Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.1.infant" />
                        </div>
                    </div>
                    @if(config('features.hotels'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label></label>
                        </div>
                        <div class="col">
                            <label></label>
                        </div>
                        <div class="col">
                            <label>Package Adult Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.1.package_adult" />
                        </div>
                        <div class="col">
                            <label>Package Child Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.1.package_child" />
                        </div>
                        <div class="col">
                            <label>Package Infant Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.1.package_infant" />
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label>Business Class J</label>
                        </div>
                        <div class="col">
                            <label>Seats</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.2.seats" />
                        </div>
                        <div class="col">
                            <label>Adult Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.2.adult" />
                        </div>
                        <div class="col">
                            <label>Child Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.2.child" />
                        </div>
                        <div class="col">
                            <label>Infant Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.2.infant" />
                        </div>
                    </div>
                    @if(config('features.hotels'))
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <label></label>
                        </div>
                        <div class="col">
                            <label></label>
                        </div>
                        <div class="col">
                            <label>Package Adult Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.2.package_adult" />
                        </div>
                        <div class="col">
                            <label>Package Child Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.2.package_child" />
                        </div>
                        <div class="col">
                            <label>Package Infant Price</label>
                            <input required type="number" class="form-control" wire:model.defer="business_pricing.2.package_infant" />
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
                        <x-select-list class="form-control" id="contract" name="contract" :options="$this->listsForFields['contract']" wire:model="flight.contract_id" />
                        <div class="validation-message">
                            {{ $errors->first('flight.contract_id') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.baggage_allowance') ? 'text-danger' : '' }}" for="baggage_allowance">Baggage Allowance</label>
                        <input class="form-control" type="text" name="baggage_allowance" id="baggage_allowance" wire:model.defer="flight.baggage_allowance">
                        <div class="validation-message">
                            {{ $errors->first('flight.baggage_allowance') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.oneway_supplements') ? 'text-danger' : '' }}" for="oneway_supplements">{{ trans('cruds.flight.fields.oneway_supplements') }}</label>
                        <input class="form-control" type="number" name="oneway_supplements" id="oneway_supplements" wire:model.defer="flight.oneway_supplements" step="1">
                        <div class="validation-message">
                            {{ $errors->first('flight.oneway_supplements') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.stop_booking_before') ? 'text-danger' : '' }}" for="stop_booking_before">Booking Cutoff Days</label>
                        <input class="form-control" type="number" name="stop_booking_before" id="stop_booking_before" wire:model.defer="flight.stop_booking_before">
                        <div class="validation-message">
                            {{ $errors->first('flight.stop_booking_before') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label class="{{ $errors->has('flight.multi_supplements') ? 'text-danger' : '' }}" for="multi_supplements">{{ trans('cruds.flight.fields.multi_supplements') }}</label>
                        <input class="form-control" type="number" name="multi_supplements" id="multi_supplements" wire:model.defer="flight.multi_supplements" step="1">
                        <div class="validation-message">
                            {{ $errors->first('flight.multi_supplements') }}
                        </div>
                    </div>
                    <div class="col">
                        <label class="{{ $errors->has('flight.combinable_supplements') ? 'text-danger' : '' }}" for="combinable_supplements">Mixed Airlines Supplements</label>
                        <input class="form-control" type="number" name="combinable_supplements" id="combinable_supplements" wire:model.defer="flight.combinable_supplements" step="1">
                        <div class="validation-message">
                            {{ $errors->first('flight.combinable_supplements') }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="">
                            <div>
                                <label for="multi_supplements">"Remaining Seats" Display</label>
                                <input class="form-control" type="number" name="remaining_seats_display" id="remaining_seats_display" wire:model.defer="flight.remaining_seats_display" step="1">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group {{ $errors->has('flight.active') ? 'invalid' : '' }}">
                            <label class="form-label" for="active">Status</label>
                            <x-select-list required class="form-control" id="active" name="active" wire:model="flight.active" :options="['1' => 'Active', '0' => 'Inactive']" />
                            <div class="validation-message">
                                {{ $errors->first('flight.active') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-4">
                                <label class="{{ $errors->has('category') ? 'text-danger' : '' }}">Categories</label>
                                <x-select-list class="form-control" id="category" name="category" wire:model="category" :options="$this->listsForFields['category']" multiple />
                                <div class="validation-message">
                                    {{ $errors->first('category') }}
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="{{ $errors->has('flight.expiry_date') ? 'text-danger' : '' }}" for="expiry_date">Expiry Date</label>
                                <x-date-picker class="form-control" wire:model="flight.expiry_date" id="dep_date" name="expiry_date" picker="date" />
                                <div class="validation-message">
                                    {{ $errors->first('flight.expiry_date') }}
                                </div>
                            </div>
                            <div class="col-4">

                                    <div class="form-group {{ $errors->has('flight.onhold_bookings') ? 'invalid' : '' }}">
                                        <label class="form-label" for="active">Restrict to On Hold bookings</label>
                                        <x-select-list required class="form-control" id="onhold_bookings" name="onhold_bookings" wire:model="flight.onhold_bookings" :options="['1' => 'Yes', '0' => 'No']" />
                                        <div class="validation-message">
                                            {{ $errors->first('flight.onhold_bookings') }}
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <label>HIDE FROM THE FOLLOWING USERS & AGENCIES</label>
                        <x-select-list buttons multiple class="form-control" id="agencies_permissions" name="agencies_permissions" :options="$this->listsForFields['users']" wire:model="flight.agencies_permissions" />
                    </div>
                    <div class="col-12 mt-3">
                        <label>PACKAGE NOTE</label>
                        <input type="text" id="package-note" wire:model.defer="flight.package_note" class="form-control" />
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
