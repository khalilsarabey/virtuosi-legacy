<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Airline
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <form wire:submit="submit" class="pt-3">
                <div class="row">
                    <div class="col-md-3 form-group {{ $errors->has('airline.name') ? 'invalid' : '' }}">
                        <label class="form-label required" for="name">{{ trans('cruds.airline.fields.name') }}</label>
                        <input class="form-control" type="text" name="name" id="name" required wire:model="airline.name">
                        <div class="validation-message">
                            {{ $errors->first('airline.name') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.airline.fields.name_helper') }}
                        </div>
                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('airline.icao') ? 'invalid' : '' }}">
                        <label class="form-label" for="icao">Code</label>
                        <input maxlength="3" class="form-control" type="text" name="icao" id="icao" wire:model="airline.icao">
                        <div class="validation-message">
                            {{ $errors->first('airline.icao') }}
                        </div>
                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('airline.country') ? 'invalid' : '' }}">
                        <label class="form-label required" for="country">{{ trans('cruds.airline.fields.country') }}</label>
                        <input class="form-control" type="text" name="country" id="country" required wire:model="airline.country">
                        <div class="validation-message">
                            {{ $errors->first('airline.country') }}
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end form-group {{ $errors->has('airline.combinable') ? 'invalid' : '' }}">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="combinable" wire:model="airline.combinable">
                            <label class="form-check-label mt-1" for="combinable">
                                Combinable
                            </label>
                        </div>
                        <div class="validation-message">
                            {{ $errors->first('airline.combinable') }}
                        </div>
                    </div>
                    <div class="col-md-12 form-group d-flex justify-content-end">
                        <a href="{{ route('admin.airlines.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button class="btn btn-primary ml-2" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
