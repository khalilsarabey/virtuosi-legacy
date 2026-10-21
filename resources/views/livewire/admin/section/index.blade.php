<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Homepage Sections
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.sections.create') }}">
                    Add Section
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th></th>
                            <th>
                                Name
                            </th>
                            <th>
                                Type
                            </th>
                            <th>
                                Layout
                            </th>
                            <th class="text-center">
                                Status
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody wire:sortable="updateOrder">
                        @forelse($sections as $section)
                            <tr wire:sortable.item="{{ $section->id }}" wire:key="section-{{ $section->id }}" >
                                <td wire:sortable.handle>
                                    <svg fill="#000000" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 246.041 246.041" xml:space="preserve">
                                        <path d="M92.832,113.021h60.377v20H92.832V113.021z M123.021,0L73.208,59.188l37.129-0.001l-0.001,37.452h25.367l0.001-37.453
                                            l37.129-0.001L123.021,0z M123.021,246.041l49.812-59.189l-37.129,0.001l0.001-37.452h-25.367l-0.001,37.453l-37.13,0.001
                                            L123.021,246.041z"/>
                                    </svg>
                                </td>
                                <td>
                                    {{ $section->name }}
                                </td>
                                <td>
                                    <small>{{ strtoupper($section->type) }}</small>
                                </td>
                                <td>
                                    {{ $section->layout }} columns
                                </td>
                                <td class="text-center">
                                    {!! $section->active ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>' !!}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                            <a class="btn btn-xs btn-success mr-2" href="{{ route('admin.sections.edit', $section) }}">
                                                Edit
                                            </a>
                                            <button class="btn btn-xs btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $section->id }})" wire:loading.attr="disabled">
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
