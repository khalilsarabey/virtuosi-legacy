<?php

namespace App\Http\Livewire\Admin\Airline;

use App\Models\Airline;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;
    public $file;
    public Airline $airline;

    public function mount(Airline $airline)
    {
        $this->airline = $airline;
    }

    public function render()
    {
        return view('livewire.admin.airline.create')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        if($this->file) {
            $file = $this->file->store('img/airlines', 'public');
            $this->airline->thumbnail = str_replace("img/airlines/", "", $file);
        }
        $this->airline->save();
        return redirect()->route('admin.airlines.index');
    }

    protected function rules(): array
    {
        return [
            'airline.name' => [
                'string',
                'required',
            ],
            'airline.iata' => [
                'string',
                'max:2',
                'nullable',
            ],
            'airline.icao' => [
                'string',
                'max:3',
                'nullable',
            ],
            'airline.sign' => [
                'string',
                'nullable',
            ],
            'airline.country' => [
                'string',
                'required',
            ],
            'airline.thumbnail' => []
        ];
    }
}
