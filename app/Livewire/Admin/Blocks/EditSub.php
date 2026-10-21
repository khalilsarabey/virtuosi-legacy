<?php

namespace App\Livewire\Admin\Blocks;

use App\Models\Block;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EditSub extends Component
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


    public function getMediaCollection($name)
    {
        return $this->mediaCollections[$name];
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->block->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function mount($id = null)
    {
        $this->block = Block::where([
            'id' => $id,
            'agency_id' => auth()->user()->agency_id
        ])->firstOrFail();
        $this->mediaCollections = [
            'block_thumbnail' => $this->block->thumbnail
        ];
    }

    public function render()
    {
        return view('livewire.admin.blocks.edit-sub')->layout('layouts.admin');
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
