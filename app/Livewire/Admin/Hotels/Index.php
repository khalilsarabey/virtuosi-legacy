<?php

namespace App\Livewire\Admin\Hotels;

use App\Models\Feature;
use App\Models\Hotel;
use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filters = [];
    public array $locations = [];

    public function mount()
    {
        $this->initListsForFields();
    }

    public function render()
    {
        $query = Hotel::with(['city']);
        if(isset($this->filters['name'])) {
            $query->where('name', 'like', '%' . $this->filters['name'] . '%');
        }
        if(isset($this->filters['location'])) {
            $query->where('location', $this->filters['location']);
        }
        if(isset($this->filters['status'])) {
            $query->where('active', $this->filters['status']);
        }
        $hotels = $query->paginate(20);
        return view('livewire.admin.hotels.index', compact('hotels'))->layout('layouts.admin', ['title' => 'Hotels']);
    }

    public function search()
    {

    }

    protected function initListsForFields(): void
    {
        $locations = Location::where('active', 1)->pluck('city', 'city_code')->toArray();
        $this->locations = $locations;
    }
}
