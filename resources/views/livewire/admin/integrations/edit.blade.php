<div>
    <div class="admin-header position-relative" wire:ignore>
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    {{ $integration->name }} Integration
                </h6>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <form wire:submit="submit" class="pt-3">
                    @if(auth()->user()->type == 'admin' || auth()->user()->type == 'white-admin')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('integration.balance') ? 'invalid' : '' }}">
                                <label class="form-label required">Balance</label>
                                <input step="0.01" type="number" class="form-control" wire:model="integration.balance">
                                <div class="validation-message">
                                    {{ $errors->first('integration.balance') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </section>
</div>
