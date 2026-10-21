<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Custom ({{ $section->title }})
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                @include('components.admin-auto-pricing-menu')
            </div>
        </div>
        <div class="container-fluid">
            <div class="mt-5 row">
                <div class="col-md-6">
                    <label>Select Agency:</label>
                    <x-select-list id="agencies" name="agencies" wire:model="selectedAgency" :options="$agencies"></x-select-list>
                </div>
                <div class="col-md-6">
                    <div class="{{ $selectedAgency ? '' : 'd-none' }}">
                        <label>Select Destination</label>
                        <x-select-list id="airlines" wire:model="selectedDestination" name="destinations" :options="$destinations"></x-select-list>
                    </div>
                </div>
            </div>
            @if($selectedAgency && $selectedDestination)
                @if(!$customDestination)
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label>Agency Remark</label>
                            <div>
                                <div x-data
                                     wire:key="{{ rand() }}"
                                     x-ref="agency_remark"
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
                                            const quill = new Quill($refs.agency_remark, {
                                                theme: 'snow',
                                                  modules: {
                                                    toolbar: toolbarOptions
                                                  }
                                            });
                                            quill.on('text-change', () => {
                                                $wire.set('agency_remark', quill.root.innerHTML, true)
                                            })
                                         ">
                                    {!! $agency_remark !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Fare Remark</label>
                            <div>
                                <div
                                    wire:key="{{ rand() }}"
                                    x-data
                                     x-ref="fare_remark"
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
                                            const quill = new Quill($refs.fare_remark, {
                                                theme: 'snow',
                                                  modules: {
                                                    toolbar: toolbarOptions
                                                  }
                                            });
                                            quill.on('text-change', () => {
                                                $wire.set('fare_remark', quill.root.innerHTML, true)
                                            })
                                         ">
                                    {!! $fare_remark !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect mt-3" wire:click="create">Create</button>
                        </div>
                    </div>
                @else
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label>Agency Remark</label>
                            <div>
                                <div x-data
                                     wire:key="{{ rand() }}"
                                     x-ref="agency_remark"
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
                                            const quill = new Quill($refs.agency_remark, {
                                                theme: 'snow',
                                                  modules: {
                                                    toolbar: toolbarOptions
                                                  }
                                            });
                                            quill.on('text-change', () => {
                                                $wire.set('agency_remark', quill.root.innerHTML, true)
                                            })
                                         ">
                                    {!! $agency_remark !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Fare Remark</label>
                            <div>
                                <div x-data
                                     wire:key="{{ rand() }}"
                                     x-ref="fare_remark"
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
                                            const quill = new Quill($refs.fare_remark, {
                                                theme: 'snow',
                                                  modules: {
                                                    toolbar: toolbarOptions
                                                  }
                                            });
                                            quill.on('text-change', () => {
                                                $wire.set('fare_remark', quill.root.innerHTML, true)
                                            })
                                         ">
                                    {!! $fare_remark !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-xs px-4 py-2 btn-danger uppercase waves-effect mt-3" wire:click="deleteCustom({{ $customDestination->id }})">Delete Customization</button>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect mt-3" wire:click="update">Update Remarks</button>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="d-flex justify-content-between mb-4 align-items-center">
                                <h6 class="text-uppercase mb-3 font-weight-bold">Classes</h6>
                                <button class="btn btn-secondary btn-sm" wire:click="import" type="button">Import Classes from Destination</button>
                            </div>
                            <table class="table table-index w-full">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customDestination->classes as $c)
                                        <tr>
                                            <td>{{ $c->name }}</td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-center">
                                                        <label>Selling Commission</label>
                                                        <div class="mt-1">{{ $c->selling_commission }}</div>
                                                    </div>
                                                    <div class="text-center">
                                                        <label>Selling Service Fee</label>
                                                        <div class="mt-1">{{ $c->selling_service_fee }}</div>
                                                    </div>
                                                    <div class="text-center">
                                                        <label>Selling Fixed Fee</label>
                                                        <div class="mt-1">{{ $c->selling_fixed_fee }}</div>
                                                    </div>
                                                    <div class="text-center">
                                                        <label>Net Commission</label>
                                                        <div class="mt-1">{{ $c->net_commission }}</div>
                                                    </div>
                                                    <div class="text-center">
                                                        <label>Net Service Fee</label>
                                                        <div class="mt-1">{{ $c->net_service_fee }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end align-items-center mt-2">
                                                    <button wire:click="editClass({{ $c->id }})" type="button" data-bs-toggle="modal" data-bs-target="#editClassModal" class="btn btn-xs btn-outline-success border-1 border-success mr-2">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-xs btn-outline-danger border-1 border-danger mr-2" type="button"
                                                            wire:click="confirm('deleteClass', {{ $c->id }})"
                                                            wire:loading.attr="disabled">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#createClassModal" class="btn btn-xs btn-primary mr-2">
                                                Add Class
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>
    <div wire:ignore.self class="modal fade" id="createClassModal" tabindex="-1" aria-labelledby="createClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="createClassModalLabel">Add class</h5>
                    <button type="button" class="btn-close border-0 bg-white" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <label>Class Name</label>
                                <input type="text" class="form-control" placeholder="" wire:model.defer="class_name" />
                            </div>
                            <div class="col-6">
                                <label>Selling Commission</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="selling_commission" />
                            </div>
                            <div class="col-6">
                                <label>Selling Service Fee</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="selling_service_fee" />
                            </div>
                            <div class="col-6">
                                <label>Selling Fixed Fee</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="selling_fixed_fee" />
                            </div>
                            <div class="col-6">
                                <label>Net Commission</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="net_commission" />
                            </div>
                            <div class="col-6">
                                <label>Net Service Fee</label>
                                <input step=".01"  type="number" class="form-control" placeholder="" wire:model.defer="net_service_fee" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="saveClass" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="editClassModalLabel">Edit Class</h5>
                    <button type="button" class="btn-close border-0 bg-white" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <label>Class Name</label>
                                <input type="text" class="form-control" placeholder="" wire:model.defer="class_name" />
                            </div>
                            <div class="col-6">
                                <label>Selling Commission</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="selling_commission" />
                            </div>
                            <div class="col-6">
                                <label>Selling Service Fee</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="selling_service_fee" />
                            </div>
                            <div class="col-6">
                                <label>Selling Fixed Fee</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="selling_fixed_fee" />
                            </div>
                            <div class="col-6">
                                <label>Net Commission</label>
                                <input step=".01" type="number" class="form-control" placeholder="" wire:model.defer="net_commission" />
                            </div>
                            <div class="col-6">
                                <label>Net Service Fee</label>
                                <input step=".01"  type="number" class="form-control" placeholder="" wire:model.defer="net_service_fee" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="updateClass" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    <link href="https://cdn.quilljs.com/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/quill.js"></script>
    <style>
        .ql-editor {
            height: 300px;
        }
    </style>
    <script>
        Livewire.on('confirm', e => {
            if (!confirm("{{ trans('global.areYouSure') }}")) {
                return
            }
                @this[e.callback](...e.argv)
        })
    </script>
@endpush
