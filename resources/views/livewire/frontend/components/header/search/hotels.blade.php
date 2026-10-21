<div class="main_search contact-form-action">
    <form action="{{ route('frontend.hotels.search') }}#search-results">
        @csrf
        <input type="hidden" name="type" value="hotel"/>
        <div class="row align-items-center">
            <div class="col-12 col-lg">
                <div class="input-wrapper" wire:ignore>
                    <span class="label-text">Destination</span>
                    <div class="form-group">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select name="from" wire:model.live="destination" class="form-control" required>
                                <option value selected>Select</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->location }}">{{ $destination->city?->city }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="input-wrapper">
                    <span class="label-text">Hotel</span>
                    <div class="form-group">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select name="hotel" wire:model.live="hotel" class="form-control">
                                <option value selected>Select</option>
                                @foreach($hotels as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="label-text">Date</div>
                <div class="form-group">
                    <x-flatpickr id="hotel-date-pick" wire:model.live="date" name="date" mode="range" min="today" months="2" required picker="date" disabled />
                    <span class="la la-calendar form-icon"></span>
                </div>
            </div>
            <div class="col-12 col-lg">
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
                                                <input type="text" name="rooms[{{$k}}][adult]" wire:model.live="rooms.{{ $k }}.adult" >
                                                <div wire:click="inc('adult', {{ $k }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-item">
                                        <div class="qty-box d-flex align-items-center justify-content-between">
                                            <label><i class="la la-female"></i> Child</label>
                                            <div class="children roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                <div  wire:click="dec('child', {{ $k }})" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                <input type="text" name="rooms[{{$k}}][child]" wire:model.live="rooms.{{ $k }}.child">
                                                <div wire:click="inc('child', {{ $k }})" class="qty qtyInc"><i class="la la-plus"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-item">
                                        <div class="qty-box d-flex align-items-center justify-content-between">
                                            <label><i class="la la-female"></i> Infant</label>
                                            <div class="infants roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                <div wire:click="dec('infant', {{ $k }})" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                <input type="text" name="rooms[{{$k}}][infant]" wire:model.live="rooms.{{ $k }}.infant">
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
            <div class="col-12 col-lg-1">
                <input type="hidden" name="adults" value="{{ $adults }}" />
                <input type="hidden" name="infants" value="{{ $infants }}" />
                <input type="hidden" name="children" value="{{ $children }}" />
                <input type="hidden" name="tab" value="tab-hotels" />
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

            @if(count($query))
                document.querySelector('#hotel-date-pick').removeAttribute('disabled');
            @endif

            function showDays() {
                let start = $('#hotel-date-pick').datepicker('getDate');
                let end = $('.package-date-pick-return').datepicker('getDate');
                if (!start || !end) return;
                let days = (end - start) / 1000 / 60 / 60 / 24;
                $('#duration').text(++days + ' Days').removeClass('d-none');
            }

            window.addEventListener('set-hotel-date', event => {
                document.querySelector('#hotel-date-pick').removeAttribute('disabled');
                if (window.flatpickrInstances && window.flatpickrInstances['hotel-date-pick']) {
                    @if(!count($query))
                    window.flatpickrInstances['hotel-date-pick'].open();
                    @endif
                }
            })

        });
    </script>
@endPushOnce
