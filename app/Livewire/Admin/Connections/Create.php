<?php

namespace App\Livewire\Admin\Connections;

use App\Models\Connection;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Create extends Component
{
    public $connection = [];

    public function mount()
    {
        $this->connection = [
            'name' => '',
            'host' => '',
            'email' => '',
            'password' => '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.connections.create')->layout('layouts.admin');
    }

    public function connect()
    {
        $this->validate([
            'connection.name' => 'required',
            'connection.host' => 'required|unique:connections,host',
            'connection.email' => 'required',
            'connection.password' => 'required',
        ]);
        $response = Http::post(rtrim($this->connection['host'], '/') . '/api/login', [
            'email' => $this->connection['email'],
            'password' => $this->connection['password']
        ]);
        if ($response->successful()) {
            $data = json_decode($response->body())->data;
            $connection = new Connection;
            $connection->name       = $this->connection['name'];
            $connection->host       = $this->connection['host'];
            $connection->email      = $this->connection['email'];
            $connection->user_id    = $data->user->id;
            $connection->balance    = $data->user->balance;
            $connection->token      = $data->access_token;
            $connection->save();
            return redirect()->route('admin.connections.index');
        } else {
            $this->addError('connection.host', 'Connection failed');
        }
    }
}
