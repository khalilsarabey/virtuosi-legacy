<div class="input-wrapper" wire:ignore>
    <span class="label-text">{{ $attributes['label'] }}</span>
    <div class="form-group">
        <span class="la la-map-marker form-icon"></span>
        <div class="input-items">
            <select name="{{ $attributes['name'] }}" class="{{$attributes['name']}} form-control" required {{ $attributes }}>
                <option value=""> Select</option>
                @foreach($options as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
