<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Block
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <form wire:submit.prevent="submit" class="row d-flex align-items-center">
                <div class="col-12 col-lg-3">
                    <div class="form-group {{ $errors->has('block.name') ? 'invalid' : '' }}">
                        <label class="form-label required" for="name">Name</label>
                        <input class="form-control" type="text" name="name" id="name" required wire:model.defer="block.name">
                        <div class="validation-message">
                            {{ $errors->first('block.name') }}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="form-group {{ $errors->has('block.layout') ? 'invalid' : '' }}">
                        <label class="form-label required">Layout</label>
                        <select class="form-control" wire:model="block.layout">
                            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
                            @foreach($this->listsForFields['layout'] as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <div class="validation-message">
                            {{ $errors->first('block.layout') }}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 d-flex align-items-center justify-content-center">
                    <div class="form-check form-switch {{ $errors->has('block.hide_title') ? 'invalid' : '' }}">
                        <input class="form-check-input" type="checkbox" name="hide_title" id="hide_title" wire:model.defer="block.hide_title">
                        <label class="form-check-label" for="hide_title">Hide Title</label>
                    </div>
                    <div class="validation-message">
                        {{ $errors->first('block.hide_title') }}
                    </div>
                </div>
                <div class="col-12 col-lg-3 d-flex align-items-center justify-content-center">
                    <div class="form-check form-switch {{ $errors->has('block.active') ? 'invalid' : '' }}">
                        <input class="form-check-input" type="checkbox" name="active" id="active" wire:model.defer="block.active">
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="validation-message">
                        {{ $errors->first('block.hide_title') }}
                    </div>
                </div>
                <div class="col-12 text-right">
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm mr-2" type="submit">
                            {{ trans('global.save') }}
                        </button>
                        <a href="{{ route('admin.blocks.index') }}" class="btn btn-secondary btn-sm">
                            {{ trans('global.cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
