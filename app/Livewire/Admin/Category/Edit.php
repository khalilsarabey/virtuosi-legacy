<?php

namespace App\Livewire\Admin\Category;

use App\Models\Agency;
use App\Models\Category;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    public Category $category;
    public $type;
    public array $mediaToRemove = [];

    public array $listsForFields = [];

    public array $mediaCollections = [];
    public $sections = [];


    public function updated($k, $v)
    {
        if($k == 'category.section_id') {
            $this->type = Section::where('id', $v)->first()->type;
        }
    }


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
        $this->category = Category::where('slug', $id)->with('section')->firstOrFail();
        $this->type = $this->category->section->type;
        $this->mediaCollections = [
            'category_image' => collect($this->category->image)->toArray(),
            'category_banner' => collect($this->category->banner)->toArray(),
        ];
        $this->sections = Section::pluck('name', 'id')->toArray();
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.category.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->category->slug = Str::slug($this->category->name, '-');
        $this->validate();
        $this->category->save();
        $this->syncMedia();
        return redirect()->route('admin.categories.index');
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
            ->update(['model_id' => $this->category->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [
            'category.name' => [
                'string',
                'nullable',
            ],
            'category.link' => [
                'url',
                'nullable',
            ],
            'category.show_title' => [
                'required'
            ],
            'category.slug' => [
                'string',
                'nullable',
                'unique:categories,slug,' . $this->category->id,
            ],
            'category.active' => [
                'required'
            ],
            'category.section_id' => [
                'nullable',
            ],
            'category.agencies_permissions' => [
                'nullable'
            ],
            'category.whitelabel_permissions' => [
                'nullable'
            ],
            'mediaCollections.category_image' => [
                'array',
                'nullable',
            ],
            'mediaCollections.category_image.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'mediaCollections.category_banner' => [
                'array',
                'nullable',
            ],
            'mediaCollections.category_banner.*.id' => [
                'integer',
                'exists:media,id',
            ],
        ];
    }
    protected function initListsForFields(): void
    {
        $this->listsForFields['users']              = User::orderBy('name')->pluck('name', 'email')->toArray();
        $this->listsForFields['whitelabels']        = Agency::orderBy('name')->pluck('name', 'id')->toArray();
    }
}
