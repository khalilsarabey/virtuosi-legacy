<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Duplicate Flight
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-4">
                    <label>Number of Duplications</label>
                    <x-select-list required class="form-control" id="num" name="num" wire:model="num" :options="['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5']" />
                </div>
            </div>

            @foreach($generated as $index => $flight)
                <h4 class="mt-5 text-center text-primary" style="font-weight:bolder;">FLIGHT {{ $index + 1 }}</h4>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">Dates</h6>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="dep_from">From</label>
                        <x-select-list required class="form-control" id="dep_from" name="dep_from" :options="$this->listsForFields['dep_from']" wire:model="generated.{{$index}}.dep_from_code" />
                    </div>
                    <div class="col">
                        <label for="dep_to">To</label>
                        <x-select-list required class="form-control" required id="dep_to" name="dep_to" :options="$this->listsForFields['dep_to']" wire:model="generated.{{$index}}.dep_to_id" />
                    </div>
                    <div class="col">
                        <label for="dep_flight_number">Flight Number</label>
                        <input type="text" name="dep_flight_number" wire:model.defer="generated.{{$index}}.dep_flight_number" class="form-control" />
                    </div>
                    <div class="col">
                        <label for="dep_date">Departure Date</label>
                        <x-date-picker required class="form-control" wire:model="generated.{{$index}}.dep_date" id="dep_date" name="dep_date" picker="date" />
                    </div>
                    <div class="col">
                        <label for="dep_time">Departure Time</label>
                        <x-date-picker required class="form-control" required wire:model="generated.{{$index}}.dep_time" id="dep_time" name="dep_time" picker="time" />
                    </div>
                    <div class="col">
                        <label for="arr_time">Arrival Time</label>
                        <x-date-picker required class="form-control" required wire:model="generated.{{$index}}.dep_arr_time" id="dep_arr_time" name="dep_arr_time" picker="time" />
                    </div>
                </div>
                <div class="row mt-3 {{ $flight['type'] == 'fixed' ? '' : 'd-none' }}">
                    <div class="col">
                        <label for="ret_from">Return From</label>
                        <x-select-list class="form-control" id="ret_from" name="ret_from" :options="$this->listsForFields['ret_from']" wire:model="generated.{{$index}}.ret_from_id" />
                    </div>
                    <div class="col">
                        <label for="ret_to">Return To</label>
                        <x-select-list class="form-control" id="ret_to" name="ret_to" :options="$this->listsForFields['ret_to']" wire:model="generated.{{$index}}.ret_to_id" />
                    </div>
                    <div class="col">
                        <label for="ret_flight_number">Flight Number</label>
                        <input type="text" name="ret_flight_number" wire:model.defer="generated.{{$index}}.ret_flight_number" class="form-control" />
                    </div>
                    <div class="col">
                        <label for="ret_dep_date">Departure Date</label>
                        <x-date-picker class="form-control" wire:model="generated.{{$index}}.ret_dep_date" id="ret_dep_date" name="ret_dep_date" picker="date" />
                    </div>
                    <div class="col">
                        <label for="ret_dep_time">Departure Time</label>
                        <x-date-picker class="form-control" wire:model="generated.{{$index}}.ret_dep_time" id="ret_dep_time" name="ret_dep_time" picker="time" />
                    </div>
                    <div class="col">
                        <label for="ret_arr_time">{{ trans('cruds.flight.fields.ret_arr_time') }}</label>
                        <x-date-picker class="form-control" wire:model="generated.{{$index}}.ret_arr_time" id="ret_arr_time" name="ret_arr_time" picker="time" />
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
                        <label>Class A</label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.0.seats" />
                    </div>
                    <div class="col">
                        <label>Adult</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.0.adult" />
                    </div>
                    <div class="col">
                        <label>Child</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.0.child" />
                    </div>
                    <div class="col">
                        <label>Infant</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.0.infant" />
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Class B</label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.1.seats" />
                    </div>
                    <div class="col">
                        <label>Adult</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.1.adult" />
                    </div>
                    <div class="col">
                        <label>Child</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.1.child" />
                    </div>
                    <div class="col">
                        <label>Infant</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.1.infant" />
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <label>Class C</label>
                    </div>
                    <div class="col">
                        <label>Seats</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.2.seats" />
                    </div>
                    <div class="col">
                        <label>Adult</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.2.adult" />
                    </div>
                    <div class="col">
                        <label>Child</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.2.child" />
                    </div>
                    <div class="col">
                        <label>Infant</label>
                        <input required type="number" class="form-control" wire:model.defer="generated.{{$index}}.prices.2.infant" />
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Additional Info</h6>
                        <hr/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="contract">Contract</label>
                        <x-select-list class="form-control" id="contract" name="contract" :options="$this->listsForFields['contract']" wire:model="generated.{{$index}}.contract_id" />
                    </div>
                    <div class="col">
                        <label for="baggage_allowance">Baggage Allowance</label>
                        <input class="form-control" type="text" name="baggage_allowance" id="baggage_allowance" wire:model.defer="generated.{{$index}}.baggage_allowance">
                    </div>
                    <div class="col">
                        <label for="oneway_supplements">{{ trans('cruds.flight.fields.oneway_supplements') }}</label>
                        <input class="form-control" type="number" name="oneway_supplements" id="oneway_supplements" wire:model.defer="generated.{{$index}}.oneway_supplements" step="1">
                    </div>
                    <div class="col">
                        <label for="multi_supplements">{{ trans('cruds.flight.fields.multi_supplements') }}</label>
                        <input class="form-control" type="number" name="multi_supplements" id="multi_supplements" wire:model.defer="generated.{{$index}}.multi_supplements" step="1">
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label" for="active">Status</label>
                            <x-select-list required class="form-control" id="active" name="active" wire:model="generated.{{$index}}.active" :options="['1' => 'Active', '0' => 'Inactive']" />
                        </div>
                    </div>
                </div>
                @endforeach
            <div class="row">
                <div class="col-12">
                    <div class="col d-flex align-items-end justify-content-end">
                        <div>
                            <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button wire:click="save" class="btn btn-success mr-2" type="button">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
