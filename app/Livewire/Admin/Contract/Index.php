<?php

namespace App\Livewire\Admin\Contract;

use App\Livewire\WithConfirmation;
use App\Livewire\WithSorting;
use App\Models\Contract;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;
    protected $paginationTheme = 'bootstrap';
    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
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
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 50;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Contract())->orderable;
    }

    public function render()
    {
        $query = Contract::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $contracts = $query->paginate($this->perPage);

        return view('livewire.admin.contract.index', compact('contracts', 'query'))->layout('layouts.admin');
    }

    public function deleteSelected()
    {
        Contract::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Contract $contract)
    {
        $contract->delete();
    }
}
