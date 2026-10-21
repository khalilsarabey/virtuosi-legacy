<?php

namespace App\Livewire\Admin\Contract;

use App\Models\Contract;
use Livewire\Component;

class Create extends Component
{
    public Contract $contract;

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function render()
    {
        return view('livewire.admin.contract.create')->layout('layouts.admin');
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
