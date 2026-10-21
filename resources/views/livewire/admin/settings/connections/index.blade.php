<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Connections
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a class="btn btn-sm btn-primary" href="{{ route('admin.connections.create') }}">Add Connection</a>
            </div>
        </div>
        <div class="row">
                <div class="col-md-12">
                    @if($connections->count())
                        <table class="table table-index p-0">
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Host
                            </th>
                            <th class="text-center">
                                Status
                            </th>
                            <th>

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($connections as $connection)
                            <tr>
                                <td>
                                    {{ $connection->name }}
                                </td>
                                <td>
                                    {{ $connection->host }}
                                </td>
                                <td class="text-center font-weight-bold">
                                    {!! $connection->active == 1 ? "<span class='text-success'>Active</span>" : "<span class='text-danger'>Inactive</span>" !!}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.connections.edit', $connection->id) }}"
                                       class="btn btn-xs btn-primary">Edit</a>
                                    @if($connection->active == 1)
                                        <button type="button" wire:click="update({{ $connection->id }})" class="btn btn-xs btn-danger">Deactivate</button>
                                    @else
                                        <button type="button" wire:click="update({{ $connection->id }})" class="btn btn-xs btn-primary">Activate</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="text-center">No connections found</div>
                    @endif
                </div>
            </div>
    </section>
</div>
