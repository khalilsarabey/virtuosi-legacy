<div class="main_search contact-form-action" wire:init="populate">
    <form action="{{ route('frontend.search') }}#search-results">
        <input type="hidden" name="type" value="fixed"/>
        <input type="hidden" name="tab" value="tab-fixed" />
        <div class="row align-items-center">
            <div class="col-12 col-lg-3">
                <div class="input-wrapper" wire:ignore>
                    <span class="label-text">From</span>
                    <div class="form-group">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select name="dep_from_code" wire:model="dep_from_code" class="form-control" required>
                                <option value disabled selected>Select</option>
                                @foreach($fromList as $value => $key)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="input-wrapper">
                    <span class="label-text">To</span>
                    <div class="form-group">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select name="dep_to_code" wire:model="dep_to_code" class="form-control" {{ count($toList) ? '' : 'disabled' }} required>
                                <option value disabled selected>Select</option>
                                @foreach($toList as $value => $key)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="input-box" wire:ignore>
                    <label class="label-text">Departure</label>
                    <div class="form-group">
                        <span class="la la-calendar form-icon"></span>
                        <x-flatpickr id="charter-fixed-date-pick-departure" value="{{ $dep_date }}" name="dep_date" disabled mode="single" min="today" required picker="date" wire:model="dep_date" wire:loading.attr="disabled" required />
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-2">
                <div class="input-box" wire:ignore>
                    <div class="label-text">Passengers</div>
                    <div class="form-group">
                        <span class="la la-male form-icon"></span>
                        <div x-data class="dropdown dropdown-contain">
                            <a class="dropdown-toggle dropdown-btn d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                <p> <span class="roundTripNumberOfPassengers pl-4" x-text="parseInt($wire.infants)+parseInt($wire.adults)+parseInt($wire.children)">{{ $infants + $children + $adults }}</span></p>
                            </a>
                            <div class="dropdown-menu dropdown-menu-wrap" style="border: 1px solid var(--theme)">
                                <div class="dropdown-item mb-3 mt-3">
                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                        <label><i class="la la-user"></i> Adult</label>
                                        <div class="roundtrip-button-inc qtyBtn d-flex align-items-center">
                                            <div wire:click="dec('adults')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                            <input type="text" name="adults" wire:model="adults" >
                                            <div wire:click="inc('adults')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-item mb-3">
                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                        <label><i class="la la-female"></i> Child</label>
                                        <div class="children roundtrip-button-inc qtyBtn d-flex align-items-center">
                                            <div  wire:click="dec('children')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                            <input type="text" name="children" wire:model="children">
                                            <div wire:click="inc('children')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-item mb-3">
                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                        <label><i class="la la-female"></i> Infant</label>
                                        <div class="infants roundtrip-button-inc qtyBtn d-flex align-items-center">
                                            <div wire:click="dec('infants')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                            <input type="text" name="infants" wire:model="infants">
                                            <div wire:click="inc('infants')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end dropdown -->
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-1">
                <div class="btn-search text-center">
                    <button style="color:#fff" type="submit" id="submit" class="effect btn btn-primary btn-block btn-lg disable-styles" data-style="zoom-in">
                        <svg style="fill:currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="c8LPF-icon" role="img" height="24" cleanup=""><path d="M174.973 150.594l-29.406-29.406c5.794-9.945 9.171-21.482 9.171-33.819C154.737 50.164 124.573 20 87.368 20S20 50.164 20 87.368s30.164 67.368 67.368 67.368c12.345 0 23.874-3.377 33.827-9.171l29.406 29.406c6.703 6.703 17.667 6.703 24.371 0c6.704-6.702 6.704-17.674.001-24.377zM36.842 87.36c0-27.857 22.669-50.526 50.526-50.526s50.526 22.669 50.526 50.526s-22.669 50.526-50.526 50.526s-50.526-22.669-50.526-50.526z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@pushOnce('scripts')
    <script>
        $(document).ready(function() {

            @if($query['type'] ?? '' == 'fixed')
                if (window.flatpickrInstances && window.flatpickrInstances['charter-fixed-date-pick-departure']) {
                    let enabledDays = @js($departureList);
                    window.flatpickrInstances['charter-fixed-date-pick-departure'].set('enable', enabledDays);
                    window.flatpickrInstances['charter-fixed-date-pick-departure'].set('minDate', enabledDays[0]);
                    window.flatpickrInstances['charter-fixed-date-pick-departure'].setDate(@js($dep_date));
                }
            @endif

            window.addEventListener('set-fixed-departure-date', event => {
                document.querySelector('#charter-fixed-date-pick-departure').removeAttribute('disabled');
                if (window.flatpickrInstances && window.flatpickrInstances['charter-fixed-date-pick-departure']) {
                    let enabledDays = event.detail.dates;
                    window.flatpickrInstances['charter-fixed-date-pick-departure'].set('enable', enabledDays);
                    window.flatpickrInstances['charter-fixed-date-pick-departure'].set('minDate', enabledDays[0]);
                    @if(!count($query))
                        window.flatpickrInstances['charter-fixed-date-pick-departure'].open();
                    @endif
                }
            })
        });
    </script>
@endPushOnce
