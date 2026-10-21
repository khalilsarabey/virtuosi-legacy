<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Turkish Sections
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th></th>
                            <th>
                                Name
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody wire:sortable="updateOrder">
                        @forelse($sections as $des)
                            <tr wire:sortable.item="{{ $des->id }}" wire:key="section-{{ $des->id }}">
                                <td wire:sortable.handle>
                                    <svg fill="#000000" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 246.041 246.041" xml:space="preserve">
                                        <path d="M92.832,113.021h60.377v20H92.832V113.021z M123.021,0L73.208,59.188l37.129-0.001l-0.001,37.452h25.367l0.001-37.453
                                            l37.129-0.001L123.021,0z M123.021,246.041l49.812-59.189l-37.129,0.001l0.001-37.452h-25.367l-0.001,37.453l-37.13,0.001
                                            L123.021,246.041z"/>
                                    </svg>
                                </td>
                                <td>
                                    <label>
                                        {{ $des->title }}
                                    </label>
                                </td>
                                <td>
                                    @if($renameId == $des->id)
                                        <div class="d-flex">
                                            <input type="text" class="form-control" wire:model="newName" />
                                            <button wire:click="saveNewName({{ $des->id }})" class="btn btn-sm btn-outline-primary ml-3">Update Name</button>
                                        </div>
                                    @else
                                        {{ $des->active == 1 ? 'Active' : 'Inactive' }}
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center mt-2">
                                        <a href="{{ route('admin.turkish.destinations.index', $des) }}" class="btn btn-xs btn-primary mr-2">
                                            View
                                        </a>
                                        <button type="button" class="btn btn-xs btn-success mr-2" wire:click="rename({{ $des->id }})">
                                            Rename
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#createClassModal" wire:click="duplicate({{ $des->id }})" class="btn btn-xs btn-secondary mr-2">
                                            Duplicate
                                        </button>
                                        <button type="button" wire:click="toggleStatus({{ $des->id }}, {{ $des->active }})" class="btn btn-xs btn-warning mr-2">
                                            Toggle Status
                                        </button>
                                        <button class="btn btn-xs btn-danger mr-2" type="button"
                                                wire:click="deleteSection({{ $des->id }})"
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
        </div>
    </section>
</div>
