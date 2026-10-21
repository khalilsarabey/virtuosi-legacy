<?php

namespace App\Livewire\Admin\Hotels\Seasons;

use App\Models\Season;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $editingSeason = false;
    public $creatingSeason = false;
    public $form = [];

    public function render()
    {
        return view('livewire.admin.hotels.seasons.index', [
            'seasons' => Season::query()->paginate(20)
        ])
            ->layout('layouts.admin');
    }

    public function createSeason()
    {
        $this->form = [
            'name'          => null,
            'start_date'    => null,
            'end_date'      => null,
            'active'        => 1
        ];
        $this->creatingSeason = true;
    }

    public function editSeason($id)
    {
        $season = Season::query()->findOrFail($id);
        $this->form = [
            'name'          => $season->name,
            'start_date'    => $season->start_date,
            'end_date'      => $season->end_date,
            'active'        => $season->active,
            'id'            => $season->id
        ];
        $this->editingSeason = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'form.name'          => ['required'],
            'form.start_date'    => ['required', 'date'],
            'form.end_date'      => ['required', 'date'],
            'form.active'        => ['nullable'],
            'form.id'            => ['nullable'],
        ]);
        $id = null;
        if(array_key_exists('id', $validated['form'])) {
            $id = $validated['form']['id'];
            unset($validated['form']['id']);
        }
        try {
            if($id) {
                Season::query()->find($id)->update([
                    'name' => $validated['form']['name'],
                    'start_date' => $validated['form']['start_date'],
                    'end_date' => $validated['form']['end_date'],
                    'active' => $validated['form']['active'],
                ]);
            } else {
                Season::query()->create([
                    'name' => $validated['form']['name'],
                    'start_date' => $validated['form']['start_date'],
                    'end_date' => $validated['form']['end_date'],
                    'active' => $validated['form']['active'],
                ]);
            }
            $this->form         = [];
            $this->editingSeason   = false;
            $this->creatingSeason = false;
        } catch (\Exception $e) {

        }
    }

    public function delete($id)
    {
        Season::query()->find($id)->delete();
    }
}
