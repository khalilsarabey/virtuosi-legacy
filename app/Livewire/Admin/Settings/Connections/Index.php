<?php

namespace App\Livewire\Admin\Settings\Connections;

use App\Models\Connection;
use Livewire\Component;

class Index extends Component
{
    public $connections;
    public function mount()
    {
        $this->connections = Connection::get();
    }
    public function render()
    {
        return view('livewire.admin.settings.connections.index')->layout('layouts.admin');
    }

    public function update($id)
    {
        $connection = $this->connections->find($id);
        $connection->update([
            'active' => $connection->active ? 0 : 1
        ]);
        \Cache::forget('connections');
        \Cache::forget('search');
    }
}
