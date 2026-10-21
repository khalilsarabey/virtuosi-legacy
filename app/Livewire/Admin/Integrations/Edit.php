<?php

namespace App\Livewire\Admin\Integrations;

use App\Models\Integration;
use Livewire\Component;

class Edit extends Component
{
    public Integration $integration;
    public function mount(Integration $id)
    {
        $this->integration = $id;
    }
    public function render()
    {
        return view('livewire.admin.integrations.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->integration->save();
        return redirect()->route('admin.integrations.index');
    }

    protected function rules(): array
    {
        return [
            'integration.balance' => [
                'required',
            ]
        ];
    }
}
