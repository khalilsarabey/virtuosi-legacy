<div>
    <x-hotels-menu menu="hotels" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    {{ $hotel->name }}
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a href="{{ route('admin.hotels.add-rooms', $hotel->id) }}" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">Add Rooms</a>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row mt-3">
                <div class="col-md-2 mt-3">
                    <label>Season</label>
                    <x-select-list class="form-control" id="filter_season" name="filter_season" :options="$seasons" wire:model="filter.season" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>From</label>
                    <input type="date" name="filter_from" class="form-control" wire:model="filter.from" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>To</label>
                    <input type="date" name="filter_to" class="form-control" wire:model="filter.to" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Room Type</label>
                    <x-select-list class="form-control" id="filter_room_type" name="filter_room_type" :options="$roomTypes" wire:model="filter.room_type" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Room Status</label>
                    <x-select-list class="form-control" id="filter_room_status" name="filter_room_status" :options="['active' => 'Active', 'inactive' => 'Inactive']" wire:model="filter.room_status" />
                </div>
                <div class="col-md-2 d-flex align-items-end justify-content-end text-right">
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-secondary disabled:opacity-50 disabled:cursor-not-allowed">Clear Filters</button>
                        <button type="button" class="btn btn-sm btn-primary disabled:opacity-50 disabled:cursor-not-allowed">Apply Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@push('scripts')
    <script>
        window.addEventListener('close-modals', event => {
            $('.close-modal').trigger('click');
        })
    </script>
@endpush
