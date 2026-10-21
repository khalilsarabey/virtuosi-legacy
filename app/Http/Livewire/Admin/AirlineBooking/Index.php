<?php

namespace App\Http\Livewire\Admin\AirlineBooking;

use App\Models\AirlineBooking;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $locations = [];
    protected $paginationTheme = 'bootstrap';
    public $filters = [];
    public $agencies;
    public $selected = [];

    public function mount()
    {
        if(auth()->user()->type != 'admin' && auth()->user()->type != 'user' && auth()->user()->type != 'accounting') {
            abort(403);
        }
        $this->filters = [
            'created_at'    => null,
            'pnr'           => null,
            'user_id'       => null,
            'airline'       => null,
            'from'          => null,
            'to'            => null,
            'status'        => null
        ];
        $this->agencies      = User::orderBy('name')->where('type', '!=', 'white-admin')->where('type', '!=', 'white')->pluck('name', 'id')->toArray();
    }

    public function render()
    {
        $query = AirlineBooking::query();
        if(!empty($this->filters['airline'])) {
            $query->where('airline', $this->filters['airline']);
        }
        if(!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }
        if(!empty($this->filters['from'])) {
            $query->where('dep_from', $this->filters['from']);
        }
        if(!empty($this->filters['to'])) {
            $query->where('dep_to', $this->filters['to']);
        }
        if(!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if(!empty($this->filters['dep_from_date'])) {
            $query->whereDate('dep_from_date', '=', $this->filters['dep_from_date']);
        }
        if(!empty($this->filters['created_at'])) {
            $query->whereDate('created_at', '=', $this->filters['created_at']);
        }
        $query->orderBy('created_at', 'DESC');
        $bookings = $query->paginate(20);
        return view('livewire.admin.airline-booking.index', compact('bookings'))->layout('layouts.admin');
    }

    public function search()
    {

    }

    public function confirmSelected()
    {
        AirlineBooking::whereIn('id', $this->selected)->update(['status' => 'confirmed']);
        $this->selected = [];
        $this->render();
    }

    public function deleteSelected()
    {
        AirlineBooking::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->render();
    }
}
