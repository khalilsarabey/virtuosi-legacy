<?php

namespace App\Http\Livewire\Admin\Location;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Location;
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
    protected $paginationTheme = 'bootstrap';
    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'active',
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
        $this->sortBy            = 'active';
        $this->sortDirection     = 'desc';
        $this->perPage           = 50;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Location())->orderable;
    }

    public function render()
    {
        $query = Location::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $locations = $query->paginate($this->perPage);

        return view('livewire.admin.location.index', compact('locations', 'query'))->layout('layouts.admin');
    }

    public function deleteSelected()
    {
        Location::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function delete(Location $location)
    {
        $location->delete();
    }
}
