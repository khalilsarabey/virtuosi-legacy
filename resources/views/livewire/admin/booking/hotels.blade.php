<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>

    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Hotels Bookings List
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
                <div class="col mt-3">
                    <label>Agency / User</label>
                    <x-select-list class="form-control w-100" id="agencies" name="agencies" :options="$agencies" wire:model.live="filters.user_id" />
                </div>
                <div class="col mt-3">
                    <label>Hotel</label>
                    <x-select-list class="form-control w-100" id="hotels" name="hotels" :options="$hotels" wire:model.live="filters.hotel" />
                </div>
                <div class="col mt-3">
                    <label>Room Type</label>
                    <x-select-list class="form-control w-100" id="airlines" name="airlines" :options="$types" wire:model.live="filters.type" />
                </div>
                <div class="col mt-3">
                    <label>Reference Number</label>
                    <input type="text" class="form-control" wire:model.live="filters.reference" />
                </div>
                <div class="col mt-3">
                    <label>Check In Date</label>
                    <input type="date" name="dep_date" id="dep_date" class="form-control" wire:model.live="filters.check_in" />
                </div>
                <div class="col mt-3">
                    <label>Check Out Date</label>
                    <input type="date" name="ret_date" id="ret_date" class="form-control" wire:model.live="filters.check_out" />
                </div>
                <div class="col mt-3">
                    <label>Include Packages</label>
                    <x-select-list class="form-control w-100" id="packages" name="packages" required :options="['1' => 'Yes', '0' => 'No']" wire:model.live="filters.packages" />
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3 border-top">
                <div class="col d-flex align-items-end">
                    <button onclick="exportReportToExcel(this)" type="button" class="btn btn-xs btn-success disabled:opacity-50 disabled:cursor-not-allowed" >
                        Export Excel
                    </button>
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
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table data-cols-width="20,20,20,20,20,20,20,20" class="table table-index clickable-table">
                        <thead>
                        <tr>
                            <th data-f-bold="true">Hotel</th>
                            <th data-f-bold="true">Reference</th>
                            <th data-f-bold="true">Rooms</th>
                            <th data-f-bold="true">Guests</th>
                            <th data-f-bold="true" class="text-center">Dates</th>
                            <th data-f-bold="true" class="text-center">Agency/User</th>
                            <th data-f-bold="true" class="text-center">Price</th>
                            <th data-f-bold="true">Status</th>
                            <th data-exclude="true"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    @if($booking->rooms?->first())
                                    <strong>{{ $booking->rooms?->first() ? $booking->rooms?->first()->hotel->name : '-' }}</strong><br/>
                                    {{ $booking->rooms?->first()->hotel?->city->city }} - {{ $booking->rooms?->first()->hotel?->city->country }}<br/>
                                    @endif
                                    <div>Booking Date: {{ \Carbon\Carbon::parse($booking->created_at)->format('d-M-Y') }}</div>
                                </td>
                                <td>
                                    <strong>{{ $booking->reference }}</strong>
                                </td>
                                <td class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                    @foreach($booking->rooms->unique('room_number') as $k => $room_type)
                                        <div><strong>{{ $room_type->room->room_type->name }}</strong></div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($booking->rooms as $room)
                                        <div><strong>{{ $room->full_name }}</strong></div>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <div class="mb-3">
                                        <strong>Check In</strong><br />
                                        {{ $booking->check_in->format(config('project.date_format')) }}
                                    </div>
                                    <div>
                                        <strong>Check Out</strong><br />
                                        {{ $booking->check_out->format(config('project.date_format')) }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <strong>
                                        @if(auth()->user()->type != 'white-admin' && $booking->agency_id != 1)
                                            {{ $booking->agency->name }}
                                        @else
                                            {{ $booking->user->name }}
                                        @endif
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <strong>${{ number_format($booking->price) }}</strong>
                                </td>
                                <td>
                                    <strong>{!! $booking->status == 'Confirmed' ? '<span class="text-success">Confirmed</span>' : '<span class="text-danger">On Hold</span>' !!}</strong>
                                </td>
                                <td data-exclude="true">
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-xs btn-info mr-2 text-center" href="{{ route('frontend.booking.print', $booking->id) }}" target="_blank">
                                            View
                                        </a>
                                        @if(auth()->user()->type != 'accounting')
                                            <a class="btn btn-xs btn-success mr-2 text-center" href="{{ route('admin.bookings.hotel.edit', $booking->id) }}">
                                                Edit
                                            </a>

                                            <button type="button" class="btn btn-xs btn-danger text-center" href="#" wire:click="delete({{ $booking->id }})">
                                                Delete
                                            </button>
                                            @if($booking->status == 'On Hold')
                                                <button class="btn btn-xs btn-info mr-2" wire:click="confirmBooking(1)">
                                                    <strong>Confirm</strong>
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
                <div class="col-12">
                    <div class="pt-3">
                        {{ $bookings->links() }}
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
        function exportReportToExcel() {
            let table = document.getElementsByTagName("table");
            TableToExcel.convert(table[0], {
                name: `hotels-export.xlsx`,
                sheet: {
                    name: 'Sheet 1'
                }
            });
        }
    </script>
@endpush
