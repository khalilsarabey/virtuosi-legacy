<?php

namespace App\Http\Livewire\Admin\Hotels;

use App\Models\Feature;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\User;
use App\Traits\UploadTrait;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    use UploadTrait;
    public Hotel $hotel;
    public $users = [];
    public array $selectedFeatures = [];
    public mixed $features;
    public array $locations;
    public $extras = [];
    public function mount(Hotel $hotel)
    {
        $this->hotel = $hotel;
        $this->extras = [
            'hb'                => $this->hotel->hb > 1 ? 1 : 0,
            'bb'                => $this->hotel->bb > 1 ? 1 : 0,
            'fb'                => $this->hotel->fb > 1 ? 1 : 0,
            'ai'                => $this->hotel->ai > 1 ? 1 : 0,
            'sai'               => $this->hotel->sai > 1 ? 1 : 0,
            'uai'               => $this->hotel->uai > 1 ? 1 : 0
        ];
        $this->selectedFeatures = $this->hotel->features()->pluck('feature_id')->toArray();
        $this->mediaCollections = [
            'hotel_gallery' => $this->hotel->gallery,
            'hotel_logo' => $this->hotel->logo,
        ];
        $this->initListsForFields();
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->hotel->id]));
        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }


    public function render()
    {
        return view('livewire.admin.hotels.edit')->layout('layouts.admin', ['title' => 'Edit Hotel']);
    }

    public function submit()
    {
        $this->validate();
        $this->hotel->save();
        $this->hotel->features()->sync($this->selectedFeatures);
        $this->syncMedia();
        return redirect()->route('admin.hotels.index');
    }

    protected function rules(): array
    {
        return [
            'hotel.name'        => 'required',
            'hotel.rating'      => 'nullable',
            'hotel.currency'    => 'required',
            'hotel.active'      => 'required',
            'hotel.check_in'    => 'required',
            'hotel.check_out'   => 'required',
            'hotel.confirmation'=> 'nullable',
            'hotel.location'    => 'required',
            'hotel.address'     => 'nullable',
            'hotel.description' => 'nullable',
            'hotel.bb'          => 'nullable',
            'hotel.hb'          => 'nullable',
            'hotel.fb'          => 'nullable',
            'hotel.ai'          => 'nullable',
            'hotel.sai'         => 'nullable',
            'hotel.uai'         => 'nullable',
            'hotel.agencies_permissions' => [
                'nullable'
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $locations = Location::where('active', 1)->pluck('city', 'city_code')->toArray();
        $features = Feature::query()->where('type', 'hotel')->whereNull('parent_id')->with('children')->get();
        $this->users     = User::orderBy('name')->pluck('name', 'email')->toArray();
        $this->locations = $locations;
        $this->features = $features;
    }
}
