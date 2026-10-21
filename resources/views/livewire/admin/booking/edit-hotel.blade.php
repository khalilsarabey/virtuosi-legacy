<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Hotel Booking
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div>
                    <button type="button" wire:click="edit('guests')" class="btn btn-xs btn-primary">Edit Guests</button>
                    <button type="button" wire:click="edit('booking')" class="btn btn-xs btn-secondary">Edit Booking</button>
                </div>
            </div>
        </div>

        @if($editing == 'guests')
            <div class="container-fluid mb-4">
            <form wire:submit="submit" class="pt-3">
                @foreach($booking->rooms as $r => $room)
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Title</label>
                                <select class="form-control" wire:model.live="rooms.{{ $r }}.title">
                                        <option value="" selected></option>
                                        <option>Mr.</option>
                                        <option>Miss.</option>
                                        <option>Mrs.</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" wire:model.live="rooms.{{ $r }}.first_name" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" wire:model.live="rooms.{{ $r }}.last_name" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <x-date-picker class="form-control" required wire:model.live="rooms.{{ $r }}.date_of_birth" id="date_of_birth_{{ $r }}" name="date_of_birth_{{ $r }}" picker="date" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" wire:model.live="rooms.{{ $r }}.phone" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Nationality</label>
                                <select class="form-control" wire:model.live="rooms.{{ $r }}.nationality">
                                    @foreach($countries as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        @if($editing == 'booking')
            <livewire:admin.booking.edit-hotel-booking :booking="$booking" />
        @endif

    </section>
</div>
