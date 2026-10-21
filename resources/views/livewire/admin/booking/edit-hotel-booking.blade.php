<div>
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-md-2">
                <label>Check In</label>
                <div class="form-group">
                    <x-flatpickr id="hotel-date-pick" wire:model="booking.dep_date" name="date" min="today" months="2" required picker="date" />
                </div>
            </div>
            <div class="col-md-2">
                <label>Check Out</label>
                <div class="form-group">
                    <x-flatpickr id="hotel-date-pick" wire:model="booking.ret_date" name="date" min="today" months="2" required picker="date" />
                </div>
            </div>
        </div>
    </div>
</div>
