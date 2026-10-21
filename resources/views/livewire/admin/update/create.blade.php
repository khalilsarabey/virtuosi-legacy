<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Contract
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
                    <form wire:submit="submit" class="pt-3">
                        <div class="form-group {{ $errors->has('update.text') ? 'invalid' : '' }}">
                            <label class="form-label required" for="text">{{ trans('cruds.update.fields.text') }}</label>
                            <input class="form-control" type="text" name="text" id="text" required wire:model="update.text">
                            <div class="validation-message">
                                {{ $errors->first('update.text') }}
                            </div>
                            <div class="help-block">
                                {{ trans('cruds.update.fields.text_helper') }}
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <button class="btn btn-primary mr-2" type="submit">
                                Save
                            </button>
                            <a href="{{ route('admin.updates.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
