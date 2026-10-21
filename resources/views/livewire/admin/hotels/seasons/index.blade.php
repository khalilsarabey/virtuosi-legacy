<div>
    <x-hotels-menu menu="seasons" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Seasons
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div>
                    <button type="button" wire:click="createSeason" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                        Add Season
                    </button>
                </div>
            </div>
            @if($editSeason || $createSeason)
                <form wire:submit.prevent="addRoom">
                    <div class="row mt-3 pb-3 bg-light">
                        <div class="col">
                            <label>Name</label>
                            <input type="text" wire:model.defer="form.name" required class="form-control" />
                        </div>
                        <div class="col">
                            <label>Start Date</label>
                            <x-flatpickr required class="form-control" id="form_start_date" wire:model="form.start_date" name="form_start_date" picker="date" />
                        </div>
                        <div class="col">
                            <label>End Date</label>
                            <x-flatpickr required class="form-control" id="room_start_date" wire:model="form.end_date" name="room_start_date" picker="date" />
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" wire:model.defer="form.active" type="checkbox" role="switch" id="active">
                                    <label class="form-check-label pt-1" for="active">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" wire:click="save" class="btn btn-xs px-4 py-2 mb-1 w-100 btn-secondary uppercase waves-effect">Save</button>
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
                                Name
                            </th>
                            <th class="text-center">
                                Start Date
                            </th>
                            <th class="text-center">End Date</th>
                            <th class="text-center">Status</th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seasons as $value)
                            <tr>
                                <td>
                                    {{ $value->name }}
                                </td>
                                <td class="text-center">
                                    {{ $value->start_date }}
                                </td>
                                <td class="text-center">{{ $value->end_date }}</td>
                                <td class="text-center">
                                    @if($value->active == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <button wire:click="editSeason({{ $value->id }})" class="btn btn-xs btn-primary" type="button">Edit</button>
                                    <button wire:click="delete({{ $value->id }})" class="btn btn-xs btn-danger" type="button">Delete</button>
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
