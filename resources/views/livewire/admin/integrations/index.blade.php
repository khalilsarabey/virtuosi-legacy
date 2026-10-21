<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Integrations
                </h6>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Remaining Balance</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($integrations as $integration)
                        <tr>
                            <td>{{ $integration->name }}</td>
                            <td>${{ number_format($integration->balance, 2) }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.integrations.edit', $integration) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
