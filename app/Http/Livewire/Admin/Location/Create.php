<?php

namespace App\Http\Livewire\Admin\Location;

use App\Models\Location;
use Livewire\Component;

class Create extends Component
{
    public Location $location;

    public function mount(Location $location)
    {
        $this->location         = $location;
        $this->location->active = '0';
    }

    public function render()
    {
        return view('livewire.admin.location.create')->layout('layouts.admin');
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
            'location.city' => [
                'string',
                'required',
            ],
            'location.airport_code' => [
                'string',
                'nullable',
            ],
            'location.city_code' => [
                'string',
                'nullable',
            ],
            'location.timezone' => [
                'string',
                'nullable',
            ],
            'location.country' => [
                'string',
                'nullable',
            ],
            'location.country_code' => [
                'string',
                'nullable',
            ],
            'location.active' => [
                'nullable',
            ],
        ];
    }
}
