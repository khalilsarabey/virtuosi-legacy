<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Agency Notes
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            @if($errors->any())

            @endif
            <form class="pt-3">
                <div class="row">
                    @if(auth()->user()->type == 'admin' || auth()->user()->type == 'white-admin')
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('notes') ? 'invalid' : '' }}">
                                <label class="form-label required" for="content">Content</label>

                                <div class="mt-2 bg-white">
                                    <div id="notes"
                                         wire:key="{{ rand() }}"
                                         x-data
                                         x-ref="notes"
                                         x-init="
                                console.log('init');
                                var toolbarOptions = [
                                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    [{ 'color': [] }, { 'background': [] }],
                                    [{ 'align': [] }],
                                    ['clean']
                                ];
                                const quill = new Quill($refs.notes, {
                                    theme: 'snow',
                                      modules: {
                                        toolbar: toolbarOptions
                                      }
                                });
                                quill.on('text-change', () => {
                                    $wire.set('notes', quill.root.innerHTML, true)
                                })
                             ">
                                        {!! $notes !!}
                                    </div>
                                </div>
                                <div class="validation-message">
                                    {{ $errors->first('notes') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="form-group text-right">
                    <button wire:click="submit" class="btn btn-success mr-2" type="button">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@push('scripts')
    <link href="https://cdn.quilljs.com/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/quill.js"></script>
@endpush
