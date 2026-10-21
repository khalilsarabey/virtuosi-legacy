<?php

namespace App\Http\Livewire\Admin\Hotels;

use App\Models\Feature;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\User;
use App\Traits\UploadTrait;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{
    use UploadTrait;
    public Hotel $hotel;
    public array $users = [];
    public array $pricing = [];
    public array $listsForFields = [];
    public array $locations = [];
    public $extras = [
        'bb'                => 0,
        'fb'                => 0,
        'ai'                => 0,
        'sai'               => 0,
        'uai'               => 0
    ];
    public array $selectedFeatures = [];
    public $features = [];

    public function mount(Hotel $hotel)
    {
        $this->hotel = $hotel;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.hotels.create')->layout('layouts.admin', ['title' => 'Create Hotel']);
    }

    public function submit()
    {
        $this->validate();
        foreach($this->extras as $key => $extra) {
            if($extra != 1) {
                $this->hotel->$key = null;
            }
        }
        $this->hotel->save();
        $this->syncMedia();
        $this->hotel->features()->sync($this->selectedFeatures);
        return redirect()->route('admin.hotels.index');
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->hotel->id]));
        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [
            'hotel.name'            => 'required',
            'hotel.rating'          => 'nullable',
            'hotel.active'          => 'nullable',
            'hotel.check_in'        => 'required',
            'hotel.check_out'       => 'required',
            'hotel.confirmation'    => 'nullable',
            'hotel.location'        => 'required',
            'hotel.address'         => 'nullable',
            'hotel.description'     => 'nullable',
            'hotel.bb'              => 'nullable',
            'hotel.hb'              => 'nullable',
            'hotel.fb'              => 'nullable',
            'hotel.ai'              => 'nullable',
            'hotel.sai'             => 'nullable',
            'hotel.uai'             => 'nullable',
            'hotel.agencies_permissions' => 'nullable',
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
