<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    {{ $class->name }}
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-index w-full">
                            <thead>
                                <tr>
                                    <th>Agency Name</th>
                                    <th>Custom Pricing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class->agencies as $agency)
                                    <tr>
                                        <td>
                                            {{ $agency->name }}
                                        </td>
                                        <td>
                                            @if($agency->custom_pricings->isNotEmpty())
                                                <div class="font-size-12">
                                                    {{ $agency->custom_pricings->pluck('airline.name')->implode(', ') }}
                                                </div>
                                            @else
                                                <small>None</small>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-xs btn-danger mr-2" type="button" wire:click="remove({{ $agency->id }})" wire:loading.attr="disabled">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@push('scripts')
    <script>
        Livewire.on('confirm', e => {
            if (!confirm("{{ trans('global.areYouSure') }}")) {
                return
            }
                @this[e.callback](...e.argv)
        })
    </script>
@endpush
