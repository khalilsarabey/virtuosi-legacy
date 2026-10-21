<div class="main_search contact-form-action">
    <form action="{{ route('frontend.packages.search') }}#search-results">
        <input type="hidden" name="type" value="roundtrip" />
        <input type="hidden" name="tab" value="tab-packages" />
        <input type="hidden" name="is_package" value="1" />
        <div class="row align-items-center">
            <div class="col-12 col-lg-2">
                <div class="input-wrapper" wire:ignore>
                    <span class="label-text">From</span>
                    <div class="form-group">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select name="dep_from_code" wire:model.live="dep_from_code" class="form-control" required>
                                <option value disabled selected>Select</option>
                                @foreach($fromList as $value => $key)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-2">
                <div class="input-wrapper">
                    <span class="label-text">To</span>
                    <div class="form-group">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select name="dep_to_code" wire:model.live="dep_to_code" class="form-control" {{ count($toList) ? '' : 'disabled' }} required>
                                <option value disabled selected>Select</option>
                                @foreach($toList as $value => $key)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="input-box" wire:ignore>
                    <label class="label-text">Departure</label>
                    <div class="form-group">
                        <x-flatpickr id="package-date-pick-departure" name="dep_date" disabled mode="single" min="today" required picker="date" wire:model.live="dep_date" wire:loading.attr="disabled" required />
                        <span class="la la-calendar form-icon"></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="input-box" wire:ignore>
                    <label class="label-text">Return</label>
                    <div class="form-group">
                        <x-flatpickr id="package-date-pick-return" name="ret_date" value="{{ $ret_date }}" disabled mode="single" min="today" required picker="date" wire:model.live="ret_date" wire:loading.attr="disabled" required />
                        <span class="la la-calendar form-icon"></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-2">
                <div class="input-box">
                    <label class="label-text">Rooms & Passengers</label>
                    <div class="form-group">
                        <span class="la la-male form-icon"></span>
                        <div x-data class="dropdown dropdown-contain">
                            <a class="dropdown-toggle dropdown-btn d-flex align-items-center" href="#" role="button" data-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <p> <span class="roundTripNumberOfPassengers pl-4" x-text="parseInt($wire.infants)+parseInt($wire.adults)+parseInt($wire.children)">{{ $infants + $children + $adults }}</span></p>
                            </a>
                            <div wire:ignore.self class="dropdown-menu dropdown-menu-wrap" style="border: 1px solid var(--theme)">
                                @foreach($rooms as $k => $room)
                                    <div class="d-flex justify-content-between mx-3">
                                        <div class="font-size-14 text-uppercase font-weight-bold">Room {{ $k + 1 }}</div>
                                        @if($k > 0)
                                            <a wire:click.prevent="removeRoom({{ $k }})" class="text-danger font-size-12 text-danger">Remove</a>
                                        @endif
                                    </div>
                                    <div class="dropdown-item mb-1 mt-1">
                                        <div class="qty-box d-flex align-items-center justify-content-between">
                                            <label><i class="la la-user"></i> Adult</label>
                                            <div class="roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                <div wire:click="dec('adult', {{ $k }})" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                <input type="text" name="rooms[{{$k}}][adult]" wire:model.live="rooms.{{ $k }}.adult" readonly>
                                                <div wire:click="inc('adult', {{ $k }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-item">
                                        <div class="qty-box d-flex align-items-center justify-content-between">
                                            <label><i class="la la-female"></i> Child</label>
                                            <div class="children roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                <div  wire:click="dec('child', {{ $k }})" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                <input type="text" name="rooms[{{$k}}][child]" wire:model.live="rooms.{{ $k }}.child" readonly>
                                                <div wire:click="inc('child', {{ $k }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-item">
                                        <div class="qty-box d-flex align-items-center justify-content-between">
                                            <label><i class="la la-female"></i> Infant</label>
                                            <div class="infants roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                <div wire:click="dec('infant', {{ $k }})" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                <input type="text" name="rooms[{{$k}}][infant]" wire:model.live="rooms.{{ $k }}.infant" readonly>
                                                <div wire:click="inc('infant', {{ $k }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                @endforeach
                                <div class="mx-3">
                                    <button type="button" wire:click="addRoom" class="btn btn-sm btn-primary w-100">Add Room</button>
                                </div>
                            </div>
                        </div>
                        <!-- end dropdown -->
                    </div>
                </div>
            </div>
            <input type="hidden" name="adults" wire:model.live="adults" />
            <input type="hidden" name="infants" wire:model.live="infants"/>
            <input type="hidden" data-form-name="children" wire:model.live="children" />
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

            function showDays() {
                let start = $('#package-date-pick-departure').datepicker('getDate');
                let end = $('#package-date-pick-return').datepicker('getDate');
                if (!start || !end) return;
                let days = (end - start) / 1000 / 60 / 60 / 24;
                $('#duration').text(++days + ' Days').removeClass('d-none');
            }

            @if(count($query))
                document.querySelector('#package-date-pick-return').removeAttribute('disabled');
                document.querySelector('#package-date-pick-departure').removeAttribute('disabled');
            @endif

            @if($query['type'] ?? '' == 'roundtrip' && $query['tab'] ?? '' == 'tab-packages')
                //document.querySelector('#charter-roundtrip-date-pick-departure').removeAttribute('disabled');
                if (window.flatpickrInstances && window.flatpickrInstances['package-date-pick-departure']) {
                    let enabledDays = @js($departureList);
                    window.flatpickrInstances['package-date-pick-departure'].set('enable', enabledDays);
                    window.flatpickrInstances['package-date-pick-departure'].set('minDate', enabledDays[0]);
                    window.flatpickrInstances['package-date-pick-departure'].setDate(@js($dep_date));
                }
                //document.querySelector('#charter-roundtrip-date-pick-return').removeAttribute('disabled');
                if (window.flatpickrInstances && window.flatpickrInstances['package-date-pick-return']) {
                    let enabledDays = @js($returnList);
                    window.flatpickrInstances['package-date-pick-return'].set('enable', enabledDays);
                    window.flatpickrInstances['package-date-pick-return'].set('minDate', enabledDays[0]);
                    window.flatpickrInstances['package-date-pick-return'].setDate(@js($ret_date));
                }
            @endif

            window.addEventListener('set-package-departure-date', event => {
                document.querySelector('#package-date-pick-departure').removeAttribute('disabled');
                if (window.flatpickrInstances && window.flatpickrInstances['package-date-pick-departure']) {
                    let enabledDays = event.detail.dates;
                    console.log(enabledDays);
                    window.flatpickrInstances['package-date-pick-departure'].set('enable', enabledDays);
                    window.flatpickrInstances['package-date-pick-departure'].set('minDate', enabledDays[0]);
                    @if(!count($query ?? []))
                        window.flatpickrInstances['package-date-pick-departure'].open();
                    @endif
                }
            })

            window.addEventListener('set-package-return-date', event => {
                document.querySelector('#package-date-pick-return').removeAttribute('disabled');
                if (window.flatpickrInstances && window.flatpickrInstances['package-date-pick-return']) {
                    let enabledDays = event.detail.dates;
                    window.flatpickrInstances['package-date-pick-return'].set('enable', enabledDays);
                    window.flatpickrInstances['package-date-pick-return'].set('minDate', enabledDays[0]);
                    @if(!count($query ?? []))
                        window.flatpickrInstances['package-date-pick-return'].open();
                    @endif
                }
            });


        });
    </script>
@endPushOnce
