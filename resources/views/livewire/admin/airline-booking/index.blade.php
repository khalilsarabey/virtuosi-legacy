<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>

    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Airline Bookings List
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
                <div class="col-12 col-md-3 mt-3">
                    <label>Airline</label>
                    <select class="form-control w-100" wire:model.defer="filters.airline" >
                        <option></option>
                        <option value="AirArabia">AirArabia</option>
                        <option value="FlyDubai">FlyDubai</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 mt-3">
                    <label>Agency / User</label>
                    <x-select-list class="form-control w-100" id="agencies" name="agencies" :options="$agencies" wire:model.defer="filters.user_id" />
                </div>
                <div class="col-12 col-md-3 mt-3">
                    <label>From</label>
                    <input type="text" class="form-control" wire:model.defer="filters.from" />
                </div>
                <div class="col-12 col-md-3 mt-3">
                    <label>To</label>
                    <input type="text" class="form-control" wire:model.defer="filters.to" />
                </div>
                <div class="col-12 col-md-3 mt-3">
                    <label>Status</label>
                    <select class="form-control w-100" wire:model.defer="filters.status">
                        <option></option>
                        <option value="confirmed">confirmed</option>
                        <option value="pending">pending</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 mt-3">
                    <label>Booking Date</label>
                    <input class="form-control" type="date" wire:model.defer="filters.created_at" />
                </div>
                <div class="col-12 col-md-3 mt-3">
                    <label>Departure Date</label>
                    <input class="form-control" type="date" wire:model.defer="filters.dep_from_date" />
                </div>
                <div class="col-12 col-md-3 mt-3">
                    <label class="text-white">Search</label>
                    <button class="btn btn-primary w-100" wire:click="search">Search</button>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col d-flex align-items-end">
                    <button onclick="exportReportToExcel(this)" type="button" class="btn btn-sm btn-success disabled:opacity-50 disabled:cursor-not-allowed" >
                        Export To Excel
                    </button>
                </div>
            </div>
            @if(count($selected))
                <div class="row mt-3">
                    <div class="col-12">
                        <button class="btn btn-sm btn-primary disabled:opacity-50 disabled:cursor-not-allowed" wire:click="confirmSelected">Confirm Selected Bookings</button>
                        <button class="btn btn-sm btn-danger disabled:opacity-50 disabled:cursor-not-allowed ml-3" wire:click="deleteSelected">Delete Selected Bookings</button>
                    </div>
                </div>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index clickable-table">
                        <thead>
                        <tr>
                            @if(auth()->user()->type != 'accounting')
                            <th data-exclude="true"></th>
                            @endif
                            <th data-f-bold="true">
                                Airline
                            </th>
                            <th data-f-bold="true">
                                Booking Date
                            </th>
                            <th data-f-bold="true">
                                Agency / User
                            </th>
                            <th data-exclude="true">
                                Departure
                            </th>
                            <th data-f-bold="true" class="d-none">
                                Departing Flight
                            </th>
                            <th data-f-bold="true" class="d-none">
                                Departing Flight Number
                            </th>
                            <th data-f-bold="true" data-exclude="true">
                                Return
                            </th>
                            <th data-f-bold="true" class="d-none">
                                Returning Flight
                            </th>
                            <th data-f-bold="true" class="d-none">
                                Returning Flight Number
                            </th>
                            <th data-f-bold="true">
                                PNR
                            </th>
                            <th class="text-left" data-f-bold="true">
                                Passengers
                            </th>
                            <th>
                                Price
                            </th>
                            <th class="text-center" data-exclude="true">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            @php $payload = json_decode($booking->payload) @endphp
                            @php $request = json_decode($booking->request) @endphp
                            <tr>
                                @if(auth()->user()->type != 'accounting')
                                <td>
                                    <input type="checkbox" value="{{ $booking->id }}" wire:model="selected" />
                                </td>
                                @endif
                                <td>
                                    {{ $booking->airline }}
                                </td>
                                <td>
                                    {{ $booking->created_at->format('d/m/y') }}
                                </td>
                                <td>
                                    {{ $booking->user->name }}
                                </td>
                                <td data-exclude="true">
                                    <h4 class="text-xl font-bold block mb-1 uppercase tracking-wide text-blueGray-600">{{ $booking->dep_from }} - {{ $booking->dep_to }}</h4>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        {{ Carbon\Carbon::parse($booking->dep_from_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        {{ Carbon\Carbon::parse($booking->dep_from_date)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->dep_to_date)->format('H:i') }}
                                    </div>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        {{ $payload->departure->flight_number }}
                                    </div>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">

                                    </div>
                                </td>
                                <td class="d-none">
                                    {{ $booking->dep_from }} - {{ $booking->dep_to }}
                                </td>
                                <td class="d-none">
                                    {{ $payload->departure->flight_number }}
                                </td>
                                <td data-exclude="true">
                                    @if($booking->ret_from)
                                    <h4 class="text-xl font-bold block mb-1 uppercase tracking-wide text-blueGray-600">{{ $booking->ret_from }} - {{ $booking->ret_to }}</h4>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        {{ Carbon\Carbon::parse($booking->ret_from_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        {{ Carbon\Carbon::parse($booking->ret_from_date)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->ret_to_date)->format('H:i') }}
                                    </div>
                                    <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                        {{ $payload->return->flight_number }}
                                    </div>
                                        <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">

                                        </div>
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>
                                <td class="d-none">
                                    @if($booking->ret_from)
                                        {{ $booking->dep_from }} - {{ $booking->dep_to }}
                                    @endif
                                </td>
                                <td class="d-none">
                                    @if($booking->ret_from)
                                        {{ $payload->departure->flight_number }}
                                    @endif
                                </td>
                                <td>
                                    {{ $payload->booking_num }}
                                </td>
                                <td class="text-left">
                                    @foreach($payload->travellers as $traveller)
                                        <div class="text-sm leading-normal mt-0 mb-1 text-blueGray-400 font-bold uppercase">
                                            {{ $traveller->first_name }} {{ $traveller->last_name }} ({{ $traveller->type }})
                                            <br/>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="text-sm mt-0 mb-2 text-blueGray-400 font-bold uppercase">
                                        <div class="d-flex justify-content-between">
                                            <div>Amount</div>
                                            <div class="text-success">
                                                ${{ number_format($booking->amount) }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div>Markup: </div>
                                            <div class="text-success">{{ $booking->markup_type == 'fixed' ? '$' : '%' }}{{ $booking->markup }}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div>Amount with Markup: </div>
                                            <div class="text-success">${{ number_format($booking->amount_with_markup) }}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div>Taxes & Fees: </div>
                                            <div class="text-success">${{ $booking->taxes }}</div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div>Total: </div>
                                            <div class="text-success">${{ $booking->total }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($booking->status != 'confirmed')
                                        @if($booking->on_hold_expiry_date && \Carbon\Carbon::parse($booking->on_hold_expiry_date)->timezone('Asia/Beirut') > \Carbon\Carbon::now('Asia/Beirut'))
                                            <div class="font-weight-bold text-center text-info">ON HOLD</div>
                                        @elseif($booking->on_hold_expiry_date && \Carbon\Carbon::parse($booking->on_hold_expiry_date)->timezone('Asia/Beirut') < \Carbon\Carbon::now('Asia/Beirut'))
                                            <div class="font-weight-bold text-center text-danger">CANCELLED</div>
                                        @endif
                                    @else
                                        <strong>{!! strtolower($booking->status) == 'confirmed' ? '<span class="text-success">Confirmed</span>' : '<span class="text-danger">On Hold</span>' !!}</strong>
                                    @endif
                                </td>
                            </tr>
                            @if($booking->remarks)
                            <tr data-exclude="true">
                                <td colspan="3" data-exclude="true"></td>
                                <td colspan="10" data-exclude="true">
                                    <strong>Remarks</strong>
                                    {!! nl2br($booking->remarks) !!}
                                </td>
                            </tr>
                            @endif
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
            let table = document.getElementsByTagName("table"); // you can use document.getElementById('tableId') as well by providing id to the table tag
            TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
                name: `low-cost-bookings.xlsx`, // fileName you could use any name
                sheet: {
                    name: 'Sheet 1' // sheetName
                }
            });
        }
    </script>
@endpush
