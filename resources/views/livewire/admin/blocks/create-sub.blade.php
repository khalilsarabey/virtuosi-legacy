<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Add Column
                </h6>
            </div>
        </div>
        <style>
            .table td, .table th {
                vertical-align: middle;
            }
        </style>
        <div class="container-fluid">
            <form wire:submit="submit">
                <div class="row">
                    <div class=" col-12 col-lg-3">
                        <div class="form-group {{ $errors->has('block.name') ? 'invalid' : '' }}">
                            <label class="form-label required" for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" required wire:model="block.name">
                            <div class="validation-message">
                                {{ $errors->first('block.name') }}
                            </div>
                        </div>
                    </div>

                    <div class=" col-12 col-lg-3">
                        <div class="form-group {{ $errors->has('block.link') ? 'invalid' : '' }}">
                            <label class="form-label" for="link">Link</label>
                            <input class="form-control" type="text" name="link" id="link" wire:model="block.link">
                            <div class="validation-message">
                                {{ $errors->first('block.link') }}
                            </div>
                        </div>
                    </div>

                    <div class=" col-12 col-lg-6 d-lg-flex align-items-center justify-content-lg-around">
                        <div>
                            <div class="form-group form-check {{ $errors->has('block.active') ? 'invalid' : '' }}">
                                <input type="checkbox" class="form-check-input" id="active" wire:model.live="block.active">
                                <label class="form-check-label pt-1" for="active">Active</label>
                            </div>
                        </div>
                        <div>
                            <div class="form-group form-check {{ $errors->has('block.hide_title') ? 'invalid' : '' }}">
                                <input type="checkbox" class="form-check-input" id="hide_title"
                                       wire:model.live="block.hide_title">
                                <label class="form-check-label pt-1" for="hide_title">Hide Title</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="form-group {{ $errors->has('mediaCollections.block_thumbnail') ? 'invalid' : '' }}">
                            <label class="form-label required" for="thumbnail">Thumbnail</label>
                            <x-dropzone id="thumbnail" name="thumbnail" action="{{ route('admin.blocks.storeMedia') }}"
                                        collection-name="block_thumbnail" max-file-size="2" max-width="4096"
                                        max-height="4096" max-files="1"/>
                            <div class="validation-message">
                                {{ $errors->first('mediaCollections.block_thumbnail') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn-primary btn-sm mr-2">{{ trans('global.save') }}</button>
                            <a href="{{ route('admin.blocks.index') }}"
                               class="btn btn-secondary btn-sm">{{ trans('global.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
