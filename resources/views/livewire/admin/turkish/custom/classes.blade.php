<div>
    @foreach($customData as $k => $data)
        <div class="row mt-5">
            <div class="col-6">
                <label>Class Name</label>
                <input type="text" class="form-control" placeholder="" wire:model="customData.{{ $k }}.class_name" />
            </div>
            <div class="col-6">
                <label>Selling Commission</label>
                <input step=".01" type="number" class="form-control" placeholder="" wire:model="customData.{{ $k }}.selling_commission" />
            </div>
            <div class="col-6">
                <label>Selling Service Fee</label>
                <input step=".01" type="number" class="form-control" placeholder="" wire:model="customData.{{ $k }}.elling_service_fee" />
            </div>
            <div class="col-6">
                <label>Selling Fixed Fee</label>
                <input step=".01" type="number" class="form-control" placeholder="" wire:model="customData.{{ $k }}.selling_fixed_fee" />
            </div>
            <div class="col-6">
                <label>Net Commission</label>
                <input step=".01" type="number" class="form-control" placeholder="" wire:model="customData.{{ $k }}.net_commission" />
            </div>
            <div class="col-6">
                <label>Net Service Fee</label>
                <input step=".01"  type="number" class="form-control" placeholder="" wire:model="customData.{{ $k }}.net_service_fee" />
            </div>
        </div>
        <div class="col-12 text-right">
            <button wire:click="remove({{ $k }})" type="button" class="mt-3 btn btn-xs px-4 py-2 btn-danger uppercase waves-effect">Remove</button>
        </div>
    @endforeach
</div>
