<?php

namespace App\Http\Livewire\Admin\Blocks;

use App\Models\Block;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{
    public Block $block;

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

    public function mount(Block $block)
    {
        $this->block             = $block;
        $this->block->position   = '0';
        $this->block->layout     = '1';
        $this->block->hide_title = false;
        $this->block->active = false;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.blocks.create')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->block->agency_id = auth()->user()->agency_id;
        $this->block->save();
        $this->syncMedia();

        return redirect()->route('admin.blocks.index');
    }

    protected function rules(): array
    {
        return [
            'block.name' => [
                'string',
                'required',
            ],
            'block.active' => [
                'boolean',
            ],
            'block.position' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'block.layout' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['layout'])),
            ],
            'block.parent_id' => [
                'integer',
                'exists:blocks,id',
                'nullable',
            ],
            'mediaCollections.block_image' => [
                'array',
                'nullable',
            ],
            'mediaCollections.block_image.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'block.hide_title' => [
                'boolean',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['layout'] = $this->block::LAYOUT_SELECT;
        $this->listsForFields['parent'] = Block::where('agency_id', auth()->user()->agency_id)->pluck('name', 'id')->toArray();
    }
}
