<div class="hero-box hero-bg d-flex align-items-top" data-bg="" style="min-height:550px; background-attachment: fixed; background-position: bottom; background-image: url('{{ $site_settings->search_bg->first() ? $site_settings->search_bg->first()['url'] : '' }}'); margin-bottom: -20px;">
    <div class="container g-0-xs p-4 p-md-0" style="z-index: 12">
        <div class="row g-0-xs">
            <div class="col-lg-12 g-0-xs mx-auto responsive--column-l">
                <div class="hero-content pb-4 d-none d-sm-block d-lg-block d-xl-block">
                    <div class="section-heading">
                        <h2 class="sec__titles cd-headline zoom d-none d-sm-block" style="color:#fff">
                                <span class="cd-words-wrapper" style="width: 252.5px;">
                                    @if($site_settings->oneway == 1)
                                        <b class="is-visible" style="text-transform: capitalize;">oneway</b>
                                    @endif
                                    @if($site_settings->roundtrip == 1)
                                        <b class="is-hidden" style="text-transform: capitalize;">roundtrip</b>
                                    @endif
                                </span>
                        </h2>
                    </div>
                </div>
                <div class="section-tab fade-in" style="min-height:167px;color: #fff;">
                    <ul class="nav nav-tabs mb-0 gap-1" id="Tab" role="tablist">
                        @if($site_settings->oneway == 1)
                            <li data-position="2" class="nav-item" role="presentation">
                                <button class="nav-link text-start text-capitalize {{ $type == 'oneway' ? 'active' : '' }}" id="oneway-tab" data-bs-toggle="tab" data-bs-target="#oneway" type="button" role="tab" aria-controls="oneway" aria-selected="false">
                                    <span class="d-xl-none d-lg-none"><i class="la la-plane mx-1"></i></span>
                                    <span class="d-none d-lg-block d-xl-block"><i class="la la-plane mx-1"></i>
                                            <span class="text">Oneway</span>
                                        </span>
                                </button>
                            </li>
                        @endif
                        @if($site_settings->roundtrip == 1)
                            <li data-position="1" class="nav-item" role="presentation">
                                <button class="nav-link text-start text-capitalize {{ $type == 'roundtrip' ? 'active' : '' }}" id="roundtrip-tab" data-bs-toggle="tab" data-bs-target="#roundtrip" type="button" role="tab" aria-controls="roundtrip"  {{ $type == 'roundtrip' ? 'aria-selected="true"' : '' }}>
                                    <span class="d-xl-none d-lg-none"><i class="la la-exchange-alt mx-1"></i></span>
                                    <span class="d-none d-lg-block d-xl-block"><i class="la la-exchange-alt mx-1"></i>
                                            <span class="text">Roundtrip</span>
                                        </span>
                                </button>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content search-fields-container search_area" id="TabContent">
                        @if($site_settings->oneway == 1)
                            <div class="tab-pane fade {{ $type == 'oneway' ? 'active show' : '' }}" id="oneway" role="oneway" aria-labelledby="oneway-tab">
                                <div class="main_search contact-form-action">
                                    <form action="{{ route('frontend.low-cost,search') }}#search-results">
                                        <div class="row align-items-center">
                                            <div class="col-12 col-lg-3">
                                                <div class="input-wrapper" wire:ignore>
                                                    <span class="label-text">From</span>
                                                    <div class="form-group">
                                                        <span class="la la-map-marker form-icon"></span>
                                                        <div class="input-items pl-5 pl-md-0">
                                                            <select name="from" wire:model="oneway.from" class="form-control" required>
                                                                <option value disabled selected>Select</option>
                                                                @foreach($airports as $airport)
                                                                    <option value="{{ $airport->airport_code }}">{{ $airport->city }} - {{ $airport->airport }}</option>
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
                                                            <select name="to" wire:model="oneway.to" class="form-control" required>
                                                                <option value disabled selected>Select</option>
                                                                @foreach($airports as $airport)
                                                                    <option value="{{ $airport->airport_code }}">{{ $airport->airport_code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <div class="input-box">
                                                    <label class="label-text">Departure</label>
                                                </div>
                                                <div class="form-group" wire:ignore>
                                                    <span class="la la-calendar form-icon"></span>
                                                    <input readonly="readonly" class="oneway-date-pick-departure form-control bg-white" name="departure" type="text" wire:model="oneway.departure" wire:loading.attr="disabled" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <div class="input-box"  wire:ignore>
                                                    <label class="label-text">Passengers</label>
                                                    <div class="form-group">
                                                        <span class="la la-male form-icon"></span>
                                                        <div x-data class="dropdown dropdown-contain">
                                                            <a class="dropdown-toggle dropdown-btn d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                                                <p> <span class="oneWayNumberOfPassengers pl-4" x-text="parseInt($wire.oneway.infants)+parseInt($wire.oneway.adults)+parseInt($wire.oneway.children)"></span></p>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-wrap" style="border: 1px solid var(--theme)">
                                                                <div class="dropdown-item mb-3 mt-3">
                                                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                                                        <label><i class="la la-user"></i> Adult</label>
                                                                        <div class="roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                                            <div wire:click="dec('oneway','adults')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                                            <input type="text" name="adult" wire:model="oneway.adults" >
                                                                            <div wire:click="inc('oneway','adults')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item mb-3">
                                                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                                                        <label><i class="la la-female"></i> Child</label>
                                                                        <div class="children roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                                            <div  wire:click="dec('oneway','children')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                                            <input type="text" name="child" wire:model="oneway.children">
                                                                            <div wire:click="inc('oneway','children')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item mb-3">
                                                                    <div class="qty-box d-flex align-items-center justify-content-between">
                                                                        <label><i class="la la-female"></i> Infant</label>
                                                                        <div class="infants roundtrip-button-inc qtyBtn d-flex align-items-center">
                                                                            <div wire:click="dec('oneway','infants')" class="qty qtyDec"><i class="la la-minus"></i></div>
                                                                            <input type="text" name="infant" wire:model="oneway.infants">
                                                                            <div wire:click="inc('oneway','infants')" class="qty qtyInc"><i class="la la-plus"></i></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
                            </div>
                        @endif
                        @if($site_settings->roundtrip == 1)
                            <div class="tab-pane fade {{ $type == 'roundtrip' ? 'active show' : '' }}" id="roundtrip" role="roundtrip" aria-labelledby="roundtrip-tab">

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@pushOnce('scripts')
    <script>
        $(document).ready(function() {
            window.addEventListener('set-oneway-departure-date', event => {
                if($('.oneway-date-pick-departure').data("datepicker") != null) { $('.oneway-date-pick-departure').datepicker('destroy'); }
                $('.oneway-date-pick-departure').datepicker({
                    dateFormat: 'dd-mm-yy',
                    minDate: new Date(),
                    onSelect: function(dateString) {
                        @this.set('oneway.departure', dateString)
                    },
                    // showButtonPanel: true,
                    // closeText: 'Clear',
                    // onClose: function () {
                    //     $('.oneway-date-pick-departure').datepicker('setDate', null);
                    // }
                });
                @if(!count($query))
                $('.oneway-date-pick-departure').datepicker('show');
                @endif
            })
        });
    </script>
@endPushOnce
