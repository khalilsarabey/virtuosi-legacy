<div>
    <x-hotels-menu menu="room-types" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Room Types
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div>
                    <button type="button" wire:click="add" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                        Add Room Type
                    </button>
                </div>
            </div>
            @if($showForm)
                <form wire:submit.prevent="addRoom">
                    <div class="row mt-3 pb-3 bg-light">
                        <div class="col">
                            <label>Type</label>
                            <input type="text" wire:model.defer="form.name" required class="form-control" />
                        </div>
                        <div class="col">
                            <label>Adult Capacity</label>
                            <input type="number" wire:model.defer="form.adult" required class="form-control" />
                        </div>
                        <div class="col">
                            <label>Child Capacity</label>
                            <input type="number" wire:model.defer="form.child" required class="form-control" />
                        </div>
                        <div class="col">
                            <label>Infant Capacity</label>
                            <input type="number" wire:model.defer="form.infant" required class="form-control" />
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" wire:click="save" class="btn btn-xs px-4 py-2 mb-1 w-100 btn-secondary uppercase waves-effect">
                                Save
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">Changing the capacity of existing room types will not affect rooms that are assigned to this type.</div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row">
                <table class="table table-index clickable-table">
                    <thead>
                    <tr>
                        <th>
                            Type
                        </th>
                        <th class="text-center">
                            Capacity
                        </th>
                        <th>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($types as $type)
                        <tr>
                            <td>
                                {{ $type->name }}
                            </td>
                            <td class="text-center font-weight-bold">
                                Adult: <span class="text-primary">{{ $type->adult }}</span><br/>
                                Child: <span class="text-primary">{{ $type->child }}</span><br/>
                                Infant: <span class="text-primary">{{ $type->infant }}</span>
                            </td>
                            <td class="text-right">
                                <button type="button" wire:click="edit({{ $type->id }})" class="btn btn-xs px-4 py-1 btn-secondary uppercase waves-effect">Edit</button>
                                <button type="button" wire:click="delete({{ $type->id }})" class="btn btn-xs px-4 py-1 btn-danger uppercase waves-effect">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
@push('scripts')
    <script>
        window.addEventListener('close-modals', event => {
            $('.close-modal').trigger('click');
        })
    </script>
@endpush
