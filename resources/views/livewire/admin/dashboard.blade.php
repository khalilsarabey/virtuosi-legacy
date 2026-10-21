<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="container-fluid">
            @if(auth()->user()->type == 'admin' || auth()->user()->type == 'accounting')
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div style="border-radius: 10px;">
                            <div class="border-bottom-blueGray-200 w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="header-card-title">Remaining Seats</h6>
                                </div>
                            </div>
                        </div>
                        @isset($message)
                            @if($message)
                                <div class="alert alert-danger">{{ $message }}</div>
                            @endif
                        @endif
                    </div>
                    <div class="col-12 mb-5">
                        @foreach($remainingSeatsSearches as $k => $remainingSeatsSearch)
                            <div class="row" wire:key="{{ $k }}">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Start Date</label>
                                        <input type="date" wire:model="remainingSeatsSearches.{{$k}}.remainingSeatsStartDate" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">End Date</label>
                                        <input type="date" class="form-control" wire:model="remainingSeatsSearches.{{$k}}.remainingSeatsEndDate">
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="">Airline</label>
                                    <x-select2 wire:model="remainingSeatsSearches.{{$k}}.remainingSeatAirline" class="form-control w-100" id="filter-airline{{ $k }}">
                                        <option></option>
                                        @foreach($airlines as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </x-select2>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">From</label>
                                        <x-select2 wire:model="remainingSeatsSearches.{{$k}}.remainingSeatFrom">
                                            <option></option>
                                            @foreach($locations as $v)
                                                <option value="{{ $v }}">{{ $v }}</option>
                                            @endforeach
                                        </x-select2>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">To</label>
                                        <x-select2 wire:model="remainingSeatsSearches.{{$k}}.remainingSeatTo">
                                            <option></option>
                                            @foreach($locations as $v)
                                                <option value="{{ $v }}">{{ $v }}</option>
                                            @endforeach
                                        </x-select2>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Type</label>
                                        <select class="form-control w-100" wire:model="remainingSeatsSearches.{{$k}}.remainingSeatType">
                                            <option value="oneway">One Way</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="exportToExcel" class="btn btn-primary my-3">Export to Excel</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" wire:click="getRemainingSeats" class="btn btn-primary my-3">Search</button>
                                <button type="button" wire:click="addCriteria" class="btn btn-outline-primary my-3">+</button>
                            </div>
                        </div>
                            @foreach($remaining as $index => $remain)
                                @if(count($remain) > 0)
                                    @if($remain['search']['remainingSeatType'] == 'oneway')
                                        @php $total_remaining_seats_first = 0 @endphp
                                        @php $total_remaining_seats_second = 0 @endphp
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table  data-airline="{{ $airlines[$remain['search']['remainingSeatAirline']] ?? '' }}" class="table table-index exportable">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="4" class="text-center font-size-18 text-primary" data-a-h="center">
                                                            {{ $airlines[$remain['search']['remainingSeatAirline']] ?? '' }}
                                                        </th>
                                                    </tr>
                                                    <tr data-fill-color="#cccccc">
                                                        <th style="padding: 0.75rem 1rem">{{ $remain['search']['remainingSeatFrom'] }} - {{ $remain['search']['remainingSeatTo'] }}</th>
                                                        <th style="padding: 0.75rem 1rem"># of Seats</th>
                                                        <th style="padding: 0.75rem 1rem">{{ $remain['search']['remainingSeatTo'] }} - {{ $remain['search']['remainingSeatFrom'] }}</th>
                                                        <th style="padding: 0.75rem 1rem"># of Seats</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @for ($i = 0; $i < max(count($remain['results']['departure']), count($remain['results']['return'])); $i++)
                                                        <tr>
                                                            <td>
                                                                @if(isset($remain['results']['departure'][$i]))
                                                                    {{ \Carbon\Carbon::createFromFormat('d-m-Y', $remain['results']['departure'][$i]['dep_date'])->format('d, M') }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($remain['results']['departure'][$i]))
                                                                    {{ $remain['results']['departure'][$i]['all_seats'] - $remain['results']['departure'][$i]['tickets_count'] }}
                                                                    @php $total_remaining_seats_first += ($remain['results']['departure'][$i]['all_seats'] - $remain['results']['departure'][$i]['tickets_count']); @endphp
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($remain['results']['return'][$i]))
                                                                    {{ \Carbon\Carbon::createFromFormat('d-m-Y', $remain['results']['return'][$i]['dep_date'])->format('d, M') }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($remain['results']['return'][$i]))
                                                                    {{ $remain['results']['return'][$i]['all_seats'] - $remain['results']['return'][$i]['tickets_count'] }}
                                                                    @php $total_remaining_seats_second += ($remain['results']['return'][$i]['all_seats'] - $remain['results']['return'][$i]['tickets_count']); @endphp
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td><strong>Total: {{ $total_remaining_seats_first }}</strong></td>
                                                        <td></td>
                                                        <td><strong>Total: {{ $total_remaining_seats_second }}</strong></td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                    @if($remain['search']['remainingSeatType'] == 'fixed')
                                        @php $total_remaining_seats = 0 @endphp
                                        <div class="row">
                                            <div class="col-12">
                                                <table data-airline="{{ $airlines[$remain['search']['remainingSeatAirline']] ?? '' }}" class="table table-index exportable">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="4" class="text-center font-size-18 text-primary">
                                                            {{ $airlines[$remain['search']['remainingSeatAirline']] }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th style="padding: 0.75rem 1rem">{{ $remain['search']['remainingSeatTo'] }} - {{ $remain['search']['remainingSeatFrom'] }}</th>
                                                        <th style="padding: 0.75rem 1rem">Return Date</th>
                                                        <th style="padding: 0.75rem 1rem"># of seats</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($remain['results']['departure'] as $flight)
                                                        <tr style="{{ $flight['all_seats'] - $flight['tickets_count'] == 0 ? 'background: rgb(194, 247, 193)' : '' }}">
                                                            <td>{{ \Carbon\Carbon::createFromFormat('d-m-Y', $flight['dep_date'])->format('d, M') }}</td>
                                                            <td>{{ \Carbon\Carbon::createFromFormat('d-m-Y', $flight['ret_dep_date'])->format('d, M') }}</td>
                                                            <td>{{ $flight['all_seats'] - $flight['tickets_count'] }} @php $total_remaining_seats += ($flight['all_seats'] - $flight['tickets_count']) @endphp</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>Total: {{ $total_remaining_seats }}</strong></td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="border-bottom-blueGray-200 w-100">
                        <div class="d-flex justify-content-between">
                            <h6 class="header-card-title">{{ auth()->user()->type == 'admin' ? 'Bookings Summary' : "Today's Bookings" }}</h6>
                        </div>
                    </div>
                    @if(auth()->user()->type == 'admin' || auth()->user()->type == 'accounting')
                        <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">From Booking Date</label>
                                        <input type="date" wire:model.defer="filter.from_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">To Booking Date</label>
                                        <input type="date" class="form-control" wire:model.defer="filter.to_date">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Airline</label>
                                        <x-select-list class="form-control w-100" id="airline" name="airline" :options="$airlines" wire:model="filter.airline" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Departure</label>
                                        <x-select-list class="form-control w-100" id="dep_from" name="dep_from" :options="$locations" wire:model="filter.departure" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Destination</label>
                                        <x-select-list class="form-control w-100" id="dep_to" name="dep_to" :options="$locations" wire:model="filter.destination" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <x-select-list class="form-control w-100" id="status" name="status" :options="['Confirmed' => 'Confirmed', 'On Hold' => 'On Hold', 'Deleted' => 'Deleted']" wire:model="filter.status" />
                                    </div>
                                </div>

                                <div class="col-1 d-flex align-items-center justify-content-end">
                                    <button type="button" wire:click="search" class="btn btn-primary mt-3">Filter</button>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-12">
                                <button onclick="exportBookingsToExcel(this)" class="btn btn-xs btn-outline-primary mb-2">Export to Excel</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
                <div class="row">
                    <div class="col-12">
                        <table data-cols-width="30,30,30,30,30,30,30" id="bookings-table" class="table table-index w-100">
                            <thead>
                                <tr>
                                    <th data-f-bold="true" style="padding: 0.75rem 1rem">Booking Date</th>
                                    <th data-f-bold="true" style="padding: 0.75rem 1rem" class="text-center">Booking Type</th>
                                    <th data-f-bold="true" style="padding: 0.75rem 1rem" class="text-center">Agency</th>
                                    <th data-f-bold="true" style="padding: 0.75rem 1rem" class="text-center">Itinerary</th>
                                    <th data-f-bold="true" style="padding: 0.75rem 1rem" class="text-center">Booking Status</th>
                                    <th data-f-bold="true" style="padding: 0.75rem 1rem" class="text-center">Num of Passengers</th>
                                    <th data-f-bold="true" class="text-center" style="padding: 0.75rem 1rem">Total Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                        <?php $total = 0 ?>
                                        <?php $deleted = 0 ?>
                                        <?php $totalPassengers = 0 ?>
                                        @forelse($bookingsToday as $bookingToday)
                                        <tr>
                                            <td>{{ $bookingToday->created_at }}</td>
                                            <td class="text-center">
                                                @if($bookingToday->type == 'roundtrip' && $bookingToday->hotel_id)
                                                    <span class="badge badge-success px-2 py-1">Package</span>
                                                @else
                                                    <span class="text-success">{{ ucfirst($bookingToday->type) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(auth()->user()->type != 'white-admin' && $bookingToday->agency_id != 1)
                                                    {{ $bookingToday->agency->name }}
                                                @else
                                                    {{ $bookingToday->user->name }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $bookingToday->dep_from_city }} - {{ $bookingToday->dep_to_city }}</strong><br/>
                                                {{ $bookingToday->dep_date }} <br/>
                                            </td>
                                            <td class="text-center">
                                                @if($bookingToday->deleted_at)
                                                    <span class="badge badge-danger px-2 py-1 text-uppercase">Deleted</span>
                                                @elseif($bookingToday->status == 'On Hold')
                                                    <span class="badge badge-warning px-2 py-1 text-uppercase">On Hold</span>
                                                @else
                                                    <span class="badge badge-success px-2 py-1 text-uppercase">Confirmed</span>
                                                @endif
                                            </td>
                                            <?php $pa = $bookingToday->tickets_count > 0 ? $bookingToday->tickets_count : $bookingToday->rooms_count ?>
                                            <td class="text-center">{{ $pa }}</td>
                                            <td class="text-center">${{ $bookingToday->total }}</td>
                                            <?php
                                                if($bookingToday->deleted_at) {
                                                    $deleted += $bookingToday->total;
                                                } else {
                                                    $total += $bookingToday->total;
                                                }
                                            ?>
                                            <?php $totalPassengers += !$bookingToday->deleted_at ? $pa : 0 ?>
                                            <td class="text-right">
                                                @if(!$bookingToday->deleted_at)
                                                    <a target="_blank" class="btn btn-xs btn-primary" href="{{ route('frontend.booking.print', $bookingToday->id) }}">View</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="py-5">No Bookings Today</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                            @if($total > 0)
                                <tfoot data-exclude="true">
                                        <tr>
                                            <td colspan="5">

                                            </td>
                                            <td class="text-left">
                                                <strong>Passengers: {{ $totalPassengers }}</strong>
                                            </td>
                                            <td class="text-right">
                                                <strong>TOTAL: ${{ number_format($total)  }}</strong>
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

            @if(auth()->user()->type != 'white-admin')
                <div class="row">
                    @if(1 == 2)
                    <div class="col-md-6 mt-5">
                        <div style="background: white; border-radius: 10px;">
                            <div class="border-bottom-blueGray-200 w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="header-card-title">
                                        Top 10 White Labels
                                        <div wire:loading.delay="">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden"></span>
                                            </div>
                                        </div>
                                    </h6>
                                </div>
                            </div>
                            <table class="table table-index" wire:init="loadTopWhiteLabels">
                                <thead>
                                <tr>
                                    <th>Agency Name</th>
                                    <th class="text-right">Number of Bookings</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($topWhiteLabels as $topWhiteLabel)
                                    <tr>
                                        <td>
                                            {{ $topWhiteLabel->agency->name }}
                                        </td>
                                        <td class="text-right"> {{ $topWhiteLabel->tickets_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <div class="py-5">Not Enough Data</div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <div style="background: white; border-radius: 10px; padding: 25px;">
                            <div class="border-bottom-blueGray-200 w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="header-card-title">
                                        Top 10 Agencies
                                        <div wire:loading.delay="">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden"></span>
                                            </div>
                                        </div>
                                    </h6>
                                </div>
                            </div>
                            <table class="table table-index" wire:init="loadTopAgencies">
                                <thead>
                                <tr>
                                    <th>Agency Name</th>
                                    <th class="text-right">Number of Bookings</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($topAgencies as $topAgency)
                                    <tr>
                                        <td>
                                            @if(auth()->user()->type == 'white-admin' && $topAgency->agency_id == auth()->user()->agency_id)
                                                {{ $topAgency->name }}
                                            @else
                                                {{ $topAgency->name }}
                                            @endif
                                        </td>
                                        <td class="text-right"> {{ $topAgency->ticket_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <div class="py-5">Not Enough Data</div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script>
        let button = document.querySelector("#exportToExcel");

        button.addEventListener("click", e => {
            // Select all tables with the "exportable" class
            const tables = document.querySelectorAll('.exportable');

            // Create a new workbook
            const wb = TableToExcel.initWorkBook();

            // Loop through each table
            tables.forEach(table => {
                // Retrieve the value of the data-airline attribute
                const airline = table.getAttribute('data-airline');

                // Create an empty object to store table options
                const tableOptions = {
                    sheet: {
                        name: airline // Use the airline as the sheet name
                    }
                };

                // Convert each table to a sheet in the workbook
                TableToExcel.tableToSheet(wb, table, tableOptions);
            });

            // Save the workbook as a single Excel file
            TableToExcel.save(wb, 'exported_tables.xlsx');
        });

        function exportBookingsToExcel() {
            TableToExcel.convert(document.getElementById("bookings-table"), {
                name: "bookings-export.xlsx",
                sheet: {
                    name: "Sheet 1"
                }
            });
        }
    </script>
<style>
    .apexcharts-tooltip-text-label {
        display: none !important;
    }
</style>
@endpush
