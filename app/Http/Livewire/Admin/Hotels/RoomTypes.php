<?php

namespace App\Http\Livewire\Admin\Hotels;

use App\Models\RoomType;
use Livewire\Component;

class RoomTypes extends Component
{
    public $showForm = false;
    public $form;
    public $types = [];

    public function render()
    {
        $this->types = RoomType::all();
        return view('livewire.admin.hotels.room-types')->layout('layouts.admin');
    }

    public function add()
    {
        $this->showForm = true;
    }

    public function edit($id)
    {
        $this->form = RoomType::query()->find($id)->toArray();
        $this->showForm = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'form.name' => 'required',
            'form.adult' => 'required',
            'form.infant' => 'required',
            'form.child' => 'required',
        ]);
        if(isset($this->form['id'])) {
            RoomType::query()->find($this->form['id'])->update([
                'name' => $validated['form']['name'],
                'adult' => $validated['form']['adult'],
                'infant' => $validated['form']['infant'],
                'child' => $validated['form']['child']
            ]);
        } else {
            try {
                RoomType::query()->create([
                    'name'      => $validated['form']['name'],
                    'capacity'  => (int) $validated['form']['adult'] + (int) $validated['form']['infant'] + (int) $validated['form']['child'],
                    'adult'     => $validated['form']['adult'],
                    'infant'    => $validated['form']['infant'],
                    'child'     => $validated['form']['child']
                ]);
            } catch(\Exception $e) {

            }
        }
        $this->showForm = false;
        $this->form     = [];
    }

    public function delete($id)
    {
        RoomType::query()->find($id)->delete();
    }
}
