<?php

namespace App\Livewire\Admin\Update;

use App\Models\Update;
use Livewire\Component;

class Edit extends Component
{
    public Update $update;

    public function mount($id)
    {
        $this->update = Update::where('id', $id)->where('agency_id', get_agency_id())->first();
    }

    public function render()
    {
        return view('livewire.admin.update.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->update->agency_id = auth()->user()->agency_id;
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
