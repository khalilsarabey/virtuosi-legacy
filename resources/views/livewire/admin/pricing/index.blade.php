<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100"></div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Auto Pricing
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
            <div class="mt-5 row">
                <div class="col-md-6">
                    <label>Select Agency:</label>
                    <x-select-list id="agencies" name="agencies" wire:model="selectedAgency" :options="$agencies"></x-select-list>
                </div>
                <div class="col-md-6">
                    <div class="{{ $selectedAgency ? '' : 'd-none' }}">
                        <label>Select Airline</label>
                        <x-select-list id="airlines" wire:model="selectedAirline" name="airlines" :options="$airlines"></x-select-list>
                    </div>
                </div>
            </div>
        </div>
        @if($selectedAgency && $selectedAirline)
            <div wire:submit.prevent="save" class="container-fluid">
                <form class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="selling_commission">Selling Commission</label>
                                <input step=".01" type="number" class="form-control" id="selling_commission" wire:model.defer="pricings.selling_commission" />
                            </div>
                            <div class="col">
                                <label class="form-label" for="selling_service_fee">Selling Service Fee</label>
                                <input step=".01" type="number" class="form-control" id="selling_service_fee" wire:model.defer="pricings.selling_service_fee" />
                            </div>
                            <div class="col">
                                <label class="form-label" for="selling_fixed_fee">Selling Fixed Fee</label>
                                <input step=".01" type="number" class="form-control" id="selling_fixed_fee" wire:model.defer="pricings.selling_fixed_fee" />
                            </div>
                            <div class="col">
                                <label class="form-label" for="net_commission">Net Commission</label>
                                <input step=".01" type="number" class="form-control" id="net_commission" wire:model.defer="pricings.net_commission" />
                            </div>
                            <div class="col">
                                <label class="form-label" for="net_service_fee">Net Service Fee</label>
                                <input step=".01" type="number" class="form-control" id="net_service_fee" wire:model.defer="pricings.net_service_fee" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label" for="agency_remark">Agency Remark</label>
                        <div>
                            <div
                                wire:key="{{ rand() }}"
                                id="agency_remark"
                                 x-data
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
                                    $wire.set('pricings.agency_remark', quill.root.innerHTML, true)
                                })
                             ">
                                {!! $pricings['agency_remark'] !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label" for="fare_remark">Fare Remark</label>
                        <div>
                            <div id="fare_remark"
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
                                    $wire.set('pricings.fare_remark', quill.root.innerHTML, true)
                                })
                             ">
                                {!! $pricings['fare_remark'] !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-left">
                        @if(isset($pricings['airline_id']))
                            <div class="mt-3 font-size-13 text-primary">The above are the airline's default pricing.</div>
                        @else
                            <button type="button" class="btn btn-danger mt-3" wire:click="delete({{ $pricings['pricing_id'] }})">Restore</button>
                            <div class="mt-3 font-size-13 text-danger">This will restore this airline's default pricing for the selected agency.</div>
                            <div class="mt-3 font-size-13 text-danger">Please do not click Save after restoring.</div>
                        @endif
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </div>
                </form>
            </div>
        @endif
    </section>
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
