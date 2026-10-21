<?php

namespace App\Http\Livewire\Admin\Settings\Agencies;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public $agency;
    public $notes;
    public function mount(User $id)
    {
        $this->agency = $id;
        $this->notes = $this->agency->notes;
    }

    public function render()
    {
        return view('livewire.admin.settings.agencies.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->agency->notes = $this->notes;
        $this->agency->save();
        return redirect()->route('admin.settings.auto-pricing.agencies.index');
    }
}
