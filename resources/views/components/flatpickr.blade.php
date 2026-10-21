<div wire:ignore x-data="{ initPicker: function() {
    // Helper: Get query param value from URL
    function getQueryParam(name) {
        const url = new URL(window.location.href);
        return url.searchParams.get(name);
    }

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

        // No Livewire emit anymore
    }

    const pickerId = '{{ $attributes['id'] }}';
    const inputSelector = '.flatpickr-' + pickerId;
    const defaultValueFromUrl = getQueryParam('{{ $attributes['name'] ?? $attributes['id'] }}');

    window.flatpickrInstances = window.flatpickrInstances || {};

    @if($attributes['picker'] === 'date')
        window.flatpickrInstances[pickerId] = flatpickr(inputSelector, {
            dateFormat: '{{ config("project.flatpickr_date_format") }}',
            wrap: true,
            disableMobile: true,
            minDate: '{{ $attributes['min'] ?? null }}',
            maxDate: '{{ $attributes['max'] ?? null }}',
            mode: '{{ $attributes['mode'] ?? 'single' }}',
            showMonths: '{{ $attributes['months'] ?? '1' }}',
            defaultDate: defaultValueFromUrl ?? ['{{ $attributes['from'] ?? null }}', '{{ $attributes['to'] ?? null }}'],
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            }
        })
    @elseif($attributes['picker'] === 'time')
        window.flatpickrInstances[pickerId] = flatpickr(inputSelector, {
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            disableMobile: true,
            wrap: true,
            dateFormat: '{{ config("project.flatpickr_time_format") }}',
            defaultDate: defaultValueFromUrl ?? null,
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            }
        })
    @else
        window.flatpickrInstances[pickerId] = flatpickr(inputSelector, {
            enableTime: true,
            time_24hr: true,
            minDate: '{{ $attributes['min'] ?? null }}',
            maxDate: '{{ $attributes['max'] ?? null }}',
            wrap: true,
            disableMobile: true,
            dateFormat: '{{ config("project.flatpickr_datetime_format") }}',
            defaultDate: defaultValueFromUrl ?? "{{ $attributes['value'] ?? null }}",
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr);
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr);
                }
            })
@endif
}}" x-init="initPicker();">
<div class="flatpickr-container">
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
</div>
