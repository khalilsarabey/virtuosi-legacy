<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Categories
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.categories.create') }}">
                    Add Category
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Filter by Section</label>
                        <select wire:model="selectedSection" class="form-control">
                            <option selected disabled value="null" disabled>Select...</option>
                            @foreach($sections as $key => $section)
                                <option value="{{ $key }}">{{ $section }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
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
                    <table class="table table-index table-borderless">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th></th>
                            <th style="width: 1%;">
                                Name
                            </th>
                            <th>Section</th>
                            <th>Status</th>
                            <th style="width: 1%;"></th>
                        </tr>
                        </thead>
                        <tbody wire:sortable="updateCategoriesOrder" wire:sortable.options="{ animation: 100 }">
                        @forelse($categories as $category)
                            <tr wire:sortable.item="{{ $category->id }}" wire:key="cat-{{ $category->id }}">
                                <td wire:sortable.handle>
                                    <svg fill="#000000" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 246.041 246.041" xml:space="preserve">
                                        <path d="M92.832,113.021h60.377v20H92.832V113.021z M123.021,0L73.208,59.188l37.129-0.001l-0.001,37.452h25.367l0.001-37.453
                                            l37.129-0.001L123.021,0z M123.021,246.041l49.812-59.189l-37.129,0.001l0.001-37.452h-25.367l-0.001,37.453l-37.13,0.001
                                            L123.021,246.041z"></path>
                                    </svg>
                                </td>
                                <td style="width: 1%;">
                                    <input type="checkbox" value="{{ $category->id }}" wire:model="selected">
                                </td>
                                <td style="width: 1%;">
                                    {{ $category->name }}
                                </td>
                                <td style="width: 1%;">
                                    {{ $category->section->name ?? '' }}
                                </td>
                                <td style="width: 1%;">
                                    {!! $category->active == 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>' !!}
                                </td>
                                <td style="width: 1%;">
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-xs btn-info mr-2" href="{{ route('admin.categories.edit', $category) }}">
                                            Edit
                                        </a>
                                        <button class="btn btn-xs btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $category->id }})" wire:loading.attr="disabled">
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
            <div class="row">
                <div class="col-12">
                    <div class="pt-3">
                        @if($this->selectedCount)
                            <p class="text-sm leading-5">
                            <span class="font-medium">
                                {{ $this->selectedCount }}
                            </span>
                                Categories Selected
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.2.0/dist/livewire-sortable.js"></script>
    <script>
        Livewire.on('confirm', e => {
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush
