<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Change Booking Flight
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
            <div class="alert alert-danger mt-3">This action can not be undone! Use it with caution</div>
            <div class="alert alert-warning mt-3">
                Make sure that the flights you're changing has the exact same Departure, Arrival and Time<br/><br/>
                This action will perform the following:<br/>
                <ol class="pl-3 mt-3">
                    <li><strong>Delete</strong> the current booking from the database (Not just the ticket, the whole booking with its tickets)</li>
                    <li>Create a new booking with the updated data entered below</li>
                    <li>This function will NOT <strong>validate the data</strong>, so make sure it is correct</li>
                    <li>All pricing will remain the same including the classes booked</li>
                </ol>
            </div>
            <div class="container-fluid mb-4">
                <div class="row">
                    <div class="col-12">
                        <h5>This is a {{ $booking->type }} booking</h5>
                        <hr/>
                    </div>
                    <div class="col-12">
                        <form wire:submit.prevent="submit" class="pt-3 row">
                            @csrf
                            @foreach(collect($booking->getAttributes())->except(['id', 'payload', 'created_at', 'updated_at', 'reference']) as $key => $value)
                                @if($key == 'dep_flight_id' || $key == 'ret_flight_id')
                                    @if($value)
                                        <div class="col-lg-2 form-group {{ $errors->has('ticket.title') ? 'invalid' : '' }}">
                                            <label>{{ $key }}</label>
                                            <input required class="form-control" type="number" wire:model.defer="change.{{$key}}" name="{{ $key }}" value="{{ $value }}" />
                                        </div>
                                    @endif
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                                @endif
                            @endforeach
                            <div class="col-12">
                                <hr/>
                                <h5>Passengers</h5>
                                <hr/>
                            </div>
                            @foreach($booking->tickets as $ticket)
                            <div class="col-lg-3">
                                {{ $ticket->full_name }}
                            </div>
                            <div class="col-lg-9">
                                {{ $ticket->dep_from_city }} - {{ $ticket->dep_to_city }}
                            </div>
                            <div class="col-12">
                                <hr/>
                            </div>
                            @endforeach
                            <div class="form-group col-12 d-flex justify-content-end">
                                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button class="btn btn-danger ml-2" type="submit">
                                    Change Booking Flight
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
