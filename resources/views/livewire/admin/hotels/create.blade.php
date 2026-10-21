<div>
    <x-hotels-menu menu="hotels" />
    <div class="container-fluid">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="row">
                <div class="col-md-9">
                    <div class="p-4 admin-container shadow">
                        <div class="border-bottom-blueGray-200 w-100">
                            <div class="d-flex justify-content-between">
                                <h6 class="header-card-title">
                                    New Hotel
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="{{ $errors->has('hotel.name') ? 'text-danger' : '' }}" for="name">Name</label>
                                        <input type="text" name="name" wire:model.defer="hotel.name" class="form-control" required />
                                        <div class="validation-message">
                                            {{ $errors->first('hotel.name') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mt-4">
                                        <label class="{{ $errors->has('hotel.rating') ? 'text-danger' : '' }}" for="rating">Rating</label>
                                        <select wire:model="hotel.rating" class="form-control">
                                            <option value="0">Not Applicable</option>
                                            <option value="1">1 Star</option>
                                            <option value="2">2 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="5">5 Stars</option>
                                        </select>
                                        <div class="validation-message">
                                            {{ $errors->first('hotel.rating') }}
                                        </div>
                                    </div>
                                    <div class="col mt-4">
                                        <label>Check in time</label>
                                        <x-date-picker required class="form-control" required wire:model.defer="hotel.check_in" id="check_in" name="check_in" picker="time" />
                                    </div>
                                    <div class="col mt-4">
                                        <label>Check out time</label>
                                        <x-date-picker required class="form-control" required wire:model.defer="hotel.check_out" id="check_out" name="check_out" picker="time" />
                                    </div>
                                    <div class="col mt-4">
                                        <label class="{{ $errors->has('location') ? 'text-danger' : '' }}">City</label>
                                        <x-select-list required class="form-control" id="location" name="location" wire:model="hotel.location" :options="$locations" />
                                        <div class="validation-message">
                                            {{ $errors->first('hotel.location') }}
                                        </div>
                                    </div>
                                </div>
                                <div wire:ignore class="row mt-4">
                                    <div class="col-md-2 my-4">
                                        <div class="form-check mb-2">
                                            <input onchange="selectExtra('bb', 'bb_value')" class="form-check-input mt-0" value="1" type="checkbox" id="bb" wire:model.defer="extras.bb">
                                            <label class="form-check-label" for="bb">
                                                B/B
                                            </label>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-right-0">$</span>
                                            <input type="number" id="bb_value" class="form-control border-left-0" wire:model.defer="hotel.bb" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2 my-4">
                                        <div class="form-check mb-2">
                                            <input onchange="selectExtra('hb', 'hb_value')" class="form-check-input mt-0" value="1" type="checkbox" id="hb" wire:model.defer="extras.hb">
                                            <label class="form-check-label" for="hb">
                                                H/B
                                            </label>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-right-0">$</span>
                                            <input type="number" id="hb_value" class="form-control border-left-0" wire:model.defer="hotel.hb" disabled>
                                        </div>
                                        </div>
                                    <div class="col-md-2 my-4">
                                        <div class="form-check mb-2">
                                                        <input onchange="selectExtra('fb', 'fb_value')" class="form-check-input mt-0" value="1" type="checkbox" id="fb" wire:model.defer="extras.fb">
                                                        <label class="form-check-label" for="fb">
                                                            F/B
                                                        </label>
                                                    </div>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-right-0">$</span>
                                            <input type="number" id="fb_value" class="form-control border-left-0" wire:model.defer="hotel.fb" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2 my-4">
                                        <div class="form-check mb-2">
                                            <input onchange="selectExtra('ai', 'ai_value')"  class="form-check-input mt-0" value="1" type="checkbox" id="ai" wire:model.defer="extras.ai">
                                            <label class="form-check-label" for="ai">
                                                A/I
                                            </label>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-right-0">$</span>
                                            <input type="number" id="ai_value" class="form-control border-left-0" wire:model.defer="hotel.ai" disabled>
                                        </div>
                                        </div>
                                    <div class="col-md-2 my-4">
                                        <div class="form-check mb-2">
                                            <input onchange="selectExtra('sai', 'sai_value')"  class="form-check-input mt-0" value="1" type="checkbox" id="sai" wire:model.defer="extras.sai">
                                            <label class="form-check-label" for="sai">
                                                SA/I
                                            </label>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-right-0">$</span>
                                            <input type="number" id="sai_value" class="form-control border-left-0" wire:model.defer="hotel.sai" disabled>
                                        </div>
                                        </div>
                                    <div class="col-md-2 my-4">
                                        <div class="form-check mb-2">
                                            <input onchange="selectExtra('uai', 'uai_value')"  class="form-check-input mt-0" value="1" type="checkbox" id="uai" wire:model.defer="extras.uai">
                                            <label class="form-check-label" for="uai">
                                                UA/I
                                            </label>
                                        </div>
                                        <div class="input-group">
                                                    <span class="input-group-text bg-white border-right-0">$</span>
                                                    <input id="uai_value" type="number" class="form-control border-left-0" wire:model.defer="hotel.uai" disabled>
                                                </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-12 mt-4">
                                            <label class="{{ $errors->has('address') ? 'text-danger' : '' }}">Address</label>
                                            <textarea rows="5   " required class="form-control" id="address" name="address" wire:model.defer="hotel.address"></textarea>
                                            <div class="validation-message">
                                                {{ $errors->first('hotel.location') }}
                                            </div>
                                        </div>
                                    <div class="col-12">
                                            <label class="mt-4 {{ $errors->has('hotel.description') ? 'text-danger' : '' }}" for="description">Description</label>
                                            <div class="mt-2 bg-white" wire:ignore>
                                                <div
                                                    style="min-height: 300px;"
                                                    x-data
                                                    x-ref="quillEditor"
                                                    x-init="
                                                        quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                                        quill.on('text-change', function () {
                                                        $dispatch('input', quill.root.innerHTML);
                                                        });
                                                    "
                                                    wire:model.debounce.500ms="hotel.description"
                                                >
                                                    {!! $hotel->description !!}
                                                </div>
                                            </div>
                                        </div>
                                    <div class="col-12 mt-4">
                                        <label class="form-label" for="gallery">Gallery</label>
                                        <div class="text-danger font-size-13">In order to keep a nicer UI, please upload 7 pictures.</div>
                                        <x-dropzone id="gallery" name="gallery" action="{{ route('admin.hotels.storeMedia') }}" collection-name="hotel_gallery" max-file-size="2" max-width="4096" max-height="4096" max-files="20" />
                                        <div class="validation-message">
                                            {{ $errors->first('mediaCollections.gallery') }}
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label>HIDE FROM THE FOLLOWING USERS & AGENCIES</label>
                                        <x-select-list buttons multiple class="form-control" id="agencies_permissions" name="agencies_permissions" :options="$users" wire:model="hotel.agencies_permissions" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmation" wire:model="hotel.confirmation" value="1">
                                        <label class="form-check-label mt-0 mb-1 pt-1" for="confirmation">
                                            Booking requires confirmation
                                        </label>
                                    </div>
                                </div>
                                    <div class="col d-flex align-items-end justify-content-end">
                                        <div>
                                            <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
                                                Cancel
                                            </a>
                                            <button class="btn btn-primary" type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                            </div>
                    </div>
                </div>
                <div class="col-md-3 pl-0">
                    <div class="py-2 px-3 admin-container border-0 shadow">
                        <div class="pb-3 w-100">
                            <div>
                                <label class="{{ $errors->has('active') ? 'text-danger' : '' }}">Status</label>
                                <x-select-list required class="form-control" id="active" name="active" wire:model="hotel.active" :options="['1' => 'Active', '0' => 'Inactive']" />
                                <div class="validation-message">
                                    {{ $errors->first('hotel.active') }}
                                </div>
                            </div>
                            <label class="form-label" for="logo">Featured Image</label>
                            <x-dropzone id="logo" name="logo" action="{{ route('admin.hotels.storeMedia') }}" collection-name="hotel_logo" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
                            <div class="validation-message">
                                {{ $errors->first('mediaCollections.logo') }}
                            </div>
                            @foreach($features as $feature)
                                <div class="card mt-3">
                                    <div class="card-header bg-white text-dark font-size-14 text-black text-uppercase py-2"><strong>{{ $feature->title }}</strong></div>
                                    <div class="card-body">
                                        <div class="terms-scrollable">
                                            @foreach($feature->children as $child)
                                                <div class="form-check">
                                                    <input class="form-check-input" wire:key="{{$child}}" wire:model.defer="selectedFeatures" value="{{ $child->id }}" type="checkbox" id="{{ $child->id }}">
                                                    <label class="form-check-label mt-0 mb-1 pt-1" for="{{ $child->id }}">
                                                        {{ $child->title }}
                                                    </label>
                                                </div>
                                            @endforeach
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

</div>
@push('scripts')
    <link href="https://cdn.quilljs.com/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/quill.js"></script>
    <script>
        function selectExtra(checkboxField, inputField)
        {
            let x = document.getElementById(checkboxField).checked;
            if (x) {
                document.getElementById(inputField).disabled    = false;
                document.getElementById(inputField).value       = 0;
            } else {
                document.getElementById(inputField).disabled    = true;
                document.getElementById(inputField).value       = '';
            }
        }
    </script>
@endpush
