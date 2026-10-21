<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Location
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-12">
                    <form wire:submit.prevent="submit" class="pt-3 row">
                        <div class="form-group {{ $errors->has('location.airport') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label required" for="airport">{{ trans('cruds.location.fields.airport') }}</label>
                            <input class="form-control" type="text" name="airport" id="airport" required wire:model.defer="location.airport">
                            <div class="validation-message">
                                {{ $errors->first('location.airport') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('location.airport_code') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label required" for="airport_code">Airport Code</label>
                            <input class="form-control" type="text" name="airport_code" id="airport_code" required wire:model.defer="location.airport_code">
                            <div class="validation-message">
                                {{ $errors->first('location.airport_code') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('location.city') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label required" for="city">{{ trans('cruds.location.fields.city') }}</label>
                            <input class="form-control" type="text" name="city" id="city" required wire:model.defer="location.city">
                            <div class="validation-message">
                                {{ $errors->first('location.city') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('location.city_code') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label required" for="city_code">City Code</label>
                            <input class="form-control" type="text" name="city_code" id="city_code" required wire:model.defer="location.city_code">
                            <div class="validation-message">
                                {{ $errors->first('location.city_code') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('location.country') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label" for="country">{{ trans('cruds.location.fields.country') }}</label>
                            <input class="form-control" type="text" name="country" id="country" wire:model.defer="location.country">
                            <div class="validation-message">
                                {{ $errors->first('location.country') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('location.country_code') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label" for="country">Country Code</label>
                            <input class="form-control" type="text" name="country_code" id="country_code" wire:model.defer="location.country_code">
                            <div class="validation-message">
                                {{ $errors->first('location.country_code') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('location.timezone') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label" for="timezone">Timezone</label>
                            <input class="form-control" type="text" name="timezone" id="timezone" wire:model.defer="location.timezone">
                            <div class="validation-message">
                                {{ $errors->first('location.timezone') }}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('location.active') ? 'invalid' : '' }} col-md-6">
                            <label class="form-label" for="active">{{ trans('cruds.location.fields.active') }}</label>
                            <select class="form-control" wire:model.defer="location.active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="validation-message">
                                {{ $errors->first('location.active') }}
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end col-12">
                            <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button class="btn btn-success ml-2" type="submit">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
