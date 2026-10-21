<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Section
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
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('section.type') ? 'invalid' : '' }}">
                                    <label class="form-label">Section Type</label>
                                    <select required class="form-control" wire:model.live="section.type">
                                        <option value="null" disabled>Select...</option>
                                        @foreach($this->listsForFields['type'] as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div class="validation-message">
                                        {{ $errors->first('section.type') }}
                                    </div>
                                </div>
                            </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('section.layout') ? 'invalid' : '' }}">
                                        <label class="form-label">Layout</label>
                                        <select class="form-control" wire:model.live="section.layout">
                                            <option value="1">1 Column per row</option>
                                            <option value="2">2 Columns per row</option>
                                            <option value="3">3 Columns per row</option>
                                            <option value="4">4 Columns per row</option>
                                        </select>
                                        <div class="validation-message">
                                            {{ $errors->first('section.layout') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('section.name') ? 'invalid' : '' }}">
                                        <label class="form-label required" for="name">Name</label>
                                        <input class="form-control" type="text" name="name" id="name" required
                                               wire:model="section.name">
                                        <div class="validation-message">
                                            {{ $errors->first('section.name') }}
                                        </div>
                                    </div>
                                </div>
                        </div>
                            <div class="col-12 d-flex justify-content-start">
                                <div class="form-group my-4 {{ $errors->has('section.active') ? 'invalid' : '' }}">
                                    <input class="form-check-input" type="checkbox" name="active" id="active"
                                           wire:model="section.active">
                                    <label class="form-check-label mt-1 ml-1" for="active">Active</label>
                                    <div class="validation-message">
                                        {{ $errors->first('section.active') }}
                                    </div>
                                </div>
                            </div>
                        <div class="col-12 mt-3">
                            <label>HIDE FROM THE FOLLOWING USERS & AGENCIES</label>
                            <x-select-list buttons multiple class="form-control" id="agencies_permissions" name="agencies_permissions" :options="$this->listsForFields['users']" wire:model.live="section.agencies_permissions" />
                        </div>
                        @if(config('features.whitelabel'))
                        <div class="col-12 mt-3">
                            <label>HIDE FROM THE FOLLOWING WHITE LABELS</label>
                            <x-select-list buttons multiple class="form-control" id="whitelabel_permissions" name="whitelabel_permissions" :options="$this->listsForFields['whitelabels']" wire:model.live="section.whitelabel_permissions" />
                        </div>
                        @endif
                        <div class="col-12 d-flex justify-content-end">
                            <div class="form-group">
                                <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button class="btn btn-success ml-2" type="submit">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
