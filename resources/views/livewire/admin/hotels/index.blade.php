<div>
    <x-hotels-menu menu="hotels" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Hotels
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div>
                    <a class="btn btn-xs px-4 py-2 btn-outline-primary uppercase waves-effect" href="{{ route('admin.hotels.create') }}">
                        Add Hotel
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-md-2">
                </div>
                <div class="col-md-2">
                    <label>Name</label>
                    <input type="text" name="name" wire:model="filters.name" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Location</label>
                    <x-select-list required class="form-control" id="location" name="location" wire:model.live="filters.location" :options="$locations" />
                </div>
                <div class="col-md-2">
                    <label>Status</label>
                    <x-select-list required class="form-control" id="status" name="status" wire:model.live="filters.status" :options="['1' => 'Active', '0' => 'Inactive']" />
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" wire:click="search" class="btn btn-xs px-4 py-2 btn-secondary uppercase waves-effect">Filter</button>
                </div>
            </div>
            <div class="row">
                <table class="table table-index clickable-table">
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th class="text-center">
                                Location
                            </th>
                            <th class="text-center">
                                Status
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotels as $hotel)
                            <tr>
                                <td>
                                    {{ $hotel->name }}
                                </td>
                                <td class="text-center">
                                    {{ $hotel->city?->city }},{{ $hotel->city?->country_code }},
                                </td>
                                <td class="text-center">
                                    {!! $hotel->active == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' !!}
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.hotels.rooms', $hotel->id) }}">Rooms</a>
                                    <a class="btn btn-xs btn-secondary" href="{{ route('admin.hotels.edit', $hotel->id) }}">
                                        Edit Hotel
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    {{ $hotels->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
