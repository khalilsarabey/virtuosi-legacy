<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Airports ({{ $section->title }})
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                @include('components.admin-auto-pricing-menu')
            </div>
        </div>
        <div class="container-fluid">
            <div class="row mt-1">
                <div class="col-10 d-flex">
                    <button onclick="exportReportToExcel(this)" type="button" class="mt-auto btn btn-sm btn-success disabled:opacity-50 disabled:cursor-not-allowed" >
                        Export to Excel
                    </button>
                </div>
                <div class="col">
                    <label>Search:</label>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control"/>
                </div>
            </div>
            <hr/>
            <div class="row my-3">
                <div class="col-md-2">
                    <label>Per page:</label>
                    <select wire:model="perPage" class="form-control">
                        @foreach($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Assign to Destination</label>
                    <select class="form-control" wire:model="destination">
                        <option selected>Select Destination</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end mb-1">
                    <button wire:click="assign" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                        Assign
                    </button>
                </div>
                <div class="col-md-2 ml-auto d-flex align-items-end justify-content-end mb-2">
                    <button type="button" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" data-bs-toggle="modal" data-bs-target="#createModal">
                        Add Airport
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="airports-table" class="table table-index w-full">
                        <thead>
                        <tr>
                            <th>
                                Code
                            </th>
                            <th>
                                Destination
                            </th>
                            <th data-exclude="true"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($airports as $airport)
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="form-check-inline" wire:model.defer="selected" value="{{ $airport->id }}" />
                                        {{ $airport->code }}
                                    </label>
                                </td>
                                <td>
                                    <label class="text-primary">
                                        {{ $airport->destination?->name ?? '-' }}
                                    </label>
                                </td>
                                <td data-exclude="true">
                                    <div class="d-flex justify-content-end align-items-center mt-2">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-xs btn-success mr-2" wire:click="edit({{ $airport->id }})">
                                            Edit
                                        </button>
                                        <button class="btn btn-xs btn-danger mr-2" type="button"
                                                wire:click="confirm('delete', {{ $airport->id }})"
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
                    {{ $airports->links() }}
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="createModalLabel">New Airport</h5>
                    <button type="button" class="btn-close border-0 bg-white" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <label>Airport Code</label>
                                <input type="text" class="form-control" placeholder="Airport Code" wire:model.defer="airport_name"  />
                            </div>
                            <div class="col-md-12">
                                <label>Assign to Destination</label>
                                <select class="form-control" wire:model.defer="selected_destination">
                                    <option selected>Select Destination</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="add" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="createModalLabel">Edit Airport</h5>
                    <button type="button" class="btn-close border-0 bg-white" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <label>Airport Code</label>
                                <input type="text" class="form-control" placeholder="Airport Code" wire:model.defer="airport_name"  />
                            </div>
                            <div class="col-md-12">
                                <label>Assign to Destination</label>
                                <select class="form-control" wire:model.defer="selected_destination">
                                    <option selected>Select Destination</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="update" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script>
        function exportReportToExcel() {
            let table = document.getElementsByTagName("table");
            TableToExcel.convert(table[0], {
                name: `airports-export.xlsx`,
                sheet: {
                    name: 'Sheet 1'
                }
            });
        }
    </script>
@endpush
