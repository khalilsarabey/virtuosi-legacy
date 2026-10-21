<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Airlines
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.airlines.create') }}">
                    Add Airline
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-md-8 d-flex justify-content-start align-items-end">
                    @if($this->selectedCount)
                        <div class="bd-example">
                            <button type="button" class="btn btn-xs btn-danger" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled">Delete Selected</button>
                        </div>
                    @endif
                </div>
            </div>
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
                                Logo
                            </th>
                            <th>
                                Airport
                                @include('components.table.sort', ['field' => 'name'])
                            </th>
                            <th>
                                ICAO
                                @include('components.table.sort', ['field' => 'icao'])
                            </th>
                            <th>
                                Country
                                @include('components.table.sort', ['field' => 'country'])
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($airlines as $airline)
                            <tr>
                                <td>
                                    @if($airline->thumbnail)
                                        <a class="link-photo" href="/{{ $airline->thumbnail }}">
                                            <img src="/airlines/{{ $airline->thumbnail }}" alt="{{ $airline->thumbnail }}" title="{{ $airline->thumbnail }}">
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {{ $airline->name }}
                                </td>
                                <td>
                                    {{ $airline->icao }}
                                </td>
                                <td>
                                    {{ $airline->country }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                            <a class="btn btn-xs btn-success mr-2" href="{{ route('admin.airlines.edit', $airline) }}">
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
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    {{ $airlines->links() }}
                </div>
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
    </script>
@endpush
