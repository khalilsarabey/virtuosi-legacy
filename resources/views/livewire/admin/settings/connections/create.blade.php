<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Connection
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-3">
                <label>Connection Name</label>
                <input wire:model.defer="data.name" type="text" class="form-control" />
                @error('data.name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label>URL</label>
                <input wire:model.defer="data.host" {{ $validated ? 'disabled' : '' }} type="url" class="form-control" />
                @error('data.host')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Email</label>
                <input wire:model.defer="data.email" {{ $validated ? 'disabled' : '' }} type="email" class="form-control" />
                @error('data.email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Password</label>
                <input wire:model.defer="data.password" {{ $validated ? 'disabled' : '' }} type="text" class="form-control" />
                @error('data.password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col text-right">
                @if($validated)
                    <button type="button" wire:click="saveConnection" class="btn mt-4 btn-primary">Save Connection</button>
                @else
                    <button type="button" wire:click="validateConnection" class="btn mt-4 btn-primary">Validate Connection</button>
                @endif
            </div>
        </div>
    </section>
</div>
