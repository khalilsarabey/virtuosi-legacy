<?php

namespace App\Http\Livewire\Admin\Update;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Update;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithConfirmation;

    public function mount()
    {

    }

    public function render()
    {
        $updates = Update::where('agency_id', get_agency_id())->get();
        return view('livewire.admin.update.index', compact( 'updates'))->layout('layouts.admin');
    }

    public function delete(Update $update)
    {
        $update->delete();
    }
}
