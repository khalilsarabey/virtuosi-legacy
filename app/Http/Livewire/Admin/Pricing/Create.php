<?php

namespace App\Http\Livewire\Admin\Pricing;

use Livewire\Component;

class Create extends Component
{
    public function mount($id)
    {

    }
    public function render()
    {
        return view('livewire.admin.pricing.create')->layout('layouts.admin');
    }
}
