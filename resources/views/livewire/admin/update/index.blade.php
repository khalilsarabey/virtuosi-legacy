<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Updates
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.updates.create') }}">
                    Add Update
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th>
                                Text
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($updates as $update)
                            <tr>
                                <td>
                                    {{ $update->text }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.updates.edit', $update) }}">
                                            Edit
                                        </a>
                                        <button class="btn btn-sm btn-primary mr-2" type="button" wire:click="confirm('delete', {{ $update->id }})" wire:loading.attr="disabled">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No entries found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
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
