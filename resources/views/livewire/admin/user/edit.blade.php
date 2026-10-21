<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit User
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
            <form wire:submit="submit" class="pt-3">
                <div class="row">
                    @if($user->type)
                        @if($user->type == 'agency' || auth()->user()->type == 'white-admin')
                            <div class="col d-flex align-items-center mb-4 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" wire:model.live="user.restricted" type="checkbox" name="restricted" id="restricted">
                                    <label class="form-check-label mt-1" for="restricted">Can only perform on hold bookings</label>
                                </div>
                            </div>
                        @endif
                        @if($user->agency_id == 1)
                                @if($user->type == 'agency')
                                    @if(config('features.autopricing'))
                                        <div class="col d-flex align-items-center mb-4 mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" wire:model.live="user.auto_pricing" type="checkbox" name="auto_pricing" id="auto_pricing">
                                                <label class="form-check-label mt-1" for="auto_pricing">Can access auto pricing</label>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                        @endif
                        @if($user->type == 'user')
                            <div class="col d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="add_bookings" id="add_bookings">
                                    <label class="form-check-label mt-1" for="add_bookings">Can Add Bookings</label>
                                </div>
                            </div>
                            <div class="col d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="edit_bookings" id="edit_bookings">
                                    <label class="form-check-label mt-1" for="edit_bookings">Can Edit Bookings</label>
                                </div>
                            </div>
                            <div class="col d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="view_bookings" id="view_bookings">
                                    <label class="form-check-label mt-1" for="view_bookings">Can View Bookings</label>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                @if($user->type)
                    <div class="row">
                        <div class="col">
                            <div class="form-group {{ $errors->has('user.name') ? 'invalid' : '' }}">
                                <label class="form-label required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                                <input class="form-control" type="text" name="name" id="name" required wire:model="user.name">
                                <div class="text-danger">
                                    {{ $errors->first('user.name') }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group {{ $errors->has('user.email') ? 'invalid' : '' }}">
                                <label class="form-label required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                                <input class="form-control" type="email" name="email" id="email" required wire:model="user.email">
                                <div class="text-danger">
                                    {{ $errors->first('user.email') }}
                                </div>
                            </div>
                        </div>
                        @if($user->type == 'agency' || auth()->user()->type == 'white-admin')
                            <div class="col">
                                <div class="form-group {{ $errors->has('user.balance') ? 'invalid' : '' }}">
                                    <label class="form-label" for="balance">{{ trans('cruds.user.fields.balance') }}</label>
                                    <input class="form-control" type="number" name="balance" id="balance" wire:model="user.balance" step="0.01">
                                    <div class="text-danger">
                                        {{ $errors->first('user.balance') }}
                                    </div>
                                    @if(auth()->user()->type == 'white-admin')
                                        <div><small>Your remaining balance is <strong>${{ get_agency_balance() }}</strong></small></div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="col">
                            <div class="form-group {{ $errors->has('user.active') ? 'invalid' : '' }}">
                                <label class="form-label" for="active">{{ trans('cruds.user.fields.active') }}</label>
                                <select class="form-control" wire:model.live="user.active" step="1">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <div class="text-danger">
                                    {{ $errors->first('user.active') }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group {{ $errors->has('user.password') ? 'invalid' : '' }}">
                                <label class="form-label required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                                <input class="form-control" type="text" name="password" id="password" wire:model.live.debounce.500ms="password" placeholder="6 or more characters">
                                <progress value="{{ $strengthScore }}" max="4" class="w-100"></progress>
                                <div><small>{{ $strengthLevels[$strengthScore] ?? 'Weak' }}</small></div>
                                <div class="validation-message">
                                    {{ $errors->first('user.password') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" wire:click="generatePassword" class="btn btn-dark">Generate Password</button>
                        </div>
                        @if(auth()->user()->type == 'admin')
                            @if(config('features.lowcost') && $site_settings->lowcost == 1)
                                <div class="col-12 my-5">
                                <h6 class="border-bottom pb-3">Low Cost Booking Markup</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('user.air_arabia_markup') ? 'invalid' : '' }}">
                                            <label class="form-label" for="air_arabia_markup">Air Arabia Markup</label>
                                            <input class="form-control" type="number" name="air_arabia_markup" id="air_arabia_markup" wire:model="user.air_arabia_markup" step="0.01">
                                            <div class="validation-message text-danger">
                                                {{ $errors->first('user.air_arabia_markup') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('user.air_arabia_markup_type') ? 'invalid' : '' }}">
                                            <label class="form-label" for="air_arabia_markup_type">Markup Type</label>
                                            <select class="form-control" wire:model.live="user.air_arabia_markup_type">
                                                <option></option>
                                                <option value="percentage">Percentage</option>
                                                <option value="fixed">Fixed</option>
                                            </select>
                                            <div class="validation-message">
                                                {{ $errors->first('user.air_arabia_markup_type') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('user.fly_dubai_markup') ? 'invalid' : '' }}">
                                            <label class="form-label" for="fly_dubai_markup">Fly Dubai Markup</label>
                                            <input class="form-control" type="number" name="fly_dubai_markup" id="fly_dubai_markup" wire:model="user.fly_dubai_markup" step="0.01">
                                            <div class="validation-message text-danger">
                                                {{ $errors->first('user.fly_dubai_markup') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('user.fly_dubai_markup_type') ? 'invalid' : '' }}">
                                            <label class="form-label" for="fly_dubai_markup_type">Markup Type</label>
                                            <select class="form-control" wire:model.live="user.fly_dubai_markup_type">
                                                <option></option>
                                                <option value="percentage">Percentage</option>
                                                <option value="fixed">Fixed</option>
                                            </select>
                                            <div class="validation-message">
                                                {{ $errors->first('user.fly_dubai_markup_type') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                        @if(config('features.connections'))
                            <div class="col-12 my-5">
                                <h6 class="border-bottom pb-3">Blocked Connections</h6>
                                <div class="row">
                                    @foreach($connections as $connection)
    <div class="col-md-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $connection->id }}" id="blocked_connection_{{ $connection->id }}" wire:model.live="blocked_connections">
            <label class="form-check-label mt-3 pt-1" for="blocked_connection_{{ $connection->id }}">
                {{ $connection->name }}
            </label>
        </div>
    </div>
@endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    @if($user->type == 'agency' || $user->type == 'white')
                        <div class="form-group {{ $errors->has('mediaCollections.user_logo') ? 'invalid' : '' }}">
                            <label class="form-label" for="logo">{{ trans('cruds.user.fields.logo') }}</label>
                            <x-dropzone id="logo" name="logo" action="{{ route('admin.users.storeMedia') }}" collection-name="user_logo" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
                            <div class="text-danger">
                                {{ $errors->first('mediaCollections.user_logo') }}
                            </div>
                        </div>
                    @endif
                    <div class="form-group text-right">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            Cancel
                        </a>
                        <button class="btn btn-success mr-2 btn-sm" type="submit">
                            Save
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </section>
</div>
