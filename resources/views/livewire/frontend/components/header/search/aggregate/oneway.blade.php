<div class="main_search contact-form-action" >
    <form method="post" action="{{ route('frontend.low-cost.search') }}#search-results">
        @csrf
        <input type="hidden" name="type" value="oneway" />
        <input type="hidden" name="tab" value="tab-lowcost" />
        <input type="hidden" name="subtab" value="low-cost-oneway" />
        <div class="row align-items-center">
            <div class="col-12 col-lg-3">
                <div class="input-wrapper" wire:ignore>
                    <span class="label-text">From</span>
                    <div class="form-group mt-1" style="border: 1px solid #eee; border-radius: 3px !important">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select data-model="from" name="from" class="origin flight_location form-control" required></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="input-wrapper" wire:ignore>
                    <span class="label-text">To</span>
                    <div class="form-group mt-1" style="border: 1px solid #eee; border-radius: 3px !important">
                        <span class="la la-map-marker form-icon"></span>
                        <div class="input-items pl-5 pl-md-0">
                            <select data-model="to" name="to" class="origin flight_location form-control" required>

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
                        <input id="oneway_date" wire:ignore class="roundtrip-date-pick-departure form-control bg-white" name="departure" type="date" wire:model="departure" value="{{ $departure }}" wire:loading.attr="disabled" required>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-2">
                <div class="input-box" wire:ignore>
                    <label class="label-text">Passengers</label>
                    <div class="form-group">
                        <span class="la la-male form-icon"></span>
                        <div x-data class="dropdown dropdown-contain">
                            <a class="dropdown-toggle dropdown-btn d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                <p> <span class="roundTripNumberOfPassengers pl-4" x-text="parseInt($wire.infant)+parseInt($wire.adult)+parseInt($wire.child)">{{ $infant + $child + $adult }}</span></p>
                            </a>
                            <div class="dropdown-menu dropdown-menu-wrap" style="border: 1px solid #eee">
                                <div class="dropdown-item mb-3 mt-3">
                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                        <label><i class="la la-user"></i> Adult</label>
                                        <div class="roundtrip-button-inc qtyBtn d-flex align-items-center">
                                            <div wire:click="dec('adult')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                            <input type="text" name="adult" wire:model="adult">
                                            <div wire:click="inc('adult')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-item mb-3">
                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                        <label><i class="la la-female"></i> Child</label>
                                        <div class="children roundtrip-button-inc qtyBtn d-flex align-items-center">
                                            <div wire:click="dec('child')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                            <input type="text" name="child" wire:model="child">
                                            <div wire:click="inc('child')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-item mb-3">
                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                        <label><i class="la la-female"></i> Infant</label>
                                        <div class="infants roundtrip-button-inc qtyBtn d-flex align-items-center">
                                            <div wire:click="dec('infant')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                            <input type="text" name="infant" wire:model="infant">
                                            <div wire:click="inc('infant')" class="qty qtyInc"><i class="la la-plus"></i></div>
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
                <div class="btn-search text-right">
                    <button style="color:#fff" type="submit" id="submit" class="effect btn btn-primary btn-block btn-lg disable-styles" data-style="zoom-in">
                        <svg style="fill:currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="c8LPF-icon" role="img" height="24" cleanup="">
                            <path d="M174.973 150.594l-29.406-29.406c5.794-9.945 9.171-21.482 9.171-33.819C154.737 50.164 124.573 20 87.368 20S20 50.164 20 87.368s30.164 67.368 67.368 67.368c12.345 0 23.874-3.377 33.827-9.171l29.406 29.406c6.703 6.703 17.667 6.703 24.371 0c6.704-6.702 6.704-17.674.001-24.377zM36.842 87.36c0-27.857 22.669-50.526 50.526-50.526s50.526 22.669 50.526 50.526s-22.669 50.526-50.526 50.526s-50.526-22.669-50.526-50.526z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').min = today;
            document.getElementById('oneway_date').min = today;
        });
        var ajax;
        function addSelect2() {
            ajax = $(".flight_location");
            function formatRepo(t) {
                return t.loading ? t.text : (
                    '<div class="d-flex align-items-center border-bottom pb-3 w-100">' +
                    '<div style="font-size: 14px; font-weight: bold;" class="btn btn-outline-primary btn-sm mx-2">' + t.id + '</div>' +
                    '<div>' +
                    '<span style="font-weight: medium; font-size: 14px;">' + t.city + '</span><span class="px-1">(' + t.country + ')</span>' +
                    '</div>' +
                    '</div>'
                );
            }

            function formatRepoSelection(t) {
                if(t.text !== '') {
                    return '<div><small>Select City or Airport Code</small></div>';
                } else {
                    let city = typeof t.city !== 'undefined' ? t.city : "";
                    let country = typeof t.country_code !== 'undefined' ? t.country_code : "";
                    return '<div>' + city + ' ' + country + ' <small>(' + t.id + ') </small></div>';
                }
            }

            $(ajax).select2({
                placeholder: "Select a state",
                theme: 'bootstrap-5',
                ajax: {
                    url: "{{ route('frontend.api.airports.search') }}",
                    dataType: "json",
                    data: function(t) {
                        return {
                            city: $.trim(t.term)
                        }
                    },
                    processResults: function(t) {
                        let e = [];
                        return t.forEach(function(t) {
                            e.push({
                                id: t.airport_code,
                                city: t.city,
                                country: t.country_code,
                                airport: t.airport
                            })
                        }), {
                            results: e
                        }
                    },
                },
                escapeMarkup: function(t) {
                    return t
                },
                minimumInputLength: 3,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                dropdownPosition: 'below',
                cache: !0
            }).on('change', function(e) {
                @this.set($(this).data('model'), e.target.value)
            });
        }
        addSelect2();
        function updateMinDate() {
            document.getElementById('end_date').min = document.getElementById('start_date').value;
        }
    </script>
@endPushOnce
