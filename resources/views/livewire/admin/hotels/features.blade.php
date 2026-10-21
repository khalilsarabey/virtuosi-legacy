<div>
    <x-hotels-menu menu="features" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Hotels & Rooms Features
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div>
                    <button type="button" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" wire:click="add">
                        Add Feature
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            @if($showForm)
                <form wire:submit.prevent="addRoom">
                    <div class="row mt-3 pb-3 bg-light">
                        <div class="col">
                            <label>Title</label>
                            <input type="text" wire:model.defer="form.title" required class="form-control" />
                        </div>
                        @if($action == 'add')
                        <div class="col">
                            <label>Type</label>
                            <select wire:model="form.type" class="form-control form-select">
                                <option value="hotel">Hotel</option>
                                <option value="room">Room</option>
                            </select>
                        </div>
                        @endif
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" wire:click="save" class="btn btn-xs px-4 py-2 mb-1 w-100 btn-secondary uppercase waves-effect">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            <div class="row">
                <table class="table table-index clickable-table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th class="text-center">Type</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($features_data as $feature)
                        <tr>
                            <td class="font-weight-bold">{{ $feature->title }}</td>
                            <td class="text-center"><span class="badge badge-pill text-{{ $feature->type == 'hotel' ? 'success' : 'info' }} py-1 font-size-11 text-uppercase">{{ $feature->type }}</span></td>
                            <td class="text-right">
                                <button wire:click="addOption({{$feature->id}})" class="btn btn-dark btn-xs py-1">Add Option</button>
                                <button wire:click="edit({{$feature->id}})" class="btn btn-primary btn-xs py-1">Edit</button>
                                <button wire:click="delete({{$feature->id}})" class="btn btn-danger btn-xs py-1">Delete</button>
                            </td>
                        </tr>
                        @foreach($feature->children as $child)
                            <tr>
                                <td class="pl-5">{{ $child->title }}</td>
                                <td class="text-center"><span class="badge badge-pill text-{{ $child->type == 'hotel' ? 'success' : 'info' }} py-1 font-size-11 text-uppercase">{{ $child->type }}</span></td>
                                <td class="text-right">
                                    <button wire:click="editOption({{$child->id}})" class="btn btn-primary btn-xs py-1">Edit</button>
                                    <button wire:click="delete({{$child->id}})" class="btn btn-danger btn-xs py-1">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
