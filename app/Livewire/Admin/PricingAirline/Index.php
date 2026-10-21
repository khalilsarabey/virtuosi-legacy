<?php

namespace App\Livewire\Admin\PricingAirline;

use App\Livewire\WithConfirmation;
use App\Livewire\WithSorting;
use App\Models\Airline;
use App\Models\PricingAirline;
use App\Models\PricingCategory;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithConfirmation;
    use WithSorting;
    protected $paginationTheme = 'bootstrap';
    public int $perPage;

    public array $orderable;

    public $categories;

    public string $search = '';

    public array $selected = [];

    public $category;

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
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 50;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new PricingAirline())->orderable;
        $this->categories        = PricingCategory::orderBy('name')->get();
    }

    public function render()
    {
        $query = PricingAirline::with('category')->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $airlines = $query->paginate($this->perPage);

        return view('livewire.admin.pricing-airline.index', compact('airlines', 'query'))->layout('layouts.admin');
    }

    public function deleteSelected()
    {
        PricingAirline::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }
    public function delete(PricingAirline $airline)
    {
        $airline->delete();
    }

    public function assign()
    {
        if(count($this->selected) && is_numeric($this->category)):
            PricingAirline::whereIn('id', $this->selected)->update(['pricing_category_id' => $this->category]);
            $this->selected = [];
        endif;
    }

}
