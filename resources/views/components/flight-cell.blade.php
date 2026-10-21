<section>
    @php
        $prices = [];
        $businessPrices = [];

        // Get the needed seats from request or from the Livewire component
        $needed_seats = request()->query('adults', 0) + request()->query('children', 0);

        // Calculate economy seat availability
        $hasEconomySeats = false;

        if(isset($flight['economy_seats']) && $flight['economy_seats'] >= $needed_seats) {
            $prices = getPrice($flight, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
        }
        if(!empty($prices)) {
            $hasEconomySeats = true;
        }

        // Calculate business seat availability
        $hasBusinessSeats = false;
        if(config('features.cabins') && isset($flight['business_seats']) && $flight['business_seats'] >= $needed_seats) {
            $businessPrices = getBusinessPrice($flight, null, ['adult' => request()->query('adults'), 'infant' => request()->query('infants'), 'child' => request()->query('children')]);
        }

        if(!empty($businessPrices)) {
            $hasBusinessSeats = true;
        }

        $requestType = $requestType ?? null;
    @endphp
    <div class="search-results-cell px-3 py-3 waves-effect mb-3">
        <form>
            <div class="row g-0">
                <x-flight-cell-details />
                <div class="col-md-{{ config('features.cabins') ? '4' : '2' }} d-flex {{ $flight['type'] == 'fixed' ? 'flex-column justify-content-center' : 'flex-column' }} ">
                    <div class="mb-2">
                        <div class="row" wire:ignore>
                            @if(config('features.cabins'))
                                <div class="col">
                                    <div class="font-weight-bold text-uppercase text-primary mb-2">Business</div>
                                    @if(!empty($businessPrices))
                                        @php $supplementsQty = 0 @endphp
                                        @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                            @foreach($businessPrices['adult'] as $adult)
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                    <div class="text-center font-weight-bold text-dark">${{ $adult['price'] }}</div>
                                                </div>
                                                @php $supplementsQty += $adult['number'] @endphp
                                            @endforeach
                                        @endif
                                        @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                            @foreach($businessPrices['child'] as $child)
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                    <div class="text-center font-weight-bold text-dark">${{ $child['price'] }}</div>
                                                </div>
                                                @php $supplementsQty += $child['number'] @endphp
                                            @endforeach
                                        @endif
                                        @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                            @foreach($businessPrices['infant'] as $infant)
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                    <div class="text-center font-weight-bold text-dark">${{ $infant['price'] }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if($requestType == 'oneway')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $flight['oneway_supplements'] }}</div>
                                            </div>
                                        @endif
                                        @if($requestType == 'multi')
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $flight['multi_supplements'] }}</div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="small text-gray">Not Available</div>
                                    @endif
                                </div>
                            @endif
                            <div class="col">
                                <div class="font-weight-bold text-uppercase text-primary mb-2">Economy</div>
                                @if(!empty($prices))
                                    @php $supplementsQty = 0 @endphp
                                    @if(count(request()->query()) && isset(request()->query()['adults']) && request()->query()['adults'] > 0)
                                        @foreach($prices['adult'] as $adult)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">ADULT x{{ $adult['number'] }} ({{ $adult['class'] }})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $adult['price'] }}</div>
                                            </div>
                                            @php $supplementsQty += $adult['number'] @endphp
                                        @endforeach
                                    @endif
                                    @if(count(request()->query()) && isset(request()->query()['children']) && request()->query()['children'] > 0)
                                        @foreach($prices['child'] as $child)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">CHILD x{{ $child['number'] }} ({{$child['class']}})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $child['price'] }}</div>
                                            </div>
                                            @php $supplementsQty += $child['number'] @endphp
                                        @endforeach
                                    @endif
                                    @if(count(request()->query()) && isset(request()->query()['infants']) && request()->query()['infants'] > 0)
                                        @foreach($prices['infant'] as $infant)
                                            <div class="d-flex justify-content-between">
                                                <div class="text-dark small">INFANT x{{ $infant['number'] }} ({{ $infant['class'] }})</div>
                                                <div class="text-center font-weight-bold text-dark">${{ $infant['price'] }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if($requestType == 'oneway')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-dark small">OW SUPP. x{{ $supplementsQty }}</div>
                                            <div class="text-center font-weight-bold text-dark">${{ $flight['oneway_supplements'] }}</div>
                                        </div>
                                    @endif
                                    @if($requestType == 'multi')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-dark small">MULTI SUPP. x{{ $supplementsQty }}</div>
                                            <div class="text-center font-weight-bold text-dark">${{ $flight['multi_supplements'] }}</div>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-gray small">Not Available</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            @if(config('features.cabins'))
                                <div class="col">
                                    @if($hasBusinessSeats)
                                        <div class="{{ $flight['type'] != 'fixed' ? 'mt-auto' : '' }}">
                                            @if($section == 'departure')
                                                <button wire:click.prevent="setDeparture({{ $id }}, 'business')" wire:target="setDeparture" wire:loading.attr="disabled" class="mt-2 btn {{ $selectedDeparture == $id ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles" {{ $selectedDeparture == $id ? 'disabled' : '' }} style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                    <div class="d-none" wire:target="setDeparture" wire:loading.class="d-block">
                                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                            <span class="visually-hidden"></span>
                                                        </div>
                                                    </div>
                                                    <div wire:target="setDeparture" wire:loading.class="d-none">
                                                        @if($selectedDeparture == $id && $selectedDepartureCabin == 'business')
                                                            Selected
                                                        @else
                                                            @if($flight['onhold_bookings'] == 1)
                                                                Request
                                                            @else
                                                                Select
                                                            @endif
                                                        @endif
                                                    </div>
                                                </button>
                                            @elseif($section == 'return')
                                                <button wire:click.prevent="setReturn({{ $id }}, 'business')" {{ !is_numeric($selectedDeparture) ? 'disabled' : '' }} wire:target="setReturn" wire:loading.attr="disabled" class="mt-2 btn {{ $selectedReturn == $id ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles" style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                    <div class="d-none" wire:target="setReturn" wire:loading.class="d-block">
                                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                            <span class="visually-hidden"></span>
                                                        </div>
                                                    </div>
                                                    <div wire:target="setReturn" wire:loading.class="d-none">
                                                        @if($selectedReturn == $id && $selectedReturnCabin == 'business')
                                                            Selected
                                                        @else
                                                            @if($flight['onhold_bookings'] == 1)
                                                                Request
                                                            @else
                                                                Select
                                                            @endif
                                                        @endif
                                                    </div>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div class="col">
                                @if($hasEconomySeats)
                                    <div class="{{ $flight['type'] != 'fixed' ? 'mt-auto' : '' }}">
                                        @if($section == 'departure')
                                            <button wire:click.prevent="setDeparture({{ $id }}, 'economy')" wire:target="setDeparture" wire:loading.attr="disabled" class="mt-2 btn {{ $selectedDeparture == $id ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles" {{ $selectedDeparture == $id ? 'disabled' : '' }} style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                <div class="d-none" wire:target="setDeparture" wire:loading.class="d-block">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                        <span class="visually-hidden"></span>
                                                    </div>
                                                </div>
                                                <div wire:target="setDeparture" wire:loading.class="d-none">
                                                    @if($selectedDeparture == $id && $selectedDepartureCabin == 'economy')
                                                        Selected
                                                    @else
                                                        @if($flight['onhold_bookings'] == 1)
                                                            Request
                                                        @else
                                                            Select
                                                        @endif
                                                    @endif
                                                </div>
                                            </button>
                                        @elseif($section == 'return')
                                            <button wire:click.prevent="setReturn({{ $id }}, 'economy')" {{ !is_numeric($selectedDeparture) ? 'disabled' : '' }} wire:target="setReturn" wire:loading.attr="disabled" class="mt-2 btn {{ $selectedReturn == $id ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-uppercase disable-styles" style="font-weight: bold; text-transform: uppercase; font-size: 13px; border-radius: 3px;">
                                                <div class="d-none" wire:target="setReturn" wire:loading.class="d-block">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                        <span class="visually-hidden"></span>
                                                    </div>
                                                </div>
                                                <div wire:target="setReturn" wire:loading.class="d-none">
                                                    @if($selectedReturn == $id && $selectedReturnCabin == 'economy')
                                                        Selected
                                                    @else
                                                        @if($flight['onhold_bookings'] == 1)
                                                            Request
                                                        @else
                                                            Select
                                                        @endif
                                                    @endif
                                                </div>
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
