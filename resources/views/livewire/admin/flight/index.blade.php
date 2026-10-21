<div>
    <div wire:ignore.self class="modal fade" id="pricesModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="pricesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <form wire:submit.prevent="savePrices">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger"><strong>Warning:</strong> If you don't want to change a value, leave the field blank. <br/>Entering any number will change the value to that number, even if it's a zero.</div>
                                    <div class="my-3 alert alert-danger">When changing number of seats, make sure the number of seats is greater than or equal to the number of booked seats for each class.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                    <label>Update Class L</label>
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Seats</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.L.seats">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.L.adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.L.child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.L.infant">
                                </div>
                            </div>
                            @if(config('features.hotels'))
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                </div>
                                <div class="col-2 col-lg">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.L.package_adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.L.package_child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.L.package_infant">
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                    <label>Update Class M</label>
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Seats</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.M.seats">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.M.adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.M.child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.M.infant">
                                </div>
                            </div>
                            @if(config('features.hotels'))
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                </div>
                                <div class="col-2 col-lg">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.M.package_adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.M.package_child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.M.package_infant">
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                    <label>Update Class Y</label>
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Seats</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.Y.seats">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.Y.adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.Y.child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.Y.infant">
                                </div>
                            </div>
                            @if(config('features.hotels'))
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                </div>
                                <div class="col-2 col-lg">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.Y.package_adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.Y.package_child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.prices.Y.package_infant">
                                </div>
                            </div>
                            @endif
                            <hr/>
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                    <label>Update Class Z</label>
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Seats</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.Z.seats">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.Z.adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.Z.child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.Z.infant">
                                </div>
                            </div>
                            @if(config('features.hotels'))
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                </div>
                                <div class="col-2 col-lg">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.Z.package_adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.Z.package_child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.Z.package_infant">
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                    <label>Update Class K</label>
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Seats</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.K.seats">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.K.adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.K.child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.K.infant">
                                </div>
                            </div>
                            @if(config('features.hotels'))
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                </div>
                                <div class="col-2 col-lg">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.K.package_adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.K.package_child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.K.package_infant">
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                    <label>Update Class J</label>
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Seats</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.J.seats">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.J.adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.J.child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.J.infant">
                                </div>
                            </div>
                            @if(config('features.hotels'))
                            <div class="row">
                                <div class="col-2 col-lg d-flex align-items-center">
                                </div>
                                <div class="col-2 col-lg">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Adult Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.J.package_adult">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Child Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.J.package_child">
                                </div>
                                <div class="col-2 col-lg">
                                    <label>Package Infant Price</label>
                                    <input type="number" class="form-control" wire:model.defer="pricing.business_pricing.J.package_infant">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary close-modal" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="supplementsModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="supplementsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <form wire:submit.prevent="saveSupplements">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger"><strong>Warning:</strong> If you don't want to change a value, leave the field blank. <br/>Entering any number will change the value to that number, even if it's a zero.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label>One Way Supplements</label>
                                    <input type="number" class="form-control" wire:model.defer="supplements.oneway_supplements">
                                </div>
                                <div class="col">
                                    <label>Multi Supplements</label>
                                    <input type="number" class="form-control" wire:model.defer="supplements.multi_supplements">
                                </div>
                                <div class="col">
                                    <label>Mixed Airlines Supplements</label>
                                    <input type="number" class="form-control" wire:model.defer="supplements.combinable_supplements">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary close-modal" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="categoriesModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            @foreach($categories as $category)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" value="{{ $category->id }}" wire:model.defer="selectedCategories" type="checkbox" id="categoryCheckbox-{{ $category->id }}">
                                    <label class="form-check-label pt-1" for="categoryCheckbox-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary close-modal" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="saveCategories()" class="btn btn-sm btn-success">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Flights
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                    {{ $synchronizing ? 'Synchronizing Flight Dates With Bookings, Please do not close or refresh the page...' : '' }}
                </h6>
                <div>
                <a class="btn btn-xs px-4 py-2 btn-info uppercase waves-effect" href="{{ route('admin.flights.generate') }}">
                    Generate Flights
                </a>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.flights.create') }}">
                    Add Flight
                </a>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row mt-3">
                <div class="col-md-2 mt-3">
                    <label>Flight Type</label>
                    <x-select-list class="form-control" id="type" name="type" :options="$types" wire:model="type" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Airline</label>
                    <x-select-list class="form-control" id="airline_id" name="airline_id" :options="$airlines" wire:model="airline_id" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>From</label>
                    <x-select-list class="form-control" id="from_id" name="from_id" :options="$locations" wire:model="dep_from_code" />
                </div>
                <div class="col-md-2 mt-3" wire:ignore>
                    <label>To</label>
                    <x-select-list class="form-control" id="to_id" name="to_id" :options="$locations" wire:model="dep_to_code" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Departure Date</label>
                    <input type="date" name="" class="form-control" wire:model="dep_date" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Return Date</label>
                    <input {{ $type == 'oneway' ? 'disabled' : '' }} type="date" name="" class="form-control" wire:model="ret_dep_date" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Departure Flight Number</label>
                    <input type="text" class="form-control" wire:model="dep_flight_number" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Return Flight Number</label>
                    <input {{ $type == 'oneway' ? 'disabled' : '' }}  type="text" class="form-control" wire:model="ret_flight_number" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>From Departure Date</label>
                    <input type="date" name="" class="form-control" wire:model="range_from_date" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>To Departure Date</label>
                    <input type="date" name="" class="form-control" wire:model="range_to_date" />
                </div>
                <div class="col-md-2 mt-3 d-flex align-items-center justify-content-start">
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" wire:model="available_seats" type="checkbox" value="" id="flexCheckChecked">
                            <label class="form-check-label pt-1" for="flexCheckChecked">
                                Flights with available seats
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <button onclick="exportReportToExcel(this)" type="button" class="btn btn-sm btn-primary disabled:opacity-50 disabled:cursor-not-allowed" >
                        Export Excel
                    </button>
                </div>
            </div>
            <div class="row my-3 border-top">
                <div class="col-md-7 d-flex justify-content-start align-items-end">
                    <label class="mr-3">
                        <input type="checkbox" wire:model="selectAll" value="1" /> Select All
                    </label>
                    @if($this->selectedCount)
                        <div class="bd-example">
                            <button type="button" class="btn btn-xs btn-danger" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled">Delete Selected</button>
                            <button type="button" class="btn btn-xs btn-success" wire:click="updatePrices()" data-bs-toggle="modal" data-bs-target="#pricesModal" wire:loading.attr="disabled">Update Prices</button>
                            <button type="button" class="btn btn-xs btn-warning" wire:click="updateSupplements()" data-bs-toggle="modal" data-bs-target="#supplementsModal" wire:loading.attr="disabled">Update Supplements</button>
                            <button type="button" class="btn btn-xs btn-info" data-bs-toggle="modal" data-bs-target="#categoriesModal"  wire:loading.attr="disabled">Add to Categories</button>
                            <button type="button" class="btn btn-xs btn-dark" wire:loading.attr="disabled" wire:click="activate()">Activate</button>
                            <button type="button" class="btn btn-xs btn-secondary" wire:loading.attr="disabled" wire:click="deactivate()">Deactivate</button>
                        </div>
                    @endif
                </div>
                <div class="ml-auto col-md-1 mt-3">
                    <label>Per Page</label>
                    <select wire:model="perPage" class="form-control form-control-sm w-100">
                        @foreach($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                        <option value="1000000">All</option>
                    </select>
                </div>
                <div class="col-md-2 mt-3">
                    <label>Sort By</label>
                    <select wire:model="sortBy" class="form-control form-control-sm w-100">
                        <option value="dep_date">Departure</option>
                        <option value="created_at">Date Added</option>
                        <option value="active">Status</option>
                    </select>
                </div>
                <div class="col-md-2 mt-3">
                    <label>Sort Direction</label>
                    <select wire:model="sortDirection" class="form-control form-control-sm w-100">
                        <option value="ASC">Asc</option>
                        <option value="DESC">Desc</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="export" style="display:none">
                        <thead>
                        <tr>
                            <th>
                                Airline
                            </th>
                            <th>
                                Departure
                            </th>
                            <th>
                                Departure Date
                            </th>
                            <th>
                                Remaining Seats
                            </th>
                            <th>
                                Price
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($flights as $flight)
                            <tr>
                                <td>
                                    {{ $flight->airline->name ?? '' }}
                                </td>
                                <td>
                                    {{ $flight->dep_from_code ?? '' }}-{{ $flight->dep_to_code ?? '' }} @if($flight->type == 'fixed') (Fixed) @endif
                                </td>
                                <td>
                                    {{ $flight->dep_date }}
                                </td>
                                <td>
                                    {{ (int) $flight->seats }} / <span> {{ (int) $flight->seats - $flight->tickets_count }}
                                </td>
                                <td>
                                    <b>Adult: </b>
                                    <?php
                                        $price = getPrice($flight, 'adult');
                                        ?>
                                    <div class="text-success">${{ is_numeric($price) ? $price : 'na' }}</div>
                                    <strong>Child: </strong>
                                        <?php
                                        $price = getPrice($flight, 'child');
                                        ?>
                                    <div class="text-success">${{ is_numeric($price) ? $price : 'na' }}</div>
                                    <strong>Infant: </strong>
                                        <?php
                                        $price = getPrice($flight, 'infant');
                                        ?>
                                    <div class="text-success">${{ is_numeric($price) ? $price : 'na' }}</div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table class="table table-index clickable-table">
                        <tbody>
                        @forelse($flights as $flight)
                            <tr class="toggle" @if($flight->seats - $flight->tickets_count == 0) style="background: rgba(207, 244, 252, .35)" @endif>
                                <td style="width: 1%;" class="align-middle text-xs whitespace-nowrap disable-clickable-td">
                                    <label>
                                        <input type="checkbox" value="{{ $flight->id }}" wire:model="selected" wire:loading.class="opacity-50">
                                    </label>
                                </td>
                                <td style="width: 1%;" class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-sm whitespace-nowrap">
                                    <div class="text-xl font-bold block mb-1 uppercase tracking-wide text-blueGray-600">{{ $flight->type == 'oneway' ? 'One Way': 'Fixed' }}</div>
                                    {!! ($flight->seats - $flight->tickets_count == 0) ? '<div class="badge badge-success text-xs mb-2 pt-1">FULLY BOOKED</div>' : '' !!}
                                    @foreach($flight->category as $k => $v)
                                        <div class="{{ $flight->active == 1 ? 'text-dark' : 'text-danger' }}">{{ $v->name ?? '' }}</div>
                                    @endforeach
                                    <div class="pt-2">{{ $flight->airline->name ?? '' }} - {{ $flight->airline->iata ?? '' }}</div>
                                    {!! $flight->active == 0 ? '<div class="text-danger">Inactive</div>' : '<br/><div class="text-success">Active</div>' !!}
                                    @if($flight->promo)
                                    <div class="badge badge-info text-xs mb-2 pt-1">On Promo</div>
                                    @endif
                                </td>
                                <td style="width: 1%;" class="border-t-0 align-middle border-l-0 border-r-0 whitespace-nowrap">
                                    <h4 class="text-xl font-bold block mb-1 uppercase tracking-wide text-blueGray-600">
                                        {{ $flight->dep_from_code ?? '' }}-{{ $flight->dep_to_code ?? '' }}
                                    </h4>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        <strong>Date: </strong><br/>
                                        {{ $flight->dep_date }}
                                    </div>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        <strong>Time: </strong><br/>{{ $flight->dep_time }} - {{ $flight->dep_arr_time }}
                                    </div>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        <strong>Flight Number: </strong><br/>{{ $flight->dep_flight_number }}
                                    </div>
                                </td>

                                <td style="width: 1%;" class="border-t-0 align-middle border-l-0 border-r-0 whitespace-nowrap py-4">
                                    @if($flight->type == 'fixed')
                                        <div class="text-xl font-bold block mb-1 uppercase tracking-wide text-blueGray-600">
                                            {{ $flight->depTo->airport_code ?? '' }}-{{ $flight->depFrom->airport_code ?? '' }}
                                        </div>
                                        <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                            <strong>Date: </strong><br/>
                                            {{ $flight->ret_dep_date }}
                                        </div>
                                        <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                            <strong>Time: </strong><br/>{{ $flight->ret_dep_time }} - {{ $flight->ret_arr_time }}
                                        </div>
                                        <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                            <strong>Flight Number: </strong><br/>{{ $flight->ret_flight_number }}
                                        </div>
                                    @endif
                                </td>
                                <td style="width: 1%;" class="align-middle text-sm whitespace-nowrap">
                                    @if(config('features.cabins'))
                                        <div class="text-sm mt-0 mb-2 text-blueGray-400 font-bold uppercase">
                                            <div class="text-center mt-2">Current Selling Classes</div>
                                            <hr/>
                                            <div class="w-100 d-flex justify-content-between">
                                                <div class="w-50 mr-4">
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <div>Economy: </div>
                                                            <?php $class = getPrice($flight, 'class') ?>
                                                        <div class="text-success">
                                                            {{ is_string($class) ? $class : 'n/a' }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Adult: </div>
                                                            <?php $price = getPrice($flight, 'adult') ?>
                                                        <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Child: </div>
                                                            <?php $price = getPrice($flight, 'child') ?>
                                                        <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Infant: </div>
                                                            <?php $price = getPrice($flight, 'infant') ?>
                                                        <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Seats: </div>
                                                        <div class="text-success">
                                                            {{ ((int) $flight->seats - (int) $flight->business_seats) }} / <span> {{ ((int) $flight->seats - (int) $flight->business_seats) - $flight->economy_tickets_count }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-50">
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <div>Business: </div>
                                                            <?php $class = getBusinessPrice($flight, 'class') ?>
                                                        <div class="text-success">
                                                            {{ is_string($class) ? $class : 'n/a' }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Adult: </div>
                                                            <?php $price = getBusinessPrice($flight, 'adult') ?>
                                                        <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Child: </div>
                                                            <?php $price = getBusinessPrice($flight, 'child') ?>
                                                        <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Infant: </div>
                                                            <?php $price = getBusinessPrice($flight, 'infant') ?>
                                                        <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Seats: </div>
                                                        <div class="text-success">
                                                            {{ (int) $flight->business_seats }} / <span> {{ (int) $flight->business_seats - $flight->business_tickets_count }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr/>
                                        @if($flight->type == 'oneway')
                                            <div class="d-flex justify-content-between">
                                                <div>Oneway Supp:</div>
                                                <div class="text-success">${{ $flight->oneway_supplements ?? '0' }}</div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div>Multiple Destinations Supp:</div>
                                                <div class="text-success">${{ $flight->multi_supplements ?? '0' }}</div>
                                            </div>
                                        @endif
                                            <div class="d-flex justify-content-between">
                                                <div>Total Seats: </div>
                                                <div class="text-success">
                                                    {{ (int) $flight->seats }} / <span> {{ (int) ($flight->seats) - ($flight->economy_tickets_count + $flight->business_tickets_count) }}</span>
                                                </div>
                                            </div>
                                    </div>
                                    @else
                                        <div class="text-sm mt-0 mb-2 text-blueGray-400 font-bold uppercase">
                                            <div class="d-flex justify-content-between">
                                                <div>Current Economy Class</div>
                                                    <?php $class = getPrice($flight, 'class') ?>
                                                <div class="text-success">
                                                    {{ is_string($class) ? $class : 'n/a' }}
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div>Adult: </div>
                                                    <?php $price = getPrice($flight, 'adult') ?>
                                                <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div>Child: </div>
                                                    <?php $price = getPrice($flight, 'child') ?>
                                                <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div>Infant: </div>
                                                    <?php $price = getPrice($flight, 'infant') ?>
                                                <div class="text-success">${{ is_numeric($price) ? $price : 'n/a' }}</div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div>Seats: </div>
                                                <div class="text-success">
                                                    {{ (int) $flight->seats }} / <span> {{ (int) $flight->seats - $flight->tickets_count }}</span>
                                                </div>
                                            </div>

                                            @if($flight->type == 'oneway')
                                                <div class="d-flex justify-content-between">
                                                    <div>Oneway Supp:</div>
                                                    <div class="text-success">${{ $flight->oneway_supplements ?? '0' }}</div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div>Multiple Destinations Supp:</div>
                                                    <div class="text-success">${{ $flight->multi_supplements ?? '0' }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="vertical-center">
                                    <div class="d-flex flex-column justify-content-center my-auto">
                                        @if(auth()->user()->type == 'admin')
                                            <a href="{{ route('admin.flights.duplicate', $flight->id) }}" class="btn btn-xs btn-info bold text-white mb-2 uppercase">
                                                Duplicate
                                            </a>
                                            <a class="btn btn-xs btn-primary mb-2 uppercase" href="{{ route('admin.flights.edit', $flight) }}">
                                                Edit
                                            </a>
                                            <button type="button" wire:click="confirmSync({{ $flight->id }})" class="btn btn-xs btn-dark mb-2 uppercase">
                                                Synchronize Bookings
                                            </button>
                                            @if($syncFlightId == $flight->id)
                                                <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5)">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Synchronize Bookings</h5>
                                                                <button type="button" class="close" wire:click="cancelSync">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-danger">
                                                                    This action will synchronize all bookings for this flight.<br>
                                                                    This will update the booking dates, flight number and airline.<br>
                                                                    The rest of the bookings details will not be changed.<br><br>
                                                                    Are you sure you want to proceed?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" wire:click="cancelSync">Cancel</button>
                                                                <button type="button" class="btn btn-primary" wire:click="sync({{ $flight->id }})">Synchronize Now</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div wire:target="sync({{ $flight->id }})" wire:loading.class="d-block" class="btn btn-xs btn-light mb-2 uppercase d-none text-danger">
                                                Synchronizing...
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No flights found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="py-3 mb-3">
                @if($this->selectedCount)
                    <p class="text-sm leading-5 mb-4">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                        Flight(s) Selected
                    </p>
                @endif
                {{ $flights->links() }}
                <div class="mt-3">Showing {{ $flights->firstItem() }} to {{ $flights->lastItem() }} of total {{$flights->total()}} entries</div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
            if (!confirm("{{ trans('global.areYouSure') }}")) {
                return
            }
            @this[e.callback](...e.argv)
        })

        window.addEventListener('close-modals', event => {
            $('.close-modal').trigger('click');
        })

    </script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script>
        function exportReportToExcel() {
            let table = document.getElementById("export"); // you can use document.getElementById('tableId') as well by providing id to the table tag
            TableToExcel.convert(table, { // html code may contain multiple tables so here we are refering to 1st table tag
                name: `flights.xlsx`, // fileName you could use any name
                sheet: {
                    name: 'Sheet 1' // sheetName
                }
            });
        }
    </script>
@endpush
