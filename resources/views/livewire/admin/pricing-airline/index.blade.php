<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Pricing Airlines
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect"
                   href="{{ route('admin.settings.auto-pricing.airlines.create') }}">
                    Add Pricing Airline
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-md-8 d-flex justify-content-start align-items-end">

                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-2">
                    <label>Per page:</label>
                    <select wire:model="perPage" class="form-control">
                        @foreach($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 ml-auto">
                    <label>Search:</label>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control"/>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3 ">
                    <div class="d-flex">
                        <select class="form-control" wire:model="category">
                            <option selected>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="ml-2">
                            <button wire:click="assign" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                                Assign
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Category
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($airlines as $airline)
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="form-check-inline" wire:model.defer="selected" value="{{ $airline->id }}" />
                                        {{ $airline->name }}
                                    </label>
                                </td>
                                <td>
                                    <label class="text-primary">
                                    {{ $airline->category?->name ?? '-' }}
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-xs btn-success mr-2"
                                           href="{{ route('admin.settings.auto-pricing.airlines.edit', $airline) }}">
                                            Edit
                                        </a>
                                        <button class="btn btn-xs btn-danger mr-2" type="button"
                                                wire:click="confirm('delete', {{ $airline->id }})"
                                                wire:loading.attr="disabled">
                                            Delete
                                        </button>
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
