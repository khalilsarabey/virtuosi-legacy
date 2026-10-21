<?php

namespace App\Livewire\Admin\Category;

use App\Livewire\WithConfirmation;
use App\Livewire\WithSorting;
use App\Models\Category;
use App\Models\Section;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public int $perPage;

    public array $sections;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'name',
        ],
        'sortDirection' => [
            'except' => 'asc',
        ],
    ];

    public mixed $selectedSection = null;

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->selectedSection   = null;
        $this->sections          = Section::pluck('name', 'id')->toArray();
    }

    public function render()
    {
        $query = Category::with('section');
        if($this->selectedSection) {
            $query->where('section_id', $this->selectedSection);
        }
        $categories = $query->orderBy('position')->get();

        return view('livewire.admin.category.index', compact('categories', 'query'))->layout('layouts.admin');
    }

    public function updateCategoriesOrder($list)
    {
        foreach($list as $item) {
            Category::find($item['value'])->update(['position' => $item['order']]);
        }
    }

    public function deleteSelected()
    {

        Category::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function delete($category)
    {
        Category::where('id', $category)->delete();
    }
}
