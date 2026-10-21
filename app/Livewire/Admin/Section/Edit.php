<?php

namespace App\Livewire\Admin\Section;

use App\Models\Agency;
use App\Models\Section;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    public Section $section;

    public array $mediaToRemove = [];

    public array $listsForFields = [];

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
        $this->section = Section::find($id);
        $this->initListsForFields();
        $this->mediaCollections = [
            'section_banner' => $this->section->banner,
        ];
    }

    public function render()
    {
        return view('livewire.admin.section.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->section->save();
        $this->syncMedia();
        return redirect()->route('admin.sections.index');
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->section->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [
            'mediaCollections.section_image' => [
                'array',
                'nullable',
            ],
            'mediaCollections.section_image.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'mediaCollections.section_banner' => [
                'array',
                'nullable',
            ],
            'mediaCollections.section_banner.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'section.name' => [
                'string',
                'required',
            ],
            'section.layout' => [
                'required',
            ],
            'section.url' => [
                'string',
                'nullable',
            ],

            'section.type' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['type'])),
            ],
            'section.active' => [
                'boolean',
            ],
            'section.agencies_permissions' => [
                'nullable'
            ],
            'section.whitelabel_permissions' => [
                'nullable'
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['users']              = User::orderBy('name')->pluck('name', 'email')->toArray();
        $this->listsForFields['whitelabels']        = Agency::orderBy('name')->pluck('name', 'id')->toArray();
        $this->listsForFields['type'] = $this->section::TYPE_SELECT;
    }
}
