<?php

namespace App\Livewire\Admin\Integrations;

use App\Models\Integration;
use Livewire\Component;

class Index extends Component
{
    public $integrations = [];

    public function render()
    {
        $this->integrations = Integration::get();
        return view('livewire.admin.integrations.index')->layout('layouts.admin');
    }

    public function add()
    {

    }
}
