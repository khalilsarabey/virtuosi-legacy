<?php

namespace App\Http\Livewire\Admin\Connections;

use App\Models\Connection;
use Livewire\Component;

class Index extends Component
{
    public $connections = [];
    public function render()
    {
        $this->connections = Connection::query()->get();
        return view('livewire.admin.connections.index')->layout('layouts.admin');
    }
}
