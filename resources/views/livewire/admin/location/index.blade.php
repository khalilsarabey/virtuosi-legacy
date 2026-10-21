<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Locations
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.locations.create') }}">
                    Add Location
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-md-2">
                    <label>Per page:</label>
                    <select wire:model.live="perPage" class="form-control">
                        @foreach($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 ml-auto">
                    <label>Search:</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th>
                                {{ trans('cruds.location.fields.airport') }}
                                @include('components.table.sort', ['field' => 'airport'])
                            </th>
                            <th>
                                Airport Code
                                @include('components.table.sort', ['field' => 'airport_code'])
                            </th>
                            <th>
                                City
                                @include('components.table.sort', ['field' => 'city'])
                            </th>
                            <th>
                                Country
                                @include('components.table.sort', ['field' => 'country'])
                            </th>
                            <th class="text-center">
                                Status
                                @include('components.table.sort', ['field' => 'active'])
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($locations as $location)
                            <tr>
                                <td>
                                    {{ $location->airport }}
                                </td>
                                <td>
                                    {{ $location->airport_code }}
                                </td>
                                <td>
                                    {{ $location->city }}
                                </td>
                                <td>
                                    {{ $location->country }}
                                </td>
                                <td class="text-center">
                                    {!! $location->active ? '<span class="text-success font-bold">Active</span>' : '<span class="text-danger font-bold">Inactive</span>' !!}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                            <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.locations.edit', $location) }}">
                                                Edit
                                            </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No entries found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="card-body">
        <div class="pt-3">
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $locations->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush
