<div wire:ignore x-data="{ initPicker: function() {
    function update(value) {
        let el = document.getElementById('clear-{{ $attributes['id'] }}');
        if (value === '') {
            value = null;
            if (el !== null) {
                el.classList.add('invisible');
            }
        } else if (el !== null) {
            el.classList.remove('invisible');
        }
        Livewire.emit('{{ $attributes['wire:model'] }}', value);
    }

    @if($attributes['picker'] === 'date')
        flatpickr('.flatpickr-{{ $attributes['id'] }}', {
            dateFormat: '{{ config("project.flatpickr_date_format") }}',
            wrap: true,
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            }
        })
    @elseif($attributes['picker'] === 'time')
        flatpickr('.flatpickr-{{ $attributes['id'] }}', {
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            wrap: true,
            dateFormat: '{{ config("project.flatpickr_time_format") }}',
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            }
        })
    @else
        flatpickr('.flatpickr-{{ $attributes['id'] }}', {
            enableTime: true,
            time_24hr: true,
            wrap: true,
            dateFormat: '{{ config("project.flatpickr_datetime_format") }}',
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            }
        })
    @endif
}}" x-init="initPicker();">
    <div class="flatpickr flatpickr-{{ $attributes['id'] }} relative">
        @if(!isset($attributes['required']))
            <div class="position-absolute d-flex align-items-center">
                <div id="clear-{{ $attributes['id'] }}" data-clear>
                    <span>X</span>
                </div>
            </div>
        @endif
        <input type="text" class="form-control bg-white" {{ $attributes }} data-input>
    </div>
</div>
