<div
    x-data="{
        model: @entangle($attributes->wire('model')),
        initSelect2: function() {
            let select2 = $(this.$refs.select)
                .not('.select2-hidden-accessible')
                .select2({
                    theme: 'bootstrap-5',
                    dropdownAutoWidth: true,
                    width: '100%',
                    placeholder: 'Select',
                    minimumResultsForSearch: 10,
                });

            select2.on('select2:select', (event) => {
                //this.model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value);
                this.model = event.params.data.id;
            });

            select2.on('select2:unselect', (event) => {
                //this.model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value);
                this.model = event.params.data.id;
            });

            this.$watch('model', (value) => {
                select2.val(value).trigger('change');
            });
        }
    }"
    x-init="initSelect2"
    wire:ignore
>
    <select x-ref="select" {{ $attributes->merge(['class' => 'form-control']) }}>
        {{ $slot }}
    </select>
</div>
