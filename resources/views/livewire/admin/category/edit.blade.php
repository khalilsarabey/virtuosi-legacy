<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Category
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
                    <div class="col-12">
                        <div>
                            <div class="form-group {{ $errors->has('category.section_id') ? 'invalid' : '' }}">
                                <label class="form-label" for="section">Section</label>
                                <x-select-list class="form-control w-100" :options="$sections" wire:model="category.section_id" />
                                <div class="validation-message">
                                    {{ $errors->first('category.section_id') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('category.name') ? 'invalid' : '' }}">
                                    <label class="form-label required" for="name">{{ trans('cruds.category.fields.name') }}</label>
                                    <input class="form-control" type="text" name="name" id="name" wire:model.defer="category.name">
                                    <div class="validation-message">
                                        {{ $errors->first('category.name') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-group w-100">
                                    <select class="form-control" wire:model="category.show_title">
                                        <option value="1">Show Title</option>
                                        <option value="0">Hide Title</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if($type == 'linked_images')
                            <div>
                                <div class="form-group {{ $errors->has('category.link') ? 'invalid' : '' }}">
                                    <label class="form-label" for="link">Link</label>
                                    <input class="form-control" type="text" name="link" id="link" wire:model.defer="category.link">
                                    <div class="validation-message">
                                        {{ $errors->first('category.link') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="">
                            <div
                                class="form-group {{ $errors->has('mediaCollections.category_image') ? 'invalid' : '' }}">
                                <label class="form-label" for="image">Thumbnail</label>
                                <x-dropzone id="image" name="image" action="{{ route('admin.categories.storeMedia') }}"
                                            collection-name="category_image" max-file-size="2" max-width="4096"
                                            max-height="4096" max-files="1"/>
                                <div class="validation-message">
                                    {{ $errors->first('mediaCollections.category_image') }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <div
                                class="form-group {{ $errors->has('mediaCollections.category_banner') ? 'invalid' : '' }}">
                                <label class="form-label" for="banner">Banner</label>
                                <x-dropzone id="banner" name="banner"
                                            action="{{ route('admin.categories.storeMedia') }}"
                                            collection-name="category_banner" max-file-size="2" max-width="4096"
                                            max-height="4096" max-files="1"/>
                                <div class="validation-message">
                                    {{ $errors->first('mediaCollections.category_banner') }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" wire:model="category.active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>HIDE FROM THE FOLLOWING USERS & AGENCIES</label>
                            <x-select-list buttons multiple class="form-control" id="agencies_permissions" name="agencies_permissions" :options="$this->listsForFields['users']" wire:model="category.agencies_permissions" />
                        </div>
                        @if(config('features.whitelabel'))
                        <div class="form-group">
                            <label>HIDE FROM THE FOLLOWING WHITE LABELS</label>
                            <x-select-list buttons multiple class="form-control" id="whitelabel_permissions" name="whitelabel_permissions" :options="$this->listsForFields['whitelabels']" wire:model="category.whitelabel_permissions" />
                        </div>
                        @endif
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <div class="form-group">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button class="btn btn-primary mr-2" type="submit">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
