<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('booking.user_id') ? 'invalid' : '' }}">
        <label class="form-label required" for="user">{{ trans('cruds.booking.fields.user') }}</label>
        <x-select-list class="form-control" required id="user" name="user" :options="$this->listsForFields['user']" wire:model="booking.user_id" />
        <div class="validation-message">
            {{ $errors->first('booking.user_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.user_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.reference') ? 'invalid' : '' }}">
        <label class="form-label" for="reference">{{ trans('cruds.booking.fields.reference') }}</label>
        <input class="form-control" type="text" name="reference" id="reference" wire:model.defer="booking.reference">
        <div class="validation-message">
            {{ $errors->first('booking.reference') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.reference_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.status') ? 'invalid' : '' }}">
        <label class="form-label" for="status">{{ trans('cruds.booking.fields.status') }}</label>
        <input class="form-control" type="text" name="status" id="status" wire:model.defer="booking.status">
        <div class="validation-message">
            {{ $errors->first('booking.status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.status_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.type') ? 'invalid' : '' }}">
        <label class="form-label" for="type">{{ trans('cruds.booking.fields.type') }}</label>
        <input class="form-control" type="text" name="type" id="type" wire:model.defer="booking.type">
        <div class="validation-message">
            {{ $errors->first('booking.type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.flight_id') ? 'invalid' : '' }}">
        <label class="form-label required" for="flight">{{ trans('cruds.booking.fields.flight') }}</label>
        <x-select-list class="form-control" required id="flight" name="flight" :options="$this->listsForFields['flight']" wire:model="booking.flight_id" />
        <div class="validation-message">
            {{ $errors->first('booking.flight_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.flight_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.dep_flight_id') ? 'invalid' : '' }}">
        <label class="form-label required" for="dep_flight">{{ trans('cruds.booking.fields.dep_flight') }}</label>
        <x-select-list class="form-control" required id="dep_flight" name="dep_flight" :options="$this->listsForFields['dep_flight']" wire:model="booking.dep_flight_id" />
        <div class="validation-message">
            {{ $errors->first('booking.dep_flight_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.dep_flight_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.ret_flight_id') ? 'invalid' : '' }}">
        <label class="form-label" for="ret_flight">{{ trans('cruds.booking.fields.ret_flight') }}</label>
        <x-select-list class="form-control" id="ret_flight" name="ret_flight" :options="$this->listsForFields['ret_flight']" wire:model="booking.ret_flight_id" />
        <div class="validation-message">
            {{ $errors->first('booking.ret_flight_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.ret_flight_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.infants') ? 'invalid' : '' }}">
        <label class="form-label" for="infants">{{ trans('cruds.booking.fields.infants') }}</label>
        <input class="form-control" type="number" name="infants" id="infants" wire:model.defer="booking.infants" step="1">
        <div class="validation-message">
            {{ $errors->first('booking.infants') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.infants_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.adults') ? 'invalid' : '' }}">
        <label class="form-label" for="adults">{{ trans('cruds.booking.fields.adults') }}</label>
        <input class="form-control" type="number" name="adults" id="adults" wire:model.defer="booking.adults" step="1">
        <div class="validation-message">
            {{ $errors->first('booking.adults') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.adults_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.children') ? 'invalid' : '' }}">
        <label class="form-label" for="children">{{ trans('cruds.booking.fields.children') }}</label>
        <input class="form-control" type="number" name="children" id="children" wire:model.defer="booking.children" step="1">
        <div class="validation-message">
            {{ $errors->first('booking.children') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.children_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.price') ? 'invalid' : '' }}">
        <label class="form-label" for="price">{{ trans('cruds.booking.fields.price') }}</label>
        <input class="form-control" type="number" name="price" id="price" wire:model.defer="booking.price" step="1">
        <div class="validation-message">
            {{ $errors->first('booking.price') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.price_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('booking.payload') ? 'invalid' : '' }}">
        <label class="form-label" for="payload">{{ trans('cruds.booking.fields.payload') }}</label>
        <textarea class="form-control" name="payload" id="payload" wire:model.defer="booking.payload" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('booking.payload') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.booking.fields.payload_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>