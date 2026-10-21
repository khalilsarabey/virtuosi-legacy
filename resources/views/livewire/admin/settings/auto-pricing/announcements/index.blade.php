<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Announcements
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>

                <div class="d-flex">
                    <div class="mr-2">
                        <button wire:click="create" data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                            Add Announcement
                        </button>
                    </div>
                </div>

            </div>
        </div>
        @if(!$creating && !$editing)
            <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th>
                                Date Added
                            </th>
                            <th>
                                Announcement
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td>
                                    {{ $announcement->created_at->format('F j, Y') }}
                                </td>
                                <td>
                                    {{ $announcement->title }}
                                </td>
                                <td class="text-right">
                                    <button data-bs-toggle="modal" data-bs-target="#editModal" wire:click="edit({{ $announcement->id }})" class="btn btn-primary btn-sm">Edit</button>
                                    <button wire:click="delete({{ $announcement->id }})" class="btn btn-danger btn-sm">Delete</button>
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
        @endif
        <div class="container-fluid">
            @if($creating)
            <div class="card mt-5">
                <div class="card-header">
                    <h5>New Announcement</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Title</label>
                            <input type="text" class="form-control" placeholder="Title" wire:model="title"  />
                        </div>
                        <div class="col-6">
                            <div class="form-group form-check d-flex align-items-center mt-5 mb-5">
                                <input type="checkbox" class="form-check-input " id="internal_announcement" wire:model="internal">
                                <label class="form-check-label mt-1" for="internal_announcement">Internal Announcement</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group form-check d-flex align-items-center mt-5 mb-5">
                                <input type="checkbox" class="form-check-input " id="agencies_announcement"  wire:model="agencies">
                                <label class="form-check-label mt-1" for="agencies_announcement">Agencies Announcement</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label>Content</label>
                            <div
                                class="d-block"
                                 x-data
                                 wire:key="{{ rand() }}"
                                 x-ref="content"
                                 x-init="
                                console.log('init');
                                var toolbarOptions = [
                                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    [{ 'color': [] }, { 'background': [] }],
                                    [{ 'align': [] }],
                                    ['clean']
                                ];
                                const quill = new Quill($refs.content, {
                                    theme: 'snow',
                                      modules: {
                                        toolbar: toolbarOptions
                                      }
                                });
                                quill.on('text-change', () => {
                                    $wire.set('content', quill.root.innerHTML, true)
                                })
                             ">
                                {!! $content !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 mt-5 text-right">
                    <button type="button" class="btn btn-secondary mt-5" wire:click="close">Cancel</button>
                    <button type="button" wire:click="save" class="btn btn-primary mt-5" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
            @endif
                @if($editing)
                    <div class="card mt-5">
                        <div class="card-header">
                            <h5>Edit Announcement</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label>Title</label>
                                    <input type="text" class="form-control" placeholder="Title" wire:model="title"  />
                                </div>
                                <div class="col-6">
                                    <div class="form-group form-check d-flex align-items-center mt-5 mb-5">
                                        <input type="checkbox" class="form-check-input " id="internal_announcement" wire:model="internal">
                                        <label class="form-check-label mt-1" for="internal_announcement">Internal Announcement</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group form-check d-flex align-items-center mt-5 mb-5">
                                        <input type="checkbox" class="form-check-input " id="agencies_announcement"  wire:model="agencies">
                                        <label class="form-check-label mt-1" for="agencies_announcement">Agencies Announcement</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label>Content</label>
                                    <div
                                        class="d-block"
                                        x-data
                                        wire:key="{{ rand() }}"
                                        x-ref="content"
                                        x-init="
                                console.log('init');
                                var toolbarOptions = [
                                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    [{ 'color': [] }, { 'background': [] }],
                                    [{ 'align': [] }],
                                    ['clean']
                                ];
                                const quill = new Quill($refs.content, {
                                    theme: 'snow',
                                      modules: {
                                        toolbar: toolbarOptions
                                      }
                                });
                                quill.on('text-change', () => {
                                    $wire.set('content', quill.root.innerHTML, true)
                                })
                             ">
                                        {!! $content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 mt-5 text-right">
                            <button type="button" class="btn btn-secondary mt-5" wire:click="close">Cancel</button>
                            <button type="button" wire:click="update" class="btn btn-primary mt-5" data-bs-dismiss="modal">Update</button>
                        </div>
                    </div>
                @endif
        </div>
    </section>

</div>
@push('scripts')
    <link href="https://cdn.quilljs.com/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/quill.js"></script>
@endpush
