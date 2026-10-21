<div>
    <div wire:ignore.self id="deleteModal" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-danger font-weight-bold text-uppercase">Delete Booking</h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        @if($deleteModal)
                            @if($deleteModal->tickets->count() > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <label class="alert alert-info py-2 px-3 text-xs my-0">Flight(s) Booking</label>
                                    </div>
                                    <div class="col-12">
                                        <table class="table table-index clickable-table">
                                            <tbody>
                                            @foreach($deleteModal->tickets as $key => $passenger)
                                                <tr>
                                                    <td>
                                                        {{ $passenger->first_name }} {{ $passenger->last_name }}
                                                        <br/>
                                                        <div class="text-uppercase font-weight-bold text-xs">{{ $passenger->dep_from_code }} -> {{ $passenger->dep_to_code }}</div>
                                                    </td>
                                                    @if(!$deleteModal->hotel_id)
                                                    <td class="text-right">
                                                        <div x-data="checkboxHandler('{{ $passenger->first_name }}-{{ $passenger->last_name }}-{{ $passenger->date_of_birth }}')" class="form-check">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                x-on:click="toggleCheckbox($event.target)"
                                                                value="{{ $passenger->id }}"
                                                                data-passenger-id="{{ $passenger->first_name }}-{{ $passenger->last_name }}-{{ $passenger->date_of_birth }}"
                                                            >
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if($deleteModal->hotel_id)
                                <div class="row">
                                    <div class="col-12">
                                        <label class="alert alert-info py-2 px-3 text-xs my-0">Hotel Booking</label>
                                    </div>
                                    <div class="col-12">
                                        <table class="table table-index clickable-table">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{ $deleteModal->hotel->name }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if(count($passengersToDelete))
                    <button type="button" wire:click="deleteSelection()" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Delete Selection</button>
                    @else
                    <button type="button" wire:click="deleteBooking()" data-bs-dismiss="modal" class="btn btn-sm btn-danger">Delete Booking</button>
                    @endif
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
                    Charter Bookings List
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row mt-3">
                <div class="col-md-2 mt-3">
                    <label>Flight Type</label>
                    <x-select-list class="form-control w-100" id="type" name="type" :options="$types" wire:model.live="filters.type" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Category</label>
                    <x-select-list class="form-control w-100" id="category" name="category" :options="$categories" wire:model.live="filters.category" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Agency / User</label>
                    <x-select-list class="form-control w-100" id="agencies" name="agencies" :options="$agencies" wire:model.live="filters.user_id" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Search by Name</label>
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="filters.name" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Departure From</label>
                    <x-select-list class="form-control w-100" id="dep_from" name="dep_from" :options="$locations" wire:model.live="filters.dep_from" />
                </div>
                <div class="col-md-2 mt-3" wire:ignore>
                    <label>Departure To</label>
                    <x-select-list class="form-control w-100" id="dep_to" name="dep_to" :options="$locations" wire:model.live="filters.dep_to" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Return From</label>
                    <x-select-list class="form-control w-100" id="ret_from" name="ret_from" :options="$locations" wire:model.live="filters.ret_from" />
                </div>
                <div class="col-md-2 mt-3" wire:ignore>
                    <label>Return To</label>
                    <x-select-list class="form-control w-100" id="ret_to" name="ret_to" :options="$locations" wire:model.live="filters.ret_to" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Departure Date</label>
                    <input type="date" name="dep_date" id="dep_date" class="form-control" wire:model.live="filters.dep_date" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Return Date</label>
                    <input type="date" name="ret_date" id="ret+date" class="form-control" wire:model.live="filters.ret_dep_date" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Departure Flight Number</label>
                    <input type="text" class="form-control" wire:model.live="filters.dep_flight_number" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Return Flight Number</label>
                    <input type="text" class="form-control" wire:model.live="filters.ret_flight_number" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Reference Number</label>
                    <input type="text" class="form-control" wire:model.live="filters.reference" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Booking Date</label>
                    <input type="date" class="form-control" wire:model.live="filters.created_at" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Airline</label>
                    <x-select-list class="form-control w-100" id="airlines" name="airlines" :options="$airlines" wire:model.live="filters.airline" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Include Packages</label>
                    <x-select-list class="form-control w-100" id="packages" name="packages" required :options="['1' => 'Yes', '0' => 'No']" wire:model.live="filters.packages" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Source</label>
                    <x-select-list class="form-control w-100" id="source" name="source" required :options="['local' => 'Local', 'connection' => 'Connections']" wire:model.live="filters.source" />
                </div>
                <div class="col-md-2 mt-3">
                    <label>Show Only</label>
                    <x-select-list class="form-control w-100" id="show_only" name="show_only" required :options="['active' => 'Active Bookings', 'deleted' => 'Deleted Bookings']" wire:model.live="filters.show_only" />
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3 border-top">
                <div class="col d-flex align-items-end">
                    <button onclick="exportReportToExcel(this)" type="button" class="btn btn-xs btn-success disabled:opacity-50 disabled:cursor-not-allowed" >
                        Export Excel
                    </button>
                    @if(config('app.saas_id') == "samadonline")
                    <button onclick="exportReportForSabre()" type="button" class="btn btn-xs btn-primary disabled:opacity-50 disabled:cursor-not-allowed" >
                        Export For Sabre
                    </button>
                    @endif
                </div>
                <div class="ml-auto col-md-1 mt-3">
                    <label>Per Page</label>
                    <select wire:model.live="perPage" class="form-control form-control-sm w-100">
                        @foreach($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                        <option value="1000000">All</option>
                    </select>
                </div>
                <div class="col-md-2 mt-3">
                    <label>Sort By</label>
                    <select wire:model.live="sortBy" class="form-control form-control-sm w-100">
                        <option value="dep_date">Departure</option>
                        <option value="created_at">Date Added</option>
                    </select>
                </div>
                <div class="col-md-1 mt-3">
                    <label>Sort</label>
                    <select wire:model.live="sortDirection" class="form-control form-control-sm w-100">
                        <option value="ASC">Asc</option>
                        <option value="DESC">Desc</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table data-cols-width="25,25,25,25,25,25,25,25,25,25,25,25" class="d-none" id="travel-book-export">
                        <thead>
                            <tr data-height="42.5">
                                <th data-f-bold="true">Airline</th>
                                <th data-f-bold="true">Social Title</th>
                                <th data-f-bold="true">First Name</th>
                                <th data-f-bold="true">Last Name</th>
                                <th data-f-bold="true">Departure</th>
                                <th data-f-bold="true">Departure Date</th>
                                <th data-f-bold="true">Return</th>
                                <th data-f-bold="true">Return Date</th>
                                <th data-f-bold="true">Reference</th>
                                <th data-f-bold="true">Agency</th>
                                <th data-f-bold="true">Date of Birth</th>
                                <th data-f-bold="true">Nationality</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $groupedTickets = $tickets->groupBy(function ($ticket) {
                                    return $ticket->booking_id . '-' . $ticket->first_name . '-' . $ticket->last_name . '-' . $ticket->date_of_birth;
                                });
                            @endphp
                            @foreach($groupedTickets as $k => $ticketGroup)
                                @php $ticket = $ticketGroup->first(); @endphp
                                <tr>
                                    <td>{{ $ticket->flight?->airline?->name }}</td>
                                    <td>{{ $ticket->title }}</td>
                                    <td>{{ $ticket->first_name }}</td>
                                    <td>{{ $ticket->last_name }}</td>
                                    <td>{{ $ticket->booking->dep_from_city }} -> {{ $ticket->booking->dep_to_city }}</td>
                                    <td>{{ $ticket->booking->dep_date }}</td>
                                    <td>
                                        @if(!empty($ticket->booking->ret_from_city))
                                            {{ $ticket->booking->ret_from_city }} -> {{ $ticket->booking->ret_to_city }}
                                        @endif
                                    </td>
                                    <td>{{ $ticket->booking->ret_date }}</td>
                                    <td>{{ $ticket->booking->reference }}</td>
                                    <td>
                                        @if(auth()->user()->type != 'white-admin' && $ticket->booking->agency_id != 1)
                                            {{ $ticket->booking->agency?->name }}
                                        @else
                                            {{ $ticket->booking->user?->name }}
                                        @endif
                                    </td>
                                    <td>{{ $ticket->date_of_birth }}</td>
                                    <td class="text-center">{{ $ticket->nationality }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table id="table" class="table table-index clickable-table">
                        <thead>
                        <tr style="font-size: 90%;">
                            <th data-f-bold="true" class="d-none">
                                Airline
                            </th>
                            <th data-f-bold="true" class="d-none">
                                Social Title
                                @include('components.table.sort', ['field' => 'title'])
                            </th>
                            <th data-exclude="true">
                                Name
                                @include('components.table.sort', ['field' => 'first_name'])
                            </th>
                            <th data-f-bold="true" class="d-none">
                                First Name
                                @include('components.table.sort', ['field' => 'first_name'])
                            </th>
                            <th data-f-bold="true" class="d-none">
                                Last Name
                                @include('components.table.sort', ['field' => 'last_name'])
                            </th>
                            <th data-f-bold="true">
                                Departure
                                @include('components.table.sort', ['field' => 'flight.dep_date'])
                            </th>
                            <th data-f-bold="true" class="d-none">Departure Date</th>
                            <th data-f-bold="true">
                                Return
                                @include('components.table.sort', ['field' => 'flight.arr_time'])
                            </th>
                            <th data-f-bold="true" class="d-none">Return Date</th>
                            <th data-f-bold="true">
                                Reference
                                @include('components.table.sort', ['field' => 'reference'])
                            </th>
                            <th data-f-bold="true">
                                Agency / User
                            </th>
                            <th data-f-bold="true">
                                Date of Birth
                                @include('components.table.sort', ['field' => 'date_of_birth'])
                            </th>
                            <th class="text-center" data-f-bold="true">
                                Nationality
                                @include('components.table.sort', ['field' => 'nationality'])
                            </th>
                            <th class="text-center" data-f-bold="true">
                                Price
                            </th>
                            <th data-exclude="true">
                                Status
                                @include('components.table.sort', ['field' => 'status'])
                            </th>
                            @if(auth()->user()->agency_id == 1)
                            <th data-exclude="true">
                                Source
                            </th>
                            @endif
                            <th data-exclude="true">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr style="font-size: 90%;">
                                <td class="d-none">
                                    {{ $ticket->flight?->airline?->name }}
                                </td>
                                <td style="vertical-align: middle" data-exclude="true">
                                        <strong>{{ $ticket->title }} {{ $ticket->first_name }} {{ $ticket->last_name }}</strong>
                                        <div>Booking Date: {{ \Carbon\Carbon::parse($ticket->booking->created_at)->format('d-M-Y') }}</div>
                                        <div class="text-success">{{ ucfirst($ticket->type) }}</div>
                                        @if($ticket->booking->hotel_id || !empty($ticket->booking->hotel_name))
                                            <div class="text-danger">Includes a hotel</div>
                                        @endif
                                        {{ $ticket->flight?->airline?->name }}<br/>
                                    </td>
                                <td class="d-none">
                                        <strong>{{ $ticket->title }}</strong>
                                    </td>
                                <td class="d-none">
                                        <strong>{{ $ticket->first_name }}</strong>
                                    </td>
                                <td class="d-none">
                                        <strong>{{ $ticket->last_name }}</strong>
                                    </td>
                                <td style="vertical-align: middle" data-exclude="true" class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                    <strong>{{ $ticket->dep_from_city }} {{ $ticket->dep_to_city }}</strong><br/>
                                    {{ $ticket->dep_date }} <br/>
                                    {{ $ticket->dep_time }} - {{ $ticket->dep_arr_time }}<br/>
                                    Flight #: {{ $ticket->flight ? $ticket->flight->dep_flight_number : '' }}
                                </td>
                                <td class="d-none">
                                    {{ $ticket->dep_from_city }} {{ $ticket->dep_to_city }}
                                </td>
                                <td class="d-none">
                                    {{ $ticket->dep_date }}
                                </td>
                                <td style="vertical-align: middle" data-exclude="true" class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                    @if($ticket->type == 'fixed')
                                        <strong>{{ $ticket->dep_to_city }} - {{ $ticket->dep_from_city }}</strong><br/>
                                        {{ $ticket->ret_date }} <br/>
                                        {{ $ticket->ret_time }} - {{ $ticket->ret_arr_time }}
                                    @else
                                        <small><i>Fixed<br/>departures<br/>only</i></small>
                                    @endif
                                </td>
                                <td class="d-none">
                                        @if($ticket->type == 'fixed')
                                            {{ $ticket->dep_to_city }} - {{ $ticket->dep_from_city }}
                                        @endif
                                    </td>
                                <td class="d-none">
                                        @if($ticket->type == 'fixed')
                                            {{ $ticket->ret_date }}
                                        @endif
                                    </td>
                                <td style="vertical-align: middle">
                                    <strong>{{ $ticket->booking->reference }}</strong>
                                </td>
                                <td style="vertical-align: middle">
                                        <strong>
                                            @if(auth()->user()->type != 'white-admin' && $ticket->booking->agency_id != 1)
                                                {{ $ticket->booking->agency?->name }}
                                            @else
                                                {{ $ticket->booking->user?->name }}
                                            @endif
                                        </strong>
                                    </td>
                                <td style="vertical-align: middle">
                                        <strong>{{ $ticket->date_of_birth }}</strong>
                                    </td>
                                <td style="vertical-align: middle" class="text-center">
                                        <strong>{{ $ticket->nationality }}</strong>
                                    </td>
                                <td style="vertical-align: middle" class="text-center">
                                    @if($ticket->booking->hotel_id || !empty($ticket->booking->hotel_name))
                                        <strong>${{ number_format($ticket->booking->total) }} @if($ticket->class) ({{ $ticket->class }}) @endif</strong>
                                    @else
                                        <strong>${{ number_format($ticket->price) }} @if($ticket->class) ({{ $ticket->class }}) @endif</strong>
                                    @endif
                                    <br/>
                                        @if($ticket->cabin == 'economy')
                                            <span class="text-info font-weight-bold">{{ ucfirst($ticket->cabin) }}</span>
                                        @elseif($ticket->cabin == 'business')
                                            <span class="text-warning font-weight-bold">{{ ucfirst($ticket->cabin) }}</span>
                                        @endif
                                </td>
                                <td style="vertical-align: middle" data-exclude="true">
                                    <strong>
                                        {!! $ticket->deleted_at ? '<span class="text-danger">Deleted</span>' : ($ticket->booking->status == 'Confirmed' ? '<span class="text-success">Confirmed</span>' : '<span class="text-danger">On Hold</span>') !!}
                                    </strong>
                                </td>
                                @if(auth()->user()->agency_id == 1)
                                <td style="vertical-align: middle" data-exclude="true">
                                    {{ $ticket->booking->connection_id ? 'Connection' : 'Local' }}
                                    @if($ticket->booking->connection_reference)
                                        <br/>
                                        <strong>Ref: {{ $ticket->booking->connection_reference }}</strong>
                                    @endif
                                </td>
                                @endif
                                <td style="vertical-align: middle" data-exclude="true">
                                        <div class="d-flex justify-content-end">
                                            @if(!$ticket->deleted_at)
                                            <a class="btn btn-xs btn-info text-center" href="{{ route('frontend.booking.print', $ticket->booking->id) }}" target="_blank">
                                                View
                                            </a>
                                            @if(auth()->user()->type !== 'accounting')
                                                @if($ticket->booking->status == 'On Hold')
                                                    <button class="btn btn-xs btn-info mr-1" wire:click="confirmBooking({{$ticket->booking->id}})">
                                                        <strong>Confirm</strong>
                                                    </button>
                                                @endif
                                                <a class="btn btn-xs btn-success mr-1" href="{{ route('admin.bookings.edit', $ticket->id) }}">
                                                    Edit
                                                </a>
                                                <button type="button" class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="initializeDelete({{ $ticket->booking_id }})">
                                                    Delete
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if(config('app.saas_id') == 'samadonline')
                <table id="sabre" style="display:none">
                    <thead>
                    <tr>
                        <th>
                            First Name
                        </th>
                        <th>
                            Last Name
                        </th>
                        <th>
                            Date of Birth
                        </th>
                        <th>
                            Gender
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Reservation No
                        </th>
                        <th>
                            CTCE
                        </th>
                        <th>
                            Email
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets as $ticket)
                            <tr>
                                <td>
                                    <strong>{{ $ticket->first_name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $ticket->last_name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::createFromFormat(config('project.date_format'), $ticket->date_of_birth)->format('d-M-y') }}</strong>
                                </td>
                                <td>
                                    <strong>
                                            <?php
                                            if(str_contains($ticket->title, 'Mr')) {
                                                echo 'M';
                                            } else if(str_contains($ticket->title, 'Miss') || str_contains($ticket->title, 'Mrs')) {
                                                echo 'F';
                                            } else {
                                                echo $ticket->title;
                                            }
                                            ?>
                                    </strong>
                                </td>
                                <td>
                                    <strong>{{ $ticket->phone }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $ticket->booking->reference }}</strong>
                                </td>
                                <td>
                                    <strong>{{ str_replace("@","//",$ticket->booking?->user?->email) }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $ticket->booking?->user?->email }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                <div class="col-12">
                    <div class="pt-3">
                        {{ $tickets->links() }}
                    </div>
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

    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script>
        function exportReportToExcel()
        {
            @if(config('app.saas_id') == 'tb2book')
                var element = document.getElementById("travel-book-export");
            @else
                var element = document.getElementById("table");
            @endif
            TableToExcel.convert(element, {
                name: "export.xlsx",
                sheet: {
                    name: "Sheet 1"
                }
            });
        }

        function exportReportForSabre() {
            let table = document.getElementById("sabre"); // you can use document.getElementById('tableId') as well by providing id to the table tag
            TableToExcel.convert(table, {
                name: `sabre.xlsx`, // fileName you could use any name
                sheet: {
                    name: 'Sheet 1' // sheetName
                }
            });
        }

        function checkboxHandler(passengerId) {
            return {
                toggleCheckbox(el) {
                    const isChecked = el.checked;
                    const checkboxes = document.querySelectorAll(`input[data-passenger-id="${passengerId}"]`);

                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = isChecked;
                    });

                    const passengerIds = [...checkboxes].map(checkbox => checkbox.value);

                    @this.call('updatePassengersToDelete', passengerIds, isChecked);
                }
            }
        }
    </script>
@endpush
