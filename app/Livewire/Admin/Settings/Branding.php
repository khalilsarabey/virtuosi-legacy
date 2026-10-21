<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Agency;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Branding extends Component
{
    use WithFileUploads;

    public $agency;
    public $properties = [];

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

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->agency->id]));
        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function mount()
    {
        $this->agency = Agency::where('id', auth()->user()->agency_id)->first();
        $this->mediaCollections = [
            'agency_logo'      => collect($this->agency->logo)->toArray(),
            'agency_login_bg'  => collect($this->agency->login_bg)->toArray(),
            'agency_search_bg' => collect($this->agency->search_bg)->toArray(),
            'agency_print_logo' => collect($this->agency->print_logo)->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings.branding')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->agency->save();
        $this->syncMedia();
        Cache::forget('settings');
        session()->flash('message', 'Branding successfully updated.');
        return redirect()->route('admin.settings.branding');
    }

    protected $rules = [
        'mediaCollections.agency_logo' => [
            'array',
            'required',
        ],
        'mediaCollections.agency_logo.*.id' => [
            'integer',
            'exists:media,id',
        ],
        'mediaCollections.agency_login_bg' => [
            'array',
            'required',
        ],
        'mediaCollections.agency_login_bg.*.id' => [
            'integer',
            'exists:media,id',
        ],
        'mediaCollections.agency_search_bg' => [
            'array',
            'required',
        ],
        'mediaCollections.agency_search_bg.*.id' => [
            'integer',
            'exists:media,id',
        ],
        'mediaCollections.agency_print_logo' => [
            'array',
            'required',
        ],
        'mediaCollections.agency_print_logo.*.id' => [
            'integer',
            'exists:media,id',
        ],
        'agency.name'               => 'required|min:6',
        'agency.alert'              => 'nullable',
        'agency.domain'             => 'required|min:3',
        'agency.email'              => 'required|email|min:6',
        'agency.footer_quote'       => 'required|min:6',
        'agency.primary_color'      => 'required|min:7|max:7',
        'agency.secondary_color1'   => 'required|min:7|max:7',
        'agency.color_footer_text'  => 'required|min:7|max:7',
        'agency.color_footer_icons_color' => 'required|min:7|max:7',
        'agency.color_footer_icons_bg' => 'required|min:7|max:7',
        'agency.color_footer_bg_light'   => 'nullable|min:7|max:7',
        'agency.color_footer_bg'   => 'nullable|min:7|max:7',
        'agency.color_body_bg'   => 'nullable|min:7|max:7',
        'agency.color_topbar_bg'   => 'nullable|min:7|max:7',
        'agency.color_top_menu'   => 'nullable|min:7|max:7',
        'agency.color_success'   => 'nullable|min:7|max:7',
        'agency.color_info'   => 'nullable|min:7|max:7',
        'agency.color_warning'   => 'nullable|min:7|max:7',
        'agency.color_danger'   => 'nullable|min:7|max:7',
        'agency.color_light'   => 'nullable|min:7|max:7',
    ];
}
