<div>
    <x-hotels-menu menu="services" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Services
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                @if($showForm)
                    <div class="ml-auto mr-3">
                        <input type="text" class="form-control" wire:model.defer="serviceName" placeholder="Service Name">
                    </div>
                    <button type="button" wire:click="save" class="btn btn-xs px-4 py-2 btn-outline-primary uppercase waves-effect align-self-center">
                        Save Service
                    </button>
                @else
                    <button type="button" wire:click="addService" class="btn btn-xs px-4 py-2 btn-outline-primary uppercase waves-effect align-self-center">
                        Add Service
                    </button>
                @endif
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <table class="table table-index clickable-table">
                    <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>
                                {{ $service->name }}
                            </td>
                            <td class="text-right">
                                <button type="button" wire:click="editService({{ $service->id }})" class="btn btn-xs btn-secondary">
                                    Edit
                                </button>
                                <button type="button" wire:click="deleteService({{ $service->id }})" class="btn btn-xs btn-danger">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
