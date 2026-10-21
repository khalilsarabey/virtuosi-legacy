<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Agencies
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>

                    <div class="d-flex">
                        <div>
                            <button wire:click="unassign" class="btn btn-xs px-4 py-2 btn-danger uppercase waves-effect">
                                Unassign
                            </button>
                        </div>
                        <div class="mr-2">
                            <button wire:click="assign" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                                Assign
                            </button>
                        </div>
                        <select class="form-control" wire:model="class">
                            <option value="">Select</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index p-0">
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Class
                            </th>
                            <th>Custom Pricing</th>
                            <th>Notes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($agencies as $agency)
                            <tr>
                                <td>
                                    <label>
                                    <input type="checkbox" class="form-check-inline" wire:model.defer="selected" value="{{ $agency->id }}" />
                                    {{ $agency->name }}
                                    </label>
                                </td>
                                <td>
                                    {{ $agency->pricing_class?->name ?? '-' }}
                                </td>
                                <td>
                                    @php $params = [] @endphp
                                    @foreach($agency->custom_pricings as $custom_pricing)
                                        @if($custom_pricing->airline)
                                            @php $params[] = '<a href="' . route('admin.settings.auto-pricing.pricing.index', ['selectedAirline' => $custom_pricing->airline->id, 'selectedAgency' => $agency->id]) . '">' . $custom_pricing->airline->name . '</a>' @endphp
                                        @endif
                                    @endforeach
                                    {!! implode(', ', $params) !!}
                                </td>
                                <td>
                                    <a href="{{ route('admin.settings.auto-pricing.agencies.edit', $agency) }}" class="btn btn-sm btn-outline-primary">Notes</a>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
