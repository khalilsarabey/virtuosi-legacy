<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Connection
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
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
                <input wire:model.defer="data.host" type="url" class="form-control" />
                @error('data.host')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Email</label>
                <input wire:model.defer="data.email" type="email" class="form-control" />
                @error('data.email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Password</label>
                <input wire:model.defer="data.password" type="text" class="form-control" />
                @error('data.password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col text-right">
                <button type="button" wire:click="validateConnection" class="btn mt-4 btn-primary">Validate Connection</button>
            </div>
        </div>
    </section>
</div>
