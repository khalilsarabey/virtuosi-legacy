 <div wire:ignore class="w-100">
     @if(isset($attributes['buttons']))
         <div id="{{ $attributes['id'] }}-btn-container" class="mb-3">
             <button type="button" class="btn btn-info btn-xs select-all-button">Select All</button>
             <button type="button" class="btn btn-info btn-xs deselect-all-button">Deselect All</button>
         </div>
     @endif
        <select wire:ignore class="form-control select2 w-100" data-placeholder="Select" {{ $attributes }}>
            @if(!isset($attributes['multiple']))
                <option></option>
            @endif
            @foreach($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
 </div>

@push('scripts')
    <script>
        document.addEventListener("livewire:initialized", () => {
            let el = $('#{{ $attributes['id'] }}')
            let buttonsId = '#{{ $attributes['id'] }}-btn-container'

            function initButtons() {
                $(buttonsId + ' .select-all-button').click(function (e) {
                    el.val(_.map(el.find('option'), opt => $(opt).attr('value')))
                    el.trigger('change')
                })

                $(buttonsId + ' .deselect-all-button').click(function (e) {
                    el.val([])
                    el.trigger('change')
                })
            }

            function initSelect () {
                initButtons()
                el.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Select',
                    allowClear: !el.attr('required')
                })
            }

        initSelect()

    Livewire.hook('commit', ({ succeed }) => {
        succeed(() => queueMicrotask(() => initSelect()))
    });

    el.on('change', function (e) {
        let data = $(this).select2("val")
        if (data === "") {
            data = null
        }
@this.set('{{ $attributes->wire('model')->value() }}', data)
    });
});
    </script>
@endpush
