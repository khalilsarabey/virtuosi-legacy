<div class="row mt-2">
    @php $depth = $depth + 20 @endphp
    <div class="col-md-6" style="padding-left: {{ $depth + 20 }}px;"> {{-- Adjust the indentation as needed --}}
        @if($editingCategory && $categoryId == $category->id)
            <input type="text" class="form-control d-inline-block" wire:model="categoryName" />
            <button class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect ml-3" wire:click="update({{ $category->id }})">Save</button>
        @else
            <label>
                <input type="checkbox" class="form-check-inline" wire:model="selected" value="{{ $category->id }}" />
                {{ $category->name }}
            </label>
        @endif
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-primary mr-2" type="button" wire:click="addSub({{ $category->id }})">
                Add Sub
            </button>
            <button class="btn btn-xs btn-success mr-2" type="button" wire:click="edit({{ $category->id }})">
                Edit
            </button>
            <button class="btn btn-xs btn-danger mr-2" type="button"
                    wire:click="confirm('delete', {{ $category->id }})"
                    wire:loading.attr="disabled">
                Delete
            </button>
        </div>
    </div>
</div>

@if($addingSubCategory && $categoryId == $category->id)
    <div class="row mt-2">
        <div class="col-md-6">
            <div  style="padding-left: {{ ($depth + 1) * 20 }}px;"> {{-- Adjust the indentation as needed --}}
                <input type="text" class="form-control  d-inline-block" wire:model="subCategoryName" />
            </div>
        </div>
        <div class="col">
            <button class="btn btn-xs px-4 py-2 btn-primary uppercase waves-effect ml-3" wire:click="createSub({{ $category->id }})">Save</button>
        </div>
    </div>
@endif

@if($category->children->isNotEmpty())
    @php $depth = $depth + 20 @endphp
    @foreach($category->children as $child)
        @include('components.admin-categories-cell', ['category' => $child, 'depth' => $depth])
    @endforeach
@endif
