<?php

namespace App\Livewire\Admin\Settings\Connections;

use App\Models\Connection;
use Http;
use Livewire\Component;

class Create extends Component
{
    public $validated = false;
    public $data = [
        'name'      => null,
        'email'     => null,
        'password'  => null,
        'host'       => null,
        'token'     => null
    ];

    public function render()
    {
        return view('livewire.admin.settings.connections.create')->layout('layouts.admin');
    }

    public function validateConnection()
    {
        $d = $this->validate([
            'data.name'     => 'required',
            'data.email'    => 'required|email',
            'data.host'      => 'required|url|ends_with:/', //'required|url|starts_with:https://|ends_with:/',
            'data.password' => 'required'
        ], [
            'data.name.required'     => 'The name field is required.',
            'data.email.required'    => 'The email field is required.',
            'data.email.email'       => 'Please enter a valid email address.',
            'data.host.required'      => 'The URL field is required.',
            'data.host.url'           => 'Please enter a valid URL.',
            'data.host.starts_with'   => 'The URL must start with "https://"',
            'data.host.ends_with'     => 'The URL must end with "/"',
            'data.password.required' => 'The password field is required.'
        ]);

        try {
            $response = \Illuminate\Support\Facades\Http::post(rtrim($this->data['host'], '/') . '/api/login', [
                'email'     => $this->data['email'],
                'password'  => $this->data['password']
            ]);
            $data = json_decode($response->body())->data;
            $connection = new Connection;
            $connection->name       = $this->data['name'];
            $connection->host       = $this->data['host'];
            $connection->email      = $this->data['email'];
            $connection->user_id    = $data->user->id;
            $connection->active     = 1;
            $connection->balance    = $data->user->balance;
            $connection->token      = $data->access_token;
            $connection->save();
            \Cache::forget('connections');
            \Cache::forget('search');
            return redirect()->route('admin.connections.index');
        } catch(\Exception $exception) {
            $this->addError('connection.host', $exception->getMessage());
        }
    }

}
