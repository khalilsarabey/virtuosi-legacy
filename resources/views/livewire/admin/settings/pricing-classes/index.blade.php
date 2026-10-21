<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Pricing Classes
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect"
                   href="{{ route('admin.settings.auto-pricing.classes.create') }}">
                    Add Pricing Class
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-full">
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td>
                                    {{ $class->name }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-xs btn-success mr-2"
                                           href="{{ route('admin.settings.auto-pricing.classes.edit', $class) }}">
                                            Edit
                                        </a>
                                        <a class="btn btn-xs btn-primary mr-2"
                                           href="{{ route('admin.settings.auto-pricing.classes.show', $class) }}">
                                            Show
                                        </a>
                                        <button class="btn btn-xs btn-danger mr-2" type="button"
                                                wire:click="delete({{ $class->id }})"
                                                wire:loading.attr="disabled">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No classes found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
