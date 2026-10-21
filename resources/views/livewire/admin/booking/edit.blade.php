<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Booking
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                @if(auth()->user()->type == 'admin')
                    <a href="{{ route('admin.bookings.change', $ticket->id) }}" class="btn btn-primary">Change Booking Flights</a>
                @endif
            </div>
            <div class="container-fluid mb-4">
                <div class="row">
                    <div class="col-12">
                        <form wire:submit.prevent="submit" class="pt-3">
                            <div class="form-group {{ $errors->has('ticket.title') ? 'invalid' : '' }}">
                                <label class="form-label required" for="title">{{ trans('cruds.ticket.fields.title') }}</label>
                                <select class="form-control" name="title" id="title" wire:model="ticket.title">
                                    <option {{ $ticket->title == 'Mr.' ? 'selected' : '' }} value="Mr.">Mr.</option>
                                    <option {{ $ticket->title == 'Mrs.' ? 'selected' : '' }} value="Mrs.">Mrs.</option>
                                    <option {{ $ticket->title == 'Miss.' ? 'selected' : '' }} value="Miss.">Miss.</option>
                                    <option {{ $ticket->title == 'Infant.' ? 'selected' : '' }} value="Infant.">Infant.</option>
                                    <option {{ $ticket->title == 'Child.' ? 'selected' : '' }} value="Child.">Child.</option>
                                </select>
                            </div>
                            <div class="form-group {{ $errors->has('ticket.first_name') ? 'invalid' : '' }}">
                                <label class="form-label required" for="first_name">{{ trans('cruds.ticket.fields.first_name') }}</label>
                                <input class="form-control" type="text" name="first_name" id="first_name" required wire:model.defer="ticket.first_name">
                                <div class="validation-message">
                                    {{ $errors->first('ticket.first_name') }}
                                </div>
                                <div class="help-block">
                                    {{ trans('cruds.ticket.fields.first_name_helper') }}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('ticket.last_name') ? 'invalid' : '' }}">
                                <label class="form-label required" for="last_name">{{ trans('cruds.ticket.fields.last_name') }}</label>
                                <input class="form-control" type="text" name="last_name" id="last_name" required wire:model.defer="ticket.last_name">
                                <div class="validation-message">
                                    {{ $errors->first('ticket.last_name') }}
                                </div>
                                <div class="help-block">
                                    {{ trans('cruds.ticket.fields.last_name_helper') }}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('ticket.date_of_birth') ? 'invalid' : '' }}">
                                <label class="form-label required" for="date_of_birth">{{ trans('cruds.ticket.fields.date_of_birth') }}</label>
                                <x-date-picker class="form-control" required wire:model="ticket.date_of_birth" id="date_of_birth" name="date_of_birth" picker="date" />
                                <div class="validation-message">
                                    {{ $errors->first('ticket.date_of_birth') }}
                                </div>
                                <div class="help-block">
                                    {{ trans('cruds.ticket.fields.date_of_birth_helper') }}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('ticket.phone') ? 'invalid' : '' }}">
                                <label class="form-label required" for="phone">{{ trans('cruds.ticket.fields.phone') }}</label>
                                <input class="form-control" type="text" name="phone" id="phone" required wire:model.defer="ticket.phone">
                                <div class="validation-message">
                                    {{ $errors->first('ticket.phone') }}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('ticket.nationality') ? 'invalid' : '' }}">
                                <label class="form-label required" for="nationality">{{ trans('cruds.ticket.fields.nationality') }}</label>
                                <input class="form-control" type="text" name="nationality" id="nationality" required wire:model.defer="ticket.nationality">
                                <div class="validation-message">
                                    {{ $errors->first('ticket.nationality') }}
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-end">
                                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button class="btn btn-primary ml-2" type="submit">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
