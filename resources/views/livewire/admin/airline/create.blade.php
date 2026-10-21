<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Airline
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <form wire:submit.prevent="submit" class="pt-3">
                <div class="row">
                    <div class="col-md-3 form-group {{ $errors->has('airline.name') ? 'invalid' : '' }}">
                        <label class="form-label required" for="name">{{ trans('cruds.airline.fields.name') }}</label>
                        <input class="form-control" type="text" name="name" id="name" required wire:model.defer="airline.name">
                        <div class="validation-message">
                            {{ $errors->first('airline.name') }}
                        </div>
                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('airline.icao') ? 'invalid' : '' }}">
                        <label class="form-label" for="icao">ICAO Code</label>
                        <input maxlength="3" class="form-control" type="text" name="icao" id="icao" wire:model.defer="airline.icao">
                        <div class="validation-message">
                            {{ $errors->first('airline.icao') }}
                        </div>
                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('airline.iata') ? 'invalid' : '' }}">
                        <label class="form-label" for="iata">IATA Code</label>
                        <input maxlength="3" class="form-control" type="text" name="iata" id="iata" wire:model.defer="airline.iata">
                        <div class="validation-message">
                            {{ $errors->first('airline.iata') }}
                        </div>
                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('airline.sign') ? 'invalid' : '' }}">
                        <label class="form-label required" for="sign">Sign</label>
                        <input class="form-control" type="text" name="sign" id="sign" required wire:model.defer="airline.sign">
                        <div class="validation-message">
                            {{ $errors->first('airline.sign') }}
                        </div>
                    </div>
                    <div class="col-md-3 form-group {{ $errors->has('airline.country') ? 'invalid' : '' }}">
                        <label class="form-label required" for="country">{{ trans('cruds.airline.fields.country') }}</label>
                        <input class="form-control" type="text" name="country" id="country" required wire:model.defer="airline.country">
                        <div class="validation-message">
                            {{ $errors->first('airline.country') }}
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end form-group {{ $errors->has('airline.combinable') ? 'invalid' : '' }}">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="combinable" wire:model.defer="airline.combinable">
                            <label class="form-check-label mt-1" for="combinable">
                                Combinable
                            </label>
                        </div>
                        <div class="validation-message">
                            {{ $errors->first('airline.combinable') }}
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label required" for="file">Logo</label>
                        <input type="file" class="form-control" id="exampleInputName" wire:model="file">
                        @if ($file)
                            <img class="mt-3 img-fluid" src="{{ $file->temporaryUrl() }}" />
                        @endif
                        @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 form-group">

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
