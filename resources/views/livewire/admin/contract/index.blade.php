<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Contracts
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.contracts.create') }}">
                    Add Contract
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-md-8 d-flex justify-content-start align-items-end">
                    @if($this->selectedCount)
                        <div class="bd-example">
                            <button type="button" class="btn btn-xs btn-danger" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled">Delete Selected</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th class="w-9">
                            </th>
                            <th>
                                {{ trans('cruds.contract.fields.name') }}
                                @include('components.table.sort', ['field' => 'name'])
                            </th>
                            <th>
                                {{ trans('cruds.contract.fields.content') }}
                                @include('components.table.sort', ['field' => 'content'])
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{ $contract->id }}" wire:model.live="selected">
                                </td>
                                <td>
                                    {{ $contract->name }}
                                </td>
                                <td>
                                    {!! $contract->content !!}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                            <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.contracts.edit', $contract) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                            <button class="btn btn-sm btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $contract->id }})" wire:loading.attr="disabled">
                                                {{ trans('global.delete') }}
                                            </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No entries found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="pt-3">
                        @if($this->selectedCount)
                            <p class="text-sm leading-5">
                            <span class="font-medium">
                                {{ $this->selectedCount }}
                            </span>
                                Contract(s) Selected
                            </p>
                        @endif
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
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
