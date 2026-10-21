<section
    class="hero-wrapper main_search p-0"
    x-data="{
        init() {
            $refs.searchBar.classList.remove('d-none')
        },
        selectedId: '{{ request()->tab ?? "tab-charter"}}',
        select(id) {
            this.selectedId = id;
            selectedTab = id;
        },
        isSelected(id) {
            return this.selectedId === id;
        }
    }"
>
    <div x-ref="searchBar" class="hero-box hero-bg d-flex align-items-top d-none" data-bg="" @if($sub == 1) style="width: 100%; padding-top: 20px; padding-bottom: 20px; background: #cccccc" @else style="background-attachment: fixed; background-position: bottom; background-image: url('{{ $site_settings->search_bg->first() ? $site_settings->search_bg->first()['url'] : '' }}');" @endif>
        <div class="container g-0-xs p-4 p-md-0" style="z-index: 12">
            <div class="row g-0-xs">
                <div class="col-lg-12 g-0-xs mx-auto responsive--column-l">
                    <div class="hero-content pb-4 d-none d-sm-block d-lg-block d-xl-block">
                        <div class="section-heading">
                            <h2 class="sec__titles cd-headline zoom d-none d-sm-block" style="color:#fff">
                                <span class="cd-words-wrapper" style="width: 252.5px;">
                                    @if(config('features.charter'))
                                        @if($site_settings->roundtrip == 1)
                                            <b class="is-hidden" style="text-transform: capitalize;">roundtrips</b>
                                        @endif
                                        @if($site_settings->multiple == 1)
                                            <b class="is-hidden" style="text-transform: capitalize;">multiple destinations</b>
                                        @endif
                                        @if($site_settings->fixed == 1)
                                            <b class="is-hidden" style="text-transform: capitalize;">fixed departures</b>
                                        @endif
                                        @if($site_settings->oneway == 1)
                                            <b class="is-visible" style="text-transform: capitalize;">oneway</b>
                                        @endif
                                    @endif
                                    @if(auth()->user()->agency_id == 1 && config('features.lowcost'))
                                       @if($site_settings->lowcost == 1)
                                            <b class="is-hidden" style="text-transform: capitalize;">low cost</b>
                                       @endif
                                    @endif
                                    @if(auth()->user()->agency_id == 1 && config('features.hotels'))
                                        @if($site_settings->hotels == 1)
                                            <b class="is-hidden" style="text-transform: capitalize;">hotels</b>
                                        @endif
                                    @endif
                                    @if(auth()->user()->agency_id == 1 && config('features.packages'))
                                        @if($site_settings->packages == 1)
                                            <b class="is-hidden" style="text-transform: capitalize;">packages</b>
                                        @endif
                                    @endif
                                </span>
                            </h2>
                        </div>
                    </div>

                    <div class="section-tab fade-in" style="color: #fff;">
                        <ul role="tablist" class="nav nav-tabs mb-0 gap-1">
                            @if(config('features.charter') && ($site_settings->roundtrip == 1 || $site_settings->oneway == 1 || $site_settings->multiple == 1) )
                            <li class="nav-item">
                                <button role="tab" id="tab-charter" :aria-selected="isSelected($el.id)" @click="select($el.id)" :class="isSelected($el.id) ? 'nav-link active' : 'nav-link'" type="button">
                                    <span class="d-xl-none d-lg-none"><i class="la la-exchange-alt mx-1"></i></span>
                                    <span class="d-none d-lg-block d-xl-block"><i class="la la-exchange-alt mx-1"></i>
                                        <span class="text">Charter Flights</span>
                                    </span>
                                </button>
                            </li>
                            @endif
                            @if(config('features.theta'))
                                <li class="nav-item">
                                        <button role="tab" id="tab-aggregator" :aria-selected="isSelected($el.id)" @click="select($el.id)" :class="isSelected($el.id) ? 'nav-link active' : 'nav-link'" type="button">
                                            <span class="d-xl-none d-lg-none"><i class="la la-exchange-alt mx-1"></i></span>
                                            <span class="d-none d-lg-block d-xl-block"><i class="la la-exchange-alt mx-1"></i>
                                                <span class="text">Flights</span>
                                            </span>
                                        </button>
                                    </li>
                            @endif
                            @if(config('features.charter') && $site_settings->fixed == 1)
                            <li class="nav-item">
                                <button role="tab" id="tab-fixed" :aria-selected="isSelected($el.id)" @click="select($el.id)" type="button" :class="isSelected($el.id) ? 'nav-link active' : 'nav-link'" >
                                    <span class="d-xl-none d-lg-none"><i class="la la-arrows-alt mx-1"></i></span>
                                    <span class="d-none d-lg-block d-xl-block"><i class="la la-arrows-alt mx-1"></i>
                                        <span class="text">Fixed Departure</span>
                                    </span>
                                </button>
                            </li>
                            @endif
                            @if(config('features.lowcost') && $site_settings->lowcost == 1)
                            <li class="nav-item">
                                <button role="tab" id="tab-low-cost" :aria-selected="isSelected($el.id)" @click="select($el.id)" type="button" :class="isSelected($el.id) ? 'nav-link active' : 'nav-link'" >
                                    <span class="d-xl-none d-lg-none"><i class="la la-money mx-1"></i></span>
                                    <span class="d-none d-lg-block d-xl-block"><i class="la la-money mx-1"></i>
                                        <span class="text">Low Cost</span>
                                    </span>
                                </button>
                            </li>
                            @endif
                            @if(auth()->user()->agency_id == 1 && config('features.hotels') && $site_settings->hotels == 1)
                            <li class="nav-item">
                                <button role="tab" id="tab-hotels" :aria-selected="isSelected($el.id)" @click="select($el.id)" type="button" :class="isSelected($el.id) ? 'nav-link active' : 'nav-link'" >
                                    <span class="d-xl-none d-lg-none"><i class="la la-hotel mx-1"></i></span>
                                    <span class="d-none d-lg-block d-xl-block"><i class="la la-hotel mx-1"></i>
                                        <span class="text">Hotels</span>
                                    </span>
                                </button>
                            </li>
                            @endif
                            @if(auth()->user()->agency_id == 1 && config('features.packages') && $site_settings->packages == 1)
                            <li class="nav-item">
                                <button role="tab" id="tab-packages" :aria-selected="isSelected($el.id)" @click="select($el.id)" type="button" :class="isSelected($el.id) ? 'nav-link active' : 'nav-link'" >
                                    <span class="d-xl-none d-lg-none"><i class="la la-map mx-1"></i></span>
                                    <span class="d-none d-lg-block d-xl-block"><i class="la la-map mx-1"></i>
                                        <span class="text">Packages</span>
                                    </span>
                                </button>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="tab-panel bg-white" role="tabpanels">
                        @if(config('features.theta'))
                            <section x-data="{aggregateSelected: '{{ request()->subtab ?? "roundtrip"}}'}" :aria-labeldby="'tab-aggregator'" role="tabpanel" x-show="isSelected('tab-aggregator')" class="bg-white p-4">
                                <div class="my-2">
                                    <div class="form-check form-check-inline mr-4">
                                        <input x-model="aggregateSelected" class="form-check-input" value="roundtrip" type="radio" name="aggregate-low-cost" id="aggregate-roundtrip">
                                        <label class="form-check-label text-dark font-weight-bold" for="aggregate-roundtrip">Roundtrip</label>
                                    </div>
                                    <div class="form-check form-check-inline mr-4">
                                        <input x-model="aggregateSelected" class="form-check-input" value="oneway" type="radio" name="aggregate-low-cost" id="aggregate-oneway">
                                        <label class="form-check-label text-dark font-weight-bold" for="aggregate-oneway">Oneway</label>
                                    </div>
                                    <div x-show="aggregateSelected == 'roundtrip'">
                                        <livewire:frontend.components.header.search.aggregate.roundtrip wire:key="15"/>
                                    </div>
                                    <div x-show="aggregateSelected == 'oneway'">
                                        <livewire:frontend.components.header.search.aggregate.oneway wire:key="16"/>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @if(config('features.charter') && ($site_settings->roundtrip == 1 || $site_settings->oneway == 1 || $site_settings->multiple == 1) )
                            <section x-data="{selected: '{{ request()->subtab ?? "roundtrip"}}'}" :aria-labeldby="'tab-charter'" role="tabpanel" x-show="isSelected('tab-charter')" class="p-4">
                            <div class="my-2">
                                @if($site_settings->roundtrip == 1)
                                <div class="form-check form-check-inline mr-4">
                                    <input x-model="selected" class="form-check-input" value="roundtrip" type="radio" name="inlineRadioOptions" id="inlineRadio1">
                                    <label class="form-check-label text-dark font-size-14 font-weight-bold" for="inlineRadio1">Roundtrip</label>
                                </div>
                                @endif
                                @if($site_settings->oneway == 1)
                                <div class="form-check form-check-inline mr-4">
                                    <input x-model="selected" class="form-check-input" value="oneway" type="radio" name="inlineRadioOptions" id="inlineRadio2">
                                    <label class="form-check-label text-dark font-size-14 font-weight-bold" for="inlineRadio2">Oneway</label>
                                </div>
                                @endif
                                @if($site_settings->multiple == 1)
                                <div class="form-check form-check-inline mr-2">
                                    <input x-model="selected" class="form-check-input" value="multiple" type="radio" name="inlineRadioOptions" id="inlineRadio3">
                                    <label class="form-check-label text-dark font-size-14 font-weight-bold" for="inlineRadio3">Multiple Destinations</label>
                                </div>
                                @endif
                            </div>
                            <div x-show="selected == 'roundtrip'">
                                <livewire:frontend.components.header.search.roundtrip :allLocations="$this->locations" wire:key="1" />
                            </div>
                            <div x-show="selected == 'oneway'">
                                <livewire:frontend.components.header.search.oneway :allLocations="$this->locations" wire:key="2" />
                            </div>
                            <div x-show="selected == 'multiple'">
                                <livewire:frontend.components.header.search.multiple :allLocations="$this->locations" wire:key="3" />
                            </div>
                        </section>
                        @endif
                        @if(config('features.charter') && $site_settings->fixed == 1)
                        <section :aria-labeldby="'tab-tab-fixed'" role="tabpanel" x-show="isSelected('tab-fixed')" class="bg-white p-4">
                            <livewire:frontend.components.header.search.fixed wire:key="4" />
                        </section>
                        @endif
                        @if(config('features.lowcost') && $site_settings->lowcost == 1)
                            <section x-data="{lowcostSelected: '{{ request()->subtab ?? "roundtrip"}}'}" :aria-labeldby="'tab-low-cost'" role="tabpanel" x-show="isSelected('tab-low-cost')" class="bg-white p-4">
                            <div class="my-2">
                                <div class="form-check form-check-inline mr-4">
                                    <input x-model="lowcostSelected" class="form-check-input" value="roundtrip" type="radio" name="low-cost" id="low-cost-roundtrip">
                                    <label class="form-check-label text-dark font-weight-bold" for="low-cost-roundtrip">Roundtrip</label>
                                </div>
                                <div class="form-check form-check-inline mr-4">
                                    <input x-model="lowcostSelected" class="form-check-input" value="oneway" type="radio" name="low-cost" id="low-cost-oneway">
                                    <label class="form-check-label text-dark font-weight-bold" for="low-cost-oneway">Oneway</label>
                                </div>
                                <div x-show="lowcostSelected == 'roundtrip'">
                                    <livewire:frontend.components.header.search.lowcost-roundtrip wire:key="5"/>
                                </div>
                                <div x-show="lowcostSelected == 'oneway'">
                                    <livewire:frontend.components.header.search.lowcost-oneway wire:key="6"/>
                                </div>
                            </div>
                        </section>
                        @endif
                        @if(auth()->user()->agency_id == 1 && config('features.hotels') && $site_settings->hotels == 1)
                        <section :aria-labeldby="'tab-hotels'" role="tabpanel" x-show="isSelected('tab-hotels')" class="bg-white p-4">
                            <livewire:frontend.components.header.search.hotels wire:key="7" />
                        </section>
                        @endif
                        @if(auth()->user()->agency_id == 1 && config('features.packages') && $site_settings->packages == 1)
                        <section :aria-labeldby="'tab-packages'" role="tabpanel" x-show="isSelected('tab-packages')" class="bg-white p-4">
                            <livewire:frontend.components.header.search.packages :allLocations="$this->locations" wire:key="8"/>
                        </section>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php $updates = \App\Models\Update::query()->where('agency_id', 1)->get() ; @endphp
    @if(count($updates) > 0)
        <div class="marquee bg-primary py-2">
            <p>
                @foreach($updates as $update)
                    {{ $update->text }}
                @endforeach
            </p>
        </div>
        <style>
            .marquee {
                width: 100%;
                white-space: nowrap;
                overflow: hidden;
                box-sizing: border-box;
            }
            .marquee p {
                display: inline-block;
                color: white;
                padding-left: 100%;
                animation: marquee 60s linear infinite;
            }
            @keyframes marquee {
                0% {
                    transform: translate(0, 0);
                }
                100% {
                    transform: translate(-100%, 0);
                }
            }
        </style>
    @endif
</section>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <style>
        body {
            overflow-x: hidden;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #eeeeee;
        }
        .select2-container {
            width: 100% !important;
        }
        .select2-dropdown {
            border: 1px solid #eeeeee;
        }

        .select2-results__options {
            font-size: 13px;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            border-radius: 3px !important;
            height: 55px;
            line-height: inherit;
            padding: 12px 20px 10px 40px;
            color: #0d233e;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            -webkit-transition: all .3s;
            -moz-transition: all .3s;
            -ms-transition: all .3s;
            -o-transition: all .3s;
            transition: all .3s;
        }
    </style>
@endpushonce
