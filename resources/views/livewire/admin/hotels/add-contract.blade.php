<div>
    <x-hotels-menu />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    {{ $hotel->name }} - Add Contract
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div
            class="container-fluid"
            x-data="{bb: false, hb: false, fb: false, ai: false, sai: false, uai: false, breakfast: false, lunch: false, dinner: false, extra: false}"
        >
            <div class="row">
                <div class="col-3">
                    <label>Select Season</label>
                    <x-select-list required class="form-control" id="season" name="season" :options="$seasons" wire:model.live="from.season_id" />
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-4">
                    <div class="d-flex mb-3">
                        <div class="w-25">
                            <div class="form-check">
                                <input x-model="bb" class="form-check-input mt-0" type="checkbox" id="bb"  wire:model.live="form.bb_status">
                                <label class="form-check-label" for="bb">
                                    B/B
                                </label>
                            </div>
                        </div>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-right-0">$</span>
                            <input type="number" class="form-control border-left-0" x-bind:disabled="!bb" wire:model="form.bb">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-25">
                            <div class="form-check">
                                <input x-model="hb" class="form-check-input mt-0" type="checkbox" id="hb" wire:model.live="form.hb_status">
                                <label class="form-check-label" for="hb">
                                    H/B
                                </label>
                            </div>
                        </div>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-right-0">$</span>
                            <input type="number" class="form-control border-left-0" x-bind:disabled="!hb" wire:model="form.hb">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-25">
                            <div class="form-check">
                                <input x-model="fb" class="form-check-input mt-0" type="checkbox" id="fb" wire:model.live="form.fb_status">
                                <label class="form-check-label" for="fb">
                                    F/B
                                </label>
                            </div>
                        </div>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-right-0">$</span>
                            <input type="number" class="form-control border-left-0" x-bind:disabled="!fb" wire:model="form.fb">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-25">
                            <div class="form-check">
                                <input x-model="ai" class="form-check-input mt-0" type="checkbox" id="ai" wire:model.live="form.ai_status">
                                <label class="form-check-label" for="ai">
                                    A/I
                                </label>
                            </div>
                        </div>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-right-0">$</span>
                            <input type="number" class="form-control border-left-0" x-bind:disabled="!ai" wire:model="form.ai">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-25">
                            <div class="form-check">
                                <input x-model="sai" class="form-check-input mt-0" type="checkbox" id="sai" wire:model.live="form.sai_status">
                                <label class="form-check-label" for="sai">
                                    SA/I
                                </label>
                            </div>
                        </div>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-right-0">$</span>
                            <input type="number" class="form-control border-left-0" x-bind:disabled="!sai" wire:model="form.sai">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-25">
                            <div class="form-check">
                                <input class="form-check-input mt-0" type="checkbox" x-model="uai" id="uai" wire:model.live="form.uai_status">
                                <label class="form-check-label" for="uai">
                                    UA/I
                                </label>
                            </div>
                        </div>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-right-0">$</span>
                            <input type="number" class="form-control border-left-0" x-bind:disabled="!uai" wire:model="form.uai">
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex mb-3">
                        <div class="w-50">
                            <div class="form-check">
                                <input class="form-check-input mt-0" type="checkbox" id="breakfast_status" x-model="breakfast">
                                <label class="form-check-label" for="breakfast_status">
                                    Breakfast Meal
                                </label>
                            </div>
                        </div>
                        <input type="text" class="form-control w-50" x-bind:disabled="!breakfast" />
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-50">
                            <div class="form-check">
                                <input class="form-check-input mt-0" type="checkbox" id="lunch_status" x-model="lunch">
                                <label class="form-check-label" for="lunch_status">
                                    Lunch Meal
                                </label>
                            </div>
                        </div>
                        <input type="text" class="form-control w-50" x-bind:disabled="!lunch" />
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-50">
                            <div class="form-check">
                                <input class="form-check-input mt-0" type="checkbox" id="dinner_status" x-model="dinner">
                                <label class="form-check-label" for="dinner_status">
                                    Dinner Meal
                                </label>
                            </div>
                        </div>
                        <input type="text" class="form-control w-50" x-bind:disabled="!dinner" />
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-50">
                            <div class="form-check">
                                <input class="form-check-input mt-0" type="checkbox" id="extra_child_meals_status" x-model="extra">
                                <label class="form-check-label" for="extra_child_meals_status">
                                    Extra Child Meals
                                </label>
                            </div>
                        </div>
                        <input type="text" class="form-control w-50" x-bind:disabled="!extra" />
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button type="button" wire:click="save" class="btn btn-primary font-weight-bold">Save & Add Rooms</button>
                </div>
            </div>

        </div>
    </section>
</div>
@push('scripts')
    <script>
        window.addEventListener('close-modals', event => {
            $('.close-modal').trigger('click');
        })
    </script>
@endpush
