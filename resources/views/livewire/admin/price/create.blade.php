<form wire:submit="submit" class="pt-3">

    <div class="form-group {{ $errors->has('price.flight_id') ? 'invalid' : '' }}">
        <label class="form-label required" for="flight">{{ trans('cruds.price.fields.flight') }}</label>
        <x-select-list class="form-control" required id="flight" name="flight" :options="$this->listsForFields['flight']" wire:model.live="price.flight_id" />
        <div class="validation-message">
            {{ $errors->first('price.flight_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.price.fields.flight_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('price.adult_price') ? 'invalid' : '' }}">
        <label class="form-label required" for="adult_price">{{ trans('cruds.price.fields.adult_price') }}</label>
        <input class="form-control" type="number" name="adult_price" id="adult_price" required wire:model="price.adult_price" step="1">
        <div class="validation-message">
            {{ $errors->first('price.adult_price') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.price.fields.adult_price_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('price.child_price') ? 'invalid' : '' }}">
        <label class="form-label required" for="child_price">{{ trans('cruds.price.fields.child_price') }}</label>
        <input class="form-control" type="number" name="child_price" id="child_price" required wire:model="price.child_price" step="1">
        <div class="validation-message">
            {{ $errors->first('price.child_price') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.price.fields.child_price_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('price.infant_price') ? 'invalid' : '' }}">
        <label class="form-label required" for="infant_price">{{ trans('cruds.price.fields.infant_price') }}</label>
        <input class="form-control" type="number" name="infant_price" id="infant_price" required wire:model="price.infant_price" step="1">
        <div class="validation-message">
            {{ $errors->first('price.infant_price') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.price.fields.infant_price_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('price.activate_once') ? 'invalid' : '' }}">
        <label class="form-label" for="activate_once">{{ trans('cruds.price.fields.activate_once') }}</label>
        <input class="form-control" type="number" name="activate_once" id="activate_once" wire:model="price.activate_once" step="1">
        <div class="validation-message">
            {{ $errors->first('price.activate_once') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.price.fields.activate_once_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.prices.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>