<?php

namespace App\Http\Livewire\Admin\WhiteLabel;

use App\Models\Agency;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public $agency;
    public $user;

    public function mount($id)
    {
        $this->agency = Agency::where('id', $id)->first();
        $this->user = $this->agency->admin;
    }

    public function render()
    {
        return view('livewire.admin.whitelabel.edit', [
            'agency' => $this->agency
        ])->layout('layouts.admin');
    }

    public function save()
    {
        $this->validate();
    }

}
