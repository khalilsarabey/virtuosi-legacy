<?php

namespace App\Http\Livewire\Admin\WhiteLabel;

use App\Http\Livewire\WithConfirmation;
use App\Models\Agency;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithConfirmation;
    public $agencies;
    public $edit = null;
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
        $this->edit = $id;
        $this->balance = $this->agencies->where('id', $id)->first()->balance;
    }

    public function update()
    {
        Agency::where('id', $this->edit)->update([
            'balance' => $this->balance
        ]);
        $this->balance = 0;
        $this->edit = null;
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
