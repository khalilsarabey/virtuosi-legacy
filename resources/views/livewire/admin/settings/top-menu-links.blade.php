<div>
    <div class="admin-header position-relative">
        <div class="my-auto w-100">
        </div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <div class="btn-group" style="border-radius: 10px;" role="group" aria-label="Basic example">
                    <x-admin.settings.my-agency-menu />
                </div>
                <div class="ml-auto">
                    <button wire:click.prevent="add" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                        Add Menu Item
                    </button>
                </div>
            </div>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="container-fluid mb-4">
            @if($create)
            <form wire:submit.prevent="save" class="row">
                <div class="col-md-5">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" wire:model="menu.name" required />
                </div>
                <div class="col-md-5">
                    <label>Link</label>
                    <input type="url" name="name" class="form-control" wire:model="menu.link" required />
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
            @endif
            <div class="row mt-5">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th></th>
                            <th>
                                Name
                            </th>
                            <th>
                                Link
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody wire:sortable="updateOrder">
                        @foreach($menus as $item)
                            <tr wire:sortable.item="{{ $item->id }}" wire:key="section-{{$item->id}}">
                                <td wire:sortable.handle>
                                    <svg fill="#000" height="15px" width="15px" version="1.1" id="Capa_1"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 246.041 246.041" xml:space="preserve">
                                        <path d="M92.832,113.021h60.377v20H92.832V113.021z M123.021,0L73.208,59.188l37.129-0.001l-0.001,37.452h25.367l0.001-37.453
                                            l37.129-0.001L123.021,0z M123.021,246.041l49.812-59.189l-37.129,0.001l0.001-37.452h-25.367l-0.001,37.453l-37.13,0.001
                                            L123.021,246.041z"></path>
                                    </svg>
                                </td>
                                <td>
                                    @if($editing == $item->id)
                                        <input type="text" wire:model.defer="updatedMenu.name" class="form-control" value="{{ $item->name }}" />
                                    @else
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td>
                                    @if($editing == $item->id)
                                        <input type="text" wire:model.defer="updatedMenu.link" class="form-control" value="{{ $item->link }}" />
                                    @else
                                        {{ $item->link }}
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        @if($editing == $item->id)
                                            <button wire:click.prevent="update({{ $item->id }})" class="btn btn-xs btn-success mr-2 waves-effect">
                                                Update
                                            </button>
                                            <button class="btn btn-xs btn-danger mr-2 waves-effect" type="button" wire:click="cancel" wire:loading.attr="disabled">
                                                Cancel
                                            </button>
                                        @else
                                            <button wire:click.prevent="edit({{ $item->id }})" class="btn btn-xs btn-success mr-2 waves-effect">
                                                Edit
                                            </button>
                                            <button class="btn btn-xs btn-danger mr-2 waves-effect" type="button" wire:click="confirm('delete', {{$item->id}})" wire:loading.attr="disabled">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
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
