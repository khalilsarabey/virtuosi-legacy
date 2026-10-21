<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Pricing Class
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">CLASS NAME</label>
                        <input type="text" class="form-control" required wire:model="class.name" />
                    </div>
                </div>
                @foreach($class['airlines'] as $key => $value)
                    <input type="hidden" value="{{ $value['pricing_airline_id'] }}" wire:model.live="class.airlines.{{$key}}.pricing_airline_id" />
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="mt-5 text-primary font-weight-bold font-size-14">{{ $value['name'] }}</div>
                        </div>
                        <div class="col">
                            <label class="form-label">SELLING COMMISSION</label>
                            <input step=".01" type="number" class="form-control" wire:model="class.airlines.{{$key}}.selling_commission" />
                        </div>
                        <div class="col">
                            <label class="form-label">SELLING SERVICE FEE</label>
                            <input step=".01" type="number" class="form-control" wire:model="class.airlines.{{$key}}.selling_service_fee" />
                        </div>
                        <div class="col">
                            <label class="form-label">SELLING FIXED FEE</label>
                            <input step=".01" type="number" class="form-control" wire:model="class.airlines.{{$key}}.selling_fixed_fee" />
                        </div>
                        <div class="col">
                            <label class="form-label">NET COMMISSION</label>
                            <input step=".01" type="number" class="form-control" wire:model="class.airlines.{{$key}}.net_commission" />
                        </div>
                        <div class="col">
                            <label class="form-label">NET SERVICE FEE</label>
                            <input step=".01" type="number" class="form-control" wire:model="class.airlines.{{$key}}.net_service_fee" />
                        </div>
                    </div>
                @endforeach
                <hr/>
                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
