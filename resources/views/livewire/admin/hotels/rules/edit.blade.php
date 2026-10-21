<div>
    <x-hotels-menu menu="rules" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="p-4 admin-container shadow">
                    <div class="border-bottom-blueGray-200 w-100">
                        <div class="d-flex justify-content-between">
                            <h6 class="header-card-title">
                                Edit Hotel Rule
                                <div wire:loading.delay>
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden"></span>
                                    </div>
                                </div>
                            </h6>
                        </div>
                    </div>
                    <form wire:submit="update">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Title (Used for display purposes only)</label>
                                    <input type="text" wire:model="title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select wire:model.live="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" wire:model.live="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" wire:model.live="end_date" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Apply only when duration is</label>
                                    <select wire:model.live="duration_type" class="form-control">
                                        <option value="">Any</option>
                                        <option value="less_than">Less Than</option>
                                        <option value="greater_than">Greater Than</option>
                                    </select>
                                </div>
                            </div>
                            @if($duration_type == 'less_than' || $duration_type == 'greater_than')
                            <div class="col">
                                <div class="form-group">
                                    <label>Trip Duration in Days</label>
                                    <input type="number" wire:model="duration" class="form-control">
                                </div>
                            </div>
                            @endif
                            <div class="col">
                                <div class="form-group">
                                    <label>Apply To Hotels</label>
                                    <x-select-list
                                        class="form-control"
                                        id="hotel_ids"
                                        name="hotel_ids"
                                        :options="$hotels_list"
                                        wire:model.live="hotel_ids"
                                        multiple
                                        placeholder="Select hotels (leave empty for all)"
                                    />
                                    <small class="form-text text-muted">You may leave empty to apply to all hotels. Search and select multiple hotels as needed.</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    @foreach($sub_rules as $i => $subRule)
                                        <div class="mb-2 p-2">
                                            <div class="form-row align-items-end">
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-light btn-sm mb-1" wire:click="moveSubRuleUp({{ $i }})" @if($i == 0) disabled @endif>&uarr;</button>
                                                    <button type="button" class="btn btn-light btn-sm mb-1" wire:click="moveSubRuleDown({{ $i }})" @if($i == count($sub_rules) - 1) disabled @endif>&darr;</button>
                                                </div>
                                                <div class="col">
                                                    <label>Amount Type</label>
                                                    <select class="form-control" wire:model.live="sub_rules.{{ $i }}.amount_type">
                                                        <option value="percentage">Percentage</option>
                                                        <option value="fixed">Fixed</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label>Amount</label>
                                                    <input type="number" class="form-control" wire:model.live="sub_rules.{{ $i }}.amount">
                                                </div>
                                                @if(isset($subRule['amount_type']) && $subRule['amount_type'] == 'percentage')
                                                    <div class="col">
                                                        <label>Applicable To</label>
                                                        <select class="form-control" wire:model.live="sub_rules.{{ $i }}.applicable_to">
                                                            <option value="hotel">Hotel</option>
                                                            <option value="ticket">Ticket</option>
                                                            <option value="total">Total</option>
                                                        </select>
                                                    </div>
                                                @endif
                                                <div class="col d-flex align-self-center mt-4">
                                                    @if(isset($subRule['amount_type']) && $subRule['amount_type'] == 'fixed')
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" wire:model.live="sub_rules.{{ $i }}.per_person" id="per_person_{{ $i }}">
                                                            <label class="form-check-label mt-1" for="per_person_{{ $i }}">Per Person</label>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col d-flex align-items-center justify-content-end">
                                                    <button type="button" class="btn btn-danger btn-sm" wire:click="removeSubRule({{ $i }})">&times;</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" wire:click="addSubRule">
                                            <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 width="10px" height="10px" viewBox="0 0 45.402 45.402"
                                                 xml:space="preserve">
                                                <g>
                                                    <path d="M41.267,18.557H26.832V4.134C26.832,1.851,24.99,0,22.707,0c-2.283,0-4.124,1.851-4.124,4.135v14.432H4.141
                                                        c-2.283,0-4.139,1.851-4.138,4.135c-0.001,1.141,0.46,2.187,1.207,2.934c0.748,0.749,1.78,1.222,2.92,1.222h14.453V41.27
                                                        c0,1.142,0.453,2.176,1.201,2.922c0.748,0.748,1.777,1.211,2.919,1.211c2.282,0,4.129-1.851,4.129-4.133V26.857h14.435
                                                        c2.283,0,4.134-1.867,4.133-4.15C45.399,20.425,43.548,18.557,41.267,18.557z"/>
                                                </g>
                                                </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <a href="{{ route('admin.hotels.rules.index') }}" class="btn btn-secondary mr-3">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
