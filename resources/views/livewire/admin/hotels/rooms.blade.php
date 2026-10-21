<div>
    <x-hotels-menu menu="hotels" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    {{ $hotel->name }} - Rooms
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div class="d-flex align-items-center">
                    <div class="mr-2">
                        <button wire:click="showInactive" class="btn btn-outline-primary btn-xs px-4 py-2 uppercase">
                            @if($showingInactive)
                                Hide Inactive
                            @else
                                Show Inactive
                            @endif
                        </button>
                    </div>
                    <a href="{{ route('admin.hotels.rooms.create', $hotel) }}" class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect">
                        Add Room
                    </a>
                </div>
            </div>

        </div>
        <div class="container-fluid">
            <div class="row">
                <table class="table table-index clickable-table">
                    <thead>
                    <tr>
                        <th>
                            Room Type
                        </th>
                        <th class="text-center">Availability</th>
                        <th class="text-center"></th>
                        <th class="text-center">
                            Status
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($hotel->rooms as $room)
                        @if($showingInactive || $room->active == 1)
                            <tr>
                            <td>
                                {{ $room->room_type->name }}
                            </td>
                            <td class="text-center text-primary font-bold">
                                {{ $room->start_date }} to {{ $room->end_date }}
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                {!! $room->active == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' !!}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.hotels.rooms.edit', $room) }}">
                                    Edit
                                </a>
                                <a class="btn btn-xs btn-danger" href="{{ route('admin.hotels.rooms', $hotel->id) }}">Delete</a>
                            </td>
                        </tr>
                        @endif
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
