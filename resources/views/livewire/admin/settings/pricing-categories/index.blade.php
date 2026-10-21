<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100"></div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Pricing Categories
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <button class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" wire:click="add">Add Category</button>
            </div>
        </div>
        @if($addingCategory)
            <div class="row mb-3 d-flex align-items-end">
                <div class="col-md-8"></div>
                <div class="col-md-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" wire:model.defer="categoryName" class="form-control" />
                </div>
                <div class="col-md-1">
                    <button class="btn btn-xs px-4 py-2 btn-secondary uppercase waves-effect" wire:click="save">Save</button>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <table class="table table-index w-full">
                    <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                @if($editingCategory && $categoryId == $category->id)
                                    <input type="text" class="form-control d-inline-block" wire:model.defer="categoryName" />
                                    <button class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect ml-3" wire:click="update({{ $category->id }})">Save</button>
                                @else
                                    {{ $category->name }}
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-xs btn-success mr-2" type="button" wire:click="edit({{ $category->id }})">
                                        Edit
                                    </button>
                                    <button class="btn btn-xs btn-danger mr-2" type="button"
                                            wire:click="confirm('delete', {{ $category->id }})"
                                            wire:loading.attr="disabled">
                                        Delete
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
