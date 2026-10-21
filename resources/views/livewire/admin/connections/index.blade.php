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
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.connections.create') }}">
                    Add New Connection
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <table class="table table-index w-full">
                    <thead>
                    <tr>
                        <th>Connection Name</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($connections as $connection)
                        <tr>
                            <td>{{ $connection->name }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
