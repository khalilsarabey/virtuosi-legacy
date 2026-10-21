<?php

namespace App\Livewire\Admin\Location;

use App\Models\Location;
use Livewire\Component;

class Edit extends Component
{
    public Location $location;

    public function mount($id)
    {
        $this->location = Location::find($id);
    }

    public function render()
    {
        return view('livewire.admin.location.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();

        $this->location->save();

        return redirect()->route('admin.locations.index');
    }

    protected function rules(): array
    {
        return [
            'location.airport' => [
                'string',
                'required',
            ],
            'location.airport_code' => [
                'string',
                'required',
            ],
            'location.city' => [
                'string',
                'required',
            ],
            'location.city_code' => [
                'string',
                'required',
            ],
            'location.country' => [
                'string',
                'nullable',
            ],
            'location.country_code' => [
                'string',
                'required',
            ],
            'location.timezone' => [
                'string',
                'required',
            ],
            'location.active' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
        ];
    }
}
