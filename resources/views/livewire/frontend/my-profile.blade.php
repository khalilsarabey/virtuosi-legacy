<div>
    <section class="breadcrumb-area bread-bg-flights mb-5">
        <div class="breadcrumb-wrap">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="breadcrumb-content my-5">
                            <div class="section-heading">
                                <div class="left-side-info" style="color:#fff">
                                    <h2 class="sec__title_list mb-1"
                                        style="font-size: 2rem !important; font-weight: 500 !important">My Profile</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <div class="breadcrumb-content my-5">
                            <div class="section-heading">
                                <div class="left-side-info" style="color:#fff">
                                    <h2 class="sec__title_list mb-1" style="font-size: 2rem !important; font-weight: 500 !important">${{$profile->balance}} </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                @if($errors->any())
                    @foreach($errors as $error)
                        <div class="col-12">
                            <div class="alert alert-danger">{{ $error }}</div>
                        </div>
                    @endforeach
                @endif
                @if(session('message'))
                    <div class="col-12">
                        <div class="alert alert-success">{{ session('message') }}</div>
                    </div>
                @endif
                <div class="col-lg-6 mb-3">
                    <label>Name</label>
                    <input type="text" disabled name="name" id="name" class="form-control" value="{{ $profile->name }}" />
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Email</label>
                    <input type="text" disabled class="form-control" value="{{ $profile->email }}" />
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" wire:model.debounce.500ms="password" />
                    <progress value="{{ $strengthScore }}" max="4" class="w-100"></progress>
                    @if($password)
                        <div><small>{{ $strengthLevels[$strengthScore] }}</small></div>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" wire:model.debounce.500ms="password_confirmation" />
                </div>
                    <div class="col-12 text-right">
                        <button type="button" wire:click="generatePassword" class="btn btn-dark">Generate Password</button>
                    </div>
                <div class="col-lg-12 d-flex justify-content-end">
                    <button class="btn btn-primary my-3" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </section>
</div>
