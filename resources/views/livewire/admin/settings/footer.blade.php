<div>
    <div class="admin-header position-relative">
        <div class="my-auto w-100">
        </div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <div class="btn-group" style="border-radius: 10px;" role="group" aria-label="Basic example">
                    <x-admin.settings.my-agency-menu />
                </div>
            </div>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <form wire:submit.prevent="save">
            <div class="container-fluid mb-4">
                <div class="row">
                    <div class="col-12">
                        <label class="form-label required" for="content">Footer Quote</label>
                        <textarea rows="4" wire:model.defer="agency.footer_quote" class="form-control">{!! $agency->footer_quote !!}</textarea>
                        <div class="validation-message">
                            {{ $errors->first('agency.footer_quote') }}
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-lg-4">
                        <label class="form-label required" for="contact">Contact Email </label>
                        <div class="mt-2 bg-white">
                            <input type="email" name="contact_email" class="form-control" wire:model.defer="agency.contact_email"/>
                        </div>
                        <div class="validation-message">
                            {{ $errors->first('agency.contact_email') }}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label required" for="contact">Contact Phone </label>
                        <div class="mt-2 bg-white">
                            <input type="text" name="contact_phone" class="form-control" wire:model.defer="agency.contact_phone"/>
                        </div>
                        <div class="validation-message">
                            {{ $errors->first('agency.contact_phone') }}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label required" for="contact">Contact Address </label>
                        <div class="mt-2 bg-white">
                            <input type="text" name="contact_address" class="form-control" wire:model.defer="agency.contact_address"/>
                        </div>
                        <div class="validation-message">
                            {{ $errors->first('agency.contact_address') }}
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Facebook</label>
                        <input type="text" wire:model.defer="agency.facebook" name="facebook" class="form-control"/>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Instagram</label>
                        <input type="text" wire:model.defer="agency.instagram" name="instagram" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Linkedin</label>
                        <input type="text" wire:model.defer="agency.linkedin" name="linkedin" class="form-control"/>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Twitter</label>
                        <input type="text" wire:model.defer="agency.twitter" name="twitter" class="form-control"/>
                    </div>
                </div>
                <hr/>
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary ">Save</button>
                </div>
            </div>
        </form>
    </section>
</div>
@push('scripts')
    <link href="https://cdn.quilljs.com/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/quill.js"></script>
@endpush
