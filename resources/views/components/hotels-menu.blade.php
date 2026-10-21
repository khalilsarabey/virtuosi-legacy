<div class="admin-header position-relative" style="top: -50px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-right">
                <a class="btn btn-xs px-4 py-2 btn{{ $menu == 'hotels' ? '' : '-outline' }}-dark uppercase waves-effect text-white" style="color: white!important;" href="{{ route('admin.hotels.index') }}">
                    Hotels
                </a>
                <a class="btn btn-xs px-4 py-2 btn{{ $menu == 'features' ? '' : '-outline' }}-dark uppercase waves-effect text-white" style="color: white!important;" href="{{ route('admin.hotels.features') }}">
                    Features
                </a>
                <a class="btn btn-xs px-4 py-2 btn{{ $menu == 'room-types' ? '' : '-outline' }}-dark uppercase waves-effect" style="color: white!important;" href="{{ route('admin.hotels.room-types') }}">
                    Room Types
                </a>
                <a class="btn btn-xs px-4 py-2 btn{{ $menu == 'services' ? '' : '-outline' }}-dark uppercase waves-effect" style="color: white!important;" href="{{ route('admin.hotels.services') }}">
                    Services
                </a>
                <a class="btn btn-xs px-4 py-2 btn{{ $menu == 'rules' ? '' : '-outline' }}-dark uppercase waves-effect text-white" style="color: white!important;" href="{{ route('admin.hotels.rules.index') }}">
                    Rules
                </a>
            </div>
        </div>
    </div>
</div>
