<?php

namespace App\Http\Livewire\Admin\Blocks;

use App\Models\Block;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CreateSub extends Component
{
    public Block $block;

    public $parent;

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

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->block->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function mount($block = null)
    {
        $this->parent             = $block;
        $this->block              = new Block;
        $this->block->parent_id   = $block->id;
        $this->block->agency_id   = auth()->user()->agency_id;
        $this->block->active      = false;
        $this->block->hide_title  = false;
    }

    public function render()
    {
        return view('livewire.admin.blocks.create-sub')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->block->save();
            $this->syncMedia();
        } catch(\Exception $e) {
            die($e->getMessage());
        }

        return redirect()->route('admin.blocks.index');
    }

    protected function rules(): array
    {
        return [
            'block.name' => [
                'string',
                'required',
            ],
            'block.link' => [
                'string',
                'nullable',
            ],
            'block.parent_id' => [
                'nullable',
            ],
            'block.agency_id' => [
                'nullable',
            ],
            'block.active' => [
                'boolean',
            ],
            'block.hide_title' => [
                'boolean',
            ],
            'mediaCollections.block_thumbnail' => [
                'array',
                'required',
            ],
            'mediaCollections.block_thumbnail.*.id' => [
                'integer',
                'exists:media,id',
            ]
        ];
    }

    public function initListsForFields()
    {
        $this->listsForFields['users'] = User::where('agency_id', auth()->user()->agency_id)->pluck('name', 'email')->toArray();
    }

}
