<?php

namespace App\Livewire\Admin\Hotels;

use App\Models\Feature;
use Livewire\Component;
use Livewire\WithPagination;

class Features extends Component
{
    use WithPagination;
    public $type = null;
    public $showForm = false;
    public $form = [];
    public $action = null;

    public function mount()
    {

    }

    public function render()
    {
        $features = Feature::query();
        $features->whereNull('parent_id');
        if($this->type)
        {
            $features->where('type', $this->type);
        }
        $features->with('children');
        $features->orderBy('created_at', 'desc');
        return view('livewire.admin.hotels.features', [
            'features_data' => $features->get()
        ])
            ->layout('layouts.admin', ['title' => 'Features']);
    }

    public function add()
    {
        $this->form = [
            'type' => 'hotel',
            'title' => null
        ];
        $this->action = 'add';
        $this->showForm = true;
    }

    public function addOption($id)
    {
        $this->form = [
            'type' => 'hotel',
            'title' => null,
            'parent_id' => $id
        ];
        $this->action = 'addOption';
        $this->showForm = true;
    }

    public function edit($edit)
    {
        $feature = Feature::find($edit);
        $this->form = [
            'type' => $feature->type,
            'title' => $feature->title,
            'id' => $feature->id
        ];
        $this->action = 'edit';
        $this->showForm = true;
    }

    public function editOption($edit)
    {
        $feature = Feature::find($edit);
        $this->form = [
            'type' => $feature->type,
            'title' => $feature->title,
            'id' => $feature->id
        ];
        $this->action = 'editOption';
        $this->showForm = true;
    }
    public function delete($id)
    {
        Feature::find($id)->delete();
        $this->showForm = false;
    }

    public function save()
    {
        if($this->action == 'add') {
            $this->validate([
                'form.type' => 'required',
                'form.title' => 'required',
            ]);
            Feature::create($this->form);
        }
        if($this->action == 'addOption') {
            $this->validate([
                'form.type' => 'required',
                'form.title' => 'required',
                'form.parent_id' => 'required'
            ]);
            Feature::create($this->form);
        }
        if($this->action == 'edit') {
            $this->validate([
                'form.id'   => 'required',
                'form.type' => 'required',
                'form.title' => 'required',
            ]);
            Feature::find($this->form['id'])->update($this->form);
        }
        if($this->action == 'editOption') {
            $this->validate([
                'form.id'   => 'required',
                'form.type' => 'required',
                'form.title' => 'required'
            ]);
            Feature::find($this->form['id'])->update($this->form);
        }
        $this->showForm = false;
        $this->action = null;
    }
}
