<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Users
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div class="d-flex align-items-center">
                    @if(auth()->user()->type == 'admin')
                        <form action="{{ route('admin.users.import') }}" method="POST" class="d-flex mr-3 align-items-center" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-xs px-4 py-2 btn-secondary uppercase waves-effect ml-3">Import Users</button>
                        </form>
                    @endif
                    <a class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect" href="{{ route('admin.users.create') }}">
                        Add User
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-md-2">
                    <label>Per page:</label>
                    <select wire:model="perPage" class="form-control">
                        @foreach($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 ml-auto">
                    <label>Search:</label>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" />
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-8 d-flex justify-content-start align-items-end">
                    @if($this->selectedCount)
                        <div class="bd-example">
                            <button type="button" class="btn btn-xs btn-danger" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled">Delete Selected</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-index w-100">
                        <thead>
                        <tr>
                            <th class="w-9">
                            </th>
                            <th>
                                Name
                                @include('components.table.sort', ['field' => 'name'])
                            </th>
                            <th>
                                Email
                                @include('components.table.sort', ['field' => 'email'])
                            </th>
                            @if(auth()->user()->type != 'white-admin')
                            <th>
                                Type
                                @include('components.table.sort', ['field' => 'type'])
                            </th>
                            @endif
                            <th>
                                On Hold Bookings
                                @include('components.table.sort', ['field' => 'restricted'])
                            </th>
                            <th>
                                Balance
                                @include('components.table.sort', ['field' => 'balance'])
                            </th>
                            <th>
                                Status
                                @include('components.table.sort', ['field' => 'active'])
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{ $user->id }}" wire:model="selected">
                                </td>
                                <td>
                                    {{ $user->name }}
                                </td>
                                <td>
                                    <a class="link-light-blue" href="mailto:{{ $user->email }}">
                                        <i class="far fa-envelope fa-fw">
                                        </i>
                                        {{ $user->email }}
                                    </a>
                                </td>
                                @if(auth()->user()->type != 'white-admin')
                                <td>
                                    <div style="text-transform: capitalize">{{ $user->type }}</div>
                                </td>
                                @endif
                                <td>
                                    @if($user->restricted)
                                        <div class="text-danger">Yes</div>
                                    @else
                                        <div class="text-success">No</div>
                                    @endif
                                </td>
                                <td>
                                    ${{ $user->balance }}
                                </td>
                                <td>
                                    {!! $user->active ? "<span class='text-success'>Active</span>" : "<span class='text-danger'>Inactive</span>" !!}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-xs btn-success mr-2" href="{{ route('admin.users.edit', $user) }}">
                                            Edit
                                        </a>
                                        <button class="btn btn-xs btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $user->id }})" wire:loading.attr="disabled">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No entries found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @if($this->selectedCount)
                        <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                            User(s) Selected
                        </p>
                    @endif
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    {{ $users->links() }}
                </div>
            </div>
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
