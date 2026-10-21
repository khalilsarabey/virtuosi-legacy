<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>

    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Packages Bookings List
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
                    <x-select-list class="form-control w-100" id="agencies" name="agencies" :options="$agencies" wire:model="filters.user_id" />
                </div>
                <div class="col mt-3">
                    <label>Hotel</label>
                    <x-select-list class="form-control w-100" id="hotel" name="hotel" :options="$hotels_list" wire:model="filters.hotel_id" />
                </div>
                <div class="col mt-3">
                    <label>From Date</label>
                    <input type="date" name="dep_date" id="dep_date" class="form-control" wire:model="filters.dep_date" />
                </div>
                <div class="col mt-3">
                    <label>To Date</label>
                    <input type="date" name="ret_date" id="ret_date" class="form-control" wire:model="filters.ret_date" />
                </div>
                <div class="col mt-3">
                    <label>Reference Number</label>
                    <input type="text" class="form-control" wire:model="filters.reference" />
                </div>
                <div class="col mt-3">
                    <label>From Booking Date</label>
                    <input type="date" class="form-control" wire:model="filters.from_created_at" />
                </div>
                <div class="col mt-3">
                    <label>To Booking Date</label>
                    <input type="date" class="form-control" wire:model="filters.to_created_at" />
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
                    <select wire:model="perPage" class="form-control form-control-sm w-100">
                        @foreach($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                        <option value="1000000">All</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table data-cols-width="30,30,30,30,45,30,30,30" class="table table-index clickable-table">
                        <thead>
                        <tr>
                            <th  data-f-bold="true">Booking Date</th>
                            <th  data-f-bold="true">
                                Hotel
                            </th>
                            <th  data-f-bold="true">
                                Destination
                            </th>
                            <th  data-f-bold="true" class="text-center">
                                Num of Rooms
                            </th>
                            <th  data-f-bold="true" class="text-center">
                                Passenger(s)
                            </th>
                            <th  data-f-bold="true" class="text-center">
                                Booked By
                            </th>
                            <th  data-f-bold="true" class="text-center">
                                Date
                            </th>
                            <th  data-f-bold="true" class="text-center">Status</th>
                            <th data-exclude="true">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->created_at }}<br/><strong>Ref: {{ $booking->reference }}</strong></td>
                                    <td>{{ $booking->hotel->name ?? $booking->hotel_name }}</td>
                                    <td>{{ $booking->hotel->city->city ?? $booking->dep_to_city }}</td>
                                    <td class="text-center">{{ $booking->rooms->unique('room_number')->count() }} Room{{ $booking->rooms->unique('room_number')->count() > 1 ? 's' : '' }}</td>
                                    <td class="text-center">
                                        @foreach($booking->rooms as $room)
                                            <strong>{{ $room->title }} {{ $room->full_name }}</strong> <br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $booking->user->name }}</td>
                                    <td class="text-center"><strong>{{ \Carbon\Carbon::parse($booking->dep_date)->format('d-m-Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($booking->ret_date)->format('d-m-Y') }}</strong></td>
                                    <td class="text-center"><strong>{!! $booking->status == 'Confirmed' ? '<span class="text-success">Confirmed</span>' : '<span class="text-danger">On Hold</span>' !!}</strong></td>
                                    <td data-exclude="true">
                                        <div class="d-flex justify-content-end">
                                            <a class="btn btn-xs btn-info text-center" href="{{ route('frontend.booking.print', $booking->id) }}" target="_blank">
                                                View
                                            </a>
                                            @if(auth()->user()->type != 'accounting')
                                                <button type="button" class="btn btn-xs btn-danger text-center" href="#" wire:click="delete({{ $booking->id }})">
                                                    Delete
                                                </button>
                                                @if($booking->status == 'On Hold')
                                                    <button class="btn btn-xs btn-info mr-2" wire:click="confirmBooking({{$booking->id}})">
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
                name: `packages-export.xlsx`,
                sheet: {
                    name: 'Sheet 1'
                }
            });
        }
    </script>
@endpush
