<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Pages
                </h6>
                <div>
                    <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.pages.create') }}">
                        Add Page
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index clickable-table">

                            <thead>
                            <tr>
                                <th>Title</th>
                                <th class="text-center">Link</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Homepage</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($pages as $page)
                                <tr>
                                    <td>{{ $page->title }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('frontend.page.show', $page->slug) }}" target="_blank" class="text-primary">
                                            {{ url('page/' . $page->slug) }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="toggleStatus({{ $page->id }})" class="btn btn-xs {{ $page->status ? 'btn-outline-success' : 'btn-outline-secondary' }}">
                                            <i class="fas fa-{{ $page->status ? 'check' : 'times' }} mr-1"></i>
                                            {{ $page->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        @if ($page->is_homepage)
                                            <span class="btn btn-xs btn-primary">Homepage</span>
                                        @else
                                            <button wire:click="setAsHomepage({{ $page->id }})" class="btn btn-xs btn-outline-primary">
                                                Set as Homepage
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="/v2/frontend/page/{{ $page->slug }}" target="_blank" class="btn btn-xs btn-info">
                                            <i class="fas fa-eye"></i> PREVIEW
                                        </a>
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-xs btn-primary">
                                            <i class="fas fa-edit"></i> EDIT
                                        </a>
                                        <button wire:click="confirmPageDeletion({{ $page->id }})"
                                                class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i> DELETE
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No pages found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this page?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deletePage">Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('livewire:load', function () {
                Livewire.on('showDeleteModal', function () {
                    $('#deleteModal').modal('show');
                });
            });

            Livewire.on('confirmingPageDeletion', () => {
                $('#deleteModal').modal('show');
            });

            Livewire.on('pageDeleted', () => {
                $('#deleteModal').modal('hide');
            });
        </script>
    @endpush
</div>
