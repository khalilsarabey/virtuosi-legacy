<?php

namespace App\Livewire\Admin\User;

use App\Livewire\WithConfirmation;
use App\Livewire\WithSorting;
use App\Models\User;
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
        $this->orderable         = (new User())->orderable;
    }

    public function render()
    {
        $query = User::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);
        if(auth()->user()->agency_id == 1) {
            $query->where('type', '!=', 'white');
        } else {
            $query->where('agency_id', auth()->user()->agency_id);
        }
        $users = $query->paginate($this->perPage);

        return view('livewire.admin.user.index', compact('query', 'users'))->layout('layouts.admin');
    }

    public function deleteSelected()
    {

        User::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(User $user)
    {
        $user->delete();
    }
}
