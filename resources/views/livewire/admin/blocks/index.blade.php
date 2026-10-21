<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Homepage Builder
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.blocks.create') }}">
                    Add Block
                </a>
            </div>
        </div>
        <style>
            .table td, .table th {
                vertical-align: middle;
            }
        </style>
        <table class="w-100">
            <tbody wire:sortable="updateGroupOrder" wire:sortable-group="updateBlockOrder" wire:sortable.options="{ animation: 500 }">
                @forelse($blocks as $block)
                    <tr class="bg-light" wire:sortable.item="{{ $block->id }}" wire:key="block-{{ $block->id }}" >
                        <td style="width: 20px;" class="p-4" wire:sortable.handle>
                            <div><i class="fa fa-ellipsis-v" aria-hidden="true"></i></div>
                        </td>
                        <td class="py-4">
                            {{ $block->name }}
                            <br/>
                            <small>{{ $block->layout }} columns</small>
                        </td>
                        <td class="py-4 text-end">
                            <div class="d-flex justify-content-end">
                                @if($block->active)
                                    <a href="#" wire:click="activate({{ $block->id }}, {{ (int) !$block->active }})" class="btn btn-xs badge-success mr-2">Active</a>
                                @else
                                    <a href="#" wire:click="deactivate({{ $block->id }}, {{ (int) !$block->active }})" class="btn btn-xs btn-warning mr-2">Inactive</a>
                                @endif
                                <a class="btn btn-xs btn-primary mr-2" href="{{ route('admin.blocks.sub.create', $block->id) }}">
                                    Add Column
                                </a>
                                <a class="btn btn-xs btn-info mr-2" href="{{ route('admin.blocks.edit', $block->id) }}">
                                    Edit
                                </a>
                                <button class="btn btn-xs btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $block->id }})" wire:loading.attr="disabled">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="row">
                                <div class="col-12 bg-white text-dark category-{{$block->id}}" wire:sortable-group.item-group="{{ $block->id }}" wire:sortable-group.options="{ animation: 500 }">
                                    @foreach($block->children as $child)
                                        <div class="row" wire:sortable-group.item="{{ $child->id }}" wire:key="child-{{ $child->id }}">
                                            <div wire:sortable.handle class="col-1 d-flex align-items-center justify-content-end">
                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                            </div>
                                            <div class="col-3 py-3">
                                                @foreach($child->thumbnail as $key => $entry)
                                                    <a class="link-photo" href="{{ $entry['url'] }}">
                                                        <img height="50" src="{{ $entry['preview_thumbnail'] }}" alt="{{ $entry['name'] }}" title="{{ $entry['name'] }}">
                                                    </a>
                                                @endforeach
                                                <span class="pl-2 {{ $child->hide_title ? 'text-danger' : '' }}">{{ $child->name }}</span>
                                            </div>
                                            <div class="col text-center d-flex justify-content-end align-items-center">
                                                @if($child->active)
                                                    <a href="#" wire:click="activate({{ $child->id }}, {{ (int) !$child->active }})" class="btn btn-xs badge-success mr-2">Active</a>
                                                @else
                                                    <a href="#" wire:click="deactivate({{ $child->id }}, {{ (int) !$child->active }})" class="btn btn-xs btn-warning mr-2">Inactive</a>
                                                @endif
                                                <a class="btn btn-xs btn-info mr-2" href="{{ route('admin.blocks.sub.edit', $child->id) }}">
                                                    Edit
                                                </a>
                                                <a href="#" class="btn btn-xs btn-danger mr-2" wire:click="confirm('delete', {{ $child->id }})" wire:loading.attr="disabled">
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
