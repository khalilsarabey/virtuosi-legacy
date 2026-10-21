<?php

namespace App\Livewire\Admin\Update;

use App\Models\Update;
use Livewire\Component;

class Create extends Component
{
    public Update $update;

    public function mount(Update $update)
    {
        $this->update = $update;
    }

    public function render()
    {
        return view('livewire.admin.update.create')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->update->agency_id = get_agency_id();
        $this->update->save();

        return redirect()->route('admin.updates.index');
    }

    protected function rules(): array
    {
        return [
            'update.text' => [
                'string',
                'required',
            ],
        ];
    }
}
