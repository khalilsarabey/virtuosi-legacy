<?php

namespace App\Http\Livewire\Admin\Airline;

use App\Models\Airline;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    public Airline $airline;

    public array $mediaToRemove = [];

    public array $mediaCollections = [];

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect($this->mediaCollections[$media['collection_name']]);

        $this->mediaCollections[$media['collection_name']] = $collection->reject(fn ($item) => $item['uuid'] === $media['uuid'])->toArray();

        $this->mediaToRemove[] = $media['uuid'];
    }

    public function getMediaCollection($name)
    {
        return $this->mediaCollections[$name];
    }

    public function mount($id)
    {
        $this->airline          = Airline::find($id);
        $this->mediaCollections = [
            'airline_image' => $this->airline->image,
        ];
    }

    public function render()
    {
        return view('livewire.admin.airline.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
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
            'airline.combinable' => [
                'boolean',
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
        ];
    }
}
