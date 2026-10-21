<?php

namespace App\Livewire\Admin\Integrations;

use App\Http\Clients\Clients;
use App\Models\Integration;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Livewire\Component;

class Create extends Component
{
    public string $name;
    public string $host;
    public string $email;
    public string $password;
    public string $response;
    public string $type;

    public function mount()
    {
        $this->type = 'local';
    }
    public function render()
    {
        return view('livewire.admin.integrations.create')->layout('layouts.admin');
    }

    public function connect()
    {
        $this->validateHost();
        $results = Clients::connect([
            'type'      => $this->type,
            'host'      => $this->host,
            'email'     => $this->email,
            'password'  => $this->password
        ]);
        if(is_object($results)) {
            $res = json_decode($results);
            $params = json_encode($res->data);

            $integration = new Integration;
            $integration->name = $this->name;
            $integration->type = $this->type;
            $integration->host = $this->host;
            $integration->params = $params;
            $integration->save();
            session()->flash('success', 'New Integration has been added.');
            return redirect()->route('admin.integrations.index');
        } else {
            $this->error = 'An error has occurred.';
        }
    }

    public function validateHost()
    {
        if($this->host[-1] != '/') {
            $this->host = $this->host . '/';
        }
    }
}
