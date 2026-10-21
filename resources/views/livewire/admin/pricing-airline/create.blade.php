<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Pricing Airline
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <form wire:submit="submit" class="pt-3">
                <div class="row">
                    <div class="col-md-6 form-group {{ $errors->has('airline.name') ? 'invalid' : '' }}">
                        <label class="form-label required" for="name">{{ trans('cruds.airline.fields.name') }}</label>
                        <input class="form-control" type="text" name="name" id="name" required wire:model="airline.name">
                        <div class="validation-message">
                            {{ $errors->first('airline.name') }}
                        </div>
                    </div>
                    <div class="col-md-6 form-group {{ $errors->has('airline.tour_code') ? 'invalid' : '' }}">
                        <label class="form-label" for="tour_code">Tour Code</label>
                        <input maxlength="3" class="form-control" type="text" name="tour_code" id="tour_code" wire:model="airline.tour_code">
                        <div class="validation-message">
                            {{ $errors->first('airline.tour_code') }}
                        </div>
                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('airline.agency_remark') ? 'invalid' : '' }}">
                        <label class="form-label" for="agency_remark">Agency Remark</label>
                        <div>
                            <div id="agency_remark"
                                 x-data
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
                                    $wire.set('airline.agency_remark', quill.root.innerHTML, true)
                                })
                             ">
                                {!! $airline['agency_remark'] !!}
                            </div>
                        </div>
                        <div class="validation-message">
                            {{ $errors->first('airline.agency_remark') }}
                        </div>
                    </div>

                    <div class="col-md-6 form-group {{ $errors->has('airline.fare_remark') ? 'invalid' : '' }}">
                        <label class="form-label" for="fare_remark">Fare Remark</label>
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
                                const quill = new Quill($refs.fare_remark, { theme: 'snow', modules: {toolbar: toolbarOptions} });
                                quill.on('text-change', () => {
                                    $wire.set('airline.fare_remark', quill.root.innerHTML, true)
                                })
                             ">
                                {!! $airline['fare_remark'] !!}
                            </div>
                        </div>
                        <div class="validation-message">
                            {{ $errors->first('airline.agency_remark') }}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col form-group {{ $errors->has('airline.default_selling_commission') ? 'invalid' : '' }}">
                                <label class="form-label required" for="default_selling_commission">Default Selling Commission</label>
                                <input step=".01" maxlength="3" class="form-control" required type="number" name="default_selling_commission" id="default_selling_commission" wire:model="airline.default_selling_commission">
                                <div class="validation-message">
                                    {{ $errors->first('airline.default_selling_commission') }}
                                </div>
                            </div>

                            <div class="col form-group {{ $errors->has('airline.default_selling_service_fee') ? 'invalid' : '' }}">
                                <label class="form-label required" for="default_selling_service_fee">Default Selling Service Fee</label>
                                <input step=".01" maxlength="3" class="form-control" required type="number" name="default_selling_service_fee" id="default_selling_service_fee" wire:model="airline.default_selling_service_fee">
                                <div class="validation-message">
                                    {{ $errors->first('airline.default_selling_service_fee') }}
                                </div>
                            </div>

                            <div class="col form-group {{ $errors->has('airline.default_selling_fixed_fee') ? 'invalid' : '' }}">
                                <label class="form-label required" for="default_selling_fixed_fee">Default Selling Fixed Fee</label>
                                <input step=".01" maxlength="3" class="form-control" required type="number" name="default_selling_fixed_fee" id="default_selling_fixed_fee" wire:model="airline.default_selling_fixed_fee">
                                <div class="validation-message">
                                    {{ $errors->first('airline.default_selling_fixed_fee') }}
                                </div>
                            </div>

                            <div class="col form-group {{ $errors->has('airline.default_net_commission') ? 'invalid' : '' }}">
                                <label class="form-label required" for="default_selling_commission">Default Net Commission</label>
                                <input step=".1" maxlength="3" class="form-control" required type="number" name="default_net_commission" id="default_net_commission" wire:model="airline.default_net_commission">
                                <div class="validation-message">
                                    {{ $errors->first('airline.default_net_commission') }}
                                </div>
                            </div>

                            <div class="col form-group {{ $errors->has('airline.default_net_service_fee') ? 'invalid' : '' }}">
                                <label class="form-label required" for="default_net_service_fee">Default Net Service Fee</label>
                                <input step=".1" maxlength="3" class="form-control" required type="number" name="default_net_service_fee" id="default_net_service_fee" wire:model="airline.default_net_service_fee">
                                <div class="validation-message">
                                    {{ $errors->first('airline.default_selling_service_fee') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 form-group d-flex justify-content-end">
                        <button class="btn btn-primary ml-2" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
@endpush
