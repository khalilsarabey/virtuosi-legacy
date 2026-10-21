<div>
    <x-hotels-menu menu="hotels" />
    <form wire:submit="save">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9 pr-0">
                    <div class="mx-3 p-4 admin-container shadow">
                        <div class="border-bottom-blueGray-200 w-100">
                            <div class="d-flex justify-content-between">
                                <h6 class="header-card-title">
                                    Add Room
                                    <div wire:loading.delay>
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden"></span>
                                        </div>
                                    </div>
                                </h6>
                            </div>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row mt-3 pb-3">
                            <div class="col">
                                <label>Room Type</label>
                                <x-select-list class="form-control" required id="roomTypes" name="roomTypes"
                                               wire:model.live="room.room_type_id"
                                               :options="$this->listsForFields['roomTypes']"/>
                            </div>
                            <div class="col">
                                <label>Availability</label>
                                <x-flatpickr from="{{ $room['start_date'] ?? '' }}" to="{{ $room['end_date'] ?? '' }}"
                                             mode="range" months="2" min="{{ $room['start_date'] ?? '' }}"
                                             wire:model.live="availability" required class="form-control"
                                             id="room_start_date" wire:model.live="available" name="room_start_date"
                                             picker="date" />
                            </div>
                            <div class="col">
                                <label>Number of Units</label>
                                <input type="number" required min="1" class="form-control" wire:model="room.units"/>
                            </div>
                            <div class="col">
                                <label>Status</label>
                                <select wire:model.live="room.active" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase font-bold font-size-14">Rules</h6>
                                    </div>
                                </div>
                                <hr/>
                                <p class="text-info font-size-13">
                                    <strong>Price per Person: </strong> Enter the price for one person, and it will be
                                    automatically multiplied by the total number of people staying in the room.
                                    <br/>
                                    <strong>Price for Total Number of People: </strong>
                                    Specify the prices for different occupant counts (e.g., 1 adult: $120, 2 adults:
                                    $160, 3 adults: $220, etc.).
                                    <br/>
                                    <strong>Price for Each Person: </strong>
                                    Define the prices for each person separately, and the system will apply the
                                    corresponding price for each person staying in the room.
                                </p>
                                <hr/>
                            </div>
                        </div>
                        <div class="row pb-4">
                            <div class="col-md-3 d-flex align-items-center">
                                <label>Adult</label>
                            </div>
                            <div class="col-md-3">
                                <label>Dynamic Pricing</label>
                                <select required wire:model.live="rule.adult.pricing" class="form-control form-control-sm">
                                    <option value="fixed">Price per Person</option>
                                    <option value="total">Price for Total People</option>
                                    <option value="each">Price for Each Person</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Capacity</label>
                                <select required class="form-control form-control-sm" wire:model.live="rule.adult.capacity">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Extra Beds</label>
                                <select wire:model.live="rule.adult.extra_beds" class="form-control form-control-sm">
                                    <option value="">None</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                        </div>

                        <div wire:key="child_enabled" class="row pb-4">
                                <div class="col-md-3 d-flex align-items-center">
                                    <label>Child</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Dynamic Pricing</label>
                                    <select wire:model.live="rule.child.pricing" class="form-control form-control-sm" required>
                                        <option value="fixed">Price per Person</option>
                                        <option value="total">Price for Total People</option>
                                        <option value="each">Price for Each Person</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Capacity</label>
                                    <select class="form-control form-control-sm" wire:model.live="rule.child.capacity" required>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Extra Beds</label>
                                    <select wire:model.live="rule.child.extra_beds" class="form-control form-control-sm">
                                        <option value="">None</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                            </div>

                        <div wire:key="infants_enabled" class="row pb-4">
                                <div class="col-md-3  d-flex align-items-center">
                                    <label>Infant</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Charge</label>
                                    <select wire:model.live="rule.infant.pricing" class="form-control form-control-sm" required>
                                        <option value="free">Free</option>
                                        <option value="fixed">Price per Person</option>
                                        <option value="total">Price for Total People</option>
                                        <option value="each">Price for Each Person</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Capacity</label>
                                    <select class="form-control form-control-sm" wire:model.live="rule.infant.capacity" required>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>
                                </div>
                            </div>

                        <div wire:key="prices">
                            <div wire:ignore class="row pb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center border-bottom border-top">
                                        <h6 class="text-uppercase py-4 mt-3 font-bold font-size-14">Prices</h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-3" wire:click="addPrice">Add Price</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @forelse($prices as $key => $price)
                            <div class="bg-light px-3 pb-3" wire:key="{{ $key }}">
                                <div class="row">
                                    <div class="col-11">
                                        <label>Date</label>
                                        <x-flatpickr wire:model.live="prices.{{$key}}.date"
                                                     mode="range"
                                                     months="3"
                                                     min="{{ isset($prices[$key - 1]['end_date']) ? \Carbon\Carbon::createFromFormat('d-m-Y', $prices[$key - 1]['end_date'])->addDay()->format('d-m-Y') : $room['start_date'] }}"
                                                     max="{{ $room['end_date'] ?? '' }}"
                                                     required
                                                     class="form-control"
                                                     picker="date"/>
                                    </div>
                                    <div class="col-md-1 d-flex justify-content-end align-items-end mb-2">
                                        <button wire:click="deletePrice({{ $key }})" type="button" class="btn btn-xs btn-danger">Delete</button>
                                    </div>
                                </div>
                                <div class="row">
                                    @if($rule['adult']['pricing'] == 'fixed')
                                        <div class="col-md-2">
                                            <label>Adult</label>
                                            <input required type="text" wire:model="prices.{{$key}}.adult_1" class="form-control"/>
                                        </div>
                                    @endif

                                    @if($rule['adult']['pricing'] == 'total')
                                        @for($i = 0; $i < $rule['adult']['capacity']; $i++)
                                            <div class="col-md-2">
                                                <label>{{ numberToWords($i+1) }} Adult{{ $i > 0 ? 's' : '' }}</label>
                                                <input required type="text" wire:model="prices.{{$key}}.adult_{{  $i+1 }}" class="form-control"/>
                                            </div>
                                        @endfor
                                    @endif

                                    @if($rule['adult']['pricing'] == 'each')
                                        @for($i = 0; $i < $rule['adult']['capacity']; $i++)
                                            <div class="col-md-2">
                                                <label>{{ numberToWords($i+1, true) }} Adult</label>
                                                <input required type="text"
                                                       wire:model="prices.{{$key}}.adult_{{  $i+1 }}"
                                                       class="form-control"/>
                                            </div>
                                        @endfor
                                    @endif


                                        @if($rule['child']['pricing'] == 'fixed')
                                            <div class="col-md-2">
                                                <label>Child</label>
                                                <input required type="text" wire:model="prices.{{$key}}.child_1"
                                                       class="form-control"/>
                                            </div>
                                        @endif

                                        @if($rule['child']['pricing'] == 'total')
                                            @for($i = 0; $i < $rule['child']['capacity']; $i++)
                                                <div class="col-md-2">
                                                    <label>{{ numberToWords($i+1) }}
                                                        Child{{ $i > 0 ? 'ren' : '' }}</label>
                                                    <input required type="text"
                                                           wire:model="prices.{{$key}}.child_{{  $i+1 }}"
                                                           class="form-control"/>
                                                </div>
                                            @endfor
                                        @endif

                                        @if($rule['child']['pricing'] == 'each')
                                            @for($i = 0; $i < $rule['child']['capacity']; $i++)
                                                <div class="col-md-2">
                                                    <label>{{ numberToWords($i+1, true) }} Child</label>
                                                    <input required type="text"
                                                           wire:model="prices.{{$key}}.child_{{  $i+1 }}"
                                                           class="form-control"/>
                                                </div>
                                            @endfor
                                        @endif

                                    @if($rule['infant']['pricing'] !== 'free')
                                        @if($rule['infant']['pricing'] == 'fixed')
                                                <div class="col-md-2">
                                                    <label>Infant</label>
                                                    <input required type="text" wire:model="prices.{{$key}}.infant_1"
                                                           class="form-control"/>
                                                </div>
                                            @endif

                                        @if($rule['infant']['pricing'] == 'total')
                                                @for($i = 0; $i < $rule['infant']['capacity']; $i++)
                                                    <div class="col-md-2">
                                                        <label>{{ numberToWords($i+1) }}
                                                            Infant{{ $i > 0 ? 's' : '' }}</label>
                                                        <input required type="text"
                                                               wire:model="prices.{{$key}}.infant_{{  $i+1 }}"
                                                               class="form-control"/>
                                                    </div>
                                                @endfor
                                            @endif

                                        @if($rule['infant']['pricing'] == 'each')
                                                @for($i = 0; $i < $rule['infant']['capacity']; $i++)
                                                    <div class="col-md-2">
                                                        <label>{{ numberToWords($i+1, true) }} Infant</label>
                                                        <input required type="text"
                                                               wire:model="prices.{{$key}}.infant_{{  $i+1 }}"
                                                               class="form-control"/>
                                                    </div>
                                                @endfor
                                            @endif
                                    @endif

                                </div>
                            </div>
                            <hr class="m-0"/>
                        @empty
                            <div class="text-center font-size-13 text-danger">You haven't added any prices</div>
                        @endforelse

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label" for="images">Room Images</label>
                                <x-dropzone id="images" name="images" action="{{ route('admin.rooms.storeMedia') }}"
                                            collection-name="room_gallery" max-file-size="2" max-width="4096"
                                            max-height="4096" max-files="1"/>
                                <div class="validation-message">
                                    {{ $errors->first('mediaCollections.room_gallery') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-right">
                                <button class="btn btn-primary mt-4">Save Room</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 pl-0">
                    <div class="mx-3 p-4 admin-container shadow">
                        @foreach($features as $feature)
                            <div class="py-2">
                                <div class="pb-3">
                                    <div class="card">
                                        <div class="card-header bg-white text-dark font-size-14 text-black text-uppercase py-2">
                                            <strong>{{ $feature->title }}</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="terms-scrollable">
                                                @foreach($feature->children as $child)
                                                    <div class="form-check">
                                                        <input class="form-check-input" wire:model.live="selectedFeatures"
                                                               value="{{ $child->id }}" type="checkbox"
                                                               id="{{ $child->id }}">
                                                        <label class="form-check-label mt-0 mb-1 pt-1"
                                                               for="{{ $child->id }}">
                                                            {{ $child->title }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
