<?php

namespace App\Livewire\Admin\WhiteLabel;

use App\Livewire\WithConfirmation;
use App\Models\Agency;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithConfirmation;
    public $agencies;
    public $editing = null;
    public $balance = 0;

    public function mount()
    {
        $this->agencies = Agency::get();
    }

    public function render()
    {
        return view('livewire.admin.whitelabel.index')->layout('layouts.admin');
    }

    public function edit($id)
    {
        $this->editing = $id;
        $this->balance = $this->agencies->where('id', $id)->first()->balance;
    }

    public function update()
    {
        Agency::where('id', $this->editing)->update([
            'balance' => $this->balance
        ]);
        $this->balance = 0;
        $this->editing = null;
        $this->mount();
    }

    public function deactivate($id)
    {
        Agency::where('id', $id)
            ->update([
                'status' => 0,
            ]);
        User::where('agency_id', $id)->update([
            'active'        => 0,
            'is_approved'   => 0
        ]);
        $this->mount();
    }

    public function activate($id)
    {
        Agency::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        User::where('agency_id', $id)->update([
            'active'        => 1,
            'is_approved'   => 1
        ]);
        $this->mount();
    }


}
