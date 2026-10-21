<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    White Label Agencies
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.whitelabel.create') }}">
                    Add White Label Agency
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-100">
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Admin Name
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Domain
                            </th>
                            <th>
                                Balance
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($agencies as $agency)
                            <tr>
                                <td>
                                    {{ $agency->name }}
                                </td>
                                <td>
                                    {{ $agency->admin->name }}
                                </td>
                                <td>
                                    <a class="link-light-blue" href="mailto:{{ $agency->email }}">
                                        <i class="far fa-envelope fa-fw">
                                        </i>
                                        {{ $agency->email }}
                                    </a>
                                </td>
                                <td>
                                    {{ $agency->domain }}
                                </td>
                                <td>
                                    @if($edit && $edit == $agency->id)
                                        <input type="number" name="balance" class="form-control-sm" wire:model.defer="balance" required />
                                    @else
                                        ${{ $agency->balance }}
                                    @endif
                                </td>
                                <td>
                                    {!! $agency->status ? "<span class='text-success'>Active</span>" : "<span class='text-danger'>Inactive</span>" !!}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        @if($edit && $edit == $agency->id)
                                            <button type="button" class="btn btn-xs btn-success" wire:click="update">Update</button>
                                        @else
                                            @if($agency->status == 1)
                                                <button class="btn btn-xs btn-danger mr-2" type="button" wire:click="confirm('deactivate', {{ $agency->id }})" wire:loading.attr="disabled">
                                                    Deactivate
                                                </button>
                                            @else
                                                <button class="btn btn-xs btn-dark mr-2" type="button" wire:click="activate({{ $agency->id }})" wire:loading.attr="disabled">
                                                    Activate
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-xs btn-success mr-2" wire:click="edit({{$agency->id}})">
                                                Edit
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No agencies found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
            if (!confirm("Are you sure you want to deactivate this agency?")) {
                return
            }
        @this[e.callback](...e.argv)
        })
    </script>
@endpush
