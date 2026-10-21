<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('price_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Price" format="csv" />
                <livewire:excel-export model="Price" format="xlsx" />
                <livewire:excel-export model="Price" format="pdf" />
            @endif




        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
            Search:
            <input type="text" wire:model.debounce.300ms="search" class="w-full sm:w-1/3 inline-block" />
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-index w-full">
                <thead>
                    <tr>
                        <th class="w-9">
                        </th>
                        <th class="w-28">
                            {{ trans('cruds.price.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.flight') }}
                            @include('components.table.sort', ['field' => 'flight.dep_date'])
                        </th>
                        <th>
                            {{ trans('cruds.flight.fields.dep_time') }}
                            @include('components.table.sort', ['field' => 'flight.dep_time'])
                        </th>
                        <th>
                            {{ trans('cruds.flight.fields.arr_time') }}
                            @include('components.table.sort', ['field' => 'flight.arr_time'])
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.adult_price') }}
                            @include('components.table.sort', ['field' => 'adult_price'])
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.child_price') }}
                            @include('components.table.sort', ['field' => 'child_price'])
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.infant_price') }}
                            @include('components.table.sort', ['field' => 'infant_price'])
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.activate_once') }}
                            @include('components.table.sort', ['field' => 'activate_once'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prices as $price)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $price->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $price->id }}
                            </td>
                            <td>
                                @if($price->flight)
                                    <span class="badge badge-relationship">{{ $price->flight->dep_date ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($price->flight)
                                    {{ $price->flight->dep_time ?? '' }}
                                @endif
                            </td>
                            <td>
                                @if($price->flight)
                                    {{ $price->flight->arr_time ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $price->adult_price }}
                            </td>
                            <td>
                                {{ $price->child_price }}
                            </td>
                            <td>
                                {{ $price->infant_price }}
                            </td>
                            <td>
                                {{ $price->activate_once }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('price_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.prices.show', $price) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('price_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.prices.edit', $price) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('price_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $price->id }})" wire:loading.attr="disabled">
                                            {{ trans('global.delete') }}
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">No entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3">
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $prices->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush