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
        @if(config('features.charter'))
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="mt-4">B2B Search Roundtrips</label>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="roundtrip" wire:model.live="roundtrip">
                        <label class="form-check-label mt-1" for="roundtrip">
                            Enable
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="mt-4">B2B Search Multi Destinations</label>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="multiple" wire:model.live="multiple">
                        <label class="form-check-label mt-1" for="multiple">
                            Enable
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="mt-4">B2B Search Fixed Departure</label>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fixed" wire:model.live="fixed">
                        <label class="form-check-label mt-1" for="fixed">
                            Enable
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="mt-4">B2B Search Oneway</label>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="oneway" wire:model.live="oneway">
                        <label class="form-check-label mt-1" for="oneway">
                            Enable
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(config('features.lowcost') && auth()->user()->agency_id == 1)
            <div class="container-fluid mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <label class="mt-4">Low Cost</label>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="lowcost" wire:model.live="lowcost">
                            <label class="form-check-label mt-1" for="lowcost">
                                Enable
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(config('features.hotels'))
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="mt-4">Hotels</label>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hotels" wire:model.live="hotels">
                        <label class="form-check-label mt-1" for="hotels">
                            Enable
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(config('features.packages'))
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="mt-4">Packages</label>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="packages" wire:model.live="packages">
                        <label class="form-check-label mt-1" for="packages">
                            Enable
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(config('features.autopricing') && auth()->user()->agency_id == 1)
            <div class="container-fluid mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <label class="mt-4">Auto Pricing</label>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="autopricing" wire:model.live="autopricing">
                            <label class="form-check-label mt-1" for="autopricing">
                                Enable
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="mt-4">Customer Support</label>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="tawk" wire:model.live="tawk">
                        <label class="form-check-label mt-1" for="tawk">
                            Enable
                        </label>
                    </div>
                </div>
                @if($tawk)
                    <div class="col-md-4">
                        <label>API Key</label>
                        <input type="text" class="form-control" name="tawkApi" wire:model.live="tawkApi"/>
                        <div class="text-sm text-info mt-2">Please visit <a href="https://www.tawk.to">Tawk.to</a> and
                            signup for a free account to get your API key.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Widget ID</label>
                        <input type="text" class="form-control" name="tawkApi" wire:model.live="tawkWidgetId"/>
                    </div>
                    <div class="col-md-4 d-flex align-items-end justify-content-end">
                        <button wire:click="save" class="btn btn-sm btn-primary">Save</button>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
