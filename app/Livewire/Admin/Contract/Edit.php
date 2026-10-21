<?php

namespace App\Livewire\Admin\Contract;

use App\Models\Contract;
use Livewire\Component;

class Edit extends Component
{
    public Contract $contract;

    public function mount($id)
    {
        $this->contract = Contract::find($id);
    }

    public function render()
    {
        return view('livewire.admin.contract.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();

        $this->contract->save();

        return redirect()->route('admin.contracts.index');
    }

    protected function rules(): array
    {
        return [
            'contract.name' => [
                'string',
                'required',
            ],
            'contract.content' => [
                'string',
                'required',
            ],
        ];
    }
}
