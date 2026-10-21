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
                    <form wire:submit.prevent="submit" class="pt-3">
                        <div class="form-group {{ $errors->has('contract.name') ? 'invalid' : '' }}">
                            <label class="form-label required" for="name">{{ trans('cruds.contract.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" required wire:model.defer="contract.name">
                            <div class="validation-message">
                                {{ $errors->first('contract.name') }}
                            </div>
                            <div class="help-block">
                                {{ trans('cruds.contract.fields.name_helper') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('contract.content') ? 'invalid' : '' }}">
                            <label class="form-label required" for="content">Content</label>
                            <div class="mt-2 bg-white" wire:ignore>
                                <div
                                    x-data
                                    x-ref="quillEditor"
                                    x-init="
                                    quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                    quill.on('text-change', function () {
                                    $dispatch('input', quill.root.innerHTML);
                                    });
                                    "
                                    wire:model.debounce.500ms="contract.content"
                                >
                                    {!! $contract->content !!}
                                </div>
                            </div>
                            <div class="validation-message">
                                {{ $errors->first('contract.content') }}
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button wire:loading.attr="disabled" class="btn btn-success ml-2" type="submit">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <link href="https://cdn.quilljs.com/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/quill.js"></script>
@endpush
